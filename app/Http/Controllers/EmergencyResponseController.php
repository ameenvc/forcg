<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;


use Auth;
use DB;
use Session;
use Carbon\Carbon;
use App\User;
use App\Departments;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Http\Requests\EmergencyResponse\AddEmergencyRequest;
use App\Http\Requests\EmergencyResponse\AddCreateReport;
use App\Mail\CorrectiveAddIssue;
use App\Mail\CorrectiveResponseNotification;
use App\Mail\CorrectiveIssueAwaitingApproval;
use App\EmergencyResponseSituation;
use App\EmergencyResponseDetails;
use App\EmployeeResponseDrillTeamDetails;
use App\EmergencyResponseMitigationDetail;
use App\EmergencyResponseProblemDetail;
use App\Location;
use App\EmployeeDetails;
use App\EmergencyDrillReportDetail;
use App\EmergencyDrillReportImage;
use App\EmergencyResponseDrillReportCorrective;

use App\Categories;
use App\Corrective;
use App\Type;

class EmergencyResponseController extends Controller
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

      public function register($msg=null)
      {
         //below is superadmin related codes
         $emergencyresponse = DB::table('modpageload')->where('id', 1)->get();
         if (sizeof($emergencyresponse) == 0)
         {
             DB::table('modpageload')->insert(['id' => 1, 'emergencyresponse' => 1]);
         }
         else
         {
             $emergencyresponse = DB::table('modpageload')->where('id', 1)->First();
             $emergencyresponse->emergencyresponse = $emergencyresponse->emergencyresponse + 1;
             DB::table('modpageload')->update(['emergencyresponse' => $emergencyresponse->emergencyresponse]);
         }
         //end of superadmin codes

         $datas = EmergencyResponseDetails::latest('created_at')->get();
         $models = DB::table('modenadisable')->First();
         if($msg==null){
            return view('user.emergencyresponse.register')
                                        ->with('datas', $datas)
                                        ->with('model', $models);
         }
         elseif($msg=='1'){
            $message = 'Drill Report created successfully';
            return redirect()->route('emergency.register')
                                        ->with('datas', $datas)
                                        ->with('message', $message)
                                        ->with('model', $models);
         }
      }

/**------------Add emergency response---------------*/
    public function addemergencyresponse()
    {
       $users = User::where('approved',1)->get();
       $models = DB::table('modenadisable')->First();
       $departments = DB::table('departments')->get();
       $emergency = EmergencyResponseSituation::all();
       $locations = Location::all();
       $frequencies = ['Monthly','Quartely','Half-Yearly','Yearly'];
       $empdetails = EmployeeDetails::all();
       return view('user.emergencyresponse.addemergencyresponse')
                                      ->with('users',$users)
                                      ->with('departments',$departments)
                                      ->with('locations',$locations)
                                      ->with('emergencies',$emergency)
                                      ->with('frequencies',$frequencies)
                                      ->with('empdetails',$empdetails)
                                      ->with('model', $models);
    }

    public function displayemergencysituationsajax(Request $request)
    {
        $data_id = $request->input('data_id');
        $situation_name = EmergencyResponseSituation::where('id',$data_id)->value('name');
        $description = EmergencyResponseSituation::where('id',$data_id)->value('description');

        return response()->json([
                'status'=>200,
                'situation_name'=>$situation_name,
                'description'=>$description,
                'emergency_id'=>$data_id,
            ]);

    }

    public function insertemergencydetails(AddEmergencyRequest $request)
    {
        $drill_team = implode(',',$request->drill_team);

        $admins = DB::table('admins')->where('email',Auth::user()->email)->first();
        if($admins)
        {
            $addedby_id = DB::table('users')->where('email',Auth::user()->email)->value('id');
        }
        else
        {
            $addedby_id = Auth::user()->id;
        }

        $nextdate = $request->next_drill;
        if($request->frequency=='Monthly')
        {
            $due = strtotime("+30 days",strtotime($nextdate));
            $duedate= date("Y-m-d",$due);
        }
        else if($request->frequency=='Quartely')
        {
            $due = strtotime("+3 months",strtotime($nextdate));
            $duedate= date("Y-m-d",$due);
        }
        else if($request->frequency=='Half-Yearly')
        {
            $due = strtotime("+6 months",strtotime($nextdate));
            $duedate= date("Y-m-d",$due);
        }
        else if($request->frequency=='Yearly')
        {
            $due = strtotime("+12 months",strtotime($nextdate));
            $duedate= date("Y-m-d",$due);
        }

        $status='Scheduled';
        $data = array(
                      'department_id'=> $request->department,
                      'location_id'=>$request->location,
                      'emergency_situation_name'=>$request->situation_name,
                      'emergency_situation_desp'=>$request->description,
                      'frequency'=>$request->frequency,
                      'drill_team_id'=>$drill_team,
                      'drill_date_last'=>$request->last_drill,
                      'drill_date_next'=>$nextdate,
                      'added_by'=>$addedby_id,
                      'duedate'=>  $duedate,
                      'status'=>$status
                     );

        $emrespdetails =  EmergencyResponseDetails::create($data);

       //inserting data into emergency_response_drill_team_details table
        foreach($request->drill_team as $item)
        {
            $drillteams = new EmployeeResponseDrillTeamDetails;
            $drillteams->emergency_response_details_id = $emrespdetails->id;
            $drillteams->drill_team_id = $item;
            $drillteams->save();
        }

        $models = DB::table('modenadisable')->First();
        return redirect('user/emergencyresponse/register')
                                                ->with('message', 'Emergency Response Created Successfully')
                                                ->with('model', $models);
}

    public static function statusdue($due_date)
     {
        $today=date('Y-m-d');
        $status='Due';
        if($due_date<$today){
            EmergencyResponseDetails::where('duedate',$due_date)->update(['duedate'=>$due_date,'status'=>$status]);
        }
    }

    public static function statusscheduled($due_date)
    {
        $today=date('Y-m-d');
        $status='Scheduled';
        if($due_date>$today){
            EmergencyResponseDetails::where('duedate',$due_date)->update(['duedate'=>$due_date,'status'=>$status]);
        }
    }

    public static function statusneardue($due_date)
    {
        $today=date('Y-m-d');
        $neartoexpiry=date('Y-m-d',strtotime($due_date. ' - 30 days'));
        if($neartoexpiry<=$today){
            $status='Near to Due';
            EmergencyResponseDetails::where('duedate',$due_date)->update(['duedate'=>$due_date,'status'=>$status]);
        }
    }


