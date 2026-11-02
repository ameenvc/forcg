<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLengthOfTextareasVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor', function (Blueprint $table) {
            $table->text('scope')->change();
            $table->text('address')->change();
            $table->text('Iso_standard')->change();
            $table->text('comments')->change();
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
            $table->string('scope', 255)->change();
            $table->string('address', 255)->change();
            $table->string('Iso_standard', 255)->change();
            $table->string('comments', 255)->change();
        });
    }
}
