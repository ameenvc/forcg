<?php

use App\Departments;
use App\Objective;
use Illuminate\Database\Migrations\Migration;

class UpdateCountObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $objectives = Objective::all();
        $departments = Departments::all();
        $current_year = date('Y');
        $objective_with_same_depts = [];

        for ($i = 1970; $i <= $current_year; $i++) {
            foreach ($departments as $d) {
                $objective_with_same_depts[$d->id][$i] = Objective::where('department', $d->id)->whereYear('created_at', $i)->orderBy('oid', 'asc')->get();
                if (!count($objective_with_same_depts[$d->id][$i])) {
                    unset($objective_with_same_depts[$d->id][$i]);
                }
            }
        }
        $arr = $objective_with_same_depts;
        for ($i = 1970; $i <= $current_year; $i++) {
            foreach ($departments as $d) {
                if (isset($arr[$d->id][$i])) {
                    $count = 1;
                    foreach ($arr[$d->id][$i] as $item) {
                        Objective::where('Oid', $item->Oid)->update([
                            'count' => $count,
                        ]);
                        $count++;
                    }
                }
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
