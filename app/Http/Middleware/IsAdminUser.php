<?php

namespace App\Http\Middleware;

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

        if(auth()->check() && auth()->user()->role == 'admin') {
            return $next($request);
        }

        return redirect()->route('home')->withErrors(trans('strings.middleware_admin'));

    }
}
