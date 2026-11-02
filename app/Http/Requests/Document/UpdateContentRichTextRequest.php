<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use stdClass;

class UpdateContentRichTextRequest extends FormRequest
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
            'content_id' => [
                'required',
                'integer',
                Rule::exists('governance_hub_document_contents', 'id')->where(function ($query) {
                    return $query->where('is_deleted', false);
                })
            ],
            'document_id' => [
                'required',
                'integer',
                Rule::exists('governance_hub_document_contents', 'document_id')->where(function ($query) {
                    return $query->where('is_deleted', false);
                }),
            ],
            'title' => ['required', 'string', 'max:255'],
            'responsibility' => ['nullable', 'array'],
            'rich_text' => ['nullable', 'array'],
        ];
    }
    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return JsonResponse
     */
    public function withValidator(Validator $validator)
    {
        $json = new stdClass();
        if ($validator->fails()) {
            $json->success = false;
            $json->errors = $validator->errors();
        } else {
            $json->success = true;
        }
        return response()->json($json);
    }
}
