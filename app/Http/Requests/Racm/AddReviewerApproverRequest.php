<?php

namespace App\Http\Requests\Racm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddReviewerApproverRequest extends FormRequest
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
            'reviewers' => ['required'],
            'approver' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'reviewers.required' => 'The reviewers should not be empty.',
            'approver.required' => 'The approver should not be empty.',
        ];
    }
    public function attributes()
    {
        return [
            'reviewers' => 'Reviewers',
            'approver' => 'Approver'
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
