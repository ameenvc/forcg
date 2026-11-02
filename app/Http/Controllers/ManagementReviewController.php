<?php

namespace App\Http\Controllers;

use App\Categories;
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
use App\Http\Requests\ManagementReview\AddManagementReviewRequest;
use App\Http\Requests\ManagementReview\AddCorrectiveRequest;
use App\ManagementAgendaType;
use App\ManagementAgendaPoint;
use App\Mail\ManagementReviewAdd;
use App\Mail\ManagementReviewMeetingCancelled;
use App\Mail\CorrectiveAddIssue;
use App\Mail\CorrectiveResponseNotification;
use App\Mail\CorrectiveIssueAwaitingApproval;
use App\Mail\ManagementReviewMeetingChangeSchedule;
use App\ManagementAgendaPointList;
use App\ManagementReviewScheduleNote;
use App\ManagementLocation;
use App\ManagementReviewSchedule;
use App\ManagementReviewScheduleAgendaType;
use App\ManagementReviewScheduleParticipant;
use App\ManagementReviewScheduleManagementParticipant;
use App\Type;
use App\Issuereviewer;
use App\ManagementReviewScheduleCorrective;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use App\Corrective;

class ManagementReviewController extends Controller
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

     public function meetings()
     {
        //below is superadmin related codes
        $managementreview = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($managementreview) == 0)
        {
            DB::table('modpageload')->insert(['id' => 1, 'managementreview' => 1]);
        }
        else
        {
            $managementreview = DB::table('modpageload')->where('id', 1)->First();
            $managementreview->managementreview = $managementreview->managementreview + 1;
            DB::table('modpageload')->update(['managementreview' => $managementreview->managementreview]);
        }
        //end of superadmin codes


        $datas = ManagementReviewSchedule::latest('created_at')->get();
        $yearvalues = ManagementReviewSchedule::select(DB::raw('YEAR(date) AS year'))->distinct()->get()->toArray();
        $year_arr = array();
        foreach($yearvalues as $yearvalue)
        {
            array_push($year_arr,$yearvalue['year']);
        }
        sort($year_arr);
        $years = implode(',', array_unique($year_arr));

        foreach($datas as $data)
        {
            switch ($data->status) {
                case 'Scheduled':
                    $data->status_class = 'badge badge-warning';
                break;
                case 'Cancelled':
                    $data->status_class = 'badge badge-danger';
                break;
                case 'Completed':
                    $data->status_class = 'badge badge-success';
                break;
            }
        }
        $models = DB::table('modenadisable')->First();
        return view('user.managementreview.meetings')
                                       ->with('datas', $datas)
                                       ->with('years',$years)
                                       ->with('model', $models);
     }


