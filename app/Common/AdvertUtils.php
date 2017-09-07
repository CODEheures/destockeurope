<?php

namespace App\Common;


use App\Advert;
use App\Bookmark;
use Carbon\Carbon;
use Codeheures\LaravelUtils\Traits\Tools\Currencies;
use Illuminate\Http\Request;

trait AdvertUtils
{

    public static function getRangePriceOnly(Request $request) {
        //only valid advert & online_at < now
        $adverts = Advert::where('isValid', true)->where('online_at', '<', Carbon::now());

        //where currency
        $currency = null;
        $currencySymbol = '';

        if($request->has('categoryId') && $request->categoryId != 0){
            $ids = CategoryUtils::getListSubTree($request->categoryId);
            if($ids){
                $adverts = $adverts->whereIn('category_id', $ids);
            }
        }

        //if location
        foreach (GeoManager::$accurate as $item){
            if($request->has($item) && $request->$item != null){
                $adverts = $adverts->where($item, '=', $request->$item);
            }
        }

        //Currencies
        $cloneAdverts = clone($adverts);
        $currenciesList = $cloneAdverts->select('currency')->groupBy('currency')->get();
        $nbCurrencies = $currenciesList->count();

        $finalCurrencyList = [];
        if($nbCurrencies==1){
            //$currency = $currenciesList->first()->currency;
            $currencySymbol = Currencies::getSymbolByCurrencyCode($currenciesList->first()->currency, config('runtime.locale'));
        } else {
            $currenciesListArray = $currenciesList->pluck('currency');
            foreach ($currenciesListArray as $currencyCode) {
                $finalCurrencyList[] = [
                    'code' => $currencyCode,
                    'symbol' =>   Currencies::getSymbolByCurrencyCode($currencyCode, config('runtime.locale'))
                ];
            }
        }

        if($request->has('currency') && Currencies::isAvailableCurrency($request->currency)) {
            $currency = $request->currency;
            $currencySymbol = Currencies::getSymbolByCurrencyCode($currency, config('runtime.locale'));
            $adverts = $adverts->where('currency', $currency);
        }

        $minAllPrice = $adverts->min('price_margin_decimal');
        $maxAllPrice = $adverts->max('price_margin_decimal');
        $minAllQuantity = $adverts->min('totalQuantity');
        $maxAllQuantity = $adverts->max('totalQuantity');

        if (!$minAllPrice) {
            $minAllPrice = 0;
        }

        if (!$maxAllPrice){
            $maxAllPrice = 0;
        }

        if(!$minAllQuantity){
            $minAllQuantity = 0;
        }

        if(!$maxAllQuantity){
            $maxAllQuantity = 0;
        }


        return [
            'minPrice'=> $minAllPrice,
            'maxPrice' => $maxAllPrice,
            'currenciesList' => $finalCurrencyList,
            'currency' => $currency,
            'currencySymbol' => $currencySymbol,
            'minQuantity'=> $minAllQuantity,
            'maxQuantity' => $maxAllQuantity,
        ];
    }

