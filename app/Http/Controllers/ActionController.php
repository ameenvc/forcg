<?php

namespace App\Http\Controllers;

use App;
use App\Area;
use App\Audit;
use App\AuditRootCauseCorrectiveActionDetail;

//use Validator;
use App\Categories;
use App\Departments;
use App\Division;
use App\Issue;
use App\Issuereviewer;
use App\Mail\ActionProposed;
use App\Mail\ActionResponseNotification;
use App\Mail\ActionUpdated;
use App\Mail\AddIssue;
use App\Mail\AuditResponsibilityChanged;
use App\Mail\IssueClosed;
use App\Mail\ReviewStatusFoundNotOK;
use App\Mail\ReviewStatusFoundOK;
use App\ModuleRegisterPageDetail;
use App\Risk;
use App\Type;
use App\User;
use App\ModuleReportDetail;
use App\ModuleClosedPageDetail;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Session;
use ZipArchive;
use App\Http\Requests\Audit\NeedAuditeeConfirmRequest;
use Illuminate\Http\JsonResponse;

class ActionController extends Controller
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
        return view('user.actionsystem.home');
    }

    public function getListDetails($id)
    {
        $issue = Issue::with('postedBy', 'approvedBy')->where('id', $id)->first();
        $responsibleUser = User::where('email', $issue->responsibility)->first();
        $postedByUser = User::find($issue->postedby);
        $issue->postedByUser = $postedByUser;
        $issue->responsibleUser = $responsibleUser;
        return response()->json(['data' => $issue]);
    }

    public function saveMultipleFiles($request, $id, $model, $field, $name)
    {
        if ($request->hasFile($name)) {
            $file_array = $request->file($name);
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/issue_files');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
                $model::insert([
                    'id' => $id,
                    $field => $new_file_name,
                ]);
            }
        }
    }
    public function actionAttachment(Request $request, int $id)
    {
        $this->saveMultipleFiles($request, $id, 'App\Mutlipleaction', 'action_file', 'fileToUpload');
        $this->saveMultipleFiles($request, $id, 'App\Multipleissue', 'issue_file', 'fileToUpload1');
        return response()->json(['data' => null]);
    }

    public function viewlist(string $type = null)
    {
        $today = date('Y-m-d');
        $app_url = BaseController::addSlashToEndOfURL();
        $company = BaseController::getCompanyFromAppRUL();

        $auditmanagement = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($auditmanagement) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 1, 'ohsrisk' => 0, 'documentmanagement' => 0]);
        } else {
            $auditmanagement = DB::table('modpageload')->where('id', 1)->First();
            $auditmanagement->auditmanagement = $auditmanagement->auditmanagement + 1;
            DB::table('modpageload')->update(['auditmanagement' => $auditmanagement->auditmanagement]);
        }

        $rec1 = DB::table('multipleissues')->distinct()->get();
        $rec2 = DB::table('multipleactions')->distinct()->get();
        $issues = Issue::with([
            'hodUser', 'postedBy', 'dept', 'typ',
            'responsible', 'division', 'area',
            'issueFiles', 'actionFiles', 'reviewedBy', 'hodreviewedBy', 'closedBy'
        ])->where(function ($query) use ($type) {
            if ($type != 'awaiting' && $type != 'proposed') {
                $query->where('status', 'open');
            }
        })->where('category', 'Internal Audit')
            ->where(function ($query) use ($type, $today) {
                if ($type != null && $type != 'due' && $type != 'awaiting' && $type != 'proposed' && $type != 'closure') {
                    $type = Type::where('name', $type)->value('order');
                    $query->where('type', $type);
                }
                switch ($type) {
                    case 'due':
                        $query->where('duedate', '<=', $today);
                        break;
                    case 'awaiting':
                        $query->whereIn('processstage', array('Approved', 'Awaiting actions'));
                        break;
                    case 'proposed':
                        $query->where('processstage', 'Actions Proposed');
                        break;
                    case 'closure':
                        $query->where('impl_status', 'Completed-100%');
                        break;
                }
            })
            ->orderBy('created_at', 'desc')->get();

        $responsibleEmails = [];
        foreach ($issues as $dat) {
            $dept = $dat->dept ? $dat->dept->departmentname : '';
            $responsibleEmails = array_merge($responsibleEmails, explode(',', $dat->responsibility));
            if ($dat->duedate > $today) {
                $freq = explode(',', $dat->frequency);
                $name = $dat->postedBy ? [$dat->postedBy->name] : [];
                $type_name = $dat->typ ? $dat->typ->name : '';

                foreach ($name as $n) {
                    $data = array(
                        'issueid' => $dat->issueid,
                        'name' => $n,
                        'type' => $type_name,
                        'department' => $dept,
                        'app_url' => $app_url,
                        //'company' => $company,
                        'duedate' => date("d/m/Y", strtotime($dat->duedate)),
                    );
                }
                foreach ($freq as $f) {
                    if ($f == 'Monthly' && $today == date("Y-m-d", strtotime($dat->Monthlyfreq . ' + 31 days'))) {
                        Mail::to($dat->responsibility)->send(new ActionResponseNotification($data));
                        $Monthlyfreq = date('Y-m-d');
                        $data1 = array('Monthlyfreq' => $Monthlyfreq);
                        Issue::where('id', $dat->id)->update($data1);
                    } elseif ($f == 'Weekly' && $today == date("Y-m-d", strtotime($dat->Weeklyfreq . ' + 7 days'))) {
                        Mail::to($dat->responsibility)->send(new ActionResponseNotification($data));
                        $Weeklyfreq = date('Y-m-d');
                        $data1 = array('Weeklyfreq' => $Weeklyfreq);
                        Issue::where('id', $dat->id)->update($data1);
                    } elseif ($f == '2 Days Before' && $today == date("Y-m-d", strtotime($dat->duedate . ' - 2 days')) && $dat->Dayfreq == '0') {
                        Mail::to($dat->responsibility)->send(new ActionResponseNotification($data));
                        $Dayfreq = '1';
                        $data1 = array('Dayfreq' => $Dayfreq);
                        Issue::where('id', $dat->id)->update($data1);
                    }
                }

            }
        }
        $me = Auth::user()->email;
        $responsibles = App\User::whereIn('email', $responsibleEmails)->get();
        $issuereviewers = DB::table('issuereviewers')->get();
        // $auditor = DB::table('internalaudits')->get();
        // $list = collect([]);
        // foreach ($issuereviewers as $reviewer) {
        //     $list = $list->push($reviewer->email);
        // }

        // foreach ($auditor as $aud) {
        //     $list = $list->push($aud->email);
        // }

        // $auditors = $list->unique();
        $auditors = DB::table('internalaudits')->pluck('email')->toArray();
        $mr = DB::table('representatives')->pluck('email');
        $types = Type::all();
        $categories = Categories::all();
        //$users = DB::table('users')->get();
        $users = User::where('approved',true)->get();
        $issuedocs = DB::table('multipleissues')->get();
        $actiondocs = DB::table('multipleactions')->get();

        $typenames = Type::pluck('name')->toArray();
        $alltypenames = implode(',', $typenames);

        $processstages = ['Awaiting actions', 'Actions Proposed', 'Action Approved',
            'Action Rejected', 'Need More Action', 'Actions Completed',
        ];
        $allpstages = implode(',', $processstages);
        $openIssues = Issue::with(['dept', 'responsible', 'reviewedBy', 'hodreviewedBy', 'closedBy'])->where('status', 'open')->get();
        $dueDateYears = $this->getYearAndMonthToFilter($openIssues, 'duedate');
        $addDateYears = $this->getYearAndMonthToFilter($openIssues, 'created_at');
        $actionReviewedDateYears = $this->getYearAndMonthToFilter($openIssues, 'reviewed_by_date');
        $hodReviewedDateYears = $this->getYearAndMonthToFilter($openIssues, 'hod_reviewed_by_date');
        $issueClosedDateYears = $this->getYearAndMonthToFilter($openIssues, 'closed');

        $departmentnames = count($openIssues) ? $openIssues->pluck('dept.departmentname')->toArray() : [];
        $alldeptnames = implode(',', array_unique($departmentnames));

        $usernameslist = count($openIssues) ? $openIssues->pluck('responsible.name')->toArray() : [];
        $allusernames = implode(',', array_unique($usernameslist));

        $reviewernameslist = count($openIssues) ? $openIssues->pluck('reviewedBy.name')->toArray() : [];
        $allreviewernames = implode(',', array_unique($reviewernameslist));
        $hodreviewernameslist = count($openIssues) ? $openIssues->pluck('hodreviewedBy.name')->toArray() : [];
        $allhodreviewernames = implode(',', array_unique($hodreviewernameslist));
        $closedBynameslist = count($openIssues) ? $openIssues->pluck('closedBy.name')->toArray() : [];
        $allcloseBynames = implode(',', array_unique($closedBynameslist));

        $models = DB::table('modenadisable')->first();

        $table_columns_active =  ModuleRegisterPageDetail::where('module_name','audit')
            ->where('is_active',true)
            ->whereNotIn('column_name', ['process stage', 'action status'])
            ->orderBy('column_position', 'asc')
            ->get();
        $table_columns_active = $table_columns_active->merge(
            ModuleRegisterPageDetail::where('module_name','audit')
                ->where('is_active',true)
                ->whereIn('column_name', ['process stage', 'action status'])
                ->orderBy('column_position', 'asc')
                ->get()
        );

        $table_columns_names_active =  ModuleRegisterPageDetail::where('module_name','audit')
            ->where('is_active',true)
            ->pluck('column_name')
            ->toArray();

        $table_columns_names_enabled_by_user =  ModuleRegisterPageDetail::where('module_name','audit')
            ->where('enabled_by_user','!=',0)
            ->get()->count();

        $table_columns_non_active =  ModuleRegisterPageDetail::where('module_name','audit')
            ->where('is_active',false)
            ->orderBy('column_position', 'asc')
            ->get();
        $division_names = Division::pluck('name')->toArray();
        $alldivisionnames =$division_names? implode(',', array_unique($division_names)):'';

        $sub_division_names = Area::pluck('name')->toArray();
        $allsubdivisionnames =$sub_division_names? implode(',', array_unique($sub_division_names)):'';

        $raised_by_names = User::pluck('name')->toArray();
        $allraisedbynames = implode(',', array_unique($raised_by_names));

        return view('user.action.issues', compact('users', 'types', 'categories',
            'issues', 'mr', 'issuereviewers', 'alldivisionnames', 'allsubdivisionnames', 'allraisedbynames', 'responsibles'))
            ->with('issuedocs', $issuedocs)
            ->with('actiondocs', $actiondocs)
            ->withRec1($rec1)->withauditors($auditors)
            ->withRec2($rec2)
            ->with('model', $models)
            ->with('alltypenames', $alltypenames)
            ->with('allpstages', $allpstages)
            ->with('dueDateYears', $dueDateYears)
            ->with('addDateYears', $addDateYears)
            ->with('actionReviewedDateYears', $actionReviewedDateYears)
            ->with('hodReviewedDateYears', $hodReviewedDateYears)
            ->with('issueClosedDateYears', $issueClosedDateYears)
            ->with('allcloseBynames', $allcloseBynames)
            ->with('alldeptnames', $alldeptnames)
            ->with('allusernames', $allusernames)
            ->with('allreviewernames', $allreviewernames)
            ->with('allhodreviewernames', $allhodreviewernames)
            ->with('table_columns_active',$table_columns_active)
            ->with('table_columns_non_active',$table_columns_non_active)
            ->with('table_columns_names_active',$table_columns_names_active)
            ->with('table_columns_names_enabled_by_user',$table_columns_names_enabled_by_user)
            ->withMr($mr);

    }

    /**
     * @param $dataList
     * @param $column
     * @return string
     */
    private function getYearAndMonthToFilter($dataList, $column)
    {
        $yearvalues = $dataList->map(function ($item) use($column) {
            return $item->{$column} ? date('Y-m', strtotime($item->{$column})) : '';
        });
        $year_arr = $yearvalues->toArray();
        $yearUnique = array_unique($year_arr);
        usort($yearUnique,  function( $x, $y) {
            if (strtotime($x)== strtotime($y))
                return 0;
            if (strtotime($x) > strtotime($y))
                return -1;
            else
                return 1;
        });

        return implode(',', $yearUnique);
    }

    // public function yearfolder()
    // {
    //     session(['actiondept' => " "]);

    //     $ad = Issue::wherein('status', array('closed', 'closed after due'))->where('category', 'Internal Audit')
    //         ->where('status', '!=', 'removed')->pluck('created_at');

    //     $addeddate = [];
    //     foreach ($ad as $a) {
    //         $addeddate[] = substr($a, 6, 4);
    //     }

    //     $alladdeddates = collect($addeddate);
    //     $uniqueaddeddates = $alladdeddates->unique()->values()->all();
    //     $years = [];

    //     for ($i = 0; $i < count($uniqueaddeddates); $i++) {
    //         $years[] = substr($uniqueaddeddates[$i], 0, 4);
    //     }
    //     $years = array_unique(array_filter($years));
    //     //convert string array to integer array
    //     $integeryears = array_map(
    //         function ($value) {
    //             return (int) $value;
    //         },
    //         $years
    //     );

    //     sort($integeryears, SORT_NUMERIC);

    //     $models = DB::table('modenadisable')->First();
    //     return view('user.action.folder')->with('model', $models)->with('years', $integeryears);
    // }

    public function yearfolder()
    {
        session(['actiondept' => " "]);

        $closed_details = Issue::whereIn('status', ['closed', 'closed after due'])
                    ->where('category', 'Internal Audit')
                    ->where('status', '!=', 'removed')
                    ->pluck('closed');

        $years = $closed_details->map(function ($date) {
            return \Carbon\Carbon::parse($date)->year;
        })->unique()->sort()->values()->all();

        $models = DB::table('modenadisable')->First();
        return view('user.action.folder')->with('model', $models)->with('years', $years);
    }



    //on clicking year folder
    public function closedissues($year)
    {
        session(['year' => $year]);
        //$users = DB::table('users')->get();
        $users = User::where('approved',true)->get();
        $closed_datas = Issue::with([
            'hodUser', 'postedBy', 'dept', 'typ',
            'responsible', 'division', 'area',
            'issueFiles', 'actionFiles', 'reviewedBy', 'hodreviewedBy', 'closedBy'])
           ->whereIn('status', ['closed', 'closed after due'])
           ->where('category', 'Internal Audit')
           ->whereNOTNULL('closed')
           ->orderBy('created_at', 'desc')
           ->get();

        if (count($closed_datas) > 0) {
            $responsibleEmails = [];
            foreach ($closed_datas as $closed_data) {
                // if (Carbon::parse($closed_data->created_at)->year === (int)$year) {
                if (Carbon::parse($closed_data->closed)->year === (int)$year) {
                    $issues1[] = $closed_data;
                    $dept = $closed_data->dept ? $closed_data->dept->departmentname : '';
                    $responsibleEmails = array_merge($responsibleEmails, explode(',', $closed_data->responsibility));
                }
            }
        }
        $responsibles = App\User::whereIn('email', $responsibleEmails)->get();

        $issuedepts = Issue::groupBy('department')->whereIn('status', ['closed', 'closed after due'])->pluck('department')->toArray();
        $departmentnames = array();
        foreach ($issuedepts as $issuedept) {
            $dept_names = DB::table('departments')->where('id', $issuedept)->value('departmentname');
            if ($dept_names) {
                array_push($departmentnames, $dept_names);
            }
        }
        $alldeptnames = implode(',', $departmentnames);

        $typenames = Type::pluck('name')->toArray();
        $alltypenames = implode(',', $typenames);

        $processstages = ['closed', 'closed after due'];
        $allpstages = implode(',', $processstages);

        $closedIssues = Issue::with(['dept', 'responsible', 'reviewedBy', 'hodreviewedBy', 'closedBy'])
                                ->where('status', 'closed')
                                ->get();

        $dueDateYears = $this->getYearAndMonthToFilter($closedIssues, 'duedate');
        $addDateYears = $this->getYearAndMonthToFilter($closedIssues, 'created_at');
        $actionReviewedDateYears = $this->getYearAndMonthToFilter($closedIssues, 'reviewed_by_date');
        $hodReviewedDateYears = $this->getYearAndMonthToFilter($closedIssues, 'hod_reviewed_by_date');
        $issueClosedDateYears = $this->getYearAndMonthToFilter($closedIssues, 'closed');

        $departmentnames = count($closedIssues) ? $closedIssues->pluck('dept.departmentname')->toArray() : [];
        $alldeptnames = implode(',', array_unique($departmentnames));

        $usernameslist = count($closedIssues) ? $closedIssues->pluck('responsible.name')->toArray() : [];
        $allusernames = implode(',', array_unique($usernameslist));

        $reviewernameslist = count($closedIssues) ? $closedIssues->pluck('reviewedBy.name')->toArray() : [];
        $allreviewernames = implode(',', array_unique($reviewernameslist));
        $hodreviewernameslist = count($closedIssues) ? $closedIssues->pluck('hodreviewedBy.name')->toArray() : [];
        $allhodreviewernames = implode(',', array_unique($hodreviewernameslist));
        $closedBynameslist = count($closedIssues) ? $closedIssues->pluck('closedBy.name')->toArray() : [];
        $allcloseBynames = implode(',', array_unique($closedBynameslist));

        $issuedocs = DB::table('multipleissues')->get();
        $actiondocs = DB::table('multipleactions')->get();
        $issuereviewers = DB::table('issuereviewers')->pluck('email');
        $mr = DB::table('internalaudits')->pluck('email')->toArray();

        $table_columns_active =  ModuleClosedPageDetail::where('module_name','audit')
                                ->where('is_active',true)
                                ->whereNotIn('column_name', ['process stage', 'action status'])
                                ->orderBy('column_position', 'asc')
                                ->get();
        $table_columns_active = $table_columns_active->merge(
                            ModuleClosedPageDetail::where('module_name','audit')
                                ->where('is_active',true)
                                ->whereIn('column_name', ['process stage', 'action status'])
                                ->orderBy('column_position', 'asc')
                                ->get()
                        );

        $table_columns_names_active =  ModuleClosedPageDetail::where('module_name','audit')
            ->where('is_active',true)
            ->pluck('column_name')
            ->toArray();

        $table_columns_names_enabled_by_user =  ModuleClosedPageDetail::where('module_name','audit')
            ->where('enabled_by_user','!=',0)
            ->get()->count();

        if (!$table_columns_names_enabled_by_user) {
            $isActiveColumns = ModuleClosedPageDetail::where('module_name','audit')
                ->whereNotIn('column_name',['slno','id','title','process stage','action status'])
                ->where('is_active', true)
                ->where('enabled_by_user', 0)
                ->get();
            $defaultPosition = [
                'type' => 4,
                'department' => 5,
                'responsibility' => 6,
                'due date' => 7,
            ];

            foreach ($isActiveColumns as $column) {
                if (array_key_exists($column->column_name, $defaultPosition) && $column->column_position != $defaultPosition[$column->column_name]) {
                    $table_columns_names_enabled_by_user = true;
                    break;
                }
            }
        }

        $table_columns_non_active =  ModuleClosedPageDetail::where('module_name','audit')
            ->where('is_active',false)
            ->orderBy('column_position', 'asc')
            ->get();

        $division_names = Division::pluck('name')->toArray();
        $alldivisionnames =$division_names? implode(',', array_unique($division_names)):'';

        $sub_division_names = Area::pluck('name')->toArray();
        $allsubdivisionnames =$sub_division_names? implode(',', array_unique($sub_division_names)):'';

        $raised_by_names = User::pluck('name')->toArray();
        $allraisedbynames = implode(',', array_unique($raised_by_names));
        $models = DB::table('modenadisable')->first();

        return view('user.action.closedissue', compact('issues1', 'users', 'mr', 'issuereviewers',
                              'alldivisionnames', 'allsubdivisionnames', 'allraisedbynames', 'responsibles'))
                                    ->with('issuedocs', $issuedocs)
                                    ->with('actiondocs', $actiondocs)
                                    ->with('alltypenames', $alltypenames)
                                    ->with('allpstages', $allpstages)
                                    ->with('dueDateYears', $dueDateYears)
                                    ->with('addDateYears', $addDateYears)
                                    ->with('actionReviewedDateYears', $actionReviewedDateYears)
                                    ->with('hodReviewedDateYears', $hodReviewedDateYears)
                                    ->with('issueClosedDateYears', $issueClosedDateYears)
                                    ->with('allcloseBynames', $allcloseBynames)
                                    ->with('alldeptnames', $alldeptnames)
                                    ->with('allusernames', $allusernames)
                                    ->with('allreviewernames', $allreviewernames)
                                    ->with('allhodreviewernames', $allhodreviewernames)
                                    ->with('table_columns_active',$table_columns_active)
                                    ->with('table_columns_non_active',$table_columns_non_active)
                                    ->with('table_columns_names_active',$table_columns_names_active)
                                    ->with('table_columns_names_enabled_by_user',$table_columns_names_enabled_by_user)
                                    ->with('model', $models);
    }

    public function closedissue()
    {
        $issues1 = [];
        $issuereviewers = DB::table('issuereviewers')->pluck('email');
        $mr = DB::table('internalaudits')->pluck('email');

        $issues1 = Issue::wherein('status', array('closed', 'closed after due'))
            ->where('status', '!=', 'removed')
            ->where('category', 'Internal Audit')
            ->get();

        $models = DB::table('modenadisable')->First();
        return view('user.action.closedissue', compact('issues1', 'mr', 'issuereviewers'))->with('model', $models);
    }

    //edit responsibility
    public function editresp()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $type = Type::all();
            $departments = Departments::active()->get();
            $data = Issue::where('id', $id)->get();
            foreach ($data as $d) {
                $res = $d->responsibility;
            }
            $resname = DB::table('users')->where('email', $res)->value('name');
            $user = User::where('approved',true)->Orderby('name')->get();
            $models = DB::table('modenadisable')->First();

            return view('user.action.editresp')
                ->withUser($user)
                ->withDepartments($departments)
                ->withData($data)
                ->withType($type)
                ->withResname($resname)
                ->with('model', $models);
        }
    }

    public function submittresp(Request $req)
    {
        $id = $req->input('id');
        $resp = $req->input('resp');
        $a = DB::table('users')->get();
        $e = Auth::user()->email;
        $department = $req->input('department');
        if ($department == 1) {
            $data = array('responsibility' => $resp);
            Issue::where('id', $id)->update($data);
        } else {
            $dept = DB::table('departments')->where('id', $department)->value('departmentname');
            $exist = Issue::where('department', $dept)->orderBy('id', 'desc')->first();
            if ($exist) {
                $c = $exist->count;
                $count = $c + 1;
            } else {
                $count = "1";
            }
            $data = array('responsibility' => $resp, 'count' => $count, 'department' => $department);
            Issue::where('id', $id)->update($data);
            $code = Departments::where('departmentname', $dept)->value('code');
            $code = strtoupper(substr($code, 0, 3));
            $y = Issue::where('id', $id)->value('year');
            $year = substr($y, -2);
            $issueid = $code . $count . "/" . $year;
            $data5 = array('issueid' => $issueid, 'year' => $y);
            Issue::where('count', $count)
                ->where('department', $department)
                ->update($data5);
        }
        $issueid = DB::table('issues')->where('id', $id)->value('issueid');
        $type = DB::table('issues')->where('id', $id)->value('type');
        $the_type = Type::where('order', $type)->first();
        $type_name = $the_type ? $the_type->name : '';
        $dept = DB::table('issues')->where('id', $id)->value('department');
        $department = DB::table('departments')->where('id', $dept)->value('departmentname');
        $duedate = DB::table('issues')->where('id', $id)->value('duedate');
        $data = array('department' => $department, 'issueid' => $issueid, 'type' => $type_name, 'duedate' => $duedate);
        Mail::to($resp)->send(new AuditResponsibilityChanged($data));
        //inserting values to activity table
        $activity = "Audit Management Issue is Edited and updated with Responsibility  as " . $resp . "  where Issue ID is  " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        Session::flash('msg', "Data edited successfully");
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    public function openissue()
    {
        $issuereviewers = DB::table('issuereviewers')->pluck('email');
        $mr = DB::table('representatives')->pluck('email');
        $issues2 = [];
        $de = Auth::user()->email;
        $issues2 = Issue::where('responsibility', '=', $de)
            ->where('status', 'open')
            ->wherein('processstage', array(
                'Awaiting actions',
                'Actions Proposed',
                'Action Reviewed, OK',
                'Action Reviewed, Not OK',
                'rejected',
                'Need More Action',
                'Actions Completed',
                'HOD Rejected',
            ))

            ->where('status', '!=', 'removed')
            ->where('category', 'Internal Audit')
            ->orderBy('created_at', 'desc')
            ->get();
        $issuedepts = Issue::groupBy('department')->where('status', 'open')->pluck('department')->toArray();
        $departmentnames = array();
        foreach ($issuedepts as $issuedept) {
            $dept_names = DB::table('departments')->where('id', $issuedept)->value('departmentname');
            if ($dept_names) {
                array_push($departmentnames, $dept_names);
            }
        }
        $alldeptnames = implode(',', $departmentnames);

        $typenames = Type::pluck('name')->toArray();
        $alltypenames = implode(',', $typenames);

        $processstages = ['Awaiting actions', 'Actions Proposed', 'Action Approved',
            'Action Rejected', 'Need More Action', 'Actions Completed',
        ];
        $allpstages = implode(',', $processstages);

        $yearvalues = Issue::select(DB::raw('YEAR(duedate) AS year'))
            ->where('status', 'open')
            ->distinct()->get()->toArray();
        $year_arr = array();
        foreach ($yearvalues as $yearvalue) {
            array_push($year_arr, $yearvalue['year']);
        }
        sort($year_arr);
        $years = implode(',', array_unique($year_arr));

        //$users = DB::table('users')->get();
        $users = User::where('approved',true)->get();
        $models = DB::table('modenadisable')->First();

        return view('user.action.openissue', compact('issues2', 'users', 'issuereviewers',
            'mr', 'alldeptnames', 'alltypenames', 'years', 'allpstages'))->with('model', $models);
    }

    public function editopen()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = Issue::where('id', $id)->get();
            //$user = User::all();
            $user = User::where('approved',true)->get();
            $res = Issue::where('id', $id)->value('responsibility');
            $resp = DB::table('users')->where('email', $res)->value('name');
            $type = Type::all();
            // $rec1 = DB::table('multipleissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec1 = DB::table('multipleissues')->where('id', $id)
                                               ->whereNotNull('issue_file')
                                               ->where('issue_file', '!=', '')
                                               ->get();
            // $rec2 = DB::table('multipleactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $rec2 = DB::table('multipleactions')->where('id', $id)
                                                ->whereNotNull('action_file')
                                                ->where('action_file', '!=', '')
                                                ->get();
            $models = DB::table('modenadisable')->First();
            $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
            $typeHasManageWorkflow = Type::where('is_manage_workflow', true)->pluck('order')->toArray();
            $userList = User::where('approved',true)->orderBy('name', 'ASC')->select('name', 'id')->get();

            return view('user.action.editopen')
                ->withUser($user)
                ->withData($data)
                ->withRec1($rec1)
                ->withRec2($rec2)
                ->withType($type)
                ->with('resp', $resp)
                ->with('userList', $userList)
                ->with('typeHasManageWorkflow', $typeHasManageWorkflow)
                ->with('rootcorrectives', $rootcorrectives)
                ->with('model', $models);
        }
    }

    public function detailOpen($id)
    {
        $data = Issue::findOrFail($id);
        $notificationEmail = explode(',', $data->notification);
        $notificationUsers = User::whereIn('email', $notificationEmail)->get();
        $frequency = $data->frequency ? explode(',', $data->frequency) : [];
        //$user = User::all();
        $user = User::where('approved',true)->get();
        $res = Issue::where('id', $id)->value('responsibility');
        $resp = DB::table('users')->where('email', $res)->value('name');
        $type = Type::all();
        $rec1 = DB::table('multipleissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
        $rec2 = DB::table('multipleactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
        $division = Division::where('id',$data->division_id)->value('name');
        $area = Area::where('id',$data->area_id)->value('name');
        $models = DB::table('modenadisable')->First();
        $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
        return view('user.action.detailopen')->with('notificationUsers', $notificationUsers)
            ->with('frequency', $frequency)
            ->withUser($user)
            ->withIssue($data)
            ->withRec1($rec1)
            ->withRec2($rec2)
            ->withType($type)
            ->with('resp', $resp)
            ->with('rootcorrectives', $rootcorrectives)
            ->with('division',$division)
            ->with('area',$area)
            ->with('model', $models);
    }

    public function exportPDFdetailopen($id)
    {
        $issue = Issue::findOrFail($id);
        $dept = DB::table('departments')->where('id', $issue->department)->value('departmentname');
        $division = Division::where('id',$issue->division_id)->value('name');
        $area = Area::where('id',$issue->area_id)->value('name');
        $switch = 0;
        $type = App\Type::where('order', $issue->type)->first();
        $switch = $type ? 1 : 0;
        $issue_docs_of_one_item = App\Multipleissue::where('id', $issue->id)->where('issue_file', '!=', 'NULL')->where('issue_file', '!=', null)->get();
        $action_docs_of_one_item = App\Mutlipleaction::where('id', $issue->id)->where('action_file', '!=', 'NULL')->where('action_file', '!=', null)->get();
        $notificationEmail = explode(',', $issue->notification);
        $notificationUsers = User::whereIn('email', $notificationEmail)->get();
        $frequency = $issue->frequency ? explode(',', $issue->frequency) : [];
        $pdf = \PDF::loadView('user.action.detailopen_pdf',
            compact(
                'issue', 'dept', 'type', 'switch',
                'issue_docs_of_one_item', 'action_docs_of_one_item',
                'notificationUsers', 'frequency','division','area'
            ));
        return $pdf->download('detail_' . $issue->issueid . '.pdf');
    }
    public function exportauditreporttemplate($id)
    {
         $file_name = ModuleReportDetail::where('module_name','audit')->value('file_name');
         $templatePath = public_path('/storage/ModuleReportTemplates/audit/'.$file_name);
         if (!File::exists(public_path('/storage/ModuleReportTemplates/user/audit/'))) {
            File::makeDirectory(public_path() . '/storage/ModuleReportTemplates/user/audit/', 0755, true);
        }
         $outputPath = public_path('storage/ModuleReportTemplates/user/audit/audit_issue_filled_template.docx');
         copy($templatePath, $outputPath);
         $issue = Issue::find($id);
         if (!$issue) {
             return response()->json(['error' => 'No data found'], 404);
         }

         $department_value = $issue->dept?$issue->dept->departmentname:'--';
         $posted_by = $issue->postedBy?$issue->postedBy->name:'--';
         $reviewed_by = $issue->reviewedBy?$issue->reviewedBy->name:'--';
         $approved_by = $issue->approvedBy?$issue->approvedBy->name:'--';
         $closed_by = $issue->closedBy?$issue->closedBy->name:'--';
         $type = $issue->typ?$issue->typ->name:'--';
         $category = $issue->category;
         $division =$issue->division_id ? Division::where('id',$issue->division_id)->value('name') :'--';
         $sub_division =$issue->area_id ? Area::where('id',$issue->area_id)->value('name') :'--';
         if($issue->related_risks){
            $relatedrisk = array();
            $relatedRisks = json_decode($issue->related_risks, true);
            if(is_array($relatedRisks) || is_object($relatedRisks)){
                foreach($relatedRisks as $riskId => $riskItem){
                    array_push($relatedrisk,$riskItem);
                }
            }
            $related_risk_data = implode(',',$relatedrisk);
         }else{
            $related_risk_data = '--';
         }

         // Open the DOCX file as a ZIP archive
         $zip = new ZipArchive;
         if ($zip->open($outputPath) === TRUE) {
             $xmlString = $zip->getFromName('word/document.xml');
             if (!$xmlString) {
                 return response()->json(['error' => 'Failed to read document.xml'], 500);
             }
            File::put(public_path('storage/ModuleReportTemplates/user/audit/original_document.xml'), $xmlString);
            $dom = new \DOMDocument();
            $dom->loadXML($xmlString);

            $replacements = [
                '{{id}}' => $issue->issueid,
                '{{postdate}}' => $issue->created_at?date('d-m-Y',strtotime($issue->created_at)):'--',
                '{{dept}}' => $department_value,
                '{{cat}}'  => $category,
                '{{type}}' => $type,
                '{{desp}}' => $issue->observation?$issue->observation:'--',
                '{{reported}}' => $posted_by,
                '{{actionreviewed}}' => $reviewed_by,
                '{{root}}' => $issue->rootcause?$issue->rootcause:'--',
                '{{correct}}' => $issue->correction?$issue->correction:'--',
                '{{correctact}}' => $issue->correctiveaction?$issue->correctiveaction:'--',
                //'{{approved}}' => $approved_by,
                //'{{closed}}' => $closed_by,
                //'{{tdate}}' => $issue->duedate?date('d-m-Y',strtotime($issue->duedate)):'--',
                '{{div}}'=> $division,
                '{{subdiv}}'=>$sub_division,
                '{{relatedrisk}}'=>$related_risk_data,
                '{{observe}}'=>$issue->observation,
                '{{agreedact}}'=>$issue->agreedaction?$issue->agreedaction:'--',
                '{{responsible}}'=>$issue->responsible ? $issue->responsible->name : '--',
                '{{duedate}}'=>$issue->duedate ? date('d-m-Y', strtotime($issue->duedate)):'--',
                '{{pstage}}'=>$issue->processstage,
                '{{attachmentissues}}'=>$issue->file?$issue->file:'--',
                '{{attachmentaction}}'=>$issue->action_file?$issue->action_file:'--',
                '{{remarks}}'=>$issue->remarks?$issue->remarks:'--',
                '{{actionremarks}}'=>$issue->action_remarks?$issue->action_remarks:'--',
                '{{hodreviewed}}'=>$issue->hodreviewedBy?$issue->hodreviewedBy->name:'--',
                '{{hodreviewdate}}'=>$issue->hod_reviewed_by_date ? date('d-m-Y', strtotime($issue->hod_reviewed_by_date)):'--',
                '{{actionrevieweddate}}'=>$issue->reviewed_by_date ? date('d-m-Y', strtotime($issue->reviewed_by_date)):'--',
                '{{closedby}}'=>$issue->closedBy ? $issue->closedBy->name : '-',
                '{{closeddate}}'=>$issue->closed ? date('d-m-Y', strtotime($issue->closed)):'--',

            ];

            $replacements = array_map(function($value) {
                return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
            }, $replacements);

            foreach ($replacements as $placeholder => $value) {
                 $xmlString = str_replace($placeholder, $value, $xmlString);
             }

             File::put(public_path('storage/ModuleReportTemplates/user/audit/modified_document.xml'), $xmlString);
             $zip->addFromString('word/document.xml', $xmlString);
             $zip->close();

            return response()->download($outputPath)->deleteFileAfterSend(true);
            }else {
                return response()->json(['error' => 'Could not open DOCX file'], 500);
            }
    }
    public function displayrootcorrectiveajax(Request $request)
    {
        $data_id = $request->input('data_id');
        $rootcause = AuditRootCauseCorrectiveActionDetail::where('id', $data_id)->value('root_cause');
        $corrective_action = AuditRootCauseCorrectiveActionDetail::where('id', $data_id)->value('corrective_action');

        return response()->json([
            'status' => 200,
            'rootdata' => $rootcause,
            'correctdata' => $corrective_action,
        ]);

    }
    public function updateopen(Request $r)
    {
        $v = Validator::make($r->all(), [
            'rootcause' => 'nullable',
            'correctiveaction' => 'nullable',
            'correction' => 'required',
            'hod_user_input' => ['required_if:is_need_verify_input,==,"1"', 'nullable', 'exists:users,id'],
        ], [
            'hod_user_input.required_if' => 'HOD user is required',
            'hod_user_input.exists' => 'HOD user is invalid',
        ]);

        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()->withErrors($v)->withInput()->with('model', $models);
        } else {
            $app_url = BaseController::addSlashToEndOfURL();
            $company = BaseController::getCompanyFromAppRUL();

            $cer = null;
            if ($r->hasFile('action_file')) {
                $file_array = $r->file('action_file');
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

            $rootcause = $r->input('rootcause');
            $correctiveaction = $r->input('correctiveaction');
            $correction = $r->input('correction');
            $type = $r->input('type'); // it is name of type

            $typ = Type::where('name', $type)->first();
            $typ_order = $typ ? $typ->order : 0;
            $id = $r->input('posted');
            $isNeedVerify = $r->get('is_need_verify_input');
            $hodUserId = $r->get('hod_user_input');
            if ($r->confirm_propose == 'yes') {
                $data = array(
                    'type' => $typ_order,
                    'rootcause' => $rootcause,
                    'correctiveaction' => $correctiveaction,
                    'correction' => $correction,
                    'action_file' => $cer,
                    'processstage' => 'Actions Completed',
                    'impl_status' => 'Completed-100%',
                    'is_fully_implement' => true,
                );
                if ($isNeedVerify == 1) {
                    $data['hod_user'] = $hodUserId;
                    $data['is_need_auditee_verify'] = true;
                    $data['processstage'] = 'HOD Review awaiting';
                    $data['impl_status'] = 'Not Started-0%';
                }

                Issue::where('id', $id)->update($data);
                $title = Issue::where('id', $id)->pluck('title');
                $activity = "An  issue " . $title . " process stage is updated as Action Completed and implementation_status as Completed-100%";
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                DB::table('viewactivity')->insert($datas);
            } else if ($r->confirm_propose == 'no') {
                $data = array(
                    'type' => $typ_order,
                    'rootcause' => $rootcause,
                    'correctiveaction' => $correctiveaction,
                    'correction' => $correction,
                    'action_file' => $cer,
                    'processstage' => 'Actions Proposed',
                    'is_fully_implement' => false,
                );

                if ($isNeedVerify == 1) {
                    $data['hod_user'] = $hodUserId;
                    $data['is_need_auditee_verify'] = true;
                    $data['processstage'] = 'HOD Review awaiting';
                }

                Issue::where('id', $id)->update($data);
                $updated_data = Issue::where('id', $id)->first();
                $the_type = Type::where('order', $updated_data->type)->first();
                $type_name = $the_type ? $the_type->name : '';

                $dept = DB::table('departments')->where('id', $updated_data->department)->value('departmentname');
                $maildata = array(
                    'issueid' => $updated_data->issueid,
                    'id' => $id,
                    'type' => $type_name,
                    'department' => $dept,
                    'app_url' => $app_url,
                    'company' => $company,
                    'duedate' => date("d/m/Y", strtotime($updated_data->duedate)),
                );
                $emails = IssueReviewer::pluck('email')->toArray();
                // foreach ($emails as $em) {
                //     Mail::to($em)->send(new ActionProposed($maildata));
                // }
                Mail::to($emails)->send(new ActionProposed($maildata));

                $title = Issue::where('id', $id)->pluck('title');
                $activity = "An open issue " . $title . " process stage is updated as Action Proposed ";
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                DB::table('viewactivity')->insert($datas);
            }
            if ($isNeedVerify == 1) {
                $this->sendNoticeMailToHodUser($hodUserId, $id);
            }

            $expl_actions = explode(',', $cer);
            foreach ($expl_actions as $expl_action) {
                $data1[] = array('id' => $id, 'action_file' => $expl_action);
            }
            DB::table('multipleactions')->insert($data1);

            $models = DB::table('modenadisable')->First();

            return redirect('/user/auditmgmt')->with('model', $models);
        }
    }

    /**
     * Send notice mail of review audit to HOD user
     *
     * @param $hodUserId
     * @param $issueId
     *
     * @return void
     */
    private function sendNoticeMailToHodUser($hodUserId, $issueId)
    {
        $receiver = User::findOrFail($hodUserId);
        $issue = Issue::findOrFail($issueId);
        $responsibilityUser = User::where('email', $issue->responsibility)->first();
        $typeObj = Type::where('order', $issue->type)->first();
        $departmentObj = Departments::where('id', $issue->department)->first();
        $appUrl = BaseController::addSlashToEndOfURL();
        $emailData = [
            'name' => $receiver->name,
            'responsibility' => $responsibilityUser ? $responsibilityUser->name : '',
            'issueid' => $issue->issueid,
            'type' => $typeObj ? $typeObj->name : '',
            'department' => $departmentObj ? $departmentObj->departmentname : '',
            'duedate' => date('d-m-Y', strtotime($issue->duedate)),
            'app_url' => $appUrl
        ];
        Mail::to($receiver)->send(new App\Mail\HodReviewNotification($emailData));
        Log::info('[ActionController] Send mail to reviewer');
    }

    public function editrespondedissue()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = Issue::where('id', $id)->get();
            //$user = User::orderBy('name')->get();
            $user = User::where('approved',true)->get();
            $res = Issue::where('id', $id)->value('responsibility');
            $resp = DB::table('users')->where('email', $res)->value('name');
            $type = Type::all();
            //$rec1 = DB::table('multipleissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec1 = DB::table('multipleissues')->where('id', $id)
                                               ->whereNotNull('issue_file')
                                               ->where('issue_file', '!=', '')
                                               ->get();
            //$rec2 = DB::table('multipleactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $rec2 = DB::table('multipleactions')->where('id', $id)
                                                ->whereNotNull('action_file')
                                                ->where('action_file', '!=', '')
                                                ->get();
            $models = DB::table('modenadisable')->First();
            return view('user.action.editrespondedissue')->withUser($user)
                ->withData($data)
                ->withRec1($rec1)
                ->withRec2($rec2)
                ->withType($type)
                ->with('resp', $resp)
                ->with('model', $models);
        }
    }
    public function updaterespondedissue(Request $r)
    {
        $v = Validator::make($r->all(), [
            'rootcause' => 'required',
            'correctiveaction' => 'required',
            'correction' => 'required',
        ]);

        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();

            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $cer = null;
            if ($r->hasFile('action_file')) {
                $file_array = $r->file('action_file');
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
            $type = $r->input('type');
            $rootcause = $r->input('rootcause');
            $correctiveaction = $r->input('correctiveaction');
            $correction = $r->input('correction');
            $impl_status = $r->input('impl_status');
            // $res   =  $r->input('responsibility');
            $id = $r->input('posted');

            $data = array(
                //'type' => $type,
                'rootcause' => $rootcause,
                'correctiveaction' => $correctiveaction,
                'correction' => $correction,
                'action_file' => $cer,
                'status' => 'open',
                'processstage' => 'Actions Proposed',
            );

            Issue::where('id', $id)->update($data);
            $d = Issue::latest('updated_at')->first();
            $id = $d->id;
            $action_files = $d->action_file;
            $expl_action = explode(',', $action_files);
            foreach ($expl_action as $action_file) {
                $data1 = array('id' => $id, 'action_file' => $action_file);
                DB::table('multipleactions')->insert($data1);
            }
            $title = Issue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Actions Proposed and  status as open";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);
            $dept = DB::table('departments')->where('id', $d->department)->value('departmentname');
            $typ = Type::where('order', $d->type)->first();
            $type_name = $typ ? $typ->name : '';
            $data = array(
                'issueid' => $d->issueid,
                'type' => $type_name,
                'department' => $dept,
                'duedate' => date("d/m/Y", strtotime($d->duedate)),
            );
            $emails = IssueReviewer::pluck('email');
            Mail::to($emails)->send(new ActionUpdated($data));

            // if ($isNeedVerify == 1) {
            //     $this->sendNoticeMailToHodUser($hodUserId, $id);
            // }

            $models = DB::table('modenadisable')->First();
            return redirect('/user/auditmgmt')->with('model', $models);
        }
    }

    public function reviewstatus()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $type = Type::all();
            $data = Issue::where('id', $id)->get();
            //$rec1 = DB::table('multipleissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec1 = DB::table('multipleissues')->where('id', $id)
                                            ->whereNotNull('issue_file')
                                            ->where('issue_file', '!=', '')
                                            ->get();
            //$rec2 = DB::table('multipleactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $rec2 = DB::table('multipleactions')->where('id', $id)
                                                ->whereNotNull('action_file')
                                                ->where('action_file', '!=', '')
                                                ->get();
            $models = DB::table('modenadisable')->First();
            return view('user.action.statusreview')->withData($data)
                ->withRec1($rec1)
                ->withRec2($rec2)
                ->withType($type)
                ->with('model', $models);
        }
    }

    public function actionreviewed_ok(Request $req)
    {
        $app_url = BaseController::addSlashToEndOfURL();
        $company = BaseController::getCompanyFromAppRUL();

        $id = $req->input('id');
        $data = array(
            'status' => 'open',
            'processstage' => 'Action Reviewed, OK',
            'active' => 'yes',

            'title' => $req->input('title'),
            'observation' => $req->input('observation'),
            'agreedaction' => $req->input('agreedaction'),

            'rootcause' => $req->input('rootcause'),
            'correctiveaction' => $req->input('correctiveaction'),
            'correction' => $req->input('correction'),
            'reviewedby' => Auth::user()->employeeid,
            'reviewed_by_date'=>date('Y-m-d'),
        );
        Issue::where('id', $id)->update($data);

        $data = Issue::where('id', $id)->first();

        $posted_emp = DB::table('users')->where('employeeid', $data->postedby)->first();
        $dept = DB::table('departments')->where('id', $data->department)->value('departmentname');

        if ($posted_emp) {
            $name = $posted_emp->name;
        } else {
            $name = '--';
        }

        $maildata = array(
            'issueid' => $data->issueid,
            'name' => $name,
            'type' => $data->typ ? $data->typ->name : '',
            'department' => $dept,
            'app_url' => $app_url,
            //'company' => $company,
            'duedate' => date("d/m/Y", strtotime($data->duedate)),
        );

        $responsible = $data->responsibility;
        Mail::to($responsible)->send(new ReviewStatusFoundOK($maildata));

        $notification = $data->notification;
        $noti = explode(',', $notification);
        foreach ($noti as $n) {
            if ($n != "") {
                Mail::to($n)->send(new ReviewStatusFoundOK($maildata));
            }
        }

        $title = Issue::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . " process stage is updated as Action Reviewed, OK and  status as open";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        DB::table('viewactivity')->insert($datas);

        Session::flash('msg', "Action reviewed successfully");
        $models = DB::table('modenadisable')->First();
        return redirect('/user/auditmgmt')->with('model', $models);

    }

    public function notok_remarks()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $models = DB::table('modenadisable')->First();
            return view('user.action.notok_remarks')->withId($id)->with('model', $models);
        }
    }

    public function notok_addremarks(Request $req)
    {
        $app_url = BaseController::addSlashToEndOfURL();
        $company = BaseController::getCompanyFromAppRUL();

        $action_remarks = $req->input('action_remarks');
        $id = $req->input('id');

        $data = array(
            'status' => 'open',
            'processstage' => 'Action Reviewed, Not OK',
            'action_remarks' => $action_remarks,
            'reviewedby' => Auth::user()->employeeid,
            'reviewed_by_date'=>date('Y-m-d'),
        );
        Issue::where('id', $id)->update($data);

        $data = Issue::where('id', $id)->first();

        $posted_emp = DB::table('users')->where('employeeid', $data->postedby)->first();
        $dept = DB::table('departments')->where('id', $data->department)->value('departmentname');

        if ($posted_emp) {
            $name = $posted_emp->name;
        } else {
            $name = '--';
        }

        $maildata = array(
            'issueid' => $data->issueid,
            'name' => $name,
            'type' => $data->typ ? $data->typ->name : '',
            'department' => $dept,
            'remarks' => $action_remarks,
            'app_url' => $app_url,
            'company' => $company,
            'duedate' => date("d/m/Y", strtotime($data->duedate)),
        );
        $responsible = $data->responsibility;
        Mail::to($responsible)->send(new ReviewStatusFoundNotOK($maildata));

        $notification = $data->notification;
        $noti = explode(',', $notification);
        foreach ($noti as $n) {
            if ($n != "") {
                Mail::to($n)->send(new ReviewStatusFoundNotOK($maildata));
            }
        }

        $title = Issue::where('id', $id)->pluck('title');
        $activity = "An  issue " . $title . " process stage is updated as Action Reviewed, Not OK and  status as open";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        DB::table('viewactivity')->insert($datas);

        echo "<script>alert('Action reviewed successfully')</script>";
        $models = DB::table('modenadisable')->First();
        return redirect('/user/auditmgmt')->with('model', $models);
    }

    public function closure()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $type = Type::all();
            $data = Issue::where('id', $id)->get();
            //$rec1 = DB::table('multipleissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec1 = DB::table('multipleissues')->where('id', $id)
                                                ->whereNotNull('issue_file')
                                                ->where('issue_file', '!=', '')
                                                ->get();
            //$rec2 = DB::table('multipleactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $rec2 = DB::table('multipleactions')->where('id', $id)
                                                ->whereNotNull('action_file')
                                                ->where('action_file', '!=', '')
                                                ->get();
            $models = DB::table('modenadisable')->First();
            return view('user.action.closurereview')->withData($data)
                ->withType($type)
                ->withRec1($rec1)
                ->withRec2($rec2)
                ->with('model', $models);
        }
    }

    public function closurereview(Request $request)
    {
        $app_url = BaseController::addSlashToEndOfURL();
        $company = BaseController::getCompanyFromAppRUL();

        switch ($request->input('submit')) {
            case 'Need More Action':
                $id = $request->issueid;
                $data = array(
                    'title' => $request->input('title'),
                    //'type' => $request->input('type'),
                    'observation' => $request->input('observation'),
                    'agreedaction' => $request->input('agreedaction'),
                    'rootcause' => $request->input('rootcause'),
                    'correctiveaction' => $request->input('correctiveaction'),
                    'correction' => $request->input('correction'),
                );
                Issue::where('id', $id)->update($data);

                return redirect('/user/actionsystem/changes?val=' . $id);
                break;

            case 'Close Issue':
                $id = $request->issueid;
                $date = date('Y-m-d');
                $closedby = Auth::user()->employeeid;
                $data = array(
                    'title' => $request->input('title'),
                    //'type' => $request->input('type'),
                    'observation' => $request->input('observation'),
                    'agreedaction' => $request->input('agreedaction'),
                    'rootcause' => $request->input('rootcause'),
                    'correctiveaction' => $request->input('correctiveaction'),
                    'correction' => $request->input('correction'),
                    'status' => 'closed',
                    'processstage' => 'Accomplished',
                    'closed' => $date,
                    'closedby' => $closedby,
                );
                Issue::where('id', $id)->update($data);

                $issue = Issue::where('id', $id)->firstOrFail();
                $date = Carbon::parse($issue->created_at);
                $year = $date->format('Y');

                //substr($d1->created_at, 6, 4) === $year
                $title = Issue::where('id', $id)->pluck('title');
                $activity = "An  issue " . $title . " process stage is updated as Accomplished, status as closed.";
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                DB::table('viewactivity')->insert($datas);

                $data1 = Issue::where('id', $id)->get();
                foreach ($data1 as $data) {
                    $posted_emp = DB::table('users')->where('employeeid', $data->postedby)->pluck('name');
                    foreach ($posted_emp as $p) {
                        $name = $p;
                    }

                    $maildata = array('issueid' => $data->issueid, 'year' => $year, 'app_url' => $app_url, 'company' => $company);
                    $responsible = $data->responsibility;
                    Mail::to($responsible)->send(new IssueClosed($maildata));

                    $notification = $data->notification;
                    $noti = explode(',', $notification);
                    foreach ($noti as $n) {
                        if ($n != "") {
                            Mail::to($n)->send(new IssueClosed($maildata));
                        }
                    }
                }
                $models = DB::table('modenadisable')->First();
                return redirect('/user/auditmgmt')->with('model', $models);
                break;
        }
    }

    public function changes()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $models = DB::table('modenadisable')->First();
            return view('user.action.suggestchanges')->withId($id)->with('model', $models);
        }
    }

    // public function suggestchanges(Request $req)
    // {
    //     $app_url = BaseController::addSlashToEndOfURL();
    //     $company = BaseController::getCompanyFromAppRUL();

    //     if ($req->action_modified == "yes") {
    //         $remarks = $req->input('remarks');
    //         $id = $req->input('id');

    //         $data = array(
    //             'status' => 'open',
    //             'processstage' => 'Need More Action',
    //             'impl_status' => 'Partially Completed-50%',
    //             'remarks' => $remarks,
    //         );
    //         Issue::where('id', $id)->update($data);

    //         $data1 = Issue::where('id', $id)->get();
    //         foreach ($data1 as $data) {
    //             $posted_emp = DB::table('users')->where('employeeid', $data->postedby)->pluck('name');
    //             foreach ($posted_emp as $p) {
    //                 $name = $p;
    //             }

    //             $dept = DB::table('departments')->where('id', $data->department)->value('departmentname');
    //             $type = Type::where('order', $data->type)->first();
    //             $type_name = $type ? $type->name : '';
    //             $maildata = array(
    //                 'issueid' => $data->issueid,
    //                 'name' => $name,
    //                 'type' => $type_name,
    //                 'department' => $dept,
    //                 'remarks' => $data->remarks,
    //                 'app_url' => $app_url,
    //                 'company' => $company,
    //                 'duedate' => date("d/m/Y", strtotime($data->duedate)),
    //             );
    //             $responsible = $data->responsibility;
    //             Mail::to($responsible)->send(new ReviewStatusFoundNotOK($maildata));

    //             $notification = $data->notification;
    //             $noti = explode(',', $notification);
    //             foreach ($noti as $n) {
    //                 if ($n != "") {
    //                     Mail::to($n)->send(new ReviewStatusFoundNotOK($maildata));
    //                 }
    //             }
    //         }
    //         $title = Issue::where('id', $id)->pluck('title');
    //         $activity = "An  issue " . $title . " process stage is updated as Need More Action, status as open and implementation_status as Partially Completed-50%";
    //         $date = Carbon::now()->toDateTimeString();
    //         $date = Carbon::parse($date);
    //         $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
    //         DB::table('viewactivity')->insert($datas);
    //     } elseif ($req->action_modified == "no") {
    //         $remarks = $req->input('remarks');
    //         $id = $req->input('id');

    //         $data = array(
    //             'status' => 'open',
    //             'processstage' => 'Action Reviewed, OK',
    //             'impl_status' => 'Partially Completed-50%',
    //             'remarks' => $remarks,
    //         );
    //         Issue::where('id', $id)->update($data);

    //         $data1 = Issue::where('id', $id)->get();
    //         foreach ($data1 as $data) {
    //             $posted_emp = DB::table('users')->where('employeeid', $data->postedby)->pluck('name');
    //             foreach ($posted_emp as $p) {
    //                 $name = $p;
    //             }

    //             $dept = DB::table('departments')->where('id', $data->department)->value('departmentname');
    //             $type = Type::where('order', $data->type)->first();
    //             $type_name = $type ? $type->name : '';

    //             $maildata = array(
    //                 'issueid' => $data->issueid,
    //                 'name' => $name,
    //                 'type' => $type_name,
    //                 'department' => $dept,
    //                 'app_url' => $app_url,
    //                 'company' => $company,
    //                 'duedate' => date("d/m/Y", strtotime($data->duedate)),
    //             );
    //             $responsible = $data->responsibility;
    //             Mail::to($responsible)->send(new ReviewStatusFoundOK($maildata));

    //             $notification = $data->notification;
    //             $noti = explode(',', $notification);
    //             foreach ($noti as $n) {
    //                 if ($n != "") {
    //                     Mail::to($n)->send(new ReviewStatusFoundOK($maildata));
    //                 }
    //             }
    //         }
    //         $title = Issue::where('id', $id)->pluck('title');
    //         $activity = "An  issue " . $title . " process stage is updated as Action Reviewed, OK, status as open and implementation_status as Partially Completed-50%";
    //         $date = Carbon::now()->toDateTimeString();
    //         $date = Carbon::parse($date);
    //         $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
    //         DB::table('viewactivity')->insert($datas);

    //     }

    //     $models = DB::table('modenadisable')->First();
    //     return redirect('/user/auditmgmt')->with('model', $models);
    // }

    public function suggestchanges(Request $req)
    {
        $app_url = BaseController::addSlashToEndOfURL();
        $company = BaseController::getCompanyFromAppRUL();
        $remarks = $req->input('remarks');
        $id = $req->input('id');

        $main_data = Issue::where('id',$id)->first();
        $posted_emp = User::where('employeeid', $main_data->postedby)->value('name');
        $posted_emp_name = $posted_emp?$posted_emp:'--';
        $dept_name = DB::table('departments')->where('id', $main_data->department)->value('departmentname');
        $dept = $dept_name?$dept_name:'--';
        $type = Type::where('order', $main_data->type)->first();
        $type_name = $type ? $type->name : '--';


        if ($req->action_modified == "yes") {
            $data = array(
                'status' => 'open',
                'processstage' => 'Need More Action',
                'impl_status' => 'Partially Completed-50%',
                'remarks' => $remarks,
            );
            Issue::where('id', $id)->update($data);
            $maildata = array(
                'issueid' => $main_data->issueid,
                'name' => $posted_emp_name,
                'type' => $type_name,
                'department' => $dept,
                'remarks' => $main_data->remarks,
                'app_url' => $app_url,
                'company' => $company,
                'duedate' => date("d/m/Y", strtotime($main_data->duedate)),
            );
            $responsible = $main_data->responsibility;
            Mail::to($responsible)->send(new ReviewStatusFoundNotOK($maildata));

            $notification = $main_data->notification;
            $noti = explode(',', $notification);
            foreach ($noti as $n) {
                if ($n != "") {
                    Mail::to($n)->send(new ReviewStatusFoundNotOK($maildata));
                }
            }

            $title = Issue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Need More Action, status as open and implementation_status as Partially Completed-50%";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);
        } elseif ($req->action_modified == "no") {
            $data = array(
                'status' => 'open',
                'processstage' => 'Action Reviewed, OK',
                'impl_status' => 'Partially Completed-50%',
                'remarks' => $remarks,
            );
            Issue::where('id', $id)->update($data);

            $maildata = array(
                'issueid' => $main_data->issueid,
                'name' => $posted_emp_name,
                'type' => $type_name,
                'department' => $dept,
                'app_url' => $app_url,
                'company' => $company,
                'duedate' => date("d/m/Y", strtotime($main_data->duedate)),
            );
            $responsible = $main_data->responsibility;
            Mail::to($responsible)->send(new ReviewStatusFoundOK($maildata));

            $notification = $main_data->notification;
            $noti = explode(',', $notification);
            foreach ($noti as $n) {
                if ($n != "") {
                    Mail::to($n)->send(new ReviewStatusFoundOK($maildata));
                }
            }

            $title = Issue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Action Reviewed, OK, status as open and implementation_status as Partially Completed-50%";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);

        }

        $models = DB::table('modenadisable')->First();
        return redirect('/user/auditmgmt')->with('model', $models);
    }
    public function hundred()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = array(
                'processstage' => 'Actions Completed',
                'impl_status' => 'Completed-100%',
            );
            Issue::where('id', '=', $id)->update($data);
            $title = Issue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Action Completed and implementation_status as Completed-100%";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/auditmgmt')->with('model', $models);
        }
    }

    public function five()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(
                'impl_status' => 'Partially Completed-50%',
            );
            Issue::where('id', '=', $id)
                ->update($data);
            $title = Issue::where('id', $id)->pluck('title');
            $activity = "An  issue " . $title . " process stage is updated as Action Completed and implementation_status as Partially Completed-50%";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);

            $models = DB::table('modenadisable')->First();
            return redirect('/user/auditmgmt')->with('model', $models);
        }
    }

    public function two()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(
                'impl_status' => 'Progress-25%',
            );
            Issue::where('id', '=', $id)
                ->update($data);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/auditmgmt')->with('model', $models);
        }
    }

    public function zero()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = array(
                'impl_status' => 'Not Started-0%',
            );
            Issue::where('id', '=', $id)
                ->update($data);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/auditmgmt')->with('model', $models);
        }
    }

    public function reopen()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $type = Type::all();
            //$user = User::all();
            $user = User::where('approved',true)->get();
            $categories = Categories::all();
            $departments = Departments::active()->get();
            $data = Issue::where('id', '=', $id)->get();
            //$rec1 = DB::table('multipleissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec1 = DB::table('multipleissues')->where('id', $id)
                                            ->whereNotNull('issue_file')
                                            ->where('issue_file', '!=', '')
                                            ->get();
            //$rec2 = DB::table('multipleactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $rec2 = DB::table('multipleactions')->where('id', $id)
                                                ->whereNotNull('action_file')
                                                ->where('action_file', '!=', '')
                                                ->get();
            $models = DB::table('modenadisable')->First();
            return view('user.action.reopen')->withCategories($categories)
                ->withDepartments($departments)
                ->withType($type)
                ->withUser($user)
                ->withData($data)
                ->withId($id)
                ->withRec1($rec1)
                ->withRec2($rec2)
                ->with('model', $models);
        }
    }

    public function reopenissue(Request $req)
    {
        $v = Validator::make($req->all(), [
            'title' => 'required',
            'observation' => 'required',
            // 'duedate' => 'required|date|after:tomorrow',
            'duedate' => 'required|date',
            //'agreedaction' => 'required',
        ]);

        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $cer = null;

            if ($req->hasFile('file')) {
                $file_array = $req->file('file');
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
            $id = $req->input('id');
            $title = $req->input('title');
            $status = "open";
            $approvement = "yes";
            $processstage = "Awaiting actions";
            $impl_status = "Not Started-0%";
            $approvedby = Auth::user()->employeeid;

            $department = $req->input('department');
            $type = $req->input('type');
            $observation = $req->input('observation');
            $agreedaction = $req->input('agreedaction');
            $resp = $req->input('responsibility');
            $due_date = $req->input('duedate');

            if ($req->notification) {
                $notify = $req->input('notification');
                $notification = implode(',', $notify);
            } else {
                $notification = null;
            }

            $postedby = $req->input('postedby');

            $data = array(
                'type' => $type,
                'department' => $department,
                'title' => $title,
                'file' => $cer,
                'observation' => $observation,
                'duedate' => $due_date,
                'agreedaction' => $agreedaction,
                'postedby' => $postedby,
                'responsibility' => $resp,
                'notification' => $notification,
                'approvement' => $approvement,
                'status' => $status,
                'processstage' => $processstage,
                'impl_status' => $impl_status,
                'approvedby' => $approvedby,
                'active' => 'yes',
            );
            Issue::where('id', $id)->update($data);
            $year = date("Y");
            $data = array('year' => $year);
            Issue::where('id', $id)
                ->update($data);

            $d = Issue::latest('updated_at')->first();
            $name = DB::table('users')->where('employeeid', $postedby)->get();
            $id = $d->id;

            $files = $d->file;

            $expl = explode(',', $files);

            foreach ($expl as $file) {
                $data1 = array('id' => $id, 'issue_file' => $file);
                DB::table('multipleissues')->insert($data1);
            }

            Session::flash('msg', "Issue reopened successfully");
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    public function delete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $name = Issue::where('id', $id)->pluck('title');
            $activity = "An issue is Removed with title " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);

            DB::delete('delete from issues where id = :eid', ['eid' => $id]);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/auditmgmt')->with('model', $models);
        }
    }

    public function editreq()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $type = Type::all();
            $id = explode(',', $_GET['val']);
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data1[] = Issue::where('id', $d)->first();
                }
                $n = $n + 1;
            }
        }
        $data = $data1;
        $models = DB::table('modenadisable')->First();
        return view('user/action/editrequest')->withData($data)
            ->with('id', $id)
            ->with('type', $type)
            ->with('model', $models);
    }

    public function editrequest(Request $req)
    {
        $m = count($req->docnumber);

        $type = $req->input('type');
        $observation = $req->input('observation');
        $agreedaction = $req->input('agreedaction');
        $correction = $req->input('correction');
        $rootcause = $req->input('rootcause');
        $correctiveaction = $req->input('correctiveaction');
        $duedate = $req->input('duedate');
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = Issue::where('id', $d)->first();
            }
        }

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {
                    Issue::where('id', $s)
                        ->update(array(
                            'type' => $req->type[$n],
                            'observation' => $req->observation[$n],
                            'agreedaction' => $req->agreedaction[$n],
                            'correction' => $req->correction[$n],
                            'rootcause' => $req->rootcause[$n],
                            'correctiveaction' => $req->correctiveaction[$n],
                            'duedate' => $req->duedate[$n],
                        ));

                    $activity = "Issue is  Edited and Updated with new Details  where  ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
                    DB::table('viewactivity')->insert($datas);
                }
            }
            $n = $n + 1;
        }
        $models = DB::table('modenadisable')->First();
        return redirect('/user/auditmgmt')->with('model', $models);
    }

    //add audit observation button in auditschedule register
    public function addauditobservation()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            $data = DB::table('audit')->where('id', '=', $id)->first();
            $departmentname = DB::table('departments')->where('id', $data->department)->value('departmentname');
            if ($data->division_id == null && $data->area_id == null) {
                $listing_name = DB::table('departments')->where('id', $data->department)->value('departmentname');
                $listing_category = 'Department';
            }
            if ($data->division_id != null) {
                $listing_name = Division::where('id', $data->division_id)->value('name');
                $listing_category = 'Division';
            }
            if ($data->area_id != null) {
                $listing_name = Area::where('id', $data->area_id)->value('name');
                $listing_category = 'Subdivision';
            }

            $reviewers = DB::table('issuereviewers')->get();
            $representatives = DB::table('representatives')->get();
            $aud = DB::table('auditors')->get();
            $u = DB::table('users')->pluck('email');
            $list = collect([]);
            foreach ($reviewers as $reviewer) {
                $list = $list->push($reviewer->email);
            }

            foreach ($representatives as $representative) {
                $list = $list->push($representative->email);
            }
            foreach ($aud as $a) {
                $list = $list->push($a->email);
            }

            $auditors = $list->unique();
            $count = count($auditors);
            $categories = Categories::all();
            $type = Type::all();
            $user = User::where('approved',true)->orderBy('name')->get();
        }
        $models = DB::table('modenadisable')->First();
        $riskList = Risk::select('Rid', 'grp_id', 'riskname')->get();
        return view('user.action.addauditobservation')
            ->withCount($count)
            ->withU($u)
            ->withAuditors($auditors)
            ->withCategories($categories)
            ->withType($type)
            ->withUser($user)
            ->with('listing_name', $listing_name)
            ->with('listing_category', $listing_category)
            ->with('departmentname', $departmentname)
            ->with('dat', $data)
            ->with('model', $models)
            ->with('riskList', $riskList);
    }

    public function insertobservations(Request $req)
    {

        $v = Validator::make($req->all(), [
            'title' => 'required',
            'observation' => 'required',
            'duedate' => 'required|date|after:tomorrow',

        ]);

        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $riskData = [];
            if ($req->related_risks) {
                $riskIds = $req->input('related_risks');
                $risks = Risk::whereIn('Rid', $riskIds)->get();
                $riskData = [];
                foreach ($risks as $risk) {
                    $riskData[$risk->Rid] = $risk->riskname . '-' . $risk->grp_id;
                }
            }

            $cer = null;
            $agreedaction = 'NULL';
            $app_url = BaseController::addSlashToEndOfURL();
            $company = BaseController::getCompanyFromAppRUL();

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

            $category = $req->input('category');
            $approvement = "yes";
            $processstage = "Awaiting actions";
            $status = "open";
            $approvedby = Auth::user()->employeeid;

            $type = $req->input('type');
            $dep = $req->input('department');
            $department = DB::table('departments')->where('departmentname', $dep)->value('id');
            if ($req->input('listing_category') == 'Division') {
                $division_name = $req->input('listing_name');
                $division_id = Division::where('name', $division_name)->value('id');
            } else {
                $division_id = null;
            }
            if ($req->input('listing_category') == 'Subdivision') {
                $area_name = $req->input('listing_name');
                $area_id = Area::where('name', $area_name)->value('id');
            } else {
                $area_id = null;
            }
            $title = $req->input('title');
            $observation = $req->input('observation');
            $agreedaction = $req->input('agreedaction');
            $due_date = $req->input('duedate');
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
                $notification = null;
            }

            $postedby = $req->input('postedby');

            $exist = Issue::where('department', $department)->orderBy('id', 'desc')->first();
            if ($exist) {
                $c = $exist->count;
                $count = $c + 1;
            } else {
                $count = "1";
            }

            $auditId = $req->input('auditid');
            $issueid = $req->input('issueid');
            $id = strtoupper(substr($issueid, 0, 3));

            $y = substr($issueid, -2);
            $issueid = $id . $count . "/" . $y;
            // $yr = "20";
            // $year = $yr . $y;

            $year = Audit::where('id', $auditId)->value('year');
            $data4 = array(
                'issueid' => $issueid,
                'auditid' => $auditId,
                'year' => $year,
                'count' => $count,
                'category' => $category,
                'type' => $type,
                'department' => $department,
                'division_id' => $division_id,
                'area_id' => $area_id,
                'title' => $title,
                'file' => $cer,
                'observation' => $observation,
                'related_risks' => json_encode($riskData),
                'duedate' => $due_date,
                'frequency' => $frequency,
                'agreedaction' => $agreedaction,
                'postedby' => $postedby,
                'responsibility' => $resp,
                'notification' => $notification,
                'approvement' => $approvement,
                'processstage' => $processstage,
                'approvedby' => $approvedby,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            );

            Issue::insert($data4);

            $activity = "An issue is added with title " . $title;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
            DB::table('viewactivity')->insert($datas);

            $d = Issue::orderBy('id', 'desc')->first();
            $ReviewDate = date('Y-m-d ', strtotime($d->created_at));
            $data2 = array(
                'Monthlyfreq' => $ReviewDate,
                'Weeklyfreq' => $ReviewDate,
            );
            Issue::where('id', $d->id)->update($data2);

            $id = $d->id;
            $postedby = $d->postedby;
            $name = Auth::user()->name;

            $files = $d->file;
            $expl = explode(',', $files);
            foreach ($expl as $file) {
                $data1[] = array('id' => $id, 'issue_file' => $file);
            }
            DB::table('multipleissues')->insert($data1);

            $data = array(
                'issueid' => $d->issueid,
                'name' => $name,
                'type' => $d->typ ? $d->typ->name : '',
                'app_url' => $app_url,
                'company' => $company,
                'department' => Departments::where('id', $department)->value('departmentname'),
                'duedate' => date("d/m/Y", strtotime($d->duedate)),
            );

            $emails = IssueReviewer::pluck('email');
            // foreach ($emails as $em) {
            //     Mail::to($em)->send(new AddIssue($data));
            // }
            Mail::to($emails)->send(new AddIssue($data));

            Mail::to($d->responsibility)->send(new ActionResponseNotification($data));

            $noti = explode(',', $notification);
            foreach ($noti as $n) {
                if ($n != "") {
                    Mail::to($n)->send(new AddIssue($data));
                    Mail::to($n)->send(new ActionResponseNotification($data));
                }
            }
            Session::flash('msg', "Audit observation submitted successfully");
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    public function analysis()
    {
        $today = date('Y-m-d');
        $processes = DB::select("select departmentname from departments order by departmentname ASC");
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->departmentname);
        }

        $high_value = Type::where('order', 1)->value('order');
        $medium_value = Type::where('order', 2)->value('order');
        $low_value = Type::where('order', 3)->value('order');

        $deptopen = DB::select("select s.id,COUNT(r.status)AS number
                       FROM issues AS r
                       right JOIN departments AS s
                       ON r.department = s.id
                       and r.approvement='yes'and r.status='open' and r.category='Internal Audit'
                       group by s.id
                       order by s.departmentname ASC
                      ");
        $deptdue = DB::select("select s.id,COUNT(r.duedate)AS number
                        FROM  issues AS r
                        right JOIN departments AS s
                        ON r.department = s.id
                        and r.approvement='yes' and r.status='open' and r.duedate <= '$today' and r.category='Internal Audit'
                        group by s.id
                        order by s.departmentname ASC
                        ");
        $deptclose = DB::select("select s.id,COUNT(r.status)AS number
                       FROM  issues AS r
                       right JOIN departments AS s
                       ON r.department = s.id
                       and r.approvement='yes'and r.status='closed' and r.category='Internal Audit'
                       group by s.id
                       order by s.departmentname ASC
                      ");
        $dopen = [];
        foreach ($deptopen as $key) {
            array_push($dopen, $key->number);
        }
        $due = [];
        foreach ($deptdue as $key) {
            array_push($due, $key->number);
        }
        $dclose = [];
        foreach ($deptclose as $key) {
            array_push($dclose, $key->number);
        }
        $awaitings = DB::select("select s.id,COUNT(r.processstage)AS number
                      FROM  issues AS r
                      right JOIN departments AS s
                      ON r.department = s.id
                      and r.processstage='Awaiting actions' and r.category='Internal Audit'
                      group by s.id
                      order by s.departmentname ASC
                     ");
        $awaiting = [];
        foreach ($awaitings as $key) {
            array_push($awaiting, $key->number);
        }

        $names = DB::table('types')->orderBy('order')->pluck('order');
        $open = Issue::where('status', 'open')->where('category', 'Internal Audit')->get();
        $opencount = count($open);

        $years = DB::select("select year from years order by id ASC");
        $year = [];
        foreach ($years as $key) {
            array_push($year, $key->year);
        }
        $trends = DB::select("select s.year,COUNT(r.year)AS number
                    FROM  issues AS r
                    right JOIN years AS s
                    ON r.year= s.year
                    and r.approvement='yes' and r.category='Internal Audit'
                    group by s.year
                    order by s.year ASC
                ");
        $trend = [];
        foreach ($trends as $key) {
            array_push($trend, $key->number);
        }
        $types = DB::table('types')->pluck('name');

        foreach ($types as $typ) {
            $dtypes = DB::select("select s.id,COUNT(r.type)AS number
        FROM  issues AS r
        right JOIN departments AS s
        ON r.department= s.id
        and  r.type='$typ' and r.approvement='yes'and r.status='open' and r.category='Internal Audit'
        group by s.id
        order by s.departmentname ASC
       ");

            $typesd = [];
            foreach ($dtypes as $key) {
                array_push($typesd, $key->number);
            }
        }

        $dhigh = DB::select("select s.id,COUNT(r.type)AS number
                       FROM  issues AS r
                       right JOIN departments AS s
                       ON r.department= s.id
                       and  r.type=" . $high_value . " and r.approvement='yes'and r.status='open' and r.category='Internal Audit'
                       group by s.id
                       order by s.departmentname ASC
                      ");
        $dmoderate = DB::select("select s.id,COUNT(r.type)AS number
                        FROM issues AS r
                        right JOIN departments AS s
                        ON r.department= s.id
                        and r.type=" . $medium_value . " and r.approvement='yes'and r.status='open' and r.category='Internal Audit'
                        group by s.id
                        order by s.departmentname ASC
                        ");
        $dlow = DB::select("select s.id,COUNT(r.type)AS number
                        FROM  issues AS r
                        right JOIN departments AS s
                        ON r.department= s.id
                        and  r.type=" . $low_value . " and r.approvement='yes'and r.status='open' and r.category='Internal Audit'
                        group by s.id
                        order by s.departmentname ASC
                    ");
        $highd = [];
        foreach ($dhigh as $key) {
            array_push($highd, $key->number);
        }
        $moderated = [];
        foreach ($dmoderate as $key) {
            array_push($moderated, $key->number);
        }
        $lowd = [];
        foreach ($dlow as $key) {
            array_push($lowd, $key->number);
        }
        $totalhigh = DB::select("select s.id,COUNT(r.type)AS number
                       FROM  issues AS r
                       right JOIN departments AS s
                       ON r.department= s.id and r.category='Internal Audit'
                       and  r.type=" . $high_value . "
                       group by s.id
                       order by s.departmentname ASC
                      ");
        $totalmoderate = DB::select("select s.id,COUNT(r.type)AS number
                        FROM issues AS r
                        right JOIN departments AS s
                        ON r.department= s.id and r.category='Internal Audit'
                        and r.type=" . $medium_value . "
                        group by s.id
                        order by s.departmentname ASC
                        ");
        $totallow = DB::select("select s.id,COUNT(r.type)AS number
                        FROM  issues AS r
                        right JOIN departments AS s
                        ON r.department= s.id and r.category='Internal Audit'
                        and  r.type=" . $low_value . "
                        group by s.id
                        order by s.departmentname ASC
                    ");
        $hightotal = [];
        foreach ($totalhigh as $key) {
            array_push($hightotal, $key->number);
        }
        $moderatetotal = [];
        foreach ($totalmoderate as $key) {
            array_push($moderatetotal, $key->number);
        }
        $lowtotal = [];
        foreach ($totallow as $key) {
            array_push($lowtotal, $key->number);
        }
        $ndue = Issue::where('category', 'Internal Audit')->where('status', 'open')->where('duedate', '<=', $today)->get();
        $nmdue = count($ndue);
        $awaitingactions = Issue::where('category', 'Internal Audit')->wherein('processstage', array('Approved', 'Awaiting actions'))->get();
        $actions = count($awaitingactions);
        $actionsproposed = Issue::where('category', 'Internal Audit')->where('processstage', 'Actions Proposed')->get();
        $proposed = count($actionsproposed);
        $closure = Issue::where('category', 'Internal Audit')->where('status', 'open')->where('impl_status', 'Completed-100%')->get();
        $awaitingclosure = count($closure);
        $depcodes = DB::select("select code from departments order by departmentname ASC");
        $depcode = [];
        foreach ($depcodes as $key) {
            array_push($depcode, $key->code);
        }
        $zerop = DB::select("select s.id,COUNT(r.impl_status)AS number
                       FROM  issues AS r
                       right JOIN departments AS s
                       ON r.department= s.id
                       and  r.impl_status='Not Started-0%' and r.approvement='yes' and r.category='Internal Audit'
                       group by s.id
                       order by s.departmentname ASC
                      ");
        $twop = DB::select("select s.id,COUNT(r.impl_status)AS number
                        FROM issues AS r
                        right JOIN departments AS s
                        ON r.department= s.id
                        and  r.impl_status='Progress-25%' and r.approvement='yes' and r.category='Internal Audit'
                        group by s.id
                        order by s.departmentname ASC
                        ");
        $fivep = DB::select("select s.id,COUNT(r.impl_status)AS number
                        FROM  issues AS r
                        right JOIN departments AS s
                        ON r.department= s.id
                        and  r.impl_status='Partially Completed-50%' and r.approvement='yes' and r.category='Internal Audit'
                        group by s.id
                        order by s.departmentname ASC
                    ");
        $hundredp = DB::select("select s.id,COUNT(r.impl_status)AS number
                        FROM  issues AS r
                        right JOIN departments AS s
                        ON r.department= s.id
                        and  r.impl_status='Completed-100%' and r.approvement='yes' and r.category='Internal Audit'
                        group by s.id
                        order by s.departmentname ASC
                    ");
        $zero = [];
        foreach ($zerop as $key) {
            array_push($zero, $key->number);
        }
        $two = [];
        foreach ($twop as $key) {
            array_push($two, $key->number);
        }
        $five = [];
        foreach ($fivep as $key) {
            array_push($five, $key->number);
        }
        $hundred = [];
        foreach ($hundredp as $key) {
            array_push($hundred, $key->number);
        }
        $h = Issue::where('category', 'Internal Audit')->where('impl_status', 'Completed-100%')->get();
        $hundredcount = count($h);
        $f = Issue::where('category', 'Internal Audit')->where('impl_status', 'Partially Completed-50%')->get();
        $fivecount = count($f);
        $z = Issue::where('category', 'Internal Audit')->where('impl_status', 'Not Started-0%')->where('approvement', 'yes')->get();
        $zerocount = count($z);
        $close = Issue::where('category', 'Internal Audit')->where('status', 'closed')->get();
        $closecount = count($close);

        $closebefore = DB::select("select COUNT(r.duedate)AS number
                        FROM  issues AS r
                        right JOIN departments AS s
                        ON r.department = s.id and r.category='Internal Audit'
                        and r.approvement='yes' and r.status='closed' and r.closed <= r.duedate
                      ");

        $closeafter = DB::select("select COUNT(r.duedate)AS number
                        FROM  issues AS r
                        right JOIN departments AS s
                        ON r.department = s.id and r.category='Internal Audit'
                        and r.approvement='yes' and r.status='closed' and closed > r.duedate
                      ");
        $beforecount = [];
        foreach ($closebefore as $key) {
            array_push($beforecount, $key->number);
        }
        $beforecount = implode(',', $beforecount);
        $aftercount = [];
        foreach ($closeafter as $key) {
            array_push($aftercount, $key->number);
        }
        $aftercount = implode(',', $aftercount);
        $deptbefore = DB::select("select s.id,COUNT(r.duedate)AS number
                        FROM  issues AS r
                        right JOIN departments AS s
                        ON r.department = s.id
                        and r.approvement='yes' and r.status='closed' and r.closed <= r.duedate and r.category='Internal Audit'
                        group by s.id
                        order by s.departmentname ASC
                        ");
        $deptafter = DB::select("select s.id,COUNT(r.status)AS number
                       FROM  issues AS r
                       right JOIN departments AS s
                       ON r.department = s.id
                       and r.approvement='yes'and r.status='closed'and closed > r.duedate and r.category='Internal Audit'
                       group by s.id
                       order by s.departmentname ASC
                      ");
        $before = [];
        foreach ($deptbefore as $key) {
            array_push($before, $key->number);
        }
        $after = [];
        foreach ($deptafter as $key) {
            array_push($after, $key->number);
        }
        $data = Issue::where('category', 'Internal Audit')->where('updated_at', '!=', 'NULL')->orderBy('updated_at', 'ASC')->paginate(10);
        $nmdepts = Issue::where('category', 'Internal Audit')->pluck('department')->unique();
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $currentyear = date('Y');
        $lastyear = $currentyear - 1;
        $twoyear = $lastyear - 1;
        $threeyear = $lastyear - 2;
        $postdate = Issue::where('category', 'Internal Audit')->where('approvement', 'yes')->where('year', $currentyear)->pluck('created_at');
        $monthtrend = [];
        $mm = [];

        foreach ($months as $k) {
            $count = 0;
            foreach ($postdate as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            array_push($monthtrend, $count);
        }
        //current year
        $updatedat = Issue::where('category', 'Internal Audit')->where('status', 'closed')->where('year', $currentyear)->pluck('updated_at');
        $closuretrend = [];
        $mm = [];

        foreach ($months as $k) {
            $count = 0;
            foreach ($updatedat as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            array_push($closuretrend, $count);
        }
        //last year
        $updatedat = Issue::where('category', 'Internal Audit') /*->where('status', 'closed')*/->where('approvement', 'yes')->where('year', $lastyear)->pluck('updated_at');
        $closuretrendlast = [];
        $mm = [];

        foreach ($months as $k) {
            $count = 0;
            foreach ($updatedat as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            array_push($closuretrendlast, $count);
        }

        //two year
        $updatedat = Issue::where('category', 'Internal Audit') /*->where('status', 'closed')*/->where('approvement', 'yes')->where('year', $twoyear)->pluck('updated_at');
        $closuretrendtwo = [];
        $mm = [];

        foreach ($months as $k) {
            $count = 0;
            foreach ($updatedat as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            array_push($closuretrendtwo, $count);
        }

        //three year
        $updatedat = Issue::where('category', 'Internal Audit') /*->where('status', 'closed')*/->where('approvement', 'yes')->where('year', $threeyear)->pluck('updated_at');
        $closuretrendthree = [];
        $mm = [];

        foreach ($months as $k) {
            $count = 0;
            foreach ($updatedat as $key) {
                $mm = date('M', strtotime($key));
                if ($k == $mm) {
                    $count++;
                }
            }
            array_push($closuretrendthree, $count);
        }

        $labels = Type::orderBy('order')->pluck('name')->toArray();
        $labels_seperated_with_slash = implode('/', $labels);
        $labels = implode(',', $labels);

        $label_bg_colors = Type::orderBy('order')->pluck('background_color')->toArray();
        $label_bg_colors = implode(',', $label_bg_colors);

        $models = DB::table('modenadisable')->first();

        return view('user.action.analysis')->with('model', $models)
            ->with('data', $data)->with('types', $types)->with('nmdepts', $nmdepts)
            ->with('opencount', $opencount)->with('closecount', $closecount)
            ->with('typesd', json_encode($typesd, JSON_NUMERIC_CHECK))
            ->with('beforecount', json_encode($beforecount, JSON_NUMERIC_CHECK))
            ->with('aftercount', json_encode($aftercount, JSON_NUMERIC_CHECK))
            ->with('nmdue', $nmdue)->with('actions', $actions)
            ->with('proposed', $proposed)->with('awaitingclosure', $awaitingclosure)
            ->with('names', $names)->with('hundredcount', $hundredcount)->with('fivecount', $fivecount)
            ->with('zerocount', $zerocount)
            ->with('monthtrend', json_encode($monthtrend, JSON_NUMERIC_CHECK))
            ->with('closuretrend', json_encode($closuretrend, JSON_NUMERIC_CHECK))
            ->with('closuretrendlast', json_encode($closuretrendlast, JSON_NUMERIC_CHECK))
            ->with('closuretrendtwo', json_encode($closuretrendtwo, JSON_NUMERIC_CHECK))
            ->with('closuretrendthree', json_encode($closuretrendthree, JSON_NUMERIC_CHECK))
            ->with('dopen', json_encode($dopen, JSON_NUMERIC_CHECK))
            ->with('due', json_encode($due, JSON_NUMERIC_CHECK))
            ->with('dclose', json_encode($dclose, JSON_NUMERIC_CHECK))
            ->with('awaiting', json_encode($awaiting, JSON_NUMERIC_CHECK))
            ->with('process', json_encode($process, JSON_NUMERIC_CHECK))
            ->with('depcode', json_encode($depcode, JSON_NUMERIC_CHECK))
            ->with('trend', json_encode($trend, JSON_NUMERIC_CHECK))
            ->with('year', json_encode($year, JSON_NUMERIC_CHECK))
            ->with('highd', json_encode($highd, JSON_NUMERIC_CHECK))
            ->with('moderated', json_encode($moderated, JSON_NUMERIC_CHECK))
            ->with('lowd', json_encode($lowd, JSON_NUMERIC_CHECK))
            ->with('hightotal', json_encode($hightotal, JSON_NUMERIC_CHECK))
            ->with('moderatetotal', json_encode($moderatetotal, JSON_NUMERIC_CHECK))
            ->with('lowtotal', json_encode($lowtotal, JSON_NUMERIC_CHECK))
            ->with('zero', json_encode($zero, JSON_NUMERIC_CHECK))
            ->with('two', json_encode($two, JSON_NUMERIC_CHECK))
            ->with('five', json_encode($five, JSON_NUMERIC_CHECK))
            ->with('hundred', json_encode($hundred, JSON_NUMERIC_CHECK))
            ->with('before', json_encode($before, JSON_NUMERIC_CHECK))
            ->with('labels', $labels)
            ->with('labels_seperated_with_slash', $labels_seperated_with_slash)
            ->with('label_bg_colors', $label_bg_colors)
            ->with('after', json_encode($after, JSON_NUMERIC_CHECK));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function showHodReviewForm($id)
    {
        $issue = Issue::findOrFail($id);
        //$user = User::orderBy('name')->get();
        $user = User::where('approved',true)->orderBy('name')->get();
        $res = Issue::where('id', $id)->value('responsibility');
        $resp = DB::table('users')->where('email', $res)->value('name');
        $type = Type::all();
        //$rec1 = DB::table('multipleissues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
        $rec1 = DB::table('multipleissues')->where('id', $id)
                                            ->whereNotNull('issue_file')
                                            ->where('issue_file', '!=', '')
                                            ->get();
        //$rec2 = DB::table('multipleactions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
        $rec2 = DB::table('multipleactions')->where('id', $id)
                                            ->whereNotNull('action_file')
                                            ->where('action_file', '!=', '')
                                            ->get();
        $department = Departments::select('departmentname')->find($issue->department);
        $models = DB::table('modenadisable')->First();
        return view('user.action.hodreview')->withUser($user)
            ->withIssue($issue)
            ->withRec1($rec1)
            ->withRec2($rec2)
            ->withType($type)
            ->with('resp', $resp)
            ->with('model', $models)
            ->with('department', $department);
    }

    /**
     * @param $id
     * @param Request $request
     * @return string
     */
    public function submitHodReview($id, Request $request)
    {
        $issue = Issue::findOrFail($id);
        $action = $request->get('action');

        if ($action == 'approve') {
            if ($issue->is_fully_implement) {
                $issue->update([
                    'processstage' => 'Actions Completed',
                    'impl_status' => 'Completed-100%',
                    'hod_reviewed_by_date'=>date('Y-m-d'),
                ]);
            } else {
                $issue->update([
                    'processstage' => 'Actions Proposed',
                    'hod_reviewed_by_date'=>date('Y-m-d'),
                ]);
            }
        } else {
            $issue->update([
                'processstage' => 'HOD Rejected',
                'hod_reviewed_by_date'=>date('Y-m-d'),
            ]);
        }

        \Illuminate\Support\Facades\Session::flash('msg', "Change the process stage successfully");

        return redirect()->route('action.viewlist');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registertablecolumnactive($id)
    {
        $user_id = User::where('email',Auth::user()->email)->value('id');
        ModuleRegisterPageDetail::where('id',$id)
            ->where('module_name', 'audit')
            ->update([
                'is_active'=>true,
                'enabled_by_user'=>$user_id
            ]);

        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registertablecolumndefaultview()
    {
        $defaultPosition = [
            'slno' => 1,
            'id' => 2,
            'title' => 3,
            'type' => 4,
            'department' => 5,
            'responsibility' => 6,
            'due date' => 7,
            'process stage' => 8,
            'action status' => 9,
            'raisedby' => 10,
            'division' => 11,
            'subdivision' => 12,
            'email remainder for action' => 13,
            'added date' => 14,
            'action reviewed by' => 15,
            'action reviewed date' => 16,
            'hOD reviewed by' => 17,
            'hOD reviewed date' => 18,
            'closure reviewed by' => 19,
            'closure reviewed date' => 20,
        ];
        ModuleRegisterPageDetail::where('enabled_by_user','!=',0)
            ->where('module_name', 'audit')
            ->update([
                'is_active'=>false,
                'enabled_by_user'=>false
            ]);
        $headers = ModuleRegisterPageDetail::where('module_name', 'audit')->get();
        foreach ($headers as $header) {
            if (isset($defaultPosition[$header->column_name])) {
                $position = $defaultPosition[$header->column_name];
                if ($position != $header->column_position) {
                    $header->update([
                        'column_position' => $position,
                    ]);
                }
            }
            if ($header->column_name == 'process stage') {
                $header->update([
                    'is_dragdropable' => false,
                ]);
            }
            if ($header->column_name == 'action status') {
                $header->update([
                    'is_dragdropable' => false,
                ]);
            }
        }

        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removecolumn(Request $request)
    {
        ModuleRegisterPageDetail::where('id',$request->remove_column_id)
            ->update([
                'is_active'=>false,
                'enabled_by_user'=>false
            ]);
        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }

    public function updatePosition(Request $request): JsonResponse
    {
        $originalId = $request->get('startIndex');
        $targetId = $request->get('targetIndex');

        \Illuminate\Support\Facades\DB::transaction(function() use ($originalId, $targetId) {
            $originalItem = ModuleRegisterPageDetail::where('module_name', 'audit')
                ->where('id', $originalId)
                ->firstOrFail();
            $targetItem = ModuleRegisterPageDetail::where('module_name', 'audit')
                ->where('id', $targetId)
                ->firstOrFail();

            $originalPosition = $originalItem->column_position;
            $targetPosition = $targetItem->column_position;

            // Determine new position (after target)
            $newPosition = $targetPosition;

            // Update positions of affected items
            if ($originalPosition < $newPosition) {
                // Moving item down: shift items between original and new position up
                ModuleRegisterPageDetail::where('module_name', 'audit')
                    ->where('column_position', '>', $originalPosition)
                    ->where('column_position', '<=', $newPosition)
                    ->decrement('column_position');
            } elseif ($originalPosition > $newPosition) {
                // Moving item up: shift items between new position and original down
                ModuleRegisterPageDetail::where('module_name', 'audit')
                    ->where('column_position', '>=', $newPosition)
                    ->where('column_position', '<', $originalPosition)
                    ->increment('column_position');
            }

            // Update original item's position
            $originalItem->update(['column_position' => $newPosition]);
        });

        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }

    //closed page --- making register table dynamic
    public function closedregistertablecolumnactive($id)
    {
        $user_id = User::where('email',Auth::user()->email)->value('id');
        ModuleClosedPageDetail::where('id',$id)
            ->where('module_name', 'audit')
            ->update([
                'is_active'=>true,
                'enabled_by_user'=>$user_id
            ]);

        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }

    public function closedregistertablecolumndefaultview()
    {
        $defaultPosition = [
            'slno' => 1,
            'id' => 2,
            'title' => 3,
            'type' => 4,
            'department' => 5,
            'responsibility' => 6,
            'due date' => 7,
            'process stage' => 8,
            'action status' => 9,
            'raisedby' => 10,
            'division' => 11,
            'subdivision' => 12,
            'email remainder for action' => 13,
            'added date' => 14,
            'action reviewed by' => 15,
            'action reviewed date' => 16,
            'hOD reviewed by' => 17,
            'hOD reviewed date' => 18,
            'closure reviewed by' => 19,
            'closure reviewed date' => 20,
        ];
        ModuleClosedPageDetail::where('enabled_by_user','!=',0)
            ->where('module_name', 'audit')
            ->update([
                'is_active'=>false,
                'enabled_by_user'=>false
            ]);
        $headers = ModuleClosedPageDetail::where('module_name', 'audit')->get();
        foreach ($headers as $header) {
            if (isset($defaultPosition[$header->column_name])) {
                $position = $defaultPosition[$header->column_name];
                if ($position != $header->column_position) {
                    $header->update([
                        'column_position' => $position,
                    ]);
                }
            }
            if ($header->column_name == 'process stage') {
                $header->update([
                    'is_dragdropable' => false,
                ]);
            }
            if ($header->column_name == 'action status') {
                $header->update([
                    'is_dragdropable' => false,
                ]);
            }
        }

        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }

    public function closedremovecolumn(Request $request)
    {
        ModuleClosedPageDetail::where('id',$request->remove_column_id)
            ->update([
                'is_active'=>false,
                'enabled_by_user'=>false
            ]);
        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }

    public function closedupdatePosition(Request $request): JsonResponse
    {
        $startIndex = $request->get('startIndex');
        $targetIndex = $request->get('targetIndex');

        \Illuminate\Support\Facades\DB::transaction(function() use ($startIndex, $targetIndex) {
            $updateData = [];
            $moduleclosedRegisterPageDetailOriginal = ModuleClosedPageDetail::where('module_name', 'audit')
                ->where('id', $startIndex)
                ->first();
            $moduleclosedRegisterPageDetailTarget = ModuleClosedPageDetail::where('module_name', 'audit')
                ->where('id', $targetIndex)
                ->first();
            $updateData[$moduleclosedRegisterPageDetailOriginal->id] = $moduleclosedRegisterPageDetailTarget->column_position;

            $updateData[$moduleclosedRegisterPageDetailTarget->id] = $moduleclosedRegisterPageDetailOriginal->column_position;

            foreach ($updateData as $id => $value) {
                ModuleClosedPageDetail::where('module_name', 'audit')
                    ->where('id', $id)
                    ->update([
                        'column_position' => $value
                    ]);
            }
        });

        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }

}
