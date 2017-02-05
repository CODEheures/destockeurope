<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNotificationRequest;
use App\Http\Requests\RemoveNotificationRequest;
use App\Notification;
use Illuminate\Http\Request;
use sngrl\PhpFirebaseCloudMessaging\Client;

class NotificationController extends Controller
{
    public function __construct() {

    }

    public function add(AddNotificationRequest $request) {
        $token = $request->get('token');
        $topic_id=$request->get('topic_id');

        $exist = Notification::existIn($token, $topic_id)->first();
        if(!$exist){
            $server_key = env('GOOGLE_FIREBASE_SERVERAPIKEY');
            $client = new Client();
            $client->setApiKey($server_key);
            $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());
            $response = $client->addTopicSubscription(config('topics.'.strval($topic_id)), [$token]);
            if($response->getStatusCode()==200){
                Notification::create([
                    'token' => $token,
                    'topic_id' => $topic_id
                ]);
                return response('ok',200);
            } else {
                return response($response->getReasonPhrase(), $response->getStatusCode());
            }
        }
        return response('ok',200);
    }

    public function remove(RemoveNotificationRequest $request) {
        $token = $request->get('token');
        $topic_id=null;
        if($request->has('topic_id')){
            $topic_id=$request->get('topic_id');
        }

        $server_key = env('GOOGLE_FIREBASE_SERVERAPIKEY');
        $client = new Client();
        $client->setApiKey($server_key);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());
        if($topic_id){
            $response = $client->removeTopicSubscription(config('topics.'.strval($topic_id)), [$token]);
            if($response->getStatusCode()==200){
                Notification::existIn($token,$topic_id)->delete();
                return response('ok',200);
            } else {
                return response($response->getReasonPhrase(), $response->getStatusCode());
            }
        } else {
            for($i=1;$i<count(config('topics'));$i++){
                $response = $client->removeTopicSubscription(config('topics.'.strval($i)), [$token]);
            }
            Notification::existInAll($token)->delete();
            return response('ok',200);
        }
    }

    public function existIn(AddNotificationRequest $request) {
        $token = $request->get('token');
        $topic_id=$request->get('topic_id');

        $exist = Notification::existIn($token, $topic_id)->first();
        if(!$exist){
            return response()->json(['existIn' => false]);
        }
        return response()->json(['existIn' => true]);
    }
}
