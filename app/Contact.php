<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'studentEmail', 'date', 'service', 'decision', 'information', 'nextContact','contactMedium','software','place'
    ];
}
