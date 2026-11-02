<?php

use App\Audit;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentcolumnInAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $audit = Audit::all();
        foreach($audit as $aud)
        {
            $auditdept = $aud->department;
            $deptid = Departments::where('departmentname',$auditdept)->value('id');
            $aud->department = $deptid ? $deptid : 0;
            $aud->save();

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $audit = Audit::all();
        foreach($audit as $aud)
        {
            $auditdept = $aud->department;
            $deptname = Departments::where('id',$auditdept)->value('departmentname');
            $aud->department = $deptname ? $deptname : '-';
            $aud->save();

        }
    }
}
