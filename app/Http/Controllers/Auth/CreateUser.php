<?php
/**
 * Created by PhpStorm.
 * User: Papoun
 * Date: 04/10/2016
 * Time: 10:12
 */

namespace App\Http\Controllers\Auth;

use App\Notifications\SendToken;
use App\User;
use Codeheures\LaravelUtils\Traits\Geo\GeoUtils;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait CreateUser {

    //***************************************************************//
    // BE CAREFUL THIS TRAIT USE $this->request for flashing session //
    //***************************************************************//

    public static function setNewToken($user, $isForNewEmail = false) {
        //Chaine random du Token
        $token = str_random(60);

        !$isForNewEmail ? $user->confirmed = false : null;
        $user->confirmationToken = $token;
        $user->save();

        self::sendToken($user, $isForNewEmail);
    }

    public static function sendToken($user, $isForNewMail) {
        //Envoi du mail de confirmation
        $user->notify(new SendToken($isForNewMail));
    }

    public function resendToken(){
        $user = auth()->user();
        if(!$user->confirmed) {
            self::sendToken($user);
        }
        return redirect()->back()->with('info', trans('strings.auth_register_resend_token'));
    }

    public function accountConfirm($id, $token){
        try {
            $user = User::findOrFail($id);
            if ($user->confirmed == true) {
                if(!is_null($user->new_email)){
                    return redirect(route('validChangeEmail', ['id' => $id, 'token'=>$token]));
                } else {
                    return redirect(route('home'))->with('info',trans('strings.auth_register_account_already_confirm'));
                }
            } elseif ($token == $user->confirmationToken) {
                $user->confirmed = true;
                $user->confirmationToken = '';
                $user->save();
                auth()->login($user);
                return redirect(route('home'))->with('success', trans('strings.auth_register_account_confirm'));
            } else {
                throw new ModelNotFoundException('');
            }
        } catch(ModelNotFoundException $e) {
            return redirect(route('home'))->with('error', trans('strings.auth_register_invalid_link'));
        }
    }

    public function changeEmail(){
        return view('auth.email.change');
    }

    public function changeEmailPost(){
        $email = $this->request->filled('email') ? filter_var($this->request->email, FILTER_VALIDATE_EMAIL) : null;

        $user = auth()->user();
        $originalMail = $user->email;
        $alreadyExist = User::where('id', '<>', $user->id)->where('email', $email)->count();

        if ($email == $originalMail) {
            return redirect()->back()->withErrors(trans('strings.auth_email_change_is_same'));
        } elseif($alreadyExist) {
            return redirect()->back()->withErrors(trans('strings.auth_email_change_already_exist'));
        } else {
            $user->new_email = $email;
            $user->save();
            self::setNewToken(auth()->user(), true);
            return redirect(route('home'))->with('status',trans('strings.auth_email_change_send_token'));
        }

    }

    public function validChangeEmail() {
        $user = auth()->user();
        $id = $this->request->filled('id') ? $this->request->id : null;
        $token = $this->request->filled('token') ? $this->request->token : null;

        if(!is_null($id) && !is_null($token) && (int)$id==$user->id && $token = $user->confirmationToken){
            $user->email = $user->new_email;
            $user->confirmationToken = '';
            $user->save();
            return redirect(route('home'))->with('success', trans('strings.auth_email_change_success'));
        }
        return redirect(route('home'))->withErrors(trans('strings.auth_register_invalid_link'));
    }

    protected function create(array $data)
    {
        $lat = 0;
        $lng = 0;
        $geoloc = GeoUtils::getGeoLocByIp(config('runtime.ip'));
        if($geoloc){
            $lat = $geoloc[0];
            $lng = $geoloc[1];
        }

        //set session subscribeNewsLetter for UserObserver created event
        if(key_exists('subscribeNewsLetter', $data) && $data['subscribeNewsLetter']==true){
            session(['subscribeNewsLetter' => true]);
        }

        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'locale' => \Locale::acceptFromHttp($this->request->server('HTTP_ACCEPT_LANGUAGE')),
            'currency' => config('runtime.currency'),
            'latitude' => $lat,
            'longitude' => $lng
        ]);

        self::setNewToken($user);

        return $user;
    }

    protected function findOrCreateUser($user, $provider)
    {
        $providers = config('providers_login');
        if(in_array($provider, $providers) && !is_null($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)){
            $keyId = $provider.'_id';
            $authUser = User::where($keyId, $user->id)->first();
            if ($authUser){
                //update infos
                $existEmail = User::where('email', '=' , $user->email)->where($keyId, '<>', $user->id)->count();
                $existEmail==0 ?
                    $authUser->email = $user->email
                    : $this->request->session()->flash('warning',trans('strings.auth_email_provider_evolution_fail', ['provider' => $provider, 'newmail' => $user->email])) ;
                $authUser->avatar = $user->avatar;
                $authUser->save();
                return $authUser;
            }

            if($user->email && $user->email != '') {
                $existEmail = User::where('email', '=' , $user->email)->first();
                if($existEmail) {
                    $refOauth = $existEmail->oAuthProvider($providers);
                    if($refOauth == '') {
                        $refOauth = 'un autre utilisateur';
                    }
                    return redirect(route('login'))->with('error', trans('strings.auth_register_already_exist_account') . $refOauth);
                }
            }

            try {
                $lat = 0;
                $lng = 0;
                $geoloc = GeoUtils::getGeoLocByIp(config('runtime.ip'));
                if($geoloc){
                    $lat = $geoloc[0];
                    $lng = $geoloc[1];
                }

                $newUser =  User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    $keyId => $user->id,
                    'avatar' => $user->avatar,
                    'confirmed' => true,
                    'locale' => \Locale::acceptFromHttp($this->request->server('HTTP_ACCEPT_LANGUAGE')),
                    'currency' => config('runtime.currency'),
                    'latitude' => $lat,
                    'longitude' => $lng
                ]);
                $this->isNewOauthUser = true;
                return $newUser;
            } catch (\Exception $e) {
                return redirect(route('login'))->with('error', trans('strings.auth_register_register_error'));
            }
        } else {
            return redirect(route('login'))->with('error', trans('strings.auth_register_global_error'));
        }
    }
}