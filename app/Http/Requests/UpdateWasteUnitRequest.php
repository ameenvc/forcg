<?php

namespace App\Http\Requests;
use App\ModenaDisable;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWasteUnitRequest extends FormRequest
{
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
            'name' => 'required|string|max:255'
        ];
    }
    public function withValidator($validator)
    {
        if ($validator->fails()) {
            $models = ModenaDisable::First();
            return redirect()->back()->withErrors($validator->errors())->withInput()->with('model', $models);
        }
    }
}
