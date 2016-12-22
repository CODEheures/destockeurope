<?php

namespace App\Http\Middleware;

use App\Common;
use App\Common\MoneyUtils;
use Closure;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;
use App\Common\LocaleUtils;

class GetConfig
{

    use LocaleUtils;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $httpAccept = \Locale::acceptFromHttp($request->server('HTTP_ACCEPT_LANGUAGE'));
        $requestLocale = \Locale::getPrimaryLanguage($request->server('HTTP_ACCEPT_LANGUAGE'));
        $requestRegion = \Locale::getRegion($httpAccept);

        $config = Common::first();
        if($config){
            config(['runtime.masterAds' => $config->masterAds]);
            config(['runtime.urlMasterAds' => $config->urlMasterAds]);
            config(['runtime.urlLinkMasterAds' => $config->urlLinkMasterAds]);
            config(['runtime.offsetYMasterAds' => $config->offsetYMasterAds]);
            config(['runtime.adsFrequency' => $config->adsFrequency]);
            config(['runtime.advertsPerPage' => $config->advertsPerPage]);
            config(['runtime.urgentCost' => $config->urgentCost]);
            config(['runtime.nbFreePictures' => $config->nbFreePictures]);
            config(['runtime.nbMaxPictures' => $config->nbMaxPictures]);
            config(['runtime.welcomeType' => $config->welcomeType]);
            config(['runtime.advertResumeLenght' => $config->advertResumeLenght]);
            config(['runtime.minLengthSearch' => $config->minLengthSearch]);
            config(['runtime.maxNumberOfSearchResults' => $config->maxNumberOfSearchResults]);
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

        //config('runtime.ip')
        strpos($request->ip(), '198') != false ? config(['runtime.ip' => $request->ip()]) : config(['runtime.ip' => env('DEFAULT_IP')]);

        //config('runtime.locale')
        try {
            if (auth()->check()) {
                config(['runtime.locale' => auth()->user()->locale]);
            } elseif (session('runtime.locale')) {
                config(['runtime.locale' => session('runtime.locale')]);
            } else {
                $locale = null;
                if ($requestLocale != '' && $requestRegion != '') {
                    $locale = LocaleUtils::composeLocale($requestLocale, $requestRegion);
                } elseif ($requestLocale == '') {
                    $locale = env('DEFAULT_LOCALE');
                } else {
                    $country = $this->getCountryByIp(config('runtime.ip'));
                    if (!$country || $country == '') {
                        $locale = env('DEFAULT_LOCALE');
                    } else {
                        $locale = LocaleUtils::composeLocale($country, $requestLocale);
                    }
                }

                if ($locale == null) {
                    $locale = env('DEFAULT_LOCALE');
                }
                config(['runtime.locale' => $locale]);
            }

            if(!LocaleUtils::existLocale(config('runtime.locale'))){
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

            if(!MoneyUtils::isAvailableCurrency(config('runtime.currency'))) {
                config(['runtime.currency' => env('DEFAULT_CURRENCY')]);
            }
        } catch (\Exception $e) {
            config(['runtime.currency' => env('DEFAULT_CURRENCY')]);
        }
        return $next($request);

    }
}
