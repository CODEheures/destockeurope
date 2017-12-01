<?php

namespace App;

use App\Common\MoneyUtils;
use App\Common\PrivilegesUtils;
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
        'ended_at',
        'highlight_until',
        'user_id',
        'category_id',
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
        'is_delegation',
        'video_id',
        'isNegociated',
        'manu_ref',
        'nextUrl',
        'discount_on_total'
    ];
    protected $dates = ['deleted_at', 'online_at', 'ended_at', 'highlight_until'];
    protected $cascadeDeletes = ['pictures', 'bookmarks'];
    protected $appends = array('breadCrumb', 'isOnEdit', 'url', 'renewUrl', 'backToTopUrl', 'highlightUrl',
        'destroyUrl', 'updateCoefficientUrl', 'updateQuantitiesUrl', 'editUrl', 'delegationUrl', 'resume',
        'titleWithManuRef', 'isEligibleForRenew', 'isEligibleForHighlight',
        'isEligibleForRenewMailZero', 'isEligibleForEdit', 'isUserOwner', 'isUserBookmark', 'bookmarkCount',
        'originalPrice', 'originalPriceWithMargin', 'totalPrice', 'totalPriceMargin', 'globalDiscount', 'priceSubUnit', 'currencySymbol');
    private $breadcrumb;
    private $resumeLength;
    private $isUserBookmark = false;
    private $bookmarkCount = 0;
    private $isOnEdit = false;
    private $totalPriceMargin = 0;
    private $globalDiscount = 0;
    private $testEditFields = [
        'title',
        'manu_ref',
        'description',
        'price',
        'currency',
        'totalQuantity',
        'lotMiniQuantity',
        'geoloc',
        'video_id',
        'discount_on_total'
    ];

    protected $casts = [
        'isPublish' => 'Boolean',
        'isValid' => 'Boolean',
        'isUrgent' => 'Boolean',
        'is_delegation' => 'Boolean',
        'isNegociated' => 'Boolean',
    ];

    //Relations
    public function user() { return $this->belongsTo('App\User'); }
    public function bookmarks() { return $this->hasMany('App\Bookmark'); }
    public function category() { return $this->belongsTo('App\Category'); }
    public function pictures() {
        //ATTENTION RETURN WITH TRASHED BECAUSE of multiple load with advert+deleted advert
        //PICTURES IS ONLY TRASHED IF ADVERT IS TRASHED
        return $this->hasMany('App\Picture')->withTrashed();
    }
    public function invoices() { return $this->hasMany('App\Invoice'); }

    //Attributs Getters
    public function getCurrencySymbolAttribute() {
        return Currencies::getSymbolByCurrencyCode($this->currency, config('runtime.locale'));
    }

    public function getOriginalPriceAttribute() {
        return $this->getOriginal('price');
    }

    public function getOriginalPriceWithMarginAttribute() {
        return $this->getOriginal('price_margin');
    }

    public function getPriceSubUnitAttribute() {
        return Currencies::getSubUnit($this->currency);
    }

    public function getPriceAttribute($value) {
        return MoneyUtils::getPriceWithDecimal($value, $this->currency);
    }

    public function getTotalPriceAttribute() {
        $discountPrice = (int)($this->originalPrice*$this->totalQuantity*(1-$this->discount_on_total/100));
        return MoneyUtils::getPriceWithDecimal($discountPrice, $this->currency);
    }

    public function getPriceMarginAttribute($value) {
        return MoneyUtils::getPriceWithDecimal($value, $this->currency);
    }

    public function getPriceCoefficientAttribute($value) {
        return $value/100;
    }

    public function getDiscountOnTotalAttribute($value) {
        return $value/100;
    }

    public function getPriceCoefficientTotalAttribute($value) {
        return $value/100;
    }

    public function getTotalPriceMarginAttribute() {
        return $this->totalPriceMargin;
    }

    public function getGlobalDiscountAttribute() {
        return $this->globalDiscount;
    }

    public function getBreadCrumbAttribute() {
        return $this->breadcrumb;
    }

    public function getUrlAttribute() {
        return route('advert.show', ['slug' => $this->slug, 'lang' => App::getLocale()]);
    }

    public function getRenewUrlAttribute() {
        if (PrivilegesUtils::canRenewAdvert($this)) {
            return route('advert.renew', ['id' => $this->id]);
        } else {
            return null;
        }
    }

    public function getBackToTopUrlAttribute() {
        if (PrivilegesUtils::canBackToTopAdvert($this)) {
            return route('advert.backToTop', ['id' => $this->id]);
        } else {
            return null;
        }
    }

    public function getHighlightUrlAttribute() {
        if (PrivilegesUtils::canHighlightAdvert($this)) {
            return route('advert.highlight', ['id' => $this->id]);
        } else {
            return null;
        }
    }

    public function getDestroyUrlAttribute() {
        if (PrivilegesUtils::canDestroyAdvert($this)) {
            return route('advert.destroy', ['id' => $this->id]);
        } else {
            return null;
        }
    }

    public function getUpdateCoefficientUrlAttribute() {
        if (PrivilegesUtils::canUpdateCoefficientAdvert($this)) {
            return route('advert.updateCoefficient', ['id' => $this->id]);
        } else {
            return null;
        }
    }

    public function getUpdateQuantitiesUrlAttribute() {
        if (PrivilegesUtils::canUpdateQuantitiesAdvert($this)) {
            return route('advert.updateQuantities', ['id' => $this->id]);
        } else {
            return null;
        }
    }

    public function getEditUrlAttribute() {
        if (PrivilegesUtils::canEditAdvert($this)) {
            return route('advert.edit', ['id' => $this->id]);
        } else {
            return null;
        }
    }

    public function getDelegationUrlAttribute() {
        if ($this->is_delegation && PrivilegesUtils::canGetDelegations()) {
            return route('advert.delegation', ['id' => $this->id]);
        } else {
            return null;
        }
    }

    public function getIsEligibleForRenewAttribute() {
        $ended = Carbon::parse($this->ended_at);
        $isPast = Carbon::create($ended->year, $ended->month, $ended->day, 0,0,0)->subDays(env('ALERT_BEFORE_END_1'))->isPast();
        return (!$this->is_delegation && $this->isValid && ($this->deleted_at || $isPast));
    }

    public function getIsEligibleForHighlightAttribute() {
        $ended = Carbon::parse($this->ended_at);
        $isQuiteYoung = $ended->subHours(env('HIGHLIGHT_HOURS_DURATION'))->isFuture();
        $isNotHighlight = is_null($this->highlight_until) || Carbon::parse($this->highlight_until)->isPast();
        return ($this->isValid && $isNotHighlight && ($isQuiteYoung || $this->is_delegation));
    }

    public function getIsEligibleForEditAttribute() {
        $ended = Carbon::parse($this->ended_at);
        $isQuiteYoung = $ended->subHours(env('REMAINING_HOURS_FOR_EDIT_ELIGIBILITY'))->isFuture();
        return ($this->isValid && !$this->isOnEdit && ($isQuiteYoung || $this->is_delegation));
    }

    public function getResumeAttribute() {
        if(!$this->resumeLength || !is_numeric($this->resumeLength) || $this->resumeLength == 0){
            $this->resumeLength = config('runtime.advertResumeLenght');
        }
        return ucfirst(mb_substr($this->description, 0,$this->resumeLength)).'...';
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

    public function getIsEligibleForRenewMailZeroAttribute() {
        if ($this->isValid && !$this->is_delegation && $this->lastObsoleteMail!==0){
            return true;
        }
        return false;
    }

    public function getIsOnEditAttribute() {
        return $this->isOnEdit;
    }

    //Setter Attribute
    public function setPriceCoefficientAttribute($value) {
        //Use this mutator to ensure Margin value
        //Example Value: 5.08 => 508 in BDD
        $this->attributes['price_coefficient'] =  (int)round(($value*100),0);
    }

    public function setPriceCoefficientTotalAttribute($value) {
        //Use this mutator to ensure Margin value
        //Example Value: 5.08 => 508 in BDD
        $this->attributes['price_coefficient_total'] =  (int)round(($value*100),0);
    }

    public function setDiscountOnTotalAttribute($value) {
        //Use this mutator to ensure Margin value
        //Example Value: 5.08 => 508 in BDD
        $this->attributes['discount_on_total'] =  (int)round(($value*100),0);
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

    public function isEligibleForBackToTop() {
        $firstAdvert = Advert::orderBy('online_at', 'DESC')->first();
        if($firstAdvert->id != $this->id && is_null($this->deleted_at) &&  (Carbon::parse($this->ended_at)->subDay(1)->isFuture() || $this->is_delegation) ){
            return true;
        }
        return false;
    }

    public function setBreadCrumb($ancestors) {
        $this->breadcrumb = $ancestors;
    }

    public function setIsUserBookmark($result) {
        $this->isUserBookmark = $result;
    }

    public function setBookmarkCount() {
        $this->bookmarkCount = $this->bookmarks->count();
    }

    public function setEndedAt() {
        $this->ended_at = Carbon::parse($this->online_at)->addDays(env('ADVERT_LIFE_TIME'));
    }

    public function calcRenewEndedAt() {
        if(!is_null($this->deleted_at)) {
            return Carbon::now()->addDays(env('ADVERT_LIFE_TIME'));
        } else {
            return Carbon::parse($this->ended_at)->addDays(env('ADVERT_LIFE_TIME'));
        }
    }

    public function setIsOnEdit() {
        $this->isOnEdit = Advert::where('isEditOf', $this->id)->where('isPublish', true)->count()>0;
    }

    public function setTotalPriceMargin() {
        if(!$this->isNegociated){
            $this->totalPriceMargin = MoneyUtils::getPriceWithDecimal($this->calcTotalFinalPrice(), $this->currency);
        }
    }

    public function setGlobalDiscount() {
        if(!$this->isNegociated && $this->OriginalPriceWithMargin>0){
            $newCoef = (100 - ($this->calcTotalFinalPrice()*100/($this->originalPriceWithMargin*$this->totalQuantity)));
            $this->globalDiscount = number_format($newCoef,2);
        }
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

    public function scopeObsoletes($query) {
        return $query->onlyTrashed()->where('deleted_at', '<', Carbon::now()->subDay(env('DELAY_DAYS_FOR_RENEW_ADVERT')));
    }

    public function scopeFinished($query) {
        return $query->where('ended_at', '<', Carbon::now());
    }

    public function scopeEligibleForMailRenew($query, $days) {
        if($days > 0) {
            return $query->whereDate('ended_at' , '=', Carbon::now()->addDay($days)->toDateString())
                ->where('isValid', true)
                ->where('is_delegation', false)
                ->where(function ($query) use ($days){
                    $query->whereNull('lastObsoleteMail')
                        ->orWhere('lastObsoleteMail', '>', $days);
                });
        } else {
            return $query->onlyTrashed()
                ->where('isValid', true)
                ->where('is_delegation', false)
                ->where('lastObsoleteMail', '<>', 0);
        }
    }

    public function scopeWithVideoId($query, $videoId) {
        return $query->withTrashed()
            ->where('video_id', '=', $videoId);
    }

    public function scopeMines($query) {
        if(PrivilegesUtils::canRenew()){
            $returnQuery = $query->withTrashed();
        } else {
            $returnQuery = $query;
        }
        return $returnQuery
            ->where('user_id', '=', auth()->id())
            ->where('isPublish', true)
            ->whereNull('isEditOF')
            ->where(function ($query_in) {
                $query_in->where('isValid', null)
                    ->orWhere('isValid', true);
            })
            ->where(function ($query_in) {
                $query_in->where('online_at', '<', Carbon::now())
                    ->orWhere(function ($query_in2) {
                        $query_in2->where('isValid', null)
                            ->where('deleted_at', null);
                    });
            })
            ->orderBy('online_at', 'desc');
    }

    public function scopeDelegations($query) {
        if(PrivilegesUtils::canGetDelegations()){
            return $query->where('is_delegation', true)
                ->where('isValid', '<>', false)
                ->where('adverts.deleted_at', null)
                ->orderBy('adverts.online_at', 'desc');
        }
        return $query;
    }

    public function scopeOnlyPublish($query){
        return $query->where('isPublish', true)->where('isValid', null)->orderBy('updated_at', 'asc')->take(200);
    }

    public function scopeValidOnline($query){
        return $query->where('isValid', true)->where('online_at', '<', Carbon::now());
    }

    public function scopeInBookmarks($query, $bookmarks){
        return $query->whereIn('id', $bookmarks)->validOnline()->orderBy('online_at', 'desc');
    }

    public function scopeHighlight($query){
        return $query->validOnline()->where('highlight_until' ,'>=', Carbon::now())->inRandomOrder()->take(env('HIGHLIGHT_QUANTITY'));
    }

    //privates parts
    private function calcTotalFinalPrice() {
        $priceCoefficientTotal=$this->price_coefficient_total;

        $totalDiscountPrice = (int)($this->originalPrice*$this->totalQuantity*(100-$this->discount_on_total)/100);

        $marginTotal = (int)($totalDiscountPrice*$priceCoefficientTotal/100);
        $finalTotalPrice = $totalDiscountPrice + $marginTotal;

        return $finalTotalPrice;
    }
}
