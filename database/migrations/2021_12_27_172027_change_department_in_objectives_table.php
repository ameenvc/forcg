<?php

use App\Objective;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentInObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $objectives = Objective::all();
        foreach($objectives as $item)
        {
            $dept = $item->department;
            $deptid = Departments::where('departmentname',$dept)->value('id');
            $item->department = $deptid ? $deptid : 0;
            Objective::where('Oid', $item->Oid)->update([
               'department' => $item->department
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $objectives = Objective::all();
        foreach($objectives as $item)
        {
            $dept = $item->department;
            $deptname = Departments::where('id',$dept)->value('departmentname');
            $item->department = $deptname ? $deptname : '-';
            Objective::where('Oid', $item->Oid)->update([
               'department' => $item->department
            ]);
        }
    }
}
