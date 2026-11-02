<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ohsrisk extends Model
{
    protected $table = "ohsrisk";
    protected $filalble = [
        'ohs_id',
        'riskid',
        'department',
        'risk_status',
        'context_id',
        'active',
        'category',
        'R_NR_EM',
        'hazards',
        'affect',
        'control',
        'OrS',
        'OrL',
        'OrR',
        'additional',
        'RS',
        'RL',
        'RR',
        'monitor',
        'proc_no',
        'procedur',
        'created_at',
        'reviewed_at',
        'approvedby',
        'time'
    ];
    public function dept()
    {
        return $this->hasOne('App\Departments', 'id', 'department');
    }
    public function ohs()
    {
        return $this->hasOne('App\Ohs', 'id', 'ohs_id');
    }

    public function getReviewedAtAttribute($date)
    {
        if ($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($date)))->format('d-m-Y h:i:s A');
        }
    }

}
