<?php

use App\Departments;
use App\Nearmiss;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentReportedByToNameInNearmissTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $nearmisses = Nearmiss::all();
        foreach ($nearmisses as $item) {
            if (is_numeric($item->department_reported_by)) {
                $item->department_reported_by = Departments::where('id', $item->department_reported_by)->value('departmentname') ?? '';
                $item->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
