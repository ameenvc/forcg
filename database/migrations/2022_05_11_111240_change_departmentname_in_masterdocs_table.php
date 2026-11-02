<?php

use App\Masterdoc;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentnameInMasterdocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $masterdocs = Masterdoc::all();
        foreach($masterdocs as $masterdoc)
        {
            $deptid = Departments::where('departmentname',$masterdoc->department)->value('id');
            $masterdoc->department = $deptid ? $deptid : 0;
            $masterdoc->save();

        } 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $masterdocs = Masterdoc::all();
        foreach($masterdocs as $masterdoc)
        {
            $deptname = Departments::where('id',$masterdoc->department)->value('departmentname');
            $masterdoc->department = $deptname ? $deptname : '-';
            $masterdoc->save();

        }
    }
}
