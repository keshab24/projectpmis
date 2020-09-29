<?php

namespace PMIS\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class OfficeUsers
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $officeUser=$this->auth->user()->type_flag == 3 || $this->auth->user()->type_flag==2 || $this->auth->user()->type_flag==1 || $this->auth->user()->type_flag == 5?true:false;
        if (!$officeUser) {
            Auth::logout();
            return redirect()->back()->withErrors(['You are not authorized to login via web, Try using Mobile application.']);
        }
        return $next($request);
    }
}
