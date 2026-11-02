<?php

namespace App\Helpers\Calibration;

use App\Models\CalibrationField;
use Illuminate\Support\Facades\Log;

class CalibrationFormConfigurationHelper
{
    public static function getTag($fieldRules, $customFields, $key, $defaultDisplayLabel)
    {
        $fieldRuleLabel = $defaultDisplayLabel;
        if ($fieldRules && isset($fieldRules[$key])) {
            $fieldRuleLabel = trim($fieldRules[$key]['field_display_label']);
        }
        return $tag = $fieldRuleLabel != $customFields[$key]['field_display_label'] ? $fieldRuleLabel : '';
    }

    public static function getCalibrationFields() {
        return $calibrationFieldRules = CalibrationField::get()
            ->map(function ($calibrationField) {
                $calibrationFieldArray = $calibrationField->toArray();
                $metaArray = $calibrationField->calibrationFieldMetas
                    ->pluck('meta_value', 'meta_key')
                    ->toArray();
                unset($calibrationFieldArray['calibration_field_metas']);
                return array_merge($calibrationFieldArray, $metaArray);
            })->keyBy('field_name')->toArray();
    }

    public static function getCalibrationConfigurationPostMethodRules($type, $data, $edit = 0, $id = null) {
        $fields = json_decode($data, true);
        $rules = [];

        foreach ($fields as $fieldName => $field) {
            $inputName = self::getInputName($type, $fieldName, $edit);
            if ($inputName) {
                $label = $field['field_display_label'] ?? $fieldName;

                $rule = [];

                // required rule
                if ($field['field_is_mandatory']) {
                    $rule[] = 'required';
                    $messages[$inputName.'.required'] = $label.' is required.';
                } else {
                    $rule[] = 'nullable';
                }
                switch ($field['field_type']) {
                    case 'text':
                        $rule[] = 'string';
                        $messages[$inputName.'.string'] = $label.' must be a valid text.';
                        break;

                    case 'textarea':
                        $rule[] = 'string';
                        $messages[$inputName.'.string'] = $label.' must be a valid text.';
                        break;

                    case 'number':
                        $rule[] = 'numeric';
                        $messages[$inputName.'.numeric'] = $label.' must be a number.';
                        break;

                    case 'email':
                        $rule[] = 'email';
                        $messages[$inputName.'.email'] = $label.' must be a valid email.';
                        break;

                    case 'date':
                        $rule[] = 'date';
                        $messages[$inputName.'.date'] = $label.' must be a valid date.';
                        break;
                }
                if (isset($field['field_is_unique']) &&  $field['field_is_unique']) {
                    if ($edit) {
                        $rule[] = 'unique:calibration_instruments,'.$fieldName.','.$id;
                    } else {
                        $rule[] = 'unique:calibration_instruments,'.$fieldName;
                    }
                    $messages[$inputName.'.unique'] = 'The '.$label.' has already been taken.';
                }
                $rules[$inputName] = $rule;
            }
        }
        return [
            'rules' => $rules,
            'messages' => $messages
        ];
    }

    private static function getInputName($type, $fieldName, $edit) {
        $data = [
            'Instrument_no' => [
                'external' => 'instno_ext',
                'internal' => 'instno_int',
                'edit' => 'Instrument_no'
            ],
            'Instru_Manufacturer' => [
                'external' => 'instmanu_ext',
                'internal' => 'instmanu_int',
                'edit' => 'inst_manf'
            ],
            'Instrument_desc' => [
                'external' => 'instdesc_ext',
                'internal' => 'instdesc_int',
                'edit' => 'desc'
            ],
            'Holder' => [
                'external' => 'insttholder_ext',
                'internal' => 'insttholder_int',
                'edit' => 'holder'
            ],
            'Capacity' => [
                'external' => 'instcapacity_ext',
                'internal' => 'instcapacity_int',
                'edit' => 'capacity'
            ],
            'Agency' => [
                'external' => 'instagency_ext',
                'internal' => '',
                'edit' => $type == 'external' ? 'agency' : ''
            ],
            'Calib_procedure' => [
                'external' => 'procedure_ext',
                'internal' => 'procedure_int',
                'edit' => 'calib_procedure'
            ],
        ];

        return $data[$fieldName][$edit ? 'edit' : $type];
    }
}
