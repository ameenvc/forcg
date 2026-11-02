<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCorrectiveIssueidColumnInComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance', function (Blueprint $table) {
            $table->string('corrective_issueid')->nullable()->after('visibleto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compliance', function (Blueprint $table) {
            $table->dropColumn('corrective_issueid');
        });
    }
}
