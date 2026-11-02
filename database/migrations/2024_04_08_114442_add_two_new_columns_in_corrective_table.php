<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoNewColumnsInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->boolean('rootcause_updated_by_investigation_team')->after('responsibility')->default(false);
            $table->boolean('correction_proposed')->after('rootcause_updated_by_investigation_team')->default(false);
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
            $table->dropColumn('rootcause_updated_by_investigation_team');
            $table->dropColumn('correction_proposed');

        });
    }
}
