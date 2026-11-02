<?php

namespace App\Http\Requests\Customer;

use App\Rules\CommaSeparatedEmailsRule;
use Illuminate\Validation\Rule;
use stdClass;
use Illuminate\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

class AjaxSendMailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array
    {
        return [
            'type' => ['required','string','in:product,service,project'],
            'id' => ['required','integer',Rule::exists('customer', 'id')->where(function ($query) {
                return $query->where('is_deleted', false);
            })],
            'customer_emails' => ['required', new CommaSeparatedEmailsRule()]
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
