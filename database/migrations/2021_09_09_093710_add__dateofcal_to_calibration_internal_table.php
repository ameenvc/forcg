<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateofcalToCalibrationInternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	  if(!Schema::hasColumn('calibration_internal', 'Dateofcal')) {
			Schema::table('calibration_internal', function (Blueprint $table) {
				$table->string('Dateofcal',200)->nullable()->after('Verified_by');
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
        Schema::table('calibration_internal', function (Blueprint $table) {
            $table->dropColumn('Dateofcal');
        });
    }
}
