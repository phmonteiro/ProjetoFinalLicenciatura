<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseManager extends Model
{
    public $timestamps = false;

    public function enee(){
        return $this->hasMany('App\User', 'email', 'studentEmail');
    }
}
