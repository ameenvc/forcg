<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActionReviewedByAndDateToCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->string('action_reviewed_by')->after('closedby')->nullable();
            $table->date('action_reviewed_at')->after('action_reviewed_by')->nullable();
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
            $table->dropColumn('action_reviewed_by');
            $table->dropColumn('action_reviewed_at');
        });
    }
}
