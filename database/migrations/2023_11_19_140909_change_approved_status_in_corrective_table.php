<?php

use App\Corrective;
use Illuminate\Database\Migrations\Migration;

class ChangeApprovedStatusInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $correctives = Corrective::all();
        foreach($correctives as $item) {
            if($item->processstage === 'Approved') {
                $item->processstage = 'Awaiting actions';
                $item->save();
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
