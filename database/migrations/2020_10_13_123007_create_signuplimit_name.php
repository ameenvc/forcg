<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignuplimitName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('signuplimit')) {
            Schema::create('signuplimit', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('limitedto');
                $table->string('name')->default('Vishwas Shetty');
                $table->string('email')->default('vishu@gmail.com	');
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
        Schema::dropIfExists('signuplimit');
    }
}
