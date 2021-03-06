<?php

namespace App\Common;

use App\Advert;
use App\Http\Controllers\PictureController;
use App\Notifications\AlertObsoleteAdvert;
use App\Notifications\AlertWaitingsAdverts;
use App\Persistent;
use App\Picture;
use App\User;
use Carbon\Carbon;
use Vimeo\Laravel\VimeoManager;

class AdvertsManager
{
    private $vimeoManager;

    public function __construct(VimeoManager $vimeoManager) {
        $this->vimeoManager = $vimeoManager;
    }

    private function definitiveDestroy(Advert $advert) {
        $advert->load('pictures');
        //is last owner of pictures?
        foreach ($advert->pictures as $picture){
            //using persistent process for deleting pictures
            Persistent::create([
                'key' => 'picture',
                'value' => $picture->thumbUrl
            ]);

            Persistent::create([
                'key' => 'picture',
                'value' => $picture->normalUrl
            ]);
        }
        $videoId = $advert->video_id;
        if($videoId && strlen($videoId)>0){
            $count = Advert::withVideoId($videoId)->count();
            if($count<=1){
                $response = $this->vimeoManager->request('/videos/'.$videoId,[],'DELETE');
            }
        }
        $advert->forceDelete();
        return true;
    }


    //Manually Purge in Admin Panel
    public function purge() {
        try {

            $results = [
                'invalids' => 0,
                'abandoned' => 0,
                'obsoletes' => 0,
                'persistent' => ['pictures' => 0, 'videos' => 0]
            ];

            //1 get Adverts where is Valid = false
            $results['invalids'] = $this->purgeInvalidsAdverts();

            //2 get Adverts where is publish = false and created_at > 2 hours
            $results['abandoned'] = $this->purgeAbandonedAdverts();

            //3 get Adverts where deleted_at > env delay
            $results['obsoletes'] = $this->purgeObsoletesAdverts();

            //4 Purge Persistents Pictures
            $persistentPicturesResults = $this->purgePersistentPictures();
            $results['persistent']['pictures'] = $persistentPicturesResults;

            //5 Purge Persistents Videos On Vimeo
            $persistentVideosResults = $this->purgePersistentVideos();
            $results['persistent']['videos'] = $persistentVideosResults;

            return $results;
        } catch (\Exception $e) {
            throw  new \Exception(trans('strings.admin_purge_error') . ': ' . $e->getMessage());
        }

    }

    public function purgeObsoletesAdverts() {
        try {
            $counterDelAdvert = 0;
            $obsoleteAdverts = Advert::obsoletes()->get();
            foreach ($obsoleteAdverts as $advert){
                $counterDelAdvert++;
                $this->definitiveDestroy($advert);
            }
            return $counterDelAdvert;
        } catch (\Exception $e) {
            throw new \Exception('purge obsoletes adverts fails');
        }
    }

    public function purgeInvalidsAdverts() {
        try {
            $counterDelAdvert = 0;
            $invalidAdverts = Advert::invalid()->get();
            foreach ($invalidAdverts as $advert){
                $counterDelAdvert++;
                $this->definitiveDestroy($advert);
            }
            return $counterDelAdvert;
        } catch (\Exception $e) {
            throw new \Exception('purge invalid adverts fails');
        }
    }

    public function purgeAbandonedAdverts() {
        try {
            $counterDelAdvert = 0;
            $abandonedAdverts = Advert::abandonned()->get();
            foreach ($abandonedAdverts as $advert){
                $counterDelAdvert++;
                $this->definitiveDestroy($advert);
            }
            return $counterDelAdvert;
        } catch (\Exception $e) {
            throw new \Exception('purge abandoned adverts fails');
        }
    }

    public function purgePersistentPictures() {
        try {
            $counterDelPictures = 0;
            $persistents = Persistent::where('key', '=', 'picture')->get();
            foreach ($persistents as $persistent) {
                if(Carbon::parse($persistent->updated_at)->addHours(env('TEMPO_HOURS_LIFE_TIME'))->isPast()
                    && Picture::withUrl($persistent->value)->count() == 0
                ) {
                    $deleteResponse = PictureController::deletePicture($persistent->value);

                    if($deleteResponse->getStatusCode() < 300){
                        $counterDelPictures++;
                    }
                    $persistent->delete();
                } elseif (Carbon::parse($persistent->updated_at)->addHours(env('TEMPO_HOURS_LIFE_TIME'))->isPast()
                    && Picture::withUrl($persistent->value)->count() > 0
                ){
                    $persistent->delete();
                }
            }
            return $counterDelPictures;
        } catch (\Exception $e) {
            throw new \Exception('purge obsoletes adverts fails');
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
                $user = $advert->user;
                if (
                    ($days == env('ALERT_BEFORE_END_1') && $user->alert_before_end1)
                    || ($days == env('ALERT_BEFORE_END_2') && $user->alert_before_end2)
                    || ($days == 0 && $user->alert_end)
                ) {
                    $counter++;

                    config(['runtime.locale' => $user->locale]);
                    $runTimeLocale = \Locale::getPrimaryLanguage(config('runtime.locale'));
                    LocaleUtils::setAppLocale($runTimeLocale);

                    $recipient = $user;
                    $recipient->notify(new AlertObsoleteAdvert($advert, $days));
                }
                $advert->lastObsoleteMail = $days;
                $advert->timestamps = false;
                $advert->save();
            }
            return $counter;
        } catch (\Exception $e) {
            throw new \Exception('alert user for J-' . $days . ' obsolete advert fails');
        }
    }

    public function alertWaitingsAdverts() {
        try {
            $waitingsAdverts = Advert::waiting()->count();
            if($waitingsAdverts > 0){
                $recipients = User::whereIn('role', PrivilegesUtils::canReceiveWaitingAlert())->get();
                foreach ($recipients as $recipient){
                    $recipient->notify(new AlertWaitingsAdverts($waitingsAdverts));
                }
            }
            return $waitingsAdverts;
        } catch (\Exception $e) {
            throw new \Exception('Validation alert fails');
        }
    }
}