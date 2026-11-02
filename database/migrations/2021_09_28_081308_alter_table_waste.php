<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableWaste extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waste', function (Blueprint $table) {
			$table->string('reviewed_by')->nullable()->default(null);
			$table->Integer('reviewed_count')->nullable()->default(null);
			$table->string('approved_by')->nullable()->default(null);
			$table->string('process_stage')->nullable()->default(null);
			$table->string('review_reject_reason',300)->nullable()->default(null);
			$table->string('approval_reject_reason',300)->nullable()->default(null);
			$table->timestamps();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waste', function (Blueprint $table) {
			$table->dropColumn('reviewed_by');
			$table->dropColumn('reviewed_count');
			$table->dropColumn('approved_by');
			$table->dropColumn('process_stage');
			$table->dropColumn('review_reject_reason');
			$table->dropColumn('approval_reject_reason');
			$table->dropTimestamps();
        });
    }
}
