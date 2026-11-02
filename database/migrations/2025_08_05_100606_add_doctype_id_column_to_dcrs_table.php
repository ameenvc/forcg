<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctypeIdColumnToDcrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dcrs', function (Blueprint $table) {
            $table->unsignedBigInteger('doctype_id')->nullable()->after('doctype');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dcrs', function (Blueprint $table) {
            $table->dropColumn('doctype_id');
        });
    }
}
