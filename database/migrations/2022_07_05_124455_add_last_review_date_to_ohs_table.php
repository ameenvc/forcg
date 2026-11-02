<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastReviewDateToOhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ohs', function (Blueprint $table) {
            $table->date('last_review_date')->nullable()->after('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ohs', function (Blueprint $table) {
            $table->dropColumn('last_review_date');
        });
    }
}
