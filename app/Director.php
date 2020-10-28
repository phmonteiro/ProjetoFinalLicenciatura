<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'directors';
}
