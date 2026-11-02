<?php

namespace App\Helpers\Complaint;

class ComplaintHelper {
    public static function getTag($fieldRules, $customFields, $key, $defaultDisplayLabel) {
        $fieldRuleLabel = $defaultDisplayLabel;
        if ($fieldRules && isset($fieldRules[$key])) {
            $fieldRuleLabel = trim($fieldRules[$key]['field_display_label']);
        }
        return $tag = $fieldRuleLabel != $customFields[$key]['field_display_label'] ? $fieldRuleLabel : '';
        // return $tag = (($fieldRules && isset($fieldRules[$key]) && $fieldRules[$key]['field_display_label'] ? trim($fieldRules[$key]['field_display_label']) : $defaultDisplayLabel) != (trim($customFields[$key]['field_display_label']) ?? '')) ? ($fieldRules && isset($fieldRules[$key]) && $fieldRules[$key]['field_display_label'] ? $fieldRules[$key]['field_display_label'] : $defaultDisplayLabel) : '';
    }

    public static function getTagForExport($fieldRules, $currentHeading, $key, $defaultDisplayLabel) {
        $fieldRuleLabel = $defaultDisplayLabel;
        if ($fieldRules && isset($fieldRules[$key])) {
            $fieldRuleLabel = trim($fieldRules[$key]['field_display_label']);
        }
        return $tag = $fieldRuleLabel != $currentHeading ? $fieldRuleLabel : '';
    }
}
