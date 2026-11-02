<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidentCorrective extends Model
{
    protected $table = 'incident_corrective';
    protected $fillable=['count','issueid','incident_reportno',
                         'corrective_id','category','year', 'file_name'
                        ];
}
