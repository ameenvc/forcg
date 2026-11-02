<?php

use App\Corrective;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Type;

class ChangeTypesInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $correctives = Corrective::all();
        foreach ($correctives as $issue) {
            if ($issue->type === 'High' || $issue->type === 'Major NCR') {
                $high = Type::where('name', 'High')->orWhere('name', 'Major NCR')->first();
                $high = $high ? $high->order : 0;
                $issue->type = $high;
            } else if ($issue->type === 'Medium' || $issue->type === 'Moderate' || $issue->type === 'Minor NCR') {
                $medium = Type::where('name', 'Medium')->orWhere('name', 'Moderate')->orwhere('name', 'Minor NCR')->first();
                $medium = $medium ? $medium->order : 0;
                $issue->type = $medium;
            } else if ($issue->type === "Low" || $issue->type === 'OFI') {
                $low = Type::where('name', 'Low')->orWhere('name', 'OFI')->first();
                $low = $low ? $low->order : 0;
                $issue->type = $low;
            } else {
                $issue->type = 0;
            }
            $issue->save();
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
