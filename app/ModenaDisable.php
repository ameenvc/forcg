<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModenaDisable extends Model
{
    
    protected $table = "modenadisable";
    protected $fillable = [
        'objectives',
        'auditmanagement',
        'compliance',
        'riskmanagement',
        'opportunity',
        'documentmanagement',
        'enviaspects',
        'ohsrisk',
        'correctiveaction', 
        'auditschedulelogs', 
        'complaintmanagement',
        'contextmanagement', 
        'vendormanagement', 
        'incidentreport', 
        'hse', 
        'hr', 
        'maintainance', 
        'calibration', 
        'occhealth', 
        'wastemanagement', 
        'nearmiss',
        'managementreview'
    ];
}
