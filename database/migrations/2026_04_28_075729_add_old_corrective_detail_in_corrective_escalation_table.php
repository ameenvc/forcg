<?php

use Illuminate\Database\Migrations\Migration;
use App\CorrectiveEscalation;
use App\Corrective;

class AddOldCorrectiveDetailInCorrectiveEscalationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $today = \Carbon\Carbon::today();
            $datas = Corrective::where('correction_target_date', '<', $today)
                ->whereNotNull('correction_target_date')
                ->whereIn('processstage', [
                    'Awaiting actions', 'RC Updated- Correction Awaiting','Correction Proposed- RC Awaiting','Action Reviewed, Not OK'
                ])->select(['id','correction_target_date'])
                ->get();
            if($datas->isEmpty()){
                return;
            }
            foreach ($datas as $data) {
                CorrectiveEscalation::create([
                    'corrective_id' => $data->id,
                ]);
            }
        });
    }

}
