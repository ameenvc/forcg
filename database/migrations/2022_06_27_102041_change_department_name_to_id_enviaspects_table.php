<?php

use App\Enviaspect;
use App\Departments;
use Illuminate\Database\Migrations\Migration;


class ChangeDepartmentNameToIdEnviaspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $enviaspects = Enviaspect::all();
        foreach ($enviaspects as $e) {
            $deptid = Departments::where('departmentname', $e->department)->value('id');
            Enviaspect::where('slno', $e->slno)->update([
               'department' => $deptid ? $deptid : 0
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
        $enviaspects = Enviaspect::all();
        foreach ($enviaspects as $e) {
            $deptname = Departments::where('id', $e->department)->value('departmentname');
            $e->where('slno', $e->slno)->update([
                'department' => $deptname ? $deptname : '-'
            ]);
        }
    }
}
