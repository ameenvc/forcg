<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Risk;

class UpdateStatusToAwaitingPeriodicReviewInRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risks', function (Blueprint $table) {
            $risks = Risk::all();
            $today = date('Y-m-d');
            if(count($risks)>0){
                $initial_year = Risk::select(DB::raw('YEAR(created_at) as year'))
                                ->orderBy('created_at', 'asc')
                                ->first()
                                ->year; 
                if($initial_year<=2024){
                    $required_years=[];
                    for($i=$initial_year;$i<=2024;$i++){
                        $required_years[] = $i;
                    }
                    $years_string = implode(',', $required_years);
                    $risk_datas = Risk::where('action_status','Active Risk')
                                    ->whereNULL('periodic_review_date')
                                    ->whereDate(DB::raw('DATE_ADD(review_date, INTERVAL 1 YEAR)'), '<=', $today)
                                    ->whereRaw("YEAR(created_at) IN ($years_string)")
                                    ->get();
                    foreach($risk_datas as $risk_data){
                        $risk_data->action_status = "Awaiting Periodic Review";
                        $risk_data->setKeyName('Rid'); 
                        $risk_data->save();
                    }
                }                  
            }
        });
    }
}
