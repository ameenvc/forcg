<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Departments;
use App\Http\Requests\Racm\AddRacmRequest;
use App\Http\Requests\Racm\AddReviewerApproverRequest;
use App\RacmReviewerDetail;
use App\ModenaDisable;
use App\RiskRacm;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Mail\RacmApprovalRequest;
use App\Mail\RacmReviewRequest;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;

class RacmController extends Controller
{
    /**
     * RacmController constructor
     */
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    /**
     * Get list racm
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $model = ModenaDisable::first();
        $riskAcms = RiskRacm::with(['department', 'approver','approvedBy', 'postedBy'])->orderBy('created_at', 'DESC')->get();
        $departments = $riskAcms->pluck('department.departmentname');
        $allDeptNames = implode(',', array_unique($departments->toArray()));
        $users = User::where('is_email_verified',true)->where('approved',true)->get();
        return view('user.risks.racm.index', compact( 'model', 'riskAcms', 'allDeptNames','users'));
    }

    /**
     * Create racm
     *
     * @param $id
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function create()
    {
        $responseData = [];

        try {
            $users = User::orderBy('created_at', 'DESC')->get();
            $departments = Departments::orderBy('created_at', 'DESC')->get();
            $responseData['success'] = true;
            $responseData['view'] = view('user.risks.racm._form', compact(
                 'users', 'departments'
            ))->render();
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * @param AddRacmRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddRacmRequest $request)
    {
        $response = [];
        try {
            $requestData = $request->only([
                'risk_factor_title',
                'process_id',
                'risk_factor_name',
                'risk_factor_description',
                'low',
                'moderate',
                'high',
                'unit',
                'unit_other',
                // 'reviewer_id',
                // 'approver_id',
            ]);
            $admin = Admin::find(auth()->id());

            if ($admin) {
                $user = User::where('email', $admin->email)->first();
                $userId = $user ? $user->id : Auth::user()->id;
            } else {
                $userId = Auth::user()->id;
            }
            $requestData['posted_by'] = $userId;
            $requestData['status'] = 'Review Awaiting';
            RiskRacm::create($requestData);
            $response['success'] = true;
            $response['message'] = 'Created new RACM successfully';
            Session::flash('msg', "Created new RACM successfully");
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
            $response['message'] = 'Create new racm failure';
        }

        return response()->json($response);
    }

    /**
     * Edit racm
     *
     * @param $id
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function edit($id)
    {
        $responseData = [];

        try {
            $racm = RiskRacm::findOrFail($id);
            $users = User::orderBy('created_at', 'DESC')->get();
            $departments = Departments::orderBy('created_at', 'DESC')->get();
            $responseData['success'] = true;
            $responseData['view'] = view('user.risks.racm._form', compact(
                'racm', 'users', 'departments'
            ))->render();
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Update Racm
     *
     * @param $id
     * @param AddRacmRequest $request
     *
     * @return JsonResponse
     */
    public function update($id, AddRacmRequest $request): JsonResponse
    {
        try {
            $response = [];
            $requestData = $request->only([
                'risk_factor_title',
                'process_id',
                'risk_factor_name',
                'risk_factor_description',
                'low',
                'moderate',
                'high',
                'unit',
                'unit_other',
                // 'reviewer_id',
                // 'approver_id',
            ]);
            $admin = Admin::find(auth()->id());

            if ($admin) {
                $user = User::where('email', $admin->email)->first();
                $userId = $user ? $user->id : Auth::user()->id;
            } else {
                $userId = Auth::user()->id;
            }
            $requestData['posted_by'] = $userId;
            $racm = RiskRacm::findOrFail($id);
            $requestData['approver_id'] = null;
            $requestData['approved_by'] = null;
            $requestData['approved_date'] = null;
            $requestData['rejected_by_approver'] = false;
            $requestData['reject_reason_by_approver'] = null;
            RacmReviewerDetail::where('racm_id',$id)->delete();
            $requestData['status'] = 'Review Awaiting';
            $racm->update($requestData);
            Session::flash('msg', "RACM updated successfully");
            $response['success'] = true;
            $response['message'] = 'RACM updated successfully';
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
            $response['message'] = "RACM updated failure";
        }

        return response()->json($response);
    }

