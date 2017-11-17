<?php

namespace App\Http\Middleware;

use App\Helpers\AuthCheck;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class changePassword
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
        if(AuthCheck::passwordChange())
        {
            return $next($request);
        }
        if(Auth::check())
        {
            Session::flash('warning','Kindly Change Your Password');
        }
        return redirect()->action('ActivityController@changePassword');
    }
}
