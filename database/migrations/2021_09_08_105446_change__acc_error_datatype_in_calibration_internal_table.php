<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAccErrorDatatypeInCalibrationInternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calibration_internal', function (Blueprint $table) {
            $table->string('Acc_error')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calibration_internal', function (Blueprint $table) {
            $table->integer('Acc_error')->change();
        });
    }
}
