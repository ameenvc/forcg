@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0"
            style="margin-left:8px; " data-toggle="dropdown" data-display="static">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf
                </button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li>
            <button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel
            </button>
        </li>
        <li>
            <button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word
            </button>
        </li>
    </ul>
</div>

<div class="input-group input-group-sm">
</div>
@endsection
@section('content')
@once
<link rel="stylesheet" href="{{ asset('css/risk-tables.css') }}?v={{ filemtime(public_path('css/risk-tables.css')) }}">
@endonce

@include('shared.forms.assets')
<script defer src="{{ asset('js/risk-workflow.js') }}?v={{ filemtime(public_path('js/risk-workflow.js')) }}"></script>
<link rel="stylesheet" href="{{ asset('css/risk-register.css') }}?v={{ filemtime(public_path('css/risk-register.css')) }}">
<link rel="stylesheet" href="{{ asset('css/risk-workflow.css') }}?v={{ filemtime(public_path('css/risk-workflow.css')) }}">

    <link rel="stylesheet" href="{{ asset('css/workflow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
    <style>
        @media screen and (max-width: 760px),
        (min-device-width: 768px) and (max-device-width: 1366px) {
            body {
                margin: 0;
                padding: 0;
                overflow-x: hidden;
                overflow-y: auto;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
            }

            .pb-1,
            .py-1 {
                /*padding-bottom: .25rem!important;*/
                bottom: 0;
                position: relative;
                display: inline-block;
                clear: both;
            }
        }
        .modal-second.show {
            background: #80808096;
        }
        .headingselect {
            white-space: break-spaces;
            line-height: 1rem;
        }
        .badge {
            font-size: 100%;
        }
        .modal-detail-mitigation {
            font-size: 1rem;
        }
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
        .status{
            display: inline-block;
            padding: 7px 5px 5px 7px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .status-blue{
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }

        .status-red{
            color: #af1e1e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }
        .status-warning{
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
        }
        .status-secondary{
            color: #343a40;
            background-color: #E5E4E2;
            border-color: #edf0f2;
        }
        .status-success{
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .close-org {
            float: right;
            font-size: 1.5rem;
            font-weight: 600;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: .5;
            position: initial;
            cursor: pointer;
        }
        .alert {
            border-radius: 0;
            box-shadow: none;
        }
        .breadcrumb li:first-child {
            font-size: 1rem;
            text-transform: uppercase;
        }
        .breadcrumb li:last-child {
            font-size: 1rem;
            color: gray;
        }
        .tooltip-inner {
            max-width: 250px;
        }
    </style>

    <?php
    $data1 = [];
    $data2 = [];
    ?>
    <div class="content risk-register-page risk-workflow-page workflow-mitigation" style="width:100%">
        @include('user.risks.register-header', ['registerPage' => 'mitigation', 'registerTitle' => 'Mitigation actions', 'registerDescription' => 'Turn risk decisions into owned actions, track progress, and verify completion.'])
        @if($riskId)<div class="workflow-scope" role="status">Showing actions for the selected risk. <a href="{{ route('risk.detail', $riskId) }}">Open risk details</a> to review the assessment and manage its actions. <a class="ml-2" href="{{ route('mitigations.index') }}">Show all actions</a></div>@endif
        <div class="block">
            <div class="block-content block-content-full">
                <div class="table-responsive" id="content">

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
                    <input type="hidden" value="a1" id="tabselected">
                    <table class="risk-module-table table table-striped table-vcenter js-dataTable-full" id="tabledata">
                        <colgroup><col style="width:38px"><col style="width:44px"><col style="width:100px"><col style="width:300px"><col style="width:180px"><col style="width:145px"><col style="width:95px"><col style="width:110px"><col style="width:160px"><col style="width:120px"></colgroup>
                        <thead>
                            <tr>
                                <td style="position:sticky;top:-1px; width: 5%"></td>
                                <td class="text-center titleheading sorting" style="width:5%;"
                                    tabindex="0" aria-controls="tabledata" rowspan="1" colspan="1"
                                    aria-label="S.No: activate to sort column ascending">S.No</td>
                                <td class="text-center titleheading sorting" style="width:5%;"
                                    tabindex="0" aria-controls="tabledata" rowspan="1" colspan="1"
                                    aria-label="Risk&nbspID: activate to sort column ascending">Action&nbspID</td>
                                <td class="text-center titleheading sorting" style="width:10%;"
                                    tabindex="0" aria-controls="tabledata" rowspan="1" colspan="1"
                                    aria-label="Risk&nbspID: activate to sort column ascending">Action Name</td>
                                <td id="ownerselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;"></td>
                                <td id="deptselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;"></td>
                                <td id="priorityselectdata" data-orderable="false" style="width:5%;text-transform:capitalize;position:sticky;"></td>
                                <td id="duedateselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;"></td>
                                <td id="statusselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;"></td>
                                <td class="text-center titleheading sorting" style="width:5%;"
                                    data-orderable="false"
                                    tabindex="0" aria-controls="tabledata" rowspan="1" colspan="1"
                                    aria-label="Mitigation action">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mitigations as $key => $mitigation)
                            <tr>
                                <td>
                                    <input class="text-center font-size-sm" type="checkbox"
                                           id="{{ $mitigation->id }}" name="id[]" aria-label="Select action {{ $mitigation->action_name }}">
                                </td>
                                <td class="text-center " style="font-size:10px;">
                                    {{ $key + 1 }}
                                </td>
                                <td class="text-center " style="font-size:10px;">
                                    {{ $mitigation->action_id }}
                                </td>
                                <td class="text-left" style="font-size:10px;">
                                    <a class="workflow-name" title="{{ $mitigation->action_name }}" href="{{ route('mitigations.detail', $mitigation->id) }}">
                                        {{ $mitigation->action_name }}
                                    </a>
                                </td>
                                <td class="text-left" style="font-size:12px;">
                                    <div class="workflow-owner">
                                        @if($mitigation->user)
                                        <img src="{{ $mitigation->user->avatar_path }}" class="avatar" alt="">
                                        <span title="{{ $mitigation->user->name }}">{{ $mitigation->user->name }}</span>
                                        @else
                                        <span>Unassigned</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center" style="font-size:10px;">
                                    {{ $mitigation->department ? $mitigation->department->departmentname : '-'}}
                                </td>
                                <td class="text-center" style="font-size:10px;">
                                    @if($mitigation->priority_level == 'high')
                                        <span class="text-danger font-w700">High</span>
                                    @elseif($mitigation->priority_level == 'medium')
                                        <span class="text-warning font-w700">Medium</span>
                                    @elseif($mitigation->priority_level == 'low')
                                        <span class="text-success font-w700">Low</span>
                                    @endif
                                </td>

                                <td class="text-center" style="font-size:10px;">
                                    {{ date("d/m/Y", strtotime($mitigation->due_date)) }}
                                </td>

                                <td class="text-center" style="font-size:10px;">
                                    @include('user.risks.mitigation._status')
                                </td>
                                <td class="text-center" style="font-size:10px;">
                                    <div class="btn-group m-auto d-flex justify-content-center">
                                        <!-- Action: Edit/Delete/Closure review/Reopen  -->
                                        <div class="dropdown">
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false"
                                               style="border-radius: 0;"
                                               data-placement="top" title="Actions" id="drop{{ $mitigation->id }}">
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="drop{{ $mitigation->id }}"
                                                 id="dropdownMenu{{ $mitigation->id }}">
                                                @if(in_array($currentUserId, [$mitigation->created_by, $mitigation->owner])
                                                    || in_array($currentUserId, $reviewerIds)
                                                )
                                                    <!-- Action: Edit -->
                                                    @if($mitigation->status != config('constants.risk_management.mitigation.closed'))
                                                        <a href="javascript:void(0)" class="dropdown-item btn-edit-mitigation"
                                                           style="font-size:10px;"
                                                           data-route="{{ route('mitigations.edit', $mitigation->id) }}">
                                                            <i class="fas fa-edit" style="font-size:10px;"></i>
                                                            <span class="ml-1">Edit</span>
                                                        </a>
                                                    @endif
                                                    <!-- Action: Closure review -->
                                                    @if ($mitigation->progress == 100 && $mitigation->status != config('constants.risk_management.mitigation.closed'))
                                                        <a href="{{ route('mitigations.closure.review', $mitigation->id) }}"
                                                           class="dropdown-item"
                                                           style="font-size:10px;">
                                                            <i class="fas fa-window-close" style="font-size:10px;"></i>
                                                            <span class="ml-1">Closure review</span>
                                                        </a>
                                                    @endif
                                                    <!-- Action: Reopen -->
                                                    @if($mitigation->status == config('constants.risk_management.mitigation.closed')
                                                        && in_array($currentUserId, $reviewerIds))
                                                        <a href="{{ route('mitigations.reopen', $mitigation->id) }}"
                                                           class="dropdown-item"
                                                           style="font-size:10px;">
                                                            <i class="si si-reload" style="font-size:10px;"></i>
                                                            <span class="ml-1">Reopen</span>
                                                        </a>
                                                    @endif
                                                    <!-- Action: Delete -->
                                                    @if($mitigation->status != config('constants.risk_management.mitigation.closed'))
                                                        <a href="javascript:void(0)" class="dropdown-item btn-delete-mitigation"
                                                           style="font-size:10px;"
                                                           data-route="{{ route('mitigations.destroy', $mitigation->id) }}">
                                                            <i class="fas fa-trash" style="font-size:10px;"></i>
                                                            <span class="ml-1">Delete</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="javascript:void(0)" class="dropdown-item disabled"
                                                       style="font-size:10px; opacity: 0.5">
                                                        <i class="fas fa-edit" style="font-size:10px;"></i>
                                                        <span class="ml-1">Edit</span>
                                                    </a>
                                                    @if ($mitigation->progress == 100 || $mitigation->status != config('constants.risk_management.mitigation.closed'))
                                                        <a href="javascript:void(0)" class="dropdown-item disabled"
                                                           style="font-size:10px; opacity: 0.5">
                                                            <i class="fas fa-window-close" style="font-size:10px;"></i>
                                                            <span class="ml-1">Closure review</span>
                                                        </a>
                                                    @endif
                                                    <!-- Action: Reopen -->
                                                    @if($mitigation->status == config('constants.risk_management.mitigation.closed')
                                                        && in_array($currentUserId, $reviewerIds))
                                                        <a href="javascript:void(0)" class="dropdown-item disabled"
                                                           style="font-size:10px;">
                                                            <i class="fas fa-box-open" style="font-size:10px;"></i>
                                                            <span class="ml-1">Reopen</span>
                                                        </a>
                                                    @endif
                                                    <a href="javascript:void(0)" class="dropdown-item disabled"
                                                       style="font-size:10px; opacity: 0.5">
                                                        <i class="fas fa-trash" style="font-size:10px;"></i>
                                                        <span class="ml-1">Delete</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Action: Change status -->
                                        <div class="dropdown">
                                            <a href="#" class="btn btn-sm btn-alt-primary {{ $mitigation->progress == 100 ? 'disabled' : '' }}" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false"
                                               style="border-radius: 0;"
                                               data-placement="top"
                                               data-toggle-second="tooltip" title="Click here to update status"
                                               id="dropdownChangeStatus{{ $mitigation->id }}">
                                                <i class="fas fa-tasks" style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                            @if($mitigation->progress != 100)
                                                <div class="dropdown-menu font-size-sm"
                                                     aria-labelledby="dropdownChangeStatus{{ $mitigation->id }}"
                                                     id="progressMenu{{ $mitigation->id }}">
                                                    @foreach(config('constants.risk_management.mitigation.progresses') as $key => $progress)
                                                        <a href="javascript:void(0)" class="dropdown-item change-status"
                                                           data-route="{{ route('mitigations.change.progress', $mitigation->id) }}"
                                                           data-value="{{ $key }}"
                                                           style="font-size:10px;">
                                                            <span class="ml-1">{{ $progress }}</span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <!-- Action: View detail -->
                                        <a href="#" class="btn btn-sm btn-alt-primary"
                                           data-id="{{ $mitigation->id }}"
                                           data-toggle="modal"
                                           data-placement="top" title="View"
                                           data-target="#viewModal{{ $mitigation->id }}">
                                            <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                        </a>
                                        @include('user.risks.mitigation._mitigation_detail')
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            <!-- END Dynamic Table Full -->
        </div>
        <div class="mitigation-area"></div>
        <div class="modal" id="modal-learn-module" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog m-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-body m-body">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <!-- 16:9 aspect ratio -->

                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://youtube.com/embed/M2qMFyvFPTI" id="video" allowscriptaccess="always" allow="autoplay"></iframe>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        {{-- --------delete request button for mr --}}
        @section('sideitem')
        <div class="input-group input-group-sm ml-2">
            <button type="button" class="btn btn-sm btn-dual width-150 video-btn" id="learn_btn" data-toggle="modal" data-src="https://youtube.com/embed/M2qMFyvFPTI" data-target="#modal-learn-module">
                <i class="fa fa-1x fa-video" style="color:grey;"></i><span id="learn" class="ml-2">Learn Module</span>
            </button>
        </div>
        @endsection
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script>
            $(function () {
                $.ajaxSetup({
                    headers : {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $('#tabledata').one('init.dt', function () {
                $(this).wrap('<div class="workflow-table-scroll" role="region" aria-label="Scrollable table" tabindex="0"></div>');
            });
                const createHeadingSelect = (
                    headingName = '', headingId = '',
                    values = '', dropdownId = '', columnIndex = 0, caseInsensitive = true
                ) => {
                    let nameArr = values.split(',');
                    let optionText = "";
                    for (let i = 0; i < nameArr.length; i++) {
                        if(nameArr[i]) {
                            optionText += `<option value="${nameArr[i]}">${nameArr[i].replaceAll('|', ', ')}</option>`;
                        }
                    }
                    $("#tabledata").one("preInit.dt", function() {
                        let table = $('#tabledata').DataTable();
                        const heading = $(`<select class="headingselect"
                            style="width:120px;margin-left:10px;" id="${dropdownId}">
                                <option value="">${headingName}&nbsp;&nbsp;&#9207;</option>${optionText}
                            </select>`);

                        $('#' + headingId).html(heading);
                        $("#" + dropdownId).on('change', function() {
                            if ($(this).val()) {
                                table.columns(columnIndex).search($(this).val(), null, null, caseInsensitive).draw();
                                $(this).addClass('text-primary');
                                $("#" + dropdownId +  "option").css('color', 'black')
                            } else {
                                table.columns(columnIndex).search('').draw();
                                $(this).removeClass('text-primary');
                            }
                        });
                    });
                }

                // Owner
                const ownerNames = @json($allOwnerNames);
                createHeadingSelect('Owner', 'ownerselectdata', ownerNames, 'owner_dropdown', 4, false);

                // Department
                const departmentNames = @json($allDeptNames);
                createHeadingSelect('Department', 'deptselectdata', departmentNames, 'dept_dropdown', 5);

                // Priority
                const priorityNames = @json($allPriorityLevel);
                createHeadingSelect('Priority Level', 'priorityselectdata', priorityNames, 'priority_dropdown', 6);

                // Year
                const dueDateYears = @json($allYears);
                createHeadingSelect('Due date', 'duedateselectdata', dueDateYears, 'duedate_dropdown', 7);

                // Status
                const statusNames = @json($allStatus);
                createHeadingSelect('Status', 'statusselectdata', statusNames, 'status_dropdown', 8);

                $(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
                    $(".alert-success").slideUp(500);
                });
                $(".alert-danger").fadeTo(2000, 500).slideUp(500, function(){
                    $(".alert-danger").slideUp(500);
                });
                $('body').on('click', '.btn-edit-mitigation', function () {
                    const url = $(this).attr('data-route');
                    $.ajax({
                        url,
                        type: 'GET',
                        success: function (data) {
                            if (data.success) {
                                $('.mitigation-area').html(data.view)
                                const modalEle = $(`#modal-mitigating-${data.risk_id}`);
                                modalEle.modal('show');
                                modalEle.find('.js-select2').each(function() {
                                    const limitSelection = $(this).attr('data-limit-selection')
                                    let dropdownParent = $(document.body);
                                    if ($(this).parents('.modal.new-mitigating-popup:first').length !== 0) {
                                        dropdownParent = $(this).parents('.modal.new-mitigating-popup:first');
                                    }
                                    $(this).select2({
                                        dropdownParent: dropdownParent,
                                        width: '100%',
                                        tags: true,
                                        multiple: true,
                                        maximumSelectionLength: limitSelection ? limitSelection : 0
                                    });
                                });
                            } else {
                                alert('Data is not found')
                            }
                        },
                        error: function () {
                            alert('Handle error');
                        }
                    })
                }).on('click', '.btn-delete-mitigation', function () {
                    if (confirm('Are you sure to delete the mitigation?')) {
                        const url = $(this).attr('data-route');
                        $.ajax({
                            url,
                            type: 'DELETE',
                            success: function (data) {
                                location.reload();
                            },
                            error: function () {
                                alert('Handle error');
                            }
                        })
                    }
                }).on('click', '.change-status', function () {
                    const url = $(this).attr('data-route');
                    const progress = $(this).attr('data-value');
                    $.ajax({
                        url,
                        type: 'POST',
                        data: {
                            progress
                        },
                        success: function (data) {
                            location.reload();
                        },
                        error: function () {
                            alert('Handle error');
                        }
                    })
                }).on('click', '.add_resource_required', function (e) {
                    e.preventDefault();
                    const val = $(this).attr('data-value');
                    $('#resource_required').val(val);
                });

                $('[data-toggle-second="tooltip"]').tooltip({
                    placement: 'bottom',
                    trigger: 'hover'
                }).click(function () {
                    $('[data-toggle-second="tooltip"]').tooltip("hide");
                });
            })
        </script>
    </div>

@endsection
