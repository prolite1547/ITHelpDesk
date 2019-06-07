<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailGroup extends Model
{
    protected $fillable = ['group_name'];

    public function emails(){
        return $this->belongsToMany(\App\Email::class);
    }
}
