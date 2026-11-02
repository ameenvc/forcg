<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;




// use Validator;
use Auth;
use DB;
use App\User;
use App\Admin;
use App\Categories;
use App\Departments;
use App\Type;
use App\EmployeeDetails;
use App\Hrtraining;
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
use App\Escalateriskreceiver;
use App\Charts\RiskAnalysis;
use App\HrJob;
use DateTime;
use Session;
use App\Mail\Escalaterisk;
use Carbon\Carbon;

class HrController extends Controller
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

    //add job

    public function addemp()
    {

        $departments = Departments::all();
        $hods = DB::table('departments')->pluck('hod');
        // $sa = DB::table('supplierapprovers')->pluck('email');
        $models = DB::table('modenadisable')->First();
        return view('user.hr.addjob')->withDepartments($departments)->withHods($hods)->with('count', $count)->with('model', $models);
    }
    //add job

    public function addjob()
    {

        $departments = Departments::all();
        $hods = DB::table('departments')->pluck('hod');
        $code = DB::table('jobcode')->value('code');
        // $sa = DB::table('supplierapprovers')->pluck('email');
        $exist = DB::table('hr_job')->get();
        if ($code == "AutoGenerate JobCode") {
            if ($exist->isEmpty()) {
                $count = "10000";
            } else {

                // $c=$exist->job_code;
                $c = DB::table('hr_job')->orderBy('id', 'desc')->first();
                $jc = $c->job_code;
                $count = (int)$jc + 1;
            }
        } else {
            $count = "0";
        }
        // below code related to superadmin
        $models = DB::table('modenadisable')->First();
        return view('user.hr.addjob')->withDepartments($departments)->withHods($hods)->with('count', $count)->with('model', $models);
    }

    public function insertjob(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'respo' => ['required', 'string', 'max:2000'],
            'code' => ['required']
        ], [
            'respo.max' => 'The Responsibilites may not be greater than 2000 characters.',
            'code.required' => 'The job code field is required. Please enter a job code.'
        ]);

        if ($validator->fails()) {
            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {

            $pos_title = $req->input('pos_title');
            $report = $req->input('report');
            $dept = $req->input('dept');
            $code = $req->input('code');
            $respo = $req->input('respo');
            $auth = $req->input('auth');
            $account = $req->input('account');
            $hse = $req->input('hse');
            $internal = $req->input('internal');
            $external = $req->input('external');
            $qual = $req->input('qual');
            $skills = $req->input('skills');
            $exp = $req->input('exp');
            $prepared = $req->input('prepared_on');
            date_default_timezone_set('Asia/Kuwait');
            $created_date = date('d-m-Y h:i:s A', time());
            $posted_id = Auth::user()->employeeid;

            $data  = array(
                'position_title' => $pos_title, 'reports_to' => $report, 'dept' => $dept, 'job_code' => $code,
                'responsibilities' => $respo, 'job_authorities' => $auth, 'accountabilities' => $account,
                'hse' => $hse, 'internal' => $internal, 'external' => $external, 'min_qual' => $qual, 'training_skills' => $skills, 'min_exp' => $exp, 'prepared_on' => $prepared, 'created_at' => $created_date, 'added_by' => $posted_id
            );
            // dd($data);
            DB::table('hr_job')->insert($data);
            Session::flash('msg', "Job added successfully");
            // $models=DB::table('modenadisable')->First();
            return redirect()->back();
            // return view('/user/vendor/register');


        }
    }


    //add training
    public function addtraining()
    {

        $departments = Departments::all();
        $hods = DB::table('departments')->pluck('hod');
        $emp = DB::table('employee_details')->get();
        $dep_details = DB::table('departments')->select('id','departmentname')->get();

        // below code related to superadmin
        $models = DB::table('modenadisable')->First();
        return view('user.hr.addtraining')
                               ->withDepartments($departments)->withemp($emp)->with('dep',$dep_details)
                               ->withHods($hods)->with('model', $models);
    }


    public function inserttraining(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'in_out' => ['required'],
            'periods' => ['required', 'date'],
            'agency' => ['required_if:in_out,Outside']
        ]);

        if ($validator->fails()) {
            // echo "<script> alert('Error Resubmit the form')</script>";
            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {

           /* $empname = $req->input('empname');
            if ($empname == null) {
                $chkemp = "null";
            } else {
                $checkbox1 = $_POST['empname'];
                $chkemp = "";
                foreach ($checkbox1 as $chk1) {
                    $chkemp .= $chk1 . " ";
                }
            }
            $position = $req->input('pos');
            if ($position == null) {
                $chkpos = "null";
            } else {
                $checkbox1 = $_POST['pos'];
                $chkpos = "";
                foreach ($checkbox1 as $chk1) {
                    $chkpos .= $chk1 . " ";
                }
            }

                $emp_number = $_POST['number'];
                $chkempnum = "";
                foreach($emp_number as $chk1)
                {
                    $chkempnum .= $chk1 . ",";
                }
               dd($chkempnum);
               */

              $empname = $req->input('empname');
              if ($empname == null)
               {
                 $emp_name = "null";
               }
              else
              {
                $emp_name = implode(',',$req->empname);
              }

              $position = $req->input('pos');
              if ($position == null)
               {
                 $emp_post = "null";
               }
              else
              {
                $emp_post = implode(',',$req->pos);
              }

               $emp_number = implode(',',$req->number);

               //fetaching id from employee-details table
               $employeenumber = $req->number;
               $employeeid = array();
               foreach($employeenumber as $val)
               {
                   $empid = EmployeeDetails::where('emp_number',$val)->value('id');
                   array_push($employeeid,$empid);
               }

              $trainingempid = implode(',',$employeeid);


               $emp_dept = $req->department;
               $dept = array();
               foreach($emp_dept as $dep)
               {
                  $empdepid = DB::table('departments')->where('departmentname',$dep)->value('id');
                  array_push($dept,$empdepid);
               }
             $empdepart = implode(',',$dept) ;

            $title = $req->input('title');
            $type = $req->input('type');
            /*if ($type == null) {
                $chktype = "null";
            } else {
                $checkbox1 = $_POST['type'];
                $chktype = "";
                foreach ($checkbox1 as $chk1) {
                    $chktype .= $chk1 . "<br>";
                }
            }*/
            if ($type == null)
             {
                $chktype = "null";
             }
            else
              {
                $chktype = implode(',',$type);
              }

            $inhouse = $req->input('in_out');
            $provider = $req->input('provider');
            $agency = $req->input('agency');
            $dur = $req->input('dur');
            $periods = $req->input('periods');
            $status = $req->input('status');
            // $eval=$req->input('eval');
            date_default_timezone_set('Asia/Kuwait');
            $created_date = date('d-m-Y h:i:s A', time());
            $posted_id = Auth::user()->employeeid;
            $benefits = $req->input('benefits');
            $data  = array(
                'emp_name' => $emp_name,'emp_number' => $emp_number,'emp_department' => $empdepart,'position' => $emp_post, 'training_title' => $title, 'training_type' => $chktype,
                'inhouse_outside' => $inhouse, 'training_provider' => $provider, 'training_agency' => $agency, 'training_duration' => $dur,
                'periods' => $periods, 'benefits' => $benefits, 'status' => $status, 'created_at' => $created_date, 'posted_id' => $posted_id,'employee_id' => $trainingempid
            );
            //dd($data);
            DB::table('hr_training')->insert($data);
            Session::flash('msg', "Training schedule added successfully");
            // $models=DB::table('modenadisable')->First();
            return redirect()->back();
            // return view('/user/vendor/register');

            //superadmin related codes
            //inserting values to activity table
            // $activity="Inserted new Risk with Name as ".$riskname." and with area as ".$riskarea;
            // $date=Carbon::now()->toDateTimeString();
            // $date=Carbon::parse($date);
            // $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'user');
            // DB::table('viewactivity')->insert($datas);
            //superadmin code ends
            //     $code=strtoupper(substr($riskgroup,0,3));
            //     $dep_id =$code.$count;
            //     $data3 = array('grp_id' => $dep_id );
            //     DB::table('risks')->where('count',$count)
            //                         ->where('riskgroup', $riskgroup)
            //                     ->update($data3);

            //     Session::flash('msg',"Risk sent for approval Successfully");
            // $models=DB::table('modenadisable')->First();
            // return redirect()->back()->with('model',$models);
        }
    }


    //edit training
    public function edittraining()
    {

        if (isset($_GET['val'])) {
            $tid = $_GET['val'];
            //$data = DB::table('hr_training')->where('id', $tid)->get();
            $data = DB::table('hr_training')->where('id', $tid)->first();
            $d = DB::table('departments')->get();
            $dep = Departments::select('id','departmentname')->get();
            $emp = EmployeeDetails::all();
            // $strategy = DB::table('riskstrategies')->get();
            $models = DB::table('modenadisable')->First();
            //return view('user/hr/edittraining')->withData($data)->with('d', $d)->with('id', $tid)->with('model', $models);
            return view('user.hr.edit_training')
                               ->with('data',$data)->with('d', $d)->with('emp',$emp)
                               ->with('id', $tid)->with('model', $models)->with('dep',$dep);
        }
    }
    //update training
    /*public function updatetraining(Request $req)
    {
        $id = $req->input('id');
        $tid = DB::select('select * from hr_training where id = :id', ['id' => $id]);
        $empname = $req->input('empname');
        $position = $req->input('pos');
        $title = $req->input('title');
        $type = $req->input('type');
        if ($type == null) {
            $chktype = "null";
        } else {
            $checkbox1 = $_POST['type'];
            $chktype = "";
            foreach ($checkbox1 as $chk1) {
                $chktype .= $chk1 . ",";
            }
        }
        $inhouse = $req->input('in_outt');
        $provider = $req->input('provider');
        $agency = $req->input('agency');
        $dur = $req->input('dur');
        $periods = $req->input('periods');
        $benefits = $req->input('benefits');
        if ($inhouse == "Inhouse") {
            $a = " ";
        } else {
            $a = $agency;
        }

        foreach ($tid as $row) {
            $t1id = (string)$row->id;
            if ($t1id == $id or $tid == null) {
                DB::update('update hr_training set emp_name=:en,position=:pos,training_title=:title,training_type=:type,inhouse_outside=:inout,training_provider=:pro,training_agency=:agency,training_duration=:dur,periods=:pd,benefits=:ben where id = :tid', ['en' => $empname, 'pos' => $position, 'title' => $title, 'type' => $chktype, 'inout' => $inhouse, 'pro' => $provider, 'agency' => $a, 'dur' => $dur, 'pd' => $periods, 'ben' => $benefits, 'tid' => $id]);
                return redirect('/user/hrtmgmt');
            } else {
                return redirect('/user/hrtmgmt');
            }
        }
    }
*/
     public function updatetraining(Request $req)
       {
           $id = $req->editid;
           $title = $req->input('title');
           $inhouse = $req->input('in_out');
           $type = $req->input('type');
           if ($type == null)
             {
                $chktype = "null";
             }
           else
            {
               $chktype = implode(',',$type);
            }

          if($req->agency!=NULL)
           {
              $agency = $req->agency;
           }
          else
          {
            $agency = NULL;
          }
         $provider = $req->input('provider');
         $dur = $req->input('dur');
         $periods = $req->input('periods');
         $status = $req->input('status');
         $benefits = $req->input('benefits');

         //fetching employee details from db
           $empdetails = Hrtraining::where('id',$id)->first();
           $empdbnum = $empdetails->emp_number;
           $empdbname = $empdetails->emp_name;
           $empdbpost = $empdetails->position;
           $empdbdept = $empdetails->emp_department;
           $empdbid = $empdetails->employee_id;



            $empname = $req->input('empname');
              if($empname[0]==null)
              {
                $emp_name = $empdbname;
              }
             else
              {
                $emp_named = implode(',',$req->empname);
                $emp_name = $empdbname.','.$emp_named;
              }

              $position = $req->input('pos');
              if ($position[0] == null)
               {
                 $emp_post = $empdbpost;
               }
              else
              {
                $emp_postd = implode(',',$req->pos);
                $emp_post = $empdbpost.','.$emp_postd;
              }

               $emp_number = $req->number;
                if ($emp_number[0] == null)
                {
                    $emp_number = $empdbnum;
                }
                else
                {
                    $emp_numd = implode(',',$req->number);
                    $emp_number = $empdbnum.','.$emp_numd;
                }

               //fetaching id from employee_details table
               $employeenumber = $req->number;
               $employeeid = array();
               foreach($employeenumber as $val)
               {
                   $empid = EmployeeDetails::where('emp_number',$val)->value('id');
                   array_push($employeeid,$empid);
               }

              $empnm = $req->number;
              if($empnm[0]==NULL)
              {
                $trainingempid = $empdbid;
              }
             else
              {
                $traingempid = implode(',',$employeeid);
                $trainingempid = $empdbid.','.$traingempid;
              }



               $emp_dept = $req->department;
               $dept = array();
               foreach($emp_dept as $dep)
               {
                  $empdepid = DB::table('departments')->where('departmentname',$dep)->value('id');
                  array_push($dept,$empdepid);
               }

             if($emp_dept[0]==NULL)
             {
                $empdepart = $empdbdept;
             }
             else
             {
                $empdepar = implode(',',$dept);
                $empdepart = $empdbdept.','.$empdepar;
             }

             DB::table('hr_training')->where('id',$id)->update([
                                  'emp_name' => $emp_name,
                                  'emp_number' => $emp_number,
                                  'emp_department' => $empdepart,
                                  'position' => $emp_post,
                                  'training_title' => $title,
                                  'training_type' => $chktype,
                                  'inhouse_outside' => $inhouse,
                                  'training_provider' => $provider,
                                  'training_agency' => $agency,
                                  'training_duration' => $dur,
                                  'periods' => $periods,
                                  'benefits' => $benefits,
                                  'status' => $status,
                                  'employee_id' => $trainingempid,
                                  'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

             ]);
             Session::flash('msg', "Training Details Updated Successfully");
             return redirect()->back();
       }



    //add questions
    public function addqstns()
    {
        $data = DB::table('trainingdetails')->get();

        // below code related to superadmin
        $models = DB::table('modenadisable')->First();
        return view('user.hr.addqstns')->withData($data)->with('model', $models);
    }


    public function insertqstns(Request $req)
    {

        $validator = Validator::make($req->all(), []);

        if ($validator->fails()) {
            // echo "<script> alert('Error Resubmit the form')</script>";
            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {

            $q = $req->input('qstn');

            $data  = array('item' => $q);
            // dd($data);
            DB::table('trainingdetails')->insert($data);
            // Session::flash('msg',"Training schedule added successfully");
            // $models=DB::table('modenadisable')->First();
            return redirect()->back();
        }
    }

    // remove qstn
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
        DB::delete('delete from trainingdetails where id = :tid', ['tid' => $id]);
        return redirect('/user/hr/addqstn')->with('model', $models);
    }

    //edit question
    //update training
    public function updateqstns(Request $req)
    {
        $id = $req->input('id');
        $qid = DB::select('select * from trainingdetails where id = :id', ['id' => $id]);
        $item = $req->input('item');

        foreach ($qid as $row) {
            $q1id = (string)$row->id;
            if ($q1id == $id or $qid == null) {
                DB::update('update trainingdetails set item=:i where id = :qid', ['i' => $item, 'qid' => $id]);
                return redirect('/user/hr/addqstn');
            } else {
                return redirect('/user/hr/addqstn');
            }
        }
    }

    //evaluate training
    public function evaluate()
    {
        if (isset($_GET['val'])) {
            $tid = $_GET['val'];
            $data = DB::table('hr_training')->where('id', $tid)->get();
            // $d=DB::table('departments')->get();
            $q = DB::table('trainingdetails')->get();

            // $strategy = DB::table('riskstrategies')->get();

            $models = DB::table('modenadisable')->First();
            return view('user/hr/evaluate')->withData($data)->with('q', $q)->with('id', $tid)->with('model', $models);
        }
    }

    public function evalqstns(Request $req)
    {
        $validator = Validator::make($req->all(), []);

        if ($validator->fails()) {
            // echo "<script> alert('Error Resubmit the form')</script>";
            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $id = $req->input('id');
            $tid = DB::select('select * from hr_training where id = :tid', ['tid' => $id]);
            $c = $_POST['qstn'];
            $count = 0;
            foreach ($c as $chk1) {
                $count = $count + 1;
            }
            $qn = $req->input('qn');
            $chk2 = 0;
            $chk = "";
            $c1 = 0;
            $c2 = 0;
            $c3 = 0;
            $c4 = 0;
            $tot = 0;
            $p = "";
            $ind_perf = "";
            $per = 0;
            $ind = 0;
            $tot_p = 0;
            $tp = 0;
            foreach ($c as $chk1) {
                $chk .= $chk1 . "  ";
                if ($chk1 == 0) {
                    $c1 = $c1 + 1;
                    $ind = ((1 * 0) / ($count * 0.3)) * 100;
                } else if ($chk1 == 0.1) {
                    $c2 = $c2 + 1;
                    $ind = ((1 * 0.1) / ($count * 0.3)) * 100;
                } else if ($chk1 == 0.2) {
                    $c3 = $c3 + 1;
                    $ind = ((1 * 0.2) / ($count * 0.3)) * 100;
                } else {
                    $c4 = $c4 + 1;
                    $ind = ((1 * 0.3) / ($count * 0.3)) * 100;
                }

                $chk2 = $chk2 + $chk1;
                // $per.=(($c1*0)+($c2*0.1)+($c3*0.2)+($c4*0.3)/($c1+$c2+$c3+$c4)*0.3)*100;
                $ind_perf .= $ind . ",";
                $tp += $tp + $tot_p;
            }
            $total_p = (($chk2) / ($qn * $count * 0.3)) * 100;
            // $tot=$c1+$c2+$c3+$c4;
            date_default_timezone_set('Asia/Kuwait');
            $evaldate = date('d-m-Y h:i:s A', time());
            $evalid = Auth::user()->employeeid;
            // $data  = array('chek'=>$chk,'totalper'=>$total_p,'total'=>$chk2,'per' => $p);
            // DB::table('formula')->insert($data);
            // return redirect('/user/hr/register');


            foreach ($tid as $row) {
                $t1id = (string)$row->id;
                if ($t1id == $id or $tid == null) {
                    DB::update('update hr_training set status=:ts,evaluatedby=:te,evaluated_at=:tat where id = :tid', ['ts' => $total_p, 'te' => $evalid, 'tat' => $evaldate, 'tid' => $id]);
                    return redirect('/user/hrtmgmt');
                } else {
                    return redirect('/user/hrtmgmt');
                }
            }
        }
    }


    // register
    public function register()
    {

        //below is superadmin related codes
        $hr = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($hr) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'hr' => 1]);
        } else {
            $hr = DB::table('modpageload')->where('id', 1)->First();
            $hr->hr = $hr->hr + 1;
            DB::table('modpageload')->update(['hr' => $hr->hr]);
        }
        //end of superadmin codes
        //Assessment

        //job
        $departments = DB::table('departments')->get();
        $data = HrJob::get();

        //training
        $data1 = DB::table('hr_training')->get();
        $tc = DB::table('trainingcoordinators')->get();
        $u = DB::table('users')->pluck('email');

        $mr = DB::table('representatives')->pluck('email');
        $md = DB::table('managements')->pluck('email');
        $models = DB::table('modenadisable')->First();
        return view('user.hr.register')->with('model', $models)->with('data', $data)->with('mr', $mr)->with('md', $md)->with('users', $u)->with('data1', $data1)->with('tc', $tc);
    }
    public function tregister()
    {
        //job
        $departments = DB::table('departments')->get();
        $data = DB::table('hr_job')->get();

        //training
        //$data1 = DB::table('hr_training')->get();
        $data1 = Hrtraining::all();
        $tc = DB::table('trainingcoordinators')->get();
        $u = DB::table('users')->pluck('email');

        $mr = DB::table('representatives')->pluck('email');
        $md = DB::table('managements')->pluck('email');
        $models = DB::table('modenadisable')->First();
        return view('user.hr.trainingregister')->with('model', $models)->with('data', $data)->with('mr', $mr)->with('md', $md)->with('users', $u)->with('data1', $data1)->with('tc', $tc);
    }

    //update status for training schedule
    public function updatestatus()
    {
        if (isset($_GET['val'])) {
            $tid = $_GET['val'];
            $data = DB::table('hr_training')->where('id', $tid)->get();
            $d = DB::table('departments')->get();


            // $strategy = DB::table('riskstrategies')->get();

            $models = DB::table('modenadisable')->First();
            return view('user/hr/updatestatus')->withData($data)->with('d', $d)->with('id', $tid)->with('model', $models);
        }
    }
    //status update on clicking submit button
    public function eval(Request $request)
    {
        $id = $request->input('id');
        $tid = DB::select('select * from hr_training where id = :id', ['id' => $id]);
        $status = $request->input('status');


        foreach ($tid as $row) {
            $t1id = (string)$row->id;
            if ($t1id == $id or $tid == null) {
                DB::update('update hr_training set status=:st where id = :tid', ['st' => $status, 'tid' => $id]);
                return redirect('/user/hrtmgmt');
            } else {
                return redirect('/user/hrtmgmt');
            }
        }
    }



    //delete training request by mr
    public function deletetraining()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            // inserting data to activities table
            $name = DB::table('hr_training')->where('id', $id)->pluck('training_title');
            $activity = "A training entry is Removed with Title " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
            DB::table('viewactivity')->insert($datas);

            DB::delete('delete from hr_training where id = :tid', ['tid' => $id]);
            // DB::delete('delete from objreport where Oid = :eid',['eid'=>$id]);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/hrtmgmt')->with('model', $models);
        }
    }



    //edit training by mr
    public function edittraining_mr()
    {

        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            //    $vendor = Vendor::all();
            // $purpose = Purpose::all();
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = DB::table('hr_training')->where('id', $d)->first();
                }
                $n = $n + 1;
            }
        }

        $models = DB::table('modenadisable')->First();
        return view('/user/hr/edittraining_mr')->withData($data)
            ->with('id', $id)
            ->with('model', $models);
    }
    public function editmrtraining(Request $req)
    {
        $m = count($req->empname);

        //    $empname = $req->input('empname');

        //    $pos=   $req->input('pos');
        $title = $req->input('title');
        $name = $req->input('name');
        $dur = $req->input('dur');
        $date = $req->input('date');
        $ben = $req->input('ben');
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = DB::table('hr_training')->where('id', $d)->first();
            }
        }

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    DB::table('hr_training')->where('id', $s)
                        ->update(array(
                            'training_title'        => $req->title[$n],
                            'training_provider'              => $req->name[$n],
                            'training_duration'        => $req->dur[$n],
                            'periods'              => $req->date[$n],
                            'benefits'              => $req->ben[$n],


                        ));
                    //insert data to activity table superadmin code
                    $activity = "Training schedule is  Edited and Updated with new Details  where  ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
                    DB::table('viewactivity')->insert($datas);
                    //end of superadmin code

                }
            }
            $n = $n + 1;
        }
        $models = DB::table('modenadisable')->First();
        return redirect('/user/hrtmgmt')->with('model', $models);
    }

    //delete job request by mr
    public function deletejob()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            // inserting data to activities table
            $name = DB::table('hr_job')->where('id', $id)->pluck('position_title');
            $activity = "A Job entry is Removed with Position Title " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
            DB::table('viewactivity')->insert($datas);

            DB::delete('delete from hr_job where id = :jid', ['jid' => $id]);
            // DB::delete('delete from objreport where Oid = :eid',['eid'=>$id]);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/hrjmgmt')->with('model', $models);
        }
    }



    //edit job by mr
    public function editjob_mr()
    {

        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            //    $vendor = Vendor::all();
            // $purpose = Purpose::all();
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = DB::table('hr_job')->where('id', $d)->first();
                }
                $n = $n + 1;
            }
        }

        $models = DB::table('modenadisable')->First();
        return view('/user/hr/editjob_mr')->withData($data)
            ->with('id', $id)
            ->with('model', $models);
    }
    public function editmrjob(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'title' => ['required','array'],
            'title.*' => ['required', 'string'],
            'pos' => ['required','array'],
            'pos.*' => ['required', 'string'],
            'name' => ['required','array'],
            'name.*' => ['required', 'string'],
        ], [
            'title.required' => 'The job code field is required. Please enter a job code.',
            'title.*.required' => 'Each job code value is required.',
            'pos.required' => 'The position title field is required. Please enter a position title.',
            'pos.*.required' => 'Each position title value is required.',
            'name.required' => 'The reports to field is required. Please enter a value for reports to.',
            'name.*.required' => 'Each reports to value is required.',
        ]);

        if ($validator->fails()) {
            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $m = count($req->pos);

            //  $empname = $req->input('empname');

            $pos =   $req->input('pos');
            $title = $req->input('title');
            $name = $req->input('name');
            $dur = $req->input('dur');
            //  $date=$req->input('date');
            //  $ben=$req->input('ben');
            $sno = $req->input('sno');
            $m = count($sno);
            $n = 0;
            foreach ($sno as $d) {
                if ($n < $m) {
                    $data2[] = DB::table('hr_job')->where('id', $d)->first();
                }
            }

            foreach ($data2 as $dat) {
                foreach ($sno as $s) {
                    if ($dat->id == $s && $n < $m) {
                        DB::table('hr_job')->where('id', $s)
                            ->update(array(
                                //  'emp_name'              =>$req->empname[$n],
                                'position_title'                      => $req->pos[$n],
                                'job_code'        => $req->title[$n],
                                'reports_to'              => $req->name[$n],
                                'prepared_on'        => $req->dur[$n],
                                //  'periods'              =>$req->date[$n],
                                //  'benefits'              =>$req->ben[$n],


                            ));
                        //insert data to activity table superadmin code
                        $activity = "Job schedule is  Edited and Updated with new Details  where  ID " . $s;
                        $date = Carbon::now()->toDateTimeString();
                        $date = Carbon::parse($date);
                        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
                        DB::table('viewactivity')->insert($datas);
                        //end of superadmin code

                    }
                }
                $n = $n + 1;
            }
            $models = DB::table('modenadisable')->First();
            return redirect('/user/hrjmgmt')->with('model', $models);
        }
    }

    public function jobsearch(Request $req)

    {
        //dd($req);
        $status = DB::table('vendor')->get();
        $departments = DB::table('departments')->get();
        $data = DB::table('hr_job')->get();
        //training
        $data1 = DB::table('hr_training')->get();

        //------------------search---------------
        $departmentname = DB::table('departments')->pluck('departmentname');
        $keyword = $req->input('keyword');

        $data = DB::table('hr_job')
            ->where(function ($query) use ($req) {
                $query

                    ->where('position_title', '=', $req->input('keyword'))
                    ->orWhere('reports_to', '=', $req->input('keyword'))
                    ->orWhere('dept', '=', $req->input('keyword'))
                    ->orWhere('job_code', '=', $req->input('keyword'))
                    ->orWhere('prepared_on', '=', $req->input('keyword'));
                //   ->orWhere('job_authorities','=',$req->input('keyword'))
                //   ->orWhere('accountabilities','=',$req->input('keyword'))
                //   ->orWhere('hse','=',$req->input('keyword'))
                //   ->orWhere('vendor_type','=',$req->input('keyword'))
                //   ->orWhere('eval','=',$req->input('keyword'))
                //   ->orWhere('status','=',$req->input('keyword'))


            })
            ->paginate(100)
            ->setpath('');


        $data->appends(array(
            'department' => $req->input('keyword')
        ));

        //dd($data);
        $models = DB::table('modenadisable')->First();
        return view('user.hr.register', compact('data', 'departments'))->with('model', $models);
    }




    //filter
    public function filtr(Request $req)

    {
        // $email=DB::table('riskreviewers')->pluck('email');
        // $departments =DB::table('departments')->get();
        // $mr = DB::table('representatives')->pluck('email');
        $purpose = null;
        $type = null;
        $purpose = $req->input('purpose');
        $type = $req->input('type');

        $data = DB::table('vendor')->where(function ($query) use ($req) {
            $query->where('vendor_group', '!=', 'null')
                ->where('vendor_group', '=', $req->input('purpose'))
                ->whereIn('status', ['Evaluated', 'Initial Evaluation', 'Due Evaluation', 'Near to Evaluation']);
        })
            ->orwhere(function ($query1) use ($req) {
                $query1->where('vendor_type', '!=', 'null')
                    ->where('vendor_type', '=', $req->input('rank'))
                    ->whereIn('status', ['Evaluated', 'Initial Evaluation', 'Due Evaluation', 'Near to Evaluation']);
            })
            ->paginate(75)
            ->setpath('');
        $data->appends(array(
            'vendor_group' => $req->input('purpose'),
            'vendor_type' => $req->input('type'),
        ));

        $e = Auth::user()->email;
        //   $riskowner=DB::select("select *  from risks");
        //   // dd( $riskowner);
        //   foreach($riskowner as $dat)
        //   {


        $data1 = DB::table('vendor')

            ->where('vendor_group', '=', $e)
            ->whereIn('status', ['Evaluated', 'Initial Evaluation', 'Due Evaluation', 'Near to Evaluation'])
            ->paginate(75);
        $dep = Departments::all();
        $models = DB::table('modenadisable')->First();
        return view('user.vendor.register', compact('data'))->with('model', $models);
    }

 //employeelist
     public function employeelist()
      {
         $departments = DB::table('departments')->get();
         $data = DB::table('employee_details')->get();
         $mr = DB::table('representatives')->pluck('email');
         $models = DB::table('modenadisable')->First();
         return view('user.hr.employeelist_register')->with('model', $models)->with('data',$data)
                                                     ->with('departments', $departments)->with('mr', $mr);

      }




}
