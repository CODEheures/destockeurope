<?php

namespace App\Common;

use App\Advert;
use App\Picture;
use Carbon\Carbon;

class AdvertsManager
{
    private $pictureManager;

    public function __construct(PicturesManager $picturesManager) {
        $this->pictureManager = $picturesManager;
    }

    private function definitiveDestroy(Advert $advert) {
        $counter = 0;
        $advert->load('picturesWithTrashed');
        //is last owner of pictures?
        foreach ($advert->picturesWithTrashed as $picture){
            $countParentAdvert = $this->pictureManager->countParent($picture);
            if($countParentAdvert == 1) {
                //definitive destroy picture
                $this->pictureManager->destroy($picture);
                $counter++;
            }
        }
        $advert->forceDelete();
        return $counter;
    }

    public function purge() {
        try {
            $counterDelPictures = 0;
            $counterDelAdvert = 0;
            //1 get Adverts where is Valid = false
            $invalidAdverts = Advert::where('isValid', '=', false)->get();
            foreach ($invalidAdverts as $advert){
                $counterDelAdvert++;
                $counterDelPictures += $this->definitiveDestroy($advert);
            }

            //2 get Adverts where deleted_at > env delay
            $obsoleteAdverts = Advert::onlyTrashed()->where('deleted_at', '<', Carbon::now()->subDay(env('DELAY_DAYS_FOR_RENEW_ADVERT')))->get();
            foreach ($obsoleteAdverts as $advert){
                $counterDelAdvert++;
                $counterDelPictures += $this->definitiveDestroy($advert);
            }
            return trans('strings.admin_purge_response', ['nbadvert' => $counterDelAdvert, 'nbimg' => $counterDelPictures]);
        } catch (\Exception $e) {
            throw  new \Exception(trans('strings.admin_purge_error'));
        }

    }
}