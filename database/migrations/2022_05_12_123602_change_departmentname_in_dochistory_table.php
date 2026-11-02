<?php
use App\Dochistory;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentnameInDochistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dochistories = Dochistory::all();
        foreach($dochistories as $dochist)
        {
            $deptid = Departments::where('departmentname',$dochist->department)->value('id');
            $dochist->department = $deptid ? $deptid : 0;
            $dochist->save();

        } 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dochistories = Dochistory::all();
        foreach($dochistories as $dochist)
        {
            $deptname = Departments::where('id',$dochist->department)->value('departmentname');
            $dochist->department = $deptname ? $deptname : '-';
            $dochist->save();

        }
    }
}
