<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dcr extends Model
{
    protected $fillable = [
                            'name', 'rid', 'doctype', 'doctype_id',
                            'type','affected','reason',
                            'edocname','edocnumber','erevdate',
                            'ndocname','ndocnumber','nrevdate',
                            'postedby','status','date',
                            'reviewed_at','count','doc_id',
                            'department','documents','hod'
                           ];
    protected $casts = [
        'date' => "date:d-m-Y h:i:s A",
        'reviewed_at' => "date:d-m-Y h:i:s A"
    ];
    protected $appends = ["date","reviewed_at"];
    public function getDateAttribute($date)
    {
        if($date != null) {
           return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s A');
        }
    }
    public function getReviewedAtAttribute($date)
    {
        if($date != null) {
           return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s A');
        }
    }

    public function dept()
    {
        return $this->belongsTo('App\Departments', 'department', 'id');
    }

    public function docType()
    {
        return $this->belongsTo('App\Doctype', 'doctype_id', 'id');
    }
}
