<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditChecklistNewRecordDetail extends Model
{
    protected $table="audit_checklist_new_record_details";
    protected $fillable = [
                            'audit_id','checklist_id','title_id','points_id',
                            'items_verified_records','conformance_id','division_id','area_id',
                            'remarks','record_created_date','audit_checklist_id',
                            'pointsname','items_verified',
                            'purpose','title_name','added_by','updated_by','record_updated_date'
                            
    ];
}
