<?php

use App\Departments;
use App\Environment;
use Illuminate\Database\Migrations\Migration;

class ChangeDepartmentNameToIdEnvironmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $environemnts = Environment::all();
        foreach ($environemnts as $e) {
            $deptid = Departments::where('departmentname', $e->department)->value('id');
            Environment::where('Eid', $e->Eid)->update([
                'department' =>  $deptid ? $deptid : 0
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $environemnts = Environment::all();
        foreach ($environemnts as $e) {
            $deptname = Departments::where('id', $e->department)->value('departmentname');
            Environment::where('Eid', $e->Eid)->update([
                'department' => $deptname ? $deptname : 0
            ]);
        }
    }
}
