<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmergencyresponseToModpageloadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modpageload', function (Blueprint $table) {
            $table->integer('emergencyresponse')->default(0)->after('managementreview');
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
            $table->dropColumn('emergencyresponse');
        });
    }
}
