<?php


namespace App\Helpers\Complaint;
use App\Models\ComplaintTemplate;
use App\Models\ComplaintTemplateSectionField;
use Illuminate\Support\Str;

class ComplaintFormConfigurationHelper
{
    public static function getComplaintTemplateSectionsFields() {
        $complaintTemplateSectionFields = ComplaintTemplateSectionField::whereHas('complaintTemplateSection.complaintTemplate', function ($query) {
            $query->where('is_active', 1);
        })->get();

        return $complaintTemplateSectionFields->keyBy('field_name')->toArray();
    }

    public static function getActiveComplaintTemplate() {
        return $activeComplaintTemplate = ComplaintTemplate::where('is_active', 1)->first();
    }

    public static function getSourceOfIssue($templateId) {
        $data = ComplaintTemplateSectionField::whereHas('complaintTemplateSection.complaintTemplate', function($query)use($templateId) {
                $query->where('id', $templateId);
            })->where('field_name', 'source_of_issue')->first();
        if ($data) {
            return $data;
        }
        return null;
    }

    public static function getSourceOfIssueDisplayLabel() {
        $activeTemplate = self::getActiveComplaintTemplate();
        if ($activeTemplate->id == 2) {
            $data = ComplaintTemplateSectionField::whereHas('complaintTemplateSection.complaintTemplate', function($query) {
                $query->where('is_active', 1);
            })->where('field_name', 'source_of_issue')->first();
            if ($data) {
                return $data->field_display_label;
            }
        }
        return null;
    }

    public static function getComplaintConfigurationPostMethodRules($data) {
        $fields = json_decode($data, true);
        $rules = [];

        foreach ($fields as $fieldName => $field) {
            if($field['enable_field'] && $field['field_name'] != 'source_of_issue') {
                $inputName = $fieldName; // optional if you change input names
                $label = $field['field_display_label'] ?? $fieldName;

                $rule = [];

                // required rule
                if ($field['field_is_mandatory']) {
                    $rule[] = 'required';
                    $messages[$inputName.'.required'] = $label.' is required.';
                } else {
                    $rule[] = 'nullable';
                }

                if($field['is_multiple']) {
                    $ruleParent = $rule;
                    $ruleChild = $rule;
                    $ruleParent[] = 'array';
                    $rules[$inputName] = $ruleParent;

                    switch ($field['field_type']) {
                        case 'text':
                            $ruleChild[] = 'string';
                            $messages[$inputName.'.*.string'] = $label.' must be a valid text.';
                            break;

                        case 'number':
                            $ruleChild[] = 'numeric';
                            $messages[$inputName.'.*.numeric'] = $label.' must be a number.';
                            break;

                        case 'email':
                            $ruleChild[] = 'email';
                            $messages[$inputName.'.*.email'] = $label.' must be a valid email.';
                            break;

                        case 'date':
                            $ruleChild[] = 'date';
                            $messages[$inputName.'.*.date'] = $label.' must be a valid date.';
                            break;
                    }
                    $rules[$inputName.'.*'] = $ruleChild;

                } else {
                    // type rule
                    switch ($field['field_type']) {
                        case 'text':
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
                    $rules[$inputName] = $rule;
                }
            }
        }

        return [
            'rules' => $rules,
            'messages' => $messages
        ];
    }

    public static function getProductFields() {
        $data = ComplaintTemplateSectionField::whereHas('complaintTemplateSection.complaintTemplate', function ($query) {
            $query->where('id', 1);
        })->get();

        return $data->keyBy('field_name')->toArray();
    }

    public static function getProjectFields() {
        $data = ComplaintTemplateSectionField::whereHas('complaintTemplateSection.complaintTemplate', function ($query) {
            $query->where('id', 2);
        })->get();

        return $data->keyBy('field_name')->toArray();
    }
}
