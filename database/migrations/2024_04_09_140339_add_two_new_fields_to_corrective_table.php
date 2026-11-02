<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoNewFieldsToCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->text('RCs_added_by_responsible_person')->nullable()->after('rootcause');
            $table->text('RCs_added_by_investigation_team')->nullable()->after('RCs_added_by_responsible_person');
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
            $table->dropColumn('RCs_added_by_responsible_person');
            $table->dropColumn('RCs_added_by_investigation_team');
        });
    }
}
