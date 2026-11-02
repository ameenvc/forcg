<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintIssueFile extends Model
{
    protected $fillable = [
        'complaint_id','issue_file','is_deleted'
     ];
}
