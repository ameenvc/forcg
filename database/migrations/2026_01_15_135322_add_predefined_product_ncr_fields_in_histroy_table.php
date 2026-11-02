<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPredefinedProductNcrFieldsInHistroyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            //Migrations for Predefined fields
            $systemFields = DB::table('product_ncr_configuration_fields')->get();
            if(count($systemFields)>0){
                foreach($systemFields as $field){
                    DB::table('product_ncr_field_histories')->insert([
                        'field_name' => $field->field_name,
                        'previous_name' => '--',
                        'updated_name' => $field->display_label,
                        'initial_name' => $field->display_label,
                        'modifier' => 'System',
                        'modified_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        });
        
    }
}
