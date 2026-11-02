<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEmailToBeUniqueDoccontrollersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doccontrollers', function (Blueprint $table) {
            $table->unique('email');
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
            $table->dropUnique(['email']);
        });
    }
}