/**--Add management review schedule--*/

     public function addmanagementreview()
     {
        $users = User::where('approved',1)->get();
        $models = DB::table('modenadisable')->First();
        $types = ManagementAgendaType::all();
        $points = ManagementAgendaPoint::all();
        $locations = ManagementLocation::all();
        return view('user.managementreview.addmanagementreview')
                                       ->with('users',$users)
                                       ->with('types',$types)
                                       ->with('points',$points)
                                       ->with('locations',$locations)
                                       ->with('model', $models);
     }


    public function selectagenda(Request $request)
    {
        $type_values = implode(',',$request->type_value);

        $data_value = ManagementAgendaPoint::where('agenda_type',$type_values)->value('points');
        $agenda_id = ManagementAgendaPoint::where('agenda_type',$type_values)->value('id');

        $data_arrays = explode("\n", $data_value);

        $data = "<ul>";
        foreach($data_arrays as $data_array)
        {
          $data .= "<li class='agenda_style'>" . $data_array . "</li>";
        }
        $data .= "</ul>";

        return response()->json([
            'status'=>200,
            'data'=>$data,
            'agenda_id'=>$agenda_id,
        ]);
    }

    public function selectmodifyagendadata(Request $request)
    {
        $id = $request->input('datasid');
        $detail_type = ManagementAgendaPoint::where('id',$id)->value('detail_type');
        $points=  ManagementAgendaPointList::where('agenda_point_detail_type',$detail_type)->get();
        return response()->json([
            'status'=>200,
            'data'=>$points,
            'main_point_id'=>$id,
            'detail_type'=>$detail_type
        ]);
    }

    public function updateagendaajax(Request $request)
    {
        $main_id = $request->input('agenda_points_id');
        $points_arr = $request->input('points');
        $point_string = implode("\n", $points_arr);

        ManagementAgendaPoint::where('id', $main_id)->update([
            'points' => $point_string,
            'updated_by'=>Auth::user()->email
            ]);

        foreach ($request->agendaid as $key => $value) {
                $pointsvaluedb = DB::table('management_agenda_point_lists')->where('id', $value)->get();
                foreach ($pointsvaluedb as $pointvaluedb) {
                  $data = array(
                      'points' => $request->points[$key],
                 );

                 DB::table('management_agenda_point_lists')
                          ->where('agenda_point_detail_type', $request->detail_type)
                          ->where('id', $request->agendaid[$key])
                          ->update($data);
                }
      }

        return response()->json([
            'status'=>200,
            'data'=>null,
        ]);
    }

    public function insertmanagementreview(AddManagementReviewRequest $request)
    {
        $types = $request->agenda_type;
        $typeids = array();
        foreach($types as $type)
        {
            $agendaids = ManagementAgendaType::where('name',$type)->value('id');
            array_push($typeids,$agendaids);
        }

        $agenda_type = implode(',',$typeids);
        $participants = implode(',',$request->participants);
        $management_part = implode(',',$request->management_participants);

        $admins = DB::table('admins')->where('email',Auth::user()->email)->first();
        if($admins)
        {
            $addedby_id = DB::table('users')->where('email',Auth::user()->email)->value('id');
        }
        else
        {
            $addedby_id = Auth::user()->id;
        }

        $year = Date('Y');

        $detail_type = ManagementAgendaPoint::where('id',$request->agenda)->value('detail_type');

        $data = array(
                        'date'=>$request->date,
                        'time'=>$request->time,
                        'location'=>$request->location,
                        'agenda_type'=>$agenda_type,
                        'agenda_points'=>$request->agenda,
                        'agenda_point_detail_type'=>$detail_type,
                        'participants'=>$participants,
                        'management_participants'=>$management_part,
                        'management_participants_other' =>$request->management_participants_oth,
                        'management_participants_other_email' =>$request->management_participants_oth_email,
                        'added_by'=>$addedby_id,
                        'status'=>'Scheduled',
                     );
        ManagementReviewSchedule::create($data);

        $recentdata = ManagementReviewSchedule::latest('id')->first();
	    $recentid = $recentdata->id;

        //inserting data into management schedule agenda types table
        foreach($typeids as $item)
        {
            $agendatypes = new ManagementReviewScheduleAgendaType;
            $agendatypes->schedule_id = $recentid;
            $agendatypes->agenda_type_id = $item;
            $agendatypes->save();
        }

       //inserting data into management schedule participants table
       foreach($request->participants as $item)
       {
           $participants = new ManagementReviewScheduleParticipant;
           $participants->schedule_id = $recentid;
           $participants->participants_id = $item;
           $participants->save();
       }

      //inserting data into management schedule management participants table
      foreach($request->management_participants as $item)
      {
          $mparticipants = new ManagementReviewScheduleManagementParticipant;
          $mparticipants->schedule_id = $recentid;
          $mparticipants->manage_participant_id = $item;
          $mparticipants->save();
      }

      //sending mail for participants and management participants
            $emails = EmailNotification::where('module','managementreview')
                                                                ->where('item','Add Management Review')
                                                                ->where('is_blocked',false)
                                                                ->first();

            if($emails)
            {
                foreach($request->participants as $participant)
                {
                    $name = User::where('id',$participant)->value('name');
                    $email = User::where('id',$participant)->value('email');
                    $emaildata = array(
                                        'name' => $name,
                                        'date' => date("d/m/Y", strtotime($request->date)),
                                        'email_content'=>$emails->email_content,
                                        'item'=>$emails->item,
                                        'time'=>$request->time,
                                      );
                    Mail::to($email)->send(new ManagementReviewAdd($emaildata));
                }

                if($request->management_participants_oth_email==NULL)
                {
                    foreach($request->management_participants as $mparticipant)
                    {
                        $name = User::where('id',$mparticipant)->value('name');
                        $email = User::where('id',$mparticipant)->value('email');
                        $emaildata = array(
                        'name' => $name,
                        'date' => date("d/m/Y", strtotime($request->date)),
                        'email_content'=>$emails->email_content,
                        'item'=>$emails->item,
                        'time'=>$request->time,
                        );
                        Mail::to($email)->send(new ManagementReviewAdd($emaildata));
                    }
                }
               else
               {
                  $email = $request->management_participants_oth_email;
                  $emaildata = array(
                                    'name' => $request->management_participants_oth,
                                    'date' => date("d/m/Y", strtotime($request->date)),
                                    'email_content'=>$emails->email_content,
                                    'item'=>$emails->item,
                                    'time'=>$request->time,
                                    );
                  Mail::to($email)->send(new ManagementReviewAdd($emaildata));
               }

                $userdatas = EmailNotificationsUserDetails::where('notification_id',$emails->id)->get();
                if(count($userdatas) > 0)
                {
                   $adminaddedusers = array();
                   foreach($userdatas as $userdata)
                    {
                       array_push($adminaddedusers,$userdata->user_id);
                    }

                    foreach($adminaddedusers as $adminaddeduser)
                    {
                        $name = User::where('id',$adminaddeduser)->value('name');
                        $email = User::where('id',$adminaddeduser)->value('email');
                        $emaildata = array(
                                'name' => $name,
                                'date' => date("d/m/Y", strtotime($request->date)),
                                'email_content'=>$emails->email_content,
                                'item'=>$emails->item,
                                'time'=>$request->time,
                            );
                        Mail::to($email)->send(new ManagementReviewAdd($emaildata));
                    }
                }

            }
            else
            {
                Session::flash('msg', 'Mail Sending Blocked for Add Management Review');
                return redirect()->back();
            }

            $models = DB::table('modenadisable')->First();
            return redirect('/user/management/meetings')
                                                    ->with('message', 'Management Review Schedule Created Successfully')
                                                    ->with('model', $models);
    }



 /**--Cancel Meeting--*/

    public function cancelmeetingreason(Request $request)
    {
        $id = $request->input('review_id_value');
        $cancel_reason_value = $request->input('cancel_reason_value');

        ManagementReviewSchedule::where('id',$id)->update(['status'=>'Cancelled','meeting_cancel_reason'=>$cancel_reason_value]);
        $s_datas = ManagementReviewSchedule::where('id',$id)->first();
        $participants = ManagementReviewScheduleParticipant::where('schedule_id',$id)->pluck('participants_id')->toArray();
        $management_participants = ManagementReviewScheduleManagementParticipant::where('schedule_id',$id)->pluck('manage_participant_id')->toArray();

        //sending mail for participants and management participants
        $emails = EmailNotification::where('module','managementreview')
                                                            ->where('item','Management Review Meeting Cancelled')
                                                            ->where('is_blocked',false)
                                                            ->first();

            if($emails)
            {
                foreach($participants as $participant)
                {
                    $name = User::where('id',$participant)->value('name');
                    $email = User::where('id',$participant)->value('email');
                    $emaildata = array(
                                        'name' => $name,
                                        'email_content'=>$emails->email_content,
                                        'item'=>$emails->item,
                                        'date' => date("d/m/Y", strtotime($s_datas->date)),
                                        'time'=>$s_datas->time,
                                        'reason'=>$request->input('cancel_reason_value'),
                                        );
                    Mail::to($email)->send(new ManagementReviewMeetingCancelled($emaildata));
                }

                if($s_datas->management_participants_other_email==NULL)
                {
                    foreach($management_participants as $mparticipant)
                    {
                        $name = User::where('id',$mparticipant)->value('name');
                        $email = User::where('id',$mparticipant)->value('email');
                        $emaildata = array(
                                            'name' => $name,
                                            'email_content'=>$emails->email_content,
                                            'item'=>$emails->item,
                                            'date' => date("d/m/Y", strtotime($s_datas->date)),
                                            'time'=>$s_datas->time,
                                            'reason'=>$request->input('cancel_reason_value'),
                                            );
                        Mail::to($email)->send(new ManagementReviewMeetingCancelled($emaildata));
                    }
                }
              else
              {
                  $email = $s_datas->management_participants_other_email;
                  $emaildata = array(
                                    'name' => $s_datas->management_participants_other,
                                    'email_content'=>$emails->email_content,
                                    'item'=>$emails->item,
                                    'date' => date("d/m/Y", strtotime($s_datas->date)),
                                    'time'=>$s_datas->time,
                                    'reason'=>$request->input('cancel_reason_value'),
                                  );
                  Mail::to($email)->send(new ManagementReviewMeetingCancelled($emaildata));
              }


                $userdatas = EmailNotificationsUserDetails::where('notification_id',$emails->id)->get();
                if(count($userdatas) > 0)
                {
                    $adminaddedusers = array();
                    foreach($userdatas as $userdata)
                    {
                      array_push($adminaddedusers,$userdata->user_id);
                    }

                    foreach($adminaddedusers as $adminaddeduser)
                    {
                        $name = User::where('id',$adminaddeduser)->value('name');
                        $email = User::where('id',$adminaddeduser)->value('email');
                        $emaildata = array(
                                            'name' => $name,
                                            'email_content'=>$emails->email_content,
                                            'item'=>$emails->item,
                                            'date' => date("d/m/Y", strtotime($s_datas->date)),
                                            'time'=>$s_datas->time,
                                            'reason'=>$request->input('cancel_reason_value'),
                                          );
                        Mail::to($email)->send(new ManagementReviewMeetingCancelled($emaildata));
                    }
                }

            }
            else
            {
               return response()->json([
                           'status'=>503,
                           'data'=>null,
                        ]);
            }

        return response()->json([
            'status'=>200,
            'data'=>null,
        ]);
    }


    /**--Record Minutes--*/

    public function recordminutes($id)
    {
       $datas = ManagementReviewSchedule::where('id',$id)->first();
       $agendapoints = ManagementAgendaPointList::where('agenda_point_detail_type',$datas->agenda_point_detail_type)->get();
       $notes = ManagementReviewScheduleNote::where('schedule_id',$id)->get();
       $models = DB::table('modenadisable')->First();
       return view('user.managementreview.recordminutes')
                                                ->with('data', $datas)
                                                ->with('agendapoints', $agendapoints)
                                                ->with('notes', $notes)
                                                ->with('model', $models);
    }

    public function addminutesajax(Request $request)
    {
        $agenda_list_id = $request->input('agendalistid_value');
        $schedule_id = $request->input('scheduleid_value');
        $minute_notes = $request->input('add_minutes_value');

        $data = array(
            'agenda_list_id'=>$agenda_list_id,
            'schedule_id'=>$schedule_id,
            'minute_notes'=>$minute_notes,
        );

       $data1 = array(
            'schedule_id'=>$schedule_id,
            'minute_notes'=>$minute_notes,
        );


            $exists =  ManagementReviewScheduleNote::where('schedule_id',$schedule_id)
                                                     ->where('agenda_list_id',$agenda_list_id)->get();
            if(count($exists)>0)
            {
                ManagementReviewScheduleNote::where('schedule_id',$schedule_id)
                                             ->where('agenda_list_id',$agenda_list_id)->update($data1);
            }
            else
            {
                ManagementReviewScheduleNote::create($data);
            }

        return response()->json([
                'status'=>200,
                'data'=>null,
            ]);

    }


    public function getminutenotesajax(Request $request)
    {
        $agenda_list_id = $request->input('agenda_list_id');
        $schedule_id = $request->input('schedule_id');
        $notes = ManagementReviewScheduleNote::where('schedule_id',$schedule_id)
                                               ->where('agenda_list_id',$agenda_list_id)
                                               ->get();

        return response()->json([
                'status'=>200,
                'data'=>$notes,
            ]);

    }

    public function displayminutenotesajax(Request $request)
    {
        $agenda_list_id = $request->input('agenda_list_id');
        $schedule_id = $request->input('schedule_id');
        $notes = ManagementReviewScheduleNote::where('schedule_id',$schedule_id)
                                               ->where('agenda_list_id',$agenda_list_id)
                                               ->get();

        return response()->json([
                'status'=>200,
                'data'=>$notes,
            ]);

    }

  /**--Delete all review meeting--*/
    public function deletemanagementreviewmeeting()
    {
        if (isset($_GET['val']))
        {
            $id = $_GET['val'];
            ManagementReviewSchedule::where('id',$id)->delete();
            $agendaexists = ManagementReviewScheduleAgendaType::where('schedule_id',$id)->get();
            if(count($agendaexists)>0)
            {
               ManagementReviewScheduleAgendaType::where('schedule_id',$id)->delete();
            }
            $partcipantexists = ManagementReviewScheduleParticipant::where('schedule_id',$id)->get();
            if(count($partcipantexists)>0)
            {
                ManagementReviewScheduleParticipant::where('schedule_id',$id)->delete();
            }
            $mpartcipantexists = ManagementReviewScheduleManagementParticipant::where('schedule_id',$id)->get();
            if(count($mpartcipantexists)>0)
            {
                 ManagementReviewScheduleManagementParticipant::where('schedule_id',$id)->delete();
            }
            return response()->json([
                'status'=>200,
                'data'=>null,
            ]);
        }
    }

  /**--Action Plans--*/

   public function addcorrective($id)
   {
        $datas = ManagementReviewSchedule::where('id', $id)->first();
        $departments = DB::table('departments')->get();
        $types = Type::all();
        $users = User::where('is_email_verified',true)->get();
        $models = DB::table('modenadisable')->first();
        return view('user.managementreview.addmanagementcorrective')
            ->withDepartments($departments)
            ->withTypes($types)
            ->withUsers($users)
            ->with('datas', $datas)
            ->with('model', $models);
   }



  public function insertcorrective(AddCorrectiveRequest $request)
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
        $review_schedule_id = $request->input('review_schedule_id');
        $category = $request->input('category');
        $categoryId = Categories::where('categoryname',$category)->value('id');
        if(!$categoryId){
            $catdata = array(
                'categoryname' => $category,
                'shortname' => "has",
                'created_at' => now(),
                'updated_at' => now()
            );
            Categories::insert($catdata);
            $categoryId = Categories::where('categoryname',$category)->value('id');
        }
        $typ    = $request->input('type');
        $department = $request->input('department');
        $title   =  $request->input('title');
        $observation = $request->input('observation');
        $agreedaction =  $request->input('agreedaction');
        $is_rc_analysis = $request->input('is_rc_analysis');
        $division = $request->input('division')??'0';
        $area = $request->input('area')??'0';
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
        $entry_reviewed_by = User::where('email',Auth::user()->email)->value('id');

        //inserting data to corrective table
        $corrective_data = array(
            'count' => '1',
            'issueid'=>$issueId,
            'category' => $categoryId,
            'type' => $typ,
            'department' =>'0',
            'correction_department' => $department,
            'title' => $title,
            'file' => $cer,
            'observation' => $observation,
            'correction_target_date' => $due_date,
            'frequency' => $frequency,
            'agreedaction' => $agreedaction,
            'postedby' => $postedby,
            'correction_responsibility' => $responsibility,
            'notification' => $notification,
            'approvement' => $approvement,
            'processstage' => 'Awaiting actions',
            'approvedby' => $approvedby,
            'status' => $status,
            'status' => $status,
            'postdate' => $postdate,
            'management_review_schedule_id'=>$review_schedule_id,
            'Monthlyfreq' => $review_date,
            'Weeklyfreq' => $review_date,
            'division_id' => $division,
            'area_id' => $area,
            'entry_reviewed_by' => $entry_reviewed_by,
            'entry_reviewed_by_date' =>date('Y-m-d'),
            'is_req_rc_analysis_management_review' => $is_rc_analysis,
            'created_at'=>now(),
            'updated_at' =>now()
        );
        DB::table('corrective')->insert($corrective_data);

        //fetching id from corrective table
        $correct_id = DB::table('corrective')->where('issueid', $issueId)->value('id');

        //inserting data to management_review_schedule_correctives table
        $data1 = [
            'corrective_id'=>$correct_id,
            'schedule_id'=>$review_schedule_id
        ];
        ManagementReviewScheduleCorrective::create($data1);
           //inserting data to multiplecorr_issues table
        $new_files = explode(',', $cer);
        foreach ($new_files as $new_file) {
            $data2  = array('id' => $correct_id, 'issue_file' =>  $new_file);
            DB::table('multiplecorr_issues')->insert($data2);
        }
        $depname = Departments::where('id',$department)->value('departmentname');
        $type = Type::where('id',$typ)->value('name');
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
                $check = 1;
                $corr_respon_emails = EmailNotification::where('module', 'correctiveaction')
                        ->where('item', 'Corrective Response Notification')
                        ->where('is_blocked', false)
                        ->first();
                if ($corr_respon_emails) {
                    // Sending Mail(Responsibility Notification) To responsibile HOD
                    $mailData = array(
                        'email_content' => $corr_respon_emails->email_content,
                        'item' => $corr_respon_emails->item,
                        'issueid' => $issueId,
                        'name' => $user_name,
                        'type' =>  $type,
                        'department' =>  $depname,
                        'duedate' => date("d/m/Y", strtotime($due_date))
                    );
                    if ($responsibility) {
                        // Mail::to($responsibility)->send(new CorrectiveResponseNotification($mailData));
                    }
                    if($notification){
                        $notificationemails = explode(',', $notification);
                        if (count($notificationemails)>0) {
                            // Mail::to($notificationemails)->send(new CorrectiveResponseNotification($mailData));
                        }
                    }
                    //sending mail for participants and management participants
                    $corrective_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_respon_emails->id)->get();
                    if (count($corrective_respon_userdatas) > 0) {
                        $adminaddedusers = $corrective_respon_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            // Mail::to($userdatas)->send(new CorrectiveResponseNotification($mailData));
                        }
                    }
                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Response Notification');
                }
                // Sending Mail(AddIssue Notification) To Other HOD
                $corrective_add_emails = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Issue Added')
                    ->where('is_blocked', false)
                    ->first();
                if ($corrective_add_emails) {
                    $mailData = array(
                        'email_content' => $corr_respon_emails->email_content,
                        'item' => $corr_respon_emails->item,
                        'issueid' => $issueId,
                        'name' => $user_name,
                        'type' =>  $type,
                        'department' =>  $depname,
                        'duedate' => date("d/m/Y", strtotime($due_date))
                    );
                    if ($notification && $notification != '') {
                        $noti = explode(',', $notification);
                        if (count($noti) > 0) {
                            // Mail::to($noti)->send(new CorrectiveAddIssue($mailData));
                        }
                    }

                    //sending mail for participants and management participants
                    $corrective_add_userdatas = EmailNotificationsUserDetails::where('notification_id', $corrective_add_emails->id)->get();
                    if (count($corrective_add_userdatas) > 0) {
                        $adminaddedusers = $corrective_add_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            // Mail::to($userdatas)->send(new CorrectiveAddIssue($mailData));
                        }
                    }
                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Add Corrective Issue');
                }
            }
        }
        if ($check == 0) {
            $corr_iss_await_emails = EmailNotification::where('module', 'correctiveaction')
                ->where('item', 'Corrective Issue Awaiting Approval')
                ->where('is_blocked', false)
                ->first();
            if ($corr_iss_await_emails) {
                $issue_rev_emails = IssueReviewer::pluck('email')->toArray();
                $mailData = array(
                    'issueid' => $issueId,
                    'name' => $user_name,
                    'type' =>  $type,
                    'email_content' => $corr_iss_await_emails->email_content,
                    'item' => $corr_iss_await_emails->item,
                    'department' =>  $depname,
                    'duedate' => date("d/m/Y", strtotime($due_date))
                );
                if (count($issue_rev_emails) > 0) {
                    // Mail::to($issue_rev_emails)->send(new CorrectiveIssueAwaitingApproval($mailData));
                }
                //sending mail for participants and management participants
                $corrective_await_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_iss_await_emails->id)->get();
                if (count($corrective_await_userdatas) > 0) {
                    $adminaddedusers = $corrective_await_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        // Mail::to($userdatas)->send(new CorrectiveIssueAwaitingApproval($mailData));
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Awaiting Approval');
            }
        }
        Session::flash('messages', "Action plans added successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);

  }

   /**--change Schedule--*/
  public function updateschedule(Request $request)
  {
      $new_date = $request->input('date_value');
      $new_time = $request->input('time_value');
      $schedule_id = $request->input('schedule_id_value');
      $data = array(
                       'date'=>$new_date,
                       'time'=>$new_time,
                    );
       ManagementReviewSchedule::where('id',$schedule_id)->update($data);

      $s_datas = ManagementReviewSchedule::where('id',$schedule_id)->first();
      $participants = ManagementReviewScheduleParticipant::where('schedule_id',$schedule_id)->pluck('participants_id')->toArray();
      $management_participants = ManagementReviewScheduleManagementParticipant::where('schedule_id',$schedule_id)->pluck('manage_participant_id')->toArray();



    //sending mail for participants and management participants
      $emails = EmailNotification::where('module','managementreview')
                                                            ->where('item','Management Review Meeting Change schedule')
                                                            ->where('is_blocked',false)
                                                            ->first();
       if($emails)
        {
            foreach($participants as $participant)
            {
                $name = User::where('id',$participant)->value('name');
                $email = User::where('id',$participant)->value('email');
                $emaildata = array(
                                    'name' => $name,
                                    'email_content'=>$emails->email_content,
                                    'item'=>$emails->item,
                                    'date' => date("d/m/Y", strtotime($new_date)),
                                    'time'=>$new_time,
                                    );
                Mail::to($email)->send(new ManagementReviewMeetingChangeSchedule($emaildata));
            }

            if($s_datas->management_participants_other_email==NULL)
            {
                foreach($management_participants as $mparticipant)
                {
                    $name = User::where('id',$mparticipant)->value('name');
                    $email = User::where('id',$mparticipant)->value('email');
                    $emaildata = array(
                                        'name' => $name,
                                        'email_content'=>$emails->email_content,
                                        'item'=>$emails->item,
                                        'date' => date("d/m/Y", strtotime($new_date)),
                                        'time'=>$new_time,
                                        );
                    Mail::to($email)->send(new ManagementReviewMeetingChangeSchedule($emaildata));
                }

            }
            else
            {
                $email = $s_datas->management_participants_other_email;
                $emaildata = array(
                                  'name' => $s_datas->management_participants_other,
                                  'email_content'=>$emails->email_content,
                                  'item'=>$emails->item,
                                  'date' => date("d/m/Y", strtotime($s_datas->date)),
                                  'time'=>$s_datas->time,
                                  'reason'=>$request->input('cancel_reason_value'),
                                );
                Mail::to($email)->send(new ManagementReviewMeetingChangeSchedule($emaildata));
            }

                $userdatas = EmailNotificationsUserDetails::where('notification_id',$emails->id)->get();
                if(count($userdatas) > 0)
                {
                    $adminaddedusers = array();
                    foreach($userdatas as $userdata)
                    {
                      array_push($adminaddedusers,$userdata->user_id);
                    }

                    foreach($adminaddedusers as $adminaddeduser)
                    {
                        $name = User::where('id',$adminaddeduser)->value('name');
                        $email = User::where('id',$adminaddeduser)->value('email');
                        $emaildata = array(
                                            'name' => $name,
                                            'email_content'=>$emails->email_content,
                                            'item'=>$emails->item,
                                            'date' => date("d/m/Y", strtotime($new_date)),
                                            'time'=>$new_time,
                                          );
                        Mail::to($email)->send(new ManagementReviewMeetingChangeSchedule($emaildata));
                    }
                }
        }
       else
       {
        return response()->json([
                    'status'=>503,
                    'data'=>$emails,
                ]);
       }

      return response()->json([
              'status'=>200,
              'data'=>null,
          ]);

  }

 /**--close--*/
  public function meetingcompleted($id)
     {
        ManagementReviewSchedule::where('id',$id)
                                     ->update([
                                                'status' => 'Completed'
                                              ]);
        $models = DB::table('modenadisable')->First();
        return redirect('/user/management/meetings')
                                                ->with('message', 'Review Meeting Completed Successfully')
                                                ->with('model', $models);

     }

  /**--Analysis--*/
  public function analysis()
   {
     $models = DB::table('modenadisable')->first();
     return view('user.managementreview.analysis')
                                        ->with('model', $models);

   }

}
