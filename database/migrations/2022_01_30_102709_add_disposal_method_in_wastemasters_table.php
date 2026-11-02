<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisposalMethodInWastemastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wastemasters', function (Blueprint $table) {
            $table->integer('disposal_method')->default(0)->after('waste_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wastemasters', function (Blueprint $table) {
            $table->dropColumn('disposal_method');
        });
    }
}
