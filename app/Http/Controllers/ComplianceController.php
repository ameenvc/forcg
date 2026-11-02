<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

// use App\Http\Controllers\Carbon;
use App\Charts\ComplianceAnalysis;
use App\Compliance;
use App\Departments;
use App\Http\Controllers\Controller;
use App\Http\Requests\Compliance\AddEntryReviewRequest;
use App\Http\Requests\Compliance\AddReviewComplianceStatusRequest;
use App\Http\Requests\Compliance\ChangeResponsibilityRequest;
use App\Http\Requests\Compliance\ReviewRequest;
use App\Issue;
use App\Legalcoordinator;
use App\LicensePermit;
use App\Corrective;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Mail\ApproveCompliance;
use App\Mail\ComplianceAddEmail;
use App\Mail\ComplianceChangeResponsibility;
use App\Mail\ComplianceChangeStatus;
use App\Mail\ComplianceNearToExpire;
use App\Mail\ComplianceWeeklyReview;
use App\Mail\CorrectiveAddIssue;
use App\Mail\HalfYearly_review;
use App\Mail\LegalRequirementNotFulfilled;
use App\Mail\NewLegalRequirement;
use App\Mail\Quarterly_review;
use App\Mail\RevertCompliance;
use App\Mail\Yearly_review;
use App\Mail\LicensePermitAdd;
use App\Mail\LicensePermitRenewalNotification;
use App\Mail\LicensePermitsUpdated;
use App\Mail\LicensePermitsRenewed;
use App\ModenaDisable;
use App\Risk;
use App\User;
use App\ViewActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;
use App\CompliancePeriodicEvaluationHistory;
use Illuminate\Support\Facades\Auth;
use App\RegulatoryAuthority;
use App\ComplianceLegalDocument;
use App\Mail\NewLegalDocumentAdded;
use App\Mail\NewRegulatoryAuthorityAdded;
use App\Admin;
use App\Http\Requests\Compliance\ResubmitRequest;
use App\Mail\ComplianceReassigned;
use App\Mail\ComplianceReassignmentNotification;
use App\Mail\RequestToReviewAssignedCompliance;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ComplianceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    public function index()
    {
        return view('user.compliance.home');
    }
    public function add()
    {
        $mn = DB::table('departments')->get();
        $models = DB::table('modenadisable')->First();
        $users = User::where('approved', 1)->orderBy('name')->get();
        $legalCoordinatorEmails = Legalcoordinator::pluck('email')->toArray();
        $legalCoordinators = User::whereIn('email', $legalCoordinatorEmails)->orderBy('name')->get();
        return view('user.compliance.add',compact('legalCoordinators'))->withMn($mn)->withUsers($users)->with('model', $models);
    }
    public function getLegalDocuments($authority_id)
    {
        // Find the authority and load its related legal documents
        $documents = ComplianceLegalDocument::where('RAC_id', $authority_id)->where('status','Approved')->get();

        // Check if the authority was found
        if (!$documents) {
            return response()->json([]);
        }

        // Return the documents as a JSON array
        return response()->json($documents);
    }
    public function checkLegalDocument(Request $request)
    {
        $authority_id = $request->input('authority_id');
        $document_name = $request->input('document_name');
        $exists = ComplianceLegalDocument::where('RAC_id', $authority_id)
            ->where('name', $document_name)
            ->where('status', 'Approved')
            ->whereNull('deleted_at')
            ->exists();
        return response()->json(['exists' => $exists]);
    }
    public function checkRegulatory(Request $request)
    {
        $regulatory_name = $request->input('regulatory_name');
        $exists = RegulatoryAuthority::where('name', $regulatory_name)
            ->where('status', 'Approved')
            ->whereNull('deleted_at')
            ->exists();
        return response()->json(['exists' => $exists]);
    }
    public function getRegulatory(Request $request){
        $query = RegulatoryAuthority::where('status', 'Approved')->whereNull('deleted_at')->orderBy('name');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }
        $authorities = $query->select('id', 'name','status')->get();
        $addOn = new RegulatoryAuthority();
        $addOn->id = '0';
        $addOn->name = 'Other';
        $addOn->status = 'Approved';
        $authorities->push($addOn);
        return response()->json([
            'items' => $authorities->toArray(),
            'hasMore' => false
        ]);
    }
    public function Insert(Request $req)
    {
        $other_reg = $req->input('other_reg');
        $other_doc = $req->input('other_doc');
        $legalDocument = $req->input('issue');
        $authority = $req->input('authority');
        $fields = [
            'version' => 'required|',
            'regulation' => 'required|',
            'requirement' => 'required|',
            'aspect' => 'required|',
            'owner' => 'required|',
            'responsibility' => 'required|email|exists:users,email',
            'frequency' => 'required|',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:' . env('FILE_SIZE') * 1024,
        ];
        $message = [
            'responsibility.required' => 'Responsible Person field is required.',
            'owner.required' => 'Department field is required.',
            'frequency.required' => 'Review frequency field is required.'
        ];
        if ($authority == '0') {
            $fields['other_reg'] = [
                'required',
                Rule::unique('regulatory_authorities', 'name')
                    ->whereNull('deleted_at')
                    ->whereNotIn('status', ['Rejected','Pending Review']),
            ];
            $fields['other_doc'] = 'required';
        } elseif ($legalDocument == 'other') {
            $fields['authority'] = 'required|exists:regulatory_authorities,id';
            $fields['other_doc'] = [
                'required',
                Rule::unique('compliance_legal_documents', 'name')
                    ->whereNull('deleted_at')
                    ->where('RAC_id', $authority)
                    ->whereNotIn('status', ['Rejected','Pending Review']),
            ];
        }
        $validator = Validator::make($req->all(), $fields,$message);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $cer = 'NULL';
            if ($req->hasFile('file')) {
                $file = $req->file('file');
                $file_size = $file->getClientOriginalName();
                $new_file_name = $file_size;
                $destination_path = public_path('storage/compliance_files');
                $file->move($destination_path, $new_file_name);
                $file = $new_file_name;
                $cer = $file;
            }
            $selectedCoordinator = $req->input('legalCoordinator');
            $coordinatorDetails = User::findOrFail($selectedCoordinator);
            $currentUser = User::where('email',Auth::user()->email)->first();
            $isCoordinator = Legalcoordinator::where('email',$currentUser->email)->exists();
            $isAdmin = Admin::where('email',$currentUser->email)->exists();
            $admins = Admin::all();
            $status = $isCoordinator || $isAdmin ? 'Approved' : 'Pending Review';
            $token = Str::random(40);
            $expires = now()->addDays(365);
            $owner = $req->input('owner');
            $departmentName = Departments::where('id', $owner)->value('departmentname');
            $isRegExists = false;
            $isDocExists = false;
            if($authority=='0'){
                $regulatory = RegulatoryAuthority::where('name',$other_reg)->whereNull('deleted_at')->first();
                if(!$regulatory){
                    $regulatory = RegulatoryAuthority::create([
                        'name' => $other_reg,
                        'status' => $status,
                        'no_of_documents' => 1,
                        'initial_name' => $other_reg,
                        'created_by' =>  $currentUser->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'token' => $token,
                    ]);
                }else{
                    $regulatory->update([
                        'status' => $status,
                        'updated_at' => now(),
                        'token' => $token,
                    ]);
                    $isRegExists = true;
                }

                $document = ComplianceLegalDocument::where('RAC_id',$regulatory->id)->where('name',$other_doc)->whereNull('deleted_at')->first();
                if(!$document){
                    $document = ComplianceLegalDocument::create([
                        'name' => $other_doc,
                        'status' => $status,
                        'created_by' =>  $currentUser->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'RAC_id' => $regulatory->id
                    ]);
                }else{
                    $document->update([
                        'status' => $status,
                        'updated_at' => now(),
                        'token' => $token,
                    ]);
                }
                $authority = $regulatory->id;
                $legalDocument = $document->id;
                //Email for new regulatory added for the Admin
                if(!($isCoordinator || $isAdmin)){
                    if(!$isRegExists){
                        $emailNotification = EmailNotification::where('module', 'compliance')
                            ->where('item', 'Approval Required: New Regulatory Authority Added')
                            ->where('is_blocked', false)
                            ->first();
                        if($emailNotification){
                            $emailData = [
                                'type' => 'Regulatory Authority',
                                'name' => $other_reg,
                                'doc_name' => $other_doc,
                                'submitted_by' => $currentUser->name,
                                'department' => $departmentName,
                                'date' => now(),
                                'status' => $status,
                                'item' => $emailNotification->item,
                                'email_content' => $emailNotification->email_content,
                            ];
                            //sending mail for responsible persons
                            if($coordinatorDetails){
                                $approvalLink = url("/compliance/review-regulatory/$authority/approve/$token/$coordinatorDetails->id");
                                // $approvalLink = URL::temporarySignedRoute(
                                //     'regulatoryapproval.process',
                                //     $expires,
                                //     ['id' => $authority, 'action' => 'approve', 'token' => $token, 'admin_id' => $coordinatorDetails->id]
                                // );
                                // $rejectLink = URL::temporarySignedRoute(
                                //     'regulatoryapproval.process',
                                //     $expires,
                                //     ['id' => $authority, 'action' => 'reject', 'token' => $token, 'admin_id' => $coordinatorDetails->id]
                                // );
                                $rejectLink = url("/compliance/review-regulatory/$authority/reject/$token/$coordinatorDetails->id");
                                $emailData['approval_link'] = $approvalLink;
                                $emailData['reject_link'] = $rejectLink;
                                $emailData['admin_name'] = $coordinatorDetails->name;
                                try {
                                    Mail::to($coordinatorDetails->email)->send(new NewRegulatoryAuthorityAdded($emailData));
                                } catch (\Exception $e) {
                                    Log::error('Failed to send NewLegalDocumentAdded email to '.$coordinatorDetails->email.': '.$e->getMessage());
                                    Session::flash('error', 'Mail sending failed for New Legal Document Added to '.$coordinatorDetails->email);
                                }
                            }
                            //sending users added for email notifications in admin panel
                            $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                            if (count($compl_userdatas) > 0) {
                                $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                                if (count($userdatas)>0) {
                                    try {
                                        Mail::to($userdatas)->send(new NewRegulatoryAuthorityAdded($emailData));
                                    } catch (\Exception $e) {
                                        Log::error('Failed to send NewRegulatoryAuthorityAdded email to '.$admin->email.': '.$e->getMessage());
                                        Session::flash('error', 'Mail sending failed for New Regulatory Authority Added to '.implode($userdatas));
                                    }
                                }
                            }
                        }else{
                            Session::flash('error', 'Mail Sending Blocked for Approval Required: New Regulatory Authority Added Email');
                        }
                    }
                }
            }
            if($legalDocument=='other'){
                $reg = RegulatoryAuthority::findOrFail($authority);
                $document = ComplianceLegalDocument::where('RAC_id',$authority)->where('name',$other_doc)->whereNull('deleted_at')->first();
                if(!$document){
                    $document = ComplianceLegalDocument::create([
                        'name' => $other_doc,
                        'status' => $status,
                        'created_by' =>  $currentUser->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'RAC_id' => $authority,
                        'token' => $token
                    ]);
                     $reg->update([
                        'no_of_documents' => $reg->no_of_documents + 1,
                        'updated_at' => now(),
                    ]);
                }else{
                    $document->update([
                        'status' => $status,
                        'updated_at' => now(),
                        'token' => $token,
                    ]);
                    $isDocExists = true;
                }

                $legalDocument = $document->id;
                //Email for new legal document added for the Admin
                if(!($isCoordinator || $isAdmin)){
                    if(!$isDocExists){
                        $emailNotification = EmailNotification::where('module', 'compliance')
                            ->where('item', 'Approval Required: New Legal Document Added')
                            ->where('is_blocked', false)
                            ->first();
                        if($emailNotification){
                            $emailData = [
                                'type' => 'Legal Document',
                                'name' => $reg->name,
                                'doc_name' => $other_doc,
                                'submitted_by' => $currentUser->name,
                                'department' => $currentUser->depid,
                                'date' => now(),
                                'status' => $status,
                                'item' => $emailNotification->item,
                                'email_content' => $emailNotification->email_content,
                                'doc_id' => $legalDocument,
                                'token' => $token
                            ];
                            //sending mail for responsible persons
                            if($coordinatorDetails){
                                // $approvalLink = URL::temporarySignedRoute(
                                //     'legaldocument.approval.process',
                                //     $expires,
                                //     ['id' => $legalDocument, 'action' => 'approve', 'token' => $token, 'admin_id' => $coordinatorDetails->id]
                                // );
                                // $rejectLink = URL::temporarySignedRoute(
                                //     'legaldocument.approval.process',
                                //     $expires,
                                //     ['id' => $legalDocument, 'action' => 'reject', 'token' => $token, 'admin_id' => $coordinatorDetails->id]
                                // );
                                $approvalLink = url("/compliance/review-legal-document/$legalDocument/approve/$token/$coordinatorDetails->id");
                                $rejectLink = url("/compliance/review-legal-document/$legalDocument/reject/$token/$coordinatorDetails->id");
                                $emailData['approval_link'] = $approvalLink;
                                $emailData['reject_link'] = $rejectLink;
                                $emailData['admin_name'] = $coordinatorDetails->name;
                                try {
                                    Mail::to($coordinatorDetails->email)->send(new NewLegalDocumentAdded($emailData));
                                } catch (\Exception $e) {
                                    Log::error('Failed to send NewLegalDocumentAdded email to '.$coordinatorDetails->email.': '.$e->getMessage());
                                    Session::flash('error', 'Mail sending failed for New Legal Document Added to '.$coordinatorDetails->email);
                                }
                            }
                            //sending users added for email notifications in admin panel
                            $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                            if (count($compl_userdatas) > 0) {
                                $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                                if (count($userdatas)>0) {
                                    try {
                                        Mail::to($userdatas)->send(new NewLegalDocumentAdded($emailData));
                                    } catch (\Exception $e) {
                                        Log::error('Failed to send NewLegalDocumentAdded email to '.implode($userdatas).': '.$e->getMessage());
                                        Session::flash('error', 'Mail sending failed for New Legal Document Added to '.implode($userdatas));
                                    }
                                }
                            }
                        }else{
                            Session::flash('error', 'Mail Sending Blocked for Approval Required: New Legal Document Added');
                        }
                    }

                }
            }
            $version = $req->input('version');
            $regulation = $req->input('regulation');
            $responsibility = $req->input('responsibility');
            $requirement = $req->input('requirement');
            $aspect = $req->input('aspect');

            $due_date = $req->input('duedate');
            $status = $req->input('status');
            $frequency = $req->input('frequency');
            $d = date('d-m-Y h:i:s A', time());
            $regId = RegulatoryAuthority::where('id',$authority)->value('name');
            //-------------------------------Id generation part start-----------------------------------------
            // Retrieve all records for today's date
            $today = date('Y-m-d');
            $todayLatestCompliance = Compliance::whereYear('created_at', $today)->orderByDesc('created_at')->first();
            if ($todayLatestCompliance) {
                $lastFormatValue = intval(substr($todayLatestCompliance->auth_id, -5));
                $formatValue = str_pad($lastFormatValue + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $formatValue = "00001";
            }
            do {
                $issueId = 'CMP-' . date('Y') . '-' . $formatValue;
                $formatValue = str_pad(intval($formatValue) + 1, 5, '0', STR_PAD_LEFT);
            } while (Compliance::where('auth_id', $issueId)->exists());
            $auth_id = $issueId;
            //------------------------------------Id generation part end ------------------------------------------
            $ID = Auth::user()->employeeid;
            $data = array(
                'created_at' => $d,
                // 'count' => $count,
                'issue' => $legalDocument,
                'version' => $version,
                'regulation' => $regulation,
                'authority' => $authority,
                'requirement' => $requirement,
                'aspect' => $aspect,
                'owner' => $owner,
                'postedby' => $ID,
                'frequency' => $frequency,
                'comp_status' => 'Under Review',
                'responsibility' => $responsibility,
                'file' => $cer,
                'auth_id' => $auth_id,
                'legal_coordinator' => $selectedCoordinator,
                'freq_review' => $d,
            );
            DB::table('compliance')->insert($data);
            //inserting values to activity table  superadmin codes
            $activity = "New Legal requirement is add for Approval with Requirement " . $requirement . " and with aspect " . $aspect . " with process owner as " . $owner;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
            ViewActivity::insert($datas);
            //superadmin code ends

            $d = DB::table('compliance')->orderBy('Cid', 'desc')->first();
            Session::flash('msg', "Legal requirement sent for approval Successfully");
            Session::flash('success_email', "Trigger Email Sending");
            Session::flash('Cid', $d->Cid);
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }
    private function sendMail($compliance){
        $emailNotification = EmailNotification::where('module', 'compliance')
            ->where('item', 'Request to Review Assigned Compliance - [Compliance ID]')
            ->where('is_blocked', false)
            ->first();

        if ($emailNotification) {
            $app_url = BaseController::addSlashToEndOfURL();
            $emailData = [
                'reg_name' => $compliance->regulatory ? $compliance->regulatory->name : '--',
                'doc_name' => $compliance->legalDocuments ? $compliance->legalDocuments->name : '--',
                'title' => $compliance->regulation,
                'requirement' =>$compliance->requirement,
                'responsible' => $compliance->responsible ? $compliance->responsible->name : '--',
                'department' => $compliance->dep ? $compliance->dep->departmentname : '--',
                'added_on' => date('d-m-Y h:i:s A', strtotime($compliance->created_at)),
                'new_coordinator' => $compliance->legalCoordinator ? $compliance->legalCoordinator->name : null,
                'added_by' => Auth::user()->name,
                'item' => $emailNotification->item,
                'email_content' => $emailNotification->email_content,
                'auth_id' => $compliance->auth_id,
                'app_url' => $app_url,
                'id' => $compliance->Cid
            ];

            // Select Mailable class based on action
            $mailable = new RequestToReviewAssignedCompliance($emailData);

            // Send to Creator
            if ($compliance->legalCoordinator && $compliance->legalCoordinator->email) {
                try {
                    Mail::to($compliance->legalCoordinator->email)->send($mailable);
                } catch (\Exception $e) {
                    Log::error("Failed sending Request to Review Assigned Compliance - [Compliance ID] email to new legal coordinator: " . $e->getMessage());
                }
            }

            // Send to added users in admin panel
            $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
            if ($compl_userdatas->isNotEmpty()) {
                $adminAddedUserIds = $compl_userdatas->pluck('user_id')->toArray();
                $adminEmails = User::whereIn('id', $adminAddedUserIds)->pluck('email')->toArray();

                if (!empty($adminEmails)) {
                    try {
                        Mail::to($adminEmails)->send($mailable);
                    } catch (\Exception $e) {
                        Log::error("Failed sending Request to Review Assigned Compliance - [Compliance ID] email to added users: " . $e->getMessage());
                    }
                }
            }
        }else{
            Log::info('Mail Sending Blocked for Request to Review Assigned Compliance');
        }

        // Add Compliance Emails
        $emaildata = array(
            'issue' => $compliance->legalDocuments ? $compliance->legalDocuments->name : '--',
            'version' => $compliance->version,
            'regulation' => $compliance->regulation,
            'authority' => $compliance->regulatory ? $compliance->regulatory->name : '--',
            'auth_id' => $compliance->auth_id,
            'postedby' => $compliance->postedByCompliance ? $compliance->postedByCompliance->name : '--',
            'department' => $compliance->dep ? $compliance->dep->departmentname : '--',
        );
        $compAddMails = EmailNotification::where('module', 'compliance')
            ->where('item', 'Compliance Add Email')
            ->where('is_blocked', false)
            ->first();
        if($compAddMails){
            $emaildata['item'] = $compAddMails->item;
            $emaildata['email_content'] = $compAddMails->email_content;
            //sending mail for responsible persons
            if($compliance->responsible && $compliance->responsible->email){
                try {
                    Mail::to($compliance->responsible->email)->send(new ComplianceAddEmail($emaildata));
                } catch (\Exception $e) {
                    Log::error('Failed to send ComplianceAddEmail to '.$compliance->responsible->email.': '.$e->getMessage());
                }
            }
            //sending users added for email notifications in admin panel
            $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $compAddMails->id)->get();
            if (count($compl_userdatas) > 0) {
                $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas)>0) {
                    try {
                        Mail::to($userdatas)->send(new ComplianceAddEmail($emaildata));
                    } catch (\Exception $e) {
                        Log::error('Failed to send ComplianceAddEmail to '.implode(', ',$userdatas).': '.$e->getMessage());
                    }
                }
            }
        }else{
            Log::info('Mail Sending Blocked for Compliance Add Email');
        }
        $legalAddMails = EmailNotification::where('module', 'compliance')
            ->where('item', 'New Legal Requirement')
            ->where('is_blocked', false)
            ->first();
        if($legalAddMails){
            $emaildata['item'] = $legalAddMails->item;
            $emaildata['email_content'] = $legalAddMails->email_content;
            //Sending Mail(Responsibility) To Responsible HOD
            if($compliance->dep && $compliance->dep->hod){
                try {
                    Mail::to($compliance->dep->hod)->send(new NewLegalRequirement($emaildata));
                } catch (\Exception $e) {
                    Log::error('Failed to send NewLegalRequirement email to HOD: '.$e->getMessage());
                }
            }
            //sending users added for email notifications in admin panel
            $legal_userdatas = EmailNotificationsUserDetails::where('notification_id', $legalAddMails->id)->get();
            if (count($legal_userdatas) > 0) {
                $adminaddedusers = $legal_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas)>0) {
                    try {
                        Mail::to($userdatas)->send(new NewLegalRequirement($emaildata));
                    } catch (\Exception $e) {
                        Log::error('Failed to send NewLegalRequirement email to userdatas: '.$e->getMessage());
                    }
                }
            }
        }else{
            Log::info('Mail Sending Blocked for New Legal Requirement');
        }
        return response()->json(['status' => 'ok']);
    }
    public function sendReviewRequestMail(){
        // C. Handle Emails
        if($_GET['id']){
            $Cid = $_GET['id'];
            $compliance = Compliance::with(['regulatory','legalDocuments','legalCoordinator','responsible','dep','postedByCompliance'])->where('Cid',$Cid)->first();
            $type = $_GET['type'];
            if($type=='add' && !$compliance->email_add_send){
                $compliance->email_add_send = 1;
                $compliance->save();
                return $this->sendMail($compliance);
            }
            if($type=='resubmit' && !$compliance->email_resubmit_send){
                $compliance->email_resubmit_send = 1;
                $compliance->save();
                return $this->sendMail($compliance);
            }
            return response()->json(['status' => 'no_action']);
        }
    }
    public function reject(Request $req)
    {
        $today = date('Y-m-d');
        $status = "Reverted by HOD";
        $Cid = $req->input('compliance_id');
        $coordinators = Legalcoordinator::pluck('email')->toArray();
        $added_person = [Auth::user()->email];
        $updatedat = date('d-m-Y h:i:s A', time());
        $c = Compliance::where('Cid', $Cid)->first();

        $coordinators = Legalcoordinator::pluck('email')->toArray();
        $maildata = [
            'Cid' => $Cid,
            'issue' => $c->issue,
            'owner' => $c->dep ? $c->dep->departmentname : '-',
        ];

        $data = array(
            'pro_status' => $status,
            'comp_status' => 'Need improvement',
            'revertedby' => Auth::user()->employeeid,
            'updatedat' => $updatedat,
        );

        DB::table('compliance')
            ->where('Cid', $Cid)
            ->update($data);
        $compliance = Compliance::where('Cid',$Cid)->first();
        // send mail notification
        if ($today == date("Y-m-d", strtotime($updatedat . ' + 7 days'))) {
            $compWeeklyReviewMails = EmailNotification::where('module', 'compliance')
                ->where('item', 'Compliance Weekly Review')
                ->where('is_blocked', false)
                ->first();
            if($compWeeklyReviewMails){
                $emaildata = array(
                    'issue' => $compliance->issue,
                    'version' => $compliance->version,
                    'regulation' => $compliance->regulation,
                    'authority' => $compliance->authority,
                    'auth_id' => $compliance->auth_id,
                    'item' => $compWeeklyReviewMails->item,
                    'email_content' => $compWeeklyReviewMails->email_content,
                    'postedby' => User::where('employeeid',$compliance->postedby)->value('name'),
                    'department' => Departments::where('id',$compliance->owner)->value('departmentname'),
                );
                //sending mail for responsible persons
                if($c->responsibility){
                    Mail::to($c->responsibility)->send(new ComplianceWeeklyReview($emaildata));
                }
                //sending users added for email notifications in admin panel
                $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $compWeeklyReviewMails->id)->get();
                if (count($compl_userdatas) > 0) {
                    $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas)>0) {
                        Mail::to($userdatas)->send(new ComplianceWeeklyReview($emaildata));
                    }
                }
            }else{
                Session::flash('error', 'Mail Sending Blocked for Compliance Weekly Review');
            }
        }
        //send mail to legal coordinator
        $revertMails = EmailNotification::where('module', 'compliance')
                ->where('item', 'Revert Compliance')
                ->where('is_blocked', false)
                ->first();
        if($revertMails){
            $emaildata = array(
                'issue' => $compliance->issue,
                'version' => $compliance->version,
                'regulation' => $compliance->regulation,
                'authority' => $compliance->authority,
                'auth_id' => $compliance->auth_id,
                'item' => $revertMails->item,
                'email_content' => $revertMails->email_content,
                'postedby' => User::where('employeeid',$compliance->postedby)->value('name'),
                'department' => Departments::where('id',$compliance->owner)->value('departmentname'),
            );
            //sending mail for responsible persons
            if(count($coordinators)>0){
                Mail::to($coordinators)->send(new RevertCompliance($emaildata));
            }
            //sending users added for email notifications in admin panel
            $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $revertMails->id)->get();
            if (count($compl_userdatas) > 0) {
                $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas)>0) {
                    Mail::to($userdatas)->send(new RevertCompliance($emaildata));
                }
            }
        }else{
            Session::flash('error', 'Mail Sending Blocked for Revert Compliance');
        }
        //inserting values to activity table superadmin codes
        $activity = "Process Stage is Updated as Reverted By HOD where Compliance Id as" . $Cid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $theTime = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
        ViewActivity::insert($datas);
        //end of superadmin codes
        Session::flash('msg', "Compliance with Id #" . $c->auth_id . " rejected.");

    }

    public function approve(AddEntryReviewRequest $request)
    {
        $data = $request->validated();
        $id = $data['id'];
        $auth_id = $request->input('auth_id');
        // $person_control = [];
        // foreach ($data['person_control'] as $value) {
        //     $person_control[] = User::where('id', $value)->value('name');
        // }
        // $person_control = implode(',', $person_control);

        $status = "Agreed by HOD";
        $coordinators = Legalcoordinator::pluck('email')->toArray();
        $added_person = [Auth::user()->email];
        $mergedArray = array_unique(array_merge($coordinators, $added_person));
        $c = Compliance::where('Cid', $id)->firstOrFail();
        $maildata = array(
            'Cid' => $id,
            'issue' => $c->issue,
            'owner' => $c->dep ? $c->dep->departmentname : '-',
        );

        $updatedat = date('d-m-Y h:i:s A', time());

        $data = [
            'pro_status' => $status,
            'comp_status' => 'Awaiting Compliance Evaluation',
            'approved_at' => now(),
            'approvedby' => Auth::user()->employeeid,
            'updatedat' => $updatedat,
            'frequency' => $data['frequency'],
            'authority' => $data['authority'],
            'issue' => $data['issue'],
            'version' => $data['version'],
            'regulation' => $data['regulation'],
            'owner' => $data['owner'],
            'aspect' => $data['aspect'],
            'requirement' => $data['requirement'],
            'responsibility' => $data['responsibility'],
        ];

        DB::table('compliance')
            ->where('Cid', $id)
            ->update($data);
        $compliance = Compliance::where('Cid', $id)->first();
        $approveMails = EmailNotification::where('module', 'compliance')
            ->where('item', 'Approve Compliance')
            ->where('is_blocked', false)
            ->first();
        if($approveMails){
            $emaildata = array(
                'issue' => $compliance->issue,
                'version' => $compliance->version,
                'authority' => $compliance->authority,
                'auth_id' => $compliance->auth_id,
                'item' => $approveMails->item,
                'email_content' => $approveMails->email_content,
                'postedby' => User::where('employeeid',$compliance->postedby)->value('name'),
                'department' => Departments::where('id',$compliance->owner)->value('departmentname'),
            );
            //sending mail for responsible persons
            if(count($mergedArray)>0){
                Mail::to($mergedArray)->send(new ApproveCompliance($emaildata));
            }
            //sending users added for email notifications in admin panel
            $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $approveMails->id)->get();
            if (count($compl_userdatas) > 0) {
                $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas)>0) {
                    Mail::to($userdatas)->send(new ApproveCompliance($emaildata));
                }
            }
        }else{
            Session::flash('error', 'Mail Sending Blocked for Approve Compliance');
        }
        //inserting values to activity table superadmin codes
        $activity = "Legal Requirement was Approved  where Compliance Id as" . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $theTime = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
        ViewActivity::insert($datas);
        //end of superadmin codes
        Session::flash('msg', "New entry with Id #" . $auth_id . " is reviewed successfully");
        //Session::flash('msg', "Compliance with Id #" . $c->auth_id . " approved successfully.");
        $models = DB::table('modenadisable')->first();

        return redirect('/user/compliancemgmt')->with('model', $models);

    }
    public function changeResponsibility(int $id)
    {
        $models = ModenaDisable::first();
        $departments = Departments::all();
        $users = User::where('approved', true)->get();
        $data = Compliance::with(['regulatory','legalDocuments'])->where('Cid', '=', $id)->firstOrFail();
        $coordinators = Legalcoordinator::pluck('email')->toArray();
        if(!in_array(Auth::user()->email, $coordinators)){
            Session::flash('error', 'You are not authorized to access that page');
            return redirect('/user/compliancemgmt');
        }
        return view('user.compliance.change_responsibility')->with([
            'departments' => $departments,
            'users' => $users,
            'data' => $data,
            'id' => $id,
            'model' => $models,
        ]);
    }

    public function postChangeResponsibility(ChangeResponsibilityRequest $request)
    {
        $coordinators = Legalcoordinator::pluck('email')->toArray();
        if(!in_array(Auth::user()->email, $coordinators)){
            Session::flash('error', 'You are not authorized to access that page');
            return redirect('/user/compliancemgmt');
        }
        $data = $request->validated();
        $id = $data['id'];

        $compliance = Compliance::where("Cid", $id)->firstOrFail();

        Compliance::where("Cid", $id)->update([
            'responsibility' => $data['responsibility'],
        ]);

        //inserting values to activity table superadmin codes
        $activity = "Responsibility changed where Compliance Id is as " . $compliance->auth_id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $theTime = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
        ViewActivity::insert($datas);
        //end of superadmin codes
        $mails = EmailNotification::where('module', 'compliance')
            ->where('item', 'Compliance Change Responsibility')
            ->where('is_blocked', false)
            ->first();
        if($mails){
            $emaildata = array(
                'issue' => $compliance->issue,
                'version' => $compliance->version,
                'regulation' => $compliance->regulation,
                'authority' => $compliance->authority,
                'auth_id' => $compliance->auth_id,
                'item' => $mails->item,
                'email_content' => $mails->email_content,
                'postedby' => User::where('employeeid',$compliance->postedby)->value('name'),
                'department' => Departments::where('id',$compliance->owner)->value('departmentname'),
            );
            //sending mail for responsible persons
            if($data['responsibility']){
                Mail::to($data['responsibility'])->send(new ComplianceChangeResponsibility($emaildata));
            }
            //sending users added for email notifications in admin panel
            $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails->id)->get();
            if (count($compl_userdatas) > 0) {
                $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas)>0) {
                    Mail::to($userdatas)->send(new ComplianceChangeResponsibility($emaildata));
                }
            }
        }else{
            Session::flash('error', 'Mail Sending Blocked for Compliance Change Responsibility');
        }
        Session::flash('msg', "Compliance responsiblity with Id #" . $compliance->auth_id . " has been changed successfully");
        $models = DB::table('modenadisable')->first();

        return redirect('/user/compliancemgmt')->with('model', $models);
    }
    public function regcompliance(string $type = null)
    {
        //below is superadmin related codes
        $sw = 1;
        $compliance = DB::table('modpageload')->where('id', 1)->get();
        if (sizeof($compliance) == 0) {
            DB::table('modpageload')->insert(['id' => 1, 'correctiveaction' => 0, 'riskmanagement' => 0, 'objectives' => 0, 'opportunity' => 0, 'compliance' => 1, 'enviaspects' => 0, 'auditmanagement' => 0, 'ohsrisk' => 0, 'documentmanagement' => 0]);
        } else {
            $compliance = DB::table('modpageload')->where('id', 1)->First();
            $compliance->compliance = $compliance->compliance + 1;
            DB::table('modpageload')->update(['compliance' => $compliance->compliance]);
        }
        $text = null;
        switch ($type) {
            case "complied":
                $text = "Complied";
                break;
            case "not-complied":
                $text = "Not Complied";
                break;
            case "partially-complied":
                $text = "Partially Complied";
                break;
            case "under-review":
                $text = "Under Review";
                break;
            case "awaiting-compliance-evaluation":
                $text = "Awaiting Compliance Evaluation";
                break;
        }
        //end of superadmin codes

        //-----------------------------register----------------
        $data = [];
        $departments = Departments::all();
        $issues = DB::table('compliance')->select('issue')->distinct()->get();
        $status = DB::table('compliance')->select('comp_status')->distinct()->get();
        $coordinators = DB::table('legalcoordinators')->pluck('email');
        $coordinatorsArray = DB::table('legalcoordinators')->pluck('email')->toArray();
        $coordinators_employeeids = User::whereIn('email', $coordinators)->pluck('employeeid')->toArray();
        $mr = DB::table('representatives')->pluck('email');
        $a = Auth::user()->email;
        if (in_array($a, $coordinatorsArray)) {
            $data = $text
                    ? Compliance::with(['regulatory','legalDocuments','legalCoordinator'])->whereIn('Hidden',['0','1'])->where('comp_status', $text)->orderByDesc('Cid')->get()
                    : Compliance::with(['regulatory','legalDocuments','legalCoordinator'])->whereIn('Hidden',['0','1'])->orderByDesc('Cid')->get();
        } else {
            $data = $text
                    ? Compliance::with(['regulatory','legalDocuments','legalCoordinator'])->where('Hidden','0')->where('visibleto','NULL')->where('comp_status', $text)->get()
                    : Compliance::where('Hidden','0')->where('visibleto','NULL')->get();
            $visibleHiddenData = $text
                    ? Compliance::with(['regulatory','legalDocuments','legalCoordinator'])->where('Hidden','1')->whereNotNull('visibleto')->where('comp_status', $text)->get()
                    : Compliance::with(['regulatory','legalDocuments','legalCoordinator'])->where('Hidden','1')->whereNotNull('visibleto')->get();
            if(count($visibleHiddenData)>0){
                foreach($visibleHiddenData as $vh){
                    $visible = explode(',',$vh->visibleto);
                    if(in_array($a,$visible)){
                        $data->push($vh);
                    }
                }
            }
            $data = $data->sortByDesc('Cid');
        }
        $today = date('Y-m-d');
        foreach ($data as $dat) {
            $hod = DB::table('departments')->where('departmentname', $dat->owner)->pluck('hod')->toArray();
            if ($dat->duedate > $today && $dat->comp_status == 'Under Review') {
                $maildata = array(
                    'issue' => $dat->issue,
                    'version' => $dat->version,
                    'regulation' => $dat->regulation,
                    'authority' => $dat->authority,
                    'auth_id' => $dat->auth_id,
                    'department' => Departments::where('id',$dat->owner)->value('departmentname'),
                );
                if ($dat->frequency == 'Quarterly' && $today == date("Y-m-d", strtotime($dat->freq_review . ' + 91 days'))) {
                    $mails1 = EmailNotification::where('module', 'compliance')
                        ->where('item', 'Quarterly Review')
                        ->where('is_blocked', false)
                        ->first();
                    if($mails1){
                        $emaildata = array_merge($maildata,array('item' => $mails1->item,'email_content' => $mails1->email_content));
                        //sending mail for legal coordinators
                        if(count($coordinators)>0){
                            Mail::to($coordinators)->send(new Quarterly_review($emaildata));
                        }
                        // Send mails to hods
                        if($hod){
                            Mail::to($hod)->send(new Quarterly_review($emaildata));
                        }
                        //sending users added for email notifications in admin panel
                        $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails1->id)->get();
                        if (count($compl_userdatas) > 0) {
                            $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas)>0) {
                                Mail::to($userdatas)->send(new Quarterly_review($emaildata));
                            }
                        }
                    }else{
                        Session::flash('error', 'Mail Sending Blocked for Quarterly Review');
                    }
                } elseif ($dat->frequency == 'Half yearly' && $today == date("Y-m-d", strtotime($dat->freq_review . ' + 185 days'))) {
                    $mails2 = EmailNotification::where('module', 'compliance')
                        ->where('item', 'Half Yearly Review')
                        ->where('is_blocked', false)
                        ->first();
                    if($mails2){
                        $emaildata = array_merge($maildata,array('item' => $mails2->item,'email_content' => $mails2->email_content));
                        //sending mail for legal coordinators
                        if(count($coordinators)>0){
                            Mail::to($coordinators)->send(new HalfYearly_review($emaildata));
                        }
                        // Send mails to hods
                        if($hod){
                            Mail::to($hod)->send(new HalfYearly_review($emaildata));
                        }
                        //sending users added for email notifications in admin panel
                        $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails2->id)->get();
                        if (count($compl_userdatas) > 0) {
                            $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas)>0) {
                                Mail::to($userdatas)->send(new HalfYearly_review($emaildata));
                            }
                        }
                    }else{
                        Session::flash('error', 'Mail Sending Blocked for Half Yearly Review');
                    }
                } elseif ($dat->frequency == 'Yearly' && $today == date("Y-m-d", strtotime($dat->freq_review . ' + 365 days'))) {
                    $mails3 = EmailNotification::where('module', 'compliance')
                        ->where('item', 'Yearly Review')
                        ->where('is_blocked', false)
                        ->first();
                    if($mails3){
                        $emaildata = array_merge($maildata,array('item' => $mails3->item,'email_content' => $mails3->email_content));
                        //sending mail for legal coordinators
                        if(count($coordinators)>0){
                            Mail::to($coordinators)->send(new Yearly_review($emaildata));
                        }
                        // Send mails to hods
                        if($hod){
                            Mail::to($hod)->send(new Yearly_review($emaildata));
                        }
                        //sending users added for email notifications in admin panel
                        $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails3->id)->get();
                        if (count($compl_userdatas) > 0) {
                            $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas)>0) {
                                Mail::to($userdatas)->send(new Yearly_review($emaildata));
                            }
                        }
                    }else{
                        Session::flash('error', 'Mail Sending Blocked for Yearly Review');
                    }
                }
                $freq_review = date('Y-m-d');
                $data1 = array('freq_review' => $freq_review);
                DB::table('compliance')->where('Cid', $dat->Cid)->update($data1);
            }
        }
        $compliancedepts = Compliance::groupBy('owner')->pluck('owner')->toArray();
        $departmentnames = array();
        foreach ($compliancedepts as $compliancedept) {
            $dept_names = DB::table('departments')->where('id', $compliancedept)->value('departmentname');
            if ($dept_names) {
                array_push($departmentnames, $dept_names);
            }
        }
        $alldeptnames = implode(',', $departmentnames);

        $responsible_emails = Compliance::groupBy('responsibility')->pluck('responsibility')->toArray();
        $usernameslist = array();
        foreach ($responsible_emails as $responsible_email) {
            $resp_names = User::where('email', $responsible_email)->value('name');
            if ($resp_names) {
                array_push($usernameslist, $resp_names);
            }
        }
        $allusernames = implode(',', $usernameslist);

        $statuses = ['Draft Under Review','Not Complied','Partially Complied','Evaluation Completed','Awaiting Periodic Evaluation','Periodic Evaluation On Due'];
        $allstatuslist = implode('_', $statuses);

        $issues = Compliance::pluck('issue')->toArray();
        $issues = ComplianceLegalDocument::whereIn('id',$issues)->pluck('name')->toArray();
        $allissueslist = implode(',', $issues);

        $authorities = Compliance::pluck('authority')->toArray();
        $authorities = RegulatoryAuthority::whereIn('id',$authorities)->pluck('name')->toArray();
        $allauthoritieslist = implode(',', $authorities);
        $users = User::where('is_email_verified',true)->get();
        $models = DB::table('modenadisable')->First();
        $legalCoordinatorEmails = Legalcoordinator::pluck('email')->toArray();
        $legalCoordinators = User::whereIn('email', $legalCoordinatorEmails)->orderBy('name')->get();
        return view("user/compliance/register")->with([
            'data' => $data,
            'issues' => $issues,
            'status' => $status,
            'departments' => $departments,
            'coordinators' => $coordinators,
            'coordinatorsArray' => $coordinatorsArray,
            'coordinators_employeeids' => $coordinators_employeeids,
            'alldeptnames' => $alldeptnames,
            'allusernames' => $allusernames,
            'allstatuslist' => $allstatuslist,
            'allissueslist' => $allissueslist,
            'allauthoritieslist' => $allauthoritieslist,
            'model' => $models,
            'mr' => $mr,
            'sw' => $sw,
            'users' => $users,
            'legalCoordinators' => $legalCoordinators,
        ]);
    }
    public function getAttachment($id)
    {

        $compliance = Compliance::where('Cid', $id)->first();
        return response()->json(['data' => $compliance->file]);
    }

    public function myactions()
    {
        $data1 = [];
        $e = Auth::user()->email;
        $dep = DB::table('departments')->where('hod', $e)->pluck('id')->toArray();
        $coordinators = DB::table('legalcoordinators')->pluck('email');
        $mr = DB::table('representatives')->pluck('email');
        // foreach ($dep as $d) {
        // $respon = DB::select("select * from compliance");
        // foreach ($respon as $dat) {
        $data1 = Compliance::select(
            'compliance.Cid',
            'compliance.auth_id',
            'compliance.issue',
            'compliance.version',
            'compliance.regulation',
            'compliance.authority',
            'compliance.requirement',
            'duedate',
            'compliance.aspect',
            'owner',
            'postedby',
            'pro_status',
            'compliance.comp_status',
            'reason',
            'compliance.updatedat',
            'compliance.person_control',
            'compliance.person_monitor',
            'compliance.frequency',
            'created_at',
            'approvedby',
            'closedby'
        )
            ->whereIn('owner', $dep)
            ->whereIn('compliance.comp_status', ['Under Review', 'Partially Complied', 'Complied', 'Not Complied'])
            ->where('pro_status', '!=', 'Reverted by HOD')
            ->orderBy('compliance.created_at', 'desc')
            ->distinct()
            ->paginate(100);
        // }
        // }
        $models = DB::table('modenadisable')->First();
        return view("user/compliance/myactions")->withData1($data1)
            ->with('model', $models)
            ->withCoordinators($coordinators)
            ->withMr($mr);
    }

    public function countOfStatusQuery($status)
    {
        return Compliance::select(DB::raw('COUNT(compliance.comp_status)AS number'))->rightJoin('departments', function ($join) use ($status) {
            $join->on('compliance.owner', '=', 'departments.id')
                ->where('compliance.comp_status', $status);
        })->first();
    }
    public function getAllOfAStatus($status)
    {
        return Compliance::select('departments.departmentname', DB::raw('COUNT(compliance.comp_status)AS number'))->rightJoin('departments', function ($join) use ($status) {
            $join->on('compliance.owner', '=', 'departments.id')
                ->where('compliance.comp_status', $status);
        })->groupBy('departments.departmentname')
            ->orderBy('departments.departmentname', 'asc')
            ->get();
    }
    public function getAllNeededDepartmentStatus($status, $ids)
    {
        $items = Compliance::select('departments.departmentname', DB::raw('COUNT(compliance.comp_status)AS number'))->whereIn('departments.id', $ids)->rightJoin('departments', function ($join) use ($status) {
            $join->on('compliance.owner', '=', 'departments.id')
                ->where('compliance.comp_status', $status);
        })->groupBy('departments.departmentname')
            ->orderBy('departments.departmentname', 'asc')
            ->get();

        return $items;
    }
    public function pushToArray($num, $text = "number")
    {
        $a = [];
        foreach ($num as $key) {
            $a[] = $text == "number" ? $key->number : $key->code;
        }
        return $a;
    }
    public function getAllOfAStatusLicense($status)
    {
        return Compliance::select('departments.departmentname', DB::raw('COUNT(license_permits.status) AS number'))
                ->rightJoin('license_permits', function ($join) use ($status) {
                    $join->on('compliance.Cid', '=', 'license_permits.comp_id')
                        ->where('license_permits.status', $status)
                        ->where('license_permits.is_deleted','=','0');
                })
                ->join('departments', 'compliance.owner', '=', 'departments.id')
                ->groupBy('departments.departmentname')
                ->orderBy('departments.departmentname', 'asc')
                ->get();
    }

    public function pushToArrayLicense($licenseRecords)
    {
        $a = [];
        $departments = Departments::orderBy('departmentname', 'asc')->pluck('departmentname')->toArray();
        foreach($departments as $d) {
            $a[$d] = 0;
        }
        foreach($licenseRecords as $li){
            if($li->number>0){
                $a[$li->departmentname] = $li->number;
            }
        }
        $result = [];
        foreach($a as $value){
            $result[] = $value > 0 ? $value : 0 ;
        }
        return $result;
    }
    public function analysis()
    {
        $chart1 = new ComplianceAnalysis;
        $chart1->title("Legal Requirements");
        $chart1->labels(['Complied', 'Partially Complied', 'NotComplied', 'UnderReview']);
        $Complied = $this->countOfStatusQuery('Complied');
        $NotComplied = $this->countOfStatusQuery('Not Complied');
        $PartiallyComplied = $this->countOfStatusQuery('Partially Complied');
        $UnderReview = $this->countOfStatusQuery('Under Review');
        $AwaitingComplianceEvaluation = $this->countOfStatusQuery('Awaiting Compliance Evaluation');
        $sumOfAgreed = Compliance::select(DB::raw('COUNT(compliance.pro_status)AS number'))->rightJoin('departments', function ($join) {
            $join->on('compliance.owner', '=', 'departments.id')
                ->where('compliance.pro_status', 'Agreed by HOD');
        })->first();
        $total = $this->countOfStatusQuery('Complied');
        $all = Compliance::select(DB::raw('COUNT(compliance.comp_status)AS number'))->rightJoin('departments', function ($join) {
            $join->on('compliance.owner', '=', 'departments.id');
        })->first();
        $chart1->dataset('', 'doughnut', [$Complied->number, $PartiallyComplied->number, $NotComplied->number, $UnderReview->number])
            ->color(['#58D68D', '#2671B4', '#FC4C2B', '#032553'])
            ->backgroundcolor(['#58D68D', '#2671B4', '#FC4C2B', '#032553']);
        $chart1->displayAxes(false);

        $num = $this->getAllOfAStatus('Complied');
        $num1 = $this->getAllOfAStatus('Not Complied');
        $num2 = $this->getAllOfAStatus('Partially Complied');
        $num3 = $this->getAllOfAStatus('Under Review');
        $num_of_awaiting_compliance = $this->getAllOfAStatus('Awaiting Compliance Evaluation');
        $num4 = Compliance::select('departments.departmentname', DB::raw('COUNT(compliance.comp_status)AS number'))->rightJoin('departments', function ($join) {
            $join->on('compliance.owner', '=', 'departments.id')
                ->where('compliance.comp_status', '!=', 'Under Review');
        })->groupBy('departments.departmentname')
            ->orderBy('departments.departmentname', 'asc')
            ->get();

        $complied_collect = $this->pushToArray($num);
        $not_complied_collect = $this->pushToArray($num1);
        $partially_complied_collect = $this->pushToArray($num2);
        $under_review_collect = $this->pushToArray($num3);
        $due_collect = $this->pushToArray($num4);
        $awaiting_compliance_collect = $this->pushToArray($num_of_awaiting_compliance);

        $depatment_ids_with_at_least_one_requirement = Compliance::select('owner')
            ->where('comp_status', 'Complied')
            ->orWhere('comp_status', 'Not Complied')
            ->orWhere('comp_status', 'Partially Complied')
            ->distinct()->pluck('owner')->toArray();

        $complied_ones = $this->getAllNeededDepartmentStatus('Complied', $depatment_ids_with_at_least_one_requirement);
        $not_complied_ones = $this->getAllNeededDepartmentStatus('Not Complied', $depatment_ids_with_at_least_one_requirement);
        $partially_complied_ones = $this->getAllNeededDepartmentStatus('Partially Complied', $depatment_ids_with_at_least_one_requirement);

        $complied_ones = $this->pushToArray($complied_ones);
        $not_complied_ones = $this->pushToArray($not_complied_ones);
        $partially_complied_ones = $this->pushToArray($partially_complied_ones);

        $depatment_codes_with_at_least_one_requirement = Departments::select('code')->whereIn('id', $depatment_ids_with_at_least_one_requirement)->orderBy('departmentname', 'asc')->get();
        $dept_codes_with_at_least_one_requirement = $this->pushToArray($depatment_codes_with_at_least_one_requirement, 'code');
        $depcodes = Departments::select('code')->orderBy('departmentname', 'asc')->get();
        $depcode = $this->pushToArray($depcodes, 'code');
        $percent = $all->number ? $sumOfAgreed->number / $all->number * 100 : 0;

        $responsibilities = Compliance::select('responsibility', DB::raw('COUNT(responsibility) AS CNT'))->where('responsibility', '!=', '')->groupBy('responsibility')->get();

        $models = DB::table('modenadisable')->First();
        /*---------------------------------- License/Permits start -------------------------------------*/
        $totalLicensePermits = LicensePermit::where('is_deleted',false)->count();
        $expired = LicensePermit::where('status','Expired')->where('is_deleted',false)->count();
        $activeLicense = LicensePermit::where('status','Active')->where('is_deleted',false)->count();
        $nearToExpiryLicense = LicensePermit::where('status','Near to expiry')->where('is_deleted',false)->count();
        $activeStatus = $this->getAllOfAStatusLicense('Active');
        $nearExpiryStatus = $this->getAllOfAStatusLicense('Near to expiry');
        $expiredStatus = $this->getAllOfAStatusLicense('Expired');
        $activeStatus_collect = $this->pushToArrayLicense($activeStatus);
        $nearExpiryStatus_collect = $this->pushToArrayLicense($nearExpiryStatus);
        $expiredStatus_collect = $this->pushToArrayLicense($expiredStatus);
        $allLicensePermits = Compliance::select('departments.departmentname', DB::raw('COUNT(license_permits.id) AS number'))
                            ->rightJoin('license_permits', function ($join){
                                $join->on('compliance.Cid', '=', 'license_permits.comp_id')
                                    ->where('license_permits.is_deleted','=','0');
                            })
                            ->join('departments', 'compliance.owner', '=', 'departments.id')
                            ->groupBy('departments.departmentname')
                            ->orderBy('departments.departmentname', 'asc')
                            ->get();
        $allLicensePermits_collect = $this->pushToArrayLicense($allLicensePermits);
        /* ---------------------------------- License/permits end  ------------------------------------ */
        return view("user/compliance/analysis")->with([
            'chart1' => $chart1,
            'model' => $models,
            'total' => $total->number,
            'NotComplied' => $NotComplied->number,
            'PartiallyComplied' => $PartiallyComplied->number,
            'responsibilities' => $responsibilities,
            'UnderReview' => $UnderReview->number,
            'AwaitingComplianceEvaluation' => $AwaitingComplianceEvaluation->number,
            'totalLicensePermits' => $totalLicensePermits,
            'expired' => $expired,
            'activeLicense' => $activeLicense,
            'nearToExpiryLicense' => $nearToExpiryLicense,
            'dept_codes_with_at_least_one_requirement' => $dept_codes_with_at_least_one_requirement,
            'depcode' => collect($depcode),
            'under_review_collect' => collect($under_review_collect),
            'awaiting_compliance_collect' => collect($awaiting_compliance_collect),
            'complied_collect' => collect($complied_collect),
            'not_complied_collect' => collect($not_complied_collect),
            'partially_complied_collect' => collect($partially_complied_collect),
            'due_collect' => collect($due_collect),
            'complied_ones' => collect($complied_ones),
            'not_complied_ones' => collect($not_complied_ones),
            'partially_complied_ones' => collect($partially_complied_ones),
            'percent' => round($percent),
            'activeStatus_collect' => collect($activeStatus_collect),
            'nearExpiryStatus_collect' => collect($nearExpiryStatus_collect),
            'expiredStatus_collect' => collect($expiredStatus_collect),
            'allLicensePermits_collect' => collect($allLicensePermits_collect),
        ]);
    }
    public function addsedit()
    {
        if (isset($_GET['val'])) {
            $Cid = $_GET['val'];
            $users = User::where('approved', 1)->get();
            $data = DB::table('compliance')->where('Cid', $Cid)->get();
            $mn = DB::table('departments')->get();
            $models = DB::table('modenadisable')->First();
            return view('user/compliance/editcompliance')->with([
                'model' => $models,
                'data' => $data,
                'Cid' => $Cid,
                'mn' => $mn,
                'users' => $users,
            ]);
        }
    }
    public function editcompliance(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'owner' => 'integer',
            'responsibility' => 'required',
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        } else {
            $postedby = Auth::user()->employeeid;
            $Cid = $req->input('Cid');
            $owner = $req->input('owner');
            $owner = Departments::where('id', $owner)->value('departmentname');
            $responsibility = $req->input('responsibility');

            $updatedat = date('d-m-Y h:i:s A', time());

            $status = 'Waiting for HOD Review';
            $data = array(
                'pro_status' => $status,
                'owner' => $req->input('owner'),
                'responsibility' => $responsibility,
                'updatedat' => $updatedat,
            );

            //inserting values to activity table superadmin code
            $oldowner = DB::table('compliance')->where('Cid', $Cid)->pluck('owner');
            $oldowner = Departments::where('id', $oldowner)->value('departmentname');
            $activity = "Updated Process owner from " . $oldowner . " to " . $owner . " where Compliance Id as " . $Cid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $theTime = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
            ViewActivity::insert($datas);
            //end of superadmin code
            $c = Compliance::where('Cid', $Cid)->first();
            $oldResponsibility = $c->responsibility;
            $compliance = DB::table('compliance')
                ->where('Cid', $Cid)
                ->update($data);
            $compliance = DB::table('compliance')
                ->where('Cid', $Cid)->first();
            if ($oldResponsibility != $responsibility) {
                $mails = EmailNotification::where('module', 'compliance')
                    ->where('item', 'Compliance Change Responsibility')
                    ->where('is_blocked', false)
                    ->first();
                if($mails){
                    $emaildata = array(
                        'issue' => $compliance->issue,
                        'version' => $compliance->version,
                        'regulation' => $compliance->regulation,
                        'authority' => $compliance->authority,
                        'auth_id' => $compliance->auth_id,
                        'item' => $mails->item,
                        'email_content' => $mails->email_content,
                        'postedby' => User::where('employeeid',$compliance->postedby)->value('name'),
                        'department' => Departments::where('id',$compliance->owner)->value('departmentname'),
                    );
                    //sending mail for responsible persons
                    if($responsibility){
                        Mail::to($responsibility)->send(new ComplianceChangeResponsibility($emaildata));
                    }
                    //sending users added for email notifications in admin panel
                    $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails->id)->get();
                    if (count($compl_userdatas) > 0) {
                        $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas)>0) {
                            Mail::to($userdatas)->send(new ComplianceChangeResponsibility($emaildata));
                        }
                    }
                }else{
                    Session::flash('error', 'Mail Sending Blocked for Compliance Change Responsibility');
                }
            }
            $models = DB::table('modenadisable')->First();
            return redirect('/user/compliancemgmt')->with('model', $models);
        }
    }

    public function notcomply()
    {

        if (isset($_GET['val'])) {
            $Cid = $_GET['val'];
            $updatedat = date('d-m-Y h:i:s A', time());
            $data = array(
                'comp_status' => 'Not Complied',
                'updatedat' => $updatedat,
            );
            DB::table('compliance')->where('Cid', $Cid)->update($data);
            $coordinators = Legalcoordinator::pluck('email')->toArray();
            $c = Compliance::where('Cid', $Cid)->first();
            $mails1 = EmailNotification::where('module', 'compliance')
                    ->where('item', 'Compliance Change Status')
                    ->where('is_blocked', false)
                    ->first();
            if($mails1){
                $emaildata = array(
                    'issue' => $c->issue,
                    'auth_id' => $c->auth_id,
                    'item' => $mails1->item,
                    'email_content' => $mails1->email_content,
                    'department' => Departments::where('id',$c->owner)->value('departmentname'),
                    'status' => 'Not Complied',
                );
                //sending mail for responsible persons
                if(count($coordinators)>0){
                    Mail::to($coordinators)->send(new ComplianceChangeStatus($emaildata));
                }
                //sending users added for email notifications in admin panel
                $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails1->id)->get();
                if (count($compl_userdatas) > 0) {
                    $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas)>0) {
                        Mail::to($userdatas)->send(new ComplianceChangeStatus($emaildata));
                    }
                }
            }else{
                Session::flash('error', 'Mail Sending Blocked for Compliance Change Status');
            }
            //inserting values to activity table superadmiini code
            $activity = "Updated Compliance Status to Not Complied where Compliance Id as " . $Cid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $theTime = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
            ViewActivity::insert($datas);
            //end of superadmin code

            $data1 = DB::table('compliance')->where('Cid', $Cid)->get();
            foreach ($data1 as $dat) {
                // Sending Mail(Not fulfilled Notification) to Responsible HOD.
                $owner = DB::table('departments')->where('id', $dat->owner)->pluck('hod')->toArray();
                $mails2 = EmailNotification::where('module', 'compliance')
                    ->where('item', 'Legal Requirement Not Fulfilled')
                    ->where('is_blocked', false)
                    ->first();
                if($mails2){
                    $emaildata = array(
                        'issue' => $dat->issue,
                        'auth_id' => $dat->auth_id,
                        'item' => $mails2->item,
                        'email_content' => $mails2->email_content,
                        'department' => Departments::where('id',$dat->owner)->value('departmentname'),
                    );
                    //sending mail for responsible persons
                    if(count($owner)>0){
                        Mail::to($owner)->send(new LegalRequirementNotFulfilled($emaildata));
                    }
                    //sending users added for email notifications in admin panel
                    $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails2->id)->get();
                    if (count($compl_userdatas) > 0) {
                        $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas)>0) {
                            Mail::to($userdatas)->send(new LegalRequirementNotFulfilled($emaildata));
                        }
                    }
                }else{
                    Session::flash('error', 'Mail Sending Blocked for Legal Requirement Not Fulfilled');
                }
            }
            $models = DB::table('modenadisable')->First();
            return redirect('/user/compliancemgmt')->with('model', $models);
        }
    }
    public function partiallycomply()
    {
        if (isset($_GET['val'])) {
            $Cid = $_GET['val'];

            $updatedat = date('d-m-Y h:i:s A', time());

            $data = array(
                'comp_status' => 'Partially Complied',
                'updatedat' => $updatedat,
            );
            DB::table('compliance')->where('Cid', $Cid)->update($data);
            $coordinators = Legalcoordinator::pluck('email')->toArray();
            $c = Compliance::where('Cid', $Cid)->first();
            $mails1 = EmailNotification::where('module', 'compliance')
                    ->where('item', 'Compliance Change Status')
                    ->where('is_blocked', false)
                    ->first();
            if($mails1){
                $emaildata = array(
                    'issue' => $c->issue,
                    'auth_id' => $c->auth_id,
                    'item' => $mails1->item,
                    'email_content' => $mails1->email_content,
                    'department' => Departments::where('id',$c->owner)->value('departmentname'),
                    'status' => 'Partially-Complied',
                );
                //sending mail for responsible persons
                if(count($coordinators)>0){
                    Mail::to($coordinators)->send(new ComplianceChangeStatus($emaildata));
                }
                //sending users added for email notifications in admin panel
                $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails1->id)->get();
                if (count($compl_userdatas) > 0) {
                    $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas)>0) {
                        Mail::to($userdatas)->send(new ComplianceChangeStatus($emaildata));
                    }
                }
            }else{
                Session::flash('error', 'Mail Sending Blocked for Compliance Change Status');
            }
            //inserting values to activity table superadmin
            $activity = "Updated Compliance Status to Partially-Complied where Compliance Id as " . $Cid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $theTime = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
            ViewActivity::insert($datas);
            //end of superadmin code

            $models = DB::table('modenadisable')->First();
            return redirect('/user/compliancemgmt')->with('model', $models);
        }
    }
    public function underreview()
    {
        if (isset($_GET['val'])) {
            $Cid = $_GET['val'];
            $updatedat = date('d-m-Y h:i:s A', time());
            $data = array(
                'comp_status' => 'Under Review',
                'updatedat' => $updatedat,
            );
            DB::table('compliance')->where('Cid', $Cid)->update($data);
            $coordinators = Legalcoordinator::pluck('email')->toArray();
            $c = Compliance::where('Cid', $Cid)->first();
            $maildata = array(
                'Cid' => $Cid,
                'issue' => $c->issue,
                'owner' => $c->dep ? $c->dep->departmentname : '-',
                'duedate' => $c->duedate,
            );
            $mails1 = EmailNotification::where('module', 'compliance')
                    ->where('item', 'Compliance Change Status')
                    ->where('is_blocked', false)
                    ->first();
            if($mails1){
                $emaildata = array(
                    'issue' => $c->issue,
                    'auth_id' => $c->auth_id,
                    'item' => $mails1->item,
                    'email_content' => $mails1->email_content,
                    'department' => Departments::where('id',$c->owner)->value('departmentname'),
                    'status' => 'Draft Under Review',
                );
                //sending mail for responsible persons
                if(count($coordinators)>0){
                    Mail::to($coordinators)->send(new ComplianceChangeStatus($emaildata));
                }
                //sending users added for email notifications in admin panel
                $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails1->id)->get();
                if (count($compl_userdatas) > 0) {
                    $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas)>0) {
                        Mail::to($userdatas)->send(new ComplianceChangeStatus($emaildata));
                    }
                }
            }else{
                Session::flash('error', 'Mail Sending Blocked for Compliance Change Status');
            }

            //inserting values to activity table superadmin
            $activity = "Updated Compliance Status to Under Review where Compliance Id as " . $Cid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $theTime = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
            ViewActivity::insert($datas);
            //end of superadmin code
            $models = DB::table('modenadisable')->First();
            return redirect('/user/compliancemgmt')->with('model', $models);
        }
    }

    public function complied()
    {
        if (isset($_GET['val'])) {
            $closedby = Auth::user()->employeeid;
            $Cid = $_GET['val'];

            $updatedat = date('d-m-Y h:i:s A', time());

            $data = array(
                'comp_status' => 'Complied',
                'closedby' => $closedby,
                'updatedat' => $updatedat,
            );
            DB::table('compliance')->where('Cid', $Cid)->update($data);
            $coordinators = Legalcoordinator::pluck('email')->toArray();
            $c = Compliance::where('Cid', $Cid)->first();
            $mails1 = EmailNotification::where('module', 'compliance')
                    ->where('item', 'Compliance Change Status')
                    ->where('is_blocked', false)
                    ->first();
            if($mails1){
                $emaildata = array(
                    'issue' => $c->issue,
                    'auth_id' => $c->auth_id,
                    'item' => $mails1->item,
                    'email_content' => $mails1->email_content,
                    'department' => Departments::where('id',$c->owner)->value('departmentname'),
                    'status' => 'Evaluation Completed',
                );
                //sending mail for responsible persons
                if(count($coordinators)>0){
                    Mail::to($coordinators)->send(new ComplianceChangeStatus($emaildata));
                }
                //sending users added for email notifications in admin panel
                $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails1->id)->get();
                if (count($compl_userdatas) > 0) {
                    $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas)>0) {
                        Mail::to($userdatas)->send(new ComplianceChangeStatus($emaildata));
                    }
                }
            }else{
                Session::flash('error', 'Mail Sending Blocked for Compliance Change Status');
            }
            //inserting values to activity table
            $activity = "Updated Compliance Status to Complied where Compliance Id as " . $Cid;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $theTime = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
            ViewActivity::insert($datas);
            $models = DB::table('modenadisable')->First();
            return redirect('/user/compliancemgmt')->with('model', $models);
        }
    }
    public function hide()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = array('Hidden' => '1');
            DB::table('compliance')
                ->where('Cid', $id)
                ->update($data);
            //inserting values to activity table superadmi code
            $activity = "Legle Requirement was made Hidden where Compliance id " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $theTime = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
            ViewActivity::insert($datas);
            //end of superadmin code
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }
    public function unhide()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $data = array('Hidden' => '0');
            DB::table('compliance')
                ->where('Cid', $id)
                ->update($data);
            //inserting values to activity table superadmin code
            $activity = "Legle Requirement was Made Visible where Compliance id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $theTime = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
            ViewActivity::insert($datas);
            //end of superadmin codes
            $models = DB::table('modenadisable')->First();
            return redirect()->back()->with('model', $models);
        }
    }
    public function choosevisibility()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $visible = DB::table('users')->where('is_email_verified',true)->get();
            $models = DB::table('modenadisable')->First();
            return view("user/compliance/visibility")->withVisible($visible)->withId($id)->with('model', $models);
        }
    }

    public function visible(Request $req)
    {
        $Cid = $req->input('id');
        $visible = $req->input('visible');
        foreach ($visible as $vis) {
            $v[] = $vis;
        }
        $visible = implode(',', $v);

        //inserting values to activity table superadmin code
        $activity = "Legle Requirement was Made visible to " . $visible . " where Compliance id is " . $Cid;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $theTime = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
        ViewActivity::insert($datas);
        //end of superadmin codes
        DB::table('compliance')->where('Cid', $Cid)
            ->update(array('visibleto' => $visible,'Hidden'=> true));
        $models = DB::table('modenadisable')->First();
        return redirect("user/compliancemgmt")->withVisible($visible)->with('model', $models);
    }

    public function entryreview(int $id)
    {
        $departments = Departments::all();
        $users = User::where('approved', true)->get();
        $data = Compliance::where('Cid', '=', $id)->firstOrFail();
        $models = ModenaDisable::first();

        return view('user.compliance.entryreview')->with([
            'data' => $data,
            'departments' => $departments,
            'users' => $users,
            'id' => $id,
            'auth_id' => $data->auth_id,
            'model' => $models,
        ]);
    }
    public function reviewComplianceStatus(int $id)
    {
        $currentUser = User::where('email',Auth::user()->email)->first();
        $coordinators = Legalcoordinator::pluck('email')->toArray();
        $compliance = Compliance::with(['regulatory','legalDocuments','legalCoordinator'])->where('Cid', $id)->firstOrFail();
        if($compliance->legal_coordinator != $currentUser->id){
            Session::flash('error', 'You are not authorized to access that page.');
            return redirect('/user/compliancemgmt');
        }
        $isLegalCoordinator = $compliance->legalCoordinator && Auth::user()->email === $compliance->legalCoordinator->email;
        $isCoordinator = in_array(Auth::user()->email, $coordinators);
        if (!$isLegalCoordinator && !$isCoordinator) {
            Session::flash('error', 'You are not authorized to access that page');
            return redirect('/user/compliancemgmt');
        }
        $auth_id = $compliance->auth_id;
        $models = ModenaDisable::first();
        $users = DB::table('users')->get();
        $departments = Departments::all();
        $data = Compliance::with(['regulatory','legalDocuments'])->where('Cid', '=', $id)->firstOrFail();

        return view('user.compliance.review_compliance_status')->with([
            'id' => $id,
            'auth_id' => $auth_id,
            'compliance' => $compliance,
            'departments' => $departments,
            'users' => $users,
            'data' => $data,
            'model' => $models,
        ]);

    }
    public function addReviewComplianceStatus(AddReviewComplianceStatusRequest $request)
    {
        // dd($request->all());
        $coordinators = Legalcoordinator::pluck('email')->toArray();
        if(!in_array(Auth::user()->email, $coordinators)){
            Session::flash('error', 'You are not authorized to access that page');
            return redirect('/user/compliancemgmt');
        }
        $today = date('Y-m-d');
        $data = $request->validated();
        $status1 = null;
        $now = now();
        switch ($data['status']) {
            case 'complied':
                $status1 = 'Complied';
                break;
            case 'not_complied':
                $status1 = 'Not Complied';
                break;
            case 'partially_complied':
                $status1 = 'Partially Complied';
                break;
        }
        $other_reg = $request->input('other_reg') ?? null;
        $other_doc = $request->input('other_doc') ?? null;
        $legalDocument = $request->input('issue') ?? null;
        $authority = $request->input('authority') ?? null;
        $exists_reg = $request->input('exists_reg') ?? null;
        $exists_doc = $request->input('exists_doc') ?? null;
        $compliance = Compliance::with(['regulatory','legalDocuments'])->where('Cid', $data['id'])->first();
        $currentUser = User::where('email',Auth::user()->email)->first();
        $isCoordinator = Legalcoordinator::where('email',$currentUser->email)->exists();
        $isAdmin = Admin::where('email',$currentUser->email)->exists();
        $admins = Admin::all();
        $status = $isCoordinator || $isAdmin ? 'Approved' : 'Pending Review';
        $token = Str::random(40);
        $expires = now()->addDays(365);
        $owner = $compliance->owner;
        $departmentName = Departments::where('id', $owner)->value('departmentname');
        // dd($authority, $legalDocument, $other_reg, $other_doc, $exists_reg, $exists_doc);
        if($authority=='0'){
            if($exists_reg==null){
                $regulatory = RegulatoryAuthority::create([
                    'name' => $other_reg,
                    'status' => $status,
                    'no_of_documents' => 1,
                    'initial_name' => $other_reg,
                    'created_by' =>  $currentUser->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'token' => $token,
                ]);
                $document = ComplianceLegalDocument::create([
                    'name' => $other_doc,
                    'status' => $status,
                    'created_by' =>  $currentUser->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'RAC_id' => $regulatory->id,
                    'token' => $token
                ]);
            }else{
                $regulatory = RegulatoryAuthority::findOrFail($exists_reg);
                $document = ComplianceLegalDocument::findOrFail($exists_doc);
                $regulatory->update([
                    'name' => $other_reg,
                    'updated_at' => now(),
                    'modified_by' => $currentUser->id,
                    'modified_at' => now(),
                    'status' => $status,
                ]);
                $document->update([
                    'name' => $other_doc,
                    'updated_at' => now(),
                    'modified_by' => $currentUser->id,
                    'modified_at' => now(),
                    'status' => $status,
                ]);
            }
            $authority = $regulatory->id;
            $legalDocument = $document->id;
            //Email for new regulatory added for the Admin
            // if(!($isCoordinator || $isAdmin)){
            //     if($exists_reg==null){
            //         $emailNotification = EmailNotification::where('module', 'compliance')
            //             ->where('item', 'Approval Required: New Regulatory Authority Added')
            //             ->where('is_blocked', false)
            //             ->first();
            //         if($emailNotification){
            //             $emailData = [
            //                 'type' => 'Regulatory Authority',
            //                 'name' => $other_reg,
            //                 'doc_name' => $other_doc,
            //                 'submitted_by' => $currentUser->name,
            //                 'department' => $departmentName,
            //                 'date' => now(),
            //                 'status' => $status,
            //                 'item' => $emailNotification->item,
            //                 'email_content' => $emailNotification->email_content,
            //                 'reg_id' => $authority,
            //             ];
            //             //sending mail for responsible persons
            //             if(count($admins)>0){
            //                 foreach ($admins as $admin) {
            //                     $approvalLink = URL::temporarySignedRoute(
            //                         'regulatoryapproval.process',
            //                         $expires,
            //                         ['id' => $authority, 'action' => 'approve', 'token' => $token, 'admin_id' => $admin->id]
            //                     );
            //                     $rejectLink = URL::temporarySignedRoute(
            //                         'regulatoryapproval.process',
            //                         $expires,
            //                         ['id' => $authority, 'action' => 'reject', 'token' => $token, 'admin_id' => $admin->id]
            //                     );
            //                     $emailData['approval_link'] = $approvalLink;
            //                     $emailData['reject_link'] = $rejectLink;
            //                     $emailData['admin_name'] = $admin->name;
            //                     try {
            //                         Mail::to($admin->email)->send(new NewRegulatoryAuthorityAdded($emailData));
            //                     } catch (\Exception $e) {
            //                         Log::error('Failed to send NewLegalDocumentAdded email to '.$admin->email.': '.$e->getMessage());
            //                         Session::flash('error', 'Mail sending failed for New Legal Document Added to '.$admin->email);
            //                     }
            //                 }
            //             }
            //             //sending users added for email notifications in admin panel
            //             $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
            //             if (count($compl_userdatas) > 0) {
            //                 $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
            //                 $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
            //                 if (count($userdatas)>0) {
            //                     try {
            //                         Mail::to($userdatas)->send(new NewRegulatoryAuthorityAdded($emailData));
            //                     } catch (\Exception $e) {
            //                         Log::error('Failed to send NewRegulatoryAuthorityAdded email to '.$admin->email.': '.$e->getMessage());
            //                         Session::flash('error', 'Mail sending failed for New Regulatory Authority Added to '.implode($userdatas));
            //                     }
            //                 }
            //             }
            //         }else{
            //             Session::flash('error', 'Mail Sending Blocked for Approval Required: New Regulatory Authority Added Email');
            //         }
            //     }
            // }
        }
        if($legalDocument=='other'){
            $reg = RegulatoryAuthority::findOrFail($authority);
            if($exists_doc==null){
                $document = ComplianceLegalDocument::create([
                    'name' => $other_doc,
                    'status' => $status,
                    'created_by' =>  $currentUser->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'RAC_id' => $authority,
                    'token' => $token
                ]);
                $reg->update([
                    'no_of_documents' => $reg->no_of_documents + 1,
                    'updated_at' => now(),
                    'status' => $status,
                ]);
            }else{
                $document = ComplianceLegalDocument::findOrFail($exists_doc);
                $document->update([
                    'name' => $other_doc,
                    'updated_at' => now(),
                    'modified_by' => $currentUser->id,
                    'modified_at' => now(),
                    'status' => $status,
                ]);
            }
            $legalDocument = $document->id;
            //Email for new legal document added for the Admin
            // if(!($isCoordinator || $isAdmin || $exists_doc==null)){
            //     if($exists_doc==null){

            //         $emailNotification = EmailNotification::where('module', 'compliance')
            //             ->where('item', 'Approval Required: New Legal Document Added')
            //             ->where('is_blocked', false)
            //             ->first();
            //         if($emailNotification){
            //             $emailData = [
            //                 'type' => 'Legal Document',
            //                 'name' => $reg->name,
            //                 'doc_name' => $other_doc,
            //                 'submitted_by' => $currentUser->name,
            //                 'department' => $departmentName,
            //                 'date' => now(),
            //                 'status' => $status,
            //                 'item' => $emailNotification->item,
            //                 'email_content' => $emailNotification->email_content,
            //                 'doc_id' => $legalDocument,
            //                 'token' => $token
            //             ];
            //             //sending mail for responsible persons
            //             if(count($admins)>0){
            //                 foreach ($admins as $admin) {
            //                     $approvalLink = URL::temporarySignedRoute(
            //                         'legaldocument.approval.process',
            //                         $expires,
            //                         ['id' => $legalDocument, 'action' => 'approve', 'token' => $token, 'admin_id' => $admin->id]
            //                     );
            //                     $rejectLink = URL::temporarySignedRoute(
            //                         'legaldocument.approval.process',
            //                         $expires,
            //                         ['id' => $legalDocument, 'action' => 'reject', 'token' => $token, 'admin_id' => $admin->id]
            //                     );
            //                     $emailData['approval_link'] = $approvalLink;
            //                     $emailData['reject_link'] = $rejectLink;
            //                     $emailData['admin_name'] = $admin->name;
            //                     try {
            //                         Mail::to($admin->email)->send(new NewLegalDocumentAdded($emailData));
            //                     } catch (\Exception $e) {
            //                         Log::error('Failed to send NewLegalDocumentAdded email to '.$admin->email.': '.$e->getMessage());
            //                         Session::flash('error', 'Mail sending failed for New Legal Document Added to '.$admin->email);
            //                     }
            //                 }
            //             }
            //             //sending users added for email notifications in admin panel
            //             $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
            //             if (count($compl_userdatas) > 0) {
            //                 $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
            //                 $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
            //                 if (count($userdatas)>0) {
            //                     try {
            //                         Mail::to($userdatas)->send(new NewLegalDocumentAdded($emailData));
            //                     } catch (\Exception $e) {
            //                         Log::error('Failed to send NewLegalDocumentAdded email to '.$admin->email.': '.$e->getMessage());
            //                         Session::flash('error', 'Mail sending failed for New Legal Document Added to '.implode($userdatas));
            //                     }
            //                 }
            //             }
            //         }else{
            //             Session::flash('error', 'Mail Sending Blocked for Approval Required: New Legal Document Added');
            //         }
            //     }
            // }
        }
        $insertData = [
            'comp_status' => $status1,
            'review_evaluation_date' => $now,
            'reviewed_evaluation_by' => Auth::user()->employeeid,
            'frequency' => $data['frequency'],
            'authority' => $authority,
            'issue' => $legalDocument,
            'version' => $data['version'],
            'regulation' => $data['regulation'],
            'owner' => $data['owner'],
            'aspect' => $data['aspect'],
            'requirement' => $data['requirement'],
            'responsibility' => $data['responsibility'],
        ];
        // dd($insertData,$request->all(),$authority,$legalDocument);
        if($compliance->comp_status=='Under Review' || $compliance->comp_status=='Need improvement'){
            $insertData['draft_reviewed_by'] = $currentUser->id;
            $insertData['draft_reviewed_at'] = $now;
        }
        $histories = CompliancePeriodicEvaluationHistory::where('compliance_id',$data['id'])->pluck('periodic_evaluation_at')->toArray();
        if($compliance->review_evaluation_date){
            if(!in_array($compliance->review_evaluation_date, $histories)){
                $addOn = new CompliancePeriodicEvaluationHistory();
                $addOn->old_status = 'Under Review';
                if($compliance->comp_status=='Awaiting Periodic Evaluation' || $compliance->comp_status=='Periodic Evaluation On Due'){
                    $addOn->new_status = 'Complied';
                }else{
                    $addOn->new_status = $compliance->comp_status;
                }
                $addOn->periodic_evaluation_at = $compliance->review_evaluation_date;
                $addOn->periodic_evaluation_by = User::where('employeeid',$compliance->reviewed_evaluation_by)->value('id')??'--';
                $addOn->compliance_id = $data['id'];
                $addOn->save();
            }
        }
        CompliancePeriodicEvaluationHistory::create([
            'compliance_id' => $data['id'],
            'new_status' => $status,
            'old_status' => $compliance->comp_status,
            'periodic_evaluation_by' => $currentUser->id,
            'periodic_evaluation_at' => $now,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        Compliance::where('Cid', $data['id'])->update($insertData);
        if($status1=='Not Complied' || $status1=='Partially Complied'){
            $comp_record = Compliance::where('Cid', $data['id'])->first();
            if ($comp_record) {
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
                $dataforcorr = [
                    'issueid' => $issueId,
                    'title' => $comp_record->regulation,
                    'postedby' => $comp_record->postedby,
                    'category' => 'Legal Requirement',
                    'department' => '0',
                    'correction_department' => $comp_record->owner,
                    'observation' => $comp_record->requirement,
                    'correction_responsibility' => $comp_record->responsibility,
                    'processstage' => 'Entry Under Review',
                    'status' => 'open',
                    'approvement' => 'no',
                    'created_at' => now(),
                    'postdate' => now(),
                    'type' => '-',
                    'count' => '1',
                    'file' => 'NULL',
                ];
                $corr = Corrective::create($dataforcorr);
                $corr_id_comp = null;
                if(!$comp_record->corrective_issueid){
                    $corr_id_comp = $corr->id;
                }else{
                    $c_ids = explode(',',$comp_record->corrective_issueid);
                    $c_ids[] = $corr->id;
                    $corr_id_comp = implode(',',$c_ids);
                }
                Compliance::where('Cid', $data['id'])->update(['corrective_issueid'=>$corr_id_comp]);
                // Sending Mail(AddIssue Notification) To Other HOD
                $corrective_add_emails = EmailNotification::where('module', 'correctiveaction')
                    ->where('item', 'Corrective Issue Added')
                    ->where('is_blocked', false)
                    ->first();
                if ($corrective_add_emails) {
                    $notimaildata = array(
                        'name' => $comp_record->postedby ? User::where('employeeid',$comp_record->postedby)->value('name') : '',
                        'issueid' => $issueId,
                        'email_content' => $corrective_add_emails->email_content,
                        'item' => $corrective_add_emails->item,
                        'department' => Departments::where('id',$comp_record->owner)->value('departmentname'),
                    );
                    //sending mails to added person and responsible person
                    if($comp_record->responsibility){
                        Mail::to($comp_record->responsibility)->send(new CorrectiveAddIssue($notimaildata));
                    }
                    if($comp_record->postedby){
                        $emailid = User::where('id',$comp_record->postedby)->value('email');
                        if($emailid){
                            Mail::to($emailid)->send(new CorrectiveAddIssue($notimaildata));
                        }
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
        }
        //Inserting data to Activity table superadmin code
        $activity = "Legal Requirement's status  Reviewed by legalcoordinator  where Compliance id is " . $data['id'];
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $theTime = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
        ViewActivity::insert($datas);
        //end ofsuperadmin code
        Session::flash('msg', "Legal Requirement's status with Id #" . $data['auth_id'] . " has been Reviewed Successfully");
        $models = DB::table('modenadisable')->first();

        return redirect('/user/compliancemgmt')->with('model', $models);

    }

    public function resubmit(int $id)
    {
        $departments = Departments::all();
        $users = User::where('approved', true)->get();
        $data = Compliance::with(['regulatory','legalDocuments'])->where('Cid', '=', $id)->firstOrFail();
        $models = ModenaDisable::first();
        $legalCoordinatorEmails = Legalcoordinator::pluck('email')->toArray();
        $legalCoordinators = User::whereIn('email', $legalCoordinatorEmails)->orderBy('name')->get();
        return view('user.compliance.resubmit',compact('legalCoordinators'))->with([
            'data' => $data,
            'departments' => $departments,
            'users' => $users,
            'id' => $id,
            'auth_id' => $data->auth_id,
            'model' => $models,
        ]);

    }

    public function addResubmit(ResubmitRequest $req)
    {
        $formData = $req->validated();
        $cer = 'NULL';
        if ($req->hasFile('file')) {
            $file = $req->file('file');
            $file_size = $file->getClientOriginalName();
            $new_file_name = $file_size;
            $destination_path = public_path('storage/compliance_files');
            $file->move($destination_path, $new_file_name);
            $file = $new_file_name;
            $cer = $file;
        }
        $version = $formData['version'];
        $regulation = $formData['regulation'];
        $responsibility = $formData['responsibility'];
        $id = $formData['id'];
        // Getting other regulatory and document values
        $other_reg = $req->input('other_reg') ?? null;
        $other_doc = $req->input('other_doc') ?? null;
        $legalDocument = $req->input('issue') ?? null;
        $authority = $req->input('authority') ?? null;
        $exists_reg = $req->input('exists_reg') ?? null;
        $exists_doc = $req->input('exists_doc') ?? null;
        // End of getting other regulatory and document values

        $exist = DB::table('compliance')->where('Cid', $id)->orderBy('Cid', 'desc')->first();
        if ($exist) {
            $c = $exist->count;
            $count = $c + 1;
        } else {
            $count = "1";
        }
        $auth_id = $exist->auth_id;
        $requirement = $formData['requirement'];
        $aspect = $formData['aspect'];
        $owner = $formData['owner'];
        $selectedCoordinator = $formData['legalCoordinator'];
        $coordinator = User::where('id', $selectedCoordinator)->first();
        $departmentName = Departments::where('id', $owner)->value('departmentname');
        $frequency = $formData['frequency'];
        $d = date('d-m-Y h:i:s A', time());
        $currentUser = User::where('email',Auth::user()->email)->first();
        $isCoordinator = Legalcoordinator::where('email',$currentUser->email)->exists();
        $isAdmin = Admin::where('email',$currentUser->email)->exists();
        $admins = Admin::all();
        $status = $isCoordinator || $isAdmin ? 'Approved' : 'Pending Review';
        $token = Str::random(40);
        $expires = now()->addDays(365);
        if($authority=='0'){
            if($exists_reg==null){
                $regulatory = RegulatoryAuthority::create([
                    'name' => $other_reg,
                    'status' => $status,
                    'no_of_documents' => 1,
                    'initial_name' => $other_reg,
                    'created_by' =>  $currentUser->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'token' => $token,
                ]);
                $document = ComplianceLegalDocument::create([
                    'name' => $other_doc,
                    'status' => $status,
                    'created_by' =>  $currentUser->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'RAC_id' => $regulatory->id,
                    'token' => $token
                ]);
            }else{
                $regulatory = RegulatoryAuthority::findOrFail($exists_reg);
                $document = ComplianceLegalDocument::findOrFail($exists_doc);
                $regulatory->update([
                    'name' => $other_reg,
                    'updated_at' => now(),
                    'modified_by' => $currentUser->id,
                    'modified_at' => now(),
                    'status' => $status,
                ]);
                $document->update([
                    'name' => $other_doc,
                    'updated_at' => now(),
                    'modified_by' => $currentUser->id,
                    'modified_at' => now(),
                    'status' => $status,
                ]);
            }
            $authority = $regulatory->id;
            $legalDocument = $document->id;
            //Email for new regulatory added for the Admin
            if(!($isCoordinator || $isAdmin)){
                if($exists_reg==null){
                    $emailNotification = EmailNotification::where('module', 'compliance')
                        ->where('item', 'Approval Required: New Regulatory Authority Added')
                        ->where('is_blocked', false)
                        ->first();
                    if($emailNotification){
                        $emailData = [
                            'type' => 'Regulatory Authority',
                            'name' => $other_reg,
                            'doc_name' => $other_doc,
                            'submitted_by' => $currentUser->name,
                            'department' => $departmentName,
                            'date' => now(),
                            'status' => $status,
                            'item' => $emailNotification->item,
                            'email_content' => $emailNotification->email_content,
                            'reg_id' => $authority,
                        ];
                        //sending mail for responsible persons
                        if($coordinator){
                            // $approvalLink = URL::temporarySignedRoute(
                            //     'regulatoryapproval.process',
                            //     $expires,
                            //     ['id' => $authority, 'action' => 'approve', 'token' => $token, 'admin_id' => $coordinator->id]
                            // );
                            // $rejectLink = URL::temporarySignedRoute(
                            //     'regulatoryapproval.process',
                            //     $expires,
                            //     ['id' => $authority, 'action' => 'reject', 'token' => $token, 'admin_id' => $coordinator->id]
                            // );
                            $approvalLink = url("/compliance/review-regulatory/$authority/approve/$token/$coordinator->id");
                            $rejectLink = url("/compliance/review-regulatory/$authority/reject/$token/$coordinator->id");
                            $emailData['approval_link'] = $approvalLink;
                            $emailData['reject_link'] = $rejectLink;
                            $emailData['admin_name'] = $coordinator->name;
                            try {
                                Mail::to($coordinator->email)->send(new NewRegulatoryAuthorityAdded($emailData));
                            } catch (\Exception $e) {
                                Log::error('Failed to send NewLegalDocumentAdded email to '.$coordinator->email.': '.$e->getMessage());
                                Session::flash('error', 'Mail sending failed for New Legal Document Added to '.$coordinator->email);
                            }
                        }
                        //sending users added for email notifications in admin panel
                        $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                        if (count($compl_userdatas) > 0) {
                            $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas)>0) {
                                try {
                                    Mail::to($userdatas)->send(new NewRegulatoryAuthorityAdded($emailData));
                                } catch (\Exception $e) {
                                    Log::error('Failed to send NewRegulatoryAuthorityAdded email to '.implode($userdatas).': '.$e->getMessage());
                                    Session::flash('error', 'Mail sending failed for New Regulatory Authority Added to '.implode($userdatas));
                                }
                            }
                        }
                    }else{
                        Session::flash('error', 'Mail Sending Blocked for Approval Required: New Regulatory Authority Added Email');
                    }
                }
            }
        }
        if($legalDocument=='other'){
            $reg = RegulatoryAuthority::findOrFail($authority);
            if($exists_doc==null){
                $document = ComplianceLegalDocument::create([
                    'name' => $other_doc,
                    'status' => $status,
                    'created_by' =>  $currentUser->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'RAC_id' => $authority,
                    'token' => $token
                ]);
                $reg->update([
                    'no_of_documents' => $reg->no_of_documents + 1,
                    'updated_at' => now(),
                    'status' => $status,
                ]);
            }else{
                $document = ComplianceLegalDocument::findOrFail($exists_doc);
                $document->update([
                    'name' => $other_doc,
                    'updated_at' => now(),
                    'modified_by' => $currentUser->id,
                    'modified_at' => now(),
                    'status' => $status,
                ]);
            }
            $legalDocument = $document->id;
            //Email for new legal document added for the Admin
            if(!($isCoordinator || $isAdmin)){
                if($exists_doc==null){
                    $emailNotification = EmailNotification::where('module', 'compliance')
                        ->where('item', 'Approval Required: New Legal Document Added')
                        ->where('is_blocked', false)
                        ->first();
                    if($emailNotification){
                        $emailData = [
                            'type' => 'Legal Document',
                            'name' => $reg->name,
                            'doc_name' => $other_doc,
                            'submitted_by' => $currentUser->name,
                            'department' => $departmentName,
                            'date' => now(),
                            'status' => $status,
                            'item' => $emailNotification->item,
                            'email_content' => $emailNotification->email_content,
                            'doc_id' => $legalDocument,
                            'token' => $token
                        ];
                        //sending mail for responsible persons
                        if($coordinator){
                            // $approvalLink = URL::temporarySignedRoute(
                            //     'legaldocument.approval.process',
                            //     $expires,
                            //     ['id' => $legalDocument, 'action' => 'approve', 'token' => $token, 'admin_id' => $coordinator->id]
                            // );
                            // $rejectLink = URL::temporarySignedRoute(
                            //     'legaldocument.approval.process',
                            //     $expires,
                            //     ['id' => $legalDocument, 'action' => 'reject', 'token' => $token, 'admin_id' => $coordinator->id]
                            // );
                            $approvalLink = url("/compliance/review-legal-document/$legalDocument/approve/$token/$coordinator->id");
                            $rejectLink = url("/compliance/review-legal-document/$legalDocument/reject/$token/$coordinator->id");
                            $emailData['approval_link'] = $approvalLink;
                            $emailData['reject_link'] = $rejectLink;
                            $emailData['admin_name'] = $coordinator->name;
                            try {
                                Mail::to($coordinator->email)->send(new NewLegalDocumentAdded($emailData));
                            } catch (\Exception $e) {
                                Log::error('Failed to send NewLegalDocumentAdded email to '.$coordinator->email.': '.$e->getMessage());
                                Session::flash('error', 'Mail sending failed for New Legal Document Added to '.$coordinator->email);
                            }
                        }
                        //sending users added for email notifications in admin panel
                        $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                        if (count($compl_userdatas) > 0) {
                            $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                            $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                            if (count($userdatas)>0) {
                                try {
                                    Mail::to($userdatas)->send(new NewLegalDocumentAdded($emailData));
                                } catch (\Exception $e) {
                                    Log::error('Failed to send NewLegalDocumentAdded email to '.implode($userdatas).': '.$e->getMessage());
                                    Session::flash('error', 'Mail sending failed for New Legal Document Added to '.implode($userdatas));
                                }
                            }
                        }
                    }else{
                        Session::flash('error', 'Mail Sending Blocked for Approval Required: New Legal Document Added');
                    }
                }
            }
        }
        $ID = Auth::user()->employeeid;
        $data = array(
            'created_at' => $d,
            'count' => $count,
            'issue' => $legalDocument,
            'version' => $version,
            'regulation' => $regulation,
            'authority' => $authority,
            'requirement' => $requirement,
            'aspect' => $aspect,
            'owner' => $owner,
            'postedby' => $ID,
            'frequency' => $frequency,
            'comp_status' => 'Under Review',
            'responsibility' => $responsibility,
            'file' => $cer,
            'legal_coordinator' => $selectedCoordinator,
            'freq_review' => $d,
            'email_resubmit_send' => 0,
        );
        DB::table('compliance')->where('Cid', $id)->update($data);
        //inserting values to activity table  superadmin codes
        $activity = "Legal requirement with id " . $id . " with Requirement " . $requirement . " and with aspect " . $aspect . " with process owner as " . $owner . " is resubmitted.";
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
        ViewActivity::insert($datas);
        //superadmin code ends
        Session::flash('msg', "Legal requirement resubmitted Successfully");
        Session::flash('success_email_resubmit','Email notifications have been sent regarding the resubmission.');
        Session::flash('Cid',$id);
        $models = DB::table('modenadisable')->first();
        return redirect('/user/compliancemgmt')->with('model', $models);
    }

    public function review()
    {
        if (isset($_GET['val'])) {

            $approvedby = Auth::user()->employeeid;
            $id = $_GET['val'];
            $users = DB::table('users')->get();
            $departments = Departments::all();
            $data = Compliance::where('Cid', '=', $id)->firstOrFail();
            $models = DB::table('modenadisable')->first();

            return view('user.compliance.review')->with([
                'model' => $models,
                'data' => $data,
                'users' => $users,
                'departments' => $departments,
                'id' => $id,
                'auth_id' => $data->auth_id,
                'approvedby' => $approvedby,
            ]);
        }
    }
    //Evaluation Compliance
    public function addreview(ReviewRequest $request)
    {
        $data = $request->validated();
        $auth_id = $request->input('auth_id');
        $status = null;

        switch ($data['status']) {
            case 'complied_under_review':
                $status = 'Complied, Under Review';
                break;
            case 'not_complied_under_review':
                $status = 'Not Complied, Under Review';
                break;
            case 'partially_complied_under_review':
                $status = 'Partially Complied, Under Review';
                break;
        }

        Compliance::where('Cid', $data['id'])->update([
            'frequency' => $data['frequency'],
            'authority' => $data['authority'],
            'issue' => $data['issue'],
            'version' => $data['version'],
            'regulation' => $data['regulation'],
            'owner' => $data['owner'],
            'aspect' => $data['aspect'],
            'requirement' => $data['requirement'],
            'evaluation_date' => date('Y-m-d'),
            'reviewed_by' => \Illuminate\Support\Facades\Auth::user()->employeeid,
            //'person_control' => $person_control,
            'responsibility' => $data['responsibility'],
            'comp_status' => $status,
        ]);

        //Inserting data to Activity table superadmin code
        $activity = "Legal Requirement  Reviewed  where Compliance id is " . $data['id'];
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $theTime = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
        ViewActivity::insert($datas);
        //end ofsuperadmin code
        Session::flash('msg', "Legal Requirement with Id #" . $auth_id . " has been Reviewed Successfully");
        $models = DB::table('modenadisable')->first();

        return redirect('/user/compliancemgmt')->with('model', $models);

    }

    public function search(Request $req)
    {
        $mr = DB::table('representatives')->pluck('email');
        $keyword = $req->input('keyword');
        $data = [];
        $data = DB::table('compliance')
            ->select(
                'compliance.Hidden',
                'compliance.Cid',
                'compliance.auth_id',
                'compliance.issue',
                'compliance.version',
                'compliance.regulation',
                'compliance.authority',
                'compliance.requirement',
                'duedate',
                'compliance.aspect',
                'compliance.comp_status',
                'compliance.postedby',
                'compliance.updatedat',
                'compliance.pro_status',
                'compliance.owner',
                'compliance.reason',
                'compliance.person_control',
                'compliance.person_monitor',
                'compliance.frequency',
                'created_at',
                'approvedby',
                'closedby',
                'freq_review'
            )
            ->wherein('compliance.comp_status', ['Under Review', 'Partially Complied', 'Complied', 'Not Complied'])
            ->where(function ($query) use ($req) {
                $query

                    ->where('auth_id', '=', $req->input('keyword'))
                    ->orwhere('comp_status', '=', $req->input('keyword'))
                    ->orwhere('pro_status', '=', $req->input('keyword'))
                    ->orwhere('frequency', '=', $req->input('keyword'))
                    ->orwhere('reason', '=', $req->input('keyword'))
                    ->orwhere('remarks', '=', $req->input('keyword'))
                    ->orwhere('authority', '=', $req->input('keyword'))
                    ->orwhere('issue', '=', $req->input('keyword'))
                    ->orwhere('version', '=', $req->input('keyword'))
                    ->orWhere('regulation', '=', $req->input('keyword'))
                    ->orwhere('requirement', '=', $req->input('keyword'))
                    ->orWhere('department', '=', $req->input('keyword'))
                    ->orWhere('owner', '=', $req->input('keyword'))
                    ->orWhere('duedate', '=', $req->input('keyword'))
                    ->orWhere('postedby', '=', $req->input('keyword'))
                    ->orWhere('person_control', '=', $req->input('keyword'))
                    ->orWhere('person_monitor', '=', $req->input('keyword'))
                    ->orWhere('aspect', '=', $req->input('keyword'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(100)
            ->setpath('');

        $departments = DB::table('departments')->get();
        $issues = DB::table('compliance')->select('issue')->distinct()->get();
        $status = DB::table('compliance')->select('comp_status')->distinct()->get();
        $coordinators = DB::table('legalcoordinators')->pluck('email');

        //-----------------------rest of the page----------------
        //-----------------------my actions----------------
        //    $deptm=Auth::user()->deptid;
        $data1 = [];
        $e = Auth::user()->email;
        $dep = DB::table('departments')->where('hod', $e)->pluck('departmentname');
        // $deptm=Auth::user()->deptid;
        foreach ($dep as $d) {

            // if(count($dep) >0 )
            // {
            $respon = DB::select("select * from compliance");
            foreach ($respon as $dat) {
                $data1 = DB::table('compliance')
                    ->select(
                        'compliance.Cid',
                        'compliance.auth_id',
                        'compliance.issue',
                        'compliance.version',
                        'compliance.regulation',
                        'compliance.authority',
                        'compliance.requirement',
                        'duedate',
                        'compliance.aspect',
                        'owner',
                        'postedby',
                        'pro_status',
                        'compliance.comp_status',
                        'reason',
                        'compliance.updatedat',
                        'compliance.person_control',
                        'compliance.person_monitor',
                        'compliance.frequency',
                        'created_at',
                        'approvedby',
                        'closedby'
                    )
                    ->where('owner', '=', $d)
                    ->wherein('compliance.comp_status', ['Under Review', 'Partially Complied', 'Complied', 'Not Complied'])
                    ->where('pro_status', '!=', 'Reverted by HOD')
                    ->orderBy('compliance.created_at', 'desc')
                    ->distinct()
                    ->paginate(100);
            }
        }

        //---------------------Analysis---------------

        $chart1 = new ComplianceAnalysis;
        $chart1->title("Compliance Summary");
        $chart1->labels(['Complied', 'Partially Complied', 'NotComplied']);

        $Total = DB::select("select COUNT(r.comp_status)AS number
                            FROM  compliance AS r
                            right JOIN departments AS s
                            ON r.owner= s.id
                            ");

        $Complied = DB::select("select COUNT(r.comp_status)AS number
                            FROM  compliance AS r
                            right JOIN departments AS s
                            ON r.owner= s.id
                            and  r.comp_status='Complied'
                              ");

        $NotComplied = DB::select("select COUNT(r.comp_status)AS number
                            FROM  compliance AS r
                            right JOIN departments AS s
                            ON r.owner= s.id
                            and  r.comp_status='Not Complied'
                              ");

        $PartiallyComplied = DB::select("select COUNT(r.comp_status)AS number
                            FROM  compliance AS r
                            right JOIN departments AS s
                            ON r.owner= s.id
                            and  r.comp_status='Partially Complied'
                            ");
        $total = DB::select("select COUNT(r.comp_status)AS number
                            FROM  compliance AS r
                            right JOIN departments AS s
                            ON r.owner= s.id
                            ");

        $Compliedkey = [];
        foreach ($Complied as $key1) {
            array_push($Compliedkey, $key1->number);
        }
        $PartiallyCompliedkey = [];
        foreach ($PartiallyComplied as $key1) {
            array_push($PartiallyCompliedkey, $key1->number);
        }
        $NotCompliedkey = [];
        foreach ($NotComplied as $key1) {
            array_push($NotCompliedkey, $key1->number);
        }

        $chart1->dataset('', 'doughnut', [$Compliedkey, $PartiallyCompliedkey, $NotCompliedkey])
            ->color(['#baeb34', '#ffec70', '#f7533e'])
            ->backgroundcolor(['#baeb34', '#ffec70', '#f7533e']);
        $chart1->displayAxes(false);

        $chart = new ComplianceAnalysis;
        $chart->title("Issues Process Stage - Department Wise");
        $processes = DB::select("select departmentname from departments order by departmentname ASC");
        $process = [];
        foreach ($processes as $key) {
            array_push($process, $key->departmentname);
        }
        $chart->labels($process);

        $num = DB::select("select s.departmentname,COUNT(r.comp_status)AS number
                           FROM  compliance AS r
                           right JOIN departments AS s
                           ON r.owner= s.id
                           and  r.comp_status='Complied'
                           group by s.departmentname
                           order by s.departmentname ASC
                          ");

        $num1 = DB::select("select s.departmentname,COUNT(r.comp_status)AS number
                            FROM  compliance AS r
                            right JOIN departments AS s
                            ON r.owner= s.id
                            and  r.comp_status='Not Complied'
                            group by s.departmentname
                            order by s.departmentname ASC
                            ");

        $num2 = DB::select("select s.departmentname,COUNT(r.comp_status)AS number
                            FROM  compliance AS r
                            right JOIN departments AS s
                            ON r.owner= s.id
                            and  r.comp_status='Partially Complied'
                            group by s.departmentname
                            order by s.departmentname ASC
                            ");
        $num3 = DB::select("select s.departmentname,COUNT(r.comp_status)AS number
                            FROM  compliance AS r
                            right JOIN departments AS s
                            ON r.owner= s.id
                            and  r.comp_status='Under Review'
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
        $d = [];
        foreach ($num3 as $key) {
            array_push($d, $key->number);
        }

        $chart->dataset('Complied', 'bar', $a)->backgroundcolor('#baeb34')->color('#baeb34');
        $chart->dataset('Partially Complied', 'bar', $c)->backgroundcolor('#ffec70')->color('#ffec70');
        $chart->dataset('Not Complied', 'bar', $b)->backgroundcolor('#f7533e')->color('#f7533e');
        $chart->dataset('Under Review', 'bar', $d)->backgroundcolor('#2887ed')->color('#2887ed');
        $chart->height(400);

        $models = DB::table('modenadisable')->First();

        return view("user/compliancemgmt")->withData($data)
            ->withData1($data1)
            ->withChart($chart)
            ->withChart1($chart1)
            ->withIssues($issues)
            ->withStatus($status)
            ->withDepartments($departments)
            ->withCoordinators($coordinators)
            ->with('model', $models)
            ->withMr($mr);
    }

    public function reopen()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            $mn = DB::table('departments')->get();
            $data = Compliance::where('Cid', '=', $id)->get();
            $models = DB::table('modenadisable')->First();
            return view('user.compliance.reopen')->withMn($mn)
                ->withData($data)
                ->withId($id)->with('model', $models);
        }
    }

    public function reopenlegal(Request $req)
    {

        $id = $req->id;
        $issue = $req->input('issue');
        $version = $req->input('version');
        $regulation = $req->input('regulation');
        $authority = $req->input('authority');
        $exist = DB::table('compliance')->where('authority', $authority)->orderBy('created_at', 'desc')->first();
        if ($exist) {
            $c = $exist->count;
            $count = $c + 1;
        } else {
            $count = "1";
        }

        $requirement = $req->input('requirement');
        $aspect = $req->input('aspect');
        $owner = $req->input('owner');
        $due_date = $req->input('duedate');
        $frequency = $req->input('frequency');

        $updatedat = date('d-m-Y h:i:s A', time());

        $ID = Auth::user()->employeeid;
        $data = array(
            'count' => $count,
            'issue' => $issue,
            'version' => $version,
            'regulation' => $regulation,
            'authority' => $authority,
            'requirement' => $requirement,
            'aspect' => $aspect,
            'owner' => $owner,
            'postedby' => $ID,
            'duedate' => $due_date,
            'frequency' => $frequency,
            'pro_status' => 'Waiting for HOD Review',
            'updatedat' => $updatedat,
        );
        Compliance::where('Cid', $id)->update($data);
        //insert data to activity table superadmin code
        $activity = "Legle Requirement was Reopened where Cid is " . $id;
        $date = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date);
        $theTime = Carbon::now()->format('Y-m-d H:i:s');
        $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
        ViewActivity::insert($datas);
        //END OF superadmin code
        $d = DB::table('compliance')->latest('updatedat')->first();
        $freq_review = $d->updatedat;
        $data1 = array('freq_review' => $freq_review);
        $f = DB::table('compliance')->where('Cid', $d->Cid)->update($data1);

        Session::flash('msg', "Legal requirement sent for approval Successfully");

        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    //delete request
    public function delete()
    {
        if (isset($_GET['val'])) {
            $id = $_GET['val'];
            if(!Gate::allows('delete-compliance')){
                Session::flash('error', 'You are not authorized to delete any Legal Requirement');
                return;
            }
            //insert data to activity table superadmin code
            $activity = "Legal Requirement was Deleted where Compliance id is " . $id;
            $date = Carbon::now()->toDateTimeString();
            $date = Carbon::parse($date);
            $theTime = Carbon::now()->format('Y-m-d H:i:s');
            $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $theTime, 'updated_at' => $theTime);
            ViewActivity::insert($datas);
            //end of superadmin codes

            DB::delete('delete from compliance where Cid = :eid', ['eid' => $id]);
            Session::flash('msg', 'Legal requirement deleted successfully!');
        }
    }

    //edit compliance
    public function editreq()
    {
        if (isset($_GET['val'])) {
            if(!Gate::allows('edit-compliance')){
                Session::flash('error', 'You are not authorized to access that page.');
                return redirect('/user/compliancemgmt');
            }
            $id = $_GET['val'];
            $mn = DB::table('departments')->get();
            $users = User::where('approved', 1)->get();
            $id = explode(',', $_GET['val']);
            $m = count($id);
            $n = 0;
            foreach ($id as $d) {
                if ($n < $m) {
                    $data[] = Compliance::where('Cid', $d)->first();
                }
                $n = $n + 1;
            }
        }

        $models = DB::table('modenadisable')->First();
        return view('user/compliance/editrequest')->with([
            'data' => $data,
            'mn' => $mn,
            'id' => $id,
            'model' => $models,
            'users' => $users,
        ]);
    }

    public function editrequest(Request $req)
    {
        if(!Gate::allows('edit-compliance')){
            Session::flash('error', 'You are not authorized to access that page.');
            return redirect('/user/compliancemgmt');
        }
        $m = count($req->Cid);
        $issue = $req->input('issue');
        $version = $req->input('version');
        $regulation = $req->input('regulation');
        $requirement = $req->input('requirement');
        $aspect = $req->input('aspect');
        $owner = $req->input('owner');
        $authority = $req->input('authority');
        $due_date = $req->input('duedate');
        $frequency = $req->input('frequency');
        $sno = $req->input('sno');
        $m = count($sno);
        $n = 0;
        foreach ($sno as $d) {
            if ($n < $m) {
                $data2[] = DB::table('compliance')->where('Cid', $d)->first();
            }
        }

        foreach ($data2 as $dat) {
            foreach ($sno as $s) {
                if ($dat->Cid == $s && $n < $m) {
                    $c = Compliance::where('Cid', $s)->first();
                    $oldResponsibility = $c->responsibility;
                    DB::table('compliance')->where('Cid', $s)
                        ->update(array(
                            'issue' => $req->issue[$n],
                            'version' => $req->version[$n],
                            'regulation' => $req->regulation[$n],
                            'requirement' => $req->requirement[$n],
                            'aspect' => $req->aspect[$n],
                            'owner' => $req->owner[$n],
                            'authority' => $req->authority[$n],
                            'responsibility' => $req->responsibility[$n],
                            'duedate' => $req->duedate[$n],
                            'frequency' => $req->frequency[$n],
                        ));
                    $maildata = array(
                        'Cid' => $s,
                        'issue' => $req->issue[$n],
                        'owner' => $req->owner[$n],
                        'duedate' => $req->duedate[$n],
                    );
                    if ($oldResponsibility != $req->responsibility[$n]) {
                        $mails = EmailNotification::where('module', 'compliance')
                            ->where('item', 'Compliance Change Responsibility')
                            ->where('is_blocked', false)
                            ->first();
                        if($mails){
                            $emaildata = array(
                                'issue' => $req->issue[$n],
                                'version' => $req->version[$n],
                                'authority' => $req->authority[$n],
                                'auth_id' => Compliance::where('Cid',$s)->value('auth_id'),
                                'item' => $mails->item,
                                'email_content' => $mails->email_content,
                                'department' => Departments::where('id',$req->owner[$n])->value('departmentname'),
                            );
                            //sending mail for responsible persons
                            if($req->responsibility[$n]){
                                Mail::to($req->responsibility[$n])->send(new ComplianceChangeResponsibility($emaildata));
                            }
                            //sending users added for email notifications in admin panel
                            $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails->id)->get();
                            if (count($compl_userdatas) > 0) {
                                $adminaddedusers = $compl_userdatas->pluck('user_id')->toArray();
                                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                                if (count($userdatas)>0) {
                                    Mail::to($userdatas)->send(new ComplianceChangeResponsibility($emaildata));
                                }
                            }
                        }else{
                            Session::flash('error', 'Mail Sending Blocked for Compliance Change Responsibility');
                        }
                    }
                    //insert data to activity table superadmin code
                    $activity = "A Legal requirement is  Edited and Updated with new Details  where  ID " . $s;
                    $date = Carbon::now()->toDateTimeString();
                    $date = Carbon::parse($date);
                    $now = Carbon::now()->format('Y-m-d H:i:s');
                    $datas = array('name' => Auth::user()->name, 'email' => Auth::user()->email, 'date' => $date->format('Y-m-d'), 'time' => $date->format('H:i:s'), 'activity' => $activity, 'type' => 'user', 'created_at' => $now, 'updated_at' => $now);
                    ViewActivity::insert($datas);
                }
            }
            $n = $n + 1;
        }
        Session::flash('msg', 'Legal Requirement updated successfully!');
        $models = DB::table('modenadisable')->First();
        return redirect('/user/compliancemgmt')->with('model', $models);
    }
    //add license permit
    public function addLicensePermit(Request $req){
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'licensenumber' => 'required|string|unique:license_permits,licensenumber,NULL,id,is_deleted,0',
            'expirydate' => 'required|string',
            'effdate' => 'nullable|string',
            'responsible' => 'required|array',
            'responsible.*'=> 'required||exists:users,id',
            'renewal' => 'nullable|string',
            'associated' => 'nullable|string',
            'compl_id' => 'required|integer',
            'document' => 'required|array',
            'document.*' => 'required|file',
            'frequency' => 'required|array',
            'frequency.*' => 'required|string',
        ],[
            'name.required' => 'The License/Permit Name field is required.',
            'licensenumber.required' => 'The License Number field is required.',
            'licensenumber.unique' => 'The License Number has already been taken.',
            'expirydate.required' => 'The Expiry Date field is required.',
            'responsible.required' => 'The Responsible Person field is required.',
            'responsible.*.required' => 'The Responsible Person field is required.',
            'responsible.*.exists' => 'Invalid user ID in the Responsible Person field.',
            'document.required' => 'The File field is required.',
            'document.*.file' => 'The File field is required',
            'frequency.required' => 'The Email reminder for Renewal field is required.',
            'frequency.*.required' => 'The Email reminder for Renewal field is required.',
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if ($error == null) {
            if ($req->hasFile('document')) {
                $id = User::where('email', Auth::user()->email)->value('id');
                $fileArray = [];
                foreach ($req->file('document') as $file) {
                    $fileSize = $file->getClientOriginalName();
                    $newFileName = $fileSize;
                    $destinationPath = public_path('storage/compliance_files');
                    $file->move($destinationPath, $newFileName);
                    $fileArray[] = $newFileName;
                }
                $files = implode(',',$fileArray);
                $compl_id = $req->input('compl_id');
                $exist = LicensePermit::where('comp_id',$compl_id)->first();
                if ($exist) {
                    $licensePermits = LicensePermit::where('comp_id',$compl_id)->orderBy('id', 'desc')->first();
                    $ynew = $licensePermits->created_at;
                    $ystr = Carbon::createFromFormat('Y-m-d H:i:s', $ynew)->format('y');
                    $y = Date("Y");
                    $year = substr($y, -2);
                    if ($year == $ystr) {
                        $c = LicensePermit::where('comp_id',$compl_id)->pluck('count');
                        foreach ($c as $count) {
                            $count = $count + 1;
                        }
                    } else {
                        $count = "1";
                    }
                } else {
                    $count = "1";
                }
                $y = Date("Y");
                $year = substr($y, -2);
                $auth_id = $req->input('auth_id');
                $compliance_auth_id = $auth_id.'/'.$count.'/'.$year;
                $today = date('Y-m-d');
                $expirydate = $req->input('expirydate');
                $oneMonthBeforeExpiry = date('Y-m-d', strtotime('+1 month', strtotime($today)));
                $status = '';
                if($expirydate < $today)
                    $status = 'Expired';
                elseif($expirydate > $oneMonthBeforeExpiry)
                    $status = 'Active';
                else
                    $status = 'Near to expiry';
                $coordinatorsArray = DB::table('legalcoordinators')->pluck('email')->toArray();
                if ($req->frequency) {
                    $freq = $req->input('frequency');
                    if(in_array('Daily',$freq)){
                        $license_respon_emails = EmailNotification::where('module', 'compliance')
                            ->where('item', 'License/Permits Renewal Notification')
                            ->where('is_blocked', false)
                            ->first();
                        if($license_respon_emails){
                            $emaildata = array(
                                'licenseid' => $compliance_auth_id,
                                'cid' => $auth_id,
                                'item' => $license_respon_emails->item,
                                'email_content' => $license_respon_emails->email_content,
                                'name' => $req->input('name'),
                                'licensenumber' => $req->input('licensenumber'),
                                'expirydate' => $req->input('expirydate') ? date("d/m/Y", strtotime($req->input('expirydate'))) : '',
                                'status' => $status,
                            );
                            $responsibilities = User::whereIn('id',$req->input('responsible'))->pluck('email')->toArray();
                            //sending mail for responsible persons
                            if(count($responsibilities)>0){
                                Mail::to($responsibilities)->send(new LicensePermitRenewalNotification($emaildata));
                            }
                            // sending mail for legal coordinator
                            if(count($coordinatorsArray)){
                                Mail::to($coordinatorsArray)->send(new LicensePermitRenewalNotification($emaildata));
                            }
                            //sending users added for email notifications in admin panel
                            $license_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $license_respon_emails->id)->get();
                            if (count($license_respon_userdatas) > 0) {
                                $adminaddedusers = $license_respon_userdatas->pluck('user_id')->toArray();
                                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                                if (count($userdatas)>0) {
                                    Mail::to($userdatas)->send(new LicensePermitRenewalNotification($emaildata));
                                }
                            }
                        }else{
                            Session::flash('msg', 'Mail Sending Blocked for License/Permits Renewal Notification');
                            return redirect()->back();
                        }
                    }
                    $frequency = implode(',', $freq);
                } else {
                    $frequency = null;
                }
                LicensePermit::insert([
                    'comp_id' => $compl_id,
                    'compliance_auth_id' => $compliance_auth_id,
                    'count' => $count,
                    'name' => $req->input('name'),
                    'licensenumber' => $req->input('licensenumber'),
                    'postedby' => $id,
                    'expirydate' => $expirydate,
                    'effectivedate' => $req->input('effdate'),
                    'responsible' => implode(',', $req->input('responsible')),
                    'renewal_requirements' => $req->input('renewal'),
                    'associated_regulations' => $req->input('associated'),
                    'file' => $files,
                    'frequency' => $frequency,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $mails1 = EmailNotification::where('module', 'compliance')
                    ->where('item', 'License/Permits Added')
                    ->where('is_blocked', false)
                    ->first();
                if ($mails1) {
                    $mailData = array(
                        'licenseid' => $compliance_auth_id,
                        'cid' => $auth_id,
                        'item' => $mails1->item,
                        'email_content' => $mails1->email_content,
                        'name' => $req->input('name'),
                        'licensenumber' => $req->input('licensenumber'),
                        'addedby' => User::where('id',$id)->value('name'),
                        'expirydate' => $req->input('expirydate') ? date("d/m/Y", strtotime($req->input('expirydate'))) : '',
                    );
                    $resp = User::whereIn('id',$req->input('responsible'))->pluck('email')->toArray();
                    if(count($resp)>0){
                        Mail::to($resp)->send(new LicensePermitAdd($mailData));
                    }
                    if (count($coordinatorsArray)>0) {
                        Mail::to($coordinatorsArray)->send(new LicensePermitAdd($mailData));
                    }
                    $userdatas1 = EmailNotificationsUserDetails::where('notification_id', $mails1->id)->get();
                    if (count($userdatas1) > 0) {
                        $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas)>0) {
                            Mail::to($userdatas)->send(new LicensePermitAdd($mailData));
                        }
                    }

                } else {
                    Session::flash('msg', 'Mail Sending Blocked for License/Permits Added');
                    return redirect()->back();
                }

                return [
                    'error' => $error,
                ];
            }

        }
        return [
            'error' => $error,
        ];
    }
    public function viewLicensePermits(string $type=null){

        $models = DB::table('modenadisable')->First();

        $data = $type ? LicensePermit::where('status',$type)->where('is_deleted',false)->orderByDesc('created_at')->get() : LicensePermit::where('is_deleted',false)->orderByDesc('created_at')->get();
        $coordinatorsArray = DB::table('legalcoordinators')->pluck('email')->toArray();
        $users = User::where('is_email_verified',true)->get();
        return view('user.compliance.license_permits')
                ->with('data',$data)
                ->with('model',$models)
                ->with('users',$users)
                ->with('coordinatorsArray',$coordinatorsArray);
    }
    public function editLicensePermit(int $id){
        $models = DB::table('modenadisable')->First();
        $data = LicensePermit::where('id',$id)->first();
        $users = User::where('is_email_verified',true)->get();
        return view('user.compliance.editlicensepermit')
            ->with('model',$models)
            ->with('users',$users)
            ->with('data',$data);
    }
    public function updateLicensePermit(Request $req){
        $licenseId = $req->input('license_id');
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'licensenumber' => 'required|string|unique:license_permits,licensenumber,'.$licenseId.',id,is_deleted,0',
            'expirydate' => 'required|string',
            'effdate' => 'nullable|string',
            'responsible' => 'required|array',
            'responsible.*' => 'required|exists:users,id',
            'renewal' => 'nullable|string',
            'associated' => 'nullable|string',
            'document' => 'nullable|array',
            'document.*' => 'nullable|file',
            'frequency' => 'required|array',
            'frequency.*' => 'required|string',
        ], [
            'name.required' => 'The License/Permit Name field is required.',
            'licensenumber.required' => 'The License Number field is required.',
            'licensenumber.unique' => 'The License Number has already been taken.',
            'expirydate.required' => 'The Expiry Date field is required.',
            'responsible.required' => 'The Responsible Person field is required.',
            'responsible.*.required' => 'The Responsible Person field is required.',
            'responsible.*.exists' => 'Invalid user ID in the Responsible Person field.',
            'document.*.file' => 'The File field is required',
            'frequency.required' => 'The Email reminder for Renewal field is required.',
            'frequency.*.required' => 'The Email reminder for Renewal field is required.',
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        }
        $updatedby = User::where('email',Auth::user()->email)->value('id');
        $existingLicense = LicensePermit::find($licenseId);
        $today = date('Y-m-d');
        $expirydate = $req->input('expirydate');
        $oneMonthBeforeExpiry = date('Y-m-d', strtotime('+1 month', strtotime($today)));
        $status = '';
        if($expirydate < $today)
            $status = 'Expired';
        elseif($expirydate > $oneMonthBeforeExpiry)
            $status = 'Active';
        else
            $status = 'Near to expiry';
        $coordinatorsArray = DB::table('legalcoordinators')->pluck('email')->toArray();
        $complianceId = Compliance::where('Cid',$existingLicense->comp_id)->value('auth_id');
        if ($req->frequency) {
            $freq = $req->input('frequency');
            if(in_array('Daily',$freq)){
                $license_respon_emails = EmailNotification::where('module', 'compliance')
                    ->where('item', 'License/Permits Renewal Notification')
                    ->where('is_blocked', false)
                    ->first();
                if($license_respon_emails){
                    $emaildata = array(
                        'licenseid' => $existingLicense->compliance_auth_id,
                        'cid' => $complianceId,
                        'item' => $license_respon_emails->item,
                        'email_content' => $license_respon_emails->email_content,
                        'name' => $req->input('name'),
                        'licensenumber' => $req->input('licensenumber'),
                        'expirydate' => $expirydate ? date("d/m/Y", strtotime($expirydate)) : '',
                        'status' => $status,
                    );
                    $responsibilities = User::whereIn('id',$req->input('responsible'))->pluck('email')->toArray();
                    //sending mail for responsible persons
                    if(count($responsibilities)>0){
                        Mail::to($responsibilities)->send(new LicensePermitRenewalNotification($emaildata));
                    }
                    // sending mail for legal coordinator
                    if(count($coordinatorsArray)){
                        Mail::to($coordinatorsArray)->send(new LicensePermitRenewalNotification($emaildata));
                    }
                    //sending users added for email notifications in admin panel
                    $license_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $license_respon_emails->id)->get();
                    if (count($license_respon_userdatas) > 0) {
                        $adminaddedusers = $license_respon_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                        if (count($userdatas)>0) {
                            Mail::to($userdatas)->send(new LicensePermitRenewalNotification($emaildata));
                        }
                    }
                }else{
                    Session::flash('error', 'Mail Sending Blocked for License/Permits Renewal Notification');
                }
            }
            $frequency = implode(',', $freq);
        } else {
            $frequency = null;
        }
        $removedFilenames = json_decode($req['removedFilenames']);
        $removedFilenames = is_array($removedFilenames) ? $removedFilenames : [];

        $updatedData = [
            'name' => $req->input('name'),
            'licensenumber' => $req->input('licensenumber'),
            'expirydate' => $expirydate,
            'effectivedate' => $req->input('effdate'),
            'responsible' => implode(',', $req->input('responsible')),
            'renewal_requirements' => $req->input('renewal'),
            'associated_regulations' => $req->input('associated'),
            'frequency' => $frequency,
            'Weeklyfreq' => date('Y-m-d',strtotime('1970-01-01')),
            'EveryFivedaysfreq' => date('Y-m-d',strtotime('1970-01-01')),
            'EveryTwodaysfreq' => date('Y-m-d',strtotime('1970-01-01')),
            'Dailyfreq' => date('Y-m-d',strtotime('1970-01-01')),
            'updatedby' => $updatedby,
            'status' => $status,
            'updated_at' => now(),
        ];

        if ($req->hasFile('document')) {
            $fileArray = [];
            foreach ($req->file('document') as $file) {
                $newFileName = $file->getClientOriginalName();
                $file->move(public_path('storage/compliance_files'), $newFileName);
                $fileArray[] = $newFileName;
            }

            $existingFiles = explode(',', $existingLicense->file);
            $existingFiles = array_diff($existingFiles, $removedFilenames);

            $updatedData['file'] = implode(',', array_merge($existingFiles, $fileArray));
        } else {
            $updatedData['file'] = implode(',', array_diff(explode(',', $existingLicense->file), $removedFilenames));
        }

        if (LicensePermit::where('id', $licenseId)->update($updatedData)) {
            Session::flash('msg', "License/Permit has been updated successfully.");
            $updatedMails = EmailNotification::where('module', 'compliance')
                ->where('item', 'License/Permits Updated')
                ->where('is_blocked', false)
                ->first();
            if($updatedMails){
                $emaildata = array(
                    'licenseid' => $existingLicense->compliance_auth_id,
                    'cid' => $complianceId,
                    'item' => $updatedMails->item,
                    'email_content' => $updatedMails->email_content,
                    'name' => $req->input('name'),
                    'licensenumber' => $req->input('licensenumber'),
                    'expirydate' => $expirydate ? date("d/m/Y", strtotime($expirydate)) : '',
                    'updatedby' => User::where('id',$updatedby)->value('name'),
                );
                $responsibilities = User::whereIn('id',$req->input('responsible'))->pluck('email')->toArray();
                //sending mail for responsible persons
                if(count($responsibilities)>0){
                    Mail::to($responsibilities)->send(new LicensePermitsUpdated($emaildata));
                }
                // sending mail for legal coordinator
                if(count($coordinatorsArray)){
                    Mail::to($coordinatorsArray)->send(new LicensePermitsUpdated($emaildata));
                }
                //sending users added for email notifications in admin panel
                $license_updated_userdatas = EmailNotificationsUserDetails::where('notification_id', $updatedMails->id)->get();
                if (count($license_updated_userdatas) > 0) {
                    $adminaddedusers = $license_updated_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas)>0) {
                        Mail::to($userdatas)->send(new LicensePermitsUpdated($emaildata));
                    }
                }
            }else{
                Session::flash('error', 'Mail Sending Blocked for License/Permits Updated');
            }
        } else {
            Session::flash('error', "License/Permit has not been updated successfully.");
        }
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }
    public function deleteLicensePermits(Request $request)
    {
        $id = $request->input('id');
        $data = LicensePermit::find($id);
        if($data){
            LicensePermit::where('id', $id)->update(['is_deleted' => true]);
            Session::flash('msg', 'License/Permit has been deleted successfully.');
            return response()->json([
                'status' => 200,
           ]);
        }
        else{
            return response()->json([
                'status' => 404,
           ]);
        }

    }
    public function renewLicensePermit(int $id){
        $models = DB::table('modenadisable')->First();
        $data = LicensePermit::where('id',$id)->first();
        return view('user.compliance.renewlicensepermit')
            ->with('model',$models)
            ->with('data',$data);
    }
    public function renewedLicensePermit(Request $req){
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'licensenumber' => 'required|string',
            'expirydate' => 'required|string',
            'effdate' => 'nullable|string',
        ], [
            'name.required' => 'The License/Permit Name field is required.',
            'licensenumber.required' => 'The License Number field is required.',
            'expirydate.required' => 'The Expiry Date field is required.',
        ]);

        if ($validator->fails()) {
            $models = DB::table('modenadisable')->First();
            return back()
                ->withErrors($validator)
                ->withInput()->with('model', $models);
        }
        $licenseId = $req->input('license_id');
        $renewedby = User::where('email',Auth::user()->email)->value('id');
        $today = date('Y-m-d');
        $expirydate = $req->input('expirydate');
        $oneMonthBeforeExpiry = date('Y-m-d', strtotime('+1 month', strtotime($today)));
        $status = '';
        if($expirydate < $today)
            $status = 'Expired';
        elseif($expirydate > $oneMonthBeforeExpiry)
            $status = 'Active';
        else
            $status = 'Near to expiry';
        $updatedData = [
            'expirydate' => $expirydate,
            'effectivedate' => $req->input('effdate'),
            'status' => $status,
            'renewedby' => $renewedby,
            'renewed_at' => now(),
            'Weeklyfreq' => date('Y-m-d',strtotime('1970-01-01')),
            'EveryFivedaysfreq' => date('Y-m-d',strtotime('1970-01-01')),
            'EveryTwodaysfreq' => date('Y-m-d',strtotime('1970-01-01')),
            'Dailyfreq' => date('Y-m-d',strtotime('1970-01-01')),
        ];
        $record = LicensePermit::where('id',$licenseId)->first();
        $coordinatorsArray = DB::table('legalcoordinators')->pluck('email')->toArray();
        $complianceId = Compliance::where('Cid',$record->comp_id)->value('auth_id');
        if (LicensePermit::where('id',$licenseId)->update($updatedData)) {
            Session::flash('msg', "License/Permit has been renewed successfully.");
            $renewedMails = EmailNotification::where('module', 'compliance')
                ->where('item', 'License/Permits Renewed')
                ->where('is_blocked', false)
                ->first();
            if($renewedMails){
                $emaildata = array(
                    'licenseid' => $record->compliance_auth_id,
                    'cid' => $complianceId,
                    'item' => $renewedMails->item,
                    'email_content' => $renewedMails->email_content,
                    'name' => $record->name,
                    'renewedby' => User::where('id',$renewedby)->value('name'),
                    'licensenumber' => $record->licensenumber,
                    'expirydate' => $expirydate ? date("d/m/Y", strtotime($expirydate)) : '',
                );
                $responsibilities = User::whereIn('id',explode(',',$record->responsible))->pluck('email')->toArray();
                //sending mail for responsible persons
                if(count($responsibilities)>0){
                    Mail::to($responsibilities)->send(new LicensePermitsRenewed($emaildata));
                }
                // sending mail for legal coordinator
                if(count($coordinatorsArray)){
                    Mail::to($coordinatorsArray)->send(new LicensePermitsRenewed($emaildata));
                }
                //sending users added for email notifications in admin panel
                $license_renewed_userdatas = EmailNotificationsUserDetails::where('notification_id', $renewedMails->id)->get();
                if (count($license_renewed_userdatas) > 0) {
                    $adminaddedusers = $license_renewed_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas)>0) {
                        Mail::to($userdatas)->send(new LicensePermitsRenewed($emaildata));
                    }
                }
            }else{
                Session::flash('error', 'Mail Sending Blocked for License/Permits Renewed');
            }
        } else {
            Session::flash('error', "License/Permit has not been renewed successfully.");
        }
        $models = DB::table('modenadisable')->First();
        return redirect()->back()->with('model', $models);
    }

    /**
     * View detail a compliance
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        $models = \Illuminate\Support\Facades\DB::table('modenadisable')->first();
        $compliance = Compliance::with(['licensePermits',
            'postedByCompliance', 'approvedByCompliance','draftReviewedBy',
            'reviewedByCompliance', 'reviewedEvaluationByCompliance','regulatory','legalDocuments'
        ])->where('Cid', $id)->firstOrFail();

        $visibleToList = [];
        $relatedRisk = [];
        if ($compliance->visibleto) {
            $visibleToList = explode(',', $compliance->visibleto);
            $visibleToList = User::whereIn('email', $visibleToList)->get();
        }
        if ($compliance->aspect) {
            $relatedRisk = explode(',', $compliance->aspect);
            $relatedRisk = Risk::whereIn('grp_id', $relatedRisk)->get();
        }
        $histories = CompliancePeriodicEvaluationHistory::with('periodicEvaluationBy')->where('compliance_id',$id)->orderByDesc('id')->get();
        $tempHistory = $histories->pluck('periodic_evaluation_at')->toArray();
        if($compliance->review_evaluation_date){
            if(!in_array($compliance->review_evaluation_date, $tempHistory)){
                $addOn = new CompliancePeriodicEvaluationHistory();
                $addOn->old_status = 'Under Review';
                if($compliance->comp_status=='Awaiting Periodic Evaluation' || $compliance->comp_status=='Periodic Evaluation On Due'){
                    $addOn->new_status = 'Complied';
                }else{
                    $addOn->new_status = $compliance->comp_status;
                }
                $addOn->periodic_evaluation_at = $compliance->review_evaluation_date;
                $addOn->periodic_evaluation_by = User::where('employeeid',$compliance->reviewed_evaluation_by)->value('name')??'--';
                $histories->push($addOn);
            }
        }
        $oldReviewDetails = CompliancePeriodicEvaluationHistory::with('periodicEvaluationBy')->where('compliance_id',$id)
            ->where('periodic_evaluation_at', 'like', '%00:00:00')
            ->first();
        return view('user.compliance.detail', compact('compliance', 'visibleToList', 'relatedRisk','histories','oldReviewDetails'))
            ->with('model', $models);
    }

    /**
     * Export PDF of detail a compliance
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function detailPdf($id)
    {
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        $models = \Illuminate\Support\Facades\DB::table('modenadisable')->first();
        $compliance = Compliance::with(['licensePermits',
            'postedByCompliance', 'approvedByCompliance','draftReviewedBy',
            'reviewedByCompliance', 'reviewedEvaluationByCompliance','regulatory','legalDocuments'
        ])->where('Cid', $id)->firstOrFail();

        $visibleToList = [];
        $relatedRisk = [];
        if ($compliance->visibleto) {
            $visibleToList = explode(',', $compliance->visibleto);
            $visibleToList = User::whereIn('email', $visibleToList)->get();
        }
        if ($compliance->aspect) {
            $relatedRisk = explode(',', $compliance->aspect);
            $relatedRisk = Risk::whereIn('grp_id', $relatedRisk)->get();
        }
        //    return view('user.compliance.detail_pdf', compact('compliance', 'visibleToList', 'relatedRisk'))
        //        ->with('model', $models);
        $histories = CompliancePeriodicEvaluationHistory::with('periodicEvaluationBy')->where('compliance_id',$id)->orderByDesc('id')->get();
        $tempHistory = $histories->pluck('periodic_evaluation_at')->toArray();
        if($compliance->review_evaluation_date){
            if(!in_array($compliance->review_evaluation_date, $tempHistory)){
                $addOn = new CompliancePeriodicEvaluationHistory();
                $addOn->old_status = 'Under Review';
                if($compliance->comp_status=='Awaiting Periodic Evaluation' || $compliance->comp_status=='Periodic Evaluation On Due'){
                    $addOn->new_status = 'Complied';
                }else{
                    $addOn->new_status = $compliance->comp_status;
                }
                $addOn->periodic_evaluation_at = $compliance->review_evaluation_date;
                $addOn->periodic_evaluation_by = User::where('employeeid',$compliance->reviewed_evaluation_by)->value('name')??'--';
                $histories->push($addOn);
            }
        }
        $oldReviewDetails = CompliancePeriodicEvaluationHistory::with('periodicEvaluationBy')->where('compliance_id',$id)
            ->where('periodic_evaluation_at', 'like', '%00:00:00')
            ->first();
        $pdf = \PDF::loadView('user.compliance.detail_pdf',
            compact(
                'compliance', 'visibleToList', 'relatedRisk','histories','oldReviewDetails'
            ));
        return $pdf->download('detail_' . $compliance->Cid . '.pdf');
    }
    /**
     * Chnage Legal Coordinator
     *
     * @param $filename
     * @return \Illuminate\Http\Response
     */
    public function postChangeCoordinator(Request $request)
    {
        try {
            $coordinator = $request->input('legalCoordinator');
            $id = $request->input('compliance_id');
            $compliance = Compliance::with(['regulatory','legalDocuments','legalCoordinator','responsible','dep'])->where('Cid', $id)->first();
            $previousCoordinator = $compliance->legalCoordinator ? $compliance->legalCoordinator->name : null;
            if (!$compliance) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Compliance not found'
                ]);
            }
            $statusArray = [
                'Under Review', 'Awaiting Compliance Evaluation', 'Complied, Under Review',
                'Not Complied, Under Review', 'Partially Complied, Under Review',
                'Need improvement', 'Compliance Evaluation due','Awaiting Periodic Evaluation','Periodic Evaluation On Due'
            ];
            Compliance::where('Cid', $id)->update(['legal_coordinator' => $coordinator,'updated_at' => now()]);
            $compliance->refresh()->load(['legalCoordinator']);

            // C. Handle Emails
            $notificationItem = in_array($compliance->comp_status, $statusArray)
                                ? 'Compliance Reassigned - Request to Review Assigned Compliance - [Compliance ID]'
                                : 'Compliance Reassignment Notification - [Compliance ID]';

            $emailNotification = EmailNotification::where('module', 'compliance')
                ->where('item', $notificationItem)
                ->where('is_blocked', false)
                ->first();

            if ($emailNotification) {
                $statusArray = [
                    'Under Review', 'Awaiting Compliance Evaluation', 'Complied, Under Review',
                    'Not Complied, Under Review', 'Partially Complied, Under Review',
                    'Need improvement', 'Compliance Evaluation due'
                ];
                $status = in_array($compliance->comp_status,$statusArray) ? 'Draft Under Review' : $compliance->comp_status;
                $app_url = BaseController::addSlashToEndOfURL();
                $emailData = [
                    'reg_name' => $compliance->regulatory ? $compliance->regulatory->name : '--',
                    'doc_name' => $compliance->legalDocuments ? $compliance->legalDocuments->name : '--',
                    'title' => $compliance->regulation,
                    'requirement' =>$compliance->requirement,
                    'responsible' => $compliance->responsible ? $compliance->responsible->name : '--',
                    'department' => $compliance->dep ? $compliance->dep->departmentname : '--',
                    'previous_coordinator' => $previousCoordinator,
                    'new_coordinator' => $compliance->legalCoordinator ? $compliance->legalCoordinator->name : null,
                    'reassigned_by' => Auth::user()->name,
                    'item' => $emailNotification->item,
                    'email_content' => $emailNotification->email_content,
                    'auth_id' => $compliance->auth_id,
                    'status' => $status,
                    'app_url' => $app_url,
                    'id' => $compliance->Cid
                ];
                // Select Mailable class based on action
                $mailable = in_array($compliance->comp_status, $statusArray)
                    ? new ComplianceReassigned($emailData)
                    : new ComplianceReassignmentNotification($emailData);

                // Send to Creator
                if ($compliance->legalCoordinator && $compliance->legalCoordinator->email) {
                    try {
                        Mail::to($compliance->legalCoordinator->email)->send($mailable);
                    } catch (\Exception $e) {
                        Log::error("Failed sending $notificationItem email to new legal coordinator: " . $e->getMessage());
                    }
                }

                // Send to added users in admin panel
                $compl_userdatas = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                if ($compl_userdatas->isNotEmpty()) {
                    $adminAddedUserIds = $compl_userdatas->pluck('user_id')->toArray();
                    $adminEmails = User::whereIn('id', $adminAddedUserIds)->pluck('email')->toArray();

                    if (!empty($adminEmails)) {
                        try {
                            Mail::to($adminEmails)->send($mailable);
                        } catch (\Exception $e) {
                            Log::error("Failed sending $notificationItem email to added users: " . $e->getMessage());
                        }
                    }
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'Legal coordinator updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }
}
