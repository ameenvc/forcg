<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dochistory extends Model
{
    protected $table = "dochistory";
    protected $fillable = [
      'document_id',
      'count',
      'request',
      'docname',
      'erevnumber',
      'docno',
      'nrevnumber',
      'erevdate',
      'nrevdate',
      'reason',
      'department',
      'reqdate',
      'approvedate',
      'ch_approved',
      'year'
    ];
    protected $casts = [
        'reqdate' => "date:d-m-Y h:i:s A",
        'approvedate' => "date:d-m-Y h:i:s A",

    ];
    protected $appends = ["reqdate", "approvedate"];
    public function getReqdateAttribute($date)
    {
        if($date != null) {
           return Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d h:i:s', strtotime($date)))->format('d-m-Y h:i:s A');
        }
    }
    public function getApprovedateAttribute($date)
    {
        if($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d h:i:s', strtotime($date)))->format('d-m-Y h:i:s A');
        }
    }

}
