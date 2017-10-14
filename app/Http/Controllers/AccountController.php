<?php

namespace App\Http\Controllers;

use App\Helpers\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    //
    public function getLogger()
    {
        return new Logger();
    }
    public function Login()
    {
        Auth::logout();
        return view('Account.login',['title' =>  'Login']);
    }

    public function LoginPost(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required'
        ]);
        //dd($request->all());
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            //dd(Auth::user());
            if(Auth::user()->access)
            {
                $this->getLogger()->LogInfo('Login Successful',null);
                Session::flash('welcome',true);
                if(Auth::user()->role_id < 3)
                {
                    return redirect()->action('ActivityController@ViewUsers');
                }
                return redirect()->action('ActivityController@ViewStudent');
            }
            else{
                $this->getLogger()->LogInfo('Access Not granted to User',['reg' => $request->all()]);
                Session::flash('error','Access Not Granted, Please Contact Admin');
            }
        }
        else{
            Session::flash('error','Incorrect Email/Password.');
            $this->getLogger()->LogInfo('Incorrect Email/Password',['req' => $request->all()]);
            Auth::logout();
        }
        return redirect()->back();
    }

    public function Logout()
    {
        Session::flash('success','You Have Successfully Logged Out.');
        Auth::logout();
        return redirect()->action('AccountController@Login');
    }
}
