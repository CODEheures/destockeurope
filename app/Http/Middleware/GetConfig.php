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
            config(['runtime.minAdvertsPerPage' => $parameters->advertsPerPage]);
            config(['runtime.advertsPerPage' => $parameters->advertsPerPage]);
            config(['runtime.photoCost' => $parameters->photoCost]);
            config(['runtime.urgentCost' => $parameters->urgentCost]);
            config(['runtime.backToTopCost' => $parameters->backToTopCost]);
            config(['runtime.highlightCost' => $parameters->highlightCost]);
            config(['runtime.videoCost' => $parameters->videoCost]);
            config(['runtime.renewCost' => $parameters->renewCost]);
            config(['runtime.editCost' => $parameters->editCost]);
            config(['runtime.validityCosts' => $parameters->validityCosts]);
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

        // redefine perPage
        if($request->filled('perPage') && filter_var($request->perPage, FILTER_VALIDATE_INT) && (int)($request->perPage) > config('runtime.advertsPerPage') && (int)($request->perPage) < 101){
            config(['runtime.advertsPerPage' => (int)($request->perPage)]);
        }

            config(['runtime.stopAnalytics' => false]);

        //Runtime query
        config(['runtime.query' => []]);
        try {
            config(['runtime.query' => $request->query()]);
        } catch (\Exception $e) {

        }

        return $next($request);

    }
}
