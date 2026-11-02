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
            <form method="post" action="{{url('/user/convert/file/'.'tvalue4')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue4" name="tvalue4" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/maintenance/addtotal')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>

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
    $data2=[];
    ?>
    <input type="hidden" value="a4" id="tabselected">
    <div class="content" style="width:100%">
        <div class="block block-rounded" style="margin-left:-1%;">
            <div class="block-content block-content-full">
                <div class="scrollable" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Maintenance Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="/user/maintenance/breakmech"> Breakdown Maintenance [{{$dept}}]</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                    </table>
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">

                        <thead>
                            <tr>
                                <th style="position:sticky;top:-1"></th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">S.No</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Equipment&nbspID</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Department</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 20%;position:sticky;top:-1">Equipment Type</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1">Location/area</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 20%;position:sticky;top:-1">Serial number</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Make/model</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Severity</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Priority</th>

                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">Actions</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=0;?>
                            @foreach($data3 as $value)
                            <?php $rr=$value->id;?>
                            <tr>
                                <td><input class="checkbox" type="checkbox" id="{{ $value->id}}" name="id[]"></td>
                                <td class="text-center " style="font-size:10px;">{{$no=$no+1}}</td>
                                <td class="text-center " style="font-size:10px;">{{$value->equid }}</td>
                                <td class="text-center " style="font-size:10px;">{{$value->department }}</td>
                                <td class="font-w600" style="font-size:10px;">
                                    <a href="#" style="font-size:10px;">{{$value->type}}</a>
                                </td>
                                <td class="d-none d-sm-table-cell" style="font-size:10px;">
                                    <em class="text-muted" style="font-size:10px;">{{$value->location}}</em>
                                </td>
                                <td>
                                    <em class="text-center " style="font-size:10px;">{{$value->serial}}</em>
                                </td>
                                <td class="font-w600" style="font-size:10px;">
                                    <a href="#" style="font-size:10px;">{{$value->make}}</a>
                                </td>





                                <td class="text-center " style="font-size:10px;width: 100px;">{{ $value->severity}}</td>
                                <td class="text-center " style="font-size:10px;width: 100px;">{{ $value->priority}}</td>

                                <div class="dropdown ">
                                    <td class="font-w600 " class="text-center " style="font-size:10px;">

                                        <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;width:70px;" data-toggle="dropdown">Actions</button>
                                        <div class="dropdown-menu " aria-labelledby="dropdown-dropleft-outline-dark">
                                            @foreach($hods as $hod)
                                            @if(Auth::user()->email==$hod->hod && $value->department==$hod->departmentname)
                                            <a href="{{ url('/user/maintenance/review?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">HoD Review</a>
                                            @endif
                                            @endforeach

                                    </td>

                                </div>
                                <td>
                                    <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal{{$value->id}}"><strong>View</strong> </button>
                                    <div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <th style="width:70px">Equipment id</th>
                                                        <td>{{ $value->equid }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Department</th>
                                                        <td>{{ $value->department }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Equipment description</th>
                                                        <td><?php echo nl2br( $value->desc)?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Equipment type</th>
                                                        <td>{{ $value->type }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Location</th>
                                                        <td>{{ $value->location }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Serial number</th>
                                                        <td>{{ $value->serial }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Make/model</th>
                                                        <td>{{ $value->make}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nature of fault</th>
                                                        <td><?php echo nl2br( $value->fault)?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Severity</th>
                                                        <td>{{ $value->severity}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Priority</th>
                                                        <td>{{ $value->priority}}</td>
                                                    </tr>

                                                    @if($value->readfreq!=NULL)
                                                    <tr>
                                                        <th>Reading frequency</th>
                                                        <td>{{$value->readfreq }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($value->testfreq!=NULL)
                                                    <tr>
                                                        <th>Testing frequency</th>
                                                        <td>{{$value->testfreq }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tested by</th>
                                                        <td>{{ $value->testby}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last test date</th>
                                                        <td>{{ $value->lasttestdate }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Next test schedule</th>
                                                        <td>{{ $value->testschedule }}</td>
                                                    </tr>


                                                    <tr>
                                                        <th>Equipment condition</th>
                                                        <td>{{$value->testcondition }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Test report number</th>
                                                        <td>{{ $value->testno}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Test status</th>
                                                        <td>
                                                            @if($value->teststatus=='Test Passed')
                                                            <span class="badge badge-success">{{$value->teststatus}}</span>
                                                            @elseif($value->teststatus=='Test Due')
                                                            <span class="badge badge-danger">{{$value->teststatus}}</span>
                                                            @elseif($value->teststatus=='Test Failed')
                                                            <span class="badge badge-info">{{$value->teststatus}}</span>
                                                            @elseif($value->teststatus=='Time for Test')
                                                            <span class="badge badge-warning">{{$value->teststatus}}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @if($value->freq!=NULL)
                                                    <tr>
                                                        <th>Preventive maintenance frequency</th>
                                                        <td>{{$value->freq }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>PM by</th>
                                                        <td>{{ $value->pmby}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last PM date</th>
                                                        <td>{{ $value->pmdate }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Next PM schedule</th>
                                                        <td>{{ $value->schedule }}</td>
                                                    </tr>


                                                    <tr>
                                                        <th>Equipment condition</th>
                                                        <td> {{$value->condition }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>PM status</th>
                                                        <td>
                                                            @if($value->pmstatus=='PM Done')
                                                            <span class="badge badge-success">{{$value->pmstatus}}</span>
                                                            @elseif($value->pmstatus=='PM Due')
                                                            <span class="badge badge-danger">{{$value->pmstatus}}</span>
                                                            @elseif($value->pmstatus=='Time for PM')
                                                            <span class="badge badge-warning">{{$value->pmstatus}}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif




                                                    <tr>
                                                        <th>Added by</th>
                                                        @if($value->addedby=='')
                                                        <td>admin</td>
                                                        @else
                                                        <td>{{$value->addedby}}</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <th>Added at</th>
                                                        <td>{{ $value->addedat}}</td>
                                                    </tr>
                                                </table>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark" style="font-size:10px;" data-dismiss="modal">Close</button>
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
   

    <script>
        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 200);

    </script>
    <!-- END Footer -->

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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/maintenancemgmt">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Total Equipment List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/pmfolder">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-address-card fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            PM Plan and Report
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/testfolder">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Test Plan
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/maintenance/breakfolder">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Breakdown Maintenance
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
    @include('exporting.formaintenance')

</body>
</html>
@endsection
