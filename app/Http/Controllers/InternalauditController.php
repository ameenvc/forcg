<?php

namespace App\Http\Controllers;

use App\Area;
use App\Audit;
use App\AuditChecklist;
use App\AuditChecklistConformance;
use App\AuditChecklistNewRecord;
use App\AuditChecklistNewRecordDetail;
use App\AuditChecklistPoints;
use App\AuditChecklistTitles;
use App\Auditor;
use App\AuditScheduleArea;
use App\AuditScheduleChecklist;
use App\AuditScheduleDivision;
use App\Charts\AuditAnalysis;
use App\Departments;
use App\Division;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddAuditChecklistRequest;
use App\Imports\ChecklistImport;
use App\Mail\Addinternalaudit;
use App\Mail\AuditChecklistRemovelRequest;
use App\Mail\AuditChecklistSuggestImprovements;
use App\Mail\Auditschedule;
use App\Standards;
use App\User;
use App\UserGate;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PDF;
use Session;

// use App\Mail\Escalaterisk;

class InternalauditController extends Controller
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

    public function addaudit()
    {
        $standards = Standards::all();
        //$users = DB::table('users')->get();
        $users = User::where('approved',true)->get();
        $controllers = DB::table('internalaudits')->get();
        $representatives = DB::table('representatives')->get();

        $list = collect([]);
        foreach ($controllers as $c) {
            $list = $list->push($c->email);
        }

        foreach ($representatives as $representative) {
            $list = $list->push($representative->email);
        }

        $mrs = $list->unique();

        $dept = DB::table('departments')->pluck('hod');
        $auditors = DB::table('auditors')->get();
        $aud = DB::table('auditors')->pluck('email');
        $departments = Departments::active()->get();
        $emai = Auth::user()->email;
        $respon = DB::select("select * from representatives");
        $res = DB::select("select * from internalaudits");
        foreach ($respon as $dat) {
            if ($emai == $dat->email) {
                $models = DB::table('modenadisable')->First();
                return view('user.internalaudit.addaudit', compact('mrs'))->withStandards($standards)->withUsers($users)->withDept($dept)->withAuditors($auditors)->withAud($aud)->withDepartments($departments)->with('model', $models);
            }
        }
        foreach ($res as $dat) {
            if ($emai == $dat->email) {
                $models = DB::table('modenadisable')->First();
                return view('user.internalaudit.addaudit', compact('mrs'))->withStandards($standards)->withUsers($users)->withDept($dept)->withAuditors($auditors)->withAud($aud)->withDepartments($departments)->with('model', $models);
            }
        }

        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.notauth')->with('model', $models);
        // return view('user.internalaudit.addaudit',compact('mrs'))->withDept($dept)->withAuditor($auditor)->withAud($aud)->withDepartments($departments);
    }

    public function selectdeptdivisiondetails(Request $request)
    {
        $dept_id = $request->input('department_id_value');
        $isoclauses = DB::table('departments')->where('id', $dept_id)->value('isoclauses');
        $hodname = DB::table('departments')->where('id', $dept_id)->value('hodname');
        $divisions = Division::where('department_id', $dept_id)->get();
        $divisions_id_array = Division::where('department_id', $dept_id)->pluck('id')->toArray();
        $areas_id_array = Area::whereIn('division_id', $divisions_id_array)->pluck('id')->toArray();
        $checklist_ids = AuditChecklistPoints::select('checklist_id')
                                      ->distinct()
                                      ->where('department_id', $dept_id)
                                      ->pluck('checklist_id')
                                      ->toArray();
        $checklists = AuditChecklist::where('department_id', $dept_id)
                                    ->whereIn('id',$checklist_ids)
                                    ->where('is_deleted',false)
                                    ->get();
        return response()->json([
            'status' => 200,
            'divisions' => $divisions,
            'isoclauses' => $isoclauses,
            'hodname' => $hodname,
            'checklists'=>$checklists
        ]);
    }
    public function selectdivisionareadetails(Request $request)
    {
        $divisionid = $request->input('division_id_value');
        $divisionid = array_map('intval', $divisionid);
        $area = Area::whereIn('division_id', $divisionid)->get();
        $dept_details = Division::whereIn('id',$divisionid)->pluck('department_id')->toArray();
        $checklists_id_depts = AuditChecklistPoints::select('checklist_id')
                                                    ->distinct()
                                                    ->where('department_id', $dept_details[0])
                                                    ->pluck('checklist_id')
                                                    ->toArray();
        $checklists_depts = AuditChecklist::where('department_id', $dept_details[0])
                                           ->whereIn('id',$checklists_id_depts)
                                           ->where('is_deleted',false)
                                           ->get();
        $checklists_id_divisions = AuditChecklistPoints::select('checklist_id')
                                                        ->distinct()
                                                        ->whereIn('division_id', $divisionid)
                                                        ->pluck('checklist_id')
                                                        ->toArray();
        $checklists_divisions = AuditChecklist::whereIn('division_id', $divisionid)
                                                ->whereIn('id',$checklists_id_divisions)
                                                ->where('is_deleted',false)
                                                ->get();
        $checklists = $checklists_depts->merge($checklists_divisions);

        return response()->json([
            'status' => 200,
            'area' => $area,
            'checklists'=>$checklists
        ]);
    }
    public function selectareachecklistdetails(Request $request)
    {
        $areaid = $request->input('area_id_value');
        $areaid = array_map('intval', $areaid);
        $checklists_id_areas = AuditChecklistPoints::select('checklist_id')
                                                        ->distinct()
                                                        ->whereIn('area_id', $areaid)
                                                        ->pluck('checklist_id')
                                                        ->toArray();
        $checklists_areas = AuditChecklist::whereIn('area_id', $areaid)
                                            ->whereIn('id',$checklists_id_areas)
                                            ->where('is_deleted',false)
                                            ->get();
        $division_ids = Area::whereIn('id',$areaid)->pluck('division_id')->toArray();
        $checklists_id_divisions = AuditChecklistPoints::select('checklist_id')
                                                        ->distinct()
                                                        ->whereIn('division_id', $division_ids)
                                                        ->pluck('checklist_id')
                                                        ->toArray();
        $checklist_divisions = AuditChecklist::whereIn('division_id', $division_ids)
                                                ->whereIn('id',$checklists_id_divisions)
                                                ->where('is_deleted',false)
                                                ->get();

        $department_id = Division::whereIn('id',$division_ids)->pluck('department_id')->toArray();
        $checklists_id_depts = AuditChecklistPoints::select('checklist_id')
                                                    ->distinct()
                                                    ->where('department_id', $department_id[0])
                                                    ->pluck('checklist_id')
                                                    ->toArray();
        $checklists_depts = AuditChecklist::where('department_id', $department_id[0])
                                           ->whereIn('id',$checklists_id_depts)
                                           ->where('is_deleted',false)
                                           ->get();
        $checklists = $checklists_depts->merge($checklist_divisions)->merge($checklists_areas);

        return response()->json([
            'status' => 200,
            'checklists'=>$checklists
        ]);
    }

    public function insertaudit(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'department' => 'required|exists:departments,id',
        ], [
            'department.exists' => 'Please Choose a department.',
        ]);

        if ($validator->fails()) {

            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {

            $app_url = BaseController::addSlashToEndOfURL();
            $company = BaseController::getCompanyFromAppRUL();
            $department = $req->input('department');
            $exist = DB::table('audit')->where('department', $department)->first();
            $e = DB::table('audit')->where('department', $department)->orderBy('id', 'desc')->first();
            if ($exist) {
                if ($e) {
                    $iid = $e->aud_id;
                    $strid = (substr($iid, -2));
                    $y = Date("Y");
                    $year = substr($y, -2);

                    if ($strid == $year) {
                        $c = DB::table('audit')->where('department', $department)->pluck('count');
                        foreach ($c as $count) {
                            $count = $count + 1;
                        }
                    } else {
                        $count = "1";
                    }
                } else {
                    $count = "1";
                }
            } else {
                $count = "1";
            }

            $hods = DB::table('departments')->where('id', $department)->pluck('hod');
            foreach ($hods as $hod) {
                $hodemail = $hod;
            }
            $scope = $req->input('scope');
            if ($scope == null) {
                $sco = "null";
            } else {
                $checkbox1 = $_POST['scope'];
                $sco = "";
                foreach ($checkbox1 as $chk1) {
                    $sco .= $chk1 . ",";
                }
            }

            $auditor = $req->input('auditors');
            foreach ($auditor as $audit) {
                $a[] = $audit;
            }
            $aud = implode(',', $a);

            $mails = DB::table('auditors')->wherein('auditorsname', $auditor)->pluck('email');
            foreach ($mails as $email) {
                $heademail[] = $email;
            }
            $mail = implode(',', $heademail);

            $contact = $req->input('contact');
            foreach ($contact as $contac) {
                $b[] = $contac;
            }
            $con = implode(',', $b);

            $now = $req->input('date');
            $year = date('Y', strtotime($now));
            $time = $req->input('time');

            $focu = $req->input('focus');
            $enddate = $req->input('enddate');
            $check_end_date = strtotime($enddate);
            $get_month = date("M", $check_end_date);
            $q1 = ['Jan', 'Feb', 'Mar'];
            $q2 = ['Apr', 'May', 'Jun'];
            $q3 = ['Jul', 'Aug', 'Sep'];
            $q4 = ['Oct', 'Nov', 'Dec'];
            if (in_array($get_month, $q1)) {
                $quarter = 'Q1';
            } else if (in_array($get_month, $q2)) {
                $quarter = 'Q2';
            } else if (in_array($get_month, $q3)) {
                $quarter = 'Q3';
            } else if (in_array($get_month, $q4)) {
                $quarter = 'Q4';
            }
            //$quarter = $req->input('quarter');
            $budgeted = $req->input('budgeted');

            // if ($req->division != null) {
            //     $division = $req->division;
            // } else {
            //     $division = null;
            // }

            // if ($req->area != null) {
            //     $area = $req->area;
            // } else {
            //     $area = null;
            // }

            $division = 0;
            $area = 0;

            $auditees_arrs = $req->input('contact');
            $auditees_emails = array();
            foreach ($auditees_arrs as $auditees_arr) {
                $audexists = User::where('name', $auditees_arr)->first();
                if ($audexists) {
                    array_push($auditees_emails, $audexists->email);
                }
            }

            $ID = Auth::user()->id;
            $departmentname = Departments::where('id', $department)->value('departmentname');
            $data = array(
                'count' => $count,
                'department' => $department,
                'division_id' => $division,
                'area_id' => $area,
                'hod' => $hodemail,
                'scope' => $sco,
                'auditors' => $aud,
                'email' => $mail,
                'contact' => $con,
                'time' => $time,
                'date' => $now,
                'year' => $year,
                'enddate' => $enddate,
                'focus' => $focu,
                'quarter' => $quarter,
                'budgeted' => $budgeted,
                'status' => 'Planned',
                'planned' => 'YES',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );

            $from_date = date('d-m-Y', strtotime($now));
            $to_date = date('d-m-Y', strtotime($enddate));

            DB::table('audit')->insert($data);

            $audit_data = DB::table('audit')->latest('id')->first();

            $yearr = substr($audit_data->year, -2);
            //code to add dept name to id
            $code = DB::table('departments')->where('id', $department)->value('code');
            $code = strtoupper(substr($code, 0, 3));
            $aud_id = $code . $count . "/" . $yearr;
            $data3 = array('aud_id' => $aud_id);

            $recent_id = $audit_data->id;
            DB::table('audit')->where('id', $recent_id)->update($data3);

        //storing data in divisions, area and checklist table
           if ($req->division != null) {
              //$division_list [] = $req->division;
              foreach($req->division as $item){
                  $divisions_data = new AuditScheduleDivision;
                  $divisions_data->audit_id = $audit_data->id;
                  $divisions_data->division_id = $item;
                  $divisions_data->save();
              }
           }

           if ($req->area != null) {
            foreach($req->area as $item){
                $area_data = new AuditScheduleArea;
                $area_data->audit_id = $audit_data->id;
                $area_data->area_id = $item;
                $area_data->save();
            }
         }

         if ($req->checklist != null) {
            foreach($req->checklist as $item){
                $checklist_data = new AuditScheduleChecklist;
                $checklist_data->audit_id = $audit_data->id;
                $checklist_data->checklist_id = $item;
                $checklist_data->save();
            }
         }


            $maildata = array(
                'count' => $count, 'department' => $departmentname, 'hod' => $hodemail, 'scope' => $sco,
                'auditors' => $aud, 'email' => $mail, 'contact' => $con, 'time' => $time,
                'date' => $from_date, 'year' => $year, 'enddate' => $to_date, 'focus' => $focu, 'quarter' => $quarter, 'budgeted' => $budgeted,
                'app_url' => $app_url,
                'aud_id' => $aud_id,
                'current_year' => date('Y'),
                //'company' => $company,
                'status' => 'Planned', 'planned' => 'YES', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );


            //mail to hod
            $hod = DB::table('departments')->where('id', $department)->pluck('hod');
            Mail::to($hod)->send(new Addinternalaudit($maildata));

            foreach ($mails as $email) {
                Mail::to($email)->send(new Auditschedule($maildata));
            }
            //sending mail to auditees
            foreach ($auditees_emails as $auditees_email) {
                Mail::to($auditees_email)->send(new Auditschedule($maildata));
            }

            //inserting values to activity table
            $activity = "Audit sent for approval with auditee " . $hodemail . " and with auditors " . $aud;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
            Session::flash('msg', "Audit sent for approval Successfully");

            //below code is related to superadmin
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    public function linkchecklist($id)
    {
        $models = DB::table('modenadisable')->First();
        $audit_record = Audit::find($id);
        if($audit_record){
            $division_lists = AuditScheduleDivision::where('audit_id',$id)->get();
            $area_lists = AuditScheduleArea::where('audit_id',$id)->get();
            //$audit_schedule_checklists = AuditScheduleChecklist::where('audit_id',$id)->pluck('checklist_id')->toArray();
            $audit_schedule_created_checklists = AuditScheduleChecklist::where('audit_id',$id)->pluck('checklist_id')->toArray();
            $audit_record_checklists = AuditChecklistNewRecord::where('audit_schedule_id',$id)->pluck('checklist_id')->toArray();
            if(count($audit_schedule_created_checklists)){
                $audit_schedule_checklists = $audit_schedule_created_checklists;
            }else{
                $audit_schedule_checklists = $audit_record_checklists;
            }
            $divisions_connected = Division::where('department_id',$audit_record->department)->get();
            $divisions_connected_ids = Division::where('department_id',$audit_record->department)->pluck('id')->toArray();
            $areas_connected = Area::whereIn('division_id',$divisions_connected_ids)->get();
            $areas_connected_ids = Area::whereIn('division_id',$divisions_connected_ids)->pluck('id')->toArray();
            if(count($division_lists) ==0 && count($area_lists)==0 || $audit_record->division_id ==Null && $audit_record->area_id==Null){
                $total_checklists_combined = AuditChecklist::where('department_id',$audit_record->department)->get();
            }
            if(count($division_lists) > 0 && count($area_lists)==0 || $audit_record->division_id !=Null && $audit_record->area_id==Null){
                if($audit_record->division_id !=Null){
                    $total_checklists_combined = AuditChecklist::where('division_id',$audit_record->division_id)->get();
                }else{
                    $division_list_ids = AuditScheduleDivision::where('audit_id',$id)->pluck('division_id')->toArray();
                    $total_checklists_combined = AuditChecklist::whereIn('division_id',$division_list_ids)->get();
                }
            }
            if(count($division_lists) > 0 && count($area_lists)>0 || $audit_record->division_id !=Null && $audit_record->area_id!=Null){
                if($audit_record->area_id !=Null){
                    $total_checklists_combined = AuditChecklist::where('area_id',$audit_record->area_id)->get();
                }else{
                    $area_list_ids = AuditScheduleArea::where('audit_id',$id)->pluck('area_id')->toArray();
                    $total_checklists_combined = AuditChecklist::whereIn('area_id',$area_list_ids)->get();
                }
            }
            //$total_checklist_dept = AuditChecklist::where('department_id',$audit_record->department)->get();
            //$total_checklist_division = AuditChecklist::whereIn('division_id',$divisions_connected_ids)->get();
            //$total_checklist_area = AuditChecklist::whereIn('area_id',$areas_connected_ids)->get();
            //$total_checklists_combined = $total_checklist_dept->merge($total_checklist_division)->merge($total_checklist_area);
            $total_checklists = $total_checklists_combined->reject(function ($checklist) use ($audit_schedule_checklists) {
                return in_array($checklist->id, $audit_schedule_checklists);
            });

            return view('user.internalaudit.linkchecklist')
                                            ->with('audit_record',$audit_record)
                                            ->with('division_lists',$division_lists)
                                            ->with('area_lists',$area_lists)
                                            ->with('audit_schedule_checklists',$audit_schedule_checklists)
                                            ->with('total_checklists',$total_checklists)
                                            ->with('model', $models);
        }
        else{
            return redirect()->back()->with('model', $models);
        }
    }
    public function linkauditchecklist($id)
    {
        $models = DB::table('modenadisable')->First();
        $audit_record = Audit::find($id);
        if($audit_record){
            $division_lists = AuditScheduleDivision::where('audit_id',$id)->get();
            $area_lists = AuditScheduleArea::where('audit_id',$id)->get();
            $audit_schedule_created_checklists = AuditScheduleChecklist::where('audit_id',$id)->pluck('checklist_id')->toArray();
            $audit_record_checklists = AuditChecklistNewRecord::where('audit_schedule_id',$id)->pluck('checklist_id')->toArray();
            if(count($audit_schedule_created_checklists)){
                $audit_schedule_checklists = $audit_schedule_created_checklists;
            }else{
                $audit_schedule_checklists = $audit_record_checklists;
            }
            $divisions_connected = Division::where('department_id',$audit_record->department)->get();
            $divisions_connected_ids = Division::where('department_id',$audit_record->department)->pluck('id')->toArray();
            $areas_connected = Area::whereIn('division_id',$divisions_connected_ids)->get();
            $areas_connected_ids = Area::whereIn('division_id',$divisions_connected_ids)->pluck('id')->toArray();
            if(count($division_lists) ==0 && count($area_lists)==0 || $audit_record->division_id ==Null && $audit_record->area_id==Null){
                $total_checklists_combined = AuditChecklist::where('department_id',$audit_record->department)->notDeleted()->get();
            }
            if(count($division_lists) > 0 && count($area_lists)==0 || $audit_record->division_id !=Null && $audit_record->area_id==Null){
                if($audit_record->division_id !=Null){
                    $dept_id = Division::where('id',$audit_record->division_id)->value('department_id');
                    $dept_checklist = AuditChecklist::where('department_id',$dept_id)->notDeleted()->get();
                    $division_checklist = AuditChecklist::where('division_id',$audit_record->division_id)->notDeleted()->get();
                    $total_checklists_combined = $dept_checklist->merge($division_checklist);
                }else{
                    $division_list_ids = AuditScheduleDivision::where('audit_id',$id)->pluck('division_id')->toArray();
                    $division_checklist = AuditChecklist::whereIn('division_id',$division_list_ids)->notDeleted()->get();
                    $dept_ids = Division::whereIn('id',$division_list_ids)->pluck('department_id')->toArray();
                    $dept_checklist = AuditChecklist::where('department_id',$dept_ids[0])->notDeleted()->get();
                    $total_checklists_combined = $dept_checklist->merge($division_checklist);
                }
            }
            if(count($division_lists) > 0 && count($area_lists)>0 || $audit_record->division_id !=Null && $audit_record->area_id!=Null){
                if($audit_record->area_id !=Null){
                    $division_ids = Area::where('id',$audit_record->area_id)->value('division_id');
                    $checklist_divisions = AuditChecklist::where('division_id', $division_ids)->notDeleted()->get();
                    $dept_id = Division::where('id',$division_ids)->value('department_id');
                    $checklists_depts = AuditChecklist::where('department_id',$dept_id)->notDeleted()->get();
                    $checklists_areas = AuditChecklist::where('area_id',$audit_record->area_id)->notDeleted()->get();
                    $total_checklists_combined = $checklists_depts->merge($checklist_divisions)->merge($checklists_areas);
                }else{
                    $area_list_ids = AuditScheduleArea::where('audit_id',$id)->pluck('area_id')->toArray();
                    $checklists_areas = AuditChecklist::whereIn('area_id',$area_list_ids)->notDeleted()->get();
                    $division_ids = Area::whereIn('id',$area_list_ids)->pluck('division_id')->toArray();
                    $checklist_divisions = AuditChecklist::whereIn('division_id', $division_ids)->notDeleted()->get();
                    $dept_ids = Division::whereIn('id',$division_ids)->pluck('department_id')->toArray();
                    $checklists_depts = AuditChecklist::where('department_id',$dept_ids[0])->notDeleted()->get();
                    $total_checklists_combined = $checklists_depts->merge($checklist_divisions)->merge($checklists_areas);
                }
            }

            // $total_checklists = $total_checklists_combined->reject(function ($checklist) use ($audit_schedule_checklists) {
            //     return in_array($checklist->id, $audit_schedule_checklists);
            // });

            $checklist_ids_with_points = AuditChecklistPoints::whereIn('checklist_id', $total_checklists_combined->pluck('id'))->pluck('checklist_id')->unique()->toArray();

            $totalcombined = $total_checklists_combined->filter(function($checklist_item) use ($checklist_ids_with_points) {
                return in_array($checklist_item->id, $checklist_ids_with_points);
            });

            $total_checklists = $totalcombined->reject(function ($checklist) use ($audit_schedule_checklists) {
                return in_array($checklist->id, $audit_schedule_checklists);
            });


            $records = AuditChecklistNewRecord::where('audit_schedule_id', $id)->get();
            $checklist_created = AuditScheduleChecklist::where('audit_id',$id)->get();

             return view('user.internalaudit.linkchecklist')
                                            ->with('audit_record',$audit_record)
                                            ->with('division_lists',$division_lists)
                                            ->with('area_lists',$area_lists)
                                            ->with('audit_schedule_checklists',$audit_schedule_checklists)
                                            ->with('total_checklists',$total_checklists)
                                            ->with('records',$records)
                                            ->with('checklist_created',$checklist_created)
                                            ->with('audit_id',$id)
                                            ->with('total_checklists_combined',$total_checklists_combined)
                                            ->with('model', $models);
        }
        else{
            return redirect()->back()->with('model', $models);
        }
    }
    public function storelinkchecklist(Request $request)
    {
        $models = DB::table('modenadisable')->First();
        $audit_id = $request->audit_id;
        foreach($request->checklist as $item){
            $checklist_exists = AuditScheduleChecklist::where('audit_id',$audit_id)
                                                        ->where('checklist_id',$item)
                                                        ->first();
            if(!$checklist_exists){
                $checklist_data = new AuditScheduleChecklist;
                $checklist_data->audit_id = $audit_id;
                $checklist_data->checklist_id = $item;
                $checklist_data->save();
            }
        }
        return response()->json([
            'status' => 200,
            ]);
    }
    public function deletelinkchecklist($checklistid,$auditid)
    {
        if($auditid && $checklistid){
            AuditScheduleChecklist::where('audit_id',$auditid)->where('checklist_id',$checklistid)->delete();
            $main_record = AuditChecklistNewRecord::where('audit_schedule_id',$auditid)->where('checklist_id',$checklistid)->first();
            if($main_record){
                AuditChecklistNewRecord::where('audit_schedule_id',$auditid)->where('checklist_id',$checklistid)->delete();
                AuditChecklistNewRecordDetail::where('audit_id',$auditid)->where('checklist_id',$checklistid)->delete();
            }
            return redirect()->route('user.internalaudit.linkchecklist',['id'=> $auditid ])
                                                    ->with('msg', 'Checklist unlinked successfully');
        }else{
            return redirect()->route('user.internalaudit.linkchecklist',['id'=> $auditid ])
                                                    ->with('error', 'Failed to unlink checklist');
        }
    }
    public function folder()
    {

        $targetdate = DB::table('audit')
            ->pluck('year');
        // dd($targetdate);
        $alltargetdates = collect($targetdate);
        // dd($alltargetdates);
        $uniquetargetdates = $alltargetdates->unique()->values()->all();
        // dd($uniquetargetdates);
        $years = [];
        for ($i = 0; $i < count($uniquetargetdates); $i++) {
            $years[] = $uniquetargetdates[$i];
        }
        $years = array_unique($years);
        // dd($years);
        //convert string array to integer array
        $integeryears = array_map(
            function ($value) {
                return (int) $value;
            },
            $years
        );

        sort($integeryears, SORT_NUMERIC);
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.folder')->with('model', $models)->with('years', $integeryears);
    }

    // public function departmentssubfolder($dept){

    //     $targetdate=DB::table('audit')->where('department',$dept)
    //     ->pluck('year');
    //     // dd($targetdate);
    //     $alltargetdates=collect($targetdate);
    //     // dd($alltargetdates);
    //     $uniquetargetdates= $alltargetdates->unique()->values()->all();
    //     // dd($uniquetargetdates);
    //     $years=[];
    //     for($i=0;$i<count($uniquetargetdates);$i++){
    //         $years[]=$uniquetargetdates[$i];

    //     }
    //     $years= array_unique($years);
    //     // dd($years);
    //     //convert string array to integer array
    //     $integeryears = array_map(
    //         function($value) { return (int)$value; },
    //         $years
    //     );

    //     sort($integeryears, SORT_NUMERIC);

    //     $models=DB::table('modenadisable')->First();
    //     return view('user.internalaudit.subfolder')->with('model',$models)->with('years',$integeryears);

    // return gettype($uniquetargetdates);

    // }

    public function auditschedule($year)
    {
        session(['year' => $year]);
        //-----------------Register---------------------

        $years = DB::table('years')->get();
        $mrs = DB::table('representatives')->pluck('email');

        $archive = DB::table('archiveprivileges')->pluck('email');

        $auditorList = $this->getAuditorList();

        $data = Audit::with('departmentObj')
            ->where('year', $year)
            ->wherein('status', array('Planned', 'Rejected', 'Accepted', 'Completed'))
        // ->where('archived',"NO")
            ->orderby('id', 'DESC')
            ->get();
        //  dd($data);

        $dept = DB::table('departments')->pluck('hod');
        //------------------------Auditors list--------------
        $mrs_list = DB::table('representatives')->where('email', Auth::user()->email)->pluck('email');

        $data1 = DB::table('auditors')
            ->get();

        $data2 = DB::table('auditors')
            ->join('multiple', 'auditors.id', '=', 'multiple.id')

            ->select(
                'auditors.id',
                'multiple.id',
                'multiple.Sno',
                'multiple.certificate',
                'auditors.auditorsname'
            )

            ->get();

        //---------------------Auditor log--------------------
        $controllers = DB::table('internalaudits')->get();
        $representatives = DB::table('representatives')->get();

        $list = collect([]);
        foreach ($controllers as $c) {
            $list = $list->push($c->email);
        }

        foreach ($representatives as $representative) {
            $list = $list->push($representative->email);
        }

        $z = $list->unique();
        $departments = DB::table('departments')->get();
        $data3 = DB::table('audit')
            ->wherein('status', array('Completed', 'Permanently closed'))
            ->orderby('id', 'DESC')
            ->get();

        //----------------------Closed audits---------------
        $data4 = DB::table('audit')
            ->where('status', '=', 'Permanently closed')->orderby('id', 'DESC')->get();

//        $issuedepts = Audit::groupBy('department')->pluck('department')->toArray();
//        $departmentnames = array();
//        foreach ($issuedepts as $issuedept) {
//            $dept_names = DB::table('departments')->where('id', $issuedept)->value('departmentname');
//            if ($dept_names) {
//                array_push($departmentnames, $dept_names);
//            }
//        }
        $issuedepts = Audit::pluck('department')->toArray();
        $departmentnames = DB::table('departments')->whereIn('id', $issuedepts)->pluck('departmentname')->toArray();
        $alldeptnames = implode(',', $departmentnames);

        $processstages = array_unique(Audit::pluck('status')->toArray());
        $allpstages = implode(',', $processstages);

        $quarters = Audit::groupBy('quarter')->pluck('quarter')->toArray();
        $allquarters = implode(',', $quarters);

        $auditorsHasAudit = $data->pluck('auditors');
        $auditorForFilters = [];
        foreach ($auditorsHasAudit as $auditorItem) {
            $auditorForFilters = array_merge($auditorForFilters, explode(',', $auditorItem));
        }
        if ($auditorForFilters) {
            $auditorForFilters = implode(',', array_unique($auditorForFilters));
        } else {
            $auditorForFilters = implode(',', $auditorForFilters);
        }

        $auditeesHasAudit = $data->pluck('contact');
        $auditeesForFilters = [];
        foreach ($auditeesHasAudit as $auditeesItem) {
            $auditeesForFilters = array_merge($auditeesForFilters, explode(',', $auditeesItem));
        }
        if ($auditeesForFilters) {
            $auditeesForFilters = implode(',', array_unique($auditeesForFilters));
        } else {
            $auditeesForFilters = implode(',', $auditeesForFilters);
        }

        $auditors = User::where('approved', 1)->orderBy('name')->pluck('name')->toArray();
        $allauditors = implode(',', $auditors);

        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.auditschedule', compact(
            'data', 'data4', 'data3', 'departments',
            'auditors', 'mrs', 'archive', 'mrs_list', 'data1', 'data2',
            'allquarters', 'alldeptnames', 'allpstages', 'allauditors',
            'auditorList', 'auditorForFilters', 'auditeesForFilters'
            ))->withZ($z)->with('model', $models);
    }

    /**
     * @return mixed
     */
    private function getAuditorList()
    {
        $auditors = DB::table('auditors')->pluck('email')->toArray();
        $internalAudits = DB::table('internalaudits')->pluck('email')->toArray();

        return array_merge($auditors, $internalAudits);
    }

    //edit button
    public function editschedule()
    {

        $dept = DB::table('departments')->pluck('hod');
        $auditors = DB::table('auditors')->get();
        $aud = DB::table('auditors')->pluck('email');
        $departments = Departments::active()->get();
        $e = Auth::user()->email;
        $mr = DB::table('representatives')->pluck('email');
        $res = DB::select("select * from internalaudits");
        foreach ($mr as $dat) {

            if ($e == $dat) {
                if (isset($_GET['val'])) {
                    $id = $_GET['val'];
                    $data = DB::table('audit')->where('id', $id)->get();
                    $mn = DB::table('departments')->get();
                    $dept = $_GET['dept'];
                    $deptname = Departments::where('id', $dept)->value('departmentname');
                    //below code related to superadmin
                    $models = DB::table('modenadisable')->First();
                    return view('user/internalaudit/editschedule')->withData($data)->with('mn', $mn)->with('id', $id)->withDept($dept)->withAuditors($auditors)->withAud($aud)->withDepartments($departments)->with('model', $models);
                }
            }
        }
        foreach ($res as $dat) {
            if ($e == $dat->email) {
                if (isset($_GET['val'])) {
                    $id = $_GET['val'];
                    $data = DB::table('audit')->where('id', $id)->get();
                    $mn = DB::table('departments')->get();
                    $dept = $_GET['dept'];
                    $deptname = Departments::where('id', $dept)->value('departmentname');
                    //below code related to superadmin
                    $models = DB::table('modenadisable')->First();
                    return view('user/internalaudit/editschedule')->withData($data)->with('mn', $mn)->with('id', $id)->withDept($dept)->withAuditors($auditors)->withAud($aud)->withDepartments($departments)->with('model', $models);
                }
            }
        }
        return view('user.internalaudit.notauth');
    }

    public function updateschedule(Request $req)
    {
        $validator = Validator::make($req->all(), []);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $id = $req->input('id');
            $dep = $req->input('department');
            $hods = DB::table('departments')->where('id', $dep)->pluck('hod');
            // foreach($hods as $hod)
            // {
            //     $hodemail=$hod;
            // }
            // // dd($hodemail);
            $sco = $req->input('scope');
            // $auditor = $req->input('auditors');
            // // dd($auditor);
            // foreach($auditor as $audit)
            // {
            //     $a[]=$audit;
            // }
            // $aud=implode(',',$a);
            // // $hea = $req->input('head');

            // $emails=DB::table('auditors')->wherein('auditorsname',$auditor)->pluck('email');
            // //  dd($emails);
            // foreach($emails as $email)
            // {
            //     $heademail[]=$email;
            //     //  dd($heademail);
            // }
            // //    dd($heademail);
            //   $mail=implode(',',$heademail);
            // //    dd($mail);

            // $contac = $req->input('contact');
            $now = $req->input('date');
            $time = $req->input('time');
            $focu = $req->input('focus');
            $enddate = $req->input('enddate');

            $ID = Auth::user()->id;
            $data = array(
                'department' => $dep,
                // 'hod'=>$hodemail,
                'scope' => $sco,
                // 'auditors'=>$aud,'email'=>$mail,'contact'=>$contac,
                'time' => $time,
                'date' => $now, 'enddate' => $enddate, 'focus' => $focu,
            );
            //    dd($data);

            DB::table('audit')
                ->where('id', $id)
                ->update($data);
            //  dd($data);

            //below code related to superadmin
            //inserting values to activity table
            $activity = "Upadated Audit Schedule with new Schedule details where Audit Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    //hod approval button
    public function hodreview()
    {

        $data = DB::table('audit')
            ->orderby('createdat', 'DESC')
            ->get();

        //below code related to superadmin
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.auditschedule')->with('model', $models);
    }
    //accept by hod
    public function accept()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(
                'status' => 'Accepted',
            );
            //inserting values to activity table
            $activity = "Audit Request is Accepted and Changed Status to Accepted where Audit Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
            DB::table('audit')->where('id', '=', $id)
                ->update($data);
            // dd($data);
            //mail for auditors
            $audit = DB::table('audit')->where('id', $id)->get();
            foreach ($audit as $dat) {

                $department = $dat->department;
                $scope = $dat->scope;
                $status = $dat->status;
                $from = $dat->date;
                $to = $dat->enddate;
                $auditors = $dat->email;

                $data = array(
                    'department' => $department, 'scope' => $scope,
                    'date' => $from, 'enddate' => $to, 'status' => $status,
                );
                $aud = explode(',', $auditors);
                foreach ($aud as $email) {
                    //    Mail::to($email)->send(new Auditacceptance($data));

                }
            }

            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }
    //propose another dateby hod

    public function propose()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            return view('user.internalaudit.propose')->withId($id)->with('model', $models);
        }
    }
    public function reason(Request $req)
    {
        $reason = $req->input('reason');
        $id = $req->input('id');
        $year = DB::table('audit')->where('id', $id)->value('year');

        $data = array(
            'status' => 'Rejected',
            'reason' => $reason,
        );
        //inserting values to activity table
        $activity = "Audit request is Rejected with Reason as" . $reason . " and Status changed to Rejected where Audit Id is " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        DB::table('audit')
            ->where('id', $id)
            ->update($data);

        //below code related to superadmin
        $models = DB::table('modenadisable')->First();
        //return redirect()->back()->with('model', $models);
        return redirect('/user/internalaudit/auditschedule/' . $year)->with('model', $models);
    }

    //update button for mr
    public function mrreview()
    {
        $mrs = DB::table('representatives')->where('email', Auth::user()->email)->pluck('email');
        $data = DB::table('audit')
            ->orderby('createdat', 'DESC')
            ->get();
        return view('user.internalaudit.auditschedule', compact('data', 'mrs'));
    }
    //updating date
    public function adds()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = DB::table('audit')->where('id', $id)->get();
            // $mn=DB::table('departments')->get();
            // $mn = User::all();
            //   dd($data);
            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            return view('user/internalaudit/update')->withData($data)->with('id', $id)->with('model', $models);
        }
    }
    public function update(Request $req)
    {
        $validator = Validator::make($req->all(), [
            //   'date' =>'before:tomorrow'

        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $status = "Planned";
            $id = $req->input('id');
            $dep = $req->input('department');
            $sco = $req->input('scope');
            $auditor = $req->input('auditors');
            $hea = $req->input('head');
            $contac = $req->input('contact');
            $now = $req->input('date');
            $enddate = $req->input('enddate');
            $check_end_date = strtotime($enddate);
            $get_month = date("M", $check_end_date);
            $q1 = ['Jan', 'Feb', 'Mar'];
            $q2 = ['Apr', 'May', 'Jun'];
            $q3 = ['Jul', 'Aug', 'Sep'];
            $q4 = ['Oct', 'Nov', 'Dec'];
            if (in_array($get_month, $q1)) {
                $quarter = 'Q1';
            } else if (in_array($get_month, $q2)) {
                $quarter = 'Q2';
            } else if (in_array($get_month, $q3)) {
                $quarter = 'Q3';
            } else if (in_array($get_month, $q4)) {
                $quarter = 'Q4';
            }
            $time = $req->input('time');
            $data = array(
                'status' => $status,
                'date' => $now, 'enddate' => $enddate, 'time' => $time, 'quarter' => $quarter,
            );

            DB::table('audit')
                ->where('id', $id)
                ->update($data);

            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            Session::flash('msg', "Details updated successfully");
            return redirect()->back()->with('model', $models);
        }
    }

    //complete button for auditors
    public function auditorreview()
    {
        $audtr = DB::table('auditors')->where('email', Auth::user()->email)->pluck('email');
        $data = DB::table('audit')
            ->orderby('createdat', 'DESC')
            ->get();
        // dd($data);
        //below code related to superadmin
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.auditschedule', compact('data', 'audtr'))->with('model', $models);
    }
    //complete status
    public function complete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(
                'status' => 'Completed',
                'observations' => 'NO',
                'completed' => 'YES',
            );
            DB::table('audit')->where('id', '=', $id)
                ->update($data);
            //inserting values to activity table
            $activity = "Audit Status is Updated to Completed, Observations is Updated to No";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
            // dd($data);
            // echo "<script>alert('Issue Sent For Closing Approval Successfully')</script>";
            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }
    //archive button for archive privilege
    public function archive()
    {

        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(
                'archived' => 'YES',
            );
            DB::table('audit')->where('id', '=', $id)
                ->update($data);
            // dd($data);
            //below code related to superadmin
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    public function auditorslist()
    {
        $mrs = DB::table('representatives')->pluck('email');

        $data1 = Auditor::with('user')->get();

        $data2 = DB::table('auditors')
            ->join('multiple', 'auditors.id', '=', 'multiple.id')

            ->select(
                'auditors.id',
                'multiple.id',
                'multiple.Sno',
                'multiple.certificate',
                'auditors.auditorsname'
            )

            ->get();

        $controllers = DB::table('internalaudits')->get();
        $representatives = DB::table('representatives')->get();

        $list = collect([]);
        foreach ($controllers as $c) {
            $list = $list->push($c->email);
        }

        foreach ($representatives as $representative) {
            $list = $list->push($representative->email);
        }

        $z = $list->unique(); //below code related to superadmin
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.auditorslist', compact('mrs'))->with('z', $z)->with('data1', $data1)->with('data2', $data2)->with('model', $models);
    }

    public function downloading(Request $req)
    {
        $data = DB::table('multiple')
        // ->select('multiple.certificate')
            ->orderby('id', 'ASC')
            ->get();
        // dd($data);
        //below code related to superadmin
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.downloading')->with('data', $data)->with('model', $models);
    }

    /**
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function viewAudit($id)
    {
        try {
            $value = Auditor::find($id);
            $files = DB::table('multiple')->where('id', $id)->get();
            return ['success' => true, 'view' => view('user.internalaudit._detailaudit', compact('value', 'files'))->render()];
        } catch (\Exception $exception) {
            Log::error($exception);
            return ['success' => false, 'message' => 'Handle failure'];
        }
    }

    public function addauditors()
    {
        //$users = DB::table('users')->get();
        $users = User::where('approved',true)->get();
        $controllers = DB::table('internalaudits')->get();
        $representatives = DB::table('representatives')->get();

        $list = collect([]);
        foreach ($controllers as $c) {
            $list = $list->push($c->email);
        }

        foreach ($representatives as $representative) {
            $list = $list->push($representative->email);
        }

        $mrs = $list->unique();
        $emai = Auth::user()->email;
        $respon = DB::select("select * from representatives");
        $res = DB::select("select * from internalaudits");
        foreach ($respon as $dat) {
            if ($emai == $dat->email) {
                //below code related to superadmin
                $models = DB::table('modenadisable')->First();
                return view('user.internalaudit.addauditors', compact('mrs', 'users'))->with('model', $models);
            }
        }
        foreach ($res as $dat) {
            if ($emai == $dat->email) {
                //below code related to superadmin
                $models = DB::table('modenadisable')->First();
                return view('user.internalaudit.addauditors', compact('mrs', 'users'))->with('model', $models);
            }
        }
        //below code related to superadmin
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.notauth')->with('model', $models);
        // return view('user.internalaudit.addauditors',compact('mrs'));
    }

    public function insertauditors(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'auditorsemail' => 'required|exists:users,email|unique:auditors,email',
            'certifications_licensing' => 'required',
            'overall_experience' => 'nullable|max:250',
            // 'certificate' =>  'mimes:doc,pdf,docx,zip,jpg,png',
            //  'experience' => 'max:300',
            // 'date' =>'before:tomorrow'

        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            //assign a variable as an array
            $file = [];
            if ($req->hasFile('file')) {
                $image_array = $req->file('file');
                $array_len = count($image_array);

                for ($i = 0; $i < $array_len; $i++) {
                    $image_size = $image_array[$i]->getClientOriginalName();
                    $image_ext = $image_array[$i]->getClientOriginalExtension();
                    // $new_image_name = rand(123456,999999).".".$image_ext;
                    $new_image_name = $image_size;

                    $destination_path = public_path('storage/images');
                    $image_array[$i]->move($destination_path, $new_image_name);

                    //read the datas into the array
                    $file[] = $new_image_name;
                }
            }

            $cer = count($file) ? implode(',', $file) : null;

            $email = $req->input('auditorsemail');
            $user = DB::table('users')->where('email', $email)->first();
            $audname = $user ? $user->name : null;

            $expe = $req->input('experience');

            $data = array(
                'auditorsname' => $audname,
                'email' => $email,
                'certificate' => $cer,
                'certifications_licensing' => $req->input('certifications_licensing'),
                'overall_experience' => $req->input('overall_experience'),
                'specialization_area' => $req->input('specialization_area'),
                'experience' => $expe,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );

            DB::table('auditors')->insert($data);

            $d = DB::table('auditors')->latest('created_at')->first();

            $id = $d->id;

            $certificate = $d->certificate;

            $expl = explode(',', $certificate);

            foreach ($expl as $certificate) {
                $data1 = array(
                    'id' => $id,
                    'certificate' => $certificate,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                );

                DB::table('multiple')->insert($data1);
            }
            //below code related to superadmin
            //inserting values to activity table
            $activity = "New Auditor  is Added with name as " . $audname . " with Experience as " . $expe;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
            $models = DB::table('modenadisable')->First();
            Session::flash('msg', "Auditors list added successfully");
            return redirect()->route('internalaudit.auditorslist');
        }
    }

    //edit auditor button
    public function editauditor()
    {
        //$users = DB::table('users')->get();
        $users = User::where('approved',true)->get();
        $dept = DB::table('departments')->pluck('hod');
        $auditors = DB::table('auditors')->get();
        $aud = DB::table('auditors')->pluck('email');
        $e = Auth::user()->email;
        $mul = DB::table('multiple')->get();
        $mr = DB::table('representatives')->pluck('email');
        $res = DB::select("select * from internalaudits");
        foreach ($mr as $dat) {

            if ($e == $dat) {
                if (isset($_GET['val'])) {
                    $id = $_GET['val'];
                    $data = DB::table('auditors')->where('id', $id)->get();
                    $mn = DB::table('departments')->get();

                    //below code related to superadmin

                    $models = DB::table('modenadisable')->First();
                    return view('user/internalaudit/editauditor')->withData($data)->withMul($mul)->withUsers($users)->with('mn', $mn)->with('id', $id)->withDept($dept)->withAuditors($auditors)->withAud($aud)->with('model', $models);
                }
            }
        }
        foreach ($res as $dat) {
            if ($e == $dat->email) {
                if (isset($_GET['val'])) {
                    $id = $_GET['val'];
                    $data = DB::table('auditors')->where('id', $id)->get();
                    $mn = DB::table('departments')->get();

                    //below code related to superadmin
                    $models = DB::table('modenadisable')->First();
                    return view('user/internalaudit/editauditor')->withData($data)->withMul($mul)->withUsers($users)->with('mn', $mn)->with('id', $id)->withDept($dept)->withAuditors($auditors)->withAud($aud)->with('model', $models);
                }
            }
        }
        return view('user.internalaudit.notauth');
    }

    public function updateauditor(Request $req)
    {
        $id = $req->input('id');
        $validator = Validator::make($req->all(), [
            'auditorsemail' => [
                'required',
                'exists:users,email',
                Rule::unique('auditors', 'email')->ignore($id),
            ],
            'certifications_licensing' => 'required',
            'overall_experience' => 'nullable|max:250',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $auditor = Auditor::find($id);
            $auditorCertificates = [];
            if ($req->hasFile('file')) {
                $image_array = $req->file('file');
                $array_len = count($image_array);
                $file = [];
                for ($i = 0; $i < $array_len; $i++) {
                    $image_size = $image_array[$i]->getClientOriginalName();
                    $image_ext = $image_array[$i]->getClientOriginalExtension();
                    $new_image_name = $image_size;
                    $destination_path = public_path('storage/images');
                    $image_array[$i]->move($destination_path, $new_image_name);
                    $file[] = $new_image_name;
                }
                if ($auditor->certificate) {
                    $auditorCertificates = array_merge($file, explode(',', $auditor->certificate));
                }

                $cer = implode(',', $file);
            } else {
                $cer = "NULL";
            }

            $email = $req->input('auditorsemail');
            $user = DB::table('users')->where('email', $email)->first();
            $audname = $user ? $user->name : null;

            $expe = $req->input('experience');

            $ID = Auth::user()->id;
            //date_default_timezone_set('Asia/Kolkata');

            if ($cer != "NULL") {
                $data = array(
                    'auditorsname' => $audname,
                    'email' => $email,
                    'certificate' => implode(',', $auditorCertificates),
                    'experience' => $expe,
                    'certifications_licensing' => $req->input('certifications_licensing'),
                    'overall_experience' => $req->input('overall_experience'),
                    'specialization_area' => $req->input('specialization_area'),
                );
                DB::table('auditors')
                    ->where('id', $id)
                    ->update($data);
            }
            if ($cer == "NULL") {
                $data = array(
                    'auditorsname' => $audname,
                    'email' => $email,
                    'experience' => $expe,
                    'certifications_licensing' => $req->input('certifications_licensing'),
                    'overall_experience' => $req->input('overall_experience'),
                    'specialization_area' => $req->input('specialization_area'),
                );

                DB::table('auditors')
                    ->where('id', $id)
                    ->update($data);
            }
            if ($cer != "NULL") {
                $certificate = $cer;

                $expl = explode(',', $certificate);

                foreach ($expl as $certificate) {

                    $data1 = array('id' => $id, 'certificate' => $certificate, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));

                    DB::table('multiple')
                        ->where('id', $id)
                        ->insert($data1);
                }
            }

            //below code related to superadmin
            //inserting values to activity table
            $activity = "Updated Auditor with name as " . $audname . " with Experience as " . $expe;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
            $models = DB::table('modenadisable')->First();
            Session::flash('msg', "Auditors item updated successfully");
            return redirect('/user/internalaudit/auditorslist')->with('model', $models);
        }
    }
    public function deletefile(Request $req)
    {

        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $riskinfo = DB::table('multiple')->where('Sno', $id)->pluck('certificate');
            // dd($riskinfo);
            $activity = "auditor certificate is Deleted with file name " . $riskinfo;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);

            DB::delete('delete from multiple where Sno = :eid', ['eid' => $id]);
            $models = DB::table('modenadisable')->First();
            Session::flash('msg', "Attachment file removed successfully");
            return redirect()->back()->with('model', $models);
        }
    }
    public function auditlog()
    {
        $controllers = DB::table('internalaudits')->get();
        $representatives = DB::table('representatives')->get();
        $mrs = DB::table('representatives')->pluck('email');
        $list = collect([]);
        foreach ($controllers as $c) {
            $list = $list->push($c->email);
        }

        foreach ($representatives as $representative) {
            $list = $list->push($representative->email);
        }

        $z = $list->unique();
        // dd($a);
        $departments = DB::table('departments')->get();
        $data3 = DB::table('audit')
            ->wherein('status', array('Completed', 'Permanently closed'))
            ->orderby('id', 'DESC')
            ->get();
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.auditlog', compact('data3', 'mrs', 'z'))->withDepartments($departments)->with('model', $models);
        // ->withEmail($email);
    }

    public function closedaudits()
    {
        $controllers = DB::table('internalaudits')->get();
        $representatives = DB::table('representatives')->get();
        $mrs = DB::table('representatives')->pluck('email');
        $list = collect([]);
        foreach ($controllers as $c) {
            $list = $list->push($c->email);
        }

        foreach ($representatives as $representative) {
            $list = $list->push($representative->email);
        }

        $z = $list->unique();
        // dd($a);
        $departments = DB::table('departments')->get();
        $data4 = DB::table('audit')
            ->where('status', '=', 'Permanently closed')->orderby('id', 'DESC')->get();

        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.closedaudits', compact('data4', 'mrs', 'z'))->withDepartments($departments)->with('model', $models);
        // ->withEmail($email);
    }

    //close audit
    public function close()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(
                'status' => 'Permanently closed',
                'completed' => 'YES',
            );
            DB::table('audit')->where('id', '=', $id)
                ->update($data);
            // dd($data);
            // echo "<script>alert('Issue Sent For Closing Approval Successfully')</script>";
            $models = DB::table('modenadisable')->First();
            return redirect('/user/internalaudit/auditlog')->with('model', $models);
        }
    }

    // add internal audit summary
    public function addss()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            // $type=Ncrtype::all();
            // $type = DB::table('ncrtype')->get();
            $data = DB::table('audit')->where('id', $id)->get();
            //   dd($data);
            $models = DB::table('modenadisable')->First();
            return view('user/internalaudit/addobservations')->withData($data)->with('id', $id)->with('model', $models);
        }
    }
    public function addobservations(Request $req)
    {
        $validator = Validator::make($req->all(), [
            //   'date' =>'before:tomorrow'

        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $observations = "YES";
            $ncrstatus = "Open";
            $id = $req->input('id');
            $dep = $req->input('department');
            $hea = $req->input('head');
            $now = $req->input('date');
            // $ncr = $req->input('ncr');

            $high = $req->input('highno');
            $moderate = $req->input('moderateno');
            $low = $req->input('lowno');
            if ($high == "0" && $moderate == "0" && $low == "0") {
                $observations = "NO";
                $ncrstatus = " ";
            }
            // $now = new DateTime();
            $data = array('observations' => $observations, 'high' => $high, 'moderate' => $moderate, 'low' => $low, 'ncrstatus' => $ncrstatus);
            // dd($type);
            //   dd($data);
            DB::table('audit')
                ->where('id', $id)
                ->update($data);
            // dd($data);
            //inserting values to activity table
            $activity = "Audit is Updated with observation as YES and NCR Status as Open where Audit Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
            $models = DB::table('modenadisable')->First();
            return redirect('/user/internalaudit/auditlog')->with('model', $models);
        }
    }
    //close ncr
    public function closencr()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(
                'ncrstatus' => 'Closed',
            );
            DB::table('audit')->where('id', '=', $id)
                ->update($data);
            //inserting values to activity table
            $activity = "Audit is Updated with NCR Status as Closed where Audit Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
            // dd($data);
            // echo "<script>alert('Issue Sent For Closing Approval Successfully')</script>";
            $models = DB::table('modenadisable')->First();
            return redirect('/user/internalaudit/auditlog')->with('model', $models);
        }
    }

    //completed audit plans
    public function completedaudits()
    {
        $departments = Departments::all();
        $data = DB::table('audit')
            ->where('status', '=', 'Permanently closed')->orderby('id', 'DESC')->get();
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.completedaudits')->with('data', $data)->with('departments', $departments)->with('model', $models);
    }

    //delete audit
    public function deleteaudit()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            //inserting data to activities table
            $name = DB::table('audit')->where('id', $id)->pluck('scope');
            $activity = "An audit is Removed with Scope " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);

            DB::delete('delete from audit where id = :eid', ['eid' => $id]);
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    //delete auditor
    public function deleteauditor()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            $m = count($id);
            $n = 0;

            foreach ($id as $d) {
                if ($n < $m) {
                    $ids = substr($d, 0, -1);

                    //inserting data to activities table
                    $name = DB::table('auditors')->where('id', $ids)->pluck('auditorsname');
                    $activity = "An auditor is Removed with Name " . $name;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                    DB::table('viewactivity')->insert($datas);

                    DB::delete('delete from auditors where id = :eid', ['eid' => $ids]);
                    DB::delete('delete from multiple where id = :eid', ['eid' => $ids]);
                }
                $n = $n + 1;
            }
        }
        $models = DB::table('modenadisable')->First();
        return redirect('/user/internalaudit/auditorslist')->with('model', $models);
    }

    //edit audit
    public function editreq()
    {

        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = DB::table('audit')->where('id', $d)->first();
                }
                $n = $n + 1;
            }
        }
        $auditors = Auditor::get();
        $users = User::where('approved', 1)->where('is_email_verified', 1)->get();
        $standards = Standards::all();
        $models = DB::table('modenadisable')->First();

        return view('user/internalaudit/editrequest')->withData($data)->with('id', $id)->with('auditors', $auditors)->with('users', $users)->with('standards', $standards)->with('model', $models);
    }

    public function editrequest(Request $req)
    {
        $m = count($req->department);

        // $scope = $req->input('scope');
        // $auditors = $req->input('auditors');
        $quarter = $req->input('quarter');
        $date = $req->input('date');
        $enddate = $req->input('enddate');
        $time = $req->input('time');
        $budget = $req->input('budget');
        $focus = $req->input('focus');
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = DB::table('audit')->where('id', $d)->first();
            }
            $n = $n + 1;
        }
        $n = 0;

        foreach ($data2 as $dat) {

            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    $auditors = $req->input('auditors' . $s);
                    $auditors = implode(',', $auditors);

                    $auditors_arrs = explode(',', $auditors);
                    $new_auditors = array();
                    foreach ($auditors_arrs as $auditors_arr) {
                        $auditor_exists = User::where('name', $auditors_arr)->first();
                        if ($auditor_exists) {
                            array_push($new_auditors, $auditor_exists->email);
                        }
                    }
                    $auditmail = implode(',', $new_auditors);

                    $auditee = $req->input('auditee' . $s);
                    $auditee = implode(',', $auditee);

                    $scope = $req->input('scope' . $s);
                    $scope = implode(',', $scope);

                    $data = array(
                        'scope' => $scope,
                        'quarter' => $req->quarter[$n],
                        'date' => $req->date[$n],
                        'enddate' => $req->enddate[$n],
                        'time' => $req->time[$n],
                        'budgeted' => $req->budget[$n],
                        'focus' => $req->focus[$n],
                        'auditors' => $auditors,
                        'contact' => $auditee,
                        'email' => $auditmail,
                    );
                    DB::table('audit')
                        ->where('id', $s)
                        ->update($data);
                    //insert data to activity table superadmin code
                    $activity = "Audit  is  Edited and Updated with new Details  where Risk ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                    DB::table('viewactivity')->insert($datas);
                    //end of superadmin code

                }
            }

            $n = $n + 1;
        }

        $models = DB::table('modenadisable')->first();

        Session::flash('msg', 'Audit issue edited successfully');

        return redirect()->back()->with('model', $models);
    }

    public function analysis(Request $req)
    {

        //------------------Analysis---------------------

        $years = DB::table('years')->get();
        // dd($y);
        $y = $req->input('year');
        $ldate = date('Y-m-d');
        // dd($ldate);
        $date = date('Y', strtotime($ldate));
        //   dd($date);
        $previousyear = $date - 1;
        // dd($previousyear);

        // $yearwise =DB::select(" SELECT DISTINCT year FROM audit  order by id ASC");
        // dd($yearwise);

        $audits = DB::table('audit')->get();
        // ->where('year','=','2019')

        // dd($audits);
        // foreach($audits as $audit)
        // {
        //      $id = $audit->id;
        //      $department = $audit->department;
        //      $year = $audit->year;
        //      $observations = $audit->observations;

        //     $data = array('id'=>$id,
        //                     'department'=>$department,
        //                     'year'=>$year,
        //                     'observations'=>$observations);
        //                     // dd($data);
        //     foreach($data as $dat)
        //     {
        //         // dd($data);
        //         if($year==$previousyear)
        //         {
        //   dd($data);
        // dd($year);
        $names = DB::select("select code from departments order by departmentname ASC");
        $x = [];
        foreach ($names as $key) {
            array_push($x, $key->code);
        }
        $chart = new AuditAnalysis;
        $chart->title("Total NC status in $previousyear Audit");
        $chart->labels($x);
        $num = DB::select("select s.id,r.high AS number
                                   FROM  audit AS r right JOIN departments AS s
                                   ON r.department= s.id
                                   and
                                    observations='YES'and year=$previousyear
                                   order by s.departmentname ASC
                                 ");
        //  dd($num);
        $num1 = DB::select("select s.id,r.moderate AS number
                   FROM  audit AS r right JOIN departments AS s
                   ON r.department= s.id
                   and
                    observations='YES'and year=$previousyear
                   order by s.departmentname ASC
                 ");
        //  dd($num1);
        $num2 = DB::select("select s.id, r.low AS number
                   FROM  audit AS r right JOIN departments AS s
                   ON r.department= s.id
                   and
                    observations='YES'and year=$previousyear
                   order by s.departmentname ASC
                 ");

        $a = [];
        foreach ($num as $key) {
            array_push($a, $key->number);
        }

        $b = [];
        foreach ($num1 as $key) {
            array_push($b, $key->number);
        }
        $c = [];
        foreach ($num2 as $key) {
            array_push($c, $key->number);
        }
        //   dd($a);
        $chart->dataset('High', 'bar', $a)->backgroundcolor('#f7533e')->color('#f7533e');
        // dd($chart);
        $chart->dataset('Moderate', 'bar', $b)->backgroundcolor('orange')->color('orange');
        $chart->dataset('Low', 'bar', $c)->backgroundcolor('#a6ff00')->color('#a6ff00');
        $chart->height(400);
        //         }

        //     }
        // }
        $processes = DB::select("select year from years order by id ASC");
        // dd($processes);
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->year);
        }

        $completed = DB::select("select s.year,COUNT(r.completed)AS number
                           FROM  audit AS r
                           right JOIN years AS s
                           ON r.year= s.year
                           and  r.completed='YES'
                           group by s.year
                           order by s.year ASC
                       ");

        // dd($completed);
        $planned = DB::select("select s.year,COUNT(r.planned)AS number
                               FROM  audit AS r
                               right JOIN years AS s
                               ON r.year= s.year
                               and r.planned='YES'
                               group by s.year
                               order by s.year ASC
                           ");
        //  dd($planned);
        $closed = DB::select("select s.year,COUNT(r.status)AS number
                            FROM  audit AS r
                            right JOIN years AS s
                            ON r.year= s.year
                            and r.status='Permanently closed'
                            group by s.year
                            order by s.year ASC
        ");
        //   dd($closed);
        $closep = [];
        foreach ($closed as $key) {
            array_push($closep, $key->number);
        }
        $comp = [];
        foreach ($completed as $key) {
            array_push($comp, $key->number);
        }
        $plan = [];
        foreach ($planned as $key) {
            array_push($plan, $key->number);
        }

        $chart4 = new AuditAnalysis;
        $chart4->title("Completed Audit-Planned Wise");
        $chart4->labels($process);
        $chart4->dataset('Planned', 'bar', $plan)->backgroundcolor('#a6ff00')->color('#a6ff00');
        $chart4->dataset('Completed', 'bar', $comp)->backgroundcolor('yellow')->color('yellow');
        $chart4->height(400);

        $chart5 = new AuditAnalysis;
        $chart5->title("Closed Audit-Completed Wise");
        $chart5->labels($process);
        $chart5->dataset('Completed', 'bar', $comp)->backgroundcolor('yellow')->color('yellow');
        $chart5->dataset('Closed', 'bar', $closep)->backgroundcolor('#2887ed')->color('#2887ed');
        $chart5->height(400);

        $models = DB::table('modenadisable')->First();

        return view('user.internalaudit.analysis', compact('chart', 'chart4', 'chart5'))->withYears($years)->with('model', $models);
    }

    public function analysisfilter(Request $req)
    {


        $audits = DB::table('audit')->get();
        $inputyear = null;

        $inputyear = $req->input('year');


        $names = DB::select("select code from departments order by departmentname ASC");
        $x = [];
        foreach ($names as $key) {
            array_push($x, $key->code);
        }
        $chart = new AuditAnalysis;
        $chart->title("Total NC status in $inputyear Audit");
        $chart->labels($x);
        $num = DB::select("select s.id,r.high AS number
                                        FROM  audit AS r right JOIN departments AS s
                                        ON r.department= s.id
                                        and
                                         observations='YES'and year=$inputyear
                                        order by s.departmentname ASC
                                      ");
        //  dd($num);
        $num1 = DB::select("select s.id,r.moderate AS number
                        FROM  audit AS r right JOIN departments AS s
                        ON r.department= s.id
                        and
                         observations='YES'and year=$inputyear
                        order by s.departmentname ASC
                      ");
        //  dd($num1);
        $num2 = DB::select("select s.id, r.low AS number
                        FROM  audit AS r right JOIN departments AS s
                        ON r.department= s.id
                        and
                         observations='YES'and year=$inputyear
                        order by s.departmentname ASC
                      ");
        $a = [];
        foreach ($num as $key) {
            array_push($a, $key->number);
        }

        $b = [];
        foreach ($num1 as $key) {
            array_push($b, $key->number);
        }
        $c = [];
        foreach ($num2 as $key) {
            array_push($c, $key->number);
        }
        //   dd($a);
        $chart->dataset('High', 'bar', $a)->backgroundcolor('#f7533e')->color('#f7533e');
        // dd($chart);
        $chart->dataset('Moderate', 'bar', $b)->backgroundcolor('orange')->color('orange');
        $chart->dataset('Low', 'bar', $c)->backgroundcolor('#a6ff00')->color('#a6ff00');
        $chart->height(400);
        // }
        //     }
        // }
        $processes = DB::select("select year from years order by id ASC");
        // dd($processes);
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->year);
        }

        $completed = DB::select("select s.year,COUNT(r.completed)AS number
                        FROM  audit AS r
                        right JOIN years AS s
                        ON r.year= s.year
                        and  r.completed='YES'
                        group by s.year
                        order by s.year ASC
                    ");

        // dd($completed);
        $planned = DB::select("select s.year,COUNT(r.planned)AS number
                            FROM  audit AS r
                            right JOIN years AS s
                            ON r.year= s.year
                            and r.planned='YES'
                            group by s.year
                            order by s.year ASC
                        ");
        //  dd($planned);
        $closed = DB::select("select s.year,COUNT(r.status)AS number
                         FROM  audit AS r
                         right JOIN years AS s
                         ON r.year= s.year
                         and r.status='Permanently closed'
                         group by s.year
                         order by s.year ASC
     ");
        //   dd($closed);
        $closep = [];
        foreach ($closed as $key) {
            array_push($closep, $key->number);
        }
        $comp = [];
        foreach ($completed as $key) {
            array_push($comp, $key->number);
        }
        $plan = [];
        foreach ($planned as $key) {
            array_push($plan, $key->number);
        }

        $chart4 = new AuditAnalysis;
        $chart4->title("Completed Audit-Planned Wise");
        $chart4->labels($process);
        $chart4->dataset('Planned', 'bar', $plan)->backgroundcolor('#a6ff00')->color('#a6ff00');
        $chart4->dataset('Completed', 'bar', $comp)->backgroundcolor('yellow')->color('yellow');
        $chart4->height(400);

        $chart5 = new AuditAnalysis;
        $chart5->title("Closed Audit-Completed Wise");
        $chart5->labels($process);
        $chart5->dataset('Completed', 'bar', $comp)->backgroundcolor('yellow')->color('yellow');
        $chart5->dataset('Closed', 'bar', $closep)->backgroundcolor('#2887ed')->color('#2887ed');
        $chart5->height(400);
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.analysis', compact('chart', 'chart4', 'chart5'))->with('model', $models);
    }

    public function checklistlibrary()
    {
        $departmentIdWithAuditCheckList = AuditChecklist::select('department_id')->distinct()->pluck('department_id');
        $departmentActives = Departments::active()->get();
        $departmentWithAuditCheckList = Departments::whereIn('id', $departmentIdWithAuditCheckList)->get();
        $departments = $departmentActives->merge($departmentWithAuditCheckList)->unique()->sortBy('id');
        $divisions = Division::all();
        $sub_divisions = Area::all();
        $models = DB::table('modenadisable')->First();
        $auditors = Auditor::pluck('email')->toArray();
        $type = null;
        $isoclauses = ['ISO 9001', 'ISO 14001', 'ISO 45001', 'ISO 50001', 'ISO 22000'];
        $isoclauses_icon = 'fa fa-database';

        /**--analysis starts */
        $departmentdatas = DB::table('departments')->orderBy('departmentname', 'ASC')->get();
        $depcode = [];
        foreach ($departmentdatas as $departmentdata) {
            array_push($depcode, $departmentdata->code);
        }

        $checklistdatas = AuditChecklist::select('departments.id', DB::raw('COUNT(audit_checklist.checklist_name)AS number'))
            ->rightJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'audit_checklist.department_id')
                    ->where('audit_checklist.is_deleted', 0);
            })
            ->groupBy('departments.id')
            ->orderBy('departments.departmentname', 'ASC')
            ->get();

        $checklist = [];
        foreach ($checklistdatas as $checklistdata) {
            array_push($checklist, $checklistdata->number);
        }

        /*
        $conformances = AuditChecklistConformance::all();
        $test = DB::table('audit_checklist_new_record_details')->select('departments.id',DB::raw('COUNT(audit_checklist_new_record_details.conformance_id)AS number'))
        ->rightJoin('departments',function($join)
        {
        $join->on('departments.id','=','audit_checklist_new_record_details.department_id');
        })
        ->groupBy('departments.id')
        ->orderBy('departments.departmentname', 'ASC')
        ->get();
         */
        $stage_ok = DB::table('audit_checklist_new_record_details')->select('departments.id', DB::raw('COUNT(audit_checklist_new_record_details.conformance_id)AS number'))
            ->rightJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'audit_checklist_new_record_details.department_id')
                    ->where('audit_checklist_new_record_details.conformance_id', 1);
            })
            ->groupBy('departments.id')
            ->orderBy('departments.departmentname', 'ASC')
            ->get();

        $stage_nca = DB::table('audit_checklist_new_record_details')->select('departments.id', DB::raw('COUNT(audit_checklist_new_record_details.conformance_id)AS number'))
            ->rightJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'audit_checklist_new_record_details.department_id')
                    ->where('audit_checklist_new_record_details.conformance_id', 2);
            })
            ->groupBy('departments.id')
            ->orderBy('departments.departmentname', 'ASC')
            ->get();

        $stage_ncb = DB::table('audit_checklist_new_record_details')->select('departments.id', DB::raw('COUNT(audit_checklist_new_record_details.conformance_id)AS number'))
            ->rightJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'audit_checklist_new_record_details.department_id')
                    ->where('audit_checklist_new_record_details.conformance_id', 3);
            })
            ->groupBy('departments.id')
            ->orderBy('departments.departmentname', 'ASC')
            ->get();

        $stage_pi = DB::table('audit_checklist_new_record_details')->select('departments.id', DB::raw('COUNT(audit_checklist_new_record_details.conformance_id)AS number'))
            ->rightJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'audit_checklist_new_record_details.department_id')
                    ->where('audit_checklist_new_record_details.conformance_id', 4);
            })
            ->groupBy('departments.id')
            ->orderBy('departments.departmentname', 'ASC')
            ->get();

        $stage_na = DB::table('audit_checklist_new_record_details')->select('departments.id', DB::raw('COUNT(audit_checklist_new_record_details.conformance_id)AS number'))
            ->rightJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'audit_checklist_new_record_details.department_id')
                    ->where('audit_checklist_new_record_details.conformance_id', 5);
            })
            ->groupBy('departments.id')
            ->orderBy('departments.departmentname', 'ASC')
            ->get();

        $ok = [];
        foreach ($stage_ok as $key) {
            array_push($ok, $key->number);
        }

        $NC_A = [];
        foreach ($stage_nca as $key) {
            array_push($NC_A, $key->number);
        }

        $NC_B = [];
        foreach ($stage_ncb as $key) {
            array_push($NC_B, $key->number);
        }

        $PI = [];
        foreach ($stage_pi as $key) {
            array_push($PI, $key->number);
        }

        $NA = [];
        foreach ($stage_na as $key) {
            array_push($NA, $key->number);
        }

        $total_ok = DB::table('audit_checklist_new_record_details')
            ->where('audit_checklist_new_record_details.conformance_id', 1)
            ->get()->count();

        $total_nca = DB::table('audit_checklist_new_record_details')
            ->where('audit_checklist_new_record_details.conformance_id', 2)
            ->get()->count();

        $total_ncb = DB::table('audit_checklist_new_record_details')
            ->where('audit_checklist_new_record_details.conformance_id', 3)
            ->get()->count();

        $total_pi = DB::table('audit_checklist_new_record_details')
            ->where('audit_checklist_new_record_details.conformance_id', 4)
            ->get()->count();

        $total_na = DB::table('audit_checklist_new_record_details')
            ->where('audit_checklist_new_record_details.conformance_id', 5)
            ->get()->count();

        $totalchecklist = AuditChecklist::where('is_deleted', 0)->get()->count();
        $totalrecords = AuditChecklistNewRecord::get()->count();
        $completedrecords = AuditChecklistNewRecord::where('record_status', 'Completed')->get()->count();
        $pendingrecords = AuditChecklistNewRecord::where('record_status', 'Pending')->get()->count();

        $completedrecordsdeps = DB::table('audit_checklist_new_records')->select('departments.id', DB::raw('COUNT(audit_checklist_new_records.record_status)AS number'))
            ->rightJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'audit_checklist_new_records.department_id')
                    ->where('audit_checklist_new_records.record_status', 'Completed');
            })
            ->groupBy('departments.id')
            ->orderBy('departments.departmentname', 'ASC')
            ->get();

        $completedrecordsdept = [];
        foreach ($completedrecordsdeps as $item) {
            array_push($completedrecordsdept, $item->number);
        }

        $pendingrecordsdeps = DB::table('audit_checklist_new_records')->select('departments.id', DB::raw('COUNT(audit_checklist_new_records.record_status)AS number'))
            ->rightJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'audit_checklist_new_records.department_id')
                    ->where('audit_checklist_new_records.record_status', 'Pending');
            })
            ->groupBy('departments.id')
            ->orderBy('departments.departmentname', 'ASC')
            ->get();

        $pendingrecordsdept = [];
        foreach ($pendingrecordsdeps as $item) {
            array_push($pendingrecordsdept, $item->number);
        }
        /**--analysis ends */

        return view('user.internalaudit.checklistlibraryindex')
            ->with('model', $models)
            ->with('auditors', $auditors)
            ->with('type', $type)
            ->with('isoclauses', $isoclauses)
            ->with('isoclauses_icon', $isoclauses_icon)
            ->withDepartments($departments)
            ->with('alldivisions', $divisions)
            ->with('allsubdivisions', $sub_divisions)
            ->with('totalchecklist', $totalchecklist)
            ->with('totalrecords', $totalrecords)
            ->with('completedrecords', $completedrecords)
            ->with('pendingrecords', $pendingrecords)
            ->with('ok', json_encode($ok, JSON_NUMERIC_CHECK))
            ->with('nc_a', json_encode($NC_A, JSON_NUMERIC_CHECK))
            ->with('nc_b', json_encode($NC_B, JSON_NUMERIC_CHECK))
            ->with('pi', json_encode($PI, JSON_NUMERIC_CHECK))
            ->with('na', json_encode($NA, JSON_NUMERIC_CHECK))
            ->with('total_ok', json_encode($total_ok, JSON_NUMERIC_CHECK))
            ->with('total_nca', json_encode($total_nca, JSON_NUMERIC_CHECK))
            ->with('total_ncb', json_encode($total_ncb, JSON_NUMERIC_CHECK))
            ->with('total_pi', json_encode($total_pi, JSON_NUMERIC_CHECK))
            ->with('total_na', json_encode($total_na, JSON_NUMERIC_CHECK))
            ->with('completedrecordsdept', json_encode($completedrecordsdept, JSON_NUMERIC_CHECK))
            ->with('pendingrecordsdept', json_encode($pendingrecordsdept, JSON_NUMERIC_CHECK))
            ->with('depcode', json_encode($depcode, JSON_NUMERIC_CHECK))
            ->with('checklist', json_encode($checklist, JSON_NUMERIC_CHECK))
            ->with('departmentActives', $departmentActives);
    }

    // public function addchecklist(AddAuditChecklistRequest $request)
    // {
    //     $checklist_name = $request->input('checklist_name');
    //     $department_id = $request->input('department_id');
    //     // setting count for checklist
    //     $exists = AuditChecklist::where('department_id', $department_id)->get();
    //     $count = '';
    //     if (count($exists) > 0) {
    //         foreach ($exists as $exist) {
    //             $db_count = $exist->count;
    //             $count = $db_count + 1;
    //         }

    //     } else {
    //         $count = 1;
    //     }

    //     $admins = DB::table('admins')->where('email',Auth::user()->email)->first();
    //     if($admins){
    //         $addedby_id = DB::table('users')->where('email',Auth::user()->email)->value('id');
    //     }
    //     else{
    //         $addedby_id = Auth::user()->id;
    //     }

    //     $data = array(
    //         'checklist_name' => $checklist_name,
    //         'department_id' => $department_id,
    //         'count' => $count,
    //         'added_by_user_id' => $addedby_id,
    //         'status'=> 'Awaiting Approval'
    //     );
    //     AuditChecklist::create($data);
    //     return [
    //         "data" => null,
    //         "error" => null,
    //     ];
    // }

    // public function selectchecklistaddercategory(Request $request)
    // {
    //   $category = $request->input('category');
    //     if($category == 'department'){
    //         $data = DB::table('departments')->get();
    //     }
    //     else if($category == 'division'){
    //         $data = Division::all();
    //     }
    //     else if($category == 'area'){
    //         $data = Area::all();
    //     }
    //     return response()->json([
    //       'status'=>200,
    //       'data'=>$data
    //   ]);
    // }
    public function addchecklist(AddAuditChecklistRequest $request)
    {
        $checklist_name = $request->input('checklist_name');
        if ($request->input('department_id_chk') != null) {
            $department_id = $request->input('department_id_chk');
            $exists = AuditChecklist::where('department_id', $department_id)->get(); //setting count for checklist
            $count = '';
            if (count($exists) > 0) {
                foreach ($exists as $exist) {
                    $db_count = $exist->count;
                    $count = $db_count + 1;
                }

            } else {
                $count = 1;
            }
            $division_id = null;
            $sub_division_id = null;
        }
        if ($request->input('division_id_chk') != null) {
            $division_id = $request->input('division_id_chk');
            $exists = AuditChecklist::where('division_id', $division_id)->get();
            $count = '';
            if (count($exists) > 0) {
                foreach ($exists as $exist) {
                    $db_count = $exist->count;
                    $count = $db_count + 1;
                }

            } else {
                $count = 1;
            }
            $department_id = null;
            $sub_division_id = null;
        }
        if ($request->input('sub_division_id_chk') != null) {
            $sub_division_id = $request->input('sub_division_id_chk');
            $exists = AuditChecklist::where('area_id', $sub_division_id)->get();
            $count = '';
            if (count($exists) > 0) {
                foreach ($exists as $exist) {
                    $db_count = $exist->count;
                    $count = $db_count + 1;
                }

            } else {
                $count = 1;
            }
            $department_id = null;
            $division_id = null;
        }

        $admins = DB::table('admins')->where('email', Auth::user()->email)->first();
        if ($admins) {
            $addedby_id = DB::table('users')->where('email', Auth::user()->email)->value('id');
        } else {
            $addedby_id = Auth::user()->id;
        }

        $data = array(
            'checklist_name' => $checklist_name,
            'department_id' => $department_id,
            'division_id' => $division_id,
            'area_id' => $sub_division_id,
            'count' => $count,
            'added_by_user_id' => $addedby_id,
            'status' => 'Awaiting Approval',
        );

        $checklistdeptexists = AuditChecklist::where('department_id', $department_id)
            ->where('checklist_name', $checklist_name)
            ->get();
        $checklistdivisionexists = AuditChecklist::where('division_id', $division_id)
            ->where('checklist_name', $checklist_name)
            ->get();
        $checklistareaexists = AuditChecklist::where('area_id', $sub_division_id)
            ->where('checklist_name', $checklist_name)
            ->get();

        if ($request->input('department_id_chk') != null && count($checklistdeptexists) > 0) {
            return response()->json([
                'status' => 403,
                'chkerrmsg' => 'This checklist already exists for this department',
            ]);
        } elseif ($request->input('division_id_chk') != null && count($checklistdivisionexists) > 0) {
            return response()->json([
                'status' => 403,
                'chkerrmsg' => 'This checklist already exists for this division',
            ]);
        } elseif ($request->input('sub_division_id_chk') != null && count($checklistareaexists) > 0) {
            return response()->json([
                'status' => 403,
                'chkerrmsg' => 'This checklist already exists for this subdivision',
            ]);
        } else {
            AuditChecklist::create($data);
            return response()->json([
                'status' => 200,
                'data' => null,
            ]);
        }
    }

    public function mainchecklistapprove($id)
    {
        $admins = DB::table('admins')->where('email', Auth::user()->email)->first();
        if ($admins) {
            $approvedby_id = DB::table('users')->where('email', Auth::user()->email)->value('id');
        } else {
            $approvedby_id = Auth::user()->id;
        }
        AuditChecklist::where('id', $id)->update([
            'status' => 'Approved',
            'approved_by_user_id' => $approvedby_id,
            'approved_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        $models = DB::table('modenadisable')->First();
        return redirect()->back();
    }

    public function editchecklistname(Request $request)
    {
        $checkid = $request->input('checklisteditid');
        $name = $request->input('checklist_name_value');
        $admins = DB::table('admins')->where('email', Auth::user()->email)->first();
        if ($admins) {
            $updatedby_id = DB::table('users')->where('email', Auth::user()->email)->value('id');
        } else {
            $updatedby_id = Auth::user()->id;
        }
        $controller_id = $request->checklist_controller_id_value;
        if($request->checklist_controller_value=="department"){
            $checklistdeptexists = AuditChecklist::where('department_id', $controller_id)
                                                ->where('checklist_name', $name)
                                                ->where('id','!=',$checkid)
                                                ->get();
            if(count($checklistdeptexists) > 0){
                return response()->json([
                    'status' => 403,
                    'chkerrmsg' => 'This checklist already exists for this department',
                ]);
            }else{
                $data = array(
                    'checklist_name' => $name,
                    'updated_by_user_id' => $updatedby_id,
                );
                AuditChecklist::where('id', $checkid)->update($data);
                return response()->json([
                    'status' => 200,
                    'data' => null,
                ]);
            }
        }
        if($request->checklist_controller_value=="division"){
            $checklistdivisionexists = AuditChecklist::where('division_id', $controller_id)
                                                    ->where('checklist_name', $name)
                                                    ->where('id','!=',$checkid)
                                                    ->get();
            if(count($checklistdivisionexists) > 0){
                return response()->json([
                    'status' => 403,
                    'chkerrmsg' => 'This checklist already exists for this division',
                ]);
            }else{
                $data = array(
                    'checklist_name' => $name,
                    'updated_by_user_id' => $updatedby_id,
                );
                AuditChecklist::where('id', $checkid)->update($data);
                return response()->json([
                    'status' => 200,
                    'data' => null,
                ]);
            }
        }
        if($request->checklist_controller_value=="area"){
            $checklistareaexists = AuditChecklist::where('area_id', $controller_id)
                                                ->where('checklist_name', $name)
                                                ->where('id','!=',$checkid)
                                                ->get();
            if(count($checklistareaexists) > 0){
                return response()->json([
                    'status' => 403,
                    'chkerrmsg' => 'This checklist already exists for this subdivision',
                ]);
            }else{
                $data = array(
                    'checklist_name' => $name,
                    'updated_by_user_id' => $updatedby_id,
                );
                AuditChecklist::where('id', $checkid)->update($data);
                return response()->json([
                    'status' => 200,
                    'data' => null,
                ]);
            }
        }

    }

    public function deletechecklistname($id)
    {
        $checklist = AuditChecklist::where('id', $id)->first();
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.deletechecklistname')
            ->with('model', $models)
            ->with('checklist', $checklist);
    }

    public function confirmdeletechecklistname(Request $request)
    {
        $checklistid = $request->checklistid;
        if ($request->delete_reason == 'other') {
            AuditChecklist::where('id', $checklistid)->update([
                'delete_status' => 'yes',
                'delete_reason' => $request->other_reason,
            ]);

            //sending mail for approve checklist privileged user
            $emails = EmailNotification::where('module', 'auditmanagement')
                ->where('item', 'Checklist Removal Request')
                ->where('is_blocked', false)
                ->first();

            if ($emails) {
                $url = config('app.url');
                $removal_approvers = UserGate::where('key', 'audit-checklist-records-approve')->get();
                foreach ($removal_approvers as $removal_approver) {
                    $admin_detail_exists = DB::table('admins')->where('id', $removal_approver->users_id)->first();
                    if ($admin_detail_exists) {
                        $approver_details_email = $admin_detail_exists->email;
                        $approver_name = $admin_detail_exists->name;
                    } else {
                        $approver_details = User::where('id', $removal_approver->users_id)->first();
                        $approver_details_email = $approver_details->email;
                        $approver_name = $approver_details->name;
                    }

                    $maildata = array(
                        'name' => $approver_name,
                        'email_content' => $emails->email_content,
                        'item' => $emails->item,
                        'id' => $checklistid,
                        'checklistname' => $request->checklist_name,
                        'reason' => $request->other_reason,
                        'checklistid' => $checklistid,
                        'app_url' => $url,
                    );

                    if ($request->department != null) {
                        $residedata = array('belongheading' => 'Department', 'residename' => $request->department);
                    } else if ($request->division != null) {
                        $residedata = array('belongheading' => 'Division', 'residename' => $request->division);
                    } else if ($request->area != null) {
                        $residedata = array('belongheading' => 'Subdivision', 'residename' => $request->area);
                    }

                    $emaildata = array_merge($maildata, $residedata);
                    Mail::to($approver_details_email)->send(new AuditChecklistRemovelRequest($emaildata));
                }

                $userdatas = EmailNotificationsUserDetails::where('notification_id', $emails->id)->get();
                if (count($userdatas) > 0) {
                    $adminaddedusers = array();
                    foreach ($userdatas as $userdata) {
                        array_push($adminaddedusers, $userdata->user_id);
                    }
                    foreach ($adminaddedusers as $adminaddeduser) {
                        $name = User::where('id', $adminaddeduser)->value('name');
                        $email = User::where('id', $adminaddeduser)->value('email');

                        $maildata = array(
                            'name' => $approver_name,
                            'email_content' => $emails->email_content,
                            'item' => $emails->item,
                            'id' => $checklistid,
                            'checklistname' => $request->checklist_name,
                            'reason' => $request->other_reason,
                            'checklistid' => $checklistid,
                            'app_url' => $url,
                        );

                        if ($request->department != null) {
                            $residedata = array('belongheading' => 'Department', 'residename' => $request->department);
                        } else if ($request->division != null) {
                            $residedata = array('belongheading' => 'Division', 'residename' => $request->division);
                        } else if ($request->area != null) {
                            $residedata = array('belongheading' => 'Subdivision', 'residename' => $request->area);
                        }

                        $emaildata = array_merge($maildata, $residedata);
                        Mail::to($email)->send(new AuditChecklistRemovelRequest($emaildata));
                    }
                }

                $models = DB::table('modenadisable')->First();
                return redirect('user/internalaudit/checklistdetails/' . $checklistid)
                    ->with('msg', 'Removal Request send successfully')
                    ->with('model', $models);
            } else {
                Session::flash('msg', 'Mail Sending Blocked for Checklist Removal Request');
                return redirect()->back();
            }
        } else {
            AuditChecklist::where('id', $checklistid)->delete();
            AuditChecklistTitles::where('checklist_id', $checklistid)->delete();
            AuditChecklistPoints::where('checklist_id', $checklistid)->delete();
            AuditChecklistNewRecord::where('checklist_id', $checklistid)->delete();
            DB::table('audit_checklist_new_record_details')->where('checklist_id', $checklistid)->delete();
            $models = DB::table('modenadisable')->First();
            return redirect('user/checklistlibrary')
                ->with('msg', 'Checklist deleted successfully')
                ->with('model', $models);
        }
    }

    public function approvechecklistremoval($id)
    {
        $admins = DB::table('admins')->where('email', Auth::user()->email)->first();
        if ($admins) {
            $deletedby_id = DB::table('users')->where('email', Auth::user()->email)->value('id');
        } else {
            $deletedby_id = Auth::user()->id;
        }
        AuditChecklist::where('id', $id)->update([
            'is_deleted' => 1,
            'deleted_by_user_id' => $deletedby_id,
        ]);

        $models = DB::table('modenadisable')->First();
        return redirect('user/checklistlibrary')
            ->with('msg', 'Checklist deleted successfully')
            ->with('model', $models);
    }

    public function cancelchecklistremoval($id)
    {
        AuditChecklist::where('id', $id)->update([
            'delete_status' => 'no',
        ]);

        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistdetails/' . $id)
            ->with('msg', 'Checklist deletion cancelled successfully')
            ->with('model', $models);
    }

    public function addchecklistsuggestions($id)
    {
        $checklist = AuditChecklist::where('id', $id)->first();
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.addchecklistsuggestions')
            ->with('model', $models)
            ->with('checklist', $checklist);
    }

    public function storechecklistimprovements(Request $request)
    {
        $checklistid = $request->checklistid;
        $admins = DB::table('admins')->where('email', Auth::user()->email)->first();
        if ($admins) {
            $improvementsby_id = DB::table('users')->where('email', Auth::user()->email)->value('id');
        } else {
            $improvementsby_id = Auth::user()->id;
        }
        AuditChecklist::where('id', $checklistid)->update([
            'status' => 'Need Improvement',
            'suggest_improvements' => $request->suggest_improvements,
            'suggest_improvements_by_user_id' => $improvementsby_id,
        ]);

        //sending mail to all auditors
        $emails = EmailNotification::where('module', 'auditmanagement')
            ->where('item', 'Checklist Suggest Improvements')
            ->where('is_blocked', false)
            ->first();

        if ($emails) {
            $auditors = DB::table('auditors')->get();
            foreach ($auditors as $auditor) {
                $emaildata = array(
                    'name' => $auditor->auditorsname,
                    'email_content' => $emails->email_content,
                    'item' => $emails->item,
                    'checklistname' => $request->checklist_name,
                    'departmentname' => $request->department,
                    'suggestions' => $request->suggest_improvements,
                );
                Mail::to($auditor->email)->send(new AuditChecklistSuggestImprovements($emaildata));
            }

            $userdatas = EmailNotificationsUserDetails::where('notification_id', $emails->id)->get();
            if (count($userdatas) > 0) {
                $adminaddedusers = array();
                foreach ($userdatas as $userdata) {
                    array_push($adminaddedusers, $userdata->user_id);
                }
                foreach ($adminaddedusers as $adminaddeduser) {
                    $name = User::where('id', $adminaddeduser)->value('name');
                    $email = User::where('id', $adminaddeduser)->value('email');
                    $emaildata = array(
                        'name' => $name,
                        'email_content' => $emails->email_content,
                        'item' => $emails->item,
                        'checklistname' => $request->checklist_name,
                        'departmentname' => $request->department,
                        'suggestions' => $request->suggest_improvements,
                    );
                    Mail::to($email)->send(new AuditChecklistSuggestImprovements($emaildata));
                }
            }

            $models = DB::table('modenadisable')->First();
            return redirect('user/internalaudit/checklistdetails/' . $checklistid)
                ->with('msg', 'Suggestions added successfully')
                ->with('model', $models);
        } else {
            Session::flash('msg', 'Mail Sending Blocked for Checklist Suggest Improvements');
            return redirect()->back();
        }

    }

    public function checklistdetails(Request $request, $id)
    {
        $models = DB::table('modenadisable')->First();
        $checklist = AuditChecklist::where('id', $id)->first();
        if ($checklist->department_id != null) {
            $departmentname = DB::table('departments')->where('id', $checklist->department_id)->value('departmentname');
            $main_heading = 'Departments';
            $heading_font = 'text-primary';
            $heading_name = $departmentname;
        } else {
            $departmentname = null;
        }

        if ($checklist->division_id != null) {
            $divisionname = Division::where('id', $checklist->division_id)->value('name');
            $main_heading = 'Division';
            $heading_font = 'text-secondary';
            $heading_name = $divisionname;
        } else {
            $divisionname = null;
        }

        if ($checklist->area_id != null) {
            $areaname = Area::where('id', $checklist->area_id)->value('name');
            $main_heading = 'Subdivision';
            $heading_font = 'text-danger';
            $heading_name = $areaname;
        } else {
            $areaname = null;
        }

        $totalpoints = AuditChecklistPoints::where('checklist_id', $id)->count();
        $models = DB::table('modenadisable')->First();
        $auditors = Auditor::pluck('email')->toArray();

        $departments = Departments::get();
        $divisions = Division::all();
        $sub_divisions = Area::all();
        $isoclauses = ['ISO 9001', 'ISO 14001', 'ISO 45001', 'ISO 50001', 'ISO 22000'];
        $isoclauses_icon = 'fa fa-database';

        if ($request->search == null) {

            $titles = AuditChecklistTitles::where('checklist_id', $id)->get();
            $points = AuditChecklistPoints::where('checklist_id', $id)->get();
            $srch_val = null;
            $pointsearch = 0;
        } else {
            $srch_val = $request->input('search');
            $checktitles = AuditChecklistTitles::where('title', 'like', '%' . $srch_val . '%')
                ->where('checklist_id', $id)
                ->get();

            $checkpoints = DB::table('audit_checklist_points')
                ->where('checklist_id', $id)
                ->where(function ($query) use ($srch_val) {
                    $query->where('points', 'like', '%' . $srch_val . '%')
                        ->orWhere('purpose', 'like', '%' . $srch_val . '%')
                        ->orWhere('items_verified', 'like', '%' . $srch_val . '%');

                })
                ->get();

            if ($request->input('verify') == 1) {
                if ((count($checktitles) == 0) && (count($checkpoints) == 0)) {

                    $titles = AuditChecklistTitles::where('checklist_id', $id)->get();
                    $points = AuditChecklistPoints::where('checklist_id', $id)->get();
                    $pointsearch = null;
                    return view('user.internalaudit.checklisterror')
                        ->with('model', $models)
                        ->with('departmentname', $departmentname)
                        ->with('departments', $departments)
                        ->with('isoclauses', $isoclauses)
                        ->with('isoclauses_icon', $isoclauses_icon)
                        ->with('auditors', $auditors)
                        ->with('checklist', $checklist)
                        ->with('serchval', $srch_val);
                } elseif (count($checktitles) > 0) {

                    $titles = DB::table('audit_checklist_titles')
                        ->where('audit_checklist_titles.title', 'like', '%' . $srch_val . '%')
                        ->where('checklist_id', $id)
                    //->select('audit_checklist_titles.*')
                        ->get();
                    $points = AuditChecklistPoints::where('checklist_id', $id)->get();
                    $pointsearch = 0;
                }
                if (count($checkpoints) > 0) {
                    $points = DB::table('audit_checklist_points')
                        ->where('checklist_id', $id)
                        ->where(function ($query) use ($srch_val) {
                            $query->where('points', 'like', '%' . $srch_val . '%')
                                ->orWhere('purpose', 'like', '%' . $srch_val . '%')
                                ->orWhere('items_verified', 'like', '%' . $srch_val . '%');

                        })
                        ->get()->toArray();
                    $alltitlevalues = array();
                    foreach ($points as $point) {
                        $titles1 = DB::table('audit_checklist_titles')->where('id', $point->checklist_title_id)->first();
                        if (!in_array($titles1, $alltitlevalues, true)) {
                            array_push($alltitlevalues, $titles1);
                        }
                    }
                    $titles = array_unique($alltitlevalues, SORT_REGULAR);
                    $pointsearch = 1;
                }

            }
        }

        $departmentActives = Departments::active()->get();

        return view('user.internalaudit.checklistdetails')
            ->with('model', $models)
            ->with('auditors', $auditors)
            ->with('departmentname', $departmentname)
            ->with('departmentActives', $departmentActives)
            ->with('alldivisions', $divisions)
            ->with('allsubdivisions', $sub_divisions)
            ->with('titles', $titles)
            ->with('points', $points)
            ->with('checklist', $checklist)
            ->with('totalpoints', $totalpoints)
            ->with('pointsearch', $pointsearch)
            ->with('serchval', $srch_val)
            ->with('isoclauses', $isoclauses)
            ->with('isoclauses_icon', $isoclauses_icon)
            ->with('divisionname', $divisionname)
            ->with('areaname', $areaname)
            ->with('main_heading', $main_heading)
            ->with('heading_font', $heading_font)
            ->with('heading_name', $heading_name)
            ->withDepartments($departments);

    }

    public function addchecklisttitle(Request $request)
    {
        $checkid = $request->input('checkid');
        $title = $request->input('title_value');
        $checklistdata = AuditChecklist::where('id', $checkid)->first();
        if ($checklistdata->department_id != null) {
            $departmentid = AuditChecklist::where('id', $checkid)->value('department_id');
        } else {
            $departmentid = null;
        }

        if ($checklistdata->division_id != null) {
            $division_id = AuditChecklist::where('id', $checkid)->value('division_id');
        } else {
            $division_id = null;
        }
        if ($checklistdata->area_id != null) {
            $area_id = AuditChecklist::where('id', $checkid)->value('area_id');
        } else {
            $area_id = null;
        }

        $data = array(
            'title' => $title,
            'checklist_id' => $checkid,
            'department_id' => $departmentid,
            'division_id' => $division_id,
            'area_id' => $area_id,
        );
        AuditChecklistTitles::create($data);
        AuditChecklist::where('id', $checkid)->update(['status' => 'Awaiting Approval']);
        return response()->json([
            'status' => 200,
        ]);
    }

    public function editchecklisttitle($id)
    {
        $title = AuditChecklistTitles::where('id', $id)->first();
        $checklistid = AuditChecklistTitles::where('id', $id)->value('checklist_id');
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.editchecklisttitle')
            ->with('model', $models)
            ->with('checklistid', $checklistid)
            ->with('title', $title);
    }

    public function updatechecklisttitle(Request $request)
    {
        $checklistid = $request->input('checklistid');
        $id = $request->input('titleid');
        $title = $request->input('title');
        $data = array(
            'title' => $title,
        );
        AuditChecklistTitles::where('id', $id)->update($data);
        //AuditChecklist::where('id', $checklistid)->update(['status'=> 'Awaiting Approval']);
        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistdetails/' . $checklistid)
            ->with('msg', 'Title updated successfully')
            ->with('model', $models);
    }

    public function deletechecklisttitle($id)
    {
        $checklistid = AuditChecklistTitles::where('id', $id)->value('checklist_id');
        AuditChecklistTitles::where('id', $id)->delete();
        AuditChecklistPoints::where('checklist_title_id', $id)->delete();
        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistdetails/' . $checklistid)
            ->with('msg', 'Title deleted successfully')
            ->with('model', $models);
    }

    public function addchecklistpoint($id)
    {
        $checklist = AuditChecklist::where('id', $id)->first();
        $titles = AuditChecklistTitles::where('checklist_id', $id)->get();
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.addchecklistpoints')
            ->with('model', $models)
            ->with('titles', $titles)
            ->with('checklist', $checklist);
    }

    public function insertchecklistpoint(Request $request)
    {

        if (count($request->doc) > 0) {
            $title_id = $request->input('title');
            // Add validation for title_id
            $validation = Validator::make($request->all(), [
                'title' => 'required|exists:audit_checklist_titles,id',
            ], [
                'title.required' => 'Please select a title, if it is not available, please add a new sub title on previous page',
                'title.exists' => 'The selected title is invalid',
            ]);
            if ($validation->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validation)
                    ->withInput($request->all());
            }
            for ($i = 0; $i < count($request->doc); $i++) {
                $checklist_id = AuditChecklistTitles::where('id', $title_id)->value('checklist_id');
                if ($request->department != null) {
                    $department_id = AuditChecklistTitles::where('id', $title_id)->value('department_id');
                } else {
                    $department_id = null;
                }
                if ($request->division != null) {
                    $division_id = AuditChecklistTitles::where('id', $title_id)->value('division_id');
                } else {
                    $division_id = null;
                }
                if ($request->area != null) {
                    $area_id = AuditChecklistTitles::where('id', $title_id)->value('area_id');
                } else {
                    $area_id = null;
                }

                if (isset($request->purpose) == null) {
                    $purpose = null;
                } else {
                    $purpose = $request->purpose;
                }

                if (isset($request->items_verified) == null) {
                    $items_verified = null;
                } else {
                    $items_verified = $request->items_verified;
                }

                DB::table('audit_checklist_points')->insert([
                    'points' => $request->doc[$i]['point'],
                    'purpose' => $purpose,
                    'items_verified' => $items_verified,
                    'checklist_title_id' => $title_id,
                    'checklist_id' => $checklist_id,
                    'department_id' => $department_id,
                    'division_id' => $division_id,
                    'area_id' => $area_id,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }

        }
        AuditChecklist::where('id', $checklist_id)->update(['status' => 'Awaiting Approval']);
        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistdetails/' . $request->checklistid)
            ->with('msg', 'Points added successfully')
            ->with('model', $models);

    }

    public function checklistpointpurpose($id)
    {
        $points = AuditChecklistPoints::where('id', $id)->first();
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.addchecklistpointspurpose')
            ->with('model', $models)
            ->with('points', $points);
    }

    public function insertchecklistpointpurpose(Request $request)
    {
        $checklistid = $request->checklistid;
        $purpose = $request->purpose;
        AuditChecklistPoints::where('id', $request->pointid)->update(['purpose' => $purpose]);
        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistdetails/' . $checklistid)
            ->with('model', $models);
    }

    public function checklistitemsverified($id)
    {
        $points = AuditChecklistPoints::where('id', $id)->first();
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.addchecklistpointsitemsverified')
            ->with('model', $models)
            ->with('points', $points);
    }

    public function insertchecklistitemsverified(Request $request)
    {
        $checklistid = $request->checklistid;
        AuditChecklistPoints::where('id', $request->pointid)->update(['items_verified' => $request->items_verified]);
        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistdetails/' . $checklistid)
            ->with('model', $models);
    }

    public function checklistpointsedit($id)
    {
        $points = AuditChecklistPoints::where('id', $id)->first();
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.checklistpointsedit')
            ->with('model', $models)
            ->with('points', $points);
    }

    public function checklistpointsupdate(Request $request)
    {
        $checklistid = $request->checklistid;
        AuditChecklistPoints::where('id', $request->pointid)
            ->update([
                'points' => $request->point,
                'purpose' => $request->purpose,
                'items_verified' => $request->items_verified,
            ]);

        AuditChecklist::where('id', $checklistid)->update(['status' => 'Awaiting Approval']);
        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistdetails/' . $checklistid)
            ->with('msg', 'Points Updated successfully')
            ->with('model', $models);
    }

    public function checklistpointsdelete($id)
    {
        $points = AuditChecklistPoints::where('id', $id)->first();
        $checklistid = $points->checklistdetails ? $points->checklistdetails->id : '';
        AuditChecklistPoints::where('id', $id)->delete();
        AuditChecklist::where('id', $checklistid)->update(['status' => 'Awaiting Approval']);
        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistdetails/' . $checklistid)
            ->with('msg', 'Points deleted successfully')
            ->with('model', $models);
    }

    /**----checklist excel upload */
    public function checklistexcelupload($id)
    {
        $datas = AuditChecklist::where('id', $id)->first();
        $models = DB::table('modenadisable')->First();
        return view('user.internalaudit.checklistexcelupload')
            ->with('model', $models)
            ->with('data', $datas);
    }

    public function insertchecklistexcelupload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'checklist_data' => 'required|mimes:xls,xlsx',

        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()->withErrors($validator)->withInput()->with('model', $models);
        } else {
            $rows = Excel::toArray(new ChecklistImport, $request->file('checklist_data'))[0];
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    if (empty($row['title']) || empty($row['control_area'])) {
                        Session::flash('errmsg', 'Title or Control Area data is empty.Please refer Note for excel upload format');
                        return redirect()->back();
                    } else {
                        $checklistid = $request->checklistid;

                        if ($request->department != null) {
                            $department_id = AuditChecklist::where('id', $checklistid)->value('department_id');
                        } else {
                            $department_id = null;
                        }
                        if ($request->division != null) {
                            $division_id = AuditChecklist::where('id', $checklistid)->value('division_id');
                        } else {
                            $division_id = null;
                        }
                        if ($request->area != null) {
                            $area_id = AuditChecklist::where('id', $checklistid)->value('area_id');
                        } else {
                            $area_id = null;
                        }

                        $titledata = array(
                            'title' => $row['title'],
                            'checklist_id' => $checklistid,
                            'department_id' => $department_id,
                            'division_id' => $division_id,
                            'area_id' => $area_id,
                        );

                        //checking if a title for a particular checklist already exists in database
                        $titlecheck = AuditChecklistTitles::where('title', $row['title'])
                            ->where('checklist_id', $checklistid)
                            ->get();

                        if (count($titlecheck) > 0) {
                            $checklisttitleid = AuditChecklistTitles::where('title', $row['title'])
                                ->where('checklist_id', $checklistid)
                                ->value('id');
                        } else {
                            AuditChecklistTitles::create($titledata);

                            $latestchecklisttitle = AuditChecklistTitles::latest('id')->first();
                            $checklisttitleid = $latestchecklisttitle->id;
                        }

                        $pointsdata = array(
                            'points' => $row['control_area'],
                            'purpose' => $row['purpose'],
                            'items_verified' => $row['items_verified'],
                            'checklist_title_id' => $checklisttitleid,
                            'checklist_id' => $checklistid,
                            'department_id' => $department_id,
                            'division_id' => $division_id,
                            'area_id' => $area_id,
                        );

                        //checking if a point for a particular checklist and title already exists in database
                        $pointscheck = AuditChecklistPoints::where('points', $row['control_area'])
                            ->where('checklist_title_id', $checklisttitleid)
                            ->where('checklist_id', $checklistid)
                            ->get();

                        if (count($pointscheck) == 0) {
                            AuditChecklistPoints::create($pointsdata);
                        } else {
                            foreach ($pointscheck as $check) {
                                AuditChecklistPoints::where('id', $check->id)->update($pointsdata);
                            }
                        }

                    }
                }
            }

            if (!File::exists(public_path('/storage/Auditchecklist/'))) {
                File::makeDirectory(public_path() . '/storage/Auditchecklist/', 0755, true);
            }

            $name = 'checklist_data' . '.' . $request->file('checklist_data')->extension();
            $request->file('checklist_data')->move(public_path() . '/storage/Auditchecklist/', $name);

            AuditChecklist::where('id', $checklistid)->update(['status' => 'Awaiting Approval']);
            $models = DB::table('modenadisable')->First();
            return redirect('user/internalaudit/checklistdetails/' . $checklistid)
                ->with('msg', 'Checklist Datas Uploaded successfully')
                ->with('model', $models);

        }

    }

