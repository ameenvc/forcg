<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ReplaceCorrectiveTextToNcrCorrectiveAction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('email_notifications')
            ->where('module', 'correctiveaction')
            ->update([
                'email_content' => DB::raw("
                    REPLACE(
                        email_content,
                        'corrective action module',
                        'ncr & corrective actions module'
                    )
                "),
                'updated_at' => now(),
            ]);
    }
}
