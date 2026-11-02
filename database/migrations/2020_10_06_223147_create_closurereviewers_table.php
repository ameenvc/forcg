<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosurereviewersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('closurereviewers')) {
            Schema::create('closurereviewers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable('true');
                $table->string('email')->nullable('true');
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
        Schema::dropIfExists('closurereviewers');
    }
}
