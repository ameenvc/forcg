<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultipleactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('multipleactions')) {
            Schema::create('multipleactions', function (Blueprint $table) {
                $table->bigIncrements('Sno');
                $table->integer('id')->nullable();
                $table->string('action_file', 2000)->nullable();
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
        Schema::dropIfExists('multipleactions');
    }
}
