<?php

namespace App\Http\Requests;
use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWasteTargetRequest extends FormRequest
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
            'items' => 'required|string|max:255',
            'qty' => 'required|string|max:255',
            'approver' => 'required|exists:users,id'
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
