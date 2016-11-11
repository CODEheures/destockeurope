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

trait CreateUser {


    public static function setNewToken($user) {
        //Chaine random du Token
        $token = str_random(60);

        $user->confirmed = false;
        $user->confirmationToken = $token;
        $user->save();

        self::sendToken($user);
    }

    public static function sendToken($user) {
        //Envoi du mail de confirmation
        $user->notify(new SendToken());
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
                return redirect('/')->with('info',trans('strings.auth_register_account_already_confirm'));
            } elseif ($token == $user->confirmationToken) {
                $user->confirmed = true;
                $user->confirmationToken = '';
                $user->save();
                auth()->login($user);
                return redirect('/')->with('success', trans('strings.auth_register_account_confirm'));
            } else {
                throw new ModelNotFoundException('');
            }
        } catch(ModelNotFoundException $e) {
            return redirect('/')->with('error', trans('strings.auth_register_invalid_link'));
        }
    }

    protected function create(array $data)
    {
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'locale' => \Locale::acceptFromHttp($this->request->server('HTTP_ACCEPT_LANGUAGE'))
        ]);

        self::setNewToken($user);

        return $user;
    }

    protected function findOrCreateUser($user, $provider)
    {
        $providers = config('providers_login');
        if(in_array($provider, $providers)){
            $keyId = $provider.'_id';
            $authUser = User::where($keyId, $user->id)->first();
            if ($authUser){
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
                $newUser =  User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    $keyId => $user->id,
                    'avatar' => $user->avatar,
                    'confirmed' => true,
                    'locale' => \Locale::acceptFromHttp($this->request->server('HTTP_ACCEPT_LANGUAGE'))
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