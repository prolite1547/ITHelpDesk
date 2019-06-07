<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fName', 'mName', 'lName', 'email', 'password', 'uname', 'position_id', 'role_id', 'store_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['full_name', 'group'];

    public  function call(){
        return $this->morphMany('App\Call','caller');
    }

    public function role()
    {
        return $this->belongsTo('App\Role')->withDefault(['role' => null]);
    }

    public function department(){
        return $this->belongsTo('App\Department');
    }

    public function position()
    {
        return $this->belongsTo('App\Position')->withDefault(['position' => null]);
    }

    public function ticketMessage()
    {
        return $this->hasMany('App\Message');
    }

    public function resolved()
    {
        return $this->hasMany('App\Resolve', 'resolved_by');
    }

    public function profpic()
    {
        return $this->hasOne('App\ProfPic');
    }

    public function ticketLogged()
    {
        return $this->hasMany('App\Ticket', 'logged_by');
    }

    public function getFullNameAttribute()
    {
        $middle_name = $this->mName;

        if (is_null($middle_name) || $middle_name === '') {
            return ucfirst($this->fName) . ' ' . ucfirst($this->lName);
        } else {
            return ucfirst($this->fName) . ' ' . $middle_name . ' ' . ucfirst($this->lName);
        }
    }

    public function getGroupAttribute()
    {
        if (strtolower($this->role->role) === 'admin') {
            return false;
        } else {
            switch (true) {
                case strpos(strtolower($this->position->position), 'support') !== false:
                    return 1;
                case strpos(strtolower($this->position->position), 'technical') !== false:
                    return 2;
                case strpos(strtolower($this->position->position),'system') !== false:
                    return 3;
                default:
                    return 0;
            }
        }
    }


    public function assignedTickets()
    {
        return $this->hasMany('App\Ticket', 'assignee', 'id');
    }

    public function isDateBetween($start, $end, $created_at)
    {

        if (($created_at >= $start) && ($created_at <= $end)) {
            return true;
        } else {
            return false;
        }

    }

    public function setfNameAttribute($value)
    {
        return $this->attributes['fName'] = cleanInputs($value);
    }

    public function setmNameAttribute($value)
    {
        return $this->attributes['mName'] = cleanInputs($value);
    }

    public function setlNameAttribute($value)
    {
        return $this->attributes['lName'] = cleanInputs($value);
    }

    public function userable()
    {
        return $this->morphTo();
    }

    public function fixedTicket(){
        return $this->hasMany('App\Fix','fixed_by');
    }

}
