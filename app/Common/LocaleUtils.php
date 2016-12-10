<?php

namespace App\Common;


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

        $userPreferedLocale = auth()->user()->locale;
        $response = [
            'listLocales' => $listLocales,
            'userPrefLocale' => $userPreferedLocale
        ];

        return $response;
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
}