<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddRiskCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('risk_group_details')
            ->where('group_name', 'Operational')
            ->delete();
        $dataForInserting = [];
        $currentTime = now();
        foreach (config('constants.risk_management.category') as $key => $item) {
            foreach ($item['details'] as $detail) {
                $dataForInserting[] = [
                    'group_name' => 'Operational',
                    'risk_category' => $item['value'],
                    'risk_name' => $detail['name'],
                    'risk_desp' => $detail['description'],
                    'risk_driver' => $detail['driver'],
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
            }
        }

        if (count($dataForInserting)) {
            DB::table('risk_group_details')->insert($dataForInserting);
        }
    }
}
