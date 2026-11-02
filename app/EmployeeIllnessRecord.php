<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeIllnessRecord extends Model
{
    protected $table = 'employee_illness_record';
    protected $fillable = [
                            'e_number',
                            'e_name',
                            'category_id',
                            'dob',
                            'department',
                            'position',
                            'dateofillness',
                            'timeofillness',
                            'ill_type',
                            'workrelated_illness',
                            'nonworkrelated_illness',
                            'nonworkrelated_illness_other',
                            'natureofillness',
                            'location',
                            'causeofillness',
                            'symptoms',
                            'things_involved',
                            'things_involved_details',
                            'pandamic',
                            'pandamic_details',
                            'pandemic_contacts',
                            'witness',
                            'reported_to_supervisor',
                            'treatment_provided',
                            'treatment_details',             
                            'treatmentdetails_other',
                            'illed_returnto_work',
                            'illed_returnto_work_details',
                            'creator_name',
                            'creator_email',
                            'creator_position',
                            'creator_department',
                            'created_date',
                            'status',
                            'medical_leaves',
                            'illed_bp_diastolic',
                            'illed_bp_systolic',
                            'illed_bs_level',
                            'remarks',
                            'pulse',
                            'temperature',
                            'respiratory_rate',
                            'weight',
                            'height',
                            'year'
                          ];

  public function illnessNature()
  {
      return $this->belongsTo('App\EmployeeHealthIllnessNature','natureofillness','id');
  }                      
}
