<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseHeadingContentUser extends Model
{
     protected $fillable = [
        'user_id',
        'course_heading_content_id'
    ];

    public function user()
    {
        return $this->belongsToMany('App\User', 'course_heading_content_users', 'course_heading_content_id', 'user_id');
    }

    public function courseHeadingContent()
    {
        return $this->belongsTo('App\CourseHeadingContent', 'course_heading_content_id', 'id');
    }
}
