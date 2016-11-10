<?php

namespace App\Http\Controllers;



use App\Advert;
use App\Common\PicturesManager;
use App\Http\Requests\UpdateCompagnyNameRequest;
use App\Http\Requests\UpdateRegistrationRequest;
use App\Http\Requests\UpdateUserLocationRequest;
use App\Http\Requests\UpdateUserNameRequest;
use Dropbox\Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class UserController extends Controller
{

    private $auth;
    private $pictureManager;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth, PicturesManager $picturesManager)
    {
        $this->middleware('auth');
        $this->auth = $auth;
        $this->pictureManager = $picturesManager;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->auth->user();
        //TODO changer ip
        //$ip = $request->ip();
        $ip='82.246.117.210';
        $geolocType = 0;
        $zoomMap = 16;
        return view('user.account', compact('user', 'ip', 'geolocType', 'zoomMap'));
    }

    public function completeAccount($id){
        $user = $this->auth->user();
        //TODO changer ip
        //$ip = $request->ip();
        $ip='82.246.117.210';
        $geolocType = 0;
        $zoomMap = 16;
        $advertAccountVerifiedStep = true;
        $advert = Advert::find($id);
        if($advert){
            return view('user.account', compact('user', 'ip', 'geolocType', 'zoomMap', 'advertAccountVerifiedStep', 'advert'));
        } else {
            return redirect()->back()->withInput()->withErrors('errors', trans('strings.view_all_error_saving_message'));
        }
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

    public function setLocation(UpdateUserLocationRequest $request) {
        try {
            $user = $this->auth->user();
            $user->latitude = $request->lat;
            $user->longitude = $request->lng;
            $user->geoloc = $request->geoloc;
            $user->save();
            return response('ok', 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_user_account_locale_patch_error'), 409);
        }

    }

    public function setName(UpdateUserNameRequest $request) {
        try {
            $user = $this->auth->user();
            $user->name = $request->value;
            $user->save();
            return response('ok', 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_user_account_locale_patch_error'), 409);
        }

    }

    public function setCompagnyName(UpdateCompagnyNameRequest $request) {
        try {
            $user = $this->auth->user();
            $user->compagny_name = $request->value;
            $user->save();
            return response('ok', 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_user_account_locale_patch_error'), 409);
        }

    }

    public function setRegistrationNumber(UpdateRegistrationRequest $request) {
        try {
            $user = $this->auth->user();
            $user->registration_number = $request->value;
            $user->save();
            return response('ok', 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_user_account_locale_patch_error'), 409);
        }

    }
}
