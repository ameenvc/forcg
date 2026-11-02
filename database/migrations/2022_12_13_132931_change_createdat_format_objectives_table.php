<?php

use App\Objective;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCreatedatFormatObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $objectives = Objective::all();
        foreach ($objectives as $o) {
            if ($o->createdat != null) {
                Objective::where('Oid', $o->Oid)->update([
                    'createdat' => date('Y-m-d H:i:s', strtotime($o->createdat))
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
