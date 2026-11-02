<?php

use App\Opportunity;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentInOpportunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $opportunity = Opportunity::all();
        foreach($opportunity as $opp)
        {
            $opportunitydept = $opp->department;
            $deptid = Departments::where('departmentname',$opportunitydept)->value('id');
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
        $opportunity = Opportunity::all();
        foreach($opportunity as $opp)
        {
            $opportunitydept = $opp->department;
            $deptname = Departments::where('id',$opportunitydept)->value('departmentname');
            $opp->department = $deptname ? $deptname : '-';
            $opp->save();

        }
    }
}
