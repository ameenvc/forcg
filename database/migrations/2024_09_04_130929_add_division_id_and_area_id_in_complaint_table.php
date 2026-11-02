<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDivisionIdAndAreaIdInComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->bigInteger('division_id')->nullable()->after('dept');
            $table->bigInteger('area_id')->nullable()->after('division_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->dropColumn('division_id');
            $table->dropColumn('area_id');
        });
    }
}
