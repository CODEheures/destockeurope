<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTopicsSubscribeRequest;
use App\Http\Requests\RemoveTopicsSubscribeRequest;
use GuzzleHttp\Client as GuzzleClient;
use sngrl\PhpFirebaseCloudMessaging\Client as ClientSngrl;

class TopicsSubscribeController extends Controller
{
    public function __construct() {

    }

    public function add(AddTopicsSubscribeRequest $request) {
        $token = $request->get('token');
        $topic_id=$request->get('topic_id');

        $exist = $this->getExistIn($token,$topic_id);

        if(!$exist){
            $server_key = env('GOOGLE_FIREBASE_SERVERAPIKEY');
            $client = new ClientSngrl();
            $client->setApiKey($server_key);
            $client->injectGuzzleHttpClient(new GuzzleClient());
            $response = $client->addTopicSubscription(config('topics.'.strval($topic_id)), [$token]);
            if($response->getStatusCode()==200){
                return response('ok',200);
            } else {
                return response($response->getReasonPhrase(), $response->getStatusCode());
            }
        }
        return response('ok',200);
    }

    public function remove(RemoveTopicsSubscribeRequest $request) {
        $token = $request->get('token');
        $topic_id=$request->get('topic_id');

        $exist = $this->getExistIn($token,$topic_id);

        if($exist){
            $server_key = env('GOOGLE_FIREBASE_SERVERAPIKEY');
            $client = new ClientSngrl();
            $client->setApiKey($server_key);
            $client->injectGuzzleHttpClient(new GuzzleClient());
            $response = $client->removeTopicSubscription(config('topics.'.strval($topic_id)), [$token]);
            if($response->getStatusCode()==200){
                return response('ok',200);
            } else {
                return response($response->getReasonPhrase(), $response->getStatusCode());
            }
        }
        return response('ok',200);
    }

    public function existIn(AddTopicsSubscribeRequest $request) {
        $token = $request->get('token');
        $topic_id=$request->get('topic_id');

        $exist = $this->getExistIn($token,$topic_id);

        return response()->json(['existIn' => $exist]);
    }

    private function getExistIn($token, $topic_id) {
        $url = 'https://iid.googleapis.com/iid/info/' . $token . '?details=true';

        $client = new GuzzleClient();
        $response = $client->post(
            $url,
            [
                'headers' => [
                    'Authorization' => sprintf('key=%s', env('GOOGLE_FIREBASE_SERVERAPIKEY')),
                ]
            ]
        );

        $exist = false;
        $details = json_decode($response->getBody()->getContents());
        if(property_exists($details, 'rel')
            && property_exists($details->rel,'topics')
            && property_exists($details->rel->topics, config('topics.'.$topic_id)))
        {
            $exist=true;
        }
        return $exist;
    }
}
