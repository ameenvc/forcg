<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMaxLengthOfTypeCodeColumnInDoctypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctypes', function (Blueprint $table) {
            $table->string('type_code', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctypes', function (Blueprint $table) {
            $table->string('type_code', 5)->change();
        });
    }
}
