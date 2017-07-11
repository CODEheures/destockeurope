<?php

namespace App\Common;


use App\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\File;

class PicturesManager
{
    Const DISK_LOCAL = 'local';
    Const CONFIG_LOCAL_ROOT_PATH = 'filesystems.disks.local.root';

    Const DISK_DISTANT = 'dropbox';
    Const CONFIG_DISTANT_PATH = '';

    Const MAX_FILE_PER_DIR = 1000;  //pair impératif
    Const MAX_DIR = 100;

    Const TYPE_TEMPO_LOCAL = 1;
    Const TYPE_FINAL_LOCAL = 2;
    Const TYPE_FINAL_DISTANT = 3;
    Const TYPE_ALL_TEMPO_LOCAL = 4;

    Const EXT = 'jpg';
    CONST MIME = 'image/jpeg';
    Const THUMB_EXT = '-thumb';
    Const FINAL_LOCAL_PATH = '/final/';

    Const WATER_MARK = 'watermark.png';

    Const PICTURE_SIZE_MAX_WIDTH = 1200;
    Const THUMB_SIZE = 600;
    Const PICTURE_BACK_COLOR = '#fafafa';
    Const THUMB_BACK_COLOR = '#ffffff';

    private $type;
    private $disk;
    private $fileName;
    private $ext;
    private $picture_size_max_height;

    public function __construct() {
        $this->picture_size_max_height = round(static::PICTURE_SIZE_MAX_WIDTH*env('IMAGE_RATIO'));
    }

    /**
     * Set private type from request
     * @param $type
     */
    public function setType($type){
        if($type == self::TYPE_TEMPO_LOCAL) {
            $this->type = self::TYPE_TEMPO_LOCAL;
            $this->disk = self::DISK_LOCAL;
        } elseif($type == self::TYPE_ALL_TEMPO_LOCAL){
            $this->type = self::TYPE_ALL_TEMPO_LOCAL;
            $this->disk = self::DISK_LOCAL;
        } elseif($type == self::TYPE_FINAL_LOCAL){
            $this->type = self::TYPE_FINAL_LOCAL;
            $this->disk = self::DISK_LOCAL;
        } else {
            $this->type = self::TYPE_FINAL_DISTANT;
            $this->disk = self::DISK_DISTANT;
        }
    }

    /**
     * @param $fileName
     */
    public function setFileName($fileName){
        $this->fileName = $fileName;
    }

    /**
     * @param $ext
     */
    public function setExt($ext){
        $this->ext = $ext;
    }

    /**
     * Create List of thumb in personnal tempo Path
     * @param null $type
     * @return array
     */
    public function listThumbs($type=null){
        $type ? $this->setType($type) : null;
        try {
            $listFiles = Storage::disk($this->disk)->files($this->personnalPath());
            $thumbListFiles = [];
            foreach ($listFiles as $item){
                if(strpos($item, self::THUMB_EXT.'.'.self::EXT)){
                    $fileTime = Storage::disk($this->disk)->lastModified($item);
                    $newItem  = str_replace(self::THUMB_EXT.'.'.self::EXT,'',$item);
                    $newItem2 = substr($newItem,strlen($this->personnalPath())-1);
                    $thumbListFiles[] = [
                        'fileTime' => $fileTime,
                        'fileName' => $newItem2
                    ];
                }
            }
            return $this->sortArrayFileTime($thumbListFiles);
        } catch (\ErrorException $e) {
            return [];
        }
    }

    private function sortArrayFileTime($array){
        foreach ($array as $key => $file){
            $time[$key] = $file['fileTime'];
            $name[$key] = $file['fileName'];
        }

        array_multisort($time, SORT_ASC, $name, SORT_ASC, $array);

        $result = [];
        foreach ($array as $key => $file){
            $result[] = $file['fileName'];
        }
        return $result;
    }

