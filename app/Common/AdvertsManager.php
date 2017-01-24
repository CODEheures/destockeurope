<?php

namespace App\Common;

use App\Advert;
use App\Notifications\AlertObsoleteAdvert;
use App\Persistent;
use Carbon\Carbon;
use Vinkla\Vimeo\VimeoManager;

class AdvertsManager
{
    private $pictureManager;
    private $vimeoManager;

    public function __construct(PicturesManager $picturesManager, VimeoManager $vimeoManager) {
        $this->pictureManager = $picturesManager;
        $this->vimeoManager = $vimeoManager;
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
        $videoId = $advert->video_id;
        if($videoId && strlen($videoId)>0){
            $count = Advert::withVideoId($videoId)->count();
            if($count<=1){
                $response = $this->vimeoManager->request('/videos/'.$videoId,[],'DELETE');
            }
        }
        $advert->forceDelete();
        return $counter;
    }


    //Manually Purge in Admin Panel
    public function purge() {
        try {
            $counterDelPictures = 0;
            $counterDelAdvert = 0;
            $counterDelVideo = 0;
            //1 get Adverts where is Valid = false
            $invalidAdverts = Advert::invalid()->get();
            foreach ($invalidAdverts as $advert){
                $counterDelAdvert++;
                $counterDelPictures += $this->definitiveDestroy($advert);
            }

            //2 get Adverts where is publish = false and created_at > 2 hours
            $abandonedAdverts = Advert::abandonned()->get();
            foreach ($abandonedAdverts as $advert){
                $counterDelAdvert++;
                $counterDelPictures += $this->definitiveDestroy($advert);
            }

            //3 get abandonned renew Adverts
            $abandonedRenewAdverts = Advert::abandonnedRenew()->get();
            foreach ($abandonedRenewAdverts as $advert){
                $counterDelAdvert++;
                $counterDelPictures += $this->definitiveDestroy($advert);
            }

            //4 get Adverts where deleted_at > env delay
            $obsoletesResults = $this->purgeObsoletesAdverts();
            $counterDelAdvert += $obsoletesResults[0];
            $counterDelPictures += $obsoletesResults[1];

            //5 deleted Tempo files with life time pass
            $counterDelPictures += $this->pictureManager->purgeObsoleteLocalTempo(env('TEMPO_HOURS_LIFE_TIME'));

            //6 Purge Videos On Vimeo WithOut Advert
            $persistents = Persistent::where('key', '=', 'videoId')->get();
            foreach ($persistents as $persistent) {
                if(Carbon::parse($persistent->updated_at)->isPast(Carbon::now()->subHours(env('TEMPO_HOURS_LIFE_TIME')))){
                    $response = $this->vimeoManager->request('/videos/'.$persistent->value,[],'DELETE');
                    if($response['status']<300){
                        $counterDelVideo++;
                    }
                    $persistent->delete();
                }
            }

            return trans('strings.admin_purge_response', ['nbadvert' => $counterDelAdvert, 'nbimg' => $counterDelPictures, 'nbvideos' => $counterDelVideo]);
        } catch (\Exception $e) {
            throw  new \Exception(trans('strings.admin_purge_error') . ': ' . $e->getMessage());
        }

    }

    public function purgeObsoletesAdverts() {
        try {
            $counterDelPictures = 0;
            $counterDelAdvert = 0;
            $obsoleteAdverts = Advert::obsoletes()->get();
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
            //stop advert with online_at > lifeTime
            $counter = 0;
            $invalidAdverts = Advert::finished()->get();
            foreach ($invalidAdverts as $advert){
                $counter++;
                $advert->delete();
            }
            return $counter;
        } catch (\Exception $e) {
            throw new \Exception('stop end life time advert fails');
        }
    }

    public function alertEndOfAdverts($days) {
        try {
            //alert advert with online_at > lifeTime-$days
            $counter = 0;
            $alertEndOfAdverts = null;
            $alertEndOfAdverts = Advert::EligibleForMailRenew($days)->get();

            foreach ($alertEndOfAdverts as $advert){
                $counter++;

                config(['runtime.locale' => $advert->user->locale]);
                $runTimeLocale = \Locale::getPrimaryLanguage(config('runtime.locale'));
                LocaleUtils::setAppLocale($runTimeLocale);

                $recipient = $advert->user;
                $recipient->notify(new AlertObsoleteAdvert($advert, $days));
                $advert->lastObsoleteMail = $days;
                $advert->timestamps = false;
                $advert->save();
            }
            return $counter;
        } catch (\Exception $e) {
            throw new \Exception('alert user for J-' . $days . ' obsolete advert fails');
        }
    }
}