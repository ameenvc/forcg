<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContextCrs extends Model
{

    protected $table = 'context_crs';
    protected $fillable = [
      'count',
      'crid',
      'title',
      'category',
      'scope',
      'reason',
      'file',
      'benefits',
      'impacts',
      'risk_identified',
      'initiator',
      'department',
      'changing_product',
      'actions',
      'priority',
      'severities_id',
      'rev_authority',
      'appr_authority',
      'status',
      'remarks',
      'proposal',
      'higherremarks',
      'higherproposal',
      'created_at',
      'updated_at',
      'reviewed_at',
      'closed_by',
      'reviewed_by',
      'approved_by',
      'postedby'
    ];
    public function severity()
    {
        return $this->belongsTo('App\ContextCrsSeverity' , 'severities_id', 'id');
    }
    public function initiatorBy(){
      return $this->belongsTo('App\User','initiator');
    }

    public function departmentDetails(){
      return $this->belongsTo('App\Departments','department');
    }

    public function priorityDetails(){
      return $this->belongsTo('App\Priority','priority');
    }

    public function reviewedBy(){
      return $this->belongsTo('App\User','reviewed_by');
    }

    public function approvedBy(){
      return $this->belongsTo('App\User','approved_by');
    }

    public function approver(){
      return $this->belongsTo('App\User','appr_authority');
    }

    public function reviewer(){
      return $this->belongsTo('App\User','rev_authority');
    }

    public function closedBy(){
      return $this->belongsTo('App\User','closed_by');
    }

    public function postedByDetails(){
      return $this->belongsTo('App\User','postedby','employeeid');
    }

    public function correctiveDetails(){
      return $this->hasOne('App\Corrective','moc_id','id');
    }
}
