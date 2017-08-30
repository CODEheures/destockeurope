<?php

namespace App\Console;

use App\Common\AdvertsManager;
use App\Common\StatsManager;
use App\Stats;
use Carbon\Carbon;
use Codeheures\LaravelUtils\Traits\Geo\GeoIPUpdater;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Topic;

class Kernel extends ConsoleKernel
{

    const LOG_STOPS = 'stopadvert.log';
    const LOG_SCHEDULE = 'schedule.log';
    const LOG_NOTIFICATIONS = 'notifications.log';
    const LOG_GEOIPUPDATE = 'geoIpUpdate.log';

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        //Stop Adverts Every Minutes
        $schedule->call(function(){
            $message = null;
            $result1='';

            try {
                $vimeoManager = resolve('Vinkla\Vimeo\VimeoManager');
                $advertManager = new AdvertsManager($vimeoManager);
                $result1 = $advertManager->stopAdverts();

                if($result1>0 || (Carbon::now()->hour==5 && Carbon::now()->minute==7)){
                    $message = Carbon::now()->toDateTimeString() . ';' . $result1 . ';';
                }
            } catch (\Exception $e) {
                $message = Carbon::now()->toDateTimeString() . ';' . $result1 . ';' .  $e->getMessage();
            }
            if(!Storage::disk('logs')->exists(self::LOG_STOPS)){
                Storage::disk('logs')->append(self::LOG_STOPS , 'DATE;STOP ADVERTS;FAILS');
            }

            if($message){
                Storage::disk('logs')->append(self::LOG_STOPS , $message);
            }

        })->everyMinute();


        //Scheduler for STATS and Adverts Purges, Alerts
        $purgerScheduler = $schedule->call(function(){
            $message = Carbon::now()->toDateTimeString();
            $purgeResult = [
                'invalids' => 0,
                'abandoned' => 0,
                'obsoletes' => 0,
                'persistent' => ['pictures' => 0, 'videos' => 0]
            ];
            $result3='';
            $result4='';
            try {
                $statManager = new StatsManager();
                $statManager->getStats();

                $vimeoManager = resolve('Vinkla\Vimeo\VimeoManager');
                $advertManager = new AdvertsManager($vimeoManager);
                $purgeResult = $advertManager->purge();
                $result3 = $advertManager->alertEndOfAdverts(env('ALERT_BEFORE_END_1'));
                $result4 = $advertManager->alertEndOfAdverts(env('ALERT_BEFORE_END_2'));

                $message = $message  . ';'
                    . $purgeResult['invalids'] . ';'
                    . $purgeResult['abandoned'] . ';'
                    . $purgeResult['obsoletes'] . ';'
                    . $purgeResult['persistent']['pictures'] . ';'
                    . $purgeResult['persistent']['videos'] . ';'
                    . $result3 . ';'
                    . $result4  .';';
            } catch (\Exception $e) {
                $message = $message . ';'
                    . $purgeResult['invalids'] . ';'
                    . $purgeResult['abandoned'] . ';'
                    . $purgeResult['obsoletes'] . ';'
                    . $purgeResult['persistent']['pictures'] . ';'
                    . $purgeResult['persistent']['videos'] . ';'
                    . $result3 . ';'
                    . $result4  .';'
                    .  $e->getMessage();
            }
            if(!Storage::disk('logs')->exists(self::LOG_SCHEDULE)){
                Storage::disk('logs')->append(self::LOG_SCHEDULE , 'DATE PURGE;INVALIDS;ABANDONED;OBSOLETES;PERSISTENT PICS;PERSISTENT VIDS;ALERT J-2;ALERT J-1;FAILS');
            }
            Storage::disk('logs')->append(self::LOG_SCHEDULE , $message);

        });
        env('APP_SCHEDULE_FAST')==true ? $purgerScheduler->everyMinute() : $purgerScheduler->dailyAt('05:57');

        //Scheduler for Notifications
        $notificationsScheduler = $schedule->call(function(){
            if(env('NOTIFICATION_ACTIVE')==true){
                $status = '0';
                $msgTxt='';
                try {
                    $stat = Stats::whereDate('created_at', Carbon::yesterday())->first();
                    if($stat && ($stat->totalNewFreeAdverts+$stat->totalNewCostAdverts)>0){
                        $msgTxt = ($stat->totalNewFreeAdverts+$stat->totalNewCostAdverts) . ' nouvelles annonces aujourd\'hui';

                        $server_key = env('GOOGLE_FIREBASE_SERVERAPIKEY');
                        $client = new Client();
                        $client->setApiKey($server_key);
                        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

                        $message = new Message();
                        $message->setPriority('high');
                        $message->setTimeToLive(60*60*24);
                        $message->addRecipient(new Topic(config('topics.1')));
                        $notification = new Notification(config('app.name'), $msgTxt);
                        $notification->setClickAction(route('portal'));
                        $notification->setIcon('/android-chrome-512x512.png');
                        $message
                            ->setNotification($notification)
                        ;
                        $response = $client->send($message);
                        $status = $response->getStatusCode();
                    }
                } catch (\Exception $e) {
                    $status = '500';
                    $msgTxt = $e->getMessage();
                }
                if(!Storage::disk('logs')->exists(self::LOG_NOTIFICATIONS)){
                    Storage::disk('logs')->append(self::LOG_NOTIFICATIONS , 'DATE;STATUS_CODE;MESSAGE');
                }
                Storage::disk('logs')->append(self::LOG_NOTIFICATIONS , Carbon::now()->toDateTimeString() . ';' . $status . ';' . $msgTxt );
            }
        });
        env('APP_SCHEDULE_FAST')==true ? $notificationsScheduler->everyMinute() : $notificationsScheduler->dailyAt('6:48');

        //Scheduler for Update GEOIP
        $geoIpScheduler = $schedule->call(function(){
            try {
                $geoIpResult = GeoIPUpdater::updateGeoIpFiles();
            } catch (\Exception $e) {
                $geoIpResult = false;
            }
            if(!Storage::disk('logs')->exists(self::LOG_GEOIPUPDATE)){
                Storage::disk('logs')->append(self::LOG_GEOIPUPDATE , 'DATE;RESULT');
            }
            Storage::disk('logs')->append(self::LOG_GEOIPUPDATE , Carbon::now()->toDateTimeString() . ';' . $geoIpResult);
        });
        env('APP_SCHEDULE_FAST')==true ? $geoIpScheduler->hourly() : $geoIpScheduler->monthlyOn(7,'3:57');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
