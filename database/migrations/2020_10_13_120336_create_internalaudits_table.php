<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalauditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('internalaudits')) {
            Schema::create('internalaudits', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable('true');
                $table->string('email')->nullable('true');
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
        Schema::dropIfExists('internalaudits');
    }
}
