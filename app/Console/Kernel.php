<?php

namespace App\Console;

use App\Common\AdvertsManager;
use App\Common\GeoIPUpdater;
use App\Common\PicturesManager;
use App\Common\StatsManager;
use App\Stats;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Topic;

class Kernel extends ConsoleKernel
{
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

        //Scheduler for STATS and Adverts Ends, Purges, Alerts
        $schedule->call(function(){
            $message = Carbon::now()->toDateTimeString();
            $result1='';
            $result2=['',''];
            $result3='';
            $result4='';
            $result5='';
            try {
                $statManager = new StatsManager();
                $statManager->getStats();

                $pictureManager = new PicturesManager();
                $vimeoManager = resolve('Vinkla\Vimeo\VimeoManager');
                $advertManager = new AdvertsManager($pictureManager, $vimeoManager);
                $result1 = $advertManager->stopAdverts();
                $result2 = $advertManager->purgeObsoletesAdverts();
                $result3 = $advertManager->alertEndOfAdverts(env('ALERT_BEFORE_END_1'));
                $result4 = $advertManager->alertEndOfAdverts(env('ALERT_BEFORE_END_2'));
                $result5 = $advertManager->alertEndOfAdverts(0);

                $message = $message . ';' . $result1 . ';' . $result2[0] . ';' . $result2[1] .';' . $result3 . ';' . $result4 . ';' . $result5 .';;';
            } catch (\Exception $e) {
                $message = $message . ';' . $result1 . ';' . $result2[0] . ';' . $result2[1] . ';' . $result3 . ';' . $result4 . ';' . $result5 . ';' .  $e->getMessage().';';
            }
            if(!Storage::disk('logs')->exists('schedule.log')){
                Storage::disk('logs')->append('schedule.log' , 'DATE;STOP ADVERTS;PURGE ADVERTS;DELETE PICTURES;ALERT USER J-5;ALERT USER J-1;ALERT USER J0;FAILS;');
            }
            Storage::disk('logs')->append('schedule.log' , $message);

        })->dailyAt('05:57');

        //Scheduler for Notifications
        $schedule->call(function(){
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
            if(!Storage::disk('logs')->exists('notifications.log')){
                Storage::disk('logs')->append('notifications.log' , 'DATE;STATUS_CODE;MESSAGE');
            }
            Storage::disk('logs')->append('notifications.log' , Carbon::now()->toDateTimeString() . ';' . $status . ';' . $msgTxt );

        })->dailyAt('11:48');

        //Scheduler for Update GEOIP
        $schedule->call(function(){
            try {
                $geoIpResult = GeoIPUpdater::updateGeoIpFiles();
            } catch (\Exception $e) {
                $geoIpResult = false;
            }
            if(!Storage::disk('logs')->exists('geoIpUpdate.log')){
                Storage::disk('logs')->append('geoIpUpdate.log' , 'DATE;RESULT;');
            }
            Storage::disk('logs')->append('geoIpUpdate.log' , Carbon::now()->toDateTimeString() . ';' . $geoIpResult);
        })->monthlyOn(7,'3:57');
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
