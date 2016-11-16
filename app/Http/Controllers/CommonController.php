<?php

namespace App\Http\Controllers;

use App\Common;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function __construct() {
        $this->middleware('isAdminUser', ['except' => ['home']]);
    }

    public function home() {
        $masterAdsControllerFlag = true;
        return view('welcome', compact('masterAdsControllerFlag'));
    }

    public function manage(){
        return view('application.manage');
    }

    public function appParameters() {
        $parameters = Common::orderBy('id', 'desc')->first();
        if($parameters){
            return response()->json($parameters);
        } else {
            $parameters = new Common();
            $parameters->save();

            $parameters = Common::first();
            if($parameters){
                return response()->json($parameters);
            } else {
                return response('error', 500);
            }
        }
    }

    public function patchParameters(Request $request) {
        $parameters = Common::orderBy('id', 'desc')->first();
        if($parameters){
            $parameters->update($request->all());
            $parameters->save();
            return response('ok',200);
        } else {
            return response('error', 500);
        }
    }
}
