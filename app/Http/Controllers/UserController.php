<?php

namespace App\Http\Controllers;



use App\Advert;
use App\Common\GeoManager;
use App\Common\PicturesManager;
use App\Http\Requests\UpdateCompagnyNameRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Http\Requests\UpdateRegistrationRequest;
use App\Http\Requests\UpdateUserLocationRequest;
use App\Http\Requests\UpdateUserNameRequest;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class UserController extends Controller
{

    private $auth;
    private $pictureManager;

    /**
     * Create a new controller instance.
     *
     * @param Guard $auth
     * @param PicturesManager $picturesManager
     */
    public function __construct(Guard $auth, PicturesManager $picturesManager)
    {
        $this->middleware('auth');
        $this->middleware('isNotDelegationUser');
        $this->auth = $auth;
        $this->pictureManager = $picturesManager;
    }

    /**
     * Return the User Account page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = $this->auth->user();
        $ip=config('runtime.ip');
        $geolocType = 0;
        $zoomMap = 16;
        return view('user.account', compact('user', 'ip', 'geolocType', 'zoomMap'));
    }

    /**
     * Return the User Account page for complete it when Publish advert
     *
     * @param $id
     * @return UserController|\Illuminate\Http\RedirectResponse
     */
    public function completeAccount($id, $title=null){
        $user = $this->auth->user();
        $ip=config('runtime.ip');
        $geolocType = 0;
        $zoomMap = 16;
        $advertAccountVerifiedStep = true;
        $advert = Advert::find($id);
        if($advert && $advert->user->id === $user->id && !$advert->isPublish){
            return view('user.account', compact('user', 'ip', 'geolocType', 'zoomMap', 'advertAccountVerifiedStep', 'advert', 'title'));
        } else {
            return redirect(route('home'))->withErrors(trans('strings.view_all_error_saving_message'));
        }
    }

    /**
     * Get somes Actual User Elements
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMe() {
        $user = $this->auth->user();
        return response()->json([
            'userName' => $user->name,
            'compagnyName' => $user->compagnyName,
            'registrationNumber' => $user->registrationNumber,
            'lng' => $user->longitude,
            'lat' => $user->latitude,
            'geoloc' => $user->geoloc,
            'vatIdentifier' => $user->vatIdentifier
        ]);
    }

    /**
     * Set The Currency User Attribute
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function setCurrency(Request $request) {
        $currencies = new ISOCurrencies();
        $prefCurrency = $request->currency;

        if($currencies->contains(new Currency($prefCurrency)) && $prefCurrency != '') {
            $this->auth->user()->currency = $prefCurrency;
            $this->auth->user()->save();
            return response('ok', 200);
        } else {
            return response(trans('strings.view_user_account_currency_patch_error'), 409);
        }
    }

    /**
     * Set the User locale Attribute
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Set the User Location attribute
     *
     * @param UpdateUserLocationRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Set the Name User Attribute
     *
     * @param UpdateUserNameRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Set the phone User Attribute
     *
     * @param UpdatePhoneRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function setPhone(UpdatePhoneRequest $request) {
        try {
            $user = $this->auth->user();
            $user->phone = $request->value;
            $user->save();
            return response('ok', 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_user_account_locale_patch_error'), 409);
        }

    }

    /**
     * Set compagnyName User Attribute
     *
     * @param UpdateCompagnyNameRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function setCompagnyName(UpdateCompagnyNameRequest $request) {
        try {
            $user = $this->auth->user();
            $user->compagnyName = $request->value;
            $user->save();
            return response('ok', 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_user_account_locale_patch_error'), 409);
        }

    }

    /**
     * Set registration user attribute
     *
     * @param UpdateRegistrationRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function setRegistrationNumber(UpdateRegistrationRequest $request) {

        try {
            $user = $this->auth->user();

            if(!$request->has('value') || is_null($request->value) || $request->value==''){
                $user->registrationNumber = null;
                $user->vatIdentifier = null;
            } else {
                $user->registrationNumber = $request->value;
                $user->vatIdentifier = $request->vat_number_identifier;
                $user->compagnyName = $request->compagny_name;

                $geoCode_json = json_decode(GeoManager::getCodeAdress($request->compagny_address));
                $geoCode_obj = (object)$geoCode_json;

                if($geoCode_obj->status=="OK"){
                    $user->geoloc = json_encode($geoCode_obj->results);
                    $user->latitude=$geoCode_obj->results[0]->geometry->location->lat;
                    $user->longitude=$geoCode_obj->results[0]->geometry->location->lng;
                }
            }

            $user->save();
            return response('ok', 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_user_account_registration_patch_error'), 409);
        }

    }
}
