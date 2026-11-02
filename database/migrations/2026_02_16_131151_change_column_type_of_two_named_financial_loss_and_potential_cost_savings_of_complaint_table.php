<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypeOfTwoNamedFinancialLossAndPotentialCostSavingsOfComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->string('financial_loss')->nullable()->change();
            $table->string('potential_cost_savings')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // clean invalid values before type change
        DB::statement("
            UPDATE corrective
            SET financial_loss = NULL
            WHERE financial_loss REGEXP '[^0-9.]'
        ");

        DB::statement("
            UPDATE corrective
            SET potential_cost_savings = NULL
            WHERE potential_cost_savings REGEXP '[^0-9.]'
        ");

        Schema::table('complaint', function (Blueprint $table) {
            $table->decimal('financial_loss', 20, 2)->nullable()->change();
            $table->decimal('potential_cost_savings', 20, 2)->nullable()->change();
        });
    }
}
