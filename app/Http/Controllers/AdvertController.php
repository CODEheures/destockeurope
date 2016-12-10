<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Category;
use App\Common\CategoryUtils;
use App\Common\DBUtils;
use App\Common\MoneyUtils;
use App\Common\PicturesManager;
use App\Common\UserUtils;
use App\Http\Requests\CreditCardRequest;
use App\Http\Requests\StoreAdvertRequest;
use App\Notifications\CustomerContactSeller;
use App\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class AdvertController extends Controller
{

    use CategoryUtils;
    use UserUtils;
    private $pictureManager;

    public function __construct(PicturesManager $picturesManager) {
        $this->middleware('auth', ['except' => ['index', 'show', 'getListType', 'sendMail']]);
        $this->middleware('haveCompleteAccount', ['only' => ['publish']]);
        $this->middleware('isAdminUser', ['only' => ['toApprove','listApprove', 'approve']]);
        $this->pictureManager  = $picturesManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Init vars
        $isSearchRequest = ($request->has('search') && strlen($request->search) >= 3);
        $isSearchResults = ($request->has('resultsFor') && strlen($request->resultsFor) >= 3);
        $isRangePricesOnly = ($request->has('priceOnly') && filter_var($request->priceOnly, FILTER_VALIDATE_BOOLEAN) == true);
        $isUrgentOnly = ($request->has('isUrgent') && filter_var($request->isUrgent, FILTER_VALIDATE_BOOLEAN) == true );

        //only valid advert
        $adverts = Advert::where('isValid', true);

        //where currency
        if($request->has('currency') && MoneyUtils::isAvailableCurrency($request->currency)) {
            $currency = $request->currency;
        } else {
            $currency = config('runtime.currency');
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
            $minAllPrice = $adverts->min('price');
            $maxAllPrice = $adverts->max('price');

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
        }

        //STOP REQUEST HERE IF only RANGE PRICES
        if($isRangePricesOnly){
            return response()->json(['minPrice'=> $minAllPrice, 'maxPrice' => $maxAllPrice]);
        }

        //if urgent
        if($isUrgentOnly){
            $adverts = $adverts->where('isUrgent', true);
        }

        //if range price
        if($request->has('minPrice') && $request->has('maxPrice') ){
            $minPrice = MoneyUtils::setPriceWithoutDecimal($request->minPrice, $currency);
            $maxPrice = MoneyUtils::setPriceWithoutDecimal($request->maxPrice, $currency);
            $adverts = $adverts->where('price', '>=', $minPrice)->where('price', '<=', $maxPrice);
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
            $adverts = $adverts->orderBy('updated_at', 'desc')->limit(config('runtime.maxNumberOfSearchResults'))->get();
        } else {
            $adverts = $adverts->orderBy('updated_at', 'desc')->paginate(config('runtime.advertsPerPage'));
        }

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

        if($isSearchRequest){
            $action = [
                'url' => '#',
                'text' => trans_choice('strings.form_input_search_view_all', $countSearch, ['nb' => $countSearch])
            ];
            return response()->json(['results'=> $resultsByCat, 'action' => $action]);
        } else {
            return response()->json(['adverts'=> $adverts, 'minPrice'=> $minAllPrice, 'maxPrice' => $maxAllPrice]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $ip=config('runtime.ip');
        $geolocType = 1;
        $zoomMap = 11;
        return view('advert.create', compact('ip', 'geolocType', 'zoomMap'));
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
                $advert->geoloc = $request->geoloc;
                $advert->mainPicture = $request->main_picture;
                $advert->currency=$request->currency;
                $advert->totalQuantity=$request->total_quantity;
                $advert->lotMiniQuantity=$request->lot_mini_quantity;
                $advert->isUrgent=filter_var($request->is_urgent, FILTER_VALIDATE_BOOLEAN);

                $advert->price = MoneyUtils::setPriceWithoutDecimal($request->price,$request->currency);

                $results = $this->pictureManager->storeLocalFinal();

                $advert->options = $this->setOptions(count($results)/2, $advert->isUrgent);

                $advert->cost = $this->getCost(count($results)/2, $advert->isUrgent);

                DB::beginTransaction();
                $advert->save();
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
                return redirect(route('user.completeAccount', ['id' =>$advert->id]));
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withInput()->withErrors(trans('strings.view_all_error_saving_message'));
            }
        } else {
            return redirect()->back()->withInput()->withErrors(trans('strings.view_all_error_saving_message'));
        }
    }

    private function setOptions($nbPictures, $isUrgent) {
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

    private function getCost($nbPictures, $isUrgent=false){
        $cost = 0;
        $cost += $this->getCostPictures($nbPictures);
        $cost += $this->getCostIsUrgent($isUrgent);
        return $cost;
    }

    public function cost($nbPictures, $isUrgent) {
        if(isset($nbPictures) && isset($isUrgent) && is_numeric($nbPictures)){
            return response()->json($this->getCost((int)$nbPictures,filter_var($isUrgent, FILTER_VALIDATE_BOOLEAN)));
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
    public function show($id, Request $request)
    {
        $advert = Advert::find($id);
        if($advert->isValid) {
            if ($request->isXmlHttpRequest()) {
                $advert->load('pictures');
                $advert->load('category');
                $ancestors = $advert->category->getAncestors();
                $ancestors->add($advert->category);
                $advert->setBreadCrumb($ancestors);
                return response()->json(['advert' => $advert]);
            } else {
                $advert->load('user');
                $advert->load('bookmarks');
                $advert->timestamps = false;
                $advert->views = $advert->views + 1;
                $advert->save();
                return view('advert.show', compact('advert'));
            }
        } elseif ($request->isXmlHttpRequest() && auth()->check() && ($advert->user->id == auth()->user()->id || auth()->user()->role == 'admin')) {
            return response()->json(['advert' => $advert]);
        } else {
            return back();
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
        if($advert && auth()->user()->id === $advert->user->id){
            $advert->delete();
            session()->flash('info', trans('strings.view_advert_show_delete_success'));
            return response(route('home'), 200);
        } else {
            return response('error',500);
        }
    }

    public function getListType()  {
        $list = DBUtils::getEnumValues('adverts', 'type');
        $transList = [];
        foreach ($list as $key => $item) {
            $transList[$key] = trans('strings.view_advert_list_type_' . $item);
        }
        return response()->json($transList);
    }

    public function toApprove() {
        return view('advert.approve');
    }

    public function listApprove() {
        $adverts = Advert::where('isPublish', true)->where('isValid', null)->get();
        $adverts->load('user');
        $adverts->load('pictures');
        $adverts->load('category');
        return response()->json($adverts);
    }

    public function approve(Request $request) {

        $approveList = $request->all();
        try {
            foreach ($approveList as $key=>$value) {
                if($value != null) {
                    $advert = Advert::find($key);
                    if($advert) {
                        $advert->isValid=(boolean)$value;
                        $advert->save();
                        /*TODO envoyer mail au client */
                    }
                }
            }
        } catch (\Exception $e) {
            return response(trans('strings.view_advert_error'), 500);
        }

        return response('ok',200);
    }

    public function publish(Request $request, $id){
        $advert = Advert::find($id);
        $advert->load('user');
        if($advert && $advert->user->id == auth()->user()->id && $advert->cost == 0){
            $advert->isPublish = true;
            $advert->save();
            $this->pictureManager->purgeLocalTempo();
            $request->session()->flash('clear', true);
            return redirect(route('home'))->with('success', trans('strings.advert_create_success'));
        } elseif($advert && $advert->user->id == auth()->user()->id && $advert->cost > 0) {
            $payment = $advert->payment();
            //TODO fin du test et redirection
            $advert->isPublish = true;
            $advert->save();
            $this->pictureManager->purgeLocalTempo();
            $request->session()->flash('clear', true);
            return redirect(route('home'))->with('success', trans('strings.advert_create_success'));
        } else {
            return response(trans('strings.view_advert_error'), 500);
        }
    }

    public function reviewForPayment($id) {
        if(!$this->haveCompleteAccount()){
            return redirect()->back()->withErrors(trans('strings.middleware_complete_account'));
        }
        $advert = Advert::find($id);
        if($advert->user->id == auth()->user()->id){
            return view('advert.reviewForPayment', compact('advert'));
        } else {
            return redirect()->back()->withErrors(trans('strings.view_all_error_saving_message'));
        }

    }

    public function sendMail(Request $request) {
        $advert = Advert::find($request->id);
        if($advert){
            $senderMail = $request->email;
            $senderName = ucfirst($request->name);
            $message = $request->message;
            $recipient = $advert->user;

            $recipient->notify(new CustomerContactSeller($advert, $senderName, $senderMail, $message));
            return response('ok', 200);
        } else {
            return response(trans('strings.mail_customerToSeller_send_error'), 500);
        }
    }

    public function payByPaypal($id) {
        return 'payée avec paypal';
    }

    public function payByCard($id, CreditCardRequest $request) {
        return 'payée avec carte';
    }

//    public function advertPayment($id) {
//        $advert = Advert::find($id);
//        $advert->load('user');
//        if($advert && $advert->user->id == auth()->user()->id && $advert->cost > 0){
//
//        } else {
//
//        }
//
//
//        $tva = round($product->tvaPrice()/100,2);
//        $price = round($product->priceTTC()/100,2);
//
//
//        $payerInfo = new PayerInfo();
//
//        $adresses = $this->auth->user()->addresses;
//        foreach ($adresses as $adress) {
//            if($adress->type == 'invoice') {
//                $paypalAdress = new ShippingAddress();
//                $paypalAdress->setLine1($adress->address);
//                $paypalAdress->setLine2($adress->complement);
//                $paypalAdress->setPostalCode($adress->zipCode);
//                $paypalAdress->setCity($adress->town);
//                $paypalAdress->setCountryCode('FR');
//                if($this->auth->user()->enterprise != null){
//                    $paypalAdress->setRecipientName($this->auth->user()->enterprise);
//                } else {
//                    $paypalAdress->setRecipientName($this->auth->user()->firstName . ' ' . $this->auth->user()->lastName);
//                }
//            }
//        }
//
//
//        $payer = new Payer();
//        $payer->setPaymentMethod('paypal');
//        $payer->setPayerInfo($payerInfo);
//
//
//        $item_1 = new Item();
//        $item_1->setName($product->description) // item name
//        ->setCurrency('EUR')
//            ->setQuantity(1)
//            ->setTax($tva)
//            ->setPrice($price); // unit price
//
//        // add item to list
//        $item_list = new ItemList();
//        $item_list->setItems(array($item_1));
//        $item_list->setShippingAddress($paypalAdress);
//
//        $amount = new Amount();
//        $amount->setCurrency('EUR')->setTotal($price);
//
//        $transaction = new Transaction();
//        $transaction->setAmount($amount)
//            ->setItemList($item_list)
//            ->setDescription('Votre achat chez CODEheures')
//            ->setInvoiceNumber($purchase->id);
//
//
//        $redirect_urls = new RedirectUrls();
//
//        // Specify return URL
//        $redirect_urls->setReturnUrl(route('customer.sale.payment.status').'?success=true')
//            ->setCancelUrl(route('customer.sale.payment.status').'?success=false');
//
//        $payment = new Payment();
//        $payment->setIntent('Sale')
//            ->setPayer($payer)
//            ->setRedirectUrls($redirect_urls)
//            ->setTransactions(array($transaction));
//
//
//        try {
//            $payment->create($this->_api_context);
//        } catch (PayPalConnectionException $ex) {
//            if (config('app.debug')) {
//                echo "Exception: " . $ex->getMessage() . PHP_EOL;
//                $err_data = json_decode($ex->getData(), true);
//                exit;
//            } else {
//                die('Ho mince! Une erreur inconnue est apparue \':(');
//            }
//        }
//
//        foreach($payment->getLinks() as $link) {
//            if($link->getRel() == 'approval_url') {
//                $redirect_url = $link->getHref();
//                break;
//            }
//        }
//
//        // add payment ID to session
//        session(['paypal_payment_id' => $payment->getId()]);
//
//        if(isset($redirect_url)) {
//            // redirect to paypal
//            return redirect($redirect_url);
//        }
//        return redirect(route('customer.monitor.index'))
//            ->with('error', 'Ho mince! Une erreur inconnue est apparue \':(');
//    }
//
//    public function salePaymentStatus(Request $request) {
//
//        if($request->get('success') == 'true') {
//            // Get the payment ID before session clear
//            $session_payment_id = session('paypal_payment_id');
//            $payment_id = $request->get('paymentId');
//
//            //test sessionId = Request return paymentId
//            if($session_payment_id != $payment_id) {
//                session()->forget('paypal_payment_id');
//                return redirect(route('customer.monitor.index'))
//                    ->with('error', 'Ho non! Le paiement à échoué \':(');
//
//            }
//
//            // clear the session payment ID
//            session()->forget('paypal_payment_id');
//
//            if (empty($request->input('PayerID')) || empty($request->input('token'))) {
//                return redirect(route('customer.monitor.index'))
//                    ->with('error', 'Ho non! Le paiement à échoué \':(');
//            }
//
//            try {
//                $payment = Payment::get($payment_id, $this->_api_context);
//
//                // PaymentExecution object includes information necessary
//                // to execute a PayPal account payment.
//                // The payer_id is added to the request query parameters
//                // when the user is redirected from paypal back to your site
//                $execution = new PaymentExecution();
//                $execution->setPayerId($request->input('PayerID'));
//                //Execute the payment
//                $result = $payment->execute($execution, $this->_api_context);
//            } catch (\Exception $e) {
//                return redirect(route('customer.monitor.index'))
//                    ->with('error', 'Ho non! Le paiement à échoué \':(');
//            }
//
//
//
//
//            if ($result->getState() == 'approved') { // payment made
//                $purchase = Purchase::findOrFail($result->getTransactions()[0]->getInvoiceNumber());
//                $purchase->paypal_result = json_encode(['id' => $result->getId()]);
//                $purchase->payed = true;
//                $purchase->save();
//
//                //envoi du mail de la facture
//                try {
//                    $this->invoiceTools->create('isSold', 'purchase', $purchase->id, true);
//                    $this->invoiceTools->sendMail();
//                    return redirect(route('customer.monitor.index'))
//                        ->with('success', 'Merci pour votre paiement. Votre compte est crédité. Votre facture est disponible dans votre espace client sur le détail de votre commande sur le lien suivant: ')
//                        ->with('info_url', route('invoice.get', ['type' => 'isSold', 'origin' => 'purchase', 'id' => $purchase->id]))
//                        ->with('info_url_txt', 'voir ma facture');
//                } catch (\Exception $e) {
//                    return redirect(route('customer.monitor.index'))
//                        ->with('success', 'Merci pour votre paiement. Votre compte est crédité.');
//                }
//            }
//
//            return redirect(route('customer.monitor.index'))
//                ->with('error', 'Ho non! Le paiement à échoué \':(');
//        } else {
//            return redirect(route('customer.monitor.index'))
//                ->with('error', 'Ho non! Le paiement à échoué \':(');
//        }
//    }
}