    /**
     * Return last part of personnal Path (example: /tempo/1)
     * @return null|string
     * @internal param $type
     */
    private function personnalPath($type=null){
        $type ? $this->setType($type) : null;
        //TODO prevoir mail pour prevenir de la surcharge
        if($this->type == self::TYPE_TEMPO_LOCAL) {
            if(auth()->check()){
                //use csrftoken for prevent collisions on multiple sessions case by same user
                return '/tempo/' . csrf_token() . '/';
            } else {
                throw new \Exception('auth must check for storage');
            }
        } elseif ($this->type == self::TYPE_ALL_TEMPO_LOCAL) {
            return '/tempo/' ;
        } elseif($this->type == self::TYPE_FINAL_LOCAL){
            for($i=1; $i<static::MAX_DIR; $i++){
                if(count(Storage::disk($this->disk)->files(self::FINAL_LOCAL_PATH . $i . '/'))<static::MAX_FILE_PER_DIR){
                    return self::FINAL_LOCAL_PATH . $i . '/';
                }
            }
            throw new \Exception('max file in storage');
        } else {
            for($i=1; $i<static::MAX_DIR; $i++){
                if(count(Storage::disk($this->disk)->files('/' . $i . '/'))<static::MAX_FILE_PER_DIR){
                    return '/' . $i . '/';
                }
            }
            throw new \Exception('max file in storage');
        }
    }

    /**
     * Get the waterMark filePath
     * @return string
     */
    private function getWaterMark() {
        return config(static::CONFIG_LOCAL_ROOT_PATH) . '/' . static::WATER_MARK;
    }

    public function storeLocalFinal(){
        $listTempoFiles = Storage::disk(static::DISK_LOCAL)->files($this->personnalPath(static::TYPE_TEMPO_LOCAL));
        $results=[];

        //Tri par date
        $sortListFiles = [];
        foreach ($listTempoFiles as $item){
            $fileTime = Storage::disk(static::DISK_LOCAL)->lastModified($item);
            $sortListFiles[] = [
                'fileTime' => $fileTime,
                'fileName' => $item
            ];
        }

        $listTempoFiles =  $this->sortArrayFileTime($sortListFiles);

        foreach ($listTempoFiles as $file){
            $originPath = $this->personnalPath(static::TYPE_TEMPO_LOCAL);
            $fileName = substr($file,strlen($originPath)-1);

            if(strpos($fileName, self::THUMB_EXT.'.'.self::EXT)){
                $hashName = substr($fileName, 0, -strlen(self::THUMB_EXT.'.'.self::EXT));
                $isThumb = true;
            } else {
                $hashName = substr($fileName, 0, -strlen('.'.self::EXT));
                $isThumb=false;
            }

            $destPath = $this->personnalPath(static::TYPE_FINAL_LOCAL);

            if(!Storage::disk(static::DISK_LOCAL)->exists($destPath.$fileName)){
                $result = Storage::disk(static::DISK_LOCAL)->copy($file,$destPath.$fileName);
            } else {
                $result =true;
            }

            if($result) {
                $results[] = [
                    'disk' => static::DISK_LOCAL,
                    'path' => $destPath,
                    'hashName' => $hashName,
                    'isThumb' => $isThumb
                ];
            } else {
                throw new \Exception('copy fails');
            }
        }
        return $results;
    }

    public function purgeSessionLocalTempo(){
        Storage::deleteDirectory($this->personnalPath(static::TYPE_TEMPO_LOCAL));
    }

    public function purgeObsoleteLocalTempo($maxHoursLifeTime){
        $countFiles = 0;
        $directories = Storage::directories($this->personnalPath(static::TYPE_ALL_TEMPO_LOCAL));
        foreach ($directories as $directory) {
            $age = time() - Storage::lastModified($directory);
            if($age > $maxHoursLifeTime*3600){
                $countFiles = $countFiles + count(Storage::files($directory));
                Storage::deleteDirectory($directory);
            }
        }
        return $countFiles;
    }

