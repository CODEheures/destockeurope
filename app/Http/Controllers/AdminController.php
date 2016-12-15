<?php

namespace App\Http\Controllers;


use App\Common;
use App\Common\DBUtils;
use App\Stats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class AdminController extends Controller
{


    public function __construct() {
        $this->middleware('isAdminUser');
    }

    public function manage(){
        return view('application.manage');
    }

    public function getStats(){
        $date = Carbon::now()->subMonths(6);
        $viewsByDay = Stats::select(array(DB::raw('DATE(`created_at`) as `date`'), DB::raw('SUM(totalNewView) as views')))
            ->where('created_at', '>', $date)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('views','date');

        $validAdvertsByDay = Stats::select(array(DB::raw('DATE(`created_at`) as `date`'), DB::raw('AVG(totalAdverts) as adverts')))
            ->where('created_at', '>', $date)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('adverts','date');

        $invalidAdvertsByDay = Stats::select(array(DB::raw('DATE(`created_at`) as `date`'), DB::raw('AVG(totalInvalidAdverts) as adverts')))
            ->where('created_at', '>', $date)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('adverts','date');

        $waitingAdvertsByDay = Stats::select(array(DB::raw('DATE(`created_at`) as `date`'), DB::raw('AVG(totalWaitingAdverts) as adverts')))
            ->where('created_at', '>', $date)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('adverts','date');

        $avgCostAdvertsByDay = Stats::select(array(DB::raw('DATE(`created_at`) as `date`'), DB::raw('AVG(totalCostAdverts) as avg_costs')))
            ->where('created_at', '>', $date)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('costs','date');

        $sumCostAdvertsByDay = Stats::select(array(DB::raw('DATE(`created_at`) as `date`'), DB::raw('SUM(totalCostAdverts) as avg_costs')))
            ->where('created_at', '>', $date)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('costs','date');

        $filesInfo = Stats::latest()->first();


        return response()->json([
            'viewsByDay' => $viewsByDay,
            'validAdvertsByDay' => $validAdvertsByDay,
            'invalidAdvertsByDay' => $invalidAdvertsByDay,
            'waitingAdvertsByDay' => $waitingAdvertsByDay,
            'avgCostAdvertsByDay' => $avgCostAdvertsByDay,
            'sumCostAdvertsByDay' => $sumCostAdvertsByDay,
            'filesInfo' => $filesInfo
        ]);
    }

    public function appParameters() {
        $parameters = Common::orderBy('id', 'desc')->first();
        if($parameters){
            return response()->json($parameters);
        } else {
            $parameters = new Common();
            $parameters->save();

            $parameters = Common::first();
            if($parameters){
                return response()->json($parameters);
            } else {
                return response('error', 500);
            }
        }
    }

    public function patchParameters(Request $request) {
        $parameters = Common::orderBy('id', 'desc')->first();
        if($parameters){
            $parameters->update($request->all());
            $parameters->save();
            return response('ok',200);
        } else {
            return response('error', 500);
        }
    }

    public function cleanApp() {
        try {
            $pictureManager = new Common\PicturesManager();
            $advertManager = new Common\AdvertsManager($pictureManager);
            $info = $advertManager->purge();
            //update stats
            $statsManager = new Common\StatsManager();
            $statsManager->getStats();
            return response($info,200);
        } catch (\Exception $e) {
            return response($e,500);
        }

    }

    public function lightenLocalDisk() {
        $quantity = 5;
        $process = new Process('php ../artisan medias:transfert ' . $quantity . ' --quiet');
        $process->start();
        return response(trans('strings.admin_transfert_image_response', ['nb' => $quantity, 'disk' => Common\PicturesManager::DISK_DISTANT]),202);
    }

    public function getWelcomeType() {
        $list = DBUtils::getEnumValues('commons', 'welcomeType');
        $transList = [];
        foreach ($list as $key => $item) {
            $transList[$key] =  $item;
        }
        return response()->json($transList);
    }
}
