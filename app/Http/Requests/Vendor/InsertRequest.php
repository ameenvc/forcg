<?php

namespace App\Http\Requests\Vendor;

use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;

class InsertRequest extends FormRequest
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
            "vendorname" => ['required', 'string', 'max:255'],
            "vendorgroup" => ['required', 'string', 'in:Existing,New'],
            "vendorappr" => ['nullable', 'date'],
            "vendorscope" => ['required', 'string', 'max:255'],
            "vendoradd" => ['required', 'string', 'max:1000'],
            "vendorloc" => ['required', 'string', 'max:255'],
            "vendorcontact" => ['required', 'string', 'max:255'],
            "vendoremail" => ['required', 'email', 'max:320'],
            "has_certificate" => ['required', 'in:yes,no'],
            "vendor_iso" => ['required_if:has_certificate,yes', 'array'],
            "vendor_iso.*" => ['nullable', 'exists:standards,id'],
            // "vendor_yoe" => ['nullable', 'digits:4', 'integer', 'min:' . config('constants.vendor.start_year'), 'max:' . (date("Y") + 1)],
            "vendor_yoe" => ['nullable'],
            "vendortype" => ['required', 'in:critical,semi_critical,non_critical'],
            "vendor_eval" => ['required', 'in:quarterly,half_year,one_year,one_half_year,two_year,three_year,four_year,five_year'],
            "vendor_trial" => ['required_if:vendorgroup,New', 'in:yes,no'],
            "vendoroutcome" => ['nullable', 'in:good,satisfactory,poor'],
            "vendorbasis_appr" => ['nullable', 'array'],
            "vendorbasis_appr.*" => ['nullable', 'in:based_on_past_experience,trial_order,suggested_by_customer,sole_supplier,cost_effective,reputed_company'],
            "vendorbasis_add" => ['nullable', 'array'],
            "vendorbasis_add.*" => ['nullable', 'in:suggested_by_customer,single_source,economical_cost_effective,reputed_company_brand_agency,suggested_by_management'],
            "vendorcomments" => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'vendorappr.required_if' => 'The approved date field is required.',
            'vendor_iso.required_if' => 'The vendor ISO field is required.',
        ];
    }

    public function attributes()
    {
        return [
            'vendorname' => 'vendor name',
            'vendorgroup' => 'purpose of addition',
            'vendorscope' => 'vendor scope',
            'vendoradd' => 'country',
            'vendorloc' => 'location',
            'vendoremail' => 'email',
            'vendorcontact' => 'contact',
            'has_certificate' => 'vendor certified',
            'vendor_yoe' => 'year of establishment',
            'vendortype' => 'vendor type',
            'vendorbasis_appr' => 'basis of approval',
            'vendor_trial' => 'any trial order made',
            'vendoroutcome' => 'what was the outcome of the trial order',
            'vendorbasis_add' => 'addition',
            'vendorcomments' => 'comments',
        ];
    }
}
