<?php
/**
 * Created by PhpStorm.
 * User: micheal
 * Date: 10/12/17
 * Time: 12:25 PM
 */

namespace App\Helpers;


use App\ErrorLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Logger
{
    private function SaveError($error_id)
    {
        $ae = new ErrorLogger();
        $ae->error_id = 'Error - '. $error_id;
        $ae->save();
        if(AuthCheck::Admin())
        {
            Session::flash('error','minor error occured, Please check Log');
        }
        Log::info('New Error saved in database to be treated');
    }

    public function LogError($errormsg,$ex,$other){
        $error_id = $this->ErrorID();
        if($other == null)
        {
            Log::error($errormsg,['error_id'=>$error_id,'error'=> $ex->getMessage().$ex->getLine().$ex->getTraceAsString()]);
        }
        else{
            Log::error($errormsg,['error_id'=>$error_id,'error'=> $ex->getMessage().$ex->getLine().$ex->getTraceAsString(), $other]);
        }

        $this->SaveError($error_id);
    }

    private function ErrorID()
    {
        $t_id = str_random(20);
        if(ErrorLogger::findByT_ID($t_id))
        {
            return $t_id;
        }
        else
        {
            //var_dump(false);
            $this->ErrorID();
        }
        return $t_id;
    }

    public function LogInfo($msg,$other)
    {
        if($other == null)
        {
            Log::info($msg,[ 'By' => Auth::id()]);
        }
        else{
            Log::info($msg,[$other, 'By' => Auth::id()]);
        }
    }
}