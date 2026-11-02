<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnApprovedAtInOpportunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opportunity', function (Blueprint $table) {
            $table->dateTime('approved_at')->nullable()->after('reviewdate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opportunity', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });
    }
}
