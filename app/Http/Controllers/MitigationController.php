<?php

namespace App\Http\Controllers;

use App\Admin;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Mail\RiskManagementMitigationActionNotFullyImplemented;
use App\Mitigation;
use App\MitigationHistory;
use App\ModenaDisable;
use App\Risk;
use App\Riskreviewer;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\MitigationAttachment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;

class MitigationController extends Controller
{
    /**
     * MitigationController constructor
     */
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    /**
     * Get list mitigation
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $model = ModenaDisable::first();
        $riskId = $request->get('riskId');
        $mitigations = Mitigation::with(['user', 'department', 'risk', 'attachments'])
            ->where(function ($query) use ($riskId) {
                if ($riskId) {
                    $query->where('risk_id', $riskId);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $departments = $mitigations->pluck('department.departmentname');
        $owners = $mitigations->whereNotNull('owner')->pluck('user.name');
        $priorities = $mitigations->pluck('priority_level');
        $years = $mitigations->pluck('due_date')->map(function ($item) {
            return date('Y', strtotime($item));
        });
        $status = [
            'Not started - 0%',
            'In progress - 25%|50%|75%',
            'Completed - 100%',
            'Closed',
        ];
        $resourceRequired = $mitigations->pluck('resource_required');
        $allDeptNames = implode(',', array_unique($departments->toArray()));
        $allOwnerNames = implode(',', array_unique($owners->toArray()));
        $allPriorityLevel = implode(',', array_unique($priorities->toArray()));
        $allYears = implode(',', array_unique($years->toArray()));
        $allStatus = implode(',', array_unique($status));
        $allResourceRequired = implode(',', array_unique($resourceRequired->toArray()));
        $currentUserId = $this->getCurrentUserId();
        $reviewer = Riskreviewer::select('email')->get();
        $reviewerIds = [];

        if (count($reviewer)) {
            $reviewerEmails = $reviewer->pluck('email')->toArray();
            $reviewerAccounts = User::select('id')->whereIn('email', $reviewerEmails)->get();

            if (count($reviewerAccounts)) {
                $reviewerIds = $reviewerAccounts->pluck('id')->toArray();
            }
        }

        return view('user.risks.mitigation.index', compact(
             'mitigations', 'model', 'riskId',
            'allOwnerNames', 'allDeptNames', 'allPriorityLevel', 'allYears', 'allStatus',
            'allStatus', 'allResourceRequired', 'currentUserId', 'reviewerIds'
        ));
    }

    /**
     * Add Mitigation
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $mitigationData = $request->only(app(Mitigation::class)->getFillable());

            // General action id: STR2/2/23
            $riskId = $request->get('risk_id');
            $risk = Risk::where('Rid', $riskId)->firstOrFail();
            $currentYear = date('y');
            $numberOfMitigationOfRisk = Mitigation::where('risk_id', $riskId)->count('risk_id');
            $mitigationData['action_id'] = $risk->grp_id . '/' . ($numberOfMitigationOfRisk + 1) . '/' . $currentYear;
            $mitigationData['link_other_risk'] = $this->getLinkOtherRiskData($mitigationData['link_other_risk']);
            $mitigationData['created_by'] = $this->getCurrentUserId();

            $mitigation = Mitigation::create($mitigationData);
            if ($request->hasFile('attachment')) {
                $this->attachFile($request->file('attachment'), $mitigation->id);
            }
            Session::flash('msg', "Mitigating added successfully");

            return redirect()->route('mitigations.index');
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "Mitigating added failure");

            return redirect()->route('risk.index');
        }
    }

    /**
     * Edit mitigation
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
            $mitigation = Mitigation::findOrFail($id);
            if ($mitigation->status == config('constants.risk_management.mitigation.closed')) {
                $responseData['success'] = false;
            } else {
                $mitigation->due_date = date('Y-m-d', strtotime($mitigation->due_date));
                //$actionOwners = User::pluck('name', 'id')->toArray();
                $actionOwners = User::where('is_email_verified',true)->orderBy('name', 'asc')->where('approved',true)->pluck('name', 'id')->toArray();
                $departments = \DB::table('departments')->get();
                $riskId = $mitigation->risk_id;
                $mitigationLinkOtherRisks = json_decode($mitigation->link_other_risk, true);
                $deletedRisks = collect();
                if ($mitigationLinkOtherRisks && count($mitigationLinkOtherRisks)) {
                    $deletedRisks = Risk::onlyTrashed()->select('Rid', 'grp_id', 'riskname')
                            ->whereIn('Rid',array_keys($mitigationLinkOtherRisks))->get();
                }
                $data = Risk::select('Rid', 'grp_id', 'riskname')->get();
                $responseData['success'] = true;
                $responseData['risk_id'] = $riskId;
                $responseData['view'] = \view('user.risks.mitigation._form', compact(
                    'mitigation', 'actionOwners', 'departments', 'riskId', 'data','deletedRisks'
                ))->render();
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Update Mitigation
     *
     * @param $id
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function update($id, Request $request): RedirectResponse
    {
        try {
            $mitigationData = $request->only(app(Mitigation::class)->getFillable());
            $mitigationData['updated_by'] = $this->getCurrentUserId();
            $mitigation = Mitigation::findOrFail($id);
            if (isset($mitigationData['status'])) {
                if ($mitigationData['status'] == config('constants.risk_management.mitigation.statuses_mapping_progresses.not_started')) {
                    $mitigationData['progress'] = 0;
                }
                if ($mitigationData['status'] == config('constants.risk_management.mitigation.statuses_mapping_progresses.completed')) {
                    $mitigationData['progress'] = 100;
                }
            }

            if ($request->get('process')) {
                $progress = $request->get('process');
                $statusOfProgress = config('constants.risk_management.mitigation.statuses_mapping_progresses');
                $progressPercent = config('constants.risk_management.mitigation.progress_percent');
                $mitigationData['status'] = isset($statusOfProgress[$progress]) ? $statusOfProgress[$progress] : '';
                $mitigationData['progress'] = isset($progressPercent[$progress]) ? $progressPercent[$progress] : 0;
            }
            if (isset($mitigationData['link_other_risk'])) {
                $mitigationData['link_other_risk'] = $this->getLinkOtherRiskData($mitigationData['link_other_risk']);
            }
            $mitigation->update($mitigationData);
            if ($request->hasFile('attachment')) {
                $this->attachFile($request->file('attachment'), $mitigation->id);
            }
            Session::flash('msg', "Mitigating updated successfully");
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "Mitigating updated failure");
        }

        return redirect()->route('mitigations.index');
    }

    /**
     * @param $riskIds
     *
     * @return false|string
     */
    protected function getLinkOtherRiskData($riskIds)
    {
        $risks = Risk::withTrashed()->whereIn('Rid', $riskIds)->get();
        $riskData = [];
        foreach ($risks as $risk) {
            $riskData[$risk->Rid] = $risk->riskname . '-' . $risk->grp_id;
        }

        return json_encode($riskData);
    }

