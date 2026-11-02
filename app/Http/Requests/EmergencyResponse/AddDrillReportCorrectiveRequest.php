<?php

namespace App\Http\Requests\EmergencyResponse;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddDrillReportCorrectiveRequest extends FormRequest
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
            'type' => 'required',
            'title'=>'required',
            'responsibility' => 'required',
            'observation' => 'required',
            'agreedaction' => 'required',
			'duedate' => 'required|date|after:tomorrow',
        ];
    }
	public function messages()
    {
        return [
                'department.required' => 'Please Choose Department',
                'type.required' => 'Please Choose Type',
                'title.required' => 'Please Enter Title',
                'responsibility.required' => 'Please select Responsibility',
                'observation.required' => 'Please Enter Observation',
                'agreedaction.required' => 'Please Enter Recommended Action',
				'duedate.required' => 'Please Enter DueDate',
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
