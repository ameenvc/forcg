<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;
use Session;

use Validator;
use Auth;
use DB;
Use App;
use App\Departments;
use App\Employeemedicalcheckuprecord;
use App\HealthCategory;
use App\HealthTreatment;
use App\IllnessNonWorkrelatedcases;
use App\InjuryWorkrelatedcases;
use App\EmployeeIllnessRecord;
use App\EmployeeIllnessTreatmentDetails;
use App\EmployeeIllnessNonWorkCasesDetails;
use App\EmployeeInjuryRecord;
use App\EmployeeInjuryTreatmentDetail;
use App\EmployeeInjuryWorkRelatedCases;
use App\EmployeeHealthInjuryNature;
use App\EmployeeHealthIllnessNature;
use App\Mail\OccupationalHealthInjuryUpdate;
use App\Exceptions\ErrorException;
use Form;
use DateTime;

// use App\Representative;
use function Opis\Closure\unserialize;
use function PHPSTORM_META\type;

use Hamcrest\Arrays\IsArray;
use Carbon\Carbon;
use ErrorException as GlobalErrorException;
use Exception as GlobalException;
use FFI\Exception as FFIException;
use PDF;

class OccupationalHealth extends Controller
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
    public function index(){
         //below is superadmin related codes
      $occhealth=DB::table('modpageload')->where('id',1)->get();
      if(sizeof($occhealth)==0){
          DB::table('modpageload')->insert(['id'=>1,'occhealth'=>1]);
      }

      else{
          $occhealth=DB::table('modpageload')->where('id',1)->First();
          $occhealth->occhealth= $occhealth->occhealth+1;
          DB::table('modpageload')->update(['occhealth'=> $occhealth->occhealth]);
      }
      //end of superadmin codes

        $eid=Auth::user()->employeeid;
        $hsemgr=DB::table('hses')->where('email',Auth::user()->email)->get();
        $mrep=DB::table('representatives')->where('email',Auth::user()->email)->get();
        $doctor=DB::table('company_doctors')->where('email',Auth::user()->email)->get();
        $mr = DB::table('representatives')->pluck('email');
        $models=DB::table('modenadisable')->First();
        $departments = DB::table('departments')->get();
        //if(count($hsemgr)>0 || count($mrep)>0 || count($doctor)>0)
        if(count($doctor)>0)
          {
            $empdetails=DB::table('employee_details')->get();
          }
        else
        {
          $empdetails=DB::table('employee_details')->where('emp_number',$eid)->get();
        }
        return view('user.occupationhealth.register')
                                    ->with('doct',$doctor)
                                    ->with('mr',$mr)
                                    ->with('departments',$departments)
                                    ->with('model',$models)
                                    ->with('empdetails',$empdetails);
    }

    public function addform(){
      $empdetails=DB::table('employee_details')->get();
      $models=DB::table('modenadisable')->First();
      $doctor=DB::table('company_doctors')->where('email',Auth::user()->email)->get();
      if(count($doctor)>0)
      {
        return view('user.occupationhealth.addform')->with('empdetails',$empdetails)->with('model',$models);
      }
      else
      {
        return redirect('/user/occupationhealth/register')->with('accessmsg', 'Access Denied ! You do not have permission to access this form');
      }


    }
    public function addrecord(Request $request){
        $mdate=Carbon::parse($request->medicaldate);
        if($request->department==NULL)
        {
          $emp_dep = '-';
        }
        else
        {
          $emp_dep = $request->department;
        }

        if($request->position==NULL)
        {
          $emp_post = '-';
        }
        else
        {
          $emp_post = $request->position;
        }
        DB::table('employee_health_record')->insert(['e_number'=>$request->number,'e_name'=>$request->name,'department'=>$emp_dep,'position'=>$emp_post,'date'=>$mdate->format('d-m-Y'),'tobacco'=>$request->tobacco,'bp_diastolic'=>$request->bloodpd,'bp_systolic'=>$request->bps,'bs_level'=>$request->bsl,'remarks'=>$request->remark]);

        $rcntdata = DB::table('employee_health_record')->latest('id')->first();
        $rcntid = $rcntdata->id;

        $data =array(
                  'emp_number' => $request->number,
                  'date_of_medical' => $mdate->format('Y-m-d'),
                  'tobacco'=>$request->tobacco,
                  'bp_diastolic'=>$request->bloodpd,
                  'bp_systolic'=>$request->bps,
                  'bs_level'=>$request->bsl,
                  'remarks'=>$request->remark,
                  'health_record_id'=>$rcntid
                  );

        Employeemedicalcheckuprecord::create($data);

        Session::flash('msg','Employee Health Record Addded Successfully');
        return redirect()->back();
    }

    public function showinjuryhistory(){
      $eid=Auth::user()->employeeid;
      $hsemgr=DB::table('hses')->where('email',Auth::user()->email)->get();
      $mrep=DB::table('representatives')->where('email',Auth::user()->email)->get();
      $doctor=DB::table('company_doctors')->where('email',Auth::user()->email)->get();

      $witnesses=DB::table('employee_injury_witness')->get();
      $mr = DB::table('representatives')->pluck('email');
      $models=DB::table('modenadisable')->First();
      $departments = Departments::all();
      $natureinjury = EmployeeHealthInjuryNature::all();

      $doctors=DB::table('company_doctors')->get();
      //if(count($hsemgr)>0 || count($mrep)>0 || count($doctor)>0)
      if(count($doctor)>0)
          {
            //$empinjuryrecord=DB::table('employee_injury_record')->latest('created_at')->get();
            $empinjuryrecord= EmployeeInjuryRecord::where('injured_person_type',NULL)->latest('created_at')->get();
          }
      else{
            //$empinjuryrecord=DB::table('employee_injury_record')->where('e_number',$eid)->latest('created_at')->get();
            $empinjuryrecord= EmployeeInjuryRecord::where('e_number',$eid)->latest('created_at')->get();
        }
      return view('user.occupationhealth.injuryrecord')
                                       ->with('doctors',$doctors)->with('witnesses',$witnesses)
                                       ->with('mr',$mr)->with('model',$models)
                                       ->with('departments',$departments)->with('emprecord',$empinjuryrecord)->with('natureinjury',$natureinjury);
    }


    public function addinjuryform($empnum)
    {
      $empid=Auth::user()->employeeid;
      $departments=DB::table('departments')->get();
      $healthcategory = HealthCategory::all();
      $treatment = HealthTreatment::all();
      $injurywrkcase = InjuryWorkrelatedcases::all();
      $empdetails=DB::table('employee_details')->get();
      $models=DB::table('modenadisable')->First();
      $emplydetails=DB::table('employee_details')->where('emp_number',$empid)->First();

      $empdeptname =isset($emplydetails->emp_department) && $emplydetails->emp_department!='-' ? DB::table('departments')->where('id',$emplydetails->emp_department)->value('departmentname') : '-';

      $injuredempdet = DB::table('employee_details')->where('emp_number',$empnum)->First();
      $injempdeptname =DB::table('departments')->where('id',$injuredempdet->emp_department)->value('departmentname');

      $status = ['Cured','In Progress','Fatality','In Treatment'];
      $injuredbodyprt = [
                          'Eyes','Nose','Throat','Head/Skull','Mouth/Lips','Tongue','Teeth','Face',
                          'Neck','Shoulder','Chest','Stomach','Upper/Lower Back','Arms','Forearms','Elbow',
                          'Hands','Fingers','Buttocks','Groin','Genital','Anal','Thigh','Leg',
                          'Knee','Calf','Foot','Heel','Ankle','Sole','Toes/ToeNails'
                        ];


        $natureinjury = EmployeeHealthInjuryNature::all();


      return view('user.occupationhealth.injuryform')
                                                     ->with('model',$models)
                                                     ->with('empdetails',$empdetails)
                                                     ->with('depts',$departments)
                                                     ->with('healthcategory',$healthcategory)
                                                     ->with('treatment',$treatment)
                                                     ->with('status',$status)
                                                     ->with('injuredbodyprt',$injuredbodyprt)
                                                     ->with('injurywrkcase',$injurywrkcase)
                                                     ->with('injuredemp',$injuredempdet)
                                                     ->with('injempdeptname',$injempdeptname)
                                                     ->with('empdeptname',$empdeptname)
                                                     ->with('natureinjury',$natureinjury)
                                                     ->with('emplydetails',$emplydetails);
    }

    public function selectinjurytype(Request $request)
    {
      $inj_type = $request->input('inj_type');
      $natureinjury = EmployeeHealthInjuryNature::where('injury_type',$inj_type)->get();

        return response()->json([
          'status'=>200,
          'data'=>$natureinjury
      ]);
    }

    public function addinjuryrecord(Request $request){
      $witness='No';
      $returned_to_work = "no";
      $doinj=Carbon::parse($request->injdate);
      if($request->dob==NULL)
      {
        $injempdob = '-';
      }
      else
      {
        $injempdob = $request->dob;
      }

      if($request->department==NULL)
      {
         $injempdept = '-';
      }
     else
      {
        $getinjdepid = Departments::where('departmentname',$request->department)->value('id');
        $injempdept = $getinjdepid;
      }

     if($request->position==NULL)
      {
         $injemppost = '-';
      }
     else
      {
        $injemppost = $request->position;
      }


      if($request->createrposition==NULL)
      {
         $creater_post = '-';
      }
     else
      {
        $creater_post = $request->createrposition;
      }

      if($request->createrdepartment==NULL || $request->createrdepartment =='-')
      {
         $creater_dept = '-';
      }
     else
      {
        $getcretdepid = Departments::where('departmentname',$request->createrdepartment)->value('id');
        $creater_dept = $getcretdepid;
      }

      if($request->injury_type == 'work_related')
      {
        $work_related = implode(',',$request->work_injury);
        //$nature_injury = implode(',',$request->work_nature);
      }
      else
      {
        $work_related = '-';
        //$nature_injury = implode(',',$request->non_work_nature);
      }


      $bodyinjured = implode(',',$request->body_injured);
      if($request->body_injured_oth!=NULL)
      {
        $body_inj_other = $request->body_injured_oth;
      }
      else
      {
        $body_inj_other = '-';
      }

      if($request->treatment == 'yes')
      {
        $treatmentdetails = implode(',',$request->treatments);
      }
      else
      {
        $treatmentdetails = '-';
      }



      $year = date('Y');

      $injdata = array(
                    'category_id'=>$request->category,
                    'e_number'=>$request->number,
                    'e_name'=>$request->name,
                    'dob'=>$injempdob,
                    'department'=>$injempdept,
                    'position'=>$injemppost,
                    'dateofinjury'=>$doinj->format('d-m-Y'),
                    'timeofinjury'=>$request->time,
                    'injury_type'=>$request->injury_type,
                    'workrelated_injury'=>$work_related,
                    'workrelated_injury_other'=>$request->workinjury_oth,
                    'nonwork_related_injury'=>$request->nonwork_injury,
                    //'natureofinjury'=>$nature_injury,
                    'natureofinjury'=>'-',
                    'bodily_location'=>$bodyinjured,
                    'body_injured_other'=>$request->body_injured_oth,
                    'location'=>$request->location,
                    'causeofinjury'=>$request->cause,
                    'things_involved'=>$request->thing,
                    'things_involved_details'=>$request->things,
                    'witness'=>$witness,
                    'reported_to_supervisor'=>$request->supervisor,
                    'treatment_provided'=>$request->treatment,
                    'treatment_details'=>$treatmentdetails,
                    'treatmentdetails_other'=>$request->treatment_oth,
                    'injured_returnto_work'=>$returned_to_work,
                    'creator_name'=>Auth::user()->name,
                    'creator_email'=>Auth::user()->email,
                    'creator_position'=>$creater_post,
                    'creator_department'=>$creater_dept,
                    'created_date'=>$request->createddate,
                    'status'=>$request->status,
                    'injured_bp_diastolic'=>$request->bloodpd,
                    'injured_bp_systolic'=>$request->bps,
                    'injured_bs_level'=>$request->bsl,
                    'remarks'=>$request->remark,
                    'pulse'=>$request->pulse,
                    'temperature'=>$request->temp,
                    'respiratory_rate'=>$request->respiratory_rate,
                    'weight'=>$request->weight,
                    'height'=>$request->height,
                    'year'=>$year,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                  );

      DB::table('employee_injury_record')->insert($injdata);

      //generating report number
          $data = DB::table('employee_injury_record')->latest('id')->first();
          $recentid = $data->id;
          $year = date('y');
          $reportnum = 'INJ'.$recentid.$year;
          DB::table('employee_injury_record')->where('id',$recentid)->update(['report_num' =>$reportnum]);

          //inserting witness data to employee_injury_witness table
          if(count($request->doc)>0)
          {
            for ($i=0;$i<count($request->doc);$i++)
             {
                if($request->doc[$i]['name']!=null && $request->doc[$i]['number']!=null)
                {
                  DB::table('employee_injury_witness')->insert([
                                                          'created_date'=>$request->createddate,
                                                          'injured_per_enumber'=>$request->number,
                                                          'name'=>$request->doc[$i]['name'],
                                                          'number'=>$request->doc[$i]['number'],
                                                          'injury_id'=>$recentid,
                                                          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                        ]);
                  $witness="Yes";
                }
              }
          }
          DB::table('employee_injury_record')->where('id',$recentid)->update(['witness'=>$witness]);

       //adding data to employee injury work related cases table
      if($request->injury_type == 'work_related')
      {
        foreach($request->work_injury as $itm)
        {
          $cases = new EmployeeInjuryWorkRelatedCases;
          $cases->injury_id = $itm;
          $cases->injury_record_id = $recentid;
          $cases->employee_num = $request->number;
          $cases->dateofinjury = $request->injdate;
          $cases->save();
        }
      }

     //adding to treatmentinjury details table
     if($request->treatment == 'yes')
     {
       foreach($request->treatments as $treat)
       {
          $treatments = new EmployeeInjuryTreatmentDetail;
          $treatments->treatment_id = $treat;
          $treatments->injury_record_id = $recentid;
          $treatments->save();

       }
     }

    //adding data to employee_injury_nature_details table
    if($request->injury_type == 'work_related')
    {
      foreach($request->work_nature as $worknat)
      {
        DB::table('employee_injury_nature_details')->insert([
                                                      'injury_id'=>$recentid,
                                                      'injury_type'=>'work_related',
                                                      'natureofinjury'=>$worknat,
                                                      'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                      'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    ]);
      }
    }
    else
    {
      foreach($request->non_work_nature as $nonworknat)
      {
        DB::table('employee_injury_nature_details')->insert([
                                                      'injury_id'=>$recentid,
                                                      'injury_type'=>'non_work_related',
                                                      'natureofinjury'=>$nonworknat,
                                                      'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                      'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    ]);
      }

    }




      Session::flash('msg','Employee injury record added successfully');
      return redirect()->back();
    }

    public function illnessrecord(){
      $eid=Auth::user()->employeeid;
      $hsemgr=DB::table('hses')->where('email',Auth::user()->email)->get();
      $mrep=DB::table('representatives')->where('email',Auth::user()->email)->get();
      $doctor=DB::table('company_doctors')->where('email',Auth::user()->email)->get();

      $models=DB::table('modenadisable')->First();
      $mr = DB::table('representatives')->pluck('email');
      $witnesses=DB::table('employee_illness_witness')->get();
      $departments = Departments::all();
      $natureillness = EmployeeHealthIllnessNature::all();

      $doctors=DB::table('company_doctors')->get();
     // if(count($hsemgr)>0 || count($mrep)>0 || count($doctor)>0)
     if(count($doctor)>0)
      {
        //$illnessrecord=DB::table('employee_illness_record')->latest('created_at')->get();
        $illnessrecord=EmployeeIllnessRecord::where('illed_person_type',NULL)->latest('created_at')->get();
      }
      else{
        //$illnessrecord=DB::table('employee_illness_record')->where('e_number',$eid)->get();
        $illnessrecord=EmployeeIllnessRecord::where('e_number',$eid)->get();
      }
      return view('user.occupationhealth.illnessrecord')
                                      ->with('doctors',$doctors)->with('witnesses',$witnesses)
                                      ->with('emprecord',$illnessrecord)->with('model',$models)
                                      ->with('departments',$departments)->with('mr',$mr)->with('natureillness',$natureillness);
    }


    public function addillnessform($empnum)
    {
      $empid=Auth::user()->employeeid;
      $departments=DB::table('departments')->get();
      $healthcategory = HealthCategory::all();
      $treatment = HealthTreatment::all();
      $illnessnonwork = IllnessNonWorkrelatedcases::all();
      $empdetails=DB::table('employee_details')->get();
      $models=DB::table('modenadisable')->First();
      $emplydetails=DB::table('employee_details')->where('emp_number',$empid)->First();
      if($emplydetails->emp_department!='-')
      {
        $empdeptname =DB::table('departments')->where('id',$emplydetails->emp_department)->value('departmentname');
      }
      else
      {
        $empdeptname = '-';
      }
      $illnessempdet = DB::table('employee_details')->where('emp_number',$empnum)->First();
      $illempdeptname =DB::table('departments')->where('id',$illnessempdet->emp_department)->value('departmentname');

      $status = ['Cured','In Progress','Fatality','In Treatment'];
      $natureillness = EmployeeHealthIllnessNature::all();

      return view('user.occupationhealth.illnessform')->with('model',$models)
                                                      ->with('healthcategory',$healthcategory)
                                                      ->with('treatment',$treatment)
                                                      ->with('illnessnonwork',$illnessnonwork)
                                                      ->with('status',$status)
                                                      ->with('empdetails',$empdetails)
                                                      ->with('empdeptname',$empdeptname)
                                                      ->with('depts',$departments)
                                                      ->with('illnessemp',$illnessempdet)
                                                      ->with('illempdeptname',$illempdeptname)
                                                      ->with('natureillness',$natureillness)
                                                      ->with('emplydetails',$emplydetails);
    }

    public function selectillnesstype(Request $request)
    {
      $ill_type = $request->input('ill_type');
      $natureillness = EmployeeHealthIllnessNature::where('illness_type',$ill_type)->get();

        return response()->json([
          'status'=>200,
          'data'=>$natureillness
      ]);
    }


    public function addillnessrecordconf(Request $request){
      $contacts=[];
      $contact="";
      $witness='No';

      if(count($request->contact)>0){
        for ($i=0;$i<count($request->contact);$i++){
          if($request->contact[$i]['name']!=null && $request->contact[$i]['eid']!=null){
            array_push($contacts,$request->contact[$i]['name'].' ('.$request->contact[$i]['eid'].')');
        }
      }
      }

      if(count($contacts)>0){
        $contact=implode(',',$contacts);
      }

      $doinj=Carbon::parse($request->injdate);

      if($request->dob==NULL)
      {
        $illempdob = '-';
      }
      else
      {
        $illempdob = $request->dob;
      }

      if($request->department==NULL)
      {
         $illempdept = '-';
      }
     else
      {
        $getilldepid = Departments::where('departmentname',$request->department)->value('id');
        $illempdept = $getilldepid;
      }

     if($request->position==NULL)
      {
         $illemppost = '-';
      }
     else
      {
        $illemppost = $request->position;
      }


      if($request->createrposition==NULL)
      {
         $creater_post = '-';
      }
     else
      {
        $creater_post = $request->createrposition;
      }

      if($request->createrdepartment==NULL || $request->createrdepartment=='-')
      {
         $creater_dept = '-';
      }
     else
      {
        $getcretdepid = Departments::where('departmentname',$request->createrdepartment)->value('id');
        $creater_dept = $getcretdepid;
      }


      if($request->ill_type == 'non_work_related')
      {
        $nonwork_related = implode(',',$request->nonwork_ill);
        $nature_illness = implode(',',$request->non_work_nature);
      }
      else
      {
        $nonwork_related = '-';
        $nature_illness = implode(',',$request->work_nature);
      }


      if($request->treatment == 'yes')
      {
        $treatmentdetails = implode(',',$request->treatments);
      }
      else
      {
        $treatmentdetails = '-';
      }

      $year = date('Y');

      $illdata = array(
                    'e_number'=>$request->number,
                    'e_name'=>$request->name,
                    'category_id'=>$request->category,
                    'dob'=>$illempdob,
                    'department'=>$illempdept,
                    'position'=>$illemppost,
                    'dateofillness'=>$doinj->format('d-m-Y'),
                    'timeofillness'=>$request->time,
                    'ill_type'=>$request->ill_type,
                    'workrelated_illness'=>$request->work_ill,
                    'nonworkrelated_illness'=>$nonwork_related,
                    'nonworkrelated_illness_other'=>$request->nonwork_oth,
                    //'natureofillness'=>$request->nature,
                    //'natureofillness'=>$nature_illness,
                    'natureofillness'=>'-',
                    'location'=>$request->location,
                    'causeofillness'=>$request->cause,
                    'symptoms'=>$request->symptom,
                    'things_involved'=>$request->thing,
                    'things_involved_details'=>$request->things,
                    'pandamic'=>$request->pandemic,
                    'pandamic_details'=>$request->pandemics,
                    'pandemic_contacts'=>$contact,
                    'witness'=>$witness,
                    'reported_to_supervisor'=>$request->supervisor,
                    'treatment_provided'=>$request->treatment,
                    'treatment_details'=>$treatmentdetails,
                    'treatmentdetails_other'=>$request->treatment_oth,
                    'illed_returnto_work'=>$request->returnwork,
                    'illed_returnto_work_details'=>$request->returnworks,
                    'creator_name'=>Auth::user()->name,
                    'creator_email'=>Auth::user()->email,
                    'creator_position'=>$creater_post,
                    'creator_department'=>$creater_dept,
                    'created_date'=>$request->createddate,
                    'status'=>$request->status,
                    'medical_leaves'=>$request->leaves,
                    'illed_bp_diastolic'=>$request->bloodpd,
                    'illed_bp_systolic'=>$request->bps,
                    'illed_bs_level'=>$request->bsl,
                    'remarks'=>$request->remark,
                    'pulse'=>$request->pulse,
                    'temperature'=>$request->temp,
                    'respiratory_rate'=>$request->respiratory_rate,
                    'weight'=>$request->weight,
                    'height'=>$request->height,
                    'year'=>$year,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

      );

      DB::table('employee_illness_record')->insert($illdata);

      //generating report number
        $data = DB::table('employee_illness_record')->latest('id')->first();
        $recentid = $data->id;
        $year = date('y');
        $reportnum = 'ILL'.$recentid.$year;
        DB::table('employee_illness_record')->where('id',$recentid)->update(['report_num' =>$reportnum]);

     //adding witness to employee_illness_witness table
        if(count($request->doc)>0)
        {
          for ($i=0;$i<count($request->doc);$i++)
          {
            if($request->doc[$i]['name']!=null && $request->doc[$i]['number']!=null)
            {
              DB::table('employee_illness_witness')->insert([
                                                      'created_date'=>$request->createddate,
                                                      'illed_per_enumber'=>$request->number,
                                                      'name'=>$request->doc[$i]['name'],
                                                      'number'=>$request->doc[$i]['number'],
                                                      'illness_id'=>$recentid,
                                                      'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                      'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    ]);
              $witness="Yes";
            }
          }
        }
        DB::table('employee_illness_record')->where('id',$recentid)->update(['witness' =>$witness]);

      //adding data to employee illness nonwork cases table
      if($request->ill_type == 'non_work_related')
      {
        foreach($request->nonwork_ill as $itm)
        {
          $cases = new EmployeeIllnessNonWorkCasesDetails;
          $cases->illness_id = $itm;
          $cases->illness_record_id = $recentid;
          $cases->employee_num = $request->number;
          $cases->dateofillness = $request->injdate;
          $cases->save();
         /* DB::table('illness_employee_test')->insert([
                                                      'illness_id'=>$itm,
                                                      'illness_record_id'=>$recentid,

                                                    ]);*/
        }
      }


//adding to treatmentillness details table
      if($request->treatment == 'yes')
      {
        foreach($request->treatments as $treat)
        {
           $treatments = new EmployeeIllnessTreatmentDetails;
           $treatments->treatment_id = $treat;
           $treatments->illness_record_id = $recentid;
           $treatments->save();

        }
      }

    //adding data to employee_illness_nature_details table
    if($request->ill_type == 'non_work_related')
    {
      foreach($request->non_work_nature as $nonworknat)
      {
        DB::table('employee_illness_nature_details')->insert([
                                                      'illness_id'=>$recentid,
                                                      'illness_type'=>'non_work_related',
                                                      'natureofillness'=>$nonworknat,
                                                      'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                      'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    ]);
      }
    }
    else
    {
      foreach($request->work_nature as $worknat)
      {
        DB::table('employee_illness_nature_details')->insert([
                                                      'illness_id'=>$recentid,
                                                      'illness_type'=>'work_related',
                                                      'natureofillness'=>$worknat,
                                                      'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                      'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    ]);
      }

    }




  //adding data to employee illness pandemic details table
    if($request->pandemic=='yes' && count($request->contact)>0)
    {
      for ($i=0;$i<count($request->contact);$i++)
      {
        if($request->contact[$i]['name']!=null && $request->contact[$i]['eid']!=null)
        {
          DB::table('employee_illness_pandemic_contacts')->insert([
                                                          'illness_record_id' => $recentid,
                                                          'contact_emp_name' => $request->contact[$i]['name'],
                                                          'contact_emp_number' => $request->contact[$i]['eid'],
                                                          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                        ]);
        }
      }
    }

      Session::flash('msg','Employee illness record added successfully');
      return redirect()->back();
    }

    public function updateinjuryrecord($id){
      $record=DB::table('employee_injury_record')->where('id',$id)->First();
      $creat_dept = $record->creator_department;
      if($creat_dept == NULL || $creat_dept == '-')
        {
          $creat_dep = '-';
        }
      else
       {
          $creat_dep = Departments::where('id',$creat_dept)->value('departmentname');
       }

      $inj_dep = Departments::where('id',$record->department)->value('departmentname');
      $witnesses=DB::table('employee_injury_witness')
                                  ->where('injury_id',$id)
                                  ->get();
      $healthcategory = HealthCategory::all();
      $treatment = HealthTreatment::all();
      $injurywrkcase = InjuryWorkrelatedcases::all();
      $injuredbodyprt = [
                          'Eyes','Nose','Throat','Head/Skull','Mouth/Lips','Tongue','Teeth','Face',
                          'Neck','Shoulder','Chest','Stomach','Upper/Lower Back','Arms','Forearms','Elbow',
                          'Hands','Fingers','Buttocks','Groin','Genital','Anal','Thigh','Leg',
                          'Knee','Calf','Foot','Heel','Ankle','Sole','Toes/ToeNails'
                        ];
      $status = ['Cured','In Progress','Fatality','In Treatment'];
      $natureinjury = EmployeeHealthInjuryNature::all();

      $models=DB::table('modenadisable')->First();
      return view('user.occupationhealth.updateinjury')
                                    ->with('witnesses',$witnesses)
                                    ->with('model',$models)
                                    ->with('creat_dep',$creat_dep)
                                    ->with('healthcategory',$healthcategory)
                                    ->with('treatment',$treatment)
                                    ->with('injurywrkcase',$injurywrkcase)
                                    ->with('inj_dep',$inj_dep)
                                    ->with('injuredbodyprt',$injuredbodyprt)
                                    ->with('status',$status)
                                    ->with('natureinjury',$natureinjury)
                                    ->with('record',$record);
    }


    public function updateconfinjuryrecord(Request $request){
          $today=Carbon::now()->toDateTimeString();
          $today=Carbon::parse($today);

          $returnwork = $request->returnwork;
          $returnworks_details= $request->returnworks;
          $leaves = $request->leaves;
          $db_status = $request->db_status;
          $bodyinjured = implode(',',$request->body_injured);
          if($request->body_injured_oth!=NULL)
          {
            $body_inj_other = $request->body_injured_oth;
          }
          else
          {
            $body_inj_other = '-';
          }

          if($request->injury_type == 'work_related')
          {
            $work_related = implode(',',$request->work_injury);
            $nonwork_related_injury = NULL;
            if(is_array($request->work_nature))
               {
                 //$nature_injury = implode(',',$request->work_nature);
                 $nature_injury = '-';
               }
             else
              {
                $nature_injury = $request->work_nature;
              }

          }
          else
          {
            $work_related = '-';
            $nonwork_related_injury = $request->nonwork_injury;
            if(is_array($request->non_work_nature))
              {
                //$nature_injury = implode(',',$request->non_work_nature);
                $nature_injury = '-';
              }
            else
             {
               $nature_injury = $request->non_work_nature;
             }
          }

        $work_arr = explode(',',$work_related);
        if($request->injury_type == 'work_related' && collect($work_arr)->contains('other'))
          {
            $work_related_injury_other = $request->workinjury_oth;
          }
        else
         {
          $work_related_injury_other = NULL;
         }





        if($request->thing=='yes')
         {
           $things_involved_details = $request->things;
         }
        else
         {
          $things_involved_details = NULL;
         }

        if($request->treatment == 'yes')
        {
          $treatmentdetails = implode(',',$request->treatments);
        }
        else
        {
          $treatmentdetails = '-';
        }


        $treatment_arr = explode(',',$treatmentdetails);
        if($request->treatment == 'yes' && collect($treatment_arr)->contains('other'))
        {
          $treatmentdetails_other = $request->treatment_oth;
        }
       else
        {
          $treatmentdetails_other = NULL;
        }

        $doinj=Carbon::parse($request->injdate);


          if($returnwork!="" || $returnworks_details!="" || $leaves!="")
          {
            DB::table('employee_injury_record')->where('id',$request->recid)
                                              ->update([
                                                          'injured_returnto_work'=>$returnwork,
                                                          'injured_returnto_work_details'=>$returnworks_details,
                                                          'medical_leaves'=>$leaves,
                                                          'status'=>$request->status
                                                        ]);
          }

        else
        {
          DB::table('employee_injury_record')->where('id',$request->recid) ->update(['status'=>$request->status]);
        }

          $emp=DB::table('employee_injury_record')->where('id',$request->recid)->First();
          DB::table('employee_injury_record')->where('id',$request->recid)
                                                          ->update([
                                                                    'category_id'=>$request->category,
                                                                    'dateofinjury'=>$doinj->format('d-m-Y'),
                                                                    'timeofinjury'=>$request->injtime,
                                                                    'injury_type'=>$request->injury_type,
                                                                    'workrelated_injury'=>$work_related,
                                                                    //'workrelated_injury_other'=>$request->workinjury_oth,
                                                                    'workrelated_injury_other'=> $work_related_injury_other,
                                                                    //'nonwork_related_injury'=>$request->nonwork_injury,
                                                                    'nonwork_related_injury'=>$nonwork_related_injury,
                                                                    //'natureofinjury'=>$request->nature,
                                                                    'natureofinjury'=>$nature_injury,
                                                                    'bodily_location'=>$bodyinjured,
                                                                    'body_injured_other'=>$request->body_injured_oth,
                                                                    'location'=>$request->location,
                                                                    'causeofinjury'=>$request->cause,
                                                                    'things_involved'=>$request->thing,
                                                                    //'things_involved_details'=>$request->things,
                                                                    'things_involved_details'=>$things_involved_details,
                                                                    'reported_to_supervisor'=>$request->supervisor,
                                                                    'treatment_provided'=>$request->treatment,
                                                                    'treatment_details'=>$treatmentdetails,
                                                                    //'treatmentdetails_other'=>$request->treatment_oth,
                                                                    'treatmentdetails_other'=>$treatmentdetails_other,
                                                                    'injured_bp_diastolic'=>$request->bloodpd,
                                                                    'injured_bp_systolic'=>$request->bps,
                                                                    'injured_bs_level'=>$request->bsl,
                                                                    'remarks'=>$request->remark,
                                                                    'pulse'=>$request->pulse,
                                                                    'temperature'=>$request->temp,
                                                                    'respiratory_rate'=>$request->respiratory_rate,
                                                                    'weight'=>$request->weight,
                                                                    'height'=>$request->height,
                                                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                                                                   ]);


       //updating data in employee injury work related cases table
         if($request->injury_type == 'work_related')
              {
                EmployeeInjuryWorkRelatedCases::where('injury_record_id',$request->recid)->delete();
                foreach($request->work_injury as $itm)
                {
                  $cases = new EmployeeInjuryWorkRelatedCases;
                  $cases->injury_id = $itm;
                  $cases->injury_record_id = $request->recid;
                  $cases->employee_num = $request->number;
                  $cases->dateofinjury = $request->injdate;
                  $cases->save();
                }
              }


        //adding data to treatmentinjury details table
          if($request->treatment == 'yes')
          {
            EmployeeInjuryTreatmentDetail::where('injury_record_id',$request->recid)->delete();
            foreach($request->treatments as $treat)
            {
                $treatments = new EmployeeInjuryTreatmentDetail;
                $treatments->treatment_id = $treat;
                $treatments->injury_record_id = $request->recid;
                $treatments->save();
            }
          }

        //updating data to employee_injury_nature_details table
          if($request->injury_type == 'work_related' && is_array($request->work_nature))
          {
            DB::table('employee_injury_nature_details')->where('injury_id',$request->recid)->delete();
            foreach($request->work_nature as $worknat)
            {
              DB::table('employee_injury_nature_details')->insert([
                                                            'injury_id'=>$request->recid,
                                                            'injury_type'=>'work_related',
                                                            'natureofinjury'=>$worknat,
                                                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                          ]);
            }
          }
          elseif($request->injury_type == 'non_work_related' && is_array($request->non_work_nature))
          {
            DB::table('employee_injury_nature_details')->where('injury_id',$request->recid)->delete();
            foreach($request->non_work_nature as $nonworknat)
            {
              DB::table('employee_injury_nature_details')->insert([
                                                            'injury_id'=>$request->recid,
                                                            'injury_type'=>'non_work_related',
                                                            'natureofinjury'=>$nonworknat,
                                                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                          ]);
            }

          }

       //Sending  mail to doctor when injury status changed from cured
          if($db_status=="Cured" && $request->status!="Cured")
          {
            $report_num = $request->report_num;
            $doctor=DB::table('company_doctors')->pluck('email');
            $data=array(
                      'line1'=>Lang::get('Injury Status with Report Number - '.$report_num.'  is changed from Cured to  '.$request->status),
                      );
            foreach($doctor as $doc)
            {
              Mail::to($doc)->send(new OccupationalHealthInjuryUpdate($data));
            }

          }

          $tobbacco=DB::table('employee_health_record')->where('e_number',$emp->e_number)->First();

            Session::flash('msg','Employee Injury Record Updated Successfully');
            return redirect()->back();
      }

    public function updateillnessrecord($id){
      $record=DB::table('employee_illness_record')->where('id',$id)->First();
      $creat_dept = $record->creator_department;
      if($creat_dept == NULL || $creat_dept == '-')
        {
          $creat_dep = '-';
        }
      else
       {
          $creat_dep = Departments::where('id',$creat_dept)->value('departmentname');
       }

      $ill_dep = Departments::where('id',$record->department)->value('departmentname');
      $witnesses=DB::table('employee_illness_witness')
                                  ->where('illness_id',$id)
                                  ->get();

      $healthcategory = HealthCategory::all();
      $treatment = HealthTreatment::all();
      $illnessnonwork = IllnessNonWorkrelatedcases::all();
      $empdetails=DB::table('employee_details')->get();
      $status = ['Cured','In Progress','Fatality','In Treatment'];
      $natureillness = EmployeeHealthIllnessNature::all();

      $models=DB::table('modenadisable')->First();
      return view('user.occupationhealth.updateillness')
                                   ->with('witnesses',$witnesses)
                                   ->with('empdetails',$empdetails)
                                   ->with('model',$models)
                                   ->with('creat_dep',$creat_dep)
                                   ->with('ill_dep',$ill_dep)
                                   ->with('healthcategory',$healthcategory)
                                   ->with('treatment',$treatment)
                                   ->with('illnessnonwork',$illnessnonwork)
                                   ->with('status',$status)
                                   ->with('natureillness',$natureillness)
                                   ->with('record',$record);

    }


    public function updateconfillnessrecord(Request $request){
        $today=Carbon::now()->toDateTimeString();
        $today=Carbon::parse($today);
        $doill=Carbon::parse($request->illdate);

        if($request->ill_type == 'non_work_related')
        {
          $nonwork_related = implode(',',$request->nonwork_ill);
          $workrelated_illness = NULL;
          //$nature_illness = implode(',',$request->non_work_nature);
          if(is_array($request->non_work_nature))
            {
              //$nature_illness = implode(',',$request->non_work_nature);
              $nature_illness = '-';
            }
          else
           {
             $nature_illness = $request->non_work_nature;
           }
        }
        else
        {
          $nonwork_related = '-';
          $workrelated_illness = $request->work_ill;
          //$nature_illness = implode(',',$request->work_nature);
          if(is_array($request->work_nature))
            {
              //$nature_illness = implode(',',$request->work_nature);
              $nature_illness = '-';
            }
          else
           {
             $nature_illness = $request->work_nature;
           }
        }

        $nonwork_arr = explode(',',$nonwork_related);
        if($request->ill_type == 'non_work_related' && collect($nonwork_arr)->contains('other'))
          {
            $nonworkrelated_illness_other = $request->nonwork_oth;
          }
        else
         {
          $nonworkrelated_illness_other = NULL;
         }

         if($request->thing=='yes')
         {
           $things_involved_details = $request->things;
         }
        else
         {
          $things_involved_details = NULL;
         }


        if($request->treatment == 'yes')
        {
          $treatmentdetails = implode(',',$request->treatments);
        }
        else
        {
          $treatmentdetails = '-';
        }


        $treatment_arr = explode(',',$treatmentdetails);
        if($request->treatment == 'yes' && collect($treatment_arr)->contains('other'))
        {
          $treatmentdetails_other = $request->treatment_oth;
        }
       else
        {
          $treatmentdetails_other = NULL;
        }

        if($request->returnwork=='yes')
        {
          $illed_returnto_work_details = $request->returnworks;
        }
       else
        {
          $illed_returnto_work_details = NULL;
        }


        DB::table('employee_illness_record')->where('id',$request->recid)
                                                                   ->update([
                                                                              //'illness_status'=>$request->status,
                                                                              'category_id'=>$request->category,
                                                                              'dateofillness'=>$doill->format('d-m-Y'),
                                                                              'timeofillness'=>$request->illtime,
                                                                              'ill_type'=>$request->ill_type,
                                                                              //'workrelated_illness'=>$request->work_ill,
                                                                              'workrelated_illness'=>$workrelated_illness,
                                                                              'nonworkrelated_illness'=>$nonwork_related,
                                                                              'nonworkrelated_illness_other'=>$nonworkrelated_illness_other,
                                                                              //'natureofillness'=>$request->nature,
                                                                              'natureofillness'=>$nature_illness,
                                                                              'location'=>$request->location,
                                                                              'causeofillness'=>$request->cause,
                                                                              'symptoms'=>$request->symptom,
                                                                              'things_involved'=>$request->thing,
                                                                              //'things_involved_details'=>$request->things,
                                                                              'things_involved_details'=>$things_involved_details,
                                                                              'reported_to_supervisor'=>$request->supervisor,
                                                                              'treatment_provided'=>$request->treatment,
                                                                              'treatment_details'=>$treatmentdetails,
                                                                              'treatmentdetails_other'=>$treatmentdetails_other,
                                                                              'reported_to_supervisor'=>$request->supervisor,
                                                                              'illed_returnto_work'=>$request->returnwork,
                                                                              'illed_returnto_work_details'=>$illed_returnto_work_details,
                                                                              'medical_leaves'=>$request->leaves,
                                                                              'status'=>$request->status,
                                                                              'illed_bp_diastolic'=>$request->bloodpd,
                                                                              'illed_bp_systolic'=>$request->bps,
                                                                              'illed_bs_level'=>$request->bsl,
                                                                              'remarks'=>$request->remark,
                                                                              'pulse'=>$request->pulse,
                                                                              'temperature'=>$request->temp,
                                                                              'respiratory_rate'=>$request->respiratory_rate,
                                                                              'weight'=>$request->weight,
                                                                              'height'=>$request->height,
                                                                              'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                                                                            ]);
        $emp=DB::table('employee_illness_record')->where('id',$request->recid)->First();



       /**--adding data to employee illness nonwork cases table-*/
        if($request->ill_type == 'non_work_related')
        {
          EmployeeIllnessNonWorkCasesDetails::where('illness_record_id',$request->recid)->delete();
          foreach($request->nonwork_ill as $itm)
          {
            $cases = new EmployeeIllnessNonWorkCasesDetails;
            $cases->illness_id = $itm;
            $cases->illness_record_id = $request->recid;
            $cases->employee_num = $request->number;
            $cases->dateofillness = $request->illdate;
            $cases->save();
          }
        }

      //adding data to employee_illness_nature_details table
      if($request->ill_type == 'non_work_related' && is_array($request->non_work_nature))
      {
        DB::table('employee_illness_nature_details')->where('illness_id',$request->recid)->delete();
        foreach($request->non_work_nature as $nonworknat)
        {
          DB::table('employee_illness_nature_details')->insert([
                                                        'illness_id'=>$request->recid,
                                                        'illness_type'=>'non_work_related',
                                                        'natureofillness'=>$nonworknat,
                                                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                      ]);
        }
      }
      elseif($request->ill_type == 'work_related' && is_array($request->work_nature))
      {
        DB::table('employee_illness_nature_details')->where('illness_id',$request->recid)->delete();
        foreach($request->work_nature as $worknat)
        {
          DB::table('employee_illness_nature_details')->insert([
                                                        'illness_id'=>$request->recid,
                                                        'illness_type'=>'work_related',
                                                        'natureofillness'=>$worknat,
                                                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                      ]);
        }

      }

      //adding data to treatmentillness details table
      if($request->treatment == 'yes')
      {
        EmployeeIllnessTreatmentDetails::where('illness_record_id',$request->recid)->delete();
        foreach($request->treatments as $treat)
        {
            $treatments = new EmployeeIllnessTreatmentDetails;
            $treatments->treatment_id = $treat;
            $treatments->illness_record_id = $request->recid;
            $treatments->save();
        }
      }

        Session::flash('msg','Employee Illness Record Updated Successfully');
        return redirect()->back();
      }

      public function emphealthhistory($eid)
      {
        $emprecord=DB::table('employee_details')->where('emp_number',$eid)->First();
        $empmedrecord=Employeemedicalcheckuprecord::where('emp_number',$eid)->get();
        $empinjury=DB::table('employee_injury_record')->where('e_number',$eid)->count();
        $empillness=DB::table('employee_illness_record')->where('e_number',$eid)->count();

        $injury_rec=DB::table('employee_injury_record')->where('e_number',$eid)->get();
        $illness_rec=DB::table('employee_illness_record')->where('e_number',$eid)->get();

        $models=DB::table('modenadisable')->First();
        $mr = DB::table('representatives')->pluck('email');
        $departments = Departments::all();
        return view('user.occupationhealth.healthhistory')
                                           ->with('model',$models)->with('mr',$mr)->with('departments',$departments)
                                           ->with('emp',$emprecord)->with('empmedrecord',$empmedrecord)
                                           ->with('injury_row',$empinjury)->with('injury',$injury_rec)
                                           ->with('ill_row',$empillness)->with('illness',$illness_rec);
      }

      public function emphealthrecordupdate($eid)
      {
           $empdata=DB::table('employee_health_record')->where('e_number',$eid)->get();
           $healthrecord = DB::table('employee_health_record')->where('e_number',$eid)->First();
           $emprecord=DB::table('employee_details')->where('emp_number',$eid)->First();
           if(count($empdata) > 0)
           {
            $emp_dept1 = DB::table('departments')->where('id',$healthrecord->department)->value('departmentname');
           }

           $emp_dept = DB::table('departments')->where('id',$emprecord->emp_department)->value('departmentname');

          $models=DB::table('modenadisable')->First();
          return view('user.occupationhealth.updatehealthrecord')
                                              ->with('model',$models)
                                              ->with('empdept',$emp_dept)
                                              ->with('healthrecrd',$healthrecord)
                                              ->with('empdata',$empdata)
                                              ->with('emprecord',$emprecord);

      }

      public function emphealthrecordupdateconf(Request $request){

        $mdate=Carbon::parse($request->medicaldate);
        if($request->department=='' || $request->department=='-' || $request->department==0)
        {
           $empdeptid = '-';
        }
        else
        {
           $empdeptid = DB::table('departments')->where('departmentname',$request->department)->value('id');
        }

        $data =array(
                      'e_number' => $request->number,
                      'e_name'=>$request->name,
                      'department'=>$empdeptid,
                      'position'=>$request->position,
                      'date' => $mdate->format('Y-m-d'),
                      'tobacco'=>$request->tobacco,
                      'bp_diastolic'=>$request->bloodpd,
                      'bp_systolic'=>$request->bps,
                      'bs_level'=>$request->bsl,
                      'remarks'=>$request->remark,
                      'employee_id' =>$request->employeeid,
                      'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                      'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    );

        $data2 = array(
                        'emp_number' => $request->number,
                        'date_of_medical' => $mdate->format('Y-m-d'),
                        'tobacco'=>$request->tobacco,
                        'bp_diastolic'=>$request->bloodpd,
                        'bp_systolic'=>$request->bps,
                        'bs_level'=>$request->bsl,
                        'remarks'=>$request->remark,
                        'health_record_id'=>0,
                        'employee_id' =>$request->employeeid
                       );
        $health = DB::table('employee_health_record')->where('employee_id',$request->employeeid)->get();
        if(count($health) > 0 )
        {
          DB::table('employee_health_record')->where('employee_id',$request->employeeid)
                                             ->update([
                                                        'date'=>$mdate->format('Y-m-d'),
                                                        'tobacco'=>$request->tobacco,
                                                        'bp_diastolic'=>$request->bloodpd,
                                                        'bp_systolic'=>$request->bps,
                                                        'bs_level'=>$request->bsl,
                                                        'remarks'=>$request->remark,
                                                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                      ]);
        }
        else
        {
          DB::table('employee_health_record')->insert([$data]);
        }

        Employeemedicalcheckuprecord::create($data2);
        return redirect('/user/occupationhealth/register')->with('msg', 'Employee Medical Record Updated Successfully');
        //return $this->index();
      }

      public function delete()
      {
        if (isset($_GET['val']))
          {
              $id=$_GET['val'];
              $empnum = DB::table('employee_health_record')->where('id',$id)->value('e_number');
              DB::table('employee_health_record')->where('id',$id)->delete();

            // inserting data to activities table
            $activity="A Health Record entry for Employee ID ".$empnum." is removed";
            $date=Carbon::now()->toDateTimeString();
            $date=Carbon::parse($date);
            $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'admin');
            DB::table('viewactivity')->insert($datas);


            $models=DB::table('modenadisable')->First();
            return redirect('/user/occupationhealth/register')->with('model',$models)->with('delmsg','Health Record Details Deleted Successfully');

          }

      }

      public function injurydelete()
      {
        if (isset($_GET['val']))
          {
              $id=$_GET['val'];

              $empnum = DB::table('employee_injury_record')->where('id',$id)->value('e_number');

              $workcasesexist =  DB::table('employee_injury_work_related_cases')->where('injury_record_id',$id)->get();
              if(count($workcasesexist) > 0)
              {
                DB::table('employee_injury_work_related_cases')->where('injury_record_id',$id)->delete();
              }

              $treatmentexist = DB::table('employee_injury_treatment_details')->where('injury_record_id',$id)->get();
              if(count($treatmentexist)>0)
              {
                DB::table('employee_injury_treatment_details')->where('injury_record_id',$id)->delete();
              }

              $natureexist = DB::table('employee_injury_nature_details')->where('injury_id',$id)->get();
              if(count($natureexist)>0)
              {
                DB::table('employee_injury_nature_details')->where('injury_id',$id)->delete();
              }


              DB::table('employee_injury_record')->where('id',$id)->delete();

            // inserting data to activities table
            $activity="An Injury Record entry for Employee ID ".$empnum." is removed";
            $date=Carbon::now()->toDateTimeString();
            $date=Carbon::parse($date);
            $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'admin');
            DB::table('viewactivity')->insert($datas);


            $models=DB::table('modenadisable')->First();
            return redirect('/user/occupationhealth/injuryrecords')->with('model',$models)->with('delmsg','Injury Record Details Deleted Successfully');

          }

      }

      public function illnessdelete()
      {
        if (isset($_GET['val']))
          {
              $id=$_GET['val'];
              $empnum = DB::table('employee_illness_record')->where('id',$id)->value('e_number');

              $nonworkcasesexist =  DB::table('employee_illness_non_work_cases_details')->where('illness_record_id',$id)->get();
              if(count($nonworkcasesexist) > 0)
              {
                DB::table('employee_illness_non_work_cases_details')->where('illness_record_id',$id)->delete();
              }

              $treatmentexist = DB::table('employeeillness_treatmentdetails')->where('illness_record_id',$id)->get();
              if(count($treatmentexist)>0)
              {
                DB::table('employeeillness_treatmentdetails')->where('illness_record_id',$id)->delete();
              }

              $natureexist = DB::table('employee_illness_nature_details')->where('illness_id',$id)->get();
              if(count($natureexist)>0)
              {
                DB::table('employee_illness_nature_details')->where('illness_id',$id)->delete();
              }

              DB::table('employee_illness_record')->where('id',$id)->delete();

            // inserting data to activities table
            $activity="An Illness Record entry for Employee ID ".$empnum." is removed";
            $date=Carbon::now()->toDateTimeString();
            $date=Carbon::parse($date);
            $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'admin');
            DB::table('viewactivity')->insert($datas);


            $models=DB::table('modenadisable')->First();
            return redirect('/user/occupationhealth/illnessrecords')->with('delmsg','Illness Record Details Deleted Successfully');

          }

      }


      public function analysis()
      {
          $totalinjured = DB::table('employee_injury_record')->get()->count();
          $totalillness = DB::table('employee_illness_record')->get()->count();
          $totalcases = $totalinjured + $totalillness;

          $totalillcatcode = DB::table('employee_illness_record')->where('category_id','!=',0)->get()->count();
          $totalinjurycatcode = EmployeeInjuryRecord::where('category_id','!=',0)->get()->count();

          $illnessnonworktopcases  = EmployeeIllnessNonWorkCasesDetails::where('illness_id','!=','other')->count();
          $illnessnonworkcasesother = DB::table('employee_illness_record')->where('nonworkrelated_illness','=','other')->get()->count();
          $illness_workcases = DB::table('employee_illness_record')->where('ill_type','work_related')->get()->count();



          $injuryworktopcases  = EmployeeInjuryWorkRelatedCases::where('injury_id','!=','other')->count();
          $injuryworkcasesother = DB::table('employee_injury_record')->where('workrelated_injury','=','other')->get()->count();
          $injury_nonworkcases = DB::table('employee_injury_record')->where('injury_type','non_work_related')->get()->count();

          $category = HealthCategory::all();
          $totalcategory = count($category);

          //fetching only category name
          $categoryname = [];
          foreach($category as $item)
           {
              $categoryname[] = $item->name;
           }

          $catcode = [];
          foreach($category as $item)
           {
              $catcode[] = $item->name;
              //array_push($catcode,$item->name);
           }
          array_push($catcode,'Total');

          $catdata = [];
          foreach($category as $item)
           {
             $catdata[] = DB::table('employee_illness_record')->where('category_id',$item->id)->get()->count();
           }
          array_push($catdata,$totalillcatcode);

          $injurycatdata = [];
          foreach($category as $item)
          {
            $injurycatdata[] = EmployeeInjuryRecord::where('category_id',$item->id)->get()->count();
          }
          array_push($injurycatdata,$totalinjurycatcode);

          $illnesscases = IllnessNonWorkrelatedcases::all();
          $illnesstotaltopcases = count($illnesscases);

          $illcasename = [];
          foreach($illnesscases as $itm)
          {
            $illcasename[] = $itm->name;
          }
          array_push($illcasename,'Other Non-Work Related Cases','Work Related Cases');

          $illcasedata = [];
          foreach($illnesscases as $data)
          {
            //$illcasedata[] = DB::table('employee_illness_non_work_cases_details')->where('illness_id',$data->id)->get()->count();
            $illcasedata[] = EmployeeIllnessNonWorkCasesDetails::where('illness_id',$data->id)->get()->count();
          }
          array_push($illcasedata,$illnessnonworkcasesother,$illness_workcases);


          $injurycases = InjuryWorkrelatedcases::all();
          $injurytotaltopcases = count($injurycases);

          $injurycasename = [];
          foreach($injurycases as $itm)
          {
            $injurycasename[] = ucfirst(strtolower($itm->name));
          }
          array_push($injurycasename,'Other Work Related Cases','Non-Work Related Cases');


          $injurycasedata = [];
          foreach($injurycases as $data)
          {
            $injurycasedata[] = EmployeeInjuryWorkRelatedCases::where('injury_id',$data->id)->get()->count();
          }
          array_push($injurycasedata,$injuryworkcasesother,$injury_nonworkcases);


          $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
          $firstyear = 2020; $lastyear = 2030;

          $year = [];
          for($i=$firstyear;$i<=$lastyear;$i++)
          {
            $year[] = $i;
          }

          $injuryalldates = DB::table('employee_injury_record')
                                                        ->where('injury_type','=','work_related')
                                                        ->where('workrelated_injury','!=','other')
                                                        ->pluck('dateofinjury');


          $injurymonth = [];
          foreach($months as $monthitm)
          {
            $cnt = 0;
            foreach($injuryalldates as $item)
            {
              $injmonth = date('M', strtotime($item));
              if($monthitm == $injmonth)
              {
                $cnt++;
              }
            }
            array_push($injurymonth,$cnt);
          }

         $injuryyear = [];
         foreach($year as $yr)
         {
            $cnt = 0;
            foreach($injuryalldates as $item)
            {
              $injyear = date('Y',strtotime($item));
              if($yr == $injyear)
              {
                $cnt++;
              }
            }
            array_push($injuryyear,$cnt);
         }

         $illnessalldates = DB::table('employee_illness_record')
                                                              ->where('ill_type','=','non_work_related')
                                                              ->where('nonworkrelated_illness','!=','other')
                                                              ->pluck('dateofillness');



         $illnessmonth = [];
         foreach($months as $item)
         {
             $cnt = 0;
             foreach($illnessalldates as $illdt)
             {
                $illdat = date('M',strtotime($illdt));
                if($item == $illdat)
                {
                  $cnt++;
                }
             }
             array_push($illnessmonth,$cnt);
         }

        $illnessyear = [];
        foreach($year as $item)
        {
          $cnt = 0;
          foreach($illnessalldates as $illdt)
          {
             $illdat = date('Y',strtotime($illdt));
             if($item == $illdat)
             {
               $cnt++;
             }
          }
          array_push($illnessyear,$cnt);
        }


        $monthnumeric = ['01','02','03','04','05','06','07','08','09','10','11','12'];

        $injurycategorymonth = [];
        foreach($monthnumeric as $month)
        {
           $catinjmonth = DB::select("select s.id,s.name,COUNT(r.category_id)AS number
                                      FROM employee_injury_record AS r
                                      right JOIN health_categories AS s
                                      ON r.category_id = s.id and dateofinjury like '%-".$month."-%'
                                      group by s.id,s.name
                                      order by s.id ASC
                                    ");
              $injcatmnth =[];
              foreach($catinjmonth as $mnthitm)
              {
                $injcatmnth[$mnthitm->name] = $mnthitm->number;
              }
              //$injurycategorymonth[$month] = $injcatmnth;
          array_push($injurycategorymonth,$injcatmnth);
        }


        $injuryyearlycategory = [];
        foreach($year as $yearitm)
         {
            $catyearly = DB::select("select s.id,s.name,COUNT(r.category_id)AS number
                                      FROM employee_injury_record AS r
                                      right JOIN health_categories AS s
                                      ON r.category_id = s.id and dateofinjury like '%-".$yearitm."'
                                      group by s.id,s.name
                                      order by s.id ASC
                                   ");
            $injcatyr = [];
            foreach($catyearly as $catyear)
            {
              //$injcatyr[catyear->name.catyear->id] = $catyear->number;
              $injcatyr[$catyear->name] = $catyear->number;
            }
            $injuryyearlycategory[$yearitm] = $injcatyr ;
         }


        $illnesscategorymonth = [];
        foreach($monthnumeric as $month)
        {
           $category_illmnth = DB::select("select s.id,s.name,COUNT(r.category_id)AS number
                                      FROM employee_illness_record AS r
                                      right JOIN health_categories AS s
                                      ON r.category_id = s.id and dateofillness like '%-".$month."-%'
                                      group by s.id,s.name
                                      order by s.id ASC
                                    ");
              $illnesscatmnth =[];
              foreach($category_illmnth as $mnthitm)
              {
                $illnesscatmnth[$mnthitm->name] = $mnthitm->number;
              }
              //$injurycategorymonth[$month] = $injcatmnth;
          array_push($illnesscategorymonth,$illnesscatmnth);
        }



        $illnesscategoryyear = [];
        foreach($year as $yearitm)
         {
            $illcatyearly = DB::select("select s.id,s.name,COUNT(r.category_id)AS number
                                      FROM employee_illness_record AS r
                                      right JOIN health_categories AS s
                                      ON r.category_id = s.id and dateofillness like '%-".$yearitm."'
                                      group by s.id,s.name
                                      order by s.id ASC
                                   ");
            $illcatyear = [];
            foreach($illcatyearly as $catyear)
            {
              $illcatyear[$catyear->name] = $catyear->number;
            }
            $illnesscategoryyear[$yearitm] = $illcatyear;
         }


          $depcodes = DB::select("select code from departments order by departmentname ASC");
          $depcode = [];
          foreach ($depcodes as $key)
          {
              array_push($depcode, $key->code);
          }

          $injureddp = DB::select("select s.id,COUNT(r.dateofinjury)AS number
                                   FROM employee_injury_record AS r
                                   right JOIN departments AS s
                                   ON r.department = s.id
                                   group by s.id
                                   order by s.departmentname ASC
                                ");


          $illnessdp = DB::select("select s.id,COUNT(r.dateofillness)AS number
                                   FROM employee_illness_record AS r
                                   right JOIN departments AS s
                                   ON r.department = s.id
                                   group by s.id
                                   order by s.departmentname ASC;
                                ");


          $injured = [];
          foreach ($injureddp as $key) {
              array_push($injured, $key->number);
          }

          $illness = [];
          foreach ($illnessdp as $key) {
              array_push($illness, $key->number);
          }


          $models=DB::table('modenadisable')->First();
          return view('user.occupationhealth.analysis')
                                        ->with('model',$models)
                                        ->with('totalcases',$totalcases)
                                        ->with('totalinjured',$totalinjured)
                                        ->with('totalillness',$totalillness)
                                        ->with('totalcategory',$totalcategory)
                                        ->with('injurytotaltopcases',$injurytotaltopcases)
                                        ->with('illnesstotaltopcases',$illnesstotaltopcases)
                                        ->with('injuryworktopcases',$injuryworktopcases)
                                        ->with('injuryworkcasesother',$injuryworkcasesother)
                                        ->with('injury_nonworkcases',$injury_nonworkcases)
                                        ->with('illnessnonworktopcases',$illnessnonworktopcases)
                                        ->with('illnessnonworkcasesother',$illnessnonworkcasesother)
                                        ->with('illness_workcases',$illness_workcases)
                                        ->with('depcode', json_encode($depcode, JSON_NUMERIC_CHECK))
                                        ->with('catcode', json_encode($catcode, JSON_NUMERIC_CHECK))
                                        ->with('catdata', json_encode($catdata, JSON_NUMERIC_CHECK))
                                        ->with('categoryname',json_encode($categoryname, JSON_NUMERIC_CHECK))
                                        ->with('illcasename',json_encode($illcasename, JSON_NUMERIC_CHECK))
                                        ->with('illcasedata',json_encode($illcasedata, JSON_NUMERIC_CHECK))
                                        ->with('injurycasename',json_encode($injurycasename, JSON_NUMERIC_CHECK))
                                        ->with('injurycasedata',json_encode($injurycasedata, JSON_NUMERIC_CHECK))
                                        ->with('injurycatdata',json_encode($injurycatdata, JSON_NUMERIC_CHECK))
                                        ->with('months',json_encode($months, JSON_NUMERIC_CHECK))
                                        ->with('year',json_encode($year, JSON_NUMERIC_CHECK))
                                        ->with('injurymonth',json_encode($injurymonth, JSON_NUMERIC_CHECK))
                                        ->with('injuryyear',json_encode($injuryyear, JSON_NUMERIC_CHECK))
                                        ->with('illnessmonth',json_encode($illnessmonth, JSON_NUMERIC_CHECK))
                                        ->with('illnessyear',json_encode($illnessyear, JSON_NUMERIC_CHECK))
                                        ->with('injurycategorymonth',json_encode($injurycategorymonth, JSON_NUMERIC_CHECK))
                                        ->with('injuryyearlycategory',json_encode($injuryyearlycategory, JSON_NUMERIC_CHECK))
                                        ->with('illnesscategorymonth',json_encode($illnesscategorymonth, JSON_NUMERIC_CHECK))
                                        ->with('illnesscategoryyear',json_encode($illnesscategoryyear, JSON_NUMERIC_CHECK))
                                        ->with('injured', json_encode($injured, JSON_NUMERIC_CHECK))
                                        ->with('illness', json_encode($illness, JSON_NUMERIC_CHECK));

      }


/**--Other cases */
  public function othercases()
  {
      $eid=Auth::user()->employeeid;
      $models=DB::table('modenadisable')->First();
      $doctors = DB::table('company_doctors')->pluck('email')->toArray();
      $witnesses=DB::table('employee_injury_witness')->get();
      $mr = DB::table('representatives')->pluck('email');
      $departments = Departments::all();
      $natureinjury = EmployeeHealthInjuryNature::all();
      $empinjuryrecord= EmployeeInjuryRecord::where('injured_person_type','!=',NULL)->latest('created_at')->get();
      $natureillness = EmployeeHealthIllnessNature::all();
      $illnessrecord=EmployeeIllnessRecord::where('illed_person_type','!=',NULL)->latest('created_at')->get();
      $illwitnesses=DB::table('employee_illness_witness')->get();

      return view('user.occupationhealth.contractordetails')
                                                 ->with('doctors',$doctors)
                                                 ->with('witnesses',$witnesses)
                                                 ->with('illwitnesses',$illwitnesses)
                                                 ->with('mr',$mr)
                                                 ->with('departments',$departments)
                                                 ->with('emprecord',$empinjuryrecord)
                                                 ->with('natureinjury',$natureinjury)
                                                 ->with('natureillness',$natureillness)
                                                 ->with('empillrecord',$illnessrecord)
                                                 ->with('model',$models);

  }


 public function addotherinjuryillness()
 {
      $models=DB::table('modenadisable')->First();
      $departments=DB::table('departments')->get();
      $healthcategory = HealthCategory::all();
      $treatment = HealthTreatment::all();
      $injurywrkcase = InjuryWorkrelatedcases::all();
      $illnessnonwork = IllnessNonWorkrelatedcases::all();
      $models=DB::table('modenadisable')->First();
      $status = ['Cured','In Progress','Fatality','In Treatment'];
      $injuredbodyprt = [
                          'Eyes','Nose','Throat','Head/Skull','Mouth/Lips','Tongue','Teeth','Face',
                          'Neck','Shoulder','Chest','Stomach','Upper/Lower Back','Arms','Forearms','Elbow',
                          'Hands','Fingers','Buttocks','Groin','Genital','Anal','Thigh','Leg',
                          'Knee','Calf','Foot','Heel','Ankle','Sole','Toes/ToeNails'
                        ];


      $natureinjury = EmployeeHealthInjuryNature::all();
      $empid=Auth::user()->employeeid;
      $emplydetails=DB::table('employee_details')->where('emp_number',$empid)->First();
      if($emplydetails->emp_department!='-')
      {
        $empdeptname =DB::table('departments')->where('id',$emplydetails->emp_department)->value('departmentname');
      }
      else
      {
        $empdeptname = '-';
      }

   return view('user.occupationhealth.otherinjuryillnessform')
                                                     ->with('model',$models)
                                                     ->with('depts',$departments)
                                                     ->with('healthcategory',$healthcategory)
                                                     ->with('treatment',$treatment)
                                                     ->with('status',$status)
                                                     ->with('injuredbodyprt',$injuredbodyprt)
                                                     ->with('injurywrkcase',$injurywrkcase)
                                                     ->with('illnessnonwork',$illnessnonwork)
                                                     ->with('natureinjury',$natureinjury)
                                                     ->with('emplydetails',$emplydetails)
                                                     ->with('empdeptname',$empdeptname);
 }


public function addotherinjuryrecords(Request $request)
{
  $witness='No';
  $returned_to_work = "no";
  $doinj=Carbon::parse($request->injdate);

  if($request->injured_person_type=='contractor')
  {
     $person_name = $request->contractor_name;
     $person_type = 'contractor';
  }
  else
  {
    $person_type ='other';
    $person_name = $request->injured_person_other;
  }

  // if($request->injured_person_other!=NULL)
  // {
  //   $person_other = $request->injured_person_other;
  // }
  // else
  // {
  //   $person_other = NULL;
  // }
  if($request->number==NULL)
  {
    $injempnumber= '-';
  }
  else
  {
    $injempnumber = $request->number;
  }

  if($request->dob==NULL)
  {
    $injempdob = '-';
  }
  else
  {
    $injempdob = $request->dob;
  }

  if($request->department==NULL)
  {
     $injempdept = '-';
  }
 else
  {
    $getinjdepid = Departments::where('departmentname',$request->department)->value('id');
    $injempdept = $getinjdepid;
  }

 if($request->position==NULL)
  {
     $injemppost = '-';
  }
 else
  {
    $injemppost = $request->position;
  }


  if($request->createrposition==NULL)
  {
     $creater_post = '-';
  }
 else
  {
    $creater_post = $request->createrposition;
  }

  if($request->createrdepartment==NULL || $request->createrdepartment =='-')
  {
     $creater_dept = '-';
  }
 else
  {
    $getcretdepid = Departments::where('departmentname',$request->createrdepartment)->value('id');
    $creater_dept = $getcretdepid;
  }

  if($request->injury_type == 'work_related')
  {
    $work_related = implode(',',$request->work_injury);
    //$nature_injury = implode(',',$request->work_nature);
    $nature_injury = '-';
  }
  else
  {
    $work_related = '-';
    //$nature_injury = implode(',',$request->non_work_nature);
    $nature_injury = '-';
  }


  $bodyinjured = implode(',',$request->body_injured);
  if($request->body_injured_oth!=NULL)
  {
    $body_inj_other = $request->body_injured_oth;
  }
  else
  {
    $body_inj_other = '-';
  }

  if($request->treatment == 'yes')
  {
    $treatmentdetails = implode(',',$request->treatments);
  }
  else
  {
    $treatmentdetails = '-';
  }



  $year = date('Y');

  $injdata = array(
                'category_id'=>$request->category,
                'e_number'=>$injempnumber,
                'injured_person_type'=>$person_type,
                'e_name'=>$person_name,
                'dob'=>$injempdob,
                'department'=>$injempdept,
                'position'=>$injemppost,
                'dateofinjury'=>$doinj->format('d-m-Y'),
                'timeofinjury'=>$request->time,
                'injury_type'=>$request->injury_type,
                'workrelated_injury'=>$work_related,
                'workrelated_injury_other'=>$request->workinjury_oth,
                'nonwork_related_injury'=>$request->nonwork_injury,
                'natureofinjury'=>$nature_injury,
                'bodily_location'=>$bodyinjured,
                'body_injured_other'=>$request->body_injured_oth,
                'location'=>$request->location,
                'causeofinjury'=>$request->cause,
                'things_involved'=>$request->thing,
                'things_involved_details'=>$request->things,
                'witness'=>$witness,
                'reported_to_supervisor'=>$request->supervisor,
                'treatment_provided'=>$request->treatment,
                'treatment_details'=>$treatmentdetails,
                'treatmentdetails_other'=>$request->treatment_oth,
                'injured_returnto_work'=>$returned_to_work,
                'creator_name'=>Auth::user()->name,
                'creator_email'=>Auth::user()->email,
                'creator_position'=>$creater_post,
                'creator_department'=>$creater_dept,
                'created_date'=>$request->createddate,
                'status'=>$request->status,
                'injured_bp_diastolic'=>$request->bloodpd,
                'injured_bp_systolic'=>$request->bps,
                'injured_bs_level'=>$request->bsl,
                'remarks'=>$request->remark,
                'pulse'=>$request->pulse,
                'temperature'=>$request->temp,
                'respiratory_rate'=>$request->respiratory_rate,
                'weight'=>$request->weight,
                'height'=>$request->height,
                'year'=>$year,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
              );

  DB::table('employee_injury_record')->insert($injdata);

  //generating report number
      $data = DB::table('employee_injury_record')->latest('id')->first();
      $recentid = $data->id;
      $year = date('y');
      $reportnum = 'INJ'.$recentid.$year;
      DB::table('employee_injury_record')->where('id',$recentid)->update(['report_num' =>$reportnum]);

      //inserting witness data to employee_injury_witness table
      if(count($request->doc)>0)
      {
        for ($i=0;$i<count($request->doc);$i++)
         {
            if($request->doc[$i]['name']!=null && $request->doc[$i]['number']!=null)
            {
              DB::table('employee_injury_witness')->insert([
                                                      'created_date'=>$request->createddate,
                                                      'injured_per_enumber'=>$injempnumber,
                                                      'name'=>$request->doc[$i]['name'],
                                                      'number'=>$request->doc[$i]['number'],
                                                      'injury_id'=>$recentid,
                                                      'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                      'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    ]);
              $witness="Yes";
            }
          }
      }
      DB::table('employee_injury_record')->where('id',$recentid)->update(['witness'=>$witness]);

   //adding data to employee injury work related cases table
  if($request->injury_type == 'work_related')
  {
    foreach($request->work_injury as $itm)
    {
      $cases = new EmployeeInjuryWorkRelatedCases;
      $cases->injury_id = $itm;
      $cases->injury_record_id = $recentid;
      $cases->employee_num = $injempnumber;
      $cases->dateofinjury = $request->injdate;
      $cases->save();
    }
  }

 //adding to treatmentinjury details table
 if($request->treatment == 'yes')
 {
   foreach($request->treatments as $treat)
   {
      $treatments = new EmployeeInjuryTreatmentDetail;
      $treatments->treatment_id = $treat;
      $treatments->injury_record_id = $recentid;
      $treatments->save();

   }
 }

//adding data to employee_injury_nature_details table
  if($request->injury_type == 'work_related')
  {
    foreach($request->work_nature as $worknat)
    {
      DB::table('employee_injury_nature_details')->insert([
                                                    'injury_id'=>$recentid,
                                                    'injury_type'=>'work_related',
                                                    'natureofinjury'=>$worknat,
                                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                  ]);
    }
  }
  else
  {
    foreach($request->non_work_nature as $nonworknat)
    {
      DB::table('employee_injury_nature_details')->insert([
                                                    'injury_id'=>$recentid,
                                                    'injury_type'=>'non_work_related',
                                                    'natureofinjury'=>$nonworknat,
                                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                  ]);
    }

  }

  Session::flash('msg','Injury record added successfully');
  return redirect()->back();
}

public function updateotherinjuryrecord($id)
{
  $record=DB::table('employee_injury_record')->where('id',$id)->First();
  $creat_dept = $record->creator_department;
  if($creat_dept == NULL || $creat_dept == '-')
    {
      $creat_dep = '-';
    }
  else
   {
      $creat_dep = Departments::where('id',$creat_dept)->value('departmentname');
   }

  $inj_dep = Departments::where('id',$record->department)->value('departmentname');
  $witnesses=DB::table('employee_injury_witness')
                              ->where('injury_id',$id)
                              ->get();
  $healthcategory = HealthCategory::all();
  $treatment = HealthTreatment::all();
  $injurywrkcase = InjuryWorkrelatedcases::all();
  $injuredbodyprt = [
                      'Eyes','Nose','Throat','Head/Skull','Mouth/Lips','Tongue','Teeth','Face',
                      'Neck','Shoulder','Chest','Stomach','Upper/Lower Back','Arms','Forearms','Elbow',
                      'Hands','Fingers','Buttocks','Groin','Genital','Anal','Thigh','Leg',
                      'Knee','Calf','Foot','Heel','Ankle','Sole','Toes/ToeNails'
                    ];
  $status = ['Cured','In Progress','Fatality','In Treatment'];
  $natureinjury = EmployeeHealthInjuryNature::all();

  $models=DB::table('modenadisable')->First();
  return view('user.occupationhealth.updateotherinjury')
                                ->with('witnesses',$witnesses)
                                ->with('model',$models)
                                ->with('creat_dep',$creat_dep)
                                ->with('healthcategory',$healthcategory)
                                ->with('treatment',$treatment)
                                ->with('injurywrkcase',$injurywrkcase)
                                ->with('inj_dep',$inj_dep)
                                ->with('injuredbodyprt',$injuredbodyprt)
                                ->with('status',$status)
                                ->with('natureinjury',$natureinjury)
                                ->with('record',$record);
}



public function updateconfirmotherinjuryrecord(Request $request)
{
    $today=Carbon::now()->toDateTimeString();
    $today=Carbon::parse($today);

    $returnwork = $request->returnwork;
    $returnworks_details= $request->returnworks;
    $leaves = $request->leaves;
    $db_status = $request->db_status;

    if($request->injured_person_type=='contractor')
      {
        $person_name = $request->contractor_name;
        $person_type = 'contractor';
      }
    else
      {
        $person_type ='other';
        $person_name = $request->injured_person_other;
      }

      if($request->number==NULL)
      {
        $injempnumber= '-';
      }
      else
      {
        $injempnumber = $request->number;
      }

      if($request->dob==NULL)
      {
        $injempdob = '-';
      }
      else
      {
        $injempdob = $request->dob;
      }

      if($request->department==NULL)
      {
         $injempdept = '-';
      }
     else
      {
        $getinjdepid = Departments::where('departmentname',$request->department)->value('id');
        $injempdept = $getinjdepid;
      }

     if($request->position==NULL)
      {
         $injemppost = '-';
      }
     else
      {
        $injemppost = $request->position;
      }

    $bodyinjured = implode(',',$request->body_injured);
    if($request->body_injured_oth!=NULL)
    {
      $body_inj_other = $request->body_injured_oth;
    }
    else
    {
      $body_inj_other = '-';
    }

    if($request->injury_type == 'work_related')
    {
      $work_related = implode(',',$request->work_injury);
      $nonwork_related_injury = NULL;
      if(is_array($request->work_nature))
        {
          //$nature_injury = implode(',',$request->work_nature);
          $nature_injury = '-';
        }
      else
        {
          $nature_injury = $request->work_nature;
        }

    }
    else
    {
      $work_related = '-';
      $nonwork_related_injury = $request->nonwork_injury;
      if(is_array($request->non_work_nature))
        {
          //$nature_injury = implode(',',$request->non_work_nature);
          $nature_injury = '-';
        }
      else
      {
        $nature_injury = $request->non_work_nature;
      }
    }

  $work_arr = explode(',',$work_related);
  if($request->injury_type == 'work_related' && collect($work_arr)->contains('other'))
    {
      $work_related_injury_other = $request->workinjury_oth;
    }
  else
   {
     $work_related_injury_other = NULL;
   }


    if($request->thing=='yes')
    {
      $things_involved_details = $request->things;
    }
    else
    {
      $things_involved_details = NULL;
    }

    if($request->treatment == 'yes')
    {
      $treatmentdetails = implode(',',$request->treatments);
    }
    else
    {
      $treatmentdetails = '-';
    }


    $treatment_arr = explode(',',$treatmentdetails);
    if($request->treatment == 'yes' && collect($treatment_arr)->contains('other'))
    {
      $treatmentdetails_other = $request->treatment_oth;
    }
    else
    {
      $treatmentdetails_other = NULL;
    }

   $doinj=Carbon::parse($request->injdate);


   if($returnwork!="" || $returnworks_details!="" || $leaves!="")
    {
       DB::table('employee_injury_record')->where('id',$request->recid)
                                      ->update([
                                                  'injured_returnto_work'=>$returnwork,
                                                  'injured_returnto_work_details'=>$returnworks_details,
                                                  'medical_leaves'=>$leaves,
                                                  'status'=>$request->status
                                                ]);
    }

  else
  {
     DB::table('employee_injury_record')->where('id',$request->recid) ->update(['status'=>$request->status]);
  }

   $emp=DB::table('employee_injury_record')->where('id',$request->recid)->First();
   DB::table('employee_injury_record')->where('id',$request->recid)
                                                  ->update([
                                                            'category_id'=>$request->category,
                                                            'e_number'=>$injempnumber,
                                                            'injured_person_type'=>$person_type,
                                                            'e_name'=>$person_name,
                                                            'dob'=>$injempdob,
                                                            'department'=>$injempdept,
                                                            'position'=>$injemppost,
                                                            'dateofinjury'=>$doinj->format('d-m-Y'),
                                                            'timeofinjury'=>$request->injtime,
                                                            'injury_type'=>$request->injury_type,
                                                            'workrelated_injury'=>$work_related,
                                                            'workrelated_injury_other'=> $work_related_injury_other,
                                                            'nonwork_related_injury'=>$nonwork_related_injury,
                                                            'natureofinjury'=>$nature_injury,
                                                            'bodily_location'=>$bodyinjured,
                                                            'body_injured_other'=>$request->body_injured_oth,
                                                            'location'=>$request->location,
                                                            'causeofinjury'=>$request->cause,
                                                            'things_involved'=>$request->thing,
                                                            'things_involved_details'=>$things_involved_details,
                                                            'reported_to_supervisor'=>$request->supervisor,
                                                            'treatment_provided'=>$request->treatment,
                                                            'treatment_details'=>$treatmentdetails,
                                                            'treatmentdetails_other'=>$treatmentdetails_other,
                                                            'injured_bp_diastolic'=>$request->bloodpd,
                                                            'injured_bp_systolic'=>$request->bps,
                                                            'injured_bs_level'=>$request->bsl,
                                                            'remarks'=>$request->remark,
                                                            'pulse'=>$request->pulse,
                                                            'temperature'=>$request->temp,
                                                            'respiratory_rate'=>$request->respiratory_rate,
                                                            'weight'=>$request->weight,
                                                            'height'=>$request->height,
                                                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                                                           ]);


//updating data in employee injury work related cases table
 if($request->injury_type == 'work_related')
      {
        EmployeeInjuryWorkRelatedCases::where('injury_record_id',$request->recid)->delete();
        foreach($request->work_injury as $itm)
        {
          $cases = new EmployeeInjuryWorkRelatedCases;
          $cases->injury_id = $itm;
          $cases->injury_record_id = $request->recid;
          $cases->employee_num = $request->number;
          $cases->dateofinjury = $request->injdate;
          $cases->save();
        }
      }


//adding data to treatmentinjury details table
  if($request->treatment == 'yes')
  {
    EmployeeInjuryTreatmentDetail::where('injury_record_id',$request->recid)->delete();
    foreach($request->treatments as $treat)
    {
        $treatments = new EmployeeInjuryTreatmentDetail;
        $treatments->treatment_id = $treat;
        $treatments->injury_record_id = $request->recid;
        $treatments->save();
    }
  }

  //updating data to employee_injury_nature_details table
  if($request->injury_type == 'work_related' && is_array($request->work_nature))
  {
    DB::table('employee_injury_nature_details')->where('injury_id',$request->recid)->delete();
    foreach($request->work_nature as $worknat)
    {
      DB::table('employee_injury_nature_details')->insert([
                                                    'injury_id'=>$request->recid,
                                                    'injury_type'=>'work_related',
                                                    'natureofinjury'=>$worknat,
                                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                  ]);
    }
  }
  elseif($request->injury_type == 'non_work_related' && is_array($request->non_work_nature))
  {
    DB::table('employee_injury_nature_details')->where('injury_id',$request->recid)->delete();
    foreach($request->non_work_nature as $nonworknat)
    {
      DB::table('employee_injury_nature_details')->insert([
                                                    'injury_id'=>$request->recid,
                                                    'injury_type'=>'non_work_related',
                                                    'natureofinjury'=>$nonworknat,
                                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                  ]);
    }

  }



//Sending  mail to doctor when injury status changed from cured
  if($db_status=="Cured" && $request->status!="Cured")
  {
    $report_num = $request->report_num;
    $doctor=DB::table('company_doctors')->pluck('email');
    $data=array(
              'line1'=>Lang::get('Injury Status with Report Number - '.$report_num.'  is changed from Cured to  '.$request->status),
              );
    foreach($doctor as $doc)
    {
      Mail::to($doc)->send(new OccupationalHealthInjuryUpdate($data));
    }

  }

  $tobbacco=DB::table('employee_health_record')->where('e_number',$emp->e_number)->First();

    Session::flash('msg','Employee Injury Record Updated Successfully');
    return redirect()->back();
}

public function addotherillnessrecords(Request $request)
{
      $contacts=[];
      $contact="";
      $witness='No';

      if(count($request->contact)>0)
      {
        for ($i=0;$i<count($request->contact);$i++)
        {
          if($request->contact[$i]['name']!=null && $request->contact[$i]['eid']!=null)
          {
            array_push($contacts,$request->contact[$i]['name'].' ('.$request->contact[$i]['eid'].')');
           }
         }
      }

      if(count($contacts)>0){
        $contact=implode(',',$contacts);
      }

      $doinj=Carbon::parse($request->injdate);

      if($request->illed_person_type=='contractor')
      {
        $person_name = $request->contractor_name_ill;
        $person_type = 'contractor';
      }
      else
      {
        $person_type ='other';
        $person_name = $request->injured_person_other_ill;
      }


      if($request->number==NULL)
      {
        $illempnumber= '-';
      }
      else
      {
        $illempnumber = $request->number;
      }

      if($request->dob==NULL)
      {
        $illempdob = '-';
      }
      else
      {
        $illempdob = $request->dob;
      }

      if($request->department==NULL)
      {
         $illempdept = '-';
      }
     else
      {
        $getilldepid = Departments::where('departmentname',$request->department)->value('id');
        $illempdept = $getilldepid;
      }

     if($request->position==NULL)
      {
         $illemppost = '-';
      }
     else
      {
        $illemppost = $request->position;
      }


      if($request->createrposition==NULL)
      {
         $creater_post = '-';
      }
     else
      {
        $creater_post = $request->createrposition;
      }

      if($request->createrdepartment==NULL || $request->createrdepartment=='-')
      {
         $creater_dept = '-';
      }
     else
      {
        $getcretdepid = Departments::where('departmentname',$request->createrdepartment)->value('id');
        $creater_dept = $getcretdepid;
      }


      if($request->ill_type == 'non_work_related')
      {
        $nonwork_related = implode(',',$request->nonwork_ill);
        //$nature_illness = implode(',',$request->non_work_nature);
        $nature_illness = '-';
      }
      else
      {
        $nonwork_related = '-';
       // $nature_illness = implode(',',$request->work_nature);
        $nature_illness = '-';
      }


      if($request->treatment == 'yes')
      {
        $treatmentdetails = implode(',',$request->treatments);
      }
      else
      {
        $treatmentdetails = '-';
      }

      $year = date('Y');

      $illdata = array(
                    'category_id'=>$request->category,
                    'illed_person_type'=>$person_type,
                    'e_number'=>$illempnumber,
                    'e_name'=>$person_name,
                    'dob'=>$illempdob,
                    'department'=>$illempdept,
                    'position'=>$illemppost,
                    'dateofillness'=>$doinj->format('d-m-Y'),
                    'timeofillness'=>$request->time,
                    'ill_type'=>$request->ill_type,
                    'workrelated_illness'=>$request->work_ill,
                    'nonworkrelated_illness'=>$nonwork_related,
                    'nonworkrelated_illness_other'=>$request->nonwork_oth,
                    //'natureofillness'=>$request->nature,
                    'natureofillness'=>$nature_illness,
                    'location'=>$request->location,
                    'causeofillness'=>$request->cause,
                    'symptoms'=>$request->symptom,
                    'things_involved'=>$request->thing,
                    'things_involved_details'=>$request->things,
                    'pandamic'=>$request->pandemic,
                    'pandamic_details'=>$request->pandemics,
                    'pandemic_contacts'=>$contact,
                    'witness'=>$witness,
                    'reported_to_supervisor'=>$request->supervisor,
                    'treatment_provided'=>$request->treatment,
                    'treatment_details'=>$treatmentdetails,
                    'treatmentdetails_other'=>$request->treatment_oth,
                    'illed_returnto_work'=>$request->returnwork,
                    'illed_returnto_work_details'=>$request->returnworks,
                    'creator_name'=>Auth::user()->name,
                    'creator_email'=>Auth::user()->email,
                    'creator_position'=>$creater_post,
                    'creator_department'=>$creater_dept,
                    'created_date'=>$request->createddate,
                    'status'=>$request->status,
                    'medical_leaves'=>$request->leaves,
                    'illed_bp_diastolic'=>$request->bloodpd,
                    'illed_bp_systolic'=>$request->bps,
                    'illed_bs_level'=>$request->bsl,
                    'remarks'=>$request->remark,
                    'pulse'=>$request->pulse,
                    'temperature'=>$request->temp,
                    'respiratory_rate'=>$request->respiratory_rate,
                    'weight'=>$request->weight,
                    'height'=>$request->height,
                    'year'=>$year,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

      );

      DB::table('employee_illness_record')->insert($illdata);

      //generating report number
        $data = DB::table('employee_illness_record')->latest('id')->first();
        $recentid = $data->id;
        $year = date('y');
        $reportnum = 'ILL'.$recentid.$year;
        DB::table('employee_illness_record')->where('id',$recentid)->update(['report_num' =>$reportnum]);

     //adding witness to employee_illness_witness table
        if(count($request->doc)>0)
        {
          for ($i=0;$i<count($request->doc);$i++)
          {
            if($request->doc[$i]['name']!=null && $request->doc[$i]['number']!=null)
            {
              DB::table('employee_illness_witness')->insert([
                                                      'created_date'=>$request->createddate,
                                                      'illed_per_enumber'=>$illempnumber,
                                                      'name'=>$request->doc[$i]['name'],
                                                      'number'=>$request->doc[$i]['number'],
                                                      'illness_id'=>$recentid,
                                                      'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                      'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    ]);
              $witness="Yes";
            }
          }
        }
        DB::table('employee_illness_record')->where('id',$recentid)->update(['witness' =>$witness]);

      //adding data to employee illness nonwork cases table
      if($request->ill_type == 'non_work_related')
      {
        foreach($request->nonwork_ill as $itm)
        {
          $cases = new EmployeeIllnessNonWorkCasesDetails;
          $cases->illness_id = $itm;
          $cases->illness_record_id = $recentid;
          $cases->employee_num = $illempnumber;
          $cases->dateofillness = $request->injdate;
          $cases->save();
         /* DB::table('illness_employee_test')->insert([
                                                      'illness_id'=>$itm,
                                                      'illness_record_id'=>$recentid,

                                                    ]);*/
        }
      }


//adding to treatmentillness details table
      if($request->treatment == 'yes')
      {
        foreach($request->treatments as $treat)
        {
           $treatments = new EmployeeIllnessTreatmentDetails;
           $treatments->treatment_id = $treat;
           $treatments->illness_record_id = $recentid;
           $treatments->save();

        }
      }

//adding data to employee_illness_nature_details table
  if($request->ill_type == 'non_work_related')
  {
    foreach($request->non_work_nature as $nonworknat)
    {
      DB::table('employee_illness_nature_details')->insert([
                                                    'illness_id'=>$recentid,
                                                    'illness_type'=>'non_work_related',
                                                    'natureofillness'=>$nonworknat,
                                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                  ]);
    }
  }
  else
  {
    foreach($request->work_nature as $worknat)
    {
      DB::table('employee_illness_nature_details')->insert([
                                                    'illness_id'=>$recentid,
                                                    'illness_type'=>'work_related',
                                                    'natureofillness'=>$worknat,
                                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                  ]);
    }

  }


  //adding data to employee illness pandemic details table
    if($request->pandemic=='yes' && count($request->contact)>0)
    {
      for ($i=0;$i<count($request->contact);$i++)
      {
        if($request->contact[$i]['name']!=null && $request->contact[$i]['eid']!=null)
        {
          DB::table('employee_illness_pandemic_contacts')->insert([
                                                          'illness_record_id' => $recentid,
                                                          'contact_emp_name' => $request->contact[$i]['name'],
                                                          'contact_emp_number' => $request->contact[$i]['eid'],
                                                          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                        ]);
        }
      }
    }

      Session::flash('msg','Illness record added successfully');
      return redirect()->back();

}

public function updateotherillnessrecord($id)
{
  $record=DB::table('employee_illness_record')->where('id',$id)->First();
  $creat_dept = $record->creator_department;
  if($creat_dept == NULL || $creat_dept == '-')
    {
      $creat_dep = '-';
    }
  else
   {
      $creat_dep = Departments::where('id',$creat_dept)->value('departmentname');
   }

  $ill_dep = Departments::where('id',$record->department)->value('departmentname');
  $witnesses=DB::table('employee_illness_witness')
                              ->where('illness_id',$id)
                              ->get();

  $healthcategory = HealthCategory::all();
  $treatment = HealthTreatment::all();
  $illnessnonwork = IllnessNonWorkrelatedcases::all();
  $empdetails=DB::table('employee_details')->get();
  $status = ['Cured','In Progress','Fatality','In Treatment'];
  $natureillness = EmployeeHealthIllnessNature::all();

  $models=DB::table('modenadisable')->First();
  return view('user.occupationhealth.updateotherillness')
                               ->with('witnesses',$witnesses)
                               ->with('empdetails',$empdetails)
                               ->with('model',$models)
                               ->with('creat_dep',$creat_dep)
                               ->with('ill_dep',$ill_dep)
                               ->with('healthcategory',$healthcategory)
                               ->with('treatment',$treatment)
                               ->with('illnessnonwork',$illnessnonwork)
                               ->with('status',$status)
                               ->with('natureillness',$natureillness)
                               ->with('record',$record);

}



public function updateconfirmotherillnessrecord(Request $request)
{
  $today=Carbon::now()->toDateTimeString();
  $today=Carbon::parse($today);
  $doill=Carbon::parse($request->illdate);

  if($request->illed_person_type=='contractor')
      {
        $person_name = $request->contractor_name_ill;
        $person_type = 'contractor';
      }
      else
      {
        $person_type ='other';
        $person_name = $request->injured_person_other_ill;
      }


      if($request->number==NULL)
      {
        $illempnumber= '-';
      }
      else
      {
        $illempnumber = $request->number;
      }

      if($request->dob==NULL)
      {
        $illempdob = '-';
      }
      else
      {
        $illempdob = $request->dob;
      }

      if($request->department==NULL)
      {
         $illempdept = '-';
      }
     else
      {
        $getilldepid = Departments::where('departmentname',$request->department)->value('id');
        $illempdept = $getilldepid;
      }

     if($request->position==NULL)
      {
         $illemppost = '-';
      }
     else
      {
        $illemppost = $request->position;
      }

  if($request->ill_type == 'non_work_related')
  {
    $nonwork_related = implode(',',$request->nonwork_ill);
    $workrelated_illness = NULL;

    if(is_array($request->non_work_nature))
      {
        //$nature_illness = implode(',',$request->non_work_nature);
        $nature_illness = '-';
      }
    else
     {
       $nature_illness = $request->non_work_nature;
     }
  }
  else
  {
    $nonwork_related = '-';
    $workrelated_illness = $request->work_ill;

    if(is_array($request->work_nature))
      {
        //$nature_illness = implode(',',$request->work_nature);
        $nature_illness = '-';
      }
    else
     {
       $nature_illness = $request->work_nature;
     }
  }

  $nonwork_arr = explode(',',$nonwork_related);
  if($request->ill_type == 'non_work_related' && collect($nonwork_arr)->contains('other'))
    {
      $nonworkrelated_illness_other = $request->nonwork_oth;
    }
  else
   {
    $nonworkrelated_illness_other = NULL;
   }

   if($request->thing=='yes')
   {
     $things_involved_details = $request->things;
   }
  else
   {
    $things_involved_details = NULL;
   }


  if($request->treatment == 'yes')
  {
    $treatmentdetails = implode(',',$request->treatments);
  }
  else
  {
    $treatmentdetails = '-';
  }


  $treatment_arr = explode(',',$treatmentdetails);
  if($request->treatment == 'yes' && collect($treatment_arr)->contains('other'))
  {
    $treatmentdetails_other = $request->treatment_oth;
  }
 else
  {
    $treatmentdetails_other = NULL;
  }

  if($request->returnwork=='yes')
  {
    $illed_returnto_work_details = $request->returnworks;
  }
 else
  {
    $illed_returnto_work_details = NULL;
  }


  DB::table('employee_illness_record')->where('id',$request->recid)
                                                             ->update([
                                                                        'illed_person_type'=>$person_type,
                                                                        'e_number'=>$illempnumber,
                                                                        'e_name'=>$person_name,
                                                                        'dob'=>$illempdob,
                                                                        'department'=>$illempdept,
                                                                        'position'=>$illemppost,
                                                                        'category_id'=>$request->category,
                                                                        'dateofillness'=>$doill->format('d-m-Y'),
                                                                        'timeofillness'=>$request->illtime,
                                                                        'ill_type'=>$request->ill_type,
                                                                        //'workrelated_illness'=>$request->work_ill,
                                                                        'workrelated_illness'=>$workrelated_illness,
                                                                        'nonworkrelated_illness'=>$nonwork_related,
                                                                        'nonworkrelated_illness_other'=>$nonworkrelated_illness_other,
                                                                        //'natureofillness'=>$request->nature,
                                                                        'natureofillness'=>$nature_illness,
                                                                        'location'=>$request->location,
                                                                        'causeofillness'=>$request->cause,
                                                                        'symptoms'=>$request->symptom,
                                                                        'things_involved'=>$request->thing,
                                                                        //'things_involved_details'=>$request->things,
                                                                        'things_involved_details'=>$things_involved_details,
                                                                        'reported_to_supervisor'=>$request->supervisor,
                                                                        'treatment_provided'=>$request->treatment,
                                                                        'treatment_details'=>$treatmentdetails,
                                                                        'treatmentdetails_other'=>$treatmentdetails_other,
                                                                        'reported_to_supervisor'=>$request->supervisor,
                                                                        'illed_returnto_work'=>$request->returnwork,
                                                                        'illed_returnto_work_details'=>$illed_returnto_work_details,
                                                                        'medical_leaves'=>$request->leaves,
                                                                        'status'=>$request->status,
                                                                        'illed_bp_diastolic'=>$request->bloodpd,
                                                                        'illed_bp_systolic'=>$request->bps,
                                                                        'illed_bs_level'=>$request->bsl,
                                                                        'remarks'=>$request->remark,
                                                                        'pulse'=>$request->pulse,
                                                                        'temperature'=>$request->temp,
                                                                        'respiratory_rate'=>$request->respiratory_rate,
                                                                        'weight'=>$request->weight,
                                                                        'height'=>$request->height,
                                                                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                                                                      ]);
  $emp=DB::table('employee_illness_record')->where('id',$request->recid)->First();



 /**--adding data to employee illness nonwork cases table-*/
  if($request->ill_type == 'non_work_related')
  {
    EmployeeIllnessNonWorkCasesDetails::where('illness_record_id',$request->recid)->delete();
    foreach($request->nonwork_ill as $itm)
    {
      $cases = new EmployeeIllnessNonWorkCasesDetails;
      $cases->illness_id = $itm;
      $cases->illness_record_id = $request->recid;
      $cases->employee_num = $illempnumber;
      $cases->dateofillness = $request->illdate;
      $cases->save();
    }
  }

//adding data to treatmentillness details table
if($request->treatment == 'yes')
{
  EmployeeIllnessTreatmentDetails::where('illness_record_id',$request->recid)->delete();
  foreach($request->treatments as $treat)
  {
      $treatments = new EmployeeIllnessTreatmentDetails;
      $treatments->treatment_id = $treat;
      $treatments->illness_record_id = $request->recid;
      $treatments->save();
  }
}


  //adding data to employee_illness_nature_details table
  if($request->ill_type == 'non_work_related' && is_array($request->non_work_nature))
  {
    DB::table('employee_illness_nature_details')->where('illness_id',$request->recid)->delete();
    foreach($request->non_work_nature as $nonworknat)
    {
      DB::table('employee_illness_nature_details')->insert([
                                                    'illness_id'=>$request->recid,
                                                    'illness_type'=>'non_work_related',
                                                    'natureofillness'=>$nonworknat,
                                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                  ]);
    }
  }
  elseif($request->ill_type == 'work_related' && is_array($request->work_nature))
  {
    DB::table('employee_illness_nature_details')->where('illness_id',$request->recid)->delete();
    foreach($request->work_nature as $worknat)
    {
      DB::table('employee_illness_nature_details')->insert([
                                                    'illness_id'=>$request->recid,
                                                    'illness_type'=>'work_related',
                                                    'natureofillness'=>$worknat,
                                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                  ]);
    }

  }



  Session::flash('msg','Employee Illness Record Updated Successfully');
  return redirect()->back();
}

public function deleteothercasesrecord()
{
  if (isset($_GET['val']))
    {
        $id=$_GET['val'];

        $injury_rec = DB::table('employee_injury_record')->where('id',$id)->get();
        $illness_rec = DB::table('employee_illness_record')->where('id',$id)->get();

        if(count($injury_rec)>0)
        {
          $empnum = DB::table('employee_injury_record')->where('id',$id)->value('e_number');
          $workcasesexist =  DB::table('employee_injury_work_related_cases')->where('injury_record_id',$id)->get();
          if(count($workcasesexist) > 0)
          {
            DB::table('employee_injury_work_related_cases')->where('injury_record_id',$id)->delete();
          }

          $treatmentexist = DB::table('employee_injury_treatment_details')->where('injury_record_id',$id)->get();
          if(count($treatmentexist)>0)
          {
            DB::table('employee_injury_treatment_details')->where('injury_record_id',$id)->delete();
          }

          $natureexist = DB::table('employee_injury_nature_details')->where('injury_id',$id)->get();
          if(count($natureexist)>0)
          {
            DB::table('employee_injury_nature_details')->where('injury_id',$id)->delete();
          }

          DB::table('employee_injury_record')->where('id',$id)->delete();

          //inserting data to activities table
          $activity="An Injury Record entry for Employee ID ".$empnum." is removed";
          $date=Carbon::now()->toDateTimeString();
          $date=Carbon::parse($date);
          $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'admin');
          DB::table('viewactivity')->insert($datas);

        }
        elseif(count($illness_rec)>0)
        {
            $empnum = DB::table('employee_illness_record')->where('id',$id)->value('e_number');

            $nonworkcasesexist =  DB::table('employee_illness_non_work_cases_details')->where('illness_record_id',$id)->get();
            if(count($nonworkcasesexist) > 0)
            {
              DB::table('employee_illness_non_work_cases_details')->where('illness_record_id',$id)->delete();
            }

            $treatmentexist = DB::table('employeeillness_treatmentdetails')->where('illness_record_id',$id)->get();
            if(count($treatmentexist)>0)
            {
              DB::table('employeeillness_treatmentdetails')->where('illness_record_id',$id)->delete();
            }

            $natureexist = DB::table('employee_illness_nature_details')->where('illness_id',$id)->get();
            if(count($natureexist)>0)
            {
              DB::table('employee_illness_nature_details')->where('illness_id',$id)->delete();
            }

            DB::table('employee_illness_record')->where('id',$id)->delete();

            // inserting data to activities table
            $activity="An Illness Record entry for Employee ID ".$empnum." is removed";
            $date=Carbon::now()->toDateTimeString();
            $date=Carbon::parse($date);
            $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'admin');
            DB::table('viewactivity')->insert($datas);
        }


      //$models=DB::table('modenadisable')->First();
      //return redirect('/user/occupationhealth/othercases')->with('model',$models)->with('msg',' Record  Deleted Successfully');
      return response()->json([
                                'status'=>200,
                                'data'=>null
                              ]);
    }

}



}
