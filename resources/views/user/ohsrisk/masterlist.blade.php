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
    <a type="button" href="{{route('addohsrisk', ['type' => $type])}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
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
<?php $data2=[]; ?>
<input type="hidden" value="a1" id="tabselected">
<div class="tab-pane active" id="Register" role="tabpanel">
    <div class="tab-pane active" id="Register" role="tabpanel">
        <!-- Page Content -->

        <div class="content" style="width:100%;">
            <!-- Dynamic Table Full -->
            <div class="block block-rounded" style="margin-left:-1%;">
                {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                <div class="block-content block-content-full">
                    <div style="overflow-x:hidden;overflow-y:auto;">
                        <table class="move" width="15%" style="margin-left:670px">
                            <tr>

                                <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                        <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                            <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Health and Safety Risk</li>
                                            <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                <a class="link-fx" href="">Master List</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </th>
                            </tr>
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">OHS Risk Id</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Activity</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Department</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Hazard</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Original Risk</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Residual Risk</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Category</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Type of Risk</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">R/NR/EM</th>

                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Monitoring</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Process Stage</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=0;?>
                                    @foreach($data1 as $value)
                                    <?php $rr=$value->id;
                                //giving unique id to each + button
                                $btnid=$value->actid."1";
                                $spnid=$value->actid."2";
                                ?>
                                    <tr>
                                        <td class="text-center " style="font-size:10px;">{{ $no=$no+1}}<b><button style="font-weight:bold;color:blue;border:none;background:none;outline:none" id="{{$btnid}}" value="+" onclick="show_hide('{{$value->actid}}','{{$btnid}}','{{$spnid}}')"><span id="{{$spnid}}" class="fa fa-angle-right text-muted"></span></button></b></td>
                                        <td class="text-center " style="font-size:10px;color:primary">{{ $value->actid }}</td>
                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted" style="font-size:10px;">{{ $value->activity }}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted" style="font-size:10px;">{{ $value->dept ? $value->dept->departmentname : '-' }}</em>
                                        </td>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <div class="dropdown">
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary dropdown-toggle" style="font-size:10px;" id="dropdown-dropleft-outline-dark" data-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                                    <a class='dropdown-item' href="{{ url('/user/ohsrisk/addmore?val='.$rr)}}" style="font-size:10px;">Add Risk</a>
                                                    @if(Auth::user()->employeeid==$value->postedby && $value->risk_status=='Rejected')
                                                    <a class='dropdown-item' href="{{url('/user/ohsrisk/resubmit?val='.$rr)}}" style="font-size:10px;">Resubmit </a>
                                                    @endif
                                                    <!-- @foreach($mr as $m)
                                                        <a class='dropdown-item'  href="{{url('/user/ohsrisk/deleterisk?val='.$rr)}}" style="font-size:10px;">Delete Risk</a>
                                                        <a class='dropdown-item'  href="{{url('/user/ohsrisk/deleteactivity?val='.$rr)}}"  style="font-size:10px;" onclick="return confirm('Are you sure you want to delete entire activity');">Delete Activity</a>
                                                    @endforeach   -->
                                                    @foreach($dep as $d)
                                                    @if($value->department==$d->departmentname && $value->risk_status=='Reviewed')
                                                    <a class='dropdown-item' href="{{url('/user/ohsrisk/secondaryreview?val='.$rr)}}" style="font-size:10px;">Secondary Review</a>
                                                    @endif
                                                    {{-- <!-- <a class='dropdown-item'  href="{{url('/user/ohsrisk/deleterisk?val='.$rr)}}" style="font-size:10px;">Delete Risk</a>
                                                    <a class='dropdown-item' href="{{url('/user/ohsrisk/deleteactivity?val='.$rr)}}" style="font-size:10px;" onclick="return confirm('Are you sure you want to delete entire activity');">Delete Activity</a>
                                                    -->--}}
                                                    @endforeach
                                                    @if($value->risk_status=='Review Pending')
                                                    @foreach($hses as $h)
                                                    @if(Auth::user()->email==$h)
                                                    <a class='dropdown-item' href="{{url('/user/ohsrisk/review?val='.$rr)}}" style="font-size:10px;">Review</a>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </td>

                                        </div>

                                    </tr>
                                <tbody style="display:none;" id="{{$value->actid}}">

                                    @foreach($data3 as $dat)
                                    @if( $dat->id==$value->id)
                                    <?php
                                            if( $dat->RR > 0 && $dat->RR < 4 ){
                                                $category = 'Low';
                                            }
                                            elseif( $dat->RR > 3 && $dat->RR < 7 )	{
                                                $category = 'Medium';
                                            }
                                            elseif( $dat->RR > 7 ){
                                                $category = 'High';
                                            }

                                          ?>
                                    @if ($category == "High" || $category == "Medium")
                                    <?php  $flag="1"; ?>
                                    @else
                                    <?php  $flag="0"; ?>
                                    @endif
                                    <tr>
                                        <td class="text-center"><input class="checkbox" type="checkbox" id="{{ $dat->id}}" name="id[]"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted">{{ $dat->hazards }}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            @if( $dat->OrR > 0 && $dat->OrR < 4 ) <span style="margin-left:30px;" class="badge badge-success">R: {{ $dat->OrR }}</span>
                                                @elseif( $dat->OrR > 3 && $dat->OrR < 7 ) <span style="margin-left:30px;" class="badge badge-warning">R:{{ $dat->OrR }}</span>
                                                    @elseif( $dat->OrR > 7 )
                                                    <span style="margin-left:30px;" class="badge badge-danger">R: {{ $dat->OrR }}</span>
                                                    @endif
                                        </td>

                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            @if( $dat->RR > 0 && $dat->RR < 4 ) <span style="margin-left:30px;" class="badge badge-success">R: {{ $dat->RR }}</span>
                                                @elseif( $dat->RR > 3 && $dat->RR < 7 ) <span style="margin-left:30px;" class="badge badge-warning">R: {{ $dat->RR }}</span>
                                                    @elseif( $dat->RR > 7 )
                                                    <span style="margin-left:30px;" class="badge badge-danger">R: {{ $dat->RR }}</span>
                                                    @endif
                                        </td>

                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted">{{ $category  }}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted">{{ $dat->type}}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted">{{ $dat->R_NR_EM }}</em>
                                        </td>

                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted">{{ $dat->monitor }}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                            @if($dat->risk_status=="Rejected")
                                            <span class="badge badge-danger">{{ $dat->risk_status }}</span>
                                            @elseif($dat->risk_status=="Review Pending")
                                            <span class="badge badge-warning">{{ $dat->risk_status }}</span>
                                            @else
                                            <span class="badge badge-success">{{ $dat->risk_status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary" style="font-size:10px;" data-toggle="modal" data-id="{{ $dat->id }}" data-target="#viewModal2{{$dat->id}}"><strong>View</strong> </button>
                                            <div id="viewModal2{{$dat->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h2 class="modal-title">Detailed View</h2>
                                                        </div>
                                                        <table class="table table-striped">
                                                            <tr>
                                                                <th style="width: 300px;">Activity</th>
                                                                <td>{{ $dat->activity }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Added at</th>
                                                                <td>{{ $dat->created_at }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Last Reviewed</th>
                                                                <td>{{ $dat->reviewed_at }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Department</th>
                                                                <td>{{ $dat->department }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Monitoring</th>
                                                                <td>{{ $dat->monitor }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Type of Risk</th>
                                                                <td>{{ $dat->type }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Legal</th>
                                                                <td>{{ $dat->legal }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Location</th>
                                                                <td>{{ $dat->loc ? $dat->loc->name : '-'}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Risk Assessment Team</th>
                                                                <td>{{ $dat->team}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Posted By</th>
                                                                <td>{{$dat->postedby}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>R/NR/EM</th>
                                                                <td>{{ $dat->R_NR_EM }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Hazards and their<br> sources</th>
                                                                <td>{{ $dat->hazards }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>How Will It Affect<br> And Whom?</th>
                                                                <td>{{ $dat->affect }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Risk Control Measures<br> Already In Use</th>
                                                                <td>{{ $dat->control }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Original Risk With<br> Present Controls</th>
                                                                <td>
                                                                    <span style="margin-left:30px;">S: {{ $dat->OrS }}</span>
                                                                    <span style="margin-left:30px;">L: {{ $dat->OrL }}</span>
                                                                    @if( $dat->OrR > 0 && $dat->OrR < 4 ) <span style="margin-left:30px;" class="badge badge-success">R: {{ $dat->OrR }}</span>
                                                                        @elseif( $dat->OrR > 3 && $dat->OrR < 7 ) <span style="margin-left:30px;" class="badge badge-warning">R:{{ $dat->OrR }}</span>
                                                                            @elseif( $dat->OrR > 7 )
                                                                            <span style="margin-left:30px;" class="badge badge-danger">R: {{ $dat->OrR }}</span>
                                                                            @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Additional Control<br> Measures Needed</th>
                                                                <td>{{ $dat->additional }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Residual Risk With<br> Additional Controls</th>
                                                                <td><span style="margin-left:30px;">S: {{ $dat->RS }}</span>
                                                                    <span style="margin-left:30px;">L: {{ $dat->RL }}</span>

                                                                    @if( $dat->RR > 0 && $dat->RR < 4 ) <span style="margin-left:30px;" class="badge badge-success">R: {{ $dat->RR }}</span>
                                                                        @elseif( $dat->RR > 3 && $dat->RR < 7 ) <span style="margin-left:30px;" class="badge badge-warning">R: {{ $dat->RR }}</span>
                                                                            @elseif( $dat->RR > 7 )
                                                                            <span style="margin-left:30px;" class="badge badge-danger">R: {{ $dat->RR }}</span>
                                                                            @endif
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <th>Category</th>
                                                                <td>{{ $category }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Proposed Change</th>
                                                                <td>{{ $dat->proposechange }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Procedure Number</th>
                                                                <td>{{ $dat->proc_no}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Procedure</th>
                                                                <td>{{ $dat->procedur }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Process<br>Stage</th>
                                                                </th>
                                                                <td class="d-none d-sm-table-cell">
                                                                    @if($dat->risk_status=="Rejected")
                                                                    <span class="badge badge-danger">{{ $dat->risk_status }}</span>
                                                                    @elseif($dat->risk_status=="Review Pending")
                                                                    <span class="badge badge-warning">{{ $dat->risk_status }}</span>
                                                                    @else
                                                                    <span class="badge badge-success">{{ $dat->risk_status }}</span>
                                                                    @endif
                                                                </td>
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
                                    @endif
                                    @endforeach
                                </tbody>
                                </tbody>
                                @endforeach
                            </table>
                    </div>

                </div>
            </div>
            <!-- END Dynamic Table Full -->
        </div>
    </div>
    <!-- END Page Content -->
    <!--Delete Button -->

    <!--Delete Button -->

    <!--End of Delete Button -->
    {{----------delete request button ----------}}

    @section('sideitem')
    @foreach($mr as $mrs)
    @if(Auth::user()->email==$mrs)
    <div class="input-group input-group-sm ml-2">
        <button type="button" id="editohs" value=id[] class="btn btn-sm btn-dual">
            <i class="fa fa-1x fa-edit" style="color:grey;"></i>
        </button>
    </div>
    <div class="input-group input-group-sm ml-2">
        <button type="button" id="deleteohs" value=id[] name="delete" data-name="" class="btn btn-sm btn-dual">
            <i class="fas fa-1x fa-trash" style="color:red;"></i>
        </button>
    </div>

    @endif
    @endforeach
    @endsection

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $("#deleteohs").click(function() {
            var dataArr = new Array();
            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                    console.log(dataArr);
                    $.ajax({
                        type: 'get'
                        , url: "{{route('deleteohs')}}" + "?val=" + $(this).attr('id')
                        , success: function(response) {
                            // alert(response);
                            window.location.href = "{{url('/user/ohsrisk/folder')}}";
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
        $("#editohs").click(function() {
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
                    , url: "{{route('editohs')}}" + "?val=" + (all)
                    , success: function(response) {
                        // alert(response);

                        window.location.href = "{{route('editohs')}}" + "?val=" + (all);
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

    </script>
    <!-- End of Interested Party register-->
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

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/ohsrisk/folder">
                                    <div class="block-content text-center">
                                        <i class="fa fa-2x fa-clipboard-list text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Register
                                        </p>
                                    </div>
                                </a>

                            </div>
                            <!-- <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/ohsrisk/masterlist">
                                            <div class="block-content text-center">
                                                <i class="fa fa-2x fa-id-card-alt text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Master List
                                                </p>
                                            </div>
                                        </a>

                                    </div> -->
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/ohsrisk/analysis">
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
</div>
<!-- END Page Container -->

@include('exporting.forohsrisk')
<!-- END Apps Modal -->
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

<!--  till here for mouseover  -->
@endsection
