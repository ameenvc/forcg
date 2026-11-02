<?php

namespace App\Http\Requests\Admin\Calibration;

use Illuminate\Foundation\Http\FormRequest;

class AddFrequencyRequest extends FormRequest
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
            'freq' => ['required', 'numeric', 'min:1'],
            'freq_time' => ['required', 'in:Year,Month,Day'],
        ];
    }

    public function attributes()
    {
        return [
            'freq' => 'frequency',
            'freq_time' => 'frequency time',
        ];
    }
}
