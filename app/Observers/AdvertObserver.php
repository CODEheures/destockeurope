<?php

namespace App\Observers;

use App\Advert;
use App\Common\MoneyUtils;
use App\User;
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
        if(auth()->check() && auth()->user()->role==User::ROLES[User::ROLE_DELEGATION]){
            $advert->is_delegation = true;
        }
        $advert->slug='';

        //Have a price coefficient before saving
        if(!key_exists('price',$advert->getAttributes())) {
            $advert->price = 0;
        }
    }

    public function created(Advert $advert) {
        //slug when have id
        $advert->slug = Str::slug($advert->title).'-'.$advert->id;
        $advert->save();
    }

    public function saving(Advert $advert) {
        //isNegociated?
        if($advert->isNegociated) {
            $advert->price = 0;
        }

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