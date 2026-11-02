<?php

namespace App\Http\Controllers;

use App\AuditRootCauseCorrectiveActionDetail;
use App\Categories;
use App\Departments;
use App\EmployeeDetails;
use App\Http\Requests\InsertNearmissIssueRequest;
use App\Http\Requests\NearmissReopenRequest;
use App\Http\Requests\UpdateRespondedIssueNearmissRequest;
use App\Mail\NearmissAddIssue;
use App\Mail\NearmissProposed;
use App\Mail\NearmissResponseNotification;
use App\Mail\NearmissResponsibilityChanged;
use App\Mail\NearmissReviewStatusFoundNotOK;
use App\Mail\NearmissReviewStatusFoundOK;
use App\Mail\NearmissUpdated;
use App\ModenaDisable;
use App\Modpageload;
use App\Near;
use App\Nearmiss;
use App\Nearmisstypes;
use App\Neartarget;
use App\Representative;
use App\Type;
use App\User;
use App\ViewActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class NearmissController extends Controller
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

    public function addissue()
    {
        $departments = Departments::all();
        $type = Nearmisstypes::get();
        $user = User::where('approved',true)->orderBy('name')->get();
        $issuereviewers = Near::get();
        $models = ModenaDisable::First();
        $targetinfo = Neartarget::all();
        $employee_details = EmployeeDetails::all();
        $currentUser = User::where('email',Auth::user()->email)->with('department')->first();
        return view('user.nearmiss.addissue')->with([
            'departments' => $departments,
            'type' => $type,
            'user' => $user,
            'targetinfo' => $targetinfo,
            'issuereviewers' => $issuereviewers,
            'model' => $models,
            'employee_details' => $employee_details,
            'currentUser' => $currentUser,
        ]);
    }
    public function saveMultipleFiles($request, $id, $field, $name)
    {
        if ($request->hasFile($name)) {
            $file_array = $request->file($name);
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/nearmiss');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
                Nearmiss::where('id', $id)->update([
                    $field => implode(',', $file),
                ]);
            }
        }
    }
    public function nearmissActionAttachment(Request $request, int $id)
    {
        $this->saveMultipleFiles($request, $id, 'action_file', 'fileToUpload');
        $this->saveMultipleFiles($request, $id, 'file', 'fileToUpload1');
        return response()->json(['data' => null]);
    }
    public function ajaxForgivingDepartmentAndPosition()
    {

        $data = [];
        $id = request()->input('id');
        $employee_detail = EmployeeDetails::where('emp_number', $id)->first();
        $position = $employee_detail->emp_position;
        $department = $employee_detail->emp_department;
        $department = Departments::where('id', $department)->value('departmentname');
        $data['position'] = $position;
        $data['department'] = $department;
        return [
            "data" => $data,
        ];
    }
    public function insertissue(InsertNearmissIssueRequest $req)
    {

        $cer = 'NULL';
        $agreedaction = 'NULL';
        if ($req->hasFile('issue_file')) {
            $file_array = $req->file('issue_file');
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $file_ext = $file_array[$i]->getClientOriginalExtension();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/nearmiss');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
            }
            $cer = implode(',', $file);
        }
        $postdate = date('Y-m-d H:i:s', time());
        $approvement = "yes";
        $processstage = "Awaiting actions";
        $status = "open";
        $approvedby = Auth::user()->employeeid;
        $area = $req->input('area_str');
        $type = $req->input('type');
        $dept = Departments::where('id', $req->input('department'))->value('departmentname');
        $department = $dept ? $dept : $req->input('department');
        $division = $req->input('division');
        $sub_div = $req->input('area');
        $title = $req->input('title');
        $observation = $req->input('observation');
        $reportedId = $req->input('reportedId');
        $employee_details = EmployeeDetails::where('emp_number', $reportedId)->first();
        $reprtedByDepartment = $dept;
        $due_date = $req->input('targetdate');
        if ($req->frequency) {
            $freq = $req->input('frequency');
            $frequency = implode(',', $freq);
        } else {
            $frequency = null;
        }
        $ques = $req->input('ques');
        if ($ques == '1') {
            $processstage = "Actions Proposed";
            $resp = Auth::user()->email;
            $correction = $req->input('correction');
            $rootcause = $req->input('rootcause');
            $correctiveaction = $req->input('correctiveaction');
            $agreedaction = null;
        } else {
            $resp = $req->input('responsibility');
            $agreedaction = $req->input('agreedaction');
            $correction = null;
            $rootcause = null;
            $correctiveaction = null;
        }
        if ($req->notification) {
            $notify = $req->input('notification');
            $notification = implode(',', $notify);
        } else {
            $notification = null;
        }

        $postedby = $req->input('postedby');
        $review = $req->input('reviewauthority');
        $reviewauthority = implode(',', $review);
        $closure = $req->input('closureauthority');
        $closureauthority = implode(',', $closure);
        //-------------------------------Id generation part start-----------------------------------------
        // Retrieve all records for today's date
        $today = date('Y-m-d');
        $todayLatestCorrective = Nearmiss::whereDate('created_at', $today)->orderByDesc('created_at')->first();
        if ($todayLatestCorrective) {
            $lastFormatValue = intval(substr($todayLatestCorrective->issueid, -4));
            $formatValue = str_pad($lastFormatValue + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $formatValue = "0001";
        }
        do {
            $issueId = 'NM-' . date('Ymd') . '-' . $formatValue;
            $formatValue = str_pad(intval($formatValue) + 1, 4, '0', STR_PAD_LEFT);
        } while (Nearmiss::where('issueid', $issueId)->exists());
        //------------------------------------Id generation part end ------------------------------------------
        $data4 = array(
            'created_at' => $postdate,
            'type' => $type,
            'department' => $department,
            'division_id' => $division,
            'area_id' => $sub_div,
            'title' => $title,
            'file' => $cer,
            'area' => $area,
            'observation' => $observation,
            'targetdate' => $due_date,
            'frequency' => $frequency,
            'agreedaction' => $agreedaction,
            'postedby' => $postedby,
            'responsibility' => $resp,
            'notification' => $notification,
            'approvement' => $approvement,
            'processstage' => $processstage,
            'approvedby' => $approvedby,
            'status' => $status,
            'rootcause' => $rootcause,
            'correctiveaction' => $correctiveaction,
            'correction' => $correction,
            'reviewauthority' => $reviewauthority,
            'closureauthority' => $closureauthority,
            'department_reported_by' => $reprtedByDepartment ?? '',
            'issueid' => $issueId
        );
        Nearmiss::insert($data4);
        $activity = "A near miss issue is added with title " . $title;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        ViewActivity::insert($datas);
        $d = Nearmiss::orderBy('id', 'desc')->first();
        $ReviewDate = date('Y-m-d ', strtotime($d->created_at));
        $data2 = array(
            'Monthlyfreq' => $ReviewDate,
            'Weeklyfreq' => $ReviewDate,
        );
        Nearmiss::where('id', $d->id)->update($data2);
        $id = $d->id;
        $postedby = $d->postedby;
        $name = Auth::user()->name;
        $data = array(
            'issueid' => $d->issueid,
            'name' => $name,
            'type' => $d->type,
            'department' => $department,
            'targetdate' => date("d/m/Y", strtotime($d->targetdate)),
        );
        Mail::to($d->responsibility)->send(new NearmissResponseNotification($data));
        $noti = explode(',', $notification);
        foreach ($noti as $n) {
            if ($n != "") {
                Mail::to($n)->send(new NearmissAddIssue($data));
            }
        }
        Session::flash('msg', "Near miss issue submitted successfully");
        $models = ModenaDisable::First();
        return redirect()->back()->with('model', $models);
    }
    public function viewlist(string $type = null)
    {
        $today = date('Y-m-d');
        $types = Nearmisstypes::all();
        $typeNames = NearmissTypes::pluck('name')->toArray();
        //below is superadmin related codes
        $nearmiss = Modpageload::where('id', 1)->get();
        if (sizeof($nearmiss) == 0) {
            Modpageload::insert(['id' => 1, 'nearmiss' => 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]);
        } else {
            $modpageload = Modpageload::where('id', 1)->First();
            $modpageload->nearmiss += 1;
            $modpageload->save();
        }
        //end of superadmin codes
        $issues = Nearmiss::where(function ($query) use ($type) {
            if ($type != 'awaiting' && $type != 'proposed') {
                $query->where('status', 'open');
            }
        })->where(function ($query) use ($type, $typeNames) {
            if (in_array($type, $typeNames)) {
                $query->where('type', $type);
            }
        })->where(function ($query) use ($type, $today) {
            if ($type == 'due') {
                $query->where('targetdate', '<=', $today);
            }
        })->where(function ($query) use ($type) {
            if ($type == 'awaiting') {
                $query->wherein('processstage', array('Approved', 'Awaiting actions'));
            } else if ($type == 'proposed') {
                $query->where('processstage', 'Actions Proposed');
            }
        })->where(function ($query) use ($type) {
            if ($type == 'closure') {
                $query->where('impl_status', 'Completed-100%');
            }
        })->orderByDesc('id')->get();
        $representatives = Representative::pluck('email');
        $hods = Departments::pluck('hod');
        $list = collect([]);
        foreach ($representatives as $representative) {
            $list = $list->push($representative);
        }
        foreach ($hods as $hod) {
            $list = $list->push($hod);
        }
        $mr = $list->unique();
        $departments = Departments::all();
        $categories = Categories::all();
        $users = User::pluck('name');
        $models = Modenadisable::First();

        $reviewers = Near::pluck('email')->toArray();
        $typenames = Nearmiss::groupBy('type')->where('status','open')->pluck('type')->toArray();  
        $alltypenames = implode(',', $typenames);

        $yearvalues = Nearmiss::select(DB::raw('YEAR(targetdate) AS year'))
                                   ->where('status','open')->distinct()->get()->toArray();
        $year_arr = array();
        foreach($yearvalues as $yearvalue)
        {
            array_push($year_arr,$yearvalue['year']);
        }
        sort($year_arr);
        $years = implode(',', array_unique($year_arr));

        $nearmissdepts = Nearmiss::groupBy('department')->where('status','open')->pluck('department')->toArray();
        $alldeptnames = implode(',',$nearmissdepts);

        $responsible_emails = Nearmiss::groupBy('responsibility')->where('status','open')->pluck('responsibility')->toArray();
        $usernameslist = array();
        foreach($responsible_emails as $responsible_email)
        {
            $resp_names = User::where('email',$responsible_email)->value('name');
            if($resp_names){
                array_push($usernameslist,$resp_names);
            }
        }
        $allusernames = implode(',',$usernameslist);

        $processstages = ['Awaiting actions','Actions Proposed','Action Approved',
                           'Action Rejected','Guest Submitted','Need More Action','Actions Completed'
                         ];
        $allpstages = implode(',', $processstages);

        return view('user.nearmiss.issues',compact('reviewers'))->with([
            'users' => $users,
            'types' => $types,
            'categories' => $categories,
            'departments' => $departments,
            'alltypenames'=>$alltypenames,
            'allpstages'=>$allpstages,
            'years'=>$years,
            'alldeptnames'=>$alldeptnames,
            'allusernames'=>$allusernames,
            'issues' => $issues,
            'mr' => $mr,
            'model' => $models,
        ])->withMr($mr);
    }
    public function closedissue()
    {

        $issues1 = [];
        $issuereviewers = Near::pluck('email');
        $mr = Representative::pluck('email');
        $issues1 = Nearmiss::whereIn('status', array('closed', 'closed after due'))
            ->where('status', '!=', 'removed')->orderByDesc('id')
            ->get();

        $models = Modenadisable::First();
        return view('user.nearmiss.closedissue')->with([
            'issues1' => $issues1,
            'mr' => $mr,
            'issuereviewers' => $issuereviewers,
            'model' => $models,
        ]);
    }
    public function openissue()
    {

        $issuereviewers = Near::pluck('email');
        $mr = Representative::pluck('email');
        $issues2 = [];
        $de = Auth::user()->email;
        $issues2 = Nearmiss::where('responsibility', '=', $de)
            ->where('status', 'open')
            ->wherein('processstage', array(
                'Awaiting actions',
                'Actions Proposed',
                'Action Reviewed, OK',
                'Action Reviewed, Not OK',
                'rejected',
                'Need More Action',
                'Actions Completed',
            ))
            ->where('status', '!=', 'removed')
            ->orderByDesc('id')
            ->get();

        $models = Modenadisable::First();
        return view('user.nearmiss.openissue')->with([
            'issues2' => $issues2,
            'issuereviewers' => $issuereviewers,
            'mr' => $mr,
            'model' => $models,
        ]);
    }
    public function editopen(int $id)
    {
        $data = Nearmiss::where('id', $id)->get();
        $user = User::all();
        $res = Nearmiss::where('id', $id)->value('responsibility');
        $resp = User::where('email', $res)->value('name');
        $type = Nearmisstypes::all();
        $models = Modenadisable::First();
        $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
        return view('user.nearmiss.editopen')
            ->withUser($user)
            ->withData($data)
            ->withType($type)
            ->with('resp', $resp)
            ->with('rootcorrectives', $rootcorrectives)
            ->with('model', $models);
    }
    public function displayrootcorrectiveajax(Request $request)
    {
        $data_id = $request->input('data_id');
        $rootcause = AuditRootCauseCorrectiveActionDetail::where('id', $data_id)->value('root_cause');
        $corrective_action = AuditRootCauseCorrectiveActionDetail::where('id', $data_id)->value('corrective_action');
        return response()->json([
            'status' => 200,
            'rootdata' => $rootcause,
            'correctdata' => $corrective_action,
        ]);
    }
    public function updateopen(Request $r)
    {
        $cer = 'NULL';
        if ($r->hasFile('action_file')) {
            $file_array = $r->file('action_file');
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/nearmiss');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
            }
            $cer = implode(',', $file);
        }
        $type = $r->input('type');
        $updated_at = date('Y-m-d H:i:s', time());
        $rootcause = $r->input('rootcause');
        $correctiveaction = $r->input('correctiveaction');
        $correction = $r->input('correction');
        $id = $r->input('posted');

        if ($r->confirm_propose == 'yes') {
            $data = array(
                'type' => $type,
                'rootcause' => $rootcause,
                'correctiveaction' => $correctiveaction,
                'correction' => $correction,
                'action_file' => $cer,
                'processstage' => 'Actions Completed',
                'impl_status' => 'Completed-100%',
                'updated_at' => $updated_at,
            );

            Nearmiss::where('id', $id)->update($data);
            $title = Nearmiss::where('id', $id)->pluck('title');
            $activity = "NearMiss Implementation Status is updated to 'Completed-100%' and Process Stage is Updated to 'Action Completed' where Nearmiss Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            ViewActivity::insert($datas);
        } else if ($r->confirm_propose == 'no') {
            $data = array(
                'type' => $type,
                'rootcause' => $rootcause,
                'correctiveaction' => $correctiveaction,
                'correction' => $correction,
                'action_file' => $cer,
                'processstage' => 'Actions Proposed',
                'updated_at' => $updated_at,
            );
            Nearmiss::where('id', $id)->update($data);
            $updateddata = Nearmiss::where('id', $id)->first();
            $maildata = array(
                'issueid' => $updateddata->issueid,
                'type' => $updateddata->type,
                'department' => $updateddata->department,
                'targetdate' => date("d/m/Y", strtotime($updateddata->targetdate)),
            );
            $reviewauthority = $updateddata->reviewauthority;
            $emails = explode(',', $reviewauthority);
            foreach ($emails as $em) {
                Mail::to($em)->send(new NearmissProposed($maildata));
            }

            $title = Nearmiss::where('id', $id)->pluck('title');
            $activity = "An open issue " . $title . " process stage is updated as Action Proposed ";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            ViewActivity::insert($datas);

        }

        $action_files = $cer;
        $expl_action = explode(',', $action_files);
        foreach ($expl_action as $action_file) {
            $data1 = array('action_file' => $action_file);
            Nearmiss::where('id', $id)->update($data1);
        }

        //echo "<script>alert('Issue sent for reviewer response successfully')</script>";
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
    public function editrespondedissue(int $id)
    {
        $data = Nearmiss::where('id', $id)->get();
        $user = User::all();
        $res = Nearmiss::where('id', $id)->value('responsibility');
        $resp = User::where('email', $res)->value('name');
        $type = Nearmisstypes::all();
        $models = Modenadisable::First();
        return view('user.nearmiss.editrespondedissue')->withUser($user)
            ->withData($data)
            ->withType($type)
            ->with([
                'resp' => $resp,
                'model' => $models,
            ]);
    }
    public function updaterespondedissue(UpdateRespondedIssueNearmissRequest $r)
    {

        $list = collect([]);
        $cer = 'NULL';
        if ($r->hasFile('action_file')) {
            $file_array = $r->file('action_file');
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $file_ext = $file_array[$i]->getClientOriginalExtension();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/nearmiss');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
            }
            $cer = implode(',', $file);
        }
        $id = $r->input('posted');
        $type = $r->input('type');
        $rootcause = $r->input('rootcause');
        $correctiveaction = $r->input('correctiveaction');
        $correction = $r->input('correction');
        $impl_status = $r->input('impl_status');
        $updated_at = date('Y-m-d H:i:s', time());
        $data = array(
            'type' => $type,
            'rootcause' => $rootcause,
            'correctiveaction' => $correctiveaction,
            'correction' => $correction,
            'action_file' => $cer,
            'status' => 'open',
            'processstage' => 'Actions Proposed',
        );
        Nearmiss::where('id', $id)->update($data);
        $d = Nearmiss::latest('updated_at')->first();
        $id = $d->id;
        $title = Nearmiss::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . " process stage is updated as Actions Proposed and  status as open";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        $data = array(
            'issueid' => $d->issueid,
            'type' => $d->type,
            'department' => $d->department,
            'targetdate' => date("d/m/Y", strtotime($d->targetdate)),
        );
        $reviewauthority = $d->reviewauthority;
        $emails = explode(',', $reviewauthority);
        foreach ($emails as $em) {
            Mail::to($em)->send(new NearmissUpdated($data));
        }
        echo "<script>alert('Issue edited successfully')</script>";
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
    public function reviewstatus(int $id)
    {
        $departments = Departments::all();
        $type = Nearmisstypes::all();
        $data = Nearmiss::where('id', $id)->get();
        $models = Modenadisable::First();
        return view('user.nearmiss.statusreview')->withData($data)
            ->withDepartments($departments)
            ->withType($type)
            ->with('model', $models);
    }
    public function actionreviewed_ok(Request $req)
    {
        $id = $req->input('id');
        $updated_at = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'open',
            'processstage' => 'Action Reviewed, OK',
            'active' => 'yes',
            'title' => $req->input('title'),
            'observation' => $req->input('observation'),
            'agreedaction' => $req->input('agreedaction'),
            'targetdate' => $req->input('targetdate'),
            'rootcause' => $req->input('rootcause'),
            'correctiveaction' => $req->input('correctiveaction'),
            'correction' => $req->input('correction'),
        );
        Nearmiss::where('id', $id)->update($data);
        $data1 = Nearmiss::where('id', $id)->get();
        foreach ($data1 as $data) {
            $posted_emp = User::where('employeeid', $data->postedby)->pluck('name');
            if (count($posted_emp)) {
                foreach ($posted_emp as $p) {
                    $name = $p;
                }
            } else {
                $name = '';
            }

            $maildata = array(
                'issueid' => $data->issueid,
                'name' => $name,
                'type' => $data->type,
                'department' => $data->department,
                'targetdate' => date("d/m/Y", strtotime($data->targetdate)),
            );
            $responsible = $data->responsibility;

            $notification = $data->notification;
            $noti = explode(',', $notification);
            foreach ($noti as $n) {
                if ($n != "") {
                    // Mail::to($n)->send(new NearmissReviewStatusFoundOK($maildata));
                }
            }
        }
        $title = Nearmiss::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . " process stage is updated as Action Reviewed, OK and  status as open";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        ViewActivity::insert($datas);
        Session::flash('msg', "Action reviewed successfully");
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
    public function notok_remarks(int $id)
    {
        $models = Modenadisable::First();
        return view('user.nearmiss.notok_remarks')->withId($id)->with('model', $models);
    }
    public function notok_addremarks(Request $req)
    {
        $action_remarks = $req->input('action_remarks');
        $id = $req->input('id');
        $updated_at = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'open',
            'processstage' => 'Action Reviewed, Not OK',
            'action_remarks' => $action_remarks,
        );
        Nearmiss::where('id', $id)->update($data);
        $data1 = Nearmiss::where('id', $id)->get();
        foreach ($data1 as $data) {
            $posted_emp = User::where('employeeid', $data->postedby)->pluck('name');
            if (count($posted_emp)) {
                foreach ($posted_emp as $p) {
                    $name = $p;
                }
            } else {
                $name = '';
            }

            $maildata = [
                'issueid' => $data->issueid,
                'name' => $name,
                'type' => $data->type,
                'department' => $data->department,
                'disapproved_reason' => $data->action_remarks,
                'targetdate' => date("d/m/Y", strtotime($data->targetdate)),
            ];
            $responsible = $data->responsibility;
            Mail::to($responsible)->send(new NearmissReviewStatusFoundNotOK($maildata));

            $notification = $data->notification;
            $noti = explode(',', $notification);
            foreach ($noti as $n) {
                if ($n != "") {
                    Mail::to($n)->send(new NearmissReviewStatusFoundNotOK($maildata));
                }
            }
        }
        $title = Nearmiss::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . " process stage is updated as Action Reviewed, Not OK and  status as open";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);

        echo "<script>alert('Action reviewed successfully')</script>";
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
    public function closure(int $id)
    {

        $type = Nearmisstypes::all();
        $departments = Departments::all();
        $data = Nearmiss::where('id', $id)->get();
        $models = Modenadisable::First();
        return view('user.nearmiss.closurereview')->withData($data)
            ->withType($type)
            ->withDepartments($departments)
            ->with('model', $models);
    }
    public function changes(int $id)
    {
        $models = Modenadisable::First();
        return view('user.nearmiss.suggestchanges')->withId($id)->with('model', $models);
    }
    public function suggestchanges(Request $req)
    {
        if ($req->action_modified == "yes") {
            $remarks = $req->input('remarks');
            $id = $req->input('id');
            $updated_at = date('Y-m-d H:i:s', time());
            $data = array(
                'status' => 'open',
                'processstage' => 'Need More Action',
                'impl_status' => 'Partially Completed-50%',
                'remarks' => $remarks,
            );
            Nearmiss::where('id', $id)->update($data);
            $data1 = Nearmiss::where('id', $id)->get();
            foreach ($data1 as $data) {
                if (is_numeric($data->department)) {
                    $dept = DB::table('departments')->where('id', $data->department)->value('departmentname');
                } else {
                    $dept = $data->department;
                }

                $posted_emp = User::where('employeeid', $data->postedby)->pluck('name');
                if (count($posted_emp)) {
                    foreach ($posted_emp as $p) {
                        $name = $p;
                    }
                } else {
                    $name = '';
                }

                $maildata = array(
                    'issueid' => $data->issueid,
                    'name' => $name,
                    'disapproved_reason' => $remarks,
                    'type' => $data->type,
                    'department' => $dept,
                    'targetdate' => date("d/m/Y", strtotime($data->targetdate)),
                );
                $responsible = $data->responsibility;
                // Mail::to($responsible)->send(new NearmissReviewStatusFoundNotOK($maildata));

                $notification = $data->notification;
                $noti = explode(',', $notification);
                foreach ($noti as $n) {
                    if ($n != "") {
                        Mail::to($n)->send(new NearmissReviewStatusFoundNotOK($maildata));
                    }
                }
            }
            $title = Nearmiss::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Need More Action, status as open and implementation_status as Partially Completed-50%";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            ViewActivity::insert($datas);
        } elseif ($req->action_modified == "no") {
            $remarks = $req->input('remarks');
            $id = $req->input('id');
            $updated_at = date('Y-m-d H:i:s', time());
            $data = array(
                'status' => 'open',
                'processstage' => 'Action Reviewed, OK',
                'impl_status' => 'Partially Completed-50%',
                'remarks' => $remarks,
            );
            Nearmiss::where('id', $id)->update($data);
            $data1 = Nearmiss::where('id', $id)->get();
            foreach ($data1 as $data) {
                //$dept = DB::table('departments')->where('id',$data->department)->value('departmentname');
                if (is_numeric($data->department)) {
                    $dept = DB::table('departments')->where('id', $data->department)->value('departmentname');
                } else {
                    $dept = $data->department;
                }
                $posted_emp = User::where('employeeid', $data->postedby)->pluck('name');
                foreach ($posted_emp as $p) {
                    $name = $p;
                }

                $maildata = array(
                    'issueid' => $data->issueid,
                    'name' => $name,
                    'type' => $data->type,
                    'department' => $dept,
                    'targetdate' => date("d/m/Y", strtotime($data->targetdate)),
                );
                $responsible = $data->responsibility;
                // Mail::to($responsible)->send(new NearmissReviewStatusFoundNotOK($maildata));

                $notification = $data->notification;
                $noti = explode(',', $notification);
                foreach ($noti as $n) {
                    if ($n != "") {
                        Mail::to($n)->send(new NearmissReviewStatusFoundOK($maildata));
                    }
                }
            }
            $title = Nearmiss::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Action Reviewed, OK, status as open and implementation_status as Partially Completed-50%";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            ViewActivity::insert($datas);
        }
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
    public function closed(int $id)
    {

        $date = date('Y-m-d');
        $closedby = Auth::user()->employeeid;
        $updated_at = date('Y-m-d h:i:s', time());
        $data = array(
            'status' => 'closed',
            'processstage' => 'Accomplished',
            'closed' => $date,
            'closedby' => $closedby,
        );
        Nearmiss::where('id', $id)->update($data);
        $title = Nearmiss::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . " process stage is updated as Accomplished, status as closed.";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        $data1 = Nearmiss::where('id', $id)->get();
        foreach ($data1 as $data) {
            $posted_emp = User::where('employeeid', $data->postedby)->pluck('name');
            foreach ($posted_emp as $p) {
                $name = $p;
            }
        }
        echo "<script>alert('Issue closed successfully')</script>";
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
    public function progressMore($id, $data, $text, $redirect_route)
    {

        Nearmiss::where('id', '=', $id)->update($data);
        $title = Nearmiss::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . $text;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        ViewActivity::insert($datas);
        $models = ModenaDisable::first();
        return redirect($redirect_route)->with('model', $models);
    }
    public function progressLess($id, $impl_status)
    {

        $data = array(
            'impl_status' => $impl_status,
        );
        Nearmiss::where('id', '=', $id)->update($data);
        $models = Modenadisable::First();
        return redirect('/user/nearmiss/openissue')->with('model', $models);
    }
    public function hundred(int $id)
    {
        $data = array(
            'processstage' => 'Actions Completed',
            'impl_status' => 'Completed-100%',
        );
        return $this->progressMore($id, $data, " process stage is updated as Action Completed and implementation_status as Completed-100%", "/user/nearmiss");
    }
    public function five(int $id)
    {

        $data = array(
            'impl_status' => 'Partially Completed-50%',
        );
        return $this->progressMore($id, $data, " process stage is updated as Action Completed and implementation_status as Partially Completed-50%", "/user/nearmiss/openissue");
    }
    public function two(int $id)
    {

        return $this->progressLess($id, 'Progress-25%');
    }
    public function zero(int $id)
    {

        return $this->progressLess($id, 'Not Started-0%');
    }
    public function reopen(int $id)
    {
        $type = Nearmisstypes::all();
        $user = User::all();
        $categories = Categories::all();
        $departments = Departments::all();
        $data = Nearmiss::where('id', '=', $id)->get();
        $models = Modenadisable::First();
        return view('user.nearmiss.reopen')->withCategories($categories)
            ->withDepartments($departments)
            ->withType($type)
            ->withUser($user)
            ->withData($data)
            ->withId($id)
            ->with('model', $models);
    }
    public function reopenissue(NearmissReopenRequest $req)
    {

        $cer = 'NULL';
        if ($req->hasFile('file')) {
            $file_array = $req->file('file');
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $file_ext = $file_array[$i]->getClientOriginalExtension();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/nearmiss');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
            }
            $cer = implode(',', $file);
        }
        $id = $req->input('id');
        $title = $req->input('title');
        $status = "open";
        $approvement = "yes";
        $processstage = "Awaiting actions";
        $impl_status = "Not Started-0%";
        $approvedby = Auth::user()->employeeid;
        $department = $req->input('department');
        $type = $req->input('type');
        $observation = $req->input('observation');
        $agreedaction = $req->input('agreedaction');
        $resp = $req->input('responsibility');
        $due_date = $req->input('targetdate');
        if ($req->notification) {
            $notify = $req->input('notification');
            $notification = implode(',', $notify);
        } else {
            $notification = null;
        }

        $postedby = $req->input('postedby');
        $data = array(
            'type' => $type,
            'department' => $department,
            'title' => $title,
            'file' => $cer,
            'observation' => $observation,
            'targetdate' => $due_date,
            'agreedaction' => $agreedaction,
            'postedby' => $postedby,
            'responsibility' => $resp,
            'notification' => $notification,
            'approvement' => $approvement,
            'status' => $status,
            'processstage' => $processstage,
            'impl_status' => $impl_status,
            'approvedby' => $approvedby,
            'active' => 'yes',
        );
        Nearmiss::where('id', $id)->update($data);
        $year = date("Y");
        $data = array('year' => $year);
        Nearmiss::where('id', $id)->update($data);
        $d = Nearmiss::latest('updated_at')->first();
        $name = Nearmiss::where('postedby', $postedby)->get();
        $ReviewDate = date('Y-m-d ', strtotime($d->created_at));
        $data2 = array(
            'Monthlyfreq' => $ReviewDate,
            'Weeklyfreq' => $ReviewDate,
        );
        Nearmiss::where('id', $d->id)->update($data2);
        Session::flash('msg', "Issue reopened successfully");
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
    public function delete(int $id)
    {

        $name = Nearmiss::where('id', $id)->pluck('title');
        $activity = "An issue is Removed with title " . $name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        Nearmiss::where('id', $id)->delete();
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
    public function editreq(string $id)
    {

        $type = Nearmisstypes::all();
        $id = explode(',', $id);
        $m = count($id);
        $n = 0;
        foreach ($id as $d) {
            if ($n < $m) {
                $data1[] = Nearmiss::where('id', $d)->first();
            }
            $n = $n + 1;
        }
        $data = array_filter($data1);
        $models = Modenadisable::First();
        return view('user/nearmiss/editrequest')->withData($data)
            ->with('id', $id)
            ->with('type', $type)
            ->with('model', $models);
    }
    public function editrequest(Request $req)
    {

        $m = count($req->docnumber);
        $type = $req->input('type');
        $observation = $req->input('observation');
        $agreedaction = $req->input('agreedaction');
        $correction = $req->input('correction');
        $rootcause = $req->input('rootcause');
        $correctiveaction = $req->input('correctiveaction');
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = Nearmiss::where('id', $d)->first();
            }
        }
        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    Nearmiss::where('id', $s)
                        ->update(array(
                            'type' => $req->type[$n],
                            'observation' => $req->observation[$n],
                            'agreedaction' => $req->agreedaction[$n],
                            'correction' => $req->correction[$n],
                            'rootcause' => $req->rootcause[$n],
                            'correctiveaction' => $req->correctiveaction[$n],
                        ));

                    $activity = "Issue is  Edited and Updated with new Details  where  ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                    ViewActivity::insert($datas);
                }
            }
            $n = $n + 1;
        }
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
    //edit responsibility
    public function editresp(int $id)
    {
        $type = Type::all();
        $issuereviewers = Near::get();
        $data = Nearmiss::where('id', $id)->get();
        $nearmiss = Nearmiss::find($id);
        $res = $nearmiss->responsibility;
        $resname = User::where('email', $res)->value('name');
        $user = User::all();
        $departments = Departments::all();
        $models = Modenadisable::First();
        return view('user.nearmiss.editresp')
            ->withDepartments($departments)
            ->withUser($user)
            ->withData($data)
            ->withType($type)
            ->withIssuereviewers($issuereviewers)
            ->withResname($resname)
            ->with('model', $models);
    }
    public function submittresp(Request $req)
    {

        $id = $req->input('id');
        $resp = $req->input('resp');
        $review = $req->input('reviewauthority');
        $reviewauthority = implode(',', $review);
        $closureauthority = $req->input('closureauthority');
        $closureauthority = implode(',', $closureauthority);
        $department = $req->input('department');
        $division = $req->input('division');
        $area = $req->input('area');
        $departmentname = Departments::where('id', $department)->value('departmentname');
        // if ($department == 1) {
        //     $data = array('responsibility' => $resp, 'reviewauthority' => $reviewauthority, 'closureauthority' => $closureauthority);
        //     Nearmiss::where('id', $id)->update($data);
        // } else {
        $exist = Nearmiss::where('department', $departmentname)->orderBy('id', 'desc')->first();
        if ($exist) {
            $c = $exist->count;
            $count = $c + 1;
        } else {
            $count = "1";
        }
        $data = array(
            'responsibility' => $resp, 'count' => $count, 'department' => $departmentname,
            'reviewauthority' => $reviewauthority, 'closureauthority' => $closureauthority,
            'division_id' => $division ?? 0, 'area_id' => $area ?? 0,
        );
        Nearmiss::where('id', $id)->update($data);
        $code = Departments::where('id', $department)->value('code');
        $code = strtoupper(substr($code, 0, 3));
        $y = Date("Y");
        $year = substr($y, -2);
        $issueid = $code . $count . "/" . $year;
        $data2 = array('issueid' => $issueid, 'year' => $y);
        Nearmiss::where('count', $count)
            ->where('department', $departmentname)
            ->update($data2);
        //}
        $issueid = DB::table('nearmiss')->where('id', $id)->value('issueid');
        $type = DB::table('nearmiss')->where('id', $id)->value('type');
        $department = DB::table('nearmiss')->where('id', $id)->value('department');
        $targetdate = DB::table('nearmiss')->where('id', $id)->value('targetdate');
        $data = array('department' => $departmentname, 'issueid' => $issueid, 'type' => $type, 'targetdate' => $targetdate);
        Mail::to($resp)->send(new NearmissResponsibilityChanged($data));
        $activity = "Near miss Issue is Edited and updated with Responsibility  as " . $resp . "  where Issue ID is  " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);
        Session::flash('msg', "Data edited successfully");
        $models = Modenadisable::First();
        return redirect()->back()->with('model', $models);
    }
    public function deptQuery($sort, string $nearmiss_status = null, bool $status = false, bool $approvement = false, $today = null, $processstage = false, bool $hasType = false, $type = null, bool $has_impl_status = false, $impl_status = null)
    {

        $dept = Nearmiss::select('departments.departmentname', $sort)
            ->rightjoin('departments', function ($join) use ($nearmiss_status, $today, $status, $approvement, $processstage, $type, $hasType, $has_impl_status, $impl_status) {
                $join->on('nearmiss.department', '=', 'departments.departmentname')
                    ->where(function ($query) use ($has_impl_status, $impl_status) {
                        if ($has_impl_status) {
                            $query->where('nearmiss.impl_status', $impl_status);
                        }

                    })
                    ->where(function ($query) use ($type, $hasType) {
                        if ($hasType) {
                            $query->where('nearmiss.type', $type);
                        }

                    })
                    ->where(function ($query) use ($processstage) {
                        if ($processstage) {
                            $query->where('nearmiss.processstage', 'Awaiting actions');
                        }

                    })
                    ->where(function ($query) use ($approvement) {
                        if ($approvement) {
                            $query->where('nearmiss.approvement', 'yes');
                        }

                    })
                    ->where(function ($query) use ($status, $nearmiss_status) {
                        if ($status) {
                            $query->where('nearmiss.status', $nearmiss_status);
                        }

                    })
                    ->where(function ($query) use ($today) {
                        if ($today != null) {
                            $query->where('nearmiss.targetdate', '<=', $today);
                        }

                    });
            })
            ->groupBy('departments.departmentname')
            ->orderBy('departments.departmentname', 'asc')
            ->get();
        return $dept;
    }
    public function closedQuery(bool $before = false)
    {

        $closed = Nearmiss::select(DB::raw('COUNT(nearmiss.targetdate) as number'))
            ->rightjoin('departments', function ($join) use ($before) {
                $join->on('nearmiss.department', '=', 'departments.departmentname')
                    ->where('nearmiss.approvement', 'yes')
                    ->where('nearmiss.status', 'closed')
                    ->where(function ($query) use ($before) {
                        if ($before) {
                            $query->where('nearmiss.closed', '<=', DB::raw('nearmiss.targetdate'));
                        } else {
                            $query->where('nearmiss.closed', '>', DB::raw('nearmiss.targetdate'));
                        }
                    });
            })->first();
        return $closed;
    }
    public function departmentQuery(bool $before = false)
    {
        $dept = Nearmiss::select('departments.departmentname', DB::raw('COUNT(nearmiss.targetdate) as number'))
            ->rightjoin('departments', function ($join) use ($before) {
                $join->on('nearmiss.department', '=', 'departments.departmentname')
                    ->where('nearmiss.approvement', 'yes')
                    ->where('nearmiss.status', 'closed')
                    ->where(function ($query) use ($before) {
                        if ($before) {
                            $query->where('nearmiss.closed', '<=', DB::raw('nearmiss.targetdate'));
                        } else {
                            $query->where('nearmiss.closed', '>', DB::raw('nearmiss.targetdate'));
                        }
                    });
            })
            ->groupBy('departments.departmentname')
            ->orderBy('departments.departmentname', 'asc')
            ->get();
        return $dept;
    }
    public function analysis()
    {

        $today = date('Y-m-d');
        $processes = Departments::select('departmentname')->orderBy('departmentname', 'asc');
        $process = [];
        foreach ($processes as $key) {
            $process[] = $key->departmentname;
        }
        $deptopen = $this->deptQuery(DB::raw('COUNT(nearmiss.status) as number'), 'open', true, true);
        $deptdue = $this->deptQuery(DB::raw('COUNT(nearmiss.targetdate) as number'), 'open', true, true, $today);
        $deptclose = $this->deptQuery(DB::raw('COUNT(nearmiss.status) as number'), 'closed', true, true);
        $dopen = [];
        foreach ($deptopen as $key) {
            $dopen[] = $key->number;
        }
        $due = [];
        foreach ($deptdue as $key) {
            $due[] = $key->number;
        }
        $dclose = [];
        foreach ($deptclose as $key) {
            $dclose[] = $key->number;
        }
        $awaitings = $this->deptQuery(DB::raw('COUNT(nearmiss.processstage) as number'), null, false, false, null, true);
        $awaiting = [];
        foreach ($awaitings as $key) {
            $awaiting[] = $key->number;
        }
        $names = Nearmisstypes::pluck('name');
        $open = Nearmiss::where('status', 'open')->get();
        $opencount = count($open);
        $currentyear = date('Y');
        $year1back = $currentyear - 1;
        $year2back = $currentyear - 2;
        $year3back = $currentyear - 3;
        $year4back = $currentyear - 4;
        $year10back = $currentyear - 9;
        //10 years
        $trends = [];
        for ($i = $year10back; $i <= $currentyear; $i++) {
            $trends[] = Nearmiss::where('approvement', 'yes')->where('year', $i)->count();
            $year[] = $i;
        }
        $trend = [];
        foreach ($trends as $key) {
            $trend[] = $key;
        }
        //5 years
        $year5 = [$year4back, $year3back, $year2back, $year1back, $currentyear];
        $trend5 = [];
        $trends5 = [];
        for ($i = $year4back; $i <= $currentyear; $i++) {
            $trends5[] = Nearmiss::where('approvement', 'yes')->where('year', $i)->count();
        }
        foreach ($trends5 as $key) {
            $trend5[] = $key;
        }
        //3 years
        $year3 = [$year2back, $year1back, $currentyear];
        $trends3 = [];
        for ($i = $year2back; $i <= $currentyear; $i++) {
            $trends3[] = Nearmiss::where('approvement', 'yes')->where('year', $i)->count();
        }
        $trend3 = [];
        foreach ($trends3 as $key) {
            $trend3[] = $key;
        }
        //2 years
        $year2 = [$year1back, $currentyear];
        $trends2 = [];
        for ($i = $year1back; $i <= $currentyear; $i++) {
            $trends2[] = Nearmiss::where('approvement', 'yes')->where('year', $i)->count();
        }
        $trend2 = [];
        foreach ($trends2 as $key) {
            $trend2[] = $key;
        }
        $types = Nearmisstypes::pluck('name');
        foreach ($types as $typ) {
            $dtypes = $this->deptQuery(DB::raw('COUNT(nearmiss.type) as number'), 'open', true, true, null, false, true, $typ);
            $typesd = [];
            foreach ($dtypes as $key) {
                $typesd[] = $key->number;
            }
        }
        $dhigh = $this->deptQuery(DB::raw('COUNT(nearmiss.type) as number'), 'open', true, true, null, false, true, 'High');
        $dmoderate = $this->deptQuery(DB::raw('COUNT(nearmiss.type) as number'), 'open', true, true, null, false, true, 'Medium');
        $dlow = $this->deptQuery(DB::raw('COUNT(nearmiss.type) as number'), 'open', true, true, null, false, true, 'Low');
        $highd = [];
        foreach ($dhigh as $key) {
            $highd[] = $key->number;
        }
        $moderated = [];
        foreach ($dmoderate as $key) {
            $moderated[] = $key->number;
        }
        $lowd = [];
        foreach ($dlow as $key) {
            $lowd[] = $key->number;
        }
        $totalhigh = $this->deptQuery(DB::raw('COUNT(nearmiss.type) as number'), null, false, false, null, false, true, 'High');
        $totalmoderate = $this->deptQuery(DB::raw('COUNT(nearmiss.type) as number'), null, false, false, null, false, true, 'Medium');
        $totallow = $this->deptQuery(DB::raw('COUNT(nearmiss.type) as number'), null, false, false, null, false, true, 'Low');
        $hightotal = [];
        foreach ($totalhigh as $key) {
            $hightotal[] = $key->number;
        }
        $moderatetotal = [];
        foreach ($totalmoderate as $key) {
            $moderatetotal[] = $key->number;
        }
        $lowtotal = [];
        foreach ($totallow as $key) {
            $lowtotal[] = $key->number;
        }
        $nmdue = Nearmiss::where('status', 'open')->where('targetdate', '<=', $today)->count();
        $actions = Nearmiss::wherein('processstage', array('Approved', 'Awaiting actions'))->count();
        $proposed = Nearmiss::where('processstage', 'Actions Proposed')->count();
        $awaitingclosure = Nearmiss::where('status', 'open')->where('impl_status', 'Completed-100%')->count();
        $depcodes = Departments::select('code')->orderBy('departmentname', 'asc')->get();
        $depcode = [];
        foreach ($depcodes as $key) {
            $depcode[] = $key->code;
        }
        $zerop = $this->deptQuery(DB::raw('COUNT(nearmiss.impl_status) as number'), null, false, true, null, false, false, null, true, 'Not Started-0%');
        $twop = $this->deptQuery(DB::raw('COUNT(nearmiss.impl_status) as number'), null, false, true, null, false, false, null, true, 'Progress-25%');
        $fivep = $this->deptQuery(DB::raw('COUNT(nearmiss.impl_status) as number'), null, false, true, null, false, false, null, true, 'Partially');
        $hundredp = $this->deptQuery(DB::raw('COUNT(nearmiss.impl_status) as number'), null, false, true, null, false, false, null, true, 'Completed-100%');
        $zero = [];
        foreach ($zerop as $key) {
            $zero[] = $key->number;
        }
        $two = [];
        foreach ($twop as $key) {
            $two[] = $key->number;
        }
        $five = [];
        foreach ($fivep as $key) {
            $five[] = $key->number;
        }
        $hundred = [];
        foreach ($hundredp as $key) {
            $hundred[] = $key->number;
        }
        $hundredcount = Nearmiss::where('impl_status', 'Completed-100%')->count();
        $fivecount = Nearmiss::where('impl_status', 'Partially Completed-50%')->count();
        $zerocount = Nearmiss::where('impl_status', 'Not Started-0%')->where('approvement', 'yes')->count();
        $closecount = Nearmiss::where('status', 'closed')->count();
        $closebefore = $this->closedQuery(true);
        $closeafter = $this->closedQuery(false);
        $beforecount = $closebefore->number;
        $aftercount = $closeafter->number;
        $deptbefore = $this->departmentQuery(true);
        $deptafter = $this->departmentQuery(false);
        $before = [];
        foreach ($deptbefore as $key) {
            $before[] = $key->number;
        }
        $after = [];
        foreach ($deptafter as $key) {
            $after[] = $key->number;
        }
        $data = Nearmiss::where('updated_at', '!=', 'NULL')->orderBy('updated_at', 'ASC')->paginate(10);
        $nmdepts = Nearmiss::pluck('department')->unique();
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $currentyear = date('Y');
        $lastyear = $currentyear - 1;
        $twoyear = $lastyear - 1;
        $threeyear = $lastyear - 2;
        //current year total
        $postdate = Nearmiss::where('approvement', 'yes')->where('year', $currentyear)->pluck('created_at');
        $monthtrend = [];
        $mm = [];
        foreach ($months as $k) {
            $count = 0;
            foreach ($postdate as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            $monthtrend[] = $count;
        }
        //current year closure
        $updatedat = Nearmiss::where('status', 'closed')->where('year', $currentyear)->pluck('updated_at');
        $closuretrend = [];
        $mm = [];
        foreach ($months as $k) {
            $count = 0;
            foreach ($updatedat as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            $closuretrend[] = $count;
        }
        //last year closure
        $updatedat = Nearmiss::where('status', 'closed')->where('year', $lastyear)->pluck('updated_at');
        $closuretrendlast = [];
        $mm = [];
        foreach ($months as $k) {
            $count = 0;
            foreach ($updatedat as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            $closuretrendlast[] = $count;
        }
        //last year total
        $updatedat = Nearmiss::where('approvement', 'yes')->where('year', $lastyear)->pluck('created_at');
        $issuestrendlast = [];
        $mm = [];
        foreach ($months as $k) {
            $count = 0;
            foreach ($updatedat as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            $issuestrendlast[] = $count;
        }

        //two year total
        $updatedat = Nearmiss::where('approvement', 'yes')->where('year', $twoyear)->pluck('created_at');
        $issuestrendtwo = [];
        $mm = [];
        foreach ($months as $k) {
            $count = 0;
            foreach ($updatedat as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            $issuestrendtwo[] = $count;
        }

        //three year total
        $updatedat = Nearmiss::where('approvement', 'yes')->where('year', $threeyear)->pluck('created_at');
        $issuestrendthree = [];
        $mm = [];
        foreach ($months as $k) {
            $count = 0;
            foreach ($updatedat as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            $issuestrendthree[] = $count;
        }
        $users = User::get();
        $cons = Nearmiss::select(DB::raw('postedby as number'))
            ->groupby('postedby')
            ->orderby(DB::raw('COUNT(postedby)'), 'desc')
            ->limit(10)
            ->get();
        $con = [];
        foreach ($cons as $key) {
            $con[] = $key->number;
        }
        $employees = EmployeeDetails::get();
        $models = Modenadisable::First();
        $today = date('Y-m-d');
        $two_previous_week = strtotime("-2 week");
        $two_previous_week = date('Y-m-d', $two_previous_week);
        $totalduelast2weekscount = Nearmiss::whereIn('department', $nmdepts)->where('status', 'open')->where('targetdate', '<=', $today)
            ->where('updated_at', '>=', $two_previous_week)
            ->where('updated_at', '<=', now())->count();
        $totalclosedlast2weekscount = Nearmiss::whereIn('department', $nmdepts)->where('status', 'closed')
            ->where('updated_at', '>=', $two_previous_week)
            ->where('updated_at', '<=', now())->count();
        //dd($beforecount);

        return view('user.nearmiss.analysis')->with([
            'totalduelast2weekscount' => $totalduelast2weekscount,
            'totalclosedlast2weekscount' => $totalclosedlast2weekscount,
            'model' => $models,
            'users' => $users,
            'con' => $con,
            'employees' => $employees,
            'data' => $data,
            'types' => $types,
            'nmdepts' => $nmdepts,
            'opencount' => $opencount,
            'closecount' => $closecount,
            'typesd' => collect($typesd),
            'beforecount' => $beforecount,
            'aftercount' => $aftercount,
            'nmdue' => $nmdue,
            'actions' => $actions,
            'proposed' => $proposed,
            'awaitingclosure' => $awaitingclosure,
            'names' => $names,
            'hundredcount' => $hundredcount,
            'fivecount' => $fivecount,
            'zerocount' => $zerocount,
            'monthtrend' => collect($monthtrend),
            'issuestrendlast' => collect($issuestrendlast),
            'closuretrend' => collect($closuretrend),
            'closuretrendlast' => collect($closuretrendlast),
            'issuestrendtwo' => collect($issuestrendtwo),
            'issuestrendthree' => collect($issuestrendthree),
            'dopen' => collect($dopen),
            'due' => collect($due),
            'dclose' => collect($dclose),
            'awaiting' => collect($awaiting),
            'process' => collect($process),
            'depcode' => collect($depcode),
            'trend' => collect($trend),
            'trend5' => collect($trend5),
            'trend3' => collect($trend3),
            'trend2' => collect($trend2),
            'year' => collect($year),
            'year5' => collect($year5),
            'year3' => collect($year3),
            'year2' => collect($year2),
            'highd' => collect($highd),
            'moderated' => collect($moderated),
            'lowd' => collect($lowd),
            'hightotal' => collect($hightotal),
            'moderatetotal' => collect($moderatetotal),
            'lowtotal' => collect($lowtotal),
            'zero' => collect($zero),
            'two' => collect($two),
            'five' => collect($five),
            'hundred' => collect($hundred),
            'before' => collect($before),
            'after' => collect($after),
        ]);
    }
    public function ajaxAnalysisQuery(bool $hasyear = false, int $lastyear = 0, bool $haspostdate = false, $postdate = null)
    {
        $cons = Nearmiss::select(DB::raw('reportedId as number'))
            ->where(function ($query) use ($hasyear, $lastyear, $haspostdate, $postdate) {
                if ($hasyear) {
                    $query->where('year', $lastyear);
                }
                if ($haspostdate) {
                    $query->where('created_at', '>=', $postdate);
                }
            })
            ->groupBy('reportedId')
            ->orderBy(DB::raw('COUNT(reportedId)'), 'desc')
            ->limit(10)
            ->get();
        return $cons;
    }
    public function ajaxAnalysis(Request $request)
    {

        $last6month = Carbon::now()->subMonths(6)->format('Y-m-d H:i:s');
        $last3month = Carbon::now()->subMonths(3)->format('Y-m-d H:i:s');
        $lastmonth = Carbon::now()->subMonths(1)->format('Y-m-d H:i:s');
        $lastweek = Carbon::now()->subWeeks(1)->format('Y-m-d H:i:s');
        switch ($request->id) {
            case null:
                $cons = $this->ajaxAnalysisQuery();
                break;
            case "last_year":
                $cons = $this->ajaxAnalysisQuery(true, date('Y') - 1);
                break;
            case "last_6_month":
                $cons = $this->ajaxAnalysisQuery(false, 0, true, $last6month);
                break;
            case "last_3_month":
                $cons = $this->ajaxAnalysisQuery(false, 0, true, $last3month);
                break;
            case "last_month":
                $cons = $this->ajaxAnalysisQuery(false, 0, true, $lastmonth);
            case "last_week":
                $cons = $this->ajaxAnalysisQuery(false, 0, true, $lastweek);
                break;
        }
        $con = [];
        //$users = User::get();
        $employees = EmployeeDetails::get();
        $currentyear = date('Y');
        $lastyear = $currentyear - 1;
        foreach ($cons as $key) {
            $con[] = $key->number;
        }
        for ($i = 0; $i < count($con); $i++) {
            switch ($request->id) {
                case null:
                    $cont = Nearmiss::where('reportedId', $con[$i])->get();
                    break;
                case "last_year":
                    $cont = Nearmiss::where('reportedId', $con[$i])->where("year", $lastyear)->get();
                    break;
                case "last_6_month":
                    $cont = Nearmiss::where('reportedId', $con[$i])->where('created_at', '>=', $last6month)->get();
                    break;
                case "last_3_month":
                    $cont = Nearmiss::where('reportedId', $con[$i])->where('created_at', '>=', $last3month)->get();
                    break;
                case "last_month":
                    $cont = Nearmiss::where('reportedId', $con[$i])->where('created_at', '>=', $lastmonth)->get();
                    break;
                case "last_week":
                    $cont = Nearmiss::where('reportedId', $con[$i])->where('created_at', '>=', $lastweek)->get();
                    break;
            }
            $count = count($cont);

            $dep = Nearmiss::where('reportedId', $con[$i])->value('department_reported_by');
            foreach ($employees as $value) {
                if ($con[$i] == $value->emp_number) {
                    $x = $value->emp_name . '-' . $con[$i];
                    $con[$i] = [$x, $dep, $count];
                }
            }
        }
        return [
            "con" => $con,
            "id" => $request->id,
        ];
    }
    public function intialReview(int $id)
    {
        $reviewers = getNonDeletedUsersEmail(Near::pluck('email')->toArray());
        if (!in_array(Auth::user()->email, $reviewers)) {
            return redirect()
                ->route('nearmiss.index')
                ->with('msg', "You're not authorized to access this page.");
        }
        $data = Nearmiss::findOrFail($id);
        $departments = Departments::all();
        $type = Nearmisstypes::get();
        $user = User::where('approved',true)->orderBy('name')->get();
        $issuereviewers = Near::get();
        $models = ModenaDisable::First();
        $targetinfo = Neartarget::all();
        $employee_details = EmployeeDetails::all();
        return view('user.nearmiss.initialreview')->with([
            'departments' => $departments,
            'type' => $type,
            'user' => $user,
            'targetinfo' => $targetinfo,
            'issuereviewers' => $issuereviewers,
            'model' => $models,
            'employee_details' => $employee_details,
            'data' => $data
        ]);
    }
    public function initialreviewed_ok(Request $req)
    {
        $id = $req->input('id');
        $currentUser = User::where('email',Auth::user()->email)->first();
        $updated_at = date('Y-m-d H:i:s', time());
        $review = $req->input('reviewauthority');
        $reviewauthority = implode(',', $review);
        $closure = $req->input('closureauthority');
        $closureauthority = implode(',', $closure);
        $dept = Departments::where('id', $req->input('department'))->value('departmentname');
        $department = $dept ? $dept : $req->input('department');
        $data = array(
            'type' => $req->input('type'),
            'department' => $department,
            'responsibility' => $req->input('responsibility'),
            'processstage' => 'Awaiting actions',
            'title' => $req->input('title'),
            'observation' => $req->input('observation'),
            'agreedaction' => $req->input('agreedaction'),
            'targetdate' => $req->input('targetdate'),
            'updated_at' => $updated_at,
            'approvement' => 'yes',
            'area_id' => $req->input('area'),
            'division_id' => $req->input('division'),
            'initial_reviewed_by' => $currentUser->id,
            'initial_reviewed_at' => now(),
            'reviewauthority' => $reviewauthority,
            'closureauthority' => $closureauthority,
        );
        Nearmiss::where('id', $id)->update($data);
        $data1 = Nearmiss::where('id', $id)->get();
        $title = Nearmiss::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . " process stage is updated as Awaiting actions.";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        ViewActivity::insert($datas);
        Session::flash('msg', "Initial reviewed successfully");
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
    public function notok_initial_remarks(int $id)
    {
        $models = Modenadisable::First();
        return view('user.nearmiss.initial_notok_remarks')->withId($id)->with('model', $models);
    }
    public function notok_initial_addremarks(Request $req)
    {
        $initial_remarks = $req->input('initial_remarks');
        $id = $req->input('id');
        $updated_at = date('Y-m-d H:i:s', time());
        $currentUser = User::where('email',Auth::user()->email)->first();
        $data = array(
            'status' => 'open',
            'processstage' => 'Entry Rejected',
            'initial_reviewed_rejection' => $initial_remarks,
            'initial_reviewed_by' => $currentUser->id,
            'initial_reviewed_at' => now(),
            'updated_at' => $updated_at,
        );
        Nearmiss::where('id', $id)->update($data);
        $title = Nearmiss::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . " process stage is updated as Entry Rejected.";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        Viewactivity::insert($datas);

        Session::flash('msg','Intial Review Rejected Successfully.');
        $models = Modenadisable::First();
        return redirect('/user/nearmiss')->with('model', $models);
    }
}
