<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use stdClass;

class StoreDraftCommentRequest extends FormRequest
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
            'comment' => ['required', 'string'],
            'document_id' => [
                'required',
                Rule::exists('governance_hub_drafts', 'id')
            ],
            'reply_id' => [
                'nullable',
                Rule::exists('governance_hub_drafts_comments', 'id')->where(function ($query) {
                    return $query->where('is_deleted', false);
                })
            ]
        ];
    }
	
	public function withValidator(Validator $validator): JsonResponse
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