    public static function getList(Request $request) {
        //Init vars
        $isSearchRequest = ($request->has('search') && strlen($request->search) >= 3);
        $isSearchResults = ($request->has('resultsFor') && strlen($request->resultsFor) >= 3);
        $isUrgentOnly = ($request->has('isUrgent') && filter_var($request->isUrgent, FILTER_VALIDATE_BOOLEAN) == true );
        $isNegociatedOnly = ($request->has('isNegociated') && filter_var($request->isNegociated, FILTER_VALIDATE_BOOLEAN) == true );

        //only valid advert & online_at < now
        $adverts = Advert::where('isValid', true)->where('online_at', '<', Carbon::now());

        //where currency
        $currency = null;
        $currencySymbol = '';

        if($request->has('categoryId') && $request->categoryId != 0){
            $ids = CategoryUtils::getListSubTree($request->categoryId);
            if($ids){
                $adverts = $adverts->whereIn('category_id', $ids);
            }
        }

        //if location
        foreach (GeoManager::$accurate as $item){
            if($request->has($item) && $request->$item != null){
                $adverts = $adverts->where($item, '=', $request->$item);
            }
        }

        //Currencies
        if($request->has('currency') && Currencies::isAvailableCurrency($request->currency)) {
            $currency = $request->currency;
            $adverts = $adverts->where('currency', $currency);
        }

        //if urgent
        if($isUrgentOnly){
            $adverts = $adverts->where('isUrgent', true);
        }

        //if negociated only
        if($isNegociatedOnly){
            $adverts = $adverts->where('isNegociated', true);
        }

        //if range price
        if($request->has('minPrice') && $request->has('maxPrice') ){
            $adverts = $adverts->where('price_margin_decimal', '>=', $request->minPrice)->where('price_margin_decimal', '<=', $request->maxPrice);
        }

        //if range quantity
        if($request->has('minQuantity') && $request->has('maxQuantity') ){
            $minQuantity = ($request->minQuantity);
            $maxQuantity = ($request->maxQuantity);
            $adverts = $adverts->where('totalQuantity', '>=', $minQuantity)->where('totalQuantity', '<=', $maxQuantity);
        }

        if($isSearchRequest){
            $search = $request->search;
            $adverts = $adverts->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' .$search .'%')
                    ->orWhere('description', 'LIKE', '%' .$search .'%')
                    ->orWhere('manu_ref', 'LIKE', '%' .$search .'%');
            });
        }

        //search results before range price
        if($isSearchResults){
            $search = $request->resultsFor;
            $adverts = $adverts->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' .$search .'%')
                    ->orWhere('description', 'LIKE', '%' .$search .'%')
                    ->orWhere('manu_ref', 'LIKE', '%' .$search .'%');
            });
        }

        if($isSearchRequest) {
            $countSearch = $adverts->count();
            $adverts = $adverts->orderBy('online_at', 'desc')->limit(config('runtime.maxNumberOfSearchResults'))->get();
        } else {
            $adverts = $adverts->orderBy('online_at', 'desc')->paginate(config('runtime.advertsPerPage'));
        }
        $loadCompleteAdverts = self::loadCompleteAdverts($adverts);

        if($isSearchRequest){
            $action = [
                'url' => '',
                'text' => trans_choice('strings.form_input_search_view_all', $countSearch, ['nb' => $countSearch])
            ];
            //choisir $loadCompleteAdverts[1] pour trier les resultats par categories
            return ['results'=> $loadCompleteAdverts[0], 'action' => $action];
        } else {
            return ['adverts'=> $loadCompleteAdverts[0]];
        }
    }

    public static function getPersonnalList() {
        $adverts = Advert::mines()->paginate(config('runtime.advertsPerPage'));
        $loadCompleteAdverts = self::loadCompleteAdverts($adverts, true);
        return $loadCompleteAdverts[0];
    }

    public static function getDelegationList(Request $request) {
        //Init vars
        $isSearchRequest = ($request->has('search') && strlen($request->search) >= 3);
        $isSearchResults = ($request->has('resultsFor') && strlen($request->resultsFor) >= 3);

        $adverts = Advert::delegations();

        $search = null;
        if($isSearchRequest){
            $search = $request->search;
        } elseif ($isSearchResults){
            $search = $request->resultsFor;
        }

        //search results before range price
        if(!is_null($search)){
            $adverts = $adverts->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' .$search .'%')
                    ->orWhere('manu_ref', 'LIKE', '%' .$search .'%')
                    ->orWhere('description', 'LIKE', '%' .$search .'%');
            });
        }

        if($isSearchRequest) {
            $countSearch = $adverts->count();
            $adverts = $adverts->orderBy('created_at', 'desc')->limit(config('runtime.maxNumberOfSearchResults'))->get();
        } else {
            $adverts = $adverts->orderBy('created_at', 'desc')->paginate(config('runtime.advertsPerPage'));
        }

        $loadCompleteAdverts = self::loadCompleteAdverts($adverts);
        $adverts->load(['user' => function ($query) {
            $query->select(['id','email','phone', 'compagnyName']);
        }]);

        if($isSearchRequest){
            $action = [
                'url' => '',
                'text' => trans_choice('strings.form_input_search_view_all', $countSearch, ['nb' => $countSearch])
            ];
            //choisir $loadCompleteAdverts[1] pour trier les resultats par categories
            return ['results'=> $loadCompleteAdverts[0], 'action' => $action];
        } else {
            return ['adverts'=> $loadCompleteAdverts[0]];
        }
    }

    public static function getBookmarkList() {
        $bookmarks = Bookmark::mines()->get()->pluck('advert_id')->toArray();
        $adverts = Advert::inBookmarks($bookmarks)->paginate(config('runtime.advertsPerPage'));

        $adverts->load('pictures');
        $adverts->load('category');
        $tempoStore =[];
        $resultsByCat = [];
        $user = auth()->user();
        $user->load('bookmarks');

        foreach ($adverts as $advert){
            if(!array_key_exists($advert->category->id,$tempoStore)){
                $ancestors = $advert->category->getAncestors();
                $ancestors->add($advert->category);
                $tempoStore[$advert->category->id] = $ancestors;
                $resultsByCat[$advert->category->id]['results'] = [];
            } else {
                $ancestors = $tempoStore[$advert->category->id];
            }
            $advert->setBreadCrumb($ancestors);
            $resultsByCat[$advert->category->id]['results'][] = $advert;
            $resultsByCat[$advert->category->id]['name'] = $advert->getConstructBreadCrumb();
            $advert->setIsUserBookmark($user->haveBookmark($advert->id));
            $advert->setBookmarkCount();
        }

        return $adverts;
    }

    public static function loadCompleteAdverts($adverts, $withTestIsOnEdit=false) {
        $adverts->load('pictures');
        $adverts->load('category');
        $tempoStore =[];
        $resultsByCat = [];
        if(auth()->check()){
            $user = auth()->user();
            $user->load('bookmarks');
        }
        foreach ($adverts as $advert){
            if(!array_key_exists($advert->category->id,$tempoStore)){
                $ancestors = $advert->category->getAncestors();
                $ancestors->add($advert->category);
                $tempoStore[$advert->category->id] = $ancestors;
                $resultsByCat[$advert->category->id]['results'] = [];
            } else {
                $ancestors = $tempoStore[$advert->category->id];
            }
            $advert->setBreadCrumb($ancestors);
            $withTestIsOnEdit ? $advert->setIsOnEdit() : null;
            $resultsByCat[$advert->category->id]['results'][] = $advert;
            $resultsByCat[$advert->category->id]['name'] = $advert->getConstructBreadCrumb();
            if($advert->isUserOwner) {
                $advert->setBookmarkCount();
            } elseif (auth()->check()){
                $advert->setIsUserBookmark($user->haveBookmark($advert->id));
            } else {
                $advert->setIsUserBookmark(false);
            }
        }
        return [$adverts,$resultsByCat];
    }
}