/**------------Create drill report--------*/
    public function createdrillreport($id,$serialnum)
    {
        $datas = EmergencyResponseDetails::where('id',$id)->first();
        //$situation_name = EmergencyResponseSituation::where('id',$datas->emergency_situation_id)->value('name');
        $location_name = Location::where('id',$datas->location_id)->value('name');
        $empdetails = EmployeeDetails::all();
        $situationstarts = ['Before Business Hour','During Business Hour','Peak Time',
                            'Break Down Time','Shut Down Time','Lunch Break','After Business Hour'
        ];

        $weather = ['Clear','Cloudy','Raining','Windy','Flood',
                    'Hot Climate (Temp less than 400 C)','Cold (Temp Less than 250 C)'
                   ];

        $problems = EmergencyResponseProblemDetail::all();
        $mitigations = EmergencyResponseMitigationDetail::all();

        $models = DB::table('modenadisable')->First();
        return view('user.emergencyresponse.adddrillreport')
                                                //->with('situation_name', $situation_name)
                                                ->with('location_name', $location_name)
                                                ->with('empdetails',$empdetails)
                                                ->with('situationstarts',$situationstarts)
                                                ->with('weather',$weather)
                                                ->with('problems',$problems)
                                                ->with('mitigations',$mitigations)
                                                ->with('datas',$datas)
                                                ->with('serialnum',$serialnum)
                                                ->with('model', $models);
    }

    public function adddrillreportimage()
    {
        $models = DB::table('modenadisable')->First();
        return view('user.emergencyresponse.addreportimages')
                                              ->with('model', $models);
    }

    public function fileStore(Request $request)
    {
        $image = $request->file('file');
        $imageName = $image->getClientOriginalName();
        $destination_path = public_path('storage/emergency');
        $image->move($destination_path,$imageName);

        //inserting image into emergencydrillreportimages table
        $data = EmergencyDrillReportDetail::latest('id')->first();
        $imageUpload = new EmergencyDrillReportImage;
        $imageUpload->drill_report_id = $data->id;
        $imageUpload->drill_images = $imageName;
        $imageUpload->save();

        return response()->json(['success'=>$imageName]);
    }

    public function fileDestroy(Request $request)
    {
        $filename =  $request->get('filename');
        EmergencyDrillReportImage::where('drill_images',$filename)->delete();
        return $filename;
    }

   public function insertreportdetails(AddCreateReport $request)
   {
        $mainid = $request->mainid;
        if($request->drill_team=='other'){
            $drill_team_value = 'other';
        }
        else{
            $drill_team_value = implode(',',$request->drill_team);
        }

        if($request->situation=='other'){
            $situation = 'other';
        }
        else{
            $situation = implode(',',$request->situation);
        }

        if($request->weather=='other'){
            $weather = 'other';
        }
        else{
            $weather = implode(',',$request->weather);
        }

        if($request->problems=='other'){
            $problems = 'other';
        }
        else{
            $problems = implode(',',$request->problems);
        }

        if($request->mitigation=='other'){
            $mitigation = 'other';
        }
        else{
            $mitigation = implode(',',$request->mitigation);
        }

        $admins = DB::table('admins')->where('email',Auth::user()->email)->first();
        if($admins)
        {
            $addedby_id = DB::table('users')->where('email',Auth::user()->email)->value('id');
        }
        else
        {
            $addedby_id = Auth::user()->id;
        }

        //generate report number
            // $exists = EmergencyDrillReportDetail::where('emergency_details_id',$mainid)->get();
            // if(count($exists)>0)
            // {
            //     $data_chk= EmergencyDrillReportDetail::where('emergency_details_id',$mainid)->latest('id')->first();
            //     $count_value = $data_chk->count;
            //     $new_count = $count_value+1;
            // }
            // else
            // {
            //     $new_count = 1;
            // }
            // $year = Date('y');
            // $report_num = $request->mainserialnum.'000'.$new_count.$year;


            $exists = EmergencyDrillReportDetail::all();
            if(count($exists)>0)
            {
                $data_chk= EmergencyDrillReportDetail::latest('id')->first();
                $count_value = $data_chk->count;
                $new_count = $count_value+1;
            }
            else
            {
                $new_count = 1000;
            }
            $year = Date('y');
            $report_num = $new_count.$year;


        $data = array(
                        'emergency_details_id'=>$mainid,
                        'drill_type'=> $request->drill_type,
                        'location_id'=>$request->location_id,
                        'drill_date'=>$request->drill_date,
                        'time_alarm_sounded'=>$request->time_alarm,
                        'time_drill_conducted'=>$request->time_drill,
                        'time_evacuated'=>$request->evacuate_time,
                        'drill_team'=>$drill_team_value,
                        'drill_team_other'=>$request->drill_tem_other,
                        'drill_start_situation'=>$situation,
                        'drill_start_situation_other'=>$request->situation_other_value,
                        'weather'=>$weather,
                        'weather_other'=>$request->weather_other_value,
                        'participants_number'=>$request->participants,
                        'employees_working_area_num'=>$request->employees_work_area,
                        'evacuation_count'=>$request->evacuation_cnt,
                        'problem_encountered'=>$problems,
                        'problem_encountered_other'=>$request->problem_other_value,
                        'evacuate_immediately'=>$request->evacuate_immediate,
                        'checked_confined_areas'=>$request->safety_check,
                        'emergency_informed'=>$request->personnel_informed,
                        'equipment_function'=>$request->equipment_function,
                        'evacuated_all'=>$request->evacuate_workareas,
                        'remain_outside'=>$request->remain_outside,
                        'emergency_team_knowledgeable'=>$request->knowledge_duties,
                        'drill_conducted_orderly'=>$request->drill_orderly,
                        'evacuation_speed'=>$request->evacuation_speed,
                        'procedure_effectiveness'=>$request->procedure_effective,
                        'drill_communication'=>$request->communication,
                        'drill_rating'=>$request->drill_rating,
                        'mitigation'=>$mitigation,
                        'mitigation_other'=>$request->mitigation_other_value,
                        'comments'=>$request->comments,
                        'created_by'=>$addedby_id,
                        'report_number'=>$report_num,
                        'count'=>$new_count
                    );
            EmergencyDrillReportDetail::create($data);
            $models = DB::table('modenadisable')->First();
                                            return redirect('user/emergencyresponse/addrillreportimage/')
                                            ->with('model', $models);

}