//checklist create new record
    // public function checklistnewrecordregister($id)
    // {
    //     $models = DB::table('modenadisable')->First();
    //     $checklist = AuditChecklist::where('id', $id)->first();
    //     if ($checklist->department_id != null) {
    //         $listing_name = DB::table('departments')->where('id', $checklist->department_id)->value('departmentname');
    //         $listing_font = 'text-primary';
    //         $audits = DB::table('audit')->where('department', $checklist->department_id)
    //             ->wherenull('division_id')
    //             ->wherenull('area_id')
    //             ->where('status', '!=', 'Completed')
    //             ->where('email', 'like', '%' . Auth::user()->email . '%')
    //             ->get();
    //     }
    //     if ($checklist->division_id != null) {
    //         $listing_name = Division::where('id', $checklist->division_id)->value('name');
    //         $listing_font = 'text-secondary';
    //         $audits = DB::table('audit')->where('division_id', $checklist->division_id)
    //             ->wherenull('area_id')
    //             ->where('status', '!=', 'Completed')
    //             ->where('email', 'like', '%' . Auth::user()->email . '%')
    //             ->get();
    //     }
    //     if ($checklist->area_id != null) {
    //         $listing_name = Area::where('id', $checklist->area_id)->value('name');
    //         $listing_font = 'text-danger';
    //         $audits = DB::table('audit')->where('area_id', $checklist->area_id)
    //             ->where('status', '!=', 'Completed')
    //             ->where('email', 'like', '%' . Auth::user()->email . '%')
    //             ->get();
    //     }
    //     $auditors = Auditor::pluck('email')->toArray();
    //     return view('user.internalaudit.checklistnewrecordregister')
    //         ->with('model', $models)
    //         ->with('audits', $audits)
    //         ->with('auditors', $auditors)
    //         ->with('listing_name', $listing_name)
    //         ->with('listing_font', $listing_font)
    //         ->with('checklist', $checklist);

    // }
    public function checklistnewrecordregister($id)
    {
        $models = DB::table('modenadisable')->First();
        $checklist = AuditChecklist::where('id', $id)->first();
        if ($checklist->department_id != null) {
            $listing_name = DB::table('departments')->where('id', $checklist->department_id)->value('departmentname');
            $listing_font = 'text-primary';
            $audits = DB::table('audit')->where('department', $checklist->department_id)
                        ->where(function ($query) {
                            $query->whereNull('division_id')
                                  ->orWhere('division_id', 0);
                        })
                        ->where(function ($query) {
                            $query->whereNull('area_id')
                                ->orWhere('area_id', 0);
                        })
                        ->where('status', '!=', 'Completed')
                        ->where('email', 'like', '%' . Auth::user()->email . '%')
                        ->get();
        }
        if ($checklist->division_id != null) {
            $listing_name = Division::where('id', $checklist->division_id)->value('name');
            $listing_font = 'text-secondary';
            $audit_division = AuditScheduleDivision::where('division_id',$checklist->division_id)->get();
            if(count($audit_division)>0){
                $audit_ids = AuditScheduleDivision::whereIn('division_id',[$checklist->division_id])->pluck('audit_id')->toArray();
                $audits_1= DB::table('audit')->whereIn('id', $audit_ids)
                                            ->where('status', '!=', 'Completed')
                                            ->where('email', 'like', '%' . Auth::user()->email . '%')
                                            ->get();

                $audits_2 = DB::table('audit')->where('division_id', $checklist->division_id)
                                ->wherenull('area_id')
                                ->where('status', '!=', 'Completed')
                                ->where('email', 'like', '%' . Auth::user()->email . '%')
                                ->get();
                $audits =  $audits_1->merge($audits_2);
            }else{
                $audits = DB::table('audit')->where('division_id', $checklist->division_id)
                ->wherenull('area_id')
                ->where('status', '!=', 'Completed')
                ->where('email', 'like', '%' . Auth::user()->email . '%')
                ->get();
            }
        }

        if ($checklist->area_id != null) {
            $listing_name = Area::where('id', $checklist->area_id)->value('name');
            $listing_font = 'text-danger';
            $audit_areas = AuditScheduleArea::where('area_id',$checklist->area_id)->get();
            if(count($audit_areas)>0){
                $audit_ids = AuditScheduleArea::whereIn('area_id',[$checklist->area_id])->pluck('audit_id')->toArray();
                $audits_3  = DB::table('audit')->whereIn('id', $audit_ids)
                                              ->where('status', '!=', 'Completed')
                                              ->where('email', 'like', '%' . Auth::user()->email . '%')
                                              ->get();
                $audits_4 = DB::table('audit')->where('area_id', $checklist->area_id)
                                              ->where('status', '!=', 'Completed')
                                              ->where('email', 'like', '%' . Auth::user()->email . '%')
                                              ->get();
                $audits = $audits_3->merge($audits_4);
            }else{
                $audits = DB::table('audit')->where('area_id', $checklist->area_id)
                ->where('status', '!=', 'Completed')
                ->where('email', 'like', '%' . Auth::user()->email . '%')
                ->get();
            }

        }
        $auditors = Auditor::pluck('email')->toArray();
        return view('user.internalaudit.checklistnewrecordregister')
            ->with('model', $models)
            ->with('audits', $audits)
            ->with('auditors', $auditors)
            ->with('listing_name', $listing_name)
            ->with('listing_font', $listing_font)
            ->with('checklist', $checklist);

    }

    public function createchecknewrecord($id, $auditid)
    {
        $models = DB::table('modenadisable')->First();
        $checklist = AuditChecklist::where('id', $id)->first();
        if ($checklist->department_id != null) {
            $listing_name = DB::table('departments')->where('id', $checklist->department_id)->value('departmentname');
            $category = 'Department';
        }
        if ($checklist->division_id != null) {
            $listing_name = Division::where('id', $checklist->division_id)->value('name');
            $category = 'Division';
        }
        if ($checklist->area_id != null) {
            $listing_name = Area::where('id', $checklist->area_id)->value('name');
            $category = 'Subdivision';
        }

        $totalpoints = AuditChecklistPoints::where('checklist_id', $id)->count();
        $titles = AuditChecklistTitles::where('checklist_id', $id)->get();
        $points = AuditChecklistPoints::where('checklist_id', $id)->get();
        $conformance = AuditChecklistConformance::all();
        $audits = DB::table('audit')->where('id', $auditid)->first();
        /*--setting auditchecklist id-- */
        //$depcode = strtoupper(substr($departmentname, 0, 3));
        $checklist_count = $checklist->count;
        $year_value = date('Y', strtotime($checklist->created_at));
        $year = substr($year_value, 2);
        $exists = DB::table('audit_checklist_new_records')->where('audit_schedule_id', $auditid)
            ->where('checklist_id', $checklist->id)
            ->get();

        $count = '';
        if (count($exists) > 0) {
            foreach ($exists as $exist) {
                $db_count = $exist->count;
                $count = $db_count + 1;
            }

        } else {
            $count = 1;
        }

        // $auditchecklistid = $audits->aud_id . "/" . $checklist_count . "-" . $count . '/' . $audits->year;
        $auditchecklistid = $audits->aud_id . "/" .$id.'/'. $checklist_count . "-" . $count . '/' . $audits->year;
        $auditchecklistidyear = $auditchecklistid . '/' . $auditid;
        return view('user.internalaudit.checklistcreatenewrecord')
            ->with('model', $models)
            ->with('audits', $audits)
            ->with('listing_name', $listing_name)
            ->with('category', $category)
            ->with('titles', $titles)
            ->with('points', $points)
            ->with('checklist', $checklist)
            ->with('conformance', $conformance)
            ->with('auditchecklistid', $auditchecklistid)
            ->with('auditchecklistidyear', $auditchecklistidyear)
            ->with('count', $count)
            ->with('totalpoints', $totalpoints);

    }

    public function insertchecklistnewrecord(Request $request)
    {
        $checklistmainid = $request->checklistmainid;
        $audit_id = $request->audit_id;
        if ($request->departmentid != null) {
            $departmentid = $request->departmentid;
        } else {
            $departmentid = null;
        }

        if ($request->division_id != null) {
            $division_id = $request->division_id;
        } else {
            $division_id = null;
        }

        if ($request->area_id != null) {
            $area_id = $request->area_id;
        } else {
            $area_id = null;
        }

        foreach ($request->points_id as $key => $value) {

            if (empty($request->items_verified_new[$key])) {
                $item = null;
            } else {
                $item = $request->items_verified_new[$key];
            }

            if (empty($request->conformance[$key])) {
                $conformance = null;
            } else {
                $conformance = $request->conformance[$key];
            }

            if (empty($request->remarks[$key])) {
                $remarks = null;
            } else {
                $remarks = $request->remarks[$key];
            }

            if (empty($request->items_verified_new[$key]) && empty($request->conformance[$key]) && empty($request->remarks[$key])) {
                $createddated = null;
                $addedby_userid = null;
            } else {
                $createddated = $request->createdate[$key];
                $addedby_userid = $request->addedby_userid[$key];
            }

            $pointsvaluedb = DB::table('audit_checklist_points')->where('id', $value)->get();

            foreach ($pointsvaluedb as $pointvaluedb) {
                $maintitlenames = DB::table('audit_checklist_titles')->where('id', $pointvaluedb->checklist_title_id)->value('title');

                $data = array(
                    'items_verified_records' => $item,
                    'conformance_id' => $conformance,
                    'remarks' => $remarks,
                    'record_created_date' => $createddated,
                    'audit_id' => $audit_id,
                    'pointsname' => $pointvaluedb->points,
                    'items_verified' => $pointvaluedb->items_verified,
                    'purpose' => $pointvaluedb->purpose,
                    'checklist_id' => $checklistmainid,
                    'department_id' => $departmentid,
                    'division_id' => $division_id,
                    'area_id' => $area_id,
                    'title_name' => $maintitlenames,
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                );

                $data1 = array(
                    'items_verified_records' => $item,
                    'conformance_id' => $conformance,
                    'remarks' => $remarks,
                    'record_created_date' => $createddated,
                    'audit_checklist_id' => $request->auditchecklistid[$key],
                    'audit_checklist_id_year' => $request->auditchecklistidyear[$key],
                    'audit_id' => $audit_id,
                    'points_id' => $value,
                    'checklist_id' => $checklistmainid,
                    'department_id' => $departmentid,
                    'division_id' => $division_id,
                    'area_id' => $area_id,
                    'pointsname' => $pointvaluedb->points,
                    'items_verified' => $pointvaluedb->items_verified,
                    'purpose' => $pointvaluedb->purpose,
                    'title_name' => $maintitlenames,
                    'added_by' => $addedby_userid,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),

                );

                $dataexists = DB::table('audit_checklist_new_record_details')
                    ->where('points_id', $value)
                    ->where('audit_checklist_id_year', $request->audit_checklist_id_year)
                    ->get();

                if (count($dataexists) > 0) {
                    DB::table('audit_checklist_new_record_details')
                        ->where('audit_checklist_id_year', $request->audit_checklist_id_year)
                        ->where('points_id', $request->points_id[$key])
                        ->update($data);

                    DB::table('audit_checklist_new_record_details')
                        ->where('audit_checklist_id_year', $request->audit_checklist_id_year)
                        ->where('points_id', $request->points_id[$key])
                        ->where('added_by', null)
                        ->update(['added_by' => $addedby_userid,
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);

                } else {
                    DB::table('audit_checklist_new_record_details')->insert($data1);
                }

            }
        }

        //entering data into new records table
        $pointscalculate = DB::table('audit_checklist_new_record_details')
            ->where('audit_checklist_id_year', $request->audit_checklist_id_year)
            ->where('conformance_id', '!=', null)
            ->get();

        $totalpoints = $request->totalpoints;
        $completedpoints = count($pointscalculate);
        $remainingqns = $totalpoints - $completedpoints;
        $completed_percentage = round(($completedpoints / $totalpoints) * 100);

        if ($totalpoints == $completedpoints) {
            $record_completion_status = 'Completed';
        } else {
            $record_completion_status = 'Pending';
        }

        $status = $completed_percentage . '%';

        $data2 = array(
            'audit_schedule_id' => $audit_id,
            'checklist_id' => $request->checklistmainid,
            'department_id' => $departmentid,
            'division_id' => $division_id,
            'area_id' => $area_id,
            'audit_checklist_id' => $request->audtchecklistid,
            'audit_checklist_id_year' => $request->audit_checklist_id_year,
            'total_checklist_qns' => $request->totalpoints,
            'completed_qns' => $completedpoints,
            'remaining_qns' => $remainingqns,
            'completed_percentage' => $completed_percentage,
            'completed_status' => $status,
            'audit_date' => date('Y-m-d'),
            'record_status' => $record_completion_status,
            'count' => $request->count,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        );

        //  echo "<pre>";
        // print_r($data2);
        // echo "</pre>";

        $newexists = DB::table('audit_checklist_new_records')
            ->where('audit_checklist_id_year', $request->audit_checklist_id_year)
            ->get();
        if (count($newexists) > 0) {
            DB::table('audit_checklist_new_records')->where('id', $request->mainrecordid)->update($data2);
        } else {
            DB::table('audit_checklist_new_records')->insert($data2);
        }

        $detailsid = AuditChecklistNewRecord::where('audit_checklist_id', $request->audtchecklistid)
            ->where('audit_checklist_id_year', $request->audit_checklist_id_year)
            ->value('id');

        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistnewrecordsdisplay/' . $detailsid)
            ->with('model', $models);

    }

    public function addchecklistnewrecordattachments(Request $request)
    {
        $record_details_id = $request->input('point_main_id');
        if ($request->TotalImages > 0) {
            for ($x = 0; $x < $request->TotalImages; $x++) {
                if ($request->hasFile('images' . $x)) {
                    $file = $request->file('images' . $x);
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    // $picture   = date('His').'-'.$filename;
                    $picture = date('His') . $filename;
                    $destination_path = public_path('storage/Auditchecklist');
                    $file->move($destination_path, $picture);
                    $names[] = $picture;
                }
            }
        }
        foreach ($names as $name) {
            $data1 = array(
                'file_name' => $name,
                'record_details_id' => $record_details_id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );
            DB::table('audit_checklist_new_record_file_details')->insert($data1);
        }

        $file_values = DB::table('audit_checklist_new_record_file_details')->where('record_details_id',$record_details_id)->get();

        return response()->json([
            'status' => 200,
            'file_values' => $file_values,
        ]);

    }

    public function deletechecklistnewrecordattachments($id)
    {
        $record_details_id = DB::table('audit_checklist_new_record_file_details')->where('id', $id)->value('record_details_id');
        $audit_checklist_id = DB::table('audit_checklist_new_record_details')->where('id', $record_details_id)->value('audit_checklist_id');
        $audit_checklist_id_year = DB::table('audit_checklist_new_record_details')->where('id', $record_details_id)->value('audit_checklist_id_year');

        $detailsid = AuditChecklistNewRecord::where('audit_checklist_id', $audit_checklist_id)
            ->where('audit_checklist_id_year', $audit_checklist_id_year)
            ->value('id');

        DB::table('audit_checklist_new_record_file_details')->where('id', $id)->delete();

        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistnewrecordsdisplay/' . $detailsid)
            ->with('msg', 'Attachments removed successfully')
            ->with('model', $models);
    }

    public function ajaxdeletechecklistnewrecordattachments($id)
    {
        $record_details_id = DB::table('audit_checklist_new_record_file_details')->where('id', $id)->value('record_details_id');
        $audit_checklist_id = DB::table('audit_checklist_new_record_details')->where('id', $record_details_id)->value('audit_checklist_id');
        $audit_checklist_id_year = DB::table('audit_checklist_new_record_details')->where('id', $record_details_id)->value('audit_checklist_id_year');

        $detailsid = AuditChecklistNewRecord::where('audit_checklist_id', $audit_checklist_id)
            ->where('audit_checklist_id_year', $audit_checklist_id_year)
            ->value('id');

        DB::table('audit_checklist_new_record_file_details')->where('id', $id)->delete();
        session()->flash('msg', 'Attachments deleted successfully.');
        return response()->json([
            'status' => 200,
            'data'=>$detailsid,
            'redirect' => url('user/internalaudit/checklistnewrecordsdisplay/' . $detailsid)
        ]);
    }

    public function checklistnewrecordsdisplay($id)
    {
        $data = AuditChecklistNewRecord::where('id', $id)->first();
        $audits = DB::table('audit')->where('id', $data->audit_schedule_id)->first();
        $auditorlists = explode(',', $audits->email);

        $pointstitle = DB::table('audit_checklist_new_record_details')
            ->select('title_name')
            ->where('audit_checklist_id', $data->audit_checklist_id)
            ->where('is_deleted', 0)
            ->distinct()
            ->get();

        $deletedpoints = DB::table('audit_checklist_new_record_details')
            ->where('audit_checklist_id', $data->audit_checklist_id)
            ->where('is_deleted', 1)
            ->get();

        $titles = AuditChecklistTitles::where('checklist_id', $data->checklist_id)->get();
        $checklist = AuditChecklist::where('id', $data->checklist_id)->first();
        if ($checklist->department_id != null) {
            $listing_name = DB::table('departments')->where('id', $checklist->department_id)->value('departmentname');
            $category = 'Department';
        }
        if ($checklist->division_id != null) {
            $listing_name = Division::where('id', $checklist->division_id)->value('name');
            $category = 'Division';
        }
        if ($checklist->area_id != null) {
            $listing_name = Area::where('id', $checklist->area_id)->value('name');
            $category = 'Subdivision';
        }
        $models = DB::table('modenadisable')->First();
        $conformance = AuditChecklistConformance::all();
        return view('user.internalaudit.checklistnewrecorddisplay')
            ->with('model', $models)
            ->with('audits', $audits)
            ->with('data', $data)
            ->with('listing_name', $listing_name)
            ->with('category', $category)
            ->with('auditorlists', $auditorlists)
            ->with('pointstitle', $pointstitle)
            ->with('titles', $titles)
            ->with('checklist', $checklist)
            ->with('conformance', $conformance)
            ->with('deletedpoints', $deletedpoints);
    }

