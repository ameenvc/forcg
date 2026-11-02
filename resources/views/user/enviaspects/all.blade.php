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
    <a type="button" href="{{url('/user/enviaspects/add')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
<div class="input-group input-group-sm">
    <a href="{{ url('/user/enviaspects/folder') }}" class="btn btn-secondary" style="font-size:11px;width: 150px;margin-left:9px;">Main Page</a>
</div>
@endsection
@section('content')

<!doctype html>
<html lang="en">

<style>
    .header {
        margin-left: 30%;
        margin-bottom: -4%;
    }

    body {
        overflow-y: auto;
    }

    .status-warning {
        color: #af6f1e;
        background-color: #fbdece;
        border-color: #fbf4ea;
    }

    .status-red {
        color: #af1e1e;
        background-color: #f8d4d4;
        border-color: #fbeaea;
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

</style>


<body>
    <input type="hidden" value="a1" id="tabselected">

    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:100%">
        <!-- Dynamic Table Full -->

        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div id="content">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    @php
                    $no=0;
                    @endphp
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" style="position:relative;">
                        @if(count($data)>0)
                        @foreach($data as $value)
                        @php
                        $cats=$value->department;
                        $color=null;
                        $count=$value->count;
                        $rr=$value->Eid;
                        $btnid=$value->Eid."1";
                        $spnid=$value->Eid."2";
                        $dropid=$value->Eid."3";
                        @endphp
                        <thead>
                            @if(count($data2[$cats]))
                            <tr>
                                <th colspan="8" style="font-size:10px;width: 75px;position:sticky;top:-1"> <label style="font-size: 12px;font-weight:bold;text-transform:capitalize;">Activity:</label><label style="font-size:11px;text-transform:capitalize;color:gray;">{{ $value->activity }}</label></th>
                                @php
                                $count=0;
                                @endphp
                                <!-- condition to wether or not activity has aspects-->
                                @foreach($data2[$cats] as $dat)
                                @if($dat->Eid==$value->Eid)
                                <?php $count=$count+1;?>
                                @endif
                                @endforeach
                                <!--it will check whether an activity has aspects or not-->
                                @if($count>0)
                                <!--code to display activity on the first row-->
                                @foreach($data2[$cats] as $dat)
                                @php
                                $cats=$value->department;
                                $HS=$dat->HS;
                                $color=null;
                                $count=$value->count;
                                @endphp
                                @if ($HS == "HS")
                                <?php $color="red";?>
                                @elseif($HS == "MS")
                                <?php $color="orange";?>
                                @else
                                <?php $color="#40ff00";?>
                                @endif
                                @if($dat->Eid==$value->Eid)
                                @php
                                //below line is to prevent aspect from not to display in next row which is already been displayed in first row
                                $slno=$dat->slno;
                                @endphp
                                <div class="dropdown">
                                    <td></td>
                                    <td>
                                        <button type="button" class="btn btn-primary" style="font-size:10px;" data-toggle="modal" data-id="{{$value->Eid}}" data-target="#viewModal{{$value->Eid}}"> <strong>view</strong> </button>
                                        <div id="viewModal{{$value->Eid}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px">Environmental aspects ID</th>
                                                            <td>{{ $value->Eid }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Activity</th>
                                                            <td>{{ $value->activity }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Location</th>
                                                            <td>{{ $value->location }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Team</th>
                                                            <td>{{ $value->team }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Department</th>
                                                            <td class="text-primary">{{ $value->dept ? $value->dept->departmentname : '-'}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Division</th>
                                                            <td class="division-gray">{{ $value->division ? $value->division->name : '' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Subdivision</th>
                                                            <td class="subdivision-brown">{{ $value->area ? $value->area->name : '' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Created date</th>
                                                            <td>{{ $value->created_at }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Created by(Employee ID)</th>
                                                            <td>{{ $value->created_by }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reviewed date</th>
                                                            <td>{{ $value->reviewed_at != null ? date('d-m-Y h:i:s A', strtotime($value->reviewed_at)) : '' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reviewed by(Employee ID)</th>
                                                            <td>{{ $value->reviewed_by }}</td>
                                                        </tr>
                                                    </table>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @break
                                    @endif
                                    @endforeach
                                    @else
                                    <td>
                                        <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;" data-toggle="dropdown">Actions</button>
                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                            <a class='dropdown-item' style="font-size:10px" href="{{url('/user/enviaspects/newedit?val='.$rr)}}"><i class="fas fa-edit" style="font-size:10px;"></i> Edit</a>
                                            <a class='dropdown-item' style="font-size:10px;" href="{{url('/user/enviaspects/addaspect?val='.$rr)}}"><i class="si si-plus" style="font-size:10px;"></i> Add Aspects</a>
                                            @if(Auth::user()->email==$dat->useremail)
                                            @if($dat->status=='Rejected')
                                            <a class='dropdown-item' style="font-size:10px;" href="{{url('/user/enviaspects/resubmit?val='.$rr)}}"><i class="fas fa-paper-plane" style="font-size:10px;"></i> Resubmit Aspects</a>
                                            @endif
                                            @endif
                                            @foreach($delete as $del)
                                            @if(Auth::user()->email==$del)
                                            <a class='dropdown-item' style="font-size:10px;" href="{{url('/user/enviaspects/deleteaspect?val='.$rr)}}"><i class="fas fa-trash" style="font-size:10px;"></i> Delete Aspects</a>
                                            <a class='dropdown-item' style="font-size:10px;" href="{{url('/user/enviaspects/deleteactivity?val='.$rr)}}" onclick="return confirm('Are you sure you want to delete entire activity');"><i class="far fa-trash-alt" style="font-size:10px;"></i> Delete Activity</a>
                                            @endif
                                            @endforeach
                                            @foreach($dep as $d)
                                            @if($dat->status=='Reviewed' /*&& $value->department==$d */ && Gate::allows('enviaspect-secondary-review'))
                                            <a class='dropdown-item' style="font-size:10px;" href="{{url('/user/enviaspects/secondaryreview?val='.$rr)}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                                    <path d="M256 96c38.4 0 73.7 13.5 101.3 36.1l-32.6 32.6c-4.6 4.6-5.9 11.5-3.5 17.4s8.3 9.9 14.8 9.9H448c8.8 0 16-7.2 16-16V64c0-6.5-3.9-12.3-9.9-14.8s-12.9-1.1-17.4 3.5l-34 34C363.4 52.6 312.1 32 256 32c-10.9 0-21.5 .8-32 2.3V99.2c10.3-2.1 21-3.2 32-3.2zM132.1 154.7l32.6 32.6c4.6 4.6 11.5 5.9 17.4 3.5s9.9-8.3 9.9-14.8V64c0-8.8-7.2-16-16-16H64c-6.5 0-12.3 3.9-14.8 9.9s-1.1 12.9 3.5 17.4l34 34C52.6 148.6 32 199.9 32 256c0 10.9 .8 21.5 2.3 32H99.2c-2.1-10.3-3.2-21-3.2-32c0-38.4 13.5-73.7 36.1-101.3zM477.7 224H412.8c2.1 10.3 3.2 21 3.2 32c0 38.4-13.5 73.7-36.1 101.3l-32.6-32.6c-4.6-4.6-11.5-5.9-17.4-3.5s-9.9 8.3-9.9 14.8V448c0 8.8 7.2 16 16 16H448c6.5 0 12.3-3.9 14.8-9.9s1.1-12.9-3.5-17.4l-34-34C459.4 363.4 480 312.1 480 256c0-10.9-.8-21.5-2.3-32zM256 416c-38.4 0-73.7-13.5-101.3-36.1l32.6-32.6c4.6-4.6 5.9-11.5 3.5-17.4s-8.3-9.9-14.8-9.9H64c-8.8 0-16 7.2-16 16l0 112c0 6.5 3.9 12.3 9.9 14.8s12.9 1.1 17.4-3.5l34-34C148.6 459.4 199.9 480 256 480c10.9 0 21.5-.8 32-2.3V412.8c-10.3 2.1-21 3.2-32 3.2z" />
                                                </svg> Periodic Review
                                            </a>
                                            @endif
                                            @endforeach
                                            @if($dat->status=='Review Pending')
                                            @foreach($hses as $h)
                                            @if(Auth::user()->email==$h)
                                            <a class='dropdown-item' style="font-size:10px" href="{{url('/user/enviaspects/review?val='.$rr)}}"><i class="fas fa-redo-alt" style="font-size:10px;"></i> Review</a>
                                            @endif
                                            @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td></td>
                                    @endif
                                    <!--end of code for displaying aspects in first row-->
                            </tr>
                            <tr>
                                <th style="position:sticky;top:-1;width: 15px;"></th>
                                <th class="text-center" style="font-size:10px;width: 75px;text-transform:capitalize;position:sticky;top:-1">Slno</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1"> ID</th>
                                <th class="text-center" style="font-size:10px;width:  200px;text-transform:capitalize;position:sticky;top:-1">Environmental Aspects</th>
                                <th class="text-center" style="font-size:10px;width:  100px;text-transform:capitalize;position:sticky;top:-1">Potential<br> Impact</th>
                                <th class="text-center" style="font-size:10px;width: 100px;text-transform:capitalize;position:sticky;top:-1">Significance</th>
                                <th class="text-center" style="font-size:10px;width: 100px;text-transform:capitalize;position:sticky;top:-1">Process Stage</th>
                                <th class="text-center" style="font-size:10px;width: 100px;text-transform:capitalize;position:sticky;top:-1">Reviewed Date</th>
                                <th class="text-center" style="font-size:10px;width: 100px;text-transform:capitalize;position:sticky;top:-1">Actions</th>
                                <th class="text-center" style="font-size:10px;width: 75px;text-transform:capitalize;position:sticky;top:-1">View</th>
                            </tr>
                            @endif
                        </thead>
                        <tbody>
                            @foreach($data2[$cats] as $dat)
                            @php
                            $cats=$value->department;
                            $HS=$dat->HS;
                            $color=null;
                            $count=$value->count;
                            @endphp
                            @if ($HS == "HS")
                            <?php $color="red";?>
                            @elseif($HS == "MS")
                            <?php $color="orange";?>
                            @else
                            <?php $color="#40ff00";?>
                            @endif
                            @if( $dat->Eid==$value->Eid)
                            <tr style="background-color:white;">
                                <td><input type="checkbox" id="{{ $dat->slno}}" name="id[]"></td>
                                <td class="text-center " style="font-size:10px;width: 50px;">{{ $no=$no+1}}</td>
                                <td class="text-center " style="font-size:10px;width: 50px;color:black">{{ $value->count }}/{{ $dat->aspect_id }}</td>
                                <td class="text-center " style="font-size:10px;width: 50px;"><em class="text-muted" style="font-size:10px">{{ $dat->enviaspects }}</em></td>
                                <td class="text-center " style="font-size:10px;width: 50px;"><em class="text-muted" style="font-size:10px">{{ $dat->potential }}</em></td>
                                @if($dat->HS=="HS")
                                <td class="text-center" style="font-size:12px">

                                    <span class="status status-red">

                                        {{$dat->HS }}</span></td>
                                @elseif($dat->HS=="MS")
                                <td class="text-center " style="font-size:12px">

                                    <span class="status status-success">
                                        {{$dat->HS}}</span></td>
                                @else

                                <td class="text-center " style="font-size:12px;">
                                    <span class="status status-success">
                                        {{$dat->HS}}</span></td>
                                @endif
                                @if($dat->status=="Rejected")
                                <td class="text-center" style="font-size:12px">
                                    <span class="status status-red">{{ $dat->status }}</span>

                                </td>
                                @elseif($dat->status=="Review Pending")
                                <td class="text-center" style="font-size:12px;">
                                    <span class="status status-warning">{{ $dat->status }}</span>

                                </td>
                                @elseif($dat->status == 'Reviewed')
                                <td class="text-center" style="font-size:12px;">
                                    <span class="status status-blue">Aspect reviewed</span>
                                </td>
                                @elseif($dat->status == 'Approved Aspect')
                                <td class="text-center " style="font-size:12px;">
                                    <span class="status status-success">Aspect approved</span>
                                </td>
                                @endif
                                @if($dat->reviewed_at!=null)
                                <td class="text-center " style="font-size:10px;width: 50px;">{{ date('d-m-Y h:i:s A', strtotime($dat->reviewed_at)) }}</td>
                                @else
                                <td></td>
                                @endif
                                <td></td>

                                <div class="dropdown">
                                    <td>

                                        <button type="button" class="btn btn-primary" style="font-size:10px;width: 50px;" data-toggle="modal" data-id="{{$dat->slno }}" data-target="#viewModal{{$dat->slno}}"> <strong>view</strong> </button>
                                        <div id="viewModal{{$dat->slno}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px">Environmental aspects ID</th>
                                                            <td>{{ $value->count }}/{{ $dat->aspect_id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Environmental aspects</th>
                                                            <td>{{ $dat->enviaspects }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Location</th>
                                                            <td>{{ $dat->location }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Team</th>
                                                            <td>{{ $dat->team }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Emergency</th>
                                                            <td>{{ $dat->ER }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Potential environmental</th>
                                                            <td>{{ $dat->potential }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Occurrence probability</th>
                                                            <td>{{ $dat->A }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Nature of consequence</th>
                                                            <td>{{ $dat->B }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Legal/Regulatory requirement</th>
                                                            <td>{{ $dat->C }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Quantity</th>
                                                            <td>{{ $dat->D }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Control measure</th>
                                                            <td>{{ $dat->E }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total</th>
                                                            <td>{{ $dat->total }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Significance</th>
                                                            @if($dat->HS=="HS")
                                                            <td>

                                                                <span class="badge badge-danger">

                                                                    {{$dat->HS }}</span></td>
                                                            @elseif($dat->HS=="MS")
                                                            <td>

                                                                <span class="badge badge-success">
                                                                    {{$dat->HS}}</span></td>
                                                            @else

                                                            <td>
                                                                <span class="badge badge-success">
                                                                    {{$dat->HS}}</span></td>
                                                            @endif
                                                        </tr>

                                                        <tr>
                                                            <th>Process stage</th>
                                                            @if($dat->status=="Rejected")
                                                            <td>
                                                                <span class="badge badge-danger">{{ $dat->status }}</span>

                                                            </td>
                                                            @elseif($dat->status=="Review Pending")

                                                            <td>

                                                                <span class="badge badge-warning">{{ $dat->status }}</span>
                                                            </td>
                                                            @elseif($dat->status == 'Reviewed')
                                                            <td class="text-center " style="font-size:10px;">
                                                                <span class="badge badge-primary">Aspect reviewed</span>
                                                            </td>
                                                            @elseif($dat->status == 'Approved Aspect')
                                                            <td class="text-center " style="font-size:10px;">
                                                                <span class="badge badge-success">Aspect approved</span>
                                                            </td>
                                                            @endif

                                                        </tr>
                                                        <tr>
                                                            <th>Review control</th>
                                                            <td>{{ $dat->control }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reviewer proposal</th>
                                                            <td>{{ $dat->proposechanges}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Added date</th>
                                                            <td>{{ $dat->created_at }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reviewed date</th>
                                                            <td>{{ $dat->reviewed_at != null ? date('d-m-Y h:i:s A', strtotime($dat->reviewed_at)) : '' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Posted by(Employee ID)</th>
                                                            <td>{{ $dat->posted_by }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reviewed by(Employee ID)</th>
                                                            <td>{{ $dat->reviewed_by }}</td>
                                                        </tr>
                                                    </table>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </div>
                            </tr>
                            <tr>
                            </tr>
                            @endif

                            @endforeach
                            {{-- <tr>
                                <td colspan="10"> </td>
                            </tr> --}}

                        </tbody>
                        @endforeach
                        @else
                        <br><br><br>
                        <tr>
                            <center>
                                <h1>No Data</h1>
                            </center>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            <!-- END Dynamic Table Full -->
            <!-- END Page Content -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

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


            <script>
                $("#del").click(function() {
                    var dataArr = new Array();
                    if ($('input:checkbox:checked').length > 0) {
                        $('input:checkbox:checked').each(function() {
                            dataArr.push($(this).attr('id'));
                            // console.log(dataArr);
                            $.ajax({
                                type: 'get'
                                , url: "{{route('deleteenviaspects')}}" + "?val=" + $(this).attr('id')
                                , success: function(response) {
                                    // alert(response);
                                    window.location.href = "{{url('/user/enviaspects/folder')}}";
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
                $("#editenvi").click(function() {
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
                            , url: "{{route('editenvi')}}" + "?val=" + (all)
                            , success: function(response) {
                                // alert(response);

                                window.location.href = "{{route('editenvi')}}" + "?val=" + (all);
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

            <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
            <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">OPTIONS</h3>
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/enviaspects/folder">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    <br> Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>




                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/enviaspects/analysis">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    <br> Analysis
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

            @section('exportadd')
            <div class="input-group input-group-sm">
                <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
                    <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
                </button>
                <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
                    <li>
                        <!--change route as below-->
                        <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
                            @csrf
                            <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px"><i class="far fa-2x fa-file-pdf"></i></button>
                            <!--change hidden input type id as below-->
                            <input type="hidden" id="tvalue1" name="tvalue1" />
                        </form>
                    </li>
                    <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px"></i><i class="far fa-2x fa-file-excel"></i></button></li>
                    <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px"><i class="far fa-2x fa-file-word"></i></button></li>
                </ul>
            </div>

            <div class="input-group input-group-sm">
                <a type="button" href="{{url('/user/enviaspects/add')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
                    <i class="fa fa-1x fa-plus" style="color:grey;"></i>
                </a>
            </div>
            @endsection
            @section('sideitem')
            @foreach($mr as $mrs)
            @if(Auth::user()->email==$mrs)
            <div class="input-group input-group-sm ml-2">
                <button type="button" class="btn btn-sm btn-dual" id="editenvi" style="margin-left:490px;" value=id[]>
                    <i class="fa fa-1x fa-edit" style="color:grey;"></i>
                </button>
            </div>

            <div class="input-group input-group-sm ml-2">
                <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
                    <i class="fas fa-1x fa-trash" style="color:red;"></i>
                </button>
            </div>
            @endif
            @endforeach
            @endsection
            <!--  -->
            @include('exporting.forallenviaspects')
</body>
<script>
    function click1(x, l1, a2, l2) {
        document.getElementById('tabselected').value = x.id;
        x.style.backgroundColor = "#2F5597";
        x.style.color = "white";
        document.getElementById(a2).style.backgroundColor = "white";
        document.getElementById(a2).style.color = "black";
        document.getElementById(l1).className = "active";
        document.getElementById(l2).className = "";
    }

</script>

</html>
@endsection
