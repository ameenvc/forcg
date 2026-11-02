<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileToComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('compliance', 'file')) {
            Schema::table('compliance', function (Blueprint $table) {
                $table->string('file')->nullable()->after('reason');
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
        if (Schema::hasColumn('compliance', 'file')) {
            Schema::table('compliance', function (Blueprint $table) {
                $table->dropColumn('file');
            });
        }
    }
}
