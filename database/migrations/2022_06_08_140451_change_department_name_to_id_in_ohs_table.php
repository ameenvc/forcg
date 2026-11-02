<?php

use Illuminate\Database\Migrations\Migration;
use App\Ohs;
use App\Departments;

class ChangeDepartmentNameToIdInOhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ohs_items = Ohs::all();
        foreach ($ohs_items as $item) {
            $deptid = Departments::where('departmentname', $item->department)->value('id');
            $item->department = $deptid ? $deptid : 0;
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
        $ohs_items = Ohs::all();
        foreach ($ohs_items as $item) {
            $deptname = Departments::where('id', $item->department)->value('departmentname');
            $item->department = $deptname ? $deptname : '-';
            $item->save();
        }
    }
}
