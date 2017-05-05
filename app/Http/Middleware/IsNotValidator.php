<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class IsNotValidator
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

        if(auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_VALIDATOR]) {
            return redirect()->route('home')->withErrors(trans('strings.middleware_admin'));
        }
        return $next($request);

    }
}
