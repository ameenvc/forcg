<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->string('job_order_no')->nullable()->after('delivery_no');
            $table->string('product_serial_no')->nullable()->after('job_order_no');
            $table->string('project_name')->nullable()->after('product_serial_no');
            $table->longText('job_history')->nullable()->after('project_name');
            $table->bigInteger('complaint_template_id')->nullable()->after('job_history');
            $table->longText('complaint_template_section_field_rules')->nullable()->after('complaint_template_id');
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
            $table->dropColumn('job_order_no');
            $table->dropColumn('product_serial_no');
            $table->dropColumn('project_name');
            $table->dropColumn('job_history');
            $table->dropColumn('complaint_template_id');
            $table->dropColumn('complaint_template_section_field_rules');
        });
    }
}
