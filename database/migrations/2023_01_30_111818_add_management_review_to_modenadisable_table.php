<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagementReviewToModenadisableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modenadisable', function (Blueprint $table) {
            $table->boolean('managementreview')->default(0)->after('nearmiss');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modenadisable', function (Blueprint $table) {
            $table->dropColumn('managementreview');
        });
    }
}
