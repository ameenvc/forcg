<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLengthOfAuditorsAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit', function (Blueprint $table) {
            $table->text('auditors')->change();
            $table->text('contact')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit', function (Blueprint $table) {
            $table->string('auditors', 255)->change();
            $table->string('contact', 255)->change();
        });
    }
}
