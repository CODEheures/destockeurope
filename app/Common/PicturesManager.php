<?php

namespace App\Common;


use App\Picture;
use Exception;
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

    Const EXT = 'jpg';
    CONST MIME = 'image/jpeg';
    Const THUMB_EXT = '-thumb';

    Const WATER_MARK = 'watermark.png';

    Const PICTURE_SIZE_MAX_WIDTH = 1200;
    Const PICTURE_SIZE_MAX_HEIGHT = 675;
    Const THUMB_SIZE = 600;

    private $type;
    private $disk;
    private $fileName;
    private $ext;

    public function __construct() {}

    /**
     * Set private type from request
     * @param $type
     */
    public function setType($type){
        if($type == self::TYPE_TEMPO_LOCAL) {
            $this->type = self::TYPE_TEMPO_LOCAL;
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
            return '/tempo/' . auth()->user()->id . '/';
        } elseif($this->type == self::TYPE_FINAL_LOCAL){
            for($i=1; $i<static::MAX_DIR; $i++){
                if(count(Storage::disk($this->disk)->files('/final/' . $i . '/'))<static::MAX_FILE_PER_DIR){
                    return '/final/' . $i . '/';
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

    public function purgeLocalTempo(){
        Storage::deleteDirectory($this->personnalPath(static::TYPE_TEMPO_LOCAL));
    }

    public function storeDistantFinal(){

        $listPictureToMove= Picture::where('disk', '=', 'local')->take(4)->get();

        //$listTempoFiles = Storage::disk(static::DISK_LOCAL)->files($this->personnalPath(static::TYPE_TEMPO_LOCAL));
        $results=[];
        foreach ($listPictureToMove as $file){
            $urlTempoFile = config(self::CONFIG_LOCAL_ROOT_PATH).'/'.$file;

            $originPath = $this->personnalPath(static::TYPE_TEMPO_LOCAL);
            $fileName = substr($file,strlen($originPath)-1);

            $destPath = $this->personnalPath(static::TYPE_FINAL_DISTANT);
            //$result = Storage::disk(static::DISK_DISTANT)->put($destPath.$fileName, file_get_contents($urlTempoFile));
            $result = Storage::disk(static::DISK_DISTANT)->putFileAs($destPath, new File($urlTempoFile), $fileName);

            if($result) {
                //Storage::disk(static::DISK_LOCAL)->delete($file);
                $results[] = [
                    'new' => [
                        'disk' => static::DISK_DISTANT,
                        'path' => $destPath,
                        'fileName' => $fileName
                    ],
                    'old' => [
                        'disk' => static::DISK_LOCAL,
                        'path' => $this->personnalPath(static::TYPE_TEMPO_LOCAL),
                        'fileName' => $fileName
                    ]
                ];
            } else {
                throw new \Exception('copy fails');
            }
        }
        //Storage::disk(static::DISK_LOCAL)->deleteDirectory($this->personnalPath(static::TYPE_TEMPO_LOCAL));
        return $results;
    }

    public function save($request) {
        $this->setType(static::TYPE_TEMPO_LOCAL);

        $file = $request->file('addpicture')->store($this->personnalPath(), $this->disk);
        $this->setExt($request->file('addpicture')->extension());
        $this->setFileName(pathinfo($file, PATHINFO_FILENAME));

        $this->createThumb();
        $this->createPicture();
        $this->deleteOriginalFile();
    }

    private function createThumb(){
        $file = Storage::disk($this->disk)->get($this->personnalPath().$this->fileName.'.'.$this->ext);

        $thumb = Image::make($file);
        $thumb->fit(static::THUMB_SIZE,static::THUMB_SIZE, function ($constraint) {
            $constraint->aspectRatio();
        });
        $thumb->insert($this->getWaterMark(),'bottom-left',5,5);

        $rawThumb = $thumb->encode(static::EXT);
        Storage::disk($this->disk)->put($this->personnalPath().$this->fileName.static::THUMB_EXT.'.'.static::EXT, $rawThumb);
    }

    private function createPicture(){
        $file = Storage::disk($this->disk)->get($this->personnalPath().$this->fileName.'.'.$this->ext);

        $picture = Image::make($file);
        $picture->fit(static::PICTURE_SIZE_MAX_WIDTH,static::PICTURE_SIZE_MAX_HEIGHT, function ($constraint) {
            $constraint->upsize();
        });
        $picture->insert($this->getWaterMark(),'bottom-left',10,10);

        $rawPicture = $picture->encode(static::EXT);
        Storage::disk($this->disk)->put($this->personnalPath().$this->fileName.'.'.static::EXT, $rawPicture);
    }

    private function deleteOriginalFile(){
        if($this->ext != static::EXT) {
            Storage::disk($this->disk)->delete($this->personnalPath().$this->fileName.'.'.$this->ext);
        }
    }

    public function destroy($type, $hashName){
        $this->setType($type);
        $this->setFileName($hashName);

        if(Storage::disk($this->disk)->exists($this->personnalPath().$this->fileName.static::THUMB_EXT.'.'.static::EXT)){
            Storage::disk($this->disk)->delete($this->personnalPath().$this->fileName.static::THUMB_EXT.'.'.static::EXT);
        }

        if(Storage::disk($this->disk)->exists($this->personnalPath().$this->fileName.'.'.static::EXT)){
            Storage::disk($this->disk)->delete($this->personnalPath().$this->fileName.'.'.static::EXT);
        }
    }

    public function getThumb($type, $hashName, $path=null){
        $this->setType($type);
        $this->setFileName($hashName);

        if(!$path) {
            $path = $this->personnalPath();
        }

        if(Storage::disk($this->disk)->exists($path.$this->fileName.static::THUMB_EXT.'.'.static::EXT)){
            $file =  Storage::disk($this->disk)->get($path.$this->fileName.static::THUMB_EXT.'.'.static::EXT);
            return $file;
        }
        return null;
    }
}