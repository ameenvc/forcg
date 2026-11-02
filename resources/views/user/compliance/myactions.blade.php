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
                <button onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue2" name="tvalue2" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/compliance/add')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
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
</head>
<body>
    <?php $data=[];?>
    <input type="hidden" value="a2" id="tabselected">
    <div class="content" style="width:100%">
        <div class="block block-rounded" style="margin-left:-1%;">
            <?php
             $no=0;
             ?>
            <div class="block-content block-content-full">
                <div class="scrollable" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Compliance Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">My Actions</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                    </table>
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">

                        <thead>
                            <tr>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Slno</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Compliance ID</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Regulatory<br> Authority</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Legislative<br> Issue</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Version/<br>Year/<br>Dated</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Act or<br> Regulation</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Requirement</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Target Date</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Related Aspect/<br>Risk Number</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Compliance<br>Status</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Process Owner</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Process Stage</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Action</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1"></th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">View</th>
                                {{-- <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1"></th> --}}

                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=0;
                                      $today = Date("Y-m-d");
                                ?>
                            @foreach($data1 as $value)
                            <?php
                                    $Cid = $value->Cid;
                                    //$owner = $value->owner;
                                // dd($Cid);
                                ?>
                            <tr>
                                <td class="text-center " style="font-size:10px;">{{$no=$no+1}}</td>
                                <td class="text-center" style="font-size:10px;">{{ $value->auth_id }}</td>
                                <td style="font-size:10px;">
                                    <em class="text-muted " style="font-size:10px;">{{ $value->authority }}</em>
                                </td>
                                <td style="font-size:10px;">
                                    <em class="text-muted " style="font-size:10px;">{{ $value->issue }}</em>
                                </td>
                                <td style="font-size:10px;">
                                    <em class="text-muted " style="font-size:10px;">{{ $value->version }}</em>
                                </td>
                                <td style="font-size:10px;">
                                    <em class="text-muted " style="font-size:10px;">{{ $value->regulation }}</em>
                                </td>
                                <td style="font-size:10px;">
                                    <em class="text-muted " style="font-size:10px;"><?php echo nl2br($value->requirement )?></em>
                                </td>
                                <td style="font-size:10px;">
                                    <em class="text-muted" style="font-size:10px;">{{date('M/d/Y',strtotime($value->duedate))}} </em>
                                </td>
                                <td style="font-size:10px;">
                                    <em class="text-muted " style="font-size:10px;"><?php echo nl2br($value->aspect )?> </em>
                                </td>
                                <td class="d-none d-sm-table-cell" style="font-size:10px;">
                                    @if ($value->comp_status == 'Under Review')
                                    <span class="badge badge-primary" style="font-size:10px;">{{ $value->comp_status }}</span>
                                    @elseif ($value->comp_status == "Complied")
                                    <span class="badge badge-success" style="font-size:10px;">{{ $value->comp_status }}</span>
                                    @elseif ($value->comp_status == "Not Complied")
                                    <span class="badge badge-danger" style="font-size:10px;">{{ $value->comp_status }}</span>
                                    @elseif ($value->comp_status == "Partially Complied")
                                    <span class="badge badge-info" style="font-size:10px;">{{ $value->comp_status }}</span>
                                    @endif
                                </td>
                                <td style="font-size:10px;">
                                    <em class="text-muted " style="font-size:10px;">{{ nl2br($value->dep ? $value->dep->departmentname : '-') }} </em>
                                </td>

                                <td class="font-w600 " style="font-size:10px;">
                                    <a href="#">{{ $value->pro_status }}</a>
                                </td>


                                <td><a href="{{url('user/compliance/approve?val='.$Cid)}}" class="btn btn-primary" style="font-size:11px;width: 60px;">Agree</a></td>
                                <td><a href="{{url('user/compliance/revert?val='.$Cid)}}" class="btn btn-primary" style="font-size:11px;width: 60px;">Revert</a></td>
                                <td>
                                    <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{ $value->Cid }}" data-target="#viewModal{{$value->Cid}}"><strong>View</strong> </button>
                                    <div id="viewModal{{$value->Cid}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <th style="width:70px">Compliance id</th>
                                                        <td>{{ $value->auth_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Regulatory authority</th>
                                                        <td>{{ $value->authority }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Legislative issue</th>
                                                        <td>{{ $value->issue}}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Version / year / dated</th>
                                                        <td>{{ $value->version }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Act or regulation</th>
                                                        <td>{{ $value->regulation }}</td>
                                                    </tr>


                                                    <tr>
                                                        <th>Requirement</th>
                                                        <td><?php echo nl2br($value->requirement )?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Due date</th>
                                                        <td>{{$value->duedate}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Related aspect / risk number</th>
                                                        <td><?php echo nl2br($value->aspect )?></td>
                                                    </tr>

                                                    <tr>
                                                        <th>Process owner</th>
                                                        <td>{{ $value->dep ? $value->dep->departmentname : '-'}}</td>
                                                        <td>{{ $value->pro_status}}</td>
                                                    </tr>
                                                    <th>Compliance status</th>
                                                    <td class="d-none d-sm-table-cell">
                                                        @if ($value->comp_status == 'Under Review')
                                                        <span class="badge badge-primary" style="font-size:10px;">{{ $value->comp_status }}</span>
                                                        @elseif ($value->comp_status == "Complied")
                                                        <span class="badge badge-success" style="font-size:10px;">{{ $value->comp_status }}</span>
                                                        @elseif ($value->comp_status == "Not Complied")
                                                        <span class="badge badge-danger" style="font-size:10px;">{{ $value->comp_status }}</span>
                                                        @elseif ($value->comp_status == "Partially Complied")
                                                        <span class="badge badge-info" style="font-size:10px;">{{ $value->comp_status }}</span>
                                                        @endif
                                                    </td>
                            </tr>
                            <tr>
                                <th>Person responsible for requirement/control</th>
                                <td>{{ $value->person_control }}</td>
                            </tr>
                            <tr>
                                <th>Person responsible for monitoring</th>
                                <td>{{ $value->person_monitor}}</td>
                            </tr>
                            <tr>
                                <th>Revert reason</th>
                                <td><?php echo nl2br($value->reason )?></td>
                            </tr>
                            <tr>
                                <th>Posted by</th>
                                <td>{{$value->postedby}}&nbsp; {{ $value->postedByCompliance ? $value->postedByCompliance->name : ''}}</td>
                            </tr>
                            <tr>
                                <th>Added date</th>
                                <td>{{$value->created_at}}</td>
                            </tr>
                            <tr>
                                <th>Last reviewed</th>
                                <td>{{$value->updatedat}}</td>
                            </tr>
                            <tr>
                                <th>Approved by</th>
                                <td>{{$value->approvedby}}&nbsp; {{ $value->approvedByCompliance ? $value->approvedByCompliance->name : ''}}</td>
                            </tr>
                            <tr>
                                <th>Issue closed by</th>
                                <td>{{$value->closedby}}&nbsp; {{ $value->closedByCompliance ? $value->closedByCompliance->name : ''}}</td>
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
    {{-- @endif --}}
    </div>
    <!-- END Dynamic Table Full -->
    </div>
    <!-- END Page Content -->

    <!-- END Main Container -->


    <!-- END Footer -->
    <script>
        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);

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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/compliance/add">
                                            <div class="block-content text-center">
                                                <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Add Compliance
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END CRM -->
                                    </div> --}}
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/compliancemgmt">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br />
                                            Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/compliance/myactions">
                                    <div class="block-content text-center">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br>My Actions
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            {{-- <div class="col-6">
                                        <!-- Payments -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="javascript:void(0)">
                                            <div class="block-content text-center">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Payments -->
                                    </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->

    @include('exporting.forcompliance')
</body>
</html>
@endsection
