<?php

namespace App;

use App\Common\MoneyUtils;
use App\Common\PicturesManager;
use Carbon\Carbon;
use Codeheures\LaravelUtils\Traits\Tools\Currencies;
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
        'slug',
        'description',
        'price',
        'price_coefficient',
        'price_margin',
        'price_margin_decimal',
        'currency',
        'latitude',
        'longitude',
        'geoloc',
        'mainPicture',
        'isPublish',
        'isValid',
        'totalQuantity',
        'lotMiniQuantity',
        'isUrgent',
        'views',
        'lastObsoleteMail',
        'isRenew',
        'is_delegation',
        'originalAdvertId',
        'video_id',
        'isNegociated',
        'manu_ref'
    ];
    protected $dates = ['deleted_at', 'online_at'];
    protected $cascadeDeletes = ['pictures', 'bookmarks'];
    protected $appends = array('breadCrumb', 'url', 'renewUrl', 'destroyUrl', 'resume', 'titleWithManuRef', 'thumb', 'isEligibleForRenew', 'isEligibleForRenewMailZero', 'isUserOwner', 'isUserBookmark', 'bookmarkCount', 'picturesWithTrashedCount', 'originalPrice', 'priceSubUnit', 'currencySymbol');
    private $breadcrumb;
    private $resumeLength;
    private $isUserBookmark = false;
    private $bookmarkCount = 0;

    protected $casts = [
        'isPublish' => 'Boolean',
        'isValid' => 'Boolean',
        'isUrgent' => 'Boolean',
        'isRenew' => 'Boolean',
        'is_delegation' => 'Boolean',
        'isNegociated' => 'Boolean',
    ];

    //Relations
    public function user() { return $this->belongsTo('App\User'); }
    public function bookmarks() { return $this->hasMany('App\Bookmark'); }
    public function category() { return $this->belongsTo('App\Category'); }
    public function pictures() { return $this->hasMany('App\Picture'); }
    public function picturesWithTrashed() { return $this->hasMany('App\Picture')->withTrashed(); }
    public function invoice() { return $this->belongsTo('App\Invoice'); }

    //Attributs Getters
    public function getCurrencySymbolAttribute() {
        return Currencies::getSymbolByCurrencyCode($this->currency, config('runtime.locale'));
    }

    public function getOriginalPriceAttribute() {
        return $this->getOriginal('price');
    }

    public function getPriceSubUnitAttribute() {
        return Currencies::getSubUnit($this->currency);
    }

    public function getPriceAttribute($value) {
        return MoneyUtils::getPriceWithDecimal($value, $this->currency);
    }

    public function getPriceMarginAttribute($value) {
        return MoneyUtils::getPriceWithDecimal($value, $this->currency);
    }

    public function getPriceCoefficientAttribute($value) {
        return $value/100;
    }

    public function getBreadCrumbAttribute() {
        return $this->breadcrumb;
    }

    public function getUrlAttribute() {
        return route('advert.show', ['slug' => $this->slug]);
    }

    public function getRenewUrlAttribute() {
        return route('advert.renew', ['id' => $this->id]);
    }

    public function getDestroyUrlAttribute() {
        return route('advert.destroy', ['id' => $this->id]);
    }

    public function getIsEligibleForRenewAttribute() {
        $online = Carbon::parse($this->online_at);
        $isPast = Carbon::create($online->year, $online->month, $online->day, 0,0,0)->addDay(env('ADVERT_LIFE_TIME'))->subDays(env('ALERT_BEFORE_END_1'))->isPast(Carbon::now());
        return (!$this->is_delegation && !$this->isRenew && ($this->deleted_at || $isPast));
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

    public function getTitleWithManuRefAttribute() {
        if (!is_null($this->manu_ref)) {
            return ucfirst($this->title) . PHP_EOL . '[ref: ' . $this->manu_ref . ']';
        }
        return ucfirst($this->title);
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

    public function getIsEligibleForRenewMailZeroAttribute() {
        if ($this->isValid && !$this->isRenew && !$this->is_delegation && $this->lastObsoleteMail!==0){
            return true;
        }
        return false;
    }

    //Setter Attribute
    public function setPriceCoefficientAttribute($value) {
        //Use this mutator to ensure Margin value
        //Example Value: 5.08 => 508 in BDD
        $this->attributes['price_coefficient'] =  (int)round(($value*100),0);
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

    public function getInvoiceFilePath() {
        $invoiceFilePath = null;
        if($this->invoice && $this->invoice->invoice_number){
            $invoice = $this->invoice;
            $year = Carbon::parse($invoice->created_at)->year;
            $month = Carbon::parse($invoice->created_at)->month;
            $invoiceFilePath = storage_path('app/invoices/' . $year . '/' . $month . '/' .$invoice->id.'.pdf');
        }
        return $invoiceFilePath;
    }

    public function getInvoiceStoragePath() {
        $invoiceStoragePath = null;
        if($this->invoice && $this->invoice->invoice_number){
            $invoice = $this->invoice;
            $year = Carbon::parse($invoice->created_at)->year;
            $month = Carbon::parse($invoice->created_at)->month;
            $invoiceStoragePath = 'invoices/' . $year . '/' . $month ;
        }
        return $invoiceStoragePath;
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
                ->where('isRenew', false)
                ->where('is_delegation', false)
                ->where(function ($query) use ($days){
                    $query->whereNull('lastObsoleteMail')
                        ->orWhere('lastObsoleteMail', '>', $days);
                });
        } else {
            return $query->onlyTrashed()
                ->where('isValid', true)
                ->where('isRenew', false)
                ->where('is_delegation', false)
                ->where('lastObsoleteMail', '<>', 0);
        }
    }

    public function scopeWithVideoId($query, $videoId) {
        return $query->withTrashed()
            ->where('video_id', '=', $videoId);
    }

    public function scopeMines($query) {
        return $query->withTrashed()
            ->where('user_id', '=', auth()->id())
            ->where('isPublish', true)
            ->where('isValid', '<>', false)
            ->where(function ($query_in) {
                $query_in->where('online_at', '<', Carbon::now())
                    ->orWhere(function ($query_in2) {
                        $query_in2->where('isValid', null)
                            ->where('deleted_at', null);
                    });
            })
            ->where(function ($queryIn){
                $queryIn->where('isRenew', false)
                    ->orWhere('deleted_at', null);
            })
            ->orderBy('online_at', 'desc');
    }

    public function scopeDelegations($query) {
        if(auth()->check() && auth()->user()->role == 'admin'){
            return $query->where('is_delegation', true)
                ->where(function ($queryIn){
                    $queryIn->where('adverts.isRenew', false)
                        ->orWhere('adverts.deleted_at', null);
                })
                ->orderBy('adverts.online_at', 'desc');
        }
        return $query;
    }

    public function scopeOnlyPublish($query){
        return $query->where('isPublish', true)->where('isValid', null)->orderBy('updated_at', 'asc')->take(10);
    }

    public function scopeValidOnline($query){
        return $query->where('isValid', true)->where('online_at', '<', Carbon::now());
    }

    public function scopeInBookmarks($query, $bookmarks){
        return $query->whereIn('id', $bookmarks)->validOnline()->orderBy('online_at', 'desc');
    }
}
