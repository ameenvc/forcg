<?php

namespace App\Http\Requests\Vendor;

use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
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
            "vendor_id" => ['required', 'integer', 'exists:vendor,id'],
            "vendorname" => ['required', 'string', 'max:255'],
            "vendorappr" => ['nullable', 'date'],
            "vendorscope" => ['required', 'string', 'max:255'],
            "vendoradd" => ['required', 'string', 'max:1000'],
            "vendorloc" => ['required', 'string', 'max:255'],
            "vendoremail" => ['required', 'email', 'max:320'],
            "vendorcontact" => ['required', 'string', 'max:255'],
            "has_certificate" => ['required', 'in:yes,no'],
            "vendor_iso" => ['required_if:has_certificate,yes', 'array'],
            "vendor_iso.*" => ['required', 'exists:standards,id'],
            // "vendor_yoe" => ['required', 'digits:4', 'integer', 'min:' . config('constants.vendor.start_year'), 'max:' . (date("Y") + 1)],
            "vendor_yoe" => ['nullable'],
            "vendortype" => ['required', 'in:critical,semi_critical,non_critical'],
            "vendor_eval" => ['required', 'in:quarterly,half_year,one_year,one_half_year,two_year,three_year,four_year,five_year'],
            "vendor_trial" => ['nullable', 'in:yes,no'],
            "vendoroutcome" => ['nullable', 'in:good,satisfactory,poor'],
            "vendorbasis_appr" => ['nullable', 'array'],
            "vendorbasis_appr.*" => ['nullable', 'in:based_on_past_experience,trial_order,suggested_by_customer,sole_supplier,cost_effective,reputed_company'],
            "vendorbasis_add" => ['nullable', 'array'],
            "vendorbasis_add.*" => ['nullable', 'in:suggested_by_customer,single_source,economical_cost_effective,reputed_company_brand_agency,suggested_by_management'],
            "vendorcomments" => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->fails()) {
            $models = ModenaDisable::first();
            return redirect()->back()->withErrors($validator->errors())->withInput()->with('model', $models);
        }

    }
}
