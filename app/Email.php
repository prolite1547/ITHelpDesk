<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = ['email','user_id'];
    public function groups(){
        return $this->belongsToMany(\App\EmailGroup::class);
    }
}
