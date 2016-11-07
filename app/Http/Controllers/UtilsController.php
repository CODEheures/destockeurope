<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use Money\Currencies\ISOCurrencies;
use NumberFormatter;

class UtilsController extends Controller
{
    public function getListCurrencies()  {
        $currencies = new ISOCurrencies();

        $listCodeCurrencies=[];
        foreach ($currencies as $currency) {

            $region = auth()->user()->locale."@currency=$currency";
            $formatter = new NumberFormatter($region, NumberFormatter::CURRENCY);
            $symbol = $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

            $listCodeCurrencies[$currency->getCode()] = [
                'code' => $currency->getCode(),
                'symbol' => $symbol];
        }

        $userPreferedCurrency = auth()->user()->currency;
        $response = [
            'listCurrencies' => $listCodeCurrencies,
            'userPrefCurrency' => $userPreferedCurrency
        ];
        return response()->json($response);
    }

    public function getListLocales() {
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

        return response()->json($response);
    }

    public function tempo(){
        $disk = Storage::disk('local');
        return $disk;
    }
}
