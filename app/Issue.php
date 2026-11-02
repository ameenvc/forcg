<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Issue extends Model
{
     protected $fillable = [
        'count',
        'issueid',
        'auditid',
        'action_file',
        'year',
        'notification',
        'category',
        'type',
        'department',
        'division_id',
        'area_id',
        'title',
        'file',
        'observation',
         'related_risks',
        'duedate',
        'agreedaction',
        'rootcause',
        'correctiveaction',
        'correction',
        'status',
        'postedby',
        'reviewedby',
        'reviewed_by_date',
        'responsibility',
        'processstage',
        'approvement',
        'impl_status',
        'remarks',
        'action_remarks',
        'closed',
        'active',
        'approvedby',
        'closedby',
        'frequency',
        'Monthlyfreq',
        'Weeklyfreq',
        'Dayfreq',
         'is_need_auditee_verify',
         'hod_user',
         'is_fully_implement',
         'hod_reviewed_by_date',
         'division_id',
         'area_id'
    ];
    protected $dates = ['created_at', 'updated_at'];
    public function postedBy()
    {
        return $this->belongsTo('App\User' , 'postedby', 'employeeid');
    }

    public function responsible()
    {
        return $this->belongsTo('App\User' , 'responsibility', 'email');
    }

    public function typ()
    {
        return $this->belongsTo('App\Type' , 'type', 'order');
    }

    public function approvedBy()
    {
        return $this->hasOne('App\User' , 'employeeid', 'approvedby');
    }
    public function closedBy()
    {
        return $this->hasOne('App\User' , 'employeeid', 'closedby');
    }
    public function reviewedBy()
    {
        return $this->hasOne('App\User' , 'employeeid', 'reviewedby');
    }
    public function hodreviewedBy()
    {
        return $this->belongsTo('App\User' , 'hod_user', 'id');
    }
    public function dept()
    {
        return $this->belongsTo('App\Departments' , 'department', 'id');
    }
    protected $appends = ["created_at", "updated_at"];
    public function getCreatedAtAttribute($date)
    {
        if($date != null) {
           return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('d-m-Y h:i:s A');
        }
    }
    public function getUpdatedAtAttribute($date)
    {
        $date = date('Y-m-d H:i:s', strtotime($date));
        if($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s A');
        }
    }

    public function hodUser()
    {
        return $this->belongsTo('App\User' , 'hod_user', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function area()
    {
        return $this->hasOne('App\Area', 'id', 'area_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function issueFiles()
    {
        return $this->hasMany('App\Multipleissue', 'id', 'id')
            ->where('issue_file', '!=', 'NULL')
            ->where('issue_file', '!=', '')
            ->where('issue_file', '!=', null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actionFiles()
    {
        return $this->hasMany('App\Mutlipleaction', 'id', 'id')
            ->where('action_file', '!=', 'NULL')
            ->where('action_file', '!=', '')
            ->where('action_file', '!=', null);
    }
}
