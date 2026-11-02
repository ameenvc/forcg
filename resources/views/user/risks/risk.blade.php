@extends('pages.datatables')
@section('exportadd')
{{-- <div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div> --}}
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <a href="{{ route('risk.exportData', 'pdf') }}" class="export-link"
                style="padding-right:16px !important;padding-left:16px !important;color:black;width:50px;font-size:10px;border:none;">
                Pdf
            </a>
        </li>
        <li>
            <a href="{{ route('risk.exportData', 'excel') }}" class="export-link"
                style="padding-right:16px !important;padding-left:16px !important;color:black;width:50px;font-size:10px;border:none;">
                Excel
            </a>
        </li>
        <li>
            <a href="{{ route('risk.exportData', 'word') }}" class="export-link"
                style="padding-right:16px !important;padding-left:16px !important;color:black;width:50px;font-size:10px;border:none;">
                Word
            </a>
        </li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/risks/addrisk')}}" class="btn btn-sm btn-dual" style="margin-left:8px;">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<link rel="stylesheet" href="https://cdn.dnatatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
<!-- <link rel="stylesheet" href="{{ asset('css/workflow.css') }}"> -->
<link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
<style>
    /*@media screen and (max-width: 760px),*/
    /*(min-device-width: 768px) and (max-device-width: 1366px) {*/
    /*    body {*/
    /*        margin: 0;*/
    /*        padding: 0;*/
    /*        overflow-x: hidden;*/
    /*        overflow-y: hidden;*/
    /*    }*/

    /*    table {*/
    /*        border-collapse: collapse;*/
    /*        border-spacing: 0;*/
    /*        width: 100%;*/
    /*    }*/

    /*    .pb-1,*/
    /*    .py-1 {*/
    /*        !*padding-bottom: .25rem!important;*!*/
    /*        bottom: 0;*/
    /*        position: relative;*/
    /*        display: inline-block;*/
    /*        clear: both;*/
    /*    }*/
    /*}*/
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
    .breadcrumb li:first-child {
        font-size: 1rem;
        text-transform: uppercase;
    }
    .breadcrumb li:last-child {
        font-size: 1rem;
        color: gray;
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
    .scrollable {
        height: 500px !important;
        overflow-x: scroll;
        overflow-y: scroll;
    }
    #modal-learn-module .close {
        position: absolute;
        right: -30px;
        top: 0;
        z-index: 999;
        font-size: 2rem;
        font-weight: normal;
        color: #fff;
        opacity: 1;
    }
    .width-150 {
        width: 150px !important;
    }

    .m-dialog {
        max-width: 800px;
        margin: 30px auto;
    }

    .m-body {
        position: relative;
        padding: 0px;
    }
    .dropdown-menu.show {
        left: -20px !important;
        min-width: auto !important;
    }
    table.table-bordered.dataTable th {
        border: 1px solid #dee2e6;
    }
