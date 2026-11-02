<?php

namespace App\Http\Requests\Corrective;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use stdClass;

class SubmitChangeInvestigationFormRequest extends FormRequest
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
            'corrective_id' => ['nullable', 'integer', 'exists:corrective,id'],
            'lead' => ['required', 'integer', Rule::exists('users', 'id')->where(function ($query) {
                return $query-> where('approved', 1);
            })],
            'team_members' => ['required', 'array'],
            'team_members.*' => ['required', 'integer', Rule::exists('users', 'id')->where(function ($query) {
                return $query-> where('approved', 1);
            })],
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
