<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class Advert extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'user_id', 'category_id', 'type', 'title', 'description', 'price', 'currency'];
    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function category() {
        return $this->belongsTo('App\Category');
    }

    public function getPriceAttribute($value) {

        $money = new Money($value, new Currency($this->currency));
        $currencies = new ISOCurrencies();



        $numberFormatter = new \NumberFormatter('fr_FR', \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
        //return $this->currency;
    }
}
