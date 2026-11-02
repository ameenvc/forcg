<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class DocumentAddMasterListRequest extends FormRequest
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
            // 'riskname' => 'required|max:100',
            // 'riskdec' => 'required|max:300',
            // 'riskdri' => 'required|max:300',
            // 'date' =>'before:tomorrow'
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
