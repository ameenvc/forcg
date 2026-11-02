<?php

namespace App\Http\Requests\Compliance;

use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;

class ChangeResponsibilityRequest extends FormRequest
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
            'id' => ['required', 'integer', 'exists:compliance,Cid'],
            'responsibility' => ['required', 'email', 'exists:users,email'],
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->fails()) {
            $models = ModenaDisable::first();

            return redirect()->back()->withErrors($validator->errors())->withInput()->with('models', $models);
        }
    }
}
