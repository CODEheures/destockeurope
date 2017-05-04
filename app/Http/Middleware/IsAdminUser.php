<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class IsAdminUser
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

        if(auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN]) {
            return $next($request);
        }

        return redirect()->route('home')->withErrors(trans('strings.middleware_admin'));

    }
}
