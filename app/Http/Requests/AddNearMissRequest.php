<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddNearMissRequest extends FormRequest
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
            'title' => 'required|string|min:1|max:255',
            'observation' => 'required|string',
            'agreedaction' => 'nullable|string',
            'reporterType' => 'required|in:Employee,Guest',
            'reporterName' => 'nullable|string|max:255',
            'mobileNumber' => 'nullable|string',
            'employeeid' => 'required_if:reporterType,Employee|nullable|string|max:255',
            'companyName' => 'required_if:reporterType,Guest|nullable|string|max:255',
            'email' => 'required_if:reporterType,Guest|nullable|email|max:255',
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
            $models = ModenaDisable::First();
            return redirect()->back()->withErrors($validator->errors())->withInput()->with('model', $models);
        }
    }
}
