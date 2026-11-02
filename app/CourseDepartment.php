<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseDepartment extends Model
{
    protected $fillable = [
        'courses_id',
        'departments_id'
    ];

    public function department()
    {
        return $this->belongsTo('App\Departments', 'departments_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo('App\Course', 'courses_id', 'id');
    }
}
