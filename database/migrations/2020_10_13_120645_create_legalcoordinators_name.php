<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLegalcoordinatorsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('legalcoordinators')) {
            Schema::create('legalcoordinators', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->string('email')->nullable();
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
        Schema::dropIfExists('legalcoordinators');
    }
}
