<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreVisitDetail extends Model
{
    protected $fillable = [
      'store_id',
      'it_personnel',
      'status_id',
      'start_date',
      'end_date',
        'logged_by'
    ];

    public function status(){
        return $this->belongsTo(\App\Status::class);
    }

    public function itPersonnel(){
        return $this->belongsTo(\App\User::class,'it_personnel');
    }

    public function store(){
        return $this->belongsTo(\App\Store::class);
    }
}
