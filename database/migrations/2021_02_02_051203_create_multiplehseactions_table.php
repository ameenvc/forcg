<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultiplehseactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('multiplehseactions')) {
            Schema::create('multiplehseactions', function (Blueprint $table) {
                $table->bigIncrements('Sno');
                $table->integer('id')->nullable('true');
                $table->string('action_file', 2000)->nullable('true');
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
        Schema::dropIfExists('multiplehseactions');
    }
}
