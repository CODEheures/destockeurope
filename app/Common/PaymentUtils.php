<?php

namespace App\Common;


use App\Invoice;
use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_Transaction;

trait PaymentUtils
{

    public static function getMode() {
        if(env('BRAINTREE_SANDBOX')){
            return 'sandbox';
        } else {
            return 'production';
        }
    }

    public static function getMerchantId() {
        if(env('BRAINTREE_SANDBOX')){
            return env('BRAINTREE_MERCHANT_ID_SANDBOX');
        } else {
            return env('BRAINTREE_MERCHANT_ID_LIVE');
        }
    }

    public static function setBaintreeConfiguration() {
        Braintree_Configuration::environment(self::getMode());
        if(env('BRAINTREE_SANDBOX')){
            Braintree_Configuration::merchantId(env('BRAINTREE_MERCHANT_ID_SANDBOX'));
            Braintree_Configuration::publicKey(env('BRAINTREE_PUBLIC_KEY_SANDBOX'));
            Braintree_Configuration::privateKey(env('BRAINTREE_PRIVATE_KEY_SANDBOX'));
        } else {
            Braintree_Configuration::merchantId(env('BRAINTREE_MERCHANT_ID_LIVE'));
            Braintree_Configuration::publicKey(env('BRAINTREE_PUBLIC_KEY_LIVE'));
            Braintree_Configuration::privateKey(env('BRAINTREE_PRIVATE_KEY_LIVE'));
        }
    }

    public static function getBraintreeClientToken() {
        self::setBaintreeConfiguration();

        $clientToken = Braintree_ClientToken::generate();
        return $clientToken;
    }

    public static function getAuthorization($nonce, $deviceData, $invoiceId) {
        self::setBaintreeConfiguration();

        $invoice = Invoice::find($invoiceId);
        if($invoice && is_string($nonce) && strlen($nonce) > 0 ){

            //set $tva, $price, $productName
            $tva = 0;
            $productName = trans_choice('strings.payment_generic_product_name', count($invoice->options), ['nb' => count($invoice->options)]);
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


            $result = Braintree_Transaction::sale([
                'amount' => strval($price+$tva),
                'orderId' => strval($invoiceId),
                'taxAmount' => strval($tva),
                'taxExempt' => !$invoice->tvaSubject,
                'deviceData' => $deviceData,
                'paymentMethodNonce' => $nonce,
                'options' => [
                    'submitForSettlement' => false
                ]
            ]);

            if($result->success) {
                $invoice->method = Invoice::BRAINTREE;
                $invoice->transaction_id = $result->transaction->id;
                $invoice->save();
                $token = md5($result->transaction->id);
                return route('advert.validateOrder', ['invoiceId' => $invoiceId, 'token'=> $token]);
            }
        }

        return false;
    }

    public static function voidTransaction(Invoice $invoice) {
        self::setBaintreeConfiguration();

        $transactionId = $invoice->transaction_id;
        $result = Braintree_Transaction::void($transactionId);
        if ($result->success) {
            $invoice->voided = true;
            $invoice->save();
        }
    }

    public static function getAndLockNextInvoiceNumber() {
        $previousInvoice = Invoice::orderBy('invoice_number', 'DESC')->lockForUpdate()->first();
        if ($previousInvoice) {
            $next_invoice_number = $previousInvoice->invoice_number + 1;
        } else {
            $next_invoice_number = 1;
        }
        return $next_invoice_number;
    }

    public static function captureTransaction(Invoice $invoice) {
        self::setBaintreeConfiguration();

        $transactionId = $invoice->transaction_id;
        $result = Braintree_Transaction::submitForSettlement($transactionId);
        if ($result->success) {
            $invoice->captured = true;

            //GET INVOICE NUMBER
            $invoice->invoice_number = self::getAndLockNextInvoiceNumber();

            //SAVE INVOICE
            $invoice->save();

            //CREATE INVOICE FILE
            try { InvoiceUtils::createInvoiceByInvoice($invoice); } catch (\Exception $e) { }

            return true;
        } else {
            return $result->errors;
        }
    }

    public static function refundTransaction(Invoice $invoice) {
        self::setBaintreeConfiguration();

        $transactionId = $invoice->transaction_id;

        $transaction = Braintree_Transaction::find($transactionId);
        if($transaction->status === "settled" || $transaction->status === "settling"){
            $result = Braintree_Transaction::refund($transactionId);
            if ($result->success) {
                //SAVE INVOICE
                $invoice->refunded = true;
                $invoice->save();
                return null;
            } else {
                throw new \Exception('refund fail');
            }
        } else {
            $invoice->refunded = true;
            self::voidTransaction($invoice);
        }




    }
}