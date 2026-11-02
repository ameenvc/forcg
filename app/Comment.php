<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=[
    	'user_id',
        'parent_id',
        'comment',
        'commentable_id',
        'commentable_type'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo('App\Admin', 'user_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany('App\Comment', 'parent_id', 'id')->where('is_deleted', false);
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}
