<?php

namespace App\Common;


use App\Advert;
use Carbon\Carbon;

trait CostUtils
{
    public static $defaultOptions = [
        'nbPictures' => 0,
        'isUrgent' => false,
        'haveVideo' => false,
        'isRenew' => false,
        'isBackToTop' => false,
        'isHighlight' => false
    ];

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

    public static function getCostIsHighlight($isHightlight, $testFor=null){
        if($isHightlight){
            if(is_null($testFor)){
                $countHightlights = 1 + (int)(Advert::where('highlight_until', '>', Carbon::now())->count());
            } else {
                $countHightlights = $testFor+1;
            }
            return (int)(config('runtime.highlightCost')/(sqrt($countHightlights))*100);
        }
        return 0;
    }

    public static function getCost(Array $options){

        $finalOptions = array_merge(self::$defaultOptions, $options);

        $cost = 0;
        if(!auth()->user()->isSupplier) {
            $cost += self::getCostPictures($finalOptions['nbPictures']);
            $cost += self::getCostIsUrgent($finalOptions['isUrgent']);
            $cost += self::getCostVideo($finalOptions['haveVideo']);
            $cost += self::getCostIsRenew($finalOptions['isRenew']);
            $cost += self::getCostIsBackToTop($finalOptions['isBackToTop']);
            $cost += self::getCostIsHighlight($finalOptions['isHighlight']);
        }
        return $cost;
    }

    /**
     *
     * Set options for Invoices
     *
     * @param $nbPictures
     * @param $isUrgent
     * @param null $isRenew
     * @param null $haveVideo
     * @return array
     */
    public static function setOptions(Array $options) {

        $finalOptions = array_merge(self::$defaultOptions, $options);

        $options = [];
        if($finalOptions['nbPictures'] > config('runtime.nbFreePictures')){
            $options['payedPictures'] = [
                'name' => trans('strings.option_payedPicture_name'),
                'quantity' => $finalOptions['nbPictures'] - config('runtime.nbFreePictures'),
                'cost' => self::getCostPictures($finalOptions['nbPictures']),
                'tva' => env('TVA')
            ];
        }
        if($finalOptions['isUrgent']){
            $options['isUrgent'] = [
                'name' => trans('strings.option_isUrgent_name'),
                'quantity' => 1,
                'cost' => self::getCostIsUrgent($finalOptions['isUrgent']),
                'tva' => env('TVA')
            ];
        }
        if($finalOptions['haveVideo']){
            $options['haveVideo'] = [
                'name' => trans('strings.option_haveVideo_name'),
                'quantity' => 1,
                'cost' => self::getCostVideo($finalOptions['haveVideo']),
                'tva' => env('TVA')
            ];
        }
        if($finalOptions['isRenew']){
            $options['isRenew'] = [
                'name' => trans('strings.option_isRenew_name'),
                'quantity' => 1,
                'cost' => self::getCostIsRenew($finalOptions['isRenew']),
                'tva' => env('TVA')
            ];
        }
        if($finalOptions['isBackToTop']){
            $options['isBackToTop'] = [
                'name' => trans('strings.option_isBackToTop_name'),
                'quantity' => 1,
                'cost' => self::getCostIsBackToTop($finalOptions['isBackToTop']),
                'tva' => env('TVA')
            ];
        }
        if($finalOptions['isHighlight']){
            $options['isHighlight'] = [
                'name' => trans('strings.option_isHighlight_name'),
                'quantity' => 1,
                'cost' => self::getCostIsHighlight($finalOptions['isHighlight']),
                'tva' => env('TVA')
            ];
        }

        foreach ($options as $key => $option){
            $options[$key]['tvaVal'] = (int)($options[$key]['cost']*$options[$key]['tva']/100);
            $options[$key]['costTTC'] = $options[$key]['cost']+$options[$key]['tvaVal'];
        }

        return $options;
    }
}