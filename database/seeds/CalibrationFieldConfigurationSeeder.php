<?php

use Illuminate\Database\Seeder;
use App\Models\CalibrationField;

class CalibrationFieldConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $calibrationFields = require database_path('data/calibrationFields.php');

        foreach ($calibrationFields as $calibrationField) {

            $fieldData = $calibrationField;
            unset($fieldData['meta']);

            $cField = CalibrationField::firstOrCreate(
                ['field_name' => $fieldData['field_name']],
                $fieldData
            );

            if (!empty($calibrationField['meta'])) {
                foreach ($calibrationField['meta'] as $meta) {
                    $cField->calibrationFieldMetas()->firstOrCreate(
                        ['meta_key' => $meta['meta_key']],
                        $meta
                    );
                }
            }
        }

    }
}
