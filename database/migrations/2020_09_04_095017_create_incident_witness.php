<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentWitness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('incident_witness')) {
            Schema::create('incident_witness', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('report_no');
                $table->string('name');
                $table->text('phone_or_address');
                $table->string('statement');
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
        Schema::dropIfExists('incident_witness');
    }
}
