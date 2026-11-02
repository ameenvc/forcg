<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDivisionAndSubdivisionToOpportunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opportunity', function (Blueprint $table) {
            $table->unsignedBigInteger('division_id')->after('department')->default(0);
            $table->unsignedBigInteger('area_id')->after('division_id')->default(0);

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
            $table->dropColumn('division_id');
            $table->dropColumn('area_id');
        });
    }
}
