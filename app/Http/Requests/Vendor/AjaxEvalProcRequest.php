<?php

namespace App\Http\Requests\Vendor;

use App\Rules\VendorMoreThanFiftyNaRule;
use Illuminate\Foundation\Http\FormRequest;
use StdClass;


class AjaxEvalProcRequest extends FormRequest
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
            'type' => 'required|string|in:Product,Service Provider',
            'id' => 'required|integer|exists:vendor,id',
            'qstn' => ['required', 'array', new VendorMoreThanFiftyNaRule],
            'score' => 'required|array',
            'score.*' => 'required|integer|in:0,5,10,15',
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
