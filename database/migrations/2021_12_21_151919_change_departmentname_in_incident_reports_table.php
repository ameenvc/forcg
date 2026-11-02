<?php
use App\IncidentReport;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentnameInIncidentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $incident = IncidentReport::all();
       foreach($incident as $inc)
       {
           $inc_dept = $inc->department;
           $depid = Departments::where('departmentname',$inc_dept)->value('id');
           $inc->department = $depid ? $depid : 0;
           $injured_dept = Departments::where('departmentname',$inc->injured_person_dept)->value('id');
           $inc->injured_person_dept = $injured_dept ? $injured_dept : 0;
           $injured_dept_oth = Departments::where('departmentname',$inc->injured_person_oth_dept)->value('id');
           $inc->injured_person_oth_dept = $injured_dept_oth ? $injured_dept_oth : 0;
           $inc->save();
       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $incident = IncidentReport::all();
        foreach($incident as $inc)
        {
            $inc_dept = $inc->department;
            $depname = Departments::where('id',$inc_dept)->value('departmentname');
            $inc->department = $depname ? $depname : '--';
            $injured_deptname = Departments::where('id',$inc->injured_person_dept)->value('departmentname');
            $inc->injured_person_dept = $injured_deptname ? $injured_deptname : '--';
            $injured_deptname_oth = Departments::where('id',$inc->injured_person_oth_dept)->value('departmentname');
            $inc->injured_person_oth_dept = $injured_deptname_oth ? $injured_deptname_oth :'--';
            $inc->save();
        }
    }
}