//delete checklistnewrecord
    public function deletechecklistnewrecords()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $audit_checklist_id = DB::table('audit_checklist_new_record_details')->where('id', $id)->value('audit_checklist_id_year');
            $id_value = AuditChecklistNewRecord::where('audit_checklist_id_year', $audit_checklist_id)->value('id');

            $data = array(
                'is_deleted' => 1,
            );
            DB::table('audit_checklist_new_record_details')->where('id', $id)->update($data);

            //updating totalchecklist data in new records table
            $pointscalculate = DB::table('audit_checklist_new_record_details')
                ->where('audit_checklist_id_year', $audit_checklist_id)
                ->where('conformance_id', '!=', null)
                ->where('is_deleted', 0)
                ->get();

            $totalpoints = DB::table('audit_checklist_new_record_details')
                ->where('audit_checklist_id_year', $audit_checklist_id)
                ->where('is_deleted', 0)
                ->count();

            $completedpoints = count($pointscalculate);
            $remainingqns = $totalpoints - $completedpoints;
            $completed_percentage = round(($completedpoints / $totalpoints) * 100);
            $status = $completed_percentage . '%';

            if ($totalpoints == $completedpoints) {
                $record_completion_status = 'Completed';
            } else {
                $record_completion_status = 'Pending';
            }

            $data2 = array(
                'total_checklist_qns' => $totalpoints,
                'completed_qns' => $completedpoints,
                'remaining_qns' => $remainingqns,
                'completed_percentage' => $completed_percentage,
                'completed_status' => $status,
                'record_status' => $record_completion_status,
            );

            AuditChecklistNewRecord::where('audit_checklist_id_year', $audit_checklist_id)->update($data2);

            return response()->json([
                'status' => 200,
                'data' => $id_value,
            ]);
        }
    }

    public function viewdeletedchecklistrecords($id)
    {
        $data = AuditChecklistNewRecord::where('id', $id)->first();
        $audits = DB::table('audit')->where('id', $data->audit_schedule_id)->first();
        $auditorlists = explode(',', $audits->email);
        $pointstitle = DB::table('audit_checklist_new_record_details')
            ->select('title_name')
            ->where('audit_checklist_id_year', $data->audit_checklist_id_year)
            ->where('is_deleted', 1)
            ->distinct()
            ->get();

        $deletedpoints = DB::table('audit_checklist_new_record_details')
            ->where('audit_checklist_id_year', $data->audit_checklist_id_year)
            ->where('is_deleted', 1)
            ->get();

        $titles = AuditChecklistTitles::where('checklist_id', $data->checklist_id)->get();
        $checklist = AuditChecklist::where('id', $data->checklist_id)->first();
        if ($checklist->department_id != null) {
            $listing_name = DB::table('departments')->where('id', $checklist->department_id)->value('departmentname');
            $category = 'Department';
        }
        if ($checklist->division_id != null) {
            $listing_name = Division::where('id', $checklist->division_id)->value('name');
            $category = 'Division';
        }
        if ($checklist->area_id != null) {
            $listing_name = Area::where('id', $checklist->area_id)->value('name');
            $category = 'Subdivision';
        }
        $models = DB::table('modenadisable')->First();
        $conformance = AuditChecklistConformance::all();
        return view('user.internalaudit.viewdeletedchecklistrecords')
            ->with('model', $models)
            ->with('audits', $audits)
            ->with('data', $data)
            ->with('listing_name', $listing_name)
            ->with('category', $category)
            ->with('auditorlists', $auditorlists)
            ->with('pointstitle', $pointstitle)
            ->with('titles', $titles)
            ->with('checklist', $checklist)
            ->with('conformance', $conformance)
            ->with('deletedpoints', $deletedpoints);

    }

