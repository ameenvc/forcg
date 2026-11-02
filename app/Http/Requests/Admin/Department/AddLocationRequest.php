<?php

namespace App\Http\Requests\Admin\Department;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddLocationRequest extends FormRequest
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
            'location_value' => 'required|string|max:255',
        ];
    }
	public function messages()
    {
        return [
                'location_value.required' => 'Please enter Location name',
               ];
    }
	
	public function withValidator($validator)
    {
        if ($validator->fails()) {
            $models = ModenaDisable::First();
            return redirect()->back()->withErrors($validator->errors())->withInput()->with('model', $models);
        }

    }
}
