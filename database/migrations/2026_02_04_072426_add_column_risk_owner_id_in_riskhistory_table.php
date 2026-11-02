<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRiskOwnerIdInRiskhistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('riskhistory', function (Blueprint $table) {
            $table->unsignedBigInteger('risk_owner_id')->nullable()->after('action');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('riskhistory', function (Blueprint $table) {
            $table->dropColumn('risk_owner_id');
        });
    }
}
