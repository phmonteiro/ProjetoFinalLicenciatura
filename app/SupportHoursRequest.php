<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportHoursRequest extends Model
{
    public $timestamps = false;

    public function support()
    {
        return $this->belongsTo('App\User', 'studentEmail',  'email');
    }
}
