<?php

namespace App\Http\Middleware;

use App\Parameters;
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

        $parameters = Parameters::first();
        if(!$parameters) {
            Parameters::create();
            $parameters = Parameters::first();
        }
        if($parameters){
            config(['runtime.masterAds' => $parameters->masterAds]);
            config(['runtime.urlMasterAds' => $parameters->urlMasterAds]);
            config(['runtime.urlLinkMasterAds' => $parameters->urlLinkMasterAds]);
            config(['runtime.offsetYMasterAds' => $parameters->offsetYMasterAds]);
            config(['runtime.adsFrequency' => $parameters->adsFrequency]);
            config(['runtime.advertsPerPage' => $parameters->advertsPerPage]);
            config(['runtime.urgentCost' => $parameters->urgentCost]);
            config(['runtime.backToTopCost' => $parameters->backToTopCost]);
            config(['runtime.videoCost' => $parameters->videoCost]);
            config(['runtime.renewCost' => $parameters->renewCost]);
            config(['runtime.nbFreePictures' => $parameters->nbFreePictures]);
            config(['runtime.nbMaxPictures' => $parameters->nbMaxPictures]);
            config(['runtime.welcomeType' => $parameters->welcomeType]);
            config(['runtime.advertResumeLenght' => $parameters->advertResumeLenght]);
            config(['runtime.minLengthSearch' => $parameters->minLengthSearch]);
            config(['runtime.maxNumberOfSearchResults' => $parameters->maxNumberOfSearchResults]);
            if($parameters->masterAds && $parameters->urlMasterAds && $parameters->urlMasterAds != ''){
                try {
                    $width = Image::make($parameters->urlMasterAds)->width();
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
