<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSomeFieldsLengthCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->longText('rootcause')->change();
            $table->longText('correctiveaction')->change();
            $table->string('department', 1000)->change();
            $table->longText('responsibility', 1000)->change();
            $table->longText('target_date')->change();
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
            $table->string('rootcause', 2000)->change();
            $table->string('correctiveaction', 1000)->change();
            $table->string('department', 255)->change();
            $table->string('responsibility', 255)->change();
            $table->date('target_date')->change();
        });
    }
}
