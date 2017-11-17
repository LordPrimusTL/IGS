<?php

namespace App\Http\Middleware;

use App\Helpers\AuthCheck;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AllAuth
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
        if(AuthCheck::AllUser())
        {
            return $next($request);
        }
        Session::flash('error','Please Try Again');
        return redirect()->action('AccountController@Login');
    }
}
