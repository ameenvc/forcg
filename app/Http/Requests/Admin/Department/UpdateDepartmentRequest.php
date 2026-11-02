<?php

namespace App\Http\Requests\Admin\Department;

use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
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
            //'department' => 'required',
            'code' => 'required | unique:departments,code,'.$this->id.',id',
            //'hod'=>'required | exists:users,id',
            'dept' => 'required'
            //'scope' => 'required',
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
