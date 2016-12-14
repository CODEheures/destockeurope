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
        'invoice_id',
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
        'options',
        'cost',
        'totalQuantity',
        'lotMiniQuantity',
        'isUrgent',
        'views'
    ];
    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['pictures', 'bookmarks'];
    protected $casts = [
        'options' => 'array'
    ];
    protected $appends = array('breadCrumb', 'url', 'resume', 'thumb', 'isUserOwner', 'isUserBookmark', 'bookmarkCount');
    private $breadcrumb;
    private $resumeLength;
    private $isUserBookmark = false;
    private $bookmarkCount = 0;

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

    public function picturesWithTrashed() {
        return $this->hasMany('App\Picture')->withTrashed();
    }

    public function invoice() {
        return $this->belongsTo('App\Invoice');
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

    public function getIsUserOwnerAttribute() {
        if(auth()->check()){
            return auth()->user()->id === $this->user_id;
        }
        return false;
    }

    public function setIsUserBookmark($result) {
        $this->isUserBookmark = $result;
    }

    public function getIsUserBookmarkAttribute() {
        return $this->isUserBookmark;
    }

    public function setBookmarkCount() {
        $this->bookmarkCount = $this->bookmarks()->count();
    }

    public function getBookmarkCountAttribute() {
        return $this->bookmarkCount;
    }
}
