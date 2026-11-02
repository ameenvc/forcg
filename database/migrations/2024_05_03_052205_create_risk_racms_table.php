<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskRacmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_racms', function (Blueprint $table) {
            $table->id();
            $table->string('risk_factor_title');
            $table->unsignedInteger('process_id');
            $table->string('risk_factor_name');
            $table->text('risk_factor_description')->nullable();
            $table->string('low')->nullable();
            $table->string('moderate')->nullable();
            $table->string('high')->nullable();
            $table->string('unit')->nullable();
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
        Schema::dropIfExists('risk_racms');
    }
}
