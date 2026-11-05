@extends('layouts.backend')
@section('content')
@include('shared.forms.risk-assets')

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
                        Closure Review
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
                    <div class="col-lg-12">
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
                        <form class="risk-module-form" action="#"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="risk_id" value="{{ $riskId }}">
                            <div class="modal-body m-body p-4">
                                <div class="row">
                                    <!-- Owner -->
                                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                        <label class="col-form-label" style="font-size: 13px;" for="owner">Owner <span class="text-danger">*</span></label>
                                        <div>
                                            {{-- <select class="form-control js-select2" id="owner" name="owner" required="true" data-limit-selection="1"
                                                    style="font-size: 13px;">
                                                <option value="" disabled>Choose Owner</option>
                                                @if(isset($actionOwners))
                                                    @foreach($actionOwners as $id => $name)
                                                        <option value="{{ $id }}"
                                                                {{ isset($mitigation) && $mitigation->user && $mitigation->user->id == $id ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select> --}}
                                            <input type="text" class="form-control" id="action_name" style="font-size: 13px;"
                                                value="{{ isset($mitigation) && $mitigation->user ? $mitigation->user->name : '' }}"
                                                name="action_name" readonly>
                                            
                                        </div>
                                    </div>
                                    <!-- Department -->
                                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                        <label class="col-form-label" style="font-size: 13px;" for="department_id">Department <span class="text-danger">*</span></label>
                                        <div>
                                            {{-- <select class="form-control js-select2" id="department_id" name="department_id" required="true"
                                                    style="font-size: 13px;">
                                                <option value="" disabled>Choose Department</option>
                                                @if(isset($departments))
                                                    @foreach($departments as $department)
                                                        <option value="{{ $department->id }}"
                                                                {{ isset($mitigation) && $mitigation->department && $mitigation->department->id == $department->id ? 'selected' : '' }}>
                                                            {{ $department->departmentname }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select> --}}
                                            <input type="text" class="form-control" id="action_name" style="font-size: 13px;"
                                                value="{{ isset($mitigation) && $mitigation->department  ? $mitigation->department->departmentname : '' }}"
                                                name="action_name" readonly>
                                        </div>
                                    </div>
                                    <!-- Action name -->
                                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                        <label class="col-form-label" style="font-size: 13px;" for="action_name">Action Name <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" id="action_name" style="font-size: 13px;"
                                                value="{{ isset($mitigation) ? $mitigation->action_name : '' }}"
                                                name="action_name" readonly>
                                        </div>
                                    </div>
                                    <!-- Action Description -->
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <label class="col-form-label" style="font-size: 13px;" for="action_description">Action Description</label>
                                        <div>
                                        <textarea style="font-size: 13px;"
                                                class="form-control" id="action_description"
                                                name="action_description" readonly>{{ isset($mitigation) ? $mitigation->action_description : '' }}</textarea>
                                        </div>
                                    </div>
                                    <!-- Priority Level -->
                                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                        <label class="col-form-label" style="font-size: 13px;" for="priority_level">Priority Level <span class="text-danger">*</span></label>
                                        <div>
                                            {{-- <select class="form-control js-select2" id="priority_level" name="priority_level" required="true"
                                                    style="font-size: 13px;">
                                                <option value="" disabled>Choose Priority Level</option>
                                                @foreach(config('constants.risk_management.mitigation.priorities') as $value => $name)
                                                    <option value="{{ $value }}"
                                                            {{ isset($mitigation) && $mitigation->priority_level == $value ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select> --}}
                                            @foreach(config('constants.risk_management.mitigation.priorities') as $value => $name)
                                                @if(isset($mitigation) && $mitigation->priority_level == $value)
                                                <input type="text" class="form-control" id="action_name" style="font-size: 13px;"
                                                    value="{{  $name}}"
                                                    name="action_name" readonly>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Link other risks -->
                                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                        <label class="col-form-label" style="font-size: 13px;" for="link_other_risk">Link other risks</label>
                                        <div>
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
                                                {{-- <select class="form-control js-select2" id="link_other_risk" name="link_other_risk[]"
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
                                                    
                                                </select> --}}
                                                
                                                @php 
                                                    $riskNames = [];
                                                    foreach($deletedRisks as $riskItem){
                                                        if(isset($mitigation) && in_array($riskItem->Rid, $linkOtherRiskIds)){
                                                           $riskNames[] = $riskItem->riskname . ' - ' . $riskItem->grp_id;
                                                        }
                                                    }
                                                        
                                                    foreach($data as $riskItem){
                                                        if(isset($mitigation) && in_array($riskItem->Rid, $linkOtherRiskIds)){
                                                           $riskNames[] = $riskItem->riskname . ' - ' . $riskItem->grp_id;
                                                        }
                                                    }
                                                @endphp
                                                    <textarea class="form-control"
                                                        rows="3"
                                                        readonly
                                                        style="font-size:13px; resize:vertical;">{!! implode(",\n", $riskNames) !!}</textarea>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Due Date -->
                                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                        <label class="col-form-label" style="font-size: 13px;" for="due_date">Due Date <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="date" class="js-datepicker form-control"
                                                required="true" disabled
                                                style="font-size:13px;" id="due_date" name="due_date"
                                                data-week-start="1" data-autoclose="true"
                                                value="{{ isset($mitigation) ? $mitigation->due_date : '' }}"
                                                data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                        </div>
                                    </div>
                                    <!-- Cost Estimate -->
                                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                        <label class="col-form-label" style="font-size: 13px;" for="cost_estimate">Cost Estimate</label>
                                        <div>
                                            <input type="text" class="form-control" id="cost_estimate" style="font-size: 13px;"
                                                value="{{ isset($mitigation) ? $mitigation->cost_estimate : '' }}"
                                                name="cost_estimate" readonly>
                                        </div>
                                    </div>
                                    <!-- Resources Required -->
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <label class="col-form-label" style="font-size: 13px;" for="resource_required">Resources Required</label>
                                        <div>
                                            @foreach(config('constants.risk_management.mitigation.resources') as $value => $name)
                                                <button type="button" class="btn btn-sm btn-outline-primary add_resource_required {{ $loop->first ? 'ml-0' : '' }}"
                                                        data-value="{{ $name }}" disabled
                                                        style="margin-right:4px;margin-left:10px;margin-bottom:10px;">
                                                    {{ $name }}
                                                </button>
                                            @endforeach
                                            <input type="text" class="form-control" id="resource_required" name="resource_required"
                                                style="font-size: 13px;"
                                                value="{{ isset($mitigation) ? $mitigation->resource_required : '' }}" readonly>
                                        </div>
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

                                <div class="form-group col-sm-12" style="display:inline-block;width:99%;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                               name="closure_review_action" id="closure_review_action_close" value="1">
                                        <label class="form-check-label" for="closure_review_action_close">
                                            Close the Mitigation action
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                               name="closure_review_action" id="closure_review_action_not_fully_implement" value="2">
                                        <label class="form-check-label" for="closure_review_action_not_fully_implement">
                                            Not fully implemented
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-5 d-flex justify-content-end">
                                    <a href="{{ url()->previous() }}" class="btn btn-lg btn-outline-primary mx-3 px-5">Cancel</a>
                                    <button type="button" class="btn btn-lg btn-primary px-5 btn-update"
                                        data-id="{{ $mitigation->id }}">Update</button>
                                </div>
                            </div>
                        </form>
                        <!-- END Form Horizontal - Default Style -->
                    </div>
                </div>
            </div>
        </div>
        @include('user.risks.mitigation._close_mitigation_form', ['risk' => $mitigation->risk])
        @include('user.risks.mitigation._not_fully_implemented_form')
        <!-- END Horizontal -->

        <!-- Apps Modal -->
        <!-- Opens from the modal toggle button in the header -->
        <div class="risk-module-form modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const limitSelection = $('.js-select2').attr('data-limit-selection')
        $('.js-select2').select2({
            width: '100%',
            // tags: true,
            // multiple: true,
            // maximumSelectionLength: limitSelection ? limitSelection : 0
        });
        const getRankOfLikelihoodAndImpact = (likelihood, impact, riskId) => {
            const rank = (likelihood * impact).toFixed(1);
            if (rank >= 5 && rank <= 9) {
                $(`#new_rank${riskId}`).html('<span class="risk-span-class high-rank">High</span>');
                $(`#ranks_input${riskId}`).val('High');
            } else if (rank >= 2.5 && rank <= 4.9) {
                $(`#new_rank${riskId}`).html('<span class="risk-span-class moderate-rank">Moderate</span>');
                $(`#ranks_input${riskId}`).val('Moderate');
            } else {
                $(`#new_rank${riskId}`).html('<span class="risk-span-class other-rank">Low</span>');
                $(`#ranks_input${riskId}`).val('Low');
            }
        }
        $('body').on('click', '.btn-update', function () {
            const action = $('input[name=closure_review_action]:checked').val();
            const mitigationId = $(this).attr('data-id');
            if (action === '1') {
                $(`#modal-close-mitigating-${mitigationId}`).modal('show');
            } else if (action === '2') {
                $(`#not-fully-implemented-${mitigationId}`).modal('show');
            } else {
                alert("Please choose action to update");
            }
        }).on('change', '.js-likelihood', function () {
            const riskId = $(this).attr('data-id');
            const likelihood = $(this).val();
            const orgLikelihood = $(`#org_likelihood${riskId}`).val();
            const newLikelihood = (orgLikelihood - (orgLikelihood * (likelihood / 100))).toFixed(1);
            $(`#new_likelihood${riskId}`).val(newLikelihood);
            const newImpact = $(`#new_impact${riskId}`).val();
            getRankOfLikelihoodAndImpact(newLikelihood, newImpact, riskId)
        }).on('change', '.js-impact', function () {
            const riskId = $(this).attr('data-id');
            const impact = $(this).val();
            const orgImpact = $(`#org_impact${riskId}`).val();
            const newImpact = (orgImpact - (orgImpact * (impact / 100))).toFixed(1);
            $(`#new_impact${riskId}`).val(newImpact);
            const newLikelihood = $(`#new_likelihood${riskId}`).val();
            getRankOfLikelihoodAndImpact(newLikelihood, newImpact, riskId)
        }).on('click', '.btn-update-not-fully-implemented-mitigation', function (e) {
            e.preventDefault();
            const url = $(this).attr('data-route');
            const progress = $('#target_process').val();
            const redirectPage = $(this).attr('data-redirect-route');
            const updateButton = $(this);

            if (progress) {
                $.ajax({
                    url,
                    type: 'POST',
                    data: {
                        progress,
                        is_send_mail: true
                    },
                    beforeSend: function () {
                        updateButton.html('<i class="fa fa-spinner fa-spin"></i> Update');
                    },
                    success: function (data) {
                        window.location.href = redirectPage;
                        updateButton.html('Update');
                    },
                    error: function () {
                        alert('Handle error');
                        updateButton.html('Update');
                    }
                })
            } else {
                alert('Please choose process for updating.')
            }
        }).on('click', '.add_resource_required', function (e) {
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
