<?php

namespace App\Http\Middleware\Privileges;

use App\Common\PrivilegesUtils;
use Closure;

class CanManageMyAccount
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

        if(PrivilegesUtils::canManageMyAccount()) {
            return $next($request);
        }

        return $request->isXmlHttpRequest() || $request->ajax() || $request->isJson() ?
            response(trans('strings.middleware_admin'),401) :
            redirect()->route('home')->withErrors(trans('strings.middleware_admin'));

    }
}
