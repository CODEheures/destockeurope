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
        $user = null;
        if($request->email && $request->email != '') {
            $user = User::where('email', '=', $request->email)->first();
        } elseif (auth()->check()) {
            $user = auth()->user();
        }
        if($user) {
            if($user) {
                $oAuth = $user->oAuthProvider(config('providers_login'));
                if($oAuth && $oAuth != '') {
                    if(auth()->check()){
                        return redirect()->back()->with('info', trans('strings.auth_register_open_by_provider', ['provider' => $oAuth]));
                    } else {
                        return redirect(route('login'))->with('info', trans('strings.auth_register_open_by_provider', ['provider' => $oAuth]));
                    }
                }
            }
        }



        return $next($request);
    }
}
