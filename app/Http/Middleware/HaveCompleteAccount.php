<?php

namespace App\Http\Middleware;

use Closure;

class HaveCompleteAccount
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

        if(auth()->check()) {

            $user = auth()->user();
            if(
                $user->name != null
                && $user->compagnyName != null
                && $user->registrationNumber != null
                && strlen($user->name) >= config('db_limits.users.minName')
                && strlen($user->compagnyName) >= config('db_limits.users.minCompagnyName')
                && strlen($user->registrationNumber) >= config('db_limits.users.minRegistrationNumber')
            ) {
                return $next($request);
            }
        }

        return redirect()->back()->withErrors(trans('strings.middleware_complete_account'));

    }
}
