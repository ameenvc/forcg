<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDivisionAndSubdivisionToRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->unsignedInteger('division_id')->default(0)->after('riskarea');
            $table->unsignedInteger('area_id')->default(0)->after('division_id');
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
            $table->dropColumn('division_id');
            $table->dropColumn('area_id');
        });
    }
}
