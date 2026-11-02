<?php

namespace App\Http\Requests\Compliance;

use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddReviewComplianceStatusRequest extends FormRequest
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
        $legalDocument = $this->input('issue');
        $authority = $this->input('authority');
        $fields = [
            'id' => ['required', 'integer', 'exists:compliance,Cid'],
            'auth_id' => [],
            'status' => ['required', 'string', 'in:complied,not_complied,partially_complied'],
            'frequency' => ['required', 'string', 'in:Yearly,Half yearly,Quarterly'],
            'version' => ['required', 'string', 'max:255'],
            'regulation' => ['required', 'string', 'max:255'],
            'owner' => ['required', 'integer', 'exists:departments,id'],
            'aspect' => ['nullable', 'string', 'max:2000'],
            'requirement' => ['required', 'string', 'max:2000'],
            'responsibility' => ['required','email','exists:users,email'],
        ];
        if ($authority == '0') {
            $fields['other_reg'] = [
                'required',
                Rule::unique('regulatory_authorities', 'name')
                    ->whereNull('deleted_at')
                    ->whereNotIn('status', ['Rejected','Pending Review']),
            ];
            $fields['other_doc'] = 'required';
        } elseif ($legalDocument == 'other') {
            $fields['authority'] = 'required|exists:regulatory_authorities,id';
            $fields['other_doc'] = [
                'required',
                Rule::unique('compliance_legal_documents', 'name')
                    ->whereNull('deleted_at')
                    ->where('RAC_id', $authority)
                    ->whereNotIn('status', ['Rejected','Pending Review']),
            ];
        }else{
            $fields['issue'] = 'required|exists:compliance_legal_documents,id';
            $fields['authority'] = 'required|exists:regulatory_authorities,id';
            $fields['other_reg'] = 'nullable';
            $fields['other_doc'] = 'nullable';
        }
        return $fields;
    }
    public function messages()
    {
        return [
            'responsibility.required' => 'Responsible Person field is required.',
            'owner.required' => 'Department field is required.',
            'frequency.required' => 'Review frequency field is required.',
            'other_reg.required' => 'Please enter a Regulatory Authority name.',
            'other_reg.unique' => 'This Regulatory Authority already exists.',
            'other_doc.required' => 'Please enter a Legal Document name.',
            'other_doc.unique' => 'This Legal Document already exists for the selected Authority.',
            'authority.required' => 'Please select a Regulatory Authority.',
            'authority.exists' => 'The selected Regulatory Authority is invalid.',
            'issue.required' => 'Please select a Legal Document.',
            'issue.exists' => 'The selected Legal Document is invalid.'
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->fails()) {
            $models = ModenaDisable::first();

            return redirect()->back()->withErrors($validator->errors())->withInput()->with('models', $models);
        }
    }
}