//move checklistnewrecord
    public function movechecklistnewrecords()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $audit_checklist_id = DB::table('audit_checklist_new_record_details')->where('id', $id)->value('audit_checklist_id_year');
            $id_value = AuditChecklistNewRecord::where('audit_checklist_id_year', $audit_checklist_id)->value('id');

            $data = array(
                'is_deleted' => 0,
            );
            DB::table('audit_checklist_new_record_details')->where('id', $id)->update($data);

            //updating totalchecklist data in new records table
            $pointscalculate = DB::table('audit_checklist_new_record_details')
                ->where('audit_checklist_id_year', $audit_checklist_id)
                ->where('conformance_id', '!=', null)
                ->where('is_deleted', 0)
                ->get();

            $totalpoints = DB::table('audit_checklist_new_record_details')
                ->where('audit_checklist_id_year', $audit_checklist_id)
                ->where('is_deleted', 0)
                ->count();

            $completedpoints = count($pointscalculate);
            $remainingqns = $totalpoints - $completedpoints;
            $completed_percentage = round(($completedpoints / $totalpoints) * 100);
            $status = $completed_percentage . '%';

            if ($totalpoints == $completedpoints) {
                $record_completion_status = 'Completed';
            } else {
                $record_completion_status = 'Pending';
            }

            $data2 = array(
                'total_checklist_qns' => $totalpoints,
                'completed_qns' => $completedpoints,
                'remaining_qns' => $remainingqns,
                'completed_percentage' => $completed_percentage,
                'completed_status' => $status,
                'record_status' => $record_completion_status,
            );

            AuditChecklistNewRecord::where('audit_checklist_id_year', $audit_checklist_id)->update($data2);

            return response()->json([
                'status' => 200,
                'data' => $id_value,
            ]);
        }
    }

    public function deletechecklistnewrecordsfr()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $audit_checklist_id = DB::table('audit_checklist_new_record_details')->where('id', $id)->value('audit_checklist_id_year');
            $id_value = DB::table('audit_checklist_new_record_details')->where('id', $id)->value('checklist_id');

            DB::table('audit_checklist_new_record_details')->where('id', $id)->delete();
            $checkexists = DB::table('audit_checklist_new_record_details')->where('audit_checklist_id_year', $audit_checklist_id)->get();
            if ($checkexists->isEmpty()) {
                AuditChecklistNewRecord::where('audit_checklist_id_year', $audit_checklist_id)->delete();
            }
            return response()->json([
                'status' => 200,
                'data' => $id_value,
            ]);
        }
    }

