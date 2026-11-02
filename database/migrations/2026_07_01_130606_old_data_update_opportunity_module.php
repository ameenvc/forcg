<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Opportunity;

class OldDataUpdateOpportunityModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $datas = Opportunity::whereIn('status',['Disapproved','Approved','Completed','Closed'])
                ->select(['id','status','is_reviewed','is_closed'])
                ->get();
        foreach($datas as $data){
            if($data->status=='Closed' || $data->remarks!=null){
                $data->is_reviewed=true;
                $data->is_closed=true;
            }else if($data->status=='Disapproved' || $data->status=='Approved' || $data->status=='Completed'){
                $data->is_reviewed=true;
                $data->is_closed=false;
            }else{
                $data->is_reviewed=false;
                $data->is_closed=false; 
            }
            $data->save();
        }
    }
}
