<?php

use App\Wastemaster;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentInWastemastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $wastemaster = Wastemaster::all();
        foreach($wastemaster as $wast)
        {
            $wastedept = $wast->department;
            $deptid = Departments::where('departmentname',$wastedept)->value('id');
            $wast->department = $deptid ? $deptid : 0;
            $wast->save();

        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $wastemaster = Wastemaster::all();
        foreach($wastemaster as $wast)
        {
            $wastedept = $wast->department;
            $deptname = Departments::where('id',$wastedept)->value('departmentname');
            $wast->department = $deptname ? $deptname : '-';
            $wast->save();

        }
    }
}
