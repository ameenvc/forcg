<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Masterdoc extends Model
{

    //public $timestamps = false;
    protected $fillable = [
      'document',
      'status',
      'hod',
      'docnumber',
      'type',
        'doctype_id',
      'level',
      'revnumber',
      'nrevnumber',
      'erevdate',
      'nrevdate',
      'updatedat',
      'createdat',
      'department',
      'reviewedat',
      'postedby',
      'affected',
      'reason',
      'ch_approved',
      'changedate',
      'removedate'
    ];
    protected $casts = [
        'created_at' => "date:d-m-Y h:i:s A",
        'updated_at' => "date:d-m-Y h:i:s A",
        'changedate' => "date:d-m-Y h:i:s A",
        'removedate' => "date:d-m-Y h:i:s A",
        'reviewedat' => "date:d-m-Y h:i:s A",
    ];
    protected $appends = ["created_at", "updated_at", "changedate", "removedate", "reviewedat"];
    public function getCreatedAtAttribute($date)
    {
        if($date != null) {
           return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s A');
        }
    }
    public function getUpdatedAtAttribute($date)
    {
        if($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s A');
        }
    }
    public function getChangedateAttribute($date)
    {
        if($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s A');
        }
    }
    public function getRemovedateAttribute($date)
    {
        if($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s A');
        }
    }
    public function getReviewedatAttribute($date)
    {
        if($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s A');
        }
    }
    public function departmentdetails(){
        return $this->belongsTo('App\Departments', 'department', 'id');
    }

    public function doctype()
    {
        return $this->belongsTo('App\Doctype', 'doctype_id', 'id');
    }
}
