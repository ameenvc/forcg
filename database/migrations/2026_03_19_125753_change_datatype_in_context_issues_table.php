<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeInContextIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('context_issues', function (Blueprint $table) {
            $table->longText('monitoring')->change();
            $table->longText('issue')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('context_issues', function (Blueprint $table) {
            $table->string('monitoring')->change();
            $table->string('issue')->change();
        });
    }
}
