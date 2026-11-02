<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjreportName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('objreport')) {
            Schema::create('objreport', function (Blueprint $table) {
                $table->bigIncrements('slno');
                $table->integer('Oid')->nullable();
                $table->string('target')->nullable();
                $table->string('achieved')->nullable();
                $table->string('state')->nullable();
                $table->string('frequency')->nullable();
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
        Schema::dropIfExists('objreport');
    }
}
