<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeReviewDateFormatRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $risks = DB::table('risks')->get();
        foreach ($risks as $r) {
            if ($r->review_date != null) {
                DB::table('risks')->where('Rid', $r->Rid)->update([
                    'review_date' => date('Y-m-d H:i:s', strtotime($r->review_date))
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
