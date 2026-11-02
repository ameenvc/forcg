<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Opportunity;

class ChangeNdareasonValueBugInOpportunityModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $datas = Opportunity::select(['id','updatedat','ndareason'])->where('ndareason','NULL')->get();
        foreach($datas as $data){
            $data->ndareason = null;
            $data->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $datas = Opportunity::select(['id','updatedat','ndareason'])->whereNull('ndareason')->get();
        foreach($datas as $data){
            $data->ndareason = 'NULL';
            $data->save();
        }
    }
}
