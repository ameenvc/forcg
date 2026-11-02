<?php

use App\RiskHistory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRankAndRankUpdatedValueRiskhistoryTable extends Migration
{
    public function helper($input)
    {
        switch ($input) {
            case 'High':
                $output = 3;
                break;
            case 'Moderate':
            case 'Medium':
                $output = 2;
                break;
            case 'Low':
                $output = 1;
                break;
            default:
                $output = 1;
                break;
        }
        return $output;
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $risk_histories = RiskHistory::all();
        foreach ($risk_histories as $r) {
            if($r->rank != null || $r->rank_updated != null) {
                $r->rank = $r->rank != null ? $this->helper($r->rank) : null;
                $r->rank_updated = $r->rank_updated != null ? $this->helper($r->rank_updated) : null;
                $r->save();
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
