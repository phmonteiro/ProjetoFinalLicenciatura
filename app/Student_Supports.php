<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_Supports extends Model
{
    public $timestamps = false;
    protected $table = "student_supports";
    public function supports()
    {
        return $this->hasMany('App\Supports');
    }
}
