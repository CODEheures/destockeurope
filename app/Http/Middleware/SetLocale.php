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
        $runTimeLocale = \Locale::getPrimaryLanguage(config('runtime.locale'));
        if(in_array($runTimeLocale,config('app.locales'))){
            App::setLocale($runTimeLocale);
        } else {
            App::setLocale(config('app.fallback_locale'));
        }


        return $next($request);
    }
}
