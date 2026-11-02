<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHodApprovalToWasteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waste', function (Blueprint $table) {
            $table->string('hod_approval')->after('posted_at')->nullable();
			$table->string('hod_reject_reason',300)->after('hod_approval')->nullable();
			$table->string('transfer_type')->after('year')->nullable();
			$table->string('disposal_agency')->after('transfer_type')->nullable();
			$table->date('disposal_date')->after('disposal_agency')->nullable();
			$table->string('sold_to')->after('disposal_date')->nullable();
			$table->date('sold_date')->after('sold_to')->nullable();
			$table->string('disp_sales_requested_by')->after('sold_date')->nullable();
			$table->string('disposed_completed')->after('disp_sales_requested_by')->nullable();
			$table->date('disposed_completed_date')->after('disposed_completed')->nullable();
			$table->string('disposed_completed_by')->after('disposed_completed_date')->nullable();
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
            $table->dropColumn('hod_approval');
			$table->dropColumn('hod_reject_reason');
			$table->dropColumn('transfer_type');
			$table->dropColumn('disposal_agency');
			$table->dropColumn('disposal_date');
			$table->dropColumn('sold_to');
			$table->dropColumn('sold_date');
			$table->dropColumn('disp_sales_requested_by');
			$table->dropColumn('disposed_completed');
			$table->dropColumn('disposed_completed_date');
			$table->dropColumn('disposed_completed_by');
        });
    }
}
