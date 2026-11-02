<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeIllnessNatureDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_illness_nature_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('illness_id');
            $table->string('illness_type',220)->nullable();
            $table->unsignedBigInteger('natureofillness');
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
        Schema::dropIfExists('employee_illness_nature_details');
    }
}
