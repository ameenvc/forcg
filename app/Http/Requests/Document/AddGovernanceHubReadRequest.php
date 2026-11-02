<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddGovernanceHubReadRequest extends FormRequest
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
		    'read_users' => 'required | exists:users,id',
            'notification_schedule' => 'required',
        ];
    }
	public function messages()
    {
        return [
		        'read_users.required' => 'Please select users',
		        'notification_schedule.required' => 'Please select notification schedule',
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
