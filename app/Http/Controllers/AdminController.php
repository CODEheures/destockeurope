<?php

namespace App\Http\Controllers;


use App\Common\AdvertsManager;
use App\Common\DBUtils;
use App\Common\PicturesManager;
use App\Common\StatsManager;
use App\Jobs\TransferMedias;
use App\Parameters;
use App\Stats;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    /**
     * Return the view for manage application
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manage(){
        return view('application.manage');
    }

    /**
     * Return the view of dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard(){
        return view('application.dashboard');
    }

    /**
     * Return view for List of Adverts type delegation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delegations() {
        $routeList = route('advert.getDelegations');
        $title = trans('strings.menu_advert_delegations');
        return view('user.personnalList', compact('routeList', 'title'));
    }

    /**
     * Return JSON of Stats for XMLHttpRequest
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Return App parameters for XMLHttpRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function appParameters() {
        $parameters = Parameters::orderBy('id', 'desc')->first();
        if($parameters){
            return response()->json($parameters);
        } else {
            $parameters = new Parameters();
            $parameters->save();

            $parameters = Parameters::first();
            if($parameters){
                return response()->json($parameters);
            } else {
                return response('error', 500);
            }
        }
    }

    /**
     * Patch App Parameters
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function patchParameters(Request $request) {
        $parameters = Parameters::orderBy('id', 'desc')->first();
        if($parameters){
            $parameters->update($request->all());
            $parameters->save();
            return response('ok',200);
        } else {
            return response('error', 500);
        }
    }

    /**
     * Purge App of obsoletes Adverts, Pictures, Video
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function cleanApp() {
        try {
            $advertManager = new AdvertsManager($this->pictureManager, $this->vimeoManager);
            $info = $advertManager->purge();
            //update stats
            $statsManager = new StatsManager();
            $statsManager->getStats();
            return response($info,200);
        } catch (\Exception $e) {
            return response($e->getMessage(),500);
        }

    }

    /**
     * Create a Job for Transfert Medias from local disk to cloud
     * @param $sizeInMb
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function transfertMedias($sizeInMb) {
        if($sizeInMb <= 0) {
            return response(trans('strings.admin_transfert_size_null'), 500);
        }
        $parameters = Parameters::latest()->first();
        if($parameters && !$parameters->isOnTransfert) {
            $parameters = Parameters::latest()->first();
            $parameters->transfertTotal = 0;
            $parameters->transfertPartial = 0;
            $parameters->save();
            $job = (new TransferMedias($sizeInMb))->delay(Carbon::now()->addSeconds(5))->onConnection('database');
            $this->dispatch($job);
            return response(trans('strings.admin_transfert_image_response', ['nb' => $sizeInMb, 'disk' => PicturesManager::DISK_DISTANT]), 202);
        } else {
            return response(trans('strings.admin_transfert_image_exist'), 500);
        }
    }

    /**
     * Get the Progress of Transfert XMLHttpRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function progressTransfertMedias() {
        $parameters = Parameters::latest()->first();
        return response()->json([$parameters->isOnTransfert, $parameters->transfertPartial, $parameters->transfertTotal]);
    }

    /**
     * Return the typ of welcome page XMLHttpRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWelcomeType() {
        $list = DBUtils::getEnumValues('commons', 'welcomeType');
        $transList = [];
        foreach ($list as $key => $item) {
            $transList[$key] =  $item;
        }
        return response()->json($transList);
    }

    /**
     * Test if Lang Files have same keys
     * @return mixed
     */
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
