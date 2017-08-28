<?php

namespace App\Http\Controllers;


use App\Advert;
use App\Common\AdvertsManager;
use App\Common\InvoiceUtils;
use App\Common\PrivilegesUtils;
use App\Common\StatsManager;
use App\Common\UserUtils;
use App\Console\Kernel;
use App\Invoice;
use App\Parameters;
use App\Stats;
use App\User;
use Carbon\Carbon;
use Codeheures\LaravelUtils\Traits\Tools\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Vinkla\Vimeo\VimeoManager;
use GuzzleHttp\Client as GuzzleClient;

class AdminController extends Controller
{
    private $vimeoManager;

    public function __construct(VimeoManager $vimeoManager) {
        $this->middleware('auth');
        $this->middleware('isAdminUser', ['except' => ['delegations', 'delegation', 'invoiceManage', 'listInvoices', 'showInvoice']]);
        $this->middleware('canGetDelegations', ['only' => ['delegations', 'delegation']]);
        $this->middleware('canManageInvoices', ['only' => ['invoiceManage', 'listInvoices', 'showInvoice']]);
        $this->middleware('appOnDevelMode', ['only' => ['testGame','tempo']]);
        $this->middleware('stopAnalytics');
        $this->vimeoManager = $vimeoManager;
    }

    /************************************************************************
     * PUBLIC PARTS: RETURN VIEWS
     *************************************************************************/

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
        //get load infos
        $client = new GuzzleClient();
        $loadInfos = [];

        foreach (config('pictures.service.domains') as $domain) {
            $infos = $client->request('GET',
                $domain . config('pictures.service.urls.routeGetInfos'),
                [
                    'http_errors' => false,
                ]
            );
            $loadInfos[] = json_decode($infos->getBody()->getContents());
        }


