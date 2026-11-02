<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

use Auth;
use DB;
use App\User;
use App\Admin;
use App\Categories;
use App\Departments;
use App\Hml;
use App\Type;

use Form;
use App\users;

use DateTime;
use Session;
class MaintenanceController extends Controller
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


        public function addtotal()
        {

            $departments = DB::table('departments')->get();
            $users = DB::table('users')->get();
            $models=DB::table('modenadisable')->First();
            return view('user.maintenance.addtotal')->with('model',$models)->with('users',$users)->withDepartments($departments);
            }

        public function insertt(Request $req)
        {

            $validator = Validator::make($req->all(), [


            ]);

            if ($validator->fails()) {
                // echo "<script> alert('Error Resubmit the form')</script>";
                        //below code related to superadmin
            $models=DB::table('modenadisable')->First();
                return back()
                            ->withErrors($validator)
                            ->withInput()->with('model',$models);
                }
            else{
                $department = $req->input('department');

                $desc = $req->input('desc');
                $location = $req->input('location');
                $serial = $req->input('serial');
                $make = $req->input('make');
                $readfreq = $req->input('readfreq');
                if($req->input('question1')=='1')
                {
                    $pmstatus ="Time for PM";
                }
               else{
                $pmstatus ="NULL";
               }
                $freq = $req->input('freq');
                $pmby = $req->input('pmby');
                $lastdate = $req->input('lastdate');
                $schedule = $req->input('schedule');
                $condition = $req->input('condition');

                if($req->input('question2')=='1')
                {
                    $teststatus ="Time for Test";
                }
               else{
                $teststatus ="NULL";
               }
                $testfreq = $req->input('testfreq');
                $testby = $req->input('testby');
                $lasttestdate = $req->input('lasttestdate');
               $testschedule = $req->input('testschedule');
               $testcondition = $req->input('testcondition');
                $testno = $req->input('testno');

                date_default_timezone_set('Asia/Kuwait');
               $today = date( 'd-m-Y h:i:s A', time () );
                // $now = new DateTime();
                $types = $req->input('type');


               $postedby=Auth::user()->employeeid;
               $m=count($types);
            //    dd($m);
               $n=0;
               foreach($types as $d)
               {
                  if($n<$m)
                  {
                    $exist=DB::table('maintenance')->where('type', $d)->first();
                    if($exist)
                    {
                        $c=DB::table('maintenance')->where('type', $d)->pluck('count');
                        foreach($c as $count)
                        {
                           $count=$count+1;
                        }
                       }
                    else
                        {
                            $count="0";
                        }
                    $data  = array('count' => $count ,
                        'department'=>$department,
                    'desc'=>$desc,
                    'location'=>$location,
                   'serial'=>$serial,
                   'make'=>$make,
                   'readfreq'=>$readfreq,
                   'freq'=>$freq,
                   'testfreq'=>$testfreq,
                    'type'=>$d,
                  'addedby'=>$postedby,
                   'addedat'=>$today,
                   'testby'=>$testby,
                     'lasttestdate'=>$lasttestdate,
                    'testschedule'=>$testschedule,
                    'testcondition'=>$testcondition,
                    'teststatus'=>$teststatus,
                    'testno'=>$testno,
                    'pmby'=>$pmby,
                       'pmdate'=>$lastdate,
                       'schedule'=>$schedule,
                       'condition'=>$condition,
                       'pmstatus'=>$pmstatus,

                  );
                //  dd($data);
                  DB::table('maintenance')->insert($data);

                  $id=strtoupper(substr($d,0,3));
                  $fixed="1000" ;
                  $equid =$id. $fixed.$count;
                  $data3 = array('equid' => $equid );
                  DB::table('maintenance')->where('count',$count)
                                      ->where('type', $d)
                                  ->update($data3);


                                    //superadmin related codes
                            //inserting values to activity table
                            $activity="Inserted new equipment details with equipment id as ".$equid. "with Department ".$department;
                            $date=Carbon::now()->toDateTimeString();
                            $date=Carbon::parse($date);
                            $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'user');
                            DB::table('viewactivity')->insert($datas);
                //superadmin code ends

                  }
                  $n=$n+1;
               }




                Session::flash('msg',"Equipment details added successfully");

            $models=DB::table('modenadisable')->First();
            return redirect()->back()->with('model',$models);
            }

        }
        public function total()
        {
    //below is superadmin related codes
      $maintainance=DB::table('modpageload')->where('id',1)->get();
      if(sizeof($maintainance)==0){
          DB::table('modpageload')->insert(['id'=>1,'maintainance'=>1]);
      }

      else{
          $maintainance=DB::table('modpageload')->where('id',1)->First();
          $maintainance->maintainance= $maintainance->maintainance+1;
          DB::table('modpageload')->update(['maintainance'=> $maintainance->maintainance]);
      }
      //end of superadmin codes

            $data=DB::table('maintenance')->get();

            $mrs=DB::table('representatives')->pluck('email');
            $models=DB::table('modenadisable')->First();
            return view('user.maintenance.total')
                    ->with('model',$models)
                    ->withData($data)
                    ->withMrs($mrs);
            }