/**------------View Drill report-------*/
   public function viewdrillreport($id)
   {
        $datas = EmergencyDrillReportDetail::where('emergency_details_id',$id)->get();
        $models = DB::table('modenadisable')->First();
        return view('user.emergencyresponse.viewdrillreports')
                                                ->with('datas', $datas)
                                                ->with('model', $models);
   }


   public function viewdetailedreport($id)
   {
      $reports = EmergencyDrillReportDetail::where('id',$id)->first();
      $drillimages = EmergencyDrillReportImage::where('drill_report_id',$id)->get();
      $correctives = Corrective::where('emergency_drill_report_id',$id)->get();
      $models = DB::table('modenadisable')->First();
      return view('user.emergencyresponse.viewdetailedreport')
                                                ->with('reports', $reports)
                                                ->with('drillimages', $drillimages)
                                                ->with('correctives', $correctives)
                                                ->with('model', $models);

   }


/**------------Addcorrective - Drill report-------*/
 public function addcorrectivedrillreport($id)
  {
     $datas = EmergencyDrillReportDetail::where('id', $id)->first();
     $departments = DB::table('departments')->get();
     $types = Type::all();
     $users = User::all();
     $models = DB::table('modenadisable')->first();
     return view('user.emergencyresponse.addcorrectivedrillreport')
                                         ->withDepartments($departments)
                                         ->withTypes($types)
                                         ->withUsers($users)
                                         ->with('datas', $datas)
                                         ->with('model', $models);
  }

