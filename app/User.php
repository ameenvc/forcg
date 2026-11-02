<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','employeeid','email_verified_at','profile_picture', 'is_email_verified', 'type', 'approved', 'deptid', 'employeeid', 'logdevices'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['avatar_path'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function user()
    {
        return $this->hasMany('App\Issue','postedby', 'employeeid');
    }
    public function gates()
    {
        return $this->hasMany('App\UserGate', 'users_id', 'id');
    }

    /**
     * @return string
     */
    public function getAvatarPathAttribute(): string
    {
        if ($this->profile_picture && file_exists(public_path('/storage/profile/'. $this->profile_picture))) {
            return asset("storage/profile/". $this->profile_picture);
        }

        return asset('/images/avatar10.jpg');
    }

    public function addCalibrationGate()
    {
        return $this->hasOne('App\UserGate', 'users_id', 'id')->where('key', 'add-calibration');
    }
    public function department(){
        return $this->belongsTo('App\Departments','deptid','departmentname');
    }
}
