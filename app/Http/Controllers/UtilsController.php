<?php

namespace App\Http\Controllers;

use App\Common\PicturesManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
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
        $this->middleware('auth', ['only' => ['getListCurrencies', 'getListLocales', 'getListCardsType']]);
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