<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investigator extends Model
{
    protected $table = 'investigators';
    protected $fillable = ['tleader','tmembers','targetdate',
                            'report_no','tleadername','tmembersnames'
                           ];

    public function incidentReport()
    {
        return $this->hasOne(IncidentReport::class, 'report_no', 'report_no');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'email', 'tleader');
    }
}
