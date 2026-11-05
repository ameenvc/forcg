<?php

namespace App\Http\Controllers;

use App\Models\Opportunityapprover;
use App\Models\AppConfiguration;
use App\Admin;
use App\Archiveprivilege;
use App\Area;
use App\Audit;
use App\AuditChecklistConformance;
use App\Auditor;
use App\AuditRootCauseCorrectiveActionDetail;
use App\Calibrationreviewer;
use App\Categories;
use App\Changecategory;
use App\Changerequestapprove;
use App\Changerequestreviewer;
use App\ClientDetail;
use App\Closurereviewer;
use App\CompanyDoctor;
use App\Complaintregister;
use App\Complaintreviewer;
use App\Compliance;
use App\Config;
use App\ContextCrsSeverity;
use App\Corrective;
use App\CorrectiveRootCauseCorrectiveActionDetail;
use App\Country;
use App\CustomerDetail;
use App\Dcr;
use App\DepartmentDetail;
use App\Departments;
use App\Division;
use App\DivisionArea;
use App\Doccontroller;
use App\Doctype;
use App\EmailNotification;
// use App\EmployeeDetails;
use App\EmailNotificationsUserDetails;
use App\EmployeeDetails;
use App\EmployeeHealthIllnessNature;
use App\EmployeeHealthInjuryNature;
use App\EnviaspectConsequence;
use App\EnviaspectControlMeasure;
use App\EnviaspectProbability;
use App\EnviaspectQuantity;
use App\EnviaspectRegulatoryRequirement;
use App\Envireviewer;
use App\Escalateriskreceiver;
use App\GovernanceHubDraft;
use App\HealthCategory;
use App\HealthTreatment;
use App\Hse;
use App\HseIssue;
use App\ComplaintPlant;
use App\ComplaintCategory;
use App\CorrectiveDetectionStage;
use App\Http\Requests\Admin\Calibration\AddFrequencyRequest;
use App\ModuleReportDetail;
use App\Http\Controllers\BaseController;
use App\Traits\GenerateTypeCodeTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Http\Requests\AddAreasToDivisionRequest;
use App\Http\Requests\AddDepartmentDivisionRequest;
use App\Http\Requests\AddWasteDisposalMethodRequest;
use App\Http\Requests\AddWasteTargetRequest;
use App\Http\Requests\AddWasteTypeRequest;
use App\Http\Requests\AddWasteUnitRequest;
use App\Http\Requests\Admin\AddCategoryRequest;
use App\Http\Requests\Admin\Corrective\EnableSourceRequest;
use App\Http\Requests\Admin\CustomerSatisfaction\StoreCustomerDetailRequest;
use App\Http\Requests\Admin\CustomerSatisfaction\UpdateCustomerDetailRequest;
use App\Http\Requests\Admin\Department\AddDepartmentRequest;
use App\Http\Requests\Admin\Department\AddLocationRequest;
use App\Http\Requests\Admin\Department\AddSubdivisionRequest;
use App\Http\Requests\Admin\Department\EditDivisionRequest;
use App\Http\Requests\Admin\Department\EditLocationRequest;
use App\Http\Requests\Admin\Department\EditSubdivisionRequest;
use App\Http\Requests\Admin\Department\ExcelUploadRequest as DepartmentExcelUploadRequest;
use App\Http\Requests\Admin\Department\UpdateDepartmentRequest;
use App\Http\Requests\Admin\Enviaspect\AddCategoryRequest as EnviaspectAddCategoryRequest;
use App\Http\Requests\Admin\Enviaspect\EditEnvRequest;
use App\Http\Requests\Admin\Type\AddTypeRequest;
use App\Http\Requests\Admin\Vendor\ExcelUploadRequest;
use App\Http\Requests\EditAreaRequest;
use App\Http\Requests\EditDepartmentDivisionRequest;
use App\Http\Requests\PostResponsibilitiesRequest;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Requests\UpdateWasteDisposalMethodRequest;
use App\Http\Requests\UpdateWasteTargetRequest;
use App\Http\Requests\UpdateWasteTypeRequest;
use App\Http\Requests\UpdateWasteUnitRequest;
use App\IllnessNonWorkrelatedcases;
use App\Imports\CalibrationImport;
use App\Imports\DocumentMasterImport;
use App\Imports\EmployeeImport;
use App\Imports\ProductServiceImport;
use App\Imports\VendorsImport;
use App\Imports\CategoryImport;
use App\IncidentApprover;
use App\IncidentReport;
use App\IncidentReviewer;
use App\InjuryWorkrelatedcases;
use App\Internalaudit;
use App\Investigator;
use App\Issue;
use App\Issuereviewer;
use App\Legalcoordinator;
use App\Location;
use App\Mail\Approval;
use App\Mail\ApprovalToUser;
use App\Mainelectrical;
use App\Maininstrument;
use App\Mainreviewer;
use App\ManagementAgendaPoint;
use App\ManagementAgendaPointList;
use App\ManagementAgendaType;
use App\ManagementLocation;
use App\ManagementReviewSchedule;
use App\ManagementReviewScheduleNote;
use App\Managements;
use App\Masterdoc;
use App\Mitigation;
use App\ModenaDisable;
use App\Near;
use App\Nearmiss;
use App\Nearmisstypes;
use App\Neartarget;
use App\Notifier;
use App\Objective;
use App\ObjectiveType;
use App\Ohs;
use App\Opportunity;
use App\OpportunityType;
use App\Party;
use App\Priority;
use App\Product;
use App\Purpose;
use App\Representative;
use App\Risk;
use App\Riskreviewer;
use App\Scope;
use App\Standards;
use App\Supplierapprovers;
use App\Trainingcoordinators;
use App\Type;
use App\User;
use App\UserGate;
use App\Vendor;
use App\Vendordetails;
use App\Vendorresult;
use App\VendorStandard;
use App\ViewActivity;
use App\Wasteapprover;
use App\Wastedisposalmethod;
use App\Wastedisposalsalesauthority;
use App\Wastereviewer;
use App\Wastetarget;
use App\Wastetype;
use App\Wasteunit;
use App\ComplaintUnit;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\ToArray;
use App\RacmReviewerDetail;
use App\RiskRacm;
use App\RegulatoryAuthority;
use App\ComplianceLegalDocument;
use App\DetectionStageLabelModel;
use App\Http\Requests\EnableDetectionStageRequest;
use App\Mail\LegalDocumentApproved;
use App\Mail\LegalDocumentRejected;
use App\Mail\RegulatoryAuthorityApproved;
use App\Mail\RegulatoryAuthorityRejected;
use App\ProductNcrConfigurationAddedField;
use App\ProductNcrConfigurationField;
use App\ProductNcrFieldHistory;
use App\ProcessNcrConfigurationAddedField;
use App\ProcessNcrConfigurationField;
use App\ProcessNcrFieldHistory;
use App\VendorNcrConfigurationAddedField;
use App\VendorNcrConfigurationField;
use App\VendorNcrFieldHistory;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\PngWriter;
// Update with the actual namespace of your models
class AdminController extends Controller
{
    use GenerateTypeCodeTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware(['auth:admin', 'mfa'])->except([
            'getDivisionsOfADepartment',
            'getAreasOfADivision',
            'ajaxDepartmentDivisions',
            'ajaxDepartmentAreas',
            'ajaxLocationDepartments',
            'ajaxDept',
            'ajaxWasteTarget',
            'ajaxAuditor',
            'ajaxLocationDivisions',
            'ajaxLocationAreas',
            'addDepartmentDivisions',
        ]);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $models = DB::table('modenadisable')->First();
        //counting total approved users
        $totalUsersCount = DB::table('users')
            ->where('approved',true)
            ->whereNull('deleted_at')
            ->count();
        // Get the current date and time
        $currentDateTime = Carbon::now();
        //counting total online user
        $onlineUsers = DB::table('users')
            ->where('approved', '=', 1)
            ->where('last_seen', '=', $currentDateTime)
            ->whereNull('deleted_at')
            ->count();
        $onlineUsersPercentage = $totalUsersCount ? round(($onlineUsers / $totalUsersCount) * 100, 2) : 0;

        //counting never visited user
        $neverVisitedUsers = DB::table('users')
            ->where('approved', '=', 1)
            // ->where('is_email_verified', 1)
            ->where('last_seen', '=', null)
            ->whereNull('deleted_at')
            ->count();
        $neverVisitedUsersPer = $totalUsersCount ? round(($neverVisitedUsers / $totalUsersCount) * 100, 2) : 0;

        //counting regular user
        $regularUser = DB::table('users')
            ->where('approved', '=', 1)
            // ->where('is_email_verified', '=', 1)
            ->where('last_seen', '!=', null)
            ->whereNull('deleted_at')
            ->count();
        $regularUserPer = $totalUsersCount ? round(($regularUser / $totalUsersCount) * 100, 2) : 0;

        //counting pending approval
        $pendingAprroval = DB::table('users')
            ->where('approved', '=', 0)
            ->whereNull('deleted_at')
            ->count();
        //counting total department
        $totalDepartments = DB::table('departments')->count();
        //counting total emplyee
        $totalEmployee = DB::table('employee_details')->count();
        //counting number of mmodules
        $module = DB::table('modenadisable')->first();
        $enabledModules = 0;

        // Get the table columns dynamically
        $moduleProperties = DB::getSchemaBuilder()->getColumnListing('modenadisable');

        // Remove unnecessary columns like id, created_at, and updated_at
        $ignoredColumns = ['id', 'created_at', 'updated_at', 'auditschedulelogs'];
        $moduleProperties = array_diff($moduleProperties, $ignoredColumns);

        foreach ($moduleProperties as $property) {
            $propertyValue = $module->$property; // Dynamically retrieve property value
            if ($propertyValue == 1) {
                $enabledModules++;
            }
        }
        $depcode = Departments::select('code')->orderBy('departmentname', 'asc')->pluck('code')->toArray();
        $process = Departments::select('departmentname')->orderBy('departmentname', 'asc')->pluck('departmentname')->toArray();

        //counting number of emplyees per department
        $employees = EmployeeDetails::select('departments.id', DB::raw("COUNT(employee_details.emp_department) as number"))->rightJoin('departments', function ($join) {
            $join->on('departments.id', '=', 'employee_details.emp_department');
        })->groupBy('departments.id')
            ->orderBy('departments.departmentname', 'ASC')->pluck('number')->toArray();

        //counting number of users per department
        $users = User::select('departments.departmentname', DB::raw("COUNT(users.deptid) as number"))->rightJoin('departments', function ($join) {
            $join->on('departments.departmentname', '=', 'users.deptid');
        })->groupBy('departments.departmentname')
            ->orderBy('departments.departmentname', 'ASC')->pluck('number')->toArray();

        return view('/admin/adminhome')
            ->with('process', json_encode($process, JSON_NUMERIC_CHECK))->with('depcode', json_encode($depcode, JSON_NUMERIC_CHECK))
            ->with('totalDepartments', $totalDepartments)
            ->with('totalUsersCount', $totalUsersCount)
            ->with('pendingAprroval', $pendingAprroval)
            ->with('totalEmployee', $totalEmployee)
            ->with('enabledModules', $enabledModules)
            ->with('model', $models)
            ->with('onlineUsersPercentage', $onlineUsersPercentage)
            ->with('neverVisitedUsersPer', $neverVisitedUsersPer)
            ->with('regularUserPer', $regularUserPer)
            ->with('employees', json_encode($employees, JSON_NUMERIC_CHECK))
            ->with('users', json_encode($users, JSON_NUMERIC_CHECK));
    }
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function showEnableModulePage(string $ids)
    {
        $models = ModenaDisable::first();
        $ids = explode(',', $ids);
        session(['enable_page_ids' => $ids]);
        $users = User::whereIn('id', $ids)->get();
        $names = User::whereIn('id', $ids)->pluck('name')->toArray();
        $names = implode(',', $names);

        return view('admin.enable_disable_module')->with([
            'users' => $users,
            'ids' => $ids,
            'names' => $names,
            'model' => $models,
        ]);

    }
    public function insertToUserGate($module_param, $ids, $key)
    {
        $auth_id = User::where('email',Auth::user()->email)->where('approved',true)->value('id');
        if ($module_param) {
            foreach ($ids as $id) {
                $found = UserGate::where('key', $key)->where('users_id', $id)->first();
                if (!$found) {
                    UserGate::create([
                        'key' => $key,
                        'users_id' => $id,
                        'saver_users_id' => $auth_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } else {
            foreach ($ids as $id) {
                UserGate::where('users_id', $id)->where('key', $key)->delete();
            }
        }

    }
    public function submitEnableModulePage(Request $request)
    {
        $ids = $request->input('ids');
        $ids = explode(',', $ids);
        $all = $request->input('all');
        $complaint_all = $request->input('complaint_all');
        $compliance_all = $request->input('compliance_all');
        $compliance_analysis = $request->input('compliance_analysis');
        $context_all = $request->input('context_all');
        $customer_all = $request->input('customer_all');
        $customer_analysis = $request->input('customer_analysis');
        $emergency_all = $request->input('emergency_all');
        $risk_all = $request->input('risk_all');
        $risk_analysis = $request->input('risk_analysis');
        $objective_all = $request->input('objective_all');
        $envi_all = $request->input('envi_all');
        $envi_analysis = $request->input('envi_analysis');
        $opportunity_all = $request->input('opportunity_all');
        $opportunity_analysis = $request->input('opportunity_analysis');
        $hse_all = $request->input('hse_all');
        $hse_analysis = $request->input('hse_analysis');
        $corrective_all = $request->input('corrective_all');
        $corrective_analysis = $request->input('corrective_analysis');
        $hsr_all = $request->input('hsr_all');
        $doc_all = $request->input('doc_all');
        $doc_analysis = $request->input('doc_analysis');
        $audit_all = $request->input('audit_all');
        $audit_analysis = $request->input('audit_analysis');
        $management_all = $request->input('management_all');
        $management_analysis = $request->input('management_analysis');
        $incident_all = $request->input('incident_all');
        $hr_all = $request->input('hr_all');
        $vendor_all = $request->input('vendor_all');
        $vendor_analysis = $request->input('vendor_analysis');
        $waste_all = $request->input('waste_all');
        $waste_analysis = $request->input('waste_analysis');
        $elearning_all = $request->input('elearning_all');
        $calibration_all = $request->input('calibration_all');
        $calibration_analysis = $request->input('calibration_analysis');
        $maintenance_all = $request->input('maintenance_all');
        $occu_all = $request->input('occu_all');
        $occu_analysis = $request->input('occu_analysis');
        $nearmiss_all = $request->input('nearmiss_all');
        $nearmiss_analysis = $request->input('nearmiss_analysis');
        $emergency_all = $request->input('emergency_all');
        $enable_dashboard = $request->input('dashboard');

        $this->insertToUserGate($complaint_all, $ids, 'complaint-all-pages');
        $this->insertToUserGate($compliance_all, $ids, 'compliance-all-pages');
        $this->insertToUserGate($compliance_analysis, $ids, 'compliance-analysis-page');
        $this->insertToUserGate($context_all, $ids, 'context-all-pages');
        $this->insertToUserGate($customer_all, $ids, 'customer-all-pages');
        $this->insertToUserGate($customer_analysis, $ids, 'customer-analysis-page');
        $this->insertToUserGate($emergency_all, $ids, 'emergency-all-pages');
        $this->insertToUserGate($risk_all, $ids, 'risk-all-pages');
        $this->insertToUserGate($risk_analysis, $ids, 'risk-analysis-page');
        $this->insertToUserGate($request->input('risk_ai_knowledge'), $ids, 'manage-risk-ai-knowledge');
        $this->insertToUserGate($objective_all, $ids, 'objective-all-pages');
        $this->insertToUserGate($envi_all, $ids, 'envi-all-pages');
        $this->insertToUserGate($envi_analysis, $ids, 'envi-analysis-page');
        $this->insertToUserGate($opportunity_all, $ids, 'opportunity-all-pages');
        $this->insertToUserGate($opportunity_analysis, $ids, 'opportunity-analysis-page');
        $this->insertToUserGate($hse_all, $ids, 'hse-all-pages');
        $this->insertToUserGate($hse_analysis, $ids, 'hse-analysis-page');
        $this->insertToUserGate($corrective_all, $ids, 'corrective-all-pages');
        $this->insertToUserGate($corrective_analysis, $ids, 'corrective-analysis-page');
        $this->insertToUserGate($hsr_all, $ids, 'hsr-all-pages');
        $this->insertToUserGate($doc_all, $ids, 'document-all-pages');
        $this->insertToUserGate($doc_analysis, $ids, 'document-analysis-page');
        $this->insertToUserGate($audit_all, $ids, 'audit-all-pages');
        $this->insertToUserGate($audit_analysis, $ids, 'audit-analysis-page');
        $this->insertToUserGate($management_all, $ids, 'management-all-pages');
        $this->insertToUserGate($management_analysis, $ids, 'management-analysis-page');
        $this->insertToUserGate($incident_all, $ids, 'incident-all-pages');
        $this->insertToUserGate($hr_all, $ids, 'hr-all-pages');
        $this->insertToUserGate($vendor_all, $ids, 'vendor-all-pages');
        $this->insertToUserGate($vendor_analysis, $ids, 'vendor-analysis-page');
        $this->insertToUserGate($waste_all, $ids, 'waste-all-pages');
        $this->insertToUserGate($waste_analysis, $ids, 'waste-analysis-page');
        $this->insertToUserGate($elearning_all, $ids, 'elearning-all-pages');
        $this->insertToUserGate($calibration_all, $ids, 'calibration-all-pages');
        $this->insertToUserGate($calibration_analysis, $ids, 'calibration-analysis-page');
        $this->insertToUserGate($maintenance_all, $ids, 'maintenance-all-pages');
        $this->insertToUserGate($occu_all, $ids, 'occhealth-all-pages');
        $this->insertToUserGate($occu_analysis, $ids, 'occhealth-analysis-page');
        $this->insertToUserGate($nearmiss_all, $ids, 'nearmiss-all-pages');
        $this->insertToUserGate($nearmiss_analysis, $ids, 'nearmiss-analysis-page');
        $this->insertToUserGate($all, $ids, 'enable-all');
        $this->insertToUserGate($enable_dashboard, $ids, 'enable-dashboard');

        // Get user names for success message
        $models = DB::table('modenadisable')->First();
        $names = User::whereIn('id', $ids)->pluck('name')->toArray();
        $name = implode(', ', $names);

        return redirect('/admin/viewUsers')->with([
            'model' => $models,
            'message' => 'Module settings updated successfully for ' . $name
        ]);

    }
    public function approve($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->update(['approved_at' => now()]);
        $models = DB::table('modenadisable')->First();
        return redirect()
            ->route('admin.users.index')
            ->withMessage('User approved successfully')
            ->with('model', $models);
    }
    public function approveUsers()
    {
        $users = User::whereNull('approved_at')->get();
        $models = DB::table('modenadisable')->First();
        return view('admin.users', compact('users'))->with('model', $models);
    }
    public function viewPurpose()
    {
        $purposes = Purpose::all();
        $parties = Party::all();
        $changereqapp = DB::select('select * from changerequestapproves');
        $users = User::where('approved',true)->orderBy('name')->get();
        $usersEmails = $users->pluck('email')->toArray();
        $changereqreview = DB::select('select * from changerequestreviewers');
        $cats = Changecategory::all();
        $models = DB::table('modenadisable')->First();
        $context_crs_severities = ContextCrsSeverity::all();
        return view('admin.viewPurpose',compact('usersEmails'))->with([
            'users' => $users,
            'purposes' => $purposes,
            'cats' => $cats,
            'changereqapp' => $changereqapp,
            'changereqreview' => $changereqreview,
            'model' => $models,
            'context_crs_severities' => $context_crs_severities,
        ]);
    }
    public function viewchangecategory()
    {
        $cats = Changecategory::all();
        $models = DB::table('modenadisable')->First();
        return view('admin.changeCategories')
            ->with('cats', $cats)
            ->with('model', $models);
    }
    public function viewPriority()
    {
        $priorities = Priority::all();
        $models = DB::table('modenadisable')->First();
        return view('admin.viewPriority')
            ->with('priorities', $priorities)
            ->with('model', $models);
    }
    public function viewParty()
    {
        $parties = Party::all();
        $models = DB::table('modenadisable')->First();
        return view('admin.viewParty')
            ->with('parties', $parties)
            ->with('model', $models);
    }
    //Purpose(context module)
    public function searchedPurpose(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $search = $req->input('search');
        if ($search != null) {
            $purposes = DB::select(
                'select * from purposes where name = :edep or id = :eid',
                ['edep' => $search, 'eid' => $search]
            );
            return view('admin.viewPurpose')
                ->with('purposes', $purposes)
                ->with('model', $models);
        } else {
            return redirect('/admin/viewPurpose')->with('model', $models);
        }
    }

    /**
     * View frequency
     *
     * @return View
     */
    public function viewfreq()
    {
        $models = DB::table('modenadisable')->First();
        $freq = DB::select('select * from calibration_frequency');

        return view('admin.calibration')
            ->with('freq', $freq)
            ->with('model', $models);
    }

    /**
     * Add frequency
     *
     * @param AddFrequencyRequest $req
     * @return RedirectResponse
     */
    public function addfreq(AddFrequencyRequest $req)
    {
        $models    = DB::table('modenadisable')->First();
        $freq      = $req->input('freq');
        $freqTime  = $req->input('freq_time');
        $inputData = $freq . ' ' . ($freq > 1 ? $freqTime . 's' : $freqTime);

        if ($freq != null) {
            $id = DB::select(
                'select id from calibration_frequency where LOWER(frequency) = :entered',
                ['entered' => strtolower($inputData)]
            );
            if ($id) {
                return back()
                    ->with('model', $models)
                    ->with('warning', 'Frequency already exists')
                    ->with('freq', $freq)
                    ->with('freq_time', $freqTime);
            } else {
                $data = ['frequency' => $inputData];
                DB::table('calibration_frequency')->insert($data);

                return back()
                    ->with('model', $models)
                    ->with('message', 'Frequency added successfully');
            }
        }
    }

    public function removefreq(Request $req)
    {
        $freq = DB::select('select * from calibration_frequency');
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            DB::delete('delete from calibration_frequency where id = :eid', [
                'eid' => $id,
            ]);
            return redirect('/admin/viewfreq')
                ->with('model', $models)
                ->with('message', 'Frequency deleted successfully')
                ->with('freq', $freq);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function addpurpose(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');

        $purposes = Purpose::all();
        if ($name != null) {
            $id = DB::select('select id from purposes where name = :entered', [
                'entered' => $name,
            ]);
            if ($id) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'Purpose already exists')
                    ->with('purposes', $purposes);
            } else {
                $data = ['name' => $name];
                DB::table('purposes')->insert($data);

                $activity = 'New purpose is added with name ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return back()
                    ->with('model', $models)
                    ->with('message', 'Purpose added successfully')
                    ->with('purposes', $purposes);
            }
        } else {
            return back()
                ->with('model', $models)
                ->with('purposes', $purposes);
        }
    }
    public function addSeverity(Request $req)
    {
        $models = ModenaDisable::first();
        $name = $req->input('severity_name');
        $severities = ContextCrsSeverity::all();
        if ($name != null) {
            $s = ContextCrsSeverity::where('name', $name)->first();
            if ($s) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'Severity already exists')
                    ->with('severities', $severities);
            } else {
                $data = ['name' => $name];
                ContextCrsSeverity::insert($data);
                $activity = 'New Severity is added with name ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);
                return back()
                    ->with('model', $models)
                    ->with('message', 'Severity added successfully')
                    ->with('severities', $severities);
            }
        } else {
            return back()
                ->with('model', $models)
                ->with('severities', $severities);
        }
    }
    public function editPurpose(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $purposes = Purpose::all()->where('id', '=', $id);
            return view('admin.editpurpose')
                ->with('purposes', $purposes)
                ->with('model', $models);
        }
    }
    public function editSeverity(int $id)
    {
        $models = modenadisable::first();
        $severities = ContextCrsSeverity::all()->where('id', '=', $id);
        return view('admin.editSeverity')
            ->with('severities', $severities)
            ->with('model', $models);
    }
    public function updatePurpose(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');
        $id = $req->input('id');
        $typeid = DB::select('select * from purposes where name = :ename', [
            'ename' => $name,
        ]);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        foreach ($typeid as $row) {
            $type1id = (string) $row->id;
            if ($type1id == $id or $typeid == null) {
                $oldname = DB::table('purposes')
                    ->where('id', $id)
                    ->pluck('name');
                $activity =
                    $oldname . '  Purpose  is Updated with name ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);
                DB::update(
                    'update purposes set name = :ename  where id = :eid',
                    ['ename' => $name, 'eid' => $id]
                );
                return redirect('/admin/viewPurpose')->with('model', $models);
            } else {
                return redirect('/admin/viewPurpose')->with('model', $models);
            }
        }
        if ($typeid == null) {
            $oldname = DB::table('doctypes')
                ->where('id', $id)
                ->pluck('typename');
            $activity =
                $oldname . ' Document Type is Updated with name ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::update('update purposes set name = :ename  where id = :eid', [
                'ename' => $name,
                'eid' => $id,
            ]);
            return redirect('/admin/viewPurpose')->with('model', $models);
        }
    }
    public function updateSeverity(Request $req)
    {
        $models = Modenadisable::first();
        $name = $req->input('name');
        $id = $req->input('id');
        //$typeid = DB::select('select * from purposes where name = :ename', ['ename' => $name]);
        $severity = ContextCrsSeverity::where('name', $name)->first();
        $now = Carbon::now()->format('Y-m-d H:i:s');
        // foreach ($typeid as $row) {
        // $type1id = (string)$row->id;
        if (!$severity) {
            $oldname = ContextCrsSeverity::where('id', $id)->pluck('name');
            $activity = $oldname . '  Severity  is Updated with name ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            //DB::update('update purposes set name = :ename  where id = :eid', ['ename' => $name, 'eid' => $id]);
            ContextCrsSeverity::where('id', $id)->update([
                'name' => $name,
            ]);
            return redirect('/admin/viewPurpose')->with('model', $models);
        } else {
            return redirect('/admin/viewPurpose')->with('model', $models);
        }
        // }
        // if ($typeid == null) {
        //     $oldname = DB::table('doctypes')->where('id', $id)->pluck('typename');
        //     $activity = $oldname . " Document Type is Updated with name " . $name;
        //     $date = Carbon::now()->toDateTimeString();
        //     $date = Carbon::parse($date);
        //     $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
        //     ViewActivity::insert($datas);

        //     DB::update('update purposes set name = :ename  where id = :eid', ['ename' => $name, 'eid' => $id]);
        //     return redirect('/admin/viewPurpose')->with('model', $models);
        // }
    }
    public function removePurpose(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $purpose = Purpose::all();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $name = DB::table('purposes')
                ->where('id', $id)
                ->pluck('name');
            $activity = 'Purpose is Removed with Name ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from purposes where id = :eid', ['eid' => $id]);
            return redirect('/admin/viewPurpose')
                ->with('model', $models)
                ->with('message', 'Purpose deleted successfully')
                ->with('purpose', $purpose);
        } else {
            return redirect('/admin/viewPurpose')
                ->with('model', $models)
                ->with('message', 'Purpose delete unsuccessful')
                ->with('purpose', $purpose);
        }
    }
    public function removeContextSeverity(int $id)
    {
        $models = DB::table('modenadisable')->First();
        $severity = ContextCrsSeverity::all();
        //inserting data to activities table
        $name = ContextCrsSeverity::where('id', $id)->pluck('name');
        $activity = 'Context crs severity is Removed with Name ' . $name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);
        ContextCrsSeverity::where('id', $id)->delete();
        return redirect('/admin/viewPurpose')
            ->with('model', $models)
            ->with('message', 'Severity deleted successfully')
            ->with('severity', $severity);
    }
    //Parties(Context module)
    public function searchedParty(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $search = $req->input('search');
        $purposes = Purpose::all();
        if ($search != null) {
            $parties = DB::select(
                'select * from parties where type = :edep or id = :eid',
                ['edep' => $search, 'eid' => $search]
            );
            return view('admin.viewPurpose')
                ->with('model', $models)
                ->with('parties', $parties)
                ->with('purposes', $purposes);
        } else {
            return redirect('/admin/viewPurpose')->with('model', $models);
        }
    }
    public function addRequestReviewer(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $email = $req->input('emailreview');
        $name = $req->input('try1');
        $changereqreview = DB::select('select * from changerequestreviewers');
        if ($name != null) {
            $id = DB::select(
                'select id from changerequestreviewers where name = :entered ',
                ['entered' => $name]
            );
            if ($id) {
                return back()
                    ->with('message', 'Reviewer already exists')
                    ->with('changereqreview', $changereqreview);
            } else {
                $data = ['name' => $name, 'email' => $email];
                DB::table('changerequestreviewers')->insert($data);

                $activity =
                    'New change request reviewer is added with name ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return back()
                    ->with('message', 'Reviewer added successfully')
                    ->with('changereqreview', $changereqreview);
            }
        } else {
            return back()
                ->with('changereqreview', $changereqreview)
                ->with('model', $models);
        }
    }
    public function editRequestReviewer(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $changereqreview = DB::select(
                'select * from changerequestreviewers where id = :i ',
                ['i' => $id]
            );
            return view('admin.editRequestReviewer')
                ->with('changereqreview', $changereqreview)
                ->with('model', $models);
        }
    }
    public function updateRequestReviewer(Request $req)
    {
        $name = $req->input('name');
        $email = $req->input('email');
        $id = $req->input('id');
        $changereqreview = DB::select(
            'select * from changerequestreviewers where name = :ename',
            ['ename' => $name]
        );
        foreach ($changereqreview as $row) {
            $type1id = (string) $row->id;
            if ($type1id == $id || $changereqreview == null) {
                $oldname = DB::table('changerequestreviewers')
                    ->where('id', $id)
                    ->pluck('name');
                $activity =
                    $oldname .
                    '  change request reviewer  is Updated with name ' .
                    $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                DB::update(
                    'update changerequestreviewers set name = :ename ,email = :eemail where id = :eid',
                    ['ename' => $name, 'eemail' => $email, 'eid' => $id]
                );
                return redirect('/admin/viewPurpose');
            } else {
                return redirect('/admin/viewPriority');
            }
        }

        if ($changereqreview == null) {
            DB::update(
                'update changerequestreviewers set name = :ename , email=:eemail where id = :eid',
                ['ename' => $name, 'eemail' => $email, 'eid' => $id]
            );
            return redirect('/admin/viewPurpose');
        }
    }
    public function removeRequestReviewer(Request $req)
    {
        $changereqreview = DB::select('select * from changerequestreviewers');
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            DB::delete('delete from changerequestreviewers where id = :eid', [
                'eid' => $id,
            ]);

            return redirect('/admin/viewPurpose')
                ->with('message', 'Reviewer deleted successfully')
                ->with('changereqreview', $changereqreview);
        } else {
            return redirect('/admin/viewPurpose')
                ->with('message', 'Reviewer delete unsuccessful')
                ->with('changereqreview', $changereqreview);
        }
    }
    //Priorities
    public function searchedPriority(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $search = $req->input('search');

        if ($search != null) {
            $priorities = DB::select(
                'select * from priorities where name = :edep or id = :eid',
                ['edep' => $search, 'eid' => $search]
            );
            return view('admin.viewPriority')
                ->with('priorities', $priorities)
                ->with('model', $models);
        } else {
            return redirect('/admin/viewPurpose')->with('model', $models);
        }
    }
    public function addRequestApprover(Request $req)
    {
        $email = $req->input('emailappr');

        $name = $req->input('try');

        $changereqapp = DB::select('select * from changerequestapproves');
        if ($name != null) {
            $id = DB::select(
                'select id from changerequestapproves where name = :entered ',
                ['entered' => $name]
            );
            if ($id) {
                return back()
                    ->with('message', 'Approver already exists')
                    ->with('changereqapp', $changereqapp);
            } else {
                $data = ['name' => $name, 'email' => $email];
                DB::table('changerequestapproves')->insert($data);

                $activity =
                    'New change request approver is added with name ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return back()
                    ->with('message', 'Approver added successfully')
                    ->with('changereqapp', $changereqapp);
            }
        } else {
            return back()->with('changereqapp', $changereqapp);
        }
    }
    public function editRequestApprover(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $changereqapp = DB::select(
                'select * from changerequestapproves where id = :i ',
                ['i' => $id]
            );
            return view('admin.editRequestApprover')
                ->with('changereqapp', $changereqapp)
                ->with('model', $models);
        }
    }
    public function updateRequestApprover(Request $req)
    {
        $models = ModenaDisable::first();
        $name = $req->input('name');
        $email = $req->input('email');
        $id = $req->input('id');
        $changereqapp = DB::select(
            'select * from changerequestapproves where name = :ename',
            ['ename' => $name]
        );
        foreach ($changereqapp as $row) {
            $type1id = (string) $row->id;
            if ($type1id == $id || $changereqapp == null) {
                $oldname = DB::table('changerequestapproves')
                    ->where('id', $id)
                    ->pluck('name');
                $activity =
                    $oldname .
                    '  change request approver  is Updated with name ' .
                    $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);
                DB::update(
                    'update changerequestapproves set name = :ename ,email = :eemail where id = :eid',
                    ['ename' => $name, 'eemail' => $email, 'eid' => $id]
                );
                return redirect('/admin/viewPurpose');
            } else {
                return redirect('/admin/viewPriority')->with('model', $models);
            }
        }
        if ($changereqapp == null) {
            DB::update(
                'update changerequestapproves set name = :ename , email=:eemail where id = :eid',
                ['ename' => $name, 'eemail' => $email, 'eid' => $id]
            );
            return redirect('/admin/viewPurpose');
        }
    }
    public function removeRequestApprover(Request $req)
    {
        $models = ModenaDisable::first();
        $changereqapp = DB::select('select * from changerequestapproves');
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            DB::delete('delete from changerequestapproves where id = :eid', [
                'eid' => $id,
            ]);
            return redirect('/admin/viewPurpose')
                ->with('message', 'Approver deleted successfully')
                ->with('changereqapp', $changereqapp);
        } else {
            return redirect('/admin/viewPurpose')
                ->with('message', 'Approver deleted successfully')
                ->with('changereqapp', $changereqapp)
                ->with('model', $models);
        }
    }
    //ChangeCategory(Context module)
    public function searchedCat(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $search = $req->input('search');
        if ($search != null) {
            $cats = DB::select(
                'select * from changecategories where category = :edep or id = :eid',
                ['edep' => $search, 'eid' => $search]
            );
            return view('admin.changeCategories')
                ->with('cats', $cats)
                ->with('model', $models);
        } else {
            return redirect('/admin/changeCategories')->with('model', $models);
        }
    }
    public function addCat(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');
        $cats = Changecategory::all();
        if ($name != null) {
            $id = DB::select(
                'select id from changecategories where category = :entered',
                ['entered' => $name]
            );
            if ($id) {
                return back()
                    ->with('message', 'Category already exists')
                    ->with('cats', $cats)
                    ->with('model', $models);
            } else {
                $data = ['category' => $name];
                DB::table('changecategories')->insert($data);

                $activity =
                    'New category(Context) is added with category ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return back()
                    ->with('message', 'Category added successfully')
                    ->with('cats', $cats)
                    ->with('model', $models);
            }
        } else {
            return back()
                ->with('cats', $cats)
                ->with('model', $models);
        }
    }
    public function editCat(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $cats = Changecategory::all()->where('id', '=', $id);
            return view('admin.editChangeCategory')
                ->with('cats', $cats)
                ->with('model', $models);
        }
    }
    public function updateCat(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('cat');
        $id = $req->input('id');
        $typeid = DB::select(
            'select * from changecategories where category = :ename',
            ['ename' => $name]
        );
        foreach ($typeid as $row) {
            $type1id = (string) $row->id;
            if ($type1id == $id || $typeid == null) {
                $oldname = DB::table('changecategories')
                    ->where('id', $id)
                    ->pluck('category');
                $activity =
                    $oldname .
                    '  Category(Context)  is Updated with name ' .
                    $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);
                DB::update(
                    'update changecategories set category = :ename  where id = :eid',
                    ['ename' => $name, 'eid' => $id]
                );
                return redirect('/admin/viewPurpose')->with('model', $models);
            } else {
                return redirect('/admin/viewPurpose')->with('model', $models);
            }
        }
        if ($typeid == null) {
            $oldname = DB::table('changecategories')
                ->where('id', $id)
                ->pluck('category');
            $activity =
                $oldname . ' Category(Context) is Updated with name ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::update(
                'update changecategories set category = :ename  where id = :eid',
                ['ename' => $name, 'eid' => $id]
            );
            return redirect('/admin/viewPurpose')->with('model', $models);
        }
    }
    public function removeCat(Request $req)
    {
        $cats = Changecategory::all();
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $name = DB::table('changecategories')
                ->where('id', $id)
                ->pluck('category');
            $activity = 'Category is Removed with type ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from changecategories where id = :eid', [
                'eid' => $id,
            ]);

            return redirect('/admin/viewPurpose')
                ->with('model', $models)
                ->with('message', 'Category deleted successfully')
                ->with('cats', $cats);
        } else {
            return redirect('/admin/dashboard')
                ->with('model', $models)
                ->with('messagedelcat', 'Category delete unsuccessful')
                ->with('cats', $cats);
        }
    }
    public function userdelete($id)
    {
        $email = User::where('id',$id)->value('email');
        User::where('id',$id)->delete();
        $model = Modenadisable::First();
        $admin = Admin::where('email',$email)->exists();
        if($admin){
            Admin::where('email',$email)->delete();
        }
        $message = 'The user has been successfully deleted.';
        $message = $admin ? 'The admin and their associated user record have been successfully deleted.' : $message;
        Session::flash('message', $message);
        return redirect('/admin/viewUsers')
                ->with('model', $model)
                ->with('message', $message);
    }
    public function showResponsibilities(int $id)
    {
        $model = Modenadisable::First();
        $users = User::where('id', '!=', $id)->where('approved', true)->get();
        $user = User::findOrFail($id);
        /**** start responsibility for nearmiss module */
        $show_nearmiss_module = false;
        $department_emails = Departments::pluck('hod')->toArray();
        $division_ids = Division::pluck('owner_id')->toArray();
        $division_emails = User::whereIn('id', $division_ids)->pluck('email')->toArray();
        $area_ids = Area::pluck('owner_id')->toArray();
        $area_emails = User::whereIn('id', $area_ids)->pluck('email')->toArray();

        $nearmiss_emails = Nearmiss::pluck('responsibility')->toArray();
        $near_emails = Near::pluck('email')->toArray();
        $representative_emails = Representative::pluck('email')->toArray();
        $nearmiss_module_emails = array_values(
            array_unique(array_merge($nearmiss_emails, $near_emails))
        );
        $common_modules = array_values(
            array_unique(
                array_merge($department_emails, $representative_emails, $division_emails, $area_emails)
            )
        );
        /*** start responsibility for audit management module*/
        $Issuereviewer_emails = Issuereviewer::pluck('email')->toArray();
        $auditor_emails = Auditor::pluck('email')->toArray();
        $issue_emails = Issue::pluck('responsibility')->toArray();
        $internalaudit_emails = Internalaudit::pluck('email')->toArray();
        $archiveprivilege_emails = Archiveprivilege::pluck('email')->toArray();
        $audit_table_emails = Audit::pluck('contact')->toArray();
        $audit_table_emails = array_values(array_unique($audit_table_emails));
        $audit_table_emails_array = [];
        foreach ($audit_table_emails as $item) {
            $audit_table_emails_array = array_merge($audit_table_emails_array, explode(',', $item));
        }
        $audit_table_emails_array = array_values(array_unique($audit_table_emails_array));
        $audit_table_emails_users = User::whereIn('name', $audit_table_emails_array)->pluck('email')->toArray();
        $audit_module_emails = array_values(
            array_unique(
                array_merge(
                    $auditor_emails,
                    $issue_emails,
                    $internalaudit_emails,
                    $archiveprivilege_emails,
                    $audit_table_emails_users
                )
            )
        );
        /*** start responsibility for corrective module */
        $corrective_lead_ids = array_filter(array_unique(Corrective::pluck('team_lead_id')->toArray()));
        $corrective_lead_emails = User::whereIn('id', $corrective_lead_ids)->pluck('email')->toArray();

        $corrective_emails = Corrective::pluck('responsibility')->toArray();
        $corrective_correction_responsibility_emails = Corrective::pluck('correction_responsibility')->toArray();
        $corr_emails = [];

        foreach ($corrective_emails as $mail) {
            $corr_emails = array_merge($corr_emails, explode(',', $mail));
        }

        $corrective_emails = array_unique(array_filter(array_merge($corr_emails, $corrective_lead_emails, $corrective_correction_responsibility_emails, $Issuereviewer_emails)));

        /*** start responsibility for opportunity module*/
        $opportunity_emails = Opportunity::pluck('responsibility')->toArray();
        //$riskreviewer_emails = Riskreviewer::pluck('email')->toArray();
        $closurereviewer_emails = Closurereviewer::pluck('email')->toArray();
        $opportunity_module_emails = array_values(
            array_unique(
                array_merge(
                    $opportunity_emails,
                    $closurereviewer_emails
                )
            )
        );
        /*** start responsibility for objective module*/
        $management_emails = Managements::pluck('email')->toArray();
        $objective_approvers_found = UserGate::where('key','objective-approver')->pluck('users_id')->toArray();
        if(count($objective_approvers_found)>0){
            $objective_approvers_emails = User::whereIn('id',$objective_approvers_found)->pluck('email')->toArray();
        }else{
            $objective_approvers_emails = [];
        }
        $objective_reviewers_found = UserGate::where('key','objective-reviewer')->pluck('users_id')->toArray();
        if(count($objective_reviewers_found)>0){
            $objective_reviewers_emails = User::whereIn('id',$objective_reviewers_found)->pluck('email')->toArray();
        }else{
            $objective_reviewers_emails = [];
        }
        $objective_responsibility_emails = array_values(array_unique(Objective::pluck('responsibility')->toArray()));
        $objective_resp_emails = [];
        $cnt = 0;
        foreach ($objective_responsibility_emails as $item) {
            $resp_found = User::where('name', $item)->first();
            if ($resp_found) {
                $objective_resp_emails[$cnt] = $resp_found->email;
            } else {
                $objective_resp_emails[$cnt] = $item;
            }
            $cnt++;
        }
        $obj_resp_mails = array_values(array_unique($objective_resp_emails));
        $objective_module_emails = array_values(
            array_unique(array_merge($objective_approvers_emails, $objective_reviewers_emails,$obj_resp_mails))
        );
        /*** start responsibility for Risk management module*/

        /*** start responsibility for HSE module*/
        $hseissue_emails = HseIssue::pluck('responsibility')->toArray();
        $hse_observation_reviewer_found = UserGate::where('key','hse-observation-reviewer')->pluck('users_id')->toArray();
        if(count($hse_observation_reviewer_found)>0){
            $hse_observation_reviewer_emails = User::whereIn('id',$hse_observation_reviewer_found)->pluck('email')->toArray();
        }else{
            $hse_observation_reviewer_emails = [];
        }
        $hse_module_emails = array_values(
            array_filter(array_unique(array_merge($hseissue_emails, $hse_observation_reviewer_emails)))
        );

        /*** start responsibility for ohsrisk module*/
        $hses_emails = Hse::pluck('email')->toArray();
        $ohsrisk_secondary_reviewer_found = UserGate::where('key','ohsrisk-secondary-review')->pluck('users_id')->toArray();
        if(count($ohsrisk_secondary_reviewer_found)>0){
            $ohsrisk_secondary_reviewer_emails = User::whereIn('id',$ohsrisk_secondary_reviewer_found)->pluck('email')->toArray();
        }else{
            $ohsrisk_secondary_reviewer_emails = [];
        }
        $ohs_join_gates = Ohs::join('ohs_riskowners', 'ohs.id', '=', 'ohs_riskowners.ohs_id')->where('ohs.risk_status', 'Reviewed')->pluck('ohs_riskowners.user_id')->toArray();
        $ohs_join_emails = [];
        foreach ($ohs_join_gates as $item) {
            $ohs_join_emails[] = User::where('id', $item)->value('email');
        }
        $ohs_join_emails = array_values(array_filter($ohs_join_emails));

        $ohsrisk_module_emails = array_values(
            array_filter(array_unique(array_merge($ohsrisk_secondary_reviewer_emails,$hses_emails, $ohs_join_emails)))
        );

        /*** start responsibility for Incident module*/
        $incident_report = IncidentReport::join('investigators', 'incident_reports.report_no', '=', 'investigators.report_no')->where('investigators.tleader', $user->email)->first();
        $incident_report_mail = $incident_report ? $incident_report->tleader : null;
        $incident_leader = Investigator::where('tleader', $incident_report_mail)->first();

        $incident_reviewer_emails = IncidentReviewer::pluck('email')->toArray();
        $incident_approver_emails = IncidentApprover::pluck('email')->toArray();
        if ($incident_leader) {
            $incident_module_emails = array_values(
                array_unique(
                    array_merge(
                        $incident_reviewer_emails,
                        $incident_approver_emails,
                        [$incident_leader->tleader]
                    )
                )
            );
        } else {
            $incident_module_emails = array_values(
                array_unique(
                    array_merge(
                        $incident_reviewer_emails,
                        $incident_approver_emails
                    )
                )
            );

        }

        $theModels = DB::table('modenadisable')->First();
        /*** start responsibility for compliance module*/

        $legal_coordinator_emails = Legalcoordinator::pluck('email')->toArray();
        $compliance_responsible_emails = Compliance::pluck('responsibility')->toArray();
        $compliance_module_emails = array_unique(array_merge($legal_coordinator_emails, $compliance_responsible_emails));

        /*** start responsibility for complaint module*/
        $complaint_reviewers_emails = Complaintreviewer::pluck('email')->toArray();
        $complaint_registers_emails = Complaintregister::pluck('email')->toArray();
        $complaint_responsibility_emails = DB::table('complaint')->pluck('responsibility')->toArray();
        $complaint_responsibility_emails = array_values(array_unique($complaint_responsibility_emails));
        $complaint_module_emails = array_unique(array_merge($complaint_reviewers_emails, $complaint_registers_emails, $complaint_responsibility_emails));

        /*** start responsibility for enviaspect module */
        $envi_reviewers_emails = Envireviewer::pluck('email')->toArray();

        // enviaspects secondary review
        $envi_secondary_reviewers_found = UserGate::where('key','enviaspect-secondary-review')->pluck('users_id')->toArray();
        if(count($envi_secondary_reviewers_found)>0){
            $envi_secondary_reviewers_emails = User::whereIn('id',$envi_secondary_reviewers_found)->pluck('email')->toArray();
        }else{
            $envi_secondary_reviewers_emails = [];
        }
        $envi_module_emails = array_values(array_filter(array_unique(array_merge($envi_reviewers_emails, $envi_secondary_reviewers_emails))));
        /*** start responsibility for waste module*/
        $waste_reviewers_emails = Wastereviewer::pluck('email')->toArray();
        $waste_approvers_emails = Wasteapprover::pluck('email')->toArray();
        $waste_disposal_sales_authorities_emails = Wastedisposalsalesauthority::pluck('email')->toArray();

        $waste_module_emails = array_unique(array_merge($waste_reviewers_emails, $waste_approvers_emails, $waste_disposal_sales_authorities_emails));

        /*** start responsibility for context module*/
        $context_reviewer_emails = Changerequestreviewer::pluck('email')->toArray();
        $context_approver_emails = Changerequestapprove::pluck('email')->toArray();
        $context_module_emails = array_unique(array_merge($context_reviewer_emails, $context_approver_emails));

        /*** start responsibility for hr module*/
        $trainingcoordinators_emails = Trainingcoordinators::pluck('email')->toArray();
        $hr_module_emails = array_unique($trainingcoordinators_emails);

        /*** start responsibility for maintenance module*/
        $main_reviewer_emails = array_unique(Mainreviewer::pluck('email')->toArray());
        $main_electrical_emails = array_unique(Mainelectrical::pluck('email')->toArray());
        $main_instrument_emails = array_unique(Maininstrument::pluck('email')->toArray());
        $maintenace_module_emails = array_unique(array_merge($main_reviewer_emails, $main_electrical_emails, $main_instrument_emails));

        /*** start responsibility for occupational health module*/
        $company_doctor_emails = CompanyDoctor::pluck('email')->toArray();
        $occhealth_module_emails = array_unique($company_doctor_emails);

        /*** start responsibility for vendor module*/
        $supplierapprovers_emails = Supplierapprovers::pluck('email')->toArray();
        // review vendor
        $review_vendor_found = UserGate::where('key','review-vendor')->pluck('users_id')->toArray();
        if(count($review_vendor_found)>0){
            $review_vendor_emails = User::whereIn('id',$review_vendor_found)->pluck('email')->toArray();
        }else{
            $review_vendor_emails = [];
        }

        // approve vendor
        $approve_vendor_found = UserGate::where('key','approve-vendor')->pluck('users_id')->toArray();
        if(count($approve_vendor_found)>0){
            $approve_vendor_emails = User::whereIn('id',$approve_vendor_found)->pluck('email')->toArray();
        }else{
            $approve_vendor_emails = [];
        }

        $vendor_module_emails = array_values(array_filter(array_unique(array_merge($supplierapprovers_emails, $review_vendor_emails, $approve_vendor_emails))));

        /*** start responsibility for e-learning module */

        // elearning add content
        $elearning_add_content_found = UserGate::where('key','elearning-add-content')->pluck('users_id')->toArray();
        if(count($elearning_add_content_found)>0){
            $elearning_add_content_emails = User::whereIn('id',$elearning_add_content_found)->pluck('email')->toArray();
        }else{
            $elearning_add_content_emails = [];
        }

        $elearning_module_emails = array_filter(array_unique($elearning_add_content_emails));

        /*** start responsibility for Management module */

        // management review meeting controls
        $meeting_controls_found = UserGate::where('key','management-review-meeting-controls')->pluck('users_id')->toArray();
        if(count($meeting_controls_found)>0){
            $meeting_controls_emails = User::whereIn('id',$meeting_controls_found)->pluck('email')->toArray();
        }else{
            $meeting_controls_emails = [];
        }

        $management_module_emails = array_filter(array_unique($meeting_controls_emails));

        /*** start responsibility for risk module*/
        $riskreviewer_emails = Riskreviewer::pluck('email')->toArray();
        $escalateriskreceiver_emails = Escalateriskreceiver::pluck(
            'email'
        )->toArray();
        $risk_emails = array_filter(Risk::pluck('risk_owner')->toArray());
        $mitigation_owners = array_filter(Mitigation::pluck('owner')->toArray());
        $mitigation_emails = User::whereIn('id', $mitigation_owners)->pluck('email')->toArray();
        $racmreviewer_ids = RacmReviewerDetail::pluck('reviewer_id')->toArray();
        $racmreviewer_emails = User::whereIn('id', $racmreviewer_ids)->pluck('email')->toArray();
        $racmapprover_ids = array_filter(RiskRacm::pluck('approver_id')->toArray());
        $racmapprover_emails = User::whereIn('id', $racmapprover_ids)->pluck('email')->toArray();
        $risk_module_emails = array_values(
            array_unique(
                array_merge(
                    $riskreviewer_emails,
                    $escalateriskreceiver_emails,
                    $risk_emails,
                    $mitigation_emails,
                    $racmreviewer_emails,
                    $racmapprover_emails
                )
            )
        );
        /*** start responsibility for document module*/
        $doccontroller_emails = Doccontroller::pluck('email')->toArray();
        $dcr_emails = Dcr::pluck('hod')->toArray();
        $masterdoc_emails = Masterdoc::pluck('hod')->toArray();
        $document_approver_emails = GovernanceHubDraft::pluck('approvers')->toArray();
        $document_reviewer_emails = GovernanceHubDraft::pluck('reviewer')->toArray();
        $doc_reviewer_emails = [];
        foreach ($document_reviewer_emails as $mail) {
            $doc_reviewer_emails = array_merge($doc_reviewer_emails, explode(',', $mail));
        }
        $document_module_emails = array_values(
            array_unique(
                array_merge(
                    $doccontroller_emails,
                    $dcr_emails,
                    $document_approver_emails,
                    $doc_reviewer_emails,
                    $masterdoc_emails
                )
            )
        );
        /*** start responsibility for calibration module*/
        $calibrationreviewer_emails = Calibrationreviewer::pluck(
            'email'
        )->toArray();
        $calibration_module_emails = $calibrationreviewer_emails;
        if ($user != null) {
            $show_nearmiss_module = in_array(
                $user->email,
                $nearmiss_module_emails
            );
            $show_common_module = in_array($user->email, $common_modules);
            $show_audit_module = in_array($user->email, $audit_module_emails);
            $show_corrective_module = in_array(
                $user->email,
                $corrective_emails
            );
            $show_hr_module = in_array(
                $user->email,
                $hr_module_emails
            );

            $show_maintenance_module = in_array(
                $user->email,
                $maintenace_module_emails
            );

            $show_occhealth_module = in_array(
                $user->email,
                $occhealth_module_emails
            );

            $show_management_module = in_array(
                $user->email,
                $management_module_emails
            );

            $show_ohsrisk_module = in_array(
                $user->email,
                $ohsrisk_module_emails
            );

            $show_vendor_module = in_array(
                $user->email,
                $vendor_module_emails
            );

            $show_opportunity_module = in_array(
                $user->email,
                $opportunity_module_emails
            );
            $show_waste_module = in_array(
                $user->email,
                $waste_module_emails
            );

            $show_elearning_module = in_array(
                $user->email,
                $elearning_module_emails
            );

            $show_objective_module = in_array(
                $user->email,
                $objective_module_emails
            );
            $show_hse_module = in_array(
                $user->email, $hse_module_emails
            );
            $show_incident_module = in_array(
                $user->email,
                $incident_module_emails
            );
            $show_compliance_module = in_array(
                $user->email,
                $compliance_module_emails
            );
            $show_complaint_module = in_array(
                $user->email,
                $complaint_module_emails
            );

            $show_context_module = in_array(
                $user->email,
                $context_module_emails
            );

            $show_risk_module = in_array($user->email, $risk_module_emails);
            $show_document_module = in_array(
                $user->email,
                $document_module_emails
            );
            $show_envi_module = in_array(
                $user->email,
                $envi_module_emails
            );

            $show_calibration_module = in_array(
                $user->email,
                $calibration_module_emails
            );
            $show_all_modules = in_array(
                $user->email,
                array_merge(
                    $nearmiss_module_emails,
                    $common_modules,
                    $audit_module_emails,
                    $corrective_emails,
                    $opportunity_module_emails,
                    $objective_module_emails,
                    $hse_module_emails,
                    $incident_module_emails,
                    $compliance_module_emails,
                    $risk_module_emails,
                    $document_module_emails,
                    $calibration_module_emails,
                )
            );
            $deps = Departments::where('hod', $user->email)->get();
            $divisions = Division::where('owner_id', $id)->get();
            $areas = Area::where('owner_id', $id)->get();
            $objective_resp_items = Objective::where('responsibility', $user->email)->orWhere('responsibility', $user->name)->get();
            $auditees = Audit::where('contact', 'like', '%' . $user->name . '%')->get();
            $nearmisses = Nearmiss::where(
                'responsibility',
                $user->email
            )->get();
            $dcrs = Dcr::where('hod', $user->email)->get();
            $masterdocs = Masterdoc::where('hod', $user->email)->get();
            $document_approvers = GovernanceHubDraft::where('approvers', $user->email)->get();
            $document_reviewers = GovernanceHubDraft::where('reviewer', 'like', '%' . $user->email . '%')->get();
            $risks = Risk::where('risk_owner', $user->email)->get();
            $mitigations = Mitigation::where('owner', $id)->get();
            $hseissues = HseIssue::where('responsibility', $user->email)->get();
            $complaint_responsibilities = DB::table('complaint')->where('responsibility', $user->email)->get();
            $lead_correctives = Corrective::where('team_lead_id', $id)->get();
            $correctives = Corrective::where(
                'responsibility',
                'like', '%' . $user->email . '%'
            )->get();
            $corrective_correction_responsibilities = Corrective::where(
                'correction_responsibility',
                'like', '%' . $user->email . '%'
            )->get();

            $compliances = Compliance::where('responsibility', $user->email)->get();
            $opportunities = Opportunity::where('responsibility', $user->email)
            //->where('status', '!=', 'Closed')
                ->get();
            $near = Near::where('email', $user->email)->first();
            $riskReviwer = Riskreviewer::where('email', $user->email)->first();
            $representative = Representative::where(
                'email',
                $user->email
            )->first();
            $representative_users = User::where('id', '!=', $id)
                ->whereIn('email', $representative_emails)
                ->where('approved', true)
                ->get();
            $trainingcoordinator = Trainingcoordinators::where(
                'email',
                $user->email
            )->first();
            $trainingcoordinator_users = User::where('id', '!=', $id)
                ->whereIn('email', $trainingcoordinators_emails)
                ->where('approved', true)
                ->get();
            $supplierapprover = Supplierapprovers::where(
                'email',
                $user->email
            )->first();
            $supplierapprover_users = User::where('id', '!=', $id)
                ->whereIn('email', $supplierapprovers_emails)
                ->where('approved', true)
                ->get();

            $admin_found = Admin::where('email', $user->email)->first();
            $vendor_reviewer = UserGate::where('key','review-vendor')->where('users_id',$id)->exists() ? User::where('email',$user->email)->first() : null;
            $vendor_reviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $review_vendor_emails)
                ->where('approved', true)
                ->get();
            // if ($admin_found) {
            //     $vendor_reviewer_count = UserGate::where('key', 'review-vendor')->where('users_id', $admin_found->id)->count();
            //     $vendor_reviewer = $vendor_reviewer_count ? User::where('email', $user->email)->first() : null;
            // } else {
            //     $vendor_reviewer_count = UserGate::where('key', 'review-vendor')->where('users_id', $user->id)->count();
            //     $vendor_reviewer = $vendor_reviewer_count ? User::where('email', $user->email)->first() : null;
            // }

            // $vendor_reviewer_users = User::where('id', '!=', $id)
            //     ->whereIn('email', $review_vendor_emails)
            //     ->where('approved', true)
            //     ->get();

            $vendor_approver = UserGate::where('key','approve-vendor')->where('users_id',$id)->exists() ? User::where('email',$user->email)->first() : null;
            $vendor_approver_users = User::where('id', '!=', $id)
                ->whereIn('email', $approve_vendor_emails)
                ->where('approved', true)
                ->get();
            // if ($admin_found) {
            //     $vendor_approver_count = UserGate::where('key', 'approve-vendor')->where('users_id', $admin_found->id)->count();
            //     $vendor_approver = $vendor_approver_count ? User::where('email', $user->email)->first() : null;
            // } else {
            //     $vendor_approver_count = UserGate::where('key', 'approve-vendor')->where('users_id', $user->id)->count();
            //     $vendor_approver = $vendor_approver_count ? User::where('email', $user->email)->first() : null;
            // }

            // $vendor_approver_users = User::where('id', '!=', $id)
            //     ->whereIn('email', $approve_vendor_emails)
            //     ->where('approved', true)
            //     ->get();
            $elearning_add_content = UserGate::where('key','elearning-add-content')->where('users_id',$id)->exists() ? User::where('email',$user->email)->first() : null;
            $elearning_add_content_users = User::where('id', '!=', $id)
                ->whereIn('email', $elearning_module_emails)
                ->where('approved', true)
                ->get();
            // if ($admin_found) {
            //     $elearning_add_content_count = UserGate::where('key', 'elearning-add-content')->where('users_id', $admin_found->id)->count();
            //     $elearning_add_content = $elearning_add_content_count ? User::where('email', $user->email)->first() : null;
            // } else {
            //     $elearning_add_content_count = UserGate::where('key', 'elearning-add-content')->where('users_id', $user->id)->count();
            //     $elearning_add_content = $elearning_add_content_count ? User::where('email', $user->email)->first() : null;
            // }

            // $elearning_add_content_users = User::where('id', '!=', $id)
            //     ->whereIn('email', $elearning_module_emails)
            //     ->where('approved', true)
            //     ->get();
            $envi_secondary_reviewer = UserGate::where('key','enviaspect-secondary-review')->where('users_id',$id)->exists() ? User::where('email',$user->email)->first() : null;
            $envi_secondary_reviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $envi_secondary_reviewers_emails)
                ->where('approved', true)
                ->get();
            $hse_observation_reviewer = UserGate::where('key','hse-observation-reviewer')->where('users_id',$id)->exists() ? User::where('email',$user->email)->first() : null;
            $hse_observation_reviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $hse_observation_reviewer_emails)
                ->where('approved', true)
                ->get();
            $management_reviewer = UserGate::where('key','management-review-meeting-controls')->where('users_id',$id)->exists() ? User::where('email',$user->email)->first() : null;
            $management_review_users = User::where('id', '!=', $id)
                ->whereIn('email', $management_module_emails)
                ->where('approved', true)
                ->get();
            $ohsrisk_secondary_review = UserGate::where('key','ohsrisk-secondary-review')->where('users_id',$id)->exists() ? User::where('email',$user->email)->first() : null;
            $ohsrisk_owner = User::where('email',$user->email)->first();
            $ohsrisk_users = User::where('id', '!=', $id)
                ->whereIn('email', $ohsrisk_module_emails)
                ->where('approved', true)
                ->get();
            $mainreviewer = Mainreviewer::where(
                'email',
                $user->email
            )->first();
            $mainreviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $main_reviewer_emails)
                ->where('approved', true)
                ->get();
            $mainelectrical = Mainreviewer::where(
                'email',
                $user->email
            )->first();
            $mainelectrical_users = User::where('id', '!=', $id)
                ->whereIn('email', $main_electrical_emails)
                ->where('approved', true)
                ->get();
            $maininstrument = Mainreviewer::where(
                'email',
                $user->email
            )->first();
            $maininstrument_users = User::where('id', '!=', $id)
                ->whereIn('email', $main_instrument_emails)
                ->where('approved', true)
                ->get();

            $company_doctor = CompanyDoctor::where(
                'email',
                $user->email
            )->first();
            $company_doctor_users = User::where('id', '!=', $id)
                ->whereIn('email', $company_doctor_emails)
                ->where('approved', true)
                ->get();

            $envireviewer = Envireviewer::where(
                'email',
                $user->email
            )->first();
            $envireviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $envi_reviewers_emails)
                ->where('approved', true)
                ->get();

            $Issuereviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $Issuereviewer_emails)
                ->where('approved', true)
                ->get();
            $near_users = User::where('id', '!=', $id)
                ->whereIn('email', $near_emails)
                ->where('approved', true)
                ->get();
            $riskreviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $riskreviewer_emails)
                ->where('approved', true)
                ->get();
            $Issuereviewer = Issuereviewer::where(
                'email',
                $user->email
            )->first();
            $auditor = Auditor::where('email', $user->email)->first();
            $auditor_users = User::where('id', '!=', $id)
                ->whereNotIn('email', $auditor_emails)
                ->where('approved', true)
                ->get();

            $context_reviewer = Changerequestreviewer::where('email', $user->email)->first();
            $context_reviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $context_reviewer_emails)
                ->where('approved', true)
                ->get();

            $context_approver = Changerequestapprove::where('email', $user->email)->first();
            $context_approver_users = User::where('id', '!=', $id)
                ->whereIn('email', $context_approver_emails)
                ->where('approved', true)
                ->get();

            $issues = Issue::where('responsibility', $user->email)->get();
            $internalaudit = Internalaudit::where(
                'email',
                $user->email
            )->first();
            $internalaudit_users = User::where('id', '!=', $id)
                ->whereIn('email', $internalaudit_emails)
                ->where('approved', true)
                ->get();
            $archiveprivilege = Archiveprivilege::where(
                'email',
                $user->email
            )->first();
            $archiveprivilege_users = User::where('id', '!=', $id)
                ->whereIn('email', $archiveprivilege_emails)
                ->where('approved', true)
                ->get();
            $closurereviewer = Closurereviewer::where(
                'email',
                $user->email
            )->first();
            $closurereviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $closurereviewer_emails)
                ->where('approved', true)
                ->get();
            $management = Managements::where('email', $user->email)->first();
            $management_users = User::where('id', '!=', $id)
                ->whereIn('email', $management_emails)
                ->where('approved', true)
                ->get();
            $waste_reviewer = WasteReviewer::where('email', $user->email)->first();
            $waste_reviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $waste_reviewers_emails)
                ->where('approved', true)
                ->get();

            $waste_approver = Wasteapprover::where('email', $user->email)->first();
            $waste_approver_users = User::where('id', '!=', $id)
                ->whereIn('email', $waste_approvers_emails)
                ->where('approved', true)
                ->get();
            $objective_reviewer = UserGate::where('key','objective-reviewer')->where('users_id',$id)->exists() ? User::where('email',$user->email)->first() : null;
            $objective_reviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $objective_reviewers_emails)
                ->where('approved', true)
                ->get();

            $objective_approver = UserGate::where('key','objective-approver')->where('users_id',$id)->exists() ? User::where('email',$user->email)->first() : null;
            $objective_approver_users = User::where('id', '!=', $id)
                ->whereIn('email', $objective_approvers_emails)
                ->where('approved', true)
                ->get();
            $waste_disposal_sales_authorithy = Wastedisposalsalesauthority::where('email', $user->email)->first();
            $waste_disposal_sales_authorithy_users = User::where('id', '!=', $id)
                ->whereIn('email', $waste_disposal_sales_authorities_emails)
                ->where('approved', true)
                ->get();

            $incident_reviewer = IncidentReviewer::where(
                'email',
                $user->email
            )->first();
            $incident_reviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $incident_reviewer_emails)
                ->where('approved', true)
                ->get();
            $incident_approver = IncidentApprover::where(
                'email',
                $user->email
            )->first();
            $incident_approver_users = User::where('id', '!=', $id)
                ->whereIn('email', $incident_approver_emails)
                ->where('approved', true)
                ->get();

            $incident_leader_users = User::where('id', '!=', $id)->where('approved', true)->where('email', '!=', $incident_leader)->get();

            $hses = Hse::where('email', $user->email)->first();
            $hses_users = User::where('id', '!=', $id)
                ->whereIn('email', $hses_emails)
                ->where('approved', true)
                ->get();
            $legal_coordinator = Legalcoordinator::where(
                'email',
                $user->email
            )->first();
            $legal_coordinator_users = User::where('id', '!=', $id)
                ->whereIn('email', $legal_coordinator_emails)
                ->where('approved', true)
                ->get();
            $complaint_reviewer = Complaintreviewer::where(
                'email',
                $user->email
            )->first();
            $complaint_reviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $complaint_reviewers_emails)
                ->where('approved', true)
                ->get();
            $complaint_register = Complaintregister::where(
                'email',
                $user->email
            )->first();
            $complaint_register_users = User::where('id', '!=', $id)
                ->whereIn('email', $complaint_registers_emails)
                ->where('approved', true)
                ->get();

            $escalateriskreceiver = Escalateriskreceiver::where(
                'email',
                $user->email
            )->first();
            $escalateriskreceiver_users = User::where('id', '!=', $id)
                ->whereIn('email', $escalateriskreceiver_emails)
                ->where('approved', true)
                ->get();
            $racmreviewer = User::where('id',$id)->where('email',$user->email)->first();
            $racmreviewer_users = User::where('id', '!=', $id)
                ->where('approved', true)
                ->get();
            $racmapprover = User::where('id',$id)->where('email',$user->email)->first();
            $racmapprover_users = User::where('id', '!=', $id)
                ->where('approved', true)
                ->get();
            $doccontroller = Doccontroller::where(
                'email',
                $user->email
            )->first();
            $doccontroller_users = User::where('id', '!=', $id)
                ->whereIn('email', $doccontroller_emails)
                ->where('approved', true)
                ->get();
            $calibrationreviewer = Calibrationreviewer::where(
                'email',
                $user->email
            )->first();
            $calibrationreviewer_users = User::where('id', '!=', $id)
                ->whereIn('email', $calibrationreviewer_emails)
                ->where('approved', true)
                ->get();
            $representative_count = Representative::count();
            $near_count = Near::count();
            $calibrationreviewer_count = Calibrationreviewer::count();
            $doccontroller_count = Doccontroller::count();
            $hse_count = Hse::count();
            $issuereviewer_count = Issuereviewer::count();
            $internalaudit_count = Internalaudit::count();
            $archiveprivilege_count = Archiveprivilege::count();
            $riskreviewer_count = Riskreviewer::count();
            $escalateriskreceiver_count = Escalateriskreceiver::count();
            $closurereviewer_count = Closurereviewer::count();
            $management_count = Managements::count();
            $legal_coordinator_count = Legalcoordinator::count();
            $incident_reviewer_count = IncidentReviewer::count();
            $incident_approver_count = IncidentApprover::count();
            $complaint_reviewer_count = Complaintreviewer::count();
            $complaint_register_count = Complaintregister::count();
            $change_request_reviewer_count = Changerequestreviewer::count();
            $change_request_approve_count = Changerequestapprove::count();
            $waste_reviewer_count = WasteReviewer::count();
            $waste_approver_count = Wasteapprover::count();
            $waste_disposal_sales_authorithy_count = Wastedisposalsalesauthority::count();
            $envi_reviewer_count = Envireviewer::count();
            $trainingcoordinator_count = Trainingcoordinators::count();
            $mainreviewer_count = Mainreviewer::count();
            $mainelectrical_count = Mainelectrical::count();
            $main_instrument_count = Maininstrument::count();
            $company_doctor_count = CompanyDoctor::count();
            $supplierapprover_count = Supplierapprovers::count();
            $review_vendor_count = UserGate::where('key', 'review-vendor')->count();
            $approve_vendor_count = UserGate::where('key', 'approve-vendor')->count();
            $elearning_add_content_countt = UserGate::where('key', 'elearning-add-content')->count();
            $enviaspect_secondary_review_count = UserGate::where('key', 'enviaspect-secondary-review')->count();
            $hse_observation_reviewer_countt = UserGate::where('key', 'hse-observation-reviewer')->count();
            $objective_reviewer_countt = UserGate::where('key', 'objective-reviewer')->count();
            $objective_approver_countt = UserGate::where('key', 'objective-approver')->count();
            $management_review_meeting_controls_count = UserGate::where('key', 'management-review-meeting-controls')->count();
            $ohsrisk_secondary_review_count = UserGate::where('key', 'ohsrisk-secondary-review')->count();
            $racm_reviewer_countt = UserGate::where('key', 'objective-reviewer')->count();
            $racm_approver_countt = UserGate::where('key', 'objective-approver')->count();
            if ($show_all_modules) {
                return view('admin.ShowResponsibilities')->with([
                    'show_nearmiss_module' => $show_nearmiss_module,
                    'show_common_module' => $show_common_module,
                    'show_hr_module' => $show_hr_module,
                    'show_management_module' => $show_management_module,
                    'show_ohsrisk_module' => $show_ohsrisk_module,
                    'show_maintenance_module' => $show_maintenance_module,
                    'show_elearning_module' => $show_elearning_module,
                    'show_occhealth_module' => $show_occhealth_module,
                    'show_vendor_module' => $show_vendor_module,
                    'show_envi_module' => $show_envi_module,
                    'show_context_module' => $show_context_module,
                    'show_audit_module' => $show_audit_module,
                    'show_corrective_module' => $show_corrective_module,
                    'show_opportunity_module' => $show_opportunity_module,
                    'show_objective_module' => $show_objective_module,
                    'show_hse_module' => $show_hse_module,
                    'show_incident_module' => $show_incident_module,
                    'show_compliance_module' => $show_compliance_module,
                    'show_complaint_module' => $show_complaint_module,
                    'show_risk_module' => $show_risk_module,
                    'show_waste_module' => $show_waste_module,
                    'show_document_module' => $show_document_module,
                    'show_calibration_module' => $show_calibration_module,
                    'correctives' => $correctives,
                    'compliances' => $compliances,
                    'lead_correctives' => $lead_correctives,
                    'auditees' => $auditees,
                    'model' => $model,
                    'deps' => $deps,
                    'divisions' => $divisions,
                    'areas' => $areas,
                    'objective_resp_items' => $objective_resp_items,
                    'nearmisses' => $nearmisses,
                    'dcrs' => $dcrs,
                    'masterdocs' => $masterdocs,
                    'document_approvers' => $document_approvers,
                    'document_reviewers' => $document_reviewers,
                    'risks' => $risks,
                    'mitigations' => $mitigations,
                    'users' => $users,
                    'opportunities' => $opportunities,
                    'representative_users' => $representative_users,
                    'representative_count' => $representative_count,
                    'near_count' => $near_count,
                    'calibrationreviewer_count' => $calibrationreviewer_count,
                    'doccontroller_count' => $doccontroller_count,
                    'hse_count' => $hse_count,
                    'issuereviewer_count' => $issuereviewer_count,
                    'internalaudit_count' => $internalaudit_count,
                    'archiveprivilege_count' => $archiveprivilege_count,
                    'riskreviewer_count' => $riskreviewer_count,
                    'escalateriskreceiver_count' => $escalateriskreceiver_count,
                    'closurereviewer_count' => $closurereviewer_count,
                    'management_count' => $management_count,
                    'legal_coordinator_count' => $legal_coordinator_count,
                    'incident_reviewer_count' => $incident_reviewer_count,
                    'incident_approver_count' => $incident_approver_count,
                    'complaint_reviewer_count' => $complaint_reviewer_count,
                    'complaint_register_count' => $complaint_register_count,
                    'change_request_reviewer_count' => $change_request_reviewer_count,
                    'change_request_approve_count' => $change_request_approve_count,
                    'waste_reviewer_count' => $waste_reviewer_count,
                    'waste_approver_count' => $waste_approver_count,
                    'waste_disposal_sales_authorithy_count' => $waste_disposal_sales_authorithy_count,
                    'envi_reviewer_count' => $envi_reviewer_count,
                    'trainingcoordinator_count' => $trainingcoordinator_count,
                    'mainreviewer_count' => $mainreviewer_count,
                    'mainelectrical_count' => $mainelectrical_count,
                    'main_instrument_count' => $main_instrument_count,
                    'company_doctor_count' => $company_doctor_count,
                    'supplierapprover_count' => $supplierapprover_count,
                    'review_vendor_count' => $review_vendor_count,
                    'approve_vendor_count' => $approve_vendor_count,
                    'elearning_add_content_countt' => $elearning_add_content_countt,
                    'enviaspect_secondary_review_count' => $enviaspect_secondary_review_count,
                    'hse_observation_reviewer_countt' => $hse_observation_reviewer_countt,
                    'objective_reviewer_countt' => $objective_reviewer_countt,
                    'objective_approver_countt' => $objective_approver_countt,
                    'racm_reviewer_countt' => $racm_reviewer_countt,
                    'racm_approver_countt' => $racm_approver_countt,
                    'management_review_meeting_controls_count' => $management_review_meeting_controls_count,
                    'ohsrisk_secondary_review_count' => $ohsrisk_secondary_review_count,
                    'Issuereviewer_users' => $Issuereviewer_users,
                    'Issuereviewer' => $Issuereviewer,
                    'near' => $near,
                    'near_users' => $near_users,
                    'representative' => $representative,
                    'auditor' => $auditor,
                    'auditor_users' => $auditor_users,
                    'internalaudit' => $internalaudit,
                    'internalaudit_users' => $internalaudit_users,
                    'archiveprivilege' => $archiveprivilege,
                    'archiveprivilege_users' => $archiveprivilege_users,
                    'issues' => $issues,
                    'riskreviewer' => $riskReviwer,
                    'riskreviewer_users' => $riskreviewer_users,
                    'closurereviewer' => $closurereviewer,
                    'closurereviewer_users' => $closurereviewer_users,
                    'management' => $management,
                    'management_users' => $management_users,
                    'near_text' => 'Nears',
                    'near_select' => 'near',
                    'legal_coordinator_text' => 'Legal Coordinator',
                    'legal_coordinator_select' => 'legalCoordinator',
                    'legal_coordinator_users' => $legal_coordinator_users,
                    'vendor_reviewer' => $vendor_reviewer,
                    'vendor_reviewer_text' => 'Vendor Reviewer',
                    'vendor_reviewer_select' => 'vendor_reviewer',
                    'vendor_reviewer_users' => $vendor_reviewer_users,
                    'envi_secondary_reviewer' => $envi_secondary_reviewer,
                    'envi_secondary_reviewer_text' => 'Enviaspects Secondary Reviewer',
                    'envi_secondary_reviewer_select' => 'envi_secondary_reviewer',
                    'envi_secondary_reviewer_users' => $envi_secondary_reviewer_users,
                    'management_reviewer' => $management_reviewer,
                    'management_reviewer_text' => 'Management Reviewer',
                    'management_reviewer_select' => 'management_reviewer',
                    'management_reviewer_users' => $management_review_users,
                    'hse_observation_reviewer' => $hse_observation_reviewer,
                    'hse_observation_reviewer_text' => 'HSE Observation reviewer',
                    'hse_observation_reviewer_select' => 'hse_observation_reviewer',
                    'hse_observation_reviewer_users' => $hse_observation_reviewer_users,

                    'objective_reviewer' => $objective_reviewer,
                    'objective_reviewer_text' => 'Objective reviewer',
                    'objective_reviewer_select' => 'objective_reviewer',
                    'objective_reviewer_users' => $objective_reviewer_users,
                    'objective_approver' => $objective_approver,
                    'objective_approver_text' => 'Objective approver',
                    'objective_approver_select' => 'Objective_approver',
                    'objective_approver_users' => $objective_approver_users,

                    'ohsrisk_owner' => $ohsrisk_owner,
                    'ohsrisk_owner_text' => 'Ohsrisk owner',
                    'ohsrisk_owner_select' => 'ohsrisk_owner',
                    'ohsrisk_owner_users' => $ohsrisk_users,
                    'ohsrisk_secondary_review' => $ohsrisk_secondary_review,
                    'ohsrisk_secondary_review_text' => 'Periodic Reviewer',
                    'ohsrisk_secondary_review_select' => 'ohs_secondary_review',
                    'ohsrisk_secondary_review_users' => $ohsrisk_users,

                    'vendor_approver' => $vendor_approver,
                    'vendor_approver_text' => 'Vendor Approver',
                    'vendor_approver_select' => 'vendor_approver',
                    'vendor_approver_users' => $vendor_approver_users,
                    'elearning_add_content' => $elearning_add_content,
                    'elearning_add_content_text' => 'Elearning add content',
                    'elearning_add_content_select' => 'elearning_add_content',
                    'elearning_add_content_users' => $elearning_add_content_users,
                    'supplierapprover' => $supplierapprover,
                    'supplierapprover_text' => 'Supplier Approver',
                    'supplierapprover_select' => 'supplierapprover',
                    'supplierapprover_users' => $supplierapprover_users,
                    'trainingcoordinator' => $trainingcoordinator,
                    'trainingcoordinator_text' => 'Training Coordinator',
                    'trainingcoordinator_select' => 'trainingcoordinator',
                    'trainingcoordinator_users' => $trainingcoordinator_users,
                    'mainreviewer' => $mainreviewer,
                    'mainreviewer_text' => 'Mainreviewer',
                    'mainreviewer_select' => 'mainreviewer',
                    'mainreviewer_users' => $mainreviewer_users,
                    'mainelectrical' => $mainelectrical,
                    'mainelectrical_text' => 'Mainelectrical',
                    'mainelectrical_select' => 'mainelectrical',
                    'mainelectrical_users' => $mainelectrical_users,
                    'maininstrument' => $maininstrument,
                    'maininstrument_text' => 'Maininstrument',
                    'maininstrument_select' => 'maininstrument',
                    'maininstrument_users' => $maininstrument_users,
                    'company_doctor' => $company_doctor,
                    'company_doctor_text' => 'Company Doctor',
                    'company_doctor_select' => 'company_doctor',
                    'company_doctor_users' => $company_doctor_users,
                    'waste_reviewer' => $waste_reviewer,
                    'waste_reviewer_text' => 'Waste Reviewer',
                    'waste_reviewer_select' => 'waste_reviewer',
                    'waste_reviewer_users' => $waste_reviewer_users,
                    'waste_approver' => $waste_approver,
                    'waste_approver_text' => 'Waste Approver',
                    'waste_approver_select' => 'waste_approver',
                    'waste_approver_users' => $waste_approver_users,
                    'envi_reviewer' => $envireviewer,
                    'envi_reviewer_text' => 'Enviaspects Reviewer',
                    'envi_reviewer_select' => 'envireviewer',
                    'envi_reviewer_users' => $envireviewer_users,
                    'waste_disposal_sales_authorithy' => $waste_disposal_sales_authorithy,
                    'waste_disposal_sales_authorities_text' => 'Waste Disposal Sales Authorities',
                    'waste_disposal_sales_authorities_select' => 'waste_disposal_sales_authorities',
                    'waste_disposal_sales_authorities_users' => $waste_disposal_sales_authorithy_users,
                    'complaint_reviewer' => $complaint_reviewer,
                    'complaint_reviewer_users' => $complaint_reviewer_users,
                    'complaint_reviewer_text' => 'Complaint Reviewer',
                    'complaint_reviewer_select' => 'complaintreviewer',
                    'complaint_register' => $complaint_register,
                    'complaint_register_users' => $complaint_register_users,
                    'complaint_register_text' => 'Complaint Register',
                    'complaint_register_select' => 'complaintregister',
                    'legal_coordinator' => $legal_coordinator,
                    'calibrationreviewer_text' => 'Calibration Reviewer',
                    'calibrationreviewer_select' => 'calibration_reviewer',
                    'calibrationreviewer_users' => $calibrationreviewer_users,
                    'calibrationreviewer' => $calibrationreviewer,
                    'doccontroller_text' => 'Doc controller',
                    'doccontroller_select' => 'doccontroller',
                    'doccontroller_users' => $doccontroller_users,
                    'doccontroller' => $doccontroller,
                    'context_reviewer' => $context_reviewer,
                    'context_reviewer_text' => 'Context Reviewer',
                    'context_reviewer_select' => 'contextreviewer',
                    'context_reviewer_users' => $context_reviewer_users,
                    'context_approver' => $context_approver,
                    'context_approver_text' => 'Context Approver',
                    'context_approver_select' => 'contextapprover',
                    'context_approver_users' => $context_approver_users,
                    'escalateriskreceiver_text' => 'escalateriskreceiver',
                    'escalateriskreceiver_select' => 'escalateriskreceiver',
                    'escalateriskreceiver_users' => $escalateriskreceiver_users,
                    'escalateriskreceiver' => $escalateriskreceiver,
                    'racmreviewer_text' => 'Racm Reviewer',
                    'racmreviewer_select' => 'racmreviewer',
                    'racmreviewer_users' => $racmreviewer_users,
                    'racmreviewer' => $racmreviewer,
                    'racmapprover_text' => 'Racm Approver',
                    'racmapprover_select' => 'racmapprover',
                    'racmapprover_users' => $racmapprover_users,
                    'racmapprover' => $racmapprover,
                    'representative_text' => 'Representatives',
                    'representative_select' => 'representative',
                    'issuereviewer_text' => 'Issuereviewers',
                    'issuereviewer_select' => 'IssueReviewer',
                    'internalaudit_text' => 'Internal audits',
                    'internalaudit_select' => 'internalaudit',
                    'archiveprivilege_text' => 'Archive Privileges',
                    'archiveprivilege_select' => 'archiveprivilege',
                    'riskreviewer_text' => 'riskreviewer',
                    'closurereviewer_text' => 'closurereviewer',
                    'management_text' => 'Managements',
                    'management_select' => 'management',
                    'nearmiss_text' => 'Nearmiss',
                    'nearmiss_name' => 'nearmiss',
                    'nearmiss_select' => 'nearmiss',
                    'complaint_responsibilities' => $complaint_responsibilities,
                    'complaint_responsibilities_text' => 'Complaint responsibilities',
                    'complaint_responsibilities_name' => 'complaint_responsibilities',
                    'complaint_responsibilities_select' => 'complaint_responsibilities',
                    'dcr_text' => 'Dcr',
                    'dcr_name' => 'dcr[]',
                    'dcr_select' => 'dcr',
                    'hseissues' => $hseissues,
                    'hseissue_text' => 'HSE Issues',
                    'hseissue_name' => 'hseissue',
                    'hseissue_select' => 'hseissue',
                    'issue_text' => 'Issues',
                    'issue_name' => 'issues',
                    'issue_select' => 'issues',
                    'corrective_text' => 'Corrective Responsibilities',
                    'corrective_name' => 'corrective_responsibility',
                    'corrective_select' => 'corrective_responsibility',
                    'corrective_correction_responsibilities' => $corrective_correction_responsibilities,
                    'corrective_correction_text' => 'Corrective Correction Responsibilities',
                    'corrective_correction_name' => 'corrective_correction_responsibility',
                    'corrective_correction_select' => 'corrective_correction_responsibility',
                    'incident_reviewer' => $incident_reviewer,
                    'incident_reviewer_users' => $incident_reviewer_users,
                    'incident_reviewer_text' => 'Incident Reviewer',
                    'incident_reviewer_select' => 'incident_reviewer',
                    'incident_approver' => $incident_approver,
                    'incident_approver_users' => $incident_approver_users,
                    'incident_approver_text' => 'Incident Approver',
                    'incident_approver_select' => 'incident_approver',
                    'incident_leader' => $incident_leader,
                    'incident_leader_users' => $incident_leader_users,
                    'incident_leader_text' => 'Incident Leader',
                    'incident_leader_select' => 'incident_leader',
                    'hses' => $hses,
                    'hses_users' => $hses_users,
                    'hses_text' => 'Health and Safety In-Charge(Hses)',
                    'hses_select' => 'hses',
                    'user' => $user,
                    'id' => $id,
                ]);
            } else {
                return $this->removeUser($id, false)->with('model', $theModels);
            }
        }
    }
    public function ajaxDept(Request $request)
    {
        $error = null;
        $dept = $request->input('dept');
        $deptId = $request->input('deptId');
        $hodposition = $request->input('hodposition');
        $user = User::find($dept);
        if ($dept == 0 || $hodposition == '') {
            $error = 'Please fill both these fields';
        }
        if ($error == null) {
            Departments::where('id', $deptId)->update([
                'hod' => $user->email,
                'hodname' => $user->name,
                'hodposition' => $hodposition,
            ]);
        }
        return [
            'data' => null,
            'error' => $error,
        ];
    }
    public function ajaxWasteTarget(AddWasteTargetRequest $request)
    {
        $items = $request->input('items');
        $qty = $request->input('qty');
        $approvers_id = $request->input('approvers_id');
        Wastetarget::insert([
            'items' => $items,
            'qty' => $qty,
            'approvers_id' => $approvers_id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        return [
            'data' => null,
            'error' => null,
        ];
    }
    public function ajaxAuditor(Request $request)
    {
        $error = null;
        $auditor = $request->input('auditor');
        $auditorId = $request->input('auditorId');
        $certificate = $request->input('certificate');
        $experience = $request->input('experience');
        $user = User::find($auditor);
        if ($auditor == 0 || $certificate == '' || $experience == '') {
            $error = 'Please fill all these fields';
        }
        if ($error == null) {
            Auditor::where('id', $auditorId)->update([
                'email' => $user->email,
                'auditorsname' => $user->name,
                'certificate' => $certificate,
                'experience' => $experience,
            ]);
        }
        return [
            'data' => null,
            'error' => $error,
        ];
    }
    public function updateResponsibilty($item, $model, $userMail)
    {
        if ($item) {
            $item = explode(',', $item);
            $user = User::find($item[0]);
            $new_email = $user->email;
            $new_name = $user->name;
            $model::where('email', $new_email)->delete();
            $model::where('id', $item[1])->update([
                'email' => $new_email,
                'name' => $new_name,
            ]);
        } else {
            $model::where('email', $userMail)->delete();
        }
    }

    public function updateThePrivilege($userMail, $new_responsibility_user)
    {
        if(Representative::where('email', $new_responsibility_user->email)->exists()){
            Representative::where('email', $userMail)->delete();
        }else{
            Representative::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Legalcoordinator::where('email', $new_responsibility_user->email)->exists()){
            Legalcoordinator::where('email', $userMail)->delete();
        }else{
            Legalcoordinator::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Riskreviewer::where('email', $new_responsibility_user->email)->exists()){
            Riskreviewer::where('email', $userMail)->delete();
        }else{
            Riskreviewer::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Escalateriskreceiver::where('email', $new_responsibility_user->email)->exists()){
            Escalateriskreceiver::where('email', $userMail)->delete();
        }else{
            Escalateriskreceiver::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Managements::where('email', $new_responsibility_user->email)->exists()){
            Managements::where('email', $userMail)->delete();
        }else{
            Managements::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Closurereviewer::where('email', $new_responsibility_user->email)->exists()){
            Closurereviewer::where('email', $userMail)->delete();
        }else{
            Closurereviewer::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Hse::where('email', $new_responsibility_user->email)->exists()){
            Hse::where('email', $userMail)->delete();
        }else{
            Hse::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Doccontroller::where('email', $new_responsibility_user->email)->exists()){
            Doccontroller::where('email', $userMail)->delete();
        }else{
            Doccontroller::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Issuereviewer::where('email', $new_responsibility_user->email)->exists()){
            Issuereviewer::where('email', $userMail)->delete();
        }else{
            Issuereviewer::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Internalaudit::where('email', $new_responsibility_user->email)->exists()){
            Internalaudit::where('email', $userMail)->delete();
        }else{
            Internalaudit::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Archiveprivilege::where('email', $new_responsibility_user->email)->exists()){
            Archiveprivilege::where('email', $userMail)->delete();
        }else{
            Archiveprivilege::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(IncidentReviewer::where('email', $new_responsibility_user->email)->exists()){
            IncidentReviewer::where('email', $userMail)->delete();
        }else{
            IncidentReviewer::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(IncidentApprover::where('email', $new_responsibility_user->email)->exists()){
            IncidentApprover::where('email', $userMail)->delete();
        }else{
            IncidentApprover::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Calibrationreviewer::where('email', $new_responsibility_user->email)->exists()){
            Calibrationreviewer::where('email', $userMail)->delete();
        }else{
            Calibrationreviewer::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Near::where('email', $new_responsibility_user->email)->exists()){
            Near::where('email', $userMail)->delete();
        }else{
            Near::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Complaintreviewer::where('email', $new_responsibility_user->email)->exists()){
            Complaintreviewer::where('email', $userMail)->delete();
        }else{
            Complaintreviewer::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Complaintregister::where('email', $new_responsibility_user->email)->exists()){
            Complaintregister::where('email', $userMail)->delete();
        }else{
            Complaintregister::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Changerequestreviewer::where('email', $new_responsibility_user->email)->exists()){
            Changerequestreviewer::where('email', $userMail)->delete();
        }else{
            Changerequestreviewer::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Changerequestapprove::where('email', $new_responsibility_user->email)->exists()){
            Changerequestapprove::where('email', $userMail)->delete();
        }else{
            Changerequestapprove::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Wastereviewer::where('email', $new_responsibility_user->email)->exists()){
            Wastereviewer::where('email', $userMail)->delete();
        }else{
            Wastereviewer::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Wasteapprover::where('email', $new_responsibility_user->email)->exists()){
            Wasteapprover::where('email', $userMail)->delete();
        }else{
            Wasteapprover::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Wastedisposalsalesauthority::where('email', $new_responsibility_user->email)->exists()){
            Wastedisposalsalesauthority::where('email', $userMail)->delete();
        }else{
            Wastedisposalsalesauthority::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Envireviewer::where('email', $new_responsibility_user->email)->exists()){
            Envireviewer::where('email', $userMail)->delete();
        }else{
            Envireviewer::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Trainingcoordinators::where('email', $new_responsibility_user->email)->exists()){
            Trainingcoordinators::where('email', $userMail)->delete();
        }else{
            Trainingcoordinators::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Mainreviewer::where('email', $new_responsibility_user->email)->exists()){
            Mainreviewer::where('email', $userMail)->delete();
        }else{
            Mainreviewer::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Mainelectrical::where('email', $new_responsibility_user->email)->exists()){
            Mainelectrical::where('email', $userMail)->delete();
        }else{
            Mainelectrical::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Maininstrument::where('email', $new_responsibility_user->email)->exists()){
            Maininstrument::where('email', $userMail)->delete();
        }else{
            Maininstrument::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(CompanyDoctor::where('email', $new_responsibility_user->email)->exists()){
            CompanyDoctor::where('email', $userMail)->delete();
        }else{
            CompanyDoctor::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        if(Supplierapprovers::where('email', $new_responsibility_user->email)->exists()){
            Supplierapprovers::where('email', $userMail)->delete();
        }else{
            Supplierapprovers::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'name' => $new_responsibility_user->name,
            ]);
        }
        $old_userId = User::where('email', $userMail)->value('id');
        $auth_user = User::where('email',Auth::user()->email)->value('id');
        $user_gates_keys = [
            'review-vendor','approve-vendor','elearning-add-content','enviaspect-secondary-review',
            'hse-observation-reviewer','management-review-meeting-controls','ohsrisk-secondary-review',
            'objective-reviewer','objective-approver'
        ];
        $gates_old_user = UserGate::whereIn('key',$user_gates_keys)->where('users_id',$old_userId)->get();
        $gates_new_user_keys = UserGate::whereIn('key', $user_gates_keys)->where('users_id', $new_responsibility_user->id)
                ->pluck('key')->toArray();
        foreach($gates_old_user as $gou){
            if (!(in_array($gou->key, $gates_new_user_keys))) {
                // update the old user's gate to the new user
                $gou->update([
                    'users_id' => $new_responsibility_user->id,
                    'saver_users_id' => $auth_user,
                    'updated_at' => now()
                ]);
            }
        }
        UserGate::where('users_id',$old_userId)->delete();
    }
    public function updateMultipleResponsibilities($userMail, $userId, $new_responsibility_user, $incident_report_no)
    {
        $userName = User::where('id', $userId)->value('name');

        Compliance::where('responsibility', $userMail)->update([
            'responsibility' => $new_responsibility_user->email,
        ]);

        Ohs::join('ohs_riskowners', 'ohs.id', '=', 'ohs_riskowners.ohs_id')->where('ohs.risk_status', 'Reviewed')->where('ohs_riskowners.user_id', $userId)->update([
            'ohs_riskowners.user_id' => $new_responsibility_user->id,
        ]);

        DB::table('complaint')->where('responsibility', $userMail)->update([
            'responsibility' => $new_responsibility_user->email,
        ]);

        Risk::where('risk_owner', $userMail)->update([
            'risk_owner' => $new_responsibility_user->email,
        ]);

        Mitigation::where('owner', $userId)->update([
            'owner' => $new_responsibility_user->id,
        ]);

        Division::where('owner_id', $userId)->update([
            'owner_id' => $new_responsibility_user->id,
        ]);

        Area::where('owner_id', $userId)->update([
            'owner_id' => $new_responsibility_user->id,
        ]);

        Objective::where('responsibility', $userMail)->orWhere('responsibility', $userName)->update([
            'responsibility' => $new_responsibility_user->email,
        ]);

        Opportunity::where('responsibility', $userMail)->update([
            'responsibility' => $new_responsibility_user->email,
        ]);

        HseIssue::where('responsibility', $userMail)->update([
            'responsibility' => $new_responsibility_user->email,
        ]);

        Corrective::where('team_lead_id', $userId)->update([
            'team_lead_id' => $new_responsibility_user->id,
        ]);

        Dcr::where('hod', $userMail)->update([
            'hod' => $new_responsibility_user->email,
        ]);

        Masterdoc::where('hod', $userMail)->update([
            'hod' => $new_responsibility_user->email,
        ]);

        GovernanceHubDraft::where('approvers', $userMail)->update([
            'approvers' => $new_responsibility_user->email,
        ]);

        Issue::where('responsibility', $userMail)->update([
            'responsibility' => $new_responsibility_user->email,
        ]);

        Investigator::where('tleader', $userMail)->where('report_no', $incident_report_no)->update([
            'tleader' => $new_responsibility_user->email,
        ]);

        Nearmiss::where('responsibility', $userMail)->update([
            'responsibility' => $new_responsibility_user->email,
        ]);

        $correctives = Corrective::where('responsibility', 'like', '%' . $userMail . '%')->get();

        foreach ($correctives as $c) {
            $responsibility = str_replace($userMail, $new_responsibility_user->email, $c->responsibility);
            $c->responsibility = $responsibility;
            $c->save();
        }

        $corrective_correction_responsibilities = Corrective::where('correction_responsibility', 'like', '%' . $userMail . '%')->get();

        foreach ($corrective_correction_responsibilities as $c) {
            $correction_responsibility = str_replace($userMail, $new_responsibility_user->email, $c->correction_responsibility);
            $c->correction_responsibility = $correction_responsibility;
            $c->save();
        }

        $goverance_hub_draft_reviewers = GovernanceHubDraft::where('reviewer', 'like', '%' . $userMail . '%')->get();

        foreach ($goverance_hub_draft_reviewers as $item) {
            $reviewer = str_replace($userMail, $new_responsibility_user->email, $item->reviewer);
            $item->reviewer = $reviewer;
            $item->save();
        }

        $audits = Audit::where('contact', 'like', '%' . $userName . '%')->get();

        foreach ($audits as $au) {
            $contact = str_replace($userName, $new_responsibility_user->name, $au->contact);
            $au->contact = $contact;
            $au->save();
        }

    }

    public function updateModalResponsibilities($userMail, $new_responsibility_user)
    {
        /*** Department ***/

        Departments::where('hod', $userMail)->update([
            'hod' => $new_responsibility_user->email,
            'hodname' => $new_responsibility_user->name,
        ]);

        /*** Auditor ***/

        $new_user_exists = Auditor::where('email',$new_responsibility_user->email)->first();
        if($new_user_exists){
            Auditor::where('email',$userMail)->delete();
        }else{
            Auditor::where('email', $userMail)->update([
                'email' => $new_responsibility_user->email,
                'auditorsname' => $new_responsibility_user->name,
            ]);
        }

    }
    public function updateResp($userMail, $model, $compared_item, $updatedValue)
    {
        if ($updatedValue) {
            $model::where($compared_item, $userMail)->update([
                $compared_item => $updatedValue,
            ]);
        }

    }
    public function updateCommaSeperatedPrivilege($updatedValue, $model, $userMail, $comparedValue)
    {
        if ($updatedValue) {
            $items = $model::where($comparedValue, 'like', '%' . $userMail . '%')->get();
            foreach ($items as $item) {
                $responsibility = str_replace($userMail, $updatedValue, $item->$comparedValue);
                $item->$comparedValue = $responsibility;
                $item->save();
            }
        }

    }
    public function responsibleGates($item, $admin_found, $key, $userId)
    {
        if ($item) {
            // $admin_found ? UserGate::where('key', $key)->where('users_id', $admin_found->id)->delete() : UserGate::where('key', $key)->where('users_id', $userId)->delete();
            $item = explode(',',$item);
            $new_user = $item[0];
            $check = UserGate::where('key',$key)->where('users_id',$new_user)->exists();
            $auth_user= User::where('email',Auth::user()->email)->value('id');
            if(!$check){
                UserGate::where('key',$key)
                    ->where('users_id', $userId)
                    ->update([
                        'users_id' => $new_user,
                        'saver_users_id' => $auth_user,
                        'updated_at' => now()
                    ]);
            }
            UserGate::where('key', $key)->where('users_id', $userId)->delete();
        }

    }
    public function postResponsibilities(PostResponsibilitiesRequest $request)
    {
        $new_responsibility = $request->input('new_responsibility');
        $representative = $request->input('representative');
        $elearning_add_content = $request->input('elearning_add_content');
        $hse_observation_reviewer = $request->input('hse_observation_reviewer');
        $objective_reviewer = $request->input('objective_reviewer');
        $objective_approver = $request->input('objective_approver');
        $envi_secondary_reviewer = $request->input('envi_secondary_reviewer');
        $management_reviewer = $request->input('management_reviewer');
        $ohs_secondary_review = $request->input("ohs_secondary_review"); //1051,1012
        $ohsrisk_owner = $request->input('ohsrisk_owner');
        $trainingcoordinator = $request->input('trainingcoordinator');
        $supplierapprover = $request->input('supplierapprover');
        $vendor_reviewer = $request->input('vendor_reviewer');
        $vendor_approver = $request->input('vendor_approver');
        $mainreviewer = $request->input('mainreviewer');
        $mainelectrical = $request->input('mainelectrical');
        $maininstrument = $request->input('maininstrument');
        $company_doctor = $request->input('company_doctor');
        $waste_reviewer = $request->input('waste_reviewer');
        $waste_approver = $request->input('waste_approver');
        $envi_reviewer = $request->input('envireviewer');
        $waste_disposal_sales_authorities = $request->input('waste_disposal_sales_authorities');
        $context_reviewer = $request->input('contextreviewer');
        $context_approver = $request->input('contextapprover');
        $near = $request->input('near'); //4,8
        $doccontroller = $request->input('doccontroller');
        $doc_approver = $request->input('doc_approver');
        $doc_reviewer = $request->input('doc_reviewer');
        $masterdoc = $request->input('masterdoc');
        $legal_coordinator = $request->input('legalCoordinator');
        $calibration_reviewer = $request->input('calibration_reviewer');
        $escalateriskreceiver = $request->input('escalateriskreceiver');
        $hses = $request->input('hses');
        $incident_reviewer = $request->input('incident_reviewer');
        $incident_approver = $request->input('incident_approver');
        $incident_leader = $request->input('incident_leader_select');
        $incident_report_no = $request->input('incident_report_no');
        $management = $request->input('management');
        $closurereviewer = $request->input('closurereviewer');
        $riskreviewer = $request->input('riskreviewer');
        $corrective_resp = $request->input('corrective_responsibility');
        $corrective_correction_resp = $request->input('corrective_correction_responsibility');
        $corrective_lead = $request->input('corrective_lead');
        $compliance_responsibility = $request->input('compliance_responsibility');
        $complaint_reviewer = $request->input('complaintreviewer');
        $complaint_register = $request->input('complaintregister');
        $complaint_responsibilities = $request->input('complaint_responsibilities');
        $auditee = $request->input('auditee');
        $division = $request->input('division');
        $subdivision = $request->input('subdivision');
        $objective_responsibilities = $request->input('objective_resp');
        $archiveprivilege = $request->input('archiveprivilege');
        $internalaudit = $request->input('internalaudit');
        $nearmiss = $request->input('nearmiss');
        $dcr = $request->input('dcr');
        $risks = $request->input('risks');
        $mitigations = $request->input('mitigations');
        $hseissue = $request->input('hseissue');
        $opportunity = $request->input('opportunity');
        $Issuereviewer = $request->input('IssueReviewer');
        $issues = $request->input('issues');
        $userId = $request->input('userId');
        $racmreviewer = $request->input('racmreviewer');
        $racmapprover = $request->input('racmapprover');
        $userMail = User::where('id', $userId)->value('email');
        $userName = User::where('id', $userId)->value('name');
        $models = DB::table('modenadisable')->first();
        if ($new_responsibility) {
            $new_responsibility_user = User::where('id', $new_responsibility)->first();
            $this->updateThePrivilege($userMail, $new_responsibility_user);
            $this->updateMultipleResponsibilities($userMail, $userId, $new_responsibility_user, $incident_report_no);
            $this->updateModalResponsibilities($userMail, $new_responsibility_user);
            $this->updateResp($userId,'App\RiskRacm','approver_id',$new_responsibility);
            $this->updateResp($userId,'App\RiskRacm','approved_by',$new_responsibility);
            $this->updateResp($userId,'App\RacmReviewerDetail','reviewer_id',$new_responsibility);
        } else {
            if ($incident_leader) {
                Investigator::where('tleader', $userMail)->where('report_no', $incident_report_no)->update([
                    'tleader' => $incident_leader,
                ]);
            }
            $this->updateResp($userMail, 'App\Nearmiss', 'responsibility', $nearmiss);
            if ($complaint_responsibilities) {
                DB::table('complaint')->where('responsibility', $userMail)->update([
                    'responsibility' => $complaint_responsibilities,
                ]);
            }

            if ($ohsrisk_owner) {
                $ohsrisk_owner_array = explode(',', $ohsrisk_owner);
                Ohs::join('ohs_riskowners', 'ohs.id', '=', 'ohs_riskowners.ohs_id')->where('ohs.risk_status', 'Reviewed')->where('ohs_riskowners.user_id', $userId)->update([
                    'ohs_riskowners.user_id' => $ohsrisk_owner_array[0],
                ]);

            }
            if($racmapprover){
                $racmid = explode(',',$racmapprover)[0];
                $this->updateResp($userId,'App\RiskRacm','approver_id',$racmid);
                $this->updateResp($userId,'App\RiskRacm','approved_by',$racmid);
            }
            if($racmreviewer){
                $racmid = explode(',',$racmreviewer)[0];
                $this->updateResp($userId,'App\RacmReviewerDetail','reviewer_id',$racmid);
            }
            $this->updateResp($userMail, 'App\Issue', 'responsibility', $issues);
            $this->updateResp($userMail, 'App\Opportunity', 'responsibility', $opportunity);
            $this->updateResp($userMail, 'App\HseIssue', 'responsibility', $hseissue);
            $this->updateResp($userMail, 'App\Compliance', 'responsibility', $compliance_responsibility);
            $this->updateResp($userId, 'App\Division', 'owner_id', $division);
            $this->updateResp($userId, 'App\Area', 'owner_id', $subdivision);
            $this->updateResp($userMail, 'App\Risk', 'risk_owner', $risks);
            $this->updateResp($userId, 'App\Mitigation', 'owner', $mitigations);
            $this->updateResp($userMail, 'App\Dcr', 'hod', $dcr);
            $this->updateResp($userMail, 'App\Masterdoc', 'hod', $masterdoc);
            $this->updateResp($userMail, 'App\GovernanceHubDraft', 'approvers', $doc_approver);
            $this->updateResp($userId, 'App\Corrective', 'team_lead_id', $corrective_lead);
            if ($objective_responsibilities) {
                Objective::where('responsibility', $userMail)->orWhere('responsibility', $userName)->update([
                    'responsibility' => $objective_responsibilities,
                ]);
            }

            $this->updateCommaSeperatedPrivilege($auditee, 'App\Audit', $userName, 'contact');
            $this->updateCommaSeperatedPrivilege($corrective_resp, 'App\Corrective', $userMail, 'responsibility');
            $this->updateCommaSeperatedPrivilege($corrective_correction_resp, 'App\Corrective', $userMail, 'correction_responsibility');
            $this->updateCommaSeperatedPrivilege($doc_reviewer, 'App\GovernanceHubDraft', $userMail, 'reviewer');
            $this->updateResponsibilty($near, 'App\Near', $userMail);
            $this->updateResponsibilty($calibration_reviewer, 'App\Calibrationreviewer', $userMail);
            $this->updateResponsibilty($doccontroller, 'App\Doccontroller', $userMail);
            $this->updateResponsibilty($legal_coordinator, 'App\Legalcoordinator', $userMail);
            $this->updateResponsibilty($escalateriskreceiver, 'App\Escalateriskreceiver', $userMail);
            $this->updateResponsibilty($hses, 'App\Hse', $userMail);
            $this->updateResponsibilty($incident_reviewer, 'App\IncidentReviewer', $userMail);
            $this->updateResponsibilty($incident_approver, 'App\IncidentApprover', $userMail);
            $this->updateResponsibilty($representative, 'App\Representative', $userMail);
            $this->updateResponsibilty($Issuereviewer, 'App\Issuereviewer', $userMail);
            $this->updateResponsibilty($internalaudit, 'App\Internalaudit', $userMail);
            $this->updateResponsibilty($archiveprivilege, 'App\Archiveprivilege', $userMail);
            $this->updateResponsibilty($riskreviewer, 'App\Riskreviewer', $userMail);
            $this->updateResponsibilty($closurereviewer, 'App\Closurereviewer', $userMail);
            $this->updateResponsibilty($management, 'App\Managements', $userMail);
            $this->updateResponsibilty($complaint_reviewer, 'App\Complaintreviewer', $userMail);
            $this->updateResponsibilty($complaint_register, 'App\Complaintregister', $userMail);
            $this->updateResponsibilty($context_reviewer, 'App\Changerequestreviewer', $userMail);
            $this->updateResponsibilty($context_approver, 'App\Changerequestapprove', $userMail);
            $this->updateResponsibilty($waste_reviewer, 'App\Wastereviewer', $userMail);
            $this->updateResponsibilty($waste_approver, 'App\Wasteapprover', $userMail);
            $this->updateResponsibilty($waste_disposal_sales_authorities, 'App\Wastedisposalsalesauthority', $userMail);
            $this->updateResponsibilty($envi_reviewer, 'App\Envireviewer', $userMail);
            $this->updateResponsibilty($trainingcoordinator, 'App\Trainingcoordinators', $userMail);
            $this->updateResponsibilty($mainreviewer, 'App\Mainreviewer', $userMail);
            $this->updateResponsibilty($mainelectrical, 'App\Mainelectrical', $userMail);
            $this->updateResponsibilty($maininstrument, 'App\Maininstrument', $userMail);
            $this->updateResponsibilty($company_doctor, 'App\CompanyDoctor', $userMail);
            $this->updateResponsibilty($supplierapprover, 'App\Supplierapprovers', $userMail);

            $admin_found = Admin::where('email', $userMail)->first();
            $this->responsibleGates($vendor_reviewer, $admin_found, 'review-vendor', $userId);
            $this->responsibleGates($vendor_approver, $admin_found, 'approve-vendor', $userId);
            $this->responsibleGates($elearning_add_content, $admin_found, 'elearning-add-content', $userId);
            $this->responsibleGates($envi_secondary_reviewer, $admin_found, 'enviaspect-secondary-review', $userId);
            $this->responsibleGates($hse_observation_reviewer, $admin_found, 'hse-observation-reviewer', $userId);
            $this->responsibleGates($objective_reviewer, $admin_found, 'objective-reviewer', $userId);
            $this->responsibleGates($objective_approver, $admin_found, 'objective-approver', $userId);
            $this->responsibleGates($management_reviewer, $admin_found, 'management-review-meeting-controls', $userId);
            $this->responsibleGates($ohs_secondary_review, $admin_found, 'ohsrisk-secondary-review', $userId);

            // if ($vendor_reviewer) {
            //     $admin_found ? UserGate::where('key', 'review-vendor')->where('users_id', $admin_found->id)->delete() : UserGate::where('key', 'review-vendor')->where('users_id', $userId)->delete();
            // }

            // if ($vendor_approver) {
            //     $admin_found ? UserGate::where('key', 'approve-vendor')->where('users_id', $admin_found->id)->delete() : UserGate::where('key', 'approve-vendor')->where('users_id', $userId)->delete();
            // }

            // if ($elearning_add_content) {
            //     $admin_found ? UserGate::where('key', 'elearning-add-content')->where('users_id', $admin_found->id)->delete() : UserGate::where('key', 'elearning-add-content')->where('users_id', $userId)->delete();
            // }

        }

        return $this->removeUser($userId)->with('model', $models);

    }
    public function viewUsers()
    {
        $models = DB::table('modenadisable')->First();
        // $users = DB::select(
        //     'select * from users where approved = 1 order by name ASC'
        // );
        $users = User::where('approved',true)->orderBy('name', 'ASC')->get();
        return view('admin.viewUsers_new')
            ->with('users', $users)
            ->with('model', $models);
    }
    public function displayeditUsers(Request $req)
    {
        //dd($req->all());
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $email = $_GET['value'];
            // $admin = Admin::where('email', $email)->first();
            // if ($admin) {
            //     $u = $admin;
            // } else {
            //     $u = User::find($id);
            // }
            $u = User::find($id);
            $department = Departments::all();
            return view('admin.displayeditUsers')->with([
                'u' => $u,
                'model' => $models,
                'department' => $department,
            ]);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function updatePrivilege($table, $id, $name, $email)
    {
        if (isset($id->id)) {
            $check = DB::table($table)->where('email',$email)->exists();
            if(!$check){
                DB::table($table)->where('id', $id->id)->update([
                    'name' => $name,
                    'email' => $email,
                ]);
            }

        }
    }
    public function updateUser(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');
        $email = $req->input('email');
        $empid = $req->input('empid');
        $id = $req->input('id');
        $dept = $req->input('dept');
        $department = Departments::find($dept);
        $u_email = $req->input('usermail');

        // finding current related row in employee_details table for validation
        $user = User::find($req->id);
        $employeeDetail = EmployeeDetails::where('emp_number', $user->employeeid)->first();
        //

        $validator = Validator::make($req->all(), [
            'email' => 'required|email|max:255|unique:users,email,' . $req->id,
            'empid' => 'required|string|max:255|unique:employee_details,emp_number,' . ($employeeDetail ? $employeeDetail->id : ''),
        ], [
            'empid.unique' => 'The entered Employee ID is already in use. Please enter a unique Employee ID.',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('model', $models);
        }

        $userid = DB::select('select * from users where email = :eemail', [
            'eemail' => $email,
        ]);
        $admin = Admin::where('email', $u_email)->first();
        $admin_name = User::where('email', $u_email)->value('name');
        if($admin) {
            Admin::where('email',$u_email)->update([
                'name' => $name,
                'email' => $email,
                'employeeid' => $empid,
                'deptid' => $department ? $department->departmentname : '',
                'updated_at' => now()
            ]);
        }

        $user = User::find($id);
        $employeeDetails = EmployeeDetails::where('emp_number', $user->employeeid)->first();

        User::where('id', $id)->update([
            'name' => $name,
            'email' => $email,
            'employeeid' => $empid,
            'deptid' => $department ? $department->departmentname : '',
            'updated_at' => now()
        ]);

        if ($employeeDetails) {
            $employeeDetails->emp_name = $name;
            $employeeDetails->emp_department = $department ? $department->id : '';
            $employeeDetails->emp_number = $empid;
            $employeeDetails->update();
        }

        $repid = DB::table('representatives')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $crevid = DB::table('complaintreviewers')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $cregid = DB::table('complaintregisters')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $notid = DB::table('notifiers')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $rrid = DB::table('riskreviewers')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $errid = DB::table('escalateriskreceivers')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $aid = DB::table('archiveprivileges')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $cloid = DB::table('closurereviewers')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $irid = DB::table('issuereviewers')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $lcid = DB::table('legalcoordinators')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $hid = DB::table('hses')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $mid = DB::table('managements')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $erid = DB::table('envireviewers')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $dcid = DB::table('doccontrollers')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $nrid = DB::table('nears')
            ->select('id')
            ->where('email', $u_email)
            ->first();
        $cbrid = Calibrationreviewer::select('id')->where('email',$u_email)->first();
        $incident_reviewer = IncidentReviewer::select('id')->where('email',$u_email)->first();
        $incident_approver = IncidentApprover::select('id')->where('email',$u_email)->first();
        $internalaudit = Internalaudit::select('id')->where('email',$u_email)->first();
        $context_reviewer = Changerequestreviewer::select('id')->where('email',$u_email)->first();
        $context_approver = Changerequestapprove::select('id')->where('email',$u_email)->first();
        $waste_reviewer = Wastereviewer::select('id')->where('email',$u_email)->first();
        $waste_approver= Wasteapprover::select('id')->where('email',$u_email)->first();
        $waste_disposal_sales_authorities = Wastedisposalsalesauthority::select('id')->where('email',$u_email)->first();
        $trainingcoordinator = Trainingcoordinators::select('id')->where('email',$u_email)->first();
        $mainreviewer = Mainreviewer::select('id')->where('email',$u_email)->first();
        $mainelectrical = Mainelectrical::select('id')->where('email',$u_email)->first();
        $maininstrument = Maininstrument::select('id')->where('email',$u_email)->first();
        $company_doctor = CompanyDoctor::select('id')->where('email',$u_email)->first();
        $supplierapprover = Supplierapprovers::select('id')->where('email',$u_email)->first();
        $this->updatePrivilege('calibrationreviewers', $cbrid, $name, $email);
        $this->updatePrivilege('incident_reviewer', $incident_reviewer, $name, $email);
        $this->updatePrivilege('incident_approvers', $incident_approver, $name, $email);
        $this->updatePrivilege('internalaudits', $internalaudit, $name, $email);
        $this->updatePrivilege('changerequestreviewers', $context_reviewer, $name, $email);
        $this->updatePrivilege('changerequestapproves', $context_approver, $name, $email);
        $this->updatePrivilege('wastereviewers', $waste_reviewer, $name, $email);
        $this->updatePrivilege('wasteapprovers', $waste_approver, $name, $email);
        $this->updatePrivilege('wastedisposalsalesauthorities', $waste_disposal_sales_authorities, $name, $email);
        $this->updatePrivilege('trainingcoordinators', $trainingcoordinator, $name, $email);
        $this->updatePrivilege('mainreviewers', $mainreviewer, $name, $email);
        $this->updatePrivilege('mainelectricals', $mainelectrical, $name, $email);
        $this->updatePrivilege('maininstruments', $maininstrument, $name, $email);
        $this->updatePrivilege('company_doctors', $company_doctor, $name, $email);
        $this->updatePrivilege('supplierapprovers', $supplierapprover, $name, $email);
        $this->updatePrivilege('representatives', $repid, $name, $email); //
        $this->updatePrivilege('complaintreviewers', $crevid, $name, $email);
        $this->updatePrivilege('complaintregisters', $cregid, $name, $email); //
        $this->updatePrivilege('notifiers', $notid, $name, $email);
        $this->updatePrivilege('riskreviewers', $rrid, $name, $email); //
        $this->updatePrivilege('escalateriskreceivers', $errid, $name, $email);
        $this->updatePrivilege('archiveprivileges', $aid, $name, $email);
        $this->updatePrivilege('closurereviewers', $cloid, $name, $email);
        $this->updatePrivilege('issuereviewers', $irid, $name, $email); //
        $this->updatePrivilege('legalcoordinators', $lcid, $name, $email); //
        $this->updatePrivilege('hses', $hid, $name, $email);
        $this->updatePrivilege('managements', $mid, $name, $email); //
        $this->updatePrivilege('envireviewers', $erid, $name, $email);
        $this->updatePrivilege('doccontrollers', $dcid, $name, $email); //
        $this->updatePrivilege('nears', $nrid, $name, $email);

        foreach ($userid as $row) {
            $user1id = (string) $row->id;
            if ($user1id == $id || $userid == null) {
                //inserting data to activities table
                // $oldname = DB::table('users')
                //     ->where('id', $id)
                //     ->pluck('name');
                $oldname = $admin_name;
                //$oldname = Admin::where('id', $id)->pluck('name');
                $activity =
                    $oldname .
                    ' User is Updated with Name ' .
                    $name .
                    ' and Email ' .
                    $email;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return redirect('/admin/viewUsers')->with([
                    'model' => $models,
                    'message' => 'User information updated successfully!'
                ]);
            } else {
                return redirect('/admin/viewUsers')->with([
                    'model' => $models,
                    'message' => 'User information updated successfully!'
                ]);
            }
        }
        if ($userid == null) {
            // dd($id);
            // $oldname = DB::table('users')
            //     ->where('id', $id)
            //     ->pluck('name');
            //$oldname = Admin::where('id', $id)->pluck('name');
            $oldname = $admin_name;
            $activity =
                $oldname .
                ' User is Updated with Name ' .
                $name .
                ' and Email ' .
                $email;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return redirect('/admin/viewUsers')->with([
                'model' => $models,
                'message' => 'User information updated successfully!'
            ]);
        }
        // }
    }
    public function CarryUserID(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            return $this->removeUser($id)->with('model', $models);
        }
    }
    public function removeUser($id, $show_all_modules = true)
    {
        $users = User::all();
        $models = DB::table('modenadisable')->First();
        $name = DB::table('users')
            ->where('id', $id)
            ->pluck('name');
        $email = DB::table('users')
            ->where('id', $id)
            ->pluck('email');
        $activity =
            'User is Deleted with Name ' . $name . ' and Email ' . $email;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        Viewactivity::insert($datas);
        //remove it also from admins and other admin tables if he is an admin
        $user = User::findOrFail($id);

        $admin = Admin::where('email', $user->email)->first();
        if ($admin) {
            Admin::where('email', $user->email)->delete();
        }
        DB::table('representatives')->where('email', $user->email)->delete();
        DB::table('complaintreviewers')->where('email', $user->email)->delete();
        DB::table('complaintregisters')->where('email', $user->email)->delete();
        DB::table('notifiers')->where('email', $user->email)->delete();
        DB::table('riskreviewers')->where('email', $user->email)->delete();
        DB::table('escalateriskreceivers')->where('email', $user->email)->delete();
        DB::table('archiveprivileges')->where('email', $user->email)->delete();
        DB::table('closurereviewers')->where('email', $user->email)->delete();
        DB::table('issuereviewers')->where('email', $user->email)->delete();
        DB::table('legalcoordinators')->where('email', $user->email)->delete();
        DB::table('hses')->where('email', $user->email)->delete();
        DB::table('managements')->where('email', $user->email)->delete();
        DB::table('envireviewers')->where('email', $user->email)->delete();
        DB::table('doccontrollers')->where('email', $user->email)->delete();
        DB::table('nears')->where('email', $user->email)->delete();
        User::where('id', $id)->delete();
        UserGate::where('users_id',$id)->delete();
        $message = !$show_all_modules
        ? 'The user has been successfully deleted.'
        : 'The user has been successfully deleted and the responsibilties were replaced.';
        $message = $admin ? 'The admin and their associated user record have been successfully deleted.' : $message;
        Session::flash('message', $message);

        return redirect('/admin/viewUsers')
            ->with(
                'model',
                $models
            )
            ->with('users', $users);
    }
    public function manageRoles(Request $req)
    {
        $model = DB::table('modenadisable')->First();

        if (isset($_GET['val']) && isset($_GET['value'])) {
            $id = $_GET['val'];
            $email = $_GET['value'];
            $users = User::where('id', $id)->where('email',$email)->get();

            // $admin = Admin::where('id', $id)->first();
            // $admin = Admin::where('email', $pass_email)->first();

            // if ($admin) {
            //     $users = Admin::with('user')->where('id', '=', $id)->get();
            //     $email = $admin->email;
            //     $isAdmin = true;
            // } else {
            //     $users = User::where('id', '=', $id)->get();
            //     $email = User::where('id', $id)->value('email');
            //     $isAdmin = false;
            // }
            $Complaintregister = Complaintregister::where(
                'email',
                '=',
                $email
            )->get();
            $Complaintreviewer = Complaintreviewer::where(
                'email',
                '=',
                $email
            )->get();
            $Notifier = Notifier::where('email', '=', $email)->get();
            $Representative = Representative::where(
                'email',
                '=',
                $email
            )->get();
            $Riskreviewer = Riskreviewer::where('email', '=', $email)->get();
            $Mainreviewer = Mainreviewer::where('email', '=', $email)->get();
            $Mainelectrical = Mainelectrical::where(
                'email',
                '=',
                $email
            )->get();
            $Maininstrument = Maininstrument::where(
                'email',
                '=',
                $email
            )->get();
            $Calibrationreviewer = Calibrationreviewer::where(
                'email',
                '=',
                $email
            )->get();
            $Issuereviewer = Issuereviewer::where('email', '=', $email)->get();
            $Legalcoordinator = Legalcoordinator::where(
                'email',
                '=',
                $email
            )->get();
            $Health_Safety = Hse::where('email', '=', $email)->get();
            $Escalateriskreceiver = Escalateriskreceiver::where(
                'email',
                '=',
                $email
            )->get();
            $archiveprivilege = Archiveprivilege::where(
                'email',
                '=',
                $email
            )->get();
            $Opportunityapprover = Opportunityapprover::where('email', $email)->get();
            $Closurereviewer = closurereviewer::where(
                'email',
                '=',
                $email
            )->get();

            $envireviewer = Envireviewer::where('email', '=', $email)->get();
            $Doccontroller = Doccontroller::where('email', '=', $email)->get();
            $Internalaudit = Internalaudit::where('email', '=', $email)->get();
            $supplierapprovers = Supplierapprovers::where(
                'email',
                '=',
                $email
            )->get();
            $trainingcoordinators = Trainingcoordinators::where(
                'email',
                '=',
                $email
            )->get();
            $wastereviewer = Wastereviewer::where('email', '=', $email)->get();
            $wasteapprover = Wasteapprover::where('email', '=', $email)->get();
            $wastetrfauthty = Wastedisposalsalesauthority::where(
                'email',
                '=',
                $email
            )->get();
            $Incidentreviewers = DB::table('incident_reviewer')
                ->where('email', '=', $email)
                ->get();
            $incidentapprover = DB::table('incident_approvers')
                ->where('email', '=', $email)
                ->get();
            $compdoctors = DB::table('company_doctors')
                ->where('email', '=', $email)
                ->get();
            $hse = UserGate::where('users_id', $id)
                ->where('key', 'hse-observation-reviewer')
                ->first();
            $objreviewer = UserGate::where('users_id', $id)
                ->where('key', 'objective-reviewer')
                ->first();
            $objapprover = UserGate::where('users_id', $id)
                ->where('key', 'objective-approver')
                ->first();
            $nearmiss = DB::table('nears')
                ->where('email', '=', $email)
                ->get();
            $nearmiss_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-nearmiss')
                ->first();
            $nearmiss_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-nearmiss')
                ->first();
            $maintenance_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-maintenance')
                ->first();
            $maintenance_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-maintenance')
                ->first();
            $doctor_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-doctor')
                ->first();
            $doctor_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-doctor')
                ->first();
            $cal_add = UserGate::where('users_id', $id)
                ->where('key', 'add-calibration')
                ->first();
            $cal_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-calibration')
                ->first();
            $cal_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-calibration')
                ->first();
            $waste_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-waste')
                ->first();
            $waste_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-waste')
                ->first();
            $waste_tr_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-waste-transferrequest')
                ->first();
            $waste_tr_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-waste-transferrequest')
                ->first();
            $vendor_review = UserGate::where('users_id', $id)
                ->where('key', 'review-vendor')
                ->first();
            $vendor_approve = UserGate::where('users_id', $id)
                ->where('key', 'approve-vendor')
                ->first();

            $vendor_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-vendor')
                ->first();
            $vendor_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-vendor')
                ->first();
            $hr_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-hr')
                ->first();
            $hr_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-hr')
                ->first();
            $incident_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-incident')
                ->first();
            $incident_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-incident')
                ->first();
            $incident_change_rev_approver = UserGate::where('users_id', $id)
                ->where('key', 'change-incident-reviewer-approver')
                ->first();
            $audit_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-audit')
                ->first();
            $audit_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-audit')
                ->first();
            $doc_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-doc')
                ->first();
            $doc_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-doc')
                ->first();
            $health_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-health')
                ->first();
            $health_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-health')
                ->first();
            $ohs_secondary_review = UserGate::where('users_id', $id)
                ->where('key', 'ohsrisk-secondary-review')
                ->first();
            $corrective_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-corrective')
                ->first();
            $corrective_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-corrective')
                ->first();
            $hse_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-hse')
                ->first();
            $hse_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-hse')
                ->first();
            $opportunity_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-opportunity')
                ->first();
            $opportunity_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-opportunity')
                ->first();
            $enviaspect_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-enviaspect')
                ->first();
            $enviaspect_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-enviaspect')
                ->first();
            $enviaspect_secondary_review = UserGate::where('users_id', $id)
                ->where('key', 'enviaspect-secondary-review')
                ->first();
            $objective_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-objective')
                ->first();
            $objective_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-objective')
                ->first();
            $objective_change_resp_person = UserGate::where('users_id', $id)
                ->where('key', 'objective-change-responsible-person')
                ->first();
            $risk_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-risk')
                ->first();
            $risk_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-risk')
                ->first();
            $risk_changeowner = UserGate::where('users_id', $id)
                ->where('key', 'risk-change-riskowner')
                ->first();
            $risk_escalate_access = UserGate::where('users_id',$id)
                ->where('key','escalate-risk-button-access')
                ->first();
            $compliance_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-compliance')
                ->first();
            $compliance_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-compliance')
                ->first();
            // $compliance_evaluate_status = UserGate::where('users_id', $id)
            //     ->where('key', 'evaluate-compliance-status')
            //     ->first();
            // $compliance_change_resp = UserGate::where('users_id', $id)
            //     ->where('key', 'compliance-change-legal-responsibility')
            //     ->first();
            $complaint_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-complaint')
                ->first();
            $complaint_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-complaint')
                ->first();
            $all_edit = UserGate::where('users_id', $id)
                ->where('key', 'edit-all')
                ->first();
            $all_delete = UserGate::where('users_id', $id)
                ->where('key', 'delete-all')
                ->first();
            $audit_RC = UserGate::where('users_id', $id)
                ->where('key', 'audit-responsibility-change')
                ->first();
            $audit_checklist_edit = UserGate::where('users_id', $id)
                ->where('key', 'audit-checklist-edit')
                ->first();
            $audit_checklist_delete = UserGate::where('users_id', $id)
                ->where('key', 'audit-checklist-delete')
                ->first();
            $audit_checklist_records_edit = UserGate::where('users_id', $id)
                ->where('key', 'audit-checklist-records-edit')
                ->first();
            $audit_checklist_records_approve = UserGate::where('users_id', $id)
                ->where('key', 'audit-checklist-records-approve')
                ->first();
            $corrective_RC = UserGate::where('users_id', $id)
                ->where('key', 'corrective-responsibility-change')
                ->first();
            $complaint_RC = UserGate::where('users_id', $id)
                ->where('key', 'complaint-responsibility-change')
                ->first();
            $hse_RC = UserGate::where('users_id', $id)
                ->where('key', 'hse-responsibility-change')
                ->first();
            $opportunity_RC = UserGate::where('users_id', $id)
                ->where('key', 'opportunity-responsibility-change')
                ->first();
            $nearmiss_RC = UserGate::where('users_id', $id)
                ->where('key', 'nearmiss-responsibility-change')
                ->first();
            $management_review_meeting = UserGate::where('users_id', $id)
                ->where('key', 'management-review-meeting-controls')
                ->first();
            $management_review_meeting_delete = UserGate::where('users_id', $id)
                ->where('key', 'management-review-meeting-delete')
                ->first();
            $elearning_add_content = UserGate::where('users_id', $id)
                ->where('key', 'elearning-add-content')
                ->first();
            $objective_change_rev_approver = UserGate::where('users_id', $id)
                ->where('key', 'change-objective-reviewer-approver')
                ->first();
            $modelArray = json_decode(json_encode($model), true);
            $availableModule = array_filter($modelArray, function ($value) {
                return $value == 1;
            });
            unset($availableModule['id']);
            unset($availableModule['auditschedulelogs']);
            $modulenames = array_merge(array_keys($availableModule), ['other']);
            $moduleNameValues = [
                'auditmanagement' => 'Audit Management',
                'calibration' => 'Calibration Management',
                'complaintmanagement' => 'Customer Complaint',
                'compliance' => 'Compliance Management',
                'contextmanagement' => 'Context Management',
                'customer_satisfaction' => 'Customer Satisfaction',
                'documentmanagement' => 'Document Management',
                'emergencyresponse' => 'Emergency Response',
                'enviaspects' => 'Environmental Aspect',
                'elearning' => 'e-Learning',
                'ohsrisk' => 'Health and Safety Risk',
                'hse' => 'HSE Observations',
                'hr' => 'Human Resource',
                'incidentreport' => 'Incident Report',
                'maintainance' => 'Maintenance Management',
                'managementreview' => 'Management Review',
                'correctiveaction' => 'NCR & Corrective Actions',
                'nearmiss' => 'Near Miss',
                'objectives' => 'Objectives',
                'occhealth' => 'Occupational Health',
                'opportunity' => 'Opportunity',
                'riskmanagement' => 'Enterprise Risk',
                'vendormanagement' => 'Vendor Management',
                'wastemanagement' => 'Waste Management',
                'other' => 'Other',
            ];
            foreach ($moduleNameValues as $key => $value) {
                if (!in_array($key, $modulenames)) {
                    unset($moduleNameValues[$key]);
                }
            }

            return view('admin.manageRoles',compact('risk_escalate_access'))->with([
                'users' => $users,
                // 'isAdmin' => $isAdmin,
                'nearmiss_edit' => $nearmiss_edit,
                'nearmiss_delete' => $nearmiss_delete,
                'maintenance_edit' => $maintenance_edit,
                'maintenance_delete' => $maintenance_delete,
                'complaint_edit' => $complaint_edit,
                'complaint_delete' => $complaint_delete,
                'audit_RC' => $audit_RC,
                'audit_checklist_edit' => $audit_checklist_edit,
                'audit_checklist_delete' => $audit_checklist_delete,
                'audit_checklist_records_edit' => $audit_checklist_records_edit,
                'audit_checklist_records_approve' => $audit_checklist_records_approve,
                'management_review_meeting' => $management_review_meeting,
                'management_review_meeting_delete' => $management_review_meeting_delete,
                'corrective_RC' => $corrective_RC,
                'complaint_RC' => $complaint_RC,
                'hse_RC' => $hse_RC,
                'opportunity_RC' => $opportunity_RC,
                'nearmiss_RC' => $nearmiss_RC,
                'all_edit' => $all_edit,
                'all_delete' => $all_delete,
                'risk_edit' => $risk_edit,
                'risk_delete' => $risk_delete,
                'risk_changeowner' => $risk_changeowner,
                'enviaspect_edit' => $enviaspect_edit,
                'enviaspect_delete' => $enviaspect_delete,
                'enviaspect_secondary_review' => $enviaspect_secondary_review,
                'elearning_add_content' => $elearning_add_content,
                'compliance_edit' => $compliance_edit,
                'compliance_delete' => $compliance_delete,
                //'compliance_evaluate_status' => $compliance_evaluate_status,
                //'compliance_change_resp' => $compliance_change_resp,
                'opportunity_edit' => $opportunity_edit,
                'opportunity_delete' => $opportunity_delete,
                'corrective_edit' => $corrective_edit,
                'corrective_delete' => $corrective_delete,
                'objective_edit' => $objective_edit,
                'objective_delete' => $objective_delete,
                'objective_change_resp_person' => $objective_change_resp_person,
                'objective_change_rev_approver' => $objective_change_rev_approver,
                'incident_edit' => $incident_edit,
                'incident_delete' => $incident_delete,
                'incident_change_rev_approver' => $incident_change_rev_approver,
                'health_edit' => $health_edit,
                'health_delete' => $health_delete,
                'ohs_secondary_review' => $ohs_secondary_review,
                'doc_edit' => $doc_edit,
                'doc_delete' => $doc_delete,
                'hse_edit' => $hse_edit,
                'hse_delete' => $hse_delete,
                'hr_edit' => $hr_edit,
                'hr_delete' => $hr_delete,
                'hse_edit' => $hse_edit,
                'hse_delete' => $hse_delete,
                'waste_edit' => $waste_edit,
                'waste_delete' => $waste_delete,
                'waste_tr_edit' => $waste_tr_edit,
                'waste_tr_delete' => $waste_tr_delete,
                'cal_add' => $cal_add,
                'cal_edit' => $cal_edit,
                'cal_delete' => $cal_delete,
                'doctor_edit' => $doctor_edit,
                'doctor_delete' => $doctor_delete,
                'vendor_review' => $vendor_review,
                'vendor_approve' => $vendor_approve,
                'vendor_edit' => $vendor_edit,
                'vendor_delete' => $vendor_delete,
                'audit_edit' => $audit_edit,
                'audit_delete' => $audit_delete,
                'Complaintregister' => $Complaintregister,
                'Complaintreviewer' => $Complaintreviewer,
                'Notifier' => $Notifier,
                'Representative' => $Representative,
                'Riskreviewer' => $Riskreviewer,
                'Mainreviewer' => $Mainreviewer,
                'Mainelectrical' => $Mainelectrical,
                'Maininstrument' => $Maininstrument,
                'Calibrationreviewer' => $Calibrationreviewer,
                'Archiveprivilege' => $archiveprivilege,
                'Escalateriskreceiver' => $Escalateriskreceiver,
                'Opportunityapprover' => $Opportunityapprover,
                'Closurereviewer' => $Closurereviewer,
                'Legalcoordinator' => $Legalcoordinator,
                'Issuereviewer' => $Issuereviewer,
                'objreviewer' => $objreviewer,
                'objapprover' => $objapprover,
                'envireviewer' => $envireviewer,
                'Health_Safety' => $Health_Safety,
                'Doccontroller' => $Doccontroller,
                'Internalaudit' => $Internalaudit,
                'supplierapprovers' => $supplierapprovers,
                'trainingcoordinators' => $trainingcoordinators,
                'wasteapprover' => $wasteapprover,
                'wastereviewer' => $wastereviewer,
                'wastetrfauthty' => $wastetrfauthty,
                'Incidentreviewers' => $Incidentreviewers,
                'model' => $model,
                'incidentapprover' => $incidentapprover,
                'compdoctors' => $compdoctors,
                'hse' => $hse,
                'nearmiss' => $nearmiss,
                'modulenames' => $moduleNameValues,
            ]);
        } else {
            return redirect('/admin/dashboard')->with('model', $model);
        }
    }

    /***
     * helpery function for updateUserRoles function
     */
    public function insertOrDeleteForAGate($key, $id, $email, $keyText)
    {
        // $admin = Admin::where('email', $email)->first();
        // $user = User::where('email', $email)->first();
        // if ($admin) {
        //     $id = $admin->id;
        // } else {
        //     $id = $user->id;
        // }

        if ($key) {
            $found = UserGate::where('key', $keyText)
                ->where('users_id', $id)
                ->first();
            if ($found == null) {
                UserGate::insert([
                    'users_id' => $id,
                    'saver_users_id' => User::where('email',Auth::user()->email)->where('approved',true)->value('id'),
                    'key' => $keyText,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        } else {
            $found = UserGate::where('key', $keyText)
                ->where('users_id', $id)
                ->first();
            if ($found) {
                UserGate::where('key', $keyText)
                    ->where('users_id', $id)
                    ->delete();
            }
        }
    }
    public function updateUserRoles(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $email = $req->input('email');
        $manrev = $req->input('managementrepresentative');
        $comrev = $req->input('complaintreviewer');
        $comreg = $req->input('complaintregister');
        $notify = $req->input('notification');
        $riskrev = $req->input('riskreviewer');
        $mainrev = $req->input('mainreviewer');
        $mainelectrical = $req->input('mainelectrical');
        $maininstrument = $req->input('maininstrument');
        $calibrationrev = $req->input('calibrationreviewer');
        $issuerev = $req->input('issuereviewer');
        $Legalc = $req->input('legalcoordinator');
        $health_safety = $req->input('health_safety');
        $escalate = $req->input('escalate');
        $archpriv = $req->input('archiveprivilege');
        $opportunityapprover = $req->input('opportunityapprover');
        $closurerev = $req->input('closurereviewer');
        $objrev = $req->input('objreviewer');
        $objapp = $req->input('objapprover');
        $envirev = $req->input('envireviewer');
        $doccontrl = $req->input('doccontroller');
        $internalaudit = $req->input('internalaudit');
        $supp_approver = $req->input('supplierapprover');
        $training_coord = $req->input('trainingcoordinator');
        $wasterev = $req->input('wastereviewer');
        $wasteapr = $req->input('wasteapprover');
        $wastetransfer = $req->input('wastetransfer');
        $incidentreviewer = $req->input('increviewer');
        $incidentapprover = $req->input('incapprover');
        $doctor = $req->input('doctor');
        $nearmiss = $req->input('nearmiss');
        $editNearmiss = $req->input('editNearmiss');
        $deleteNearmiss = $req->input('nearmissDelete');
        $editMaintenance = $req->input('editmaintanance');
        $deleteMaintenance = $req->input('deletemaintanance');
        $deleteDoctor = $req->input('doctorDelete');
        $editDoctor = $req->input('doctorEdit');
        $addCalibration = $req->input('calibrationAdd');
        $deleteCalibration = $req->input('calibrationDelete');
        $editCalibration = $req->input('calibrationEdit');
        $wasteDelete = $req->input('wasteDelete');
        $wasteEdit = $req->input('wasteEdit');
        $wasteTrEdit = $req->input('wasteTrEdit');
        $wasteTrDelete = $req->input('wasteTrDelete');
        $vendorReview = $req->input('vendorReview');
        $vendorApprove = $req->input('vendorApprove');
        $vendorDelete = $req->input('vendorDelete');
        $vendorEdit = $req->input('vendorEdit');
        $hrDelete = $req->input('hrDelete');
        $hrEdit = $req->input('hrEdit');
        $incidentDelete = $req->input('incDelete');
        $incidentEdit = $req->input('incEdit');
        $incident_change_rev_appr = $req->input('incident_change_rev_app');
        $auditEdit = $req->input('auditEdit');
        $auditDelete = $req->input('auditDelete');
        $audit_checklist_edit = $req->input('audit_checklist_edit');
        $audit_checklist_delete = $req->input('audit_checklist_delete');
        $audit_checklist_records_edit = $req->input('audit_checklist_records_edit');
        $audit_checklist_records_approve = $req->input('audit_checklist_records_approve');
        $management_review_meeting = $req->input('management_review_meeting');
        $management_review_meeting_delete = $req->input('management_review_meeting_delete');
        $docEdit = $req->input('docEdit');
        $docDelete = $req->input('docDelete');
        $healthEdit = $req->input('healthEdit');
        $healthDelete = $req->input('healthDelete');
        $correctiveEdit = $req->input('correctiveEdit');
        $correctiveDelete = $req->input('correctiveDelete');
        $hseEdit = $req->input('hseEdit');
        $hseDelete = $req->input('hseDelete');
        $opportunityDelete = $req->input('opportunityDelete');
        $opportunityEdit = $req->input('opportunityEdit');
        $enviDelete = $req->input('enviDelete');
        $enviEdit = $req->input('enviEdit');
        $objectiveDelete = $req->input('objectiveDelete');
        $objectiveEdit = $req->input('objectiveEdit');
        $objective_change_res_person = $req->input('objective_change_res_person');
        $deleteRisk = $req->input('deleteRisk');
        $editRisk = $req->input('editRisk');
        $riskEscalateAccess = $req->input('escalateaccess');
        $changeriskowner = $req->input('changeriskowner');
        $complianceDelete = $req->input('complianceDelete');
        $complianceEdit = $req->input('complianceEdit');
        $complianceEvaluate = $req->input('complianceEvaluate');
        $complianceChangeResp = $req->input('complianceChangeResp');
        $complaintDelete = $req->input('complaintDelete');
        $complaintEdit = $req->input('complaintEdit');
        $deleteAll = $req->input('deleteAll');
        $editAll = $req->input('editAll');
        $auditRC = $req->input('auditRC');
        $nearmissRC = $req->input('nearmissRC');
        $hseRC = $req->input('hseRC');
        $opportunityRC = $req->input('opportunityRC');
        $correctiveRC = $req->input('correctiveRC');
        $complaintRC = $req->input('complaintRC');
        $hse = $req->input('hse');
        $enviaspect_secondary_review = $req->input('env_secondary_review');
        $ohs_secondary_review = $req->input('ohs_secondary_review');
        $elearning_add_content = $req->input('addcontent');
        $objective_change_rev_app = $req->input('objective_change_rev_app');
        $id = $req->input('user_id');

        $user = DB::select('select * from users where email = :eemail', [
            'eemail' => $email,
        ]);
        foreach ($user as $row) {
            $name = $row->name;
        }
        $data = ['name' => $name, 'email' => $email,'updated_at'=>now(),'created_at'=>now()];
        $this->insertOrDeleteForAGate(
            $enviaspect_secondary_review,
            $id,
            $email,
            'enviaspect-secondary-review'
        );

        $this->insertOrDeleteForAGate(
            $ohs_secondary_review,
            $id,
            $email,
            'ohsrisk-secondary-review'
        );
        $this->insertOrDeleteForAGate(
            $objrev,
            $id,
            $email,
            'objective-reviewer'
        );
        $this->insertOrDeleteForAGate(
            $objapp,
            $id,
            $email,
            'objective-approver'
        );
        $this->insertOrDeleteForAGate(
            $hse,
            $id,
            $email,
            'hse-observation-reviewer'
        );
        $this->insertOrDeleteForAGate(
            $editNearmiss,
            $id,
            $email,
            'edit-nearmiss'
        );
        $this->insertOrDeleteForAGate(
            $deleteNearmiss,
            $id,
            $email,
            'delete-nearmiss'
        );
        $this->insertOrDeleteForAGate(
            $editMaintenance,
            $id,
            $email,
            'edit-maintenance'
        );
        $this->insertOrDeleteForAGate(
            $deleteMaintenance,
            $id,
            $email,
            'delete-maintenance'
        );
        $this->insertOrDeleteForAGate($editDoctor, $id, $email, 'edit-doctor');
        $this->insertOrDeleteForAGate(
            $deleteDoctor,
            $id,
            $email,
            'delete-doctor'
        );
        $this->insertOrDeleteForAGate(
            $addCalibration,
            $id,
            $email,
            'add-calibration'
        );
        $this->insertOrDeleteForAGate(
            $editCalibration,
            $id,
            $email,
            'edit-calibration'
        );
        $this->insertOrDeleteForAGate(
            $deleteCalibration,
            $id,
            $email,
            'delete-calibration'
        );
        $this->insertOrDeleteForAGate($wasteEdit, $id, $email, 'edit-waste');
        $this->insertOrDeleteForAGate(
            $wasteDelete,
            $id,
            $email,
            'delete-waste'
        );
        $this->insertOrDeleteForAGate(
            $wasteTrEdit,
            $id,
            $email,
            'edit-waste-transferrequest'
        );
        $this->insertOrDeleteForAGate(
            $wasteTrDelete,
            $id,
            $email,
            'delete-waste-transferrequest'
        );
        $this->insertOrDeleteForAGate($vendorReview, $id, $email, 'review-vendor');
        $this->insertOrDeleteForAGate($vendorApprove, $id, $email, 'approve-vendor');
        $this->insertOrDeleteForAGate($vendorEdit, $id, $email, 'edit-vendor');
        $this->insertOrDeleteForAGate(
            $vendorDelete,
            $id,
            $email,
            'delete-vendor'
        );
        $this->insertOrDeleteForAGate($hrEdit, $id, $email, 'edit-hr');
        $this->insertOrDeleteForAGate($hrDelete, $id, $email, 'delete-hr');
        $this->insertOrDeleteForAGate(
            $incidentEdit,
            $id,
            $email,
            'edit-incident'
        );
        $this->insertOrDeleteForAGate(
            $incidentDelete,
            $id,
            $email,
            'delete-incident'
        );
        $this->insertOrDeleteForAGate($incident_change_rev_appr, $id, $email, 'change-incident-reviewer-approver');
        $this->insertOrDeleteForAGate($objective_change_rev_app, $id, $email, 'change-objective-reviewer-approver');
        $this->insertOrDeleteForAGate($auditEdit, $id, $email, 'edit-audit');
        $this->insertOrDeleteForAGate($auditDelete, $id, $email, 'delete-audit');
        $this->insertOrDeleteForAGate($audit_checklist_edit, $id, $email, 'audit-checklist-edit');
        $this->insertOrDeleteForAGate($audit_checklist_delete, $id, $email, 'audit-checklist-delete');
        $this->insertOrDeleteForAGate($audit_checklist_records_edit, $id, $email, 'audit-checklist-records-edit');
        $this->insertOrDeleteForAGate($audit_checklist_records_approve, $id, $email, 'audit-checklist-records-approve');
        $this->insertOrDeleteForAGate($management_review_meeting, $id, $email, 'management-review-meeting-controls');
        $this->insertOrDeleteForAGate($management_review_meeting_delete, $id, $email, 'management-review-meeting-delete');
        $this->insertOrDeleteForAGate($docEdit, $id, $email, 'edit-doc');
        $this->insertOrDeleteForAGate($docDelete, $id, $email, 'delete-doc');
        $this->insertOrDeleteForAGate($healthEdit, $id, $email, 'edit-health');
        $this->insertOrDeleteForAGate(
            $healthDelete,
            $id,
            $email,
            'delete-health'
        );
        $this->insertOrDeleteForAGate(
            $correctiveEdit,
            $id,
            $email,
            'edit-corrective'
        );
        $this->insertOrDeleteForAGate(
            $correctiveDelete,
            $id,
            $email,
            'delete-corrective'
        );
        $this->insertOrDeleteForAGate($hseEdit, $id, $email, 'edit-hse');
        $this->insertOrDeleteForAGate($hseDelete, $id, $email, 'delete-hse');
        $this->insertOrDeleteForAGate(
            $opportunityEdit,
            $id,
            $email,
            'edit-opportunity'
        );
        $this->insertOrDeleteForAGate(
            $opportunityDelete,
            $id,
            $email,
            'delete-opportunity'
        );
        $this->insertOrDeleteForAGate(
            $enviEdit,
            $id,
            $email,
            'edit-enviaspect'
        );
        $this->insertOrDeleteForAGate(
            $enviDelete,
            $id,
            $email,
            'delete-enviaspect'
        );
        $this->insertOrDeleteForAGate(
            $objectiveEdit,
            $id,
            $email,
            'edit-objective'
        );
        $this->insertOrDeleteForAGate(
            $objectiveDelete,
            $id,
            $email,
            'delete-objective'
        );
        $this->insertOrDeleteForAGate($objective_change_res_person, $id, $email, 'objective-change-responsible-person');
        $this->insertOrDeleteForAGate($deleteRisk, $id, $email, 'edit-risk');
        $this->insertOrDeleteForAGate($editRisk, $id, $email, 'delete-risk');
        $this->insertOrDeleteForAGate(
            $complianceEdit,
            $id,
            $email,
            'edit-compliance'
        );
        $this->insertOrDeleteForAGate(
            $complianceDelete,
            $id,
            $email,
            'delete-compliance'
        );
        // $this->insertOrDeleteForAGate(
        //     $complianceEvaluate,
        //     $id,
        //     $email,
        //     'evaluate-compliance-status'
        // );
        // $this->insertOrDeleteForAGate(
        //     $complianceChangeResp,
        //     $id,
        //     $email,
        //     'compliance-change-legal-responsibility'
        // );

        $this->insertOrDeleteForAGate(
            $complaintEdit,
            $id,
            $email,
            'edit-complaint'
        );
        $this->insertOrDeleteForAGate(
            $complaintDelete,
            $id,
            $email,
            'delete-complaint'
        );
        $this->insertOrDeleteForAGate($editAll, $id, $email, 'edit-all');
        $this->insertOrDeleteForAGate($deleteAll, $id, $email, 'delete-all');
        $this->insertOrDeleteForAGate(
            $changeriskowner,
            $id,
            $email,
            'risk-change-riskowner'
        );
        $this->insertOrDeleteForAGate(
            $riskEscalateAccess,
            $id,
            $email,
            'escalate-risk-button-access'
        );
        $this->insertOrDeleteForAGate(
            $auditRC,
            $id,
            $email,
            'audit-responsibility-change'
        );
        $this->insertOrDeleteForAGate(
            $nearmissRC,
            $id,
            $email,
            'nearmiss-responsibility-change'
        );
        $this->insertOrDeleteForAGate(
            $hseRC,
            $id,
            $email,
            'hse-responsibility-change'
        );
        $this->insertOrDeleteForAGate(
            $opportunityRC,
            $id,
            $email,
            'opportunity-responsibility-change'
        );
        $this->insertOrDeleteForAGate(
            $correctiveRC,
            $id,
            $email,
            'corrective-responsibility-change'
        );
        $this->insertOrDeleteForAGate(
            $complaintRC,
            $id,
            $email,
            'complaint-responsibility-change'
        );
        $this->insertOrDeleteForAGate(
            $elearning_add_content,
            $id,
            $email,
            'elearning-add-content'
        );

        if ($manrev) {
            $man = DB::select(
                'select * from representatives where email = :eemail',
                ['eemail' => $email]
            );
            if ($man == null) {
                DB::table('representatives')->insert($data);
            }
        } else {
            $man = DB::select(
                'select * from representatives where email = :eemail',
                ['eemail' => $email]
            );
            if ($man) {
                DB::delete(
                    'delete from representatives where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }

        if ($comrev) {
            $comrev1 = DB::select(
                'select * from complaintreviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($comrev1 == null) {
                DB::table('complaintreviewers')->insert($data);
            }
        } else {
            $comrev1 = DB::select(
                'select * from complaintreviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($comrev1) {
                DB::delete(
                    'delete from complaintreviewers where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }

        if ($comreg) {
            $comreg1 = DB::select(
                'select * from complaintregisters where email = :eemail',
                ['eemail' => $email]
            );
            if ($comreg1 == null) {
                DB::table('complaintregisters')->insert($data);
            }
        } else {
            $comreg1 = DB::select(
                'select * from complaintregisters where email = :eemail',
                ['eemail' => $email]
            );
            if ($comreg1) {
                DB::delete(
                    'delete from complaintregisters where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }

        if ($notify) {
            $notify1 = DB::select(
                'select * from notifiers where email = :eemail',
                ['eemail' => $email]
            );
            if ($notify1 == null) {
                DB::table('notifiers')->insert($data);
            }
        } else {
            $notify1 = DB::select(
                'select * from notifiers where email = :eemail',
                ['eemail' => $email]
            );
            if ($notify1) {
                DB::delete('delete from notifiers where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }

        if ($riskrev) {
            $riskrev1 = DB::select(
                'select * from riskreviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($riskrev1 == null) {
                DB::table('riskreviewers')->insert($data);
            }
        } else {
            $riskrev1 = DB::select(
                'select * from riskreviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($riskrev1) {
                DB::delete('delete from riskreviewers where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }
        if ($mainrev) {
            $mainrev1 = DB::select(
                'select * from mainreviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($mainrev1 == null) {
                DB::table('mainreviewers')->insert($data);
            }
        } else {
            $mainrev1 = DB::select(
                'select * from mainreviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($mainrev1) {
                DB::delete('delete from mainreviewers where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }

        if ($mainelectrical) {
            $mainelectrical1 = DB::select(
                'select * from mainelectricals where email = :eemail',
                ['eemail' => $email]
            );
            if ($mainelectrical1 == null) {
                DB::table('mainelectricals')->insert($data);
            }
        } else {
            $mainelectrical1 = DB::select(
                'select * from mainelectricals where email = :eemail',
                ['eemail' => $email]
            );
            if ($mainelectrical1) {
                DB::delete(
                    'delete from mainelectricals where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }

        if ($maininstrument) {
            $maininstrument1 = DB::select(
                'select * from maininstruments where email = :eemail',
                ['eemail' => $email]
            );
            if ($maininstrument1 == null) {
                DB::table('maininstruments')->insert($data);
            }
        } else {
            $maininstrument1 = DB::select(
                'select * from maininstruments where email = :eemail',
                ['eemail' => $email]
            );
            if ($maininstrument1) {
                DB::delete(
                    'delete from maininstruments where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }

        if ($calibrationrev) {
            $calibrationrev1 = DB::select(
                'select * from calibrationreviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($calibrationrev1 == null) {
                DB::table('calibrationreviewers')->insert($data);
            }
        } else {
            $calibrationrev1 = DB::select(
                'select * from calibrationreviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($calibrationrev1) {
                DB::delete(
                    'delete from calibrationreviewers where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }
        if ($escalate) {
            $escalate1 = DB::select(
                'select * from escalateriskreceivers where email = :eemail',
                ['eemail' => $email]
            );
            if ($escalate1 == null) {
                DB::table('escalateriskreceivers')->insert($data);
            }
        } else {
            $escalate1 = DB::select(
                'select * from escalateriskreceivers where email = :eemail',
                ['eemail' => $email]
            );
            if ($escalate1) {
                DB::delete(
                    'delete from escalateriskreceivers where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }

        if ($archpriv) {
            $arch = DB::select(
                'select * from archiveprivileges where email = :eemail',
                ['eemail' => $email]
            );
            if ($arch == null) {
                DB::table('archiveprivileges')->insert($data);
            }
        } else {
            $arch = DB::select(
                'select * from archiveprivileges where email = :eemail',
                ['eemail' => $email]
            );
            if ($arch) {
                DB::delete(
                    'delete from archiveprivileges where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }

        if($opportunityapprover) {
            Opportunityapprover::firstOrCreate(['email' => $email], $data);
        } else {
            Opportunityapprover::where('email', $email)->delete();
        }

        if ($closurerev) {
            $closurerev1 = DB::select(
                'select * from closurereviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($closurerev1 == null) {
                DB::table('closurereviewers')->insert($data);
            }
        } else {
            $closurerev1 = DB::select(
                'select * from closurereviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($closurerev1) {
                DB::delete(
                    'delete from closurereviewers where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }

        if ($issuerev) {
            $issuerev1 = DB::select(
                'select * from issuereviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($issuerev1 == null) {
                DB::table('issuereviewers')->insert($data);
            }
        } else {
            $issuerev1 = DB::select(
                'select * from issuereviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($issuerev1) {
                DB::delete('delete from issuereviewers where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }

        if ($Legalc) {
            $Legalc1 = DB::select(
                'select * from legalcoordinators where email = :eemail',
                ['eemail' => $email]
            );
            if ($Legalc1 == null) {
                DB::table('legalcoordinators')->insert($data);
            }
        } else {
            $Legalc1 = DB::select(
                'select * from legalcoordinators where email = :eemail',
                ['eemail' => $email]
            );
            if ($Legalc1) {
                DB::delete(
                    'delete from legalcoordinators where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }

        if ($health_safety) {
            $health_safety1 = DB::select(
                'select * from hses where email = :eemail',
                ['eemail' => $email]
            );
            if ($health_safety1 == null) {
                DB::table('hses')->insert($data);
            }
        } else {
            $health_safety1 = DB::select(
                'select * from hses where email = :eemail',
                ['eemail' => $email]
            );
            if ($health_safety1) {
                DB::delete('delete from hses where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }

        if ($envirev) {
            $envirev1 = DB::select(
                'select * from envireviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($envirev1 == null) {
                DB::table('envireviewers')->insert($data);
            }
        } else {
            $envirev1 = DB::select(
                'select * from envireviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($envirev1) {
                DB::delete('delete from envireviewers where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }

        if ($doccontrl) {
            $doccontrl1 = DB::select(
                'select * from doccontrollers where email = :eemail',
                ['eemail' => $email]
            );
            if ($doccontrl1 == null) {
                DB::table('doccontrollers')->insert($data);
            }
        } else {
            $doccontrl1 = DB::select(
                'select * from doccontrollers where email = :eemail',
                ['eemail' => $email]
            );
            if ($doccontrl1) {
                DB::delete('delete from doccontrollers where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }

        if ($internalaudit) {
            $internalaudit1 = DB::select(
                'select * from internalaudits where email = :eemail',
                ['eemail' => $email]
            );
            if ($internalaudit1 == null) {
                DB::table('internalaudits')->insert($data);
            }
        } else {
            $internalaudit1 = DB::select(
                'select * from internalaudits where email = :eemail',
                ['eemail' => $email]
            );
            if ($internalaudit1) {
                DB::delete('delete from internalaudits where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }
        if ($supp_approver) {
            $supplierapprover1 = DB::select(
                'select * from supplierapprovers where email = :eemail',
                ['eemail' => $email]
            );
            if ($supplierapprover1 == null) {
                DB::table('supplierapprovers')->insert($data);
            }
        } else {
            $supplierapprover1 = DB::select(
                'select * from supplierapprovers where email = :eemail',
                ['eemail' => $email]
            );
            if ($supplierapprover1) {
                DB::delete(
                    'delete from supplierapprovers where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }
        if ($wasterev) {
            $wasterev1 = DB::select(
                'select * from wastereviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($wasterev1 == null) {
                DB::table('wastereviewers')->insert($data);
            }
        } else {
            $wasterev1 = DB::select(
                'select * from wastereviewers where email = :eemail',
                ['eemail' => $email]
            );
            if ($wasterev1) {
                DB::delete('delete from wastereviewers where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }
        if ($wasteapr) {
            $wasteapr1 = DB::select(
                'select * from wasteapprovers where email = :eemail',
                ['eemail' => $email]
            );
            if ($wasteapr1 == null) {
                DB::table('wasteapprovers')->insert($data);
            }
        } else {
            $wasteapr1 = DB::select(
                'select * from wasteapprovers where email = :eemail',
                ['eemail' => $email]
            );
            if ($wasteapr1) {
                DB::delete('delete from wasteapprovers where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }
        if ($wastetransfer) {
            $wastetrnsfr1 = DB::select(
                'select * from wastedisposalsalesauthorities where email = :eemail',
                ['eemail' => $email]
            );
            if ($wastetrnsfr1 == null) {
                DB::table('wastedisposalsalesauthorities')->insert($data);
            }
        } else {
            $wastetrnsfr1 = DB::select(
                'select * from wastedisposalsalesauthorities where email = :eemail',
                ['eemail' => $email]
            );
            if ($wastetrnsfr1) {
                DB::delete(
                    'delete from wastedisposalsalesauthorities where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }
        if ($doctor) {
            $cdoctor = DB::select(
                'select * from company_doctors where email = :eemail',
                ['eemail' => $email]
            );
            if ($cdoctor == null) {
                DB::table('company_doctors')->insert($data);
            }
        } else {
            $cdoctor = DB::select(
                'select * from company_doctors where email = :eemail',
                ['eemail' => $email]
            );
            if ($cdoctor) {
                DB::delete(
                    'delete from company_doctors where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }

        if ($training_coord) {
            $trainingcoordinator1 = DB::select(
                'select * from trainingcoordinators where email = :eemail',
                ['eemail' => $email]
            );
            if ($trainingcoordinator1 == null) {
                DB::table('trainingcoordinators')->insert($data);
            }
        } else {
            $trainingcoordinator1 = DB::select(
                'select * from trainingcoordinators where email = :eemail',
                ['eemail' => $email]
            );
            if ($trainingcoordinator1) {
                DB::delete(
                    'delete from trainingcoordinators where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }
        if ($incidentreviewer) {
            $incidentrev = DB::select(
                'select * from incident_reviewer where email = :eemail',
                ['eemail' => $email]
            );
            if ($incidentrev == null) {
                DB::table('incident_reviewer')->insert($data);
            }
        } else {
            $doccontrl1 = DB::select(
                'select * from incident_reviewer where email = :eemail',
                ['eemail' => $email]
            );
            if ($doccontrl1) {
                DB::delete(
                    'delete from incident_reviewer where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }
        if ($incidentapprover) {
            $incidentapp = DB::select(
                'select * from incident_approvers where email = :eemail',
                ['eemail' => $email]
            );
            if ($incidentapp == null) {
                DB::table('incident_approvers')->insert($data);
            }
        } else {
            $doccontrl1 = DB::select(
                'select * from incident_approvers where email = :eemail',
                ['eemail' => $email]
            );
            if ($doccontrl1) {
                DB::delete(
                    'delete from incident_approvers where email = :eemail',
                    ['eemail' => $email]
                );
            }
        }
        if ($nearmiss) {
            $nearmisss = DB::select(
                'select * from nears where email = :eemail',
                ['eemail' => $email]
            );
            if ($nearmisss == null) {
                DB::table('nears')->insert($data);
            }
        } else {
            $nearmiss1 = DB::select(
                'select * from nears where email = :eemail',
                ['eemail' => $email]
            );
            if ($nearmiss1) {
                DB::delete('delete from nears where email = :eemail', [
                    'eemail' => $email,
                ]);
            }
        }
        //insert data into activities table
        $activity =
            'User Roles are Updated of ' . $name . ' with Email ' . $email;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);
        return redirect('/admin/viewUsers')->with([
            'model' => $models,
            'message' => 'User roles updated successfully for ' . $name
        ]);
    }
    public function searchedUser(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $search = $req->input('search');
        if ($search != null) {
            $users = DB::select(
                'select * from users where id = :eid or email = :eemail or name = :ename',
                ['eid' => $search, 'eemail' => $search, 'ename' => $search]
            );

            return view('admin.viewUsers')
                ->with('users', $users)
                ->with('model', $models);
        } else {
            return redirect('/admin/viewUsers')->with('model', $models);
        }
    }
    public function viewCategories()
    {
        $models = DB::table('modenadisable')->First();
        $departments = Departments::all();
        $types = Type::orderBy('order', 'ASC')->get();
        $standards = Standards::all();
        $conformances = AuditChecklistConformance::all();
        $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
        return view('admin.viewCategories')
            ->with('model', $models)
            ->withStandards($standards)
            ->with('departments', $departments)
            ->with('types', $types)
            ->with('conformances', $conformances)
            ->with('rootcorrectives', $rootcorrectives);
    }

    public function ajaxEnableSource(EnableSourceRequest $request)
    {
        $data = $request->validated();
        $id = $data['id'];
        $is_checked = $data['is_checked'];
        Categories::where('id', $id)->update([
            'enable_source' => $is_checked == 'true' ? 1 : 0,
        ]);

        return [
            'data' => null,
            'error' => null,
        ];
    }

    /**--audit root cause and corrective action for corrective module----*/

    public function addcorrectiverootcorrective()
    {
        $models = DB::table('modenadisable')->first();

        return view('admin.add_corrective_root_corrective')->with('model', $models);
    }

    public function insertcorrectiverootcorrective(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'root_cause' => 'required|string',
            'corrective_action' => 'required|string',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('model', $models);
        } else {
            $data = [
                'title' => $request->title,
                'root_cause' => $request->root_cause,
                'corrective_action' => $request->corrective_action,
            ];

            CorrectiveRootCauseCorrectiveActionDetail::create($data);
            Session::flash('msg', 'Details added successfully');

            return redirect()->back()->withInput();
        }
    }

    public function editcorrectiverootcorrective($id)
    {
        $models = DB::table('modenadisable')->first();
        $data = CorrectiveRootCauseCorrectiveActionDetail::where('id', $id)->first();

        return view('admin.edit_corrective_root_corrective')
            ->with('data', $data)
            ->with('model', $models);
    }

    public function updatecorrectiverootcorrective(Request $request)
    {
        $data = [
            'title' => $request->title,
            'root_cause' => $request->root_cause,
            'corrective_action' => $request->corrective_action,
        ];
        CorrectiveRootCauseCorrectiveActionDetail::where('id', $request->rootcorrectiveid)->update($data);
        //$models = DB::table('modenadisable')->First();
        Session::flash('msg', 'Details updated successfully');

        return redirect()->back();
    }

    public function deletecorrectiverootcorrective($id)
    {
        CorrectiveRootCauseCorrectiveActionDetail::where('id', $id)->delete();

        $models = DB::table('modenadisable')->First();
        Session::flash('delmessage', 'Data Deleted Successfully');

        return redirect()->back();
    }

    /**--audit root cause and corrective action----*/
    public function addrootcorrective()
    {
        $models = DB::table('modenadisable')->First();
        return view('admin.addauditrootcorrective')->with('model', $models);
    }

    public function insertrootcorrective(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'root_cause' => 'required',
            'corrective_action' => 'required',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('model', $models);
        } else {
            $data = [
                'title' => $request->title,
                'root_cause' => $request->root_cause,
                'corrective_action' => $request->corrective_action,
            ];

            AuditRootCauseCorrectiveActionDetail::create($data);
            Session::flash('msg', 'Details added successfully');
            return redirect()
                ->back()
                ->withInput();
        }
    }

    public function editrootcorrective($id)
    {
        $models = DB::table('modenadisable')->First();
        $data = AuditRootCauseCorrectiveActionDetail::where('id', $id)->first();
        return view('admin.editauditrootcorrective')
            ->with('data', $data)
            ->with('model', $models);
    }

    public function updaterootcorrective(Request $request)
    {
        $data = [
            'title' => $request->title,
            'root_cause' => $request->root_cause,
            'corrective_action' => $request->corrective_action,
        ];
        AuditRootCauseCorrectiveActionDetail::where('id', $request->rootcorrectiveid)->update(
            [
                'title' => $request->title,
                'root_cause' => $request->root_cause,
                'corrective_action' => $request->corrective_action,
            ]
        );
        $models = DB::table('modenadisable')->First();
        Session::flash('msg', 'Details updated successfully');
        return redirect()->back();
    }

    public function deleterootcorrective($id)
    {
        AuditRootCauseCorrectiveActionDetail::where('id', $id)->delete();

        $models = DB::table('modenadisable')->First();
        Session::flash('delmessage', 'Data Deleted Successfully');
        return redirect()->back();
    }

    /**--audit checklist conformance----*/
    public function addconformance()
    {
        $models = DB::table('modenadisable')->First();
        return view('admin.addconformance')->with('model', $models);
    }

    public function insertconformance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stage' => 'required',
            'classification' => 'required',
            'descp' => 'required',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('model', $models);
        } else {
            $data = [
                'stage' => $request->stage,
                'classification' => $request->classification,
                'description' => $request->descp,
            ];

            AuditChecklistConformance::create($data);
            Session::flash('msg', 'Details added successfully');
            return redirect()
                ->back()
                ->withInput();
        }
    }

    public function editconformance($id)
    {
        $models = DB::table('modenadisable')->First();
        $data = AuditChecklistConformance::where('id', $id)->first();
        return view('admin.editconformance')
            ->with('data', $data)
            ->with('model', $models);
    }

    public function updateconformance(Request $request)
    {
        $data = [
            'stage' => $request->stage,
            'classification' => $request->classification,
            'description' => $request->descp,
        ];
        AuditChecklistConformance::where('id', $request->conformanceid)->update(
            [
                'stage' => $request->stage,
                'classification' => $request->classification,
                'description' => $request->descp,
            ]
        );
        $models = DB::table('modenadisable')->First();
        Session::flash('msg', 'Details updated successfully');
        return redirect()->back();
    }

    public function editCategory(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $category = Categories::with('productNcrAddedRelation','processNcrAddedRelation','vendorNcrAddedRelation')->findOrFail($id);
            if($category->is_pnc_cat=='yes' ){
                $ncrConfigurationFields = ProductNcrConfigurationField::all();
                $ncrConAddedFields = ProductNcrConfigurationAddedField::where('category_id',$id)->get();
            }else if($category->is_process_cat=='yes'){
                $ncrConfigurationFields = ProcessNcrConfigurationField::all();
                $ncrConAddedFields = ProcessNcrConfigurationAddedField::where('category_id',$id)->get();
            }else if($category->is_vendor_cat=='yes'){
                $ncrConfigurationFields = VendorNcrConfigurationField::all();
                $ncrConAddedFields = VendorNcrConfigurationAddedField::where('category_id',$id)->get();
            }else{
                $ncrConfigurationFields = collect();
                $ncrConAddedFields = collect();
            }

            return view('admin.editCategory',compact('ncrConfigurationFields','ncrConAddedFields'))
                ->with('model', $models)
                ->with('category', $category);
        }
    }
    public function updateCategory(Request $req)
    {
        DB::transaction(function () use ($req) {

            $categoryId  = $req->id;
            $name        = $req->name;
            $pncQuestion = $req->pnc_question ?? 'no';
            $userId      = User::where('email', Auth::user()->email)->value('id');

            // Update category
            $category = Categories::findOrFail($categoryId);
            $category->update([
                'categoryname' => $name,
                'product_specific_details_required' => $pncQuestion,
            ]);
            $fieldModel = null;
            $addedFieldModel = null;
            $historyModel = null;
            if($category->is_pnc_cat=='yes'){
                $fieldModel = ProductNcrConfigurationField::class;
                $addedFieldModel = ProductNcrConfigurationAddedField::class;
                $historyModel = ProductNcrFieldHistory::class;
            }else if($category->is_process_cat=='yes'){
                $fieldModel = ProcessNcrConfigurationField::class;
                $addedFieldModel = ProcessNcrConfigurationAddedField::class;
                $historyModel = ProcessNcrFieldHistory::class;
            }else if($category->is_vendor_cat=='yes'){
                $fieldModel = VendorNcrConfigurationField::class;
                $addedFieldModel = VendorNcrConfigurationAddedField::class;
                $historyModel = VendorNcrFieldHistory::class;
            }else{
                // If category is neither PNC, Process, nor Vendor, we don't have custom fields to manage
                // Activity log
                ViewActivity::create([
                    'name'       => Auth::user()->name,
                    'email'      => Auth::user()->email,
                    'date'       => now()->format('Y-m-d'),
                    'time'       => now()->format('H:i:s'),
                    'activity'   => "{$req->oldcatname} Category updated to {$req->name}",
                    'type'       => 'admin',
                ]);
                Session::flash('message', 'Category updated successfully');
                return redirect('/admin/viewTypes');
            }

            if($pncQuestion && $pncQuestion =='yes'){
                $selectedFields   = $req->select_field ?? [];
                $mandatoryFields  = $req->mandatory ?? [];
                $displayLabels    = $req->display_label ?? [];
                $masterFields = $fieldModel::whereIn('id', $selectedFields)
                    ->get()
                    ->keyBy('id');

                // Existing active configs
                $existingConfigs = $addedFieldModel::where('category_id', $categoryId)
                    ->whereNull('deleted_at')
                    ->get()
                    ->keyBy('field_id');

                // Handle removed fields
                $fieldsToRemove = array_diff(
                    $existingConfigs->keys()->toArray(),
                    $selectedFields
                );

                if (!empty($fieldsToRemove)) {
                    $addedFieldModel::where('category_id', $categoryId)
                        ->whereIn('field_id', $fieldsToRemove)
                        ->whereNull('deleted_at')
                        ->update([
                            'deleted_at' => now(),
                            'deleted_by' => $userId,
                        ]);
                }

                foreach ($selectedFields as $index => $fieldId) {

                    if (!isset($masterFields[$fieldId])) {
                        continue;
                    }
                    $newLabel     = $displayLabels[$index] ?? $masterFields[$fieldId]->display_label;
                    $newMandatory = in_array($fieldId, $mandatoryFields) ? 1 : 0;
                    $existing = $existingConfigs[$fieldId] ?? null;
                    $lastHistory = $historyModel::where('field_name',$masterFields[$fieldId]->field_name)->latest()->first();

                    // CASE 1: Field already exists
                    if ($existing) {

                        $labelChanged     = $existing->display_label !== $newLabel;
                        $mandatoryChanged = (int)$existing->is_mandatory !== $newMandatory;

                        // Nothing changed → skip
                        if (!$labelChanged && !$mandatoryChanged) {
                            continue;
                        }

                        // Determine version
                        $newVersion = $existing->field_label_version;

                        if ($labelChanged) {
                            // Increment version ONLY if label changed
                            $newVersion++;
                        }

                        // Soft delete old row
                        $existing->update([
                            'deleted_at' => now(),
                            'deleted_by' => $userId,
                        ]);

                        // Insert new row
                        $addedFieldModel::create([
                            'field_id'            => $fieldId,
                            'field_name'          => $masterFields[$fieldId]->field_name,
                            'field_type'          => $masterFields[$fieldId]->field_type,
                            'display_label'       => $newLabel,
                            'field_label_version' => $newVersion,
                            'is_mandatory'        => $newMandatory,
                            'category_id'         => $categoryId,
                            'created_by'          => $userId,
                        ]);
                        $historyModel::create([
                            'field_name' => $masterFields[$fieldId]->field_name,
                            'previous_name' => $lastHistory->updated_name??'--',
                            'updated_name' => $newLabel,
                            'modifier' => $userId,
                            'modified_at' => now(),
                        ]);
                        continue;
                    }

                    // CASE 2: New field added
                    $addedFieldModel::create([
                        'field_id'            => $fieldId,
                        'field_name'          => $masterFields[$fieldId]->field_name,
                        'field_type'          => $masterFields[$fieldId]->field_type,
                        'display_label'       => $newLabel,
                        'field_label_version' => 1,
                        'is_mandatory'        => $newMandatory,
                        'category_id'         => $categoryId,
                        'created_by'          => $userId,
                    ]);

                    $historyModel::create([
                        'field_name' => $masterFields[$fieldId]->field_name,
                        'previous_name' => $lastHistory->updated_name??'--',
                        'updated_name' => $newLabel,
                        'modifier' => $userId,
                        'modified_at' => now(),
                    ]);
                }

            }else{
                // If PNC question is 'no', remove all added fields for this category
                $addedFieldModel::where('category_id', $categoryId)
                    ->whereNull('deleted_at')
                    ->update([
                        'deleted_at' => now(),
                        'deleted_by' => $userId,
                    ]);
            }
        });

        // Activity log
        ViewActivity::create([
            'name'       => Auth::user()->name,
            'email'      => Auth::user()->email,
            'date'       => now()->format('Y-m-d'),
            'time'       => now()->format('H:i:s'),
            'activity'   => "{$req->oldcatname} Category updated to {$req->name}",
            'type'       => 'admin',
        ]);
        Session::flash('message', 'Category updated successfully');
        return redirect('/admin/viewTypes');
    }

    public function removeCategory(Request $req)
    {
        $categories = Categories::all();
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $category = Categories::where('id', $id)->first();
            $activity = 'Category is Removed with Name ' . $category->categoryname;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            Session::flash('message','Category #'.$category->categoryname.' deleted successfully');
            ProductNcrConfigurationAddedField::where('category_id', $id)->update([
                'deleted_at' => now(),
                'deleted_by' => User::where('email', Auth::user()->email)->value('id'),
            ]);
            ProcessNcrConfigurationAddedField::where('category_id', $id)->update([
                'deleted_at' => now(),
                'deleted_by' => User::where('email', Auth::user()->email)->value('id'),
            ]);
            VendorNcrConfigurationAddedField::where('category_id', $id)->update([
                'deleted_at' => now(),
                'deleted_by' => User::where('email', Auth::user()->email)->value('id'),
            ]);
            if($category->categoryname=='Product Non- Conformity'){
                $category->update([
                    'is_pnc_cat' => 'yes',
                    'deleted_at' => now()
                ]);
            }else if($category->categoryname=='Process Non- Conformity'){
                $category->update([
                    'is_process_cat' => 'yes',
                    'deleted_at' => now()
                ]);
            }else if($category->categoryname=='Vendor Non- Conformity'){
                $category->update([
                    'is_vendor_cat' => 'yes',
                    'deleted_at' => now()
                ]);
            }else{
                $category->delete();
            }
            return redirect('/admin/viewTypes')
                ->with('model', $models)
                ->with('messageremcat', 'Category deleted successfully')
                ->with('categories', $categories);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function searchedCategory(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $search = $req->input('search');
        if ($search != null) {
            $categories = DB::select(
                'select * from categories where categoryname = :ecat or id = :eid',
                ['ecat' => $search, 'eid' => $search]
            );

            return view('admin.viewTypes')
                ->with('categories', $categories)
                ->with('model', $models);
        } else {
            return redirect('/admin/viewTypes')->with('model', $models);
        }
    }
    public function addCategory(AddCategoryRequest $req)
    {
        //$models = DB::table('modenadisable')->First();
        $name = $req->input('category');
        if ($name === 'other') {
            $catname = $req->input('name');
        } else {
            $catname = $name;
        }
        $sh = 'has';
        $found = Categories::where('categoryname', $catname)->first();
        $error = $found ? 'Category already exists' : '';
        $pncQuestion = $req->input('pnc_question');
        $userId      = User::where('email', Auth::user()->email)->value('id');
        if ($error === '') {
            DB::transaction(function () use ($req, $catname, $sh,$pncQuestion,$name,$userId) {
                $existingCategory = Categories::withTrashed()
                    ->where('categoryname', $catname)
                    ->where(function ($query) {
                        $query->where('is_pnc_cat', 'yes')
                            ->orWhere('is_process_cat', 'yes')
                            ->orWhere('is_vendor_cat', 'yes');
                    })
                    ->first();

                if ($existingCategory && $existingCategory->trashed()) {
                    $existingCategory->restore();
                    $existingCategory->update([
                        'shortname' => $sh,
                        'product_specific_details_required' => $pncQuestion ?? 'no',
                    ]);

                    $cat = $existingCategory;

                } else {
                    $catnameTemp = strtolower(trim($catname));
                    $cat = Categories::create([
                        'categoryname' => $catname,
                        'shortname' => $sh,
                        'is_pnc_cat' => $catnameTemp === strtolower('Product Non- Conformity') ? 'yes' : 'no',
                        'is_process_cat' => $catnameTemp === strtolower('Process Non- Conformity') ? 'yes' : 'no',
                        'is_vendor_cat' => $catnameTemp === strtolower('Vendor Non- Conformity') ? 'yes' : 'no',
                        'product_specific_details_required' => $pncQuestion ?? 'no',
                    ]);
                }

                // Insert selected fields into ProductNcrConfigurationAddedField table
                if($pncQuestion && $pncQuestion =='yes'){
                    $selectedFields = $req->select_field ?? [];
                    $mandatoryArray = $req->mandatory ?? [];
                    $model = null;
                    $modelAdded = null;
                    if($cat->is_pnc_cat=='yes'){
                        $model = ProductNcrConfigurationField::class;
                        $modelAdded = ProductNcrConfigurationAddedField::class;
                    }elseif($cat->is_process_cat=='yes'){
                        $model = ProcessNcrConfigurationField::class;
                        $modelAdded = ProcessNcrConfigurationAddedField::class;
                    }elseif($cat->is_vendor_cat=='yes'){
                        $model = VendorNcrConfigurationField::class;
                        $modelAdded = VendorNcrConfigurationAddedField::class;
                    }
                    // Fetch only selected fields from DB
                    $productNcrFields = $model::whereIn('id', $selectedFields)
                        ->get()
                        ->keyBy('id');

                    foreach ($selectedFields as $index => $fieldId) {
                        if(!isset($productNcrFields[$fieldId])) {
                            continue;
                        }
                        $latestVersion = $modelAdded::where('field_id', $fieldId)
                            ->max('field_label_version');
                        $nextVersion = $latestVersion ? $latestVersion + 1 : 1;
                        $modelAdded::create([
                            'field_name' => $productNcrFields[$fieldId]->field_name,
                            'field_type' => $productNcrFields[$fieldId]->field_type,
                            'display_label' => $req->display_label[$index] ?? $productNcrFields[$fieldId]->display_label,
                            'is_mandatory' => in_array($fieldId, $mandatoryArray) ? 1 : 0,
                            'category_id' => $cat->id,
                            'created_by' => $userId,
                            'field_id' => $fieldId,
                            'field_label_version' => $nextVersion,
                        ]);

                    }
                }
                //inserting data to activities table
                $activity = 'Category is Added with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);
            });
        }

        return [
            'data' => null,
            'error' => $error,
        ];

    }
    public function checkCategory(Request $req){
        $name = $req->input('category_name');
        $type = $req->input('type');
        $id = $req->input('id');
        $isPncCat = $req->input('is_pnc_cat');
        $isProcessCat = $req->input('is_process_cat');
        $isVendorCat = $req->input('is_vendor_cat');

        $PNC_NAME = 'product non- conformity';
        $PROCESS_NAME = 'process non- conformity';
        $VENDOR_NAME = 'vendor non- conformity';
        $catname = $name === 'other' ? $req->input('name') : $name;
        if($type=='add'){
            $catnameTemp = strtolower($catname);

            /*
            |--------------------------------------------------------------------------
            | Product Non-Conformity Check
            |--------------------------------------------------------------------------
            */
            if ($catnameTemp == $PNC_NAME) {

                $category = Categories::where('is_pnc_cat', 'yes')->first();

                if ($category) {
                    return response()->json([
                        'exists' => true,
                        'msg' => 'This category is already selected for the Product Non-Conformity named as
                        <b style="color:blue;">' . $category->categoryname . '</b>. Please choose a different one.'
                    ]);
                }
            }
            /*
            |--------------------------------------------------------------------------
            | Process Non-Conformity Check
            |--------------------------------------------------------------------------
            */
            if ($catnameTemp == $PROCESS_NAME) {

                $category = Categories::where('is_process_cat', 'yes')->first();

                if ($category) {
                    return response()->json([
                        'exists' => true,
                        'msg' => 'This category is already selected for the Process Non-Conformity named as
                        <b style="color:blue;">' . $category->categoryname . '</b>. Please choose a different one.'
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Vendor Non-Conformity Check
            |--------------------------------------------------------------------------
            */
            if ($catnameTemp == $VENDOR_NAME) {

                $category = Categories::where('is_vendor_cat', 'yes')->first();

                if ($category) {
                    return response()->json([
                        'exists' => true,
                        'msg' => 'This category is already selected for the Vendor Non-Conformity named as
                        <b style="color:blue;">' . $category->categoryname . '</b>. Please choose a different one.'
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Normal Category Exists Check
            |--------------------------------------------------------------------------
            */
            $exists = Categories::where('categoryname', $catname)->first();
        }elseif($type=='edit'){
            //Pnc is already there so user can't change another category to pnc category
            if($isPncCat == 'yes' || $isProcessCat == 'yes' || $isVendorCat == 'yes'){
                $exists = Categories::where('categoryname', $catname)
                        ->where('id','!=',$id)
                        ->first();
                $msg = 'This category name is already selected. Please choose a different one.';
            }
            if($isPncCat == 'no'){
                $sCat = strtolower($catname);
                if($sCat == $PNC_NAME){
                    // $exists = Categories::where('is_pnc_cat','yes')->first();
                    $exists = Categories::where('is_pnc_cat','no')->first();
                }else if($sCat == $PROCESS_NAME){
                    $exists = Categories::where('is_process_cat','no')->first();
                }else if($sCat == $VENDOR_NAME){
                    $exists = Categories::where('is_vendor_cat','no')->first();
                }else{
                    $exists = Categories::where('categoryname', $catname)
                        ->where('id','!=',$id)
                        ->first();
                }
            }
        }
        if ($exists) {
            if($exists->is_pnc_cat == 'yes'){
                $msg = 'This category is already selected for the Product Non-Conformity named as <b style="color:blue;">' . $exists->categoryname . '</b>. Please choose a different one.';
            }elseif($exists->is_process_cat == 'yes'){
                $msg = 'This category is already selected for the Process Non-Conformity named as <b style="color:blue;">' . $exists->categoryname . '</b>. Please choose a different one.';
            }elseif($exists->is_vendor_cat == 'yes'){
                $msg = 'This category is already selected for the Vendor Non-Conformity named as <b style="color:blue;">' . $exists->categoryname . '</b>. Please choose a different one.';
            }else{
                $msg = 'This category name is already selected. Please choose a different one.';
            }
            return response()->json(['exists' => true, 'msg' => $msg]);
        } else {
            return response()->json(['exists' => false]);
        }
    }
    //hse category
    public function viewhseCategories()
    {
        $models = DB::table('modenadisable')->First();
        $hsecategories = DB::select('select * from hsecategories');
        // dd($hsecategories);
        $hsetypes = DB::select('select * from hsetypes');

        return view('admin.view_hseCategories')
            ->with('model', $models)
            ->with('hsetypes', $hsetypes)
            ->with('hsecategories', $hsecategories);
    }
    public function edithseCategory(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $hsecategories = DB::select(
                'select * from hsecategories where id = :i',
                ['i' => $id]
            );
            return view('admin.edit_hseCategory')
                ->with('model', $models)
                ->with('hsecategories', $hsecategories);
        }
    }
    public function updatehseCategory(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');
        $id = $req->input('id');
        $catid = DB::select(
            'select * from hsecategories where categoryname = :ename',
            ['ename' => $name]
        );
        foreach ($catid as $row) {
            $cat1id = (string) $row->id;
            if ($cat1id == $id || $catid == null) {
                //inserting data to activities table
                $oldname = DB::table('hsecategories')
                    ->where('id', $id)
                    ->pluck('categoryname');
                $activity =
                    $oldname . ' Category is Updated with Name ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                DB::update(
                    'update hsecategories set categoryname = :ename  where id = :eid',
                    ['ename' => $name, 'eid' => $id]
                );
                return redirect('/admin/viewhseCategories');
            } else {
                return redirect('/admin/viewhseCategories')->with(
                    'model',
                    $models
                );
            }
        }
        if ($catid == null) {
            //inserting data to activities table
            $oldname = DB::table('categories')
                ->where('id', $id)
                ->pluck('categoryname');
            $activity = $oldname . ' Category is Updated with Name ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::update(
                'update hsecategories set categoryname = :ename  where id = :eid',
                ['ename' => $name, 'eid' => $id]
            );
            return redirect('/admin/viewhseCategories')->with('model', $models);
        }
    }
    public function removehseCategory(Request $req)
    {
        $categories = Categories::all();
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $name = DB::table('hsecategories')
                ->where('id', $id)
                ->pluck('categoryname');
            $activity = 'Category is Removed with Name ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from hsecategories where id = :eid', [
                'eid' => $id,
            ]);
            return redirect('/admin/viewhseCategories')
                ->with('model', $models)
                ->with('messageremcat', 'Category deleted successfully')
                ->with('categories', $categories);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function addhseCategory(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('category');
        $sh = 'has';
        $hsecategories = DB::select('select * from hsecategories');
        if ($name != null) {
            $id = DB::select(
                'select id from hsecategories where categoryname = :enteredCategory',
                ['enteredCategory' => $name]
            );
            if ($id) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'HSE Category already exists')
                    ->with('hsecategories', $hsecategories);
            } else {
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $data = ['categoryname' => $name, 'created_at' => $now, 'updated_at' => $now];
                DB::table('hsecategories')->insert($data);

                //inserting data to activities table
                $activity = 'Category is Added with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return back()
                    ->with('model', $models)
                    ->with('message', ' HSE Category added successfully')
                    ->with('hsecategories', $hsecategories);
            }
        } else {
            return back()
                ->with('model', $models);
        }
    }
    public function viewAdmins()
    {
        $models = DB::table('modenadisable')->First();
        $admins = Admin::all();
        $user = User::where('approved',true)->where('is_email_verified',true)->get();
        return view('admin.viewAdmins')
            ->with('model', $models)
            ->with('admins', $admins)
            ->withUser($user);
    }
    public function addAdmin(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $rules = [
            'admin' => 'required|unique:admins,email',
        ];
        $messages = ['admin.required' => 'Please select Name'];
        $validator = Validator::make($req->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('model', $models);
        } else {
            $name = $req->input('try');
            $email = $req->input('admin');
            $hodp = $req->input('position');
            $type = 1;
            $empid = DB::table('users')
                ->select('employeeid')
                ->where('name', $name)
                ->where('email', $email)
                ->first()->employeeid;
            $password = DB::table('users')
                ->select('password')
                ->where('name', $name)
                ->where('email', $email)
                ->first()->password;

            $dept = DB::table('users')
                ->select('deptid')
                ->where('name', $name)
                ->where('email', $email)
                ->first()->deptid;

            $departments = Departments::all();
            if ($name != null) {
                $id = DB::select(
                    'select id from departments where departmentname = :enteredDepartment',
                    ['enteredDepartment' => $name]
                );

                if ($id) {
                    return back()
                        ->with('model', $models)
                        ->with('message', 'Already added');
                } else {
                    $data = [
                        'name' => $name,
                        'email' => $email,
                        'type' => $type,
                        'employeeid' => $empid,
                        'password' => $password,
                        'deptid' => $dept,
                        'code' => '-',
                    ];

                    DB::table('admins')->insert($data);

                    $data1 = [
                        'name' => $name,
                        'email' => $email,
                        'updated_at' =>now(),
                        'created_at' =>now()
                    ];
                    DB::update(
                        'update users set type=:etype where  email=:eemail',
                        ['etype' => 1, 'eemail' => $email]
                    );

                    return back()
                        ->with('model', $models)
                        ->with('message', 'Admin added successfully')
                        ->with('departments', $departments);
                }
            } else {
                return back()
                    ->with('model', $models)
                    ->with('departments', $departments);
            }
        }
    }
    public function CarryAdminID(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            return $this->removeAdmin($id)->with('model', $models);
        }
    }
    public function removeAdmin(Request $req)
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $email = $_GET['email'];
            $admins = Admin::all();

            $models = DB::table('modenadisable')->First();

            DB::delete('delete from admins where id = :eid', ['eid' => $id]);

            DB::update('update users set type=:etype where  email=:eemail', [
                'etype' => 0,
                'eemail' => $email,
            ]);

            return redirect('/admin/viewAdmins')
                ->with('model', $models)
                ->with('message', 'Delete Successful')
                ->with('admins', $admins);
        }
    }
    public function departmentAreas()
    {
        $models = DB::table('modenadisable')->First();
        $areas = Area::all();
        $departments = Departments::get();
        return view('admin.departmentAreas')->with([
            'areas' => $areas,
            'departments' => $departments,
            'model' => $models,
        ]);
    }
    public function departmentDivisions()
    {
        $models = DB::table('modenadisable')->First();
        $divisions = Division::orderBy('department_id', 'desc')->get();
        $departments = Departments::get();
        return view('admin.departmentDivisions')->with([
            'divisions' => $divisions,
            'departments' => $departments,
            'model' => $models,
        ]);
    }
    public function deleteDivision(int $id)
    {
        $division = Division::findOrFail($id);
        $division->delete();
        Session::flash('message', 'Division deleted Successfully');
        return redirect()->back();
    }
    public function addDepartmentDivisions(AddDepartmentDivisionRequest $request)
    {
        $data = $request->validated();
        $division = Division::create($data);

        return [
            'data' => $division,
            'error' => null,
        ];
    }
    public function editDepartmentDivisions(
        EditDepartmentDivisionRequest $request,
        int $id
    ) {
        $data = $request->validated();
        $division = Division::findOrFail($id);
        $division->update($data);
        Session::flash('message', 'Division edited Successfully');
        return redirect()->back();
    }
    public function addAreasToADivision(AddAreasToDivisionRequest $request)
    {
        $data = $request->validated();
        $area = Area::create($data);
        DivisionArea::create([
            'area_id' => $area->id,
            'division_id' => $request->input('division_id'),
        ]);
        Session::flash('message', 'Area Successfully added to this division');
        return redirect()->back();
    }
    public function ajaxDepartmentAreas(string $id)
    {
        $arr_ids = explode(',', $id);
        $department_id = $arr_ids[0];
        $division_id = $arr_ids[1];
        $areas = Area::whereHas('division', function ($query) use (
            $department_id,
            $division_id
        ) {
            if ($department_id) {
                $query->where('department_id', $department_id);
            }
            if ($division_id) {
                $query->where('id', $division_id);
            }
        })
            ->with('owner')
            ->get();
        return [
            'data' => $areas,
            'error' => null,
        ];
    }
    public function ajaxDepartmentDivisions(int $id)
    {
        $divisions = Division::where(function ($query) use ($id) {
            if ($id) {
                $query->where('department_id', $id);
            }
        })
            ->orderBy('department_id', 'desc')
            ->with('department')
            ->with('owner')
            ->get();
        return [
            'data' => $divisions,
            'error' => null,
        ];
    }
    public function editArea(EditAreaRequest $request, int $id)
    {
        $data = $request->validated();
        $area = Area::findOrFail($id);
        $area->update($data);
        Session::flash('message', 'Area edited Successfully');
        return redirect()->back();
    }
    public function deleteArea(int $id)
    {
        $area = Area::findOrFail($id);
        $area->delete();
        Session::flash('message', 'Area deleted Successfully');
        return redirect()->back();
    }
    public function getDivisionsOfADepartment(int $id)
    {
        $divisions = Division::where('department_id', $id)->where('is_deleted',false)->get();
        return [
            'data' => $divisions,
            'error' => null,
        ];
    }
    public function getAreasOfADivision(int $id)
    {
        $areas = Area::where('division_id', $id)->where('is_deleted',false)->get();
        return [
            'data' => $areas,
            'error' => null,
        ];
    }
    public function getAreasOfADepartment(int $id)
    {
        $divisions = Division::where('department_id', $id)
            ->pluck('id')
            ->toArray();

        $areas = Area::whereIn('division_id', $divisions)->get();
        return [
            'data' => $areas,
            'error' => null,
        ];
    }
    public function viewDepartments()
    {
        //start of all font awsome icons
        $fontawsicons = [
            'fab fa-500px',
            'fab fa-accessible-icon',
            'fab fa-accusoft',
            'fas fa-address-book',
            'far fa-address-book',
            'fas fa-address-card',
            'far fa-address-card',
            'fas fa-adjust',
            'fab fa-adn',
            'fab fa-adversal',
            'fab fa-affiliatetheme',
            'fab fa-algolia',
            'fas fa-align-center',
            'fas fa-align-justify',
            'fas fa-align-left',
            'fas fa-align-right',
            'fab fa-amazon',
            'fas fa-ambulance',
            'fas fa-american-sign-language-interpreting',
            'fab fa-amilia',
            'fas fa-anchor',
            'fab fa-android',
            'fab fa-angellist',
            'fas fa-angle-double-down',
            'fas fa-angle-double-left',
            'fas fa-angle-double-right',
            'fas fa-angle-double-up',
            'fas fa-angle-down',
            'fas fa-angle-left',
            'fas fa-angle-right',
            'fas fa-angle-up',
            'fab fa-angrycreative',
            'fab fa-angular',
            'fab fa-app-store',
            'fab fa-app-store-ios',
            'fab fa-apper',
            'fab fa-apple',
            'fab fa-apple-pay',
            'fas fa-archive',
            'fas fa-arrow-alt-circle-down',
            'far fa-arrow-alt-circle-down',
            'fas fa-arrow-alt-circle-left',
            'far fa-arrow-alt-circle-left',
            'fas fa-arrow-alt-circle-right',
            'far fa-arrow-alt-circle-right',
            'fas fa-arrow-alt-circle-up',
            'far fa-arrow-alt-circle-up',
            'fas fa-arrow-circle-down',
            'fas fa-arrow-circle-left',
            'fas fa-arrow-circle-right',
            'fas fa-arrow-circle-up',
            'fas fa-arrow-down',
            'fas fa-arrow-left',
            'fas fa-arrow-right',
            'fas fa-arrow-up',
            'fas fa-arrows-alt',
            'fas fa-arrows-alt-h',
            'fas fa-arrows-alt-v',
            'fas fa-assistive-listening-systems',
            'fas fa-asterisk',
            'fab fa-asymmetrik',
            'fas fa-at',
            'fab fa-audible',
            'fas fa-audio-description',
            'fab fa-autoprefixer',
            'fab fa-avianex',
            'fab fa-aviato',
            'fab fa-aws',
            'fas fa-backward',
            'fas fa-balance-scale',
            'fas fa-ban',
            'fab fa-bandcamp',
            'fas fa-barcode',
            'fas fa-bars',
            'fas fa-bath',
            'fas fa-battery-empty',
            'fas fa-battery-full',
            'fas fa-battery-half',
            'fas fa-battery-quarter',
            'fas fa-battery-three-quarters',
            'fas fa-bed',
            'fas fa-beer',
            'fab fa-behance',
            'fab fa-behance-square',
            'fas fa-bell',
            'far fa-bell',
            'fas fa-bell-slash',
            'far fa-bell-slash',
            'fas fa-bicycle',
            'fab fa-bimobject',
            'fas fa-binoculars',
            'fas fa-birthday-cake',
            'fab fa-bitbucket',
            'fab fa-bitcoin',
            'fab fa-bity',
            'fab fa-black-tie',
            'fab fa-blackberry',
            'fas fa-blind',
            'fab fa-blogger',
            'fab fa-blogger-b',
            'fab fa-bluetooth',
            'fab fa-bluetooth-b',
            'fas fa-bold',
            'fas fa-bolt',
            'fas fa-bomb',
            'fas fa-book',
            'fas fa-bookmark',
            'far fa-bookmark',
            'fas fa-braille',
            'fas fa-briefcase',
            'fab fa-btc',
            'fas fa-bug',
            'fas fa-building',
            'far fa-building',
            'fas fa-bullhorn',
            'fas fa-bullseye',
            'fab fa-buromobelexperte',
            'fas fa-bus',
            'fab fa-buysellads',
            'fas fa-calculator',
            'fas fa-calendar',
            'far fa-calendar',
            'fas fa-calendar-alt',
            'far fa-calendar-alt',
            'fas fa-calendar-check',
            'far fa-calendar-check',
            'fas fa-calendar-minus',
            'far fa-calendar-minus',
            'fas fa-calendar-plus',
            'far fa-calendar-plus',
            'fas fa-calendar-times',
            'far fa-calendar-times',
            'fas fa-camera',
            'fas fa-camera-retro',
            'fas fa-car',
            'fas fa-caret-down',
            'fas fa-caret-left',
            'fas fa-caret-right',
            'fas fa-caret-square-down',
            'far fa-caret-square-down',
            'fas fa-caret-square-left',
            'far fa-caret-square-left',
            'fas fa-caret-square-right',
            'far fa-caret-square-right',
            'fas fa-caret-square-up',
            'far fa-caret-square-up',
            'fas fa-caret-up',
            'fas fa-cart-arrow-down',
            'fas fa-cart-plus',
            'fab fa-cc-amex',
            'fab fa-cc-apple-pay',
            'fab fa-cc-diners-club',
            'fab fa-cc-discover',
            'fab fa-cc-jcb',
            'fab fa-cc-mastercard',
            'fab fa-cc-paypal',
            'fab fa-cc-stripe',
            'fab fa-cc-visa',
            'fab fa-centercode',
            'fas fa-certificate',
            'fas fa-chart-area',
            'fas fa-chart-bar',
            'far fa-chart-bar',
            'fas fa-chart-line',
            'fas fa-chart-pie',
            'fas fa-check',
            'fas fa-check-circle',
            'far fa-check-circle',
            'fas fa-check-square',
            'far fa-check-square',
            'fas fa-chevron-circle-down',
            'fas fa-chevron-circle-left',
            'fas fa-chevron-circle-right',
            'fas fa-chevron-circle-up',
            'fas fa-chevron-down',
            'fas fa-chevron-left',
            'fas fa-chevron-right',
            'fas fa-chevron-up',
            'fas fa-child',
            'fab fa-chrome',
            'fas fa-circle',
            'far fa-circle',
            'fas fa-circle-notch',
            'fas fa-clipboard',
            'far fa-clipboard',
            'fas fa-clock',
            'far fa-clock',
            'fas fa-clone',
            'far fa-clone',
            'fas fa-closed-captioning',
            'far fa-closed-captioning',
            'fas fa-cloud',
            'fas fa-cloud-download-alt',
            'fas fa-cloud-upload-alt',
            'fab fa-cloudscale',
            'fab fa-cloudsmith',
            'fab fa-cloudversify',
            'fas fa-code',
            'fas fa-code-branch',
            'fab fa-codepen',
            'fab fa-codiepie',
            'fas fa-coffee',
            'fas fa-cog',
            'fas fa-cogs',
            'fas fa-columns',
            'fas fa-comment',
            'far fa-comment',
            'fas fa-comment-alt',
            'far fa-comment-alt',
            'fas fa-comments',
            'far fa-comments',
            'fas fa-compass',
            'far fa-compass',
            'fas fa-compress',
            'fab fa-connectdevelop',
            'fab fa-contao',
            'fas fa-copy',
            'far fa-copy',
            'fas fa-copyright',
            'far fa-copyright',
            'fab fa-cpanel',
            'fab fa-creative-commons',
            'fas fa-credit-card',
            'far fa-credit-card',
            'fas fa-crop',
            'fas fa-crosshairs',
            'fab fa-css3',
            'fab fa-css3-alt',
            'fas fa-cube',
            'fas fa-cubes',
            'fas fa-cut',
            'fab fa-cuttlefish',
            'fab fa-d-and-d',
            'fab fa-dashcube',
            'fas fa-database',
            'fas fa-deaf',
            'fab fa-delicious',
            'fab fa-deploydog',
            'fab fa-deskpro',
            'fas fa-desktop',
            'fab fa-deviantart',
            'fab fa-digg',
            'fab fa-digital-ocean',
            'fab fa-discord',
            'fab fa-discourse',
            'fab fa-dochub',
            'fab fa-docker',
            'fas fa-dollar-sign',
            'fas fa-dot-circle',
            'far fa-dot-circle',
            'fas fa-download',
            'fab fa-draft2digital',
            'fab fa-dribbble',
            'fab fa-dribbble-square',
            'fab fa-dropbox',
            'fab fa-drupal',
            'fab fa-dyalog',
            'fab fa-earlybirds',
            'fab fa-edge',
            'fas fa-edit',
            'far fa-edit',
            'fas fa-eject',
            'fas fa-ellipsis-h',
            'fas fa-ellipsis-v',
            'fab fa-ember',
            'fab fa-empire',
            'fas fa-envelope',
            'far fa-envelope',
            'fas fa-envelope-open',
            'far fa-envelope-open',
            'fas fa-envelope-square',
            'fab fa-envira',
            'fas fa-eraser',
            'fab fa-erlang',
            'fab fa-etsy',
            'fas fa-euro-sign',
            'fas fa-exchange-alt',
            'fas fa-exclamation',
            'fas fa-exclamation-circle',
            'fas fa-exclamation-triangle',
            'fas fa-expand',
            'fas fa-expand-arrows-alt',
            'fab fa-expeditedssl',
            'fas fa-external-link-alt',
            'fas fa-external-link-square-alt',
            'fas fa-eye',
            'fas fa-eye-dropper',
            'fas fa-eye-slash',
            'far fa-eye-slash',
            'fab fa-facebook',
            'fab fa-facebook-f',
            'fab fa-facebook-messenger',
            'fab fa-facebook-square',
            'fas fa-fast-backward',
            'fas fa-fast-forward',
            'fas fa-fax',
            'fas fa-female',
            'fas fa-fighter-jet',
            'fas fa-file',
            'far fa-file',
            'fas fa-file-alt',
            'far fa-file-alt',
            'fas fa-file-archive',
            'far fa-file-archive',
            'fas fa-file-audio',
            'far fa-file-audio',
            'fas fa-file-code',
            'far fa-file-code',
            'fas fa-file-excel',
            'far fa-file-excel',
            'fas fa-file-image',
            'far fa-file-image',
            'fas fa-file-pdf',
            'far fa-file-pdf',
            'fas fa-file-powerpoint',
            'far fa-file-powerpoint',
            'fas fa-file-video',
            'far fa-file-video',
            'fas fa-file-word',
            'far fa-file-word',
            'fas fa-film',
            'fas fa-filter',
            'fas fa-fire',
            'fas fa-fire-extinguisher',
            'fab fa-firefox',
            'fab fa-first-order',
            'fab fa-firstdraft',
            'fas fa-flag',
            'far fa-flag',
            'fas fa-flag-checkered',
            'fas fa-flask',
            'fab fa-flickr',
            'fab fa-fly',
            'fas fa-folder',
            'far fa-folder',
            'fas fa-folder-open',
            'far fa-folder-open',
            'fas fa-font',
            'fab fa-font-awesome',
            'fab fa-font-awesome-alt',
            'fab fa-font-awesome-flag',
            'fab fa-fonticons',
            'fab fa-fonticons-fi',
            'fab fa-fort-awesome',
            'fab fa-fort-awesome-alt',
            'fab fa-forumbee',
            'fas fa-forward',
            'fab fa-foursquare',
            'fab fa-free-code-camp',
            'fab fa-freebsd',
            'fas fa-frown',
            'far fa-frown',
            'fas fa-futbol',
            'far fa-futbol',
            'fas fa-gamepad',
            'fas fa-gavel',
            'fas fa-gem',
            'far fa-gem',
            'fas fa-genderless',
            'fab fa-get-pocket',
            'fab fa-gg',
            'fab fa-gg-circle',
            'fas fa-gift',
            'fab fa-git',
            'fab fa-git-square',
            'fab fa-github',
            'fab fa-github-alt',
            'fab fa-github-square',
            'fab fa-gitkraken',
            'fab fa-gitlab',
            'fab fa-gitter',
            'fas fa-glass-martini',
            'fab fa-glide',
            'fab fa-glide-g',
            'fas fa-globe',
            'fab fa-gofore',
            'fab fa-goodreads',
            'fab fa-goodreads-g',
            'fab fa-google',
            'fab fa-google-drive',
            'fab fa-google-play',
            'fab fa-google-plus',
            'fab fa-google-plus-g',
            'fab fa-google-plus-square',
            'fab fa-google-wallet',
            'fas fa-graduation-cap',
            'fab fa-gratipay',
            'fab fa-grav',
            'fab fa-gripfire',
            'fab fa-grunt',
            'fab fa-gulp',
            'fas fa-h-square',
            'fab fa-hacker-news',
            'fab fa-hacker-news-square',
            'fas fa-hand-lizard',
            'far fa-hand-lizard',
            'fas fa-hand-paper',
            'far fa-hand-paper',
            'fas fa-hand-peace',
            'far fa-hand-peace',
            'fas fa-hand-point-down',
            'far fa-hand-point-down',
            'fas fa-hand-point-left',
            'far fa-hand-point-left',
            'fas fa-hand-point-right',
            'far fa-hand-point-right',
            'fas fa-hand-point-up',
            'far fa-hand-point-up',
            'fas fa-hand-pointer',
            'far fa-hand-pointer',
            'fas fa-hand-rock',
            'far fa-hand-rock',
            'fas fa-hand-scissors',
            'far fa-hand-scissors',
            'fas fa-hand-spock',
            'far fa-hand-spock',
            'fas fa-handshake',
            'far fa-handshake',
            'fas fa-hashtag',
            'fas fa-hdd',
            'far fa-hdd',
            'fas fa-heading',
            'fas fa-headphones',
            'fas fa-heart',
            'far fa-heart',
            'fas fa-heartbeat',
            'fab fa-hire-a-helper',
            'fas fa-history',
            'fas fa-home',
            'fab fa-hooli',
            'fas fa-hospital',
            'far fa-hospital',
            'fab fa-hotjar',
            'fas fa-hourglass',
            'far fa-hourglass',
            'fas fa-hourglass-end',
            'fas fa-hourglass-half',
            'fas fa-hourglass-start',
            'fab fa-houzz',
            'fab fa-html5',
            'fab fa-hubspot',
            'fas fa-i-cursor',
            'fas fa-id-badge',
            'far fa-id-badge',
            'fas fa-id-card',
            'far fa-id-card',
            'fas fa-image',
            'far fa-image',
            'fas fa-images',
            'far fa-images',
            'fab fa-imdb',
            'fas fa-inbox',
            'fas fa-indent',
            'fas fa-industry',
            'fas fa-info',
            'fas fa-info-circle',
            'fab fa-instagram',
            'fab fa-internet-explorer',
            'fab fa-ioxhost',
            'fas fa-italic',
            'fab fa-itunes',
            'fab fa-itunes-note',
            'fab fa-jenkins',
            'fab fa-joget',
            'fab fa-joomla',
            'fab fa-js',
            'fab fa-js-square',
            'fab fa-jsfiddle',
            'fas fa-key',
            'fas fa-keyboard',
            'far fa-keyboard',
            'fab fa-keycdn',
            'fab fa-kickstarter',
            'fab fa-kickstarter-k',
            'fas fa-language',
            'fas fa-laptop',
            'fab fa-laravel',
            'fab fa-lastfm',
            'fab fa-lastfm-square',
            'fas fa-leaf',
            'fab fa-leanpub',
            'fas fa-lemon',
            'far fa-lemon',
            'fab fa-less',
            'fas fa-level-down-alt',
            'fas fa-level-up-alt',
            'fas fa-life-ring',
            'far fa-life-ring',
            'fas fa-lightbulb',
            'far fa-lightbulb',
            'fab fa-line',
            'fas fa-link',
            'fab fa-linkedin',
            'fab fa-linkedin-in',
            'fab fa-linode',
            'fab fa-linux',
            'fas fa-lira-sign',
            'fas fa-list',
            'fas fa-list-alt',
            'far fa-list-alt',
            'fas fa-list-ol',
            'fas fa-list-ul',
            'fas fa-location-arrow',
            'fas fa-lock',
            'fas fa-lock-open',
            'fas fa-long-arrow-alt-down',
            'fas fa-long-arrow-alt-left',
            'fas fa-long-arrow-alt-right',
            'fas fa-long-arrow-alt-up',
            'fas fa-low-vision',
            'fab fa-lyft',
            'fab fa-magento',
            'fas fa-magic',
            'fas fa-magnet',
            'fas fa-male',
            'fas fa-map',
            'far fa-map',
            'fas fa-map-marker',
            'fas fa-map-marker-alt',
            'fas fa-map-pin',
            'fas fa-map-signs',
            'fas fa-mars',
            'fas fa-mars-double',
            'fas fa-mars-stroke',
            'fas fa-mars-stroke-h',
            'fas fa-mars-stroke-v',
            'fab fa-maxcdn',
            'fab fa-medapps',
            'fab fa-medium',
            'fab fa-medium-m',
            'fas fa-medkit',
            'fab fa-medrt',
            'fab fa-meetup',
            'fas fa-meh',
            'far fa-meh',
            'fas fa-mercury',
            'fas fa-microchip',
            'fas fa-microphone',
            'fas fa-microphone-slash',
            'fab fa-microsoft',
            'fas fa-minus',
            'fas fa-minus-circle',
            'fas fa-minus-square',
            'far fa-minus-square',
            'fab fa-mix',
            'fab fa-mixcloud',
            'fab fa-mizuni',
            'fas fa-mobile',
            'fas fa-mobile-alt',
            'fab fa-modx',
            'fab fa-monero',
            'fas fa-money-bill-alt',
            'far fa-money-bill-alt',
            'fas fa-moon',
            'far fa-moon',
            'fas fa-motorcycle',
            'fas fa-mouse-pointer',
            'fas fa-music',
            'fab fa-napster',
            'fas fa-neuter',
            'fas fa-newspaper',
            'far fa-newspaper',
            'fab fa-nintendo-switch',
            'fab fa-node',
            'fab fa-node-js',
            'fab fa-npm',
            'fab fa-ns8',
            'fab fa-nutritionix',
            'fas fa-object-group',
            'far fa-object-group',
            'fas fa-object-ungroup',
            'far fa-object-ungroup',
            'fab fa-odnoklassniki',
            'fab fa-odnoklassniki-square',
            'fab fa-opencart',
            'fab fa-openid',
            'fab fa-opera',
            'fab fa-optin-monster',
            'fab fa-osi',
            'fas fa-outdent',
            'fab fa-page4',
            'fab fa-pagelines',
            'fas fa-paint-brush',
            'fab fa-palfed',
            'fas fa-paper-plane',
            'far fa-paper-plane',
            'fas fa-paperclip',
            'fas fa-paragraph',
            'fas fa-paste',
            'fab fa-patreon',
            'fas fa-pause',
            'fas fa-pause-circle',
            'far fa-pause-circle',
            'fas fa-paw',
            'fab fa-paypal',
            'fas fa-pen-square',
            'fas fa-pencil-alt',
            'fas fa-percent',
            'fab fa-periscope',
            'fab fa-phabricator',
            'fab fa-phoenix-framework',
            'fas fa-phone',
            'fas fa-phone-square',
            'fas fa-phone-volume',
            'fab fa-pied-piper',
            'fab fa-pied-piper-alt',
            'fab fa-pied-piper-pp',
            'fab fa-pinterest',
            'fab fa-pinterest-p',
            'fab fa-pinterest-square',
            'fas fa-plane',
            'fas fa-play',
            'fas fa-play-circle',
            'far fa-play-circle',
            'fab fa-playstation',
            'fas fa-plug',
            'fas fa-plus',
            'fas fa-podcast',
            'fas fa-plus-circle',
            'fas fa-plus-square',
            'far fa-plus-square',
            'fas fa-pound-sign',
            'fas fa-power-off',
            'fas fa-print',
            'fab fa-product-hunt',
            'fab fa-pushed',
            'fas fa-puzzle-piece',
            'fab fa-python',
            'fab fa-qq',
            'fas fa-qrcode',
            'fas fa-question',
            'fas fa-question-circle',
            'far fa-question-circle',
            'fab fa-quora',
            'fas fa-quote-left',
            'fas fa-quote-right',
            'fas fa-random',
            'fab fa-ravelry',
            'fab fa-react',
            'fab fa-rebel',
            'fas fa-recycle',
            'fab fa-red-river',
            'fab fa-reddit',
            'fab fa-reddit-alien',
            'fab fa-reddit-square',
            'fas fa-redo',
            'fas fa-redo-alt',
            'fas fa-registered',
            'far fa-registered',
            'fab fa-rendact',
            'fab fa-renren',
            'fas fa-reply',
            'fas fa-reply-all',
            'fab fa-replyd',
            'fab fa-resolving',
            'fas fa-retweet',
            'fas fa-road',
            'fas fa-rocket',
            'fab fa-rocketchat',
            'fab fa-rockrms',
            'fas fa-rss',
            'fas fa-rss-square',
            'fas fa-ruble-sign',
            'fas fa-rupee-sign',
            'fab fa-safari',
            'fab fa-sass',
            'fas fa-save',
            'far fa-save',
            'fab fa-schlix',
            'fab fa-scribd',
            'fas fa-search',
            'fas fa-search-minus',
            'fas fa-search-plus',
            'fab fa-searchengin',
            'fab fa-sellcast',
            'fab fa-sellsy',
            'fas fa-server',
            'fab fa-servicestack',
            'fas fa-share',
            'fas fa-share-alt',
            'fas fa-share-alt-square',
            'fas fa-share-square',
            'far fa-share-square',
            'fas fa-shekel-sign',
            'fas fa-shield-alt',
            'fas fa-ship',
            'fab fa-shirtsinbulk',
            'fas fa-shopping-bag',
            'fas fa-shopping-basket',
            'fas fa-shopping-cart',
            'fas fa-shower',
            'fas fa-sign-in-alt',
            'fas fa-sign-language',
            'fas fa-sign-out-alt',
            'fas fa-signal',
            'fab fa-simplybuilt',
            'fab fa-sistrix',
            'fas fa-sitemap',
            'fab fa-skyatlas',
            'fab fa-skype',
            'fab fa-slack',
            'fab fa-slack-hash',
            'fas fa-sliders-h',
            'fab fa-slideshare',
            'fas fa-smile',
            'far fa-smile',
            'fab fa-snapchat',
            'fab fa-snapchat-ghost',
            'fab fa-snapchat-square',
            'fas fa-snowflake',
            'far fa-snowflake',
            'fas fa-sort',
            'fas fa-sort-alpha-down',
            'fas fa-sort-alpha-up',
            'fas fa-sort-amount-down',
            'fas fa-sort-amount-up',
            'fas fa-sort-down',
            'fas fa-sort-numeric-down',
            'fas fa-sort-numeric-up',
            'fas fa-sort-up',
            'fab fa-soundcloud',
            'fas fa-space-shuttle',
            'fab fa-speakap',
            'fas fa-spinner',
            'fab fa-spotify',
            'fas fa-square',
            'far fa-square',
            'fab fa-stack-exchange',
            'fab fa-stack-overflow',
            'fas fa-star',
            'far fa-star',
            'fas fa-star-half',
            'far fa-star-half',
            'fab fa-staylinked',
            'fab fa-steam',
            'fab fa-steam-square',
            'fab fa-steam-symbol',
            'fas fa-step-backward',
            'fas fa-step-forward',
            'fas fa-stethoscope',
            'fab fa-sticker-mule',
            'fas fa-sticky-note',
            'far fa-sticky-note',
            'fas fa-stop',
            'fas fa-stop-circle',
            'far fa-stop-circle',
            'fab fa-strava',
            'fas fa-street-view',
            'fas fa-strikethrough',
            'fab fa-stripe',
            'fab fa-stripe-s',
            'fab fa-studiovinari',
            'fab fa-stumbleupon',
            'fab fa-stumbleupon-circle',
            'fas fa-subscript',
            'fas fa-subway',
            'fas fa-suitcase',
            'fas fa-sun',
            'far fa-sun',
            'fab fa-superpowers',
            'fas fa-superscript',
            'fab fa-supple',
            'fas fa-sync',
            'fas fa-sync-alt',
            'fas fa-table',
            'fas fa-tablet',
            'fas fa-tablet-alt',
            'fas fa-tachometer-alt',
            'fas fa-tag',
            'fas fa-tags',
            'fas fa-tasks',
            'fas fa-taxi',
            'fab fa-telegram',
            'fab fa-telegram-plane',
            'fab fa-tencent-weibo',
            'fas fa-terminal',
            'fas fa-text-height',
            'fas fa-text-width',
            'fas fa-th',
            'fas fa-th-large',
            'fas fa-th-list',
            'fab fa-themeisle',
            'fas fa-thermometer-empty',
            'fas fa-thermometer-full',
            'fas fa-thermometer-half',
            'fas fa-thermometer-quarter',
            'fas fa-thermometer-three-quarters',
            'fas fa-thumbs-down',
            'far fa-thumbs-down',
            'fas fa-thumbs-up',
            'far fa-thumbs-up',
            'fas fa-thumbtack',
            'fas fa-ticket-alt',
            'fas fa-times',
            'fas fa-times-circle',
            'far fa-times-circle',
            'fas fa-tint',
            'fas fa-toggle-off',
            'fas fa-toggle-on',
            'fas fa-trademark',
            'fas fa-train',
            'fas fa-transgender',
            'fas fa-transgender-alt',
            'fas fa-trash',
            'fas fa-trash-alt',
            'far fa-trash-alt',
            'fas fa-tree',
            'fab fa-trello',
            'fab fa-tripadvisor',
            'fas fa-trophy',
            'fas fa-truck',
            'fas fa-tty',
            'fab fa-tumblr',
            'fab fa-tumblr-square',
            'fas fa-tv',
            'fab fa-twitch',
            'fab fa-twitter',
            'fab fa-twitter-square',
            'fab fa-typo3',
            'fab fa-uber',
            'fab fa-uikit',
            'fas fa-umbrella',
            'fas fa-underline',
            'fas fa-undo',
            'fas fa-undo-alt',
            'fab fa-uniregistry',
            'fas fa-universal-access',
            'fas fa-university',
            'fas fa-unlink',
            'fas fa-unlock',
            'fas fa-unlock-alt',
            'fab fa-untappd',
            'fas fa-upload',
            'fab fa-usb',
            'fas fa-user',
            'far fa-user',
            'fas fa-user-circle',
            'far fa-user-circle',
            'fas fa-user-md',
            'fas fa-user-plus',
            'fas fa-user-secret',
            'fas fa-user-times',
            'fas fa-users',
            'fab fa-ussunnah',
            'fas fa-utensil-spoon',
            'fas fa-utensils',
            'fab fa-vaadin',
            'fas fa-venus',
            'fas fa-venus-double',
            'fas fa-venus-mars',
            'fab fa-viacoin',
            'fab fa-viadeo',
            'fab fa-viadeo-square',
            'fab fa-viber',
            'fas fa-video',
            'fab fa-vimeo',
            'fab fa-vimeo-square',
            'fab fa-vimeo-v',
            'fab fa-vine',
            'fab fa-vk',
            'fab fa-vnv',
            'fas fa-volume-down',
            'fas fa-volume-off',
            'fas fa-volume-up',
            'fab fa-vuejs',
            'fab fa-weibo',
            'fab fa-weixin',
            'fab fa-whatsapp',
            'fab fa-whatsapp-square',
            'fas fa-wheelchair',
            'fab fa-whmcs',
            'fas fa-wifi',
            'fab fa-wikipedia-w',
            'fas fa-window-close',
            'far fa-window-close',
            'fas fa-window-maximize',
            'far fa-window-maximize',
            'fas fa-window-minimize',
            'fas fa-window-restore',
            'far fa-window-restore',
            'fab fa-windows',
            'fas fa-won-sign',
            'fab fa-wordpress',
            'fab fa-wordpress-simple',
            'fab fa-wpbeginner',
            'fab fa-wpexplorer',
            'fab fa-wpforms',
            'fas fa-wrench',
            'fab fa-xbox',
            'fab fa-xing',
            'fab fa-xing-square',
            'fab fa-y-combinator',
            'fab fa-yahoo',
            'fab fa-yandex',
            'fab fa-yandex-international',
            'fab fa-yelp',
            'fas fa-yen-sign',
            'fab fa-yoast',
            'fab fa-youtube',
        ];

        //end of all font awsome icons

        $models = DB::table('modenadisable')->First();
        $departments = Departments::all();
        $user = User::where('is_email_verified', true)->where('approved', true)->get();
        $departmentdetails = DepartmentDetail::all();
        $scopes = Scope::all();
        return view('admin.viewDepartments')
            ->with('icons', $fontawsicons)
            ->with('model', $models)
            ->with('departments', $departments)
            ->with('departmentdetails', $departmentdetails)
            ->with('scopes', $scopes)
            ->withUser($user);
    }
    public function displaydepartmentcodeajax(Request $request)
    {
        $department_name_value = $request->input('department_name_value');
        $department_code = DepartmentDetail::where('name', $department_name_value)->pluck('code');

        return response()->json([
            'status' => 200,
            'department_code' => $department_code,
        ]);
    }

    public function savedepartmentsajax(AddDepartmentRequest $req)
    {
        $models = DB::table('modenadisable')->First();
        if ($req->input('department') == 'other') {
            $name = $req->input('department_other');
        } else {
            $name = $req->input('department');
        }

        $code = $req->input('depcode');
        $user_id = $req->input('hod');
        $hodemail = User::where('id', $user_id)->value('email');
        $hodname = $req->input('hodname');
        $hodp = $req->input('position');
        $icon = $req->input('ficon');
        $scopes = implode(',', $req->input('scope'));
        $scopes_oth = implode(',', $req->input('scope_other'));

        $departments = Departments::all();
        if ($name != null) {
            $exists = DB::table('departments')->where('departmentname', $name)->first();
            if ($exists) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Department already exists',
                ]);
            } else {
                $data = [
                    'departmentname' => $name,
                    'code' => $code,
                    'hod' => $hodemail,
                    'hodname' => $hodname,
                    'hodposition' => $hodp,
                    'scopes' => $scopes,
                    'scopes_other' => $scopes_oth,
                    'icon' => $icon,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];
                DB::table('departments')->insert($data);
                //inserting data to activities table
                $activity = 'New Department is Added with Name as ' . $name . ' with HOD ' . $hodname;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return response()->json([
                    'status' => 200,
                    'message' => 'Department added successfully',
                ]);
            }
        } else {
            return back()
                ->with('model', $models)
                ->with('departments', $departments);
        }
    }
    public function editDepartment(Request $req)
    {
        //start of all font awsome icons
        $fontawsicons = [
            'fab fa-500px',
            'fab fa-accessible-icon',
            'fab fa-accusoft',
            'fas fa-address-book',
            'far fa-address-book',
            'fas fa-address-card',
            'far fa-address-card',
            'fas fa-adjust',
            'fab fa-adn',
            'fab fa-adversal',
            'fab fa-affiliatetheme',
            'fab fa-algolia',
            'fas fa-align-center',
            'fas fa-align-justify',
            'fas fa-align-left',
            'fas fa-align-right',
            'fab fa-amazon',
            'fas fa-ambulance',
            'fas fa-american-sign-language-interpreting',
            'fab fa-amilia',
            'fas fa-anchor',
            'fab fa-android',
            'fab fa-angellist',
            'fas fa-angle-double-down',
            'fas fa-angle-double-left',
            'fas fa-angle-double-right',
            'fas fa-angle-double-up',
            'fas fa-angle-down',
            'fas fa-angle-left',
            'fas fa-angle-right',
            'fas fa-angle-up',
            'fab fa-angrycreative',
            'fab fa-angular',
            'fab fa-app-store',
            'fab fa-app-store-ios',
            'fab fa-apper',
            'fab fa-apple',
            'fab fa-apple-pay',
            'fas fa-archive',
            'fas fa-arrow-alt-circle-down',
            'far fa-arrow-alt-circle-down',
            'fas fa-arrow-alt-circle-left',
            'far fa-arrow-alt-circle-left',
            'fas fa-arrow-alt-circle-right',
            'far fa-arrow-alt-circle-right',
            'fas fa-arrow-alt-circle-up',
            'far fa-arrow-alt-circle-up',
            'fas fa-arrow-circle-down',
            'fas fa-arrow-circle-left',
            'fas fa-arrow-circle-right',
            'fas fa-arrow-circle-up',
            'fas fa-arrow-down',
            'fas fa-arrow-left',
            'fas fa-arrow-right',
            'fas fa-arrow-up',
            'fas fa-arrows-alt',
            'fas fa-arrows-alt-h',
            'fas fa-arrows-alt-v',
            'fas fa-assistive-listening-systems',
            'fas fa-asterisk',
            'fab fa-asymmetrik',
            'fas fa-at',
            'fab fa-audible',
            'fas fa-audio-description',
            'fab fa-autoprefixer',
            'fab fa-avianex',
            'fab fa-aviato',
            'fab fa-aws',
            'fas fa-backward',
            'fas fa-balance-scale',
            'fas fa-ban',
            'fab fa-bandcamp',
            'fas fa-barcode',
            'fas fa-bars',
            'fas fa-bath',
            'fas fa-battery-empty',
            'fas fa-battery-full',
            'fas fa-battery-half',
            'fas fa-battery-quarter',
            'fas fa-battery-three-quarters',
            'fas fa-bed',
            'fas fa-beer',
            'fab fa-behance',
            'fab fa-behance-square',
            'fas fa-bell',
            'far fa-bell',
            'fas fa-bell-slash',
            'far fa-bell-slash',
            'fas fa-bicycle',
            'fab fa-bimobject',
            'fas fa-binoculars',
            'fas fa-birthday-cake',
            'fab fa-bitbucket',
            'fab fa-bitcoin',
            'fab fa-bity',
            'fab fa-black-tie',
            'fab fa-blackberry',
            'fas fa-blind',
            'fab fa-blogger',
            'fab fa-blogger-b',
            'fab fa-bluetooth',
            'fab fa-bluetooth-b',
            'fas fa-bold',
            'fas fa-bolt',
            'fas fa-bomb',
            'fas fa-book',
            'fas fa-bookmark',
            'far fa-bookmark',
            'fas fa-braille',
            'fas fa-briefcase',
            'fab fa-btc',
            'fas fa-bug',
            'fas fa-building',
            'far fa-building',
            'fas fa-bullhorn',
            'fas fa-bullseye',
            'fab fa-buromobelexperte',
            'fas fa-bus',
            'fab fa-buysellads',
            'fas fa-calculator',
            'fas fa-calendar',
            'far fa-calendar',
            'fas fa-calendar-alt',
            'far fa-calendar-alt',
            'fas fa-calendar-check',
            'far fa-calendar-check',
            'fas fa-calendar-minus',
            'far fa-calendar-minus',
            'fas fa-calendar-plus',
            'far fa-calendar-plus',
            'fas fa-calendar-times',
            'far fa-calendar-times',
            'fas fa-camera',
            'fas fa-camera-retro',
            'fas fa-car',
            'fas fa-caret-down',
            'fas fa-caret-left',
            'fas fa-caret-right',
            'fas fa-caret-square-down',
            'far fa-caret-square-down',
            'fas fa-caret-square-left',
            'far fa-caret-square-left',
            'fas fa-caret-square-right',
            'far fa-caret-square-right',
            'fas fa-caret-square-up',
            'far fa-caret-square-up',
            'fas fa-caret-up',
            'fas fa-cart-arrow-down',
            'fas fa-cart-plus',
            'fab fa-cc-amex',
            'fab fa-cc-apple-pay',
            'fab fa-cc-diners-club',
            'fab fa-cc-discover',
            'fab fa-cc-jcb',
            'fab fa-cc-mastercard',
            'fab fa-cc-paypal',
            'fab fa-cc-stripe',
            'fab fa-cc-visa',
            'fab fa-centercode',
            'fas fa-certificate',
            'fas fa-chart-area',
            'fas fa-chart-bar',
            'far fa-chart-bar',
            'fas fa-chart-line',
            'fas fa-chart-pie',
            'fas fa-check',
            'fas fa-check-circle',
            'far fa-check-circle',
            'fas fa-check-square',
            'far fa-check-square',
            'fas fa-chevron-circle-down',
            'fas fa-chevron-circle-left',
            'fas fa-chevron-circle-right',
            'fas fa-chevron-circle-up',
            'fas fa-chevron-down',
            'fas fa-chevron-left',
            'fas fa-chevron-right',
            'fas fa-chevron-up',
            'fas fa-child',
            'fab fa-chrome',
            'fas fa-circle',
            'far fa-circle',
            'fas fa-circle-notch',
            'fas fa-clipboard',
            'far fa-clipboard',
            'fas fa-clock',
            'far fa-clock',
            'fas fa-clone',
            'far fa-clone',
            'fas fa-closed-captioning',
            'far fa-closed-captioning',
            'fas fa-cloud',
            'fas fa-cloud-download-alt',
            'fas fa-cloud-upload-alt',
            'fab fa-cloudscale',
            'fab fa-cloudsmith',
            'fab fa-cloudversify',
            'fas fa-code',
            'fas fa-code-branch',
            'fab fa-codepen',
            'fab fa-codiepie',
            'fas fa-coffee',
            'fas fa-cog',
            'fas fa-cogs',
            'fas fa-columns',
            'fas fa-comment',
            'far fa-comment',
            'fas fa-comment-alt',
            'far fa-comment-alt',
            'fas fa-comments',
            'far fa-comments',
            'fas fa-compass',
            'far fa-compass',
            'fas fa-compress',
            'fab fa-connectdevelop',
            'fab fa-contao',
            'fas fa-copy',
            'far fa-copy',
            'fas fa-copyright',
            'far fa-copyright',
            'fab fa-cpanel',
            'fab fa-creative-commons',
            'fas fa-credit-card',
            'far fa-credit-card',
            'fas fa-crop',
            'fas fa-crosshairs',
            'fab fa-css3',
            'fab fa-css3-alt',
            'fas fa-cube',
            'fas fa-cubes',
            'fas fa-cut',
            'fab fa-cuttlefish',
            'fab fa-d-and-d',
            'fab fa-dashcube',
            'fas fa-database',
            'fas fa-deaf',
            'fab fa-delicious',
            'fab fa-deploydog',
            'fab fa-deskpro',
            'fas fa-desktop',
            'fab fa-deviantart',
            'fab fa-digg',
            'fab fa-digital-ocean',
            'fab fa-discord',
            'fab fa-discourse',
            'fab fa-dochub',
            'fab fa-docker',
            'fas fa-dollar-sign',
            'fas fa-dot-circle',
            'far fa-dot-circle',
            'fas fa-download',
            'fab fa-draft2digital',
            'fab fa-dribbble',
            'fab fa-dribbble-square',
            'fab fa-dropbox',
            'fab fa-drupal',
            'fab fa-dyalog',
            'fab fa-earlybirds',
            'fab fa-edge',
            'fas fa-edit',
            'far fa-edit',
            'fas fa-eject',
            'fas fa-ellipsis-h',
            'fas fa-ellipsis-v',
            'fab fa-ember',
            'fab fa-empire',
            'fas fa-envelope',
            'far fa-envelope',
            'fas fa-envelope-open',
            'far fa-envelope-open',
            'fas fa-envelope-square',
            'fab fa-envira',
            'fas fa-eraser',
            'fab fa-erlang',
            'fab fa-etsy',
            'fas fa-euro-sign',
            'fas fa-exchange-alt',
            'fas fa-exclamation',
            'fas fa-exclamation-circle',
            'fas fa-exclamation-triangle',
            'fas fa-expand',
            'fas fa-expand-arrows-alt',
            'fab fa-expeditedssl',
            'fas fa-external-link-alt',
            'fas fa-external-link-square-alt',
            'fas fa-eye',
            'fas fa-eye-dropper',
            'fas fa-eye-slash',
            'far fa-eye-slash',
            'fab fa-facebook',
            'fab fa-facebook-f',
            'fab fa-facebook-messenger',
            'fab fa-facebook-square',
            'fas fa-fast-backward',
            'fas fa-fast-forward',
            'fas fa-fax',
            'fas fa-female',
            'fas fa-fighter-jet',
            'fas fa-file',
            'far fa-file',
            'fas fa-file-alt',
            'far fa-file-alt',
            'fas fa-file-archive',
            'far fa-file-archive',
            'fas fa-file-audio',
            'far fa-file-audio',
            'fas fa-file-code',
            'far fa-file-code',
            'fas fa-file-excel',
            'far fa-file-excel',
            'fas fa-file-image',
            'far fa-file-image',
            'fas fa-file-pdf',
            'far fa-file-pdf',
            'fas fa-file-powerpoint',
            'far fa-file-powerpoint',
            'fas fa-file-video',
            'far fa-file-video',
            'fas fa-file-word',
            'far fa-file-word',
            'fas fa-film',
            'fas fa-filter',
            'fas fa-fire',
            'fas fa-fire-extinguisher',
            'fab fa-firefox',
            'fab fa-first-order',
            'fab fa-firstdraft',
            'fas fa-flag',
            'far fa-flag',
            'fas fa-flag-checkered',
            'fas fa-flask',
            'fab fa-flickr',
            'fab fa-fly',
            'fas fa-folder',
            'far fa-folder',
            'fas fa-folder-open',
            'far fa-folder-open',
            'fas fa-font',
            'fab fa-font-awesome',
            'fab fa-font-awesome-alt',
            'fab fa-font-awesome-flag',
            'fab fa-fonticons',
            'fab fa-fonticons-fi',
            'fab fa-fort-awesome',
            'fab fa-fort-awesome-alt',
            'fab fa-forumbee',
            'fas fa-forward',
            'fab fa-foursquare',
            'fab fa-free-code-camp',
            'fab fa-freebsd',
            'fas fa-frown',
            'far fa-frown',
            'fas fa-futbol',
            'far fa-futbol',
            'fas fa-gamepad',
            'fas fa-gavel',
            'fas fa-gem',
            'far fa-gem',
            'fas fa-genderless',
            'fab fa-get-pocket',
            'fab fa-gg',
            'fab fa-gg-circle',
            'fas fa-gift',
            'fab fa-git',
            'fab fa-git-square',
            'fab fa-github',
            'fab fa-github-alt',
            'fab fa-github-square',
            'fab fa-gitkraken',
            'fab fa-gitlab',
            'fab fa-gitter',
            'fas fa-glass-martini',
            'fab fa-glide',
            'fab fa-glide-g',
            'fas fa-globe',
            'fab fa-gofore',
            'fab fa-goodreads',
            'fab fa-goodreads-g',
            'fab fa-google',
            'fab fa-google-drive',
            'fab fa-google-play',
            'fab fa-google-plus',
            'fab fa-google-plus-g',
            'fab fa-google-plus-square',
            'fab fa-google-wallet',
            'fas fa-graduation-cap',
            'fab fa-gratipay',
            'fab fa-grav',
            'fab fa-gripfire',
            'fab fa-grunt',
            'fab fa-gulp',
            'fas fa-h-square',
            'fab fa-hacker-news',
            'fab fa-hacker-news-square',
            'fas fa-hand-lizard',
            'far fa-hand-lizard',
            'fas fa-hand-paper',
            'far fa-hand-paper',
            'fas fa-hand-peace',
            'far fa-hand-peace',
            'fas fa-hand-point-down',
            'far fa-hand-point-down',
            'fas fa-hand-point-left',
            'far fa-hand-point-left',
            'fas fa-hand-point-right',
            'far fa-hand-point-right',
            'fas fa-hand-point-up',
            'far fa-hand-point-up',
            'fas fa-hand-pointer',
            'far fa-hand-pointer',
            'fas fa-hand-rock',
            'far fa-hand-rock',
            'fas fa-hand-scissors',
            'far fa-hand-scissors',
            'fas fa-hand-spock',
            'far fa-hand-spock',
            'fas fa-handshake',
            'far fa-handshake',
            'fas fa-hashtag',
            'fas fa-hdd',
            'far fa-hdd',
            'fas fa-heading',
            'fas fa-headphones',
            'fas fa-heart',
            'far fa-heart',
            'fas fa-heartbeat',
            'fab fa-hire-a-helper',
            'fas fa-history',
            'fas fa-home',
            'fab fa-hooli',
            'fas fa-hospital',
            'far fa-hospital',
            'fab fa-hotjar',
            'fas fa-hourglass',
            'far fa-hourglass',
            'fas fa-hourglass-end',
            'fas fa-hourglass-half',
            'fas fa-hourglass-start',
            'fab fa-houzz',
            'fab fa-html5',
            'fab fa-hubspot',
            'fas fa-i-cursor',
            'fas fa-id-badge',
            'far fa-id-badge',
            'fas fa-id-card',
            'far fa-id-card',
            'fas fa-image',
            'far fa-image',
            'fas fa-images',
            'far fa-images',
            'fab fa-imdb',
            'fas fa-inbox',
            'fas fa-indent',
            'fas fa-industry',
            'fas fa-info',
            'fas fa-info-circle',
            'fab fa-instagram',
            'fab fa-internet-explorer',
            'fab fa-ioxhost',
            'fas fa-italic',
            'fab fa-itunes',
            'fab fa-itunes-note',
            'fab fa-jenkins',
            'fab fa-joget',
            'fab fa-joomla',
            'fab fa-js',
            'fab fa-js-square',
            'fab fa-jsfiddle',
            'fas fa-key',
            'fas fa-keyboard',
            'far fa-keyboard',
            'fab fa-keycdn',
            'fab fa-kickstarter',
            'fab fa-kickstarter-k',
            'fas fa-language',
            'fas fa-laptop',
            'fab fa-laravel',
            'fab fa-lastfm',
            'fab fa-lastfm-square',
            'fas fa-leaf',
            'fab fa-leanpub',
            'fas fa-lemon',
            'far fa-lemon',
            'fab fa-less',
            'fas fa-level-down-alt',
            'fas fa-level-up-alt',
            'fas fa-life-ring',
            'far fa-life-ring',
            'fas fa-lightbulb',
            'far fa-lightbulb',
            'fab fa-line',
            'fas fa-link',
            'fab fa-linkedin',
            'fab fa-linkedin-in',
            'fab fa-linode',
            'fab fa-linux',
            'fas fa-lira-sign',
            'fas fa-list',
            'fas fa-list-alt',
            'far fa-list-alt',
            'fas fa-list-ol',
            'fas fa-list-ul',
            'fas fa-location-arrow',
            'fas fa-lock',
            'fas fa-lock-open',
            'fas fa-long-arrow-alt-down',
            'fas fa-long-arrow-alt-left',
            'fas fa-long-arrow-alt-right',
            'fas fa-long-arrow-alt-up',
            'fas fa-low-vision',
            'fab fa-lyft',
            'fab fa-magento',
            'fas fa-magic',
            'fas fa-magnet',
            'fas fa-male',
            'fas fa-map',
            'far fa-map',
            'fas fa-map-marker',
            'fas fa-map-marker-alt',
            'fas fa-map-pin',
            'fas fa-map-signs',
            'fas fa-mars',
            'fas fa-mars-double',
            'fas fa-mars-stroke',
            'fas fa-mars-stroke-h',
            'fas fa-mars-stroke-v',
            'fab fa-maxcdn',
            'fab fa-medapps',
            'fab fa-medium',
            'fab fa-medium-m',
            'fas fa-medkit',
            'fab fa-medrt',
            'fab fa-meetup',
            'fas fa-meh',
            'far fa-meh',
            'fas fa-mercury',
            'fas fa-microchip',
            'fas fa-microphone',
            'fas fa-microphone-slash',
            'fab fa-microsoft',
            'fas fa-minus',
            'fas fa-minus-circle',
            'fas fa-minus-square',
            'far fa-minus-square',
            'fab fa-mix',
            'fab fa-mixcloud',
            'fab fa-mizuni',
            'fas fa-mobile',
            'fas fa-mobile-alt',
            'fab fa-modx',
            'fab fa-monero',
            'fas fa-money-bill-alt',
            'far fa-money-bill-alt',
            'fas fa-moon',
            'far fa-moon',
            'fas fa-motorcycle',
            'fas fa-mouse-pointer',
            'fas fa-music',
            'fab fa-napster',
            'fas fa-neuter',
            'fas fa-newspaper',
            'far fa-newspaper',
            'fab fa-nintendo-switch',
            'fab fa-node',
            'fab fa-node-js',
            'fab fa-npm',
            'fab fa-ns8',
            'fab fa-nutritionix',
            'fas fa-object-group',
            'far fa-object-group',
            'fas fa-object-ungroup',
            'far fa-object-ungroup',
            'fab fa-odnoklassniki',
            'fab fa-odnoklassniki-square',
            'fab fa-opencart',
            'fab fa-openid',
            'fab fa-opera',
            'fab fa-optin-monster',
            'fab fa-osi',
            'fas fa-outdent',
            'fab fa-page4',
            'fab fa-pagelines',
            'fas fa-paint-brush',
            'fab fa-palfed',
            'fas fa-paper-plane',
            'far fa-paper-plane',
            'fas fa-paperclip',
            'fas fa-paragraph',
            'fas fa-paste',
            'fab fa-patreon',
            'fas fa-pause',
            'fas fa-pause-circle',
            'far fa-pause-circle',
            'fas fa-paw',
            'fab fa-paypal',
            'fas fa-pen-square',
            'fas fa-pencil-alt',
            'fas fa-percent',
            'fab fa-periscope',
            'fab fa-phabricator',
            'fab fa-phoenix-framework',
            'fas fa-phone',
            'fas fa-phone-square',
            'fas fa-phone-volume',
            'fab fa-pied-piper',
            'fab fa-pied-piper-alt',
            'fab fa-pied-piper-pp',
            'fab fa-pinterest',
            'fab fa-pinterest-p',
            'fab fa-pinterest-square',
            'fas fa-plane',
            'fas fa-play',
            'fas fa-play-circle',
            'far fa-play-circle',
            'fab fa-playstation',
            'fas fa-plug',
            'fas fa-plus',
            'fas fa-podcast',
            'fas fa-plus-circle',
            'fas fa-plus-square',
            'far fa-plus-square',
            'fas fa-pound-sign',
            'fas fa-power-off',
            'fas fa-print',
            'fab fa-product-hunt',
            'fab fa-pushed',
            'fas fa-puzzle-piece',
            'fab fa-python',
            'fab fa-qq',
            'fas fa-qrcode',
            'fas fa-question',
            'fas fa-question-circle',
            'far fa-question-circle',
            'fab fa-quora',
            'fas fa-quote-left',
            'fas fa-quote-right',
            'fas fa-random',
            'fab fa-ravelry',
            'fab fa-react',
            'fab fa-rebel',
            'fas fa-recycle',
            'fab fa-red-river',
            'fab fa-reddit',
            'fab fa-reddit-alien',
            'fab fa-reddit-square',
            'fas fa-redo',
            'fas fa-redo-alt',
            'fas fa-registered',
            'far fa-registered',
            'fab fa-rendact',
            'fab fa-renren',
            'fas fa-reply',
            'fas fa-reply-all',
            'fab fa-replyd',
            'fab fa-resolving',
            'fas fa-retweet',
            'fas fa-road',
            'fas fa-rocket',
            'fab fa-rocketchat',
            'fab fa-rockrms',
            'fas fa-rss',
            'fas fa-rss-square',
            'fas fa-ruble-sign',
            'fas fa-rupee-sign',
            'fab fa-safari',
            'fab fa-sass',
            'fas fa-save',
            'far fa-save',
            'fab fa-schlix',
            'fab fa-scribd',
            'fas fa-search',
            'fas fa-search-minus',
            'fas fa-search-plus',
            'fab fa-searchengin',
            'fab fa-sellcast',
            'fab fa-sellsy',
            'fas fa-server',
            'fab fa-servicestack',
            'fas fa-share',
            'fas fa-share-alt',
            'fas fa-share-alt-square',
            'fas fa-share-square',
            'far fa-share-square',
            'fas fa-shekel-sign',
            'fas fa-shield-alt',
            'fas fa-ship',
            'fab fa-shirtsinbulk',
            'fas fa-shopping-bag',
            'fas fa-shopping-basket',
            'fas fa-shopping-cart',
            'fas fa-shower',
            'fas fa-sign-in-alt',
            'fas fa-sign-language',
            'fas fa-sign-out-alt',
            'fas fa-signal',
            'fab fa-simplybuilt',
            'fab fa-sistrix',
            'fas fa-sitemap',
            'fab fa-skyatlas',
            'fab fa-skype',
            'fab fa-slack',
            'fab fa-slack-hash',
            'fas fa-sliders-h',
            'fab fa-slideshare',
            'fas fa-smile',
            'far fa-smile',
            'fab fa-snapchat',
            'fab fa-snapchat-ghost',
            'fab fa-snapchat-square',
            'fas fa-snowflake',
            'far fa-snowflake',
            'fas fa-sort',
            'fas fa-sort-alpha-down',
            'fas fa-sort-alpha-up',
            'fas fa-sort-amount-down',
            'fas fa-sort-amount-up',
            'fas fa-sort-down',
            'fas fa-sort-numeric-down',
            'fas fa-sort-numeric-up',
            'fas fa-sort-up',
            'fab fa-soundcloud',
            'fas fa-space-shuttle',
            'fab fa-speakap',
            'fas fa-spinner',
            'fab fa-spotify',
            'fas fa-square',
            'far fa-square',
            'fab fa-stack-exchange',
            'fab fa-stack-overflow',
            'fas fa-star',
            'far fa-star',
            'fas fa-star-half',
            'far fa-star-half',
            'fab fa-staylinked',
            'fab fa-steam',
            'fab fa-steam-square',
            'fab fa-steam-symbol',
            'fas fa-step-backward',
            'fas fa-step-forward',
            'fas fa-stethoscope',
            'fab fa-sticker-mule',
            'fas fa-sticky-note',
            'far fa-sticky-note',
            'fas fa-stop',
            'fas fa-stop-circle',
            'far fa-stop-circle',
            'fab fa-strava',
            'fas fa-street-view',
            'fas fa-strikethrough',
            'fab fa-stripe',
            'fab fa-stripe-s',
            'fab fa-studiovinari',
            'fab fa-stumbleupon',
            'fab fa-stumbleupon-circle',
            'fas fa-subscript',
            'fas fa-subway',
            'fas fa-suitcase',
            'fas fa-sun',
            'far fa-sun',
            'fab fa-superpowers',
            'fas fa-superscript',
            'fab fa-supple',
            'fas fa-sync',
            'fas fa-sync-alt',
            'fas fa-table',
            'fas fa-tablet',
            'fas fa-tablet-alt',
            'fas fa-tachometer-alt',
            'fas fa-tag',
            'fas fa-tags',
            'fas fa-tasks',
            'fas fa-taxi',
            'fab fa-telegram',
            'fab fa-telegram-plane',
            'fab fa-tencent-weibo',
            'fas fa-terminal',
            'fas fa-text-height',
            'fas fa-text-width',
            'fas fa-th',
            'fas fa-th-large',
            'fas fa-th-list',
            'fab fa-themeisle',
            'fas fa-thermometer-empty',
            'fas fa-thermometer-full',
            'fas fa-thermometer-half',
            'fas fa-thermometer-quarter',
            'fas fa-thermometer-three-quarters',
            'fas fa-thumbs-down',
            'far fa-thumbs-down',
            'fas fa-thumbs-up',
            'far fa-thumbs-up',
            'fas fa-thumbtack',
            'fas fa-ticket-alt',
            'fas fa-times',
            'fas fa-times-circle',
            'far fa-times-circle',
            'fas fa-tint',
            'fas fa-toggle-off',
            'fas fa-toggle-on',
            'fas fa-trademark',
            'fas fa-train',
            'fas fa-transgender',
            'fas fa-transgender-alt',
            'fas fa-trash',
            'fas fa-trash-alt',
            'far fa-trash-alt',
            'fas fa-tree',
            'fab fa-trello',
            'fab fa-tripadvisor',
            'fas fa-trophy',
            'fas fa-truck',
            'fas fa-tty',
            'fab fa-tumblr',
            'fab fa-tumblr-square',
            'fas fa-tv',
            'fab fa-twitch',
            'fab fa-twitter',
            'fab fa-twitter-square',
            'fab fa-typo3',
            'fab fa-uber',
            'fab fa-uikit',
            'fas fa-umbrella',
            'fas fa-underline',
            'fas fa-undo',
            'fas fa-undo-alt',
            'fab fa-uniregistry',
            'fas fa-universal-access',
            'fas fa-university',
            'fas fa-unlink',
            'fas fa-unlock',
            'fas fa-unlock-alt',
            'fab fa-untappd',
            'fas fa-upload',
            'fab fa-usb',
            'fas fa-user',
            'far fa-user',
            'fas fa-user-circle',
            'far fa-user-circle',
            'fas fa-user-md',
            'fas fa-user-plus',
            'fas fa-user-secret',
            'fas fa-user-times',
            'fas fa-users',
            'fab fa-ussunnah',
            'fas fa-utensil-spoon',
            'fas fa-utensils',
            'fab fa-vaadin',
            'fas fa-venus',
            'fas fa-venus-double',
            'fas fa-venus-mars',
            'fab fa-viacoin',
            'fab fa-viadeo',
            'fab fa-viadeo-square',
            'fab fa-viber',
            'fas fa-video',
            'fab fa-vimeo',
            'fab fa-vimeo-square',
            'fab fa-vimeo-v',
            'fab fa-vine',
            'fab fa-vk',
            'fab fa-vnv',
            'fas fa-volume-down',
            'fas fa-volume-off',
            'fas fa-volume-up',
            'fab fa-vuejs',
            'fab fa-weibo',
            'fab fa-weixin',
            'fab fa-whatsapp',
            'fab fa-whatsapp-square',
            'fas fa-wheelchair',
            'fab fa-whmcs',
            'fas fa-wifi',
            'fab fa-wikipedia-w',
            'fas fa-window-close',
            'far fa-window-close',
            'fas fa-window-maximize',
            'far fa-window-maximize',
            'fas fa-window-minimize',
            'fas fa-window-restore',
            'far fa-window-restore',
            'fab fa-windows',
            'fas fa-won-sign',
            'fab fa-wordpress',
            'fab fa-wordpress-simple',
            'fab fa-wpbeginner',
            'fab fa-wpexplorer',
            'fab fa-wpforms',
            'fas fa-wrench',
            'fab fa-xbox',
            'fab fa-xing',
            'fab fa-xing-square',
            'fab fa-y-combinator',
            'fab fa-yahoo',
            'fab fa-yandex',
            'fab fa-yandex-international',
            'fab fa-yelp',
            'fas fa-yen-sign',
            'fab fa-yoast',
            'fab fa-youtube',
        ];

        //end of all font awsome icons

        $curicon = DB::table('departments')
            ->where('departmentname', request()->get('dept'))
            ->pluck('icon');
        $models = DB::table('modenadisable')->First();

        if (isset($_GET['val'])) {
            // $users = User::all();
            $users = User::where('approved',true)->get();
            $departments = DB::select(
                'select distinct departmentname from departments'
            );
            // dd($curicon[0]);
            $id = $_GET['val'];
            $datas = DB::table('departments')->where('id', $id)->get();
            $hodasst = DB::table('departments')->where('id', $id)->value('hod_assistants');
            $isoclauses = DB::table('departments')->where('id', $id)->value('isoclauses');
            $scopes = Scope::all();
            $depscopes = DB::table('departments')->where('id', $id)->value('scopes');
            if ($depscopes == 'other') {
                $scope_other = DB::table('departments')->where('id', $id)->value('scopes_other');
            } else {
                $scope_other = null;
            }
            return view('admin.editDepartment')
                ->with('datas', $datas)
                ->with('isoclauses', $isoclauses)
                ->with('icons', $fontawsicons)
                ->with('model', $models)
                ->with('curicon', $curicon[0])
                ->with('departments', $departments)
                ->with('users', $users)
                ->with('hodasst', $hodasst)
                ->with('scopes', $scopes)
                ->with('depscopes', $depscopes)
                ->with('scope_other', $scope_other)
                ->with('hodname', $datas[0]->hodname);
        }
    }
    public function updateDepartment(UpdateDepartmentRequest $req)
    {
        $models = DB::table('modenadisable')->First();
        $dname = $req->input('dept1');
        $code = $req->input('code');
        // $isoclauses = $req->input('isoclauses');
        $id = $req->input('id');
        $hodname = $req->input('changehod');
        $hodemail = User::where('id', $req->input('dept'))->value('email');
        $hodpos = $req->input('hodposition');
        $oldemail = $req->input('oldemail');
        //$hodemail = $req->input('hodemail');
        $newicon = $req->input('ficon');
        if ($newicon != null) {
            $ficon = $req->input('ficon');
        } else {
            $ficon = $req->input('cicon');
        }

        if ($req->asst != null) {
            $assist[] = $req->asst;

            foreach ($assist as $ast) {
                $assistants = implode(',', $ast);
            }
        } else {
            $assistants = null;
        }

        $scopes = implode(',', $req->input('scope'));
        $scopes_oth = implode(',', $req->input('scope_other'));

        //privilige table updating code begins
        $Complaintregister = Complaintregister::where(
            'email',
            '=',
            $oldemail
        )->get();
        $Complaintreviewer = Complaintreviewer::where(
            'email',
            '=',
            $oldemail
        )->get();
        $Notifier = Notifier::where('email', '=', $oldemail)->get();
        $Representative = Representative::where('email', '=', $oldemail)->get();
        $Riskreviewer = Riskreviewer::where('email', '=', $oldemail)->get();
        $Mainreviewer = Mainreviewer::where('email', '=', $oldemail)->get();
        $Mainelectrical = Mainelectrical::where('email', '=', $oldemail)->get();
        $Maininstrument = Maininstrument::where('email', '=', $oldemail)->get();
        $Issuereviewer = Issuereviewer::where('email', '=', $oldemail)->get();
        $Legalcoordinator = Legalcoordinator::where(
            'email',
            '=',
            $oldemail
        )->get();
        $Health_Safety = Hse::where('email', '=', $oldemail)->get();
        $Escalateriskreceiver = Escalateriskreceiver::where(
            'email',
            '=',
            $oldemail
        )->get();
        $archiveprivilege = Archiveprivilege::where(
            'email',
            '=',
            $oldemail
        )->get();
        $Closurereviewer = closurereviewer::where(
            'email',
            '=',
            $oldemail
        )->get();
        $envireviewer = Envireviewer::where('email', '=', $oldemail)->get();
        $Doccontroller = Doccontroller::where('email', '=', $oldemail)->get();
        $Internalaudit = Internalaudit::where('email', '=', $oldemail)->get();
        $supplierapprovers = Supplierapprovers::where(
            'email',
            '=',
            $oldemail
        )->get();
        $trainingcoordinators = Trainingcoordinators::where(
            'email',
            '=',
            $oldemail
        )->get();
        $wastereviewer = Wastereviewer::where('email', '=', $oldemail)->get();
        $wasteapprover = Wasteapprover::where('email', '=', $oldemail)->get();
        $Incidentreviewers = DB::table('incident_reviewer')
            ->where('email', '=', $oldemail)
            ->get();
        $incidentapprover = DB::table('incident_approvers')
            ->where('email', '=', $oldemail)
            ->get();
        $compdoctors = DB::table('company_doctors')
            ->where('email', '=', $oldemail)
            ->get();
        $Nearmissreviewer = DB::table('nears')
            ->where('email', '=', $oldemail)
            ->get();

        Dcr::where('department', $id)->update([
            'hod' => $hodemail,
        ]);
        Masterdoc::where('department', $id)->update([
            'hod' => $hodemail,
        ]);

        if ($supplierapprovers) {
            DB::update(
                'update supplierapprovers set name = :hname ,email=:hmail where email = :hm',
                ['hname' => $hodname, 'hmail' => $hodemail, 'hm' => $oldemail]
            );
        }
        if ($Complaintregister) {
            $found = ComplaintRegister::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update complaintregisters set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Complaintreviewer) {
            $found = Complaintreviewer::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update complaintreviewers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Notifier) {
            $found = Notifier::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update notifiers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Representative) {
            $found = Representative::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update representatives set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Riskreviewer) {
            $found = Riskreviewer::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update riskreviewers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Mainreviewer) {
            DB::update(
                'update mainreviewers set name = :hname ,email=:hmail where email = :hm',
                ['hname' => $hodname, 'hmail' => $hodemail, 'hm' => $oldemail]
            );
        }
        if ($Mainelectrical) {
            DB::update(
                'update mainelectricals set name = :hname ,email=:hmail where email = :hm',
                ['hname' => $hodname, 'hmail' => $hodemail, 'hm' => $oldemail]
            );
        }
        if ($Maininstrument) {
            DB::update(
                'update maininstruments set name = :hname ,email=:hmail where email = :hm',
                ['hname' => $hodname, 'hmail' => $hodemail, 'hm' => $oldemail]
            );
        }
        if ($Nearmissreviewer) {
            $found = Near::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update nears set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Issuereviewer) {
            $found = Issuereviewer::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update issuereviewers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Legalcoordinator) {
            $found = Legalcoordinator::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update legalcoordinators set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Health_Safety) {
            $found = Hse::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update hses set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Escalateriskreceiver) {
            $found = Escalateriskreceiver::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update escalateriskreceivers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($archiveprivilege) {
            $found = Archiveprivilege::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update archiveprivileges set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Closurereviewer) {
            $found = Closurereviewer::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update closurereviewers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($envireviewer) {
            $found = Envireviewer::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update envireviewers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Doccontroller) {
            $found = Doccontroller::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update doccontrollers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Internalaudit) {
            $found = Internalaudit::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update internalaudits set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($trainingcoordinators) {
            $found = Trainingcoordinators::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update trainingcoordinators set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($wastereviewer) {
            $found = Wastereviewer::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update wastereviewers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($wasteapprover) {
            $found = Wasteapprover::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update wasteapprovers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($Incidentreviewers) {
            $found = IncidentReviewer::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update incident_reviewer set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($incidentapprover) {
            $found = IncidentApprover::where('email', $hodemail)->first();
            if (!$found) {
                DB::update(
                    'update incident_approvers set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }
        if ($compdoctors) {
            $found = DB::table('company_doctors')
                ->where('email', $hodemail)
                ->first();
            if (!$found) {
                DB::update(
                    'update company_doctors set name = :hname ,email=:hmail where email = :hm',
                    [
                        'hname' => $hodname,
                        'hmail' => $hodemail,
                        'hm' => $oldemail,
                    ]
                );
            }
        }

        //privilige table updating code ends
        $depid = DB::select('select * from departments where id = :eid', [
            'eid' => $id,
        ]);
        foreach ($depid as $row) {
            $dep1id = (string) $row->id;
            if ($dep1id == $id || $depid == null) {
                DB::update(
                    'update departments set departmentname = :ename ,code=:dcode,hodname=:ehod,
                      hod_assistants=:eassist,hod=:ehodemail,hodposition=:epos,
                      icon=:ficon,scopes=:escopes,scopes_other=:escopes_other
                      where id = :eid',
                    [
                        'ename' => $dname,
                        'dcode' => $code,
                        'ehod' => $hodname,
                        'eassist' => $assistants,
                        'ehodemail' => $hodemail,
                        'epos' => $hodpos,
                        'ficon' => $ficon,
                        'escopes' => $scopes,
                        'escopes_other' => $scopes_oth,
                        'eid' => $id,
                    ]
                );
                return redirect('/admin/viewDepartments')->with(
                    'model',
                    $models
                );
            } else {
                return redirect('/admin/viewDepartments')->with(
                    'model',
                    $models
                );
            }
        }
        if ($depid == null) {
            DB::update(
                'update departments set departmentname = :ename ,code=:dcode,hodname=:ehod,
                  hod_assistants=:eassist,hod=:ehodemail,hodposition=:epos,
                  scopes=:escopes,scopes_other=:escopes_other
                  where id = :eid',
                [
                    'ename' => $dname,
                    'dcode' => $code,
                    'ehod' => $hodname,
                    'eassist' => $assistants,
                    'ehodemail' => $hodemail,
                    'epos' => $hodpos,
                    'escopes' => $scopes,
                    'escopes_other' => $scopes_oth,
                    'eid' => $id,
                ]
            );
            return redirect('/admin/viewDepartments')->with('model', $models);
        }
    }
    public function CarryDepartmentID(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            return $this->removeDepartment($id)->with('model', $models);
        }
    }
    public function removeDepartment($id)
    {
        $dept = Departments::all();
        $models = DB::table('modenadisable')->First();

        //inserting data to activities table
        $hodn = DB::table('departments')
            ->where('id', $id)
            ->pluck('hodname');
        $name = DB::table('departments')
            ->where('id', $id)
            ->pluck('departmentname');
        $activity =
            'Department is Deleted with Name as ' .
            $name .
            ' with HOD ' .
            $hodn;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);

        DB::delete('delete from departments where id = :eid', ['eid' => $id]);

        return redirect('/admin/viewDepartments')
            ->with('model', $models)
            ->with('messagedeldept', 'Department deleted successfully')
            ->with('dept', $dept);
    }

    /**--------department page division and subdivision details starts---------- */
    public function selectdivisiondetails(Request $request)
    {
        $depts_id = $request->input('depts_id_value');
        $divisions = Division::with('owner')->where('department_id', $depts_id)->get();
        return response()->json([
            'status' => 200,
            'divisions' => $divisions,

        ]);
    }

    public function selectsubdivisiondetails(Request $request)
    {
        $division_id = $request->input('division_id_value');
        $subdivisions = Area::with('owner')->where('division_id', $division_id)->get();

        return response()->json([
            'status' => 200,
            'subdivisions' => $subdivisions,
        ]);
    }

    public function geteditdivisiondetails(Request $request)
    {
        $division_id = $request->input('divisionid_value');
        $dividata = Division::where('id', $division_id)->first();
        $deptname = DB::table('departments')->where('id', $dividata->department_id)->value('departmentname');
        $users = User::all();

        return response()->json([
            'status' => 200,
            'dividata' => $dividata,
            'deptname' => $deptname,
            'users' => $users,
        ]);
    }

    public function updatedeptdivisionajax(EditDivisionRequest $request)
    {
        $division_id = $request->input('division_main_id');
        Division::where('id', $division_id)->update([
            'name' => $request->input('division_name_value'),
            'owner_id' => $request->input('division_owner'),
        ]);

        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }

    public function deletedeptdivisions($id)
    {
        Division::where('id', $id)->update([
            'is_deleted' => 1,
            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $models = DB::table('modenadisable')->First();
        return redirect('/admin/viewDepartments')
            ->with('message', 'Division deleted successfully')
            ->with('model', $models);
    }

    public function insertdeptsubdivisionajax(AddSubdivisionRequest $request)
    {
        $data = $request->validated();
        $division_id = $request->input('divi_main_id');
        $check = Area::where('name', $request->input('sub_division_name_value'))
            ->where('division_id', $division_id)
            ->first();
        if ($check) {
            return response()->json([
                'status' => 403,
                'data' => null,
            ]);
        } else {
            $data = array(
                'name' => $request->input('sub_division_name_value'),
                'owner_id' => $request->input('division_owner'),
                'division_id' => $division_id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );
            $area = Area::create($data);

            //inserting data into division_areas table
            $div_area = new DivisionArea;
            $div_area->division_id = $division_id;
            $div_area->area_id = $area->id;
            $div_area->save();

            return response()->json([
                'status' => 200,
                'data' => null,
            ]);
        }

    }

    public function geteditsubdivisiondetails(Request $request)
    {
        $sub_division_id = $request->input('subdivisionid_value');
        $subdividata = Area::where('id', $sub_division_id)->first();
        $main_division = Division::where('id', $subdividata->division_id)->first();
        $division_name = $main_division->name;
        $deptname = DB::table('departments')->where('id', $main_division->department_id)->value('departmentname');
        $users = User::all();

        return response()->json([
            'status' => 200,
            'subdividata' => $subdividata,
            'division_name' => $division_name,
            'division_id' => $main_division->id,
            'deptname' => $deptname,
            'users' => $users,
        ]);
    }

    public function updatedeptsubdivisionajax(EditSubdivisionRequest $request)
    {
        $sub_division_id = $request->input('sub_division_main_id');
        Area::where('id', $sub_division_id)->update([
            'name' => $request->input('sub_divi_name_value'),
            'owner_id' => $request->input('sub_division_owner'),
        ]);
        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }

    public function deletedeptsubdivisions($id)
    {
        Area::where('id', $id)->update([
            'is_deleted' => 1,
            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $models = DB::table('modenadisable')->First();
        return redirect('/admin/viewDepartments')
            ->with('message', 'Subdivision deleted successfully')
            ->with('model', $models);
    }
    /**--------department page division and subdivision details ends---------- */

    /**--------department page location details starts---------- */
    public function selectlocationdetails(Request $request)
    {
        $depts_id = $request->input('depts_id_value');
        $locations = Location::where('locationable_id', $depts_id)
            ->where('locationable_type', 'App\Departments')
            ->get();

        return response()->json([
            'status' => 200,
            'locations' => $locations,
        ]);
    }

    public function insertdeptlocationajax(AddLocationRequest $request)
    {
        $data = $request->validated();
        $dept_id = $request->input('dept_locat_id');
        $check = Location::where('name', $request->input('location_value'))
            ->where('locationable_id', $dept_id)
            ->first();
        if ($check) {
            return response()->json([
                'status' => 403,
                'data' => null,
            ]);
        } else {
            $data = array(
                'name' => $request->input('location_value'),
                'locationable_id' => $dept_id,
                'locationable_type' => 'App\Departments',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );

            Location::create($data);

            return response()->json([
                'status' => 200,
                'data' => null,
            ]);
        }

    }

    public function geteditlocationdetails(Request $request)
    {
        $location_id = $request->input('locationid_value');
        $locations = Location::where('id', $location_id)->first();
        $deptname = DB::table('departments')->where('id', $locations->locationable_id)->value('departmentname');
        return response()->json([
            'status' => 200,
            'locations' => $locations,
            'deptname' => $deptname,
        ]);
    }

    public function updatedeptlocationajax(EditLocationRequest $request)
    {
        $location_id = $request->input('location_upt_id');
        Location::where('id', $location_id)->update([
            'name' => $request->input('location_upt_value'),
        ]);
        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }

    public function deletedeptlocations($id)
    {
        Location::where('id', $id)->delete();
        $models = DB::table('modenadisable')->First();
        return redirect('/admin/viewDepartments')
            ->with('message', 'Location deleted successfully')
            ->with('model', $models);
    }

/**--------department page location details ends---------- */

    public function searchedDepartment(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $search = $req->input('search');
        $user = User::all();
        if ($search != null) {
            $departments = DB::select(
                'select * from departments where departmentname = :edep or id = :eid',
                ['edep' => $search, 'eid' => $search]
            );
            return view('admin.viewDepartments')
                ->with('model', $models)
                ->with('departments', $departments)
                ->withUser($user);
        } else {
            return redirect('/admin/viewDepartments')->with('model', $models);
        }
    }

    //adding HOD Assistants
    public function addhodassist()
    {
        if (
            isset($_GET['val']) &&
            isset($_GET['dept']) &&
            isset($_GET['hod'])
        ) {
            $id = $_GET['val'];
            $dept = $_GET['dept'];
            $hod = $_GET['hod'];
            $depttb = DB::table('departments')
                ->where('id', $id)
                ->First();
            $user = User::all();
            $models = DB::table('modenadisable')->First();
            return view('admin.addhodassistants')
                ->with('model', $models)
                ->with('dept', $dept)
                ->with('hod', $hod)
                ->with('id', $id)
                ->with('deptb', $depttb)
                ->with('user', $user);
        }
    }

    public function inserthodasst(Request $request)
    {
        $id = $request->id;
        $dept = $request->dept;
        $hod = $request->hod;
        $assist[] = $request->asst;

        foreach ($assist as $ast) {
            $assistants = implode(',', $ast);
        }

        DB::table('departments')
            ->where('id', $id)
            ->update([
                'hod_assistants' => $assistants,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

        $models = DB::table('modenadisable')->First();
        return redirect('/admin/viewDepartments')
            ->with('model', $models)
            ->with('message', 'HOD Assistants Added Successfully');
    }

    public function enabledisabledepartment($id)
    {
        $response = [];
        try {
            $department = DB::table('departments')->find($id);

            if ($department) {
                $newStatus = !$department->is_active;
                DB::table('departments')->where('id', $id)
                    ->update(['is_active' => $newStatus]);

                $response['success'] = true;
                $response['message'] = $newStatus
                    ? 'Department has been enabled successfully.'
                    : 'Department has been disabled successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Data not found';
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
            $response['message'] = 'An error occurred while updating the department status.';
        }

        return response()->json($response);
    }

    public function viewTypes()
    {
        $models = DB::table('modenadisable')->First();
        $categories = Categories::orderBy('categoryname')->get();
        $categories_array = [
            // 0 => 'Process Non- Conformity',
            // 1 => 'Product Non- Conformity',
            0 => 'External Audit Observation',
            1 => 'Management Review',
            // 4 => 'Vendor Non- Conformity',
            2 => 'Consultant Recommendation',
            3 => 'Customer Audit',
            4 => 'Raw Material',
            5 => 'Corrective Action',
            6 => 'API Audit',
            7 => 'Insurance',
        ];
        if (!$categories->where('is_pnc_cat','yes')->count()) {
            $categories_array[] = 'Product Non- Conformity';
        }

        if (!$categories->where('is_process_cat','yes')->count()) {
            $categories_array[] = 'Process Non- Conformity';
        }

        if (!$categories->where('is_vendor_cat','yes')->count()) {
            $categories_array[] = 'Vendor Non- Conformity';
        }
        sort($categories_array);
        $productNcrConfigurationFields = ProductNcrConfigurationField::all();
        $proNcrConAddedFields = ProductNcrConfigurationAddedField::all();
        $processNcrConfigurationFields = ProcessNcrConfigurationField::all();
        $processNcrConAddedFields = ProcessNcrConfigurationAddedField::all();
        $vendorNcrConfigurationFields = VendorNcrConfigurationField::all();
        $vendorNcrConAddedFields = VendorNcrConfigurationAddedField::all();
        $types = Type::orderBy('order', 'asc')->get();
        $rootcorrectives = CorrectiveRootCauseCorrectiveActionDetail::all();

        //Detection Stages
        $detectionStages = CorrectiveDetectionStage::all();
        $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
        return view('admin.viewTypes',compact('productNcrConfigurationFields','proNcrConAddedFields',
            'processNcrConfigurationFields','processNcrConAddedFields','vendorNcrConfigurationFields',
            'vendorNcrConAddedFields','detectionStages','detectionStageLabel'
        ))
            ->with('types', $types)
            ->with('model', $models)
            ->with('categories_array', $categories_array)
            ->with('rootcorrectives', $rootcorrectives)
            ->with('categories', $categories);
    }
    public function editType(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $types = Type::all()->where('id', '=', $id);
            return view('admin.editType')
                ->with('types', $types)
                ->with('model', $models);
        }
    }
    public function updateType(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');
        $id = $req->input('id');
        $typeid = DB::select('select * from types where name = :ename', [
            'ename' => $name,
        ]);
        foreach ($typeid as $row) {
            $type1id = (string) $row->id;
            if ($type1id == $id || $typeid == null) {
                //inserting data to activities table
                $oldname = DB::table('types')
                    ->where('id', $id)
                    ->pluck('name');
                $activity = $oldname . ' Type is Updated with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                DB::update('update types set name = :ename  where id = :eid', [
                    'ename' => $name,
                    'eid' => $id,
                ]);
                return redirect('/admin/viewCategories')->with(
                    'model',
                    $models
                );
            } else {
                return redirect('/admin/viewCategories')->with(
                    'model',
                    $models
                );
            }
        }
        if ($typeid == null) {
            //inserting data to activities table
            $oldname = DB::table('types')
                ->where('id', $id)
                ->pluck('name');
            $activity = $oldname . ' Type is Updated with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::update('update types set name = :ename  where id = :eid', [
                'ename' => $name,
                'eid' => $id,
            ]);
            return redirect('/admin/viewCategories')->with('model', $models);
        }
    }

    public function updateManageWorkflowFlag($typeId, Request $request)
    {
        $response = [];
        try {
            $type = DB::table('types')->find($typeId);

            if ($type) {
                DB::table('types')->where('id', $typeId)
                    ->update(['is_manage_workflow' => !$type->is_manage_workflow]);

                $response['success'] = true;
                $response['message'] = 'The workflow management has been successfully modified.';
            } else {
                $response['success'] = false;
                $response['message'] = 'The data is not found';
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
            $response['message'] = 'Modifying the workflow management has failed.';
        }

        return response()->json($response);
    }

    public function editisoclauses(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $datas = DB::table('departments')
                ->where('id', $id)
                ->get();
            $departments = Departments::all()->where('id', '=', $id);
            $isoclauses = DB::table('departments')
                ->where('id', $id)
                ->value('isoclauses');
            return view('admin.editisoclauses')
                ->with('datas', $datas)
                ->with('isoclauses', $isoclauses)
                ->with('departments', $departments)
                ->with('model', $models);
        }
    }
    public function updateisoclauses(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'isoclauses' => 'regex:/^[^~]+$/',
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('model', $models);
        } else {
            $models = DB::table('modenadisable')->First();
            $isoclauses = $req->input('isoclauses');
            $id = $req->input('id');
            $depid = DB::select('select * from departments where id = :eid', [
                'eid' => $id,
            ]);
            foreach ($depid as $row) {
                $dep1id = (string) $row->id;
                if ($dep1id == $id || $depid == null) {
                    DB::update(
                        'update departments set isoclauses=:eisoclauses where id = :eid',
                        ['eisoclauses' => $isoclauses, 'eid' => $id]
                    );
                    return redirect('/admin/viewCategories')->with(
                        'model',
                        $models
                    );
                } else {
                    return redirect('/admin/viewCategories')->with(
                        'model',
                        $models
                    );
                }
            }
            if ($depid == null) {
                DB::update(
                    'update departments set isoclauses=:eisoclauses where id = :eid',
                    ['eisoclauses' => $isoclauses, 'eid' => $id]
                );
                return redirect('/admin/viewCategories')->with(
                    'model',
                    $models
                );
            }
        }
    }

    public function removehseType(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $name = DB::table('hsetypes')
                ->where('id', $id)
                ->pluck('hsetype');
            $activity = 'Type is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from hsetypes where id = :eid', ['eid' => $id]);
            return redirect('/admin/viewhseCategories')
                ->with('model', $models)
                ->with('message', 'HSE Type deleted successfully');
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function edithseType(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $hsetypes = DB::select('select * from hsetypes where id = :i', [
                'i' => $id,
            ]);

            return view('admin.edit_hseType')
                ->with('hsetypes', $hsetypes)
                ->with('model', $models);
        }
    }
    public function updatehseType(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');
        $oldname = $req->input('oldname');
        if ($name == $oldname) {
            return redirect('/admin/viewhseCategories')->with('model', $models);
        }
        $id = $req->input('id');
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|unique:hsetypes,hsetype,' . $id,
            'id' => 'required|integer|exists:hsetypes,id',
        ]);
        if($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('model', $models);
        }
        DB::update(
            'update hsetypes set hsetype = :ename  where id = :eid',
            ['ename' => $name, 'eid' => $id]
        );
        $activity = $oldname . 'HSE Type is Updated with Name as ' . $name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);
        return redirect('/admin/viewhseCategories')->with('model', $models);
    }
    public function addhseType(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('type');
        $hsetypes = DB::select('select * from hsetypes');

        if ($name != null) {
            $id = DB::select(
                'select id from hsetypes where hsetype = :entered',
                ['entered' => $name]
            );
            if ($id) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'HSE Type already exists')
                    ->with('hsetypes', $hsetypes);
            } else {
                $data = ['hsetype' => $name];
                DB::table('hsetypes')->insert($data);
                //inserting data to activities table
                $activity = 'New Type is added with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return back()
                    ->with('model', $models)
                    ->with('message', 'HSE Type added successfully')
                    ->with('hsetypes', $hsetypes);
            }
        } else {
            return back()
                ->with('model', $models);
        }
    }
    public function editTypeCorAction(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $types = Type::all()->where('id', '=', $id);
            return view('admin.editTypeCorAction')
                ->with('types', $types)
                ->with('model', $models);
        }
    }

    public function updateTypeCorAction(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');
        $id = $req->input('id');
        $typeid = DB::select('select * from types where name = :ename', [
            'ename' => $name,
        ]);
        foreach ($typeid as $row) {
            $type1id = (string) $row->id;
            if ($type1id == $id || $typeid == null) {
                //inserting data to activities table
                $oldname = DB::table('types')
                    ->where('id', $id)
                    ->pluck('name');
                $activity = $oldname . ' Type is Updated with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                DB::update('update types set name = :ename  where id = :eid', [
                    'ename' => $name,
                    'eid' => $id,
                ]);
                return redirect('/admin/viewTypes')->with('model', $models);
            } else {
                return redirect('/admin/viewTypes')->with('model', $models);
            }
        }
        if ($typeid == null) {
            //inserting data to activities table
            $oldname = DB::table('types')
                ->where('id', $id)
                ->pluck('name');
            $activity = $oldname . ' Type is Updated with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::update('update types set name = :ename  where id = :eid', [
                'ename' => $name,
                'eid' => $id,
            ]);
            return redirect('/admin/viewTypes')->with('model', $models);
        }
    }
    public function removeType(Request $req)
    {
        $types = Type::all();
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $name = DB::table('types')
                ->where('id', $id)
                ->pluck('name');
            $activity = 'Type is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from types where id = :eid', ['eid' => $id]);
            return redirect('/admin/viewCategories')
                ->with('model', $models)
                ->with('message', 'Type deleted successfully')
                ->with('types', $types);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function removeTypeCorAction(Request $req)
    {
        $types = Type::all();
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $name = DB::table('types')
                ->where('id', $id)
                ->pluck('name');
            $activity = 'Type is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from types where id = :eid', ['eid' => $id]);
            return redirect('/admin/viewTypes')
                ->with('model', $models)
                ->with('message', 'Type deleted successfully')
                ->with('types', $types);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function searchedType(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $search = $req->input('search');
        if ($search != null) {
            $types = DB::select(
                'select * from types where name = :edep or id = :eid',
                ['edep' => $search, 'eid' => $search]
            );
            return view('admin.viewTypes')->with('types', $types);
        } else {
            return redirect('/admin/viewTypes')->with('model', $models);
        }
    }
    public function addType(AddTypeRequest $request)
    {
        $types_count = Type::count();
        $data = $request->validated();
        $type = $data['type'];
        $observation = $data['observation'];
        $details = null;

        switch ($observation) {
            case 'major':
                $details = 'Major Observation';
                break;
            case 'moderate':
                $details = 'Moderate Observation';
                break;
            case 'minor':
                $details = 'Minor Observation';
                break;
        }

        $found = Type::where('name', $type)->orWhere('details', $details)->first();
        if ($found) {
            $error_message = null;

            switch ($observation) {
                case 'major':
                    $error_message = 'Major category existing, please delete existing Major catgory to add again';
                    break;
                case 'moderate':
                    $error_message = 'Moderate category existing, please delete existing Moderate catgory to add again';
                    break;
                case 'minor':
                    $error_message = 'Minor category existing, please delete existing Minor catgory to add again';
                    break;
            }

            return [
                'data' => null,
                'error' => $error_message,
            ];

        } else {
            $text_color = '#af1e1e';
            $background_color = '#f8d4d4';
            $order = 0;

            // switch ($types_count) {
            //     case 0:
            //         $text_color = '#af1e1e';
            //         $background_color = '#f8d4d4';
            //         break;
            //     case 1:
            //         $text_color = '#cc8800';
            //         $background_color = '#ffebcc';
            //         break;
            //     case 2:
            //         $text_color = '#176044';
            //         $background_color = '#e0edcf';
            //         break;
            // }

            switch ($observation) {
                case 'major':
                    $text_color = '#af1e1e';
                    $background_color = '#f8d4d4';
                    $order = 1;
                    break;
                case 'moderate':
                    $text_color = '#cc8800';
                    $background_color = '#ffebcc';
                    $order = 2;
                    break;
                case 'minor':
                    $text_color = '#176044';
                    $background_color = '#e0edcf';
                    $order = 3;
                    break;
            }

            if ($types_count < 3) {
                Type::create([
                    'name' => $type,
                    'details' => $details,
                    'text_color' => $text_color,
                    'order' => $order,
                    'background_color' => $background_color,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $activity = 'New Type is added with Name as ' . $type;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

        }

        return [
            'data' => null,
            'error' => null,
        ];

    }
    public function addstandard(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('standard');
        $standards = Standards::all();
        $types = Type::all();
        if ($name != null) {
            $id = DB::select('select id from standards where name = :entered', [
                'entered' => $name,
            ]);
            if ($id) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'Type already exists')
                    ->with('standards', $standards)
                    ->with('types', $types);
            } else {
                $data = ['name' => $name];
                DB::table('standards')->insert($data);
                //inserting data to activities table
                $activity = 'New Standard is added with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return back()
                    ->with('model', $models)
                    ->with('message', 'Standard added successfully')
                    ->with('types', $types);
            }
        } else {
            return back()
                ->with('model', $models)
                ->with('standards', $standards)
                ->with('types', $types);
        }
    }
    public function viewIssues()
    {
        $models = DB::table('modenadisable')->First();
        $issues = Issue::all();
        return view('admin.viewIssues')
            ->with('model', $models)
            ->with('issues', $issues);
    }
    public function closeIssue(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $title = DB::table('issues')
                ->where('id', $id)
                ->pluck('title');
            $department = DB::table('issues')
                ->where('id', $id)
                ->pluck('department');
            $activity =
                'Issue Closed with Title as ' .
                $title .
                ' and with Department ' .
                $department;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from issues where id = :eid', ['eid' => $id]);
            return redirect('/admin/viewIssues')->with('model', $models);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    //public function to show registrations which are not approved yet
    public function approveReg()
    {
        $models = DB::table('modenadisable')->First();
        $userss = User::all();

        $userzero = User::where('approved', 0)->get();
        $currsize = count($userss);
        $totalsize = DB::table('companysize')
            ->where('id', 1)
            ->First();
        //list of user registration which are not approved
        $users = DB::table('users')
            ->where('approved', '=', 0)
            ->get();

        return view('/admin/userapproval')
            ->with('model', $models)
            ->with('users', $users)
            ->with('size', $totalsize->compsize)
            ->with('currsize', $currsize)
            ->with('userzero', count($userzero));
    }
    //function for accepting registration
    public function approveaccept(Request $request)
    {
        $id = request()->input('userid');
        $user = DB::table('users')
            ->where('id', (int) $id)
            ->update(['approved' => 1]);

        UserGate::create([
            'key' => 'enable-all',
            'users_id' => $id,
            'saver_users_id' => User::where('email',Auth::user()->email)->where('approved',true)->value('id'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $name = DB::table('users')
            ->where('id', (int) $id)
            ->pluck('name');
        $email = DB::table('users')
            ->where('id', (int) $id)
            ->pluck('email');
        $activity =
            'Registration request is Accepted of User ' .
            $name .
            ' with Email ' .
            $email;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);
        DB::table('users')
            ->where('email', $email)
            ->update(['user_joining_date' => $date]);
        //accepted message to user registered gmail
        //code....
        $data = ['name' => $name, 'email' => $email];
        $admin = DB::table('admins')->pluck('email');
        Mail::to($admin)->send(new Approval($data));
        if(count($data['name']) > 0) {
           Mail::to($email)->send(new ApprovalToUser($data['name'][0]));
        }
        //Mail::to($email)->send(new ApprovalToUser((count($data['name']) ? $data['name'][0] : '')));

        return $this->approveReg();
    }
    //function for  rejecting  registeration
    public function approvereject(Request $request)
    {
        $id = request()->input('userid');

        $name = DB::table('users')
            ->where('id', (int) $id)
            ->pluck('name');
        $email = DB::table('users')
            ->where('id', (int) $id)
            ->pluck('email');
        $activity =
            'Registration request is Rejected of User ' .
            $name .
            ' with Email ' .
            $email;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);

        $user = DB::table('users')
            ->where('id', (int) $id)
            ->delete();
        //accepted message to user registered gmail
        //code....

        return $this->approveReg();
    }
    //function for limiting the the user signUp
    public function limitSignUp(Request $request)
    {
        if ($request->input('limit') === '') {
            return redirect()->back();
        }
        $oldlimit = DB::table('signuplimit')
            ->where('id', 1)
            ->First();
        if ($request->input('limit') == -1) {
            if ($oldlimit->limitedto <= 0) {
                //updating name and email is just to specify who just modefied signup limit
                DB::table('signuplimit')
                    ->where('id', 1)
                    ->update([
                        'limitedto' => 0,
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ]);
            } else {
                //updating name and email is just to specify who just modefied signup limit
                DB::table('signuplimit')
                    ->where('id', 1)
                    ->update([
                        'limitedto' => $oldlimit->limitedto - 1,
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ]);
            }
        } else {
            //updating name and email is just to specify who just modefied signup limit
            DB::table('signuplimit')
                ->where('id', 1)
                ->update([
                    'limitedto' => $request->input('limit'),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]);
            $activity =
            'SignUp Limit is Updated to ' . $request->input('limit');
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
        }
        $userlimit = DB::table('signuplimit')
            ->where('id', 1)
            ->First();
        session(['limit' => $userlimit->limitedto]);
        return $this->approveReg();
    }
    public function changePassword(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        if (
            !Hash::check(
                $request->get('current-password'),
                Auth::user()->password
            )
        ) {
            // The passwords matches
            return redirect()
                ->back()
                ->with('model', $models)
                ->with(
                    'error',
                    'Your current password does not matches with the password you provided. Please try again.'
                );
        }

        if (
            strcmp(
                $request->get('current-password'),
                $request->get('new-password')
            ) == 0
        ) {
            //Current password and new password are same
            return redirect()
                ->back()
                ->with('model', $models)
                ->with(
                    'error',
                    'New Password cannot be same as your current password. Please choose a different password.'
                );
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        // Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        $users = DB::table('users')
            ->where('email', Auth::user()->email)
            ->get();
        if (!empty($users)) {
            DB::table('users')
                ->where('email', Auth::user()->email)
                ->update(['password' => bcrypt($request->get('new-password'))]);
        }

        return redirect()
            ->back()
            ->with('model', $models)
            ->with('success', 'Password changed successfully !');
    }

    public function AddDetails(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $departments = DB::select(
                'select distinct departmentname from departments'
            );
            return view('admin.AddDetails')
                ->with('departments', $departments)
                ->with('model', $models);
        }
    }
    public function usermanage()
    {
        $models = DB::table('modenadisable')->First();
        return view('admin/usermanage')->with('model', $models);
    }
    //to update dept name and ID
    public function updateDetails(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $uid = $req->input('userid');
        $dep = $req->input('dept_id');
        $empi = $req->input('empid');
        $aid = 1;
        $userid = DB::select('select * from users where id = :eid', [
            'eid' => $uid,
        ]);
        $adminid = DB::select('select * from admins where id = :eid', [
            'eid' => $aid,
        ]);
        $u_email = $req->input('usermail');
        $a_mail = Auth::user()->email;

        if (strcmp($a_mail, $u_email) == 0) {
            foreach ($adminid as $row) {
                $admin1id = (string) $row->id;

                if ($admin1id == $aid || $adminid == null) {
                    DB::update(
                        'update admins set deptid = :de, employeeid=:empid where id = :did',
                        ['de' => $dep, 'empid' => $empi, 'did' => $aid]
                    );
                }
            }
        }

        foreach ($userid as $row) {
            $user1id = (string) $row->id;
            if ($user1id == $uid || $userid == null) {
                $name = DB::table('users')
                    ->where('id', $uid)
                    ->pluck('name');
                $activity =
                    'User ' .
                    $name .
                    ' details is Updated with Department id as ' .
                    $dep .
                    ' and Employee Id as ' .
                    $empi;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                DB::update(
                    'update users set deptid = :de ,employeeid=:empid where id = :did',
                    ['de' => $dep, 'empid' => $empi, 'did' => $uid]
                );
                $email = DB::table('users')
                    ->select('email')
                    ->where('id', $uid)
                    ->first()->email;
                DB::update(
                    'update admins set deptid = :de,employeeid=:empid where email = :eemail',
                    ['de' => $dep, 'empid' => $empi, 'eemail' => $email]
                );

                return redirect('/admin/viewUsers')->with('model', $models);
            }
        }
    }
    //attach information files button
    public function information()
    {
        $models = DB::table('modenadisable')->First();
        return view('admin/information')->with('model', $models);
    }
    //riskinfo button
    public function riskinfo()
    {
        $models = DB::table('modenadisable')->First();
        $riskinfo = DB::table('riskinfo')->get();
        return view('admin.riskinfo')
            ->with('model', $models)
            ->with('riskinfo', $riskinfo);
    }
    //add images
    public function addriskinfo(Request $request)
    {
        $models = DB::table('modenadisable')->First();

        // below code will store the file into public/storage

        // Handle file Upload
        if ($request->hasFile('file')) {
            $image_array = $request->file('file');

            $array_len = count($image_array);

            //assign a variable as an array
            $name = [];

            for ($i = 0; $i < $array_len; $i++) {
                $image_size = $image_array[$i]->getClientOriginalName();

                $new_image_name = $image_size;

                $destination_path = public_path('storage/riskinfo');
                $image_array[$i]->move($destination_path, $new_image_name);

                //read the datas into the array
                $name[] = $new_image_name;
            }
        }
        foreach ($name as $f) {
            $a[] = $f;
        }

        $cer = implode(',', $name);

        $riskinfo = DB::table('riskinfo')->get();

        $expl = explode(',', $cer);

        foreach ($expl as $cer) {
            $data1 = ['infoname' => $cer];

            DB::table('riskinfo')->insert($data1);
        }

        $activity = 'Risk info is added with file name ' . $cer;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);
        return back()
            ->with('riskinfo', $riskinfo)
            ->with('model', $models);
        // }
    }
    //remove images
    public function removeriskinfo(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $riskinfo = DB::table('riskinfo')
                ->where('id', $id)
                ->pluck('infoname');
            $activity = 'Risk info is Deleted with file name ' . $riskinfo;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from riskinfo where id = :eid', ['eid' => $id]);
            return redirect('/admin/riskinfo')->with('model', $models);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    //ohsinfo button
    public function ohsinfo()
    {
        $models = DB::table('modenadisable')->First();
        $ohsinfo = DB::table('ohsinfo')->get();
        return view('admin.ohsinfo')
            ->with('ohsinfo', $ohsinfo)
            ->with('model', $models);
    }
    //add images
    public function addohsinfo(Request $request)
    {
        $models = DB::table('modenadisable')->First();

        // below code will store the file into public/storage

        // Handle file Upload
        if ($request->hasFile('file')) {
            $image_array = $request->file('file');
            $array_len = count($image_array);
            //assign a variable as an array
            $name = [];

            for ($i = 0; $i < $array_len; $i++) {
                $image_size = $image_array[$i]->getClientOriginalName();

                $new_image_name = $image_size;
                $destination_path = public_path('storage/ohsinfo');
                $image_array[$i]->move($destination_path, $new_image_name);
                //read the datas into the array
                $name[] = $new_image_name;
            }
        }
        foreach ($name as $f) {
            $a[] = $f;
        }

        $cer = implode(',', $name);

        $ohsinfo = DB::table('ohsinfo')->get();

        $expl = explode(',', $cer);

        foreach ($expl as $cer) {
            $data1 = ['infoname' => $cer];

            DB::table('ohsinfo')->insert($data1);
        }
        $activity = 'New Ohs infomation is added with file name ' . $cer;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);
        return back()
            ->with('ohsinfo', $ohsinfo)
            ->with('model', $models);
    }
    //remove images
    public function removeohsinfo(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $ohsinfo = DB::table('ohsinfo')
                ->where('id', $id)
                ->pluck('infoname');
            $activity = 'Ohs information is Deleted with file name ' . $ohsinfo;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            DB::delete('delete from ohsinfo where id = :eid', ['eid' => $id]);
            return redirect('/admin/ohsinfo')->with('model', $models);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    //envinfo button
    public function enviinfo()
    {
        $enviaspect_probabilities = EnviaspectProbability::all();
        $enviaspect_consequences = EnviaspectConsequence::all();
        $enviaspect_regulatory_requirements = EnviaspectRegulatoryRequirement::all();
        $enviaspect_quantities = EnviaspectQuantity::all();
        $enviaspect_control_measures = EnviaspectControlMeasure::all();

        $models = DB::table('modenadisable')->first();

        return view('admin.enviinfo')->with([
            'model' => $models,
            'enviaspect_probabilities' => $enviaspect_probabilities,
            'enviaspect_consequences' => $enviaspect_consequences,
            'enviaspect_regulatory_requirements' => $enviaspect_regulatory_requirements,
            'enviaspect_quantities' => $enviaspect_quantities,
            'enviaspect_control_measures' => $enviaspect_control_measures,
        ]);

    }
    //add images
    public function addenvinfo(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        // below code will store the file into public/storage

        // Handle file Upload
        if ($request->hasFile('file')) {
            $image_array = $request->file('file');

            $array_len = count($image_array);
            //assign a variable as an array
            $name = [];

            for ($i = 0; $i < $array_len; $i++) {
                $image_size = $image_array[$i]->getClientOriginalName();

                $new_image_name = $image_size;
                $destination_path = public_path('storage/envinfo');
                $image_array[$i]->move($destination_path, $new_image_name);
                //read the datas into the array
                $name[] = $new_image_name;
            }
        }

        foreach ($name as $f) {
            $a[] = $f;
        }

        $cer = implode(',', $name);
        $cer = strtoupper($cer);
        $envinfo = DB::table('envinfo')->get();

        $expl = explode(',', $cer);

        foreach ($expl as $cer) {
            $data1 = ['infoname' => $cer];

            DB::table('envinfo')->insert($data1);
        }

        $activity = 'New Envi infomation is added with file name ' . $cer;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);

        return back()
            ->with('envinfo', $envinfo)
            ->with('model', $models);
    }
    //remove images
    public function removeenvinfo(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $envinfo = DB::table('envinfo')
                ->where('id', $id)
                ->pluck('infoname');
            $activity =
                'Envi information is Deleted with file name ' . $envinfo;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            DB::delete('delete from envinfo where id = :eid', ['eid' => $id]);
            return redirect('/admin/enviinfo')->with('model', $models);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }

    public function removeEnvProbabilities(int $id)
    {
        $envinfo = EnviaspectProbability::where('id', $id)->pluck('description');
        $activity = 'Envi probability information is Deleted with description as  ' . $envinfo;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);

        EnviaspectProbability::where('id', $id)->delete();
        $models = DB::table('modenadisable')->first();

        return redirect('/admin/enviinfo')->with('model', $models)->with('message', 'Probability deleted successfully');
    }

    public function editEnvProbabilities(EditEnvRequest $request)
    {
        $data = $request->validated();
        $id = $data['id'];
        $category = $data['category'];
        $weightage = $data['weightage'];
        $description = $data['description'];

        switch ($category) {
            case 'probabilities':
                EnviaspectProbability::where('id', $id)->update([
                    'description' => $description,
                    'weightage' => $weightage,
                ]);
                break;
            case 'consequences':
                EnviaspectConsequence::where('id', $id)->update([
                    'description' => $description,
                    'weightage' => $weightage,
                ]);

                break;
            case 'regulatory':
                EnviaspectRegulatoryRequirement::where('id', $id)->update([
                    'description' => $description,
                    'weightage' => $weightage,
                ]);

                break;
            case 'quantities':
                EnviaspectQuantity::where('id', $id)->update([
                    'description' => $description,
                    'weightage' => $weightage,
                ]);

                break;
            case 'control_measures':
                EnviaspectControlMeasure::where('id', $id)->update([
                    'description' => $description,
                    'weightage' => $weightage,
                ]);

                break;
        }

        return [
            'data' => null,
            'error' => null,
        ];

    }

    public function removeEnvConsequences(int $id)
    {
        $envinfo = EnviaspectConsequence::where('id', $id)->pluck('description');
        $activity = 'Envi consequence information is Deleted with description as  ' . $envinfo;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);

        EnviaspectConsequence::where('id', $id)->delete();
        $models = DB::table('modenadisable')->first();

        return redirect('/admin/enviinfo')->with('model', $models)->with('message', 'Consequence deleted successfully');
    }

    public function removeEnvRegulatory(int $id)
    {
        $envinfo = EnviaspectRegulatoryRequirement::where('id', $id)->pluck('description');
        $activity = 'Envi regulatory requirement information is Deleted with description as  ' . $envinfo;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);

        EnviaspectRegulatoryRequirement::where('id', $id)->delete();
        $models = DB::table('modenadisable')->first();

        return redirect('/admin/enviinfo')->with('model', $models)->with('message', 'Regulatory requirement deleted successfully');
    }

    public function removeEnvQunatity(int $id)
    {
        $envinfo = EnviaspectQuantity::where('id', $id)->pluck('description');
        $activity = 'Envi quantity information is Deleted with description as  ' . $envinfo;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);

        EnviaspectQuantity::where('id', $id)->delete();
        $models = DB::table('modenadisable')->first();

        return redirect('/admin/enviinfo')->with('model', $models)->with('message', 'Quantity deleted successfully');
    }

    public function removeEnvControlMeasures(int $id)
    {
        $envinfo = EnviaspectControlMeasure::where('id', $id)->pluck('description');
        $activity = 'Envi control measure information is Deleted with description as  ' . $envinfo;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);

        EnviaspectControlMeasure::where('id', $id)->delete();
        $models = DB::table('modenadisable')->first();

        return redirect('/admin/enviinfo')->with('model', $models)->with('message', 'Control Measure deleted successfully');
    }

    // public static function showDescriptions(string $description)
    // {
    //     switch ($description) {
    //         case 'very_rare':
    //             $description = 'Very rare';
    //             break;
    //         case 'rare':
    //             $description = 'Rare';
    //             break;
    //         case 'low':
    //             $description = 'Low';
    //             break;
    //         case 'moderate':
    //             $description = 'Moderate';
    //             break;
    //         case 'high':
    //             $description = 'High';
    //             break;
    //         case 'negligible_risk':
    //             $description = 'Negligible risk';
    //             break;
    //         case 'very_low_risk':
    //             $description = 'Very low risk';
    //             break;
    //         case 'low_risk':
    //             $description = 'Low risk';
    //             break;
    //         case 'moderate_risk':
    //             $description = 'Moderate risk';
    //             break;
    //         case 'high_risk':
    //             $description = 'High risk';
    //             break;
    //         case 'no_legal_requirement':
    //             $description = 'No Legal Requirement';
    //             break;
    //         case 'legal_requirement':
    //             $description = 'Legal Requirement';
    //             break;
    //         case 'legal_requirement':
    //             $description = 'Legal Requirement';
    //             break;
    //         case 'very_high':
    //             $description = 'Very high';
    //             break;
    //         case 'excessive':
    //             $description = 'Excessive';
    //             break;
    //         case 'effectively_controlled':
    //             $description = 'Effectively Controlled';
    //             break;
    //         case 'ineffective_control':
    //             $description = 'Ineffective Control';
    //             break;
    //         case 'no_control':
    //             $description = 'No Control';
    //             break;
    //         default:
    //             $description = $description;
    //             break;
    //     }

    //     return $description;
    // }

    public function addEnvCategory(EnviaspectAddCategoryRequest $request)
    {
        $data = $request->validated();
        $category = $data['category'];
        $weightage = $data['weightage'];
        $description = $data['description'];

        switch ($category) {
            case 'probabilities':
                $found = EnviaspectProbability::where('description', $description)->first();
                if (!$found) {
                    EnviaspectProbability::create([
                        'description' => $description,
                        'weightage' => $weightage,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                break;
            case 'consequences':
                $found = EnviaspectConsequence::where('description', $description)->first();
                if (!$found) {
                    EnviaspectConsequence::create([
                        'description' => $description,
                        'weightage' => $weightage,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                break;
            case 'regulatory':
                $found = EnviaspectRegulatoryRequirement::where('description', $description)->first();
                if (!$found) {
                    EnviaspectRegulatoryRequirement::create([
                        'description' => $description,
                        'weightage' => $weightage,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                break;
            case 'quantities':
                $found = EnviaspectQuantity::where('description', $description)->first();
                if (!$found) {
                    EnviaspectQuantity::create([
                        'description' => $description,
                        'weightage' => $weightage,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                break;
            case 'control_measures':
                $found = EnviaspectControlMeasure::where('description', $description)->first();
                if (!$found) {
                    EnviaspectControlMeasure::create([
                        'description' => $description,
                        'weightage' => $weightage,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                break;
        }

        return [
            'data' => null,
            'error' => null,
        ];
    }

    //document type
    public function doctypes()
    {
        $models = DB::table('modenadisable')->First();
        $defaultType = Doctype::where('is_default', true)->orderBy('level', 'asc')->get();
        $notDefaultType = Doctype::where('is_default', false)->orderBy('level', 'asc')->get();
        $doctypes = $defaultType->merge($notDefaultType);
        return view('admin.doctypes')
            ->with('doctypes', $doctypes)
            ->with('model', $models);
    }

    public function validateDoctypeCode(Request $request)
    {
        $code = trim($request->get('code'));
        $id = $request->get('id');

        if ($code) {
            $exist = Doctype::where('type_code', $code)
                ->where(function ($query) use ($id) {
                    if ($id) {
                        $query->where('id', '!=', $id);
                    }
                })
                ->exists();

            return response()->json(['exists' => $exist]);
        }

        return response()->json(['exists' => false]);
    }

    public function generateDoctypeCode(Request $request)
    {
        $doctypeName = trim($request->get('name'));

        if (empty($doctypeName)) {
            return response()->json([
                'code' => '',
                'error' => 'Document type name is required'
            ]);
        }

        $doctypeCode = $this->generateUniqueTypeCode($doctypeName);

        return response()->json([
            'code' => $doctypeCode,
        ]);
    }

    public function editdoctype($id, Request $req)
    {
        try {
            $doctype = Doctype::findOrFail($id);
            return response()->json([
                'success' => true,
                'view' => view('admin._doctypes_form', [
                    'doctype' => $doctype
                ])->render()
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Document type not found'
            ], 404);
        }
    }
    public function updatedoctype($id, Request $req)
    {
        $validator = Validator::make($req->all(), [
            'type_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('doctypes', 'typename')->ignore($id),
            ],
            'type_code' => [
                'required',
                'string',
                'max:255',
                'min:2',
                Rule::unique('doctypes', 'type_code')->ignore($id),
            ],
        ],[],[
            'type_name' => 'type name',
            'type_code' => 'type code'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $data = [
            'typename' => $req->input('type_name'),
            'type_code' => $req->input('type_code'),
        ];

        try {
            $oldname = DB::table('doctypes')
                ->where('id', $id)
                ->pluck('typename');
            $activity =
                $oldname . ' Document Type is Updated with name ' . $req->input('type_name');
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            $doctype = Doctype::findOrFail($id);
            if  ($doctype->is_default) {
                unset($data['typename']);
            }
            $doctype->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Type updated successfully'
            ], 200);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the type'
            ], 500);
        }
    }
    public function removedoctype(Request $req)
    {
        $types = Doctype::all();
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $name = DB::table('doctypes')
                ->where('id', $id)
                ->pluck('typename');
            $activity = 'Document Type is Deleted with name ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from doctypes where id = :eid and is_default = false', ['eid' => $id]);
            return redirect('/admin/doctypes')
                ->with('model', $models)
                ->with('message', 'Type deleted successfully')
                ->with('types', $types)
                ->with('model', $models);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function searcheddoctype(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $search = $req->input('search');
        if ($search != null) {
            $doctypes = DB::select(
                'select * from doctypes where typename = :edep or id = :eid',
                ['edep' => $search, 'eid' => $search]
            );
            return view('admin.doctypes')
                ->with('doctypes', $doctypes)
                ->with('model', $models);
        } else {
            return redirect('/admin/doctypes')->with('model', $models);
        }
    }
    public function adddoctype(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'type_name' => 'required|string|max:100|unique:doctypes,typename',
            'type_code' => 'required|string|min:2|max:255|unique:doctypes,type_code',
        ],[],[
            'type_name' => 'type name',
            'type_code' => 'type code'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $data = [
            'typename' => $req->input('type_name'),
            'type_code' => $req->input('type_code'),
        ];
        $lastLevel = DB::table('doctypes')->max('level');
        $data['level'] = $lastLevel ? $lastLevel + 1 : 1;
        DB::table('doctypes')->insert($data);

        $activity = 'New Document Type is added with name ' . $req->input('type_name');
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);

        return response()->json([
            'success' => true,
            'message' => 'Type added successfully'
        ], 200);
    }

    public function reorderDoctypes(Request $req)
    {
        try {
            $models = DB::table('modenadisable')->First();
            $reorderedData = $req->input('reordered_data');

            if (!$reorderedData || !is_array($reorderedData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data provided'
                ], 400);
            }

            // Validate that all IDs exist in the database
            $providedIds = array_column($reorderedData, 'id');
            $numberOfExistingIds = DB::table('doctypes')
                ->where('is_default', false)
                ->whereIn('id', $providedIds)
                ->count();

            if (count($providedIds) !== $numberOfExistingIds) {
                return response()->json([
                    'success' => false,
                    'message' => 'Number of items does not match existing records'
                ], 400);
            }

            // Validate that all IDs are unique
            if (count($providedIds) !== count(array_unique($providedIds))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate document type IDs detected'
                ], 400);
            }

            // Update levels based on the new order
            foreach ($reorderedData as $index => $item) {
                $id = $item['id'];
                $newLevel = $item['level'];

                DB::table('doctypes')
                    ->where('id', $id)
                    ->update(['level' => $newLevel]);
            }

            // Log the activity
            $activity = 'Document Types hierarchy reordered';
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return response()->json([
                'success' => true,
                'message' => 'Document types reordered successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while reordering: ' . $e->getMessage()
            ], 500);
        }
    }
    public function logout()
    {
        $logindevices = DB::table('admins')
            ->where('id', Auth::user()->id)
            ->pluck('logdevices')
            ->First();
        $logindevices = $logindevices - 1;
        if ($logindevices <= 0) {
            $logindevices = 0;
        }
        DB::table('admins')
            ->where('id', Auth::user()->id)
            ->update(['logdevices' => $logindevices]);
        $user = DB::table('users')
            ->where('email', Auth::user()->email)
            ->get();
        if (!$user->isEmpty()) {
            DB::table('users')
                ->where('email', Auth::user()->email)
                ->update(['logdevices' => $logindevices]);
        }
        Auth::guard('admin')->logout();
        Session::flush();
        return redirect('/');
    }
    //product/services
    public function viewproduct()
    {
        $models = DB::table('modenadisable')->First();
        $product = Product::all();

        //------------types----------------
        $types = Type::all();

        return view('admin.viewproduct')
            ->with('types', $types)
            ->with('product', $product)
            ->with('model', $models);
    }
    public function indexComplaint()
    {
        $models = DB::table('modenadisable')->First();
        $product = Product::all();
        $comp_category = ComplaintCategory::where('is_deleted',false)->get();
        $comp_category_with_no_plant = ComplaintCategory::where('is_deleted',false)->where('plant_id', null)->get();
        $types = Type::all();
        $plants = ComplaintPlant::where('is_deleted',false)->get();
        $units = ComplaintUnit::where('is_deleted',false)->get();
        $complaint_categories_exists = $comp_category->count() > 0 ? 1 : 0;
        $complaint_exists = Complaintregister::exists();
        $category_plant_dependency = AppConfiguration::where('meta_key', 'complaint_category_plant_dependency')->first();
        $category_plant_dependency_options = AppConfiguration::where('meta_key', 'complaint_category_plant_dependency_options')->first();

        return view('admin.complaint.index')
            ->with('types', $types)
            ->with('comp_category',$comp_category)
            ->with('comp_category_with_no_plant',$comp_category_with_no_plant)
            ->with('product', $product)
            ->with('model', $models)
            ->with('units',$units)
            ->with('plants',$plants)
            ->with('complaint_categories_exists',$complaint_categories_exists)
            ->with('complaint_exists',$complaint_exists)
            ->with('category_plant_dependency',$category_plant_dependency)
            ->with('category_plant_dependency_options',$category_plant_dependency_options ? json_decode($category_plant_dependency_options->meta_value) : []);
    }

    public function updateComplaintCategoryPlantDependency(Request $request)
    {
        $value = $request->value;
        $category_plant_dependency_options = AppConfiguration::where('meta_key', 'complaint_category_plant_dependency_options')->first();
        $options = $category_plant_dependency_options ? json_decode($category_plant_dependency_options->meta_value, true) : [];
        $validated = $request->validate([
            'value' => ['required', Rule::in($options)],
        ]);

        AppConfiguration::where('meta_key', 'complaint_category_plant_dependency')
            ->update([
                'meta_value' => $validated['value'],
                'updated_by' => Auth::guard('admin')->user()->user->id,
            ]);

        return response()->json([
                'status' => true,
                'message' => 'Complaint category plant dependency updated succesfully',
            ]);
    }

    public function updateComplaintCategoryPlantMapping(Request $request)
    {
        $plantMap = $request->plant_map;

        foreach ($plantMap as $categoryId => $plantId) {
            ComplaintCategory::where('id', $categoryId)->update(['plant_id' => $plantId]);
        }

        AppConfiguration::where('meta_key', 'complaint_category_plant_dependency')
            ->update([
                'meta_value' => 'Linked to Plant',
                'updated_by' => Auth::guard('admin')->user()->user->id,
            ]);


        return response()->json([
                'status' => true,
                'message' => 'Category Plant Mapping Done Succesfully and Complaint Category Plant Dependency Updated to Linked to Plant',
            ]);
    }

    public function editproduct(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $product = Product::all()->where('id', '=', $id);
            return view('admin.editproduct')
                ->with('product', $product)
                ->with('model', $models);
        }
    }
    public function updateproduct(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');
        $id = $req->input('id');
        $catid = DB::select('select * from products where product = :ename', [
            'ename' => $name,
        ]);
        foreach ($catid as $row) {
            $cat1id = (string) $row->id;
            if ($cat1id == $id || $catid == null) {
                //inserting data to activities table
                $oldname = DB::table('products')
                    ->where('id', $id)
                    ->pluck('product');
                $activity =
                    $oldname . ' Product/Service is Updated with Name ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                DB::update(
                    'update products set product = :ename  where id = :eid',
                    ['ename' => $name, 'eid' => $id]
                );
                return redirect('/admin/viewproduct')->with('model', $models);
            } else {
                return redirect('/admin/viewproduct')->with('model', $models);
            }
        }
        if ($catid == null) {
            //inserting data to activities table
            $oldname = DB::table('products')
                ->where('id', $id)
                ->pluck('product');
            $activity =
                $oldname . ' Product/Service is Updated with Name ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::update(
                'update products set product = :ename  where id = :eid',
                ['ename' => $name, 'eid' => $id]
            );
            return redirect('/admin/viewproduct')->with('model', $models);
        }
    }
    public function removeproduct(Request $req)
    {
        $product = Product::all();
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $name = DB::table('products')
                ->where('id', $id)
                ->pluck('product');
            $activity = 'Product/Service is Removed with Name ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from products where id = :eid', ['eid' => $id]);
            return redirect('/admin/viewproduct')
                ->with('model', $models)
                ->with('message', 'Product/Service deleted successfully')
                ->with('product', $product);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function searchedproduct(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $search = $req->input('search');
        if ($search != null) {
            $product = DB::select(
                'select * from products where product = :ecat or id = :eid',
                ['ecat' => $search, 'eid' => $search]
            );

            return view('admin.viewproduct')
                ->with('product', $product)
                ->with('model', $models);
        } else {
            return redirect('/admin/viewproduct')->with('model', $models);
        }
    }
    public function addproduct(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('product');

        $product = Product::all();
        if ($name != null) {
            $id = DB::select(
                'select id from products where product = :enteredproduct',
                ['enteredproduct' => $name]
            );
            if ($id) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'Product/Service already exists')
                    ->with('product', $product);
            } else {
                $data = ['product' => $name];
                DB::table('products')->insert($data);

                //inserting data to activities table
                $activity = 'Product/Service is Added with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return back()
                    ->with('model', $models)
                    ->with('message', 'Product/Service added successfully')
                    ->with('product', $product);
            }
        } else {
            return back()
                ->with('product', $product)
                ->with('model', $models);
        }
    }
    //vendor details
    public function vendorEval()
    {
        $models = DB::table('modenadisable')->First();
        $venp = DB::table('vendordetails')
            ->where('type', 'Product')
            ->get();
        $vens = DB::table('vendordetails')
            ->where('type', 'Service Provider')
            ->get();
        $user = User::all();
        return view('admin.vendorEvaluationDetails')
            ->with('vens', $vens)
            ->with('venp', $venp)
            ->withUser($user)
            ->with('model', $models);
    }

    public function productExcelUpload(DepartmentExcelUploadRequest $request)
    {
        $rows = Excel::toArray(new ProductServiceImport, $request->file('excel_data'))[0];

        if (count($rows) > 0) {
            for ($i = 0; $i < count($rows); $i++) {
                $count_of_null[$i] = 0;

                foreach ($rows[$i] as $key => $item) {
                    if ($item == null) {
                        $count_of_null[$i]++;
                    }
                }
                if ($count_of_null[$i] > 6) {
                    $rows[$i] = array_filter($rows[$i], function ($k) {
                        return !is_numeric($k);
                    }, ARRAY_FILTER_USE_KEY);
                }
            }
        }

        $rows = array_values(array_filter($rows));
        $cnt = count($rows);
        $null_rows = [];

        if (count($rows) > 0) {
            for ($i = 0; $i < count($rows); $i++) {
                if (BaseController::containsOnlyNull($rows[$i])) {
                    $null_rows[$i] = $i;
                }
            }
        }
        $null_rows = array_values($null_rows);
        for ($i = 0; $i < $cnt; $i++) {
            if (isset($null_rows[$i])) {
                unset($rows[$null_rows[$i]]);
            }
        }

        if (count($rows) > 0) {
            for ($i = 0; $i < count($rows); $i++) {
                $name = $rows[$i]['product_service'];

                if (empty($rows[$i]['product_service'])) {
                    Session::flash('errmsg', 'Product/Service name is empty.Please refer Note for excel upload format');

                    return redirect()->back();
                } else {

                    $data = [
                        'product' => $rows[$i]['product_service'],
                    ];

                    //checking if a point for a particular data already exists in database
                    $check = Product::where('product', $name)->first();
                    if ($check) {
                        Product::where('product', $name)->update($data);
                    } else {
                        Product::create($data);
                    }

                }
            }
        }

        if (!File::exists(public_path('/storage/product_service/'))) {
            File::makeDirectory(public_path() . '/storage/product_service/', 0755, true);
        }

        $name = 'excel_data' . '.' . $request->file('excel_data')->extension();
        $request->file('excel_data')->move(public_path() . '/storage/product_service/', $name);

        $models = ModenaDisable::first();

        return redirect()
            ->back()
            ->with('msg', 'Product/Service List Uploaded successfully')
            ->with('model', $models);

    }

    public function vendorExcelUpload(ExcelUploadRequest $request)
    {
        $rows = Excel::toArray(new VendorsImport, $request->file('excel_data'))[0];
        if (count($rows) > 0) {
            for ($i = 0; $i < count($rows); $i++) {
                $count_of_null[$i] = 0;

                foreach ($rows[$i] as $key => $item) {
                    if ($item == null) {
                        $count_of_null[$i]++;
                    }
                }
                if ($count_of_null[$i] > 6) {
                    $rows[$i] = array_filter($rows[$i], function ($k) {
                        return !is_numeric($k);
                    }, ARRAY_FILTER_USE_KEY);
                }
            }
        }

        $rows = array_values(array_filter($rows));
        $cnt = count($rows);
        $null_rows = [];

        if (count($rows) > 0) {
            for ($i = 0; $i < count($rows); $i++) {
                if ( /*count($rows[$i]) != 10 ||*/BaseController::containsOnlyNull($rows[$i])) {
                    $null_rows[$i] = $i;
                }
            }
        }
        $null_rows = array_values($null_rows);
        for ($i = 0; $i < $cnt; $i++) {
            if (isset($null_rows[$i])) {
                unset($rows[$null_rows[$i]]);
            }
        }

        if (count($rows) > 0) {
            for ($i = 0; $i < count($rows); $i++) {
                $email = $rows[$i]['email'];
                $standards = $rows[$i]['certified_standards'];
                $standards = explode(',', $standards);
                $standard_ids = [];
                foreach ($standards as $s) {
                    $standard_ids[] = Standards::where('name', trim($s))->value('id');
                }

                $country = Country::where('country_name', $rows[$i]['country'])->first();
                $country_id = $country ? $country->id : 0;
                $approved_date = null;
                if($rows[$i]['approved_date']){
                    $approved_date = date_format(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rows[$i]['approved_date']), 'Y-m-d');
                }

                if (empty($rows[$i]['vendor_name']) || empty($rows[$i]['product_service']) || empty($email) || empty($rows[$i]['frequency_of_evaluation'])) {
                    Session::flash('errmsg', 'Vendor_name or product_service or frequency_of_evaluation and email data is empty. Please refer Note for excel upload format');
                    return redirect()->back();
                } else {
                    if($rows[$i]['vendor_type']){
                        $vendorType = strtolower($rows[$i]['vendor_type']);
                        if($vendorType=="critical"){
                            $vendorType = 'critical';
                        }elseif($vendorType=='semi-critical'){
                            $vendorType = 'semi_critical';
                        }elseif($vendorType=='non critical'){
                            $vendorType = 'non_critical';
                        }else{
                            $vendorType = null;
                        }
                    }else{
                        $vendorType =  null;
                    }
                    $frequency = null;
                    $due = null;
                    $c = date('Y-m-d');
                    switch(strtolower($rows[$i]['frequency_of_evaluation'])){
                        case 'quarterly':
                            $frequency = 'quarterly';
                            break;
                        case 'half year':
                            $frequency = 'half_year';
                            break;
                        case 'one year':
                            $frequency = 'one_year';
                            break;
                        case '1.5 year':
                            $frequency = 'one_half_year';
                            break;
                        case 'two year':
                            $frequency = 'two_year';
                            break;
                        case 'three year':
                            $frequency = 'three_year';
                            break;
                        case 'four year':
                            $frequency = 'four_year';
                            break;
                        case 'five year':
                            $frequency = 'five_year';
                            break;
                    }
                    if ($frequency == "one_year") {
                        $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(1);
                    } else if ($frequency == "two_year") {
                        $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(2);
                    } else if ($frequency == "three_year") {
                        $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(3);
                    } else if ($frequency == "four_year") {
                        $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(4);
                    } else if ($frequency == "five_year") {
                        $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(5);
                    } else if ($frequency == "quarterly") {
                        $d = strtotime("+3 months", strtotime($c));
                        $due = date("Y-m-d", $d);
                    } else if ($frequency == "half_year") {
                        $d = strtotime("+6 months", strtotime($c));
                        $due = date("Y-m-d", $d);
                    } else if ($frequency == "one_half_year") {
                        $y = Carbon::createFromFormat('Y-m-d', $c)->addYear(1);
                        $d = strtotime("+6 months", strtotime($y));
                        $due = date("Y-m-d", $d);
                    }
                    $data = [
                        'vendor_name' => $rows[$i]['vendor_name'],
                        'vendor_group' => 'Existing',
                        'email' => $email,
                        'approved_date' => $approved_date,
                        'scope' => $rows[$i]['product_service'],
                        'country_id' => $country_id,
                        'location' => $rows[$i]['location'],
                        'contact' => $rows[$i]['contact'],
                        'Yoe' => $rows[$i]['year_establishment'],
                        'vendor_type' => $vendorType,
                        'status' => 'approved',
                        'added_by' => Auth::user()->employeeid,
                        'eval' => $frequency,
                        'due_date' => $due,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    //checking if a point for a particular data already exists in database
                    $check = Vendor::where('email', $email)->where('scope', $rows[$i]['product_service'])->first();
                    if ($check) {
                        $vendor_id = $check->id;
                        Vendor::where('email', $email)->where('scope', $rows[$i]['product_service'])->update($data);
                    } else {
                        $vendor = Vendor::create($data);
                        $vendor_id = $vendor->id;
                    }
                    $j = 0;
                    foreach ($standards as $s) {
                        if ($standard_ids[$j] != null) {
                            VendorStandard::firstOrCreate([
                                'vendors_id' => $vendor_id,
                                'standards_id' => $standard_ids[$j],
                            ]);
                        }
                        $j++;
                    }
                }
            }
        }

        if (!File::exists(public_path('/storage/vendor/'))) {
            File::makeDirectory(public_path() . '/storage/vendor/', 0755, true);
        }

        $name = 'excel_data' . '.' . $request->file('excel_data')->extension();
        $request->file('excel_data')->move(public_path() . '/storage/vendor/', $name);

        $models = ModenaDisable::first();

        return redirect()
            ->back()
            ->with('msg', 'Vendor List Uploaded successfully')
            ->with('model', $models);
    }
    public function addqstns()
    {
        $departments = Departments::all();
        $hods = DB::table('departments')->pluck('hod');
        $sa = DB::table('supplierapprovers')->pluck('email');

        // below code related to superadmin
        $models = DB::table('modenadisable')->First();

        return view('admin.AddQstns')
            ->with('model', $models)
            ->with('model', $models);
    }
    public function insertqstns(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $question = $req->input('qstn');
        $score = $req->input('score');
        $type = $req->input('type');
        $fill = $req->input('fill');
        $data = [
            'question' => $question,
            'score' => $score,
            'type' => $type,
            'filled_by' => $fill,
        ];
        DB::table('vendordetails')->insert($data);
        // below code related to superadmin

        return redirect('/admin/vendorEvaluationDetails')->with(
            'model',
            $models
        );
    }
    public function editQstns(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $eval = Vendordetails::all()->where('id', '=', $id);

            return view('admin.editQstns')
                ->with('eval', $eval)
                ->with('model', $models);
        }
    }
    public function updateQstns(Request $req)
    {
        $models = DB::table('modenadisable')->First();

        $qstn = $req->input('qstn');
        $id = $req->input('id');
        $type = $req->input('type1');
        $score = $req->input('score');
        $fill = $req->input('fill');
        $vid = DB::select('select * from vendordetails where id = :id', [
            'id' => $id,
        ]);
        foreach ($vid as $row) {
            $v1id = (string) $row->id;
            if ($v1id == $id || $vid == null) {
                DB::update(
                    'update vendordetails set question = :eq ,score=:es,type=:et,filled_by=:ef where id = :vid',
                    [
                        'eq' => $qstn,
                        'es' => $score,
                        'et' => $type,
                        'ef' => $fill,
                        'vid' => $id,
                    ]
                );
                return redirect('/admin/vendorEvaluationDetails')->with(
                    'model',
                    $models
                );
            } else {
                return redirect('/admin/vendorEvaluationDetails')->with(
                    'model',
                    $models
                );
            }
        }
    }
    public function CarryQstnID(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            return $this->removeQstn($id)->with('model', $models);
        }
    }
    public function removeQstn($id)
    {
        $models = DB::table('modenadisable')->First();

        DB::delete('delete from vendordetails where id = :vid', ['vid' => $id]);

        return redirect('/admin/vendorEvaluationDetails')
            ->with('model', $models)
            ->with('message', 'Question deleted successfully');
    }
    public function vendorResult()
    {
        $models = DB::table('modenadisable')->First();
        $ven = DB::table('vendorresult')->get();
        $user = User::all();
        return view('admin.vendorEvaluationResult')
            ->with('ven', $ven)
            ->withUser($user)
            ->with('model', $models);
    }
    public function editres(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $eval = DB::table('vendorresult')
                ->where('id', $id)
                ->get();

            return view('admin.editRes')
                ->with('eval', $eval)
                ->with('model', $models);
        }
    }
    public function updateRes(Request $req)
    {
        $models = DB::table('modenadisable')->First();

        $per = $req->input('percent');
        $id = $req->input('id');
        $comment = $req->input('comment');

        $vid = DB::select('select * from vendorresult where id = :id', [
            'id' => $id,
        ]);
        foreach ($vid as $row) {
            $id1 = (string) $row->id;
            if ($id1 == $id || $vid == null) {
                DB::update(
                    'update vendorresult set percent = :p ,comment=:c where id = :id',
                    ['p' => $per, 'c' => $comment, 'id' => $id]
                );
                return redirect('/admin/vendorEvaluationResult')->with(
                    'model',
                    $models
                );
            } else {
                return redirect('/admin/vendorEvaluationResult')->with(
                    'model',
                    $models
                );
            }
        }
    }
    //hr management related function
    public function addjobcode()
    {
        $jc = DB::table('jobcode')->get();

        // below code related to superadmin
        $models = DB::table('modenadisable')->First();

        return view('admin.AddJobCode')
            ->with('model', $models)
            ->with('jc', $jc);
    }
    public function submitjobcode(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $jc = DB::table('jobcode')->get();
        $id = $req->input('id');
        $type = $req->input('type');
        $code = $req->input('code');
        if ($type != null) {
            $jid = DB::select('select * from jobcode where id = :id', [
                'id' => $id,
            ]);
            foreach ($jid as $row) {
                $jd1 = (string) $row->id;
                if ($jd1 == $id || $jid == null) {
                    DB::update('update jobcode set code = :c where id = :id', [
                        'c' => $type,
                        'id' => $id,
                    ]);
                    return redirect('/admin/addjobcode')->with(
                        'model',
                        $models
                    );
                } else {
                    return redirect('/admin/addjobcode')->with(
                        'model',
                        $models
                    );
                }
            }
        } else {
            $data = ['code' => $code];
            DB::table('jobcode')->insert($data);
            return redirect('/admin/addjobcode')->with('model', $models);
        }
    }
    //waste management related functions
    public function wastedesc()
    {
        $models = DB::table('modenadisable')->first();
        $waste = DB::table('wastedesc')->get();
        $users = User::all();
        // $vens =  DB::table('vendordetails')->where('type','Service Provider')->get();
        $wastetypes = Wastetype::get();
        $wastetargets = Wastetarget::get();
        $user = User::all();
        //return view('admin.wastedesc')->with('waste', $waste)->withUser($user)->with('model', $models);
        return view('admin.wastedesc')->with([
            'waste' => $waste,
            'user' => $user,
            'model' => $models,
            'wastetypes' => $wastetypes,
            'wastetargets' => $wastetargets,
            'users' => $users,
        ]);
    }
    public function editwaste(int $id)
    {
        $models = DB::table('modenadisable')->first();
        $waste = DB::table('wastedesc')
            ->where('id', $id)
            ->get();
        $wastetypes = Wastetype::get();
        return view('admin.EditWaste')->with([
            'waste' => $waste,
            'model' => $models,
            'wastetypes' => $wastetypes,
            'id' => $id,
        ]);
    }
    public function updatewaste(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $desc = $req->input('desc');
        $id = $req->input('id');
        $type = $req->input('type');

        $wid = DB::select('select * from wastedesc where id = :id', [
            'id' => $id,
        ]);
        foreach ($wid as $row) {
            $w1id = (string) $row->id;
            if ($w1id == $id || $wid == null) {
                DB::update(
                    'update wastedesc set waste_type = :wt ,wastedesc=:wd where id = :wid',
                    ['wt' => $type, 'wd' => $desc, 'wid' => $id]
                );
                return redirect('/admin/wastedesc')->with('model', $models);
            } else {
                return redirect('/admin/wastedesc')->with('model', $models);
            }
        }
    }
    //Incident Report module related functions
    public function add_update_IncidentElements()
    {
        $models = DB::table('modenadisable')->First();
        $protection = DB::table('protection_kit')->get();
        $incident_prevention = DB::table('incident_prevention')->get();
        $incidents = DB::table('incident_type')->get();
        return view('admin.addupdateincidentelements')
            ->with('model', $models)
            ->with('protection', $protection)
            ->with('incident_prevention', $incident_prevention)
            ->with('incidents', $incidents);
    }
    public function updatedeleteIncidentType(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        if ($request->opt == 1) {
            $incidents = DB::table('incident_type')->get();
            foreach ($incidents as $inc) {
                if (
                    ucwords($inc->incident_type) == ucwords($request->updated)
                ) {
                    Session::flash('msg', 'Incident type already exists');
                    return redirect()
                        ->back()
                        ->with('model', $models);
                }
            }
            DB::table('incident_type')
                ->where('id', $request->id)
                ->update(['incident_type' => ucwords($request->updated)]);
            Session::flash('msg', 'Incident type updated successfully');
        } else {
            DB::table('incident_type')
                ->where('id', $request->id)
                ->delete();
            Session::flash('msg', 'Incident type deleted successfully');
        }
        return redirect()
            ->back()
            ->with('model', $models);
    }
    public function addIncidentType(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $incident_type = ucwords($request->incident_type);
        $incidents = DB::table('incident_type')->get();
        foreach ($incidents as $incident) {
            if (ucwords($incident->incident_type) == $incident_type) {
                Session::flash('message', 'Incident type already exists');
                return redirect()
                    ->back()
                    ->with('model', $models);
            }
        }
        DB::table('incident_type')->insert(['incident_type' => $incident_type]);
        Session::flash('message', 'Incident Type added successfully');
        return redirect()
            ->back()
            ->with('model', $models);
    }
    public function updatedeleteIncidentPrevention(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $incident_prevention = DB::table('incident_prevention')->get();
        if ($request->opt == 1) {
            foreach ($incident_prevention as $ip) {
                if (
                    ucwords($ip->incident_prevent) == ucwords($request->updated)
                ) {
                    Session::flash(
                        'msg1',
                        'Incident Prevention method already exists'
                    );
                    return redirect()
                        ->back()
                        ->with('model', $models);
                }
            }
            DB::table('incident_prevention')
                ->where('id', $request->id)
                ->update(['incident_prevent' => ucwords($request->updated)]);
            Session::flash(
                'msg1',
                'Incident prevention method updated successfully'
            );
        } else {
            DB::table('incident_prevention')
                ->where('id', $request->id)
                ->delete();
            Session::flash(
                'msg1',
                'Incident prevention method deleted successfully'
            );
        }
        return redirect()
            ->back()
            ->with('model', $models);
    }
    public function addIncidentPrevention(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $incident_prevention = ucwords($request->incident_prevention);
        $incident_preventions = DB::table('incident_prevention')->get();
        foreach ($incident_preventions as $ip) {
            if (ucwords($ip->incident_prevent) == $incident_prevention) {
                Session::flash(
                    'message1',
                    'Incident prevention methodalready exists'
                );
                return redirect()
                    ->back()
                    ->with('model', $models);
            }
        }
        DB::table('incident_prevention')->insert([
            'incident_prevent' => $incident_prevention,
        ]);
        Session::flash(
            'message1',
            'Incident prevention method added successfully'
        );
        return redirect()
            ->back()
            ->with('model', $models);
    }
    public function addIncidentProtection(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $incident_protection = ucwords($request->incident_protection);
        $incident_protections = DB::table('protection_kit')->get();
        foreach ($incident_protections as $prot) {
            if (ucwords($prot->kit) == $incident_protection) {
                Session::flash('message2', 'Protection kit already exists');
                return redirect()->back();
            }
        }
        DB::table('protection_kit')->insert(['kit' => $incident_protection]);
        Session::flash('message2', 'Protection kit added successfully');
        return redirect()
            ->back()
            ->with('model', $models);
    }
    public function updatedeleteIncidentProtection(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $incident_protection = DB::table('protection_kit')->get();
        if ($request->opt == 1) {
            foreach ($incident_protection as $prot) {
                if (ucwords($prot->kit) == ucwords($request->updated)) {
                    Session::flash('msg2', 'Protection kit already exists');
                    return redirect()->back();
                }
            }
            DB::table('protection_kit')
                ->where('id', $request->id)
                ->update(['kit' => ucwords($request->updated)]);
            Session::flash('msg2', 'Protection kit updated successfully');
        } else {
            DB::table('protection_kit')
                ->where('id', $request->id)
                ->delete();
            Session::flash('msg2', 'Protection kit deleted successfully');
        }
        return redirect()
            ->back()
            ->with('model', $models);
    }

    public function changePasswordpage()
    {
        $models = DB::table('modenadisable')->First();
        return view('auth/passwords/changepassadmin')->with('model', $models);
    }

    // employee details
    public function employeedetails()
    {
        //$empdetails = DB::table('employee_details')->get();
        $empdetails = DB::table('employee_details')
            ->where('is_deleted', false)
            ->get();
        $models = DB::table('modenadisable')->First();
        $department = Departments::all();
        return view('admin.employeelist')
            ->with('empdetails', $empdetails)
            ->with('model', $models)
            ->with('departments', $department);
    }

    public function addemployeedetails()
    {
        $models = DB::table('modenadisable')->First();
        return view('admin.addemployees')->with('model', $models);
    }

    //reads the employee details from excel sheet and adds it to database.
    public function add_to_database(Request $request)
    {
        // $this->validate($request,[
        //     'emp_details'=>'required|mimes:xls,xlsx'
        // ]);
        try {
            $validator = Validator::make($request->all(), [
                'emp_details' => 'required|mimes:xls,xlsx',
            ]);
            if ($validator->fails()) {
                $models = DB::table('modenadisable')->First();
                return back()->withErrors($validator)->withInput()->with('model', $models);
            } else {
                $rows = Excel::toArray(
                    new EmployeeImport(),
                    $request->file('emp_details')
                )[0];
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $rowNumber = $key + 2;

                        // Required fields
                        if (empty($row['emp_number'])) {
                            throw new \Exception("In row {$rowNumber}, Employee Number is required.");
                        }

                        if (empty($row['emp_name'])) {
                            throw new \Exception("In row {$rowNumber}, Employee Name is required.");
                        }

                        // Validate and convert DOJ
                        if (!empty($row['doj'])) {
                            if (!is_numeric($row['doj'])) {
                                throw new \Exception(
                                    "In row {$rowNumber}, doj(Date of Joining) must be a valid Excel date."
                                );
                            }

                            try {
                                $doj = Carbon::instance(
                                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                                        $row['doj']
                                    )
                                )->format('d-m-Y');
                            } catch (\Exception $e) {
                                throw new \Exception(
                                    "In row {$rowNumber}, doj(Date of Joining) is not a valid date."
                                );
                            }
                        }

                        // Validate and convert DOB
                        if (!empty($row['dob'])) {
                            if (!is_numeric($row['dob'])) {
                                throw new \Exception(
                                    "In row {$rowNumber}, dob(Date of Birth) must be a valid Excel date."
                                );
                            }

                            try {
                                $dob = Carbon::instance(
                                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                                        $row['dob']
                                    )
                                )->format('d-m-Y');
                            } catch (\Exception $e) {
                                throw new \Exception(
                                    "In row {$rowNumber}, dob(Date of Birth) is not a valid date."
                                );
                            }
                        }
                    }
                    $nonExistingDepartmentRows = [];
                    $newlyCreatedEmployees = [];
                    $skippedEmployees = [];
                    $updatedEmployees = [];
                    foreach ($rows as $row) {
                        if ($row['position'] == null) {
                            $emppost = '-';
                        } else {
                            $emppost = $row['position'];
                        }

                        if ($row['dob'] != null) {
                            $dob = Carbon::instance(
                                \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                                    $row['dob']
                                )
                            )->format('d-m-Y');
                        } else {
                            $dob = '-';
                        }
                        if ($row['doj'] != null) {
                            $doj = Carbon::instance(
                                \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                                    $row['doj']
                                )
                            )->format('d-m-Y');
                        } else {
                            $doj = '-';
                        }

                        $department = Departments::where('departmentname', $row['department'])->first();
                        $depid = $department ? $department->id : null;

                        if ($depid === null) {
                            $depid = '-';
                            array_push($nonExistingDepartmentRows, $row['department']);
                        }

                        $employeeDetails = DB::table('employee_details')->where('emp_number', $row['emp_number'])->first();
                        if ($employeeDetails) {
                            if ($depid != '-') {
                                if ($depid == $employeeDetails->emp_department && $emppost == $employeeDetails->emp_position) {
                                    array_push($skippedEmployees, $row);
                                } else {
                                    DB::table('employee_details')
                                    ->where('id', $employeeDetails->id)
                                    ->update([
                                        'emp_position' => $emppost,
                                        'emp_department' => $depid,
                                        // 'joining_date' => $doj,
                                        // 'emp_dob' => $dob,
                                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    ]);
                                    array_push($updatedEmployees, $row);
                                    $user = DB::table('users')->where('employeeid')->first();
                                    if ($user) {
                                        DB::table('users')
                                        ->where('id', $user->id)
                                        ->update([
                                            'deptid' => $department->departmentname,
                                        ]);
                                    }
                                }
                            } else {
                                if ($emppost != $employeeDetails->emp_position) {
                                    DB::table('employee_details')
                                    ->where('id', $employeeDetails->id)
                                    ->update([
                                        'emp_position' => $emppost,
                                        // 'emp_department' => $depid,
                                        // 'joining_date' => $doj,
                                        // 'emp_dob' => $dob,
                                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    ]);
                                    array_push($updatedEmployees, $row);
                                } else {
                                    array_push($skippedEmployees, $row);
                                }
                            }

                        } else {
                            DB::table('employee_details')->insert([
                                'emp_number' => $row['emp_number'],
                                'emp_name' => $row['emp_name'],
                                'emp_position' => $emppost,
                                'emp_department' => $depid,
                                'joining_date' => $doj,
                                'emp_dob' => $dob,
                                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            ]);
                            array_push($newlyCreatedEmployees, $row);
                        }
                    }
                }

                if (!File::exists(public_path('/storage/OccupationalHealth/'))) {
                    File::makeDirectory(
                        public_path() . '/storage/OccupationalHealth/',
                        0755,
                        true
                    );
                }

                $name =
                'employee_details' .
                '.' .
                $request->file('emp_details')->extension();
                $request
                    ->file('emp_details')
                    ->move(public_path() . '/storage/OccupationalHealth/', $name);

                //Session::flash('success', 'Employee Details Uploaded Successfully');
                //return redirect()->back();
                $empdetails = DB::table('employee_details')->get();
                $models = DB::table('modenadisable')->First();
                $flashMessage = "
                    <h5>Employee Details Uploaded successfully</h5>
                    <ul>
                        <li>".count($newlyCreatedEmployees)." New employees uploaded</li>
                        <li>".count($updatedEmployees)." Existing employees updated</li>
                        <li>".count($skippedEmployees)." skipped employees</li>
                        <li>".count($nonExistingDepartmentRows)." Employees with unmatched departments</li>
                    </ul>
                ";
                return redirect('/admin/employeelist')
                    ->with('model', $models)
                    ->with('message', $flashMessage)
                    ->with('empdetails', $empdetails);
            }
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
    }
    /*
    public function add_more_empdetails(Request $request)
    {
    try {
    if (count($request->doc) > 0)
    {
    for ($i = 0; $i < count($request->doc); $i++)
    {
    $doj = Carbon::parse($request->doc[$i]['doj']);
    $dob = Carbon::parse($request->doc[$i]['dob']);
    DB::table('employee_details')->insert(['emp_number' => $request->doc[$i]['number'], 'emp_name' => $request->doc[$i]['name'], 'emp_position' => $request->doc[$i]['position'], 'emp_department' => $request->doc[$i]['department'], 'joining_date' => $doj->format('d-m-Y'), 'emp_dob' => $dob->format('d-m-Y')]);
    //Session::flash('success', 'Employee Details Added Successfully');
    //return redirect()->back();
    $empdetails = DB::table('employee_details')->get();
    $models = DB::table('modenadisable')->First();
    return redirect('/admin/employeelist')->with('model', $models)->with('message', 'Employee details added successfully')->with('empdetails', $empdetails);
    }
    }
    else
    {
    Session::flash('error', 'Please enter all the fields');
    return redirect()->back();
    }
    }
    catch (\Exception $e)
    {
    Session::flash('error', $e->getMessage());
    return redirect()->back();
    }
    }
     */
    public function more_empdetails(Request $request)
    {
        try {
            if (count($request->doc) > 0) {
                for ($i = 0; $i < count($request->doc); $i++) {
                    //$doj = Carbon::parse($request->doc[$i]['doj']);
                    //$dob = Carbon::parse($request->doc[$i]['dob']);

                    if ($request->doc[$i]['number'] === null) {
                        throw new \Exception('Employee Number is required.');
                    }
                    if ($request->doc[$i]['name'] === null) {
                        throw new \Exception('Employee Name is required.');
                    }
                    if (DB::table('employee_details')->where('emp_number', $request->doc[$i]['number'])->exists()) {
                        throw new \Exception('Employee Number already exists.');
                    }
                    if ($request->doc[$i]['doj'] == null) {
                        $doj = '-';
                    } else {
                        $doj = Carbon::parse($request->doc[$i]['doj']);
                        $doj = $doj->format('d-m-Y');
                    }

                    if ($request->doc[$i]['dob'] == null) {
                        $dob = '-';
                    } else {
                        $dob = Carbon::parse($request->doc[$i]['dob']);
                        $dob = $dob->format('d-m-Y');
                    }

                    if ($request->doc[$i]['position'] == null) {
                        $emppost = '-';
                    } else {
                        $emppost = $request->doc[$i]['position'];
                    }

                    $depid = Departments::where(
                        'departmentname',
                        $request->doc[$i]['department']
                    )->value('id');
                    $depid = $depid ? $depid : '-';
                    $data[] = [
                        'emp_number' => $request->doc[$i]['number'],
                        'emp_name' => $request->doc[$i]['name'],
                        'emp_position' => $emppost,
                        'emp_department' => $depid,
                        'joining_date' => $doj,
                        'emp_dob' => $dob,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ];
                }

                DB::table('employee_details')->insert($data);
                $empdetails = DB::table('employee_details')->get();
                $models = DB::table('modenadisable')->First();
                return redirect('/admin/employeelist')
                    ->with('model', $models)
                    ->with('message', 'Employee details added successfully')
                    ->with('empdetails', $empdetails);
            } else {
                Session::flash('error', 'Please enter all the fields');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function displayeditEmp($id)
    {
        $models = DB::table('modenadisable')->First();
        $empdetails = DB::table('employee_details')
            ->where('emp_number', $id)
            ->First();
        return view('admin.editemployees')
            ->with('model', $models)
            ->with('emp', $empdetails);
    }

    public function updateEmp(Request $req, $id)
    {
        $name = $req->name;
        $position = $req->position;
        $department = $req->dept;
        $doj = $req->joindt;
        $dob = $req->dob;

        DB::table('employee_details')
            ->where('emp_number', $id)
            ->update([
                'emp_name' => $name,
                'emp_position' => $position,
                'emp_department' => $department,
                'joining_date' => $doj,
                'emp_dob' => $dob,
            ]);

        $models = DB::table('modenadisable')->First();
        $empdetails = DB::table('employee_details')->get();
        return redirect('/admin/employeelist')
            ->with('model', $models)
            ->with('message', 'Employee Details Edited successfully')
            ->with('empdetails', $empdetails);
    }

    public function deleteemployeeID(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            return $this->removeEmp($id)->with('model', $models);
        }
    }

    public function removeEmp($id)
    {
        $empdetails = DB::table('employee_details')->get();
        $models = DB::table('modenadisable')->First();

        $name = DB::table('employee_details')
            ->where('emp_number', $id)
            ->pluck('emp_name');
        $activity = 'Employee is Deleted with Name ' . $name . ' and ID ' . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        DB::table('viewactivity')->insert($datas);

        DB::delete('delete from employee_details where emp_number = :eid', [
            'eid' => $id,
        ]);
        return redirect('/admin/employeelist')
            ->with('model', $models)
            ->with('message', 'Employee deleted successfully')
            ->with('empdetails', $empdetails);
    }

    public function multipleaction(Request $request)
    {
        switch ($request->input('submit')) {
            case 'Delete':
                $id = $request->id;
                foreach ($request->id as $key => $value) {
                    DB::table('employee_details')
                        ->where('emp_number', $request->id[$key])
                        ->update([
                            'is_deleted' => 1,
                            'updated_at' => Carbon::now()->format(
                                'Y-m-d H:i:s'
                            ),
                        ]);
                }
                return redirect()
                    ->back()
                    ->with('message', 'Employee deleted successfully');
                break;
            case 'Force Delete':
                $id = $request->id;
                foreach ($request->id as $key => $value) {
                    $emp_id = $request->id[$key];
                    DB::table('employee_details')
                        ->where('emp_number', $emp_id)
                        ->delete();

                }
                return redirect()
                    ->back()
                    ->with('message', 'Employee deleted successfully, permenantly');
                break;

            case 'Edit':
                $id = $request->id;
                $models = DB::table('modenadisable')->First();
                $empdetails = DB::table('employee_details')
                    ->whereIn('emp_number', $id)
                    ->get();
                $depart = Departments::all();
                return view('admin.editallemployees')
                    ->with('model', $models)
                    ->with('empdetails', $empdetails)
                    ->with('dept', $depart);
                break;
        }
    }

    public function updateallEmp(Request $request)
    {
        $ids = $request->id;
        foreach ($request->id as $key => $value) {
            $emp = DB::table('employee_details')
                ->where('emp_number', $request->id[$key])
                ->update([
                    'emp_name' => $request->name[$key],
                    'emp_position' => $request->position[$key],
                    'emp_department' => $request->dept[$key],
                    'joining_date' => $request->joindt[$key],
                    'emp_dob' => $request->dob[$key],
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            $user = User::where('employeeid', $request->id[$key])->first();
            if ($user) {
                $department = Departments::find($request->dept[$key]);
                $user->name = $request->name[$key];
                $user->deptid = $department ? $department->departmentname : null;
                $user->update();
            }
        }
        $models = DB::table('modenadisable')->First();
        return redirect('/admin/employeelist')
            ->with('model', $models)
            ->with('message', 'Employee Details edited successfully');
    }

    //calibration master list
    /* public function calibrationdetails()
    {
    $caldetails = DB::table('calibration_instruments')->get();
    $models = DB::table('modenadisable')->First();
    return view('admin.calibrationlist')->with('caldetails', $caldetails)->with('model', $models);
    //return view('admin.test')->with('caldetails', $caldetails)->with('model', $models);
    }*/

    //calibration master list
    public function calibrationindex(Request $request)
    {
        if ($request->search == null) {
            $items = $request->items ?? 25;
            $caldetails = DB::table('calibration_instruments')->latest('id')->paginate($items);
            $srch_val = null;
        } else {
            $items = $request->items ?? 25;
            $srch_val = $request->input('search');
            if (strtolower($srch_val) == 'not calibrated') {
                $caldetails = DB::table('calibration_instruments')
                    ->where('Status', '=', '')
                    ->orWhereNull('Status')
                    ->select('calibration_instruments.*')
                    ->paginate($items);
            } else {
                $caldetails = DB::table('calibration_instruments')
                    ->where(
                        'calibration_instruments.Instrument_no',
                        'like',
                        '%' . $srch_val . '%'
                    )
                    ->orWhere(
                        'calibration_instruments.Department',
                        'like',
                        '%' . $srch_val . '%'
                    )
                    ->orWhere(
                        'calibration_instruments.Type',
                        'like',
                        '%' . $srch_val . '%'
                    )
                    ->orWhere(
                        'calibration_instruments.Instrument_desc',
                        'like',
                        '%' . $srch_val . '%'
                    )
                    ->orWhere(
                        'calibration_instruments.Frequency',
                        'like',
                        '%' . $srch_val . '%'
                    )
                    ->orWhere(
                        'calibration_instruments.Status',
                        'like',
                        '%' . $srch_val . '%'
                    )
                    ->orWhere(
                        'calibration_instruments.Holder',
                        'like',
                        '%' . $srch_val . '%'
                    )
                    ->orWhere(
                        'calibration_instruments.Capacity',
                        'like',
                        '%' . $srch_val . '%'
                    )
                    ->orWhere(
                        'calibration_instruments.Agency',
                        'like',
                        '%' . $srch_val . '%'
                    )
                    ->orwhere(
                        'calibration_instruments.Duedate',
                        'like',
                        '%' . date('Y-m-d', strtotime($srch_val)) . '%'
                    )
                    ->orwhere(
                        'calibration_instruments.dateofcal',
                        'like',
                        '%' . date('Y-m-d', strtotime($srch_val)) . '%'
                    )
                    ->select('calibration_instruments.*')
                    ->paginate($items);
            }
            $caldetails->appends(['search' => $srch_val]);
            //$data->appends(array('search'=> $srch_val));
        }
        $model = DB::table('modenadisable')->First();
        $departments = Departments::all();
        return view('admin.calibrationlist')
            ->with('model', $model)
            ->with('caldetails', $caldetails)
            ->with('departments', $departments)
            ->withItems($items)
            ->with('serchval', $srch_val);
    }

    public function addcalibrationlist()
    {
        $models = DB::table('modenadisable')->First();
        return view('admin.addcalibration')->with('model', $models);
    }

    //reads the calibration details from excel sheet and adds it to database.
    public function insertcal(Request $request)
    {
        try {
            $rows = Excel::toArray(new CalibrationImport(), $request->file('cal_details'))[0];
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    $duedate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['duedate']));
                    $dateofcal = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['dateofcal']));
                    $datecalformat = $dateofcal->format('Y-m-d', Carbon::parse($dateofcal));
                    $dudateformat = $duedate->format('Y-m-d', Carbon::parse($duedate));

                    if ($row['dateofcal'] == null) {
                        $caldate = null;
                    } else {
                        $caldate = $datecalformat;
                    }

                    if ($row['duedate'] == null) {
                        $dudate = null;
                    } else {
                        $dudate = $dudateformat;
                    }

                    $instrumentno = preg_replace('/[^A-Za-z0-9\-]/', '', $row['instrument_no']);
                    $depid = Departments::where('departmentname', $row['department'])->value('id');

                    $data = [
                        'Instrument_no' => preg_replace('/[^A-Za-z0-9\-]/', '', $row['instrument_no']),
                        'Instrument_desc' => $row['instrument_desc'],
                        'Instru_Manufacturer' => 'no value',
                        'Department' => $depid,
                        'Holder' => $row['holder'],
                        'Capacity' => $row['capacity'],
                        'Type' => trim(strtolower($row['type'])),
                        'Frequency' => trim($row['frequency']),
                        'standard_edition' => $row['standard_edition'],
                        'acceptance_criteria' => $row['acceptance_criteria'],
                        'Agency' => $row['agency'],
                        'Duedate' => $dudate,
                        'dateofcal' => $caldate,
                        //'Status' => trim(ucwords($row['status'])),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ];

                    DB::table('calibration_instruments')->insert($data);
                    $insid = DB::table('calibration_instruments')
                        ->select('id')
                        ->where('Instrument_no', $instrumentno)
                        ->first()->id;

                    $data1 = [
                        'Instrument_no' => preg_replace('/[^A-Za-z0-9\-]/', '', $row['instrument_no']),
                        'Duedate' => $dudate,
                        'Dateofcal' => $caldate,
                        //'Status' => trim(ucwords($row['status'])),
                        'instrument_id' => $insid,
                    ];

                    $data2 = [
                        'Instrument_no' => preg_replace('/[^A-Za-z0-9\-]/', '', $row['instrument_no']),
                        'Acc_error' => 'novalue',
                        'M_instru' => 'novalue',
                        'Certificate_no' => 'novalue',
                        'M_reading' => 'novalue',
                        'Instrument_reading' => 'novalue',
                        'Error' => 'novalue',
                        'Remarks' => 'novalue',
                        'Report_no' => 'novalue',
                        'Adj_made' => 'novalue',
                        'Calibrated_by' => 'novalue',
                        'Verified_by' => 'novalue',
                        'Calibrated_by' => 'novalue',
                        'Duedate' => $dudate,
                        'Dateofcal' => $caldate,
                        //'Status' => trim(ucwords($row['status'])),
                        'instrument_id' => $insid,
                    ];

                    if ($row['type'] == 'External' || $row['type'] == 'external') {
                        DB::table('calibration_external')->insert($data1);
                    } else {
                        DB::table('calibration_internal')->insert($data2);
                    }
                }
            }

            if (!File::exists(public_path('/storage/calibration/'))) {
                File::makeDirectory(public_path() . '/storage/calibration/', 0755, true);
            }

            //$name ='calibration'.'.' .$request->file('cal_details')->extension();
            $name = 'calibration' . '.' . $request->file('cal_details')->extension();
            $request->file('cal_details')->move(public_path() . '/storage/calibration/', $name);

            Session::flash('success', 'Calibration Master List Uploaded Successfully');
            return redirect('/admin/addcalibration');
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    //calibration edit and delete
    public function multiplecalaction(Request $request)
    {
        switch ($request->input('submit')) {
            case 'Delete':
                $delid = $request->id;
                DB::table('calibration_instruments')
                    ->whereIn('Instrument_no', $delid)
                    ->delete();
                DB::table('calibration_internal')
                    ->whereIn('Instrument_no', $delid)
                    ->delete();
                DB::table('calibration_external')
                    ->whereIn('Instrument_no', $delid)
                    ->delete();
                DB::table('calibration_externalfiles')
                    ->whereIn('Instrument_no', $delid)
                    ->delete();
                return redirect('/admin/calibrationlist')->with(
                    'message',
                    'Instrument  deleted successfully'
                );
                break;

            case 'Edit':
                $id = $request->id;
                $models = DB::table('modenadisable')->First();
                $caldetails = DB::table('calibration_instruments')
                    ->whereIn('Instrument_no', $id)
                    ->get();
                $cal_freq = DB::table('calibration_frequency')->get();
                $depart = DB::table('departments')->get();
                return view('admin.editcalibrationlist')
                    ->with('model', $models)
                    ->with('caldetails', $caldetails)
                    ->with('freq', $cal_freq)
                    ->with('dept', $depart);
                break;
        }
    }

    public function updateallcal(Request $request)
    {
        $ids = $request->id;

        foreach ($request->id as $key => $value) {
            $c = Carbon::createFromFormat(
                'd-m-Y',
                $request->caldt[$key]
            )->format('Y-m-d');
            $inst_frequency = $request->freq[$key];
            $freq = $inst_frequency[0];

            //$c=date('Y-m-d');

            if (
                $inst_frequency == '1 year' ||
                $inst_frequency == '1 Year' ||
                $inst_frequency == 'one year' ||
                $inst_frequency == 'One year' ||
                $inst_frequency == 'One Year'
            ) {
                $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(1);
                $status = 'Calibrated';
            } elseif (
                $inst_frequency == 'Two Year' ||
                $inst_frequency == 'Two Years' ||
                $inst_frequency == '2 Year' ||
                $inst_frequency == '2 Years' ||
                $inst_frequency == '2 year' ||
                $inst_frequency == '2 years' ||
                $inst_frequency == 'Two year' ||
                $inst_frequency == 'Two years' ||
                $inst_frequency == 'two year' ||
                $inst_frequency == 'two years'
            ) {
                $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(2);
                $status = 'Calibrated';
            } elseif (
                $inst_frequency == 'Three Year' ||
                $inst_frequency == 'Three Years' ||
                $inst_frequency == '3 Year' ||
                $inst_frequency == '3 Years' ||
                $inst_frequency == '3 year' ||
                $inst_frequency == '3 years' ||
                $inst_frequency == 'Three year' ||
                $inst_frequency == 'Three years' ||
                $inst_frequency == 'three year' ||
                $inst_frequency == 'three years'
            ) {
                $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(3);
                $status = 'Calibrated';
            } elseif (
                $inst_frequency == 'Four Year' ||
                $inst_frequency == 'Four Years' ||
                $inst_frequency == '4 Year' ||
                $inst_frequency == '4 Years' ||
                $inst_frequency == '4 year' ||
                $inst_frequency == '4 years' ||
                $inst_frequency == 'Four year' ||
                $inst_frequency == 'Four years' ||
                $inst_frequency == 'four year' ||
                $inst_frequency == 'four years'
            ) {
                $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(4);
                $status = 'Calibrated';
            } elseif (
                $inst_frequency == 'Quarterly' ||
                $inst_frequency == 'quarterly' ||
                $inst_frequency == 'Quarter' ||
                $inst_frequency == 'quarter' ||
                $inst_frequency == '3 Months' ||
                $inst_frequency == '3 months' ||
                $inst_frequency == '3 month' ||
                $inst_frequency == '3 Month'
            ) {
                // $due=Carbon::createFromFormat('Y-m-d', $c)->addYear(5);
                $d = strtotime('+3 months', strtotime($c));
                $due = date('Y-m-d', $d);
                $status = 'Calibrated';
            } elseif (
                $inst_frequency == 'Half Year' ||
                $inst_frequency == 'Half year' ||
                $inst_frequency == 'half year' ||
                $inst_frequency == '6 Month' ||
                $inst_frequency == '6 months' ||
                $inst_frequency == '6 month' ||
                $inst_frequency == '6 Months'
            ) {
                // $due=Carbon::createFromFormat('Y-m-d', $c)->addYear(5);
                $d = strtotime('+6 months', strtotime($c));
                $due = date('Y-m-d', $d);
                $status = 'Calibrated';
            } elseif (
                $inst_frequency == '1.5 Year' ||
                $inst_frequency == '1.5 Years' ||
                $inst_frequency == '1.5 years' ||
                $inst_frequency == '1.5 year'
            ) {
                $y = Carbon::createFromFormat('Y-m-d', $c)->addYear(1);
                $d = strtotime('+6 months', strtotime($y));
                $due = date('Y-m-d', $d);
                $status = 'Calibrated';
            } else {
                $due = Carbon::createFromFormat('Y-m-d', $c)->addYear($freq);
                $status = 'Calibrated';
            }

            if (isset($request->agency[$key])) {
                $agency = $request->agency[$key];
            }

            DB::table('calibration_instruments')
                ->where('Instrument_no', $request->id[$key])
                ->update([
                    'Instrument_desc' => $request->desc[$key],
                    'Department' => $request->dept[$key],
                    'Holder' => $request->holder[$key],
                    'Capacity' => $request->capacity[$key],
                    'Type' => $request->type[$key],
                    'Frequency' => $request->freq[$key],
                    'standard_edition' => $request->standard_edition[$key],
                    'acceptance_criteria' => $request->acceptance_criteria[$key],
                    'Duedate' => $due,
                    'dateofcal' => Carbon::createFromFormat(
                        'd-m-Y',
                        $request->caldt[$key]
                    )->format('Y-m-d'),
                    'Status' => $status,
                ]);

            if (
                $request->type[$key] == 'External' ||
                $request->type[$key] == 'external'
            ) {
                DB::table('calibration_instruments')
                    ->where('Instrument_no', $request->id[$key])
                    ->update(['Agency' => $agency]);

                $chk = DB::table('calibration_external')
                    ->where('Instrument_no', $request->id[$key])
                    ->get();
                if (count($chk) > 0) {
                    DB::table('calibration_external')
                        ->where('Instrument_no', $request->id[$key])
                        ->update([
                            'Duedate' => $due,
                            'Dateofcal' => Carbon::createFromFormat(
                                'd-m-Y',
                                $request->caldt[$key]
                            )->format('Y-m-d'),
                            'Status' => $status,
                        ]);
                } else {
                    DB::table('calibration_external')->insert([
                        'Instrument_no' => $request->id[$key],
                        'Duedate' => $due,
                        'Dateofcal' => Carbon::createFromFormat(
                            'd-m-Y',
                            $request->caldt[$key]
                        )->format('Y-m-d'),
                        'Status' => $status,
                    ]);
                }
            } else {
                $chkint = DB::table('calibration_internal')
                    ->where('Instrument_no', $request->id[$key])
                    ->get();
                if (count($chkint) > 0) {
                    DB::table('calibration_internal')
                        ->where('Instrument_no', $request->id[$key])
                        ->update([
                            'Duedate' => $due,
                            'Dateofcal' => Carbon::createFromFormat(
                                'd-m-Y',
                                $request->caldt[$key]
                            )->format('Y-m-d'),
                            'Status' => $status,
                        ]);
                } else {
                    DB::table('calibration_internal')->insert([
                        'Instrument_no' => $request->id[$key],
                        'Acc_error' => 'novalue',
                        'M_instru' => 'novalue',
                        'Certificate_no' => 'novalue',
                        'M_reading' => 'novalue',
                        'Instrument_reading' => 'novalue',
                        'Error' => 'novalue',
                        'Remarks' => 'novalue',
                        'Report_no' => 'novalue',
                        'Adj_made' => 'novalue',
                        'Calibrated_by' => 'novalue',
                        'Verified_by' => 'novalue',
                        'Calibrated_by' => 'novalue',
                        'Duedate' => $due,
                        'Dateofcal' => Carbon::createFromFormat(
                            'd-m-Y',
                            $request->caldt[$key]
                        )->format('Y-m-d'),
                        'Status' => $status,
                    ]);
                }
            }
        }

        $models = DB::table('modenadisable')->First();
        return redirect('/admin/calibrationlist')
            ->with('model', $models)
            ->with('message', 'Instrument Details edited successfully');
    }

    //document master list index
    public function documentindex(Request $request)
    {
        if ($request->search == null) {
            $items = $request->items ?? 25;
            $docdetails = DB::table('masterdocs')->paginate($items);
            $srch_val = null;
        } else {
            $items = $request->items ?? 25;
            $srch_val = $request->input('search');

            $docdetails = DB::table('masterdocs')
                ->where('masterdocs.document', 'like', '%' . $srch_val . '%')
                ->orWhere('masterdocs.docnumber', 'like', '%' . $srch_val . '%')
                ->orWhere('masterdocs.type', 'like', '%' . $srch_val . '%')
                ->orWhere('masterdocs.level', 'like', '%' . $srch_val . '%')
                ->orWhere('masterdocs.revnumber', 'like', '%' . $srch_val . '%')
                ->orwhere(
                    'masterdocs.erevdate',
                    'like',
                    '%' . date('Y-m-d', strtotime($srch_val)) . '%'
                )
                ->orWhere(
                    'masterdocs.department',
                    'like',
                    '%' . $srch_val . '%'
                )
                ->select('masterdocs.*')
                ->paginate($items);

            $docdetails->appends(['search' => $srch_val]);
            //$data->appends(array('search'=> $srch_val));
        }
        $model = DB::table('modenadisable')->First();
        return view('admin.documentmasterlist')
            ->with('model', $model)
            ->with('docdetails', $docdetails)
            ->withItems($items)
            ->with('serchval', $srch_val);
    }

    public function adddocumentmaster()
    {
        $models = DB::table('modenadisable')->First();
        return view('admin.adddocumentmaster')->with('model', $models);
    }

    //reads the document details from excel sheet and adds it to database.
    public function insertdocmaster(Request $request)
    {
        try {
            $rows = Excel::toArray(
                new DocumentMasterImport(),
                $request->file('docmaster_details')
            )[0];
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    $department = $row['department'];
                    $hods = Departments::where(
                        'departmentname',
                        $department
                    )->pluck('hod');
                    foreach ($hods as $hod) {
                        $hodemail = $hod;
                    }
                    $nrevnumber = trim($row['revisionnumber']) + 1;
                    $revision_date = Carbon::instance(
                        \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                            trim($row['revisiondate'])
                        )
                    );
                    $revdateformat = $revision_date->format(
                        'Y-m-d',
                        Carbon::parse($revision_date)
                    );
                    $nrevdate = date(
                        'Y-m-d',
                        strtotime('+1 day', strtotime($revdateformat))
                    );
                    $postedby = 'admin';

                    $data = [
                        'document' => trim($row['document']),
                        'hod' => $hodemail,
                        'docnumber' => trim($row['documentnumber']),
                        'type' => trim(ucfirst($row['type'])),
                        'level' => trim($row['level']),
                        'revnumber' => trim($row['revisionnumber']),
                        'nrevnumber' => $nrevnumber,
                        'erevdate' => $revdateformat,
                        'nrevdate' => $nrevdate,
                        'department' => trim(ucfirst($row['department'])),
                        'postedby' => $postedby,
                    ];
                    Masterdoc::create($data);
                }
            }

            if (!File::exists(public_path('/storage/DocumentModule/'))) {
                File::makeDirectory(
                    public_path() . '/storage/DocumentModule/',
                    0755,
                    true
                );
            }

            $name =
            'document_masterlist' .
            '.' .
            $request->file('docmaster_details')->extension();
            $request
                ->file('docmaster_details')
                ->move(public_path() . '/storage/DocumentModule/', $name);

            $docdetails = Masterdoc::all();
            $models = DB::table('modenadisable')->First();
            return redirect('/admin/documentmasterlist')
                ->with('model', $models)
                ->with('message', 'Document Master List Uploaded successfully')
                ->with('docdetails', $docdetails);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    //document masterlist delete
    public function multipledocmasteraction(Request $request)
    {
        $delid = $request->input('id');
        DB::table('masterdocs')
            ->whereIn('id', $delid)
            ->delete();
        return redirect('/admin/documentmasterlist')->with(
            'message',
            'Document Master List  deleted successfully'
        );
    }

    //Occupational health

    //Category
    public function healthcategorylist()
    {
        $models = DB::table('modenadisable')->First();
        $category = HealthCategory::all();
        return view('admin.healthcategory')
            ->with('model', $models)
            ->with('category', $category);
    }

    public function addhealthcategory(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $category = HealthCategory::all();
        $catexist = HealthCategory::where('name', $request->cat_name)->get();
        if (count($catexist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Category already exists')
                ->with('category', $category);
        } else {
            $data = [
                'name' => $request->cat_name,
            ];
            HealthCategory::create($data);

            //inserting data to activities table
            $activity =
            'New Health category is added with Name as ' .
            $request->cat_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return back()
                ->with('model', $models)
                ->with('message', 'Category added successfully')
                ->with('category', $category);
        }
    }

    public function edithealthcategory($id)
    {
        $category = HealthCategory::where('id', $id)->First();
        $models = DB::table('modenadisable')->First();
        return view('admin.edithealthcategory')
            ->with('category', $category)
            ->with('model', $models);
    }

    public function updatehealthcategory(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $category = HealthCategory::all();
        $catexist = HealthCategory::where('name', $request->cat_name)->get();
        if (count($catexist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Category already exists')
                ->with('category', $category);
        } else {
            HealthCategory::where('id', $request->id)->update([
                'name' => $request->cat_name,
            ]);
            //inserting data to activities table
            $activity =
            'Health category with ID' .
            $request->id .
            ' is Edited and Updated with Name as ' .
            $request->cat_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return redirect('/admin/health/category')
                ->with('message', 'Category Updated successfully')
                ->with('category', $category)
                ->with('model', $models);
        }
    }

    public function deletehealthcategory($id)
    {
        $models = DB::table('modenadisable')->First();
        $category = HealthCategory::all();
        $catgrydel = HealthCategory::find($id);
        if ($catgrydel) {
            $name = HealthCategory::where('id', $id)->value('name');
            $activity = 'Health Category is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            HealthCategory::where('id', $id)->delete();
            return redirect('/admin/health/category')
                ->with('model', $models)
                ->with('message', 'Category deleted successfully')
                ->with('category', $category);
        } else {
            return redirect('/admin/health/category')
                ->with('model', $models)
                ->with('message', 'Category with this id not exists')
                ->with('category', $category);
        }
    }

    //Treatments
    public function healthtreatment()
    {
        $models = DB::table('modenadisable')->First();
        $treatment = HealthTreatment::all();
        return view('admin.healthtreatment')
            ->with('model', $models)
            ->with('treatment', $treatment);
    }

    public function addtreatment(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $treatment = HealthTreatment::all();
        $exist = HealthTreatment::where('name', $request->treatment)->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Treatment already exists')
                ->with('treatment', $treatment);
        } else {
            $data = [
                'name' => $request->treatment,
            ];
            HealthTreatment::create($data);

            //inserting data to activities table
            $activity =
            'New Health Treatment is added with Name as ' .
            $request->treatment;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return back()
                ->with('model', $models)
                ->with('message', 'Treatment added successfully')
                ->with('treatment', $treatment);
        }
    }

    public function edittreatment($id)
    {
        $treatment = HealthTreatment::where('id', $id)->First();
        $models = DB::table('modenadisable')->First();
        return view('admin.edithealthtreatment')
            ->with('treatment', $treatment)
            ->with('model', $models);
    }

    public function updatetreatment(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $treatment = HealthTreatment::all();
        $exist = HealthTreatment::where('name', $request->treatment)->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Treatment already exists')
                ->with('treatment', $treatment);
        } else {
            HealthTreatment::where('id', $request->id)->update([
                'name' => $request->treatment,
            ]);
            //inserting data to activities table
            $activity =
            'Health Treatment with ID' .
            $request->id .
            ' is Edited and Updated with Name as ' .
            $request->treatment;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return redirect('/admin/health/treatment')
                ->with('message', 'Treatment Updated successfully')
                ->with('treatment', $treatment)
                ->with('model', $models);
        }
    }

    public function deletetreatment($id)
    {
        $models = DB::table('modenadisable')->First();
        $treatment = HealthTreatment::all();
        $exist = HealthTreatment::find($id);
        if ($exist) {
            $name = HealthTreatment::where('id', $id)->value('name');
            $activity = 'Health Treatment is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            HealthTreatment::where('id', $id)->delete();
            return redirect('/admin/health/treatment')
                ->with('model', $models)
                ->with('message', 'Treatment deleted successfully')
                ->with('treatment', $treatment);
        } else {
            return redirect('/admin/health/treatment')
                ->with('model', $models)
                ->with('message', 'Treatment with this id not exists')
                ->with('treatment', $treatment);
        }
    }

    //Injury
    public function viewinjury()
    {
        $models = DB::table('modenadisable')->First();
        $injurywork = InjuryWorkrelatedcases::all();
        return view('admin.healthinjury')
            ->with('model', $models)
            ->with('injurywork', $injurywork);
    }

    public function addinjurywork(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $injurywork = InjuryWorkrelatedcases::all();
        $exist = InjuryWorkrelatedcases::where('name', $request->case)->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Case already exists')
                ->with('injurywork', $injurywork);
        } else {
            $data = [
                'name' => $request->case,
            ];
            InjuryWorkrelatedcases::create($data);

            //inserting data to activities table
            $activity =
            'New Work-Related Injury is added with Name as ' .
            $request->case;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return back()
                ->with('model', $models)
                ->with('message', 'Case added successfully')
                ->with('injurywork', $injurywork);
        }
    }

    public function editinjurywork($id)
    {
        $injurywork = InjuryWorkrelatedcases::where('id', $id)->First();
        $models = DB::table('modenadisable')->First();
        return view('admin.editinjurywork')
            ->with('injurywork', $injurywork)
            ->with('model', $models);
    }

    public function updateinjurywork(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $injurywork = InjuryWorkrelatedcases::all();
        $exist = InjuryWorkrelatedcases::where('name', $request->case)->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Case already exists')
                ->with('injurywork', $injurywork);
        } else {
            InjuryWorkrelatedcases::where('id', $request->id)->update([
                'name' => $request->case,
            ]);
            //inserting data to activities table
            $activity =
            'Work-Related Injury with ID' .
            $request->id .
            ' is Edited and Updated with Name as ' .
            $request->case;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return redirect('/admin/health/injury')
                ->with('message', 'Case Updated successfully')
                ->with('injurywork', $injurywork)
                ->with('model', $models);
        }
    }

    public function deleteinjurywork($id)
    {
        $models = DB::table('modenadisable')->First();
        $injurywork = InjuryWorkrelatedcases::all();
        $exist = InjuryWorkrelatedcases::find($id);
        if ($exist) {
            $name = InjuryWorkrelatedcases::where('id', $id)->value('name');
            $activity = 'Work-Related Injury is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            InjuryWorkrelatedcases::where('id', $id)->delete();
            return redirect('/admin/health/injury')
                ->with('model', $models)
                ->with('message', 'Case deleted successfully')
                ->with('injurywork', $injurywork);
        } else {
            return redirect('/admin/health/injury')
                ->with('model', $models)
                ->with('message', 'Case with this id not exists')
                ->with('injurywork', $injurywork);
        }
    }

    //Illness
    public function viewillness()
    {
        $models = DB::table('modenadisable')->First();
        $illwork = IllnessNonWorkrelatedcases::all();
        return view('admin.healthillness')
            ->with('model', $models)
            ->with('illwork', $illwork);
    }

    public function addillnessnonwork(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $illwork = IllnessNonWorkrelatedcases::all();
        $exist = IllnessNonWorkrelatedcases::where(
            'name',
            $request->case
        )->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Case already exists')
                ->with('illwork', $illwork);
        } else {
            $data = [
                'name' => $request->case,
            ];
            IllnessNonWorkrelatedcases::create($data);

            //inserting data to activities table
            $activity =
            'New Non Work-Related Illness is added with Name as ' .
            $request->case;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return back()
                ->with('model', $models)
                ->with('message', 'Case added successfully')
                ->with('illwork', $illwork);
        }
    }

    public function editillnonwork($id)
    {
        $illwork = IllnessNonWorkrelatedcases::where('id', $id)->First();
        $models = DB::table('modenadisable')->First();
        return view('admin.editillnessnonwork')
            ->with('illwork', $illwork)
            ->with('model', $models);
    }

    public function updateillnessnonwork(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $illwork = IllnessNonWorkrelatedcases::all();
        $exist = IllnessNonWorkrelatedcases::where(
            'name',
            $request->case
        )->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Case already exists')
                ->with('illwork', $illwork);
        } else {
            IllnessNonWorkrelatedcases::where('id', $request->id)->update([
                'name' => $request->case,
            ]);
            //inserting data to activities table
            $activity =
            'Non-Work Related Illness with ID' .
            $request->id .
            ' is Edited and Updated with Name as ' .
            $request->case;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return redirect('/admin/health/illness')
                ->with('message', 'Case Updated successfully')
                ->with('illwork', $illwork)
                ->with('model', $models);
        }
    }

    public function deleteillnonwork($id)
    {
        $models = DB::table('modenadisable')->First();
        $illwork = IllnessNonWorkrelatedcases::all();
        $exist = IllnessNonWorkrelatedcases::find($id);
        if ($exist) {
            $name = IllnessNonWorkrelatedcases::where('id', $id)->value('name');
            $activity =
                'Non-Work Related illness is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            IllnessNonWorkrelatedcases::where('id', $id)->delete();
            return redirect('/admin/health/illness')
                ->with('model', $models)
                ->with('message', 'Case deleted successfully')
                ->with('illwork', $illwork);
        } else {
            return redirect('/admin/health/illness')
                ->with('model', $models)
                ->with('message', 'Case with this id not exists')
                ->with('illwork', $illwork);
        }
    }

    //Nature of injury
    public function viewinjurynature()
    {
        $models = DB::table('modenadisable')->First();
        $work_natures = EmployeeHealthInjuryNature::where(
            'injury_type',
            'work_related'
        )->get();
        $nonwork_natures = EmployeeHealthInjuryNature::where(
            'injury_type',
            'non_work_related'
        )->get();
        return view('admin.healthinjurynature')
        //return view('admin.test')
        ->with('model', $models)
            ->with('work_natures', $work_natures)
            ->with('nonwork_natures', $nonwork_natures);
    }

    public function addinjurynature(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $natures = EmployeeHealthInjuryNature::all();
        $exist = EmployeeHealthInjuryNature::where(
            'name',
            $request->nature_name
        )
            ->where('injury_type', $request->injury_type)
            ->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Data already exists')
                ->with('natures', $natures);
        } else {
            $data = [
                'name' => $request->nature_name,
                'injury_type' => $request->injury_type,
            ];
            EmployeeHealthInjuryNature::create($data);

            //inserting data to activities table
            $activity =
            'Work Related Nature of injury is added with Name as ' .
            $request->nature_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return back()
                ->with('model', $models)
                ->with('message', 'Nature of injury added successfully')
                ->with('natures', $natures);
        }
    }

    public function editinjurynature($id)
    {
        $natures = EmployeeHealthInjuryNature::where('id', $id)->First();
        $models = DB::table('modenadisable')->First();
        return view('admin.edithealthinjurynature')
            ->with('natures', $natures)
            ->with('model', $models);
    }

    public function updateinjurynature(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $natures = EmployeeHealthInjuryNature::all();
        $exist = EmployeeHealthInjuryNature::where(
            'name',
            $request->nature_name
        )
            ->where('injury_type', $request->injury_type)
            ->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Data already exists')
                ->with('natures', $natures);
        } else {
            EmployeeHealthInjuryNature::where('id', $request->id)->update([
                'name' => $request->nature_name,
            ]);
            //inserting data to activities table
            $activity =
            'Nature of Injury with ID' .
            $request->id .
            ' is Edited and Updated with Name as ' .
            $request->nature_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return redirect('/admin/health/injurynature')
                ->with('message', 'Data Updated successfully')
                ->with('natures', $natures)
                ->with('model', $models);
        }
    }

    public function deleteinjurynature($id)
    {
        $models = DB::table('modenadisable')->First();
        $natures = EmployeeHealthInjuryNature::all();
        $exist = EmployeeHealthInjuryNature::find($id);
        if ($exist) {
            $name = EmployeeHealthInjuryNature::where('id', $id)->value('name');
            $activity = 'Nature of Injury is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            EmployeeHealthInjuryNature::where('id', $id)->delete();
            return redirect('/admin/health/injurynature')
                ->with('model', $models)
                ->with('message', 'Data deleted successfully')
                ->with('natures', $natures);
        } else {
            return redirect('/admin/health/injurynature')
                ->with('model', $models)
                ->with('message', 'Data with this id not exists')
                ->with('natures', $natures);
        }
    }

    //Nature of Illness
    public function viewillnessnature()
    {
        $models = DB::table('modenadisable')->First();
        //$natures = EmployeeHealthIllnessNature::all();
        $work_natures = EmployeeHealthIllnessNature::where(
            'illness_type',
            'work_related'
        )->get();
        $nonwork_natures = EmployeeHealthIllnessNature::where(
            'illness_type',
            'non_work_related'
        )->get();
        return view('admin.healthillnessnature')
            ->with('model', $models)
            ->with('work_natures', $work_natures)
            ->with('nonwork_natures', $nonwork_natures);
    }

    public function addillnessnature(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $natures = EmployeeHealthIllnessNature::all();
        $exist = EmployeeHealthIllnessNature::where(
            'name',
            $request->nature_name
        )
            ->where('illness_type', $request->illness_type)
            ->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Data already exists')
                ->with('natures', $natures);
        } else {
            $data = [
                'name' => $request->nature_name,
                'illness_type' => $request->illness_type,
            ];
            EmployeeHealthIllnessNature::create($data);

            //inserting data to activities table
            $activity =
            'Nature of Illness is added with Name as ' .
            $request->nature_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return back()
                ->with('model', $models)
                ->with('message', 'Nature of illness added successfully')
                ->with('natures', $natures);
        }
    }

    public function editillnessnature($id)
    {
        $natures = EmployeeHealthIllnessNature::where('id', $id)->First();
        $models = DB::table('modenadisable')->First();
        return view('admin.edithealthillnessnature')
            ->with('natures', $natures)
            ->with('model', $models);
    }

    public function updateillnessnature(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $natures = EmployeeHealthIllnessNature::all();
        $exist = EmployeeHealthIllnessNature::where(
            'name',
            $request->nature_name
        )
            ->where('illness_type', $request->illness_type)
            ->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Data already exists')
                ->with('natures', $natures);
        } else {
            EmployeeHealthIllnessNature::where('id', $request->id)->update([
                'name' => $request->nature_name,
            ]);
            //inserting data to activities table
            $activity =
            'Nature of Illness with ID' .
            $request->id .
            ' is Edited and Updated with Name as ' .
            $request->nature_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return redirect('/admin/health/illnessnature')
                ->with('message', 'Data Updated successfully')
                ->with('natures', $natures)
                ->with('model', $models);
        }
    }

    public function deleteillnessnature($id)
    {
        $models = DB::table('modenadisable')->First();
        $natures = EmployeeHealthIllnessNature::all();
        $exist = EmployeeHealthIllnessNature::find($id);
        if ($exist) {
            $name = EmployeeHealthIllnessNature::where('id', $id)->value(
                'name'
            );
            $activity = 'Nature of Illness is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            EmployeeHealthIllnessNature::where('id', $id)->delete();
            return redirect('/admin/health/illnessnature')
                ->with('model', $models)
                ->with('message', 'Data deleted successfully')
                ->with('natures', $natures);
        } else {
            return redirect('/admin/health/illnessnature')
                ->with('model', $models)
                ->with('message', 'Data with this id not exists')
                ->with('natures', $natures);
        }
    }

    /**---Management Review--- */

    //Agenda Type- ManagementReview
    public function managementagendatypes()
    {
        $models = DB::table('modenadisable')->First();
        $types = ManagementAgendaType::all();
        return view('admin.managementreview.agendatype')
            ->with('model', $models)
            ->with('types', $types);
    }

    public function addagendatypes(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $types = ManagementAgendaType::all();
        $exist = ManagementAgendaType::where('name', $request->name)->get();
        if (count($exist) > 0) {
            return back()->with('model', $models)
                ->with('msg', 'Type already exists')
                ->with('types', $types);
        } else {
            $data = [
                'name' => $request->name,
            ];
            ManagementAgendaType::create($data);

            //inserting data to activities table
            $activity = 'New Agenda Type is added with Name as ' . $request->name . 'for Management Review';
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return back()
                ->with('model', $models)
                ->with('message', 'Agenda type added successfully')
                ->with('types', $types);
        }
    }

    public function editagendatypes($id)
    {
        $types = ManagementAgendaType::where('id', $id)->First();
        $models = DB::table('modenadisable')->First();
        return view('admin.managementreview.editagendatype')
            ->with('types', $types)
            ->with('model', $models);
    }

    public function updateagendatype(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $types = ManagementAgendaType::all();
        $exist = ManagementAgendaType::where('name', $request->name)->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Type already exists')
                ->with('types', $types);
        } else {
            ManagementAgendaType::where('id', $request->id)->update([
                'name' => $request->name,
            ]);
            //inserting data to activities table
            $activity = 'Agenda Type with ID' . $request->id . ' is Edited and Updated with Name as ' . $request->name . 'for Management Review';
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return redirect('/admin/management/agendatypes')
                ->with('message', 'Type Updated successfully')
                ->with('types', $types)
                ->with('model', $models);
        }
    }

    //Agenda - ManagementReview

    public function managementagenda()
    {
        $models = DB::table('modenadisable')->First();
        $agendapoints = ManagementAgendaPoint::all();
        return view('admin.managementreview.agendapoints')
            ->with('model', $models)
            ->with('agendapoints', $agendapoints);
    }

    public function addagenda()
    {
        $models = DB::table('modenadisable')->First();
        $types = ManagementAgendaType::all();
        return view('admin.managementreview.addagenda')
            ->with('types', $types)
            ->with('model', $models);
    }

    public function insertagenda(Request $request)
    {
        $type = implode(',', $request->type);
        $points = array_filter($request->points);
        $points_string = implode("\n", $points);
        $detail_type = ManagementAgendaPoint::latest('id')->value('detail_type');
        $nxtval = substr($detail_type, 4);
        $cnt = $nxtval + 1;
        $newdetail = 'type' . $cnt;
        $data = [
            'points' => $points_string,
            'agenda_type' => $type,
            'detail_type' => $newdetail,
        ];

        $dataexist = ManagementAgendaPoint::where('agenda_type', $type)->first();
        if ($dataexist) {
            $oldpoints = $dataexist->points;
            $new_points = $oldpoints . "\n" . $points_string;
            $agendapoint_detail_type = $dataexist->detail_type;
            $agendatype = $dataexist->agenda_type;
            ManagementAgendaPoint::where('id', $dataexist->id)->update([
                'points' => $new_points,
                'updated_by' => Auth::user()->email,
            ]);
        } else {
            ManagementAgendaPoint::create($data);
            $latest_data = ManagementAgendaPoint::latest('id')->first();
            $agendapoint_detail_type = $latest_data->detail_type;
            $agendatype = $latest_data->agenda_type;
        }

        //adding datas to Management Agenda point lists table
        $pointvalues = array_filter($request->points);

        foreach ($pointvalues as $pointvalue) {
            $magendapointlist = new ManagementAgendaPointList;
            $magendapointlist->points = $pointvalue;
            $magendapointlist->agenda_type = $agendatype;
            $magendapointlist->agenda_point_detail_type = $agendapoint_detail_type;
            $magendapointlist->save();
        }

        $models = DB::table('modenadisable')->First();
        return redirect('/admin/management/agenda')
            ->with('message', 'Agenda added successfully')
            ->with('model', $models);
    }

    public function editagenda($id)
    {
        $agendas = ManagementAgendaPoint::where('id', $id)->First();
        $detail_type = ManagementAgendaPoint::where('id', $id)->value('detail_type');
        $agendalist = ManagementAgendaPointList::where('agenda_point_detail_type', $detail_type)->get();
        $models = DB::table('modenadisable')->First();
        return view('admin.managementreview.editagenda')
            ->with('agendas', $agendas)
            ->with('agendalist', $agendalist)
            ->with('detail_type', $detail_type)
            ->with('model', $models);
    }

    public function addagendaajax(Request $request)
    {
        $details_type = $request->input('details_type');
        $agenda_type = $request->input('agenda_type');
        $agendapoints = $request->input('agenda');
        foreach ($agendapoints as $item) {
            $magendapointlist = new ManagementAgendaPointList;
            $magendapointlist->points = $item;
            $magendapointlist->agenda_type = $agenda_type;
            $magendapointlist->agenda_point_detail_type = $details_type;
            $magendapointlist->save();
        }

        //updating managementagendapoints table
        $mainagendaid = $request->input('mainagendaid');
        $updatedpoints = ManagementAgendaPointList::where('agenda_point_detail_type', $details_type)->pluck('points')->toArray();
        $points_string = implode("\n", $updatedpoints);

        ManagementAgendaPoint::where('id', $mainagendaid)->update([
            'points' => $points_string,
            'updated_by' => Auth::user()->email,
        ]);

        return response()->json([
            'status' => 200,
            'data' => $mainagendaid,
        ]);
    }

    public function agendapointdelete($id)
    {
        $detail_type = ManagementAgendaPointList::where('id', $id)->value('agenda_point_detail_type');
        ManagementAgendaPointList::where('id', $id)->delete();
        ManagementReviewScheduleNote::where('agenda_list_id', $id)->delete();

        $updatedpoints = ManagementAgendaPointList::where('agenda_point_detail_type', $detail_type)->pluck('points')->toArray();
        $points_string = implode("\n", $updatedpoints);

        ManagementAgendaPoint::where('detail_type', $detail_type)->update([
            'points' => $points_string,
            'updated_by' => Auth::user()->email,
        ]);

        $models = DB::table('modenadisable')->First();
        Session::flash('msg', 'Agenda Deleted Successfully');
        return redirect()->back();
    }

    public function updateagenda(Request $request)
    {
        $points_arr = $request->points;
        $point_string = implode("\n", $points_arr);

        ManagementAgendaPoint::where('id', $request->id)->update([
            'points' => $point_string,
            'updated_by' => Auth::user()->email,
        ]);

        foreach ($request->agendaid as $key => $value) {
            $pointsvaluedb = DB::table('management_agenda_point_lists')->where('id', $value)->get();
            foreach ($pointsvaluedb as $pointvaluedb) {
                $data = array(
                    'points' => $request->points[$key],
                );

                DB::table('management_agenda_point_lists')
                    ->where('agenda_point_detail_type', $request->detail_type)
                    ->where('id', $request->agendaid[$key])
                    ->update($data);
            }
        }

        $models = DB::table('modenadisable')->First();
        return redirect('/admin/management/agenda')
            ->with('message', 'Agenda Updated successfully')
            ->with('model', $models);
    }

    //Location - ManagementReview

    public function managementlocation()
    {
        $models = DB::table('modenadisable')->First();
        $locations = ManagementLocation::all();
        return view('admin.managementreview.locationlist')
            ->with('model', $models)
            ->with('locations', $locations);
    }

    public function addmanagementlocation(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $locations = ManagementLocation::all();
        $exist = ManagementLocation::where('name', $request->name)->get();
        if (count($exist) > 0) {
            return back()->with('model', $models)
                ->with('msg', 'Location already exists')
                ->with('locations', $locations);
        } else {
            $data = [
                'name' => $request->name,
            ];
            ManagementLocation::create($data);

            //inserting data to activities table
            $activity = 'New Location is added with Name as ' . $request->name . 'for Management Review';
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return back()
                ->with('model', $models)
                ->with('message', 'Location added successfully')
                ->with('locations', $locations);
        }
    }

    public function editmanagementlocation($id)
    {
        $locations = ManagementLocation::where('id', $id)->First();
        $models = DB::table('modenadisable')->First();
        return view('admin.managementreview.editlocation')
            ->with('locations', $locations)
            ->with('model', $models);
    }

    public function updatemanagementlocation(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $locations = ManagementLocation::all();
        $exist = ManagementLocation::where('name', $request->name)->get();
        if (count($exist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'This Location already exists')
                ->with('locations', $locations);
        } else {
            ManagementLocation::where('id', $request->id)->update([
                'name' => $request->name,
            ]);
            //inserting data to activities table
            $activity = 'Location with ID' . $request->id . ' is Edited and Updated with Name as ' . $request->name . 'for Management Review';
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return redirect('/admin/management/location')
                ->with('message', 'Location Updated successfully')
                ->with('locations', $locations)
                ->with('model', $models);
        }
    }

    public function deletemanagementlocation($id)
    {
        $models = DB::table('modenadisable')->First();
        $location_name = ManagementLocation::where('id', $id)->value('name');
        ManagementReviewSchedule::where('location', $id)->update(['location' => $location_name]);
        $exist = ManagementLocation::find($id);
        if ($exist) {
            $name = ManagementLocation::where('id', $id)->value('name');
            $activity = 'Management Review Location is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            ManagementLocation::where('id', $id)->delete();
            return redirect('/admin/management/location')
                ->with('model', $models)
                ->with('message', 'Location deleted successfully');
        } else {
            return redirect('/admin/management/location')
                ->with('model', $models)
                ->with('message', 'Data with this id not exists');
        }
    }

    /**---Email Notification list--- */

    public function emailnotificationlist(Request $request)
    {
        $models = DB::table('modenadisable')->First();

        $users = User::where('approved', 1)->where('is_email_verified',true)->get();
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
        $emaildatas = EmailNotification::whereIn('module',$modulenames_keys)->orderBy('module', 'ASC')->get();

        return view('admin.emailnotification.emailnotificationlist')
            ->with('model', $models)
            ->with('users', $users)
            ->with('modulenames', $modulenames)
            ->with('emaildatas', $emaildatas);
    }

    public function addemailnotificationajax(Request $request)
    {
        $dataid = $request->input('dataid');
        // $users = implode(',',$request->users_value);

        //inserting data into email notications user details table
        foreach ($request->users_value as $item) {
            $emailusers = new EmailNotificationsUserDetails;
            $emailusers->notification_id = $dataid;
            $emailusers->user_id = $item;
            $emailusers->save();
        }

        $emailusers = EmailNotificationsUserDetails::where('notification_id', $dataid)->pluck('user_id')->toArray();
        $newusers = array();
        foreach ($emailusers as $item) {
            array_push($newusers, $item);
        }
        $updatedusers = implode(',', $newusers);

        $data = array(
            'email_send_to_users' => $updatedusers,
        );
        EmailNotification::where('id', $dataid)->update($data);

        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }

    public function selectemaildetails(Request $request)
    {
        $id = $request->input('datasid');
        $details = DB::table('email_notifications_user_details')->where('notification_id', $id)->get();
        $userdata = array();
        foreach ($details as $detail) {
            $data = User::where('id', $detail->user_id)->first();
            array_push($userdata, $data);
        }

        return response()->json([
            'status' => 200,
            'data' => $userdata,
            'id' => $id,
        ]);
    }

    public function deleteemailusers()
    {
        if ((isset($_GET['val'])) && (isset($_GET['value']))) {
            $userid = $_GET['val'];
            $notificationid = $_GET['value'];

            EmailNotificationsUserDetails::where('notification_id', $notificationid)
                ->where('user_id', $userid)
                ->delete();

            $emailusers = EmailNotification::where('id', $notificationid)->value('email_send_to_users');
            $userarrays = explode(',', $emailusers);
            $updatedusers = array();
            foreach ($userarrays as $item) {
                if ($item != $userid) {
                    array_push($updatedusers, $item);
                }
            }
            $existingusers = implode(',', $updatedusers);
            EmailNotification::where('id', $notificationid)->update([
                'email_send_to_users' => $existingusers,
            ]);

            $models = DB::table('modenadisable')->First();
            return redirect('/admin/emailnotificationlist')
                ->with('model', $models)
                ->with('message', 'User Deleted Successfully');
        }
    }

    public function editemailnotification($id)
    {
        $models = DB::table('modenadisable')->First();
        $data = EmailNotification::where('id', $id)->first();
        $users = User::where('approved', 1)->get();
        $modules = new ModenaDisable();
        $table = $modules->getTable();
        $columns = \Schema::getColumnListing($table);
        $removeFields = ['id', 'auditschedulelogs', 'created_at', 'updated_at'];
        $modulenames = array_diff($columns, $removeFields);
        sort($modulenames);
        return view('admin.emailnotification.editemailnotification')
            ->with('model', $models)
            ->with('users', $users)
            ->with('modulenames', $modulenames)
            ->with('data', $data);
    }

    public function updateemailnotification(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $data = array(
            'description' => $request->description,
            'email_content' => $request->content,
            'frequently' => $request->get('frequently'),
        );

        EmailNotification::where('id', $request->id)->update($data);
        return redirect('/admin/emailnotificationlist')
            ->with('model', $models)
            ->with('message', 'Email Notification Updated Successfully');
    }

    public function blockemailnotification($id)
    {
        $models = DB::table('modenadisable')->First();
        $data = array(
            'is_blocked' => 1,
        );

        $itemname = EmailNotification::where('id', $id)->value('item');
        EmailNotification::where('id', $id)->update($data);
        return redirect('/admin/emailnotificationlist')
            ->with('model', $models)
            ->with('message', 'Email Notification Blocked for ' . $itemname);
    }

    public function unblockemailnotification($id)
    {
        $models = DB::table('modenadisable')->First();
        $data = array(
            'is_blocked' => 0,
        );

        $itemname = EmailNotification::where('id', $id)->value('item');
        EmailNotification::where('id', $id)->update($data);
        return redirect('/admin/emailnotificationlist')
            ->with('model', $models)
            ->with('message', 'Email Notification Unblocked for ' . $itemname);
    }

    // Opportunity types

    public function viewopportunitytypes()
    {
        $models = DB::table('modenadisable')->First();
        $opptypes = OpportunityType::all();
        return view('admin.viewopportunitytypes')
            ->with('model', $models)
            ->with('opptypes', $opptypes);
    }

    public function addopportunitytypes(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $opptypes = OpportunityType::all();
        $typeexist = OpportunityType::where('name', $request->type_name)->get();
        if (count($typeexist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Type already exists')
                ->with('opptypes', $opptypes);
        } else {
            $data = [
                'name' => $request->type_name,
            ];
            OpportunityType::create($data);

            //inserting data to activities table
            $activity =
            'New Opportunity Type is added with Name as ' .
            $request->type_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return back()
                ->with('model', $models)
                ->with('message', 'Type added successfully')
                ->with('opptypes', $opptypes);
        }
    }

    public function editopportunitytypes($id)
    {
        $opptypes = OpportunityType::where('id', $id)->First();
        $models = DB::table('modenadisable')->First();
        return view('admin.editopportunitytype')
            ->with('opptypes', $opptypes)
            ->with('model', $models);
    }

    public function updateopportunitytype(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $opptypes = OpportunityType::all();
        $typeexist = OpportunityType::where('name', $request->type_name)->get();
        if (count($typeexist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Type already exists')
                ->with('opptypes', $opptypes);
        } else {
            OpportunityType::where('id', $request->id)->update([
                'name' => $request->type_name,
            ]);
            //inserting data to activities table
            $activity =
            'Opportunity Type with ID' .
            $request->id .
            ' is Edited and Updated with Name as ' .
            $request->type_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            return redirect('/admin/viewopportunitytypes')
                ->with('message', 'Type Updated successfully')
                ->with('opptypes', $opptypes)
                ->with('model', $models);
        }
    }

    public function deleteopportunitytype($id)
    {
        $models = DB::table('modenadisable')->First();
        $opptypes = OpportunityType::all();
        $typedel = OpportunityType::find($id);
        if ($typedel) {
            $name = OpportunityType::where('id', $id)->value('name');
            $activity = 'Opportunity Type is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            OpportunityType::where('id', $id)->delete();
            return redirect('/admin/viewopportunitytypes')
                ->with('model', $models)
                ->with('message', 'Type deleted successfully')
                ->with('opptypes', $opptypes);
        } else {
            return redirect('/admin/viewopportunitytypes')
                ->with('model', $models)
                ->with('message', 'Type with this id not exists')
                ->with('opptypes', $opptypes);
        }
    }

    // Objective types
    public function viewobjectivetypes()
    {
        $models = DB::table('modenadisable')->First();
        $objtypes = ObjectiveType::where('is_deleted', false)->get();

        return view('admin.viewobjectivetypes')
            ->with('model', $models)
            ->with('objtypes', $objtypes);
    }

    public function addobjectivetypes(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $objtypes = ObjectiveType::where('is_deleted', false)->get();
        $typeexist = ObjectiveType::where('name', $request->type_name)
            ->where('is_deleted', false)
            ->get();
        if (count($typeexist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Type already exists')
                ->with('objtypes', $objtypes);
        } else {
            $data = [
                'name' => $request->type_name,
            ];
            ObjectiveType::create($data);

            //inserting data to activities table
            $activity =
            'New Objective Type is added with Name as ' .
            $request->type_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            return back()
                ->with('model', $models)
                ->with('message', 'Type added successfully')
                ->with('objtypes', $objtypes);
        }
    }
    public function editobjectivetypes($id)
    {
        $objtypes = ObjectiveType::where('is_deleted', false)->findOrFail($id);
        $models = DB::table('modenadisable')->First();
        return view('admin.editobjectivetype')
            ->with('objtypes', $objtypes)
            ->with('model', $models);
    }

    public function updateobjectivetype(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $objtypes = ObjectiveType::where('is_deleted', false)->get();
        $typeexist = ObjectiveType::where('name', $request->type_name)
            ->where('is_deleted', false)
            ->get();
        if (count($typeexist) > 0) {
            return back()
                ->with('model', $models)
                ->with('msg', 'Type already exists')
                ->with('objtypes', $objtypes);
        } else {
            ObjectiveType::where('id', $request->id)->update([
                'name' => $request->type_name,
            ]);
            //inserting data to activities table
            $activity =
            'Objective Type with ID' .
            $request->id .
            ' is Edited and Updated with Name as ' .
            $request->type_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            return redirect('/admin/viewobjectivetypes')
                ->with('message', 'Type Updated successfully')
                ->with('objtypes', $objtypes)
                ->with('model', $models);
        }
    }

    public function deleteobjectivetype($id)
    {
        $models = DB::table('modenadisable')->First();
        $objtypes = ObjectiveType::where('is_deleted', false)->get();
        $typedel = ObjectiveType::where('is_deleted', false)->find($id);
        if ($typedel) {
            $name = ObjectiveType::where('is_deleted', false)
                ->where('id', $id)
                ->value('name');
            $activity = 'Objective Type is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            ObjectiveType::where('id', $id)->update([
                'is_deleted' => 1,
            ]);
            return redirect('/admin/viewobjectivetypes')
                ->with('model', $models)
                ->with('message', 'Type deleted successfully')
                ->with('objtypes', $objtypes);
        } else {
            return redirect('/admin/viewobjectivetypes')
                ->with('model', $models)
                ->with('message', 'Type with this id not exists')
                ->with('objtypes', $objtypes);
        }
    }

    public function viewnearmissTypes()
    {
        $models = DB::table('modenadisable')->First();
        $types = Nearmisstypes::all();
        $targetinfo = Neartarget::all();
        return view('admin.viewnearmissTypes')
            ->with('types', $types)
            ->with('targetinfo', $targetinfo)
            ->with('model', $models);
    }
    public function addnearmissType(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('type');
        $types = Nearmisstypes::all();
        if ($name != null) {
            $id = DB::select(
                'select id from nearmisstypes where name = :entered',
                ['entered' => $name]
            );
            if ($id) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'Type already exists')
                    ->with('types', $types);
            } else {
                $data = ['name' => $name];
                DB::table('nearmisstypes')->insert($data);
                //inserting data to activities table
                $activity = 'New Near Miss Type is added with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return back()
                    ->with('model', $models)
                    ->with('message', 'Type added successfully')
                    ->with('types', $types);
            }
        } else {
            return back()
                ->with('model', $models)
                ->with('types', $types);
        }
    }
    public function addwasteType(AddWasteTypeRequest $req)
    {
        $models = modenadisable::first();
        $name = $req->input('type');
        $types = Wastetype::all();
        if ($name != null) {
            $wastetype = Wastetype::where('name', $name)->first();
            if ($wastetype) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'Type already exists')
                    ->with('types', $types);
            } else {
                $data = ['name' => $name];
                Wastetype::insert($data);
                $activity = 'New Waste Type is added with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);
                return back()
                    ->with('model', $models)
                    ->with('message', 'Type added successfully')
                    ->with('types', $types);
            }
        } else {
            return back()
                ->with('model', $models)
                ->with('types', $types);
        }
    }
    public function addwasteUnit(AddWasteUnitRequest $req)
    {
        $models = modenadisable::first();
        $name = $req->input('unit');
        $units = wasteUnit::all();
        if ($name != null) {
            $units = Wasteunit::where('name', $name)->first();
            if ($units) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'Unit already exists')
                    ->with('units', $units);
            } else {
                $data = ['name' => $name];
                Wasteunit::insert($data);
                $activity = 'New Waste Unit is added with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);
                return back()
                    ->with('model', $models)
                    ->with('message', 'Unit added successfully')
                    ->with('units', $units);
            }
        } else {
            return back()
                ->with('model', $models)
                ->with('units', $units);
        }
    }
    public function addwasteDisposalMethod(AddWasteDisposalMethodRequest $req)
    {
        $models = modenadisable::first();
        $name = $req->input('disposal_method');
        $disposalmethods = Wastedisposalmethod::all();
        if ($name != null) {
            $disposalmethods = Wastedisposalmethod::where(
                'value',
                $name
            )->first();
            if ($disposalmethods) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'Disposal Method already exists')
                    ->with('disposalmethods', $disposalmethods);
            } else {
                $data = [
                    'value' => $name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                Wastedisposalmethod::insert($data);
                $activity =
                    'New Waste Disposal Method is added with Name as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);
                return back()
                    ->with('model', $models)
                    ->with('message', 'Disposal method added successfully')
                    ->with('disposalmethods', $disposalmethods);
            }
        } else {
            return back()
                ->with('model', $models)
                ->with('disposalmethods', $disposalmethods);
        }
    }
    public function editwasteType(int $id)
    {
        $models = modenadisable::first();
        $types = Wastetype::all()->where('id', '=', $id);
        return view('admin.editwasteType')
            ->with('types', $types)
            ->with('model', $models);
    }
    public function editwasteUnit(int $id)
    {
        $models = modenadisable::first();
        $unit = WasteUnit::where('id', '=', $id)->first();
        return view('admin.editwasteUnit')
            ->with('unit', $unit)
            ->with('model', $models);
    }
    public function editwasteDisposalMethod(int $id)
    {
        $models = modenadisable::first();
        $disposal_method = Wastedisposalmethod::where('id', '=', $id)->first();
        return view('admin.editwasteDisposalMethod')
            ->with('disposal_method', $disposal_method)
            ->with('model', $models);
    }
    public function editwasteTarget(int $id)
    {
        $models = modenadisable::first();
        $target = Wastetarget::where('id', '=', $id)->first();
        $users = User::where('approved', 1)->get();
        return view('admin.editwasteTarget')
            ->with('target', $target)
            ->with('model', $models)
            ->with('users', $users);
    }
    public function updatewasteTarget(UpdateWasteTargetRequest $req)
    {
        $models = ModenaDisable::first();
        $id = $req->input('id');
        $items = $req->input('items');
        $qty = $req->input('qty');
        $approvers_id = $req->input('approver');
        $wastetarget = Wastetarget::find($id);
        if ($wastetarget != null) {
            $activity = 'Waste Target with id ' . $id . 'was updated';
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            Wastetarget::where('id', $id)->update([
                'items' => $items,
                'qty' => $qty,
                'approvers_id' => $approvers_id,
            ]);
            return redirect('/admin/wastetargets')->with('model', $models);
        } else {
            return redirect('/admin/wastetargets')->with('model', $models);
        }
    }
    public function updatewasteType(UpdateWasteTypeRequest $req)
    {
        $models = ModenaDisable::first();
        $name = $req->input('name');
        $id = $req->input('id');
        $wastetype = Wastetype::find($id);
        if ($wastetype != null) {
            $oldname = Wastetype::where('id', $id)->pluck('name');
            $activity =
                $oldname . ' Waste Type is Updated with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            Wastetype::where('id', $id)->update([
                'name' => $name,
            ]);
            return redirect('/admin/wastetypes')->with('model', $models);
        } else {
            return redirect('/admin/wastetypes')->with('model', $models);
        }
    }
    public function updatewasteUnit(UpdateWasteUnitRequest $req)
    {
        $models = ModenaDisable::first();
        $name = $req->input('name');
        $id = $req->input('id');
        $wasteunit = Wasteunit::find($id);
        if ($wasteunit != null) {
            $oldname = Wasteunit::where('id', $id)->pluck('name');
            $activity =
                $oldname . ' Waste Unit is Updated with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            Wasteunit::where('id', $id)->update([
                'name' => $name,
            ]);
            return redirect('/admin/wasteunits')->with('model', $models);
        } else {
            return redirect('/admin/wasteunits')->with('model', $models);
        }
    }
    public function updatewasteDisposalMethod(
        UpdateWasteDisposalMethodRequest $req
    ) {
        $models = ModenaDisable::first();
        $name = $req->input('name');
        $id = $req->input('id');
        $wastedispoalmethod = Wastedisposalmethod::find($id);
        if ($wastedispoalmethod != null) {
            $oldname = Wastedisposalmethod::where('id', $id)->pluck('value');
            $activity =
                $oldname .
                ' Waste Disposal Method is Updated with Name as ' .
                $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            Wastedisposalmethod::where('id', $id)->update([
                'value' => $name,
            ]);
            return redirect('/admin/wastedisposalmethods')->with(
                'model',
                $models
            );
        } else {
            return redirect('/admin/wastedisposalmethods')->with(
                'model',
                $models
            );
        }
    }
    public function editnearmissType(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $types = Nearmisstypes::all()->where('id', '=', $id);
            return view('admin.editnearmissType')
                ->with('types', $types)
                ->with('model', $models);
        }
    }
    public function removewasteType(int $id)
    {
        $types = Wastetype::all();
        $models = Modenadisable::first();
        $wastetype = Wastetype::find($id);
        if ($wastetype) {
            $name = Wastetype::where('id', $id)->pluck('name');
            $activity = 'Waste Type is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            Wastetype::where('id', $id)->delete();
            return redirect('/admin/wastetypes')
                ->with('model', $models)
                ->with('message', 'Type deleted successfully')
                ->with('types', $types);
        } else {
            return redirect('/admin/wastetypes')
                ->with('model', $models)
                ->with('message', 'Type with this id not exists')
                ->with('types', $types);
        }
    }
    public function removewasteUnit(int $id)
    {
        $types = Wasteunit::all();
        $models = Modenadisable::first();
        $wastetype = Wasteunit::find($id);
        if ($wastetype) {
            $name = Wasteunit::where('id', $id)->pluck('name');
            $activity = 'Waste Type is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            Wasteunit::where('id', $id)->delete();
            return redirect('/admin/wasteunits')
                ->with('model', $models)
                ->with('message', 'Unit deleted successfully')
                ->with('types', $types);
        } else {
            return redirect('/admin/wasteunits')
                ->with('model', $models)
                ->with('message', 'Unit with this id not exists')
                ->with('types', $types);
        }
    }
    public function removewasteDisposalMethod(int $id)
    {
        $types = Wastedisposalmethod::all();
        $models = Modenadisable::first();
        $wastetype = Wastedisposalmethod::find($id);
        if ($wastetype) {
            $name = Wastedisposalmethod::where('id', $id)->pluck('value');
            $activity =
                'Waste Disposal Method is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            Wastedisposalmethod::where('id', $id)->delete();
            return redirect('/admin/wastedisposalmethods')
                ->with('model', $models)
                ->with('message', 'Disposal Method deleted successfully')
                ->with('types', $types);
        } else {
            return redirect('/admin/wastedisposalmethods')
                ->with('model', $models)
                ->with('message', 'Disposal Method with this id not exists')
                ->with('types', $types);
        }
    }
    public function updatenearmissType(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');
        $id = $req->input('id');
        $typeid = DB::select(
            'select * from nearmisstypes where name = :ename',
            ['ename' => $name]
        );
        foreach ($typeid as $row) {
            $type1id = (string) $row->id;
            if ($type1id == $id || $typeid == null) {
                //inserting data to activities table
                $oldname = DB::table('nearmisstypes')
                    ->where('id', $id)
                    ->pluck('name');
                $activity =
                    $oldname .
                    ' Near Miss Type is Updated with Name as ' .
                    $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                DB::update(
                    'update nearmisstypes set name = :ename  where id = :eid',
                    ['ename' => $name, 'eid' => $id]
                );
                return redirect('/admin/viewnearmissTypes')->with(
                    'model',
                    $models
                );
            } else {
                return redirect('/admin/viewnearmissTypes')->with(
                    'model',
                    $models
                );
            }
        }
        if ($typeid == null) {
            //inserting data to activities table
            $oldname = DB::table('nearmisstypes')
                ->where('id', $id)
                ->pluck('name');
            $activity =
                $oldname . ' Near miss Type is Updated with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::update(
                'update nearmisstypes set name = :ename  where id = :eid',
                ['ename' => $name, 'eid' => $id]
            );
            return redirect('/admin/viewnearmissTypes')->with('model', $models);
        }
    }
    public function removenearmissType(Request $req)
    {
        $types = Nearmisstypes::all();
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $name = DB::table('nearmisstypes')
                ->where('id', $id)
                ->pluck('name');
            $activity = 'Near miss Type is deleted with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from nearmisstypes where id = :eid', [
                'eid' => $id,
            ]);
            return redirect('/admin/viewnearmissTypes')
                ->with('model', $models)
                ->with('message', 'Type deleted successfully')
                ->with('types', $types);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function removewasteTarget(int $id)
    {
        $types = Wastetarget::all();
        $models = Modenadisable::first();
        $wastetarget = Wastetarget::find($id);
        if ($wastetarget) {
            $id = Wastetarget::where('id', $id)->pluck('id');
            $activity = 'Waste target is deleted with id as ' . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);
            Wastetarget::where('id', $id)->delete();
            return redirect('/admin/wastetargets')
                ->with('model', $models)
                ->with('message', 'Target deleted successfully')
                ->with('types', $types);
        } else {
            return redirect('/admin/wastetargets')
                ->with('model', $models)
                ->with('message', 'Target with this id not exists')
                ->with('types', $types);
        }
    }
    public function addnearmisstarget(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('target');
        $neartargets = Neartarget::all();
        if ($name != null) {
            $id = DB::select(
                'select id from neartargets where target = :entered',
                ['entered' => $name]
            );
            if ($id) {
                return back()
                    ->with('model', $models)
                    ->with('message', 'Information already exists')
                    ->with('neartargets', $neartargets);
            } else {
                $data = ['target' => $name];
                DB::table('neartargets')->insert($data);
                //inserting data to activities table
                $activity =
                    'New Near Miss Target Information is added with Name as ' .
                    $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                return back()
                    ->with('model', $models)
                    ->with('message', 'Information added successfully')
                    ->with('neartargets', $neartargets);
            }
        } else {
            return back()
                ->with('model', $models)
                ->with('neartargets', $neartargets);
        }
    }
    public function editnearmisstarget(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $types = Neartarget::all()->where('id', '=', $id);
            return view('admin.editnearmisstarget')
                ->with('types', $types)
                ->with('model', $models);
        }
    }
    public function updatenearmisstarget(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $name = $req->input('name');
        $id = $req->input('id');
        $typeid = DB::select(
            'select * from neartargets where target = :ename',
            ['ename' => $name]
        );
        foreach ($typeid as $row) {
            $type1id = (string) $row->id;
            if ($type1id == $id || $typeid == null) {
                //inserting data to activities table
                $oldname = DB::table('neartargets')
                    ->where('id', $id)
                    ->pluck('target');
                $activity =
                    $oldname . ' Near Miss Target Info is Updated as ' . $name;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);

                DB::update(
                    'update neartargets set target = :ename  where id = :eid',
                    ['ename' => $name, 'eid' => $id]
                );
                return redirect('/admin/viewnearmisstarget')->with(
                    'model',
                    $models
                );
            } else {
                return redirect('/admin/viewnearmissTypes')->with(
                    'model',
                    $models
                );
            }
        }
        if ($typeid == null) {
            //inserting data to activities table
            $oldname = DB::table('neartargets')
                ->where('id', $id)
                ->pluck('target');
            $activity =
                $oldname . ' Near miss Type is Updated with Name as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::update(
                'update neartargets set target = :ename  where id = :eid',
                ['ename' => $name, 'eid' => $id]
            );
            return redirect('/admin/viewnearmissTypes')->with('model', $models);
        }
    }
    public function removenearmisstarget(Request $req)
    {
        $types = Neartarget::all();
        $models = DB::table('modenadisable')->First();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $name = DB::table('neartargets')
                ->where('id', $id)
                ->pluck('target');
            $activity =
                'Near miss target info is deleted with title as ' . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'activity' => $activity,
                'type' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            ViewActivity::insert($datas);

            DB::delete('delete from neartargets where id = :eid', [
                'eid' => $id,
            ]);
            return redirect('/admin/viewnearmissTypes')
                ->with('model', $models)
                ->with('message', 'Type deleted successfully')
                ->with('types', $types);
        } else {
            return redirect('/admin/dashboard')->with('model', $models);
        }
    }
    public function profile()
    {
        $models = DB::table('modenadisable')->First();
        // $users = DB::table('admins')
        //     ->where('employeeid', '=', Auth::user()->employeeid)
        //     ->get();
        $user_id = User::where('email',Auth::user()->email)->value('id');
        $users = DB::table('users')->where('id',$user_id)->get();
        $mfa = AppConfiguration::where('meta_key', 'enable_mfa')->first();
        return view('/admin/update_profile', compact('mfa'))
            ->with('users', $users)
            ->with('model', $models);
    }
    public function showWasteTypes()
    {
        $wastetypes = Wastetype::all();
        $models = DB::table('modenadisable')->first();
        return view('admin.wastetypes')->with([
            'wastetypes' => $wastetypes,
            'model' => $models,
        ]);
    }
    public function showWasteUnit()
    {
        $wasteunits = Wasteunit::all();
        $models = DB::table('modenadisable')->first();
        return view('admin.wasteunits')->with([
            'wasteunits' => $wasteunits,
            'model' => $models,
        ]);
    }
    public function showWasteDisposalMethod()
    {
        $wastedispoalmethods = Wastedisposalmethod::all();
        $models = DB::table('modenadisable')->first();
        return view('admin.wastedisposalmethods')->with([
            'wastedispoalmethods' => $wastedispoalmethods,
            'model' => $models,
        ]);
    }
    public function showWasteTarget()
    {
        //$users = User::all();
        $waste_approver = Wasteapprover::all();
        $wastetargets = Wastetarget::all();
        $models = DB::table('modenadisable')->first();
        return view('admin.wastetarget')->with([
            'wastetargets' => $wastetargets,
            'model' => $models,
            'wasteappr' => $waste_approver,
        ]);
    }
/*
    public function updateProfile(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $id = Auth::user()->employeeid;
        if ($request->hasfile('profile_pic')) {
            $avatar = $request->file('profile_pic');
            $filename = $id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
            $destination_path = public_path('storage/profile');
            $avatar->move($destination_path, $filename);
            //  Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename) );
            DB::update(
                'update admins set profile_picture = :eprofile_picture  where employeeid = :eid',
                ['eprofile_picture' => $filename, 'eid' => $id]
            );
            DB::update(
                'update users set profile_picture = :eprofile_picture  where employeeid = :eid',
                ['eprofile_picture' => $filename, 'eid' => $id]
            );
        }

        $users = DB::table('admins')
            ->where('employeeid', '=', Auth::user()->employeeid)
            ->get();
        // $users->update(['profile_picture' => $filename]);
        session()->flash('msg', 'Profile Updated Successfully');
        return redirect()->route('admin.profile');
    }
*/
   public function updateProfile(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $validator = Validator::make($request->all(), [
            'profile_pic' => 'nullable|mimes:jpeg,png,jpg',
        ],[
            'profile_pic.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        }else{
                $id = User::where('email',Auth::user()->email)->value('id');
                if ($request->hasfile('profile_pic')) {
                    $avatar = $request->file('profile_pic');
                    $filename = $id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
                    $destination_path = public_path('storage/profile');
                    $avatar->move($destination_path, $filename);
                    Admin::where('email',Auth::user()->email)->update(['profile_picture' => $filename]);
                    User::where('id',$id)->update(['profile_picture'=>$filename]);
                }
                session()->flash('msg', 'Profile Updated Successfully');
                return redirect()->route('admin.profile');
            }
    }
    public function remove_avatar($id)
    {
        $data = User::where('id',$id)->first();
        $textColor = 'FFFFFF';
            $backgroundColors = [
                '1F42AD',
                '1F42AD',
                'F5993D',
                'E64C73',
                'AD858F',
                '9952E0',
                '75ABBD',
                'AD661F',
                'C63939',
                '0D80F2',
                'E87DB3',
                '7594F0',
                '7DA1E8'
            ];
            $backgroundColorIndex = array_rand($backgroundColors);
            $image = file_get_contents('https://ui-avatars.com/api/?name=' . rawurlencode(ucwords($data['name'])) . '&color=' . $textColor . '&background=' . $backgroundColors[$backgroundColorIndex] . '&rounded=true&bold=true');
            $filename = (isset($data['employeeid']) && $data['employeeid'] ? $data['employeeid'] : rand(1000, 2000)) . '_' . time() . '.png';
            file_put_contents(public_path('storage/profile') . '/' . $filename, $image);
            User::where('id',$id)->update(['profile_picture' => $filename]);
            Admin::where('email',$data->email)->update(['profile_picture' => $filename]);
            session()->flash('msg', 'Image deleted successfully');
            return redirect()->route('admin.profile');
    }
    //------------------------Client Logo Upload------------------//
    public function clientviewprofile()
    {
        $models = Modenadisable::first();
        $logos = ClientDetail::first();
        $mfa = AppConfiguration::where('meta_key', 'enable_mfa')->first();
        return view('admin.client.add_profile', compact('mfa'))->with('model', $models)
            ->with('logos', $logos);
    }
    public function uploadclientlogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            // 'client_logo' => 'required|image|mimes:jpeg,png,jpg,gif|min:12|max:4096',
            'client_logo' => ['required','file','mimes:jpg,jpeg,png,bmp,webp,gif','max:10240']
        ],
            [
                'company_name.required' => 'Please enter company name',
                'client_logo.required' => 'Please upload Logo',
            ]
        );
        $models = DB::table('modenadisable')->First();
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('model', $models);
        } else {
            if ($request->hasfile('client_logo')) {
                $file = $request->file('client_logo');
                $manager = new ImageManager(['driver' => 'gd']);
                $image = $manager->make($file);
                $mime = $file->getMimeType();
                if ($mime == 'image/png') {
                    $filename = time() . '.png';
                    $encoded = (string) $image->encode('png');
                } else {
                    $filename = time() . '.jpg';
                    $encoded = (string) $image->encode('jpg', 90);
                }
                if (strlen($encoded) < 12288) {
                    $width = $image->width();
                    $height = $image->height();
                    $image->resize(
                        $width * 2,
                        $height * 2
                    );
                    if ($mime == 'image/png') {
                        $encoded = (string) $image->encode('png');
                    } else {
                        $encoded = (string) $image->encode('jpg', 100);
                    }
                }
                $destinationPath = public_path('storage/clientlogos');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                file_put_contents(
                    $destinationPath . '/' . $filename,
                    $encoded
                );
                $data = [
                    'company_name' => $request->company_name,
                    'client_logo' => $filename
                ];
                $client_id = $request->input('client_id');
                $exists = ClientDetail::where('id',$client_id)->first();
                if ($exists) {
                    $exists->update($data);
                }else{
                    ClientDetail::create($data);
                }
            }

            session()->flash('msg', 'Company Profile updated successfully');
            return redirect()->route('admin.client.profile');
        }
    }

    //--------------------------- Locations ---------------------------------------------------
    public function indexLocations()
    {
        $models = Modenadisable::first();
        $locations = Location::all();
        $divisions = Division::orderBy('department_id', 'desc')->get();
        $areas = Area::all();
        $departments = Departments::get();
        $location_id_array = [
            'Locations under departments' => 'departments_table',
            'Locations under divisions' => 'divisions_table',
            'Loactions under Sub-divisions' => 'areas_table',
        ];
        return view('admin.departmentLocations')->with([
            'model' => $models,
            'divisions' => $divisions,
            'areas' => $areas,
            'departments' => $departments,
            'locations' => $locations,
            'location_id_array' => $location_id_array,
        ]);
    }
    public function ajaxLocationable(int $id, string $model)
    {
        $locations = Location::where('locationable_type', $model)
            ->where(function ($query) use ($id) {
                if ($id) {
                    $query->where('locationable_id', $id);
                }
            })
            ->get();
        return [
            'data' => $locations,
            'error' => null,
        ];
    }
    public function ajaxLocationDepartments(int $id)
    {
        return $this->ajaxLocationable($id, 'App\Departments');
    }
    public function ajaxLocationDivisions(int $id)
    {
        return $this->ajaxLocationable($id, 'App\Division');
    }
    public function ajaxLocationAreas(int $id)
    {
        return $this->ajaxLocationable($id, 'App\Area');
    }
    public function storeLocation(StoreLocationRequest $request)
    {
        $name = $request->input('name');
        $radio_select = $request->input('radio_select');
        $department = $request->input('department');
        $division = $request->input('division');
        $area = $request->input('area');
        $id = 0;
        $type = null;
        switch ($radio_select) {
            case 'department':
                $id = $department;
                $type = 'App\Departments';
                break;
            case 'division':
                $id = $division;
                $type = 'App\Division';
                break;
            case 'area':
                $id = $area;
                $type = 'App\Area';
                break;
        }
        $location = Location::create([
            'name' => $name,
            'locationable_type' => $type,
            'locationable_id' => $id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return [
            'data' => $location,
            'error' => null,
        ];
    }
    public function deleteLocation(int $id)
    {
        $models = ModenaDisable::first();
        Location::where('id', $id)->delete();
        Ohs::where('location', $id)->delete();
        return redirect()
            ->back()
            ->with([
                'model' => $models,
            ]);
    }
    public function editLocation(UpdateLocationRequest $request, int $id)
    {
        $data = $request->validated();
        $location = Location::findOrFail($id);
        $location->update($data);
        return [
            'data' => null,
            'error' => null,
        ];
    }
    //--------------------------- End: Locations ---------------------------------------------------
    //--------------------------- Standards ---------------------------------------------------
    public function indexStandards()
    {
        $models = Modenadisable::first();
        $standards = Standards::all();

        return view('admin.standards')->with([
            'model' => $models,
            'standards' => $standards,
        ]);
    }
    //--------------------------- End: Standards ---------------------------------------------------

    //--------------------------- Start: Customer Satisfaction ------------------------------------

    public function customerEvaluationDetails()
    {
        $models = ModenaDisable::first();
        $customer_product_type_details = CustomerDetail::where('is_deleted', false)
            ->where('type', 'product')
            ->get();
        $customer_service_type_details = CustomerDetail::where('is_deleted', false)
            ->where('type', 'service')
            ->get();
        $customer_project_type_details = CustomerDetail::where('is_deleted', false)
            ->where('type', 'project')
            ->get();
        $user = User::all();
        $config = Config::where('module', 'customer_satisfaction')->first();

        return view('admin.customer_satisfaction.evaluationDetails')->with([
            'customer_product_type_details' => $customer_product_type_details,
            'customer_service_type_details' => $customer_service_type_details,
            'customer_project_type_details' => $customer_project_type_details,
            'user' => $user,
            'config' => $config,
            'model' => $models,
        ]);
    }

    public function deleteCustomerQstn(int $id)
    {
        $models = ModenaDisable::first();

        CustomerDetail::where('id', $id)->update([
            'is_deleted' => 1,
        ]);

        return redirect('/admin/customer-satisfaction/evaluationDetails')
            ->with('model', $models)
            ->with('message', 'Question deleted successfully');
    }

    public function editCustomerQstn(int $id)
    {
        $models = ModenaDisable::first();

        $customer_detail = CustomerDetail::where('is_deleted', false)->findOrFail($id);

        return view('admin.customer_satisfaction.editQstn')
            ->with('customer_detail', $customer_detail)
            ->with('model', $models);
    }

    public function updateCustomerQstn(UpdateCustomerDetailRequest $req)
    {
        $models = ModenaDisable::first();

        $qstn = $req->input('qstn');
        $id = $req->input('id');
        $score = $req->input('score');

        CustomerDetail::where('is_deleted', false)->where('id', $id)->update([
            'question' => $qstn,
            'score' => $score ? $score : null,
        ]);

        return redirect('/admin/customer-satisfaction/evaluationDetails')->with([
            'model' => $models,
        ]);
    }

    public function addCustomerQstn(string $type)
    {
        // below code related to superadmin
        $models = ModenaDisable::first();

        return view('admin.customer_satisfaction.addQstn')->with([
            'model' => $models,
            'type' => $type,
        ]);
    }

    public function insertCustomerQstn(StoreCustomerDetailRequest $req)
    {
        $models = ModenaDisable::first();
        $question = $req->input('qstn');
        $score = $req->input('score');
        $type = $req->input('type');

        CustomerDetail::firstOrCreate([
            'question' => $question,
            'score' => $score ? $score : null,
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/customer-satisfaction/evaluationDetails')->with([
            'model' => $models,
        ]);
    }

    //--------------------------- End: Customer Satisfaction ------------------------------------
    //-----------------------------Start: Plants--------------------------------------------------
    public function viewPlants()
    {
        $models = DB::table('modenadisable')->First();
        $plants = ComplaintPlant::where('is_deleted',false)->get();
        return view('admin.viewPlants')
            ->with('plants', $plants)
            ->with('model', $models);
    }
    public function addPlant(Request $req){
        $validator = Validator::make($req->all(), [
            'plant_name' => 'required|string|unique:complaint_plants,plant_name,NULL,id,is_deleted,false',
        ],[
            'plant_name.required' => 'The Plant Name field is required.',
            'plant_name.unique' => 'A Plant with the same name already exists.',
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if ($error == null) {
            $plantname = $req->input('plant_name');
            ComplaintPlant::insert([
                'plant_name' => $plantname,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
    public function updatePlant(Request $req)
    {
        $id = $req->input('id');
        $validator = Validator::make($req->all(), [
            'plant_name' => 'required|string|unique:complaint_plants,plant_name,NULL,id,is_deleted,false',
        ],[
            'plant_name.required' => 'The Plant Name field is required.',
            'plant_name.unique' => 'A Plant with the same name already exists.',
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if ($error == null) {
            $plantname = $req->input('plant_name');
            ComplaintPlant::where('id',$id)->where('is_deleted',false)->update([
                'plant_name' => $plantname,
            ]);
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
    public function deletePlant(Request $req){
        $error = null;
        if ($error == null) {
            $id = $req->input('id');
            ComplaintPlant::where('id',$id)->update([
                'is_deleted' => true,
            ]);
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
    //------------------------------END: Plants---------------------------------------------------
    //------------------------------START: Complaint Category-------------------------------------
    public function addComplaintCategory(Request $req){
        $category_plant_dependency = $req->category_plant_dependency;
        if ($category_plant_dependency == "Linked to Plant") {
            $validator = Validator::make($req->all(), [
                // 'cat_name' => 'required|string|unique:complaint_categories,category_name,NULL,id,is_deleted,false',
                'cat_name' => [
                    'required',
                    'string',
                    Rule::unique('complaint_categories', 'category_name')
                        ->where(function ($query) use ($req) {
                            return $query->where('plant_id', $req->input('plant'))
                                        ->where('is_deleted', false);
                        })
                ],
                'plant' => 'required|integer|exists:complaint_plants,id',
            ],[
                'cat_name.required' => 'The Category Name field is required.',
                'cat_name.unique' => 'A Category with the same name already exists.',
                'plant.required' => 'The Plant field is required.',
                'plant.integer' => 'The Plant field is required.',
            ]);
        } elseif ($category_plant_dependency == "Independent") {
            $validator = Validator::make($req->all(), [
                'cat_name' => 'required|string|unique:complaint_categories,category_name,NULL,id,is_deleted,false',
            ],[
                'cat_name.required' => 'The Category Name field is required.',
                'cat_name.unique' => 'A Category with the same name already exists.',
            ]);        }
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if ($error == null) {
            $catname = $req->input('cat_name');
            $plant_id = $req->input('plant');
            ComplaintCategory::insert([
                'category_name' => $catname,
                'plant_id' => $plant_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
    public function editComplaintCategory(Request $req)
    {
        $id = $req->input('id');
        $category_plant_dependency = $req->input('category_plant_dependency');
        if ($category_plant_dependency == 'Linked to Plant') {
            $validator = Validator::make($req->all(), [
                'cat_name' => [
                    'required',
                    'string',
                    Rule::unique('complaint_categories', 'category_name')
                        ->ignore($id, 'id')
                        ->where(function ($query) use ($req) {
                            return $query->where('plant_id', $req->input('plant_id'))
                                        ->where('is_deleted', false);
                        })
                ],
                'plant_id' => 'required|integer|exists:complaint_plants,id',
            ],[
                'cat_name.required' => 'The Category Name field is required.',
                'cat_name.unique' => 'A Category with the same name already exists.',
                'plant_id.required' => 'The Plant field is required.',
                'plant_id.integer' => 'The Plant field is required.',
            ]);
        } elseif($category_plant_dependency == 'Independent') {
            $validator = Validator::make($req->all(), [
                'cat_name' => [
                    'required',
                    'string',
                    Rule::unique('complaint_categories', 'category_name')
                        ->ignore($id, 'id')
                        ->where(function ($query) use ($req) {
                            return $query->where('is_deleted', false);
                        })
                ],
            ],[
                'cat_name.required' => 'The Category Name field is required.',
                'cat_name.unique' => 'A Category with the same name already exists.',
            ]);
        }

        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if ($error == null) {
            $catname = $req->input('cat_name');
            $plant = $req->input('plant_id');
            ComplaintCategory::where('id',$id)->where('is_deleted',false)->update([
                'category_name' => $catname,
                'plant_id' => $plant ?? null,
            ]);
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
    public function deleteComplaintCategory(Request $req){
        $error = null;
        if ($error == null) {
            $id = $req->input('id');
            ComplaintCategory::where('id',$id)->update([
                'is_deleted' => true,
            ]);
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
    public function addCategoryExcel(Request $request){
        $category_plant_dependency = $request->category_plant_dependency;

        $validator = Validator::make($request->all(), [
            'category_details' => 'required|mimes:xls,xlsx',
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'model' => $models
            ]);
        } else {
            $rows = Excel::toArray(new CategoryImport, $request->file('category_details'))[0];
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    if($category_plant_dependency == 'Independent') {
                        if (empty($row['category_name'])) {
                            return response()->json([
                                'success' => false,
                                'errors' => 'Category Name is empty.Please refer Note for excel upload format'
                            ]);
                        } else {
                            $data = array(
                                'category_name' => $row['category_name'],
                                'created_at' => now(),
                                'updated_at' => now()
                            );
                            //checking if a category for a particular plant already exists in database
                            $catcheck = DB::table('complaint_categories')->where('category_name', $row['category_name'])->where('is_deleted',false)->get();
                            if (count($catcheck) > 0) {
                                continue;
                            } else {
                                DB::table('complaint_categories')->insert($data);
                            }
                        }
                    } else {
                        if (empty($row['category_name']) || empty($row['plant_name'])) {
                            return response()->json([
                                'success' => false,
                                'errors' => 'Category Name or Plant Name data is empty.Please refer Note for excel upload format'
                            ]);
                        } else {
                            $plant_id = DB::table('complaint_plants')->where('plant_name', $row['plant_name'])->where('is_deleted',false)->value('id');
                            if ($plant_id) {
                                $data = array(
                                    'category_name' => $row['category_name'],
                                    'plant_id' => $plant_id,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                );
                                //checking if a category for a particular plant already exists in database
                                $catcheck = DB::table('complaint_categories')->where('category_name', $row['category_name'])
                                    ->where('plant_id', $plant_id)->where('is_deleted',false)
                                    ->get();

                                if (count($catcheck) > 0) {
                                    continue;
                                } else {
                                    DB::table('complaint_categories')->insert($data);
                                }
                            }else{
                                continue;
                            }
                        }
                    }
                }
            }

            if (!File::exists(public_path('/storage/complaint/'))) {
                File::makeDirectory(public_path() . '/storage/complaint/', 0755, true);
            }

            $name = 'category_details' . '.' . $request->file('category_details')->extension();
            $request->file('category_details')->move(public_path() . '/storage/complaint/', $name);
            $models = DB::table('modenadisable')->First();
            return response()->json([
                'success' => true,
                'message' => 'Category Datas Uploaded successfully',
                'model' => $models
            ]);

        }

    }
    //------------------------------END: Complaint Category---------------------------------------

    //------------------------------START: Complaint Units----------------------------------------
    public function complaintUnit(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $types = Type::all();
        $units = ComplaintUnit::where('is_deleted',false)->get();

        return view('admin.complaint.unit')
            ->with('types', $types)
            ->with('model', $models)
            ->with('units',$units);
    }

    public function addComplaintUnit(Request $request){
        $validator = Validator::make($request->all(), [
            'unit' => 'required|string|unique:complaint_units,name,NULL,id,is_deleted,false',
        ],[
            'unit.required' => 'The Unit Name field is required.',
            'unit.unique' => 'A Unit with the same name already exists.',
        ]);
        $models = DB::table('modenadisable')->First();
        if($validator->fails()){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('model', $models);
        }else{
            $unit = $request->input('unit');
            ComplaintUnit::insert([
                'name' => $unit,
                'updated_at' => now(),
                'created_at' => now()
            ]);
            Session::flash('msg','Complaint Unit Name has been added successfully.');
            return redirect()->back()->with('model',$models);
        }
    }
    public function deleteComplaintUnit(Request $req){
        $error = null;
        if ($error == null) {
            $id = $req->input('id');
            ComplaintUnit::where('id',$id)->update([
                'is_deleted' => true,
                'updated_at' => now()
            ]);
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
    public function editComplaintUnit(Request $req)
    {
        $id = $req->input('unitid');
        $validator = Validator::make($req->all(), [
            'unit_name' => [
                'required',
                'string',
                Rule::unique('complaint_units', 'name')
                    ->ignore($id, 'id')
                    ->where(function ($query) use ($req) {
                        return $query->where('is_deleted', false);
                    })
            ],
        ],[
            'unit_name.required' => 'The Unit Name field is required.',
            'unit_name.unique' => 'A Unit with the same name already exists.',
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if ($error == null) {
            $unitname = $req->input('unit_name');
            ComplaintUnit::where('id',$id)->where('is_deleted',false)->update([
                'name' => $unitname,
                'updated_at' => now()
            ]);
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
    //------------------------------END: Complaint Units------------------------------------------

 //---------------------Audit Dynamic reports starts--------------------//
   public function auditdynamicreports()
   {
     $models = DB::table('modenadisable')->First();
     $audit_reports = ModuleReportDetail::where('module_name','audit')->first();
     return view('admin.reports.add_reports')
            ->with('audit_reports', $audit_reports)
            ->with('model', $models);
   }

   public function uploadauditreport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audit_report' => 'required|mimes:doc,docx',
        ],
            [
                'audit_report.required' => 'Please upload report template',
                'audit_report.mimes' => 'Report Template should be a word document',
            ]
        );

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()->withErrors($validator)->withInput()->with('model', $models);
        } else {
            $models = DB::table('modenadisable')->First();
            if ($request->hasfile('audit_report')) {
                $report = $request->file('audit_report');
                $filename = 'audit_report_template'.time() .'.' . $report->getClientOriginalExtension();
                if (!File::exists(public_path('/storage/ModuleReportTemplates/audit'))) {
                    File::makeDirectory(public_path() . '/storage/ModuleReportTemplates/audit', 0755, true);
                }
                $destination_path = public_path('storage/ModuleReportTemplates/audit/');
                $report->move($destination_path, $filename);
                $data = array('module_name' =>'audit', 'file_name' => $filename);
                $exists = ModuleReportDetail::where('module_name','audit')->first();
                if ($exists) {
                    if (file_exists(public_path('/storage/ModuleReportTemplates/audit/'.$exists->file_name))){
                        $filedeleted = unlink(public_path('/storage/ModuleReportTemplates/audit/'.$exists->file_name));
                    }
                    ModuleReportDetail::where('module_name','audit')->delete();
                }
                ModuleReportDetail::create($data);
            }

            session()->flash('msg', 'Report Template uploaded successfully');
            return redirect()->route('admin.audit.dynamicreports');
        }
    }
 //---------------------Ends Audit Dynamic reports --------------------//


    public function restoreUser($id)
    {
        $textColor = 'FFFFFF';
        $backgroundColors = [
            '1F42AD',
            '1F42AD',
            'F5993D',
            'E64C73',
            'AD858F',
            '9952E0',
            '75ABBD',
            'AD661F',
            'C63939',
            '0D80F2',
            'E87DB3',
            '7594F0',
            '7DA1E8'
        ];

        try {
            $user = User::onlyTrashed()->findOrFail($id);
            if (!$user->profile_picture || !File::exists(public_path('/storage/profile/'. $user->profile_picture))) {
                $backgroundColorIndex = array_rand($backgroundColors);
                $image = file_get_contents('https://ui-avatars.com/api/?name=' . rawurlencode($user->name) . '&color=' . $textColor . '&background=' . $backgroundColors[$backgroundColorIndex] . '&rounded=true&bold=true');
                $fileName = $user->employeeid . '_' . time() . '.png';
                file_put_contents(public_path('storage/profile') . '/' . $fileName, $image);
                $user->profile_picture = $fileName;
                $user->save();
            }
            $user->restore();

            return response()->json([
                'success' => true,
                'message' => 'User restored successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restoreMultipleUsers(Request $request)
    {
        $textColor = 'FFFFFF';
        $backgroundColors = [
            '1F42AD',
            '1F42AD',
            'F5993D',
            'E64C73',
            'AD858F',
            '9952E0',
            '75ABBD',
            'AD661F',
            'C63939',
            '0D80F2',
            'E87DB3',
            '7594F0',
            '7DA1E8'
        ];

        try {
            $userIds = $request->input('user_ids', []);

            if (empty($userIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No users selected'
                ], 400);
            }

            $restoredCount = 0;
            foreach ($userIds as $userId) {
                $user = User::onlyTrashed()->find($userId);
                if (!$user->profile_picture || !File::exists(public_path('/storage/profile/'. $user->profile_picture))) {
                    $backgroundColorIndex = array_rand($backgroundColors);
                    $image = file_get_contents('https://ui-avatars.com/api/?name=' . rawurlencode($user->name) . '&color=' . $textColor . '&background=' . $backgroundColors[$backgroundColorIndex] . '&rounded=true&bold=true');
                    $fileName = $user->employeeid . '_' . time() . '.png';
                    file_put_contents(public_path('storage/profile') . '/' . $fileName, $image);
                    $user->profile_picture = $fileName;
                    $user->save();
                }

                if ($user) {
                    $user->restore();
                    $restoredCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully restored {$restoredCount} users"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore users: ' . $e->getMessage()
            ], 500);
        }
    }

    public function permanentDeleteUser($id)
    {
        try {
            $user = \App\User::onlyTrashed()->findOrFail($id);
            $userName = $user->name;
            $user->forceDelete(); // Permanently delete the user

            // Check if it's an AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User "' . $userName . '" has been permanently deleted successfully.'
                ]);
            }

            return redirect()->back()->with('success', 'User permanently deleted successfully.');
        } catch (\Exception $e) {
            // Check if it's an AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to permanently delete user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to permanently delete user: ' . $e->getMessage());
        }
    }
    public function indexCompliance(){
        $models = Modenadisable::first();
        $authorities = RegulatoryAuthority::with([
            'creator', 'modifier',
            'complianceLegalDocuments' => function ($query) {
                $query->where('status', 'Approved')
                    ->with(['creator', 'modifier']);
            }
        ])->where('status', 'Approved')->orderByDesc('created_at')->get();

        $pendingAuthorities = RegulatoryAuthority::with([
            'creator', 'modifier',
            'complianceLegalDocuments' => function ($query) {
                $query->whereIn('status', ['Pending Review', 'Rejected'])
                    ->with(['creator', 'modifier']);
            }
        ])
        ->whereHas('complianceLegalDocuments', function ($query) {
            $query->whereIn('status', ['Pending Review', 'Rejected']);
        })->orderByDesc('created_at')->get();
        return view('admin.compliance.index')->with([
            'model' => $models,
            'authorities' => $authorities,
            'pendingAuthorities' => $pendingAuthorities
        ]);
    }
    public function addComplianceRegulatory(Request $req){
        $validator = Validator::make($req->all(), [
            'authname' => 'required|string|unique:regulatory_authorities,name,NULL,id,deleted_at,NULL',
        ],[
            'authname.required' => 'The Regulatory Authority Name field is required.',
            'authname.unique' => 'A Regulatory Authority with the same name already exists.',
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        $currentUser = User::where('email',Auth::user()->email)->value('id');
        if ($error == null) {
            $authname = $req->input('authname');
            $legalDocuments = $req->input('dropdown_options', []);
            // normalize to array and ensure not null
            // normalize to array and ensure not null
            if ($legalDocuments === null) {
                $legalDocuments = [];
            }

            if (!is_array($legalDocuments)) {
                if (is_string($legalDocuments) && $legalDocuments !== '') {
                    $legalDocuments = array_filter(array_map('trim', explode(',', $legalDocuments)), function ($v) {
                        return $v !== '' && $v !== null;
                    });
                    $legalDocuments = array_values($legalDocuments);
                } else {
                    $legalDocuments = [];
                }
            } else {
                // it's an array (e.g. [ null ]). remove null/empty values and trim strings
                $legalDocuments = array_values(array_filter(array_map(function ($v) {
                    if (is_string($v)) {
                        return trim($v);
                    }
                    return $v;
                }, $legalDocuments), function ($v) {
                    return $v !== null && $v !== '';
                }));
            }
            $record = RegulatoryAuthority::create([
                'name' => $authname,
                'no_of_documents' => count($legalDocuments),
                'initial_name' => $authname,
                'status' => 'Approved',
                'created_by' => $currentUser,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            if($record){
                if(count($legalDocuments) > 0){
                    foreach($legalDocuments as $document){
                        ComplianceLegalDocument::insert([
                            'RAC_id' => $record->id,
                            'name' => $document,
                            'status' => 'Approved',
                            'created_by' => $currentUser,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            Session::flash('msg','Regulatory Authority has been added successfully.');
            return [
                'error' => $error,
            ];
        }
    }
    public function editComplianceRegulatory(Request $req){
        $validator = Validator::make($req->all(), [
            'authname_edit' => [
            'required',
            'string',
            \Illuminate\Validation\Rule::unique('regulatory_authorities', 'name')
                ->ignore($req->input('RAC_id'))
                ->whereNull('deleted_at'),
        ]
        ],[
            'authname_edit.required' => 'The Regulatory Authority Name field is required.',
            'authname_edit.unique' => 'A Regulatory Authority with the same name already exists.',
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        $currentUser = User::where('email',Auth::user()->email)->value('id');
        if ($error == null) {
            $RAC_id = $req->input('RAC_id');
            $auth_name = $req->input('authname_edit');
            RegulatoryAuthority::where('id',$RAC_id)->update([
                'name' => $auth_name,
                'updated_name' => $auth_name,
                'status' => 'Approved',
                'modified_by' => $currentUser,
                'modified_at' => now(),
                'updated_at' => now(),
            ]);
            Session::flash('msg','Regulatory Authority: #'.$auth_name.' has been updated successfully.');
            return [
                'error' => $error,
            ];
        }
    }
    public function getEditRegulatoryModal($id){
        $responseData = [];
        try {
            $authority = RegulatoryAuthority::with([
                'complianceLegalDocuments'
            ])->findOrFail($id);
            $models = ModenaDisable::first();
            $responseData['view'] = \view('admin.compliance.edit_regulatory', compact(
                'authority', 'models'
            ))->render();
            $responseData['id'] = $id;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
    public function deleteComplianceRegulatory($id){
        try {
            RegulatoryAuthority::where('id', $id)->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);
            ComplianceLegalDocument::where('RAC_id', $id)->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json([
                'success' => true,
                'message' => "Regulatory authority and its associated legal documents were deleted successfully."
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting Regulatory AUthorities: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Internal server error.']);
        }
    }
    public function checkUniqueRegulatory(Request $req){
        $name = $req->input('auth_name');
        $RAC_id = $req->input('RAC_id');
        if($RAC_id){
            $exists = RegulatoryAuthority::where('name', $name)
            ->where('id','!=',$RAC_id)
            ->whereNull('deleted_at')
            ->first();
        }else{
            $exists = RegulatoryAuthority::where('name', $name)
                ->whereNull('deleted_at')
                ->first();
        }
        if ($exists) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }
    public function addLegalDocument(Request $req){
        $error = null;
        $currentUser = User::where('email',Auth::user()->email)->value('id');
        if ($error == null) {
            $legalDocuments = $req->input('legal_documents', []);
            $RAC_id = $req->input('ragid');
            // normalize to array and ensure not null
            if ($legalDocuments === null) {
                $legalDocuments = [];
            }

            if (!is_array($legalDocuments)) {
                if (is_string($legalDocuments) && $legalDocuments !== '') {
                    $legalDocuments = array_filter(array_map('trim', explode(',', $legalDocuments)), function ($v) {
                        return $v !== '' && $v !== null;
                    });
                    $legalDocuments = array_values($legalDocuments);
                } else {
                    $legalDocuments = [];
                }
            } else {
                // it's an array (e.g. [ null ]). remove null/empty values and trim strings
                $legalDocuments = array_values(array_filter(array_map(function ($v) {
                    if (is_string($v)) {
                        return trim($v);
                    }
                    return $v;
                }, $legalDocuments), function ($v) {
                    return $v !== null && $v !== '';
                }));
            }
            RegulatoryAuthority::where('id',$RAC_id)->update([
                'no_of_documents' => DB::raw('no_of_documents + '.count($legalDocuments)),
                'updated_at' => now(),
            ]);
            if(count($legalDocuments) > 0){
                foreach($legalDocuments as $document){
                    ComplianceLegalDocument::insert([
                        'RAC_id' => $RAC_id,
                        'name' => $document,
                        'status' => 'Approved',
                        'created_by' => $currentUser,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            Session::flash('msg','Legal Document names has been added successfully.');
            return [
                'error' => $error,
            ];
        }
    }
    public function deleteLegalDocument($id){
        try {
            $doc = ComplianceLegalDocument::where('id', $id)->firstOrFail();
            if($doc){
                $regAuthority = RegulatoryAuthority::where('id',$doc->RAC_id)->first();
                if($regAuthority && $regAuthority->no_of_documents > 0){
                    RegulatoryAuthority::where('id',$doc->RAC_id)->update([
                        'no_of_documents' => DB::raw('no_of_documents - 1'),
                    ]);
                }
                $doc->deleted_at = now();
                $doc->updated_at = now();
                $doc->save();
            }

            return response()->json([
                'success' => true,
                'message' => "Legal document has been deleted successfully."
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting Legal Document: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Internal server error.']);
        }
    }
    public function editLegalDocument(Request $req){
        $id = $req->input('doc_id');
        $doc = ComplianceLegalDocument::where('id',$id)->first();
        $validator = Validator::make($req->all(), [
            'doc_name' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('compliance_legal_documents', 'name')
                    ->ignore($id)
                    ->where('RAC_id',$doc->RAC_id)
                    ->whereNull('deleted_at'),
            ],
        ],[
            'doc_name.required' => 'The Legal Document Name field is required.',
            'doc_name.unique' => 'A Legal Document with the same name already exists.',
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        $currentUser = User::where('email',Auth::user()->email)->value('id');
        if ($error == null) {
            $docname = $req->input('doc_name');
            $doc->update([
                'name' => $docname,
                'modified_by' => $currentUser,
                'modified_at' => now(),
                'updated_at' => now(),
            ]);
            Session::flash('msg','Legal Document: #'.$docname.' has been updated successfully.');
            return [
                'error' => $error,
            ];
        }
    }
    public function getEditLegalDocumentModal($id){
        $responseData = [];
        try {
            $legalDocument = ComplianceLegalDocument::findOrFail($id);
            $models = ModenaDisable::first();
            $responseData['view'] = \view('admin.compliance.edit_document', compact(
                'legalDocument','models'
            ))->render();
            $responseData['id'] = $id;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
    public function checkUniqueLegalDocument(Request $req){
        $docnames = $req->input('doc_name');
        $RAC_id = $req->input('rac_id');
        $doc_id = $req->input('doc_id');
        //Edit Mode
        if($doc_id){
            $RAC_id = ComplianceLegalDocument::where('id',$doc_id)->value('RAC_id');
            $exists = ComplianceLegalDocument::where('name', $docnames)
                ->where('RAC_id',$RAC_id)
                ->where('id','!=',$doc_id)
                ->whereNull('deleted_at')
                ->first();
            return response()->json([
                'exists' => $exists ? true : false,
            ]);
        }else{
            //New Mode
            $existing = ComplianceLegalDocument::whereIn('name', $docnames)
                ->where('RAC_id',$RAC_id)
                ->whereNull('deleted_at')
                ->pluck('name')->toArray();
            if (count($existing) > 0) {
                return response()->json([
                    'exists' => true,
                    'duplicates' => $existing,
                    'msg' => 'Some document names already exist in the system.',
                ]);
            } else {
                return response()->json(['exists' => false]);
            }
        }
    }
    public function viewComplianceDetails($id){
        $responseData = [];
        try {
            $authority = RegulatoryAuthority::with([
                'complianceLegalDocuments.creator',
                'complianceLegalDocuments.modifier',
                'creator',
                'modifier',
            ])->findOrFail($id);
            $models = ModenaDisable::first();
            $responseData['view'] = \view('admin.compliance.view_regulatory', compact(
                'authority', 'models'
            ))->render();
            $responseData['id'] = $id;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
    public function reviewLegalDocumentMultiple(Request $req)
    {
        $now = now();
        $ids = $req->input('docIds');
        $action = $req->input('action');
        $racId = $req->input('racId');
        $loggedInUserEmail = Auth::user()->email;

        // 1. Check Admin Authorization
        $adminDetails = Admin::where('email', $loggedInUserEmail)->first();

        if (!$adminDetails) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access this page.'
            ], 403);
        }

        // Ensure IDs is an array (safety check)
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No documents selected.']);
        }

        try {
            $currentUser = User::where('email', $adminDetails->email)->value('id');

            // Fetch all documents at once
            $legalDocuments = ComplianceLegalDocument::with('creator')->whereIn('id', $ids)->get();

            if ($legalDocuments->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Records not found.']);
            }
            $notificationItem = ($action == 'Approved') ? 'Legal Document Approved' : 'Legal Document Rejected';

            $emailNotification = EmailNotification::where('module', 'compliance')
                ->where('item', $notificationItem)
                ->where('is_blocked', false)
                ->first();

            $adminEmails = [];
            if ($emailNotification) {
                $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                if ($compl_userdatas->isNotEmpty()) {
                    $adminAddedUserIds = $compl_userdatas->pluck('user_id')->toArray();
                    $adminEmails = User::whereIn('id', $adminAddedUserIds)->pluck('email')->toArray();
                }
            }
            // -------------------------------------------------------

            $updatedCount = 0;

            // 2. Loop through the documents
            foreach ($legalDocuments as $legalDocument) {

                // Process only if Pending
                if ($legalDocument->status != 'Approved') {

                    // Determine new status strings
                    $approvedByField = ($action == 'Approved') ? 'approved_by' : 'rejected_by';
                    $approvedAtField = ($action == 'Approved') ? 'approved_at' : 'rejected_at';

                    // A. Update the Legal Document
                    $legalDocument->update([
                        'status' => $action,
                        'updated_at' => $now,
                        $approvedByField => $currentUser,
                        $approvedAtField => $now,
                        'token' => null,
                    ]);

                    $updatedCount++;


                    $regulatory = RegulatoryAuthority::where('id', $racId)->first();
                    // C. Handle Emails
                    if ($emailNotification) {
                        $emailData = [
                            'type' => 'Legal Document',
                            'name' => $regulatory ? $regulatory->name : 'N/A',
                            'doc_name' => $legalDocument->name,
                            'creator' => $legalDocument->creator ? $legalDocument->creator->name : '',
                            'action_by' => Auth::user()->name,
                            'date' => $now->format('d-m-Y h:i:s A'),
                            'item' => $emailNotification->item,
                            'email_content' => $emailNotification->email_content,
                        ];

                        // Select Mailable class based on action
                        $mailable = ($action == 'Approved')
                            ? new LegalDocumentApproved($emailData)
                            : new LegalDocumentRejected($emailData);

                        // Send to Creator
                        if ($legalDocument->creator && $legalDocument->creator->email) {
                            try {
                                Mail::to($legalDocument->creator->email)->send($mailable);
                            } catch (\Exception $e) {
                                Log::error("Failed sending $action email to creator (Doc ID: {$legalDocument->id}): " . $e->getMessage());
                            }
                        }

                        // Send to Admins (using pre-fetched list)
                        if (!empty($adminEmails)) {
                            try {
                                Mail::to($adminEmails)->send($mailable);
                            } catch (\Exception $e) {
                                Log::error("Failed sending $action email to admins: " . $e->getMessage());
                            }
                        }
                    }
                }
            }

            // D. Return Response
            if ($updatedCount > 0) {
                return response()->json([
                    'success' => true,
                    'message' => "Selected legal documents have been updated to $action."
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending documents were available to update.'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Review Legal Document Batch Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An internal error occurred. Please check logs.'
            ], 500);
        }
    }
    public function reviewLegalDocument(Request $req)
    {
        $now = now();
        $id = $req->input('docId');
        $action = $req->input('action');
        $loggedInUserEmail = Auth::user()->email;

        // 1. Check Admin Authorization
        $adminDetails = Admin::where('email', $loggedInUserEmail)->first();

        if (!$adminDetails) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access this page.'
            ], 403);
        }

        try {
            $currentUser = User::where('email', $adminDetails->email)->value('id');

            $legalDocument = ComplianceLegalDocument::with('creator')->where('id', $id)->first();

            if (!$legalDocument) {
                return response()->json(['success' => false, 'message' => 'Legal Document not found.']);
            }
            $approvedByField = ($action == 'Approved') ? 'approved_by' : 'rejected_by';
            $approvedAtField = ($action == 'Approved') ? 'approved_at' : 'rejected_at';

            // A. Update the Legal Document
            $legalDocument->update([
                'status' => $action,
                'updated_at' => $now,
                $approvedByField => $currentUser,
                $approvedAtField => $now,
                'token' => null,
            ]);
            $regulatory = RegulatoryAuthority::where('id', $legalDocument->RAC_id)->first();

            // C. Handle Emails
            $notificationItem = ($action == 'Approved') ? 'Legal Document Approved' : 'Legal Document Rejected';

            $emailNotification = EmailNotification::where('module', 'compliance')
                ->where('item', $notificationItem)
                ->where('is_blocked', false)
                ->first();

            if ($emailNotification) {
                $emailData = [
                    'type' => 'Legal Document',
                    'name' => $regulatory ? $regulatory->name : 'N/A',
                    'doc_name' => $legalDocument->name,
                    'creator' => $legalDocument->creator ? $legalDocument->creator->name : '',
                    'action_by' => Auth::user()->name,
                    'date' => $now->format('d-m-Y h:i:s A'),
                    'item' => $emailNotification->item,
                    'email_content' => $emailNotification->email_content,
                ];

                // Select Mailable class based on action
                $mailable = ($action == 'Approved')
                    ? new LegalDocumentApproved($emailData)
                    : new LegalDocumentRejected($emailData);

                // Send to Creator
                if ($legalDocument->creator && $legalDocument->creator->email) {
                    try {
                        Mail::to($legalDocument->creator->email)->send($mailable);
                    } catch (\Exception $e) {
                        Log::error("Failed sending $action email to creator: " . $e->getMessage());
                    }
                }

                // Send to Admins
                $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                if ($compl_userdatas->isNotEmpty()) {
                    $adminAddedUserIds = $compl_userdatas->pluck('user_id')->toArray();
                    $adminEmails = User::whereIn('id', $adminAddedUserIds)->pluck('email')->toArray();

                    if (!empty($adminEmails)) {
                        try {
                            Mail::to($adminEmails)->send($mailable);
                        } catch (\Exception $e) {
                            Log::error("Failed sending $action email to admins: " . $e->getMessage());
                        }
                    }
                }
            }

            // D. Return Success JSON
            return response()->json([
                'success' => true,
                'message' => "Legal Document status has been updated to $action."
            ]);

        } catch (\Exception $e) {
            Log::error('Review Legal Document Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An internal error occurred. Please check logs.'
            ], 500);
        }
    }
    public function reviewRegulatoryMultiple(Request $req)
    {
        $now = now();
        $ids = $req->input('racIds');
        $action = $req->input('action');
        $loggedInUserEmail = Auth::user()->email;

        // 1. Check Admin Access
        $isAdmin = Admin::where('email', $loggedInUserEmail)->exists();

        if (!$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action.'
            ], 403);
        }

        // Ensure IDs is an array (safety check)
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            $currentUser = User::where('email', $loggedInUserEmail)->first();

            // 2. Fetch all requested Regulatory Authorities with their creators
            $regulatories = RegulatoryAuthority::with('creator')->whereIn('id', $ids)->get();

            if ($regulatories->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Records not found.']);
            }

            $notificationItem = $action == 'Approved'
                ? 'Regulatory Authority Approved'
                : 'Regulatory Authority Rejected';

            $emailNotification = EmailNotification::where('module', 'compliance')
                ->where('item', $notificationItem)
                ->where('is_blocked', false)
                ->first();

            $adminEmails = [];
            if ($emailNotification) {
                $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                if ($compl_userdatas->isNotEmpty()) {
                    $adminAddedUserIds = $compl_userdatas->pluck('user_id')->toArray();
                    $adminEmails = User::whereIn('id', $adminAddedUserIds)->pluck('email')->toArray();
                }
            }

            // 4. Process Each Regulatory Authority
            foreach ($regulatories as $regulatory) {
                if(!($regulatory->status=='Approved')){
                    // A. Update Regulatory Status
                    $updateData = [
                        'status' => $action,
                        'updated_at' => $now,
                        ($action == 'Approved' ? 'approved_by' : 'rejected_by') => $currentUser->id,
                        ($action == 'Approved' ? 'approved_at' : 'rejected_at') => $now,
                        'token' => null,
                    ];
                    $regulatory->update($updateData);
                    // B. Update Associated Legal Documents if Rejected
                    if($action == 'Rejected'){
                        ComplianceLegalDocument::where('RAC_id', $regulatory->id)
                            ->where('status', '!=', 'Rejected')
                            ->update([
                                'status' => 'Rejected',
                                'updated_at' => $now,
                                'rejected_by' => $currentUser->id,
                                'rejected_at' => $now,
                                'token' => null,
                            ]);
                    }
                }

                // C. Handle Emails
                if ($emailNotification) {
                    // Get doc names specific to THIS regulatory authority
                    $docNamesArray = ComplianceLegalDocument::where('RAC_id', $regulatory->id)
                        ->where('status', $action)
                        ->pluck('name')
                        ->toArray();

                    $docNamesString = count($docNamesArray) > 0 ? implode(', ', $docNamesArray) : '';

                    $emailData = [
                        'type' => 'Regulatory Authority',
                        'name' => $regulatory->name,
                        'doc_name' => $docNamesString,
                        'creator' => $regulatory->creator ? $regulatory->creator->name : '',
                        'action_by' => $currentUser->name,
                        'date' => $now->format('d-m-Y h:i:s A'),
                        'item' => $emailNotification->item,
                        'email_content' => $emailNotification->email_content,
                        'status' => $action
                    ];

                    $mailable = $action == 'Approved'
                        ? new RegulatoryAuthorityApproved($emailData)
                        : new RegulatoryAuthorityRejected($emailData);

                    // Send to Creator
                    if ($regulatory->creator && $regulatory->creator->email) {
                        try {
                            Mail::to($regulatory->creator->email)->send($mailable);
                        } catch (\Exception $e) {
                            Log::error("Failed sending $action email to creator (ID: {$regulatory->creator->id}): " . $e->getMessage());
                        }
                    }

                    // Send to Admins (using pre-fetched list)
                    if (!empty($adminEmails)) {
                        try {
                            Mail::to($adminEmails)->send($mailable);
                        } catch (\Exception $e) {
                            Log::error("Failed sending $action email to admins: " . $e->getMessage());
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Selected regulatory authorities have been updated to $action."
            ]);

        } catch (\Exception $e) {
            Log::error('Review Regulatory Batch Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An internal error occurred. Please check logs.'
            ], 500);
        }
    }
    public function reviewRegulatory(Request $req)
    {
        $now = now();
        $id = $req->input('racId');
        $action = $req->input('action');
        $loggedInUserEmail = Auth::user()->email;

        // Check Admin Access
        $isAdmin = Admin::where('email', $loggedInUserEmail)->exists();

        if (!$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action.'
            ], 403);
        }

        try {
            $currentUser = User::where('email', $loggedInUserEmail)->first();
            $regulatory = RegulatoryAuthority::with('creator')->find($id);

            if (!$regulatory) {
                return response()->json(['success' => false, 'message' => 'Regulatory Authority not found.']);
            }

            // 1. Prepare Update Data based on Action
            $updateData = [
                'status' => $action,
                'updated_at' => $now,
                'token' => null,
                ($action == 'Approved' ? 'approved_by' : 'rejected_by') => $currentUser->id,
                ($action == 'Approved' ? 'approved_at' : 'rejected_at') => $now,
            ];
            // 2. Update Regulatory Authority and Documents
            $regulatory->update($updateData);
            if($action == 'Rejected'){
                ComplianceLegalDocument::where('RAC_id', $id)
                    ->where('status', '!=', 'Rejected')
                    ->update([
                        'status' => 'Rejected',
                        'updated_at' => $now,
                        'rejected_by' => $currentUser->id,
                        'rejected_at' => $now,
                        'token' => null,
                    ]);
            }
            // 3. Prepare Email Data
            $docNamesArray = ComplianceLegalDocument::where('RAC_id', $id)
                ->where('status', $action)
                ->pluck('name')
                ->toArray();

            $docNamesString = count($docNamesArray) > 0 ? implode(', ', $docNamesArray) : '';

            $notificationItem = $action == 'Approved'
                ? 'Regulatory Authority Approved'
                : 'Regulatory Authority Rejected';

            $emailNotification = EmailNotification::where('module', 'compliance')
                ->where('item', $notificationItem)
                ->where('is_blocked', false)
                ->first();

            // 4. Send Emails
            if ($emailNotification) {
                $emailData = [
                    'type' => 'Regulatory Authority',
                    'name' => $regulatory->name,
                    'doc_name' => $docNamesString,
                    'creator' => $regulatory->creator ? $regulatory->creator->name : '',
                    'action_by' => $currentUser->name,
                    'date' => $now->format('d-m-Y h:i:s A'),
                    'item' => $emailNotification->item,
                    'email_content' => $emailNotification->email_content,
                    'status' => $action
                ];

                $mailable = $action == 'Approved' ? new RegulatoryAuthorityApproved($emailData) : new RegulatoryAuthorityRejected($emailData);

                // A. Send to Creator
                if ($regulatory->creator && $regulatory->creator->email) {
                    try {
                        Mail::to($regulatory->creator->email)->send($mailable);
                    } catch (\Exception $e) {
                        Log::error("Failed sending $action email to creator: " . $e->getMessage());
                    }
                }

                // B. Send to Admin Notification Users
                $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                if ($compl_userdatas->isNotEmpty()) {
                    $adminAddedUserIds = $compl_userdatas->pluck('user_id')->toArray();
                    $adminEmails = User::whereIn('id', $adminAddedUserIds)->pluck('email')->toArray();
                    if (!empty($adminEmails)) {
                        try {
                            Mail::to($adminEmails)->send($mailable);
                        } catch (\Exception $e) {
                            Log::error("Failed sending $action email to admins: " . $e->getMessage());
                        }
                    }
                }
            }

            // 5. Return JSON for AJAX
            return response()->json([
                'success' => true,
                'message' => "The regulatory authority status has been updated to $action."
            ]);

        } catch (\Exception $e) {
            Log::error('Review Regulatory Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An internal error occurred. Please check logs.'
            ], 500);
        }
    }


    public function viewPNCHistory($fieldName,$categoryId){
        $responseData = [];
        try {
            $category = Categories::findOrFail($categoryId);
            $model = null;
            if($category->is_pnc_cat=='yes'){
                $model = ProductNcrFieldHistory::class;
            }else if($category->is_process_cat=='yes'){
                $model = ProcessNcrFieldHistory::class;
            }else if($category->is_vendor_cat=='yes'){
                $model = VendorNcrFieldHistory::class;
            }
            $histories = $model::with([
                'modifierDetails',
            ])->withTrashed()->where('field_name',$fieldName)->get();

            $models = ModenaDisable::first();
            $responseData['view'] = \view('admin.corrective.view_product_ncr_fields', compact(
                'histories', 'models'
            ))->render();
            $responseData['fieldname'] = $fieldName;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            $responseData['msg'] = $exception->getMessage();
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
    public function viewNcrAddedField($categoryId){
        $responseData = [];
        try {
            $category = Categories::findOrFail($categoryId);
            $addedFields = collect();
            if($category->is_pnc_cat=='yes'){
                $addedFields = ProductNcrConfigurationAddedField::all();
            }else if($category->is_process_cat=='yes'){
                $addedFields = ProcessNcrConfigurationAddedField::all();
            }else if($category->is_vendor_cat=='yes'){
                $addedFields = VendorNcrConfigurationAddedField::all();
            }
            $models = ModenaDisable::first();
            $responseData['view'] = \view('admin.corrective.ncr_fields_model', compact(
                'addedFields', 'models', 'category'
            ))->render();
            $responseData['category'] = $categoryId;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            $responseData['msg'] = $exception->getMessage();
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    //Corrective Detection Stage Start
    public function checkAddDetectionStage(Request $request){
        $detectionStageName = $request->input('stage_name');
        $exists = CorrectiveDetectionStage::where('name', $detectionStageName)->first();
        if ($exists) {
            return response()->json(['exists' => true, 'msg' => 'This detection name is already selected. Please choose a different one.']);
        } else {
            return response()->json(['exists' => false]);
        }
    }
    public function addDetectionStage(Request $request){
        $detectionStageName = $request->input('detectionStageName');
        $found = CorrectiveDetectionStage::where('name', $detectionStageName)->first();
        $error = $found ? 'Detection Stage already exists' : '';
        $userId      = User::where('email', Auth::user()->email)->value('id');
        if ($error === '') {
            DB::transaction(function () use ($detectionStageName, $userId) {
                $existingStage = CorrectiveDetectionStage::withTrashed()
                    ->where('name', $detectionStageName)->first();

                if ($existingStage && $existingStage->trashed()) {
                    $existingStage->restore();
                } else {
                    CorrectiveDetectionStage::create([
                        'name' => $detectionStageName,
                        'created_by' => $userId
                    ]);
                }
                //inserting data to activities table
                $activity = 'Detection Stage is Added with Name as ' . $detectionStageName;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);
            });
        }

        return [
            'data' => null,
            'error' => $error,
        ];
    }
    public function removeDetectionStage($stageId){
        //inserting data to activities table
        $detectionStage = CorrectiveDetectionStage::where('id', $stageId)->first();
        $activity = 'Detection Stage is Removed with Name ' . $detectionStage->name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'activity' => $activity,
            'type' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        ViewActivity::insert($datas);
        Session::flash('message','Detection Stage #'.$detectionStage->name.' deleted successfully');
        $detectionStage->delete();
        $detectionStagesCount = CorrectiveDetectionStage::get()->count();
        if ($detectionStagesCount == 0) {
            Categories::where('enable_detection_stage', true)
                ->update(['enable_detection_stage' => false]);
        }
        return redirect('/admin/viewTypes');
    }
    public function updateDetectionStage($detectionStageId,Request $request){
        $detectionStageName = $request->input('editDetectionStageName');
        $found = CorrectiveDetectionStage::where('name', $detectionStageName)->where('id','!=',$detectionStageId)->first();
        $error = $found ? 'Detection Stage already exists' : '';
        $userId      = User::where('email', Auth::user()->email)->value('id');
        if ($error === '') {
            DB::transaction(function () use ($detectionStageName, $userId,$detectionStageId) {
                CorrectiveDetectionStage::where('id',$detectionStageId)->update([
                    'name' => $detectionStageName,
                    'modified_by' => $userId
                ]);
                //inserting data to activities table
                $activity = 'Detection Stage is Updated with Name as ' . $detectionStageName;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'date' => $date->format('Y-m-d'),
                    'time' => $date->format('H:i:s'),
                    'activity' => $activity,
                    'type' => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                ViewActivity::insert($datas);
            });
        }

        return [
            'data' => null,
            'error' => $error,
        ];
    }
    public function checkEditDetectionStage(Request $request){
        $detectionStageName = $request->input('stage_name');
        $stage_id = $request->input('stage_id');
        $exists = CorrectiveDetectionStage::where('name', $detectionStageName)->where('id','!=',$stage_id)->first();
        if ($exists) {
            return response()->json(['exists' => true, 'msg' => 'This detection stage name is already selected. Please choose a different one.']);
        } else {
            return response()->json(['exists' => false]);
        }
    }
    public function enableDetectionStage(EnableDetectionStageRequest $request)
    {
        $data = $request->validated();
        $id = $data['id'];
        $is_checked = $data['is_checked'];
        Categories::where('id', $id)->update([
            'enable_detection_stage' => $is_checked=="true" ? true : false,
        ]);

        return [
            'data' => null,
            'error' => null,
        ];
    }
    public function updateDetectionStageLabel(Request $request)
    {
        if(!$request->id){
            DetectionStageLabelModel::create([
                'name' => $request->name
            ]);
            return response()->json(['success' => true]);
        }
        $stage = DetectionStageLabelModel::find($request->id);
        if (!$stage) {
            return response()->json(['success' => false]);
        }
        $stage->name = $request->name;
        $stage->save();
        return response()->json(['success' => true]);
    }
    //Corrective Detection Stage End
    public function nearMissQr(){
        $models = DB::table('modenadisable')->First();
        $result = Builder::create()->writer(new SvgWriter())
        ->data(url('/public/nearmiss/report'))->size(150)
        ->margin(10)->build();

        return view('admin.nearmiss-qr')->with(
            [
                'model' => $models,
                'qrCode' => $result->getString()
            ]
        );
    }

    public function downloadPDF()
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data(url('/public/nearmiss/report'))
            ->size(150)
            ->margin(10)
            ->build();
        $url = url('/public/nearmiss/report');
        $path = storage_path('app/public/guest-near-miss.png');

        file_put_contents($path, $result->getString());

        $pdf = \PDF::loadView('admin.nearmiss_qr_pdf', [
            'image' => $path,
            'url' => $url
        ]);

        return $pdf->download('Guest-Near-Miss-QR.pdf');
    }
}
