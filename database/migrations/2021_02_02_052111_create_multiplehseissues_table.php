<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultiplehseissuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('multiplehseissues')) {
            Schema::create('multiplehseissues', function (Blueprint $table) {
                $table->bigIncrements('Sno');
                $table->integer('id')->nullable('true');
                $table->string('issue_file', 2000)->nullable('true');
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
        Schema::dropIfExists('multiplehseissues');
    }
}