    public function moveToDistantFinal(Picture $picture){
        $fileName = $picture->isThumb ? $picture->hashName.static::THUMB_EXT.'.'.static::EXT : $picture->hashName.'.'.static::EXT;$picture->hashName;
        $originPath = config(self::CONFIG_LOCAL_ROOT_PATH).$picture->path;
        $destPathBDD = $this->personnalPath(static::TYPE_FINAL_DISTANT);
        substr($destPathBDD,-1)=='/' ?  $destPath=substr($destPathBDD,0,strlen($destPathBDD)-1) : $destPath=$destPathBDD;
        $result = Storage::disk(static::DISK_DISTANT)->putFileAs($destPath, new File($originPath.$fileName), $fileName);
        if($result) {
            //Attention d'abord destruction puis sauv BDD sinon le fichier detruit est le fichier final!
            $countParentAdvert = $this->countParent($picture);
            $countParentAdvert == 1 ? $this->destroy($picture) : null;
            $picture->path = $destPathBDD;
            $picture->disk = self::DISK_DISTANT;
            $picture->save();
        } else {
            throw new \Exception('copy fails');
        }
    }

    public function copyFinalToTempoLocal(Picture $picture){
        $thumbFile = $this->getThumbFinal($picture);
        $this->setType(self::TYPE_TEMPO_LOCAL);
        Storage::disk($this->disk)->put($this->personnalPath().$picture->hashName.static::THUMB_EXT.'.'.static::EXT, $thumbFile);

        $normalFile = $this->getNormal($picture);
        $this->setType(self::TYPE_TEMPO_LOCAL);
        Storage::disk($this->disk)->put($this->personnalPath().$picture->hashName.'.'.static::EXT, $normalFile);
    }

    public function getSize(Picture $picture){
        $fileName = $picture->isThumb ? $picture->hashName.static::THUMB_EXT.'.'.static::EXT : $picture->hashName.'.'.static::EXT;$picture->hashName;
        if(Storage::disk('local')->exists($picture->path.$fileName)){
            return Storage::disk('local')->size($picture->path.$fileName);
        } else {
            return 0;
        }
    }

    public function save(Request $request) {
        $this->setType(static::TYPE_TEMPO_LOCAL);
        $md5Name = md5_file($request->file('addpicture')->getRealPath());
        $guessExtension = $request->file('addpicture')->guessExtension();
        $file = $request->file('addpicture')->storeAs($this->personnalPath(), $md5Name.'.'.$guessExtension  ,$this->disk);
        $this->setExt($request->file('addpicture')->extension());
        $this->setFileName(pathinfo($file, PATHINFO_FILENAME));

        $this->createThumb();
        $this->createPicture();
        $this->deleteOriginalFile();
    }

    private function createThumb(){
        $file = Storage::disk($this->disk)->get($this->personnalPath().$this->fileName.'.'.$this->ext);

        $rawThumb = Image::canvas(static::THUMB_SIZE, static::THUMB_SIZE, static::THUMB_BACK_COLOR);

        $thumb = Image::make($file);
        $thumb->resize(static::THUMB_SIZE,static::THUMB_SIZE, function ($constraint) {
            $constraint->aspectRatio();
        });


        $rawThumb->insert($thumb, 'center');
        $rawThumb->insert($this->getWaterMark(),'bottom-left',5,5);
        $rawThumb->encode(static::EXT);
        Storage::disk($this->disk)->put($this->personnalPath().$this->fileName.static::THUMB_EXT.'.'.static::EXT, $rawThumb);
    }

    private function createPicture(){
        $file = Storage::disk($this->disk)->get($this->personnalPath().$this->fileName.'.'.$this->ext);

        $picture = Image::make($file);
        $picture->resize(static::PICTURE_SIZE_MAX_WIDTH,$this->picture_size_max_height, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        });

        if($picture->width() < 16*$picture->height()/9){
            $rawPicture = Image::canvas(16*$picture->height()/9, $picture->height(), static::PICTURE_BACK_COLOR);
        } elseif ($picture->width() > 16*$picture->height()/9) {
            $rawPicture = Image::canvas($picture->width(), 9*$picture->width()/16, static::PICTURE_BACK_COLOR);
        } else {
            $rawPicture = Image::canvas($picture->width(), $picture->height(), static::PICTURE_BACK_COLOR);
        }

