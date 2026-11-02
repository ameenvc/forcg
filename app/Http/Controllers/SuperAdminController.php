<?php
namespace App\Http\Controllers;

use App;
use App\Admin;
use App\Charts\ModuleAnalysis;
use App\ClientDetail;
use App\CofingureMailSettings;
use App\Mail\ActivationCompleted;
use App\Mail\ForActivation;
use App\Mail\MasterResetCode;
use App\Type;
use App\User;
use App\ViewActivity;
use App\UserGate;
use Auth;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;
// use App\Providers\ConfigMail;
use Session;

class SuperAdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:superadmin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $admin = DB::table('admins')->get();
        if (count($admin) > 0) {
            $type = Auth::user()->type;
            session(['type' => $type]);
            return view('superadmin/dashboard');
        }
        //client activation
        else {
            $type = Auth::user()->type;
            session(['type' => $type]);
            $clientname = "";

            return view('superadmin/lisenceActivation')->with('name', $clientname);
        }

    }
    public function enadismoduleview()
    {
        $modules = DB::table('modenadisable')->First();
        return view('superadmin/enabledisable')->with('module', $modules);
    }
    //function for enable desable modules
    public function enadisconfirm(Request $request)
    {
        if ($request->has('obj')) {
            DB::table('modenadisable')->where('id', 1)->update(['objectives' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['objectives' => 0]);
        }

        if ($request->has('audit')) {
            DB::table('modenadisable')->where('id', 1)->update(['auditmanagement' => 1]);
            if ($request->has('auditschedule')) {
                DB::table('modenadisable')->where('id', 1)->update(['auditschedulelogs' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['auditschedulelogs' => 0]);
            }

        } else {
            DB::table('modenadisable')->where('id', 1)->update(['auditmanagement' => 0]);
            DB::table('modenadisable')->where('id', 1)->update(['auditschedulelogs' => 0]);
        }

        if ($request->has('comp')) {
            DB::table('modenadisable')->where('id', 1)->update(['compliance' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['compliance' => 0]);
        }

        if ($request->has('survey')) {
            DB::table('modenadisable')->where('id', 1)->update(['customer_satisfaction' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['customer_satisfaction' => 0]);
        }

        if ($request->has('elearning')) {
            DB::table('modenadisable')->where('id', 1)->update(['elearning' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['elearning' => 0]);
        }

        if ($request->has('rm')) {
            DB::table('modenadisable')->where('id', 1)->update(['riskmanagement' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['riskmanagement' => 0]);
        }

        if ($request->has('op')) {
            DB::table('modenadisable')->where('id', 1)->update(['opportunity' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['opportunity' => 0]);
        }

        if ($request->has('as')) {
            DB::table('modenadisable')->where('id', 1)->update(['correctiveaction' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['correctiveaction' => 0]);
        }

        if ($request->has('management_review')) {
            DB::table('modenadisable')->where('id', 1)->update(['managementreview' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['managementreview' => 0]);
        }

        if ($request->has('emergency_response')) {
            DB::table('modenadisable')->where('id', 1)->update(['emergencyresponse' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['emergencyresponse' => 0]);
        }

        if ($request->has('ea')) {
            DB::table('modenadisable')->where('id', 1)->update(['enviaspects' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['enviaspects' => 0]);
        }

        if ($request->has('ohs')) {
            DB::table('modenadisable')->where('id', 1)->update(['ohsrisk' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['ohsrisk' => 0]);
        }

        if ($request->has('da')) {
            DB::table('modenadisable')->where('id', 1)->update(['documentmanagement' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['documentmanagement' => 0]);
        }

        if ($request->has('cmpl')) {
            DB::table('modenadisable')->where('id', 1)->update(['complaintmanagement' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['complaintmanagement' => 0]);
        }

        if ($request->has('cntx')) {
            DB::table('modenadisable')->where('id', 1)->update(['contextmanagement' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['contextmanagement' => 0]);
        }

        if ($request->has('vendor')) {
            DB::table('modenadisable')->where('id', 1)->update(['vendormanagement' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['vendormanagement' => 0]);
        }

        if ($request->has('incident')) {
            DB::table('modenadisable')->where('id', 1)->update(['incidentreport' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['incidentreport' => 0]);
        }

        if ($request->has('hse')) {
            DB::table('modenadisable')->where('id', 1)->update(['hse' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['hse' => 0]);
        }

        if ($request->has('hr')) {
            DB::table('modenadisable')->where('id', 1)->update(['hr' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['hr' => 0]);
        }

        if ($request->has('maintainance')) {
            DB::table('modenadisable')->where('id', 1)->update(['maintainance' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['maintainance' => 0]);
        }

        if ($request->has('calibration')) {
            DB::table('modenadisable')->where('id', 1)->update(['calibration' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['calibration' => 0]);
        }

        if ($request->has('occhealth')) {
            DB::table('modenadisable')->where('id', 1)->update(['occhealth' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['occhealth' => 0]);
        }

        if ($request->has('wastemanagement')) {
            DB::table('modenadisable')->where('id', 1)->update(['wastemanagement' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['wastemanagement' => 0]);
        }

        if ($request->has('nearmiss')) {
            DB::table('modenadisable')->where('id', 1)->update(['nearmiss' => 1]);
        } else {
            DB::table('modenadisable')->where('id', 1)->update(['nearmiss' => 0]);
        }

        return $this->enadismoduleview();
    }

    public function viewlogs()
    {
        $admin = DB::table('admins')->get();
        if (count($admin) <= 0) {
            $type = Auth::user()->type;
            session(['type' => $type]);
            $clientname = "";
            return view('superadmin/lisenceActivation')->with('name', $clientname);
        }
        $users = User::where('approved', 1)->get();
        $admins = Admin::all();
        $today = Carbon::now()->toDateTimeString();
        $today = Carbon::parse($today);
        $todaylogs = DB::table('viewlogs')->orderBy('id', 'desc')->get();
        return view('superadmin/viewlogs')->with('todaylogs', $todaylogs)->with('users', $users)->with('admins', $admins);

    }
    public function searchlogs(Request $request)
    {
        if ($request->email == "All") {
            $date = Carbon::parse($request->date)->format('d-m-Y');
            $todaylogs = DB::table('viewlogs')->where('last_login_date', $date)->orderBy('last_login_time', 'desc')->get();
            $users = User::where('approved', 1)->get();
            $admins = Admin::all();
            return view('superadmin/viewlogs')->with('todaylogs', $todaylogs)->with('users', $users)->with('admins', $admins);
        } else {
            $date = Carbon::parse($request->date)->format('d-m-Y');
            $todaylogs = DB::table('viewlogs')->where('last_login_date', $date)->where('email', $request->email)->orderBy('last_login_time', 'desc')->get();
            $users = User::where('approved', 1)->get();
            $admins = Admin::all();
            return view('superadmin/viewlogs')->with('todaylogs', $todaylogs)->with('users', $users)->with('admins', $admins);

        }
    }
    public function compsize()
    {

        $users = User::where('approved', 1)->get();
        $admins = Admin::all();
        $rem = 0;
        $curr = 0;
        $size = 0;
        $currentsize = DB::table('companysize')->where('id', 1)->get();
        if ($currentsize->isEmpty()) {
            $currentsize->compsize = 0;
            $size = $currentsize->compsize;
            return view('superadmin/viewcompsize')->with('size', $size)->with('rem', $rem)->with('curr', $curr)->with('users', $users)->with('admins', $admins);
        } else {

            $size = $currentsize[0]->compsize;
            $curr = count($users) + count($admins);
            $rem = $size - $curr;
            if ($rem <= 0) {
                $rem = 0;
            }
            if ($curr == $size) {
                Session::flash('msg', "Company Successfully Reached Specified Size Limit");
                return view('superadmin/viewcompsize')->with('size', $size)->with('curr', $curr)->with('rem', $rem)->with('users', $users)->with('admins', $admins);
            } else {
                return view('superadmin/viewcompsize')->with('size', $size)->with('curr', $curr)->with('rem', $rem)->with('users', $users)->with('admins', $admins);
            }
        }
    }

    public function updatecompsize(Request $request)
    {
        $size = DB::table('companysize')->where('id', 1)->get();
        if (!$size->isEmpty()) {

            if ($request->dec) {
                $oldsize = DB::table('companysize')->where('id', 1)->First();
                if ($oldsize->compsize > 0) {
                    if (($oldsize->compsize - $request->size) <= 0) {
                        DB::table('companysize')->where('id', 1)->update(['compsize' => 0]);
                    } else {
                        $newsize = $oldsize->compsize - $request->size;
                        DB::table('companysize')->where('id', 1)->update(['compsize' => $newsize]);
                    }
                } else {
                    DB::table('companysize')->where('id', 1)->update(['compsize' => 0]);
                }
            } else {
                $oldsize = DB::table('companysize')->where('id', 1)->First();
                $newsize = $oldsize->compsize + $request->size;
                DB::table('companysize')->where('id', 1)->update(['compsize' => $newsize]);
            }

        } else {
            $data = array('email' => Auth::user()->email, 'name' => Auth::user()->name, 'compsize' => $request->size);
            DB::table('companysize')->where('id', 1)->insert($data);
        }
        return $this->compsize();
    }

    public function logout()
    {
        Auth::guard('superadmin')->logout();
        Session::flush();
        return redirect('/');
    }
    public function DevicelogsView()
    {
        $admin = DB::table('admins')->get();
        if (count($admin) <= 0) {
            $type = Auth::user()->type;
            session(['type' => $type]);
            $clientname = "";
            return view('superadmin/lisenceActivation')->with('name', $clientname);
        }

        $curperperson = DB::table('companysize')->where('id', 1)->pluck('loginsperperson')->First();
        $logs = DB::table('viewlogs')->orderBy('id', 'desc')->orderBy('last_login_time', 'desc')->orderBy('last_login_date', 'desc')->get();
        $users = User::where('approved', 1)->get();
        $admin = DB::table('admins')->get();
        return view('superadmin/contlogins')->with('perperson', $curperperson)->with('users', $users)->with('logs', $logs)->with('admins', $admin);
    }
    public function DevicelogsUpdate(Request $request)
    {
        if ($request->devices <= 0) {
            DB::table('companysize')->where('id', 1)->update(['loginsperperson' => 0]);
        } else {
            DB::table('companysize')->where('id', 1)->update(['loginsperperson' => $request->devices]);
        }
        return $this->DevicelogsView();
    }

//function for deleting logs
    public function deletelogs(Request $request)
    {
        $email = $request->delmail;
        $date = $request->deldate;
        $date = date("d-m-Y", strtotime($date));
        if ($email == "All") {
            DB::table('viewlogs')->where('last_login_date', '=', $date)->delete();
        } else {
            DB::table('viewlogs')->where('email', '=', $email)->where('last_login_date', '=', $date)->delete();
        }
        return $this->viewlogs();
    }

//function for view activity
    public function viewactivity()
    {
        $admin = DB::table('admins')->get();
        if (count($admin) <= 0) {
            $type = Auth::user()->type;
            session(['type' => $type]);
            $clientname = "";
            return view('superadmin/lisenceActivation')->with('name', $clientname);
        }

        $users = DB::table('users')->where('approved', 1)->get();
        $admins = DB::table('admins')->get();
        $today = Carbon::now()->toDateTimeString();
        $today = Carbon::parse($today);
        $activities = viewactivity::orderBy('id', 'desc')->get();
        return view('superadmin/viewactivity')->with('activities', $activities)->with('admins', $admins)->with('users', $users);
    }
//search activity
    public function searchactivity(Request $request)
    {
        if ($request->email == "All") {
            $date = Carbon::parse($request->date)->format('Y-m-d');
            $activities = viewactivity::where('date', $date)->orderBy('id', 'desc')->get();
            $users = User::where('approved', 1)->get();
            $admins = Admin::all();
            return view('superadmin/viewactivity')->with('activities', $activities)->with('users', $users)->with('admins', $admins);
        } else {
            $date = Carbon::parse($request->date)->format('Y-m-d');
            $activities = viewactivity::where('date', $date)->where('email', $request->email)->orderBy('id', 'desc')->get();
            $users = User::where('approved', 1)->get();
            $admins = Admin::all();
            return view('superadmin/viewactivity')->with('activities', $activities)->with('users', $users)->with('admins', $admins);

        }
    }

//function for modle analysis and passing deafult argument to the function for making filtering easy
    public function moduleoverview($year = null)
    {
        if ($year == null) {
            $year = Carbon::parse(Carbon::now()->toDateTimeString());
            $year = $year->format('Y');
        }
        //below query is to get the user name and login date per month year
        // $logins=DB::table('viewlogs')->select('email','last_login_date',DB::raw('Count(*) as `count`'))
        // ->groupBy('email','last_login_date')
        // ->having('count','>',1)
        // ->get();
        $logins = DB::table('viewlogs')->select('email', 'last_login_date')->get();
        $totapprovedusers = count(User::where('approved', 1)->get());
        $totregusers = count(User::get());
        $compsize = DB::table('companysize')->where('id', 1)->First();
        $registerdate = DB::table('users')->pluck('created_at');
        $janperson = $febperson = $marperson = $aprperson = $mayperson = $junperson = $julperson = $augperson = $sepperson = $octperson = $novperson = $decperson = [];
        $jan = $feb = $mar = $apr = $may = $jun = $july = $aug = $sep = $oct = $nov = $dec = 0;
        $regmonthinteger = [];
        $person = null;
        $username = null;
        // $regyear=[];
        $yearmonth = null;
        foreach ($registerdate as $reg) {
            $yearmonth = substr($reg, 0, strrpos($reg, "-"));
            if (substr($yearmonth, 0, 4) === $year) {
                // $regyear[]=substr($yearmonth,0,4);
                if (substr($yearmonth, 5, 2) == '1') {
                    $jan += 1;
                } elseif (substr($yearmonth, 5, 2) == '2') {
                    $feb += 1;
                } elseif (substr($yearmonth, 5, 2) == '3') {
                    $mar += 1;
                } elseif (substr($yearmonth, 5, 2) == '4') {
                    $apr += 1;
                } elseif (substr($yearmonth, 5, 2) == '5') {
                    $may += 1;
                } elseif (substr($yearmonth, 5, 2) == '6') {
                    $jun += 1;
                } elseif (substr($yearmonth, 5, 2) == '7') {
                    $july += 1;
                } elseif (substr($yearmonth, 5, 2) == '8') {
                    $aug += 1;
                } elseif (substr($yearmonth, 5, 2) == '9') {
                    $sep += 1;
                } elseif (substr($yearmonth, 5, 2) == '10') {
                    $oct += 1;
                } elseif (substr($yearmonth, 5, 2) == '11') {
                    $nov += 1;
                } else {
                    $dec += 1;
                }

            }

        }

        foreach ($logins as $log) {
            $yearmonth = substr($log->last_login_date, 3, 7);
            if (substr($yearmonth, 3, 4) === $year) {
                $person = $log->email;
                if (substr($yearmonth, 0, 2) == '1') {if (!in_array($person, $janperson)) {array_push($janperson, $person);}} elseif (substr($yearmonth, 0, 2) == '2') {if (!in_array($person, $febperson)) {array_push($febperson, $person);}} elseif (substr($yearmonth, 0, 2) == '3') {if (!in_array($person, $marperson)) {array_push($marperson, $person);}} elseif (substr($yearmonth, 0, 2) == '4') {if (!in_array($person, $aprperson)) {array_push($aprperson, $person);}} elseif (substr($yearmonth, 0, 2) == '5') {if (!in_array($person, $mayperson)) {array_push($mayperson, $person);}} elseif (substr($yearmonth, 0, 2) == '6') {if (!in_array($person, $junperson)) {array_push($junperson, $person);}} elseif (substr($yearmonth, 0, 2) == '7') {if (!in_array($person, $julperson)) {array_push($julperson, $person);}} elseif (substr($yearmonth, 0, 2) == '8') {if (!in_array($person, $augperson)) {array_push($augperson, $person);}} elseif (substr($yearmonth, 0, 2) == '9') {if (!in_array($person, $sepperson)) {array_push($sepperson, $person);}} elseif (substr($yearmonth, 0, 2) == '10') {if (!in_array($person, $octperson)) {array_push($octperson, $person);}} elseif (substr($yearmonth, 0, 2) == '11') {if (!in_array($person, $novperson)) {array_push($novperson, $person);}} else {if (!in_array($person, $decperson)) {array_push($decperson, $person);}}
            }
        }

        //chart for analysing registration of user per month
        $chart3 = new ModuleAnalysis;
        $chart3->minimalist(true);
        $chart3->title("Line Chart Showing No.of Registration and No.of Users Logged in Per Month in the Year " . $year);
        $chart3->labels(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);
        $chart3->dataset('Registration', 'line', [$jan, $feb, $mar, $apr, $may, $jun, $july, $aug, $sep, $oct, $nov, $dec])->color('cyan')->backgroundcolor('none');
        $chart3->dataset('Users Logged in', 'line', [count($janperson), count($febperson), count($marperson), count($aprperson), count($mayperson), count($junperson), count($julperson), count($augperson), count($sepperson), count($octperson), count($novperson), count($decperson)])->color('green');

        $pageloadbarcolor = "#3C99DC";
        $utilizebarcolor = "#66D3FA";
        $bordercolors = [
            "rgba(255,99,132,1.0)",
            "rgba(22,160,133,1.0)",
            "rgba(255,205,86,1.0)",
            "rgba(51,105,232,1.0)",
            "rgba(244,67,54,1.0)",
            "rgba(255,99,132,1.0)",
            "rgba(34,198,246,1.0)",
            "rgba(153,102,255,1.0)",
            "rgba(255,159,64,1.0)",
            "rgba(233,159,642,1.0)",
            "rgba(233,30,99,1.0)",
            "rgba(233,99,205,1.0)",
            "rgba(205,220,57,1.0)",
        ];
        $fillcolors = [
            "rgba(255,99,132,1.0)",
            "rgba(22,160,133,1.0)",
            "rgba(255,205,86,1.0)",
            "rgba(51,105,232,1.0)",
            "rgba(244,67,54,1.0)",
            "rgba(255,99,132,1.0)",
            "rgba(34,198,246,1.0)",
            "rgba(153,102,255,1.0)",
            "rgba(255,159,64,1.0)",
            "rgba(233,159,642,1.0)",
            "rgba(233,30,99,1.0)",
            "rgba(233,99,205,1.0)",
            "rgba(205,220,57,1.0)",
        ];

        $chart1 = new ModuleAnalysis;
        $chart2 = new ModuleAnalysis;

        $chart1->minimalist(true);
        $chart2->minimalist(true);
        $chart1->title("Bar Chart");
        $chart2->title("Pie Chart");
        $chart1->labels(['NCR & Corrective Actions', 'Enterprise Risk', 'Objectives', 'Opportunity', 'Compliance', 'Enviaspects', 'Audit Management', 'OHS Risk', 'Document Management', 'Context Management', 'Customer Complaint', 'Vendor Management', 'Incident Report', 'Hse Observation', 'HR', 'Maintenance', 'Calibration', 'Occupational Health', 'Waste Management']);
        $chart2->labels(['NCR & Corrective Actions', 'Enterprise Risk', 'Objectives', 'Opportunity', 'Compliance', 'Enviaspects', 'Audit Management', 'OHS Risk', 'Document Management', 'Context Management', 'Customer Complaint', 'Vendor Management', 'Incident Report', 'Hse Observation', 'HR', 'Maintenance', 'Calibration', 'Occupational Health', 'Waste Management']);
        $modules = [];
        $modpageload = DB::table('modpageload')->where('id', 1)->get();
        if (count($modpageload) > 0) {
            $total = $modpageload[0]->correctiveaction + $modpageload[0]->riskmanagement + $modpageload[0]->objectives + $modpageload[0]->opportunity + $modpageload[0]->compliance + $modpageload[0]->enviaspects + $modpageload[0]->auditmanagement + $modpageload[0]->ohsrisk + $modpageload[0]->documentmanagement + $modpageload[0]->contextmanagement + $modpageload[0]->complaintmanagement + $modpageload[0]->vendormanagement + $modpageload[0]->incidentreport + $modpageload[0]->hse + $modpageload[0]->hr + $modpageload[0]->maintainance + $modpageload[0]->calibration + $modpageload[0]->occhealth + $modpageload[0]->wastemanagement;
            $correctiveaction = ($modpageload[0]->correctiveaction / $total) * 100;
            $riskmanagement = ($modpageload[0]->riskmanagement / $total) * 100;
            $objectives = ($modpageload[0]->objectives / $total) * 100;
            $opportunity = ($modpageload[0]->opportunity / $total) * 100;
            $compliance = ($modpageload[0]->compliance / $total) * 100;
            $enviaspects = ($modpageload[0]->enviaspects / $total) * 100;
            $auditmanagement = ($modpageload[0]->auditmanagement / $total) * 100;
            $ohsrisk = ($modpageload[0]->ohsrisk / $total) * 100;
            $documentmanagement = ($modpageload[0]->documentmanagement / $total) * 100;
            $contextmanagement = ($modpageload[0]->contextmanagement / $total) * 100;
            $complaintmanagement = ($modpageload[0]->complaintmanagement / $total) * 100;
            $vendormanagement = ($modpageload[0]->vendormanagement / $total) * 100;
            $incidentreport = ($modpageload[0]->incidentreport / $total) * 100;
            $hse = ($modpageload[0]->hse / $total) * 100;
            $maintainance = ($modpageload[0]->maintainance / $total) * 100;
            $hr = ($modpageload[0]->hr / $total) * 100;
            $calibration = ($modpageload[0]->calibration / $total) * 100;
            $occhealth = ($modpageload[0]->occhealth / $total) * 100;
            $wastemanagement = ($modpageload[0]->wastemanagement / $total) * 100;

            $chart1->dataset('Utilized %', 'bar', [round($correctiveaction, 2), round($riskmanagement, 2), round($objectives, 2), round($opportunity, 2), round($compliance, 2), round($enviaspects, 2), round($auditmanagement, 2), round($ohsrisk, 2), round($documentmanagement, 2), round($contextmanagement, 2), round($complaintmanagement, 2), round($vendormanagement, 2), round($incidentreport, 2), round($hse, 2), round($hr, 2), round($maintainance, 2), round($calibration, 2), round($occhealth, 2), round($wastemanagement, 2)])->color($utilizebarcolor)->backgroundcolor($utilizebarcolor);
            $chart1->dataset('Page Loads', 'bar', [$modpageload[0]->correctiveaction, $modpageload[0]->riskmanagement, $modpageload[0]->objectives, $modpageload[0]->opportunity, $modpageload[0]->compliance, $modpageload[0]->enviaspects, $modpageload[0]->auditmanagement, $modpageload[0]->ohsrisk, $modpageload[0]->documentmanagement, $modpageload[0]->contextmanagement, $modpageload[0]->complaintmanagement, $modpageload[0]->vendormanagement, $modpageload[0]->incidentreport, $modpageload[0]->hse, $modpageload[0]->hr, $modpageload[0]->maintainance, $modpageload[0]->calibration, $modpageload[0]->occhealth, $modpageload[0]->wastemanagement])->color($pageloadbarcolor)->backgroundcolor($pageloadbarcolor);
            $chart2->dataset('Utilized %', 'doughnut', [round($correctiveaction, 2), round($riskmanagement, 2), round($objectives, 2), round($opportunity, 2), round($compliance, 2), round($enviaspects, 2), round($auditmanagement, 2), round($ohsrisk, 2), round($documentmanagement, 2), round($contextmanagement, 2), round($complaintmanagement, 2), round($vendormanagement, 2), round($incidentreport, 2), round($hse, 2), round($hr, 2), round($maintainance, 2), round($calibration, 2), round($occhealth, 2), round($wastemanagement, 2)])->color($bordercolors)->backgroundcolor($fillcolors);
        }
        //   dd($chart1);
        $admin = DB::table('admins')->get();
        if (count($admin) <= 0) {
            $type = Auth::user()->type;
            session(['type' => $type]);
            $clientname = "";
            return view('superadmin/lisenceActivation')->with('name', $clientname);
        }

        $modpageload = DB::table('modpageload')->where('id', 1)->get();
        $users = User::where('approved', 1)->get();
        $admins = Admin::all();
        return view('superadmin/moduleoverview', compact('janperson', 'febperson', 'marperson', 'aprperson', 'mayperson', 'junperson', 'julperson', 'augperson', 'sepperson', 'octperson', 'novperson', 'decperson'))->with('compsize', $compsize->compsize)->with('totappuser', $totapprovedusers)->with('totuser', $totregusers)->with('modpageload', $modpageload)->with('chart3', $chart3)->with('chart2', $chart2)->with('chart1', $chart1)->with('users', $users)->with('admins', $admins);
    }

    public function moduleoverviewfilter(Request $req)
    {
        return $this->moduleoverview($req->input('year'));
    }

    public function generate(Request $req)
    {
        $name = $req->name;
        $email = $req->email;
        $company_name = $req->company_name;
        function generate_lisence($suffix = null)
        {
            if (isset($suffix)) {
                $num_segments = 3;
                $segment_chars = 6;
            } else {
                $num_segments = 4;
                $segment_chars = 5;
            }
            $tokens = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
            $lisence_string = '';
            for ($i = 0; $i < $num_segments; $i++) {
                $segment = '';
                for ($j = 0; $j < $segment_chars; $j++) {
                    $segment .= $tokens[rand(0, strlen($tokens) - 1)];
                }
                $lisence_string .= $segment;
                if ($i < ($num_segments - 1)) {
                    $lisence_string .= '-';
                }
            }
            if (isset($suffix)) {
                if (is_numeric($suffix)) {
                    $lisence_string .= '-' . strtoupper(base_convert($suffix, 10, 30));
                } else {
                    $lisence_string .= '-' . strtoupper(str_ireplace(' ', '-', $suffix));
                }
            }

            return $lisence_string;
        }
        $lisence = generate_lisence($email);
        $sadmin = DB::table('super_admins')->get();
        $data = array(
            //server info admin email,
            'line1' => Lang::get('Dear ' . $sadmin[0]->name),
            'line2' => Lang::get('This mail is regarding the Activation of the client ' . $name . ' with email ' . $email . ' for server ' . $req->getHttpHost() . '.'), //waraq;
            'line3' => Lang::get('Use lisence code: ' . $lisence . ' to Activate above account.', ['count' => config('auth.passwords.users.expire')]),
        );
        Mail::to($sadmin[0]->email)->send(new ForActivation($data));
        session(['key' => $lisence]);
        session(['email' => $email]);
        session(['name' => $name]);
        session(['company_name' => $company_name]);
        Session::flash('mailed', 'Activation code has been sent to ' . $sadmin[0]->email);

        return view('superadmin/lisenceActivation')->with('name', $name)->with('email', $email)->with('company_name',$company_name);
    }

    public function activationComplete(Request $req)
    {
        $pass = $req->password;
        $code = $req->code;
        $filename = "";
        if ($code === Session::get('key')) {
            if (!File::exists(public_path('storage/clientlogos'))) {
                mkdir(public_path('storage/clientlogos'), 0700, true);
            }
            if ($req->hasfile('logo')) {
                $avatar = $req->file('logo');
                $filename = time() . '.' . $avatar->getClientOriginalExtension();
                $destination_path = public_path('storage/clientlogos');
                $avatar->move($destination_path, $filename);
            }
            if ($req->app == "quickms") {
                $appname = "quickms_logo.png";
            } else if ($req->app == "quickcg") {
                $appname = "quickcg_logo.png";
            }
            $cap = ucwords(Session::get('name'));
            $company_name = ucwords(Session::get('company_name'));
            //Insert company details
            $data = [
                'company_name' => $company_name,
                'client_logo' => $filename,
                'appname_to_display' => $req->appname
            ];
            $exists = ClientDetail::all();
            if (count($exists) > 0) {
                ClientDetail::truncate();
            }
            ClientDetail::create($data);
            $data1 = array('name' => $cap, 'email' => Session::get('email'), 'password' => Hash::make($pass), 'code' => Hash::make($code), 'employeeid' => '1', 'appname' => $appname, 'appname_to_display' => $req->appname);
            DB::table('admins')->insert($data1);
            $data2 = array('name' => $cap, 'email' => Session::get('email'), 'password' => Hash::make($pass), 'approved' => '1', 'employeeid' => '1','is_email_verified'=>1);
            DB::table('users')->insert($data2);
            $latest_data = User::latest('id')->first();
            $latest_user_id = $latest_data->id;
            UserGate::create([
                'key' => 'enable-all',
                'users_id' => $latest_user_id,
                'saver_users_id' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $data = array('name' => $cap, 'email' => Session::get('email'));
            DB::table('representatives')->insert($data);
            DB::table('complaintreviewers')->insert($data);
            DB::table('complaintregisters')->insert($data);
            DB::table('notifiers')->insert($data);
            DB::table('riskreviewers')->insert($data);
            DB::table('escalateriskreceivers')->insert($data);
            // DB::table('archiveprivileges')->insert($data);
            DB::table('closurereviewers')->insert($data);
            DB::table('issuereviewers')->insert($data);
            DB::table('legalcoordinators')->insert($data);
            DB::table('hses')->insert($data);
            DB::table('managements')->insert($data);
            DB::table('envireviewers')->insert($data);
            DB::table('doccontrollers')->insert($data);

            //inserting values to modenadisable table
            if ($req->has('obj')) {
                DB::table('modenadisable')->where('id', 1)->update(['objectives' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['objectives' => 0]);
            }

            if ($req->has('audit')) {
                DB::table('modenadisable')->where('id', 1)->update(['auditmanagement' => 1]);
                if ($req->has('auditschedule')) {
                    DB::table('modenadisable')->where('id', 1)->update(['auditschedulelogs' => 1]);
                } else {
                    DB::table('modenadisable')->where('id', 1)->update(['auditschedulelogs' => 0]);
                }
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['auditmanagement' => 0]);
                DB::table('modenadisable')->where('id', 1)->update(['auditschedulelogs' => 0]);
            }
            if ($req->has('comp')) {
                DB::table('modenadisable')->where('id', 1)->update(['compliance' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['compliance' => 0]);
            }

            if ($req->has('rm')) {
                DB::table('modenadisable')->where('id', 1)->update(['riskmanagement' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['riskmanagement' => 0]);
            }

            if ($req->has('op')) {
                DB::table('modenadisable')->where('id', 1)->update(['opportunity' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['opportunity' => 0]);
            }

            if ($req->has('as')) {
                DB::table('modenadisable')->where('id', 1)->update(['correctiveaction' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['correctiveaction' => 0]);
            }

            if ($req->has('ea')) {
                DB::table('modenadisable')->where('id', 1)->update(['enviaspects' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['enviaspects' => 0]);
            }

            if ($req->has('ohs')) {
                DB::table('modenadisable')->where('id', 1)->update(['ohsrisk' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['ohsrisk' => 0]);
            }

            if ($req->has('da')) {
                DB::table('modenadisable')->where('id', 1)->update(['documentmanagement' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['documentmanagement' => 0]);
            }

            if ($req->has('cmpl')) {
                DB::table('modenadisable')->where('id', 1)->update(['complaintmanagement' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['complaintmanagement' => 0]);
            }

            if ($req->has('cntx')) {
                DB::table('modenadisable')->where('id', 1)->update(['contextmanagement' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['contextmanagement' => 0]);
            }

            if ($req->has('vendor')) {
                DB::table('modenadisable')->where('id', 1)->update(['vendormanagement' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['vendormanagement' => 0]);
            }

            if ($req->has('incident')) {
                DB::table('modenadisable')->where('id', 1)->update(['incidentreport' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['incidentreport' => 0]);
            }

            if ($req->has('hse')) {
                DB::table('modenadisable')->where('id', 1)->update(['hse' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['hse' => 0]);
            }

            if ($req->has('hr')) {
                DB::table('modenadisable')->where('id', 1)->update(['hr' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['hr' => 0]);
            }

            if ($req->has('maintainance')) {
                DB::table('modenadisable')->where('id', 1)->update(['maintainance' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['maintainance' => 0]);
            }

            if ($req->has('calibration')) {
                DB::table('modenadisable')->where('id', 1)->update(['calibration' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['calibration' => 0]);
            }

            if ($req->has('occhealth')) {
                DB::table('modenadisable')->where('id', 1)->update(['occhealth' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['occhealth' => 0]);
            }

            if ($req->has('wastemanagement')) {
                DB::table('modenadisable')->where('id', 1)->update(['wastemanagement' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['wastemanagement' => 0]);
            }

            if ($req->has('nearmiss')) {
                DB::table('modenadisable')->where('id', 1)->update(['nearmiss' => 1]);
            } else {
                DB::table('modenadisable')->where('id', 1)->update(['nearmiss' => 0]);
            }

            $sadmin = DB::table('super_admins')->get();
            //inserting values to companysize table
            DB::table('companysize')->where('id', 1)->update(['compsize' => $req->compsize, 'name' => $sadmin[0]->name, 'email' => $sadmin[0]->email, 'loginsperperson' => 1]);

            //renaming App name image to ClientApp
            // if($req->app=="quickms"){
            // rename(public_path().'/images/quickms_logo.png',public_path().'/images/ClientApp.png');
            // }
            // else if($req->app=="quickcg"){
            // rename(public_path().'/images/quickcg_logo.png',public_path().'/images/ClientApp.png');
            // }

            $maildata1 = array(
                'line1' => Lang::get('Dear ' . $sadmin[0]->name),
                'line2' => Lang::get('This mail is to cofirm that the activation for ' . strtoupper($req->app) . ' account of the client ' . $company_name . ' with email ' . Session::get('email') . ' for the server ' . $req->getHttpHost() . " has been successfully completed. "),
            );
            Mail::to($sadmin[0]->email)->send(new ActivationCompleted($maildata1));

            $maildata2 = array(
                'line1' => Lang::get('Dear ' . Session::get('name')),
                'line2' => Lang::get('This mail is to cofirm that the activation for ' . strtoupper($req->app) . ' account for server ' . $req->getHttpHost() . ' is successfully completed.'),
            );
            Mail::to(Session::get('email'))->send(new ActivationCompleted($maildata2));

            //null session value
            session(['key' => ""]);
            session(['email' => ""]);
            session(['name' => ""]);
            Session::flash('msg', 'Client Activation Completed');
            return view('superadmin/dashboard');

        } else {
            Session::flash('msg', 'Activation Code Entered is Incorrect please try again');
            return view('superadmin/lisenceActivation')->with('name', Session::get('name'))->with('email', Session::get('email'))->with('lisence', Session::get('key'));
        }
    }
    public function initReset()
    {
        $admin = DB::table('admins')->get();
        if (count($admin) <= 0) {
            $type = Auth::user()->type;
            session(['type' => $type]);
            $clientname = "";
            return view('superadmin/lisenceActivation')->with('name', $clientname);
        }

        $masterset = "";
        session(['vericode' => ""]);
        $sadmin = DB::table('super_admins')->get();
        return view('superadmin/masterreset')->with('sadmin', $sadmin)->with('reset', $masterset);
    }
    public function getCode()
    {
        $masterset = "yes";

        $vericode = $this->generateCode();
        session(['vericode' => $vericode]);
        $sadmin = DB::table('super_admins')->get();
        $maildata1 = array(
            'line1' => Lang::get('Dear ' . $sadmin[0]->name),
            'line2' => Lang::get('This mail is regarding master reset verification code.  '),
            'line3' => Lang::get('Please use ' . $vericode . ' as your master reset confirmation code. '),
        );
        Mail::to($sadmin[0]->email)->send(new MasterResetCode($maildata1));
        Session::flash('mastermsg', 'We have mailed you master reset verification code');
        return view('superadmin/masterreset')->with('sadmin', $sadmin)->with('reset', $masterset);
        // return $vericode;
    }
    public function generateCode($suffix = null)
    {
        $num_segments = 1;
        $segment_chars = 6;
        $tokens = 'bA1cB2dC3eD4fE5gF6hG7iH8jI9kJ0lKm1Ln2Mo3Np4Oq5Pr6Qs7Rt8Su9Tv0Uw1Vx2Wy3Xz4Ya5Z';
        $lisence_string = '';
        for ($i = 0; $i < $num_segments; $i++) {
            $segment = '';
            for ($j = 0; $j < $segment_chars; $j++) {
                $segment .= $tokens[rand(0, strlen($tokens) - 1)];
            }
            $lisence_string .= $segment;
        }
        return $lisence_string;
    }

    public function MasterReset(Request $request)
    {
        $masterset = "yes";
        $sadmin = DB::table('super_admins')->get();
        if ($request->vcode == Session::get('vericode')) {
            if ($request->btn == 1) {
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $db = $this->dbbackup(\Config::get('database.connections.mysql.host'), \Config::get('database.connections.mysql.username'), \Config::get('database.connections.mysql.password'), \Config::get('database.connections.mysql.database'));
                echo $db;
                exit;
            } else if ($request->btn == 2) {
                $files = scandir(public_path('/storage'));
                $item = count($files);
                if ($item <= 2) {
                    session(['codeis' => $request->vcode]);
                    Session::flash('mastermsg', "You don't have any files to backup");
                } else {
                    $this->Zip1(public_path('/storage'));
                }
            } else if ($request->btn == 3) {
                $app = config('app.name');

                $this->ResetDBFiles($app, public_path('/storage' . '/'), \Config::get('database.connections.mysql.host'), \Config::get('database.connections.mysql.username'), \Config::get('database.connections.mysql.password'), \Config::get('database.connections.mysql.database'));
                if (!File::exists(public_path('/storage' . '/'))) {
                    File::makeDirectory(public_path() . '/storage' . '/');
                }

                Session::flash('mastermsg', 'Reset Completed');
                session(['vericode' => ""]);
                session(['codeis' => ""]);
            }
        } else {
            Session::flash('mastermsg', 'Inorrect verification code');
        }
        return view('superadmin/masterreset')->with('sadmin', $sadmin)->with('reset', $masterset);
    }

    public function Zip1($source)
    {
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $zip = new \ZipArchive();
        $zip_file = 'MasterResetBackupFiles' . $date->format('d-m-Y') . '.zip';
        $zip->open($zip_file, \ZIPARCHIVE::CREATE | \ZipArchive::OVERWRITE);
        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..'))) {
                    continue;
                }
                $file = realpath($file);

                if (is_dir($file) === true) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } else if (is_file($file) === true) {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        } else if (is_file($source) === true) {
            $zip->addFromString(basename($source), file_get_contents($source));
        }

        $zip->close();

        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($zip_file) . '"');
        header("Content-length: " . filesize($zip_file));
        header("Pragma: no-cache");
        header("Expires: 0");
        ob_clean();
        flush();
        readfile($zip_file);
        unlink($zip_file);
        exit;
    }
//db backup
    public function dbbackup($host, $user, $pass, $name, $tables = false, $backup_name = false)
    {
        set_time_limit(3000);
        $con = mysqli_connect($host, $user, $pass, $name);
        // $mysqli = new mysqli($host,$user,$pass,$name);
        mysqli_select_db($con, $name);
        mysqli_query($con, "SET NAMES 'utf8'");
        $queryTables = mysqli_query($con, 'SHOW TABLES');
        while ($row = $queryTables->fetch_row()) {
            $target_tables[] = $row[0];
        }
        if ($tables !== false) {
            $target_tables = array_intersect($target_tables, $tables);
        }
        $content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `" . $name . "`\r\n--\r\n\r\n\r\n";
        foreach ($target_tables as $table) {
            if (empty($table)) {
                continue;
            }
            $result = mysqli_query($con, 'SELECT * FROM `' . $table . '`');
            $fields_amount = $result->field_count;
            $rows_num = mysqli_affected_rows($con);
            $res = mysqli_query($con, 'SHOW CREATE TABLE ' . $table);
            $TableMLine = $res->fetch_row();
            $content .= "\n\n" . $TableMLine[1] . ";\n\n";
            $TableMLine[1] = str_ireplace('CREATE TABLE `', 'CREATE TABLE IF NOT EXISTS `', $TableMLine[1]);
            for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
                while ($row = $result->fetch_row()) {
                    if ($st_counter % 100 == 0 || $st_counter == 0) {
                        $content .= "\nINSERT INTO " . $table . " VALUES";
                    }
                    $content .= "\n(";
                    for ($j = 0; $j < $fields_amount; $j++) {
                        $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                        if (isset($row[$j])) {
                            $content .= '"' . $row[$j] . '"';
                        } else {
                            $content .= '""';
                        }
                        if ($j < ($fields_amount - 1)) {
                            $content .= ',';
                        }
                    }
                    $content .= ")";
                    if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
                        $content .= ";";
                    } else {
                        $content .= ",";
                    }
                    $st_counter = $st_counter + 1;
                }
            }$content .= "\n\n\n";
        }
        $content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
        $backup_name = $backup_name ? $backup_name : $name . '___(' . date('H-i-s') . '_' . date('d-m-Y') . ').sql';
        ob_get_clean();
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header('Content-Length: ' . (function_exists('mb_strlen') ? mb_strlen($content, '8bit') : strlen($content)));
        header("Content-disposition: attachment; filename=\"" . $backup_name . "\"");
        return $content;
    }

    public function ResetDBFiles($app, $source, $host, $user, $pass, $name, $tables = false, $backup_name = false)
    {
        //delete backups taken
        if (is_dir(storage_path('app/' . $app))) {
            Storage::disk('local')->deleteDirectory($app);
        }
        //deleting all files
        $source = str_replace('\\', '/', realpath($source));
        if (is_dir($source) === true) {
            File::deleteDirectory($source);
        }
        //truncating all tables keeping needed tables
        set_time_limit(3000);
        $con = mysqli_connect($host, $user, $pass, $name);
        // $mysqli = new mysqli($host,$user,$pass,$name);
        mysqli_select_db($con, $name);
        mysqli_query($con, "SET NAMES 'utf8'");
        $queryTables = mysqli_query($con, 'SHOW TABLES');
        while ($row = $queryTables->fetch_row()) {
            $target_tables[] = $row[0];
        }
        if ($tables !== false) {
            $target_tables = array_intersect($target_tables, $tables);
        }
        $content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `" . $name . "`\r\n--\r\n\r\n\r\n";
        foreach ($target_tables as $table) {
            if (empty($table)) {
                continue;
            } else if ($table == 'super_admins' || $table == 'companysize' || $table == 'modenadisable') {
                continue;
            } else {
                mysqli_query($con, 'TRUNCATE TABLE `' . $table . '`');
            }
        }
        mysqli_close($con);

        DB::table('companysize')->where('id', 1)->update(['compsize' => 0, 'loginsperperson' => 1, 'name' => "", 'email' => '']);
        DB::table('modenadisable')->where('id', 1)->update(['objectives' => 0, 'auditmanagement' => 0, 'compliance' => 0, 'riskmanagement' => 0, 'opportunity' => 0, 'documentmanagement' => 0, 'enviaspects' => 0, 'ohsrisk' => 0, 'correctiveaction' => 0, 'auditschedulelogs' => 0, 'complaintmanagement' => 0, 'contextmanagement' => 0]);
    }

    public function errorLogs()
    {
        $errors = DB::table('error_logs')->get();
        return view('superadmin.errorlogs')->with('error', $errors);
    }

//for email settings
    public function getOldEmailSetting()
    {
        $from = Config::get('mail.from');
        $from_address = $from['address'];
        $from_name = $from['name'];
        $oldsettings = array(
            'driver' => Config::get('mail.driver'),
            'host' => Config::get('mail.host'),
            'port' => Config::get('mail.port'),
            'from_address' => $from_address,
            'from_name' => $from_name,
            'encryption' => Config::get('mail.encryption'),
            'username' => Config::get('mail.username'),
            'password' => Config::get('mail.password'),
        );
        return view('superadmin.emailsettings')->with('oldsettings', $oldsettings);
    }

    public function updateNewEmailSetting(Request $request)
    {

        $mails = array(
            'driver' => $request->driver,
            'host' => $request->host,
            'port' => $request->port,
            'faddress' => $request->faddress,
            'fname' => $request->fname,
            'encryption' => $request->encryption,
            'username' => $request->uname,
            'password' => $request->password,
        );
        $configmail = new CofingureMailSettings($mails);
        Session::flash('emailmsg', 'Email Settings Updates Successfully');
        return redirect()->back();
    }
    public function showCustomFieldPage(){
        $enable = DB::table('customfield_enadisable')->first();
        $enable = $enable ? $enable->is_enable : false;
        return view('superadmin.custom_fields_configuration',compact('enable'));
    }
    public function updateCustomFieldEnadisable(Request $request){
        $enablebtn = $request->input('enadisable') ? true : false;
        $now = now();
        $record = DB::table('customfield_enadisable')->first();
        if($record){
            DB::table('customfield_enadisable')
                ->where('id', $record->id)
                ->update([
                    'is_enable' => $enablebtn,
                    'updated_at' => $now
                ]);
        }else{
            DB::table('customfield_enadisable')->insert([
                'is_enable' => $enablebtn,
                'updated_at' => $now,
                'created_at' => $now
            ]);
        }
        Session::flash('successmsg', 'Custom Field Configuration has been updated successfully.');
        return redirect()->route('superadmin.custom_fields');
    }

}
