<?php

namespace App\Common;


use App\Advert;
use App\User;
use Carbon\Carbon;

trait CostUtils
{
    public static $defaultOptions = [
        'nbPictures' => 0,
        'isUrgent' => false,
        'haveVideo' => false,
        'isRenew' => false,
        'isBackToTop' => false,
        'isHighlight' => false,
        'isEditOf' => null,
        'intermediary' => ['cost' => 0, 'description' => '']
    ];

    public static function countCostPictures($nbPictures, Advert $isEditOf=null) {
        $nbAlreadyPaid = 0;
        if(!is_null($isEditOf)){
            $nbOriginalPictures = $isEditOf->pictures()->count();
            if($nbOriginalPictures > config('runtime.nbFreePictures')){
                $nbAlreadyPaid = $nbOriginalPictures-config('runtime.nbFreePictures');
            }
        }
        return ($nbPictures - config('runtime.nbFreePictures') - $nbAlreadyPaid);
    }

    public static function isSwitchToUrgent($isUrgent, Advert $isEditOf=null){
        $isAlreadyUrgent = false;
        if(!is_null($isEditOf)){
            $isAlreadyUrgent = $isEditOf->isUrgent;
        }
        if($isUrgent && !$isAlreadyUrgent){
            return true;
        }
        return false;
    }

    public static function hasAddVideo($haveVideo, Advert $isEditOf=null){
        $haveAlreadyVideo = false;
        if(!is_null($isEditOf)){
            $haveAlreadyVideo = !is_null($isEditOf->video_id);
        }
        if($haveVideo && !$haveAlreadyVideo){
            return true;
        }
        return false;
    }

    public static function getCostPictures($nbPictures, Advert $isEditOf=null){
        $countCostPictures = self::countCostPictures($nbPictures, $isEditOf);
        if($countCostPictures>0){
            return $countCostPictures*config('runtime.photoCost')*100;
        }
        return 0;
    }

    public static function getCostIsUrgent($isUrgent, Advert $isEditOf=null){
        if(self::isSwitchToUrgent($isUrgent, $isEditOf)){
            return config('runtime.urgentCost')*100;
        }
        return 0;
    }

    public static function getCostVideo($haveVideo, Advert $isEditOf=null){
        if(self::hasAddVideo($haveVideo, $isEditOf)){
            return config('runtime.videoCost')*100;
        }
        return 0;
    }

    public static function getCostIsEdit($isEdit){
        if($isEdit){
            return config('runtime.editCost')*100;
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
        if(!PrivilegesUtils::isCostFree()) {
            $cost += self::getCostPictures($finalOptions['nbPictures'], $finalOptions['isEditOf']);
            $cost += self::getCostIsUrgent($finalOptions['isUrgent'], $finalOptions['isEditOf']);
            $cost += self::getCostVideo($finalOptions['haveVideo'], $finalOptions['isEditOf']);
            $cost += self::getCostIsRenew($finalOptions['isRenew']);
            $cost += self::getCostIsBackToTop($finalOptions['isBackToTop']);
            $cost += self::getCostIsHighlight($finalOptions['isHighlight']);
            $cost += self::getCostIsEdit(!is_null($finalOptions['isEditOf']));
        }

        // ByPass COST Process for Testing
        if(PrivilegesUtils::isCostTest()) {
            $cost = 10;
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
        if(!is_null($finalOptions['isEditOf'])){
            $options['isEdit'] = [
                'name' => trans('strings.option_isEdit_name'),
                'quantity' => 1,
                'cost' => self::getCostIsEdit(!is_null($finalOptions['isEditOf'])),
                'tva' => env('TVA')
            ];
        }
        if(self::countCostPictures($finalOptions['nbPictures'], $finalOptions['isEditOf'])>0){
            $options['payedPictures'] = [
                'name' => trans('strings.option_payedPicture_name'),
                'quantity' => self::countCostPictures($finalOptions['nbPictures'], $finalOptions['isEditOf']),
                'cost' => self::getCostPictures($finalOptions['nbPictures'], $finalOptions['isEditOf']),
                'tva' => env('TVA')
            ];
        }
        if(self::isSwitchToUrgent($finalOptions['isUrgent'], $finalOptions['isEditOf'])){
            $options['isUrgent'] = [
                'name' => trans('strings.option_isUrgent_name'),
                'quantity' => 1,
                'cost' => self::getCostIsUrgent($finalOptions['isUrgent'], $finalOptions['isEditOf']),
                'tva' => env('TVA')
            ];
        }
        if(self::hasAddVideo($finalOptions['haveVideo'], $finalOptions['isEditOf'])){
            $options['haveVideo'] = [
                'name' => trans('strings.option_haveVideo_name'),
                'quantity' => 1,
                'cost' => self::getCostVideo($finalOptions['haveVideo'], $finalOptions['isEditOf']),
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
        if($finalOptions['intermediary']['cost'] > 0 && strlen($finalOptions['intermediary']['description']) > 0){
            $options['intermediary'] = [
                'name' => $finalOptions['intermediary']['description'],
                'quantity' => 1,
                'cost' => $finalOptions['intermediary']['cost'],
                'tva' => env('TVA')
            ];
        }

        foreach ($options as $key => $option){
            $options[$key]['tvaVal'] = (int)($options[$key]['cost']*$options[$key]['tva']/100);
            $options[$key]['costTTC'] = $options[$key]['cost']+$options[$key]['tvaVal'];
        }

        // ByPass COST Process for Testing
        if(PrivilegesUtils::isCostTest()) {
            $options = [];
            $options['test'] = [
                'name' => 'Payment test',
                'quantity' => 1,
                'cost' => 10,
                'tva' => env('TVA'),
            ];
            $options['test']['tvaVal'] = (int)($options['test']['cost']*$options['test']['tva']/100);
            $options['test']['costTTC'] = $options['test']['cost']+$options['test']['tvaVal'];
        }

        return $options;
    }
}