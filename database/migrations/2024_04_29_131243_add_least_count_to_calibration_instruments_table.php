<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeastCountToCalibrationInstrumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calibration_instruments', function (Blueprint $table) {
            $table->string('least_count')->after('Type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calibration_instruments', function (Blueprint $table) {
            $table->dropColumn('least_count');
        });
    }
}
