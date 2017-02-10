<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Anonymous;
use App\Bookmark;
use App\Category;
use App\Common\CategoryUtils;
use App\Common\DBUtils;
use App\Common\GeoManager;
use App\Common\LocaleUtils;
use App\Common\MoneyUtils;
use App\Common\PicturesManager;
use App\Common\UserUtils;
use App\Http\Requests\CreditCardRequest;
use App\Http\Requests\StoreAdvertRequest;
use App\Invoice;
use App\Notifications\AdminAdvertApprove;
use App\Notifications\AdvertApprove;
use App\Notifications\AdvertNotApprove;
use App\Notifications\AdvertRenew;
use App\Notifications\CustomerContactSeller;
use App\Notifications\InvoicePdf;
use App\Notifications\ReportAdvert;
use App\Persistent;
use App\Picture;
use App\Stats;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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

    use CategoryUtils;
    use UserUtils;
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

        //only valid advert & online_at < now
        $adverts = Advert::where('isValid', true)->where('online_at', '<', Carbon::now());

        //where currency
        $currencySymbol = '';
        if($request->has('currency') && MoneyUtils::isAvailableCurrency($request->currency)){
            $currency = $request->currency;
            $currencySymbol = MoneyUtils::getSymbolByCurrencyCode($currency);
        } elseif($request->has('country')){
            $pseudoLocale = LocaleUtils::getFirstLocaleByCountryCode($request->country);
            if($pseudoLocale){
                $currency = MoneyUtils::getDefaultMoneyByLocale($pseudoLocale);
                $currencySymbol = MoneyUtils::getSymbolByCurrencyCode($currency);
            }
        } else {
            $currency = config('runtime.currency');
            $currencySymbol = MoneyUtils::getSymbolByCurrencyCode($currency);
        }
        if(!MoneyUtils::isAvailableCurrency($currency)){
            $currency = config('runtime.currency');
            $currencySymbol = MoneyUtils::getSymbolByCurrencyCode($currency);
        }
        $adverts = $adverts->where('currency', $currency);

        if($request->has('categoryId') && $request->categoryId != 0){
            $ids = CategoryUtils::getListSubTree($request->categoryId);
            if($ids){
                $adverts = $adverts->whereIn('category_id', $ids);
            }
        }

        //search results before range price
        if($isSearchResults){
            $search = $request->resultsFor;
            $adverts = $adverts->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' .$search .'%')
                    ->orWhere('description', 'LIKE', '%' .$search .'%');
            });
        }

        //Set min & max prices only if not $isSearchRequest
        if(!$isSearchRequest) {
            $minAllPrice = $adverts->min('price_margin');
            $maxAllPrice = $adverts->max('price_margin');
            $minAllQuantity = $adverts->min('totalQuantity');
            $maxAllQuantity = $adverts->max('totalQuantity');

            if ($minAllPrice) {
                $minAllPrice = MoneyUtils::getPriceWithDecimal($minAllPrice, $currency, false);
            } else {
                $minAllPrice = 0;
            }

            if ($maxAllPrice) {
                $maxAllPrice = MoneyUtils::getPriceWithDecimal($maxAllPrice, $currency, false);
            } else {
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
                'currencySymbol' => $currencySymbol,
                'minQuantity'=> $minAllQuantity,
                'maxQuantity' => $maxAllQuantity,
            ]);
        }

        //if urgent
        if($isUrgentOnly){
            $adverts = $adverts->where('isUrgent', true);
        }

        //if range price
        if($request->has('minPrice') && $request->has('maxPrice') ){
            $minPrice = MoneyUtils::setPriceWithoutDecimal($request->minPrice, $currency);
            $maxPrice = MoneyUtils::setPriceWithoutDecimal($request->maxPrice, $currency);
            $adverts = $adverts->where('price_margin', '>=', $minPrice)->where('price_margin', '<=', $maxPrice);
        }

        //if range quantity
        if($request->has('minQuantity') && $request->has('maxQuantity') ){
            $minQuantity = ($request->minQuantity);
            $maxQuantity = ($request->maxQuantity);
            $adverts = $adverts->where('totalQuantity', '>=', $minQuantity)->where('totalQuantity', '<=', $maxQuantity);
        }

        //if location
        foreach (GeoManager::$accurate as $item){
            if($request->has($item) && $request->$item != null){
                $adverts = $adverts->where($item, '=', $request->$item);
            }
        }

        if($isSearchRequest){
            $search = $request->search;
            $adverts = $adverts->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' .$search .'%')
                    ->orWhere('description', 'LIKE', '%' .$search .'%');
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
            return response()->json(['results'=> $loadCompleteAdverts[1], 'action' => $action]);
        } else {
            return response()->json(['adverts'=> $loadCompleteAdverts[0], 'minPrice'=> $minAllPrice, 'maxPrice' => $maxAllPrice]);
        }
    }

    public function mines()
    {
        $adverts = Advert::mines()->paginate(config('runtime.advertsPerPage'));
        $loadCompleteAdverts = $this->loadCompleteAdverts($adverts);
        return response()->json(['adverts'=> $loadCompleteAdverts[0]]);

    }

    public function delegations()
    {
        $adverts = Advert::delegations()->paginate(config('runtime.advertsPerPage'));
        $loadCompleteAdverts = $this->loadCompleteAdverts($adverts);
        return response()->json(['adverts'=> $loadCompleteAdverts[0]]);

    }

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
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
                $persistent=null;
                if(session()->has('videoId')){
                    $advert->video_id = session('videoId');
                    $persistent = Persistent::where('key', '=', 'videoId')->where('value', '=', session('videoId'))->first();
                }

                $advert->price = MoneyUtils::setPriceWithoutDecimal($request->price,$request->currency);

                //pass picture from local tempo to final and count them
                $results = $this->pictureManager->storeLocalFinal();

                //Cost for picture is based on final file number
                $cost = $this->getCost(count($results)/2, $advert->isUrgent, false, session()->has('videoId'));


                DB::beginTransaction();
                //create invoice
                if($cost > 0 && !$advert->invoice){
                    $invoice = Invoice::create([
                        'user_id' => $advert->user->id,
                        //'invoice_number' => $next_invoice_number,
                        //'method' => Invoice::PAYPAL,
                        'cost' => $cost,
                        'options' => $this->setOptions(count($results)/2, $advert->isUrgent, null, session()->has('videoId'))
                    ]);

                    $advert->invoice()->associate($invoice);
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

    private function setOptions($nbPictures, $isUrgent, $isRenew=null, $haveVideo=null) {
        $options = [];
        if($nbPictures > config('runtime.nbFreePictures')){
            $options['payedPictures'] = [
                'name' => trans('strings.option_payedPicture_name'),
                'quantity' => $nbPictures - config('runtime.nbFreePictures'),
                'cost' => $this->getCostPictures($nbPictures),
                'tva' => env('TVA')
            ];
        }
        if($isUrgent){
            $options['isUrgent'] = [
                'name' => trans('strings.option_isUrgent_name'),
                'quantity' => 1,
                'cost' => $this->getCostIsUrgent($isUrgent),
                'tva' => env('TVA')
            ];
        }
        if($isRenew){
            $options['isRenew'] = [
                'name' => trans('strings.option_isRenew_name'),
                'quantity' => 1,
                'cost' => $this->getCostIsRenew($isRenew),
                'tva' => env('TVA')
            ];
        }
        if($haveVideo){
            $options['haveVideo'] = [
                'name' => trans('strings.option_haveVideo_name'),
                'quantity' => 1,
                'cost' => $this->getCostVideo($haveVideo),
                'tva' => env('TVA')
            ];
        }

        foreach ($options as $key => $option){
            $options[$key]['tvaVal'] = (int)($options[$key]['cost']*$options[$key]['tva']/100);
            $options[$key]['costTTC'] = $options[$key]['cost']+$options[$key]['tvaVal'];
        }

        return $options;
    }

    private function getCostPictures($nbPictures){
        if($nbPictures > config('runtime.nbFreePictures')){
            return ($nbPictures - config('runtime.nbFreePictures'))*10*100;
        }
        return 0;
    }

    private function getCostIsUrgent($isUrgent){
        if($isUrgent){
            return config('runtime.urgentCost')*100;
        }
        return 0;
    }

    private function getCostVideo($haveVideo){
        if($haveVideo){
            return config('runtime.videoCost')*100;
        }
        return 0;
    }

    private function getCostIsRenew($isRenew){
        if($isRenew){
            return config('runtime.renewCost')*100;
        }
        return 0;
    }

    private function getCost($nbPictures, $isUrgent=false, $isRenew=false, $haveVideo=false){
        $cost = 0;
        if(!auth()->user()->isDelegation) {
            $cost += $this->getCostPictures($nbPictures);
            $cost += $this->getCostIsUrgent($isUrgent);
            $cost += $this->getCostVideo($haveVideo);
            $cost += $this->getCostIsRenew($isRenew);
        }
        return $cost;
    }

    //Public function for get Cost of Advert on create
    public function cost($nbPictures, $isUrgent) {
        if(isset($nbPictures) && isset($isUrgent) && is_numeric($nbPictures)){
            return response()->json($this->getCost((int)$nbPictures,filter_var($isUrgent, FILTER_VALIDATE_BOOLEAN), false, session()->has('videoId')));
        } else {
            return response('error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
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
                $ancestors = $advert->category->getAncestors();
                $ancestors->add($advert->category);
                $advert->setBreadCrumb($ancestors);
                $advert->setBookmarkCount();
                return view('advert.show', compact('advert'));
//            }
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $advert = Advert::find($id);
        if($advert && (auth()->user()->id === $advert->user->id || auth()->user()->role == 'admin')){
            $advert->delete();
            session()->flash('info', trans('strings.view_advert_show_delete_success'));
            return response(route('home'), 200);
        } else {
            return response('error',500);
        }
    }

    /**
     *
     * Return List of Type advert
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListType()  {
        $list = DBUtils::getEnumValues('adverts', 'type');
        $transList = [];
        foreach ($list as $key => $item) {
            $transList[$key] = trans('strings.view_advert_list_type_' . $item);
        }
        return response()->json($transList);
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
                $this->approveAdvert($key, $value['isApprove'], $value['priceCoefficient']);
            }
        } catch (\Exception $e) {
            return response(trans('strings.view_advert_approve_error'), 500);
        }
        return response('ok',200);
    }

    /**
     *
     * private function to approve One advert
     *
     * @param $key
     * @param $value
     * @return null
     * @throws \Exception
     */
    private function approveAdvert($key, $value, $priceCoefficient=null) {
        if($value != null) {
            $advert = Advert::find($key);
            if($advert && is_null($advert->isValid)) {
                $advert->price_coefficient = $priceCoefficient;
                $invoiceFileName = null;
                //IF EXIST AUTHORIZATION PAYMENT
                if($advert->invoice
                    && $advert->invoice->authorization
                    && $advert->invoice->authorization != '')
                {
                    try {
                        $invoice = $advert->invoice;
                        $authorizationId = $advert->invoice->authorization;
                        $authorization = Authorization::get($authorizationId, $this->_api_context);
                        if((boolean)$value==true){
                            //First try to setting some params to advert before capture payment
                            $originalAdvert = null;
                            if($advert->originalAdvertId && $advert->originalAdvertId > 0) {
                                $originalAdvert = Advert::withTrashed()->find($advert->originalAdvertId);
                                if(!$originalAdvert){
                                    throw new \Exception('error original advert');
                                } else {
                                    $originalAdvert->isRenew = true;
                                    if(!$originalAdvert->deleted_at) {
                                        //renew after deleted original
                                        $advert->online_at = Carbon::parse($originalAdvert->online_at)->addDays(env('ADVERT_LIFE_TIME'));
                                    } else {
                                        //renew now!
                                        $advert->online_at = Carbon::now();
                                    }
                                }
                            } else {
                                $advert->online_at = Carbon::now();
                            }


                            //CAPTURE PAYMENT
                            $amt = new Amount();
                            $amt->setCurrency($authorization->getAmount()->getCurrency());
                            $amt->setTotal($authorization->getAmount()->getTotal());
                            $capture = new Capture();
                            $capture->setAmount($amt);
                            $getCapture = $authorization->capture($capture, $this->_api_context);
                            $invoice->captureId = $getCapture->getId();
                            $advert->isValid=(boolean)$value;



                            $stats = Stats::latest()->first();
                            if($invoice->cost > 0){
                                $stats->totalNewCostAdverts = $stats->totalNewCostAdverts + 1;
                                $stats->totalCosts = $stats->totalCosts + $invoice->cost;
                            } else {
                                $stats->totalNewFreeAdverts = $stats->totalNewFreeAdverts + 1;
                            }

                            DB::beginTransaction();
                            if($originalAdvert){ $originalAdvert->save();}

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
                                $invoiceFileName = $this->createInvoice($advert);
                            } catch (\Exception $e) {

                            }
                        } else {
                            //VOID PAYMENT
                            $getVoid = $authorization->void($this->_api_context);
                            $invoice->voidId = $getVoid->getId();
                            $advert->isValid=(boolean)$value;

                            DB::beginTransaction();
                            $invoice->save();
                            $advert->save();
                            DB::commit();
                        }
                    } catch (\Exception $e) {
                        throw new \Exception($e);
                    }
                } else {
                    $advert->isValid=(boolean)$value;
                    if((boolean)$value){
                        $advert->online_at = Carbon::now();
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
                        $recipient->notify(new InvoicePdf($advert, $senderName, $senderMail));
                    }
                }

                $recipient = $advert->user;
                $senderMail = env('SERVICE_MAIL_FROM');
                $senderName = ucfirst(config('app.name'));
                if($advert->isValid && !$advert->originalAdvertId) {
                    $recipient->notify(new AdvertApprove($advert, $senderName, $senderMail));
                    return null;
                } elseif ($advert->isValid && $advert->originalAdvertId > 0) {
                    $recipient->notify(new AdvertRenew($advert));
                    return $advert;
                } else {
                    $recipient->notify(new AdvertNotApprove($advert, $senderName, $senderMail));
                    return null;
                }

            }
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
        if($advert && $advert->user->id == auth()->user()->id && !$advert->invoice){
            $this->advertPublish($advert, $request, null);
            return redirect(route('home'))->with('success', trans('strings.advert_create_success'));
        } else {
            return response(trans('strings.view_advert_error'), 500);
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
    private function advertPublish(Advert $advert, Request $request, $authorizationId=null){
        DB::beginTransaction();
        $advert->isPublish = true;
        if($authorizationId){
            $invoice = $advert->invoice;
            $invoice->authorization = $authorizationId;
            $invoice->save();
        }
        $advert->save();
        DB::commit();
        $this->pictureManager->purgeSessionLocalTempo();
        session()->has('videoId') ? session()->forget('videoId'): null;
        $request->session()->flash('clear', true);
    }


    /**
     *
     * Return View for Payment
     *
     * @param $id
     * @return AdvertController|\Illuminate\Http\RedirectResponse
     */
    public function reviewForPayment($id) {
        if(!$this->haveCompleteAccount()){
            return redirect()->back()->withErrors(trans('strings.middleware_complete_account'));
        }
        $advert = Advert::find($id);
        if($advert
            && $advert->user->id == auth()->user()->id
            && ($advert->invoice && !$advert->invoice->authorization)
        ){
            $advert->load('invoice');
            $invoice = $advert->invoice;
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
            return view('advert.reviewForPayment', compact('advert', 'listCardTypes'));
        }
        return redirect(route('home'));
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
            $this->createAnonymous($request->email, $request->name, $request->phone, $request->compagnyName);
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


    /**
     *
     * Route when choice Pay by paypal
     *
     * @param $id
     * @return Controller|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function payByPaypal($id) {
        return $this->advertPayment($id, self::PAYPAL);
    }

    /**
     *
     * Route when choice Pay by Card
     *
     * @param $id
     * @param CreditCardRequest $request
     * @return Controller|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function payByCard($id, CreditCardRequest $request) {
        return $this->advertPayment($id, self::CARD, $request);
    }

    private function splitName($name) {
        $result = (explode(' ', $name));
        $firstname = '';
        $lastname = '';
        for($i=0; $i<count($result); $i++) {
            $i < count($result)/2 ? $firstname .= ' ' . $result[$i]: $lastname .= ' ' . $result[$i];
        }
        return [
            'firstName' => substr($firstname,1),
            'lastName' => substr($lastname,1),
        ];
    }


    /**
     *
     * Process Payment: Set Invoice and Get Authorization number if Card or rediret To paypal
     *
     * @param $id
     * @param $type
     * @param null $request
     * @return AdvertController|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function advertPayment($id, $type, $request=null) {
        $advert = Advert::find($id);
        $invoice = $advert->invoice;
        if($advert && $invoice
            && $advert->user->id == auth()->user()->id
            && $invoice->cost > 0
            && !$invoice->authorization
        ){
            $advert->load('user');

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
            $geoCodes = json_decode($advert->user->geoloc);
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
            $paypalAdress->setRecipientName($advert->user->compagnyName);


            //Payer Infos
            $payer = new Payer();
            if($type==self::PAYPAL){
                $payer->setPaymentMethod('paypal');
            } else {
                $card = new PaymentCard();
                $splitName = $this->splitName($request->name);
                foreach ($splitName as $item){
                    if(!$item || $item=''){
                        return redirect(route('advert.reviewForPayment', ['id' => $id]))
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
                $redirect_urls->setReturnUrl(route('advert.paypalStatus', ['id' => $advert->id, 'success'=>'true']))
                    ->setCancelUrl(route('advert.paypalStatus', ['id' => $advert->id, 'success'=>'false']));
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
                    return redirect(route('advert.reviewForPayment', ['id' => $id]))
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
                    return $this->saveApprovedAuthorization($payment, $advert, $request);
                }
            }
        }
        return redirect(route('home'))
            ->withErrors(trans('strings.payment_all_error'));
    }


    /**
     *
     * Get Result of Paypal Payment
     *
     * @param $id
     * @param $success
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function paypalStatus($id, $success, Request $request) {
        $advert = Advert::find($id);
        if(!$advert || !$advert->invoice){
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
                return redirect(route('advert.reviewForPayment', ['id' => $id]))
                    ->withErrors('err1: ' . trans('strings.payment_all_error'));
            }

            // clear the session payment ID
            session()->forget('paypal_payment_id');

            if (empty($request->input('PayerID')) || empty($request->input('token'))) {
                return redirect(route('advert.reviewForPayment', ['id' => $id]))
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
                return redirect(route('advert.reviewForPayment', ['id' => $id]))
                    ->withErrors('err4: ' . trans('strings.payment_all_error'));
            }

            if ($result->getState() == 'approved') { // payment made
                return $this->saveApprovedAuthorization($payment, $advert, $request);
            }

            return redirect(route('advert.reviewForPayment', ['id' => $id]))
                ->withErrors('err2: ' . trans('strings.payment_all_error'));
        } else {
            return redirect(route('advert.reviewForPayment', ['id' => $id]))
                ->withErrors('err6: ' . trans('strings.payment_all_error'));
        }
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
    private function saveApprovedAuthorization(Payment $payment, Advert $advert, Request $request) {
        $transactions = $payment->getTransactions();
        $relatedResources = $transactions[0]->getRelatedResources();
        $authorization = $relatedResources[0]->getAuthorization();
        $authorizationId = $authorization->getId();
        $this->advertPublish($advert, $request, $authorizationId);
        if($advert->originalAdvertId){
            try {
                $saveAdvert = $this->approveAdvert($advert->id, true, null);
                return redirect(route('home'))
                    ->with('success', trans('strings.payment_renew_success', ['date' => Carbon::parse($saveAdvert->online_at)->toDateTimeString()]));
            } catch (\Exception $e) {
                //TODO MAIL TO ADMIN FOR THIS CASE
                return redirect(route('home'))
                    ->withErrors(trans('strings.view_advert_auto_approve_error'));
            }
        }
        return redirect(route('home'))
                ->with('success', trans('strings.payment_paypal_success'));
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
        if($advert && auth()->user()->id === $advert->user->id && $advert->isValid && !$advert->isRenew && $advert->online_at){
            try {
                $newAdvert = $advert->replicate();
                $newAdvert->isPublish = false;
                $newAdvert->invoice_id = null;
                $newAdvert->deleted_at = null;
                $newAdvert->online_at = null;
                $newAdvert->lastObsoleteMail = null;
                $newAdvert->originalAdvertId = $advert->id;

                //Create Invoice
                DB::beginTransaction();
                $invoice = Invoice::create([
                    'user_id' => $advert->user->id,
                    //'invoice_number' => $next_invoice_number,
                    //'method' => Invoice::PAYPAL,
                    'cost' => $this->getCost(null, null, true, false),
                    'options' => $this->setOptions(null, null, true, false)
                ]);
                $newAdvert->invoice()->associate($invoice);


                //Advert Pictures
                $newPictures = [];
                foreach ($advert->pictures as $picture){
                    $newPictures[] = $picture->replicate();
                }
                $newAdvert->save();
                foreach ($newPictures as $picture){
                    $newAdvert->pictures()->save($picture);
                    $picture->save();
                }
                DB::commit();
                return redirect(route('user.completeAccount', ['id' => $newAdvert->id, 'title' => trans('strings.option_isRenew_name')]));
            } catch (\Exception $e) {
                return redirect(route('home'))->withErrors(trans('strings.view_all_error_saving_message'));
            }
        } else {
            return redirect(route('home'));
        }
    }

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

    public function vimeoQuota() {
        dd($this->vimeoManager->request('/CODEheures', [], 'GET'));
    }

    public function test($id) {
        $advert = Advert::find($id);
        $this->createInvoice($advert);
    }

    private  function createInvoice(Advert $advert) {
        $invoice = $advert->invoice;
        $user = $advert->user;
        LocaleUtils::switchToUserLocale($user);
        $fileName = null;
        if($invoice){
            //Invoice TTC Cost
            $tva = 0;
            if($invoice->tvaSubject){
                foreach ($invoice->options as $option){
                    $tva = $tva + $option['tvaVal'];
                }
            }

            //Invoice & Address
            $invoice->load('user');
            $address = json_decode($invoice->user->geoloc)[0]->formatted_address;

            //PDF FileName
            $fileName = $advert->getInvoiceFilePath();
            Storage::makeDirectory($advert->getInvoiceStoragePath() );

            //Create PDF
            $content = view('pdf.invoice.index', compact('invoice', 'address', 'tva'))->__toString();
            $header = view('pdf.header.view', compact('invoice'))->__toString();
            $footer = view('pdf.footer.view')->__toString();
            $this->createPdf($content, $header, $footer, $fileName);
        }
        LocaleUtils::switchToRuntimeLocale();
        return $fileName;
    }

    private function createPdf($content, $header, $footer, $fileName) {
        try {
            $css = file_get_contents(mix('css/pdf.css'),false,stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false))));

            $mpdf = new \mPDF();
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
            $mpdf->Output($fileName, 'F');
            return true;
        } catch (\Exception $e) {
            Throw new \Exception($e);
        }
    }
}
