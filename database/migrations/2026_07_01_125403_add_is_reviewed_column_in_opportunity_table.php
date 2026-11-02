<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsReviewedColumnInOpportunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opportunity', function (Blueprint $table) {
            $table->boolean('is_reviewed')->default(false)->after('risk_id');
            $table->boolean('is_closed')->default(false)->after('is_reviewed');
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
            $table->dropColumn('is_reviewed');
            $table->dropColumn('is_approved');
        });
    }
}
