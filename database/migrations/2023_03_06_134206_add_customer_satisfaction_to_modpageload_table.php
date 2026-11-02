<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerSatisfactionToModpageloadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modpageload', function (Blueprint $table) {
            $table->integer('customer_satisfaction')->default(0)->after('complaintmanagement');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modpageload', function (Blueprint $table) {
            $table->dropColumn('customer_satisfaction');
        });
    }
}
