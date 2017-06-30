<?php

namespace App\Http\Middleware;

use Closure;

class StopAnalytics
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

        config(['runtime.stopAnalytics' => true]);
        return $next($request);

    }
}
