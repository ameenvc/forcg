<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateContentOfAuditDelayMailNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('email_notifications')
            ->where('module', '=', 'auditmanagement')
            ->where('item', '=', 'Audit delay notification')
            ->delete();

        DB::table('email_notifications')->insert([
            'module' => 'auditmanagement',
            'item' => 'Audit delay notification',
            'description' => 'when scheduled audit is not conducted by the due date',
            'email_content' => 'We noticed that the scheduled audit assigned to you has not been initiated.Please start the audit or reschedule it at your earliest convenience.',
            'is_blocked' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('email_notifications')
            ->where('module', '=', 'auditmanagement')
            ->where('item', '=', 'Audit delay notification')
            ->delete();
    }
}
