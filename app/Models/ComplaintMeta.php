<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintMeta extends Model
{
    protected $fillable = [
        'complaint_id',
        'meta_key',
        'meta_value'
    ];

    public function complaint()
    {
        return $this->belongsTo('App\Complaint', 'complaint_id', 'id');
    }
}
