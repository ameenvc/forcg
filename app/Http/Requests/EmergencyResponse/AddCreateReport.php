<?php

namespace App\Http\Requests\EmergencyResponse;

use Illuminate\Foundation\Http\FormRequest;
use App\ModenaDisable;

class AddCreateReport extends FormRequest
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
            'drill_type' => 'required',
            'drill_date'=>'required',
            'time_alarm' => 'required',
            'time_drill' => 'required',
            'evacuate_time' => 'required',
			'drill_team' => 'required',
            'situation' => 'required',
            'weather'=>'required',
            'participants' => 'required|numeric',
            'employees_work_area' => 'required',
            'evacuation_cnt' => 'required',
			'problems' => 'required',
			'evacuate_immediate' => 'required',
			'safety_check' => 'required',
			'personnel_informed' => 'required',
			'equipment_function' => 'required',
			'evacuate_workareas' => 'required',
			'remain_outside' => 'required',
			'knowledge_duties' => 'required',
			'drill_orderly' => 'required',
			'evacuation_speed' => 'required',
			'procedure_effective' => 'required',
			'communication' => 'required',
			'drill_rating' => 'required',
			'mitigation' => 'required',
        ];
    }
	
	public function messages()
    {
        return [
                'drill_date.required' => 'Please select Date',
                'time_alarm.required' => 'Please enter Time Alarm Sounded',
                'time_drill.required' => 'Please enter Time Drill Conducted',
                'evacuate_time.required' => 'Please enter Time taken to Evacuate',
                'drill_team.required' => 'Please select Drill Team',
                'situation.required' => 'Please choose Situation',
				'drill_team.required' => 'Please select Drill Team',
                'situation.required' => 'Please choose Situation',
				'weather.required' => 'Please select Weather Condition',
                'participants.required' => 'Please enter Number of Participants',
				'employees_work_area.required' => 'Please enter Number of Employees working in that area',
                'evacuation_cnt.required' => 'Please enter Evacuation Count',
				'problems.required' => 'Please select Problem Encountered',
                'evacuate_immediate.required' => 'Please choose site personnel immediately begin to evacuate',
				'safety_check.required' => 'Please choose Site Safety Personnel check restrooms and confined areas',
				'personnel_informed.required' => 'Please choose was all personnel are informed of the emergency',
				'equipment_function.required' => 'Please choose did appropriate emergency equipment function properly?',
				'evacuate_workareas.required'=>'Please choose did everyone evacuate the work areas',
				'remain_outside.required' => 'Please choose did everyone remain outside and wait for further instructions',
				'knowledge_duties.required' => 'Please choose site personnel immediately begin to evacuate',
				'drill_orderly.required' => 'Please choose were the drill conducted in an orderly manner',
				'evacuation_speed.required' => 'Please choose Speed of Evacuation',
				'procedure_effective.required' => 'Please choose Effectiveness of Procedures',
				'communication.required' => 'Please choose Communications during drills',
				'drill_rating.required' => 'Please choose Overall Drill rating',
				'mitigation.required' => 'Please choose Mitigation/ Plan for improvement',
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
