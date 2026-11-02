<?php

use App\Admin;
use App\Calibration_instrument;
use App\ComplaintCategory;
use App\ModenaDisable;
use App\Product;
use App\User;
use Carbon\Carbon;

if (!function_exists('showDescriptions')) {

    function showDescriptions(string $description)
    {
        switch ($description) {
            case 'very_rare':
                $description = 'Very rare';
                break;
            case 'rare':
                $description = 'Rare';
                break;
            case 'low':
                $description = 'Low';
                break;
            case 'moderate':
                $description = 'Moderate';
                break;
            case 'high':
                $description = 'High';
                break;
            case 'negligible_risk':
                $description = 'Negligible risk';
                break;
            case 'very_low_risk':
                $description = 'Very low risk';
                break;
            case 'low_risk':
                $description = 'Low risk';
                break;
            case 'moderate_risk':
                $description = 'Moderate risk';
                break;
            case 'high_risk':
                $description = 'High risk';
                break;
            case 'no_legal_requirement':
                $description = 'No Legal Requirement';
                break;
            case 'legal_requirement':
                $description = 'Legal Requirement';
                break;
            case 'legal_requirement':
                $description = 'Legal Requirement';
                break;
            case 'very_high':
                $description = 'Very high';
                break;
            case 'excessive':
                $description = 'Excessive';
                break;
            case 'effectively_controlled':
                $description = 'Effectively Controlled';
                break;
            case 'ineffective_control':
                $description = 'Ineffective Control';
                break;
            case 'no_control':
                $description = 'No Control';
                break;
            default:
                $description = $description;
                break;
        }

        return $description;
    }
}

if (!function_exists('get_calibration_status_base_on_due_date')) {

    /**
     * [Module: Calibration Management] Get the calibration status base on due date
     *
     * @param $dueDate
     * @return string
     */
    function get_calibration_status_base_on_due_date($dueDate): string
    {
        $today             = date("Y-m-d");
        $nearToExpiry      = date('Y-m-d', strtotime($dueDate . ' - 30 days'));

        if ($dueDate < $today) {
            $status = Calibration_instrument::EXPIRED_STATUS;
        } elseif ($nearToExpiry <= $today) {
            $status = Calibration_instrument::NEAR_TO_EXPIRED_STATUS;
        } elseif ($dueDate > $today) {
            $status = Calibration_instrument::CALIBRATED_STATUS;
        } else {
            $status = Calibration_instrument::UNDER_CALIBRATION_STATUS;
        }

        return $status;
    }
}

if(!function_exists('isModuleEnabled')){
    function isModuleEnabled($modulename)
    {
        $model = DB::table('modenadisable')->First();
        if($model->$modulename == 1){
            return true;
        }
        return false;
    }
}
if(!function_exists('getModuleNames')){
    function getModuleNames(){
        $modules = new ModenaDisable();
        $table = $modules->getTable();
        $columns = \Schema::getColumnListing($table);
        $removeFields = ['id', 'auditschedulelogs', 'created_at', 'updated_at'];
        $columns = array_diff($columns, $removeFields);
        $records = DB::table($table)->select($columns)->first();
        $modulenames = [
            'auditmanagement' => 'Audit Management',
            'calibration' => 'Calibration Management',
            'complaintmanagement' => 'Customer Complaint',
            'compliance' => 'Compliance Management',
            'contextmanagement' => 'Context Management',
            'correctiveaction' => 'NCR & Corrective Actions',
            'customer_satisfaction' => 'Customer Satisfaction',
            'documentmanagement' => 'Document Management',
            'elearning' => 'E-Learning',
            'emergencyresponse' => 'Emergency Response',
            'enviaspects' => 'Environmental Aspect',
            'hse' => 'HSE Observations',
            'hr' => 'Human Resource',
            'incidentreport' => 'Incident Report',
            'managementreview' => 'Management Review',
            'maintainance' => 'Maintenance Management',
            'nearmiss' => 'Near Miss',
            'objectives' => 'Objectives',
            'occhealth' => 'Occupational Health',
            'ohsrisk' => 'Health and Safety Risk',
            'opportunity' => 'Opportunity',
            'riskmanagement' => 'Enterprise Risk',
            'vendormanagement' => 'Vendor Management',
            'wastemanagement' => 'Waste Management',
        ];
        foreach ($records as $key => $value) {
            if ($value == 0) {
                unset($modulenames[$key]);
            }
        }
        asort($modulenames);
        $modulenames_keys = array_keys($modulenames);
        return $modulenames_keys;
    }
}
if(!function_exists('getNonDeletedUsersEmail')){
    function getNonDeletedUsersEmail($email)
    {
        $flag = false;
        try{
            $query = DB::table('users')->where('approved', true)->where('deleted_at', null);
            if (is_array($email)) {
                $query->whereIn(is_numeric($email[0]) ? 'id' : 'email', $email);
                $flag = true;
            } elseif (is_numeric($email)) {
                $query->where('id', $email);
                $flag = true;
            } elseif (is_string($email)) {
                $query->where('email', $email);
                $flag = true;
            }
            $emails = $flag ? $query->pluck('email')->toArray() :[];
        }catch (\Exception $e) {
            $emails = [];
        }
        return $emails;
    }
}
/**
 * Get custom fields view
 *
 * @param $customFields
 * @return string
 */
