<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    //
    //use SoftDeletes;
    //protected $dates = ['deleted_at'];
    public function ctype()
    {
        return $this->belongsTo(ClassType::class,'type', 'type');
    }
}
