<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
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

        $requestLocale = \Locale::getPrimaryLanguage(\Locale::acceptFromHttp($request->server('HTTP_ACCEPT_LANGUAGE')));
        if(auth()->check()) {
            $authLocale = \Locale::getPrimaryLanguage(auth()->user()->locale);
            if(in_array($authLocale,config('app.locales'))){
                App::setLocale($authLocale);
            } else {
                App::setLocale(config('app.fallback_locale'));
            }
        } else {
            if(in_array($requestLocale,config('app.locales'))){
                App::setLocale($requestLocale);
            } else {
                App::setLocale(config('app.fallback_locale'));
            }
        }

        return $next($request);
    }
}
