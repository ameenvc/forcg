<?php

namespace App\Http\Requests\Compliance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\ModenaDisable;

class ReviewRequest extends FormRequest
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
            'id' => ['required', 'integer', 'exists:compliance,Cid'],
            'frequency' => ['required', 'string', 'in:Yearly,Half yearly,Quarterly'],
            'authority' => ['required', 'string', 'max:255'],
            'issue' => ['required', 'string', 'max:2000'],
            'version' => ['required', 'string', 'max:255'],
            'regulation' => ['required', 'string', 'max:255'],
            'owner' => ['required', 'integer', 'exists:departments,id'],
            'aspect' => ['nullable', 'string', 'max:2000'],
            'requirement' => ['required', 'string', 'max:2000'],
            'status' => ['required', 'string', 'in:complied_under_review,not_complied_under_review,partially_complied_under_review'],
            'responsibility' => ['required','email','exists:users,email']
        ];

    }
    public function withValidator($validator)
    {
        if ($validator->fails()) {
            $models = ModenaDisable::first();

            return redirect()->back()->withErrors($validator->errors())->withInput()->with('models', $models);
        }
    }

    public function messages()
    {
        return [
            'person_control.required' => 'The Compliance Responsibility field is Required to be filled',
        ];
    }
}
