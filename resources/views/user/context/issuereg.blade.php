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
            <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/context/addissue')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')

{{--<style>
.header{
    margin-left:7%;
    margin-bottom:-3.6%;
}
</style>--}}
<style>
    body {
        overflow-y: auto;
    }

</style>
<?php
    $issues2=[];
    ?>
<input type="hidden" value="a1" id="tabselected">

<div class="tab-pane active" id="Register" role="tabpanel">
    <!-- Page Content -->

    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
            <div class="block-content block-content-full">
                <div class="scrollable" style="overflow-y:scroll;" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Context Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">Issue Register</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;"></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1">S.No</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1">Title</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1">Type</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1">Issue</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1">Purpose</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1">Monitoring</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1">Bias</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1">Accountability</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>
                                @foreach($issues as $issue)
                                <?php

                                                $rr=$issue->id;

                                                $today = Date("Y-m-d");
                                                $color = null ;?>
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{$issue->id}}" name="id[]"></td>
                                    <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                    <td class="text-center " style="font-size:10px;">{{$issue->title}}</td>
                                    <td class="text-center " style="font-size:10px;">
                                        {{$issue->type}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center " style="font-size:10px;">
                                        {{$issue->issue}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                        {{$issue->purpose}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center " style="font-size:10px;">
                                        {{$issue->monitoring}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center " style="font-size:10px;">
                                        {{$issue->bias}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                        {{$issue->accountability}}
                                    </td>

                                    <td class="text-center">
                                        
                                        <button type="button" class="btn btn-sm btn-alt-primary" style="font-size:10px;width: 50px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal{{$issue->id}}"><i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i></button>
                                        <div id="viewModal{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">

                                                        <tr>
                                                            <th class="text-left" style="width:70px;">Title</th>
                                                            <td class="text-left">{{$issue->title}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-left">Type</th>
                                                            <td class="text-left">{{$issue->type}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-left"> Issue</th>
                                                            <td class="text-left"><?php echo nl2br($issue->issue)?></td>
                                                        </tr>

                                                        <tr>
                                                            <th class="text-left">Purpose</th>
                                                            <td class="text-left">{{$issue->purpose}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th class="text-left">Monitoring</th>
                                                            <td class="text-left">
                                                                <?php echo nl2br($issue->monitoring)?>
                                                            </td>
                                                        <tr>
                                                            <th class="text-left">Bias</th>
                                                            <td class="text-left">{{$issue->bias}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th class="text-left">Accountability</th>
                                                            <td class="text-left">{{$issue->accountability }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-left">Status</th>
                                                            <td class="text-left">{{$issue->status}}</td>
                                                        </tr>


                                                        <tr>
                                                            <th class="text-left">Added Date</th>
                                                            <td class="text-left">{{$issue->created_at}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-left">Last Reviewed</th>
                                                            <td class="text-left">{{$issue->updated_at}}</td>
                                                        </tr>

                                                    </table>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
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
    </div>

    <!-- END Page Content -->
    <!--Delete Button -->

    <!-- end of delete Button-->
    {{----------delete request button --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $("#delissues").click(function() {
            var dataArr = new Array();
            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                    // console.log(dataArr);
                    $.ajax({
                        type: 'get'
                        , url: "{{route('deleteissue')}}" + "?val=" + $(this).attr('id')
                        , success: function(response) {
                            // alert(response);
                            window.location.href = "{{url('/user/contextmgmt')}}";
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

    </script>
    {{-----------edit button ------------}}
    <script>
        $("#editissues").click(function() {
            var dataArr = new Array();
            var group = new Array();
            var d;
            var all;
            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                    //  console.log(dataArr);

                    all = "" + dataArr;
                    group.push(all); // push all checkbox values into an array
                    //  console.log(group);


                });
                $.ajax({
                    type: 'get'
                    , url: "{{route('editissues')}}" + "?val=" + (all)
                    , success: function(response) {
                        // alert(response);

                        window.location.href = "{{route('editissues')}}" + "?val=" + (all);
                        // console.log(response);

                    }
                    , error: function(response) {
                        alert(errResponse);
                    }
                });

            } else {
                alert('No Record Selected');
            }
        });

        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);

    </script>
    <!--End of Issue Register -->


    <!-- END Main Container -->


    <!-- END Footer -->

    <!-- Apps Modal -->

    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">More</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row gutters-tiny">

                            <div class="col-6">
                                <!-- CRM -->
                                <a class="block block-rounded block-link-shadow bg-body" href="{{url('/user/contextmgmt')}}">
                                    <div class="block-content text-center">
                                        <i class="fa fa-bug fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Issue Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END CRM -->
                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/context/interestparty">
                                    <div class="block-content text-center">
                                        <i class="fa fa-2x fa-id-card-alt text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            InterestedParty Register
                                        </p>
                                    </div>
                                </a>

                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/context/changelogreg">
                                    <div class="block-content text-center">
                                        <i class="fa fa-2x fa-clipboard-list text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Change Log Register
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

<!-- END Apps Modal -->
@include('exporting.forcontextmanagement')
@endsection


@section('sideitem')
{{-- @foreach($mr as $mrs) --}}
{{-- @if(Auth::user()->email==$mrs) --}}
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" style="margin-left:398px;" id="editissues" value="id[]">
        <i class="fa fa-1x fa-edit" style="color:grey;"></i>
    </button>
</div>
<div class="input-group input-group-sm ml-2">
    <button type="button" id="delissues" value="id[]" class="btn btn-sm btn-dual">
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
{{-- @endif --}}
{{-- @endforeach --}}
@endsection
