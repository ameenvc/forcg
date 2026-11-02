<?php

namespace App\Http\Controllers;

use App\Departments;
use App\Enviaspect;
use App\EnviaspectConsequence;
use App\EnviaspectControlMeasure;
use App\EnviaspectProbability;
use App\EnviaspectQuantity;
use App\EnviaspectRegulatoryRequirement;
use App\Enviaspects;

// use Validator;
use App\Environment;
use App\EnvironmentalImpactDetail;
use App\Http\Controllers\Controller;
use App\ModenaDisable;
// use App\Notifications;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Session as FacadesSession;
use Session;

class EnviaspectsController extends Controller
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
        return view('user.enviaspects.home');
    }

    public function allAspects($type = null)
    {
        $enviaspects = DB::table('modpageload')->where('id', 1)->get();
        $mr = DB::table('representatives')->get();
        $admin = DB::table('admins')->get();
        $user = Environment::get();

        $list = collect([]);
        foreach ($mr as $mrs) {
            $list = $list->push($mrs->email);
        }
        foreach ($admin as $admins) {
            $list = $list->push($admins->email);
        }
        foreach ($user as $users) {
            $list = $list->push($users->useremail);
        }
        $delete = $list->unique();

        if (sizeof($enviaspects) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 1, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0]);
        } else {
            $enviaspects = DB::table('modpageload')->where('id', 1)->First();
            $enviaspects->enviaspects = $enviaspects->enviaspects + 1;
            DB::table('modpageload')->update(['enviaspects' => $enviaspects->enviaspects]);
        }
        //end of superadmin  code
        $hses = DB::table('envireviewers')->pluck('email');
        $hod = DB::table('departments')->pluck('hod');
        $data = Environment::wherein('environment.status', ['Review Pending', 'Reviewed', 'Approved', 'Rejected'])
            ->orderBy('environment.Eid', 'asc')
            ->paginate(2000);
        $data2 = [];
        $departs = Environment::all();
        foreach ($departs as $item) {
            $data2[$item->department] = Environment::join('enviaspects', 'environment.Eid', '=', 'enviaspects.Eid')
                ->where(function ($query) use ($type, $item) {
                    $dept = $item->dept ? $item->dept->id : 0;
                    if ($type == "high") {
                        $query->where('enviaspects.status', 'Reviewed')
                            ->where('environment.department', $dept)
                            ->where('enviaspects.HS', 'HS');
                    } else if ($type == "medium") {
                        $query->where('enviaspects.status', 'Reviewed')
                            ->where('environment.department', $dept)
                            ->where('enviaspects.HS', 'MS');
                    } else if ($type == "low") {
                        $query->where('enviaspects.status', 'Reviewed')
                            ->where('environment.department', $dept)
                            ->where('enviaspects.HS', 'NS');
                    } else if ($type == 'c') {
                        $query->where('enviaspects.status', 'Reviewed')
                            ->where('environment.department', $dept)
                            ->where('enviaspects.C', '2');
                    }
                })
                ->orderBy('environment.Eid', 'asc')
                ->paginate(2000);
        }

        $HS = Enviaspect::pluck('HS');
        $hod = DB::table('departments')->pluck('hod');
        $e = Auth::user()->email;
        $dep = DB::table('departments')->where('hod', $e)->pluck('id');
        $potential = Enviaspect::pluck('potential');
        $departments = Departments::all();
        //below line is related to superadmin
        $models = DB::table('modenadisable')->First();

        return view('user.enviaspects.all')->with('data', $data)->with('mr', $mr)->with('delete', $delete)
            ->with('data2', $data2)
            ->withDepartments($departments)
            ->with('HS', $HS)
            ->with('potential', $potential)
            ->with('dep', $dep)
            ->with('hses', $hses)
            ->with('hod', $hod)->with('model', $models);
    }
    public function getText($items)
    {
        $items_text = '';
        foreach ($items as $item) {
            $w = $item->weightage;
            $text = showDescriptions($item->description);
            $items_text .= '<option value="' . $w . '">' . $text . ' [' . $w . '] ' . '</option>';
        }

        return $items_text;

    }
    public function addenviaspects()
    {
        $select_color = '#e6e6ff';
        $envinfo = DB::table('envinfo')->get();
        $departments = Departments::all();
        $users = User::all();
        $x = 0;
        $data = Environment::get();
        $envi = DB::table('envireviewers')->get();
        $environmental_impact_details = EnvironmentalImpactDetail::all();
        $enviaspect_probabilities = EnviaspectProbability::all();
        $enviaspect_consequences = EnviaspectConsequence::all();
        $enviaspect_regulatory_requirements = EnviaspectRegulatoryRequirement::all();
        $enviaspect_quantities = EnviaspectQuantity::all();
        $enviaspect_control_measures = EnviaspectControlMeasure::all();

        $enviaspect_probabilities_text = $this->getText($enviaspect_probabilities);
        $enviaspect_consequences_text = $this->getText($enviaspect_consequences);
        $enviaspect_regulatory_requirements_text = $this->getText($enviaspect_regulatory_requirements);
        $enviaspect_quantities_text = $this->getText($enviaspect_quantities);
        $enviaspect_control_measures_text = $this->getText($enviaspect_control_measures);

        if (count($data) > 0) {
            $data = Environment::latest('created_at')->first();
            foreach ($envi as $e) {
                if (Auth::user()->email == $e->email) {
                    $x = 5;
                }
            }
            $Eid = $data->Eid;
            $k = $Eid + 1;
            $data2 = Enviaspect::where('Eid', $k)->get();
            $models = DB::table('modenadisable')->First();

            return view('user.enviaspects.add')->with([
                'model' => $models,
                'environmental_impact_details' => $environmental_impact_details,
                'x' => $x,
                'envinfo' => $envinfo,
                'departments' => $departments,
                'envi' => $envi,
                'data2' => $data2,
                'users' => $users,
                'enviaspect_probabilities' => $enviaspect_probabilities,
                'enviaspect_consequences' => $enviaspect_consequences,
                'enviaspect_regulatory_requirements' => $enviaspect_regulatory_requirements,
                'enviaspect_quantities' => $enviaspect_quantities,
                'enviaspect_control_measures' => $enviaspect_control_measures,
                'enviaspect_probabilities_text' => $enviaspect_probabilities_text,
                'enviaspect_consequences_text' => $enviaspect_consequences_text,
                'enviaspect_regulatory_requirements_text' => $enviaspect_regulatory_requirements_text,
                'enviaspect_quantities_text' => $enviaspect_quantities_text,
                'enviaspect_control_measures_text' => $enviaspect_control_measures_text,
                'select_color' => $select_color,
            ]);
        }
        $data2 = Enviaspect::get();

        $models = DB::table('modenadisable')->First();

        return view('user.enviaspects.add')->with([
            'model' => $models,
            'environmental_impact_details' => $environmental_impact_details,
            'x' => $x,
            'envinfo' => $envinfo,
            'departments' => $departments,
            'envi' => $envi,
            'data2' => $data2,
            'users' => $users,
            'enviaspect_probabilities' => $enviaspect_probabilities,
            'enviaspect_consequences' => $enviaspect_consequences,
            'enviaspect_regulatory_requirements' => $enviaspect_regulatory_requirements,
            'enviaspect_quantities' => $enviaspect_quantities,
            'enviaspect_control_measures' => $enviaspect_control_measures,
            'enviaspect_probabilities_text' => $enviaspect_probabilities_text,
            'enviaspect_consequences_text' => $enviaspect_consequences_text,
            'enviaspect_regulatory_requirements_text' => $enviaspect_regulatory_requirements_text,
            'enviaspect_quantities_text' => $enviaspect_quantities_text,
            'enviaspect_control_measures_text' => $enviaspect_control_measures_text,
            'select_color' => $select_color,
        ]);
    }

    public function displayimpactdetailajax(Request $request)
    {
        $data_id = $request->input('data_id');
        $title = EnvironmentalImpactDetail::where('id', $data_id)->value('title');
        $impact = EnvironmentalImpactDetail::where('id', $data_id)->value('impact');
        $details = EnvironmentalImpactDetail::where('id', $data_id)->value('details');

        return response()->json([
            'status' => 200,
            'title' => $title,
            'impact' => $impact,
            'details' => $details,
        ]);

    }

    public function adddcr(Request $req)
    {
        $n = count($req->doc);
        $department = $req->input('department');
        $division = $req->input('division');
        $area = $req->input('area');

        $location = $req->input('location');

        $a = DB::table('users')->get();
        $e = Auth::user()->email;
        $post = Auth::user()->employeeid;
        foreach ($a as $b) {
            if ($e == $b->email) {
                $post = $b->id;
            }

        }
        $activity = $req->input('activity');
        $date = $req->input('date');
        $teams = $req->input('team');
        $created_date = date('d-m-Y h:i:s A', time());
        $created_by = Auth::user()->employeeid;
        $tea = null;
        if ($teams) {
            foreach ($teams as $team) {
                $t[] = $team;
            }
            $tea = implode(' , ', $t);
        }

        $exist = Environment::where('department', $department)->first();
        if ($exist) {

            $c = Environment::where('department', $department)->pluck('count');
            foreach ($c as $count) {
                $count = $count + 1;
            }
        } else {

            $count = "1";
        }
        $data1 = array('date' => $date, 'team' => $tea, 'count' => $count, 'department' => $department, 'division_id' => $division, 'area_id' => $area, 'activity' => $activity, 'useremail' => Auth::user()->email, 'location' => $location, 'created_at' => $created_date, 'created_by' => $created_by);

        //inserting values to activity table
        $activity = "New Environmental Activity is Added with activity name as " . $activity . ", team " . $tea . " and with department " . $department;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        Environment::insert($data1);

        $data = Environment::orderBy('Eid', 'desc')->first();

        $Eid = $data->Eid;

        $d = 0;

        foreach ($req->doc as $key => $value) {

            $total = ($value['A'] * $value['B'] * $value['C'] * $value['D'] * $value['E']);
            if ($total >= 200) {
                $HS = 'HS';
            } else if ($total < 200 && $total >= 100) {
                $HS = 'MS';
            } else {
                $HS = 'NS';
            }

            if ($d <= $n) {
                $d = $d + 1;
            }

            $post = Auth::user()->employeeid;

            $data2 = array(
                'Eid' => $Eid, 'enviaspects' => $value['enviaspects'],
                'ER' => $value['ER'],
                'potential' => $value['potential'],
                'details' => $value['details'],
                'A' => $value['A'],
                'B' => $value['B'],
                'C' => $value['C'],
                'D' => $value['D'],
                'E' => $value['E'],
                'total' => $total, 'HS' => $HS, 'department' => $department, 'posted_by' => $post, 'aspect_id' => $d, 'created_at' => $created_date,
            );

            Enviaspect::insert($data2);
        }

        //inserting values to activity table
        $activity = "New Environmental Aspect is Added with Aspect name as " . $value['enviaspects'] . "  where Activity ID is  " . $Eid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        Session::flash('msg', "Aspect added successfully");
        $models = DB::table('modenadisable')->First();

        return redirect()->back()->withDate($date)->with('model', $models);
    }

    //new edit for locatin and activity
    public function editnew(Request $req)
    {

        $a = $req->val;
        //    $departments = Departments::all();
        $data = Environment::where('Eid', $a)->get();
        //    $data2=Enviaspect::where('Eid',$a)->get();

        $models = DB::table('modenadisable')->First();
        return view('user.enviaspects.newedit')->withData($data)->with('model', $models);
    }

    public function newedit(Request $req)
    {

        //    $m=count($req->doc);
        $id = $req->input('id');
        $activity = $req->input('activity');
        $location = $req->input('location');
        $a = DB::table('users')->get();
        $e = Auth::user()->email;
        date_default_timezone_set('Asia/Kuwait');
        $created_date = date('d-m-Y h:i:s A', time());
        $data = array('activity' => $activity, 'location' => $location);
        Environment::where('Eid', $id)->update($data);
        // dd($data);

        //inserting values to activity table
        $activity = "Environmental Aspect is Edited and updated with Activity name as " . $activity . "  where Activity ID is  " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        Session::flash('msg', "Aspect edited successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    //addaspect
    public function addaspect(Request $req)
    {
        $a = $req->val;
        $select_color = '#e6e6ff';
        $departments = Departments::all();
        $data = Environment::where('Eid', $a)->get();
        $data2 = Enviaspect::where('Eid', $a)->get();
        $enviaspect_probabilities = EnviaspectProbability::all();
        $enviaspect_consequences = EnviaspectConsequence::all();
        $enviaspect_regulatory_requirements = EnviaspectRegulatoryRequirement::all();
        $enviaspect_quantities = EnviaspectQuantity::all();
        $enviaspect_control_measures = EnviaspectControlMeasure::all();
        $enviaspect_probabilities_text = $this->getText($enviaspect_probabilities);
        $enviaspect_consequences_text = $this->getText($enviaspect_consequences);
        $enviaspect_regulatory_requirements_text = $this->getText($enviaspect_regulatory_requirements);
        $enviaspect_quantities_text = $this->getText($enviaspect_quantities);
        $enviaspect_control_measures_text = $this->getText($enviaspect_control_measures);
        $environmental_impact_details = EnvironmentalImpactDetail::all();

        $models = DB::table('modenadisable')->first();

        return view('user.enviaspects.addaspect')->with([
            'departments' => $departments,
            'data' => $data,
            'data2' => $data2,
            'select_color' => $select_color,
            'enviaspect_probabilities' => $enviaspect_probabilities,
            'enviaspect_consequences' => $enviaspect_consequences,
            'enviaspect_regulatory_requirements' => $enviaspect_regulatory_requirements,
            'enviaspect_quantities' => $enviaspect_quantities,
            'enviaspect_control_measures' => $enviaspect_control_measures,
            'enviaspect_probabilities_text' => $enviaspect_probabilities_text,
            'enviaspect_consequences_text' => $enviaspect_consequences_text,
            'enviaspect_regulatory_requirements_text' => $enviaspect_regulatory_requirements_text,
            'enviaspect_quantities_text' => $enviaspect_quantities_text,
            'enviaspect_control_measures_text' => $enviaspect_control_measures_text,
            'environmental_impact_details' => $environmental_impact_details,
            'model' => $models,
        ]);

    }

    public function adddcrr(Request $req)
    {
        $m = count($req->doc);
        $id = $req->input('id');
        $department = $req->input('department');
        $location = $req->input('location');
        $a = DB::table('users')->get();
        $e = Auth::user()->email;
        $created_date = date('d-m-Y h:i:s A', time());
        foreach ($a as $b) {
            if ($e == $b->email) {
                $post = $b->id;
            }

            $status = 'Review Pending';
            DB::table('environment')
                ->where('Eid', $id)
                ->update(array('status' => $status));
        }
        $exist = Environment::where('department', $department)->first();
        if ($exist) {
            $c = Environment::where('department', $department)->pluck('count');
            foreach ($c as $count) {
                $count = $count + 1;
            }
        } else {
            $count = "1";
        }
        $d = Enviaspect::where('Eid', $id)->latest('aspect_id')->pluck('aspect_id')->first();
        $n = $m + $d;
        foreach ($req->doc as $key => $value) {

            $total = ($value['A'] * $value['B'] * $value['C'] * $value['D'] * $value['E']);
            if ($total >= 200) {
                $HS = 'HS';
            } else if ($total < 200 && $total >= 100) {
                $HS = 'MS';
            } else {
                $HS = 'NS';
            }

            if ($d <= $n) {
                $d = $d + 1;
            }

            $data1 = array(
                'Eid' => $id, 'enviaspects' => $value['enviaspects'],
                'ER' => $value['ER'],
                'potential' => $value['potential'],
                'details' => $value['details'],
                'A' => $value['A'],
                'B' => $value['B'],
                'C' => $value['C'],
                'D' => $value['D'],
                'E' => $value['E'],
                'total' => $total, 'HS' => $HS, 'department' => $department, 'posted_by' => $post, 'aspect_id' => $d, 'created_at' => $created_date,
            );
            Enviaspect::insert($data1);
        }
        //inserting values to activity table
        $activity = "New Environmental Aspect is Added with Aspect name as " . $value['enviaspects'] . "  where Activity ID is  " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        Session::flash('msg', "Aspect added successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    public function action(int $id)
    {
        $data = DB::table('environment')
            ->join('enviaspects', 'environment.Eid', '=', 'enviaspects.Eid')
            ->where('enviaspects.slno', $id)->get();

        $models = ModenaDisable::first();

        return view('user.enviaspects.action')->with([
            'id' => $id,
            'model' => $models,
            'data' => $data,
        ]);
    }
    public function control_pro(Request $req)
    {
        $proc_no = $req->input('proc_no');
        $procedure = $req->input('procedure');
        $id = $req->input('id');

        $reviewed_at = date('Y-m-d H:i:s', time());

        $data = [
            'enviaspects.reviewed_at' => $reviewed_at,
            'enviaspects.proc_no' => $proc_no,
            'enviaspects.procedure' => $procedure,
        ];

        DB::table('environment')
            ->join('enviaspects', 'environment.Eid', '=', 'enviaspects.Eid')
            ->where('enviaspects.slno', $id)->update($data);

        //inserting values to activity table
        $Rid = DB::table('environment')
            ->join('enviaspects', 'environment.Eid', '=', 'enviaspects.Eid')
            ->where('enviaspects.slno', $id)->pluck('enviaspects.slno');
        $activity = "Control Procedure is Added to the Envirionment where id is " . $id . " and where Activity Id is " . $Rid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        FacadesDB::table('viewactivity')->insert($datas);
        //end of superadmin code

        $models = Modenadisable::first();

        return redirect('/user/enviaspects/listofaspects/' . FacadesSession::get('envidept'))->with('model', $models);
    }

    public function regenviaspects($dept = null)
    {
        session(['envidept' => $dept]);
        //below superadmin related code
        $enviaspects = DB::table('modpageload')->where('id', 1)->get();
        $mr = DB::table('representatives')->get();
        $admin = DB::table('admins')->get();
        $user = Environment::get();

        $list = collect([]);
        foreach ($mr as $mrs) {
            $list = $list->push($mrs->email);
        }
        foreach ($admin as $admins) {
            $list = $list->push($admins->email);
        }
        foreach ($user as $users) {
            $list = $list->push($users->useremail);
        }
        $delete = $list->unique();

        if (sizeof($enviaspects) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 1, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0]);
        } else {
            $enviaspects = DB::table('modpageload')->where('id', 1)->First();
            $enviaspects->enviaspects = $enviaspects->enviaspects + 1;
            DB::table('modpageload')->update(['enviaspects' => $enviaspects->enviaspects]);
        }
        //end of superadmin  code

        $hses = DB::table('envireviewers')->pluck('email');
        $hod = DB::table('departments')->pluck('hod');
        $code = DB::table('departments')->where('departmentname', $dept)->value('code');
        $deptid = Departments::where('departmentname', $dept)->value('id');
        $data = Environment::where('department', $deptid)
            ->wherein('environment.status', ['Review Pending', 'Reviewed', 'Approved', 'Rejected', 'Approved Aspect'])
            ->orderBy('Eid', 'asc')
            ->paginate(2000);

        $data2 = DB::table('environment')
            ->join('enviaspects', 'environment.Eid', '=', 'enviaspects.Eid')
            ->select(
                'enviaspects.Eid',
                'enviaspects.slno',
                'enviaspects.HS',
                'enviaspects.A',
                'enviaspects.B',
                'enviaspects.C',
                'enviaspects.D',
                'enviaspects.E',
                'environment.team',
                'environment.location',
                'enviaspects.aspect_id',
                'enviaspects.proc_no',
                'enviaspects.procedure',
                'environment.date',
                'environment.department',
                'environment.count',
                'environment.proposechanges',
                'environment.useremail',
                'environment.activity',
                'enviaspects.enviaspects',
                'environment.status',
                'enviaspects.ER',
                'enviaspects.potential',
                'enviaspects.control',
                'enviaspects.approved_by',
                'enviaspects.posted_by',
                'enviaspects.reviewed_by',
                'enviaspects.HS',
                'enviaspects.reviewed_at',
                'enviaspects.total',
                'enviaspects.created_at',
                'enviaspects.slno'
            )
            ->orderBy('Eid', 'asc')
            ->paginate(2000);
        $HS = Enviaspect::pluck('HS');
        $hod = DB::table('departments')->pluck('hod');
        $e = Auth::user()->email;
        $dep = DB::table('departments')->where('hod', $e)->pluck('id')->toArray();
        $potential = Enviaspect::pluck('potential');
        $departments = Departments::all();
        //below line is related to superadmin
        $models = DB::table('modenadisable')->first();

        return view('user.enviaspects.listofaspects')->with('data', $data)->with('mr', $mr)->with('delete', $delete)
            ->with('dept', $dept)
            ->with('data2', $data2)
            ->withDepartments($departments)
            ->with('HS', $HS)
            ->with('potential', $potential)
            ->with('code', $code)
            ->with('dep', $dep)
            ->with('hses', $hses)
            ->with('hod', $hod)->with('model', $models);
    }

    public function regenviaspects1($location)
    {
        //below superadmin related code
        $enviaspects = DB::table('modpageload')->where('id', 1)->get();
        $mr = DB::table('representatives')->pluck('email');
        if (sizeof($enviaspects) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 1, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0]);
        } else {
            $enviaspects = DB::table('modpageload')->where('id', 1)->First();
            $enviaspects->enviaspects = $enviaspects->enviaspects + 1;
            DB::table('modpageload')->update(['enviaspects' => $enviaspects->enviaspects]);
        }
        //end of superadmin  code

        $hses = DB::table('envireviewers')->pluck('email');
        $hod = DB::table('departments')->pluck('hod');
        $data = Environment::where('location', $location)
            ->wherein('environment.status', ['Review Pending', 'Reviewed', 'Approved', 'Rejected', 'Approved Aspect'])
        //->where('ohs.department',$dat->departmentname)
            ->orderBy('Eid', 'asc')
            ->paginate(2000);

        $data2 = DB::table('environment')
            ->join('enviaspects', 'environment.Eid', '=', 'enviaspects.Eid')
            ->select(
                'enviaspects.Eid',
                'enviaspects.slno',
                'enviaspects.HS',
                'enviaspects.A',
                'enviaspects.B',
                'enviaspects.C',
                'enviaspects.D',
                'enviaspects.E',
                'enviaspects.proc_no',
                'enviaspects.procedure',
                'environment.team',
                'environment.location',
                'enviaspects.aspect_id',
                'environment.date',
                'environment.department',
                'environment.count',
                'environment.proposechanges',
                'environment.useremail',
                'environment.activity',
                'enviaspects.enviaspects',
                'environment.status',
                'enviaspects.ER',
                'enviaspects.potential',
                'enviaspects.control',
                'enviaspects.approved_by',
                'enviaspects.posted_by',
                'enviaspects.reviewed_by',
                'enviaspects.HS',
                'enviaspects.reviewed_at',
                'enviaspects.total',
                'enviaspects.created_at',
                'enviaspects.slno'
            )
            ->orderBy('Eid', 'asc')
            ->paginate(2000);
        dd($data2);
        $HS = Enviaspect::pluck('HS');
        $hod = DB::table('departments')->pluck('hod');
        $e = Auth::user()->email;
        $dep = DB::table('departments')->where('hod', $e)->pluck('id');

        $data1 = DB::table('environment')
            ->where('location', $location)
            ->get();

        $potential = Enviaspect::pluck('potential');

        $departments = Departments::all();

        $locations = Environment::select('location')->distinct()->get();
        //below line is related to superadmin
        $models = DB::table('modenadisable')->first();

        return view('user.enviaspects.listofaspects')->with('data', $data)->with('mr', $mr)
            ->with('data2', $data2)
            ->withDepartments($departments)
            ->withLocations($locations)
            ->with('HS', $HS)
            ->with('potential', $potential)
            ->with('data1', $data1)
            ->with('dep', $dep)
            ->with('hses', $hses)
            ->with('hod', $hod)->with('model', $models);
    }

    public function adds()
    {
        if (isset($_GET['val'])) {
            $Eid = $_GET['val'];
            $select_color = '#e6e6ff';
            $enviaspect_probabilities = EnviaspectProbability::all();
            $enviaspect_consequences = EnviaspectConsequence::all();
            $enviaspect_regulatory_requirements = EnviaspectRegulatoryRequirement::all();
            $enviaspect_quantities = EnviaspectQuantity::all();
            $enviaspect_control_measures = EnviaspectControlMeasure::all();
            $environmental_impact_details = EnvironmentalImpactDetail::all();
            $enviaspect_probabilities_text = $this->getText($enviaspect_probabilities);
            $enviaspect_consequences_text = $this->getText($enviaspect_consequences);
            $enviaspect_regulatory_requirements_text = $this->getText($enviaspect_regulatory_requirements);
            $enviaspect_quantities_text = $this->getText($enviaspect_quantities);
            $enviaspect_control_measures_text = $this->getText($enviaspect_control_measures);

            $data = Environment::where('environment.Eid', $Eid)->get();
            $data1 = Enviaspect::where('enviaspects.Eid', $Eid)->get();
            $first_row_HS_value = Enviaspect::where('Eid', $Eid)->orderBy('slno', 'ASC')->first();
            $first_row_HS_value = $first_row_HS_value ? $first_row_HS_value->HS : null;

            $mn = DB::table('departments')->get();
            $departments = Departments::all();
            $users = User::all();

            $models = DB::table('modenadisable')->first();

            return view('user/enviaspects/review')->with([
                'model' => $models,
                'select_color' => $select_color,
                'departments' => $departments,
                'users' => $users,
                'data' => $data,
                'mn' => $mn,
                'Eid' => $Eid,
                'data1' => $data1,
                'enviaspect_probabilities' => $enviaspect_probabilities,
                'enviaspect_consequences' => $enviaspect_consequences,
                'enviaspect_regulatory_requirements' => $enviaspect_regulatory_requirements,
                'enviaspect_quantities' => $enviaspect_quantities,
                'enviaspect_control_measures' => $enviaspect_control_measures,
                'environmental_impact_details' => $environmental_impact_details,
                'enviaspect_probabilities_text' => $enviaspect_probabilities_text,
                'enviaspect_consequences_text' => $enviaspect_consequences_text,
                'enviaspect_regulatory_requirements_text' => $enviaspect_regulatory_requirements_text,
                'enviaspect_quantities_text' => $enviaspect_quantities_text,
                'enviaspect_control_measures_text' => $enviaspect_control_measures_text,
                'first_row_HS_value' => $first_row_HS_value,
            ]);
        }
    }

    public function updateaspects(Request $req)
    {
        $p = $req->input('proposal');
        $reviewed = $req->input('question');

        $m = count($req->slno);
        $aspects_count = count($req->aspects);
        $aspects = $req->input('aspects');
        $er = $req->input('er');
        $potential = $req->input('potential');
        $control = $req->input('control');
        $a = DB::table('users')->get();
        $e = Auth::user()->email;
        $reviewed_date = date('Y-m-d H:i:s', time());
        foreach ($a as $b) {
            if ($e == $b->email) {
                $reviewed_by = $b->id;
            }

        }
        $Eid = $req->input('posted');
        $reviewed_by = Auth::user()->employeeid;
        $slno = $req->input('slno');

        if ($reviewed == 2) {

            $status = 'Reviewed';
            DB::table('environment')
                ->where('Eid', $Eid)
                ->update(array('status' => $status, 'reviewed_at' => $reviewed_date, 'reviewed_by' => $reviewed_by));
            //inserting values to activity table
            $act = Environment::where('Eid', $Eid)->pluck('activity');
            $activity = "Environmental Aspects under Actvity " . $act . " is Done with initial review and Updated Status as Reviewed where Environmental Id is " . $Eid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
        } else {

            $status = 'Rejected';
            DB::table('environment')
                ->where('Eid', $Eid)
                ->update(array('status' => $status, 'proposechanges' => $p, 'reviewed_at' => $reviewed_date, 'reviewed_by' => $reviewed_by));
            //inserting values to activity table
            $act = Environment::where('Eid', $Eid)->pluck('activity');
            $activity = "Environmental Aspects under Actvity " . $act . " is Disapproved in initial review and Updated Status as Rejected where Environmental Id is " . $Eid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
        }
        //end of superadmin code
        $data1 = Enviaspect::where('Eid', $Eid)->get();
        $n = 0;
        foreach ($data1 as $dat) {
            foreach ($slno as $s) {
                if ($dat->slno == $s && $n < $m) {
                    $total = ($req->A[$n] * $req->B[$n] * $req->C[$n] * $req->D[$n] * $req->E[$n]);

                    if ($total >= 200) {
                        $HS[$n] = 'HS';
                    } else if ($total < 200 && $total >= 100) {
                        $HS[$n] = 'MS';
                    } else {
                        $HS[$n] = 'NS';
                    }

                    $reviewed_by = Auth::user()->employeeid;
                    Enviaspect::where('slno', $s)
                        ->update([
                            'enviaspects' => isset($req->aspects[$n]) ? $req->aspects[$n] : null,
                            'reviewed_by' => $reviewed_by,
                            'status' => $status,
                            'HS' => isset($HS[$n]) ? $HS[$n] : null,
                            'ER' => isset($req->er[$n]) ? $req->er[$n] : null,
                            'E' => isset($req->E[$n]) ? $req->E[$n] : null,
                            'A' => isset($req->A[$n]) ? $req->A[$n] : null,
                            'B' => isset($req->B[$n]) ? $req->B[$n] : null,
                            'C' => isset($req->C[$n]) ? $req->C[$n] : null,
                            'D' => isset($req->D[$n]) ? $req->D[$n] : null,
                            'potential' => isset($req->potential[$n]) ? $req->potential[$n] : null,
                            'details' => isset($req->details[$n]) ? $req->details[$n] : null,
                            'control' => isset($req->control[$n]) ? $req->control[$n] : null,
                            'reviewed_at' => $reviewed_date]);
                }
            }
            $n = $n + 1;
        }

        if ($m < $aspects_count) {
            for ($i = count($data1); $i < $aspects_count; $i++) {
                $total = ($req->A[$i] * $req->B[$i] * $req->C[$i] * $req->D[$i] * $req->E[$i]);

                if ($total >= 200) {
                    $HS[$i] = 'HS';
                } else if ($total < 200 && $total >= 100) {
                    $HS[$i] = 'MS';
                } else {
                    $HS[$i] = 'NS';
                }

                $data_to_insert[] = [
                    'Eid' => $Eid,
                    'enviaspects' => $req->aspects[$i],
                    'reviewed_by' => $reviewed_by,
                    'status' => $status,
                    'HS' => $HS[$i],
                    'ER' => $req->er[$i],
                    'E' => $req->E[$i],
                    'A' => $req->A[$i],
                    'B' => $req->B[$i],
                    'C' => $req->C[$i],
                    'D' => $req->D[$i],
                    'potential' => $req->potential[$i],
                    'details' => $req->details[$i],
                    'control' => $req->control[$i],
                    'reviewed_at' => $reviewed_date,
                ];

            }

            Enviaspect::insert($data_to_insert);

        }

        $models = DB::table('modenadisable')->First();
        return redirect('/user/enviaspects/folder')->with('model', $models);
    }

    //Approve Aspect
    public function approve(int $id)
    {
        $Eid = $id;
        $select_color = '#e6e6ff';
        $enviaspect_probabilities = EnviaspectProbability::all();
        $enviaspect_consequences = EnviaspectConsequence::all();
        $enviaspect_regulatory_requirements = EnviaspectRegulatoryRequirement::all();
        $enviaspect_quantities = EnviaspectQuantity::all();
        $enviaspect_control_measures = EnviaspectControlMeasure::all();
        $environmental_impact_details = EnvironmentalImpactDetail::all();
        $enviaspect_probabilities_text = $this->getText($enviaspect_probabilities);
        $enviaspect_consequences_text = $this->getText($enviaspect_consequences);
        $enviaspect_regulatory_requirements_text = $this->getText($enviaspect_regulatory_requirements);
        $enviaspect_quantities_text = $this->getText($enviaspect_quantities);
        $enviaspect_control_measures_text = $this->getText($enviaspect_control_measures);

        $data = Environment::where('environment.Eid', $Eid)->get();
        $data1 = Enviaspect::where('enviaspects.Eid', $Eid)->get();
        $first_row_HS_value = Enviaspect::where('Eid', $Eid)->orderBy('slno', 'ASC')->first();
        $first_row_HS_value = $first_row_HS_value ? $first_row_HS_value->HS : null;

        $mn = DB::table('departments')->get();
        $departments = Departments::all();
        $users = User::all();

        $models = DB::table('modenadisable')->first();

        return view('user.enviaspects.approve')->with([
            'model' => $models,
            'select_color' => $select_color,
            'departments' => $departments,
            'users' => $users,
            'data' => $data,
            'mn' => $mn,
            'Eid' => $Eid,
            'data1' => $data1,
            'enviaspect_probabilities' => $enviaspect_probabilities,
            'enviaspect_consequences' => $enviaspect_consequences,
            'enviaspect_regulatory_requirements' => $enviaspect_regulatory_requirements,
            'enviaspect_quantities' => $enviaspect_quantities,
            'enviaspect_control_measures' => $enviaspect_control_measures,
            'environmental_impact_details' => $environmental_impact_details,
            'enviaspect_probabilities_text' => $enviaspect_probabilities_text,
            'enviaspect_consequences_text' => $enviaspect_consequences_text,
            'enviaspect_regulatory_requirements_text' => $enviaspect_regulatory_requirements_text,
            'enviaspect_quantities_text' => $enviaspect_quantities_text,
            'enviaspect_control_measures_text' => $enviaspect_control_measures_text,
            'first_row_HS_value' => $first_row_HS_value,
        ]);

    }

    public function doApprove(Request $req)
    {
        $p = $req->input('proposal');
        $reviewed = $req->input('question');

        $m = count($req->slno);
        $aspects_count = count($req->aspects);

        $aspects = $req->input('aspects');
        $er = $req->input('er');
        $potential = $req->input('potential');
        $control = $req->input('control');
        $a = DB::table('users')->get();
        $e = Auth::user()->email;
        $reviewed_date = date('Y-m-d H:i:s', time());
        foreach ($a as $b) {
            if ($e == $b->email) {
                $reviewed_by = $b->id;
            }

        }
        $Eid = $req->input('posted');
        $reviewed_by = Auth::user()->employeeid;
        $slno = $req->input('slno');

        if ($reviewed == 2) {

            $status = 'Approved Aspect';
            DB::table('environment')
                ->where('Eid', $Eid)
                ->update(array('status' => $status, 'reviewed_at' => $reviewed_date, 'reviewed_by' => $reviewed_by));
            //inserting values to activity table
            $act = Environment::where('Eid', $Eid)->pluck('activity');
            $activity = "Environmental Aspects under Actvity " . $act . " is Done with initial review and Updated Status as Reviewed where Environmental Id is " . $Eid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
        } else {

            $status = 'Rejected';
            DB::table('environment')
                ->where('Eid', $Eid)
                ->update(array('status' => $status, 'proposechanges' => $p, 'reviewed_at' => $reviewed_date, 'reviewed_by' => $reviewed_by));
            //inserting values to activity table
            $act = Environment::where('Eid', $Eid)->pluck('activity');
            $activity = "Environmental Aspects under Actvity " . $act . " is Disapproved in initial review and Updated Status as Rejected where Environmental Id is " . $Eid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
        }
        //end of superadmin code
        $data1 = Enviaspect::where('Eid', $Eid)->get();
        $n = 0;
        foreach ($data1 as $dat) {
            foreach ($slno as $s) {
                if ($dat->slno == $s && $n < $m) {
                    $total = ($req->A[$n] * $req->B[$n] * $req->C[$n] * $req->D[$n] * $req->E[$n]);

                    if ($total >= 200) {
                        $HS[$n] = 'HS';
                    } else if ($total < 200 && $total >= 100) {
                        $HS[$n] = 'MS';
                    } else {
                        $HS[$n] = 'NS';
                    }

                    $reviewed_by = Auth::user()->employeeid;
                    Enviaspect::where('slno', $s)
                        ->update(array('enviaspects' => $req->aspects[$n], 'reviewed_by' => $reviewed_by, 'status' => $status, 'HS' => $HS[$n], 'ER' => $req->er[$n], 'E' => $req->E[$n], 'A' => $req->A[$n], 'B' => $req->B[$n], 'C' => $req->C[$n], 'D' => $req->D[$n], 'potential' => $req->potential[$n], 'details' => $req->details[$n], 'control' => $req->control[$n], 'reviewed_at' => $reviewed_date));
                }
            }
            $n = $n + 1;
        }

        if ($m < $aspects_count) {
            for ($i = count($data1); $i < $aspects_count; $i++) {
                $total = ($req->A[$i] * $req->B[$i] * $req->C[$i] * $req->D[$i] * $req->E[$i]);

                if ($total >= 200) {
                    $HS[$i] = 'HS';
                } else if ($total < 200 && $total >= 100) {
                    $HS[$i] = 'MS';
                } else {
                    $HS[$i] = 'NS';
                }

                $data_to_insert[] = [
                    'Eid' => $Eid,
                    'enviaspects' => $req->aspects[$i],
                    'reviewed_by' => $reviewed_by,
                    'status' => $status,
                    'HS' => $HS[$i],
                    'ER' => $req->er[$i],
                    'E' => $req->E[$i],
                    'A' => $req->A[$i],
                    'B' => $req->B[$i],
                    'C' => $req->C[$i],
                    'D' => $req->D[$i],
                    'potential' => $req->potential[$i],
                    'details' => $req->details[$i],
                    'control' => $req->control[$i],
                    'reviewed_at' => $reviewed_date,
                ];

            }

            Enviaspect::insert($data_to_insert);

        }

        $models = DB::table('modenadisable')->First();
        return redirect('/user/enviaspects/folder')->with('model', $models);

    }

    public function addsecondary()
    {
        if (isset($_GET['val'])) {
            $Eid = $_GET['val'];

            $data = Environment::where('environment.Eid', $Eid)->get();
            $data1 = Enviaspect::where('enviaspects.Eid', $Eid)->get();
            $mn = DB::table('departments')->get();
            $models = DB::table('modenadisable')->First();
            return view('user/enviaspects/secondaryreview')->with('model', $models)->withData($data)->withMn($mn)->with('Eid', $Eid)->withData1($data1);
        }
    }

    public function updatesecondary(Request $req)
    {
        $reviewed = $req->input('question');
        $Eid = $req->input('posted');
        $slno = $req->input('slno');
        $a = DB::table('users')->get();
        $e = Auth::user()->email;
        foreach ($a as $b) {
            if ($e == $b->email) {
                $approved = $b->id;
            }

        }

        $m = count($req->aspects);
        $aspects = $req->input('aspects');
        $er = $req->input('er');
        $p = $req->input('proposal');
        $potential = $req->input('potential');
        $control = $req->input('control');
        //$review_date = date('d-m-Y h:i:s A', time());
        $review_date = date('Y-m-d H:i:s', time());
        $reviewed_by = Auth::user()->employeeid;
        // $data=array('status'=>'Approved');
        // Environment::where('Eid',$Eid)->update($data);
        if ($reviewed == 2) {
            $status = 'Reviewed';
            DB::table('environment')
                ->where('Eid', $Eid)
                ->update(array('status' => $status, 'reviewed_at' => $review_date, 'reviewed_by' => $reviewed_by));
            //inserting values to activity table superadmin code
            $act = Environment::where('Eid', $Eid)->pluck('activity');
            $activity = "Environmental Aspects under Actvity " . $act . " is Reviewed Second Time and Updated Status as Reviewed where Environmental Id is " . $Eid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code

            // return redirect('/user/enviaspects/listofaspects');
        } else {
            $status = 'Rejected';
        }

        DB::table('environment')
            ->where('Eid', $Eid)
            ->update(array('status' => $status, 'reviewed_at' => $review_date, 'reviewed_by' => $reviewed_by));
        //inserting values to activity table
        $act = Environment::where('Eid', $Eid)->pluck('activity');
        $activity = "Environmental Aspects under Actvity " . $act . " is Rejected in Secondary Review  and Updated Status as Rejected where Environmental Id is " . $Eid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);

        $data1 = Enviaspect::where('Eid', $Eid)->get();

        $n = 0;
        foreach ($data1 as $dat) {
            foreach ($slno as $s) {
                // dd($aspects);
                if ($dat->slno == $s && $n < $m) {
                    $total = ($req->A[$n] * $req->B[$n] * $req->C[$n] * $req->D[$n] * $req->E[$n]);

                    if ($total >= 200) {
                        $HS[$n] = 'HS';
                    } else if ($total < 200 && $total >= 100) {
                        $HS[$n] = 'MS';
                    } else {
                        $HS[$n] = 'NS';
                    }

                    $data = array('enviaspects' => $req->aspects[$n], 'control' => $req->control[$n], 'HS' => $HS[$n], 'ER' => $req->er[$n], 'E' => $req->E[$n], 'A' => $req->A[$n], 'B' => $req->B[$n], 'C' => $req->C[$n], 'D' => $req->D[$n], 'status' => 'Reviewed', 'potential' => $req->potential[$n], 'control' => $req->control[$n], 'reviewed_at' => $review_date, 'reviewed_by' => $reviewed_by);
                    // dd($data);

                    Enviaspect::where('slno', $s)
                        ->update($data);
                }
            }
            $n = $n + 1;
        }

        $models = DB::table('modenadisable')->First();
        return redirect('/user/enviaspects/folder')->with('model', $models);
    }
    //edit mr

    public function editenvi(Request $req)
    {
        $mr = DB::table('representatives')->pluck('email');
        $md = DB::table('managements')->pluck('email');
        $slno = $req->input('slno');

        $department = DB::table('departments')->get();
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);

            $m = count($id);

            $n = 0;
            foreach ($id as $d) {

                //$count=Enviaspect::where('E',$id)->count();
                //$x=0;
                if ($n < $m) {

                    //  if($x<$count)

                    // {

                    $data[] = Enviaspect::where('slno', $d)->first();
                }

                ///  $x=$x+1;

            }

            $n = $n + 1;
        }

        $models = DB::table('modenadisable')->First();
        return view('user/enviaspects/editenvi')->withData($data)
            ->with('id', $id)
            ->with('mr', $mr)
            ->with('md', $md)
            ->with('department', $department)
        // ->withPurpose($purpose)
            ->with('model', $models);
    }

    public function editrequest_envi(Request $req)
    {
        $m = count($req->aspects);

        $aspects = $req->input('aspects');
        $er = $req->input('er');
        // $agreedaction = $req->input('purpose');
        $potential = $req->input('potential');
        $A = $req->input('A');
        $B = $req->input('B');
        $C = $req->input('C');
        $D = $req->input('D');
        $E = $req->input('E');
        $HS = $req->input('HS');
        $control = $req->input('control');

        $sno = $req->input('slno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = DB::table('enviaspects')
                    ->where('slno', $d)->first();
            }
        }

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->slno == $s && $n < $m) {
                    $total = ($req->A[$n] * $req->B[$n] * $req->C[$n] * $req->D[$n] * $req->E[$n]);
                    if ($total >= 200) {
                        $HS = 'HS';
                    } else if ($total < 200 && $total >= 100) {
                        $HS = 'MS';
                    } else {
                        $HS = 'NS';
                    }

                    if ($d <= $n) {
                        $d = $d + 1;
                    }

                    Enviaspect::where('slno', $s)
                        ->update(array(
                            'enviaspects' => $req->aspects[$n],
                            'ER' => $req->er[$n],
                            'potential' => $req->potential[$n],
                            'A' => $req->A[$n],
                            'B' => $req->B[$n],
                            'C' => $req->C[$n],
                            'D' => $req->D[$n],
                            'E' => $req->E[$n],
                            'HS' => $req->HS[$n],
                            'control' => $req->control[$n],
                            'total' => $total,
                            'HS' => $HS,

                        ));
                    //    //insert data to activity table superadmin code
                    //    $activity="Context Issue is  Edited and Updated with new Details  where  ID ".$s;
                    //    $date=Carbon::now()->toDateTimeString();
                    //    $date=Carbon::parse($date);
                    //    $datas=array('name'=>Auth::user()->name,'email'=>Auth::user()->email,'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'),'activity'=>$activity,'type'=>'user');
                    //    DB::table('viewactivity')->insert($datas);
                    //    //end of superadmin code

                }
            }
            $n = $n + 1;
        }
        $models = DB::table('modenadisable')->First();
        return redirect('/user/enviaspects/folder')->with('model', $models);
    }

    //dissaprove and propose change
    public function propose(Request $req)
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $models = DB::table('modenadisable')->First();
            return view('user.enviaspects.propose')->withId($id)->with('model', $models);
        }
    }

    public function propose_change(Request $req)
    {
        $id = $req->input('id');
        $change = $req->input('change');
        //$reviewed_date = date('d-m-Y h:i:s A', time());
        $reviewed_date = date('Y-m-d H:i:s', time());
        $reviewed_by = Auth::user()->employeeid;
        $data1 = array('status' => 'Rejected', 'reviewed_at' => $reviewed_date, 'reviewed_by' => $reviewed_by);

        Environment::where('Eid', $id)
            ->update($data1);
        $data = array('proposechanges' => $change, 'reviewed_at' => $reviewed_date, 'reviewed_by' => $reviewed_by);
        DB::table('environment')
            ->where('Eid', $id)
            ->update($data);
        //inserting values to activity table
        $act = Environment::where('Eid', $id)->pluck('activity');
        $activity = "Environmental Aspects under Actvity " . $act . " is Disapproved with reviewer praposal as " . $change . " where Activity Id is " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        $models = DB::table('modenadisable')->First();
        return redirect('/user/enviaspects/folder')->with('model', $models);
    }

    //issue deleting non-required aspects
    public function deleteaspect(Request $req)
    {

        $a = $req->val;
        $departments = Departments::all();
        $data = Environment::where('Eid', $a)->get();
        $data2 = Enviaspect::where('Eid', $a)->get();
        $models = DB::table('modenadisable')->First();
        return view('user.enviaspects.deleteaspect')->withDepartments($departments)->withData($data)->withData2($data2)->with('model', $models);
    }

    //deleting confirm
    public function deleteaspectconf(Request $request)
    {
        $Eid = $request->input('Envid');
        $slno = $request->input('slno');

        //inserting values to activity table
        $act = Environment::where('Eid', $Eid)->pluck('activity');
        $enviasp = Enviaspect::where(['Eid' => $Eid, 'slno' => $slno])->pluck('enviaspects');
        $activity = "Environmental Aspect " . $enviasp . " under Actvity " . $act . " is Deleted where Environmental Id is " . $Eid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superamdin code
        Enviaspect::where(['Eid' => $Eid, 'slno' => $slno])->delete();
        Session::flash('msg', "Aspect deleted successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    public function deleteactivity(Request $request)
    {
        $Eid = $request->val;
        //inserting values to activity table
        $act = Environment::where('Eid', $Eid)->pluck('activity');
        $activity = "Environmental Activity " . $act . " and all Aspects Under this activity are Deleted where Environmental Id is " . $Eid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        Enviaspect::where('Eid', $Eid)->delete();
        Environment::where('Eid', $Eid)->delete();
        Session::flash('mssg', "Activity Deleted Successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    public function resubmit()
    {
        if (isset($_GET['val'])) {
            $Eid = $_GET['val'];

            $data = Environment::where('environment.Eid', $Eid)->get();
            $data1 = Enviaspect::where('enviaspects.Eid', $Eid)->get();
            $mn = DB::table('departments')->get();
            $departments = Departments::all();
            $users = User::all();
            foreach ($data as $dat) {
                $dep = $dat->department;

                $team = $dat->team;
            }
            $models = DB::table('modenadisable')->First();
            return view('user/enviaspects/resubmit')->with('model', $models)->withDep($dep)->withTeam($team)->withDepartments($departments)->withUsers($users)->withData($data)->withMn($mn)->with('Eid', $Eid)->withData1($data1);
        }
    }

    public function folder()
    {
        $departments = DB::table('departments')->get();
        // $envifolder=DB::table('envifolder')->pluck('foldername');
        $models = DB::table('modenadisable')->First();
        return view('user.enviaspects.folder')->with('model', $models)->withDepartments($departments);
        // ->withenvifolder($envifolder);
    }

    public function createfolder(Request $req)
    {
        $models = DB::table('modenadisable')->First();
        $foldername = $req->input('foldername');

        $data = array('foldername' => $foldername);
        // dd($data);
        DB::table('envifolder')->insert($data);

        return redirect('/user/enviaspects/folder')->with('model', $models);
    }
    public function updateresubmit(Request $req)
    {
        $activity = $req->input('activity');
        $date = $req->input('date');
        $m = count($req->aspects);
        $department = $req->input('department');
        $division = $req->input('division');
        $area = $req->input('area');
        $location = $req->input('location');
        $er = $req->input('er');
        $potential = $req->input('potential');
        $control = $req->input('control');
        $a = DB::table('users')->get();
        $e = Auth::user()->email;
        $teams = $req->input('team');

        foreach ($teams as $team) {
            $t[] = $team;
        }
        $tea = implode(',', $t);

        $Eid = $req->input('posted');
        $slno = $req->input('slno');

        $status = 'Review Pending';
        DB::table('environment')
            ->where('Eid', $Eid)
            ->update(array('status' => $status, 'activity' => $activity, 'department' => $department, 'division_id' => $division ?? 0, 'area_id' => $area ?? 0, 'date' => $date, 'team' => $tea, 'location' => $location));
        $data1 = Enviaspect::where('Eid', $Eid)->get();
        $n = 0;
        foreach ($data1 as $dat) {
            foreach ($slno as $s) {
                if ($dat->slno == $s && $n < $m) {
                    $total = ($req->A[$n] * $req->B[$n] * $req->C[$n] * $req->D[$n] * $req->E[$n]);

                    if ($total >= 200) {
                        $HS[$n] = 'HS';
                    } else if ($total < 200 && $total >= 100) {
                        $HS[$n] = 'MS';
                    } else {
                        $HS[$n] = 'NS';
                    }

                    Enviaspect::where('slno', $s)
                        ->update(array('enviaspects' => $req->aspects[$n], 'HS' => $HS[$n], 'ER' => $req->er[$n], 'E' => $req->E[$n], 'A' => $req->A[$n], 'B' => $req->B[$n], 'C' => $req->C[$n], 'D' => $req->D[$n], 'status' => 'Review Pending', 'department' => $department, 'potential' => $req->potential[$n]));
                }
            }
            $n = $n + 1;
        }

        //inserting values to activity table
        $activity = "Environmental Activity " . $activity . " is Resubmitted and its Status is Updated to Review Pending";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code

        $models = DB::table('modenadisable')->First();
        return redirect('/user/enviaspects/folder')->with('model', $models);
    }

    //delete request
    public function delete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            //inserting values to activity table
            $act = Environment::where('Eid', $id)->pluck('activity');
            $activity = "Activity " . $act . " is Deleted and all Aspects Under the activity this activity are Deleted";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //DB::delete('delete from environment where Eid = :eid',['eid'=>$id]);
            Enviaspect::where('slno', $id)->delete();

            //Environment::where('Eid',$id)->delete();
            // $models=DB::table('modenadisable')->First();
            // return redirect('/user/opportunity/register')->with('model',$models);

        }
    }

    public function analysisshow()
    {
        $processes = DB::select("select departmentname from departments order by departmentname ASC");
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->departmentname);
        }

        $phigh = DB::select("select s.departmentname,COUNT(r.enviaspects)AS number
                   FROM  enviaspects AS r
                   right JOIN departments AS s
                   ON r.department= s.id
                   and r.status='Reviewed'
                   and r.HS='HS'
                   group by s.departmentname
                   order by s.departmentname ASC");

        $pmedium = DB::select("select s.departmentname,COUNT(r.enviaspects)AS number
                   FROM  enviaspects AS r
                   right JOIN departments AS s
                   ON r.department= s.id
                   and r.status='Reviewed'
                   and r.HS='MS'
                   group by s.departmentname
                   order by s.departmentname ASC");
        $plow = DB::select("select s.departmentname,COUNT(r.enviaspects)AS number
                   FROM  enviaspects AS r
                   right JOIN departments AS s
                   ON r.department= s.id
                   and r.status='Reviewed'
                   and r.HS='NS'
                   group by s.departmentname
                   order by s.departmentname ASC");
        $highp = [];
        $mediump = [];
        $lowp = [];
        foreach ($phigh as $key) {
            array_push($highp, $key->number);
        }
        foreach ($pmedium as $key) {
            array_push($mediump, $key->number);
        }
        foreach ($plow as $key) {
            array_push($lowp, $key->number);
        }

        $high = DB::select("select count(HS) AS number
                           FROM  enviaspects
                           where HS='HS' and status='Reviewed'
                          ");
        $medium = DB::select("select count(HS) AS number
                           FROM  enviaspects
                           where HS='MS' and status='Reviewed'
                          ");
        $low = DB::select("select count(HS) AS number
                          FROM  enviaspects
                          where HS='NS' and status='Reviewed'
                         ");
        $highk = [];
        foreach ($high as $key1) {
            array_push($highk, $key1->number);
        }
        $mediumk = [];
        foreach ($medium as $key1) {
            array_push($mediumk, $key1->number);
        }
        $lowk = [];
        foreach ($low as $key1) {
            array_push($lowk, $key1->number);
        }

        $RD = DB::select("select count(potential) AS number
                           FROM  enviaspects where potential='Resource Depletion' and status='Reviewed'
                          ");
        $RDK = [];
        foreach ($RD as $key1) {
            array_push($RDK, $key1->number);
        }

        $AP = DB::select("select count(potential) AS number
                           FROM  enviaspects where potential='Air Pollution' and status='Reviewed'
                          ");
        $APK = [];
        foreach ($AP as $key1) {
            array_push($APK, $key1->number);
        }

        $WP = DB::select("select count(potential) AS number
                           FROM  enviaspects where potential='Water Pollution' and status='Reviewed'
                          ");
        $WPK = [];
        foreach ($WP as $key1) {
            array_push($WPK, $key1->number);
        }

        $NV = DB::select("select count(potential) AS number
                           FROM  enviaspects where potential='Noise and Vibrational Effect' and status='Reviewed'
                          ");
        $NVK = [];
        foreach ($NV as $key1) {
            array_push($NVK, $key1->number);
        }

        $LC = DB::select("select count(potential) AS number
                           FROM  enviaspects where potential='Land Contamination' and status='Reviewed'
                          ");
        $LCK = [];
        foreach ($LC as $key1) {
            array_push($LCK, $key1->number);
        }

        $OD = DB::select("select count(potential) AS number
                           FROM  enviaspects where potential='Ozone Depletion' and status='Reviewed'
                          ");
        $ODK = [];
        foreach ($OD as $key1) {
            array_push($ODK, $key1->number);
        }

        $high = Enviaspect::where('enviaspects.status', 'Reviewed')
            ->where('enviaspects.HS', 'HS')
            ->get();
        $highcount = count($high);
        $medium = Enviaspect::where('enviaspects.status', 'Reviewed')
            ->where('enviaspects.HS', 'MS')
            ->get();
        $mediumcount = count($medium);
        $low = Enviaspect::where('enviaspects.status', 'Reviewed')
            ->where('enviaspects.HS', 'NS')
            ->get();
        $lowcount = count($low);
        $C = Enviaspect::where('enviaspects.status', 'Reviewed')
            ->where('enviaspects.C', '2')
            ->get();
        $Ccount = count($C);
        $models = DB::table('modenadisable')->First();
        // return view('user.enviaspects.analysis')->with('model',$models)->with('process',json_encode($process,JSON_NUMERIC_CHECK))->with('highp',json_encode($highp,JSON_NUMERIC_CHECK));

        return view('user.enviaspects.analysis')->with('Ccount', $Ccount)
            ->with('model', $models)
            ->with('lowcount', $lowcount)
            ->with('mediumcount', $mediumcount)
            ->with('highcount', $highcount)
            ->with('RDK', json_encode($RDK, JSON_NUMERIC_CHECK))
            ->with('APK', json_encode($APK, JSON_NUMERIC_CHECK))
            ->with('WPK', json_encode($WPK, JSON_NUMERIC_CHECK))
            ->with('NVK', json_encode($NVK, JSON_NUMERIC_CHECK))
            ->with('LCK', json_encode($LCK, JSON_NUMERIC_CHECK))
            ->with('ODK', json_encode($ODK, JSON_NUMERIC_CHECK))
            ->with('highk', json_encode($highk, JSON_NUMERIC_CHECK))
            ->with('lowk', json_encode($lowk, JSON_NUMERIC_CHECK))
            ->with('mediumk', json_encode($mediumk, JSON_NUMERIC_CHECK))
            ->with('highp', json_encode($highp, JSON_NUMERIC_CHECK))
            ->with('mediump', json_encode($mediump, JSON_NUMERIC_CHECK))
            ->with('process', json_encode($process, JSON_NUMERIC_CHECK))
            ->with('lowp', json_encode($lowp, JSON_NUMERIC_CHECK));
    }
}
