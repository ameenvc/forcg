<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeSomeFieldsToBeNonMandatoryVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `vendor` MODIFY `Yoe` YEAR NULL;');

        Schema::table('vendor', function (Blueprint $table) {
            $table->string('location')->nullable()->change();
            $table->string('contact')->nullable()->change();
            $table->string('vendor_type')->default('semi_critical')->change();
            $table->string('eval')->default('one_year')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `vendor` MODIFY `Yoe` YEAR NOT NULL;');

        Schema::table('vendor', function (Blueprint $table) {
            $table->string('location')->nullable(false)->change();
            $table->string('contact')->nullable(false)->change();
            //$table->string('vendor_type')->default('semi_critical')->change();
            //$table->string('eval')->default('one_year')->change();
        });
    }
}