//save checklistreport to pdf
    public function savechecklistreportpdf()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = AuditChecklistNewRecord::where('id', $id)->first();
            $checkaudid = $data->audit_checklist_id;

            $audits = DB::table('audit')->where('id', $data->audit_schedule_id)->first();
            $newpoints = DB::table('audit_checklist_new_record_details')
                ->where('audit_checklist_id_year', $data->audit_checklist_id_year)
                ->where('is_deleted', 0)
                ->get();

            $pointstitle = DB::table('audit_checklist_new_record_details')
                ->select('title_name')
                ->where('audit_checklist_id_year', $data->audit_checklist_id_year)
                ->where('is_deleted', 0)
                ->distinct()
                ->get();

            $titles = AuditChecklistTitles::where('checklist_id', $data->checklist_id)->get();
            $checklist = AuditChecklist::where('id', $data->checklist_id)->first();
            if ($checklist->department_id != null) {
                $listing_name = DB::table('departments')->where('id', $checklist->department_id)->value('departmentname');
                $category = 'Department';
            }
            if ($checklist->division_id != null) {
                $listing_name = Division::where('id', $checklist->division_id)->value('name');
                $category = 'Division';
            }
            if ($checklist->area_id != null) {
                $listing_name = Area::where('id', $checklist->area_id)->value('name');
                $category = 'Subdivision';
            }

            $conformance = AuditChecklistConformance::all();
            //return view('user.internalaudit.checklistsavepdf',compact('audits','data','pointstitle','titles','checklist','conformance','listing_name','category'));
            $pdf = PDF::loadView('user/internalaudit/checklistsavepdf', compact('audits', 'data', 'pointstitle', 'titles', 'checklist', 'conformance', 'listing_name', 'category'));
            return $pdf->download($checkaudid . '_Checklist_save.pdf');
        }
    }

