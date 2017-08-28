<?php

namespace App\Http\Controllers;



use App\Advert;
use App\Common\GeoManager;
use App\Http\Requests\UpdateCompagnyNameRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Http\Requests\UpdateRegistrationRequest;
use App\Http\Requests\UpdateUserLocationRequest;
use App\Http\Requests\UpdateUserNameRequest;
use Codeheures\LaravelUtils\Traits\Tools\Currencies;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private $auth;

    /**
     * Create a new controller instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->middleware('auth');
        $this->middleware('canManageMyAccount');
        $this->auth = $auth;
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


        if(!is_null($user->avatar) && filter_var($user->avatar, FILTER_VALIDATE_URL)) {
            $routeAvatar = $user->avatar;
        } else {
            $routeAvatar = '';
        }


        return view('user.account', compact('user', 'ip', 'geolocType', 'zoomMap', 'routeAvatar'));
    }

    /**
     * Return the User Account page for complete it when Publish advert
     *
     * @param $id
     * @return UserController|\Illuminate\Http\RedirectResponse
     */
    public function completeAccount($id, Request $request){
        $title = $request->has('title') ? $request->title : null;
        $cost = $request->has('infoCost') ? $request->infoCost : 0;
        $user = $this->auth->user();
        if(!is_null($user->avatar) && filter_var($user->avatar, FILTER_VALIDATE_URL)) {
            $routeAvatar = $user->avatar;
        } else {
            $routeAvatar = '';
        }
        $ip=config('runtime.ip');
        $geolocType = 0;
        $zoomMap = 16;
        $advertAccountVerifiedStep = true;
        $advert = Advert::withTrashed()->find($id);
        if($advert && $advert->user->id === $user->id){
            return view('user.account', compact('user', 'ip', 'geolocType', 'zoomMap', 'advertAccountVerifiedStep', 'advert', 'title', 'cost', 'routeAvatar'));
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
        $prefCurrency = $request->currency;

        if(Currencies::isAvailableCurrency($prefCurrency)) {
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
                $user->requesterNumber = null;
                $user->vatIdentifier = null;
            } else {
                $user->registrationNumber = $request->value;
                $user->requesterNumber = env('TVA_REQUESTER_COUNTRY_CODE').env('TVA_REQUESTER_VAT_NUMBER');
                $user->vatIdentifier = $request->vat_number_identifier;
                $request->compagny_name ? $user->compagnyName = $request->compagny_name : null;

                if($request->compagny_address){
                    $geoCode_json = json_decode(GeoManager::getCodeAdress($request->compagny_address));
                    $geoCode_obj = (object)$geoCode_json;

                    if($geoCode_obj->status=="OK"){
                        $user->geoloc = json_encode($geoCode_obj->results);
                        $user->latitude=$geoCode_obj->results[0]->geometry->location->lat;
                        $user->longitude=$geoCode_obj->results[0]->geometry->location->lng;
                    }
                }
            }

            $user->save();
            return response('ok', 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_user_account_registration_patch_error'), 409);
        }

    }
}
