<?php

use Illuminate\Database\Migrations\Migration;
use App\Environment;

class ChangeReviewedAtFormatEnvironmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $environments = Environment::all();
        foreach ($environments as $e) {
            if ($e->reviewed_at != null) {
                Environment::where('Eid', $e->Eid)->update([
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
