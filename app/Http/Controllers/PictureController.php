<?php

namespace App\Http\Controllers;

use App\Http\Requests\PictureAdvertRequest;
use App\Persistent;
use App\Picture;
use GuzzleHttp\Client as GuzzleClient;

class PictureController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * post a picture and thumb
     * @param PictureAdvertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function post(PictureAdvertRequest $request) {
        //save file and create thumb with watermark
        $client = new GuzzleClient();
        $alreadyUploadPictures = session('uploadPictures', []);

        //1°) POST PICTURE FOR GET MD5
        $md5Response = $client->request('POST',
            config('pictures.routeGetMd5'),
            [
                'http_errors' => false,
                'multipart' => [
                    [
                        'name' => 'csrf',
                        'contents' => csrf_token()],
                    [
                        'name' => 'addpicture',
                        'contents' => fopen($request->file('addpicture')->getRealPath(),'r')
                    ]
                ]
            ]
        );
        if($md5Response->getStatusCode() >= 400){ return response($md5Response->getBody(),500); }
        $md5Infos = json_decode($md5Response->getBody()->getContents());


        //2°) Process or delete
        $existHash = count(array_filter($alreadyUploadPictures, function($elem) use ($md5Infos){
            return $elem['hashName'] == $md5Infos->md5_name;
        })) == 1;

        if(!$existHash){
            $saveResponse = $client->request('POST',
                config('pictures.routeSavePicture'),
                [
                    'http_errors' => false,
                    'multipart' => [
                        [
                            'name' => 'csrf',
                            'contents' => csrf_token()
                        ],
                        [
                            'name' => 'md5_name',
                            'contents' => $md5Infos->md5_name
                        ],
                        [
                            'name' => 'guess_extension',
                            'contents' => $md5Infos->guess_extension
                        ],
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
            if($saveResponse->getStatusCode() >= 400){ return response($saveResponse->getReasonPhrase(),500); }
            $picture = json_decode($saveResponse->getBody()->getContents());
            $alreadyUploadPictures[] = [
                'hashName' => $picture->hashName,
                'thumbUrl' => $picture->thumbUrl,
                'normalUrl' => $picture->normalUrl,
            ];

            Persistent::create([
                'key' => 'picture',
                'value' => $picture->thumbUrl
            ]);

            Persistent::create([
                'key' => 'picture',
                'value' => $picture->normalUrl
            ]);

        } else {
            $md5CancelResponse = $client->request('DELETE',
                config('pictures.routeCancelMd5').'/'.csrf_token()
            );
            if($md5CancelResponse->getStatusCode() >= 400){ return response($md5CancelResponse->getReasonPhrase(),500); }
        }

        session(['uploadPictures' => $alreadyUploadPictures]);
        return response()->json($alreadyUploadPictures);

    }

    /**
     * Delete a specific Picture and thumb
     * @param $type
     * @param $fileName
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($hashName) {

        $sessionHash = array_merge([], array_filter(session('uploadPictures'), function($elem) use ($hashName) {
            return $elem['hashName'] == $hashName;
        }));


        if(count($sessionHash)==1){
            $sessionHash = $sessionHash[0];
            $client = new GuzzleClient();

            //1°) Test if thumb is not already employed
            $thumbIsFree = Picture::withTrashed()->where('thumbUrl', $sessionHash['thumbUrl'])->count() == 0;
            if($thumbIsFree){
                $deleteResponse = $client->request('DELETE',
                    str_replace_first('/thumb','/private/thumb',$sessionHash['thumbUrl']),
                    [
                        'http_errors' => false,
                    ]
                );
            }

            $persistent = Persistent::where([
                'key' => 'picture',
                'value' => $sessionHash['thumbUrl']
            ])->first();

            $persistent ? $persistent->delete() : null;

            //1°) Test if normal is not already employed
            $normalIsFree = Picture::withTrashed()->where('normalUrl', $sessionHash['normalUrl'])->count() == 0;
            if($normalIsFree) {
                $deleteResponse = $client->request('DELETE',
                    str_replace_first('/normal', '/private/normal', $sessionHash['normalUrl']),
                    [
                        'http_errors' => false,
                    ]
                );
            }

            $persistent = Persistent::where([
                'key' => 'picture',
                'value' => $sessionHash['normalUrl']
            ])->first();

            $persistent ? $persistent->delete() : null;

            $sessionWithoutHash = array_filter(session('uploadPictures'), function($elem) use ($hashName) {
                return $elem['hashName'] != $hashName;
            });

            session(['uploadPictures' => array_merge([], $sessionWithoutHash)]);
        }

        return response()->json(session('uploadPictures'));
    }

    /**
     * Get list of Thumbs in personnal tempo Path
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListPosts() {
        return response()->json(session('uploadPictures', []));
    }
}
