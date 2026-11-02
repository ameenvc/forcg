<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintInvestigator extends Model
{
    protected $fillable = ['user_id', 'complaint_id'];
    public function complaint()
    {
        return $this->belongsTo('App\Complaint', 'complaint_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
