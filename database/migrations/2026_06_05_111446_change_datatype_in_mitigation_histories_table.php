<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeInMitigationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mitigation_histories', function (Blueprint $table) {
            $table->unsignedDecimal('existing_likelihood',2,1)->nullable()->change();
            $table->unsignedDecimal('existing_impact',2,1)->nullable()->change();
            $table->unsignedDecimal('new_likelihood',2,1)->nullable()->change();
            $table->unsignedDecimal('new_impact',2,1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mitigation_histories', function (Blueprint $table) {
            $table->unsignedInteger('existing_likelihood')->nullable()->change();
            $table->unsignedInteger('existing_impact')->nullable()->change();
            $table->unsignedInteger('new_likelihood')->nullable()->change();
            $table->unsignedInteger('new_impact')->nullable()->change();
        });
    }
}
