<?php

namespace App\Http\Requests\Admin\CustomerSatisfaction;

use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerDetailRequest extends FormRequest
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
            'id' => ['required', 'integer',
                Rule::exists('customer_details', 'id')->where(function ($query) {
                    return $query->where('is_deleted', 0);
                }),
            ],
            'qstn' => ['required','string','max:1000'],
            //'type' => ['required','string','in:product,service,project'],
            'score' => ['nullable','integer'],
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
            $models = ModenaDisable::first();
            return redirect()->back()->withErrors($validator->errors())->withInput()->with('model', $models);
        }
    }
}
