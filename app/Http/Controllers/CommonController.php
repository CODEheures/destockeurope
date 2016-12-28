<?php

namespace App\Http\Controllers;


use App\Anonymous;
use App\Common\UserUtils;
use App\Http\Requests\SubscribeNewsLetterRequest;
use App\User;

class CommonController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => ['portal', 'subscribeNewsLetter', 'home', 'cgv']]);
    }

    public function portal() {
        $masterAdsControllerFlag = true;
        return view('portal', compact('masterAdsControllerFlag'));
    }

    public function subscribeNewsLetter(SubscribeNewsLetterRequest $request) {
        try {
            $existUser = Anonymous::where('email', '=', $request->email)->first();
            if(!$existUser){
                UserUtils::createAnonymous($request->email, $request->name, $request->phone, null, true);
            }
            return response(trans('strings.view_portal_newsletter_subscribe_success'), 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_portal_newsletter_subscribe_error'), 500);
        }
    }

    public function home() {
        $masterAdsControllerFlag = true;
        return view('welcome', compact('masterAdsControllerFlag'));
    }

    public function mines() {
        return view('user.personnalList');
    }

    public function bookmarks() {
        return view('user.bookmarksList');
    }

    public function cgv(){
        return 'cgv';
    }
}