//edit checklist new records
    public function checklistnewrecordsedit($id)
    {
        $data = AuditChecklistNewRecord::where('id', $id)->first();
        $audits = DB::table('audit')->where('id', $data->audit_schedule_id)->first();
        $auditorlists = explode(',', $audits->email);
        $pointstitle = DB::table('audit_checklist_new_record_details')
            ->select('title_name')
            ->where('audit_checklist_id_year', $data->audit_checklist_id_year)
            ->where('is_deleted', 0)
            ->distinct()
            ->get();

        $titles = AuditChecklistTitles::where('checklist_id', $data->checklist_id)->get();
        $checklist = AuditChecklist::where('id', $data->checklist_id)->first();
        if ($checklist->department_id != null) {
            $listing_name = DB::table('departments')->where('id', $checklist->department_id)->value('departmentname');
            $category = 'Department';
        }
        if ($checklist->division_id != null) {
            $listing_name = Division::where('id', $checklist->division_id)->value('name');
            $category = 'Division';
        }
        if ($checklist->area_id != null) {
            $listing_name = Area::where('id', $checklist->area_id)->value('name');
            $category = 'Subdivision';
        }
        $models = DB::table('modenadisable')->First();
        $conformance = AuditChecklistConformance::all();
        return view('user.internalaudit.editchecklistnewrecords')
            ->with('model', $models)
            ->with('audits', $audits)
            ->with('data', $data)
            ->with('listing_name', $listing_name)
            ->with('category', $category)
            ->with('auditorlists', $auditorlists)
            ->with('pointstitle', $pointstitle)
            ->with('titles', $titles)
            ->with('checklist', $checklist)
            ->with('conformance', $conformance);
    }

    public function updatechecklistnewrecords(Request $request)
    {
        $checklistmainid = $request->checklistmainid;
        $audit_id = $request->audit_id;
        $mainrecordid = $request->mainrecordid;

        foreach ($request->points_id as $key => $value) {
            if (empty($request->pointsname[$key])) {
                $pointsname = null;
            } else {
                $pointsname = $request->pointsname[$key];
            }

            if (empty($request->purpose[$key])) {
                $purpose = null;
            } else {
                $purpose = $request->purpose[$key];
            }

            if (empty($request->items_verified[$key])) {
                $items_verified = null;
            } else {
                $items_verified = $request->items_verified[$key];
            }

            if (empty($request->items_verified_new[$key])) {
                $item = null;
            } else {
                $item = $request->items_verified_new[$key];
            }

            if (empty($request->conformance[$key])) {
                $conformance = null;
            } else {
                $conformance = $request->conformance[$key];
            }

            if (empty($request->remarks[$key])) {
                $remarks = null;
            } else {
                $remarks = $request->remarks[$key];
            }

            $data = array(
                'items_verified_records' => $item,
                'conformance_id' => $conformance,
                'remarks' => $remarks,
                'pointsname' => $pointsname,
                'items_verified' => $items_verified,
                'purpose' => $purpose,
                'updated_by' => $request->updatedby_userid,
                'record_updated_date' => Carbon::now()->format('Y-m-d'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );

            DB::table('audit_checklist_new_record_details')
                ->where('audit_checklist_id_year', $request->audtchecklistidyear)
                ->where('points_id', $request->points_id[$key])
                ->update($data);

        }

        //updating data in audit_checklist_new_records table
        $pointscalculate = DB::table('audit_checklist_new_record_details')
            ->where('audit_checklist_id_year', $request->audtchecklistidyear)
            ->where('conformance_id', '!=', null)
            ->get();

        $totalpoints = $request->totalpoints;
        $completedpoints = count($pointscalculate);
        $remainingqns = $totalpoints - $completedpoints;
        $completed_percentage = round(($completedpoints / $totalpoints) * 100);

        if ($totalpoints == $completedpoints) {
            $record_completion_status = 'Completed';
        } else {
            $record_completion_status = 'Pending';
        }

        $status = $completed_percentage . '%';

        $data2 = array(
            'total_checklist_qns' => $request->totalpoints,
            'completed_qns' => $completedpoints,
            'remaining_qns' => $remainingqns,
            'completed_percentage' => $completed_percentage,
            'completed_status' => $status,
            'record_updated_date' => date('Y-m-d'),
            'record_status' => $record_completion_status,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        );

        DB::table('audit_checklist_new_records')->where('id', $request->mainrecordid)->update($data2);

        $models = DB::table('modenadisable')->First();
        return redirect('user/internalaudit/checklistnewrecordsdisplay/' . $request->mainrecordid)
            ->with('msg', 'Record Updated successfully')
            ->with('model', $models);

    }

    public function reschedule($id)
    {
        $audit = Audit::with('departmentObj')->findOrFail($id);
        $models = DB::table('modenadisable')->First();
        $auditorList = $this->getAuditorList();

        if ($audit->status != 'Accepted' || !in_array(auth()->user()->email, $auditorList)) {
            return redirect()->route('user.internalaudit.auditschedule', ['year' => date("Y")])->with('model', $models);
        }
        return view('user.internalaudit.reschedule')
            ->with('audit', $audit)
            ->with('id', $id)
            ->with('model', $models);
    }

    public function rescheduleRecord($id, Request $req)
    {
        $validator = Validator::make($req->all(), [
            'date' =>'required|before:enddate',
            'enddate' => 'required',
            'time' => 'required'
        ], [
            'date.required' => 'The from date is not empty',
            'date.before' => 'The From date must be a date before To date.',
            'enddate.required' => 'The to date is not empty',
            'time.required' => 'The time is not empty',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $audit = Audit::findOrFail($id);
            $models = DB::table('modenadisable')->First();
            $auditorList = $this->getAuditorList();
            if ($audit->status != 'Accepted' || !in_array(auth()->user()->email, $auditorList)) {
                return redirect()->route('user.internalaudit.auditschedule', ['year' => date("Y")])->with('model', $models);
            }
            $startDate = $req->input('date');
            $endDate = $req->input('enddate');
            $check_end_date = strtotime($endDate);
            $get_month = date("M", $check_end_date);
            $q1 = ['Jan', 'Feb', 'Mar'];
            $q2 = ['Apr', 'May', 'Jun'];
            $q3 = ['Jul', 'Aug', 'Sep'];
            $q4 = ['Oct', 'Nov', 'Dec'];
            if (in_array($get_month, $q1)) {
                $quarter = 'Q1';
            } else if (in_array($get_month, $q2)) {
                $quarter = 'Q2';
            } else if (in_array($get_month, $q3)) {
                $quarter = 'Q3';
            } else if (in_array($get_month, $q4)) {
                $quarter = 'Q4';
            }
            $time = $req->input('time');
            $data = array(
                'status' => 'Planned',
                'date' => $startDate, 'enddate' => $endDate, 'time' => $time, 'quarter' => $quarter,
            );

            $audit->update($data);

            \Illuminate\Support\Facades\Session::flash('msg', "Reschedule successfully");
            return redirect()->route('user.internalaudit.auditschedule', ['year' => date("Y")])->with('model', $models);
        }
    }
}
