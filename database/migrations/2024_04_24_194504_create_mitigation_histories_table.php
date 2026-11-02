<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitigationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mitigation_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('risk_id');
            $table->unsignedInteger('mitigation_id');
            $table->unsignedInteger('existing_likelihood')->nullable();
            $table->unsignedInteger('existing_impact')->nullable();
            $table->string('existing_risk')->nullable();
            $table->unsignedInteger('new_likelihood')->nullable();
            $table->unsignedInteger('new_impact')->nullable();
            $table->string('new_risk')->nullable();
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
        Schema::dropIfExists('mitigation_histories');
    }
}
