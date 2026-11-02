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
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue3" name="tvalue3" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/risks/addrisk')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>
    {{-- <style>
        .move{
            margin-left:5%;
            position:absolute;
            top:17%
        }
        </style>--}}
    <style>
        body {
            overflow-y: hidden;
        }

    </style>
</head>
<body>
    <?php
    $data=[];
    $data1=[];
    ?>
    <input type="hidden" value="a3" id="tabselected">
    <div class="content" style="width:100%">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded" style="margin-left:-1%;">
            {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
            <div class="block-content block-content-full">
                <div class="scrollable" style="height:450px;" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Enterprise Risk</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="/user/risks/closedrisks">Closed Risks</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                    </table>
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">S.No</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1">Risk&nbspID</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">Risk Group</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 20%;position:sticky;top:-1">Risk Area</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 25%;position:sticky;top:-1">Risk&nbsp&nbspName</th>
                                {{-- <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Impact</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Likelihood</th> --}}
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Risk</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Risk Owner</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Risk Response</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Target</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Process Stage</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Action<br>Status</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Actions</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=0;?>
                            @foreach($data2 as $value)
                            <?php $rr=$value->Rid;?>
                            <tr>
                                <td class="text-center " style="font-size:10px;">{{$no=$no+1}}</td>
                                <td class="text-center " style="font-size:10px;">{{$value->grp_id}}</td>
                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted">{{$value->riskgroup}}</em>
                                </td>
                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted">{{$value->department ? $value->department->departmentname : ''}}</em>
                                </td>
                                <td class="font-w600 " style="font-size:10px;">
                                    <a href="#">{{$value->riskname}}</a>
                                </td>
                                <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                    @if($value->rank=="High")
                                    <span class="badge badge-danger">{{$value->rank }}</span>
                                    @elseif($value->rank=="Moderate")
                                    <span class="badge badge-warning">{{$value->rank }}</span>
                                    @else
                                    <span class="badge badge-success">{{$value->rank }}</span>
                                    @endif
                                </td>
                                <td class="text-center " style="font-size:10px;">@foreach($users as $user)
                                    @if( $value->risk_owner == $user->email )
                                    {{ $user->name }}-{{$user->employeeid}}
                                    @endif
                                    @endforeach</td>
                                <td class="text-center " style="font-size:10px;">{{ $value->risk_response }}</td>
                                <td class="text-center " style="font-size:10px;">{{ $value->action_target }}</td>
                                <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                    <span class="badge badge-primary">{{$value->action_status}}</span>

                                </td>

                                <td class="text-center " style="font-size:10px">{{ $value->actionst}}</td>
                                <td>
                                    <a href="{{ url('/user/risks/reopenrisk?val='.$rr)}}" class="btn btn-primary" style="font-size:10px;width: 70px;">Reopen</a>

                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;height:30px" data-toggle="modal" data-id="{{ $value->Rid }}" data-target="#viewModal{{$value->Rid}}"><strong>View</strong> </button>
                                    <div id="viewModal{{$value->Rid}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <th style="width:70px">Risk id</th>
                                                        <td>{{ $value->grp_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Risk group</th>
                                                        <td>{{ $value->riskgroup }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Risk area</th>
                                                        <td>{{ $value->department ? $value->department->departmentname : '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Risk name</th>
                                                        <td>{{ $value->riskname  }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Description</th>
                                                        <td><?php echo nl2br($value->riskdec)?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Risk drivers</th>
                                                        <td><?php echo nl2br($value->riskdri)?></td>
                                                    </tr>

                                                    <tr>
                                                        <th>Risk source</th>
                                                        <td>{{$value->riskscr }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Added date</th>
                                                        <td>{{$value->date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Reviewed date</th>
                                                        <td>{{$value->review_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Impact</th>
                                                        {{-- <td>{{$value->impa }}
                                </td> --}}
                                @if($value->impa =="High")
                                <td>{{$value->impa }}</td>
                                @elseif($value->impa =="Moderate")
                                <td>{{$value->impa }}</td>
                                @else
                                <td>{{$value->impa }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Likelihood</th>
                                {{-- <td>{{$value->like}}</td> --}}
                                @if($value->like=="High")
                                <td>{{$value->like }}</td>
                                @elseif($value->like=="Moderate")
                                <td>{{$value->like }}</td>
                                @else
                                <td>{{$value->like }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Risk</th>
                                {{-- <td>{{$value->rank }}</td> --}}
                                @if($value->rank=="High")
                                <td>{{$value->rank }}</td>
                                @elseif($value->rank=="Moderate")
                                <td>{{$value->rank }}</td>
                                @else
                                <td>{{$value->rank }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Risk owner</th>
                                <td>

                                    @foreach($users as $user)
                                    @if( $value->risk_owner == $user->email )
                                    {{ $user->name }}-{{$user->employeeid}}
                                    @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Risk response</th>
                                <td>{{$value->risk_response }}</td>
                            </tr>
                            <tr>
                                <th>Mitigating action</th>
                                <td><?php echo nl2br($value->mitigating_action )?></td>
                            </tr>
                            <tr>
                                <th>Target</th>
                                <td>{{$value->action_target }}</td>
                            </tr>

                            <tr>
                                <th>Process stage</th>
                                <td>{{$value->action_status }}</td>
                            </tr>

                            @if($value->action_status=='Active Risk')

                            <tr>
                                <th>Action status</th>
                                <td>{{$value->actionst}}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Added by</th>
                                @if($value->postedby=='')
                                <td>admin</td>
                                @else
                                <td>{{$value->postedby}}&nbsp; {{ $value->postedByRisk ? $value->postedByRisk->name : ''}}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Reviewed by</th>
                                <td>{{$value->reviewed_by}}&nbsp; {{ $value->reviewedByRisk ? $value->reviewedByRisk->name : ''}}</td>
                            </tr>
                            <tr>
                                <th>Approved by</th>
                                <td>{{$value->approved_by}}&nbsp; {{ $value->approvedByRisk ? $value->approvedByRisk->name : ''}}</td>
                            </tr>
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" style="font-size:11px;" data-dismiss="modal">Close</button>


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
    <!--</div>-->
    <!-- END Dynamic Table Full -->


    </div>
    <script>
        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 240);

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
                                            <div class="block-content text-center">
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

    @include('exporting.forriskmanagement')
</body>
</html>
@endsection
