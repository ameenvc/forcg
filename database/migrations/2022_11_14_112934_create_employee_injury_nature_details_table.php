<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeInjuryNatureDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_injury_nature_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('injury_id');
            $table->string('injury_type',220)->nullable();
            $table->unsignedBigInteger('natureofinjury');
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
        Schema::dropIfExists('employee_injury_nature_details');
    }
}
