<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NcrFieldHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            //Migrations for Predefined fields for process
            $systemFields = DB::table('process_ncr_configuration_fields')->get();
            if(count($systemFields)>0){
                foreach($systemFields as $field){
                    if (
                        !DB::table('process_ncr_field_histories')->where('field_name', $field->field_name)->where('modifier','System')
                        ->where('initial_name', $field->display_label)->where('updated_name', $field->display_label)
                        ->where('deleted_at', null)->exists()
                    ) {
                        DB::table('process_ncr_field_histories')->insert([
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
            }
            //Migrations for Predefined fields for vendor
             //Migrations for Predefined fields
            $systemFields = DB::table('vendor_ncr_configuration_fields')->get();
            if(count($systemFields)>0){
                foreach($systemFields as $field){
                    if (
                        !DB::table('vendor_ncr_field_histories')->where('field_name', $field->field_name)->where('modifier','System')
                        ->where('initial_name', $field->display_label)->where('updated_name', $field->display_label)
                        ->where('deleted_at', null)->exists()
                    ) {
                        DB::table('vendor_ncr_field_histories')->insert([
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
            }
        });
    }
}
