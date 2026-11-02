<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFrequentlyOfEmailNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table('email_notifications')
            ->where('module', 'calibration')
            ->where('item', 'Email Content for daily notification for Near to expiry & Expired instruments')
            ->update(['frequently' => 'daily']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::table('email_notifications')
            ->where('module', 'calibration')
            ->where('item', 'Email Content for daily notification for Near to expiry & Expired instruments')
            ->update(['frequently' => null]);
    }
}
