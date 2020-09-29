<?php

namespace PMIS\Http\Middleware;

use Closure;
use proFirmDarta\User;

class RequestLogger
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
            $name = 'request.log';
            $fp = fopen($name, 'a');
            $writableArray = $request->all();
            $writableArray['method']=$request->method();
            $writableArray['url']=$request->url();
            fwrite($fp, json_encode($writableArray));
            fwrite($fp, "\n");
            fclose($fp);
            return $next($request);
    }

}