//maintenance req
public function req()
    {

        if (isset($_GET['val']))
        {
            $id=$_GET['val'];
            $id = explode(',',$_GET['val']);
            $m=count($id);
            $n=0;
            foreach($id as $d)
            {
               if($n<$m)
               {
                    $data[]=DB::table('maintenance')->where('id',$d)->first();
               }
               $n=$n+1;
            }
        }
        // $data=$data;
        $models=DB::table('modenadisable')->First();
        return view('user/maintenance/mrequest') ->withData($data)->with('id',$id)->with('model',$models);
    }


    public function mrequest(Request $req)
    {
            $m=count($req->department);


            $fault = $req->input('fault');
            $severity = $req->input('severity')  ;
            $priority = $req->input('priority')  ;
            $sno=$req->input('sno');
            // dd($sno);
            $m=count($sno);
            // dd($m);
            $n=0;
            foreach($sno as $d)
            {
                //   dd($sno);
               if($n<$m)
               {
                        // dd($sno);
                    $data2[]=DB::table('maintenance')->where('id',$d)->first();
                        //    dd($data2);
                        // dd($sno);
               }
                    //    dd($sno);
                $n=$n+1;
            }
                        // dd($sno);

            $n=0;

            foreach($data2 as $dat){
                    // dd($sno);
                    // dd($data2);
                foreach($sno as $s){
                    if($dat->id==$s && $n<$m){

                        $data=array(

                        'breakreq'=>'Yes',
                        'fault'=>$req->fault[$n],
                        'severity'=>$req->severity[$n],
                        'priority'=>$req->priority[$n],

                                    );
// dd($data);
                                DB::table('maintenance')
                                    ->where('id',$s)
                                    ->update($data);
     //insert data to activity table superadmin code
     $activity="Maintenance request submitted for equipment ID ".$s;
     $date=Carbon::now()->toDateTimeString();
     $date=Carbon::parse($date);
     $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'user');
     DB::table('viewactivity')->insert($datas);
      //end of superadmin code



                        }
                    }

                    $n=$n+1;
                }

          $models=DB::table('modenadisable')->First();
      return redirect('/user/maintenancemgmt')->with('model',$models);


    }




            public function pmfolder()
    {
        $m=['Mechanical','Electrical','Instrumentation'];
       $models=DB::table('modenadisable')->First();
        return view('user.maintenance.pmfolder')
        ->with('model',$models)
        ->with('m',$m);
       }
            public function pmplan($dept)
            {
               $data1=DB::table('maintenance')
               ->where('type',$dept)
                ->where('freq','!=',"NULL")
                ->get();

                $models=DB::table('modenadisable')->First();
                return view('user.maintenance.pmplan')
                        ->with('model',$models)
                        ->withData1($data1)
                        ->withDept($dept);
             }
             public function testfolder()
             {
                 $m=['Mechanical','Electrical','Instrumentation'];
                $models=DB::table('modenadisable')->First();
                 return view('user.maintenance.testfolder')
                 ->with('model',$models)
                 ->with('m',$m);
                }
                public function testplan($dept)
                {


                    $data2=DB::table('maintenance')
                    ->where('type',$dept)
                    ->where('testfreq','!=',"NULL")
                    ->get();
                    // dd($data);

                    $models=DB::table('modenadisable')->First();
                    return view('user.maintenance.testplan')
                            ->with('model',$models)
                            ->withData2($data2) ->withDept($dept);
                    }

                    public function breakfolder()
    {
        $m=['Mechanical','Electrical','Instrumentation'];
       $models=DB::table('modenadisable')->First();
        return view('user.maintenance.breakfolder')
        ->with('model',$models)  ->with('m',$m);
       }
                    public function break($dept)
                    {


                        $data3=DB::table('maintenance') ->where('type',$dept)->where('breakreq','!=',"No")->get();

                    $hods=DB::table('departments')->get();
                        $models=DB::table('modenadisable')->First();
                        return view('user.maintenance.break')
                                ->with('model',$models)
                                ->with('hods',$hods)
                                ->withData3($data3)->withDept($dept);
                        }


 public function review()
    {
        if (isset($_GET['val'])) {
            $id=$_GET['val'];
            $data=DB::table('maintenance')->where('id',$id)->get();


      $models=DB::table('modenadisable')->First();
      return view('user/maintenance/hodreview')->withData($data)->with('id',$id)->with('model',$models);

            }
    }
    public function hodreview(Request $req)
    {

                    $approvedby=Auth::user()->employeeid;
                    $reviewedby =Auth::user()->employeeid;

                $Rid=$req->input('posted');
                $riskname = $req->input('riskname');
                $riskdec = $req->input('riskdec');
                $riskdri = $req->input('riskdri');
                $riskscr = $req->input('riskscr');
                $risk_response = $req->input('risk_response');
                $mitigating_action = $req->input('mitigating_action');
                $risk_owner = $req->input('riskowner');
                $actionone= $req->input('actionone');
                $s=$req->input("action");
                $action_target=$actionone.$s;
               $impa = $req->input('impa');
                $like = $req->input('like');

            date_default_timezone_set('Asia/Kuwait');
            $now = date( 'd-m-Y h:i:s A', time () );
               $data  = array('Status'=>$Status,'riskname'=>$riskname,'riskdec'=>$riskdec,
                'riskdri'=>$riskdri,'riskscr'=>$riskscr,'risk_response'=>$risk_response,'mitigating_action'=>$mitigating_action,
                'risk_owner'=>$risk_owner,'action_target'=>$action_target,'action_status'=>'Active Risk',
                'impa'=>$impa,'like'=>$like,'rank'=>$rank1,'review_date'=>$now,
                'approved_by'=>$approvedby, 'reviewed_by'=>$reviewedby);

            //inserting values to activity table super admin codes
             $activity="Risk is Approved with action status as Active Risk  where Riskname is ".$riskname;
             $date=Carbon::now()->toDateTimeString();
             $date=Carbon::parse($date);
             $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'user');
             DB::table('viewactivity')->insert($datas);

                DB::table('risks')
                            ->where('Rid',$Rid)
                            ->update($data);

      $models=DB::table('modenadisable')->First();
      return redirect('/user/riskmgmt')->with('model',$models);

    }


