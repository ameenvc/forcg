<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountColumnLicensePermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('license_permits', function (Blueprint $table) {
            $table->string('compliance_auth_id')->after('comp_id')->nullable();
            $table->integer('count')->default('0')->after('compliance_auth_id');
            $table->string('renewedby')->after('updatedby')->nullable();
            $table->string('renewed_at')->after('renewedby')->nullable();
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
            $table->dropColumn('compliance_auth_id');
            $table->dropColumn('count');
            $table->dropColumn('renewedby');
            $table->dropColumn('renewed_at');
        });
    }
}
