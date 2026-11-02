<?php

namespace App\Http\Controllers;

use App;
use App\Admin;
use App\AuditRootCauseCorrectiveActionDetail;
use App\Categories;
use App\Departments;
use App\Hse;
use App\HseCategory;
use App\HseIssue;
use App\Mail\HSEAddIssue;
use App\Mail\HSEImplementationStatus;
use App\Mail\HSEIssueClosed;
use App\Mail\HSEProposed;
use App\Mail\HSEResponseNotification;
use App\Mail\HSEResponsibilityChanged;
use App\Mail\HSEReviewStatusFoundNotOK;
use App\Mail\HSEReviewStatusFoundOK;
use App\Mail\HSEUpdated;
use App\ModenaDisable;
use App\Modpageload;
use App\Multiplehseissue;
use App\Risk;
use App\Type;
use App\User;
use App\UserGate;
use App\ViewActivity;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Session;
use Validator;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;

use function PHPSTORM_META\map;

class HseObservationController extends Controller
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
        return view('user.hseobservation.home');
    }
    public function addissue()
    {
        $categories = HseCategory::get();
        $departments = Departments::where('is_active',true)->get();
        $type = DB::table('hsetypes')->get();
        $user = User::where('approved',true)->orderBy('name')->get();
        $models = ModenaDisable::First();

        return view('user.hseobservation.addissue')->with([
            'categories' => $categories,
            'departments' => $departments,
            'type' => $type,
            'user' => $user,
            'model' => $models,
        ]);
    }
    public function insertissue(Request $req)
    {
        $v = Validator::make($req->all(), [
            'duedate' => 'required|date',
            'department' => 'required'
        ]);
        if ($v->fails()) {
            $models = ModenaDisable::First();
            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
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
                    $destination_path = public_path('storage/issue_hse');
                    $file_array[$i]->move($destination_path, $new_file_name);
                    $file[] = $new_file_name;
                }
                $cer = implode(',', $file);
            }
            $postdate = date('d-m-Y h:i:s A', time());
            $categoryId = $req->input('category');
            $category = HseCategory::find($categoryId);
            $category = $category ? $category->categoryname : 'No Category';
            $approvement = "yes";
            $processstage = "Awaiting actions";
            $status = "open";
            $approvedby = Auth::user()->employeeid;
            $area = $req->input('area');
            $type = $req->input('type');
            $department = $req->input('department');
            $departmentname = Departments::where('id', $department)->value('departmentname');
            $division = $req->input('division')??'0';
            $sub_div = $req->input('sub_div')??'0';
            $title = $req->input('title');
            $observation = $req->input('observation');
            $agreedaction = $req->input('agreedaction');
            $due_date = $req->input('duedate');
            if ($req->frequency) {
                $freq = $req->input('frequency');
                $frequency = implode(',', $freq);
            } else {
                $frequency = null;
            }

            $resp = $req->input('responsibility');
            if ($req->notification) {
                $notify = $req->input('notification');
                $notification = implode(',', $notify);
            } else {
                $notification = null;
            }

            $postedby = $req->input('postedby');
            $exist = HseIssue::where('category_id', $categoryId)->orderBy('id', 'desc')->first();
            if ($exist) {
                $c = $exist->count;
                $count = $c + 1;
            } else {
                $count = "1";
            }
            $data4 = array(
                'postdate' => $postdate,
                'count' => $count,
                'category' => $category,
                'category_id' => $categoryId,
                'type' => $type,
                'department' => $department,
                'division_id' => $division,
                'area_id' => $sub_div,
                'title' => $title,
                'file' => $cer,
                'area' => $area,
                'observation' => $observation,
                'duedate' => $due_date,
                'frequency' => $frequency,
                'agreedaction' => $agreedaction,
                'postedby' => $postedby,
                'responsibility' => $resp,
                'notification' => $notification,
                'approvement' => $approvement,
                'processstage' => $processstage,
                'approvedby' => $approvedby,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            );
            HseIssue::create($data4);
            $activity = "An issue is added with title " . $title;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            $code = strtoupper(substr($category, 0, 3));
            $y = Date("Y");
            $year = substr($y, -2);
            $issueid = $code . $count . "/" . $year;
            $data5 = array('issueid' => $issueid, 'year' => $y);
            HseIssue::where('count', $count)->where('category_id', $categoryId)->update($data5);
            $d = HseIssue::orderBy('id', 'desc')->first();
            $ReviewDate = date('Y-m-d ', strtotime($d->postdate));
            $data2 = array(
                'Monthlyfreq' => $ReviewDate,
                'Weeklyfreq' => $ReviewDate,
            );
            HseIssue::where('id', $d->id)->update($data2);
            $id = $d->id;
            $postedby = $d->postedby;
            $name = Auth::user()->name;
            $files = $d->file;
            $expl = $files!="NULL" ? explode(',', $files) : [];
            if(count($expl)>0){
                foreach ($expl as $file) {
                    $data1 = array('id' => $id, 'issue_file' => $file);
                    DB::table('multiplehseissues')->insert($data1);
                }
            }
            $typename = DB::table('hsetypes')->where('id',$d->type)->value('hsetype');
            $hse_respon_emails = EmailNotification::where('module', 'hse')
                ->where('item', 'HSE Response Notification')
                ->where('is_blocked', false)
                ->first();
            if ($hse_respon_emails) {
                // Sending Mail(Responsibility Notification) To responsibile HOD
                $mailData = array(
                    'issueid' => $d->issueid,
                    'name' => $name,
                    'email_content' => $hse_respon_emails->email_content,
                    'item' => $hse_respon_emails->item,
                    'type' => $typename,
                    'department' => $departmentname,
                    'duedate' => $d->duedate ? date("d/m/Y", strtotime($d->duedate)) : '',
                );
                if ($notification) {
                    $noti = getNonDeletedUsersEmail(explode(',', $notification));
                    try{
                        Mail::to($noti)->send(new HSEResponseNotification($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$noti).' for issueid '.$d->issueid);
                    }
                }

                //sending mail for participants and management participants
                $hse_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_respon_emails->id)->get();
                if (count($hse_respon_userdatas) > 0) {
                    $adminaddedusers = $hse_respon_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new HSEResponseNotification($mailData));
                        }catch(\Exception $e){
                            Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$d->issueid);
                        }
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for HSE Response Notification');
            }
            // Sending Mail(AddIssue Notification) To Other HOD
            $hse_add_emails = EmailNotification::where('module', 'hse')
                ->where('item', 'HSE Issue Added')
                ->where('is_blocked', false)
                ->first();
            if ($hse_add_emails) {
                $mailData = array(
                    'issueid' => $d->issueid,
                    'name' => $name,
                    'email_content' => $hse_add_emails->email_content,
                    'item' => $hse_add_emails->item,
                    'type' => $typename,
                    'department' => $departmentname,
                    'duedate' => $d->duedate ? date("d/m/Y", strtotime($d->duedate)) : '',
                );
                if ($resp) {
                    try{
                        Mail::to($resp)->send(new HSEAddIssue($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to responsible person for issueid '.$d->issueid);
                    }
                }
                //sending mail for participants and management participants
                $hse_add_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_add_emails->id)->get();
                if (count($hse_add_userdatas) > 0) {
                    $adminaddedusers = $hse_add_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new HSEAddIssue($mailData));
                        }catch(\Exception $e){
                            Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$d->issueid);
                        }
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for Add HSE Issue');
            }
            Session::flash('msg', "Issue submitted successfully");
            $models = ModenaDisable::First();

            return redirect()->back()->with('model', $models);
        }
    }
    public function viewlist(string $type = null)
    {
        $today = date('Y-m-d');
        //below is superadmin related codes
        $hse = Modpageload::where('id', 1)->get();
        if (sizeof($hse) == 0) {
            Modpageload::insert(['id' => 1, 'hse' => 1]);
        } else {
            $hse = Modpageload::where('id', 1)->First();
            $hse->hse += 1;
            $hse->save();
            //Modpageload::update(['hse'=> $hse->hse]);
        }
        //end of superadmin codes
        //Assessment
        $issuedocs = DB::table('multiplehseissues')->get();
        $actiondocs = DB::table('multiplehseactions')->get();
        $issues = HseIssue::with(['approvedByHse', 'hseCategory','departmentHse'])->where(function ($query) use ($type) {
            if ($type != 'awaiting' && $type != 'proposed') {
                $query->where('status', 'open');
            }
            })->where(function ($query) use ($type) {
                $hsetypes = DB::table('hsetypes')->pluck('id','hsetype')->toArray();
                if (in_array($type,array_keys($hsetypes))) {
                    $query->where('type', $hsetypes[$type]);
                }
            })->where(function ($query) use ($type, $today) {
                if ($type == 'due') {
                    $query->where('duedate', '<', $today);
                }
            })->where(function ($query) use ($type) {
                if ($type == 'awaiting') {
                    $query->wherein('processstage', array('Approved', 'Awaiting actions'));
                } else if ($type == 'proposed') {
                    $query->where('processstage', 'Actions Proposed');
                } else if ($type == 'closure') {
                    $query->where('processstage', 'Actions Completed');
                }
            })->where('category', '!=', 'Near Miss')
            ->orderBy('id', 'desc')
            ->get();
        foreach ($issues as $dat) {
            if ($dat->duedate > $today) {
                $freq = explode(',', $dat->frequency);
                foreach ($freq as $f) {
                    if ($f == 'Monthly' && $today == date("Y-m-d", strtotime($dat->Monthlyfreq . ' + 31 days'))) {
                        $Monthlyfreq = date('Y-m-d');
                        $data1 = array('Monthlyfreq' => $Monthlyfreq);
                        HseIssue::where('id', $dat->id)->update($data1);
                        $this->sendMailHse($dat->id);
                    } elseif ($f == 'Weekly' && $today == date("Y-m-d", strtotime($dat->Weeklyfreq . ' + 7 days'))) {
                        $Weeklyfreq = date('Y-m-d');
                        $data1 = array('Weeklyfreq' => $Weeklyfreq);
                        HseIssue::where('id', $dat->id)->update($data1);
                        $this->sendMailHse($dat->id);
                    } elseif ($f == '2 Days Before' && $today == date("Y-m-d", strtotime($dat->duedate . ' - 2 days')) && $dat->Dayfreq == '0') {
                        $Dayfreq = '1';
                        $data1 = array('Dayfreq' => $Dayfreq);
                        HseIssue::where('id', $dat->id)->update($data1);
                        $this->sendMailHse($dat->id);
                    }
                }
            }
        }
        $hsetypes = DB::table('hsetypes')->pluck('hsetype','id')->toArray();
        $alltypenames = implode(',', array_values($hsetypes));

        $processstages = ['Awaiting actions','Actions Proposed','Action Approved',
                           'Action Rejected','Need More Action','Actions Completed'
                         ];
        $allpstages = implode(',', $processstages);

        $yearvalues = HseIssue::select(DB::raw('YEAR(duedate) AS year'))->where('status','open')->distinct()->get()->toArray();
        $year_arr = array();
        foreach($yearvalues as $yearvalue)
        {
            array_push($year_arr,$yearvalue['year']);
        }
        sort($year_arr);
        $years = implode(',', array_unique($year_arr));

        $hsedepts = HseIssue::groupBy('department')->where('status','open')->pluck('department')->toArray();
        $dept = Departments::pluck('id')->toArray();
        $defaultDepartments = array_diff($hsedepts,$dept);
        $departmentDB = Departments::whereIn('id',array_diff($hsedepts,$defaultDepartments))->pluck('departmentname')->toArray();
        $departmentDB = array_merge($departmentDB,$defaultDepartments);
        $alldeptnames = implode(',',$departmentDB);

        $categoriesname = HseCategory::pluck('categoryname')->toArray();
        $allcategoriesname = implode(',',$categoriesname);

        $responsible_emails = HseIssue::groupBy('responsibility')->where('status','open')->pluck('responsibility')->toArray();
        $usernameslist = array();
        foreach($responsible_emails as $responsible_email)
        {
            $resp_names = User::where('email',$responsible_email)->value('name');
            if($resp_names){
                array_push($usernameslist,$resp_names);
            }
        }
        $allusernames = implode(',',$usernameslist);

        $models = ModenaDisable::First();

        return view('user.hseobservation.issues')->with([
            'issues' => $issues,
            'hsetypes' => $hsetypes,
            'alltypenames'=>$alltypenames,
            'allpstages'=>$allpstages,
            'years'=>$years,
            'alldeptnames'=>$alldeptnames,
            'allcategoriesname'=>$allcategoriesname,
            'allusernames'=>$allusernames,
            'issuedocs' => $issuedocs,
            'actiondocs' => $actiondocs,
            'model' => $models,
        ]);
    }
    private function sendMailHse($id){
        $hse_respon_emails = EmailNotification::where('module', 'hse')
                ->where('item', 'HSE Response Notification')
                ->where('is_blocked', false)
                ->first();
        $d = HseIssue::with('departmentHse')->find($id);
        $name = User::where('employeeid', $d->postedby)->value('name');
        $typename = DB::table('hsetypes')->where('id', $d->type)->value('hsetype');
        if ($hse_respon_emails) {
            // Sending Mail(Responsibility Notification) To responsibile HOD
            $mailData = array(
                'issueid' => $d->issueid,
                'name' => $name,
                'email_content' => $hse_respon_emails->email_content,
                'item' => $hse_respon_emails->item,
                'type' => $typename,
                'department' => $d->departmentHse? $d->departmentHse->departmentname : $d->department,
                'duedate' => $d->duedate ? date("d/m/Y", strtotime($d->duedate)) : '',
            );
            if ($d->responsibility) {
                $noti = getNonDeletedUsersEmail($d->responsibility);
                try{
                    Mail::to($noti)->send(new HSEResponseNotification($mailData));
                }catch(\Exception $e){
                    Log::info('Mail not sent to '.$d->responsibility.' for issueid '.$d->issueid);
                }
            }

            //sending mail for participants and management participants
            $hse_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_respon_emails->id)->get();
            if (count($hse_respon_userdatas) > 0) {
                $adminaddedusers = $hse_respon_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas) > 0) {
                    try{
                        Mail::to($userdatas)->send(new HSEResponseNotification($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$d->issueid);
                    }
                }
            }
        } else {
            Session::flash('msg', 'Mail Sending Blocked for HSE Response Notification');
        }
    }
    public function saveMultipleFiles($request, $id, $model, $field, $name)
    {
        if ($request->hasFile($name)) {
            $file_array = $request->file($name);
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/issue_hse');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
                $model::insert([
                    'id' => $id,
                    $field => $new_file_name,
                ]);
            }
        }
    }
    public function hseActionAttachment(Request $request, int $id)
    {
        $this->saveMultipleFiles($request, $id, 'App\Multiplehseaction', 'action_file', 'fileToUpload');
        $this->saveMultipleFiles($request, $id, 'App\Multiplehseissue', 'issue_file', 'fileToUpload1');
        return response()->json(['data' => null]);
    }
    //edit responsibility
    public function editresp(int $id)
    {

        $data = HseIssue::find($id);
        $type = DB::table('hsetypes')->where('id',$data->type)->value('hsetype');
        $departments = Departments::where('is_active',true)->get();
        $resname = User::where('email', $data->responsibility)->value('name');
        $user = User::where('approved',true)->get();
        $models = ModenaDisable::First();
        return view('user.hseobservation.editresp',compact('type'))
            ->withUser($user)->withDepartments($departments)
            ->withData($data)
            ->withResname($resname)
            ->with('model', $models);
    }
    public function submittresp(Request $req)
    {
        $id = $req->input('id');
        $resp = $req->input('resp');
        $department = $req->input('department');
        $departmentname = Departments::where('id', $department)->value('departmentname');
        $division = $req->input('division');
        $area = $req->input('area');
        $a = User::get();
        $e = Auth::user()->email;
        $data = array(
            'responsibility' => $resp,
            'department' => $department,
            'division_id' => $division ?? 0,
            'area_id' => $area ?? 0,
            'updated_at' => now(),
        );
        HseIssue::where('id', $id)->update($data);
        $d = HSEIssue::with('departmentHse')->find($id);
        $hse_add_emails = EmailNotification::where('module', 'hse')
            ->where('item', 'HSE Responsibility Changed')
            ->where('is_blocked', false)
            ->first();
        if ($hse_add_emails) {
            $mailData = array(
                'issueid' => $d->issueid,
                'email_content' => $hse_add_emails->email_content,
                'item' => $hse_add_emails->item,
                'type' => DB::table('hsetypes')->where('id', $d->type)->value('hsetype'),
                'department' => $departmentname,
                'duedate' => $d->duedate ? date("d/m/Y", strtotime($d->duedate)) : '',
            );
            if ($resp) {
                try{
                    Mail::to($resp)->send(new HSEResponsibilityChanged($mailData));
                }catch(\Exception $e){
                    Log::info('Mail not sent to for issueid '.$d->issueid);
                }
            }
            //sending mail for participants and management participants
            $hse_add_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_add_emails->id)->get();
            if (count($hse_add_userdatas) > 0) {
                $adminaddedusers = $hse_add_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas) > 0) {
                    try{
                        Mail::to($userdatas)->send(new HSEResponsibilityChanged($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$d->issueid);
                    }
                }
            }
        } else {
            Session::flash('msg', 'Mail Sending Blocked for HSE Responsibility Changed');
        }
        //inserting values to activity table
        $activity = "Hse Observation Issue is Edited and updated with Responsibility  as " . $resp . "  where Issue ID is  " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //end of superadmin code
        Session::flash('msg', "Data edited successfully");
        $models = ModenaDisable::First();

        return redirect()->back()->with('model', $models);
    }
    public function closedissue()
    {
        $issues1 = HseIssue::with(['hseCategory','departmentHse'])->whereIn('status', array('closed', 'closed after due'))
            ->where('category', '!=', 'Near Miss')
            ->orderByDesc('id')
            ->get();
        $hsetypes = DB::table('hsetypes')->pluck('hsetype','id')->toArray();
        $alltypenames = implode(',', array_values($hsetypes));

        $processstages = ['Closed','Closed After Due'];
        $allpstages = implode(',', $processstages);

        $yearvalues = HseIssue::select(DB::raw('YEAR(duedate) AS year'))->whereIn('status', array('closed', 'closed after due'))->distinct()->get()->toArray();
        $year_arr = array();
        foreach($yearvalues as $yearvalue)
        {
            array_push($year_arr,$yearvalue['year']);
        }
        sort($year_arr);
        $years = implode(',', array_unique($year_arr));

        $hsedepts = HseIssue::groupBy('department')->whereIn('status', array('closed', 'closed after due'))->pluck('department')->toArray();
        $dept = Departments::pluck('id')->toArray();
        $defaultDepartments = array_diff($hsedepts,$dept);
        $departmentDB = Departments::whereIn('id',array_diff($hsedepts,$defaultDepartments))->pluck('departmentname')->toArray();
        $departmentDB = array_merge($departmentDB,$defaultDepartments);
        $alldeptnames = implode(',',$departmentDB);

        $categoriesname = HseCategory::pluck('categoryname')->toArray();
        $allcategoriesname = implode(',',$categoriesname);

        $models = ModenaDisable::First();

        return view('user.hseobservation.closedissue')->with([
            'issues1' => $issues1,
            'alltypenames'=>$alltypenames,
            'allpstages'=>$allpstages,
            'years'=>$years,
            'alldeptnames'=>$alldeptnames,
            'allcategoriesname'=>$allcategoriesname,
            'model' => $models,
            'hsetypes' => $hsetypes,
        ]);
    }

    /**
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function showClosedIssueDetail($id)
    {
        $response = [];
        try {
            $issue = HseIssue::with(['postedBy', 'approvedByHse', 'closedByHse','departmentHse'])->findOrFail($id);
            $models = ModenaDisable::First();
            $issuedocs = DB::table('multiplehseissues')->where('id', $issue->id)->get();
            $actiondocs = DB::table('multiplehseactions')->where('id', $issue->id)->get();
            $issue->type = DB::table('hsetypes')->where('id', $issue->type)->value('hsetype')??'--';
            $response['success'] = true;
            $response['view'] = view('user.hseobservation._closedissue_item', [
                'issue' => $issue,
                'model' => $models,
                'issuedocs' => $issuedocs,
                'actiondocs' => $actiondocs,
            ])->render();
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
        }

        return $response;
    }

    public function openissue()
    {
        $de = Auth::user()->email;
        $issues2 = HseIssue::where('responsibility', '=', $de)
            ->where('status', 'open')
            ->where('category', '!=', 'Near Miss')
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
        $issues2Ids = $issues2->pluck('id')->toArray();
        $issuedocs = DB::table('multiplehseissues')->whereIn('id', $issues2Ids)
            ->where('issue_file', '!=', 'NULL')
            ->get();
        $actiondocs = DB::table('multiplehseactions')->whereIn('id', $issues2Ids)
            ->where('action_file', '!=', 'NULL')
            ->get();
        $hsetypes = DB::table('hsetypes')->pluck('hsetype','id')->toArray();
        $alltypenames = implode(',', array_values($hsetypes));
        $processstages = ['Awaiting actions','Actions Proposed','Action Approved',
                           'Action Rejected','Need More Action','Actions Completed'
                         ];
        $allpstages = implode(',', $processstages);

        $yearvalues = HseIssue::select(DB::raw('YEAR(duedate) AS year'))->where('responsibility', '=', $de)
            ->where('status', 'open')->where('category', '!=', 'Near Miss')
            ->wherein('processstage', array(
                'Awaiting actions',
                'Actions Proposed',
                'Action Reviewed, OK',
                'Action Reviewed, Not OK',
                'rejected',
                'Need More Action',
                'Actions Completed',
            ))
            ->where('status', '!=', 'removed')->distinct()->get()->toArray();
        $year_arr = array();
        foreach($yearvalues as $yearvalue)
        {
            array_push($year_arr,$yearvalue['year']);
        }
        sort($year_arr);
        $years = implode(',', array_unique($year_arr));

        $hsedepts = HseIssue::groupBy('department')->where('responsibility', '=', $de)
            ->where('status', 'open')->where('category', '!=', 'Near Miss')
            ->wherein('processstage', array(
                'Awaiting actions',
                'Actions Proposed',
                'Action Reviewed, OK',
                'Action Reviewed, Not OK',
                'rejected',
                'Need More Action',
                'Actions Completed',
            ))
            ->where('status', '!=', 'removed')->pluck('department')->toArray();
        $dept = Departments::pluck('id')->toArray();
        $defaultDepartments = array_diff($hsedepts,$dept);
        $departmentDB = Departments::whereIn('id',array_diff($hsedepts,$defaultDepartments))->pluck('departmentname')->toArray();
        $departmentDB = array_merge($departmentDB,$defaultDepartments);
        $alldeptnames = implode(',',$departmentDB);

        $categoriesname = HseCategory::pluck('categoryname')->toArray();
        $allcategoriesname = implode(',',$categoriesname);
        $models = ModenaDisable::First();
        return view('user.hseobservation.openissue', compact('issues2'))->with('issuedocs', $issuedocs)->with('actiondocs', $actiondocs)->with('model', $models)
        ->with([
            'alltypenames'=>$alltypenames,
            'allpstages'=>$allpstages,
            'years'=>$years,
            'alldeptnames'=>$alldeptnames,
            'allcategoriesname'=>$allcategoriesname,
            'hsetypes' => $hsetypes,
        ]);
    }
    public function editopen()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = HseIssue::with('departmentHse')->find($id);
            $resp = User::where('email', $data->responsibility)->value('name');
            $type = DB::table('hsetypes')->get();
            $rec1 = DB::table('multiplehseissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec2 = DB::table('multiplehseactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $models = ModenaDisable::First();
            $department = Departments::where('id', $data->department)->value('departmentname') ?? $data->department;
            $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
            return view('user.hseobservation.editopen',compact('department'))
                ->withData($data)
                ->withRec1($rec1)
                ->withRec2($rec2)
                ->withType($type)
                ->with('resp', $resp)
                ->with('rootcorrectives', $rootcorrectives)
                ->with('model', $models);
        }
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
                $file_ext = $file_array[$i]->getClientOriginalExtension();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/issue_hse');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
            }
            $cer = implode(',', $file);
        }
        //$updated_at = date('d-m-Y h:i:s A', time());
        $type = $r->input('type');
        $rootcause = $r->input('rootcause');
        $correctiveaction = $r->input('correctiveaction');
        $correction = $r->input('correction');
        $id = $r->input('posted');
        $reviewer = UserGate::where('key', 'hse-observation-reviewer')->pluck('users_id')->toArray();
        $reviewer = getNonDeletedUsersEmail($reviewer);
        $typename = DB::table('hsetypes')->where('id', $type)->value('hsetype');
        if ($r->confirm_propose == 'yes') {
            $data = array(
                'type' => $type,
                'rootcause' => $rootcause,
                'correctiveaction' => $correctiveaction,
                'correction' => $correction,
                'action_file' => $cer,
                'processstage' => 'Actions Completed',
                'impl_status' => 'Completed-100%',
                'updated_at' => now(),
            );

            HseIssue::where('id', '=', $id)->update($data);
            $title = HseIssue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Action Completed and implementation_status as Completed-100%";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);

            $type = HseIssue::where('id', '=', $id)->value('type');
            $updateddata = HseIssue::where('id', $id)->first();
            $hse_imple_emails = EmailNotification::where('module', 'hse')
                ->where('item', 'HSE Implementation Status')
                ->where('is_blocked', false)
                ->first();
            if ($hse_imple_emails) {
                $mailData = array(
                    'issueid' => $updateddata->issueid,
                    'email_content' => $hse_imple_emails->email_content,
                    'item' => $hse_imple_emails->item,
                    'type' => $typename,
                    'department' => Departments::where('id', $updateddata->department)->value('departmentname')?? $updateddata->department,
                    'duedate' => $updateddata->duedate ? date("d/m/Y", strtotime($updateddata->duedate)) : '',
                );
                if (count($reviewer) > 0) {
                    try{
                        Mail::to($reviewer)->send(new HSEImplementationStatus($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                    }
                }
                //sending mail for participants and management participants
                $hse_imple_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_imple_emails->id)->get();
                if (count($hse_imple_userdatas) > 0) {
                    $adminaddedusers = $hse_imple_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new HSEImplementationStatus($mailData));
                        }catch(\Exception $e){
                            Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$updateddata->issueid);
                        }
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for HSE Implementation Status');
            }

        } else if ($r->confirm_propose == 'no') {
            $data = array(
                'type' => $type,
                'rootcause' => $rootcause,
                'correctiveaction' => $correctiveaction,
                'correction' => $correction,
                'action_file' => $cer,
                'processstage' => 'Actions Proposed',
                'updated_at' => now(),
            );
            HseIssue::where('id', $id)->update($data);

            $updateddata = HseIssue::where('id', $id)->first();
            // Sending Mail(AddIssue Notification) To Other HOD
            $hse_proposed_emails = EmailNotification::where('module', 'hse')
                ->where('item', 'HSE Issue Proposed Actions')
                ->where('is_blocked', false)
                ->first();
            if ($hse_proposed_emails) {
                $mailData = array(
                    'issueid' => $updateddata->issueid,
                    'email_content' => $hse_proposed_emails->email_content,
                    'item' => $hse_proposed_emails->item,
                    'type' => $typename,
                    'department' => Departments::where('id', $updateddata->department)->value('departmentname')?? $updateddata->department,
                    'duedate' => $updateddata->duedate ? date("d/m/Y", strtotime($updateddata->duedate)) : '',
                );
                if (count($reviewer) > 0) {
                    try{
                        Mail::to($reviewer)->send(new HSEProposed($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                    }
                }
                //sending mail for participants and management participants
                $hse_proposed_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_proposed_emails->id)->get();
                if (count($hse_proposed_userdatas) > 0) {
                    $adminaddedusers = $hse_proposed_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new HSEProposed($mailData));
                        }catch(\Exception $e){
                            Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$updateddata->issueid);
                        }
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for HSE Issue Proposed Actions');
            }
            $title = HseIssue::where('id', $id)->pluck('title');
            $activity = "An open issue " . $title . " process stage is updated as Action Proposed ";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
        }
        $action_files = $cer;
        $expl_action = explode(',', $action_files);
        foreach ($expl_action as $action_file) {
            $data1 = array('id' => $id, 'action_file' => $action_file);
            DB::table('multiplehseactions')->insert($data1);
        }
        $models = ModenaDisable::First();
        return redirect('/user/hseobservation/issues')->with('model', $models);
    }
    public function editrespondedissue()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = HseIssue::find($id);
            $resp = User::where('email', $data->responsibility)->value('name');
            $type = DB::table('hsetypes')->get();
            $department = Departments::where('id', $data->department)->value('departmentname') ?? $data->department;
            $rec1 = DB::table('multiplehseissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec2 = DB::table('multiplehseactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $models = ModenaDisable::First();
            return view('user.hseobservation.editrespondedissue',compact('department'))
                ->withData($data)
                ->withRec1($rec1)
                ->withRec2($rec2)
                ->withType($type)
                ->with('resp', $resp)
                ->with('model', $models);
        }
    }
    public function updaterespondedissue(Request $r)
    {
        $v = Validator::make($r->all(), [
            'rootcause' => 'required',
            'correctiveaction' => 'required',
            'correction' => 'required',
        ]);

        if ($v->fails()) {
            $models = ModenaDisable::First();

            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $cer = 'NULL';
            if ($r->hasFile('action_file')) {
                $file_array = $r->file('action_file');
                $array_len = count($file_array);
                $file = [];
                for ($i = 0; $i < $array_len; $i++) {
                    $file_size = $file_array[$i]->getClientOriginalName();
                    $file_ext = $file_array[$i]->getClientOriginalExtension();
                    $new_file_name = $file_size;
                    $destination_path = public_path('storage/issue_hse');
                    $file_array[$i]->move($destination_path, $new_file_name);
                    $file[] = $new_file_name;
                }
                $cer = implode(',', $file);
            }
            $type = $r->input('type');
            $rootcause = $r->input('rootcause');
            $correctiveaction = $r->input('correctiveaction');
            $correction = $r->input('correction');
            $id = $r->input('posted');

            $data = array(
                'type' => $type,
                'rootcause' => $rootcause,
                'correctiveaction' => $correctiveaction,
                'correction' => $correction,
                'action_file' => $cer,
                'status' => 'open',
                'processstage' => 'Actions Proposed',
                'updated_at' => now(),
            );

            HseIssue::where('id', $id)->update($data);

            $d = HseIssue::latest('updated_at')->first();
            $id = $d->id;
            $action_files = $d->action_file;
            $expl_action = explode(',', $action_files);
            foreach ($expl_action as $action_file) {
                $data1 = array('id' => $id, 'action_file' => $action_file);
                DB::table('multiplehseactions')->insert($data1);
            }
            $title = HseIssue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Actions Proposed and  status as open";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            $reviewer = UserGate::where('key', 'hse-observation-reviewer')->pluck('users_id')->toArray();
            $reviewer = getNonDeletedUsersEmail($reviewer);
            $hse_issue_emails = EmailNotification::where('module', 'hse')
                ->where('item', 'HSE Issue Updated')
                ->where('is_blocked', false)
                ->first();
            if ($hse_issue_emails) {
                $mailData = array(
                    'issueid' => $d->issueid,
                    'email_content' => $hse_issue_emails->email_content,
                    'item' => $hse_issue_emails->item,
                    'type' => DB::table('hsetypes')->where('id', $d->type)->value('hsetype'),
                    'department' => Departments::where('id', $d->department)->value('departmentname')?? $d->department,
                    'duedate' => $d->duedate ? date("d/m/Y", strtotime($d->duedate)) : '',
                );
                if (count($reviewer) > 0) {
                    try{
                        Mail::to($reviewer)->send(new HSEUpdated($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$d->issueid);
                    }
                }
                //sending mail for participants and management participants
                $hse_issue_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_issue_emails->id)->get();
                if (count($hse_issue_userdatas) > 0) {
                    $adminaddedusers = $hse_issue_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new HSEUpdated($mailData));
                        }catch(\Exception $e){
                            Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$d->issueid);
                        }
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for HSE Issue Updated');
            }

            echo "<script>alert('Issue edited successfully')</script>";
            $models = ModenaDisable::First();
            return redirect('/user/hseobservation/issues')->with('model', $models);
        }
    }
    public function reviewstatus(int $id)
    {
        $data = HseIssue::with('departmentHse')->find($id);
        $data->type = DB::table('hsetypes')->where('id', $data->type)->value('hsetype')??$data->type;
        $rec1 = DB::table('multiplehseissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
        $rec2 = DB::table('multiplehseactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
        $models = ModenaDisable::First();
        return view('user.hseobservation.statusreview')->withData($data)
            ->withRec1($rec1)
            ->withRec2($rec2)
            ->with('model', $models);
    }
    public function actionreviewed_ok(Request $req)
    {

        $id = $req->input('id');
        $data = array(
            'status' => 'open',
            'processstage' => 'Action Reviewed, OK',
            'active' => 'yes',
            'title' => $req->input('title'),
            'observation' => $req->input('observation'),
            'agreedaction' => $req->input('agreedaction'),
            'duedate' => $req->input('duedate'),
            'rootcause' => $req->input('rootcause'),
            'correctiveaction' => $req->input('correctiveaction'),
            'correction' => $req->input('correction'),
            'approvedby' => Auth::user()->employeeid,
            'updated_at' => now(),
            'impl_status' =>'Progress-25%'
        );
        HseIssue::where('id', $id)->update($data);
        $reviewer = UserGate::where('key', 'hse-observation-reviewer')->pluck('users_id')->toArray();
        $reviewer = getNonDeletedUsersEmail($reviewer);
        $updateddata = HseIssue::where('id', $id)->first();
        $name = User::where('employeeid', $updateddata->postedby)->value('name');
        $hse_imple_emails = EmailNotification::where('module', 'hse')
            ->where('item', 'HSE Review Status Found OK')
            ->where('is_blocked', false)
            ->first();
        if ($hse_imple_emails) {
            $mailData = array(
                'issueid' => $updateddata->issueid,
                'email_content' => $hse_imple_emails->email_content,
                'item' => $hse_imple_emails->item,
                'type' => $req->input('type'),
                'name' => $name,
                'department' => Departments::where('id', $updateddata->department)->value('departmentname')?? $updateddata->department,
                'duedate' => $updateddata->duedate ? date("d/m/Y", strtotime($updateddata->duedate)) : '',
            );
            if (count($reviewer) > 0) {
                try{
                    Mail::to($reviewer)->send(new HSEReviewStatusFoundOK($mailData));
                }catch(\Exception $e){
                    Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                }
            }
            $notification = $updateddata->notification;
            $noti = getNonDeletedUsersEmail(explode(',', $notification));
            if (count($noti) > 0) {
                try{
                    Mail::to($noti)->send(new HSEReviewStatusFoundOK($mailData));
                }catch(\Exception $e){
                    Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                }
            }
            //sending mail for participants and management participants
            $hse_imple_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_imple_emails->id)->get();
            if (count($hse_imple_userdatas) > 0) {
                $adminaddedusers = $hse_imple_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas) > 0) {
                    try{
                        Mail::to($userdatas)->send(new HSEReviewStatusFoundOK($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$updateddata->issueid);
                    }
                }
            }
        } else {
            Session::flash('msg', 'Mail Sending Blocked for HSE Review Status Found OK');
        }
        $title = HseIssue::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . " process stage is updated as Action Reviewed, OK and  status as open";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);

        Session::flash('msg', "Action reviewed successfully");
        $models = ModenaDisable::First();
        return redirect('/user/hseobservation/issues')->with('model', $models);
    }
    public function notok_remarks()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $models = ModenaDisable::First();
            return view('user.hseobservation.notok_remarks')->withId($id)->with('model', $models);
        }
    }
    public function notok_addremarks(Request $req)
    {

        $action_remarks = $req->input('action_remarks');
        $id = $req->input('id');
        $data = array(
            'status' => 'open',
            'processstage' => 'Action Reviewed, Not OK',
            'action_remarks' => $action_remarks,
            'updated_at' => now(),
            'impl_status' => 'Progress-10%',
        );
        HseIssue::where('id', $id)->update($data);
        $reviewer = UserGate::where('key', 'hse-observation-reviewer')->pluck('users_id')->toArray();
        $reviewer = getNonDeletedUsersEmail($reviewer);
        $updateddata = HseIssue::where('id', $id)->first();
        $name = User::where('employeeid', $updateddata->postedby)->value('name');
        $hse_imple_emails = EmailNotification::where('module', 'hse')
            ->where('item', 'HSE Review Status Found Not OK')
            ->where('is_blocked', false)
            ->first();
        if ($hse_imple_emails) {
            $mailData = array(
                'issueid' => $updateddata->issueid,
                'email_content' => $hse_imple_emails->email_content,
                'item' => $hse_imple_emails->item,
                'type' => DB::table('hsetypes')->where('id', $updateddata->type)->value('hsetype'),
                'name' => $name,
                'department' => Departments::where('id', $updateddata->department)->value('departmentname')?? $updateddata->department,
                'duedate' => $updateddata->duedate ? date("d/m/Y", strtotime($updateddata->duedate)) : '',
                'action_remarks' => $action_remarks,
            );
            if (count($reviewer) > 0) {
                try{
                    Mail::to($reviewer)->send(new HSEReviewStatusFoundNotOK($mailData));
                }catch(\Exception $e){
                    Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                }
            }
            $notification = $updateddata->notification;
            $noti = getNonDeletedUsersEmail(explode(',', $notification));
            if (count($noti) > 0) {
                try{
                    Mail::to($noti)->send(new HSEReviewStatusFoundNotOK($mailData));
                }catch(\Exception $e){
                    Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                }
            }
            //sending mail for participants and management participants
            $hse_imple_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_imple_emails->id)->get();
            if (count($hse_imple_userdatas) > 0) {
                $adminaddedusers = $hse_imple_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas) > 0) {
                    try{
                        Mail::to($userdatas)->send(new HSEReviewStatusFoundNotOK($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$updateddata->issueid);
                    }
                }
            }
        } else {
            Session::flash('msg', 'Mail Sending Blocked for HSE Review Status Found Not OK');
        }
        $title = HseIssue::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . " process stage is updated as Action Reviewed, Not OK and  status as open";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);

        echo "<script>alert('Action reviewed successfully')</script>";
        $models = ModenaDisable::First();
        return redirect('/user/hseobservation/issues')->with('model', $models);
    }
    public function closure(int $id)
    {

        $type = DB::table('hsetypes')->get();
        $data = HseIssue::with('departmentHse')->find($id);
        $rec1 = DB::table('multiplehseissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
        $rec2 = DB::table('multiplehseactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
        $models = ModenaDisable::First();
        return view('user.hseobservation.closurereview')->withData($data)
            ->withType($type)
            ->withRec1($rec1)
            ->withRec2($rec2)
            ->with('model', $models);
    }
    public function changes()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $models = ModenaDisable::First();
            return view('user.hseobservation.suggestchanges')->withId($id)->with('model', $models);
        }
    }
    public function suggestchanges(Request $req)
    {
        if ($req->action_modified == "yes") {
            $remarks = $req->input('remarks');
            $id = $req->input('id');

            $data = array(
                'status' => 'open',
                'processstage' => 'Need More Action',
                'impl_status' => 'Partially Completed-50%',
                'remarks' => $remarks, 'updated_at' => now(),
            );
            HseIssue::where('id', $id)->update($data);
            $reviewer = UserGate::where('key', 'hse-observation-reviewer')->pluck('users_id')->toArray();
            $reviewer = getNonDeletedUsersEmail($reviewer);
            $updateddata = HseIssue::where('id', $id)->first();
            $name = User::where('employeeid', $updateddata->postedby)->value('name');
            $hse_imple_emails = EmailNotification::where('module', 'hse')
                ->where('item', 'HSE Review Status Found Not OK')
                ->where('is_blocked', false)
                ->first();
            if ($hse_imple_emails) {
                $mailData = array(
                    'issueid' => $updateddata->issueid,
                    'email_content' => $hse_imple_emails->email_content,
                    'item' => $hse_imple_emails->item,
                    'type' => DB::table('hsetypes')->where('id', $updateddata->type)->value('hsetype'),
                    'name' => $name,
                    'department' => Departments::where('id', $updateddata->department)->value('departmentname')?? $updateddata->department,
                    'duedate' => $updateddata->duedate ? date("d/m/Y", strtotime($updateddata->duedate)) : '',
                    'action_remarks' => $remarks,
                );
                if (count($reviewer) > 0) {
                    try{
                        Mail::to($reviewer)->send(new HSEReviewStatusFoundNotOK($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                    }
                }
                $notification = $updateddata->notification;
                $noti = getNonDeletedUsersEmail(explode(',', $notification));
                if (count($noti) > 0) {
                    try{
                        Mail::to($noti)->send(new HSEReviewStatusFoundNotOK($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                    }
                }
                //sending mail for participants and management participants
                $hse_imple_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_imple_emails->id)->get();
                if (count($hse_imple_userdatas) > 0) {
                    $adminaddedusers = $hse_imple_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new HSEReviewStatusFoundNotOK($mailData));
                        }catch(\Exception $e){
                            Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$updateddata->issueid);
                        }
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for HSE Review Status Found Not OK');
            }
            $title = HseIssue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Need More Action, status as open and implementation_status as Partially Completed-50%";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
        } elseif ($req->action_modified == "no") {
            $remarks = $req->input('remarks');
            $id = $req->input('id');
            $data = array(
                'processstage' => 'Action Reviewed, OK',
                'impl_status' => 'Partially Completed-50%',
                'remarks' => $remarks,
                'updated_at' => now(),
            );

            HseIssue::where('id', $id)->update($data);
            $reviewer = UserGate::where('key', 'hse-observation-reviewer')->pluck('users_id')->toArray();
            $reviewer = getNonDeletedUsersEmail($reviewer);
            $updateddata = HseIssue::where('id', $id)->first();
            $name = User::where('employeeid', $updateddata->postedby)->value('name');
            $hse_imple_emails = EmailNotification::where('module', 'hse')
                ->where('item', 'HSE Review Status Found OK')
                ->where('is_blocked', false)
                ->first();
            if ($hse_imple_emails) {
                $mailData = array(
                    'issueid' => $updateddata->issueid,
                    'email_content' => $hse_imple_emails->email_content,
                    'item' => $hse_imple_emails->item,
                    'type' => DB::table('hsetypes')->where('id', $updateddata->type)->value('hsetype'),
                    'name' => $name,
                    'department' => Departments::where('id', $updateddata->department)->value('departmentname')?? $updateddata->department,
                    'duedate' => $updateddata->duedate ? date("d/m/Y", strtotime($updateddata->duedate)) : '',
                );
                if (count($reviewer) > 0) {
                    try{
                        Mail::to($reviewer)->send(new HSEReviewStatusFoundOK($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                    }
                }
                $notification = $updateddata->notification;
                $noti = getNonDeletedUsersEmail(explode(',', $notification));
                if (count($noti) > 0) {
                    try{
                        Mail::to($noti)->send(new HSEReviewStatusFoundOK($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                    }
                }
                //sending mail for participants and management participants
                $hse_imple_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_imple_emails->id)->get();
                if (count($hse_imple_userdatas) > 0) {
                    $adminaddedusers = $hse_imple_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new HSEReviewStatusFoundOK($mailData));
                        }catch(\Exception $e){
                            Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$updateddata->issueid);
                        }
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for HSE Review Status Found OK');
            }

            //inserting values to activity table
            $activity = "Issue Process Stage is Updated to 'Action Reviewed, OK' and Implementation Status is updated to 'Partially Completed-50%'  where Issue Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code

        }

        $models = ModenaDisable::First();
        return redirect('/user/hseobservation/issues')->with('model', $models);
    }
    public function closed()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $date = date('Y-m-d');
            $closedby = Auth::user()->employeeid;
            $data = array(
                'status' => 'closed',
                'processstage' => 'Accomplished',
                'closed' => $date,
                'closedby' => $closedby,
                'updated_at' => now(),
            );
            HseIssue::where('id', $id)
                ->update($data);
            $title = HseIssue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Accomplished, status as closed.";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            $updateddata = HSEIssue::with('departmentHse')->find($id);
            $hse_add_emails = EmailNotification::where('module', 'hse')
                ->where('item', 'HSE Issue Closed')
                ->where('is_blocked', false)
                ->first();
            if ($hse_add_emails) {
                $mailData = array(
                    'issueid' => $updateddata->issueid,
                    'email_content' => $hse_add_emails->email_content,
                    'item' => $hse_add_emails->item,
                    'type' => DB::table('hsetypes')->where('id', $updateddata->type)->value('hsetype'),
                    'department' => $updateddata->departmentHse ? $updateddata->departmentHse->departmentname : $updateddata->department,
                    'closed' => $updateddata->closed ? date("d/m/Y", strtotime($updateddata->closed)) : '',
                );
                if ($updateddata->responsibility) {
                    $resp = getNonDeletedUsersEmail($updateddata->responsibility);
                    try{
                        Mail::to($resp)->send(new HSEIssueClosed($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to responsible person for issueid '.$updateddata->issueid);
                    }
                }
                if($updateddata->notification){
                    $noti = getNonDeletedUsersEmail(explode(',', $updateddata->notification));
                    if (count($noti) > 0) {
                        try{
                            Mail::to($noti)->send(new HSEIssueClosed($mailData));
                        }catch(\Exception $e){
                            Log::info('Mail not sent to '.implode(',',$noti).' for issueid '.$updateddata->issueid);
                        }
                    }
                }
                //sending mail for participants and management participants
                $hse_add_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_add_emails->id)->get();
                if (count($hse_add_userdatas) > 0) {
                    $adminaddedusers = $hse_add_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new HSEIssueClosed($mailData));
                        }catch(\Exception $e){
                            Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$updateddata->issueid);
                        }
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for HSE Issue Closed');
            }
            echo "<script>alert('Issue closed successfully')</script>";
            $models = ModenaDisable::First();
            return redirect('/user/hseobservation/issues')->with('model', $models);
        }
    }
    public function search(Request $req)
    {
        $today = date('Y-m-d');
        $types = Type::all();
        $categories = Categories::all();
        $departments = DB::table('departments')->get();
        //$issuereviewers = Hse::pluck('email');
        $issuereviewers = UserGate::where('key', 'hse-observation-reviewer')->get();
        foreach ($issuereviewers as $r) {
            $is_admin = Admin::find($r->users_id);
            if ($is_admin) {
                $issuereviewers = $issuereviewers->pluck('admin.email');
            } else {
                $issuereviewers = $issuereviewers->pluck('user.email');
            }
        }
        $departmentname = null;
        $keyword = $req->input('keyword');
        //$mr = Hse::pluck('email');
        $users = User::pluck('name');
        $rec1 = DB::table('multiplehseissues')->distinct()->get();
        $rec2 = DB::table('multiplehseactions')->distinct()->get();
        $issues = HseIssue::wherein('status', array('open', 'open,due', 'closed', 'NULL'))
            ->wherein('approvement', array('yes', 'no'))
            ->where('status', '!=', 'removed')
            ->orderBy('category')
            ->where(function ($query) use ($req) {
                $query

                    ->where('category', '=', $req->input('keyword'))
                    ->orWhere('department', '=', $req->input('keyword'))
                    ->orWhere('type', '=', $req->input('keyword'))
                    ->orWhere('title', '=', $req->input('keyword'))
                    ->orWhere('file', '=', $req->input('keyword'))
                    ->orWhere('action_file', '=', $req->input('keyword'))
                    ->orWhere('observation', '=', $req->input('keyword'))
                    ->orWhere('postdate', '=', $req->input('keyword'))
                    ->orWhere('duedate', '=', $req->input('keyword'))
                    ->orWhere('agreedaction', '=', $req->input('keyword'))
                    ->orWhere('rootcause', '=', $req->input('keyword'))
                    ->orWhere('correctiveaction', '=', $req->input('keyword'))
                    ->orWhere('correction', '=', $req->input('keyword'))
                    ->orWhere('status', '=', $req->input('keyword'))
                    ->orWhere('postedby', '=', $req->input('keyword'))
                    ->orWhere('responsibility', '=', $req->input('keyword'))
                    ->orWhere('processstage', '=', $req->input('keyword'))
                    ->orWhere('impl_status', '=', $req->input('keyword'))
                    ->orWhere('remarks', '=', $req->input('keyword'))
                    ->orWhere('issueid', '=', $req->input('keyword'));
            })
            ->paginate(100)
            ->setpath('');
        $issues->appends(array(
            'department' => $req->input('keyword'),
        ));

        $models = ModenaDisable::First();
        return view('user.hseobservation.issues', compact('users', 'types', 'categories', 'departments', 'issues', 'issuereviewers'))->with('model', $models)->withRec1($rec1)->withRec2($rec2)->withMr($mr);
    }
    public function hundred()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = array(
                'processstage' => 'Actions Completed',
                'impl_status' => 'Completed-100%',
                'updated_at' => now(),
            );
            HseIssue::where('id', '=', $id)->update($data);
            $issueid = HseIssue::where('id', '=', $id)->pluck('issueid');
            $type = HseIssue::where('id', '=', $id)->pluck('type');
            $department = HseIssue::where('id', '=', $id)->pluck('department');
            $duedate = HseIssue::where('id', '=', $id)->pluck('duedate');
            $data = array(
                'issueid' => $issueid,
                'name' => Auth::user()->name,
                'type' => $type,
                'department' => $department,
                'duedate' => date("d/m/Y", strtotime($duedate)),
            );
            $title = HseIssue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Action Completed and implementation_status as Completed-100%";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            $reviewer = UserGate::where('key', 'hse-observation-reviewer')->pluck('users_id')->toArray();
            $reviewer = getNonDeletedUsersEmail($reviewer);
            $updateddata = HseIssue::where('id', $id)->first();
            $hse_imple_emails = EmailNotification::where('module', 'hse')
                ->where('item', 'HSE Implementation Status')
                ->where('is_blocked', false)
                ->first();
            if ($hse_imple_emails) {
                $mailData = array(
                    'issueid' => $updateddata->issueid,
                    'email_content' => $hse_imple_emails->email_content,
                    'item' => $hse_imple_emails->item,
                    'type' => DB::table('hsetypes')->where('id', $updateddata->type)->value('hsetype'),
                    'department' => Departments::where('id', $updateddata->department)->value('departmentname')?? $updateddata->department,
                    'duedate' => $updateddata->duedate ? date("d/m/Y", strtotime($updateddata->duedate)) : '',
                );
                if (count($reviewer) > 0) {
                    try{
                        Mail::to($reviewer)->send(new HSEImplementationStatus($mailData));
                    }catch(\Exception $e){
                        Log::info('Mail not sent to '.implode(',',$reviewer).' for issueid '.$updateddata->issueid);
                    }
                }
                //sending mail for participants and management participants
                $hse_imple_userdatas = EmailNotificationsUserDetails::where('notification_id', $hse_imple_emails->id)->get();
                if (count($hse_imple_userdatas) > 0) {
                    $adminaddedusers = $hse_imple_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new HSEImplementationStatus($mailData));
                        }catch(\Exception $e){
                            Log::info('Mail not sent to '.implode(',',$userdatas).' for issueid '.$updateddata->issueid);
                        }
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for HSE Implementation Status');
            }

            $models = ModenaDisable::First();
            return redirect('/user/hseobservation/issues')->with('model', $models);
        }
    }
    public function five()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $updated_at = date('d-m-Y h:i:s A', time());

            $data = array(
                'impl_status' => 'Partially Completed-50%',
                'updated_at' => now(),
            );
            HseIssue::where('id', '=', $id)
                ->update($data);
            $title = HseIssue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Action Completed and implementation_status as Partially Completed-50%";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);

            $models = ModenaDisable::First();
            return redirect('/user/hseobservation/issues')->with('model', $models);
        }
    }
    public function two()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $updated_at = date('d-m-Y h:i:s A', time());

            $data = array(
                'impl_status' => 'Progress-25%',
                'updated_at' => now(),
            );
            HseIssue::where('id', '=', $id)
                ->update($data);
            $models = ModenaDisable::First();
            return redirect('/user/hseobservation/issues')->with('model', $models);
        }
    }
    public function zero()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $updated_at = date('d-m-Y h:i:s A', time());

            $data = array(
                'impl_status' => 'Not Started-0%',
                'updated_at' => now(),
            );
            HseIssue::where('id', '=', $id)
                ->update($data);
            $models = ModenaDisable::First();
            return redirect('/user/hseobservation/issues')->with('model', $models);
        }
    }
    public function reopen(int $id)
    {
        $type = DB::table('hsetypes')->get();
        $user = User::all();
        $categories = Categories::all();
        $departments = Departments::where('is_active',true)->get();
        $data = HseIssue::where('id', $id)->first();
        $rec1 = DB::table('multiplehseissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
        $rec2 = DB::table('multiplehseactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
        $models = ModenaDisable::First();
        return view('user.hseobservation.reopen')->withCategories($categories)
            ->withDepartments($departments)
            ->withType($type)
            ->withUser($user)
            ->withData($data)
            ->with('id', $id)
            ->withRec1($rec1)
            ->withRec2($rec2)
            ->with('model', $models);
    }
    public function reopenissue(Request $req)
    {
        $v = Validator::make($req->all(), [
            'title' => 'required',
            'observation' => 'required',
            'duedate' => 'required|date|after:tomorrow',
            'agreedaction' => 'required',
        ]);

        if ($v->fails()) {
            $models = ModenaDisable::First();
            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $cer = 'NULL';

            if ($req->hasFile('file')) {
                $file_array = $req->file('file');
                $array_len = count($file_array);
                $file = [];
                for ($i = 0; $i < $array_len; $i++) {
                    $file_size = $file_array[$i]->getClientOriginalName();
                    $file_ext = $file_array[$i]->getClientOriginalExtension();
                    $new_file_name = $file_size;
                    $destination_path = public_path('storage/issue_hse');
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
            $departmentname = Departments::where('id', $department)->value('departmentname');
            $division = $req->input('division');
            $area = $req->input('area');
            $type = $req->input('type');
            $observation = $req->input('observation');
            $agreedaction = $req->input('agreedaction');
            $resp = $req->input('responsibility');
            $due_date = $req->input('duedate');

            if ($req->notification) {
                $notify = $req->input('notification');
                $notification = implode(',', $notify);
            } else {
                $notification = null;
            }

            $postedby = $req->input('postedby');

            $updated_at = date('d-m-Y h:i:s A', time());

            $data = array(
                'updated_at' => now(),
                'type' => $type,
                'department' => $department,
                'division_id' => $division ?? 0,
                'area_id' => $area ?? 0,
                'title' => $title,
                'file' => $cer,
                'observation' => $observation,
                'duedate' => $due_date,
                'agreedaction' => $agreedaction,
                'postedby' => $postedby,
                'responsibility' => $resp,
                'notification' => $notification,
                'approvement' => $approvement,
                'status' => $status,
                'processstage' => $processstage,
                'impl_status' => $impl_status,
                'year' => date("Y"),
                'approvedby' => $approvedby,
                'active' => 'yes',
            );
            HseIssue::where('id', $id)->update($data);


            $d = HseIssue::latest('updated_at')->first();
            $name = User::where('employeeid', $postedby)->get();
            $id = $d->id;

            $files = $d->file;

            $expl = explode(',', $files);

            foreach ($expl as $file) {
                $data1 = array('id' => $id, 'issue_file' => $file);
                DB::table('multiplehseissues')->insert($data1);
            }

            Session::flash('msg', "Issue reopened successfully");
            $models = ModenaDisable::First();
            return redirect()->back()->with('model', $models);
        }
    }
    public function delete(int $id)
    {

        $name = HseIssue::where('id', $id)->pluck('title');
        $activity = "An issue is Removed with title " . $name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        DB::delete('delete from hseissues where id = :eid', ['eid' => $id]);
        $models = ModenaDisable::First();
        return redirect('/user/hseobservation/issues')->with('model', $models);
    }
    public function editreq(string $id)
    {

        $type = DB::table('hsetypes')->get();
        $id = explode(',', $id);
        $m = count($id);
        $n = 0;
        foreach ($id as $d) {
            if ($n < $m) {
                $data1[] = HseIssue::where('id', $d)->first();
            }
            $n = $n + 1;
        }
        $data = $data1;
        $models = ModenaDisable::First();
        return view('user/hseobservation/editrequest')->withData($data)
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
                $data2[] = HseIssue::where('id', $d)->first();
            }
        }

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    DB::table('hseissues')->where('id', $s)
                        ->update(array(
                            'type' => $req->type[$n],
                            'observation' => $req->observation[$n],
                            'agreedaction' => $req->agreedaction[$n],
                            'correction' => $req->correction[$n],
                            'rootcause' => $req->rootcause[$n],
                            'correctiveaction' => $req->correctiveaction[$n],
                            'updated_at' => now(),
                        ));

                    $activity = "Issue is  Edited and Updated with new Details  where  ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $now = Carbon::now()->format('Y-m-d H:i:s');
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
                    ViewActivity::insert($datas);
                }
            }
            $n = $n + 1;
        }
        $models = ModenaDisable::First();
        return redirect('/user/hseobservation/issues')->with('model', $models);
    }
    public function analysis()
    {
        $today = date('Y-m-d');
        $hseDepartments = HseIssue::distinct()->pluck('department')->toArray();
        $deptTables = Departments::pluck('id')->toArray();
        $hseDeletedDepartments = array_diff($hseDepartments,$deptTables);
        $disabledDepartments = Departments::where('is_active',false)->pluck('code')->toArray();
        //Fetch the department codes from the database
        $depcodes = DB::select("select code from departments order by departmentname ASC");
        $depcode = [];
        foreach ($depcodes as $key) {
            array_push($depcode, $key->code);
        }
        $departmentNames = Departments::pluck('departmentname','code')->toArray();
        $hseDeletedDepartmentNames = array_combine($hseDeletedDepartments, $hseDeletedDepartments);
        $departmentNames = array_merge($departmentNames, $hseDeletedDepartmentNames);

        //Getting the count of open issues for each department
        $deptopen = DB::select("select s.id,COUNT(r.status)AS number
                       FROM  hseissues AS r
                       right JOIN departments AS s
                       ON r.department = s.id
                       and r.approvement='yes'and r.status='open'
                       group by s.id, s.departmentname
                       order by s.departmentname ASC
                      ");
        $dopen = [];
        foreach ($deptopen as $key) {
            array_push($dopen, $key->number);
        }
        //Getting the count of open issues for each deleted department and department codes
        $deletedDeparmentCount = [];
        $zeroRecordsDeletedDepartments = [];
        foreach($hseDeletedDepartments as $department){
            $count = HseIssue::where('department',$department)->where('approvement','yes')->where('status','open')->get('id')->count();
            if($count > 0){
                $deletedDeparmentCount[] = $count;
            }else{
                $zeroRecordsDeletedDepartments[] = $department;
            }
        }
        $dopen = array_merge($dopen, $deletedDeparmentCount);
        $depcodeForOpenDue = array_merge($depcode, array_diff($hseDeletedDepartments,$zeroRecordsDeletedDepartments));
        //END of getting the count of open issues for each deleted department and department codes

        //Getting the count of due issues for each department
        $deptdue = DB::select("select s.id,COUNT(r.duedate)AS number
                        FROM  hseissues AS r
                        right JOIN departments AS s
                        ON r.department = s.id
                        and r.approvement='yes' and r.status='open' and r.duedate < '$today'
                        group by s.id, s.departmentname
                        order by s.departmentname ASC
                        ");
        $due = [];
        foreach ($deptdue as $key) {
            array_push($due, $key->number);
        }
        $deletedDeparmentCount = [];
        foreach($hseDeletedDepartments as $department){
            $count = HseIssue::where('department',$department)->where('approvement','yes')->where('status','open')->where('duedate','<',$today)->get('id')->count();
            if($count > 0){
                $deletedDeparmentCount[] = $count;
            }
        }
        $due = array_merge($due, $deletedDeparmentCount);
        //Getting the count of the awaiting actions for each department
        $awaitings = DB::select("select s.id,COUNT(r.processstage)AS number
                    FROM  hseissues AS r
                    right JOIN departments AS s
                    ON r.department = s.id
                    and r.processstage='Awaiting actions'
                    group by s.id, s.departmentname
                    order by s.departmentname ASC
                     ");
        $awaiting = [];
        foreach ($awaitings as $key) {
            array_push($awaiting, $key->number);
        }
        //Getting count of the awaiting actions for each deleted department
        $deletedDeparmentCount = [];
        $departmentsForAwaiting = HseIssue::where('processstage', 'Awaiting actions')->pluck('department')->unique()->toArray();
        $departmentsForAwaiting = array_diff($departmentsForAwaiting,$deptTables);

        foreach($departmentsForAwaiting as $department){
            $deletedDeparmentCount[] = HseIssue::where('department',$department)->where('approvement','yes')->where('processstage','Awaiting actions')->get('id')->count();
        }
        $awaiting = array_merge($awaiting, $deletedDeparmentCount);
        $depcodeForAwaitingIssues = array_merge($depcode,$departmentsForAwaiting);

        $names = DB::table('hsetypes')->pluck('hsetype','id');


        $years = DB::select("select year from years order by id ASC");
        $year = [];
        foreach ($years as $key) {
            array_push($year, $key->year);
        }
        $trends = DB::select("select s.year,COUNT(r.year)AS number
                    FROM  hseissues AS r
                    right JOIN years AS s
                    ON r.year= s.year
                    and r.approvement='yes'
                    group by s.year
                    order by s.year ASC
                ");
        $trend = [];
        foreach ($trends as $key) {
            array_push($trend, $key->number);
        }
        $types = DB::table('hsetypes')->pluck('hsetype');
        //Getting count of the types for each deleted department
        $hsetypes = DB::table('hsetypes')->pluck('id','hsetype')->toArray();
        $departmentsForType = HseIssue::whereIn('type', array_values($hsetypes))->where('status','open')->pluck('department')->unique()->toArray();
        $departmentsForType = array_diff($departmentsForType,$deptTables);
        $depcodeForTypeIssues = array_merge($depcode,$departmentsForType);

        $typeWiseData = [];
        $typeDataArrays = [];

        foreach ($hsetypes as $typeName => $typeId) {
            $typeKey = strtolower($typeName);
            // Query department-wise count for each type
            $typeWiseData[$typeKey] = DB::select("
                SELECT s.id, COUNT(r.type) AS number
                FROM hseissues AS r
                RIGHT JOIN departments AS s
                    ON r.department = s.id
                    AND r.type = ?
                    AND r.approvement = 'yes'
                    AND r.status = 'open'
                GROUP BY s.id, s.departmentname
                ORDER BY s.departmentname ASC
            ", [$typeId]);

            // Extract just the numbers into a flat array (similar to $dhigh, etc.)
            $typeDataArrays[$typeKey] = array_map(function($item) { return $item->number; }, $typeWiseData[$typeKey]);
        }
        extract($typeDataArrays);
        $highd = $high ?? [];
        $moderated = $medium ?? [];
        $lowd = $low ?? [];
        //Getting the count of the deleted departments for each type
        $typeKeys = array_keys($hsetypes);
        $normalizedTypes = [];
        foreach ($hsetypes as $typeName => $typeId) {
            $normalizedTypes[strtolower($typeName)] = $typeId;
        }
        foreach (array_keys($normalizedTypes) as $key) {
            ${$key . 'deleted'} = [];
        }
        $result = [];
        foreach ($normalizedTypes as $typeKey => $typeId) {
            $row = [];
            foreach ($departmentsForType as $department) {
                $count = HseIssue::where('department', $department)
                    ->where('approvement', 'yes')
                    ->where('status', 'open')
                    ->where('type', $typeId)
                    ->count();
                $row[$department] = $count;
            }
            $result[$typeKey] = $row;
        }
        foreach ($normalizedTypes as $typeKey => $typeId) {
            ${$typeKey . 'deleted'} = array_merge(
                ${$typeKey . 'deleted'},
                array_values($result[$typeKey] ?? [])
            );
        }
        $highd = array_merge($highd, $highdeleted ?? []);
        $moderated = array_merge($moderated, $mediumdeleted ?? []);
        $lowd = array_merge($lowd, $lowdeleted ?? []);

        $departmentsForType = HseIssue::whereIn('type', array_values($hsetypes))->pluck('department')->unique()->toArray();
        $departmentsForType = array_diff($departmentsForType,$deptTables);
        $depcodeForTotalTypeIssues = array_merge($depcode,$departmentsForType);

        //Getting the count of the total types for each department
        $typeWiseData = [];
        $typeDataArrays = [];
        foreach ($hsetypes as $typeName => $typeId) {
            $typeKey = strtolower($typeName);
            $typeWiseData[$typeKey] = DB::select("
                SELECT s.id, COUNT(r.type) AS number
                FROM hseissues AS r
                RIGHT JOIN departments AS s
                    ON r.department = s.id
                    AND r.type = ?
                GROUP BY s.id, s.departmentname
                ORDER BY s.departmentname ASC
            ", [$typeId]);
            $typeDataArrays[$typeKey] = array_map(function($item) { return $item->number; }, $typeWiseData[$typeKey]);

        }
        extract($typeDataArrays);
        $hightotal = $high ?? [];
        $moderatetotal = $medium ?? [];
        $lowtotal = $low ?? [];
        //Getting the count of the total types for each department
        $typeKeys = array_keys($hsetypes);
        $normalizedTypes = [];
        foreach ($hsetypes as $typeName => $typeId) {
            $normalizedTypes[strtolower($typeName)] = $typeId;
        }
        foreach (array_keys($normalizedTypes) as $key) {
            ${$key . 'totaldeleted'} = [];
        }
        $result = [];
        foreach ($normalizedTypes as $typeKey => $typeId) {
            $row = [];
            foreach ($departmentsForType as $department) {
                $count = HseIssue::where('department', $department)
                    ->where('type', $typeId)
                    ->count();
                $row[$department] = $count;
            }
            $result[$typeKey] = $row;
        }
        foreach ($normalizedTypes as $typeKey => $typeId) {
            ${$typeKey . 'totaldeleted'} = array_merge(
                ${$typeKey . 'totaldeleted'},
                array_values($result[$typeKey] ?? [])
            );
        }
        $hightotal = array_merge($hightotal, $hightotaldeleted ?? []);
        $moderatetotal = array_merge($moderatetotal, $mediumtotaldeleted ?? []);
        $lowtotal = array_merge($lowtotal, $lowtotaldeleted ?? []);
        //Get the count of the implementation status for each department
        $depcodeForImplementStatusIssues = array_merge($depcode,$hseDeletedDepartments);
        $zerop = DB::select("select s.id,COUNT(r.id)AS number
                       FROM  hseissues AS r
                       right JOIN departments AS s
                       ON r.department= s.id
                       and  r.impl_status='Not Started-0%' and r.approvement='yes'
                       group by s.id, s.departmentname
                       order by s.departmentname ASC
                      ");
        $tenp = DB::select("select s.id,COUNT(r.id)AS number
                        FROM hseissues AS r
                        right JOIN departments AS s
                        ON r.department= s.id
                        and  r.impl_status='Progress-10%' and r.approvement='yes'
                        group by s.id, s.departmentname
                        order by s.departmentname ASC
                        ");
        $twop = DB::select("select s.id,COUNT(r.id)AS number
                        FROM hseissues AS r
                        right JOIN departments AS s
                        ON r.department= s.id
                        and  r.impl_status='Progress-25%' and r.approvement='yes'
                        group by s.id, s.departmentname
                        order by s.departmentname ASC
                        ");

        $fivep = DB::select("select s.id,COUNT(r.id)AS number
                        FROM  hseissues AS r
                        right JOIN departments AS s
                        ON r.department= s.id
                        and  r.impl_status='Partially Completed-50%' and r.approvement='yes'
                        group by s.id, s.departmentname
                        order by s.departmentname ASC
                    ");
        $hundredp = DB::select("select s.id,COUNT(r.id)AS number
                        FROM  hseissues AS r
                        right JOIN departments AS s
                        ON r.department= s.id
                        and  r.impl_status='Completed-100%' and r.approvement='yes'
                        group by s.id, s.departmentname
                        order by s.departmentname ASC
                    ");
        $zero = [];
        foreach ($zerop as $key) {
            array_push($zero, $key->number);
        }
        $deletedDeparmentCount = [];
        foreach($hseDeletedDepartments as $department){
            $deletedDeparmentCount[] = HseIssue::where('department',$department)->where('impl_status','Not Started-0%')->get('id')->count();
        }
        $zero = array_merge($zero, $deletedDeparmentCount);
        $ten = [];
        foreach ($tenp as $key) {
            array_push($ten, $key->number);
        }
        $deletedDeparmentCount = [];
        foreach($hseDeletedDepartments as $department){
            $deletedDeparmentCount[] = HseIssue::where('department',$department)->where('impl_status','Progress-10%')->get('id')->count();
        }
        $ten = array_merge($ten, $deletedDeparmentCount);
        $two = [];
        foreach ($twop as $key) {
            array_push($two, $key->number);
        }
        $deletedDeparmentCount = [];
        foreach($hseDeletedDepartments as $department){
            $deletedDeparmentCount[] = HseIssue::where('department',$department)->where('impl_status','Progress-25%')->get('id')->count();
        }
        $two = array_merge($two, $deletedDeparmentCount);
        $five = [];
        foreach ($fivep as $key) {
            array_push($five, $key->number);
        }
        $deletedDeparmentCount = [];
        foreach($hseDeletedDepartments as $department){
            $deletedDeparmentCount[] = HseIssue::where('department',$department)->where('impl_status','Partially Completed-50%')->get('id')->count();
        }
        $five = array_merge($five, $deletedDeparmentCount);
        $hundred = [];
        foreach ($hundredp as $key) {
            array_push($hundred, $key->number);
        }
        $deletedDeparmentCount = [];
        foreach($hseDeletedDepartments as $department){
            $deletedDeparmentCount[] = HseIssue::where('department',$department)->where('impl_status','Completed-100%')->get('id')->count();
        }
        $hundred = array_merge($hundred, $deletedDeparmentCount);

        $deptbefore = DB::select("select s.id,COUNT(r.duedate)AS number
                        FROM  hseissues AS r
                        right JOIN departments AS s
                        ON r.department = s.id
                        and r.approvement='yes' and r.status='closed' and r.closed <= r.duedate
                        group by s.id, s.departmentname
                        order by s.departmentname ASC
                        ");
        $deptafter = DB::select("select s.id,COUNT(r.status)AS number
                       FROM  hseissues AS r
                       right JOIN departments AS s
                       ON r.department = s.id
                       and r.approvement='yes'and r.status='closed'and r.closed > r.duedate
                       group by s.id, s.departmentname
                       order by s.departmentname ASC
                      ");
        $before = [];
        foreach ($deptbefore as $key) {
            array_push($before, $key->number);
        }
        //Getting the count of open issues for each deleted department and department codes
        $deletedDeparmentCount = [];
        $zeroRecordsDeletedDepartments = [];
        foreach($hseDeletedDepartments as $department){
            $count = HseIssue::where('department',$department)->where('approvement','yes')->where('status','closed')->whereColumn('closed', '<=', 'duedate')->get('id')->count();
            if($count > 0){
                $deletedDeparmentCount[] = $count;
            }else{
                $zeroRecordsDeletedDepartments[] = $department;
            }
        }
        $before = array_merge($before, $deletedDeparmentCount);
        $after = [];
        foreach ($deptafter as $key) {
            array_push($after, $key->number);
        }
        $deletedDeparmentCount = [];
        $zeroRecordsDeletedDepartments = [];
        foreach($hseDeletedDepartments as $department){
            $count = HseIssue::where('department',$department)->where('approvement','yes')->where('status','closed')->whereColumn('closed', '>', 'duedate')->get('id')->count();
            if($count > 0){
                $deletedDeparmentCount[] = $count;
            }else{
                $zeroRecordsDeletedDepartments[] = $department;
            }
        }
        $after = array_merge($after, $deletedDeparmentCount);
        $departmentsForClosed = HseIssue::where('status', 'closed')->pluck('department')->unique()->toArray();
        $departmentsForClosed = array_diff($departmentsForClosed,$deptTables);
        $depcodeForClosedIssues = array_merge($depcode,$departmentsForClosed);
        $data = HseIssue::with('departmentHse')->whereNotNull('updated_at')->orderByDesc('updated_at')->limit(10)->get();
        $nmdepts = HseIssue::pluck('department')->unique();
        $departmentDB = Departments::pluck('departmentname','id')->toArray();
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $currentyear = date('Y');
        $lastyear = $currentyear - 1;
        $twoyear = $lastyear - 1;
        $threeyear = $lastyear - 2;
        //Closure trend
        $CurrentYearMonthlyClosed = HseIssue::where('status','Closed')->whereYear('closed', $currentyear)->pluck('closed');
        $closuretrend = $this->getCounts($CurrentYearMonthlyClosed, $months);
        //Monthly trend current year
        $postdate = HseIssue::whereRaw("YEAR(STR_TO_DATE(postdate, '%d-%m-%Y %h:%i:%s %p')) = ?", [$currentyear])->pluck('postdate');
        $monthtrend = $this->getCounts($postdate, $months);
        //Monthly Trend last year
        $updatedat = HseIssue::whereRaw("YEAR(STR_TO_DATE(postdate, '%d-%m-%Y %h:%i:%s %p')) = ?", [$lastyear])->pluck('postdate');
        $closuretrendlast = $this->getCounts($updatedat, $months);

        //Monthly Trend two year
        $updatedat = HseIssue::whereRaw("YEAR(STR_TO_DATE(postdate, '%d-%m-%Y %h:%i:%s %p')) = ?", [$twoyear])->pluck('postdate');
        $closuretrendtwo = $this->getCounts($updatedat, $months);
        //three year
        $updatedat = HseIssue::whereRaw("YEAR(STR_TO_DATE(postdate, '%d-%m-%Y %h:%i:%s %p')) = ?", [$threeyear])->pluck('postdate');
        $closuretrendthree = $this->getCounts($updatedat, $months);
        $counts = HseIssue::select(
            DB::raw("COUNT(CASE WHEN impl_status = 'Completed-100%' THEN 1 END) AS hundredcount"),
            DB::raw("COUNT(CASE WHEN impl_status = 'Partially Completed-50%' THEN 1 END) AS fivecount"),
            DB::raw("COUNT(CASE WHEN impl_status = 'Progress-25%' THEN 1 END) AS twcount"),
            DB::raw("COUNT(CASE WHEN impl_status = 'Progress-10%' THEN 1 END) AS tencount"),
            DB::raw("COUNT(CASE WHEN impl_status = 'Not Started-0%' THEN 1 END) AS zerocount"),
            DB::raw("COUNT(CASE WHEN status = 'closed' THEN 1 END) AS closecount"),
            DB::raw("COUNT(CASE WHEN status = 'closed' AND closed <= duedate THEN 1 END) AS closebeforecount"),
            DB::raw("COUNT(CASE WHEN status = 'closed' AND closed > duedate THEN 1 END) AS closeaftercount"),
            DB::raw("COUNT(CASE WHEN status = 'open' THEN 1 END) AS opencount"),
            DB::raw("COUNT(CASE WHEN status = 'open' AND duedate < '$today' THEN 1 END) AS nmdue"),
            DB::raw("COUNT(CASE WHEN processstage IN ('Approved', 'Awaiting actions') THEN 1 END) AS awaitingactions"),
            DB::raw("COUNT(CASE WHEN processstage = 'Actions Proposed' THEN 1 END) AS proposed"),
            DB::raw("COUNT(CASE WHEN processstage = 'Actions Completed' THEN 1 END) AS awaitingclosure")
        )->first();
        $users = User::get();
        $cons = DB::select(" SELECT postedby as number
                    FROM hseissues
                    GROUP BY  postedby
                    ORDER BY COUNT(postedby) DESC LIMIT 10");
        $con = [];
        foreach ($cons as $key) {
            array_push($con, $key->number);
        }
        $models = ModenaDisable::First();
        return view('user.hseobservation.analysis',compact('departmentDB'))->with('model', $models)->with('users', $users)->with('con', $con)
            ->with([
                'depcodeForOpenDue'=>json_encode($depcodeForOpenDue, JSON_NUMERIC_CHECK),
                'hseDeletedDepartments'=>implode(',',$hseDeletedDepartments),
                'disabledDepartments'=>implode(',',$disabledDepartments),
                'departmentNamesKeys' => implode(',',array_keys($departmentNames)),
                'departmentNamesValues' => implode(',',array_values($departmentNames)),
                'depcodeForClosedIssues' => json_encode($depcodeForClosedIssues, JSON_NUMERIC_CHECK),
                'depcodeForAwaitingIssues'=> json_encode($depcodeForAwaitingIssues, JSON_NUMERIC_CHECK),
                'depcodeForTotalTypeIssues'=> json_encode($depcodeForTotalTypeIssues, JSON_NUMERIC_CHECK),
                'depcodeForTypeIssues'=> json_encode($depcodeForTypeIssues, JSON_NUMERIC_CHECK),
                'depcodeForImplementStatusIssues'=> json_encode($depcodeForImplementStatusIssues, JSON_NUMERIC_CHECK),
                'twocount' => $counts->twcount, 'tencount' => $counts->tencount,
                'ten' => json_encode($ten, JSON_NUMERIC_CHECK),
            ])
            ->with('data', $data)->with('types', $types)->with('nmdepts', $nmdepts)
            ->with('opencount', $counts->opencount)->with('closecount', $counts->closecount)
            ->with('closebeforecount', $counts->closebeforecount)
            ->with('closeaftercount', $counts->closeaftercount)
            ->with('nmdue', $counts->nmdue)->with('actions', $counts->awaitingactions)
            ->with('proposed', $counts->proposed)->with('awaitingclosure', $counts->awaitingclosure)
            ->with('names', $names)->with('hundredcount', $counts->hundredcount)->with('fivecount', $counts->fivecount)
            ->with('zerocount', $counts->zerocount)
            ->with('monthtrend', json_encode($monthtrend, JSON_NUMERIC_CHECK))
            ->with('closuretrend', json_encode($closuretrend, JSON_NUMERIC_CHECK))
            ->with('closuretrendlast', json_encode($closuretrendlast, JSON_NUMERIC_CHECK))
            ->with('closuretrendtwo', json_encode($closuretrendtwo, JSON_NUMERIC_CHECK))
            ->with('closuretrendthree', json_encode($closuretrendthree, JSON_NUMERIC_CHECK))
            ->with('dopen', json_encode($dopen, JSON_NUMERIC_CHECK))
            ->with('due', json_encode($due, JSON_NUMERIC_CHECK))
            ->with('awaiting', json_encode($awaiting, JSON_NUMERIC_CHECK))
            ->with('depcode', json_encode($depcode, JSON_NUMERIC_CHECK))
            ->with('trend', json_encode($trend, JSON_NUMERIC_CHECK))
            ->with('year', json_encode($year, JSON_NUMERIC_CHECK))
            ->with('highd', json_encode($highd, JSON_NUMERIC_CHECK))
            ->with('moderated', json_encode($moderated, JSON_NUMERIC_CHECK))
            ->with('lowd', json_encode($lowd, JSON_NUMERIC_CHECK))
            ->with('hightotal', json_encode($hightotal, JSON_NUMERIC_CHECK))
            ->with('moderatetotal', json_encode($moderatetotal, JSON_NUMERIC_CHECK))
            ->with('lowtotal', json_encode($lowtotal, JSON_NUMERIC_CHECK))
            ->with('zero', json_encode($zero, JSON_NUMERIC_CHECK))
            ->with('two', json_encode($two, JSON_NUMERIC_CHECK))
            ->with('five', json_encode($five, JSON_NUMERIC_CHECK))
            ->with('hundred', json_encode($hundred, JSON_NUMERIC_CHECK))
            ->with('before', json_encode($before, JSON_NUMERIC_CHECK))
            ->with('after', json_encode($after, JSON_NUMERIC_CHECK));
    }
    private function getCounts($data, $months){
        $monthMap = array_fill_keys($months, 0);
        foreach ($data as $date) {
            $month = date('M', strtotime($date));
            if (isset($monthMap[$month])) {
                $monthMap[$month]++;
            }
        }
        return array_values($monthMap);
    }
    public function getListDetails($id)
    {
        $issue = HseIssue::with('postedBy', 'approvedBy')->where('id', $id)->first();
        $responsibleUser = User::where('email', $issue->responsibility)->first();
        $postedByUser = User::find($issue->postedby);
        $issue->postedByUser = $postedByUser;
        $issue->responsibleUser = $responsibleUser;
        return response()->json(['data' => $issue]);
    }
    public function getIssueAttachment($id)
    {
        $issuedocs = DB::table('multiplehseissues')->where('id', $id)->where('issue_file', '!=', 'null')->get();

        return response()->json(['data' => $issuedocs]);
    }
    public function getActionAttachment($id)
    {
        $actiondocs = DB::table('multiplehseactions')->where('id', $id)->where('action_file', '!=', 'null')->get();

        return response()->json(['data' => $actiondocs]);
    }
}
