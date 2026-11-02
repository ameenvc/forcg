<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeOfDateColumnsInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->dateTime('entry_reviewed_by_date')->nullable()->change();
            $table->dateTime('action_reviewed_at')->nullable()->change();
            $table->dateTime('closed')->nullable()->change();
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
            $table->date('entry_reviewed_by_date')->nullable()->change();
            $table->date('action_reviewed_at')->nullable()->change();
            $table->date('closed')->nullable()->change();
        });
    }
}
