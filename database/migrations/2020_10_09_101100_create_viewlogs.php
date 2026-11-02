<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewlogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('viewlogs')) {
            Schema::create('viewlogs', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('last_login_date')->nullable();
                $table->string('last_ip')->nullable();
                $table->string('type')->nullable();
                $table->string('last_login_time')->nullable();
                $table->string('browser')->nullable();
                $table->string('device')->nullable();
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
        Schema::dropIfExists('viewlogs');
    }
}
