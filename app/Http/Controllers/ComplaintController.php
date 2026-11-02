<?php
namespace App\Http\Controllers;

use App\AuditRootCauseCorrectiveActionDetail;
use App\Models\AppConfiguration;
use App\Constants\Constants;
use App\Complaintfile;
use App\Complaintreviewer;
use App\Country;
use App\Customer;
use App\ClientDetail;
use App\Departments;
use App\EmployeeDetails;
use App\ComplaintRootCauseCorrectiveActionDetail;
use App\Http\Controllers\Controller;
use App\Mail\ComplaintAddIssue;
use App\Mail\ComplaintIssueDisapproved;
use App\Mail\ComplaintIssueApproved;
use App\Mail\ComplaintIssueAwaitingApproval;
use App\Mail\ComplaintIssueClosed;
use App\Mail\ComplaintIssueResubmitNotification;
use App\Mail\ComplaintResponseNotification;
use App\Mail\ComplaintResubmitIssue;
use App\Mail\ComplaintReviewStatusFoundNotOK;
use App\Mail\ComplaintReviewStatusFoundOK;
use App\Mail\ComplaintUpdated;
use App\Mail\ComplaintTeamMembers;
use App\Mail\ComplaintInvestigate;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Product;
use App\Rules\CommaSeparatedEmailsRule;
use App\State;
use App\Type;
use App\User;
use App\ViewActivity;
use App\Issuereviewer;
use App\ComplaintPlant;
use App\ComplaintCategory;
use App\ComplaintInvestigator;
use App\ComplaintProductDetail;
use App\Complaint;
use App\ComplaintUnit;
use App\ComplaintActionFile;
use App\ComplaintIssueFile;
use Auth;
use Excel;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Imports\CustomersImport;
use Illuminate\Http\JsonResponse;
use Session;
use App\Http\Requests\Complaint\SubmitInvestigationFormRequest;
use App\ModuleRegisterPageDetail;
use App\ModuleClosedPageDetail;
use App\Division;
use App\Area;
use App\Vendor;
use App\Models\ComplaintMeta;
use App\Models\SourceOfIssue;
use App\Helpers\Complaint\ComplaintFormConfigurationHelper;
use App\Helpers\Complaint\ComplaintHelper;
use App\Helpers\Complaint\ComplaintExportHelper;

