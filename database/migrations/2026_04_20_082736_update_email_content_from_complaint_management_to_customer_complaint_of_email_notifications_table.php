<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmailContentFromComplaintManagementToCustomerComplaintOfEmailNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('email_notifications')
            ->where('module', 'complaintmanagement')
            ->where('email_content', 'like', '%complaint management%')
            ->update([
                'email_content' => DB::raw("REPLACE(email_content, 'Complaint Management', 'Customer Complaint')")
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
