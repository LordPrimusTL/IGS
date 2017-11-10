<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //


    public static function GeneratePaymentID()
    {
        $t_id = str_random(20);
        $checkTid = ErrorLogger::where(['error_id' => $t_id])->get();
        if(count($checkTid) > 0)
        {
            self::GeneratePaymentID();
        }
        else{
            return $t_id;
        }
    }

    public function stud()
    {
        return $this->belongsTo(Student::class, 'id');
    }

    public function sess()
    {
        return $this->belongsTo(SchoolSession::class, 'sess_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class,'term_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'c_id');
    }

    public function list()
    {
        return $this->belongsTo(PaymentList::class,'pl_id');
    }




}
