<?php

use App\Corrective;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class PutAFormatOfPostdateInCreatedAtCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $correctives = Corrective::all();
        foreach ($correctives as $c) {
            $c->created_at = Carbon::createFromFormat('d-m-Y h:i:s A', $c->postdate)->format('Y-m-d H:i:s');
            $c->save();
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
