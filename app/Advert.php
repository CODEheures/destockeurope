<?php

namespace App;

use App\Common\MoneyUtils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'isUrgent'
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
        return MoneyUtils::getPriceWithDecimal($value, $this->currency);
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
