<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeOfObservationColumnInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->longText('observation')->change();
            $table->longText('agreedaction')->change();
            $table->longText('remarks')->change();
            $table->longText('action_remarks')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->string('observation',1000)->change();
            $table->string('agreedaction',2000)->change();
            $table->string('remarks',1000)->change();
            $table->string('action_remarks',1000)->change();
        });
    }
}
