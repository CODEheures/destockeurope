<?php

namespace App\Http\Controllers;


class CommonController extends Controller
{
    public function __construct() {
        $this->middleware('isAdminUser', ['except' => ['home', 'cgv']]);
    }

    public function home() {
        $masterAdsControllerFlag = true;
        return view('welcome', compact('masterAdsControllerFlag'));
    }

    public function cgv(){
        return 'cgv';
    }
}
