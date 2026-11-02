<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeillnessTreatmentdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employeeillness_treatmentdetails', function (Blueprint $table) {
            $table->id();
            $table->string('treatment_id',20);
            $table->unsignedbigInteger('illness_record_id');
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
        Schema::dropIfExists('employeeillness_treatmentdetails');
    }
}
