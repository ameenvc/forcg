<?php

namespace App\Http\Controllers;

use App;
use App\Area;
use App\AuditRootCauseCorrectiveActionDetail;
use App\Categories;
use App\Corrective;
use App\CorrectiveInvestigator;
use App\CorrectiveRootCauseCorrectiveActionDetail;
use App\Departments;
use App\Division;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\ModuleRegisterPageDetail;
use App\Http\Requests\Corrective\ApproveRequest;
use App\Http\Requests\Corrective\SubmitChangeInvestigationFormRequest;
use App\Http\Requests\Corrective\SubmitInvestigationFormRequest;
use App\Http\Requests\Corrective\UpdateOpenRequest;
use App\Http\Requests\Corrective\UpdatePercentageRequest;
use App\Issuereviewer;
use App\Admin;
use App\CategoryBasedField;
use App\Changerequestapprove;
use App\Changerequestreviewer;
use App\ClientDetail;
use App\ContextCrs;
use App\CorrectiveDetectionStage;
use App\CorrectiveEscalation;
use App\DetectionStageLabelModel;
use App\Exports\CorrectiveExport;
use App\Mail\CorrectiveAddIssue;
use App\Mail\CorrectiveDisapprove;
use App\Mail\CorrectiveInvestigate;
use App\Mail\CorrectiveIssueApprove;
use App\Mail\CorrectiveIssueAwaitingApproval;
use App\Mail\CorrectiveIssueClosed;
use App\Mail\CorrectiveIssueResubmitNotification;
use App\Mail\CorrectiveReopenIssue;
use App\Mail\CorrectiveResponseNotification;
use App\Mail\CorrectiveResponsibilityChanged;
use App\Mail\CorrectiveResubmitIssue;
use App\Mail\CorrectiveReviewStatusFoundNotOK;
use App\Mail\CorrectiveReviewStatusFoundOK;
use App\Mail\CorrectiveTeamMembers;
use App\Mail\CorrectiveUpdated;
use App\Mail\CorrectiveActionsProposed;
use App\ModenaDisable;
use App\Type;
use App\User;
use App\ViewActivity;
use App\ModuleClosedPageDetail;
use App\ModuleMyOpenPageDetail;
use App\NcrFieldValue;
use App\ProductNcrConfigurationAddedField;
use App\ProcessNcrFieldValue;
use App\ProcessNcrConfigurationAddedField;
use App\Services\CorrectiveWordExport;
use App\UserClosedColumnPreference;
use App\UserColumnPreference;
use App\UserMyOpenColumnPreference;
use App\VendorNcrFieldValue;
use App\VendorNcrConfigurationAddedField;
use App\Vendor;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Session;
use Throwable;

//use Validator;

