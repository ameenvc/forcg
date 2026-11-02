<?php

namespace App\Http\Controllers;

use App\Area;
use App\Charts\OpportunityAnalysis;
use App\Departments;
use App\Division;
use App\Http\Controllers\Controller;
use App\Mail\NewOpportunityAdded;
use App\Mail\OpportunityApprove;
use App\Mail\OpportunityClose;
use App\Mail\OpportunityDisapprove;
use App\Mail\OpportunityResponsibilityChanged;
use App\Mail\OpportunityStatus;
use App\ModenaDisable;
use App\Opportunity;
use App\OpportunityType;
use App\Type;
use App\User;
use App\ViewActivity;
use App\Closurereviewer;
use App\Models\Opportunityapprover;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\ClientDetail;
use App\Exports\OpportunityExport;
use App\Services\OpportunityWordExport;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Helpers\Opportunity\NewOpportunityAddedEmailHelper;
use App\Helpers\Opportunity\OpportunityStatusEmailHelper;
use Illuminate\Support\Collection;

class OpportunityController extends Controller
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

    public function addopportunity()
    {
        $opportunityapprovers = Opportunityapprover::with('user')->whereHas('user')->orderBy('name', 'asc')->get();;
        $closurereviewers = Closurereviewer::with('user')->whereHas('user')->orderBy('name', 'asc')->get();;
        $departments = Departments::where('is_active', true)->get();
        $hods = DB::table('departments')->pluck('hod');
        $hml = DB::table('hml')->get();
        $users = User::where('is_email_verified',true)->where('approved',true)->orderBy('name')->get();
        $opptypes = OpportunityType::all();
        $models = ModenaDisable::First();
        return view('user.opportunity.addopportunity')->with('model', $models)
            ->with('opportunityapprovers', $opportunityapprovers)
            ->with('closurereviewers', $closurereviewers)
            ->withDepartments($departments)
            ->withHml($hml)
            ->with('users', $users)
            ->with('opptypes', $opptypes)
            ->withHods($hods);
    }

    public function InsertOpportunity(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'targetdate' => 'required|date',
            'oppdec' => 'required',
            'opportunityapprover_id' => 'required|integer|not_in:0',
            'closurereviewer_id' => 'required|integer|not_in:0'
        ]);

        if ($validator->fails()) {

            $models = DB::table('modenadisable')->first();

            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $department = $req->input('responsible_department');
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

            if($benefit == "High") {
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
                'department' => $department, 'division_id' => $division, 'area_id' => $area, 'oppname' => $oppname,
                'oppdes' => $oppdec, 'ohs' => $ohsvalue, 'likelihood' => $likelihood, 'benefit' => $benefit,
                'tardate' => $now, 'impact' => $impa, 'postedby' => $ID, 'type' => $type,
                'action' => $action, 'createdat' => $createdat, 'responsibility' => $responsibility, 'file' => $cer,
                'updatedat' => now(), 'dep_id' => $dep_id, 'opportunityapprover_id' => $req->input('opportunityapprover_id'),
                'closurereviewer_id' => $req->input('closurereviewer_id')
            );

            $newlyCreatedOpportunity = Opportunity::create($data);

            $opparea = Departments::where('id', $department)->firstOrFail();
            $opparea = $opparea->departmentname;

            //inserting values to activity table superadmin code
            $activity = "New Opportunity Added with Name " . $oppname . " and with Department " . $opparea;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code

            Session::flash('msg', "Opportunities sent for approval Successfully");

            NewOpportunityAddedEmailHelper::sendEmail($newlyCreatedOpportunity->id);

            $models = DB::table('modenadisable')->first();

            return redirect()->back()->with('model', $models);
        }
    }

    public function register(string $type = null)
    {
        $today = date('Y-m-d');
        //below is superadmin related codes
        $opportunity = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($opportunity) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 1, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0]);
        } else {
            $opportunity = DB::table('modpageload')->where('id', 1)->First();
            $opportunity->opportunity = $opportunity->opportunity + 1;
            DB::table('modpageload')->update(['opportunity' => $opportunity->opportunity]);
        }
        //end of superadmin codes

        $email = Auth::user()->email;
        $poss = DB::table('users')->where('email', $email)->get();
        foreach ($poss as $dat) {
            $ID = $dat->id;
        }
        $mrs = DB::table('representatives')->pluck('email');
        $rvrs = DB::table('riskreviewers')->where('email', Auth::user()->email)->pluck('email');
        $closurervrs = DB::table('closurereviewers')->where('email', Auth::user()->email)->pluck('email');
        $opportunityapprovers = Opportunityapprover::get();
        // dd($closurervrs);
        if ($type == null) {
            $data = Opportunity::with('opportunityapprover', 'closurereviewer')->whereIn('status', ['Review Pending', 'Approved', 'Disapproved', 'Completed'])
                ->whereIn('impact', ['High', 'Low', 'Moderate'])
                ->orderby('id', 'desc')
                ->get();
        } else {
            if($type == 'all'){
                $data = Opportunity::with('opportunityapprover', 'closurereviewer')->where('status', '!=','Review Pending')
                ->whereIn('impact', ['High', 'Low', 'Moderate'])
                ->orderby('id', 'desc')
                ->get();
            }
            else{
                $data = Opportunity::with('opportunityapprover', 'closurereviewer')->where('status', '!=','Review Pending')
                ->where('impact', $type)
                ->orderby('id', 'desc')
                ->get();
            }
        }
        $departments = DB::table('departments')->get();
        $users = DB::table('users')->get();
        $mr = DB::table('representatives')->pluck('email');
        $models = DB::table('modenadisable')->First();

        $typenames = OpportunityType::pluck('name')->toArray();
        $alltypenames = implode(',', $typenames);

        $yearvalues = Opportunity::select(DB::raw('YEAR(tardate) AS year'))
            ->where('status', '!=', 'Closed')
            ->distinct()->get()->toArray();
        $year_arr = array();
        foreach ($yearvalues as $yearvalue) {
            array_push($year_arr, $yearvalue['year']);
        }
        sort($year_arr);
        $years = implode(',', array_unique($year_arr));

        $oppdepts = Opportunity::groupBy('department')->where('status', '!=', 'Closed')->pluck('department')->toArray();
        $departmentnames = array();
        foreach ($oppdepts as $oppdept) {
            $dept_names = DB::table('departments')->where('id', $oppdept)->value('departmentname');
            if ($dept_names) {
                array_push($departmentnames, $dept_names);
            }
        }
        $alldeptnames = implode(',', $departmentnames);

        $responsible_emails = Opportunity::groupBy('responsibility')->where('status', '!=', 'Closed')->pluck('responsibility')->toArray();
        $usernameslist = array();
        foreach ($responsible_emails as $responsible_email) {
            $resp_names = User::where('email', $responsible_email)->value('name');
            if ($resp_names) {
                array_push($usernameslist, $resp_names);
            }
        }
        $allusernames = implode(',', $usernameslist);

        $processstages = ['Approved', 'Completed', 'Disapproved', 'Review Pending'];
        $allpstages = implode(',', $processstages);

        $impactstages = Opportunity::groupBy('impact')->where('status', '!=', 'Closed')->pluck('impact')->toArray();
        $allimpactstages = implode(',', $impactstages);

        return view('user.opportunity.register')
            ->with([
                'model' => $models,
                'users' => $users,
                'data' => $data,
                'departments' => $departments,
                'alltypenames' => $alltypenames,
                'allpstages' => $allpstages,
                'years' => $years,
                'alldeptnames' => $alldeptnames,
                'allimpactstages' => $allimpactstages,
                'allusernames' => $allusernames,
                'mr' => $mr,
                'mrs' => $mrs,
                'closurervrs' => $closurervrs,
                'opportunityapprovers' => $opportunityapprovers,
                'rvrs' => $rvrs,
            ]);
    }

    //edit responsibility
    public function editresp($id)
    {
        $data = Opportunity::where('id', $id)->First();
        $opportunityapprovers = Opportunityapprover::with('user')->whereHas('user')->orderBy('name', 'asc')->get();;
        $closurereviewers = Closurereviewer::with('user')->whereHas('user')->orderBy('name', 'asc')->get();;
        $type = OpportunityType::whereIn('id',explode(',',$data->type))->pluck('name')->toArray();
        $type = implode(', ',$type);
        $departments = DB::table('departments')->get();
        $areas = Area::all();
        $users = User::where('is_email_verified',true)->where('approved',true)->get();
        $models = DB::table('modenadisable')->First();
        return view('user.opportunity.editresponsibility',compact('type'))
            ->withData($data)
            ->withDepartments($departments)
            ->with('users', $users)
            ->with('areas', $areas)
            ->with('model', $models)
            ->with('opportunityapprovers', $opportunityapprovers)
            ->with('closurereviewers', $closurereviewers);
    }

    public function submittresp(Request $req)
    {
        $id = $req->input('id');
        $responsible_person_email = $req->input('responsible_person');
        $department = $req->input('department');
        $division = $req->input('division');
        $area = $req->input('area');
        DB::table('opportunity')->where('id', $id)->update([
            'department' => $department,
            'responsibility' => $responsible_person_email,
            'division_id' => $division ?? 0,
            'area_id' => $area ?? 0,
            'updatedat' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $departmentname = DB::table('departments')->where('id', $department)->value('departmentname');
        $opprdata = DB::table('opportunity')->where('id', $id)->first();

        if($responsible_person_email){
            $opportunity_emails = EmailNotification::where('module', 'opportunity')
                ->where('item', 'Opportunity Responsibility Changed')
                ->where('is_blocked', false)
                ->first();
            if($opportunity_emails){
                $emaildata = array(
                    'opportunityid' => $opprdata->dep_id,
                    'item' => $opportunity_emails->item,
                    'email_content' => $opportunity_emails->email_content,
                    'department' => $departmentname,
                    'duedate' => $opprdata->tardate ? date('d-m-Y',strtotime($opprdata->tardate)) : '',
                );
                //Send email to responsible person
                $respon = getNonDeletedUsersEmail($responsible_person_email);
                if(count($respon)>0){
                    try {
                        Mail::to($respon)->send(new OpportunityResponsibilityChanged($emaildata));
                    } catch (\Exception $e) {
                        Log::error('Error sending OpportunityResponsibilityChanged email to responsible person: ' . $e->getMessage());
                    }
                }

                $opportunity_userdatas = EmailNotificationsUserDetails::where('notification_id', $opportunity_emails->id)->get();
                $userdatas = [];
                if (count($opportunity_userdatas) > 0) {
                    $adminaddedusers = $opportunity_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try {
                            Mail::to($userdatas)->send(new OpportunityResponsibilityChanged($emaildata));
                        } catch (\Exception $e) {
                            Log::error('Error sending OpportunityResponsibilityChanged email to user added by admin in admin panel: ' . $e->getMessage());
                        }
                    }
                }
            }else{
                Session::flash('err', 'Mail Sending Blocked for Opportunity Responsibility Changed.');
            }
        }
        //inserting values to activity table
        $activity = "Opportunity is Edited and updated with Responsibility  as " . $responsible_person_email . " and Department as " . $departmentname . " where Opportunity ID is " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //end of superadmin code
        Session::flash('msg', "Responsibility edited successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    public function adds(int $id)
    {
        $hml = DB::table('hml')->get();
        $data = Opportunity::where('id', $id)->get();
        $models = Modenadisable::First();
        $departments = Departments::where('is_active', true)->get();
        $user = User::where('is_email_verified',true)->where('approved',true)->get();
        $types = OpportunityType::all();
        return view('user/opportunity/update',compact('types','departments'))
            ->with('user', $user)
            ->with('model', $models)
            ->withData($data)
            ->with('id', $id)
            ->withHml($hml);
    }
    public function updateopportunity(Request $req)
    {
        $validator = Validator::make($req->all(), [
            // 'action' => 'required|max:300'
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $Status = "Approved";
            $Oid = $req->input('posted');
            $responsibility = $req->input('responsible_person');
            $ohs = $req->input('ohs');
            if ($ohs == "Yes") {
                $ohs = "Yes";
                $type = implode(',',$req->input('type'));
            }
            if ($ohs == "No") {
                $ohs = "No";
                $type = $req->input('type') ? implode(',',$req->input('type')) : '';
            }
            $division = $req->input('division')??'0';
            $area = $req->input('area')??'0';
            $department = $req->input('responsible_department');
            $action = $req->input('action');
            $s = $req->input("action");
            $oppname = $req->input('oppname');
            $oppdes = $req->input('oppdes');
            $now = $req->input('tardate');
            $bene = $req->input('benefit');
            $like = $req->input('likelihood');
            $additionals = $req->input('additional');
            if ($additionals != null) {
                foreach ($additionals as $add) {
                    $a[] = $add;
                }
                $additional = implode(',', $a);
            }

            //$reviewtime = date('d-m-Y h:i:s A', time());
            $reviewtime = date('Y-m-d H:i:s', time());

            if ($like == "High") {
                $value1 = 3;
            } elseif ($like == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($bene == "High") {
                $value2 = 3;
            } elseif ($bene == "Moderate") {
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
            $data = array(
                'oppname' => $oppname, 'oppdes' => $oppdes, 'status' => $Status,'is_reviewed' => true,
                'responsibility' => $responsibility, 'action' => $action,
                'likelihood' => $like, 'benefit' => $bene, 'impact' => $impa,'ohs' => $ohs,'division_id' => $division,'area_id' => $area, 'department' => $department,
                'tardate' => $now, 'reviewdate' => $reviewtime, 'approved_at' => $reviewtime, 'secondary' => 'yes','type' => $type,
            );

            DB::table('opportunity')
                ->where('id', $Oid)
                ->update($data);

            DB::table('oppremarks')
                ->insert(array(
                    'Oid' => $Oid,
                    'postedby' => $responsibility,
                    'ostatus' => 'for secondary',
                ));
            //inserting values to activity table
            $activity = "Opportunity  Status is Updated to Approved where  Opportunity Id is" . $Oid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin codes

            $opportunity_emails = EmailNotification::where('module', 'opportunity')
                ->where('item', 'Opportunity Approved')
                ->where('is_blocked', false)
                ->first();
            if($opportunity_emails){
                $opdept = DB::table('opportunity')->where('id', $Oid)->first();
                $emaildata = array(
                    'dep_id' => $opdept->dep_id,
                    'oppname' => $oppname,
                    'item' => $opportunity_emails->item,
                    'email_content' => $opportunity_emails->email_content,
                    'department' => $opdept ? Departments::where('id',$opdept->department)->value('departmentname') : '',
                    'oppdes' => $oppdes,
                );
                //Send email to posted person
                $postemail = User::where('employeeid', $opdept->postedby)->value('email');
                if ($postemail) {
                    try {
                        Mail::to($postemail)->send(new OpportunityApprove($emaildata));
                    } catch (\Exception $e) {
                        Log::error('Error sending OpportunityApprove email to posted person: ' . $e->getMessage());
                    }
                }
                //Send email to responsible person
                $respon = DB::table('opportunity')->where('id', $Oid)->value('responsibility');
                $respon = getNonDeletedUsersEmail($respon);
                if(count($respon)>0){
                    try {
                        Mail::to($respon)->send(new OpportunityApprove($emaildata));
                    } catch (\Exception $e) {
                        Log::error('Error sending OpportunityApprove email to responsible person: ' . $e->getMessage());
                    }
                }

                //Send Emails to Addtional users
                if ($additionals) {
                    $noti = explode(',', $additional);
                    $noti = getNonDeletedUsersEmail($noti);
                    if (count($noti)>0) {
                        try {
                            Mail::to($noti)->send(new OpportunityApprove($emaildata));
                        } catch (\Exception $e) {
                            Log::error('Error sending OpportunityApprove email to additional person: ' . $e->getMessage());
                        }
                    }
                }
                $opportunity_userdatas = EmailNotificationsUserDetails::where('notification_id', $opportunity_emails->id)->get();
                $userdatas = [];
                if (count($opportunity_userdatas) > 0) {
                    $adminaddedusers = $opportunity_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try {
                            Mail::to($userdatas)->send(new OpportunityApprove($emaildata));
                        } catch (\Exception $e) {
                            Log::error('Error sending OpportunityApprove email to user added by admin in admin panel: ' . $e->getMessage());
                        }
                    }
                }
            }else{
                Session::flash('err', 'Mail Sending Blocked for Opportunity Approved.');
            }
            Session::flash('msg', "Opportunity Approved Successfully");
            $models = DB::table('modenadisable')->First();
            return redirect('/user/opportunity')->with('model', $models);
        }
    }
    public function disapprove(int $id)
    {

        $user = User::where('is_email_verified',true)->where('approved',true)->get();
        $models = Modenadisable::First();
        return view('user.opportunity.disapprove')
            ->withId($id)
            ->with('model', $models)
            ->with('user', $user);
    }
    public function disapproving(Request $req)
    {
        $reason = $req->input('reason');
        $additionals = $req->input('additional');
        if ($additionals != null) {
            foreach ($additionals as $add) {
                $a[] = $add;
            }
            $additional = implode(',', $a);
        }
        $id = $req->input('id');
        $reviewtime = date('Y-m-d H:i:s', time());
        $data = array(
            'status' => 'Disapproved', 'reviewdate' => $reviewtime,
            'reason' => $reason,'is_reviewed' => true
        );
        DB::table('opportunity')
            ->where('id', $id)
            ->update($data);
        //inserting values to activity table superadmin code
        $activity = "Opportunity is Rejeceted and its Status Updated to Disapproved where Opportunity Id is" . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //end of superadmin codes

        $opportunity_emails = EmailNotification::where('module', 'opportunity')
                ->where('item', 'Opportunity Disapproved')
                ->where('is_blocked', false)
                ->first();
        if($opportunity_emails){
            //disapproval mail
            $opdept = DB::table('opportunity')->where('id', $id)->first();
            $emaildata = array(
                'dep_id' => $opdept->dep_id,
                'oppname' => $opdept->oppname,
                'item' => $opportunity_emails->item,
                'email_content' => $opportunity_emails->email_content,
                'department' => $opdept ? Departments::where('id',$opdept->department)->value('departmentname') : '',
                'oppdes' => $opdept->oppdes,
                'reason' => $opdept->reason,
            );
            //Send email to posted person
            $postemail = User::where('employeeid', $opdept->postedby)->value('email');
            if ($postemail) {
                try {
                    Mail::to($postemail)->send(new OpportunityDisapprove($emaildata));
                } catch (\Exception $e) {
                    Log::error('Error sending OpportunityDisapprove email to posted person: ' . $e->getMessage());
                }
            }
            //Send email to responsible person
            $respon = getNonDeletedUsersEmail($opdept->responsibility);
            if(count($respon)>0){
                try {
                    Mail::to($respon)->send(new OpportunityDisapprove($emaildata));
                } catch (\Exception $e) {
                    Log::error('Error sending OpportunityDisapprove email to responsible person: ' . $e->getMessage());
                }
            }

            //Send Emails to Addtional users
            if ($additionals) {
                $noti = explode(',', $additional);
                $noti = getNonDeletedUsersEmail($noti);
                if (count($noti)>0) {
                    try {
                        Mail::to($noti)->send(new OpportunityDisapprove($emaildata));
                    } catch (\Exception $e) {
                        Log::error('Error sending OpportunityDisapprove email to additional person: ' . $e->getMessage());
                    }
                }
            }
            $opportunity_userdatas = EmailNotificationsUserDetails::where('notification_id', $opportunity_emails->id)->get();
            $userdatas = [];
            if (count($opportunity_userdatas) > 0) {
                $adminaddedusers = $opportunity_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas) > 0) {
                    try {
                        Mail::to($userdatas)->send(new OpportunityDisapprove($emaildata));
                    } catch (\Exception $e) {
                        Log::error('Error sending OpportunityDisapprove email to user added by admin in admin panel: ' . $e->getMessage());
                    }
                }
            }
        }else{
            Session::flash('err', 'Mail Sending Blocked for Opportunity Disapproved.');
        }
        $models = DB::table('modenadisable')->First();
        return redirect('/user/opportunity')->with('model', $models);
    }
    public function resubmit(int $id)
    {
        $opportunityapprovers = Opportunityapprover::with('user')->whereHas('user')->orderBy('name', 'asc')->get();;
        $closurereviewers = Closurereviewer::with('user')->whereHas('user')->orderBy('name', 'asc')->get();;
        $hml = DB::table('hml')->get();
        $departments = Departments::where('is_active',true)->get();
        $users = User::where('is_email_verified',true)->where('approved',true)->get();
        $data = Opportunity::where('id', $id)->get();
        if($data[0]->postedby!= Auth::user()->employeeid){
            Session::flash('msg', "You cannot resubmit the opportunity. Please contact opportunity posted by user to resubmit.");
            return redirect('/user/opportunity');
        }
        $models = Modenadisable::First();
        $types = OpportunityType::all();
        return view('user.opportunity.resubmit',compact('types', 'opportunityapprovers', 'closurereviewers'))
            ->with('model', $models)
            ->withDepartments($departments)
            ->withUsers($users)
            ->withData($data)
            ->withHml($hml);
    }

    public function resubmitopportunity(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'opportunityapprover_id' => 'required|integer|not_in:0',
            'closurereviewer_id' => 'required|integer|not_in:0'
        ]);

        if ($validator->fails()) {

            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $id = $req->input('id');

            $oppname = $req->input('oppname');
            $oppdec = $req->input('oppdes');
            $likelihood = $req->input('likelihood');
            $benefit = $req->input('benefit');
            $now = $req->input('targetdate');
            $ohs = $req->input('ohs');
            $type = $req->input('type') ? implode(',',$req->input('type')) :'';
            $action = $req->input('action');
            $responsibility = $req->input('responsibility');
            $division = $req->input('division')??'0';
            $area = $req->input('area')??'0';
            $department = $req->input('responsible_department');
            if($likelihood == "High"){
                $value1 = 3;
            }elseif($likelihood == "Moderate") {
                $value1 = 2;
            }else {
                $value1 = 1;
            }
            if($benefit == "High") {
                $value2 = 3;
            }elseif($benefit == "Moderate") {
                $value2 = 2;
            }else{
                $value2 = 1;
            }
            if(($value1 * $value2) > 5) {
                $impa = "High";
            }elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                $impa = "Moderate";
            }else {
                $impa = "Low";
            }
            $ID = Auth::user()->employeeid;
            $cer = DB::table('opportunity')->where('id', $id)->value('file');
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
           $data = array(
                'oppname' => $oppname, 'tardate' => $now,'department' => $department,'division_id' => $division,'area_id' => $area,
                'oppdes' => $oppdec, 'likelihood' => $likelihood, 'benefit' => $benefit,
                'impact' => $impa, 'postedby' => $ID, 'status' => 'Review Pending', 'ohs' => $ohs, 'type' => $type, 'file' => $cer,
                'action' => $action,'responsibility' => $responsibility, 'opportunityapprover_id' => $req->input('opportunityapprover_id'),
                'closurereviewer_id' => $req->input('closurereviewer_id')
            );
            DB::table('opportunity')
                ->where('id', '=', $id)
                ->update($data);

            //inserting values to activity table superadmincode
            $activity = "Opportunity is  Resubmitted and  status Updated to Review Pending where  Opportunity Id is" . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code
            Session::flash('msg', "Opportunity resubmitted for approval successfully.");

            NewOpportunityAddedEmailHelper::sendEmail($id);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/opportunity')->with('model', $models);

        }
    }

    public function accomplished()
    {

        $departments = DB::table('departments')->get();
        $data1 = opportunity::with('opportunityapprover', 'closurereviewer')->where('status', '=', 'Closed')->orderby('id', 'DESC')->get();
        $models = DB::table('modenadisable')->First();
        return view('user.opportunity.accomplished')->with('data1', $data1)->with('model', $models)->with('departments', $departments);
    }

    //secondary review
    public function addsreview(int $id)
    {

        $hml = DB::table('hml')->get();
        $data = Opportunity::where('id', $id)->get();
        $mn = Departments::all();
        $models = Modenadisable::First();
        $type = OpportunityType::whereIn('id',explode(',',$data[0]->type))->pluck('name')->toArray();
        $type = implode(', ',$type);
        return view('user/opportunity/updatereview',compact('type'))
            ->with('model', $models)
            ->withData($data)
            ->with('mn', $mn)
            ->with('id', $id)
            ->withHml($hml);
    }
    public function updatereview(Request $req)
    {
        $validator = Validator::make($req->all(), []);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $Oid = $req->input('posted');
            $targetdate = $req->input('date');
            //$reviewtime = date('d-m-Y h:i:s A', time());
            $reviewtime = date('Y-m-d H:i:s', time());

            $act = $req->input('action');

            $ben = $req->input('benefit');
            $like = $req->input('likelihood');
            $actionstatus = $req->input('actionstatus');

            if ($ben == "High") {
                $value1 = 3;
            } elseif ($ben == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($like == "High") {
                $value2 = 3;
            } elseif ($like == "Moderate") {
                $value2 = 2;
            } else {
                $value2 = 1;
            }

            if (($value1 * $value2) > 5) {
                $imp = "High";
            } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                $imp = "Moderate";
            } else {
                $imp = "Low";
            }

            if ($actionstatus == 'Completed-100%') {
                $data2 = array(
                    'action' => $act,
                    'reviewdate' => $reviewtime,
                    'benefit' => $ben,
                    'likelihood' => $like,
                    'impact' => $imp, 'actionstatus' => $actionstatus, 'status' => "Completed", 'secondary' => 'no',
                    'tardate' => $targetdate,
                );

                DB::table('opportunity')
                    ->where('id', $Oid)
                    ->update($data2);

                DB::table('oppremarks')
                    ->where('Oid', $Oid)

                    ->update(array('ostatus' => "Completed"));
                //inserting values to activity table superadmin code
                $activity = "Opportunity Status is Updated to " . $actionstatus . " where  Opportunity Id is " . $Oid;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
                ViewActivity::insert($datas);
                //end of superadmin code

                //mail to  closure reviewer
                OpportunityStatusEmailHelper::sendEmail($Oid);
            } else {

                $data = array(
                    'action' => $act,
                    'reviewdate' => $reviewtime,
                    'benefit' => $ben,
                    'likelihood' => $like,
                    'impact' => $imp,
                    'actionstatus' => $actionstatus,
                    'tardate' => $targetdate,
                );

                DB::table('opportunity')
                    ->where('id', $Oid)
                    ->update($data);

                DB::table('oppremarks')
                    ->where('Oid', $Oid)

                    ->update(array('ostatus' => "Not Completed"));
                //inserting values to activity table superadmin code
                $activity = "Opportunity Status is Updated to " . $actionstatus . " where  Opportunity Id is " . $Oid;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
                ViewActivity::insert($datas);
                //end of superadmin code

            }
            Session::flash('msg', "Opportunity status has been updated successfully.");
            $models = DB::table('modenadisable')->First();
            return redirect('/user/opportunity')->with('model', $models);
        }
    }

    //closurereview for need more action
    public function closurereview(int $id)
    {

        $hml = DB::table('hml')->get();
        $data = Opportunity::where('id', $id)->get();
        $mn = Departments::all();
        $models = Modenadisable::First();
        $type = OpportunityType::whereIn('id',explode(',',$data[0]->type))->pluck('name')->toArray();
        $type = implode(', ',$type);
        return view('user/opportunity/closure',compact('type'))
            ->with('model', $models)
            ->withData($data)
            ->with('mn', $mn)
            ->with('id', $id)
            ->withHml($hml);
    }

    //edit opportunity by added user
    public function editopport(int $id)
    {
        $opportunityapprovers = Opportunityapprover::with('user')->whereHas('user')->orderBy('name', 'asc')->get();;
        $closurereviewers = Closurereviewer::with('user')->whereHas('user')->orderBy('name', 'asc')->get();;
        $data = Opportunity::where('id', $id)->get();
        $departments = Departments::where('is_active', true)->get();
        $users = User::where('is_email_verified',true)->where('approved',true)->get();
        $hml = DB::table('hml')->get();
        $models = Modenadisable::First();
        $types = OpportunityType::all();
        return view('user/opportunity/editopport',compact('types'))
            ->withData($data)
            ->with('opportunityapprovers', $opportunityapprovers)
            ->with('closurereviewers', $closurereviewers)
            ->with('hml', $hml)
            ->with('users', $users)
            ->with('departments', $departments)
            ->with('id', $id)
            ->with('model', $models);
    }
    //update edited opportunity by added user
    public function submitopport(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'opportunityapprover_id' => 'required|integer|not_in:0',
            'closurereviewer_id' => 'required|integer|not_in:0'
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->first();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        }

        $id = $req->input('id');
        // $oid = DB::select('select * from opportunity where oid = :id',['id'=>$id]);
        $name = $req->input('opportunityname');
        $desc = $req->input('oppdec');
        $tardate = $req->input('targetdate');
        $ohs = $req->input('ohs');
        if ($ohs == "Yes") {
            $ohs = "Yes";
            $type = implode(',',$req->input('type'));
        }
        if ($ohs == "No") {
            $ohs = "No";
            $type = $req->input('type') ? implode(',',$req->input('type')) : '';
        }
        $action = $req->input('action');
        $like = $req->input('likelihood');
        $benefit = $req->input('benefit');
        if ($like == "High") {
            $value1 = 3;
        } elseif ($like == "Moderate") {
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
        $responsibility = $req->input('responsible_person');
        $cer = DB::table('opportunity')->where('id', $id)->value('file');
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
        $division = $req->input('division')??'0';
        $area = $req->input('area')??'0';
        $department = $req->input('responsible_department');
        $data = array(
            'oppname' => $name, 'oppdes' => $desc, 'tardate' => $tardate, 'ohs' => $ohs, 'type' => $type, 'action' => $action,
            'likelihood' => $like, 'benefit' => $benefit, 'impact' => $impa, 'file' => $cer,'responsibility' => $responsibility,
            'division_id' => $division, 'area_id' => $area,'department' => $department, 'opportunityapprover_id' => $req->input('opportunityapprover_id'),
            'closurereviewer_id' => $req->input('closurereviewer_id')
        );
        DB::table('opportunity')->where('id', $id)->update($data);
        NewOpportunityAddedEmailHelper::sendEmail($id);
        Session::flash('msg', "Opportunity edited successfully");
        return redirect()->back();
    }

    public function closure(Request $req)
    {

        $validator = Validator::make($req->all(), []);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $Oid = $req->input('posted');
            //$reviewtime = date('d-m-Y h:i:s A', time());
            $reviewtime = date('Y-m-d H:i:s', time());

            $ben = $req->input('benefit');
            $like = $req->input('likelihood');
            $ndareason = $req->input('ndareason');

            if ($ben == "High") {
                $value1 = 3;
            } elseif ($ben == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($like == "High") {
                $value2 = 3;
            } elseif ($like == "Moderate") {
                $value2 = 2;
            } else {
                $value2 = 1;
            }

            if (($value1 * $value2) > 5) {
                $imp = "High";
            } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
                $imp = "Moderate";
            } else {
                $imp = "Low";
            }

            $data = array(

                'benefit' => $ben,
                'likelihood' => $like,
                'impact' => $imp,
                'ndareason' => $ndareason,'is_closed' => true,
                'status' => "Approved", 'closed_at' => date('Y-m-d H:i:s', time()), 'actionstatus' => "Partially Completed-50%",
            );
            DB::table('opportunity')
                ->where('id', $Oid)
                ->update($data);

            DB::table('oppremarks')
                ->where('Oid', $Oid)

                ->update(array('ostatus' => 'Need more action'));

            //inserting values to activity table superadmin code
            $activity = "Opportunity's Action Status is Updated to Partially Completed-50 and Opportunity Status is Updated to Need More Action  where  Opportunity Id is " . $Oid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            Session::flash('msg', "Opportunity status has been submitted successfully.");
            //end of superadmin code
            $models = DB::table('modenadisable')->First();
            return redirect('/user/opportunity')->with('model', $models);
        }
    }

    //close
    public function close(int $id, Request $req)
    {

        //date_default_timezone_set('Asia/Kuwait');
        //$reviewtime = date('d-m-Y h:i:s A', time());
        $reviewtime = date('Y-m-d H:i:s', time());
        $ben = $req->input('benefit');
        $like = $req->input('likelihood');
        $ndareason = $req->input('ndareason');

        if ($ben == "High") {
            $value1 = 3;
        } elseif ($ben == "Moderate") {
            $value1 = 2;
        } else {
            $value1 = 1;
        }

        if ($like == "High") {
            $value2 = 3;
        } elseif ($like == "Moderate") {
            $value2 = 2;
        } else {
            $value2 = 1;
        }

        if (($value1 * $value2) > 5) {
            $imp = "High";
        } elseif (($value1 * $value2) > 2 && ($value1 * $value2) < 6) {
            $imp = "Moderate";
        } else {
            $imp = "Low";
        }
        $data = array(
            'benefit' => $ben,'likelihood' => $like, 'impact' => $imp,'ndareason' => $ndareason,'is_closed' => true,
            'status' => 'Closed', 'closed_at' => date('Y-m-d H:i:s', time()), 'oppstatus' => 'Achieved', 'reviewdate' => $reviewtime,
        );
        DB::table('opportunity')->where('id', '=', $id)
            ->update($data);

        DB::table('oppremarks')
            ->where('Oid', $id)

            ->update(array('ostatus' => 'Closed'));

        //inserting values to activity table superadmin
        $activity = "Opportunity's Action Status is Updated to Closed and Opportunity Status is Updated to Achieved  where  Opportunity Id is " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //end of superadmin code

        //mail after closing
        $opportunity_emails = EmailNotification::where('module', 'opportunity')
                ->where('item', 'Opportunity Closed')
                ->where('is_blocked', false)
                ->first();
        if($opportunity_emails){
            $opdept = DB::table('opportunity')->where('id', $id)->first();
            $emaildata = array(
                'dep_id' => $opdept->dep_id,
                'oppname' => $opdept->oppname,
                'item' => $opportunity_emails->item,
                'email_content' => $opportunity_emails->email_content,
                'department' => $opdept ? Departments::where('id',$opdept->department)->value('departmentname') : '',
                'oppdes' => $opdept->oppdes,
            );
            //Send email to reviewers
            $riskreviewer = DB::table('closurereviewers')->pluck('email')->toArray();
            $riskreviewer = getNonDeletedUsersEmail($riskreviewer);
            if (count($riskreviewer)>0) {
                try {
                    Mail::to($riskreviewer)->send(new OpportunityClose($emaildata));
                } catch (\Exception $e) {
                    Log::error('Error sending OpportunityClose email to posted person: ' . $e->getMessage());
                }
            }
            //Send email to responsible person
            $respon = getNonDeletedUsersEmail($opdept->responsibility);
            if(count($respon)>0){
                try {
                    Mail::to($respon)->send(new OpportunityClose($emaildata));
                } catch (\Exception $e) {
                    Log::error('Error sending OpportunityClose email to responsible person: ' . $e->getMessage());
                }
            }

            $opportunity_userdatas = EmailNotificationsUserDetails::where('notification_id', $opportunity_emails->id)->get();
            $userdatas = [];
            if (count($opportunity_userdatas) > 0) {
                $adminaddedusers = $opportunity_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas) > 0) {
                    try {
                        Mail::to($userdatas)->send(new OpportunityClose($emaildata));
                    } catch (\Exception $e) {
                        Log::error('Error sending OpportunityClose email to user added by admin in admin panel: ' . $e->getMessage());
                    }
                }
            }
        }else{
            Session::flash('err', 'Mail Sending Blocked for Opportunity Closed.');
        }

        $models = Modenadisable::first();
        Session::flash('msg', "Opportunity closed successfully");
        return redirect('/user/opportunity')
            ->with('model', $models);
    }
    //edit request
    public function editreq(string $id)
    {
        $allids = explode(',', $id);
        foreach ($allids as $allid) {
            $data[] = Opportunity::where('id', $allid)->first();
        }

        $models = Modenadisable::First();
        return view('user/opportunity/editrequest')
            ->withData($data)
            ->with('id', $id)
            ->with('model', $models);
    }

    public function editrequest(Request $req)
    {
        $oppkname = $req->input('oppname');
        $oppdes = $req->input('oppdes');
        $tardate = $req->input('tardate');

        $benefit = $req->input('benefit');
        $likelihood = $req->input('likelihood');
        //$sno = $req->input('sno');

        foreach ($req->sno as $key => $value) {

            if ($req->likelihood[$key] == "High") {
                $value1 = 3;
            } elseif ($req->likelihood[$key] == "Moderate") {
                $value1 = 2;
            } else {
                $value1 = 1;
            }

            if ($req->benefit[$key] == "High") {
                $value2 = 3;
            } elseif ($req->benefit[$key] == "Moderate") {
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

            $data = DB::table('opportunity')->where('id', $req->sno[$key])->update([
                'oppname' => $req->oppname[$key],
                'oppdes' => $req->oppdes[$key],
                'tardate' => $req->tardate[$key],
                'benefit' => $req->benefit[$key],
                'likelihood' => $req->likelihood[$key],
                'impact' => $impa,
            ]);

            //insert data to activity table superadmin code
            $activity = "Opportunity  is  Edited and Updated with new Details  where Opportunity ID " . $req->sno[$key];
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code
        }

        $models = DB::table('modenadisable')->First();
        return redirect('/user/opportunity')->with('model', $models);
    }

    //delete request
    public function delete(int $id)
    {

        $name = Opportunity::where('id', $id)->pluck('oppname');
        $activity = "An opportunity entry is Removed with Name " . $name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);

        Opportunity::where('id', $id)->delete();

        $models = DB::table('modenadisable')->First();
        return redirect('/user/opportunity')->with('model', $models);
    }

    public function analysis()
    {
        //---------------Analysis--------------------

        $chart = new OpportunityAnalysis;
        $chart->title("Opportunity Summary");
        $chart->labels(['High', 'Moderate', 'Low']);

        $H = DB::select("select COUNT(r.impact)AS number
                                     FROM  opportunity AS r
                                     right JOIN departments AS s
                                     ON r.department= s.id
                                     and  r.impact='High' and r.status!='Review Pending'
                                       ");

        $M = DB::select("select COUNT(r.impact)AS number
                                     FROM  opportunity AS r
                                     right JOIN departments AS s
                                     ON r.department= s.id
                                     and  r.impact='Moderate' and r.status!='Review Pending'
                                       ");

        $L = DB::select("select COUNT(r.impact)AS number
                                       FROM  opportunity AS r
                                       right JOIN departments AS s
                                       ON r.department= s.id
                                       and  r.impact='Low' and r.status!='Review Pending'
                                         ");

        $total = DB::select("select COUNT(r.impact)AS number
                                         FROM  opportunity AS r
                                         right JOIN departments AS s
                                         ON r.department= s.id and r.status!='Review Pending'
                                         ");

        $Hkey = [];
        foreach ($H as $key1) {
            array_push($Hkey, $key1->number);
        }
        $Mkey = [];
        foreach ($M as $key1) {
            array_push($Mkey, $key1->number);
        }
        $Lkey = [];
        foreach ($L as $key1) {
            array_push($Lkey, $key1->number);
        }

        $totalk = [];
        foreach ($total as $key) {
            array_push($totalk, $key->number);
        }

        $chart->dataset('', 'doughnut', [$Hkey, $Mkey, $Lkey])
        // ->color(['#a6ff00','#FFD700','#f7533e'])
            ->backgroundcolor(['#58D68D', '#FFD700', '#FC4C2B']);
        $chart->displayAxes(false);

        $charta = new OpportunityAnalysis;
        $charta->title("Opportunity Summary");
        $charta->labels(['Accomplished Opportunities', 'Total Opportunities']);

        $A = DB::select("select COUNT(r.impact)AS number
                                     FROM  opportunity AS r
                                     right JOIN departments AS s
                                     ON r.department= s.id
                                     and  r.status='Closed'
                                       ");

        $NotA = DB::select("select COUNT(r.impact)AS number
                                     FROM  opportunity AS r
                                     right JOIN departments AS s
                                     ON r.department= s.id
                                     and  r.status!='Closed' and r.status!='Review Pending'
                                       ");
        $total = DB::select("select COUNT(r.impact)AS number
                                         FROM  opportunity AS r
                                         right JOIN departments AS s
                                         ON r.department= s.id and r.status!='Review Pending'
                                         ");
        $approved = Opportunity::select(DB::raw('COUNT(opportunity.impact)AS number'))->rightJoin('departments', function ($join) {
            $join->on('departments.id', '=', 'opportunity.department')
                ->where('opportunity.status', 'Approved');
        })->first();

        $Akey = [];
        foreach ($A as $key1) {
            array_push($Akey, $key1->number);
        }
        $NotAkey = [];
        foreach ($NotA as $key1) {
            array_push($NotAkey, $key1->number);
        }

        $totalk = [];
        foreach ($total as $key) {
            array_push($totalk, $key->number);
        }

        $charta->dataset('', 'doughnut', [$Akey, $NotAkey])
        // ->color(['#2887ed','#a6ff00'])
            ->backgroundcolor(['#2887ed', '#a6ff00']);
        $charta->displayAxes(false);

        $chart1 = new OpportunityAnalysis;
        $chart1->title("Opportunity Summary-Department wise");
        $processes = DB::select("select code from departments order by departmentname ASC");
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->code);
        }
        $chart1->labels($process);

        $phigh = DB::select("select s.id,COUNT(r.impact)AS number
                                 FROM  opportunity AS r
                                 right JOIN departments AS s
                                 ON r.department= s.id
                                 and  r.impact='high' and r.status!='Disapproved' and r.status!='Review Pending'
                                 group by s.id
                                 order by s.departmentname ASC
                                ");
        $pmoderate = DB::select("select s.id,COUNT(r.impact)AS number
                                 FROM  opportunity AS r
                                 right JOIN departments AS s
                                 ON r.department= s.id
                                 and  r.impact='moderate' and r.status!='Disapproved' and r.status!='Review Pending'
                                 group by s.id
                                 order by s.departmentname ASC
                                ");
        $plow = DB::select("select s.id,COUNT(r.impact)AS number
                                 FROM  opportunity AS r
                                 right JOIN departments AS s
                                 ON r.department= s.id
                                 and  r.impact='low' and r.status!='Disapproved' and r.status!='Review Pending'
                                 group by s.id
                                 order by s.departmentname ASC
                                ");

        $tot = DB::select("select s.id,COUNT(r.department)AS number
                                FROM  opportunity AS r
                                right JOIN departments AS s
                                ON r.department= s.id
                                 and r.status!='Disapproved' and r.status!='Review Pending'
                                group by s.id
                                order by s.departmentname ASC
                               ");
        $totp = [];
        foreach ($tot as $key) {
            array_push($totp, $key->number);
        }

        $closed = DB::select("select s.id,COUNT(r.status)AS number
                                 FROM  opportunity AS r
                                 right JOIN departments AS s
                                 ON r.department= s.id
                                 and r.status='Closed'
                                 group by s.id
                                 order by s.departmentname ASC
             ");
        $due = Opportunity::select('departments.id', DB::raw('COUNT(opportunity.status)AS number'))->rightJoin('departments', function ($join) {
            $join->on('departments.id', '=', 'opportunity.department')
                ->where('opportunity.status', '!=', 'Closed')->where('opportunity.tardate', '<=', NOW());
        })->groupBy('departments.id')
            ->orderBy('departments.departmentname', 'ASC')
            ->get();

        $closep = [];
        foreach ($closed as $key) {
            array_push($closep, $key->number);
        }
        $duep = [];
        foreach ($due as $key) {
            $duep[] = $key->number;
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

        $chart1->dataset('High', 'bar', $highp)->backgroundcolor('#58D68D')->color('#58D68D');
        $chart1->dataset('Moderate', 'bar', $moderatep)->backgroundcolor('#FFD700')->color('#FFD700');
        $chart1->dataset('Low', 'bar', $lowp)->backgroundcolor('#FC4C2B')->color('#FC4C2B');
        $chart1->dataset('Total', 'bar', $totp)->backgroundcolor('#032553')->color('#032553');

        $chart1->height(400);

        $chart2 = new OpportunityAnalysis;
        $chart2->title("Opportunity Summary(High)-Department Wise");
        $chart2->labels($process);
        $chart2->dataset('High', 'bar', $highp)->backgroundcolor('#a6ff00');
        $chart2->height(400);

        $chart3 = new OpportunityAnalysis;
        $chart3->title("Opportunity Summary(Moderate)-Department Wise");
        $chart3->labels($process);
        $chart3->dataset('Moderate', 'bar', $moderatep)->backgroundcolor('#FFD700');
        $chart2->height(400);

        $chart4 = new OpportunityAnalysis;
        $chart4->title("Opportunity Summary(Low)-Department Wise");
        $chart4->labels($process);
        $chart4->dataset('Low', 'bar', $lowp)->backgroundcolor('#f7533e');
        $chart4->height(400);

        $chart5 = new OpportunityAnalysis;
        $chart5->title("Accomplished Opportunities-Department Wise");
        $chart5->labels($process);
        $chart5->dataset('Accomplished', 'bar', $closep)->backgroundcolor('#2671B4')->color('#2671B4');
        $chart5->height(400);

        $chart6 = new OpportunityAnalysis;
        $chart6->title("Due Opportunities-Department Wise");
        $chart6->labels($process);
        $chart6->dataset('Due', 'bar', $duep)->backgroundcolor('#FC4C2B')->color('#FC4C2B');
        $chart6->height(400);
        $models = DB::table('modenadisable')->First();
        $percent = $totalk[0] ? $approved->number / $totalk[0] * 100 : 0;
        return view('user.opportunity.analysis')->with([
            'model' => $models,
            'chart' => $chart,
            'charta' => $charta,
            'chart5' => $chart5,
            'chart4' => $chart4,
            'chart3' => $chart3,
            'chart2' => $chart2,
            'chart1' => $chart1,
            'chart6' => $chart6,
            'Hkey' => $Hkey[0],
            'Mkey' => $Mkey[0],
            'Lkey' => $Lkey[0],
            'totalk' => $totalk[0],
            'percent' => round($percent),
        ]);
    }
    /**
     * Display the detail popup when click "View" button
     *
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function view($id): JsonResponse
    {
        $responseData = [];
        try {
            $value = Opportunity::with('opportunityapprover', 'closurereviewer')->findOrFail($id);
            $responseData['view'] = \view('user.opportunity._view', compact(
                'value', 'id'
            ))->render();
            $responseData['id'] = $id;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }
        return response()->json($responseData);
    }
    public function detail($id)
    {
        $models = DB::table('modenadisable')->first();
        $opportunity = Opportunity::findOrFail($id);
        $clientDetails = ClientDetail::first();

        $attachments = explode(',',$opportunity->file);
        $files = [];
        if(count($attachments)>0){
            foreach($attachments as $file){
                if($file!='NULL'){
                    $files[] = $file;
                }
            }
        }
        $attachments = view('user.opportunity._attachments', compact(
            'files','opportunity'
        ))->render();

        $processStage = view('user.opportunity._process_stage', compact(
            'opportunity'
        ))->render();

        return view('user.opportunity.detail', compact(
            'clientDetails','attachments', 'processStage','opportunity'
        ))->with('model', $models);
    }

    public function detailPdf($id)
    {
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        $opportunity = Opportunity::findOrFail($id);
        $clientDetails = ClientDetail::first();

        $attachments = explode(',',$opportunity->file);
        $files = [];
        if(count($attachments)>0){
            foreach($attachments as $file){
                if($file!='NULL'){
                    $files[] = $file;
                }
            }
        }
        $clientDetails = ClientDetail::first();
        $pdf = \PDF::loadView('user.opportunity.detail_pdf',
            compact(
                'clientDetails','files','opportunity'
            ))->setPaper('a4', 'portrait');
        return $pdf->download('detail_' . $opportunity->dep_id . '.pdf');
    }
    public function exportData(string $format)
    {
        $clientDetails = ClientDetail::first();
        if(isset($_GET['ids'])){
            $ids = json_decode($_GET['ids'], true);
            $datas = Opportunity::whereIn('id', $ids)
                ->orderby('id', 'desc')
                ->get();
        }else{
            $datas = Opportunity::with('opportunityapprover', 'closurereviewer')->whereIn('status', ['Review Pending', 'Approved', 'Disapproved', 'Completed'])
                ->whereIn('impact', ['High', 'Low', 'Moderate'])
                ->orderby('id', 'desc')
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
        $html = view('exporting.foropportunity', compact('commanData'))->render();
        $headerHtml = view('user.pdf_header', compact('commanData'))->render();
        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetFooter('{PAGENO} of {nbpg}');
        if($commanData['clientDetails'] && $commanData['clientDetails']->client_logo){
            $mpdf->SetWatermarkImage(public_path('storage/clientlogos/'.$commanData['clientDetails']->client_logo), 0.1);
        }
        $mpdf->showWatermarkImage = true;
        $mpdf->WriteHTML($html);
        return $mpdf->Output('Opportunity Datas savedBy_'.Auth::user()->name.'.pdf', 'D');
    }
    private function generateExcel($commanData){
        if (ob_get_length()) ob_end_clean();
        return Excel::download(
            new OpportunityExport(
                $commanData,
                public_path('storage/clientlogos/'.$commanData['clientDetails']->client_logo)
            ),
            'Opportunity Datas savedBy_'.Auth::user()->name.'.xlsx'
        );
    }
    private function generateWord($commanData){
        if (ob_get_length()) ob_end_clean();
        $export = new OpportunityWordExport($commanData);
        $filePath = $export->generate();
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
