<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteColumnsFromComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->dropColumn('complaint_category');
            $table->dropColumn('customer_pin');
            $table->dropColumn('customer_email_for_survey');
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
            $table->bigInteger('complaint_category')->after('delivery_no');
            $table->string('customer_pin')->nullable();
            $table->string('customer_email_for_survey')->nullable();
        });
    }
}
