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
        $this->middleware('auth', ['except' => ['getThumb', 'getNormal']]);
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
    public function destroyTempo($hashName) {
        $this->pictureManager->destroyTempo($hashName);
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
                $file = $this->pictureManager->getThumbFinal($picture);
                if($file){
                    return response($file,200)->header("Content-Type", PicturesManager::MIME);
                } else {
                    return response(trans('strings.view_all_error_download_file'), 404);
                }
            } else {
                return response(trans('strings.view_all_error_download_file'), 404);
            }
        } else {
            $file = $this->pictureManager->getThumbTempo($hashName);
            if($file){
                return response($file,200)->header("Content-Type", PicturesManager::MIME);
            } else {
                return response(trans('strings.view_all_error_download_file'), 404);
            }
        }
    }

    /**
     * Get a specific Thumb
     * @param $type
     * @param $fileName
     * @return \Illuminate\Http\Response
     */
    public function getNormal($hashName, $advertId) {
        $picture = Picture::where('hashName', '=', $hashName)
            ->where('advert_id','=',$advertId)
            ->where('isThumb', '=', false)->first();
        if($picture){
            $file = $this->pictureManager->getNormal($picture);
            if($file){
                return response($file,200)->header("Content-Type", PicturesManager::MIME);
            } else {
                return response(trans('strings.view_all_error_download_file'), 404);
            }
        } else {
            return response(trans('strings.view_all_error_download_file'), 404);
        }
    }

    /**
     * Get list of Thumbs in personnal tempo Path
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListThumbs($type) {
        return response()->json($this->pictureManager->listThumbs($type));
    }
}
