@extends('admin.nav')
@section('content')
<style>
    .empty-avatar {
        border: 2px solid #e1e6e9;
    }
    .not-found-module {
        display: none;
    }
</style>
<main id="main-container" style="margin-top:-10px;margin-left:22px;margin-right:22px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 style="font-size:15px" class="mb-0">User Roles (Authority)</h3>
        <a href="{{ url('/admin/viewUsers') }}" class="btn btn-sm btn-secondary">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="block">
        <div class="row justify-content-center" style="margin-top:-10px;">

            <div style="margin-left:40px;margin-top:50px;width:100%;margin-right:40px;">
                @foreach($users as $u)
                <?php $email = $u->email; ?>
                <?php $name = $u->name; ?>
                @endforeach
                <div class="card-header d-flex justify-content-between">
                    <h3 style="font-size:15px; margin-bottom: 0; margin-top: 1rem">{{$name}}</h3>
                    <div>
                        {{-- @if($isAdmin)
                            @if (
                                    isset($u->user->profile_picture)
                                    && file_exists(public_path('/storage/profile/'. $u->user->profile_picture))
                                )
                                <img src="{{ $u->user->avatar_path }}"
                                     class="avatar" alt="{{ $name }}">
                            @else
                                <img src="{{ asset('/images/avatar10.jpg') }}"
                                     class="avatar empty-avatar" alt="{{ $name }}">
                            @endif
                        @else --}}
                            @if (
                                    $u->profile_picture
                                    && file_exists(public_path('/storage/profile/'. $u->profile_picture))
                                )
                                <img src="{{ $u->avatar_path }}"
                                     class="avatar" alt="{{ $name }}">
                            @else
                                <img src="{{ $u->avatar_path }}"
                                     class="avatar empty-avatar" alt="{{ $name }}">
                            @endif
                        {{-- @endif --}}
                    </div>
                </div>
                <div class="responsive" style="margin-top: 1.25rem;">
                    <div>
                        <form action="{{ url('/admin/manageroles')}}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="email" value=<?php echo $email; ?>>
                            <input type="hidden" name="user_id" value="{{ $u->id }}">
                            <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                                <tr>
                                    <td colspan="5" align="center">
                                        <div class="d-flex justify-content-lg-between">
                                            <div>
                                                <select class="form-control  justify-content-center" name ="module" id="module" style="font-size: 13px;width:250px;">
                                                    <option value="" class="bg-light text-black">Select Module</option>
                                                    @foreach($modulenames as $key => $value)
                                                        <option value="{{$key}}" class="bg-light text-black"> {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <input type="reset" style="font-size:12px; padding: 6px 20px" class="btn btn-outline-primary" name="submit" value="Reset">
                                                <input type="submit" style="font-size:12px; padding: 6px 20px" class="btn btn-primary" name="submit" value="Update">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white">
                                    <th style="font-size:12px">Authority</th>
                                    <th style="font-size:12px">Authority Details</th>
                                    <th style="font-size:12px">Status</th>
                                </tr>
                                @if($model->complaintmanagement==1)
                                <tr style="background-color:#ededed" class="complaintmanagement-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Customer Complaint</span></td>

                                </tr>
                                <tr style="background-color:white" class="complaintmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Complaint Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All registered complaints can be reviewed</span></td>
                                    <td>
                                        @if(count($Complaintreviewer)>0)
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complaintreviewer" id="example-sw-custom-success2" class="custom-control-input" value="complaintreviewer" style="position: fixed;left: 600px" checked>
                                            <label class="custom-control-label" for="example-sw-custom-success2"></label>
                                        </div>
                                        @else
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complaintreviewer" id="example-sw-custom-success2" class="custom-control-input" value="complaintreviewer" style="position: fixed;left: 600px">
                                            <label class="custom-control-label" for="example-sw-custom-success2"></label>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="complaintmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Complaint Registrant</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To register any received customer complaints</span></td>
                                    <td>
                                        @if(count($Complaintregister)>0)
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complaintregister" id="example-sw-custom-success3" class="custom-control-input" value="complaintregister" style="position: fixed;left: 600px" checked>
                                            <label class="custom-control-label" for="example-sw-custom-success3"></label>
                                        </div>
                                        @else
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complaintregister" id="example-sw-custom-success3" class="custom-control-input" value="complaintregister" style="position: fixed;left: 600px">
                                            <label class="custom-control-label" for="example-sw-custom-success3"></label>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="complaintmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Get Complaint Notification</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To receive notification if any complaint registered in the system</span></td>
                                    <td>
                                        @if(count($Notifier)>0)
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="notification" value="notification" id="example-sw-custom-success4" class="custom-control-input" style="position: fixed;left: 600px" checked>
                                            <label class="custom-control-label" for="example-sw-custom-success4"></label>
                                        </div>
                                        @else
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="notification" value="notification" id="example-sw-custom-success4" class="custom-control-input" style="position: fixed;left: 600px">
                                            <label class="custom-control-label" for="example-sw-custom-success4"></label>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="complaintmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complaintDelete" value="complaintDelete" id="example-sw-custom-success5" class="custom-control-input" style="position: fixed;left: 600px" {{ $complaint_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success5"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="complaintmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complaintEdit" value="complaintEdit" id="example-sw-custom-success6" class="custom-control-input" style="position: fixed;left: 600px" {{ $complaint_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success6"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="complaintmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Responsibility Change</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To change responsibility</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complaintRC" value="complaintRC" id="example-sw-custom-success110" class="custom-control-input" style="position: fixed;left: 600px" {{ $complaint_RC ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success110"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @if($model->compliance==1)
                                <tr style="background-color:#ededed" class="compliance-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Compliance</span></td>

                                </tr>
                                <tr style="background-color:white" class="compliance-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Legal Coordinator</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To review legal requirements, hide and un hide any legal requirements, To evaluate compliance status, To change legal responsibility</span></td>
                                    <td>
                                        @if(count($Legalcoordinator)>0)
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="legalcoordinator" value="legalcoordinator" id="example-sw-custom-success15" class="custom-control-input" style="position: fixed;left: 600px" checked>
                                            <label class="custom-control-label" for="example-sw-custom-success15"></label>
                                        </div>
                                        @else
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="legalcoordinator" value="legalcoordinator" id="example-sw-custom-success15" class="custom-control-input" style="position: fixed;left: 600px">
                                            <label class="custom-control-label" for="example-sw-custom-success15"></label>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                {{-- <tr style="background-color:white" class="compliance-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Evaluate compliance Status</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To evaluate compliance status</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complianceEvaluate" value="complianceEvaluate" id="example-sw-custom-success80" class="custom-control-input" style="position: fixed;left: 600px" {{ $compliance_evaluate_status ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="example-sw-custom-success80"></label>
                                        </div>
                                    </td>
                                </tr> --}}
                                {{-- <tr style="background-color:white" class="compliance-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Change legal responsibility</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To change legal responsibility</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complianceChangeResp" value="complianceChangeResp" id="example-sw-custom-success81" class="custom-control-input" style="position: fixed;left: 600px" {{ $compliance_change_resp ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="example-sw-custom-success81"></label>
                                        </div>
                                    </td>
                                </tr> --}}

                                <tr style="background-color:white" class="compliance-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="complianceDelete" value="complianceDelete" id="example-sw-custom-success16" class="custom-control-input" style="position: fixed;left: 600px" {{ $compliance_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success16"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="compliance-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="complianceEdit" value="complianceEdit" id="example-sw-custom-success17" class="custom-control-input" style="position: fixed;left: 600px" {{ $compliance_edit ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success17"></label>
                                            </div>
                                        </td>
                                </tr>
                                @endif
                                @if($model->riskmanagement==1)
                                <tr style="background-color:#ededed" class="riskmanagement-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Enterprise Risk</span></td>

                                </tr>
                                <tr style="background-color:white" class="riskmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Risk Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To make initial review of any added risk</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="riskreviewer" value="riskreviewer" id="example-sw-custom-success10" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Riskreviewer)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success10"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="riskmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Escalate Risk Email Receiver</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To receive email while clicking risk escalation button</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="escalate" value="escalate" id="example-sw-custom-success11" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Escalateriskreceiver)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success11"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="riskmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Escalate Risk Button Access</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To escalate risk</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="escalateaccess" value="escalateaccess" id="example-sw-custom-success102" class="custom-control-input" style="position: fixed;left: 600px" {{ $risk_escalate_access ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success102"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="riskmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="deleteRisk" value="deleteRisk" id="example-sw-custom-success12" class="custom-control-input" style="position: fixed;left: 600px" {{ $risk_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success12"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="riskmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="editRisk" value="editRisk" id="example-sw-custom-success13" class="custom-control-input" style="position: fixed;left: 600px" {{ $risk_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success13"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="riskmanagement-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Change Risk Owner</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To change risk owner</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="changeriskowner" value="changeriskowner" id="example-sw-custom-success92" class="custom-control-input" style="position: fixed;left: 600px" {{ $risk_changeowner ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success92"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                @if($model->objectives==1)
                                <tr style="background-color:#ededed" class="objectives-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Objectives</span></td>

                                </tr>
                                <tr style="background-color:white" class="objectives-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Objectives Approver</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To approve any objective, modify objective description until approve</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="objapprover" value="objapprover" id="example-sw-custom-success14" class="custom-control-input" style="position: fixed;left: 600px" {{ $objapprover ?'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success14"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="objectives-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Objectives Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To review any objective, modify objective description until review</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="objreviewer" value="objreviewer" id="example-sw-custom-success18" class="custom-control-input" style="position: fixed;left: 600px" {{ $objreviewer ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success18"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="objectives-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="objectiveDelete" value="objectiveDelete" id="example-sw-custom-success19" class="custom-control-input" style="position: fixed;left: 600px" {{ $objective_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success19"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="objectives-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="objectiveEdit" value="objectiveEdit" id="example-sw-custom-success20" class="custom-control-input" style="position: fixed;left: 600px" {{ $objective_edit ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success20"></label>
                                            </div>
                                        </td>
                                    </tr>
                                <tr style="background-color:white" class="objectives-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Change Responsible Person</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To change responsible person</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="objective_change_res_person" value="objective_change_res_person" id="example-sw-custom-success101" class="custom-control-input" style="position: fixed;left: 600px" {{ $objective_change_resp_person ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success101"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="objectives-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Change Reviewer and Approver</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To Change reviewer and approver of an objective</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="objective_change_rev_app" value="objective_change_rev_app" id="example-sw-custom-success109" class="custom-control-input" style="position: fixed;left: 600px" {{ $objective_change_rev_approver ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success109"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @if($model->enviaspects==1)
                                <tr style="background-color:#ededed" class="enviaspects-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Environmental Aspects</span></td>

                                </tr>
                                <tr style="background-color:white" class="enviaspects-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Environmental Aspects Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To make initial review of any aspect added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="envireviewer" value="envireviewer" id="example-sw-custom-success21" class="custom-control-input" style="position: fixed;left: 600px" {{ count($envireviewer)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success21"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="enviaspects-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Periodic Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">For Periodic Review</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="env_secondary_review" value="env_secondary_review" id="example-sw-custom-success97" class="custom-control-input" style="position: fixed;left: 600px" {{ $enviaspect_secondary_review ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success97"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="enviaspects-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="enviDelete" value="enviDelete" id="example-sw-custom-success22" class="custom-control-input" style="position: fixed;left: 600px" {{ $enviaspect_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success22"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="enviaspects-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="enviEdit" value="enviEdit" id="example-sw-custom-success23" class="custom-control-input" style="position: fixed;left: 600px" {{ $enviaspect_edit ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success23"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if($model->opportunity==1)
                                <tr style="background-color:#ededed" class="opportunity-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Opportunity</span></td>

                                </tr>
                                <tr style="background-color:white" class="opportunity-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Opportunity Approver</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To approve any added opportunity</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="opportunityapprover" value="opportunityapprover" id="example-sw-custom-success24approver" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Opportunityapprover)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success24approver"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="opportunity-module module-wrap">

                                    <td><span class="label label-success" style="font-size:12px">Closure Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To closure review of any added opportunity</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="closurereviewer" value="closurereviewer" id="example-sw-custom-success24" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Closurereviewer)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success24"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="opportunity-module module-wrap">

                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="opportunityDelete" value="opportunityDelete" id="example-sw-custom-success25" class="custom-control-input" style="position: fixed;left: 600px" {{ $opportunity_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success25"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="opportunity-module module-wrap">

                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="opportunityEdit" value="opportunityEdit" id="example-sw-custom-success26" class="custom-control-input" style="position: fixed;left: 600px" {{ $opportunity_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success26"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="opportunity-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Responsibility Change</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To change responsibility</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="opportunityRC" value="opportunityRC" id="example-sw-custom-success91" class="custom-control-input" style="position: fixed;left: 600px" {{ $opportunity_RC ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success91"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                <tr style="background-color:#ededed" class="hse-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:HSE Observations</span></td>

                                </tr>
                                <tr style="background-color:white" class="hse-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">HSE Observation Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To make initial review of any added issue in ‘HSE Observation’ module; To review proposed actions; to close issue if the implementation status shown as 100 %</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="hse" value="hse" id="example-sw-custom-success27" class="custom-control-input" style="position: fixed;left: 600px" {{ $hse ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success27"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="hse-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="hseDelete" value="hseDelete" id="example-sw-custom-success28" class="custom-control-input" style="position: fixed;left: 600px" {{ $hse_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success28"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="hse-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="hseEdit" value="hseEdit" id="example-sw-custom-success29" class="custom-control-input" style="position: fixed;left: 600px" {{ $hse_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success29"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="hse-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Responsibility Change</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To change responsibility</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="hseRC" value="hseRC" id="example-sw-custom-success72" class="custom-control-input" style="position: fixed;left: 600px" {{ $hse_RC ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success72"></label>
                                            </div>
                                        </td>
                                    </tr>
                                <tr style="background-color:#ededed" class="correctiveaction-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:NCR & Corrective Actions</span></td>

                                </tr>
                                <tr style="background-color:white" class="correctiveaction-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Issue Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To make initial review of any added issue in ‘NCR & Corrective Actions’ module; To review proposed actions; to close issue if the implementation status shown as 100 %</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="issuereviewer" value="issuereviewer" id="example-sw-custom-success30" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Issuereviewer)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success30"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="correctiveaction-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="correctiveDelete" value="correctiveDelete" id="example-sw-custom-success31" class="custom-control-input" style="position: fixed;left: 600px" {{ $corrective_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success31"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="correctiveaction-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="correctiveEdit" value="correctiveEdit" id="example-sw-custom-success32" class="custom-control-input" style="position: fixed;left: 600px" {{ $corrective_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success32"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="correctiveaction-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Responsibility Change</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To change responsibility</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="correctiveRC" value="correctiveRC" id="example-sw-custom-success71" class="custom-control-input" style="position: fixed;left: 600px" {{ $corrective_RC ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success71"></label>
                                        </div>
                                    </td>
                                </tr>
                                @if($model->ohsrisk==1)
                                <tr style="background-color:#ededed" class="ohsrisk-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Health And Safety Risk</span></td>

                                </tr>
                                <tr style="background-color:white" class="ohsrisk-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Health and Safety In-Charge</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All health & Safety related privileges</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="health_safety" value="health_safety" id="example-sw-custom-success33" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Health_Safety)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success33"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="ohsrisk-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Periodic Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">For Periodic Review</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="ohs_secondary_review" value="ohs_secondary_review" id="example-sw-custom-success95" class="custom-control-input" style="position: fixed;left: 600px" {{ $ohs_secondary_review ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success95"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="ohsrisk-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="healthDelete" value="healthDelete" id="example-sw-custom-success34" class="custom-control-input" style="position: fixed;left: 600px" {{ $health_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success34"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="ohsrisk-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="healthEdit" value="healthEdit" id="example-sw-custom-success35" class="custom-control-input" style="position: fixed;left: 600px" {{ $health_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success35"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @if($model->documentmanagement==1)
                                <tr style="background-color:#ededed" class="documentmanagement-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Document Management</span></td>
                                </tr>
                                <tr style="background-color:white" class="documentmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Document Controller</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To approve document addition / deletion / change requests; to add / delete any document in document explorer</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="doccontroller" value="doccontroller" id="example-sw-custom-success36" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Doccontroller)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success36"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="documentmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="docDelete" value="docDelete" id="example-sw-custom-success37" class="custom-control-input" style="position: fixed;left: 600px" {{ $doc_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success37"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="documentmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="docEdit" value="docEdit" id="example-sw-custom-success38" class="custom-control-input" style="position: fixed;left: 600px" {{ $doc_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success38"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @if($model->auditmanagement==1)
                                <tr style="background-color:#ededed" class="auditmanagement-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Audit Management</span></td>

                                </tr>
                                <tr style="background-color:white" class="auditmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Auditor</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To add any issue in ‘Audit Management’ module; To review proposed actions; to close issue if the implementation status updated as 100 %</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="internalaudit" value="internalaudit" id="example-sw-custom-success39" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Internalaudit)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success39"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="auditmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="auditDelete" value="auditDelete" id="example-sw-custom-success40" class="custom-control-input" style="position: fixed;left: 600px" {{ $audit_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success40"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="auditmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="auditEdit" value="auditEdit" id="example-sw-custom-success41" class="custom-control-input" style="position: fixed;left: 600px" {{ $audit_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success41"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="auditmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Responsibility Change</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To change responsibility</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="auditRC" value="auditRC" id="example-sw-custom-success70" class="custom-control-input" style="position: fixed;left: 600px" {{ $audit_RC ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success70"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="auditmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Checklist Name Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit checklist name</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="audit_checklist_edit" value="audit_checklist_edit" id="example-sw-custom-success76" class="custom-control-input" style="position: fixed;left: 600px" {{ $audit_checklist_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success76"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="auditmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Checklist Name Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete checklist name</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="audit_checklist_delete" value="audit_checklist_delete" id="example-sw-custom-success77" class="custom-control-input" style="position: fixed;left: 600px" {{ $audit_checklist_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success77"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="auditmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Checklist Record Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit checklist records</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="audit_checklist_records_edit" value="audit_checklist_records_edit" id="example-sw-custom-success78" class="custom-control-input" style="position: fixed;left: 600px" {{ $audit_checklist_records_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success78"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="auditmanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Approve Checklist</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To approve checklist</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="audit_checklist_records_approve" value="audit_checklist_records_approve" id="example-sw-custom-success99" class="custom-control-input" style="position: fixed;left: 600px" {{ $audit_checklist_records_approve ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success99"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @if($model->managementreview==1)
                                <tr style="background-color:#ededed" class="managementreview-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Management Review</span></td>

                                </tr>
                                <tr style="background-color:white" class="managementreview-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Management Review Meeting Controls </span></td>
                                    <td><span class="label label-success" style="font-size:11px">Record Minutes, Add Action Plans, Change Schedule, Cancel the Meeting, Update Status</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="management_review_meeting" value="management_review_meeting" id="example-sw-custom-success96" class="custom-control-input" style="position: fixed;left: 600px" {{ $management_review_meeting ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success96"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="managementreview-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px"> Delete</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="management_review_meeting_delete" value="management_review_meeting_delete" id="example-sw-custom-success108" class="custom-control-input" style="position: fixed;left: 600px" {{ $management_review_meeting_delete ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success108"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                @if($model->incidentreport==1)
                                <tr style="background-color:#ededed" class="incidentreport-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Incident Report</span></td>

                                </tr>
                                <tr style="background-color:white" class="incidentreport-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Incident Report Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To review incident report if selected for review</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="increviewer" value="increviewer" id="example-sw-custom-success42" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Incidentreviewers)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success42"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="incidentreport-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Approver</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To approve incident report if selected for approval and to review closure of incident</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="incapprover" value="incapprover" id="example-sw-custom-success43" class="custom-control-input" style="position: fixed;left: 600px" {{ count($incidentapprover)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success43"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="incidentreport-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="incDelete" value="incDelete" id="example-sw-custom-success44" class="custom-control-input" style="position: fixed;left: 600px" {{ $incident_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success44"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="incidentreport-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="incEdit" value="incEdit" id="example-sw-custom-success45" class="custom-control-input" style="position: fixed;left: 600px" {{ $incident_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success45"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="incidentreport-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Change Reviewer and Approver</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To Change reviewer and approver of an incident and its investigation</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="incident_change_rev_app" value="incident_change_rev_app" id="example-sw-custom-success79" class="custom-control-input" style="position: fixed;left: 600px" {{ $incident_change_rev_approver ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success79"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if($model->hr==1)
                                <tr style="background-color:#ededed" class="hr-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:HR</span></td>

                                </tr>
                                <tr style="background-color:white" class="hr-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Training Coordinator</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To add / change training schedule / reports</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="trainingcoordinator" value="trainingcoordinator" id="example-sw-custom-success46" class="custom-control-input" style="position: fixed;left: 600px" {{ count($trainingcoordinators)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success46"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="hr-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="hrDelete" value="hrDelete" id="example-sw-custom-success47" class="custom-control-input" style="position: fixed;left: 600px" {{ $hr_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success47"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="hr-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="hrEdit" value="hrEdit" id="example-sw-custom-success48" class="custom-control-input" style="position: fixed;left: 600px" {{ $hr_edit ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success48"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if($model->vendormanagement==1)
                                <tr style="background-color:#ededed" class="vendormanagement-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Vendor</span></td>

                                </tr>
                                <tr style="background-color:white" class="vendormanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Add and evaluate vendors</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To add and evaluate any vendor</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="supplierapprover" value="supplierapprover" value="trainingcoordinator" id="example-sw-custom-success49" class="custom-control-input" style="position: fixed;left: 600px" {{ count($supplierapprovers)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success49"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="vendormanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Review</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To review the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="vendorReview" value="vendorReview" id="example-sw-custom-success93" class="custom-control-input" style="position: fixed;left: 600px" {{ $vendor_review ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success93"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="vendormanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Approve</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To approve the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="vendorApprove" value="vendorApprove" id="example-sw-custom-success94" class="custom-control-input" style="position: fixed;left: 600px" {{ $vendor_approve ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success94"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="vendormanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="vendorDelete" value="vendorDelete" id="example-sw-custom-success50" class="custom-control-input" style="position: fixed;left: 600px" {{ $vendor_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success50"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="vendormanagement-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="vendorEdit" value="vendorEdit" id="example-sw-custom-success51" class="custom-control-input" style="position: fixed;left: 600px" {{ $vendor_edit ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success51"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if($model->wastemanagement==1)
                                <tr style="background-color:#ededed" class="wastemanagement-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Waste Management</span></td>

                                </tr>
                                <tr style="background-color:white" class="wastemanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:13px">Waste Management Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To review request of waste management</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="wastereviewer" value="wastereviewer" id="example-sw-custom-success52" class="custom-control-input" style="position: fixed;left: 600px" {{ count($wastereviewer)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success52"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="wastemanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:13px">Waste Mangement Approver</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To approve waste management approval</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="wasteapprover" value="wasteapprover" id="example-sw-custom-success53" class="custom-control-input" style="position: fixed;left: 600px" {{ count($wasteapprover)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success53"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="wastemanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:13px">Disposal / Sales Request Authority</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To generate scrap / sales request</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="wastetransfer" value="wastetransfer" id="example-sw-custom-success90" class="custom-control-input" style="position: fixed;left: 600px" {{ count($wastetrfauthty)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success90"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="wastemanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:13px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="wasteDelete" value="wasteDelete" id="example-sw-custom-success54" class="custom-control-input" style="position: fixed;left: 600px" {{ $waste_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success54"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="wastemanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:13px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="wasteEdit" value="wasteEdit" id="example-sw-custom-success55" class="custom-control-input" style="position: fixed;left: 600px" {{ $waste_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success55"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="wastemanagement-module module-wrap">
                                    <td><span class="label label-success" style="font-size:13px">Delete Transfer Request</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete transfer request added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="wasteTrDelete" value="wasteTrDelete" id="example-sw-custom-success74" class="custom-control-input" style="position: fixed;left: 600px" {{ $waste_tr_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success74"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="wastemanagement-module module-wrap">
                                        <td><span class="label label-success" style="font-size:13px">Edit Transfer Request</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To edit transfer request added</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="wasteTrEdit" value="wasteTrEdit" id="example-sw-custom-success75" class="custom-control-input" style="position: fixed;left: 600px" {{ $waste_tr_edit ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success75"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if($model->calibration==1)
                                <tr style="background-color:#ededed" class="calibration-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Calibration</span></td>

                                </tr>
                                <tr style="background-color:white" class="calibration-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Calibration Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">Calibration Reviewer</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="calibrationreviewer" value="calibrationreviewer" id="example-sw-custom-success56" class="custom-control-input" style="position: fixed; left: 600px" {{ count($Calibrationreviewer)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success56"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="calibration-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Add Instrument</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To Create New Calibration Instrument</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="calibrationAdd" value="calibrationAdd" id="example-sw-custom-success57" class="custom-control-input" style="position: fixed; left: 600px" {{ $cal_add ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success57"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="calibration-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="calibrationDelete" value="calibrationDelete" id="example-sw-custom-success58" class="custom-control-input" style="position: fixed; left: 600px" {{ $cal_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success58"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="calibration-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="calibrationEdit" value="calibrationEdit" id="example-sw-custom-success58b" class="custom-control-input" style="position: fixed; left: 600px" {{ $cal_edit ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success58b"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if($model->nearmiss==1)
                                <tr style="background-color:#ededed" class="nearmiss-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Near Miss</span></td>

                                </tr>
                                <tr style="background-color:white" class="nearmiss-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Near Miss Reviewer</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To make initial review of any added issue in ‘Near Miss’ module; To review proposed actions; to close issue if the implementation status shown as 100 %</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="nearmiss" value="nearmiss" id="example-sw-custom-success59" class="custom-control-input" style="position: absolute;" {{ count($nearmiss)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success59"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="nearmiss-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="nearmissDelete" value="nearmissDelete" id="example-sw-custom-success60" class="custom-control-input" style="position: absolute;" {{ $nearmiss_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success60"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="nearmiss-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="editNearmiss" value="editNearmiss" id="example-sw-custom-success61" class="custom-control-input" style="position: absolute;" {{ $nearmiss_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success61"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="nearmiss-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Responsibility Change</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To change responsibility</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="nearmissRC" value="nearmissRC" id="example-sw-custom-success73" class="custom-control-input" style="position: fixed;left: 600px" {{ $nearmiss_RC ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success73"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if($model->occhealth==1)
                                <tr style="background-color:#ededed" class="occhealth-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Occupational Health</span></td>

                                </tr>
                                <tr style="background-color:white" class="occhealth-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Company Doctor</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To add data in “occupational health” module</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="doctor" value="doctor" id="example-sw-custom-success62" class="custom-control-input" style="position: fixed;left: 600px" {{ count($compdoctors)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success62"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="occhealth-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="doctorDelete" value="doctorDelete" id="example-sw-custom-success63" class="custom-control-input" style="position: fixed;left: 600px" {{ $doctor_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success63"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="occhealth-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="doctorEdit" value="doctorEdit" id="example-sw-custom-success64" class="custom-control-input" style="position: fixed;left: 600px" {{ $doctor_edit ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success64"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if($model->maintainance == 1)
                                <tr style="background-color:#ededed" class="maintainance-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:Maintenance</span></td>

                                </tr>
                                <tr style="background-color:white" class="maintainance-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Maintenance Incharge(Mechancial)</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To review added maintenance in mechanical</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="mainreviewer" value="mainreviewer" id="example-sw-custom-success65" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Mainreviewer)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success65"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="maintainance-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Maintenance Incharge(Electrical)</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To review added maintenance in electrical</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="mainelectrical" value="mainelectrical" id="example-sw-custom-success66" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Mainelectrical)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success66"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="maintainance-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Maintenance Incharge(Instrumentation)</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To review added maintenance in instrumentation</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="maininstrument" value="maininstrument" id="example-sw-custom-success67" class="custom-control-input" style="position: fixed;left: 600px" {{ count($Maininstrument)>0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success67"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="maintainance-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="deletemaintanance" value="deletemaintanance" id="example-sw-custom-success68" class="custom-control-input" style="position: fixed;left: 600px" {{ $maintenance_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success68"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="maintainance-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="editmaintanance" value="editmaintanance" id="example-sw-custom-success69" class="custom-control-input" style="position: fixed;left: 600px" {{ $maintenance_edit ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success69"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if($model->elearning == 1)
                                <tr style="background-color:#ededed" class="elearning-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:e-Learning</span></td>
                                </tr>
                                <tr style="background-color:white" class="elearning-module module-wrap">
                                        <td><span class="label label-success" style="font-size:12px">Add content</span></td>
                                        <td><span class="label label-success" style="font-size:11px">To add content</span></td>
                                        <td>
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="addcontent" value="addcontent" id="example-sw-custom-success98" class="custom-control-input" style="position: fixed;left: 600px" {{ $elearning_add_content ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="example-sw-custom-success98"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                <tr style="background-color:#ededed" class="other-module module-wrap">
                                    <td colspan="3"><span class="label label-success font-weight-bold" style="font-size:13px;">Module:All Modules</span></td>
                                </tr>
                                <tr style="background-color:white" class="other-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Management Representative</span></td>
                                    <td><span class="label label-success" style="font-size:11px">All privileges</span></td>
                                    <td>
                                        @if(count($Representative)>0)
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="managementrepresentative" id="example-sw-custom-success1" class="custom-control-input" value="managementrepresentative" style="position: fixed;left: 600px" checked>
                                                <label class="custom-control-label" for="example-sw-custom-success1"></label>
                                            </div>
                                        @else
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="managementrepresentative" id="example-sw-custom-success1" class="custom-control-input" value="managementrepresentative" style="position: fixed;left: 600px">
                                                <label class="custom-control-label" for="example-sw-custom-success1"></label>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="other-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Archive Privilege</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To archive any items who has archive button</span></td>
                                    <td>
                                        @if(count($Archiveprivilege)>0)
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="archiveprivilege" value="archiveprivilege" id="example-sw-custom-success7" class="custom-control-input" style="position: fixed;left: 600px" checked>
                                                <label class="custom-control-label" for="example-sw-custom-success7"></label>
                                            </div>
                                        @else
                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                <input type="checkbox" name="archiveprivilege" value="archiveprivilege" id="example-sw-custom-success7" class="custom-control-input" style="position: fixed;left: 600px">
                                                <label class="custom-control-label" for="example-sw-custom-success7"></label>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="other-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Delete</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To delete the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="deleteAll" value="deleteAll" id="example-sw-custom-success8" class="custom-control-input" style="position: fixed;left: 600px" {{ $all_delete ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success8"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="other-module module-wrap">
                                    <td><span class="label label-success" style="font-size:12px">Edit</span></td>
                                    <td><span class="label label-success" style="font-size:11px">To edit the item added</span></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" name="editAll" value="editAll" id="example-sw-custom-success9" class="custom-control-input" style="position: fixed;left: 600px" {{ $all_edit ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="example-sw-custom-success9"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color:white" class="not-found-module module-wrap">
                                    <td colspan="3" class="text-center"><span class="label label-success text-danger" style="font-size:11px">No matching records found</span></td>
                                </tr>
                            </table>
                        </form>
                        <!--<br>-->
                    </div>

                </div>
            </div>
        </div>
    </div>

</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(function () {
        $('#module').on('change', function () {
            if ($(this).val()) {
                $('.module-wrap').hide();
                if ($('.' + $(this).val() + '-module').length) {
                    $('.' + $(this).val() + '-module').show();
                } else {
                    $('.not-found-module').show();
                }
            } else {
                $('.module-wrap').show();
                $('.not-found-module').hide();
            }
        })
    })
</script>
@endsection
