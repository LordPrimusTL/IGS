<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassType extends Model
{
    //
    public function cclass()
    {
        return $this->hasMany(SchoolClass::class,'type');
    }
}
