<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostedByColumnToRiskRacmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risk_racms', function (Blueprint $table) {
            $table->unsignedInteger('posted_by')->nullable()->after('approver_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risk_racms', function (Blueprint $table) {
            $table->dropColumn('posted_by');
        });
    }
}
