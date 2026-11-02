@extends('pages.datatables')
@section('exportadd')
{{-- <div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div> --}}
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <a href="{{ route('opportunity.exportData', 'pdf') }}" class="export-link"
                style="padding-right:16px !important;padding-left:16px !important;color:black;width:50px;font-size:10px;border:none;">
                Pdf
            </a>
        </li>
        <li>
            <a href="{{ route('opportunity.exportData', 'excel') }}" class="export-link"
                style="padding-right:16px !important;padding-left:16px !important;color:black;width:50px;font-size:10px;border:none;">
                Excel
            </a>
        </li>
        <li>
            <a href="{{ route('opportunity.exportData', 'word') }}" class="export-link"
                style="padding-right:16px !important;padding-left:16px !important;color:black;width:50px;font-size:10px;border:none;">
                Word
            </a>
        </li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/opportunity/addopportunity')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
{{-- <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}"> --}}
<style>

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
    .status-flat{
        color: #44b3ab;
        background-color: #e0edcf;
        border-color: #bff0dd;
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


    .headingselect{
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border:none;
        line-height:40px;
        font-size: 10px;
        font-weight: bold;
        position:relative;
        display: inline-block;
        text-align: center;
        font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";

    }
    .scrollable2 {
        height: 100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
    .titleheading {
        font-size: 10px !important;
        text-transform: capitalize !important;
        position: sticky !important;
        position: relative !important;
        font-weight:bold !important;
        background-color:#fff;
        font-weight:bold !important;
    }
    .titleheading2{
        min-width: 100px !important;
    }
    #firstcolumn::after{
        content:'';
    }
</style>
<input type="hidden" value="a1" id="tabselected">
<div class="content" style="width:100%">
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
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item font-w700">Register</li>
            <li class="breadcrumb-item">
                Opportunity
            </li>
        </ol>
    </nav>
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="table-responsive scrollable2" id="content">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                    <thead>
                        <tr style="position: sticky; top:0;z-index:5;">
                            <td class="text-center" id="firstcolumn" data-orderable="false" style="background-color:#fff;"></td>
                            <td class="text-center titleheading" data-orderable="false">S.No</td>
                            <td class="d-none"></td>
                            <td class="text-center titleheading" style="max-width:120px;" data-orderable="false">ID</td>
                            <td id="deptselectdata" class="text-center titleheading titleheading2" style="max-width:120px;" data-orderable="false"></td>
                            <td id="responsibleselectdata" class="titleheading" data-orderable="false" style="max-width:120px;"></td>
                            <td class="text-center titleheading" data-orderable="false" style="min-width:200px;">Opportunity Name</td>
                            <td id="duedateselectdata" class="text-center titleheading titleheading2" style="margin: 0px;padding: 5px" data-orderable="false"></td>
                            <td id="typeselectdata" class="text-center titleheading titleheading2" data-orderable="false"></td>
                            <td class="text-center titleheading" data-orderable="false" style="min-width:90px !important;max-width:100px; margin: 0px;padding: 5px">Added date</td>
                            <td id="impactselectdata" class="text-center titleheading" style="min-width:40px !important;max-width:100px; margin: 0px;padding: 5px" data-orderable="false"></td>
                            <td id="stageselectdata" class="text-center titleheading" data-orderable="false" style="min-width:40px !important;max-width:80px; margin: 0px;padding: 5px"></td>
                            <td class="text-center titleheading" data-orderable="false"  style="min-width:80px !important;max-width:100px; margin: 0px;padding: 5px">Action Status</td>
                            <td class="text-center titleheading" data-orderable="false">Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=0;?>
                        @foreach($data as $value)
                        @php $rr=$value->id;
                            $dept = DB::table('departments')->where('id',$value->department)->value('departmentname');
					    @endphp
                        <tr>
                            <td><input class="checkbox" type="checkbox" id="{{ $value->id}}" name="id[]"></td>
                            <td class="text-center" style="font-size:10px;">{{$loop->iteration}}</td>
                            <td class="d-none">{{ $value->id }}</td> 
                            <td class="text-center font-w600" style="font-size:10px;">
                                <a href="{{ route('opportunity.detail', $value->id) }}">{{$value->dep_id }}</a>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                {{$dept}}
                                <div style="display: none;">{{ 'd_'.$dept.'_s'}}</div>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                {{ $value->byresponsibility ? $value->byresponsibility->name : ''}}-{{ $value->byresponsibility ? $value->byresponsibility->employeeid : ''}}
                                <div style="display: none;">{{$value->byresponsibility ? 'd_'.$value->byresponsibility->name.'_s' : ''}}</div>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                {{$value->oppname}}
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                @if($value->tardate <= date('Y-m-d'))
                                <span class="status status-red" data-toggle="tooltip" title='Due' style="font-size:10px;">{{date('d-m-Y',strtotime($value->tardate))}}</span>
                                @else
                                <span class="status status-success" style="font-size:10px;">{{date('d-m-Y',strtotime($value->tardate))}}</span>
                                @endif
                            </td>
                            <td style="font-size:12px;" class="text-center">
                                <em class="text-center " style="font-size:10px;">
                                    <?php
                                        $typesname = explode(',', $value->type);
                                        $typesnames = DB::table("opportunity_types")->whereIn('id',$typesname)->pluck('name')->toArray()??[];
                                    ?>
                                    @foreach($typesnames as $type)
                                    <div style="display: none;">{{'d_'.$type.'_s'}}</div>
                                    @endforeach
                                    {{implode(', ',$typesnames)}}

                                </em>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                {{date('d-m-Y',strtotime($value->createdat))}}
                            </td>
                            <td class="text-center" style="font-size:12px;">
                                @if($value->impact=="Low")
                                <span class="status" style="font-size: 10px; background-color: #e0edcf; color: #176044;">{{$value->impact }}</span>
                                @elseif($value->impact=="Moderate")
                                <span class="status" style="font-size: 10px; background-color: #ffebcc; color: #cc8800;">{{$value->impact }}</span>
                                @else
                                <span class="status" style="font-size: 10px;background-color:#f8d4d4;color:#af1e1e;">{{$value->impact }}</span>
                                @endif
                            </td>
                            <td class="text-center" style="font-size:12px;">
                                @if($value->status=='Approved')
                                <span class="status status-success" style="font-size: 10px;">{{$value->status}}</span>
                                @elseif($value->status=='Completed')
                                <span class="status status-blue" style="font-size:10px;">{{$value->status}}</span>
                                @elseif($value->status=='Disapproved')
                                <span class="status status-red" style="font-size:10px;">{{$value->status}}</span>
                                @elseif($value->status=='Review Pending')
                                <span class="status" style="font-size: 10px;background-color: #ffebcc; color: #cc8800;">{{$value->status}}</span>
                                @endif
                                <div style="display: none;">{{'d_'.$value->status.'_s'}}</div>
                            </td>
                            <td style="font-size:10px;width:150px;">
                                @if($value->actionstatus=='Progress-25%')
                                    <div class="progress mb-1" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 25%;background-color:#F57C00;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="fs-xs fw-semibold mb-0">25%</p>
                                @elseif($value->actionstatus=='Partially Completed-50%')
                                    <div class="progress mb-1" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 50%;background-color:#FFC107;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="fs-xs fw-semibold mb-0">50%</p>
                                @elseif($value->actionstatus=='Completed-100%')
                                    <div class="progress mb-1" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 100%;background-color:#4CAF50;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="fs-xs fw-semibold mb-0">100%</p>
                                @elseif($value->actionstatus=='Not Started-0%')
                                    <div class="progress mb-1" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 10%;background-color:#D32F2F;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="fs-xs fw-semibold mb-0">0%</p>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group m-auto d-flex justify-content-center">
                                    <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->id}}">
                                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                    </a>
                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->id }}">
                                        @if(Auth::user()->employeeid==$value->postedby && $value->status=="Review Pending")
                                        <a href="{{ route('opportunity.usereditopp', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Edit</a>
                                        @endif
                                        {{-- start of logic used for opportunity, after introduction of opportunity approver --}}
                                        @if ($value->opportunityapprover)
                                            @if ($value->opportunityapprover->email == Auth::user()->email && $value->status=='Review Pending')
                                                    <a href="{{ route('opportunity.inireviewopp', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Approve</a>
                                            @endif
                                        @else
                                            @foreach($opportunityapprovers as $opportunityapprover)
                                                @if(Auth::user()->email == $opportunityapprover->email && $value->status=='Review Pending')
                                                    <a href="{{ route('opportunity.inireviewopp', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Approve</a>
                                                @endif
                                            @endforeach
                                        @endif
                                        {{-- end of logic used for opportunity, after introduction of opportunity approver --}}
                                        @if($value->responsibility == Auth::user()->email && $value->status=='Approved')
                                        <a href="{{ route('opportunity.secreviewopp', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Update Progress</a>

                                        @endif

                                        @if ($value->closurereviewer)
                                            @if ($value->closurereviewer->email == Auth::user()->email && $value->status=='Completed')
                                                <a href="{{ route('opportunity.closreviewopp', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Closure Review</a>
                                            @endif
                                        @else
                                            @foreach($closurervrs as $closurervr)
                                                @if(Auth::user()->email==$closurervr && $value->status=='Completed')
                                                    <a href="{{ route('opportunity.closreviewopp', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Closure Review</a>
                                                @endif
                                            @endforeach
                                        @endif

                                        @if($value->status == "Disapproved" && Auth::user()->employeeid==$value->postedby)
                                        <a href="{{ route('opportunity.resubmitopp', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Resubmit</a>
                                        @endif


                                        @if(Gate::allows('opportunity-responsibility-change'))
                                        <a href="{{ route('opportunity.changeoppresponsibility', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Responsibility change</a>
                                        @endif

                                    </div>
                                    <a href="#" data-route="{{$value->id}}" class="btn btn-sm btn-alt-primary btn-view-detail" data-id="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View">
                                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                    </a>
                                </div>


                            </td>


                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>
    <!-- END Dynamic Table Full -->
