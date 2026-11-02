<?php
use App\EmployeeDetails;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentnameInEmployeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $employeedetails = EmployeeDetails::all();
       foreach($employeedetails as $emp)
       {
           $emp_dept = $emp->emp_department;
           $depid = Departments::where('departmentname',$emp_dept)->value('id');
           $emp->emp_department = $depid ? $depid : 0;
           $emp->save();
       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $employeedetails = EmployeeDetails::all();
        foreach($employeedetails as $emp)
        {
            $emp_dept = $emp->emp_department;
            $depname = Departments::where('id',$emp_dept)->value('departmentname');
            $emp->emp_department = $depname ? $depname : '-';
            $emp->save();
        }
    }
}