        return view('application.dashboard', compact('loadInfos'));
    }

    /**
     * Return view for List of Adverts type delegation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delegations(Request $request) {
        $request->session()->flash('clear',true);
        $routeList = route('advert.getDelegations');
        $title = trans('strings.menu_advert_delegations');
        $isDelegation = true;
        return view('user.personnalList', compact('routeList', 'title', 'isDelegation'));
    }

    /**
     * Return view for List of Adverts type delegation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delegation($id) {
        $advert = Advert::find($id);
        if($advert && $advert->is_delegation){
            $advert->load('pictures');
            $advert->load('category');
            $advert->load(['user' => function ($query) {
                $query->select(['id','email','phone', 'compagnyName']);
            }]);
            $title = trans('strings.view_delegation_header', ['advertTitle' => $advert->title]);
            return view('advert.manageDelegation', compact('advert', 'title'));
        } else {
            return response('not found', 404);
        }
    }

    /**
     * Return the view of invoice manager
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invoiceManage(Request $request) {
        $request->session()->flash('clear',true);
        return view('invoice.manage');
    }


    /************************************************************************
     * PUBLIC PARTS: RETURN INFOS
     *************************************************************************/

    /**
     * Return JSON of Stats for XMLHttpRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats(){
        $date = Carbon::now()->subMonths(6);
        $viewsByDay = Stats::viewsByDay($date)->get();
        $advertsByDay = Stats::advertsByDay($date)->get();
        $costsByDay = Stats::costsByDay($date)->get();

        $csvLogs=[];

        //schedule log
        $csvLogs['schedule'] = $this->getLogs(Kernel::LOG_SCHEDULE);

        //stops log
        $csvLogs['stops'] = $this->getLogs(Kernel::LOG_STOPS);

        //notifications log
        $csvLogs['notifications'] = $this->getLogs(Kernel::LOG_NOTIFICATIONS);

        //geoIpUpdate log
        $csvLogs['geoIpUpdate'] = $this->getLogs(Kernel::LOG_GEOIPUPDATE);

        return response()->json([
            'viewsByDay' => $viewsByDay->toArray(),
            'advertsByDay' => $advertsByDay->toArray(),
            'costsByDay' => $costsByDay->toArray(),
            'logs' => $csvLogs
        ]);
    }

    private function getLogs($logName) {
        $log = (storage_path('logs').'/'.$logName);
        $handle = file_exists($log) ? fopen($log,'r') : null;
        $csv=[];
        if ($handle) {
            while ($line = fgetcsv($handle,0,";")) {
                $csv[] = $line;
                if (count($csv) > 4) {
                    array_splice($csv, 1,1);
                }
            }
            fclose($handle);
        }
        return $csv;
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
        $list = Database::getEnumValues('parameters', 'welcomeType');
        $transList = [];
        foreach ($list as $key => $item) {
            $transList[$key] =  $item;
        }
        return response()->json($transList);
    }

    /**
     * Return the view of user manager
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userManage(Request $request) {
        $request->session()->flash('clear',true);
        return view('user.manage');
    }

    /**
     *
     * Return invoice file
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function showInvoice($id) {
        $invoice = Invoice::find($id);
        $file = null;
        if(!is_null($invoice)){
            $file = InvoiceUtils::getInvoiceFile($invoice);
        }

        if(!is_null($file)){
            return response(file_get_contents($file),200)->header("Content-Type", "application/pdf");
        } else {
            return response(trans('strings.view_all_error_download_file'), 404);
        }

    }


    /************************************************************************
     * PUBLIC PARTS: PROCESS
     *************************************************************************/

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
     * Patch Role User
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function patchRole($id, Request $request) {
        $user = User::find($id);
        if($user
            && $request->has('role')
            && in_array($request->role, User::ROLES)
            && PrivilegesUtils::canPatchRoleUser($user))
        {
            if(in_array($request->role, PrivilegesUtils::mustBeCompleteToBePatched()) && !UserUtils::haveCompleteAccount($user)){
                return response(trans('strings.view_all_incomplete_account'), 409);
            }
            $user->role = $request->role;
            $user->save();
            return response('ok',200);
        } else {
            return response('error', 500);
        }
    }

    /**
     * Delete User
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteUser($id, Request $request) {

        $user = User::find($id);

        if($user
            && PrivilegesUtils::canDeleteUser($user))
        {
            $user->email = $user->email . '_old' . time();
            $user->save();
            $user->delete();
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
            $advertManager = new AdvertsManager( $this->vimeoManager);
            $purgeResults = $advertManager->purge();
            //update stats
            $statsManager = new StatsManager();
            $statsManager->getStats();

            $info =  trans('strings.admin_purge_response', [
                'nbInvalidsadvert' => $purgeResults['invalids']['adverts'],
                'nbAbandonedAdvert' => $purgeResults['abandoned']['adverts'],
                'nbObsoletesAdvert' => $purgeResults['obsoletes']['adverts'],
                'nbimg' => $purgeResults['invalids']['pictures']+$purgeResults['abandoned']['pictures']+$purgeResults['obsoletes']['pictures'],
                'nbLocalImg' => $purgeResults['obsoleteLocalTempo']['pictures'],
                'nbvideos' => $purgeResults['persistent']['videos']
            ]);

            return response($info,200);
        } catch (\Exception $e) {
            return response($e->getMessage(),500);
        }

    }

    /**
     * Test if Lang Files have same keys
     * @return mixed
     */
    public function testLangs() {
        $lines = [];
        $arglist = [];
        foreach (config('codeheuresUtils.availableLocales') as $locale) {
            $lines[$locale] = Lang::get('strings',[],$locale);
            $arglist[] = $lines[$locale];
        }

        return call_user_func_array("array_diff_key", $arglist);
    }

    /**
     * Test de creation de PDF
     */
    public function testPdf() {
        $content = '
        <!DOCTYPE html>
        <html lang="en">
        <body>
        
        <div class="seller">
            <div class="from-seller">Designation du vendeur</div>
            <div class="infos">
                SAS <br />
                32 , s<br />
                SIRET: <br/>
                TVA n°:
            </div>
        </div>
        
        <div class="customer">
            <div class="to-customer">Designation du client</div>
            <div class="infos">
                <br />
                1 Rue-lès-, France
                <br />TVA n°:-/-
            </div>
        </div>
        
        <div class="invoice-title">
            <h2>Facture n°1:</h2>
            <div class="price">
                <div class="total-price">
                    Coût total: 58.80€ TTC
                </div>
            </div>
        </div>
        <div class="invoice">
            <table>
                <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix Ht</th>
                    <th>Quantité</th>
                    <th>TVA</th>
                    <th>Prix TTC</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Annonce urgente</td>
                    <td>49.00€</td>
                    <td>1</td>
                    <td>20%</td>
                    <td>58.80€</td>
                </tr>
                </tbody>
            </table>
            <p class="line-info">Cette facture contient 1 ligne.</p>
            <div class="invoice-total">
                <div class="invoice-total-title">
                    <p class="ht">Total HT</p>
                    <p class="tva">Total TVA</p>
                    <p class="ttc">Total TTC</p>
                </div>
                <div class="invoice-total-value">
                    <p class="ht">49.00€</p>
                    <p class="tva">9.80€</p>
                    <p class="ttc">58.80€</p>
                </div>
            </div></div>
        
        <div class="invoice-title">
            <h2>
                Facture acquitée le mercredi 22 mars 2017
            </h2>
        </div>
        </body>
        </html>
        ';

        $header = '
        <div class="pdfHeader">
        <div class="logo">
            <a href="http://destockeurope.progress/home" class="navbar-logo ">
                <img src="'. asset('/images/logopdf.svg') .'"/>
            </a>
        </div>
        
        <p class="navbar-menu">
            <a href="http://destockeurope.progress/home">Facture n°1<br/>
                <span class="created_at">
                        Emise le: mercredi 22 mars 2017
                    </span>
            </a>
        </p>
        
        </div>';

        $footer = '
        <div class="pdfFooter">
        <div class="infos">
            <p>
                SAS DestockEurope<br/>
                32 rue Gutenberg, 37300 Joué-Lès-Tours<br />
                06.87.34.06.83<br />
                contact@desctockeurope.com
            </p>
        </div>
        <div class="copyright">
            <p class="page">Page <span class="page">{PAGENO}</span> sur <span class="topage">{nbpg}</span></p>
            <div class="suiteLogo">
                <span class="htmlentity">&copy;</span>2017 DestockEurope<span class="logo"></span>
            </div>
        </div>
        </div>';

        $css = 'table a:link{color:#666}table a:link,table a:visited{font-weight:700;text-decoration:none}table a:visited{color:#999}table a:active,table a:hover{color:#bd5a35;text-decoration:underline}table caption{width:100%;text-align:left}table caption span{font-size:1rem}table{font-family:Arial,Helvetica,sans-serif;color:#666;font-size:1.2rem;text-shadow:.1rem .1rem 0 #fff;background:#eaebec;margin:2rem;border:none;border-radius:.3rem;box-shadow:0 .1rem .2rem #e1e1e1}table th{padding:2.1rem .8rem 2.2rem;border-top:.1rem solid #fafafa;border-bottom:.1rem solid #e0e0e0;background:#ededed;background:-webkit-linear-gradient(bottom,#ededed,#ebebeb);background:linear-gradient(0deg,#ededed,#ebebeb)}table th:first-child{text-align:left;padding-left:2rem}table tr:first-child th:first-child{border-top-left-radius:.3rem}table tr:first-child th:last-child{border-top-right-radius:.3rem}table tr{text-align:center;padding-left:2rem;display:table-row}table td:first-child{text-align:left;padding-left:2rem;border-left:0}table td{padding:.8rem;border-top:.1rem solid #fff;border-bottom:.1rem solid #e0e0e0;border-left:.1rem solid #e0e0e0;background:#fff}table tr.even td{background:#f6f6f6;background:-webkit-linear-gradient(bottom,#f8f8f8,#f6f6f6);background:linear-gradient(0deg,#f8f8f8,#f6f6f6)}table tr:last-child td{border-bottom:0}table tr:last-child td:first-child{border-bottom-left-radius:.3rem}table tr:last-child td:last-child{border-bottom-right-radius:.3rem}table tbody tr:hover td{background:#f2f2f2;background:-webkit-linear-gradient(bottom,#f2f2f2,#f0f0f0);background:linear-gradient(0deg,#f2f2f2,#f0f0f0)}body{background-color:transparent}body,div.pdfHeader{font-family:open sans,Helvetica Neue,Arial,sans-serif}div.pdfHeader{margin:0;padding-top:.3cm;padding-left:.3cm;padding-right:.3cm;width:100%;height:1.2cm;line-height:16px;background:rgba(34,34,34,.06);border-bottom:1px solid #32368c;font-size:10px;font-weight:100}div.pdfHeader div.logo{float:left;vertical-align:middle;width:2.5cm}div.pdfHeader div.logo .navbar-logo img{width:2.5cm;padding-bottom:.1cm}div.pdfHeader div.rubalise{width:100%;height:.4cm;padding-top:-.1cm;padding-bottom:-.1cm;background-size:contain;background-image:url("/images/rubalisepdf.svg")}div.pdfHeader .navbar-menu{float:right;text-align:right;overflow:hidden}div.pdfHeader .navbar-menu a{text-transform:uppercase;font-size:10px;margin:0 10px;position:relative;color:#222}div.pdfHeader .navbar-menu a span.created_at{position:relative;left:10px}div.seller div.from-seller{float:left;display:inline-block;width:180px}div.seller div.infos{float:left;padding-left:10px;margin-left:20px;border-left:1px solid #000}div.customer{padding-top:50px;margin-left:5cm}div.customer div.to-customer{float:left;display:inline-block;width:180px}div.customer div.infos{float:left;padding-left:10px;margin-left:20px;border-left:1px solid #000}div.invoice-title{margin-top:20px;line-height:40px}div.invoice-title h2{display:inline-block;font-size:18px}div.invoice-title div.price{float:right}div.invoice-title div.price div.total-price{width:5cm;float:right;margin-right:40px;padding:0 15px;border-radius:8px;border:1px solid #32368c;background-color:#32368c;color:#fff;text-align:center}div.invoice{padding-top:20px}div.invoice p{padding:0;font-weight:700}div.invoice p.line-info{font-size:12px;font-weight:100}div.invoice table{margin:0;width:100%;border:1px solid #b5b5b5;border-radius:6px}div.invoice table tr{page-break-inside:avoid;page-break-after:auto}div.invoice table tr td{text-align:right}div.invoice table td{page-break-inside:avoid;page-break-after:auto;font-size:12px}div.invoice table thead{display:table-header-group}div.invoice table thead tr th{font-size:14px;padding-top:5px;padding-bottom:5px}div.invoice div.invoice-total p:not(.ttc){font-weight:100}div.invoice div.invoice-total p.ttc{margin-top:10px;padding-top:5px}div.invoice div.invoice-total div.invoice-total-title{width:60%;display:inline-block;float:left;text-align:right}div.invoice div.invoice-total div.invoice-total-value{text-align:right;float:right;width:25%;display:inline-block;padding-right:20px}div.invoice div.invoice-total div.invoice-total-value p.ttc{border-top:2px solid #000}div.pdfFooter{margin:0;padding-top:.3cm;padding-left:.3cm;padding-right:.3cm;width:100%;height:1.2cm;font-size:10px;line-height:10px;background:rgba(34,34,34,.06);border-top:1px solid #222}div.pdfFooter .infos{width:30%;float:left}div.pdfFooter .infos i{padding-right:3px}div.pdfFooter .infos i:not(:first-of-type){padding-left:10px}div.pdfFooter .copyright{width:40%;float:right;position:relative;text-align:right;vertical-align:top}div.pdfFooter .copyright p.page{padding-bottom:.2cm}div.pdfFooter .copyright div.suiteLogo{float:right;vertical-align:top;height:1cm;width:3cm}';

        $mpdf = new mPDF();
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);
        $mpdf->AddPageByArray([
            'margin-left' => 10,
            'margin-right' => 10,
            'margin-top' => 30,
            'margin-bottom' => 30,
            'margin-header' => 10,
            'margin-footer' => 10
        ]);
        $mpdf->WriteHTML($css,1);
        $mpdf->WriteHTML($content,2);
        $storage = storage_path('app/testpdf.pdf');
        Storage::delete($storage);

        $mpdf->Output($storage, 'F');
        return response('Fichier ici: ' . $storage,200);
    }

    /**
     * Tempo tests
     */
    public function tempo(){

    }

    /**
     *
     * Reset app with testGame
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function testGame($quantity=null) {
        if(!is_null($quantity)){
            config(['runtime.tempo.seeder.quantity' => $quantity]);
        } else {
            config(['runtime.tempo.seeder.quantity' => 1]);
        }
        $exitCode = Artisan::call('migrate:refresh', ['--seed' => true]);
        return redirect(route('home'))->with('info', $exitCode . ': ' . trans('strings.admin_testGame_memo'));
    }


    /************************************************************************
     * PUBLIC PARTS: RETURN LISTS
     *************************************************************************/

    /**
     *
     * Get List of Invoices
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listInvoices(Request $request) {

        //Init vars
        $isSearchRequest = ($request->has('search') && strlen($request->search) >= 3);
        $isSearchResults = ($request->has('resultsFor') && strlen($request->resultsFor) >= 3);

        $invoices = Invoice::join('users', 'users.id', '=', 'invoices.user_id')
            ->select(array(DB::raw('users.email as usermail'),DB::raw('invoices.*')));

        $search = null;
        if($isSearchRequest){
            $search = $request->search;
        } elseif ($isSearchResults) {
            $search = $request->resultsFor;
        }

        if(!is_null($search)){
            $invoices = $invoices->where(function ($query) use ($search) {
                $query->where('invoices.invoice_number', 'LIKE', '%' .$search .'%')
                    ->orWhere('invoices.captureId', 'LIKE', '%' .$search .'%')
                    ->orWhere('invoices.voidId', 'LIKE', '%' .$search .'%')
                    ->orWhere('invoices.refundId', 'LIKE', '%' .$search .'%')
                    ->orWhere('users.email', 'LIKE', '%' .$search .'%')
                    ->orWhere(function ($query) use ($search) {
                        try {
                            $query->whereDate('invoices.created_at', Carbon::parse($search));
                        } catch (\Exception $e) {

                        }
                    });
            });
        }

        if($isSearchRequest) {
            $countSearch = $invoices->count();
            $invoices = $invoices->orderBy('invoices.created_at', 'desc')->limit(config('runtime.maxNumberOfSearchResults'))->get();
        } else {
            $invoices = $invoices->orderBy('invoices.created_at', 'desc')->paginate(10);
        }

        $invoices->load('user');
        if($isSearchRequest){
            $action = [
                'url' => '#',
                'text' => trans_choice('strings.form_input_search_view_all', $countSearch, ['nb' => $countSearch])
            ];
            return response()->json(['results'=> $invoices, 'action' => $action]);
        } else {
            return response()->json(['invoices'=> $invoices]);
        }
    }

    /**
     *
     * Get List of Invoices
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUsers(Request $request) {

        //Init vars
        $isSearchRequest = ($request->has('search') && strlen($request->search) >= 3);
        $isSearchResults = ($request->has('resultsFor') && strlen($request->resultsFor) >= 3);

        $users = User::query();

        $search = null;
        if($isSearchRequest){
            $search = $request->search;
        } elseif ($isSearchResults){
            $search = $request->resultsFor;
        }

        //search results before range price
        if(!is_null($search)){
            $users = $users->where(function ($query) use ($search) {
                $query->where('email', 'LIKE', '%' .$search .'%')
                    ->orWhere('name', 'LIKE', '%' .$search .'%')
                    ->orWhere('compagnyName', 'LIKE', '%' .$search .'%')
                    ->orWhere('registrationNumber', 'LIKE', '%' .$search .'%')
                    ->orWhere('role', 'LIKE', '%' .$search .'%');
            });
        }

        if($isSearchRequest) {
            $countSearch = $users->count();
            $users = $users->orderBy('created_at', 'desc')->limit(config('runtime.maxNumberOfSearchResults'))->get();
        } else {
            $users = $users->orderBy('created_at', 'desc')->paginate(10);
        }

        if($isSearchRequest){
            $action = [
                'url' => '#',
                'text' => trans_choice('strings.form_input_search_view_all', $countSearch, ['nb' => $countSearch])
            ];
            return response()->json(['results'=> $users, 'action' => $action]);
        } else {
            foreach ($users as $user){
                $user->setIsRemovable();
                $user->setUrlSetRole();
                $user->isRemovable ? $user->setUrlDelete() : null;
            }
            $users->makeVisible(['role', 'rolesList', 'urlSetRole'])->toArray();
            return response()->json(['users'=> $users]);
        }
    }
}
