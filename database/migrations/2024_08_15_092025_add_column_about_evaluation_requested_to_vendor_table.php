<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAboutEvaluationRequestedToVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor', function (Blueprint $table) {
            $table->unsignedBigInteger('evaluation_requested_by')->nullable()->after('status');
            $table->timestamp('evaluation_requested_time')->nullable()->after('evaluation_requested_by');
            $table->unsignedBigInteger('end_user_id')->nullable()->after('evaluation_requested_time');
            $table->unsignedBigInteger('system_compliance_user_id')->nullable()->after('end_user_id');
            $table->unsignedBigInteger('supply_chain_representative_user_id')->nullable()->after('system_compliance_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor', function (Blueprint $table) {
            $table->dropColumn([
                'evaluation_requested_by',
                'evaluation_requested_time',
                'end_user_id',
                'system_compliance_user_id',
                'supply_chain_representative_user_id'
            ]);
        });
    }
}
