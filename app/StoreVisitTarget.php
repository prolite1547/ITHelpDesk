<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreVisitTarget extends Model
{
    protected $fillable = [
        'month',
        'year',
        'num_of_stores',
        'logged_by'
    ];
}
