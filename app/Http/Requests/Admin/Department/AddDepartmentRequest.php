<?php

namespace App\Http\Requests\Admin\Department;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddDepartmentRequest extends FormRequest
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
            'department' => 'required',
            'depcode' => 'required | unique:departments,code',
            'hod'=>'required | exists:users,id',
            'scope' => 'required',
        ];
    }

	public function messages()
    {
        return [
                'department.required' => 'Please Enter Department',
                'depcode.required' => 'Please Enter Department Code',
                'hod.required' => 'Please select HOD',
                'scope.required' => 'Please select Scope',
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
