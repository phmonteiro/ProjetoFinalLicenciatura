<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_Supports extends Model
{
    public $timestamps = false;
    protected $table = "student_supports";

    public function support()
    {
        return $this->belongsTo('App\Supports', 'support_value',  'value');
    }
}

