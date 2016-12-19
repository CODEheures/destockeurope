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
            try {
                $statManager = new StatsManager();
                $statManager->getStats();

                $pictureManager = new PicturesManager();
                $advertManager = new AdvertsManager($pictureManager);
                $result1 = $advertManager->stopAdverts();
                $result2 = $advertManager->purgeObsoletesAdverts();

                $message = $message . ';stop adverts;' . $result1 . ';purge adverts;' . $result2[0] . ';delete pictures;' . $result2[1];
            } catch (\Exception $e) {
                $message = $message . ';schedule fails: ' . $e->getMessage();
            }
            Storage::append('/logs/schedule.log', $message);

        })->everyThirtyMinutes();//->dailyAt('02:00');
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
