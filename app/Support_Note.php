<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Support_Note extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'support_notes';
}
