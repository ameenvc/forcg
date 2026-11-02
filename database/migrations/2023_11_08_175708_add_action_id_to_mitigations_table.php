<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActionIdToMitigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mitigations', function (Blueprint $table) {
            $table->string('action_id')->nullable()->after('risk_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mitigations', function (Blueprint $table) {
            $table->dropColumn(['action_id']);
        });
    }
}
