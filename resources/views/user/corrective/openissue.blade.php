@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue3" name="tvalue3" />
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel </button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/corrective/addcorrective')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/workflow.css') }}">

    <style>
        body {
            overflow-y: auto;
        }

        .status-red {
            color: #af1e1e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }

        .status-red-light {
            color: #e27e7e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }

        .status-warning {
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
        }

        .status-gray {
            color: #737373;
            background-color: #e6e6e6;
            border-color: #fbf4ea;
        }

        .status-blue {
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }

        .status-success {
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }

        .status-success-light {
            color: #488a71;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }

        .disabled-link {
            pointer-events: none;
            color: #999 !important;
            text-decoration: none;
        }



        .status {
            display: inline-block;
            padding: 7px 5px 5px 7px;
            font-size: 75%;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .titleheading {
            font-size: 10px !important;
            text-transform: capitalize !important;
            position: sticky !important;
            position: relative !important;
            top: 2px !important;
            font-weight: 600 !important;
        }

        .td-class {
            text-align: center !important;
            font-size: 10px !important;
        }

        .breadcrumb>li+li:before {
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

        .badge-class {
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
        .scrollable{
            height:100%;
            overflow-x: scroll;
            overflow-y: scroll;
        }

    </style>


    <?php
    $issues=$issues1=$issues3=[];
    ?>
    <input type="hidden" value="a3" id="tabselected">
    <!--Start of Change Log  -->
    <!-- Page Content -->
    <div class="tab-pane active" id="My_open" role="tabpanel" style="">
        <div class="content" style="width:100%">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item font-w700">My Open Corrective Actions</li>
                    <li class="breadcrumb-item">
                        Corrective action
                    </li>
                </ol>
            </nav>


            <!-- Dynamic Table Full -->
            <div class="block block-rounded">

                <div class="block-content block-content-full">

                    <!--shift header to white background-->
                    <div class="scrollable" id="content">

                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                            <thead>
                                <tr>
                                    <th class="text-center" data-orderable="false" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1;padding-bottom:25px;">Slno</th>
                                    <th class="text-center" data-orderable="false" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1;padding-bottom:25px;">Id</th>
                                    <th class="text-center" data-orderable="false"style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1;padding-bottom:25px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th id="categoryselectdata" class="text-center" data-orderable="false" style="text-transform:capitalize;position:sticky;width:30px;"></th>
                                    <th id="typeselectdata" class="text-center" data-orderable="false" style="text-transform:capitalize;width:30px;"></th>
                                    <th id="deptselectdata" class="text-center" data-orderable="false" style="text-transform:capitalize;position:sticky;width:30px;"></th>
                                    <th id="duedateselectdata" class="text-center" data-orderable="false" style="text-transform:capitalize;position:sticky; width:30px;"></th>
                                    <th id="stageselectdata" class="text-center" data-orderable="false" class="text-center" style="text-transform:capitalize;position:sticky;width:30px;"></th>
                                    <th class="text-center" data-orderable="false" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1;">Action Status</th>
                                    <th class="text-center" data-orderable="false" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1;z-index:99;padding-bottom:25px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>
                                @foreach ($issues2 as $issue)
                                <?php $rr=$issue->id;
                                    $date = $issue->correction_target_date;
                                    $today = Date("Y-m-d");
                                    $issuedocs=explode(',',$issue->file);
                                    $actiondocs=explode(',',$issue->action_file);
                                    $dept = DB::table('departments')->where('id',$issue->correction_department)->value('departmentname');
                                    $cat = $issue->cat ? $issue->cat->categoryname : '';
                                ?>
                                <tr>

                                    <td class="text-center " style="font-size:10px;">{{ $no=$no+1}}</td>
                                    <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                        @if (($issue->status == 'open' || $issue->status == 'NULL')&& $today > $issue->correction_target_date)
                                        <span class="status status-red" data-toggle="popover" data-placement="right" data-content="Due">{{$issue->issueid}}</span>
                                        @else
                                        <span class="status status-success">{{$issue->issueid}}</span>
                                        @endif
                                    </td>
                                    <td class=" font-w600 " style="font-size:10px;">
                                        <a href="{{ route('corrective.detail', $issue->id) }}">{{$issue->title}}</a>
                                    </td>
                                    @if($cat=="Internal Audit")
                                    <td class="text-center " style="font-size:10px;width: 5%;color:#0033cc">{{$cat}}</td>
                                    @elseif($cat=="External Audit")
                                    <td class="text-center " style="font-size:10px;width: 5%;color:orange">{{$cat}}</td>
                                    @elseif($cat=="Management Review")
                                    <td class="text-center " style="font-size:10px;width: 5%;color:#33cc33">{{$cat}}</td>
                                    @elseif($cat=="Corrective Action")
                                    <td class="text-center " style="font-size:10px;width: 5%;color:#ff0066">{{$cat}}</td>
                                    @elseif($issue->category=='Legal Requirement')
                                    <td class="text-center" style="font-size:10px;width: 5%;color:#ff0066">{{$issue->category}}</td>
                                    @elseif($issue->category=='Incident Investigation')
                                    <td class="text-center" style="font-size:10px;width: 5%;color:#ff0066">{{$issue->category}}</td>
                                    @elseif($issue->category=="Management Review")
                                    <td class="text-center " style="font-size:10px;width: 5%;color:#33cc33">{{$issue->category}}</td>
                                    @else
                                    <td class="text-center " style="font-size:10px;width: 5%;color:black">{{$cat}}</td>
                                    @endif
                                    <td class="text-center" style="font-size:12px;">
                                        @if($issue->type=='' || $issue->type=='-')
                                        <span class="status status-blue">-</span>
                                        @elseif($issue->typ)
                                        <span style="font-size:10px;background-color:{{ $issue->typ->background_color }};color:{{ $issue->typ->text_color}}" class="badge-class">{{$issue->typ ? $issue->typ->name : ''}}</span>
                                        @else
                                        <span class="status status-blue"></span>
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        <em class="text-muted">{{$dept}}</em>
                                    </td>

                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        <em class="text-muted">{{$issue->correction_target_date ? date('M/d/Y',strtotime($issue->correction_target_date)) : '' }}</em>
                                    </td>
                                    <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                        @if($issue->processstage=='Awaiting actions')
                                        <span class="status status-gray">Awaiting actions</span>
                                        @elseif($issue->processstage == 'Correction Proposed- RC Awaiting' || $issue->processstage == 'Actions Proposed- Review Awaiting' || $issue->processstage == 'RC Updated- Correction Awaiting')
                                        <span class="status status-blue">{{$issue->processstage}}</span>
                                        @elseif($issue->processstage=='Action Reviewed, OK')
                                        <span class="status status-success">Action Approved</span>
                                        @elseif($issue->processstage=='Action Reviewed, Not OK' /*|| $issue->processstage=='rejected'*/)
                                        <span class="status status-red">Action Rejected</span>
                                        @elseif($issue->processstage=='rejected')
                                        <span class="status status-red">Entry Rejected</span>
                                        @elseif($issue->processstage=='Need More Action' || $issue->processstage=='Entry Under Review')
                                        <span class="status status-warning">{{$issue->processstage}}</span>
                                        @elseif($issue->processstage=='Actions Completed')
                                        <span class="status status-blue">{{$issue->processstage}}</span>
                                        {{-- @elseif($issue->processstage=='Approved')
                                        <span class="status status-success">{{$issue->processstage}}</span> --}}
                                        @endif

                                    </td>
                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $issue->average_percent }}%;" aria-valuenow="{{ $issue->average_precent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">{{ $issue->average_percent }}%</p>
                                    </td>

                                    <div class="dropdown font-size-sm">
                                        <td>
                                            <div class="btn-group m-auto d-flex justify-content-center">
                                                <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$issue->id}}">
                                                    <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                                </a>
                                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $issue->id }}">
                                                    @if ($issue->processstage =='Awaiting actions' || $issue->processstage == 'RC Updated- Correction Awaiting')
                                                    <a style="font-size:10px;" class="dropdown-item" href="{{url('/user/corrective/proposeaction?val='.$rr)}}">Propose Action</a>
                                                    {{-- @elseif ($issue->processstage =='Action Reviewed, Not OK' || $issue->processstage =='Action Reviewed, OK' || $issue->processstage == 'Need More Action' || $issue->processstage == 'Correction Proposed- RC Awaiting' || $issue->processstage == 'Actions Proposed- Review Awaiting' || $issue->processstage == 'RC Updated- Correction Awaiting') --}}
                                                    @endif
                                                    <a style="font-size:10px;" class="dropdown-item {{ $issue->processstage == 'Awaiting actions' || $issue->processstage == 'RC Updated- Correction Awaiting' ? 'disabled-link' : ''}}" href="{{url('/user/corrective/editrespondedissue?val='.$rr)}}">Edit</a>
                                                    @if( $issue->processstage=='Action Reviewed, OK' )
                                                    <a href="{{ route("user.corrective.update_percentage", ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Update Progress</a>
                                                    @endif

                                                </div>
                                                <a href="#" class="btn btn-sm btn-alt-primary btn-view-detail viewDetails" data-route="{{ $issue->id }}">
                                                    <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </div>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="corrective-area"></div>
            <div id="view-detail"></div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            function ViewBtn(id) {
                $('#viewModal' + id).modal('show');
            }

            $(document).ready(function() {
                $(".viewDetails").on('click', function() {
                    var id = $(this).attr('data-route');
                    $.ajax({
                        url: window.location.protocol + "//" + window.location.host+"/show/" + id + "/" + "corrective"
                        , type: 'get'
                        , data: {
                            id: id
                        }
                        , dataType: "json"
                        , success: function(response) {
                            response.data.responsibleUser ? $('#responsible' + id).attr('src', '/storage/profile/' + response.data.responsibleUser.profile_picture) : $('#responsible').attr('src', '');
                            response.data.postedByUser ? $('#postedbyUser' + id).attr('src', '/storage/profile/' + response.data.postedByUser.profile_picture) : $('#postedbyUser').attr('src', '');
                        }
                    });
                });

                $("#tabledata").one("preInit.dt", function() {
                    let table = $('#tabledata').DataTable();

                    let alltypenames = "{{ $alltypenames }}".split(',');
                    let type_text = "";
                    for (var i = 0; i < alltypenames.length; i++) {
                        type_text += `<option value="${alltypenames[i]}">${alltypenames[i]}</option>`;
                    }
                    $typeheading = $(`<select class="headingselect" style="border:none;" id="type_dropdown">
                         <option value="">Type&nbsp;&nbsp;&#9207;</option>` +
                        type_text +
                        `
                     </select>`);
                    $('#typeselectdata').html($typeheading);
                    $("#type_dropdown").on('change', function() {
                        if ($(this).val()) {
                            table.columns(4).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#type_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });
                    let allcategoriesname = "{{ $allcategoriesname }}".split(',');
                    let catgy_text = "";
                    for (var i = 0; i < allcategoriesname.length; i++) {
                        catgy_text += `<option value="${allcategoriesname[i]}">${allcategoriesname[i]}</option>`;
                    }
                    $catgyheading = $(`<select class="headingselect" style="margin-left:10px;" id="catgy_dropdown">
                            <option value="">Category&nbsp;&nbsp;&#9207;</option>` + catgy_text +
                        `
                        </select>`);

                    $('#categoryselectdata').html($catgyheading);
                    $("#catgy_dropdown").on('change', function() {
                        if ($(this).val()) {
                            table.columns(3).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#catgy_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });

                    let alldeptnames = "{{ $alldeptnames }}".split(',');
                    let dept_text = "";
                    for (var i = 0; i < alldeptnames.length; i++) {
                        dept_text += `<option value="${alldeptnames[i]}">${alldeptnames[i]}</option>`;
                    }
                    $deptheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;" id="dept_dropdown">
                            <option value="">Department&nbsp;&nbsp;&#9207;</option>` + dept_text +
                        `
                        </select>`);

                    $('#deptselectdata').html($deptheading);
                    $("#dept_dropdown").on('change', function() {
                        if ($(this).val()) {
                            table.columns(5).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#dept_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });

                    let years = "{{ $years }}".split(',');
                    let duedate_text = "";
                    for (var i = 0; i < years.length; i++) {
                        duedate_text += `<option value="${years[i]}">${years[i]}</option>`;
                    }


                    $duedateheading = $(`<select class="headingselect" style="width:100px;" id="year_dropdown">
                        <option value="">Due&nbspDate&nbsp;&nbsp;&#9207;</option>` +
                        duedate_text +
                        `
                    </select>`);
                    $('#duedateselectdata').html($duedateheading);
                    $("#year_dropdown").on('change', function() {
                        if ($(this).val()) {
                            table.columns(6).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#year_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });


                    let allpstages = "{{ $allpstages }}".split(',');
                    let stage_text = "";
                    for (var i = 0; i < allpstages.length; i++) {
                        stage_text += `<option value="${allpstages[i]}">${allpstages[i]}</option>`;
                    }
                    $stageheading = $(`<select class="headingselect" style="width:85px !important;" id="stage_dropdown">
                             <option value="">Process Stage&nbsp;&nbsp;&#9207;</option>` + stage_text + `
                         </select>`);

                    $('#stageselectdata').html($stageheading);
                    $("#stage_dropdown").on('change', function() {
                        if ($(this).val()) {
                            // search the exact word
                            table.column(7).search("^" + $(this).val() + "$", true, false).draw();
                            $(this).addClass('text-primary');
                            $("#stage_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });


                });

            });

            function autoResizeDiv(height) {
                document.getElementById('content').style.height = (height) + 'px';
            }
            window.onresize = autoResizeDiv;
            autoResizeDiv(window.innerHeight - 100);

        </script>
        <script>
            $('body').on('click', '.view-moc-detail', function () {
                const id = $(this).attr('data-route');
                $.ajax({
                    url : window.location.origin+`/user/context/changelog/${id}/show`,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('#view-detail').html(data.view)
                            const modalEle = $(`#viewModal${data.id}`);
                            modalEle.modal('show');
                        } else {
                            alert('Data is not found')
                        }
                    },
                    error: function () {
                        alert('Handle error');
                    }
                })
            });
        </script>
        <script>
            $('body').on('click', '.btn-view-detail', function () {
                const id = $(this).attr('data-route');
                $.ajax({
                    url : window.location.protocol + "//" + window.location.host+`/user/corrective/corrissues/${id}/show`,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.corrective-area').html(data.view)
                            const modalEle = $(`#viewModal${data.id}`);
                            modalEle.modal('show');
                        } else {
                            alert('Data is not found')
                        }
                    },
                    error: function () {
                        alert('Handle error');
                    }
                })
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

                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/corrective/analysis'>
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
        <!-- END Apps Modal -->
    </div>
    @include('exporting.forcorrective');
@endsection
