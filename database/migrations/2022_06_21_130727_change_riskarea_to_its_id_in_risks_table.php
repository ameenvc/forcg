<?php

use Illuminate\Support\Facades\DB;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRiskareaToItsIdInRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $risk_items = DB::table('risks')->get();
        foreach ($risk_items as $item) {
            $deptid = Departments::where('departmentname', $item->riskarea)->value('id');
            $item->riskarea = $deptid ? $deptid : 0;
            DB::table('risks')->where('Rid', $item->Rid)->update([
                'riskarea' => $item->riskarea
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
        $risk_items = DB::table('risks')->get();
        foreach ($risk_items as $item) {
            $deptname = Departments::where('id', $item->riskarea)->value('departmentname');
            $item->riskarea = $deptname ? $deptname : '-';
            DB::table('risks')->where('Rid', $item->Rid)->update([
                'riskarea' => $item->riskarea
            ]);
        }
    }
}
