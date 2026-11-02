<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCollationTypeOfColumnRequirementInComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance', function (Blueprint $table) {
            $table->string('requirement', 2000)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
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
            //
        });
    }
}
