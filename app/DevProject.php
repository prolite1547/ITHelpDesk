<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DevProject extends Model
{
    protected $fillable = [
        'project_name',
        'assigned_to',
        'status',
        'date_start',
        'date_end',
        'md50_status'
    ];
}
