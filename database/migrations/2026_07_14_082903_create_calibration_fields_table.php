<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalibrationFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calibration_fields', function (Blueprint $table) {
            $table->id();
            $table->boolean('enable_field');
            $table->string('field_name');
            $table->string('field_type');
            $table->string('default_display_label');
            $table->string('field_display_label');
            $table->boolean('field_is_mandatory');
            $table->bigInteger('updated_by');
            $table->integer('order');
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
        Schema::dropIfExists('calibration_fields');
    }
}
