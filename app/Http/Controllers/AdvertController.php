<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Anonymous;
use App\Bookmark;
use App\Category;
use App\Common\CategoryUtils;
use App\Common\CostUtils;
use App\Common\GeoManager;
use App\Common\InvoiceUtils;
use App\Common\LocaleUtils;
use App\Common\MoneyUtils;
use App\Common\PicturesManager;
use App\Common\UserUtils;
use App\Http\Requests\CreditCardRequest;
use App\Http\Requests\StoreAdvertRequest;
use App\Invoice;
use App\Notifications\AdvertApprove;
use App\Notifications\AdvertNotApprove;
use App\Notifications\AdvertRenew;
use App\Notifications\CustomerContactSeller;
use App\Notifications\InvoicePdf;
use App\Notifications\ReportAdvert;
use App\Notifications\ReportAppError;
use App\Persistent;
use App\Picture;
use App\Stats;
use App\User;
use Carbon\Carbon;
use Codeheures\LaravelUtils\Traits\Tools\Currencies;
use Codeheures\LaravelUtils\Traits\Tools\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;
use PayPal\Api\Amount;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use Vinkla\Vimeo\VimeoManager;

class AdvertController extends Controller
{
    private $pictureManager;
    private $vimeoManager;
    private $_api_context;
    Const PAYPAL = 0;
    Const CARD = 1;

    /**
     * AdvertController constructor.
     * @param PicturesManager $picturesManager
     */
    public function __construct(PicturesManager $picturesManager, VimeoManager $vimeoManager) {
        $this->middleware('auth', ['except' => ['index', 'show', 'getListType', 'sendMail', 'report']]);
        $this->middleware('isEmailConfirmed', ['except' => ['index', 'show', 'getListType', 'sendMail', 'report', 'bookmarks', 'unbookmarks']]);
        $this->middleware('haveCompleteAccount', ['only' => ['publish']]);
        $this->middleware('isAdminUser', ['only' => ['delegations', 'toApprove','listApprove', 'approve', 'updateCoefficient']]);
        $this->pictureManager  = $picturesManager;
        $this->vimeoManager = $vimeoManager;

        // setup PayPal api context
        if(auth()->check() && !(env('PAYPAL_SANDBOX')=='true')){
            $paypal_conf = config('paypal');
        } else {
            $paypal_conf = config('paypal_sandbox');
        }
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
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
    public function index(Request $request)
    {
        //Init vars
        $isSearchRequest = ($request->has('search') && strlen($request->search) >= 3);
        $isSearchResults = ($request->has('resultsFor') && strlen($request->resultsFor) >= 3);
        $isRangePricesOnly = ($request->has('priceOnly') && filter_var($request->priceOnly, FILTER_VALIDATE_BOOLEAN) == true);
        $isUrgentOnly = ($request->has('isUrgent') && filter_var($request->isUrgent, FILTER_VALIDATE_BOOLEAN) == true );
        $isNegociatedOnly = ($request->has('isNegociated') && filter_var($request->isNegociated, FILTER_VALIDATE_BOOLEAN) == true );

        //only valid advert & online_at < now
        $adverts = Advert::where('isValid', true)->where('online_at', '<', Carbon::now());

        //where currency
        $currency = null;
        $currencySymbol = '';

        if($request->has('categoryId') && $request->categoryId != 0){
            $ids = CategoryUtils::getListSubTree($request->categoryId);
            if($ids){
                $adverts = $adverts->whereIn('category_id', $ids);
            }
        }

        //if location
        foreach (GeoManager::$accurate as $item){
            if($request->has($item) && $request->$item != null){
                $adverts = $adverts->where($item, '=', $request->$item);
            }
        }

        //Currencies
        $cloneAdverts = clone($adverts);
        $currenciesList = $cloneAdverts->select('currency')->groupBy('currency')->get();
        $nbCurrencies = $currenciesList->count();

        $finalCurrencyList = [];
        if($nbCurrencies==1){
            //$currency = $currenciesList->first()->currency;
            $currencySymbol = Currencies::getSymbolByCurrencyCode($currenciesList->first()->currency, config('runtime.locale'));
        } else {
            $currenciesListArray = $currenciesList->pluck('currency');
            foreach ($currenciesListArray as $currencyCode) {
                $finalCurrencyList[] = [
                    'code' => $currencyCode,
                    'symbol' =>   Currencies::getSymbolByCurrencyCode($currencyCode, config('runtime.locale'))
                ];
            }
        }

        if($request->has('currency') && Currencies::isAvailableCurrency($request->currency)) {
            $currency = $request->currency;
            $currencySymbol = Currencies::getSymbolByCurrencyCode($currency, config('runtime.locale'));
            $adverts = $adverts->where('currency', $currency);
        }

        //Set min & max prices only if not $isSearchRequest
        if(!$isSearchRequest) {
            $minAllPrice = $adverts->min('price_margin_decimal');
            $maxAllPrice = $adverts->max('price_margin_decimal');
            $minAllQuantity = $adverts->min('totalQuantity');
            $maxAllQuantity = $adverts->max('totalQuantity');

            if (!$minAllPrice) {
                $minAllPrice = 0;
            }

            if (!$maxAllPrice){
                $maxAllPrice = 0;
            }

            if(!$minAllQuantity){
                $minAllQuantity = 0;
            }

            if(!$maxAllQuantity){
                $maxAllQuantity = 0;
            }
        }


        //STOP REQUEST HERE IF only RANGE PRICES
        if($isRangePricesOnly){
            return response()->json([
                'minPrice'=> $minAllPrice,
                'maxPrice' => $maxAllPrice,
                'currenciesList' => $finalCurrencyList,
                'currency' => $currency,
                'currencySymbol' => $currencySymbol,
                'minQuantity'=> $minAllQuantity,
                'maxQuantity' => $maxAllQuantity,
            ]);
        }

        //if urgent
        if($isUrgentOnly){
            $adverts = $adverts->where('isUrgent', true);
        }

        //if urgent
        if($isNegociatedOnly){
            $adverts = $adverts->where('isNegociated', true);
        }

        //if range price
        if($request->has('minPrice') && $request->has('maxPrice') ){
            $adverts = $adverts->where('price_margin_decimal', '>=', $request->minPrice)->where('price_margin_decimal', '<=', $request->maxPrice);
        }

        //if range quantity
        if($request->has('minQuantity') && $request->has('maxQuantity') ){
            $minQuantity = ($request->minQuantity);
            $maxQuantity = ($request->maxQuantity);
            $adverts = $adverts->where('totalQuantity', '>=', $minQuantity)->where('totalQuantity', '<=', $maxQuantity);
        }

        if($isSearchRequest){
            $search = $request->search;
            $adverts = $adverts->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' .$search .'%')
                    ->orWhere('description', 'LIKE', '%' .$search .'%')
                    ->orWhere('manu_ref', 'LIKE', '%' .$search .'%');
            });
        }

