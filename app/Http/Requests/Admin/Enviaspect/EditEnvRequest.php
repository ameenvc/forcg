<?php

namespace App\Http\Requests\Admin\Enviaspect;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use stdClass;

class EditEnvRequest extends FormRequest
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
            'category' => ['required', 'string', 'in:probabilities,consequences,regulatory,quantities,control_measures'],
            'description' => ['required', 'string', 'max:400'],
            'weightage' => ['required', 'numeric'],
            'id' => ['required', 'integer']
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
