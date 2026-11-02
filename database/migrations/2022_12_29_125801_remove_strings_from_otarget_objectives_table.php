<?php

use App\Objective;
use Illuminate\Database\Migrations\Migration;

class RemoveStringsFromOtargetObjectivesTable extends Migration
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
            $o->otarget = preg_replace("/[^0-9.]/", "", $o->otarget);
            $o->oachieved = preg_replace("/[^0-9.]/", "", $o->oachieved);
            $o->where('Oid', $o->Oid)->update([
                'otarget' => $o->otarget == "" ? null : $o->otarget,
                'oachieved' => $o->oachieved == "" ? null : $o->oachieved
            ]);
        }
        foreach ($objectives as $o) {
            if ($o->otarget == "." || $o->oachieved == ".") {
                $o->where('Oid', $o->Oid)->update([
                    'otarget' => null,
                    'oachieved' => null
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
