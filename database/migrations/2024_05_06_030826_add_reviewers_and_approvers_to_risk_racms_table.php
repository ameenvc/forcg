<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewersAndApproversToRiskRacmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risk_racms', function (Blueprint $table) {
            $table->unsignedInteger('reviewer_id')->nullable()->after('unit');
            $table->unsignedInteger('approver_id')->nullable()->after('reviewer_id');
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
            $table->dropColumn(['reviewer_id', 'approver_id']);
        });
    }
}
