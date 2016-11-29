<?php

namespace App;

use App\Common\MoneyUtils;
use App\Common\PicturesManager;
use Carbon\Carbon;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Advert extends Model {
    use SoftDeletes;
    use CascadeSoftDeletes;

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
        'isUrgent',
        'views'
    ];
    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['pictures', 'bookmarks'];
    protected $appends = array('breadCrumb', 'url', 'resume', 'thumb');
    private $breadcrumb;
    private $resumeLength;

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function bookmarks() {
        return $this->hasMany('App\Bookmark');
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

    public function getConstructBreadCrumb() {
        $locale = App::getLocale();
        $bread = '';
        foreach ($this->breadcrumb as $item){
            if($bread == '') {
                $bread = $item->description[$locale];
            } else {
                $bread = $bread . ' > ' . $item->description[$locale];
            }
        }
        return $bread;
    }

    public function getUrlAttribute() {
        return route('advert.show', ['id' => $this->id]);
    }

    public function getThumbAttribute() {
        return route('picture.thumb', ['type' => PicturesManager::TYPE_FINAL_LOCAL, 'hashName' => $this->mainPicture, 'advertId' => $this->id]);
    }

    public function setResumeLength($length) {
        if(!$length || !is_numeric($length) || $length == 0){
            $this->resumeLength = config('runtime.advertResumeLenght');
        } else {
            $this->resumeLength = $length;
        }
    }

    public function getResumeAttribute() {
        if(!$this->resumeLength || !is_numeric($this->resumeLength) || $this->resumeLength == 0){
            $this->resumeLength = config('runtime.advertResumeLenght');
        }
        return ucfirst(substr($this->description, 0,$this->resumeLength)).'...';
    }

    public function getUpdatedAtAttribute($time) {
        return Carbon::parse($time)->toAtomString();
    }
}
