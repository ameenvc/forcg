<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRiskReviewerInRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->unsignedBigInteger('riskreviewer_id')->nullable()->after('rank');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->dropColumn('riskreviewer_id');
        });
    }
}
