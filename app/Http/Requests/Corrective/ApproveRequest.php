<?php

namespace App\Http\Requests\Corrective;

use App\Categories;
use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class ApproveRequest extends FormRequest
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
        //$category = Categories::where('categoryname', $this->category)->value('id');
        //$enabled_source_categories = Categories::where('enable_source', 1)->pluck('id')->toArray();
        //$source_rules = in_array($category, $enabled_source_categories) ? ['required', 'max:255'] : ['nullable'];

        return [
            'type' => ['required', 'string', 'exists:types,order'],
            'source' => ['nullable'],
            'corrective_id' => ['required', 'exists:corrective,id'],
            'title' => ['required', 'string', 'max:255'],
            'responsibility' => ['nullable', 'array', 'exists:users,email'],
            'responsibility.*' => ['nullable', 'email', 'exists:users,email'],
            'observation' => ['required', 'string', 'max:1000'],
            'agreedaction' => ['nullable', 'string', 'max:2000'],
            'notification' => ['nullable', 'array'],
            'notification.*' => ['nullable', 'string', 'exists:users,email'],
            'frequency' => ['nullable', 'array'],
            'frequency.*' => ['nullable', 'string', 'in:Monthly,Weekly,2 Days Before'],
            'lead' => ['required_if:seperate,yes'],
            'investigation_date' => ['required_if:seperate,yes'],
            'correction_target_date' => ['required', 'date'],
            'category' => ['required'],
            'department' => ['required'],
            'financial_loss' => ['nullable', 'string', 'max:32'],
            'potential_cost_savings' => ['nullable', 'string', 'max:32'],
            'removedIssueFilenames' => ['nullable'],
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

            alert()->warning('<h1>Validation errors</h1><br><ul>' . $v_errors_text . '</ul>')->html()->persistent('Close');

            $models = ModenaDisable::first();

            return redirect()->back()->withErrors($v)->withInput()->with('models', $models);
        }
    }
}
