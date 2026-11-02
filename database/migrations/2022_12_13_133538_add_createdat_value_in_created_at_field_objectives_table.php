<?php

use App\Objective;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCreatedatValueInCreatedAtFieldObjectivesTable extends Migration
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
                DB::table('objectives')->where('Oid', $o->Oid)->update([
                    'created_at' => $o->createdat,
                ]);
            }
        }
        if (Schema::hasColumn('objectives', 'createdat')) {
            Schema::table('objectives', function (Blueprint $table) {
                $table->dropColumn('createdat');
            });
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
