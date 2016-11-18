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
}