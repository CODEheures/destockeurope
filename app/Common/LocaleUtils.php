<?php

namespace App\Common;


use App\User;
use Codeheures\LaravelTools\Traits\Locale;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

trait LocaleUtils
{
    use Locale;

    public static function listUserLocales() {
        $response = [
            'listLocales' => self::listLocales(),
            'userPrefLocale' => config('runtime.locale')
        ];

        return $response;
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

    public static function switchToUserLocale(User $user) {
        session(['runtimeLocale' => App::getLocale()]);
        self::setAppLocale(\Locale::getPrimaryLanguage($user->locale));
    }

    public static function switchToRuntimeLocale() {
        self::setAppLocale(session('runtimeLocale'));
    }
}