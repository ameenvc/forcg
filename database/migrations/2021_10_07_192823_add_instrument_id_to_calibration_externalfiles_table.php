<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstrumentIdToCalibrationExternalfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calibration_externalfiles', function (Blueprint $table) {
            $table->bigInteger('instrument_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calibration_externalfiles', function (Blueprint $table) {
            $table->dropColumn('instrument_id');
        });
    }
}
