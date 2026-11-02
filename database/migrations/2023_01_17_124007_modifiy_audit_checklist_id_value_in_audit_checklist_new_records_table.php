<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\AuditChecklistNewRecord;

class ModifiyAuditChecklistIdValueInAuditChecklistNewRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $datas =  AuditChecklistNewRecord::all();
       foreach($datas as $data)
       {
           $auditid = $data->audit_schedule_id;
           $year = DB::table('audit')->where('id',$auditid)->value('year');
           $year_value_id = $data->audit_checklist_id.'/'.$year;
           $data->audit_checklist_id = $year_value_id ? $year_value_id :'';
           $data->save();
       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $datas =  AuditChecklistNewRecord::all();
        foreach($datas as $data)
        {
            $new_value = $data->audit_checklist_id;
            $data->audit_checklist_id = substr($new_value,0,11);
            $data->save();
        } 
    }
}
