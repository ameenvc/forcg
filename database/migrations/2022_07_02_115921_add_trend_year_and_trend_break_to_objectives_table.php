<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrendYearAndTrendBreakToObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->integer('trend_break')->after('count')->default(0)->nullable();
            $table->integer('trend_year')->after('count')->nullable();
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
            $table->dropColumn('trend_break');
            $table->dropColumn('trend_year');
        });
    }
}
