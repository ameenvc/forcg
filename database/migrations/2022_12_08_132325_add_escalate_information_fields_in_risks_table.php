<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEscalateInformationFieldsInRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->timestamp('last_escalated_time')->after('approved_by')->nullable();
            $table->integer('number_of_escalated_times')->after('approved_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->dropColumn('last_escalated_time');
            $table->dropColumn('number_of_escalated_times');
        });
    }
}
