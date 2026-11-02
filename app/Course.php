<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
      'name',
      'cover_image',
      'published',
      'duration',
      'like_count'
    ];

    public function departments()
    {
        return $this->belongsToMany('App\Departments', 'course_departments', 'courses_id', 'departments_id');
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable')->where('is_deleted', false)->where('parent_id', 0);
    }

    public function replies()
    {
        return $this->morphMany('App\Comment', 'commentable')->where('is_deleted', false)->where('parent_id', '!=', 0);
    }


}
