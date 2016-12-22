<?php

namespace App\Common;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

trait MoneyUtils
{
    public static function getPriceWithDecimal($value, $currency, $withCurrency=true) {
        $money = new Money($value, new Currency($currency));
        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);
        $result = $moneyFormatter->format($money);

        if($withCurrency){
            $symbol = self::getSymbolByCurrencyCode($currency);
            $result .= $symbol;
        }

        return $result;
    }

    public static function setPriceWithoutDecimal($price, $currency){
        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);
        return $moneyParser->parse($price,$currency)->getAmount();
    }

    public static function getDefaultMoneyByLocale($locale) {
        $currencies = new ISOCurrencies();
        $numberFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $currency =  $numberFormatter->getTextAttribute(\NumberFormatter::CURRENCY_CODE);
        if ($currency != '' && $currencies->contains(new Currency($currency))) {
            return $currency;
        } else {
            return config('runtime.currency');
        }
    }

    public static function getSymbolByCurrencyCode($currencyCode) {
        $listCurrencies = self::listCurrencies();
        foreach ($listCurrencies as $currency){
            if($currency['code'] == $currencyCode){
                return $currency['symbol'];
            }
        }
        return null;
    }

    public static function listCurrencies()  {
        $currencies = new ISOCurrencies();

        $listCodeCurrencies=[];
        foreach ($currencies as $currency) {

            $region = config('runtime.locale')."@currency=$currency";
            $formatter = new \NumberFormatter($region, \NumberFormatter::CURRENCY);
            $symbol = $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);

            if($symbol != '') {
                $listCodeCurrencies[$currency->getCode()] = [
                    'code' => $currency->getCode(),
                    'symbol' => $symbol];
            }
        }
        return $listCodeCurrencies;
    }

    public static function listUserCurrencies()  {
        $response = [
            'listCurrencies' => self::listCurrencies(),
            'userPrefCurrency' => config('runtime.currency')
        ];
        return $response;
    }

    public static function isAvailableCurrency($currency) {
        $currencies = new ISOCurrencies();
        if ($currencies->contains(new Currency($currency)) && $currency != '') {
            return true;
        }
        return false;
    }
}