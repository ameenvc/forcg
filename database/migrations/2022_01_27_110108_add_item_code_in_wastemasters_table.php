<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemCodeInWastemastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wastemasters', function (Blueprint $table) {
            $table->string('waste_code', 255)->nullable()->after('waste_name');
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
            $table->dropColumn('waste_code');
        });
    }
}