class CorrectiveController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $selectColumn = [];
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
        $this->selectColumn = [
            'id','issueid','title','category','type','financial_loss','potential_cost_savings','correction_responsibility',
            'frequency','duedate','investigation_date','correction_target_date','source','correction_department','area_id',
            'created_at','action_reviewed_at','closed','processstage','average_percent','is_req_rc_analysis_management_review',
            'team_lead_id','postedby','status','responsibility','impl_status','approvement','detection_stage','division_id',
            'entry_reviewed_by','action_reviewed_by','approvedby','closedby'

        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function addcorrective()
    {
        // $departments = Departments::all();
        $departments = Departments::where('is_active',true)->get();
        $type = Type::all();
        $user = User::where('approved', true)->orderBy('name')->get();
        $categories = Categories::orderBy('categoryname')->get();
        $issue_reviewers = Issuereviewer::pluck('email')->toArray();
        $detectionStages = CorrectiveDetectionStage::all();
        $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
        $models = DB::table('modenadisable')->First();
        return view('user.corrective.addcorrective')->with([
            'departments' => $departments,
            'type' => $type,
            'categories' => $categories,
            'issue_reviewers' => $issue_reviewers,
            'user' => $user,'detectionStages' => $detectionStages,
            'model' => $models,
            'detectionStageLabel' => $detectionStageLabel
        ]);
    }
    /**
     * Display the added fields in the Add Corrective page based on the selected category and also in the edit/view page based on the mode.
     */
    public function getProductNcrFields($category, $mode = 'blank', $id = null)
    {
        $data      = null;
        $ncrValues = null;

        $categoryRecord = Categories::where('id', $category)->first();
        $vendors        =  Vendor::select(['id', 'vendor_name','is_deleted'])
                            ->orderBy('vendor_name', 'asc')
                            ->get();

        // ---- Resolve category type and related config in a single pass ----------------------------
        $typeMap = [
            'is_pnc_cat'     => [
                'model'    => ProductNcrConfigurationAddedField::class,
                'relation' => 'ncrFieldValueRelation',
                'order'    => ['job_order_number','product_serial_number','product_name','product_code','batch','production_line','date_of_manufacture','quantity_affected'],
            ],
            'is_process_cat' => [
                'model'    => ProcessNcrConfigurationAddedField::class,
                'relation' => 'processNcrFieldValueRelation',
                'order'    => ['job_order_number','product_serial_number','process_name','machine_equipment_id','operator_shift','production_line','date_of_process','quantity_affected'],
            ],
            'is_vendor_cat'  => [
                'model'    => VendorNcrConfigurationAddedField::class,
                'relation' => 'vendorNcrFieldValueRelation',
                'order'    => ['job_order_number','product_serial_number','vendor_name','item_serial_number','item_description','purchase_order_number','receiving_report_number','delivery_date','quantity_affected'],
            ],
        ];

        $resolved = null;
        foreach ($typeMap as $key => $config) {
            if ($categoryRecord && $categoryRecord->$key === 'yes') {
                $resolved = $config;
                break;
            }
        }

        if (!$resolved) {
            return $this->noFieldsResponse($mode);
        }

        $ncrModel    = $resolved['model'];
        $ncrRelation = $resolved['relation'];
        $fieldOrder  = $resolved['order'];

        // Vendor detail page has a different field order
        if ($ncrModel === VendorNcrConfigurationAddedField::class
            && in_array($mode, ['new-detail-page', 'pdf-detail-page'])) {
            $fieldOrder = ['job_order_number','product_serial_number','vendor_name','purchase_order_number','receiving_report_number','delivery_date','quantity_affected','item_serial_number','item_description'];
        }

        $productNcrFields           = $ncrModel::where('category_id', $category)->orderBy('field_id')->get();
        $mandatoryProductFieldArray = [];
        $fieldTypeArray             = [];
        $ncrValues                  = collect();

        // --- Existing record -----------------------------------
        if ($id) {
            $data = Corrective::with([
                'correctionDept', 'cat', 'typ',
                'ncrFieldValueRelation'        => function ($q) { $q->withTrashed(); },
                'vendorNcrFieldValueRelation'  => function ($q) { $q->withTrashed(); },
                'processNcrFieldValueRelation' => function ($q) { $q->withTrashed(); },
            ])->find($id);

            // Category changed — blank slate
            if ($data && $data->category != $category) {
                $productNcrConfigQuery      = $ncrModel::where('category_id', $category)->get();
                $mandatoryProductFieldArray = $productNcrConfigQuery->pluck('is_mandatory', 'id')->toArray();
                $fieldTypeArray             = $productNcrConfigQuery->pluck('field_type', 'id')->toArray();

            } else {
                // Same category — merge existing values with missing fields
                $ncrValues             = $data->$ncrRelation ? $data->$ncrRelation->keyBy('field_id') : collect();
                $existingNcrFieldNames = $data->$ncrRelation ? $data->$ncrRelation->pluck('field_name')->toArray() : [];

                $productNcrConfigQuery      = $ncrModel::withTrashed()->where('category_id', $category)->get();
                $mandatoryProductFieldArray = $productNcrConfigQuery->pluck('is_mandatory', 'id')->toArray();
                $fieldTypeArray             = $productNcrConfigQuery->pluck('field_type', 'id')->toArray();

                // Only query for truly missing fields
                $missingFields    = array_diff(
                    array_unique($productNcrConfigQuery->pluck('field_name')->toArray()),
                    $existingNcrFieldNames
                );

                $productNcrFields = !empty($missingFields)
                    ? $ncrModel::whereIn('field_name', $missingFields)->where('category_id', $category)->orderBy('field_id')->get()
                    : collect();
            }

            // Push missing fields as blank value objects
            foreach ($productNcrFields as $field) {
                $ncrValues->push((object)[
                    'field_id'      => $field->id,
                    'field_name'    => $field->field_name,
                    'field_type'    => $field->field_type,
                    'display_label' => $field->display_label,
                    'value'         => '',
                ]);
            }

            // Sort once
            $ncrValues = $ncrValues->sortBy(function ($item) use ($fieldOrder) {
                return array_search($item->field_name, $fieldOrder);
            })->values();

            $fields = view('user.corrective.product_ncr_fields', compact(
                'productNcrFields', 'mandatoryProductFieldArray',
                'data', 'mode', 'ncrValues', 'fieldTypeArray', 'vendors'
            ))->render();

            return in_array($mode, ['view-detail', 'new-detail-page', 'pdf-detail-page'])
                ? $fields
                : response()->json([
                    'fields'       => $fields,
                    'fields_exist' => $ncrValues->isNotEmpty(),
                    'message'      => $ncrValues->isNotEmpty()
                        ? 'NCR fields fetched successfully'
                        : 'No NCR fields configured for the selected category',
                ]);
        }

        // -------------- New record ---------------------------------
        $fields = view('user.corrective.product_ncr_fields', compact(
            'productNcrFields', 'mandatoryProductFieldArray',
            'data', 'mode', 'ncrValues', 'fieldTypeArray', 'vendors'
        ))->render();

        return in_array($mode, ['view-detail', 'new-detail-page', 'pdf-detail-page'])
            ? null
            : response()->json([
                'fields'       => $fields,
                'fields_exist' => $productNcrFields->isNotEmpty(),
            ]);
    }

    // Helper Function to return consistent response when no fields are configured for the selected category, based on the mode (view/edit/new)
    private function noFieldsResponse($mode)
    {
        return in_array($mode, ['view-detail', 'new-detail-page', 'pdf-detail-page'])
            ? null
            : response()->json([
                'fields'       => null,
                'fields_exist' => false,
                'message'      => 'No NCR fields configured for the selected category',
            ]);
    }
    //Save the values of the field when PNC Category Selected in Add corrective page.
    private function saveNcrFieldFormData($request,$corrective){
        $category = Categories::where('id',$corrective->category)->first();
        if($category && $category->is_pnc_cat == 'yes'){
            $fields = ProductNcrConfigurationAddedField::where('category_id',$corrective->category)->get();
            $valueModel = NcrFieldValue::class;
        }else if($category && $category->is_process_cat == 'yes'){
            $fields = ProcessNcrConfigurationAddedField::where('category_id',$corrective->category)->get();
            $valueModel = ProcessNcrFieldValue::class;
        }else if($category && $category->is_vendor_cat == 'yes'){
            $fields = VendorNcrConfigurationAddedField::where('category_id',$corrective->category)->get();
            $valueModel = VendorNcrFieldValue::class;
        }else{
            $fields = collect();
            $valueModel = null;
        }
        $now = now();
        if(count($fields)>0){
            $insertData = [];
            foreach ($fields as $field) {
                $insertData[] = [
                    'corrective_id' => $corrective->id,
                    'category_id' => $corrective->category,
                    'field_id' => $field->id,
                    'field_name'=> $field->field_name,
                    'display_label'=> $field->display_label,
                    'value' => $request->input($field->field_name),
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            $valueModel::insert($insertData);
        }
        return;
    }
    private function saveOrUpdateNcrFieldFormData($request,$corrective){
        $category = $request->input('category') ?? $corrective->category;
        $categoryRecord = Categories::where('id',$category)->first();
        if($categoryRecord && $categoryRecord->is_pnc_cat == 'yes'){
            $addedFieldModel = ProductNcrConfigurationAddedField::class;
            $valueModel = NcrFieldValue::class;
            ProcessNcrFieldValue::where('corrective_id',$corrective->id)->delete();
            VendorNcrFieldValue::where('corrective_id',$corrective->id)->delete();
        }else if($categoryRecord && $categoryRecord->is_process_cat == 'yes'){
            $addedFieldModel = ProcessNcrConfigurationAddedField::class;
            $valueModel = ProcessNcrFieldValue::class;
            NcrFieldValue::where('corrective_id',$corrective->id)->delete();
            VendorNcrFieldValue::where('corrective_id',$corrective->id)->delete();
        }else if($categoryRecord && $categoryRecord->is_vendor_cat == 'yes'){
            $addedFieldModel = VendorNcrConfigurationAddedField::class;
            $valueModel = VendorNcrFieldValue::class;
            NcrFieldValue::where('corrective_id',$corrective->id)->delete();
            ProcessNcrFieldValue::where('corrective_id',$corrective->id)->delete();
        }else{
            $fields = collect();
            NcrFieldValue::where('corrective_id',$corrective->id)->delete();
            ProcessNcrFieldValue::where('corrective_id',$corrective->id)->delete();
            VendorNcrFieldValue::where('corrective_id',$corrective->id)->delete();
            return;
        }
        //No category change only data is changed
        if($category==$corrective->category){
            $ncrFields = $valueModel::where('corrective_id',$corrective->id)->where('category_id',$category)->get();
            $ncrFieldsNamesArray = count($ncrFields)>0 ? $ncrFields->pluck('field_id')->toArray() : [];
            $remainingProductFields = $addedFieldModel::where('category_id',$category)->whereNotIn('id',$ncrFieldsNamesArray)->get();
            if(count($ncrFields)>0){
                foreach ($ncrFields as $field) {
                    $field->update([
                        'value' => $request->input($field->field_name)
                    ]);
                }
            }else{
                $valueModel::where('corrective_id',$corrective->id)->delete();
            }
            if(count($remainingProductFields)>0){
                foreach($remainingProductFields as $field){
                    $valueModel::create([
                        'corrective_id' => $corrective->id,
                        'field_id' => $field->id,
                        'category_id'            => $category,
                        'field_name'             => $field->field_name,
                        'display_label'          => $field->display_label,
                        'value'                  => $request->input($field->field_name),
                    ]);
                }
            }
            return;
        }else{
            //Category Changed
            $fields = $addedFieldModel::where('category_id',$category)->get();
            if(count($fields)>0){
                foreach ($fields as $field) {
                    $valueModel::updateOrCreate(
                        [
                            'corrective_id' => $corrective->id,
                            'field_id' => $field->id,
                        ],
                        [
                            'category_id'            => $category,
                            'field_name'             => $field->field_name,
                            'display_label'          => $field->display_label,
                            'value'                  => $request->input($field->field_name),
                        ]
                    );
                }
            }else{
                $valueModel::where('corrective_id',$corrective->id)->delete();
            }
        }

        return;
    }
    public function insertissue(Request $req)
    {
        $issue_reviewers = Issuereviewer::pluck('email')->toArray();
        $responsibility_mandatory_check = in_array(Auth::user()->email, $issue_reviewers) ? 'required' : 'nullable';
        $rules = [
            'title' => 'required',
            'observation' => 'required',
            'lead' => 'required_if:seperate,yes',
            'financial_loss' => 'nullable|string|max:32',
            'potential_cost_savings' => 'nullable|string|max:32',
            'investigation_date' => 'required_if:seperate,yes',
            'responsibility' => [$responsibility_mandatory_check, 'array'],
            'responsibility.*' => [$responsibility_mandatory_check, 'email', 'exists:users,email'],
            'department' => 'required',
            'correction_target_date' => 'required|date',
            'source' => 'nullable',
        ];

        $v = Validator::make($req->all(), $rules, [
            'lead.required_if' => 'The lead field is mandatory, if investigation required',
            'investigation_date.required_if' => 'The investigation date is mandatory, if investigation required',
        ]);
        if ($v->fails()) {
            $v_error_array = [];
            $cnt = 0;
            foreach ($v->errors()->messages() as $e) {
                $v_error_array[$cnt] = '<li style="text-align:left !important;">' . implode(',', $e) . '</li>';
                $cnt++;
            }
            $v_errors_text = implode('', $v_error_array);

            alert()->warning('<h1>Validation errors</h1><br> <ul>' . $v_errors_text . '</ul>')->html()->persistent('Close');

            $models = DB::table('modenadisable')->first();

            return redirect()->back()->withErrors($v)->withInput()->with('model', $models);
        } else {
            $cer = 'NULL';
            $agreedaction = 'NULL';
            $reviewers = DB::table('issuereviewers')->get();
            $representatives = DB::table('representatives')->get();
            $issue_reviewers = Issuereviewer::pluck('email')->toArray();
            $financial_loss = $req->input('financial_loss');
            $potential_cost_savings = $req->input('potential_cost_savings');

            $list = collect([]);
            foreach ($reviewers as $reviewer) {
                $list = $list->push($reviewer->email);
            }
            foreach ($representatives as $representative) {
                $list = $list->push($representative->email);
            }
            $auditors = $list->unique();
            if (in_array(Auth::user()->email, $issue_reviewers)) {
                $processstage = "Awaiting actions";
                $approvement = "yes";
                $status = "open";
                $approvedby = Auth::user()->employeeid;
            } else {
                $processstage = "Entry Under Review";
                $approvement = "no";
                $status = "NULL";
                $approvedby = 'NULL';
            }

            $category_id = $req->input('category');
            $category = Categories::where('id', $category_id)->value('categoryname');
            $source = $req->input('source');
            $detectionStage = $req->input('detectionStage');
            $type = $req->input('type');
            $department = $req->input('department');
            $dep = DB::table('departments')->where('id', $department)->value('departmentname');
            $division = $req->input('division');
            $area = $req->input('area');
            $title = $req->input('title');
            $observation = $req->input('observation');
            $agreedaction = $req->input('agreedaction');
            $correction_target_date = $req->input('correction_target_date');
            if ($req->frequency) {
                $freq = $req->input('frequency');
                $frequency = implode(',', $freq);
            } else {
                $frequency = null;
            }

            $resp = $req->input('responsibility');
            $resp = $resp ? implode(',', $resp) : null;

            if ($req->notification) {
                $notify = $req->input('notification');
                $notification = implode(',', $notify);
            } else {
                $notification = '';
            }

            $postedby = $req->input('postedby');
            $name = DB::table('users')->where('employeeid', $postedby)->get();
            $postdate = date('d-m-Y h:i:s A', time());
            $year_to_be_inserted = date('Y', time());
            $investigation_date = $req->input('investigation_date');
            $lead = $req->input('lead');
            //Previous NCR Details
            $is_previously_occurred = $req->input('ncr_question');
            $no_of_occurrence = $req->input('no_of_occurrence');
            $previous_ncr_reference = $req->input('previous_ncr_reference');
            $other_previous_ncr_reference = $previous_ncr_reference==0 ? $req->input('other_ncr') : null;
            //-------------------------------Id generation part start-----------------------------------------
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
            $data4 = array(
                // 'count' => $count,
                'issueid' => $issueId, 'is_previously_occurred' => $is_previously_occurred,
                'category' => $category_id, 'no_of_occurrence' => $no_of_occurrence,
                'source' => $source, 'previous_ncr_reference' => $previous_ncr_reference,
                'type' => $type, 'other_previous_ncr_reference' => $other_previous_ncr_reference,
                'correction_department' => $department,
                'department' => 0,
                'division_id' => $division,
                'area_id' => $area,
                'title' => $title,'detection_stage' => $detectionStage,
                // 'file' => $cer,
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
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
                'status' => $status,
                'status' => $status,
                'postdate' => $postdate,
                'created_at' => now(),
                'year' => $year_to_be_inserted,
                'investigation_date' => $investigation_date,
                'team_lead_id' => $req->input('lead') ?? 0,
                'updated_at' => now(),
            );
            $new_corr = Corrective::create($data4);
            $this->saveNcrFieldFormData($req,$new_corr);
            $investigators = $req->input('investigation');
            if ($req->hasFile('issue_file')) {
                $corrective = Corrective::orderBy('id', 'desc')->first();
                $file_array = $req->file('issue_file');
                $array_len = count($file_array);
                $file = [];
                for ($i = 0; $i < $array_len; $i++) {
                    $file_size = $file_array[$i]->getClientOriginalName();
                    $new_file_name = $file_size;
                    $destination_path = public_path('storage/issue_files');
                    $file_array[$i]->move($destination_path, $new_file_name);
                    $file[] = $new_file_name;
                    App\MultiplecorrIssue::insert([
                        'id' => $corrective->id,
                        'issue_file' => $new_file_name
                    ]);
                }
                // $cer = implode(',', $file);
            }
            if ($investigators && count($investigators)) {

                foreach ($investigators as $investigator) {
                    $corrective = Corrective::orderBy('id', 'desc')->first();

                    if ($corrective && $investigator) {
                        $found = CorrectiveInvestigator::where('user_id', $investigator)->where('corrective_id', $corrective->id)->first();
                        if (!$found) {
                            CorrectiveInvestigator::insert([
                                'user_id' => $investigator,
                                'corrective_id' => $corrective->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                        }

                    }
                }
            }

            //inserting values to activity table superadmin code
            $activity = "New Issue is Inserted with Title as " . $title . ", with Status as " . $status . " and with Process Stage as " . $processstage;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //superadmin code end

            // mail all the team members and team leader
            $corrective_inves_emails = EmailNotification::where('module', 'correctiveaction')
                ->where('item', 'Corrective Investigate')
                ->where('is_blocked', false)
                ->first();

            if ($corrective_inves_emails) {
                if ($lead) {
                    $leaderemail = User::where('id', $lead)->value('email');
                    if ($leaderemail != "") {
                        $emaildata = array(
                            'email_content' => $corrective_inves_emails->email_content,
                            'item' => $corrective_inves_emails->item,
                            'issueid' => $issueId,
                            'duedate' => $investigation_date ? date("d/m/Y", strtotime($investigation_date)) : '',
                        );
                        Mail::to($leaderemail)->send(new CorrectiveInvestigate($emaildata));
                    }
                }
                //sending mail for participants and management participants
                $correctiv_inves_userdatas = EmailNotificationsUserDetails::where('notification_id', $corrective_inves_emails->id)->get();
                if (count($correctiv_inves_userdatas) > 0) {
                    $adminaddedusers = $correctiv_inves_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        $mailData = [
                            'email_content' => $corrective_inves_emails->email_content,
                            'item' => $corrective_inves_emails->item,
                            'issueid' => $issueId,
                            'duedate' => $investigation_date ? date("d/m/Y", strtotime($investigation_date)) : '',
                        ];
                        Mail::to($userdatas)->send(new CorrectiveInvestigate($mailData));
                    }
                }
            } else {
            Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Investigator');
            }
            $corrective_member_emails = EmailNotification::where('module', 'correctiveaction')
                ->where('item', 'Corrective Team Members')
                ->where('is_blocked', false)
                ->first();
            if ($corrective_member_emails) {
                $datateam = array(
                    'email_content' => $corrective_member_emails->email_content,
                    'item' => $corrective_member_emails->item,
                    'issueid' => $issueId,
                    'target_date' => $investigation_date ? date("d/m/Y", strtotime($investigation_date)) : '',
                );
                $team_members = getNonDeletedUsersEmail($investigators);
                if (count($team_members) > 0) {
                    //mail to team members
                    Mail::to($team_members)->send(new CorrectiveTeamMembers($datateam));
                }
                $correctiv_member_userdatas = EmailNotificationsUserDetails::where('notification_id', $corrective_member_emails->id)->get();
                if (count($correctiv_member_userdatas) > 0) {
                    $adminaddedusers = $correctiv_member_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new CorrectiveTeamMembers($datateam));
                    }
                }

            } else {
                Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Team Members');
            }
            $d = Corrective::orderBy('id', 'desc')->with('typ')->first();

            $id = $d->id;
            if (in_array(Auth::user()->email, $issue_reviewers)){
                CorrectiveEscalation::create([
                    'corrective_id' => $id,
                ]);
            }
            $postedby = $d->postedby;
            $name = DB::table('users')->where('employeeid', $postedby)->pluck('name');

            if (count($name) <= 0) {
                $name = Auth::user()->name;
            }
            $name = Auth::user()->name;
            $m = 0;
            foreach ($auditors as $a) {
                if (Auth::user()->email == $a) {
                    $corr_respon_emails = EmailNotification::where('module', 'correctiveaction')
                        ->where('item', 'Corrective Response Notification')
                        ->where('is_blocked', false)
                        ->first();
                    if ($corr_respon_emails) {
                        // Sending Mail(Responsibility Notification) To responsibile HOD
                        $mailData = array(
                            'issueid' => $d->issueid,
                            'name' => $name,
                            'email_content' => $corr_respon_emails->email_content,
                            'item' => $corr_respon_emails->item,
                            'type' => $d->typ ? $d->typ->name : '',
                            'department' => $dep,
                            'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                        );
                        if ($d->responsibility) {
                            $responsibilities = getNonDeletedUsersEmail(explode(',', $d->responsibility));
                            Mail::to($responsibilities)->send(new CorrectiveResponseNotification($mailData));
                        }
                        if ($d->correction_responsibility) {
                            $correction_responsibilities = getNonDeletedUsersEmail(explode(',', $d->correction_responsibility));
                            Mail::to($correction_responsibilities)->send(new CorrectiveResponseNotification($mailData));
                        }

                        //sending mail for participants and management participants
                        $corrective_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_respon_emails->id)->get();
                        if (count($corrective_respon_userdatas) > 0) {
                            $adminaddedusers = $corrective_respon_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas) > 0) {
                                Mail::to($userdatas)->send(new CorrectiveResponseNotification($mailData));
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
                            'name' => $name,
                            'email_content' => $corrective_add_emails->email_content,
                            'item' => $corrective_add_emails->item,
                            'type' => $d->typ ? $d->typ->name : '',
                            'department' => $dep,
                            'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                        );
                        if ($notification && $notification != '') {
                            $noti = explode(',', $notification);
                            $noti = getNonDeletedUsersEmail($noti);
                            if (count($noti) > 0) {
                                Mail::to($noti)->send(new CorrectiveAddIssue($mailData));
                            }
                        }

                        //sending mail for participants and management participants
                        $corrective_add_userdatas = EmailNotificationsUserDetails::where('notification_id', $corrective_add_emails->id)->get();
                        if (count($corrective_add_userdatas) > 0) {
                            $adminaddedusers = $corrective_add_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas) > 0) {
                                Mail::to($userdatas)->send(new CorrectiveAddIssue($mailData));
                            }
                        }
                    } else {
                        Session::flash('msg', 'Mail Sending Blocked for Add Corrective Issue');
                    }
                    $m = 1;
                }
            }
            if ($m == 0) {
                $corr_iss_await_emails = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Issue Awaiting Approval')
                    ->where('is_blocked', false)
                    ->first();
                if ($corr_iss_await_emails) {
                    $issue_rev_emails = IssueReviewer::pluck('email')->toArray();
                    $mailData = array(
                        'issueid' => $d->issueid,
                        'name' => $name,
                        'email_content' => $corr_iss_await_emails->email_content,
                        'item' => $corr_iss_await_emails->item,
                        'type' => $d->typ ? $d->typ->name : '',
                        'department' => $dep,
                        'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                    );
                    $issue_rev_emails = getNonDeletedUsersEmail($issue_rev_emails);
                    if (count($issue_rev_emails) > 0) {
                        Mail::to($issue_rev_emails)->send(new CorrectiveIssueAwaitingApproval($mailData));
                    }
                    //sending mail for participants and management participants
                    $corrective_await_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_iss_await_emails->id)->get();
                    if (count($corrective_await_userdatas) > 0) {
                        $adminaddedusers = $corrective_await_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveIssueAwaitingApproval($mailData));
                        }
                    }
                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Awaiting Approval');
                }
            }
            Session::flash('msg', "Issue created successfully");
            $models = DB::table('modenadisable')->first();

            return redirect()->back()->with('model', $models);
        }
    }

    public function enableSource(int $id)
    {
        $category = Categories::withTrashed()->findOrFail($id);

        return [
            'data' => $category->enable_source ? 1 : 0,
            'error' => null,
            'enable_detection_stage' => $category->enable_detection_stage
        ];
    }

    public function submitInvestigationForm(SubmitInvestigationFormRequest $request)
    {
        $data = $request->validated();
        $corrective = Corrective::where('id', $data['corrective_id'])->firstOrFail();

        $processstage = 'RC Updated- Correction Awaiting';

        if ($corrective->correction_proposed) {
            $processstage = 'Actions Proposed- Review Awaiting';
        }
        Corrective::where('id', $data['corrective_id'])->update([
            'processstage' => $processstage,
            'occurrence' => $data['occurrence'],
            'rootcause_updated_by_investigation_team' => 1,
            'system_related_problems' => $data['system_related_problems'],
            'recent_changes' => $data['recent_changes'],
            'follow_procedures' => $data['follow_procedures'],
            'contributing_factors' => $data['contributing_factors'] ? implode(',', $data['contributing_factors']) : null,
            'external_factors' => $data['external_factors'] ? implode(',', $data['external_factors']) : null,
            'issue_foreseeable' => $data['issue_foreseeable'],
            'prevent_reason' => $data['prevent_reason'],
            'rootcause' => implode('$', $data['rootcause']),
            'RCs_added_by_investigation_team' => implode('$', $data['rootcause']),
            'correctiveaction' => implode('$', $data['correctiveaction']),
            'department' => implode(',', $data['department']),
            'responsibility' => implode(',', $data['responsibility']),
            'target_date' => implode(',', $data['target_date']),
            'contributing_factors_details' => $data['factors_details'],
            'external_factors_details' => $data['influence_details'],
            'is_team_changed' => false,
            'updated_at' => now(),
        ]);
        $removedIssueFiles = json_decode($request->input('removedIssueFilenames'),true);
        $removedActionFiles = json_decode($request->input('removedActionFilenames'),true);
        if(count($removedIssueFiles)>0){
            $issueFiles = Corrective::where('id',$data['corrective_id'])->value('file');
            if($issueFiles && $issueFiles!="NULL"){
                $issueFilesArray = explode(',',$issueFiles);
                $remainFilesArray = array_diff($issueFilesArray, $removedIssueFiles);
                $remainFiles = implode(',', $remainFilesArray);
                Corrective::where('id',$data['corrective_id'])->update([
                    'file' => $remainFiles ? $remainFiles : NULL,
                    'updated_at' => now()
                ]);
            }
            App\MultiplecorrIssue::where('id', $data['corrective_id'])
                ->whereIn('issue_file', $removedIssueFiles)
                ->delete();
        }
        if(count($removedActionFiles)>0){
            $actionFiles = Corrective::where('id',$data['corrective_id'])->value('action_file');
            if($actionFiles && $actionFiles!="NULL"){
                $actionFilesArray = explode(',',$actionFiles);
                $remainFilesArray = array_diff($actionFilesArray,$removedActionFiles);
                $remainFiles = implode(',', $remainFilesArray);
                Corrective::where('id',$data['corrective_id'])->update([
                    'action_file' => $remainFiles ? $remainFiles : NULL,
                    'updated_at' => now()
                ]);
            }
            App\MultiplecorrAction::where('id', $data['corrective_id'])->whereIn('action_file',$removedActionFiles)->delete();
        }
        if ($request->hasFile('issue_file')) {
            $file_array = $request->file('issue_file');
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/issue_files');
                $file_array[$i]->move($destination_path, $new_file_name);
                App\MultiplecorrIssue::insert([
                    'id' => $data['corrective_id'],
                    'issue_file' => $new_file_name
                ]);
            }
            // $cer = implode(',', $file);
        }
        if ($request->hasFile('action_file')) {
            $file_array = $request->file('action_file');
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/issue_files');
                $file_array[$i]->move($destination_path, $new_file_name);
                App\MultiplecorrAction::insert([
                    'id' => $data['corrective_id'],
                    'action_file' => $new_file_name
                ]);
            }
        }
        $corrective = Corrective::with('postedByCorrective', 'postedByAdmin', 'teamLead')->findOrFail($data['corrective_id']);
        $table = $this->getRootCauseTable($corrective);
        $attachments = $this->getAttachments($corrective);
        $processStage = $this->getProcessStage($corrective);
        if($processstage == "Actions Proposed- Review Awaiting"){
            $correctiveRecord = Corrective::where('id', $data['corrective_id'])->with(['correctionDept','typ'])->first();
            $appUrl = BaseController::addSlashToEndOfURL();
            $emailsNotifi = EmailNotification::where('module', 'correctiveaction')
                ->where('item', 'Actions Proposed')
                ->where('is_blocked', false)
                ->first();
            if ($emailsNotifi) {
                $mailData = array(
                    'issueid' => $correctiveRecord->issueid,
                    'item' => $emailsNotifi->item,
                    'email_content' => $emailsNotifi->email_content,
                    'type' => $correctiveRecord->typ ? $correctiveRecord->typ->name : '',
                    'department' => $correctiveRecord->correctionDept->departmentname,
                    'duedate' => $correctiveRecord->correction_target_date ? date("d/m/Y", strtotime($correctiveRecord->correction_target_date)) : '',
                    'id' => $data['corrective_id'],
                    'app_url' => $appUrl,
                );
                $emails = IssueReviewer::pluck('email')->toArray();
                $emails = getNonDeletedUsersEmail($emails);
                if (count($emails) > 0) {
                    try{
                        Mail::to($emails)->send(new CorrectiveActionsProposed($mailData));
                    }catch(\Exception $e){
                        Log::error('Exception occuar while sending email to issue reviewers: '.$e->getMessage());
                    }
                }
                $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $emailsNotifi->id)->get();
                if (count($userdatas1) > 0) {
                    $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new CorrectiveActionsProposed($mailData));
                        }catch(\Exception $e){
                            Log::error('Exception occuar while sending email to issue reviewers: '.$e->getMessage());
                        }
                    }
                }

            } else {
                Session::flash('msg', 'Mail Sending Blocked for NCR & Corrective Actions Proposed.');
            }
        }
        return [
            'data' => null,
            'error' => null,
            'table' => $table,
            'attachments' => $attachments,
            'processStage' => $processStage,
        ];
    }
    public function submitChangeInvestigationForm(SubmitChangeInvestigationFormRequest $request)
    {
        $data = $request->validated();
        $id = $data['corrective_id'];

        Corrective::where('id', $id)->update([
            'team_lead_id' => $data['lead'],
            'is_team_changed' => true,
            'updated_at' => now(),
        ]);

        $found = CorrectiveInvestigator::where('corrective_id', $id)->first();

        if ($found) {
            CorrectiveInvestigator::where('corrective_id', $id)->delete();
        }

        foreach ($data['team_members'] as $tm) {
            CorrectiveInvestigator::where('corrective_id', $id)->insert([
                'corrective_id' => $id,
                'user_id' => $tm,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return [
            'data' => null,
            'error' => null,
        ];
    }
    // public function viewlist(Request $request,string $type = null)
    // {
    //     $today = date('Y-m-d');
    //     $postedby_image = null;
    //     $responsible_image = null;
    //     //below is superadmin related code
    //     $correctiveaction = DB::table('modpageload')->where('id', 1)->get();
    //     if (sizeof($correctiveaction) == 0) {
    //         DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 1, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0]);
    //     } else {
    //         $correctiveaction = DB::table('modpageload')->where('id', 1)->First();
    //         $correctiveaction->correctiveaction = $correctiveaction->correctiveaction + 1;
    //         DB::table('modpageload')->update(['correctiveaction' => $correctiveaction->correctiveaction]);
    //     }
    //     //superadmin code ends

    //     //------------------------corrissue-------------
    //     $rec1 = DB::table('multiplecorr_issues')->distinct()->get();
    //     $rec2 = DB::table('multiplecorr_actions')->distinct()->get();
    //     $mr = DB::table('representatives')->pluck('email');
    //     $issuereviewers = DB::table('issuereviewers')->pluck('email')->toArray();
    //     $departments = Departments::all();
    //     $types = Type::all();
    //     $relationData = [
    //         'correctionDept', 'division', 'area', 'teamLead',
    //         'postedByCorrective', 'correctiveInvestigators', 'investigators', 'cat',
    //         'approvedByCorrective', 'closedByCorrective', 'actionReviewedByCorrective', 'typ'
    //     ];

    //     if ($type == null) {
    //         $issues1 = Corrective::with($relationData)->whereIn('status', array('open', 'NULL'))
    //         ->where('processstage', 'Entry Under Review')
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //         $issues2 = Corrective::with($relationData)->whereIn('status', array('open', 'NULL'))
    //             ->where('processstage', '!=', 'Entry Under Review')
    //             ->orderBy('created_at', 'desc')
    //             ->get();
    //         $issues = $issues1->merge($issues2);
    //     } else if ($type === 'High' || $type === 'Major NCR') {
    //         $issues = Corrective::with($relationData)
    //             ->where('type', 1)
    //             ->where('approvement', 'yes')
    //             ->where('status', '!=', 'closed')
    //             ->get();
    //     } else if ($type === 'Medium' || $type === 'Moderate' || $type === 'Minor NCR') {
    //         $issues = Corrective::with($relationData)
    //             ->where('type', 2)
    //             ->where('approvement', 'yes')
    //             ->where('status', '!=', 'closed')
    //             ->get();
    //     } else if ($type === 'Low' || $type === 'OFI') {
    //         $issues = Corrective::with($relationData)
    //             ->where('type', 3)
    //             ->where('approvement', 'yes')
    //             ->where('status', '!=', 'closed')
    //             ->get();
    //     } else if ($type == "due") {
    //         $issues = Corrective::with($relationData)
    //             ->where('status', 'open')
    //             ->where('correction_target_date', '<=', $today)
    //             ->get();
    //     } else if ($type == "awaiting") {
    //         $issues = Corrective::with($relationData)
    //             ->wherein('processstage', array('Awaiting actions'))->get();
    //     } else if ($type == "proposed") {
    //         $issues = Corrective::with($relationData)
    //             ->where('processstage', 'Actions Proposed- Review Awaiting')
    //             ->get();
    //     } else if ($type == "closure") {
    //         $issues = Corrective::with($relationData)
    //             ->where('status', 'open')
    //             ->where('impl_status', 'Completed-100%')->get();
    //     } else {
    //         $issues = Corrective::with($relationData)
    //             ->where('id', $type)
    //             ->get();
    //     }
    //     $corr_respon_emails = EmailNotification::where('module', 'correctiveaction')
    //         ->where('item', 'Corrective Response Notification')
    //         ->where('is_blocked', false)
    //         ->first();
    //     $allResponsibilities = Corrective::where('status', 'open')->whereNotNull('correction_responsibility')
    //         ->pluck('correction_responsibility')->toArray();

    //     $emails = collect($allResponsibilities)
    //         ->flatMap(function ($item) {
    //             return array_map('trim', explode(',', $item));
    //         })->unique()->values()->toArray();
    //     $correctionResponsibilities = App\User::whereIn('email',$emails)->get();
    //     foreach ($issues as $dat) {
    //         $dept = $dat->correctionDept ? $dat->correctionDept->departmentname : '';
    //         if ($dat->correction_target_date > $today && $dat->status == 'open') {
    //             $freq = explode(',', $dat->frequency);
    //             $name = $dat->postedByCorrective ? $dat->postedByCorrective->name : '';
    //             $responsibilities = getNonDeletedUsersEmail(explode(',', $dat->responsibility));
    //             $correction_responsibilities = $dat->correction_responsibility ? getNonDeletedUsersEmail(explode(',', $dat->correction_responsibility)) : [];
    //             // Sending Mail(Responsibility Notification) To responsibile HOD
    //             foreach ($freq as $f) {
    //                 if ($f == 'Monthly' && $today == date("Y-m-d", strtotime($dat->Monthlyfreq . ' + 31 days'))) {
    //                     if ($corr_respon_emails) {
    //                         $mailData = array(
    //                             'issueid' => $dat->issueid,
    //                             'email_content' => $corr_respon_emails->email_content,
    //                             'item' => $corr_respon_emails->item,
    //                             'type' => $dat->typ ? $dat->typ->name : '',
    //                             'department' => $dept,
    //                             'duedate' => $dat->correction_target_date ? date("d/m/Y", strtotime($dat->correction_target_date)) : '',
    //                         );
    //                         foreach ($name as $n) {
    //                             $emaildata = array_merge($mailData, ['name' => $n]);
    //                             if (count($responsibilities) > 0) {
    //                                 Mail::to($responsibilities)->send(new CorrectiveResponseNotification($emaildata));
    //                             }
    //                             if (count($correction_responsibilities) > 0) {
    //                                 Mail::to($correction_responsibilities)->send(new CorrectiveResponseNotification($emaildata));
    //                             }
    //                         }
    //                         //sending mail for participants and management participants
    //                         $corrective_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_respon_emails->id)->get();
    //                         if (count($corrective_respon_userdatas) > 0) {
    //                             foreach ($name as $n) {
    //                                 $emaildata = array_merge($mailData, ['name' => $n]);
    //                                 if (count($corrective_respon_userdatas) > 0) {
    //                                     Mail::to($corrective_respon_userdatas)->send(new CorrectiveResponseNotification($emaildata));
    //                                 }
    //                             }
    //                         }
    //                     } else {
    //                         Session::flash('msg', 'Mail Sending Blocked for Corrective Response Notification');
    //                     }
    //                     $Monthlyfreq = date('Y-m-d');
    //                     $data1 = array('Monthlyfreq' => $Monthlyfreq);
    //                     Corrective::where('id', $dat->id)->update($data1);
    //                 } elseif ($f == 'Weekly' && $today == date("Y-m-d", strtotime($dat->Weeklyfreq . ' + 7 days'))) {
    //                     if ($corr_respon_emails) {
    //                         $mailData = array(
    //                             'issueid' => $dat->issueid,
    //                             'email_content' => $corr_respon_emails->email_content,
    //                             'item' => $corr_respon_emails->item,
    //                             'type' => $dat->typ ? $dat->typ->name : '',
    //                             'department' => $dept,
    //                             'duedate' => $dat->correction_target_date ? date("d/m/Y", strtotime($dat->correction_target_date)) : '',
    //                         );
    //                         foreach ($name as $n) {
    //                             $emaildata = array_merge($mailData, ['name' => $n]);
    //                             if (count($responsibilities) > 0) {
    //                                 Mail::to($responsibilities)->send(new CorrectiveResponseNotification($emaildata));
    //                             }
    //                             if (count($correction_responsibilities) > 0) {
    //                                 Mail::to($correction_responsibilities)->send(new CorrectiveResponseNotification($emaildata));
    //                             }

    //                         }
    //                         //sending mail for participants and management participants
    //                         $corrective_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_respon_emails->id)->get();
    //                         if (count($corrective_respon_userdatas) > 0) {
    //                             $adminaddedusers = $corrective_respon_userdatas->pluck('user_id')->toArray();
    //                             $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
    //                             foreach ($name as $n) {
    //                                 $emaildata = array_merge($mailData, ['name' => $n]);
    //                                 if (count($userdatas) > 0) {
    //                                     Mail::to($userdatas)->send(new CorrectiveResponseNotification($emaildata));
    //                                 }
    //                             }
    //                         }
    //                     } else {
    //                         Session::flash('msg', 'Mail Sending Blocked for Corrective Response Notification');
    //                     }
    //                     $Weeklyfreq = date('Y-m-d');
    //                     $data1 = array('Weeklyfreq' => $Weeklyfreq);
    //                     Corrective::where('id', $dat->id)->update($data1);
    //                 } elseif ($f == '2 Days Before' && $today == date("Y-m-d", strtotime($dat->duedate . ' - 2 days')) && $dat->Dayfreq == '0') {

    //                     if ($corr_respon_emails) {
    //                         $mailData = array(
    //                             'issueid' => $dat->issueid,
    //                             'email_content' => $corr_respon_emails->email_content,
    //                             'item' => $corr_respon_emails->item,
    //                             'type' => $dat->typ ? $dat->typ->name : '',
    //                             'department' => $dept,
    //                             'duedate' => $dat->correction_target_date ? date("d/m/Y", strtotime($dat->correction_target_date)) : '',
    //                         );
    //                         foreach ($name as $n) {
    //                             $emaildata = array_merge($mailData, ['name' => $n]);
    //                             if (count($responsibilities) > 0) {
    //                                 Mail::to($responsibilities)->send(new CorrectiveResponseNotification($emaildata));
    //                             }
    //                             if (count($correction_responsibilities) > 0) {
    //                                 Mail::to($correction_responsibilities)->send(new CorrectiveResponseNotification($emaildata));
    //                             }

    //                         }
    //                         //sending mail for participants and management participants
    //                         $corrective_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_respon_emails->id)->get();
    //                         if (count($corrective_respon_userdatas) > 0) {
    //                             $adminaddedusers = $corrective_respon_userdatas->pluck('user_id')->toArray();
    //                             $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
    //                             foreach ($name as $n) {
    //                                 $emaildata = array_merge($mailData, ['name' => $n]);
    //                                 if (count($userdatas) > 0) {
    //                                     Mail::to($userdatas)->send(new CorrectiveResponseNotification($emaildata));
    //                                 }
    //                             }
    //                         }
    //                     } else {
    //                         Session::flash('msg', 'Mail Sending Blocked for Corrective Response Notification');
    //                     }
    //                     $Dayfreq = '1';
    //                     $data1 = array('Dayfreq' => $Dayfreq);
    //                     Corrective::where('id', $dat->id)->update($data1);
    //                 }
    //             }
    //         }
    //     }
    //     $issuedocs = DB::table('multiplecorr_issues')->get();
    //     $actiondocs = DB::table('multiplecorr_actions')->get();
    //     $typenames = Type::pluck('name')->toArray();
    //     $alltypenames = implode(',', $typenames);

    //     $processstages = ['Awaiting actions', 'Action Approved',
    //         'Action Rejected', 'Need More Action', 'Actions Completed',
    //         'Entry Under Review', 'Entry Rejected', 'Actions Proposed- Review Awaiting', 'Correction Proposed- RC Awaiting', 'RC Updated- Correction Awaiting',
    //     ];
    //     $allpstages = implode(',', $processstages);

    //     $yearvalues = Corrective::select(DB::raw('YEAR(correction_target_date) AS correction_year'))
    //         ->where('status', 'open')
    //         ->pluck('correction_year')
    //         ->toArray();

    //     $year_arr = array_values(array_filter((array_unique($yearvalues))));
    //     sort($year_arr);
    //     $years = implode(',', $year_arr);

    //     $addeddateyearvalues = Corrective::select(DB::raw('YEAR(created_at) AS added_year'))
    //         ->where('status', 'open')
    //         ->pluck('added_year')
    //         ->toArray();

    //     $added_date_year_arr = array_values(array_filter((array_unique($addeddateyearvalues))));
    //     sort($added_date_year_arr);
    //     $addeddateyears = implode(',', $added_date_year_arr);

    //     $actionreviewedyearvalues = Corrective::select(DB::raw('YEAR(action_reviewed_at) AS action_reviewed_year'))
    //     ->where('status', 'open')
    //     ->pluck('action_reviewed_year')
    //     ->toArray();

    //     $action_reviwed_date_year_arr = array_values(array_filter((array_unique($actionreviewedyearvalues))));
    //     sort($action_reviwed_date_year_arr);
    //     $action_reviewed_date_years = implode(',', $action_reviwed_date_year_arr);

    //     $closurereviewedyearvalues = Corrective::select(DB::raw('YEAR(closed) AS closed_reviewed_year'))
    //     ->where('status', 'open')
    //     ->pluck('closed_reviewed_year')
    //     ->toArray();

    //     $closed_reviwed_date_year_arr = array_values(array_filter((array_unique($closurereviewedyearvalues))));
    //     sort($closed_reviwed_date_year_arr);
    //     $closed_reviewed_date_years = implode(',', $closed_reviwed_date_year_arr);

    //     $openCorrectives = Corrective::with([
    //         'correctionDept', 'division', 'area',
    //         'postedByCorrective', 'teamLead', 'cat',
    //         'correctionResponsibility'
    //     ])->where('status', 'open')->get();

    //     $dueDateYears = $this->getYearAndMonthToFilter($openCorrectives, 'duedate');
    //     $addDateYears = $this->getYearAndMonthToFilter($openCorrectives, 'created_at');
    //     $actionReviewedDateYears = $this->getYearAndMonthToFilter($openCorrectives, 'action_reviewed_at');
    //     $closureReviewedDateYears = $this->getYearAndMonthToFilter($openCorrectives, 'closed');

    //     $investigtaion_target_dates_issues = Corrective::whereNotNull('investigation_date')
    //                                                     ->where('status', 'open')
    //                                                     ->get();
    //     $investigtaion_target_dates = $this->getYearAndMonthToFilter($investigtaion_target_dates_issues, 'investigation_date');

    //     $correction_target_dates_issues = Corrective::whereNotNull('correction_target_date')
    //                                                 ->where('status', 'open')
    //                                                 ->get();
    //     $correctiontargetDateYears = $this->getYearAndMonthToFilter($correction_target_dates_issues, 'correction_target_date');
    //     $sourceNames = implode(',',array_unique($this->getNameForFilter($openCorrectives, 'source')));
    //     $alldeptnames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'dept_name')));
    //     $alldivisionnames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'division')));
    //     $allsubdivisionnames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'area')));
    //     $allraisedbynames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'raised_name')));
    //     $allteamleadnames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'team_lead')));
    //     $category_names = $this->getNameForFilter($openCorrectives, 'category');
    //     if ($openCorrectives->whereIn('category', ['Legal Requirement','Incident Investigation'])->count()) {
    //         $category_names = array_merge($category_names, ['Legal Requirement', 'Incident Investigation']);
    //     }
    //     $allcategoriesname = implode(',', array_unique($category_names));
    //     $allactionreviewednames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'action_reviewed_by')));
    //     $allclosurereviewednames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'closure_reviewed_by')));
    //     $allusernames  = $correctionResponsibilities ? implode(',',$correctionResponsibilities->pluck('name')->toArray()) : '';
    //     $issue_ids = $openCorrectives->pluck('id')->toArray();
    //     $issue_ids = implode(",", $issue_ids);
    //     $users = User::where('approved', true)->get();
    //     $users_text = "";
    //     if (count($users)) {
    //         foreach ($users as $user) {
    //             $users_text .= "<option value='$user->email' style='font-size:13px;'>$user->name</option>";
    //         }
    //     }
    //     $departments_text = "";
    //     if (count($departments)) {
    //         foreach ($departments as $dep) {
    //             $departments_text .= "<option value='$dep->id' style='font-size:13px;'>$dep->departmentname</option>";
    //         }
    //     }

    //     $auth_email = FacadesAuth::user()->email;
    //     $models = DB::table('modenadisable')->first();

    //     $table_columns_active =  ModuleRegisterPageDetail::where('module_name','corrective')
    //                                      ->where('is_active',true)
    //                                      ->whereNotIn('column_name',['slno','id','title','process stage','action status'])
    //                                     //->whereNotIn('column_name',['slno','id','title','action status'])
    //                                      ->orderBy('column_position', 'asc')
    //                                      ->get();

    //     $table_columns_active_for_js =  ModuleRegisterPageDetail::where('module_name','corrective')
    //         ->where('is_active',true)
    //         ->whereNotIn('column_name',['slno','id','title','action status'])
    //         ->orderBy('column_position', 'asc')
    //         ->get();

    //     $table_columns_names_active =  ModuleRegisterPageDetail::where('module_name','corrective')
    //                                      ->where('is_active',true)
    //                                      ->pluck('column_name')
    //                                      ->toArray();

    //     $table_columns_names_enabled_by_user =  ModuleRegisterPageDetail::where('module_name','corrective')
    //                                                                 ->where('enabled_by_user','!=',0)
    //                                                                 ->get()->count();

    //     if (!$table_columns_names_enabled_by_user) {
    //         $isActiveColumns = ModuleRegisterPageDetail::where('module_name','corrective')
    //             ->whereNotIn('column_name',['slno','id','title','process stage','action status'])
    //             ->where('is_active', true)
    //             ->where('enabled_by_user', 0)
    //             ->get();
    //         $defaultPosition = [
    //             'type' => 4,
    //             'category' => 5,
    //             'department' => 6,
    //             'responsibility' => 7,
    //             'correction target date' => 8,
    //         ];

    //         foreach ($isActiveColumns as $column) {
    //             if (array_key_exists($column->column_name, $defaultPosition) && $column->column_position != $defaultPosition[$column->column_name]) {
    //                 $table_columns_names_enabled_by_user = true;
    //                 break;
    //             }
    //         }
    //     }

    //     $table_columns_non_active =  ModuleRegisterPageDetail::where('module_name','corrective')
    //                                                     ->where('is_active',false)
    //                                                     // ->whereNotIn('column_name',['investigation target date'])
    //                                                     ->orderBy('column_position', 'asc')
    //                                                     ->get();
    //     $responsibilityEmails = explode(',', implode(',', array_unique($issues->pluck('responsibility')->toArray())));
    //     $responsibleUser = App\User::whereIn('email', $responsibilityEmails)->get();
    //     return view('user.corrective.corrissues', compact('types','sourceNames', 'departments', 'issues', 'issuereviewers', 'correctionResponsibilities', 'responsibleUser'))
    //     ->with('issuedocs', $issuedocs)
    //                 ->with('actiondocs', $actiondocs)
    //                 ->with('issue_ids', $issue_ids)
    //                 ->with('auth_email', $auth_email)
    //                 ->withRec1($rec1)
    //                 ->withRec2($rec2)
    //                 ->with('alltypenames', $alltypenames)
    //                 ->with('allpstages', $allpstages)
    //                 ->with('years', $years)
    //                 ->with('alldeptnames', $alldeptnames)
    //                 ->with('alldivisionnames', $alldivisionnames)
    //                 ->with('allsubdivisionnames', $allsubdivisionnames)
    //                 ->with('allraisedbynames', $allraisedbynames)
    //                 ->with('allteamleadnames', $allteamleadnames)
    //                 ->with('table_columns_active',$table_columns_active)
    //                 ->with('table_columns_active_for_js',$table_columns_active_for_js)
    //                 ->with('table_columns_non_active',$table_columns_non_active)
    //                 ->with('table_columns_names_active',$table_columns_names_active)
    //                 ->with('table_columns_names_enabled_by_user',$table_columns_names_enabled_by_user)
    //                 ->with('allcategoriesname', $allcategoriesname)
    //                 ->with('allusernames', $allusernames)
    //                 ->with('users', $users)
    //                 ->with('users_text', $users_text)
    //                 ->with('departments_text', $departments_text)
    //                 ->with('addeddateyears',$addeddateyears)
    //                 ->with('allactionreviewednames',$allactionreviewednames)
    //                 ->with('action_reviewed_date_years',$action_reviewed_date_years)
    //                 ->with('allclosurereviewednames',$allclosurereviewednames)
    //                 ->with('closed_reviewed_date_years',$closed_reviewed_date_years)
    //                 ->with('dueDateYears', $dueDateYears)
    //                 ->with('addDateYears', $addDateYears)
    //                 ->with('actionReviewedDateYears',$actionReviewedDateYears)
    //                 ->with('closureReviewedDateYears',$closureReviewedDateYears)
    //                 ->with('investigtaion_target_dates',$investigtaion_target_dates)
    //                 ->with('correctiontargetDateYears',$correctiontargetDateYears)
    //                 ->with('model', $models)
    //                 ->withMr($mr);
    // }
    /**
     * @New NCR - CA page
     * OPEN CA
     * MY OPEN CA
     * CLOSED CA
    */
    public function ncrCARegister($type = null){
        $model = ModenaDisable::first();
        $today = date('Y-m-d');
        $typeForExport = $type;
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
        // ----------------------------- Corrective Configations-----------------------------
        $de = Auth::user()->email;
        $relationData = [
            'correctionDept', 'division', 'area', 'teamLead','ncrFieldValueRelation','processNcrFieldValueRelation',
            'postedByCorrective', 'correctiveInvestigators', 'investigators', 'cat','vendorNcrFieldValueRelation',
            'approvedByCorrective', 'closedByCorrective', 'actionReviewedByCorrective', 'typ'
        ];
        $base = Corrective::with($relationData)->select($this->selectColumn);
        $typeMap = [
            'High' => 1, 'Major NCR' => 1,
            'Medium' => 2, 'Moderate' => 2, 'Minor NCR' => 2,
            'Low' => 3, 'OFI' => 3,
        ];
        // ── Open issues ─────
        if ($type === null) {
            $entryReviewIssues = (clone $base)
                ->where(function ($q) {
                    $q->where('status', 'open')
                    ->orWhereNull('status')
                    ->orWhere('status', 'NULL');
                })
                ->where('processstage', 'Entry Under Review')
                ->orderBy('created_at', 'desc')
                ->get();

            $remainingIssues = (clone $base)
                ->where(function ($q) {
                    $q->where('status', 'open')
                    ->orWhereNull('status')
                    ->orWhere('status', 'NULL');
                })
                ->where('processstage', '!=', 'Entry Under Review')
                ->orderBy('created_at', 'desc')
                ->get();

            $openissues = $entryReviewIssues->merge($remainingIssues);
        } elseif (isset($typeMap[$type])) {
            $openissues = Corrective::with($relationData)->where('type', $typeMap[$type])
                ->where('approvement', 'yes')
                ->where('status', '!=', 'closed')
                ->get();
        } elseif ($type === 'due') {
            $openissues = Corrective::with($relationData)->where('status', 'open')
                ->where('correction_target_date', '<=', $today)
                ->get();
        } elseif ($type === 'awaiting') {
            $openissues = Corrective::with($relationData)->where('processstage', 'Awaiting actions')->get();

        } elseif ($type === 'proposed') {
            $openissues = Corrective::with($relationData)->where('processstage', 'Actions Proposed- Review Awaiting')->get();

        } elseif ($type === 'closure') {
            $openissues = Corrective::with($relationData)->where('status', 'open')
                ->where('impl_status', 'Completed-100%')
                ->get();
        } else {
            $openissues = Corrective::with($relationData)->where('id', $type)->get();
        }
        // ── My Open issues ────
        $myopenissues = Corrective::with($relationData)
            ->where(function ($q) use ($de) {
                $q->where('responsibility', 'LIKE', '%' . $de . '%')
                ->orWhere('correction_responsibility', 'LIKE', '%' . $de . '%');
            })
            ->whereIn('status', ['open', 'NULL'])
            ->whereIn('processstage', [
                'Awaiting actions', 'Correction Proposed- RC Awaiting',
                'Actions Proposed- Review Awaiting', 'RC Updated- Correction Awaiting',
                'Action Reviewed, OK', 'Action Reviewed, Not OK',
                'Need More Action', 'Actions Completed',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // ── Closed issues ───
        $closedissues = Corrective::with($relationData)
            ->whereIn('status', ['closed', 'closed after due'])
            ->where('status', '!=', 'removed')
            ->orderBy('created_at', 'desc')
            ->get();
        $typenames = Type::pluck('name')->toArray();
        $openIds   = $openissues->pluck('id');
        $myOpenIds = $myopenissues->pluck('id');
        $closedIds = $closedissues->pluck('id');
        //-------------Vendor Details------------------
        $vendorsCollection = Vendor::select(['id', 'vendor_name'])
            ->orderBy('vendor_name', 'asc')
            ->get();

        $vendorsForFilter = [
            'open'   => $this->getVendorFilter($openIds, $vendorsCollection),
            'myopen' => $this->getVendorFilter($myOpenIds, $vendorsCollection),
            'closed' => $this->getVendorFilter($closedIds, $vendorsCollection),
        ];

        //-----------------Job order Number------------------------------------
        $jobOrderFilters = [
            'open' => [
                'product' => $this->getFilterValues(NcrFieldValue::class, 'job_order_number', $openIds),
                'process' => $this->getFilterValues(ProcessNcrFieldValue::class, 'job_order_number', $openIds),
                'vendor'  => $this->getFilterValues(VendorNcrFieldValue::class, 'job_order_number', $openIds),
            ],
            'myopen' => [
                'product' => $this->getFilterValues(NcrFieldValue::class, 'job_order_number', $myOpenIds),
                'process' => $this->getFilterValues(ProcessNcrFieldValue::class, 'job_order_number', $myOpenIds),
                'vendor'  => $this->getFilterValues(VendorNcrFieldValue::class, 'job_order_number', $myOpenIds),
            ],
            'closed' => [
                'product' => $this->getFilterValues(NcrFieldValue::class, 'job_order_number', $closedIds),
                'process' => $this->getFilterValues(ProcessNcrFieldValue::class, 'job_order_number', $closedIds),
                'vendor'  => $this->getFilterValues(VendorNcrFieldValue::class, 'job_order_number', $closedIds),
            ]
        ];

        //-----------------Product serial Number------------------------------------
        $productSerialFilters = [
            'open' => [
                'product' => $this->getFilterValues(NcrFieldValue::class, 'product_serial_number', $openIds),
                'process' => $this->getFilterValues(ProcessNcrFieldValue::class, 'product_serial_number', $openIds),
                'vendor'  => $this->getFilterValues(VendorNcrFieldValue::class, 'product_serial_number', $openIds),
            ],
            'myopen' => [
                'product' => $this->getFilterValues(NcrFieldValue::class, 'product_serial_number', $myOpenIds),
                'process' => $this->getFilterValues(ProcessNcrFieldValue::class, 'product_serial_number', $myOpenIds),
                'vendor'  => $this->getFilterValues(VendorNcrFieldValue::class, 'product_serial_number', $myOpenIds),
            ],
            'closed' => [
                'product' => $this->getFilterValues(NcrFieldValue::class, 'product_serial_number', $closedIds),
                'process' => $this->getFilterValues(ProcessNcrFieldValue::class, 'product_serial_number', $closedIds),
                'vendor'  => $this->getFilterValues(VendorNcrFieldValue::class, 'product_serial_number', $closedIds),
            ]
        ];
        //-----------------Product Name------------------------------------
        $productNameFilters = [
            'open'   => $this->getFilterValues(NcrFieldValue::class, 'product_name', $openIds),
            'myopen' => $this->getFilterValues(NcrFieldValue::class, 'product_name', $myOpenIds),
            'closed' => $this->getFilterValues(NcrFieldValue::class, 'product_name', $closedIds),
        ];

        //-----------------Process Name------------------------------------
        $processNameFilters = [
            'open'   => $this->getFilterValues(ProcessNcrFieldValue::class, 'process_name', $openIds),
            'myopen' => $this->getFilterValues(ProcessNcrFieldValue::class, 'process_name', $myOpenIds),
            'closed' => $this->getFilterValues(ProcessNcrFieldValue::class, 'process_name', $closedIds),
        ];

        //-------------------- COLUMN CONFIG --------------------//
        $productColumnsDetails = $this->getCategoryBasedColumn('product ncr');
        $processColumnsDetails = $this->getCategoryBasedColumn('process ncr');
        $vendorColumnsDetails = $this->getCategoryBasedColumn('vendor ncr');

        //-------------------- FINAL DATA --------------------//
        $commanData = [
            'issuereviewers' => DB::table('issuereviewers')->pluck('email')->toArray(),
            'departments' => Departments::all(),
            'alltypenames' => implode(',', $typenames),
            'productUserPrefs' => [
                'open' => $productColumnsDetails['open']['userPrefs'],
                'my_open' => $productColumnsDetails['my_open']['userPrefs'],
                'closed' => $productColumnsDetails['closed']['userPrefs'],
            ],
            'processUserPrefs' => [
                'open' => $processColumnsDetails['open']['userPrefs'],
                'my_open' => $processColumnsDetails['my_open']['userPrefs'],
                'closed' => $processColumnsDetails['closed']['userPrefs'],
            ],
            'vendorUserPrefs' => [
                'open' => $vendorColumnsDetails['open']['userPrefs'],
                'my_open' => $vendorColumnsDetails['my_open']['userPrefs'],
                'closed' => $vendorColumnsDetails['closed']['userPrefs'],
            ],
            'vendors' => $vendorsCollection->pluck('vendor_name','id')->toArray(),
            'filters' => [
                'vendorsForFilter' => $vendorsForFilter,
                'jobOrderFilters' => $jobOrderFilters,
                'productSerialFilters' => $productSerialFilters,
                'productNameFilters' => $productNameFilters,
                'processNameFilters' => $processNameFilters,
            ]
        ];
        $tableDetails = $this->getConfigurationTableDetails(
            $productColumnsDetails,
            $processColumnsDetails,
            $vendorColumnsDetails
        );
        $open = $this->opencorrectives($tableDetails['open'],$openissues,$commanData);
        $myopen = $this->myopencorrectives($tableDetails['my_open'],$myopenissues,$commanData);
        $closed = $this->closedcorrectives($tableDetails['closed'],$closedissues,$commanData);

        $userId = User::where('email',Auth::user()->email)->value('id');
        $lastSelectedCategory = DB::table('last_selected_category')->where('module_name','corrective')
                                ->where('user_id', $userId)
                                ->value('category_name');
        $userForResponsibility = User::all();

        // fetching alert msg from previous endpoint
        if (isset($msg)) {
            Session::flash('msg', $msg);
        }
        // fetching dangerMsg from previous endpoint
        if (isset($dangerMsg)) {
            Session::flash('dangerMsg', $dangerMsg);
        }
        // fetching warningMsg from previous endpoint
        if (isset($infoMsg)) {
            Session::flash('dangerMsg', $infoMsg);
        }
        // fetching successMsg from previous endpoint
        if (isset($successMsg)) {
            Session::flash('successMsg', $successMsg);
        }

        return view('user.corrective.ncr-ca-page',compact(
            'model','open','myopen','closed','tableDetails','openissues','myopenissues','closedissues','userForResponsibility',
            'productColumnsDetails','processColumnsDetails','vendorColumnsDetails','lastSelectedCategory','typeForExport'
        ));
    }
    private function getCategoryBasedColumn($category)
    {
        $userId = User::where('email',Auth::user()->email)->value('id');

        //  Step 1: Get admin fields
        if ($category == 'product ncr') {
            $adminFields = ProductNcrConfigurationAddedField::select('field_name', 'display_label')->get();
        } elseif ($category == 'process ncr') {
            $adminFields = ProcessNcrConfigurationAddedField::select('field_name', 'display_label')->get();
        } elseif ($category == 'vendor ncr') {
            $adminFields = VendorNcrConfigurationAddedField::select('field_name', 'display_label')->get();
        } else {
            $adminFields = collect([]);
        }

        $adminEnabledColumns = $adminFields->pluck('field_name')->toArray();
        $displayLabels = $adminFields->pluck('display_label', 'field_name')->toArray();

        // Step 2: Base columns
        $columns = CategoryBasedField::where('module_name', 'corrective')
            ->where('category_name', $category)
            ->whereIn('field_name', $adminEnabledColumns)
            ->get();

        //  Common function to process
        $process = function ($model) use ($userId, $category, $columns, $adminEnabledColumns, $displayLabels) {

            $userPrefs = $model::where('user_id', $userId)
                ->where('module_name', 'corrective')
                ->where('category_name', $category)
                ->whereIn('field_name', $adminEnabledColumns)
                ->get();

            $columnsDiff = array_diff(
                $columns->pluck('column_name')->toArray(),
                $userPrefs->pluck('column_name')->toArray()
            );

            $filteredColumns = $columns->whereIn('column_name', $columnsDiff)->values();

            $filteredColumns = $filteredColumns->map(function ($column) use ($displayLabels) {
                $column->display_label = $displayLabels[$column->field_name] ?? $column->field_name;
                return $column;
            })->sortBy('column_position');

            $userPrefs = $userPrefs->map(function ($pref) use ($displayLabels) {
                $pref->display_label = $displayLabels[$pref->field_name] ?? $pref->field_name;
                return $pref;
            })->sortBy('column_position');

            return [
                'columns' => $filteredColumns,
                'userPrefs' => $userPrefs
            ];
        };

        return [
            'open' => $process(UserColumnPreference::class),
            'my_open' => $process(UserMyOpenColumnPreference::class),
            'closed' => $process(UserClosedColumnPreference::class),
        ];
    }
    private function getConfigurationTableDetails(
        $productUserPrefs = null,
        $processUserPrefs = null,
        $vendorUserPrefs = null
    ){
        $productUserPrefs = $productUserPrefs ?? collect();
        $processUserPrefs = $processUserPrefs ?? collect();
        $vendorUserPrefs = $vendorUserPrefs ?? collect();
        $tableConfigs = [
            'open'    => [
                'model'          => ModuleRegisterPageDetail::class,
                'excludeActive'  => ['slno','id','title','process stage','action status'],
                'excludeForJs'   => ['slno','id','title','action status'],
                'defaultPosition'=> ['type'=>4,'category'=>5,'department'=>6,'responsibility'=>7,'correction target date'=>8],
                'productUserPrefs'      => $productUserPrefs['open']['userPrefs'],
                'processUserPrefs'      => $processUserPrefs['open']['userPrefs'],
                'vendorUserPrefs'      => $vendorUserPrefs['open']['userPrefs'],
            ],
            'closed'  => [
                'model'          => ModuleClosedPageDetail::class,
                'excludeActive'  => ['slno','id','title','process stage','action status'],
                'excludeForJs'   => ['slno','id','title','action status'],
                'defaultPosition'=> ['type'=>4,'category'=>5,'department'=>6,'responsibility'=>7,'correction target date'=>8],
                'productUserPrefs'      => $productUserPrefs['closed']['userPrefs'],
                'processUserPrefs'      => $processUserPrefs['closed']['userPrefs'],
                'vendorUserPrefs'      => $vendorUserPrefs['closed']['userPrefs'],
            ],
            'my_open' => [
                'model'          => ModuleMyOpenPageDetail::class,
                'excludeActive'  => ['slno','id','title','process stage','action status'],
                'excludeForJs'   => ['slno','id','title','action status'],
                'defaultPosition'=> ['type'=>4,'category'=>5,'department'=>6,'correction target date'=>7],
                'productUserPrefs'      => $productUserPrefs['my_open']['userPrefs'],
                'processUserPrefs'      => $processUserPrefs['my_open']['userPrefs'],
                'vendorUserPrefs'      => $vendorUserPrefs['my_open']['userPrefs'],
            ],
        ];

        $result = [];

        foreach ($tableConfigs as $key => $config) {
            $model           = $config['model'];
            $excludeActive   = $config['excludeActive'];
            $defaultPosition = $config['defaultPosition'];
            $productUserPrefs = $config['productUserPrefs'] ?? collect();
            $processUserPrefs = $config['processUserPrefs'] ?? collect();
            $vendorUserPrefs = $config['vendorUserPrefs'] ?? collect();

            $mergedUserPrefs = $productUserPrefs
                ->concat($processUserPrefs)
                ->concat($vendorUserPrefs)
                ->unique(function ($item) {
                    return $item->category_name . '_' . $item->field_name;
                })->values();
            $columnsActive = $model::where('module_name', 'corrective')
                ->where('is_active', true)
                ->whereNotIn('column_name', $excludeActive)
                ->orderBy('column_position', 'asc')
                ->get();
            $columnsActive = $columnsActive
                ->concat($mergedUserPrefs)
                ->sortBy('column_position')
                ->values();
            $columnsActiveForJs = $model::where('module_name', 'corrective')
                ->where('is_active', true)
                ->whereNotIn('column_name', $config['excludeForJs'])
                ->orderBy('column_position', 'asc')
                ->get();
            $columnsActiveForJs = $columnsActiveForJs
                ->concat($mergedUserPrefs)
                ->sortBy('column_position')
                ->values();
            $columnsNamesActive = $model::where('module_name', 'corrective')
                ->where('is_active', true)
                ->pluck('column_name')
                ->toArray();

            $enabledByUser = $model::where('module_name', 'corrective')
                ->where('enabled_by_user', '!=', 0)
                ->count();

            if (!$enabledByUser) {
                $isActiveColumns = $model::where('module_name', 'corrective')
                    ->whereNotIn('column_name', $excludeActive)
                    ->where('is_active', true)
                    ->where('enabled_by_user', 0)
                    ->get();

                foreach ($isActiveColumns as $column) {
                    if (array_key_exists($column->column_name, $defaultPosition)
                        && $column->column_position != $defaultPosition[$column->column_name]) {
                        $enabledByUser = true;
                        break;
                    }
                }
            }

            $columnsNonActive = $model::where('module_name', 'corrective')
                ->where('is_active', false)
                ->orderBy('column_position', 'asc')
                ->get();

            $result[$key] = [
                'columns_active'               => $columnsActive,
                'columns_active_for_js'        => $columnsActiveForJs,
                'columns_names_active'         => $columnsNamesActive,
                'columns_names_enabled_by_user'=> $enabledByUser,
                'columns_non_active'           => $columnsNonActive,
            ];
        }

        return $result;
    }
    private function getFilterValues($model, $field, $ids)
    {
        return $model::where('field_name', $field)
            ->whereIn('corrective_id', $ids)
            ->pluck('value')
            ->unique()
            ->filter()
            ->implode(',');
    }
    private function getVendorFilter($ids, $vendorsCollection)
    {
        $vendorIds = VendorNcrFieldValue::where('field_name', 'vendor_name')
            ->whereIn('corrective_id', $ids)
            ->pluck('value')
            ->unique();

        return $vendorsCollection
            ->whereIn('id', $vendorIds)
            ->pluck('vendor_name')
            ->unique()
            ->filter()
            ->implode(',');
    }
    private function opencorrectives($tableDetails,$issues,$commanData)
    {
        //------------------------corrissue-------------
        $departments = $commanData['departments'];
        $allResponsibilities = $issues->whereNotNull('correction_responsibility')
            ->pluck('correction_responsibility')->toArray();
        $emails = collect($allResponsibilities)
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })->unique()->values()->toArray();
        $correctionResponsibilities = User::whereIn('email',$emails)->get();
        $processstages = ['Awaiting actions', 'Action Approved',
            'Action Rejected', 'Need More Action', 'Actions Completed',
            'Entry Under Review', 'Entry Rejected', 'Actions Proposed- Review Awaiting', 'Correction Proposed- RC Awaiting', 'RC Updated- Correction Awaiting',
        ];
        $allpstages = implode(',', $processstages);
        $openCorrectives = Corrective::with([
            'correctionDept', 'division', 'area',
            'postedByCorrective', 'teamLead', 'cat',
            'correctionResponsibility'
        ])->where('status', 'open')->get();

        $dueDateYears = $this->getYearAndMonthToFilter($openCorrectives, 'duedate');
        $addDateYears = $this->getYearAndMonthToFilter($openCorrectives, 'created_at');
        $actionReviewedDateYears = $this->getYearAndMonthToFilter($openCorrectives, 'action_reviewed_at');
        $closureReviewedDateYears = $this->getYearAndMonthToFilter($openCorrectives, 'closed');

        $investigtaion_target_dates = $this->getYearAndMonthToFilter(
            $openCorrectives->whereNotNull('investigation_date'),
            'investigation_date'
        );
        $correctiontargetDateYears = $this->getYearAndMonthToFilter(
            $openCorrectives->whereNotNull('correction_target_date'),
            'correction_target_date'
        );
        $sourceNames = implode(',',array_unique($this->getNameForFilter($openCorrectives, 'source')));
        $alldeptnames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'dept_name')));
        $alldivisionnames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'division')));
        $allsubdivisionnames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'area')));
        $allraisedbynames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'raised_name')));
        $allteamleadnames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'team_lead')));
        $category_names = $this->getNameForFilter($openCorrectives, 'category');
        if ($openCorrectives->whereIn('category', ['Legal Requirement','Incident Investigation'])->count()) {
            $category_names = array_merge($category_names, ['Legal Requirement', 'Incident Investigation']);
        }
        $allcategoriesname = implode(',', array_unique($category_names));
        $allactionreviewednames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'action_reviewed_by')));
        $allclosurereviewednames = implode(',', array_unique($this->getNameForFilter($openCorrectives, 'closure_reviewed_by')));
        $allusernames  = $correctionResponsibilities ? implode(',',$correctionResponsibilities->pluck('name')->toArray()) : '';
        $issue_ids = $openCorrectives->pluck('id')->toArray();
        $issue_ids = implode(",", $issue_ids);
        $users = User::where('approved', true)->get();
        $users_text = "";
        if (count($users)) {
            foreach ($users as $user) {
                $users_text .= "<option value='$user->email' style='font-size:13px;'>$user->name</option>";
            }
        }
        $departments_text = "";
        if (count($departments)) {
            foreach ($departments as $dep) {
                $departments_text .= "<option value='$dep->id' style='font-size:13px;'>$dep->departmentname</option>";
            }
        }
        $auth_email = FacadesAuth::user()->email;
        $responsibilityEmails = explode(',', implode(',', array_unique($issues->pluck('responsibility')->toArray())));
        $responsibleUser = App\User::whereIn('email', $responsibilityEmails)->get();
        $alldetectionstages = implode(',', array_unique($this->getNameForFilter($issues, 'detection_stage')));
        return \view('user.corrective.open', compact(
            'sourceNames', 'departments', 'issues','alldetectionstages',
            'correctionResponsibilities', 'responsibleUser',
            'issue_ids', 'auth_email', 'allpstages',
             'alldeptnames', 'alldivisionnames', 'allsubdivisionnames',
            'allraisedbynames', 'allteamleadnames','tableDetails',
            'allcategoriesname', 'allusernames', 'users', 'users_text',
            'allactionreviewednames','departments_text','allclosurereviewednames', 'dueDateYears', 'addDateYears',
            'actionReviewedDateYears', 'closureReviewedDateYears','commanData',
            'investigtaion_target_dates', 'correctiontargetDateYears'
        ))->with([
            'issuereviewers' => $commanData['issuereviewers'],
            'alltypenames' => $commanData['alltypenames'],
        ])->render();
    }
    private function myopencorrectives($tableDetails,$issues2,$commanData)
    {
        $category_names = $this->getNameForFilter($issues2, 'category');
        if ($issues2->whereIn('category', ['Legal Requirement','Incident Investigation'])->count()) {
            $category_names = array_merge($category_names, ['Legal Requirement', 'Incident Investigation']);
        }
        $allcategoriesname = implode(',', array_unique($category_names));
        $sourceNames = implode(',',array_unique($this->getNameForFilter($issues2, 'source')));
        $alldeptnames = implode(',', array_unique($this->getNameForFilter($issues2, 'dept_name')));
        $alldivisionnames = implode(',', array_unique($this->getNameForFilter($issues2, 'division')));
        $allsubdivisionnames = implode(',', array_unique($this->getNameForFilter($issues2, 'area')));
        $allraisedbynames = implode(',', array_unique($this->getNameForFilter($issues2, 'raised_name')));
        $allteamleadnames = implode(',', array_unique($this->getNameForFilter($issues2, 'team_lead')));
        $dueDateYears = $this->getYearAndMonthToFilter($issues2, 'duedate');
        $addDateYears = $this->getYearAndMonthToFilter($issues2, 'created_at');
        $actionReviewedDateYears = $this->getYearAndMonthToFilter($issues2, 'action_reviewed_at');
        $closureReviewedDateYears = $this->getYearAndMonthToFilter($issues2, 'closed');
        $processstages = ['Awaiting actions', 'Action Approved',
            'Action Rejected', 'Need More Action', 'Actions Completed',
            'Actions Proposed- Review Awaiting', 'Correction Proposed- RC Awaiting', 'RC Updated- Correction Awaiting',
        ];
        $allpstages = implode(',', $processstages);
        $investigtaion_target_dates = $this->getYearAndMonthToFilter(
            $issues2->whereNotNull('investigation_date'),
            'investigation_date'
        );
        $correctiontargetDateYears = $this->getYearAndMonthToFilter(
            $issues2->whereNotNull('correction_target_date'),
            'correction_target_date'
        );
        $allactionreviewednames = implode(',', array_unique($this->getNameForFilter($issues2, 'action_reviewed_by')));
        $allclosurereviewednames = implode(',', array_unique($this->getNameForFilter($issues2, 'closure_reviewed_by')));
        $alldetectionstages = implode(',', array_unique($this->getNameForFilter($issues2, 'detection_stage')));
        return \view('user.corrective.myopen',compact(
            'alldivisionnames','allsubdivisionnames','allraisedbynames','allteamleadnames','alldetectionstages',
            'dueDateYears','investigtaion_target_dates','correctiontargetDateYears','sourceNames','commanData',
            'addDateYears','actionReviewedDateYears','closureReviewedDateYears','allactionreviewednames','allclosurereviewednames',
            ))
            ->with([
                'issues2' => $issues2,
                'issuereviewers' => $commanData['issuereviewers'],
                'alltypenames' => $commanData['alltypenames'],
                'allcategoriesname' => $allcategoriesname,
                'alldeptnames' => $alldeptnames,
                'allpstages' => $allpstages,
                'tableDetails' => $tableDetails,
            ])->render();
    }
    private function closedcorrectives($tableDetails,$closedCorrectives,$commanData)
    {
        $allResponsibilities = $closedCorrectives->whereNotNull('correction_responsibility')
            ->pluck('correction_responsibility')->toArray();

        $emails = collect($allResponsibilities)
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })->unique()->values()->toArray();
        $correctionResponsibilities = User::whereIn('email',$emails)->get();
        $allstatusnames = implode(',',['Closed', 'Closed After Due']);

        $alldivisionnames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'division')));
        $allsubdivisionnames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'area')));
        $allraisedbynames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'raised_name')));
        $allteamleadnames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'team_lead')));
        $allactionreviewednames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'action_reviewed_by')));
        $sourceNames = implode(',',array_unique($this->getNameForFilter($closedCorrectives, 'source')));
        $allclosurereviewednames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'closure_reviewed_by')));
        $alldeptnames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'dept_name')));
        $allusernames = $correctionResponsibilities ? implode(',',$correctionResponsibilities->pluck('name')->toArray()) : '';
        $dueDateYears = $this->getYearAndMonthToFilter($closedCorrectives, 'duedate');
        $category_names = $this->getNameForFilter($closedCorrectives, 'category');
        if ($closedCorrectives->whereIn('category', ['Legal Requirement','Incident Investigation'])->count()) {
            $category_names = array_merge($category_names, ['Legal Requirement', 'Incident Investigation']);
        }
        $allcategoriesname = implode(',', array_unique($category_names));
        $addDateYears = $this->getYearAndMonthToFilter($closedCorrectives, 'created_at');
        $actionReviewedDateYears = $this->getYearAndMonthToFilter($closedCorrectives, 'action_reviewed_at');
        $closureReviewedDateYears = $this->getYearAndMonthToFilter($closedCorrectives, 'closed');
        $investigtaion_target_dates = $this->getYearAndMonthToFilter(
            $closedCorrectives->whereNotNull('investigation_date'),
            'investigation_date'
        );
        $correctiontargetDateYears = $this->getYearAndMonthToFilter(
            $closedCorrectives->whereNotNull('correction_target_date'),
            'correction_target_date'
        );
        $alldetectionstages = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'detection_stage')));
        return \view('user.corrective.closed',
                compact('sourceNames','tableDetails','dueDateYears','investigtaion_target_dates','addDateYears','actionReviewedDateYears',
                'closureReviewedDateYears','correctiontargetDateYears','alldetectionstages','commanData'))
            ->with([
                'issues' => $closedCorrectives,
                'issuereviewers' => $commanData['issuereviewers'],
                'alltypenames' => $commanData['alltypenames'],
                'allcategoriesname' => $allcategoriesname,
                'alldeptnames' => $alldeptnames,
                'allpstages' => $allstatusnames,
                'alldivisionnames' => $alldivisionnames,
                'allsubdivisionnames' => $allsubdivisionnames,
                'allraisedbynames' => $allraisedbynames,
                'allteamleadnames' => $allteamleadnames,
                'allactionreviewednames' => $allactionreviewednames,
                'allclosurereviewednames' => $allclosurereviewednames,
                'allusernames' => $allusernames,
                'correctionResponsibilities' => $correctionResponsibilities
            ])->render();
    }
    // ** Category filter */
    public function categorytablecolumnactive($id)
    {
        $user_id = User::where('email',Auth::user()->email)->value('id');
        $field = CategoryBasedField::where('id',$id)->first();
        UserColumnPreference::create(
            [
                'user_id' => $user_id,
                'module_name' => 'corrective',
                'column_name' => $field->column_name,
                'category_name' => $field->category_name,
                'column_position' => $field->column_position,
                'column_filter' => $field->column_filter,
                'field_name' => $field->field_name,
            ]
        );
        UserMyOpenColumnPreference::create(
            [
                'user_id' => $user_id,
                'module_name' => 'corrective',
                'column_name' => $field->column_name,
                'category_name' => $field->category_name,
                'column_position' => $field->column_position,
                'column_filter' => $field->column_filter,
                'field_name' => $field->field_name,
            ]
        );
        UserClosedColumnPreference::create(
            [
                'user_id' => $user_id,
                'module_name' => 'corrective',
                'column_name' => $field->column_name,
                'category_name' => $field->category_name,
                'column_position' => $field->column_position,
                'column_filter' => $field->column_filter,
                'field_name' => $field->field_name,
            ]
        );

        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }
    public function clearFilter(){
        $userId = User::where('email',Auth::user()->email)->value('id');
        DB::transaction(function () use ($userId) {
            UserColumnPreference::where([
                ['user_id', $userId],
                ['module_name', 'corrective']
            ])->delete();

            UserMyOpenColumnPreference::where([
                ['user_id', $userId],
                ['module_name', 'corrective']
            ])->delete();

            UserClosedColumnPreference::where([
                ['user_id', $userId],
                ['module_name', 'corrective']
            ])->delete();

            DB::table('last_selected_category')->where([
                ['user_id', $userId],
                ['module_name', 'corrective']
            ])->delete();
        });
        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }
    public function lastSelectedCategory(Request $request){
        try {
            $userId = User::where('email',Auth::user()->email)->value('id');
            DB::table('last_selected_category')
                    ->where('user_id', $userId)
                    ->where('module_name', 'corrective')
                    ->updateOrInsert(
                        ['user_id' => $userId, 'module_name' => 'corrective'],
                        ['category_name' => $request->filter_value]
                    );
            return response()->json([
                'status' => 200,
                'data' => null,
            ]);
        } catch (\Exception $e) {
            Log::error('Exception occurred in lastSelectedCategory: '.$e->getMessage());
            return response()->json([
                'status' => 500,
                'data' => null,
                'message' => 'An error occurred while saving category preference'
            ], 500);
        }
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
    private function getmultipleYearAndMonthToFilter($dataList, $column)
    {
        $yearvalues = $dataList->flatMap(function ($item) use ($column) {
            $dates = explode(',', $item->{$column});
            return array_map(function ($date) {
                return $date ? date('Y-m', strtotime($date)) : '';
            }, $dates);
        });
        $yearUnique = $yearvalues->unique()->toArray();
        usort($yearUnique, function ($x, $y) {
            return strtotime($y) <=> strtotime($x);
        });

        return implode(',', $yearUnique);
    }
    private function getNameForFilter($correctives, $field)
    {
        $output = [];

        switch ($field) {
            case 'dept_name':
                $output = $correctives->where('correctionDept.departmentname', '!=', null)
                        ->pluck('correctionDept.departmentname')->toArray();
                break;
            case 'division':
                $output = $correctives->where('division.name', '!=', null)
                        ->pluck('division.name')->toArray();
                break;
            case 'area':
                $output = $correctives->where('area.name', '!=', null)
                        ->pluck('area.name')->toArray();
                break;
            case 'raised_name':
                $output = $correctives->where('postedByCorrective.name', '!=', null)
                        ->pluck('postedByCorrective.name')->toArray();
                break;
            case 'team_lead':
                $output = $correctives->where('teamLead.name', '!=', null)
                        ->pluck('teamLead.name')->toArray();
                break;
            case 'category':
                $output = $correctives->where('cat.categoryname', '!=', null)
                        ->pluck('cat.categoryname')->toArray();
                break;
            case 'responsibility':
                $output = $correctives->where('correctionResponsibility.name', '!=', null)
                        ->pluck('correctionResponsibility.name')->toArray();
                break;
            case 'action_reviewed_by':
                $output = $correctives->where('actionReviewedByCorrective.name', '!=', null)
                        ->pluck('actionReviewedByCorrective.name')->toArray();
                break;
            case 'closure_reviewed_by':
                $output = $correctives->where('closedByCorrective.name', '!=', null)
                        ->pluck('closedByCorrective.name')->toArray();
                break;
            case 'source':
                $output = $correctives->whereNotNull('source')
                        ->pluck('source')->toArray();
                break;
            case 'detection_stage':
                $output = $correctives->whereNotNull('categoryDetectionStage.name')
                        ->pluck('categoryDetectionStage.name')->toArray();
                break;
            default:
                $output = [];
        }

        return $output;
    }

    public function registertablecolumnactive($id)
    {
        $user_id = User::where('email',Auth::user()->email)->value('id');
        ModuleRegisterPageDetail::where('id',$id)
                                        ->update([
                                            'is_active'=>true,
                                            'enabled_by_user'=>$user_id
                                        ]);

        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }
    public function registertablecolumndefaultview()
    {
        ModuleRegisterPageDetail::where('module_name','corrective')->where('enabled_by_user','!=',0)
                                       ->update([
                                            'is_active'=>false,
                                            'enabled_by_user'=>false
                                        ]);

        $default_values = [
            [
                'module_name' => 'corrective',
                'column_name'=>'type',
                'column_position'=>4,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'category',
                'column_position'=>5,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'department',
                'column_position'=>6,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'responsibility',
                'column_position'=>7,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'correction target date',
                'column_position'=>8,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'process stage',
                'column_position'=>9,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action status',
                'column_position'=>10,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'raisedby',
                'column_position'=>11,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'added date',
                'column_position'=>12,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'source',
                'column_position'=>13,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'detection stage',
                'column_position'=>14,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'division',
                'column_position'=>15,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'subdivision',
                'column_position'=>16,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation team lead',
                'column_position'=>17,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation target date',
                'column_position'=>18,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'financial loss',
                'column_position'=>19,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'potential cost saving',
                'column_position'=>20,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'email remainder for action',
                'column_position'=>21,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed by',
                'column_position'=>22,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed date',
                'column_position'=>23,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed by',
                'column_position'=>24,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed date',
                'column_position'=>25,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'due date',
                'column_position'=>26,
            ]
        ];

        foreach ($default_values as $value) {
            ModuleRegisterPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update(['column_position' => $value['column_position']]);
        }
        $default_values = [
            [
                'module_name' => 'corrective',
                'field_name'=>'job_order_number',
                'column_position'=>51,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_serial_number',
                'column_position'=>52,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_name',
                'column_position'=>53,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_code',
                'column_position'=>54,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'batch',
                'column_position'=>55,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'production_line',
                'column_position'=>56,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'date_of_manufacture',
                'column_position'=>57,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'quantity_affected',
                'column_position'=>58,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'job_order_number',
                'column_position'=>59,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_serial_number',
                'column_position'=>60,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'process_name',
                'column_position'=>61,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'machine_equipment_id',
                'column_position'=>62,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'operator_shift',
                'column_position'=>63,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'production_line',
                'column_position'=>64,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'date_of_process',
                'column_position'=>65,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'quantity_affected',
                'column_position'=>66,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'job_order_number',
                'column_position'=>67,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_serial_number',
                'column_position'=>68,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'vendor_name',
                'column_position'=>69,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'item_serial_number',
                'column_position'=>80,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'purchase_order_number',
                'column_position'=>81,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'receiving_report_number',
                'column_position'=>82,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'delivery_date',
                'column_position'=>83,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'quantity_affected',
                'column_position'=>84,
                'category_name' => 'vendor ncr',
            ],
        ];
        $userId = User::where('email',Auth::user()->email)->value('id');
        $count = UserColumnPreference::where('user_id',$userId)->count();
        if($count > 0){
            foreach ($default_values as $value) {
                UserColumnPreference::where('module_name', $value['module_name'])
                    ->where('field_name', $value['field_name'])
                    ->where('category_name', $value['category_name'])
                    ->where('user_id', $userId)
                    ->update(['column_position' => $value['column_position']]);

                UserMyOpenColumnPreference::where('module_name', $value['module_name'])
                    ->where('field_name', $value['field_name'])
                    ->where('category_name', $value['category_name'])
                    ->where('user_id', $userId)
                    ->update(['column_position' => $value['column_position']]);

                UserClosedColumnPreference::where('module_name', $value['module_name'])
                    ->where('field_name', $value['field_name'])
                    ->where('category_name', $value['category_name'])
                    ->where('user_id', $userId)
                    ->update(['column_position' => $value['column_position']]);
            }
        }
        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }
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
    public function categoryremovecolumn(Request $request)
    {
        $removeColumn = UserColumnPreference::where('id',$request->remove_column_id)->first();
        UserMyOpenColumnPreference::where('field_name',$removeColumn->field_name)->delete();
        UserClosedColumnPreference::where('field_name',$removeColumn->field_name)->delete();
        $removeColumn->delete();
        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }
    public function updatePosition(Request $request) : JsonResponse
    {
        $order = $request->order;

        DB::transaction(function () use ($order) {

            foreach ($order as $item) {

                if (!$item['id']) continue;

                if(isset($item['categoryName']) && isset($item['fieldName'])) {
                    UserColumnPreference::where('id', $item['id'])->where('category_name', $item['categoryName'])
                        ->where('field_name', $item['fieldName'])
                        ->update(['column_position' => $item['position']]);
                }else{
                     ModuleRegisterPageDetail::where('id', $item['id'])
                        ->update(['column_position' => $item['position']]);
                }
            }
        });

        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }
    // ** My Open page column configuration */
    public function myopentablecolumnactive($id)
    {
        $user_id = User::where('email',Auth::user()->email)->value('id');
        ModuleMyOpenPageDetail::where('id',$id)->update([
                                                'is_active'=>true,
                                                'enabled_by_user'=>$user_id
                                            ]);

        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }
    public function myopentablecolumndefaultview()
    {
        ModuleMyOpenPageDetail::where('module_name','corrective')->where('enabled_by_user','!=',0)
                                       ->update([
                                                  'is_active'=>false,
                                                  'enabled_by_user'=>false
                                               ]);

        $default_values = [
            [
                'module_name' => 'corrective',
                'column_name'=>'type',
                'column_position'=>4,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'category',
                'column_position'=>5,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'department',
                'column_position'=>6,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'correction target date',
                'column_position'=>7,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'process stage',
                'column_position'=>8,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action status',
                'column_position'=>9,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'raisedby',
                'column_position'=>10,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'added date',
                'column_position'=>11,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'source',
                'column_position'=>12,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'detection stage',
                'column_position'=>13,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'division',
                'column_position'=>14,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'subdivision',
                'column_position'=>15,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation team lead',
                'column_position'=>16,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation target date',
                'column_position'=>17,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'financial loss',
                'column_position'=>18,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'potential cost saving',
                'column_position'=>19,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'email remainder for action',
                'column_position'=>20,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed by',
                'column_position'=>21,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed date',
                'column_position'=>22,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed by',
                'column_position'=>23,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed date',
                'column_position'=>24,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'due date',
                'column_position'=>25,
            ]
        ];

        foreach ($default_values as $value) {
            ModuleMyOpenPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update(['column_position' => $value['column_position']]);
        }
        $default_values = [
            [
                'module_name' => 'corrective',
                'field_name'=>'job_order_number',
                'column_position'=>51,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_serial_number',
                'column_position'=>52,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_name',
                'column_position'=>53,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_code',
                'column_position'=>54,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'batch',
                'column_position'=>55,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'production_line',
                'column_position'=>56,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'date_of_manufacture',
                'column_position'=>57,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'quantity_affected',
                'column_position'=>58,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'job_order_number',
                'column_position'=>59,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_serial_number',
                'column_position'=>60,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'process_name',
                'column_position'=>61,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'machine_equipment_id',
                'column_position'=>62,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'operator_shift',
                'column_position'=>63,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'production_line',
                'column_position'=>64,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'date_of_process',
                'column_position'=>65,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'quantity_affected',
                'column_position'=>66,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'job_order_number',
                'column_position'=>67,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_serial_number',
                'column_position'=>68,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'vendor_name',
                'column_position'=>69,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'item_serial_number',
                'column_position'=>80,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'purchase_order_number',
                'column_position'=>81,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'receiving_report_number',
                'column_position'=>82,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'delivery_date',
                'column_position'=>83,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'quantity_affected',
                'column_position'=>84,
                'category_name' => 'vendor ncr',
            ],
        ];
        $userId = User::where('email',Auth::user()->email)->value('id');
        $count = UserColumnPreference::where('user_id',$userId)->count();
        if($count > 0){
            foreach ($default_values as $value) {
                UserColumnPreference::where('module_name', $value['module_name'])
                    ->where('field_name', $value['field_name'])
                    ->where('category_name', $value['category_name'])
                    ->where('user_id', $userId)
                    ->update(['column_position' => $value['column_position']]);

                UserMyOpenColumnPreference::where('module_name', $value['module_name'])
                    ->where('field_name', $value['field_name'])
                    ->where('category_name', $value['category_name'])
                    ->where('user_id', $userId)
                    ->update(['column_position' => $value['column_position']]);

                UserClosedColumnPreference::where('module_name', $value['module_name'])
                    ->where('field_name', $value['field_name'])
                    ->where('category_name', $value['category_name'])
                    ->where('user_id', $userId)
                    ->update(['column_position' => $value['column_position']]);
            }
        }

        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }
    public function removecolumnmyopen(Request $request)
    {
        ModuleMyOpenPageDetail::where('id',$request->remove_column_id)
                                        ->update([
                                                    'is_active'=>false,
                                                    'enabled_by_user'=>false
                                                ]);
        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }

    public function updatePositionMyOpen(Request $request) : JsonResponse
    {
        $order = $request->order;

        DB::transaction(function () use ($order) {

            foreach ($order as $item) {

                if (!$item['id']) continue;

                if(isset($item['categoryName']) && isset($item['fieldName'])) {
                    UserMyOpenColumnPreference::where('id', $item['id'])->where('category_name', $item['categoryName'])
                        ->where('field_name', $item['fieldName'])
                        ->update(['column_position' => $item['position']]);
                }else{
                     ModuleMyOpenPageDetail::where('id', $item['id'])
                        ->update(['column_position' => $item['position']]);
                }
            }
        });

        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }
    // ** End of My Open page column configuration */

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
    public function correctiveActionAttachment(Request $request, $id)
    {
        $this->saveMultipleFiles($request, $id, 'App\MultiplecorrAction', 'action_file', 'fileToUpload');
        $this->saveMultipleFiles($request, $id, 'App\MultiplecorrIssue', 'issue_file', 'fileToUpload1');
        return response()->json(['data' => null]);
    }
    public function openissue()
    {
        $issuedocs = DB::table('multiplecorr_issues')->get();
        $actiondocs = DB::table('multiplecorr_actions')->get();
        $issues2 = [];
        $issuereviewers = DB::table('issuereviewers')->pluck('email');
        $mr = DB::table('representatives')->pluck('email');
        $de = Auth::user()->email;
        $issues2 = Corrective::where(function ($q) use ($de) {
            return $q->where('responsibility', 'LIKE', '%' . $de . '%')->orWhere('correction_responsibility', 'like', '%' . $de . '%');
        })
            ->whereIn('status', array('open', 'NULL'))
            ->whereIn('processstage', array(
                'Awaiting actions',
                'Correction Proposed- RC Awaiting',
                'Actions Proposed- Review Awaiting',
                'RC Updated- Correction Awaiting',
                'Action Reviewed, OK',
                'Action Reviewed, Not OK',
                'Need More Action',
                'Actions Completed',
            ))
            ->orderBy('created_at', 'desc')
            ->get();
        $typenames = Type::pluck('name')->toArray();
        $alltypenames = implode(',', $typenames);
        $category_ids = Corrective::/*groupBy('category')->*/where('status', 'open')->pluck('category')->toArray();
        $category_names = Categories::whereIn('id', $category_ids)->pluck('categoryname')->toArray();
        if (Corrective::where('status', 'open')->whereIn('category', ['Legal Requirement','Incident Investigation','Management Review'])->exists()) {
            $category_names[] = 'Legal Requirement,Incident Investigation,Management Review';
        }
        $allcategoriesname = implode(',', array_unique($category_names));
        $correctivedepts = Corrective::/*groupBy('department')->*/where('status', 'open')->pluck('department')->toArray();
        $dept_names = Departments::whereIn('id', $correctivedepts)->pluck('departmentname')->toArray();
        $alldeptnames = implode(',', array_unique($dept_names));
        $yearvalues = Corrective::select(DB::raw('YEAR(correction_target_date) AS correction_year'))->where('status', 'open')->pluck('correction_year')->toArray();

        $year_arr = array_values(array_filter((array_unique($yearvalues))));

        sort($year_arr);
        $years = implode(',', $year_arr);

        $processstages = ['Awaiting actions', 'Action Approved',
            'Action Rejected', 'Need More Action', 'Actions Completed',
            'Actions Proposed- Review Awaiting', 'Correction Proposed- RC Awaiting', 'RC Updated- Correction Awaiting',
        ];
        $allpstages = implode(',', $processstages);

        $models = DB::table('modenadisable')->First();

        return view('user.corrective.openissue')->with([
            'issues2' => $issues2,
            'issuereviewers' => $issuereviewers,
            'issuedocs' => $issuedocs,
            'actiondocs' => $actiondocs,
            'alltypenames' => $alltypenames,
            'allcategoriesname' => $allcategoriesname,
            'alldeptnames' => $alldeptnames,
            'allpstages' => $allpstages,
            'years' => $years,
            'mr' => $mr,
            'model' => $models,
        ]);
    }
    public function analysis()
    {
        $today = date('Y-m-d');
        $processes = DB::select("select departmentname from departments order by departmentname ASC");
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->departmentname);
        }

        $deptopen = DB::select("select s.id,COUNT(r.status)AS number
                           FROM  corrective AS r
                           right JOIN departments AS s
                           ON r.department = s.id
                           and r.approvement='yes'and r.status='open'
                           group by s.id
                           order by s.departmentname ASC
                          ");
        $deptdue = DB::select("select s.id,COUNT(r.correction_target_date)AS number
                            FROM  corrective AS r
                            right JOIN departments AS s
                            ON r.department = s.id
                            and r.approvement='yes' and r.status='open' and r.correction_target_date <= '$today'
                            group by s.id
                            order by s.departmentname ASC
                            ");
        $deptclose = DB::select("select s.id,COUNT(r.status)AS number
                           FROM  corrective AS r
                           right JOIN departments AS s
                           ON r.department = s.id
                           and r.approvement='yes'and r.status='closed'
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
                          FROM  corrective AS r
                          right JOIN departments AS s
                          ON r.department = s.id
                          and (r.processstage='Awaiting actions')
                          group by s.id
                          order by s.departmentname ASC
                         ");
        $awaiting = [];
        foreach ($awaitings as $key) {
            array_push($awaiting, $key->number);
        }

        $names = DB::table('types')->pluck('name');
        $implode_names = implode('/', $names->toArray());
        $types = Type::all();
        $open = Corrective::where('status', 'open')->get();
        $opencount = count($open);

        $years = DB::select("select year from years order by id ASC");
        $year = [];
        foreach ($years as $key) {
            array_push($year, $key->year);
        }
        $trends = DB::select("select s.year,COUNT(r.year)AS number
                        FROM  corrective AS r
                        right JOIN years AS s
                        ON r.year= s.year
                        and r.approvement='yes'
                        group by s.year
                        order by s.year ASC
                    ");
        $trend = [];
        foreach ($trends as $key) {
            array_push($trend, $key->number);
        }
        $dhigh = DB::select("select s.id,COUNT(r.type)AS number
                           FROM  corrective AS r
                           right JOIN departments AS s
                           ON r.department= s.id
                           and  r.type=1 and r.approvement='yes'and r.status='open'
                           group by s.id
                           order by s.departmentname ASC
                          ");
        $dmoderate = DB::select("select s.id,COUNT(r.type)AS number
                            FROM corrective AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and r.type=2 and r.approvement='yes'and r.status='open'
                            group by s.id
                            order by s.departmentname ASC
                            ");
        $dlow = DB::select("select s.id,COUNT(r.type)AS number
                            FROM  corrective AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.type=3 and r.approvement='yes'and r.status='open'
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
                           FROM  corrective AS r
                           right JOIN departments AS s
                           ON r.department= s.id
                           and  r.type=1
                           group by s.id
                           order by s.departmentname ASC
                          ");
        $totalmoderate = DB::select("select s.id,COUNT(r.type)AS number
                            FROM corrective AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and r.type=2
                            group by s.id
                            order by s.departmentname ASC
                            ");
        $totallow = DB::select("select s.id,COUNT(r.type)AS number
                            FROM  corrective AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.type=3
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
        $ndue = Corrective::where('status', 'open')->where('correction_target_date', '<=', $today)->get();
        $nmdue = count($ndue);
        $awaitingactions = Corrective::wherein('processstage', array('Awaiting actions'))->get();
        $actions = count($awaitingactions);
        $actionsproposed = Corrective::where('processstage', 'Actions Proposed- Review Awaiting')->get();
        $proposed = count($actionsproposed);
        $closure = Corrective::where('status', 'open')->where('impl_status', 'Completed-100%')->get();
        $awaitingclosure = count($closure);
        $depcodes = DB::select("select code from departments order by departmentname ASC");
        $depcode = [];
        foreach ($depcodes as $key) {
            array_push($depcode, $key->code);
        }
        $zerop = DB::select("select s.id,COUNT(r.impl_status)AS number
                           FROM  corrective AS r
                           right JOIN departments AS s
                           ON r.department= s.id
                           and  r.impl_status='Not Started-0%' and r.approvement='yes'
                           group by s.id
                           order by s.departmentname ASC
                          ");
        $twop = DB::select("select s.id,COUNT(r.impl_status)AS number
                            FROM corrective AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.impl_status='Progress-25%' and r.approvement='yes'
                            group by s.id
                            order by s.departmentname ASC
                            ");
        $fivep = DB::select("select s.id,COUNT(r.impl_status)AS number
                            FROM  corrective AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.impl_status='Partially Completed-50%' and r.approvement='yes'
                            group by s.id
                            order by s.departmentname ASC
                        ");
        $hundredp = DB::select("select s.id,COUNT(r.impl_status)AS number
                            FROM  corrective AS r
                            right JOIN departments AS s
                            ON r.department= s.id
                            and  r.impl_status='Completed-100%' and r.approvement='yes'
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
        $h = Corrective::where('impl_status', 'Completed-100%')->get();
        $hundredcount = count($h);
        $f = Corrective::where('impl_status', 'Partially Completed-50%')->get();
        $fivecount = count($f);
        $z = Corrective::where('impl_status', 'Not Started-0%')->where('approvement', 'yes')->get();
        $zerocount = count($z);
        $close = Corrective::where('status', 'closed')->get();
        $closecount = count($close);

        $closebefore = DB::select("select COUNT(r.correction_target_date)AS number
                            FROM  corrective AS r
                            right JOIN departments AS s
                            ON r.department = s.id
                            and r.approvement='yes' and r.status='closed' and r.closed <= r.correction_target_date
                          ");

        $closeafter = DB::select("select COUNT(r.correction_target_date)AS number
                            FROM  corrective AS r
                            right JOIN departments AS s
                            ON r.department = s.id
                            and r.approvement='yes' and r.status='closed' and closed > r.correction_target_date
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
        $deptbefore = DB::select("select s.id,COUNT(r.correction_target_date)AS number
                            FROM  corrective AS r
                            right JOIN departments AS s
                            ON r.department = s.id
                            and r.approvement='yes' and r.status='closed' and r.closed <= r.correction_target_date
                            group by s.id
                            order by s.departmentname ASC
                            ");
        $deptafter = DB::select("select s.id,COUNT(r.status)AS number
                           FROM  corrective AS r
                           right JOIN departments AS s
                           ON r.department = s.id
                           and r.approvement='yes'and r.status='closed'and closed > r.correction_target_date
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
        $data = Corrective::where('updated_at', '!=', 'NULL')->orderBy('updated_at', 'ASC')->paginate(10);
        $nmdepts = Corrective::pluck('department')->unique();
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $currentyear = date('Y');
        $lastyear = $currentyear - 1;
        $twoyear = $lastyear - 1;
        $threeyear = $lastyear - 2;
        $postdate = Corrective::where('approvement', 'yes')->where('year', $currentyear)->pluck('postdate');
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
        $updatedat = Corrective::where('status', 'closed')->where('year', $currentyear)->pluck('updated_at');
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
        $updatedat = Corrective::where('status', 'closed')->where('year', $lastyear)->pluck('updated_at');
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
        $updatedat = Corrective::where('status', 'closed')->where('year', $twoyear)->pluck('updated_at');
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
        $updatedat = Corrective::where('status', 'closed')->where('year', $threeyear)->pluck('updated_at');
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

        $models = DB::table('modenadisable')->First();
        return view('user.corrective.analysis')->with('model', $models)
            ->with('types', $types)
            ->with('implode_names', $implode_names)
            ->with('data', $data)->with('nmdepts', $nmdepts)
            ->with('opencount', $opencount)->with('closecount', $closecount)
            ->with('beforecount', json_encode($beforecount, JSON_NUMERIC_CHECK))
            ->with('aftercount', json_encode($aftercount, JSON_NUMERIC_CHECK))
            ->with('nmdue', $nmdue)->with('actions', $actions)
            ->with('proposed', $proposed)->with('awaitingclosure', $awaitingclosure)
            ->with('names', $names)->with('hundredcount', $hundredcount)->with('fivecount', $fivecount)
            ->with('zerocount', $zerocount)
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
            ->with('after', json_encode($after, JSON_NUMERIC_CHECK))
            ->with('monthtrend', json_encode($monthtrend, JSON_NUMERIC_CHECK))
            ->with('closuretrend', json_encode($closuretrend, JSON_NUMERIC_CHECK))
            ->with('closuretrendlast', json_encode($closuretrendlast, JSON_NUMERIC_CHECK))
            ->with('closuretrendtwo', json_encode($closuretrendtwo, JSON_NUMERIC_CHECK))
            ->with('closuretrendthree', json_encode($closuretrendthree, JSON_NUMERIC_CHECK));
    }

    public function closedissue()
    {
        $issuedocs = DB::table('multiplecorr_issues')->get();
        $actiondocs = DB::table('multiplecorr_actions')->get();
        $issues1 = [];
        $issuereviewers = DB::table('issuereviewers')->pluck('email')->toArray();
        $mr = DB::table('representatives')->pluck('email');
        $relationData = [
            'correctionDept', 'division', 'area', 'teamLead',
            'postedByCorrective', 'correctiveInvestigators', 'investigators', 'cat',
            'approvedByCorrective', 'closedByCorrective', 'actionReviewedByCorrective', 'typ'
        ];
        $issues1 = Corrective::with($relationData)->whereIn('status',['closed','closed after due'])
            ->where('status', '!=','removed')
            ->orderBy('created_at', 'desc')
            ->get();
        $allResponsibilities = Corrective::whereIn('status',['closed','closed after due'])->whereNotNull('correction_responsibility')
            ->pluck('correction_responsibility')->toArray();

        $emails = collect($allResponsibilities)
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })->unique()->values()->toArray();
        $correctionResponsibilities = App\User::whereIn('email',$emails)->get();
        $typenames = Type::pluck('name')->toArray();
        $alltypenames = implode(',', $typenames);
        $category_ids = Corrective::/*groupBy('category')->*/where('status', 'open')->pluck('category')->toArray();
        $category_names = Categories::whereIn('id', $category_ids)->pluck('categoryname')->toArray();
        // if (Corrective::where('status', 'open')->where('category', 'Legal Requirement')->exists()) {
        //     $category_names[] = 'Legal Requirement';
        // }
        if (Corrective::where('status', 'open')->whereIn('category', ['Legal Requirement','Incident Investigation'])->exists()) {
            $category_names[] = 'Legal Requirement,Incident Investigation';
        }
        $allcategoriesname = implode(',', array_unique($category_names));
        $correctivedepts = Corrective::/*groupBy('department')->*/where('status', 'open')->pluck('department')->toArray();
        $dept_names = Departments::whereIn('id', $correctivedepts)->pluck('departmentname')->toArray();
        $alldeptnames = implode(',', array_unique($dept_names));
        $yearvalues = Corrective::select(DB::raw('YEAR(correction_target_date) AS correction_year'))->where('status', 'open')->pluck('correction_year')->toArray();

        $year_arr = array_values(array_filter((array_unique($yearvalues))));

        sort($year_arr);
        $years = implode(',', $year_arr);

        $allstatusnames = implode(',',['closed', 'closed after due']);

        $models = DB::table('modenadisable')->first();

        $table_columns_active =  ModuleClosedPageDetail::where('module_name','corrective')
                                         ->where('is_active',true)
                                         ->whereNotIn('column_name',['slno','id','title','action status'])
                                         ->orderBy('column_position', 'asc')
                                         ->get();


        $table_columns_names_active =  ModuleClosedPageDetail::where('module_name','corrective')
                                         ->where('is_active',true)
                                         ->pluck('column_name')
                                         ->toArray();

        $table_columns_names_enabled_by_user =  ModuleClosedPageDetail::where('module_name','corrective')
                                                                    ->where('enabled_by_user','!=',0)
                                                                    ->get()->count();

        if (!$table_columns_names_enabled_by_user) {
            $isActiveColumns = ModuleClosedPageDetail::where('module_name','corrective')
                ->whereNotIn('column_name', ['slno','id','title','action status'])
                ->where('is_active', true)
                ->where('enabled_by_user', 0)
                ->get();
            $defaultPosition = [
                'type' => 4,
                'category' => 5,
                'department' => 6,
                'responsibility' => 7,
                'due date' => 8,
            ];

            foreach ($isActiveColumns as $column) {
                if (array_key_exists($column->column_name, $defaultPosition) && $column->column_position != $defaultPosition[$column->column_name]) {
                    $table_columns_names_enabled_by_user = true;
                    break;
                }
            }
        }

        $table_columns_non_active =  ModuleClosedPageDetail::where('module_name','corrective')
                                                        ->where('is_active',false)
                                                        ->orderBy('column_position', 'asc')
                                                        ->get();
        $closedCorrectives = Corrective::with([
            'correctionDept', 'division', 'area',
            'postedByCorrective', 'teamLead', 'cat',
            'correctionResponsibility'
        ])->whereIn('status', ['closed','closed after due'])
        ->where('status', '!=', 'removed')
        ->get();
        $alldivisionnames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'division')));
        $allsubdivisionnames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'area')));
        $allraisedbynames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'raised_name')));
        $allteamleadnames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'team_lead')));
        $allactionreviewednames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'action_reviewed_by')));
        $sourceNames = implode(',',array_unique($this->getNameForFilter($closedCorrectives, 'source')));
        $allclosurereviewednames = implode(',', array_unique($this->getNameForFilter($closedCorrectives, 'closure_reviewed_by')));
        $allusernames = $correctionResponsibilities ? implode(',',$correctionResponsibilities->pluck('name')->toArray()) : '';
        $yearvalues = Corrective::select(DB::raw('YEAR(correction_target_date) AS correction_year'))
            ->whereIn('status', ['closed','closed after due'])
            ->where('status', '!=', 'removed')
            ->pluck('correction_year')
            ->toArray();

        $year_arr = array_values(array_filter((array_unique($yearvalues))));
        sort($year_arr);
        $years = implode(',', $year_arr);

        $addeddateyearvalues = Corrective::select(DB::raw('YEAR(created_at) AS added_year'))
            ->whereIn('status', ['closed','closed after due'])
            ->where('status', '!=', 'removed')
            ->pluck('added_year')
            ->toArray();

        $added_date_year_arr = array_values(array_filter((array_unique($addeddateyearvalues))));
        sort($added_date_year_arr);
        $addeddateyears = implode(',', $added_date_year_arr);

        $actionreviewedyearvalues = Corrective::select(DB::raw('YEAR(action_reviewed_at) AS action_reviewed_year'))
        ->whereIn('status', ['closed','closed after due'])
        ->where('status', '!=', 'removed')
        ->pluck('action_reviewed_year')
        ->toArray();

        $action_reviwed_date_year_arr = array_values(array_filter((array_unique($actionreviewedyearvalues))));
        sort($action_reviwed_date_year_arr);
        $action_reviewed_date_years = implode(',', $action_reviwed_date_year_arr);


        $closurereviewedyearvalues = Corrective::select(DB::raw('YEAR(closed) AS closed_reviewed_year'))
        ->whereIn('status', ['closed','closed after due'])
        ->where('status', '!=', 'removed')
        ->pluck('closed_reviewed_year')
        ->toArray();

        $closed_reviwed_date_year_arr = array_values(array_filter((array_unique($closurereviewedyearvalues))));
        sort($closed_reviwed_date_year_arr);
        $closed_reviewed_date_years = implode(',', $closed_reviwed_date_year_arr);
        return view('user.corrective.closedissue',compact('sourceNames'))->with([
            'issues' => $issues1,
            'mr' => $mr,
            'issuereviewers' => $issuereviewers,
            'issuedocs' => $issuedocs,
            'actiondocs' => $actiondocs,
            'alltypenames' => $alltypenames,
            'allcategoriesname' => $allcategoriesname,
            'alldeptnames' => $alldeptnames,
            'allpstages' => $allstatusnames,
            'years' => $years,
            'model' => $models,
            'table_columns_active' => $table_columns_active,
            'table_columns_names_active' => $table_columns_names_active,
            'table_columns_names_enabled_by_user' => $table_columns_names_enabled_by_user,
            'table_columns_non_active' => $table_columns_non_active,
            'alldivisionnames' => $alldivisionnames,
            'allsubdivisionnames' => $allsubdivisionnames,
            'allraisedbynames' => $allraisedbynames,
            'allteamleadnames' => $allteamleadnames,
            'allactionreviewednames' => $allactionreviewednames,
            'allclosurereviewednames' => $allclosurereviewednames,
            'allusernames' => $allusernames,
            'addeddateyears' => $addeddateyears,
            'action_reviewed_date_years' => $action_reviewed_date_years,
            'closed_reviewed_date_years' => $closed_reviewed_date_years,
            'correctionResponsibilities' => $correctionResponsibilities
        ]);
    }
    public function closedtablecolumnactive($id)
    {
        $user_id = User::where('email',Auth::user()->email)->value('id');
        ModuleClosedPageDetail::where('id',$id)
                                        ->update([
                                                    'is_active'=>true,
                                                    'enabled_by_user'=>$user_id
                                                ]);

        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }
    public function closedtablecolumndefaultview()
    {
        ModuleClosedPageDetail::where('module_name','corrective')->where('enabled_by_user','!=',0)
            ->update([
                'is_active'=>false,
                'enabled_by_user'=>false
            ]);

        $default_values = [
            [
                'module_name' => 'corrective',
                'column_name'=>'type',
                'column_position'=>4,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'category',
                'column_position'=>5,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'department',
                'column_position'=>6,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'responsibility',
                'column_position'=>7,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'correction target date',
                'column_position'=>8,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'process stage',
                'column_position'=>9,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action status',
                'column_position'=>10,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'raisedby',
                'column_position'=>11,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'added date',
                'column_position'=>12,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'source',
                'column_position'=>13,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'detection stage',
                'column_position'=>14,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'division',
                'column_position'=>15,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'subdivision',
                'column_position'=>16,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation team lead',
                'column_position'=>17,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation target date',
                'column_position'=>18,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'financial loss',
                'column_position'=>19,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'potential cost saving',
                'column_position'=>20,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'email remainder for action',
                'column_position'=>21,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed by',
                'column_position'=>22,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed date',
                'column_position'=>23,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed by',
                'column_position'=>24,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed date',
                'column_position'=>25,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'due date',
                'column_position'=>26,
            ]
        ];

        foreach ($default_values as $value) {
            ModuleClosedPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update(['column_position' => $value['column_position']]);
        }
        $default_values = [
            [
                'module_name' => 'corrective',
                'field_name'=>'job_order_number',
                'column_position'=>51,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_serial_number',
                'column_position'=>52,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_name',
                'column_position'=>53,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_code',
                'column_position'=>54,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'batch',
                'column_position'=>55,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'production_line',
                'column_position'=>56,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'date_of_manufacture',
                'column_position'=>57,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'quantity_affected',
                'column_position'=>58,
                'category_name' => 'product ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'job_order_number',
                'column_position'=>59,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_serial_number',
                'column_position'=>60,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'process_name',
                'column_position'=>61,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'machine_equipment_id',
                'column_position'=>62,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'operator_shift',
                'column_position'=>63,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'production_line',
                'column_position'=>64,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'date_of_process',
                'column_position'=>65,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'quantity_affected',
                'column_position'=>66,
                'category_name' => 'process ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'job_order_number',
                'column_position'=>67,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'product_serial_number',
                'column_position'=>68,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'vendor_name',
                'column_position'=>69,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'item_serial_number',
                'column_position'=>80,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'purchase_order_number',
                'column_position'=>81,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'receiving_report_number',
                'column_position'=>82,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'delivery_date',
                'column_position'=>83,
                'category_name' => 'vendor ncr',
            ],
            [
                'module_name' => 'corrective',
                'field_name'=>'quantity_affected',
                'column_position'=>84,
                'category_name' => 'vendor ncr',
            ],
        ];
        $userId = User::where('email',Auth::user()->email)->value('id');
        $count = UserColumnPreference::where('user_id',$userId)->count();
        if($count > 0){
            foreach ($default_values as $value) {
                UserColumnPreference::where('module_name', $value['module_name'])
                    ->where('field_name', $value['field_name'])
                    ->where('category_name', $value['category_name'])
                    ->where('user_id', $userId)
                    ->update(['column_position' => $value['column_position']]);

                UserMyOpenColumnPreference::where('module_name', $value['module_name'])
                    ->where('field_name', $value['field_name'])
                    ->where('category_name', $value['category_name'])
                    ->where('user_id', $userId)
                    ->update(['column_position' => $value['column_position']]);

                UserClosedColumnPreference::where('module_name', $value['module_name'])
                    ->where('field_name', $value['field_name'])
                    ->where('category_name', $value['category_name'])
                    ->where('user_id', $userId)
                    ->update(['column_position' => $value['column_position']]);
            }
        }
        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }
    public function removecolumnclosed(Request $request)
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

    public function updatePositionClosed(Request $request) : JsonResponse
    {
        $order = $request->order;

        DB::transaction(function () use ($order) {

            foreach ($order as $item) {

                if (!$item['id']) continue;

                if(isset($item['categoryName']) && isset($item['fieldName'])) {
                    UserClosedColumnPreference::where('id', $item['id'])->where('category_name', $item['categoryName'])
                        ->where('field_name', $item['fieldName'])
                        ->update(['column_position' => $item['position']]);
                }else{
                     ModuleClosedPageDetail::where('id', $item['id'])
                        ->update(['column_position' => $item['position']]);
                }
            }
        });

        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }
    // propose action
    public function editopen()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = Corrective::with('previousNcrReferenceRelation')->findOrFail($id);
            $correction_responsibility =  explode(',',$data->correction_responsibility);
            if(!in_array(Auth::user()->email, $correction_responsibility) || !in_array($data->processstage,['RC Updated- Correction Awaiting','Awaiting actions'])){
                return redirect()->route('corrective.viewlist')->with('msg', 'You are not authorized to access propose action page.');
            }
            $type = Type::all();

            $responsible_persons = explode(',', $data->responsibility);

            $user = User::where('approved', true)->get();
            $users_text = "";
            if (count($user)) {
                foreach ($user as $use) {
                    $users_text .= "<option value='$use->email' style='font-size:13px;'>$use->name</option>";
                }
            }

            $rec1 = DB::table('multiplecorr_issues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec2 = DB::table('multiplecorr_actions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $models = DB::table('modenadisable')->First();
            //$departments = Departments::all();
            $departments = Departments::where('is_active', true)->get();
            $departments_text = "";
            if (count($departments)) {
                foreach ($departments as $dep) {
                    $departments_text .= "<option value='$dep->id' style='font-size:13px;'>$dep->departmentname</option>";
                }
            }

            $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();

            $responsibility = $data->correction_responsibility;
            $responsibility = explode(',', $responsibility);
            $responsible_name = User::whereIn('email', $responsibility)->pluck('name')->toArray();
            $responsible_name = implode(',', $responsible_name);
            $team_lead_user = User::where('id', $data->team_lead_id)->first();
            $team_leader_name = $team_lead_user ? $team_lead_user->name : null;
            $team_members = CorrectiveInvestigator::where('corrective_id', $id)->get();
            $team_member_array = [];
            $team_member_name_array = [];
            if (count($team_members)) {
                foreach ($team_members as $member) {
                    $team_member_array[] = User::where('id', $member->user_id)->value('email');
                    $team_member_name_array[] = User::where('id', $member->user_id)->value('name');
                }
            }
            $team_member_name = implode(',', $team_member_name_array);
            $issue_reviewers = Issuereviewer::pluck('email')->toArray();
            $rootcorrectives = CorrectiveRootCauseCorrectiveActionDetail::all();
            $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
            $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
            $issuedocs = $data->file && $data->file!="NULL" ? explode(',', $data->file) : [];
            $actiondocs = $data->action_file && $data->action_file !="NULL" ? explode(',', $data->action_file) : [];
            $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
            $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
            $reviewer = Changerequestreviewer::pluck('email')->toArray();
            $approver = Changerequestapprove::pluck('email')->toArray();
            $reviewers = User::whereIn('email',$reviewer)->get()->sortBy('name');
            $approvers = User::whereIn('email',$approver)->get()->sortBy('name');
            $categories = Categories::withTrashed()->whereNull('deleted_at')->orWhere('id', $data->category)->get();
            $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
            return view('user.corrective.editopen',compact('reviewers','approvers','categories','detectionStageLabel'))
                ->withUser($user)
                ->withData($data)
                ->withRec1($rec1)
                ->withType($type)
                ->withRec2($rec2)
                ->with('team_member_array', $team_member_array)
                ->with('team_member_name', $team_member_name)
                ->with('team_leader_name', $team_leader_name)
                ->with('responsible_name', $responsible_name)
                ->with('issue_reviewers', $issue_reviewers)
                ->with('rootcorrectives', $rootcorrectives)
                ->with('responsible_persons', $responsible_persons)
                ->with('rootcorrectives', $rootcorrectives)
                ->with('departments', $departments)
                ->with('departments_text', $departments_text)
                ->with('users_text', $users_text)
                ->with('issuedocs',$issuedocs)
                ->with('actiondocs',$actiondocs)
                ->with('model', $models);
        }
    }

    public function displayrootcorrectiveajax(Request $request)
    {
        $data_id = $request->input('data_id');
        $rootcause = CorrectiveRootCauseCorrectiveActionDetail::where('id', $data_id)->value('root_cause');
        $corrective_action = CorrectiveRootCauseCorrectiveActionDetail::where('id', $data_id)->value('corrective_action');

        return response()->json([
            'status' => 200,
            'rootdata' => $rootcause,
            'correctdata' => $corrective_action,
        ]);

    }
    // submit propose action
    public function updateopen(UpdateOpenRequest $r)
    {
        // $cer = 'NULL';
        $id = $r->input('posted');
        $is_moc_required = $r->input('is_moc_required');
        $changeDescription = $r->input('changeDescription');
        $currentUserDetails = User::where('email',Auth::user()->email)->first();
        $corrective = Corrective::findOrFail($id);
        /**-------------------------------MOC Details: Start------------------------------------------------ */
        if($is_moc_required=='yes'){
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
            $mocTitle = $r->input('mocTitle');
            $reviewAuthority = $r->input('reviewAuthority');
            $approveAuthority = $r->input('approveAuthority');

            $contextData = [
                'created_at' => date('d-m-Y h:i:s A', time()),
                'updated_at' => date('d-m-Y h:i:s A', time()),
                'title' => $mocTitle,
                'initiator' => $currentUserDetails->id,
                'department' => $corrective->correction_department,
                'rev_authority' => $reviewAuthority,
                'appr_authority' => $approveAuthority,
                'postedby' => $currentUserDetails->employeeid,
                'crid' => $issueId,
            ];
            DB::table('context_crs')->insert($contextData);
            $contextId = ContextCrs::where('crid',$issueId)->value('id');
        }else{
            $contextId = null;
        }
        /**-------------------------------Moc Details: End------------------------------------------------ */
        if ($r->hasFile('action_file')) {
            $file_array = $r->file('action_file');
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/issue_files');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
                App\MultiplecorrAction::insert([
                    'id' => $id,
                    'action_file' => $new_file_name
                ]);
            }
            // $cer = implode(',', $file);
        }
        $financial_loss = $r->input('financial_loss');
        $potential_cost_savings = $r->input('potential_cost_savings');
        $correction = $r->input('correction');
        $correction_target_date = $r->input('correction_target_date');
        $source = $r->input('source');
        $action_name = "Actions Proposed- Review Awaiting";
        $data = [
            'financial_loss' => $financial_loss,
            'potential_cost_savings' => $potential_cost_savings,
            'correction' => $correction,
            'correction_target_date' => $correction_target_date,
            // 'action_file' => $cer,
            'source' => $source,
            'updated_at' => now(),
            'is_responsible_person_changed'=> false,
            'is_moc_required' => $is_moc_required,
            'change_description' => $changeDescription,
            'moc_id' => $contextId
        ];
        if($corrective->is_req_rc_analysis_management_review=="yes"){
            $rootcause = $r->input('rootcause');
            $rootcause = implode("$", $rootcause);
            $correctiveaction = $r->input('correctiveaction');
            $correctiveaction = implode('$', $correctiveaction);
            $department = $r->input('department');
            $department = implode(',', $department);
            $responsibility = $r->input('responsibility');
            $responsibility = implode(',', $responsibility);
            $target_date = $r->input('target_date');
            $target_date = implode(',', $target_date);
            // if both rootcause and correction updated
            if ($correction != $corrective->correction && $rootcause != $corrective->rootcause) {
                $action_name = "Actions Proposed- Review Awaiting";
            } else if (($correction != $corrective->correction && $rootcause == $corrective->rootcause) && $corrective->rootcause_updated_by_investigation_team) {
                $action_name = "Actions Proposed- Review Awaiting";
            }
            //if only correction updated
            else if (($correction != $corrective->correction && $rootcause == $corrective->rootcause) && !$corrective->rootcause_updated_by_investigation_team) {
                $action_name = "Correction Proposed- RC Awaiting";
            }
            // if only rootcause updated
            else if ($correction == $corrective->correction && $rootcause != $corrective->rootcause) {
                $action_name = "RC Updated- Correction Awaiting";
            }

            if ($action_name === "Correction Proposed- RC Awaiting") {
                Corrective::where('id', $id)->update(['correction_proposed' => 1]);
            }
            $data['rootcause'] = $rootcause;
            $data['RCs_added_by_responsible_person'] = $rootcause;
            $data['correctiveaction'] = $correctiveaction;
            $data['department'] = $department;
            $data['responsibility'] = $responsibility;
            $data['target_date'] = $target_date;
        }
        if($corrective->is_req_rc_analysis_management_review=="no"){
            $action_name = "Actions Proposed- Review Awaiting";
        }
        $data['processstage'] = $action_name;
        Corrective::where('id', $id)->update($data);
        if($action_name == "Actions Proposed- Review Awaiting"){
            $correctiveRecord = Corrective::where('id', $id)->with(['correctionDept','typ'])->first();
            $appUrl = BaseController::addSlashToEndOfURL();
            $emailsNotifi = EmailNotification::where('module', 'correctiveaction')
                ->where('item', 'Actions Proposed')
                ->where('is_blocked', false)
                ->first();
            if ($emailsNotifi) {
                $mailData = array(
                    'issueid' => $correctiveRecord->issueid,
                    'item' => $emailsNotifi->item,
                    'email_content' => $emailsNotifi->email_content,
                    'type' => $correctiveRecord->typ ? $correctiveRecord->typ->name : '',
                    'department' => $correctiveRecord->correctionDept ? $correctiveRecord->correctionDept->departmentname : '',
                    'duedate' => $correctiveRecord->correction_target_date ? date("d/m/Y", strtotime($correctiveRecord->correction_target_date)) : '',
                    'id' => $id,
                    'app_url' => $appUrl,
                );
                $emails = getNonDeletedUsersEmail(IssueReviewer::pluck('email')->toArray());

                if (count($emails) > 0) {
                    try{
                        Mail::to($emails)->send(new CorrectiveActionsProposed($mailData));
                    }catch(\Exception $e){
                        Log::error('Exception occuar while sending email to issue reviewers: '.$e->getMessage());
                    }
                }
                $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $emailsNotifi->id)->get();
                if (count($userdatas1) > 0) {
                    $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        try{
                            Mail::to($userdatas)->send(new CorrectiveActionsProposed($mailData));
                        }catch(\Exception $e){
                            Log::error('Exception occuar while sending email to issue reviewers: '.$e->getMessage());
                        }
                    }
                }

            } else {
                Session::flash('msg', 'Mail Sending Blocked for NCR & Corrective Actions Proposed.');
            }
        }
        $activity = "Issue Implementation Status is updated and Process Stage is Updated to " . $action_name . " where Issue Id is " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        $models = DB::table('modenadisable')->First();
        switch ($action_name) {
            case 'Actions Proposed- Review Awaiting':
                $sessionType = "successMsg";
                $sessionMsg = "Actions proposed successfully.";
                break;
            case 'Correction Proposed- RC Awaiting':
                $sessionType = "infoMsg";
                $sessionMsg = "Correction updated successfully.";
                break;
            case 'RC Updated- Correction Awaiting':
                $sessionType = "infoMsg";
                $sessionMsg = "Root cause updated successfully.";
                break;
            default:
                $sessionType = "infoMsg";
                $sessionMsg = "";
                break;
        }
        return redirect('/user/corrective/corrissues')->with($sessionType, $sessionMsg)->with('model', $models);

    }
    //resubmit the corrissue
    public function resubmit()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            // $departments = Departments::all();
            $data_dept = Corrective::where('id',$id)->value('correction_department');
            $departments = Departments::where('is_active',true)
                                       ->orWhere('id',$data_dept)
                                       ->get();
            $type = Type::all();
            $user = User::where('approved', true)->get();
            $data = Corrective::with('cat')->where('id', '=', $id)->get();
            $categories = Categories::all();
            $issue_reviewers = Issuereviewer::pluck('email')->toArray();
            $data1 = Corrective::findOrFail($id);
            $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
            $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
            $issuedocs = $data1->file && $data1->file!="NULL" ? explode(',', $data1->file) : [];
            $actiondocs = $data1->action_file && $data1->action_file !="NULL" ? explode(',', $data1->action_file) : [];
            $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
            $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
            $rootcorrectives = CorrectiveRootCauseCorrectiveActionDetail::all();
            $models = DB::table('modenadisable')->First();
            $detectionStages = CorrectiveDetectionStage::all();
            $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
            return view('user.corrective.resubmit',compact('detectionStages','detectionStageLabel'))->withCategories($categories)
                ->withDepartments($departments)
                ->with('rootcorrectives', $rootcorrectives)
                ->with('issue_reviewers', $issue_reviewers)
                ->with(['issuedocs'=>$issuedocs,'actiondocs' => $actiondocs])
                ->withType($type)
                ->withUser($user)
                ->withData($data)
                ->withId($id)->with('model', $models);
        }
    }
    //submit resubmit
    public function resubmitissue(Request $req)
    {
        $id = $req->id;
        $corrective = Corrective::findOrFail($id);
        $cor_category_id = $corrective->category;
        $cor_year = $corrective->year;
        $rules = [
            'title' => 'required',
            'observation' => 'required',
            'duedate' => 'required|date',
            'agreedaction' => 'nullable',
            'financial_loss' => 'nullable|string|max:32',
            'potential_cost_savings' => 'nullable|string:max:32',
            // 'responsibility' => 'required',
            'source' => 'nullable',
        ];

        $v = Validator::make($req->all(), $rules);

        if ($v->fails()) {
            $v_error_array = [];
            $cnt = 0;
            foreach ($v->errors()->messages() as $e) {
                $v_error_array[$cnt] = '<li style="text-align:left !important;">' . implode(',', $e) . '</li>';
                $cnt++;
            }
            $v_errors_text = implode('', $v_error_array);

            alert()->warning('<h1>Validation errors</h1><br> <ul>' . $v_errors_text . '</ul>')->html()->persistent('Close');

            $models = DB::table('modenadisable')->First();

            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $cer = 'NULL';
            $removedIssueFiles = json_decode($req->input('removedIssueFilenames'),true);
            // $removedActionFiles = json_decode($req->input('removedActionFilenames'),true);
            if(count($removedIssueFiles)>0){
                $issueFiles = Corrective::where('id',$id)->value('file');
                if($issueFiles && $issueFiles!="NULL"){
                    $issueFilesArray = explode(',',$issueFiles);
                    $remainFilesArray = array_diff($issueFilesArray, $removedIssueFiles);
                    $remainFiles = implode(',', $remainFilesArray);
                    Corrective::where('id',$id)->update([
                        'file' => $remainFiles ? $remainFiles : NULL,
                        'updated_at' => now()
                    ]);
                }
                App\MultiplecorrIssue::where('id', $id)
                    ->whereIn('issue_file', $removedIssueFiles)
                    ->delete();
            }
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
                    App\MultiplecorrIssue::insert([
                        'id' => $id,
                        'issue_file' => $new_file_name
                    ]);
                }
                // $cer = implode(',', $file);
            }

            $financial_loss = $req->input('financial_loss');
            $potential_cost_savings = $req->input('potential_cost_savings');

            $id = $req->input('id');
            $issue_reviewers = Issuereviewer::pluck('email')->toArray();

            if (in_array(Auth::user()->email, $issue_reviewers)) {
                $processstage = "Awaiting actions";
                $approvement = "yes";
                $status = "open";
                $approvedby = Auth::user()->employeeid;
                CorrectiveEscalation::firstOrCreate([
                    'corrective_id' => $id,
                ]);
            } else {
                $processstage = "Entry Under Review";
                $approvement = "no";
                $status = "NULL";
                $approvedby = 'NULL';
            }

            $category_id = $req->input('category');
            $category = Categories::where('id', $category_id)->value('categoryname');
            $type = $req->input('type');
            $department = $req->input('department');
            $title = $req->input('title');
            $observation = $req->input('observation');
            $agreedaction = $req->input('agreedaction');
            $due_date = $req->input('duedate');
            // $resp = $req->input('responsibility');
            // $resp = $resp ? implode(',', $resp) : null;
            $source = $req->input('source');

            $postedby = $req->input('postedby');
            $division = $req->input('division');
            $area = $req->input('area');

            $year = date("Y");
            $data = array(
                'updated_at' => now(),
                'type' => $type,'detection_stage' => $req->input('detectionStage'),
                'correction_department' => $department,
                'division_id' => $division ?? 0,
                'area_id' => $area ?? 0,
                'title' => $title,
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
                // 'file' => $cer,
                'observation' => $observation,
                'correction_target_date' => $due_date,
                'agreedaction' => $agreedaction,
                'postedby' => $postedby,
                // 'correction_responsibility' => $resp,
                'approvement' => $approvement,
                'processstage' => $processstage,
                'approvedby' => $approvedby,
                'source' => $source,
                'active' => 'yes',
                'year' => $year,
                'category' => $category_id,
            );
            //Previous NCR Details
            $data['is_previously_occurred'] = $req->input('ncr_question');
            $data['no_of_occurrence'] = $req->input('no_of_occurrence');
            $data['previous_ncr_reference'] = $req->input('previous_ncr_reference');
            $data['other_previous_ncr_reference'] = $req->input('previous_ncr_reference')==0 ? $req->input('other_ncr') : null;
            Corrective::where('id', $id)->update($data);
            $this->saveOrUpdateNcrFieldFormData($req,$corrective);
            $dat = Corrective::where('id', $id)->with('typ')->get();
            foreach ($dat as $d) {
                $name = Auth::user()->name;
                $id = $d->id;
                $dept = DB::table('departments')->where('id', $department)->value('departmentname');
                // Sending Mail(Resubmit Issue) to IssueReviewer.
                $mails1 = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Issue Resubmited')
                    ->where('is_blocked', false)
                    ->first();
                if ($mails1) {
                    $emails = getNonDeletedUsersEmail(IssueReviewer::pluck('email')->toArray());
                    $mailData = array(
                        'issueid' => $d->issueid,
                        'item' => $mails1->item,
                        'email_content' => $mails1->email_content,
                        'name' => $name,
                        'type' => $d->typ ? $d->typ->name : '',
                        'department' => $dept,
                        'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                    );
                    if (count($emails) > 0) {
                        Mail::to($emails)->send(new CorrectiveResubmitIssue($mailData));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $mails1->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveResubmitIssue($mailData));
                        }
                    }

                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Resubmited');
                }
                // Sending Mail(Resubmit Notification) to Responsible HOD.
                $mails2 = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Issue Resubmit Notification')
                    ->where('is_blocked', false)
                    ->first();
                if ($mails2) {
                    $mailData = array(
                        'issueid' => $d->issueid,
                        'item' => $mails2->item,
                        'email_content' => $mails2->email_content,
                        'name' => $name,
                        'type' => $d->typ ? $d->typ->name : '',
                        'department' => $dept,
                        'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                    );
                    $responsibilities = getNonDeletedUsersEmail(explode(',', $d->responsibility));
                    if ($d->responsibility && count($responsibilities) > 0) {
                        Mail::to($responsibilities)->send(new CorrectiveIssueResubmitNotification($mailData));
                    }
                    $correction_responsibilities = $d->correction_responsibility ? getNonDeletedUsersEmail(explode(',', $d->correction_responsibility)) : [];
                    if ($d->correction_responsibility && count($correction_responsibilities) > 0) {
                        Mail::to($correction_responsibilities)->send(new CorrectiveIssueResubmitNotification($mailData));
                    }

                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $mails2->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveIssueResubmitNotification($mailData));
                        }
                    }

                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Resubmit Notification');
                }
            }
            //inserting values to activity table
            $activity = "Resubmitted Issue for Approval where Issue Id is " . $id;

            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmi code

            // Session::flash('msg', "Issue resubmitted for approval successfully");
            $models = DB::table('modenadisable')->first();

            return redirect('/user/corrective/corrissues')->with('infoMsg', "Issue resubmitted successfully")->with('model', $models);
        }
    }
    //Entry Review
    public function approve_()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $user = User::where('approved', true)->orderBy('name')->get();
            $issue_reviewers = Issuereviewer::pluck('email')->toArray();
            $type = Type::all();
            // $departments = Departments::all();
            $data = Corrective::with('cat')->findOrFail($id);
            $categories = Categories::whereNull('deleted_at')->orWhere('id', $data->category)->get();
            $departments = Departments::where('is_active', true)
                                        ->orWhere('id', $data->correction_department)
                                        ->get();
            $rec1 = DB::table('multiplecorr_issues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec2 = DB::table('multiplecorr_actions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $investigators = CorrectiveInvestigator::where('corrective_id', $id)->pluck('user_id')->toArray();
            $models = DB::table('modenadisable')->First();
            $data1 = Corrective::findOrFail($id);
            $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
            $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
            $issuedocs = $data1->file && $data1->file!="NULL" ? explode(',', $data1->file) : [];
            $actiondocs = $data1->action_file && $data1->action_file !="NULL" ? explode(',', $data1->action_file) : [];
            $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
            $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
            $detectionStages = CorrectiveDetectionStage::all();
            $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
            return view('user.corrective.approvement',compact('detectionStages','detectionStageLabel'))->withData($data)
                ->withDepartments($departments)
                ->with('investigators', $investigators)
                ->withRec1($rec1)
                ->with('id', $id)
                ->withRec2($rec2)
                ->withType($type)
                ->withUser($user)
                ->with([
                    'issuedocs' => $issuedocs,
                    'actiondocs' => $actiondocs
                ])
                ->with('categories', $categories)
                ->with('issue_reviewers', $issue_reviewers)
                ->with('model', $models);
        }
    }
    //Entry Review Approvement
    public function approvement(ApproveRequest $req)
    {
        $id = $req->input("corrective_id");
        $corr = Corrective::with('ncrFieldValueRelation','processNcrFieldValueRelation','vendorNcrFieldValueRelation')->findOrFail($id);
        CorrectiveEscalation::firstOrCreate([
            'corrective_id' => $id,
        ]);
        $financial_loss = $req->input('financial_loss');
        $potential_cost_savings = $req->input('potential_cost_savings');
        if ($req->frequency) {
            $freq = $req->input('frequency');
            $frequency = implode(',', $freq);
        } else {
            $frequency = null;
        }

        if ($req->notification) {
            $notify = $req->input('notification');
            $notification = implode(',', $notify);
        } else {
            $notification = null;
        }

        $approvedby = Auth::user()->employeeid;
        $category = $req->input('category');
        $catname = Categories::where("id", $category)->value('categoryname');
        $department = $req->input('department');
        $correction_target_date = $req->input('correction_target_date');
        $source = $req->input('source');
        $seperate = $req->input('seperate');
        $data = [
            'approvement' => 'yes',
            'processstage' => 'Awaiting actions',
            'status' => 'open','detection_stage' => $req->input('detectionStage'),
            'correction_department' => $department,
            'correction_target_date' => $correction_target_date,
            'investigation_date' => $req->input('investigation_date'),
            'title' => $req->input('title'),
            'type' => $req->input('type'),
            'observation' => $req->input('observation'),
            'agreedaction' => $req->input('agreedaction'),
            'financial_loss' => $financial_loss,
            'potential_cost_savings' => $potential_cost_savings,
            'source' => $source,
            'approvedby' => $approvedby,
            'entry_reviewed_by'=> User::where('email',Auth::user()->email)->value('id'),
            'entry_reviewed_by_date'=> now(),
            'correction_responsibility' => implode(',', $req->input('responsibility')),
            'notification' => $notification,
            'team_lead_id' => $req->input('lead') ?? 0,
            'frequency' => $frequency,
            'updated_at' => now(),
            'category' => $category,
            'division_id' => $req->input('division') ?? 0,
            'area_id' => $req->input('area') ?? 0,
        ];
        //Previous NCR Details
        $data['is_previously_occurred'] = $req->input('ncr_question');
        $data['no_of_occurrence'] = $req->input('no_of_occurrence');
        $data['previous_ncr_reference'] = $req->input('previous_ncr_reference');
        $data['other_previous_ncr_reference'] = $req->input('previous_ncr_reference')==0 ? $req->input('other_ncr') : null;
        Corrective::where('id', $id)->update($data);
        $this->saveOrUpdateNcrFieldFormData($req,$corr);
        $investigators = $req->input('investigation');
        $team_members = [];
        $corrective = Corrective::findOrFail($id);
        $found = CorrectiveInvestigator::where('corrective_id', $id)->first();
        if ($found) {
            CorrectiveInvestigator::where('corrective_id', $id)->delete();
        }

        if ($investigators && count($investigators)) {

            foreach ($investigators as $investigator) {
                $team_members[] = User::where('id', $investigator)->value('email');

                if ($corrective && $investigator) {
                    if ($found) {
                        CorrectiveInvestigator::insert([
                            'user_id' => $investigator,
                            'corrective_id' => $id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                    } else {
                        CorrectiveInvestigator::insert([
                            'user_id' => $investigator,
                            'corrective_id' => $id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                    }

                }
            }
        }
        if ($seperate === 'no') {
            Corrective::where('id', $id)->update([
                'team_lead_id' => 0,
                'investigation_date' => null,
            ]);
            CorrectiveInvestigator::where('corrective_id', $id)->delete();
        }
        $removedIssueFiles = json_decode($req->input('removedIssueFilenames'),true);
        $removedActionFiles = json_decode($req->input('removedActionFilenames'),true);
        if(count($removedIssueFiles)>0){
            $issueFiles = Corrective::where('id',$id)->value('file');
            if($issueFiles && $issueFiles!="NULL"){
                $issueFilesArray = explode(',',$issueFiles);
                $remainFilesArray = array_diff($issueFilesArray, $removedIssueFiles);
                $remainFiles = implode(',', $remainFilesArray);
                Corrective::where('id',$id)->update([
                    'file' => $remainFiles ? $remainFiles : NULL,
                    'updated_at' => now()
                ]);
            }
            App\MultiplecorrIssue::where('id', $id)
                ->whereIn('issue_file', $removedIssueFiles)
                ->delete();
        }
        if(count($removedActionFiles)>0){
            $actionFiles = Corrective::where('id',$id)->value('action_file');
            if($actionFiles && $actionFiles!="NULL"){
                $actionFilesArray = explode(',',$actionFiles);
                $remainFilesArray = array_diff($actionFilesArray,$removedActionFiles);
                $remainFiles = implode(',', $remainFilesArray);
                Corrective::where('id',$id)->update([
                    'action_file' => $remainFiles ? $remainFiles : NULL,
                    'updated_at' => now()
                ]);
            }
            App\MultiplecorrAction::where('id', $id)->whereIn('action_file',$removedActionFiles)->delete();
        }
        //inserting values to activity table
        $activity = "Issue is Approved and status is updated to Open where Issue Id is " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //end of superadmin code

        $data1 = Corrective::where('id', $id)->get();
        foreach ($data1 as $data) {
            $dept = DB::table('departments')->where('id', $data->department)->value('departmentname');
            $empid = $data->postedby;
            $posted_emp = DB::table('users')->where('employeeid', $empid)->get();
            $dept = Departments::where('id', $data->department)->value('departmentname');
            $corr_app_emails = EmailNotification::where('module', 'correctiveaction')
                ->where('item', 'Corrective Issue Approved')
                ->where('is_blocked', false)
                ->first();
            foreach ($posted_emp as $p) {
                //Sending Mail(Approved) To Posted Employee
                if($corr_app_emails) {
                    $maildata1 = array(
                        'issueid' => $data->issueid,
                        'email_content' => $corr_app_emails->email_content,
                        'item' => $corr_app_emails->item,
                        'type' => $data->typ ? $data->typ->name : '',
                        'department' => $dept,
                    );
                    $pemail = getNonDeletedUsersEmail($p->email);
                    if ($pemail) {
                        Mail::to($pemail)->send(new CorrectiveIssueApprove($maildata1));
                    }
                }

                $corr_respon_emails = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Response Notification')
                    ->where('is_blocked', false)
                    ->first();
                if ($corr_respon_emails) {
                    $mailData = array(
                        'issueid' => $data->issueid,
                        'name' => $p->name,
                        'email_content' => $corr_respon_emails->email_content,
                        'item' => $corr_respon_emails->item,
                        'type' => $data->typ ? $data->typ->name : '',
                        'department' => $dept,
                        'duedate' => $data->correction_target_date ? date("d/m/Y", strtotime($data->correction_target_date)) : '',
                    );
                    // Sending Mail(Responsibility Notification) To responsibile HOD
                    $responsible = getNonDeletedUsersEmail(explode(',', $data->responsibility));
                    if ($data->responsibility && count($responsible) > 0) {
                        Mail::to($responsible)->send(new CorrectiveResponseNotification($mailData));
                    }
                    $correction_responsible = $data->correction_responsibility ? getNonDeletedUsersEmail(explode(',', $data->correction_responsibility)) : [];
                    if ($data->correction_responsibility && count($correction_responsible) > 0) {
                        Mail::to($correction_responsible)->send(new CorrectiveResponseNotification($mailData));
                    }

                    //sending mail for participants and management participants
                    $corrective_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_respon_emails->id)->get();
                    if (count($corrective_respon_userdatas) > 0) {
                        $adminaddedusers = $corrective_respon_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveResponseNotification($mailData));
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
                    $notimaildata = array(
                        'name' => $p->name,
                        'email_content' => $corrective_add_emails->email_content,
                        'item' => $corrective_add_emails->item,
                        'type' => $data->typ ? $data->typ->name : '',
                        'department' => $dept,
                        'duedate' => $data->correction_target_date ? date("d/m/Y", strtotime($data->correction_target_date)) : '',
                    );
                    $noti = getNonDeletedUsersEmail(explode(',', $notification));
                    if ($notification && count($noti) > 0) {
                        Mail::to($noti)->send(new CorrectiveAddIssue($notimaildata));
                    }
                    //sending mail for participants and management participants
                    $corrective_add_userdatas = EmailNotificationsUserDetails::where('notification_id', $corrective_add_emails->id)->get();
                    if (count($corrective_add_userdatas) > 0) {
                        $adminaddedusers = $corrective_add_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveAddIssue($notimaildata));
                        }
                    }
                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Add Corrective Issue');
                }
            }
            if ($corr_app_emails) {
                //sending mail for participants and management participants
                $corrective_app_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_app_emails->id)->get();
                if (count($corrective_app_userdatas) > 0) {
                    $adminaddedusers = $corrective_app_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    $mailData = [
                        'issueid' => $data->issueid,
                        'email_content' => $corr_app_emails->email_content,
                        'item' => $corr_app_emails->item,
                        'type' => $data->typ ? $data->typ->name : '',
                        'department' => $dept,
                    ];
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new CorrectiveIssueApprove($mailData));
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Approved');
            }
        }

        // Session::flash('successMsg', 'Issue approved successfully');
        // echo "<script>alert('Issue approved successfully')</script>";
        $models = DB::table('modenadisable')->First();

        return redirect('/user/corrective/corrissues')->with('successMsg', 'Issue approved successfully')->with('model', $models);

    }

    public function disapprovereason(int $id)
    {
        $models = ModenaDisable::first();

        return view('user.corrective.disapprove_reason')->with([
            'id' => $id,
            'model' => $models,
        ]);
    }
    //Entry Review Disapprove
    public function disapprove(Request $req)
    {
        $id = $req->input("id");
        $disapproval_reason = $req->input('disapprove_reason');
        $updated_at = date('d-m-Y h:i:s A', time());

        $data = array(
            'processstage' => 'rejected',
            'active' => 'no',
            'disapproval_reason' => $disapproval_reason,
            'approvedby' =>Auth::user()->employeeid,
            'entry_reviewed_by'=> User::where('email',Auth::user()->email)->value('id'),
            'entry_reviewed_by_date'=>now(),
            'updated_at' => now(),
        );
        Corrective::where('id', $id)->update($data);
        //inserting values to activity table superadmin
        $activity = "Issue is 'Disapproved' and Process Stage is Updated to 'Rejected' where Issue Id is " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //end of superadmin code
        // Sending Mail(Disapprove) to Posted Employee.
        $data1 = Corrective::where('id', $id)->get();
        $emails = EmailNotification::where('module', 'correctiveaction')
            ->where('item', 'Corrective Issue Disapprove')
            ->where('is_blocked', false)
            ->first();
        if ($emails) {
            foreach ($data1 as $dat) {
                $id = $dat->postedby;
                $receiverdata = DB::table('users')->where('employeeid', $id)->pluck('email')->toArray();
                $mailData = array(
                    'issueid' => $dat->issueid,
                    'disapproval_reason' => $disapproval_reason,
                    'item' => $emails->item,
                    'email_content' => $emails->email_content,
                );
                if (count($receiverdata) > 0) {
                    Mail::to($receiverdata)->send(new CorrectiveDisapprove($mailData));
                }
                $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $emails->id)->get();
                if (count($userdatas1) > 0) {
                    $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new CorrectiveDisapprove($mailData));
                    }
                }
            }

        } else {
            Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Disapprove');
        }

        // echo "<script>alert('Issue disapproved successfully')</script>";
        $models = DB::table('modenadisable')->First();
        return redirect('/user/corrective/corrissues')->with('dangerMsg', 'Issue was not approved')->with('model', $models);

    }
    public function reviewstatus()
    {
        $issue_reviewers = Issuereviewer::pluck('email')->toArray();
        $currentUser = Auth::user()->email;
        if (isset($_GET['val']) && in_array($currentUser, $issue_reviewers)) {
            $id = $_GET['val'];
            $corrective = Corrective::with('previousNcrReferenceRelation')->findOrFail($id);
            $corr_depts = explode(',', $corrective->department);
            $type = Type::all();
            //$departments = Departments::all();
            $departments = Departments::where('is_active',true)->get();
            $users = User::where('approved', true)->get();
            $data = Corrective::where('id', $id)->get();
            $rec1 = DB::table('multiplecorr_issues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec2 = DB::table('multiplecorr_actions')->where('id', $id)->select('action_file')->distinct()->paginate(10);

            $users_text = "";
            if (count($users)) {
                foreach ($users as $user) {
                    $users_text .= "<option value='$user->email' style='font-size:13px;'>$user->name</option>";
                }
            }

            $departments_text = "";
            if (count($departments)) {
                $i = 1;
                foreach ($departments as $dep) {
                    $department_selected = isset($corr_depts[$i]) && $dep->id == $corr_depts[$i] ? 'selected' : '';
                    $departments_text .= "<option value='$dep->id' style='font-size:13px;' '$department_selected'>$dep->departmentname</option>";
                    $i++;
                }
            }
            $data1 = Corrective::findOrFail($id);
            $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
            $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
            $issuedocs = $data1->file && $data1->file!="NULL" ? explode(',', $data1->file) : [];
            $actiondocs = $data1->action_file && $data1->action_file !="NULL" ? explode(',', $data1->action_file) : [];
            $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
            $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
            $rootcorrectives = CorrectiveRootCauseCorrectiveActionDetail::all();
            $reviewer = Changerequestreviewer::pluck('email')->toArray();
            $approver = Changerequestapprove::pluck('email')->toArray();
            $reviewers = User::whereIn('email',$reviewer)->get()->sortBy('name');
            $approvers = User::whereIn('email',$approver)->get()->sortBy('name');
            $models = DB::table('modenadisable')->First();
            $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
            return view('user.corrective.statusreview',compact('approvers','reviewers','detectionStageLabel'))
                ->withData($data)
                ->withDepartments($departments)
                ->with('users', $users)
                ->with('users_text', $users_text)
                ->with('departments_text', $departments_text)
                ->with('issue_reviewers', $issue_reviewers)
                ->with('rootcorrectives', $rootcorrectives)
                ->with('issuedocs',$issuedocs)
                ->with('actiondocs',$actiondocs)
                ->withRec1($rec1)
                ->withRec2($rec2)
                ->withType($type)
                ->with('model', $models);
        }
        Session::flash('msg', 'You are not authorized to review the issue.');
        return redirect('/user/corrective/corrissues')->with('msg', 'You are not authorized to review the issue.');
    }
    //update progress
    public function updatePercentage(int $id)
    {
        $type = Type::all();
        $departments = Departments::all();
        $users = User::where('approved', true)->get();
        $data = Corrective::findOrFail($id);
        $rec1 = DB::table('multiplecorr_issues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
        $rec2 = DB::table('multiplecorr_actions')->where('id', $id)->select('action_file')->distinct()->paginate(10);

        $users_text = "";
        if (count($users)) {
            foreach ($users as $user) {
                $users_text .= "<option value='$user->email' style='font-size:13px;'>$user->name</option>";
            }
        }
        $departments_text = "";
        if (count($departments)) {
            foreach ($departments as $dep) {
                $departments_text .= "<option value='$dep->id' style='font-size:13px;'>$dep->departmentname</option>";
            }
        }

        $issue_reviewers = Issuereviewer::pluck('email')->toArray();
        $data1 = Corrective::findOrFail($id);
        $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
        $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
        $issuedocs = $data1->file && $data1->file!="NULL" ? explode(',', $data1->file) : [];
        $actiondocs = $data1->action_file && $data1->action_file !="NULL" ? explode(',', $data1->action_file) : [];
        $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
        $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
        $responsible_persons = explode(',', $data->correction_responsibility);

        $corrective_config_array = config('constants.corrective_management.progresses');
        $reviewer = Changerequestreviewer::pluck('email')->toArray();
        $approver = Changerequestapprove::pluck('email')->toArray();
        $reviewers = User::whereIn('email',$reviewer)->get()->sortBy('name');
        $approvers = User::whereIn('email',$approver)->get()->sortBy('name');
        $models = DB::table('modenadisable')->First();
        $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
        return view('user.corrective.updatepercentage',compact('reviewers','approvers','detectionStageLabel'))
            ->withData($data)
            ->withDepartments($departments)
            ->with('users_text', $users_text)
            ->with('departments_text', $departments_text)
            ->with('responsible_persons', $responsible_persons)
            ->with('corrective_config', $corrective_config_array)
            ->with('issue_reviewers', $issue_reviewers)
            ->with('issuedocs',$issuedocs)
            ->with('actiondocs',$actiondocs)
            ->withRec1($rec1)
            ->withRec2($rec2)
            ->withType($type)
            ->with('model', $models);

    }

    public function postUpdatePercentage(UpdatePercentageRequest $request)
    {
        $id = $request->input("id");
        $data = $request->validated();
        $corrective = Corrective::findOrFail($id);
        $changeDescription = $request->input('changeDescription');
        $is_moc_required = $request->input('is_moc_required');
        $mocTitle = $request->input('mocTitle');
        $reviewAuthority = $request->input('reviewAuthority');
        $approveAuthority = $request->input('approveAuthority');
        $currentUserDetails = User::where('email',Auth::user()->email)->first();
        $updateData = [
            'correction' => $data['correction'],
            'correction_target_date' => $data['correction_target_date'],
            'financial_loss' => isset($data['financial_loss']) ? $data['financial_loss'] : null,
            'potential_cost_savings' => isset($data['potential_cost_savings']) ? $data['potential_cost_savings'] : null,
            'correction_status' => $data['correction_status'] ? $data['correction_status'] : 0,
            'updated_at' => now(),
            'change_description' => $changeDescription,
            'is_moc_required' => $is_moc_required,
        ];
        if($corrective->is_req_rc_analysis_management_review=="yes"){
            $percent_array = array_merge([$data['correction_status']], explode(',', $data['percent_status']));
            $average_percent = array_sum($percent_array) / count($percent_array);
            $updateData['rootcause'] = isset($data['rootcause']) ? implode('$', $data['rootcause']) : '';
            $updateData['correctiveaction'] = isset($data['correctiveaction']) ? implode('$', $data['correctiveaction']) : '';
            $updateData['percent_status'] = $data['percent_status'];
            $updateData['average_percent'] = round($average_percent);
            if ($average_percent === 100) {
                $updateData['processstage'] = 'Actions Completed';
            }
        }else{
            $updateData['average_percent'] = round($data['correction_status']);
            if($data['correction_status']==100){
                $updateData['processstage'] = 'Actions Completed';
            }
        }
        /**-------------------------------MOC Details: Start------------------------------------------------ */
        if($is_moc_required=='yes'){
            if(!$corrective->moc){
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
                $contextData = [
                    'created_at' => date('d-m-Y h:i:s A', time()),
                    'updated_at' => date('d-m-Y h:i:s A', time()),
                    'title' => $mocTitle,
                    'initiator' => $currentUserDetails->id,
                    'department' => $corrective->correction_department,
                    'rev_authority' => $reviewAuthority,
                    'appr_authority' => $approveAuthority,
                    'postedby' => $currentUserDetails->employeeid,
                    'crid' => $issueId,
                ];
                DB::table('context_crs')->insert($contextData);
                $updateData['moc_id'] = ContextCrs::where('crid',$issueId)->value('id');
            }else{
                ContextCrs::where('id',$corrective->moc_id)->update([
                    'updated_at' => date('d-m-Y h:i:s A', time()),
                    'title' => $mocTitle
                ]);
            }
        }else{
            if($corrective->moc){
                DB::table('context_crs')->where('id',$corrective->moc_id)->delete();
            }
            $updateData['moc_id'] = null;
            $updateData['change_description'] = null;
        }
        /**-------------------------------Moc Details: End------------------------------------------------ */
        Corrective::where('id', $id)->update($updateData);

        if ($request->hasFile('action_file')) {
            $file_array = $request->file('action_file');
            $array_len = count($file_array);
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/issue_files');
                $file_array[$i]->move($destination_path, $new_file_name);
                App\MultiplecorrAction::insert([
                    'id' => $id,
                    'action_file' => $new_file_name
                ]);
            }
        }
        //inserting values to activity table superadmin
        $activity = "Corrective issue with Id as " . $id . " 's percentage is updated.";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);

        // Session::flash('msg', "Correcitve issue percentage updated successfully");

        $models = DB::table('modenadisable')->first();
        if (isset($updateData['processstage']) && $updateData['processstage'] == 'Actions Completed') {
            $sessionType = "successMsg";
            $sessionMsg = "Action completed successfully";
        } else {
            $sessionType = "infoMsg";
            $sessionMsg = "Progress updated successfully";
        }
        return redirect('/user/corrective/corrissues')->with($sessionType, $sessionMsg)->with('model', $models);
    }

    public function actionreviewed_ok(Request $req)
    {
        $id = $req->input('id');
        $corrective = Corrective::findOrFail($id);
        $rules = [
            'correction_target_date' => ['required', 'date'],
            'financial_loss' => ['nullable', 'string', 'max:32'],
            'potential_cost_savings' => ['nullable', 'string', 'max:32'],
            'source' => ['nullable'],
            'correction' => ['required']
        ];
        if ($corrective->is_req_rc_analysis_management_review =="yes") {
            $rules['rootcause'] = ['required'];
            $rules['rootcause.*'] = ['required'];
            $rules['correctiveaction'] = ['required'];
            $rules['correctiveaction.*'] = ['required'];
            $rules['department'] = ['nullable', 'array'];
            $rules['department.*'] = ['nullable', 'integer', 'exists:departments,id'];
            $rules['responsibility'] = ['required', 'array'];
            $rules['responsibility.*'] = ['required', 'email', 'exists:users,email'];
            $rules['target_date'] = ['required', 'array'];
            $rules['target_date.*'] = ['required', 'date'];
        } else {
            $rules['rootcause'] = ['nullable'];
            $rules['rootcause.*'] = ['nullable'];
            $rules['correctiveaction'] = ['nullable'];
            $rules['correctiveaction.*'] = ['nullable'];
            $rules['department'] = ['nullable', 'array'];
            $rules['department.*'] = ['nullable', 'integer', 'exists:departments,id'];
            $rules['responsibility'] = ['nullable', 'array'];
            $rules['responsibility.*'] = ['nullable', 'email', 'exists:users,email'];
            $rules['target_date'] = ['nullable', 'array'];
            $rules['target_date.*'] = ['nullable', 'date'];
        }
        $v = Validator::make($req->all(),$rules);

        if ($v->fails()) {

            $v_error_array = [];
            $cnt = 0;
            foreach ($v->errors()->messages() as $e) {
                $v_error_array[$cnt] = '<li style="text-align:left !important;">' . implode(',', $e) . '</li>';
                $cnt++;
            }
            $v_errors_text = implode('', $v_error_array);

            alert()->warning('<h1>Validation errors</h1><br> <ul>' . $v_errors_text . '</ul>')->html()->persistent('Close');

            $models = DB::table('modenadisable')->first();

            return redirect()->back()->withErrors($v)->withInput()->with('model', $models);
        } else {
            $financial_loss = $req->input('financial_loss');
            $potential_cost_savings = $req->input('potential_cost_savings');
            $updated_at = date('d-m-Y h:i:s A', time());
            $correctiveData = Corrective::findOrFail($id);
            $changeDescription = $req->input('changeDescription');
            $is_moc_required = $req->input('is_moc_required');
            $mocTitle = $req->input('mocTitle');
            $reviewAuthority = $req->input('reviewAuthority');
            $approveAuthority = $req->input('approveAuthority');
            $currentUserDetails = User::where('email',Auth::user()->email)->first();
            $data = array(
                'status' => 'open',
                'processstage' => 'Action Reviewed, OK',
                'active' => 'yes',
                'updated_at' => now(),
                'title' => $req->input('title'),
                'action_reviewed_by' => Auth::user()->employeeid,
                'action_reviewed_at' => now(),
                'observation' => $req->input('observation'),
                'agreedaction' => $req->input('agreedaction'),
                'change_description' => $changeDescription,
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
                'correction' => $req->input('correction'),
                'correction_target_date' => $req->input('correction_target_date'),
                'source' => $req->input('source'),
                'is_moc_required' => $is_moc_required,
            );
            if($corrective->is_req_rc_analysis_management_review=="yes"){
                $rootcause = $req->input('rootcause');
                $correctiveaction = $req->input('correctiveaction');
                $department = $req->input('department');
                $responsibility = $req->input('responsibility');
                $target_date = $req->input('target_date');
                $data['rootcause'] = implode('$', $rootcause);
                $data['correctiveaction'] = implode('$', $correctiveaction);
                $data['department'] = implode(',', $department);
                $data['responsibility'] = implode(',', $responsibility);
                $data['target_date'] = implode(',', $target_date);
            }

            /**-------------------------------MOC Details: Start------------------------------------------------ */
            if($is_moc_required=='yes'){
                if(!$correctiveData->moc){
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
                    $contextData = [
                        'created_at' => date('d-m-Y h:i:s A', time()),
                        'updated_at' => date('d-m-Y h:i:s A', time()),
                        'title' => $mocTitle,
                        'initiator' => $currentUserDetails->id,
                        'department' => $corrective->correction_department,
                        'rev_authority' => $reviewAuthority,
                        'appr_authority' => $approveAuthority,
                        'postedby' => $currentUserDetails->employeeid,
                        'crid' => $issueId,
                    ];
                    DB::table('context_crs')->insert($contextData);
                    $data['moc_id'] = ContextCrs::where('crid',$issueId)->value('id');
                }else{
                    ContextCrs::where('id',$correctiveData->moc_id)->update([
                        'updated_at' => date('d-m-Y h:i:s A', time()),
                        'title' => $mocTitle
                    ]);
                }
            }else{
                if($correctiveData->moc){
                    DB::table('context_crs')->where('id',$correctiveData->moc_id)->delete();
                }
                $data['moc_id'] = null;
                $data['change_description'] = null;
            }
            /**-------------------------------Moc Details: End------------------------------------------------ */
            Corrective::where('id', $id)->update($data);
            $removedIssueFiles = json_decode($req->input('removedIssueFilenames'),true);
            $removedActionFiles = json_decode($req->input('removedActionFilenames'),true);
            if(count($removedIssueFiles)>0){
                $issueFiles = Corrective::where('id',$id)->value('file');
                if($issueFiles && $issueFiles!="NULL"){
                    $issueFilesArray = explode(',',$issueFiles);
                    $remainFilesArray = array_diff($issueFilesArray, $removedIssueFiles);
                    $remainFiles = implode(',', $remainFilesArray);
                    Corrective::where('id',$id)->update([
                        'file' => $remainFiles ? $remainFiles : NULL,
                        'updated_at' => now()
                    ]);
                }
                App\MultiplecorrIssue::where('id', $id)
                    ->whereIn('issue_file', $removedIssueFiles)
                    ->delete();
            }
            if(count($removedActionFiles)>0){
                $actionFiles = Corrective::where('id',$id)->value('action_file');
                if($actionFiles && $actionFiles!="NULL"){
                    $actionFilesArray = explode(',',$actionFiles);
                    $remainFilesArray = array_diff($actionFilesArray,$removedActionFiles);
                    $remainFiles = implode(',', $remainFilesArray);
                    Corrective::where('id',$id)->update([
                        'action_file' => $remainFiles ? $remainFiles : NULL,
                        'updated_at' => now()
                    ]);
                }
                App\MultiplecorrAction::where('id', $id)->whereIn('action_file',$removedActionFiles)->delete();
            }
            $data1 = Corrective::where('id', $id)->get();
            foreach ($data1 as $data) {
                $dept = DB::table('departments')->where('id', $data->department)->value('departmentname');
                $posted_emp = DB::table('users')->where('employeeid', $data->postedby)->pluck('name');
                $name = '';
                foreach ($posted_emp as $p) {
                    $name = $p;
                }
                $mails = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Review Status Found OK')
                    ->where('is_blocked', false)
                    ->first();
                if ($mails) {
                    $mailData = array(
                        'issueid' => $data->issueid,
                        'item' => $mails->item,
                        'email_content' => $mails->email_content,
                        'type' => $data->typ ? $data->typ->name : '',
                        'department' => $dept,
                        'remarks' => null,
                        'duedate' => $data->correction_target_date ? date("d/m/Y", strtotime($data->correction_target_date)) : '',
                    );
                    //Sending Mail To Responsible HOD
                    $responsible = getNonDeletedUsersEmail(explode(',', $data->responsibility));
                    if ($data->responsibility && count($responsible) > 0) {
                        Mail::to($responsible)->send(new CorrectiveReviewStatusFoundOK($mailData));
                    }
                    $correction_responsible = getNonDeletedUsersEmail(explode(',', $data->correction_responsibility));
                    if ($data->correction_responsibility && count($correction_responsible) > 0) {
                        Mail::to($correction_responsible)->send(new CorrectiveReviewStatusFoundOK($mailData));
                    }

                    //Sending Mail(Notification) To Other HOD
                    $notification = $data->notification;
                    $noti = getNonDeletedUsersEmail(explode(',', $notification));
                    if ($notification && count($noti) > 0) {
                        Mail::to($noti)->send(new CorrectiveReviewStatusFoundOK($mailData));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $mails->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveReviewStatusFoundOK($mailData));
                        }
                    }
                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Review Status Found OK');
                }
            }
            //inserting values to activity table
            $activity = "Action Reviwed and Process Stage is Updated to 'Action Reviwed, OK' where Issue Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code

            // Session::flash('msg', "Action reviewed successfully");
            $models = DB::table('modenadisable')->First();
            return redirect('/user/corrective/corrissues')->with('successMsg', 'Actions approved successfully')->with('model', $models);

        }

    }

    public function notok_remarks()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $models = DB::table('modenadisable')->First();
            return view('user.corrective.notok_remarks')->withId($id)->with('model', $models);
        }
    }

    public function notok_addremarks(Request $req)
    {
        $action_remarks = $req->input('action_remarks');
        $id = $req->input('id');

        date_default_timezone_set('Asia/Kuwait');
        $updated_at = date('d-m-Y h:i:s A', time());

        $data = array(
            'status' => 'open',
            'processstage' => 'Action Reviewed, Not OK',
            'action_remarks' => $action_remarks,
            'updated_at' => now(),
            'action_reviewed_by' => Auth::user()->employeeid,
            'action_reviewed_at' => now(),
        );
        Corrective::where('id', $id)->update($data);

        $data1 = Corrective::where('id', $id)->get();
        foreach ($data1 as $data) {
            $dept = DB::table('departments')->where('id', $data->department)->value('departmentname');
            $posted_emp = DB::table('users')->where('employeeid', $data->postedby)->pluck('name');
            $name = '';
            foreach ($posted_emp as $p) {
                $name = $p;
            }

            $mails = EmailNotification::where('module', 'correctiveaction')
                ->where('item', 'Corrective Review Status Found Not OK')
                ->where('is_blocked', false)
                ->first();
            if ($mails) {
                $mailData = array(
                    'issueid' => $data->issueid,
                    'item' => $mails->item,
                    'email_content' => $mails->email_content,
                    'type' => $data->typ ? $data->typ->name : '',
                    'remarks' => $action_remarks,
                    'department' => $dept,
                    'duedate' => $data->correction_target_date ? date("d/m/Y", strtotime($data->correction_target_date)) : '',
                );
                //Sending Mail To Responsible HOD
                $responsible = getNonDeletedUsersEmail(explode(',', $data->responsibility));
                if ($data->responsibility && count($responsible) > 0) {
                    Mail::to($responsible)->send(new CorrectiveReviewStatusFoundNotOK($mailData));
                }
                $correction_responsible = getNonDeletedUsersEmail(explode(',', $data->correction_responsibility));
                if ($data->correction_responsibility && count($correction_responsible) > 0) {
                    Mail::to($correction_responsible)->send(new CorrectiveReviewStatusFoundNotOK($mailData));
                }

                //Sending Mail(Notification) To Other HOD
                $notification = $data->notification;
                $noti = getNonDeletedUsersEmail(explode(',', $notification));
                if ($notification && count($noti) > 0) {
                    Mail::to($noti)->send(new CorrectiveReviewStatusFoundNotOK($mailData));
                }
                $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $mails->id)->get();
                if (count($userdatas1) > 0) {
                    $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new CorrectiveReviewStatusFoundNotOK($mailData));
                    }
                }

            } else {
                Session::flash('msg', 'Mail Sending Blocked for Corrective Review Status Found Not OK');
            }
        }

        //inserting values to activity table
        $activity = "Action Reviwed and Process Stage is Updated to 'Action Reviwed,Not OK' where Issue Id is " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //end of superadmin code
        // Session::flash('msg', "Suggested changes successfully");
        $models = DB::table('modenadisable')->first();

        return redirect('/user/corrective/corrissues')->with('dangerMsg', "Actions were not approved")->with('model', $models);
    }

    //edit responsibility
    public function editresp()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $type = Type::all();
            //$departments = Departments::all();
            $data_dept = Corrective::where('id',$id)->value('correction_department');
            $departments = Departments::where('is_active',true)
                                       ->orWhere('id',$data_dept)
                                       ->get();
            $data = Corrective::where('id', $id)->get();
            foreach ($data as $d) {
                $res = $d->responsibility;
            }
            $res = explode(',', $res);
            $user = User::where('approved', true)->get();
            $issue_reviewers = Issuereviewer::pluck('email')->toArray();
            $models = DB::table('modenadisable')->first();

            return view('user.corrective.editresp')->withDepartments($departments)
                ->withUser($user)
                ->withData($data)
                ->with('issue_reviewers', $issue_reviewers)
                ->withType($type)
                ->with('model', $models);
        }
    }

    public function submittresp(Request $req)
    {
        $id = $req->input('id');
        $corrective = Corrective::findOrFail($id);
        $correction_resp = $req->input('correction_responsibility');
        $correction_resp_string = implode(',', $correction_resp);
        $correction_department = $req->input('correction_department');
        $source = $req->input('source');
        $datas = array(
            'correction_responsibility' => $correction_resp_string,
            'correction_department' => $correction_department,
            'source' => $source,
            'updated_at' => now(),
            'is_responsible_person_changed'=> true,
            'division_id' => $req->input('division') ?? 0,
            'area_id' => $req->input('area') ?? 0,
        );
        $resp = null;
        if($corrective->is_req_rc_analysis_management_review=="yes"){
            $resp = $req->input('responsibility');
            $resp_string = $resp ? implode(',', $resp) : null;
            $department = $req->input('department');
            $department_string = $department ? implode(',', $department) : 0;
            $datas['responsibility'] = $resp_string;
            $datas['department'] = $department_string;
        }
        $a = DB::table('users')->get();
        $e = Auth::user()->email;
        $created_date = now();
        Corrective::where('id', $id)->update($datas);

        $c = Corrective::findOrFail($id);
        $issueid = $c->issueid;
        $type = $c->type;
        $dept = $c->department;
        $department = DB::table('departments')->where('id', $dept)->value('departmentname');
        $duedate = $c->correction_target_date;
        $emails = EmailNotification::where('module', 'correctiveaction')
            ->where('item', 'Corrective Responsibility Changed')
            ->where('is_blocked', false)
            ->first();
        if ($emails) {
            $data = array(
                'item' => $emails->item,
                'email_content' => $emails->email_content,
                'department' => $department,
                'issueid' => $issueid,
                'type' => $c->typ ? $c->typ->name : '',
                'duedate' => $duedate ? date("d/m/Y", strtotime($duedate)) : '',
            );
            $resp = getNonDeletedUsersEmail($resp);
            $correction_resp = getNonDeletedUsersEmail($correction_resp);
            if ($resp && count($resp) > 0) {
                Mail::to($resp)->send(new CorrectiveResponsibilityChanged($data));
            }
            if (count($correction_resp) > 0) {
                Mail::to($correction_resp)->send(new CorrectiveResponsibilityChanged($data));
            }

            $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $emails->id)->get();
            if (count($userdatas1) > 0) {
                $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                $mailData = [
                    'item' => $emails->item,
                    'email_content' => $emails->email_content,
                    'department' => $department,
                    'issueid' => $issueid,
                    'type' => $c->typ ? $c->typ->name : '',
                    'duedate' => $duedate ? date("d/m/Y", strtotime($duedate)) : '',
                ];
                if (count($userdatas) > 0) {
                    Mail::to($userdatas)->send(new CorrectiveResponsibilityChanged($mailData));
                }
            }
        } else {
            Session::flash('msg', 'Mail Sending Blocked for Corrective Responsibility Change');
        }
        //inserting values to activity table
        $activity = "Corrective Issue is Edited and updated with Responsibility  as " . $correction_resp_string . "  where Issue ID is  " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //end of superadmin code
        Session::flash('msg', "Responsibility edited successfully");
        $models = DB::table('modenadisable')->first();

        return redirect()->back()->with('model', $models);
    }

    public function edit(int $id)
    {
        $type = Type::all();
        $users = User::where('approved', true)->get();
        $data = Corrective::with('cat')->where('id', $id)->get();
        $categories = Categories::all();
        $data_dept = Corrective::where('id',$id)->value('correction_department');
        $departments = Departments::where('is_active',true)
                                    ->orWhere('id',$data_dept)
                                    ->get();
        $rec1 = DB::table('multiplecorr_issues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
        $rec2 = DB::table('multiplecorr_actions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
        $users_text = "";
        if (count($users)) {
            foreach ($users as $user) {
                $users_text .= "<option value='$user->email' style='font-size:13px;'>$user->name</option>";
            }
        }
        $departments_text = "";
        if (count($departments)) {
            foreach ($departments as $dep) {
                $departments_text .= "<option value='$dep->id' style='font-size:13px;'>$dep->departmentname</option>";
            }
        }
        $issue_reviewers = Issuereviewer::pluck('email')->toArray();
        $data1 = Corrective::findOrFail($id);
        $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
        $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
        $issuedocs = $data1->file && $data1->file!="NULL" ? explode(',', $data1->file) : [];
        $actiondocs = $data1->action_file && $data1->action_file !="NULL" ? explode(',', $data1->action_file) : [];
        $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
        $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
        $rootcorrectives = CorrectiveRootCauseCorrectiveActionDetail::all();

        $models = DB::table('modenadisable')->first();
        $reviewer = Changerequestreviewer::pluck('email')->toArray();
        $approver = Changerequestapprove::pluck('email')->toArray();
        $reviewers = User::whereIn('email',$reviewer)->get()->sortBy('name');
        $approvers = User::whereIn('email',$approver)->get()->sortBy('name');
        $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
        $detectionStages = CorrectiveDetectionStage::all();
        return view('user.corrective.editmainpage',compact('reviewers','approvers','detectionStageLabel','detectionStages'))
            ->withData($data)
            ->withRec1($rec1)
            ->withRec2($rec2)
            ->withType($type)
            ->with('rootcorrectives', $rootcorrectives)
            ->with('issue_reviewers', $issue_reviewers)
            ->with('departments', $departments)
            ->with('departments_text', $departments_text)
            ->with('users_text', $users_text)
            ->with('users', $users)
            ->with('issuedocs',$issuedocs)
            ->with('actiondocs',$actiondocs)
            ->with('categories', $categories)
            ->with('model', $models);
    }
    public function update(Request $r)
    {
        $rules = [
            'title' => 'required',
            'observation' => 'required',
            'type' => 'required',
            'category' => 'required',
            'financial_loss' => 'nullable|string|max:32',
            'potential_cost_savings' => 'nullable|string|max:32',
            'source' => 'nullable',
        ];
        $id = $r->input('posted');
        $correctiveData = Corrective::findOrFail($id);
        $changeDescription = $r->input('changeDescription');
        $is_moc_required = $r->input('is_moc_required');
        $mocTitle = $r->input('mocTitle');
        $reviewAuthority = $r->input('reviewAuthority');
        $approveAuthority = $r->input('approveAuthority');
        $currentUserDetails = User::where('email',Auth::user()->email)->first();

        $v = Validator::make($r->all(), $rules);

        if ($v->fails()) {
            $v_error_array = [];
            $cnt = 0;
            foreach ($v->errors()->messages() as $e) {
                $v_error_array[$cnt] = '<li style="text-align:left !important;">' . implode(',', $e) . '</li>';
                $cnt++;
            }
            $v_errors_text = implode('', $v_error_array);

            alert()->warning('<h1>Validation errors</h1><br> <ul>' . $v_errors_text . '</ul>')->html()->persistent('Close');

            $models = DB::table('modenadisable')->First();

            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $cer = 'NULL';
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
                    App\MultiplecorrIssue::insert([
                        'id' => $id,
                        'issue_file' => $new_file_name
                    ]);
                }
                // $cer = implode(',', $file);
            }
            $financial_loss = $r->input('financial_loss');
            $potential_cost_savings = $r->input('potential_cost_savings');
            $type = $r->input('type');
            $title = $r->input('title');
            $category = $r->input('category');
            $catname = Categories::where('id', $category)->value('categoryname');
            $observation = $r->input('observation');
            $agreedaction = $r->input('agreedaction');
            $source = $r->input('source');
            $corrective = Corrective::findOrFail($id);
            $cor_category_id = $corrective->category;
            $cor_year = $corrective->year;

            $corr_department = $r->input('department');
            $division = $r->input('division');
            $area = $r->input('area');
            $data = array(
                'type' => $type,
                'title' => $title,
                'source' => $source,'detection_stage' => $r->input('detectionStage'),
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
                'observation' => $observation,
                'agreedaction' => $agreedaction,
                // 'action_file' => $cer,
                'status' => 'open',
                'correction_department' => $corr_department,
                'division_id' => $division ?? 0,
                'area_id' => $area ?? 0,
                'updated_at' => now(),
                'category' => $category,
                'change_description' => $changeDescription,
                'is_moc_required' => $is_moc_required,
            );
            //Previous NCR Details
            $data['is_previously_occurred'] = $r->input('ncr_question');
            $data['no_of_occurrence'] = $r->input('no_of_occurrence');
            $data['previous_ncr_reference'] = $r->input('previous_ncr_reference');
            $data['other_previous_ncr_reference'] = $r->input('previous_ncr_reference')==0 ? $r->input('other_ncr') : null;
            /**-------------------------------MOC Details: Start------------------------------------------------ */
            if($is_moc_required=='yes'){
                if(!$correctiveData->moc){
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
                    $contextData = [
                        'created_at' => date('d-m-Y h:i:s A', time()),
                        'updated_at' => date('d-m-Y h:i:s A', time()),
                        'title' => $mocTitle,
                        'initiator' => $currentUserDetails->id,
                        'department' => $corr_department,
                        'rev_authority' => $reviewAuthority,
                        'appr_authority' => $approveAuthority,
                        'postedby' => $currentUserDetails->employeeid,
                        'crid' => $issueId,
                    ];
                    DB::table('context_crs')->insert($contextData);
                    $data['moc_id'] = ContextCrs::where('crid',$issueId)->value('id');
                }else{
                    ContextCrs::where('id',$correctiveData->moc_id)->update([
                        'updated_at' => date('d-m-Y h:i:s A', time()),
                        'title' => $mocTitle
                    ]);
                }
            }else{
                if($correctiveData->moc){
                    DB::table('context_crs')->where('id',$correctiveData->moc_id)->delete();
                }
                $data['moc_id'] = null;
                $data['change_description'] = null;
            }
            /**-------------------------------Moc Details: End------------------------------------------------ */

            Corrective::where('id', $id)->update($data);
            $this->saveOrUpdateNcrFieldFormData($r,$corrective);
            $data2 = Corrective::where('id', $id)->with('typ')->get();
            foreach ($data2 as $d) {
                $id = $d->id;
                $dept = DB::table('departments')->where('id', $d->department)->value('departmentname');
                $emailsNotifi = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Updated')
                    ->where('is_blocked', false)
                    ->first();
                if ($emailsNotifi) {
                    $mailData = array(
                        'issueid' => $d->issueid,
                        'item' => $emailsNotifi->item,
                        'email_content' => $emailsNotifi->email_content,
                        'type' => $d->typ ? $d->typ->name : '',
                        'department' => $dept,
                        'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                    );
                    $emails = getNonDeletedUsersEmail(IssueReviewer::pluck('email')->toArray());
                    if (count($emails) > 0) {
                        Mail::to($emails)->send(new CorrectiveUpdated($mailData));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $emailsNotifi->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveUpdated($mailData));
                        }
                    }

                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Updated');
                }

            }
            //inserting values to activity table
            $activity = "Issue Edited and Status updated to 'Open' and Process Stage is Updated to 'Action Proposed' where Issue Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code
            // Sending Mail(ActionUpdated) to IssueReviewer.

            // Session::flash('msg', "Issue edited successfully");
            $models = DB::table('modenadisable')->first();

            return redirect('/user/corrective/corrissues')->with('infoMsg', "Issue updated successfully")->with('model', $models);
        }
    }

    public function editProposed(int $id)
    {
        $type = Type::all();
        $departments = Departments::all();
        $users = User::where('approved', true)->get();
        $data = Corrective::with('cat')->where('id', $id)->get();
        $categories = Categories::all();
        $rec1 = DB::table('multiplecorr_issues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
        $rec2 = DB::table('multiplecorr_actions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
        $users_text = "";
        if (count($users)) {
            foreach ($users as $user) {
                $users_text .= "<option value='$user->email' style='font-size:13px;'>$user->name</option>";
            }
        }
        $departments_text = "";
        if (count($departments)) {
            foreach ($departments as $dep) {
                $departments_text .= "<option value='$dep->id' style='font-size:13px;'>$dep->departmentname</option>";
            }
        }

        $rootcorrectives = CorrectiveRootCauseCorrectiveActionDetail::all();
        $issue_reviewers = Issuereviewer::pluck('email')->toArray();
        $data1 = Corrective::findOrFail($id);
        $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
        $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
        $issuedocs = $data1->file && $data1->file!="NULL" ? explode(',', $data1->file) : [];
        $actiondocs = $data1->action_file && $data1->action_file !="NULL" ? explode(',', $data1->action_file) : [];
        $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
        $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
        $models = DB::table('modenadisable')->first();
        $reviewer = Changerequestreviewer::pluck('email')->toArray();
        $approver = Changerequestapprove::pluck('email')->toArray();
        $reviewers = User::whereIn('email',$reviewer)->get()->sortBy('name');
        $approvers = User::whereIn('email',$approver)->get()->sortBy('name');
        $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
        $detectionStages = CorrectiveDetectionStage::all();
        return view('user.corrective.edit_proposed',compact('reviewers','approvers','detectionStageLabel','detectionStages'))
            ->withData($data)
            ->withRec1($rec1)
            ->withRec2($rec2)
            ->withType($type)
            ->with('issuedocs',$issuedocs)
            ->with('actiondocs',$actiondocs)
            ->with('rootcorrectives', $rootcorrectives)
            ->with('issue_reviewers', $issue_reviewers)
            ->with('departments', $departments)
            ->with('departments_text', $departments_text)
            ->with('users_text', $users_text)
            ->with('users', $users)
            ->with('categories', $categories)
            ->with('model', $models);
    }
    public function updateProposed(Request $r)
    {
        $id = $r->input('posted');
        $corrective = Corrective::findOrFail($id);
        $rules = [
            'title' => 'required',
            'observation' => 'required',
            'type' => 'required',
            'category' => 'required',
            // 'correction' => 'required',
            'financial_loss' => 'nullable|string|max:32',
            'source' => 'nullable',
            'potential_cost_savings' => 'nullable|string|max:32',
        ];
        $v = Validator::make($r->all(), $rules);

        if ($v->fails()) {
            $v_error_array = [];
            $cnt = 0;
            foreach ($v->errors()->messages() as $e) {
                $v_error_array[$cnt] = '<li style="text-align:left !important;">' . implode(',', $e) . '</li>';
                $cnt++;
            }
            $v_errors_text = implode('', $v_error_array);

            alert()->warning('<h1>Validation errors</h1><br> <ul>' . $v_errors_text . '</ul>')->html()->persistent('Close');

            $models = DB::table('modenadisable')->First();

            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $cer = 'NULL';
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
                    App\MultiplecorrAction::insert([
                        'id' => $id,
                        'action_file' => $new_file_name
                    ]);
                }
                // $cer = implode(',', $file);
            }
            if ($r->hasFile('issue_file')) {
                $file_array = $r->file('issue_file');
                $array_len = count($file_array);
                $file = [];
                for ($i = 0; $i < $array_len; $i++) {
                    $file_size = $file_array[$i]->getClientOriginalName();
                    $new_file_name = $file_size;
                    $destination_path = public_path('storage/issue_files');
                    $file_array[$i]->move($destination_path, $new_file_name);
                    $file[] = $new_file_name;
                    App\MultiplecorrIssue::insert([
                        'id' => $id,
                        'issue_file' => $new_file_name
                    ]);
                }
                // $cer = implode(',', $file);
            }
            $financial_loss = $r->input('financial_loss');
            $potential_cost_savings = $r->input('potential_cost_savings');
            $source = $r->input('source');
            $type = $r->input('type');
            $title = $r->input('title');
            $category = $r->input('category');
            $catname = Categories::where('id', $category)->value('categoryname');
            $observation = $r->input('observation');
            $agreedaction = $r->input('agreedaction');
            $cor_category_id = $corrective->category;
            $cor_year = $corrective->year;
            $changeDescription = $r->input('changeDescription');
            $is_moc_required = $r->input('is_moc_required');
            $mocTitle = $r->input('mocTitle');
            $reviewAuthority = $r->input('reviewAuthority');
            $approveAuthority = $r->input('approveAuthority');
            $currentUserDetails = User::where('email',Auth::user()->email)->first();
            $data = array(
                'type' => $type,
                'title' => $title,
                'category' => $category,'detection_stage' => $r->input('detectionStage'),
                'source' => $source,
                'observation' => $observation,
                'agreedaction' => $agreedaction,
                // 'correction' => $correction,
                // 'correction_target_date' => $correction_target_date,
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
                // 'action_file' => $cer,
                'status' => 'open',
                'updated_at' => now(),
                'change_description' => $changeDescription,
                'is_moc_required' => $is_moc_required,
            );
            //Previous NCR Details
            $data['is_previously_occurred'] = $r->input('ncr_question');
            $data['no_of_occurrence'] = $r->input('no_of_occurrence');
            $data['previous_ncr_reference'] = $r->input('previous_ncr_reference');
            $data['other_previous_ncr_reference'] = $r->input('previous_ncr_reference')==0 ? $r->input('other_ncr') : null;
            if($is_moc_required=='yes'){
                if(!$corrective->moc){
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
                    $contextData = [
                        'created_at' => date('d-m-Y h:i:s A', time()),
                        'updated_at' => date('d-m-Y h:i:s A', time()),
                        'title' => $mocTitle,
                        'initiator' => $currentUserDetails->id,
                        'department' => $corrective->correction_department,
                        'rev_authority' => $reviewAuthority,
                        'appr_authority' => $approveAuthority,
                        'postedby' => $currentUserDetails->employeeid,
                        'crid' => $issueId,
                    ];
                    DB::table('context_crs')->insert($contextData);
                    $data['moc_id'] = ContextCrs::where('crid',$issueId)->value('id');
                }else{
                    ContextCrs::where('id',$corrective->moc_id)->update([
                        'updated_at' => date('d-m-Y h:i:s A', time()),
                        'title' => $mocTitle
                    ]);
                }
            }else{
                if($corrective->moc){
                    DB::table('context_crs')->where('id',$corrective->moc_id)->delete();
                }
                $data['moc_id'] = null;
                $data['change_description'] = null;
            }
            /**-------------------------------Moc Details: End------------------------------------------------ */


            Corrective::where('id', $id)->update($data);
            $this->saveOrUpdateNcrFieldFormData($r,$corrective);
            $data2 = Corrective::where('id', $id)->with('typ')->get();
            foreach ($data2 as $d) {
                $id = $d->id;
                $dept = DB::table('departments')->where('id', $d->department)->value('departmentname');
                $emailsNotifi = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Updated')
                    ->where('is_blocked', false)
                    ->first();
                if ($emailsNotifi) {
                    $mailData = array(
                        'issueid' => $d->issueid,
                        'item' => $emailsNotifi->item,
                        'email_content' => $emailsNotifi->email_content,
                        'type' => $d->typ ? $d->typ->name : '',
                        'department' => $dept,
                        'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                    );
                    $emails = getNonDeletedUsersEmail(IssueReviewer::pluck('email')->toArray());
                    if (count($emails) > 0) {
                        Mail::to($emails)->send(new CorrectiveUpdated($mailData));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $emailsNotifi->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveUpdated($mailData));
                        }
                    }

                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Updated');
                }
            }
            //inserting values to activity table
            $activity = "Issue Edited and Status updated to 'Open' and Process Stage is Updated to 'Action Proposed' where Issue Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code
            // Sending Mail(ActionUpdated) to IssueReviewer.

            // Session::flash('msg', "Issue edited successfully");
            $models = DB::table('modenadisable')->first();

            return redirect('/user/corrective/corrissues')->with('infoMsg', "Issue edited successfully")->with('model', $models);
        }

    }
    public function editrespondedissue()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = Corrective::with('previousNcrReferenceRelation')->where('id', $id)->get();
            $correction_responsibility =  $data[0]->correction_responsibility ? explode(',',$data[0]->correction_responsibility) : [];
            $rootcuase_responsibility =  $data[0]->responsibility ? explode(',',$data[0]->responsibility) : [];
            $responsibility_array = array_merge($correction_responsibility,$rootcuase_responsibility);
            if(!in_array(Auth::user()->email, $responsibility_array) || !in_array($data[0]->processstage,[
                'Action Reviewed, OK','Action Reviewed, Not OK','Need More Action','Actions Completed',
                'Actions Proposed- Review Awaiting','Correction Proposed- RC Awaiting'
            ])){
                return redirect()->route('corrective.viewlist')->with('msg', 'You are not authorized to access edit propose action page.');
            }
            $type = Type::all();
            //$departments = Departments::all();
            $departments = Departments::where('is_active',true)->get();
            $users = User::where('approved', true)->get();
            $rec1 = DB::table('multiplecorr_issues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec2 = DB::table('multiplecorr_actions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $users_text = "";
            if (count($users)) {
                foreach ($users as $user) {
                    $users_text .= "<option value='$user->email' style='font-size:13px;'>$user->name</option>";
                }
            }
            $departments_text = "";
            if (count($departments)) {
                foreach ($departments as $dep) {
                    $departments_text .= "<option value='$dep->id' style='font-size:13px;'>$dep->departmentname</option>";
                }
            }
            $issue_reviewers = Issuereviewer::pluck('email')->toArray();
            $data1 = Corrective::findOrFail($id);
            $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
            $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
            $issuedocs = $data1->file && $data1->file!="NULL" ? explode(',', $data1->file) : [];
            $actiondocs = $data1->action_file && $data1->action_file !="NULL" ? explode(',', $data1->action_file) : [];
            $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
            $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
            $rootcorrectives = CorrectiveRootCauseCorrectiveActionDetail::all();

            $models = DB::table('modenadisable')->first();
            $reviewer = Changerequestreviewer::pluck('email')->toArray();
            $approver = Changerequestapprove::pluck('email')->toArray();
            $reviewers = User::whereIn('email',$reviewer)->get()->sortBy('name');
            $approvers = User::whereIn('email',$approver)->get()->sortBy('name');
            $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();

             return view('user.corrective.editrespondedissue',compact('approvers','reviewers','detectionStageLabel'))
                ->withData($data)
                ->withRec1($rec1)
                ->withRec2($rec2)
                ->withType($type)
                ->with(['issuedocs'=>$issuedocs,'actiondocs' => $actiondocs])
                ->with('rootcorrectives', $rootcorrectives)
                ->with('issue_reviewers', $issue_reviewers)
                ->with('departments', $departments)
                ->with('departments_text', $departments_text)
                ->with('users_text', $users_text)
                ->with('users', $users)
                ->with('model', $models);
        }
    }
    public function updaterespondedissue(Request $r)
    {
        $id = $r->input('posted');
        $corrective = Corrective::findOrFail($id);
        $changeDescription = $r->input('changeDescription');
        $is_moc_required = $r->input('is_moc_required');
        $mocTitle = $r->input('mocTitle');
        $reviewAuthority = $r->input('reviewAuthority');
        $approveAuthority = $r->input('approveAuthority');
        $currentUserDetails = User::where('email',Auth::user()->email)->first();
        $rules = [
            'correction' => 'required',
            'financial_loss' => 'nullable|string|max:32',
            'source' => 'nullable',
            'potential_cost_savings' => 'nullable|string|max:32',
        ];
        if ($corrective->is_req_rc_analysis_management_review == "yes") {
            $rules['rootcause'] = 'required';
            $rules['correctiveaction'] = 'required';
            $rootcause = $r->input('rootcause');
            $correctiveaction = $r->input('correctiveaction');
            if($rootcause || $correctiveaction){
                if($rootcause[0]!=null || $correctiveaction[0]!=null){
                    $rules['department'] = ['required', 'array'];
                    $rules['department.*'] = ['required', 'integer', \Illuminate\Validation\Rule::exists('departments', 'id')];
                    $rules['responsibility'] = ['required', 'array'];
                    $rules['responsibility.*'] = ['required', 'email', \Illuminate\Validation\Rule::exists('users', 'email')->where(function ($query) {
                        return $query->where('is_email_verified', 1)->where('approved', 1);
                    })];
                    $rules['target_date'] = 'required';
                    $rules['target_date.*'] = 'required|date';
                }
            }
        } else {
            $rules['rootcause'] = 'nullable';
            $rules['correctiveaction'] = 'nullable';
            $rules['target_date'] = 'nullable';
            $rules['target_date.*'] = 'nullable|date';
        }
        $messages = [
            'correction.required' => 'The correction field is required.',
            'financial_loss.numeric' => 'The financial loss must be a numeric value.',
            'potential_cost_savings.numeric' => 'The potential cost savings must be a numeric value.',
            'rootcause.required' => 'The root cause field is required.',
            'correctiveaction.required' => 'The corrective action field is required.',
            'department.required' => 'The department field is required.',
            'department.array' => 'The department field is required.',
            // 'department.*.required' => 'The department field is required.',
            // 'department.*.integer' => 'The department field is required.',
            // 'department.*.exists' => 'The department field is required.',
            'responsibility.required' => 'The responsibility field is required.',
            'responsibility.array' => 'The responsibility field is required.',
            // 'responsibility.*.required' => 'The responsibility field is required.',
            // 'responsibility.*.email' => 'The responsibility field is required.',
            // 'responsibility.*.exists' => 'The responsibility field is required.',
            'target_date.required' => 'The target date field is required.',
            // 'target_date.*.required' => 'The target date field is required.',
        ];

        $v = Validator::make($r->all(), $rules,$messages);

        if ($v->fails()) {
            $v_error_array = [];
            $cnt = 0;
            foreach ($v->errors()->messages() as $e) {
                $v_error_array[$cnt] = '<li style="text-align:left !important;">' . implode(',', $e) . '</li>';
                $cnt++;
            }
            $v_errors_text = implode('', $v_error_array);

            alert()->warning('<h1>Validation errors</h1><br> <ul>' . $v_errors_text . '</ul>')->html()->persistent('Close');

            $models = DB::table('modenadisable')->First();

            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $cer = 'NULL';
            $removedIssueFiles = json_decode($r->input('removedIssueFilenames'),true);
            $removedActionFiles = json_decode($r->input('removedActionFilenames'),true);
            if(count($removedIssueFiles)>0){
                $issueFiles = Corrective::where('id',$id)->value('file');
                if($issueFiles && $issueFiles!="NULL"){
                    $issueFilesArray = explode(',',$issueFiles);
                    $remainFilesArray = array_diff($issueFilesArray, $removedIssueFiles);
                    $remainFiles = implode(',', $remainFilesArray);
                    Corrective::where('id',$id)->update([
                        'file' => $remainFiles ? $remainFiles : NULL,
                        'updated_at' => now()
                    ]);
                }
                App\MultiplecorrIssue::where('id', $id)
                    ->whereIn('issue_file', $removedIssueFiles)
                    ->delete();
            }
            if(count($removedActionFiles)>0){
                $actionFiles = Corrective::where('id',$id)->value('action_file');
                if($actionFiles && $actionFiles!="NULL"){
                    $actionFilesArray = explode(',',$actionFiles);
                    $remainFilesArray = array_diff($actionFilesArray,$removedActionFiles);
                    $remainFiles = implode(',', $remainFilesArray);
                    Corrective::where('id',$id)->update([
                        'action_file' => $remainFiles ? $remainFiles : NULL,
                        'updated_at' => now()
                    ]);
                }
                App\MultiplecorrAction::where('id', $id)->whereIn('action_file',$removedActionFiles)->delete();
            }
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
                    App\MultiplecorrAction::insert([
                        'id' => $id,
                        'action_file' => $new_file_name
                    ]);
                }
                // $cer = implode(',', $file);
            }
            //$type = $r->input('type');

            $financial_loss = $r->input('financial_loss');
            $potential_cost_savings = $r->input('potential_cost_savings');
            $correction = $r->input('correction');
            $correction_target_date = $r->input('correction_target_date');
            $source = $r->input('source');
            $data = array(
                'correction' => $correction,
                'correction_target_date' => $correction_target_date,
                'source' => $source,
                // 'action_file' => $cer,
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
                'status' => 'open',
                'updated_at' => now(),
                'change_description' => $changeDescription,
                'is_moc_required' => $is_moc_required,
            );
            if ($corrective->is_req_rc_analysis_management_review == "yes") {
                $rootcause = $r->input('rootcause');
                $correctiveaction = $r->input('correctiveaction');
                $department = $r->input('department');
                $responsibility = $r->input('responsibility');
                $target_date = $r->input('target_date');
                $data['rootcause'] = implode('$', $rootcause);
                $data['correctiveaction'] = implode('$', $correctiveaction);
                $data['department'] = implode(',', $department);
                $data['responsibility'] = implode(',', $responsibility);
                $data['target_date'] = implode(',', $target_date);
            }
            /**-------------------------------MOC Details: Start------------------------------------------------ */
            if($is_moc_required=='yes'){
                if(!$corrective->moc){
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
                    $contextData = [
                        'created_at' => date('d-m-Y h:i:s A', time()),
                        'updated_at' => date('d-m-Y h:i:s A', time()),
                        'title' => $mocTitle,
                        'initiator' => $currentUserDetails->id,
                        'department' => $corrective->correction_department,
                        'rev_authority' => $reviewAuthority,
                        'appr_authority' => $approveAuthority,
                        'postedby' => $currentUserDetails->employeeid,
                        'crid' => $issueId,
                    ];
                    DB::table('context_crs')->insert($contextData);
                    $data['moc_id'] = ContextCrs::where('crid',$issueId)->value('id');
                }else{
                    ContextCrs::where('id',$corrective->moc_id)->update([
                        'updated_at' => date('d-m-Y h:i:s A', time()),
                        'title' => $mocTitle
                    ]);
                }
            }else{
                if($corrective->moc){
                    DB::table('context_crs')->where('id',$corrective->moc_id)->delete();
                }
                $data['moc_id'] = null;
                $data['change_description'] = null;
            }
            /**-------------------------------Moc Details: End------------------------------------------------ */
            Corrective::where('id', $id)->update($data);
            $corrective_after_update = Corrective::findOrFail($id);
            $rootcause_after_update = $corrective_after_update->rootcause;
            $correctiveaction_after_update = $corrective_after_update->correctiveaction;
            if($rootcause_after_update || $correctiveaction_after_update){
                $processstage = 'Actions Proposed- Review Awaiting';
            }else{
                $processstage = 'Correction Proposed- RC Awaiting';
            }
            Corrective::where('id', $id)->update(['processstage' => $processstage]);

            $data2 = Corrective::where('id', $id)->with(['correctionDept','typ'])->get();
            foreach ($data2 as $d) {
                $id = $d->id;
                $emailsNotifi = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Updated')
                    ->where('is_blocked', false)
                    ->first();
                if ($emailsNotifi) {
                    $mailData = array(
                        'issueid' => $d->issueid,
                        'item' => $emailsNotifi->item,
                        'email_content' => $emailsNotifi->email_content,
                        'type' => $d->typ ? $d->typ->name : '',
                        'department' => $d->correctionDept->departmentname,
                        'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                    );
                    $emails = getNonDeletedUsersEmail(IssueReviewer::pluck('email')->toArray());
                    if (count($emails) > 0) {
                        try{
                            Mail::to($emails)->send(new CorrectiveUpdated($mailData));
                        }catch(\Exception $e){
                            Log::error('Exception occuar while sending email to issue reviewers: '.$e->getMessage());
                        }
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $emailsNotifi->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            try{
                                Mail::to($userdatas)->send(new CorrectiveUpdated($mailData));
                            }catch(\Exception $e){
                                Log::error('Exception occuar while sending email to issue reviewers: '.$e->getMessage());
                            }
                        }
                    }

                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Updated');
                }
            }
            if($processstage == "Actions Proposed- Review Awaiting"){
                $correctiveRecord = Corrective::where('id', $id)->with(['correctionDept','typ'])->first();
                $appUrl = BaseController::addSlashToEndOfURL();
                $emailsNotifi = EmailNotification::where('module','correctiveaction')
                    ->where('item', 'Actions Proposed')
                    ->where('is_blocked', false)
                    ->first();
                if ($emailsNotifi) {
                    $mailData = array(
                        'issueid' => $correctiveRecord->issueid,
                        'item' => $emailsNotifi->item,
                        'email_content' => $emailsNotifi->email_content,
                        'type' => $correctiveRecord->typ ? $correctiveRecord->typ->name : '',
                        'department' => $correctiveRecord->correctionDept->departmentname,
                        'duedate' => $correctiveRecord->correction_target_date ? date("d/m/Y", strtotime($correctiveRecord->correction_target_date)) : '',
                        'id' => $id,
                        'app_url' => $appUrl,
                    );
                    $emails = getNonDeletedUsersEmail(IssueReviewer::pluck('email')->toArray());
                    if (count($emails) > 0) {
                        try{
                            Mail::to($emails)->send(new CorrectiveActionsProposed($mailData));
                        }catch(\Exception $e){
                            Log::error('Exception occuar while sending email to issue reviewers: '.$e->getMessage());
                        }
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $emailsNotifi->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            try{
                                Mail::to($userdatas)->send(new CorrectiveActionsProposed($mailData));
                            }catch(\Exception $e){
                                Log::error('Exception occuar while sending email to issue reviewers: '.$e->getMessage());
                            }
                        }
                    }

                } else {
                    Session::flash('msg', 'Mail Sending Blocked for NCR & Corrective Actions Proposed.');
                }
            }
            //inserting values to activity table
            $activity = "Issue Edited and Status updated to 'Open' and Process Stage is Updated to 'Action Proposed' where Issue Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code
            // Sending Mail(ActionUpdated) to IssueReviewer.

            // Session::flash('msg', "Issue edited successfully");
            $models = DB::table('modenadisable')->first();
            if ($processstage == "Actions Proposed- Review Awaiting") {
                $sessionType = "infoMsg";
                $sessionMsg = "Action updated successfully";
            } else {
                $sessionType = "infoMsg";
                $sessionMsg = "Correction updated successfully";
            }
            return redirect('/user/corrective/corrissues')->with($sessionType, $sessionMsg)->with('model', $models);
        }
    }

    public function closure()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $type = Type::all();
            //$departments = Departments::all();
            $departments = Departments::where('is_active', true)->get();
            $users = User::where('approved', true)->get();
            $data = Corrective::with(['moc','previousNcrReferenceRelation'])->findOrFail($id);
            $rec1 = DB::table('multiplecorr_issues')->where('id', $id)->select('issue_file')->distinct()->paginate(10);
            $rec2 = DB::table('multiplecorr_actions')->where('id', $id)->select('action_file')->distinct()->paginate(10);
            $users_text = "";
            if (count($users)) {
                foreach ($users as $user) {
                    $users_text .= "<option value='$user->email' style='font-size:13px;'>$user->name</option>";
                }
            }
            $departments_text = "";
            if (count($departments)) {
                foreach ($departments as $dep) {
                    $departments_text .= "<option value='$dep->id' style='font-size:13px;'>$dep->departmentname</option>";
                }
            }

            $corrective_config_array = config('constants.corrective_management.progresses');

            $issue_reviewers = Issuereviewer::pluck('email')->toArray();
            $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
            $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
            $issuedocs = $data->file && $data->file!="NULL" ? explode(',', $data->file) : [];
            $actiondocs = $data->action_file && $data->action_file !="NULL" ? explode(',', $data->action_file) : [];
            $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
            $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
            $models = DB::table('modenadisable')->First();
            $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
            return view('user.corrective.closurereview',compact('detectionStageLabel'))
                ->withData($data)
                ->withDepartments($departments)
                ->with('users', $users)
                ->with('departments_text', $departments_text)
                ->with('users_text', $users_text)
                ->with('corrective_config', $corrective_config_array)
                ->with('issue_reviewers', $issue_reviewers)
                ->with('issuedocs',$issuedocs)
                ->with('actiondocs',$actiondocs)
                ->withRec1($rec1)
                ->withRec2($rec2)
                ->withType($type)
                ->with('model', $models);
        }
    }

    public function changes(int $id, string $status = '')
    {
        $models = DB::table('modenadisable')->first();

        return view('user.corrective.suggestchanges')->withId($id)->with('status', $status)->with('model', $models);
    }

    public function suggestchanges(Request $req)
    {
        $v = Validator::make($req->all(), [
            //'source' => 'nullable|max:255',
        ]);

        if ($v->fails()) {
            $models = DB::table('modenadisable')->first();
            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            //$source = $req->input('source');
            $id = $req->input('id');
            $corrective = Corrective::findOrFail($id);
            $remarks = $req->input('remarks');
            $data = array(
                'status' => 'open',
                'impl_status' => 'Partially Completed-50%',
                'remarks' => $remarks,
                'updated_at' => now(),
                'closed' => now(),
                'closedby' => Auth::user()->employeeid,
            );
            $percent_status = $req->input('percent_status');
            $percent_status_array = explode(',', $percent_status);
            $percent_status_array_copy = explode(',', $percent_status);
            $correction_status = end($percent_status_array);
            $average_percent = array_sum($percent_status_array) / count($percent_status_array);
            $average_percent = round($average_percent, 0);
            array_pop($percent_status_array_copy);
            $percent_status_array_copy = implode(',', $percent_status_array_copy);
            $data['correction_status'] = $correction_status;
            $data['average_percent'] = $average_percent;
            if($corrective->is_req_rc_analysis_management_review == "yes"){
                $data['percent_status'] = $percent_status_array_copy;
            }
            if ($req->action_modified == "yes") {
                $data['processstage'] = 'Need More Action';
                Corrective::where('id', $id)->update($data);
                $data1 = Corrective::where('id', $id)->with('typ')->First();
                $dept = DB::table('departments')->where('id', $data1->department)->value('departmentname');
                $posted_emp = DB::table('users')->where('employeeid', $data1->postedby)->value('name');
                $mails = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Review Status Found Not OK')
                    ->where('is_blocked', false)
                    ->first();
                if ($mails) {
                    $mailData = array(
                        'issueid' => $data1->issueid,
                        'item' => $mails->item,
                        'email_content' => $mails->email_content,
                        'type' => $data1->typ ? $data1->typ->name : '',
                        'department' => $dept,
                        'remarks' => $data1->remarks,
                        'duedate' => $data1->correction_target_date ? date("d/m/Y", strtotime($data1->correction_target_date)) : '',
                    );
                    //Sending Mail To Responsible HOD
                    $responsible = getNonDeletedUsersEmail(explode(',', $data1->responsibility));
                    if ($data1->responsibility && count($responsible) > 0) {
                        Mail::to($responsible)->send(new CorrectiveReviewStatusFoundNotOK($mailData));
                    }
                    $correction_responsible = getNonDeletedUsersEmail(explode(',', $data1->correction_responsibility));
                    if ($data1->correction_responsibility && count($correction_responsible) > 0) {
                        Mail::to($correction_responsible)->send(new CorrectiveReviewStatusFoundNotOK($mailData));
                    }

                    //Sending Mail(Notification) To Other HOD
                    $notification = $data1->notification;
                    $noti = getNonDeletedUsersEmail(explode(',', $notification));
                    if ($notification && count($noti) > 0) {
                        Mail::to($noti)->send(new CorrectiveReviewStatusFoundNotOK($mailData));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $mails->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveReviewStatusFoundNotOK($mailData));
                        }
                    }

                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Review Status Found Not OK');
                }
                //inserting values to activity table
                $activity = "Issue Process Stage is Updated to 'Need More Action' and Implementation Status is updated to 'Partially Completed-50%'  where Issue Id is " . $id;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
                ViewActivity::insert($datas);
                //end of superadmin code
            } elseif ($req->action_modified == "no") {
                $data['processstage'] = 'Action Reviewed, OK';
                Corrective::where('id', $id)->update($data);
                $data1 = Corrective::where('id', $id)->with('typ')->First();
                $dept = DB::table('departments')->where('id', $data1->department)->value('departmentname');
                $posted_emp = DB::table('users')->where('employeeid', $data1->postedby)->value('name');
                $mails = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Review Status Found OK')
                    ->where('is_blocked', false)
                    ->first();
                if ($mails) {
                    $mailData = array(
                        'issueid' => $data1->issueid,
                        'item' => $mails->item,
                        'email_content' => $mails->email_content,
                        'type' => $data1->typ ? $data1->typ->name : '',
                        'remarks' => $remarks,
                        'department' => $dept,
                        'duedate' => $data1->correction_target_date ? date("d/m/Y", strtotime($data1->correction_target_date)) : '',
                    );
                    //Sending Mail To Responsible HOD
                    $responsible = getNonDeletedUsersEmail(explode(',', $data1->responsibility));
                    if ($data1->responsibility && count($responsible) > 0) {
                        Mail::to($responsible)->send(new CorrectiveReviewStatusFoundOK($mailData));
                    }
                    $correction_responsible = getNonDeletedUsersEmail(explode(',', $data1->correction_responsibility));
                    if ($data1->correction_responsibility && count($correction_responsible) > 0) {
                        Mail::to($correction_responsible)->send(new CorrectiveReviewStatusFoundOK($mailData));
                    }

                    //Sending Mail(Notification) To Other HOD
                    $notification = $data1->notification;
                    $noti = getNonDeletedUsersEmail(explode(',', $notification));
                    if ($notification && count($noti) > 0) {
                        Mail::to($noti)->send(new CorrectiveReviewStatusFoundOK($mailData));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $mails->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveReviewStatusFoundOK($mailData));
                        }
                    }

                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Review Status Found OK');
                }
                //inserting values to activity table
                $activity = "Issue Process Stage is Updated to 'Action Reviewed, OK' and Implementation Status is updated to 'Partially Completed-50%'  where Issue Id is " . $id;
                $date = Carbon::now()->toDateTimeString();
                $date = Carbon::parse($date);
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
                ViewActivity::insert($datas);
                //end of superadmin code

            }

            $models = DB::table('modenadisable')->first();
            return redirect('/user/corrective/corrissues')->with('msg', "Additional action has been requested before closure.")->with('model', $models);
        }

    }

    public function closed(int $id, Request $request)
    {
        $v = Validator::make($request->all(), [
            //'source' => 'nullable|max:255',
        ]);
        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $date = date('Y-m-d');
            $corrective = Corrective::findOrFail($id);
            $closedby = Auth::user()->employeeid;
            $correction = $request->input('correction');
            $removedIssueFiles = json_decode($request->input('issueFiles'),true);
            $removedActionFiles = json_decode($request->input('actionFiles'),true);
            if(count($removedIssueFiles)>0){
                $issueFiles = Corrective::where('id',$id)->value('file');
                if($issueFiles && $issueFiles!="NULL"){
                    $issueFilesArray = explode(',',$issueFiles);
                    $remainFilesArray = array_diff($issueFilesArray, $removedIssueFiles);
                    $remainFiles = implode(',', $remainFilesArray);
                    Corrective::where('id',$id)->update([
                        'file' => $remainFiles ? $remainFiles : NULL,
                        'updated_at' => now()
                    ]);
                }
                App\MultiplecorrIssue::where('id', $id)
                    ->whereIn('issue_file', $removedIssueFiles)
                    ->delete();
            }
            if(count($removedActionFiles)>0){
                $actionFiles = Corrective::where('id',$id)->value('action_file');
                if($actionFiles && $actionFiles!="NULL"){
                    $actionFilesArray = explode(',',$actionFiles);
                    $remainFilesArray = array_diff($actionFilesArray,$removedActionFiles);
                    $remainFiles = implode(',', $remainFilesArray);
                    Corrective::where('id',$id)->update([
                        'action_file' => $remainFiles ? $remainFiles : NULL,
                        'updated_at' => now()
                    ]);
                }
                App\MultiplecorrAction::where('id', $id)->whereIn('action_file',$removedActionFiles)->delete();
            }
            $vRemark = $request->input('vRemarks');
            $data = [
                'status' => 'closed',
                'processstage' => 'Accomplished',
                'correction' => $correction,
                'closed' => now(),
                'closedby' => $closedby,
                'updated_at' => now(),
                'verification_remarks' => $vRemark,
            ];
            if($corrective->is_req_rc_analysis_management_review == "yes"){
                $rootcause = $request->input('rootcause');
                $rootcause = implode('$', $rootcause);
                $correctiveaction = $request->input('correctiveaction');
                $correctiveaction = implode('$', $correctiveaction);
                $responsibility = $request->input('responsibility');
                $responsibility = implode(',', $responsibility);
                $department = $request->input('department');
                $department = implode(',', $department);
                $data['rootcause'] = $rootcause;
                $data['correctiveaction'] = $correctiveaction;
                $data['responsibility'] = $responsibility;
                $data['department'] = $department;
            }

            Corrective::where('id', $id)->update($data);

            //checking corrective status for incident reports and auto close incident
            $incident_id = Corrective::where('id', $id)->select('incident_id')->First()->incident_id;
            $open_status = DB::table('corrective')
                ->where('incident_id', $incident_id)
                ->where('status', 'open')
                ->get();

            if ($open_status->isEmpty()) {
                $status = "Closed";
                DB::table('incident_reports')->where('id', $incident_id)->update(['reviewed' => $status]);
            }

            //inserting values to activity table
            $activity = "Issue is Closed with Status updated as 'Closed' and Process Stage Updated as Accomplished where Issue Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin code
            // Sending Mail(Closed Issue) to HOD and notifying members.
            $data1 = Corrective::where('id', $id)->with('typ')->get();
            foreach ($data1 as $data) {
                $posted_emp = DB::table('users')->where('employeeid', $data->postedby)->pluck('name');
                foreach ($posted_emp as $p) {
                    $name = $p;
                }
                $dept = Departments::where('id', $data->department)->value('departmentname');
                //Sending Mail To Responsible HOD
                $emails = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Issue Closed')
                    ->where('is_blocked', false)
                    ->first();
                if ($emails) {
                    $mailData = array(
                        'issueid' => $data->issueid,
                        'item' => $emails->item,
                        'email_content' => $emails->email_content,
                        'type' => $data->typ ? $data->typ->name : '',
                        'department' => $dept,
                    );
                    $responsible = getNonDeletedUsersEmail(explode(',', $data->responsibility));
                    if ($data->responsibility && count($responsible) > 0) {
                        try {
                            Mail::to($responsible)->send(new CorrectiveIssueClosed($mailData));
                        } catch (\Exception $e) {
                            Log::error('Exception occurred while sending email to users: ' . $e->getMessage());
                        }
                    }
                    $correction_responsible = getNonDeletedUsersEmail(explode(',', $data->correction_responsibility));
                    if ($data->correction_responsibility && count($correction_responsible) > 0) {
                        try {
                            Mail::to($correction_responsible)->send(new CorrectiveIssueClosed($mailData));
                        } catch (\Exception $e) {
                            Log::error('Exception occurred while sending email to users: ' . $e->getMessage());
                        }
                    }

                    //Sending Mail(Notification) To Other HOD
                    $notification = $data->notification;
                    $noti = getNonDeletedUsersEmail(explode(',', $notification));
                    if ($notification && count($noti) > 0) {
                        try {
                            Mail::to($noti)->send(new CorrectiveIssueClosed($mailData));
                        } catch (\Exception $e) {
                            Log::error('Exception occurred while sending email to users: ' . $e->getMessage());
                        }
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $emails->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            try {
                                Mail::to($userdatas)->send(new CorrectiveIssueClosed($mailData));
                            } catch (\Exception $e) {
                                Log::error('Exception occurred while sending email to users: ' . $e->getMessage());
                            }
                        }
                    }

                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Closed');
                }

                Session::flash('successMsg', "Issue closed successfully");
            }
        }

    }

    //delete request
    public function delete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            //inserting data to activities table
            $activity = "A Corrective Issue was Deleted where Issue id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //end of superadmin codes

            DB::delete('delete from corrective where id = :eid', ['eid' => $id]);
            DB::table('incident_corrective')->where('corrective_id', $id)->delete();

            $models = DB::table('modenadisable')->First();
            return redirect('/user/corrective/corrissues')->with('msg', $activity)->with('model', $models);
        }
    }

    public function reopen(int $id)
    {
        $data = Corrective::with('cat')->findOrFail($id);
        $data_dept = Corrective::where('id',$id)->value('correction_department');
        $departments = Departments::where('is_active',true)
                                   ->orWhere('id',$data_dept)
                                   ->get();
        //$departments = Departments::all();
        $type = Type::all();
        $user = User::where('approved', true)->orderBy('name')->get();
        $categories = Categories::all();
        $issue_reviewers = Issuereviewer::pluck('email')->toArray();
        $investigators = CorrectiveInvestigator::where('corrective_id', $data->id)->pluck('user_id')->toArray();
        $models = DB::table('modenadisable')->First();
        $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
        $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
        $issuedocs = $data->file && $data->file!="NULL" ? explode(',', $data->file) : [];
        $actiondocs = $data->action_file && $data->action_file !="NULL" ? explode(',', $data->action_file) : [];
        $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
        $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
        $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
        $detectionStages = CorrectiveDetectionStage::all();
        return view('user.corrective.reopen',compact('detectionStageLabel','detectionStages'))->with([
            'departments' => $departments,
            'type' => $type,
            'data' => $data,
            'categories' => $categories,
            'investigators' => $investigators,
            'issue_reviewers' => $issue_reviewers,
            'user' => $user,
            'model' => $models,
            'actiondocs' => $actiondocs,
            'issuedocs' => $issuedocs
        ]);

    }

    public function reopenissue(Request $req)
    {
        $corr_id = $req->input('id');
        $financial_loss = $req->input('financial_loss');
        $potential_cost_savings = $req->input('potential_cost_savings');
        $corrective = Corrective::findOrFail($corr_id);
        $issue_reviewers = Issuereviewer::pluck('email')->toArray();

        $rules = [
            'lead' => 'required_if:seperate,yes',
            'investigation_date' => 'required_if:seperate,yes',
            'responsibility' => ['required', 'array'],
            'responsibility.*' => ['required', 'email', 'exists:users,email'],
            'correction_target_date' => 'required|date',
            'financial_loss' => 'nullable|string|max:32',
            'potential_cost_savings' => 'nullable|string|max:32',
            'source' => 'nullable',
        ];

        $v = Validator::make($req->all(), $rules);

        if ($v->fails()) {
            $v_error_array = [];
            $cnt = 0;
            foreach ($v->errors()->messages() as $e) {
                $v_error_array[$cnt] = '<li style="text-align:left !important;">' . implode(',', $e) . '</li>';
                $cnt++;
            }
            $v_errors_text = implode('', $v_error_array);

            alert()->warning('<h1>Validation errors</h1><br> <ul>' . $v_errors_text . '</ul>')->html()->persistent('Close');

            $models = DB::table('modenadisable')->First();
            return redirect()->back()->withErrors($v)->withInput()->with('model', $models);
        } else {
            $cer = 'NULL';
            $agreedaction = 'NULL';
            $removedIssueFiles = json_decode($req->input('removedIssueFilenames'),true);
            $removedActionFiles = json_decode($req->input('removedActionFilenames'),true);
            if(count($removedIssueFiles)>0){
                $issueFiles = Corrective::where('id',$corr_id)->value('file');
                if($issueFiles && $issueFiles!="NULL"){
                    $issueFilesArray = explode(',',$issueFiles);
                    $remainFilesArray = array_diff($issueFilesArray, $removedIssueFiles);
                    $remainFiles = implode(',', $remainFilesArray);
                    Corrective::where('id',$corr_id)->update([
                        'file' => $remainFiles ? $remainFiles : NULL,
                        'updated_at' => now()
                    ]);
                }
                App\MultiplecorrIssue::where('id', $corr_id)
                    ->whereIn('issue_file', $removedIssueFiles)
                    ->delete();
            }
            if(count($removedActionFiles)>0){
                $actionFiles = Corrective::where('id',$corr_id)->value('action_file');
                if($actionFiles && $actionFiles!="NULL"){
                    $actionFilesArray = explode(',',$actionFiles);
                    $remainFilesArray = array_diff($actionFilesArray,$removedActionFiles);
                    $remainFiles = implode(',', $remainFilesArray);
                    Corrective::where('id',$corr_id)->update([
                        'action_file' => $remainFiles ? $remainFiles : NULL,
                        'updated_at' => now()
                    ]);
                }
                App\MultiplecorrAction::where('id', $corr_id)->whereIn('action_file',$removedActionFiles)->delete();
            }
            if ($req->hasFile('issue_file')) {
                $file_array = $req->file('issue_file');
                $array_len = count($file_array);
                $file = [];
                for ($i = 0; $i < $array_len; $i++) {
                    $file_size = $file_array[$i]->getClientOriginalName();
                    $new_file_name = $file_size;
                    $destination_path = public_path('storage/issue_files');
                    $file_array[$i]->move($destination_path, $new_file_name);
                    $file[] = $new_file_name;
                    App\MultiplecorrIssue::insert([
                        'id' => $corr_id,
                        'issue_file' => $new_file_name
                    ]);
                }
                // $cer = implode(',', $file);
            }
            $reviewers = DB::table('issuereviewers')->get();
            $representatives = DB::table('representatives')->get();
            $issue_reviewers = Issuereviewer::pluck('email')->toArray();

            $list = collect([]);
            foreach ($reviewers as $reviewer) {
                $list = $list->push($reviewer->email);
            }
            foreach ($representatives as $representative) {
                $list = $list->push($representative->email);
            }
            $auditors = $list->unique();
            if (in_array(Auth::user()->email, $issue_reviewers)) {
                $processstage = "Awaiting actions";
                $approvement = "yes";
                $status = "open";
                $approvedby = Auth::user()->employeeid;
                CorrectiveEscalation::firstOrCreate([
                    'corrective_id' => $corr_id,
                ]);
            } else {
                $processstage = "Entry Under Review";
                $approvement = "no";
                $status = "NULL";
                $approvedby = 'NULL';
            }
            $category_id = $req->input('category');
            $source = $req->input('source');
            $type = $req->input('type');
            $department = $req->input('department');
            $dep = DB::table('departments')->where('id', $department)->value('departmentname');
            $division = $req->input('division');
            $area = $req->input('area');
            $title = $req->input('title');
            $observation = $req->input('observation');
            $agreedaction = $req->input('agreedaction');
            $correction_target_date = $req->input('correction_target_date');
            if ($req->frequency) {
                $freq = $req->input('frequency');
                $frequency = implode(',', $freq);
            } else {
                $frequency = null;
            }

            $resp = $req->input('responsibility');
            $resp = $resp ? implode(',', $resp) : null;
            if ($req->notification) {
                $notify = $req->input('notification');
                $notification = implode(',', $notify);
            } else {
                $notification = '';
            }

            $postedby = $req->input('postedby');
            $name = DB::table('users')->where('employeeid', $postedby)->get();
            $postdate = date('d-m-Y h:i:s A', time());
            $year_to_be_inserted = date('Y', time());
            $isRequiredTeam = $req->input('seperate');
            if($isRequiredTeam){
                $investigation_date = $req->input('investigation_date');
                $lead = $req->input('lead');
                $investigators = $req->input('investigation');
            }else{
                $investigation_date =null;
                $lead = 0;
                $investigators = [];
            }

            $category_found = Categories::where('id', $category_id)->first();
            if ($category_found && $category_found->enable_source === 0) {
                $source = null;
            }
            $data4 = array(
                'count' => $corrective->count+1,
                'category' => $category_id,
                'source' => $source,
                'type' => $type, 'detection_stage' => $req->input('detectionStage'),
                'department' => $department,
                'division_id' => $division,
                'area_id' => $area,
                'title' => $title,
                // 'file' => $cer,
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
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
                'status' => $status,
                'status' => $status,
                'postdate' => $postdate,
                'year' => $year_to_be_inserted,
                'investigation_date' => $investigation_date,
                'team_lead_id' => $lead,
                'is_reopened' => true,
                'updated_at' => now(),
            );
            //Previous NCR Details
            $data4['is_previously_occurred'] = $req->input('ncr_question');
            $data4['no_of_occurrence'] = $req->input('no_of_occurrence');
            $data4['previous_ncr_reference'] = $req->input('previous_ncr_reference');
            $data4['other_previous_ncr_reference'] = $req->input('previous_ncr_reference')==0 ? $req->input('other_ncr') : null;
            Corrective::where('id', $corr_id)->update($data4);
            $this->saveOrUpdateNcrFieldFormData($req,$corrective);
            $found = CorrectiveInvestigator::where('corrective_id', $corr_id)->first();
            if ($found) {
                CorrectiveInvestigator::where('corrective_id', $corr_id)->delete();
            }

            if ($investigators && count($investigators)) {

                foreach ($investigators as $investigator) {
                    if ($corrective && $investigator) {
                        //$found = CorrectiveInvestigator::where('corrective_id', $corr_id)->first();
                        if ($found) {
                            //CorrectiveInvestigator::where('corrective_id', $corr_id)->delete();
                            CorrectiveInvestigator::insert([
                                'user_id' => $investigator,
                                'corrective_id' => $corrective->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                        } else {
                            CorrectiveInvestigator::insert([
                                'user_id' => $investigator,
                                'corrective_id' => $corrective->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                        }

                    }
                }
            }

            //inserting values to activity table superadmin code
            $activity = "Issue with id " . $corrective->issue_id . " is reopened with Title as " . $title . ", with Status as " . $status . " and with Process Stage as " . $processstage;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            $corrective_inves_emails = EmailNotification::where('module', 'correctiveaction')
                ->where('item', 'Corrective Investigate')
                ->where('is_blocked', false)
                ->first();
            if ($corrective_inves_emails) {
                if ($lead) {
                    $leaderEmail = User::where('id', $lead)->value('email');
                    $emaildata = array(
                        'email_content' => $corrective_inves_emails->email_content,
                        'item' => $corrective_inves_emails->item,
                        'issueid' => $corrective->issueid,
                        'duedate' => $investigation_date ? date("d/m/Y", strtotime($investigation_date)) : '',
                    );
                    if ($leaderEmail != "") {
                        Mail::to($leaderEmail)->send(new CorrectiveInvestigate($emaildata));
                    }
                }
                //sending mail for participants and management participants
                $correctiv_inves_userdatas = EmailNotificationsUserDetails::where('notification_id', $corrective_inves_emails->id)->get();
                if (count($correctiv_inves_userdatas) > 0) {
                    $adminaddedusers = $correctiv_inves_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    $mailData = array(
                        'email_content' => $corrective_inves_emails->email_content,
                        'item' => $corrective_inves_emails->item,
                        'issueid' => $corrective->issueid,
                        'duedate' => $investigation_date ? date("d/m/Y", strtotime($investigation_date)) : '',
                    );
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new CorrectiveInvestigate($mailData));
                    }
                }

            } else {
                Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Investigator');
            }
            $corrective_member_emails = EmailNotification::where('module', 'correctiveaction')
                ->where('item', 'Corrective Team Members')
                ->where('is_blocked', false)
                ->first();
            if ($corrective_member_emails) {
                $mailData = array(
                    'email_content' => $corrective_member_emails->email_content,
                    'item' => $corrective_member_emails->item,
                    'issueid' => $corrective->issueid,
                    'target_date' => $investigation_date ? date("d/m/Y", strtotime($investigation_date)) : '',
                );
                //mail to team members
                $team_members = getNonDeletedUsersEmail($investigators);
                if (count($team_members) > 0) {
                    Mail::to($team_members)->send(new CorrectiveTeamMembers($mailData));
                }
                $correctiv_member_userdatas = EmailNotificationsUserDetails::where('notification_id', $corrective_member_emails->id)->get();
                if (count($correctiv_member_userdatas) > 0) {
                    $adminaddedusers = $correctiv_member_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new CorrectiveTeamMembers($mailData));
                    }
                }

            } else {
                Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Team Members');
            }

            $d = Corrective::with('typ')->orderBy('id', 'desc')->first();

            $id = $d->id;
            $postedby = $d->postedby;
            $name = DB::table('users')->where('employeeid', $postedby)->pluck('name');

            if (count($name) <= 0) {
                $name = Auth::user()->name;
            }
            $name = Auth::user()->name;
            $m = 0;

            foreach ($auditors as $a) {
                if (Auth::user()->email == $a) {
                    $corr_respon_emails = EmailNotification::where('module', 'correctiveaction')
                        ->where('item', 'Corrective Response Notification')
                        ->where('is_blocked', false)
                        ->first();
                    if ($corr_respon_emails) {
                        $mailData = array(
                            'issueid' => $d->issueid,
                            'name' => $name,
                            'email_content' => $corr_respon_emails->email_content,
                            'item' => $corr_respon_emails->item,
                            'type' => $d->typ ? $d->typ->name : '',
                            'department' => $dep,
                            'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                        );
                        // Sending Mail(Responsibility Notification) To responsibile HOD
                        $responsibilities = getNonDeletedUsersEmail(explode(',', $d->responsibility));
                        if ($d->responsibility && count($responsibilities) > 0) {
                            Mail::to($responsibilities)->send(new CorrectiveResponseNotification($mailData));
                        }

                        $correction_responsibilities = getNonDeletedUsersEmail(explode(',', $d->correction_responsibility));
                        if ($d->correction_responsibility && count($correction_responsibilities) > 0) {
                            Mail::to($correction_responsibilities)->send(new CorrectiveResponseNotification($mailData));
                        }

                        //sending mail for participants and management participants
                        $corrective_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_respon_emails->id)->get();
                        if (count($corrective_respon_userdatas) > 0) {
                            $adminaddedusers = $corrective_respon_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas) > 0) {
                                Mail::to($userdatas)->send(new CorrectiveResponseNotification($mailData));
                            }
                        }
                    } else {
                        Session::flash('msg', 'Mail Sending Blocked for Corrective Response Notification');
                    }

                    // Sending Mail(AddIsssue Notification) To Other HOD
                    $mails1 = EmailNotification::where('module', 'correctiveaction')
                        ->where('item', 'Corrective Reopen Issue')
                        ->where('is_blocked', false)
                        ->first();
                    if ($mails1) {
                        $data1 = array(
                            'issueid' => $d->issueid,
                            'item' => $mails1->item,
                            'email_content' => $mails1->email_content,
                            'name' => $name,
                            'type' => $d->typ ? $d->typ->name : '',
                            'department' => $dep,
                            'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                        );
                        $noti = getNonDeletedUsersEmail(explode(',', $notification));
                        if ($notification && count($noti) > 0) {
                            Mail::to($noti)->send(new CorrectiveReopenIssue($data1));
                        }
                        $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $mails1->id)->get();
                        if (count($userdatas1) > 0) {
                            $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas) > 0) {
                                Mail::to($userdatas)->send(new CorrectiveReopenIssue($data1));
                            }
                        }

                    } else {
                        Session::flash('msg', 'Mail Sending Blocked for Corrective Reopen Issue');
                    }
                    $m = 1;
                }
            }
            if ($m == 0) {
                $corr_iss_await_emails = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Issue Awaiting Approval')
                    ->where('is_blocked', false)
                    ->first();
                if ($corr_iss_await_emails) {
                    $mailData = array(
                        'issueid' => $d->issueid,
                        'name' => $name,
                        'email_content' => $corr_iss_await_emails->email_content,
                        'item' => $corr_iss_await_emails->item,
                        'type' => $d->typ ? $d->typ->name : '',
                        'department' => $dep,
                        'duedate' => $d->correction_target_date ? date("d/m/Y", strtotime($d->correction_target_date)) : '',
                    );
                    $issue_rev_emails = getNonDeletedUsersEmail(IssueReviewer::pluck('email')->gtoArray());
                    if (count($issue_rev_emails) > 0) {
                        Mail::to($issue_rev_emails)->send(new CorrectiveIssueAwaitingApproval($mailData));
                    }
                    //sending mail for participants and management participants
                    $corrective_await_userdatas = EmailNotificationsUserDetails::where('notification_id', $corr_iss_await_emails->id)->get();
                    if (count($corrective_await_userdatas) > 0) {
                        $adminaddedusers = $corrective_await_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new CorrectiveIssueAwaitingApproval($mailData));
                        }
                    }
                } else {
                    Session::flash('msg', 'Mail Sending Blocked for Corrective Issue Awaiting Approval');
                }
            }

            // Session::flash('msg', "Issue reopened successfully");
            $models = DB::table('modenadisable')->first();

            return redirect('/user/corrective/corrissues')->with('infoMsg', "Issue reopened successfully")->with('model', $models);
        }

    }

    // public function get_duplicates($array)
    // {
    //     return array_unique(array_diff_assoc($array, array_unique($array)));
    // }

    // public function test()
    // {
    //     $issue_ids = Corrective::pluck('issueid')->toArray();
    //     //$x  = Audit::pluck('aud_id')->toArray();
    //     dd($this->get_duplicates($issue_ids));

    //     $corrective_categories = Corrective::groupBy('category')->pluck('category')->toArray();

    //     for ($i = 0; $i < count($corrective_categories); $i++) {

    //         $correctives = Corrective::select('id', 'count', 'issueid', 'year', 'postdate', 'category')->where('category', $corrective_categories[$i])->orderBy('id', 'asc')->get();

    //         for ($j = 0; $j < count($correctives); $j++) {

    //             $cat_id = Categories::where('categoryname', $correctives[$j]['category'])->value('id') ?? 0;

    //             $post_date_different_format = Carbon::createFromFormat('d-m-Y h:i:s A', $correctives[$j]['postdate'])->format('Y-m-d H:i:s');

    //             $the_year = date("Y", strtotime($post_date_different_format));

    //             $the_year = substr($the_year, -2);

    //             Corrective::where('category', $corrective_categories[$i])->where('id', $correctives[$j]['id'])->update([
    //                 'count' => $j + 1,
    //                 'issueid' => strtoupper(substr($corrective_categories[$i], 0, 3)) . ($j + 1) . '/' . $cat_id . '/' . $the_year,
    //             ]);

    //         }
    //     }

    // }

    public function detail($id)
    {
        $models = DB::table('modenadisable')->first();
        $corrective = Corrective::with('postedByCorrective', 'postedByAdmin', 'teamLead','previousNcrReferenceRelation')->findOrFail($id);
        if (!$corrective->postedByCorrective) {
            $postedByAdmin = $corrective->postedByAdmin;
            $postedBy = $postedByAdmin ? User::where('email', $postedByAdmin->email)->first() : null;
        } else {
            $postedBy = $corrective->postedByCorrective;
        }
        $dept = DB::table('departments')->where('id', $corrective->correction_department)->value('departmentname');
        if ($corrective->category == 'Legal Requirement' || $corrective->category == 'Management Review' ||$corrective->category == 'Incident Investigation') {
            $cat = $corrective->category;
        } else {
            $cat = $corrective->cat ? $corrective->cat->categoryname : '';
        }
        $notificationEmail = explode(',', $corrective->notification);
        $notificationUsers = User::whereIn('email', $notificationEmail)->get();
        $frequency = $corrective->frequency ? explode(',', $corrective->frequency) : [];
        $issue_reviewers = Issuereviewer::pluck('email')->toArray();
        $investigationTeams = CorrectiveInvestigator::with('user')->where('corrective_id', $corrective->id)->get();
        $investigationTeamIds = $investigationTeams->pluck('user_id')->toArray();
        $currentUser = User::where('email',Auth::user()->email)->value('id');
        $rootcause = $this->getRootCauseTable($corrective);
        $attachments = $this->getAttachments($corrective);
        $processStage = $this->getProcessStage($corrective);
        $ncrFields = $this->getProductNcrFields($corrective->category,'new-detail-page',$corrective->id);
        $clientDetails = ClientDetail::first();

        return view('user.corrective.detail', compact(
            'corrective', 'dept', 'cat', 'notificationUsers', 'frequency','investigationTeamIds','rootcause','clientDetails',
            'attachments', 'investigationTeams', 'postedBy','issue_reviewers','currentUser','processStage','ncrFields'
        ))
            ->with('model', $models);
    }

    public function exportPdf($id)
    {
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        $corrective = Corrective::with('postedByCorrective', 'postedByAdmin','previousNcrReferenceRelation','cat')->findOrFail($id);
        if (!$corrective->postedByCorrective) {
            $postedByAdmin = $corrective->postedByAdmin;
            $postedBy = User::where('email', $postedByAdmin->email)->first();
        } else {
            $postedBy = $corrective->postedByCorrective;
        }
        $dept = DB::table('departments')->where('id', $corrective->correction_department)->value('departmentname');
        if ($corrective->category == 'Legal Requirement' || $corrective->category == 'Management Review' ||$corrective->category == 'Incident Investigation') {
            $cat = $corrective->category;
        } else {
            $cat = $corrective->cat ? $corrective->cat->categoryname : '';
        }
        $notificationEmail = explode(',', $corrective->notification);
        $notificationUsers = User::whereIn('email', $notificationEmail)->get();
        $frequency = $corrective->frequency ? explode(',', $corrective->frequency) : [];
        $issueFiles = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
        $actionFiles = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
        $issuedocs = $corrective->file && $corrective->file!="NULL" ? explode(',', $corrective->file) : [];
        $actiondocs = $corrective->action_file && $corrective->action_file !="NULL" ? explode(',', $corrective->action_file) : [];
        $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
        $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
        $investigationTeams = CorrectiveInvestigator::with('user')->where('corrective_id', $corrective->id)->get();
        $ncrFields = $this->getProductNcrFields($corrective->category,'pdf-detail-page',$corrective->id);
        $breakPage = false;
        if($corrective->cat && $corrective->cat->is_vendor_cat=='yes'){
            $countFields = VendorNcrFieldValue::where('category_id', $corrective->cat->id)->where('corrective_id', $corrective->id)->count();
            $breakPage = $countFields > 6 ? true : false;
        }
        $clientDetails = ClientDetail::first();
        $pdf = \PDF::loadView('user.corrective.detail_pdf',
            compact('corrective', 'dept', 'cat', 'notificationUsers', 'frequency','clientDetails',
                'issuedocs', 'actiondocs', 'investigationTeams', 'postedBy','ncrFields', 'breakPage'
            ))->setPaper('a4', 'portrait');
        return $pdf->download('detail_' . $corrective->issueid . '.pdf');
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
            $models = DB::table('modenadisable')->first();
            $relationData = [
                'correctionDept', 'division', 'area', 'teamLead','moc','previousNcrReferenceRelation',
                'postedByCorrective', 'correctiveInvestigators', 'investigators', 'cat','entryReviewedByCorrective',
                'approvedByCorrective', 'closedByCorrective', 'actionReviewedByCorrective', 'typ'
            ];
            $users = User::where('approved',true)->get();
            $issue = Corrective::with($relationData)->findOrFail($id);
            $correctionResponsibilities =  $users->whereIn('email',explode(',', $issue->correction_responsibility));
            $issueFilesArray = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
            $actionFilesArray = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
            $issuedocs = $issue->file && $issue->file!="NULL" ? explode(',', $issue->file) : [];
            $actiondocs = $issue->action_file && $issue->action_file !="NULL" ? explode(',', $issue->action_file) : [];
            $issueFiles = array_unique(array_merge($issuedocs,$issueFilesArray));
            $actionFiles = array_unique(array_merge($actionFilesArray,$actiondocs));
            $departments = Departments::all();
            $responsibilityEmails = explode(',', $issue->responsibility);
            $responsibleUser =  $users->whereIn('email', $responsibilityEmails);
            $responseData['success'] = true;
            $issue_reviewers = Issuereviewer::pluck('email')->toArray();

            $ncrFields = $this->getProductNcrFields($issue->category,'view-detail',$issue->id);
            $detectionStageLabel = DetectionStageLabelModel::orderByDesc('created_at')->first();
            $responseData['view'] = \view('user.corrective._detail', compact(
                'issue', 'models', 'correctionResponsibilities','issue_reviewers','users',
                'departments', 'responsibleUser', 'issueFiles', 'actionFiles','ncrFields'
            ))->render();
            $responseData['id'] = $issue->id;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Display the investigation form
     *
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function displayInvestigation($id): JsonResponse
    {
        $responseData = [];

        try {
            $models = DB::table('modenadisable')->first();
            $issue = Corrective::findOrFail($id);
            $rootcorrectives = CorrectiveRootCauseCorrectiveActionDetail::all();
            $departments = Departments::where('is_active',true)->get();
            $departments_text = "";
            if (count($departments)) {
                foreach ($departments as $dep) {
                    $departments_text .= "<option value='$dep->id' style='font-size:13px;'>$dep->departmentname</option>";
                }
            }
            $users = User::where('approved', true)->get();
            $users_text = "";
            if (count($users)) {
                foreach ($users as $user) {
                    $users_text .= "<option value='$user->email' style='font-size:13px;'>$user->name</option>";
                }
            }
            $responseData['success'] = true;
            $rr = $issue->id;
            $issueFilesArray = App\MultiplecorrIssue::where('id', $id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
            $actionFilesArray = App\MultiplecorrAction::where('id', $id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
            $issuedocs = $issue->file && $issue->file!="NULL" ? explode(',', $issue->file) : [];
            $actiondocs = $issue->action_file && $issue->action_file !="NULL" ? explode(',', $issue->action_file) : [];
            $issueFiles = array_unique(array_merge($issuedocs,$issueFilesArray));
            $actionFiles = array_unique(array_merge($actionFilesArray,$actiondocs));
            $issue_reviewers = Issuereviewer::pluck('email')->toArray();
            $responseData['view'] = \view('user.corrective._investigation', compact(
                'departments', 'users', 'models', 'issue', 'rr', 'rootcorrectives','issueFiles', 'actionFiles','issue_reviewers','users_text','departments_text'
            ))->render();
            $responseData['id'] = $issue->id;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Display the investigation team modification form
     *
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function changeInvestigationTeam($id): JsonResponse
    {
        $responseData = [];

        try {
            $models = DB::table('modenadisable')->first();
            $issue = Corrective::with('correctiveInvestigators')->findOrFail($id);
            $users = User::where('approved', true)->get();
            $rr = $issue->id;
            $responseData['success'] = true;
            $responseData['view'] = \view('user.corrective._change_investigation_team', compact(
                'users', 'models', 'issue', 'rr'
            ))->render();
            $responseData['id'] = $issue->id;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
    public function deleteIssueAttachment($id,Request $request) : JsonResponse {
        $responseData = [];
        try {
            $filename = $request->input('filename');
            $issueFiles = Corrective::where('id',$id)->value('file');
            if($issueFiles && $issueFiles!="NULL"){
                $issueFilesArray = explode(',',$issueFiles);
                $remainFilesArray = array_filter($issueFilesArray, function($file) use ($filename) {
                    return $file !== $filename;
                });
                $remainFiles = implode(',', $remainFilesArray);
                Corrective::where('id',$id)->update([
                    'file' => $remainFiles ? $remainFiles : NULL,
                    'updated_at' => now()
                ]);
            }
            App\MultiplecorrIssue::where('id', $id)->where('issue_file',$filename)->delete();
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
    public function deleteActionAttachment($id,Request $request) : JsonResponse {
        $responseData = [];
        try {
            $filename = $request->input('filename');
            $actionFiles = Corrective::where('id',$id)->value('action_file');
            if($actionFiles && $actionFiles!="NULL"){
                $actionFilesArray = explode(',',$actionFiles);
                $remainFilesArray = array_filter($actionFilesArray, function($file) use ($filename) {
                    return $file !== $filename;
                });
                $remainFiles = implode(',', $remainFilesArray);
                Corrective::where('id',$id)->update([
                    'action_file' => $remainFiles? $remainFiles : NULL,
                    'updated_at' => now()
                ]);
            }
            App\MultiplecorrAction::where('id', $id)->where('action_file',$filename)->delete();
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }
        return response()->json($responseData);
    }

    /**
     * Display the investigation report
     *
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function displayInvestigationReport($id): JsonResponse
    {
        $responseData = [];

        try {
            $models = DB::table('modenadisable')->first();
            $corrective = Corrective::where('id', $id)
                ->select('occurrence', 'system_related_problems', 'recent_changes', 'follow_procedures', 'contributing_factors', 'contributing_factors_details',
                         'external_factors', 'external_factors_details', 'issue_foreseeable', 'prevent_reason', 'issueid', 'id')
                ->first();
            $responseData['success'] = true;
            $responseData['view'] = \view('user.corrective._investigation_report', compact(
                'models', 'corrective'
            ))->render();
            $responseData['id'] = $corrective->id;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
    /**
     * Display the root cause table
     */
    private function getRootCauseTable(object $corrective): string
    {
        $rootcause = explode('$', $corrective->rootcause);
        $correctiveaction = explode('$', $corrective->correctiveaction);
        $responsibility = explode(',', $corrective->responsibility);
        $department = explode(',', $corrective->department);
        $target_date = explode(',', $corrective->target_date);
        $count = 1;
        $table = view('user.corrective._rootcause_table', compact(
            'rootcause',
            'department',
            'responsibility',
            'correctiveaction',
            'target_date',
            'corrective',
            'count'
        ))->render();

        // Return the HTML of root cause table
        return $table;
    }
     /**
     * Display the Issue docs and Action docs
     */
    private function getAttachments(object $corrective):string
    {
        $issueFiles = App\MultiplecorrIssue::where('id', $corrective->id)->where('issue_file','!=','NULL')->pluck('issue_file')->toArray();
        $actionFiles = App\MultiplecorrAction::where('id', $corrective->id)->where('action_file','!=','NULL')->pluck('action_file')->toArray();
        $issuedocs = $corrective->file && $corrective->file!="NULL" ? explode(',', $corrective->file) : [];
        $actiondocs = $corrective->action_file && $corrective->action_file !="NULL" ? explode(',', $corrective->action_file) : [];
        $issuedocs = array_unique(array_merge($issuedocs,$issueFiles));
        $actiondocs = array_unique(array_merge($actionFiles,$actiondocs));
        $issue_reviewers = Issuereviewer::pluck('email')->toArray();
        $attachments = view('user.corrective._attachments', compact(
            'actiondocs','issuedocs','issue_reviewers','corrective'
        ))->render();

        // Return the HTML in a JSON response
        return $attachments;
    }
    /**
     * Display the process stage
     */
    private function getProcessStage(object $corrective):string
    {
        $processStage = view('user.corrective._process_stage', compact(
            'corrective'
        ))->render();

        // Return the HTML in a JSON response
        return $processStage;
    }
    /**
     * Display the NCR fields data in detail page
     */
    public function getPreviousNcrDetails(Request $request){
        if ($request->id) {
            $item = Corrective::find($request->id);
            return response()->json(['item' => $item]);
        }
        $query = Corrective::orderBy('title', 'asc');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%')
            ->orWhere('issueid', 'like', '%' . $request->input('search') . '%');
        }
        $correctives = $query->select('id', 'issueid','title')->get();
        $addOn = new Corrective();
        $addOn->id = '0';
        $addOn->title = 'Other';
        $correctives->push($addOn);
        return response()->json([
            'items' => $correctives->toArray(),
            'hasMore' => false
        ]);
    }
    public function exportData(string $format, string $tab='open')
    {
        $clientDetails = ClientDetail::first();
        $ids = json_decode($_GET['ids'], true);
        $relationData = [
            'correctionDept', 'division', 'area', 'teamLead','ncrFieldValueRelation','processNcrFieldValueRelation',
            'postedByCorrective', 'correctiveInvestigators', 'investigators', 'cat','vendorNcrFieldValueRelation',
            'approvedByCorrective', 'closedByCorrective', 'actionReviewedByCorrective', 'typ'
        ];

        $vendorsCollection = Vendor::select(['id', 'vendor_name'])
            ->orderBy('vendor_name', 'asc')
            ->get();
        $base = Corrective::with($relationData)->select($this->selectColumn)->whereIn('id',$ids);

        //-------------------- COLUMN CONFIG --------------------//
        $productColumnsDetails = $this->getCategoryBasedColumn('product ncr');
        $processColumnsDetails = $this->getCategoryBasedColumn('process ncr');
        $vendorColumnsDetails = $this->getCategoryBasedColumn('vendor ncr');

        //-------------------- FINAL DATA --------------------//
        $tableDetails = $this->getConfigurationTableDetails(
            $productColumnsDetails,
            $processColumnsDetails,
            $vendorColumnsDetails
        );
        $commanData = [
            'productUserPrefs' => [
                'open' => $productColumnsDetails['open']['userPrefs'],
                'my_open' => $productColumnsDetails['my_open']['userPrefs'],
                'closed' => $productColumnsDetails['closed']['userPrefs'],
            ],
            'processUserPrefs' => [
                'open' => $processColumnsDetails['open']['userPrefs'],
                'my_open' => $processColumnsDetails['my_open']['userPrefs'],
                'closed' => $processColumnsDetails['closed']['userPrefs'],
            ],
            'vendorUserPrefs' => [
                'open' => $vendorColumnsDetails['open']['userPrefs'],
                'my_open' => $vendorColumnsDetails['my_open']['userPrefs'],
                'closed' => $vendorColumnsDetails['closed']['userPrefs'],
            ],
            'vendors' => $vendorsCollection->pluck('vendor_name','id')->toArray(),
            'open' => collect(),
            'myopen' => collect(),
            'closed' => collect(),
            'paper' => 'a4',
            'orientation' => 'P',
            'tableDetails' => $tableDetails,
            'userForResponsibility' => User::all(),
            'clientDetails' => $clientDetails,
        ];
        switch($tab){
            case "open" :
                $count = count($tableDetails['open']['columns_active']);
                if($count>23){
                    $commanData['paper'] = 'a1';
                    $commanData['orientation'] = 'P';
                }else{
                    $commanData['paper'] = $count > 17 ? 'a2' : ($count > 10 ? 'a3': 'a4');
                    $commanData['orientation'] = $count < 5 ? 'P' : 'L';
                }
                $entryReviewIssues = (clone $base)
                    ->where(function ($q) {
                       $q->where('status', 'open')
                        ->orWhereNull('status')
                        ->orWhere('status', 'NULL');
                    })
                    ->where('processstage', 'Entry Under Review')
                    ->orderBy('created_at', 'desc')
                    ->get();

                $remainingIssues = (clone $base)
                    ->where(function ($q) {
                        $q->where('status', 'open')
                        ->orWhereNull('status')
                        ->orWhere('status', 'NULL');
                    })
                    ->where('processstage', '!=', 'Entry Under Review')
                    ->orderBy('created_at', 'desc')
                    ->get();

                $commanData['open'] = $entryReviewIssues->merge($remainingIssues);
                if($format=="pdf"){
                    return $this->generatePdf($commanData,$tab);
                }elseif($format=="excel"){
                    return $this->generateExcel($commanData,$tab);
                }elseif($format=="word"){
                    return $this->generateWord($commanData,$tab);
                }
            break;
            case "my_open" :
                $count = count($tableDetails['my_open']['columns_active']);
                if($count>23){
                    $commanData['paper'] = 'a1';
                    $commanData['orientation'] = 'P';
                }else{
                    $commanData['paper'] = $count > 17 ? 'a2' : ($count > 10 ? 'a3': 'a4');
                    $commanData['orientation'] = $count < 5 ? 'P' : 'L';
                }
                $commanData['my_open'] = $base->orderBy('created_at', 'desc')->get();
                if($format=="pdf"){
                    return $this->generatePdf($commanData,$tab);
                }elseif($format=="excel"){
                    return $this->generateExcel($commanData,$tab);
                }elseif($format=="word"){
                    return $this->generateWord($commanData,$tab);
                }
            break;
            case "closed" :
               $count = count($tableDetails['closed']['columns_active']);
                if($count>23){
                    $commanData['paper'] = 'a1';
                    $commanData['orientation'] = 'P';
                }else{
                    $commanData['paper'] = $count > 17 ? 'a2' : ($count > 10 ? 'a3': 'a4');
                    $commanData['orientation'] = $count < 5 ? 'P' : 'L';
                }
                $commanData['closed'] = $base->orderBy('created_at', 'desc')->get();
                if($format=="pdf"){
                    return $this->generatePdf($commanData,$tab);
                }elseif($format=="excel"){
                    return $this->generateExcel($commanData,$tab);
                }elseif($format=="word"){
                    return $this->generateWord($commanData,$tab);
                }
            break;
            default:
            break;
        }
    }
    private function generatePdf($commanData,$tab){
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        $name = $tab.'_ncr_and_corrective_actions.pdf';
        ini_set('pcre.backtrack_limit', '50000000');
        ini_set('pcre.recursion_limit', '50000000');

        $tempPath = storage_path('app/mpdf');
        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true, true);
        }
        $mpdf = new Mpdf([
            'orientation' => $commanData['orientation'],      // Landscape
            'format'      => $commanData['paper'],
            'tempDir'     => $tempPath,
            'margin_top' => 30,
        ]);
        $html = view('exporting.forcorrective', compact('commanData','tab'))->render();
        $headerHtml = view('user.pdf_header', compact('commanData'))->render();
        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetFooter('{PAGENO} of {nbpg}');
        if($commanData['clientDetails'] && $commanData['clientDetails']->client_logo){
            $mpdf->SetWatermarkImage(public_path('storage/clientlogos/'.$commanData['clientDetails']->client_logo), 0.1);
        }
        $mpdf->showWatermarkImage = true;
        $mpdf->WriteHTML($html);
        return $mpdf->Output($name, 'D');
    }
    private function generateExcel($commanData,$tab){
        if (ob_get_length()) ob_end_clean();
        return Excel::download(
            new CorrectiveExport(
                $commanData,
                $tab,
                public_path('storage/clientlogos/'.$commanData['clientDetails']->client_logo)
            ),
            $tab.'_ncr_and_corrective_actions.xlsx'
        );
    }
    private function generateWord($commanData,$tab){
        if (ob_get_length()) ob_end_clean();
        $export = new CorrectiveWordExport($commanData,$tab);
        $filePath = $export->generate();
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
