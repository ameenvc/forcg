<?php

namespace App\Http\Controllers;

use App;
use App\Categories;
use App\Corrective;
use App\Departments;
use App\IncidentReport;
use App\Investigator;
use App\Issuereviewer;
use App\Mail\AddIncident;
use App\Mail\ApproveIncidentReport;
use App\Mail\CorrectiveAddIssue;
use App\Mail\CorrectiveIssueAwaitingApproval;
use App\Mail\CorrectiveResponseNotification;
use App\Mail\ForInvestigate;
use App\Mail\IncidentApproverChanged;
use App\Mail\IncidentReportApproval;
use App\Mail\IncidentReviewerChanged;
use App\Mail\IncidentTeamMember;
use App\Mail\InvestigationRejected;
use App\Mail\ReviewInvestigationReport;
use App\Mail\IncidentInvestigationReviewerchanged;
use App\Mail\IncidentInvestigationApproverChanged;
use App\Type;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Incident\AddInvestigatorRequest;
use App\IncidentCorrective;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;

class IncidentReportController extends Controller
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
        //below is superadmin related codes
        $incident = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($incident) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'incidentreport' => 1]);
        } else {
            $incident = DB::table('modpageload')->where('id', 1)->First();
            $incident->incidentreport = $incident->incidentreport + 1;
            DB::table('modpageload')->update(['incidentreport' => $incident->incidentreport]);
        }
        //end of superadmin codes

        $incidents = IncidentReport::with([
            'departmentOfIncident',
            'investigator.user',
            'injuredPersonOthDepartment',
            'injuredPersonDepartment',
            'correctives',
            'division',
            'subdiv'
        ])->withCount('correctives')
            ->where('reviewed','!=','Closed')
            ->latest('created_at')->get();
        $reviewers = DB::table('incident_reviewer')->get();
        $departments = DB::table('departments')->get();
        $models = DB::table('modenadisable')->First();
        $investigators = DB::table('investigators')->get();
        $corrective = DB::table('corrective')->get();
        $mr = DB::table('representatives')->pluck('email');
         return view('user.incident.incidentregister')
            ->with('mr', $mr)
            ->with('reviewers', $reviewers)->with('model', $models)
            ->with('incidents', $incidents)->with('departments', $departments)
            ->with('corr', $corrective)->with('investigators', $investigators);

    }

    public function addreport()
    {
        $today = Carbon::now()->toDateTimeString();
        $today = Carbon::parse($today);
        $year = $today->format('Y');
        $year = substr($year, 2);
        $irids = DB::table('incident_reports')->pluck('report_no');
        $incidents = DB::table('incident_type')->get();
        $rds = [];
        $rs = [];
        //function for changing Incident report id each year
        if (count($irids) > 0) {
            foreach ($irids as $irid) {
                if (substr($irid, -2, strrpos($irid, "/")) == $year) {
                    $rds[] = substr($irid, 0, strrpos($irid, "/"));
                }
            }
            if (count($rds) > 0) {
                foreach ($rds as $rd) {
                    $rs[] = substr($rd, 3, strlen($rd));
                }
                $iid = (int) $rs[count($rs) - 1] + 1;
                $iid = "IR0" . $iid . "/" . $year;
            } else {
                $iid = "IR01/" . $year;
            }
        } else {
            $iid = "IR01/" . $year;
        }
        $users = DB::table('users')->get();
        $empdetails = DB::table('employee_details')->get();
        $models = DB::table('modenadisable')->First();
        $approvers = DB::table('incident_approvers')->get();
        $reviewers = DB::table('incident_reviewer')->get();
        $depts = DB::table('departments')->pluck('departmentname');
        //$depname = DB::table('departments')->select('id','departmentname')->orderBy('id', 'ASC')->get();
        $depname = DB::table('departments')->select('id', 'departmentname')->get();
        $departments = DB::table('departments')->get();
        return view('user.incident.addreport')
            ->with('approvers', $approvers)->with('reviewers', $reviewers)
            ->with('incidents', $incidents)->with('model', $models)
            ->with('departments', $departments)->with('users', $users)->with('depnam', $depname)
            ->with('empdetails', $empdetails)->with('irid', $iid)->with('depts', $depts);
    }

    public function addreportconfirm(Request $request)
    {
        $data = [];
        if ($request->hasfile('filenames')) {
            $dir = str_replace('/', '_', $request->rid);
            if (!File::exists(public_path('/storage/IncidentFiles/'))) {
                File::makeDirectory(public_path() . '/storage/IncidentFiles/', 0755, true);
            }

            if (!File::exists(public_path('storage/IncidentFiles/' . $dir . '/'))) {
                File::makeDirectory(public_path() . '/storage/IncidentFiles/' . $dir . '/', 0755, true);
            }

            foreach ($request->file('filenames') as $file) {
                $fnamewithext = $file->getClientOriginalName();
                $fname = pathinfo($fnamewithext, PATHINFO_FILENAME);
                $name = 'Reporterfile' . '_' . $fname . '.' . $file->extension();
                $file->move(public_path() . '/storage/IncidentFiles/' . $dir . '/', $name);
                $data[] = $name;
            }
        }
        if (count($data) <= 0) {
            $data = [null];
        }

        if ($request->local == "yes") {
            $local_reg = $request->regulation;
        } else {
            $local_reg = null;
        }

        $approver = DB::table('incident_approvers')->where('email', $request->approver)->First();
        $reviewer = DB::table('incident_reviewer')->where('email', $request->reviewer)->First();
        $date = Carbon::parse($request->input('incidentdate'))->format('d/m/Y');
        //   $empid=DB::table('users')->where('email',$request->person)->First();
        if ($request->person_injured == 'yes') {
            if ($request->injper == "other") {
                $injured_person_name = 'other';
                $empid = 'nodata';
                $injpost = null;
                $injdept = null;
            } else {
                $injured_person_name = DB::table('employee_details')->where('emp_number', $request->injper)->value('emp_name');
                $empid = $request->injempid;
                $injpost = $request->injposition;
                $injdepmt = Departments::where('departmentname', $request->injdept)->value('id');
                $injdept = $injdepmt ? $injdepmt : '-';
            }
        } else {
            $injured_person_name = '-';
            $empid = 'nodata';
            $injpost = null;
            $injdept = null;
        }

        IncidentReport::create([
            'addedby' => $request->addedby,
            'addeddate' => $request->addeddate,
            'department' => $request->department,
            'division_id' => $request->division,
            'area_id' => $request->sub_div,
            'area' => $request->area,
            'report_no' => $request->rid,
            'incident_type' => implode(",", $request->type),
            'any_person_injured' => $request->person_injured,
            'injuredperson' => $injured_person_name,
            'empid' => $empid,
            'injured_person_position' => $injpost,
            'injured_person_dept' => $injdept,
            'injured_person_oth_name' => $request->injempname_oth,
            'injured_person_oth_empid' => $request->injempid_oth,
            'injured_person_oth_position' => $request->injposition_oth,
            'injured_person_oth_dept' => $request->injdept_oth,
            'hospital' => $request->hospital,
            'incidentplace' => $request->place,
            'incidentdate' => $date,
            'emplyoer' => $request->employer,
            'trade_or_occupation' => $request->trade,
            'incidentdesc' => $request->desc,
            'actions' => $request->action,
            'local_regulation' => $local_reg,
            'time' => $request->time,
            'attachmentby_reporter' => implode(',', $data),
            'reviewed' => 'Under review',
            'adder_email' => Auth::user()->email,
            'approver_name' => $approver->name,
            'approver_email' => $approver->email,
            'reviewer_name' => $reviewer->name,
            'reviewer_email' => $reviewer->email,
        ]);
        //  inserting data to activities table
        $activity = "An Incident report is added with report number " . $request->rid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
        DB::table('viewactivity')->insert($datas);

        $data1 = array(
            'line1' => Lang::get('1'),
            'line2' => Lang::get('Dear ' . $request->addedby),
            'line3' => Lang::get('Incident Report with Report  Number ' . $request->rid . ' is Added Successfully.'),
        );
        Mail::to(Auth::user()->email)->send(new AddIncident($data1));
        $data2 = array(
            'line1' => Lang::get('2'),
            'line2' => Lang::get('Dear ' . $reviewer->name),
            'line3' => Lang::get('Incident Report ' . $request->rid . ' is added for review. Please Start your review process'),
        );
        Mail::to($reviewer->email)->send(new AddIncident($data2));

        $models = DB::table('modenadisable')->first();

        return redirect('user/incidents/register')
            ->with('model', $models)
            ->with('msg', 'Incident report added successfully');

    }

    public function reviewReport($id)
    {
        $users = DB::table('users')->get();
        $models = DB::table('modenadisable')->First();
        $depts = DB::table('departments')->get();
        $depname = DB::table('departments')->select('id', 'departmentname')->get();
        $potlss = DB::table('potential_loss')->get();
        $pfls = $potlss;
        $incidenttype = DB::table('incident_type')->get();
        $inctype = $incidenttype;
        $incidentreport = DB::table('incident_reports')->where('id', $id)->First();
        $injured_person_deptname = Departments::where('id', $incidentreport->injured_person_dept)->value('departmentname');
        $injured_dept = $injured_person_deptname ? $injured_person_deptname : '-';
        $potforloss = explode(',', $incidentreport->potloss);
        $incidents = explode(',', $incidentreport->incident_type);
        $empdetails = DB::table('employee_details')->get();
        $approvers = DB::table('incident_approvers')->get();
        $reviewers = DB::table('incident_reviewer')->get();
        function remove_elm($arr, $key, $val, $within = false)
        {
            foreach ($arr as $i => $array) {
                if ($within && stripos($array[$key], $val) !== false && (gettype($val) === gettype($array[$key]))) {
                    unset($arr[$i]);
                } elseif ($array[$key] === $val) {
                    unset($arr[$i]);
                }
            }

            return array_values($arr);
        }
        for ($i = 0; $i < count($incidents); $i++) {
            $incidenttype = remove_elm(json_decode($incidenttype, true), "incident_type", $incidents[$i]);
            $incidenttype = json_encode($incidenttype);
        }
        if (count($potforloss) > 0) {
            if ($potforloss[0] != "") {
                for ($i = 0; $i < count($potforloss); $i++) {
                    $pfls = remove_elm(json_decode($pfls, true), 'losses', $potforloss[$i]);
                    $pfls = json_encode($pfls, true);
                }
            } else {
                $potforloss = [];
            }

        } else {
            $potforloss = [];
        }

        return view('user.incident.review')
            ->with('inctype', $inctype)->with('incidenttype', json_decode($incidenttype, true))
            ->with('depts', $depts)->with('incident', $incidentreport)
            ->with('depnam', $depname)
            ->with('model', $models)->with('injured_person_deptname', $injured_dept)
            ->with('potlss', $potlss)
            ->with('pfl', json_decode($pfls, true))
            ->with('potforloss', $potforloss)
            ->with('em', $incidentreport->eval)
            ->with('pre', $incidentreport->pubrec)
            ->with('po', $incidentreport->probablity)
            ->with('sf', $incidentreport->finloss)
            ->with('ec', $incidentreport->efcom)
            ->with('sh', $incidentreport->humtur)
            ->with('spl', $incidentreport->sevpotloss)
            ->with('probre', $incidentreport->porbocc)
            ->with('empdetails', $empdetails)
            ->with('approvers', $approvers)
            ->with('reviewers', $reviewers)
            ->with('users', $users);
    }

    public function reviewedReport(Request $request)
    {
        $data = [];
        $approver = DB::table('incident_approvers')->where('email', $request->approver)->First();
        $reviewer = DB::table('incident_reviewer')->where('email', $request->reviewer)->First();
        //saving attachments if any
        if ($request->hasfile('filenames')) {
            $dir = str_replace('/', '_', $request->rid);
            if (!File::exists(public_path('/storage/IncidentFiles/'))) {
                File::makeDirectory(public_path() . '/storage/IncidentFiles/', 0755, true);
            }

            if (!File::exists(public_path('storage/IncidentFiles/' . $dir . '/'))) {
                File::makeDirectory(public_path() . '/storage/IncidentFiles/' . $dir . '/', 0755, true);
            }

            foreach ($request->file('filenames') as $file) {
                $fnamewithext = $file->getClientOriginalName();
                $fname = pathinfo($fnamewithext, PATHINFO_FILENAME);
                $name = 'Reviewerfile' . '_' . $fname . '.' . $file->extension();
                $file->move(public_path() . '/storage/IncidentFiles/' . $dir . '/', $name);
                $data[] = $name;
            }
        }
        if (count($data) <= 0) {
            $data = [null];
        }

        //calculating severiy/effect
        $severity[] = substr($request->eval, -1, strrpos($request->eval, "/"));
        $severity[] = substr($request->pubrec, -1, strrpos($request->pubrec, "/"));
        $severity[] = substr($request->prob, -1, strrpos($request->prob, "/"));
        $severity[] = substr($request->floss, -1, strrpos($request->floss, "/"));
        $severity[] = substr($request->efcom, -1, strrpos($request->efcom, "/"));
        $severity[] = substr($request->humtu, -1, strrpos($request->humtu, "/"));
        $effect = (int) $severity[0] * (int) $severity[2] * (int) $severity[4] * (int) $severity[1] * (int) $severity[3] + (int) $severity[5];

        $rid = $request->rid;

        if ($request->person_injured == 'yes') {
            if ($request->injempname_oth == null && $request->injempname_oth2 == null) {
                //$a = "othnull and oth2null";
                $injempname = DB::table('employee_details')->where('emp_number', $request->person)->value('emp_name');
                $injempid = $request->empid;
                $injpost = $request->injposition;
                $injdeptid = Departments::where('departmentname', $request->injdept)->value('id');
                $injdepts = $injdeptid ? $injdeptid : '-';
                $injempnameoth = null;
                $injempidoth = null;
                $injpostoth = null;
                $injdeptsoth = null;
            } elseif ($request->injempname_oth2 != null) {
                //$a = "oth2notnull";
                $injempnameoth = $request->injempname_oth2;
                $injempidoth = $request->injempid_oth2;
                $injpostoth = $request->injposition_oth2;
                $injdeptsoth = $request->injdept_oth2;
                $injempname = 'other';
                $injempid = 'nodata';
                $injpost = null;
                $injdepts = null;
            } else {
                //$a = "othnotnull";
                $injempnameoth = $request->injempname_oth;
                $injempidoth = $request->injempid_oth;
                $injpostoth = $request->injposition_oth;
                $injdeptsoth = $request->injdept_oth;
                $injempname = 'other';
                $injempid = 'nodata';
                $injpost = null;
                $injdepts = null;
            }
        } else {
            $injempname = '-';
            $injempid = 'nodata';
            $injpost = null;
            $injdepts = null;
            $injempnameoth = null;
            $injempidoth = null;
            $injpostoth = null;
            $injdeptsoth = null;

        }

        $date = Carbon::parse($request->input('incidentdate'))->format('d/m/Y');
        $today = Carbon::now()->toDateTimeString();
        $today = Carbon::parse($today);
        $investigator = DB::table('investigators')->where('report_no', $rid)->get();
        if ($request->apprej == "yes") {
            //$status = "Approved";
            $status = "Reviewed";
            if ($request->investigation == "no") {
                DB::table('incident_reports')->where('report_no', $rid)->update([
                    'reviewed' => $status, 'justification' => $request->justification, 'reviewed_by' => Auth::user()->email, 'reviewed_at' => $today->format('d/m/Y'), 'pubrec' => substr($request->pubrec, 0, strrpos($request->pubrec, "/")),
                    'eval' => substr($request->eval, 0, strrpos($request->eval, "/")),
                    'probablity' => substr($request->prob, 0, strrpos($request->prob, "/")),
                    'finloss' => substr($request->floss, 0, strrpos($request->floss, "/")),
                    'efcom' => substr($request->efcom, 0, strrpos($request->efcom, "/")),
                    'humtur' => substr($request->humtu, 0, strrpos($request->humtu, "/")),
                    'potloss' => implode(',', $request->potloss),
                    'sevpotloss' => $request->spl,
                    'porbocc' => $request->porbocc,
                    //updation of the values by reviewer which is added by reporter
                    'department' => $request->department,
                    'area' => $request->area,
                    'incident_type' => implode(",", $request->type),
                    'any_person_injured' => $request->person_injured,
                    'injuredperson' => $injempname,
                    'empid' => $injempid,
                    'injured_person_position' => $injpost,
                    'injured_person_dept' => $injdepts,
                    'injured_person_oth_name' => $injempnameoth,
                    'injured_person_oth_empid' => $injempidoth,
                    'injured_person_oth_position' => $injpostoth,
                    'injured_person_oth_dept' => $injdeptsoth,
                    'hospital' => $request->hospital,
                    'incidentplace' => $request->place,
                    'incidentdate' => $date,
                    'emplyoer' => $request->employer,
                    'trade_or_occupation' => $request->trade,
                    'incidentdesc' => $request->desc,
                    'actions' => $request->action,
                    'local_regulation' => $request->localreg,
                    'severity' => $effect,
                    'time' => $request->time,
                    'further_investigate' => $request->investigation,
                    'division_id' => $request->division ?? 0,
                    'area_id' => $request->subdiv ?? 0,
                    'investigation_status' => "Not Needed",
                    'approver_name' => $approver->name,
                    'approver_email' => $approver->email,
                    'reviewer_name' => $reviewer->name,
                    'reviewer_email' => $reviewer->email,
                    'attachmentby_reviewer' => implode(',', $data),
                ]);
                if (count($investigator) > 0) {
                    DB::table('investigators')->where('report_no', $rid)->delete();
                    DB::table('incident_witness')->where('report_no', $rid)->delete();
                }

                //  inserting data to activities table
                $activity = "An Incident with report number " . $rid . " is reviewed and not claimed for further investigation.";
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
                DB::table('viewactivity')->insert($datas);
            } else {

                $tleader = $request->leader;
                $members = implode(',', $request->members);
                $tmember[] = $request->members;
                $tmembernames = [];
                $tleadername = "";
                $users = DB::table('users')->get();
                for ($i = 0; $i < count($request->members); $i++) {
                    foreach ($users as $user) {
                        if ($user->email == $request->members[$i]) {
                            $tmembernames[] = $user->name;
                        }

                    }
                }
                foreach ($users as $user) {
                    if ($user->email == $request->leader) {
                        $tleadername = $user->name;
                    }

                }

                $tdate = Carbon::parse($request->input('targetdate'))->format('d/m/Y');
                if (count($investigator) > 0) {
                    DB::table('investigators')->where('report_no', $rid)->update(['tleader' => $tleader, 'tmembers' => $members, 'tmembersnames' => implode(',', $tmembernames), 'tleadername' => $tleadername]);
                } else {
                    DB::table('investigators')->insert(['tleader' => $tleader, 'tmembers' => $members, 'targetdate' => $tdate, 'report_no' => $rid, 'tmembersnames' => implode(',', $tmembernames), 'tleadername' => $tleadername]);
                }

                DB::table('incident_reports')->where('report_no', $rid)->update([
                    'reviewed' => $status, 'reviewed_by' => Auth::user()->email, 'reviewed_at' => $today->format('d/m/Y'),
                    'justification' => null,
                    'pubrec' => substr($request->pubrec, 0, strrpos($request->pubrec, "/")),
                    'eval' => substr($request->eval, 0, strrpos($request->eval, "/")),
                    'probablity' => substr($request->prob, 0, strrpos($request->prob, "/")),
                    'finloss' => substr($request->floss, 0, strrpos($request->floss, "/")),
                    'efcom' => substr($request->efcom, 0, strrpos($request->efcom, "/")),
                    'humtur' => substr($request->humtu, 0, strrpos($request->humtu, "/")),
                    'potloss' => implode(',', $request->potloss),
                    'sevpotloss' => $request->spl,
                    'porbocc' => $request->porbocc,
                    //updation of the values by reviewer which is added by reporter
                    'department' => $request->department,
                    'division_id' => $request->division ?? 0,
                    'area_id' => $request->subdiv ?? 0,
                    'area' => $request->area,
                    'incident_type' => implode(",", $request->type),
                    'any_person_injured' => $request->person_injured,
                    'injuredperson' => $injempname,
                    'empid' => $injempid,
                    'injured_person_position' => $injpost,
                    'injured_person_dept' => $injdepts,
                    'injured_person_oth_name' => $injempnameoth,
                    'injured_person_oth_empid' => $injempidoth,
                    'injured_person_oth_position' => $injpostoth,
                    'injured_person_oth_dept' => $injdeptsoth,
                    'hospital' => $request->hospital,
                    'incidentplace' => $request->place,
                    'incidentdate' => $date,
                    'emplyoer' => $request->employer,
                    'trade_or_occupation' => $request->trade,
                    'incidentdesc' => $request->desc,
                    'actions' => $request->action,
                    'local_regulation' => $request->localreg,
                    'severity' => $effect,
                    'time' => $request->time,
                    'further_investigate' => $request->investigation,
                    'attachmentby_reviewer' => implode(',', $data),
                    'approver_name' => $approver->name,
                    'approver_email' => $approver->email,
                    'reviewer_name' => $reviewer->name,
                    'reviewer_email' => $reviewer->email,
                    'investigation_status' => "Under Investigation",
                ]);

                //  inserting data to activities table
                $activity = "An Incident with report number " . $rid . " is reviewed and claimed for further investigation.";
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
                DB::table('viewactivity')->insert($datas);

                // mail all the team members and team leader
                $data = array(
                    'line1' => Lang::get('1'),
                    'line2' => Lang::get('Dear '),
                    'line3' => Lang::get('Your are receiving this mail because your appointed as Team Leader to investigate the Incident Report ' . $rid . ' with Target Date ' . $date->format('d-m-Y') . '. So please start your Investigation process.'),

                );
                Mail::to($tleader)->send(new ForInvestigate($data, $tleader));
                //mail to team members
                $datateam = array('inc_id' => $rid, 'tardate' => $tdate);
                foreach ($tmember as $tm) {
                    Mail::to($tm)->send(new IncidentTeamMember($datateam));
                }
            }
        } else {
            $status = "Need Improvement";
            DB::table('incident_reports')->where('report_no', $rid)->update([
                'reviewed' => $status, 'reviewed_by' => Auth::user()->email, 'reviewed_at' => $today->format('d/m/Y'),
                'justification' => null,
                'pubrec' => substr($request->pubrec, 0, strrpos($request->pubrec, "/")),
                'eval' => substr($request->eval, 0, strrpos($request->eval, "/")),
                'probablity' => substr($request->prob, 0, strrpos($request->prob, "/")),
                'finloss' => substr($request->floss, 0, strrpos($request->floss, "/")),
                'efcom' => substr($request->efcom, 0, strrpos($request->efcom, "/")),
                'humtur' => substr($request->humtu, 0, strrpos($request->humtu, "/")),
                'potloss' => implode(',', $request->potloss),
                'sevpotloss' => $request->spl,
                'porbocc' => $request->porbocc,
                //updation of the values by reviewer which is added by reporter
                'department' => $request->department,
                'division_id' => $request->division ?? 0,
                'area_id' => $request->subdiv ?? 0,
                'area' => $request->area,
                'incident_type' => implode(",", $request->type),
                'any_person_injured' => $request->person_injured,
                'injuredperson' => $injempname,
                'empid' => $injempid,
                'injured_person_position' => $injpost,
                'injured_person_dept' => $injdepts,
                'injured_person_oth_name' => $injempnameoth,
                'injured_person_oth_empid' => $injempidoth,
                'injured_person_oth_position' => $injpostoth,
                'injured_person_oth_dept' => $injdeptsoth,
                'hospital' => $request->hospital,
                'incidentplace' => $request->place,
                'incidentdate' => $date,
                'emplyoer' => $request->employer,
                'trade_or_occupation' => $request->trade,
                'incidentdesc' => $request->desc,
                'actions' => $request->action,
                'local_regulation' => $request->localreg,
                'severity' => $effect,
                'time' => $request->time,
                'attachmentby_reviewer' => implode(',', $data),
                'investigation_status' => null,
                'improve_justification' => $request->improvement,
            ]);
        }

        $models = DB::table('modenadisable')->First();

        return redirect('user/incidents/register')
            ->with('model', $models)
            ->with('msg', 'Incident Report reviewed and updated successfully');
    }

    public function changeInvestigators($id)
    {
        $incident_report = DB::table('incident_reports')->where('id', $id)->First();
        $investigators = DB::table('investigators')->where('report_no', $incident_report->report_no)->First();
        $models = DB::table('modenadisable')->First();
        $users = DB::table('users')->get();
        $usrs = DB::table('users')->get();
        $tmembers = explode(',', $investigators->tmembers);
        function remove_elm($arr, $key, $val, $within = false)
        {
            foreach ($arr as $i => $array) {
                if ($within && stripos($array[$key], $val) !== false && (gettype($val) === gettype($array[$key]))) {
                    unset($arr[$i]);
                } elseif ($array[$key] === $val) {
                    unset($arr[$i]);
                }
            }

            return array_values($arr);
        }
        for ($i = 0; $i < count($tmembers); $i++) {
            $users = remove_elm(json_decode($users, true), "email", $tmembers[$i]);
            $users = json_encode($users);
        }
        return view('user.incident.changeInvestigator')->with('rid', $incident_report->report_no)->with('usrs', $usrs)->with('model', $models)->with('investigators', $investigators)->with('users', json_decode($users, true));
    }

    public function changedInvestigators(Request $request)
    {
        $tmembernames = [];
        $tleadername = "";
        $users = DB::table('users')->get();
        $tmemb[] = $request->tmembers;
        for ($i = 0; $i < count($request->tmembers); $i++) {
            foreach ($users as $user) {
                if ($user->email == $request->tmembers[$i]) {
                    $tmembernames[] = $user->name;
                }

            }
        }
        foreach ($users as $user) {
            if ($user->email == $request->tleader) {
                $tleadername = $user->name;
            }

        }
        DB::table('investigators')->where('report_no', $request->rid)->update(['tleader' => $request->tleader, 'tmembers' => implode(',', $request->tmembers), 'tleadername' => $tleadername, 'tmembersnames' => implode(',', $tmembernames)]);
        $inves = DB::table('investigators')->where('report_no', $request->rid)->First();
        // // mail all the team members and team leader
        $data = array(
            'line1' => Lang::get('1'),
            'line2' => Lang::get('Dear ' . $tleadername),
            'line3' => Lang::get('Your are receiving this mail because your appointed as Team Leader to investigate the Incident Report ' . $request->rid . ' with Target Date ' . $inves->targetdate . '. So please start your Investigation process.'),
        );
        Mail::to($request->tleader)->send(new ForInvestigate($data));

        //mail to team members
        //  dd($teammember);
        $datateam = array('inc_id' => $request->rid, 'tardate' => $inves->targetdate);
        foreach ($tmemb as $tm) {
            Mail::to($tm)->send(new IncidentTeamMember($datateam));
        }

        // $data= array(
        //     //server info admin email,
        //     'line1'=>Lang::getFromJson('2'),
        //     'line2'=>Lang::getFromJson('Dear '),
        //     'line3'=>Lang::getFromJson('Your are receiving this mail because your appointed as one of the team member to investigate the Incident with report number '.$request->rid),
        // );
        // for($i=0;$i<count($request->tmembers);$i++){
        // Mail::to($request->tmembers[$i])->send(new ForInvestigate($data,$request->tmembers[$i]));
        // }

        //  inserting data to activities table
        $activity = 'Investigators for incident report ' . $request->rid . ' is changed';
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
        DB::table('viewactivity')->insert($datas);

        Session::flash('msg', "Investigation team changed successfully");
        return redirect()->back();
    }
    public function updateIncidentReport($id)
    {
        $prevent = DB::table('incident_prevention')->get();
        $protection = DB::table('protection_kit')->get();
        $incident_report = IncidentReport::where('id', $id)->First();
        $reportedby_deptname = Departments::where('id', $incident_report->department)->value('departmentname');
        if ($incident_report->injuredperson == 'other') {
            $injured_deptname = Departments::where('id', $incident_report->injured_person_oth_dept)->value('departmentname');
        } else {
            $injured_deptname = Departments::where('id', $incident_report->injured_person_dept)->value('departmentname');
        }
        $models = DB::table('modenadisable')->First();
        $users = DB::table('users')->get();
        $investigator = DB::table('investigators')->where('report_no', $incident_report->report_no)->First();
        return view('user.incident.updatereport')
            ->with('investigator', $investigator)->with('protection', $protection)
            ->with('prevent', $prevent)->with('users', $users)->with('reportedby_depname', $reportedby_deptname)
            ->with('model', $models)->with('incident', $incident_report)->with('injured_deptname', $injured_deptname);
    }
    public function updatedIncidentReport(Request $request)
    {
        if ($request->riskassessment == "yes") {
            $assessno = $request->riskassess;
        } else {
            $assessno = null;
        }

        if ($request->toolbox == "yes") {
            $tooldate = Carbon::parse($request->tooldate)->format('d/m/Y');
        } else {
            $tooldate = null;
        }

        if ($request->training == "yes") {
            $trandate = Carbon::parse($request->traindate)->format('d/m/Y');
        } else {
            $trandate = null;
        }

        if ($request->unsafework == 'yes') {
            $workunsafe = $request->workunsafe;
        } else {
            $workunsafe = null;
        }

        if ($request->unsafeact == 'yes') {
            $actunsafe = $request->actunsafe;
        } else {
            $actunsafe = null;
        }

        $data = [];
        //saving attachments if any
        if ($request->hasfile('filenames')) {
            $dir = str_replace('/', '_', $request->rid);
            if (!File::exists(public_path('/storage/IncidentFiles/'))) {
                File::makeDirectory(public_path() . '/storage/IncidentFiles/', 0755, true);
            }

            if (!File::exists(public_path('storage/IncidentFiles/' . $dir . '/'))) {
                File::makeDirectory(public_path() . '/storage/IncidentFiles/' . $dir . '/', 0755, true);
            }

            foreach ($request->file('filenames') as $file) {
                $fnamewithext = $file->getClientOriginalName();
                $fname = pathinfo($fnamewithext, PATHINFO_FILENAME);
                $name = 'Investigatorfile' . '_' . $fname . '.' . $file->extension();
                $file->move(public_path() . '/storage/IncidentFiles/' . $dir . '/', $name);
                $data[] = $name;
            }
        }
        if (count($data) <= 0) {
            $data = [null];
        }

        $targetndate = Carbon::parse($request->currective_targdate)->format('d/m/Y');
        // Check if 'otherReason' is set and not empty
        if ($request->has('otherReason') && !empty($request->otherReason)) {
            $prevent_other = $request->otherReason;
        }else{
            $prevent_other = null;
        }
        DB::table('incident_reports')->where('report_no', $request->rid)->update([
            'riskassessment' => $request->riskassessment, 'riskassess' => $assessno,
            'toolbox' => $request->toolbox,
            'tooldate' => $tooldate,
            'training' => $request->training,
            'traindate' => $trandate,
            'autherised' => $request->autherised,
            'activity' => $request->activity,
            'investigatordisc' => $request->investigatordisc,
            'bodypart' => $request->bodypart,
            'unsafework' => $request->unsafework,
            'unsafeact' => $request->unsafeact,
            'ppkit' => implode(',', $request->ppkit),
            'prevent' => implode(',',$request->prevent),
            'prevent_other' => $prevent_other,
            'rootcause' => $request->rootcause, 'corrective_act' => $request->corrective_act, 'currective_targdate' => $targetndate, 'responsiblity' => $request->responsiblity, 'actunsafe' => $actunsafe, 'workunsafe' => $workunsafe, 'reviewed' => 'Under Secondary Review', 'attachmentby_investigator' => implode(',', $data), 'investigation_status' => "Investigation Completed",
        ]);

        //  inserting data to activities table
        $activity = 'Investigation for incident report ' . $request->rid . ' is updated by team leader';
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
        DB::table('viewactivity')->insert($datas);

        /*if (count($request->doc) > 0) {
        for ($i = 0; $i < count($request->doc); $i++) {
        DB::table('incident_witness')->insert(['report_no' => $request->rid, 'name' => $request->doc[$i]['name'], 'phone_or_address' => $request->doc[$i]['address'], 'statement' => $request->doc[$i]['statement']]);
        }
        }*/
        $incident_r = DB::table('incident_reports')->where('report_no', $request->rid)->First();
        //send message to review incident after investigation report

        $data = array(
            'line1' => Lang::get('Dear ' . $incident_r->reviewer_name),
            'line2' => Lang::get('Investigation Process for Incident Report ' . $request->rid . ' is completed and added for secondary review. So please start your Review process.'),
        );
        Mail::to($incident_r->reviewer_email)->send(new ReviewInvestigationReport($data));

        // Session::flash('msg', 'Incident Report Updated Successfully');
        // return redirect()->back();
        $models = DB::table('modenadisable')->First();
        return redirect('user/incidents/register')
            ->with('model', $models)
            ->with('msg', 'Investigation report added successfully for ' . $request->rid);
    }
    public function filter(Request $request)
    {
        $incidents = DB::table('incident_reports')->where('department', $request->department)->paginate(100);
        $reviewers = DB::table('incident_reviewer')->get();
        $users = DB::table('users')->get();
        $departments = DB::table('departments')->get();
        $models = DB::table('modenadisable')->First();
        $investigators = DB::table('investigators')->get();
        return view('user.incident.incidentregister')->with('departments', $departments)->with('reviewers', $reviewers)->with('model', $models)->with('incidents', $incidents)->with('users', $users)->with('investigators', $investigators);
    }
    public function deleteIncidentReport()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //  inserting data to activities table
            $activity = 'Incident report ' . $id . ' is deleted';
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
            DB::table('viewactivity')->insert($datas);

            DB::table('incident_reports')->where('report_no', $id)->delete();
            DB::table('investigators')->where('report_no', $id)->delete();
            DB::table('incident_witness')->where('report_no', $id)->delete();
            $dir = str_replace('/', '_', $id);
            if (File::exists(public_path('storage/IncidentFiles/' . $dir . '/'))) {
                File::deleteDirectory(public_path('storage/IncidentFiles/' . $dir . '/'));
            }

        }
    }

    //updating report 2nd time by the investigator
    public function updateSecondIncidentReport($id)
    {
        $incident_report = IncidentReport::where('id', $id)->First();
        $reportedby_deptname = Departments::where('id', $incident_report->department)->value('departmentname');
        if ($incident_report->injuredperson == 'other') {
            $injured_deptname = Departments::where('id', $incident_report->injured_person_oth_dept)->value('departmentname');
        } else {
            $injured_deptname = Departments::where('id', $incident_report->injured_person_dept)->value('departmentname');
        }
        function remove_elm($arr, $key, $val, $within = false)
        {
            foreach ($arr as $i => $array) {
                if ($within && stripos($array[$key], $val) !== false && (gettype($val) === gettype($array[$key]))) {
                    unset($arr[$i]);
                } elseif ($array[$key] === $val) {
                    unset($arr[$i]);
                }
            }

            return array_values($arr);
        }
        $ppkits = DB::table('protection_kit')->get();
        $usedppkits = explode(',', $incident_report->ppkit);
        for ($i = 0; $i < count($usedppkits); $i++) {
            $ppkits = remove_elm(json_decode($ppkits, true), "kit", $usedppkits[$i]);
            $ppkits = json_encode($ppkits);
        }
        $prevmethods = DB::table('incident_prevention')->get();
        $usedpremethods = explode(',', $incident_report->prevent);
        for ($i = 0; $i < count($usedpremethods); $i++) {
            $prevmethods = remove_elm(json_decode($prevmethods, true), "incident_prevent", $usedpremethods[$i]);
            $prevmethods = json_encode($prevmethods);
        }

        $prevent = DB::table('incident_prevention')->get();
        $protection = DB::table('protection_kit')->get();
        $models = DB::table('modenadisable')->First();
        $users = DB::table('users')->get();
        $witnesses = DB::table('incident_witness')->where('report_no', $incident_report->report_no)->get();
        $investigator = DB::table('investigators')->where('report_no', $incident_report->report_no)->First();
        return view('user.incident.updatesecondreport')
            ->with('witnesses', $witnesses)
            ->with('usedpremethods', $usedpremethods)
            ->with('usedppkits', $usedppkits)
            ->with('ppkits', json_decode($ppkits, true))
            ->with('prevmethods', json_decode($prevmethods, true))
            ->with('investigator', $investigator)
            ->with('protection', $protection)
            ->with('prevent', $prevent)
            ->with('users', $users)
            ->with('model', $models)
            ->with('reportedby_depname', $reportedby_deptname)
            ->with('injured_deptname', $injured_deptname)
            ->with('incident', $incident_report);
    }

    //update second time investigation report
    public function updatedSecondIncidentReport(Request $request)
    {
        $data = [];
        if ($request->hasfile('filenames')) {
            $dir = str_replace('/', '_', $request->rid);
            if (!File::exists(public_path('/storage/IncidentFiles/'))) {
                File::makeDirectory(public_path() . '/storage/IncidentFiles/', 0755, true);
            }

            if (!File::exists(public_path('storage/IncidentFiles/' . $dir . '/'))) {
                File::makeDirectory(public_path() . '/storage/IncidentFiles/' . $dir . '/', 0755, true);
            }

            foreach ($request->file('filenames') as $file) {
                $fnamewithext = $file->getClientOriginalName();
                $fname = pathinfo($fnamewithext, PATHINFO_FILENAME);
                $name = 'Investigatorfile' . '_' . $fname . '.' . $file->extension();
                $file->move(public_path() . '/storage/IncidentFiles/' . $dir . '/', $name);
                $data[] = $name;
            }
        }
        $attachmentby_investigator = DB::table('incident_reports')->where('report_no', $request->rid)->First();

        $dta = $attachmentby_investigator->attachmentby_investigator . ',' . implode($data);
        ////////
        $report_no = $request->rid;
        if ($request->riskassessment == 'yes') {
            $assessno = $request->riskassess;
        } else {
            $assessno = null;
        }

        if ($request->toolbox == 'yes') {

            $tooldate = $traindate = Carbon::parse($request->tooldate)->format('d/m/Y');
        } else {
            $tooldate = null;
        }

        if ($request->training == 'yes') {
            $traindate = Carbon::parse($request->traindate)->format('d/m/Y');
        } else {
            $traindate = null;
        }

        if ($request->unsafework == 'yes') {
            $workunsafe = $request->workunsafe;
        } else {
            $workunsafe = null;
        }

        if ($request->unsafeact == 'yes') {
            $actunsafe = $request->actunsafe;
        } else {
            $actunsafe = null;
        }

/*
$newvitness = count($request->doc) - $request->existinig_witness;
$existinig_witness = count($request->doc) - 1;
$new = [];
// below code will calculate existing witness and try to avoid reinserting same data to witness table and it will add value from end of doc array till the end of existing witness
for ($i = 0; $i < $newvitness; $i++) {
if ($request->doc[$existinig_witness]['name'] != null && $request->doc[$existinig_witness]['address'] != null && $request->doc[$existinig_witness]['statement'] != null)
DB::table('incident_witness')
->insert(['report_no' => $report_no, 'name' => $request->doc[$existinig_witness]['name'], 'phone_or_address' => $request->doc[$existinig_witness]['address'], 'statement' => $request->doc[$existinig_witness]['statement']]);
$existinig_witness -= 1;
}*/

        $trgindate = Carbon::parse($request->currective_targdate)->format('d/m/Y');
        // Check if 'otherReason' is set and not empty
        if ($request->has('otherReason') && !empty($request->otherReason)) {
            $prevent_other = $request->otherReason;
        }else{
            $prevent_other = null;
        }
        DB::table('incident_reports')->where('report_no', $request->rid)->update([
            'riskassessment' => $request->riskassessment, 'riskassess' => $assessno,
            'toolbox' => $request->toolbox,
            'tooldate' => $tooldate,
            'training' => $request->training,
            'traindate' => $traindate,
            'autherised' => $request->autherised,
            'activity' => $request->activity,
            'investigatordisc' => $request->investigatordisc,
            'bodypart' => $request->bodypart,
            'unsafework' => $request->unsafework,
            'unsafeact' => $request->unsafeact,
            'ppkit' => implode(',', $request->ppkit),
            'prevent' => implode(',', $request->prevent),
            'prevent_other' => $prevent_other,
            'rootcause' => $request->rootcause, 'corrective_act' => $request->corrective_act, 'currective_targdate' => $trgindate, 'responsiblity' => $request->responsiblity, 'attachmentby_investigator' => $dta, 'reviewed' => 'Under Secondary Review', 'investigation_status' => "Investigation Completed", 'actunsafe' => $actunsafe, 'workunsafe' => $workunsafe,
        ]);

        $incident_r = DB::table('incident_reports')->where('report_no', $request->rid)->First();

        //send message to review incident after investigation report
        $data = array(
            'line1' => Lang::get('Dear ' . $incident_r->reviewer_name),
            'line2' => Lang::get('Investigation Process for Incident Report ' . $request->rid . ' is completed and added for secondary review. So please start your Review process.'),
        );
        Mail::to($incident_r->reviewer_email)->send(new ReviewInvestigationReport($data));

        // Session::flash('msg', 'Investigation Report Updated');
        // return redirect()->back();
        $models = DB::table('modenadisable')->First();
        return redirect('user/incidents/register')
            ->with('model', $models)
            ->with('msg', 'Investigation Report Updated for Report No ' . $request->rid);
    }
    public function deleteWitness()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            DB::table('incident_witness')->where('id', $id)->delete();
        }
    }

    //revieweing once investigation completed
    public function reviewApproveInvestigation($id)
    {
        $prevent = DB::table('incident_prevention')->get();
        $protection = DB::table('protection_kit')->get();
        $incident_report = IncidentReport::where('id', $id)->First();
        $reportedby_deptname = Departments::where('id', $incident_report->department)->value('departmentname');
        if ($incident_report->injuredperson == 'other') {
            $injured_deptname = Departments::where('id', $incident_report->injured_person_oth_dept)->value('departmentname');
        } else {
            $injured_deptname = Departments::where('id', $incident_report->injured_person_dept)->value('departmentname');
        }
        $models = DB::table('modenadisable')->First();
        $users = DB::table('users')->get();
        $witnesses = DB::table('incident_witness')->where('report_no', $incident_report->report_no)->get();
        $investigator = DB::table('investigators')->where('report_no', $incident_report->report_no)->First();
        return view('user.incident.reviewapprove')
            ->with('witnesses', $witnesses)->with('investigator', $investigator)
            ->with('protection', $protection)->with('prevent', $prevent)
            ->with('users', $users)->with('model', $models)
            ->with('incident', $incident_report)->with('reportedby_depname', $reportedby_deptname)
            ->with('injured_deptname', $injured_deptname);
    }

    public function reviewedApprovedInvestigation(Request $request)
    {
        $rid = $request->rid;
        if ($request->apprej == 'yes') {
            $improve_investigation = null;
            $invest_status = "Reviewed";
            //$status = "Closed";
            $status = "Reviewed";

            $appro = DB::table('incident_reports')->where('report_no', $rid)->First();

            $data = array(
                'line1' => Lang::get('Dear ' . $appro->approver_name),
                'line2' => Lang::get('Secondary Review for Incident Report ' . $rid . ' is completed and added for Approval Process. So please start your Approval process'),
            );
            Mail::to($appro->approver_email)->send(new ApproveIncidentReport($data));
        } else {
            $improve_investigation = $request->improvement;
            $invest_status = "Need Improvement";
            $status = "Reviewed";

            $inves = DB::table('investigators')->where('report_no', $rid)->First();
            $teammembers = explode(',', $inves->tmembers);
            // // mail all the team members and team leader
            $data = array(
                'line1' => Lang::get('Dear ' . $inves->tleadername),
                'reason' => $improve_investigation,
                'line2' => Lang::get('Investigation for Incident Report ' . $rid . ' is rejected by the Reviewer. So please update your Investigation Report as soon as possible.'),
            );
            Mail::to($inves->tleader)->send(new InvestigationRejected($data));
            foreach ($teammembers as $tm) {
                $data = array(
                    'line1' => Lang::get('Dear ' . $tm),
                    'reason' => $improve_investigation,
                    'line2' => Lang::get('Investigation for Incident Report ' . $rid . ' is rejected by the Reviewer. So please update your Investigation Report as soon as possible.'),
                );
                Mail::to($tm)->send(new InvestigationRejected($data));
            }
        }

        DB::table('incident_reports')->where('report_no', $rid)->update([
            'improve_investigation' => $improve_investigation, 'investigation_status' => $invest_status, 'reviewed' => $status,
        ]);
        $models = DB::table('modenadisable')->First();
        return redirect('user/incidents/register')
            ->with('model', $models)
            ->with('msg', 'Investigation report reviewed and updated successfully');
    }

    public function updateReviewedReport($id)
    {
        $users = DB::table('users')->get();
        $models = DB::table('modenadisable')->First();
        $depts = DB::table('departments')->get();
        $depname = DB::table('departments')->select('id', 'departmentname')->get();
        $empdetails = DB::table('employee_details')->get();
        $incidenttype = DB::table('incident_type')->get();
        $approvers = DB::table('incident_approvers')->get();
        $reviewers = DB::table('incident_reviewer')->get();
        $inctype = $incidenttype;
        $incidentreport = DB::table('incident_reports')->where('id', $id)->First();
        $inj_dep_name = Departments::where('id', $incidentreport->injured_person_dept)->value('departmentname');
        $injured_person_deptname = $inj_dep_name ? $inj_dep_name : '-';

        $incidents = explode(',', $incidentreport->incident_type);
        function remove_elm($arr, $key, $val, $within = false)
        {
            foreach ($arr as $i => $array) {
                if ($within && stripos($array[$key], $val) !== false && (gettype($val) === gettype($array[$key]))) {
                    unset($arr[$i]);
                } elseif ($array[$key] === $val) {
                    unset($arr[$i]);
                }
            }

            return array_values($arr);
        }
        for ($i = 0; $i < count($incidents); $i++) {
            $incidenttype = remove_elm(json_decode($incidenttype, true), "incident_type", $incidents[$i]);
            $incidenttype = json_encode($incidenttype);
        }
        return view('user.incident.updatereviewedreport')
            ->with('inctype', $inctype)
            ->with('incidenttype', json_decode($incidenttype, true))
            ->with('depts', $depts)->with('incident', $incidentreport)
            ->with('model', $models)->with('approvers', $approvers)
            ->with('reviewers', $reviewers)->with('users', $users)
            ->with('empdetails', $empdetails)->with('depnam', $depname)
            ->with('injured_person_deptname', $injured_person_deptname);
    }

    public function updatedReviewedReport(Request $request)
    {
        $data = [];
        if ($request->hasfile('filenames')) {
            $dir = str_replace('/', '_', $request->rid);
            if (!File::exists(public_path('/storage/IncidentFiles/'))) {
                File::makeDirectory(public_path() . '/storage/IncidentFiles/', 0755, true);
            }

            if (!File::exists(public_path('storage/IncidentFiles/' . $dir . '/'))) {
                File::makeDirectory(public_path() . '/storage/IncidentFiles/' . $dir . '/', 0755, true);
            }

            foreach ($request->file('filenames') as $file) {
                $fnamewithext = $file->getClientOriginalName();
                $fname = pathinfo($fnamewithext, PATHINFO_FILENAME);
                $name = 'Reporterfile' . '_' . $fname . '.' . $file->extension();
                $file->move(public_path() . '/storage/IncidentFiles/' . $dir . '/', $name);
                $data[] = $name;
            }
        }
        $attachmentby_reporter = DB::table('incident_reports')->where('report_no', $request->rid)->First();

        $dta = $attachmentby_reporter->attachmentby_reporter . ',' . implode($data);

        // if($request->local=="yes")
        //   $local_reg=$request->localreg;
        //   else
        //   $local_reg=null;

        // $adddate=Carbon::parse($request->addeddate)->format('d/m/Y');
        if ($request->person_injured == 'yes') {
            if ($request->injempname_oth == null && $request->injempname_oth2 == null) {
                //$a = "othnull and oth2null";
                $injempname = DB::table('employee_details')->where('emp_number', $request->person)->value('emp_name');
                $injempid = $request->empid;
                $injpost = $request->injposition;
                //$injdepts = $request->injdept;
                $injdeptid = Departments::where('departmentname', $request->injdept)->value('id');
                $injdepts = $injdeptid ? $injdeptid : '-';
                $injempnameoth = null;
                $injempidoth = null;
                $injpostoth = null;
                $injdeptsoth = null;
            } elseif ($request->injempname_oth2 != null) {
                //$a = "oth2notnull";
                $injempnameoth = $request->injempname_oth2;
                $injempidoth = $request->injempid_oth2;
                $injpostoth = $request->injposition_oth2;
                $injdeptsoth = $request->injdept_oth2;
                $injempname = 'other';
                $injempid = 'nodata';
                $injpost = null;
                $injdepts = null;
            } else {
                //$a = "othnotnull";
                $injempnameoth = $request->injempname_oth;
                $injempidoth = $request->injempid_oth;
                $injpostoth = $request->injposition_oth;
                $injdeptsoth = $request->injdept_oth;
                $injempname = 'other';
                $injempid = 'nodata';
                $injpost = null;
                $injdepts = null;
            }
        } else {
            $injempname = '-';
            $injempid = 'nodata';
            $injpost = null;
            $injdepts = null;
            $injempnameoth = null;
            $injempidoth = null;
            $injpostoth = null;
            $injdeptsoth = null;
        }

        $incidentdate = Carbon::parse($request->input('incidentdate'))->format('d/m/Y');
        DB::table('incident_reports')->where('report_no', $request->rid)
            ->update([
                'addedby' => $request->addedby,
                'addeddate' => $request->addeddate,
                'department' => $request->department,
                'division_id' => $request->division ?? 0,
                'area_id' => $request->subdiv ?? 0,
                'area' => $request->area,
                'report_no' => $request->rid,
                'incident_type' => implode(",", $request->type),
                'any_person_injured' => $request->person_injured,
                'injuredperson' => $injempname,
                'empid' => $injempid,
                'injured_person_position' => $injpost,
                'injured_person_dept' => $injdepts,
                'injured_person_oth_name' => $injempnameoth,
                'injured_person_oth_empid' => $injempidoth,
                'injured_person_oth_position' => $injpostoth,
                'injured_person_oth_dept' => $injdeptsoth,
                'hospital' => $request->hospital,
                'incidentplace' => $request->place,
                'incidentdate' => $incidentdate,
                'emplyoer' => $request->employer,
                'trade_or_occupation' => $request->trade,
                'incidentdesc' => $request->desc,
                'actions' => $request->action,
                'local_regulation' => $request->localreg,
                'time' => $request->time,
                'attachmentby_reporter' => $dta,
                'reviewed' => 'Under review',
                'adder_email' => Auth::user()->email,
            ]);
        $rev = DB::table('incident_reports')->where('report_no', $request->rid)->First();
        $data2 = array(
            'line1' => Lang::get('2'),
            'line2' => Lang::get('Dear ' . $rev->reviewer_name),
            'line3' => Lang::get('Incident Report ' . $request->rid . ' is Updated and added for Review. Please Start your review process'),
        );
        Mail::to($rev->reviewer_email)->send(new AddIncident($data2));

        // Session::flash('msg', 'Incident report updated successfully');
        // return redirect()->back();
        $models = DB::table('modenadisable')->First();
        return redirect('user/incidents/register')
            ->with('model', $models)
            ->with('msg', 'Incident Report updated successfully');
    }

    //editing incindent report by mr
    public function mrEditIncident()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = DB::table('incident_reports')->where('report_no', $d)->first();
                }
                $n = $n + 1;
            }
        }
        $inctype = DB::table('incident_type')->get();
        // $data=$data;
        $departments = DB::table('departments')->pluck('departmentname');
        $models = DB::table('modenadisable')->First();
        return view('user.incident.editincident')->with('inctype', $inctype)->withData($data)->with('departments', $departments)->with('id', $id)->with('model', $models);
    }

    public function mrEditedIncident(Request $request)
    {
        $totalreports = count($request->rid);
        $i = 0;
        while ($totalreports > $i) {
            $incidentdate = Carbon::parse($request->input('incidentdate')[$i])->format('d/m/Y');
            DB::table('incident_reports')->where('report_no', $request->rid[$i])->update(['department' => $request->department[$i], 'area' => $request->area[$i], 'injuredperson' => $request->person[$i], 'empid' => $request->empid[$i], 'hospital' => $request->empid[$i], 'incidentplace' => $request->place[$i], 'incidentdate' => $incidentdate, 'emplyoer' => $request->employer[$i], 'trade_or_occupation' => $request->trade[$i], 'incidentdesc' => $request->desc[$i], 'actions' => $request->action[$i], 'local_regulation' => $request->localreg[$i], 'time' => $request->time[$i]]);
            $i += 1;
        }
        session::flash('editmsg', 'Reports successfully updated');
        return redirect()->back();
    }

    public function ApproveInvestigationreport($id)
    {
        $prevent = DB::table('incident_prevention')->get();
        $protection = DB::table('protection_kit')->get();
        $incident_report = IncidentReport::where('id', $id)->First();
        $reportedby_deptname = Departments::where('id', $incident_report->department)->value('departmentname');
        if ($incident_report->injuredperson == 'other') {
            $injured_deptname = Departments::where('id', $incident_report->injured_person_oth_dept)->value('departmentname');
        } else {
            $injured_deptname = Departments::where('id', $incident_report->injured_person_dept)->value('departmentname');
        }
        $models = DB::table('modenadisable')->First();
        $users = DB::table('users')->get();
        $witnesses = DB::table('incident_witness')->where('report_no', $incident_report->report_no)->get();
        $investigator = DB::table('investigators')->where('report_no', $incident_report->report_no)->First();
        return view('user.incident.approveincidentreport')
            ->with('witnesses', $witnesses)->with('investigator', $investigator)
            ->with('protection', $protection)->with('prevent', $prevent)
            ->with('users', $users)->with('model', $models)
            ->with('reportedby_depname', $reportedby_deptname)
            ->with('injured_deptname', $injured_deptname)
            ->with('incident', $incident_report);
    }

    public function approveRejIncident(Request $request)
    {
        $rid = $request->rid;
        $adder = DB::table('incident_reports')->where('report_no', $rid)->First();
        if ($request->apprej == 'yes') {
            $rejreason = null;
            $invest_status = "Approved";
            //$status = "Closed";
            //$status = "Approved";
            $data = array(
                'line1' => Lang::get('Dear ' . $adder->addedby),
                'line2' => Lang::get('Your Incident Report ' . $rid . ' is Approved By Approver on ' . Date('d/m/Y') . '.'),
            );
            Mail::to($adder->adder_email)->send(new IncidentReportApproval($data));
        } else {
            $rejreason = $request->rejreason;
            $invest_status = "Rejected";
            //$status = "Closed";
            // $status = "Reviewed";
            $data = array(
                'line1' => Lang::get('Dear ' . $adder->addedby),
                'line2' => Lang::get('Your Incident Report ' . $rid . ' is Rejected By Approver on ' . Date('d/m/Y') . ' with Rejection Reason as ' . $rejreason),
            );
            Mail::to($adder->adder_email)->send(new IncidentReportApproval($data));
        }

        DB::table('incident_reports')->where('report_no', $rid)->update([
            'rej_reason' => $rejreason, 'investigation_status' => $invest_status, 'approved_rej' => $request->apprej,
        ]);
        // Session::flash('msg', 'Investigation report reviewed and updated successfully');
        // return redirect()->back();
        $models = DB::table('modenadisable')->First();
        return redirect('user/incidents/register')
            ->with('model', $models)
            ->with('msg', 'Incident Report reviewed and updated successfully');
    }
