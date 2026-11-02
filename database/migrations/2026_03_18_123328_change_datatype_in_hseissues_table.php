<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeInHseissuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('hseissues', function (Blueprint $table) {
            $table->text('remarks')->change();
			$table->text('action_remarks')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hseissues', function (Blueprint $table) {
            $table->string('remarks')->change();
			$table->string('action_remarks')->change();
        });
    }
}
