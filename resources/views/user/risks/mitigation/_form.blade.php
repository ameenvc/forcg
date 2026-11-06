@include('shared.forms.risk-assets')
<style>
    .text-sm {
        font-size: 13px;
    }
    .select2-container .select2-selection--single {
        height: calc(1.5em + 0.5rem + 2px);
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        height: calc(1.5em + 0.5rem + 2px);
        font-size: 13px;
    }
    .select2-container .select2-dropdown {
        z-index: 100;
    }
    .select2-results__options, .select2-search__field {
        font-size: 13px;
    }
    .select2-container .select2-search__field {
        font-size: 13px;
    }

</style>

<!-- Mitigating Modal -->
<div class="risk-module-form modal new-mitigating-popup risk-workflow-modal" id="modal-mitigating-{{ $riskId }}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog m-dialog" role="document">
        <div class="modal-content" style="text-align: left">
            <div class="modal-header" style="border-bottom: 1px solid #e1e6e9; padding: 0.75rem 1rem;">
                <h5 class="modal-title"><span class="rf-modal-icon" aria-hidden="true"><i class="fa fa-tasks"></i></span>{{isset($mitigation)? 'Edit' : 'Add'}} Mitigation Action</h5>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            @php
                if (!env('FORCE_HTTPS', false)) {
                    $url = isset($mitigation) ? url('/user/riskmgmt/mitigations/' . $mitigation->id) : url('/user/riskmgmt/mitigations');
                } else {
                    $url = isset($mitigation) ? secure_url('/user/riskmgmt/mitigations/' . $mitigation->id) : secure_url('/user/riskmgmt/mitigations');
                }
            @endphp
            <form class="risk-module-form" action="{{ $url }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($mitigation))
                    @method('put')
                @endif
                <input type="hidden" name="risk_id" value="{{ $riskId }}">
                <div class="modal-body m-body p-4 risk-action-grid">
                    <p class="workflow-form-hint">Describe the action, assign an owner, and set a realistic due date. Track progress and request closure review from Mitigation actions.</p>
                    <!-- Owner -->
                    <div class="form-group risk-action-field">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="owner">Owner <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <select class="form-control text-sm owner" id="owner" name="owner" required="true" style="font-size: 13px;" data-placeholder="Select owner" data-limit-selection="1">
                                @if(isset($actionOwners))
                                    <option value="">Choose Owner</option>
                                    @foreach($actionOwners as $id => $name)
                                        <option value="{{ $id }}" {{ isset($mitigation) && $mitigation->user && $mitigation->user->id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <!-- Department -->
                    <div class="form-group risk-action-field">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department_id">Department <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <select class="form-control text-sm" id="department_id" name="department_id" required="true"
                                    style="font-size: 13px;" data-placeholder="Select Department" data-limit-selection="1">
                                <option value="">Choose Department</option>
                                @if(isset($departments))
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ isset($mitigation) && $mitigation->department && $mitigation->department->id == $department->id ? 'selected' : '' }}>
                                            {{ $department->departmentname }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <!-- Action name -->
                    <div class="form-group risk-action-field risk-action-wide">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="action_name">Action Name <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="action_name" style="font-size: 13px;"
                                   value="{{ isset($mitigation) ? $mitigation->action_name : '' }}"
                                   name="action_name" required>
                        </div>
                    </div>
                    <!-- Action Description -->
                    <div class="form-group risk-action-field risk-action-wide">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="action_description">Action Description</label>
                        <div class="col-sm-12">
                            <textarea style="font-size: 13px;"
                              class="form-control" id="action_description"
                              name="action_description">{{ isset($mitigation) ? $mitigation->action_description : '' }}</textarea>
                        </div>
                    </div>
                    <!-- Priority Level -->
                    <div class="form-group risk-action-field">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="priority_level">Priority Level <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <select class="form-control" id="priority_level" name="priority_level" required="true"
                                    style="font-size: 13px;" data-placeholder="Select Priority Level" data-limit-selection="1">
                                <option value="">Choose Priority Level</option>
                                @foreach(config('constants.risk_management.mitigation.priorities') as $value => $name)
                                    <option value="{{ $value }}"
                                        {{ isset($mitigation) && $mitigation->priority_level == $value ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Due Date -->
                    <div class="form-group risk-action-field">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="due_date">Due Date <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <input type="date" class="js-datepicker form-control"
                                   required="true"
                                   style="font-size:13px;" id="due_date" name="due_date"
                                   data-week-start="1" data-autoclose="true"
                                   value="{{ isset($mitigation) ? $mitigation->due_date : '' }}"
                                   data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                        </div>
                    </div>
                    <!-- Link other risks -->
                    <div class="form-group risk-action-field risk-action-wide">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="link_other_risk">Link other risks</label>
                        <div class="col-sm-12">
                            @if(isset($data))
                                @if(isset($mitigation))
                                    @php
                                        $linkOtherRisk = json_decode($mitigation->link_other_risk, true);
                                        $linkOtherRiskIds = [];
                                        if (is_array($linkOtherRisk)) {
                                            $linkOtherRiskIds = array_keys($linkOtherRisk);
                                        }
                                    @endphp
                                @endif
                                <select class="form-control js-select2" id="link_other_risk" name="link_other_risk[]"
                                        style="font-size: 13px;" multiple>
                                    @foreach($deletedRisks as $riskItem)
                                        <option value="{{ $riskItem->Rid }}"
                                                {{ isset($mitigation) && in_array($riskItem->Rid, $linkOtherRiskIds) ? 'selected' : '' }}>
                                            {{ $riskItem->riskname . ' - ' . $riskItem->grp_id }}
                                        </option>
                                    @endforeach
                                    @foreach($data as $riskItem)
                                        <option value="{{ $riskItem->Rid }}"
                                            {{ $riskId == $riskItem->Rid ? 'selected' : '' }}
                                            {{ isset($mitigation) && in_array($riskItem->Rid, $linkOtherRiskIds) ? 'selected' : '' }}>
                                            {{ $riskItem->riskname . ' - ' . $riskItem->grp_id }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    <!-- Resources Required -->
                    <div class="form-group risk-action-field risk-action-wide">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="resource_required">Resources Required</label>
                        <div class="col-sm-12">
                            @foreach(config('constants.risk_management.mitigation.resources') as $value => $name)
                                <button type="button" class="btn btn-sm btn-outline-primary add_resource_required {{ $loop->first ? 'ml-0' : '' }}"
                                        data-value="{{ $name }}"
                                        style="margin-right:4px;margin-left:10px;margin-bottom:10px;">
                                    {{ $name }}
                                </button>
                            @endforeach
                            <input type="text" class="form-control" id="resource_required" name="resource_required"
                                   style="font-size: 13px;"
                                    value="{{ isset($mitigation) ? $mitigation->resource_required : '' }}">
                        </div>
                    </div>
                    <!-- Cost Estimate -->
                    <div class="form-group risk-action-field">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="cost_estimate">Cost Estimate</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="cost_estimate" style="font-size: 13px;"
                                   value="{{ isset($mitigation) ? $mitigation->cost_estimate : '' }}"
                                   name="cost_estimate">
                        </div>
                    </div>
                    @if(isset($mitigation) && count($mitigation->attachments))
                        <button type="button"
                                class="btn btn-secondary btn-small"
                                style="font-size:13px;width: 250px;"
                                data-dismiss="modal"
                                data-toggle="modal"
                                data-target="#viewModalAttachment{{ $mitigation->id }}">
                            <strong>View Attachments</strong>
                        </button>
                    @endif
                    <!-- Attachments -->
                    <div class="form-group risk-action-field">
                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="attachment">Attachments</label>
                        <div class="col-sm-12">
                            <input type="file" class="form-control" id="attachment" style="font-size: 13px;"
                                   name="attachment">
                        </div>
                    </div>
                    <!-- Save button -->
                    <div class="risk-form-actions">
                        <button type="submit" class="btn btn-primary"
                                style="font-size:13px;">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@if (isset($mitigation))
    <script>
        $(document).ready(function () {
            $('#viewModalAttachment{{ $mitigation->id }}').on('hidden.bs.modal', function () {
                $('#modal-mitigating-{{ $riskId }}').modal('show');
            });

            $('#owner').select2({
                placeholder: $('#owner').data('placeholder'),
                width: '100%',
                dropdownParent: $('#modal-mitigating-{{ $riskId }}')
            });
            $('#department_id').select2({
                placeholder: $('#department_id').data('placeholder'),
                width: '100%',
                dropdownParent: $('#modal-mitigating-{{ $riskId }}')
            });
            $('#priority_level').select2({
                placeholder: $('#priority_level').data('placeholder'),
                width: '100%',
                dropdownParent: $('#modal-mitigating-{{ $riskId }}')
            });
        });
    </script>
@else
    <script>
        $(document).ready(function () {
            $('#owner').select2({
                placeholder: $('#owner').data('placeholder'),
                width: '100%',
                dropdownParent: $('#modal-mitigating-{{ $riskId }}')
            });
            $('#department_id').select2({
                placeholder: $('#department_id').data('placeholder'),
                width: '100%',
                dropdownParent: $('#modal-mitigating-{{ $riskId }}')
            });
            $('#priority_level').select2({
                placeholder: $('#priority_level').data('placeholder'),
                width: '100%',
                dropdownParent: $('#modal-mitigating-{{ $riskId }}')
            });
        });
    </script>
@endif

@include('user.risks.mitigation._mitigation_attachment')
