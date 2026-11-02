<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddAuditChecklistRequest extends FormRequest
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
            'checklist_name' => 'required',
			'department_id_chk' => [
                'required',
                'exists:departments,id'
            ],
            'division_id_chk' =>
            [
                'nullable',
            ],
            'sub_division_id_chk' =>
            [
                'nullable',
            ],
        ];
    }
	public function messages()
    {
        return [
                'checklist_name.required' => 'Please enter checklist name',
				'department_id_chk.required' => 'Please select department',
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
