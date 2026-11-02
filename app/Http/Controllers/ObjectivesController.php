<?php

namespace App\Http\Controllers;

use App\Area;
use App\Departments;
use App\Division;
use App\Http\Controllers\Controller;
use App\Helpers\Objective\ObjectiveExportHelper;
// use Validator;
use App\Mail\ObjectiveApprove;
use App\Mail\ObjectiveReviewed;
use App\Mail\ObjectiveReviewRequest;
use App\Mail\ObjectiveAwaitingApproval;
use App\Mail\ObjectiveReviewerChanged;
use App\Mail\ObjectiveApproverChanged;
use App\ClientDetail;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Objective;
use App\ObjectiveType;
use App\ObjReport;
use App\ObjectiveCategoryDetails;
// use App\Notifications;
use Illuminate\Http\JsonResponse;
use App\Type;
use App\User;
use App\ViewActivity;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use DateTime;
use App\UserGate;
use Illuminate\Support\Facades\Log;

class ObjectivesController extends Controller
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

        $ldate = date('Y-m-d');
        $date1 = date('Y-03-01');
        $date2 = date('Y-06-01');
        $date3 = date('Y-09-01');
        $date4 = date('Y-12-01');
        $objectives = DB::table('objectives')->get();

        foreach ($objectives as $objective) {
            $Oid = $objective->Oid;
            $status = $objective->status;
            $frequency = $objective->frequency;
            $frstatus = $objective->frstatus;
            $department = $objective->department;
            $notification = DB::table('departments')->where('departmentname', $department)->pluck('hod');
            $data = array(
                'Oid' => $Oid,
                'status' => $status,
                'frequency' => $frequency,
                'frstatus' => $frstatus,
            );
            foreach ($data as $dat) {
                if ($frstatus == 'NULL' && $frequency == 'Yearly' && $ldate >= $date4) {
                    Mail::to($notification)->send(new Objectivenotification($data));

                    echo "<script>alert('Notification Sent')</script>";
                    $frstatus = 'sent';
                    DB::table('objectives')->where('Oid', $Oid)
                        ->update(array('frstatus' => $frstatus));
                }
                if ($frstatus == 'NULL' && $frequency == 'Half yearly' && $ldate >= $date2 && $ldate <= $date4) {
                    Mail::to($notification)->send(new Objectivenotification($data));

                    echo "<script>alert('Notification Sent')</script>";
                    $frstatus = 'sent';
                    DB::table('objectives')->where('Oid', $Oid)
                        ->update(array('frstatus' => $frstatus));
                }
                if ($frstatus == 'sent' && $frequency == 'Half yearly' && $ldate >= $date4) {
                    Mail::to($notification)->send(new Objectivenotification($data));

                    echo "<script>alert('Notification Sent')</script>";
                    $frstatus = 'sent1';
                    DB::table('objectives')->where('Oid', $Oid)
                        ->update(array('frstatus' => $frstatus));
                }
                if ($frstatus == 'NULL' && $frequency == 'Quarterly' && $ldate >= $date1 && $ldate <= $date2) {
                    Mail::to($notification)->send(new Objectivenotification($data));

                    echo "<script>alert('Notification Sent')</script>";
                    $frstatus = 'sent';
                    DB::table('objectives')->where('Oid', $Oid)
                        ->update(array('frstatus' => $frstatus));
                }
                if ($frstatus == 'sent' && $frequency == 'Quarterly' && $ldate >= $date2 && $ldate <= $date3) {
                    Mail::to($notification)->send(new Objectivenotification($data));

                    echo "<script>alert('Notification Sent')</script>";
                    $frstatus = 'sent1';
                    DB::table('objectives')->where('Oid', $Oid)
                        ->update(array('frstatus' => $frstatus));
                }
                if ($frstatus == 'sent1' && $frequency == 'Quarterly' && $ldate >= $date3 && $ldate <= $date4) {
                    Mail::to($notification)->send(new Objectivenotification($data));

                    echo "<script>alert('Notification Sent')</script>";
                    $frstatus = 'sent2';
                    DB::table('objectives')->where('Oid', $Oid)
                        ->update(array('frstatus' => $frstatus));
                }
                if ($frstatus == 'sent2' && $frequency == 'Quarterly' && $ldate >= $date4) {
                    Mail::to($notification)->send(new Objectivenotification($data));

                    echo "<script>alert('Notification Sent')</script>";
                    $frstatus = 'sent3';
                    DB::table('objectives')->where('Oid', $Oid)
                        ->update(array('frstatus' => $frstatus));
                }
            }
        }

        $models = DB::table('modenadisable')->First();
        return view('user.objectives.home')->with('model', $models);
    }

    public function addobjectives()
    {
        $departments = Departments::all();
        $users = User::where('is_email_verified', true)->where('approved',true)->orderBy('name')->get();
        $objective_reviewers = UserGate::where('key', 'objective-reviewer')->pluck('users_id')->unique()->toArray();
        $objective_approvers = UserGate::where('key', 'objective-approver')->pluck('users_id')->unique()->toArray();
        $objective_types = ObjectiveType::where('is_deleted', false)->get();
        $models = DB::table('modenadisable')->First();

        return view('user.objectives.addobjectives')->with([
            'departments' => $departments,
            'objective_types' => $objective_types,
            'users' => $users,
            'model' => $models,
            'objective_reviewers' => $objective_reviewers,
            'objective_approvers' => $objective_approvers
        ]);
    }

    //get departments to dispaly folders departmentwise
    public function departmentsfolder(string $type = null){
        $departments = DB::table('departments')->get();
        $objectiveCounts = Objective::select('department', DB::raw('COUNT(*) as total'))
            ->whereIn('status', ['Review Pending', 'Reviewed', 'Rejected', 'Approved', 'Resubmit'])
            ->groupBy('department')
            ->pluck('total', 'department');

        $latestReports = Objective::select('department', DB::raw('MAX(report_update_date) as latest'))
            ->groupBy('department')
            ->pluck('latest', 'department');

        $departments = $departments->map(function ($dep) use ($objectiveCounts, $latestReports) {

            $dep->numberOfObjectives = $objectiveCounts[$dep->id] ?? 0;

            $latest = $latestReports[$dep->id] ?? null;
            $dep->update_date = $latest ? date('M d, Y', strtotime($latest)) : 'no report';

            return $dep;
        });
        $models = DB::table('modenadisable')->first();

        return view('user.objectives.folder')->with([
            'model' => $models,
            'departments' => $departments,
            'type' => $type,
        ]);
    }

    public function departmentssubfolder($dept, string $type = null)
    {
        session(['objdept' => $dept]);
        $dep = Departments::where('departmentname', $dept)->first();
        $targetdate = DB::table('objectives')->where('department', $dep->id)
            ->pluck('tardate');

        $alltargetdates = collect($targetdate);
        $uniquetargetdates = $alltargetdates->unique()->values()->all();
        $years = [];
        for ($i = 0; $i < count($uniquetargetdates); $i++) {
            $years[] = substr($uniquetargetdates[$i], 0, 4);
        }
        $years = array_unique($years);
        //convert string array to integer array
        $integeryears = array_map(
            function ($value) {
                return (int) $value;
            },
            $years
        );

        sort($integeryears, SORT_NUMERIC);

        $models = DB::table('modenadisable')->First();

        return view('user.objectives.subfolder')->with('model', $models)->with('years', $integeryears)->with('type', $type);
    }

    public function departmentsDivsionSubfolder($dept, string $type = null)
    {
        session(['objdept' => $dept]);

        $department = Departments::where('departmentname', $dept)->firstOrFail();

        $divisions = Division::where('department_id', $department->id)->get();

        $objectiveCount = Objective::where('department', $department->id)
            ->whereIn('status', ['Review Pending', 'Reviewed', 'Rejected', 'Approved', 'Resubmit'])
            ->count();

        $latestDate = Objective::where('department', $department->id)
            ->max('report_update_date');

        $department->numberOfObjectives = $objectiveCount;
        $department->update_date = $latestDate
            ? date('M d, Y', strtotime($latestDate))
            : 'no report';
        $models = DB::table('modenadisable')->first();
        return view('user.objectives.getDivisionFolder', [
            'model'     => $models,
            'divisions' => $divisions,
            'type'      => $type,
            'dep'       => $department
        ]);
    }

    public function departmentsSubDivisionSubfolder(int $id, $dept, int $division, string $type = null)
    {
        session(['objdept' => $dept]);
        //$dep = Departments::where('departmentname', $dept)->first();
        $subdivisions = Area::where('division_id', $id)->get();
        $division_name = Division::where('id', $division)->value('name');
        $models = DB::table('modenadisable')->First();

        return view('user.objectives.getSubDivisionFolder')->with('model', $models)->with('subdivisions', $subdivisions)->with('type', $type)->with('division', $division)->with('division_name', $division_name);
    }

    //edit issues
    public function editobj(string $type = null)
    {
        $objective_reviewers = UserGate::where('key', 'objective-reviewer')->pluck('user_id')->unique()->toArray();
        $objective_approvers = UserGate::where('key', 'objective-approver')->pluck('user_id')->unique()->toArray();
        $department = DB::table('departments')->get();
        $objective_types = ObjectiveType::where('is_deleted', false)->get();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = Objective::where('Oid', $d)->first();
                }
                $n = $n + 1;
            }
        }
        $models = DB::table('modenadisable')->first();

        return view('user/objectives/editobj')->withData($data)
            ->with('id', $id)
            ->with('objective_reviewers', $objective_reviewers)
            ->with('objective_approvers', $objective_approvers)
            ->with('department', $department)
            ->with('objective_types', $objective_types)
            ->withType($type)
            ->with('model', $models);
    }
    public function editrequest_obj(Request $req, string $type = null)
    {
        $validator = Validator::make($req->all(), [
            'target' => 'required|array',
            'target.*' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();

            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $m = count($req->name);

            $sno = $req->input('sno');
            $m = count($sno);
            $n = 0;
            foreach ($sno as $d) {
                if ($n < $m) {
                    $data2[] = Objective::where('Oid', $d)->first();
                }
            }

            foreach ($data2 as $dat) {
                foreach ($sno as $s) {
                    if ($dat->Oid == $s && $n < $m) {
                        Objective::where('Oid', $s)
                            ->update(array(
                                'objectives' => $req->name[$n],
                                'department' => $req->department[$n],
                                'resources' => $req->resources[$n],
                                'unit' => $req->unit[$n],
                                'span' => $req->span[$n],
                                'methodology' => $req->methodology[$n],
                                'otarget' => $req->target[$n],
                                'frequency' => $req->frequency[$n],
                                'objective_type_id' => $req->type[$n],
                            ));
                    }
                }
                $n = $n + 1;
            }
            $departments = DB::table('departments')->pluck('departmentname');
            $models = DB::table('modenadisable')->first();

            return redirect('/user/objectives/folders/' . $type)->with('model', $models)->with('departments', $departments);
        }
    }

    public function Insertobjectives(Request $req)
    {
        $validator = Validator::make($req->all(), [
                'otarget' => 'required|numeric',
                'nature' => 'nullable|in:0,increase,decrease',
                'otherunit' => 'nullable|string|max:75',
                'baseline' => 'nullable|string|max:150',
                'reviewers' => 'required|array',
                'reviewers.*' => 'required|string',
                'approver' => 'required|string',
                'document' => 'nullable|array',
                'document.*' => 'nullable|file|mimes:jpeg,png,jpg,xlsx,xls,pdf,docx,doc',
            ],
            [
                'otherunit.max' => 'The unit may not be greater than 75 characters.',
                'document.*.mimes' => 'Only JPEG, PNG, JPG, XLSX, XLS, PDF, DOC and DOCX files are allowed.',
            ]
        );

        if ($validator->fails()) {

            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {

            $department = $req->input('department');
            $division_id = $req->input('division_select') ?? 0;
            $area_id = $req->input('area_select') ?? 0;
            $objectives = $req->input('objectives');
            $objective_type = $req->input('objective_type');
            $description = $req->input('description');
            $baseline = $req->input('baseline');
            $target = $req->input('otarget');
            $nature = $req->input('nature');
            if ($req->input('unit') == 3) {
                $unit = "No sign";
            } else if ($req->input('unit') == 2) {
                $unit = "%";
            } else {
                $unit = ($req->input('otherunit'));
            }

            $span = $req->input('span');
            $methodology = $req->input('methodology');
            $tardate = $req->input('tardate');
            $resources = $req->input('resources');
            $responsibility = implode(',', $req->input('responsibility'));
            $fromdate = $req->input('fromdate');
            $todate = $req->input('todate');
            $frequency = $req->input('frequency');
            $reviewers = implode(',',$req->input('reviewers'));
            $approver = $req->input('approver');
            //$createdat = DB::table('objectives')->get();
            //$created_date = date('d-m-Y h:i:s A', time());
            $created_date = date('Y-m-d H:i:s', time());

            $ldate = date('Y-m-d');
            $date1 = date('Y-03-31');
            $date2 = date('Y-06-30');
            $date3 = date('Y-09-30');

            $reviewed_at = date('d-m-Y h:i:s A', time());
            $exist = DB::table('objectives')->where('department', $department)->first();
            if ($exist) {
                $objective = DB::table('objectives')->where('department', $department)->orderBy('Oid', 'desc')->first();
                $ynew = $objective->created_at;
                //$ystr = (substr($ynew, 8, 2));
                $ystr = Carbon::createFromFormat('Y-m-d H:i:s', $ynew)->format('y');
                $y = Date("Y");
                $year = substr($y, -2);
                if ($year == $ystr) {
                    $c = DB::table('objectives')->where('department', $department)->pluck('count');

                    foreach ($c as $count) {
                        $count = $count + 1;
                    }
                } else {
                    $count = "1";
                }
            } else {
                $count = "1";
            }
            $ID = User::where('email',Auth::user()->email)->value('id');
            $code = DB::table('departments')->where('id',$department)->value('code');
            $cat = strtoupper(substr($code, 0, 3));
            $y = Date("Y");
            $year = substr($y, -2);
            $objective_id = $cat.$count.'/'.$year;
            $files = null;
            if ($req->hasFile('document')) {
                $fileArray = [];
                foreach ($req->file('document') as $file) {
                    // $fileSize = $file->getClientOriginalName();
                    // $newFileName = $fileSize;
                    // $destinationPath = public_path('storage/objectives_files');
                    // $file->move($destinationPath, $newFileName);
                    // $fileArray[] = $newFileName;
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $originalName = trim($originalName);
                    $extension = $file->getClientOriginalExtension();

                    $newFileName = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

                    $destinationPath = public_path('storage/objectives_files');
                    $file->move($destinationPath, $newFileName);

                    $fileArray[] = $newFileName;
                }
                $files = implode(',',$fileArray);
            }
            $data = [
                'objective_type_id' => json_encode($objective_type ? (array) $objective_type : []),
                'count' => $count, 'department' => $department, 'objectives' => $objectives, 'description' => $description, 'frequency' => $frequency,
                'baseline' => $baseline, 'otarget' => $target, 'span' => $span, 'methodology' => $methodology, 'fromdate' => $fromdate, 'todate' => $todate,
                'unit' => $unit, 'tardate' => $tardate, 'resources' => $resources,
                'responsibility' => $responsibility, 'new_postedby' => $ID, 'created_at' => $created_date,
                'parents_id' => $req->input('id'), 'division_id' => $division_id, 'area_id' => $area_id, 'target_nature' => $nature ? $nature : null,
                'objective_id'=>$objective_id, 'reviewers' => $reviewers, 'approver' => $approver, 'files' => $files,
            ];
            DB::table('objectives')->insert($data);

            //inserting values to activity table superadmin codes
            $activity = "New Objective is inserted with name as" . $objectives . ", description as " . $description . " and with department as " . $department;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin codes
            $dept = DB::table('departments')->where('id',$department)->first();
            Session::flash('msg', "Objectives added successfully");
            $mails = EmailNotification::where('module','objectives')
                    ->where('item','Objective Review Request')
                    ->where('is_blocked',false)
                    ->first();
            if($mails){
                $emaildata = [
                    'department' => $dept->departmentname,
                    'targetdate' => date('d-m-Y',strtotime($tardate)),
                    'objectives' => $objectives,
                    'objective_id'=>$objective_id,
                    'description' => $description,
                    'item' => $mails->item,
                    'email_content' => $mails->email_content,
                ];
                //Sending Mail To Responsible Reviewers
                $reviewers = explode(',',$reviewers);
                $rev = User::whereIn('id',$reviewers)->pluck('email')->toArray();
                if(count($rev)>0){
                    Mail::to($rev)->send(new ObjectiveReviewRequest($emaildata));
                }
                $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
                if(count($userdatas1) > 0)
                {
                    $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if(count($userdatas)>0){
                        Mail::to($userdatas)->send(new ObjectiveReviewRequest($emaildata));
                    }
                }
            }else{
                Session::flash('errmsg', 'Mail Sending Blocked for Objective Review Request');
                return redirect()->route('user.objectives.folders');
            }
            $models = DB::table('modenadisable')->First();
            $departments = DB::table('departments')->pluck('departmentname');
            return redirect()->back()->with('model', $models)->with('departments', $departments);
        }
    }

    //repeat achieved objectives
    public function repeat(string $type = null)
    {
        if (isset($_GET['val'])) {
            $oid = $_GET['val'];
            $data = Objective::where('Oid', $oid)->get();
            $mn = DB::table('departments')->get();
            $users = User::where('is_email_verified',true)->where('approved',true)->get();
            $objective_reviewers = UserGate::where('key', 'objective-reviewer')->pluck('users_id')->unique()->toArray();
            $objective_approvers = UserGate::where('key', 'objective-approver')->pluck('users_id')->unique()->toArray();
            $objective_types = ObjectiveType::where('is_deleted', false)->get();
            $models = DB::table('modenadisable')->First();
            return view('user/objectives/repeatobjective',compact('objective_reviewers','objective_approvers'))->withData($data)->with('users', $users)->with('mn', $mn)->with('id', $oid)->with('model', $models)->with('type', $type)->with('objective_types', $objective_types);
        }
    }
    //repeat objective
    public function repeatobjectives(Request $req)
    {
        $validator = Validator::make($req->all(), [
                'otarget' => 'required|numeric',
                'nature' => 'nullable|in:0,increase,decrease',
                'otherunit' => 'nullable|string|max:75',
                'baseline' => 'nullable|string|max:150',
                'reviewers' => 'required|array',
                'reviewers.*' => 'required|string',
                'approver' => 'required|string',
                'document' => 'nullable|array',
                'document.*' => 'nullable|file|mimes:jpeg,png,jpg,xlsx,xls,pdf,docx,doc',
            ],
            [
                'otherunit.max' => 'The unit may not be greater than 75 characters.',
                'document.*.mimes' => 'Only JPEG, PNG, JPG, XLSX, XLS, PDF, DOC and DOCX files are allowed.',
            ]
        );

        if ($validator->fails()) {

            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {

            $department = $req->input('department');
            $division_id = $req->input('division_select') ?? 0;
            $area_id = $req->input('area_select') ?? 0;
            $objectives = $req->input('objectives');
            $objective_type = $req->input('objective_type');
            $description = $req->input('description');
            $baseline = $req->input('baseline');
            $target = $req->input('otarget');
            $nature = $req->input('nature');
            $objective_type = $req->input('objective_type');
            if ($req->input('unit') == 3) {
                $unit = "No sign";
            } else if ($req->input('unit') == 2) {
                $unit = "%";
            } else {
                $unit = ($req->input('otherunit'));
            }

            $span = $req->input('span');
            $methodology = $req->input('methodology');
            $tardate = $req->input('tardate');
            $resources = $req->input('resources');
            $responsibility = implode(',', $req->input('responsibility'));
            $fromdate = $req->input('fromdate');
            $todate = $req->input('todate');
            $frequency = $req->input('frequency');
            $reviewers = implode(',',$req->input('reviewers'));
            $approver = $req->input('approver');
            //$createdat = DB::table('objectives')->get();
            //$created_date = date('d-m-Y h:i:s A', time());
            $created_date = date('Y-m-d H:i:s', time());

            $ldate = date('Y-m-d');
            $date1 = date('Y-03-31');
            $date2 = date('Y-06-30');
            $date3 = date('Y-09-30');

            $reviewed_at = date('d-m-Y h:i:s A', time());
            $exist = DB::table('objectives')->where('department', $department)->first();
            if ($exist) {
                $objective = DB::table('objectives')->where('department', $department)->orderBy('Oid', 'desc')->first();
                $ynew = $objective->created_at;
                //$ystr = (substr($ynew, 8, 2));
                $ystr = Carbon::createFromFormat('Y-m-d H:i:s', $ynew)->format('y');
                $y = Date("Y");
                $year = substr($y, -2);
                if ($year == $ystr) {
                    $c = DB::table('objectives')->where('department', $department)->pluck('count');

                    foreach ($c as $count) {
                        $count = $count + 1;
                    }
                } else {
                    $count = "1";
                }
            } else {
                $count = "1";
            }
            $ID = User::where('email',Auth::user()->email)->value('id');
            $code = DB::table('departments')->where('id',$department)->value('code');
            $cat = strtoupper(substr($code, 0, 3));
            $y = Date("Y");
            $year = substr($y, -2);
            $objective_id = $cat.$count.'/'.$year;
            $existingObjective = DB::table('objectives')->where('Oid',$req->input('id'))->first();
            $removedFilenames = json_decode($req['removedFilenames']);
            $removedFilenames = is_array($removedFilenames) ? $removedFilenames : [];
            if ($req->hasFile('document')) {
                $fileArray = [];
                foreach ($req->file('document') as $file) {
                    // $newFileName = $file->getClientOriginalName();
                    // $file->move(public_path('storage/objectives_files'), $newFileName);
                    // $fileArray[] = $newFileName;

                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $originalName = trim($originalName);
                    $extension = $file->getClientOriginalExtension();

                    $newFileName = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

                    $destinationPath = public_path('storage/objectives_files');
                    $file->move($destinationPath, $newFileName);

                    $fileArray[] = $newFileName;
                }
                if($existingObjective->files!=''){
                    $existingFiles = explode(',', $existingObjective->files);
                    $existingFiles = array_diff($existingFiles, $removedFilenames);
                    $filesUpload = implode(',', array_merge($existingFiles, $fileArray));
                }else{
                    $filesUpload = implode(',',$fileArray);
                }
            } else {
                $filesUpload = implode(',', array_diff(explode(',', $existingObjective->files), $removedFilenames));
            }

            $data = [
                'objective_type_id' => json_encode($objective_type ? (array) $objective_type : []),
                'count' => $count, 'department' => $department, 'objectives' => $objectives, 'description' => $description, 'frequency' => $frequency,
                'baseline' => $baseline, 'otarget' => $target, 'span' => $span, 'methodology' => $methodology, 'fromdate' => $fromdate, 'todate' => $todate,
                'unit' => $unit, 'tardate' => $tardate, 'resources' => $resources,
                'responsibility' => $responsibility, 'new_postedby' => $ID, 'created_at' => $created_date,
                'parents_id' => $req->input('id'), 'division_id' => $division_id, 'area_id' => $area_id, 'target_nature' => $nature ? $nature : null,
                'objective_id'=>$objective_id, 'reviewers' => $reviewers, 'approver' => $approver, 'files' => $filesUpload,
            ];
            DB::table('objectives')->insert($data);

            //inserting values to activity table superadmin codes
            $activity = "New Objective is inserted with name as" . $objectives . ", description as " . $description . " and with department as " . $department;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin codes
            $dept = DB::table('departments')->where('id',$department)->first();
            Session::flash('msg', "Objectives added successfully");
            $mails = EmailNotification::where('module','objectives')
                    ->where('item','Objective Review Request')
                    ->where('is_blocked',false)
                    ->first();
            if($mails){
                $emaildata = [
                    'department' => $dept->departmentname,
                    'targetdate' => date('d-m-Y',strtotime($tardate)),
                    'objectives' => $objectives,
                    'objective_id'=>$objective_id,
                    'description' => $description,
                    'item' => $mails->item,
                    'email_content' => $mails->email_content,
                ];
                //Sending Mail To Responsible Reviewers
                $reviewers = explode(',',$reviewers);
                $rev = User::whereIn('id',$reviewers)->pluck('email')->toArray();
                if(count($rev)>0){
                    Mail::to($rev)->send(new ObjectiveReviewRequest($emaildata));
                }
                $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
                if(count($userdatas1) > 0)
                {
                    $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if(count($userdatas)>0){
                        Mail::to($userdatas)->send(new ObjectiveReviewRequest($emaildata));
                    }
                }
            }else{
                Session::flash('errmsg', 'Mail Sending Blocked for Objective Review Request');
                return redirect()->route('user.objectives.folders');
            }
            $models = DB::table('modenadisable')->First();
            $departments = DB::table('departments')->pluck('departmentname');
            return redirect()->back()->with('model', $models)->with('departments', $departments);
        }
    }
    //edit objectives by added user
    public function editobjective(string $type = null)
    {
        if (isset($_GET['val'])) {
            $oid = $_GET['val'];
            $data = Objective::where('Oid', $oid)->get();
            $mn = DB::table('departments')->get();
            $users = User::where('is_email_verified',true)->where('approved',true)->get();
            $objective_types = ObjectiveType::where('is_deleted', false)->get();
            $objective_reviewers = UserGate::where('key', 'objective-reviewer')->pluck('users_id')->unique()->toArray();
            $objective_approvers = UserGate::where('key', 'objective-approver')->pluck('users_id')->unique()->toArray();
            $models = DB::table('modenadisable')->first();
            return view('user/objectives/editobjective',compact('objective_reviewers','objective_approvers'))->withData($data)->with('users', $users)->with('mn', $mn)->with('id', $oid)->with('model', $models)->with('type', $type)->with('objective_types', $objective_types);
        }
    }
    //update objectives
    public function updateobjective(Request $req, string $type = null)
    {
        $validator = Validator::make($req->all(), [
            'otarget' => 'required|numeric',
            'baseline' => 'nullable|string|max:150',
            'reviewers' => 'required|array',
            'reviewers.*' => 'required|string',
            'approver' => 'required|string',
            'document' => 'nullable|array',
            'document.*' => 'nullable|file|mimes:jpeg,png,jpg,xlsx,xls,pdf,docx,doc',
        ],[
            'reviewers.required' => 'The reviewer filed is required',
            'reviewers.*.required' => 'The reviewer filed is required',
            'document.*.mimes' => 'Only JPEG, PNG, JPG, XLSX, XLS, PDF, DOC and DOCX files are allowed.',
        ]);
        if ($validator->fails()) {

            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $id = $req->input('id');
            $objective_type = $req->input('objective_type');
            $oid = DB::select('select * from objectives where Oid = :id', ['id' => $id]);
            $existingObjective = DB::table('objectives')->where('Oid',$id)->first();
            $objname = $req->input('objectives');
            $desc = $req->input('description');
            $fromdate = $req->input('fromdate');
            $todate = $req->input('todate');
            $baseline = $req->input('baseline');
            $target = $req->input('otarget');
            if ($req->input('unit') == 3) {
                $unit = "No sign";
            } else if ($req->input('unit') == 2) {
                $unit = "%";
            } else if ($req->input('unit') == 4) {
                $unit = ($req->input('otherunit'));
            } else {
                $unit = $req->input('unit');
            }

            $span = $req->input('span');
            $tardate = $req->input('tardate');
            $methodology = $req->input('methodology');
            $resources = $req->input('resources');
            $responsibility = implode(',', $req->input('responsibility'));
            $frequency = $req->input('frequency');
            $approver = $req->input('approver');
            $reviewers = implode(',',$req->input('reviewers'));
            $removedFilenames = json_decode($req['removedFilenames']);
            $removedFilenames = is_array($removedFilenames) ? $removedFilenames : [];
            if ($req->hasFile('document')) {
                $fileArray = [];
                foreach ($req->file('document') as $file) {
                    // $newFileName = $file->getClientOriginalName();
                    // $file->move(public_path('storage/objectives_files'), $newFileName);
                    // $fileArray[] = $newFileName;
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $originalName = trim($originalName);
                    $extension = $file->getClientOriginalExtension();

                    $newFileName = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

                    $destinationPath = public_path('storage/objectives_files');
                    $file->move($destinationPath, $newFileName);

                    $fileArray[] = $newFileName;
                }
                if($existingObjective->files!=''){
                    $existingFiles = explode(',', $existingObjective->files);
                    $existingFiles = array_diff($existingFiles, $removedFilenames);
                    $filesUpload = implode(',', array_merge($existingFiles, $fileArray));
                }else{
                    $filesUpload = implode(',',$fileArray);
                }
            } else {
                $filesUpload = implode(',', array_diff(explode(',', $existingObjective->files), $removedFilenames));
            }
            foreach ($oid as $row) {
                $o1id = (string) $row->Oid;
                if ($o1id == $id or $oid == null) {
                    DB::table('objectives')->where('Oid',$id)->update([
                        'objectives' => $objname, 'span' => $span, 'tardate' => $tardate, 'methodology' => $methodology,
                        'resources' => $resources, 'responsibility' => $responsibility, 'fromdate' => $fromdate,
                        'todate' => $todate, 'frequency' => $frequency, 'otarget' => $target, 'unit' => $unit,
                        'objective_type_id' => json_encode($objective_type ? (array) $objective_type : []),
                        'description' => $desc, 'oid' => $id,
                        'baseline' => $baseline,
                        'approver' => $approver,
                        'reviewers' => $reviewers,
                        'files' => $filesUpload,
                    ]);
                    //inserting values to activity table superadmin codes
                    $activity = " Objective is edited with name as" . $objname . ", description as " . $desc;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $now = Carbon::now()->format('Y-m-d H:i:s');
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
                    Viewactivity::insert($datas);
                    //end of superadmin codes
                    Session::flash('msg','The objective edited Successfully');
                    return redirect('/user/objectives/folders/' . $type);
                } else {
                    Session::flash('errmsg','The objective has not been updated Successfully');
                    return redirect('/user/objectives/folders/' . $type);
                }
            }
        }
    }

    public function regobjectives($dept, string $type = null, int $division = 0, int $subdivision = 0)
    {
        session(['objdept' => $dept]);
        $dep = Departments::where('departmentname', $dept)->first();
        //below is superadmin codes
        $objectives = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($objectives) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 1, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0]);
        } else {
            $objectives = DB::table('modpageload')->where('id', 1)->First();
            $objectives->objectives = $objectives->objectives + 1;
            DB::table('modpageload')->update(['objectives' => $objectives->objectives]);
        }
        //end of superadmin codes
        $cat = strtoupper(substr($dep->code, 0, 3));
        $data = Objective::where('department', $dep->id)
            ->with([
                'responsiblePersonName', 'responsiblePersonEmail','creator'
            ])->where(function ($query) use ($division) {
                if ($division) {
                    return $query->where('division_id', $division);
                }
            })->where(function ($query) use ($subdivision) {
                if ($subdivision) {
                    return $query->where('area_id', $subdivision);
                }
            })->whereIn('status', ['Review Pending', 'Reviewed', 'Rejected', 'Approved', 'Resubmit'])->orderBy('Oid', 'desc')->get();
        $objectiveTypeNames = $data->where('objective_type_id', '!=', 0)->pluck('objectiveType.name');
        $allTypeNames = implode(',', array_unique($objectiveTypeNames ? $objectiveTypeNames->toArray() : []));
        $years = Objective::where('department', $dep->id)
            ->selectRaw('YEAR(tardate) as year')
            ->pluck('year')
            ->unique()
            ->sort()
            ->implode(',');

        $obj_ids = $data->pluck('Oid')->toArray();
        $obj_ids = implode(',', $obj_ids);
        $division_name = Division::where('id', $division)->value('name');
        $subdivision_name = Area::where('id', $subdivision)->value('name');

        $model = DB::table('modenadisable')->first();
        $users = User::where('is_email_verified',true)->get();
        return view('user.objectives.listofobjectives', compact(
            'data', 'allTypeNames', 'model','users','years','cat','division_name','subdivision_name','type','obj_ids'
        ));
    }

    public function objectivesreport()
    {
        $ldate = date('Y-m-d');
        $date1 = date('Y-03-01');
        $date2 = date('Y-06-01');

        $date4 = date('Y-12-01');

        $data4 = array();

        $department_id = Departments::where('departmentname', Session::get('objdept'))->value('id');

        $data = DB::table('objectives')->where('department', $department_id)
            ->whereIn('status', ['Approved'])
            ->get();

        //get the data yearwise
        if (count($data) > 0) {
            foreach ($data as $d1) {
                if (substr($d1->tardate, 0, 4) === Session::get('year')) {
                    $data4[] = $d1;
                }
            }
        }
        $departments = DB::table('departments')->get();

        $models = DB::table('modenadisable')->First();

        return view('user/objectives/objreport')->with('data4', $data4)->withDepartments($departments)->with('model', $models);
    }

    public function add(string $type = null)
    {
        if (isset($_GET['val'])) {
            $Oid = $_GET['val'];

            $data1 = Objective::where('Oid', $Oid)->get();
            $data = Objective::join('objreport', 'objreport.Oid', '=', 'objectives.Oid')
                ->select(
                    'objectives.Oid',
                    'objectives.created_at',
                    'objectives.otarget',
                    'objectives.oachieved',
                    'objectives.objectives',
                    'objectives.responsibility',
                    'objectives.fromdate',
                    'objectives.todate',
                    'objectives.frequency',
                    'objectives.baseline',
                    'objreport.target',
                    'objreport.slno',
                    'objreport.achieved',
                    'objectives.CAR',
                    'objectives.trend_val1',
                    'objectives.trend_val2',
                    'objectives.trend_val3',
                    'objectives.trend_val4',
                    'objectives.trend_val5',
                    'objreport.state'
                )
                ->where('objectives.Oid', $Oid)
                ->get();

            $departments = Departments::all();
            $mn = DB::table('departments')->get();
            $models = DB::table('modenadisable')->First();
            return view('user/objectives/add')
                 ->with('model', $models)->withData($data)->withDepartments($departments)->withMn($mn)->with('Oid', $Oid)->withData1($data1)->withType($type);
        }
    }

    public function updatereport(Request $req, string $type = null)
    {
        $validator = Validator::make($req->all(), [
            'otarget' => 'nullable|numeric',
            'oachieved' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {

            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {

            $Oid = $req->input('posted');
            $now = $req->input('tardate');
            $state = $req->input('state');
            $target = $req->input('target');
            $achieved = $req->input('achieved');
            $frequency = $req->input('frequency');
            $otarget = $req->input('otarget');
            $oachieved = $req->input('oachieved');
            $baseline =  $req->input('baseline');

            if($req->input('db_year')!=null){
                $quarter = array_unique($req->input('db_year'));
                $db_main_year = $quarter;
            }
            else if($req->input('db_quarter')!=null){
                $quarter = array_unique($req->input('db_quarter'));
                $db_main_year = $req->input('quarter_year_db');
            }
            else if($req->input('db_half_year')!=null){
                $quarter = array_unique($req->input('db_half_year'));
                $db_main_year = $req->input('half_year_db');
            }
            else if($req->input('db_month')!=null){
                $quarter = array_unique($req->input('db_month'));
                $db_main_year = $req->input('month_year_db');
            }


            $val1 = DB::table('objectives')->where('Oid', $Oid)->value('trend_val1');
            $val2 = DB::table('objectives')->where('Oid', $Oid)->value('trend_val2');
            $val3 = DB::table('objectives')->where('Oid', $Oid)->value('trend_val3');
            $val4 = DB::table('objectives')->where('Oid', $Oid)->value('trend_val4');
            $val5 = DB::table('objectives')->where('Oid', $Oid)->value('trend_val5');
            $tb = DB::table('objectives')->where('Oid', $Oid)->value('trend_break');
            $year = DB::table('objectives')->where('Oid', $Oid)->value('todate');
            $year = substr($year, 0, 4);
            if ($val1 == null) {
                $val1 = $oachieved;
                $tb = 1;
                $tyear = $year;
                DB::table('objectives')->where('Oid', $Oid)
                    ->update([
                        'trend_year' => $tyear,
                        'report_update_date' => now(),
                    ]);
            } elseif ($val1 != null) {
                if ($tb == 1) {
                    $val2 = $oachieved;
                    $tb = 2;
                }
            } elseif ($val2 != null) {
                if ($tb == 2) {
                    $val3 = $oachieved;
                    $tb = 3;
                } else {
                    $val1 = null;
                    $val2 = null;
                    $val3 = null;
                    $val4 = null;
                    $val5 = null;
                    $tb = 0;
                }
            } elseif ($val3 != null) {
                if ($tb == 3) {
                    $val4 = $oachieved;
                    $tb = 4;
                } else {
                    $val1 = null;
                    $val2 = null;
                    $val3 = null;
                    $val4 = null;
                    $val5 = null;
                    $tb = 0;
                }
            } elseif ($val4 != null) {
                if ($tb == 4) {
                    $val5 = $oachieved;
                    $tb = 5;
                } else {
                    $val1 = null;
                    $val2 = null;
                    $val3 = null;
                    $val4 = null;
                    $val5 = null;
                    $tb = 0;
                }
            }

            $CAR = $req->input('CAR');

            $ldate = date('Y-m-d');
            $date1 = date('Y-04-01');
            $date2 = date('Y-06-31');
            $date3 = date('Y-09-30');
            $date4 = date('Y-12-01');

            $data = DB::table('objectives')
                ->join('objreport', 'objreport.Oid', '=', 'objectives.Oid')
                ->select(
                    'objectives.Oid',
                    'objectives.created_at',
                    'objreport.target',
                    'objreport.achieved',
                    'objectives.frequency',
                    'objectives.CAR',
                    'objectives.trend_val1',
                    'objectives.trend_val2',
                    'objectives.trend_val3',
                    'objectives.trend_val4',
                    'objectives.trend_val5'
                )
                ->paginate(100);

            if ($otarget != null && $oachieved != null) {
                DB::table('objectives')->where('Oid', $Oid)
                    ->update([
                        'otarget' => $otarget,
                        'oachieved' => $oachieved,
                        'report_update_date' => now(),
                    ]);
            }

            if ($CAR != null) {
                DB::table('objectives')->where('Oid', $Oid)
                    ->update([
                        'CAR' => $CAR,
                        'report_update_date' => now(),
                    ]);
            }

            DB::table('objectives')->where('Oid', $Oid)->update(array('trend_break' => $tb, 'trend_val1' => $val1, 'trend_val2' => $val2, 'trend_val3' => $val3, 'trend_val4' => $val4, 'trend_val5' => $val5, 'report_update_date' => now(),'baseline'=>$baseline));

            //inserting values to activity table superadmin code
            $activity = "Objective Report is updated where Objective Id is " . $Oid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now1 = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now1, 'updated_at' => $now1);
            Viewactivity::insert($datas);
            //end of superadmin code
            $data1 = DB::table('objreport')->where('Oid', $Oid)->get();

            for($i = 0; $i < count($target); $i++) {

                $total_quarter = isset($quarter[$i]) ? $quarter[$i] : $quarter;
                $total_db_main_year = isset($db_main_year[$i]) ? $db_main_year[$i] : $db_main_year;

                $exist_data = DB::table('objreport')
                                                 ->where('Oid', $Oid)
                                                 ->where('state',$state[$i])
                                                 ->where('frequency',$frequency)
                                                 ->where('quarter',$total_quarter)
                                                 ->get();

                    $insert_datas = array(
                                        'Oid' => $Oid,
                                        'target' => $target[$i],
                                        'achieved' => $req->achieved[$i],
                                        'state' => $state[$i],
                                        'frequency' => $frequency,
                                        'quarter' =>  $total_quarter,
                                        'year'=> $total_db_main_year,
                                        'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
                                        'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'),
                                    );

                    $update_datas = array(
                                      'target' => $target[$i],
                                      'achieved' => $req->achieved[$i],
                                      'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'),
                                     );

                if(count($exist_data) > 0){
                    DB::table('objreport')
                                        ->where('Oid', $Oid)
                                        ->where('state',$state[$i])
                                        ->where('frequency',$frequency)
                                        ->where('quarter',$total_quarter)
                                        ->update($update_datas);
                }
                else{
                    DB::table('objreport')->insert($insert_datas);
                }
            }

            $departments = DB::table('departments')->pluck('departmentname');
            $models = DB::table('modenadisable')->First();
            return redirect('/user/objectives/viewreport/'.$Oid);
        }
    }
    public function deletequarters($id)
    {
        $data_exists = Objreport::where('Oid',$id)->get();
        if(count($data_exists)>0){
            Objreport::where('Oid',$id)->delete();
            Session::flash('msg','Quarter Details Deleted Successfully');
            return redirect()->back();
        }
        else{
            Session::flash('errmsg','Quarter Details Deletion process Failed');
            return redirect()->back();
        }

    }
    public function changeresponsibleperson($id,string $type = null)
    {
        $datas = Objective::where('Oid', $id)->firstOrFail();
        $models = DB::table('modenadisable')->First();
        $users = User::where('is_email_verified', true)
                       ->where('approved',true)->get();
        return view('user.objectives.change_responsible_person')
                                         ->with('model',$models)
                                         ->with('type',$type)
                                         ->with('users',$users)
                                         ->with('datas',$datas);

    }
    public function updateresponsibleperson(Request $request,string $type = null)
    {
        $data = Objective::where('Oid',$request->objective_id)->first();
        if($data){
            $responsibility = implode(',', $request->input('responsibility'));
            Objective::where('Oid',$request->objective_id)->update(['responsibility'=>$responsibility]);
            $departments = DB::table('departments')->pluck('departmentname');
            Session::flash('msg','Responsible Person changed successfully');
            return redirect()->back();
        }
        else{
            Session::flash('errmsg','Change Responsible Person process Failed');
            return redirect()->back();
        }
    }
    public function reject(string $type = null)
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $md = DB::table('managements')->pluck('email');
            $mr = DB::table('representatives')->pluck('email');
            $models = DB::table('modenadisable')->First();
            return view('user.objectives.reject')->withId($id)->withmd($md)->withmr($mr)->with('model', $models)->with('type', $type);
        }
    }
    public function rejecting(Request $req, string $type = null)
    {
        $higherproposal = $req->input('higherproposal');
        $proposal = $req->input('proposal');
        $id = $req->input('id');
        $md = DB::table('managements')->pluck('email');
        $now = Carbon::now()->format('Y-m-d H:i:s');
        foreach ($md as $mds) {
            if (Auth::user()->email == $mds) {
                $data = array(
                    'status' => 'Resubmit',
                    'higherproposal' => $higherproposal,
                );
                DB::table('objectives')
                    ->where('Oid', $id)
                    ->update($data);
                //inserting values to activity table
                $activity = "Objective is Rejected and Updated status as Resubmit where Objectives id is " . $id;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
                ViewActivity::insert($datas);
                //end of superadmin code
            }
        }
        $mr = DB::table('representatives')->pluck('email');
        foreach ($mr as $mrs) {
            if (Auth::user()->email == $mrs) {
                $data1 = array(
                    'status' => 'Resubmit',
                    'proposal' => $proposal,
                );
                DB::table('objectives')
                    ->where('Oid', $id)
                    ->update($data1);

                //inserting values to activity table
                $activity = "Objective is Rejected and Updated status as Resubmit where Objectives id is " . $id;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
                ViewActivity::insert($datas);
                //end of superadmin
            }
        }
        $departments = DB::table('departments')->pluck('departmentname');
        $models = DB::table('modenadisable')->First();
        return redirect('/user/objectives/folders/' . $type)->with('model', $models)->with('departments', $departments);
    }

    public function resubmit(string $type = null)
    {
        if (isset($_GET['val'])) {
            $Oid = $_GET['val'];
            $departments = Departments::all();
            $data = Objective::where('Oid', $Oid)->first();
            $resname = DB::table('users')->where('email', $data->responsibility)->value('name');
            $users = User::where('is_email_verified',true)->where('approved',true)->get();
            $objective_reviewers = UserGate::where('key', 'objective-reviewer')->pluck('users_id')->unique()->toArray();
            $objective_approvers = UserGate::where('key', 'objective-approver')->pluck('users_id')->unique()->toArray();
            $objective_types = ObjectiveType::where('is_deleted', false)->get();
            $models = DB::table('modenadisable')->first();

            return view('user.objectives.resubmit')
                ->withUsers($users)
                ->withDepartments($departments)
                ->withResname($resname)
                ->withData($data)
                ->with('model', $models)
                ->with('objective_types', $objective_types)
                ->with('type', $type)
                ->with('Oid', $Oid)
                ->with([
                    'objective_reviewers' => $objective_reviewers,
                    'objective_approvers' => $objective_approvers
                ]);
        }
    }

    public function resubmitobjectives(Request $req, string $type = null)
    {
        $validator = Validator::make($req->all(), [
            'target' => 'required|numeric',
            'reviewers' => 'required|array',
            'reviewers.*' => 'required|string',
            'approver' => 'required|string',
            'document' => 'nullable|array',
            'document.*' => 'nullable|file|mimes:jpeg,png,jpg,xlsx,xls,pdf,docx,doc',
        ],[
            'reviewers.required' => 'The reviewers field is required',
            'reviewers.*.required' => 'The reviewers filed is required',
            'document.*.mimes' => 'Only JPEG, PNG, JPG, XLSX, XLS, PDF, DOC and DOCX files are allowed.',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();

            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $id = $req->input('Oid');
            $existingObjective = DB::table('objectives')->where('Oid',$id)->first();
            $objectives = $req->input('objname');
            $objective_type = $req->input('objective_type');
            $target = $req->input('target');
            if ($req->input('unit') == "No sign") {
                $unit = "No sign";
            } else if ($req->input('unit') == "%") {
                $unit = "%";
            } else {
                $unit = $req->input('otherunit');
            }

            $span = $req->input('span');
            $methodology = $req->input('methodology');
            $tardate = $req->input('tardate');
            $resources = $req->input('resources');
            $responsibility = implode(',', $req->input('responsibility'));
            $fromdate = $req->input('fromdate');
            $todate = $req->input('todate');
            $desc = $req->input('description');
            $ID = User::where('email',Auth::user()->email)->value('id');
            $reviewers = implode(',',$req->input('reviewers'));
            $approver = $req->input('approver');
            $removedFilenames = json_decode($req['removedFilenames']);
            $removedFilenames = is_array($removedFilenames) ? $removedFilenames : [];
            if ($req->hasFile('document')) {
                $fileArray = [];
                foreach ($req->file('document') as $file) {
                    // $newFileName = $file->getClientOriginalName();
                    // $file->move(public_path('storage/objectives_files'), $newFileName);
                    // $fileArray[] = $newFileName;

                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $originalName = trim($originalName);
                    $extension = $file->getClientOriginalExtension();

                    $newFileName = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

                    $destinationPath = public_path('storage/objectives_files');
                    $file->move($destinationPath, $newFileName);

                    $fileArray[] = $newFileName;
                }

                if($existingObjective->files!=''){
                    $existingFiles = explode(',', $existingObjective->files);
                    $existingFiles = array_diff($existingFiles, $removedFilenames);
                    $filesUpload = implode(',', array_merge($existingFiles, $fileArray));
                }else{
                    $filesUpload = implode(',',$fileArray);
                }
            } else {
                $filesUpload = implode(',', array_diff(explode(',', $existingObjective->files), $removedFilenames));
            }
            $data = [
                'objective_type_id' => json_encode($objective_type ? (array) $objective_type : []),
                'objectives' => $objectives, 'description' => $desc,
                'otarget' => $target, 'unit' => $unit, 'span' => $span, 'methodology' => $methodology, 'fromdate' => $fromdate, 'todate' => $todate,
                'tardate' => $tardate, 'resources' => $resources, 'responsibility' => $responsibility, 'new_postedby' => $ID, 'status' => 'Review Pending',
                'reviewers' => $reviewers, 'approver' => $approver, 'reviewed_by' => null, 'new_reviewedby' => null,'reviewed_at' => null, 'files' => $filesUpload,
            ];
            DB::table('objectives')->where('Oid', '=', $id)->update($data);

            //inserting values to activity table superadmin code
            $activity = "Objective is Resubmitted where Objectives id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code
            Session::flash('msg', "Objectives sent for approval successfully");
            $departments = DB::table('departments')->pluck('departmentname');
            $models = DB::table('modenadisable')->first();

            return redirect('user/objectives/folders/' . $type)->with('model', $models)->with('departments', $departments);
        }
    }

    public function adds(string $type = null)
    {
        if (isset($_GET['val'])) {
            $Oid = $_GET['val'];
            $departments = Departments::all();
            $dep = DB::table('departments')->pluck('hod');
            $data = Objective::where('Oid', $Oid)->get();
            foreach ($data as $d) {
                $res = $d->responsibility;
            }
            $resname = DB::table('users')->where('email', $res)->value('name');
            $user = User::where('is_email_verified',true)->get();
            $objective_types = ObjectiveType::where('is_deleted', false)->get();
            $models = DB::table('modenadisable')->First();
            return view('user/objectives/review', compact('data'))
                ->with('model', $models)
                ->withUser($user)
                ->withDepartments($departments)
                ->withResname($resname)
                ->with('objective_types', $objective_types)
                ->with('Oid', $Oid)
                ->with('type', $type);
        }
    }
    public function updateobjectives(Request $req, string $type = null)
    {
        $validator = Validator::make($req->all(), [
            'target' => 'required|numeric',
            'nature' => 'nullable|in:0,increase,decrease',
            'baseline' => 'nullable|string|max:150',
            'document' => 'nullable|array',
            'document.*' => 'nullable|file|mimes:jpeg,png,jpg,xlsx,xls,pdf,docx,doc',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();

            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $Oid = $req->input('posted');
            $objective_type = $req->input('objective_type');
            $nature = $req->input('nature');
            $now = $req->input('tardate');
            $now1 = Carbon::now()->format('Y-m-d H:i:s');
            $d = $req->input('department');
            $d = Departments::where('departmentname', $d)->first();
            $d = $d->id;
            $name = $req->input('objectives');
            $des = $req->input('description');
            $baseline = $req->input('baseline');
            $tar = $req->input('target');

            $files = '';
            if ($req->hasFile('document')) {
                $fileArray = [];
                foreach ($req->file('document') as $file) {
                    // $fileSize = $file->getClientOriginalName();
                    // $newFileName = $fileSize;
                    // $destinationPath = public_path('storage/objectives_files');
                    // $file->move($destinationPath, $newFileName);
                    // $fileArray[] = $newFileName;
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $originalName = trim($originalName);
                    $extension = $file->getClientOriginalExtension();

                    $newFileName = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

                    $destinationPath = public_path('storage/objectives_files');
                    $file->move($destinationPath, $newFileName);

                    $fileArray[] = $newFileName;
                }
                $files = implode(',',$fileArray);
            }

            $hod = DB::table('departments')->pluck('hod');
            $todate = DB::table('objectives')->where('Oid', '=', $Oid)->value('todate');
            $fromdate = DB::table('objectives')->where('Oid', '=', $Oid)->value('fromdate');
            $objdept = DB::table('objectives')->where('Oid', '=', $Oid)->value('department');
            $hodmail = DB::table('departments')->where('id', '=', $objdept)->value('hod');
            if ($req->input('unit') == "No sign") {
                $unit = "No sign";
            } else if ($req->input('unit') == "%") {
                $unit = "%";
            } else {
                $unit = $req->input('otherunit');
            }

            $span = $req->input('span');
            $meth = $req->input('methodology');
            $r = $req->input('resources');
            $res = implode(',', $req->input('responsibility'));
            $remarks = $req->input('remarks');
            $p = $req->input('proposal');
            $reviewed = $req->input('question');
            $reviewed_at = date('d-m-Y h:i:s A', time());
            $rev_id = User::where('email',Auth::user()->email)->value('id'); //reviewed persons id
            $frequency = $req->input('frequency');
            $ldate = date('Y-m-d');
            $reviewersArray = DB::table('objectives')->where('Oid', $Oid)->first();
            $reviewedAt = explode(',',$reviewersArray->reviewed_at);
            if($reviewedAt[0]==null){
                $reviewedAt[0] = $reviewed_at;
            }else{
                $reviewedAt[] = $reviewed_at;
            }
            // dd($reviewedAt);
            $reviewedAt = implode(',',$reviewedAt);

            // fetching current objective
            $objective = Objective::where('Oid', $Oid)->first();
            //fetching prevoiusly attached files as string
            $previousAttachedDocuments = $objective->files;
            //concatenating previously attached file and new file
            $files = $previousAttachedDocuments ? $previousAttachedDocuments.', '.$files : $files;

            if ($reviewed == 2) {
                $reviewersArray = explode(',',$reviewersArray->new_reviewedby);
                if ($reviewersArray[0] == null) {
                    $reviewersArray[0] = $rev_id;
                } else {
                    $reviewersArray[] = $rev_id;
                }
                $reviewers = DB::table('objectives')->where('Oid', $Oid)->first();

                $reviewers = explode(',',$reviewers->reviewers);
                $difference = array_diff($reviewers,$reviewersArray);
                if (empty($difference)) {
                    $status = 'Reviewed';
                } else {
                    $status = 'Review Pending';
                }
                $revId = implode(',',$reviewersArray);
                $data = [
                    'objective_type_id' => json_encode($objective_type ? (array) $objective_type : []),
                    'tardate' => $now,'department' => $d, 'objectives' => $name,
                    'description' => $des, 'otarget' => $tar, 'unit' => $unit, 'frequency' => $frequency,
                    'baseline' => $baseline,'span' => $span, 'methodology' => $meth,
                    'status' => $status, 'remarks' => $remarks, 'resources' => $r, 'responsibility' => $res,
                    'reviewed_at' => $reviewedAt,'new_reviewedby' => $revId,'target_nature' => $nature ? $nature : null,
                    'proposal'=>$p, 'files' => $files,
                ];
                DB::table('objectives')
                    ->where('Oid', $Oid)
                    ->update($data);
                //inserting values to activity table superadmin
                $activity = "Objective is Reviewed and Updated status as Reviewed where Objective Id" . $Oid;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now1, 'updated_at' => $now1);
                ViewActivity::insert($datas);
                if($status=="Reviewed"){
                    $mails = EmailNotification::where('module','objectives')->where('item','Objective Awaiting Approval')
                                ->where('is_blocked',false)->first();
                    if($mails){
                        $emaildata = array(
                            'department' => $req->input('department'),
                            'objectives' => $name,
                            'description' => $des,
                            'targetdate' => date('d-m-Y',strtotime($now)),
                            'objective_id' => $req->input('objid'),
                            'item' => $mails->item,
                            'email_content' => $mails->email_content,
                            'status' => $status,
                        );

                        //sending mail when Objective reviewed successfully

                        $approver =  User::where('id',$req->input('approver'))->value('email');
                        if($approver!=''){
                            Mail::to($approver)->send(new ObjectiveAwaitingApproval($emaildata));
                        }
                        $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
                        if(count($userdatas1) > 0)
                        {
                            $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if(count($userdatas)>0){
                                Mail::to($userdatas)->send(new ObjectiveAwaitingApproval($emaildata));
                            }
                        }
                    }else{
                        Session::flash('errmsg', 'Mail Sending Blocked for Objective Awaiting Approval');
                        return redirect()->route('user.objectives.folders');
                    }
                }
                // //end of superadmin
                $mails = EmailNotification::where('module','objectives')
                    ->where('item','Objective Reviewed')
                    ->where('is_blocked',false)
                    ->first();
                if($mails){
                    $emaildata = array(
                        'department' => $d,
                        'objectives' => $name,
                        'description' => $des,
                        'targetdate' => date('d-m-Y',strtotime($now)),
                        'objective_id' => $req->input('objid'),
                        'item' => $mails->item,
                        'email_content' => $mails->email_content,
                        'status' => $status,
                    );
                    //sending mail when Objective reviewed
                    if($hodmail!=''){
                        Mail::to($hodmail)->send(new ObjectiveReviewed($emaildata));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
                    if(count($userdatas1) > 0)
                    {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if(count($userdatas)>0){
                            Mail::to($userdatas)->send(new ObjectiveReviewed($emaildata));
                        }
                    }
                }else{
                    Session::flash('errmsg', 'Mail Sending Blocked for Objective Reviewed');
                    return redirect()->route('user.objectives.folders');
                }
                $departments = DB::table('departments')->pluck('departmentname');
                $models = DB::table('modenadisable')->First();
                Session::flash('msg', "Objective Reviewed Successfully");
                return redirect('/user/objectives/folders/' . $type)->with('model', $models)->with('departments', $departments);
            } else {
                $status = 'Rejected';
            }

            $data = [
                'objective_type_id' => json_encode($objective_type ? (array) $objective_type : []),
                'tardate' => $now,'department' => $d, 'objectives' => $name, 'description' => $des, 'otarget' => $tar, 'unit' => $unit, 'frequency' => $frequency,
                'span' => $span, 'methodology' => $meth, 'proposal' => $p, 'status' => $status, 'resources' => $r, 'responsibility' => $res, 'reviewed_at' => $reviewedAt,
                'target_nature' => $nature ? $nature : null, 'files' => $files
            ];
            DB::table('objectives')
                ->where('Oid', $Oid)
                ->update($data);
            //inserting values to activity table superadmin
            $activity = "Objective is Rejected and Updated status as Rejected where Objectives Id is " . $Oid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now1, 'updated_at' => $now1);
            Viewactivity::insert($datas);
            Session::flash('msg', "Notification send After Objective Review");
            //end of superadmin
            $mails = EmailNotification::where('module','objectives')
                    ->where('item','Objective Reviewed')
                    ->where('is_blocked',false)
                    ->first();
            if($mails){
                $emaildata = array(
                    'department' => $d,
                    'objectives' => $name,
                    'description' => $des,
                    'targetdate' => date('d-m-Y',strtotime($now)),
                    'objective_id' => $req->input('objid'),
                    'item' => $mails->item,
                    'email_content' => $mails->email_content,
                    'status' => $status,
                );
                //sending mail when Objective reviewed
                if($hodmail!=''){
                    Mail::to($hodmail)->send(new ObjectiveReviewed($emaildata));
                }
                $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
                if(count($userdatas1) > 0)
                {
                    $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if(count($userdatas)>0){
                        Mail::to($userdatas)->send(new ObjectiveReviewed($emaildata));
                    }
                }
            }else{
                Session::flash('errmsg', 'Mail Sending Blocked for Objective Reviewed');
                return redirect()->route('user.objectives.folders');
            }

            $departments = DB::table('departments')->pluck('departmentname');
            $models = DB::table('modenadisable')->First();
            return redirect('/user/objectives/folders/' . $type)->with('model', $models)->with('departments', $departments);
        }
    }

    public function addsapprove(string $type = null)
    {
        if (isset($_GET['val'])) {
            $Oid = $_GET['val'];
            $departments = Departments::all();
            $data = Objective::where('Oid', $Oid)->get();
            foreach ($data as $d) {
                $res = $d->responsibility;
            }
            $resname = DB::table('users')->where('email', $res)->value('name');
            $user = User::all();
            $objective_types = ObjectiveType::where('is_deleted', false)->get();
            $models = DB::table('modenadisable')->First();
            return view('user/objectives/approve')
                ->with('model', $models)
                ->withUser($user)
                ->withDepartments($departments)
                ->withResname($resname)
                ->withData($data)
                ->withType($type)
                ->with('objective_types', $objective_types)
                ->with('Oid', $Oid);
        }
    }

    public function updateapprove(Request $req, string $type = null)
    {
        $validator = Validator::make($req->all(), [
            'target' => 'required|numeric',
            'nature' => 'nullable|in:0,increase,decrease',
            'baseline' => 'nullable|string|max:150',
            'document' => 'nullable|array',
            'document.*' => 'nullable|file|mimes:jpeg,png,jpg,xlsx,xls,pdf,docx,doc',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();

            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $Oid = $req->input('posted');
            $objective_type = $req->input('objective_type');
            $now = $req->input('tardate');
            $d = $req->input('department');
            $nature = $req->input('nature');
            $d = Departments::where('departmentname', $d)->first();
            $d = $d->id;
            $name = $req->input('objectives');
            $des = $req->input('description');
            $baseline = $req->input('baseline');
            $tar = $req->input('target');
            $responsibility = implode(',', $req->input('responsibility'));

            $files = '';
            if ($req->hasFile('document')) {
                $fileArray = [];
                foreach ($req->file('document') as $file) {
                    // $fileSize = $file->getClientOriginalName();
                    // $newFileName = $fileSize;
                    // $destinationPath = public_path('storage/objectives_files');
                    // $file->move($destinationPath, $newFileName);
                    // $fileArray[] = $newFileName;
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $originalName = trim($originalName);
                    $extension = $file->getClientOriginalExtension();

                    $newFileName = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

                    $destinationPath = public_path('storage/objectives_files');
                    $file->move($destinationPath, $newFileName);

                    $fileArray[] = $newFileName;
                }
                $files = implode(',',$fileArray);
            }

            // fetching current objective
            $objective = Objective::where('Oid', $Oid)->first();

            //fetching prevoiusly attached files as string
            $previousAttachedDocuments = $objective->files;

            //concatenating previously attached file and new file
            $files = $previousAttachedDocuments ? $previousAttachedDocuments.', '.$files : $files;

            // $todate = DB::table('objectives')->where('Oid', '=', $Oid)->value('todate');
            // $fromdate = DB::table('objectives')->where('Oid', '=', $Oid)->value('fromdate');
            // $hod = DB::table('departments')->pluck('hod');
            $objdept = DB::table('objectives')->where('Oid', '=', $Oid)->value('department');
            $hodmail = DB::table('departments')->where('id', '=', $objdept)->value('hod');
            $mr = DB::table('representatives')->pluck('email')->toArray();

            if ($req->input('unit') == "No sign") {
                $unit = "No sign";
            } else if ($req->input('unit') == "%") {
                $unit = "%";
            } else {
                $unit = $req->input('otherunit');
            }

            $span = $req->input('span');
            $meth = $req->input('methodology');
            $r = $req->input('resources');
            $res = implode(',', $req->input('responsibility'));

            $remarks = $req->input('higherremarks');
            $p = $req->input('higherproposal');
            $reviewed = $req->input('question');
            $reviewed_at = date('d-m-Y h:i:s A', time());
            $appr_id = User::where('email',Auth::user()->email)->value('id'); //reviewed persons id

            $frequency = $req->input('frequency');

            // $ldate = date('Y-m-d');
            // $date1 = date('Y-03-31');
            // $date2 = date('Y-06-30');
            // $date3 = date('Y-09-30');
            // $date4 = date('Y-12-01');

            if ($reviewed == 2) {
                $status = 'Approved';
                $data = [
                    'objective_type_id' => json_encode($objective_type ? (array) $objective_type : []),
                    'tardate' => $now, 'responsibility' => $responsibility, 'department' => $d, 'objectives' => $name, 'description' => $des, 'otarget' => $tar, 'unit' => $unit, 'frequency' => $frequency,
                    'baseline' => $baseline,'span' => $span, 'methodology' => $meth, 'status' => $status, 'higherremarks' => $remarks, 'resources' => $r, 'responsibility' => $res, 'approved_at' => $reviewed_at,
                    'new_approvedby' => $appr_id,'target_nature' => $nature ? $nature : null,'higherproposal'=>$p, 'files' => $files,
                ];

                DB::table('objectives')
                    ->where('Oid', $Oid)
                    ->update($data);
                //inserting values to activity table superadmin code
                $activity = "Objective is Reviewed and Updated status as Approved where Objective Id" . $Oid;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now1 = Carbon::now()->format('Y-m-d H:i:s');
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now1, 'updated_at' => $now1);
                ViewActivity::insert($datas);
                //end of superadmin code
                $models = DB::table('modenadisable')->First();
                $departments = DB::table('departments')->pluck('departmentname');
                if ($frequency == "Quarterly" /*&& $fromdate < $date1*/) {
                    $data2 = array('Oid' => $Oid, 'target' => null, 'achieved' => null, 'state' => null, 'frequency' => $frequency, 'state' => "First Quarter");
                    DB::table('objreport')->insert($data2);
                }

                // if ($frequency == "Quarterly" && $todate > $date1 && $fromdate < $date2) {
                //     $data3 = array('Oid' => $Oid, 'target' => null, 'achieved' => null, 'state' => null, 'frequency' => $frequency, 'state' => "Second Quarter");
                //     DB::table('objreport')->insert($data3);
                // }

                // if ($frequency == "Quarterly" && $todate > $date2 && $fromdate < $date3) {
                //     $data4 = array('Oid' => $Oid, 'target' => null, 'achieved' => null, 'state' => null, 'frequency' => $frequency, 'state' => "Third Quarter");
                //     DB::table('objreport')->insert($data4);
                // }

                // if ($frequency == "Quarterly" && $todate > $date3) {
                //     $data5 = array('Oid' => $Oid, 'target' => null, 'achieved' => null, 'state' => null, 'frequency' => $frequency, 'state' => "Fourth Quarter");
                //     DB::table('objreport')->insert($data5);
                // }

                if ($frequency == "Half yearly" /*&& $fromdate < $date2*/) {
                    $data6 = array('Oid' => $Oid, 'target' => null, 'achieved' => null, 'state' => null, 'frequency' => $frequency, 'state' => "First Half");
                    DB::table('objreport')->insert($data6);
                }

                // if ($frequency == "Half yearly" && $todate > $date2) {
                //     $data7 = array('Oid' => $Oid, 'target' => null, 'achieved' => null, 'state' => null, 'frequency' => $frequency, 'state' => "Second Half");
                //     DB::table('objreport')->insert($data7);
                // }

                if ($frequency == "Yearly") {
                    $data8 = array('Oid' => $Oid + 1, 'target' => null, 'achieved' => null, 'state' => null, 'frequency' => $frequency, 'state' => "Yearly");
                    DB::table('objreport')->insert($data8);
                }
                $mails = EmailNotification::where('module','objectives')
                    ->where('item','Objective Approved')
                    ->where('is_blocked',false)
                    ->first();
                if($mails){
                    $emaildata = array(
                        'department' => $req->input('department'),
                        'objectives' => $name,
                        'description' => $des,
                        'targetdate' => date('d-m-Y',strtotime($now)),
                        'objective_id' => $req->input('objid'),
                        'item' => $mails->item,
                        'email_content' => $mails->email_content,
                        'status' => $status,
                    );
                    //sending mail when Objective approve successfully
                    if($hodmail!=''){
                        Mail::to($hodmail)->send(new ObjectiveApprove($emaildata));
                    }
                    if(count($mr)>0){
                        Mail::to($mr)->send(new ObjectiveApprove($emaildata));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
                    if(count($userdatas1) > 0)
                    {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if(count($userdatas)>0){
                            Mail::to($userdatas)->send(new ObjectiveApprove($emaildata));
                        }
                    }
                }else{
                    Session::flash('errmsg', 'Mail Sending Blocked for Objective Approved');
                    return redirect()->route('user.objectives.folders');
                }
                return redirect('/user/objectives/folders/' . $type)->with('model', $models)->with('departments', $departments);
            } else {
                $status = 'Rejected';
            }
            $appr_id = User::where('email',Auth::user()->email)->value('id');
            $data = [
                'objective_type_id' => json_encode($objective_type ? (array) $objective_type : []),
                'tardate' => $now, 'responsibility' => $responsibility, 'department' => $d, 'objectives' => $name, 'description' => $des, 'otarget' => $tar, 'unit' => $unit, 'frequency' => $frequency,
                'baseline' => $baseline,'span' => $span, 'methodology' => $meth, 'higherproposal' => $p, 'status' => $status, 'resources' => $r, 'responsibility' => $res, 'approved_at' => $reviewed_at,
                'target_nature' => $nature ? $nature : null,'higherremarks' => $remarks,'new_approvedby' => $appr_id, 'files' => $files
            ];

            DB::table('objectives')
                ->where('Oid', $Oid)
                ->update($data);
            $mails = EmailNotification::where('module','objectives')
                    ->where('item','Objective Approved')
                    ->where('is_blocked',false)
                    ->first();
            if($mails){
                $emaildata = array(
                    'department' => $req->input('department'),
                    'objectives' => $name,
                    'description' => $des,
                    'targetdate' => date('d-m-Y',strtotime($now)),
                    'objective_id' => $req->input('objid'),
                    'item' => $mails->item,
                    'email_content' => $mails->email_content,
                    'status' => $status,
                );
                //sending mail when Objective approve successfully
                if($hodmail!=''){
                    Mail::to($hodmail)->send(new ObjectiveApprove($emaildata));
                }
                if(count($mr)>0){
                    Mail::to($mr)->send(new ObjectiveApprove($emaildata));
                }
                $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
                if(count($userdatas1) > 0)
                {
                    $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if(count($userdatas)>0){
                        Mail::to($userdatas)->send(new ObjectiveApprove($emaildata));
                    }
                }
            }else{
                Session::flash('errmsg', 'Mail Sending Blocked for Objective Approved');
                return redirect()->route('user.objectives.folders');
            }
            Session::flash('msg', "Notification send After Objective Approving Procedure");
            //inserting values to activity table superadmin
            $activity = "Objective is Rejected and Updated status as Rejected where Objective Id" . $Oid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now1 = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now1, 'updated_at' => $now1);
            ViewActivity::insert($datas);
            //end of superadmin code
            $models = DB::table('modenadisable')->First();
            $departments = DB::table('departments')->pluck('departmentname');
            return redirect('/user/objectives/folders/' . $type)->with('model', $models)->with('departments', $departments);
        }
    }

    public function viewreport(Request $request,$id)
    {
            $year = [];
            $otargetWithYear = [];
            $oachievedWithYear = [];
            $arr = [];
            $arr1 = [];
            //$Oid = $_GET['val'];
            $Oid = $id;
            $data = DB::table('objectives')
                ->where('Oid', $Oid)
                ->get();
            $objective = DB::table('objectives')
                ->where('Oid', $Oid)
                ->first();
            $record_date = explode('-',$objective->fromdate);
            $monthly_start_year = $record_date[0];
            $description = DB::table('objectives')
                ->where('Oid', $Oid)
                ->value('description');
            foreach ($data as $dat) {
                $freq = $dat->frequency;
                $Oid = $dat->Oid;
            }
            $quarters = [
                "First Quarter" => 0,
                "Second Quarter" => 1,
                "Third Quarter" => 2,
                "Fourth Quarter" => 3,
            ];
            if($objective->frequency==='Monthly'){
                $targetp =  DB::table('objreport')
                                ->select(DB::raw('target as number, state,quarter,year,frequency'))
                                ->where('Oid', $Oid)
                                ->whereNotNull('year')
                                ->where('year',Date('Y'))
                                ->get();

                $achievedp = DB::table('objreport')
                                ->select(DB::raw('achieved as number, state ,quarter,year,frequency'))
                                ->where('Oid', $Oid)
                                ->whereNotNull('year')
                                ->where('year',Date('Y'))
                                ->get();

            }
            else if($objective->frequency==='Quarterly'){
                $targetp =  DB::table('objreport')
                                        ->select(DB::raw('target as number, state,quarter,year,frequency'))
                                        ->where('Oid', $Oid)
                                        ->whereNotNull('year')
                                        ->where('year',Date('Y'))
                                        ->get();

                $achievedp = DB::table('objreport')
                                        ->select(DB::raw('achieved as number, state ,quarter,year,frequency'))
                                        ->where('Oid', $Oid)
                                        ->whereNotNull('year')
                                        ->where('year',Date('Y'))
                                        ->get();
            }
            else{
                $targetp =  DB::table('objreport')
                                ->select(DB::raw('target as number, state,quarter,year,frequency'))
                                ->where('Oid', $Oid)
                                ->whereNotNull('year')
                                ->get();

                $achievedp = DB::table('objreport')
                                ->select(DB::raw('achieved as number, state ,quarter,year,frequency'))
                                ->where('Oid', $Oid)
                                ->whereNotNull('year')
                                ->get();
            }

            $title2 = array('Over All Performance');
            $otargetp = DB::select("select otarget AS number,todate
             FROM  objectives
             where Oid=$Oid or parents_id=$Oid
            ");
            $oachievedp = DB::select("select oachieved AS number,todate
             FROM  objectives
             where Oid=$Oid or parents_id=$Oid
            ");
            $states = [];
            foreach ($targetp as $key) {
                if($key->frequency==='Quarterly'){
                      $db_quarter = preg_replace('/\s+/', '', $key->quarter);
                      if($db_quarter==$key->year.'/'.'Jan-Mar'){
                        $states[] = 'Q1 '.$key->year;
                      }
                      else if($db_quarter==$key->year.'/'.'Apr-Jun'){
                        $states[] = 'Q2 '.$key->year;
                      }
                      else if($db_quarter==$key->year.'/'.'Jul-Sep'){
                        $states[] = 'Q3 '.$key->year;
                      }
                      else if($db_quarter==$key->year.'/'.'Oct-Dec'){
                        $states[] = 'Q4 '.$key->year;
                      }
                   }
                else{
                      $states[] = $key->quarter;
                   }
            }

            if ($freq == "Quarterly") {
                $names = $states;
                $title = "Quarterwise";
            } elseif ($freq == "Half yearly") {
                //$names = array('First half', 'Second half');
                $names = $states;
                $title = "Half yearly";
            } elseif($freq == "Yearly") {
                //$names = array("Yearly");
                $names = $states;
                $title = "Yearly";
            }
            else{
                $names = $states;
                $title = "Monthly";
            }

            $target = [];

            foreach ($targetp as $key1) {
                $target[] = $key1->number != null ? $key1->number : 0;
            }

            $achieved = [];

            foreach ($achievedp as $key1) {
                $achieved[] = $key1->number != null ? $key1->number : 0;
            }

            $otarget = [];
            foreach ($otargetp as $key1) {
                array_push($otarget, $key1->number);
            }
            $y = Carbon::now()->format('Y');
            foreach ($otargetp as $key1) {
                $dates = explode('-', $key1->todate);
                if ($dates[0] <= $y + 4) {
                    $year[] = $dates[0];
                    $otargetWithYear[$dates[0]] = $key1->number;
                }
            }

            // Sorting
            asort($year);

            $previous = null;
            $result = array();
            $consecutiveArray = array();

            // Slice array by consecutive sequences
            foreach ($year as $number) {
                if ($number == $previous + 1) {
                    $consecutiveArray[] = $number;
                } else {
                    $result[] = $consecutiveArray;
                    $consecutiveArray = array($number);
                }
                $previous = $number;
            }
            $result[] = $consecutiveArray;
            rsort($result);
            $year = $result[0];

            foreach ($otargetWithYear as $key => $item) {
                if (in_array($key, $result[0])) {
                    $arr[] = $item;
                }
            }
            $otarget = $arr;
            $oachieved = [];
            foreach ($oachievedp as $key1) {
                array_push($oachieved, $key1->number);
            }
            foreach ($oachievedp as $key1) {
                $dates = explode('-', $key1->todate);
                if ($dates[0] <= $y + 4) {
                    $oachievedWithYear[$dates[0]] = $key1->number;
                }
            }
            foreach ($oachievedWithYear as $key => $item) {
                if (in_array($key, $result[0])) {
                    $arr1[] = $item;
                }
            }
            $oachieved = $arr1;

            if($objective->frequency==='Monthly' || $objective->frequency==='Quarterly')
            {
                if ($request->ajax()) {
                    if (empty($request->selected_year)) {

                        $current_year =Date('Y');

                            $datareports = DB::table('objreport')
                                                   ->select('quarter','target','achieved')
                                                    ->where('Oid', $Oid)
                                                    ->whereNotNull('year')
                                                    ->where('year',$current_year)
                                                    ->get();
                            $report_collection = collect();
                            if($objective->frequency==='Monthly'){
                                    for ($i=1; $i <= 12 ; $i++) {
                                        $month_name = DateTime::createFromFormat('d-m', '1-'.$i)->format('M');
                                        $target_entry = "";
                                        $achieved_entry = "";
                                        $entry_list = collect([]);
                                        foreach ($datareports as $datareport) {
                                            if (str_contains($datareport->quarter, $month_name)) {
                                                $target_entry = $datareport->target;
                                                $achieved_entry = $datareport->achieved;
                                                $entry_list->push($target_entry);
                                                $entry_list->push($achieved_entry);
                                                break;
                                            }
                                        }
                                        $report_collection->put($month_name, $entry_list);
                                    }

                                    $month_achieved_all = DB::table('objreport')
                                                                ->where('Oid', $Oid)
                                                                ->whereNotNull('year')
                                                                ->where('year',$current_year)
                                                                ->pluck('achieved')
                                                                ->toArray();

                                    $total_achieved = array_sum($month_achieved_all);
                                    $achieved_over = $total_achieved / 12;
                                    $overall_achieved = number_format((float)$achieved_over, 2, '.', '');

                                    $month_target_all = DB::table('objreport')
                                                                            ->where('Oid', $Oid)
                                                                            ->whereNotNull('year')
                                                                            ->where('year',$current_year)
                                                                            ->pluck('target')
                                                                            ->toArray();

                                    $total_target = array_sum($month_target_all);
                                    $target_over = $total_target / 12;
                                    $overall_target = number_format((float)$target_over, 2, '.', '');
                                    $obj_frequency = 'Monthly';


                            }
                            else if($objective->frequency==='Quarterly'){
                                   $quarter_name = [$current_year.'/Jan-Mar',$current_year.'/Apr-Jun',
                                                  $current_year.'/Jul-Sep',$current_year.'/Oct-Dec'
                                                ];

                                    for($i=0;$i<count($quarter_name);$i++){
                                        $target_entry = "";
                                        $achieved_entry = "";
                                        $entry_list = collect([]);
                                        foreach ($datareports as $datareport) {
                                            $db_quarter = preg_replace('/\s+/', '', $datareport->quarter);
                                            if ($db_quarter==$quarter_name[$i]) {
                                                $target_entry = $datareport->target;
                                                $achieved_entry = $datareport->achieved;
                                                $entry_list->push($target_entry);
                                                $entry_list->push($achieved_entry);
                                            }
                                        }
                                        $report_collection->put($quarter_name[$i], $entry_list);
                                    }

                                    $month_achieved_all = DB::table('objreport')
                                                                        ->where('Oid', $Oid)
                                                                        ->whereNotNull('year')
                                                                        ->where('year',$current_year)
                                                                        ->pluck('achieved')
                                                                        ->toArray();

                                    $total_achieved = array_sum($month_achieved_all);
                                    $achieved_over = $total_achieved / 4;
                                    $overall_achieved = number_format((float)$achieved_over, 2, '.', '');

                                    $month_target_all = DB::table('objreport')
                                                                            ->where('Oid', $Oid)
                                                                            ->whereNotNull('year')
                                                                            ->where('year',$current_year)
                                                                            ->pluck('target')
                                                                            ->toArray();

                                    $total_target = array_sum($month_target_all);
                                    $target_over = $total_target / 4;
                                    $overall_target = number_format((float)$target_over, 2, '.', '');
                                    $obj_frequency = 'Quarterly';



                            }
                    }
                    else {
                            $current_year =$request->selected_year;

                                $datareports = DB::table('objreport')
                                                       ->select('quarter','target','achieved')
                                                        ->where('Oid', $Oid)
                                                        ->whereNotNull('year')
                                                        ->where('year',$current_year)
                                                        ->get();
                                $report_collection = collect();
                                if($objective->frequency==='Monthly'){
                                        for ($i=1; $i <= 12 ; $i++) {
                                            $month_name = DateTime::createFromFormat('d-m', '1-'.$i)->format('M');
                                            $target_entry = "";
                                            $achieved_entry = "";
                                            $entry_list = collect([]);
                                            foreach ($datareports as $datareport) {
                                                if (str_contains($datareport->quarter, $month_name)) {
                                                    $target_entry = $datareport->target;
                                                    $achieved_entry = $datareport->achieved;
                                                    $entry_list->push($target_entry);
                                                    $entry_list->push($achieved_entry);
                                                    break;
                                                }
                                            }
                                            $report_collection->put($month_name, $entry_list);
                                        }

                                        $month_achieved_all = DB::table('objreport')
                                                                    ->where('Oid', $Oid)
                                                                    ->whereNotNull('year')
                                                                    ->where('year',$current_year)
                                                                    ->pluck('achieved')
                                                                    ->toArray();

                                        $total_achieved = array_sum($month_achieved_all);
                                        $achieved_over = $total_achieved / 12;
                                        $overall_achieved = number_format((float)$achieved_over, 2, '.', '');

                                        $month_target_all = DB::table('objreport')
                                                                                ->where('Oid', $Oid)
                                                                                ->whereNotNull('year')
                                                                                ->where('year',$current_year)
                                                                                ->pluck('target')
                                                                                ->toArray();

                                        $total_target = array_sum($month_target_all);
                                        $target_over = $total_target / 12;
                                        $overall_target = number_format((float)$target_over, 2, '.', '');
                                        $obj_frequency = 'Monthly';


                                }
                                else if($objective->frequency==='Quarterly'){
                                       $quarter_name = [$current_year.'/Jan-Mar',$current_year.'/Apr-Jun',
                                                      $current_year.'/Jul-Sep',$current_year.'/Oct-Dec'
                                                    ];

                                        for($i=0;$i<count($quarter_name);$i++){
                                            $target_entry = "";
                                            $achieved_entry = "";
                                            $entry_list = collect([]);
                                            foreach ($datareports as $datareport) {
                                                $db_quarter = preg_replace('/\s+/', '', $datareport->quarter);
                                                if ($db_quarter==$quarter_name[$i]) {
                                                    $target_entry = $datareport->target;
                                                    $achieved_entry = $datareport->achieved;
                                                    $entry_list->push($target_entry);
                                                    $entry_list->push($achieved_entry);
                                                }
                                            }
                                            $report_collection->put($quarter_name[$i], $entry_list);
                                        }

                                        $month_achieved_all = DB::table('objreport')
                                                                            ->where('Oid', $Oid)
                                                                            ->whereNotNull('year')
                                                                            ->where('year',$current_year)
                                                                            ->pluck('achieved')
                                                                            ->toArray();

                                        $total_achieved = array_sum($month_achieved_all);
                                        $achieved_over = $total_achieved / 4;
                                        $overall_achieved = number_format((float)$achieved_over, 2, '.', '');

                                        $month_target_all = DB::table('objreport')
                                                                                ->where('Oid', $Oid)
                                                                                ->whereNotNull('year')
                                                                                ->where('year',$current_year)
                                                                                ->pluck('target')
                                                                                ->toArray();

                                        $total_target = array_sum($month_target_all);
                                        $target_over = $total_target / 4;
                                        $overall_target = number_format((float)$target_over, 2, '.', '');
                                        $obj_frequency = 'Quarterly';

                                }

                    }
                    return response()->json([
                        'status' => 200,
                        'datareports' => $datareports,
                        'current_year' => $current_year,
                        'overall_achieved' => $overall_achieved,
                        'overall_target' => $overall_target,
                        'report_collection' => $report_collection,
                        'obj_frequency' => $obj_frequency,
                    ]);
                }
                else{

                        $selected_year = Date('Y');
                        $main_records = DB::table('objreport')->select('quarter','target','achieved')
                                                            ->where('Oid', $Oid)
                                                            ->whereNotNull('year')
                                                            ->where('year',$selected_year)
                                                            ->get();


                        if(count($main_records)>0){
                            $current_year = Date('Y');

                            $datareports = DB::table('objreport')
                                                       ->select('quarter','target','achieved')
                                                        ->where('Oid', $Oid)
                                                        ->whereNotNull('year')
                                                        ->where('year',$current_year)
                                                        ->get();

                        }
                        else{
                            $current_year = Date('Y') - 1;
                            $datareports = DB::table('objreport')
                                                       ->select('quarter','target','achieved')
                                                        ->where('Oid', $Oid)
                                                        ->whereNotNull('year')
                                                        ->where('year',$current_year)
                                                        ->get();
                        }

                        $report_collection = collect();

                        if($objective->frequency==='Monthly'){
                            for ($i=1; $i <= 12 ; $i++) {
                                $month_name = DateTime::createFromFormat('d-m', '1-'.$i)->format('M');
                                $target_entry = "";
                                $achieved_entry = "";
                                $entry_list = collect([]);
                                foreach ($datareports as $datareport) {
                                    if (str_contains($datareport->quarter, $month_name)) {
                                        $target_entry = $datareport->target;
                                        $achieved_entry = $datareport->achieved;
                                        $entry_list->push($target_entry);
                                        $entry_list->push($achieved_entry);
                                        break;
                                    }
                                }
                                $report_collection->put($month_name, $entry_list);
                            }

                            $month_achieved_all = DB::table('objreport')
                                                                    ->where('Oid', $Oid)
                                                                    ->whereNotNull('year')
                                                                    ->where('year',$current_year)
                                                                    ->pluck('achieved')
                                                                    ->toArray();

                            $total_achieved = array_sum($month_achieved_all);
                            $achieved_over = $total_achieved / 12;
                            $overall_achieved = number_format((float)$achieved_over, 2, '.', '');

                            $month_target_all = DB::table('objreport')
                                                                    ->where('Oid', $Oid)
                                                                    ->whereNotNull('year')
                                                                    ->where('year',$current_year)
                                                                    ->pluck('target')
                                                                    ->toArray();

                            $total_target = array_sum($month_target_all);
                            $target_over = $total_target / 12;
                            $overall_target = number_format((float)$target_over, 2, '.', '');
                       }
                      else if($objective->frequency==='Quarterly'){
                                $quarter_name = [$current_year.'/Jan-Mar',$current_year.'/Apr-Jun',
                                                    $current_year.'/Jul-Sep',$current_year.'/Oct-Dec'
                                                ];

                                for($i=0;$i<count($quarter_name);$i++){
                                    $target_entry = "";
                                    $achieved_entry = "";
                                    $entry_list = collect([]);
                                    foreach ($datareports as $datareport) {
                                        $db_quarter = preg_replace('/\s+/', '', $datareport->quarter);
                                        if ($db_quarter==$quarter_name[$i]) {
                                            $target_entry = $datareport->target;
                                            $achieved_entry = $datareport->achieved;
                                            $entry_list->push($target_entry);
                                            $entry_list->push($achieved_entry);
                                        }
                                    }
                                    $report_collection->put($quarter_name[$i], $entry_list);
                                }

                                $month_achieved_all = DB::table('objreport')
                                                                    ->where('Oid', $Oid)
                                                                    ->whereNotNull('year')
                                                                    ->where('year',$current_year)
                                                                    ->pluck('achieved')
                                                                    ->toArray();

                                $total_achieved = array_sum($month_achieved_all);
                                $achieved_over = $total_achieved / 4;
                                $overall_achieved = number_format((float)$achieved_over, 2, '.', '');

                                $month_target_all = DB::table('objreport')
                                                                        ->where('Oid', $Oid)
                                                                        ->whereNotNull('year')
                                                                        ->where('year',$current_year)
                                                                        ->pluck('target')
                                                                        ->toArray();

                                $total_target = array_sum($month_target_all);
                                $target_over = $total_target / 4;
                                $overall_target = number_format((float)$target_over, 2, '.', '');
                        }

                    $year_datas = DB::table('objreport')->whereNotNull('year')
                                                        ->distinct()
                                                        ->pluck('year')
                                                        ->toArray();

            }
        }
        else{
            $datareports = NULL;
            $current_year = NULL;
            $overall_achieved = NULL;
            $overall_target = NULL;
            $year_datas =NULL;
            $report_collection = NULL;
        }


            $type = NULL;
            $models = DB::table('modenadisable')->First();
             return view('user.objectives.viewreport')
                ->with('names', json_encode($names, JSON_NUMERIC_CHECK))
                ->with('title', json_encode($title, JSON_NUMERIC_CHECK))
                ->with('target', json_encode($target, JSON_NUMERIC_CHECK))
                ->with('achieved', json_encode($achieved, JSON_NUMERIC_CHECK))
                ->with('otarget', json_encode($otarget, JSON_NUMERIC_CHECK))
                ->with('oachieved', json_encode($oachieved, JSON_NUMERIC_CHECK))
                ->with('model', $models)->with('description', $description)
                ->with('year', json_encode($year, JSON_NUMERIC_CHECK))
                ->with('data', $data)
                ->with('datareports', $datareports)
                ->with('otarget', json_encode($otarget, JSON_NUMERIC_CHECK))
                ->with('objective', $objective)
                ->with('monthly_start_year',$monthly_start_year)
                ->with('type', $type)
                ->with('current_year', $current_year)
                ->with('overall_achieved', $overall_achieved)
                ->with('overall_target',$overall_target)
                ->with('report_collection', $report_collection)
                ->with('year_datas',$year_datas)
                ->with('oachieved', json_encode($oachieved, JSON_NUMERIC_CHECK));

    }

    public function selectmonthfreqgraph(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->month_to_select)) {
                $objective_id = $request->objective_id;
                $current_year = Date('Y');
                $reports = DB::table('objreport')
                                    ->select('quarter','target','achieved')
                                    ->where('Oid', $objective_id)
                                    ->whereNotNull('year')
                                    ->where('frequency','Monthly')
                                    ->get();

                $month_list = [
                                'Jan','Feb','Mar','Apr','May','Jun',
                                'Jul','Aug','Sep','Oct','Nov','Dec'
                              ];

                $count = 1;
                $graph_collection = collect();
                $total_listed_months = array();
                for ($index = 0; $index < count($month_list); $index++) {
                    $listed_months = $current_year.'/'.$month_list[$index];
                    array_push($total_listed_months,$listed_months);
                    if ($count == 13) {
                        break;
                    }
                    $count++;
                }

                foreach($total_listed_months as $total_listed_month){
                    $target_entry = "";
                    $achieved_entry = "";
                    $entry_list = collect([]);
                    foreach($reports as $report){
                    $report_quarter = preg_replace('/\s+/', '', $report->quarter);
                    if($report_quarter==$total_listed_month){
                            $target_entry = $report->target;
                            $achieved_entry = $report->achieved;
                            $entry_list->push($target_entry);
                            $entry_list->push($achieved_entry);

                    }
                    }
                    $graph_collection->put($total_listed_month, $entry_list);
                }

                $graph_display_months = array();
                foreach($graph_collection->keys()->all() as $graphmonths){
                    array_push($graph_display_months,$graphmonths);
                }

                return response()->json([
                                        'status' => 200,
                                        'graph_display_months'=> $graph_display_months,
                                        'graph_collection'=>$graph_collection,
                                        'total_listed_months'=>$total_listed_months,
                                    ]);


            }
            else{
                $objective_id = $request->objective_id;
                $frm_month = preg_replace('/\s+/', '', $request->month_frm_select);
                $frm_month_array = explode('/',$frm_month);
                $frm_year = $frm_month_array[0];
                $frm_month = $frm_month_array[1];

                $to_month = preg_replace('/\s+/', '', $request->month_to_select);
                $to_month_array = explode('/',$to_month);
                $to_year = $to_month_array[0];
                $to_month = $to_month_array[1];


                $month_list = [
                                'Jan','Feb','Mar','Apr','May','Jun',
                                'Jul','Aug','Sep','Oct','Nov','Dec'
                            ];

                $month_index = array_search($frm_month,$month_list);
                $next_month_index = $month_index + 1;

                $to_month_index = array_search($to_month,$month_list);

                $next_to_month_index = $to_month_index + 1;

                $count = 1;
                $graph_collection = collect();

                $reports = DB::table('objreport')
                                    ->select('quarter','target','achieved')
                                    ->where('Oid', $objective_id)
                                    ->whereNotNull('year')
                                    ->where('frequency','Monthly')
                                    ->get();

                $total_listed_months = array();
                for ($index = $month_index; $index < count($month_list); $index++) {
                    $listed_months = $frm_year.'/'.$month_list[$index];
                    array_push($total_listed_months,$listed_months);

                    if($index==$to_month_index && $frm_year==$to_year){
                        break;
                    }

                    if ($index == count($month_list)-1) {
                        $index = -1;
                        $frm_year++;
                    }

                    if ($count == 13) {
                        break;
                    }
                    $count++;
                }


                foreach($total_listed_months as $total_listed_month){
                    $target_entry = "";
                    $achieved_entry = "";
                    $entry_list = collect([]);
                    foreach($reports as $report){
                    $report_quarter = preg_replace('/\s+/', '', $report->quarter);
                    if($report_quarter==$total_listed_month){
                            $target_entry = $report->target;
                            $achieved_entry = $report->achieved;
                            $entry_list->push($target_entry);
                            $entry_list->push($achieved_entry);

                    }
                    }
                    $graph_collection->put($total_listed_month, $entry_list);
                }

                $graph_display_months = array();
                foreach($graph_collection->keys()->all() as $graphmonths){
                    array_push($graph_display_months,$graphmonths);
                }

                return response()->json([
                    'status' => 200,
                    'graph_display_months'=> $graph_display_months,
                    'graph_collection'=>$graph_collection,
                    'total_listed_months'=>$total_listed_months,

                ]);
            }
        }

    }

    public function selectquarterfreqgraph(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->quarter_to_select)) {
                $objective_id = $request->objective_id;
                $current_year = Date('Y');
                $reports = DB::table('objreport')
                                    ->select('quarter','target','achieved')
                                    ->where('Oid', $objective_id)
                                    ->whereNotNull('year')
                                    ->where('frequency','Quarterly')
                                    ->get();

                $month_list = ['Jan-Mar','Apr-Jun','Jul-Sep','Oct-Dec'];

                $count = 1;
                $graph_collection = collect();
                $total_listed_quarters = array();
                for ($index = 0; $index < count($month_list); $index++) {
                    $listed_quarter = $current_year.'/'.$month_list[$index];
                    array_push($total_listed_quarters,$listed_quarter);
                    if ($count == 13) {
                        break;
                    }
                    $count++;
                }

                foreach($total_listed_quarters as $total_listed_quarter){
                    $target_entry = "";
                    $achieved_entry = "";
                    $entry_list = collect([]);
                    foreach($reports as $report){
                    $report_quarter = preg_replace('/\s+/', '', $report->quarter);
                    if($report_quarter==$total_listed_quarter){
                            $target_entry = $report->target;
                            $achieved_entry = $report->achieved;
                            $entry_list->push($target_entry);
                            $entry_list->push($achieved_entry);

                    }
                    }
                    $graph_collection->put($total_listed_quarter, $entry_list);
                }

                $quarterlist= '';
                $graph_display_quarters = array();
                foreach($graph_collection->keys()->all() as $graphquarters){
                        $quarterlist = explode("/",$graphquarters);
                        if($quarterlist[1]=='Jan-Mar'){
                            $pass_value = 'Q1'. " " .$quarterlist[0];
                        }
                        else if($quarterlist[1]=='Apr-Jun'){
                            $pass_value = 'Q2'. " " .$quarterlist[0];
                        }
                        else if($quarterlist[1]=='Jul-Sep'){
                            $pass_value = 'Q3'. " " .$quarterlist[0];
                        }
                        else if($quarterlist[1]=='Oct-Dec'){
                            $pass_value = 'Q4'. " " .$quarterlist[0];
                        }

                    array_push($graph_display_quarters,$pass_value);
                }

                return response()->json([
                                        'status' => 200,
                                        'graph_display_quarters'=> $graph_display_quarters,
                                        'graph_collection'=>$graph_collection,
                                        'total_listed_quarter'=>$total_listed_quarter,
                                    ]);


            }
            else{
                $objective_id = $request->objective_id;
                $frm_quarter = preg_replace('/\s+/', '', $request->quarter_frm_select);
                $frm_quarter_array = explode('/',$frm_quarter);
                $frm_year = $frm_quarter_array[0];
                $frm_month = $frm_quarter_array[1];

                $to_quarter = preg_replace('/\s+/', '', $request->quarter_to_select);
                $to_quarter_array = explode('/',$to_quarter);
                $to_year = $to_quarter_array[0];
                $to_month = $to_quarter_array[1];

                $month_list = ['Jan-Mar','Apr-Jun','Jul-Sep','Oct-Dec'];

                $quarter_index = array_search($frm_month,$month_list);
                $next_quarter_index = $quarter_index + 1;

                $to_quarter_index = array_search($to_month,$month_list);

                $next_to_quarter_index = $to_quarter_index + 1;

                $count = 1;
                $graph_collection = collect();

                $reports = DB::table('objreport')
                                    ->select('quarter','target','achieved')
                                    ->where('Oid', $objective_id)
                                    ->whereNotNull('year')
                                    ->where('frequency','Quarterly')
                                    ->get();

                $total_listed_quarters = array();
                for ($index = $quarter_index; $index < count($month_list); $index++) {
                    $listed_months = $frm_year.'/'.$month_list[$index];
                    array_push($total_listed_quarters,$listed_months);

                    if($index==$to_quarter_index && $frm_year==$to_year){
                        break;
                    }

                    if ($index == count($month_list)-1) {
                        $index = -1;
                        $frm_year++;
                    }

                    if ($count == 13) {
                        break;
                    }
                    $count++;
                }


                foreach($total_listed_quarters as $total_listed_quarter){
                    $target_entry = "";
                    $achieved_entry = "";
                    $entry_list = collect([]);
                    foreach($reports as $report){
                    $report_quarter = preg_replace('/\s+/', '', $report->quarter);
                    if($report_quarter==$total_listed_quarter){
                            $target_entry = $report->target;
                            $achieved_entry = $report->achieved;
                            $entry_list->push($target_entry);
                            $entry_list->push($achieved_entry);

                    }
                    }
                    $graph_collection->put($total_listed_quarter, $entry_list);
                }

                $quarterlist= '';
                $graph_display_quarters = array();
                foreach($graph_collection->keys()->all() as $graphquarters){
                        $quarterlist = explode("/",$graphquarters);
                        if($quarterlist[1]=='Jan-Mar'){
                            $pass_value = 'Q1'. " " .$quarterlist[0];
                        }
                        else if($quarterlist[1]=='Apr-Jun'){
                            $pass_value = 'Q2'. " " .$quarterlist[0];
                        }
                        else if($quarterlist[1]=='Jul-Sep'){
                            $pass_value = 'Q3'. " " .$quarterlist[0];
                        }
                        else if($quarterlist[1]=='Oct-Dec'){
                            $pass_value = 'Q4'. " " .$quarterlist[0];
                        }

                    array_push($graph_display_quarters,$pass_value);
                }

                return response()->json([
                    'status' => 200,
                    'graph_display_quarters'=> $graph_display_quarters,
                    'graph_collection'=>$graph_collection,
                    'total_listed_quarter'=>$total_listed_quarter,

                ]);
            }
        }

    }

    public function updateCAR(Request $request, $id)
    {
        $CAR = $request->CAR;
        DB::table('objectives')->where('Oid', $id)->update(['CAR' => $CAR]);
        //inserting values to activity table superadmin
        $activity = "Objective CAR value is Updated with " . $CAR . " where Objective Id" . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //end of superadmin code
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    public function close()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $departments = Departments::all();

            $mr = DB::table('representatives')->pluck('email');
            $models = DB::table('modenadisable')->First();
            return view('user.objectives.close')->withId($id)->withDepartments($departments)->withmr($mr)->with('model', $models);
        }
    }
    public function closing(Request $req)
    {
        $d = $req->input('close');
        $CAR = $req->input('CAR');
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $id = $req->input('id');
        if ($d == 2) {
            $data = array('status' => 'Achieved');

            //inserting values to activity table superadmin code
            $activity = "Objective closed with Status  Updated to Achieved Objective Id" . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code
        } else {
            $data = array('status' => 'Not Achieved', 'CAR' => $CAR);
        }

        DB::table('objectives')
            ->where('Oid', $id)
            ->update($data);

        //inserting values to activity table superadmin code
        $activity = "Objective closed with Status  Updated to Not Achieved Objective Id" . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        Viewactivity::insert($datas);
        //end of superadmin code

        $models = DB::table('modenadisable')->First();
        $departments = DB::table('departments')->pluck('departmentname');
        return redirect('/user/objectives/folders')->with('model', $models)->with('departments', $departments);
    }

    public function achieved()
    {
        $data1 = array();
        $data = DB::table('objectives')->where('department', Session::get('objdept'))
            ->whereIn('status', ['Achieved'])
            ->get();
        if (count($data) > 0) {
            foreach ($data as $d1) {
                if (substr($d1->tardate, 0, 4) === Session::get('year')) {
                    $data1[] = $d1;
                }
            }
        }

        $status = DB::table('objectives')->get();
        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $md = DB::table('managements')->pluck('email');
        $models = DB::table('modenadisable')->First();
        return view('user.objectives.achieved')->with('data1', $data1)->withDepartments($departments)->withMr($mr)->withMd($md)->with('model', $models);
    }
    public function notachieved()
    {
        $data2 = array();
        $data = DB::table('objectives')->where('department', Session::get('objdept'))
            ->whereIn('status', ['Not Achieved'])
            ->get();
        if (count($data) > 0) {
            foreach ($data as $d1) {
                if (substr($d1->tardate, 0, 4) === Session::get('year')) {
                    $data2[] = $d1;
                }
            }
        }
        $status = DB::table('objectives')->get();
        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $md = DB::table('managements')->pluck('email');
        $models = DB::table('modenadisable')->First();
        return view('user.objectives.notachieved')->with('data2', $data2)->withDepartments($departments)->withMr($mr)->withMd($md)->with('model', $models);
    }
    public function my_obj()
    {
        $departments = DB::table('departments')->get();
        foreach ($departments as $d) {
            if ($d->hod == Auth::user()->email) {
                $dep = $d->departmentname;
            }

        }
        $dep = $d->departmentname;
        $data3 = array();
        $data = DB::table('objectives')->where('department', Session::get('objdept'))
            ->where('department', $dep)
            ->get();
        if (count($data) > 0) {
            foreach ($data as $d1) {
                if (substr($d1->tardate, 0, 4) === Session::get('year')) {
                    $data3[] = $d1;
                }
            }
        }
        $status = DB::table('objectives')->get();

        $mr = DB::table('representatives')->pluck('email');
        $md = DB::table('managements')->pluck('email');
        $models = DB::table('modenadisable')->First();
        return view('user.objectives.my_obj')->with('data3', $data3)->withDepartments($departments)->withMr($mr)->withMd($md)->with('model', $models);
    }
    public function search(Request $req)
    {

        $status = DB::table('objectives')->get();
        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $md = DB::table('managements')->pluck('email');
        // --------------------achieved-------------
        $data1 = DB::table('objectives')
            ->whereIn('status', ['Achieved'])
            ->paginate(100);
        //  ---------------Not achieved-----------
        $data2 = DB::table('objectives')
            ->whereIn('status', ['Not Achieved'])
            ->paginate(100);
        //-----------------My objectives

        $deptm = null;
        $deptm = Auth::user()->deptid;

        $data3 = DB::table('objectives')
            ->where('department', $deptm)
            ->paginate(100);

        //---------------------Objectives report

        $ldate = date('Y-m-d');
        $date1 = date('Y-03-01');
        $date2 = date('Y-06-01');

        $date4 = date('Y-12-01');
        if ($ldate <= $date1) {
            $data4 = DB::table('objectives')
                ->where('frequency', '=', 'Quarterly')
                ->whereIn('status', ['Approved'])
                ->paginate(100);
        } else if ($ldate <= $date4 && $ldate >= $date1) {
            $data4 = DB::table('objectives')
                ->whereIn('frequency', ['Quarterly', 'Half yearly'])
                ->whereIn('status', ['Approved'])
                ->paginate(100);
        } else if ($ldate >= $date4) {
            $data4 = DB::table('objectives')
                ->whereIn('frequency', ['Quarterly', 'Half yearly', 'Yearly'])
                ->whereIn('status', ['Approved'])
                ->paginate(100);
        }

        //------------------search---------------
        $departmentname = DB::table('departments')->pluck('departmentname');
        $keyword = $req->input('keyword');

        $data = DB::table('objectives')
            ->where(function ($query) use ($req) {
                $query

                    ->where('department', '=', $req->input('keyword'))
                    ->orWhere('objectives', '=', $req->input('keyword'))
                    ->orWhere('description', '=', $req->input('keyword'))
                    ->orWhere('responsibility', '=', $req->input('keyword'))
                    ->orWhere('fromdate', '=', $req->input('keyword'))
                    ->orWhere('todate', '=', $req->input('keyword'))
                    ->orWhere('methodology', '=', $req->input('keyword'))
                    ->orWhere('resources', '=', $req->input('keyword'))
                    ->orWhere('remarks', '=', $req->input('keyword'))
                    ->orWhere('proposal', '=', $req->input('keyword'))
                    ->orWhere('higherremarks', '=', $req->input('keyword'))
                    ->orWhere('higherproposal', '=', $req->input('keyword'))
                    ->orWhere('created_at', '=', $req->input('keyword'))
                    ->orWhere('otarget', '=', $req->input('keyword'))
                    ->orWhere('span', '=', $req->input('keyword'))
                    ->orWhere('status', '=', $req->input('keyword'))

                    ->orWhere('tardate', '=', $req->input('keyword'))
                    ->orWhere('reviewed_at', '=', $req->input('keyword'))
                    ->orWhere('frequency', '=', $req->input('keyword'));
            })
            ->paginate(100)
            ->setpath('');

        $data->appends(array(
            'department' => $req->input('keyword'),
        ));

        $models = DB::table('modenadisable')->First();
        $departments = DB::table('departments')->pluck('departmentname');
        return view('user.objectives.folder', compact('data', 'data1', 'data2', 'data3', 'data4', 'departments', 'mr', 'md'))->with('model', $models)->with('departments', $departments);
    }
    //delete request
    public function delete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            // inserting data to activities table
            $name = DB::table('objectives')->where('Oid', $id)->pluck('objectives');
            $activity = "An objective entry is Removed with Name " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);

            DB::delete('delete from objectives where Oid = :eid', ['eid' => $id]);
            DB::delete('delete from objreport where Oid = :eid', ['eid' => $id]);

            $models = DB::table('modenadisable')->First();
            $departments = DB::table('departments')->pluck('departmentname');
            return redirect('/user/objectives/folders')->with('model', $models)->with('departments', $departments);
        }
    }

    public function ajaxReview(Request $request)
    {
        $ids = $request->input('ids');

        if ($ids) {
            foreach ($ids as $id) {
                Objective::where('Oid', $id)->update([
                    'status' => 'Reviewed',
                ]);
            }
        }

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function ajaxApprove(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            foreach ($ids as $id) {
                Objective::where('Oid', $id)->update([
                    'status' => 'Approved',
                ]);
            }
        }

        return [
            'data' => null,
            'error' => null,
        ];
    }
    public function changeApprover(Request $req){
        $validator = Validator::make($req->all(), [
            'approver' => 'required|string',
        ],[
            'approver.required' => 'The Approver field is required.',
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if ($error == null) {
            $id = $req->input('Oid');
            $approver = $req->input('approver');
            $obj = DB::table('objectives')->where('Oid', $id)->update(['approver' => $approver]);
            if($obj){
                $objective = DB::table('objectives')->where('Oid',$id)->first();
                $mails = EmailNotification::where('module','objectives')
                        ->where('item','Objective Approver Changed')
                        ->where('is_blocked',false)
                        ->first();
                if($mails){
                    $emaildata = array(
                        'department' => DB::table('departments')->where('id',$objective->department)->value('departmentname'),
                        'objectives' => $objective->objectives,
                        'description' => $objective->description,
                        'targetdate' => date('d-m-Y',strtotime($objective->tardate)),
                        'objective_id' => $objective->objective_id,
                        'item' => $mails->item,
                        'email_content' => $mails->email_content,
                        'status' => $objective->status,
                    );
                    //sending mail when Objective approve successfully
                    $app = User::where('id',$approver)->where('is_email_verified',true)->value('email');
                    if($app){
                        Mail::to($app)->send(new ObjectiveApproverChanged($emaildata));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
                    if(count($userdatas1) > 0)
                    {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if(count($userdatas)>0){
                            Mail::to($userdatas)->send(new ObjectiveApproverChanged($emaildata));
                        }
                    }
                }else{
                    Session::flash('errmsg', 'Mail Sending Blocked for Objective Approver Changed');
                }
            }
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
    public function changeReviewer(Request $req){
        $validator = Validator::make($req->all(), [
            'reviewer' => 'required|',
            'reviewer.*' => 'required|string',
        ],[
            'reviewer.required' => 'The Reviewer field is required.',
            'reviewer.*.required' => 'The Reviewer field is required.',
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if ($error == null) {
            $id = $req->input('Oid');
            $rev = $req->input('reviewer');
            $obj = DB::table('objectives')->where('Oid', $id)->update(['reviewers' => $rev]);
            if($obj){
                $objective = DB::table('objectives')->where('Oid',$id)->first();
                $mails = EmailNotification::where('module','objectives')
                        ->where('item','Objective Reviewer Changed')
                        ->where('is_blocked',false)
                        ->first();
                if($mails){
                    $emaildata = array(
                        'department' => DB::table('departments')->where('id',$objective->department)->value('departmentname'),
                        'objectives' => $objective->objectives,
                        'description' => $objective->description,
                        'targetdate' => date('d-m-Y',strtotime($objective->tardate)),
                        'objective_id' => $objective->objective_id,
                        'item' => $mails->item,
                        'email_content' => $mails->email_content,
                        'status' => $objective->status,
                    );
                    //sending mail when Objective approve successfully
                    $reviewers = User::whereIn('id',explode(',',$rev))->pluck('email')->toArray();
                    if(count($reviewers)>0){
                        Mail::to($reviewers)->send(new ObjectiveReviewerChanged($emaildata));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
                    if(count($userdatas1) > 0)
                    {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if(count($userdatas)>0){
                            Mail::to($userdatas)->send(new ObjectiveReviewerChanged($emaildata));
                        }
                    }
                }else{
                    Session::flash('errmsg', 'Mail Sending Blocked for Objective Reviewer Changed');
                }
            }

            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
    public function displayobjectivegroupnameajax(Request $request)
    {
        $obj_category_name = $request->input('obj_category_name');
        $obj_names = ObjectiveCategoryDetails::where('objective_category', $obj_category_name)->pluck('objective_name');
        return response()->json([
                'status'=>200,
                'objnames'=>$obj_names,
            ]);
    }

    public function displayobjectivegroupdetailsajax(Request $request)
    {
        $obj_name = $request->input('obj_name');
        $category_name = $request->input('category_name');
        $obj_desp = ObjectiveCategoryDetails::where('objective_category',$category_name)->where('objective_name',$obj_name)->value('objective_description');
        $obj_target = ObjectiveCategoryDetails::where('objective_category',$category_name)->where('objective_name',$obj_name)->value('objective_target');
        $obj_unit = ObjectiveCategoryDetails::where('objective_category',$category_name)->where('objective_name',$obj_name)->value('objective_unit');
        $obj_method = ObjectiveCategoryDetails::where('objective_category',$category_name)->where('objective_name',$obj_name)->value('objective_methodology');
        $obj_resource = ObjectiveCategoryDetails::where('objective_category',$category_name)->where('objective_name',$obj_name)->value('objective_resources');

        return response()->json([
            'status'=>200,
            'obj_desp'=>$obj_desp,
            'obj_target'=>$obj_target,
            'obj_unit'=>$obj_unit,
            'obj_method'=>$obj_method,
            'obj_resource' => $obj_resource,
        ]);

    }

     /**
     * Display the detail popup when click "View" button
     *
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function show($id): JsonResponse
    {
        $responseData = [];

        try {
            $value = Objective::with([
                'responsiblePersonName', 'responsiblePersonEmail',
                'dep','division','area','approverDetails','creator','approvedBy'
                ])
                ->where('Oid', $id)->first();
            $models = DB::table('modenadisable')->first();
            $responseData['view'] = \view('user.objectives._viewDetailsModal', compact(
                'value', 'models',
            ))->render();
            $responseData['id'] = $id;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['msg'] = $exception->getMessage();
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    public function detail($id)
    {
        $model = DB::table('modenadisable')->First();
        $objective = Objective::with('objectiveTypes', 'dep','division','area','approverDetails','creator','approvedBy')->where('Oid', $id)->first();
        return view('user.objectives.detail', compact('model', 'objective'));
    }

    public function exportPdf($id)
    {
        $clientDetails = ClientDetail::first();
        $objective = Objective::with('objectiveTypes', 'dep','division','area','approverDetails','creator','approvedBy')->where('Oid', $id)->first();
        $pdf = \PDF::loadView('user.objectives.detail_pdf',compact('objective', 'clientDetails'));
        return $pdf->download('detail_' . $objective->objective_id . '.pdf');
    }

    public function exportData(Request $request)
    {
        $format = $request->format;
        $ids = $request->ids;
        $headers = $request->headings;
        $contextData = $request->contextData;

        $contextData = [
            'department' => $contextData['department'],
            'division' => $contextData['division'] ? Division::find($contextData['division'])->name : $contextData['division'],
            'subDivision' => $contextData['subDivision'] ? Area::find($contextData['subDivision'])->name : $contextData['subDivision']
        ];
        return ObjectiveExportHelper::exportData($ids, $headers, $format, $contextData);
    }
}

