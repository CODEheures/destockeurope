<?php

namespace App\Http;

use App\Http\Middleware\IsAdminUser;
use Codeheures\LaravelUtils\Http\Middleware\RuntimeIp;
use Codeheures\LaravelUtils\Http\Middleware\RuntimeLocale;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Codeheures\LaravelUtils\Http\Middleware\RuntimeIp::class,
            \Codeheures\LaravelUtils\Http\Middleware\RuntimeLocale::class,
            \App\Http\Middleware\GetConfig::class,
            \App\Http\Middleware\SetLocale::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],

        'fw-block-bl' => [
            \PragmaRX\Firewall\Middleware\FirewallBlacklist::class,
        ],
        'fw-allow-wl' => [
            \PragmaRX\Firewall\Middleware\FirewallWhitelist::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'isAdminUser' => \App\Http\Middleware\IsAdminUser::class,
        'isNotDelegationUser' => \App\Http\Middleware\IsNotDelegationUser::class,
        'appOnDevelMode' => \App\Http\Middleware\AppOnDevelMode::class,
        'haveCompleteAccount' => \App\Http\Middleware\HaveCompleteAccount::class,
        'captcha' => \App\Http\Middleware\Captcha::class,
        'isNotOauth' => \App\Http\Middleware\IsNotOauth::class,
        'isEmailConfirmed' => \App\Http\Middleware\IsEmailConfirmed::class,
    ];
}
