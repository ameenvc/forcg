<?php

use Illuminate\Database\Migrations\Migration;
use App\Ohsrisk;

class ChangeReviewedAtFormatOhsriskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ohsrisks = Ohsrisk::all();
        foreach ($ohsrisks as $o) {
            if ($o->reviewed_at != null) {
                Ohsrisk::where('id', $o->id)->update([
                    'reviewed_at' => date('Y-m-d H:i:s', strtotime($o->reviewed_at))
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
