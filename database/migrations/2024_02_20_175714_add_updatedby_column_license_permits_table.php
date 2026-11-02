<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedbyColumnLicensePermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('license_permits', function (Blueprint $table) {
            $table->string('updatedby')->nullable()->after('postedby');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('license_permits', function (Blueprint $table) {
            $table->dropColumn('updatedby');
        });
    }
}
