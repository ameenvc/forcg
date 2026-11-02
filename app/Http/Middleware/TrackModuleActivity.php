<?php

namespace App\Http\Middleware;

use Closure;
use App\ModuleActivity;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
class TrackModuleActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user_id = User::where('email',Auth::user()->email)->value('id');
            $controller = class_basename($request->route()->getController());
            $moduleName = null;
            switch($controller){
                case 'RiskController' : $moduleName = 'riskmanagement';
                break;
                case 'RacmController' : $moduleName = 'riskmanagement';
                break;
                case 'MitigationController' : $moduleName = 'riskmanagement';
                break;
                case 'RiskCommentController' : $moduleName = 'riskmanagement';
                break;
                case 'CalibrationController' : $moduleName = 'calibration';
                break;
                case 'EnviaspectsController' : $moduleName = 'enviaspects';
                break;
                case 'ObjectivesController' : $moduleName = 'objectives';
                break;
                case 'OpportunityController' : $moduleName = 'opportunity';
                break;
                case 'CorrectiveController' : $moduleName = 'correctiveaction';
                break;
                case 'ComplianceController' : $moduleName = 'compliance';
                break;
                case 'OhsRiskController' : $moduleName = 'ohsrisk';
                break;
                case 'OhsRiskownerController' : $moduleName = 'ohsrisk';
                break;
                case 'DocumentController' : $moduleName = 'documentmanagement';
                break;
                case 'GovernanceHubCommentController' : $moduleName = 'documentmanagement';
                break;
                case 'GovernanceHubDraftCommentController' : $moduleName = 'documentmanagement';
                break;
                case 'InternalauditController' : $moduleName = 'auditmanagement';
                break;
                case 'ActionController' : $moduleName = 'auditmanagement';
                break;
                case 'ManagementReviewController' : $moduleName = 'managementreview';
                break;
                case 'EmergencyResponseController' : $moduleName = 'emergencyresponse';
                break;
                case 'IncidentReportController' : $moduleName = 'incidentreport';
                break;
                case 'ContextController' : $moduleName = 'contextmanagement';
                break;
                case 'WasteController' : $moduleName = 'wastemanagement';
                break;
                case 'VendorController' : $moduleName = 'vendormanagement';
                break;
                case 'HrController' : $moduleName = 'hr';
                break;
                case 'ComplaintController' : $moduleName = 'complaintmanagement';
                break;
                case 'MaintenanceController' : $moduleName = 'maintainance';
                break;
                case 'HseObservationController' : $moduleName = 'hse';
                break;
                case 'OccupationalHealth' : $moduleName = 'occhealth';
                break;
                case 'NearmissController' : $moduleName = 'nearmiss';
                break;
                case 'CustomerSatisfactionController' : $moduleName = 'customer_satisfaction';
                break;
                case 'ElearningController' : $moduleName = 'elearning';
                break;
                case 'CommentController' : $moduleName = 'elearning';
                break;
            }

            if($moduleName){
                ModuleActivity::updateOrCreate(
                    ['user_id' => $user_id, 'module_name' => $moduleName],
                    ['last_accessed_at' => now()]
                );
            }
        }
        return $next($request);
    }
    
}
