<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeInjuryRecord extends Model
{
    protected $table = 'employee_injury_record';
    protected $fillable = [
                            'category_id',
                            'e_number',
                            'e_name',
                            'dob',
                            'department',
                            'position',
                            'dateofinjury',
                            'timeofinjury',
                            'injury_type',
                            'workrelated_injury',
                            'workrelated_injury_other',
                            'nonwork_related_injury',
                            'natureofinjury',
                            'bodily_location',
                            'body_injured_other',
                            'location',
                            'causeofinjury',
                            'things_involved',
                            'things_involved_details',
                            'witness',
                            'reported_to_supervisor',
                            'treatment_provided',
                            'treatment_details',
                            'treatmentdetails_other',
                            'injured_returnto_work',
                            'creator_name',
                            'creator_email',
                            'creator_position',
                            'creator_department',
                            'created_date',
                            'status',
                            'injured_bp_diastolic',
                            'injured_bp_systolic',
                            'injured_bs_level',
                            'remarks',
                            'pulse',
                            'temperature',
                            'respiratory_rate',
                            'weight',
                            'height',
                            'year'
                          ];


  public function injuryNature()
  {
     return $this->belongsTo('App\EmployeeHealthInjuryNature','natureofinjury','id');
  }

}
