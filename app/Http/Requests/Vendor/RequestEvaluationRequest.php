<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestEvaluationRequest extends FormRequest
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
            'vendor_ids' => 'required|array',
            'vendor_ids.*' => 'required|exists:vendor,id',
            'end_user_id' => 'required|exists:users,id',
            'system_compliance_user_id' => 'required|exists:users,id',
            'supply_chain_representative_user_id' => 'required|exists:users,id'
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

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'vendor_ids' => 'vendor list',
            'end_user_id' => 'end user',
            'system_compliance_user_id' => 'system & compliance',
            'supply_chain_representative_user_id' => 'supply chain representative',
        ];
    }
}
