<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentsOfAddedReviewedAndApprovedPersonVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor', function (Blueprint $table) {
            $table->text('comments_of_added_person')->after('comments')->nullable();
            $table->text('comments_of_reviewed_person')->after('comments')->nullable();
            $table->text('comments_of_approved_person')->after('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor', function (Blueprint $table) {
            $table->dropColumn('comments_of_added_person');
            $table->dropColumn('comments_of_reviewed_person');
            $table->dropColumn('comments_of_approved_person');
        });
    }
}
