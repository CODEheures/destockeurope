<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Category;
use App\Common\AdvertUtils;
use App\Common\CostUtils;
use App\Common\GeoManager;
use App\Common\InvoiceUtils;
use App\Common\LocaleUtils;
use App\Common\MoneyUtils;
use App\Common\PaymentUtils;
use App\Common\PrivilegesUtils;
use App\Common\UserUtils;
use App\Http\Requests\StoreAdvertRequest;
use App\Invoice;
use App\Notifications\AdvertApprove;
use App\Notifications\AdvertBackToTop;
use App\Notifications\AdvertHighlight;
use App\Notifications\AdvertNotApprove;
use App\Notifications\AdvertRenew;
use App\Notifications\CustomerContactIntermediary;
use App\Notifications\CustomerContactSeller;
use App\Notifications\InvoicePdf;
use App\Notifications\InvoiceRefundPdf;
use App\Notifications\ReportAdvert;
use App\Notifications\ReportAppError;
use App\Persistent;
use App\Picture;
use App\Stats;
use App\User;
use Carbon\Carbon;
use Codeheures\LaravelUtils\Traits\Tools\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Vinkla\Vimeo\VimeoManager;

class AdvertController extends Controller
{
    private $vimeoManager;

    /**
     * AdvertController constructor.
     * @param VimeoManager $vimeoManager
     */
    public function __construct(VimeoManager $vimeoManager) {
        $this->middleware('auth', ['except' => ['index', 'show', 'getListType', 'getHighlight', 'sendMail', 'report']]);
        $this->middleware('isEmailConfirmed', ['except' => ['index', 'show', 'getListType', 'getHighlight', 'sendMail', 'report', 'bookmarks', 'unbookmarks']]);
        $this->middleware('haveCompleteAccount', ['only' => ['publish']]);
        $this->middleware('canGetMines', ['only' => ['mines']]);
        $this->middleware('canGetDelegations', ['only' => ['delegations']]);
        $this->middleware('canGetBookmarks', ['only' => ['bookmarks']]);
        $this->middleware('canApprove', ['only' => ['listApprove','toApprove','approve']]);
        $this->middleware('canCreate', ['only' => ['create']]);
        $this->middleware('canEdit', ['only' => ['edit', 'store', 'update']]);
        $this->middleware('canUpdateCoefficient', ['only' => ['updateCoefficient']]);
        $this->middleware('canUpdateQuantities', ['only' => ['updateQuantities']]);
        $this->middleware('canDestroy', ['only' => ['destroy']]);
        $this->middleware('canRefund', ['only' => ['refund']]);
        $this->middleware('canRenew', ['only' => ['renew']]);
        $this->middleware('canBackToTop', ['only' => ['backToTop']]);
        $this->middleware('canHighlight', ['only' => ['highlight']]);
        $this->middleware('stopAnalytics', ['only' => ['toApprove']]);
        $this->middleware('picturesExists', ['only' => ['store']]);
        $this->vimeoManager = $vimeoManager;
    }


    /************************************************************************
     * PUBLIC PARTS: RETURN LISTS
     *************************************************************************/

    /**
     * Get List advert or range Price
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
//    public function index(Request $request)
//    {
//        return response()->json(AdvertUtils::getList($request));
//    }

    /**
     *
     * return list of mines adverts
     *
     * @return \Illuminate\Http\JsonResponse
     */
//    public function mines()
//    {
//        return response()->json(AdvertUtils::getPersonnalList());
//    }

    /**
     *
     * Return list of advert on delegation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delegations(Request $request)
    {
        return response()->json(AdvertUtils::getDelegationList($request));
    }

    /**
     *
     * return a list of bookmarks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookmarks()
    {
        return response()->json(AdvertUtils::getBookmarkList());

    }

    /**
     *
     * Route to Get the list of Advert to Approve
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listApprove() {
        $adverts = Advert::onlyPublish()->paginate(config('runtime.advertsPerPage'));

        $adverts->load('user');
        $adverts->load('pictures');
        $adverts->load('category');

        $response = [];
        foreach ($adverts as $advert){
            $ancestors = $advert->category->getAncestors();
            $ancestors->add($advert->category);
            $advert->setBreadCrumb($ancestors);
            $advert->setGlobalDiscount();
            $advert->setTotalPriceMargin();

            $response[$advert->id] = $advert;
        }

        $pagination = json_decode($adverts->toJson(), true);
        unset($pagination['data']);
        return response()->json(['adverts' => $response, 'paginate' => $pagination]);
    }

    /**
     *
     * return list of mines adverts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHighlight()
    {
        $adverts = Advert::highlight()->get();
        $loadCompleteAdverts = AdvertUtils::loadCompleteAdverts($adverts);
        return response()->json(['adverts'=> $loadCompleteAdverts[0]]);
    }


    /************************************************************************
     * PUBLIC PARTS: RETURN INFOS
     *************************************************************************/

