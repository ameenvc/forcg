<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateAuditDelayNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");

        $default_values = [
            [
                'module' => 'auditmanagement',
                'item' => 'Audit delay notification',
                'description' => 'when scheduled audit is not conducted by the due date',
                'email_content' => 'We noticed that the scheduled audit assigned to you has not been initiated.Please start the audit or reschedule it at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ]
        ];

        foreach ($default_values as $value) {
            $found = DB::table('email_notifications')->where('module', $value['module'])
                ->where('item', $value['item'])
                ->where('description', $value['description'])
                ->where('is_blocked', $value['is_blocked'])
                ->first();
            if ($found) {
                DB::table('email_notifications')->update([
                    'email_content' => $value['email_content'],
                    'updated_at' => $time,
                ]);
            }

        }
    }
}
