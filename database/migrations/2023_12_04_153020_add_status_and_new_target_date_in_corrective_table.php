<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndNewTargetDateInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->date('correction_target_date')->after('target_date')->nullable();
            $table->integer('correction_status')->after('impl_status')->default(0);
            $table->text('percent_status')->after('correction_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corrective', function (Blueprint $table) {
           $table->dropColumn('correction_target_date');
           $table->dropColumn('correction_status');
           $table->dropColumn('percent_status');
        });
    }
}
