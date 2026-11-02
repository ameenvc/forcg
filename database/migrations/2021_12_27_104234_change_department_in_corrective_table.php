<?php
use App\Corrective;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $corrective = Corrective::all();
        foreach($corrective as $opp)
        {
            $correctivedept = $opp->department;
            $deptid = Departments::where('departmentname',$correctivedept)->value('id');
            $opp->department = $deptid ? $deptid : 0;
            $opp->save();

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $corrective = Corrective::all();
        foreach($corrective as $opp)
        {
            $correctivedept = $opp->department;
            $deptname = Departments::where('id',$correctivedept)->value('departmentname');
            $opp->department = $deptname ? $deptname : '-';
            $opp->save();

        }
    }
}
