<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseHeading extends Model
{
    protected $fillable = [
        'courses_id',
        'heading',
        'duration',
        'is_deleted'
    ];

    public function course()
    {
        return $this->belongsTo('App\Course', 'courses_id', 'id');
    }
}
