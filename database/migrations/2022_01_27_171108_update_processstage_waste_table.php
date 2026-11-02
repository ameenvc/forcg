<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Waste;

class UpdateProcessstageWasteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $wastes = Waste::all();
        foreach($wastes as $waste)
        {
            if($waste->process_stage == "Created"){
               $waste->process_stage = "Created (Awaiting HOD Approval)";
               $waste->save();
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
        $wastes = Waste::all();
        foreach($wastes as $waste)
        {
            if($waste->process_stage == "Created (Awaiting HOD Approval)"){
               $waste->process_stage = "Created";
               $waste->save();
            }
        }
    }
}
