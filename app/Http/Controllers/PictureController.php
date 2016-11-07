<?php

namespace App\Http\Controllers;

use App\Http\Requests\PictureAdvertRequest;
use App\Common\PicturesManager;
use App\Picture;
use Illuminate\Http\Response;

class PictureController extends Controller
{

    private $pictureManager;

    public function __construct(PicturesManager $picturesManager) {
        $this->middleware('auth', ['except' => []]);
        $this->pictureManager = $picturesManager;
    }

    /**
     * post a picture and thumb in temporary personal folder
     * @param PictureAdvertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function post(PictureAdvertRequest $request) {
        //save file and create thumb with watermark
        $this->pictureManager->save($request);
        return response()->json($this->pictureManager->listThumbs());
    }

    /**
     * Delete a specific Picture and thumb
     * @param $type
     * @param $fileName
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($type, $hashName) {
        $this->pictureManager->destroy($type,$hashName);
        return response()->json($this->pictureManager->listThumbs());
    }

    /**
     * Get a specific Thumb
     * @param $type
     * @param $fileName
     * @return \Illuminate\Http\Response
     */
    public function getThumb($type, $hashName, $advertId=null) {

        if($type != PicturesManager::TYPE_TEMPO_LOCAL){
            $picture = Picture::where('hashName', '=', $hashName)
                ->where('advert_id','=',$advertId)
                ->where('isThumb', '=', true)->first();
            if($picture){
                $realType =  $picture->disk == 'local' ? PicturesManager::TYPE_FINAL_LOCAL : PicturesManager::TYPE_FINAL_DISTANT;
                $file = $this->pictureManager->getThumb($realType, $picture->hashName, $picture->path);
                if($file){
                    return response($file,200)->header("Content-Type", PicturesManager::MIME);
                } else {
                    return response(trans('strings.view_all_error_download_file'), 404);
                }
            } else {
                return response(trans('strings.view_all_error_download_file'), 404);
            }
        } else {
            $file = $this->pictureManager->getThumb($type, $hashName);
            if($file){
                return response($file,200)->header("Content-Type", PicturesManager::MIME);
            } else {
                return response(trans('strings.view_all_error_download_file'), 404);
            }
        }
    }

    /**
     * Get list of Thumbs in personnal tempo Path
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListThumbs($type) {
        return response()->json($this->pictureManager->listThumbs($type));
    }

    public function tempo(){
        $results = $this->pictureManager->getThumb(PicturesManager::TYPE_TEMPO_LOCAL,'70aa326692d594e8aade9e695ce296b8');
        dd($results);
    }
}
