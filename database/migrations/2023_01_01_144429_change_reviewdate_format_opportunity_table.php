<?php

use Illuminate\Database\Migrations\Migration;
use App\Opportunity;

class ChangeReviewdateFormatOpportunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $opportunities = Opportunity::all();
        foreach ($opportunities as $o) {
            if ($o->reviewdate != null) {
                Opportunity::where('id', $o->id)->update([
                    'reviewdate' => date('Y-m-d H:i:s', strtotime($o->reviewdate))
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
