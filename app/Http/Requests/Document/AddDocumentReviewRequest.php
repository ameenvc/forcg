<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use stdClass;
class AddDocumentReviewRequest extends FormRequest
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
                    return $query->where('is_deleted', false);
            })],
            'score' => ['required', 'integer', 'not_in:0']
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
