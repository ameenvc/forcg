<?php

namespace App\Http\Requests\Calibration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCalibrationRequest extends FormRequest
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
     * @return array<string>
     */
    public function rules(): array
    {
        return [
            'Instrument_no' => 'required|string|max:250|unique:calibration_instruments,Instrument_no,' . $this->get('id'),
        ];
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'Instrument_no' => 'instrument number',
        ];
    }
}