</style>
<div class="content" style="width:100%">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item font-w700">Register</li>
            <li class="breadcrumb-item">
                Enterprise Risk
            </li>
        </ol>
    </nav>
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            @if(Session::has('errmsg'))
                <div class="alert alert-danger">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('errmsg')!!}
                </div>
            @endif
            @if(Session::has('msg'))
                <div class="alert alert-primary">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('msg')!!}
                </div>
            @endif
            <div id="content">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                    <input type="hidden" value="a1" id="tabselected">
                    <thead>
                    <tr>
                        <th style="position:sticky;top:-1px"></th>
                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1px;vertical-align: middle;">
                            S.No
                        </th>
                        <td class="d-none"></td>
                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1px;vertical-align: middle;">
                            Risk&nbspID
                        </th>
                        <th class="text-center"
                            id="riskgroupselectdata"
                            data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;">
                        </th>
                        <th class="text-center" id="riskareaselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;">
                        </th>
                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 25%;position:sticky;top:-1px;vertical-align: middle;">
                            Risk&nbsp&nbspName
                        </th>
                        <th class="text-center" id="riskselectdata" data-orderable="false" style="width:5%;text-transform:capitalize;position:sticky;">
                        </th>
                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1px;vertical-align: middle;">
                            <i class="far fa-user" style="font-size:14px;"></i>
                        </th>
                        <th class="text-center" id="riskownerselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;">
                        </th>
                        <th class="text-center" id="riskresponseselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;">
                        </th>
                        <th class="text-center" id="processtageseselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;">
                        </th>
                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px; z-index: 1;vertical-align: middle;">Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; ?>
                        @foreach($data as $value)
                        @php
                            $rr = $value->Rid;
                            $riskowners = explode(',', $value->risk_owner);
                        @endphp
                        <tr>
                            <td><input class="text-center font-size-sm" type="checkbox" id="{{ $value->Rid}}" name="id[]"></td>
                            <td class="text-center " style="font-size:10px;">{{$no=$no+1}}</td>
                            <td class="d-none">{{ $value->Rid }}</td>
                            <td class="text-center " style="font-size:10px;">{{$value->grp_id}}</td>
                            <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                <em class="text-muted">{{$value->riskgroup}}</em>
                            </td>
                            <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                <em class="text-muted">{{$value->department ? $value->department->departmentname : '-'}}</em>
                            </td>
                            <td class="font-w600 " style="font-size:10px;">
                                <a href="{{ route('risk.detail', $value->Rid) }}">{{$value->riskname}}</a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center" style="font-size:12px;">
                                @if($value->rank=="High")
                                    <span class="status status-red" style="font-size:10px;">{{$value->rank }}</span>
                                @elseif($value->rank=="Moderate")
                                    <span class="status status-warning" style="font-size:10px;">{{$value->rank }}</span>
                                @else
                                    <span class="status status-success" style="font-size:10px;">{{$value->rank }}</span>
                                @endif
                            </td>
                            <td class="text-center " style="font-size:10px;">
                                @foreach($riskowners as $riskowner)
                                    @if(isset($riskOwnerData[$riskowner]))
                                        @php
                                            $riskOwnerItem = $riskOwnerData[$riskowner];
                                        @endphp
                                        @if ($riskOwnerItem['profile_picture'] && file_exists(public_path('/storage/profile/'. $riskOwnerItem['profile_picture'])))
                                            <img src="{{ asset("storage/profile/". $riskOwnerItem['profile_picture']) }}"
                                                    class="avatar" alt="{{ $riskOwnerItem['name'] }}">
                                        @else
                                            <img src="{{ asset("/images/avatar10.jpg") }}"
                                                    class="avatar"
                                                    alt="{{ $riskOwnerItem['name'] }}">
                                        @endif
                                    @endif
                                    <br />
                                @endforeach
                            </td>
                            <td class="text-center " style="font-size:10px;">
                                @foreach($riskowners as $riskowner)
                                    @if(isset($riskOwnerData[$riskowner]))
                                        {{ $riskOwnerData[$riskowner]['name'] }}-{{ $riskOwnerData[$riskowner]['employeeid'] }} <br />
                                    @endif
                                @endforeach
                            </td>
                            <td class="text-center text-center" style="font-size:10px;">{{ $value->risk_response }}</td>
                            <td class="d-none d-sm-table-cell text-center" style="font-size:12px;">
                                @if($value->action_status=='Active Risk')
                                    <span class="status status-success" style="font-size:10px;">{{$value->action_status}}</span>
                                @elseif($value->action_status=='Action under review')
                                    <span class="status status-secondary" style="font-size:10px;">{{$value->action_status}}</span>
                                @elseif($value->action_status=='Completed')
                                    <span class="status status-blue" style="font-size:10px;">{{$value->action_status}}</span>
                                @elseif($value->action_status=='Rejected')
                                    <span class="status status-red"  style="font-size:10px;">{{$value->action_status}}</span>
                                @elseif($value->action_status=='Under review')
                                    <span class="status status-warning" style="font-size:10px;">{{$value->action_status}}</span>
                                @elseif($value->action_status=='Awaiting Periodic Review')
                                    <span class="status status-warning" style="font-size:10px;">{{$value->action_status}}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group m-auto d-flex justify-content-center">
                                    <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->id}}">
                                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                    </a>
                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->id }}">
                                        <!-- Status: Under review, Action under review -->
                                        @if(in_array($value->action_status, ['Under review', 'Action under review']))
                                            <!-- Action: Edit -->
                                            @if(Auth::user()->employeeid == $value->postedby)
                                                <a href="{{ route('risk.usereditrisk', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">
                                                    <i class="fas fa-edit" style="font-size:10px;"></i>
                                                    <span class="ml-1">Edit</span>
                                                </a>
                                            @endif
                                            <!-- Action: Initial Review -->
                                            @if ($value->riskreviewer_id && $value->riskreviewer)
                                                @if (Auth::user()->email == $value->riskreviewer->email)
                                                    <a href="{{ url('/user/risks/updaterisk?val='.$rr)}}"
                                                        class="dropdown-item"
                                                        style="font-size:10px;">
                                                        <i class="fas fa-eye" style="font-size:10px;"></i>
                                                        <span class="ml-1">Initial Review</span>
                                                    </a>
                                                @endif
                                            @else
                                                @if(count($email) > 0)
                                                    @foreach($email as $rep)
                                                        @if(Auth::user()->email == $rep)
                                                            <a href="{{ url('/user/risks/updaterisk?val='.$rr)}}"
                                                                class="dropdown-item"
                                                                style="font-size:10px;">
                                                                <i class="fas fa-eye" style="font-size:10px;"></i>
                                                                <span class="ml-1">Initial Review</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif

                                            <!-- Status: Completed -->
                                        @elseif($value->action_status == 'Completed')
                                            <!-- Action: Closure Review -->
                                            @foreach($email as $rep)
                                                @if(Auth::user()->email == $rep)
                                                    <a href="{{ url('/user/risks/closure?val='.$rr)}}"
                                                        class="dropdown-item"
                                                        style="font-size:10px;">Closure Review</a>
                                                @endif
                                            @endforeach
                                            <!-- Status: Active Risk -->
                                        @elseif($value->action_status == 'Active Risk' || $value->action_status == 'Awaiting Periodic Review')
                                            <!-- Action: Edit Action, Completed-100%, Partially
                                            Completed-50%, Progress-25%, Not Started-0% -->
                                            @foreach($riskowners as $riskowner)
                                                @if($riskowner == Auth::user()->email)
                                                    <a href="{{ url('/user/risks/riskreview?val='.$rr)}}"
                                                        class="dropdown-item"
                                                        style="font-size:10px;">
                                                        <i class="fas fa-eye" style="font-size:10px;"></i>
                                                        Periodic Review
                                                    </a>
                                                    {{--                                                        <a href="{{ url('/user/risks/ownrespo?val='.$rr)}}"--}}
                                                    {{--                                                           class="dropdown-item" style="font-size:10px;">Edit Action</a>--}}
                                                    {{--                                                        <a href="{{ url('/user/risks/hundred?val='.$rr)}}"--}}
                                                    {{--                                                           class="dropdown-item" style="font-size:10px;">Completed-100%</a>--}}
                                                    {{--                                                        <a href="{{ url('/user/risks/five?val='.$rr)}}"--}}
                                                    {{--                                                           class="dropdown-item" style="font-size:10px;">Partially Completed-50%</a>--}}
                                                    {{--                                                        <a href="{{ url('/user/risks/two?val='.$rr)}}"--}}
                                                    {{--                                                           class="dropdown-item" style="font-size:10px;">Progress-25%</a>--}}
                                                    {{--                                                        <a href="{{ url('/user/risks/zero?val='.$rr)}}"--}}
                                                    {{--                                                           class="dropdown-item" style="font-size:10px;">Not Started-0%</a>--}}
                                                @endif
                                            @endforeach
                                            <!-- Status: Rejected -->
                                        @elseif($value->action_status == 'Rejected')
                                            <!-- Action: Resubmit Risk -->
                                            @if(Auth::user()->employeeid == $value->postedby)
                                                <a href="{{ url('/user/risks/resubmitrisk?val='.$rr)}}"
                                                    class="dropdown-item" style="font-size:10px;">
                                                    <i class="si si-reload" style="font-size:10px;"></i>
                                                    Resubmit Risk
                                                </a>
                                            @endif
                                        @endif
                                        <!-- Action: Add Opportunity -->
                                        @foreach($riskowners as $riskowner)
                                            @if($value->action_status == 'Active Risk' && $riskowner == Auth::user()->email)
                                                <a href="{{ url('/user/risks/addopportunity/'.$rr)}}"
                                                    class="dropdown-item" style="font-size:10px;">
                                                    <i class="fas fa-plus" style="font-size:10px;"></i>
                                                    Add Opportunity
                                                </a>
                                            @endif
                                        @endforeach
                                        <!-- Action: Risk History -->
                                        <a href="{{ url('/user/risks/riskhistory?val='.$rr)}}"
                                            class="dropdown-item" style="font-size:10px;">
                                            <i class="fas fa-history" style="font-size:10px;"></i>
                                            <span class="ml-1">Risk History</span>
                                        </a>
                                        <!-- Action: Risk History -->
                                        @if(Gate::allows('risk-change-riskowner') && $value->action_status != 'Under review' && $value->risk_owner != NULL)
                                            <a href="{{ url('/user/risks/changeriskowner/'.$rr)}}"
                                                class="dropdown-item" style="font-size:10px;">
                                                <i class="fas fa-sync" style="font-size:10px;"></i>
                                                <span class="ml-1">Change Risk Owner/area</span>
                                            </a>
                                        @endif
                                        <!-- Action: Add Mitigating Action -->
                                        @if(!in_array($value->action_status, ['Under review', 'Rejected']))
                                            <a href="javascript:void(0)"
                                                class="dropdown-item create-mitigation" style="font-size:10px;"
                                                data-id="{{ $value->Rid }}">
                                                <i class="fas fa-arrow-down" style="font-size:10px;"></i>
                                                <span class="ml-1">Add Mitigating Action</span>
                                            </a>
                                        @endif
                                    </div>
                                    <!-- Action: View detail -->
                                    <a href="javascript:void(0)"
                                        class="btn btn-sm btn-alt-primary show-detail"
                                        data-id="{{ $value->Rid }}">
                                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                    </a>
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
    <div class="risk-area"></div>
    {{-- --------delete request button for mr --}}
    @section('sideitem')

        <div class="input-group input-group-sm ml-2">
            <button type="button" class="btn btn-sm btn-dual width-150 video-btn" id="learn_btn" data-toggle="modal" data-src="https://youtube.com/embed/M2qMFyvFPTI" data-target="#modal-learn-module">
                <i class="fa fa-1x fa-video" style="color:grey;"></i><span id="learn" class="ml-2">Learn Module</span>
            </button>
        </div>

        @if(Gate::allows('edit-risk') || Gate::allows('edit-all'))
        <div class="input-group input-group-sm ml-2">
            <button type="button" class="btn btn-sm btn-dual" id="edit" value=id[]>
                <i class="fa fa-1x fa-edit" style="color:grey;"></i>
            </button>
        </div>
        @endif
        @if(Gate::allows('delete-risk') || Gate::allows('delete-all'))
        <div class="input-group input-group-sm ml-2">
            <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
                <i class="fas fa-1x fa-trash" style="color:red;"></i>
            </button>
        </div>
        @endif

    @endsection
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/learn_module.js') }}"></script>
    <script>
        let video_selector = $('#modal-learn-module');
        let video_id_selector = $('#video');
        displayVideo(video_selector, );
        let learn_selector = $('#learn');
        let learn_btn_selector = $('#learn_btn');
        showOrHide(learn_selector, learn_btn_selector);
        $('.export-link').on('click', function (e) {
            e.preventDefault();
            let baseUrl = $(this).attr('href');
            let finalUrl = baseUrl;
            let ids = getVisibleIds('#tabledata');
            let countData = "{{count($data)}}";
            if(countData!=ids.length){
                finalUrl = baseUrl+'?ids='+encodeURIComponent(JSON.stringify(ids));
            }
            window.location.href = finalUrl;
        });
        function getVisibleIds(tableSelector,columnIndex=2) {
            let table = $(tableSelector).DataTable();
            let ids = [];
            table.rows({ search: 'applied' }).every(function () {
                let rowData = this.data();
                ids.push(rowData[columnIndex]);
            });
            return ids;
        }
    </script>
    <script>
        $("#del").on('click', function() {
            let dataArr = [];
            let input_checkbox_selector = $('input:checkbox:checked');
            if (input_checkbox_selector.length > 0){
                input_checkbox_selector.each(function() {
                    dataArr.push($(this).attr('id'));
                    $.ajax({
                        type: 'get'
                        , url: "{{route('risk.deleterisk')}}" + "?val=" + $(this).attr('id')
                        , success: function() {
                            window.location.href = "{{url('/user/riskmgmt')}}";
                        }
                        , error: function(response) {
                            console.log(response);
                        }
                    });
                });

            } else {
                alert('No Record Selected');
            }
        });

        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }

        // window.onresize = autoResizeDiv;
        // autoResizeDiv(window.innerHeight - 100);

    </script>
    {{-- --------edit  button ------------}}
    <script>
        $("#edit").on('click', function() {
            let dataArr = [];
            let group = [];
            let all;
            let input_checkbox_selector = $('input:checkbox:checked');
            if (input_checkbox_selector.length > 0) {
                input_checkbox_selector.each(function() {
                    dataArr.push($(this).attr('id'));
                    all = "" + dataArr;
                    group.push(all); // push all checkbox values into an array

                });
                $.ajax({
                    type: 'get'
                    , url: "{{route('risk.editrisk')}}" + "?val=" + (all)
                    , success: function() {
                        window.location.href = "{{route('risk.editrisk')}}" + "?val=" + (all);
                    }
                    , error: function(response) {
                        console.log(response);
                    }
                });

            } else {
                alert('No Record Selected');
            }
        });
    </script>
    <!-- END Footer -->
    <script>
        $(function () {
            $('body').on('click', '.show-detail', function (e) {
                e.preventDefault();
                const id = $(this).attr('data-id');
                $.ajax({
                    url : window.location.protocol+"//"+window.location.host+`/user/risks/${id}/detail`,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.risk-area').html(data.view);
                            const modal = $(`#viewModal${id}`);

                            if (modal.length) {
                                modal.modal('show');
                            }
                        } else {
                            alert('Data is not found')
                        }
                    }
                })
            }).on('click', '.create-mitigation', function (e) {
                e.preventDefault();
                const id = $(this).attr('data-id');
                $.ajax({
                    url: window.location.protocol+"//"+window.location.host+`/user/risks/${id}/create-mitigation`,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.risk-area').html(data.view);
                            const modal = $(`#modal-mitigating-${id}`);

                            if (modal.length) {
                                modal.modal('show');
                            }
                        } else {
                            alert('Data is not found')
                        }
                    }
                })
            }).on('click', '.add_resource_required', function (e) {
                e.preventDefault();
                const val = $(this).attr('data-value');
                $('#resource_required').val(val);
            });

            const createHeadingSelect = (
                headingName = '', headingId = '',
                values = '', dropdownId = '', columnIndex = 0, caseInsensitive = true
            ) => {
                let nameArr = values.split(',');
                let optionText = "";
                for (let i = 0; i < nameArr.length; i++) {
                    if(nameArr[i] != '') {
                        if (headingId == 'riskownerselectdata') {
                            const $ownerAfterParse = nameArr[i].split('-')
                            optionText += `<option value="${nameArr[i]}">${$ownerAfterParse[0]}</option>`;
                        } else {
                            optionText += `<option value="${nameArr[i]}">${nameArr[i]}</option>`;
                        }
                    }
                }
                $("#tabledata").one("preInit.dt", function() {
                    let table = $('#tabledata').DataTable();
                    // Add index column
                    table.on('draw.dt', function() {
                        let pageInfo = table.page.info();
                        table.rows({ page: 'current' }).every(function(rowIdx, tableLoop, rowLoop) {
                            $('td:eq(1)', this.node()).text(pageInfo.start + rowLoop + 1);
                        });
                    });
                    const heading = $(`<select class="headingselect"
                            style="width:100px;" id="${dropdownId}">
                                <option value="">${headingName}&nbsp;&nbsp;&#9207;</option>${optionText}
                            </select>`);

                    $('#' + headingId).html(heading);
                    $("#" + dropdownId).on('change', function() {
                        if ($(this).val()) {
                            table.columns(columnIndex).search($(this).val(), false, false, caseInsensitive).draw();
                            $(this).addClass('text-primary');
                            $("#" + dropdownId +  "option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });
                });
            }

            // Risk group
            const riskGroups = "{{ $allRiskGroup }}";
            createHeadingSelect('Risk Group', 'riskgroupselectdata', riskGroups, 'riskgroup_dropdown', 4);

            // Risk area
            const riskAreas = "{{ $allRiskArea }}";
            createHeadingSelect('Risk Area', 'riskareaselectdata', riskAreas, 'riskarea_dropdown', 5);

            // rank
            const ranks = "{{ $allRank }}";
            createHeadingSelect('Risk', 'riskselectdata', ranks, 'risk_dropdown', 7);

            // risk owner
            const allRiskOwner = "{{ $allRiskOwner }}";
            createHeadingSelect('Risk Owner', 'riskownerselectdata', allRiskOwner, 'riskowner_dropdown', 9);

            // risk response
            const allRiskResponse = "{{ $allRiskResponse }}";
            createHeadingSelect('Risk Response', 'riskresponseselectdata', allRiskResponse, 'riskresponse_dropdown', 10);

            // process stage
            const allProcessStage = "{{ $allProcessStage }}";
            createHeadingSelect('Process Stage', 'processtageseselectdata', allProcessStage, 'processstage_dropdown', 11, false);
        });
        $(document).ready(function () {
            // Parse query parameters
            const params = new URLSearchParams(window.location.search);
            const openModal = params.get('open_modal');
            const riskId = params.get('id');

            if (openModal === 'true' && riskId) {
                const url = `/user/risks/${riskId}/create-mitigation`; // Update URL as needed
                $.ajax({
                    url,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.risk-area').html(data.view);
                            const modal = $(`#modal-mitigating-${riskId}`);
                            if (modal.length) {
                                modal.modal('show');
                            }
                        } else {
                            alert('Data is not found');
                        }
                        window.history.replaceState(null, "", window.location.protocol + "//" + window.location.host + "/user/riskmgmt");

                    }
                });
            }
        });

    </script>

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
                            {{-- <div class="col-6">
                                        <!-- CRM -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/risks/addrisk">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Add Risk
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END CRM -->
                                    </div> --}}
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/riskmgmt">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Risks
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/analysis">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->
</div>
@endsection
