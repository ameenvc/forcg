<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvestigationReportFieldComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->text('system_related_problems')->nullable()->after('correction');
            $table->boolean('recent_changes')->default(false)->after('system_related_problems');
            $table->enum('occurrence', ['one_time', 'regularly'])->nullable()->after('recent_changes');
            $table->boolean('follow_procedures')->default(false)->after('occurrence');
            $table->text('contributing_factors')->nullable()->after('follow_procedures');
            $table->string('contributing_factors_details')->after('contributing_factors')->nullable();
            $table->text('external_factors')->nullable()->after('contributing_factors_details');
            $table->string('external_factors_details')->after('external_factors')->nullable();
            $table->boolean('issue_foreseeable')->default(false)->after('external_factors_details');
            $table->text('prevent_reason')->nullable()->after('issue_foreseeable');
            $table->boolean('rootcause_updated_by_investigation_team')->after('team_lead_id')->default(false);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->dropColumn("contributing_factors_details");
            $table->dropColumn("external_factors_details");
            $table->dropColumn('occurrence');
            $table->dropColumn('system_related_problems');
            $table->dropColumn('recent_changes');
            $table->dropColumn('follow_procedures');
            $table->dropColumn('contributing_factors');
            $table->dropColumn('external_factors');
            $table->dropColumn('issue_foreseeable');
            $table->dropColumn('prevent_reason');
            $table->dropColumn('rootcause_updated_by_investigation_team');
        });
    }
}