//edit maintenance
public function editreq()
    {

        if (isset($_GET['val']))
        {
            $id=$_GET['val'];
            $id = explode(',',$_GET['val']);
            $m=count($id);
            $n=0;
            foreach($id as $d)
            {
               if($n<$m)
               {
                    $data[]=DB::table('maintenance')->where('id',$d)->first();
               }
               $n=$n+1;
            }
        }
        // $data=$data;
        $models=DB::table('modenadisable')->First();
        return view('user/maintenance/editrequest') ->withData($data)->with('id',$id)->with('model',$models);
    }


    public function editrequest(Request $req)
    {
            $m=count($req->department);

            // $type = $req->input('type');
            $location = $req->input('location')  ;
            $serial= $req->input('serial');
            $make = $req->input('make')  ;

            $sno=$req->input('sno');

            $m=count($sno);

            $n=0;
            foreach($sno as $d)
            {

               if($n<$m)
               {

                    $data2[]=DB::table('maintenance')->where('id',$d)->first();

               }

                $n=$n+1;
            }


            $n=0;

            foreach($data2 as $dat){

                foreach($sno as $s){
                    if($dat->id==$s && $n<$m){

                        $data=array(

                        // 'type'=>$req->type[$n],
                        'location'=>$req->location[$n],
                        'serial'=>$req->serial[$n],
                        'make'=>$req->make[$n],


                                    );
// dd($data);
                                DB::table('maintenance')
                                    ->where('id',$s)
                                    ->update($data);
     //insert data to activity table superadmin code
     $activity="Maintenance  is  Edited and Updated with new Details  where  ID ".$s;
     $date=Carbon::now()->toDateTimeString();
     $date=Carbon::parse($date);
     $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'user');
     DB::table('viewactivity')->insert($datas);
      //end of superadmin code



                        }
                    }

                    $n=$n+1;
                }

          $models=DB::table('modenadisable')->First();
      return redirect('/user/maintenancemgmt')->with('model',$models);


    }

  //mr delete
  public function delete()
  {
      if (isset($_GET['val']))
      {
          $id=$_GET['val'];

         //inserting data to activities table
         $name=DB::table('maintenance')->where('id',$id)->pluck('type');
         $activity="A maintenance entry is Removed with equipment type ".$name;
         $date=Carbon::now()->toDateTimeString();
         $date=Carbon::parse($date);
         $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'admin');
         DB::table('viewactivity')->insert($datas);

  DB::delete('delete from maintenance where id = :eid',['eid'=>$id]);

                          $models=DB::table('modenadisable')->First();
          return redirect('/user/maintenancemgmt')->with('model',$models);

      }

  }
}