if(!function_exists('getCustomFieldsView')){
    function getCustomFieldsView($customFields,$fieldIndex,$data=null,$structure=null){
        return view('admin.custom_field.custom_fields', compact('customFields','data','structure','fieldIndex'))->render();
    }
}
if(!function_exists('versionedCache')){
    function versionedCache($path) {
        return asset($path) . '?v=' . filemtime(public_path($path));
    }
}

if (!function_exists('getProductNames')) {
    function getProductNames(array $ids)
    {
        return Product::whereIn('id', $ids)
            ->pluck('product')
            ->implode(', ');
    }
}

if (!function_exists('getProductTypeStyle')) {
    function getProductTypeStyle($type, $eType){
        switch ($type) {
            case 'Low':
                $className = "status status-success";
                $fontColor = "#176044";
                $backgroundColor = "#e0edcf";
                break;
            case 'Moderate':
                $className = "status status-warning";
                $fontColor = "#af6f1e";
                $backgroundColor = "#fbdece";
                break;
            case 'High':
                $className = "status status-red";
                $fontColor = "#af1e1e";
                $backgroundColor = "#f8d4d4";
                break;
            default:
                $className = "";
                $fontColor = "";
                $backgroundColor = "";
                break;
        }

        if ($eType == 'pdf') {
            return $className;
        } elseif ($eType == 'word') {
            return [$fontColor, $backgroundColor];
        } else {
            return $fontColor;
        }
    }
}

if (!function_exists('getDueDateStyle')) {
    function getDueDateStyle($dueDate, $eType){
        // Convert string to Carbon instance if needed
        if (!$dueDate instanceof \Carbon\Carbon) {
            $dueDate = \Carbon\Carbon::parse($dueDate);
        }
        if ($dueDate <= date('Y-m-d')) {
            $className = "status status-red";
            $fontColor = "#af1e1e";
            $backgroundColor = "#f8d4d4";
        } else {
            $className = "status status-success";
            $fontColor = "#176044";
            $backgroundColor = "#e0edcf";
        }
        if ($eType == 'pdf' ) {
            return $className;
        } elseif ($eType == 'word') {
            return [$fontColor, $backgroundColor];
        } else {
            return $fontColor;
        }
    }
}

if (!function_exists('getWarrantyCoverageStyle')) {
    function getWarrantyCoverageStyle($warrantyCoverage, $eType){
        if ($warrantyCoverage == 'Under Warranty') {
            $className = "status status-success";
            $fontColor = "#176044";
            $backgroundColor = "#e0edcf";
        } elseif ($warrantyCoverage == 'Out of Warranty') {
            $className = "status status-red";
            $fontColor = "#af1e1e";
            $backgroundColor = "#f8d4d4";
        } else{
            $className = "";
            $fontColor = "";
            $backgroundColor = "";
        }
        if ($eType == 'pdf') {
            return $className;
        } elseif ($eType == 'word') {
            return [$fontColor, $backgroundColor];
        } else {
            return $fontColor;
        }
    }
}

