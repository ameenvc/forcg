<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIsoStandardFromVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor', function (Blueprint $table) {
            $table->dropColumn('Iso_standard');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor', function (Blueprint $table) {
            $table->text('Iso_standard')->after('Basis_addition');
        });
    }
}
