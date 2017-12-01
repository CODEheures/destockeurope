<?php

namespace App\Common;


use Carbon\Carbon;
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
        $tab = self::listLocales();
        $tabIsSort = uasort($tab, array('self', 'compareNameLocale'));

        $response = [
            'listLocales' => $tabIsSort ? $tab :  self::listLocales(),
            'userPrefLocale' => config('runtime.locale')
        ];

        return $response;
    }

    private static function compareNameLocale($loc1, $loc2) {
        if ($loc1['name'] == $loc2['name']) {
            return 0;
        }
        return ($loc1['name'] < $loc2['name']) ? -1 : 1;
    }

    /**
     *
     * List de noms de pays traduits
     *
     * @return array
     */
    public static function getListCountries($lower = false){
        $countries = [];
        foreach (Lang::get('strings') as $key => $item){
            if (strpos($key, 'country_') !== false && strpos($key, 'country') === 0) {
                $params = explode(';', $item);
                if ($lower) {
                    $countries[str_replace('country_','',$key)]=[
                        'name' => strtolower($params[0]),
                        'code' => strtolower($params[1])
                    ];
                } else {
                    $countries[str_replace('country_','',$key)]=[
                        'name' => $params[0],
                        'code' => $params[1]
                    ];
                }

            }
        }
        return $countries;
    }

    /**
     *
     * get a translate date without php setlocale() (because of this is process not a thread)
     * return type = "vendredi 7 avril 2017"
     *
     * @param $date
     * @return string
     */
    public static function getTransDate($date) {
        $dateString = trans('strings.'. strtolower(Carbon::parse($date)->formatLocalized('%A'))) . ' '
            . Carbon::parse($date)->formatLocalized('%e') . ' '
            . trans('strings.'. strtolower(Carbon::parse($date)->formatLocalized('%B'))) . ' '
            . Carbon::parse($date)->formatLocalized('%Y');

        return $dateString;
    }
}