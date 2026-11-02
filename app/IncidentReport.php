<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    protected $table = 'incident_reports';
    protected $fillable=[
        'addedby','addeddate','department','area','report_no',
        'incident_type','injuredperson','empid','injured_person_position',
        'injured_person_dept','injured_person_oth_name','injured_person_oth_empid',
        'injured_person_oth_position','injured_person_oth_dept','hospital',
        'incidentplace','incidentdate','emplyoer','trade_or_occupation',
        'incidentdesc','actions','local_regulation','time','attachmentby_reporter',
        'reviewed_at','reviewed_by','reviewed','adder_email','justification',
        'eval',
        'pubrec',
        'probablity',
        'finloss',
        'efcom',
        'humtur',
        'potloss',
        'sevpotloss',
        'porbocc',
        'approver_name',
        'approver_email',
        'reviewer_name',
        'reviewer_email',
        'riskassessment',
        'riskassess',
        'toolbox','tooldate','training','traindate','autherised',
        'activity','investigatordisc','bodypart','unsafework','unsafeact',
        'ppkit','prevent','rootcause','corrective_act','currective_targdate',
        'responsiblity','severity','further_investigate','investigation_status',
        'workunsafe','actunsafe','attachmentby_reviewer','improve_justification','attachmentby_investigator',
        'approved_rej','rej_reason','corr_implement_status','corrective_issue_id',
        'corrective_id','any_person_injured',
        'division_id', 'area_id'
    ];

    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }
    public function subdiv()
    {
        return $this->hasOne('App\Area', 'id', 'area_id');
    }

    public function departmentOfIncident()
    {
        return $this->hasOne(Departments::class, 'id', 'department');
    }

    public function investigator()
    {
        return $this->hasOne(Investigator::class, 'report_no', 'report_no');
    }

    public function correctives()
    {
        return $this->hasMany(Corrective::class, 'incident_id', 'id');
    }

    public function injuredPersonOthDepartment()
    {
        return $this->hasOne(Departments::class, 'id', 'injured_person_oth_dept');
    }

    public function injuredPersonDepartment()
    {
        return $this->hasOne(Departments::class, 'id', 'injured_person_dept');
    }
}
