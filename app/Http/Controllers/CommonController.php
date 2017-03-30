<?php

namespace App\Http\Controllers;


use App\Anonymous;
use App\Common\UserUtils;
use App\Http\Requests\SubscribeNewsLetterRequest;
use Codeheures\LaravelUtils\Traits\Tools\Browser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => ['portal', 'subscribeNewsLetter', 'home', 'cgv', 'contact', 'contactPost', 'imageServer']]);
        $this->middleware('isEmailConfirmed', ['only' => ['mines']]);
    }

    /**
     * Return View Of Portal APP
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function portal() {
        $masterAdsControllerFlag = false;
        $browser = Browser::getBrowserName();
        return view('portal', compact('masterAdsControllerFlag', 'browser'));
    }

    /**
     * Process to newsletter subrcription XMLHttpRequest
     * @param SubscribeNewsLetterRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function subscribeNewsLetter(SubscribeNewsLetterRequest $request) {
        try {
            $existUser = Anonymous::whereMail($request->email)->first();
            if(!$existUser){
                UserUtils::createAnonymous($request->email, $request->name, $request->phone, null, true);
            }
            return response(trans('strings.view_portal_newsletter_subscribe_success'), 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_portal_newsletter_subscribe_error'), 500);
        }
    }

    /**
     * Return Home Adverts View
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home() {
        $masterAdsControllerFlag = true;
        return view('welcome', compact('masterAdsControllerFlag'));
    }

    /**
     * Return list of Mines Adverts XMLHttpRequest
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mines() {
        $routeList = route('advert.mines');
        return view('user.personnalList', compact('routeList'));
    }

    /**
     * Return View of my bookmarks adverts
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bookmarks() {
        return view('user.bookmarksList');
    }

    /**
     * Return view of CGV
     * @return string
     */
    public function cgv(){
        return view('global.cgv');
    }

    /**
     * Return view of contact
     * @return string
     */
    public function contact(){
        return view('global.contact');
    }

    public function contactPost(Request $request){
        //TODO ENVOI MAIL CONTACT A ADMIN
        return redirect(route('home'))->with('success', trans('strings.view_contact_success'));
    }

    public function imageServer(Request $request) {
        $clientResponse=null;

        if($request->has('url')){
            $client = new Client();
            $clientResponse = $client->get($request->url);
        }


        if($clientResponse->getStatusCode()<300){
            return response($clientResponse->getBody()->getContents(),200)->header("Content-Type", $clientResponse->getHeader("Content-Type"));
        } else {
            return response('not found',404);
        }
    }
}
