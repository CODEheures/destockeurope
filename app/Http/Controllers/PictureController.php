<?php

namespace App\Http\Controllers;

use App\Http\Requests\PictureAdvertRequest;
use App\Common\PicturesManager;
use App\Picture;
use Illuminate\Http\Response;
use GuzzleHttp\Client as GuzzleClient;

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
        $url = 'http://statics.destockeurope.progress/'.env('PICTURE_MANAGER_POST_URL');
        $client = new GuzzleClient();
        $alreadyUploadPictures = session('uploadPictures', ['hashName' => ['normals' => [], 'thumbs' => []]]);

        try {
            $response = $client->request('POST',
                $url,
                [
                    'multipart' => [
                        [
                            'name' => 'token',
                            'contents' => config('pictures.token')],
                        [
                            'name' => 'csrf',
                            'contents' => csrf_token()],
                        [
                            'name' => 'addpicture',
                            'contents' => fopen($request->file('addpicture')->getRealPath(),'r')],
                        [
                            'name' => 'watermark',
                            'contents' => fopen(config('pictures.watermark_path'),'r')],
                        [
                            'name' => 'max_width',
                            'contents' => config('pictures.max_width'),
                        ],
                        [
                            'name' => 'picture_ratio',
                            'contents' => config('pictures.picture_ratio'),
                        ],
                        [
                            'name'     => 'thumb_size',
                            'contents' => config('pictures.thumb_size'),
                        ],
                        [
                            'name' => 'thumb_ratio',
                            'contents' => config('pictures.thumb_ratio'),
                        ],
                        [
                            'name'     => 'picture_back_color',
                            'contents' => config('pictures.picture_back_color'),
                        ],
                        [
                            'name'     => 'thumb_back_color',
                            'contents' => config('pictures.thumb_back_color'),
                        ],
                        [
                            'name'     => 'format_encoding',
                            'contents' => config('pictures.format_encoding'),
                        ]

                    ]
                ]
            );

            $urls = json_decode($response->getBody()->getContents());
            !in_array($urls->normal, $alreadyUploadPictures['normals']) ? $alreadyUploadPictures['normals'][] =  $urls->normal : null;
            !in_array($urls->thumb, $alreadyUploadPictures['thumbs']) ? $alreadyUploadPictures['thumbs'][] =  $urls->thumb : null;

            session(['uploadPictures' => $alreadyUploadPictures]);
            return response()->json($alreadyUploadPictures);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    /**
     * Delete a specific Picture and thumb
     * @param $type
     * @param $fileName
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTempo($hashName) {
        $this->pictureManager->destroyTempo($hashName);
        return response()->json(session('uploadPictures'));
    }

    /**
     * Get a specific Thumb
     * @param $type
     * @param $fileName
     * @return \Illuminate\Http\Response
     */
    public function getThumb($type, $hashName, $advertId=null) {
        if($type != PicturesManager::TYPE_TEMPO_LOCAL){
            $picture = Picture::findThumb($hashName,$advertId)->first();
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
    public function getNormal($type, $hashName, $advertId=null) {
        if($type != PicturesManager::TYPE_TEMPO_LOCAL) {
            $picture = Picture::findNormal($hashName, $advertId)->first();
            if ($picture) {
                $file = $this->pictureManager->getNormal($picture);
                if ($file) {
                    return response($file, 200)->header("Content-Type", PicturesManager::MIME);
                } else {
                    return response(trans('strings.view_all_error_download_file'), 404);
                }
            } else {
                return response(trans('strings.view_all_error_download_file'), 404);
            }
        } else {
            $file = $this->pictureManager->getNormalTempo($hashName);
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
    public function getListThumbs() {
        return response()->json(session('uploadPictures'));
    }
}
