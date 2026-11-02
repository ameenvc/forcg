<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDoccontrollersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doccontrollers', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('email', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doccontrollers', function (Blueprint $table) {
            $table->string('name', 1000)->change();
            $table->string('email', 1000)->change();
        });
    }
}