    /**
     *
     * Public function for get Cost of Advert on create
     *
     * @param $nbPictures
     * @param $isUrgent
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function cost($nbPictures, $isUrgent, Request $request) {

        $originalAdvert = null;
        if($request->filled('isEditOf') && filter_var($request->isEditOf, FILTER_VALIDATE_INT)){
            $originalAdvert = Advert::find((int)$request->isEditOf);
        }

        if(isset($nbPictures) && isset($isUrgent) && is_numeric($nbPictures)){
            return response()->json(CostUtils::getCost([
                'nbPictures' => (int)$nbPictures,
                'isUrgent' => filter_var($isUrgent, FILTER_VALIDATE_BOOLEAN),
                'haveVideo' => session()->has('videoId'),
                'isEditOf' => !is_null($originalAdvert) ? $originalAdvert : null
            ]));
        } else {
            return response('error', 500);
        }
    }

    /**
     *
     * Return List of Type advert
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListType()  {
        $list = Database::getEnumValues('adverts', 'type');
        $transList = [];
        foreach ($list as $key => $item) {
            $transList[$key] = trans('strings.view_advert_list_type_' . $item);
        }
        return response()->json($transList);
    }


    /************************************************************************
     * PUBLIC PARTS: RETURN VIEWS
     *************************************************************************/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ip=config('runtime.ip');
        $geolocType = 1;
        $zoomMap = 11;
        $user = null;
        $lat = null;
        $lng = null;
        auth()->check() ? $user = auth()->user() : null;

