<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Contracts\Auth\Guard;

class IsNotOauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->only('email') && $request->only('email') != '') {
            $user = User::where('email', '=', $request->email)->first();
            if($user) {
                $oAuth = $user->oAuthProvider(config('providers_login'));
                if($oAuth && $oAuth != '') {
                    return redirect(route('login'))->with('info', trans('strings.auth_register_open_by_provider', ['provider' => $oAuth]));
                }
            }
        }



        return $next($request);
    }
}
