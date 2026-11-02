<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modpageload extends Model
{

    protected $table = "modpageload";
    protected $fillable = [
       'correctiveaction',
       'riskmanagement',
       'objectives',
       'opportunity',
       'compliance',
       'enviaspects',
       'auditmanagement',
       'ohsrisk',
       'documentmanagement',
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
