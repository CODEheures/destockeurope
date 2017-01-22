<?php

namespace App\Http\Controllers;


use App\Common;
use App\Common\DBUtils;
use App\Common\PicturesManager;
use App\Jobs\TransferMedias;
use App\Picture;
use App\Stats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Vinkla\Vimeo\VimeoManager;

class AdminController extends Controller
{
    private $pictureManager;
    private $vimeoManager;

    public function __construct(PicturesManager $picturesManager, VimeoManager $vimeoManager) {
        $this->middleware('isAdminUser');
        $this->pictureManager = $picturesManager;
        $this->vimeoManager = $vimeoManager;
    }

    public function manage(){
        return view('application.manage');
    }

    public function dashboard(){
        return view('application.dashboard');
    }

    public function delegations() {
        $routeList = route('advert.getDelegations');
        $title = trans('strings.menu_advert_delegations');
        return view('user.personnalList', compact('routeList', 'title'));
    }

    public function getStats(){
        $date = Carbon::now()->subMonths(6);
        $viewsByDay = Stats::viewsByDay($date)->get();
        $advertsByDay = Stats::advertsByDay($date)->get();
        $costsByDay = Stats::costsByDay($date)->get();
        $filesInfo = Stats::latest()->first();

        return response()->json([
            'viewsByDay' => $viewsByDay->toArray(),
            'advertsByDay' => $advertsByDay->toArray(),
            'costsByDay' => $costsByDay->toArray(),
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
            $advertManager = new Common\AdvertsManager($this->pictureManager, $this->vimeoManager);
            $info = $advertManager->purge();
            //update stats
            $statsManager = new Common\StatsManager();
            $statsManager->getStats();
            return response($info,200);
        } catch (\Exception $e) {
            return response($e->getMessage(),500);
        }

    }

    public function transfertMedias($sizeInMb) {
        if($sizeInMb <= 0) {
            return response(trans('strings.admin_transfert_size_null'), 500);
        }
        $commons = Common::latest()->first();
        if($commons && !$commons->isOnTransfert) {
            $commons = Common::latest()->first();
            $commons->transfertTotal = 0;
            $commons->transfertPartial = 0;
            $commons->save();
            $job = (new TransferMedias($sizeInMb))->delay(Carbon::now()->addSeconds(5))->onConnection('database');
            $this->dispatch($job);
            return response(trans('strings.admin_transfert_image_response', ['nb' => $sizeInMb, 'disk' => Common\PicturesManager::DISK_DISTANT]), 202);
        } else {
            return response(trans('strings.admin_transfert_image_exist'), 500);
        }
    }

    public function progressTransfertMedias() {
        $common = Common::latest()->first();
        return response()->json([$common->isOnTransfert, $common->transfertPartial, $common->transfertTotal]);
    }

    public function getWelcomeType() {
        $list = DBUtils::getEnumValues('commons', 'welcomeType');
        $transList = [];
        foreach ($list as $key => $item) {
            $transList[$key] =  $item;
        }
        return response()->json($transList);
    }

    public function testLangs() {
        $lines = [];
        $arglist = [];
        foreach (config('app.locales') as $locale) {
            $lines[$locale] = Lang::get('strings',[],$locale);
            $arglist[] = $lines[$locale];
        }

        return call_user_func_array("array_diff_key", $arglist);
    }
}