    /**
     * Delete Racm
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $responseData = [];

        try {
            $racm = RiskRacm::findOrFail($id);
            $racm->delete();
            Session::flash('msg', "RACM deleted successfully");
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "RACM deleted failure");
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }
    public function addRequestReviewerApprover(AddReviewerApproverRequest $request){
        $response = [];
        try {
            $approver = $request->input('approver');
            $reviewers = $request->input('reviewers');
            $racm_ids = $request->input('recordids');
            $racm_ids = explode(',',$racm_ids);
            RiskRacm::whereIn('id',$racm_ids)->update(['approver_id'=>$approver,'updated_at' => now(),'status'=>'Review Requested']);
            RacmReviewerDetail::whereIn('racm_id',$racm_ids)->delete();
            $data = [];
            foreach ($racm_ids as $racm_id) {
                foreach ($reviewers as $reviewer) {
                    $data[] = [
                        'racm_id' => $racm_id,
                        'reviewer_id' => $reviewer,
                        'updated_at' => now(),
                        'created_at' => now()
                    ];
                }
            }
            RacmReviewerDetail::insert($data);
            $review_emails = EmailNotification::where('module', 'riskmanagement')
                ->where('item', 'Review RACM Records')
                ->where('is_blocked', false)
                ->first();
            if($review_emails){
                $appUrl = BaseController::addSlashToEndOfURL();
                $data = [
                    'item' => $review_emails->item,
                    'email_content' => $review_emails->email_content,
                    'app_url' => $appUrl,
                    'racmids' => $request->input('recordids')
                ];
                if(count($reviewers)>0){
                    foreach($reviewers as $reviewer){
                        $user = User::findOrFail($reviewer);
                        if($user){
                            $data['reviewer'] = $user->name;
                            $data['reviewer_id'] = $user->id;
                            try {
                                Mail::to($user->email)->send(new RacmReviewRequest($data));
                            } catch (\Throwable $th) {
                                $this->error("Error while sending email: {$th->getMessage()}");
                            }
                        }

                    }
                }
                $review_userdatas = EmailNotificationsUserDetails::where('notification_id', $review_emails->id)->get();
                if (count($review_userdatas) > 0) {
                    $adminaddedusers = $review_userdatas->pluck('user_id')->toArray();
                    if (count($adminaddedusers)>0) {
                        foreach ($adminaddedusers as $adminaddeduser) {
                            $userdata = User::where('id', $adminaddeduser)->first();
                            try {
                                $data['reviewer'] = $$userdata->name;
                                $data['reviewer_id'] = $adminaddeduser;
                                Mail::to($userdata->email)->send(new RacmReviewRequest($data));
                            } catch (\Throwable $th) {
                                $this->error("Error while sending email: {$th->getMessage()}");
                            }
                        }
                    }
                }
            }
            $response['success'] = true;
            $response['message'] = 'Request has been sent successfully';
            Session::flash('msg', "Request has been sent successfully");
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
            $response['message'] = 'Request has not been sent successfully';
        }

        return response()->json($response);
    }
    public function reviewRacms($ids,$r){
        $model = ModenaDisable::first();
        $reviewer_id = User::where('email',Auth::user()->email)->value('id');
        if($reviewer_id==$r){
            $ids = explode(',',$ids);
            $riskAcms = RiskRacm::whereIn('id',$ids)->whereIn('status',['Review Requested','Reviewed'])->with(['department'])->orderBy('created_at', 'DESC')->get();
            $reviewer_id = User::where('email',Auth::user()->email)->value('id');
            $departments = $riskAcms->pluck('department.departmentname');
            $allDeptNames = implode(',', array_unique($departments->toArray()));
            $reviewerExists = RacmReviewerDetail::whereIn('racm_id',$ids)->where('reviewer_id',$reviewer_id)->exists();
            if($reviewerExists && count($riskAcms)>0){
                return view('user.risks.racm.review-request',compact( 'model', 'riskAcms', 'allDeptNames'));
            }
        }
        $riskAcms = RiskRacm::with(['department', 'reviewer', 'approver', 'postedBy'])->orderBy('created_at', 'DESC')->get();
        $departments = $riskAcms->pluck('department.departmentname');
        $allDeptNames = implode(',', array_unique($departments->toArray()));
        $users = User::where('is_email_verified',true)->where('approved',true)->get();
        return redirect()->route('racm.index')->with([
            'model' => $model,
            'riskAcms' => $riskAcms,
            'allDeptNames' => $allDeptNames,
            'users' => $users,
        ])->with('error', 'You are not authorized to access the review page, or another reviewer has already rejected the selected RACMs.');
    }
    public function reviewRacmsSubmit(Request $req){
        $reviewer_id = User::where('email',Auth::user()->email)->value('id');
        RacmReviewerDetail::whereIn('racm_id',$req->racm)->where('reviewer_id',$reviewer_id)->update([
            'reviewed_status' => true,
            'reviewed_date' => date('Y-m-d'),
            'racm_rejected' => false,
            'updated_at' => now(),
        ]);
        RiskRacm::whereIn('id',$req->racm)->update([
            'status' => "Reviewed",
            'updated_at' => now(),
        ]);
        $totalreviewed = RacmReviewerDetail::whereIn('racm_id',$req->racm)->where('reviewed_status',true)->where('racm_rejected',false)->pluck('reviewer_id')->unique()->toArray();
        $totalreviewers = RacmReviewerDetail::whereIn('racm_id',$req->racm)->pluck('reviewer_id')->unique()->toArray();
        if(count($totalreviewed)===count($totalreviewers)){
            RiskRacm::whereIn('id',$req->racm)->update([
                'status' => "Awaiting Approval",
                'updated_at' => now(),
            ]);
            $racms_ids = implode(',',$req->racm);
            $approver = RiskRacm::whereIn('id',$req->racm)->value('approver_id');
            $user = User::findOrFail($approver);
            $appUrl = BaseController::addSlashToEndOfURL();
            $approve_emails = EmailNotification::where('module', 'riskmanagement')
                ->where('item', 'Approve RACM Records')
                ->where('is_blocked', false)
                ->first();
            if($approve_emails){
                $data = [
                    'item' => $approve_emails->item,
                    'email_content' => $approve_emails->email_content,
                    'app_url' => $appUrl,
                    'racmids' => $racms_ids,
                    'approver' => $user->name
                ];
                if($user->email){
                    try {
                        Mail::to($user->email)->send(new RacmApprovalRequest($data));
                    } catch (\Throwable $th) {
                        $this->error("Failed to send email to: {$user->email}. Error: {$th->getMessage()}");
                    }
                }
                $approve_userdatas = EmailNotificationsUserDetails::where('notification_id', $approve_emails->id)->get();
                if (count($approve_userdatas) > 0) {
                    $adminaddedusers = $approve_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas)>0) {
                        try {
                            Mail::to($userdatas)->send(new RacmApprovalRequest($data));
                        } catch (\Throwable $th) {
                            $this->error("Failed to send email. Error: {$th->getMessage()}");
                        }

                    }
                }
            }
        }
        $model = ModenaDisable::first();
        $riskAcms = RiskRacm::with(['department', 'reviewer', 'approver', 'postedBy'])->orderBy('created_at', 'DESC')->get();
        $departments = $riskAcms->pluck('department.departmentname');
        $allDeptNames = implode(',', array_unique($departments->toArray()));
        $users = User::where('is_email_verified',true)->where('approved',true)->get();
        return redirect()->route('racm.index')->with([
            'model' => $model,
            'riskAcms' => $riskAcms,
            'allDeptNames' => $allDeptNames,
            'users' => $users,
        ]);
    }
    public function reviewRejectReason(Request $request){
        $response = [];
        try {
            $reason = $request->input('reason');
            $racm_ids = $request->input('recordids');
            $reviewer_id = User::where('email',Auth::user()->email)->value('id');
            RiskRacm::whereIn('id',$racm_ids)->update([
                'updated_at' => now(),'status'=>'Review Rejected'
            ]);
            RacmReviewerDetail::whereIn('racm_id',$racm_ids)->where('reviewer_id',$reviewer_id)->update([
                'reviewed_status' => true,
                'racm_rejected' => true,
                'racm_reject_reason' => $reason,
                'reviewed_date' => date('Y-m-d'),
                'updated_at' => now()
            ]);
            $response['success'] = true;
            $response['message'] = 'Risk RACMs have been rejected successfully.';
            Session::flash('msg', "Risk RACMs have been rejected successfully.");
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
            $response['message'] = 'Risk RACMs could not be rejected successfully.';
        }

        return response()->json($response);
    }
    public function approveRacms($ids){
        $model = ModenaDisable::first();
        $approver_id = User::where('email',Auth::user()->email)->value('id');
        $ids = explode(',',$ids);
        $riskAcms = RiskRacm::whereIn('id',$ids)->with(['department'])->orderBy('created_at', 'DESC')->get();
        $departments = $riskAcms->pluck('department.departmentname');
        $allDeptNames = implode(',', array_unique($departments->toArray()));
        $approverExists = RiskRacm::whereIn('id',$ids)->where('status','Awaiting Approval')->where('approver_id',$approver_id)->exists();
        if(!$approverExists){
            $riskAcms = RiskRacm::with(['department', 'reviewer', 'approver', 'postedBy'])->orderBy('created_at', 'DESC')->get();
            $users = User::where('is_email_verified',true)->where('approved',true)->get();
            return redirect()->route('racm.index')->with([
                'model' => $model,
                'riskAcms' => $riskAcms,
                'allDeptNames' => $allDeptNames,
                'users' => $users,
            ])->with('error', 'You are not authorized to access approval page.');
        }
        return view('user.risks.racm.approve-request',compact( 'model', 'riskAcms', 'allDeptNames'));
    }
    public function approveRacmsSubmit(Request $req){
        $approver_id = User::where('email',Auth::user()->email)->value('id');
        RiskRacm::whereIn('id',$req->racm)->where('approver_id',$approver_id)->update([
            'approved_by' => $approver_id,
            'approved_date' => date('Y-m-d'),
            'updated_at' => now(),
            'status' => 'Approved'
        ]);
        $model = ModenaDisable::first();
        $riskAcms = RiskRacm::with(['department', 'reviewer', 'approver', 'postedBy'])->orderBy('created_at', 'DESC')->get();
        $departments = $riskAcms->pluck('department.departmentname');
        $allDeptNames = implode(',', array_unique($departments->toArray()));
        $users = User::where('is_email_verified',true)->where('approved',true)->get();
        return redirect()->route('racm.index')->with([
            'model' => $model,
            'riskAcms' => $riskAcms,
            'allDeptNames' => $allDeptNames,
            'users' => $users,
        ]);
    }
    public function approveRejectReason(Request $request){
        $response = [];
        try {
            $reason = $request->input('reason');
            $racm_ids = $request->input('recordids');
            $approver_id = User::where('email',Auth::user()->email)->value('id');
            RiskRacm::whereIn('id',$racm_ids)->update([
                'approved_by'=>$approver_id,
                'approved_date' => date('Y-m-d'),
                'rejected_by_approver' => true,
                'reject_reason_by_approver' => $reason,
                'updated_at' => now(),'status'=>'Rejected'
            ]);
            $response['success'] = true;
            $response['message'] = 'Risk RACMs have been rejected successfully.';
            Session::flash('msg', "Risk RACMs have been rejected successfully.");
        } catch (\Exception $exception) {
            Log::error($exception);
            $response['success'] = false;
            $response['message'] = 'Risk RACMs could not be rejected successfully.';
        }

        return response()->json($response);
    }
}
