<?php

return [
    [
        'enable_field' => 1,
        'field_name' => "Instrument_no",
        'field_type' => "text",
        'default_display_label' => "Instrument number",
        'field_display_label' => "Instrument number",
        'field_is_mandatory' => 1,
        'updated_by' => 0,
        'order' => 1,
        'meta' => [
            ['meta_key' => 'lock_enable_field', 'meta_value' => true],
            ['meta_key' => 'lock_is_mandatory', 'meta_value' => true],
            ['meta_key' => 'field_is_unique', 'meta_value' => true]
        ]
    ],
    [
        'enable_field' => 1,
        'field_name' => "Instru_Manufacturer",
        'field_type' => "text",
        'default_display_label' => "Instrument manufacturer",
        'field_display_label' => "Instrument manufacturer",
        'field_is_mandatory' => 1,
        'updated_by' => 0,
        'order' => 2
    ],
    [
        'enable_field' => 1,
        'field_name' => "Instrument_desc",
        'field_type' => "textarea",
        'default_display_label' => "Instrument description",
        'field_display_label' => "Instrument description",
        'field_is_mandatory' => 1,
        'updated_by' => 0,
        'order' => 3
    ],
    [
        'enable_field' => 1,
        'field_name' => "Holder",
        'field_type' => "text",
        'default_display_label' => "Instrument holder/location",
        'field_display_label' => "Instrument holder/location",
        'field_is_mandatory' => 1,
        'updated_by' => 0,
        'order' => 4
    ],
    [
        'enable_field' => 1,
        'field_name' => "Capacity",
        'field_type' => "text",
        'default_display_label' => "Instrument capacity/range",
        'field_display_label' => "Instrument capacity/range",
        'field_is_mandatory' => 1,
        'updated_by' => 0,
        'order' => 5
    ],
    [
        'enable_field' => 1,
        'field_name' => "Agency",
        'field_type' => "text",
        'default_display_label' => "Calibration agency",
        'field_display_label' => "Calibration agency",
        'field_is_mandatory' => 0,
        'updated_by' => 0,
        'order' => 6
    ],
    [
        'enable_field' => 1,
        'field_name' => "Calib_procedure",
        'field_type' => "textarea",
        'default_display_label' => "Calibration procedure",
        'field_display_label' => "Calibration procedure",
        'field_is_mandatory' => 0,
        'updated_by' => 0,
        'order' => 7
    ],
];
