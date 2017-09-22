<?php

namespace App\Common;



use App\Exceptions\PaymentException;
use App\Invoice;
use Illuminate\Http\Request;
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
use PayPal\Api\RefundRequest;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PaymentManager
{
    private $_api_context;
    Const PAYPAL = 0;
    Const CARD = 1;

    public function __construct() {
        // setup PayPal api context
        if(!(env('PAYPAL_SANDBOX')=='true')){
            $paypal_conf = config('paypal');
        } else {
            $paypal_conf = config('paypal_sandbox');
        }
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    /**
     *
     * Process Payment: Set Invoice and Get Authorization number if Card or rediret To paypal
     *
     * @param $invoiceId
     * @param $type
     * @param null $request
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function createPayment($invoiceId, $type, $request=null) {

        try {
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
                            throw new PaymentException(trans('strings.request_input_generic_error', ['name' => trans('strings.payment_card_name_label')]));
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
                        throw new PaymentException('err6: ' . trans('strings.payment_all_error'));
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
                        return $redirect_url;
                    }
                } elseif ($type == self::CARD) {
                    $resultOfPayment = [
                        'state' => $payment->getState(),
                        'payment' => $payment,
                        'invoice' => $invoice,
                        'request' => $request
                    ];
                    return $resultOfPayment;
                }
            }
            throw new PaymentException(trans('strings.payment_all_error'));
        }  catch (PaymentException $e) {
            throw new PaymentException($e->getMessage());
        }  catch (\Exception $e) {
            throw new PaymentException(trans('strings.payment_all_error'));
        }

    }

    /**
     *
     * Get Result of Paypal Payment
     *
     * @param $invoiceId
     * @param $success
     * @param Request $request
     * @return array
     */
    public function paypalStatus($invoiceId, $success, Request $request) {

        try {
            $invoice = Invoice::find($invoiceId);

            if(is_null($invoice)){
                throw new PaymentException('err3: ' . trans('strings.payment_all_error'));
            }
            if($success == 'true') {
                // Get the payment ID before session clear
                $session_payment_id = session('paypal_payment_id');
                $payment_id = $request->get('paymentId');

                //test sessionId = Request return paymentId
                if($session_payment_id != $payment_id) {
                    session()->forget('paypal_payment_id');
                    throw new PaymentException('err1: ' . trans('strings.payment_all_error'));
                }

                // clear the session payment ID
                session()->forget('paypal_payment_id');

                if (empty($request->input('PayerID')) || empty($request->input('token'))) {
                    throw new PaymentException('err2: ' . trans('strings.payment_all_error'));
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
                    throw new PaymentException('err4: ' . trans('strings.payment_all_error'));
                }


                return ['state' => $result->getState(), 'payment' => $payment, 'invoice' => $invoice, 'request' => $request];

            } else {
                throw new PaymentException('err6: ' . trans('strings.payment_all_error'));
            }
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage());
        }  catch (\Exception $e) {
            throw new PaymentException(trans('strings.payment_all_error'));
        }
    }

    /**
     *
     * Save Authorization number Of payment
     *
     * @param Payment $payment
     * @param Invoice $invoice
     * @return \Illuminate\Http\RedirectResponse
     * @throws PaymentException
     * @internal param Advert $advert
     * @internal param Request $request
     */
    public function saveAuthorization(Payment $payment, Invoice $invoice) {
        try {
            $transactions = $payment->getTransactions();
            $relatedResources = $transactions[0]->getRelatedResources();
            $authorization = $relatedResources[0]->getAuthorization();
            $authorizationId = $authorization->getId();

            if(!is_null($authorizationId) && !is_null($invoice)){
                $invoice->authorization = $authorizationId;
                $invoice->save();
            }
        } catch (\Exception $e) {
            throw new PaymentException('err7' . trans('strings.payment_all_error'));
        }
        return null;
    }

    /**
     *
     * Capture a payment
     *
     * @param Invoice $invoice
     */
    public function capturePayment(Invoice $invoice) {
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

        //GET INVOICE NUMBER
        $previousInvoice = Invoice::orderBy('invoice_number', 'DESC')->lockForUpdate()->first();
        if($previousInvoice){
            $next_invoice_number = $previousInvoice->invoice_number + 1;
        } else {
            $next_invoice_number = 1;
        }
        $invoice->invoice_number = $next_invoice_number;

        //SAVE INVOICE
        $invoice->save();

        //CREATE INVOICE FILE
        try { InvoiceUtils::createInvoiceByInvoice($invoice); } catch (\Exception $e) { }
    }

    /**
     *
     * Refund a payment
     *
     * @param Invoice $invoice
     */
    public function refundPayment(Invoice $invoice) {
        //CAPTURE PAYMENT
        $captureId = $invoice->captureId;
        $capture = Capture::get($captureId, $this->_api_context);

        $amt = new Amount();
        $amt->setCurrency($capture->getAmount()->getCurrency());
        $amt->setTotal($capture->getAmount()->getTotal());

        $refundRequest = new RefundRequest();
        $refundRequest->setAmount($amt);

        $captureRefund = $capture->refundCapturedPayment($refundRequest, $this->_api_context);

        //SAVE INVOICE
        $invoice->refundId = $captureRefund->getId();
        $invoice->save();
    }

    /**
     *
     * Void a payment
     *
     * @param Invoice $invoice
     */
    public function voidPayment(Invoice $invoice) {
        $authorizationId = $invoice->authorization;
        $authorization = Authorization::get($authorizationId, $this->_api_context);
        $getVoid = $authorization->void($this->_api_context);
        $invoice->voidId = $getVoid->getId();
        $invoice->save();
    }
}