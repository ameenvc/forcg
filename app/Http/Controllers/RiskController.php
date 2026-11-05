<?php

namespace App\Http\Controllers;

use App\Charts\RiskAnalysis;
use App\ClientDetail;
use App\Departments;
use App\Http\Controllers\Controller;
use App\Mail\NewOpportunityAdded;
use App\Mail\NewRiskAdded;
use App\Mail\RiskChangeowner;
use App\Mail\RiskNotApproved;
use App\Mitigation;
use App\MitigationAttachment;
use App\ModenaDisable;
use App\OpportunityType;
use App\Risk;
use App\Type;
use App\User;
use App\ViewActivity;
use App\RiskGroupDetails;
use App\RiskHistory;
use App\Riskreviewer;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Exports\RiskExport;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\View\View;
use App\RiskComment;
use App\Mail\Escalaterisk;
use App\Opportunity;
use App\Helpers\Risk\NewRiskAddedEmailHelper;
use App\Services\RiskWordExport;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class RiskController extends Controller
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

    public function helper($value)
    {
        switch ($value) {
            case 'High':
                $output = 3;
                break;
            case 'Moderate':
            case 'Medium':
                $output = 2;
                break;
            case 'Low':
                $output = 1;
                break;
            default:
                $output = 1;
                break;
        }
        return $output;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //add risk

    public function addrisk()
    {
        $riskreviewers = Riskreviewer::with('user')->whereHas('user')->orderBy('name', 'asc')->get();
        $rskgrp = DB::table('risknames')->get();
        $departments = DB::table('departments')->get();
        $hods = DB::table('departments')->pluck('hod');
        $strategy = DB::table('riskstrategies')->get();
        $riskinfo = DB::table('riskinfo')->get();
        $riskgroupdetails = RiskGroupDetails::all();
        $hml = DB::table('hml')->get();

        //below code related to superadmin
        $models = DB::table('modenadisable')->First();
        return view('user.risks.addrisk')
                     ->with('riskreviewers', $riskreviewers)
                     ->with('model', $models)
                     ->withHml($hml)
                     ->withRskgrp($rskgrp)
                     ->withStrategy($strategy)
                     ->withRiskinfo($riskinfo)
                     ->withDepartments($departments)
                     ->with('riskgroupdetails',$riskgroupdetails)
                     ->withHods($hods);
    }

    public function displayriskgroupnameajax(Request $request)
    {
        $risk_group_name = $request->input('risk_group_name');
        $risk_category_name = $request->input('risk_category_name');
        if ($risk_group_name == 'Operational') {
            $risk_names = RiskGroupDetails::where('group_name',$risk_group_name)
                ->where('risk_category', $risk_category_name)
                ->pluck('risk_name');
        } else {
            $risk_names = RiskGroupDetails::where('group_name',$risk_group_name)->pluck('risk_name');
        }

        return response()->json([
                'status'=>200,
                'risknames'=>$risk_names,
                'riskgroupname'=>$risk_group_name,
            ]);

    }

    public function displayriskgroupdetailsajax(Request $request)
    {
        $risk_name = $request->input('risk_name');
        $groupname = $request->input('risk_groupname');
        $risk_desp = RiskGroupDetails::where('group_name',$groupname)->where('risk_name',$risk_name)->value('risk_desp');
        $risk_driver = RiskGroupDetails::where('group_name',$groupname)->where('risk_name',$risk_name)->value('risk_driver');
        $mitigating_action = RiskGroupDetails::where('group_name',$groupname)->where('risk_name',$risk_name)->value('mitigating_actions');

        return response()->json([
                'status'=>200,
                'risk_name'=>$risk_name,
                'risk_desp'=>$risk_desp,
                'risk_driver'=>$risk_driver,
                'mitigating_action'=>$mitigating_action,
            ]);

    }

    public function insertrisk(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'riskname' => 'required|max:190',
            'riskarea' => 'required|not_in:0',
            'riskdec' => 'required|max:65435',
            'riskdri' => 'nullable|max:65435',
            'existing_controls' => 'nullable|max:65435',
            'riskreviewer_id' => 'required|integer|not_in:0'
        ], [], [
            'riskname' => 'Risk Name',
            'riskarea' => 'Risk Area',
            'riskdec' => 'Risk Description',
            'riskdri' => 'Risk Drivers',
            'existing_controls' => 'Existing Controls',
            'riskreviewer_id' => 'Risk Reviewer'
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput($req->all())->with('model', $models);
        } else {
            $riskgroup = $req->input('riskgroup');

            $lastRiskItemOfGroup = Risk::where('riskgroup', $riskgroup)->orderBy('count', 'desc')->first();
            if ($lastRiskItemOfGroup) {
                $count = $lastRiskItemOfGroup->count + 1;
            } else {
                $count = "1";
            }
            $riskarea = $req->input('riskarea');
            $riskarea_name = Departments::where('id', $riskarea)->value('departmentname');
            $riskname = $req->input('riskname');
            $riskdec = $req->input('riskdec');
            $riskdri = $req->input('riskdri');
            $existingControls = $req->input('existing_controls');
            $riskscr = $req->input('riskscr');
            $risk_response = $req->input('risk_response');
            $now = date('d-m-Y h:i:s A', time());
            $impa = $req->input('impa');
            $like = $req->input('like');
            $postedby = Auth::user()->employeeid;
            if ($like == "High") {
                $value1 = 3;
            } elseif ($like == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($impa == "High") {
                $value2 = 3;
            } elseif ($impa == "Moderate") {
                $value2 = 2;
            } else {
                $value2 = 1;
            }

            if (($value1 * $value2) > 5) {
                $rank1 = "High";
            } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                $rank1 = "Moderate";
            } else {
                $rank1 = "Low";
            }

            $data = array(
                'count' => $count,
                'riskgroup' => $riskgroup,
                'riskarea' => $riskarea,
                'division_id' => $req->input('division'),
                'area_id' => $req->input('area'),
                'riskname' => $riskname,
                'riskdec' => $riskdec, 'riskdri' => $riskdri, 'riskscr' => $riskscr, 'date' => $now,
                'existing_controls' => $existingControls,
                'impa' => $impa,
                'like' => $like,
                'rank' => $rank1,
                'postedby' => $postedby,
                'riskreviewer_id' => $req->input('riskreviewer_id'),
                'risk_response' => $risk_response,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );
            $newlyAddedRisk = Risk::create($data);

            //superadmin related codes
            //inserting values to activity table
            $activity = "Inserted new Risk with Name as " . $riskname . " and with area as " . $riskarea_name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);
            //superadmin code ends
            $code = strtoupper(substr($riskgroup, 0, 3));
            $dep_id = $code . $count;
            $data3 = array('grp_id' => $dep_id);
            Risk::where('count', $count)
                ->where('riskgroup', $riskgroup)
                ->update($data3);

            Session::flash('msg', 'Risk saved successfully.');

            // shifted mail service to helper function
            $status = NewRiskAddedEmailHelper::sendEmail($newlyAddedRisk->id);

            if(!$status) {
                Session::flash('risk_notification_warning', 'Risk saved. Its email notification could not be completed. Please contact your administrator; do not submit the risk again.');
                return redirect()->route('risk.index');
            }
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    //resubmit for raised
    public function resubmit()
    {
        if (isset($_GET['val'])) {
            $strategy = DB::table('riskstrategies')->get();
            $rskgrp = DB::table('risknames')->get();
            $hml = DB::table('hml')->get();
            $departments = DB::table('departments')->get();
            $data = Risk::with('riskreviewer')->where('Rid', '=', $_GET['val'])->get();
            $riskreviewers = Riskreviewer::with('user')->whereHas('user')->orderBy('name', 'asc')->get();
            $models = DB::table('modenadisable')->First();
            return view('user.risks.resubmit')->withHml($hml)->with('riskreviewers', $riskreviewers)->withStrategy($strategy)->withRskgrp($rskgrp)->withDepartments($departments)->withData($data)->with('model', $models);
        }
    }

    public function resubmitrisk(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'riskname' => 'required|max:190',
            'riskarea' => 'required|not_in:0',
            'riskdec' => 'required|max:65435',
            'riskdri' => 'nullable|max:65435',
            'existing_controls' => 'nullable|max:65435',
            'riskreviewer_id' => 'required|integer|not_in:0'
        ], [], [
            'riskname' => 'Risk Name',
            'riskarea' => 'Risk Area',
            'riskdec' => 'Risk Description',
            'riskdri' => 'Risk Drivers',
            'existing_controls' => 'Existing Controls',
            'riskreviewer_id' => 'Risk Reviewer'
        ]);

        if ($validator->fails()) {
            // echo "<script> alert('Error Resubmit the form')</script>";
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $id = $req->input('riskid');
            // dd($id);
            $riskarea = $req->input('riskarea');
            $riskname = $req->input('riskname');
            $riskdec = $req->input('riskdec');
            $riskdri = $req->input('riskdri');
            $riskscr = $req->input('riskscr');
            $now = date('d-m-Y h:i:s A', time());
            // $now = new DateTime();
            $impa = $req->input('impa');
            $like = $req->input('like');
            // $postedby = $req->input('post');
            $postedby = Auth::user()->employeeid;
            if ($like == "High") {
                $value1 = 3;
            } elseif ($like == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($impa == "High") {
                $value2 = 3;
            } elseif ($impa == "Moderate") {
                $value2 = 2;
            } else {
                $value2 = 1;
            }

            if (($value1 * $value2) > 5) {
                $rank1 = "High";
            } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                $rank1 = "Moderate";
            } else {
                $rank1 = "Low";
            }

            $data = array(
                'riskname' => $riskname,
                'riskdec' => $riskdec,
                'riskdri' => $riskdri,
                'riskscr' => $riskscr,
                'date' => $now,
                'impa' => $impa,
                'like' => $like,
                'rank' => $rank1,
                'riskreviewer_id' => $req->input('riskreviewer_id'),
                'postedby' => $postedby,
                'status' => 'NO',
                'action_status' => 'Under review',
                'existing_controls' => $req->input('existing_controls'),
            );
            // dd($data);
            Risk::where('Rid', '=', $id)->update($data);
            $newlyUpdatedRisk = Risk::where('Rid', '=', $id)->first();
            //inserting values to activity table
            //below is superadmin codes
            $activity = "Resubmitted Risk where Risk Name is " . $riskname . " and where Area is " . $riskarea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);
            //superadmin code ends

            Session::flash('msg', "Risk resubmitted for approval Successfully");

            // shifted mail service to helper function
            $status = NewRiskAddedEmailHelper::sendEmail($id);

            if(!$status){
                Session::flash('risk_notification_warning', 'Risk resubmitted. Its email notification could not be completed. Please contact your administrator; do not resubmit again.');
                return redirect()->route('risk.index');
            }

            $models = DB::table('modenadisable')->First();
            return redirect('user/riskmgmt')->with('model', $models);
        }
    }

    //risk register
    public function regrisk(Request $request)
    {
        //below is superadmin related codes
        $riskmanagement = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($riskmanagement) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 1, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0]);
        } else {
            $riskmanagement = DB::table('modpageload')->where('id', 1)->First();
            $riskmanagement->riskmanagement = $riskmanagement->riskmanagement + 1;
            DB::table('modpageload')->update(['riskmanagement' => $riskmanagement->riskmanagement]);
        }
        //superadmin code ends

        $models = DB::table('modenadisable')->First();

        //--------------------Register------------
        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $email = DB::table('riskreviewers')->pluck('email');
        $users = DB::table('users')->get();
        $data = Risk::with(['department', 'riskreviewer'])
            ->whereIn('action_status', ['Under review', 'Rejected', 'Active Risk', 'Action under review', 'Completed', 'Awaiting Periodic Review'])
            ->orderBy('created_at', 'desc')
            ->orderby('riskgroup', 'asc')
            ->orderby('riskarea', 'asc')
            ->where(function ($query) use ($request) {
                if ($request->get('rank')) {
                    $query->where('rank', $request->get('rank'));
                }
                if ($request->get('riskgroup')) {
                    $query->where('riskgroup', $request->get('riskgroup'));
                }
            })
            ->get();

        $riskOwnerEmails = [];
        $riskOwnerData = [];
        if ($data) {
            $dataRiskOwnerEmails = $data->pluck('risk_owner')->toArray();
            $riskOwnerEmails = explode(',', implode(',', array_unique($dataRiskOwnerEmails)));
        }

        if ($riskOwnerEmails) {
            $riskOwners = User::select('email', 'id', 'name', 'employeeid', 'profile_picture')->whereIn('email', $riskOwnerEmails)->withTrashed()->get();
            if ($riskOwners) {
                foreach ($riskOwners as $riskOwner) {
                    if (!isset($riskOwnerData[$riskOwner->email])) {
                        $riskOwnerData[$riskOwner->email] = [
                            'id' => $riskOwner->id,
                            'name' => $riskOwner->name,
                            'employeeid' => $riskOwner->employeeid,
                            'profile_picture' => $riskOwner->profile_picture,
                        ];
                    }
                }
            }
        }

        $riskOwnerDataForFilter = [];
        foreach ($riskOwnerData as $value) {
            $riskOwnerDataForFilter[] = $value['name'] . '-' . $value['employeeid'];
        }
        $allRiskGroup = $data ? implode(',', array_unique($data->pluck('riskgroup')->toArray())) : '';
        $allRiskArea = $data ? implode(',', array_unique($data->pluck('department.departmentname')->toArray())) : '';
        $allRank = $data ? implode(',', array_unique($data->pluck('rank')->toArray())) : '';
        $allRiskOwner = $riskOwnerData ? implode(',', array_unique($riskOwnerDataForFilter)) : '';
        $allRiskResponse = $data ? implode(',', array_unique($data->where('risk_response', '!=', '')->pluck('risk_response')->toArray())) : '';
        $allProcessStage = $data ? implode(',', array_unique($data->whereNotIn('action_status', ['Action under review', 'Completed'])->pluck('action_status')->toArray())) : '';

        return view('user.risks.risk', compact(
            'allRiskGroup', 'allRiskArea', 'allRank', 'allRiskOwner', 'allRiskResponse', 'allProcessStage'
        ))
            ->with('model', $models)->withDepartments($departments)
            ->withMr($mr)->withUsers($users)->with('data', $data)->with('email', $email)
            ->withRiskOwnerData($riskOwnerData);
    }

    public function regriskDetail($id)
    {
        $models = DB::table('modenadisable')->first();
        $risk = Risk::with([
            'riskreviewer',
            'mitigations',
            'department',
            'division',
            'area',
            'opportunities',
            'reviewedByRisk',
            'postedByRisk',
            'approvedByRisk'
        ])->where('Rid', $id)->first();
        $posted_user_image = DB::table('users')->where('employeeid', $risk->postedby)->value('profile_picture');
        $riskOwnerData = [];
        $riskOwnerEmails = explode(',', $risk->risk_owner);
        $riskOwners = User::select('email', 'id', 'name', 'employeeid', 'profile_picture')->whereIn('email', $riskOwnerEmails)->withTrashed()->get();
        if ($riskOwners) {
            foreach ($riskOwners as $riskOwner) {
                if (!isset($riskOwnerData[$riskOwner->email])) {
                    $riskOwnerData[$riskOwner->email] = [
                        'id' => $riskOwner->id,
                        'name' => $riskOwner->name,
                        'employeeid' => $riskOwner->employeeid,
                        'profile_picture' => $riskOwner->profile_picture,
                    ];
                }
            }
        }
        $riskHistories = RiskHistory::where('Rid', '=', $id)
            ->get();
        $risk_comments = RiskComment::where('commentable_id', $id)
            ->where('is_deleted', false)
            ->get();
        $risk_comments_count = RiskComment::where('commentable_id', $id)->where('is_deleted', false)->count();
        return view('user.risks.detail', compact(
            'risk', 'posted_user_image', 'riskOwnerData', 'riskOwners', 'riskHistories','risk_comments','risk_comments_count'
        ))->with('model', $models);
    }

    public function regriskDetailPdf($id)
    {
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        $models = DB::table('modenadisable')->first();
        $risk = Risk::with([
            'riskreviewer',
            'mitigations',
            'department',
            'division',
            'area',
            'opportunities',
            'reviewedByRisk',
            'postedByRisk',
            'approvedByRisk'
        ])->where('Rid', $id)->first();
        $posted_user_image = DB::table('users')->where('employeeid', $risk->postedby)->value('profile_picture');
        $riskOwnerData = [];
        $riskOwnerEmails = explode(',', $risk->risk_owner);
        $riskOwners = User::select('email', 'id', 'name', 'employeeid', 'profile_picture')->whereIn('email', $riskOwnerEmails)->withTrashed()->get();
        if ($riskOwners) {
            foreach ($riskOwners as $riskOwner) {
                if (!isset($riskOwnerData[$riskOwner->email])) {
                    $riskOwnerData[$riskOwner->email] = [
                        'id' => $riskOwner->id,
                        'name' => $riskOwner->name,
                        'employeeid' => $riskOwner->employeeid,
                        'profile_picture' => $riskOwner->profile_picture,
                    ];
                }
            }
        }
        $riskHistories = RiskHistory::where('Rid', '=', $id)
            ->get();
        $clientDetails = ClientDetail::first();
        $pdf = \PDF::loadView('user.risks.detail_pdf',
            compact(
                'risk', 'posted_user_image', 'riskOwnerData', 'riskOwners', 'riskHistories','clientDetails'
            ));
        return $pdf->download('detail_' . $risk->Rid . '.pdf');
    }

    /**
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function show($id)
    {
        $response = [];
        try {
            $risk = Risk::with([
                'riskreviewer',
                'mitigations',
                'department',
                'division',
                'area',
                'opportunities',
                'reviewedByRisk',
                'postedByRisk',
                'approvedByRisk'
            ])->where('Rid', $id)->first();
            $riskOwnerEmails = explode(',', $risk->risk_owner);
            $riskOwnerData = [];
            $riskOwners = User::select('email', 'id', 'name', 'employeeid', 'profile_picture')->whereIn('email', $riskOwnerEmails)->get();
            if ($riskOwners) {
                foreach ($riskOwners as $riskOwner) {
                    if (!isset($riskOwnerData[$riskOwner->email])) {
                        $riskOwnerData[$riskOwner->email] = [
                            'id' => $riskOwner->id,
                            'name' => $riskOwner->name,
                            'employeeid' => $riskOwner->employeeid,
                            'profile_picture' => $riskOwner->profile_picture,
                        ];
                    }
                }
            }

            $response['success'] = true;
            $response['view'] = view('user.risks._risk_detail', [
                'value' => $risk,
                'riskowners' => $riskOwnerEmails,
                'riskOwnerData' => $riskOwnerData,
                'rr' => $risk->Rid
            ])->render();
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
        }

        return $response;
    }

    /**
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function createMitigation($id)
    {
        $response = [];
        try {
            $risk = Risk::where('Rid', $id)->first();
            // $actionOwners = User::pluck('name', 'id')->toArray();
            $actionOwners = User::where('is_email_verified',true)->orderBy('name', 'asc')->where('approved',true)->pluck('name', 'id')->toArray();
            $departments = DB::table('departments')->get();
            $riskData = Risk::whereIn('action_status', ['Under review', 'Rejected', 'Active Risk', 'Action under review', 'Completed'])
                ->orderby('riskgroup', 'asc')
                ->orderby('riskarea', 'asc')
                ->get();

            $response['success'] = true;
            if (!env('FORCE_HTTPS', false)) {
                $url = isset($mitigation) ? url('/user/riskmgmt/mitigations/' . $mitigation->id) : url('/user/riskmgmt/mitigations');
            } else {
                $url = isset($mitigation) ? secure_url('/user/riskmgmt/mitigations/' . $mitigation->id) : secure_url('/user/riskmgmt/mitigations');
            }
            Log::info('Mitigation URL: ' . $url);
            Log::info('Mitigation [FORCE_HTTPS]: ' . (env('FORCE_HTTPS', false) ? "true" : "false"));
            $deletedRisks = collect();
            $response['view'] = view('user.risks.mitigation._form', [
                'riskId' => $risk->Rid,
                'actionOwners' => $actionOwners,
                'departments' => $departments,
                'data' => $riskData,
                'deletedRisks' => $deletedRisks,
            ])->render();
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
        }

        return $response;
    }

    //risk register
    public function closedrisks()
    {
        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $email = DB::table('riskreviewers')->pluck('email');

        $models = DB::table('modenadisable')->First();

        $users = User::orderBy('name')->get();
        //------------------closed--------

        $data2 = Risk::where('action_status', '=', 'Closed')->orderby('date', 'DESC')->get();
        return view('user.risks.closedrisks')->with('data2', $data2)->withDepartments($departments)->withMr($mr)->with('email', $email)->with('model', $models)->with('users', $users);
    }

    //filter
    public function filtr(Request $req)
    {
        $email = DB::table('riskreviewers')->pluck('email');
        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $risky = null;
        $liky = null;
        $risky = $req->input('risk_owner');
        $liky = $req->input('rank');

        $data = Risk::where(function ($query) use ($req) {
            $query->where('risk_owner', '!=', 'null')
                ->where('risk_owner', '=', $req->input('risk_owner'))
                ->whereIn('action_status', ['Under review', 'Rejected', 'Active Risk', 'Action under review', 'Completed']);
        })
            ->orwhere(function ($query1) use ($req) {
                $query1->where('rank', '!=', 'null')
                    ->where('rank', '=', $req->input('rank'))
                    ->whereIn('action_status', ['Under review', 'Rejected', 'Active Risk', 'Action under review', 'Completed']);
            })
            ->orderby('riskgroup', 'asc')
            ->orderby('riskarea', 'asc')
            ->get()
            ->setpath('');
        $data->appends(array(
            'risk_owner' => $req->input('risk_owner'),
            'rank' => $req->input('rank'),
        ));
        //-------------------My risks----------
        //   $ID=Auth::user()->id;

        //   $poss=DB::table('users')->where('id',$ID)->get();
        //   foreach ($poss as $dat){
        //       $e = $dat->email;
        //       // dd($e);
        //   }
        $e = Auth::user()->email;
        //   $riskowner=DB::select("select *  from risks");
        //   // dd( $riskowner);
        //   foreach($riskowner as $dat)
        //   {

        $mydep = Auth::user()->deptid;
        $mydep_id = Departments::where('departmentname', $mydep)->value('id');
        // dd($mydep);
        $data1 = Risk::where('riskarea', $mydep_id)
        //   ->where('risk_owner','=',$e)
            ->whereIn('action_status', ['Active Risk', 'Action under review'])
            ->get();
        // dd($data);
        $dep = Departments::all();

        //   }
        //------------------closed--------

        $data2 = Risk::where('action_status', '=', 'Closed')->orderby('date', 'DESC')->paginate(75);

        //------------Analysis-------------

        //------strategic    - High / Moderate / Low
        $chart = new RiskAnalysis;
        $chart->title("Strategic");
        $chart->labels(['High', 'Moderate', 'Low']);
        $str_high = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                         and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                        ");
        // dd($str_high);
        $str_moderate = DB::select("select COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN risknames AS s
                            ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                            and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                            ");
        $str_low = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                        and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                        ");
        $total = DB::select("select COUNT(r.rank)AS number
                            FROM   risks AS r
                            right JOIN risknames AS s
                            ON r.riskgroup= s.riskgroups
                            and  r.status='YES' and r.action_status!='Closed'
                            ");

        $highstr = [];
        foreach ($str_high as $key) {
            array_push($highstr, $key->number);
        }
        $moderatestr = [];
        foreach ($str_moderate as $key) {
            array_push($moderatestr, $key->number);
        }
        $lowstr = [];
        foreach ($str_low as $key) {
            array_push($lowstr, $key->number);
        }
        $totalstr = [];
        foreach ($total as $key) {
            array_push($totalstr, $key->number);
        }
        // $highper= floor( $highstr[0] / $totalstr[0] *100) ;
        // $medper= floor( $moderatestr[0] / $totalstr[0] *100 ) ;
        // $lowper= floor( $lowstr[0] / $totalstr[0] *100 ) ;
        $chart->dataset('', 'doughnut', [$highstr, $moderatestr, $lowstr])
        // ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $chart->displayAxes(false);

        //------operational    - High / Moderate / Low
        $charto = new RiskAnalysis;
        $charto->title("Operational");
        $charto->labels(['High', 'Moderate', 'Low']);
        $opr_high = DB::select("select COUNT(r.rank)AS number
                         FROM  risks AS r
                         right JOIN risknames AS s
                         ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                          and  r.rank='High' and r.status='yes'and r.action_status!='Closed'
                         ");
        // dd($str_high);
        $opr_moderate = DB::select("select COUNT(r.rank)AS number
                             FROM  risks AS r
                             right JOIN risknames AS s
                             ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                             and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                             ");
        $opr_low = DB::select("select COUNT(r.rank)AS number
                         FROM  risks AS r
                         right JOIN risknames AS s
                         ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                         and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                         ");
        $total = DB::select("select COUNT(r.rank)AS number
                             FROM   risks AS r
                             right JOIN risknames AS s
                             ON r.riskgroup= s.riskgroups
                             and  r.status='YES' and r.action_status!='Closed'
                             ");

        $highopr = [];
        foreach ($opr_high as $key) {
            array_push($highopr, $key->number);
        }
        $moderateopr = [];
        foreach ($opr_moderate as $key) {
            array_push($moderateopr, $key->number);
        }
        $lowopr = [];
        foreach ($opr_low as $key) {
            array_push($lowopr, $key->number);
        }
        $totalopr = [];
        foreach ($total as $key) {
            array_push($totalopr, $key->number);
        }
        //  $highper= floor( $highopr[0] / $totalopr[0] *100) ;
        //  $medper= floor( $moderateopr[0] / $totalopr[0] *100 ) ;
        //  $lowper= floor( $lowopr[0] / $totalopr[0] *100 ) ;
        $charto->dataset('', 'doughnut', [$highopr, $moderateopr, $lowopr])
        //  ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $charto->displayAxes(false);

        //------Financial    - High / Moderate / Low
        $chartf = new RiskAnalysis;
        $chartf->title("Financial");
        $chartf->labels(['High', 'Moderate', 'Low']);
        $f_high = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                     and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                    ");

        $f_moderate = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                        and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                        ");
        $f_low = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                    and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                    ");
        $total = DB::select("select COUNT(r.rank)AS number
                        FROM   risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= s.riskgroups
                        and  r.status='YES' and r.action_status!='Closed'
                        ");

        $highf = [];
        foreach ($f_high as $key) {
            array_push($highf, $key->number);
        }
        $moderatef = [];
        foreach ($f_moderate as $key) {
            array_push($moderatef, $key->number);
        }
        $lowf = [];
        foreach ($f_low as $key) {
            array_push($lowf, $key->number);
        }
        $totalf = [];
        foreach ($total as $key) {
            array_push($totalf, $key->number);
        }
        // $highper= floor( $highf[0] / $totalf[0] *100) ;
        // $medper= floor( $moderatef[0] / $totalf[0] *100 ) ;
        // $lowper= floor( $lowf[0] / $totalf[0] *100 ) ;
        $chartf->dataset('', 'doughnut', [$highf, $moderatef, $lowf])
        // ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $chartf->displayAxes(false);

        //------Compliance    - High / Moderate / Low
        $chartc = new RiskAnalysis;
        $chartc->title("Compliance");
        $chartc->labels(['High', 'Moderate', 'Low']);
        $c_high = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                     and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                    ");

        $c_moderate = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                        and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                        ");
        $c_low = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                    and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                    ");
        $total = DB::select("select COUNT(r.rank)AS number
                        FROM   risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= s.riskgroups
                        and  r.status='YES' and r.action_status!='Closed'
                        ");

        $highc = [];
        foreach ($c_high as $key) {
            array_push($highc, $key->number);
        }
        $moderatec = [];
        foreach ($c_moderate as $key) {
            array_push($moderatec, $key->number);
        }
        $lowc = [];
        foreach ($c_low as $key) {
            array_push($lowc, $key->number);
        }
        $totalc = [];
        foreach ($total as $key) {
            array_push($totalc, $key->number);
        }
        // $highper= floor( $highc[0] / $totalc[0] *100) ;
        // $medper= floor( $moderatec[0] / $totalc[0] *100 ) ;
        // $lowper= floor( $lowc[0] / $totalc[0] *100 ) ;
        $chartc->dataset('', 'doughnut', [$highc, $moderatec, $lowc])
        // ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $chartc->displayAxes(false);

        $chart1 = new RiskAnalysis;
        $chart1->title("Total Risks-Department Wise");
        $processes = DB::select("select departmentname from departments order by departmentname ASC");
        // dd($processes);
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->departmentname);
        }
        // dd($processes);
        $chart1->labels($process);
        // dd($processes);
        $phigh = DB::select("select s.departmentname,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='high' and r.status='yes' and r.action_status!='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");
        // dd($phigh);
        $pmoderate = DB::select("select s.departmentname,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='moderate' and r.status='yes' and r.action_status!='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");
        $plow = DB::select("select s.departmentname,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='low' and r.status='yes' and r.action_status!='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");

        $tot = DB::select("select s.departmentname,COUNT(r.riskarea)AS number
                           FROM  risks AS r
                           right JOIN departments AS s
                           ON r.riskarea= s.id
                            and r.status='yes' and r.action_status!='Closed'
                           group by s.departmentname
                           order by s.departmentname ASC
                          ");
        $totp = [];
        foreach ($tot as $key) {
            array_push($totp, $key->number);
        }

        $closed = DB::select("select s.departmentname,COUNT(r.action_status)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and r.action_status='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
        ");
        $closep = [];
        foreach ($closed as $key) {
            array_push($closep, $key->number);
        }

        $highp = [];
        foreach ($phigh as $key) {
            array_push($highp, $key->number);
        }
        $moderatep = [];
        foreach ($pmoderate as $key) {
            array_push($moderatep, $key->number);
        }
        $lowp = [];
        foreach ($plow as $key) {
            array_push($lowp, $key->number);
        }
        $chart1->dataset('Total', 'bar', $totp)->backgroundcolor('#787478');
        $chart1->dataset('High', 'bar', $highp)->backgroundcolor('#f7533e');
        $chart1->dataset('Moderate', 'bar', $moderatep)->backgroundcolor('#FFD700');
        $chart1->dataset('Low', 'bar', $lowp)->backgroundcolor('#a6ff00');
        $chart1->height(400);

        // dd($processes);

        $chart2 = new RiskAnalysis;
        $chart2->title("High Risks-Department Wise");
        $chart2->labels($process);
        $chart2->dataset('High', 'bar', $highp)->backgroundcolor('#f7533e');
        $chart2->height(400);

        $chart3 = new RiskAnalysis;
        $chart3->title("Moderate Risks-Department Wise");
        $chart3->labels($process);
        $chart3->dataset('Moderate', 'bar', $moderatep)->backgroundcolor('#FFD700');
        $chart2->height(400);

        $chart4 = new RiskAnalysis;
        $chart4->title("Low Risks-Department Wise");
        $chart4->labels($process);
        $chart4->dataset('Low', 'bar', $lowp)->backgroundcolor('#a6ff00');
        $chart4->height(400);

        $chart5 = new RiskAnalysis;
        $chart5->title("Closed Risks-Department Wise");
        $chart5->labels($process);
        $chart5->dataset('Closed', 'bar', $closep)->backgroundcolor('#2887ed');
        $chart5->height(400);
        $models = DB::table('modenadisable')->First();
        return view('user.risks.viewrisk', compact('data', 'departments', 'mr'))->with('charto', $charto)->with('chartf', $chartf)->with('chartc', $chartc)->with('chart5', $chart5)->with('chart4', $chart4)->with('chart3', $chart3)->with('chart2', $chart2)->with('chart1', $chart1)->with('chart', $chart)->with('data2', $data2)->with('data1', $data1)->with('email', $email)->with('model', $models);
    }

    //initial review
    //approve
    public function adds()
    {
        if (isset($_GET['val'])) {
            $Rid = $_GET['val'];
            $data = Risk::where('Rid', $Rid)->get();
            $mn =  User::where('is_email_verified',true)->where('approved',true)->orderBy('name')->get();
            $hml = DB::table('hml')->get();
            $strategy = DB::table('riskstrategies')->get();
            $models = DB::table('modenadisable')->First();
            return view('user/risks/updaterisk')->withData($data)->with('hml', $hml)->with('mn', $mn)->with('strategy', $strategy)->with('Rid', $Rid)->with('model', $models);
        }
    }
    public function updaterisk(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'riskowner' => 'required',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $approvedby = Auth::user()->employeeid;
            $reviewedby = Auth::user()->employeeid;
            $Status = "YES";
            $Rid = $req->input('posted');
            $riskname = $req->input('riskname');
            $riskdec = $req->input('riskdec');
            $riskdri = $req->input('riskdri');
            $riskscr = $req->input('riskscr');
            $risk_response = $req->input('risk_response');
            $risk_owner = $req->input('riskowner');
            $impa = $req->input('impa');
            $like = $req->input('like');

            if ($like == "High") {
                $value1 = 3;
            } elseif ($like == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($impa == "High") {
                $value2 = 3;
            } elseif ($impa == "Moderate") {
                $value2 = 2;
            } else {
                $value2 = 1;
            }

            if (($value1 * $value2) > 5)
            //$rank1 = "High";
            {
                $rank1 = 3;
            } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6)
            //$rank1 = "Moderate";
            {
                $rank1 = 2;
            } else
            //$rank1 = "Low";
            {
                $rank1 = 1;
            }

            $rank1_value = null;

            switch ($rank1) {
                case 3:
                    $rank1_value = 'High';
                    break;
                case 2:
                    $rank1_value = 'Moderate';
                    break;
                case 1:
                    $rank1_value = 'Low';
                    break;

            }

            //$now = date('d-m-Y h:i:s A', time());
            $now = date('Y-m-d H:i:s', time());

            $data = array(
                'Status' => $Status, 'riskname' => $riskname, 'riskdec' => $riskdec,
                'riskdri' => $riskdri, 'riskscr' => $riskscr, 'risk_response' => $risk_response,
                'risk_owner' => implode(',',$risk_owner), 'action_status' => 'Active Risk',
                'impa' => $impa, 'like' => $like, 'rank' => $rank1_value, 'review_date' => $now,
                'approved_by' => $approvedby, 'reviewed_by' => $reviewedby,
                'existing_controls' => $req->input('existing_controls'),
            );

            //inserting values to activity table super admin codes
            $activity = "Risk is Approved with action status as Active Risk  where Riskname is " . $riskname;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);

            $rid_old = Risk::where('Rid', $Rid)->value('Rid');
            $impact = Risk::where('Rid', $Rid)->value('impa');
            if ($impact == $impa) {
                $impa_old = null;
            } else {
                $impa_old = $impact;
            }
            $likelihood = Risk::where('Rid', $Rid)->value('like');
            if ($likelihood == $like) {
                $like_old = null;
            } else {
                $like_old = $likelihood;
            }
            $riskrank = Risk::where('Rid', $Rid)->value('rank');
            if ($riskrank == $rank1) {
                $rank_old = null;
            } else {
                $rank_old = $this->helper($riskrank);
            }
            if ($like_old != null && $impa_old != null) {
                $riskhistory = array(
                    'Rid' => $rid_old,
                    'impa' => $impa_old,
                    'like' => $like_old,
                    'rank' => $rank_old,
                    'impa_updated' => $impa,
                    'like_updated' => $like,
                    'rank_updated' => $rank1,
                    'action' => 'Intial Review',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                );

                DB::table('riskhistory')
                    ->insert($riskhistory);
            }
            Risk::where('Rid', $Rid)
                ->update($data);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/riskmgmt')->with('model', $models);
        }
    }

    public function analysis()
    {
        //------------Analysis-------------

        //------strategic    - High / Moderate / Low
        $chart = new RiskAnalysis;
        $chart->title("Strategic");
        $chart->labels(['High', 'Moderate', 'Low']);
        $str_high = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                         and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                        ");
        // dd($str_high);
        $str_moderate = DB::select("select COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN risknames AS s
                            ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                            and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                            ");
        $str_low = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                        and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                        ");
        $total = DB::select("select COUNT(r.rank)AS number
                            FROM   risks AS r
                            right JOIN risknames AS s
                            ON r.riskgroup= s.riskgroups
                            and  r.status='YES' and r.action_status!='Closed'
                            ");

        $highstr = [];
        foreach ($str_high as $key) {
            array_push($highstr, $key->number);
        }
        $moderatestr = [];
        foreach ($str_moderate as $key) {
            array_push($moderatestr, $key->number);
        }
        $lowstr = [];
        foreach ($str_low as $key) {
            array_push($lowstr, $key->number);
        }
        $totalstr = [];
        foreach ($total as $key) {
            array_push($totalstr, $key->number);
        }
        // $highper= floor( $highstr[0] / $totalstr[0] *100) ;
        // $medper= floor( $moderatestr[0] / $totalstr[0] *100 ) ;
        // $lowper= floor( $lowstr[0] / $totalstr[0] *100 ) ;
        $chart->dataset('', 'doughnut', [$highstr, $moderatestr, $lowstr])
        // ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $chart->displayAxes(false);

        //------operational    - High / Moderate / Low
        $charto = new RiskAnalysis;
        $charto->title("Operational");
        $charto->labels(['High', 'Moderate', 'Low']);
        $opr_high = DB::select("select COUNT(r.rank)AS number
                         FROM  risks AS r
                         right JOIN risknames AS s
                         ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                          and  r.rank='High' and r.status='yes'and r.action_status!='Closed'
                         ");
        // dd($str_high);
        $opr_moderate = DB::select("select COUNT(r.rank)AS number
                             FROM  risks AS r
                             right JOIN risknames AS s
                             ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                             and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                             ");
        $opr_low = DB::select("select COUNT(r.rank)AS number
                         FROM  risks AS r
                         right JOIN risknames AS s
                         ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                         and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                         ");
        $total = DB::select("select COUNT(r.rank)AS number
                             FROM   risks AS r
                             right JOIN risknames AS s
                             ON r.riskgroup= s.riskgroups
                             and  r.status='YES' and r.action_status!='Closed'
                             ");

        $highopr = [];
        foreach ($opr_high as $key) {
            array_push($highopr, $key->number);
        }
        $moderateopr = [];
        foreach ($opr_moderate as $key) {
            array_push($moderateopr, $key->number);
        }
        $lowopr = [];
        foreach ($opr_low as $key) {
            array_push($lowopr, $key->number);
        }
        $totalopr = [];
        foreach ($total as $key) {
            array_push($totalopr, $key->number);
        }
        //  $highper= floor( $highopr[0] / $totalopr[0] *100) ;
        //  $medper= floor( $moderateopr[0] / $totalopr[0] *100 ) ;
        //  $lowper= floor( $lowopr[0] / $totalopr[0] *100 ) ;
        $charto->dataset('', 'doughnut', [$highopr, $moderateopr, $lowopr])
        //  ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $charto->displayAxes(false);

        //------Financial    - High / Moderate / Low
        $chartf = new RiskAnalysis;
        $chartf->title("Financial");
        $chartf->labels(['High', 'Moderate', 'Low']);
        $f_high = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                     and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                    ");

        $f_moderate = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                        and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                        ");
        $f_low = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                    and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                    ");
        $total = DB::select("select COUNT(r.rank)AS number
                        FROM   risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= s.riskgroups
                        and  r.status='YES' and r.action_status!='Closed'
                        ");

        $highf = [];
        foreach ($f_high as $key) {
            array_push($highf, $key->number);
        }
        $moderatef = [];
        foreach ($f_moderate as $key) {
            array_push($moderatef, $key->number);
        }
        $lowf = [];
        foreach ($f_low as $key) {
            array_push($lowf, $key->number);
        }
        $totalf = [];
        foreach ($total as $key) {
            array_push($totalf, $key->number);
        }
        // $highper= floor( $highf[0] / $totalf[0] *100) ;
        // $medper= floor( $moderatef[0] / $totalf[0] *100 ) ;
        // $lowper= floor( $lowf[0] / $totalf[0] *100 ) ;
        $chartf->dataset('', 'doughnut', [$highf, $moderatef, $lowf])
        // ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $chartf->displayAxes(false);

        //------Compliance    - High / Moderate / Low
        $chartc = new RiskAnalysis;
        $chartc->title("Compliance");
        $chartc->labels(['High', 'Moderate', 'Low']);
        $c_high = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                     and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                    ");

        $c_moderate = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                        and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                        ");
        $c_low = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                    and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                    ");
        $total = DB::select("select COUNT(r.rank)AS number
                        FROM   risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= s.riskgroups
                        and  r.status='YES' and r.action_status!='Closed'
                        ");

        $highc = [];
        foreach ($c_high as $key) {
            array_push($highc, $key->number);
        }
        $moderatec = [];
        foreach ($c_moderate as $key) {
            array_push($moderatec, $key->number);
        }
        $lowc = [];
        foreach ($c_low as $key) {
            array_push($lowc, $key->number);
        }
        $totalc = [];
        foreach ($total as $key) {
            array_push($totalc, $key->number);
        }
        // $highper= floor( $highc[0] / $totalc[0] *100) ;
        // $medper= floor( $moderatec[0] / $totalc[0] *100 ) ;
        // $lowper= floor( $lowc[0] / $totalc[0] *100 ) ;
        $chartc->dataset('', 'doughnut', [$highc, $moderatec, $lowc])
        // ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $chartc->displayAxes(false);

        $chart1 = new RiskAnalysis;
        $chart1->title("Total Risks-Department Wise");
        $processes = DB::select("select departmentname, code from departments order by departmentname ASC");
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->code);
        }
        $chart1->labels($process);
        $departmentCodes = Departments::select('code')->orderBy('code')->pluck('code');

        $phigh = DB::select("select s.code,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='high' and r.status='yes' and r.action_status!='Closed'
                            group by s.code
                            order by s.code ASC
                           ");
        $pmoderate = DB::select("select s.code,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='moderate' and r.status='yes' and r.action_status!='Closed'
                            group by s.code
                            order by s.code ASC
                           ");
        $plow = DB::select("select s.code,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='low' and r.status='yes' and r.action_status!='Closed'
                            group by s.code
                            order by s.code ASC
                           ");

        $highp = [];
        foreach ($phigh as $key) {
            array_push($highp, $key->number);
        }
        $moderatep = [];
        foreach ($pmoderate as $key) {
            array_push($moderatep, $key->number);
        }
        $lowp = [];
        foreach ($plow as $key) {
            array_push($lowp, $key->number);
        }
        $riskInfoByGroup = $this->countRiskByGroup();
        $riskInfoByDepartment = $this->countRiskByDepartment();
        $riskInfoByDepartmentDataset = [];
        $riskInfoByDepartmentData = [];
        foreach ($riskInfoByDepartment as $item) {
            if (isset($riskInfoByDepartmentDataset[$item->rank])) {
                $riskInfoByDepartmentDataset[$item->rank][$item->code] = $item->number;
            } else {
                $riskInfoByDepartmentDataset[$item->rank] = [$item->code => $item->number];
            }
            $riskInfoByDepartmentData[] = $item->code;
        }

        $chartDataByDepartment = $this->makeChartOfRiskAnalysis($riskInfoByDepartmentData, "", $riskInfoByDepartmentDataset);
        $riskDataChart = [];
        $riskDataChartOfDepartment = [];
        foreach ($riskInfoByGroup as $item) {
            if ($item->riskgroup) {
                if (isset($riskDataChart[$item->riskgroup])
                && isset($riskDataChart[$item->riskgroup][$item->rank])
            ) {
                    $riskDataChart[$item->riskgroup][$item->rank][$item->code] = $item->number;
                } else {
                    $riskDataChart[$item->riskgroup][$item->rank] = [
                        $item->code => $item->number
                    ];
                }
            }

            if (isset($riskDataChartOfDepartment[$item->riskgroup])) {
                $riskDataChartOfDepartment[$item->riskgroup][] = $item->code;
            } else {
                $riskDataChartOfDepartment[$item->riskgroup] = [$item->code];
            }
        }

        $chartData = [];
        foreach (RiskAnalysis::RISK_GROUP as $item) {
            if (isset($riskDataChartOfDepartment[$item])) {
                $chartData[$item] = $this->makeChartOfRiskAnalysis($riskDataChartOfDepartment[$item], $item, $riskDataChart[$item]);
            }
        }

        $models = DB::table('modenadisable')->First();
        $totalRisk = Risk::count('Rid');
        $totalHighRisk = Risk::where('rank', 'High')->count('Rid');
        $totalModerateRisk = Risk::where('rank', 'Moderate')->count('Rid');
        $totalLowRisk = Risk::where('rank', 'Low')->count('Rid');
        $totalRiskByGroups = Risk::select('riskgroup', 'rank', DB::raw('COUNT(Rid) as total'))
            ->groupBy('riskgroup', 'rank')
            ->get();
        $totalRiskByGroupData = [];
        foreach ($totalRiskByGroups as $item) {
            if (!isset($totalRiskByGroupData[$item->riskgroup])) {
                $totalRiskByGroupData[$item->riskgroup] = [
                    $item->rank => $item->total
                ];
            } else {
                $totalRiskByGroupData[$item->riskgroup][$item->rank] = $item->total;
            }
        }

        return view('user.risks.analysis', compact(
            'chart', 'chart1', 'chartData',
            'totalRisk', 'totalHighRisk', 'totalModerateRisk',
            'totalLowRisk', 'totalRiskByGroupData', 'chartDataByDepartment'
            )
        )
            ->with('charto', $charto)
            ->with('chartf', $chartf)
            ->with('chartc', $chartc)
            ->with('model', $models);
    }

    protected function countRiskByGroup()
    {
        return DB::select("select r.riskgroup, s.code, r.rank, COUNT(r.rank) AS number
            FROM  risks AS r
            RIGHT JOIN departments AS s
            ON r.riskarea = s.id
            group by r.riskgroup, s.code, r.rank
            order by s.code ASC
       ");
    }

    protected function countRiskByDepartment()
    {
        return DB::select("select s.code, r.rank, COUNT(r.rank) AS number
            FROM  risks AS r
            RIGHT JOIN departments AS s
            ON r.riskarea = s.id
            group by s.code, r.rank
            order by s.code ASC
       ");
    }

    protected function makeChartOfRiskAnalysis($departmentCodes, $riskGroup, $dataset)
    {
        $process = array_unique($departmentCodes);
        $high = [];
        if (isset($dataset['High'])) {
            foreach ($process as $departmentCode) {
                if (isset($dataset['High'][$departmentCode])) {
                    $high[] = $dataset['High'][$departmentCode];
                } else {
                    $high[] = 0;
                }
            }
        }

        $moderate = [];
        if (isset($dataset['Moderate'])) {
            foreach ($process as $departmentCode) {
                if (isset($dataset['Moderate'][$departmentCode])) {
                    $moderate[] = $dataset['Moderate'][$departmentCode];
                } else {
                    $moderate[] = 0;
                }
            }
        }
        $low = [];
        if (isset($dataset['Low'])) {
            foreach ($process as $departmentCode) {
                if (isset($dataset['Low'][$departmentCode])) {
                    $low[] = $dataset['Low'][$departmentCode];
                } else {
                    $low[] = 0;
                }
            }
        }
        $labels = [];
        foreach ($process as $departmentCode) {
            $labels[] = $departmentCode;
        }
        $chart = new RiskAnalysis;
        $chart->title("Department Wise-Risk" . ($riskGroup ? "-" . $riskGroup : ""));
        $chart->labels($labels);
        $chart->dataset('High', 'bar', $high)->backgroundcolor('#EB564F')->color('#EB564F');
        $chart->dataset('Moderate', 'bar', $moderate)->backgroundcolor('#F7CE76')->color('#F7CE76');
        $chart->dataset('Low', 'bar', $low)->backgroundcolor('#48A09D')->color('#48A09D');
        $chart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);
        $chart->height(400);

        return $chart;
    }

    //dissapprove
    public function disapprove()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $models = DB::table('modenadisable')->First();
            return view('user.risks.disapprove')->withId($id)->with('model', $models);
        }
    }
    public function disapproving(Request $req)
    {
        //$now = date('d-m-Y h:i:s A', time());
        $now = date('Y-m-d H:i:s', time());
        $reviewedby = $req->input('review');
        $reason = $req->input('reason');
        $id = $req->input('id');
        $data = array(
            'status' => 'NO',
            'action_status' => 'Rejected',
            'disapprovereason' => $reason, 'reviewed_by' => $reviewedby, 'review_date' => $now,
        );
        Risk::where('Rid', $id)->update($data);
        $risk_added_person_employee_id = Risk::where('Rid', $id)->value('postedby');
        $risk_added_person = User::where('approved', true)->where('is_email_verified', true)->where('employeeid', $risk_added_person_employee_id)->first();
        $mails = EmailNotification::where('module','riskmanagement')
                        ->where('item','Risk Not Approved')
                        ->where('is_blocked',false)
                        ->first();
        if($mails)
        {
            $data = array(
                'disapprovereason' => $reason,
                'item' => $mails->item,
                'email_content' => $mails->email_content,
            );
            //$riskreviewers = Riskreviewer::pluck('email')->toArray();
             $riskreviewer_list = Riskreviewer::pluck('email')->toArray();
             $riskreviewers = User::whereIn('email',$riskreviewer_list)->pluck('email')->toArray();
            //Sending Mail To Risk Reviewers
            if(!empty($riskreviewers)){
                Mail::to($riskreviewers)->send(new RiskNotApproved($data));
            }
            if($risk_added_person && !in_array($risk_added_person->email, $riskreviewers)) {
                Mail::to($risk_added_person)->send(new RiskNotApproved($data));
            }
            $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
            if(count($userdatas1) > 0)
            {
                $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if(!empty($userdatas)){
                    Mail::to($userdatas)->send(new RiskNotApproved($data));
                }
            }
        }
        else
        {
            Session::flash('errmsg', 'Mail Sending Blocked for Risk Not Approved');
            return redirect()->route('risk.index');
        }
        //inserting values to activity table superadmin codes
        $riskname = Risk::where('Rid', $id)->pluck('riskname');
        $riskarea = Risk::where('Rid', $id)->pluck('riskarea')->toArray();
        $riskarea = Departments::whereIn('id', $riskarea)->pluck('departmentname');
        $activity = "Risk is Disapproved with action status as Rejected where Riskname is " . $riskname . " and where risk area as " . $riskarea;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now_time = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
        ViewActivity::insert($datas);

        $models = DB::table('modenadisable')->First();
        return redirect('/user/riskmgmt')->with('model', $models);
    }

    //reopen adter closed
    public function reopenrisk()
    {
        if (isset($_GET['val'])) {
            $Rid = $_GET['val'];
            $data = Risk::where('Rid', $Rid)->get();
            $mn = DB::table('departments')->get();
            $strategy = DB::table('riskstrategies')->get();
            $departments = Departments::all();
            $models = DB::table('modenadisable')->First();
            return view('user.risks.reopenrisk')->with('model', $models)->withData($data)->with('mn', $mn)->with('strategy', $strategy)->with('departments', $departments);
        }
    }

    public function reopen(Request $req)
    {
        $validator = Validator::make($req->all(), [
            // 'mitigating_action' => 'required|max:300'

        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $Rid = $req->input('posted');
            $riskgroup = $req->input('riskgp');
            $riskarea = $req->input('riskar');
            $riskname = $req->input('risknm');
            $risk_response = $req->input('risk_response');
            $mitigating_action = $req->input('mitigating_action');
            // $risk_owner = $req->input('riskowner');
            // $actionone= $req->input('actionone');
            // $s=$req->input("action");
            // $action_target=$actionone.$s;
            //$action_status = $req->input('action_status');
            $impa = $req->input('impa');
            $like = $req->input('like');
            $reason = $req->input('reasontoreopen');

            if ($like == "High") {
                $value1 = 3;
            } elseif ($like == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($impa == "High") {
                $value2 = 3;
            } elseif ($impa == "Moderate") {
                $value2 = 2;
            } else {
                $value2 = 1;
            }

            if (($value1 * $value2) > 5) {
                $rank1 = "High";
            } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                $rank1 = "Moderate";
            } else {
                $rank1 = "Low";
            }

            //$now = date('d-m-Y h:i:s A', time());
            $now = date('Y-m-d H:i:s', time());
            $data = array(
                'risk_response' => $risk_response, 'mitigating_action' => $mitigating_action,
                // 'risk_owner'=>$risk_owner,
                // 'action_target'=>$action_target,
                'action_status' => 'Under review', 'actionst' => 'Not Started-0%',
                'impa' => $impa, 'like' => $like, 'rank' => $rank1, 'review_date' => $now, 'reason' => $reason,
            );
            Risk::where('Rid', $Rid)
                ->update($data);

            //super adminn code
            $riskname = Risk::where('Rid', $Rid)->pluck('riskname');
            $riskarea = Risk::where('Rid', $Rid)->pluck('riskarea')->toArray();
            $riskarea = Departments::whereIn('id', $riskarea)->pluck('departmentname');
            $activity = "Risk is Reopened where Risk name is " . $riskname . " and where risk area as " . $riskarea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/riskmgmt')->with('model', $models);
        }
    }

    // public function getapp()
    //  {
    //      return view('user.risks.myactiondash');

    // }

    //(old-----risk review of action under review  status)
    //secondary review of active risk
    public function upd()
    {
        if (isset($_GET['val'])) {
            $Rid = $_GET['val'];
            $rskgrp = DB::table('risknames')->get();
            $hml = DB::table('hml')->get();
            $data = Risk::where('Rid', $Rid)->get();
            $mn =  User::where('is_email_verified',true)->where('approved',true)->orderBy('name')->get();
            $models = DB::table('modenadisable')->First();
            $strategy = DB::table('riskstrategies')->get();
            $departments = DB::table('departments')->get();
            return view('user/risks/riskreview')
                ->withHml($hml)
                ->withRskgrp($rskgrp)
                ->withStrategy($strategy)
                ->withDepartments($departments)
                ->withData($data)
                ->with('mn',$mn)
                ->with('model', $models);
        }
    }
    public function updatereview(Request $req)
    {
        $validator = Validator::make($req->all(), [
            // 'mitigating_action' => 'required|max:300'

        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $Rid = $req->input('posted');
            // $reviewedby = $req->input('review');
            $reviewedby = Auth::user()->employeeid;

            $impa = $req->input('impa');
            $like = $req->input('like');
            if ($like == "High") {
                $value1 = 3;
            } elseif ($like == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($impa == "High") {
                $value2 = 3;
            } elseif ($impa == "Moderate") {
                $value2 = 2;
            } else {
                $value2 = 1;
            }

            if (($value1 * $value2) > 5) {
                $rank1 = 3;
            } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                $rank1 = 2;
            } else {
                $rank1 = 1;
            }

            $rank1_value = null;
            switch ($rank1) {
                case 3:
                    $rank1_value = "High";
                    break;
                case 2:
                    $rank1_value = "Moderate";
                    break;
                case 1:
                    $rank1_value = "Low";
                    break;
            }

            //$now = date('d-m-Y h:i:s A', time());
            $now = date('Y-m-d H:i:s', time());

            $rid_old = Risk::where('Rid', $Rid)->value('Rid');
            $impact = Risk::where('Rid', $Rid)->value('impa');
            if ($impact == $impa) {
                $impa_old = null;
            } else {
                $impa_old = $impact;
            }
            $likelihood = Risk::where('Rid', $Rid)->value('like');
            if ($likelihood == $like) {
                $like_old = null;
            } else {
                $like_old = $likelihood;
            }
            $riskrank = Risk::where('Rid', $Rid)->value('rank');
            if ($riskrank == $rank1) {
                $rank_old = null;
            } else {
                $rank_old = $this->helper($riskrank);
            }
            //if($like_old!=NULL && $impa_old!=NULL && $mitigating_action_old!=NULL)
            if ($like_old != null || $impa_old != null) {
                $riskhistory = array(
                    'Rid' => $rid_old,
                    'impa' => $impa_old,
                    'like' => $like_old,
                    'rank' => $rank_old,
                    'impa_updated' => $impa,
                    'like_updated' => $like,
                    'rank_updated' => $rank1,
                    'action' => 'Periodic Review',
                    'risk_owner_id' => User::where('email', Auth::user()->email)->first()->id,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                );

                DB::table('riskhistory')
                    ->insert($riskhistory);
            } else {
                $riskhistory = array(
                    'Rid' => $rid_old,
                    'action' => 'Periodic Review',
                    'risk_owner_id' => User::where('email', Auth::user()->email)->first()->id,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                );

                DB::table('riskhistory')
                    ->insert($riskhistory);
            }
            $data = array(
                'riskarea' => $req->input('riskarea'),
                'division_id' => $req->input('division'),
                'area_id' => $req->input('area'),
                'riskgroup' => $req->input('riskgroup'),
                'riskname' => $req->input('riskname'),
                'riskdec' => $req->input('riskdec'),
                'riskdri' => $req->input('riskdri'),
                'existing_controls' => $req->input('existing_controls'),
                'riskscr' => $req->input('riskscr'),
                'risk_response' => $req->input('risk_response'),
                'impa' => $impa,
                'like' => $like,
                'rank' => $rank1_value,
                'reviewed_by' => $reviewedby,
                'periodic_review_date' => now(),
                'action_status' => 'Active Risk',
                'existing_controls' => $req->input('existing_controls')
            );

            Risk::where('Rid', $Rid)
                ->update($data);

            //inserting values to activity table superadmin codes
            $riskname = Risk::where('Rid', $Rid)->pluck('riskname');
            $riskarea = Risk::where('Rid', $Rid)->pluck('riskarea')->toArray();
            $riskarea = Departments::whereIn('id', $riskarea)->pluck('departmentname');
            $activity = "Risk is Reviewed where Risk name is " . $riskname . " and where risk area" . $riskarea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/riskmgmt')->with('model', $models);
        }
    }

    // myrisk

    public function myrisk()
    {
        // $ID=Auth::user()->id;
        // $poss=DB::table('users')->where('id',$ID)->get();
        // foreach ($poss as $dat){
        //     $e = $dat->email;
        //     // dd($e);
        // }
        // $riskowner=DB::select("select *  from risks");
        // // dd( $riskowner);
        // foreach($riskowner as $dat)
        // {

        // $data= DB::table('risks')

        // ->where('risk_owner','=',$e)
        // ->whereIn('action_status',['Active Risk','Action under review'])
        // ->paginate(75);
        // // dd($data);
        // $dep=Departments::all();
        //-------------------My risks----------
        //   $ID=Auth::user()->id;

        //   $poss=DB::table('users')->where('id',$ID)->get();
        //   foreach ($poss as $dat){
        //       $e = $dat->email;
        //       // dd($e);
        //   }
        $e = Auth::user()->email;
        //   $riskowner=DB::select("select *  from risks");
        //   // dd( $riskowner);
        //   foreach($riskowner as $dat)
        //   {

        $mydep = Auth::user()->deptid;
        $mydep_id = Departments::where('departmentname', $mydep)->value('id');
        // dd($mydep);
        $data1 = Risk::where('riskarea', $mydep_name)
        //   ->where('risk_owner','=',$e)
            ->whereIn('action_status', ['Active Risk', 'Action under review'])
            ->get();
        // dd($data);
        $dep = Departments::all();

        //   }

        $models = DB::table('modenadisable')->First();
        return view('user/risk/myrisk')->withData($data)->withDepartments($dep)->with('model', $models);

        // }
        //   $models=DB::table('modenadisable')->First();
        //   return view('user.risks.notauth')->with('model',$models);

    }
    public function mysearch(Request $req)
    {

        $keyword = $req->input('keyword');
        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $email = DB::table('riskreviewers')->pluck('email');

        $ID = Auth::user()->id;
        $poss = DB::table('users')->where('id', $ID)->get();
        foreach ($poss as $dat) {
            $e = $dat->email;
            // dd($e);
        }
        $riskowner = DB::select("select *  from risks");
        // dd( $riskowner);
        foreach ($riskowner as $dat) {
            $data = Risk::where('risk_owner', '=', $e)
                ->whereIn('action_status', ['Active Risk', 'Action under review'])
                ->where(function ($query) use ($req) {
                    $query

                        ->where('riskgroup', '=', $req->input('keyword'))
                        ->orWhere('riskarea', '=', $req->input('keyword'))
                        ->orWhere('riskname', '=', $req->input('keyword'))
                        ->orWhere('riskdec', '=', $req->input('keyword'))
                        ->orWhere('riskdri', '=', $req->input('keyword'))
                        ->orWhere('date', '=', $req->input('keyword'))
                        ->orWhere('riskscr', '=', $req->input('keyword'))
                        ->orWhere('impa', '=', $req->input('keyword'))
                        ->orWhere('like', '=', $req->input('keyword'))
                        ->orWhere('rank', '=', $req->input('keyword'))
                        ->orWhere('status', '=', $req->input('keyword'))
                        ->orWhere('risk_response', '=', $req->input('keyword'))
                        ->orWhere('mitigating_action', '=', $req->input('keyword'))
                        ->orWhere('risk_owner', '=', $req->input('keyword'))
                        ->orWhere('action_target', '=', $req->input('keyword'))
                        ->orWhere('action_status', '=', $req->input('keyword'))
                        ->orWhere('actionst', '=', $req->input('keyword'));
                })

                ->paginate(100)
                ->setpath('');

            $data->appends(array(
                'department' => $req->input('keyword'),
            ));
            //---------------rest of code------------
            //-------------------My risks----------
            //   $ID=Auth::user()->id;

            //   $poss=DB::table('users')->where('id',$ID)->get();
            //   foreach ($poss as $dat){
            //       $e = $dat->email;
            //       // dd($e);
            //   }
            $e = Auth::user()->email;
            //   $riskowner=DB::select("select *  from risks");
            //   // dd( $riskowner);
            //   foreach($riskowner as $dat)
            //   {

            $mydep = Auth::user()->deptid;
            $mydep_id = Departments::where('departmentname', $mydep)->value('id');
            $data1 = Risk::where('riskarea', $mydep_id)
            //   ->where('risk_owner','=',$e)
                ->whereIn('action_status', ['Active Risk', 'Action under review'])
                ->get();
            $dep = Departments::all();

            //   }
            //------------------closed--------

            $data2 = Risk::where('action_status', '=', 'Closed')->orderby('date', 'DESC')->get();

            //------------Analysis-------------

            //------strategic    - High / Moderate / Low
            $chart = new RiskAnalysis;
            $chart->title("Strategic");
            $chart->labels(['High', 'Moderate', 'Low']);
            $str_high = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                         and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                        ");
            // dd($str_high);
            $str_moderate = DB::select("select COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN risknames AS s
                            ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                            and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                            ");
            $str_low = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                        and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                        ");
            $total = DB::select("select COUNT(r.rank)AS number
                            FROM   risks AS r
                            right JOIN risknames AS s
                            ON r.riskgroup= s.riskgroups
                            and  r.status='YES' and r.action_status!='Closed'
                            ");

            $highstr = [];
            foreach ($str_high as $key) {
                array_push($highstr, $key->number);
            }
            $moderatestr = [];
            foreach ($str_moderate as $key) {
                array_push($moderatestr, $key->number);
            }
            $lowstr = [];
            foreach ($str_low as $key) {
                array_push($lowstr, $key->number);
            }
            $totalstr = [];
            foreach ($total as $key) {
                array_push($totalstr, $key->number);
            }
            // $highper= floor( $highstr[0] / $totalstr[0] *100) ;
            // $medper= floor( $moderatestr[0] / $totalstr[0] *100 ) ;
            // $lowper= floor( $lowstr[0] / $totalstr[0] *100 ) ;
            $chart->dataset('', 'doughnut', [$highstr, $moderatestr, $lowstr])
            // ->color(['#f7533e','#FFD700','#a6ff00'])
                ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
            $chart->displayAxes(false);

            //------operational    - High / Moderate / Low
            $charto = new RiskAnalysis;
            $charto->title("Operational");
            $charto->labels(['High', 'Moderate', 'Low']);
            $opr_high = DB::select("select COUNT(r.rank)AS number
                         FROM  risks AS r
                         right JOIN risknames AS s
                         ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                          and  r.rank='High' and r.status='yes'and r.action_status!='Closed'
                         ");
            // dd($str_high);
            $opr_moderate = DB::select("select COUNT(r.rank)AS number
                             FROM  risks AS r
                             right JOIN risknames AS s
                             ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                             and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                             ");
            $opr_low = DB::select("select COUNT(r.rank)AS number
                         FROM  risks AS r
                         right JOIN risknames AS s
                         ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                         and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                         ");
            $total = DB::select("select COUNT(r.rank)AS number
                             FROM   risks AS r
                             right JOIN risknames AS s
                             ON r.riskgroup= s.riskgroups
                             and  r.status='YES' and r.action_status!='Closed'
                             ");

            $highopr = [];
            foreach ($opr_high as $key) {
                array_push($highopr, $key->number);
            }
            $moderateopr = [];
            foreach ($opr_moderate as $key) {
                array_push($moderateopr, $key->number);
            }
            $lowopr = [];
            foreach ($opr_low as $key) {
                array_push($lowopr, $key->number);
            }
            $totalopr = [];
            foreach ($total as $key) {
                array_push($totalopr, $key->number);
            }
            //  $highper= floor( $highopr[0] / $totalopr[0] *100) ;
            //  $medper= floor( $moderateopr[0] / $totalopr[0] *100 ) ;
            //  $lowper= floor( $lowopr[0] / $totalopr[0] *100 ) ;
            $charto->dataset('', 'doughnut', [$highopr, $moderateopr, $lowopr])
            //  ->color(['#f7533e','#FFD700','#a6ff00'])
                ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
            $charto->displayAxes(false);

            //------Financial    - High / Moderate / Low
            $chartf = new RiskAnalysis;
            $chartf->title("Financial");
            $chartf->labels(['High', 'Moderate', 'Low']);
            $f_high = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                     and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                    ");

            $f_moderate = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                        and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                        ");
            $f_low = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                    and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                    ");
            $total = DB::select("select COUNT(r.rank)AS number
                        FROM   risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= s.riskgroups
                        and  r.status='YES' and r.action_status!='Closed'
                        ");

            $highf = [];
            foreach ($f_high as $key) {
                array_push($highf, $key->number);
            }
            $moderatef = [];
            foreach ($f_moderate as $key) {
                array_push($moderatef, $key->number);
            }
            $lowf = [];
            foreach ($f_low as $key) {
                array_push($lowf, $key->number);
            }
            $totalf = [];
            foreach ($total as $key) {
                array_push($totalf, $key->number);
            }
            // $highper= floor( $highf[0] / $totalf[0] *100) ;
            // $medper= floor( $moderatef[0] / $totalf[0] *100 ) ;
            // $lowper= floor( $lowf[0] / $totalf[0] *100 ) ;
            $chartf->dataset('', 'doughnut', [$highf, $moderatef, $lowf])
            // ->color(['#f7533e','#FFD700','#a6ff00'])
                ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
            $chartf->displayAxes(false);

            //------Compliance    - High / Moderate / Low
            $chartc = new RiskAnalysis;
            $chartc->title("Compliance");
            $chartc->labels(['High', 'Moderate', 'Low']);
            $c_high = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                     and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                    ");

            $c_moderate = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                        and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                        ");
            $c_low = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                    and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                    ");
            $total = DB::select("select COUNT(r.rank)AS number
                        FROM   risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= s.riskgroups
                        and  r.status='YES' and r.action_status!='Closed'
                        ");

            $highc = [];
            foreach ($c_high as $key) {
                array_push($highc, $key->number);
            }
            $moderatec = [];
            foreach ($c_moderate as $key) {
                array_push($moderatec, $key->number);
            }
            $lowc = [];
            foreach ($c_low as $key) {
                array_push($lowc, $key->number);
            }
            $totalc = [];
            foreach ($total as $key) {
                array_push($totalc, $key->number);
            }
            // $highper= floor( $highc[0] / $totalc[0] *100) ;
            // $medper= floor( $moderatec[0] / $totalc[0] *100 ) ;
            // $lowper= floor( $lowc[0] / $totalc[0] *100 ) ;
            $chartc->dataset('', 'doughnut', [$highc, $moderatec, $lowc])
            // ->color(['#f7533e','#FFD700','#a6ff00'])
                ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
            $chartc->displayAxes(false);

            $chart1 = new RiskAnalysis;
            $chart1->title("Total Risks-Department Wise");
            $processes = DB::select("select departmentname from departments order by departmentname ASC");
            // dd($processes);
            $process = [];
            foreach ($processes as $key) {
                array_push($process, $key->departmentname);
            }
            // dd($processes);
            $chart1->labels($process);
            // dd($processes);
            $phigh = DB::select("select s.departmentname,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='high' and r.status='yes' and r.action_status!='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");
            // dd($phigh);
            $pmoderate = DB::select("select s.departmentname,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='moderate' and r.status='yes' and r.action_status!='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");
            $plow = DB::select("select s.departmentname,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='low' and r.status='yes' and r.action_status!='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");

            $tot = DB::select("select s.departmentname,COUNT(r.riskarea)AS number
                           FROM  risks AS r
                           right JOIN departments AS s
                           ON r.riskarea= s.id
                            and r.status='yes' and r.action_status!='Closed'
                           group by s.departmentname
                           order by s.departmentname ASC
                          ");
            $totp = [];
            foreach ($tot as $key) {
                array_push($totp, $key->number);
            }

            $closed = DB::select("select s.departmentname,COUNT(r.action_status)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and r.action_status='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
        ");
            $closep = [];
            foreach ($closed as $key) {
                array_push($closep, $key->number);
            }

            $highp = [];
            foreach ($phigh as $key) {
                array_push($highp, $key->number);
            }
            $moderatep = [];
            foreach ($pmoderate as $key) {
                array_push($moderatep, $key->number);
            }
            $lowp = [];
            foreach ($plow as $key) {
                array_push($lowp, $key->number);
            }
            $chart1->dataset('Total', 'bar', $totp)->backgroundcolor('#787478');
            $chart1->dataset('High', 'bar', $highp)->backgroundcolor('#f7533e');
            $chart1->dataset('Moderate', 'bar', $moderatep)->backgroundcolor('#FFD700');
            $chart1->dataset('Low', 'bar', $lowp)->backgroundcolor('#a6ff00');
            $chart1->height(400);

            // dd($processes);

            $chart2 = new RiskAnalysis;
            $chart2->title("High Risks-Department Wise");
            $chart2->labels($process);
            $chart2->dataset('High', 'bar', $highp)->backgroundcolor('#f7533e');
            $chart2->height(400);

            $chart3 = new RiskAnalysis;
            $chart3->title("Moderate Risks-Department Wise");
            $chart3->labels($process);
            $chart3->dataset('Moderate', 'bar', $moderatep)->backgroundcolor('#FFD700');
            $chart2->height(400);

            $chart4 = new RiskAnalysis;
            $chart4->title("Low Risks-Department Wise");
            $chart4->labels($process);
            $chart4->dataset('Low', 'bar', $lowp)->backgroundcolor('#a6ff00');
            $chart4->height(400);

            $chart5 = new RiskAnalysis;
            $chart5->title("Closed Risks-Department Wise");
            $chart5->labels($process);
            $chart5->dataset('Closed', 'bar', $closep)->backgroundcolor('#2887ed');
            $chart5->height(400);

            $models = DB::table('modenadisable')->First();
            return view('user.risks.myrisk', compact('data', 'mr', 'departments', 'email'))->with('model', $models)->with('charto', $charto)->with('chartf', $chartf)->with('chartc', $chartc)->with('chart5', $chart5)->with('chart4', $chart4)->with('chart3', $chart3)->with('chart2', $chart2)->with('chart1', $chart1)->with('chart', $chart)->with('data2', $data2)->with('data1', $data1);
        }
    }

    //edit action for risk owner
    public function own()
    {
        if (isset($_GET['val'])) {
            $Rid = $_GET['val'];
            $data = Risk::where('Rid', $Rid)->get();
            $models = DB::table('modenadisable')->First();
            return view('user.risks.ownrespo')->withData($data)->with('model', $models);
        }
    }

    public function responsive(Request $req)
    {
        $Rid = $req->input('posted');
        $remark = $req->input('remarks');
        // $group=$req->input('Riskgp');
        // $area=$req->input('riskar');
        // $name=$req->input('risknm');
        $description = $req->input('riskdec');
        $drive = $req->input('riskdri');
        $source = $req->input('riskscr');
        $mitigating = $req->input('mitigating_action');

        $rid_old = Risk::where('Rid', $Rid)->value('Rid');

        $mitigatingactions = Risk::where('Rid', $Rid)->value('mitigating_action');
        if ($mitigatingactions == $mitigating) {
            $mitigating_action_old = null;
        } else {
            $mitigating_action_old = $mitigatingactions;
        }
        if ($mitigating_action_old != null) {
            $riskhistory = array(
                'Rid' => $rid_old,
                'mitigating_action' => $mitigating_action_old,
                'action' => 'Edit Action',
                'mitigating_action_updated' => $mitigating,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );

            DB::table('riskhistory')
                ->insert($riskhistory);
        }

        $data = array(
            'riskdec' => $description,
            'riskdri' => $drive, 'riskscr' => $source, 'mitigating_action' => $mitigating,
            'remarks' => $remark,
            'action_status' => 'Action under review',
        );
        Risk::where('Rid', $Rid)
            ->update($data);
        $models = DB::table('modenadisable')->First();
        return redirect('/user/riskmgmt')->with('model', $models);
    }

    //completed 100%
    public function hundred()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(
                'action_status' => 'Completed',
                'actionst' => 'Completed-100%',
            );
            Risk::where('Rid', '=', $id)
                ->update($data);
            // dd($data);
            // echo "<script>alert('Issue Sent For Closing Approval Successfully')</script>";
            //inserting values to activity table superadmin codes
            $riskname = Risk::where('Rid', $id)->pluck('riskname');
            $riskarea = Risk::where('Rid', $id)->pluck('riskarea')->toArray();
            $riskarea = Departments::whereIn('id', $riskarea)->value('departmentname');
            $activity = "Risk is closed with Action Status as Completed-100% where Risk name is " . $riskname . " and where risk area" . $riskarea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/riskmgmt')->with('model', $models);
        }
    }
    //partiallycompleted 50%
    public function five()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(

                'actionst' => 'Partially Completed-50%',
            );
            Risk::where('Rid', '=', $id)
                ->update($data);
            $riskname = Risk::where('Rid', $id)->pluck('riskname');
            $riskarea = Risk::where('Rid', $id)->pluck('riskarea')->toArray();
            $riskarea = Departments::whereIn('id', $riskarea)->value('departmentname');
            $activity = "Action Status is Updated as Partially Completed-50% where Risk name is " . $riskname . " and where risk area" . $riskarea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/riskmgmt')->with('model', $models);
        }
    }
    //progress
    public function two()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(

                'actionst' => 'Progress-25%',
            );
            Risk::where('Rid', '=', $id)
                ->update($data);
            // dd($data);
            // echo "<script>alert('Issue Sent For Closing Approval Successfully')</script>";
            //inserting values to activity table
            $riskname = Risk::where('Rid', $id)->pluck('riskname');
            $riskarea = Risk::where('Rid', $id)->pluck('riskarea')->toArray();
            $riskarea = Departments::whereIn('id', $riskarea)->value('departmentname');
            $activity = "Action Status is Updated as  Progress-50% where Risk name" . $riskname . " and where risk area" . $riskarea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/riskmgmt')->with('model', $models);
        }
    }

    //not started
    public function zero()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(

                'actionst' => 'Not Started-0%',
            );
            Risk::where('Rid', '=', $id)
                ->update($data);
            // dd($data);
            // echo "<script>alert('Issue Sent For Closing Approval Successfully')</script>";
            $models = DB::table('modenadisable')->First();
            return redirect('/user/riskmgmt')->with('model', $models);
        }
    }

    //closurereview for need more action
    public function closurereview()
    {
        if (isset($_GET['val'])) {
            $Rid = $_GET['val'];

            $data = Risk::where('Rid', $Rid)->get();
            $mn = DB::table('departments')->get();

            $models = DB::table('modenadisable')->First();
            return view('user/risks/closure')->withData($data)->with('mn', $mn)->with('Rid', $Rid)->with('model', $models);
        }
    }
    public function closure(Request $req)
    {

        $validator = Validator::make($req->all(), [
            // 'action' => 'max:300',

        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $Rid = $req->input('Rid');

            //$reviewtime = date('d-m-Y h:i:s A', time());
            $reviewtime = date('Y-m-d H:i:s', time());

            $impa = $req->input('impa');
            $like = $req->input('like');
            if ($like == "High") {
                $value1 = 3;
            } elseif ($like == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($impa == "High") {
                $value2 = 3;
            } elseif ($impa == "Moderate") {
                $value2 = 2;
            } else {
                $value2 = 1;
            }

            if (($value1 * $value2) > 5) {
                $rank1 = "High";
            } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                $rank1 = "Moderate";
            } else {
                $rank1 = "Low";
            }

            $data = array(
                'impa' => $impa, 'like' => $like, 'rank' => $rank1,
                //    'ndareason'=>$ndareason,
                'action_status' => "Active Risk", 'review_date' => $reviewtime, 'actionst' => "Partially Completed-50%",
            );
            Risk::where('Rid', $Rid)
                ->update($data);

            //inserting values to activity table
            $riskname = Risk::where('Rid', $Rid)->pluck('riskname');
            $riskarea = Risk::where('Rid', $Rid)->pluck('riskarea')->toArray();
            $riskarea = Departments::whereIn('id', $riskarea)->value('departmentname');
            $activity = "Action Status is Updated as Partially Completed-50% where Risk name is" . $riskname . " and where risk area" . $riskarea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/riskmgmt')->with('model', $models);
        }
    }

    //  public function initialrisk()
    // {
    //     $data=DB::table('risks')
    //                 ->where('status',"NO")
    //                 ->orderby('date','DESC')
    //                 ->paginate(10);
    //     return view('user.risks.initialrisk')->with('data',$data);
    // }
    // public function reviewrisk()
    // {
    //     $data=DB::table('risks') ->wherein('action_status',array('response','ongoing'))
    //                         ->orderby('review_date','ASC')
    //                         ->paginate(10);
    //     $department = DB::table('departments')->get();
    //     return view('user.risks.reviewrisk')->with('data',$data)->with('department',$department);
    // }
    public function viewdet()
    {
        if (isset($_GET['val'])) {
            $Rid = $_GET['val'];
            $data = Risk::where('Rid', $Rid)->get();
            $models = DB::table('modenadisable')->First();
            return view('user/risk/view')->withData($data)->with('model', $models);
        }
    }
    public function closedrisk()
    {
        $departments = Departments::all();
        $data = Risk::where('action_status', '=', 'Closed')->orderby('date', 'DESC')->paginate(75);
        $models = DB::table('modenadisable')->First();
        return view('user.risks.closedrisk')->with('data', $data)->with('departments', $departments)->with('model', $models);
    }
    public function closedsearch(Request $req)
    {

        $keyword = $req->input('keyword');

        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $email = DB::table('riskreviewers')->pluck('email');
        $data = Risk::where('action_status', '=', 'Closed')
            ->orderby('Rid', 'desc')

            ->where(function ($query) use ($req) {
                $query

                    ->where('riskgroup', '=', $req->input('keyword'))
                    ->orWhere('riskarea', '=', $req->input('keyword'))
                    ->orWhere('riskname', '=', $req->input('keyword'))
                    ->orWhere('riskdec', '=', $req->input('keyword'))
                    ->orWhere('riskdri', '=', $req->input('keyword'))
                    ->orWhere('date', '=', $req->input('keyword'))
                    ->orWhere('riskscr', '=', $req->input('keyword'))
                    ->orWhere('impa', '=', $req->input('keyword'))
                    ->orWhere('like', '=', $req->input('keyword'))
                    ->orWhere('rank', '=', $req->input('keyword'))
                    ->orWhere('status', '=', $req->input('keyword'))
                    ->orWhere('risk_response', '=', $req->input('keyword'))
                    ->orWhere('mitigating_action', '=', $req->input('keyword'))
                    ->orWhere('risk_owner', '=', $req->input('keyword'))
                    ->orWhere('action_target', '=', $req->input('keyword'))
                    ->orWhere('action_status', '=', $req->input('keyword'))
                    ->orWhere('actionst', '=', $req->input('keyword'));
            })

            ->paginate(100)
            ->setpath('');

        $data->appends(array(
            'department' => $req->input('keyword'),
        ));

        $models = DB::table('modenadisable')->First();

        //-------------------My risks----------
        //   $ID=Auth::user()->id;

        //   $poss=DB::table('users')->where('id',$ID)->get();
        //   foreach ($poss as $dat){
        //       $e = $dat->email;
        //       // dd($e);
        //   }
        $e = Auth::user()->email;
        //   $riskowner=DB::select("select *  from risks");
        //   // dd( $riskowner);
        //   foreach($riskowner as $dat)
        //   {

        $mydep = Auth::user()->deptid;
        $mydep = Departments::where('departmentname', $mydep)->value('id');
        $data1 = Risk::where('riskarea', $mydep)
        //   ->where('risk_owner','=',$e)
            ->whereIn('action_status', ['Active Risk', 'Action under review'])
            ->get();
        $dep = Departments::all();

        //   }
        //------------------closed--------

        $data2 = Risk::where('action_status', '=', 'Closed')->orderby('date', 'DESC')->paginate(75);

        //------------Analysis-------------

        //------strategic    - High / Moderate / Low
        $chart = new RiskAnalysis;
        $chart->title("Strategic");
        $chart->labels(['High', 'Moderate', 'Low']);
        $str_high = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                         and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                        ");
        // dd($str_high);
        $str_moderate = DB::select("select COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN risknames AS s
                            ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                            and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                            ");
        $str_low = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Strategic' and s.riskgroups='Strategic'
                        and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                        ");
        $total = DB::select("select COUNT(r.rank)AS number
                            FROM   risks AS r
                            right JOIN risknames AS s
                            ON r.riskgroup= s.riskgroups
                            and  r.status='YES' and r.action_status!='Closed'
                            ");

        $highstr = [];
        foreach ($str_high as $key) {
            array_push($highstr, $key->number);
        }
        $moderatestr = [];
        foreach ($str_moderate as $key) {
            array_push($moderatestr, $key->number);
        }
        $lowstr = [];
        foreach ($str_low as $key) {
            array_push($lowstr, $key->number);
        }
        $totalstr = [];
        foreach ($total as $key) {
            array_push($totalstr, $key->number);
        }
        // $highper= floor( $highstr[0] / $totalstr[0] *100) ;
        // $medper= floor( $moderatestr[0] / $totalstr[0] *100 ) ;
        // $lowper= floor( $lowstr[0] / $totalstr[0] *100 ) ;
        $chart->dataset('', 'doughnut', [$highstr, $moderatestr, $lowstr])
        // ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $chart->displayAxes(false);

        //------operational    - High / Moderate / Low
        $charto = new RiskAnalysis;
        $charto->title("Operational");
        $charto->labels(['High', 'Moderate', 'Low']);
        $opr_high = DB::select("select COUNT(r.rank)AS number
                         FROM  risks AS r
                         right JOIN risknames AS s
                         ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                          and  r.rank='High' and r.status='yes'and r.action_status!='Closed'
                         ");
        // dd($str_high);
        $opr_moderate = DB::select("select COUNT(r.rank)AS number
                             FROM  risks AS r
                             right JOIN risknames AS s
                             ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                             and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                             ");
        $opr_low = DB::select("select COUNT(r.rank)AS number
                         FROM  risks AS r
                         right JOIN risknames AS s
                         ON r.riskgroup= 'Operational' and s.riskgroups='Operational'
                         and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                         ");
        $total = DB::select("select COUNT(r.rank)AS number
                             FROM   risks AS r
                             right JOIN risknames AS s
                             ON r.riskgroup= s.riskgroups
                             and  r.status='YES' and r.action_status!='Closed'
                             ");

        $highopr = [];
        foreach ($opr_high as $key) {
            array_push($highopr, $key->number);
        }
        $moderateopr = [];
        foreach ($opr_moderate as $key) {
            array_push($moderateopr, $key->number);
        }
        $lowopr = [];
        foreach ($opr_low as $key) {
            array_push($lowopr, $key->number);
        }
        $totalopr = [];
        foreach ($total as $key) {
            array_push($totalopr, $key->number);
        }
        //  $highper= floor( $highopr[0] / $totalopr[0] *100) ;
        //  $medper= floor( $moderateopr[0] / $totalopr[0] *100 ) ;
        //  $lowper= floor( $lowopr[0] / $totalopr[0] *100 ) ;
        $charto->dataset('', 'doughnut', [$highopr, $moderateopr, $lowopr])
        //  ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $charto->displayAxes(false);

        //------Financial    - High / Moderate / Low
        $chartf = new RiskAnalysis;
        $chartf->title("Financial");
        $chartf->labels(['High', 'Moderate', 'Low']);
        $f_high = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                     and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                    ");

        $f_moderate = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                        and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                        ");
        $f_low = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Financial' and s.riskgroups='Financial'
                    and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                    ");
        $total = DB::select("select COUNT(r.rank)AS number
                        FROM   risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= s.riskgroups
                        and  r.status='YES' and r.action_status!='Closed'
                        ");

        $highf = [];
        foreach ($f_high as $key) {
            array_push($highf, $key->number);
        }
        $moderatef = [];
        foreach ($f_moderate as $key) {
            array_push($moderatef, $key->number);
        }
        $lowf = [];
        foreach ($f_low as $key) {
            array_push($lowf, $key->number);
        }
        $totalf = [];
        foreach ($total as $key) {
            array_push($totalf, $key->number);
        }
        // $highper= floor( $highf[0] / $totalf[0] *100) ;
        // $medper= floor( $moderatef[0] / $totalf[0] *100 ) ;
        // $lowper= floor( $lowf[0] / $totalf[0] *100 ) ;
        $chartf->dataset('', 'doughnut', [$highf, $moderatef, $lowf])
        // ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $chartf->displayAxes(false);

        //------Compliance    - High / Moderate / Low
        $chartc = new RiskAnalysis;
        $chartc->title("Compliance");
        $chartc->labels(['High', 'Moderate', 'Low']);
        $c_high = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                     and  r.rank='High' and r.status='yes' and r.action_status!='Closed'
                    ");

        $c_moderate = DB::select("select COUNT(r.rank)AS number
                        FROM  risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                        and  r.rank='Moderate' and r.status='YES' and r.action_status!='Closed'
                        ");
        $c_low = DB::select("select COUNT(r.rank)AS number
                    FROM  risks AS r
                    right JOIN risknames AS s
                    ON r.riskgroup= 'Compliance' and s.riskgroups='Compliance'
                    and  r.rank='Low' and r.status='YES' and r.action_status!='Closed'
                    ");
        $total = DB::select("select COUNT(r.rank)AS number
                        FROM   risks AS r
                        right JOIN risknames AS s
                        ON r.riskgroup= s.riskgroups
                        and  r.status='YES' and r.action_status!='Closed'
                        ");

        $highc = [];
        foreach ($c_high as $key) {
            array_push($highc, $key->number);
        }
        $moderatec = [];
        foreach ($c_moderate as $key) {
            array_push($moderatec, $key->number);
        }
        $lowc = [];
        foreach ($c_low as $key) {
            array_push($lowc, $key->number);
        }
        $totalc = [];
        foreach ($total as $key) {
            array_push($totalc, $key->number);
        }
        // $highper= floor( $highc[0] / $totalc[0] *100) ;
        // $medper= floor( $moderatec[0] / $totalc[0] *100 ) ;
        // $lowper= floor( $lowc[0] / $totalc[0] *100 ) ;
        $chartc->dataset('', 'doughnut', [$highc, $moderatec, $lowc])
        // ->color(['#f7533e','#FFD700','#a6ff00'])
            ->backgroundcolor(['#f7533e', '#FFD700', '#a6ff00']);
        $chartc->displayAxes(false);

        $chart1 = new RiskAnalysis;
        $chart1->title("Total Risks-Department Wise");
        $processes = DB::select("select departmentname from departments order by departmentname ASC");
        // dd($processes);
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->departmentname);
        }
        // dd($processes);
        $chart1->labels($process);
        // dd($processes);
        $phigh = DB::select("select s.departmentname,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='high' and r.status='yes' and r.action_status!='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");
        // dd($phigh);
        $pmoderate = DB::select("select s.departmentname,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='moderate' and r.status='yes' and r.action_status!='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");
        $plow = DB::select("select s.departmentname,COUNT(r.rank)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and  r.rank='low' and r.status='yes' and r.action_status!='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");

        $tot = DB::select("select s.departmentname,COUNT(r.riskarea)AS number
                           FROM  risks AS r
                           right JOIN departments AS s
                           ON r.riskarea= s.id
                            and r.status='yes' and r.action_status!='Closed'
                           group by s.departmentname
                           order by s.departmentname ASC
                          ");
        $totp = [];
        foreach ($tot as $key) {
            array_push($totp, $key->number);
        }

        $closed = DB::select("select s.departmentname,COUNT(r.action_status)AS number
                            FROM  risks AS r
                            right JOIN departments AS s
                            ON r.riskarea= s.id
                            and r.action_status='Closed'
                            group by s.departmentname
                            order by s.departmentname ASC
        ");
        $closep = [];
        foreach ($closed as $key) {
            array_push($closep, $key->number);
        }

        $highp = [];
        foreach ($phigh as $key) {
            array_push($highp, $key->number);
        }
        $moderatep = [];
        foreach ($pmoderate as $key) {
            array_push($moderatep, $key->number);
        }
        $lowp = [];
        foreach ($plow as $key) {
            array_push($lowp, $key->number);
        }
        $chart1->dataset('Total', 'bar', $totp)->backgroundcolor('#787478');
        $chart1->dataset('High', 'bar', $highp)->backgroundcolor('#f7533e');
        $chart1->dataset('Moderate', 'bar', $moderatep)->backgroundcolor('#FFD700');
        $chart1->dataset('Low', 'bar', $lowp)->backgroundcolor('#a6ff00');
        $chart1->height(400);

        // dd($processes);

        $chart2 = new RiskAnalysis;
        $chart2->title("High Risks-Department Wise");
        $chart2->labels($process);
        $chart2->dataset('High', 'bar', $highp)->backgroundcolor('#f7533e');
        $chart2->height(400);

        $chart3 = new RiskAnalysis;
        $chart3->title("Moderate Risks-Department Wise");
        $chart3->labels($process);
        $chart3->dataset('Moderate', 'bar', $moderatep)->backgroundcolor('#FFD700');
        $chart2->height(400);

        $chart4 = new RiskAnalysis;
        $chart4->title("Low Risks-Department Wise");
        $chart4->labels($process);
        $chart4->dataset('Low', 'bar', $lowp)->backgroundcolor('#a6ff00');
        $chart4->height(400);

        $chart5 = new RiskAnalysis;
        $chart5->title("Closed Risks-Department Wise");
        $chart5->labels($process);
        $chart5->dataset('Closed', 'bar', $closep)->backgroundcolor('#2887ed');
        $chart5->height(400);

        return view('user.risks.closedrisk', compact('data', 'mr', 'departments', 'email'))->with('model', $models)->with('charto', $charto)->with('chartf', $chartf)->with('chartc', $chartc)->with('chart5', $chart5)->with('chart4', $chart4)->with('chart3', $chart3)->with('chart2', $chart2)->with('chart1', $chart1)->with('chart', $chart)->with('data2', $data2)->with('data1', $data1);
    }
    public function needmoreaction()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(
                'action_status' => 'needmoreaction',
            );
            Risk::where('Rid', '=', $id)
                ->update($data);
            // echo "<script>alert('Issue Sent For Closing Approval Successfully')</script>";
            return redirect('/user/risks/risk');
        }
    }

    public function closed()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //$reviewtime = date('d-m-Y h:i:s A', time());
            $reviewtime = date('Y-m-d H:i:s', time());
            $data = array(
                'action_status' => 'Closed', 'review_date' => $reviewtime,
            );
            Risk::where('Rid', '=', $id)
                ->update($data);

            //inserting values to activity table
            $riskname = Risk::where('Rid', $id)->pluck('riskname');
            $riskarea = Risk::where('Rid', $id)->pluck('riskarea')->toArray();
            $riskarea = Departments::whereIn('id', $riskarea)->value('departmentname');
            $activity = "Action Status is Updated as Closed where Risk name is" . $riskname . " and where risk area" . $riskarea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);

            // echo "<script>alert('Issue Sent For Closing Approval Successfully')</script>";
            $models = DB::table('modenadisable')->First();
            return redirect('/user/riskmgmt')->with('model', $models);
        }
    }
    public function notification()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $risk = Risk::where('Rid', $id)->first();

            Risk::where('Rid', $id)->update([
                'last_escalated_time' => now(),
                'number_of_escalated_times' => $risk->number_of_escalated_times + 1,
            ]);

            $escalate = DB::table('escalateriskreceivers')->pluck('email')->toArray();
            $escalate = getNonDeletedUsersEmail($escalate);
            $riskgroup = $risk->riskgroup;
            $riskname = $risk->riskname;
            $riskarea = $risk->riskarea;
            $riskdescriptiion = $risk->riskdec;
            $riskdrive = $risk->riskdri;
            $risksource = $risk->riskscr;
            $impact = $risk->impa;
            $liklihood = $risk->like;
            $escalate_emails = EmailNotification::where('module', 'riskmanagement')
                    ->where('item', 'Escalate Risk')
                    ->where('is_blocked', false)
                    ->first();
            if ($escalate_emails) {
                $data = array(
                    'id' => $id,
                    'riskgroup' => $riskgroup,
                    'riskname' => $riskname,
                    'riskarea' => Departments::where('id', $riskarea)->value('departmentname'),
                    'riskdescription' => $riskdescriptiion,
                    'riskdrive' => $riskdrive,
                    'risksource' => $risksource,
                    'impact' => $impact,
                    'liklihood' => $liklihood,
                    'item' => $escalate_emails->item,
                    'email_content' => $escalate_emails->email_content,
                );
                if (count($escalate) > 0) {
                    try {
                        Mail::to($escalate)->send(new Escalaterisk($data));
                    } catch (\Exception $e) {
                        Log::info("Error sending email: " . $e->getMessage());
                    }
                }
                //sending mail for participants and management participants
                $escalate_userdatas = EmailNotificationsUserDetails::where('notification_id', $escalate_emails->id)->get();
                if (count($escalate_userdatas) > 0) {
                    $adminaddedusers = $escalate_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new Escalaterisk($data));
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for Escalate Risk. Please contact admin.');
            }
            //inserting values to activity table
            $riskname = Risk::where('Rid', $id)->pluck('riskname');
            $riskarea = Risk::where('Rid', $id)->pluck('riskarea')->toArray();
            $riskarea = Departments::whereIn('id', $riskarea)->value('departmentname');
            $activity = " Nottification sent for Escalate Risk where name is" . $riskname . " and where risk area" . $riskarea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);

            echo "<script>alert('Notification Sent to Escalate')</script>";
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    //delete request
    public function delete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $name = Risk::where('Rid', $id)->pluck('riskname');
            $activity = "A risk entry is Removed with Name " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);
            Risk::where('Rid',$id)->delete();
        }
    }

    //edit risk
    public function editreq()
    {

        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = Risk::where('Rid', $d)->first();
                }
                $n = $n + 1;
            }
        }
        $strategy = DB::table('riskstrategies')->get();
        $models = DB::table('modenadisable')->First();
        $rskgrp = DB::table('risknames')->get();
        return view('user/risks/editrequest')->withData($data)->withStrategy($strategy)->with('id', $id)->with('model', $models)->with('rskgrp',$rskgrp);
    }

    public function editrequest(Request $req)
    {
        $m = count($req->docnumber);

        $riskgroup = $req->input('riskgroup');
        $riskname = $req->input('riskname');
        $riskdec = $req->input('riskdec');
        $riskdri = $req->input('riskdri');
        $risksource = $req->input('source');
        $risk_response = $req->input('risk_response');
        $impact = $req->input('impact');
        $likelihood = $req->input('likelihood');
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = Risk::where('Rid', $d)->first();
            }
            $n = $n + 1;
        }
        $n = 0;

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->Rid == $s && $n < $m) {

                    if ($req->likelihood[$n] == "High") {
                        $value1 = 3;
                    } elseif ($req->likelihood[$n] == "Moderate") {
                        $value1 = 2;
                    } else {
                        $value1 = 1;
                    }

                    if ($req->impact[$n] == "High") {
                        $value2 = 3;
                    } elseif ($req->impact[$n] == "Moderate") {
                        $value2 = 2;
                    } else {
                        $value2 = 1;
                    }

                    if (($value1 * $value2) > 5) {
                        $rank1 = "High";
                    } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                        $rank1 = "Moderate";
                    } else {
                        $rank1 = "Low";
                    }

                    // $date= date('Y-m-d', strtotime('+1 day', strtotime($req->erevdate[$n])));
                    $data = array(

                        'riskgroup' => $req->riskgroup[$n],
                        'riskname' => $req->riskname[$n],
                        'riskdec' => $req->riskdec[$n],
                        'riskdri' => $req->riskdri[$n],
                        'riskscr' => $req->source[$n],
                        'risk_response' => $req->risk_response[$n],
                        'impa' => $req->impact[$n],
                        'like' => $req->likelihood[$n],
                        'rank' => $rank1,
                    );
                    Risk::where('Rid', $s)
                        ->update($data);
                    //insert data to activity table superadmin code
                    $activity = "Risk  is  Edited and Updated with new Details  where Risk ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $now_time = Carbon::now()->format('Y-m-d H:i:s');
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
                    ViewActivity::insert($datas);
                    //end of superadmin code

                }
            }

            $n = $n + 1;
        }

        $models = DB::table('modenadisable')->First();
        return redirect('/user/riskmgmt')->with('model', $models);
    }
    public function useredit(int $id)
    {
        $data = Risk::with('riskreviewer')->where('Rid', $id)->get();
        $riskreviewers = Riskreviewer::with('user')->whereHas('user')->orderBy('name', 'asc')->get();
        $mn = Departments::all();
        $users = User::all();
        $hml = DB::table('hml')->get();
        $riskinfo = DB::table('riskinfo')->get();
        $strategy = DB::table('riskstrategies')->get();
        $rskgrp = DB::table('risknames')->get();
        $models = Modenadisable::First();
        return view('user/risks/useredit')
            ->withData($data)
            ->with('riskreviewers', $riskreviewers)
            ->with('hml', $hml)
            ->with('users', $users)
            ->with('mn', $mn)
            ->with('id', $id)
            ->with('model', $models)
            ->withRiskinfo($riskinfo)
            ->withStrategy($strategy)
            ->withRskgrp($rskgrp);
    }
    public function usereditsubmit(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'riskname' => 'required|max:190',
            'riskarea' => 'required|not_in:0',
            'riskdec' => 'required|max:65435',
            'riskdri' => 'nullable|max:65435',
            'existing_controls' => 'nullable|max:65435',
            'riskreviewer_id' => 'required|integer|not_in:0'
        ], [], [
            'riskname' => 'Risk Name',
            'riskarea' => 'Risk Area',
            'riskdec' => 'Risk Description',
            'riskdri' => 'Risk Drivers',
            'existing_controls' => 'Existing Controls',
            'riskreviewer_id' => 'Risk Reviewer'
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput($req->all())->with('model', $models);
        } else {
            $id        = $req->input('riskid');
            $risk      = Risk::where('Rid', $id)->firstOrFail();
            $riskgroup = $req->input('riskgroup');

            if ($riskgroup != $risk->riskgroup) {
                $lastRiskItemOfGroup = Risk::where('riskgroup', $riskgroup)->orderBy('count', 'desc')->first();
                if ($lastRiskItemOfGroup) {
                    $count = $lastRiskItemOfGroup->count + 1;
                } else {
                    $count = "1";
                }
            } else {
                $count = $risk->count;
            }

            $riskarea = $req->input('riskarea');
            //$riskarea_name = Departments::where('id', $riskarea)->value('departmentname');
            $riskname         = $req->input('riskname');
            $riskdec          = $req->input('riskdec');
            $riskdri          = $req->input('riskdri');
            $existingControls = $req->input('existing_controls');
            $riskscr          = $req->input('riskscr');
        //        $hod = $req->input('hod');
        //        if ($hod == "3") {
        //            $risk_response = $req->input('risk_response');
        //        }
        //        if ($hod == "4") {
        //            $risk_response = " ";
        //            $mitigating_action = " ";
        //        }
        //        if ($hod == "0") {
        //            $risk_response = Risk::where('Rid', $id)->value('risk_response');
        //        }
            $impa = $req->input('impa');
            $like = $req->input('like');
            if ($like == "High") {
                $value1 = 3;
            } elseif ($like == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($impa == "High") {
                $value2 = 3;
            } elseif ($impa == "Moderate") {
                $value2 = 2;
            } else {
                $value2 = 1;
            }

            if (($value1 * $value2) > 5) {
                $rank1 = "High";
            } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                $rank1 = "Moderate";
            } else {
                $rank1 = "Low";
            }

            $data = array(
                'count' => $count,
                'riskgroup' => $riskgroup,
                'riskarea' => $riskarea,
                'division_id' => $req->input('division'),
                'area_id' => $req->input('area'),
                'riskname' => $riskname,
                'riskdec' => $riskdec,
                'riskdri' => $riskdri,
                'riskscr' => $riskscr,
                'riskreviewer_id' => $req->input('riskreviewer_id'),
                'impa' => $impa,
                'like' => $like,
                'rank' => $rank1,
                'existing_controls' => $existingControls, 'risk_response' => $req->input('risk_response'),
            );
            Risk::where('Rid', $id)->update($data);

            $code   = strtoupper(substr($riskgroup, 0, 3));
            $dep_id = $code . $count;
            $data3  = array('grp_id' => $dep_id);
            Risk::where('count', $count)
                ->where('riskgroup', $riskgroup)
                ->update($data3);

            // shifted mail service to helper function
            $status = NewRiskAddedEmailHelper::sendEmail($id);

            if(!$status){
                Session::flash('risk_notification_warning', 'Your risk changes were saved. Email notification could not be completed. Please contact your administrator.');
                return redirect()->back();
            }

            Session::flash('msg', "Risk edited successfully");
            return redirect()->back();
        }
    }

    public function riskhistory()
    {
        if (isset($_GET['val'])) {

            $riskhistory = DB::table('riskhistory')->where('Rid', '=', $_GET['val'])
                ->get();
            $models = DB::table('modenadisable')->First();
            $riskdata = Risk::where('Rid', $_GET['val'])->first();
            return view('user.risks.riskhistory')
                ->with('riskhistory', $riskhistory)
                ->with('riskdata', $riskdata)
                ->with('model', $models);
        }
    }

    //addopportunity
    public function addriskopportunity($id)
    {
        $riskdata = Risk::where('Rid', $id)->first();
        $departments = Departments::all();
        $opptypes = OpportunityType::all();
        $hml = DB::table('hml')->get();
        // $users = User::all();
        $users = User::where('is_email_verified',true)->where('approved',true)->get();
        $models = DB::table('modenadisable')->First();
        return view('user.risks.addriskopportunity')
            ->with('riskdata', $riskdata)
            ->with('departments', $departments)
            ->with('hml', $hml)
            ->with('users', $users)
            ->with('opptypes', $opptypes)
            ->with('model', $models);
    }

    public function insertriskopportunity(Request $req)
    {
        $validator = Validator::make($req->all(), [
        ]);

        if ($validator->fails()) {

            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {

            $riskid = $req->riskid;
            $department = $req->input('responsible_department');
            $opparea = DB::table('departments')->where('id', $department)->value('departmentname');
            //-------------------------------Id generation part start-----------------------------------------
            // Retrieve all records for today's date
            $today = date('Y-m-d');
            $todayLatestOpportunity = Opportunity::whereRaw(
                "DATE(STR_TO_DATE(createdat, '%d-%m-%Y %h:%i:%s %p')) = ?",
                [$today]
            )->orderByDesc('createdat')->first();
            if ($todayLatestOpportunity) {
                $lastFormatValue = intval(substr($todayLatestOpportunity->dep_id, -4));
                $formatValue = str_pad($lastFormatValue + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $formatValue = "0001";
            }
            do {
                $dep_id = 'OPP-' . date('Ymd') . '-' . $formatValue;
                $formatValue = str_pad(intval($formatValue) + 1, 4, '0', STR_PAD_LEFT);
            } while (Opportunity::where('dep_id', $dep_id)->exists());
            //------------------------------------Id generation part end ------------------------------------------
            $cer = 'NULL';
            if ($req->hasFile('opp_file')) {
                $file_array = $req->file('opp_file');
                $array_len = count($file_array);
                $file = [];
                for ($i = 0; $i < $array_len; $i++) {
                    $file_size = $file_array[$i]->getClientOriginalName();
                    $file_ext = $file_array[$i]->getClientOriginalExtension();
                    $new_file_name = $file_size;
                    $destination_path = public_path('storage/opportunity');
                    $file_array[$i]->move($destination_path, $new_file_name);
                    $file[] = $new_file_name;
                }
                $cer = implode(',', $file);
            }

            $oppname = $req->input('opportunityname');
            $oppdec = $req->input('oppdec');
            $likelihood = $req->input('likelihood');
            $benefit = $req->input('benefit');
            $now = $req->input('targetdate');
            $ohs = $req->input('ohs');
            $division = $req->input('division');
            $area = $req->input('area');
            if ($ohs == '1') {
                $type = implode(',', $req->type);
                $ohsvalue = 'Yes';
            } else {
                $type = $req->type ? implode(',', $req->type) : null;
                $ohsvalue = 'No';
            }

            $action = $req->input('action');
            $createdat = date('d-m-Y h:i:s A', time());

            if ($likelihood == "High") {
                $value1 = 3;
            } elseif ($likelihood == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($benefit == "High") {
                $value2 = 3;
            } elseif ($benefit == "Moderate") {
                $value2 = 2;
            } else {
                $value2 = 1;
            }

            if (($value1 * $value2) > 5) {
                $impa = "High";
            } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                $impa = "Moderate";
            } else {
                $impa = "Low";
            }

            $ID = Auth::user()->employeeid;
            $responsibility = DB::table('users')->where('id', $req->responsible_person)->value('email');

            $data = array(
                'department' => $department, 'division_id' => $division, 'area_id' => $area,'oppname' => $oppname,'dep_id' => $dep_id,
                'oppdes' => $oppdec, 'ohs' => $ohsvalue, 'likelihood' => $likelihood, 'benefit' => $benefit,
                'tardate' => $now, 'impact' => $impa, 'postedby' => $ID, 'type' => $type,'updatedat' => now(),
                'action' => $action, 'createdat' => $createdat, 'responsibility' => $responsibility, 'file' => $cer, 'risk_id' => $riskid,
            );

            DB::table('opportunity')->insert($data);

            //inserting values to activity table superadmin code
            $activity = "New Opportunity Added with Name " . $oppname . " and with Department " . $opparea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code
            Session::flash('msg', "Opportunities sent for approval Successfully");
            $mails = EmailNotification::where('module','riskmanagement')
                        ->where('item','New Opportunity Added')
                        ->where('is_blocked',false)
                        ->first();
            if($mails){
                $data = array(
                    'department' => $opparea, 'oppname' => $oppname,
                    'oppdes' => $oppdec, 'dep_id' => $dep_id,
                    'item' => $mails->item,
                    'email_content' => $mails->email_content,
                );
                $riskreviewer = DB::table('closurereviewers')->pluck('email')->toArray();
                //Sending Mail To Responsible HOD
                if(!empty($riskreviewer)){
                    Mail::to($riskreviewer)->send(new NewOpportunityAdded($data));
                }
                $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
                if(count($userdatas1) > 0)
                {
                    $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if(!empty($userdatas)){
                        Mail::to($userdatas)->send(new NewOpportunityAdded($data));
                    }
                }
            }else{
                Session::flash('errmsg', 'Mail Sending Blocked for New Opportunity Added');
                return redirect()->route('risk.index');
            }

            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    // change risk owner
    public function changeriskowner($id)
    {
        $data = Risk::where('Rid', $id)->first();
        $riskowner = User::where('email', $data->risk_owner)->value('name');
        //$users = User::all();
        $users = User::where('is_email_verified',true)->orderBy('name', 'asc')->where('approved',true)->get();
        $models = DB::table('modenadisable')->First();
        $departments = Departments::all();
        return view('user.risks.changeriskowner')
            ->withData($data)
            ->with('users', $users)
            ->with('riskowner', $riskowner)
            ->with('departments', $departments)
            ->with('model', $models);
    }

    public function updateriskowner(Request $req)
    {
        $id = $req->input('id');
        $riskarea = $req->input('riskarea');
        $riskarea_name = Departments::where('id', $riskarea)->value('departmentname');
        $riskowner = array();
        foreach ($req->riskowner as $riskownr) {
            $riskowner_email = User::where('id', $riskownr)->value('email');
            array_push($riskowner, $riskowner_email);
        }
        $riskowneremails = implode(',', $riskowner);
        //$riskowner_email = User::where('id',$req->riskowner)->value('email');

        Risk::where('Rid', $id)->update([
            'risk_owner' => $riskowneremails,
            'division_id' => $req->input('division'),
            'area_id' => $req->input('area'),
            'riskarea' => $riskarea,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $mails = EmailNotification::where('module','riskmanagement')
                    ->where('item','Risk Owner Changed')
                    ->where('is_blocked',false)
                    ->first();
        if($mails){
            $data = array(
                    'RiskArea' => $req->riskarea,
                    'Riskid' => $req->riskid,
                    'Target' => $req->target,
                    'RiskGroup' => $req->risk_group,
                    'item' => $mails->item,
                    'email_content' => $mails->email_content,
                );
            //Sending Mail To Responsible HOD
            if(!empty($riskowner_email)){
                Mail::to($riskowner_email)->send(new RiskChangeowner($data));
            }
            $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
            if(count($userdatas1) > 0)
            {
                $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if(!empty($userdatas)){
                    Mail::to($userdatas)->send(new RiskChangeowner($data));
                }
            }
        }else{
            Session::flash('errmsg', 'Mail Sending Blocked for Risk Owner Changed');
            return redirect()->route('risk.index');
        }
        //inserting values to activity table
        $activity = "Risk Owner is Changed and updated as " . $riskowner_email . " and RiskArea is updated as " . $riskarea_name . " for Risk ID  " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //end of superadmin code
        Session::flash('msg', "Risk Owner changed successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }
    public function exportData(string $format)
    {
        $clientDetails = ClientDetail::first();
        $actionStatus = [
                'Under review', 'Rejected', 'Active Risk', 'Action under review', 'Completed', 'Awaiting Periodic Review'
            ];
        if(isset($_GET['ids'])){
            $ids = json_decode($_GET['ids'], true);
            $datas = Risk::with(['department', 'riskreviewer'])
                    ->whereIn('Rid', $ids)
                    ->orderBy('created_at', 'desc')
                    ->orderby('riskgroup', 'asc')
                    ->orderby('riskarea', 'asc')
                    ->get();
        }else{
            $datas = Risk::with(['department', 'riskreviewer'])
                    ->whereIn('action_status', $actionStatus)
                    ->orderBy('created_at', 'desc')
                    ->orderby('riskgroup', 'asc')
                    ->orderby('riskarea', 'asc')
                    ->get();
        }

        $commanData = [
            'clientDetails' => $clientDetails,
            'datas' => $datas,
            'paper' => 'a4',
            'orientation' =>'L'
        ];
        if($format=="pdf"){
            return $this->generatePdf($commanData);
        }elseif($format=="excel"){
            return $this->generateExcel($commanData);
        }elseif($format=="word"){
            return $this->generateWord($commanData);
        }
    }
    private function generatePdf($commanData){
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        ini_set('pcre.backtrack_limit', '50000000');
        ini_set('pcre.recursion_limit', '50000000');

        $tempPath = storage_path('app/mpdf');
        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true, true);
        }
        $mpdf = new Mpdf([
            'orientation' => 'P',      // Landscape
            'format'      => 'a4',
            'tempDir'     => $tempPath,
            'margin_top' => 30,
        ]);
        $html = view('exporting.forriskmanagement', compact('commanData'))->render();
        $headerHtml = view('user.pdf_header', compact('commanData'))->render();
        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetFooter('{PAGENO} of {nbpg}');
        if($commanData['clientDetails'] && $commanData['clientDetails']->client_logo){
            $mpdf->SetWatermarkImage(public_path('storage/clientlogos/'.$commanData['clientDetails']->client_logo), 0.1);
        }
        $mpdf->showWatermarkImage = true;
        $mpdf->WriteHTML($html);
        return $mpdf->Output('Enterprise Risk Management Datas.pdf', 'D');
    }
    private function generateExcel($commanData){
        if (ob_get_length()) ob_end_clean();
        return Excel::download(
            new RiskExport(
                $commanData,
                public_path('storage/clientlogos/'.$commanData['clientDetails']->client_logo)
            ),
            'Enterprise Risk Management Datas.xlsx'
        );
    }
    private function generateWord($commanData){
        if (ob_get_length()) ob_end_clean();
        $export = new RiskWordExport($commanData);
        $filePath = $export->generate();
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
