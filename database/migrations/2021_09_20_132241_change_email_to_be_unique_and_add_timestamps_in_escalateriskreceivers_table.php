<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEmailToBeUniqueAndAddTimestampsInEscalateriskreceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escalateriskreceivers', function (Blueprint $table) {
            $table->unique('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('escalateriskreceivers', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropTimestamps();
        });
    }
}