<div class="view-modal"></div>
</div>


@section('sideitem')
@if(Gate::allows('edit-opportunity') || Gate::allows('edit-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" style="margin-left:432px; " class="btn btn-sm btn-dual" id="edit" value=id[]>
        <i class="fa fa-1x fa-edit" style="color:grey;"></i>
    </button>
</div>
@endif
@if(Gate::allows('delete-opportunity') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("#tabledata").one("preInit.dt", function() {
            let table = $('#tabledata').DataTable();
            // Add index column
            table.on('draw.dt', function() {
                let pageInfo = table.page.info();
                table.rows({ page: 'current' }).every(function(rowIdx, tableLoop, rowLoop) {
                    $('td:eq(1)', this.node()).text(pageInfo.start + rowLoop + 1);
                });
            });
            let alltypenames = "{{ $alltypenames }}".split(',');
            let type_text = "";
            for (var i = 0; i < alltypenames.length; i++) {
                type_text += `<option value="${alltypenames[i]}">${alltypenames[i]}</option>`;
            }
            $typeheading = $(`<select class="headingselect" style="min-width:fit-content;border:none;" id="type_dropdown">
                <option value="">Type&nbsp;&nbsp;&#9207;</option>` +
                type_text +
                `</select>`);
            $('#typeselectdata').html($typeheading);
            $("#type_dropdown").on('change', function() {
                console.log($(this).val());
                if ($(this).val()) {
                    table.columns(8).search('d_'+$(this).val()+'_s').draw();
                    $(this).addClass('text-primary');
                    $("#type_dropdown option").css('color', 'black')
                } else {
                    location.reload();
                }
            });
        });

        let allpstages = "{{ $allpstages }}".split(',');
        let stage_text = "";
        for (var i = 0; i < allpstages.length; i++) {
            stage_text += `<option value="${allpstages[i]}">${allpstages[i]}</option>`;
        }
        $("#tabledata").one("preInit.dt", function() {
            let table = $('#tabledata').DataTable();
            $stageheading = $(`<select class="headingselect" style="" id="stage_dropdown">
                <option value="">Process Stage&nbsp;&nbsp;&#9207;</option>` + stage_text +
                `</select>`);

            $('#stageselectdata').html($stageheading);
            $("#stage_dropdown").on('change', function() {
                console.log($(this).val());
                if ($(this).val()) {
                    table.columns(11).search('d_'+$(this).val()+'_s').draw();
                    $(this).addClass('text-primary');
                    $("#stage_dropdown option").css('color', 'black')
                } else {
                    location.reload();
                }
            });
        });

        let years = "{{ $years }}".split(',');
        let duedate_text = "";
        for (var i = 0; i < years.length; i++) {
            duedate_text += `<option value="${years[i]}">${years[i]}</option>`;
        }

        $("#tabledata").one("preInit.dt", function() {
            let table = $('#tabledata').DataTable();
            $duedateheading = $(`<select class="headingselect" style="min-width:fit-content;" id="year_dropdown">
                <option value="">Target date&nbsp;&nbsp;&#9207;</option>` +
                duedate_text +
                `</select>`);
            $('#duedateselectdata').html($duedateheading);
            $("#year_dropdown").on('change', function() {
                console.log($(this).val());
                if ($(this).val()) {
                    table.columns(7).search($(this).val()).draw();
                    $(this).addClass('text-primary');
                    $("#year_dropdown option").css('color', 'black')
                } else {
                    location.reload();
                }
            });

        });

        let alldeptnames = "{{ $alldeptnames }}".split(',');
        let dept_text = "";
        for (var i = 0; i < alldeptnames.length; i++) {
            dept_text += `<option value="${alldeptnames[i]}">${alldeptnames[i]}</option>`;
        }
        $("#tabledata").one("preInit.dt", function() {
            let table = $('#tabledata').DataTable();
            $deptheading = $(`<select class="headingselect" style=" max-width: 100px;" id="dept_dropdown">
                <option value="">Department&nbsp;&nbsp;&#9207;</option>` + dept_text +
                `</select>`);

            $('#deptselectdata').html($deptheading);
            $("#dept_dropdown").on('change', function() {
                console.log($(this).val());
                if ($(this).val()) {
                    table.columns(4).search('d_'+$(this).val()+'_s').draw();
                    $(this).addClass('text-primary');
                    $("#dept_dropdown option").css('color', 'black')
                } else {
                    location.reload();
                }
            });
        });

        let allimpactstages = "{{ $allimpactstages }}".split(',');
        let impact_text = "";
        for (var i = 0; i < allimpactstages.length; i++) {
            impact_text += `<option value="${allimpactstages[i]}">${allimpactstages[i]}</option>`;
        }
        $("#tabledata").one("preInit.dt", function() {
            let table = $('#tabledata').DataTable();
            $impactheading = $(`<select class="headingselect" style="" id="impact_dropdown">
                <option value="">Positive Impact&nbsp;&nbsp;&#9207;</option>` + impact_text +
                `</select>`);

            $('#impactselectdata').html($impactheading);
            $("#impact_dropdown").on('change', function() {
                console.log($(this).val());
                if ($(this).val()) {
                    table.columns(10).search($(this).val()).draw();
                    $(this).addClass('text-primary');
                    $("#impact_dropdown option").css('color', 'black')
                } else {
                    location.reload();
                }
            });
        });

        let allusernames = "{{ $allusernames }}".split(',');
        let all_user_text = "";
        for (var i = 0; i < allusernames.length; i++) {
            all_user_text += `<option value="${allusernames[i]}">${allusernames[i]}</option>`;
        }
        $("#tabledata").one("preInit.dt", function() {
            let table = $('#tabledata').DataTable();
            $alluserheading = $(`<select class="headingselect" style="border:none; max-width: 100px;" id="all_user_dropdown">
                <option value="">Responsibility&nbsp;&nbsp;&#9207;</option>` + all_user_text +
                `</select>`);

            $('#responsibleselectdata').html($alluserheading);
            $("#all_user_dropdown").on('change', function() {
                console.log($(this).val());
                if ($(this).val()) {
                    table.columns(5).search('d_'+$(this).val()+'_s').draw();
                    $(this).addClass('text-primary');
                    $("#all_user_dropdown option").css('color', 'black')
                } else {
                    location.reload();
                }
            });
        });

    });
    $('body').on('click', '.btn-view-detail', function () {
        const id = $(this).attr('data-route');
        $.ajax({
            url : window.location.protocol + "//" + window.location.host +`/user/opportunity/view/${id}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.success) {
                    $('.view-modal').html(data.view)
                    const modalEle = $(`#viewModal${data.id}`);
                    modalEle.modal('show');
                } else {
                    alert('Data is not found');
                }
            },
            error: function () {
                alert('Handle error');
            }
        });
    });  
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
    var route_opportunity_oppindex = "{{ route('opportunity.oppindex') }}";

    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);

</script>
<script src="{{ asset('js/opportunity/opportunity_script.js') }}"></script>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="{{ route('opportunity.oppindex') }}">
                                <div class="block-content text-center">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/opportunity/accomplished">
                                <div class="block-content text-center">
                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Accomplished
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>

                        <div class="col-6">
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/opportunity/analysis'>
                                <div class="block-content text-center">
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
@endsection