        //search results before range price
        if($isSearchResults){
            $search = $request->resultsFor;
            $adverts = $adverts->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' .$search .'%')
                    ->orWhere('description', 'LIKE', '%' .$search .'%')
                    ->orWhere('manu_ref', 'LIKE', '%' .$search .'%');
            });
        }

        if($isSearchRequest) {
            $countSearch = $adverts->count();
            $adverts = $adverts->orderBy('online_at', 'desc')->limit(config('runtime.maxNumberOfSearchResults'))->get();
        } else {
            $adverts = $adverts->orderBy('online_at', 'desc')->paginate(config('runtime.advertsPerPage'));
        }
        $loadCompleteAdverts = $this->loadCompleteAdverts($adverts);

        if($isSearchRequest){
            $action = [
                'url' => '#',
                'text' => trans_choice('strings.form_input_search_view_all', $countSearch, ['nb' => $countSearch])
            ];
            //choisir $loadCompleteAdverts[1] pour trier les resultats par categories
            return response()->json(['results'=> $loadCompleteAdverts[0], 'action' => $action]);
        } else {
            return response()->json(['adverts'=> $loadCompleteAdverts[0], 'minPrice'=> $minAllPrice, 'maxPrice' => $maxAllPrice]);
        }
    }

    /**
     *
     * return list of mines adverts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function mines()
    {
        $adverts = Advert::mines()->paginate(config('runtime.advertsPerPage'));
        $loadCompleteAdverts = $this->loadCompleteAdverts($adverts);
        return response()->json(['adverts'=> $loadCompleteAdverts[0]]);

    }

    /**
     *
     * Return list of advert on delegation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delegations()
    {
        $adverts = Advert::delegations()->paginate(config('runtime.advertsPerPage'));
        $loadCompleteAdverts = $this->loadCompleteAdverts($adverts);
        return response()->json(['adverts'=> $loadCompleteAdverts[0]]);

    }

    /**
     *
     * return a list of bookmarks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookmarks()
    {
        $bookmarks = Bookmark::mines()->get()->pluck('advert_id')->toArray();
        $adverts = Advert::inBookmarks($bookmarks)->paginate(config('runtime.advertsPerPage'));

        $adverts->load('pictures');
        $adverts->load('category');
        $tempoStore =[];
        $resultsByCat = [];
        $user = auth()->user();
        $user->load('bookmarks');

        foreach ($adverts as $advert){
            if(!array_key_exists($advert->category->id,$tempoStore)){
                $ancestors = $advert->category->getAncestors();
                $ancestors->add($advert->category);
                $tempoStore[$advert->category->id] = $ancestors;
                $resultsByCat[$advert->category->id]['results'] = [];
            } else {
                $ancestors = $tempoStore[$advert->category->id];
            }
            $advert->setBreadCrumb($ancestors);
            $resultsByCat[$advert->category->id]['results'][] = $advert;
            $resultsByCat[$advert->category->id]['name'] = $advert->getConstructBreadCrumb();
            $advert->setIsUserBookmark($user->haveBookmark($advert->id));
            $advert->setBookmarkCount();
        }

        return response()->json(['adverts'=> $adverts]);

    }

    /**
     *
     * Route to Get the list of Advert to Approve
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listApprove() {
        $adverts = Advert::onlyPublish()->get();
        $adverts->load('user');
        $adverts->load('pictures');
        $adverts->load('category');

        $response = [];
        foreach ($adverts as $advert){
            $response[$advert->id] = $advert;
        }
        return response()->json($response);
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
    public function cost($nbPictures, $isUrgent) {
        if(isset($nbPictures) && isset($isUrgent) && is_numeric($nbPictures)){
            return response()->json(CostUtils::getCost((int)$nbPictures,filter_var($isUrgent, FILTER_VALIDATE_BOOLEAN), false, session()->has('videoId')));
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
        auth()->check() ? $user = auth()->user() : null;
        return view('advert.create', compact('ip', 'geolocType', 'zoomMap', 'user'));
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
        if($advert && $advert->isValid && $advert->online_at != null && Carbon::parse($advert->online_at)->isPast(Carbon::now())) {
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
                    $query->select(['id','role','phone']);
                }]);
                $ancestors = $advert->category->getAncestors();
                $ancestors->add($advert->category);
                $advert->setBreadCrumb($ancestors);
                $advert->setBookmarkCount();
                return view('advert.show', compact('advert'));
        } elseif ($advert && $request->isXmlHttpRequest() && auth()->check() && ($advert->user->id == auth()->user()->id || auth()->user()->role == 'admin')) {
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
        //
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
        if($invoice->user->id == auth()->user()->id && !$invoice->authorization) {
            $invoice->tva_customer = $invoice->user->registrationNumber;
            $invoice->tva_requester = $invoice->user->requesterNumber;
            $invoice->vatIdentifier = $invoice->user->vatIdentifier;

            if(!$invoice->user->requesterNumber){
                $invoice->tva_requester = env('TVA_REQUESTER_COUNTRY_CODE').env('TVA_REQUESTER_VAT_NUMBER');
            }

            if(!$invoice->vatIdentifier || substr($invoice->tva_customer,0,2)=='FR'){
                $invoice->tvaSubject = true;
            } else {
                $invoice->tvaSubject = false;
            }
            $invoice->save();
            $listCardTypes = config('paypal_cards.list');
            return view('advert.reviewForPayment', compact('invoice', 'listCardTypes'));
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
        if($category) {
            try {
                $advert = new Advert();
                $advert->user_id = auth()->user()->id;
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
                $advert->lotMiniQuantity=$request->lot_mini_quantity;
                $advert->isUrgent=filter_var($request->is_urgent, FILTER_VALIDATE_BOOLEAN);
                $advert->isNegociated=filter_var($request->is_negociated, FILTER_VALIDATE_BOOLEAN);
                $persistent=null;
                if(session()->has('videoId')){
                    $advert->video_id = session('videoId');
                    //sert a garder trace de l'upload de la video en cas d'echec de l'enregistrement l'advertManager purgera cette video de vimeo
                    $persistent = Persistent::where('key', '=', 'videoId')->where('value', '=', session('videoId'))->first();
                }

                $advert->price = MoneyUtils::setPriceWithoutDecimal($request->has('price') ? $request->price : '0',$request->currency);

                //pass picture from local tempo to final and count them
                $results = $this->pictureManager->storeLocalFinal();

                //Cost for picture is based on final file number
                $cost = CostUtils::getCost(count($results)/2, $advert->isUrgent, false, session()->has('videoId'));


                DB::beginTransaction();
                $advert->save();
                //create invoice
                $invoice = null;
                if($cost > 0){
                    $invoice = Invoice::create([
                        'user_id' => $advert->user->id,
                        'advert_id' => $advert->id,
                        'state' => Invoice::STATE_CREATION,
                        'cost' => $cost,
                        'options' => $this->setOptions(count($results)/2, $advert->isUrgent, null, session()->has('videoId'))
                    ]);
                    $advert->nextUrl = route('advert.reviewForPayment', ['invoiceId' => $invoice->id]);
                } else {
                    $advert->nextUrl = route('advert.publish', ['id' => $advert->id]);
                }

                $advert->save();

                if($persistent){
                    $persistent->delete();
                }
                foreach ($results as $result){
                    $picture = new Picture();
                    $picture->hashName = $result['hashName'];
                    $picture->path = $result['path'];
                    $picture->disk = $result['disk'];
                    $picture->isThumb = $result['isThumb'];
                    $advert->pictures()->save($picture);
                    $picture->save();
                }

                DB::commit();
                if(auth()->user()->isDelegation){
                    return redirect(route('advert.publish', ['id' =>$advert->id]));
                } else {
                    return redirect(route('user.completeAccount', ['id' =>$advert->id]));
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     *
     * Update coefficient for delegation advert
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateCoefficient(Request $request) {
        $coefficient = $request->coefficient;
        if((int)$coefficient >= 0){
            $advert = Advert::find($request->id);
            if($advert){
                $advert->price_coefficient = $coefficient/100;
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
        if($advert && (auth()->user()->id === $advert->user->id || auth()->user()->role == 'admin')){
            if (auth()->user()->role == 'admin') {
                $advert->isValid = false;
                $advert->save();
            }
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
                $this->approveAdvert($key, $value['isApprove'], $value['priceCoefficient'], $value['disapproveReason']);
            }
        } catch (\Exception $e) {
            return response(trans('strings.view_advert_approve_error'), 500);
        }
        return response('ok',200);
    }

    /**
     *
     * redirect to the next step
     *
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
        if($advert && auth()->user()->id === $advert->user->id && $advert->isEligibleForRenew){
            try {
                //Create Invoice
                DB::beginTransaction();
                $invoice = Invoice::create([
                    'user_id' => $advert->user->id,
                    'advert_id' => $advert->id,
                    'state' => Invoice::STATE_RENEW,
                    'cost' => CostUtils::getCost(null, null, true, false),
                    'options' => $this->setOptions(null, null, true, false)
                ]);
                $advert->nextUrl = route('advert.reviewForPayment', ['invoiceId' => $invoice->id]);
                $advert->save();
                DB::commit();
                return redirect(route('user.completeAccount', ['id' => $advert->id, 'title' => trans('strings.option_isRenew_name')]));
            } catch (\Exception $e) {
                return redirect(route('home'))->withErrors(trans('strings.view_all_error_saving_message'));
            }
        } else {
            return redirect(route('home'));
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
        if($advert && $advert->user->id == auth()->user()->id && $advert->invoices()->count()==0 && $advert->isPublish==false){
            $this->advertPublish($advert, $request, null, null);
            return redirect(route('home'))->with('success', trans('strings.advert_create_success'));
        } else {
            return redirect(route('home'))->withErrors(trans('strings.view_all_error_patch_message'));
        }
    }

    /**
     *
     * Route when choice Pay by paypal
     *
     * @param $invoiceId
     * @return Controller|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function payByPaypal($invoiceId) {
        return $this->advertPayment($invoiceId, self::PAYPAL);
    }

    /**
     *
     * Route when choice Pay by Card
     *
     * @param $invoiceId
     * @param CreditCardRequest $request
     * @return Controller|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function payByCard($invoiceId, CreditCardRequest $request) {
        return $this->advertPayment($invoiceId, self::CARD, $request);
    }

    /**
     *
     * Get Result of Paypal Payment
     *
     * @param $invoiceId
     * @param $success
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function paypalStatus($invoiceId, $success, Request $request) {
        $invoice = Invoice::find($invoiceId);

        if(is_null($invoice)){
            return redirect(route('home'))
                ->withErrors('err3: ' . trans('strings.payment_all_error'));
        }
        if($success == 'true') {
            // Get the payment ID before session clear
            $session_payment_id = session('paypal_payment_id');
            $payment_id = $request->get('paymentId');

            //test sessionId = Request return paymentId
            if($session_payment_id != $payment_id) {
                session()->forget('paypal_payment_id');
                return redirect(route('advert.reviewForPayment', ['invoiceId' => $invoiceId]))
                    ->withErrors('err1: ' . trans('strings.payment_all_error'));
            }

            // clear the session payment ID
            session()->forget('paypal_payment_id');

            if (empty($request->input('PayerID')) || empty($request->input('token'))) {
                return redirect(route('advert.reviewForPayment', ['invoiceId' => $invoiceId]))
                    ->withErrors('err2: ' . trans('strings.payment_all_error'));
            }

            try {
                $payment = Payment::get($payment_id, $this->_api_context);

                // PaymentExecution object includes information necessary
                // to execute a PayPal account payment.
                // The payer_id is added to the request query parameters
                // when the user is redirected from paypal back to your site
                $execution = new PaymentExecution();
                $execution->setPayerId($request->input('PayerID'));
                //Execute the payment
                $result = $payment->execute($execution, $this->_api_context);
            } catch (\Exception $e) {
                return redirect(route('advert.reviewForPayment', ['invoiceId' => $invoiceId]))
                    ->withErrors('err4: ' . trans('strings.payment_all_error'));
            }

            if ($result->getState() == 'approved') { // payment made
                return $this->saveApprovedAuthorization($payment, $invoice, $request);
            }

            return redirect(route('advert.reviewForPayment', ['invoiceId' => $invoiceId]))
                ->withErrors('err2: ' . trans('strings.payment_all_error'));
        } else {
            return redirect(route('advert.reviewForPayment', ['invoiceId' => $invoiceId]))
                ->withErrors('err6: ' . trans('strings.payment_all_error'));
        }
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
            if(!$sender){
                //test if anonymous exist
                $sender = Anonymous::whereMail($request->email)->first();
            }
        }
        if($sender){
            //complete infos
            $sender->name = $request->name;
            if($request->has('phone') && $request->phone != ''){
                $sender->phone = $request->phone;
            }
            if($request->has('compagnyName') && $request->compagnyName != '' && strlen($request->compagnyName) >= config('db_limits.users.minCompagnyName')){
                $sender->compagnyName = $request->compagnyName;
            }
            $sender->save();
        } else {
            //create anonymous user
            UserUtils::createAnonymous($request->email, $request->name, $request->phone, $request->compagnyName);
        }
        //Test compagnyName mini car pas de test dans message car pas required
        $advert = Advert::find($request->id);
        if($advert){
            $senderMail = $request->email;
            $senderName = ucfirst($request->name);
            $senderPhone = $request->phone;
            $senderCompagnyName = $request->compagnyName;
            $message = $request->message;
            $recipient = $advert->user;

            $recipient->notify(new CustomerContactSeller($advert, $senderName, $senderMail, $message, $senderPhone, $senderCompagnyName));
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

            $recipients = User::whereRole('admin')->get();
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
     * return adverts with loads
     *
     * @param $adverts
     * @return array
     */
    private function loadCompleteAdverts($adverts) {
        $adverts->load('pictures');
        $adverts->load('category');
        $tempoStore =[];
        $resultsByCat = [];
        if(auth()->check()){
            $user = auth()->user();
            $user->load('bookmarks');
        }
        foreach ($adverts as $advert){
            if(!array_key_exists($advert->category->id,$tempoStore)){
                $ancestors = $advert->category->getAncestors();
                $ancestors->add($advert->category);
                $tempoStore[$advert->category->id] = $ancestors;
                $resultsByCat[$advert->category->id]['results'] = [];
            } else {
                $ancestors = $tempoStore[$advert->category->id];
            }
            $advert->setBreadCrumb($ancestors);
            $resultsByCat[$advert->category->id]['results'][] = $advert;
            $resultsByCat[$advert->category->id]['name'] = $advert->getConstructBreadCrumb();
            if($advert->isUserOwner) {
                $advert->setBookmarkCount();
            } elseif (auth()->check()){
                $advert->setIsUserBookmark($user->haveBookmark($advert->id));
            } else {
                $advert->setIsUserBookmark(false);
            }
        }
        return [$adverts,$resultsByCat];
    }

    /**
     *
     * Set options for Invoices
     *
     * @param $nbPictures
     * @param $isUrgent
     * @param null $isRenew
     * @param null $haveVideo
     * @return array
     */
    private function setOptions($nbPictures, $isUrgent, $isRenew=null, $haveVideo=null) {
        $options = [];
        if($nbPictures > config('runtime.nbFreePictures')){
            $options['payedPictures'] = [
                'name' => trans('strings.option_payedPicture_name'),
                'quantity' => $nbPictures - config('runtime.nbFreePictures'),
                'cost' => CostUtils::getCostPictures($nbPictures),
                'tva' => env('TVA')
            ];
        }
        if($isUrgent){
            $options['isUrgent'] = [
                'name' => trans('strings.option_isUrgent_name'),
                'quantity' => 1,
                'cost' => CostUtils::getCostIsUrgent($isUrgent),
                'tva' => env('TVA')
            ];
        }
        if($isRenew){
            $options['isRenew'] = [
                'name' => trans('strings.option_isRenew_name'),
                'quantity' => 1,
                'cost' => CostUtils::getCostIsRenew($isRenew),
                'tva' => env('TVA')
            ];
        }
        if($haveVideo){
            $options['haveVideo'] = [
                'name' => trans('strings.option_haveVideo_name'),
                'quantity' => 1,
                'cost' => CostUtils::getCostVideo($haveVideo),
                'tva' => env('TVA')
            ];
        }

        foreach ($options as $key => $option){
            $options[$key]['tvaVal'] = (int)($options[$key]['cost']*$options[$key]['tva']/100);
            $options[$key]['costTTC'] = $options[$key]['cost']+$options[$key]['tvaVal'];
        }

        return $options;
    }

    /**
     *
     * private function to approve One advert
     *
     * @param $key
     * @param $isApproved
     * @return null
     * @throws \Exception
     */
    private function approveAdvert($key, $isApproved, $priceCoefficient=null, $disapproveReason=null) {
        if($isApproved != null) {
            $advert = Advert::find($key);
            if($advert && is_null($advert->isValid)) {
                $advert->price_coefficient = $priceCoefficient;
                $invoiceFileName = null;
                //Get latest invoice with create state
                $invoice = null;
                $invoice = $advert->invoices()
                    ->where('state', Invoice::STATE_CREATION)
                    ->latest()->first();
                //IF EXIST AUTHORIZATION PAYMENT
                if(!is_null($invoice)
                    && $invoice->authorization
                    && $invoice->authorization != '')
                {
                    try {
                        if((boolean)$isApproved==true){
                            //First try to setting some params to advert before capture payment
                            $advert->online_at = Carbon::now();
                            $advert->setEndedAt();

                            $this->capturePayment($invoice);
                            $advert->isValid=(boolean)$isApproved;

                            $stats = Stats::latest()->first();
                            if($invoice->cost > 0){
                                $stats->totalNewCostAdverts = $stats->totalNewCostAdverts + 1;
                                $stats->totalCosts = $stats->totalCosts + $invoice->cost;
                            } else {
                                $stats->totalNewFreeAdverts = $stats->totalNewFreeAdverts + 1;
                            }

                            DB::beginTransaction();
                            //get invoice number
                            $previousInvoice = Invoice::orderBy('invoice_number', 'DESC')->lockForUpdate()->first();
                            if($previousInvoice){
                                $next_invoice_number = $previousInvoice->invoice_number + 1;
                            } else {
                                $next_invoice_number = 1;
                            }

                            $invoice->invoice_number = $next_invoice_number;
                            $invoice->save();
                            $advert->save();
                            $stats->save();
                            DB::commit();
                            try {
                                $invoiceFileName = InvoiceUtils::createInvoiceByInvoice($invoice);
                            } catch (\Exception $e) {

                            }
                        } else {
                            //VOID PAYMENT
                            $this->voidPayment($invoice);
                            $advert->isValid=(boolean)$isApproved;
                            $advert->save();
                        }
                    } catch (\Exception $e) {
                        $recipients = User::where('role', '=', 'admin')->get();
                        $senderMail = env('SERVICE_MAIL_FROM');
                        $senderName = ucfirst(config('app.name'));
                        $message = trans('strings.mail_apperror_approve_line', ['advertNumber' => $advert->id, 'mailClient' => $advert->user->email]);
                        foreach ($recipients as $recipient){
                            $recipient->notify(new ReportAppError($message, $senderName, $senderMail));
                        }
                        throw new \Exception($e);
                    }
                } else {
                    $advert->isValid=(boolean)$isApproved;
                    if((boolean)$isApproved){
                        $advert->online_at = Carbon::now();
                        $advert->setEndedAt();
                        $stats = Stats::latest()->first();
                        $stats->totalNewFreeAdverts = $stats->totalNewFreeAdverts + 1;
                        DB::beginTransaction();
                        $advert->save();
                        $stats->save();
                        DB::commit();
                    } else {
                        $advert->save();
                    }
                }

                if($invoiceFileName){
                    $recipients = User::where('role', '=', 'admin')->get();
                    $senderMail = env('SERVICE_MAIL_FROM');
                    $senderName = ucfirst(config('app.name'));
                    foreach ($recipients as $recipient){
                        $recipient->notify(new InvoicePdf($advert, $invoice, $senderName, $senderMail));
                    }
                }

                $recipient = $advert->user;
                $senderMail = env('SERVICE_MAIL_FROM');
                $senderName = ucfirst(config('app.name'));
                if($advert->isValid && (is_null($invoice) || $invoice->state==Invoice::STATE_CREATION)) {
                    $recipient->notify(new AdvertApprove($advert, $invoice, $senderName, $senderMail));
                    return null;
                } elseif ($advert->isValid && $invoice->state==Invoice::STATE_RENEW) {
                    $recipient->notify(new AdvertRenew($advert, $invoice));
                    return $advert;
                } else {
                    $recipient->notify(new AdvertNotApprove($advert, $invoice, $senderName, $senderMail, $disapproveReason));
                    return null;
                }

            }
        }
    }

    /**
     *
     * Process Payment: Set Invoice and Get Authorization number if Card or rediret To paypal
     *
     * @param $invoiceId
     * @param $type
     * @param null $request
     * @return AdvertController|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function advertPayment($invoiceId, $type, $request=null) {
        $invoice = Invoice::find($invoiceId);

        if($invoice
            && $invoice->user->id == auth()->user()->id
            && $invoice->cost > 0
            && !$invoice->authorization
        ){
            $invoice->load('user');

            //set invoice
            $invoice->method = Invoice::PAYPAL;
            $invoice->save();


            //set $tva, $price, $productName
            $tva = 0;
            $productName = trans_choice('strings.payment_paypal_generic_product_name', count($invoice->options), ['nb' => count($invoice->options)]);
            $i = 0;
            foreach ($invoice->options as $option){
                if($invoice->tvaSubject){
                    $tva = $tva + $option['tvaVal'];
                }
                $productName .= ($i > 0 ? ' + #' : ' #') . $option['quantity'] . ' ' . $option['name'];
                $i++;
            }
            $tva = round($tva/100,2);
            $price = round($invoice->cost/100,2);

            //Paypal ShippingAddress
            $geoCodes = json_decode($invoice->user->geoloc);
            $street_components = null;
            $components = [];
            if(is_array($geoCodes) && count($geoCodes)>0){
                foreach ($geoCodes as $geoCode){
                    if($geoCode->types[0]=='street_address'){
                        $street_components = $geoCode->address_components;
                        foreach ($street_components as $component){
                            if($component->types[0] != 'country'){
                                $components[$component->types[0]] = $component->long_name;
                            } else {
                                $components[$component->types[0]] = $component->short_name;
                            }
                        }
                    }
                }
            }

            $line1 = '';
            $line1 .= key_exists('street_number', $components) ?  $components['street_number'] : null;
            $line1 .= key_exists('route', $components) ?  ' ' . $components['route'] : null;

            $paypalAdress = new ShippingAddress();
            $paypalAdress->setLine1($line1);
            $paypalAdress->setLine2('');
            $paypalAdress->setPostalCode(key_exists('postal_code', $components) ?  $components['postal_code'] : '');
            $paypalAdress->setCity(key_exists('locality', $components) ?  $components['locality'] : '');
            $paypalAdress->setCountryCode(key_exists('country', $components) ?  $components['country'] : '');
            $paypalAdress->setRecipientName($invoice->user->compagnyName);


            //Payer Infos
            $payer = new Payer();
            if($type==self::PAYPAL){
                $payer->setPaymentMethod('paypal');
            } else {
                $card = new PaymentCard();
                $splitName = UserUtils::splitName($request->name);
                foreach ($splitName as $item){
                    if(!$item || $item=''){
                        return redirect(route('advert.reviewForPayment', ['invoiceId' => $invoiceId]))
                            ->withErrors(trans('strings.request_input_generic_error', ['name' => trans('strings.payment_card_name_label')]));
                    }
                }
                $card->setType(config('paypal_cards.list')[$request->card_type])
                    ->setNumber($request->card_no)
                    ->setExpireMonth($request->expiration_month)
                    ->setExpireYear($request->expiration_year)
                    ->setCvv2($request->cvc)
                    ->setFirstName($splitName['firstName'])
                    ->setBillingCountry($paypalAdress->getCountryCode())
                    ->setLastName($splitName['lastName']);
                $fi = new FundingInstrument();
                $fi->setPaymentCard($card);
                $payer->setPaymentMethod("credit_card")
                    ->setFundingInstruments(array($fi));
            }
            $payer->setPayerInfo(new PayerInfo());

            //ItemList
            $item_1 = new Item();
            $item_1->setName($productName) // item name
            ->setCurrency('EUR')
                ->setQuantity(1)
                ->setTax($tva)
                ->setPrice($price); // unit price

            // add item to list
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            $item_list->setShippingAddress($paypalAdress);

            //set details
            $details = new Details();
            $details->setShipping(0)
                ->setTax($tva)
                ->setSubtotal($price);

            //Paypal Amount
            $amount = new Amount();
            $amount->setCurrency('EUR')->setTotal($price+$tva)->setDetails($details);

            //Paypal Transaction
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription(trans('strings.payment_paypal_invoice_description', ['name' => config('app.name')]))
                ->setInvoiceNumber($invoice->id);


            //Paypal Payment
            $payment = new Payment();
            $payment->setIntent('authorize')
                ->setPayer($payer)
                ->setTransactions(array($transaction));
            if($type==self::PAYPAL){
                //Paypal Redirect URL
                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(route('advert.paypalStatus', ['invoiceId' => $invoice->id, 'success'=>'true']))
                    ->setCancelUrl(route('advert.paypalStatus', ['invoiceId' => $invoice->id, 'success'=>'false']));
                $payment->setRedirectUrls($redirect_urls);
            }


            try {
                $payment->create($this->_api_context);
            } catch (PayPalConnectionException $ex) {
                if (config('app.debug')) {
//                    echo "Exception: " . $ex->getMessage() . PHP_EOL;
//                    $err_data = json_decode($ex->getData(), true);
                    echo $ex->getCode(); // Prints the Error Code
                    echo $ex->getData(); // Prints the detailed error message
                    die($ex);
//                    exit;
                } else {
                    return redirect(route('advert.reviewForPayment', ['invoiceId' => $invoiceId]))
                        ->withErrors('err6: ' . trans('strings.payment_all_error'));
                }
            }


            if($type==self::PAYPAL){
                foreach($payment->getLinks() as $link) {
                    if($link->getRel() == 'approval_url') {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }
                // add payment ID to session
                session(['paypal_payment_id' => $payment->getId()]);
                if(isset($redirect_url)) {
                    // redirect to paypal
                    return redirect($redirect_url);
                }
            } elseif ($type == self::CARD) {
                if ($payment->getState() == 'approved') { // payment made
                    return $this->saveApprovedAuthorization($payment, $invoice, $request);
                }
            }
        }
        return redirect(route('home'))
            ->withErrors(trans('strings.payment_all_error'));
    }

    /**
     *
     * Capture a payment
     *
     * @param Invoice $invoice
     */
    private function capturePayment(Invoice $invoice) {
        //CAPTURE PAYMENT
        $authorizationId = $invoice->authorization;
        $authorization = Authorization::get($authorizationId, $this->_api_context);
        $amt = new Amount();
        $amt->setCurrency($authorization->getAmount()->getCurrency());
        $amt->setTotal($authorization->getAmount()->getTotal());
        $capture = new Capture();
        $capture->setAmount($amt);
        $getCapture = $authorization->capture($capture, $this->_api_context);
        $invoice->captureId = $getCapture->getId();
        $invoice->save();
    }

    /**
     *
     * Void a payment
     *
     * @param Invoice $invoice
     */
    private function voidPayment(Invoice $invoice) {
        $authorizationId = $invoice->authorization;
        $authorization = Authorization::get($authorizationId, $this->_api_context);
        $getVoid = $authorization->void($this->_api_context);
        $invoice->voidId = $getVoid->getId();
        $invoice->save();
    }

    /**
     *
     * Common Save Result Of payment and Process to publish Advert before validation
     *
     * @param Payment $payment
     * @param Advert $advert
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function saveApprovedAuthorization(Payment $payment, Invoice $invoice, Request $request) {
        $transactions = $payment->getTransactions();
        $relatedResources = $transactions[0]->getRelatedResources();
        $authorization = $relatedResources[0]->getAuthorization();
        $authorizationId = $authorization->getId();

        if($invoice->state == Invoice::STATE_CREATION){
            $advert = $invoice->advert;
            $this->advertPublish($advert, $request, $authorizationId, $invoice);
            return redirect(route('home'))
                ->with('success', trans('strings.payment_paypal_success'));
        } elseif ($invoice->state == Invoice::STATE_RENEW) {
            $advert = Advert::withTrashed()->find($invoice->advert_id);
            $this->advertPublish($advert, $request, $authorizationId, $invoice);
            try {
                $this->capturePayment($invoice);
                if(!is_null($advert->deleted_at)){
                    DB::beginTransaction();
                    $advert->online_at = Carbon::now();
                    $advert->setEndedAt();
                    $advert->deleted_at = null;
                    $advert->save();
                    $advertPictures = Picture::findByAdvertIdWithTrashed($advert->id)->get();
                    foreach ($advertPictures as $picture){
                        $picture->deleted_at = null;
                        $picture->save();
                    }
                    DB::commit();
                } else {
                    DB::beginTransaction();
                    $advert->online_at = Carbon::now();
                    $advert->ended_at = Carbon::parse($advert->ended_at)->addDay(env('ADVERT_LIFE_TIME'));
                    $advert->save();
                    DB::commit();
                }
                return redirect(route('home'))
                    ->with('success', trans('strings.payment_renew_success', ['date' => LocaleUtils::getTransDate($advert->ended_at)]));
            } catch (\Exception $e) {
                $recipients = User::where('role', '=', 'admin')->get();
                $senderMail = env('SERVICE_MAIL_FROM');
                $senderName = ucfirst(config('app.name'));
                $message = trans('strings.mail_apperror_renew_line', ['advertNumber' => $advert->id, 'mailClient' => $advert->user->email]);
                foreach ($recipients as $recipient){
                    $recipient->notify(new ReportAppError($message, $senderName, $senderMail));
                }
                return redirect(route('home'))
                    ->withErrors(trans('strings.view_advert_renew_error'));
            }
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
    private function advertPublish(Advert $advert, Request $request, $authorizationId=null, Invoice $invoice=null){
        DB::beginTransaction();
        $advert->isPublish = true;
        if(!is_null($authorizationId) && !is_null($invoice)){
            $invoice->authorization = $authorizationId;
            $invoice->save();
        }
        $advert->save();
        DB::commit();
        $this->pictureManager->purgeSessionLocalTempo();
        session()->has('videoId') ? session()->forget('videoId'): null;
        $request->session()->flash('clear', true);
    }
}
