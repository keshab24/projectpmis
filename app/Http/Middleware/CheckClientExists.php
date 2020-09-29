<?php

namespace PMIS\Http\Middleware;

use Closure;

class CheckClientExists
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
        if(!session()->has(session()->get('website_info')->slug.'client_info')){
            return redirect()->route('user.login');
        }
        return $next($request);
    }
}
