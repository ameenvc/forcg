<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships; // 1. Import trait

class Complaint extends Model
{
    protected $table = "complaint";
    use SoftDeletes;
    use HasJsonRelationships; // 2. Use trait

    protected $fillable=[
    	'cname','location','product','productid','title','complaint','file','responsibility','duedate','active','closedate',
        'financial_loss',
        'potential_cost_savings',
        'team_lead_id',
        'correction_target_date',
    ];

    public function investigators()
    {
        return $this->belongsToMany('App\User', 'complaint_investigators', 'complaint_id', 'user_id');
    }

    public function lead()
    {
        return $this->belongsTo('App\User', 'team_lead_id', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\ComplaintProductDetail');
    }

    public function complaintMeta()
    {
        return $this->hasMany('App\Models\ComplaintMeta');
    }

    public function previousComplaintReferenceMeta()
    {
        return $this->hasOne('App\Models\ComplaintMeta', 'complaint_id')->where('meta_key', 'previous_complaint_reference');
    }

    public function previousComplaintRef()
    {
        return $this->hasOneThrough(
            Complaint::class,
            'App\Models\ComplaintMeta',
            'complaint_id',     // Foreign key on complaintmetas table
            'id',          // Foreign key on complaint table(related to complaint reference)
            'id',          // Local key on complaint table
            'meta_value'   // Local key on complaintmeta (previous_complaint_reference value stored here)
        )->withTrashed() // include soft deleted complaints
        ->where('complaint_metas.meta_key', 'previous_complaint_reference');
    }

    public static function generateComplaintId()
    {
        $today = Carbon::now()->format('Ymd');
        $count = self::whereDate('created_at', Carbon::today())->count();
        do {
            $count = $count + 1;
            $sequence = str_pad($count, 4, '0', STR_PAD_LEFT);
            $trackno = "CM-{$today}-{$sequence}";
        } while (self::where('trackno', $trackno)->exists());
        return $trackno;
    }

    public function customerDetails()
    {
        return $this->belongsTo(Customer::class, 'customer');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'dept');
    }

    public function responsiblePerson()
    {
        return $this->belongsTo(User::class, 'responsibility', 'email');
    }

    public function recievedByPerson()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee', 'emp_number');
    }

    public function raisedByPerson()
    {
        return $this->belongsTo(User::class, 'addedby', 'employeeid');
    }

    public function plantDetails()
    {
        return $this->belongsTo(ComplaintPlant::class, 'plant');
    }

    public function complaintCategory()
    {
        return $this->hasOneThrough(
            ComplaintCategory::class,
            'App\Models\ComplaintMeta',
            'complaint_id',     // Foreign key on complaintmetas table
            'id',          // Foreign key on complaint category table(related to complaint_category_id)
            'id',          // Local key on complaint table
            'meta_value'   // Local key on complaintmeta (complaint_category_id value stored here)
        )->where('complaint_metas.meta_key', 'complaint_category_id');
    }

    public function vendor()
    {
        return $this->hasOneThrough(
            Vendor::class,
            'App\Models\ComplaintMeta',
            'complaint_id',     // Foreign key on complaintmetas table
            'id',          // Foreign key on vendor table(related to vendor id)
            'id',          // Local key on complaint table
            'meta_value'   // Local key on complaintmeta (vendor_id value stored here)
        )->where('complaint_metas.meta_key', 'vendor_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function subDivision()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function complaintEscalation()
    {
        return $this->hasOne('App\Models\ComplaintEscalation');
    }
}
