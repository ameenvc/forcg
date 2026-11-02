<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeHealthRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('employee_health_record')) {
            Schema::create('employee_health_record', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('e_number');
                $table->string('e_name');
                $table->string('department');
                $table->string('position');
                $table->string('date');
                $table->string('tobacco');
                $table->string('bp_diastolic');
                $table->string('bp_systolic');
                $table->string('bs_level');
                $table->text('remarks')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('employee_health_record');
    }
}
