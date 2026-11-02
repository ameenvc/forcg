<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewactivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('viewactivity')) {
            Schema::create('viewactivity', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->date('date')->nullable();
                $table->time('time')->nullable();
                $table->string('activity', 2000)->nullable();
                $table->string('type')->nullable();
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
        Schema::dropIfExists('viewactivity');
    }
}
