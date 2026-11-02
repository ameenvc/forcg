<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class ReplaceCorrectiveTextToNcrCapitalCorrectiveAction extends Migration
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
                        'ncr & corrective actions module',
                        'NCR & Corrective Actions module'
                    )
                "),
                'updated_at' => now(),
            ]);
    }
}
