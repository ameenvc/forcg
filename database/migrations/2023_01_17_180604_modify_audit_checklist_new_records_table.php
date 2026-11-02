<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\AuditChecklistNewRecord;
use App\AuditChecklistNewRecordDetail;

class ModifyAuditChecklistNewRecordsTable extends Migration
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
           $check = AuditChecklistNewRecordDetail::where('audit_id',$auditid)->get();
           if($check->isEmpty())
           {
             AuditChecklistNewRecord::where('audit_schedule_id',$auditid)->delete();
           }

       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
