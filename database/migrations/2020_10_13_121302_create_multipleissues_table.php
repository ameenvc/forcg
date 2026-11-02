<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultipleissuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('multipleissues')) {
            Schema::create('multipleissues', function (Blueprint $table) {
                $table->bigIncrements('Sno');
                $table->integer('id')->nullable();
                $table->string('issue_file', 2000)->nullable();
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
        Schema::dropIfExists('multipleissues');
    }
}
