<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    protected $fillable = [
        'course_id',
        'email',
        'score'
    ];

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }
}
