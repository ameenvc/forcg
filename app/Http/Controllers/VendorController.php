<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Country;

// use Validator;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\AjaxEvalProcRequest;
use App\Http\Requests\Vendor\ApproveRequest;

// use App\Notifications;
use App\Http\Requests\Vendor\EditRequest;
use App\Http\Requests\Vendor\InsertRequest;
use App\Http\Requests\Vendor\RequestEvaluationRequest;
use App\Http\Requests\Vendor\ReviewRequest;
use App\Mail\RequestEvaluation;
use App\Mail\VendorReview;
//use App\Type;
use App\ModenaDisable;
use App\Standards;
use App\Supplierapprovers;
use App\User;
use App\Vendor;
use App\Vendordetails;
use App\VendorHistory;
use App\Vendorresult;
use App\VendorStandard;
use App\ViewActivity;
use Carbon\Carbon;
//use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('user.vendor.home');
    }

    //add vendor
    public function addvendor()
    {
        //$departments = Departments::all();
        $hods = DB::table('departments')->pluck('hod')->unique()->toArray();
        $sa = DB::table('supplierapprovers')->pluck('email');
        $certificateStandard = Standards::where('name', '!=', 'Not certified')->get();
        $countries = Country::all();

        // below code related to superadmin
        $models = DB::table('modenadisable')->first();

        return view('user.vendor.addvendor') /*->withDepartments($departments)*/
            ->withHods($hods)
            ->with('model', $models)
            ->with('certificateStandard', $certificateStandard)
            ->with('countries', $countries)->with('sa', $sa);
    }

    public function insertvendor(InsertRequest $req)
    {
        $vendorname = $req->input('vendorname');
        $vendorgroup = $req->input('vendorgroup');
        $vendorscope = $req->input('vendorscope');
        $vendoraddress = $req->input('vendoradd');
        $vendorloc = $req->input('vendorloc');
        $vendoremail = $req->input('vendoremail');
        $vendorcontact = $req->input('vendorcontact');
        $vendorbasis_add = $req->input('vendorbasis_add');
        if ($vendorbasis_add == null) {
            $chk = "null";
        } else {
            $checkbox1 = $_POST['vendorbasis_add'];
            $chk = "";
            foreach ($checkbox1 as $chk1) {
                $chk .= $chk1 . ",";
            }
        }
        $vendoriso = $req->input('vendor_iso');
        $vendoryoe = $req->input('vendor_yoe')!='0' ? $req->input('vendor_yoe') : null;
        $vendorcomments = $req->input('vendorcomments');
        $vendortrial = $req->input('vendor_trial');
        if ($vendortrial == "no") {
            $vendor_trial = "No";
        } else {
            $vendor_trial = "Yes";
        }
        $vendorapprove = $req->input('vendorappr');
        $vendorbasis_appr = $req->input('vendorbasis_appr');
        if ($vendorbasis_appr == null) {
            $chkappr = "null";
        } else {
            $checkbox1 = $_POST['vendorbasis_appr'];
            $chkappr = "";
            foreach ($checkbox1 as $chk1) {
                $chkappr .= $chk1 . ",";
            }
        }
        $vendortype = $req->input('vendortype');
        $vendoroutcome = $req->input('vendoroutcome');
        if ($vendortrial == "yes") {
            $vendorout = $vendoroutcome;

        } else {
            $vendorout = " ";
        }
        if ($vendorgroup == "Existing") {
            $chkbasis = " ";
            $vntrial = " ";
            $vnout = " ";
            $venchkappr = $chkappr;
        } else if ($vendorgroup == "New") {
            $chkbasis = $chk;
            $vntrial = $vendortrial;
            $vnout = $vendorout;
            $venchkappr = " ";
        }
        $vendoreval = $req->input('vendor_eval');
        $vendor_status = $vendorgroup == 'New' ? "under_review" : "approved";
        $created_date = date('d-m-Y h:i:s A', time());
        $posted_id = Auth::user()->employeeid;

        $c = date('Y-m-d');

        if ($vendoreval == "one_year") {
            $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(1);
        } else if ($vendoreval == "two_year") {
            $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(2);
        } else if ($vendoreval == "three_year") {
            $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(3);
        } else if ($vendoreval == "four_year") {
            $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(4);
        } else if ($vendoreval == "five_year") {
            $due = Carbon::createFromFormat('Y-m-d', $c)->addYear(5);
        } else if ($vendoreval == "quarterly") {
            $d = strtotime("+3 months", strtotime($c));
            $due = date("Y-m-d", $d);
        } else if ($vendoreval == "half_year") {
            $d = strtotime("+6 months", strtotime($c));
            $due = date("Y-m-d", $d);
        } else if ($vendoreval == "one_half_year") {
            $y = Carbon::createFromFormat('Y-m-d', $c)->addYear(1);
            $d = strtotime("+6 months", strtotime($y));
            $due = date("Y-m-d", $d);
        }

        $data = array('vendor_name' => $vendorname, 'vendor_group' => $vendorgroup, 'scope' => $vendorscope, 'country_id' => $vendoraddress,
                      'location' => $vendorloc, 'contact' => $vendorcontact, 'email' => $vendoremail, 'Basis_addition' => $chkbasis,
                      'Yoe' => $vendoryoe, 'comments' => $vendorcomments, 'comments_of_added_person' => $vendorcomments, 'trial_order' => $vntrial, 'approved_date' => $vendorapprove, 'basis_approval' => $venchkappr, 'vendor_type' => $vendortype, 'outcome' => $vntrial == 'yes' ? $vnout : null, 'eval' => $vendoreval, 'status' => $vendor_status,
                      'created_at' => $created_date, 'due_date' => $due, 'added_by' => $posted_id);
        $vendor = Vendor::create($data);
        if ($vendoriso) {
            foreach ($vendoriso as $v_iso) {
                $vendor_standard_data[] = [
                    'vendors_id' => $vendor->id,
                    'standards_id' => $v_iso,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            VendorStandard::insert($vendor_standard_data);
        }

        Session::flash('msg', "Vendor added successfully");
        //inserting values to activity table
        $activity = "Inserted new vendor with Name  " . $vendorname . " and with group as " . $vendorgroup;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //superadmin code ends
        return redirect()->back();

    }

    public function reviewvendor(int $id)
    {
        $vendor = Vendor::where('is_deleted', false)->findOrFail($id);
        $hods = DB::table('departments')->pluck('hod')->unique()->toArray();
        $sa = DB::table('supplierapprovers')->pluck('email');
        $vendor_standards_array = [];
        $isNotCertified = !count($vendor->standards);
        foreach ($vendor->standards as $s) {
            $vendor_standards_array[] = $s->id;
        }

        $certificateStandard = Standards::where('name', '!=', 'Not certified')->get();
        $basis_approvals = explode(',', $vendor->basis_approval);
        $basis_approvals = array_filter($basis_approvals);

        $basis_additions = explode(',', $vendor->Basis_addition);
        $basis_additions = array_filter($basis_additions);

        $countries = Country::all();

        // below code related to superadmin
        $models = DB::table('modenadisable')->first();

        //return view('user.vendor.review') /*->withDepartments($departments)*/->withHods($hods)->with('model', $models)->with('sa', $sa);
        return view('user.vendor.review')->with([
            'hods' => $hods,
            'model' => $models,
            'vendor' => $vendor,
            'certificateStandard' => $certificateStandard,
            'isNotCertified' => $isNotCertified,
            'countries' => $countries,
            'vendor_standards_array' => $vendor_standards_array,
            'basis_approvals' => $basis_approvals,
            'basis_additions' => $basis_additions,
            'sa' => $sa,
        ]);
    }

    public function review(ReviewRequest $request)
    {
        $data = $request->validated();
        $name = $request->input('vendorname');
        $id = $request->input('vendor_id');
        $iso = $request->input('vendor_iso');
        $vendor = Vendor::where('is_deleted', false)->findOrFail($id);
        Vendor::where('is_deleted', false)->where('id', $data['vendor_id'])->update([
            'vendor_name' => $data['vendorname'],
            'scope' => $data['vendorscope'],
            'country_id' => $data['vendoradd'],
            'location' => $data['vendorloc'],
            'email' => $data['vendoremail'],
            'contact' => $data['vendorcontact'],
            'Yoe' => $data['vendor_yoe']!='0' ? $data['vendor_yoe'] : null,
            'comments' => isset($data['vendorcomments']) ? $data['vendorcomments'] : null,
            'comments_of_reviewed_person' => isset($data['vendorcomments']) ? $data['vendorcomments'] : null,
            'vendor_type' => $data['vendortype'],
            'eval' => $data['vendor_eval'],
            'trial_order' => isset($data['vendor_trial']) ? $data['vendor_trial'] : null,
            'outcome' => isset($data['vendoroutcome']) && $data['vendor_trial'] == 'yes' ? $data['vendoroutcome'] : null,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::user()->employeeid,
            'status' => 'awaiting_approval',
            'approved_date' => $vendor->vendor_group == 'Existing' ? $data['vendorappr'] : date('Y-m-d'),
            'Basis_addition' => isset($data['vendorbasis_add']) ? implode(',', $data['vendorbasis_add']) : null,
            'basis_approval' => isset($data['vendorbasis_appr']) ? implode(',', $data['vendorbasis_appr']) : null,
        ]);
        $data_to_insert_vendor_standards = [];
        if ($iso) {
            foreach ($iso as $item) {
                $data_to_insert_vendor_standards[] = $item;
            }
        }
        $vendor->standards()->sync($data_to_insert_vendor_standards);

        Session::flash('msg', "Vendor reviewed successfully");
        //inserting values to activity table
        $activity = "Vendor with Name  " . $name . " and with id as " . $id . " is reviewed.";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        ViewActivity::insert($datas);
        //superadmin code ends

        return redirect()->back();
    }

    public function approvevendor(int $id)
    {
        $vendor = Vendor::where('is_deleted', false)->findOrFail($id);
        $hods = DB::table('departments')->pluck('hod')->unique()->toArray();
        $sa = DB::table('supplierapprovers')->pluck('email');
        $basis_approvals = explode(',', $vendor->basis_approval);
        $basis_approvals = array_filter($basis_approvals);

        $basis_additions = explode(',', $vendor->Basis_addition);
        $basis_additions = array_filter($basis_additions);

        $vendor_standards_array = [];
        $isNotCertified = !count($vendor->standards);
        foreach ($vendor->standards as $s) {
            $vendor_standards_array[] = $s->id;
        }

        $certificateStandard = Standards::where('name', '!=', 'Not certified')->get();
        $countries = Country::all();

        // below code related to superadmin
        $models = DB::table('modenadisable')->first();

        return view('user.vendor.approve')->with([
            'hods' => $hods,
            'model' => $models,
            'vendor' => $vendor,
            'countries' => $countries,
            'basis_approvals' => $basis_approvals,
            'basis_additions' => $basis_additions,
            'certificateStandard' => $certificateStandard,
            'isNotCertified' => $isNotCertified,
            'vendor_standards_array' => $vendor_standards_array,
            'sa' => $sa,
        ]);
    }

    public function approve(ApproveRequest $request)
    {
        $data = $request->validated();
        $name = $request->input('vendorname');
        $id = $request->input('vendor_id');
        $vendor = Vendor::where('is_deleted', false)->findOrFail($id);
        $iso = $request->input('vendor_iso');
        Vendor::where('is_deleted', false)->where('id', $data['vendor_id'])->update([
            'vendor_name' => $data['vendorname'],
            'scope' => $data['vendorscope'],
            'country_id' => $data['vendoradd'],
            'location' => $data['vendorloc'],
            'email' => $data['vendoremail'],
            'contact' => $data['vendorcontact'],
            'Yoe' => $data['vendor_yoe']!=0 ? $data['vendor_yoe'] : null,
            'comments' => isset($data['vendorcomments']) ? $data['vendorcomments'] : null,
            'comments_of_approved_person' => isset($data['vendorcomments']) ? $data['vendorcomments'] : null,
            'vendor_type' => $data['vendortype'],
            'eval' => $data['vendor_eval'],
            'trial_order' => isset($data['vendor_trial']) ? $data['vendor_trial'] : null,
            'outcome' => isset($data['vendoroutcome']) && $data['vendor_trial'] == 'yes' ? $data['vendoroutcome'] : null,
            'approved_at' => now(),
            'approved_by' => Auth::user()->employeeid,
            'status' => 'approved',
            'approved_date' => $vendor->vendor_group == 'Existing' ? $data['vendorappr'] : date('Y-m-d'),
            'Basis_addition' => isset($data['vendorbasis_add']) ? implode(',', $data['vendorbasis_add']) : null,
            'basis_approval' => isset($data['vendorbasis_appr']) ? implode(',', $data['vendorbasis_appr']) : null,
        ]);
        $data_to_insert_vendor_standards = [];
        if ($iso) {
            foreach ($iso as $item) {
                $data_to_insert_vendor_standards[] = $item;
            }
        }
        $vendor->standards()->sync($data_to_insert_vendor_standards);

        Session::flash('msg', "Vendor approved successfully");
        //inserting values to activity table
        $activity = "Vendor with Name  " . $name . " and with id as " . $id . " is approved.";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        ViewActivity::insert($datas);
        //superadmin code ends

        return redirect()->back();
    }

    //status
    // public static function evaluate($value)
    // {
    //     $frequency_days = null;
    //     $last_evaluation_date = date('Y-m-d', strtotime(max($value->histories->pluck('evaluation_date')->toArray())));
    //     $status = 'evaluated';

    //     switch ($value->eval) {
    //         case 'quarterly':
    //             $frequency_days = ' + 91 days';
    //             break;
    //         case 'half_year':
    //             $frequency_days = ' + 185 days';
    //             break;
    //         case 'one_half_year':
    //             $frequency_days = ' + 550 days';
    //             break;
    //         case 'one_year':
    //             $frequency_days = ' + 365 days';
    //             break;
    //         case 'two_year':
    //             $frequency_days = ' + 730 days';
    //             break;
    //         case 'three_year':
    //             $frequency_days = ' + 1095 days';
    //             break;
    //         case 'four_year':
    //             $frequency_days = ' + 1460 days';
    //             break;
    //         case 'five_year':
    //             $frequency_days = ' + 1825 days';
    //             break;
    //     }

    //     $due_date = date('Y-m-d', strtotime($last_evaluation_date . $frequency_days));
    //     Vendor::where('id', $value->id)->update([
    //         'due_date' => $due_date,
    //         'status' => $status,
    //     ]);

    // }

    public static function Neartoevaluate($d)
    {
        $today = date("Y-m-d");
        $z = date('Y-m-d', strtotime($d . ' - 1 months'));
        $e = 'Near To Evaluation';
        if ($today == $z) {
            DB::update('update vendor set status=:estatus where due_date=:ddd', ['estatus' => $e, 'ddd' => $d]);
        }

    }

    public static function Dueevaluation($d)
    {
        $today = date("Y-m-d");
        $e = 'Due Evaluation';

        if ($d == $today) {
            DB::update('update vendor set status=:estatus where due_date=:ddd', ['estatus' => $e, 'ddd' => $d]);
        }
    }

    //vendor register
    public function register()
    {
        //below is superadmin related code
        $vendormanagement = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($vendormanagement) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0, 'contextmanagement' => 1, 'complaintmanagement' => 0]);
        } else {
            $vendormanagement = DB::table('modpageload')->where('id', 1)->First();
            $vendormanagement->vendormanagement = $vendormanagement->vendormanagement + 1;
            DB::table('modpageload')->update(['vendormanagement' => $vendormanagement->vendormanagement]);
        }
        // superadmin code ends

        $departments = DB::table('departments')->get();
        $per1 = DB::table('vendorresult')->where('id', 1)->get();
        $per2 = DB::table('vendorresult')->where('id', 2)->get();
        $per3 = DB::table('vendorresult')->where('id', 3)->get();
        $data = Vendor::where('is_deleted', false)->orderBy('approved_at', 'asc')
            ->orderBy('reviewed_at', 'asc')
            ->with(['standards', 'requestEvaluatedByUser', 'endUser', 'systemAndCompliance', 'supplyChainRepresentative'])
            ->get();
        $vendor_ids = Vendor::where('is_deleted', false)->pluck('id')->toArray();
        $vendor_ids = implode(',', $vendor_ids);
        $sa = DB::table('supplierapprovers')->pluck('email')->toArray();
        $hse = DB::table('hses')->get();
        $d = "Procurement";
        $procrole = DB::table('users')->where('deptid', $d)->get();
        $dep = DB::table('users')->where('deptid', '=', $d)->pluck('email');
        $mr = DB::table('representatives')->pluck('email');
        $md = DB::table('managements')->pluck('email');
        $hseeval = DB::table('vendorevaluation')->pluck('hse_name', 'id');
        $users = DB::table('users')->select('name', 'id')->get();

        $addition_array_to_show = [];
        for ($i = 0; $i < count($data); $i++) {
            $basis_addition_array[$i] = explode(',', $data[$i]->Basis_addition);
            for ($j = 0; $j < count($basis_addition_array[$i]); $j++) {
                if (isset($basis_addition_array[$i][$j])) {
                    switch ($basis_addition_array[$i][$j]) {
                        case 'suggested_by_customer':
                            $basis_addition_array[$i][$j] = 'Suggested by customer';
                            break;
                        case 'single_source':
                            $basis_addition_array[$i][$j] = 'Single source';
                            break;
                        case 'economical_cost_effective':
                            $basis_addition_array[$i][$j] = 'Economical/cost effective';
                            break;
                        case 'reputed_company_brand_agency':
                            $basis_addition_array[$i][$j] = 'Reputed company/brand/agency';
                            break;
                        case 'suggested_by_management':
                            $basis_addition_array[$i][$j] = 'Suggested by management';
                            break;
                    }

                }
            }
            $addition_array_to_show[$i] = implode(',', array_filter($basis_addition_array[$i]));
        }

        $approval_array_to_show = [];

        for ($i = 0; $i < count($data); $i++) {
            $basis_approval_array[$i] = explode(',', $data[$i]->basis_approval);
            for ($j = 0; $j < count($basis_approval_array[$i]); $j++) {
                if (isset($basis_approval_array[$i][$j])) {
                    switch ($basis_approval_array[$i][$j]) {
                        case 'based_on_past_experience':
                            $basis_approval_array[$i][$j] = 'Based on past experience';
                            break;
                        case 'trial_order':
                            $basis_approval_array[$i][$j] = 'Trial order';
                            break;
                        case 'suggested_by_customer':
                            $basis_approval_array[$i][$j] = 'Suggested by customer';
                            break;
                        case 'sole_supplier':
                            $basis_approval_array[$i][$j] = 'Sole supplier';
                            break;
                        case 'cost_effective':
                            $basis_approval_array[$i][$j] = 'Cost effective';
                            break;
                        case 'reputed_company':
                            $basis_approval_array[$i][$j] = 'Reputed company';
                            break;
                    }

                }
            }
            $approval_array_to_show[$i] = implode(',', array_filter($basis_approval_array[$i]));
        }

        $last_evaluation_statuses = [];
        $last_evaluation_colors = [];
        $last_evaluation_comments = [];
        for ($i = 0; $i < count($data); $i++) {
            if (count($data[$i]->histories) > 0) {
                $max_evaluation_date = max($data[$i]->histories->pluck('evaluation_date')->toArray());
                $vendor_history = VendorHistory::where('evaluation_date', $max_evaluation_date)->first();
                $last_evaluation_statuses[$i] = VendorHistory::where('evaluation_date', $max_evaluation_date)->value('status');
                switch ($last_evaluation_statuses[$i]) {
                    case 'poor':
                        $last_evaluation_statuses[$i] = 'Poor';
                        $last_evaluation_colors[$i] = 'badge-poor';
                        break;
                    case 'below_average':
                        $last_evaluation_statuses[$i] = 'Below Average';
                        $last_evaluation_colors[$i] = 'badge-below-average';
                        break;
                    case 'fair':
                        $last_evaluation_statuses[$i] = 'Fair';
                        $last_evaluation_colors[$i] = 'badge-fair';
                        break;
                    case 'good':
                        $last_evaluation_statuses[$i] = 'Good';
                        $last_evaluation_colors[$i] = 'badge-good';
                        break;
                    case 'excellent':
                        $last_evaluation_statuses[$i] = 'Excellent';
                        $last_evaluation_colors[$i] = 'badge-excellent';
                        break;
                }
                $last_evaluation_comments[$i] = Vendorresult::where('result', $last_evaluation_statuses[$i])->value('comment');
                $data[$i]->last_evaluation_status = $last_evaluation_statuses[$i];
                $data[$i]->last_evaluation_comment = $last_evaluation_comments[$i];
                $data[$i]->last_evaluation_color = $last_evaluation_colors[$i];
            } else {
                $data[$i]->last_evaluation_status = '-';
                $data[$i]->last_evaluation_comment = '-';
                $data[$i]->last_evaluation_color = null;
            }
        }

        // $frequency_days = [];
        // for ($i = 0; $i < count($data); $i++) {
        //     switch ($data[$i]->eval) {
        //         case 'quarterly':
        //             $frequency_days[$i] = ' + 91 days';
        //             break;
        //         case 'half_year':
        //             $frequency_days[$i] = ' + 185 days';
        //             break;
        //         case 'one_half_year':
        //             $frequency_days[$i] = ' + 550 days';
        //             break;
        //         case 'one_year':
        //             $frequency_days[$i] = ' + 365 days';
        //             break;
        //         case 'two_year':
        //             $frequency_days[$i] = ' + 730 days';
        //             break;
        //         case 'three_year':
        //             $frequency_days[$i] = ' + 1095 days';
        //             break;
        //         case 'four_year':
        //             $frequency_days[$i] = ' + 1460 days';
        //             break;
        //         case 'five_year':
        //             $frequency_days[$i] = ' + 1825 days';
        //             break;
        //     }

        // }

        $models = DB::table('modenadisable')->first();

        return view('user.vendor.reg')->with('model', $models)
            ->with('data', $data)
            /*->with('frequency_days', $frequency_days)*/->with('vendor_ids', $vendor_ids)
            ->with('hseeval', $hseeval)
            ->with('sa', $sa)
            ->with('hse', $hse)
            ->with('procrole', $procrole)
            ->with('dept', $dep)
            ->with('per1', $per1)
            ->with('per2', $per2)
            ->with('per3', $per3)
            ->with('addition_array_to_show', $addition_array_to_show)
            ->with('approval_array_to_show', $approval_array_to_show)
            ->withMr($mr)
            ->withMd($md)
            ->with('users', $users);
    }
    //edit vendor
    public function editvendor()
    {
        if (isset($_GET['val'])) {
            $vid = $_GET['val'];
            $data = Vendor::where('is_deleted', false)->where('id', $vid)->firstOrFail();
            $mn = DB::table('departments')->get();
            $certificateStandard = Standards::where('name', '!=', 'Not certified')->get();
            $eval_value = "";
            $vendor_standards_array = [];
            $isNotCertified = !count($data->standards);
            foreach ($data->standards as $s) {
                $vendor_standards_array[] = $s->id;
            }

            $countries = Country::all();

            $models = DB::table('modenadisable')->first();

            return view('user/vendor/editvendor')
                ->withData($data)->with('mn', $mn)
                ->with('countries', $countries)
                ->with('id', $vid)
                ->with('vendor_standards_array', $vendor_standards_array)
                ->with('certificateStandard', $certificateStandard)
                ->with('isNotCertified', $isNotCertified)
                ->with('eval_value', $eval_value)->with('model', $models);
        }
        return 0;
    }

    public function updatevendor(EditRequest $req)
    {
        $id = $req->input('vendor_id');
        $vendor = Vendor::where('is_deleted', false)->find($id);
        $vendorname = $req->input('vendorname');
        $vendorgroup = $req->input('vendorgroup');
        $scope = $req->input('vendorscope');
        $adress = $req->input('vendoradd');
        $location = $req->input('vendorloc');
        $email = $req->input('vendoremail');
        $contact = $req->input('vendorcontact');
        $iso = $req->input('vendor_iso');
        $yoe = $req->input('vendor_yoe')!='0' ? $req->input('vendor_yoe') : null;
        $type = $req->input('vendortype');
        $addb = $req->input('vendorbasis_add');
        if ($addb == null) {
            $chkb = null;
        } else {
            $checkbox1 = $_POST['vendorbasis_add'];
            $chkb = "";
            foreach ($checkbox1 as $chk1) {
                $chkb .= $chk1 . ",";
            }
        }
        $trial = $req->input('vendor_trial');
        $outcome = $req->input('vendoroutcome');
        $apprb = $req->input('vendorbasis_appr');
        if ($apprb == null) {
            $chkap = null;
        } else {
            $checkbox1 = $_POST['vendorbasis_appr'];
            $chkap = "";
            foreach ($checkbox1 as $chk1) {
                $chkap .= $chk1 . ",";
            }
        }
        $apprdate = $req->input('vendor_appr');
        if ($trial == "no") {
            $out = null;
        } else {
            $out = $outcome;
        }
        $c = date('Y-m-d');
        $eval = $req->input('eval_hidden');

        if ($eval == "one_year") {
            $dued = Carbon::createFromFormat('Y-m-d', $c)->addYear(1);
        } else if ($eval == "two_year") {
            $dued = Carbon::createFromFormat('Y-m-d', $c)->addYear(2);
        } else if ($eval == "three_year") {
            $dued = Carbon::createFromFormat('Y-m-d', $c)->addYear(3);
        } else if ($eval == "four_year") {
            $dued = Carbon::createFromFormat('Y-m-d', $c)->addYear(4);
        } else if ($eval == "five_year") {
            $dued = Carbon::createFromFormat('Y-m-d', $c)->addYear(5);
        } else if ($eval == "quarterly") {
            $d = strtotime("+3 months", strtotime($c));
            $dued = date("Y-m-d", $d);
        } else if ($eval == "half_year") {
            $d = strtotime("+6 months", strtotime($c));
            $dued = date("Y-m-d", $d);
        } else if ($eval == "one_half_year") {
            $y = Carbon::createFromFormat('Y-m-d', $c)->addYear(1);
            $d = strtotime("+6 months", strtotime($y));
            $dued = date("Y-m-d", $d);
        }

        if ($vendor->id == $id || $vendor == null) {
            Vendor::where('is_deleted', false)->where('id', $id)->update([
                'vendor_name' => $vendorname,
                'vendor_group' => $vendorgroup,
                'scope' => $scope,
                'country_id' => $adress,
                'location' => $location,
                'email' => $email,
                'contact' => $contact,
                'Basis_addition' => $chkb,
                'Yoe' => $yoe,
                'trial_order' => $trial,
                'approved_date' => $apprdate,
                'basis_approval' => $chkap,
                'vendor_type' => $type,
                'outcome' => $out,
                'eval' => $req->input('vendor_eval'),
                'due_date' => $dued,
            ]);
            $data_to_insert_vendor_standards = [];

            if ($iso) {
                foreach ($iso as $item) {
                    $data_to_insert_vendor_standards[] = $item;
                }
            }
            $vendor->standards()->sync($data_to_insert_vendor_standards);

            //return redirect('/user/vendormgmt');
        }
        // else {

        //     return redirect('/user/vendormgmt');
        // }
        return redirect('/user/vendormgmt');
    }

