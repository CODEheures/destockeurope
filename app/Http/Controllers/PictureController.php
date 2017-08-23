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

        $client = new GuzzleClient();
        $alreadyUploadPictures = session('uploadPictures', []);

        //1°) GET LOAD INFOS FROM PICTURE SERVICES

        //best load
        foreach (config('pictures.service.domains') as $domain) {
            $infos = $client->request('GET',
                $domain . config('pictures.service.urls.routeGetInfos'),
                [
                    'http_errors' => false,
                ]
            );
            $loadInfos[$domain] = json_decode($infos->getBody()->getContents(),true);
        }

        try {
            $bestLoad = array_sort($loadInfos, function ($value, $key) {
                return $value['load'];
            });
            $bestLoad = array_keys($bestLoad)[0];
        } catch (\Exception $e) {
            //default load
            $bestLoad = config('pictures.service.domains')[0];
        }


        //2°) POST PICTURE FOR GET MD5
        $md5Response = $client->request('POST',
            $bestLoad . config('pictures.service.urls.routeGetMd5'),
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


        //3°) Process or delete
        $existHash = count(array_filter($alreadyUploadPictures, function($elem) use ($md5Infos){
            return $elem['hashName'] == $md5Infos->md5_name;
        })) == 1;

        if(!$existHash){
            $saveResponse = $client->request('POST',
                $bestLoad . config('pictures.service.urls.routeSavePicture'),
                [
                    'http_errors' => false,
                    'multipart' => [
                        [
                            'name' => 'watermark',
                            'contents' => fopen(config('pictures.watermark_path'),'r')
                        ],
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
                            'name' => 'formats',
                            'contents' => json_encode(config('pictures.formats'))
                        ]
                    ]
                ]
            );
            if($saveResponse->getStatusCode() >= 400){ return response($saveResponse->getBody(),500); }

            $picture = json_decode($saveResponse->getBody()->getContents(), true);
            $alreadyUploadPictures[] = [
                'hashName' => $picture['hashName'],
                'thumbUrl' => $picture['thumb'],
                'normalUrl' => $picture['normal'],
            ];

            Persistent::create([
                'key' => 'picture',
                'value' => $picture['thumb']
            ]);

            Persistent::create([
                'key' => 'picture',
                'value' => $picture['normal']
            ]);

        } else {
            $md5CancelResponse = $client->request('DELETE',
                $bestLoad . config('pictures.service.urls.routeCancelMd5').'/'.csrf_token()
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
            $thumbIsFree = Picture::withUrl($sessionHash['thumbUrl'])->count() == 0;
            $delUrl = parse_url($sessionHash['thumbUrl'])['scheme'] . '://' . parse_url($sessionHash['thumbUrl'])['host'] . config('pictures.service.urls.routeDelete') . parse_url($sessionHash['thumbUrl'])['path'];

            if($thumbIsFree){
                $deleteResponse = $client->request('DELETE',
                    $delUrl,
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
            $normalIsFree = Picture::withUrl($sessionHash['normalUrl'])->count() == 0;
            $delUrl = parse_url($sessionHash['normalUrl'])['scheme'] . '://' . parse_url($sessionHash['normalUrl'])['host'] . config('pictures.service.urls.routeDelete') . parse_url($sessionHash['normalUrl'])['path'];

            if($normalIsFree) {
                $deleteResponse = $client->request('DELETE',
                    $delUrl,
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
