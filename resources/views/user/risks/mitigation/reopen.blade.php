@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <style>
        .breadcrumb > li + li:before {
            display: none !important;
        }
        .breadcrumb {
            font-size: 15px;
            padding: 0.75rem 1.25rem;
            background: #F6F7F9;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .risk-span-class {
            display: block;
            font-weight: bold;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 13px;
        }
        .high-rank {
            background: #F8D7D6;
            color: #E93A2D;
        }
        .moderate-rank {
            background: #FDF3D1;
            color: #F5C342;
        }
        .other-rank {
            background: #E5F0DB;
            color: #4EAD5B;
        }
    </style>
</head>
<body>
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">
            <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item font-w700">
                        <a href="{{ route('mitigations.index') }}" class="text-black">
                            Mitigating Actions
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Reopen
                    </li>
                </ol>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('risk.index') }}">Enterprise Risk</a>
                    </li>
                </ol>
            </nav>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        @if(Session::has('errmsg'))
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('errmsg')!!}
                            </div>
                        @endif
                        @if(Session::has('msg'))
                            <div class="alert alert-success">
                                <a class="close close-org" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                        @endif
                        <form action="{{ route('mitigations.update', $mitigation->id) }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input type="hidden" name="risk_id" value="{{ $riskId }}">
                            <div class="modal-body m-body p-4">
                                <!-- Owner -->
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="owner">Owner <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="owner" name="owner" required="true"
                                                style="font-size: 13px;">
                                            <option value="">Choose Owner</option>
                                            @if(isset($actionOwners))
                                                @foreach($actionOwners as $id => $name)
                                                    <option value="{{ $id }}"
                                                            {{ isset($mitigation) && $mitigation->user && $mitigation->user->id == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <!-- Department -->
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department_id">Department <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="department_id" name="department_id" required="true"
                                                style="font-size: 13px;">
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
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="action_name">Action Name <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="action_name" style="font-size: 13px;"
                                               readonly
                                               value="{{ isset($mitigation) ? $mitigation->action_name : '' }}"
                                               name="action_name" required>
                                    </div>
                                </div>
                                <!-- Action Description -->
                                <div class="form-group" style="display:inline-block;width:98.5%;">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="action_description">Action Description</label>
                                    <div class="col-sm-12">
                                    <textarea style="font-size: 13px;" readonly
                                      class="form-control" id="action_description"
                                      name="action_description">{{ isset($mitigation) ? $mitigation->action_description : '' }}</textarea>
                                    </div>
                                </div>
                                <!-- Priority Level -->
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="priority_level">Priority Level <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="priority_level" name="priority_level" required="true"
                                                style="font-size: 13px;">
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
                                <!-- Link other risks -->
                                <div class="form-group " style="display:inline-block;width:49%;">
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
                                                    style="font-size: 12px;" multiple>
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
                                <!-- Due Date -->
                                <div class="form-group" style="display:inline-block;width:49%;">
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
                                <!-- Status -->
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="process">Status <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="process" name="process" required="true"
                                                style="font-size: 13px;">
                                            <option value="">Choose Status</option>
                                            @foreach(config('constants.risk_management.mitigation.progresses') as $value => $name)
                                                <option value="{{ $value }}">
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Resources Required -->
                                <div class="form-group" style="display:inline-block;width:49%;">
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
                                <div class="form-group" style="display:inline-block;width:49%;">
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
                                            data-toggle="modal"
                                            data-target="#viewModalAttachment{{ $mitigation->id }}">
                                        <strong>View Attachments</strong>
                                    </button>
                                @endif
                                <!-- Attachments -->
                                <div class="form-group col-sm-12" style="display:inline-block;width:49%;">
                                    <button type="button"
                                            class="btn btn-secondary btn-small"
                                            style="font-size:13px;width: 250px;"
                                            data-toggle="modal"
                                            data-target="#viewModalAttachment{{ $mitigation->id }}">
                                        <strong>View Attachments</strong>
                                    </button>
                                    @include('user.risks.mitigation._mitigation_attachment')
                                </div>

                                <div class="mt-5 d-flex justify-content-end">
                                    <button type="button" class="btn btn-lg btn-outline-primary mx-3 px-5">Cancel</button>
                                    <button type="submit" class="btn btn-lg btn-primary px-5">Update</button>
                                </div>
                            </div>
                        </form>
                        <!-- END Form Horizontal - Default Style -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->

        <!-- Apps Modal -->
        <!-- Opens from the modal toggle button in the header -->
        <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Options</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="row gutters-tiny">
                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/corrective/corrissues">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Register
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/closedissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-check-square fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Closed Issues
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/openissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                My Open Issues
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">

                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/corrective/analysis' style="margin-top:6px">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Analysis
                                            </p>
                                        </div>
                                    </a>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        const limitSelection = $('.js-select2').attr('data-limit-selection')
        $('.js-select2').select2({
            width: '100%',
            tags: true,
            multiple: true,
            maximumSelectionLength: limitSelection ? limitSelection : 0
        });

        $('body').on('click', '.add_resource_required', function (e) {
            e.preventDefault();
            const val = $(this).attr('data-value');
            $('#resource_required').val(val);
        });
    });
</script>
</html>
@endsection
@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/select2/js/select2.full.js') }}"></script>
@endsection
@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection
