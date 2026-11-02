<?php

namespace App\Http\Requests\Customer;

use App\Rules\VendorMoreThanFiftyNaRule;
use Illuminate\Foundation\Http\FormRequest;
use StdClass;

class AjaxEvaluationRequest extends FormRequest
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
            'type' => ['nullable','string','in:product,service,project'],
            'id' => ['required','integer','exists:customer,id'],
            'qstn' => ['required', 'array', new VendorMoreThanFiftyNaRule],
            'score' => ['required','array'],
            'filled_by_name' => ['required_if:switch,1', 'string', 'max:255'],
            'filled_by_position' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'switch' => ['required', 'integer', 'in:0,1'],
            'score.*' => ['required_if:switch,1','integer','in:5,10,15']
        ];
    }

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
