<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModifiedColumnsInObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->string('new_postedby')->nullable()->after('postedby');
            $table->string('new_approvedby')->nullable()->after('approved_by');
            $table->string('new_reviewedby')->nullable()->after('reviewed_by');
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
            $table->dropColumn('new_postedby');
            $table->dropColumn('new_approvedby');
            $table->dropColumn('new_reviewedby');
        });
    }
}
