<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddDraftReviewApproveRequest extends FormRequest
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
            'reviewer_value' => 'required | exists:users,email',
			'approver_value' => 'required | exists:users,email',
        ];
    }
	public function messages()
    {
        return [
		        'reviewer_value.required' => 'Please select Reviewers',
		        'approver_value.required' => 'Please select Approvers',
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
