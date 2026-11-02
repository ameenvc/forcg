<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeInjuryWitness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('employee_injury_witness')) {
            Schema::create('employee_injury_witness', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('created_date');
                $table->string('injured_per_enumber');
                $table->string('name');
                $table->string('number')->nullable();
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
        Schema::dropIfExists('employee_injury_witness');
    }
}
