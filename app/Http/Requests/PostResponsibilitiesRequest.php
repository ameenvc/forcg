<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostResponsibilitiesRequest extends FormRequest
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
        $default_arr = [
            "representative" => "nullable",
            "near" => "nullable",
            "calibration_reviewer" => "nullable",
            "doccontroller" => "nullable",
            "legalCoordinator" => "nullable",
            "escalateriskreceiver" => "nullable",
            "incident_reviewer" => "nullable",
            "incident_approver" => "nullable",
            "IssueReviewer" => "nullable",
            "internalaudit" => "nullable",
            "riskreviewer" => "nullable",
            "closurereviewer" => "nullable",
            "management" => "nullable",
            "archiveprivilege" => "nullable",
            "hses" => "nullable",
            "complaintreviewer" => "nullable",
            "complaintregister" => "nullable",
            "contextreviewer" => "nullable",
            "contextapprover" => "nullable",
            "waste_reviewer" => "nullable",
            "waste_approver" => "nullable",
            "waste_disposal_sales_authorities" => "nullable",
            "envireviewer" => "nullable",
            "trainingcoordinator" => "nullable",
            "mainreviewer" => "nullable",
            "mainelectrical" => "nullable",
            "maininstrument" => "nullable",
            "company_doctor" => "nullable",
            "supplierapprover" => "nullable",
            "vendor_reviewer" => "nullable",
            "vendor_approver" => "nullable",
            "elearning_add_content" => "nullable",
            "envi_secondary_reviewer" => "nullable",
            "hse_observation_reviewer" => "nullable",
            "management_reviewer" => "nullable",
            "ohs_secondary_review" => "nullable",
            "objective_reviewer" => "nullable",
            "objective_approver" => "nullable",
        ];

        if (!$this->new_responsibility) {
            return array_merge($default_arr, [
                "incident_leader_select" => "nullable|not_in:0",
                "nearmiss" => "nullable|not_in:0",
                "masterdoc" => "nullable|not_in:0",
                "dcr" => "nullable|not_in:0",
                "doc_reviewer" => "nullable|not_in:0",
                "doc_approver" => "nullable|not_in:0",
                "risks" => "nullable|not_in:0",
                "issues" => "nullable|not_in:0",
                "corrective_responsibility" => "nullable|not_in:0",
                "corrective_correction_responsibility" => "nullable|not_in:0",
                "corrective_lead" => "nullable|not_in:0",
                "compliance_responsibility" => "nullable|not_in:0",
                "opportunity" => "nullable|not_in:0",
                "mitigations" => "nullable|not_in:0",
                "hseissue" => "nullable|not_in:0",
                "division" => "nullable|not_in:0",
                "subdivision" => "nullable|not_in:0",
                "objective_resp" => "nullable|not_in:0",
                "auditee" => "nullable|not_in:0",
                "complaint_responsibilities" => "nullable|not_in:0",
            ]);

        } else {
            return array_merge($default_arr, [
                "incident_leader_select" => "nullable",
                "nearmiss" => "nullable",
                "masterdoc" => "nullable",
                "dcr" => "nullable",
                "doc_reviewer" => "nullable",
                "doc_approver" => "nullable",
                "risks" => "nullable",
                "issues" => "nullable",
                "corrective_responsibility" => "nullable",
                "corrective_correction_responsibility" => "nullable",
                "corrective_lead" => "nullable",
                "compliance_responsibility" => "nullable",
                "opportunity" => "nullable",
                "mitigations" => "nullable",
                "hseissue" => "nullable",
                "division" => "nullable",
                "subdivision" => "nullable",
                "objective_resp" => "nullable",
                "auditee" => "nullable",
                "complaint_responsibilities" => "nullable",
            ]);
        }

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
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
    }
}
