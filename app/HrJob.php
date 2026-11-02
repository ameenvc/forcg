<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HrJob extends Model
{
    protected $table = "hr_job";
    protected $fillable = [
       'position_title',
       'reports_to',
       'dept',
       'job_code',
       'responsibilities',
       'job_authorities',
       'accountabilities',
       'hse',
       'internal',
       'external',
       'min_qual',
       'training_skills',
       'min_exp',
       'prepared_on',
       'added_by',
       'created_at'
    ];
    public function department()
    {
        return $this->hasOne('App\Departments', 'id', 'dept');
    }
}
