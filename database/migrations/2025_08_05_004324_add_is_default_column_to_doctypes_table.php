<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDefaultColumnToDoctypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctypes', function (Blueprint $table) {
            $table->boolean('is_default')
                ->default(false)
                ->after('typename')
                ->comment('Indicates if this is the default document type');
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
            $table->dropColumn('is_default');
        });
    }
}
