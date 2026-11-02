<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\UploadedFile;



// use Validator;
use Auth;
use DB;
use App\User;
use App\Admin;
use App\Categories;
use App\Departments;
use App\Type;
// use App\Notifications;
use Form;
use App\Issue;
use App\vendor;
use App\risk;
use App\mng;
use App\Riskareas;
use App\Risknames;
use App\Riskstrategy;
use App\Notifier;
use App\Representative;
use App\Wastedisposalmethod;
use App\Escalateriskreceiver;

use Session;
use DateTime;
use App\Mail\Escalaterisk;
use App\Mail\WasteReview;
use App\Mail\WasteApproverEmail;
use App\Mail\WasteReviewRejected;
use App\Mail\WasteRequestUpdatedReviewerEmail;
use App\Mail\WasteRequestUpdatedApproverEmail;
use App\Mail\WasteApprovalHOD;
use App\Mail\WasteRequestHODRejected;
use App\Wastetarget;
use App\Wasteunit;
use App\Wastetype;
use App\Wastemaster;
use App\Waste;
use App\Wastereviewer;
use App\Wasteapprover;
use App\Wastedisposalsalesauthority;
use Carbon\Carbon;

class WasteController extends Controller
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
        return view('user.waste.home');
    }

    //add waste master

    public function addwastemaster()
    {

        $departments = Departments::all();
        $wastedesc = DB::table('wastedesc')->get();
        $wastetypes = Wastetype::get();
        $users = User::all();
        $wasteunits = Wasteunit::all();
        // below code related to superadmin
        $models = DB::table('modenadisable')->first();
        //superadmin code ends
        return view('user.waste.addwastename')->with([
            'model' => $models,
            'departments' => $departments,
            'users' => $users,
            'wastetypes' => $wastetypes,
            'wasteunits' => $wasteunits,
            'wastedesc' => $wastedesc,

        ]);
    }

    public function insertwastemaster(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'monthly_expected_qty' => 'nullable',
            'yearly_expected_qty' => 'nullable',
            'agency' => 'nullable'
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()->withErrors($validator)->withInput()->with('model', $models);
        } else {
            $dept = $request->dept;
            $responsibility = $request->responsibility;
            $wastename = $request->wastename;
            $wastecode = $request->wastecode;
            $wastetype = $request->type;
            $postedby = Auth::user()->email;
            $monthly_qty = $request->mqty;
            $yearly_qty = $request->yqty;
            $unit = $request->unit;
            if ($request->unit == "Other") {
                $unit_other = $request->other;
            } else {
                $unit_other = NULL;
            }

            $agency = $request->agency;
            if ($request->agencylicense != NULL) {
                $sec = date('s');
                if (!File::exists(public_path('/storage/waste/')))
                    File::makeDirectory(public_path() . '/storage/waste/', 0755, true);

                $license_name = 'licence' . $sec . '.' . $request->file('agencylicense')->extension();
                $request->file('agencylicense')->move(public_path() . '/storage/waste/', $license_name);
            } else {
                $license_name = NULL;
            }

            $data = array(
                'department' => $dept,
                'responsibility' => $responsibility,
                'waste_name' => $wastename,
                'waste_code' => $wastecode,
                'waste_type' => $wastetype,
                'monthly_expected_qty' => $monthly_qty,
                'yearly_expected_qty' => $yearly_qty,
                'unit' => $unit,
                'unit_other' => $unit_other,
                'agency' => $agency,
                'agency_license' => $license_name,
                'posted_by' => $postedby,

            );
            Wastemaster::create($data);
            if ($license_name != NULL) {
                $data1  = array('file_name' =>  $license_name, 'waste_name' => $wastename, 'waste_type' => $wastetype);
                DB::table('waste_externalfiles')->insert($data1);
            }

            //inserting values to activity table
            $activity = "Inserted new waste master with type  " . $wastetype . " of department " . $dept;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $data2 = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($data2);
            //superadmin code ends

            Session::flash('msg', "Details added successfully");
            return redirect('/user/waste/register');
        }
    }
    //transfer request
    public function wasterequestform()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $user = User::all();
            $wastemaster = Wastemaster::where('id', $id)->First();
            $wdept = $wastemaster ? $wastemaster->department : '-';
            // if (is_numeric($wdept)) {
            //     $departments = Departments::where('id', $wdept)->value('departmentname');
            // } else {
            //     $departments = $wdept;
            // }
            $departments = Departments::all();
            $rev = DB::table('wastereviewers')->get();
            $appr = DB::table('wasteapprovers')->get();
            $targets = Wastetarget::get();
            $wastetypes = Wastetype::get();
            //$departments = Departments::all();
            $wasteunits = Wasteunit::all();
            //$requested_by=DB::table('users')->where('email',Auth::user()->email)->value('name');
            $requested_by = Auth::user()->name;
            $wrqnums = DB::table('waste')->pluck('request_no');
            $fyear = date('Y');
            $year = substr($fyear, 2);
            $rds = [];
            $rs = [];
            if (count($wrqnums) > 0) {
                foreach ($wrqnums as $irid) {
                    if (substr($irid, -2, strrpos($irid, "/")) == $year) {
                        $rds[] = substr($irid, 0, strrpos($irid, "/"));
                    }
                }
                if (count($rds) > 0) {
                    foreach ($rds as $rd) {
                        $rs[] = substr($rd, 3, strlen($rd));
                    }
                    $iid = (int)$rs[count($rs) - 1] + 1;
                    $iid = "WRN0" . $iid . "/" . $year;
                } else {
                    $iid = "WRN01/" . $year;
                }
            } else {
                $iid = "WRN01/" . $year;
            }

            //below code related to superadmin
            $models = DB::table('modenadisable')->first();
            //superadmin code ends
            return view('user.waste.addwaste')->with([
                'departments' => $departments,
                'wdept' => $wdept,
                'waste' => $wastemaster,
                'appr' => $appr,
                'rev' => $rev,
                'model' => $models,
                'wastetypes' => $wastetypes,
                'wasteunits' => $wasteunits,
                'targets' => $targets,
                'reportnum' => $iid,
                'user' => $user,
                'requestedby' => $requested_by

            ]);
        }
    }

    public function insertwasterequest(Request $req)
    {
        $validator = Validator::make($req->all(), []);

        if ($validator->fails()) {
            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            //superadmin code ends
            return back()->withErrors($validator)->withInput()->with('model', $models);
        } else {
            $req->flash();
            $qty = $req->input('qty');
            $use = $req->input('use');
            $appr = $req->input('appr');
            //$wasteapprid = Wasteapprover::where('email',$appr)->select('id')->First()->id;
            //$apprqty = Wastetarget::where('approvers_id',$wasteapprid)->select('qty')->First()->qty;


            $wasteid = $req->wasteid;
            $request_no = $req->request_num;
            $dept = $req->input('dept');
            //$depid = Departments::where('departmentname', $dept)->value('id');
            //$resp = $req->input('resp');
            $resp = Wastemaster::where('id', $wasteid)->value('responsibility');
            $type = $req->input('type');
            $date = $req->input('date');
            $itemname = $req->input('itemname');
            $itemcode = $req->input('itemcode');
            $unit = $req->unit;
            $posted_by = Auth::user()->email;
            //date_default_timezone_set('Asia/Kuwait');
            $created_date = date('d-m-Y h:i:s A', time());
            //$posted_id=Auth::user()->employeeid;
            $posted_name = $req->requestedby;
            $count = count($req->rev);
            $revi[] = $req->rev;

            foreach ($revi as $val) {
                $reviewers = implode(",", $val);
            }

            $year = date('Y');
            $process_stage = 'Created (Awaiting HOD Approval)';
            //$process_stage = 'Transfer Requested';

            $insdata = array(
                'request_no' => $request_no,
                'department' => $dept,
                'responsibility' => $resp,
                'date' => $date,
                'itemname' => $itemname,
                'itemcode' => $itemcode,
                'type' => $type,
                'quantity' => $qty,
                'unit' => $unit,
                'useableparts' => $use,
                'posted_by' => $posted_by,
                'posted_name' => $posted_name,
                'posted_at' => $created_date,
                'reviewer' => $reviewers,
                'revcheck' => $reviewers,
                'approver' => $appr,
                'count' => $count,
                'year' => $year,
                'process_stage' => $process_stage,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );


            //sending email to department HOD for approval
            $hod = DB::table('departments')->where('departmentname', $dept)->value('hod');
            $hodname = DB::table('departments')->where('departmentname', $dept)->value('hodname');
            $maildata = array('request_no' => $request_no, 'hodname' => $hodname, 'dept' => $dept, 'yr' => $year);
            if ($hod != "") {
                Mail::to($hod)->send(new WasteApprovalHOD($maildata));
            }

            /*
                    //sending email to reviewers
                    $revmail = explode(',',$reviewers);
                    $maildata = array('request_no' => $request_no);
                    foreach($revmail as $toreviewer)
                    {
                        if($toreviewer!="")
                        {
                            Mail::to($toreviewer)->send(new WasteReview($maildata));
                        }
                    }

                //sending email to approver
                    if($appr!="")
                    {
                        Mail::to($appr)->send(new WasteApproverEmail($maildata));
                    }
                */



            DB::table('waste')->insert($insdata);
            Session::flash('msg', "Request added successfully");

            //inserting values to activity table
            $activity = "Inserted new waste with type  " . $type . " of department " . $dept;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //superadmin code ends
            return redirect()->back();
        }
    }
    //transfer request main page
    public function folder()
    {

        $targetdate = DB::table('waste')->pluck('year');
        $alltargetdates = collect($targetdate);
        $uniquetargetdates = $alltargetdates->unique()->values()->all();
        $years = [];
        for ($i = 0; $i < count($uniquetargetdates); $i++) {
            $years[] = $uniquetargetdates[$i];
        }
        $years = array_unique($years);

        //convert string array to integer array
        $integeryears = array_map(
            function ($value) {
                return (int)$value;
            },
            $years
        );

        sort($integeryears, SORT_NUMERIC);
        $models = DB::table('modenadisable')->First();
        return view('user.waste.folder')->with('model', $models)->with('years', $integeryears);
    }

    public function transferschedule($year)
    {
        //session('year', '');
        $data = DB::table('waste')->where('year', $year)
            ->latest('created_at')
            ->get();

        $dept = DB::table('departments')->get();
        $trf_auth = Wastedisposalsalesauthority::all();
        $users = User::all();
        $models = DB::table('modenadisable')->First();
        /*$wastereviewers = DB::table('wastereviewers')->get();
         $wasteapprovers = Wasteapprover::get();
        return view('user.waste.transferrequest')->with('model',$models)->with('data',$data)
                                                  ->with('reviewers',$wastereviewers);  */
        return view('user.waste.transferrequest')
            ->with('model', $models)
            ->with('data', $data)
            ->with('dept', $dept)
            ->with('year', $year)
            ->with('trfauth', $trf_auth)
            ->with('users', $users);
    }

    //waste  register
    public function register()
    {
        //below is superadmin related codes
        $wastemanagement = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($wastemanagement) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'wastemanagement' => 1]);
        } else {
            $wastemanagement = DB::table('modpageload')->where('id', 1)->First();
            $wastemanagement->wastemanagement = $wastemanagement->wastemanagement + 1;
            DB::table('modpageload')->update(['wastemanagement' => $wastemanagement->wastemanagement]);
        }
        //end of superadmin codes
        $data = Wastemaster::latest('created_at')->get();
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
        $models = DB::table('modenadisable')->First();
        return view('user.waste.reg')->with('model', $models)->with('data', $data)->with('mr', $mr)->with('departments', $departments);
    }

    //delete request by mr
    public function delete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            // inserting data to activities table
            $name = DB::table('wastemasters')->where('id', $id)->pluck('waste_type');
            $activity = "A waste entry is Removed with Type " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
            DB::table('viewactivity')->insert($datas);

            //DB::delete('delete from waste where id = :wid',['wid'=>$id]);
            DB::table('wastemasters')->where('id', $id)->delete();
            $models = DB::table('modenadisable')->First();
            return redirect('/user/wastemgmt')->with('model', $models)->with('msg', 'Waste Details Deleted Successfully');
        }
    }
    //delete request by mr
    public function deleteTransferRequest(int $id)
    {
        // inserting data to activities table
        $name = DB::table('waste')->where('id', $id)->pluck('type');
        $activity = "A waste transfer request entry is Removed with Type " . $name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
        DB::table('viewactivity')->insert($datas);

        //DB::delete('delete from waste where id = :wid',['wid'=>$id]);
        DB::table('waste')->where('id', $id)->delete();
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models)->with('msg', 'Waste transfer request Deleted Successfully');
    }

    //checking for authenticated reviewers
    /*
public function reviewcheck()
{
    if (isset($_GET['val']))
    {
        $id=$_GET['val'];

        // dd($rev);
        // $imp[]=implode( ',', $rev);
        $mail=Auth::user()->email;
        foreach($rev as $r1)
        {
            if (strpos($r1, $mail) !== false) {
                // dd($s);
                $str=str_replace($mail,'', $r1);
                $l=strlen($str);
                // dd($l);
                $stringg=substr($str, 2,$l-4);
                $count=$count-1;
                // dd($stringg);
                $data  = array('revcheck'=>$stringg,'count'=>$count);
                $models=DB::table('modenadisable')->First();
                //return redirect('/user/waste/review')->with('model',$models);

                return view('user.waste.review')->with('waste',$waste_dt)->with('model',$models);
            }
            else{
                // $models=DB::table('modenadisable')->First();
                return redirect()->back()->with('msg','Access Denied!');
            }

        }

    }
}
*/
    //HOD Approve

    public function hodapproveform()
    {
        if ((isset($_GET['val'])) && (isset($_GET['dept'])) && (isset($_GET['yr']))) {
            $request_num = $_GET['val'];
            $waste_dt = DB::table('waste')->where('request_no', $request_num)->First();
            $dept = $_GET['dept'];
            $yr =  $_GET['yr'];

            //$hod = DB::table('departments')->where('departmentname',$dept)->value('hod');
            $hod = DB::table('departments')->where('id', $dept)->value('hod');
            $deptname = Departments::where('id', $dept)->value('departmentname');

            if ($hod == Auth::user()->email) {
                $models = DB::table('modenadisable')->First();
                return view('user.waste.hodapprove')->with('waste', $waste_dt)->with('model', $models)->with('deptname', $deptname);
            } else {
                $models = DB::table('modenadisable')->First();
                return redirect('/user/waste/transferschedule/' . $yr)
                    ->with('msg', 'Access Denied ! You do not have the permission to approve &nbsp;' . $request_num);
            }
        }
    }

    public function hodapproveaction(Request $request)
    {
        switch ($request->input('submit')) {
            case 'Approve':
                $request_num = $request->request_num;
                $waste_dt = DB::table('waste')->where('request_no', $request_num)->First();
                $yr = $waste_dt->year;
                $hod_approval = 'Approved';
                $process_stage = 'HOD Approved';

                DB::table('waste')->where('request_no', $request_num)
                    ->update([
                        'process_stage' => $process_stage,
                        'hod_approval' => $hod_approval,
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ]);

                $models = DB::table('modenadisable')->First();
                return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models);
                break;

            case 'Reject':
                $request_num = $request->request_num;
                $dept =  $request->dept;
                $year = $request->year;
                $models = DB::table('modenadisable')->First();
                return view('user.waste.hodapprovereject')->with([
                    'model' => $models,
                    'reqstnum' => $request_num,
                    'dept' => $dept,
                    'year' => $year,
                ]);
                break;
        }
    }

    public function hodapprovereject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reject_reason' => 'required',
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->withErrors($validator)->withInput()->with('model', $models);
        } else {
            $reqnum = $request->req_num;
            $reject_reason = $request->reject_reason;
            $process_stage = 'HOD Rejected';
            $waste_dt = DB::table('waste')->where('request_no', $reqnum)->First();
            $yr = $waste_dt->year;
            $requested_by = $waste_dt->posted_by;
            $requestedby_name =  $waste_dt->posted_name;
            $hod_approval = 'Rejected';


            DB::table('waste')->where('request_no', $reqnum)
                ->update([
                    'hod_reject_reason' => $reject_reason,
                    'process_stage' => $process_stage,
                    'hod_approval' => $hod_approval,
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);


            //sending email to requestedby when transfer request is rejected by HOD
            $maildata = array(
                'request_no' => $reqnum,
                'name' => $requestedby_name,
                'yr' => $yr,
                'hod_reject_reason' => $reject_reason
            );

            if ($requested_by != "") {
                Mail::to($requested_by)->send(new WasteRequestHODRejected($maildata));
            }


            $models = DB::table('modenadisable')->First();
            return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models);
        }
    }

    //edit when hod reject request
    public function hodeditrequestfrm()
    {
        if ((isset($_GET['val'])) && (isset($_GET['yr']))) {
            $reqst_no = $_GET['val'];
            $yr = $_GET['yr'];
            $data = DB::table('waste')->where('request_no', $reqst_no)->First();
            $wdept = $data->department;
            $deptname = Departments::where('id', $wdept)->value('departmentname');
            $models = DB::table('modenadisable')->First();
            return view('user.waste.hodeditrequestform')->with('model', $models)
                ->with('waste', $data)
                ->with('deptname', $deptname)
                ->with('year', $yr);
        }
    }

    public function hodupdaterequest(Request $request)
    {
        $request->flash();
        $requestnum = $request->requestnum;
        $yr = $request->year;
        $qty = $request->qty;
        $use = $request->use;
        $hod_approval = NULL;
        $process_stage = "Created";


        $approver = $request->approver;
        //$reviewers = $request->reviewers;
        //$rev = explode(',',$reviewers);
        //$rcnt = count($rev);
        //$reviewedby = NULL;
        //$reviewed_cnt = 0;

        //$wasteapprid = Wasteapprover::where('email',$approver)->select('id')->First()->id;
        //$apprqty = Wastetarget::where('approvers_id',$wasteapprid)->select('qty')->First()->qty;

        DB::table('waste')->where('request_no', $requestnum)->update([
            'quantity' => $qty,
            'useableparts' => $use,
            'process_stage' => $process_stage,
            'hod_approval' => $hod_approval,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        /*
     //sending email to HOD when transfer request is updated
         $dept = $request->dept;
         $hod = DB::table('departments')->where('departmentname',$dept)->value('hod');
         $maildata = array('request_no' => $requestnum);
         if($hod!="")
         {
            Mail::to($hod)->send(new WasteRequestUpdatedApproverEmail($maildata));
         }

      //sending email to reviewers when transfer request is updated
        $revmail = explode(',',$reviewers);
        $maildata = array('request_no' => $requestnum);
        foreach($revmail as $toreviewer)
        {
            if($toreviewer!="")
            {
                Mail::to($toreviewer)->send(new WasteRequestUpdatedReviewerEmail($maildata));
            }
        }

      //sending email to approver when transfer request is updated
        if($approver!="")
        {
            Mail::to($approver)->send(new WasteRequestUpdatedApproverEmail($maildata));
        }
        */

        $models = DB::table('modenadisable')->First();
        return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models)
            ->with('updtmsg', 'Transfer Request Updated Successfully');
    }

    //delete when hod reject request
    public function hoddeleterequest()
    {
        if ((isset($_GET['val'])) && (isset($_GET['yr']))) {
            $reqst_no = $_GET['val'];
            $yr = $_GET['yr'];
            DB::table('waste')->where('request_no', $reqst_no)->delete();
            $models = DB::table('modenadisable')->First();
            return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models)
                ->with('msg', 'Transfer Request Deleted Successfully');
        }
    }

    //disposal/sales request
    public function disposalsalesform()
    {
        if ((isset($_GET['val'])) && (isset($_GET['yr']))) {
            $request_num = $_GET['val'];
            $waste_dt = DB::table('waste')->where('request_no', $request_num)->First();
            $wdept = $waste_dt->department;
            $deptname = Departments::where('id', $wdept)->value('departmentname');
            $yr =  $_GET['yr'];
            $models = DB::table('modenadisable')->First();
            return view('user.waste.disposalsales')->with('waste', $waste_dt)->with('model', $models)->with('deptname', $deptname);
        }
    }


    public function insertdisposalsales(Request $request)
    {
        $requestnum = $request->requestnum;
        $yr = $request->year;
        $approver = $request->approver;
        $reviewers = $request->reviewers;
        $transfer_type = $request->transfer_type;
        $disposal_agency = $request->disposal_agency;
        $disposal_date = $request->disposal_date;
        $sold_to = $request->sold_to;
        $sold_date = $request->sold_date;
        $requestedby = Auth::user()->email;
        if ($request->transfer_type == "disposal") {
            $process_stage = 'Disposal Requested';
        } else {
            $process_stage = "Sales Requested";
        }

        DB::table('waste')->where('request_no', $requestnum)->update([
            'transfer_type' => $transfer_type,
            'disposal_agency' => $disposal_agency,
            'disposal_date' => $disposal_date,
            'sold_to' => $sold_to,
            'sold_date' => $sold_date,
            'disp_sales_requested_by' => $requestedby,
            'process_stage' => $process_stage,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);


        //sending email to reviewers
        $revmail = explode(',', $reviewers);
        $maildata = array('request_no' => $requestnum, 'yr' => $yr);

        foreach ($revmail as $toreviewer) {
            if ($toreviewer != "") {
                Mail::to($toreviewer)->send(new WasteReview($maildata));
            }
        }

        //sending email to approver
        if ($approver != "") {
            Mail::to($approver)->send(new WasteApproverEmail($maildata));
        }


        $models = DB::table('modenadisable')->First();
        return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models)
            ->with('updtmsg', 'Transfer Request Created Successfully');
    }

    public function reviewform()
    {
        if ((isset($_GET['val'])) && (isset($_GET['rev'])) && (isset($_GET['yr']))) {
            $request_num = $_GET['val'];
            $waste_dt = DB::table('waste')->where('request_no', $request_num)->First();
            $wdept = $waste_dt->department;
            $deptname = Departments::where('id', $wdept)->value('departmentname');
            $revr = $_GET['rev'];
            $yr = $_GET['yr'];

            if ($revr == Auth::user()->email) {
                $models = DB::table('modenadisable')->First();
                return view('user.waste.review')->with('waste', $waste_dt)->with('model', $models)
                    ->with('deptname', $deptname)->with('reviwedby', $revr);
            } else {
                $models = DB::table('modenadisable')->First();
                return redirect('/user/waste/transferschedule/' . $yr)
                    ->with('msg', 'Access Denied ! You do not have the permission to review &nbsp;' . $request_num);
            }
        }
    }

    public function reviewaction(Request $request)
    {
        switch ($request->input('submit')) {
            case 'Accept':
                $request_num = $request->request_num;
                $reviewedby = $request->reviewed;
                $waste_dt = DB::table('waste')->where('request_no', $request_num)->First();
                $revr = Waste::where('request_no', $request_num)->select('reviewed_by')->First()->reviewed_by;
                $yr = $waste_dt->year;
                $reviewedcnt = $waste_dt->reviewed_count;
                $process_stage = 'Reviewed';
                $totalrev = "";
                $rcnt = 0;
                if ($revr != NULL) {
                    $totalrev .= $reviewedby . "," . $revr;
                    $rcnt = $reviewedcnt + 1;
                } else {
                    $totalrev = $reviewedby;
                    $rcnt = $rcnt + 1;
                }


                DB::table('waste')->where('request_no', $request_num)
                    ->update([
                        'reviewed_count' => $rcnt,
                        'reviewed_by' => $totalrev,
                        'process_stage' => $process_stage
                    ]);

                $models = DB::table('modenadisable')->First();
                return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models);
                break;

            case 'Reject':
                $request_num = $request->request_num;
                $reviewedby = $request->reviewed;
                $year = $request->year;
                $models = DB::table('modenadisable')->First();
                return view('user.waste.reviewreject')->with([
                    'model' => $models,
                    'reqstnum' => $request_num,
                    'reviewedby' => $reviewedby,
                    'year' => $year,
                ]);
                break;
        }
    }

    public function reviewreject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reject_reason' => 'required',
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->withErrors($validator)->withInput()->with('model', $models);
        } else {

            $reviewed = $request->reviewed;
            $reqnum = $request->req_num;
            $reject_reason = $request->reject_reason;
            $process_stage = 'Rejected';
            $waste_dt = DB::table('waste')->where('request_no', $reqnum)->First();
            $yr = $waste_dt->year;
            $requested_by = $waste_dt->posted_by;
            $requestedby_name =  $waste_dt->posted_name;
            $reviewedcnt = $waste_dt->reviewed_count;

            $revr = Waste::where('request_no', $reqnum)->select('reviewed_by')->First()->reviewed_by;
            $totalrev = "";
            $rcnt = 0;
            if ($revr != NULL) {
                $totalrev .= $reviewed . "," . $revr;
                $rcnt = $reviewedcnt + 1;
            } else {
                $totalrev = $reviewed;
                $rcnt = $rcnt + 1;
            }

            DB::table('waste')->where('request_no', $reqnum)
                ->update([
                    'review_reject_reason' => $reject_reason,
                    'reviewed_by' => $totalrev,
                    'reviewed_count' => $rcnt,
                    'process_stage' => $process_stage
                ]);

            //Sending email to requestedby person when review is rejected
            $data = array(
                'line1' => Lang::get('Dear ' . $requestedby_name),
                'line2' => Lang::get('Waste Transfer Request for Request Number - ' . $reqnum . ' has been rejected due to  ' . $reject_reason),
            );
            Mail::to($requested_by)->send(new WasteReviewRejected($data));

            $models = DB::table('modenadisable')->First();
            return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models);
        }
    }
    //approve form
    public function approveform()
    {
        if ((isset($_GET['val'])) && (isset($_GET['appr'])) && (isset($_GET['yr']))) {
            $request_num = $_GET['val'];
            $waste_dt = DB::table('waste')->where('request_no', $request_num)->First();
            $wdept = $waste_dt->department;
            $deptname = Departments::where('id', $wdept)->value('departmentname');
            $appr = $_GET['appr'];
            $yr =  $_GET['yr'];

            if ($appr == Auth::user()->email) {
                $models = DB::table('modenadisable')->First();
                return view('user.waste.approve')->with('waste', $waste_dt)->with('model', $models)->with('deptname', $deptname);
            } else {
                $models = DB::table('modenadisable')->First();
                return redirect('/user/waste/transferschedule/' . $yr)
                    ->with('msg', 'Access Denied ! You do not have the permission to approve &nbsp;' . $request_num);
            }
        }
    }

    public function approveaction(Request $request)
    {
        switch ($request->input('submit')) {
            case 'Approve':
                $request_num = $request->request_num;
                $waste_dt = DB::table('waste')->where('request_no', $request_num)->First();
                $yr = $waste_dt->year;
                $process_stage = 'Approved';

                DB::table('waste')->where('request_no', $request_num)
                    ->update([
                        'process_stage' => $process_stage
                    ]);

                $models = DB::table('modenadisable')->First();
                return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models);
                break;

            case 'Reject':
                $request_num = $request->request_num;
                $approvedby = $request->approved;
                $year = $request->year;
                $models = DB::table('modenadisable')->First();
                return view('user.waste.approvereject')->with([
                    'model' => $models,
                    'reqstnum' => $request_num,
                    'approved' => $approvedby,
                    'year' => $year,
                ]);
                break;
        }
    }

    public function approvereject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reject_reason' => 'required',
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->withErrors($validator)->withInput()->with('model', $models);
        } else {
            $reqnum = $request->req_num;
            $reject_reason = $request->reject_reason;
            $process_stage = 'Rejected';
            $waste_dt = DB::table('waste')->where('request_no', $reqnum)->First();
            $yr = $waste_dt->year;
            $requested_by = $waste_dt->posted_by;
            $requestedby_name =  $waste_dt->posted_name;

            DB::table('waste')->where('request_no', $reqnum)
                ->update([
                    'approval_reject_reason' => $reject_reason,
                    'process_stage' => $process_stage
                ]);

            //Sending email to requestedby person when approval is rejected
            $data = array(
                'line1' => Lang::get('Dear ' . $requestedby_name),
                'line2' => Lang::get('Waste Transfer Request for Request Number - ' . $reqnum . ' has been rejected due to  ' . $reject_reason),
            );
            Mail::to($requested_by)->send(new WasteReviewRejected($data));

            $models = DB::table('modenadisable')->First();
            return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models);
        }
    }
    //edit request form
    public function editrequestfrm()
    {
        if ((isset($_GET['val'])) && (isset($_GET['yr']))) {
            $reqst_no = $_GET['val'];
            $yr = $_GET['yr'];
            $data = DB::table('waste')->where('request_no', $reqst_no)->First();
            $wdept = $data->department;
            $deptname = Departments::where('id', $wdept)->value('departmentname');
            $models = DB::table('modenadisable')->First();
            return view('user.waste.editrequestform')->with('model', $models)
                ->with('waste', $data)
                ->with('deptname', $deptname)
                ->with('year', $yr);
        }
    }

    public function updaterequest(Request $request)
    {
        $request->flash();
        $requestnum = $request->requestnum;
        $yr = $request->year;
        $qty = $request->qty;
        $use = $request->use;
        $transfer_type = $request->transfer_type;
        if ($request->transfer_type == "disposal") {
            $process_stage = "Disposal Requested";
        } else {
            $process_stage = "Sales Requested";
        }
        //$process_stage = "Created";

        $approver = $request->approver;
        $reviewers = $request->reviewers;
        $rev = explode(',', $reviewers);
        $rcnt = count($rev);
        $reviewedby = NULL;
        $reviewed_cnt = 0;

        //$wasteapprid = Wasteapprover::where('email',$approver)->select('id')->First()->id;
        //$apprqty = Wastetarget::where('approvers_id',$wasteapprid)->select('qty')->First()->qty;


        DB::table('waste')->where('request_no', $requestnum)->update([
            'quantity' => $qty,
            'useableparts' => $use,
            'count' => $rcnt,
            'reviewed_by' => $reviewedby,
            'reviewed_count' => $reviewed_cnt,
            'process_stage' => $process_stage
        ]);


        //sending email to reviewers when transfer request is updated
        $revmail = explode(',', $reviewers);
        $maildata = array('request_no' => $requestnum, 'yr' => $yr);

        foreach ($revmail as $toreviewer) {
            if ($toreviewer != "") {
                Mail::to($toreviewer)->send(new WasteRequestUpdatedReviewerEmail($maildata));
            }
        }

        //sending email to approver when transfer request is updated
        if ($approver != "") {
            Mail::to($approver)->send(new WasteRequestUpdatedApproverEmail($maildata));
        }

        $models = DB::table('modenadisable')->First();
        return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models)
            ->with('updtmsg', 'Transfer Request Updated Successfully');
    }

    //delete request
    public function deleterequest()
    {
        if ((isset($_GET['val'])) && (isset($_GET['yr']))) {
            $reqst_no = $_GET['val'];
            $yr = $_GET['yr'];
            DB::table('waste')->where('request_no', $reqst_no)->delete();
            $models = DB::table('modenadisable')->First();
            return redirect('/user/waste/transferschedule/' . $yr)->with('model', $models)
                ->with('msg', 'Transfer Request Deleted Successfully');
        }
    }

    //edit waste by mr
    public function editwastemr()
    {

        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $allid = explode(',', $id);
            $departments = Departments::all();
            $wastetypes = Wastetype::get();
            $wastedisposalmethods = Wastedisposalmethod::all();
            $users = User::all();
            $wasteunits = Wasteunit::all();
            foreach ($allid as $wid) {
                $data[] = DB::table('wastemasters')->where('id', $wid)->First();
            }

            $models = DB::table('modenadisable')->First();
            return view('user.waste.editwaste')->with('data', $data)->with('model', $models)
                ->with('dept', $departments)->with('wastetype', $wastetypes)
                ->with('wastedisposalmethods', $wastedisposalmethods)
                ->with('users', $users)->with('wasteunits', $wasteunits);
        }
    }

    //edit waste transfer request
    public function editTransferRequest(string $id)
    {
        $allid = explode(',', $id);
        $departments = Departments::all();
        $wastetypes = Wastetype::get();
        $wastedisposalmethods = Wastedisposalmethod::all();
        $users = User::all();
        $wasteunits = Wasteunit::all();
        foreach ($allid as $wid) {
            $data[] = DB::table('waste')->where('id', $wid)->First();
        }
        foreach($data as $item)
        {
            $year = $item->year;
        }
        $rev = DB::table('wastereviewers')->get();
        $appr = DB::table('wasteapprovers')->get();
        $models = DB::table('modenadisable')->First();

        return view('user.waste.editwastetransferrequest')
                                    ->with('data', $data)
                                    ->with('year',$year)
                                    ->with('model', $models)
                                    ->with('departments', $departments)->with('wastetype', $wastetypes)
                                    ->with('rev', $rev)
                                    ->with('appr',$appr)
                                    ->with('wastedisposalmethods', $wastedisposalmethods)
                                    ->with('users', $users)->with('wasteunits', $wasteunits);
    }
    public function updatewaste(Request $request)
    {
        $ids = $request->id;
        foreach ($request->id as $key => $value) {
            $data = DB::table('wastemasters')->where('id', $request->id[$key])->update([
                'department' => $request->dept[$key],
                'responsibility' => $request->responsibility[$key],
                'waste_name' => $request->wname[$key],
                'waste_code' => $request->wcode[$key],
                'waste_type' =>  $request->type[$key],
                'disposal_method' => $request->wdisposalmethod[$key],
                'monthly_expected_qty' => $request->mqty[$key],
                'yearly_expected_qty' => $request->yqty[$key],
                'unit' => $request->units[$key],
                'unit_other' => $request->units_other[$key],
                'agency' => $request->agency[$key],
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            //insert data to activity table superadmin code
            $activity = "Waste with ID " . $request->id[$key] . " is Edited and Updated with new Details";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code

        }
        $models = DB::table('modenadisable')->First();
        return redirect('/user/wastemgmt')->with('model', $models)->with('msg', 'Waste Details edited successfully');
    }
    public function updatetransferrequest(Request $request)
    {
        $ids = $request->id;
        foreach ($request->id as $key => $value) {
            $data = DB::table('waste')->where('id', $request->id[$key])->update([
                'department' => $request->dept[$key],
                'quantity' => $request->qty[$key],
                'useableparts' => $request->use[$key],
                'reviewer' => implode(',',$request->rev[$key]),
                'approver' => $request->appr[$key],
            ]);

            //insert data to activity table superadmin code
            $activity = "Transfer request with ID " . $request->id[$key] . " is Edited and Updated with new Details";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code

        }
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models)->with('msg', 'Transfer request Details edited successfully');
    }

    //dispose for sold/disposal request
    public function wastedispose(Request $request)
    {
        $wasteid = $request->input('wasteid');
        $disposed_completed = 'yes';
        $completed_dt = date('Y-m-d');
        $completedby = Auth::user()->email;
        $process_stage = 'Physically Disposed';

        DB::table('waste')->where('id', $wasteid)->update([
            'disposed_completed' => $disposed_completed,
            'disposed_completed_date' => $completed_dt,
            'disposed_completed_by' => $completedby,
            'process_stage' => $process_stage,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return [
            "data" => null,
            "error" => null
        ];
    }

    //waste analysis
    public function analysis()
    {
        $models = DB::table('modenadisable')->first();
        $total_transfer_request_count = Waste::where('year', '!=', null)->count();
        $transfer_requests_under_review_count = Waste::where('year', '!=', null)->where('process_stage', 'Created (Awaiting HOD Approval)')->count();
        $awaiting_sales_request_approval_count = Waste::where('year', '!=', null)->where('process_stage', 'Sales Requested')->count();
        $awaiting_disposal_request_approval_count = Waste::where('year', '!=', null)->where('process_stage', 'Disposal Requested')->count();
        $waste_types = Wastetype::pluck('name')->toArray();
        $data_type = [];
        foreach ($waste_types as $type) {
            $data_type[] = Wastemaster::where('waste_type', $type)->count();
        }
        $data_year = [];
        $two_years_before = [date('Y') - 1, date('Y')];
        foreach ($two_years_before as $year) {
            $data_year[] = Waste::where('year', $year)->count();
        }
        return view('user.waste.analysis')->with([
            'model' => $models,
            'total_transfer_request_count' => $total_transfer_request_count,
            'transfer_requests_under_review_count' => $transfer_requests_under_review_count,
            'awaiting_sales_request_approval_count' => $awaiting_sales_request_approval_count,
            'awaiting_disposal_request_approval_count' => $awaiting_disposal_request_approval_count,
            'waste_types' => json_encode($waste_types),
            'data_type' => json_encode($data_type),
            'two_years_before' => json_encode($two_years_before),
            'data_year' => json_encode($data_year)
        ]);
    }
}
