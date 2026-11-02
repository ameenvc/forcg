<?php

namespace App\Http\Requests\Admin\Department;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddSubdivisionRequest extends FormRequest
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
            'sub_division_name_value' => 'required|string|max:255',
            'division_owner'=>'required | exists:users,id',
        ];
    }
	
	public function messages()
    {
        return [
                'sub_division_name_value.required' => 'Please enter Subdivision name',
                'division_owner.required' => 'Please select Division owner',
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
