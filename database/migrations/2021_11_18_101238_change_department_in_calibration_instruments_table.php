<?php

use App\Calibration_instrument;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentInCalibrationInstrumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $calibration_instruments = Calibration_instrument::all();
        foreach($calibration_instruments as $item){
            $dep = Departments::where('departmentname', $item->Department)->first();
            $item->Department = $dep ? $dep->id : 0;
            $item->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $calibration_instruments = Calibration_instrument::all();
        foreach($calibration_instruments as $item){
            $dep = Departments::where('id', $item->Department)->first();
            $item->Department = $dep ? $dep->departmentname : '-';
            $item->save();
        }
    }
}
