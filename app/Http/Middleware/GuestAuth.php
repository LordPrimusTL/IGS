<?php

namespace App\Http\Middleware;

use App\Helpers\AuthCheck;
use Closure;
use Illuminate\Support\Facades\Session;

class GuestAuth
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
        if(AuthCheck::Staff())
        {
            return $next($request);
        }
        Session::flash('error','Please Try Again...');
        return redirect()->action('AccountController@Login');
    }
}
