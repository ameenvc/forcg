<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsInComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->string('mode_of_complaint')->nullable()->after('crdate');
            $table->unsignedBigInteger('plant')->nullable()->after('title');
            $table->string('customer_phone')->nullable()->after('customer');
            $table->string('customer_email')->nullable()->after('customer_phone');
            $table->string('customer_address')->nullable()->after('customer_email');
            $table->string('customer_pin')->nullable()->after('customer_address');
            $table->text('customer_email_for_survey')->nullable()->after('customer_pin');
            $table->string('sale_order_no')->nullable()->after('product');
            $table->date('delivery_date')->nullable()->after('sale_order_no');
            $table->string('delivery_no')->nullable()->after('delivery_date');
            $table->unsignedBigInteger('complaint_category')->nullable()->after('delivery_no');
            $table->double('financial_loss')->nullable()->after('sendnotif');
            $table->double('potential_cost_savings')->nullable()->after('financial_loss');
            $table->unsignedBigInteger('team_lead_id')->nullable()->after('tardate');
            $table->date('investigation_date')->nullable()->after('team_lead_id');
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
            $table->dropColumn([
                'mode_of_complaint',
                'plant',
                'customer_phone',
                'customer_email',
                'customer_address',
                'customer_pin',
                'customer_email_for_survey',
                'sale_order_no',
                'delivery_date',
                'delivery_no',
                'complaint_category',
                'financial_loss',
                'potential_cost_savings',
                'team_lead_id',
                'investigation_date',
            ]);
        });
    }
}
