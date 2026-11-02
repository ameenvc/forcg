<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditChecklistNewRecordFileDetail extends Model
{
    protected $table="audit_checklist_new_record_file_details";
    protected $fillable = [
                            'record_details_id','file_name'
                            
    ];
}
