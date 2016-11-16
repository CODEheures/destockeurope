<?php

namespace App\Http\Middleware;

use App\Common;
use Closure;
use Intervention\Image\Facades\Image;

class GetConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $config = Common::first();
        if($config){
            config(['runtime.masterAds' => $config->masterAds]);
            config(['runtime.urlMasterAds' => $config->urlMasterAds]);
            config(['runtime.offsetYMasterAds' => $config->offsetYMasterAds]);
            config(['runtime.adsFrequency' => $config->adsFrequency]);
            config(['runtime.advertsPerPage' => $config->advertsPerPage]);
            config(['runtime.urgentCost' => $config->urgentCost]);
            config(['runtime.nbFreePictures' => $config->nbFreePictures]);
            config(['runtime.nbMaxPictures' => $config->nbMaxPictures]);
        }

        if($config->masterAds && $config->urlMasterAds && $config->urlMasterAds != ''){
            $width = Image::make($config->urlMasterAds)->width();
            config(['runtime.widthUrlMasterAds' => $width]);
        } else {
            config(['runtime.widthUrlMasterAds' => 0]);
        }


        return $next($request);

    }
}
