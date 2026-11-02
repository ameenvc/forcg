<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewedByAndApprovedByAndReviewedAtAndApprovedAtToVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendorhistory', function (Blueprint $table) {
            $table->enum('action_status', ['awaiting_review', 'reviewed', 'approved'])->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('is_deleted');
            $table->timestamp('approved_at')->nullable()->after('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendorhistory', function (Blueprint $table) {
            $table->dropColumn('action_status');
            $table->dropColumn('reviewed_at');
            $table->dropColumn('approved_at');
        });
    }
}