/*
public function closedincidents()
{
$investigators = DB::table('investigators')->get();
$witnesses = DB::table('incident_witness')->get();
$closed = DB::table('incident_reports')->select('*')->join('investigators', 'investigators.report_no', '=', 'incident_reports.report_no')
->where('incident_reports.reviewed', 'Closed')->get();
$models = DB::table('modenadisable')->First();
return view('user.incident.closedincident')->with('witnesses', $witnesses)->with('investigators', $investigators)->with('incidents', $closed)->with('model', $models);
}
 */
    public function closedincidents()
    {
        $incidents = IncidentReport::with([
            'departmentOfIncident',
            'investigator.user',
            'injuredPersonOthDepartment',
            'injuredPersonDepartment',
            'correctives',
            'division',
            'subdiv'
        ])->withCount('correctives')
            ->where('reviewed','!=','Closed')
            ->latest('created_at')->get();

        $investigators = DB::table('investigators')->get();
        $departments = DB::table('departments')->get();
        $corrective = DB::table('corrective')->get();
        $closed = DB::table('incident_reports')->where('reviewed', '=', 'Closed')->get();
        $models = DB::table('modenadisable')->First();
        return view('user.incident.closedincident')->with('investigators', $investigators)
            ->with('incidents', $incidents)
            ->with('corr', $corrective)
            ->with('departments', $departments)
            ->with('model', $models);

    }

    public function deleteincident($id)
    {

        $rid = DB::table('incident_reports')->where('id', $id)->First();
        $activity = 'Incident report ' . $rid->report_no . ' is deleted';
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'User');
        DB::table('viewactivity')->insert($datas);

        DB::table('incident_reports')->where('report_no', $rid->report_no)->delete();
        $dir = str_replace('/', '_', $rid->report_no);
        if (File::exists(public_path('storage/IncidentFiles/' . $dir . '/'))) {
            File::deleteDirectory(public_path('storage/IncidentFiles/' . $dir . '/'));
        }

        return redirect()->back();
    }

    //corrective actions starts here
    //adding corrective actions starts

    public function correctiveaction($id)
    {

        $departments = Departments::all();
        $type = Type::all();
        $user = User::all();
        $categories = Categories::all();
        $models = DB::table('modenadisable')->First();
        $incidentreport = DB::table('incident_reports')->where('id', $id)->First();
        return view('user.incident.addincidentcorrective')->withDepartments($departments)
            ->withType($type)
            ->withCategories($categories)
            ->withUser($user)
            ->with('incident', $incidentreport)
            ->with('model', $models);
    }

    public function correctedAction(Request $req)
    {
        $v = Validator::make($req->all(), [
            'department' => 'required',
            'type'=>'required',
            'responsibility' => 'required',
            'observation' => 'required',
            'agreedaction'=>'required',
            'duedate' => 'required|date|after:tomorrow',
        ]);

        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
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
                    $destination_path = public_path('storage/issue_files');
                    $file_array[$i]->move($destination_path, $new_file_name);
                    $file[] = $new_file_name;
                }
                $cer = implode(',', $file);
            }

            $approvement = "yes";
            $processstage = "Awaiting actions";
            $status = "open";
            $approvedby = Auth::user()->employeeid;
            $category = $req->input('category');
            $type = $req->input('type');
            $department = $req->input('department');
            $division = $req->input('division');
            $area = $req->input('area');
            $title = $req->input('title');
            $observation = $req->input('observation');
            $agreedaction = $req->input('agreedaction');
            $correction_target_date = $req->input('duedate');

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
                $notification = '';
            }

            $type_name = Type::where('order',$type)->value('name');
            $postedby = $req->input('postedby');
            $name = DB::table('users')->where('employeeid', $postedby)->get();

            //generating corrective issue id
            $incident_report_data = IncidentReport::where('report_no',$title)->first();
            $incident_report_id = $incident_report_data->id;
            $current_year = date('Y');
            $corrective_exist = Corrective::where('category', 'Incident Investigation')->latest()->first();
            if($corrective_exist){
                $count = $corrective_exist->count + 1;
            }else{
                $count = 1;
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
            //inserting data to corrective table
            $postdate = date('d-m-Y h:i:s A', time());
            $ReviewDate = date('Y-m-d', strtotime($postdate));
            $data4 = array(
                'count' => $count,
                'issueid' => $issueId,
                'category' => $category,
                'type' => $type,
                'correction_department' => $department,
                'department' => 0,
                'title' => $title,
                'file' => $cer,
                'observation' => $observation,
                'correction_target_date' => $correction_target_date,
                'frequency' => $frequency,
                'agreedaction' => $agreedaction,
                'postedby' => $postedby,
                'correction_responsibility' => $resp,
                'notification' => $notification,
                'approvement' => $approvement,
                'processstage' => $processstage,
                'approvedby' => $approvedby,
                'year' => $current_year,
                'status' => $status,
                'postdate' => $postdate,
                'incident_id' => $incident_report_id,
                'Monthlyfreq' => $ReviewDate,
                'Weeklyfreq' => $ReviewDate,
            );
            Corrective::create($data4);

            //fetching id from corrective table
            $correct_id = Corrective::where('issueid', $issueId)->select('id')->First()->id;

            //inserting data to incident_corrective table
            $data = [
                        'count' => $count,
                        'issueid' => $issueId,
                        'incident_reportno' => $title,
                        'corrective_id' => $correct_id,
                        'category' => $category,
                        'year' => $current_year,
                        'file_name' => $cer,
                    ];
            IncidentCorrective::create($data);

            //checking status of that particular incident in incident_reports table
            $reviw_status = DB::table('incident_reports')->where('report_no', $title)->select('reviewed')->First()->reviewed;
            if ($reviw_status == "Closed") {
                $new_status = "Actions Open";
                DB::table('incident_reports')->where('report_no', $title)->update(['reviewed' => $new_status]);
            }

            //inserting values to activity table superadmin code
            $activity = "New Issue is Inserted with Title as " . $title . ", with Status as " . $status . " and with Process Stage as " . $processstage;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //superadmin code end

            $id = $correct_id;
            $depname = Departments::where('id', $department)->value('departmentname');
            $name = Auth::user()->name;
            $files = $cer;
            $expl = explode(',', $files);

            foreach ($expl as $file) {
                $data1 = array('id' => $id, 'issue_file' => $file);
                DB::table('multiplecorr_issues')->insert($data1);
            }

            //Sending Mail(Responsibility Notification) To responsibile HOD
            $m = 0;
            $reviewers = DB::table('issuereviewers')->pluck('email')->toArray();
            if(count($reviewers)>0){
                if(in_array(Auth::user()->email,$reviewers)) {
                    $corr_respon_emails = EmailNotification::where('module', 'correctiveaction')
                                                            ->where('item', 'Corrective Response Notification')
                                                            ->where('is_blocked', false)
                                                            ->first();
                    if ($corr_respon_emails){
                            $data = array(
                                        'issueid' => $issueId,
                                         'name' => $name,
                                         'email_content' => $corr_respon_emails->email_content,
                                         'item' => $corr_respon_emails->item,
                                         'type' => $type_name ? $type_name  : '',
                                         'department' => $depname,
                                         'duedate' => date("d-m-Y", strtotime($correction_target_date)),
                                     );
                            Mail::to($resp)->send(new CorrectiveResponseNotification($data));

                            $userdatas = EmailNotificationsUserDetails::where('notification_id',$corr_respon_emails->id)->get();
                            if (count($userdatas) > 0) {
                                $adminaddedusers = $userdatas->pluck('user_id')->toArray();
                                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                                if (count($userdatas) > 0) {
                                    Mail::to($userdatas)->send(new CorrectiveResponseNotification($data));
                                }
                            }
                    }else{
                        Session::flash('msg', 'Mail Sending Blocked for Corrective Response Notification');
                        return redirect()->back();
                    }
                }
            }

               //Sending Mail(AddIsssue Notification) To Other HOD
                $corrective_add_emails = EmailNotification::where('module', 'correctiveaction')
                                                            ->where('item', 'Corrective Issue Added')
                                                            ->where('is_blocked', false)
                                                            ->first();
                if ($corrective_add_emails) {
                            $data = array(
                                'name' => $name,
                                'email_content' => $corrective_add_emails->email_content,
                                'item' => $corrective_add_emails->item,
                                'type' => $type_name ? $type_name  : '',
                                'department' => $depname,
                                'duedate' => $correction_target_date ? date("d-m-Y", strtotime($correction_target_date)) : '',
                            );
                    if ($notification && $notification != '') {
                        $noti = explode(',', $notification);
                        if (count($noti) > 0) {
                            Mail::to($noti)->send(new CorrectiveAddIssue($data));
                        }
                    }
                    $m = 1;
                    $userdatas = EmailNotificationsUserDetails::where('notification_id',$corrective_add_emails->id)->get();
                    if (count($userdatas) > 0) {
                        $adminaddedusers = $userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveAddIssue($data));
                        }
                    }

                }else{
                    Session::flash('msg', 'Mail Sending Blocked for Add Corrective Issue');
                    return redirect()->back();
                }
            Session::flash('msg', "Issue submitted successfully");
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    //adding corrective actions ends

    //display corrective starts here

    public function viewcorrective($id)
    {
        $postedby_image = null;
        $responsible_image = null;
        //below is superadmin related code
        $correctiveaction = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($correctiveaction) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 1, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0]);
        } else {
            $correctiveaction = DB::table('modpageload')->where('id', 1)->First();
            $correctiveaction->correctiveaction = $correctiveaction->correctiveaction + 1;
            DB::table('modpageload')->update(['correctiveaction' => $correctiveaction->correctiveaction]);
        }
        //superadmin code ends

        $rec1 = DB::table('multiplecorr_issues')->distinct()->get();
        $rec2 = DB::table('multiplecorr_actions')->distinct()->get();
        $mr = DB::table('representatives')->pluck('email');
        $issuereviewers = DB::table('issuereviewers')->pluck('email');
        $departments = Departments::all();
        $types = Type::all();

        $issues = DB::table('corrective')->where('id', $id)->First();

        $today = date('Y-m-d');

        if ($issues->duedate > $today && $issues->status == 'open') {
            $freq = explode(',', $issues->frequency);
            $name = DB::table('users')->where('employeeid', $issues->postedby)->pluck('name');
            foreach ($name as $n) {
                $data = array(
                    'issueid' => $issues->issueid,
                    'name' => $n,
                    'type' => $issues->type,
                    'department' => $issues->department,
                    'duedate' => date("d/m/Y", strtotime($issues->duedate)),
                );
            }
            foreach ($freq as $f) {
                if ($f == 'Monthly' && $today == date("Y-m-d", strtotime($issues->Monthlyfreq . ' + 31 days'))) {
                    Mail::to($issues->responsibility)->send(new CorrectiveResponseNotification($data));
                    $Monthlyfreq = date('Y-m-d');
                    $data1 = array('Monthlyfreq' => $Monthlyfreq);
                    DB::table('corrective')->where('id', $issues->id)->update($data1);
                } elseif ($f == 'Weekly' && $today == date("Y-m-d", strtotime($issues->Weeklyfreq . ' + 7 days'))) {
                    Mail::to($issues->responsibility)->send(new CorrectiveResponseNotification($data));
                    $Weeklyfreq = date('Y-m-d');
                    $data1 = array('Weeklyfreq' => $Weeklyfreq);
                    DB::table('corrective')->where('id', $issues->id)->update($data1);
                } elseif ($f == '2 Days Before' && $today == date("Y-m-d", strtotime($issues->duedate . ' - 2 days')) && $issues->Dayfreq == '0') {
                    Mail::to($issues->responsibility)->send(new CorrectiveResponseNotification($data));
                    $Dayfreq = '1';
                    $data1 = array('Dayfreq' => $Dayfreq);
                    DB::table('corrective')->where('id', $issues->id)->update($data1);
                }
            }
        }

        $models = DB::table('modenadisable')->First();
        $issuedocs = DB::table('multiplecorr_issues')->get();
        $actiondocs = DB::table('multiplecorr_actions')->get();

        return view('user.incident.incidentcorrections', compact('types', 'departments', 'issuereviewers'))->with('issue', $issues)->with('issuedocs', $issuedocs)->with('actiondocs', $actiondocs)->withRec1($rec1)->withRec2($rec2)->with('model', $models)->withMr($mr);
        //return view('user.incident.test')->with('issue',$issues)->with('model',$models);

    }

    public function ajaxShowProfile($id)
    {

        $issue = Corrective::with('postedBy', 'approvedBy')->where('id', $id)->first();
        $responsibleUser = User::where('email', $issue->responsibility)->first();
        $postedByUser = User::find($issue->postedby);
        $issue->postedByUser = $postedByUser;
        $issue->responsibleUser = $responsibleUser;
        return response()->json(['data' => $issue]);
    }

    //display corrective ends here
    //corrective actions ends here
    /*
    public function mycorractions()
    {
    $investigators = DB::table('investigators')->get();
    $witnesses = DB::table('incident_witness')->get();
    $closed = DB::table('incident_reports')
    ->select('*')->join('investigators', 'investigators.report_no', '=', 'incident_reports.report_no')
    ->where('responsiblity', Auth::user()->email)
    ->where('incident_reports.reviewed', '!=', 'Closed')->get();
    $models = DB::table('modenadisable')->First();
    return view('user.incident.correctiveaction')->with('witnesses', $witnesses)->with('investigators', $investigators)->with('incidents', $closed)->with('model', $models);
    }
     */
    public function mycorractions()
    {
        $corrections = Corrective::with('dept', 'postedByCorrective', 'approvedByCorrective', 'closedByCorrective', 'incidentReport')->where('category','Incident Investigation')
                                 ->where('status','Open')
                                 ->latest('created_at')
                                 ->get();
        $increport = DB::table('incident_reports')->get();
        //$investigators = DB::table('investigators')->get();
        $issuedocs = DB::table('multiplecorr_issues')->get();
        $actiondocs = DB::table('multiplecorr_actions')->get();
        $users = User::all();
        $departments = DB::table('departments')->get();
        $models = DB::table('modenadisable')->First();
        return view('user.incident.correctiveaction')
            ->with('corrections', $corrections)
            ->with('incrpt', $increport)
            ->with('issuedocs', $issuedocs)
            ->with('actiondocs', $actiondocs)
            ->with('users', $users)
            ->with('departments', $departments)
            ->with('model', $models);
    }

    public function closedcorrections()
    {
        $corrections = Corrective::with('dept', 'postedByCorrective', 'approvedByCorrective', 'closedByCorrective', 'incidentReport')->where('category','Incident Investigation')
                                    ->where('status', 'Closed')
                                    ->latest('closed')
                                    ->get();
        $increport = DB::table('incident_reports')->get();
        //$investigators = DB::table('investigators')->get();
        $issuedocs = DB::table('multiplecorr_issues')->get();
        $actiondocs = DB::table('multiplecorr_actions')->get();
        $users = User::all();
        $departments = DB::table('departments')->get();
        $models = DB::table('modenadisable')->First();
        return view('user.incident.closedcorrections')
            ->with('corrections', $corrections)
            ->with('incrpt', $increport)
            ->with('issuedocs', $issuedocs)
            ->with('actiondocs', $actiondocs)
            ->with('users', $users)
            ->with('departments', $departments)
            ->with('model', $models);
    }

    public function editcorractions($id)
    {
        $id = str_replace('_', '/', $id);
        $models = DB::table('modenadisable')->First();
        $corr = DB::table('incident_reports')->where('report_no', $id)->First();
        $witnesses = DB::table('incident_witness')->where('report_no', $id)->get();
        $investigator = DB::table('investigators')->where('report_no', $id)->First();
        return view('user.incident.updatecorr')->with('witnesses', $witnesses)->with('investigator', $investigator)->with('incident', $corr)->with('model', $models);
    }

    public function corrupdate(Request $request)
    {

        $corr_file = 'NULL';

        if ($request->hasFile('corrfiles')) {
            $file_array = $request->file('corrfiles');
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $file_ext = $file_array[$i]->getClientOriginalExtension();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/IncidentFiles');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
            }
            $corr_file = implode(',', $file);
        }
        DB::table('incident_reports')->where('report_no', $request->rid)
            ->update([
                'rootcause' => $request->root, 'corrective_act' => $request->corrective,
                'corr_implement_status' => $request->impl_status, 'corrfiles' => $corr_file,
            ]);
        Session::flash('msg', 'Report Updated Successfully');
        return redirect()->back();
    }

    //change reviewer
    public function changereviewer($id)
    {
        $data = DB::table('incident_reports')->where('id', $id)->first();
        if($data->reviewed=='Under review' || $data->reviewed=='Rejected') {
            $status = 'Incident Under Review';
        }
        else if($data->reviewed=='Under Secondary Review'){
            $status = 'Investigation Under Review';
        }
        else if($data->reviewed=='Need Improvement'){
            $status = 'Need Improvement';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Under Investigation'){
            $status = 'Investigation Assigned';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Not Needed'){
            $status = 'No Investigation needed';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Reviewed'){
            $status = 'Investigation Report Reviewed';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Need Improvement'){
            $status = 'Investigation Report Need Improvement';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Approved'){
            $status = 'Investigation Report Approved';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Rejected'){
            $status = 'Investigation Report Need Improvement';
        }
        $models = DB::table('modenadisable')->First();
        $reviewers = DB::table('incident_reviewer')->get();
        return view('user.incident.changereviewer')
            ->with('data', $data)
            ->with('status', $status)
            ->with('reviewers', $reviewers)
            ->with('model', $models);
    }

    public function changereviewerupdate(Request $request)
    {
        $reviewer = $request->reviewer;
        $reviewer_name = DB::table('incident_reviewer')->where('email', $reviewer)->value('name');
        DB::table('incident_reports')->where('id', $request->incident_id)
            ->update([
                'reviewer_email' => $reviewer,
                'reviewer_name' => $reviewer_name,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

        $incident_data = DB::table('incident_reports')->where('id', $request->incident_id)->first();
        $data = array('inc_id' => $incident_data->report_no, 'name' => $reviewer_name);
        Mail::to($reviewer)->send(new IncidentReviewerChanged($data));

        Session::flash('msg', "Reviewer Changed successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    //change approver
    public function changeapprover($id)
    {
        $data = DB::table('incident_reports')->where('id', $id)->first();
        if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Under Investigation'){
            $status = 'Investigation Assigned';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Not Needed'){
            $status = 'No Investigation needed';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Reviewed'){
            $status = 'Investigation Report Reviewed';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Need Improvement'){
            $status = 'Investigation Report Need Improvement';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Approved'){
            $status = 'Investigation Report Approved';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Rejected'){
            $status = 'Investigation Report Need Improvement';
        }
        else if($data->reviewed=='Under Secondary Review'){
            $status = 'Investigation Under Review';
        }
        else if($data->reviewed=='Need Improvement'){
            $status = 'Need Improvement';
        }

        $models = DB::table('modenadisable')->First();
        $approvers = DB::table('incident_approvers')->get();
        return view('user.incident.changeapprover')
            ->with('data', $data)
            ->with('status', $status)
            ->with('approvers', $approvers)
            ->with('model', $models);
    }

    public function changeapproverupdate(Request $request)
    {
        $approver = $request->approver;
        $approver_name = DB::table('incident_approvers')->where('email', $approver)->value('name');
        DB::table('incident_reports')->where('id', $request->incident_id)
            ->update([
                'approver_email' => $approver,
                'approver_name' => $approver_name,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

        $incident_data = DB::table('incident_reports')->where('id', $request->incident_id)->first();
        $data = array('inc_id' => $incident_data->report_no, 'name' => $approver_name);
        Mail::to($approver)->send(new IncidentApproverChanged($data));

        Session::flash('msg', "Approver Changed successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    //closure review
    public function closurereview($id)
    {
        $incident_report = IncidentReport::where('id', $id)->First();
        $models = DB::table('modenadisable')->First();
        $approvers = DB::table('incident_approvers')->get();
        $reportedby_deptname = Departments::where('id', $incident_report->department)->value('departmentname');
        if ($incident_report->injuredperson == 'other') {
            $injured_deptname = Departments::where('id', $incident_report->injured_person_oth_dept)->value('departmentname');
        } else {
            $injured_deptname = Departments::where('id', $incident_report->injured_person_dept)->value('departmentname');
        }
        $investigator = DB::table('investigators')->where('report_no', $incident_report->report_no)->First();
        $users = User::where('is_email_verified',true)->where('approved',true)->get();
        return view('user.incident.closurereview')
            ->with('incident', $incident_report)
            ->with('approvers', $approvers)
            ->with('investigator', $investigator)
            ->with('users', $users)
            ->with('reportedby_depname', $reportedby_deptname)
            ->with('injured_deptname', $injured_deptname)
            ->with('model', $models);
    }

    public function addinvestigatorsajax(AddInvestigatorRequest $request)
    {
        $report_no = IncidentReport::where('id',$request->input('incident_id'))->value('report_no');
        $leader = $request->input('leader');
        $members = implode(',', $request->members);
        $targetdate = Carbon::parse($request->input('targetdate'))->format('d/m/Y');
        $user_exists = User::where('email',$request->input('leader'))->first();
        if($user_exists){
            $tleader_name = $user_exists->name;
        }
        $member_names_arr = array();
        foreach($request->members as $member){
            $memexists = User::where('email',$member)->first();
            if($memexists){
                array_push($member_names_arr,$memexists->name);
            }
        }
        $member_names = implode(',',$member_names_arr);
        $investigators = Investigator::where('report_no', $report_no)->get();
        $data = array(
                        'tleader' => $leader,
                        'tmembers'=>$members,
                        'targetdate'=>$targetdate,
                        'tleadername'=>$tleader_name,
                        'tmembersnames'=>$member_names
                    );
        $data1 = array(
                        'tleader' => $leader,
                        'tmembers'=>$members,
                        'targetdate'=>$targetdate,
                        'tleadername'=>$tleader_name,
                        'tmembersnames'=>$member_names,
                        'report_no'=>$report_no
                        );
        if(count($investigators)>0){
            Investigator::where('report_no', $report_no)->update($data);
        }
        else{
            Investigator::create($data1);
        }

        IncidentReport::where('id',$request->input('incident_id'))
                                        ->update([
                                                'further_investigate'=>'yes',
                                                'reviewed'=>'Reviewed',
                                                'investigation_status' => 'Under Investigation',
                                                ]);

        // mail all the team members and team leader
            $data = array(
            'line1' => Lang::get('1'),
            'line2' => Lang::get('Dear '.$tleader_name),
            'line3' => Lang::get('Your are receiving this mail because you are appointed as Team Leader to investigate the Incident Report ' . $report_no . ' with Target Date ' . $targetdate. '. So please start your Investigation process.'),
            );
            Mail::to($leader)->send(new ForInvestigate($data, $leader));

            //mail to team members
            $datateam = array('inc_id' => $report_no, 'tardate' => $targetdate);
            foreach ($request->members as $tm) {
                Mail::to($tm)->send(new IncidentTeamMember($datateam));
            }

            return response()->json([
            'status' => 200,
            'message'=>'Invetigator added successfully'
        ]);

    }

    public function confirmclosurereview(Request $request)
    {
        IncidentReport::where('id',$request->main_incident_id)->update(['reviewed'=>'Closed']);
        $models = DB::table('modenadisable')->First();
        return redirect('user/incidents/register')
            ->with('model', $models)
            ->with('msg', 'Incident closed successfully');
    }


    //change investigation reviewer
    public function changeinvestigationreviewer($id)
    {
        $data = IncidentReport::where('id', $id)->first();
        if($data->reviewed=='Under review' || $data->reviewed=='Rejected') {
            $status = 'Incident Under Review';
        }
        else if($data->reviewed=='Under Secondary Review'){
            $status = 'Investigation Under Review';
        }
        else if($data->reviewed=='Need Improvement'){
            $status = 'Need Improvement';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Under Investigation'){
            $status = 'Investigation Assigned';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Not Needed'){
            $status = 'No Investigation needed';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Reviewed'){
            $status = 'Investigation Report Reviewed';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Need Improvement'){
            $status = 'Investigation Report Need Improvement';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Approved'){
            $status = 'Investigation Report Approved';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Rejected'){
            $status = 'Investigation Report Need Improvement';
        }
        $models = DB::table('modenadisable')->First();
        $reviewers = DB::table('incident_reviewer')->get();
        return view('user.incident.changeinvestigationreviewer')
            ->with('data', $data)
            ->with('status', $status)
            ->with('reviewers', $reviewers)
            ->with('model', $models);
    }

    public function changeinvestigationreviewerupdate(Request $request)
    {
        $reviewer = $request->reviewer;
        $reviewer_name = DB::table('incident_reviewer')->where('email', $reviewer)->value('name');
        IncidentReport::where('id', $request->incident_id)
            ->update([
                'reviewer_email' => $reviewer,
                'reviewer_name' => $reviewer_name,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

        $incident_data = IncidentReport::where('id', $request->incident_id)->first();
        $data = array('inc_id' => $incident_data->report_no, 'name' => $reviewer_name);
        Mail::to($reviewer)->send(new IncidentInvestigationReviewerchanged($data));

        Session::flash('msg', "Investigation Reviewer Changed successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    //change investigation approver
    public function changeinvestigationapprover($id)
    {
        $data = IncidentReport::where('id', $id)->first();
        if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Under Investigation'){
            $status = 'Investigation Assigned';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Not Needed'){
            $status = 'No Investigation needed';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Reviewed'){
            $status = 'Investigation Report Reviewed';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Need Improvement'){
            $status = 'Investigation Report Need Improvement';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Approved'){
            $status = 'Investigation Report Approved';
        }
        else if(($data->reviewed=='Reviewed' || $data->reviewed=='Approved') && $data->investigation_status=='Rejected'){
            $status = 'Investigation Report Need Improvement';
        }
        else if($data->reviewed=='Under Secondary Review'){
            $status = 'Investigation Under Review';
        }
        else if($data->reviewed=='Need Improvement'){
            $status = 'Need Improvement';
        }

        $models = DB::table('modenadisable')->First();
        $approvers = DB::table('incident_approvers')->get();
        return view('user.incident.changeinvestigationapprover')
            ->with('data', $data)
            ->with('status', $status)
            ->with('approvers', $approvers)
            ->with('model', $models);
    }

    public function changeinvestigationapproverupdate(Request $request)
    {
        $approver = $request->approver;
        $approver_name = DB::table('incident_approvers')->where('email', $approver)->value('name');
        DB::table('incident_reports')->where('id', $request->incident_id)
            ->update([
                'approver_email' => $approver,
                'approver_name' => $approver_name,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

        $incident_data = IncidentReport::where('id', $request->incident_id)->first();
        $data = array('inc_id' => $incident_data->report_no, 'name' => $approver_name);
        Mail::to($approver)->send(new IncidentInvestigationApproverChanged($data));

        Session::flash('msg', "Investigation Approver Changed successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

}
