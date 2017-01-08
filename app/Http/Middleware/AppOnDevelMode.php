<?php

namespace App\Http\Middleware;

use Closure;

class AppOnDevelMode
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

        if(env('APP_MODE')=='devel') {
            return $next($request);
        }

        return redirect()->route('home')->withErrors(trans('strings.middleware_admin'));

    }
}
