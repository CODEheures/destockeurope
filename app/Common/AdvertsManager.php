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

            //2 get Adverts where is publish = false and created_at > 2 hours
            $abandonedAdverts = Advert::where('isPublish', '=', false)->where('created_at', '<', Carbon::now()->subHours(2))->get();
            foreach ($abandonedAdverts as $advert){
                $counterDelAdvert++;
                $counterDelPictures += $this->definitiveDestroy($advert);
            }

            //3 get Adverts where deleted_at > env delay
            $obsoletesResults = $this->purgeObsoletesAdverts();
            $counterDelAdvert += $obsoletesResults[0];
            $counterDelPictures += $obsoletesResults[1];

            return trans('strings.admin_purge_response', ['nbadvert' => $counterDelAdvert, 'nbimg' => $counterDelPictures]);
        } catch (\Exception $e) {
            throw  new \Exception(trans('strings.admin_purge_error') . ': ' . $e->getMessage());
        }

    }

    public function purgeObsoletesAdverts() {
        try {
            $counterDelPictures = 0;
            $counterDelAdvert = 0;
            $obsoleteAdverts = Advert::onlyTrashed()->where('deleted_at', '<', Carbon::now()->subDay(env('DELAY_DAYS_FOR_RENEW_ADVERT')))->get();
            foreach ($obsoleteAdverts as $advert){
                $counterDelAdvert++;
                $counterDelPictures += $this->definitiveDestroy($advert);
            }
            return [$counterDelAdvert, $counterDelPictures];
        } catch (\Exception $e) {
            throw new \Exception('purge obsoletes adverts fails');
        }
    }

    public function stopAdverts() {
        try {
            //stop advert with updated_at > lifeTime
            $counter = 0;
            $invalidAdverts = Advert::where('updated_at', '<', Carbon::now()->subDay(env('ADVERT_LIFE_TIME')))->get();
            foreach ($invalidAdverts as $advert){
                $counter++;
                $advert->delete();
            }
            return $counter;
        } catch (\Exception $e) {
            throw new \Exception('stop end life time advert fails');
        }
    }
}