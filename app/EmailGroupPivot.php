<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailGroupPivot extends Model
{
    public $incrementing = true;
    protected $table = 'email_email_group';

    public function email(){
        return $this->belongsTo(Email::class);
    }
}
