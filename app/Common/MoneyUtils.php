<?php

namespace App\Common;

use Codeheures\LaravelUtils\Traits\Tools\Currencies;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

trait MoneyUtils
{
    use Currencies;

    public static function getPriceWithDecimal($value, $currency, $withCurrency=true) {
        $money = new Money($value, new Currency($currency));
        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);
        $result = $moneyFormatter->format($money);

        if($withCurrency){
            $symbol = self::getSymbolByCurrencyCode($currency, config('runtime.locale'));
            $result .= $symbol;
        }

        return $result;
    }

    public static function setPriceWithoutDecimal($price, $currency){
        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);
        return $moneyParser->parse($price,$currency)->getAmount();
    }

    public static function listUserCurrencies()  {
        $tab  = self::listCurrencies(config('runtime.locale'));
        $tabIsSort = ksort($tab);
        $response = [
            'listCurrencies' => $tabIsSort ? $tab : self::listCurrencies(config('runtime.locale')),
            'userPrefCurrency' => config('runtime.currency')
        ];
        return $response;
    }
}