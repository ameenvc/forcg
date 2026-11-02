<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLocationOwnerIdOhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ohs', function (Blueprint $table) {
            $table->dropColumn('location_owner_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ohs', function (Blueprint $table) {
            $table->unsignedBigInteger('location_owner_id')->after('location')->default(0);
        });
    }
}
