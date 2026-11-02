<?php

use App\Corrective;
use Illuminate\Database\Migrations\Migration;

class ChangeActionPropsedNameCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $correctives = Corrective::all();
        foreach($correctives as $c) {
            if($c->processstage === 'Actions Proposed') {
                $c->processstage = 'Actions Proposed- Review Awaiting';
                $c->save();
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
