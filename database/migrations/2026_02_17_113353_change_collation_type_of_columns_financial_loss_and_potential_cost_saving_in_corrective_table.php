<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCollationTypeOfColumnsFinancialLossAndPotentialCostSavingInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->string('financial_loss')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
            $table->string('potential_cost_savings')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corrective', function (Blueprint $table) {
            //
        });
    }
}
