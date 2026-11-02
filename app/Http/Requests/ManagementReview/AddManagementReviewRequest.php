<?php

namespace App\Http\Requests\ManagementReview;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddManagementReviewRequest extends FormRequest
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
            'date' => 'required',
            'time' => 'required',
            'location'=>'required | exists:management_locations,id',
            'agenda_type' => 'required',
            'participants' => 'required | exists:users,id',
            'management_participants' => 'required',
        ];
    }

    public function messages()
    {
        return [
                'date.required' => 'Please Enter Date',
                'time.required' => 'Please Enter Time',
                'location.required' => 'Please select Location',
                'agenda_type.required' => 'Please select Agenda Type',
                'participants.required' => 'Please select Participants',
                'management_participants.required' => 'Please select Management Participants',
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
