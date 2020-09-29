<?php

namespace PMIS\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

    protected function redirectTo($request)
    {
        if (!$request->expectsJson() || $request->ajax()) {
            return 'auth/login';
        }
    }
}