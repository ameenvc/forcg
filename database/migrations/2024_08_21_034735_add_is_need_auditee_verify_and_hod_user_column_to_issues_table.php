<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsNeedAuditeeVerifyAndHodUserColumnToIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->boolean('is_need_auditee_verify')->default(false)->after('processstage');
            $table->unsignedBigInteger('hod_user')->nullable()->after('is_need_auditee_verify');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn(['is_need_auditee_verify', 'hod_user']);
        });
    }
}
