<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskGroupDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_group_details', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->string('risk_name');
            $table->text('risk_desp');
            $table->text('risk_driver');
            $table->text('mitigating_actions');
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
        Schema::dropIfExists('risk_group_details');
    }
}
