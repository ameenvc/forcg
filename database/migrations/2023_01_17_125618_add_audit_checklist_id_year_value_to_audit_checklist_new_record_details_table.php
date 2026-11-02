<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\AuditChecklistNewRecordDetail;

class AddAuditChecklistIdYearValueToAuditChecklistNewRecordDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $datas =  AuditChecklistNewRecordDetail::all();
       foreach($datas as $data)
       {
           $auditid = $data->audit_id;
           $year = DB::table('audit')->where('id',$auditid)->value('year');
           //$year_value_id = $data->audit_checklist_id.'/'.$auditid.'/'.$year;
           $year_value_id = $data->audit_checklist_id.'/'.$auditid;
           $data->audit_checklist_id_year = $year_value_id ? $year_value_id :'';
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
       $datas =  AuditChecklistNewRecordDetail::all();
       foreach($datas as $data)
       {
           $data->audit_checklist_id_year = NULL;
           $data->save();
       }
    }
}
