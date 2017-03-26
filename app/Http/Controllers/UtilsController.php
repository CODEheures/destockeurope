<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Common\AdvertsManager;
use App\Common\PicturesManager;
use App\Stats;
use Carbon\Carbon;
use GeoIp2\Database\Reader;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mockery\Matcher\Not;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Topic;
use App\Common\LocaleUtils;
use App\Common\MoneyUtils;
use Vinkla\Vimeo\VimeoManager;

class UtilsController extends Controller
{

    use LocaleUtils;
    use MoneyUtils;

    private $vimeoManager;

    public function __construct(VimeoManager $vimeoManager) {
        $this->middleware('auth', ['only' => ['getListLocales', 'getListCardsType', 'getGeoByIp']]);
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

    public function geoByIp($ip=null) {
        $result = $this->getGeoByIp($ip);
        $response = new Response();
        $response->header('Content-Type','application/json ; charset=UTF-8');
        $response->setContent(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    public function tempo(){

    }
}