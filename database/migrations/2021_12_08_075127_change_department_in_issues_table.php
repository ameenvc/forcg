<?php
use App\Issue;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentInIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $issue = Issue::all();
        foreach($issue as $issu)
        {
            $issuedept = $issu->department;
            $deptid = Departments::where('departmentname',$issuedept)->value('id');
            $issu->department = $deptid ? $deptid : 0;
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
        $issue = Issue::all();
        foreach($issue as $issu)
        {
            $issuedept = $issu->department;
            $deptname = Departments::where('id',$issuedept)->value('departmentname');
            $issu->department = $deptname ? $deptname : '-';
            $issu->save();

        }
    }
}
