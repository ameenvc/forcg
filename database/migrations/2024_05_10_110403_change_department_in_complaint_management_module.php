<?php
use Illuminate\Support\Facades\DB;
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
        $complaints = DB::table('complaint')->get();
        foreach($complaints as $comp)
        {
            $compdept = $comp->dept;
            $deptid = Departments::where('departmentname',$compdept)->value('id');
            $comp->dept = $deptid ? $deptid : $compdept;
            DB::table('complaint')->where('id', $comp->id)->update(['dept' => $comp->dept]);

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $complaints = DB::table('complaint')->get();
        foreach($complaints as $comp)
        {
            $compdept = $comp->dept;
            $deptname = Departments::where('id',$compdept)->value('departmentname');
            $comp->dept = $deptname ? $deptname : '-' ;
            DB::table('complaint')->where('id', $comp->id)->update(['dept' => $comp->dept]);

        }
    }
}
