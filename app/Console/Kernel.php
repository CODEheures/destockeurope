<?php

namespace App\Console;

use App\Common\AdvertsManager;
use App\Common\PicturesManager;
use App\Common\StatsManager;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;

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
        // $schedule->command('inspire')
        //          ->hourly();
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
                $advertManager = new AdvertsManager($pictureManager);
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
