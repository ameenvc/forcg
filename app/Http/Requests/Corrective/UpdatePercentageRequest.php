<?php

namespace App\Http\Requests\Corrective;

use App\Corrective;
use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePercentageRequest extends FormRequest
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
        $data = Corrective::find($this->id);
        $responsible_persons = $data ? explode(',', $data->responsibility) : [];
        $correction_rule = ['nullable', 'string', 'max:1000'];
        $rootcase_rule = ['required', 'array'];
        $rootcase_rule2 = ['required', 'string', 'max:5000'];
        $corretive_rule = ['required', 'array'];
        $corretive_rule2 = ['required', 'string', 'max:5000'];
        if (in_array(Auth::user()->email, $responsible_persons)) {
            $correction_rule = ['required', 'string', 'max:1000'];
        }
        if($data->is_req_rc_analysis_management_review =="no"){
            $rootcase_rule = ['nullable', 'array'];
            $rootcase_rule2 = ['nullable', 'string', 'max:5000'];
            $corretive_rule = ['nullable', 'array'];
            $corretive_rule2 = ['nullable', 'string', 'max:5000'];
        }
        return [
            'correction' => $correction_rule,
            'correction_target_date' => ['required', 'date'],
            //'target_date' => ['required', 'array'],
            //'target_date.*' => ['required', 'date'],
            'rootcause' => $rootcase_rule,
            'rootcause.*' => $rootcase_rule2,
            'correctiveaction' => $corretive_rule,
            'correctiveaction.*' => $corretive_rule2,
            'correction_status' => ['required', 'integer', 'in:0,25,50,75,100'],
            'percent_status' => ['nullable', 'string'],
            'financial_loss' => ['nullable', 'string', 'max:32'],
            'potential_cost_savings' => ['nullable', 'string', 'max:32'],
        ];
    }

    public function withValidator($v)
    {
        if ($v->fails()) {
            $v_error_array = [];
            $cnt = 0;
            foreach ($v->errors()->messages() as $e) {
                $v_error_array[$cnt] = '<li style="text-align:left !important;">' . implode(',', $e) . '</li>';
                $cnt++;
            }
            $v_errors_text = implode('', $v_error_array);

            alert()->warning('<h1>Validation errors</h1><br> <ul>' . $v_errors_text . '</ul>')->html()->persistent('Close');

            $models = ModenaDisable::first();

            return redirect()->back()->withErrors($v)->withInput()->with('models', $models);
        }
    }
}