if (!function_exists('getProcessStageValue')) {
    function getProcessStageValue($status){
        $value = "";
        switch ($status) {
            case 'Under Review':
                $value = $status;
                break;
            case 'Actions Proposed':
                $value = $status;
                break;
            case 'Action Reviewed, OK':
                $value = 'Action Approved';
                break;
            case 'Action Reviewed, Not OK':
                $value = 'Action Rejected';
                break;
            case 'Need More Action':
                $value = $status;
                break;
            case 'Actions Completed':
                $value = $status;
                break;
            case 'Approved':
                $value = $status;
                break;
            case 'Rejected':
                $value = $status;
                break;
            case 'Correction Proposed- RC Awaiting':
                $value = $status;
                break;
            case 'Actions Proposed- Review Awaiting':
                $value = $status;
                break;
            case 'RC Updated- Correction Awaiting':
                $value = $status;
                break;
            case 'closed':
                $value = $status;
                break;
            default:
                $value = $status;
                break;
        }
        return $value;
    }
}
if (!function_exists('getProcessStageStyle')) {
    function getProcessStageStyle($status, $eType){
        switch ($status) {
            case 'Under Review':
                $className = "status status-gray";
                $fontColor = "#737373";
                $backgroundColor = "#e6e6e6";
                $color = $fontColor;
                break;
            case 'Actions Proposed':
                $className = "bg-flat";
                $fontColor = "#495057";
                $backgroundColor = "#f1f3f5;";
                $color = $fontColor;
                break;
            case 'Action Reviewed, OK':
            case 'Action Approved':
            case 'Approved':
                $className = "status status-success";
                $fontColor = "#176044";
                $backgroundColor = "#e0edcf";
                $color = $fontColor;
                break;
            case 'Action Reviewed, Not OK':
            case 'Rejected':
                $className = "status status-red";
                $fontColor = "#af1e1e";
                $backgroundColor = "#f8d4d4";
                $color = $fontColor;
                break;
            case 'Action Rejected':
                $className = "status status-red";
                $fontColor = "#af1e1e";
                $backgroundColor = "#f8d4d4";
                $color = $fontColor;
                break;
            case 'Need More Action':
            case 'Review Pending':
                $className = "status status-warning";
                $fontColor = "#af6f1e";
                $backgroundColor = "#fbdece";
                $color = $fontColor;
                break;
            case 'Actions Completed':
            case 'Reviewed':
                $className = "status status-blue";
                $fontColor = "#25499c";
                $backgroundColor = "#d9e2f6";
                $color = $fontColor;
                break;
            case 'Approved':
                $className = "bg-amethyst-light";
                $fontColor = "#6f42c1";
                $backgroundColor = "#f3e8ff";
                $color = $fontColor;
                break;
            case 'Rejected':
                $className = "status status-red";
                $fontColor = "#af1e1e";
                $backgroundColor = "#f8d4d4";
                $color = $fontColor;
                break;
            case 'Correction Proposed- RC Awaiting':
                $className = "status status-blue";
                $fontColor = "#25499c";
                $backgroundColor = "#d9e2f6";
                $color = $fontColor;
                break;
            case 'Actions Proposed- Review Awaiting':
                $className = "status status-blue";
                $fontColor = "#25499c";
                $backgroundColor = "#d9e2f6";
                $color = $fontColor;
                break;
            case 'RC Updated- Correction Awaiting':
                $className = "status status-blue";
                $fontColor = "#25499c";
                $backgroundColor = "#d9e2f6";
                $color = $fontColor;
                break;
            case 'Closed':
                $className = "status status-success";
                $fontColor = "#176044";
                $backgroundColor = "#e0edcf";
                $color = $fontColor;
                break;
            default:
                $className = "";
                $fontColor = "";
                $backgroundColor = "";
                $color = "";
                break;
        }

        if ($eType == 'pdf') {
            return $className;
        } elseif ($eType == 'word') {
            return [$fontColor, $backgroundColor];
        } else {
            return $color;
        }
    }
}

if (!function_exists('getComplaintCategoriesFromStringIds')) {
    function getComplaintCategoriesFromStringIds($ids)
    {
        if ($ids) {
            $ids = array_map('trim', explode(',', $ids));
            $complaintCategories = ComplaintCategory::whereIn('id', $ids)->pluck('category_name');
            return $data = implode(', ', $complaintCategories->toArray());
        } else {
            return '';
        }
     }
}

if (!function_exists('getUsersFromUserIds')) {
    function getUsersFromUserIds($userIds)
    {
        if ($userIds) {
            $userIds = array_map('trim', explode(',', $userIds));
            return $users = User::whereIn('id', $userIds)->get();
        } else {
            return null;
        }
    }
}

if (!function_exists('getUsersFromEmailsOrNames')) {
    function getUsersFromEmailsOrNames($emails)
    {
        if ($emails) {
            $emails = array_map('trim', explode(',', $emails));
            $users = User::whereIn('email', $emails)->orWhereIn('name', $emails)->get();
            return $users;
        } else {
            return null;
        }
    }
}

if (!function_exists('getAdminsFromEmployeeIds')) {
    function getAdminsFromEmployeeIds($employeeIds) {
        if ($employeeIds) {
            $employeeIds = array_map('trim', explode(',', $employeeIds));
            $admins = Admin::whereIn('employeeid', $employeeIds)->get();
            return $admins;
        } else {
            return null;
        }
    }
}

if (!function_exists('getUsersFromEmployeeIds')) {
    function getUsersFromEmployeeIds($employeeIds) {
        if ($employeeIds) {
            $employeeIds = array_map('trim', explode(',', $employeeIds));
            $users = User::whereIn('employeeid', $employeeIds)->get();
            return $users;
        } else {
            return null;
        }
    }
}

if (!function_exists('getFlashNewTag')) {
    function getFlashNewTag($feature) {
        $service = [
            'mfa' => ['validity' => Carbon::create(2026, 9, 25)],
            'user_2fa' => ['validity' => Carbon::create(2026, 9, 25)],
            'system_backup' => ['validity' => Carbon::create(2026, 10, 1)]
        ];

        if (Carbon::today()->greaterThan($service[$feature]['validity'])) {
            echo '';
        } else {
            return '<span class="new-feature-badge">New</span>';
        }

    }
}
