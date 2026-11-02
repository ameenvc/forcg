<?php

namespace App\Http\Requests\Audit;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class NeedAuditeeConfirmRequest extends FormRequest
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
            'is_need_verify' => ['required', Rule::in(['1', '2'])],
            'hod_user' => ['required_if:is_need_verify,==,"1"', 'nullable', 'exists:users,id'],
        ];

    }

    public function messages()
    {
        return [
            'hod_user.required_if' => 'HOD user is required',
            'hod_user.exists' => 'HOD user is invalid',
        ];
    }

    public function attributes()
    {
        return [
            'is_need_verify' => 'item',
            'hod_user' => 'HOD user',
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
