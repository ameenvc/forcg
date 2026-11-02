<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErrorLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('error_logs')) {
            Schema::create('error_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->longText('error')->nullable();
                $table->string('logged_date')->nullable();
                $table->Integer('occurence')->nullable();
                $table->boolean('solved')->nullable();
                $table->string('solved_date')->nullable();
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
        Schema::dropIfExists('error_logs');
    }
}
