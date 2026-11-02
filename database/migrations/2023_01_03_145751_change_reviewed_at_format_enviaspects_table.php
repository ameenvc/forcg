<?php

use Illuminate\Database\Migrations\Migration;
use App\Enviaspect;

class ChangeReviewedAtFormatEnviaspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $enviaspects = Enviaspect::all();
        foreach ($enviaspects as $e) {
            if ($e->reviewed_at != null) {
                Enviaspect::where('slno', $e->slno)->update([
                    'reviewed_at' => date('Y-m-d H:i:s', strtotime($e->reviewed_at))
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
