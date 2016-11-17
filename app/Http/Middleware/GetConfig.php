<?php

namespace App\Http\Middleware;

use App\Common;
use Closure;
use Intervention\Image\Exception\NotReadableException;
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
            config(['runtime.welcomeType' => $config->welcomeType]);

            if($config->masterAds && $config->urlMasterAds && $config->urlMasterAds != ''){
                try {
                    $width = Image::make($config->urlMasterAds)->width();
                    config(['runtime.widthUrlMasterAds' => $width]);
                } catch (NotReadableException $e) {
                    config(['runtime.widthUrlMasterAds' => 0]);
                }
            } else {
                config(['runtime.widthUrlMasterAds' => 0]);
            }
        }


        return $next($request);

    }
}
