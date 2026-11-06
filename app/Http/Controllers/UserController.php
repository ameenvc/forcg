<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;

use App;
use App\Models\AppConfiguration;
use App\Audit;
use App\Calibration_instrument;
use App\Compliance;
use App\Corrective;
use App\Departments;
use App\Dochistory;
use App\Enviaspect;
use App\HseIssue;
use App\Issue;
use App\ModenaDisable;
// use App\Representative;
use App\Nearmiss;
use App\Objective;
use App\Ohs;
use App\Ohsrisk;
use App\Opportunity;
use App\Risk;
use App\Type;
use App\User;
use App\Admin;
use App\CorrectiveInvestigator;
use App\UserGate;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PDF;
use Session;
use App\ModuleActivity;
use App\Issuereviewer;

class UserController extends Controller
{
    /**
     * Variable for the Pending Actions
     *
     */
    public $pendingActionIconPath = 'images/icons/pending_action.png';
    public $pendingReviewIconPath = 'images/icons/pending_reviews.png';
    public $pendingApprovalIconPath = 'images/icons/pending_approval.png';
    public $pendingActionBorderColorCode = '#25499c';
    public $pendingReviewBorderColorCode = '#ee9f27';
    public $pendingApprovalBorderColorCode = '#F9A825';
    public $pendingActionTaskType = 'Pending Action';
    public $pendingReviewTaskType = 'Pending Review';
    public $pendingApprovalTaskType = 'Pending Approval';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web,admin,superadmin');
        $this->middleware('mfa')->except(['logout']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // public function index()
    // {
    //     return view('user.userhome');
    // }
    public function userdashboardindex(\App\Services\OrganizationDashboardService $service)
    {
        session(['type' => Auth::user()->type]);
        $model = Schema::hasTable('modenadisable') ? ModenaDisable::first() : null;
        $enabled = [];
        foreach ((array) ($model ? $model->getAttributes() : []) as $key => $value) if ((string) $value === '1') $enabled[] = $key;
        $dashboard = $service->snapshot($enabled);
        $company = Schema::hasTable('client_details') && Schema::hasColumn('client_details','company_name') ? DB::table('client_details')->value('company_name') : null;
        return response()->view('user.userdashboard', compact('model','dashboard','company'))->header('Cache-Control','private, no-store');
    }

    public function scoring(Request $request, \App\Services\OrganizationEsgService $service)
    {
        $model = ModenaDisable::first();
        $enabled = [];
        foreach ((array) ($model ? $model->getAttributes() : []) as $key => $value) if ((string) $value === '1') $enabled[] = $key;
        $scorecard = $service->snapshot($enabled);
        if ($request->query('export') === 'csv') {
            return response()->streamDownload(function () use ($scorecard) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Internal ESG readiness', 'Model '.$scorecard['version'], $scorecard['at'], $scorecard['status']]);
                fputcsv($out, ['Evidence-adjusted score', $scorecard['score'], 'Coverage %', $scorecard['coverage'], 'Self-reported; not independently verified']);
                fputcsv($out, ['Pillar', 'Indicator', 'Weight', 'Satisfactory records', 'Eligible records', 'Score /100', 'Evidence status']);
                foreach ($scorecard['indicators'] as $item) fputcsv($out, [$item['pillar'],$item['label'],$item['weight'],$item['passed'],$item['total'],$item['score'],$item['state']]);
                fclose($out);
            }, 'organization-esg-readiness.csv', ['Content-Type'=>'text/csv', 'Cache-Control'=>'private, no-store']);
        }
        return response()->view('user.userscore', compact('model', 'scorecard'))->header('Cache-Control', 'private, no-store');
    }
    public function index()
    {
        $type = Auth::user()->type;
        session(['type' => $type]);
        $models = DB::table('modenadisable')->First();
        $activeModule = [];
        $modules = [];
        foreach ($models as $column => $value) {
            if ($column != "id" && $column != "created_at" && $column != "updated_at") {
                $modules[] = $column;

                if ($value == 1) {
                    $activeModule[] = $column;
                }
            }
        }

        $i = 0;
        $user = User::where('email', FacadesAuth::user()->email)->firstOrFail();
        $user_id = $user->id;

        $enable_all = UserGate::where('users_id', $user_id)->where('key', 'enable-all')->first();
        $objective_found = UserGate::where('users_id', $user_id)->where('key', 'objective-all-pages')->first();
        $audit_found = UserGate::where('users_id', $user_id)->where('key', 'audit-all-pages')->first();
        $management_found = UserGate::where('users_id', $user_id)->where('key', 'management-all-pages')->first();
        $risk_found = UserGate::where('users_id', $user_id)->where('key', 'risk-all-pages')->first();
        $compliance_found = UserGate::where('users_id', $user_id)->where('key', 'compliance-all-pages')->first();
        $document_found = UserGate::where('users_id', $user_id)->where('key', 'document-all-pages')->first();
        $opportunity_found = UserGate::where('users_id', $user_id)->where('key', 'opportunity-all-pages')->first();
        $corrective_found = UserGate::where('users_id', $user_id)->where('key', 'corrective-all-pages')->first();
        $ohsrisk_found = UserGate::where('users_id', $user_id)->where('key', 'hsr-all-pages')->first();
        $envi_found = UserGate::where('users_id', $user_id)->where('key', 'envi-all-pages')->first();
        $elearning_found = UserGate::where('users_id', $user_id)->where('key', 'elearning-all-pages')->first();
        $complaint_found = UserGate::where('users_id', $user_id)->where('key', 'complaint-all-pages')->first();
        $vendor_found = UserGate::where('users_id', $user_id)->where('key', 'vendor-all-pages')->first();
        $incident_found = UserGate::where('users_id', $user_id)->where('key', 'incident-all-pages')->first();
        $hr_found = UserGate::where('users_id', $user_id)->where('key', 'hr-all-pages')->first();
        $maintenence_found = UserGate::where('users_id', $user_id)->where('key', 'maintenance-all-pages')->first();
        $hse_found = UserGate::where('users_id', $user_id)->where('key', 'hse-all-pages')->first();
        $calibration_found = UserGate::where('users_id', $user_id)->where('key', 'calibration-all-pages')->first();
        $occhealth_found = UserGate::where('users_id', $user_id)->where('key', 'occhealth-all-pages')->first();
        $waste_found = UserGate::where('users_id', $user_id)->where('key', 'waste-all-pages')->first();
        $nearmiss_found = UserGate::where('users_id', $user_id)->where('key', 'nearmiss-all-pages')->first();
        $context_found = UserGate::where('users_id', $user_id)->where('key', 'context-all-pages')->first();
        $customer_found = UserGate::where('users_id', $user_id)->where('key', 'customer-all-pages')->first();
        $emergency_found = UserGate::where('users_id', $user_id)->where('key', 'emergency-all-pages')->first();

        foreach ($modules as $module) {
            $isActive = in_array($module, $activeModule);
            switch ($module) {
                case 'auditmanagement':
                    $module_arr[$module] = [
                        ($audit_found || $enable_all) && $isActive ? '#4A148C3D' : '#ccc',
                        'fact_check.png',
                        'Audit Management',
                        'Internal Audit Scheduling & Reporting',
                        ($audit_found || $enable_all) && $isActive ? 'action.viewlist' : '',
                        (!$audit_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'governance_compliance'
                    ];
                    break;
                case 'managementreview':
                    $module_arr[$module] = [
                        ($management_found || $enable_all) && $isActive ? '#6A1B9A3D' : '#ccc',
                        'analytics.png', 'Management Review',
                        'Management Review in Minutes',
                        ($management_found || $enable_all) && $isActive ? 'management.meetings' : '',
                        (!$management_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'governance_compliance'
                    ];
                    break;
                case 'riskmanagement':
                    $module_arr[$module] = [
                        ($risk_found || $enable_all) && $isActive ? '#B71C1C3D' : '#ccc',
                        'warning.png',
                        'Enterprise Risk',
                        'Risk Assessment & Identification',
                        ($risk_found || $enable_all) && $isActive ? 'risk.index' : '',
                        (!$risk_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'governance_compliance'
                    ];
                    break;
                case 'compliance':
                    $module_arr[$module] = [
                        ($compliance_found || $enable_all) && $isActive ? '#0D47A13D' : '#ccc',
                        'verified_user.png',
                        'Compliance Management',
                        'Identification of Legal Requirements',
                        ($compliance_found || $enable_all) && $isActive ? 'compliance.register' : '',
                        (!$compliance_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'governance_compliance'
                    ];
                    break;
                case 'documentmanagement':
                    $module_arr[$module] = [
                        ($document_found || $enable_all) && $isActive ? '#00695C3D' : '#ccc',
                        'folder.png',
                        'Document Management',
                        'Governance Hub',
                        ($document_found || $enable_all) && $isActive ? 'doc.governancehub' : '',
                        (!$document_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'governance_compliance'
                    ];
                    break;
                case 'objectives':
                    $module_arr[$module] = [
                        ($objective_found || $enable_all) && $isActive ? '#1E3A8A3D' : '#ccc',
                        'flag.png',
                        'Objectives',
                        'Objectives & Performance Reporting',
                        ($objective_found || $enable_all) && $isActive ? 'user.objectives.folders' : '',
                        (!$objective_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'governance_compliance'];
                    break;
                case 'opportunity':
                    $module_arr[$module] = [
                        ($opportunity_found || $enable_all) && $isActive ? '#F9A8253D' : '#ccc',
                        'trending_up_org.png',
                        'Opportunity',
                        'Opportunity Action Plan & Identification',
                        ($opportunity_found || $enable_all) && $isActive ? 'opportunity.oppindex' : '',
                        (!$opportunity_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'governance_compliance'
                    ];
                    break;
                case 'correctiveaction':
                    $module_arr[$module] = [
                        ($corrective_found || $enable_all) && $isActive ? '#33691E3D' : '#ccc',
                        'settings_backup_restore.png',
                        'NCR & Corrective Actions',
                        'Actions, Register & Open/Closed Issues',
                        ($corrective_found || $enable_all) && $isActive ? 'corrective.viewlist' : '',
                        (!$corrective_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'quality'
                    ];
                    break;
                case 'ohsrisk':
                    $module_arr[$module] = [
                        ($ohsrisk_found || $enable_all) && $isActive ? '#D32F2F3D' : '#ccc',
                        'health_and_safety.png',
                        'Health & Safety Risks',
                        'Occupational Health & Safety Risks',
                        ($ohsrisk_found || $enable_all) && $isActive ? 'ohsrisk.folder' : '',
                        (!$ohsrisk_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'hse_sustainability'
                    ];
                    break;
                case 'enviaspects':
                    $module_arr[$module] = [
                        ($envi_found || $enable_all) && $isActive ? '#1B5E203D' : '#ccc',
                        'nest_eco_leaf.png',
                        'Environmental Aspects',
                        'Env. Aspect & Impact Identification',
                        ($envi_found || $enable_all) && $isActive ? 'enviaspect.folder' : '',
                        (!$envi_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'hse_sustainability'
                    ];
                    break;
                case 'contextmanagement':
                    $module_arr[$module] = [
                        ($context_found || $enable_all) && $isActive ? '#0288D13D' : '#ccc',
                        'notes.png',
                        'Context Management',
                        'Context & Change Management',
                        ($context_found || $enable_all) && $isActive ? 'user.context.index' : '',
                        (!$context_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'governance_compliance'
                    ];
                    break;
                case 'customer_satisfaction':
                    $module_arr[$module] = [
                        ($customer_found || $enable_all) && $isActive ? '#2E7D323D' : '#ccc',
                        'thumb_up.png',
                        'Customer Satisfaction',
                        'Customers List & Survey Analysis',
                        ($customer_found || $enable_all) && $isActive ? 'customer.register' : '',
                        (!$customer_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'quality'
                    ];
                    break;
                case 'elearning':
                    $module_arr[$module] = [
                        ($elearning_found || $enable_all) && $isActive ? '#3949AB3D' : '#ccc',
                        'school.png',
                        'e-Learning',
                        'E-Learning',
                        ($elearning_found || $enable_all) && $isActive ? 'elearning.courses' : '',
                        (!$elearning_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'people_culture'
                    ];
                    break;
                case 'complaintmanagement':
                    $module_arr[$module] = [
                        ($complaint_found || $enable_all) && $isActive ? '#FF70433D' : '#ccc',
                        'support_agent.png',
                        'Customer Complaint',
                        'Customer List, My Complaints, Closed',
                        ($complaint_found || $enable_all) && $isActive ? 'user.compliancemgmt' : '',
                        (!$complaint_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'quality'
                    ];
                    break;
                case 'vendormanagement':
                    $module_arr[$module] = [
                        ($vendor_found || $enable_all) && $isActive ? '#004D403D' : '#ccc',
                        'store.png',
                        'Vendor Management',
                        'Vendor Register & Evaluation',
                        ($vendor_found || $enable_all) && $isActive ? 'vendor.index' : '',
                        (!$vendor_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'operations'
                    ];
                    break;
                case 'incidentreport':
                    $module_arr[$module] = [
                        ($incident_found || $enable_all) && $isActive ? '#E651003D' : '#ccc',
                        'report.png',
                        'Incident Reporting',
                        'Incident Reporting & Identification',
                        ($incident_found || $enable_all) && $isActive ? 'incidentregister' : '',
                        (!$incident_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'hse_sustainability'
                    ];
                    break;
                case 'hr':
                    $module_arr[$module] = [
                        ($hr_found || $enable_all) && $isActive ? '#303F9F3D' : '#ccc',
                        'groups.png',
                        'Human Resource',
                        'Job Description & Training Schedule',
                        ($hr_found || $enable_all) && $isActive ? 'hr.register' : '',
                        (!$hr_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'people_culture'
                    ];
                    break;
                case 'maintainance':
                    $module_arr[$module] = [
                        ($maintenence_found || $enable_all) && $isActive ? '#2E7D323D' : '#ccc',
                        'build.png',
                        'Maintenance',
                        'Maintenance Reporting & Scheduling',
                        ($maintenence_found || $enable_all) && $isActive ? 'maintenance.total' : '',
                        (!$maintenence_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'operations'
                    ];
                    break;
                case 'hse':
                    $module_arr[$module] = [
                        ($hse_found || $enable_all) && $isActive ? '#FF8F003D' : '#ccc',
                        'visibility_org.png',
                        'HSE Observations',
                        'HSE Observation & Corrective Action',
                        ($hse_found || $enable_all) && $isActive ? 'hseobservation.index' : '',
                        (!$hse_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'hse_sustainability'
                    ];
                    break;
                case 'emergencyresponse':
                    $module_arr[$module] = [
                        ($emergency_found || $enable_all) && $isActive ? '#C628283D' : '#ccc',
                        'fire_extinguisher.png',
                        'Emergency Response',
                        'Emergency Reporting & Actions',
                        ($emergency_found || $enable_all) && $isActive ? 'emergency.register' : '',
                        (!$emergency_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'hse_sustainability'
                    ];
                    break;
                case 'calibration':
                    $module_arr[$module] = [
                        ($calibration_found || $enable_all) && $isActive ? '#8277173D' : '#ccc',
                        'tune.png',
                        'Calibration Management',
                        'Instrument Log & Calibration Reporting',
                        ($calibration_found || $enable_all) && $isActive ? 'calibration.index' : '',
                        (!$calibration_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'operations'
                    ];
                    break;
                case 'occhealth':
                    $module_arr[$module] = [
                        ($occhealth_found || $enable_all) && $isActive ? '#D843153D' : '#ccc',
                        'local_hospital.png',
                        'Occupational Health',
                        'Industrial Clinic Management',
                        ($occhealth_found || $enable_all) && $isActive ? 'employeehealthrecords' : '',
                        (!$occhealth_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'hse_sustainability'
                    ];
                    break;
                case 'wastemanagement':
                    $module_arr[$module] = [
                        ($waste_found || $enable_all) && $isActive ? '#558B2F3D' : '#ccc',
                        'delete_sweep.png',
                        'Waste Management',
                        'Waste Reporting & Review Approval',
                        ($waste_found || $enable_all) && $isActive ? 'waste.register' : '',
                        (!$waste_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'hse_sustainability'
                    ];
                    break;
                case 'nearmiss':
                    $module_arr[$module] = [
                        ($nearmiss_found || $enable_all) && $isActive ? '#D32F2F3D' : '#ccc',
                        'priority_high.png',
                        'Near Miss',
                        'Near Miss Reporting & Action',
                        ($nearmiss_found || $enable_all) && $isActive ? 'nearmiss.index' : '',
                        (!$nearmiss_found && !$enable_all) && !$isActive ? 'disabled-color-text' : '',
                        'hse_sustainability'
                    ];
                    break;
            }

            $i++;
        }

        return view('user.userhome')->with([
            'model' => $models,
            'modules' => $module_arr,
        ]);
    }
    public function actiondashboard()
    {
        return view('user.action.dashboard');
    }

    //Display UserListing in user dashboard in user login

    public function viewusers()
    {
        $models = DB::table('modenadisable')->First();
        $users = DB::select('select * from users where approved = 1 order by name ASC');

        return view('user.displayusers')->with('users', $users)->with('model', $models);
    }

    //Issue Provision to change User Login Password

    public function changePassword(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        return redirect()->back()->with("success", "Password changed successfully !");
    }

    public function usermessage($id)
    {
        $user = User::find($id);
        DB::table('chats')->where('reciever_id', '=', Auth::user()->id)->where('sender_id', '=', $id)->update(['read_status' => 1]);
        $recmsg = DB::table('chats')->where('reciever_id', '=', Auth::user()->id)->get();
        $users = User::where('id', '!=', Auth::user()->id)->where('approved', 1)->get();
        $msgs = DB::table('chats')->where(function ($query) use ($id) {
            $query->where('sender_id', '=', Auth::user()->id)
                ->where('reciever_id', '=', $id)->where('vistosender', '=', 1);
        })->orWhere(function ($query) use ($id) {
            $query->where('sender_id', '=', $id)->where('reciever_id', '=', Auth::user()->id)->where('vistoreciever', '=', 1);
        })->get();

        return view('layouts/app')->with('msg', $msgs)->with('use', $user)->with('users', $users)->with('rec', $recmsg);
    }

    public function sendmsg(Request $request)
    {

        if ($request->hasFile('image')) {
            $this->validate($request, [
                'image' => 'image|mimes:jpeg,jpg,png,gif,jfif',
            ]);
            $image = $request->file('image');
            $filename = basename($image->getClientOriginalName(), '.' . $image->getClientOriginalExtension());
            $file_size = $image->getClientOriginalName();
            $ext = $image->getClientOriginalExtension();
            $datetime = Carbon::now()->toDateTimeString();
            $datetime = Carbon::parse($datetime);
            $date = $datetime->format('d-m-Y');
            $fname = $filename . '_' . $date . '.' . $ext;
            $path = public_path('storage/chat_files');
            $image->move($path, $fname);
            $data = array('message' => $fname, 'sender_id' => Auth::user()->id, 'reciever_id' => $request->userid, 'time' => $datetime->format('g:i A'), 'date' => $date, 'vistosender' => 1, 'vistoreciever' => 1, 'img' => 1);
            DB::table('chats')->insert($data);
        }

        if ($request->message != "") {

            $datetime = Carbon::now()->toDateTimeString();
            $datetime = Carbon::parse($datetime);
            //dd($datetime->format('g:i A'));
            $data = array('message' => $request->message, 'sender_id' => Auth::user()->id, 'reciever_id' => $request->userid, 'time' => $datetime->format('g:i A'), 'date' => $datetime->format('d-m-Y'), 'vistosender' => 1, 'vistoreciever' => 1);
            DB::table('chats')->insert($data);
        }

        return $this->usermessage($request->userid);
    }

    public function deletemsg($id)
    {

        $msgs = DB::table('chats')->where(function ($query) use ($id) {
            $query->where('sender_id', '=', Auth::user()->id)
                ->where('reciever_id', '=', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('sender_id', '=', $id)->where('reciever_id', '=', Auth::user()->id);
        })->get();

        foreach ($msgs as $msg) {
            if ($msg->sender_id == Auth::user()->id) {
                if ($msg->vistoreciever == 1) {
                    DB::table('chats')->where('id', $msg->id)->update(['vistosender' => 0]);
                } else {
                    if ($msg->img == 1) {
                        $path = 'storage/chat_files';
                        File::delete($path . $msg->message);
                        DB::table('chats')->where('id', $msg->id)->delete();
                    } else {
                        DB::table('chats')->where('id', $msg->id)->delete();
                    }
                }
            } else if ($msg->reciever_id == Auth::user()->id) {
                if ($msg->vistosender == 1) {
                    DB::table('chats')->where('id', $msg->id)->update(['vistoreciever' => 0]);
                } else {
                    if ($msg->img == 1) {
                        $path = 'storage/chat_files';
                        File::delete($path . $msg->message);
                        DB::table('chats')->where('id', $msg->id)->delete();
                    } else {
                        DB::table('chats')->where('id', $msg->id)->delete();
                    }
                }
            }
        }
        return $this->usermessage($id);
    }

    //    public function convertpdf(Request $request,$tname)
    //     {

    //         $tvalue=$request->get($tname);
    //         $pdf=App::make('dompdf.wrapper');
    //         $pdf->loadHTML($tvalue);
    //         $name='pdfsavedby_'.Auth::user()->name.'_'.'.pdf';
    //         return $pdf->setPaper('a4')->download($name);

    //     }
    public function convertpdf(Request $request, $tname)
    {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        ini_set('memory_limit', '-1');
        $paper = $request->get('paper');
        $orientation = $request->get('orientation');
        $tvalue = $request->get($tname);

        $name = 'pdfsavedby_' . Auth::user()->name . '_' . '.pdf';

        return PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/'),
        ])->loadHTML($tvalue)
            ->setPaper($paper ? $paper : 'a4', $orientation ? $orientation : 'portrait')
            ->setWarnings(false)
            ->download($name);
    }

    public function logout()
    {

        $logindevices = DB::table('users')->where('id', Auth::user()->id)->pluck('logdevices')->First();
        $logindevices = $logindevices - 1;
        if ($logindevices <= 0) {
            $logindevices = 0;
        }
        DB::table('users')->where('id', Auth::user()->id)->update(['logdevices' => $logindevices]);
        $admin = DB::table('admins')->where('email', Auth::user()->email)->get();
        if (!$admin->isEmpty()) {
            DB::table('admins')->where('email', Auth::user()->email)->update(['logdevices' => $logindevices]);
        }

        Auth::guard('web')->logout();
        Session::flush();
        return redirect('/');
    }

    public function changePasswordpage()
    {
        $models = DB::table('modenadisable')->First();
        return view('auth/passwords/changepassword')->with('model', $models);
    }
    public function profile()
    {
        $models = DB::table('modenadisable')->First();
        // $users = DB::table('users')->where('employeeid', '=', Auth::user()->employeeid)->get();
        $user_id = User::where('email',Auth::user()->email)->value('id');
        $users = DB::table('users')->where('id',$user_id)->get();
        $mfa = AppConfiguration::where('meta_key', 'enable_mfa')->first();
        return view('/user/update_profile', compact('mfa'))->with('users', $users)->with('model', $models);
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
            $admins = DB::table('admins')->get();
            //  Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename) );
            foreach ($admins as $admin) {
                if (Auth::user()->employeeid == $admin->employeeid) {
                    DB::update('update admins set profile_picture = :eprofile_picture  where employeeid = :eid', ['eprofile_picture' => $filename, 'eid' => $id]);
                }
            }

            DB::update('update users set profile_picture = :eprofile_picture  where employeeid = :eid', ['eprofile_picture' => $filename, 'eid' => $id]);
        }

        $users = DB::table('users')->where('employeeid', '=', Auth::user()->employeeid)->get();
        // $users->update(['profile_picture' => $filename]);
        session()->flash('msg', 'Profile Updated Successfully');

        return redirect()->route('user.profile');
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
        } else {
                $id = User::where('email',Auth::user()->email)->value('id');
                if ($request->hasfile('profile_pic')) {
                    $avatar = $request->file('profile_pic');
                    $filename = $id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
                    $destination_path = public_path('storage/profile');
                    $avatar->move($destination_path, $filename);
                    User::where('id',$id)->update(['profile_picture'=>$filename]);
                    Admin::where('email',Auth::user()->email)->update(['profile_picture' => $filename]);
                }
                session()->flash('msg', 'Profile Updated Successfully');
                return redirect()->route('user.profile');
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
            return redirect()->route('user.profile');
    }
    public function ajaxShowAllUsers()
    {
        $users = User::select('id', 'name')->where('is_email_verified', 1)->where('approved', 1)->get();
        return [
            'data' => $users,
            'error' => null,
        ];
    }
    public function myBoard(Request $request, \App\Services\MyBoard\MyBoardService $service)
    {
        $account = FacadesAuth::user();
        $user = User::where('email', $account->email)->firstOrFail();
        $model = DB::table('modenadisable')->first();
        $settings = config('my_board') ?: require config_path('my_board.php');
        $active = array_values(array_filter(array_keys($settings['modules']), function ($key) use ($model) {
            return !$model || !isset($model->$key) || (bool) $model->$key;
        }));
        $board = $service->snapshot($user, $active);
        if ($request->expectsJson()) return response()->json($board)->header('Cache-Control', 'private, no-store');
        return response()->view('user.myboard', compact('model', 'user', 'board'))->header('Cache-Control', 'private, no-store');
    }
    public function correctiveAction(object $user, object $data)
    {
        $finalOutput = [];
        $issueReviewers = Issuereviewer::pluck('email')->toArray();
        $teamMemberCorrIds = CorrectiveInvestigator::where('user_id',$user->id)->pluck('corrective_id')->toArray();
        foreach ($data as $value) {
            $correctionResponsibilityFlag = $value->correction_responsibility ? in_array($user->email,explode(',',$value->correction_responsibility)) : false;
            $RCResponsibilityFlag = $value->responsibility ? in_array($user->email,explode(',',$value->responsibility)) : false;
            $teamLeaderFlag = $value->team_lead_id == $user->id;
            $teamMemberFlag = in_array($value->id, $teamMemberCorrIds);
            $issueReviewersFlag = in_array($user->email, $issueReviewers);
            $equalTimeFlag = Carbon::parse($value->created_at)->eq(Carbon::parse($value->updated_at));
            $notEqualTimeFlag = Carbon::parse($value->created_at)->ne(Carbon::parse($value->updated_at));
            $targetDateArray = $value->target_date ? explode(',',$value->target_date) : [];
            $RCTargetDate = 'dd/mm/yyyy';
            $RCArray = $value->responsibility ? explode(',',$value->responsibility) : [];
            $investigationDate = $value->investigation_date ? date('d/m/Y',strtotime($value->investigation_date)) : 'dd/mm/yyyy';
            $correctionDate = $value->correction_target_date ? date('d/m/Y',strtotime($value->correction_target_date)) : 'dd/mm/yyyy';
            $time = $value->updated_at ? Carbon::parse($value->updated_at)->diffForHumans() : '';
            if(count($targetDateArray) > 0 && (count($targetDateArray)==count($RCArray))){
                for($i=0;$i<count($targetDateArray);$i++){
                    if(strcasecmp($RCArray[$i], $user->email) == 0){
                        $RCTargetDate = $targetDateArray[$i] ? date('d/m/Y',strtotime($targetDateArray[$i])) : 'dd/mm/yyyy';
                        break;
                    }
                }
            }
            if($value->processstage == 'Awaiting actions' && ($value->is_reopened==false) && $correctionResponsibilityFlag) {
                $finalOutput[] = [
                    'message' => "An issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> requires your action.",
                    'time' => $time,
                    'redirectPage' => "corrective/proposeaction?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $correctionDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Awaiting actions' && ($value->is_reopened == false) && ($teamLeaderFlag)) {
                $finalOutput[] = [
                    'message' => "An issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> requires your investigation.",
                    'time' => $time,
                    'redirectPage' => "corrective/corrissues/$value->id/detail",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $investigationDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Awaiting actions' && ($value->is_reopened == false) && ($teamMemberFlag)) {
                $finalOutput[] = [
                    'message' => "An issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> requires your investigation.",
                    'time' => $time,
                    'redirectPage' => "corrective/corrissues/$value->id/detail",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $investigationDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Awaiting actions' && ($value->is_reopened==true) && $correctionResponsibilityFlag) {
                $finalOutput[] = [
                    'message' => "Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> has been reopened and requires your action.",
                    'time' => $time,
                    'redirectPage' => "corrective/proposeaction?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $correctionDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Awaiting actions' && ($value->is_reopened==true) && ($teamLeaderFlag)) {
                $finalOutput[] = [
                    'message' => "Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> has been reopened and requires your investigation.",
                    'time' => $time,
                    'redirectPage' => "corrective/corrissues/$value->id/detail",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $investigationDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Awaiting actions' && ($value->is_reopened==true) && ($teamMemberFlag)) {
                $finalOutput[] = [
                    'message' => "Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> has been reopened and requires your investigation.",
                    'time' => $time,
                    'redirectPage' => "corrective/corrissues/$value->id/detail",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $investigationDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Entry Under Review' && $issueReviewersFlag && $equalTimeFlag) {
                $finalOutput[] = [
                    'message' => "An new issue <b style='color:#25499c;'>$value->issueid</b> is pending review in <u>NCR & Corrective Actions</u>.",
                    'time' => $time,
                    'redirectPage' => "corrective/approve?val=$value->id",
                    'borderColorCode' => $this->pendingReviewBorderColorCode,
                    'dueDate' => 'dd/mm/yyyy',
                    'taskType' => $this->pendingReviewTaskType,
                    'iconPath' => asset($this->pendingReviewIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'rejected' && $value->postedby == $user->employeeid) {
                $finalOutput[] = [
                    'message' => "Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> has been rejected. Please review and take action.",
                    'time' => $time,
                    'redirectPage' => "corrective/resubmit?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $correctionDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Entry Under Review' && $issueReviewersFlag && $notEqualTimeFlag) {
                $finalOutput[] = [
                    'message' => "A resubmitted issue <b style='color:#25499c;'>$value->issueid</b> is pending review in <u>NCR & Corrective Actions</u>.",
                    'time' => $time,
                    'redirectPage' => "corrective/approve?val=$value->id",
                    'borderColorCode' => $this->pendingReviewBorderColorCode,
                    'dueDate' => 'dd/mm/yyyy',
                    'taskType' => $this->pendingReviewTaskType,
                    'iconPath' => asset($this->pendingReviewIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'RC Updated- Correction Awaiting' && $RCResponsibilityFlag) {
                $finalOutput[] = [
                    'message' => "An issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> requires your action for the identified Rootcause.",
                    'time' => $time,
                    'redirectPage' => "corrective/proposeaction?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $RCTargetDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Correction Proposed- RC Awaiting' && $correctionResponsibilityFlag && $value->team_lead_id==0) {
                $finalOutput[] = [
                    'message' => "Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> requires Rootcause analysis.",
                    'time' => $time,
                    'redirectPage' => "corrective/editrespondedissue?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $correctionDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Actions Proposed- Review Awaiting' && $RCResponsibilityFlag) {
                $finalOutput[] = [
                    'message' => "An issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> requires your action for the identified Rootcause.",
                    'time' => $time,
                    'redirectPage' => "corrective/proposeaction?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $RCTargetDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Actions Proposed- Review Awaiting' && $issueReviewersFlag) {
                $finalOutput[] = [
                    'message' => "Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> requires review for the proposed action.",
                    'time' => $time,
                    'redirectPage' => "corrective/reviewstatus?val=$value->id",
                    'borderColorCode' => $this->pendingReviewBorderColorCode,
                    'dueDate' => 'dd/mm/yyyy',
                    'taskType' => $this->pendingReviewTaskType,
                    'iconPath' => asset($this->pendingReviewIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Action Reviewed, OK' && $correctionResponsibilityFlag) {
                $finalOutput[] = [
                    'message' => "Action proposed for Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> is approved; please proceed.",
                    'time' => $time,
                    'redirectPage' => "corrective/update_percentage/$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $correctionDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Action Reviewed, OK' && $RCResponsibilityFlag) {
                $finalOutput[] = [
                    'message' => "Action proposed for Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> is approved; please proceed.",
                    'time' => $time,
                    'redirectPage' => "corrective/update_percentage/$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $RCTargetDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Action Reviewed, Not OK' && $correctionResponsibilityFlag) {
                $finalOutput[] = [
                    'message' => "Action proposed for Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> is rejected; please update as needed.",
                    'time' => $time,
                    'redirectPage' => "corrective/editrespondedissue?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $correctionDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Action Reviewed, Not OK' && $RCResponsibilityFlag) {
                $finalOutput[] = [
                    'message' => "Action proposed for Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> is rejected; please update as needed.",
                    'time' => $time,
                    'redirectPage' => "corrective/editrespondedissue?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $RCTargetDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Actions Completed' && $issueReviewersFlag) {
                $finalOutput[] = [
                    'message' => "Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> is 100% completed and requires final review.",
                    'time' => $time,
                    'redirectPage' => "corrective/closure?val=$value->id",
                    'borderColorCode' => $this->pendingReviewBorderColorCode,
                    'dueDate' => 'dd/mm/yyyy',
                    'taskType' => $this->pendingReviewTaskType,
                    'iconPath' => asset($this->pendingReviewIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Need More Action' && $RCResponsibilityFlag) {
                $finalOutput[] = [
                    'message' => "Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> needs your more action for closure review.",
                    'time' => $time,
                    'redirectPage' => "corrective/editrespondedissue?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $RCTargetDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if ($value->processstage == 'Need More Action' && $correctionResponsibilityFlag) {
                $finalOutput[] = [
                    'message' => "Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> needs your more action for closure review.",
                    'time' => $time,
                    'redirectPage' => "corrective/editrespondedissue?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $correctionDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name'] ?? "",
                    'priorityColor' => $value->typ['text_color'] ?? '',
                    'updated_at' => $value->updated_at
                ];
            }
            if($value->is_responsible_person_changed && $correctionResponsibilityFlag){
                $finalOutput[] = [
                    'message' => "Issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> has been reassigned to you.",
                    'time' => $time,
                    'redirectPage' => "corrective/proposeaction?val=$value->id",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $correctionDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name']??"",
                    'priorityColor' => $value->typ['text_color']??'',
                    'updated_at' => $value->updated_at
                ];
            }
            if($value->is_team_changed && $teamLeaderFlag){
                $finalOutput[] = [
                    'message' => "Investigation for issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> has been reassigned to you.",
                    'time' => $time,
                    'redirectPage' => "corrective/corrissues/$value->id/detail",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $investigationDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name']??"",
                    'priorityColor' => $value->typ['text_color']??'',
                    'updated_at' => $value->updated_at
                ];
            }
            if($value->is_team_changed && $teamMemberFlag){
                $finalOutput[] = [
                    'message' => "Investigation for issue <b style='color:#25499c;'>$value->issueid</b> in <u>NCR & Corrective Actions</u> has been reassigned to you.",
                    'time' => $time,
                    'redirectPage' => "corrective/corrissues/$value->id/detail",
                    'borderColorCode' => $this->pendingActionBorderColorCode,
                    'dueDate' => $investigationDate,
                    'taskType' => $this->pendingActionTaskType,
                    'iconPath' => asset($this->pendingActionIconPath),
                    'priorityType' => $value->typ['name']??"",
                    'priorityColor' => $value->typ['text_color']??'',
                    'updated_at' => $value->updated_at
                ];
            }
        }
        return $finalOutput;
    }
}
