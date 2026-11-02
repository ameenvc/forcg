<?php

namespace App\Http\Requests\Corrective;

use App\Categories;
use App\Corrective;
use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOpenRequest extends FormRequest
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
        // $category = Categories::where('categoryname', $this->category)->value('id');
        // $enabled_source_categories = Categories::where('enable_source', 1)->pluck('id')->toArray();
        // $source_rules = in_array($category, $enabled_source_categories) ? ['required', 'max:255'] : ['nullable']
        $data = Corrective::find($this->posted);
        $rootcause = $this->rootcause;
        $correctiveaction = $this->correctiveaction;
        $rootcase_rule = ['nullable', 'array'];
        $rootcase_rule2 = ['nullable', 'string', 'max:5000'];
        $corretive_rule = ['nullable', 'array'];
        $corretive_rule2 = ['nullable', 'string', 'max:5000'];
        $dept_rule = ['required', 'array'];
        $dept_rule2 = ['required', 'integer', Rule::exists('departments', 'id')];
        $target_date_rule1 = ['required'];
        $target_date_rule2 = ['required','date'];
        $resp_rule = ['required', 'array'];
        $resp_rule2 = ['required', 'email', Rule::exists('users', 'email')->where(function ($query) {
            return $query->where('is_email_verified', 1)->where('approved', 1);
        })];
        if($rootcause || $correctiveaction){
            if($rootcause[0]==null && $correctiveaction[0]==null){
                $dept_rule = ['nullable', 'array'];
                $dept_rule2 = ['nullable', 'integer'];
                $resp_rule = ['nullable', 'array'];
                $resp_rule2 = ['nullable'];
                $target_date_rule1 = ['nullable'];
                $target_date_rule2 = ['nullable','date'];
            }
        }
        if($data->is_req_rc_analysis_management_review=="no"){
            $dept_rule = ['nullable', 'array'];
            $dept_rule2 = ['nullable', 'integer'];
            $resp_rule = ['nullable', 'array'];
            $resp_rule2 = ['nullable'];
        }
        return [
            'rootcause' => $rootcase_rule,
            'rootcause.*' => $rootcase_rule2,
            'correctiveaction' => $corretive_rule,
            'correctiveaction.*' => $corretive_rule2,
            'department' => $dept_rule,
            'department.*' => $dept_rule2,
            'correction' => ['required', 'string', 'max:1000'],
            'correction_target_date' => ['required', 'date'],
            'source' => ['nullable'],
            'responsibility' => $resp_rule,
            'responsibility.*' => $resp_rule2,
            'target_date' => $target_date_rule1,
            'target_date.*' => $target_date_rule2,
            'financial_loss' => ['nullable', 'string', 'max:32'],
            'potential_cost_savings' => ['nullable', 'string', 'max:32'],
            //'confirm_propose' => ['required', 'in:yes,no'],
            //'type' => ['required', 'string', Rule::exists('types', 'name')],
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
