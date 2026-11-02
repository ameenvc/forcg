<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeInjuryWorkRelatedCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_injury_work_related_cases', function (Blueprint $table) {
            $table->id();
            $table->string('injury_id',20);
            $table->unsignedbigInteger('injury_record_id');
            $table->string('employee_num',150);
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
        Schema::dropIfExists('employee_injury_work_related_cases');
    }
}
