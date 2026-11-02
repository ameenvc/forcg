<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctypeIdColumnToMasterdocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('masterdocs', function (Blueprint $table) {
            $table->unsignedBigInteger('doctype_id')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('masterdocs', function (Blueprint $table) {
            $table->dropColumn('doctype_id');
        });
    }
}
