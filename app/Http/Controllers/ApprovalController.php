<?php

namespace App\Http\Controllers;
use App\RegulatoryAuthority;
use App\ComplianceLegalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Admin;
use App\ClientDetail;
use App\Departments;
use App\User;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Legalcoordinator;
use App\Mail\LegalDocumentApproved;
use App\Mail\LegalDocumentRejected;
use App\Mail\RegulatoryAuthorityApproved;
use App\Mail\RegulatoryAuthorityRejected;
use App\ModenaDisable;
use App\Near;
use App\Nearmisstypes;
use App\Neartarget;
use App\EmployeeDetails;
use App\Http\Requests\AddNearMissRequest;
use App\Mail\NewGuestNearMissAwaitingInitialReview;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Nearmiss;
use App\ViewActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(){}
    public function reviewRegulatory(Request $request,$id, $action, $token, $admin_id){
        $regulatory = RegulatoryAuthority::with('creator')->where('id',$id)->first();
        $now = now();
        $legalCoordinator = User::where('id',$admin_id)->first();
        $adminDetails = Legalcoordinator::where('email',$legalCoordinator->email)->first();
        if($adminDetails && $regulatory->token==$token){
            $currentUser = User::where('email',$adminDetails->email)->first();
            if($regulatory->status!='Approved'){
                if($action=='approve'){
                    $regulatory->update([
                        'status' => 'Approved',
                        'updated_at' => $now,
                        'approved_by' => $currentUser->id,
                        'approved_at' => $now,
                        'token' => null
                    ]);
                    ComplianceLegalDocument::where('RAC_id',$id)->update([
                        'status' => 'Approved',
                        'updated_at' => $now,
                        'approved_by' => $currentUser->id,
                        'approved_at' => $now,
                        'token' => null
                    ]);

                    // 3. Prepare Email Data
                    $docNamesArray = ComplianceLegalDocument::where('RAC_id', $id)
                        ->where('status', $action)
                        ->pluck('name')
                        ->toArray();

                    $docNamesString = count($docNamesArray) > 0 ? implode(', ', $docNamesArray) : '';
                    //Send Email to Created_by User to inform for Regulatory Authority When approved
                    $emailNotification = EmailNotification::where('module', 'compliance')
                        ->where('item', 'Regulatory Authority Approved')
                        ->where('is_blocked', false)
                        ->first();
                    if($emailNotification){
                        $emailData = [
                            'type' => 'Regulatory Authority',
                            'name' => $regulatory->name,
                            'doc_name' => $docNamesString,
                            'creator' => $regulatory->creator ? $regulatory->creator->name : '',
                            'action_by' => $currentUser->name,
                            'date' => date('d-m-Y h:i:s A',strtotime($now)),
                            'item' => $emailNotification->item,
                            'email_content' => $emailNotification->email_content,
                        ];
                        //sending mail for responsible persons
                        if($regulatory->creator && $regulatory->creator->name){
                            try {
                                Mail::to($regulatory->creator->email)->send(new RegulatoryAuthorityApproved($emailData));
                            } catch (\Exception $e) {
                                Log::error('Failed sending RegulatoryAuthorityApproved to creator: '.$e->getMessage(), ['email' => $regulatory->creator->email]);
                            }
                        }
                        //sending users added for email notifications in admin panel
                        $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                        if (count($compl_userdatas) > 0) {
                            $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas)>0) {
                                try {
                                    Mail::to($userdatas)->send(new RegulatoryAuthorityApproved($emailData));
                                } catch (\Exception $e) {
                                    Log::error('Failed sending RegulatoryAuthorityApproved to notification users: '.$e->getMessage(), ['emails' => $userdatas]);
                                }
                            }
                        }
                    }else{
                        Session::flash('error', 'Mail Sending Blocked for Regulatory Authority Approved Email');
                    }
                }elseif($action=='reject'){
                    $regulatory->update([
                        'status' => 'Rejected',
                        'updated_at' => $now,
                        'rejected_by' => $currentUser->id,
                        'rejected_at' => $now,
                        'token' => null,
                    ]);
                    ComplianceLegalDocument::where('RAC_id',$id)->update([
                        'status' => 'Rejected',
                        'updated_at' => $now,
                        'rejected_by' => $currentUser->id,
                        'rejected_at' => $now,
                        'token' => null
                    ]);
                    // 3. Prepare Email Data
                    $docNamesArray = ComplianceLegalDocument::where('RAC_id', $id)
                        ->where('status', $action)
                        ->pluck('name')
                        ->toArray();

                    $docNamesString = count($docNamesArray) > 0 ? implode(', ', $docNamesArray) : '';
                    //Send Email to Created_by User to inform for Regulatory Authority when rejected
                    $emailNotification = EmailNotification::where('module', 'compliance')
                        ->where('item', 'Regulatory Authority Rejected')
                        ->where('is_blocked', false)
                        ->first();
                    if($emailNotification){
                        $emailData = [
                            'type' => 'Regulatory Authority',
                            'name' => $regulatory->name,
                            'doc_name' => $docNamesString,
                            'creator' => $regulatory->creator ? $regulatory->creator->name : '',
                            'action_by' => $currentUser->name,
                            'date' => date('d-m-Y h:i:s A',strtotime($now)),
                            'item' => $emailNotification->item,
                            'email_content' => $emailNotification->email_content,
                        ];
                        //sending mail for responsible persons
                        if($regulatory->creator && $regulatory->creator->name){
                            try {
                                Mail::to($regulatory->creator->email)->send(new RegulatoryAuthorityRejected($emailData));
                            } catch (\Exception $e) {
                                Log::error('Failed sending RegulatoryAuthorityRejected to creator: '.$e->getMessage(), ['email' => $regulatory->creator->email]);
                            }
                        }
                        //sending users added for email notifications in admin panel
                        $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                        if (count($compl_userdatas) > 0) {
                            $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas)>0) {
                                try {
                                    Mail::to($userdatas)->send(new RegulatoryAuthorityRejected($emailData));
                                } catch (\Exception $e) {
                                    Log::error('Failed sending RegulatoryAuthorityRejected to notification users: '.$e->getMessage(), ['emails' => $userdatas]);
                                }
                            }
                        }
                    }else{
                        Session::flash('error', 'Mail Sending Blocked for Regulatory Authority Rejected Email');
                    }
                }
                Session::flash('msg',"The regulatory authority's status has been updated.");
                return Auth::check() ? redirect('/admin/compliance/index') : redirect()->guest('login');
            }
            Session::flash('error', "The regulatory authority's status has already been updated.");
            return Auth::check() ? redirect('/admin/compliance/index') : redirect()->guest('login');

        }
        Session::flash('error', 'You are not authorised to access that page.');
        return Auth::check() ? redirect('/user/home') : redirect()->guest('login');
    }
    public function reviewLegalDocument(Request $request,$id, $action, $token, $admin_id){
        $now = now();
        $legalCoordinator = User::where('id',$admin_id)->first();
        $adminDetails = Legalcoordinator::where('email',$legalCoordinator->email)->first();
        $legalDocument = ComplianceLegalDocument::with('creator')->where('id',$id)->first();
        $regulatory = RegulatoryAuthority::where('id',$legalDocument->RAC_id)->first();
        if($adminDetails && $legalDocument->token==$token){
            $currentUser = User::where('email',$adminDetails->email)->first();
            if($legalDocument->status!='Approved'){
                if($action=='approve'){
                    $legalDocument->update([
                        'status' => 'Approved',
                        'updated_at' => $now,
                        'approved_by' => $currentUser->id,
                        'approved_at' => $now,
                        'token' => null,
                    ]);
                    //Send Email to Created_by User to inform for Legal Document When approved
                    $emailNotification = EmailNotification::where('module', 'compliance')
                        ->where('item', 'Legal Document Approved')
                        ->where('is_blocked', false)
                        ->first();
                    if($emailNotification){
                        $emailData = [
                            'type' => 'Legal Document',
                            'name' => $regulatory->name,
                            'doc_name' => $legalDocument->name,
                            'creator' => $legalDocument->creator ? $legalDocument->creator->name : '',
                            'action_by' => $currentUser->name,
                            'date' => date('d-m-Y h:i:s A',strtotime($now)),
                            'item' => $emailNotification->item,
                            'email_content' => $emailNotification->email_content,
                        ];
                        //sending mail for responsible persons
                        if($legalDocument->creator && $legalDocument->creator->name){
                            try {
                                Mail::to($legalDocument->creator->email)->send(new LegalDocumentApproved($emailData));
                            } catch (\Exception $e) {
                                Log::error('Failed sending LegalDocumentApproved to creator: '.$e->getMessage(), ['email' => $legalDocument->creator->email]);
                            }
                        }
                        //sending users added for email notifications in admin panel
                        $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                        if (count($compl_userdatas) > 0) {
                            $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas)>0) {
                                try {
                                    Mail::to($userdatas)->send(new LegalDocumentApproved($emailData));
                                } catch (\Exception $e) {
                                    Log::error('Failed sending LegalDocumentApproved to notification users: '.$e->getMessage(), ['emails' => $userdatas]);
                                }
                            }
                        }
                    }else{
                        Session::flash('error', 'Mail Sending Blocked for Legal Document Approved Email');
                    }
                }elseif($action=='reject'){
                    $legalDocument->update([
                        'status' => 'Rejected',
                        'updated_at' => $now,
                        'rejected_by' => $currentUser->id,
                        'rejected_at' => $now,
                        'token' => null,
                    ]);
                    //Send Email to Created_by User to inform for Legal Document When rejected
                    $emailNotification = EmailNotification::where('module', 'compliance')
                        ->where('item', 'Legal Document Rejected')
                        ->where('is_blocked', false)
                        ->first();
                    if($emailNotification){
                        $emailData = [
                            'type' => 'Legal Document',
                            'name' => $regulatory->name,
                            'doc_name' => $legalDocument->name,
                            'creator' => $legalDocument->creator ? $legalDocument->creator->name : '',
                            'action_by' => $currentUser->name,
                            'date' => date('d-m-Y h:i:s A',strtotime($now)),
                            'item' => $emailNotification->item,
                            'email_content' => $emailNotification->email_content,
                        ];
                        //sending mail for responsible persons
                        if($legalDocument->creator && $legalDocument->creator->name){
                            try {
                                Mail::to($legalDocument->creator->email)->send(new LegalDocumentRejected($emailData));
                            } catch (\Exception $e) {
                                Log::error('Failed sending LegalDocumentRejected to creator: '.$e->getMessage(), ['email' => $legalDocument->creator->email]);
                            }
                        }
                        //sending users added for email notifications in admin panel
                        $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                        if (count($compl_userdatas) > 0) {
                            $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas)>0) {
                                try {
                                    Mail::to($userdatas)->send(new LegalDocumentRejected($emailData));
                                } catch (\Exception $e) {
                                    Log::error('Failed sending LegalDocumentRejected to notification users: '.$e->getMessage(), ['emails' => $userdatas]);
                                }
                            }
                        }
                    }else{
                        Session::flash('error', 'Mail Sending Blocked for Legal Document Rejected Email');
                    }
                }
                Session::flash('msg','Legal Document status has been updated.');
                return Auth::check() ? redirect('/admin/compliance/index') : redirect()->guest('login');
            }
            Session::flash('error','The legal document status has already been updated.');
            return Auth::check() ? redirect('/admin/compliance/index') : redirect()->guest('login');

        }
        Session::flash('error','You are not authorised to access that page.');
        return Auth::check() ? redirect('/user/home') : redirect()->guest('login');
    }
    public function nearMissAdd(){
        $departments = Departments::all();
        $type = Nearmisstypes::get();
        $user = User::orderBy('name')->get();
        $issuereviewers = Near::get();
        $models = ModenaDisable::First();
        $targetinfo = Neartarget::all();
        $employee_details = EmployeeDetails::all();
        $client_logos = ClientDetail::first();
        return view('nearmiss-add-form',compact('client_logos'))->with([
            'departments' => $departments,
            'type' => $type,
            'user' => $user,
            'targetinfo' => $targetinfo,
            'issuereviewers' => $issuereviewers,
            'model' => $models,
            'employee_details' => $employee_details,
        ]);
    }
     public function nearMissAddSubmit(AddNearMissRequest $req)
    {
        $cer = 'NULL';
        if ($req->hasFile('issue_file')) {
            $file_array = $req->file('issue_file');
            $array_len = count($file_array);
            $file = [];
            for ($i = 0; $i < $array_len; $i++) {
                $file_size = $file_array[$i]->getClientOriginalName();
                $file_ext = $file_array[$i]->getClientOriginalExtension();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/nearmiss');
                $file_array[$i]->move($destination_path, $new_file_name);
                $file[] = $new_file_name;
            }
            $cer = implode(',', $file);
        }
        $postdate = date('Y-m-d H:i:s', time());
        $processstage = "Guest Submitted";
        $status = "open";
        $title = $req->input('title');
        $observation = $req->input('observation');
        $reporterType = $req->input('reporterType');
        $employeeid = $req->input('employeeid');
        $reporterName = $req->input('reporterName');
        $mobileNumber = $req->input('mobileNumber');
        $email = $req->input('email');
        $companyName = $req->input('companyName');
        $agreedaction = $req->input('agreedaction') ?? 'NULL';
        $ipAddress = $req->ip();
        //-------------------------------Id generation part start-----------------------------------------
            // Retrieve all records for today's date
            $today = date('Y-m-d');
            $todayLatestNearMiss = Nearmiss::whereDate('created_at', $today)->orderByDesc('created_at')->first();
            if ($todayLatestNearMiss) {
                $lastFormatValue = intval(substr($todayLatestNearMiss->issueid, -4));
                $formatValue = str_pad($lastFormatValue + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $formatValue = "0001";
            }
            do {
                $issueId = 'NM-' . date('Ymd') . '-' . $formatValue;
                $formatValue = str_pad(intval($formatValue) + 1, 4, '0', STR_PAD_LEFT);
            } while (Nearmiss::where('issueid', $issueId)->exists());
            //------------------------------------Id generation part end ------------------------------------------
        Nearmiss::create([
            'created_at' => $postdate,
            'updated_at' => $postdate,
            'postdate' => $postdate,
            'title' => $title,
            'file' => $cer,
            'observation' => $observation,
            'agreedaction' => $agreedaction,
            'status' => $status,
            'ip_address' => $ipAddress,
            'reporter_type' => $reporterType,
            'employeeid' => $employeeid,
            'reporter_name' => $reporterName,
            'mobile_number' => $mobileNumber,
            'email' => $email,
            'company_name' => $companyName,
            'processstage' => $processstage,
            'year' => date('Y'),
            'issueid' => $issueId,
        ]);

        $appUrl = BaseController::addSlashToEndOfURL();
        $reviewers = getNonDeletedUsersEmail(Near::pluck('email')->toArray());
        $users = User::where('approved',true)->where('is_email_verified',true)->get();
        $data = Nearmiss::where('issueid', $issueId)->first();
        //Fetch Alert email configuration
        $alert_email = EmailNotification::where('module', 'nearmiss')
                        ->where('item', 'Initial Review Required')
                        ->where('is_blocked', false)
                        ->first();
        if(!$alert_email){
            Log::info('Initial Review Required email configuration not found or is blocked.');
        }else{
            try {
                $userdatas = EmailNotificationsUserDetails::where('notification_id', $alert_email->id)->get();
                $emailData = [
                    'item' => $alert_email->item,
                    'email_content' => $alert_email->email_content,
                    'issueid' => $issueId,
                    'title' => $title,
                    'observation' => $observation,
                ];
                if(count($reviewers) > 0){
                    $emailData['url'] = $appUrl . 'user/nearmiss/initial-review/'.$data->id;
                    $emailData['btn_txt'] = 'Review Near Miss';
                    Mail::to($reviewers)->send(new NewGuestNearMissAwaitingInitialReview($emailData));
                }
                if (count($userdatas) > 0) {
                    $adminaddedusers = $userdatas->pluck('user_id')->toArray();
                    $userdatas = $users->whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas)>0) {
                        $emailData['url'] = $appUrl . 'user/nearmiss';
                        $emailData['btn_txt'] = 'Near Miss Register';
                        Mail::to($userdatas)->send(new NewGuestNearMissAwaitingInitialReview($emailData));
                    }
                }
            } catch (\Exception $e) {
                Log::info('Error sending nearmiss alert email: ' . $e->getMessage());
            }
        }

        $activity = "A near miss issue is added with title " . $title;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $datas = array('name' => 'Guest User', 'email' => 'Guest User', 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'guest', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'));
        ViewActivity::insert($datas);
        Session::flash('msg', "Near miss issue submitted successfully");
        $models = ModenaDisable::First();
        return redirect()->back()->with('model', $models);
    }
    public function nearMissEmployeeidVerification(Request $request)
    {
        $employeeId = trim($request->employeeid);
        if (empty($employeeId)) {
            return response()->json([
                'status' => false,
                'message' => 'Employee ID is required.'
            ]);
        }
        $user = EmployeeDetails::with('department')->where('emp_number', $employeeId)
            ->where('is_deleted', false)
            ->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Employee ID.'
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => [
                'name' => $user->emp_name,
                'department' => optional($user->department)->departmentname ?? '-',
            ]
        ]);
    }
}
