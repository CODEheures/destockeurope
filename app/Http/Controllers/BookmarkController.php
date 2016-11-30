<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Bookmark;
use Illuminate\Http\Request;

use App\Http\Requests;

class BookmarkController extends Controller
{


    public function __construct() {

    }


    public function add($advertId) {
        if(!auth()->check()){
            return response(trans('strings.view_advert_show_bookmark_auth_required'), 409);
        }
        $advert = Advert::find($advertId);
        if($advert){
            $existBookmark = Bookmark::where('advert_id', $advert->id)->where('user_id', auth()->user()->id)->first();
            if($existBookmark){
                return response('ok', 200);
            } else {
                $newBookmark = Bookmark::create([
                    'user_id' => auth()->user()->id,
                    'advert_id' => $advert->id
                ]);
                $newBookmark->save();
                return response('ok', 200);
            }
        } else {
            return response('error', 500);
        }
    }

    public function remove($advertId) {
        if(!auth()->check()){
            return response(trans('strings.view_advert_show_bookmark_auth_required'), 409);
        }
        $existBookmark = Bookmark::where('advert_id', $advertId)->where('user_id', auth()->user()->id)->first();
        if($existBookmark){
            $existBookmark->delete();
            return response('ok', 200);
        } else {
            return response('error', 500);
        }
    }

}
