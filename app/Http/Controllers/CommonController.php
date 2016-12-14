<?php

namespace App\Http\Controllers;

use App\Common;
use App\Common\DBUtils;
use App\Picture;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function __construct() {
        $this->middleware('isAdminUser', ['except' => ['home', 'cgv']]);
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

    public function cleanApp() {
        try {
            $pictureManager = new Common\PicturesManager();
            $advertManager = new Common\AdvertsManager($pictureManager);
            $info = $advertManager->purge();
            return back()->with('info', $info);
        } catch (\Exception $e) {
            return back()->withErrors($e);
        }

    }

    public function lightenLocalDisk() {
        $lot = 1;
        //move pictures to distant disk
        $pictureManager = new Common\PicturesManager();
        $pictures = Picture::where('disk', '=', 'local')->take($lot)->get();
        foreach ($pictures as $picture){
            $pictureManager->moveToDistantFinal($picture);
        }
        return back()->with('info', trans('strings.admin_transfert_image_response', ['nb' => $lot, 'disk' => $pictureManager::DISK_DISTANT]));
    }

    public function getWelcomeType() {
        $list = DBUtils::getEnumValues('commons', 'welcomeType');
        $transList = [];
        foreach ($list as $key => $item) {
            $transList[$key] =  $item;
        }
        return response()->json($transList);
    }

    public function cgv(){
        return 'cgv';
    }
}
