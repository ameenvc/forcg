<?php
use App\Complaint;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentInComplaintManagementModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $complaints = Complaint::all();
        foreach($complaints as $comp)
        {
            $compdept = $comp->dept;
            $deptid = Departments::where('departmentname',$compdept)->value('id');
            $comp->dept = $deptid ? $deptid : $compdept;
            $comp->save();

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $complaints = Complaint::all();
        foreach($complaints as $comp)
        {
            $compdept = $comp->dept;
            $deptname = Departments::where('id',$compdept)->value('departmentname');
            $comp->dept = $deptname ? $deptname : '-' ;
            $comp->save();

        }
    }
}
