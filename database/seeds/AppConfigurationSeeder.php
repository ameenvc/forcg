<?php

use Illuminate\Database\Seeder;

class AppConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'meta_key' => 'complaint_category_plant_dependency_options',
                'meta_value' => json_encode(['Linked to Plant', 'Independent']),
                'updated_by' => 1,
            ],
            [
                'meta_key' => 'complaint_category_plant_dependency',
                'meta_value' => 'Linked to Plant',
                'updated_by' => 1,
            ],
            [
                'meta_key' => 'enable_mfa',
                'meta_value' => false,
                'updated_by' => 0
            ]
        ];

        $existingKeys = DB::table('app_configurations')
            ->whereIn('meta_key', array_column($datas, 'meta_key'))
            ->pluck('meta_key')
            ->toArray();

        foreach ($datas as $key => $data) {
            if (!in_array($data['meta_key'], $existingKeys)) {
                DB::table('app_configurations')->insert($data);
            }
        }
    }
}
