<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubComment extends Model
{
    protected $fillable=[
    	'user_id',
        'parent_id',
        'comment',
        'commentable_id',
        'commentable_type',
        'record_id'
    ];
    protected $attributes = [
        'read_by_user' => 0,
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
        return $this->hasMany('App\GovernanceHubComment', 'parent_id', 'id')->where('is_deleted', false);
    }

    public function commentable()
    {
        return $this->morphTo();
    }
    public function documentdetails()
    {
        return $this->belongsTo('App\GovernanceHub', 'commentable_id', 'id');
    }
}
