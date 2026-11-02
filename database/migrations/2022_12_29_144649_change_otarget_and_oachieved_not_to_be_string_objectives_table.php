<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOtargetAndOachievedNotToBeStringObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->float('otarget', 8, 4)->nullable()->change();
            $table->float('oachieved', 8, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->string('otarget', 500)->nullable()->change();
            $table->string('oachieved', 500)->nullable()->change();
        });
    }
}
