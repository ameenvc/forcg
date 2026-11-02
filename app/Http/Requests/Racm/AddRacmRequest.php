<?php

namespace App\Http\Requests\Racm;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class AddRacmRequest extends FormRequest
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
            'risk_factor_title' => ['required', 'string', 'max:255'],
            'process_id' => ['required'],
            'risk_factor_description' => ['required', 'string', 'max:800'],
            // 'reviewer_id' => ['required'],
            // 'approver_id' => ['required'],
            'unit_other' => ['required_if:unit,==,4']
        ];
        
    }

    public function messages()
    {
        return [
            'risk_factor_title.required' => 'The risk factor title should not be empty',
            'process_id.required' => 'The process name should not be empty',
            'risk_factor_description.required' => 'The risk factor description should not be empty.',
            // 'reviewer_id.required' => 'The reviewers should not be empty.',
            // 'approver_id.required' => 'The approvers should not be empty.',
            'unit_other.required_if' => 'The unit should not be empty.',
        ];
    }

    public function attributes()
    {
        return [
            'risk_factor_title' => 'Risk factor title',
            'process_id' => 'Process Name',
            'risk_factor_name' => 'Risk factor name',
            'risk_factor_description' => 'Risk factor description',
            // 'reviewer_id' => 'Reviewers',
            // 'approver_id' => 'Approvers'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'success' => false
        ], 422));
    }
}
