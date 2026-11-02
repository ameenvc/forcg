<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementReviewScheduleCorrective extends Model
{
     protected $table='management_review_schedule_correctives';
     protected $fillable = ['schedule_id','corrective_id'];
}
