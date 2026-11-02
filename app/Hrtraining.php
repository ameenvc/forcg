<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hrtraining extends Model
{
    protected $table = "hr_training";
    protected $fillable = [
        'emp_name',
        'emp_number',
        'emp_department',
        'position',
        'training_title',
        'training_type',
        'inhouse_outside',
        'training_provider',
        'training_agency',
        'training_duration',
        'periods',
        'benefits',
        'status',
        'evaluatedby',
        'evaluated_at',
        'created_at',
        'posted_id','employee_id'
    ];

  public function postedByHrtraining()
   {
       return $this->belongsTo('App\User','posted_id','employeeid');
   }
  
}
