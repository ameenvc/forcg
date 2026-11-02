<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('hse')) {
            Schema::create('hse', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('auditorsname', 250)->nullable('true');
                $table->string('email', 250)->nullable('true');
                $table->string('certificate', 2500)->nullable('true');
                $table->string('experience', 2000)->nullable('true');
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
        Schema::dropIfExists('hse');
    }
}
