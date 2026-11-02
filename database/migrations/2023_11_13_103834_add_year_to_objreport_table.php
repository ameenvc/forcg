<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearToObjreportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objreport', function (Blueprint $table) {
            $table->year('year')->after('quarter')->nullable();
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
        Schema::table('objreport', function (Blueprint $table) {
             $table->dropColumn('year');
			 $table->dropTimestamps();
        });
    }
}
