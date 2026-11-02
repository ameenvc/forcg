<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddWasteDisposalMethodRequest extends FormRequest
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
            'disposal_method' => 'required|string|max:255'
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
