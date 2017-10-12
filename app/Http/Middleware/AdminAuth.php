<?php

namespace App\Http\Middleware;

use App\Helpers\AuthCheck;
use Closure;
use Illuminate\Support\Facades\Session;

class AdminAuth
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
        if(AuthCheck::Admin())
        {
            return $next($request);
        }
        Session::flash('error','Please Login...');
        return redirect()->action('AccountController@Login');
    }
}
