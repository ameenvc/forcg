<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Document extends Model
{
    protected $table = "document";
    protected $fillable = [
                            'dcrs_id',
                            'doc_id', 
                            'department', 
                            'doctype',
                            'name', 
                            'rid',
                            'edocname', 
                            'edocnumber', 
                            'erevdate', 
                            'erevnumber', 
                            'nrevnumber', 
                            'ndocname',
                            'ndocnumber', 
                            'nrevdate', 
                            'status', 
                            'reviewed_at'
                          ];
    protected $casts = [
        'reviewed_at' => "date:d-m-Y h:i:s A"
    ];
    protected $appends = ["reviewed_at"];
    public function getReviewedAtAttribute($date)
    {
        if ($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s A');
        }
    }
}
