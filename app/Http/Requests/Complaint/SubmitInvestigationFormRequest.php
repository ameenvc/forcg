<?php

namespace App\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use stdClass;

class SubmitInvestigationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return [
            'complaint_id' => ['nullable', 'integer', 'exists:complaint,id'],
            'occurrence' => ['nullable', 'in:0,one_time,regularly'],
            'system_related_problems' => ['nullable', 'string', 'max:1000'],
            'recent_changes' => ['nullable', 'in:1,0,-1'],
            'follow_procedures' => ['nullable', 'in:1,0,-1'],
            'contributing_factors' => ['nullable', 'array'],
            'contributing_factors.*' => ['nullable', 'in:human,technical,organizational,material,machine,design,method,envi_aspects,others'],
            'factors_details' => ['nullable', 'string', 'max:255'],
            'external_factors' => ['nullable', 'array'],
            'external_factors.*' => ['nullable', 'in:market_conditions,regulatory_changes,external_events,others'],
            'influence_details' => ['nullable', 'string', 'max:255'],
            'issue_foreseeable' => ['nullable', 'in:1,0,-1'],
            'prevent_reason' => ['nullable', 'string', 'max:1000'],
            'rootcause' => ['required', 'array'],
            'rootcause.*' => ['required', 'string'],
            'correctiveaction' => ['required', 'array'],
            'correctiveaction.*' => ['required', 'string'],
            'department' => ['required', 'array'],
            'department.*' => ['required', 'integer', Rule::exists('departments', 'id')],
            'rid' => ['nullable', 'array'],
            'rid.*' => ['nullable', 'integer', Rule::exists('complaint_root_cause_corrective_action_details', 'id')],
            'responsibility' => ['required', 'array'],
            'responsibility.*' => ['required', 'integer', Rule::exists('users', 'id')->where(function ($query) {
                return $query->where('is_email_verified', 1)->where('approved', 1);
            })],
            'target_date' => ['required', 'array'],
            'target_date.*' => ['required', 'date'],
        ];
    }
    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return JsonResponse
     */
    public function withValidator(Validator $validator)
    {           
        $json = new stdClass();
        if ($validator->fails()) {
            $json->success = false;
            $json->errors = $validator->errors();
        } else {
            $json->success = true;
        }

        return response()->json($json);
    }
}
