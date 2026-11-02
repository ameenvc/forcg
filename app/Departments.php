<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Departments extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'departmentname',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [

    public function department()
    {
        return $this->hasMany('App\Division', 'id', 'division_id');
    }
    public function locations()
    {
        return $this->morphMany('App\Location', 'locationable');
    }

    /**
     * Get active department
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