public function insertcorrectivedrillreport(Request $request)
  {
       $cer = 'NULL';
        $agreedaction = 'NULL';
        if ($request->hasFile('issue_file')) {
            $file_array = $request->file('issue_file');
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $file_ext = $file_array[$i]->getClientOriginalExtension();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/issue_files');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
            }
            $cer = implode(',', $file);
        }

        $issuereviewers = DB::table('issuereviewers')->get();
        $representatives = DB::table('representatives')->get();

        $list = collect([]);
        foreach ($issuereviewers as $issuereviewer) {
            $list = $list->push($issuereviewer->email);
        }
        foreach ($representatives as $representative) {
            $list = $list->push($representative->email);
        }

        $reviewers = $list->unique();
        foreach ($reviewers as $reviewer) {
            if (Auth::user()->email == $reviewer) {
                $approvement = "yes";
                $processstage = "Approved";
                $status = "open";
                $approvedby = Auth::user()->employeeid;
                break;
            }
            if (Auth::user()->email != $reviewer) {
                $approvement = "no";
                $processstage = "Under Review";
                $status = "NULL";
                $approvedby = 'NULL';
            }
        }
            $drill_report_id = $request->input('drill_report_id');
            $category = $request->input('category');
            $type    = $request->input('type');
            $department = $request->input('department');
            $title   =  $request->input('title');
            $observation = $request->input('observation');
            $agreedaction =  $request->input('agreedaction');
            $due_date =  $request->input('duedate');
            if ($request->frequency) {
                $freq = $request->input('frequency');
                $frequency = implode(',', $freq);
            } else $frequency = NULL;
            $responsibility = $request->input('responsibility');
            if ($request->notification) {
                $notify = $request->input('notification');
                $notification = implode(',', $notify);
            } else $notification = '';
            $postedby = $request->input('postedby');
            $name = DB::table('users')->where('employeeid', $postedby)->get();

            // $exist = DB::table('corrective')->where('category', $category)->orderBy('id', 'desc')->first();
            // $full_year = Date("Y");
            // $year = substr($full_year, -2);
            // if($exist)
            // {
            //     $exist_issueid = $exist->issueid;
            //     $str_issueid = substr($exist_issueid, -2);
            //     if ($str_issueid == $year)
            //     {
            //         $existing_count = $exist->count;
            //         $new_count = $existing_count + 1;
            //     }
            //     else
            //     {
            //         $new_count = 1;
            //     }
            // }
            // else
            // {
            //     $new_count = 1;
            // }

            //issue of each entry numbering will be refreshed yearly
            //$code = strtoupper(substr($category, 0, 3));
            //$issueid = $code . $new_count . "/" . $year;
            //$issueid = $request->drill_number.$new_count;
            $exist = EmergencyResponseDrillReportCorrective::where('drill_report_number',$request->drill_number)
                ->latest('id')
                ->first();
            if($exist)
            {
                $dcount = $exist->count;
                $newdcount = $dcount + 1;
            }
            else{
                $newdcount = 1;
            }
            //------------------------------------Id generation part start ------------------------------------------
            // Retrieve all records for today's date
            $today = date('Y-m-d');
            $todayLatestCorrective = Corrective::whereDate('created_at', $today)->orderByDesc('created_at')->first();
            if ($todayLatestCorrective) {
                $lastFormatValue = intval(substr($todayLatestCorrective->issueid, -4));
                $formatValue = str_pad($lastFormatValue + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $formatValue = "0001";
            }
            do {
                $issueId = 'CA-' . date('Ymd') . '-' . $formatValue;
                $formatValue = str_pad(intval($formatValue) + 1, 4, '0', STR_PAD_LEFT);
            } while (Corrective::where('issueid', $issueId)->exists());
            //------------------------------------Id generation part end ------------------------------------------
            $postdate = date('d-m-Y h:i:s A', time());
            $review_date = date('Y-m-d', strtotime($postdate));


            //inserting data to corrective table
            $corrective_data = array(
                            'count' => $newdcount,
                            'issueid'=>$issueId,
                            'category' => $category,
                            'type' => $type,
                            'department' => $department,
                            'title' => $title,
                            'file' => $cer,
                            'observation' => $observation,
                            'duedate' => $due_date,
                            'frequency' => $frequency,
                            'agreedaction' => $agreedaction,
                            'postedby' => $postedby,
                            'responsibility' => $responsibility,
                            'notification' => $notification,
                            'approvement' => $approvement,
                            'processstage' => $processstage,
                            'approvedby' => $approvedby,
                            'status' => $status,
                            'status' => $status,
                            'postdate' => $postdate,
                            'emergency_drill_report_id'=>$drill_report_id,
                            'Monthlyfreq' => $review_date,
                            'Weeklyfreq' => $review_date
                        );
            DB::table('corrective')->insert($corrective_data);

            //fetching id from corrective table
            $correct_id = DB::table('corrective')->where('issueid', $issueId)->value('id');

            //inserting data to management_review_schedule_correctives table
            $data1 = [
                        'corrective_id'=>$correct_id,
                        'drill_report_id'=>$drill_report_id,
                        'drill_report_number'=>$request->drill_number,
                        'count'=>$newdcount
                     ];

                     EmergencyResponseDrillReportCorrective::create($data1);

           //inserting data to multiplecorr_issues table
            $new_files = explode(',', $cer);
            foreach ($new_files as $new_file) {
                $data2  = array('id' => $correct_id, 'issue_file' =>  $new_file);
                DB::table('multiplecorr_issues')->insert($data2);
            }

            $depname = Departments::where('id',$department)->value('departmentname');
            $user_exists= DB::table('users')->where('employeeid', $postedby)->first();

            if($user_exists)
            {
                $user_name = DB::table('users')->where('employeeid', $postedby)->value('name');
            }
            else
            {
                $user_name = Auth::user()->name;
            }

       //sending mail
            $check = 0;
            foreach ($reviewers as $reviewer) {
                if (Auth::user()->email == $reviewer) {
                    $data = array(
                        'issueid' => $issueId,
                        'name' => $user_name,
                        'type' =>  $type,
                        'department' =>  $depname,
                        'duedate' => date("d/m/Y", strtotime($due_date))
                    );

                   Mail::to($responsibility)->send(new CorrectiveResponseNotification($data));

                   $notificationemails = explode(',', $notification);
                   foreach ($notificationemails as $notificationemail) {
                       if ($notificationemail != "") {
                           Mail::to($notificationemail)->send(new CorrectiveAddIssue($data));
                       }
                   }
                   $check = 1;
                }
            }
                if ($check == 0) {
                    $data = array(
                                    'issueid' => $issueId,
                                    'name' => $user_name,
                                    'type' =>  $type,
                                    'department' =>  $depname,
                                    'duedate' => date("d/m/Y", strtotime($due_date))
                                 );

                $allissuereviewers = DB::table('issuereviewers')->pluck('email');
                    foreach ($allissuereviewers as $allissuereviewer) {
                       Mail::to($allissuereviewer)->send(new CorrectiveIssueAwaitingApproval($data));
                    }
                }

            $main_emergency_id = $request->main_emergency_details_id;

            $models = DB::table('modenadisable')->First();
            return redirect('user/emergencyresponse/viewdrillreport/'.$main_emergency_id)
                                                                                  ->with('message', 'Action plans added successfully')
                                                                                  ->with('model', $models);

   }



}

