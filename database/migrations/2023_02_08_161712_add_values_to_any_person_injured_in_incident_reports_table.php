<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\IncidentReport;

class AddValuesToAnyPersonInjuredInIncidentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $datas = IncidentReport::all();
		 foreach($datas as $data)
		 {
			if($data->injuredperson=='NA' || $data->injuredperson=='Non' || $data->empid=='NA' || $data->empid=='Non')
			{
				$data->any_person_injured = 'no';
			}
			else
			{
				$data->any_person_injured = 'yes';
			}
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
        $datas = IncidentReport::all();
        foreach($datas as $data)
         {
          $data->any_person_injured = '';
          $data->save();
         }
    }
}