        $rawPicture->insert($picture, 'center');
        $rawPicture->insert($this->getWaterMark(),'bottom-left',10,10);
        $rawPicture->encode(static::EXT);
        Storage::disk($this->disk)->put($this->personnalPath().$this->fileName.'.'.static::EXT, $rawPicture);
    }

    private function deleteOriginalFile(){
        if($this->ext != static::EXT) {
            Storage::disk($this->disk)->delete($this->personnalPath().$this->fileName.'.'.$this->ext);
        }
    }

    public function destroyTempo($hashName){
        $this->setType(self::TYPE_TEMPO_LOCAL);
        $this->setFileName($hashName);

        if(Storage::disk($this->disk)->exists($this->personnalPath().$this->fileName.static::THUMB_EXT.'.'.static::EXT)){
            Storage::disk($this->disk)->delete($this->personnalPath().$this->fileName.static::THUMB_EXT.'.'.static::EXT);
        }

        if(Storage::disk($this->disk)->exists($this->personnalPath().$this->fileName.'.'.static::EXT)){
            Storage::disk($this->disk)->delete($this->personnalPath().$this->fileName.'.'.static::EXT);
        }
    }

    public function destroy(Picture $picture){
        if($picture->isThumb){
            if(Storage::disk($picture->disk)->exists($picture->path.$picture->hashName.static::THUMB_EXT.'.'.static::EXT)){
                Storage::disk($picture->disk)->delete($picture->path.$picture->hashName.static::THUMB_EXT.'.'.static::EXT);
            }
        } else {
            if(Storage::disk($picture->disk)->exists($picture->path.$picture->hashName.'.'.static::EXT)){
                Storage::disk($picture->disk)->delete($picture->path.$picture->hashName.'.'.static::EXT);
            }
        }
    }

    public function getThumbFinal(Picture $picture){
        if(Storage::disk($picture->disk)->exists($picture->path.$picture->hashName.static::THUMB_EXT.'.'.static::EXT)){
            $file =  Storage::disk($picture->disk)->get($picture->path.$picture->hashName.static::THUMB_EXT.'.'.static::EXT);
            return $file;
        }
        return null;
    }

    public function getThumbTempo($hashName){
        $this->setType(self::TYPE_TEMPO_LOCAL);
        $this->setFileName($hashName);
        $path = $this->personnalPath();

        if(Storage::disk($this->disk)->exists($path.$this->fileName.static::THUMB_EXT.'.'.static::EXT)){
            $file =  Storage::disk($this->disk)->get($path.$this->fileName.static::THUMB_EXT.'.'.static::EXT);
            return $file;
        }
        return null;
    }

    public function getNormalTempo($hashName){
        $this->setType(self::TYPE_TEMPO_LOCAL);
        $this->setFileName($hashName);
        $path = $this->personnalPath();

        if(Storage::disk($this->disk)->exists($path.$this->fileName.'.'.static::EXT)){
            $file =  Storage::disk($this->disk)->get($path.$this->fileName.'.'.static::EXT);
            return $file;
        }
        return null;
    }

    public function getNormal(Picture $picture){
        if(Storage::disk($picture->disk)->exists($picture->path.$picture->hashName.'.'.static::EXT)){
            $file =  Storage::disk($picture->disk)->get($picture->path.$picture->hashName.'.'.static::EXT);
            return $file;
        }
        return null;
    }

    public function countParent(Picture $picture) {
        return $picture->parents()->count();
    }

    public function infoLocalFiles() {
        $files = [];
        $size = 0;
        try {
            $files = Storage::disk(self::DISK_LOCAL)->allFiles(self::FINAL_LOCAL_PATH);
            foreach ($files as $file){
                $size += Storage::disk(self::DISK_LOCAL)->size($file);
            }

            return [count($files), $size];
        } catch (\Exception $e) {
            return [count($files), $size];
        }
    }

    public function infoDistantFiles() {
        $distantFiles = [];
        $distantSize = 0;

        try {
            $distantFiles = Storage::disk(self::DISK_DISTANT)->allFiles('/');
            foreach ($distantFiles as $distantFile){
                $distantSize += Storage::disk(self::DISK_DISTANT)->size($distantFile);
            }
            return [count($distantFiles), $distantSize];
        } catch (\Exception $e) {
            return [count($distantFiles), $distantSize];
        }
    }
}