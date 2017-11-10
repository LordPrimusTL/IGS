<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudStatus extends Model
{
    //

    public function student()
    {
        return $this->hasMany(Student::class,'s_id');
    }
}