        if(count(old())==0){
            session(['uploadPictures' => []]);
            session()->forget('videoId');

            if($user) {
                $lat = $user->latitude;
                $lng = $user->longitude;
            }
        }
        $route = route('advert.store');
        return view('advert.createOrEdit', compact('route', 'ip', 'geolocType', 'zoomMap', 'user', 'lat', 'lng'));
    }

    /**
     *
     * Display the specified resource.
     *
     * @param $slug
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($slug, Request $request)
    {
        $advert = Advert::where('slug', '=', $slug)->first();
        if($advert && $advert->isValid && $advert->online_at != null && Carbon::parse($advert->online_at)->isPast()) {
                //+1 Views to advert
                $advert->timestamps = false;
                $advert->views = $advert->views + 1;
                $advert->save();
                //+1 view to stats
                $stats = Stats::latest()->first();
                $stats->totalNewViews = $stats->totalNewViews + 1;
                $stats->save();

                //Eager Loading but not for user to not pass user to VueJS Props
                $advert->load('bookmarks');
                $advert->load('pictures');
                $advert->load('category');
                $advert->load(['user' => function ($query) {
                    $query->select(['id','role','phone', 'compagnyName']);
                }]);

                //bypass user phone on delegation
                if($advert->is_delegation){
                    $advert->user->phone = env('DELEGATE_PHONE');
                }

                $ancestors = $advert->category->getAncestors();
                $ancestors->add($advert->category);
                $advert->setBreadCrumb($ancestors);
                $advert->setBookmarkCount();
                $advert->setGlobalDiscount();
                $advert->setTotalPriceMargin();
                auth()->check() ? $advert->setIsOnEdit() : null;
                return view('advert.show', compact('advert'));
        } elseif ($advert && $request->isXmlHttpRequest() && PrivilegesUtils::canShowXmlAdvert($advert)) {
            return response()->json(['advert' => $advert]);
        } else {
            return redirect(route('home'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editAdvert = Advert::find($id);
        $editAdvert->load('pictures');
        if($editAdvert && PrivilegesUtils::canEditAdvert($editAdvert)){

            //1°) set session upload pictures
            if(count(old())==0){
                $alreadyUploadPictures = [];
                foreach ($editAdvert->pictures as $picture){
                    $alreadyUploadPictures[] = [
                        'hashName' => $picture->hashName,
                        'thumbUrl' => $picture->thumbUrl,
                        'normalUrl' => $picture->normalUrl,
                    ];
                }
                session(['uploadPictures' => $alreadyUploadPictures]);
            }

            //2°) set session videoId
            !is_null($editAdvert->video_id) &&  count(old())==0 ?  session(['videoId' => $editAdvert->video_id]): null;

            //3°)
            $ip=config('runtime.ip');
            $geolocType = 1;
            $zoomMap = 11;
            $user = auth()->user();

            $route = route('advert.update', ['id' => $editAdvert->id]);
            return view('advert.createOrEdit', compact('route', 'ip', 'geolocType', 'zoomMap', 'user', 'editAdvert'));
        }
    }

    /**
     *
     * Return view of list Advert to Approve
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toApprove() {
        return view('advert.approve');
    }

    /**
     *
     * Return View for Payment
     *
     * @param $invoiceId
     * @return AdvertController|\Illuminate\Http\RedirectResponse
     */
    public function reviewForPayment($invoiceId) {

        if(!UserUtils::haveCompleteAccount()){
            return redirect()->back()->withErrors(trans('strings.middleware_complete_account'));
        }
        $invoice = Invoice::find($invoiceId);
        if(!is_null($invoice) && PrivilegesUtils::canReviewInvoice($invoice) && !$invoice->transaction_id) {
            $invoice->tva_customer = $invoice->user->registrationNumber;
            $invoice->tva_requester = $invoice->user->requesterNumber;
            $invoice->vatIdentifier = $invoice->user->vatIdentifier;

            if(!$invoice->user->requesterNumber){
                $invoice->tva_requester = env('TVA_REQUESTER_COUNTRY_CODE').env('TVA_REQUESTER_VAT_NUMBER');
            }

            if(!$invoice->vatIdentifier || strtoupper(substr($invoice->tva_customer,0,2))=='FR'){
                $invoice->tvaSubject = true;
            } else {
                $invoice->tvaSubject = false;
            }
            $invoice->save();

            $mode = PaymentUtils::getMode();
            $clientToken = PaymentUtils::getBraintreeClientToken();

            return view('advert.reviewForPayment', compact('invoice', 'title', 'mode', 'clientToken'));
        }
        return redirect(route('home'));
    }


    /************************************************************************
     * PUBLIC PARTS: PROCESS
     *************************************************************************/

    /**
     *
     * Store a newly created resource in storage.
     *
     * @param StoreAdvertRequest $request
     * @return AdvertController|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreAdvertRequest $request)
    {
        $category = Category::find($request->category);
        $completeGeoLoc = json_decode($request->completegeoloc);
        $parsedAddressComponent = GeoManager::parseAddressComponent($completeGeoLoc[0]->address_components);
        $editAdvert = null;
        $isEditOf = $request->filled('isEditOf') && filter_var($request->isEditOf, FILTER_VALIDATE_INT) && (int)$request->isEditOf > 0 && ($editAdvert = Advert::find((int)$request->isEditOf)) ? (int)$request->isEditOf : null;
        if($category) {
            try {
                $advert = new Advert();
                $advert->user_id = $isEditOf ?  $editAdvert->user_id : auth()->user()->id;
                $advert->category_id = $request->category;
                $advert->type = $request->type;
                $advert->title = $request->title;
                $advert->manu_ref = $request->manu_ref;
                $advert->description = $request->description;
                $advert->latitude = $request->lat;
                $advert->longitude = $request->lng;
                foreach (GeoManager::$accurate as $key){
                    key_exists($key, $parsedAddressComponent) ? $advert->$key = $parsedAddressComponent[$key] : $advert->$key = null;
                }
                $advert->geoloc = $request->geoloc;
                $advert->mainPicture = $request->main_picture;
                $advert->currency=$request->currency;
                $advert->totalQuantity=$request->total_quantity;
                $advert->discount_on_total=filter_var($request->discount_on_total, FILTER_VALIDATE_FLOAT);
                $advert->lotMiniQuantity=$request->lot_mini_quantity;
                $advert->isUrgent=filter_var($request->is_urgent, FILTER_VALIDATE_BOOLEAN);
                $advert->isNegociated=filter_var($request->is_negociated, FILTER_VALIDATE_BOOLEAN);
                $advert->isEditOf=$isEditOf;
                $isEditOf ? $advert->is_delegation = true : null;
                $isEditOf ? $advert->price_coefficient = $editAdvert->price_coefficient : null;
                $isEditOf ? $advert->price_coefficient_total = $editAdvert->price_coefficient_total : null;
                $persistent=null;
                if(session()->has('videoId')){
                    $advert->video_id = session('videoId');
                    //sert a garder trace de l'upload de la video en cas d'echec de l'enregistrement l'advertManager purgera cette video de vimeo
                    $persistent = Persistent::where('key', '=', 'videoId')->where('value', '=', session('videoId'))->first();
                }

                $advert->price = MoneyUtils::setPriceWithoutDecimal($request->filled('price') ? strval(filter_var($request->price, FILTER_VALIDATE_FLOAT)) : '0',$request->currency);

                //Cost for picture is based on final file number
                $cost = CostUtils::getCost([
                    'nbPictures' => count(session('uploadPictures')),
                    'isUrgent' => $advert->isUrgent,
                    'haveVideo' => session()->has('videoId'),
                    'isEditOf' => $editAdvert
                ]);

                DB::beginTransaction();
                $advert->save();
                //create invoice
                $invoice = null;
                if($cost > 0){
                    $invoice = Invoice::create([
                        'user_id' => $advert->user->id,
                        'advert_id' => !is_null($isEditOf) ? $isEditOf : $advert->id,
                        'state' => !is_null($isEditOf) ? Invoice::STATE_EDIT :  Invoice::STATE_CREATION,
                        'cost' => $cost,
                        'options' => CostUtils::setOptions([
                            'nbPictures' => count(session('uploadPictures')),
                            'isUrgent' => $advert->isUrgent,
                            'haveVideo' => session()->has('videoId'),
                            'isEditOf' => $editAdvert
                        ])
                    ]);
                    $advert->nextUrl = route('advert.reviewForPayment', ['invoiceId' => $invoice->id]);
                } else {
                    $advert->nextUrl = route('advert.publish', ['id' => $advert->id]);
                }

                $advert->save();

                if($persistent){
                    $persistent->delete();
                }
                foreach (session('uploadPictures') as $uploadPicture){
                    $picture = new Picture();
                    $picture->hashName = $uploadPicture['hashName'];
                    $picture->thumbUrl = $uploadPicture['thumbUrl'];
                    $picture->normalUrl = $uploadPicture['normalUrl'];
                    $advert->pictures()->save($picture);
                    $picture->save();

                    $persistent = Persistent::where([
                        'key' => 'picture',
                        'value' => $uploadPicture['thumbUrl']
                    ])->first();
                    $persistent ? $persistent->delete() : null;

                    $persistent = Persistent::where([
                        'key' => 'picture',
                        'value' => $uploadPicture['normalUrl']
                    ])->first();
                    $persistent ? $persistent->delete() : null;
                }

                DB::commit();
                if(PrivilegesUtils::isCostFree()){
                    return redirect(route('advert.publish', ['id' =>$advert->id]));
                } else {
                    return redirect(route('user.completeAccount', ['id' =>$advert->id, 'infoCost' => $cost]));
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withInput()->withErrors(trans('strings.view_all_error_saving_message'));
            }
        } else {
            return redirect()->back()->withInput()->withErrors(trans('strings.view_all_error_saving_message'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return AdvertController|\Illuminate\Http\RedirectResponse
     */
    public function update(StoreAdvertRequest $request, $id)
    {
        $editAdvert = Advert::find($id);
        if($editAdvert && PrivilegesUtils::canEditAdvert($editAdvert)){
            $request->request->add(['isEditOf'=> $id]);
            return $this->store($request);
        } else {
            return redirect()->back()->withErrors(trans('strings.view_all_error_saving_message'));
        }
    }

    /**
     *
     * Update coefficient for delegation advert
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateCoefficient($id, Request $request) {
        $coefficient = $request->coefficient;
        $coefficient_total = $request->coefficient_total;
        if((int)$coefficient >= 0 && (int)$coefficient_total >= 0){
            $advert = Advert::find($id);
            if($advert && PrivilegesUtils::canUpdateCoefficientAdvert($advert)){
                $advert->price_coefficient = $coefficient/100;
                $advert->price_coefficient_total = $coefficient_total/100;
                $advert->save();
                return response('ok', 200);
            }
        }
        return response('error', 500);
    }

    /**
     *
     * Update quantities
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateQuantities($id, Request $request) {
        $totalQuantity = $request->totalQuantity;
        $lotMiniQuantity = $request->lotMiniQuantity;
        if((int)$lotMiniQuantity > 0 && (int)$totalQuantity > 0 && (int)$totalQuantity >= (int)$lotMiniQuantity){
            $advert = Advert::find($id);
            if($advert && PrivilegesUtils::canUpdateQuantitiesAdvert($advert)){
                $advert->lotMiniQuantity = (int)$lotMiniQuantity;
                $advert->totalQuantity = (int)$totalQuantity;
                $advert->save();
                return response('ok', 200);
            }
        }
        return response('error', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $advert = Advert::find($id);
        if($advert && PrivilegesUtils::canDestroyAdvert($advert)) {
            $advert->delete();
            session()->flash('info', trans('strings.view_advert_show_delete_success'));
            return response(route('home'), 200);
        } else {
            return response('error',500);
        }
    }

    /**
     *
     * Route to Approve Adverts after publish. Capture Payment, and set online_at attribute
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function approve(Request $request) {
        $approveList = $request->all();
        try {
            foreach ($approveList as $key=>$value) {
                $this->approveAdvert($key, $value['isApprove'], $value['lotMiniQuantity'], $value['priceCoefficient'], $value['priceCoefficientTotal'], $value['disapproveReason']);
            }
        } catch (\Exception $e) {
            return response(trans('strings.view_advert_approve_error') . '(' . $e->getMessage() . ')', 409);
        }
        return response('ok',200);
    }

    /**
     * Refund user for invoice id.
     *
     * @param  int  $id
     * @return AdvertController|\Illuminate\Http\RedirectResponse
     */
    public function refund($id)
    {
        $refundInvoice = Invoice::find($id);
        if(!is_null($refundInvoice) && !($refundInvoice->refunded) && $refundInvoice->captured && !($refundInvoice->voided)){
            try {
                PaymentUtils::refundTransaction($refundInvoice);
            } catch (\Exception $e) {
                return response(trans('strings.view_manage_invoice_refund_fail'), 409);
            }
            try {
                InvoiceUtils::createInvoiceByInvoice($refundInvoice);
                $this->notifyRefund($refundInvoice);
            } catch (\Exception $e) {
                return response(trans('strings.view_manage_invoice_sent_fail'), 409);
            }
            return response('ok',200);
        } else {
            return response(trans('strings.view_all_error_saving_message'), 500);
        }
    }

    /**
     *
     * redirect to the next step
     *
     * @param $id
     * @return AdvertController|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function nextStep($id) {
        $advert = Advert::withTrashed()->find($id);
        if($advert) {
            return redirect($advert->nextUrl);
        } else {
            return redirect(route('home'))->withErrors(trans('strings.view_all_error_patch_message'));
        }
    }

    /**
     *
     * Renew an Advert
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function renew($id)
    {
        $advert = Advert::withTrashed()->find($id);
        if($advert && $advert->isEligibleForRenew && PrivilegesUtils::canRenewAdvert($advert)){
            try {
                //Create Invoice
                $cost = CostUtils::getCost(['isRenew' => true]);
                if($cost>0){
                    DB::beginTransaction();
                    $invoice = Invoice::create([
                        'user_id' => $advert->user->id,
                        'advert_id' => $advert->id,
                        'state' => Invoice::STATE_RENEW,
                        'cost' => $cost,
                        'options' => CostUtils::setOptions(['isRenew' => true])
                    ]);
                    $advert->nextUrl = route('advert.reviewForPayment', ['invoiceId' => $invoice->id]);
                    $advert->save();
                    DB::commit();
                    return redirect(route('user.completeAccount', ['id' => $advert->id, 'title' => trans('strings.option_isRenew_name'), 'infoCost' => $cost]));
                } else {
                    $this->advertUpdate($advert, Invoice::STATE_RENEW, null);
                    return redirect()->back()->with('success', trans('strings.noPayment_renew_success', ['date' => LocaleUtils::getTransDate($advert->ended_at)]));
                }
            } catch (\Exception $e) {
                return redirect(route('home'))->withErrors(trans('strings.view_all_error_saving_message'));
            }
        } else {
            return redirect(route('mines'))->withErrors(trans('strings.view_all_ineligible_advert_action'));
        }
    }

    /**
     *
     * Back To Top an Advert
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function backToTop($id)
    {
        $advert = Advert::find($id);
        if($advert && $advert->isEligibleForBackToTop() && PrivilegesUtils::canBackToTopAdvert($advert)) {
            try {
                //Create Invoice
                $cost = CostUtils::getCost(['isBackToTop' => true]);
                if($cost>0){
                    DB::beginTransaction();
                    $invoice = Invoice::create([
                        'user_id' => $advert->user->id,
                        'advert_id' => $advert->id,
                        'state' => Invoice::STATE_BACKTOTOP,
                        'cost' => $cost,
                        'options' => CostUtils::setOptions(['isBackToTop' => true])
                    ]);
                    $advert->nextUrl = route('advert.reviewForPayment', ['invoiceId' => $invoice->id]);
                    $advert->save();
                    DB::commit();
                    return redirect(route('user.completeAccount', ['id' => $advert->id, 'title' => trans('strings.option_isBackToTop_name'), 'infoCost' => $cost]));
                } else {
                    $this->advertUpdate($advert, Invoice::STATE_BACKTOTOP, null);
                    return redirect()->back()->with('success', trans('strings.noPayment_backToTop_success'));
                }
            } catch (\Exception $e) {
                return redirect(route('home'))->withErrors(trans('strings.view_all_error_saving_message'));
            }
        } else {
            return redirect(route('mines'))->withErrors(trans('strings.view_all_ineligible_advert_action'));
        }
    }

    /**
     *
     * Highlight an Advert
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function highlight($id)
    {
        $advert = Advert::find($id);
        if($advert && $advert->isEligibleForHighlight && PrivilegesUtils::canHighlightAdvert($advert)) {
            try {
                //Create Invoice
                $cost = CostUtils::getCost(['isHighlight' => true]);
                if($cost>0){
                    DB::beginTransaction();
                    $invoice = Invoice::create([
                        'user_id' => $advert->user->id,
                        'advert_id' => $advert->id,
                        'state' => Invoice::STATE_HIGHLIGHT,
                        'cost' => $cost,
                        'options' => CostUtils::setOptions(['isHighlight' => true])
                    ]);
                    $advert->nextUrl = route('advert.reviewForPayment', ['invoiceId' => $invoice->id]);
                    $advert->save();
                    DB::commit();
                    return redirect(route('user.completeAccount', ['id' => $advert->id, 'title' => trans('strings.option_isHighlight_name'), 'infoCost' => $cost]));
                } else {
                    $this->advertUpdate($advert, Invoice::STATE_HIGHLIGHT, null);
                    return redirect()->back()->with('success', trans('strings.noPayment_highlight_success'));
                }
            } catch (\Exception $e) {
                return redirect(route('home'))->withErrors(trans('strings.view_all_error_saving_message'));
            }
        } else {
            return redirect(route('mines'))->withErrors(trans('strings.view_all_ineligible_advert_action'));
        }
    }

    /**
     *
     * Route to publish Advert without Payment
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function publish(Request $request, $id){
        $advert = Advert::find($id);
        $advert->load('user');
        if($advert && PrivilegesUtils::canPublishAdvert($advert) && $advert->invoices()->count()==0 && $advert->isPublish==false){
            $this->advertPublish($advert, $request);
            return redirect(route('home'))->with('success', trans('strings.advert_create_success'));
        } else {
            return redirect(route('home'))->withErrors(trans('strings.view_all_error_saving_message'));
        }
    }

    /**
     *
     * Get the nonce token to call Brantree_transaction to get authorization
     * Send route to go if authorize (route validateOrder)
     *
     * @param Request $request
     * @param $invoiceId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function noncePost(Request $request, $invoiceId) {
        $routeToGo = false;
        if($request->filled('nonce') && $request->filled('deviceData') && filter_var($invoiceId, FILTER_VALIDATE_INT)){
            $routeToGo = PaymentUtils::getAuthorization($request->nonce, $request->deviceData,  $invoiceId);
        }
        if($routeToGo){
            return response()->json($routeToGo);
        } else {
            return response('ko', 402);
        }
    }

    /**
     *
     *
     *
     * @param Request $request
     * @param $invoiceId
     * @param $token
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function validateOrder(Request $request, $invoiceId, $token) {
        $invoice = Invoice::find($invoiceId);
        if($invoice && $invoice->user->id == auth()->user()->id && $token == md5($invoice->transaction_id)){
            return $this->setAdvertState($invoice, $request);
        }

        return redirect(route('advert.reviewForPayment', ['invoiceId' => $invoiceId]))
            ->withErrors('err2: ' . trans('strings.payment_all_error'));
    }

    /**
     *
     * Message from customer to seller
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function sendMail(Request $request) {
        //valid Request here because not possible back in pop-up message
        $validator = Validator::make($request->all(), [
            'message' => 'required|min:' . config('db_limits.messages.minLength') . '|max:' . config('db_limits.messages.maxLength'),
            'email' => 'required|email',
            'name' => 'required|min:' . config('db_limits.users.minName'),
            'phone' => 'max:'.config('db_limits.users.maxPhone'),
            'compagnyName' => 'max:'.config('db_limits.users.maxCompagnyName') //min test in store because not min in pop-up
        ]);

        if ($validator->fails()) {
            return response(trans('strings.mail_customerToSeller_send_error'), 409);
        }



        if(auth()->check()){
            $sender = auth()->user();
        } else {
            //test if User exist
            $sender = User::whereMail($request->email)->first();
        }

        if($sender){
            $sender->name = $request->name;
            if($request->filled('phone') && strlen($request->phone) > 0){
                $sender->phone = $request->phone;
            }
            if($request->filled('compagnyName') && strlen($request->compagnyName) >= config('db_limits.users.minCompagnyName')){
                $sender->compagnyName = $request->compagnyName;
            }
            $sender->save();
        } else {
            //create anonymous user
            UserUtils::createOrUpdateAnonymous($request->email, $request->name, $request->phone, $request->compagnyName, false);
        }
        //Test compagnyName mini car pas de test dans message car pas required
        $advert = Advert::find($request->id);
        if($advert){
            $senderMail = $request->email;
            $senderName = ucfirst($request->name);
            $senderPhone = $request->phone;
            $senderCompagnyName = $request->compagnyName;
            $message = $request->message;

            //ByPass if delegation
            if($advert->is_delegation){
                $advert->load('user');
                $recipients = User::whereIn('role', PrivilegesUtils::canReceiveBypassAdvertMessage())->get();
                foreach ($recipients as $recipient){
                    $recipient->notify(new CustomerContactIntermediary($advert, $senderName, $senderMail, $message, $senderPhone, $senderCompagnyName));
                }
            } else {
                $recipient = $advert->user;
                $recipient->notify(new CustomerContactSeller($advert, $senderName, $senderMail, $message, $senderPhone, $senderCompagnyName));
            }

            return response('ok', 200);
        } else {
            return response(trans('strings.mail_customerToSeller_send_error'), 409);
        }
    }

    /**
     *
     * Message from customer to admin
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function report(Request $request) {
        //valid Request here because not possible back in pop-up message
        $validator = Validator::make($request->all(), [
            'message' => 'required|min:' . config('db_limits.messages.minLength') . '|max:' . config('db_limits.messages.maxLength'),
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response(trans('strings.mail_report_send_error'), 409);
        }

        $advert = Advert::find($request->id);
        if($advert){
            $senderMail = $request->email;
            $message = $request->message;

            $recipients = User::whereIn('role', PrivilegesUtils::canReceiveReport())->get();
            foreach ($recipients as $recipient){
                $recipient->notify(new ReportAdvert($advert, $senderMail, $message));
            }
            return response('ok', 200);
        } else {
            return response(trans('strings.mail_report_send_error'), 409);
        }
    }




    /************************************************************************
     * PRIVATE PARTS
     *************************************************************************/

    /**
     *
     * private function to approve One advert
     *
     * @param $key
     * @param $isApproved
     * @return null
     * @throws \Exception
     */
    private function approveAdvert($key, $isApproved, $lotMiniQuantity, $priceCoefficient=null, $priceCoefficientTotal=null, $disapproveReason=null) {
        if($isApproved != null) {
            $advert = Advert::find($key);
            $invoice = null;
            $state = is_null($advert->isEditOf) ? Invoice::STATE_CREATION : Invoice::STATE_EDIT;
            if($advert && is_null($advert->isValid)) {
                $advert->price_coefficient = $priceCoefficient;
                $advert->price_coefficient_total = $priceCoefficientTotal;
                $advert->lotMiniQuantity = (int)$lotMiniQuantity;
                //Get latest invoice with create state
                if($state == Invoice::STATE_CREATION){
                    $invoice = $advert->invoices()
                        ->where('state', Invoice::STATE_CREATION)
                        ->latest()->first();
                } else {
                    $invoice = Advert::find($advert->isEditOf)->invoices()
                        ->where('state', Invoice::STATE_EDIT)
                        ->latest()->first();
                }

                $redirectOptions = ['withRedirect'=>false];
                $this->autoProcess($advert, $state, $invoice,(boolean)$isApproved,$disapproveReason,$redirectOptions,true);
            }
        }
    }

    /**
     *
     * Common Save Result Of payment and Process to publish Advert before validation
     *
     * @param Invoice $invoice
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @internal param Payment $payment
     * @internal param Advert $advert
     */
    private function setAdvertState(Invoice $invoice, Request $request) {

        if($invoice->state == Invoice::STATE_CREATION){
            $advert = $invoice->advert;
            $this->advertPublish($advert, $request);
            //stop process here, admin approve is required
            return redirect(route('home'))->with('success', trans('strings.payment_success'));

        } elseif ($invoice->state == Invoice::STATE_RENEW) {
            $advert = Advert::withTrashed()->find($invoice->advert_id);
            $redirectOption = [
                'withRedirect' => true,
                'successMessage' => trans('strings.payment_renew_success', ['date' => LocaleUtils::getTransDate($advert->calcRenewEndedAt())]),
                'errorMessage' => trans('strings.view_advert_renew_error')
            ];
            return $this->autoProcess($advert, $invoice->state,$invoice,null,null, $redirectOption,true);

        } elseif ($invoice->state == Invoice::STATE_BACKTOTOP) {
            $advert = Advert::find($invoice->advert_id);
            $redirectOption = [
                'withRedirect' => true,
                'successMessage' => trans('strings.payment_backToTop_success'),
                'errorMessage' => trans('strings.view_advert_backToTop_error')
            ];
            return $this->autoProcess($advert, $invoice->state,$invoice,null,null, $redirectOption,true);

        } elseif ($invoice->state == Invoice::STATE_HIGHLIGHT) {
            $advert = Advert::find($invoice->advert_id);
            $redirectOption = [
                'withRedirect' => true,
                'successMessage' => trans('strings.payment_highlight_success'),
                'errorMessage' => trans('strings.payment_highlight_error')
            ];
            return $this->autoProcess($advert, $invoice->state,$invoice,null,null, $redirectOption,true);

        } elseif ($invoice->state == Invoice::STATE_EDIT) {
            $advert = Advert::where('isEditOf', $invoice->advert->id)->latest()->first();
            $this->advertPublish($advert, $request);
            //stop process here, admin approve is required
            return redirect(route('home'))->with('success', trans('strings.payment_success'));
        }
    }

    /**
     *
     * Common Publish Process (with or without Payment)
     *
     * @param Advert $advert
     * @param Request $request
     * @param null $authorizationId
     */
    private function advertPublish(Advert $advert, Request $request){
        $advert->isPublish = true;
        $advert->save();

        session(['uploadPictures' => []]);
        session()->has('videoId') ? session()->forget('videoId'): null;
        $request->session()->flash('clear', true);
    }

    /**
     *
     *
     *
     * @param Advert $advert
     * @param int $state
     * @param Invoice $invoice
     * @param bool|null $isApproved
     * @param null|string $disapproveReason
     * @param array $redirectOptions
     * @param bool $withNotification
     * @return AdvertController|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function autoProcess(Advert $advert, int $state, Invoice $invoice=null, bool $isApproved=null, string $disapproveReason=null, Array $redirectOptions, bool $withNotification=true) {
        try {

            //1 Capture + stats or Void
            if(!is_null($invoice)){
                if(is_null($isApproved) || $isApproved==true){
                    $result = PaymentUtils::captureTransaction($invoice);
                    if($result === true){
                        $this->updateStats($invoice);
                    } else {
                        throw new \Exception('payment capture fails');
                    }
                } else {
                    PaymentUtils::voidTransaction($invoice);
                }
            } else {
                $isApproved==true ? $this->updateStats(null) : null;
            }

            //2 Update Advert
            $this->advertUpdate($advert, $state, $isApproved);

            //3 Notify Event
            if($withNotification && PrivilegesUtils::canReceiveBypassAutoprocessNotify($advert)){
                if($state==Invoice::STATE_EDIT && !is_null($advert->isEditOf)) {
                    $originalAdvert = Advert::find($advert->isEditOf);
                    !is_null($originalAdvert) ? $this->notifyEvent($originalAdvert, $state, $invoice, $disapproveReason, $isApproved) : null;
                } else {
                    $this->notifyEvent($advert, $state, $invoice, $disapproveReason, $isApproved);
                }
            }


            //4 Redirect
            return $redirectOptions['withRedirect'] == true ? redirect(route('home'))->with('success', $redirectOptions['successMessage']) : null;

        } catch (\Exception $e) {
            $this->notifyError($advert);
            if ($redirectOptions['withRedirect'] == true) {
                return  redirect(route('home'))->withErrors($redirectOptions['errorMessage']);
            } else {
                throw new \Exception('autoprocess error: ' . $e->getMessage());
            }

        }
    }

    /**
     *
     * Update advert when a capture paiement for option is done
     *
     * @param Advert $advert
     * @param $typeUpdate
     */
    private function advertUpdate(Advert $advert, $state, $isApproved=null) {
        if($state == Invoice::STATE_CREATION){
            $advert->isValid=(boolean)$isApproved;
            if($isApproved==true){
                $advert->online_at = Carbon::now();
                $advert->setEndedAt();
            }
            $advert->save();
        } elseif ($state == Invoice::STATE_RENEW){
            if(!is_null($advert->deleted_at)){
                DB::beginTransaction();
                $advert->online_at = Carbon::now();
                $advert->setEndedAt();
                $advert->deleted_at = null;
                $advert->lastObsoleteMail = null;
                $advert->save();
                $advertPictures = Picture::findByAdvertIdWithTrashed($advert->id)->get();
                foreach ($advertPictures as $picture){
                    $picture->deleted_at = null;
                    $picture->save();
                }
                DB::commit();
            } else {
                $advert->online_at = Carbon::now();
                $advert->ended_at = Carbon::parse($advert->ended_at)->addDay(env('ADVERT_LIFE_TIME'));
                $advert->save();
            }
        }  elseif ($state == Invoice::STATE_BACKTOTOP){
                $advert->online_at = Carbon::now();
                $advert->save();
        } elseif ($state == Invoice::STATE_HIGHLIGHT) {
            $advert->highlight_until = Carbon::now()->addHours(env('HIGHLIGHT_HOURS_DURATION'));
            $advert->save();
        } elseif ($state == Invoice::STATE_EDIT) {
            $originalAdvert = Advert::find($advert->isEditOf);
            if($isApproved==true){
                //new attributes
                $originalAdvert->category_id = $advert->category_id;
                $originalAdvert->title = $advert->title;
                $originalAdvert->slug = $advert->slug;
                $originalAdvert->description = $advert->description;
                $originalAdvert->price = $advert->originalPrice;
                $originalAdvert->price_coefficient = $advert->price_coefficient;
                $originalAdvert->price_coefficient_total = $advert->price_coefficient_total;
                $originalAdvert->discount_on_total = $advert->discount_on_total;
                $originalAdvert->currency = $advert->currency;
                $originalAdvert->latitude = $advert->latitude;
                $originalAdvert->longitude = $advert->longitude;
                $originalAdvert->geoloc = $advert->geoloc;
                foreach (GeoManager::$accurate as $key){
                    isset($advert->$key) && isset($originalAdvert->$key) ? $originalAdvert->$key = $advert->$key : null;
                }
                $originalAdvert->totalQuantity = $advert->totalQuantity;
                $originalAdvert->lotMiniQuantity = $advert->lotMiniQuantity;
                $originalAdvert->isUrgent = $advert->isUrgent;
                $originalAdvert->isNegociated = $advert->isNegociated;
                $originalAdvert->manu_ref = $advert->manu_ref;
                $originalAdvert->mainPicture = $advert->mainPicture;
                $originalAdvert->online_at = Carbon::now();

                !is_null($originalAdvert->video_id) && $originalAdvert->video_id != $advert->video_id ? $this->vimeoManager->request('/videos/'.$originalAdvert->video_id,[],'DELETE') : null;
                $originalAdvert->video_id = $advert->video_id;

                DB::beginTransaction();
                $originalAdvert->save();

                //delete old pictures
                foreach ($originalAdvert->pictures as $picture){
                    //using persistent process for deleting pictures
                    Persistent::create([
                        'key' => 'picture',
                        'value' => $picture->thumbUrl
                    ]);

                    Persistent::create([
                        'key' => 'picture',
                        'value' => $picture->normalUrl
                    ]);

                    $picture->forceDelete();
                }

                //add new pictures
                foreach ($advert->pictures as $newPicture){
                    $picture = new Picture();
                    $picture->hashName = $newPicture->hashName;
                    $picture->thumbUrl = $newPicture->thumbUrl;
                    $picture->normalUrl = $newPicture->normalUrl;
                    $originalAdvert->pictures()->save($picture);
                    $picture->save();
                }
            } else {
                //delete edit pictures and video
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

                    $picture->forceDelete();
                }
                !is_null($advert->video_id) && $advert->video_id != $originalAdvert->video_id? $this->vimeoManager->request('/videos/'.$advert->video_id,[],'DELETE') : null;
            }

            //bye bye edit advert
            $advert->forceDelete();
            DB::commit();
        }
    }

    /**
     *
     * Update stats
     *
     * @param Invoice|null $invoice
     */
    private function updateStats(Invoice $invoice = null){
        $stats = Stats::latest()->first();
        is_null($stats) ? $stats = Stats::create([]) : null;
        if(!is_null($invoice) && $invoice->cost > 0){
            $stats->totalNewCostAdverts = $stats->totalNewCostAdverts + 1;
            $stats->totalCosts = $stats->totalCosts + $invoice->cost;
        } else {
            $stats->totalNewFreeAdverts = $stats->totalNewFreeAdverts + 1;
        }
        $stats->save();
    }

    /**
     *
     * Notify an event on advert
     *
     * @param Advert $advert
     * @param Invoice|null $invoice
     * @param null $disapproveReason
     * @return Advert|null
     */
    private function notifyEvent(Advert $advert, $state, Invoice $invoice = null, $disapproveReason = null, $isApproved = false) {

        //Send Invoice to Accountant
        if(!is_null($invoice) && file_exists($invoice->filePath)){
            $recipients = User::whereIn('role', PrivilegesUtils::canReceiveInvoice())->get();
            $senderMail = env('SERVICE_MAIL_FROM');
            $senderName = ucfirst(config('app.name'));
            foreach ($recipients as $recipient){
                $recipient->notify(new InvoicePdf($advert, $invoice, $senderName, $senderMail));
            }
        }

        $recipient = $advert->user;
        $senderMail = env('SERVICE_MAIL_FROM');
        $senderName = ucfirst(config('app.name'));
        if(($state==Invoice::STATE_CREATION || $state==Invoice::STATE_EDIT) && $isApproved) {
            $recipient->notify(new AdvertApprove($advert, $state, $invoice, $senderName, $senderMail));
            return null;
        } elseif ($state==Invoice::STATE_RENEW) {
            $recipient->notify(new AdvertRenew($advert, $invoice));
            return $advert;
        } elseif ($state==Invoice::STATE_BACKTOTOP) {
            $recipient->notify(new AdvertBackToTop($advert, $invoice));
            return $advert;
        } elseif ($state==Invoice::STATE_HIGHLIGHT) {
            $recipient->notify(new AdvertHighlight($advert, $invoice));
            return $advert;
        } else {
            $recipient->notify(new AdvertNotApprove($advert, $state, $invoice, $senderName, $senderMail, $disapproveReason));
            return null;
        }
    }

    /**
     *
     * Notify for a refund
     *
     * @param Advert $advert
     * @param Invoice|null $invoice
     * @param null $disapproveReason
     * @return Advert|null
     */
    private function notifyRefund(Invoice $invoice) {

        //Send Refund Invoice to Accountant
        if(!is_null($invoice) && file_exists($invoice->filePath)){
            $recipients = User::whereIn('role', PrivilegesUtils::canReceiveInvoice())->get();
            $senderMail = env('SERVICE_MAIL_FROM');
            $senderName = ucfirst(config('app.name'));
            foreach ($recipients as $recipient){
                $recipient->notify(new InvoiceRefundPdf($invoice, $senderName, $senderMail, $recipient->role));
            }
        }

        $recipient = $invoice->user;
        $senderMail = env('SERVICE_MAIL_FROM');
        $senderName = ucfirst(config('app.name'));
        $recipient->notify(new InvoiceRefundPdf($invoice, $senderName, $senderMail, $recipient->role));
        return null;
    }

    /**
     *
     * Notify Admin off error on advert process
     *
     * @param Advert $advert
     */
    private function notifyError(Advert $advert) {
        $recipients = User::whereIn('role', PrivilegesUtils::canReceiveError())->get();
        $senderMail = env('SERVICE_MAIL_FROM');
        $senderName = ucfirst(config('app.name'));
        $message = trans('strings.mail_apperror_renew_line', ['advertNumber' => $advert->id, 'mailClient' => $advert->user->email]);
        foreach ($recipients as $recipient){
            $recipient->notify(new ReportAppError($message, $senderName, $senderMail));
        }
    }
}
