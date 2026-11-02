<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Session;
use Illuminate\Support\Facades\DB;
use App;
use Form;
use DateTime;
use DateTimeZone;
use App\User;
use App\Admin;
use App\Departments;
use App\Purpose;
use App\Party;
use App\Priority;
use App\Changecategory;
use App\Changerequestreviewer;
use App\Changerequestapprove;
use App\Mail\ChangeRequestReviewed;
use App\Mail\ChangeRequestApproved;
use App\Mail\ChangeRequestReviewCompleted;
use App\Mail\NewChangeRequestRaised;
use Carbon\Carbon;
use App\Charts\ContextAnalysis;
use App\ContextCrs;
use App\ContextCrsSeverity;
use App\EmailNotification;
use App\Mail\ContextClosed;
use App\Mail\ContextCompleted;
use App\Mail\ContextNeedMoreAction;
use App\ModenaDisable;
use App\ViewActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use function Opis\Closure\unserialize;
use Illuminate\Support\Facades\Mail;
use Hamcrest\Arrays\IsArray;
use App\Mail\NewOhsRiskAdded;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContextController extends Controller
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
        return view('user.context.home');
    }


    public function issuereg()
    {
        //below is superadmin related code
        //below commented code is to keep track of page loads based on the present date
        // $contextmanagement=DB::table('modpageload')->get();
        // $today=Carbon::now()->toDateTimeString();
        // $today=Carbon::parse($today);
        // $present=false;
        // foreach( $contextmanagement as $date ){
        //     if($date->date===$today->format('d-m-Y')){
        //         DB::table('modpageload')->where('id',$date->id)->update(['contextmanagement'=>$date->contextmanagement+=1]);
        //         $present=true;
        //         break;
        //     }
        // }
        // if(!$present)
        //     DB::table('modpageload')->insert(['contextmanagement'=>1,'date'=>$today->format('d-m-Y')]);

        $contextmanagement = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($contextmanagement) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0, 'contextmanagement' => 1, 'complaintmanagement' => 0]);
        } else {
            $contextmanagement = DB::table('modpageload')->where('id', 1)->First();
            $contextmanagement->contextmanagement = $contextmanagement->contextmanagement + 1;
            DB::table('modpageload')->update(['contextmanagement' => $contextmanagement->contextmanagement]);
        }
        // superadmin code ends
        // issue register----------------------------------------------------------------------

        $issues = DB::table('context_issues')->orderBy('title')->paginate(100);
        $issuereviewers = DB::table('issuereviewers')->pluck('email');
        $mr = DB::table('representatives')->pluck('email');
        $departments = Departments::all();
        $parties = Party::all();
        $users = DB::table('users')->pluck('name');

        // interested party register----------------------------------------------------------

        $issues1 = DB::table('context_parties')->orderBy('type')->paginate(2000);
        $issues3 = DB::table('context_parties')
            ->join('context_partiesreqs', 'context_parties.Cid', '=', 'context_partiesreqs.Cid')
            ->select(
                'context_parties.Cid',
                'context_parties.count',
                'context_parties.partyid',
                'context_parties.status',
                'context_parties.intr_party',
                'context_parties.type',
                'context_parties.purpose',
                'context_parties.posted_by',
                'context_parties.updated_at',
                'context_partiesreqs.id',
                'context_partiesreqs.reqid',
                'context_partiesreqs.reqip',
                'context_partiesreqs.monitoring',
                'context_partiesreqs.accountability',
                'context_partiesreqs.created_at',
                'context_partiesreqs.reviewed_at'
            )
            ->paginate(2000);

        // CR log------------------------------------------------------------------------------
        $de = Auth::user()->email;
        $issues2 = DB::table('context_crs')->orderBy('department')->paginate(100);


        $models = DB::table('modenadisable')->First();
        return view('user.context.issuereg', compact('issues', 'issues1', 'issues2', 'issues3', 'users', 'parties', 'departments', 'issuereviewers', 'mr'))->with('model', $models);
    }

    public function view(Request $r)
    {
        // issue register----------------------------------------------------------------------

        $issuereviewers = DB::table('issuereviewers')->pluck('email');
        $mr = DB::table('representatives')->pluck('email');
        $departments = Departments::all();
        $parties = Party::all();
        $users = DB::table('users')->pluck('name');
        $type = null;
        $type = $r->input('type');

        $issues = DB::table('context_issues')->orderBy('title')
            ->orwhere('type', '=', $type)
            ->paginate(100);

        // interested party register----------------------------------------------------------
        $issues1 = DB::table('context_parties')
            ->orderBy('type')
            ->paginate(100);

        // CR log-----------------------------------------------------------------------------
        $de = Auth::user()->email;
        $issues2 = DB::table('context_crs')->orderBy('department')->paginate(100);


        $models = DB::table('modenadisable')->First();
        return view('user.context.issuereg', compact('issues', 'issues1', 'issues2', 'users', 'parties', 'departments', 'issuereviewers', 'mr'))->with('model', $models);
    }


    public function addissue()
    {
        $changereq_approval = Changerequestapprove::all();
        $changereq_reviewers = Changerequestreviewer::all();
        $parties = Party::all();
        $purpose = Purpose::all();
        $models = DB::table('modenadisable')->First();
        return view('user.context.addissue')
            ->withChangereq_reviewers($changereq_reviewers)
            ->withChangereq_approval($changereq_approval)
            ->withParties($parties)
            ->withPurpose($purpose)
            ->with('model', $models);
    }

    public function insertissue(Request $req)
    {

        $v = Validator::make($req->all(), [
            'purpose' => 'required',
            'bias' => 'required'
        ]);
        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $created_at = date('d-m-Y h:i:s A', time());
            // {{date('M/d/Y',strtotime($dat->created_at))}}
            $type = $req->input('type');
            if ($req->type) {
                $t = $req->input('type');
                $type = implode(',', $t);
            }
            $title = $req->input('title');
            $issue    =  $req->input('issue');
            $purpose   =  $req->input('purpose');
            $monitoring = $req->input('monitoring');
            $bias =  $req->input('bias');
            $accountability =  $req->input('accountability');
            if ($req->purpose) {
                $p = $req->input('purpose');
                $purpose = implode(',', $p);
            } else $purpose = NULL;

            $posted_by = $req->input('postedby');
            $exist = DB::table('context_issues')->where('issue', $issue)->orderBy('id', 'desc')->first();
            if ($exist) {
                $c = $exist->count;
                $count = $c + 1;
            } else {
                $count = "1";
            }

            $data = array(
                'created_at' => $created_at,
                'count' => $count,
                'type' => $type,
                'title' => $title,
                'issue' => $issue,
                'purpose' => $purpose,
                'monitoring' => $monitoring,
                'bias' => $bias,
                'accountability' => $accountability,
                'posted_by' => $posted_by
            );

            DB::table('context_issues')->insert($data);
            //  inserting data to activities table
            $activity = "An issue is added with type " . $type;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
            DB::table('viewactivity')->insert($datas);

            // $code=strtoupper(substr($title,0,3));
            // $issueid =$code.$count;
            // $data1 = array('issueid' => $issueid );
            // DB::table('context_issues')->where('count',$count)
            //                     ->where('issue', $issue)
            //                 ->update($data1);


            Session::flash('msg', "Issue Submitted Successfully");
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    public function addparty()
    {

        $party = Party::all();
        $purpose = Purpose::all();
        $models = DB::table('modenadisable')->First();
        return view('user.context.addparty')
            ->withParty($party)
            ->withPurpose($purpose)
            ->with('model', $models);
    }

    public function insertparty(Request $req)
    {

        //dd($req->doc);
        $v = Validator::make($req->all(), [
            'purpose' => 'required',
            'type' => 'required',
        ]);
        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $created_at = date('d-m-Y h:i:s A', time());
            $n = count($req->doc);
            $intr_party    =  $req->input('intr_party');
            $type = $req->input('type');
            $purpose   =  $req->input('purpose');
            $postedby = $req->input('postedby');
            $exist = DB::table('context_parties')->where('type', $type)->orderBy('Cid', 'desc')->first();
            if ($exist) {
                $c = $exist->count;
                $count = $c + 1;
            } else {
                $count = "1";
            }
            $data = array(
                'created_at' => $created_at,
                'count' => $count,
                'intr_party' => $intr_party,
                'type' => $type,
                'purpose' => $purpose,
                'posted_by' => $postedby
            );
            DB::table('context_parties')->insert($data);

            $code = strtoupper(substr($type, 0, 3));
            $partyid = $code . $count;

            $data = DB::table('context_parties')->orderBy('Cid', 'desc')->first();
            $Cid = $data->Cid;
            $d = 0;

            foreach ($req->doc as $key => $value) {
                $reqip = $value['reqip'];
                $monitoring = $value['monitoring'];
                $accountability = $value['accountability'];
                $data1 = array('partyid' => $partyid);
                DB::table('context_parties')->where('count', $count)
                    ->where('intr_party', $intr_party)
                    ->update($data1);
                if ($d <= $n)
                    $d = $d + 1;
                $data2 = array(
                    'Cid' => $Cid,
                    'intr_party' => $intr_party,
                    'reqip' => $value['reqip'],
                    'monitoring' => $value['monitoring'],
                    'accountability' => $value['accountability'],
                    'created_at'  => $created_at,
                    'reqid' => $d,
                );
                DB::table('context_partiesreqs')->insert($data2);
            }
            //  inserting data to activities table
            $activity = "An Interested Party has been added with name " . $intr_party;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
            DB::table('viewactivity')->insert($datas);


            Session::flash('msg', "Party has been added successfully");
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }


    public function addmoreparty()
    {
        if (isset($_GET['val'])) {
            // $party = Party::all();
            // $purpose = Purpose::all();
            $Cid = $_GET['val'];
            $data = DB::table('context_parties')->where('context_parties.Cid', $Cid)->get();
            $models = DB::table('modenadisable')->First();
            return view('user/context/addmoreparty')->withData($data)
                ->withCid($Cid)
                // ->withParty($party)
                // ->withPurpose($purpose)
                ->with('model', $models);
        }
    }

    public function insertmoreparty(Request $req)
    {

        $created_at = date('d-m-Y h:i:s A', time());
        $m = count($req->doc);
        $intr_party    =  $req->input('intr_party');
        $posted_by = $req->input('postedby');
        $Cid = $req->input('Cid');
        $data = array(
            'posted_by' => $posted_by,
            'created_at' => $created_at,
        );
        $d = DB::table('context_partiesreqs')->where('Cid', $Cid)->latest('reqid')->pluck('reqid')->first();
        $n = $m + $d;
        foreach ($req->doc as $key => $value) {
            $reqip = $value['reqip'];
            $monitoring = $value['monitoring'];
            $accountability = $value['accountability'];
            if ($d <= $n)
                $d = $d + 1;
            $data1 = array(
                'Cid' => $Cid,
                'intr_party' => $intr_party,
                'reqip' => $value['reqip'],
                'monitoring' => $value['monitoring'],
                'accountability' => $value['accountability'],
                'created_at'  => $created_at,
                'posted_by' => $posted_by,
                'reqid' => $d
            );
            DB::table('context_partiesreqs')->insert($data1);
        }
        //inserting values to activity table
        $activity = "New Party is Added with name " . $intr_party;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        Session::flash('msg', "Added successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }



    public function changelog()
    {
        $department = Departments::where('is_active',true)->get();
        $changecategory = Changecategory::all();
        $reviewer = Changerequestreviewer::pluck('email')->toArray();
        $approver = Changerequestapprove::pluck('email')->toArray();
        $reviewer = User::whereIn('email',$reviewer)->get()->sortBy('name');
        $approver = User::whereIn('email',$approver)->get()->sortBy('name');
        // $purpose = Purpose::all();
        $priority = Priority::all();
        $models = DB::table('modenadisable')->First();
        $severities = ContextCrsSeverity::all();
        return view('user.context.changelog')
            ->withChangecategory($changecategory)
            // ->withPurpose($purpose)
            ->withPriority($priority)
            ->withDepartment($department)
            ->withReviewer($reviewer)
            ->withApprover($approver)
            ->withSeverities($severities)
            ->with('model', $models);
    }

    public function insertrequest(Request $req)
    {
        $v = Validator::make($req->all(), [
            'scope' => 'required',
            'category' => 'required',
            'department' => 'required',
            'rev_authority' => 'required',
            'appr_authority' => 'required',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,xlsx,xls,pdf',
        ],[
            'department.required' => 'Department field is required.',
            'rev_authority.required' => 'Review Authority field is required.',
            'appr_authority.required' => 'Approve Authority field is required.'
        ]);

        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $cer = null;
            if ($req->hasFile('file')) {
                $file = $req->file('file');
                $file_size = $file->getClientOriginalName();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/context_files');
                $file->move($destination_path, $new_file_name);
                $file = $new_file_name;
                $cer = $file;
            }
            $currentUserDetail = User::where('email',Auth::user()->email)->first();
            $created_at = date('d-m-Y h:i:s A', time());
            $title    =  $req->input('title');
            $category = $req->input('category') ? implode(',',$req->input('category')) : null;
            $scope   =  $req->input('scope');
            $reason =  $req->input('reason');
            $benefits = $req->input('benefits');
            $impacts =  $req->input('impacts');
            $risk_identified =  $req->input('risk_identified');
            $initiator = $currentUserDetail->id;
            $department =  $req->input('department');
            $changing_product =  $req->input('changing_product');
            $priority =  $req->input('priority');
            $severity = $req->input('severity');
            $rev_authority =  $req->input('rev_authority');
            $appr_authority =  $req->input('appr_authority');
            $postedby = $req->input('postedby');

            $exist = DB::table('context_crs')->where('category', $category)->orderBy('id', 'desc')->first();
            if ($exist) {
                $c = $exist->count;
                $count = $c + 1;
            } else {
                $count = "1";
            }
            //-------------------------------Id generation part start-----------------------------------------
            // Retrieve all records for today's date
            $today = date('Y-m-d');
            $todayLatestContextCrs = ContextCrs::whereDate('created_at', $today)->orderByDesc('created_at')->first();
            if ($todayLatestContextCrs) {
                $lastFormatValue = intval(substr($todayLatestContextCrs->crid, -4));
                $formatValue = str_pad($lastFormatValue + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $formatValue = "0001";
            }
            do {
                $issueId = 'MOC-' . date('Ymd') . '-' . $formatValue;
                $formatValue = str_pad(intval($formatValue) + 1, 4, '0', STR_PAD_LEFT);
            } while (ContextCrs::where('crid', $issueId)->exists());
            //------------------------------------Id generation part end ------------------------------------------
            $data = [
                'created_at' => $created_at,
                'count' => $count,
                'title' => $title,
                'category' => $category,
                'scope' => $scope,
                'reason' => $reason,
                'benefits' => $benefits,
                'impacts' => $impacts,
                'risk_identified' => $risk_identified,
                'initiator' => $initiator,
                'department' => $department,
                'changing_product' => $changing_product,
                'priority' => $priority,
                'severities_id' => $severity,
                'rev_authority' => $rev_authority,
                'appr_authority' => $appr_authority,
                'postedby' => $postedby,
                'file' => $cer,
                'crid' => $issueId,
            ];

            DB::table('context_crs')->insert($data);
            //  inserting data to activities table
            $activity = "A Change Request has been added with scope " . $scope;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin');
            DB::table('viewactivity')->insert($datas);

            $dat = ContextCrs::with(['severity','priorityDetails','reviewer','departmentDetails'])->where('crid', $issueId)->first();
            $maildata = array(
                'crid' => $issueId,
                'crpriority' => $dat->priorityDetails ? $dat->priorityDetails->name:'',
                'crseverity' => $dat->severity ? $dat->severity->name : '-'
            );
            //Sending new request mail to HOD.
            if($dat->departmentDetails){
                Mail::to($dat->departmentDetails->hod)->send(new NewChangeRequestRaised($maildata));
            }
            //Sending new request to reviewal authority.

            if($dat->reviewer){
                Mail::to($dat->reviewer->email)->send(new NewChangeRequestRaised($maildata));
            }

            Session::flash('msg', "Change request has been added successfully");
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    public function adds()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $users = User::where('approved', 1)->get();
            $dat = ContextCrs::with([
                'severity','initiatorBy','departmentDetails','priorityDetails','reviewedBy',
                'approvedBy','approver','reviewer','closedBy','postedByDetails','correctiveDetails'
            ])->where('id', $id)->first();
            $cat = $dat->category ? explode(',',$dat->category) : [];
            $changecategory = Changecategory::all();
            $changeCategories = $changecategory->whereIn('id',$cat)->pluck('category')->toArray();
            $changeCategories = count($changeCategories) > 0 ? implode(',',$changeCategories) : implode(',',$cat);

            $department = Departments::where('is_active',true)->get();
            $approver = Changerequestapprove::pluck('email')->toArray();
            $approver = User::whereIn('email',$approver)->get()->sortBy('name');
            $priority = Priority::all();
            $models = DB::table('modenadisable')->First();
            $severities = ContextCrsSeverity::all();

            return view('user/context/review',compact('changeCategories','priority','approver','changecategory'))->with([
                'model' => $models,
                'priority' => $priority,
                'department' => $department,
                'dat' => $dat,
                'id' => $id,
                'users' => $users
            ]);
        }
    }

    public function updatechangereq(Request $req)
    {
        $crid = $req->input('crid');
        $scope = $req->input('scope');
        $reason = $req->input('reason');
        $benefits = $req->input('benefits');
        $impacts = $req->input('impacts');
        $risk_identified = $req->input('risk_identified');
        $department = $req->input('department');
        $appr_authority = $req->input('appr_authority');
        $changing_product = $req->input('changing_product');
        $priority = $req->input('priority');
        $remarks = $req->input('remarks');
        $proposal = $req->input('proposal');
        $reviewed = $req->input('question');
        $reviewed_at = date('d-m-Y h:i:s A', time());
        if ($reviewed == 2) {
            $status = 'Reviewed';
        } else
            $status = 'Rejected';

        $category = $req->input('category') ? implode(',',$req->input('category')) : null;
        $currentUserDetail = User::where('email',Auth::user()->email)->first();
        $data  = array(
            'scope' => $scope,
            'reason' => $reason,
            'benefits' => $benefits,
            'impacts' => $impacts,
            'risk_identified' => $risk_identified,
            'department' => $department,
            'changing_product' => $changing_product,
            'appr_authority' => $appr_authority,
            'priority' => $priority,
            'status' => $status,
            'remarks' => $remarks,
            'proposal' => $proposal,
            'reviewed_at' => $reviewed_at,
            'reviewed_by' => $currentUserDetail->id
        );
        if($category){
            $data['category'] = $category;
        }
        DB::table('context_crs')->where('id', $req->id)->update($data);

        //inserting values to activity table superadmin
        $activity = "Change Request is Reviewed and status Updated for Request where Request Id" . $crid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin

        $dat = ContextCrs::with(
            ['priorityDetails','severity','reviewedBy','postedByDetails','approver']
            )->where('id', $req->id)->first();
        if ($dat->status == 'Reviewed') {
            $maildata = array(
                'crid' => $dat->crid,
                'reviewed_by' => $dat->reviewedBy ? $dat->reviewedBy->name : '',
                'title' =>  $dat->title,
                'crpriority' => $dat->priorityDetails ? $dat->priorityDetails->name : $dat->priority,
                'crseverity' => $dat->severity ? $dat->severity->name : '-'
            );
            //Sending reviewed mail to MR.
            $mr = DB::table('representatives')->pluck('email')->toArray();
            if(count($mr)>0){
                Mail::to($mr)->send(new ChangeRequestReviewCompleted($maildata));
            }
            //Sending reviewed mail to posted user.
            if($dat->postedByDetails && $dat->postedByDetails->email){
                Mail::to($dat->postedByDetails->email)->send(new ChangeRequestReviewCompleted($maildata));
            }
            //Sending reviewed mail to approval authority.
            if($dat->approver && $dat->approver->email){
                Mail::to($dat->approver->email)->send(new ChangeRequestReviewed($maildata));
            }
        }
        $models = DB::table('modenadisable')->First();
        return redirect('/user/context/changelogreg')->with('model', $models);
    }

    public function addsapprove()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $models = DB::table('modenadisable')->First();
            $dat = ContextCrs::with([
                'severity','initiatorBy','departmentDetails','priorityDetails','reviewedBy',
                'approvedBy','approver','reviewer','closedBy','postedByDetails'
            ])->where('id', $id)->first();
            $cat = $dat->category ? explode(',',$dat->category) : [];
            $changeCategories = Changecategory::whereIn('id',$cat)->pluck('category')->toArray();
            $changeCategories = count($changeCategories) > 0 ? implode(',',$changeCategories) : implode(',',$cat);
            $department = Departments::where('is_active',true)->get();
            $priority = Priority::all();

            return view('user/context/approve',compact('changeCategories'))
                ->with('model', $models)
                ->withPriority($priority)
                ->withDepartment($department)
                ->withDat($dat)
                ->with('id', $id);
        }
    }

    public function updateapprove(Request $req)
    {
        $crid = $req->input('crid');
        $scope = $req->input('scope');
        $reason = $req->input('reason');
        $benefits = $req->input('benefits');
        $impacts = $req->input('impacts');
        $risk_identified = $req->input('risk_identified');
        $department = $req->input('department');
        $changing_product = $req->input('changing_product');
        $priority = $req->input('priority');
        $remarks = $req->input('higherremarks');
        $proposal = $req->input('higherproposal');
        $reviewed = $req->input('question');
        $reviewed_at = date('d-m-Y h:i:s A', time());
        if ($reviewed == 2) {
            $status = 'Approved';
        } else
            $status = 'Rejected';

        $currentUserDetail = User::where('email',Auth::user()->email)->first();
        $data  = array(
            'scope' => $scope,
            'reason' => $reason,
            'benefits' => $benefits,
            'impacts' => $impacts,
            'risk_identified' => $risk_identified,
            'department' => $department,
            'changing_product' => $changing_product,
            'priority' => $priority,
            'status' => $status,
            'higherremarks' => $remarks,
            'higherproposal' => $proposal,
            'reviewed_at' => $reviewed_at,
            'approved_by' => $currentUserDetail->id
        );
        DB::table('context_crs')->where('id', $req->id)->update($data);

        $dat = ContextCrs::with(
            ['priorityDetails','severity','approvedBy','postedByDetails','approver','departmentDetails']
            )->where('id', $req->id)->first();
        if ($dat->status == 'Approved') {
            $maildata = array(
                'crid' => $dat->crid,
                'approved_by' => $currentUserDetail->name,
                'title' =>  $dat->title,
                'crpriority' => $dat->priorityDetails ? $dat->priorityDetails->name : $dat->priority,
                'crseverity' => $dat->severity ? $dat->severity->name : '-'
            );


            //Sending approved mail to posted user.
            if($dat->postedByDetails && $dat->postedByDetails->email){
                Mail::to($dat->postedByDetails->email)->send(new ChangeRequestApproved($maildata));
            }

            //Sending approved mail to department HOD.
            if($dat->departmentDetails && $dat->departmentDetails->hod){
                Mail::to($dat->departmentDetails->hod)->send(new ChangeRequestApproved($maildata));
            }
        }

        //inserting values to activity table superadmin
        $activity = "Change Request is Reviewed and status Updated for Request where Request Id" . $crid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin

        $models = DB::table('modenadisable')->First();
        return redirect('/user/context/changelogreg')->with('model', $models);
    }


    //  delete issues
    public function delete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $name = DB::table('context_issues')->where('id', $id)->pluck('issue');
            //insert data to activity table superadmin code
            $activity = "An Issue was Deleted where Issue name is " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin codes

            DB::delete('delete from context_issues where id = :eid', ['eid' => $id]);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/contextmgmt')->with('model', $models);
        }
    }
    //edit issues
    public function editreq_issues()
    {

        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            $parties = Party::all();
            // $purpose = Purpose::all();
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = DB::table('context_issues')->where('id', $d)->first();
                }
                $n = $n + 1;
            }
        }
        $models = DB::table('modenadisable')->First();
        return view('user/context/editrequest_issues')->withData($data)
            ->with('id', $id)
            ->withParties($parties)
            // ->withPurpose($purpose)
            ->with('model', $models);
    }
    public function editrequest_issues(Request $req)
    {
        $m = count($req->title);

        $type = $req->input('type');
        $issue = $req->input('issue');
        // $agreedaction = $req->input('purpose');
        $monitoring = $req->input('monitoring');
        $bias = $req->input('bias');
        $accountability =  $req->input('accountability');

        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = DB::table('context_issues')->where('id', $d)->first();
            }
        }

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    DB::table('context_issues')->where('id', $s)
                        ->update(array(
                            'type'              => $req->type[$n],
                            'issue'             => $req->issue[$n],
                            // 'purpose'           =>$req->purpose[$n],
                            'monitoring'        => $req->monitoring[$n],
                            'bias'              => $req->bias[$n],
                            'accountability'    => $req->accountability[$n]
                        ));
                    //insert data to activity table superadmin code
                    $activity = "Context Issue is  Edited and Updated with new Details  where  ID " . $s;
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
        return redirect('/user/contextmgmt')->with('model', $models);
    }

    public function deleteparty()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $name = DB::table('context_partiesreqs')->where('id', $id)->pluck('intr_party');
            //insert data to activity table superadmin code
            $activity = "A Requirement was Deleted where Interested Party is " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin codes

            // $data[]=DB::table('context_partiesreqs')->where('id',$d)->first();
            DB::delete('delete from context_partiesreqs where id = :eid', ['eid' => $id]);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/context/interestparty')->with('model', $models);
        }
    }

    //edit party
    public function editreq_party()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            foreach ($id as $i) {
                $c = DB::table('context_partiesreqs')->select('Cid')->where('id', $i)->first();
            }
            foreach ($c as $i) {
                $Cid = $i;
            }

            $parties = Party::all();
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $subitem[] = DB::table('context_parties')
                        ->join('context_partiesreqs', 'context_parties.Cid', '=', 'context_partiesreqs.Cid')
                        ->where('context_partiesreqs.id', $d)
                        ->where('context_partiesreqs.Cid', $Cid)
                        ->first();
                }
                $n = $n + 1;
            }
            $mainitem[] = DB::table('context_parties')->where('Cid', $Cid)->first();
        }

        $models = DB::table('modenadisable')->First();
        return view('user/context/editrequest_party')->with('data1', $mainitem)
            ->with('data2', $subitem)
            // ->withMainitem($data1)
            // ->withSubitem($data2)
            // ->withData1($data1)
            // ->withData2($data2)
            ->with('id', $id)
            ->withParties($parties)
            ->with('model', $models);
    }

    public function editrequest_party(Request $req)
    {
        $m = count($req->intr_party);
        $reqip = $req->input('reqip');
        $monitoring = $req->input('monitoring');
        $accountability =  $req->input('accountability');

        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = DB::table('context_partiesreqs')->where('id', $d)->first();
            }
        }

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    DB::table('context_partiesreqs')->where('id', $s)
                        ->update(array(
                            'reqip'              => $req->reqip[$n],
                            'monitoring'        => $req->monitoring[$n],
                            'accountability'    => $req->accountability[$n]
                        ));
                    //insert data to activity table superadmin code
                    $activity = "Interested Party is  Edited and Updated with new Details  where  ID " . $s;
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
        return redirect('/user/context/interestparty')->with('model', $models);
    }
    public function deletecr()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $name = DB::table('context_crs')->where('id', $id)->pluck('title');
            //insert data to activity table superadmin code
            $activity = "A Change Request was Deleted where Title is " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin codes

            DB::delete('delete from context_crs where id = :eid', ['eid' => $id]);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/context/changelogreg')->with('model', $models);
        }
    }
    //edit cr
    public function editreq_cr()
    {

        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            $reviewer = Changerequestreviewer::pluck('email')->toArray();
            $approver = Changerequestapprove::pluck('email')->toArray();
            $reviewer = User::whereIn('email',$reviewer)->get()->sortBy('name');
            $approver = User::whereIn('email',$approver)->get()->sortBy('name');
            $priority = Priority::all();
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = ContextCrs::with(['correctiveDetails'])->where('id', $d)->first();
                }
                $n = $n + 1;
            }
        }

        $models = DB::table('modenadisable')->First();
        return view('user/context/editrequest_cr')->withData($data)
            ->with('id', $id)
            ->withPriority($priority)
            ->withReviewer($reviewer)
            ->withApprover($approver)
            ->with('model', $models);
    }
    public function editrequest_cr(Request $req)
    {
        $m = count($req->crid);
        $scope = $req->input('scope');
        $reason = $req->input('reason');
        $benefits = $req->input('benefits');
        $impacts = $req->input('impacts');
        $changing_product = $req->input('changing_product');
        $priority =  $req->input('priority');
        $rev_authority =  $req->input('rev_authority');
        $appr_authority =  $req->input('appr_authority');

        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = DB::table('context_crs')->where('id', $d)->first();
            }
        }

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    DB::table('context_crs')->where('id', $s)
                        ->update(array(
                            'scope'             => $req->scope[$n],
                            'reason'            => $req->reason[$n],
                            'benefits'          => $req->benefits[$n],
                            'impacts'           => $req->impacts[$n],
                            'changing_product'  => $req->changing_product[$n],
                            'priority'          => $req->priority[$n],
                            'rev_authority'     => $req->rev_authority[$n],
                            'appr_authority'    => $req->appr_authority[$n]
                        ));
                    //insert data to activity table superadmin code
                    $activity = "Change log is  Edited and Updated with new Details  where  ID " . $s;
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
        return redirect('/user/context/changelogreg')->with('model', $models);
    }


    //register
    public function interestparty()
    {
        // issue register----------------------------------------------------------------------

        $issues = DB::table('context_issues')->orderBy('title')->paginate(100);
        $issuereviewers = DB::table('issuereviewers')->pluck('email');
        $mr = DB::table('representatives')->pluck('email');
        $departments = Departments::all();
        $parties = Party::all();
        $users = DB::table('users')->pluck('name');

        // interested party register----------------------------------------------------------

        $issues1 = DB::table('context_parties')->orderBy('type')->paginate(2000);
        $issues3 = DB::table('context_parties')
            ->join('context_partiesreqs', 'context_parties.Cid', '=', 'context_partiesreqs.Cid')
            ->select(
                'context_parties.Cid',
                'context_parties.count',
                'context_parties.partyid',
                'context_parties.status',
                'context_parties.intr_party',
                'context_parties.type',
                'context_parties.purpose',
                'context_parties.posted_by',
                'context_parties.updated_at',
                'context_partiesreqs.id',
                'context_partiesreqs.reqid',
                'context_partiesreqs.reqip',
                'context_partiesreqs.monitoring',
                'context_partiesreqs.accountability',
                'context_partiesreqs.created_at',
                'context_partiesreqs.reviewed_at'
            )
            ->paginate(2000);

        // CR log------------------------------------------------------------------------------
        $de = Auth::user()->email;
        $issues2 = DB::table('context_crs')->orderBy('department')->paginate(100);


        $models = DB::table('modenadisable')->First();
        return view('user.context.interestedparty', compact('issues', 'issues1', 'issues2', 'issues3', 'users', 'parties', 'departments', 'issuereviewers', 'mr'))->with('model', $models);
    }

    public function changelogreg()
    {
        // issue register----------------------------------------------------------------------
        $issues = DB::table('context_issues')->orderBy('title')->paginate(100);
        $issuereviewers = DB::table('issuereviewers')->pluck('email');
        $mr = DB::table('representatives')->pluck('email');
        $departments = Departments::all();
        $parties = Party::all();
        $users = DB::table('users')->pluck('name');

        // interested party register----------------------------------------------------------

        $issues1 = DB::table('context_parties')->orderBy('type')->paginate(2000);
        $issues3 = DB::table('context_parties')
            ->join('context_partiesreqs', 'context_parties.Cid', '=', 'context_partiesreqs.Cid')
            ->select(
                'context_parties.Cid',
                'context_parties.count',
                'context_parties.partyid',
                'context_parties.status',
                'context_parties.intr_party',
                'context_parties.type',
                'context_parties.purpose',
                'context_parties.posted_by',
                'context_parties.updated_at',
                'context_partiesreqs.id',
                'context_partiesreqs.reqid',
                'context_partiesreqs.reqip',
                'context_partiesreqs.monitoring',
                'context_partiesreqs.accountability',
                'context_partiesreqs.created_at',
                'context_partiesreqs.reviewed_at'
            )
            ->paginate(2000);

        // CR log------------------------------------------------------------------------------
        $de = Auth::user()->email;
        $issues2 = ContextCrs::with([
            'severity','initiatorBy','departmentDetails','priorityDetails','reviewedBy',
            'approvedBy','approver','reviewer','closedBy','postedByDetails'
        ])->orderByDesc('id')->get();
        $issue2Ids = count($issues2) ?  $issues2->pluck('id')->toArray() : [];
        //ohsrisk data display
        $data2 = DB::table('ohs')
            ->join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
            ->select('ohsrisk.riskid','ohs.actid','ohs.context_id','ohs.count')
            ->whereIn('ohs.context_id', $issue2Ids)
            /*->pluck('ohs.context_id')*/->get()->toArray();

        //environmentaspect data display
        $envaspt = DB::table('environment')
            ->join('enviaspects', 'environment.Eid', '=', 'enviaspects.Eid')
            ->select(
                     'enviaspects.Eid',
                     'enviaspects.aspect_id',
                     'environment.department',
                     'environment.context_id',
                     'environment.count'
                    )
            ->whereNotNull('environment.context_id')
            ->get();
        $models = DB::table('modenadisable')->First();
        $departments = Departments::pluck('departmentname','id')->toArray();
        $currentUserDetail = User::where('email',Auth::user()->email)->first();
        $changeCategories = Changecategory::all();
        return view('user.context.changelogreg', compact('issues', 'issues1', 'issues2', 'issues3', 'users', 'parties',
        'departments', 'issuereviewers', 'mr','changeCategories'))
                                     ->with('ohs',$data2)
                                     ->with('currentUserDetail',$currentUserDetail)
                                     ->with('departments',$departments)
                                     ->with('envaspt',$envaspt)
                                     ->with('model', $models);
    }
    //closurereview for need more action
    public function closurereview(int $id)
    {

        $hml = DB::table('hml')->get();
        $dat = ContextCrs::with([
            'severity','initiatorBy','departmentDetails','priorityDetails','reviewedBy',
            'approvedBy','approver','reviewer','closedBy','postedByDetails'
        ])->where('id',$id)->first();
        $mn = Departments::all();
        $models = Modenadisable::First();
        $cat = $dat->category ? explode(',',$dat->category) : [];
        $changeCategories = Changecategory::whereIn('id',$cat)->pluck('category')->toArray();
        $changeCategories = count($changeCategories) > 0 ? implode(',',$changeCategories) : implode(',',$cat);
        return view('user/context/closure',compact('changeCategories'))
            ->with('model', $models)
            ->withDat($dat)
            ->with('mn', $mn)
            ->with('id', $id)
            ->withHml($hml);
    }
    public function needMoreAction(int $id)
    {
        $hml = DB::table('hml')->get();
        $data = ContextCrs::where('id', $id)->get();
        $mn = Departments::all();
        $models = Modenadisable::First();
        return view('user.context.needMoreAction')
            ->with('model', $models)
            ->withData($data)
            ->with('mn', $mn)
            ->with('id', $id)
            ->withHml($hml);
    }
    public function closure(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'description' => 'required|string|max:1000',
            'action_status' => 'required|in:Completed-100%,Partially Completed-50%,Progress-25%,Not Started-0%'
        ]);
        if ($validator->fails()) {
            $models = Modenadisable::first();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $id = $req->input('id');
            $reviewtime = date('d-m-Y h:i:s A', time());
            $action_status = $req->input('action_status');
            $description = $req->input('description');
            $newStatus = "Completed";
            if ($action_status == "Partially Completed-50%" || $action_status == "Progress-25%") {
                $newStatus = "Partially Completed";
            } else if ($action_status == "Not Started-0%") {
                $newStatus = "Not Started";
            }
            $data  = array(
                'action_status' => $action_status,
                'description' => $description,
                'reviewed_at' => $reviewtime,
                'status' => $newStatus
            );
            ContextCrs::where('id', $id)->update($data);
            $dat = ContextCrs::find($id);
            $maildata = array(
                'crid' => $dat->crid,
                'crpriority' => $dat->priority,
                'crseverity' => $dat->severity ? $dat->severity->name : '-',
                'description' => $dat->description != null ? $dat->description : '-'
            );
            $hod = DB::table('departments')->where('departmentname', $dat->department)->pluck('hod');
            foreach ($hod as $h) {
                Mail::to($h)->send(new ContextNeedMoreAction($maildata));
            }
            if (!in_array(Auth::user()->email, $hod->toArray())) {
                Mail::to(Auth::user()->email)->send(new ContextNeedMoreAction($maildata));
            }
            $crid = ContextCrs::where('id', $id)->pluck('crid');
            $title = ContextCrs::where('id', $id)->pluck('title');
            $activity = "Action Status is Updated as Partially Completed-50% where Context crid is" . $crid . " and where context title " . $title;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);
            $models = DB::table('modenadisable')->First();
            return redirect()->route('user.context.changelogreg')->with('model', $models);
        }
    }
    public function closed(int $id)
    {

        $reviewtime = date('d-m-Y h:i:s A', time());
        $data = array(
            'status' => 'Closed', 'reviewed_at' => $reviewtime
        );
        ContextCrs::where('id', '=', $id)->update($data);
        //inserting values to activity table
        $crid = ContextCrs::where('id', $id)->pluck('crid');
        $title = ContextCrs::where('id', $id)->pluck('title');
        $activity = "Context Action Status is Updated as Closed where crid is " . $crid . " and where title " . $title;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now_time = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
        ViewActivity::insert($datas);
        $dat = ContextCrs::with(['departmentDetails','priorityDetails','severity'])->where('id',$id)->first();
        $maildata = array(
            'crid' => $dat->crid,
            'crpriority' => $dat->priorityDetails ? $dat->priorityDetails->name : $dat->priority,
            'crseverity' => $dat->severity ? $dat->severity->name : '-'
        );
        if($dat->departmentDetails && $dat->departmentDetails->hod){
            Mail::to($dat->departmentDetails->hod)->send(new ContextClosed($maildata));
            if(Auth::user()->email!=$dat->departmentDetails->hod){
                Mail::to(Auth::user()->email)->send(new ContextClosed($maildata));
            }
        }else{
            Mail::to(Auth::user()->email)->send(new ContextClosed($maildata));
        }

        $models = DB::table('modenadisable')->First();
        return redirect()->route('user.context.changelogreg')->with('model', $models);
    }
    //completed 100%
    public function hundred(int $id)
    {
        $data = array(
            'action_status' => 'Completed-100%',
            'status' => 'Completed'
        );
        ContextCrs::where('id', '=', $id)->update($data);
        $dat = ContextCrs::find($id);
        $maildata = array(
            'crid' => $dat->crid,
            'crpriority' => $dat->priority,
            'crseverity' => $dat->severity ? $dat->severity->name : '-'
        );
        $hod = DB::table('departments')->where('departmentname', $dat->department)->pluck('hod')->toArray();
        // if (!in_array(Auth::user()->email, $hod->toArray())) {
        //     Mail::to(Auth::user()->email)->send(new ContextCompleted($maildata));
        // }
        //Sending reviewed mail to approval authority.
        $approveauthority = User::where('name', $dat->appr_authority)->pluck('email')->toArray();
        $reviewauthority = User::where('name', $dat->rev_authority)->pluck('email')->toArray();
        $allToSendMail = array_unique(array_merge($hod, $approveauthority, $reviewauthority));
        foreach ($allToSendMail as $a) {
            Mail::to($a)->send(new ContextCompleted($maildata));
        }
        $crid = ContextCrs::where('id', $id)->pluck('crid');
        $title = ContextCrs::where('id', $id)->pluck('title');
        $activity = "Context is closed with Action Status as Completed-100% where Context id is " . $crid . " and where title" . $title;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now_time = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
        ViewActivity::insert($datas);
        $models = Modenadisable::first();
        return redirect()->back()->with('model', $models);
    }
    //partiallycompleted 50%
    public function five(int $id)
    {
        $data = array(
            'action_status' => 'Partially Completed-50%',
            'status' => 'Partially Completed'
        );
        ContextCrs::where('id', '=', $id)->update($data);
        $crid = ContextCrs::where('id', $id)->pluck('crid');
        $title = ContextCrs::where('id', $id)->pluck('title');
        $activity = "Context Action Status is Updated as Partially Completed-50% where Context id is " . $crid . " and where title " . $title;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now_time = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
        ViewActivity::insert($datas);
        $models = Modenadisable::first();
        return redirect()->back()->with('model', $models);
    }
    //progress
    public function two(int $id)
    {
        $data = array(
            'action_status' => 'Progress-25%',
            'status' => 'Partially Completed'
        );
        ContextCrs::where('id', '=', $id)->update($data);
        $crid = ContextCrs::where('id', $id)->pluck('crid');
        $title = ContextCrs::where('id', $id)->pluck('title');
        $activity = "Context Action Status is Updated as  Progress-50% where Context id" . $crid . " and where risk title " . $title;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now_time = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
        ViewActivity::insert($datas);
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    //not started
    public function zero(int $id)
    {
        $data = array(
            'action_status' => 'Not Started-0%',
            'status' => 'Not Started'
        );
        ContextCrs::where('id', '=', $id)->update($data);
        $models = ModenaDisable::first();
        return redirect()->back()->with('model', $models);
    }

   //changelog add ohsrisk
    public function addohsrisk($id,$crid)
    {
        $cont_dept_Details = ContextCrs::with(['departmentDetails'])->where('id',$id)->first();
        $cont_dept = $cont_dept_Details->departmentDetails ? $cont_dept_Details->departmentDetails->departmentname : $cont_dept_Details->department;
        $cont_dept_id = $cont_dept_Details->department;
        //$contextcode = ContextCrs::where('id',$id)->value('context_code');
        $ohsinfo = DB::table('ohsinfo')->get();
        $monitor = DB::table('monitor')->get();
        $departments = DB::table('departments')->where('is_active',true)->get();
        $models = DB::table('modenadisable')->First();
        return view('user.context.addohsrisk',compact('cont_dept_id'))
                              ->with('model', $models)
                              ->with('contdept', $cont_dept)
                              ->with('context_id', $id)
                              ->withDepartments($departments)
                              ->withMonitor($monitor)
                              ->withOhsinfo($ohsinfo);
    }


    public function insertchlogohsrisk(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'department' => 'required',
            'type' => 'required',
            'activity' => 'required',
            'legal' => 'required',
            'location' => 'required',
            'team' => 'required',

        ]);

        $context_id = $req->context_id;
        $n = count($req->doc);
        $department_id = $req->input('department');
        $type = $req->input('type');
        $created_at = $req->input('date_created');
        $activity = $req->input('activity');
        $legal = $req->input('legal');
        $loc = $req->input('location');
        $location = ucwords($loc);
        $team = $req->input('team');
        $postedby = $req->input('postedby');
        $exist = DB::table('ohs')->where('department', $department_id)->orderBy('id', 'desc')->first();
        if ($exist) {
            $c = $exist->count;
            $count = $c + 1;
        } else {
            $count = "1";
        }

        date_default_timezone_set('Asia/Kuwait');
        $created_at = date('d-m-Y h:i:s A', time());

        $data = array('created_at' => $created_at,'context_id' => $context_id,'count' => $count,'department' => $department_id, 'type' => $type, 'legal' => $legal, 'team' => $team, 'location' => $location, 'activity' => $activity, 'postedby' => $postedby);
        DB::table('ohs')->insert($data);

        $code = DB::table('departments')->where('id', $department_id)->value('code');
        $code = strtoupper(substr($code, 0, 3));
        $actnum = $code . $count;

        $data = DB::table('ohs')->orderBy('id', 'desc')->first();
        $Rid = $data->id;
        $d = 0;

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


            $data1 = array('actid' => $actnum);
            DB::table('ohs')->where('count', $count)
                ->where('department', $department_id)
                ->update($data1);
            if ($d <= $n)
                $d = $d + 1;

            $data2 = array(
                'ohs_id' => $Rid,
                'department' => $department_id,
                'context_id' => $context_id,
                // 'type'=>$type,
                'R_NR_EM' => $value['R_NR_EM'],
                'hazards' => $value['hazards'],
                'affect' => $value['affect'],
                'control' => $value['control'],
                'OrS'    => $value['OrS'],
                'OrL'    => $value['OrL'],
                'OrR'    => $OrR,
                'additional' => $value['additional'],
                'RS'    => $value['RS'],
                'RL'    => $value['RL'],
                'RR'    => $RR,
                'monitor' => $value['monitor'],
                'created_at'  => $created_at,
                'category' => $category,
                'riskid' => $d,
            ); //dd($data2);
            DB::table('ohsrisk')->insert($data2);
        }
        //inserting values to activity table
        $activity = "New OHS Risk is Added with Activity Name as " . $activity;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code

        //Sending mail to risk reviewer(hse).
        $dat = DB::table('ohs')->orderBy('id', 'desc')->first();
        $maildata = array(
            'actid' => $dat->actid,
        );
        $riskrev = DB::table('hses')->pluck('email');
        foreach ($riskrev as $r) {
            Mail::to($r)->send(new NewOhsRiskAdded($maildata));
        }
        Session::flash('msg', "OHS risk added successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

  //changelog add envaspect
    public function addenvaspect($id,$crid)
    {
        $cont_dept_Details = ContextCrs::with(['departmentDetails'])->where('id',$id)->first();
        $cont_dept = $cont_dept_Details->departmentDetails ? $cont_dept_Details->departmentDetails->departmentname : $cont_dept_Details->department;
        $cont_dept_id = $cont_dept_Details->department;
        $envinfo = DB::table('envinfo')->get();
        $departments = DB::table('departments')->where('is_active',true)->get();
        $users = User::where('approved',true)->orderBy('name')->get();
        $x = 0;
        $data = DB::table('environment')->get();
        $envi = DB::table('envireviewers')->get();

        if (count($data) > 0) {
            $data = DB::table('environment')->latest('created_at')->first();
            foreach ($envi as $e) {
                if (Auth::user()->email == $e->email)
                    $x = 5;
            }
            // dd($envi);
            $Eid = $data->Eid;
            $k = $Eid + 1;
            $data2 = DB::table('enviaspects')->where('Eid', $k)->get();
            $models = DB::table('modenadisable')->First();
            return view('user.context.addenvaspect',compact('cont_dept_id'))
                                            ->with('model', $models)
                                            ->withEnvinfo($envinfo)
                                            ->withX($x)
                                            ->with('contdept',$cont_dept)
                                            ->with('context_id', $id)
                                            ->with('context_crid', $crid)
                                            ->withDepartments($departments)
                                            ->withEnvi($envi)
                                            ->withData2($data2)
                                            ->withUsers($users);
        }
        $data2 = DB::table('enviaspects')->get();
        $models = DB::table('modenadisable')->First();
        return view('user.context.addenvaspect',compact('cont_dept_id'))
                                            ->with('model', $models)
                                            ->withEnvinfo($envinfo)
                                            ->withX($x)
                                            ->with('contdept',$cont_dept)
                                            ->with('context_id', $id)
                                            ->with('context_crid', $crid)
                                            ->withDepartments($departments)
                                            ->withEnvi($envi)
                                            ->withData2($data2)
                                            ->withUsers($users);
    }


    public function insertchlogenvaspt(Request $req)
    {
        $context_id = $req->context_id;
        $context_crid = $req->context_crid;
        $n = count($req->doc);
        $department = $req->input('department');

        $location = $req->input('location');

        // $a = DB::table('users')->get();
        // $e = Auth::user()->email;
        // $post = Auth::user()->employeeid;
        // foreach ($a as $b) {
        //     if ($e == $b->email)
        //         $post = $b->id;
        // }
        $post = User::where('email',Auth::user()->email)->value('id');
        //dd($post);
        $activity = $req->input('activity');
        $date = $req->input('date');
        $teams = $req->input('team');
        date_default_timezone_set('Asia/Kuwait');
        $created_date = date('d-m-Y h:i:s A', time());
        $created_by = Auth::user()->employeeid;
        foreach ($teams as $team) {
            $t[] = $team;
        }
        $tea = implode(' , ', $t);
        $exist = DB::table('environment')->where('department', $department)->first();
        if ($exist) {

            $c = DB::table('environment')->where('department', $department)->pluck('count');
            foreach ($c as $count) {
                $count = $count + 1;
            }
        } else {


            $count = "1";
        }
        $data1 = array('date' => $date, 'team' => $tea, 'count' => $count, 'department' => $department,'context_id' => $context_id,'activity' => $activity, 'useremail' => Auth::user()->email, 'location' => $location, 'created_at' => $created_date, 'created_by' => $created_by);

        //inserting values to activity table
        $activity = "New Environmental Activity is Added with activity name as " . $activity . ", team " . $tea . " and with department " . $department;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        DB::table('environment')->insert($data1);


        // $data=DB::table('environment')->latest('created_at')->first();
        $data = DB::table('environment')->orderBy('Eid', 'desc')->first();
        // dd($data);

        $Eid = $data->Eid;

        $d = 0;


        foreach ($req->doc as $key => $value) {

            $total = ($value['A'] * $value['B'] * $value['C'] * $value['D'] * $value['E']);
            if ($total >= 200)
                $HS = 'HS';
            else if ($total < 200 && $total >= 100)
                $HS = 'MS';
            else
                $HS = 'NS';
            if ($d <= $n)
                $d = $d + 1;
            $post = Auth::user()->employeeid;


            $data2 = array(
                'Eid' => $Eid, 'enviaspects' => $value['enviaspects'],
                'ER' => $value['ER'],
                'potential' => $value['potential'],
                'A' => $value['A'],
                'B' => $value['B'],
                'C' => $value['C'],
                'D' => $value['D'],
                'E' => $value['E'],
                'total' => $total, 'HS' => $HS, 'department' => $department,'context_id' => $context_id,'posted_by' => $post, 'aspect_id' => $d, 'created_at' => $created_date
            );

            DB::table('enviaspects')->insert($data2);
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
            $issue = ContextCrs::with([
                'severity','initiatorBy','departmentDetails','priorityDetails','reviewedBy',
                'approvedBy','approver','reviewer','closedBy','postedByDetails'
            ])->where('id',$id)->first();
            //ohsrisk data display
            $ohs = DB::table('ohs')
                ->join('ohsrisk', 'ohs.id', '=', 'ohsrisk.ohs_id')
                ->select('ohsrisk.riskid','ohs.actid','ohs.context_id','ohs.count')
                ->where('ohs.context_id', $id)->get()->toArray();

            //environmentaspect data display
            $envaspt = DB::table('environment')
                ->join('enviaspects', 'environment.Eid', '=', 'enviaspects.Eid')
                ->select(
                        'enviaspects.Eid',
                        'enviaspects.aspect_id',
                        'environment.department',
                        'environment.context_id',
                        'environment.count'
                        )
                ->where('environment.context_id',$id)
                ->get();
            $models = DB::table('modenadisable')->first();
            $changeCategories = Changecategory::all();
            $responseData['view'] = \view('user.context.__viewDetailsModal', compact(
                'issue', 'models','ohs','envaspt','changeCategories'
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

}
