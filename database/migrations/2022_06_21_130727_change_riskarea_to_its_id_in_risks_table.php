<?php

use App\Risk;
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
        $risk_items = Risk::all();
        foreach ($risk_items as $item) {
            $deptid = Departments::where('departmentname', $item->riskarea)->value('id');
            $item->riskarea = $deptid ? $deptid : 0;
            Risk::where('Rid', $item->Rid)->update([
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
        $risk_items = Risk::all();
        foreach ($risk_items as $item) {
            $deptname = Departments::where('id', $item->riskarea)->value('departmentname');
            $item->riskarea = $deptname ? $deptname : '-';
            Risk::where('Rid', $item->Rid)->update([
                'riskarea' => $item->riskarea
            ]);
        }
    }
}
