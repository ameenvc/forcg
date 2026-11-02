<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagementReviewToModpageloadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modpageload', function (Blueprint $table) {
            $table->integer('managementreview')->default(0)->after('nearmiss');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modpageload', function (Blueprint $table) {
            $table->dropColumn('managementreview');
        });
    }
}
