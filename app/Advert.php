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

    protected $fillable = [ 'user_id', 'category_id', 'type', 'title', 'description', 'price', 'currency', 'latitude', 'longitude', 'geoloc'];
    protected $dates = ['deleted_at'];
    protected $appends = array('breadCrumb');
    private $breadcrumb;

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
    }

    public function setBreadCrumb($ancestors) {
        $this->breadcrumb = $ancestors;
    }

    public function getBreadCrumbAttribute() {
        return $this->breadcrumb;
    }

//    public function getGeolocAttribute($value) {
//        $key = env('GOOGLE_MAP_API_KEY');
//        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$this->latitude.','.$this->longitude.'&key='.$key;
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//
//        $exec = json_decode(curl_exec($ch), true);
//        $result = $exec['results'][1]['formatted_address'];
//
//        return $result;
//    }
}
