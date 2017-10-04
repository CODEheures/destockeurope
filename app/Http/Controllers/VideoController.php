<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Persistent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Vinkla\Vimeo\VimeoManager;

class VideoController extends Controller
{
    private $vimeoManager;

    public function __construct(VimeoManager $vimeoManager) {
        $this->middleware('auth');
        $this->vimeoManager = $vimeoManager;
    }

    private function quota() {
        $response = $this->vimeoManager->request('/me', [], 'GET');
        return $response['body']['upload_quota']['space']['free'];
    }

    public function ticket(Request $request) {

        if(!$request->filled('size') || !$request->filled('type') ||  $request->size<=0 || substr($request->type,0,5)!='video'){
            return response('error',500);
        }

        $size = $request->size;
        if($size > env('VIDEO_MAX_SIZE_MB')*1024*1024 || $this->quota() < $size) {
            return response(trans('strings.view_all_error_service_unavailable'),503);
        }

        $response = $this->vimeoManager->request('/me/videos', ['type'=>'streaming', 'upgrade_to_1080' => false], 'POST');
        if($response['status']==201){
            session([
                'completeVideoUpload' => $response['body']['complete_uri'],
                'routePutVideo' => $response['body']['upload_link_secure'],
                'getVideoUploadPerform' => $response['body']['upload_link_secure']
            ]);
            return response()->json([
                'routeCloseTicket' => route('videos.closeTicket'),
                'completeVideoUpload' => $response['body']['complete_uri'],
                'routePutVideo' => $response['body']['upload_link_secure'],
                'getVideoUploadPerform' =>$response['body']['upload_link_secure']
            ]);
        } else {
            return response(trans('strings.view_all_error_service_unavailable'),503);
        }
    }

    public function closeTicket(Request $request) {
        if(!$request->filled('completeVideoUpload')){
            return response('error',500);
        }

        $response = $this->vimeoManager->request($request->completeVideoUpload, [], 'DELETE');

        if(key_exists('Location',$response['headers'])){
            $videoId = str_replace('/videos/', '', $response['headers']['Location']);

            session()->forget('completeVideoUpload');
            session()->forget('routePutVideo');
            session()->forget('getVideoUploadPerform');
            session(['videoId' => $videoId]);

            Persistent::create([
                'key' => 'videoId',
                'value' => $videoId
            ]);

            $this->vimeoManager->request(
                $response['headers']['Location'],
                ['privacy' => [
                    'download'=>false,
                    'add'=>false,
                    'comments'=>'nobody',
                ]],
                'PATCH'
            );
//            $this->vimeoManager->request($response['headers']['location'].'/privacy/domains/destockeurope.dev',[],'PUT');
        }
        return response($response['body'], $response['status'], $response['headers']);
    }

    public function delTempoVideo($videoId) {

        if(!$videoId || !session()->has('videoId') || $videoId != session('videoId')){
            return response('error',500);
        } else {
            $existInAdvert = Advert::withTrashed()->where('video_id', session('videoId'))->count();
            if($existInAdvert==0){
                $this->vimeoManager->request('/videos/'.session('videoId'),[],'DELETE');
            }

            $persistent = Persistent::where([
                'key' => 'videoId',
                'value' => session('videoId')
            ])->first();

            $persistent ? $persistent->delete() : null;
            session()->forget('videoId');
            return response('ok',200);
        }
    }

    public function status() {
        if(!session()->has('videoId')){
            return response('error',500);
        } else {
            $response = $this->vimeoManager->request('/me/videos/'.session('videoId'),[],'GET');
            return response()->json(['status'=>$response['body']['status']]);
        }
    }
}
