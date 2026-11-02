<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('compliance', 'responsibility')) {
            Schema::table('compliance', function (Blueprint $table) {
                $table->string('responsibility')->after('frequency');
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
        if (Schema::hasColumn('compliance', 'responsibility')) {
            Schema::table('compliance', function (Blueprint $table) {
                $table->dropColumn('responsibility');
            });
        }
    }
}
