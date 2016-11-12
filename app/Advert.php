<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class Advert extends Model {
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'payment_id',
        'type',
        'title',
        'description',
        'price',
        'currency',
        'latitude',
        'longitude',
        'geoloc',
        'mainPicture',
        'isPublish',
        'isValid',
        'cost',
        'totalQuantity',
        'lotMiniQuantity',
        'urgent'
    ];
    protected $dates = ['deleted_at'];
    protected $appends = array('breadCrumb');
    private $breadcrumb;

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function category() {
        return $this->belongsTo('App\Category');
    }

    public function pictures() {
        return $this->hasMany('App\Picture');
    }

    public function payment() {
        return $this->hasOne('App\Payment');
    }

    public function getPriceAttribute($value) {

        $money = new Money($value, new Currency($this->currency));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter('fr_FR', \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }

    public function setBreadCrumb($ancestors) {
        $this->breadcrumb = $ancestors;
    }

    public function getBreadCrumbAttribute() {
        return $this->breadcrumb;
    }

    public function getUpdatedAtAttribute($time) {
        return Carbon::parse($time)->toAtomString();
    }
}
