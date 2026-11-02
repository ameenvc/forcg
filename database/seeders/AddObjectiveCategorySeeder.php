<?php

use Illuminate\Database\Seeder;

class AddObjectiveCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('objective_category_details')->truncate();
        $dataForInserting = [];
        $currentTime = now();
        foreach (config('constants.objectives.category') as $key => $item) {
            foreach ($item['details'] as $detail) {
                $dataForInserting[] = [
                    'objective_category' => $item['value'],
                    'objective_name' => $detail['name'],
                    'objective_description' => $detail['description'],
                    'objective_target' => $detail['target'],
                    'objective_unit' => $detail['unit'],
                    'objective_methodology' => $detail['Methodology'],
                    'objective_resources' => $detail['resources'],
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
            }
        }

        if (count($dataForInserting)) {
            DB::table('objective_category_details')->insert($dataForInserting);
        }
    }
}
