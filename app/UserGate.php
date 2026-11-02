<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGate extends Model
{
    protected $fillable = [
        'key',
        'users_id',
        'saver_users_id'
    ];
    public function user()
    {
        return $this->hasOne('App\User' , 'id', 'users_id');
    }
    public function saverUser()
    {
        return $this->hasOne('App\User' , 'id', 'saver_users_id');
    }
    public function admin()
    {
        return $this->hasOne('App\Admin', 'id', 'users_id');
    }
}
