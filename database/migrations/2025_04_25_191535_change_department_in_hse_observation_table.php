<?php
use App\Departments;
use App\HseIssue;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentInHseObservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $issue = HseIssue::all();
        foreach($issue as $issu)
        {
            $deptid = Departments::where('departmentname',$issu->department)->value('id');
            $issu->department = $deptid ? $deptid : $issu->department;
            $issu->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $issue = HseIssue::all();
        foreach($issue as $issu)
        {
            $deptname = Departments::where('id',$issu->department)->value('departmentname');
            $issu->department = $deptname ? $deptname : $issu->department;
            $issu->save();
        }
    }
}
