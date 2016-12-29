<?php

namespace App\Http\Middleware;

use App\Common\LocaleUtils;
use Closure;

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
        LocaleUtils::setAppLocale($runTimeLocale);

        return $next($request);
    }
}
