<?php

namespace App\Http\Controllers;


class CommonController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => ['home', 'cgv']]);
    }

    public function home() {
        $masterAdsControllerFlag = true;
        return view('welcome', compact('masterAdsControllerFlag'));
    }

    public function mines() {
        return view('user.personnalList');
    }

    public function cgv(){
        return 'cgv';
    }
}
