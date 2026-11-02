<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContextIdToOhsriskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ohsrisk', function (Blueprint $table) {
             $table->bigInteger('context_id')->after('risk_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ohsrisk', function (Blueprint $table) {
            $table->dropColumn('context_id');
        });
    }
}
