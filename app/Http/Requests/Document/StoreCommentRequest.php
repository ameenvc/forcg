<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use stdClass;

class StoreCommentRequest extends FormRequest
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
            'comment' => ['required', 'string', 'max:2000'],
            'document_id' => [
                'required', 'integer',
                // Rule::exists('governance_hubs', 'id')->where(function ($query) {
                //     return $query->where('is_deleted', false);
                // })
                Rule::exists('governance_hubs', 'id')
            ],
            'reply_id' => [
                'nullable', 'integer',
                Rule::exists('governance_hub_comments', 'id')->where(function ($query) {
                    return $query->where('is_deleted', false);
                })
            ]
        ];
    }
    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return JsonResponse
     */
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
