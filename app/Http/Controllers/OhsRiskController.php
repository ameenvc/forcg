<?php

namespace App\Http\Controllers;

use App\Area;
use App\Charts\OhsRiskAnalysis;
use App\Departments;
use App\Division;
use App\EmployeeDetails;

// use Validator;
use App\Http\Controllers\Controller;
use App\Location;
use App\Mail\NewOhsRiskAdded;
use App\Mail\OhsRiskReviewed;
use App\Ohs;
use App\Ohsrisk;
use App\OhsRiskowner;
use App\OhsTeam;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Session;

class OhsRiskController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    public function index()
    {
        return view('user.ohsrisk.home');
    }
    public function folder(string $type = null)
    {
        session(['ohsdept' => " "]);
        switch ($type) {
            case null:
                $departments = DB::table('departments')->get();
                break;
            case 'divisional':
                $ids = Division::pluck('department_id');
                $departments = Departments::whereIn('id', $ids)->get();
                break;
            case 'subdivisional':
                $areas = Area::pluck('division_id');
                $ids = Division::whereIn('id', $areas)->pluck('department_id');
                $departments = Departments::whereIn('id', $ids)->get();
                break;
            default:
                $departments = DB::table('departments')->get();
                break;
        }
        //$departments = DB::table('departments')->get();
        $models = DB::table('modenadisable')->first();

        foreach ($departments as $dep) {
            $data = Ohs::where('department', $dep->id)
                ->pluck('location')
                ->toArray();
            $data_count = count(array_unique($data));
            $reported_dates = Ohs::where('department', $dep->id)->pluck('last_review_date')->toArray();
            $update_date = count($reported_dates) ? max($reported_dates) : 0;
            $dep->numberOfLocations = $data_count;
            $dep->update_date = $update_date ? date('M d, Y', strtotime($update_date)) : 'no review';
        }
        return view('user.ohsrisk.folder')->with([
            'model' => $models,
            'departments' => $departments,
            'type' => $type,
        ]);
    }
    public function subfolder($dept, string $type = null)
    {
        session(['ohsdept' => $dept]);
        $dept_id = Departments::where('departmentname', $dept)->value('id');
        $location = Ohs::where('department', $dept_id)
            ->pluck('location');

        $alllocation = collect($location);
        $uniquelocation = $alllocation->unique()->values()->all();
        $locations = [];
        for ($i = 0; $i < count($uniquelocation); $i++) {
            $locations[] = $uniquelocation[$i];
        }
        $locations = array_unique($locations);
        $locationsNew = [];
        foreach ($locations as $item) {
            $location = Location::find($item);
            $locationsNew[$item] = [$location ? Location::where('id', $item)->value('name') : '-', Ohs::where('department', $dept_id)->where('location', $item)->count()];
        }
        $models = DB::table('modenadisable')->First();

        return view('user.ohsrisk.subfolder')->with([
            'model' => $models,
            'locations' => $locations,
            'locationsNew' => $locationsNew,
            'dept' => $dept,
            'type' => $type,
        ]);
    }

    public function divisionSubFolder($dept, string $type = null)
    {
        session(['objdept' => $dept]);
        $dep = Departments::where('departmentname', $dept)->first();
        $divisions = Division::where('department_id', $dep->id)->get();
        $models = DB::table('modenadisable')->First();

        return view('user.ohsrisk.getDivisionFolder')->with([
            'model' => $models,
            'divisions' => $divisions,
            'type' => $type,
        ]);
    }

    public function subDivisionSubFolder(int $id, $dept, string $type = null)
    {
        session(['objdept' => $dept]);
        $subdivisions = Area::where('division_id', $id)->get();
        $models = DB::table('modenadisable')->First();

        return view('user.ohsrisk.getSubDivisionFolder')->with('model', $models)->with('subdivisions', $subdivisions)->with('type', $type);
    }

    public function add(Request $req, string $type = null)
    {
        $ohsinfo = DB::table('ohsinfo')->get();
        $monitor = DB::table('monitor')->get();
        $departments = DB::table('departments')->get();
        $users = User::where('is_email_verified', 1)->where('approved', 1)->orderBy('name')->get();
        $employees = EmployeeDetails::all();
        $models = DB::table('modenadisable')->First();
        switch ($type) {
            case null:
                $locations = Location::where('locationable_type', 'App\Departments')->get();
                break;
            case 'divisional':
                $locations = Location::where('locationable_type', 'App\Division')->get();
                break;
            case 'subdivisional':
                $locations = Location::where('locationable_type', 'App\Area')->get();
                break;
        }

        return view('user.ohsrisk.add')->with([
            'model' => $models,
            'departments' => $departments,
            'monitor' => $monitor,
            'ohsinfo' => $ohsinfo,
            'users' => $users,
            'employees' => $employees,
            'locations' => $locations,
            'type' => $type,
        ]);
    }

    public function insert(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'department' => 'required',
            'type' => 'required',
            'activity' => 'required',
            'legal' => 'required',
            'location' => 'required',
            'team' => 'required',
            'division_select' => 'nullable|exists:divisions,id',
            'area_select' => 'nullable|exists:areas,id',
        ]);
        $n = count($req->doc);
        $department = $req->input('department');
        $department_name = Departments::where('id', $department)->value('departmentname');
        $division = $req->input('division_select') ?? 0;
        $area = $req->input('area_select') ?? 0;
        $type = $req->input('type');
        $created_at = $req->input('date_created');
        $activity = $req->input('activity');
        $legal = $req->input('legal');
        $loc = $req->input('location');
        $location = ucwords($loc);
        $team = $req->input('team');
        $owner = $req->input('owner');
        $postedby = $req->input('postedby');
        $exist = Ohs::where('department', $department)->orderBy('id', 'desc')->first();
        if ($exist) {
            $c = $exist->count;
            $count = $c + 1;
        } else {
            $count = "1";
        }

        $created_at = date('d-m-Y h:i:s A', time());

        $data = array(
            'created_at'  => $created_at,
            'count'       => $count,
            'department'  => $department,
            'type'        => $type,
            'legal'       => $legal,
            /*'team' => $team,*/
            'location'    => $location,
            'activity'    => $activity,
            'postedby'    => $postedby,
            'division_id' => $division,
            'area_id'     => $area
        );
        Ohs::insert($data);
        $ohs = Ohs::latest('id')->first();
        $currentTime = now();
        $ohsTeamData = [];
        foreach ($team as $t) {
            $ohsTeamData[] = [
                'employee_id' => $t,
                'ohs_id' => $ohs->id,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ];
        }
        if (count($ohsTeamData)) {
            OhsTeam::insert($ohsTeamData);
        }

        $ohsRiskOwnerData = [];
        foreach ($owner as $o) {
            $ohsRiskOwnerData[] = [
                'user_id' => $o,
                'ohs_id' => $ohs->id,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ];
        }
        if (count($ohsRiskOwnerData)) {
            OhsRiskowner::insert($ohsRiskOwnerData);
        }

        $code = DB::table('departments')->where('departmentname', $department)->value('code');
        $code = strtoupper(substr($code, 0, 3));
        $actnum = $code . $count;

        $data = Ohs::orderBy('id', 'desc')->first();
        $Rid = $data->id;
        $d = 0;
        $ohriskExtend = [];
        $data1 = array('actid' => $actnum);
        Ohs::where('count', $count)
            ->where('department', $department)
            ->update($data1);
        foreach ($req->doc as $key => $value) {

            $OrS = $value['OrS'];
            $OrL = $value['OrL'];
            $OrR = $value['OrS'] * $value['OrL'];
            if ($OrR > 0 && $OrR < 4) {
                $category = "Low";
            } elseif ($OrR > 3 && $OrR < 7) {
                $category = "Medium";
            } elseif ($OrR > 7 && $OrR < 17) {
                $category = "High";
            }
            $RS = $value['RS'];
            $RL = $value['RL'];
            $RR = $value['RS'] * $value['RL'];

            if ($d <= $n) {
                $d = $d + 1;
            }

            $ohriskExtend[] = array(
                'ohs_id' => $Rid,
                'department' => $department,
                'R_NR_EM' => $value['R_NR_EM'],
                'hazards' => $value['hazards'],
                'affect' => $value['affect'],
                'control' => $value['control'],
                'OrS' => $value['OrS'],
                'OrL' => $value['OrL'],
                'OrR' => $OrR,
                'additional' => $value['additional'],
                'RS' => $value['RS'],
                'RL' => $value['RL'],
                'RR' => $RR,
                'monitor' => $value['monitor'],
                'created_at' => $created_at,
                'category' => $category,
                'riskid' => $d,
            );
        }

        if (count($ohriskExtend)) {
            Ohsrisk::insert($ohriskExtend);
        }
        //inserting values to activity table
        $activity = "New OHS Risk is Added with Activity Name as " . $activity . " and with Department as " . $department_name;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code

        //Sending mail to risk reviewer(hse).
        $dat = Ohs::orderBy('id', 'desc')->first();
        $maildata = array(
            'actid' => $dat->actid,
        );
        $riskrev = DB::table('hses')->pluck('email');
        try {
            Mail::to($riskrev)->send(new NewOhsRiskAdded($maildata));
        } catch (\Exception $exception) {
            Log::error($exception);
            Log::info('Send mail is not work');
        }

        Session::flash('msg', "OHS Risk added successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    function list($dept, $area, string $type = null) {
        session(['area' => $area]);
        //below is superadmin related codes
        $ohsrisk = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($ohsrisk) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 1, 'documentmanagement' => 0]);
        } else {
            $ohsrisk = DB::table('modpageload')->where('id', 1)->First();
            $ohsrisk->ohsrisk = $ohsrisk->ohsrisk + 1;
            DB::table('modpageload')->update(['ohsrisk' => $ohsrisk->ohsrisk]);
        }
        //end of superadmin codes
        //Assessment

        $ID = Auth::user()->employeeid;
        $mr = DB::table('representatives')->pluck('email');
        $poss = DB::table('users')->where('employeeid', $ID)->get();
        foreach ($poss as $dat) {
            $e = $dat->email;
        }
        $e = Auth::user()->email; // added this

        $dep = DB::table('departments')->where('hod', $e)->pluck('id')->toArray();
        $dept_id = Departments::where('departmentname', $dept)->value('id');
        $data1 = Ohs::where('department', $dept_id)
            ->where('location', $area)
            ->get();
        $data1_ids =
        Ohs::where('department', $dept_id)
            ->where('location', $area)
            ->pluck('id')->toArray();
        $data1_ids = implode(',', $data1_ids);

        $data2 = Ohs::select('ohs.id',
        'ohs.count',
        'ohs.actid',
        'ohs.risk_status',
        'ohs.context_id',
        'ohs.department',
        'ohs.division_id',
        'ohs.area_id',
        'ohs.type',
        'ohs.activity',
        'ohs.legal',
        'ohs.location',
        'ohs.team',
        'ohs.proposechange',
        'ohs.postedby',
        'ohs.created_at',
        'ohs.reviewed_at as reviewed',
        'ohs.last_review_date',
        'ohs.approvedby',
        'ohsrisk.id',
        'ohsrisk.ohs_id',
        'ohsrisk.riskid',
        'ohsrisk.department',
        'ohsrisk.risk_status',
        'ohsrisk.context_id',
        'ohsrisk.active',
        'ohsrisk.category',
        'ohsrisk.R_NR_EM',
        'ohsrisk.hazards',
        'ohsrisk.affect',
        'ohsrisk.control',
        'ohsrisk.OrS',
        'ohsrisk.OrL',
        'ohsrisk.OrR',
        'ohsrisk.additional',
        'ohsrisk.RS',
        'ohsrisk.RL',
        'ohsrisk.RR',
        'ohsrisk.monitor',
        'ohsrisk.proc_no',
        'ohsrisk.procedur',
        'ohsrisk.reviewed_at',
        'ohsrisk.approvedby',
        'ohsrisk.time',
        'ohsrisk.updated_at',
        )
            ->join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->get();
        $data1IdsForDataFilter = $data1->pluck('id')->toArray();
        $data2 = $data2->whereIn('ohs_id', $data1IdsForDataFilter);
        $hses = DB::table('hses')->pluck('email');
        $departments = Departments::all();
        $categories = Ohsrisk::select('category')->distinct()->get();
        $locations = Ohs::select('location')->distinct()->get();

        $users = User::where('is_email_verified', 1)->where('approved', 1)->get();
        $models = DB::table('modenadisable')->First();

        return view('user.ohsrisk.list')
            ->with('data2', $data2)
            ->withDepartments($departments)
            ->withCategories($categories)
            ->withLocations($locations)
            ->with('dep', $dep)
            ->with('dept', $dept)
            ->with('hses', $hses)
            ->with('model', $models)
            ->with('data1', $data1)
            ->with('ids', $data1_ids)
            ->with('type', $type)
            ->with('users', $users)
            ->withMr($mr);
    }
    public function list2($location)
    {
        //below is superadmin related codes
        $ohsrisk = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($ohsrisk) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 1, 'documentmanagement' => 0]);
        } else {
            $ohsrisk = DB::table('modpageload')->where('id', 1)->First();
            $ohsrisk->ohsrisk = $ohsrisk->ohsrisk + 1;
            DB::table('modpageload')->update(['ohsrisk' => $ohsrisk->ohsrisk]);
        }
        //end of superadmin codes
        //Assessment

        $ID = Auth::user()->employeeid;
        $mr = DB::table('representatives')->pluck('email');
        $poss = DB::table('users')->where('employeeid', $ID)->get();
        foreach ($poss as $dat) {
            $e = $dat->email;
        }
        $e = Auth::user()->email; // added this

        $dep = DB::table('departments')->where('hod', $e)->get('departmentname');
        $data1 = Ohs
            ::where('location', $location)
            ->get();
        $data2 = Ohs
            ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->select(
                'ohsrisk.ohs_id',
                'ohsrisk.id',
                'ohs.risk_status',
                'ohsrisk.category',
                'ohsrisk.R_NR_EM',
                'ohsrisk.hazards',
                'ohsrisk.affect',
                'ohsrisk.control',
                'ohs.actid',
                'ohs.department',
                'ohs.type',
                'ohs.proposechange',
                'ohs.postedby',
                'ohs.team',
                'ohs.location',
                'ohs.legal',
                'ohs.activity',
                'ohs.approvedby',
                'ohsrisk.monitor',
                'ohsrisk.OrS',
                'ohsrisk.OrL',
                'ohsrisk.OrR',
                'ohsrisk.additional',
                'ohsrisk.RS',
                'ohsrisk.RL',
                'ohsrisk.RR',
                'ohsrisk.proc_no',
                'ohsrisk.procedur',
                'ohsrisk.created_at',
                'ohsrisk.reviewed_at',
                'ohsrisk.riskid'
            )
            ->get();

        $hses = DB::table('hses')->pluck('email');
        $departments = Departments::all();
        $categories = Ohsrisk::select('category')->distinct()->get();
        $locations = Ohs::select('location')->distinct()->get();

        $models = DB::table('modenadisable')->First();
        return view('user.ohsrisk.list')
            ->with('data2', $data2)
            ->withDepartments($departments)
            ->withCategories($categories)
            ->withLocations($locations)
            ->with('dep', $dep)
            ->with('hses', $hses)
            ->with('model', $models)
            ->with('data1', $data1)
            ->withMr($mr);
    }
    public function masterlist(string $type = null)
    {
        //Register
        $ID = Auth::user()->employeeid;
        $poss = DB::table('users')->where('employeeid', $ID)->get();
        foreach ($poss as $dat) {
            $e = $dat->email;
        }
        $hses = DB::table('hses')->pluck('email');
        $dep = DB::table('departments')->where('hod', $e)->get('departmentname');
        $mr = DB::table('representatives')->pluck('email');
        $data1 = Ohs
            ::orderBy('department')
            ->get();
        $data3 = Ohs
            ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->select(
                'ohsrisk.ohs_id',
                'ohsrisk.id',
                'ohs.risk_status',
                'ohsrisk.category',
                'ohsrisk.R_NR_EM',
                'ohsrisk.hazards',
                'ohsrisk.affect',
                'ohsrisk.control',
                'ohs.actid',
                'ohs.department',
                'ohs.type',
                'ohs.proposechange',
                'ohs.postedby',
                'ohs.team',
                'ohs.location',
                'ohs.legal',
                'ohs.activity',
                'ohs.approvedby',
                'ohsrisk.monitor',
                'ohsrisk.OrS',
                'ohsrisk.OrL',
                'ohsrisk.OrR',
                'ohsrisk.additional',
                'ohsrisk.RS',
                'ohsrisk.RL',
                'ohsrisk.RR',
                'ohsrisk.proc_no',
                'ohsrisk.procedur',
                'ohsrisk.created_at',
                'ohsrisk.reviewed_at',
                'ohsrisk.riskid'
            )
            ->wherein('ohsrisk.category', ['High', 'Medium'])
            ->get();
        //below code is to identify the mideum and high risks from ohsrisk table which belongs to ohs table
        $data_1 = [];
        foreach ($data1 as $d1) {
            foreach ($data3 as $d3) {
                if ($d3->id == $d1->id) {
                    $data_1[] = $d1;
                }
            }
        }

        $models = DB::table('modenadisable')->First();
        return view('user.ohsrisk.masterlist')
            ->with('dep', $dep)
            ->with('hses', $hses)
            ->with('data3', $data3)
            ->with('data1', $data1)
            ->with('data_1', $data_1)
            ->with('model', $models)
            ->with('type', $type)
            ->withMr($mr);
    }

    public function analysis()
    {

        $chart = new OhsRiskAnalysis;
        $chart->title("Ohs Risk Summary-Department Wise");
        $names = DB::select("select departmentname from departments order by departmentname ASC");
        $x = [];
        foreach ($names as $key) {
            array_push($x, $key->departmentname);
        }
        $chart->labels($x);
        $num = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='High' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");

        $num1 = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='Medium' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");

        $num2 = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='Low' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");
        $total = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and r.active='Yes'
                            group by s.departmentname
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
        $chart->dataset('High', 'bar', $a)->backgroundcolor('#f7533e')->color('#f7533e');
        $chart->dataset('Medium', 'bar', $b)->backgroundcolor('#FFD700')->color('#FFD700');
        $chart->dataset('Low', 'bar', $c)->backgroundcolor('#a6ff00')->color('#a6ff00');
        $chart->dataset('Total', 'bar', $c)->backgroundcolor('#575553')->color('#575553');
        $chart->height(400);

        $processes = DB::select("select departmentname from departments order by departmentname ASC");
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->departmentname);
        }

        $phigh = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='High' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");

        $pmedium = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='Medium' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");

        $plow = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='Low' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                           ");

        $highp = [];
        foreach ($phigh as $key) {
            array_push($highp, $key->number);
        }
        $mediump = [];
        foreach ($pmedium as $key) {
            array_push($mediump, $key->number);
        }
        $lowp = [];
        foreach ($plow as $key) {
            array_push($lowp, $key->number);
        }

        $chart2 = new OhsRiskAnalysis;
        $chart2->title("Ohs Risk Summary(High)-Department Wise");
        $chart2->labels($process);
        $chart2->dataset('High', 'bar', $highp)->backgroundcolor('#f7533e')->color('#f7533e');
        $chart2->height(200);

        $chart3 = new OhsRiskAnalysis;
        $chart3->title("Ohs Risk Summary(Medium)-Department Wise");
        $chart3->labels($process);
        $chart3->dataset('Medium', 'bar', $mediump)->backgroundcolor('#FFD700')->color('#FFD700');
        $chart3->height(200);

        $chart4 = new OhsRiskAnalysis;
        $chart4->title("Ohs Risk Summary(Low)-Department Wise");
        $chart4->labels($process);
        $chart4->dataset('Low', 'bar', $lowp)->backgroundcolor('#baeb34')->color('#baeb34');
        $chart4->height(200);

        $models = DB::table('modenadisable')->First();
        return view('user.ohsrisk.analysis')
            ->with('chart', $chart)
            ->with('chart2', $chart2)
            ->with('chart3', $chart3)
            ->with('chart4', $chart4)
            ->with('model', $models);
    }

    public function filtr(Request $req)
    {
        $e = Auth::user()->email;
        $dep = DB::table('departments')->where('hod', $e)->get('departmentname');

        $dept = null;
        $hses = DB::table('hses')->pluck('email');
        $departments = Departments::all();
        $categories = Ohsrisk::select('category')->distinct()->get();
        $locations = Ohs::select('location')->distinct()->get();
        $mr = DB::table('representatives')->pluck('email');
        $dept = $req->input('dept');
        $cate = $req->input('category');
        $loc = $req->input('location');

        $data1 = Ohs
            ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->select(
                'ohs.id',
                'ohs.risk_status',
                'ohs.actid',
                'ohs.department',
                'ohs.type',
                'ohs.proposechange',
                'ohs.postedby',
                'ohs.team',
                'ohs.location',
                'ohs.legal',
                'ohs.activity',
                'ohs.approvedby'
            )
            ->where('ohsrisk.department', $dept)
            ->orwhere('ohsrisk.category', '=', $cate)
            ->orwhere('ohs.location', '=', $loc)
            // ->orderBy('ohs.created_at','desc')
            ->orderBy('department')
            ->distinct()
            ->paginate(2000);
        //dd($data1)    ;
        $data2 = Ohs
            ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->select(
                'ohsrisk.ohs_id',
                'ohsrisk.id',
                'ohs.risk_status',
                'ohsrisk.category',
                'ohsrisk.R_NR_EM',
                'ohsrisk.hazards',
                'ohsrisk.affect',
                'ohsrisk.control',
                'ohs.actid',
                'ohs.department',
                'ohs.type',
                'ohs.proposechange',
                'ohs.postedby',
                'ohs.team',
                'ohs.location',
                'ohs.legal',
                'ohs.activity',
                'ohs.approvedby',
                'ohsrisk.monitor',
                'ohsrisk.OrS',
                'ohsrisk.OrL',
                'ohsrisk.OrR',
                'ohsrisk.additional',
                'ohsrisk.RS',
                'ohsrisk.RL',
                'ohsrisk.RR',
                'ohsrisk.proc_no',
                'ohsrisk.procedur',
                'ohsrisk.created_at',
                'ohsrisk.reviewed_at',
                'ohsrisk.riskid'
            )
            ->where('ohsrisk.department', $dept)
            ->orwhere('ohsrisk.category', '=', $cate)
            ->orwhere('ohs.location', '=', $loc)
            ->distinct()
            ->paginate(2000);

        $data1->appends(array(
            'department' => $req->input('dept'),
            'category' => $req->input('cate'),
            'location' => $req->input('loc'),
        ));

        //--------------------------Register
        $ID = Auth::user()->employeeid;
        $poss = DB::table('users')->where('employeeid', $ID)->get();
        foreach ($poss as $dat) {
            $e = $dat->email;
        }
        $e = Auth::user()->email; // added this
        $dep = DB::table('departments')->where('hod', $e)->get('departmentname');
        $data3 = Ohs
            ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->select(
                'ohsrisk.ohs_id',
                'ohsrisk.id',
                'ohs.risk_status',
                'ohsrisk.category',
                'ohsrisk.R_NR_EM',
                'ohsrisk.hazards',
                'ohsrisk.affect',
                'ohsrisk.control',
                'ohs.actid',
                'ohs.department',
                'ohs.type',
                'ohs.proposechange',
                'ohs.postedby',
                'ohs.team',
                'ohs.location',
                'ohs.legal',
                'ohs.activity',
                'ohs.approvedby',
                'ohsrisk.monitor',
                'ohsrisk.OrS',
                'ohsrisk.OrL',
                'ohsrisk.OrR',
                'ohsrisk.additional',
                'ohsrisk.RS',
                'ohsrisk.RL',
                'ohsrisk.RR',
                'ohsrisk.proc_no',
                'ohsrisk.procedur',
                'ohsrisk.created_at',
                'ohsrisk.reviewed_at',
                'ohsrisk.riskid'
            )
            ->wherein('ohsrisk.category', ['High', 'Medium'])
            ->paginate(2000);

        //---------------------Analysis

        $chart = new OhsRiskAnalysis;
        $chart->title("Ohs Risk Summary-Department Wise");
        $names = DB::select("select departmentname from departments order by departmentname ASC");
        $x = [];
        foreach ($names as $key) {
            array_push($x, $key->departmentname);
        }
        $chart->labels($x);
        $num = DB::select("select s.departmentname,COUNT(r.category)AS number
                    FROM  ohsrisk AS r
                    right JOIN departments AS s
                    ON r.department= s.id
                    and  r.category='High' and r.active='Yes'
                    group by s.departmentname
                    order by s.departmentname ASC
                   ");

        $num1 = DB::select("select s.departmentname,COUNT(r.category)AS number
                    FROM  ohsrisk AS r
                    right JOIN departments AS s
                    ON r.department= s.id
                    and  r.category='Medium' and r.active='Yes'
                    group by s.departmentname
                    order by s.departmentname ASC
                   ");

        $num2 = DB::select("select s.departmentname,COUNT(r.category)AS number
                    FROM  ohsrisk AS r
                    right JOIN departments AS s
                    ON r.department= s.id
                    and  r.category='Low' and r.active='Yes'
                    group by s.departmentname
                    order by s.departmentname ASC
                   ");
        $total = DB::select("select s.departmentname,COUNT(r.category)AS number
                    FROM  ohsrisk AS r
                    right JOIN departments AS s
                    ON r.department= s.id
                    and r.active='Yes'
                    group by s.departmentname
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
        $chart->dataset('High', 'bar', $a)->backgroundcolor('#f7533e')->color('#f7533e');
        $chart->dataset('Medium', 'bar', $b)->backgroundcolor('#FFD700')->color('#FFD700');
        $chart->dataset('Low', 'bar', $c)->backgroundcolor('#a6ff00')->color('#a6ff00');
        $chart->dataset('Total', 'bar', $c)->backgroundcolor('#575553')->color('#575553');
        $chart->height(400);

        $processes = DB::select("select departmentname from departments order by departmentname ASC");
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->departmentname);
        }

        $phigh = DB::select("select s.departmentname,COUNT(r.category)AS number
                    FROM  ohsrisk AS r
                    right JOIN departments AS s
                    ON r.department= s.id
                    and  r.category='High' and r.active='Yes'
                    group by s.departmentname
                    order by s.departmentname ASC
                   ");

        $pmedium = DB::select("select s.departmentname,COUNT(r.category)AS number
                    FROM  ohsrisk AS r
                    right JOIN departments AS s
                    ON r.department= s.id
                    and  r.category='Medium' and r.active='Yes'
                    group by s.departmentname
                    order by s.departmentname ASC
                   ");

        $plow = DB::select("select s.departmentname,COUNT(r.category)AS number
                    FROM  ohsrisk AS r
                    right JOIN departments AS s
                    ON r.department= s.id
                    and  r.category='Low' and r.active='Yes'
                    group by s.departmentname
                    order by s.departmentname ASC
                   ");

        $highp = [];
        foreach ($phigh as $key) {
            array_push($highp, $key->number);
        }
        $mediump = [];
        foreach ($pmedium as $key) {
            array_push($mediump, $key->number);
        }
        $lowp = [];
        foreach ($plow as $key) {
            array_push($lowp, $key->number);
        }

        $chart2 = new OhsRiskAnalysis;
        $chart2->title("Ohs Risk Summary(High)-Department Wise");
        $chart2->labels($process);
        $chart2->dataset('High', 'bar', $highp)->backgroundcolor('#f7533e')->color('#f7533e');
        $chart2->height(200);

        $chart3 = new OhsRiskAnalysis;
        $chart3->title("Ohs Risk Summary(Medium)-Department Wise");
        $chart3->labels($process);
        $chart3->dataset('Medium', 'bar', $mediump)->backgroundcolor('#FFD700')->color('#FFD700');
        $chart3->height(200);

        $chart4 = new OhsRiskAnalysis;
        $chart4->title("Ohs Risk Summary(Low)-Department Wise");
        $chart4->labels($process);
        $chart4->dataset('Medium', 'bar', $lowp)->backgroundcolor('#baeb34')->color('#baeb34');
        $chart4->height(200);

        $models = DB::table('modenadisable')->First();

        return view('user.ohsrisk.list')->with('data2', $data2)
            ->withDepartments($departments)
            ->withCategories($categories)
            ->withLocations($locations)
            ->with('dep', $dep)
            ->with('hses', $hses)
            ->with('model', $models)

            ->with('data3', $data3)

            ->with('chart', $chart)
            ->with('chart2', $chart2)
            ->with('chart3', $chart3)
            ->with('chart4', $chart4)

            ->with('data1', $data1)
            ->with('data_1', $data1)
            ->withMr($mr);
    }

    public function masterfiltr(Request $req)
    {
        $ID = Auth::user()->employeeid;
        $poss = DB::table('users')->where('employeeid', $ID)->get();
        foreach ($poss as $dat) {
            $e = $dat->email;
        }
        $e = Auth::user()->email;
        $dep = DB::table('departments')->where('hod', $e)->get('departmentname');
        $dept = null;
        $hses = DB::table('hses')->pluck('email');

        $departments = Departments::all();
        $mr = DB::table('representatives')->pluck('email');
        $categories = Ohsrisk::select('category')->distinct()->get();
        $locations = Ohs::select('location')->distinct()->get();
        $dept = $req->input('dept');
        $cate = $req->input('category');
        $loc = $req->input('location');

        $data1 = Ohs
            ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->select(
                'ohs.id',
                'ohs.risk_status',
                'ohs.actid',
                'ohs.department',
                'ohs.type',
                'ohs.proposechange',
                'ohs.postedby',
                'ohs.team',
                'ohs.location',
                'ohs.legal',
                'ohs.activity',
                'ohs.approvedby'
            )
            ->orderBy('ohs.created_at', 'desc')
            ->distinct()
            ->paginate(2000);

        $data2 = Ohs
            ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->select(
                'ohsrisk.id',
                'ohsrisk.ohs_id',
                'ohs.risk_status',
                'ohsrisk.category',
                'ohsrisk.R_NR_EM',
                'ohsrisk.hazards',
                'ohsrisk.affect',
                'ohsrisk.control',
                'ohs.actid',
                'ohs.department',
                'ohs.type',
                'ohs.proposechange',
                'ohs.postedby',
                'ohs.team',
                'ohs.location',
                'ohs.legal',
                'ohs.activity',
                'ohs.approvedby',
                'ohsrisk.monitor',
                'ohsrisk.OrS',
                'ohsrisk.OrL',
                'ohsrisk.OrR',
                'ohsrisk.additional',
                'ohsrisk.RS',
                'ohsrisk.RL',
                'ohsrisk.RR',
                'ohsrisk.proc_no',
                'ohsrisk.procedur',
                'ohsrisk.created_at',
                'ohsrisk.reviewed_at',
                'ohsrisk.riskid'
            )
            ->where('ohsrisk.category', '!=', 'Low')
            ->distinct()
            ->paginate(2000);

        //--------------------------Register
        $ID = Auth::user()->employeeid;
        $poss = DB::table('users')->where('employeeid', $ID)->get();
        foreach ($poss as $dat) {
            $e = $dat->email;
        }
        $e = Auth::user()->email; // added this
        $dep = DB::table('departments')->where('hod', $e)->get('departmentname');

        $data_1 = Ohs
            ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->select(
                'ohs.id',
                'ohs.risk_status',
                'ohs.actid',
                'ohs.department',
                'ohs.type',
                'ohs.proposechange',
                'ohs.postedby',
                'ohs.team',
                'ohs.location',
                'ohs.legal',
                'ohs.activity',
                'ohs.approvedby'
            )
            ->where('ohsrisk.department', $dept)
            ->orwhere('ohsrisk.category', '=', $cate)
            ->orwhere('ohs.location', '=', $loc)
            ->orderBy('department')
            ->distinct()
            ->paginate(2000);
        $data3 = Ohs
            ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->select(
                'ohsrisk.ohs_id',
                'ohsrisk.id',
                'ohs.risk_status',
                'ohsrisk.category',
                'ohsrisk.R_NR_EM',
                'ohsrisk.hazards',
                'ohsrisk.affect',
                'ohsrisk.control',
                'ohs.actid',
                'ohs.department',
                'ohs.type',
                'ohs.proposechange',
                'ohs.postedby',
                'ohs.team',
                'ohs.location',
                'ohs.legal',
                'ohs.activity',
                'ohs.approvedby',
                'ohsrisk.monitor',
                'ohsrisk.OrS',
                'ohsrisk.OrL',
                'ohsrisk.OrR',
                'ohsrisk.additional',
                'ohsrisk.RS',
                'ohsrisk.RL',
                'ohsrisk.RR',
                'ohsrisk.proc_no',
                'ohsrisk.procedur',
                'ohsrisk.created_at',
                'ohsrisk.reviewed_at',
                'ohsrisk.riskid'
            )
            ->wherein('ohsrisk.category', ['High', 'Medium'])
            ->where('ohsrisk.department', $dept)
            ->orwhere('ohsrisk.category', '=', $cate)
            ->orwhere('ohs.location', '=', $loc)
            ->paginate(2000);

        $data3->appends(array(
            'department' => $req->input('dept'),
            'category' => $req->input('cate'),
            'location' => $req->input('loc'),
        ));

        //---------------------Analysis

        $chart = new OhsRiskAnalysis;
        $chart->title("Ohs Risk Summary-Department Wise");
        $names = DB::select("select departmentname from departments order by departmentname ASC");
        $x = [];
        foreach ($names as $key) {
            array_push($x, $key->departmentname);
        }
        $chart->labels($x);
        $num = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='High' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                        ");

        $num1 = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='Medium' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                        ");

        $num2 = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='Low' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                        ");
        $total = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and r.active='Yes'
                            group by s.departmentname
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
        $chart->dataset('High', 'bar', $a)->backgroundcolor('#f7533e')->color('#f7533e');
        $chart->dataset('Medium', 'bar', $b)->backgroundcolor('#FFD700')->color('#FFD700');
        $chart->dataset('Low', 'bar', $c)->backgroundcolor('#a6ff00')->color('#a6ff00');
        $chart->dataset('Total', 'bar', $c)->backgroundcolor('#575553')->color('#575553');
        $chart->height(400);

        $processes = DB::select("select departmentname from departments order by departmentname ASC");
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->departmentname);
        }

        $phigh = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='High' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                        ");

        $pmedium = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='Medium' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                        ");

        $plow = DB::select("select s.departmentname,COUNT(r.category)AS number
                            FROM  ohsrisk AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.category='Low' and r.active='Yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                        ");

        $highp = [];
        foreach ($phigh as $key) {
            array_push($highp, $key->number);
        }
        $mediump = [];
        foreach ($pmedium as $key) {
            array_push($mediump, $key->number);
        }
        $lowp = [];
        foreach ($plow as $key) {
            array_push($lowp, $key->number);
        }

        $chart2 = new OhsRiskAnalysis;
        $chart2->title("Ohs Risk Summary(High)-Department Wise");
        $chart2->labels($process);
        $chart2->dataset('High', 'bar', $highp)->backgroundcolor('#f7533e')->color('#f7533e');
        $chart2->height(200);

        $chart3 = new OhsRiskAnalysis;
        $chart3->title("Ohs Risk Summary(Medium)-Department Wise");
        $chart3->labels($process);
        $chart3->dataset('Medium', 'bar', $mediump)->backgroundcolor('#FFD700')->color('#FFD700');
        $chart3->height(200);

        $chart4 = new OhsRiskAnalysis;
        $chart4->title("Ohs Risk Summary(Low)-Department Wise");
        $chart4->labels($process);
        $chart4->dataset('Medium', 'bar', $lowp)->backgroundcolor('#baeb34')->color('#baeb34');
        $chart4->height(200);

        $models = DB::table('modenadisable')->First();

        return view('user.ohsrisk.list')->withDepartments($departments)
            ->withCategories($categories)
            ->withLocations($locations)
            ->with('dep', $dep)
            ->with('hses', $hses)
            ->with('model', $models)
            ->with('chart', $chart)
            ->with('chart2', $chart2)
            ->with('chart3', $chart3)
            ->with('chart4', $chart4)
            ->with('data1', $data1)
            ->with('data2', $data2)
            ->with('data_1', $data_1)
            ->with('data3', $data3)
            ->withMr($mr);
    }

    public function addmore(string $type = null)
    {
        if (isset($_GET['val'])) {
            $ohsinfo = DB::table('ohsinfo')->get();
            $Rid = $_GET['val'];
            $data = Ohs::where('id', $Rid)->get();
            $models = DB::table('modenadisable')->First();
            return view('user/ohsrisk/addmore')->withData($data)->withRid($Rid)->with('model', $models)->withOhsinfo($ohsinfo)->with('type', $type);
        }
    }

    public function insertmore(Request $req)
    {
        $m = count($req->doc);
        $department = $req->input('department');
        $department_id = Departments::where('departmentname', $department)->value('id');
        $type = $req->input('type');
        $created_at = $req->input('date_created');
        $activity = $req->input('activity');
        $legal = $req->input('legal');
        $location = $req->input('location');
        $team = $req->input('team');
        $postedby = $req->input('postedby');
        $Rid = $req->input('Rid');

        date_default_timezone_set('Asia/Kuwait');
        $created_at = date('d-m-Y h:i:s A', time());
        $data = array(
            'created_at' => $created_at,
            'department' => $department,
            'type' => $type,
            'legal' => $legal,
            'team' => $team,
            'location' => $location,
            'activity' => $activity,
            'postedby' => $postedby,
            'created_at' => $created_at,
            'risk_status' => 'Review Pending',
        );

        $d = Ohsrisk::where('ohs_id', $Rid)->latest('riskid')->pluck('riskid')->first();
        $n = $m + $d;
        foreach ($req->doc as $key => $value) {
            $OrS = $value['OrS'];
            $OrL = $value['OrL'];
            $OrR = $value['OrS'] * $value['OrL'];
            if ($OrR > 0 && $OrR < 4) {
                $category = "Low";
            } elseif ($OrR > 3 && $OrR < 7) {
                $category = "Medium";
            } elseif ($OrR > 7 && $OrR < 17) {
                $category = "High";
            }
            $RS = $value['RS'];
            $RL = $value['RL'];
            $RR = $value['RS'] * $value['RL'];

            if ($d <= $n) {
                $d = $d + 1;
            }

            $data1 = array(
                'ohs_id' => $Rid,
                'department' => $department_id,
                'R_NR_EM' => $value['R_NR_EM'],
                'hazards' => $value['hazards'],
                'affect' => $value['affect'],
                'control' => $value['control'],
                'OrS' => $value['OrS'],
                'OrL' => $value['OrL'],
                'additional' => $value['additional'],
                'RS' => $value['RS'],
                'RL' => $value['RL'],
                'monitor' => $value['monitor'],
                'OrR' => $OrR,
                'RR' => $RR,
                'created_at' => $created_at,
                'category' => $category,
                'riskid' => $d,
            );
            Ohsrisk::insert($data1);
        }
        //inserting values to activity table
        $activity = "New Risk is Added to the existing Activity " . $activity . " where Activity Id is " . $Rid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        Session::flash('msg', "Request sent for approval Successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    public function action(Request $req, string $type = null)
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = Ohsrisk::where('id', $id)->get();
            $models = DB::table('modenadisable')->First();
            return view('user.ohsrisk.action')->withId($id)->withData($data)->with('model', $models)->with('type', $type);
        }
    }

    public function control_pro(Request $req)
    {
        $proc_no = $req->input('proc_no');
        $procedure = $req->input('procedure');
        $id = $req->input('id');
        $ohs_id = Ohsrisk::where('id', $id)->value('ohs_id');
        $location = Ohs::where('id', $ohs_id)->value('location');
        $department_id = Ohs::where('id', $ohs_id)->value('department');
        $department_name = Departments::where('id', $department_id)->value('departmentname');

        $reviewed_at = date('Y-m-d H:i:s', time());

        $data = array(
            'reviewed_at' => $reviewed_at,
            'proc_no' => $proc_no,
            'procedur' => $procedure,
        );

        Ohsrisk::where('id', $id)
            ->update($data);
        //inserting values to activity table
        $Rid = Ohsrisk::where('id', $id)->pluck('ohs_id');
        $activity = "Control Procedure is Added to the OHS Risk where id is " . $id . " and  where Activity Id is " . $Rid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code

        $models = DB::table('modenadisable')->First();
        return redirect('/user/ohsrisk/list/' . $department_name . '/' . $location)->with('model', $models);
    }

    public function adds(string $type = null)
    {
        if (isset($_GET['val'])) {
            $Rid = $_GET['val'];

            $data = Ohs::where('id', $Rid)->get();
            $data1 = Ohsrisk::where('ohs_id', $Rid)->get();
            $mn = DB::table('departments')->get();
            $models = DB::table('modenadisable')->First();
            return view('user/ohsrisk/review')->with('model', $models)->withData($data)->withMn($mn)->with('Rid', $Rid)->withData1($data1)->with('type', $type);
        }
    }

    public function updateinitial(Request $req)
    {

        $p = $req->input('proposal');
        $reviewed = $req->input('question');
        $m = count($req->R_NR_EM);
        $approvedby = Auth::user()->employeeid;
        $Rid = $req->input('posted');
        $slno = $req->input('slno');

        if ($reviewed == 2) {
            $risk_status = 'Reviewed';
            Ohs::where('id', $Rid)
                ->update(array('risk_status' => $risk_status, 'approvedby' => $approvedby, 'last_review_date' => now()));
            Ohsrisk::where('ohs_id', $Rid)
                ->update(array('approvedby' => $approvedby));

            $data1 = Ohs::where('id', $Rid)->get();
            foreach ($data1 as $dat) {
                $maildata = array(
                    'actid' => $dat->actid,
                    'department' => $dat->department,
                    'type' => $dat->type,
                );
                //Sending reviewed mail to posted user.
                $user = DB::table('users')->where('employeeid', $dat->postedby)->pluck('email');
                foreach ($user as $u) {
                    Mail::to($u)->send(new OhsRiskReviewed($maildata));
                }
                //Sending new request mail to HOD.
                $hod = DB::table('departments')->where('departmentname', $dat->department)->pluck('hod');
                foreach ($hod as $h) {
                    Mail::to($h)->send(new OhsRiskReviewed($maildata));
                }
            }
            //inserting values to activity table
            $activity = "OHS Approved and Status is updated to Reviewed  where Activity Id is " . $Rid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
        } else {
            $risk_status = 'Rejected';
        }

        Ohs::where('id', $Rid)
            ->update(array('risk_status' => $risk_status, 'proposechange' => $p, 'last_review_date' => now()));
        //inserting values to activity table
        $activity = "OHS DisApproved and Status is updated to Rejected  where Activity Id is " . $Rid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        $data1 = Ohsrisk::where('ohs_id', $Rid)->get();
        $n = 0;
        foreach ($data1 as $dat) { //dd($data1)         ;
            foreach ($slno as $s) { //dd($dat->id)         ;
                if ($dat->id == $s && $n < $m) {

                    $RR[$n] = $req->RS[$n] * $req->RL[$n];
                    if ($RR[$n] > 0 && $RR[$n] < 4) {
                        $category[$n] = "Low";
                    } elseif ($RR[$n] > 3 && $RR[$n] < 7) {
                        $category[$n] = "Medium";
                    } elseif ($RR[$n] > 7 && $RR[$n] < 17) {
                        $category[$n] = "High";
                    }
                    Ohsrisk::where('id', $s)
                        ->update(array(
                            'R_NR_EM' => $req->R_NR_EM[$n],
                            'hazards' => $req->hazards[$n],
                            'affect' => $req->affect[$n],
                            'control' => $req->control[$n],
                            'OrS' => $req->OrS[$n],
                            'OrL' => $req->OrL[$n],
                            'OrR' => $req->OrR[$n],
                            'additional' => $req->additional[$n],
                            'RS' => $req->RS[$n],
                            'RL' => $req->RL[$n],
                            'RR' => $RR[$n],
                            'monitor' => $req->monitor[$n],
                            'category' => $category[$n],
                            'risk_status' => 'Reviewed',
                        ));
                }
            }
            $n = $n + 1;
        }

        $models = DB::table('modenadisable')->First();

        return redirect('/user/ohsrisk/list/' . $req->department . '/' . $req->location_hidden)->withReviewed_at('reviewed_at')->with('model', $models);
    }

    public function addsecondary(string $type = null)
    {
        if (isset($_GET['val'])) {
            $Rid = $_GET['val'];
            $data = Ohs::where('id', $Rid)->get();
            $data1 = Ohsrisk::where('ohs_id', $Rid)->get();
            $mn = DB::table('departments')->get();
            $models = DB::table('modenadisable')->First();
            return view('user/ohsrisk/secondaryreview')->with('model', $models)->withData($data)->withMn($mn)->with('Rid', $Rid)->withData1($data1)->with('type', $type);
        }
    }

    public function updatesecondary(Request $req)
    {
        $m = count($req->R_NR_EM);
        $approvedby = Auth::user()->employeeid;
        $reviewed = $req->input('question');
        $Rid = $req->input('posted');
        $slno = $req->input('slno');
        date_default_timezone_set('Asia/Kuwait');
        $reviewed_at = date('d-m-Y h:i:s A', time());

        if ($reviewed == 2) {
            $risk_status = 'Reviewed';
            Ohs::where('id', $Rid)
                ->update(array('risk_status' => $risk_status, 'reviewed_at' => $reviewed_at));
            //inserting values to activity table
            $activity = "OHS done with Secondary Reviewed and Status is updated to Reviewed  where Activity Id is " . $Rid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code

        } else {
            $risk_status = 'Rejected';
        }

        Ohs::where('id', $Rid)
            ->update(array('risk_status' => $risk_status, 'reviewed_at' => $reviewed_at));
        //inserting values to activity table
        $activity = "OHS DisApproved and Status is updated to Rejected  where Activity Id is " . $Rid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        $data1 = Ohsrisk::where('ohs_id', $Rid)->get();
        $n = 0;
        foreach ($data1 as $dat) {
            foreach ($slno as $s) {
                if ($dat->id == $s && $n < $m) {
                    $OrR[$n] = $req->OrS[$n] * $req->OrL[$n];

                    $RR[$n] = $req->RS[$n] * $req->RL[$n];
                    if ($RR[$n] > 0 && $RR[$n] < 4) {
                        $category[$n] = "Low";
                    } elseif ($RR[$n] > 3 && $RR[$n] < 7) {
                        $category[$n] = "Medium";
                    } elseif ($RR[$n] > 7 && $RR[$n] < 17) {
                        $category[$n] = "High";
                    }
                    Ohsrisk::where('id', $s)
                        ->update(array(
                            'R_NR_EM' => $req->R_NR_EM[$n],
                            'hazards' => $req->hazards[$n],
                            'affect' => $req->affect[$n],
                            'control' => $req->control[$n],
                            'OrS' => $req->OrS[$n],
                            'OrL' => $req->OrL[$n],
                            'OrR' => $OrR[$n],
                            'additional' => $req->additional[$n],
                            'RS' => $req->RS[$n],
                            'RL' => $req->RL[$n],
                            'RR' => $RR[$n],
                            'monitor' => $req->monitor[$n],
                            'category' => $category[$n],
                            'risk_status' => 'Reviewed',
                        ));
                }
            }
            $n = $n + 1;
        }
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    public function resubmit(string $pagetype = null)
    {
        if (isset($_GET['val'])) {
            $Rid = $_GET['val'];

            $data = Ohs::where('id', $Rid)->get();
            $data1 = Ohsrisk::where('ohs_id', $Rid)->get();
            $mn = DB::table('departments')->get();
            $departments = Departments::all();
            $users = User::all();
            foreach ($data as $dat) {
                $dep = $dat->dept ? $dat->dept->departmentname : '-';
                $type = $dat->type;
            }
            $models = DB::table('modenadisable')->First();
            return view('user/ohsrisk/resubmit')->withDep($dep)
                ->withType($type)
                ->withDepartments($departments)
                ->withData($data)
                ->withMn($mn)
                ->with('Rid', $Rid)
                ->with('pagetype', $pagetype)
                ->withData1($data1)
                ->with('model', $models);
        }
    }

    public function updateresubmit(Request $req)
    {

        $m = count($req->R_NR_EM);
        $department = $req->input('department');
        $dep_name = Departments::where('id', $department)->value('departmentname');
        $type = $req->input('type');
        $date_created = $req->input('date_created');
        $activity = $req->input('activity');
        $legal = $req->input('legal');
        $location = $req->input('location');
        $team = $req->input('team');
        $e = Auth::user()->email;
        $id = $req->input('idd');
        $Rid = $req->input('posted');
        $status = 'Review Pending';

        date_default_timezone_set('Asia/Kuwait');
        $reviewed_at = date('d-m-Y h:i:s A', time());

        Ohs::where('id', $req->Rid)
            ->update(array(
                'reviewed_at' => $reviewed_at,
                'risk_status' => $status,
                'department' => $department,
                'type' => $type,
                'created_at' => $date_created,
                'activity' => $activity,
                'legal' => $legal,
                'location' => $location,
                'team' => $team,
            ));
        Ohsrisk::where('ohs_id', $req->Rid)
            ->update(array(
                'risk_status' => $status,
                'department' => $department,
                'active' => 'Yes',
                'created_at' => $date_created,
            ));

        $data1 = Ohsrisk::where('ohs_id', $Rid)->get();
        $n = 0;

        foreach ($data1 as $dat) {
            foreach ($id as $s) {
                if ($dat->id == $s && $n < $m) {
                    $hazards[$n] = $req->hazards[$n];
                    $OrR[$n] = $req->OrS[$n] * $req->OrL[$n];

                    $RR[$n] = $req->RS[$n] * $req->RL[$n];
                    if ($RR[$n] > 0 && $RR[$n] < 4) {
                        $category[$n] = "Low";
                    } elseif ($RR[$n] > 3 && $RR[$n] < 7) {
                        $category[$n] = "Medium";
                    } elseif ($RR[$n] > 7 && $RR[$n] < 17) {
                        $category[$n] = "High";
                    }

                    Ohsrisk::where('id', $s)
                        ->update(array(
                            'R_NR_EM' => $req->R_NR_EM[$n],
                            'hazards' => $req->hazards[$n],
                            'affect' => $req->affect[$n],
                            'control' => $req->control[$n],
                            'OrS' => $req->OrS[$n],
                            'OrL' => $req->OrL[$n],
                            'OrR' => $OrR[$n],
                            'additional' => $req->additional[$n],
                            'RS' => $req->RS[$n],
                            'RL' => $req->RL[$n],
                            'RR' => $RR[$n],
                            'monitor' => $req->monitor[$n],
                            'category' => $category[$n],
                        ));
                }
            }
            // }
            $n = $n + 1;
        }
        $models = DB::table('modenadisable')->First();
        return redirect('/user/ohsrisk/list/' . $dep_name . '/' . $location)->with('model', $models);
    }

    // public function search(Request $req)

    // {

    //     $ID = Auth::user()->employeeid;
    //     $poss = DB::table('users')->where('employeeid', $ID)->get();
    //     foreach ($poss as $dat) {
    //         $e = $dat->email;
    //     }
    //     $e = Auth::user()->email; // added this
    //     $dep = DB::table('departments')->where('hod', $e)->get('departmentname');
    //     $mr = DB::table('representatives')->pluck('email');
    //     $hses = DB::table('hses')->pluck('email');
    //     $departments = Departments::all();
    //     $categories =  Ohsrisk::select('category')->distinct()->get();
    //     $locations =  Ohs::select('location')->distinct()->get();

    //     $keyword = $req->input('keyword');

    //     $data1 = Ohs
    //         ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
    //         ->select('ohs.id', 'ohs.actid', 'ohs.department', 'ohs.activity', 'ohs.risk_status', 'ohs.postedby')
    //         ->where(function ($query) use ($req) {
    //             $query
    //                 ->where('ohs.department', '=', Departments::where('departmentname', $req->input('keyword'))->value('id'))
    //                 ->orwhere('ohs.actid', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.risk_status', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.category', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.R_NR_EM', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.hazards', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.affect', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.control', '=', $req->input('keyword'))
    //                 ->orwhere('ohs.actid', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.type', '=', $req->input('keyword'))
    //                 ->orwhere('ohs.proposechange', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.team', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.location', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.legal', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.activity', '=', $req->input('keyword'))
    //                 ->orWhere('ohsrisk.monitor', '=', $req->input('keyword'))
    //                 ->orWhere('ohsrisk.proc_no', '=', $req->input('keyword'))
    //                 ->orWhere('ohsrisk.procedur', '=', $req->input('keyword'));
    //         })

    //         ->wherein('ohs.risk_status', ['Review Pending', 'Reviewed', 'Approved', 'Rejected'])
    //         ->orderBy('department')
    //         ->distinct()
    //         ->paginate(2000)
    //         ->setpath('');

    //     $data2 = Ohs
    //         ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
    //         ->select(
    //             'ohsrisk.ohs_id',
    //             'ohsrisk.id',
    //             'ohs.risk_status',
    //             'ohsrisk.category',
    //             'ohsrisk.R_NR_EM',
    //             'ohsrisk.hazards',
    //             'ohsrisk.affect',
    //             'ohsrisk.control',
    //             'ohs.actid',
    //             'ohs.department',
    //             'ohs.type',
    //             'ohs.proposechange',
    //             'ohs.postedby',
    //             'ohs.team',
    //             'ohs.location',
    //             'ohs.legal',
    //             'ohs.activity',
    //             'ohs.approvedby',
    //             'ohsrisk.monitor',
    //             'ohsrisk.OrS',
    //             'ohsrisk.OrL',
    //             'ohsrisk.OrR',
    //             'ohsrisk.additional',
    //             'ohsrisk.RS',
    //             'ohsrisk.RL',
    //             'ohsrisk.RR',
    //             'ohsrisk.proc_no',
    //             'ohsrisk.procedur',
    //             'ohsrisk.created_at',
    //             'ohsrisk.reviewed_at',
    //             'ohsrisk.riskid'
    //         )
    //         ->where(function ($query) use ($req) {
    //             $query
    //                 ->where('ohs.department', '=', Departments::where('departmentname', $req->input('keyword'))->value('id'))
    //                 //->where('ohs.department', '=', $req->input('keyword'))
    //                 ->orwhere('ohs.actid', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.risk_status', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.category', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.R_NR_EM', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.hazards', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.affect', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.control', '=', $req->input('keyword'))
    //                 ->orwhere('ohs.actid', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.type', '=', $req->input('keyword'))
    //                 ->orwhere('ohs.proposechange', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.team', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.location', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.legal', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.activity', '=', $req->input('keyword'))
    //                 ->orWhere('ohsrisk.monitor', '=', $req->input('keyword'))
    //                 ->orWhere('ohsrisk.proc_no', '=', $req->input('keyword'))
    //                 ->orWhere('ohsrisk.procedur', '=', $req->input('keyword'));
    //         })

    //         ->wherein('ohs.risk_status', ['Review Pending', 'Reviewed', 'Approved', 'Rejected'])
    //         //->orderBy('created_at','desc')
    //         ->paginate(2000)
    //         ->setpath('');

    //     //-------------------Register
    //     $data3 = Ohs
    //         ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
    //         ->select(
    //             'ohsrisk.ohs_id',
    //             'ohsrisk.id',
    //             'ohs.risk_status',
    //             'ohsrisk.category',
    //             'ohsrisk.R_NR_EM',
    //             'ohsrisk.hazards',
    //             'ohsrisk.affect',
    //             'ohsrisk.control',
    //             'ohs.actid',
    //             'ohs.department',
    //             'ohs.type',
    //             'ohs.proposechange',
    //             'ohs.postedby',
    //             'ohs.team',
    //             'ohs.location',
    //             'ohs.legal',
    //             'ohs.activity',
    //             'ohs.approvedby',
    //             'ohsrisk.monitor',
    //             'ohsrisk.OrS',
    //             'ohsrisk.OrL',
    //             'ohsrisk.OrR',
    //             'ohsrisk.additional',
    //             'ohsrisk.RS',
    //             'ohsrisk.RL',
    //             'ohsrisk.RR',
    //             'ohsrisk.proc_no',
    //             'ohsrisk.procedur',
    //             'ohsrisk.created_at',
    //             'ohsrisk.reviewed_at',
    //             'ohsrisk.riskid'
    //         )
    //         ->wherein('ohsrisk.category', ['High', 'Medium'])

    //         ->where(function ($query) use ($req) {
    //             $query
    //                 ->where('ohs.department', '=', Departments::where('departmentname', $req->input('keyword'))->value('id'))
    //                 // ->where('ohs.department', '=', $req->input('keyword'))
    //                 ->orwhere('ohs.actid', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.risk_status', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.category', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.R_NR_EM', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.hazards', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.affect', '=', $req->input('keyword'))
    //                 ->orwhere('ohsrisk.control', '=', $req->input('keyword'))
    //                 ->orwhere('ohs.actid', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.type', '=', $req->input('keyword'))
    //                 ->orwhere('ohs.proposechange', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.team', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.location', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.legal', '=', $req->input('keyword'))
    //                 ->orWhere('ohs.activity', '=', $req->input('keyword'))
    //                 ->orWhere('ohsrisk.monitor', '=', $req->input('keyword'))
    //                 ->orWhere('ohsrisk.proc_no', '=', $req->input('keyword'))
    //                 ->orWhere('ohsrisk.procedur', '=', $req->input('keyword'));
    //         })

    //         ->wherein('ohs.risk_status', ['Review Pending', 'Reviewed', 'Approved', 'Rejected'])
    //         //->orderBy('created_at','desc')
    //         ->paginate(2000)
    //         ->setpath('');

    //     //---------------------Analysis

    //     $chart = new OhsRiskAnalysis;
    //     $chart->title("Ohs Risk Summary-Department Wise");
    //     $names = DB::select("select departmentname from departments order by departmentname ASC");
    //     $x = [];
    //     foreach ($names as $key) {
    //         array_push($x, $key->departmentname);
    //     }
    //     $chart->labels($x);
    //     $num = DB::select("select s.departmentname,COUNT(r.category)AS number
    //                 FROM  ohsrisk AS r
    //                 right JOIN departments AS s
    //                 ON r.department= s.id
    //                 and  r.category='High' and r.active='Yes'
    //                 group by s.departmentname
    //                 order by s.departmentname ASC
    //                ");

    //     $num1 = DB::select("select s.departmentname,COUNT(r.category)AS number
    //                 FROM  ohsrisk AS r
    //                 right JOIN departments AS s
    //                 ON r.department= s.id
    //                 and  r.category='Medium' and r.active='Yes'
    //                 group by s.departmentname
    //                 order by s.departmentname ASC
    //                ");

    //     $num2 = DB::select("select s.departmentname,COUNT(r.category)AS number
    //                 FROM  ohsrisk AS r
    //                 right JOIN departments AS s
    //                 ON r.department= s.id
    //                 and  r.category='Low' and r.active='Yes'
    //                 group by s.departmentname
    //                 order by s.departmentname ASC
    //                ");
    //     $total = DB::select("select s.departmentname,COUNT(r.category)AS number
    //                 FROM  ohsrisk AS r
    //                 right JOIN departments AS s
    //                 ON r.department= s.id
    //                 and r.active='Yes'
    //                 group by s.departmentname
    //                 order by s.departmentname ASC
    //                ");

    //     $a = [];
    //     foreach ($num as $key) {
    //         array_push($a, $key->number);
    //     }
    //     $b = [];
    //     foreach ($num1 as $key) {
    //         array_push($b, $key->number);
    //     }
    //     $c = [];
    //     foreach ($num2 as $key) {
    //         array_push($c, $key->number);
    //     }
    //     $chart->dataset('High', 'bar', $a)->backgroundcolor('#f7533e')->color('#f7533e');
    //     $chart->dataset('Medium', 'bar', $b)->backgroundcolor('#FFD700')->color('#FFD700');
    //     $chart->dataset('Low', 'bar', $c)->backgroundcolor('#a6ff00')->color('#a6ff00');
    //     $chart->dataset('Total', 'bar', $c)->backgroundcolor('#575553')->color('#575553');
    //     $chart->height(400);

    //     $processes = DB::select("select departmentname from departments order by departmentname ASC");
    //     $process = [];
    //     foreach ($processes as $key) {
    //         array_push($process, $key->departmentname);
    //     }

    //     $phigh = DB::select("select s.departmentname,COUNT(r.category)AS number
    //                 FROM  ohsrisk AS r
    //                 right JOIN departments AS s
    //                 ON r.department= s.id
    //                 and  r.category='High' and r.active='Yes'
    //                 group by s.departmentname
    //                 order by s.departmentname ASC
    //                ");

    //     $pmedium = DB::select("select s.departmentname,COUNT(r.category)AS number
    //                 FROM  ohsrisk AS r
    //                 right JOIN departments AS s
    //                 ON r.department= s.id
    //                 and  r.category='Medium' and r.active='Yes'
    //                 group by s.departmentname
    //                 order by s.departmentname ASC
    //                ");

    //     $plow = DB::select("select s.departmentname,COUNT(r.category)AS number
    //                 FROM  ohsrisk AS r
    //                 right JOIN departments AS s
    //                 ON r.department= s.id
    //                 and  r.category='Low' and r.active='Yes'
    //                 group by s.departmentname
    //                 order by s.departmentname ASC
    //                ");

    //     $highp = [];
    //     foreach ($phigh as $key) {
    //         array_push($highp, $key->number);
    //     }
    //     $mediump = [];
    //     foreach ($pmedium as $key) {
    //         array_push($mediump, $key->number);
    //     }
    //     $lowp = [];
    //     foreach ($plow as $key) {
    //         array_push($lowp, $key->number);
    //     }

    //     $chart2 = new OhsRiskAnalysis;
    //     $chart2->title("Ohs Risk Summary(High)-Department Wise");
    //     $chart2->labels($process);
    //     $chart2->dataset('High', 'bar', $highp)->backgroundcolor('#f7533e')->color('#f7533e');
    //     $chart2->height(200);

    //     $chart3 = new OhsRiskAnalysis;
    //     $chart3->title("Ohs Risk Summary(Medium)-Department Wise");
    //     $chart3->labels($process);
    //     $chart3->dataset('Medium', 'bar', $mediump)->backgroundcolor('#FFD700')->color('#FFD700');
    //     $chart3->height(200);

    //     $chart4 = new OhsRiskAnalysis;
    //     $chart4->title("Ohs Risk Summary(Low)-Department Wise");
    //     $chart4->labels($process);
    //     $chart4->dataset('Medium', 'bar', $lowp)->backgroundcolor('#baeb34')->color('#baeb34');
    //     $chart4->height(200);

    //     $models = DB::table('modenadisable')->First();
    //     return view('user.ohsrisk.list')
    //         ->withData1($data1)
    //         ->with('data_1', $data1)
    //         ->withData2($data2)
    //         ->with('data3', $data3)
    //         ->withDepartments($departments)
    //         ->withCategories($categories)
    //         ->withLocations($locations)
    //         ->with('dep', $dep)
    //         ->with('hses', $hses)
    //         ->with('model', $models)
    //         ->with('chart', $chart)
    //         ->with('chart2', $chart2)
    //         ->with('chart3', $chart3)
    //         ->with('chart4', $chart4)
    //         ->withMr($mr);
    // }

    public function deleteactivity(Request $request)
    {
        $Rid = $request->val;
        //inserting values to activity table
        $activity = "OHS Deleted where Activity Id is " . $Rid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        Ohsrisk::where('ohs_id', $Rid)->delete();
        Ohs::where('id', $Rid)->delete();
        Session::flash('mssg', "OHS Risk Deleted Successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    public function delete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            //inserting data to activities table
            $activity = "An Activity is Removed with ID " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
            DB::table('viewactivity')->insert($datas);

            Ohsrisk::where('ohs_id', $id)->delete();
            Ohs::where('id', $id)->delete();

            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    public function deleteohs()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $name = Ohsrisk::where('id', $id)->pluck('riskid');
            //insert data to activity table superadmin code
            $activity = "A risk was Deleted where risk id is " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin codes

            DB::delete('delete from ohsrisk where id = :eid', ['eid' => $id]);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/ohsrisk/folder')->with('model', $models);
        }
    }

    //edit party
    public function editreq_ohs(string $type = null)
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            foreach ($id as $i) {
                $c = Ohsrisk::select('ohs_id')->where('id', $i)->first();
            }
            foreach ($c as $i) {
                $Rid = $i;
            }
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $subitem[] = Ohs
                        ::join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
                        ->where('ohsrisk.id', $d)
                        ->where('ohsrisk.ohs_id', $Rid)
                        ->first();
                }
                $n = $n + 1;
            }
            $mainitem[] = Ohs::where('id', $Rid)->first();
        }

        $models = DB::table('modenadisable')->First();
        return view('user/ohsrisk/editrequest_ohs')->with('data1', $mainitem)
            ->with('data2', $subitem)
            ->with('id', $id)
            ->with('type', $type)
            ->with('model', $models);
    }

    public function editrequest_ohs(Request $req)
    {
        $m = count($req->R_NR_EM);
        $hazards = $req->input('hazards');
        $affect = $req->input('affect');
        $control = $req->input('control');
        $OrS = $req->input('OrS');
        $OrL = $req->input('OrL');
        $RS = $req->input('RS');
        $RL = $req->input('RL');
        $monitor = $req->input('monitor');

        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = Ohsrisk::where('id', $d)->first();
            }
        }

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    $OrR[$n] = $req->OrS[$n] * $req->OrL[$n];

                    $RR[$n] = $req->RS[$n] * $req->RL[$n];
                    if ($RR[$n] > 0 && $RR[$n] < 4) {
                        $category[$n] = "Low";
                    } elseif ($RR[$n] > 3 && $RR[$n] < 7) {
                        $category[$n] = "Medium";
                    } elseif ($RR[$n] > 7 && $RR[$n] < 17) {
                        $category[$n] = "High";
                    }
                    Ohsrisk::where('id', $s)
                        ->update(array(
                            'hazards' => $req->hazards[$n],
                            'affect' => $req->affect[$n],
                            'control' => $req->control[$n],
                            'OrS' => $req->OrS[$n],
                            'OrL' => $req->OrL[$n],
                            'OrR' => $OrR[$n],
                            'RS' => $req->RS[$n],
                            'RL' => $req->RL[$n],
                            'RR' => $RR[$n],
                            'monitor' => $req->monitor[$n],
                            'category' => $category[$n],
                        ));

                    //insert data to activity table superadmin code
                    $activity = "Risk is  Edited and Updated with new Details  where  ID " . $s;
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
        return redirect('/user/ohsrisk/list')->with('model', $models);
    }
}
