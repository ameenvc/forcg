@extends('admin.nav')
@section('css_after')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
<style>
    .qnsstyle {
    font-weight: bold;
}


.radiobtn input[type="radio"] {
    margin: 0;
    padding: 0;
}


@media (max-width: 575.98px) {
    .qnsstyle {
        font-size: 13px;
    }
}

@media (min-width: 576px) and (max-width: 767.98px) {
    .qnsstyle {
        font-size: 14px;
    }
}

@media (min-width: 768px) and (max-width: 991.98px) {
    .qnsstyle {
        font-size: 16px;
    }
}

@media (min-width: 992px) and (max-width: 1199.98px) {
    .qnsstyle {
        font-size: 16px;
    }
}

@media (min-width: 1200px) and (max-width: 1399.98px) {
    .qnsstyle {
        font-size: 16px;
    }
}

@media (min-width: 1400px) {
    .qnsstyle {
        font-size: 16px;
    }
}
</style>
<main id="main-container" style="margin-top:-10px;margin-left:22px;margin-right:22px;">
    <!-- <h1>Responsible modules</h1> -->

    <div class="block">
     <!-- user deletion confirm starts -->
            <div class="row justify-content-left px-0" style="margin-top: -10px;margin-bottom: 25px;">
                <div class="d-flex" style="margin-left:40px;margin-top:50px;width:100%;margin-right:40px;">
                    <label class="qnsstyle mb-0">
                        Do you want to assign all responsibilities to another user?
                    </label>
                    <div class="radiobtn d-flex align-items-center mx-2" style="gap: 10px;">
                        <div class="d-flex align-items-center" style="gap: 5px;">
                            <input type="radio" value="user_delete_yes" id="user_delete_yes" name="user_delete_select" onclick="displayuserdeletemodules()">
                            <label for="user_delete_yes" class="qnsstyle mb-0">YES</label>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 5px;">
                            <input type="radio" value="user_delete_no" id="user_delete_no" name="user_delete_select" onclick="displayuserdeletemodules()">
                            <label for="user_delete_no" class="qnsstyle mb-0">NO</label>
                        </div>
                    </div>
                </div>
            </div>

    <!-- user deletion -assign responsiblities-no starts -->
    <div class="row justify-content-center" style="margin-top:-10px;display:none;" id="user_delete_selected_no">
            <div style="margin-left:40px;margin-top:50px;width:100%;margin-right:40px;">
               <div class="row ml-auto" style="position: relative;bottom:20px;">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12 text-primary qnsstyle px-0">
                        You are about to delete <span class="text-danger"><b>{{ucfirst($user->name)}}</b></span> from the system.
                        Deleting this user will remove their access and may require reassignment of their responsibilities
                    </div>
               </div>

            <!-- display reponsibility to user starts -->
            <div>
                @if($show_common_module)
                        <!-- <h3>Common Modules</h3> -->
                        @if(count($deps))
                        <p>Departments</p>
                        <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                            <tr style="background-color:white">
                                <!-- <th style="font-size:12px">id</th> -->
                                <th style="font-size:12px">Department</th>
                                <th style="font-size:12px">HOD name</th>
                                <th style="font-size:12px">HOD email</th>
                                <th style="font-size:12px">code</th>
                            </tr>
                            @php $i=1; @endphp
                            @foreach($deps as $dep)
                            <tr style="background-color:white">
                                <!-- <td><span class="label label-success" style="font-size:12px">{{ $dep->id}}</span></td> -->
                                <td width="15%"><span class="label label-success" style="font-size:11px">{{ ucfirst($dep->departmentname) }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">{{ $dep->hodname }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">{{ $dep->hod }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">{{ $dep->code }}</span></td>
                            </tr>
                            @php $i++; @endphp

                            @endforeach
                        </table>
                        @endif
                        @if(count($divisions))
                        <p>Divisions</p>
                        <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                            <tr style="background-color:white">
                                <th style="font-size:12px">Division</th>
                                <th style="font-size:12px">owner</th>
                                <th style="font-size:12px">owner email</th>
                            </tr>
                            @foreach($divisions as $division)
                            <tr style="background-color:white">
                                <td width="15%"><span class="label label-success" style="font-size:11px">{{ $division->name }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">{{ ucfirst($user->name) }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                            </tr>
                            @endforeach
                        </table>
                        @endif
                        @if(count($areas))
                            <p>Sub Divisions</p>
                            <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                                <tr style="background-color:white">
                                    <th style="font-size:12px">Sub Division</th>
                                    <th style="font-size:12px">owner</th>
                                    <th style="font-size:12px">owner email</th>
                                </tr>
                                @foreach($areas as $area)
                                <tr style="background-color:white">
                                    <td width="15%"><span class="label label-success" style="font-size:11px">{{ $area->name }}</span></td>
                                    <td><span class="label label-success" style="font-size:11px">{{ ucfirst($user->name) }}</span></td>
                                    <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                </tr>
                                @endforeach
                            </table>
                            @endif
                        @endif
                        <x-user-delete-single-responsibility-notify :item="$representative" :item-users="$representative_users" :item-text="$representative_text" />
                        @if($show_audit_module)
                        <h3>Audit Management</h3>
                        @if($auditor)
                        <p>Auditors</p>
                            <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                                <tr style="background-color:white">
                                    <th style="font-size:12px">name</th>
                                    <th style="font-size:12px">email</th>
                                </tr>
                                <tr style="background-color:white">
                                    <td><span class="label label-success" style="font-size:11px">{{ $auditor->auditorsname }}</span></td>
                                    <td><span class="label label-success" style="font-size:11px">{{ $auditor->email }}</span></td>
                                </tr>
                            </table>
                        @endif
                        @if(count($auditees))
                        <p>Auditees</p>
                        <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                            <tr style="background-color:white">
                                <th style="font-size:12px">responsibility</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                            </tr>
                        </table>
                        @endif
                        <x-user-delete-multiple-responsibility-notify :item="$issues" :item-text="$issue_text" :item-name="$issue_name"  :users="$users" :user="$user->email" />
                        <x-user-delete-single-responsibility-notify :item="$internalaudit" :item-users="$internalaudit_users" :item-text="$internalaudit_text"  />
                        <x-user-delete-single-responsibility-notify :item="$archiveprivilege" :item-users="$archiveprivilege_users" :item-text="$archiveprivilege_text"  />
                        @endif
                        @if($show_corrective_module)
                            <h3>NCR & Corrective Actions</h3>
                            <x-user-delete-single-responsibility-notify :item="$Issuereviewer" :item-users="$Issuereviewer_users" :item-text="$issuereviewer_text"  />
                            <x-user-delete-multiple-responsibility-notify :item="$correctives" :item-text="$corrective_text" :item-name="$corrective_name" :users="$users" :user="$user->email" />
                            <x-user-delete-multiple-responsibility-notify :item="$corrective_correction_responsibilities" :item-text="$corrective_correction_text" :item-name="$corrective_correction_name"  :users="$users" :user="$user->email" />
                            @if(count($lead_correctives))
                            <table class="table table-bordered table-striped table-vcenter">
                                <tr style="background-color:white">
                                    <th style="font-size:12px">Investigation Team Lead</th>
                                </tr>
                                <tr style="background-color:white">
                                    <td><span class="label label-success" style="font-size:11px">{{ $user->name }}</span></td>
                                </tr>
                            </table>
                            @endif
                        @endif
                        @if($show_risk_module)
                            <h3>Risk</h3>
                            <x-user-delete-single-responsibility-notify :item="$riskreviewer" :item-users="$riskreviewer_users" :item-text="$riskreviewer_text" />
                            <x-user-delete-single-responsibility-notify :item="$escalateriskreceiver" :item-users="$escalateriskreceiver_users" :item-text="$escalateriskreceiver_text" />
                            @if(count($risks) > 0)
                                <p>Risks</p>
                                <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                                    <tr style="background-color:white">
                                        <th style="font-size:12px">risk_owner</th>
                                    </tr>
                                    <tr style="background-color:white">
                                        <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                    </tr>
                                </table>
                            @endif
                            @if(count($mitigations))
                                <p>Mitigations</p>
                                <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                                    <tr style="background-color:white">
                                        <th style="font-size:12px">owner</th>
                                    </tr>
                                    <tr style="background-color:white">
                                        <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                    </tr>
                                </table>
                            @endif
                            <x-user-delete-single-responsibility-notify :item="$racmreviewer" :item-users="$racmreviewer_users" :item-text="$racmreviewer_text" />
                            <x-user-delete-single-responsibility-notify :item="$racmapprover" :item-users="$racmapprover_users" :item-text="$racmapprover_text" />
                        @endif
                        @if($show_document_module)
                            <h3>Document</h3>
                            <x-user-delete-single-responsibility-notify :item="$doccontroller" :item-users="$doccontroller_users" :item-text="$doccontroller_text" />
                            @if(count($dcrs))
                            <p>Dcr</p>
                            <table class="table table-bordered table-striped table-vcenter">
                                <tr style="background-color:white">
                                    <th style="font-size:12px">hod</th>
                                </tr>
                                <tr style="background-color:white">
                                    <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                </tr>
                            </table>
                            @endif
                            @if(count($masterdocs))
                            <p>Masterdoc</p>
                            <table class="table table-bordered table-striped table-vcenter">
                                <tr style="background-color:white">
                                    <th style="font-size:12px">hod</th>
                                </tr>
                                <tr style="background-color:white">
                                    <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                </tr>
                            </table>
                            @endif
                            @if(count($document_approvers))
                            <p>Document approvers</p>
                            <table class="table table-bordered table-striped table-vcenter">
                                <tr style="background-color:white">
                                    <th style="font-size:12px">approver</th>
                                </tr>
                                <tr style="background-color:white">
                                    <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                </tr>
                            </table>
                            @endif
                            @if(count($document_reviewers))
                            <p>Document reviewers</p>
                            <table class="table table-bordered table-striped table-vcenter">
                                <tr style="background-color:white">
                                    <th style="font-size:12px">reviewers</th>
                                </tr>
                                <tr style="background-color:white">
                                    <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                </tr>
                            </table>
                            @endif
                        @endif
                        @if($show_opportunity_module)
                            <h3>Opportunity</h3>
                            @if(count($opportunities) > 0)
                            <p>Opportunities</p>
                            <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                                <tr style="background-color:white">
                                    <th style="font-size:12px">responsibility</th>
                                </tr>
                                @php
                                $j = 0;
                                $responsibilities = [];

                                foreach($opportunities as $o) {
                                    $responsibilities[$j] = $o->responsibility;
                                    $j++;
                                }
                                $responsibilities = array_unique($responsibilities);
                                $responsibilities = implode(',', $responsibilities);
                                @endphp
                                <tr style="background-color:white">
                                    <td><span class="label label-success" style="font-size:11px">{{ $responsibilities }}</span></td>
                                </tr>
                            </table>
                            @endif
                            <x-user-delete-single-responsibility-notify :item="$closurereviewer" :item-users="$closurereviewer_users" :item-text="$closurereviewer_text"  />
                        @endif
                        @if($show_objective_module)
                            <h3>Objective</h3>
                            <x-user-delete-single-responsibility-notify :item="$objective_reviewer" :item-users="$objective_reviewer_users" :item-text="$objective_reviewer_text" :item-select="$objective_reviewer_select" />
                            <x-user-delete-single-responsibility-notify :item="$objective_approver" :item-users="$objective_approver_users" :item-text="$objective_approver_text" :item-select="$objective_approver_select" />
                            <x-user-delete-single-responsibility-notify :item="$management" :item-users="$management_users" :item-text="$management_text"  />
                            @if(count($objective_resp_items))
                            <p>Responsibilities</p>
                            <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                                <tr style="background-color:white">
                                    <th style="font-size:12px">responsibility</th>
                                </tr>
                                <tr style="background-color:white">
                                    <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                </tr>
                            </table>
                            @endif
                        @endif
                        @if($show_incident_module)
                            <h3>Incident</h3>
                            @if($incident_leader)
                            <p>Incident Leader</p>
                            <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                                <tr style="background-color:white">
                                    <th style="font-size:12px">team leader</th>
                                    <th style="font-size:12px">report no</th>
                                </tr>
                                <input type="hidden" name="incident_report_no" value="{{$incident_leader->report_no}}" />
                                <tr style="background-color:white">
                                    <td><span class="label label-success" style="font-size:11px">{{ $incident_leader->tleader }}</span></td>
                                    <td><span class="label label-success" style="font-size:11px">{{ $incident_leader->report_no }}</span></td>
                                </tr>
                            </table>
                            @endif
                            <x-user-delete-single-responsibility-notify :item="$incident_reviewer" :item-users="$incident_reviewer_users" :item-text="$incident_reviewer_text"  />
                            <x-user-delete-single-responsibility-notify :item="$incident_approver" :item-users="$incident_approver_users" :item-text="$incident_approver_text"  />
                        @endif
                        @if($show_hse_module)
                        <h3>HSE</h3>
                        <x-user-delete-multiple-responsibility-notify :item="$hseissues" :item-text="$hseissue_text" :item-name="$hseissue_name"  :users="$users" :user="$user->email" />
                        <x-user-delete-single-responsibility-notify :item="$hse_observation_reviewer" :item-users="$hse_observation_reviewer_users" :item-text="$hse_observation_reviewer_text"  />
                        @endif
                        @if($show_calibration_module)
                        <h3>Calibration</h3>
                        <x-user-delete-single-responsibility-notify :item="$calibrationreviewer" :item-users="$calibrationreviewer_users" :item-text="$calibrationreviewer_text"  />
                        @endif
                        @if($show_compliance_module)
                        <h3>Compliance</h3>
                        <x-user-delete-single-responsibility-notify :item="$legal_coordinator" :item-users="$legal_coordinator_users" :item-text="$legal_coordinator_text" />
                        @if(count($compliances))
                        <table class="table table-bordered table-striped table-vcenter">
                            <tr style="background-color:white">
                                <th style="font-size:12px">responsibility</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                            </tr>
                        </table>
                        @endif
                        @endif
                        @if($show_nearmiss_module)
                        <h3>Nearmiss</h3>
                         <x-user-delete-single-responsibility-notify :item="$near" :item-users="$near_users" :item-text="$near_text" />
                         <x-user-delete-multiple-responsibility-notify :item="$nearmisses" :item-text="$nearmiss_text" :item-name="$nearmiss_name" :users="$users" :user="$user->email" />
                        @endif
                        @if($show_management_module)
                        <h3>Management Review</h3>
                        <x-user-delete-single-responsibility-notify :item="$management_reviewer" :item-users="$management_reviewer_users" :item-text="$management_reviewer_text"  />
                        @endif
                        @if($show_complaint_module)
                        <h3>Customer Complaint</h3>
                        <x-user-delete-single-responsibility-notify :item="$complaint_reviewer" :item-users="$complaint_reviewer_users" :item-text="$complaint_reviewer_text"  />
                        <x-user-delete-single-responsibility-notify :item="$complaint_register" :item-users="$complaint_register_users" :item-text="$complaint_register_text"  />
                        <x-user-delete-multiple-responsibility-notify :item="$complaint_responsibilities" :item-text="$complaint_responsibilities_text" :item-name="$complaint_responsibilities_name"  :users="$users" :user="$user->email" />
                        @endif
                        @if($show_context_module)
                        <h3>Context</h3>
                        <x-user-delete-single-responsibility-notify :item="$context_reviewer" :item-users="$context_reviewer_users" :item-text="$context_reviewer_text" />
                        <x-user-delete-single-responsibility-notify :item="$context_approver" :item-users="$context_approver_users" :item-text="$context_approver_text" />
                        @endif
                        @if($show_waste_module)
                        <h3>Waste</h3>
                        <x-user-delete-single-responsibility-notify :item="$waste_reviewer" :item-users="$waste_reviewer_users" :item-text="$waste_reviewer_text" />
                        <x-user-delete-single-responsibility-notify :item="$waste_approver" :item-users="$waste_approver_users" :item-text="$waste_approver_text" />
                        <x-user-delete-single-responsibility-notify :item="$waste_disposal_sales_authorithy" :item-users="$waste_disposal_sales_authorities_users" :item-text="$waste_disposal_sales_authorities_text" />
                        @endif
                        @if($show_envi_module)
                        <h3>Enviaspects</h3>
                        <x-user-delete-single-responsibility-notify :item="$envi_reviewer" :item-users="$envi_reviewer_users" :item-text="$envi_reviewer_text" />
                        <x-user-delete-single-responsibility-notify :item="$envi_secondary_reviewer" :item-users="$envi_secondary_reviewer_users" :item-text="$envi_secondary_reviewer_text" />
                        @endif

                        @if($show_vendor_module)
                        <h3>Vendor</h3>
                        <x-user-delete-single-responsibility-notify :item="$supplierapprover" :item-users="$supplierapprover_users" :item-text="$supplierapprover_text" />
                        <x-user-delete-single-responsibility-notify :item="$vendor_reviewer" :item-users="$vendor_reviewer_users" :item-text="$vendor_reviewer_text" />
                        <x-user-delete-single-responsibility-notify :item="$vendor_approver" :item-users="$vendor_approver_users" :item-text="$vendor_approver_text" />
                        @endif

                        @if($show_hr_module)
                        <h3>Human Resources</h3>
                        <x-user-delete-single-responsibility-notify :item="$trainingcoordinator" :item-users="$trainingcoordinator_users" :item-text="$trainingcoordinator_text" />
                        @endif

                        @if($show_occhealth_module)
                        <h3>Occupational health</h3>
                        <x-user-delete-single-responsibility-notify :item="$company_doctor" :item-users="$company_doctor_users" :item-text="$company_doctor_text"/>
                        @endif

                        @if($show_elearning_module)
                        <h3>E-learning</h3>
                        <x-user-delete-single-responsibility-notify :item="$elearning_add_content" :item-users="$elearning_add_content_users" :item-text="$elearning_add_content_text" />
                        @endif

                        @if($show_maintenance_module)
                        <h3>Maintenance</h3>
                        <x-user-delete-single-responsibility-notify :item="$mainreviewer" :item-users="$mainreviewer_users" :item-text="$mainreviewer_text"/>
                        <x-user-delete-single-responsibility-notify :item="$mainelectrical" :item-users="$mainelectrical_users" :item-text="$mainelectrical_text" />
                        <x-user-delete-single-responsibility-notify :item="$maininstrument" :item-users="$maininstrument_users" :item-text="$maininstrument_text"/>
                        @endif

                        @if($show_ohsrisk_module)
                        <h3>Health & safety risk</h3>
                        <x-user-delete-single-responsibility-notify :item="$ohsrisk_owner" :item-users="$ohsrisk_owner_users" :item-text="$ohsrisk_owner_text"  />
                        <x-user-delete-single-responsibility-notify :item="$hses" :item-users="$hses_users" :item-text="$hses_text"  />
                        <x-user-delete-single-responsibility-notify :item="$ohsrisk_secondary_review" :item-users="$ohsrisk_secondary_review_users" :item-text="$ohsrisk_secondary_review_text"/>
                        @endif
                        <input type="hidden" name="userId" value="{{ $id }}">
                    </div>
                    <div class="form-group text-right">
                        <a href="{{url('/admin/viewUsers')}}" class="btn btn-secondary" style="font-size:11px;">Cancel</a>
                        <button type="button" class="btn btn-primary" style="font-size:11px;" onclick="confirmDelete({{$id}})">Delete</button>
                    </div>
            </div>
            <!-- ends display responsibilty to user -->
    </div>
     <!-- Ends user deletion -assign responsiblities-no - -->

      <!-- ends user deletion confirm  -->
        <div class="row justify-content-center" style="margin-top:-10px;display:none;" id="user_delete_selected_yes">
            <div style="margin-left:40px;margin-top:50px;width:100%;margin-right:40px;">
                <form method="POST" action="{{ route('admin.postResponsibilities')}}">
                    @csrf
                    <div class="row ml-auto /*justify-content-center*/" style="position: relative;bottom:20px;">
                        <div class="mr-2 px-0">
                            <p class="text-danger font-weight-bold">Assign all Responsibilities to a single person</p>
                            <select name="new_responsibility" id="new_responsibility" class="form-control @error('new_responsibility') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                <option value="0" selected="selected"> Select a user...</option>
                                @foreach($users as $u)
                                <option value="{{$u->id}}">{{$u->name}}</option>
                                @endforeach
                            </select>
                            @error('new_responsibility')
                            <div class="invalid-feedback">{{ $message}}</div>
                            @enderror
                        </div>
                        <div id="radio_dropdown">
                            <div class="form-group mr-5">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="example-radios-inline1" name="example-radios-inline" value="yes" checked onchange="handleEvent()">
                                    <label class="form-check-label" for="example-radios-inline1">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="example-radios-inline2" name="example-radios-inline" value="no" onchange="handleEvent()">
                                    <label class="form-check-label" for="example-radios-inline2">NO</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="module_div">
                        @if($show_common_module)
                        <h3>Common Modules</h3>
                        @include('admin.layouts.departments')
                        <x-single-responsibility :item="$representative" :item-users="$representative_users" :item-text="$representative_text" :item-select="$representative_select" />
                        @if(count($divisions))
                        <p>Divisions</p>
                        <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                            <tr style="background-color:white">
                                <th style="font-size:12px">owner</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select id="division" name="division" class="form-control @error('division') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected"> Select a user...</option>
                                            @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('division')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif
                        @if(count($areas))
                        <p>Sub Divisions</p>
                        <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                            <tr style="background-color:white">
                                <th style="font-size:12px">owner</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select id="subdivision" name="subdivision" class="form-control @error('subdivision') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected"> Select a user...</option>
                                            @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('subdivision')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif


                        @endif
                        @if($show_nearmiss_module)
                        <h3>Nearmiss</h3>
                        <x-single-responsibility :item="$near" :item-users="$near_users" :item-text="$near_text" :item-select="$near_select" />
                        <x-single-plural-responsibility :item="$nearmisses" :item-text="$nearmiss_text" :item-name="$nearmiss_name" :item-select="$nearmiss_select" :users="$users" :user="$user->email" />
                        @endif
                        @if($show_audit_module)
                        <h3>Audit Management</h3>
                        @include('admin.layouts.auditor')
                        <x-single-plural-responsibility :item="$issues" :item-text="$issue_text" :item-name="$issue_name" :item-select="$issue_select" :users="$users" :user="$user->email" />
                        <x-single-responsibility :item="$internalaudit" :item-users="$internalaudit_users" :item-text="$internalaudit_text" :item-select="$internalaudit_select" />
                        <x-single-responsibility :item="$archiveprivilege" :item-users="$archiveprivilege_users" :item-text="$archiveprivilege_text" :item-select="$archiveprivilege_select" />
                        @if(count($auditees))
                        <p>Auditees</p>
                        <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                            <tr style="background-color:white">
                                <th style="font-size:12px">responsibility</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select id="auditee" name="auditee" class="form-control @error('auditee') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected"> Select a user...</option>
                                            @foreach($users as $u)
                                            <option value="{{ $u->name }}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('auditee')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif

                        @endif
                        @if($show_corrective_module)
                        <h3>NCR & Corrective Actions</h3>
                        <x-single-responsibility :item="$Issuereviewer" :item-users="$Issuereviewer_users" :item-text="$issuereviewer_text" :item-select="$issuereviewer_select" />
                        <x-single-plural-responsibility :item="$correctives" :item-text="$corrective_text" :item-name="$corrective_name" :item-select="$corrective_select" :users="$users" :user="$user->email" />
                        <x-single-plural-responsibility :item="$corrective_correction_responsibilities" :item-text="$corrective_correction_text" :item-name="$corrective_correction_name" :item-select="$corrective_correction_select" :users="$users" :user="$user->email" />
                        @if(count($lead_correctives))
                        <table class="table table-bordered table-striped table-vcenter">
                            <tr style="background-color:white">
                                <th style="font-size:12px">Investigation Team Lead</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->name }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select name="corrective_lead" id="corrective_lead" class="form-control @error('corrective_lead') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected"> Select a user...</option>
                                            @foreach($users as $u)
                                            <option value="{{$u->id}}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('corrective_lead')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif

                        @endif
                        @if($show_opportunity_module)
                        <h3>Opportunity</h3>
                        @include('admin.layouts.opportunity')
                        <x-single-responsibility :item="$closurereviewer" :item-users="$closurereviewer_users" :item-text="$closurereviewer_text" :item-select="$closurereviewer_text" />
                        @endif
                        @if($show_objective_module)
                        <h3>Objective</h3>
                        <x-single-responsibility :item="$management" :item-users="$management_users" :item-text="$management_text" :item-select="$management_select" />
                        <x-single-responsibility :item="$objective_reviewer" :item-users="$objective_reviewer_users" :item-text="$objective_reviewer_text" :item-select="$objective_reviewer_select" />
                        <x-single-responsibility :item="$objective_approver" :item-users="$objective_approver_users" :item-text="$objective_approver_text" :item-select="$objective_approver_select" />
                        @if(count($objective_resp_items))
                        <p>Responsibilities</p>
                        <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                            <tr style="background-color:white">
                                <th style="font-size:12px">responsibility</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select id="objective_resp" name="objective_resp" class="form-control @error('objective_resp') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected"> Select a user...</option>
                                            @foreach($users as $u)
                                            <option value="{{ $u->email }}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('objective_resp')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif

                        @endif
                        @if($show_hse_module)
                        <h3>HSE</h3>
                        <x-single-plural-responsibility :item="$hseissues" :item-text="$hseissue_text" :item-name="$hseissue_name" :item-select="$hseissue_select" :users="$users" :user="$user->email" />
                        <x-single-responsibility :item="$hse_observation_reviewer" :item-users="$hse_observation_reviewer_users" :item-text="$hse_observation_reviewer_text" :item-select="$hse_observation_reviewer_select" />
                        @endif
                        @if($show_incident_module)
                        <h3>Incident</h3>
                        @if($incident_leader)
                        <p>Incident Leader</p>
                        <table class="table table-bordered table-striped table-vcenter" style="margin-top:-20px;">
                            <tr style="background-color:white">
                                <th style="font-size:12px">id</th>
                                <th style="font-size:12px">team leader</th>
                                <th style="font-size:12px">report no</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <input type="hidden" name="incident_report_no" value="{{$incident_leader->report_no}}" />
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:12px">{{ $incident_leader->id}}</span></td>
                                <td><span class="label label-success" style="font-size:11px">{{ $incident_leader->tleader }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">{{ $incident_leader->report_no }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select id="incident_leader_select" name="incident_leader_select" class="form-control @error('incident_leader_select') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected"> Select a user...</option>
                                            @foreach($incident_leader_users as $u)
                                            <option value="{{ $u->email }}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('incident_leader_select')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif

                        <x-single-responsibility :item="$incident_reviewer" :item-users="$incident_reviewer_users" :item-text="$incident_reviewer_text" :item-select="$incident_reviewer_select" />
                        <x-single-responsibility :item="$incident_approver" :item-users="$incident_approver_users" :item-text="$incident_approver_text" :item-select="$incident_approver_select" />
                        @endif
                        @if($show_compliance_module)
                        <h3>Compliance</h3>
                        <x-single-responsibility :item="$legal_coordinator" :item-users="$legal_coordinator_users" :item-text="$legal_coordinator_text" :item-select="$legal_coordinator_select" />
                        @if(count($compliances))
                        <table class="table table-bordered table-striped table-vcenter">
                            <tr style="background-color:white">
                                <th style="font-size:12px">responsibility</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select name="compliance_responsibility" id="compliance_responsibility" class="form-control @error('compliance_responsibility') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected"> Select a user...</option>
                                            @foreach($users as $u)
                                            <option value="{{$u->email}}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('compliance_responsibility')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif

                        @endif
                        @if($show_complaint_module)
                        <h3>Customer Complaint</h3>
                        <x-single-responsibility :item="$complaint_reviewer" :item-users="$complaint_reviewer_users" :item-text="$complaint_reviewer_text" :item-select="$complaint_reviewer_select" />
                        <x-single-responsibility :item="$complaint_register" :item-users="$complaint_register_users" :item-text="$complaint_register_text" :item-select="$complaint_register_select" />
                        <x-single-plural-responsibility :item="$complaint_responsibilities" :item-text="$complaint_responsibilities_text" :item-name="$complaint_responsibilities_name" :item-select="$complaint_responsibilities_select" :users="$users" :user="$user->email" />
                        @endif
                        @if($show_context_module)
                        <h3>Context</h3>
                        <x-single-responsibility :item="$context_reviewer" :item-users="$context_reviewer_users" :item-text="$context_reviewer_text" :item-select="$context_reviewer_select" />
                        <x-single-responsibility :item="$context_approver" :item-users="$context_approver_users" :item-text="$context_approver_text" :item-select="$context_approver_select" />
                        @endif
                        @if($show_risk_module)
                        <h3>Risk</h3>
                        <x-single-responsibility :item="$riskreviewer" :item-users="$riskreviewer_users" :item-text="$riskreviewer_text" :item-select="$riskreviewer_text" />
                        <x-single-responsibility :item="$escalateriskreceiver" :item-users="$escalateriskreceiver_users" :item-text="$escalateriskreceiver_text" :item-select="$escalateriskreceiver_text" />
                        <x-single-responsibility :item="$racmreviewer" :item-users="$racmreviewer_users" :item-text="$racmreviewer_text" :item-select="$racmreviewer_select"/>
                        <x-single-responsibility :item="$racmapprover" :item-users="$racmapprover_users" :item-text="$racmapprover_text" :item-select="$racmapprover_select"/>
                        @include('admin.layouts.risks')
                        @include('admin.layouts.risk_mitigations')
                        @endif
                        @if($show_document_module)
                        <h3>Document</h3>
                        <x-single-responsibility :item="$doccontroller" :item-users="$doccontroller_users" :item-text="$doccontroller_text" :item-select="$doccontroller_select" />
                        @if(count($dcrs))
                        <p>Dcr</p>
                        <table class="table table-bordered table-striped table-vcenter">
                            <tr style="background-color:white">
                                <th style="font-size:12px">hod</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select name="dcr" id="dcr" class="form-control @error('dcr') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected"> Select a user...</option>
                                            @foreach($users as $u)
                                            <option value="{{$u->email}}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('dcr')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif
                        @if(count($masterdocs))
                        <p>Masterdoc</p>
                        <table class="table table-bordered table-striped table-vcenter">
                            <tr style="background-color:white">
                                <th style="font-size:12px">hod</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select name="masterdoc" id="masterdoc" class="form-control @error('masterdoc') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected"> Select a user...</option>
                                            @foreach($users as $u)
                                            <option value="{{$u->email}}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('masterdoc')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif
                        @if(count($document_approvers))
                        <p>Document approvers</p>
                        <table class="table table-bordered table-striped table-vcenter">
                            <tr style="background-color:white">
                                <th style="font-size:12px">approver</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select name="doc_approver" id="doc_approver" class="form-control @error('doc_approver') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected"> Select a user...</option>
                                            @foreach($users as $u)
                                            <option value="{{$u->email}}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('doc_approver')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif

                        @if(count($document_reviewers))
                        <p>Document reviewers</p>
                        <table class="table table-bordered table-striped table-vcenter">
                            <tr style="background-color:white">
                                <th style="font-size:12px">reviewers</th>
                                <th style="font-size:12px">edit</th>
                            </tr>
                            <tr style="background-color:white">
                                <td><span class="label label-success" style="font-size:11px">{{ $user->email }}</span></td>
                                <td><span class="label label-success" style="font-size:11px">
                                        <select name="doc_reviewer" id="doc_reviewer" class="form-control @error('doc_reviewer') is-invalid  @enderror" style="font-size: 13px;" required="Please">
                                            <option value="0" selected="selected">Select a user...</option>
                                            @foreach($users as $u)
                                            <option value="{{$u->email}}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('doc_reviewer')
                                        <div class="invalid-feedback">{{ $message}}</div>
                                        @enderror
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @endif

                        @endif
                        @if($show_waste_module)
                        <h3>Waste</h3>
                        <x-single-responsibility :item="$waste_reviewer" :item-users="$waste_reviewer_users" :item-text="$waste_reviewer_text" :item-select="$waste_reviewer_select" />
                        <x-single-responsibility :item="$waste_approver" :item-users="$waste_approver_users" :item-text="$waste_approver_text" :item-select="$waste_approver_select" />
                        <x-single-responsibility :item="$waste_disposal_sales_authorithy" :item-users="$waste_disposal_sales_authorities_users" :item-text="$waste_disposal_sales_authorities_text" :item-select="$waste_disposal_sales_authorities_select" />
                        @endif
                        @if($show_envi_module)
                        <h3>Enviaspects</h3>
                        <x-single-responsibility :item="$envi_reviewer" :item-users="$envi_reviewer_users" :item-text="$envi_reviewer_text" :item-select="$envi_reviewer_select" />
                        <x-single-responsibility :item="$envi_secondary_reviewer" :item-users="$envi_secondary_reviewer_users" :item-text="$envi_secondary_reviewer_text" :item-select="$envi_secondary_reviewer_select" />
                        @endif

                        @if($show_vendor_module)
                        <h3>Vendor</h3>
                        <x-single-responsibility :item="$supplierapprover" :item-users="$supplierapprover_users" :item-text="$supplierapprover_text" :item-select="$supplierapprover_select" />
                        <x-single-responsibility :item="$vendor_reviewer" :item-users="$vendor_reviewer_users" :item-text="$vendor_reviewer_text" :item-select="$vendor_reviewer_select" />
                        <x-single-responsibility :item="$vendor_approver" :item-users="$vendor_approver_users" :item-text="$vendor_approver_text" :item-select="$vendor_approver_select" />
                        @endif

                        @if($show_hr_module)
                        <h3>Human Resources</h3>
                        <x-single-responsibility :item="$trainingcoordinator" :item-users="$trainingcoordinator_users" :item-text="$trainingcoordinator_text" :item-select="$trainingcoordinator_select" />
                        @endif

                        @if($show_occhealth_module)
                        <h3>Occupational health</h3>
                        <x-single-responsibility :item="$company_doctor" :item-users="$company_doctor_users" :item-text="$company_doctor_text" :item-select="$company_doctor_select" />
                        @endif

                        @if($show_elearning_module)
                        <h3>E-learning</h3>
                        <x-single-responsibility :item="$elearning_add_content" :item-users="$elearning_add_content_users" :item-text="$elearning_add_content_text" :item-select="$elearning_add_content_select" />
                        @endif

                        @if($show_maintenance_module)
                        <h3>Maintenance</h3>
                        <x-single-responsibility :item="$mainreviewer" :item-users="$mainreviewer_users" :item-text="$mainreviewer_text" :item-select="$mainreviewer_select" />
                        <x-single-responsibility :item="$mainelectrical" :item-users="$mainelectrical_users" :item-text="$mainelectrical_text" :item-select="$mainelectrical_select" />
                        <x-single-responsibility :item="$maininstrument" :item-users="$maininstrument_users" :item-text="$maininstrument_text" :item-select="$maininstrument_select" />
                        @endif

                        @if($show_management_module)
                        <h3>Management Review</h3>
                        <x-single-responsibility :item="$management_reviewer" :item-users="$management_reviewer_users" :item-text="$management_reviewer_text" :item-select="$management_reviewer_select" />
                        @endif

                        @if($show_ohsrisk_module)
                        <h3>Health & safety risk</h3>
                        <x-single-responsibility :item="$ohsrisk_owner" :item-users="$ohsrisk_owner_users" :item-text="$ohsrisk_owner_text" :item-select="$ohsrisk_owner_select" />
                        <x-single-responsibility :item="$hses" :item-users="$hses_users" :item-text="$hses_text" :item-select="$hses_select" />
                        <x-single-responsibility :item="$ohsrisk_secondary_review" :item-users="$ohsrisk_secondary_review_users" :item-text="$ohsrisk_secondary_review_text" :item-select="$ohsrisk_secondary_review_select" />
                        @endif

                        @if($show_calibration_module)
                        <h3>Calibration</h3>
                        <x-single-responsibility :item="$calibrationreviewer" :item-users="$calibrationreviewer_users" :item-text="$calibrationreviewer_text" :item-select="$calibrationreviewer_select" />
                        @endif
                        <input type="hidden" name="userId" value="{{ $id }}">
                    </div>
                    <div class="form-group text-right">
                        <!-- <input type="submit" style="font-size:12px" class="btn btn-primary" name="submit" value="Update"> -->
                        <a href="{{url('/admin/viewUsers')}}" class="btn btn-secondary" style="font-size:11px;">Cancel</a>
                        <button type="button" class="btn btn-primary" style="font-size:12px" onclick="confirmDeleteWithReassignment()">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@section('js_after')
<script>
    let deps_count = "{{ count($deps)}}";
    let nearmiss_count = "{{ count($nearmisses)}}";
    let hseissue_count = "{{ count($hseissues) }}";
    let corrective_count = "{{ count($correctives)}}";
    let issues_count = "{{ count($issues)}}";
    let opportunity_count = "{{ count($opportunities) }}";
    let risks_count = "{{ count($risks)}}";
    let dcrs_count = "{{ count($dcrs) }}";
    let masterdocs_count = "{{ count($masterdocs) }}";
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let url = "{{route('admin.ajaxDept')}}";
    let auditor_url = "{{route('admin.ajaxAuditor')}}";

</script>
<script src="/js/admin/showResponsibility_script.js"></script>
<script>
    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    let radio_dropdown_selector = $('#radio_dropdown');
    let module_div_selector = $('#module_div');
    let new_responsibility_div_selector = $('#new_responsibility');
    let representative_count = '{{ $representative_count }}';
    let near_count = '{{ $near_count }}';
    let calibrationreviewer_count = '{{ $calibrationreviewer_count }}';
    let doccontroller_count = '{{ $doccontroller_count }}';
    let hse_count = '{{ $hse_count }}';
    let issuereviewer_count = '{{ $issuereviewer_count }}';
    let internalaudit_count = '{{ $internalaudit_count }}';
    let archiveprivilege_count = '{{ $archiveprivilege_count }}';
    let riskreviewer_count = '{{ $riskreviewer_count }}';
    let escalateriskreceiver_count = '{{ $escalateriskreceiver_count }}';
    let closurereviewer_count = '{{ $closurereviewer_count }}';
    let management_count = '{{ $management_count }}';
    let legal_coordinator_count = '{{ $representative_count }}';
    let incident_reviewer_count = '{{ $incident_reviewer_count }}';
    let incident_approver_count = '{{ $incident_approver_count }}';
    let complaint_reviewer_count = '{{ $complaint_reviewer_count }}';
    let complaint_register_count = '{{ $complaint_register_count }}';
    let change_request_reviewer_count = '{{ $change_request_reviewer_count }}';
    let change_request_approve_count = '{{ $change_request_approve_count }}';
    let waste_reviewer_count = '{{ $waste_reviewer_count }}';
    let waste_approver_count = '{{ $waste_approver_count }}';
    let waste_disposal_sales_authorithy_count = '{{ $waste_disposal_sales_authorithy_count }}';
    let envi_reviewer_count = '{{ $envi_reviewer_count }}';
    let trainingcoordinator_count = '{{ $trainingcoordinator_count }}';
    let mainreviewer_count = '{{ $mainreviewer_count }}';
    let mainelectrical_count = '{{ $mainelectrical_count }}';
    let main_instrument_count = '{{ $main_instrument_count }}';
    let company_doctor_count = '{{ $company_doctor_count }}';
    let supplierapprover_count = '{{ $supplierapprover_count }}';
    let review_vendor_count = '{{ $review_vendor_count }}';
    let approve_vendor_count = '{{ $approve_vendor_count }}';
    let elearning_add_content_count = '{{ $elearning_add_content_countt }}';
    let enviaspect_secondary_review_count = '{{ $enviaspect_secondary_review_count }}';
    let hse_observation_reviewer_count = '{{ $hse_observation_reviewer_countt }}';
    let objective_reviewer_count = '{{ $objective_reviewer_countt }}';
    let objective_approver_count = '{{ $objective_approver_countt }}';
    let racm_reviewer_count = '{{ $racm_reviewer_countt }}';
    let racm_approver_count = '{{ $racm_approver_countt }}';
    let management_review_meeting_controls_count = '{{ $management_review_meeting_controls_count }}';
    let ohsrisk_secondary_review_count = '{{ $ohsrisk_secondary_review_count }}';

    if (isMobile) {
        radio_dropdown_selector.addClass('ml-3 mt-3');
    } else {
        radio_dropdown_selector.removeClass('ml-3 mt-3')
    }

    handleEvent();

    function handleEvent() {
        let selected_val = document.querySelector('input[name="example-radios-inline"]:checked').value;

        if (selected_val === 'yes') {
            module_div_selector.addClass('d-none').removeClass('d-block');
            new_responsibility_div_selector.removeClass('d-none').addClass('d-inline-block');
        } else {
            module_div_selector.removeClass('d-none').addClass('d-block');
            new_responsibility_div_selector.addClass('d-none').removeClass('d-inline-block');
        }
    }

   function displayuserdeletemodules(){
    if(document.getElementById('user_delete_yes').checked)
        {
            document.getElementById('user_delete_selected_yes').style.display="";
            document.getElementById('user_delete_selected_no').style.display="none";
        }
    else if(document.getElementById('user_delete_no').checked){
        document.getElementById('user_delete_selected_yes').style.display="none";
        document.getElementById('user_delete_selected_no').style.display="";
    }
   }

   // Confirmation for deleting user without reassignment
   function confirmDelete(userId) {
       Swal.fire({
           title: 'Delete User?',
           text: "Are you sure you want to delete this user? This action cannot be undone!",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#AF1E1E',
           cancelButtonColor: '#6c757d',
           confirmButtonText: 'Yes, delete user!',
           cancelButtonText: 'Cancel'
       }).then((result) => {
           if (result.isConfirmed) {
               window.location.href = '{{ route("admin.user.delete", ":id") }}'.replace(':id', userId);
           }
       });
   }

   // Confirmation for deleting user with reassignment
   function confirmDeleteWithReassignment() {
       // Check if a new responsibility person is selected
       var newResponsibility = document.getElementById('new_responsibility');
       if (newResponsibility && newResponsibility.value === '0') {
           Swal.fire({
               title: 'Selection Required',
               text: 'Please select a user to assign the responsibilities to.',
               icon: 'warning',
               confirmButtonColor: '#3085d6',
               confirmButtonText: 'OK'
           });
           return;
       }

       Swal.fire({
           title: 'Delete User with Reassignment?',
           text: "Are you sure you want to delete this user and reassign their responsibilities? This action cannot be undone!",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#AF1E1E',
           cancelButtonColor: '#6c757d',
           confirmButtonText: 'Yes, delete and reassign!',
           cancelButtonText: 'Cancel'
       }).then((result) => {
           if (result.isConfirmed) {
               // Submit the form
               document.querySelector('form[action="{{ route('admin.postResponsibilities')}}"]').submit();
           }
       });
   }
</script>
@endsection
@endsection
