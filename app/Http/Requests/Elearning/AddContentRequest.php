<?php

namespace App\Http\Requests\Elearning;

use App\ModenaDisable;
use App\Rules\ContentTypeOfFileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddContentRequest extends FormRequest
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
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')->where(function ($query) {
                    return $query->where('is_deleted', false);
                })],
            'topic_title' => ['required_if:radio_add_content,no', 'max:255'],
            'heading' => ['required_if:radio_add_content,yes', 'integer', 'exists:course_headings,id'],
            'rich_text' => ['required'],
            'radio_add_content' => ['required', 'in:yes,no'],
            'content_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->fails()) {
            $models = ModenaDisable::first();

            return redirect()->back()->withErrors($validator->errors())->withInput()->with('model', $models);
        }

    }
    public function messages()
    {
        return [
            'radio_add_content.required' => 'The question is Required to be answered',
        ];
    }
}
