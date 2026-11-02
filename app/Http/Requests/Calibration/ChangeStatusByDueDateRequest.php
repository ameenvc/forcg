<?php

namespace App\Http\Requests\Calibration;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangeStatusByDueDateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string
     */
    public function rules(): array
    {

        return [
            'target_ids'          => 'required|array',
            'target_ids.*'          => 'required',
            'date_of_calibrations' => 'required|array',
            'date_of_calibrations.*' => 'required',
            'certificate_numbers'  => 'required|array',
            'certificate_numbers.*'  => 'required',
            'next_due_dates'       => 'required|array',
            'next_due_dates.*'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'target_ids.*.required'          => 'The target calibration is required',
            'date_of_calibrations.*.required' => 'The date of calibration field is required',
            'certificate_numbers.*.required'  => 'The certificate number field is required',
            'next_due_dates.*.required'       => 'The next due date field is required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors'  => $validator->errors(),
            'success' => false
        ], 422));
    }
}
