@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; "
        data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')"
                    style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')"
                style="width:50px;  font-size:10px;border:none;background-color:white;">Excel </button></li>
        <li><button title="Word" onclick="fnCovertTable('word')"
                style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/calibration/addinstruments')}}" class="btn btn-sm btn-dual"
        style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<style>
    .scrollable {
        height: 100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
</style>
<input type="hidden" value="a1" id="tabselected">
<!-- Page Content -->

<div class="content" style="width:100%;">
    <!-- Dynamic Table Full -->
    <div class="block block-rounded" style="height:600px;">
        <div class="block-content block-content-full">
            <div class="table-responsive scrollable" style="height:550px;">
                <table class="move" id="navtable"style="margin-left:670px;width:25%;">
                    <tr>
                        <th>
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                    <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-4px">Calibration Management</li>
                                    <li class="breadcrumb-item" aria-current="page" style="margin-top:-4px">
                                        <a class="link-fx" href="">Register</a>
                                    </li>
                                </ol>
                            </nav>
                        </th>
                    </tr>
                </table>
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead style="position: sticky; top: 0;z-index:5;background-color:#ffffff;">
                        <tr>
                            <th style="width: 5%;position:sticky;top:-1"></th>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                            <th class="text-center" style="width:5%;font-size:10px;text-transform:capitalize;position:sticky;top:-1">Instrument No</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Description</th>
                            <th class="text-center" style="width: 15%;font-size:10px;text-transform:capitalize;position:sticky;top:-1">Department</th>
                            <th style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Holder</th>
                            <th style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Capacity</th>
                            <th class="text-center" style="font-size:10px;width:100px;text-transform:capitalize;position:sticky;top:-1">Type</th>
                            <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1">Agency</th>
                            <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1">Frequency</th>
                            <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1">Due Date</th>
                            <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1">Status</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($data)>0)
                        <?php $no=0;?>

                        @foreach($data as $key => $value)
                        @php
                        $duedate= $value->Duedate;
                        $ddate = date('Y-m-d',strtotime($duedate));
                        $dudat = explode('-',$ddate);
                        $dudateval = $duedate == NULL ? NULL : $dudat[2]."-".$dudat[1]."-".$dudat[0];

                        $neartoexpiry=date('Y-m-d',strtotime($duedate. ' - 30 days'));
                        $today_dt=date("Y-m-d");
                        @endphp
                        <tr>
                            <td><input class="checkbox" type="checkbox" id="{{ $value->Instrument_no}}" name="id[]"></td>

                            <td class="text-center " style="font-size:10px;height:70px;"><span>{{ ++$no }}</td>


                            <td class="text-center " style="font-size:10px">
                                <em class="text-muted" style="font-size:10px">{{ $value->Instrument_no }}</em>
                            </td>
                            <td class="text-center " style="font-size:10px">
                                <em class="text-muted" style="font-size:10px">{{ $value->Instrument_desc }}</em>
                            </td>
                            <td class="text-center " style="font-size:10px">
                                <em class="text-muted" style="font-size:10px">
                                    @if(is_numeric($value->Department))
                                    @foreach($departments as $dep)
                                    @if($value->Department==$dep->id)
                                    {{$dep->departmentname}}
                                    @break
                                    @endif
                                    @endforeach
                                    @else
                                    {{$value->Department }}
                                    @endif
                                </em>
                            </td>
                            <td class="text-center" style="font-size:10px">
                                <em class="text-muted">{{$value->Holder}}</em>
                            </td>
                            <td class="text-center " style="font-size:10px">
                                <em class="text-muted" style="font-size:10px">{{ $value->Capacity }}</em>
                            </td>
                            <td class="text-center " style="font-size:10px">
                                <em class="text-muted" style="font-size:10px">{{ $value->Type }}</em>
                            </td>
                            @if(($value->Type=='Internal') || ($value->Type=='INTERNAL') || ($value->Type=='internal'))
                            <td class="text-center " style="font-size:10px">
                                <em class="text-muted" style="font-size:10px">--</em>
                            </td>
                            @elseif(($value->Type=='External') || ($value->Type=='EXTERNAL') || ($value->Type=='external'))
                            <td class="text-center " style="font-size:10px">
                                <em class="text-muted" style="font-size:10px">{{ $value->Agency }}</em>
                            </td>
                            @endif
                            <td class="text-center " style="font-size:10px">
                                <em class="text-muted" style="font-size:10px">{{ $value->Frequency }}</em>
                            </td>
                            <td class="text-center " style="font-size:10px">
                                <em class="text-muted" style="font-size:10px">
                                    @if($value->Duedate=="1970-01-01")
                                    &nbsp;&nbsp;
                                    @else
                                    {{ $dudateval }}
                                    @endif
                                </em>
                            </td>
                            <td class="text-center" style="font-size:10px">
                                @if($value->Status == \App\Calibration_instrument::UNDER_CALIBRATION_STATUS)
                                    <span class="badge badge-primary">Under Calibration</span>
                                @else
                                    @if($value->Duedate==NULL)
                                        <span class="badge badge-secondary" style="font-size:10px">Not Calibrated</span>
                                    @elseif($value->Duedate<$today_dt || $value->Status=="Expired")
                                        <?php // echo App\Http\Controllers\CalibrationController::statusExpired($value->Duedate);?>
                                        <span class="badge badge-danger" style="font-size:10px">Expired</span>
                                    @elseif($neartoexpiry<=$today_dt || $value->Status=="Near to expiry")
                                        <?php // echo App\Http\Controllers\CalibrationController::statusNeartoexpiry($value->Duedate);?>
                                        <span class="badge badge-warning" style="font-size:10px">Near to expiry</span>
                                    @elseif($value->Duedate>$today_dt || $value->Status=="Calibrated")
                                        <?php // echo App\Http\Controllers\CalibrationController::statusCalibrated($value->Duedate);?>
                                        <span class="badge badge-success" style="font-size:10px">Calibrated</span>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center" style="font-size:10px">
                                <?php $id=$value->id; $insno=$value->Instrument_no;?>
                                <div class="btn-group m-auto d-flex justify-content-center">
                                    <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$id}}">
                                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                    </a>
                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $id }}">
                                        @if(count($mr)>0)
                                            @if(Gate::allows('edit-calibration'))
                                            <a href="{{url('/user/calibration/editins?val='.$id)}}" class="dropdown-item" style="font-size:10px">Edit Instrument Details</a>
                                            @endif
                                            @if(($value->Type=='Internal') || ($value->Type=='INTERNAL') || ($value->Type=='internal'))
                                                @if(($value->Status=='Near to expiry') || ($value->Status==NULL) || ($value->Status=='Expired') || ($value->Duedate==$today_dt))
                                                <a href="{{url('/user/calibration/internal?val='.$id)}}" class="dropdown-item" style="font-size:10px"> Add Report details</a>
                                                @endif
                                                <a href="{{url('/user/calibration/cal_log?val='.$id)}}" class="dropdown-item" style="font-size:10px">Calibration log</a>
                                                <a href="{{url('/user/calibration/intrep?val='.$id)}}" class="dropdown-item" style="font-size:10px">Internal calibration </a>
                                            @elseif(($value->Type=='External') || ($value->Type=='EXTERNAL') || ($value->Type=='external'))
                                                <a href="{{url('/user/calibration/cal_logext?val='.$id)}}" class="dropdown-item" style="font-size:10px">Calibration log</a>
                                                @if(($value->Status=='Near to expiry') || ($value->Status==NULL) || ($value->Status=="Expired") || ($value->Duedate==$today_dt))
                                                <a href="{{url('/user/calibration/viewstatus?val='.$id)}}" class="dropdown-item" style="font-size:10px">Update calibration status</a>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                    <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View" onclick="ViewBtn({{$value->id}})">
                                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                    </a>

                                </div>
                                <div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg scrollable" tabindex="-1" role="dialog" aria-hidden="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="modal-title">Detailed View</h2>
                                            </div>
                                            <table class="table table-striped">
                                                <tr>
                                                    <th style="width:70px">Instrument No</th>
                                                    <td>{{ $value->Instrument_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width:70px">Description</th>
                                                    <td><?php echo nl2br($value->Instrument_desc )?></td>
                                                </tr>
                                                <tr>
                                                    <th style="width:70px">Department</th>
                                                    <td>
                                                        @if(is_numeric($value->Department))
                                                        @foreach($departments as $dep)
                                                        @if($value->Department==$dep->id)
                                                        {{$dep->departmentname}}
                                                        @break
                                                        @endif
                                                        @endforeach
                                                        @else
                                                        {{$value->Department }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width:70px">Holder/Location</th>
                                                    <td>{{ $value->Holder}}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width:70px">Type</th>
                                                    <td>{{ $value->Type}}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width:70px">Capacity</th>
                                                    <td>{{ $value->Capacity}}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width:70px">Procedure</th>
                                                    <td><?php echo nl2br($value->Calib_procedure)?></td>
                                                </tr>
                                                @if(($value->Type=='Internal') || ($value->Type=='INTERNAL') || ($value->Type=='internal'))
                                                <tr>
                                                    <th style="width:70px">Agency</th>
                                                    <td>--</td>
                                                </tr>
                                                @elseif(($value->Type=='External') || ($value->Type=='EXTERNAL') || ($value->Type=='external'))
                                                <tr>
                                                    <th style="width:70px">Agency</th>
                                                    <td>{{$value->Agency}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Attachments</th>
                                                    <td>
                                                        @if($value->extfile!=NULL)
                                                        <div class="col-sm-8">
                                                            <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal"  data-id="{{ $value->id }}" data-target="#viewModal4{{$value->id}}"><strong>View Attachments</strong></button>
                                                            <div id="viewModal4{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h3 class="modal-title">Attachments</h3>
                                                                        </div>
                                                                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                            <div class="row justify-content-center">
                                                                                <thead>
                                                                                    <tr class="table-secondary">
                                                                                        <th>Attachments</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>

                                                                                        <td
                                                                                            style="font-size:15px;width: 100%;">
                                                                                            {{$value->extfile}}</td>
                                                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;"
                                                                                                class="btn btn-success"
                                                                                                href="{{asset("storage/calibration/".$value->extfile)}}"
                                                                                                download="{{$value->extfile}}">Download</a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </div>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <span style="color:red;font-size:13px;">No Attachments</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <th style="width:70px">Frequency</th>
                                                    <td>{{$value->Frequency}}</td>
                                                </tr>

                                                <tr>
                                                    <th style="width:70px">Due Date</th>

                                                    <td>@if($value->Duedate=="1970-01-01")
                                                        &nbsp;&nbsp;
                                                        @else
                                                        {{ $dudateval }}
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th style="width:70px">Status</th>
                                                    @if($value->Duedate==NULL)
                                                        <td><span class="badge badge-secondary">Not Calibrated</span></td>
                                                    @elseif(($value->Duedate<$today_dt) || ($value->Status=="Expired"))
                                                        <td><span class="badge badge-danger">Expired</span></td>
                                                    @elseif(($neartoexpiry<=$today_dt) || ($value->Status=="Near to expiry"))
                                                        <td><span class="badge badge-warning">Near to expiry</span></td>
                                                    @elseif(($value->Duedate>$today_dt || $value->Status=="Calibrated"))
                                                        <td><span class="badge badge-success">Calibrated</span></td>
                                                    @endif
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
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Dynamic Table Full -->
</div>
<!-- END Page Content -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $("#del").click(function() {
        mess1 = "Are you sure?";
        x = confirm(mess1);
        if (x == true) {
            mess2 = 'Instrument Details will be deleted permanently,Press OK to continue.';
            y = confirm(mess2);
            if (y == true) {} else {
                return false;
            }
        } else {
            return false;
        }
        var dataArr = new Array();
        if ($('input:checkbox:checked').length > 0) {
            $('input:checkbox:checked').each(function() {
                dataArr.push($(this).attr('id'));
                // console.log(dataArr);
                $.ajax({
                    type: 'get',
                    url: "{{route('deletecal')}}" + "?val=" + $(this).attr('id'),
                    success: function(response) {
                        // alert(response);
                        window.location.href = "{{url('/user/calibrationmgmt')}}";
                        //  redirect('/user/doc/masterlist')->withData($data);
                    },
                    error: function(response) {
                        alert(errResponse);
                    }
                });
            });

        } else {
            alert('No Record Selected');
        }
    });
</script>

<!-- END Main Container -->
<!-- END Footer -->
<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">OPTIONS</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal"
                            aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row gutters-tiny">

                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body"
                                href="/user/calibrationmgmt">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br> Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- END Apps Modal -->
@endsection
<script>
    function ViewBtn(id) {
        $('#viewModal' + id).modal('show');
    }
</script>
@include('exporting.forcalibration')
@section('sideitem')
@if(Gate::allows('delete-calibration') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
@endsection