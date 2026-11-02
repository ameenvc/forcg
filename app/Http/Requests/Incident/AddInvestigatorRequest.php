<?php

namespace App\Http\Requests\Incident;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddInvestigatorRequest extends FormRequest
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
                'leader' => 'required',
                'members' => 'required',
                'targetdate' =>'required|date|after:tomorrow'
               ];
    }
	
	public function messages()
    {
        return [
                'leader.required' => 'Please select Team Leader',
                'members.required' => 'Please select Team memebers',
                'targetdate.required' => 'Please enter Targetdate',
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
