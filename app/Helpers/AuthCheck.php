<?php
/**
 * Created by PhpStorm.
 * User: micheal
 * Date: 10/12/17
 * Time: 12:01 PM
 */

namespace App\Helpers;


use Illuminate\Support\Facades\Auth;

class AuthCheck
{
    public static function Staff()
    {
        if(Auth::check() && Auth::user()->role_id === 3)
        {
            return true;
        }
        return false;
    }

    public  static  function SuperAdmin()
    {
        if(Auth::check() && Auth::user()->role_id == 1 && Auth::user()->access)
        {
            return true;
        }
        return false;
    }

    public static function Admin()
    {
        if(Auth::check() && Auth::user()->role_id < 3 && Auth::user()->access)
        {
            return true;
        }
        return false;
    }

    public static function Guest()
    {
        if(Auth::check() && Auth::user()->role_id == 4 && Auth::user()->access)
        {
            return true;
        }
        return false;
    }

    public static function AllUser()
    {
        if(Auth::check() && Auth::user()->access)
        {
            return true;
        }
        return false;

    }

    public static function passwordChange()
    {
        if(Auth::user()->password_change)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}