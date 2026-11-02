<?php

use Illuminate\Database\Migrations\Migration;
use App\Ohsrisk;
use App\Departments;

class ChangeDepartmentNameToIdInOhsriskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ohsrisk_items = Ohsrisk::all();
        foreach ($ohsrisk_items as $item) {
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
        $ohsrisk_items = Ohsrisk::all();
        foreach ($ohsrisk_items as $item) {
            $deptname = Departments::where('id', $item->department)->value('departmentname');
            $item->department = $deptname ? $deptname : '-';
            $item->save();
        }
    }
}