class ComplaintController extends Controller
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
    public function add()
    {
        $employees = EmployeeDetails::where('is_deleted',false)->get();
        $issue_reviewers = Complaintreviewer::pluck('email')->toArray();
        //$departments = Departments::all();
        $departments = Departments::where('is_active',true)->get();
        $user = User::where('is_email_verified',true)->orderBy('name')->get();
        $product = Product::get();
        $plants = ComplaintPlant::where('is_deleted',false)->get();
        $complaint_cat = ComplaintCategory::where('is_deleted',false)->get();
        $countries = Country::all();
        $states = State::all();
        $units = ComplaintUnit::where('is_deleted',false)->get();
        $models = DB::table('modenadisable')->First();
        $activeComplaintTemplate = ComplaintFormConfigurationHelper::getActiveComplaintTemplate();
        $complaintTemplateSectionFields = ComplaintFormConfigurationHelper::getComplaintTemplateSectionsFields();
        $category_plant_dependency = AppConfiguration::where('meta_key', 'complaint_category_plant_dependency')->first();
        $vendors = Vendor::where('is_deleted', false)->get();

        return view('user.complaint.add')
            ->with(['product' => $product,
                'departments' => $departments,
                'user' => $user, 'model' => $models, 'employees' => $employees,
                'issue_reviewers' =>$issue_reviewers,
                'plants' => $plants, 'complaint_cat' => $complaint_cat,
                'countries' => $countries, 'states' => $states,
                'units' => $units, 'complaintTemplateSectionFields' => $complaintTemplateSectionFields,
                'activeComplaintTemplate' => $activeComplaintTemplate,
                'category_plant_dependency' => $category_plant_dependency,
                'vendors' => $vendors
            ]);
    }

    public function getDataFromTableAjax(Request $request){
        $table = $request->table;
        $value_column = $request->value_column;
        $text_column = $request->text_column;
        $sort_column = $request->sort_column;
        $sort_direction = $request->sort_direction;
        $search = $request->search;

        $data = [];
        if ($table) {
            $query = DB::table($table)->select('*');

            if ($search) {
                $query->where($text_column, 'like', '%' . $search . '%');
            }

            if ($sort_column) {
                $query->orderBy($sort_column, $sort_direction ?? 'asc');
            }

            $data = $query->limit(100)->get();
        }

        return response()->json([
            'status' => true,
            'message' => 'Fetched data from table',
            'data' => $data
        ]);
    }

    public function getCustomerAjax(Request $req){
        $customerId = $req->input('id');
        $customer = Customer::where('id', $customerId)->first();

        $customerProductIds = explode(',', $customer->product);
        $products = Product::whereIn('id', $customerProductIds)->get();
        $products = count($products) > 0 ? $products : $customer->product;
        $remainproduct = Product::whereNotIn('id', $customerProductIds)->get();

        if ($customer) {
            return [
                "error" => null,
                "data" => $customer,
                "products" => $products,
                'remainproduct' => $remainproduct
            ];
            } else {
            return [
                "error" => "Customer not found",
                "data" => null,
                "products" => null,
                'remainproduct' => null,
            ];
        }
    }

    public function addCustomerAjax(Request $req){
        $validator = Validator::make($req->all(), [
            'customerajax' => 'required|string',
            'productajax' => 'required',
            'productajax.*' => 'required|string|exists:products,id',
            'location' => 'nullable|string|max:255',
            'country' => 'required',
            'state' => 'nullable|string',
            'pinajax' => 'required|string',
            'emailajax' => 'nullable|email',
            'phoneajax' => 'nullable|numeric',
            'other_country' => 'nullable|string',
            'other_state' => 'nullable|string',
            'customer_survey' => new CommaSeparatedEmailsRule,
        ], [
            'customerajax.required' => 'The customer name field is required.',
            'productajax.required' => 'The product/service field is required.',
            'productajax.*.required' => 'Each product/service field is required.',
            'productajax.*.exists' => 'The selected product/service is invalid.',
            'country.required' => 'The country field is required.',
            'country.exists' => 'The selected country is invalid.',
            'pinajax.required' => 'The Customer Code field is required',
            'emailajax.email' => 'The email must be a valid email address.',
        ]);
        if ($validator->fails()) {

            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if($error==null){
            $customer = $req->input('customerajax');
            $location = $req->input('location');
            $product = $req->input('productajax');
            $email = $req->input('emailajax');
            $phone = $req->input('phoneajax');
            $country = $req->input('country');
            $state = $req->input('state');
            $pin = $req->input('pinajax');
            $customer_survey = $req->input('customer_survey');
            $other_country = $req->input('other_country');
            $other_state = $req->input('other_state');
            $country = $country=='0' ? Country::where('country_name',$other_country)->value('id') : '0';
            $other_country = $country =='0' ? $other_country : null;
            $stateid = State::where('state_name',$other_state)->where('country_id',$country)->value('id');
            if($stateid){
                $state = $stateid;
                $other_state = null;
            }
            if($country==null){
                $country='0';
            }
            $data = [
                'customer' => $customer,
                'location' => $location,
                'product' => $product,
                'email' => $email,
                'phone' => $phone,
                'country_id' => $country,
                'state_id' => $state,
                'pin' => $pin,
                'other_country' => $other_country,
                'other_state' => $other_state,
                'customer_survey_emails' => $customer_survey,
                'updated_at' => now(),
            ];
            if($country=="0"){
                $check = Customer::where('pin', $pin)->where('customer',$customer)->where('other_country',$other_country)->where('is_deleted',false)->first();
            }else{
                $check = Customer::where('pin', $pin)->where('customer',$customer)->where('country_id',$country)->where('is_deleted',false)->first();
            }
            if ($check) {
                return [
                    'error' => "This customer already exists.",
                ];
            } else {
                $data['created_at'] = now();
                Customer::create($data);
            }

            //below code related to superadmin
            //inserting values to activity table
            $activity = "New Customer  is Added with name as " . $customer . " with Location as " . $location;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
            // $models = DB::table('modenadisable')->First();
            // Session::flash('msg', "customer list added successfully");
        }
        return [
            'error' => $error,
        ];
    }

    public function addCustomerExcel(Request $request){
        $validator = Validator::make($request->all(), [
            'customer_details' => 'required|mimes:xls,xlsx',
        ]);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'model' => $models
            ]);
        } else {
            $rows = Excel::toArray(new CustomersImport, $request->file('customer_details'))[0];
            $row_number = 1;
            $total_records = count($rows);
            $countriesMap = Country::pluck('id', DB::raw('LOWER(country_name)'))->toArray();
            $customersMap = Customer::where('is_deleted', false)
                            ->get(['id', DB::raw('LOWER(customer) as customer'), 'country_id', DB::raw('LOWER(pin) as pin'), DB::raw('LOWER(other_country) as other_country')])
                            ->mapWithKeys(function ($item) {
                                $key = $item->pin . '|' . $item->customer . '|' . $item->country_id . '|' . $item->other_country;
                                return [$key => $item->id];
                            })
                            ->toArray();
            $statesMap = State::select('id', 'country_id', DB::raw('LOWER(state_name) as state_name'))->get()->groupBy('country_id')->map(function ($item) {
                return $item->pluck('id', 'state_name')->toArray();
            })->toArray();
            $insertData = [];
            if ($total_records > 0) {
                foreach ($rows as $row) {
                    ++$row_number;
                    if (empty($row['customer_name']) || empty($row['products']) || empty($row['country']) || empty($row['customer_code'])) {
                        $msg = null;
                        if(empty($row['customer_name'])){
                            $msg = 'Customer Name is empty in row '. $row_number.'. Please refer Note for excel upload format.';
                        }elseif(empty($row['products'])){
                            $msg = 'Plant Name is empty in row '. $row_number.'. Please refer Note for excel upload format.';
                        }elseif(empty($row['country'])){
                            $msg = 'Country Name is empty in row '. $row_number.'. Please refer Note for excel upload format.';
                        }elseif(empty($row['customer_code'])){
                            $msg = 'Customer Code is empty '. $row_number.'. Please refer Note for excel upload format.';
                        }else{
                            $msg = 'Customer Name or Plant Name or Country Name or Customer Code data is empty in row '. $row_number.'. Please refer Note for excel upload format.';
                        }
                        return response()->json([
                            'success' => false,
                            'errors' => $msg
                        ]);
                    } else {
                        $customer_name = $row['customer_name'];
                        $country_name = strtolower($row['country']);
                        $country = $countriesMap[$country_name] ?? null;
                        $other_country = null;
                        $other_state = null;
                        if($country==null){
                            $other_country = $row['country'];
                            $country = '0';
                            $other_state = $row['state'];
                            $state = $row['state'] ? '0' : null;
                        }else{
                            $state_name = strtolower($row['state']);
                            $state= $statesMap[$country][$state_name] ?? null;;
                            if($state==null){
                                $state = $row['state'] ? '0' : null;
                                $other_state = $row['state'];
                            }
                        }
                        $location = $row['location'];
                        $customer_code = $row['customer_code'];
                        $key = strtolower($row['customer_code']) . '|' . strtolower($row['customer_name']) . '|' . $country . '|' . strtolower($other_country);
                        $check = $customersMap[$key] ?? null;
                        $email = filter_var($row['email'], FILTER_VALIDATE_EMAIL) ? $row['email'] : null;
                        $phone = $row['phone'];
                        $customer_email_addresses = $row['customer_email_addresses'];
                        $products = explode(',', $row['products']);
                        $trimmed_products = array_map('trim', $products);
                        $lowercase_products = array_map('strtolower', $trimmed_products);
                        $products_ids = Product::whereIn(DB::raw('LOWER(product)'), $lowercase_products)->pluck('id', DB::raw('LOWER(product)'))->toArray();
                        $missing_products = array_diff($lowercase_products, array_keys($products_ids));
                        if (!empty($missing_products)) {
                            $models = DB::table('modenadisable')->first();
                            return response()->json([
                                'success' => false,
                                'message' => 'The following product(s) in row ' . $row_number . ' are not present in the products/services table: ' . implode(', ', $missing_products) . '. Please correct the entry and try uploading again.',
                                'model' => $models
                            ]);
                        }
                        $products_ids_result = implode(',', $products_ids);
                        if ($check==null) {
                            $insertData[] = array(
                                'customer' => $customer_name,
                                'location' =>  $location,
                                'product' => $products_ids_result,
                                'country_id' => $country,
                                'state_id' => $state,
                                'pin' => $customer_code,
                                'email' => $email,
                                'phone' => $phone,
                                'other_country' => $other_country,
                                'other_state' => $other_state,
                                'customer_survey_emails' => $customer_email_addresses,
                                'is_deleted' => false,
                                'updated_at' => now(),
                                'created_at' => now()
                            );
                        }
                    }
                }
            }
            $uniqueInsertData = array_intersect_key($insertData, array_unique(array_map('serialize', array_map(function ($item) {
                return [$item['customer'], $item['country_id'], $item['other_country'],$item['pin']];
            }, $insertData))));
            if (!empty($uniqueInsertData)) {
                $chunks = array_chunk($uniqueInsertData, 2000);
                foreach ($chunks as $chunk) {
                    Customer::insert($chunk);
                }
            }
            if (!File::exists(public_path('/storage/complaint/'))) {
                File::makeDirectory(public_path() . '/storage/complaint/', 0755, true);
            }

            $name = 'customer_details' . '.' . $request->file('customer_details')->extension();
            $request->file('customer_details')->move(public_path() . '/storage/complaint/', $name);
            $models = DB::table('modenadisable')->First();
            return response()->json([
                'success' => true,
                'message' => 'Customer Details uploaded successfully.',
                'model' => $models
            ]);
        }
    }

    public function viewCustomerRequest(Request $req){
        $customerId = $req->input('id');
        $customer = Customer::find($customerId);
        $customerName = $customer->customer;
        $customerCode = $customer->pin ?? "--";
        $location = $customer->location ?? "--";
        $contact =  $customer->email ?? "--";
        $customerProductIds = explode(',', $customer->product);
        $products = Product::whereIn('id', $customerProductIds)->pluck('product')->toArray();
        $products = count($products) > 0 ? implode(',',$products) : $customer->product;
        $country = $customer->country_id !='0' ? Country::where('id',$customer->country_id)->value('country_name') : $customer->other_country;
        $state = $customer->state_id !='0' ? State::where('id',$customer->state_id)->where('country_id',$customer->country_id)->value('state_name') : $customer->other_state;
        $phone = $customer->phone ?? "--";
        $customer_survey_emails = $customer->customer_survey_emails ?? "--";
        if ($customer) {
            return [
                "error" => null,
                'customername' => $customerName,
                'customercode' => $customerCode,
                'location' => $location,
                'contact' => $contact,
                'products' => $products,
                'country' => $country,
                'state' => $state,
                'phone' => $phone,
                'customer_survey_emails' => $customer_survey_emails,
            ];
            } else {
            return [
                "error" => "Customer not found",
                "data" => null,
            ];
        }
    }

    public function insertcomplaint(Request $req)
    {
        $dynamicFieldValidation =  ComplaintFormConfigurationHelper::getComplaintConfigurationPostMethodRules($req->complaint_template_section_field_rules);
        $staticFieldValidationRules = [
            'employee' => 'nullable|string',
            'crdate' => 'date|required',
            'modeofcomplaint' => 'string|nullable',
            'title' => 'required|string',
            'complaint' => 'string|required',
            'customerpin' => 'numeric|nullable',
            'customeremailforsurvey' => new CommaSeparatedEmailsRule,
            'type' =>  'string|nullable',
            'plant' => 'required|integer|exists:complaint_plants,id',
            'meta.warranty_coverage' => [
            'required_if:active_template,Project Template',\Illuminate\Validation\Rule::in(
                collect(Constants::WARRANTY_COVERAGES)->pluck('title')->toArray()
            )],
            'meta.complaint_category_id' => 'required_if:active_template,Project Template|nullable|exists:complaint_categories,id',
            'meta.vendor_id' => 'nullable|exists:vendor,id',
            'meta.no_of_occurrence' => 'nullable|integer|min:1',
            'department' => 'string|required|exists:departments,id',
            'responsibility' => 'string|required|exists:users,email',
            'action' => 'string|required',
            'crdate' => 'date|nullable',
            'lead' => 'required_if:seperate,yes',
            'financial_loss' => 'nullable|string|max:32',
            'potential_cost_savings' => 'nullable|string|max:32',
            'investigation_date' => 'required_if:seperate,yes',
        ];
        $rules = array_merge($staticFieldValidationRules, $dynamicFieldValidation['rules']);

        $staticFieldValidationMessages = [
            'title.required' => 'The title field is required',
            'complaint.required' => 'The Description field is required',
            'crdate.required' => 'The Complaint Received Date field is required',
            'plant.required' => 'The Plant field is required',
            'departmentname.required' => 'The Responsible Department field is required',
            'responsibility.required' => 'The Responsibility field is required',
            'action.required' => 'The Recommended Action field is required',
            'lead.required_if' => 'The Team leader field is mandatory, if investigation required',
            'investigation_date.required_if' => 'The investigation date is mandatory, if investigation required',
            'meta.no_of_occurrence.min' => 'Number of occurrences must be at least 1.',
            'meta.warranty_coverage.required' => 'Warranty Coverage should Select'
        ];
        $message = array_merge($staticFieldValidationMessages, $dynamicFieldValidation['messages']);

        $validator = Validator::make($req->all(), $rules,$message);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)->withInput()->with('model', $models);
        } else {
            $addedby = Auth::user()->employeeid;
            $title = $req->input('title');
            $complaint = $req->input('complaint');
            $customer = $req->input('customer');
            $product = $req->input('product') ? implode(',',$req->input('product')) : null;
            $exist = DB::table('complaint')->orderBy('id', 'desc')->first();

            $type = $req->input('type');
            $action = $req->input('action');
            $department = $req->input('department');
            $division = $req->input('division');
            $area = $req->input('area');
            $responsibility = $req->input('responsibility');
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

            $date = $req->input('date');
            $crdate = $req->input('crdate');
            $employee = $req->input('employee');
            $createdat = date('d-m-Y h:i:s A', time());
            $reviewers = DB::table('complaintreviewers')->get();
            $representatives = DB::table('representatives')->get();
            $modeofcomplaint = $req->input('modeofcomplaint');
            $plant = $req->input('plant') ?? null;
            $customerphone = $req->input('customerphone');
            $customeremail = $req->input('customeremail');
            $customeraddress = $req->input('customeraddress');
            $saleorder = $req->input('saleorder');
            $deliverydate = $req->input('deliverydate');
            $deliveryno = $req->input('deliveryno');
            $lead = $req->input('lead');
            $investigation = $req->input('investigation') ? implode(',',$req->input('investigation')) : null;
            $investigation_date = $req->input('investigation_date');
            $financial_loss = $req->input('financial_loss');
            $potential_cost_savings = $req->input('potential_cost_savings');
            $list = collect([]);
            foreach ($reviewers as $reviewer) {
                $list = $list->push($reviewer->email);
            }
            $auditors = $list->unique();
            foreach ($auditors as $a) {
                if (Auth::user('id')->email == $a) {
                    $processstage = "Approved";
                    $approvedby = Auth::user()->employeeid;
                    $approvedat = now();
                    break;
                }
                if (Auth::user('id')->email != $a) {
                    $processstage = "Under Review";
                    $approvedby = null;
                    $approvedat = null;
                }
            }

            $data = [
                'addedby' => $addedby,
                'title' => $title,
                'complaint' => $complaint,
                'status' => $processstage,
                'actionstatus' => "Not Started-0%",
                'customer' => $customer ?? null,
                'product' => $product ?? null,
                'trackno' => $track = Complaint::generateComplaintId(),
                'type' => $type,
                'action' => $action,
                'dept' => $department,
                'responsibility' => $responsibility,
                'eremainder' => $frequency,
                'sendnotif' => $notification,
                // 'createdat' => date('d-m-Y h:i:s A', time()),
                'tardate' => $date,
                'crdate' => $crdate,
                'employee' => $employee,
                'approved_by' => $approvedby,
                'approved_at' => $approvedat,
                'created_at' => now(),
                'updated_at' => now(),
                'mode_of_complaint' => $modeofcomplaint,
                'plant' => $plant,
                'customer_phone' => $customerphone ?? null,
                'customer_email' => $customeremail ?? null,
                'customer_address' => $customeraddress ?? null,
                'sale_order_no' => $saleorder ?? null,
                'delivery_date' => $deliverydate ?? null,
                'delivery_no' => $deliveryno ?? null,
                'job_order_no' => $req->input('job_order_no') ?? null,
                'product_serial_no' => $req->input('product_serial_no') ?? null,
                'project_name' => $req->input('project_name') ?? null,
                'job_history' => $req->input('job_history') ?? null,
                'complaint_template_id' => $req->input('complaint_template_id') ?? null,
                'complaint_template_section_field_rules' => $req->input('complaint_template_section_field_rules') ?? null,
                'team_lead_id' => $lead,
                'investigation_date' => $investigation_date,
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
                'division_id' => $division,
                'area_id' => $area,
            ];
            // DB::table('complaint')->insert($data); prevoiusly insert function was like this
            $complaintId = DB::table('complaint')->insertGetId($data); //later i have changed to this to get id

            $meta = $req->input('meta');
            $metaData = [];
            foreach ($meta as $key => $value) {
                if (!is_null($value)) {
                    $metaData[] = [
                        'complaint_id' => $complaintId,
                        'meta_key' => $key,
                        'meta_value' => $value,
                    ];
                }
            }
            $complaint = Complaint::find($complaintId);
            $complaint->complaintMeta()->createMany($metaData);

            $itemcodes = $req->input('itemcode');
            $productdesc = $req->input('productdesc');
            $idno = $req->input('idno');
            $totalquant = $req->input('totalquant');
            $affectquant = $req->input('affectquant');
            $unit = $req->input('unit');
            $costimpact = $req->input('costimpact');
            $category = $req->input('complaintcategory');
            // $collection = collect($category);
            // $reindexedCollection = $collection->values();
            // $category = $reindexedCollection->toArray();
            $srr = $req->input('salereturnreq');
            $rr = $req->input('reworkreq');
            $sizeofitem = $req->input('sizeofitem');
            if ($itemcodes && count($itemcodes)) {
                $totalcodes = count($itemcodes);
                $complaint = Complaint::orderBy('id', 'desc')->first();
                for ($i=0;$i<$totalcodes;$i++) {
                    if ($complaint) {
                        ComplaintProductDetail::insert([
                            'complaint_id' => $complaint->id,
                            'item_code' => $itemcodes[$i] ?? null,
                            'product_description' =>$productdesc[$i] ?? null,
                            'size_of_item' => $sizeofitem[$i] ?? null,
                            'idno' => $idno[$i] ?? null,
                            'total_delivery_quantity' => $totalquant[$i] ?? null,
                            'affected_quantity' => $affectquant[$i] ?? null,
                            'unit' =>  $unit[$i] ?? null,
                            'cost_impact' => $costimpact[$i] ?? null,
                            'complaint_category' => $category && isset($category[$i+1]) ? implode(',',$category[$i+1]) : null,
                            'sale_return_request' => $srr[$i] ?? null,
                            'rework_request' => $rr[$i] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            $investigators = $req->input('investigation');
            if ($investigators && count($investigators)) {

                foreach ($investigators as $investigator) {
                    $complaint = Complaint::orderBy('id', 'desc')->first();
                    if ($complaint && $investigator) {
                        $found = ComplaintInvestigator::where('user_id', $investigator)->where('complaint_id', $complaint->id)->first();
                        if (!$found) {
                            ComplaintInvestigator::insert([
                                'user_id' => $investigator,
                                'complaint_id' => $complaint->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                        }

                    }
                }
            }
            $send = array('complaint' => $complaint, 'title' => $title, 'customer' => $customer, 'product' => $product,
                'trackno' => $track, 'dept' => $department);
            $activity = "Inserted new Complaint with Name as " . $title . " and with customer named " . $customer;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            $d = Complaint::orderBy('id', 'desc')->first();
            $id = $d->id;
            $destination_path = public_path('storage/complaint');
            if ($req->hasFile('file')) {
                $file_array = $req->file('file');
                $insertData = [];
                foreach ($file_array as $file) {
                    $new_file_name = $file->getClientOriginalName();
                    $file->move($destination_path, $new_file_name);
                    $insertData[] = [
                        'complaint_id' => $id,
                        'issue_file' => $new_file_name,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                ComplaintIssueFile::insert($insertData);
            }
            $m = 0;
            $departmentname = Departments::where('id',$department)->value('departmentname');
            $date = $req->input('date');
            foreach ($auditors as $a) {
                if (Auth::user()->email == $a) {
                    $com_add_emails = EmailNotification::where('module', 'complaintmanagement')
                        ->where('item', 'Complaint Issue Added')
                        ->where('is_blocked', false)
                        ->first();
                    if($com_add_emails) {
                        $emaildata = array(
                            'email_content' => $com_add_emails->email_content,
                            'item' => $com_add_emails->item,
                            'trackno' => $track,
                            'addedby' => Auth::user()->name,
                            'type' => $type,
                            'dept' => $departmentname,
                            'tardate' => $date ? date("d/m/Y", strtotime($date)) : '',
                        );
                        $noti = $notification ? getNonDeletedUsersEmail(explode(',', $notification)) : [];
                        if (count($noti)>0) {
                            Mail::to($noti)->send(new ComplaintAddIssue($emaildata));
                        }
                        $com_add_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_add_emails->id)->get();
                        if (count($com_add_userdatas) > 0) {
                            $adminaddedusers = $com_add_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas) > 0) {
                                Mail::to($userdatas)->send(new ComplaintAddIssue($emaildata));
                            }
                        }
                    } else {
                        Session::flash('err', 'Mail Sending Blocked for Complaint Add Issue');
                    }
                    $com_res_emails = EmailNotification::where('module', 'complaintmanagement')
                        ->where('item', 'Complaint Response Notification')
                        ->where('is_blocked', false)
                        ->first();
                    if($com_res_emails) {
                        $emaildata = array(
                            'email_content' => $com_res_emails->email_content,
                            'item' => $com_res_emails->item,
                            'trackno' => $track,
                            'addedby' => Auth::user()->name,
                            'type' => $type,
                            'dept' => $departmentname,
                            'tardate' => $date ? date("d/m/Y", strtotime($date)) : '',
                        );
                        $responsibility = $responsibility != "" && $responsibility != null ? getNonDeletedUsersEmail($responsibility): [];
                        if (count($responsibility)>0) {
                            Mail::to($responsibility)->send(new ComplaintResponseNotification($emaildata));
                        }
                        //sending mail for participants and management participants
                        $com_res_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_res_emails->id)->get();
                        if (count($com_res_userdatas) > 0) {
                            $adminaddedusers = $com_res_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas) > 0) {
                                Mail::to($userdatas)->send(new ComplaintResponseNotification($emaildata));
                            }
                        }
                    } else {
                        Session::flash('err', 'Mail Sending Blocked for Complaint Response Notification');
                    }

                    $m = 1;
                }
            }
            if ($m == 0) {
                $com_app_emails = EmailNotification::where('module', 'complaintmanagement')
                    ->where('item', 'Complaint Issue Awaiting Approval')
                    ->where('is_blocked', false)
                    ->first();
                if ($com_app_emails) {
                    $emaildata = array(
                        'email_content' => $com_app_emails->email_content,
                        'item' => $com_app_emails->item,
                        'trackno' => $track,
                        'addedby' => Auth::user()->name,
                        'type' => $type,
                        'dept' => $departmentname,
                        'tardate' => $date ? date("d/m/Y", strtotime($date)) : '',
                    );
                    $emails = getNonDeletedUsersEmail(Complaintreviewer::pluck('email')->toArray());
                    if (count($emails)>0) {
                        Mail::to($emails)->send(new ComplaintIssueAwaitingApproval($emaildata));
                    }
                    //sending mail for participants and management participants
                    $com_app_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_app_emails->id)->get();
                    if (count($com_app_userdatas) > 0) {
                        $adminaddedusers = $com_app_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas) > 0) {
                            Mail::to($userdatas)->send(new ComplaintIssueAwaitingApproval($emaildata));
                        }
                    }
                } else {
                    Session::flash('err', 'Mail Sending Blocked for Complaint Issue Awaiting Approval');
                }
            }
            // mail all the team members and team leader
            $com_inves_emails = EmailNotification::where('module', 'complaintmanagement')
                ->where('item', 'Complaint Investigate')
                ->where('is_blocked', false)
                ->first();
            if ($com_inves_emails) {
                $emaildata = array(
                    'email_content' => $com_inves_emails->email_content,
                    'item' => $com_inves_emails->item,
                    'trackno' => $track,
                    'addedby' => Auth::user()->name,
                    'type' => $type,
                    'dept' => $departmentname,
                    'tardate' => $investigation_date ? date("d/m/Y", strtotime($investigation_date)) : '',
                );
                if ($lead) {
                    $leaderemail = User::where('id', $lead)->value('email');
                    if ($leaderemail != "") {
                        Mail::to($leaderemail)->send(new ComplaintInvestigate($emaildata));
                    }
                }
                //sending mail for participants and management participants
                $com_inves_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_inves_emails->id)->get();
                if (count($com_inves_userdatas) > 0) {
                    $adminaddedusers = $com_inves_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new ComplaintInvestigate($emaildata));
                    }
                }
            } else {
                Session::flash('err', 'Mail Sending Blocked for Complaint Issue Investigator');
            }
            $com_member_emails = EmailNotification::where('module', 'complaintmanagement')
                ->where('item', 'Complaint Team Members')
                ->where('is_blocked', false)
                ->first();

            if ($com_member_emails) {
                $datateam = array(
                    'email_content' => $com_member_emails->email_content,
                    'item' => $com_member_emails->item,
                    'trackno' => $track,
                    'addedby' => Auth::user()->name,
                    'type' => $type,
                    'dept' => $departmentname,
                    'tardate' => $investigation_date ? date("d/m/Y", strtotime($investigation_date)) : '',
                );
                if ($investigators) {
                    //mail to team members
                    $team_members = User::whereIn('id',$investigators)->pluck('email');
                    Mail::to($team_members)->send(new ComplaintTeamMembers($datateam));
                }
                $com_member_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_member_emails->id)->get();
                if (count($com_member_userdatas) > 0) {
                    $adminaddedusers = $com_member_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new ComplaintTeamMembers($datateam));
                    }
                }

            } else {
                Session::flash('err', 'Mail Sending Blocked for Complaint Issue Team Members');
            }
            Session::flash('msg', "Complaint sent for approval successfully");
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }

    public function getPreviousComplaints(Request $request)
    {
        $template_id = $request->template_id;

        $query = Complaint::orderBy('trackno', 'asc');

        if ($template_id == 1) {
            $query->where(function ($qu) use ($template_id) {
                $qu->where('complaint_template_id', $template_id)
                ->orWhereNull('complaint_template_id');
            });
        } elseif ($template_id == 2) {
            $query->where('complaint_template_id', $template_id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('trackno', 'like', '%' . $search . '%');
            });
        }

        $complaints = $query->select('id', 'trackno','title')->get();

        $addOn = new Complaint();
        $addOn->id = '0';
        $addOn->title = 'Other';
        $complaints->push($addOn);
        return response()->json([
            'items' => $complaints->toArray(),
            'hasMore' => false
        ]);

    }
    //add customer
    public function addcustomer()
    {
        $product = DB::table('products')->get();
        $countries = Country::all();
        $states = State::all();

        //below code related to superadmin
        $models = DB::table('modenadisable')->first();

        return view('user.complaint.addcustomer', compact('product', 'countries', 'states'))->with('model', $models);
    }

    public function insertcustomer(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'customer' => 'required',
            'product' => 'required|array',
            'product.*' => 'required|string|exists:products,id',
            'location' => 'nullable|string|max:255',
            'country' => 'required',
            'state' => 'nullable|string',
            'pin' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
            'other_country' => 'nullable|string',
            'other_state' => 'nullable|string',
            'customer_survey' => new CommaSeparatedEmailsRule,
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {

            $customer = $req->input('customer');
            $location = $req->input('location');
            $product = $req->input('product');
            $product = $product ? implode(',', $product) : null;
            $email = $req->input('email');
            $phone = $req->input('phone');
            $country = $req->input('country');
            $other_country = $req->input('other_country');
            $other_state = $req->input('other_state');
            $state = $req->input('state');
            $pin = $req->input('pin');
            $customer_survey = $req->input('customer_survey');
            $ID = Auth::user()->employeeid;
            //check country exist or not
            if($country=='0'){
                $country = Country::where('country_name',$other_country)->value('id');
                if($country==null){
                    $country='0';
                }
            }
            $other_country = $country =='0' ? $other_country : null;
            if($state=='0'){
                $stateid = State::where('state_name',$other_state)->where('country_id',$country)->value('id');
                if($stateid){
                    $state = $stateid;
                    $other_state = null;
                }
            }
            $data = [
                'customer' => $customer,
                'location' => $location,
                //'contact' =>  $contact,
                'product' => $product,
                'email' => $email,
                'phone' => $phone,
                'country_id' => $country,
                'state_id' => $state,
                'pin' => $pin,
                'other_country' => $other_country,
                'other_state' => $other_state,
                'customer_survey_emails' => $customer_survey,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if($country=="0"){
                $check = Customer::where('pin', $pin)->where('customer',$customer)->where('other_country',$other_country)->where('is_deleted',false)->first();
            }else{
                $check = Customer::where('pin', $pin)->where('customer',$customer)->where('country_id',$country)->where('is_deleted',false)->first();
            }
            $models = DB::table('modenadisable')->First();
            if ($check) {
                Session::flash('err', "This customer already exists");
                return redirect()->back()->withInput();
            } else {
                $data['created_at'] = now();
                Customer::create($data);
            }
            //below code related to superadmin
            //inserting values to activity table
            $activity = "New Customer  is Added with name as " . $customer . " with Location as " . $location;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            //end of superadmin code
            Session::flash('msg', "customer details added successfully");

            return redirect()->back()->with('model', $models);
        }
    }
    //category ajax
    public function getCategoryAjax(Request $req){
        $plantId = $req->input('id');
        if ($plantId) {
            $cat = ComplaintCategory::where('plant_id', $plantId)->where('is_deleted',false)->get();
        } else {
            $cat = ComplaintCategory::where('is_deleted',false)->get();
        }
        if ($cat) {
            return [
                "error" => null,
                "data" => $cat,
            ];
        } else {
            return [
                "error" => "Category not found",
                "data" => null,
            ];
        }
    }
    //complaint register
    public function register()
    {
        //below is superadmin related codes
        $complaintmanagement = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($complaintmanagement) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 1, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0, 'contextmanagement' => 0, 'complaintmanagement' => 1]);
        } else {
            $complaintmanagement = DB::table('modpageload')->where('id', 1)->First();
            $complaintmanagement->complaintmanagement = $complaintmanagement->complaintmanagement + 1;
            DB::table('modpageload')->update(['complaintmanagement' => $complaintmanagement->complaintmanagement]);
        }
        //superadmin code ends
        $allproducts = Product::pluck("product")->toArray();
        $allproducts = implode(',',$allproducts);
        $types = Complaint::whereNotNull('type')->pluck('type')->toArray();
        $types = $types ? array_unique($types) : [];
        $types = implode(',',$types);
        $alldepartments = Departments::pluck('departmentname')->toArray();
        $alldepartments = implode(',',$alldepartments);

        $processstages = ['Action Approved', 'Approved',
            'Action Rejected', 'Need More Action', 'Actions Completed',
            'Under Review', 'Rejected', 'Actions Proposed- Review Awaiting', 'Correction Proposed- RC Awaiting', 'RC Updated- Correction Awaiting',
        ];
        $processstages = implode(',',$processstages);
        $responsibility = Complaint::whereNotNull('responsibility')->pluck('responsibility')->toArray();
        $responsibility = User::whereIn('email',$responsibility)->pluck('name')->toArray();
        $responsibility = $responsibility ? array_unique($responsibility) : [];
        $responsibility = implode(',',$responsibility);
        $raisedbynames = Complaint::whereNotNull('addedby')->pluck('addedby')->toArray();
        $raisedbynames = User::whereIn('employeeid',$raisedbynames)->pluck('name')->toArray();
        $raisedbynames = $raisedbynames ? array_unique($raisedbynames) : [];
        $allraisedbynames = implode(',',$raisedbynames);

        $recievedby = Complaint::whereNotNull('employee')->pluck('employee')->toArray();
        $recievedbynames = EmployeeDetails::whereIn('emp_number',$recievedby)->pluck('emp_name')->toArray();
        $recievedbynames = $recievedbynames ? array_unique($recievedbynames):[];
        $allrecievedbynames = implode(',',$recievedbynames);
        $complaintRecords = Complaint::all();
        //Due date
        $years = $this->getYearAndMonthToFilter($complaintRecords, 'tardate');
        //complaint recieved date
        $complaintRecievedDates = $this->getYearAndMonthToFilter($complaintRecords, 'crdate');
        $modeofcomplaint = ['Online forms', 'Phone call',
            'Emails', 'Fax', 'In-person visits to customer service center',
            'Direct office', 'Hot lines'
        ];
        $modeofcomplaint = implode(',',$modeofcomplaint);

        $plantids = Complaint::whereNotNull('plant')->pluck('plant')->toArray();
        $plantnames = ComplaintPlant::whereIn('id',$plantids)->pluck('plant_name')->toArray();
        $plantnames = $plantnames ? array_unique($plantnames):[];
        $allplants = implode(',',$plantnames);

        $divisionids = Complaint::whereNotNull('division_id')->pluck('division_id')->toArray();
        $divisionnames = Division::whereIn('id',$divisionids)->pluck('name')->toArray();
        $alldivisionnames = implode(',',$divisionnames);

        $areaids = Complaint::whereNotNull('area_id')->pluck('area_id')->toArray();
        $areanames = Area::whereIn('id',$areaids)->pluck('name')->toArray();
        $allsubdivisionnames = implode(',',$areanames);

        $leaderids = Complaint::whereNotNull('team_lead_id')->pluck('team_lead_id')->toArray();
        $allteamleadersnames = User::whereIn('id',$leaderids)->pluck('name')->toArray();
        $allteamleaders = implode(',',$allteamleadersnames);

        //investigation_date
        $inyears = $complaintRecievedDates = $this->getYearAndMonthToFilter($complaintRecords, 'investigation_date');
        //added date
        $addedyears = $this->getYearAndMonthToFilter($complaintRecords, 'created_at');
        //reviewed date
        $revieweddateyears = $this->getYearAndMonthToFilter($complaintRecords, 'reviewedat');
        //closed date
        $closedatyears = $this->getYearAndMonthToFilter($complaintRecords, 'closed_at');

        $reviewedbyids = Complaint::whereNotNull('reviewed_by')->pluck('reviewed_by')->toArray();
        $allreviewedbynames = User::whereIn('id',$reviewedbyids)->pluck('name')->toArray();
        $allreviewedby = implode(',',$allreviewedbynames);

        $closedbyids = Complaint::whereNotNull('closed_by')->pluck('closed_by')->toArray();
        $allclosedbynames = User::whereIn('id',$closedbyids)->pluck('name')->toArray();
        $allclosedby = implode(',',$allclosedbynames);
        $models = DB::table('modenadisable')->First();

        //--------------------Register------------
        $data = Complaint::with('complaintMeta', 'vendor')
            ->where('status', '!=', 'Closed')
            ->where(function ($query) {
                $query->whereNull('complaint_template_id')
                    ->orWhere('complaint_template_id', 1);
            })
            ->orderByDesc('id')
            ->get()
            ->map(function ($product) {
                $productArray = $product->toArray();
                $metaArray = $product->complaintMeta
                    ->pluck('meta_value', 'meta_key')
                    ->toArray();
                unset($productArray['complaint_meta']);
                return (object)array_merge($productArray, $metaArray);
            });

        $dataProject = Complaint::with('complaintMeta', 'vendor')
            ->where('status', '!=', 'Closed')
            ->where('complaint_template_id', 2)
            ->orderByDesc('id')
            ->get()
            ->map(function ($project) {
                $projectArray = $project->toArray();
                $metaArray = $project->complaintMeta
                    ->pluck('meta_value', 'meta_key')
                    ->toArray();
                unset($projectArray['complaint_meta']);
                return (object)array_merge($projectArray, $metaArray);
            });

        $productVendors = $data->pluck('vendor.vendor_name')->filter()->unique()->implode(',');
        $projectVendors = $dataProject->pluck('vendor.vendor_name')->filter()->unique()->implode(',');

        $complaintCategories = ComplaintProductDetail::whereNotNull('complaint_category')->distinct()->pluck('complaint_category')->toArray();
        $complaintCategories = array_map(function($item) {
            return explode(',', $item);
        }, $complaintCategories);
        $complaintCategories = !empty($complaintCategories) ? array_unique(array_merge(...$complaintCategories)) : [];
        $allComplaintCategories = ComplaintCategory::whereIn('id',$complaintCategories)->pluck('category_name')->toArray();
        $allComplaintCategories = implode(',',array_unique($allComplaintCategories));

        $complaintCategoryIds = $dataProject->pluck('complaint_category_id')->unique()->toArray();
        $allComplaintProjectCategories = ComplaintCategory::whereIn('id',$complaintCategoryIds)->pluck('category_name')->toArray();
        $allComplaintProjectCategories = implode(',',array_unique($allComplaintProjectCategories));

        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $email = DB::table('complaintreviewers')->pluck('email');
        $user = User::where('is_email_verified',true)->get();

        $docs = DB::table('complaintfiles')->get();
        //-------------------Customer list----------

        $data1 = DB::table('customer')
            ->where('is_deleted', false)
            ->paginate(1000);
        //--------------------my responsible complaints------------
        $res = Auth::user()->email;
        $data2 = DB::table('complaint')
            ->where('responsibility', '=', $res)
            ->wherein('status', array(
                'Approved',
                'Actions Proposed',
                'Action Reviewed, Ok',
                'Action Reviewed, Not OK',
                'rejected',
                'Need More Action',
                'Actions Completed',
            ))
            ->paginate(100);

        //------------------Closed--------------
        $docs = DB::table('complaintfiles')->get();
        $data3 = DB::table('complaint')
            ->where('status', 'Closed')
            ->orderBy('id', 'desc')
            ->paginate(1000);
        $complaintregisters = DB::table('complaintregisters')->pluck('email')->toArray();
        $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
        $table_columns_active =  ModuleRegisterPageDetail::where('module_name','complaint')
            ->where('is_active',true)
            ->where('column_name','!=','S.No')
            ->where('column_name','!=','title')
            ->where('column_name','!=','tracking number')
            ->where('column_name','!=','process stage')
            ->where('column_name','!=','action status')
            ->orderBy('column_position', 'asc')
            ->get();
        $table_columns_names_active =  ModuleRegisterPageDetail::where('module_name','complaint')
            ->where('is_active',true)
            ->pluck('column_name')
            ->toArray();

        $table_columns_names_enabled_by_user =  ModuleRegisterPageDetail::where('module_name','complaint')
            ->where('enabled_by_user','!=',0)
            ->get()->count();

        $table_columns_non_active =  ModuleRegisterPageDetail::where('module_name','complaint')
            ->where('is_active',false)
            ->orderBy('column_position', 'asc')
            ->get();

        if (!$table_columns_names_enabled_by_user) {
            $isActiveColumns = ModuleRegisterPageDetail::where('module_name','complaint')
                ->whereNotIn('column_name',['S.No','tracking number','title','process stage','action status'])
                ->where('is_active', true)
                ->where('enabled_by_user', 0)
                ->get();
            $defaultPosition = [
                'type' => 4,
                'customer' => 5,
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


        $project_table_columns_active =  ModuleRegisterPageDetail::where('module_name','complaint_project')
            ->where('is_active',true)
            ->where('column_name','!=','S.No')
            ->where('column_name','!=','title')
            ->where('column_name','!=','tracking number')
            ->where('column_name','!=','process stage')
            ->where('column_name','!=','action status')
            ->orderBy('column_position', 'asc')
            ->get();
        $project_table_columns_names_active =  ModuleRegisterPageDetail::where('module_name','complaint_project')
            ->where('is_active',true)
            ->pluck('column_name')
            ->toArray();

        $project_table_columns_names_enabled_by_user =  ModuleRegisterPageDetail::where('module_name','complaint_project')
            ->where('enabled_by_user','!=',0)
            ->get()->count();

        $project_table_columns_non_active =  ModuleRegisterPageDetail::where('module_name','complaint_project')
            ->where('is_active',false)
            ->orderBy('column_position', 'asc')
            ->get();

        $product_custom_fields = ComplaintFormConfigurationHelper::getProductFields();
        $project_custom_fields = ComplaintFormConfigurationHelper::getProjectFields();

        if (!$project_table_columns_names_enabled_by_user) {
            $isActiveProjectColumns = ModuleRegisterPageDetail::where('module_name','complaint_project')
                ->whereNotIn('column_name',['S.No','tracking number','title','process stage','action status'])
                ->where('is_active', true)
                ->where('enabled_by_user', 0)
                ->get();
            $defaultPosition = [
                'type' => 4,
                'customer' => 5,
                'department' => 6,
                'responsibility' => 7,
                'due date' => 8,
            ];

            foreach ($isActiveProjectColumns as $column) {
                if (array_key_exists($column->column_name, $defaultPosition) && $column->column_position != $defaultPosition[$column->column_name]) {
                    $project_table_columns_names_enabled_by_user = true;
                    break;
                }
            }

        }

        $complaintIdsData = $data->pluck('id');
        $customersData = Customer::pluck('customer', 'id');
        $departmentsData = Departments::pluck('departmentname', 'id');
        $responsibilitiesData = User::whereNull('deleted_at')->pluck('name','email');
        $raisedbyRecordsData = User::whereNull('deleted_at')->pluck('name','employeeid');
        $receivedbyRecordsData = EmployeeDetails::pluck('emp_name','emp_number');
        $plantsData = ComplaintPlant::pluck('plant_name','id');
        $subdivisionsData = Area::pluck('name','id');
        $divisionsData = Division::pluck('name','id');
        $usersData = User::whereNull('deleted_at')->pluck('name','id');
        $productDetailsData = ComplaintProductDetail::whereIn('complaint_id', $complaintIdsData)
            ->get()
            ->groupBy('complaint_id');
        $categoryNamesData = ComplaintCategory::pluck('category_name', 'id');
        $complaintCategoriesTable = $this->getComplaintCategoryTable(
            $data,$complaintIdsData,$customersData,$responsibilitiesData,$raisedbyRecordsData,$receivedbyRecordsData,
            $plantsData,$subdivisionsData,$divisionsData,$usersData,$productDetailsData,$categoryNamesData,$departmentsData
        );
        $activeComplaintTemplate = ComplaintFormConfigurationHelper::getActiveComplaintTemplate();

        $customer_ids = Complaint::whereNotNull('customer')->pluck('customer')->unique()->toArray();
        $all_customer_names = Customer::whereIn('id',$customer_ids)->pluck('customer')->toArray();
        $all_customers = implode(',',$all_customer_names);

        return view('user.complaint.register',compact('all_customers', 'productVendors', 'projectVendors', 'complaintCategoriesTable','allComplaintCategories','complaintIdsData','departmentsData','responsibilitiesData',
                'raisedbyRecordsData','receivedbyRecordsData','plantsData','subdivisionsData','divisionsData','usersData','productDetailsData','categoryNamesData'))
            ->with('model', $models)
            ->with('rootcorrectives',$rootcorrectives)
            ->with('user', $user)
            ->with('docs', $docs)
            ->with('data1', $data1)
            ->with('data2', $data2)->with('data3', $data3)->withDepartments($departments)->withMr($mr)->with('data', $data)
            ->with('email', $email)->with('complaintregisters',$complaintregisters)
            ->with([
                'customersData' => $customersData,
                'dataProject' => $dataProject,
                'allproducts' => $allproducts,
                'years' => $years,
                'complaintRecievedDates' => $complaintRecievedDates,
                'types' => $types,
                'alldepartments' => $alldepartments,
                'allComplaintCategories' => $allComplaintCategories,
                'allComplaintProjectCategories' => $allComplaintProjectCategories,
                'responsibility' => $responsibility,
                'allraisedbynames' =>$allraisedbynames,
                'processstages' => $processstages,
                'allrecievedbynames' => $allrecievedbynames,
                'modeofcomplaint' => $modeofcomplaint,
                'allplants' => $allplants,
                'categoryNamesData' => $categoryNamesData,
                'alldivisionnames' => $alldivisionnames,
                'allsubdivisionnames' => $allsubdivisionnames,
                'allteamleaders' => $allteamleaders,
                'inyears' => $inyears,
                'table_columns_active' => $table_columns_active,
                'table_columns_non_active' => $table_columns_non_active,
                'table_columns_names_active' => $table_columns_names_active,
                'table_columns_names_enabled_by_user' => $table_columns_names_enabled_by_user,
                'project_table_columns_active' => $project_table_columns_active,
                'project_table_columns_non_active' => $project_table_columns_non_active,
                'project_table_columns_names_active' => $project_table_columns_names_active,
                'project_table_columns_names_enabled_by_user' => $project_table_columns_names_enabled_by_user,
                'product_custom_fields' => $product_custom_fields,
                'project_custom_fields' => $project_custom_fields,
                'addedyears' => $addedyears,
                'revieweddateyears' => $revieweddateyears,
                'closedatyears' => $closedatyears,
                'allreviewedby' => $allreviewedby,
                'allclosedby' => $allclosedby,
                'activeTemplate' => $activeComplaintTemplate
            ]);
    }

    public function exportData(Request $request)
    {
        $tab = $request->tab;
        $format = $request->format;
        $ids = $request->ids;
        $headers = $request->headings;
        if ($tab == 'product' || $tab =="project" || $tab == "category") {
            array_shift($headers);                  // removing first array item which is checkbox
        }
        array_pop($headers);                        // removing last array item which is action

        return ComplaintExportHelper::exportData($ids, $headers, $tab, $format);
    }

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
    private function getComplaintCategoryTable($data,$complaintIdsData,$customersData,$responsibilitiesData,$raisedbyRecordsData,$receivedbyRecordsData,
    $plantsData,$subdivisionsData,$divisionsData,$usersData,$productDetailsData,$categoryNamesData,$departmentsData){
        $complaintCategoriesTable = view('user.complaint.complaint_category_table',compact('data','complaintIdsData','customersData','responsibilitiesData','raisedbyRecordsData',
        'receivedbyRecordsData','plantsData','subdivisionsData','divisionsData','usersData','productDetailsData','categoryNamesData','departmentsData')
        )->render();
        return $complaintCategoriesTable;
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

        $default_values = Constants::REGISTER_PAGE_PRODUCT_COMPLAINT_COLUMNS;
        foreach ($default_values as $value) {
            ModuleRegisterPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update([
                    'column_position' => $value['column_position'],
                    'is_active' => $value['is_active'],
                    'enabled_by_user' => $value['enabled_by_user']
                ]);
        }
        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }

    public function registertablecolumndefaultviewProject()
    {
        $default_values = Constants::REGISTER_PAGE_PROJECT_COMPLAINT_COLUMNS;
        foreach ($default_values as $value) {
            ModuleRegisterPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update([
                    'column_position' => $value['column_position'],
                    'is_active' => $value['is_active'],
                    'enabled_by_user' => $value['enabled_by_user']
                ]);
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

    public function updatePosition(Request $request)
    {
        $order = $request->order;

        DB::transaction(function () use ($order) {

            foreach ($order as $item) {
                if (!$item['id']) continue;
                ModuleRegisterPageDetail::where('id', $item['id'])
                ->update(['column_position' => $item['position']]);
            }
        });

        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }
    public function complaintActionAttachment(Request $request, int $id)
    {
        $destination_path = public_path('storage/complaint');
        if ($request->hasFile('fileToUpload')) {
            $file_array = $request->file('fileToUpload');
            $insertData = [];
            foreach ($file_array as $file) {
                $new_file_name = $file->getClientOriginalName();
                $file->move($destination_path, $new_file_name);
                $insertData[] = [
                    'complaint_id' => $id,
                    'action_file' => $new_file_name,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            ComplaintActionFile::insert($insertData);
        }
        if ($request->hasFile('fileToUpload1')) {
            $file_array = $request->file('fileToUpload1');
            $insertData = [];
            foreach ($file_array as $file) {
                $new_file_name = $file->getClientOriginalName();
                $file->move($destination_path, $new_file_name);
                $insertData[] = [
                    'complaint_id' => $id,
                    'issue_file' => $new_file_name,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            ComplaintIssueFile::insert($insertData);
        }
        return response()->json(['data' => null]);
    }
    public function list() {
        $models = DB::table('modenadisable')->first();
        return view('user.complaint.list')
            ->with('model', $models);
    }
    public function getAllCustomers(Request $request) {
        $length = $request->input('length');
        $start = $request->input('start');
        $totalRecords = Customer::where('is_deleted', false)->count();
        $customers = null;
        $filteredRecords = 0;
        if ($request->has('search.value') && $request->input('search.value')) {
            $search = $request->input('search.value');
            $query = Customer::query()
                    ->join('products', function ($join) {
                        $join->whereRaw('FIND_IN_SET(products.id, customer.product)');
                    })
                    ->leftJoin('countries', 'customer.country_id', '=', 'countries.id')
                    ->leftJoin('states', 'customer.state_id', '=', 'states.id')
                    ->select('customer.*', 'products.product as product_name', 'countries.country_name as country_name', 'states.state_name as state_name')
                    ->where('customer.is_deleted', false);
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('customer.customer', 'like', "%{$search}%")
                    ->orWhere('customer.location', 'like', "%{$search}%")
                    ->orWhere('customer.other_country', 'like', "%{$search}%")
                    ->orWhere('customer.other_state', 'like', "%{$search}%")
                    ->orWhere('customer.pin', 'like', "%{$search}%")
                    ->orWhere('customer.email', 'like', "%{$search}%")
                    ->orWhere('customer.phone', 'like', "%{$search}%")
                    ->orWhere('customer.customer_survey_emails', 'like', "%{$search}%")
                    ->orWhere('products.product', 'like', "%{$search}%")
                    ->orWhere('countries.country_name', 'like', "%{$search}%")
                    ->orWhere('states.state_name', 'like', "%{$search}%");
            });
            $customers = $query->count();
            $filteredRecords = $query->count();
            $customers = $query->skip($start)->take($length)->get()->unique('id')->values();
        }else{
            $queryForOrder = Customer::where('is_deleted', false);
            if ($request->has('order')) {
                $orderColumnIndex = $request->input('order')[0]['column'];
                $orderDir = $request->input('order')[0]['dir'];
                $columns = ['id', 'customer', 'location', 'email', 'product'];
                $orderColumn = $columns[$orderColumnIndex] ?? 'id';
                $queryForOrder->orderBy($orderColumn, $orderDir);
            }else{
                $queryForOrder->orderBy('id', 'desc');
            }
            $filteredRecords =  $totalRecords;
            $customers = $queryForOrder->skip($start)->take($length)->get();
        }

        $productsArray = $customers->pluck('product')->toArray();
        for($i=0;$i<count($customers);$i++){
            $customers[$i]->product_name = implode(',',Product::whereIn('id',explode(',',$productsArray[$i]))->pluck('product')->toArray());
        }
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $customers,
        ]);
    }
    public function getAllCustomersForAddComplaint(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $pageSize = 50;
        $query = Customer::query()->where('is_deleted',false)->select(['id','customer']);

        if ($search) {
            $query->where('customer', 'like', '%' . $search . '%');
        }
        $customers = $query->paginate($pageSize, ['*'], 'page', $page);
        return response()->json([
            'items' => $customers->items(),
            'hasMore' => $customers->hasMorePages(),
        ]);
    }

    public function my()
    {
        $models = DB::table('modenadisable')->First();
        //--------------------my responsible complaints------------
        $user = User::all();
        $res = Auth::user()->email;
        $currentUser = User::where('email', $res)->first();
        $complainRootCauseCorrectiveActionDetail = ComplaintRootCauseCorrectiveActionDetail::where('responsibility', '=', $currentUser->id);
        $complainIds = $complainRootCauseCorrectiveActionDetail->pluck('complaint_id')->toArray();


        $data2 = Complaint::where(function ($query) use ($res, $complainIds) {
                $query->where(function ($q) use ($res) {
                        $q->where('responsibility', '=', $res)
                        ->whereIn('status', [
                            'Approved',
                            'Actions Proposed',
                            'Action Reviewed, Ok',
                            'Action Reviewed, Not OK',
                            'rejected',
                            'Need More Action',
                            'Actions Completed',
                            'Actions Proposed- Review Awaiting',
                            'Correction Proposed- RC Awaiting',
                            'RC Updated- Correction Awaiting'
                        ]);
                    })
                    ->orWhereIn('id', $complainIds);
            })
            ->whereNotIn('status', ['Closed']) // ✅ applied globally
            ->orderByDesc('id')
            ->get();

        $docs = DB::table('complaintfiles')->get();
        $allproducts = Product::pluck("product")->toArray();
        $allproducts = implode(',',$allproducts);
        $types = Complaint::whereNotNull('type')->pluck('type')->toArray();
        $types = $types ? array_unique($types) : [];
        $types = implode(',',$types);
        $alldepartments = Departments::pluck('departmentname')->toArray();
        $alldepartments = implode(',',$alldepartments);
        $processstages = ['Action Approved', 'Approved',
            'Action Rejected', 'Need More Action', 'Actions Completed',
            'Under Review', 'Rejected', 'Actions Proposed- Review Awaiting', 'Correction Proposed- RC Awaiting', 'RC Updated- Correction Awaiting',
        ];
        $complaintregisters = DB::table('complaintregisters')->pluck('email')->toArray();
        $processstages = implode(',',$processstages);
        return view('user.complaint.my')->with('model', $models)->with('data2', $data2)->with('docs', $docs)->with('user', $user)
                ->with([
                    'allproducts' => $allproducts,
                    'types' => $types,
                    'alldepartments' => $alldepartments,
                    'processstages' => $processstages,
                    'complaintregisters' => $complaintregisters
                ]);
    }
    public function closedc()
    {
        //below is superadmin related codes
        $complaintmanagement = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($complaintmanagement) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 1, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 0, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0, 'contextmanagement' => 0, 'complaintmanagement' => 1]);
        } else {
            $complaintmanagement = DB::table('modpageload')->where('id', 1)->First();
            $complaintmanagement->complaintmanagement = $complaintmanagement->complaintmanagement + 1;
            DB::table('modpageload')->update(['complaintmanagement' => $complaintmanagement->complaintmanagement]);
        }
        //superadmin code ends
        $allproducts = Product::pluck("product")->toArray();
        $allproducts = implode(',',$allproducts);
        $types = Complaint::whereNotNull('type')->pluck('type')->toArray();
        $types = $types ? array_unique($types) : [];
        $types = implode(',',$types);
        $alldepartments = Departments::pluck('departmentname')->toArray();
        $alldepartments = implode(',',$alldepartments);
        $responsibility = Complaint::where('status','Closed')->whereNotNull('responsibility')->pluck('responsibility')->toArray();
        $responsibility = User::whereIn('email',$responsibility)->pluck('name')->toArray();
        $responsibility = $responsibility ? array_unique($responsibility) : [];
        $responsibility = implode(',',$responsibility);
        $raisedbynames = Complaint::where('status','Closed')->whereNotNull('addedby')->pluck('addedby')->toArray();
        $raisedbynames = User::whereIn('employeeid',$raisedbynames)->pluck('name')->toArray();
        $raisedbynames = $raisedbynames ? array_unique($raisedbynames) : [];
        $allraisedbynames = implode(',',$raisedbynames);

        $recievedby = Complaint::where('status','Closed')->whereNotNull('employee')->pluck('employee')->toArray();
        $recievedbynames = EmployeeDetails::whereIn('emp_number',$recievedby)->pluck('emp_name')->toArray();
        $recievedbynames = $recievedbynames ? array_unique($recievedbynames):[];
        $allrecievedbynames = implode(',',$recievedbynames);
        $complaintRecords = Complaint::where('status','Closed')->get();
        //Due date
        $years = $this->getYearAndMonthToFilter($complaintRecords, 'tardate');
        //complaint recieved date
        $complaintRecievedDates = $this->getYearAndMonthToFilter($complaintRecords, 'crdate');
        $modeofcomplaint = ['Online forms', 'Phone call',
            'Emails', 'Fax', 'In-person visits to customer service center',
            'Direct office', 'Hot lines'
        ];
        $modeofcomplaint = implode(',',$modeofcomplaint);

        $plantids = Complaint::where('status','Closed')->whereNotNull('plant')->pluck('plant')->toArray();
        $plantnames = ComplaintPlant::whereIn('id',$plantids)->pluck('plant_name')->toArray();
        $plantnames = $plantnames ? array_unique($plantnames):[];
        $allplants = implode(',',$plantnames);

        $divisionids = Complaint::where('status','Closed')->whereNotNull('division_id')->pluck('division_id')->toArray();
        $divisionnames = Division::whereIn('id',$divisionids)->pluck('name')->toArray();
        $alldivisionnames = implode(',',$divisionnames);

        $areaids = Complaint::where('status','Closed')->whereNotNull('area_id')->pluck('area_id')->toArray();
        $areanames = Area::whereIn('id',$areaids)->pluck('name')->toArray();
        $allsubdivisionnames = implode(',',$areanames);

        $leaderids = Complaint::where('status','Closed')->whereNotNull('team_lead_id')->pluck('team_lead_id')->toArray();
        $allteamleadersnames = User::whereIn('id',$leaderids)->pluck('name')->toArray();
        $allteamleaders = implode(',',$allteamleadersnames);

        //investigation_date
        $inyears = $complaintRecievedDates = $this->getYearAndMonthToFilter($complaintRecords, 'investigation_date');
        //added date
        $addedyears = $this->getYearAndMonthToFilter($complaintRecords, 'created_at');
        //reviewed date
        $revieweddateyears = $this->getYearAndMonthToFilter($complaintRecords, 'reviewedat');
        //closed date
        $closedatyears = $this->getYearAndMonthToFilter($complaintRecords, 'closed_at');
        //delivery date
        $deliverydateyears = $this->getYearAndMonthToFilter($complaintRecords, 'delivery_date');

        $reviewedbyids = Complaint::where('status','Closed')->whereNotNull('reviewed_by')->pluck('reviewed_by')->toArray();
        $allreviewedbynames = User::whereIn('id',$reviewedbyids)->pluck('name')->toArray();
        $allreviewedby = implode(',',$allreviewedbynames);

        $closedbyids = Complaint::where('status','Closed')->whereNotNull('closed_by')->pluck('closed_by')->toArray();
        $allclosedbynames = User::whereIn('id',$closedbyids)->pluck('name')->toArray();
        $allclosedby = implode(',',$allclosedbynames);

        $customer_ids = Complaint::where('status','Closed')->whereNotNull('customer')->pluck('customer')->unique()->toArray();
        $all_customer_names = Customer::whereIn('id',$customer_ids)->pluck('customer')->toArray();
        $all_customers = implode(',',$all_customer_names);

        $models = DB::table('modenadisable')->First();

        //--------------------Register------------
        $data3 = Complaint::with('complaintMeta', 'vendor')
            ->where('status', 'Closed')
            ->where(function ($query) {
                $query->whereNull('complaint_template_id')
                    ->orWhere('complaint_template_id', 1);
            })
            ->orderByDesc('id')
            ->get()
            ->map(function ($product) {
                $productArray = $product->toArray();
                $metaArray = $product->complaintMeta
                    ->pluck('meta_value', 'meta_key')
                    ->toArray();
                unset($productArray['complaint_meta']);
                return (object)array_merge($productArray, $metaArray);
            });

        $dataProject = Complaint::with('complaintMeta', 'vendor')
            ->where('status', 'Closed')
            ->where('complaint_template_id', 2)
            ->orderByDesc('id')
            ->get()
            ->map(function ($project) {
                $projectArray = $project->toArray();
                $metaArray = $project->complaintMeta
                    ->pluck('meta_value', 'meta_key')
                    ->toArray();
                unset($projectArray['complaint_meta']);
                return (object)array_merge($projectArray, $metaArray);
            });

        $productVendors = $data3->pluck('vendor.vendor_name')->filter()->unique()->implode(',');
        $projectVendors = $dataProject->pluck('vendor.vendor_name')->filter()->unique()->implode(',');
        $projectSourceOfIssues = $dataProject->pluck('source_of_issue')->filter()->unique()->implode(',');

        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $email = DB::table('complaintreviewers')->pluck('email');
        $user = User::where('is_email_verified',true)->get();

        $docs = DB::table('complaintfiles')->get();
        //--------------------my responsible complaints------------
        $res = Auth::user()->email;
        //------------------Closed--------------
        $docs = DB::table('complaintfiles')->get();
        $complaintregisters = DB::table('complaintregisters')->pluck('email')->toArray();
        $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
        $table_columns_active =  ModuleClosedPageDetail::where('module_name','complaint')
            ->where('is_active',true)
            ->where('column_name','!=','title')
            ->where('column_name','!=','tracking number')
            ->where('column_name','!=','process stage')
            ->where('column_name','!=','action status')
            ->orderBy('column_position', 'asc')
            ->get();
        $table_columns_names_active =  ModuleClosedPageDetail::where('module_name','complaint')
            ->where('is_active',true)
            ->pluck('column_name')
            ->toArray();

        $table_columns_names_enabled_by_user =  ModuleClosedPageDetail::where('module_name','complaint')
            ->where('enabled_by_user','!=',0)
            ->get()->count();

        $table_columns_non_active =  ModuleClosedPageDetail::where('module_name','complaint')
            ->where('is_active',false)
            ->orderBy('column_position', 'asc')
            ->get();
        $table_columns_names_enabled_by_user =  ModuleClosedPageDetail::where('module_name','complaint')
                                                                    ->where('enabled_by_user','!=',0)
                                                                    ->get()->count();

        $project_table_columns_active =  ModuleClosedPageDetail::where('module_name','complaint_project')
            ->where('is_active',true)
            ->where('column_name','!=','S.No')
            ->where('column_name','!=','title')
            ->where('column_name','!=','tracking number')
            ->where('column_name','!=','process stage')
            ->where('column_name','!=','action status')
            ->orderBy('column_position', 'asc')
            ->get();
        $project_table_columns_names_active =  ModuleClosedPageDetail::where('module_name','complaint_project')
            ->where('is_active',true)
            ->pluck('column_name')
            ->toArray();

        $project_table_columns_names_enabled_by_user =  ModuleClosedPageDetail::where('module_name','complaint_project')
            ->where('enabled_by_user','!=',0)
            ->get()->count();

        $project_table_columns_non_active =  ModuleClosedPageDetail::where('module_name','complaint_project')
            ->where('is_active',false)
            ->orderBy('column_position', 'asc')
            ->get();

        $product_custom_fields = ComplaintFormConfigurationHelper::getProductFields();
        $project_custom_fields = ComplaintFormConfigurationHelper::getProjectFields();

        if (!$project_table_columns_names_enabled_by_user) {
            $isActiveProjectColumns = ModuleClosedPageDetail::where('module_name','complaint_project')
                ->whereNotIn('column_name',['S.No','tracking number','title','process stage','action status'])
                ->where('is_active', true)
                ->where('enabled_by_user', 0)
                ->get();

            $defaultPosition = [
                'type' => 4,
                'customer' => 5,
                'department' => 6,
                'responsibility' => 7,
                'due date' => 8,
            ];

            foreach ($isActiveProjectColumns as $column) {
                if (array_key_exists($column->column_name, $defaultPosition) && $column->column_position != $defaultPosition[$column->column_name]) {
                    $project_table_columns_names_enabled_by_user = true;
                    break;
                }
            }
        }

        if (!$table_columns_names_enabled_by_user) {
            $isActiveColumns = ModuleClosedPageDetail::where('module_name','complaint')
                ->whereNotIn('column_name',['tracking number','title','process stage','action status'])
                ->where('is_active', true)
                ->where('enabled_by_user', 0)
                ->get();
            $defaultPosition = [
                'type' => 4,
                'customer' => 5,
                'department' => 6,
                'responsibility' => 7,
                'due date' => 8,
                // 'issue authenticity' => 8,
            ];

            foreach ($isActiveColumns as $column) {
                if (array_key_exists($column->column_name, $defaultPosition) && $column->column_position != $defaultPosition[$column->column_name]) {
                    $table_columns_names_enabled_by_user = true;
                    break;
                }
            }
        }
        $authenticity = ['Genuine', 'Non Genuine'];
        $allauthenticity = implode(',',$authenticity);
        $activeComplaintTemplate = ComplaintFormConfigurationHelper::getActiveComplaintTemplate();

        $customersData = Customer::pluck('customer', 'id');
        $departmentsData = Departments::pluck('departmentname', 'id');
        $responsibilitiesData = User::whereNull('deleted_at')->pluck('name','email');
        $raisedbyRecordsData = User::whereNull('deleted_at')->pluck('name','employeeid');
        $receivedbyRecordsData = EmployeeDetails::pluck('emp_name','emp_number');
        $plantsData = ComplaintPlant::pluck('plant_name','id');
        $categoryNamesData = ComplaintCategory::pluck('category_name', 'id');
        $subdivisionsData = Area::pluck('name','id');
        $divisionsData = Division::pluck('name','id');
        $usersData = User::whereNull('deleted_at')->pluck('name','id');

        $complaintCategoryIds = $dataProject->pluck('complaint_category_id')->unique()->toArray();
        $allComplaintProjectCategories = ComplaintCategory::whereIn('id',$complaintCategoryIds)->pluck('category_name')->toArray();
        $allComplaintProjectCategories = implode(',',array_unique($allComplaintProjectCategories));

        return view('user.complaint.closedc',compact('all_customers', 'projectSourceOfIssues', 'productVendors', 'projectVendors', 'deliverydateyears'))
            ->with('model', $models)
            ->with('rootcorrectives',$rootcorrectives)
            ->with('user', $user)
            ->with('docs', $docs)
            ->withDepartments($departments)->withMr($mr)->with('data3', $data3)
            ->with('email', $email)->with('complaintregisters',$complaintregisters)
            ->with([
                'allproducts' => $allproducts,
                'years' => $years,
                'complaintRecievedDates' => $complaintRecievedDates,
                'types' => $types,
                'alldepartments' => $alldepartments,
                'responsibility' => $responsibility,
                'allraisedbynames' =>$allraisedbynames,
                'allrecievedbynames' => $allrecievedbynames,
                'modeofcomplaint' => $modeofcomplaint,
                'allplants' => $allplants,
                'alldivisionnames' => $alldivisionnames,
                'allsubdivisionnames' => $allsubdivisionnames,
                'allteamleaders' => $allteamleaders,
                'inyears' => $inyears,
                'table_columns_active' => $table_columns_active,
                'table_columns_non_active' => $table_columns_non_active,
                'table_columns_names_active' => $table_columns_names_active,
                'table_columns_names_enabled_by_user' => $table_columns_names_enabled_by_user,
                'project_table_columns_active' => $project_table_columns_active,
                'project_table_columns_non_active' => $project_table_columns_non_active,
                'project_table_columns_names_active' => $project_table_columns_names_active,
                'project_table_columns_names_enabled_by_user' => $project_table_columns_names_enabled_by_user,
                'product_custom_fields' => $product_custom_fields,
                'project_custom_fields' => $project_custom_fields,
                'addedyears' => $addedyears,
                'revieweddateyears' => $revieweddateyears,
                'closedatyears' => $closedatyears,
                'allreviewedby' => $allreviewedby,
                'allclosedby' => $allclosedby,
                'allauthenticity' => $allauthenticity,
                'dataProject' => $dataProject,
                'activeTemplate' => $activeComplaintTemplate,
                'customersData' => $customersData,
                'departmentsData' => $departmentsData,
                'responsibilitiesData' => $responsibilitiesData,
                'raisedbyRecordsData' => $raisedbyRecordsData,
                'receivedbyRecordsData' => $receivedbyRecordsData,
                'plantsData' => $plantsData,
                'categoryNamesData' => $categoryNamesData,
                'subdivisionsData' => $subdivisionsData,
                'divisionsData' => $divisionsData,
                'usersData' => $usersData,
                'allComplaintProjectCategories' => $allComplaintProjectCategories
            ]);

    }
    //--------------------------------dynamic table functionality for closed complaint start---------------------------------
    public function closedcomplainttablecolumnactive($id)
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

    public function closedcomplainttablecolumndefaultview()
    {
        $default_values = Constants::CLOSED_REGISTER_PAGE_PRODUCT_COMPLAINT_COLUMNS;
        foreach ($default_values as $value) {
            ModuleClosedPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update([
                    'column_position' => $value['column_position'],
                    'is_active' => $value['is_active'],
                    'enabled_by_user' => $value['enabled_by_user']
                ]);
        }
        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }

    public function closedcomplainttablecolumndefaultviewProject()
    {
        $default_values = Constants::CLOSED_REGISTER_PAGE_PROJECT_COMPLAINT_COLUMNS;
        foreach ($default_values as $value) {
            ModuleClosedPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update([
                    'column_position' => $value['column_position'],
                    'is_active' => $value['is_active'],
                    'enabled_by_user' => $value['enabled_by_user']
                ]);
        }
        $models = DB::table('modenadisable')->first();
        return redirect()->back()->with('model', $models);
    }

    public function closedcomplaintremovecolumn(Request $request)
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

    public function closedcomplaintupdatePosition(Request $request)
    {
        // $template = $request->get('template');
        // $startIndex = $request->get('startIndex');
        // $targetIndex = $request->get('targetIndex');

        // DB::transaction(function() use ($template, $startIndex, $targetIndex) {
        //     $updateData = [];
        //     $moduleRegisterPageDetailOriginal = ModuleClosedPageDetail::where('module_name', $template)
        //         ->where('id', $startIndex)
        //         ->first();
        //     $moduleRegisterPageDetailTarget = ModuleClosedPageDetail::where('module_name', $template)
        //         ->where('id', $targetIndex)
        //         ->first();
        //     $updateData[$moduleRegisterPageDetailOriginal->id] = $moduleRegisterPageDetailTarget->column_position;

        //     $updateData[$moduleRegisterPageDetailTarget->id] = $moduleRegisterPageDetailOriginal->column_position;

        //     foreach ($updateData as $id => $value) {
        //         ModuleClosedPageDetail::where('module_name', $template)
        //             ->where('id', $id)
        //             ->update([
        //                 'column_position' => $value
        //             ]);
        //     }
        // });

        $order = $request->order;

        DB::transaction(function () use ($order) {

            foreach ($order as $item) {
                if (!$item['id']) continue;
                ModuleClosedPageDetail::where('id', $item['id'])
                ->update(['column_position' => $item['position']]);
            }
        });


        return response()->json([
            'status' => 200,
            'data' => null,
        ]);
    }
    //--------------------------------dynamic table functionality for closed complaint start---------------------------------//--------------------------------dynamic table functionality for closed complaint start---------------------------------
    //complaint entry review page
    public function creview()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $docs = DB::table('complaintfiles')->where('id',$id)->get();
            $files = [];
            foreach($docs as $doc){
                if($doc && $doc->files!="NULL"){
                    $files[] = $doc;
                }
            }
            // fetching complaint
            $data = Complaint::with('complaintMeta', 'previousComplaintRef')->where('id', $id)->first();

            // adding complaint meta datas as elements of complaint
            $result = collect($data->toArray());
            $meta = collect($result['complaint_meta'])->pluck('meta_value', 'meta_key');
            $data = (object)$result->except('complaint_meta')->merge($meta)->toArray();
            //

            $employees = EmployeeDetails::where('is_deleted',false)->get();
            $user = User::where('is_email_verified',true)->orderBy('name')->get();
            //$departments = Departments::all();
            $departments = Departments::where('is_active',true)
                                        ->orWhere('id',$data->dept)
                                        ->get();
            $type = Type::all();
            $res = $data->responsibility;
            $product_details = ComplaintProductDetail::where('complaint_id',$data->id)->get();
            $product = Product::get();
            $plants = ComplaintPlant::where('is_deleted',false)->get();
            $category_plant_dependency = AppConfiguration::where('meta_key', 'complaint_category_plant_dependency')->first();
            if ($category_plant_dependency->meta_value != "Independent") {
                $all_complaint_cat = ComplaintCategory::where('is_deleted',false)->get();
                $complaint_cat = $all_complaint_cat->where('plant_id',$data->plant);
            } else {
                $all_complaint_cat = $complaint_cat = ComplaintCategory::where('is_deleted',false)->get();
            }
            $resname = DB::table('users')->where('email', $res)->value('name');
            $issue_reviewers = Complaintreviewer::pluck('email')->toArray();
            $countries = Country::all();
            $states = State::all();
            $team_members = DB::table('complaint_investigators')->where('complaint_id',$data->id)->pluck('user_id')->toArray();
            $customer = Customer::where('id',$data->customer)->where('is_deleted', false)->get(['id','customer']);
            $models = DB::table('modenadisable')->First();
            $units = ComplaintUnit::where('is_deleted',false)->get();
            $issuedocs = ComplaintIssueFile::where('complaint_id',$id)->where('is_deleted',false)->get();
            $vendors = Vendor::where('is_deleted', false)->get();
            return view('user/complaint/review')->with([
                'resname' => $resname,
                'departments' => $departments,
                'type' => $type,
                'model' => $models,
                'data' => $data,
                'user' => $user,
                'id' => $id,
                'files' => $files,
                'issue_reviewers' => $issue_reviewers,
                'employees' => $employees,
                'customer' => $customer,
                'product' => $product,
                'plants' => $plants,
                'complaint_cat' => $complaint_cat,
                'all_complaint_cat' => $all_complaint_cat,
                'countries' => $countries,
                'states' => $states,
                'product_details' => $product_details,
                'team_members' => $team_members,
                'units' => $units,
                'issuedocs' => $issuedocs,
                'category_plant_dependency' =>  $category_plant_dependency,
                'vendors' => $vendors
            ]);

        }
    }
    // while entry review approving
    public function review(Request $req)
    {
        $complaint = Complaint::find($req->compid);

        if($complaint->complaint_template_section_field_rules) {
            $dynamicFieldValidation =  ComplaintFormConfigurationHelper::getComplaintConfigurationPostMethodRules($complaint->complaint_template_section_field_rules);
            $staticFieldValidationRules = [
                'employee' => 'nullable|string',
                'crdate' => 'date|required',
                'modeofcomplaint' => 'string|nullable',
                'title' => 'required|string',
                'type' =>  'string|nullable',
                'plant' => 'required|integer|exists:complaint_plants,id',
                'meta.vendor_id' => 'nullable|exists:vendor,id',
                'meta.no_of_occurrence' => 'nullable|integer|min:1',
                'department' => 'string|required|exists:departments,id',
                'responsibility' => 'string|required|exists:users,email',
                'action' => 'string|required',
                'crdate' => 'date|nullable',
                'lead' => 'required_if:seperate,yes',
                'financial_loss' => 'nullable|string|max:32',
                'potential_cost_savings' => 'nullable|string|max:32',
                'investigation_date' => 'required_if:seperate,yes',
            ];

            if($complaint->complaint_template_id == 2) {
                $staticFieldValidationRules['meta.complaint_category_id'] = 'required|exists:complaint_categories,id';
                $staticFieldValidationRules['meta.warranty_coverage'] = [
                    'required',\Illuminate\Validation\Rule::in(
                        collect(Constants::WARRANTY_COVERAGES)->pluck('title')->toArray()
                    )];
            } else {
                $staticFieldValidationRules['meta.complaint_category_id'] = 'nullable|exists:complaint_categories,id';
            }

            $rules = array_merge($staticFieldValidationRules, $dynamicFieldValidation['rules']);

            $staticFieldValidationMessages = [
                'title.required' => 'The title field is required',
                'crdate.required' => 'The Complaint Received Date field is required',
                'plant.required' => 'The Plant field is required',
                'departmentname.required' => 'The Responsible Department field is required',
                'responsibility.required' => 'The Responsibility field is required',
                'action.required' => 'The Recommended Action field is required',
                'lead.required_if' => 'The Team leader field is mandatory, if investigation required',
                'investigation_date.required_if' => 'The investigation date is mandatory, if investigation required',
                'meta.no_of_occurrence.min' => 'Number of occurrences must be at least 1.',
            ];
            $message = array_merge($staticFieldValidationMessages, $dynamicFieldValidation['messages']);

        } else {
            $rules = [
                'employee' => 'nullable|string',
                'crdate' => 'date|required',
                'modeofcomplaint' => 'string|nullable',
                'title' => 'required|string',
                'plant' => 'string|required|exists:complaint_plants,id',
                'customer' => 'string|required',
                'customerphone' => 'nullable|numeric',
                'customeremail' => 'string|required',
                'customeraddress' => 'string|nullable',
                'product' => 'required|array',
                'product.*' => 'required|string',
                'saleorder' => 'string|required',
                'deliverydate' => 'date|required',
                'deliveryno' => 'string|required',
                'itemcode' =>  'array|required',
                'itemcode.*' => 'string|required',
                'productdesc' => 'array|required',
                'productdesc.*' => 'string|required',
                'sizeofitem' => 'array|required',
                'sizeofitem.*' => 'string|required',
                'idno' => 'array|required',
                'idno.*' => 'string|required',
                'totalquant' => 'array|required',
                'totalquant.*' => 'string|required',
                'affectquant' => 'array|required',
                'affectquant.*' => 'string|required',
                'unit' => 'array|required',
                'unit.*' => 'string|required',
                'costimpact' => 'array|required',
                'costimpact.*' => 'string|required',
                'complaint' => 'string|required',
                'complaintcategory.*' => 'array|required',
                'complaintcategory.*' => 'array|required',
                'type' =>  'string|nullable',
                'department' => 'string|required|exists:departments,id',
                'responsibility' => 'string|required|exists:users,email',
                'action' => 'string|required',
                'crdate' => 'date|nullable',
                'lead' => 'required_if:seperate,yes',
                'financial_loss' => 'nullable|string|max:32',
                'potential_cost_savings' => 'nullable|string|max:32',
                'investigation_date' => 'required_if:seperate,yes',
            ];
            $message = [
                'title.required' => 'The title field is required',
                'crdate.required' => 'The Complaint Received Date field is required',
                'customer.required' => 'The Customer Name field',
                'customeremail.required' => 'The Customer Email field is required',
                'plant.required' => 'The Plant field is required',
                'customerphone.numeric' => 'The customer phone must be a numeric value.',
                'product.required' => 'The Product/Service field is required',
                'product.*.required' => 'The Product/Service field is required',
                'saleorder.required' => 'The Sale Order No. filed is required',
                'deliverydate.required' => 'The Delivery Date field is required',
                'deliveryno.required' => 'The Delivery No. field is required',
                'itemcode.required' => 'The Item Code field is required',
                'itemcode.*.required' => 'The Item Code field is required',
                'productdesc.required' => 'The Product Description field is required',
                'productdesc.*.required' => 'The Prodcut Description field is required',
                'idno.required' => 'The ID No. field is required',
                'idno.*.required' => 'The ID No. field is required',
                'totalquant.required' => 'The Total Delivery Quantity field is required',
                'totalquant.*.required' => 'The Total Delivery Quantity field is required',
                'affectquant.required' => 'The Affected Quantity field is required',
                'affectquant.*' => 'The Affected Quantity field is required',
                'unit.required' => 'The Unit field is required',
                'unit.*.required' => 'The Unit field is required',
                'costimpact.required' => 'The Cost Impact field is required',
                'costimpact.*.required' => 'The Cost Impact field is required',
                'complaint.required' => 'The Complaint Description field is required',
                'complaintcategory.required' => 'The Complaint Category field is required',
                'complaintcategory.*.required' => 'The Complaint Category field is required',
                'departmentname.required' => 'The Responsible Department field is required',
                'responsibility.required' => 'The Responsibility field is required',
                'action.required' => 'The Recommended Action field is required',
                'lead.required_if' => 'The Team leader field is mandatory, if investigation required',
                'investigation_date.required_if' => 'The investigation date is mandatory, if investigation required',
            ];

        }

        $validator = Validator::make($req->all(), $rules,$message);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $Status = "Approved";
            $id = $req->input('compid');
            date_default_timezone_set('Asia/Kuwait');
            $reviewtime = date('d-m-Y h:i:s A', time());
            $approvedby = Auth::user()->employeeid;
            $title = $req->input('title');
            $complaint = $req->input('complaint');
            $customer = $req->input('customer');
            $product = $req->input('product') ? implode(',',$req->input('product')) : null;
            $type = $req->input('type');
            $action = $req->input('action');
            $department = $req->input('department');
            $division = $req->input('division');
            $area = $req->input('area');
            $responsibility = $req->input('responsibility');
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
            $date = $req->input('date');
            $crdate = $req->input('crdate');
            $employee = $req->input('employee');
            $modeofcomplaint = $req->input('modeofcomplaint');
            $plant = $req->input('plant');
            $customerphone = $req->input('customerphone');
            $customeremail = $req->input('customeremail');
            $customeraddress = $req->input('customeraddress');
            $saleorder = $req->input('saleorder');
            $deliverydate = $req->input('deliverydate');
            $deliveryno = $req->input('deliveryno');
            $complaintcategory = $req->input('complaintcategory');
            $lead = $req->input('lead');
            $investigation = $req->input('investigation') ? implode(',',$req->input('investigation')) : null;
            $investigation_date = $req->input('investigation_date');
            $financial_loss = $req->input('financial_loss');
            $potential_cost_savings = $req->input('potential_cost_savings');
            //---------------------------------------Record------------------

            $data = [
                'title' => $title,
                'complaint' => $complaint,
                'customer' => $customer ?? null,
                'product' => $product ?? null,
                'type' => $type,
                'action' => $action,
                'dept' => $department,
                'responsibility' => $responsibility,
                'eremainder' => $frequency,
                'sendnotif' => $notification,
                'tardate' => $date,
                'crdate' => $crdate,
                'employee' => $employee,
                'updated_at' => now(),
                'mode_of_complaint' => $modeofcomplaint,
                'plant' => $plant,
                'customer_phone' => $customerphone ?? null,
                'customer_email' => $customeremail ?? null,
                'customer_address' => $customeraddress ?? null,
                'sale_order_no' => $saleorder ?? null,
                'delivery_date' => $deliverydate ?? null,
                'delivery_no' => $deliveryno ?? null,
                'job_order_no' => $req->input('job_order_no') ?? null,
                'product_serial_no' => $req->input('product_serial_no') ?? null,
                'project_name' => $req->input('project_name') ?? null,
                'job_history' => $req->input('job_history') ?? null,
                'team_lead_id' => $lead,
                'investigation_date' => $investigation_date,
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
                'status' => "Approved",
                'approvement' => 'yes',
                'approved_by' => $approvedby,
                'approved_at' => $reviewtime,
                'division_id' => $division,
                'area_id' => $area,
            ];
            DB::table('complaint')
                ->where('id', $id)
                ->update($data);
            // updating meta table
            $meta = $req->input('meta', []);

            if (!isset($meta['no_of_occurrence'])) {
                $meta['no_of_occurrence'] = null;
            }

            foreach ($meta as $key => $value) {
                if (!is_null($value)) {
                    ComplaintMeta::updateOrCreate(
                        ['complaint_id' => $id, 'meta_key' => $key],
                        ['meta_value' => $value]
                    );
                } else {
                    ComplaintMeta::where('complaint_id', $id)
                        ->where('meta_key', $key)
                        ->delete();
                }
            }

            /** ---------------------For Product Details ------------------------------ */
            $product_desc_id = $req->input('product_desc_id');
            $itemcodes = $req->input('itemcode');
            $productdesc = $req->input('productdesc');
            $idno = $req->input('idno');
            $totalquant = $req->input('totalquant');
            $affectquant = $req->input('affectquant');
            $unit = $req->input('unit');
            $costimpact = $req->input('costimpact');
            $category = $req->input('complaintcategory');
            // $collection = collect($category);
            // $reindexedCollection = $collection->values();
            // $category = $reindexedCollection->toArray();
            $srr = $req->input('salereturnreq');
            $rr = $req->input('reworkreq');
            $sizeofitem = $req->input('sizeofitem');
            $product_details = ComplaintProductDetail::where('complaint_id',$id)->pluck('id')->toArray();
            foreach($product_details as $pd){
                if(!in_array($pd,$product_desc_id)){
                    ComplaintProductDetail::where('id',$pd)->delete();
                }
            }
            if ($itemcodes && count($itemcodes)) {
                $count = 0;
                for($i=0;$i<count($product_desc_id);$i++){
                    ComplaintProductDetail::where('id',$product_desc_id[$i])->update([
                        'item_code' => $itemcodes[$i] ?? null,
                        'product_description' =>$productdesc[$i] ?? null,
                        'idno' => $idno[$i] ?? null,
                        'total_delivery_quantity' => $totalquant[$i] ?? null,
                        'affected_quantity' => $affectquant[$i] ?? null,
                        'unit' =>  $unit[$i] ?? null,
                        'cost_impact' => $costimpact[$i] ?? null,
                        'complaint_category' => $category && isset($category[$i+1]) ? implode(',',$category[$i+1]) : null,
                        'size_of_item' => $sizeofitem[$i] ?? null,
                        'sale_return_request' => $srr[$i] ?? null,
                        'rework_request' => $rr[$i] ?? null,
                        'updated_at' => now(),
                    ]);
                    $count++;
                }
                $totalcodes = count($itemcodes);
                for($i=$count;$i<$totalcodes;$i++) {
                    ComplaintProductDetail::insert([
                        'complaint_id' => $id,
                        'item_code' => $itemcodes[$i] ?? null,
                        'product_description' =>$productdesc[$i] ?? null,
                        'idno' => $idno[$i] ?? null,
                        'total_delivery_quantity' => $totalquant[$i] ?? null,
                        'affected_quantity' => $affectquant[$i] ?? null,
                        'unit' =>  $unit[$i] ?? null,
                        'cost_impact' => $costimpact[$i] ?? null,
                        'complaint_category' => $category && isset($category[$i+1]) ? implode(',',$category[$i+1]) : null,
                        'sale_return_request' => $srr[$i] ?? null,
                        'rework_request' => $rr[$i] ?? null,
                        'size_of_item' => $sizeofitem[$i] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            /** ---------------------For Team Members ------------------------------ */

            $investigators = $req->input('investigation');
            if ($investigators && count($investigators)) {
                foreach ($investigators as $investigator) {
                    if ($investigator) {
                        $found = ComplaintInvestigator::where('user_id', $investigator)->where('complaint_id', $id)->first();
                        if (!$found) {
                            ComplaintInvestigator::insert([
                                'user_id' => $investigator,
                                'complaint_id' => $id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }

                    }
                }
            }
            $data = DB::table('complaint')->where('id', $id)->first();
            $depname = Departments::where('id',$data->dept)->value('departmentname');
            $addedbyname = User::where('employeeid',$data->addedby)->value('name');
            $addedbyemail = User::where('employeeid',$data->addedby)->value('email');
            $removedFileIds = json_decode($req->input('removedFileIds'),true);
            $removedIssueFileIds = json_decode($req->input('removedIssueFileIds'),true);
            if(count($removedFileIds)>0){
               DB::table('complaintfiles')->whereIn('sno',$removedFileIds)->delete();
            }
            if(count($removedIssueFileIds)>0){
                ComplaintIssueFile::whereIn('id',$removedIssueFileIds)->update([
                    'updated_at' => now(),
                    'is_deleted' => true
                ]);
            }
            /**-----------------------------Start Send mails to Respnisbile and Notification-------------------- */
            $com_respon_emails = EmailNotification::where('module', 'complaintmanagement')
                ->where('item', 'Complaint Response Notification')
                    ->where('is_blocked', false)
                    ->first();
            if ($com_respon_emails) {
                $emaildata = array(
                    'email_content' => $com_respon_emails->email_content,
                    'item' => $com_respon_emails->item,
                    'trackno' => $data->trackno,
                    'addedby' => $addedbyname,
                    'type' => $data->type,
                    'dept' => $depname,
                    'tardate' => $data->tardate ? date("d/m/Y", strtotime($data->tardate)) : '',
                );
                // Sending Mail(Responsibility Notification) To responsibile HOD
                $responsibilityMail = getNonDeletedUsersEmail($data->responsibility);
                if (count($responsibilityMail)>0) {
                    Mail::to($data->responsibility)->send(new ComplaintResponseNotification($emaildata));
                }
                $notifi = $data->sendnotif ? getNonDeletedUsersEmail(explode(',', $data->sendnotif)) : [];
                if (count($notifi) > 0) {
                    Mail::to($notifi)->send(new ComplaintResponseNotification($emaildata));
                }

                //sending mail for participants and management participants
                $com_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_respon_emails->id)->get();
                if (count($com_respon_userdatas) > 0) {
                    $adminaddedusers = $com_respon_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new ComplaintResponseNotification($emaildata));
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for Complaint Response Notification');
            }
            /**-----------------------------End Send mails to Respnisbile and Notification-------------------- */
            /**-----------------------------Start Send mails to Posted by-------------------- */

            $com_app_emails = EmailNotification::where('module', 'complaintmanagement')
                ->where('item', 'Complaint Issue Approved')
                    ->where('is_blocked', false)
                    ->first();
            if ($com_app_emails) {
                $emaildata = array(
                    'email_content' => $com_app_emails->email_content,
                    'item' => $com_app_emails->item,
                    'trackno' => $data->trackno,
                    'type' => $data->type,
                    'dept' => $depname,
                );
                // Sending Mail(Responsibility Notification) To responsibile HOD
                if ($addedbyemail) {
                    Mail::to($addedbyemail)->send(new ComplaintIssueApproved($emaildata));
                }
                $com_app_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_app_emails->id)->get();
                if (count($com_app_userdatas) > 0) {
                    $adminaddedusers = $com_app_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new ComplaintIssueApproved($emaildata));
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for Complaint Issue Approved');
            }
            /**-----------------------------End Send mails to Posted By-------------------- */

            // inserting values to activity table
            $activity = "Complaint  Status is Updated to Approved where  Complaint Id is" . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            // end of superadmin codes
            $models = DB::table('modenadisable')->First();
            return redirect('/user/complaintmgmt')->with('model', $models);
        }
    }
    //while entry review disapproving
    public function disapprove()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $models = DB::table('modenadisable')->First();
            return view('user.complaint.dissapprove')->withId($id)->with('model', $models);
        }
    }
    public function disapproving(Request $req)
    {
        $reason = $req->input('reason');
        $id = $req->input('id');
        $reviewtime = date('d-m-Y h:i:s A', time());
        $approvedby =  Auth::user()->employeeid;
        $data = array(
            'status' => 'Rejected', 'approved_at' => $reviewtime,
            'reason' => $reason, 'approved_by' => $approvedby,
        );
        DB::table('complaint')
            ->where('id', $id)
            ->update($data);
        $data1 = DB::table('complaint')->where('id', $id)->first();
        /**-----------------------------Start Send mails to Posted by-------------------- */
        $depname = Departments::where('id',$data1->dept)->value('departmentname');
        $addedbyemail = User::where('employeeid', $data1->addedby)->value('email');
         $com_app_emails = EmailNotification::where('module', 'complaintmanagement')
             ->where('item', 'Complaint Issue Disapproved')
             ->where('is_blocked', false)
             ->first();
        if ($com_app_emails) {
            $emaildata = array(
                'email_content' => $com_app_emails->email_content,
                'item' => $com_app_emails->item,
                'trackno' => $data1->trackno,
                'type' => $data1->type,
                'dept' => $depname,
                'reason' => $reason,
            );
            // Sending Mail(Responsibility Notification) To responsibile HOD
            if ($addedbyemail) {
                Mail::to($addedbyemail)->send(new ComplaintIssueDisapproved($emaildata));
            }
            $com_app_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_app_emails->id)->get();
            if (count($com_app_userdatas) > 0) {
                $adminaddedusers = $com_app_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas) > 0) {
                    Mail::to($userdatas)->send(new ComplaintIssueDisapproved($emaildata));
                }
            }
        } else {
            Session::flash('msg', 'Mail Sending Blocked for Complaint Issue Disapproved');
        }
        /**-----------------------------End Send mails to Posted By-------------------- */
        //inserting values to activity table superadmin code
        $activity = "Complaint is Rejeceted and its Status Updated to Rejected where Complaint Id is" . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin codes
        $models = DB::table('modenadisable')->First();
        return redirect('/user/complaintmgmt')->with('model', $models);
    }

    //resubmit for raised complaint
    public function resubmit()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $docs = DB::table('complaintfiles')->where('id',$id)->get();
            $files = [];
            foreach($docs as $doc){
                if($doc && $doc->files!="NULL"){
                    $files[] = $doc;
                }
            }
            // fetching complaint
            $data = Complaint::with('complaintMeta', 'previousComplaintRef')->where('id', $id)->first();
            // adding complaint meta datas as column of complaint
            $result = collect($data->toArray());
            $meta = collect($result['complaint_meta'])->pluck('meta_value', 'meta_key');
            // final data with meta table value
            $data = (object)$result->except('complaint_meta')->merge($meta)->toArray();
            //

            $employees = EmployeeDetails::where('is_deleted',false)->get();
            $user = User::where('is_email_verified',true)->orderBy('name')->get();
            //$departments = Departments::all();
            $departments = Departments::where('is_active', true)
                                        ->orWhere('id', $data->dept)
                                        ->get();
            $type = Type::all();
            $res = $data->responsibility;
            $product_details = ComplaintProductDetail::where('complaint_id',$data->id)->get();
            $product = Product::get();
            $plants = ComplaintPlant::where('is_deleted',false)->get();
            $category_plant_dependency = AppConfiguration::where('meta_key', 'complaint_category_plant_dependency')->first();
            if ($category_plant_dependency->meta_value != 'Independent') {
                $all_complaint_cat = ComplaintCategory::where('is_deleted',false)->get();
                $complaint_cat = $all_complaint_cat->where('plant_id',$data->plant);
            } else {
                $all_complaint_cat = $complaint_cat = ComplaintCategory::where('is_deleted',false)->get();
            }
            $resname = DB::table('users')->where('email', $res)->value('name');
            $issue_reviewers = Complaintreviewer::pluck('email')->toArray();
            $countries = Country::all();
            $states = State::all();
            $team_members = DB::table('complaint_investigators')->where('complaint_id',$data->id)->pluck('user_id')->toArray();
            $customer = Customer::where('id',$data->customer)->where('is_deleted', false)->get(['id','customer']);
            $models = DB::table('modenadisable')->First();
            $units = ComplaintUnit::where('is_deleted',false)->get();
            $issuedocs = ComplaintIssueFile::where('complaint_id',$id)->where('is_deleted',false)->get();
            $vendors = Vendor::where('is_deleted', false)->get();
            return view('user.complaint.resubmit')->with([
                'resname' => $resname,
                'departments' => $departments,
                'type' => $type,
                'model' => $models,
                'data' => $data,
                'user' => $user,
                'id' => $id,
                'files' => $files,
                'issue_reviewers' => $issue_reviewers,
                'employees' => $employees,
                'customer' => $customer,
                'product' => $product,
                'category_plant_dependency' => $category_plant_dependency,
                'plants' => $plants,
                'complaint_cat' => $complaint_cat,
                'all_complaint_cat' => $all_complaint_cat,
                'countries' => $countries,
                'states' => $states,
                'product_details' => $product_details,
                'team_members' => $team_members,
                'units' => $units,
                'issuedocs' => $issuedocs,
                'vendors' => $vendors
            ]);

        }
    }
    //resubmit request
    public function resubmitcomplaint(Request $req)
    {

        $complaint = Complaint::find($req->compid);

        if($complaint->complaint_template_section_field_rules) {
            $dynamicFieldValidation =  ComplaintFormConfigurationHelper::getComplaintConfigurationPostMethodRules($complaint->complaint_template_section_field_rules);
            $staticFieldValidationRules = [
                'employee' => 'nullable|string',
                'crdate' => 'date|required',
                'modeofcomplaint' => 'string|nullable',
                'title' => 'required|string',
                'type' =>  'string|nullable',
                'plant' => 'required|integer|exists:complaint_plants,id',
                'meta.vendor_id' => 'nullable|exists:vendor,id',
                'meta.no_of_occurrence' => 'nullable|integer|min:1',
                'department' => 'string|required|exists:departments,id',
                'responsibility' => 'string|required|exists:users,email',
                'action' => 'string|required',
                'crdate' => 'date|nullable',
            ];
            if($complaint->complaint_template_id == 2) {
                $staticFieldValidationRules['meta.complaint_category_id'] = 'required|exists:complaint_categories,id';
                $staticFieldValidationRules['meta.warranty_coverage'] = [
                    'required',\Illuminate\Validation\Rule::in(
                        collect(Constants::WARRANTY_COVERAGES)->pluck('title')->toArray()
                    )];
            } else {
                $staticFieldValidationRules['meta.complaint_category_id'] = 'nullable|exists:complaint_categories,id';
            }

            $rules = array_merge($staticFieldValidationRules, $dynamicFieldValidation['rules']);

            $staticFieldValidationMessages = [
                'title.required' => 'The title field is required',
                'crdate.required' => 'The Complaint Received Date field is required',
                'plant.required' => 'The Plant field is required',
                'departmentname.required' => 'The Responsible Department field is required',
                'responsibility.required' => 'The Responsibility field is required',
                'action.required' => 'The Recommended Action field is required',
                'meta.no_of_occurrence.min' => 'Number of occurrences must be at least 1.',
            ];
            $message = array_merge($staticFieldValidationMessages, $dynamicFieldValidation['messages']);

        } else {
            $rules = [
                'employee' => 'nullable|string',
                'crdate' => 'date|required',
                'modeofcomplaint' => 'string|nullable',
                'title' => 'required|string',
                'plant' => 'string|required|exists:complaint_plants,id',
                'customer' => 'string|required',
                'customerphone' => 'nullable|numeric',
                'customeremail' => 'string|required',
                'customeraddress' => 'string|nullable',
                'product' => 'required|array',
                'product.*' => 'required|string',
                'saleorder' => 'string|required',
                'deliverydate' => 'date|required',
                'deliveryno' => 'string|required',
                'itemcode' =>  'array|required',
                'itemcode.*' => 'string|required',
                'productdesc' => 'array|required',
                'productdesc.*' => 'string|required',
                'sizeofitem' => 'array|required',
                'sizeofitem.*' => 'string|required',
                'idno' => 'array|required',
                'idno.*' => 'string|required',
                'totalquant' => 'array|required',
                'totalquant.*' => 'string|required',
                'affectquant' => 'array|required',
                'affectquant.*' => 'string|required',
                'unit' => 'array|required',
                'unit.*' => 'string|required',
                'costimpact' => 'array|required',
                'costimpact.*' => 'string|required',
                'complaint' => 'string|required',
                'complaintcategory.*' => 'array|required',
                'complaintcategory.*' => 'array|required',
                'type' =>  'string|nullable',
                'department' => 'string|required|exists:departments,id',
                'responsibility' => 'string|required|exists:users,email',
                'action' => 'string|required',
                'crdate' => 'date|nullable',
            ];

            $message = [
                'title.required' => 'The title field is required',
                'crdate.required' => 'The Complaint Received Date field is required',
                'customer.required' => 'The Customer Name field',
                'customeremail.required' => 'The Customer Email field is required',
                'plant.required' => 'The Plant field is required',
                'customerphone.numeric' => 'The customer phone must be a numeric value.',
                'product.required' => 'The Product/Service field is required',
                'product.*.required' => 'The Product/Service field is required',
                'saleorder.required' => 'The Sale Order No. filed is required',
                'deliverydate.required' => 'The Delivery Date field is required',
                'deliveryno.required' => 'The Delivery No. field is required',
                'itemcode.required' => 'The Item Code field is required',
                'itemcode.*.required' => 'The Item Code field is required',
                'productdesc.required' => 'The Product Description field is required',
                'productdesc.*.required' => 'The Prodcut Description field is required',
                'idno.required' => 'The ID No. field is required',
                'idno.*.required' => 'The ID No. field is required',
                'totalquant.required' => 'The Total Delivery Quantity field is required',
                'totalquant.*.required' => 'The Total Delivery Quantity field is required',
                'affectquant.required' => 'The Affected Quantity field is required',
                'affectquant.*' => 'The Affected Quantity field is required',
                'unit.required' => 'The Unit field is required',
                'unit.*.required' => 'The Unit field is required',
                'costimpact.required' => 'The Cost Impact field is required',
                'costimpact.*.required' => 'The Cost Impact field is required',
                'complaint.required' => 'The Complaint Description field is required',
                'complaintcategory.required' => 'The Complaint Category field is required',
                'complaintcategory.*.required' => 'The Complaint Category field is required',
                'departmentname.required' => 'The Responsible Department field is required',
                'responsibility.required' => 'The Responsibility field is required',
                'action.required' => 'The Recommended Action field is required',
            ];
        }
        $validator = Validator::make($req->all(), $rules,$message);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $Status = "Under Review";
            $id = $req->input('compid');
            date_default_timezone_set('Asia/Kuwait');
            $title = $req->input('title');
            $complaint = $req->input('complaint');
            $customer = $req->input('customer');
            $product = $req->input('product') ? implode(',',$req->input('product')) : null;
            $type = $req->input('type');
            $action = $req->input('action');
            $department = $req->input('department');
            $division = $req->input('division');
            $area = $req->input('area');
            $responsibility = $req->input('responsibility');
            $date = $req->input('date');
            $crdate = $req->input('crdate');
            $employee = $req->input('employee');
            $modeofcomplaint = $req->input('modeofcomplaint');
            $plant = $req->input('plant');
            $customerphone = $req->input('customerphone');
            $customeremail = $req->input('customeremail');
            $customeraddress = $req->input('customeraddress');
            $saleorder = $req->input('saleorder');
            $deliverydate = $req->input('deliverydate');
            $deliveryno = $req->input('deliveryno');
            //---------------------------------------Record------------------
            $removedFileIds = json_decode($req->input('removedFileIds'),true);
            $removedIssueFileIds = json_decode($req->input('removedIssueFileIds'),true);
            if(count($removedFileIds)>0){
               DB::table('complaintfiles')->whereIn('sno',$removedFileIds)->delete();
            }
            if(count($removedIssueFileIds)>0){
                ComplaintIssueFile::whereIn('id',$removedIssueFileIds)->update([
                    'updated_at' => now(),
                    'is_deleted' => true
                ]);
            }
            $data = [
                'title' => $title,
                'complaint' => $complaint,
                'customer' => $customer ?? null,
                'product' => $product ?? null,
                'type' => $type,
                'action' => $action,
                'dept' => $department,
                'responsibility' => $responsibility,
                'tardate' => $date,
                'crdate' => $crdate,
                'employee' => $employee,
                'approved_by' => null,
                'approved_at' => null,
                'updated_at' => now(),
                'mode_of_complaint' => $modeofcomplaint,
                'plant' => $plant,
                'customer_phone' => $customerphone ?? null,
                'customer_email' => $customeremail ?? null,
                'customer_address' => $customeraddress ?? null,
                'sale_order_no' => $saleorder ?? null,
                'delivery_date' => $deliverydate ?? null,
                'delivery_no' => $deliveryno ?? null,
                'job_order_no' => $req->input('job_order_no') ?? null,
                'product_serial_no' => $req->input('product_serial_no') ?? null,
                'project_name' => $req->input('project_name') ?? null,
                'job_history' => $req->input('job_history') ?? null,
                'status' => $Status,
                'approvement' => 'yes',
                'actionstatus' => "Not Started-0%",
                'division_id' => $division,
                'area_id'=> $area
            ];
            DB::table('complaint')
                ->where('id', $id)
                ->update($data);

            // updating meta table
            $meta = $req->input('meta', []);

            if (!isset($meta['no_of_occurrence'])) {
                $meta['no_of_occurrence'] = null;
            }

            foreach ($meta as $key => $value) {
                if (!is_null($value)) {
                    ComplaintMeta::updateOrCreate(
                        ['complaint_id' => $id, 'meta_key' => $key],
                        ['meta_value' => $value]
                    );
                } else {
                    ComplaintMeta::where('complaint_id', $id)
                        ->where('meta_key', $key)
                        ->delete();
                }
            }

            $destination_path = public_path('storage/complaint');
            if ($req->hasFile('file')) {
                $file_array = $req->file('file');
                $insertData = [];
                foreach ($file_array as $file) {
                    $new_file_name = $file->getClientOriginalName();
                    $file->move($destination_path, $new_file_name);
                    $insertData[] = [
                        'complaint_id' => $id,
                        'issue_file' => $new_file_name,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                ComplaintIssueFile::insert($insertData);
            }
            /** ---------------------For Product Details ------------------------------ */
            $product_desc_id = $req->input('product_desc_id');
            $itemcodes = $req->input('itemcode');
            $productdesc = $req->input('productdesc');
            $idno = $req->input('idno');
            $totalquant = $req->input('totalquant');
            $affectquant = $req->input('affectquant');
            $unit = $req->input('unit');
            $costimpact = $req->input('costimpact');
            $category = $req->input('complaintcategory');
            // $collection = collect($category);
            // $reindexedCollection = $collection->values();
            // $category = $reindexedCollection->toArray();
            $srr = $req->input('salereturnreq');
            $rr = $req->input('reworkreq');
            $sizeofitem = $req->input('sizeofitem');
            $product_details = ComplaintProductDetail::where('complaint_id',$id)->pluck('id')->toArray();
            foreach($product_details as $pd){
                if(!in_array($pd,$product_desc_id)){
                    ComplaintProductDetail::where('id',$pd)->delete();
                }
            }
            if ($itemcodes && count($itemcodes)) {
                $count = 0;
                for($i=0;$i<count($product_desc_id);$i++){
                    ComplaintProductDetail::where('id',$product_desc_id[$i])->update([
                        'item_code' => $itemcodes[$i] ?? null,
                        'product_description' =>$productdesc[$i] ?? null,
                        'idno' => $idno[$i] ?? null,
                        'total_delivery_quantity' => $totalquant[$i] ?? null,
                        'affected_quantity' => $affectquant[$i] ?? null,
                        'unit' =>  $unit[$i] ?? null,
                        'cost_impact' => $costimpact[$i] ?? null,
                        'complaint_category' => $category && isset($category[$i+1]) ? implode(',',$category[$i+1]) : null,
                        'sale_return_request' => $srr[$i] ?? null,
                        'size_of_item' => $sizeofitem[$i] ?? null,
                        'rework_request' => $rr[$i] ?? null,
                        'updated_at' => now(),
                    ]);
                    $count++;
                }
                $totalcodes = count($itemcodes);
                for($i=$count;$i<$totalcodes;$i++) {
                    ComplaintProductDetail::insert([
                        'complaint_id' => $id,
                        'item_code' => $itemcodes[$i] ?? null,
                        'product_description' =>$productdesc[$i] ?? null,
                        'idno' => $idno[$i] ?? null,
                        'total_delivery_quantity' => $totalquant[$i] ?? null,
                        'affected_quantity' => $affectquant[$i] ?? null,
                        'unit' =>  $unit[$i] ?? null,
                        'cost_impact' => $costimpact[$i] ?? null,
                        'complaint_category' => $category && isset($category[$i+1]) ? implode(',',$category[$i+1]) : null,
                        'sale_return_request' => $srr[$i] ?? null,
                        'size_of_item' => $sizeofitem[$i] ?? null,
                        'rework_request' => $rr[$i] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            /** ---------------------For Team Members ------------------------------ */
            $data = DB::table('complaint')->where('id', $id)->first();
            $depname = Departments::where('id',$data->dept)->value('departmentname');
            $addedbyname = User::where('employeeid',$data->addedby)->value('name');
            $addedbyemail = User::where('employeeid',$data->addedby)->value('email');
            /**-----------------------------Start Send mails to Respnisbile and Notification-------------------- */
            $com_respon_emails = EmailNotification::where('module', 'complaintmanagement')
                ->where('item', 'Complaint Issue Resubmit Notification')
                    ->where('is_blocked', false)
                    ->first();
            if ($com_respon_emails) {
                $emaildata = array(
                    'email_content' => $com_respon_emails->email_content,
                    'item' => $com_respon_emails->item,
                    'trackno' => $data->trackno,
                    'addedby' => $addedbyname,
                    'type' => $data->type,
                    'dept' => $depname,
                    'tardate' => $data->tardate ? date("d/m/Y", strtotime($data->tardate)) : '',
                );
                // Sending Mail(Responsibility Notification) To responsibile HOD
                $responsibilityMail = getNonDeletedUsersEmail($data->responsibility);
                if (count($responsibilityMail)>0) {
                    Mail::to($responsibilityMail)->send(new ComplaintIssueResubmitNotification($emaildata));
                }
                $notifi = $data->sendnotif ? getNonDeletedUsersEmail(explode(',', $data->sendnotif)) : [];
                if (count($notifi) > 0) {
                    Mail::to($notifi)->send(new ComplaintIssueResubmitNotification($emaildata));
                }
                //sending mail for participants and management participants
                $com_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_respon_emails->id)->get();
                if (count($com_respon_userdatas) > 0) {
                    $adminaddedusers = $com_respon_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new ComplaintIssueResubmitNotification($emaildata));
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for Complaint Issue Resubmit Notification');
            }
            /**-----------------------------End Send mails to Respnisbile and Notification-------------------- */
            /**-----------------------------Start Send mails to Posted by-------------------- */

            $com_app_emails = EmailNotification::where('module', 'complaintmanagement')
                ->where('item', 'Complaint Issue Resubmit')
                    ->where('is_blocked', false)
                    ->first();
            if ($com_app_emails) {
                $emaildata = array(
                    'email_content' => $com_app_emails->email_content,
                    'item' => $com_app_emails->item,
                    'trackno' => $data->trackno,
                    'type' => $data->type,
                    'dept' => $depname,
                    'addedby' => $addedbyname,
                    'tardate' => $data->tardate ? date("d/m/Y", strtotime($data->tardate)) : '',
                );
                $emails = getNonDeletedUsersEmail(Complaintreviewer::pluck('email')->toArray());
                if(count($emails)>0){
                    Mail::to($emails)->send(new ComplaintResubmitIssue($emaildata));
                }
                // Sending Mail(Responsibility Notification) To responsibile HOD
                if ($addedbyemail) {
                    Mail::to($addedbyemail)->send(new ComplaintResubmitIssue($emaildata));
                }
                $com_app_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_app_emails->id)->get();
                if (count($com_app_userdatas) > 0) {
                    $adminaddedusers = $com_app_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new ComplaintResubmitIssue($emaildata));
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for Complaint Issue Resubmit');
            }
            /**-----------------------------End Send mails to Posted By-------------------- */

            // inserting values to activity table
            $activity = "Resubmitted Complaint where Complaint Name is " . $title . " and where Responsible department is " . $responsibility;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            // end of superadmin codes
            Session::flash('msg', "Complaint resubmitted for approval successfully");
            $models = DB::table('modenadisable')->First();
            return redirect('/user/complaintmgmt')->with('model', $models);
        }
    }

    //propose action
    public function action()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $files = DB::table('complaintfiles')->where('id',$id)->pluck('files')->toArray();
            // fetching complaint
            $data = Complaint::with('complaintMeta', 'previousComplaintRef')->where('id', $id)->first();
            // adding complaint meta datas as column of complaint
            $result = collect($data->toArray());
            $meta = collect($result['complaint_meta'])->pluck('meta_value', 'meta_key');
            // final data with meta table value
            $data = (object)$result->except('complaint_meta')->merge($meta)->toArray();
            //
            $employeename = EmployeeDetails::where('emp_number',$data->employee)->where('is_deleted',false)->value('emp_name');
            $employees = EmployeeDetails::where('is_deleted',false)->get();
            $customer = Customer::where('id',$data->customer)->where('is_deleted', false)->value('customer');
            $user = User::where('is_email_verified',true)->orderBy('name')->get();
            //$departments = Departments::all();
            $departments = Departments::where('is_active', true)->get();
            $type = Type::all();
            $res = $data->responsibility;
            $product_details = ComplaintProductDetail::where('complaint_id',$data->id)->get();
            $product = Product::whereIn('id',explode(',',$data->product))->pluck('product')->toArray();
            $product = count($product)>0 ? $product : [$data->product];
            $plants = ComplaintPlant::where('is_deleted',false)->get();
            if ($data->plant) {
                $complaint_cat = ComplaintCategory::where('plant_id',$data->plant)->where('is_deleted',false)->get();
            } else {
                $complaint_cat = ComplaintCategory::where('is_deleted',false)->get();
            }
            $resname = DB::table('users')->where('email', $res)->value('name');
            $issue_reviewers = Complaintreviewer::pluck('email')->toArray();
            $countries = Country::all();
            $states = State::all();
            $team_members = DB::table('complaint_investigators')->where('complaint_id',$data->id)->pluck('user_id')->toArray();
            $models = DB::table('modenadisable')->First();
            $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
            $issuedocs = ComplaintIssueFile::where('complaint_id',$id)->where('is_deleted',false)->get();
            return view('user.complaint.proposeaction')->with([
                'resname' => $resname,
                'departments' => $departments,
                'type' => $type,
                'model' => $models,
                'data' => $data,
                'user' => $user,
                'id' => $id,
                'files' => $files,
                'issue_reviewers' => $issue_reviewers,
                'employeename' => $employeename,
                'employees' => $employees,
                'customer' => $customer,
                'product' => $product,
                'plants' => $plants,
                'complaint_cat' => $complaint_cat,
                'countries' => $countries,
                'states' => $states,
                'product_details' => $product_details,
                'team_members' => $team_members,
                'rootcorrectives'=> $rootcorrectives,
                'issuedocs' => $issuedocs
            ]);

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

    public function proposeaction(Request $req)
    {
        $v = Validator::make($req->all(), [
            'correction' => 'required|string',
            'date' => 'required|date',
        ],[
            'correction.required' => 'The Correction field is required.',
            'date.required' => 'The Correction Target date field is required.',
        ]);
        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($v)->withInput()->with('model', $models);
        } else {
            $id = $req->input('compid');
            $crdate = $req->input('crdate');
            $modeofcomplaint = $req->input('modeofcomplaint');
            $customerphone = $req->input('customerphone');
            $customeraddress = $req->input('customeraddress');
            $type = $req->input('type');
            $date = $req->input('date');
            $correction = $req->input('correction');
            $financial_loss = $req->input('financial_loss');
            $potential_cost_savings = $req->input('potential_cost_savings');
            $complaint = Complaint::findOrFail($id);
            $data = [
                'type' => $type,
                'tardate' => $date,
                'crdate' => $crdate,
                'updated_at' => now(),
                'mode_of_complaint' => $modeofcomplaint,
                'customer_phone' => $customerphone,
                'customer_address' => $customeraddress,
                'correction' => $correction,
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
                'correction_proposed' => true,
            ];
            $plant = $req->input('plant');
            if($plant!=null){
                $data['plant'] = $plant;
            }
            $employee = $req->input('employee');
            if($employee!=null){
                $data['employee'] = $employee;
            }
            $division = $req->input('division');
            if($division!=null){
                $data['division_id'] = $division;
            }
            $area = $req->input('area');
            if($division!=null){
                $data['area_id'] = $area;
            }
            //---------------------------------------Record------------------
            //-------------------------Handle file--------------------------------
            // $existingFiles = DB::table('complaintfiles')->where('id',$id)->first();
            // if ($existingFiles == null) {
            //     $cer = "NULL";
            //     if ($req->hasFile('file')) {
            //         $file_array = $req->file('file');
            //         $array_len = count($file_array);
            //         $file = [];
            //         for ($i = 0; $i < $array_len; $i++) {
            //             $file_name = $file_array[$i]->getClientOriginalName();
            //             $destination_path = public_path('storage/complaint');
            //             $file_array[$i]->move($destination_path, $file_name);
            //             $file[] = $file_name;
            //         }
            //         foreach ($file as $f) {
            //             DB::table('complaintfiles')->insert(['id' => $id, 'files' => $f]);
            //         }
            //         $cer = implode(',', $file);
            //     }
            //     $data['cfiles'] = $cer;
            // }
            $destination_path = public_path('storage/complaint');
            if ($req->hasFile('file')) {
                $file_array = $req->file('file');
                $insertData = [];
                foreach ($file_array as $file) {
                    $new_file_name = $file->getClientOriginalName();
                    $file->move($destination_path, $new_file_name);
                    $insertData[] = [
                        'complaint_id' => $id,
                        'action_file' => $new_file_name,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                ComplaintActionFile::insert($insertData);
            }
            //-------------------------End Handle file--------------------------------

            /** ---------------------For Product Details ------------------------------ */
            $product_desc_id = $req->input('product_desc_id',[]);
            $srr = $req->input('salereturnreq');
            $rr = $req->input('reworkreq');
            $sizeofitem = $req->input('sizeofitem');
            if(is_array($req->input('complaintcategory'))){
                if(count($product_desc_id)>0){
                    $category = $req->input('complaintcategory');
                    $collection = collect($category);
                    $reindexedCollection = $collection->values();
                    $category = $reindexedCollection->toArray();
                    for($i=0;$i<count($product_desc_id);$i++){
                        ComplaintProductDetail::where('id',$product_desc_id[$i])->update([
                            'complaint_category' => $category && isset($category[$i+1]) ? implode(',',$category[$i+1]) : null,
                            'sale_return_request' => $srr[$i] ?? null,
                            'rework_request' => $rr[$i] ?? null,
                            'size_of_item' => $sizeofitem[$i] ?? null,
                            'updated_at' => now(),
                        ]);
                    }
                }
            }else{
                if(count($product_desc_id)>0){
                    for($i=0;$i<count($product_desc_id);$i++){
                        ComplaintProductDetail::where('id',$product_desc_id[$i])->update([
                            'sale_return_request' => $srr[$i] ?? null,
                            'rework_request' => $rr[$i] ?? null,
                            'size_of_item' => $sizeofitem[$i] ?? null,
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            $cr_ids = $req->input('rid');
            $rootcause = $req->input('rootcause');
            $correctiveaction = $req->input('correctiveaction');
            $department = $req->input('department');
            $responsibility = $req->input('responsibility');
            $corr_tardate = $req->input('corr_tardate');
            $complaint = Complaint::findOrFail($id);
            if(!($rootcause[0]==null || $correctiveaction[0]==null || $department[0]==null || $responsibility[0]==null || $corr_tardate[0]==null)){
                $complaint_rca_details_ids = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('id')->toArray();
                foreach($complaint_rca_details_ids as $crid){
                    if(!in_array($crid,$cr_ids)){
                        ComplaintRootCauseCorrectiveActionDetail::where('id',$crid)->delete();
                    }
                }
                $count = 0;
                for($i=0;$i<count($complaint_rca_details_ids);$i++){
                    ComplaintRootCauseCorrectiveActionDetail::where('id',$complaint_rca_details_ids[$i])->update([
                        'root_cause' => $rootcause[$i],
                        'corrective_action' => $correctiveaction[$i],
                        'department' => $department[$i],
                        'responsibility' => $responsibility[$i],
                        'target_date' => $corr_tardate[$i],
                        'complaint_id' => $id,
                        'updated_at' => now(),
                    ]);
                    $count++;
                }
                $total_records_to_add = count($responsibility);
                for($i=$count;$i<$total_records_to_add;$i++) {
                    ComplaintRootCauseCorrectiveActionDetail::insert([
                        'complaint_id' => $id,
                        'root_cause' => $rootcause[$i],
                        'corrective_action' => $correctiveaction[$i],
                        'department' => $department[$i],
                        'responsibility' => $responsibility[$i],
                        'target_date' => $corr_tardate[$i],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            $rc = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->first();
            /** ---------------------END Product Details ------------------------------ */
            // if both rootcause and correction updated
            if ($correction != $complaint->correction && $rc) {
                $data['status'] = "Actions Proposed- Review Awaiting";
            }else{
                $data['status'] = "Correction Proposed- RC Awaiting";
            }
            DB::table('complaint')->where('id', $id)->update($data);
            $updateddata = DB::table('complaint')->where('id', $id)->first();

            $com_up_emails = EmailNotification::where('module', 'complaintmanagement')
                ->where('item', 'Complaint Updated')
                    ->where('is_blocked', false)
                    ->first();
            if ($com_up_emails) {
                $emaildata = array(
                    'email_content' => $com_up_emails->email_content,
                    'item' => $com_up_emails->item,
                    'trackno' => $updateddata->trackno,
                    'type' => $updateddata->type,
                    'dept' => Departments::where('id',$updateddata->dept)->value('departmentname'),
                    'tardate' => $updateddata->tardate ? date("d/m/Y", strtotime($updateddata->tardate)) : '',
                );
                $emails = getNonDeletedUsersEmail(Complaintreviewer::pluck('email')->toArray());
                if(count($emails)>0){
                    Mail::to($emails)->send(new ComplaintUpdated($emaildata));
                }
                $com_app_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_up_emails->id)->get();
                if (count($com_app_userdatas) > 0) {
                    $adminaddedusers = $com_app_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new ComplaintUpdated($emaildata));
                    }
                }
            } else {
                Session::flash('msg', 'Mail Sending Blocked for Complaint Updated');
            }
            //inserting values to activity table superadmin
            $activity = "Complaint sent for Reviewer Response where Complaint Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/complaintmgmt')->with('model', $models);
        }
    }
    //ADD investigation report
    public function submitInvestigationForm(SubmitInvestigationFormRequest $request)
    {
        $data = $request->validated();
        $complaint = Complaint::where('id', $data['complaint_id'])->firstOrFail();
        $id = $data['complaint_id'];
        $processstage = 'RC Updated- Correction Awaiting';

        if ($complaint->correction_proposed) {
            $processstage = 'Actions Proposed- Review Awaiting';
        }
        Complaint::where('id', $id)->update([
            'status' => $processstage,
            'occurrence' => $data['occurrence'],
            'rootcause_updated_by_investigation_team' => 1,
            'system_related_problems' => $data['system_related_problems'],
            'recent_changes' => $data['recent_changes'],
            'follow_procedures' => $data['follow_procedures'],
            'contributing_factors' => $data['contributing_factors'] ? implode(',', $data['contributing_factors']) : null,
            'external_factors' => $data['external_factors'] ? implode(',', $data['external_factors']) : null,
            'issue_foreseeable' => $data['issue_foreseeable'],
            'prevent_reason' => $data['prevent_reason'],
            'contributing_factors_details' => $data['factors_details'],
            'external_factors_details' => $data['influence_details'],
        ]);
        $cr_ids = isset($data['rid']) ? $data['rid'] : null;
        $rootcause = $data['rootcause'];
        $correctiveaction = $data['correctiveaction'];
        $department = $data['department'];
        $responsibility = $data['responsibility'];
        $corr_tardate = $data['target_date'];
        if($responsibility && count($responsibility)){
            $complaint_rca_details_ids = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('id')->toArray();
            foreach($complaint_rca_details_ids as $crid){
                if(!in_array($crid,$cr_ids)){
                    ComplaintRootCauseCorrectiveActionDetail::where('id',$crid)->delete();
                }
            }
            $count = 0;
            for($i=0;$i<count($complaint_rca_details_ids);$i++){
                ComplaintRootCauseCorrectiveActionDetail::where('id',$complaint_rca_details_ids[$i])->update([
                    'root_cause' => $rootcause[$i],
                    'corrective_action' => $correctiveaction[$i],
                    'department' => $department[$i],
                    'responsibility' => $responsibility[$i],
                    'target_date' => $corr_tardate[$i],
                    'complaint_id' => $id,
                    'updated_at' => now(),
                ]);
                $count++;
            }
            $total_records_to_add = count($responsibility);
            for($i=$count;$i<$total_records_to_add;$i++) {
                ComplaintRootCauseCorrectiveActionDetail::insert([
                    'complaint_id' => $id,
                    'root_cause' => $rootcause[$i],
                    'corrective_action' => $correctiveaction[$i],
                    'department' => $department[$i],
                    'responsibility' => $responsibility[$i],
                    'target_date' => $corr_tardate[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $destination_path = public_path('storage/complaint');
        if ($request->hasFile('issue_file')) {
            $file_array = $request->file('issue_file');
            $insertData = [];
            foreach ($file_array as $file) {
                $new_file_name = $file->getClientOriginalName();
                $file->move($destination_path, $new_file_name);
                $insertData[] = [
                    'complaint_id' => $id,
                    'issue_file' => $new_file_name,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            ComplaintIssueFile::insert($insertData);
        }
        if ($request->hasFile('action_file')) {
            $file_array = $request->file('action_file');
            $insertData = [];
            foreach ($file_array as $file) {
                $new_file_name = $file->getClientOriginalName();
                $file->move($destination_path, $new_file_name);
                $insertData[] = [
                    'complaint_id' => $id,
                    'action_file' => $new_file_name,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            ComplaintActionFile::insert($insertData);
        }
        return [
            'data' => null,
            'error' => null,
        ];
    }
    //edit propose action
    public function edit()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $docs = DB::table('complaintfiles')->where('id',$id)->get();
            $files = [];
            foreach($docs as $doc){
                if($doc && $doc->files!="NULL"){
                    $files[] = $doc;
                }
            }
            // fetching complaint
            $data = Complaint::with('complaintMeta', 'previousComplaintRef')->where('id', $id)->first();
            // adding complaint meta datas as column of complaint
            $result = collect($data->toArray());
            $meta = collect($result['complaint_meta'])->pluck('meta_value', 'meta_key');
            // final data with meta table value
            $data = (object)$result->except('complaint_meta')->merge($meta)->toArray();
            //
            $employeename = EmployeeDetails::where('emp_number',$data->employee)->where('is_deleted',false)->value('emp_name');
            $employees = EmployeeDetails::where('is_deleted',false)->get();
            $customer = Customer::where('id',$data->customer)->where('is_deleted', false)->value('customer');
            $user = User::where('is_email_verified',true)->orderBy('name')->get();
            //$departments = Departments::all();
            $departments = Departments::where('is_active', true)->get();
            $type = Type::all();
            $res = $data->responsibility;
            $product_details = ComplaintProductDetail::where('complaint_id',$data->id)->get();
            $product = Product::whereIn('id',explode(',',$data->product))->pluck('product')->toArray();
            $product = count($product)>0 ? $product : [$data->product];
            $plants = ComplaintPlant::where('is_deleted',false)->get();
            if ($data->plant) {
                $complaint_cat = ComplaintCategory::where('plant_id',$data->plant)->where('is_deleted',false)->get();
            } else {
                $complaint_cat = ComplaintCategory::where('is_deleted',false)->get();
            }
            $resname = DB::table('users')->where('email', $res)->value('name');
            $issue_reviewers = Complaintreviewer::pluck('email')->toArray();
            $countries = Country::all();
            $states = State::all();
            $team_members = DB::table('complaint_investigators')->where('complaint_id',$data->id)->pluck('user_id')->toArray();
            $models = DB::table('modenadisable')->First();
            $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
            $issuedocs = ComplaintIssueFile::where('complaint_id',$id)->where('is_deleted',false)->get();
            $actiondocs = ComplaintActionFile::where('complaint_id',$id)->where('is_deleted',false)->get();
            return view('user.complaint.editproposeaction')->with([
                'resname' => $resname,
                'departments' => $departments,
                'type' => $type,
                'model' => $models,
                'data' => $data,
                'user' => $user,
                'id' => $id,
                'files' => $files,
                'issue_reviewers' => $issue_reviewers,
                'employees' => $employees,
                'employeename' => $employeename,
                'customer' => $customer,
                'product' => $product,
                'plants' => $plants,
                'complaint_cat' => $complaint_cat,
                'countries' => $countries,
                'states' => $states,
                'product_details' => $product_details,
                'team_members' => $team_members,
                'rootcorrectives'=> $rootcorrectives,
                'issuedocs' => $issuedocs,
                'actiondocs' => $actiondocs,
            ]);

        }
    }
    public function editproposeaction(Request $req)
    {
        $v = Validator::make($req->all(), [
            'correction' => 'required|string',
            'date' => 'required|date',
        ],[
            'correction.required' => 'The Correction field is required.',
            'date.required' => 'The Correction Target date field is required.',
            'date.date' => 'The Correction Target date is not a valid date.',
        ]);
        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($v)->withInput()->with('model', $models);
        } else {
            $id = $req->input('compid');
            $crdate = $req->input('crdate');
            $modeofcomplaint = $req->input('modeofcomplaint');
            $customerphone = $req->input('customerphone');
            $customeraddress = $req->input('customeraddress');
            $type = $req->input('type');
            $date = $req->input('date');
            $correction = $req->input('correction');
            $financial_loss = $req->input('financial_loss');
            $potential_cost_savings = $req->input('potential_cost_savings');
            $complaint = Complaint::findOrFail($id);
            $data = [
                'type' => $type,
                'tardate' => $date,
                'crdate' => $crdate,
                'updated_at' => now(),
                'mode_of_complaint' => $modeofcomplaint,
                'customer_phone' => $customerphone,
                'customer_address' => $customeraddress,
                'correction' => $correction,
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
                'correction_proposed' => true,
            ];
            $plant = $req->input('plant');
            if($plant!=null){
                $data['plant'] = $plant;
            }
            $employee = $req->input('employee');
            if($employee!=null){
                $data['employee'] = $employee;
            }
            $division = $req->input('division');
            if($division!=null){
                $data['division_id'] = $division;
            }
            $area = $req->input('area');
            if($division!=null){
                $data['area_id'] = $area;
            }
            //---------------------------------------Record------------------
            //-------------------------Handle file--------------------------------
            $removedFileIds = json_decode($req->input('removedFileIds'),true);
            $removedIssueFileIds = json_decode($req->input('removedIssueFileIds'),true);
            $removedActionFileIds = json_decode($req->input('removedActionFileIds'),true);
            if(count($removedFileIds)>0){
               DB::table('complaintfiles')->whereIn('sno',$removedFileIds)->delete();
            }
            if(count($removedIssueFileIds)>0){
                ComplaintIssueFile::whereIn('id',$removedIssueFileIds)->update([
                    'updated_at' => now(),
                    'is_deleted' => true
                ]);
            }
            if(count($removedActionFileIds)>0){
                ComplaintActionFile::whereIn('id',$removedActionFileIds)->update([
                    'updated_at' => now(),
                    'is_deleted' => true
                ]);
            }
            $destination_path = public_path('storage/complaint');
            if ($req->hasFile('file')) {
                $file_array = $req->file('file');
                $insertData = [];
                foreach ($file_array as $file) {
                    $new_file_name = $file->getClientOriginalName();
                    $file->move($destination_path, $new_file_name);
                    $insertData[] = [
                        'complaint_id' => $id,
                        'action_file' => $new_file_name,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                ComplaintActionFile::insert($insertData);
            }
            //-------------------------End Handle file--------------------------------

            /** ---------------------For Product Details ------------------------------ */
            $product_desc_id = $req->input('product_desc_id',[]);
            $srr = $req->input('salereturnreq');
            $rr = $req->input('reworkreq');
            $sizeofitem = $req->input('sizeofitem');
            if(is_array($req->input('complaintcategory'))){
                if(count($product_desc_id)>0){
                    $category = $req->input('complaintcategory');
                    $collection = collect($category);
                    $reindexedCollection = $collection->values();
                    $category = $reindexedCollection->toArray();
                    for($i=0;$i<count($product_desc_id);$i++){
                        ComplaintProductDetail::where('id',$product_desc_id[$i])->update([
                            'complaint_category' => $category && isset($category[$i+1]) ? implode(',',$category[$i+1]) : null,
                            'sale_return_request' => $srr[$i] ?? null,
                            'rework_request' => $rr[$i] ?? null,
                            'size_of_item' => $sizeofitem[$i] ?? null,
                            'updated_at' => now(),
                        ]);
                    }
                }
            }else{
                if(count($product_desc_id)>0){
                    for($i=0;$i<count($product_desc_id);$i++){
                        ComplaintProductDetail::where('id',$product_desc_id[$i])->update([
                            'sale_return_request' => $srr[$i] ?? null,
                            'rework_request' => $rr[$i] ?? null,
                            'size_of_item' => $sizeofitem[$i] ?? null,
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            $cr_ids = $req->input('rid');
            $rootcause = $req->input('rootcause');
            $correctiveaction = $req->input('correctiveaction');
            $department = $req->input('department');
            $responsibility = $req->input('responsibility');
            $corr_tardate = $req->input('corr_tardate');
            $complaint = Complaint::findOrFail($id);
            $record_before_update = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->get()->toArray();
            $record_root = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('root_cause')->toArray();
            $record_ca = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('corrective_action')->toArray();
            $record_res = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('responsibility')->toArray();
            $record_date = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('target_date')->toArray();
            $record_dept = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('department')->toArray();
            $is_record_updated = null;
            $flag = !($rootcause[0]==null || $correctiveaction[0]==null || $department[0]==null || $responsibility[0]==null || $corr_tardate[0]==null);
            $flag2 = $record_ca || $record_root || $record_res || $record_date || $record_dept;
            if($flag==true && $flag2==true){
                $is_record_updated = !($record_root==$rootcause &&  $record_ca == $correctiveaction && $record_res==$responsibility && $record_date==$corr_tardate && $record_dept == $department);
            }elseif($flag==false && $flag2==false){
                $is_record_updated = false;
            }elseif($flag==true && $flag2==false){
                $is_record_updated = true;
            }
            if($is_record_updated){
                $complaint_rca_details_ids = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('id')->toArray();
                foreach($complaint_rca_details_ids as $crid){
                    if(!in_array($crid,$cr_ids)){
                        ComplaintRootCauseCorrectiveActionDetail::where('id',$crid)->delete();
                    }
                }
                $count = 0;
                for($i=0;$i<count($complaint_rca_details_ids);$i++){
                    ComplaintRootCauseCorrectiveActionDetail::where('id',$complaint_rca_details_ids[$i])->update([
                        'root_cause' => $rootcause[$i],
                        'corrective_action' => $correctiveaction[$i],
                        'department' => $department[$i],
                        'responsibility' => $responsibility[$i],
                        'target_date' => $corr_tardate[$i],
                        'complaint_id' => $id,
                        'updated_at' => now(),
                    ]);
                    $count++;
                }
                $total_records_to_add = count($responsibility);
                for($i=$count;$i<$total_records_to_add;$i++) {
                    ComplaintRootCauseCorrectiveActionDetail::insert([
                        'complaint_id' => $id,
                        'root_cause' => $rootcause[$i],
                        'corrective_action' => $correctiveaction[$i],
                        'department' => $department[$i],
                        'responsibility' => $responsibility[$i],
                        'target_date' => $corr_tardate[$i],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            $record_after_update = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->get()->toArray();
            if($flag==false){
                $data['status'] = 'Correction Proposed- RC Awaiting';
            }else{
                $data['status'] = 'Actions Proposed- Review Awaiting';
            }
            /** ---------------------END Product Details ------------------------------ */
            DB::table('complaint')->where('id', $id)->update($data);
            //inserting values to activity table superadmin
            $activity = "Complaint edited for Reviewer Response where Complaint Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/complaintmgmt')->with('model', $models);
        }
    }
    public function reviewaction()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $docs = DB::table('complaintfiles')->where('id',$id)->get();
            $files = [];
            foreach($docs as $doc){
                if($doc && $doc->files!="NULL"){
                    $files[] = $doc;
                }
            }
            // fetching complaint
            $data = Complaint::with('complaintMeta', 'previousComplaintRef')->where('id', $id)->first();
            // adding complaint meta datas as column of complaint
            $result = collect($data->toArray());
            $meta = collect($result['complaint_meta'])->pluck('meta_value', 'meta_key');
            // final data with meta table value
            $data = (object)$result->except('complaint_meta')->merge($meta)->toArray();
            //
            $employeename = EmployeeDetails::where('emp_number',$data->employee)->where('is_deleted',false)->value('emp_name');
            $employees = EmployeeDetails::where('is_deleted',false)->get();
            $customer = Customer::where('id',$data->customer)->where('is_deleted', false)->value('customer');
            $user = User::where('is_email_verified',true)->orderBy('name')->get();
            //$departments = Departments::all();
            $departments = Departments::where('is_active', true)->get();
            $type = Type::all();
            $res = $data->responsibility;
            $product_details = ComplaintProductDetail::where('complaint_id',$data->id)->get();
            $product = Product::whereIn('id',explode(',',$data->product))->pluck('product')->toArray();
            $product = count($product)>0 ? $product : [$data->product];
            $plants = ComplaintPlant::where('is_deleted',false)->get();
            if ($data->plant) {
                $complaint_cat = ComplaintCategory::where('plant_id',$data->plant)->where('is_deleted',false)->get();
            } else {
                $complaint_cat = ComplaintCategory::where('is_deleted',false)->get();
            }
            $resname = DB::table('users')->where('email', $res)->value('name');
            $issue_reviewers = Complaintreviewer::pluck('email')->toArray();
            $countries = Country::all();
            $states = State::all();
            $team_members = DB::table('complaint_investigators')->where('complaint_id',$data->id)->pluck('user_id')->toArray();
            $models = DB::table('modenadisable')->First();
            $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
            $issuedocs = ComplaintIssueFile::where('complaint_id',$id)->where('is_deleted',false)->get();
            $actiondocs = ComplaintActionFile::where('complaint_id',$id)->where('is_deleted',false)->get();
             return view('user.complaint.reviewaction')->with([
                'resname' => $resname,
                'departments' => $departments,
                'type' => $type,
                'model' => $models,
                'data' => $data,
                'user' => $user,
                'id' => $id,
                'files' => $files,
                'issue_reviewers' => $issue_reviewers,
                'employees' => $employees,
                'employeename' => $employeename,
                'customer' => $customer,
                'product' => $product,
                'plants' => $plants,
                'complaint_cat' => $complaint_cat,
                'countries' => $countries,
                'states' => $states,
                'product_details' => $product_details,
                'team_members' => $team_members,
                'rootcorrectives'=> $rootcorrectives,
                'issuedocs' => $issuedocs,
                'actiondocs' => $actiondocs,
            ]);

        }
    }
    //review ok
    public function reviewok(Request $req)
    {

        $v = Validator::make($req->all(), [
            'correction' => 'required|string',
            'date' => 'required|date',
        ],[
            'correction.required' => 'The Correction field is required.',
            'date.required' => 'The Correction Target date field is required.',
            'date.date' => 'The Correction Target date is not a valid date.',
        ]);
        if ($v->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($v)->withInput()->with('model', $models);
        } else {
            $id = $req->input('compid');
            $crdate = $req->input('crdate');
            $modeofcomplaint = $req->input('modeofcomplaint');
            $customerphone = $req->input('customerphone');
            $customeraddress = $req->input('customeraddress');
            $type = $req->input('type');
            $date = $req->input('date');
            $correction = $req->input('correction');
            $financial_loss = $req->input('financial_loss');
            $potential_cost_savings = $req->input('potential_cost_savings');
            $reviewed_at = date('d-m-Y h:i:s A', time());
            $reviewedby = User::where('email',Auth::user()->email)->value('id');
            $data = [
                'type' => $type,
                'tardate' => $date,
                'crdate' => $crdate,
                'updated_at' => now(),
                'mode_of_complaint' => $modeofcomplaint,
                'customer_phone' => $customerphone,
                'customer_address' => $customeraddress,
                'correction' => $correction,
                'dept'=>$req->input('department_corr'),
                'division_id'=>$req->input('division'),
                'area_id'=>$req->input('area'),
                'financial_loss' => $financial_loss,
                'potential_cost_savings' => $potential_cost_savings,
                'correction_proposed' => true,
                'status' => 'Action Reviewed, OK',
                'active' => 'yes',
                'reviewed_by' => $reviewedby,
                'reviewedat' => $reviewed_at,
            ];
            Complaint::where('id',$id)->update($data);
            $plant = $req->input('plant');
            if($plant!=null){
                $data['plant'] = $plant;
            }
            $employee = $req->input('employee');
            if($employee!=null){
                $data['employee'] = $employee;
            }
            $division = $req->input('division');
            if($division!=null){
                $data['division_id'] = $division;
            }
            $area = $req->input('area');
            if($division!=null){
                $data['area_id'] = $area;
            }
            //---------------------------------------Record------------------
            //-------------------------Handle file--------------------------------
            $removedFileIds = json_decode($req->input('removedFileIds'),true);
            $removedIssueFileIds = json_decode($req->input('removedIssueFileIds'),true);
            $removedActionFileIds = json_decode($req->input('removedActionFileIds'),true);
            if(count($removedFileIds)>0){
               DB::table('complaintfiles')->whereIn('sno',$removedFileIds)->delete();
            }
            if(count($removedIssueFileIds)>0){
                ComplaintIssueFile::whereIn('id',$removedIssueFileIds)->update([
                    'updated_at' => now(),
                    'is_deleted' => true
                ]);
            }
            if(count($removedActionFileIds)>0){
                ComplaintActionFile::whereIn('id',$removedActionFileIds)->update([
                    'updated_at' => now(),
                    'is_deleted' => true
                ]);
            }
            //-------------------------End Handle file--------------------------------

            /** ---------------------For Product Details ------------------------------ */
            $product_desc_id = $req->input('product_desc_id',[]);
            $srr = $req->input('salereturnreq');
            $rr = $req->input('reworkreq');
            $sizeofitem = $req->input('sizeofitem');
            if(is_array($req->input('complaintcategory'))){
                if(count($product_desc_id)>0){
                    $category = $req->input('complaintcategory');
                    $collection = collect($category);
                    $reindexedCollection = $collection->values();
                    $category = $reindexedCollection->toArray();
                    for($i=0;$i<count($product_desc_id);$i++){
                        ComplaintProductDetail::where('id',$product_desc_id[$i])->update([
                            'complaint_category' => $category[$i+1] ? implode(',',$category[$i+1]) : null,
                            'sale_return_request' => $srr[$i] ?? null,
                            'rework_request' => $rr[$i] ?? null,
                            'size_of_item' => $sizeofitem[$i] ?? null,
                            'updated_at' => now(),
                        ]);
                    }
                }
            }else{
                if(count($product_desc_id)>0){
                    for($i=0;$i<count($product_desc_id);$i++){
                        ComplaintProductDetail::where('id',$product_desc_id[$i])->update([
                            'sale_return_request' => $srr[$i] ?? null,
                            'rework_request' => $rr[$i] ?? null,
                            'size_of_item' => $sizeofitem[$i] ?? null,
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            $cr_ids = $req->input('rid');
            $rootcause = $req->input('rootcause');
            $correctiveaction = $req->input('correctiveaction');
            $department = $req->input('department');
            $responsibility = $req->input('responsibility');
            $corr_tardate = $req->input('corr_tardate');
            $record_root = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('root_cause')->toArray();
            $record_ca = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('corrective_action')->toArray();
            $record_res = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('responsibility')->toArray();
            $record_date = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('target_date')->toArray();
            $record_dept = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('department')->toArray();
            $is_record_updated = null;
            $flag = !($rootcause[0]==null || $correctiveaction[0]==null || $department[0]==null || $responsibility[0]==null || $corr_tardate[0]==null);
            $flag2 = $record_ca || $record_root || $record_res || $record_date || $record_dept;
            if($flag==true && $flag2==true){
                $is_record_updated = !($record_root==$rootcause &&  $record_ca == $correctiveaction && $record_res==$responsibility && $record_date==$corr_tardate && $record_dept == $department);
            }elseif($flag==false && $flag2==false){
                $is_record_updated = false;
            }elseif($flag==true && $flag2==false){
                $is_record_updated = true;
            }
            if($is_record_updated){
                $complaint_rca_details_ids = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$id)->pluck('id')->toArray();
                foreach($complaint_rca_details_ids as $crid){
                    if(!in_array($crid,$cr_ids)){
                        ComplaintRootCauseCorrectiveActionDetail::where('id',$crid)->delete();
                    }
                }
                $count = 0;
                for($i=0;$i<count($complaint_rca_details_ids);$i++){
                    ComplaintRootCauseCorrectiveActionDetail::where('id',$complaint_rca_details_ids[$i])->update([
                        'root_cause' => $rootcause[$i],
                        'corrective_action' => $correctiveaction[$i],
                        'department' => $department[$i],
                        'responsibility' => $responsibility[$i],
                        'target_date' => $corr_tardate[$i],
                        'complaint_id' => $id,
                        'updated_at' => now(),
                    ]);
                    $count++;
                }
                $total_records_to_add = count($responsibility);
                for($i=$count;$i<$total_records_to_add;$i++) {
                    ComplaintRootCauseCorrectiveActionDetail::insert([
                        'complaint_id' => $id,
                        'root_cause' => $rootcause[$i],
                        'corrective_action' => $correctiveaction[$i],
                        'department' => $department[$i],
                        'responsibility' => $responsibility[$i],
                        'target_date' => $corr_tardate[$i],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            /** ---------------------END Product Details ------------------------------ */
            DB::table('complaint')->where('id', $id)->update($data);
            //Sending mail
            $complaint = DB::table('complaint')->where('id', $id)->first();
            $addedby = User::where('employeeid',$complaint->addedby)->value('name');
            $addedbyemail = User::where('employeeid',$complaint->addedby)->value('email');
            $departmentname = Departments::where('id',$complaint->dept)->value('departmentname');
            $responsibility = User::where('id',$complaint->responsibility)->value('email');
            $notification = $complaint->sendnotif;
            $noti = $notification ? getNonDeletedUsersEmail(explode(',', $notification)) : null;
            $com_rev_emails = EmailNotification::where('module', 'complaintmanagement')
                        ->where('item', 'Complaint Review Status Found OK')
                        ->where('is_blocked', false)
                        ->first();
            if($com_rev_emails) {
                $emaildata = array(
                    'email_content' => $com_rev_emails->email_content,
                    'item' => $com_rev_emails->item,
                    'trackno' => $complaint->trackno,
                    'addedby' => $addedby,
                    'type' => $complaint->type,
                    'dept' => $departmentname,
                    'tardate' => $complaint->tardate ? date("d/m/Y", strtotime($complaint->tardate)) : '',
                );
                if ($addedbyemail) {
                    Mail::to($addedbyemail)->send(new ComplaintReviewStatusFoundOK($emaildata));
                }
                if ($responsibility) {
                    Mail::to($responsibility)->send(new ComplaintReviewStatusFoundOK($emaildata));
                }
                if ($noti) {
                    Mail::to($noti)->send(new ComplaintReviewStatusFoundOK($emaildata));
                }
                $com_rev_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_rev_emails->id)->get();
                if (count($com_rev_userdatas) > 0) {
                    $adminaddedusers = $com_rev_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new ComplaintReviewStatusFoundOK($emaildata));
                    }
                }
            } else {
                Session::flash('err', 'Mail Sending Blocked for Complaint Review Status Found OK');
            }
            //inserting values to activity table superadmin
            $activity = "Action Reviwed and Process Stage is Updated to 'Action Reviwed, OK' where Complaint Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            DB::table('viewactivity')->insert($datas);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/complaintmgmt')->with('model', $models);
        }
    }

    public function notok()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $models = DB::table('modenadisable')->First();
            return view('user.complaint.reviewnotok')->withId($id)->with('model', $models);
        }
    }

    public function reviewnotok(Request $req)
    {
        $remarks = $req->input('remarks');
        $id = $req->input('compid');

        $reviewed_at = date('d-m-Y h:i:s A', time());
        $reviewedby = User::where('email',Auth::user()->email)->value('id');
        $data = array(
            'status' => 'Action Reviewed, Not OK',
            'remarks' => $remarks, 'reviewed_by' => $reviewedby, 'reviewedat' => $reviewed_at,
        );
        DB::table('complaint')
            ->where('id', $id)
            ->update($data);

        //inserting values to activity table
        $activity = "Action Reviwed and Process Stage is Updated to 'Action Reviwed,Not OK' where Complaint Id is " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
        DB::table('viewactivity')->insert($datas);
        //end of superadmin code
        // Session::flash('msg',"Suggested Changes Successfully");
        $models = DB::table('modenadisable')->First();
        return redirect('/user/complaintmgmt')->with('model', $models);
    }
    //update progress
    public function updateProgress(int $id){
        $files = DB::table('complaintfiles')->where('id',$id)->pluck('files')->toArray();
        // fetching complaint
        $data = Complaint::with('complaintMeta', 'previousComplaintRef')->where('id', $id)->first();
        // adding complaint meta datas as column of complaint
        $result = collect($data->toArray());
        $meta = collect($result['complaint_meta'])->pluck('meta_value', 'meta_key');
        // final data with meta table value
        $data = (object)$result->except('complaint_meta')->merge($meta)->toArray();
        //
        $employees = EmployeeDetails::where('emp_number',$data->employee)->where('is_deleted',false)->value('emp_name');
        $customer = Customer::where('id',$data->customer)->where('is_deleted', false)->value('customer');
        $user = User::where('is_email_verified',true)->orderBy('name')->get();
        $departments = Departments::all();
        $type = Type::all();
        $res = $data->responsibility;
        $product_details = ComplaintProductDetail::where('complaint_id',$data->id)->get();
        $product = Product::whereIn('id',explode(',',$data->product))->pluck('product')->toArray();
        $product = count($product)>0 ? $product : [$data->product];
        $plants = ComplaintPlant::where('is_deleted',false)->get();
        if ($data->plant) {
            $complaint_cat = ComplaintCategory::where('plant_id',$data->plant)->where('is_deleted',false)->get();
        } else {
            $complaint_cat = ComplaintCategory::where('is_deleted',false)->get();
        }
        $resname = DB::table('users')->where('email', $res)->value('name');
        $issue_reviewers = Complaintreviewer::pluck('email')->toArray();
        $countries = Country::all();
        $states = State::all();
        $team_members = DB::table('complaint_investigators')->where('complaint_id',$data->id)->pluck('user_id')->toArray();
        $models = DB::table('modenadisable')->First();
        $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
        $issuedocs = ComplaintIssueFile::where('complaint_id',$id)->where('is_deleted',false)->get();
        $actiondocs = ComplaintActionFile::where('complaint_id',$id)->where('is_deleted',false)->get();
        return view('user.complaint.updateprogress')->with([
            'resname' => $resname,
            'departments' => $departments,
            'type' => $type,
            'model' => $models,
            'data' => $data,
            'user' => $user,
            'id' => $id,
            'files' => $files,
            'issue_reviewers' => $issue_reviewers,
            'employees' => $employees,
            'customer' => $customer,
            'product' => $product,
            'plants' => $plants,
            'complaint_cat' => $complaint_cat,
            'countries' => $countries,
            'states' => $states,
            'product_details' => $product_details,
            'team_members' => $team_members,
            'rootcorrectives'=> $rootcorrectives,
            'issuedocs' => $issuedocs,
            'actiondocs' => $actiondocs,
        ]);
    }
    public function postUpdatePercentage(Request $request)
    {
        $rules = [
            'correction_status' => 'integer|required|in:0,25,50,75,100',
            'rootcause' => 'array|required',
            'rootcause.*' => 'string|required',
            'corrective_action' => 'array|required',
            'corrective_action.*' => 'string|required',
            'status' => 'array|required',
            'status.*' => 'integer|required|in:0,25,50,75,100',
            'correction' => 'string|required'
        ];
        $messages = [
            'correction_status.integer' => 'Correction status must be an integer.',
            'correction_status.required' => 'Correction status is required.',
            'correction_status.in' => 'Correction status must be one of the following values: 0, 25, 50, 75, or 100.',

            'rootcause.array' => 'Root cause must be an array.',
            'rootcause.required' => 'Root cause is required.',
            'rootcause.*.string' => 'Each root cause must be a string.',
            'rootcause.*.required' => 'Each root cause is required.',

            'corrective_action.array' => 'Corrective action must be an array.',
            'corrective_action.required' => 'Corrective action is required.',
            'corrective_action.*.string' => 'Each corrective action must be a string.',
            'corrective_action.*.required' => 'Each corrective action is required.',

            'status.array' => 'Status must be an array.',
            'status.required' => 'Status is required.',
            'status.*.integer' => 'Each status must be an integer.',
            'status.*.required' => 'Each status is required.',
            'status.*.in' => 'Each status must be one of the following values: 0, 25, 50, 75, or 100.',
        ];
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $id = $request->input("compid");
            $crdate = $request->input('crdate');
            $correction_status = $request->input('correction_status');
            $rid = $request->input("rid");
            $rootcause = $request->input('rootcause');
            $correctiveaction = $request->input('corrective_action');
            $status = $request->input('status');
            $percent_array = array_merge(array($correction_status), $status);
            $average_percent = array_sum($percent_array) / count($percent_array);
            Complaint::where('id', $id)->update([
                'correction_status' => $correction_status ? $correction_status : 0,
                'average_percent' => round($average_percent),
                'correction' => $request->input('correction'),
                'crdate' => $crdate
            ]);
            if ($average_percent === 100) {
                Complaint::where('id', $id)->update([
                    'status' => 'Actions Completed',
                    'actionstatus' => 'Completed-100%'
                ]);
            }
            for($i = 0; $i < count($rid); $i++){
                ComplaintRootCauseCorrectiveActionDetail::where('id',$rid[$i])->update([
                    'root_cause' => $rootcause[$i],
                    'corrective_action' => $correctiveaction[$i],
                    'percent_status' => $status[$i],
                    'updated_at' => now(),
                ]);
            }
            $destination_path = public_path('storage/complaint');
            if ($request->hasFile('file')) {
                $file_array = $request->file('file');
                $insertData = [];
                foreach ($file_array as $file) {
                    $new_file_name = $file->getClientOriginalName();
                    $file->move($destination_path, $new_file_name);
                    $insertData[] = [
                        'complaint_id' => $id,
                        'action_file' => $new_file_name,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                ComplaintActionFile::insert($insertData);
            }
            //inserting values to activity table superadmin
            $activity = "Complaint issue with Id as " . $id . " 's percentage is updated.";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);

            Session::flash('msg', "Complaint percentage updated successfully");

            $models = DB::table('modenadisable')->first();
            return redirect('/user/complaint/my')->with('model', $models);
        }
    }
    //closure review
    public function closure()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $docs = DB::table('complaintfiles')->where('id',$id)->get();
            $files = [];
            foreach($docs as $doc){
                if($doc && $doc->files!="NULL"){
                    $files[] = $doc;
                }
            }
            // fetching complaint
            $data = Complaint::with('complaintMeta', 'previousComplaintRef')->where('id', $id)->first();
            // adding complaint meta datas as column of complaint
            $result = collect($data->toArray());
            $meta = collect($result['complaint_meta'])->pluck('meta_value', 'meta_key');
            // final data with meta table value
            $data = (object)$result->except('complaint_meta')->merge($meta)->toArray();
            //
            $sourceOfIssueField = ComplaintFormConfigurationHelper::getSourceOfIssue($data->complaint_template_id ?? 0);

            $employees = EmployeeDetails::where('emp_number',$data->employee)->where('is_deleted',false)->value('emp_name');
            $customer = Customer::where('id',$data->customer)->where('is_deleted', false)->value('customer');
            $user = User::where('is_email_verified',true)->orderBy('name')->get();
            //$departments = Departments::all();
            $departments = Departments::where('is_active', true)->get();
            $type = Type::all();
            $res = $data->responsibility;
            $product_details = ComplaintProductDetail::where('complaint_id',$data->id)->get();
            $product = Product::whereIn('id',explode(',',$data->product))->pluck('product')->toArray();
            $product = count($product)>0 ? $product : [$data->product];
            $plants = ComplaintPlant::where('is_deleted',false)->get();
            $complaint_cat = ComplaintCategory::where('plant_id',$data->plant)->where('is_deleted',false)->get();
            $resname = DB::table('users')->where('email', $res)->value('name');
            $issue_reviewers = Complaintreviewer::pluck('email')->toArray();
            $countries = Country::all();
            $states = State::all();
            $team_members = DB::table('complaint_investigators')->where('complaint_id',$data->id)->pluck('user_id')->toArray();
            $models = DB::table('modenadisable')->First();
            $rootcorrectives = AuditRootCauseCorrectiveActionDetail::all();
            $issuedocs = ComplaintIssueFile::where('complaint_id',$id)->where('is_deleted',false)->get();
            $actiondocs = ComplaintActionFile::where('complaint_id',$id)->where('is_deleted',false)->get();
            $sourceOfIssues = SourceOfIssue::get();

            return view('user.complaint.closurereview')->with([
                'resname' => $resname,
                'departments' => $departments,
                'type' => $type,
                'model' => $models,
                'data' => $data,
                'user' => $user,
                'id' => $id,
                'files' => $files,
                'issue_reviewers' => $issue_reviewers,
                'employees' => $employees,
                'customer' => $customer,
                'product' => $product,
                'plants' => $plants,
                'complaint_cat' => $complaint_cat,
                'countries' => $countries,
                'states' => $states,
                'product_details' => $product_details,
                'team_members' => $team_members,
                'rootcorrectives'=> $rootcorrectives,
                'issuedocs' => $issuedocs,
                'actiondocs' => $actiondocs,
                'sourceOfIssueField' => $sourceOfIssueField,
                'sourceOfIssues' => $sourceOfIssues
            ]);
        }
    }
    //need more action
    public function changes(int $id, string $status='', string $rid='')
    {
        $models = DB::table('modenadisable')->First();
        return view('user.complaint.suggestchanges')->withId($id)->with('model', $models)->with('status',$status)->with('rid',$rid);
    }

    public function suggestchanges(Request $req)
    {
        $v = Validator::make($req->all(), []);
        if ($v->fails()) {
            $models = DB::table('modenadisable')->first();
            return redirect()->back()->withErrors($v->errors())->withInput()->with('model', $models);
        } else {
            $id = $req->input('id');
            $percent_status = $req->input('percent_status') ? explode(',', $req->input('percent_status')) : null;
            $average_percent = array_sum($percent_status)/count($percent_status); //complaint
            $rid = $req->input('rid') ? explode(',',$req->input('rid')) : null;
            $correction_status = array_shift($percent_status); //complaint
            $closedby = User::where('email',Auth::user()->email)->value('id');
            $remarks = $req->input('remarks');
            $data = array(
                'actionstatus' => 'Partially Completed-50%',
                'correction_status' => $correction_status,
                'average_percent' => round($average_percent,0),
                'needaction' => $remarks,
                'updated_at' => now(),
                'closed_by' => $closedby, 'closed_at' => now(),
            );
            for($i = 0; $i < count($rid); $i++){
                ComplaintRootCauseCorrectiveActionDetail::where('id',$rid[$i])->update([
                    'percent_status' => $percent_status[$i],
                    'updated_at' => now(),
                ]);
            }
            $com_res_emails = null;
            if ($req->action_modified == "yes") {
                $data['status'] = 'Need More Action';
                Complaint::where('id', $id)->update($data);
                $com_res_emails = EmailNotification::where('module', 'complaintmanagement')
                    ->where('item', 'Complaint Review Status Found Not OK')
                    ->where('is_blocked', false)
                    ->first();
                //inserting values to activity table
                $activity = "Complaint Process Stage is Updated to 'Need More Action' and Implementation Status is updated to 'Partially Completed-50%'  where Complaint Id is " . $id;
                //end of superadmin code
            } elseif ($req->action_modified == "no") {
                $data['status'] = 'Action Reviewed, OK';
                Complaint::where('id', $id)->update($data);
                $com_res_emails = EmailNotification::where('module', 'complaintmanagement')
                    ->where('item', 'Complaint Review Status Found OK')
                    ->where('is_blocked', false)
                    ->first();
                //inserting values to activity table
                $activity = "Complaint Process Stage is Updated to 'Action Reviewed, OK' and Implementation Status is updated to 'Partially Completed-50%'  where Complaint Id is " . $id;
            }
            //mail

            $complaint = Complaint::where('id',$id)->first();
            $departmentname = Departments::where('id', $complaint->dept)->value('departmentname') ?? $complaint->dept;
            $addedby = User::where('employeeid',$complaint->addedby)->value('name');
            $addedbyemail = User::where('employeeid',$complaint->addedby)->where('is_email_verified',true)->value('email');
            if($com_res_emails) {
                $emaildata = array(
                    'email_content' => $com_res_emails->email_content,
                    'item' => $com_res_emails->item,
                    'trackno' => $complaint->trackno,
                    'addedby' => $addedby,
                    'type' => $complaint->type,
                    'dept' => $departmentname,
                    'tardate' => $complaint->tardate ? date("d/m/Y", strtotime($complaint->tardate)) : '',
                );
                if($addedbyemail) {
                    if($req->action_modified == "yes"){
                        Mail::to($addedbyemail)->send(new ComplaintReviewStatusFoundNotOK($emaildata));
                    }elseif($req->action_modified == "no"){
                        Mail::to($addedbyemail)->send(new ComplaintReviewStatusFoundOK($emaildata));
                    }
                }
                $responsibilityMail = getNonDeletedUsersEmail($complaint->responsibility);
                if(count($responsibilityMail)>0) {
                    if($req->action_modified == "yes"){
                        Mail::to($responsibilityMail)->send(new ComplaintReviewStatusFoundNotOK($emaildata));
                    }elseif($req->action_modified == "no"){
                        Mail::to($responsibilityMail)->send(new ComplaintReviewStatusFoundOK($emaildata));
                    }
                }
                //Sending Mail(Notification) To Other HOD
                $noti = $complaint->sendnotif ? getNonDeletedUsersEmail(explode(',', $complaint->sendnotif)) : [];
                if(count($noti)>0){
                    if($req->action_modified == "yes"){
                        Mail::to($noti)->send(new ComplaintReviewStatusFoundNotOK($emaildata));
                    }elseif($req->action_modified == "no"){
                        Mail::to($noti)->send(new ComplaintReviewStatusFoundOK($emaildata));
                    }
                }
                //sending mail for participants and management participants
                $com_res_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_res_emails->id)->get();
                if (count($com_res_userdatas) > 0) {
                    $adminaddedusers = $com_res_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        if($req->action_modified == "yes"){
                            Mail::to($userdatas)->send(new ComplaintReviewStatusFoundNotOK($emaildata));
                        }elseif($req->action_modified == "no"){
                            Mail::to($userdatas)->send(new ComplaintReviewStatusFoundOK($emaildata));
                        }
                    }
                }
            } else {
                if($req->action_modified == "yes"){
                    Session::flash('err', 'Mail Sending Blocked for Complaint Review Status Found Not OK.');
                }elseif($req->action_modified == "no"){
                    Session::flash('err', 'Mail Sending Blocked for Complaint Review Status Found OK.');
                }
            }
            //inserting values to activity table
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            $models = DB::table('modenadisable')->first();
            return redirect('/user/complaintmgmt')->with('model', $models);

        }
    }

    public function closed(Request $request)
    {
        $rules = [
            'rootcause' => 'array|required',
            'rootcause.*' => 'string|required',
            'correctiveaction' => 'array|required',
            'correctiveaction.*' => 'string|required',
            'department' => 'array|required',
            'department.*' => 'string|required',
            'responsibility' => 'array|required',
            'responsibility.*' => 'string|required',
        ];
        $messages = [
            'rootcause.array' => 'Root cause must be an array.',
            'rootcause.required' => 'Root cause is required.',
            'rootcause.*.string' => 'Each root cause must be a string.',
            'rootcause.*.required' => 'Each root cause is required.',

            'corrective_action.array' => 'Corrective action must be an array.',
            'corrective_action.required' => 'Corrective action is required.',
            'corrective_action.*.string' => 'Each corrective action must be a string.',
            'corrective_action.*.required' => 'Each corrective action is required.',

            'department.array' => 'Department must be an array.',
            'department.required' => 'Department is required.',
            'department.*.string' => 'Each department must be a string.',
            'department.*.required' => 'Each department is required.',

            'responsibility.array' => 'Responsibilty must be an array.',
            'responsibility.required' => 'Responsibilty is required.',
            'responsibility.*.string' => 'Each responsibilty must be a string.',
            'responsibility.*.required' => 'Each responsibilty is required.',
        ];
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'model' => $models
            ]);
        } else {
            $id = $request->input("id");
            $rid = $request->input("rid");
            $crdate = $request->input('crdate');
            $rootcause = $request->input('rootcause');
            $correctiveaction = $request->input('correctiveaction');
            $department = $request->input('department');
            $responsibility = $request->input('responsibility');
            $authenticity = $request->input('authenticity');
            $source_of_issue = $request->input('source_of_issue');

            $closedby = User::where('email',Auth::user()->email)->value('id');
            $complaint = Complaint::find($id);
            $complaintTemplateSectionFields = json_decode($complaint->complaint_template_section_field_rules, true);
            $source_of_issue_field = json_decode($request->input('source_of_issue_field'), true);
            if ($source_of_issue_field) {
                $complaintTemplateSectionFields['source_of_issue'] = $source_of_issue_field;
            }

            Complaint::where('id', $id)->update([
                'status' => 'Closed', 'closed_by' => $closedby, 'closed_at' => now(),
                'actionstatus' => 'Completed-100%', 'correction_status' => 100, 'updated_at' => now(),
                'average_percent' => 100,
                'issue_authenticity' => $authenticity,
                'complaint_template_section_field_rules' => json_encode($complaintTemplateSectionFields)
            ]);
            if (isset($source_of_issue)) {
                ComplaintMeta::create([
                    'complaint_id' => $id,
                    'meta_key' => 'source_of_issue',
                    'meta_value' => $source_of_issue,
                ]);
            }
            for($i = 0; $i < count($rid); $i++){
                ComplaintRootCauseCorrectiveActionDetail::where('id',$rid[$i])->update([
                    'root_cause' => $rootcause[$i],
                    'corrective_action' => $correctiveaction[$i],
                    'department' => $department[$i],
                    'responsibility' => $responsibility[$i],
                    'percent_status' => 100,
                    'updated_at' => now(),
                ]);
            }
            $removedFileIds = json_decode($request->input('removedFileIds'),true);
            $removedIssueFileIds = json_decode($request->input('removedIssueFileIds'),true);
            $removedActionFileIds = json_decode($request->input('removedActionFileIds'),true);
            if(count($removedFileIds)>0){
               DB::table('complaintfiles')->whereIn('sno',$removedFileIds)->delete();
            }
            if(count($removedIssueFileIds)>0){
                ComplaintIssueFile::whereIn('id',$removedIssueFileIds)->update([
                    'updated_at' => now(),
                    'is_deleted' => true
                ]);
            }
            if(count($removedActionFileIds)>0){
                ComplaintActionFile::whereIn('id',$removedActionFileIds)->update([
                    'updated_at' => now(),
                    'is_deleted' => true
                ]);
            }
            //mail
            $com_res_emails = EmailNotification::where('module', 'complaintmanagement')
                ->where('item', 'Complaint Issue Closed')
                ->where('is_blocked', false)
                ->first();
            $complaint = Complaint::where('id',$id)->first();
            $departmentname = Departments::where('id', $complaint->dept)->value('departmentname') ?? $complaint->dept;
            $addedby = User::where('employeeid',$complaint->addedby)->value('name');
            $addedbyemail = User::where('employeeid',$complaint->addedby)->where('is_email_verified',true)->value('email');
            if($com_res_emails) {
                $emaildata = array(
                    'email_content' => $com_res_emails->email_content,
                    'item' => $com_res_emails->item,
                    'trackno' => $complaint->trackno,
                    'addedby' => $addedby,
                    'type' => $complaint->type,
                    'dept' => $departmentname,
                    'tardate' => $complaint->tardate ? date("d/m/Y", strtotime($complaint->tardate)) : '',
                );
                if($addedbyemail) {
                    Mail::to($addedbyemail)->send(new ComplaintIssueClosed($emaildata));
                }
                $responsibilityMail = getNonDeletedUsersEmail($complaint->responsibility);
                if(count($responsibilityMail)>0) {
                    Mail::to($responsibilityMail)->send(new ComplaintIssueClosed($emaildata));
                }
                //Sending Mail(Notification) To Other HOD
                $noti = $complaint->sendnotif ? getNonDeletedUsersEmail(explode(',', $complaint->sendnotif)) : [];
                if(count($noti)>0){
                    Mail::to($noti)->send(new ComplaintIssueClosed($emaildata));
                }
                //sending mail for participants and management participants
                $com_res_userdatas = EmailNotificationsUserDetails::where('notification_id', $com_res_emails->id)->get();
                if (count($com_res_userdatas) > 0) {
                    $adminaddedusers = $com_res_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas) > 0) {
                        Mail::to($userdatas)->send(new ComplaintIssueClosed($emaildata));
                    }
                }
            } else {
                Session::flash('err', 'Mail Sending Blocked for Complaint Issue Closed.');
            }
            //inserting values to activity table superadmin
            $activity = "Complaint is Closed with Process Stage Updated as Closed where Complaint Id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user');
            ViewActivity::insert($datas);
            //end of superadmin code
            $models = DB::table('modenadisable')->First();
            // return redirect('/user/complaintmgmt')->with('model', $models);
            return response()->json([
                'success' => true,
                'message' => 'The complaint has been closed successfully.',
                'model' => $models
            ]);
        }
    }
    public function search(Request $req)
    {

        $models = DB::table('modenadisable')->First();

        //--------------------Register------------
        $data = DB::table('complaint')
            ->where('status', '!=', 'Closed')
            ->paginate(1000);
        $data = DB::table('complaint')
            ->where(function ($query) use ($req) {
                $query

                    ->where('addedby', '=', $req->input('keyword'))
                    ->orWhere('title', '=', $req->input('keyword'))
                    ->orWhere('complaint', '=', $req->input('keyword'))
                    ->orWhere('status', '=', $req->input('keyword'))
                    ->orWhere('actionstatus', '=', $req->input('keyword'))
                    ->orWhere('customer', '=', $req->input('keyword'))
                    ->orWhere('product', '=', $req->input('keyword'))
                    ->orWhere('trackno', '=', $req->input('keyword'))
                    ->orWhere('type', '=', $req->input('keyword'))
                    ->orWhere('action', '=', $req->input('keyword'))
                    ->orWhere('dept', '=', $req->input('keyword'))
                    ->orWhere('responsibility', '=', $req->input('keyword'))
                    ->orWhere('rootcause', '=', $req->input('keyword'))
                    ->orWhere('correctiveaction', '=', $req->input('keyword'))
                    ->orWhere('correction', '=', $req->input('keyword'))
                    ->orWhere('tardate', '=', $req->input('keyword'))
                    ->orWhere('reason', '=', $req->input('keyword'))
                    ->orWhere('remarks', '=', $req->input('keyword'))
                    ->orWhere('needaction', '=', $req->input('keyword'));
            })
            ->where('status', '!=', 'Closed')
            ->paginate(1000)
            ->setpath('');

        $data->appends(array(
            'department' => $req->input('keyword'),
        ));
        $departments = DB::table('departments')->get();
        $mr = DB::table('representatives')->pluck('email');
        $email = DB::table('complaintreviewers')->pluck('email');
        $user = User::all();

        //-------------------Customer list----------

        $data1 = DB::table('customer')
            ->where('is_deleted', false)
            ->paginate(1000);
        //--------------------my responsible complaints------------
        $res = Auth::user()->email;
        $data2 = DB::table('complaint')
            ->where('responsibility', '=', $res)
            ->wherein('status', array(
                'Approved',
                'Actions Proposed',
                'Action Reviewed, Ok',
                'Action Reviewed, Not OK',
                'rejected',
                'Need More Action',
                'Actions Completed',
            ))
            ->paginate(100);

        //------------------Closed--------------
        $docs = DB::table('complaintfiles')->get();
        $data3 = DB::table('complaint')
            ->where('status', 'Closed')
            ->orderBy('id', 'desc')
            ->paginate(1000);

        return view('user.complaint.register')->with('model', $models)->with('user', $user)->with('docs', $docs)->with('data1', $data1)->with('data2', $data2)->with('data3', $data3)->withDepartments($departments)->withMr($mr)->with('data', $data)->with('email', $email);
    }
    public function delete(Request $request)
    {
            $id = $request->input('id');
            $ids = explode(',',$id);
            //inserting data to activities table
            $activity = "Customers name deleted.";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);
            Customer::whereIn('id', $ids)->update(['is_deleted' => true]);
            $models = DB::table('modenadisable')->First();
            // return redirect('/user/complaint/list')->with('model', $models);
    }

    public function deletecomplaint()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];

            //inserting data to activities table
            $name = DB::table('complaint')->where('id', $id)->pluck('title');
            $activity = "A cmplaint is removed with name " . $name;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'admin', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);

            Complaint::find($id)->delete();

            $models = DB::table('modenadisable')->First();
            //return redirect('/user/complaint/list')->with('model', $models);
        }
    }

    public function editreq(Request $req)
    {
        $idString = $req->input('id');
        session(['edit_customer_ids' => $idString]);
        return response()->json(['redirect_url' => route('editcustomer_session')]);
    }
    public function editreqFromSession()
    {
        $idString = session('edit_customer_ids');
        $idArray = explode(',', $idString);

        // Fetch customer data
        $data = Customer::whereIn('id', $idArray)->OrderByDesc('id')->get();
        $countries = Country::all();
        $p = Product::all();
        $models = DB::table('modenadisable')->first();

        return view('user/complaint/editrequest')
            ->with('data', $data)
            ->with('p', $p)
            ->with('countries',$countries)
            ->with('id', $idArray)
            ->with('model', $models);
    }

    public function editreqcomplaint()
    {

        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $id = explode(',', $_GET['val']);
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = DB::table('complaint')->where('id', $d)->first();
                }
                $n = $n + 1;
            }
        }
        $product = DB::table('products')->get();
        $departments = Departments::all();
        $users = User::where('approved', 1)->get();
        $models = DB::table('modenadisable')->First();

        return view('user.complaint.editrequestcomplaint')->withData($data)->withProduct($product)->with('id', $id)->with('users', $users)->with('departments', $departments)->with('model', $models);
    }

    public function editrequest(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'customer' => 'required|array',
            'customer.*' => 'required|string',
            'product' => 'required|array',
            'product.*' => 'required|array',
            'country' => 'required|array',
            'country' => 'required',
            'pin' => 'required|array',
            'pin.*' => 'required|string',
            'customer_survey' => new CommaSeparatedEmailsRule,
        ]);
        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();
        } else {

            $customerArray = $req->input('customer');
            $locationArray = $req->input('location');
            $productArray = $req->input('product');
            $emailArray = $req->input('email');
            $phoneArray = $req->input('phone');
            $countryArray = $req->input('country');
            $other_countryArray = $req->input('other_country');
            $other_stateArray = $req->input('other_state');
            $stateArray = $req->input('state');
            $pinArray = $req->input('pin');
            $customer_surveyArray = $req->input('survey_emails');
            $idsArray = $req->input('sno');
            for($index=0;$index<count($idsArray);$index++){

                $countryArray[$index] = $countryArray[$index]=='0' ? Country::where('country_name',$other_countryArray[$index])->value('id') : $countryArray[$index];

                if($countryArray[$index]==null){
                    $countryArray[$index]='0';
                }
                $other_countryArray[$index] = $countryArray[$index] =='0' ? $other_countryArray[$index] : null;
                $stateid = State::where('state_name',$other_stateArray[$index])->where('country_id',$countryArray[$index])->value('id');
                if($stateid){
                    $stateArray[$index] = $stateid;
                    $other_stateArray[$index] = null;
                }

                $product = count($productArray[$index])>0 ? implode(',',$productArray[$index]) : null;
                $duplicateCheck = null;
                if($countryArray[$index]=='0'){
                    $duplicateCheck = DB::table('customer')
                        ->where('customer', $customerArray[$index])
                        ->where('pin', $pinArray[$index])
                        ->where('other_country', $other_countryArray[$index])
                        ->where('id', '!=', $idsArray[$index])
                        ->exists();
                }else{
                    $duplicateCheck = DB::table('customer')
                        ->where('customer', $customerArray[$index])
                        ->where('pin', $pinArray[$index])
                        ->where('country_id', $countryArray[$index])
                        ->where('id', '!=', $idsArray[$index])
                        ->exists();
                }
                if ($duplicateCheck) {
                    continue;
                }
                $data = [
                    'customer' => $customerArray[$index],
                    'location' => $locationArray[$index],
                    'product' => $product,
                    'email' => $emailArray[$index],
                    'phone' => $phoneArray[$index],
                    'country_id' => $countryArray[$index],
                    'state_id' => $stateArray[$index],
                    'pin' => $pinArray[$index],
                    'other_country' => $other_countryArray[$index],
                    'other_state' => $other_stateArray[$index],
                    'customer_survey_emails' => $customer_surveyArray[$index],
                    'updated_at' => now(),
                ];
                DB::table('customer')->where('id', $idsArray[$index])->update($data);
            }
            //insert data to activity table superadmin code
            $activity = "Customer  is  Edited and Updated with new Details";
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now_time = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
            ViewActivity::insert($datas);
            $models = DB::table('modenadisable')->first();
            $data = Customer::whereIn('id', $idsArray)->OrderByDesc('id')->get();
            $countries = Country::all();
            $p = Product::all();
            $models = DB::table('modenadisable')->first();
            Session::flash('msg','Customer details have been updated successfully.');
            return redirect('user/complaint/list')
                ->with('model', $models);
            }

    }

     public function editrequestcomplaint(Request $req)
    {
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = DB::table('complaint')->where('id', $d)->first();
            }
            $n = $n + 1;
        }
        $n = 0;

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->id == $s && $n < $m) {

                    $data = array(

                        'title' => $req->title[$n],
                        'complaint' => $req->complaint[$n],
                        'type' => $req->type[$n],
                        'action' => $req->action[$n],
                        'dept' => $req->department[$n],
                        'responsibility' => $req->responsibility[$n]

                    );
                    DB::table('complaint')
                        ->where('id', $s)
                        ->update($data);
                    //insert data to activity table superadmin code
                    $activity = "Complaint is Edited and Updated with new Details  where  ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $now_time = Carbon::now()->format('Y-m-d H:i:s');
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now_time, 'updated_at' => $now_time);
                    ViewActivity::insert($datas);
                    //end of superadmin code

                }
            }

            $n = $n + 1;
        }

        $models = DB::table('modenadisable')->First();
        return redirect('/user/complaintmgmt')->with('model', $models);
    }
    public function analysis()
    {
        $today = date('Y-m-d');
        $processes = DB::select("select departmentname from departments order by departmentname ASC");
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->departmentname);
        }

        $deptopen = DB::select("select s.departmentname,COUNT(r.status)AS number
                           FROM  complaint AS r
                           right JOIN departments AS s
                           ON r.department = s.departmentname
                           and r.approvement='yes'and r.status!='Closed' and r.status!='Under Review'
                           group by s.departmentname
                           order by s.departmentname ASC
                          ");
        $deptdue = DB::select("select s.departmentname,COUNT(r.duedate)AS number
                            FROM  complaint AS r
                            right JOIN departments AS s
                            ON r.department = s.departmentname
                            and r.approvement='yes' and r.status='open' and r.duedate <= '$today'
                            group by s.departmentname
                            order by s.departmentname ASC
                            ");
        $deptclose = DB::select("select s.departmentname,COUNT(r.status)AS number
                           FROM  complaint AS r
                           right JOIN departments AS s
                           ON r.department = s.departmentname
                           and r.approvement='yes'and r.status='closed'
                           group by s.departmentname
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
        $awaitings = DB::select("select s.departmentname,COUNT(r.status)AS number
                          FROM  complaint AS r
                          right JOIN departments AS s
                          ON r.department = s.departmentname
                          and r.status='Under Review'
                          group by s.departmentname
                          order by s.departmentname ASC
                         ");
        $awaiting = [];
        foreach ($awaitings as $key) {
            array_push($awaiting, $key->number);
        }

        $names = DB::table('types')->pluck('name');
        $open = DB::table('complaint')->where('status', 'open')->get();
        $opencount = count($open);

        $years = DB::select("select year from years order by id ASC");
        $year = [];
        foreach ($years as $key) {
            array_push($year, $key->year);
        }
        $trends = DB::select("select s.year,COUNT(r.year)AS number
                        FROM  complaint AS r
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
        $dhigh = DB::select("select s.departmentname,COUNT(r.type)AS number
                           FROM  complaint AS r
                           right JOIN departments AS s
                           ON r.department= s.departmentname
                           and  r.type='High' and r.approvement='yes'and r.status='open'
                           group by s.departmentname
                           order by s.departmentname ASC
                          ");
        $dmoderate = DB::select("select s.departmentname,COUNT(r.type)AS number
                            FROM complaint AS r
                            right JOIN departments AS s
                            ON r.department= s.departmentname
                            and r.type='Medium' and r.approvement='yes'and r.status='open'
                            group by s.departmentname
                            order by s.departmentname ASC
                            ");
        $dlow = DB::select("select s.departmentname,COUNT(r.type)AS number
                            FROM  complaint AS r
                            right JOIN departments AS s
                            ON r.department= s.departmentname
                            and  r.type='Low' and r.approvement='yes'and r.status='open'
                            group by s.departmentname
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
        $totalhigh = DB::select("select s.departmentname,COUNT(r.type)AS number
                           FROM  complaint AS r
                           right JOIN departments AS s
                           ON r.department= s.departmentname
                           and  r.type='High'
                           group by s.departmentname
                           order by s.departmentname ASC
                          ");
        $totalmoderate = DB::select("select s.departmentname,COUNT(r.type)AS number
                            FROM complaint AS r
                            right JOIN departments AS s
                            ON r.department= s.departmentname
                            and r.type='Medium'
                            group by s.departmentname
                            order by s.departmentname ASC
                            ");
        $totallow = DB::select("select s.departmentname,COUNT(r.type)AS number
                            FROM  complaint AS r
                            right JOIN departments AS s
                            ON r.department= s.departmentname
                            and  r.type='Low'
                            group by s.departmentname
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
        $ndue = DB::table('complaint')->where('status', 'open')->where('duedate', '<=', $today)->get();
        $nmdue = count($ndue);
        $awaitingactions = DB::table('complaint')->wherein('status', array('Approved', 'Under Review'))->get();
        $actions = count($awaitingactions);
        $actionsproposed = DB::table('complaint')->where('status', 'Actions Proposed')->get();
        $proposed = count($actionsproposed);
        $closure = DB::table('complaint')->where('status', 'open')->where('impl_status', 'Completed-100%')->get();
        $awaitingclosure = count($closure);
        $depcodes = DB::select("select code from departments order by departmentname ASC");
        $depcode = [];
        foreach ($depcodes as $key) {
            array_push($depcode, $key->code);
        }
        $zerop = DB::select("select s.departmentname,COUNT(r.impl_status)AS number
                           FROM  complaint AS r
                           right JOIN departments AS s
                           ON r.department= s.departmentname
                           and  r.impl_status='Not Started-0%' and r.approvement='yes'
                           group by s.departmentname
                           order by s.departmentname ASC
                          ");
        $twop = DB::select("select s.departmentname,COUNT(r.impl_status)AS number
                            FROM complaint AS r
                            right JOIN departments AS s
                            ON r.department= s.departmentname
                            and  r.impl_status='Progress-25%' and r.approvement='yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                            ");
        $fivep = DB::select("select s.departmentname,COUNT(r.impl_status)AS number
                            FROM  complaint AS r
                            right JOIN departments AS s
                            ON r.department= s.departmentname
                            and  r.impl_status='Partially Completed-50%' and r.approvement='yes'
                            group by s.departmentname
                            order by s.departmentname ASC
                        ");
        $hundredp = DB::select("select s.departmentname,COUNT(r.impl_status)AS number
                            FROM  complaint AS r
                            right JOIN departments AS s
                            ON r.department= s.departmentname
                            and  r.impl_status='Completed-100%' and r.approvement='yes'
                            group by s.departmentname
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
        $h = DB::table('complaint')->where('impl_status', 'Completed-100%')->get();
        $hundredcount = count($h);
        $f = DB::table('complaint')->where('impl_status', 'Partially Completed-50%')->get();
        $fivecount = count($f);
        $z = DB::table('complaint')->where('impl_status', 'Not Started-0%')->where('approvement', 'yes')->get();
        $zerocount = count($z);
        $close = DB::table('complaint')->where('status', 'closed')->get();
        $closecount = count($close);

        $closebefore = DB::select("select COUNT(r.duedate)AS number
                            FROM  complaint AS r
                            right JOIN departments AS s
                            ON r.department = s.departmentname
                            and r.approvement='yes' and r.status='closed' and r.closed <= r.duedate
                          ");

        $closeafter = DB::select("select COUNT(r.duedate)AS number
                            FROM  complaint AS r
                            right JOIN departments AS s
                            ON r.department = s.departmentname
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
        $deptbefore = DB::select("select s.departmentname,COUNT(r.duedate)AS number
                            FROM  complaint AS r
                            right JOIN departments AS s
                            ON r.department = s.departmentname
                            and r.approvement='yes' and r.status='closed' and r.closed <= r.duedate
                            group by s.departmentname
                            order by s.departmentname ASC
                            ");
        $deptafter = DB::select("select s.departmentname,COUNT(r.status)AS number
                           FROM  complaint AS r
                           right JOIN departments AS s
                           ON r.department = s.departmentname
                           and r.approvement='yes'and r.status='closed'and closed > r.duedate
                           group by s.departmentname
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
        $data = DB::table('complaint')->where('updated_at', '!=', 'NULL')->orderBy('updated_at', 'ASC')->get();
        $nmdepts = DB::table('complaint')->pluck('department')->unique();
        $models = DB::table('modenadisable')->First();
        return view('user.complaint.analysis')->with('model', $models)
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
            ->with('after', json_encode($after, JSON_NUMERIC_CHECK));
    }
    /**
     * Details and Export details page
     *
     */
    public function detail($id)
    {
        $models = DB::table('modenadisable')->first();

        // adding complaint meta datas as column of complaint
        $complaint = Complaint::with('complaintMeta', 'previousComplaintRef')->withTrashed()->findOrFail($id);
        $meta = $complaint->complaintMeta->pluck('meta_value', 'meta_key');
        foreach ($meta as $key => $value) {
            $complaint->$key = $value;
        }

        //fetching complaint category information if complaint_category_id is set
        $projectComplaint = [];
        if (isset($complaint->complaint_category_id)) {
            $projectComplaint = ComplaintCategory::find($complaint->complaint_category_id);
        }
        // fetching vendor information if vendor_id is set
        $vendor = [];
        if (isset($complaint->vendor_id)) {
            $vendor = Vendor::find($complaint->vendor_id);
        }

        $plant = ComplaintPlant::where('id',$complaint->plant)->first();
        $department = Departments::where('id',$complaint->dept)->first();
        $division = Division::where('id',$complaint->division_id)->first();
        $area = Area::where('id',$complaint->area_id)->first();
        $postedBy = User::withTrashed()->where('employeeid',$complaint->addedby)->first();
        $reviewedBy = null;
        $approvedBy = User::withTrashed()->where('employeeid',$complaint->approved_by)->first();
        if($complaint->status=="Approved" || $complaint->status="Closed"){
            if(User::where('employeeid',$complaint->reviewed_by)->first()){
                $reviewedBy = User::withTrashed()->where('employeeid',$complaint->reviewed_by)->first();
            }else{
                $reviewedBy = User::withTrashed()->where('id',$complaint->reviewed_by)->first();
            }
        }else{
            $reviewedBy = User::withTrashed()->where('id',$complaint->reviewed_by)->first();
        }
        $countryName = null;
        $stateName = null;
        $customer = Customer::where('id',$complaint->customer)->first();
        if($customer){
            $phonenumber = $complaint->customer_phone ? $complaint->customer_phone : $customer->phone;
            $countryPhoneCode = Country::where('id',$customer->country_id)->value('phoneCode')??"00";
            $customeraddress= $complaint->customer_address ? $complaint->customer_address : $customer->location;
            if($customer->country_id!="0"){
                $countryName = Country::where('id',$customer->country_id)->value('country_name');
            }else{
                $countryName = $customer->other_country;
            }
            if($customer->state_id!="0"){
                $stateName = State::where('id',$customer->state_id)->value('state_name');
            }else{
                $stateName = $customer->other_state;
            }
        }else{
            $customer = $complaint->customer;
            $phonenumber = $complaint->customer_phone;
            $countryPhoneCode = "00";
            $customeraddress = $complaint->customer_address;
        }

        $products = explode(',',$complaint->product);
        $productNames = Product::whereIn('id',$products)->pluck('product')->toArray();
        $records = ComplaintProductDetail::where('complaint_id',$complaint->id)->get();
        $notificationEmail = explode(',', $complaint->sendnotif);
        $notificationUsers = User::whereIn('email', $notificationEmail)->get();
        $frequency = $complaint->eremainder ? explode(',', $complaint->eremainder) : [];
        $docs = DB::table('complaintfiles')->where('id',$id)->get();
        $complaintdocs = [];
        foreach($docs as $doc){
            if($doc && $doc->files!="NULL"){
                $complaintdocs[] = $doc;
            }
        }
        $issuedocs = ComplaintIssueFile::where('complaint_id',$id)->where('is_deleted',false)->get();
        $actiondocs = ComplaintActionFile::where('complaint_id',$id)->where('is_deleted',false)->get();
        $investigationTeams = ComplaintInvestigator::where('complaint_id', $complaint->id)->get();
        $issue_reviewers = Complaintreviewer::pluck('email')->toArray();

        return view('user.complaint.detail',compact(
            'plant','department', 'projectComplaint', 'division','countryName', 'vendor', 'stateName','productNames','issue_reviewers',
            'area','postedBy','reviewedBy','customer','countryPhoneCode','phonenumber','customeraddress',
            'records','frequency','notificationUsers','investigationTeams','issuedocs','approvedBy','complaintdocs','actiondocs'
        ))->with('model',$models)->with('complaint',$complaint);
    }

    public function exportPdf($id)
    {
        $models = DB::table('modenadisable')->first();

        // adding complaint meta datas as column of complaint
        $complaint = Complaint::with('complaintMeta', 'previousComplaintRef')->findOrFail($id);
        $meta = $complaint->complaintMeta->pluck('meta_value', 'meta_key');
        foreach ($meta as $key => $value) {
            $complaint->$key = $value;
        }
        //fetching complaint category information if complaint_category_id is set
        $projectComplaint = [];
        if (isset($complaint->complaint_category_id)) {
            $projectComplaint = ComplaintCategory::find($complaint->complaint_category_id);
        }
        // fetching vendor information if vendor_id is set
        $vendor = [];
        if (isset($complaint->vendor_id)) {
            $vendor = Vendor::find($complaint->vendor_id);
        }

        $plant = ComplaintPlant::where('id',$complaint->plant)->first();
        $department = Departments::where('id',$complaint->dept)->first();
        $division = Division::where('id',$complaint->division_id)->first();
        $area = Area::where('id',$complaint->area_id)->first();
        $postedBy = User::where('employeeid',$complaint->addedby)->first();
        $reviewedBy = null;
        $approvedBy = User::where('employeeid',$complaint->approved_by)->first();
        if($complaint->status=="Approved" || $complaint->status="Closed"){
            if(User::where('employeeid',$complaint->reviewed_by)->first()){
                $reviewedBy = User::where('employeeid',$complaint->reviewed_by)->first();
            }else{
                $reviewedBy = User::where('id',$complaint->reviewed_by)->first();
            }
        }else{
            $reviewedBy = User::where('id',$complaint->reviewed_by)->first();
        }
        $approvedat = null;
        if($complaint->approved_at==null){
            $approvedat = $complaint->reviewedat;
        }else{
            $approvedat = $complaint->approved_at;
        }
        if(!$reviewedBy){
            $complaint->reviewedat = null;
        }
        $countryName = null;
        $stateName = null;
        $customer = Customer::where('id',$complaint->customer)->first();
        if($customer){
            $phonenumber = $complaint->customer_phone ? $complaint->customer_phone : $customer->phone;
            $countryPhoneCode = Country::where('id',$customer->country_id)->value('phoneCode')??"00";
            $customeraddress= $complaint->customer_address ? $complaint->customer_address : $customer->location;
            if($customer->country_id!="0"){
                $countryName = Country::where('id',$customer->country_id)->value('country_name');
            }else{
                $countryName = $customer->other_country;
            }
            if($customer->state_id!="0"){
                $stateName = State::where('id',$customer->state_id)->value('state_name');
            }else{
                $stateName = $customer->other_state;
            }
        }else{
            $customer = $complaint->customer;
            $phonenumber = $complaint->customer_phone;
            $countryPhoneCode = "00";
            $customeraddress = $complaint->customer_address;
        }
        $products = explode(',',$complaint->product);
        $productNames = Product::whereIn('id',$products)->pluck('product')->toArray();
        $records2 = ComplaintProductDetail::where('complaint_id',$complaint->id)->get();
        $notificationEmail = explode(',', $complaint->sendnotif);
        $notificationUsers = User::whereIn('email', $notificationEmail)->get();
        $frequency = $complaint->eremainder ? explode(',', $complaint->eremainder) : [];
        $investigationTeams = ComplaintInvestigator::where('complaint_id', $complaint->id)->get();
        $closedBy = null;
        $closedat = null;
        if($complaint->status=="Closed" || $complaint->status=="Need More Action"){
            if(User::where('employeeid',$complaint->reviewed_by)->first()){
                $closedBy = User::where('employeeid',$complaint->reviewed_by)->first();
                $closedat = $complaint->reviewedat ? date('d/m/Y', strtotime($complaint->reviewedat)):null;
            }else{
                $closedBy = User::where('id',$complaint->closedby)->first();
                $closedat = $complaint->closed_at ? date('d/m/Y', strtotime($complaint->closed_at)):null;
            }
        }else{
            $closedBy = User::where('id',$complaint->closedby)->first();
            $closedat = $complaint->closed_at ? date('d/m/Y', strtotime($complaint->closed_at)):null;
        }
        $resp = User::where('email', $complaint->responsibility)->first();
        $dept = Departments::where('id',$complaint->dept)->value('departmentname');
        $employee = EmployeeDetails::where('emp_number',$complaint->employee)->value('emp_name');
        $records = DB::table('complaint_root_cause_corrective_action_details')->where('complaint_id',$complaint->id)->get()->toArray();
        $docs = DB::table('complaintfiles')->where('id',$id)->pluck('files')->toArray();
        $complaintdocs = [];
        foreach($docs as $doc){
            if($doc && $doc!="NULL"){
                $complaintdocs[] = $doc;
            }
        }
        $issuedocs = ComplaintIssueFile::where('complaint_id',$id)->where('is_deleted',false)->get();
        $actiondocs = ComplaintActionFile::where('complaint_id',$id)->where('is_deleted',false)->get();
        $clientDetails = ClientDetail::first();
        $pdf =  \PDF::loadView('user.complaint.detail_pdf',compact(
            'clientDetails', 'complaint','plant', 'projectComplaint', 'vendor', 'department','division','countryName','stateName','productNames','resp','dept','records','employee',
            'area','postedBy','reviewedBy','customer','countryPhoneCode','phonenumber','customeraddress','closedBy','closedat',
            'records2','frequency','notificationUsers','investigationTeams','issuedocs','approvedBy','approvedat','complaintdocs','actiondocs'
        ));
        return $pdf->download('detail_' . $complaint->trackno . '.pdf');
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
            $docs = DB::table('complaintfiles')->where('id',$id)->get();
            $issuedocs = ComplaintIssueFile::where('complaint_id',$id)->where('is_deleted',false)->get();
            $actiondocs = ComplaintActionFile::where('complaint_id',$id)->where('is_deleted',false)->get();
            $models = DB::table('modenadisable')->first();

            // adding complaint meta datas as column of complaint
            $value = Complaint::with('complaintMeta', 'previousComplaintRef')->withTrashed()->findOrFail($id);

            $meta = $value->complaintMeta->pluck('meta_value', 'meta_key');
            foreach ($meta as $key => $val) {
                $value->$key = $val;
            }

            // fetching complaint category information
            $projectComplaint = null;
            if (isset($value->complaint_category_id)) {
                $projectComplaint = ComplaintCategory::find($value->complaint_category_id);
            }

            // fetching vendor information
            $vendor = null;
            if (isset($value->vendor_id)) {
                $vendor = Vendor::find($value->vendor_id);
            }

            $issue_reviewers = Complaintreviewer::pluck('email')->toArray();
            $responseData['view'] = \view('user.complaint._viewdetails', compact(
                'value', 'projectComplaint', 'vendor', 'models','docs','issuedocs','actiondocs','issue_reviewers'
            ))->render();
            $responseData['id'] = $id;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
    /**
     * Display the detail popup when click "View" button
     *
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function changeResp($id): JsonResponse
    {
        $responseData = [];

        try {
            $models = DB::table('modenadisable')->first();
            $data = Complaint::findOrFail($id);
            //$departments = Departments::all();
            $departments = Departments::where('is_active', true)
                                        ->orWhere('id', $data->dept)
                                        ->get();
            $user = User::where('is_email_verified',true)->get();
            $rootcausedetails = ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->get();
            $responseData['view'] = \view('user.complaint.changeresponsibility', compact(
                'data', 'models','departments','user','rootcausedetails'
            ))->render();
            $responseData['id'] = $id;
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
            $responseData['error'] = $exception->getMessage();
        }

        return response()->json($responseData);
    }
    public function changeRespRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'complaint_id' => 'required|integer|exists:complaint,id',
            'mainresp' => 'required|string|exists:users,email',
            'maindept' => 'required|string|exists:departments,id',
        ],[
            'mainresp.required' => 'The Correction Responsibility field is required.',
            'maindept.required' => 'The Correction Department field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all(),
                'data' => null
            ], 422);
        }
        try{
            $complaint_id = $request->input('complaint_id');
            $mainresp = $request->input('mainresp');
            $maindept = $request->input('maindept');
            $division = $request->input('division');
            $sub_division = $request->input('subdivision');
            $rootids = $request->input('rootids');
            $rootdepts = $request->input('rootdepts');
            $rootresp = $request->input('rootresp');
            Complaint::where('id', $complaint_id)->update([
                'dept' => $maindept,
                'division_id'=>$division,
                'area_id'=>$sub_division,
                'responsibility' => $mainresp,
                'updated_at' => now()
            ]);
            if($rootids){
                for($i=0;$i<count($rootids);$i++){
                    ComplaintRootCauseCorrectiveActionDetail::where('id',$rootids[$i])->update([
                        'department' => $rootdepts[$i],
                        'responsibility' => $rootresp[$i],
                        'updated_at' => now(),
                    ]);
                }
            }
            return response()->json([
                'data' => 'Complaint updated successfully.',
                'error' => null,
            ], 200);
        }catch (\Exception $e) {
            Log::info($e);
            return response()->json([
                'error' => 'An error occurred while updating the complaint.',
                'data' => null,
            ], 500);
        }
    }
    public function deleteIssueAttachment($id,Request $request) : JsonResponse {
        $responseData = [];
        try {
            $fileid = $request->input('fileid');
            ComplaintIssueFile::where('id',$fileid)->update([
                'updated_at' => now(),
                'is_deleted' => true
            ]);
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
    public function deleteActionAttachment($id,Request $request) : JsonResponse {
        $responseData = [];
        try {
            $fileid = $request->input('fileid');
            ComplaintActionFile::where('id',$fileid)->update([
                'updated_at' => now(),
                'is_deleted' => true
            ]);
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }
        return response()->json($responseData);
    }
    public function deleteComplaintAttachment($id,Request $request) : JsonResponse {
        $responseData = [];
        try {
            $fileid = $request->input('fileid');
            DB::table('complaintfiles')->where('sno', $fileid)->delete();
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
}
