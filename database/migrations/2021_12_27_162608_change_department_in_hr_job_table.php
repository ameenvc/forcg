<?php

use App\HrJob;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Departments;

class ChangeDepartmentInHrJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $hr_jobs = HrJob::all();
        foreach($hr_jobs as $item)
        {
            $dept = $item->dept;
            $deptid = Departments::where('departmentname',$dept)->value('id');
            $item->dept = $deptid ? $deptid : 0;
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
        $hr_jobs = HrJob::all();
        foreach($hr_jobs as $item)
        {
            $dept = $item->dept;
            $deptname = Departments::where('id',$dept)->value('departmentname');
            $item->dept = $deptname ? $deptname : '-';
            $item->save();

        }
    }
}
