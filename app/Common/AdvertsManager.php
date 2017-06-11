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

            $results = [
                'invalids' => ['adverts' => 0, 'pictures' => 0],
                'abandoned' => ['adverts' => 0, 'pictures' => 0],
                'obsoletes' => ['adverts' => 0, 'pictures' => 0],
                'obsoleteLocalTempo' => ['pictures' => 0],
                'persistent' => ['videos' => 0]
            ];

            //1 get Adverts where is Valid = false
            $invalidsResults = $this->purgeInvalidsAdverts();
            $results['invalids']['adverts'] = $invalidsResults[0];
            $results['invalids']['pictures'] = $invalidsResults[1];

            //2 get Adverts where is publish = false and created_at > 2 hours
            $abandonedResults = $this->purgeAbandonedAdverts();
            $results['abandoned']['adverts'] = $abandonedResults[0];
            $results['abandoned']['pictures'] = $abandonedResults[1];

            //3 get Adverts where deleted_at > env delay
            $obsoletesResults = $this->purgeObsoletesAdverts();
            $results['obsoletes']['adverts'] = $obsoletesResults[0];
            $results['obsoletes']['pictures'] = $obsoletesResults[1];

            //4 deleted Tempo files with life time pass
            $obsoleteLocalTempoResults = $this->pictureManager->purgeObsoleteLocalTempo(env('TEMPO_HOURS_LIFE_TIME'));
            $results['obsoleteLocalTempo']['pictures'] = $obsoleteLocalTempoResults;

            //5 Purge Videos On Vimeo WithOut Advert
            $persistentVideosResults = $this->purgePersistentVideos();
            $results['persistent']['videos'] = $persistentVideosResults;

            return $results;
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

    public function purgeInvalidsAdverts() {
        try {
            $counterDelPictures = 0;
            $counterDelAdvert = 0;
            $invalidAdverts = Advert::invalid()->get();
            foreach ($invalidAdverts as $advert){
                $counterDelAdvert++;
                $counterDelPictures += $this->definitiveDestroy($advert);
            }
            return [$counterDelAdvert, $counterDelPictures];
        } catch (\Exception $e) {
            throw new \Exception('purge invalid adverts fails');
        }
    }

    public function purgeAbandonedAdverts() {
        try {
            $counterDelPictures = 0;
            $counterDelAdvert = 0;
            $abandonedAdverts = Advert::abandonned()->get();
            foreach ($abandonedAdverts as $advert){
                $counterDelAdvert++;
                $counterDelPictures += $this->definitiveDestroy($advert);
            }
            return [$counterDelAdvert, $counterDelPictures];
        } catch (\Exception $e) {
            throw new \Exception('purge abandoned adverts fails');
        }
    }

    public function purgePersistentVideos() {
        try {
            $counterDelVideo = 0;
            $persistents = Persistent::where('key', '=', 'videoId')->get();
            foreach ($persistents as $persistent) {
                if(Carbon::parse($persistent->updated_at)->addHours(env('TEMPO_HOURS_LIFE_TIME'))->isPast()){
                    $response = $this->vimeoManager->request('/videos/'.$persistent->value,[],'DELETE');
                    if($response['status']<300){
                        $counterDelVideo++;
                    }
                    $persistent->delete();
                }
            }
            return $counterDelVideo;
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
                if(!$advert->is_delegation) {
                    $counter++;
                    $advert->isEligibleForRenewMailZero ? $this->alertEndOfAdverts(0,[$advert]) : null;
                    $advert->delete();
                } else {
                    //automatic renew for delegations
                    $advert->online_at = Carbon::now();
                    $advert->setEndedAt();
                    $advert->save();
                }
            }
            return $counter;
        } catch (\Exception $e) {
            throw new \Exception('stop end life time advert fails');
        }
    }

    public function alertEndOfAdverts($days, $advertZero = null) {
        try {
            //alert advert with online_at > lifeTime-$days
            $counter = 0;
            $alertEndOfAdverts = null;
            $alertEndOfAdverts = $advertZero ? $advertZero : Advert::eligibleForMailRenew($days)->get();

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