    /**
     * @param $attachment
     * @param $mitigationId
     *
     * @return void
     */
    protected function attachFile($attachment, $mitigationId)
    {
        $attachmentName = $attachment->getClientOriginalName();

        $destination_path = public_path('storage/mitigation');
        $attachment->move($destination_path, $attachmentName);
        MitigationAttachment::create([
            'attachment' => $attachmentName,
            'mitigation_id' => $mitigationId,
        ]);
    }

    /**
     * Delete Mitigation
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $responseData = [];

        try {
            $mitigation = Mitigation::with('attachments')->findOrFail($id);
            if ($mitigation->status == config('constants.risk_management.mitigation.closed')) {
                $responseData['success'] = false;
                Session::flash('errmsg', "Mitigating deleted failure");
            } else {
                foreach ($mitigation->attachments as $attachment) {
                    if (Storage::disk('public')->exists('mitigation/' . $attachment->attachment)) {
                        Storage::disk('public')->delete('mitigation/' . $attachment->attachment);
                    }
                }

                $mitigation->attachments()->delete();
                $mitigation->delete();
                Session::flash('msg', "Mitigating deleted successfully");
                $responseData['success'] = true;
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "Mitigating deleted failure");
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * Change progress
     *
     * @param $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function changeProgress($id, Request $request): JsonResponse
    {
        $responseData = [];

        try {
            $mitigation = Mitigation::with('user', 'createdBy', 'department')->findOrFail($id);
            $progress = $request->get('progress');
            $updateData = [];
            $statusOfProgress = config('constants.risk_management.mitigation.statuses_mapping_progresses');
            $progressPercent = config('constants.risk_management.mitigation.progress_percent');
            $updateData['status'] = isset($statusOfProgress[$progress]) ? $statusOfProgress[$progress] : '';
            $updateData['progress'] = isset($progressPercent[$progress]) ? $progressPercent[$progress] : 0;
            $updateData['updated_by'] = $this->getCurrentUserId();

            DB::beginTransaction();
            if ($updateData) {
                $mitigation->update($updateData);
            }
            if ($request->get('is_send_mail')) {
                $createdPerson = $mitigation->createdBy ? $mitigation->createdBy->email : '';
                $owner = $mitigation->user ? $mitigation->user->email : '';
                $this->sendMailChangeProcess($owner, $createdPerson, $progress, [
                    'action_id' => $mitigation->action_id,
                    'department' => $mitigation->department ? $mitigation->department->departmentname : '',
                    'owner' => $mitigation->user ? $mitigation->user->name : '',
                    'due_date' => $mitigation->due_date,
                ]);
            }
            DB::commit();
            Session::flash('msg', "Change status of mitigating successfully");
            $responseData['success'] = true;
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
            Session::flash('errmsg', "Change status of mitigating failure");
            $responseData['success'] = false;
        }

        return response()->json($responseData);
    }

    /**
     * @param $owner
     * @param $createdPerson
     * @param $progress
     * @param array $additionData
     * @return void
     */
    protected function sendMailChangeProcess($owner, $createdPerson, $progress, array $additionData = [])
    {
        $emailTemplate = EmailNotification::where('module', 'riskmanagement')
            ->where('item', 'Mitigation action not fully implemented')
            ->where('is_blocked', false)
            ->first();

        if ($emailTemplate) {
            $additionUserIds = EmailNotificationsUserDetails::select('user_id')
                ->where('notification_id', $emailTemplate->id)
                ->get();
            $emailOfAdditionUsers = [];
            if (count($additionUserIds)) {
                $additionUsers = User::select('email')
                    ->whereIn('id', $additionUserIds->pluck('user_id')->toArray())->get();

                if (count($additionUsers)) {
                    $emailOfAdditionUsers = $additionUsers->pluck('email')->toArray();
                }
            }
            $data['email_content'] = $emailTemplate->email_content;
            $data['new_process'] = config('constants.risk_management.mitigation.progresses')[$progress];
            $data['action_id'] = $additionData['action_id'] ?? '';
            $data['department'] = $additionData['department'] ?? '';
            $data['owner'] = $additionData['owner'] ?? '';
            $data['due_date'] = $additionData['due_date'] ?? '';
            $receivers = [];
            if ($owner) {
                $receivers[] = $owner;
            }
            if ($createdPerson) {
                $receivers[] = $createdPerson;
            }
            if (count($emailOfAdditionUsers)) {
                $receivers = array_merge($receivers, $emailOfAdditionUsers);
            }

            if (count($receivers)) {
                Log::info('[Risk management][Mitigation action][Closure review] Send mail change process');
                Log::info('[Risk management][Mitigation action][Closure review] Receivers: ' . implode(',', $receivers));
                Mail::to(array_unique($receivers))->send(new RiskManagementMitigationActionNotFullyImplemented($data));
            } else {
                Log::info('[Risk management][Mitigation action][Closure review] Send mail change process: Receiver list not found');
            }
        } else {
            Log::info('[Risk management][Mitigation action][Closure review] Send mail change process: Template not found');
        }
    }

