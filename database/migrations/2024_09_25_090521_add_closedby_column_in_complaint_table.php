<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClosedbyColumnInComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->string('closed_by')->nullable()->after('reviewed_by');
            $table->string('closed_at')->nullable()->after('reviewedat');
            $table->string('approved_at')->nullable()->after('approved_by');
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
            $table->dropColumn('closed_by');
            $table->dropColumn('closed_at');
            $table->dropColumn('approved_at');
        });
    }
}
