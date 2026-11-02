<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use stdClass;

class UpdateLocationRequest extends FormRequest
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
            'name' => [
                'required', 'regex:/^[A-Za-z0-9 ]+$/', 'max:255',
                Rule::unique('locations')->where(function ($query) {
                    $query->where('name', $this->name)->where('locationable_id', $this->locationable_id)
                        ->where('locationable_type', $this->locationable_type)
                        ->whereNull('deleted_at');
                })->ignore($this->id)
            ],
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

    public function messages()
    {
        return [
            'name.unique' => 'Combination of name & locationable_id & locationable_type is not unique',
        ];
    }
}
