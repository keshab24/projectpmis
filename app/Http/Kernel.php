<?php

namespace PMIS\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];


    protected $middlewareGroups = [
        'web' => [
            \PMIS\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \PMIS\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];
    
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \PMIS\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \PMIS\Http\Middleware\RedirectIfAuthenticated::class,
        'check.client' => \PMIS\Http\Middleware\CheckClientExists::class,
        'officeUsers' => \PMIS\Http\Middleware\OfficeUsers::class,
        'api_request' => \PMIS\Http\Middleware\ApiRequests::class,
        'request.log' => \PMIS\Http\Middleware\RequestLogger::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'user.status' => \PMIS\Http\Middleware\CheckUserStatus::class,
    ];
}