    /**
     * @return int
     */
    protected function getCurrentUserId(): int
    {
        $currentUser = auth()->user();

        $admin = Admin::where('id', $currentUser->id)->first();

        if ($admin) {
            $user = User::select('id')->where('email', '=', $admin->email)->first();
            $userId = $user ? $user->id : 0;
        } else {
            $userId = $currentUser->id;
        }

        return $userId;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|RedirectResponse|View
     */
    public function closureReview($id)
    {
        try {
            $model = ModenaDisable::first();
            $mitigation = Mitigation::with('risk')->findOrFail($id);
            $mitigation->due_date = date('Y-m-d', strtotime($mitigation->due_date));
            $mitigationLinkOtherRisks = json_decode($mitigation->link_other_risk, true);
            $otherRisks = null;
            $deletedRisks = collect();
            if ($mitigationLinkOtherRisks && count($mitigationLinkOtherRisks)) {
                $otherRisks = Risk::whereIn('Rid', array_keys($mitigationLinkOtherRisks))
                    ->where('Rid', '!=', $mitigation->risk_id)->withTrashed()
                    ->get();
                $deletedRisks = Risk::onlyTrashed()->select('Rid', 'grp_id', 'riskname')
                        ->whereIn('Rid',array_keys($mitigationLinkOtherRisks))->get();
            }

            $riskId = $mitigation->risk_id;
            $data = Risk::select('Rid', 'grp_id', 'riskname')->get();

            return view('user.risks.mitigation.closurereview', compact(
                'mitigation', 'model','deletedRisks',
                'riskId', 'data', 'otherRisks'
            ));
        } catch (\Exception $exception) {
            Log::error($exception);
            Log::error($exception);
            Session::flash('errmsg', "Get closure review page error");

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function closeMitigation($id, Request $request): RedirectResponse
    {
        try {
            $mitigation = Mitigation::findOrFail($id);
            $likelihoods = $request->get('likelihoods');
            $orgLikelihoods = $request->get('org_likelihood');
            $impacts = $request->get('impacts');
            $orgImpacts = $request->get('org_impact');
            $ranks = $request->get('ranks');
            $orgRanks = $request->get('org_ranks');
            $updateData = [];
            $currentTime = now();

            $mitigationHistoryData = [];
            if($likelihoods){
                foreach ($likelihoods as $riskId => $value) {
                    $likelihood = $this->calculatedValue($value);
                    $impact = isset($impacts[$riskId]) ? $this->calculatedValue($impacts[$riskId]) : 'Low';
                    $rank = $ranks[$riskId] ?? 'Low';
                    $updateData[$riskId] = [
                        'like' => $likelihood,
                        'impa' => $impact,
                        'rank' => $rank,
                        'updated_at' => now()
                    ];
                    $mitigationHistoryData[] = [
                        'risk_id' => $riskId,
                        'mitigation_id' => $id,
                        'existing_likelihood' => $orgLikelihoods[$riskId] ?? null,
                        'existing_impact' => $orgImpacts[$riskId] ?? null,
                        'existing_risk' => $orgRanks[$riskId] ?? null,
                        'new_likelihood' => $value,
                        'new_impact' => $impacts[$riskId] ?? null,
                        'new_risk' => $rank,
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                    ];
                }
            }
            if (count($updateData)) {
                foreach ($updateData as $riskId => $data) {
                    Risk::where('Rid', $riskId)->update($data);
                }
            }
            if (count($mitigationHistoryData)) {
                MitigationHistory::insert($mitigationHistoryData);
            }
            $mitigation->update([
                'status' => config('constants.risk_management.mitigation.closed'),
                'reviewed_by' =>  $this->getCurrentUserId(),
                'reviewed_date' =>  now(),
            ]);
            Session::flash('msg', "Mitigation closed successfully");
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "Mitigation closed failure");
        }

        return redirect()->route('mitigations.index');
    }

    /**
     * @param $number
     * @return string
     */
    protected function calculatedValue($number): string
    {
        if ($number >= 3) {
            return 'High';
        }

        if ($number >= 2) {
            return 'Moderate';
        }

        return 'Low';
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|RedirectResponse|View
     */
    public function reopen($id)
    {
        try {
            $model = ModenaDisable::first();
            $mitigation = Mitigation::with('risk')->findOrFail($id);
            $mitigation->due_date = date('Y-m-d', strtotime($mitigation->due_date));
            $mitigationLinkOtherRisks = json_decode($mitigation->link_other_risk, true);
            $otherRisks = null;
            $deletedRisks = collect();
            if ($mitigationLinkOtherRisks && count($mitigationLinkOtherRisks)) {
                $otherRisks = Risk::whereIn('Rid', array_keys($mitigationLinkOtherRisks))
                    ->where('Rid', '!=', $mitigation->risk_id)->withTrashed()
                    ->get();
                $deletedRisks = Risk::onlyTrashed()->select('Rid', 'grp_id', 'riskname')
                        ->whereIn('Rid',array_keys($mitigationLinkOtherRisks))->get();
            }

            //$actionOwners = User::pluck('name', 'id')->toArray();
            $actionOwners = User::where('is_email_verified',true)->where('approved',true)->pluck('name', 'id')->toArray();
            $departments = \DB::table('departments')->get();
            $riskId = $mitigation->risk_id;
            $data = Risk::select('Rid', 'grp_id', 'riskname')->get();



            return view('user.risks.mitigation.reopen', compact(
                'mitigation', 'model', 'actionOwners','deletedRisks',
                'departments', 'riskId', 'data', 'otherRisks'
            ));
        } catch (\Exception $exception) {
            Log::error($exception);
            Session::flash('errmsg', "Get reopen mitigation page error");

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function detail($id)
    {
        $model = ModenaDisable::first();
        $mitigation = Mitigation::with([
            'risk', 'department', 'attachments',
            'user', 'createdBy', 'reviewedBy'
        ])->findOrFail($id);
        $posted_user_image = DB::table('users')->where('id', $mitigation->created_by)->value('profile_picture');
        $otherRisks = null;

        if ($mitigation->link_other_risk) {
            $linkOtherRisk = json_decode($mitigation->link_other_risk, true);
            $riskIds = array_keys($linkOtherRisk);
            $otherRisks = Risk::withTrashed()->with('mitigationHistories')->whereIn('Rid', $riskIds)->get();
        }
        return view('user.risks.mitigation.detail', compact('model', 'mitigation', 'posted_user_image', 'otherRisks'));
    }

    public function detailPdf($id)
    {
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        $model = ModenaDisable::first();
        $mitigation = Mitigation::with([
            'risk', 'department', 'attachments',
            'user', 'createdBy', 'reviewedBy'
        ])->findOrFail($id);
        $posted_user_image = DB::table('users')->where('id', $mitigation->created_by)->value('profile_picture');
        $otherRisks = null;

        if ($mitigation->link_other_risk) {
            $linkOtherRisk = json_decode($mitigation->link_other_risk, true);
            $riskIds = array_keys($linkOtherRisk);
            $otherRisks = Risk::withTrashed()->with('mitigationHistories')->whereIn('Rid', $riskIds)->get();
        }

        $pdf = \PDF::loadView('user.risks.mitigation.detail_pdf', compact('model', 'mitigation', 'posted_user_image', 'otherRisks'));
        return $pdf->download('detail_' . $mitigation->id . '.pdf');
    }

}
