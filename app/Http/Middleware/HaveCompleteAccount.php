<?php

namespace App\Http\Middleware;

use App\Common\UserUtils;
use Closure;

class HaveCompleteAccount
{

    use UserUtils;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if($this->haveCompleteAccount()){
            return $next($request);
        } else {
            return redirect()->back()->withErrors(trans('strings.middleware_complete_account'));
        }
    }
}
