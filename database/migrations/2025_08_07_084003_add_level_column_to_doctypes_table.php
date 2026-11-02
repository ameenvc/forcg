<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLevelColumnToDoctypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('doctypes')) {
            Schema::table('doctypes', function (Blueprint $table) {
                $table->integer('level')->default(1)->after('typename')->comment('Level of the doctype');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('doctypes', 'level')) {
            Schema::table('doctypes', function (Blueprint $table) {
                $table->dropColumn('level');
            });
        }
    }
}
