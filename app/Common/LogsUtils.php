<?php

namespace App\Common;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait LogsUtils
{

    static $LOG_STOPS = 'stopadvert.log';
    static $LOG_SCHEDULE = 'schedule.log';
    static $LOG_NOTIFICATIONS = 'notifications.log';
    static $LOG_WAITINGS = 'waitings.log';
    static $LOG_SITEMAP = 'sitemap.log';
    static $LOG_GEOIPUPDATE = 'geoIpUpdate.log';
    static $LOG_STORE_ADVERT = 'storeadvert.log';

    public static function addStoreLog(string $step, Request $request = null, string $error = null) {
        if(!Storage::disk('logs')->exists(self::$LOG_STORE_ADVERT)){
            Storage::disk('logs')->append(self::$LOG_STORE_ADVERT , 'DATE;STEP;LOGS;FAILS');
        }

        $requestInfos = [];
        if ($request){
            $requestInfos = [
                'server' => $request->server(),
                'headers' => $request->header(),
                'request' => $request->toArray(),
            ];
        }

        $user = auth()->user();
        $userInfos = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'currency' => $user->currency,
            'locale' => $user->locale,
            'compagnyName' => $user->compagnyName,
            'runtime' => config('')
            ];

        $errorInfos = [];
        if ($error) {
            $errorInfos = [
                'message' => $error
            ];
        }

        $infos = [
            'user' => $userInfos,
            'request' => $requestInfos,
            'error' => $errorInfos
        ];

        $message  = Carbon::now()->toDateTimeString() . ';' . $step . ';' . json_encode($infos);

        Storage::disk('logs')->append(self::$LOG_STORE_ADVERT , $message);

    }

}