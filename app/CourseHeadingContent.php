<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseHeadingContent extends Model
{
    protected $fillable = [
        'course_headings_id',
        'name',
        'rich_text',
        'duration',
        'is_deleted',
    ];


    public function courseHeading()
    {
        return $this->belongsTo('App\CourseHeading', 'course_headings_id', 'id')->where('is_deleted', false);
    }

    public function courseHeadingContentUser()
    {
        return $this->hasMany('App\CourseHeadingContentUser', 'course_heading_content_id', 'id');
    }

}
