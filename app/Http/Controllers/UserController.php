<?php

namespace App\Http\Controllers;



use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class UserController extends Controller
{

    private $auth;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->middleware('auth');
        $this->auth = $auth;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->auth->user();
        return view('user.account', compact('user'));
    }

    public function setCurrency(Request $request) {
        $currencies = new ISOCurrencies();
        $prefCurrency = $request->currency;

        if($currencies->contains(new Currency($prefCurrency))) {
            $this->auth->user()->currency = $prefCurrency;
            $this->auth->user()->save();
            return response('ok', 200);
        } else {
            return response(trans('strings.view_user_account_currency_patch_error'), 409);
        }
    }

    public function setLocale(Request $request) {

        $existLocales = \ResourceBundle::getLocales('');

        if(in_array($request->localisation, $existLocales)){
            $this->auth->user()->locale = $request->localisation;
            $this->auth->user()->save();
            return response('ok', 200);
        } else {
            return response(trans('strings.view_user_account_locale_patch_error'), 409);
        }
    }
}
