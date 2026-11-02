@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class="btn btn-sm btn-dual btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue3" name="tvalue3" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{ url('/user/context/changelog') }}" style="margin-left:8px;" class="btn btn-sm btn-dual">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<style>
    .scrollable{
        height:100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
</style>
<?php
    $issues=[];
    ?>
<input type="hidden" value="a3" id="tabselected">

<!--Start of Change Log  -->

<div class="tab-pane active" id="My_open" role="tabpanel">
    <!-- Page Content -->

    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
            <div class="block-content block-content-full">
                <div class="scrollable" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Context Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">Change Log Register</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th style="font-size:10px;text-transform:capitalize;position:sticky;top:-1"></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">CR#</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Title</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Category</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Initiator</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Department</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Changing Product/Service</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Priority</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Impact</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Status</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Action Status</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">View</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=0;@endphp
                                @foreach ($issues2 as $issue)
                                @php $rr=$issue->id;
                                $today = Date("Y-m-d");
                                $class=null;
                                @endphp
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{ $issue->id}}" name="id[]"></td>
                                    <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                    <td class="text-center" style="font-size:10px;min-width:130px;">{{$issue->crid}}</td>
                                    <td class="text-center" style="font-size:10px;">
                                        {{$issue->title}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                        @php 
                                            if($issue->category){
                                                $catIds = $issue->category ? explode(',',$issue->category) : [];
                                                $names = $changeCategories->whereIn('id',$catIds)->pluck('category')->toArray();
                                                $namesString = implode(',',$names);
                                            }else{
                                                $namesString = '';
                                            }
                                        @endphp
                                        {{$namesString}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                        @if($issue->initiatorBy)
                                            {{$issue->initiatorBy->name}}
                                        @else
                                            {{$issue->initiator}}
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                        @if($issue->departmentDetails)
                                            {{$issue->departmentDetails->departmentname}}
                                        @else
                                            {{$issue->department}}
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                        {{$issue->changing_product}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                        @if($issue->priorityDetails)
                                            {{$issue->priorityDetails->name}}
                                        @else
                                            {{$issue->priority}}
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                        {{$issue->severity ? $issue->severity->name : null}}
                                    </td>
                                    <td style="font-size:12px;">
                                        @if($issue->status=="Review Pending")
                                        <?php $class="badge badge-info" ?>
                                        <span class="badge badge-info">{{$issue->status}}</span>
                                        @elseif($issue->status=="Reviewed")
                                        <?php $class="badge badge-success" ?>
                                        <span class="badge badge-success">{{$issue->status}}</span>
                                        @elseif($issue->status=="Approved")
                                        <?php $class="badge badge-primary" ?>
                                        <span class="badge badge-primary">{{$issue->status}}</span>
                                        @elseif($issue->status=="Rejected")
                                        <?php $class="badge badge-danger" ?>
                                        <span class="badge badge-danger"> {{$issue->status}}</span>
                                        @elseif($issue->status=="Partially Completed")
                                        <?php $class="badge badge-warning" ?>
                                        <span class="badge badge-warning"> {{$issue->status}}</span>
                                        @elseif($issue->status=="Not Started")
                                        <?php $class="badge badge-warning" ?>
                                        <span class="badge badge-warning"> {{$issue->status}}</span>
                                        @elseif($issue->status=="Completed")
                                        <?php $class="badge badge-info" ?>
                                        <span class="badge badge-info"> {{$issue->status}}</span>
                                        @elseif($issue->status=="Closed")
                                        <?php $class="badge badge-success" ?>
                                        <span class="badge badge-success"> {{$issue->status}}</span>
                                        @else
                                        <?php $class="badge badge-warning" ?>
                                        <span class="badge badge-warning">{{$issue->status}}</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        @if($issue->action_status=="Progress-25%")
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">25%</p>
                                        @elseif($issue->action_status=="Partially Completed-50%")
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">50%</p>
                                        @elseif($issue->action_status=="Completed-100%")
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">100%</p>
                                        @elseif($issue->action_status=="Not Started-0%")
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">0%</p>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-view-detail" style="font-size:10px;"
                                            data-route="{{ $issue->id }}">
                                            View
                                        </a>
                                    </td>
                                    @php 

                                    @endphp
                                    <td class="text-center">
                                        {{-- @if((Auth::user()->name==$issue->rev_authority && $issue->status=='Review Pending') || --}}
                                        {{-- (Auth::user()->name==$issue->appr_authority && $issue->status=='Reviewed') || (Auth::user()->name==$issue->appr_authority && $issue->status=='Completed')) --}}
                                        <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;" data-toggle="dropdown">Actions</button>
                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                            @if($currentUserDetail->id==$issue->appr_authority && $issue->status=='Completed' && $issue->action_status == "Completed-100%")
                                                <a href="{{ route('closreviewcontext', ['id' => $rr])}}" class="dropdown-item" style="font-size:10px;">Closure Review</a>
                                            @elseif($currentUserDetail->id==$issue->appr_authority && $issue->status!='Completed')
                                                <a href="{{ route('user.context.hundred', ['id' => $rr])}}" class="dropdown-item" style="font-size:10px;">Completed-100%</a>
                                                <a href="{{ route('user.context.five', ['id' => $rr])}}" class="dropdown-item" style="font-size:10px;">Partially Completed-50%</a>
                                                <a href="{{ route('user.context.two', ['id' => $rr])}}" class="dropdown-item" style="font-size:10px;">Progress-25%</a>
                                                <a href="{{ route('user.context.zero', ['id' => $rr])}}" class="dropdown-item" style="font-size:10px;">Not Started-0%</a>
                                                <div class="dropdown-divider"></div>
                                            @endif
                                            @if($currentUserDetail->id==$issue->rev_authority && $issue->status=='Review Pending')
                                                <a href="{{ url('/user/context/review?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Review</a>
                                            @elseif($currentUserDetail->id==$issue->appr_authority && $issue->status=='Reviewed')
                                                <a href="{{ url('/user/context/approve?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Approve</a>
                                            @elseif($issue->approved_by!=NULL && $issue->status!='Rejected')
                                                <a href="{{ url('/user/context/changelog/addohsrisk/'.$rr.'/'.$issue->crid)}}" class="dropdown-item" style="font-size:10px;">Add Health and Safefty Risks</a>
                                                <a href="{{ url('/user/context/changelog/addenvaspect/'.$rr.'/'.$issue->crid)}}" class="dropdown-item" style="font-size:10px;">Add Environmental Aspects</a>
                                            @endif
                                            
                                        </div>
                                        {{-- @endif --}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
        <div id="view-detail"></div>
        <!-- END Dynamic Table Full -->

        <!-- Delete Button-->

        <!--End of  Delete Button-->
        {{-- --------delete request button  --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            $("#delcr").click(function() {
                var dataArr = new Array();
                if ($('input:checkbox:checked').length > 0) {
                    $('input:checkbox:checked').each(function() {
                        dataArr.push($(this).attr('id'));
                        // console.log(dataArr);
                        $.ajax({
                            type: 'get'
                            , url: "{{route('deletecr')}}" + "?val=" + $(this).attr('id')
                            , success: function(response) {
                                // alert(response);
                                window.location.href = "{{url('/user/context/changelogreg')}}";
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
            $("#editcr").click(function() {
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
                        , url: "{{route('editcr')}}" + "?val=" + (all)
                        , success: function(response) {
                            // alert(response);

                            window.location.href = "{{route('editcr')}}" + "?val=" + (all);
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
        <script>
            $('body').on('click', '.btn-view-detail', function () {
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

    </div>
    <!-- End of Change Log-->

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
<!-- Opens from the modal toggle button in the header -->

@include('exporting.forcontextmanagement');
@endsection

@section('sideitem')
@foreach($mr as $mrs)
@if(Auth::user()->email==$mrs)
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" style="margin-left:393px;" id="editcr" value=id[]>
        <i class="fa fa-1x fa-edit" style="color:grey;"></i>
    </button>
</div>
<div class="input-group input-group-sm ml-2">
    <button type="button" id="delcr" value=id[] class="btn btn-sm btn-dual">
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
@endforeach
@endsection
