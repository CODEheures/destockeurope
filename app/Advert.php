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
        'online_at',
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
        'views',
        'lastObsoleteMail',
        'isRenew',
        'originalAdvertId'
    ];
    protected $dates = ['deleted_at', 'online_at'];
    protected $cascadeDeletes = ['pictures', 'bookmarks'];
    protected $casts = [
        'options' => 'array'
    ];
    protected $appends = array('breadCrumb', 'url', 'renewUrl', 'destroyUrl', 'resume', 'thumb', 'isEligibleForRenew', 'isUserOwner', 'isUserBookmark', 'bookmarkCount', 'picturesWithTrashedCount');
    private $breadcrumb;
    private $resumeLength;
    private $isUserBookmark = false;
    private $bookmarkCount = 0;

    //Relations
    public function user() { return $this->belongsTo('App\User'); }
    public function bookmarks() { return $this->hasMany('App\Bookmark'); }
    public function category() { return $this->belongsTo('App\Category'); }
    public function pictures() { return $this->hasMany('App\Picture'); }
    public function picturesWithTrashed() { return $this->hasMany('App\Picture')->withTrashed(); }
    public function invoice() { return $this->belongsTo('App\Invoice'); }


    //Attributs Getters
    public function getPriceAttribute($value) {
        return MoneyUtils::getPriceWithDecimal($value, $this->currency);
    }

    public function getBreadCrumbAttribute() {
        return $this->breadcrumb;
    }

    public function getUrlAttribute() {
        return route('advert.show', ['id' => $this->id]);
    }

    public function getRenewUrlAttribute() {
        return route('advert.renew', ['id' => $this->id]);
    }

    public function getDestroyUrlAttribute() {
        return route('advert.destroy', ['id' => $this->id]);
    }

    public function getIsEligibleForRenewAttribute() {
        return (!$this->isRenew && ($this->deleted_at || Carbon::parse($this->online_at)->addDay(env('ADVERT_LIFE_TIME'))->subDays(env('ALERT_BEFORE_END_1'))->isPast(Carbon::now())));
    }

    public function getThumbAttribute() {
        return route('picture.thumb', ['type' => PicturesManager::TYPE_FINAL_LOCAL, 'hashName' => $this->mainPicture, 'advertId' => $this->id]);
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

    public function getOnlineAtAttribute($time) {
        return Carbon::parse($time)->toAtomString();
    }

    public function getIsUserOwnerAttribute() {
        if(auth()->check()){
            return auth()->user()->id === $this->user_id;
        }
        return false;
    }

    public function getIsUserBookmarkAttribute() {
        return $this->isUserBookmark;
    }

    public function getBookmarkCountAttribute() {
        return $this->bookmarkCount;
    }

    public function getPicturesWithTrashedCountAttribute() {
        return $this->hasMany('App\Picture')->withTrashed()->count();
    }

    //Public functions
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

    public function setBreadCrumb($ancestors) {
        $this->breadcrumb = $ancestors;
    }

    public function setIsUserBookmark($result) {
        $this->isUserBookmark = $result;
    }

    public function setBookmarkCount() {
        $this->bookmarkCount = $this->bookmarks()->count();
    }

    //Locals Scopes
    public function scopeInvalid($query) {
        return $query->where('isValid', '=', false);
    }

    public function scopeWaiting($query) {
        return $query->where('isPublish', '=', true)->where('isValid', '=', null);
    }

    public function scopeAbandonned($query) {
        return $query->where('isPublish', '=', false)->where('created_at', '<', Carbon::now()->subHours(2));
    }

    public function scopeAbandonnedRenew($query) {
        return $query->whereNotNull('originalAdvertId')->whereNull('invoice_id')->whereNull('online_at')->where('created_at', '<', Carbon::now()->subHours(2));
    }

    public function scopeObsoletes($query) {
        return $query->onlyTrashed()->where('deleted_at', '<', Carbon::now()->subDay(env('DELAY_DAYS_FOR_RENEW_ADVERT')));
    }

    public function scopeFinished($query) {
        return $query->where('online_at', '<', Carbon::now()->subDay(env('ADVERT_LIFE_TIME')));
    }

    public function scopeEligibleForMailRenew($query, $days) {
        if($days > 0) {
            return $query->whereDate('online_at' , '=', Carbon::now()->subDay(env('ADVERT_LIFE_TIME')-$days)->toDateString())
                ->where('isValid', true)
                ->where(function ($query) use ($days){
                    $query->whereNull('lastObsoleteMail')
                        ->orWhere('lastObsoleteMail', '>', $days);
                });
        } else {
            return $query->onlyTrashed()
                ->where('isValid', true)
                ->where('lastObsoleteMail', '<>', 0);
        }
    }

    public function scopeMines($query) {
        return $query->withTrashed()
            ->where('user_id', '=', auth()->id())->where('isValid', true)
            ->where('online_at', '<', Carbon::now())
            ->where(function ($queryIn){
                $queryIn->where('isRenew', false)
                    ->orWhere('deleted_at', null);
            })
            ->orderBy('online_at', 'desc');
    }

    public function scopeOnlyPublish($query){
        return $query->where('isPublish', true)->where('isValid', null);
    }

    public function scopeValidOnline($query){
        return $query->where('isValid', true)->where('online_at', '<', Carbon::now());
    }

    public function scopeInBookmarks($query, $bookmarks){
        return $query->whereIn('id', $bookmarks)->validOnline()->orderBy('online_at', 'desc');
    }
}
