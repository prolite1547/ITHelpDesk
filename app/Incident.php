<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{

    protected $fillable = [
        'call_id',
        'subject',
        'details',
        'category',
        'catA',
        'catB',
        'catC',
        'expiration',
        'drd',
    ];

    public function incident(){
        return $this->morphTo();
    }

    public function ticket(){
        return $this->morphOne(\App\Ticket::class,'issue');
    }

    public function categoryRelation(){
        return $this->belongsTo('App\Category','category')->withDefault(['name' => 'none']);
    }

    public function catARelation(){
        return $this->belongsTo('App\CategoryA','catA')->withDefault(['name' => 'none']);;
    }

    public function catBRelation(){
        return $this->belongsTo('App\CategoryB','catB')->withDefault(['name' => 'none','id' => 'none']);;
    }

    public function catCRelation(){
        return $this->belongsTo('App\CategoryC','catC')->withDefault(['name' => 'none','id' => 'none']);
    }


    public function getFiles(){
        return $this->hasMany('App\File');
    }

    public function getDetailsAttribute($value)
    {
        return nl2br($value);
    }


}
