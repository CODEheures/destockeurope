<?php

namespace App\Common;


trait CostUtils
{
    public static function getCostPictures($nbPictures){
        if($nbPictures > config('runtime.nbFreePictures')){
            return ($nbPictures - config('runtime.nbFreePictures'))*10*100;
        }
        return 0;
    }

    public static function getCostIsUrgent($isUrgent){
        if($isUrgent){
            return config('runtime.urgentCost')*100;
        }
        return 0;
    }

    public static function getCostVideo($haveVideo){
        if($haveVideo){
            return config('runtime.videoCost')*100;
        }
        return 0;
    }

    public static function getCostIsRenew($isRenew){
        if($isRenew){
            return config('runtime.renewCost')*100;
        }
        return 0;
    }

    public static function getCostIsBackToTop($isBackToTop){
        if($isBackToTop){
            return config('runtime.backToTopCost')*100;
        }
        return 0;
    }

    public static function getCost($nbPictures, $isUrgent=false, $haveVideo=false, $isRenew=false, $isBackToTop=false){
        $cost = 0;
        if(!auth()->user()->isDelegation) {
            $cost += self::getCostPictures($nbPictures);
            $cost += self::getCostIsUrgent($isUrgent);
            $cost += self::getCostVideo($haveVideo);
            $cost += self::getCostIsRenew($isRenew);
            $cost += self::getCostIsBackToTop($isBackToTop);
        }
        return $cost;
    }
}