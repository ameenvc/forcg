<?php

namespace App\Http\Requests\Elearning;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Http\JsonResponse;
use stdClass;
use App\Rules\CommaSeperatedStringRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
            'edit_name' => ['required', 'string', 'max:255'],
            'edit_labels' => [new CommaSeperatedStringRule()],
            'edit_departments' => ['nullable', 'array'],
            'edit_deprtments.*' => [
                'nullable',
                'exists:departments,id'
            ],
            'edit_cover' => ['nullable', 'sometimes', 'image', 'mimes:png,jpg,jpeg']
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
