<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnInRiskRacmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risk_racms', function (Blueprint $table) {
            $table->string('status')->nullable()->after('process_id');
            $table->string('approved_by')->nullable()->after('approver_id');
            $table->date('approved_date')->nullable()->after('approved_by');
            $table->boolean('rejected_by_approver')->default(false)->after('approved_date');
            $table->longText('reject_reason_by_approver')->nullable()->after('rejected_by_approver');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risk_racms', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'approved_by',
                'approved_date',
                'rejected_by_approver',
                'reject_reason_by_approver'
            ]);
        });
    }
}
