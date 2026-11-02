<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeIllnessPandemicContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_illness_pandemic_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('illness_record_id')->nullable();
            $table->string('contact_emp_name',255)->nullable();
            $table->string('contact_emp_number',255)->nullable();
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
        Schema::dropIfExists('employee_illness_pandemic_contacts');
    }
}
