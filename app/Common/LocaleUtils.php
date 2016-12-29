<?php

namespace App\Common;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

trait LocaleUtils
{
    public static function listLocales() {
        $locales = \ResourceBundle::getLocales('');

        $listLocales = [];
        foreach ($locales as $locale) {
            $listLocales[$locale] = [
                'code' => $locale,
                'name' => \Locale::getDisplayName($locale),
                'region' => strtolower(\Locale::getDisplayRegion($locale))
            ];
        }

        return $listLocales;
    }

    public static function existLocale($locale) {
        $listLocales = self::listLocales();
        foreach ($listLocales as $item) {
            if($item['code'] == $locale){
                return true;
            }
        }
        return false;
    }

    public static function listUserLocales() {
        $response = [
            'listLocales' => self::listLocales(),
            'userPrefLocale' => config('runtime.locale')
        ];

        return $response;
    }

    public static function getFirstLocaleByCountryCode($countryCode){
        foreach (self::listLocales() as $locale){
            if(strpos($locale['code'], '_'. strtoupper($countryCode))){
                return $locale['code'];
            }
        }
        return null;
    }

    public static function composeLocale($language, $country){
        $locale = \Locale::composeLocale( [
            'language' => $language,
            'region' => $country
        ] );
        if(self::existLocale($locale)){
            return $locale;
        } else {
            return null;
        }
    }

    public static function getCountryByIp($ip) {
        $details = file_get_contents("http://ipinfo.io/{$ip}/country");
        return $details;
    }

    public static function getGeoLocByIp($ip) {
        $loc = false;
        $details = file_get_contents("http://ipinfo.io/{$ip}/geo");
        if($details){
            $loc = explode(',', json_decode($details)->loc);
        }
        return $loc;
    }

    public static function getListCountries(){
        $countries = [];
        foreach (Lang::get('strings') as $key => $item){
            if (strpos($key, 'country_') !== false && strpos($key, 'country') === 0) {
                $countries[str_replace('country_','',$key)]=$item;
            }
        }
        return $countries;
    }

    public static function setAppLocale($locale){
        if(in_array($locale,config('app.locales'))){
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.fallback_locale'));
        }
    }
}