//for selecting proc by vendor evaluator
    public function procrole(Request $req)
    {
        $id = $req->input('id');
        $proc = $req->input('proc');
        $vendorid = DB::table('vendorevaluation')->where('vid', $id)->value('vid');
        $due_date = Vendor::where('is_deleted', false)->where('id', $id)->value('due_date');
        if ($vendorid == null) {

            $d = array('vid' => $id, 'proc_name' => $proc);
            DB::table('vendorevaluation')->insert($d);

            $data = array(
                'vid' => $id,
                'duedate' => $due_date,
            );

            Mail::to($proc)->send(new VendorReview($data));
            return redirect('/user/vendormgmt');
        } else {
            DB::update('update vendorevaluation set proc_name=:p where vid = :id', ['p' => $proc, 'id' => $id]);
            $data = array(
                'vid' => $id,
                'duedate' => $due_date,
            );

            Mail::to($proc)->send(new VendorReview($data));
            return redirect('/user/vendormgmt');
        }

    }

    //for product reevaluation
    public function peval(int $history_id = null, string $reviewed = null)
    {
        if (isset($_GET['val'])) {
            $vid = $_GET['val'];
            $data = Vendor::where('is_deleted', false)->where('id', $vid)->with('histories')->get();
            $mn = DB::table('departments')->get();
            $t = "Product";
            $d = "Procurement";
            $eval_proc = DB::table('vendordetails')->where('filled_by', $d)->get();
            $h = "HSE";
            $eval_hse = DB::table('vendordetails')->where('filled_by', $h)->get();
            $u = "End User";
            $eval_end = DB::table('vendordetails')->where('filled_by', $u)->get();

            $hse = DB::table('hses')->get();
            //checking if user is mr
            $mr = DB::table('representatives')->pluck('email');
            $count = 0;
            foreach ($mr as $mrs) {
                if (Auth::user()->email == $mrs) {
                    $count = 1;
                }
            }

            $max_evaluation_date = null;
            for ($i = 0; $i < count($data); $i++) {
                if (count($data[$i]->histories) > 0) {
                    $max_evaluation_date = max($data[$i]->histories->pluck('evaluation_date')->toArray());
                } else {
                    $max_evaluation_date = null;
                }
            }

            $dep = DB::table('users')->where('deptid', '=', $d)->pluck('email');
            $hseeval = DB::table('vendorevaluation')->pluck('hse_name', 'id');
            //$user_hse=DB::table('users')->where('deptid',$h)->get();
            $user_hse = User::all();
            $enduser = DB::table('users')->get();

            $hsename = DB::table('vendorevaluation')->where('vid', $vid)->value('hse_name');
            $endusername = DB::table('vendorevaluation')->where('vid', $vid)->value('enduser_name');
            $models = DB::table('modenadisable')->First();
            $procname = DB::table('vendorevaluation')->where('vid', $vid)->value('proc_name');
            $vendor_supplier_approver = Supplierapprovers::where('email', Auth::user()->email)->first();
            //if (strcmp(Auth::user()->email, $procname) == 0 or $count == 1) {
            if($vendor_supplier_approver){
                $vendor_history = VendorHistory::where('id', $history_id)->first();
                $answers = $vendor_history ? $vendor_history->answers : [];
                $score = $vendor_history ? $vendor_history->overall_score : 0;
                return view('user/vendor/proc_reeval')->withData($data)->with('enduser', $enduser)->with('reviewed', $reviewed)->with('vendor_history', $vendor_history)->with('score', $score)->with('answers', $answers)->with('history_id', $history_id)->with('max_evaluation_date', $max_evaluation_date)->with('uh', $user_hse)->with('mn', $mn)->with('id', $vid)->with('model', $models)->with('eval_proc', $eval_proc)->with('eval_hse', $eval_hse)->with('eval_end', $eval_end)->with('t', $t);
            } elseif (strcmp(Auth::user()->email, $hsename) == 0) {
                return view('user/vendor/hse_reeval')->withData($data)->with('mn', $mn)->with('id', $vid)->with('model', $models)->with('eval', $eval_hse)->with('t', $t);
            } elseif (strcmp(Auth::user()->email, $endusername) == 0) {
                return view('user/vendor/enduser_reeval')->withData($data)->with('mn', $mn)->with('id', $vid)->with('model', $models)->with('eval', $eval_end)->with('t', $t);
            }

            return redirect()->back()->with('alert', 'Access Denied!');
        }
        //return redirect()->back();

    }

