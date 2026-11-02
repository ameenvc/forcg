<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\AuditChecklistNewRecordDetail;
use App\AuditChecklist;

class AddDepartmentIdValuesToAuditChecklistNewRecordDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $recorddetails = AuditChecklistNewRecordDetail::all();
		foreach($recorddetails as $item)
		{
			$departmentid = AuditChecklist::where('id',$item->checklist_id)->value('department_id');
			$item->department_id = $departmentid ? $departmentid : 0;
			$item->save();       
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $recorddetails = AuditChecklistNewRecordDetail::all();
		foreach($recorddetails as $item)
		{
			$item->department_id = NULL;
			$item->save();      
		}
    }
}
