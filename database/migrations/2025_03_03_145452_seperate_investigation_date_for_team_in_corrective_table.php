<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Corrective;

class SeperateInvestigationDateForTeamInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $team_but_no_responsibility = Corrective::where('team_lead_id','!=',false)->whereNull('responsibility')->whereNotNull('target_date')
                                        ->get();
        foreach($team_but_no_responsibility as $team){        
            $team->update([
                'investigation_date' => $team->target_date,
                'target_date' => null,
                'updated_at' => $team->updated_at
            ]);
        }
                                        
        $team_with_responsibility = Corrective::where('team_lead_id','!=',false)->whereNotNull('responsibility')->whereNotNull('target_date')
                                  ->get();
        foreach($team_with_responsibility as $team){
            $targetDate = $team->target_date ? explode(',',$team->target_date)[0] : null;
            $team->update([
                'investigation_date' => $targetDate,
                'target_date' => $team->responsibility=='0' ? null : $team->target_date,
                'updated_at' => $team->updated_at
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $team_with_investigation_date = Corrective::whereNotNull('investigation_date')->get();
        foreach ($team_with_investigation_date as $team) {
            $team->update([
                'target_date' => $team->investigation_date,
                'investigation_date' => null
            ]);
        }
        $team_with_responsibility = Corrective::where('team_lead_id','!=',false)->whereNotNull('responsibility')->whereNotNull('target_date')->get();
        foreach($team_with_responsibility as $team){
            $team->update([
                'target_date' => $team->responsibility=='0' ? $team->investigation_date : null,
                'investigation_date' => null,
            ]);
        }
    }
}