//for service provider reevaluation
    public function seval()
    {
        if (isset($_GET['val'])) {
            $vid = $_GET['val'];
            $data = Vendor::where('is_deleted', false)->where('id', $vid)->get();
            $mn = DB::table('departments')->get();
            $t = "Service Provider";
            $d = "Procurement";
            $eval_proc = DB::table('vendordetails')->where('type', $t)->where('filled_by', $d)->get();
            $h = "HSE";
            $eval_hse = DB::table('vendordetails')->where('type', $t)->where('filled_by', $h)->get();
            $u = "End User";
            $eval_end = DB::table('vendordetails')->where('type', $t)->where('filled_by', $u)->get();
            $hse = DB::table('hses')->get();
            // $dep = User::all()->where('id','=',$id);
            $dep = DB::table('users')->where('deptid', '=', $d)->pluck('email');
            $hseeval = DB::table('vendorevaluation')->pluck('hse_name', 'id');
            $user_hse = DB::table('users')->where('deptid', $h)->get();
            $enduser = DB::table('users')->get();
            $count = 0;
            $mr = DB::table('representatives')->pluck('email');
            foreach ($mr as $mrs) {
                if (Auth::user()->email == $mrs) {
                    $count = 1;
                }
            }
            $hsename = DB::table('vendorevaluation')->where('vid', $vid)->value('hse_name');
            $endusername = DB::table('vendorevaluation')->where('vid', $vid)->value('enduser_name');
            $models = DB::table('modenadisable')->First();
            $procname = DB::table('vendorevaluation')->where('vid', $vid)->value('proc_name');
            if (Auth::user()->email == $procname or $count == 1) {
                return view('user/vendor/proc_reeval')->withData($data)->with('uh', $user_hse)->with('mn', $mn)->with('id', $vid)->with('model', $models)->with('eval', $eval_proc)->with('t', $t)->with('enduser', $enduser);
            } elseif (strcmp(Auth::user()->email, $hsename) == 0) {
                return view('user/vendor/hse_reeval')->withData($data)->with('mn', $mn)->with('id', $vid)->with('model', $models)->with('eval', $eval_hse)->with('t', $t);
            } elseif (strcmp(Auth::user()->email, $endusername) == 0) {
                return view('user/vendor/enduser_reeval')->withData($data)->with('mn', $mn)->with('id', $vid)->with('model', $models)->with('eval', $eval_end)->with('t', $t);
            }
            //else {
            return redirect()->back()->with('alert', 'Access Denied!');

            //}
        }

        return redirect()->back();
    }

    public function getVendorDetailRecords($filled_by, $type = null)
    {

        switch ($type) {
            case 'product':
                $type = 'Product';
                break;
            case 'service_provider':
                $type = 'Service Provider';
                break;
            default:
                $type = 0;
                break;
        }

        $eval = DB::table('vendordetails')->where('filled_by', $filled_by)
            ->where(function ($query) use ($type) {
                if ($type) {
                    return $query->where('type', $type);
                }
            })
            ->get();

        return $eval->map(function ($item) {
            return [
                'id' => $item->id,
                'question' => $item->question,
                'filled_by' => $item->filled_by,
                'type' => $item->type,
                'score' => $item->score ? $item->score : 0,
            ];
        });

    }

    public function ajaxVendor(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');
        $reviewed = $request->input('reviewed');
        $vendor = Vendor::find($id);
        $eval_end = $eval_proc = $eval_hse = [];
        $currentUserId = $this->getCurrentUserId();
        $isSpecificationVendor = false;

        if ($vendor && $reviewed == 'evaluated') {
            if ($vendor->end_user_id == $currentUserId) {
                $eval_end = $this->getVendorDetailRecords('End User', $type);
                $isSpecificationVendor = true;
            }
            if ($vendor->supply_chain_representative_user_id == $currentUserId) {
                $eval_proc = $this->getVendorDetailRecords('Procurement', $type);
                $isSpecificationVendor = true;
            }
            if ($vendor->system_compliance_user_id == $currentUserId) {
                $eval_hse = $this->getVendorDetailRecords('HSE', $type);
                $isSpecificationVendor = true;
            }
        }

        if (!$isSpecificationVendor) {
            $eval_end = $this->getVendorDetailRecords('End User');
            $eval_proc = $this->getVendorDetailRecords('Procurement', $type);
            $eval_hse = $this->getVendorDetailRecords('HSE', $type);
        }

        $data['eval_end'] = $eval_end;
        $data['eval_proc'] = $eval_proc;
        $data['eval_hse'] = $eval_hse;

        return [
            "data" => $data,
            "error" => null,
        ];
    }

    public function ajaxChangeType(Request $request)
    {
        $eval_proc = DB::table('vendordetails')->where('type', $request->input('type'))->where('filled_by', 'Procurement')->get();
        $eval_hse = DB::table('vendordetails')->where('type', $request->input('type'))->where('filled_by', 'HSE')->get();
        $eval_end = DB::table('vendordetails')->where('type', $request->input('type'))->where('filled_by', 'End User')->get();

        $data['eval_proc'] = $eval_proc;
        $data['eval_hse'] = $eval_hse;
        $data['eval_end'] = $eval_end;

        return [
            "data" => $data,
            "error" => null,
        ];

    }

    //reevaluation on clicking submit button in proc_reeval page
    public function ajaxEvalProc(AjaxEvalProcRequest $request)
    {
        $data = [];
        $qstn = $request->input('qstn');
        $score = $request->input('score');
        $switch = $request->input('switch');
        $from_date_to = $request->input("from_date_to");
        $history_id = $request->input("history_id");
        $reviewed = $request->input("reviewed");

        [$score_of_all, $whole_score] = BaseController::calculateWholeScore($score, $qstn);

        $score_of_all = $whole_score ? $score_of_all / (0.4 * $whole_score) * 100 : 0;

        $score_of_all = round($score_of_all, 2);

        $data['score'] = $score_of_all;

        $id = $request->input('id');
        $type = $request->input('type');

        $vendor_details_count = DB::table('vendordetails')->count();
        $vid = DB::select('select * from vendorevaluation where vid = :id', ['id' => $id]);
        $eval_date = date('Y-m-d H:i:s', time()); //evaluation date
        $eval_id = Auth::user()->employeeid; //evaluated persons id
        $due_date = Vendor::where('is_deleted', false)->where('id', $id)->value('due_date');

        $sccc = $_POST['score']; //getting  each qstns total score
        $chk = 0;
        $chk2 = 0;
        $checkbox1 = 0;
        $sc = "";
        $s2 = 0;
        $to1 = 0;
        $q1 = 0;

        $to2 = 0;
        $q2 = 0;

        $to3 = 0;
        $q3 = 0;

        $res = 0;
        //adding user given checked score
        for ($i = 0; $i < $vendor_details_count; $i++) {

            if (isset($_POST['qstn' . $i])) {
                switch ($_POST['qstn' . $i]) {
                    case 10:
                        $to1 = $_POST['qstn' . $i];
                        $q1 = 0;
                        foreach ($to1 as $t1) {
                            $q1 = $q1 + $t1;
                        }
                        break;
                    case 5:
                        $to2 = $_POST['qstn' . $i];
                        foreach ($to2 as $t2) {
                            $q2 = $q2 + $t2;
                        }
                        break;
                    case 15:
                        $to3 = $_POST['qstn' . $i];
                        foreach ($to3 as $t3) {
                            $q3 = $q3 + $t3;
                        }
                        break;
                    case 0.4:
                    case 0.3:
                    case 0.2:
                    case 0.1:
                        $checkbox1 = $_POST['qstn' . $i];
                        foreach ($checkbox1 as $chk1) {
                            $chk .= $chk1 . "  ";
                            $chk2 = $chk2 + $chk1;

                        }
                        break;
                }

            }

        }

        //adding total maximum score
        foreach ($sccc as $s1) {

            $s2 = (int) $s2 + (int) $s1;
        }
        //substracting each  N/A frpm the total max  score
        $res = $s2 - $q1 - $q2 - $q3;

        $status = BaseController::calculateStatus($score_of_all);

        if ($switch) {

            $vendor = Vendor::where('is_deleted', false)->where('id', $id)->firstOrFail();
            $frequency_days = null;

            switch ($vendor->eval) {
                case 'quarterly':
                    $frequency_days = ' + 91 days';
                    break;
                case 'half_year':
                    $frequency_days = ' + 185 days';
                    break;
                case 'one_half_year':
                    $frequency_days = ' + 550 days';
                    break;
                case 'one_year':
                    $frequency_days = ' + 365 days';
                    break;
                case 'two_year':
                    $frequency_days = ' + 730 days';
                    break;
                case 'three_year':
                    $frequency_days = ' + 1095 days';
                    break;
                case 'four_year':
                    $frequency_days = ' + 1460 days';
                    break;
                case 'five_year':
                    $frequency_days = ' + 1825 days';
                    break;
            }

            $reviewed_by_id = null;
            $approved_by_id = null;
            $action_status = 'awaiting_review';
            $reviewed_at = null;
            $approved_at = null;
            $evaluation_date = null;

            $admin = Admin::where('email', Auth::user()->email)->first();
            $evaluated_by_id = $admin ? User::where('email', Auth::user()->email)->value('id') : Auth::user()->id;

            switch ($reviewed) {
                case 'under_review':
                    $reviewed_at = now();
                    $approved_at = null;
                    $reviewed_by_id = $admin ? User::where('email', Auth::user()->email)->value('id') : Auth::user()->id;
                    $approved_by_id = null;
                    $action_status = 'reviewed';
                    $evaluation_date = now();
                    break;
                case 'reviewed':
                    $reviewed_at = VendorHistory::where('is_deleted', false)->where('id', $history_id)->value('reviewed_at');
                    $reviewed_by_id = $admin ? VendorHistory::where('is_deleted', false)->where('id', $history_id)->value('evaluated_by') : VendorHistory::where('is_deleted', false)->where('id', $history_id)->value('evaluated_by');
                    $approved_by_id = $admin ? User::where('email', Auth::user()->email)->value('id') : Auth::user()->id;
                    $action_status = 'approved';
                    $evaluation_date = VendorHistory::where('is_deleted', false)->where('id', $history_id)->value('evaluation_date');
                    $approved_at = now();
                    break;
                case null:
                    $reviewed_at = null;
                    $reviewed_by_id = null;
                    $approved_by_id = null;
                    $approved_at = null;
                    $evaluation_date = now();
                    break;

            }

            $arrayToInsertOrUpdateVendorHistory = [
                'vendor_id' => $id,
                'evaluated_by' => $reviewed_by_id,
                'approved_by' => $approved_by_id,
                'reviewed_at' => $reviewed_at,
                'approved_at' => $approved_at,
                'evaluation_date' => $evaluation_date,
                'evaluation_period' => $from_date_to,
                'overall_score' => $score_of_all,
                'status' => $status,
                'action_status' => $action_status,
                'type' => $request->input('theType'),
                'answers' => $qstn,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($history_id != null) {
                Vendor::where('id', $id)->update([
                    'due_date' => date('Y-m-d', strtotime(now() . $frequency_days)),
                ]);
                VendorHistory::where('id', $history_id)->update($arrayToInsertOrUpdateVendorHistory);
            } else {
                Vendor::where('id', $id)->update([
                    'due_date' => date('Y-m-d', strtotime(now() . $frequency_days)),
                ]);
                VendorHistory::create($arrayToInsertOrUpdateVendorHistory);
            }

        }

        foreach ($vid as $row) {
            $v1id = (string) $row->vid;
            if ($v1id == $id or $vid == null) {
                DB::update('update vendorevaluation set type=:vtype,proc_score=:ps,proc_total =:pt,proc_evaluated_by=:pevalby,proc_evaluated_at=:pevalat where vid =:vd', ['vtype' => $type, 'ps' => $chk2, 'pt' => $res, 'pevalby' => $eval_id, 'pevalat' => $eval_date, 'vd' => $id]);

                return [
                    "data" => $data,
                    "error" => null,
                ];

            } else {
                return [
                    "data" => $data,
                    "error" => null,
                ];

            }

        }
        return [
            "data" => $data,
            "error" => null,
        ];

    }

    //reevaluation on clicking submit button in hse_reeval page
    public function evalhse(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $vid = DB::select('select * from vendorevaluation where vid = :id', ['id' => $id]);
        $due_date = Vendor::where('is_deleted', false)->where('id', $id)->value('due_date');
        $endusername = DB::table('vendorevaluation')->where('vid', $id)->value('enduser_name');
        date_default_timezone_set('Asia/Kuwait');
        $eval_date = date('d-m-Y h:i:s A', time()); //evaluation date
        $eval_id = Auth::user()->employeeid; //evaluated persons id
        $sccc = $_POST['score']; //getting  each qstns total score
        $chk = "";
        $chk2 = 0;
        $sc = "";
        $s2 = 0;
        //for every N/A checkbox
        if (isset($_POST['qstn1'])) {
            $to1 = $_POST['qstn1'];
            $q1 = 0;
            foreach ($to1 as $t1) {
                $q1 = $q1 + $t1;

            }
        } else {
            $to1 = 0;
            $q1 = 0;
        }
//for every N/A checkbox
        $q2 = 0;
        if (isset($_POST['qstn2'])) {
            $to2 = $_POST['qstn2'];
            foreach ($to2 as $t2) {
                $q2 = $q2 + $t2;

            }
        } else {
            $to2 = 0;
            $q2 = 0;
        }
        //for every N/A checkbox
        $q3 = 0;

        if (isset($_POST['qstn3'])) {
            $to3 = $_POST['qstn3'];
            foreach ($to3 as $t3) {
                $q3 = $q3 + $t3;

            }
        } else {
            $to3 = 0;
            $q3 = 0;
        }
        $res = 0;
//adding user given checked score
        if (isset($_POST['qstn'])) {
            $checkbox1 = $_POST['qstn'];
            foreach ($checkbox1 as $chk1) {
                $chk .= $chk1 . "  ";
                $chk2 = $chk2 + $chk1;

            }
        } else {
            $checkbox1 = 0;
            $chk = 0;
            $chk2 = 0;
        }
//adding total maximum score
        foreach ($sccc as $s1) {
// $sc.= $s1." ";
            $s2 = (int) $s2 + (int) $s1;
        }
//substracting each  N/A frpm the total max  score
        $res = $s2 - $q1 - $q2 - $q3;

// $dh  = array('vid' => $id,'score'=>$per,'evaluation_date'=>$eval_date,'evaluated_by'=>$eval_id);
        foreach ($vid as $row) {
            $v1id = (string) $row->vid;
            if ($v1id == $id or $vid == null) {
                DB::update('update vendorevaluation set hse_score=:vscore,hse_total=:vht,hse_evaluated_by=:veb,hse_evaluated_at =:vet where vid =:vd', ['vscore' => $chk2, 'vht' => $res, 'veb' => $eval_id, 'vet' => $eval_date, 'vd' => $id]);
                $data = array(
                    'vid' => $id,
                    'duedate' => $due_date,
                );

                Mail::to($endusername)->send(new VendorReview($data));
                return redirect('/user/vendormgmt');

            } else {
                return redirect('/user/vendormgmt');

            }

        }

    }

//reevaluation on clicking submit button
    public function evalenduser(Request $request)
    {
        $id = $request->input('id');
        $vid = DB::select('select * from vendorevaluation where vid = :id', ['id' => $id]);
        $eval_date = date('Y-m-d H:i:s', time());
        $fdate = date("F Y", strtotime($eval_date));
        $eval_id = Auth::user()->employeeid; //evaluated persons id
        $proc_score = DB::table('vendorevaluation')->where('vid', $id)->value('proc_score');
        $hse_score = DB::table('vendorevaluation')->where('vid', $id)->value('hse_score');
        $proc_tot = DB::table('vendorevaluation')->where('vid', $id)->value('proc_total');
        $hse_tot = DB::table('vendorevaluation')->where('vid', $id)->value('hse_total');
        $proc_id = DB::table('vendorevaluation')->where('vid', $id)->value('proc_evaluated_by');
        $proc_date = DB::table('vendorevaluation')->where('vid', $id)->value('proc_evaluated_at');
        $hse_id = DB::table('vendorevaluation')->where('vid', $id)->value('hse_evaluated_by');
        $hse_date = DB::table('vendorevaluation')->where('vid', $id)->value('hse_evaluated_at');
        $type = DB::table('vendorevaluation')->where('vid', $id)->value('type');

        $sccc = $_POST['score']; //getting  each qstns total score
        $chk = "";
        $chk2 = 0;
        $sc = "";
        $s2 = 0;
        // $t=$request->input('tot');
        //for every N/A checkbox
        if (isset($_POST['qstn1'])) {
            $to1 = $_POST['qstn1'];
            $q1 = 0;
            foreach ($to1 as $t1) {
                $q1 = $q1 + $t1;

            }
        } else {
            $to1 = 0;
            $q1 = 0;
        }
        //for every N/A checkbox
        $q2 = 0;
        if (isset($_POST['qstn2'])) {
            $to2 = $_POST['qstn2'];
            foreach ($to2 as $t2) {
                $q2 = $q2 + $t2;

            }
        } else {
            $to2 = 0;
            $q2 = 0;
        }
        //for every N/A checkbox
        $q3 = 0;

        if (isset($_POST['qstn3'])) {
            $to3 = $_POST['qstn3'];
            foreach ($to3 as $t3) {
                $q3 = $q3 + $t3;

            }
        } else {
            $to3 = 0;
            $q3 = 0;
        }
        $res = 0;
        //adding user given checked score
        if (isset($_POST['qstn'])) {
            $checkbox1 = $_POST['qstn'];
            foreach ($checkbox1 as $chk1) {
                $chk .= $chk1 . "  ";
                $chk2 = $chk2 + $chk1; //end user score

            }
        } else {
            $checkbox1 = 0;
            $chk = 0;
            $chk2 = 0;
        }
        //adding proc,hse,enduser score
        $overall_score = $proc_score + $hse_score + $chk2;
//adding total maximum score
        foreach ($sccc as $s1) {
            // $sc.= $s1." ";
            $s2 = (int) $s2 + (int) $s1;
        }
//substracting each  N/A frpm the total max  score
        $res = $s2 - $q1 - $q2 - $q3;
        $result = $res + $proc_tot + $hse_tot;
//calculating percentage by dividing total max score with total given checked score.
        if ($overall_score == 0) {
            $per = 0;
        } else {
            $per = ($overall_score / $result) * 100;
        }
// $dh  = array('vid' => $id,'percentage'=>$per,'evaluation_date'=>$eval_date,'evaluated_by'=>$eval_id);
        $status = "Evaluated";
        $recommend = $request->input('recommend');

        foreach ($vid as $row) {
            $v1id = (string) $row->vid;
            if ($v1id == $id or $vid == null) {
                $vendorname = Vendor::where('is_deleted', false)->where('id', $id)->pluck('vendor_name');
                $activity = "Vendor " . $vendorname . " updated with status as " . $status;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
                DB::table('viewactivity')->insert($datas);

                DB::update('update vendorevaluation set final_score=:fs,recommend=:vr,end_score=:vendscore,end_total=:vendtot,end_evaluated_by = :vevalby,end_evaluated_at=:vevalat,percentage=:vp,total_score=:vtotal where vid = :vid', ['fs' => $result, 'vr' => $recommend, 'vendscore' => $chk2, 'vendtot' => $res, 'vevalby' => $eval_id, 'vevalat' => $eval_date, 'vp' => $per, 'vtotal' => $overall_score, 'vid' => $id]);
                DB::update('update vendor set status=:vst,per=:vper,evaluated_at=:vevalat  where id = :vid', ['vst' => $status, 'vper' => $per, 'vevalat' => $eval_date, 'vid' => $id]);

                return redirect('/user/vendormgmt');

            } else {
                return redirect('/user/vendormgmt');
            }
        }
    }

    //vendor history
    public function history()
    {
        if (isset($_GET['val'])) {
            $vid = $_GET['val'];
            $data = VendorHistory::where('vendor_id', $vid)->get();
            $vname = Vendor::where('is_deleted', false)->where('id', $vid)->pluck('vendor_name');
            $vn = trim($vname, '[ ] " "');
            $vgroup = Vendor::where('is_deleted', false)->where('id', $vid)->pluck('vendor_group');
            $vg = trim($vgroup, '[ ] " "');
            $createdat = Vendor::where('is_deleted', false)->where('id', $vid)->pluck('created_at');
            $vcat = trim($createdat, '[ ] " "');
            $createdby = Vendor::where('is_deleted', false)->where('id', $vid)->pluck('added_by');
            $vcby = trim($createdby, '[ ] " "');
            $vendor = Vendor::where('is_deleted', false)->findOrFail($vid);

            $eval_end = $this->getVendorDetailRecords('End User');
            $eval_proc = $this->getVendorDetailRecords('Procurement');
            $eval_hse = $this->getVendorDetailRecords('HSE');

            $models = DB::table('modenadisable')->first();

            return view('user.vendor.history')->with([
                'model' => $models,
                'data' => $data,
                'eval_end' => $eval_end,
                'eval_hse' => $eval_hse,
                'eval_proc' => $eval_proc,
                'vid' => $vid,
                'vname' => $vn,
                'vendor' => $vendor,
                'created_at' => $vcat,
                'createdby' => $vcby,
                'vgroup' => $vg,
            ]);

        }
    }

    public function search(Request $req)
    {
        $status = Vendor::where('is_deleted', false)->get();
        $departments = DB::table('departments')->get();

//------------------search---------------
        $departmentname = DB::table('departments')->pluck('departmentname');
        $keyword = $req->input('keyword');

        $data = DB::table('vendor')
            ->where('is_deleted', false)
            ->where(function ($query) use ($req) {
                $query

                    ->where('vendor_name', '=', $req->input('keyword'))
                    ->orWhere('vendor_group', '=', $req->input('keyword'))
                    ->orWhere('scope', '=', $req->input('keyword'))
                    ->orWhere('country_id', '=', $req->input('keyword'))
                    ->orWhere('location', '=', $req->input('keyword'))
                    ->orWhere('Basis_Addition', '=', $req->input('keyword'))
                    //->orWhere('Iso_standard', '=', $req->input('keyword'))
                    ->orWhere('Yoe', '=', $req->input('keyword'))
                    ->orWhere('vendor_type', '=', $req->input('keyword'))
                    ->orWhere('eval', '=', $req->input('keyword'))
                    ->orWhere('status', '=', $req->input('keyword'));

            })
            ->paginate(100)
            ->setpath('');

        $data->appends(array(
            'department' => $req->input('keyword'),
        ));

        $models = DB::table('modenadisable')->First();
        return view('user.vendor.register', compact('data', 'departments'))->with('model', $models);
    }
    //filter
    public function filtr(Request $req)
    {
        $data = Vendor::where('is_deleted', false)->where(function ($query) use ($req) {
            $query->where('vendor_group', '!=', 'null')
                ->where('vendor_group', '=', $req->input('purpose'))
                ->whereIn('vendor_group', ['New', 'Existing']);

        })
            ->orwhere(function ($query1) use ($req) {
                $query1->where('vendor_type', '!=', 'null')
                    ->where('vendor_type', '=', $req->input('type'))
                    ->whereIn('vendor_type', ['Critical', 'SemiCritical', 'Non critical']);
            })
            ->paginate(75)
            ->setpath('');
        $data->appends(array(
            'vendor_group' => $req->input('purpose'),
            'vendor_type' => $req->input('type'),
        ));

        $models = DB::table('modenadisable')->First();
        return view('user.vendor.reg')->with('model', $models)->with('data', $data);
    }

    //delete request by mr
    public function delete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            // inserting data to activities table
            $name = Vendor::where('is_deleted', false)->where('id', $id)->pluck('vendor_name');
            $activity = "A vendor entry is Removed with Name " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
            DB::table('viewactivity')->insert($datas);

            //DB::delete('delete from vendor where id = :vid', ['vid' => $id]);
            Vendor::where('id', $id)->update([
                'is_deleted' => 1,
            ]);
            $models = DB::table('modenadisable')->First();

            return [
                'data' => null,
                'error' => null,
            ];

            //return redirect('/user/vendormgmt')->with('model', $models);
        }}

    //edit vendor by mr
    public function editv()
    {
        $vendor_standards_array = [];

        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id_array = $id;
            $id = explode(',', $_GET['val']);

            $standards = Standards::all();

            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = Vendor::where('is_deleted', false)->where('id', $d)->with('standards')->first();
                }
                $n = $n + 1;
            }
        }
        $data_string = implode(',', $data);

        $countries = Country::all();

        $models = DB::table('modenadisable')->first();

        return view('/user/vendor/editven')->withData($data)
            ->with('id', $id)
            ->with('id_array', $id_array)
            ->with('standards', $standards)
            ->with('data_string', $data_string)
            ->with('countries', $countries)
        //->with('vendor_standards_array', $vendor_standards_array)
            ->with('model', $models);
    }
    public function editven(Request $req)
    {
        $m = count($req->group);

        $name = $req->input('vname');

        $year = $req->input('year');
        $type = $req->input('type');
        $eval = $req->input('eval');
        $scope = $req->input('scope');
        $con = $req->input('contact');
        $email = $req->input('email');
        $add = $req->input('add');
        $iso = $req->input('iso');
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = Vendor::where('is_deleted', false)->where('id', $d)->first();
                $data_to_insert_vendor_standards[$d] = $req->input("iso" . $d);
            }
        }

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {

                if ($dat->id == $s && $n < $m) {

                    $vendor = Vendor::where('is_deleted', false)->find($s);
                    Vendor::where('is_deleted', false)->where('id', $s)
                        ->update(array(
                            'vendor_name' => $req->vname[$n],
                            'Yoe' => $req->year[$n] != '0' ? $req->year[$n] : null,
                            'vendor_type' => $req->type[$n],
                            'email' => $req->email[$n],
                            'eval' => $req->eval[$n],
                            'scope' => $req->scope[$n],
                            'contact' => $req->contact[$n],
                            'country_id' => $req->add[$n],
                        ));

                    $vendor->standards()->sync($data_to_insert_vendor_standards[$s]);

                    //insert data to activity table superadmin code
                    $activity = "Vendor is  Edited and Updated with new Details  where  ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
                    DB::table('viewactivity')->insert($datas);
                    //end of superadmin code
                }
            }
            $n = $n + 1;
        }
        $models = DB::table('modenadisable')->first();

        return redirect('/user/vendormgmt')->with('model', $models);
    }

    //report
    public function report()
    {
        //below is superadmin related code
        $vendormanagement = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($vendormanagement) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0, 'contextmanagement' => 1, 'complaintmanagement' => 0]);
        } else {
            $vendormanagement = DB::table('modpageload')->where('id', 1)->First();
            $vendormanagement->vendormanagement = $vendormanagement->vendormanagement + 1;
            DB::table('modpageload')->update(['vendormanagement' => $vendormanagement->vendormanagement]);
        }
        // superadmin code ends

        $departments = DB::table('departments')->get();
        //    $per1=DB::table('vendorresult')->where('id',1)->get();
        //    $per2=DB::table('vendorresult')->where('id',2)->get();
        //    $per3=DB::table('vendorresult')->where('id',3)->get();
        $e = "Evaluated";
        $data = Vendor::where('is_deleted', false)->where('status', $e)->get();
        $sa = DB::table('supplierapprovers')->pluck('email');
        //    $hse=DB::table('hses')->get();
        // $dep = User::all()->where('id','=',$id);
        //    $d="Procurement";
        //    $dep=DB::table('users')->where('deptid','=',$d)->pluck('email');
        //    $mr = DB::table('representatives')->pluck('email');
        //    $md = DB::table('managements')->pluck('email');
        //    $hseeval = DB::table('vendorevaluation')->pluck('hse_name','id');

        $models = DB::table('modenadisable')->First();
        return view('user.vendor.report')->with('model', $models)->with('data', $data)->with('sa', $sa);

    }

    //view report
    public function viewreport()
    {
        //----------------Analysis-------------
        // if (isset($_GET['val'])) {
        //     $id = $_GET['val'];
        //     $chart = new VendorAnalysis;
        //     $chart->title("Performance Of Vendor");
        //     $processes = DB::select("select date from vendorhistory where vid=$id");
        //     $process = [];
        //     foreach ($processes as $key) {array_push($process, $key->date);}
        //     $chart->labels($process);
        //     $phigh = DB::select("select percentage AS number
        //            FROM  vendorhistory where vid=$id");

        //     $highp = [];
        //     foreach ($phigh as $key) {array_push($highp, $key->number);}
        //     $chart->dataset('Percentage', 'bar', $highp)->color('#FFD700')->backgroundcolor('#FFD700');

        //     $models = DB::table('modenadisable')->First();
        //     return view('user.vendor.analysis', compact('chart'))->with('model', $models);
        // }
    }

    // analysis
    public function analysis()
    {
        $model = ModenaDisable::first();

        return view('user.vendor.analysis')->with([
            'model' => $model,
        ]);
    }

    /**
     * Request Evaluation
     *
     * @param RequestEvaluationRequest $request
     *
     * @return array
     */
    public function requestEvaluation(RequestEvaluationRequest $request): array
    {
        $response = [];
        try {
            $vendorIds     = $request->get('vendor_ids');
            $currentUserId = $this->getCurrentUserId();
            DB::table('vendor')->whereIn('id', $vendorIds)
                ->update([
                    'evaluation_requested_by'             => $currentUserId,
                    'evaluation_requested_time'           => now(),
                    'end_user_id'                         => $request->get('end_user_id'),
                    'system_compliance_user_id'           => $request->get('system_compliance_user_id'),
                    'supply_chain_representative_user_id' => $request->get('supply_chain_representative_user_id'),
                ]);
            $vendors = Vendor::select('id', 'vendor_name')->whereIn('id', $vendorIds)->get();
            $appUrl = BaseController::addSlashToEndOfURL();
            $emailData = [];

            $emailNotification = EmailNotification::where('module', 'vendormanagement')
                ->where('item', 'Request evaluation vendor')
                ->where('is_blocked', false)
                ->first();
            $emailNotificationUsers = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
            $recipientIds = [];
            if (count($emailNotificationUsers) > 0) {
                $recipientIds = array_merge($recipientIds, $emailNotificationUsers->pluck('user_id')->toArray());
            }
            $emailData['app_url'] = $appUrl;
            $emailData['email_content'] = htmlspecialchars($emailNotification->email_content);
            $recipientIds[] = $request->get('end_user_id');
            $recipientIds[] = $request->get('system_compliance_user_id');
            $recipientIds[] = $request->get('supply_chain_representative_user_id');
            $recipients = User::whereIn('id', $recipientIds)->get();

            foreach ($vendors as $vendor) {
                $emailData['vendor_id'] = $vendor->id;
                $emailData['vendor_name'] = $vendor->vendor_name;

                foreach ($recipients as $recipient) {
                    $emailData['name'] = $recipient->name;
                    Mail::to($recipient)->send(new RequestEvaluation($emailData));
                }
            }

            $response['success'] = true;
            $response['message'] = 'Request evaluation successfully';
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
            $response['message'] = 'Request evaluation failure';
        }

        return $response;
    }

    /**
     * @return int
     */
    protected function getCurrentUserId(): int
    {
        $currentUser = auth()->user();

        $admin = Admin::where('id', $currentUser->id)->first();

        if ($admin) {
            $user = User::select('id')->where('email', '=', $admin->email)->first();
            $userId = $user ? $user->id : 0;
        } else {
            $userId = $currentUser->id;
        }

        return $userId;
    }

}
