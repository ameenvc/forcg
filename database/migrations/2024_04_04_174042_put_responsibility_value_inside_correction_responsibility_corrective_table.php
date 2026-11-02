<?php

use App\Corrective;
use Illuminate\Database\Migrations\Migration;

class PutResponsibilityValueInsideCorrectionResponsibilityCorrectiveTable extends Migration
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
          $c->correction_responsibility = $c->responsibility;
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
