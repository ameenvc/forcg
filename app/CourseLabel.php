<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseLabel extends Model
{
    protected $fillable = [
        'courses_id',
        'label',
        'is_deleted'
    ];

    public function course()
    {
        return $this->belongsTo('App\Course', 'courses_id', 'id');
    }
}
