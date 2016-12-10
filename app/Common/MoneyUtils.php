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

        if($withCurrency){
            $numberFormatter = new \NumberFormatter('fr_FR', \NumberFormatter::CURRENCY);
            $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);
        } else {
            $moneyFormatter = new DecimalMoneyFormatter($currencies);
        }

        return $moneyFormatter->format($money);
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
        if ($currencies->contains(new Currency($currency)) && $currency != '') {
            return $currency;
        } else {
            return env('DEFAULT_CURRENCY');
        }
    }

    public static function listCurrencies()  {
        $currencies = new ISOCurrencies();

        $listCodeCurrencies=[];
        foreach ($currencies as $currency) {

            $region = auth()->user()->locale."@currency=$currency";
            $formatter = new \NumberFormatter($region, \NumberFormatter::CURRENCY);
            $symbol = $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);

            if($symbol != '') {
                $listCodeCurrencies[$currency->getCode()] = [
                    'code' => $currency->getCode(),
                    'symbol' => $symbol];
            }
        }

        $userPreferedCurrency = auth()->user()->currency;
        $response = [
            'listCurrencies' => $listCodeCurrencies,
            'userPrefCurrency' => $userPreferedCurrency
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