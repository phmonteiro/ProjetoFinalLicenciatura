<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportSummary extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'support_summaries';
}
