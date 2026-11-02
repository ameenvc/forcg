<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddGovernanceHubChangeRequest extends FormRequest
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
            'change_reason' => 'required'
        ];
    }
	public function messages()
    {
        return [
		        'change_reason.required' => 'Please Enter Reason for change',
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
