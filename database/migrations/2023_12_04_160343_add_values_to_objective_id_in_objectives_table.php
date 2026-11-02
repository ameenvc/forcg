<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Objective;

class AddValuesToObjectiveIdInObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $datas = Objective::all();
        foreach($datas as $data){
        $code = DB::table('departments')->where('id',$data->department)->value('code');
        $count_value = $data->count;
        $cat = strtoupper(substr($code, 0, 3));
        $objective_id = $cat.$count_value;
        Objective::where('Oid',$data->Oid)->update(['objective_id'=> $objective_id]);
       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $datas = Objective::all();
       foreach($datas as $data){
        Objective::where('Oid',$data->Oid)->update(['objective_id'=>NULL]);
       }
    }
}
