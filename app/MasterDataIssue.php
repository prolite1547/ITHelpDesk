<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterDataIssue extends Model
{
    protected $fillable = [
        'issue_name',
        'status',
        'start_date',
        'end_date',
        'logged_by'
    ];
}
