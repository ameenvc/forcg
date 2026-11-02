<?php

namespace App\Http\Requests;

use App\Rules\LocationUniqueRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use stdClass;

class StoreLocationRequest extends FormRequest
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
                'required', 'string', 'regex:/^[A-Za-z0-9 ]+$/', 'max:255',
                Rule::unique('locations')->where(function ($query) {
                    $query->where('name', $this->name)
                        ->where(function ($q) {
                            if ($this->department != null) {
                                $q->where('locationable_id', $this->department)
                                    ->where('locationable_type', 'App\Departments');
                            } else if ($this->division != null) {
                                $q->where('locationable_id', $this->division)
                                    ->where('locationable_type', 'App\Division');
                            } else if ($this->area != null) {
                                $q->where('locationable_id', $this->area)
                                    ->where('locationable_type', 'App\Area');
                            }
                        })->whereNull('deleted_at');
                })
            ],
            'department' => [
                'nullable',
                'required_if:radio_select,department',
                'exists:departments,id'
            ],
            'division' =>
            [
                'nullable',
                'required_if:radio_select,division',
                'exists:divisions,id',
            ],
            'area' =>
            [
                'nullable',
                'required_if:radio_select,area',
                'exists:areas,id',
            ],
            'radio_select' => ['required', 'in:department,division,area'],

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
