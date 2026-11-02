<?php

namespace App\Http\Requests\Document;
use App\ModenaDisable;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'document_id' => [
                'required',
                'integer',
                Rule::exists('governance_hubs', 'id')->where(function ($query) {
                    return $query->where('id', $this->input('document_id'));
                }),
            ],
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('governance_hub_document_contents', 'title')
                    ->where(function ($query) {
                    return $query->where('document_id', $this->input('document_id'))
                                 ->where('is_deleted',false);
                }),
            ],
            
            'type' => ['required', 'string','max:255'],
            'responsibility' => ['required', 'array'],
            'rich_text' => ['required', 'array'],
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
            'rich_text.required' => 'The content should not be empty',
            'title.required' => 'The title should not be empty',
            'type.required' => 'The document type should not be empty',
            'title' => 'The title should not be duplicated.',

        ];
    }
}
