<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateofcalToCalibrationInstrumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		if(!Schema::hasColumn('calibration_instruments', 'dateofcal')) {
				Schema::table('calibration_instruments', function (Blueprint $table) {
					$table->date('dateofcal')->nullable()->after('Status');
				});
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calibration_instruments', function (Blueprint $table) {
            $table->dropColumn('dateofcal');
        });
    }
}
