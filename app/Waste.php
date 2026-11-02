<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    protected $table = "waste";
    protected $fillable = [
        'request_no',
        'department',
        'responsibility',
        'date',
        'type',
        'itemname',
        'wastedesc',
        'unit',
        'quantity',
        'useableparts',
        'posted_by',
        'posted_name',
        'posted_at',
        'hod_approval',
        'hod_reject_reason',
        'reviewer',
        'revcheck',
        'approver',
        'count',
        'reviewed_by',
        'reviewed_count',
        'approved_by',
        'process_stage',
        'review_reject_reason',
        'approval_reject_reason',
        'year',   
        'transfer_type','disposal_agency',
        'disposal_date','sold_to',
        'sold_date','disp_sales_requested_by',
        'disposed_completed','disposed_completed_date',
        'disposed_completed_by',

    ];
}
