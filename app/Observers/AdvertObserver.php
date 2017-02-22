<?php

namespace App\Observers;

use App\Advert;
use App\Common\MoneyUtils;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class AdvertObserver
{
    /**
     * Listen to the Advert created event.
     *
     * @param  Advert  $advert
     * @return void
     */
    public function creating(Advert $advert)
    {
        if(auth()->check() && auth()->user()->role=='delegation'){
            $advert->is_delegation = true;
        }

        $slug = Str::slug($advert->title);
        $count = Advert::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
        $advert->slug = $count ? "{$slug}-{$count}" : $slug;
    }

    public function saving(Advert $advert) {
        //Have a price coefficient before saving
        if(!key_exists('price_coefficient',$advert->getAttributes())) {
            $advert->price_coefficient = 0;
        }

        //calc price_margin
        if((int)($advert->getAttributes()['price_coefficient']) > 0) {
            $price=(int)$advert->getAttributes()['price'];
            $coefficient=(int)$advert->getAttributes()['price_coefficient']/10000;
            $margin = (int)($price*$coefficient);
            $advert->price_margin =  $price+$margin;
        } else {
            $advert->price_margin =  (int)$advert->getAttributes()['price'];
        }

        //Atttibut price_margin_decimal
        $advert->price_margin_decimal = MoneyUtils::getPriceWithDecimal($advert->getAttributes()['price_margin'], $advert->currency,false);
    }

}