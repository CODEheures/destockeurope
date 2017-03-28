<?php

namespace App\Http\Middleware;

use App\Parameters;
use Closure;
use Codeheures\LaravelGeoUtils\Traits\GeoUtils;
use Codeheures\LaravelTools\Traits\Currencies;
use Codeheures\LaravelTools\Traits\Ip;
use Codeheures\LaravelTools\Traits\Locale;
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

        //config('runtime.ip')
        config(['runtime.ip' => Ip::getNonPrivateIpByRequest($request)]);

        //config('runtime.locale')
        if(!session()->has('runtime.http_accept_language') || $request->server('HTTP_ACCEPT_LANGUAGE') != session('runtime.http_accept_language')){
            session()->forget('runtime.locale');
            session(['runtime.http_accept_language' => $request->server('HTTP_ACCEPT_LANGUAGE')]);
        }
        $httpAccept = \Locale::acceptFromHttp($request->server('HTTP_ACCEPT_LANGUAGE'));
        $requestLocale = \Locale::getPrimaryLanguage($request->server('HTTP_ACCEPT_LANGUAGE'));
        $requestRegion = \Locale::getRegion($httpAccept);
        try {
            if (auth()->check()) {
                config(['runtime.locale' => auth()->user()->locale]);
            } elseif (session('runtime.locale')) {
                config(['runtime.locale' => session('runtime.locale')]);
            } else {
                $locale = null;
                if ($requestLocale != '' && $requestRegion != '') {
                    $locale = Locale::composeLocale($requestLocale, $requestRegion);
                } elseif ($requestLocale == '') {
                    $locale = env('DEFAULT_LOCALE');
                } else {
                    $country = GeoUtils::getCountryByIp(config('runtime.ip'));
                    if (!$country || $country == '') {
                        $locale = env('DEFAULT_LOCALE');
                    } else {
                        $locale = Locale::composeLocale($country, $requestLocale);
                    }
                }

                if ($locale == null) {
                    $locale = env('DEFAULT_LOCALE');
                }
                config(['runtime.locale' => $locale]);
            }

            if(!Locale::existLocale(config('runtime.locale'))){
                config(['runtime.locale' => env('DEFAULT_LOCALE')]);
            }

        } catch(\Exception $e) {
            config(['runtime.locale' => env('DEFAULT_LOCALE')]);
        }
        session(['runtime.locale' => config('runtime.locale')]);

        //config('runtime.currency')
        try {
            if(auth()->check()) {
                config(['runtime.currency' => auth()->user()->currency]);
            } else {
                $formatter = new \NumberFormatter(config('runtime.locale'), \NumberFormatter::CURRENCY);
                config(['runtime.currency' => $formatter->getTextAttribute(\NumberFormatter::CURRENCY_CODE)]);
            }

            if(!Currencies::isAvailableCurrency(config('runtime.currency'))) {
                config(['runtime.currency' => env('DEFAULT_CURRENCY')]);
            }
        } catch (\Exception $e) {
            config(['runtime.currency' => env('DEFAULT_CURRENCY')]);
        }
        return $next($request);

    }
}
