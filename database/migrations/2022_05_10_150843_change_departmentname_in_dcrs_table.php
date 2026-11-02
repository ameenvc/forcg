<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Departments;
use App\Dcr;

class ChangeDepartmentnameInDcrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dcr = Dcr::all();
        foreach($dcr as $dc)
        {
            $deptid = Departments::where('departmentname',$dc->department)->value('id');
            $dc->department = $deptid ? $deptid : 0;
            $dc->save();

        } 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dcr = Dcr::all();
        foreach($dcr as $dc)
        {
            $deptname = Departments::where('id',$dc->department)->value('departmentname');
            $dc->department = $deptname ? $deptname : '-';
            $dc->save();

        }
    }
}
