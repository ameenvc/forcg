<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsLicensePermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('license_permits', function (Blueprint $table) {
            $table->string('licensenumber')->after('name');
            $table->string('effectivedate')->nullable()->after('expirydate');
            $table->string('postedby')->nullable()->after('effectivedate');
            $table->boolean('is_deleted')->default(false)->after('file');
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
            $table->dropColumn('licensenumber');
            $table->dropColumn('effectivedate');
            $table->dropColumn('postedby');
            $table->dropColumn('is_deleted');
        });
    }
}
