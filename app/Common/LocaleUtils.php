<?php

namespace App\Common;


use Codeheures\LaravelUtils\Traits\Tools\Locale;
use Codeheures\LaravelUtils\Traits\Locale\ManageLocale;
use Illuminate\Support\Facades\Lang;

trait LocaleUtils
{
    use Locale;
    use ManageLocale;

    /**
     *
     * Une liste des locales et de la locale user
     *
     * @return array
     */
    public static function listUserLocales() {
        $response = [
            'listLocales' => self::listLocales(),
            'userPrefLocale' => config('runtime.locale')
        ];

        return $response;
    }

    /**
     *
     * List de noms de pays traduits
     *
     * @return array
     */
    public static function getListCountries(){
        $countries = [];
        foreach (Lang::get('strings') as $key => $item){
            if (strpos($key, 'country_') !== false && strpos($key, 'country') === 0) {
                $countries[str_replace('country_','',$key)]=$item;
            }
        }
        return $countries;
    }
}