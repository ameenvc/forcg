<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('mail')) {
            Schema::create('mail', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('driver')->nullable();
                $table->string('host')->nullable();
                $table->string('port')->nullable();
                $table->string('faddress')->nullable();
                $table->string('fname')->nullable();
                $table->string('encryption')->nullable();
                $table->string('username')->nullable();
                $table->string('password')->nullable();
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
        Schema::dropIfExists('mail');
    }
}
