<?php

namespace App\Http\Requests\EmergencyResponse;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddEmergencyRequest extends FormRequest
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
            'situation_name' => 'required',
            'description'=>'required',
            'frequency' => 'required',
            'drill_team' => 'required',
            'next_drill' => 'required',
        ];
    }
	public function messages()
    {
        return [
                'department.required' => 'Please select Department',
                'situation_name.required' => 'Please Enter Emergency Situation',
                'description.required' => 'Please Enter Emergency Situation Description',
                'frequency.required' => 'Please select Frequency',
                'drill_team.required' => 'Please select Drill Team',
                'next_drill.required' => 'Please choose next drill date',
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
