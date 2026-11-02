<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use stdClass;

class ChangeOhsRiskownerRequest extends FormRequest
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
            'ohs_id' => 'required|exists:ohs,id',
            'users' => 'required|array',
            'users.*' => 'required|integer|exists:users,id'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return \Illuminate\Http\JsonResponse
     */
    public function withValidator($validator)
    {
        if ($validator->fails()) {
            $json = new stdClass();
            $json->success = false;
            $json->errors = $validator->errors();
        } else {
            $json = new stdClass();
            $json->success = true;
        }

        return response()->json($json);
    }
}
