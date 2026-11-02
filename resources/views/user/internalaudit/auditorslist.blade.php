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
            <form method="post" action="{{url('/user/convert/file/'.'tvalue2')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue2" name="tvalue2" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/internalaudit/addauditors')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>
    <style>
        .move {
            margin-left: 6%;
            position: absolute;
            top: -10px;
        }

        body {
            overflow-y: auto;
        }

        .modal-second.show {
            background: #80808096;
        }

    </style>

</head>
<body>
    <?php $data=$data3=$data4=[];?>
    <input type="hidden" value="a2" id="tabselected">
    <div class="content" style="width:100%">
        <div class="block block-rounded" style="margin-left:-1%;">
            <div class="block-content block-content-full">
                @if(Session::has('msg'))
                    <div class="alert alert-primary">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                @endif
                <div class="scrollable" style="height:450px;overflow-y:scroll; position: relative" id="content">
                    <table class="move" width="65%">
                        <tr>
                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-left:1%;">
                                        <li class="breadcrumb-item">Audit Management</li>
                                        <li class="breadcrumb-item" aria-current="page">
                                            <a class="link-fx" href="">Auditors List</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                    </table>
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1;"></th>

                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1;">S.No</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1;">Auditor Avatar</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1;">Auditors Name</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1;">Experience</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 8%;position:sticky;top:-1;">Action</th>
{{--                                @if(in_array(Auth::user()->email, $z->toArray()))--}}
{{--                                @if(Gate::allows('edit-audit') || Gate::allows('edit-all'))--}}
{{--                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1;">Edit</th>--}}
{{--                                @endif--}}
{{--                                @endif--}}
{{--                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">View</th>--}}
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=0;?>
                            @foreach($data1 as $value)
                            <?php  $rr=$value->id;

                                //giving unique id to each + button
                                $btnid=$value->id."1";
                                $spnid=$value->id."2";
                                 ?>

                            <tr>
                                <td><input class="text-center" type="checkbox" id="{{$btnid}}" name="id[]"></td>
                                <td class="text-center " style="font-size:10px;">{{ $no=$no+1}}</td>
                                <td>
                                    @if($value->user)
                                        <span>
                                            <img src="{{ $value->user ? $value->user->avatar_path : asset('/images/avatar10.jpg') }}"
                                                 class="avatar" alt="{{ $value->auditorsname }}">
                                        </span>
                                    @else
                                        <span>
                                            <img src="{{ asset('/images/avatar10.jpg') }}"
                                                 class="avatar" alt="{{ $value->auditorsname }}">
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center " style="font-size:10px;">{{$value->auditorsname}}</td>

                                <td style="font-size:10px;">{{ $value->experience }}</td>
                                <td class="text-center">
                                    @if(in_array(Auth::user()->email, $z->toArray()) && Gate::allows('edit-audit'))
                                    <div class="btn-group m-auto d-flex justify-content-center">
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->Oid}}">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                        <div class="dropdown-menu font-size-sm"
                                             aria-labelledby="dropdown-dropleft-outline-dark"
                                             id="dropdownMenu{{ $value->id }}">
                                            @if($value->user)
                                                <a href="{{ url('/user/internalaudit/editauditor?val='.$rr)}}" style="font-size:10px" class="dropdown-item">
                                                    <i class="fa fa-fw fa-pencil-alt" style="font-size:10px;"></i> Edit
                                                </a>
                                            @else
                                                <a href="#" style="font-size:10px" class="dropdown-item" data-toggle="modal" data-target="#doNotAllowEdit">
                                                    <i class="fa fa-fw fa-pencil-alt" style="font-size:10px;"></i> Edit
                                                </a>
                                           @endif
                                        </div>
                                    @else
                                    <div class="btn-group m-auto">
                                    @endif
                                        <a href="#" class="btn btn-sm btn-alt-primary view-audit-detail"
                                           data-route="{{ route('user.viewAudit', $value->id) }}">
                                            <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                        </a>
                                    </div>
                                </td>


{{--                                @if(in_array(Auth::user()->email, $z->toArray()))--}}
{{--                                @if(Gate::allows('edit-audit'))--}}
{{--                                <td class="text-center " style="font-size:10px;">--}}
{{--                                    --}}{{-- @foreach($z as $mr) --}}
{{--                                    --}}{{-- @if(Auth::user()->email==$mr) --}}
{{--                                    --}}{{-- @if(Gate::allows('edit-audit')) --}}
{{--                                    <a href="{{ url('/user/internalaudit/editauditor?val='.$rr)}}" class="btn btn-primary" style="font-size:10px;width: 50px;">Edit</a>--}}
{{--                                    --}}{{-- @endif --}}
{{--                                    --}}{{-- @endif --}}
{{--                                    --}}{{-- @endforeach --}}
{{--                                </td>--}}
{{--                                @endif--}}
{{--                                @endif--}}
{{--                                <td>--}}
{{--                                </td>--}}

                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->
        <div class="audit-detail-area"></div>

        <!--Norecords modal starts-->
        <div class="modal fade" id="doNotAllowEdit" tabindex="-1"
             role="dialog" aria-labelledby="doNotAllowEdit" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">This data cannot be edited as this user doesn't exist</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--</div>
        </div>-->


    @section('sideitem')
    {{-- @foreach($mrs as $mr) --}}
    {{-- @if(Auth::user()->email==$mr) --}}
    @if(Gate::allows('delete-audit') || Gate::allows('delete-all'))
        <div class="input-group input-group-sm ml-2">
            <button type="button" class="btn btn-sm btn-dual" style="margin-left:477px;" id="delete" value=id[]>
                <i class="fas fa-1x fa-trash" style="color:red;"></i>
            </button>
        </div>
    @else
        <div class="input-group input-group-sm ml-2">
            <button type="button" class="btn btn-sm btn-alt-danger disabled" style="margin-left:477px;">
                <i class="fas fa-1x fa-trash" style="color:red;"></i>
            </button>
        </div>
    @endif
    {{-- @endif --}}
    {{-- @endforeach --}}
    @endsection
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $("#delete").click(function() {
            var dataArr = new Array();
            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));

                    $.ajax({
                        type: 'get'
                        , url: "{{route('deleteauditor')}}" + "?val=" + $(this).attr('id')
                        , success: function(response) {
                            // alert(response);
                            window.location.href = "{{url('/user/internalaudit/auditorslist')}}";
                            //  redirect('/user/doc/masterlist')->withData($data);
                        }
                        , error: function(response) {
                            alert(errResponse);
                        }
                    });
                });

            } else {
                alert('No Record Selected');
            }
        });

        $('.view-audit-detail').click(function () {
            const url = $(this).attr('data-route');
            $.ajax({
                type: 'get'
                , url: url
                , success: function(response) {
                    if (response.success) {
                        $('.audit-detail-area').html(response.view);
                        const viewModal = $('#viewModal');
                        if (viewModal.length) {
                            viewModal.modal('show');
                        }
                    } else {
                        alert(response.message);
                    }
                }
                , error: function(response) {
                    alert(errResponse);
                }
            });
        })

        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);

    </script>


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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Audit Schedule
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-address-card fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Auditors List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br>Audit Log
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Audits
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->
    @include('exporting.forauditmanagement');

</body>
</html>
<script>
    //javascript function for showing and hiding ohs risks
    function show_hide(id, bid, sid) {
        //for display the hidden elements
        if (document.getElementById(bid).value == "+") {
            document.getElementById(bid).value = "-";
            document.getElementById(sid).className = "fa fa-angle-down text-muted";
            document.getElementById(id).style.display = "";
        }
        //for hide the tbody elements
        else {
            document.getElementById(bid).value = "+";
            document.getElementById(sid).className = "fa fa-angle-right text-muted";
            document.getElementById(id).style.display = "none";
        }
    }

</script>
<!--  added for mouseover  -->
<script>
    $(".tiptext").mouseover(function() {
        $(this).children(".description").show();
    }).mouseout(function() {
        $(this).children(".description").hide();
    });

</script>
@endsection
