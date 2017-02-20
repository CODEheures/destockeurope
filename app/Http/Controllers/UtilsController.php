<?php

namespace App\Http\Controllers;

use App\Common\AdvertsManager;
use App\Common\PicturesManager;
use App\Stats;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Mockery\Matcher\Not;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Topic;
use Symfony\Component\HttpFoundation\Request;
use App\Common\LocaleUtils;
use App\Common\MoneyUtils;
use Vinkla\Vimeo\VimeoManager;

class UtilsController extends Controller
{

    use LocaleUtils;
    use MoneyUtils;

    private $vimeoManager;

    public function __construct(VimeoManager $vimeoManager) {
        $this->middleware('auth', ['only' => ['getListLocales', 'getListCardsType']]);
        $this->middleware('isAdminUser', ['only' => ['isPicture', 'tempo']]);
        $this->middleware('appOnDevelMode', ['only' => ['testGame','tempo']]);
        $this->vimeoManager = $vimeoManager;
    }


    public function getListCurrencies()  {
        return response()->json($this->listUserCurrencies());
    }

    public function getListLocales() {
        return response()->json($this->listUserLocales());
    }

    public function testGame() {
        $testFiles = Storage::disk('local')->files('/testGame');
        foreach ($testFiles as $file){
            if(!Storage::disk('local')->exists(PicturesManager::FINAL_LOCAL_PATH.'/1/'.basename($file))){
                Storage::disk('local')->copy($file, PicturesManager::FINAL_LOCAL_PATH.'/1/'.basename($file));
            }
        }
        $exitCode = Artisan::call('migrate:refresh', ['--seed' => true]);
        return redirect(route('home'))->with('info', $exitCode);
    }

    //test if a resource is a picture. Only use for advertissement on welcome page
    public function isPicture(Request $request){
        $url = $request->url;
        if(array_key_exists('Content-Type', get_headers($url,1))){
            if(strpos(get_headers($url,1)['Content-Type'],'image') !== false){
                return response()->json(true);
            }
        }
        return response()->json(false);
    }

    public function send(){

        try {
            $stat = Stats::whereDate('created_at', Carbon::yesterday())->first();
            if($stat && ($stat->totalNewFreeAdverts+$stat->totalNewCostAdverts)>0){
                $msgTxt = ($stat->totalNewFreeAdverts+$stat->totalNewCostAdverts) . ' nouvelles annonces aujourd\'hui';

                $server_key = env('GOOGLE_FIREBASE_SERVERAPIKEY');
                $client = new Client();
                $client->setApiKey($server_key);
                $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

                $message = new Message();
                $message->setPriority('high');
                $message->setTimeToLive(60*60*24);
                $message->addRecipient(new Topic(config('topics.1')));
                $notification = new Notification(config('app.name'), $msgTxt);
                $notification->setClickAction(route('portal'));
                $notification->setIcon('/android-chrome-512x512.png');
                $message
                    ->setNotification($notification)
                ;
                $response = $client->send($message);
                $status = $response->getStatusCode();
            }
        } catch (\Exception $e) {
            $status = '500';
            $msgTxt = $e->getMessage();
        }
        dd([$status, $msgTxt]);
        //curl --header "Authorization: key=AAAAtC_G_hU:APA91bFX3h22_WBunkt3YJJoUWjzg0nagmu91YOW6hWKND0CHN1RiX4B55MrFTKnpbpBlQBsB7iv_vZB5YX1PKxgwB__XXqi_I1GamM5aVfZV4zQp310U-aONJAfrTDRlMylyCxZb7AU" --header "Content-Type: application/json"  -d '{"notification": {"title": "Background Message Title","body": "Background message body","click_action" : "https://dummypage.com"},"to" : "cOtWaWp-mUg:APA91bEjuELrVZ6-RgJzZK8Ocisf8KBFUe1T2Wtqw9oOw5KXi4e_HqtH7nrTUZsYAiAULf5JRL8WP5aAeD_siNvW7h2_-V8D4T9-7D34CIn5arOxaSjMARaFYIJWn5NqBpO2KzosmZct"}' https://fcm.googleapis.com/fcm/send
        //curl --header "Authorization: key=AAAAtC_G_hU:APA91bFX3h22_WBunkt3YJJoUWjzg0nagmu91YOW6hWKND0CHN1RiX4B55MrFTKnpbpBlQBsB7iv_vZB5YX1PKxgwB__XXqi_I1GamM5aVfZV4zQp310U-aONJAfrTDRlMylyCxZb7AU"  https://iid.googleapis.com/iid/info/feAk5_D1sHI:APA91bGpktNyejVYtHN1h_dMsY28nrr50jNOTlZTWMQYX36AyqwsrvTkPz0Onm8zmB4Y0MNgn1MGgfwReChXshmcJbcTd92CrslrQ8bjj2B6cpTmyL6SDu0Fl6pSeercLjTvkkDP4TQS
        //curl --header "Authorization: key=AAAAtC_G_hU:APA91bFX3h22_WBunkt3YJJoUWjzg0nagmu91YOW6hWKND0CHN1RiX4B55MrFTKnpbpBlQBsB7iv_vZB5YX1PKxgwB__XXqi_I1GamM5aVfZV4zQp310U-aONJAfrTDRlMylyCxZb7AU" --header "Content-Type: application/json"  -d '{"notification": {"title": "curl topic test","body": "body curl topic test","click_action" : "https://dummypage.com"},"to" : "/topics/news_adverts"}' https://fcm.googleapis.com/fcm/send
        return null;
    }

    public function tempo(){
        //test changement bio
        //$response = $this->vimeoManager->request('/me', ['bio'=>'essair'], 'PATCH');

        //Check quota
//        $response = $this->vimeoManager->request('/me/videos', ['type'=>'streaming'], 'POST');
//        dd($response);

        //test upload serveur
//        $content = Storage::disk('local')->url('public/1.mp4');
//        $content = ($_SERVER["DOCUMENT_ROOT"].'/video/1.mp4');
//        $response = $this->vimeoManager->upload($content, false);
//        dd($response);

        //test changement param video
        //$response = json_decode(json_encode($this->vimeoManager->request('/me/videos')));
        //dd($response->body->data[0]->uri);
        //$response2 = $this->vimeoManager->request($response->body->data[0]->uri , ['privacy' => ['view' => 'anybody', 'download' => false]], 'PATCH');
        //$response2 = $this->vimeoManager->request($response->body->data[0]->uri , ['name' => 'Amazing video de ma face'], 'PATCH');
        //return null;


//        $response = $this->vimeoManager->request('/users/61078479/tickets/f25e11bfccf379c47454064572494e34',null,'GET');
//        dd($response);

//        session([
//            'totok' => 'totov',
//            'tatak' => 'tatav'
//        ]);
        //session(['videoId'=>'200465680']);
        return storage_path('whitelists').DIRECTORY_SEPARATOR.'facebook';

//        $success = Common\GeoIPUpdater::updateGeoIpFiles();
//        if($success) {
//            return response('ok', 200) ;
//        } else {
//            return response('echec', 200);
//        }



//        return response('ok',308,['Range'=>'bytes=0-1000']);
    }
}