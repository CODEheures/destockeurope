<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('vat_number', function ($attribute, $value, $parameters, $validator){
            $country = strtoupper(substr($value,0,2));
            $vatNumber = strtoupper(substr($value, -strlen($value)+2));

            //$saop = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatTestService.wsdl");
            //FR95481749687
            //FR56749988721
            //DE811569869
            //BE0877241373
            //ESN1081152I
            $saop = new \SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
            $request = App::make('request');

            try {
                $saopResponse = ($saop->checkVatApprox(array(
                    'countryCode' => $country,
                    'vatNumber' => $vatNumber,
                    'requesterCountryCode' => env('TVA_REQUESTER_COUNTRY_CODE'),
                    'requesterVatNumber' => env('TVA_REQUESTER_VAT_NUMBER')
                )));
                if ($saopResponse->valid) {
                    $request->request->add(['vat_number_identifier' => $saopResponse->requestIdentifier]);
                    isset($saopResponse->traderName) ?
                        $request->request->add(['compagny_name' => $saopResponse->traderName]) :
                        null;
                    isset($saopResponse->traderAddress) ?
                        $request->request->add(['compagny_address' => $saopResponse->traderAddress]) :
                        null;
                    $validator->after(function ($validator) {
                        $request = App::make('request');
                        return $request;
                    });
                    return true;
                } else {
                    $transVatError = trans('strings.request_vat_invalid');
                    $validator->addReplacer('vat_number' , function ($message, $attribute, $rule, $parameters) use($transVatError) {
                        return str_replace(':vat_message', $transVatError, $message);
                    });
                    return false;
                }
            } catch (\SoapFault $e) {
                $request->request->add(['vatSaopFault'=>$e->getMessage()]);
                $transVatError = trans('strings.request_vat_invalid');
                if ($request->filled('vatSaopFault')) {
                    if($request->vatSaopFault == 'INVALID_INPUT') {
                        $transVatError = trans('strings.request_vat_invalid_input');
                    } elseif ($request->vatSaopFault == 'SERVICE_UNAVAILABLE'
                        || $request->vatSaopFault == 'MS_UNAVAILABLE'
                        || $request->vatSaopFault == 'TIMEOUT'
                        || $request->vatSaopFault == 'IP_BLOCKED'
                        || $request->vatSaopFault == 'GLOBAL_MAX_CONCURRENT_REQ'
                        || $request->vatSaopFault == 'GLOBAL_MAX_CONCURRENT_REQ_TIME'
                        || $request->vatSaopFault == 'MS_MAX_CONCURRENT_REQ'
                        || $request->vatSaopFault == 'MS_MAX_CONCURRENT_REQ_TIME'
                    ) {
                        $transVatError = trans('strings.request_vat_service_unavailable');
                    } elseif ($request->vatSaopFault == 'VAT_BLOCKED') {
                        $transVatError = trans('strings.request_vat_blocked');
                    }
                }
                $validator->addReplacer('vat_number' , function ($message, $attribute, $rule, $parameters) use($transVatError) {
                    return str_replace(':vat_message', $transVatError, $message);
                });
                return false;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}