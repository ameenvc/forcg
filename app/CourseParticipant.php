<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseParticipant extends Model
{
     protected $fillable = [
      'email',
      'course_id',
      'duration',
      'progress_percentage',
      'score'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }
}
