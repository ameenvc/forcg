<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalibrationFieldHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calibration_field_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('calibration_field_id');
            $table->string('previous_display_label');
            $table->string('updated_display_label');
            $table->bigInteger('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calibration_field_histories');
    }
}
