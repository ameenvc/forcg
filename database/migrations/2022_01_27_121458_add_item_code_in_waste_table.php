<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemCodeInWasteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waste', function (Blueprint $table) {
            $table->string('itemcode', 255)->nullable()->after('itemname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waste', function (Blueprint $table) {
            $table->dropColumn('itemcode');
        });
    }
}
