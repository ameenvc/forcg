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

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <input type="hidden" name="paper" value="a3">
                <input type="hidden" name="orientation" id="orientation" value="landscape">
                <input type="hidden" name="" id="current_user_name" value="{{ Auth::user()->name }}">
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel </button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>
<div class="input-group input-group-sm">
    <a type="button" id="addCalibration"  class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
<style>
    .scrollable {
        height: 100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
    .breadcrumb > li + li:before {
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
    .under-calibration-wrap ul {
        border: 1px solid #e0e5e8;
        border-radius: 50%;
    }
    .under-calibration-wrap img {
        width: 25px;
        margin: 5px;
    }
    .under-calibration-wrap ul:hover {
        background: #e0e5e8;
        border: 1px solid grey;
    }
    .tag-style {
        display:block;
        font-size:9px;
        color:#555;
        background:#f2f2f2;
        padding:2px 6px;
        border-radius:4px;
        margin-top:2px;"
    }
</style>
    <input type="hidden" value="a1" id="tabselected">

    <div class="content" style="width:100%">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">Register</li>
                <li class="breadcrumb-item">
                    Calibration Management
                </li>
            </ol>
        </nav>
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="container-fluid d-flex justify-content-between w-100" style="border-bottom: 1px solid #e1e6e9;">
                    <div>
                        <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link js-internal {{ !app('request')->get('tab') || app('request')->get('tab') == 'int' ? 'active' : '' }}"
                                   id="internal-tab"
                                   href="#btabs-alt-static-home">Internal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link js-external {{ app('request')->get('tab') && app('request')->get('tab') == 'ext' ? 'active' : '' }}"
                                   id="external-tab"
                                   href="#btabs-alt-static-profile">External</a>
                            </li>
                        </ul>
                    </div>
                    <div class="under-calibration-wrap {{ app('request')->get('tab') && app('request')->get('tab') == 'ext' ? '' : 'd-none' }}">
                         <li class="breadcrumb-item">
                            <a href="#" class="btn btn-sm btn-alt-primary" style="font-size:12px;" data-toggle="dropdown" data-placement="top">
                                <span data-toggle="tooltip" title="Click here to change the status">Change Status &nbsp;<i class="fas fa-chevron-down"></i></span>
                            </a>
                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                <a href="#" class="dropdown-item text-dark js-change-to-under" style="font-size:12px;" data-status="{{ \App\Calibration_instrument::UNDER_CALIBRATION_STATUS }}" data-route="{{ route('calibration.change.status') }}">
                                    Under Calibration
                                </a>
                                <a href="#" data-route="{{ route('calibration.change.status.obsolete') }}" class="dropdown-item text-dark js-change-to-obsolete" style="font-size:12px;">
                                    Obsolete
                                </a>
                            </div>
                        </li>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="flash-msg">
                        @if(Session::has('errmsg'))
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('errmsg')!!}
                            </div>
                        @endif
                        @if(Session::has('msg'))
                            <div class="alert alert-success">
                                <a class="close close-org" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                        @endif
                        <!-- <div class="alert alert-success" id="delmessage" style="display:none;">
                            <a class="close close-org" data-dismiss="alert">×</a>
                                Instrument details deleted successfully
                        </div> -->

                        @if (session('delete_success'))
                            <div id="delmessage" class="alert alert-success">
                                {{ session('delete_success') }}
                            </div>
                        @endif
                    </div>
                </div>
                @php
                    $isEditAble = Gate::allows('edit-calibration');
                @endphp
                <div class="block-content tab-content">
                    <!-- Internal tab starts -->
                    <div class="tab-pane {{ !app('request')->get('tab') || app('request')->get('tab') == 'int' ? 'active' : '' }}"
                         id="btabs-alt-static-home" role="tabpanel">
                       <div class="table-responsive scrollable" style="height:550px;" id="content">
                            <table class="table table-bordered table-striped table-vcenter" id="tabledata_int">
                                <thead style="position: sticky; top: 0;z-index:5;background-color:#ffffff;">
                                    <tr>
                                        <th style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1px;vertical-align: middle;"></th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1px;vertical-align: middle;">S.No</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Instrument_no']['field_display_label']}}</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 25%;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Instrument_desc']['field_display_label']}}</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 25%;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Instru_Manufacturer']['field_display_label']}}</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 15%;position:sticky;top:-1px;vertical-align: middle;">Department</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Holder']['field_display_label']}}</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Capacity']['field_display_label']}}</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;vertical-align: middle;">Least count</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;vertical-align: middle;">Standard Edition</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;vertical-align: middle;">Acceptance Criteria</th>
                                        <th class="text-center"
                                            id="frequency_int_selectData"
                                            data-orderable="false"
                                            style="font-size:10px;width: 5%;text-transform:capitalize;position:sticky;top:-1px">Frequency</th>
                                        <th class="text-center"
                                            id="dateofcal_int_selectData"
                                            data-orderable="false"
                                            style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">Date of Calibration</th>
                                        <th class="text-center"
                                            id="duedate_int_selectData"
                                            data-orderable="false"
                                            style="font-size:10px;width: 20%;text-transform:capitalize;position:sticky;top:-1px">&nbsp;&nbsp;&nbsp;DueDate</th>
                                        <th class="text-center"
                                            id="status_int_selectData"
                                            data-orderable="false"
                                            style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">Status</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;width:10%;vertical-align: middle;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $srno = 0; ?>
                                    @if(count($internal_datas)>0)
                                    @foreach($internal_datas as $key => $value)
                                    @php
                                        $duedate= $value->Duedate;
                                        $ddate = date('Y-m-d',strtotime($duedate));
                                        $dudat = explode('-',$ddate);
                                        $dudateval = $duedate == NULL ? NULL : $dudat[2]."-".$dudat[1]."-".$dudat[0];
                                        $neartoexpiry=date('Y-m-d',strtotime($duedate. ' - 30 days'));
                                        $today_dt=date("Y-m-d");
                                        $rowCalibrationFieldRules = json_decode($value->calibration_field_rules, true);
                                    @endphp
                                    <tr id={{"row_int_".$value->id}}>
                                        <td><input class="checkbox" type="checkbox" id="{{ $value->id}}" name="id[]"></td>
                                        <td class="text-center " style="font-size:10px;height:70px;"><span>{{++$srno}}</td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->Instrument_no }}</span>
                                            @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instrument_no', 'Instrument number');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->Instrument_desc }}</span>
                                            @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instrument_desc', 'Instrument description');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->Instru_Manufacturer=='no value'?'--': $value->Instru_Manufacturer}}</span>
                                            @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instru_Manufacturer', 'Instrument manufacturer');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">
                                                @if(is_numeric($value->Department))
                                                    {{$value->departmentdetails? $value->departmentdetails->departmentname : ''}}
                                                @else
                                                    {{$value->Department}}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-center" style="font-size:10px">
                                            <span>{{$value->Holder}}</span>
                                            @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Holder', 'Instrument holder/location');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->Capacity }}</span>
                                            @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Capacity', 'Instrument capacity/range');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->least_count?$value->least_count:'--' }}</span>
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->standard_edition?$value->standard_edition:'--' }}</span>
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->acceptance_criteria?$value->acceptance_criteria:'--' }}</span>
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">
                                                {{ $value->Frequency }}
                                            </span>
                                            <div class="d-none">{{ "fre_int" . str_replace(' ', '', $value->Frequency) }}</div>
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">
                                                {{$value->dateofcal==""?'--':date('d-m-Y',strtotime($value->dateofcal))}}
                                            </span>
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">
                                            {{$value->Duedate==""?'--':date('d-m-Y',strtotime($value->Duedate))}}
                                            </span>
                                        </td>
                                        <td class="text-center" style="font-size:10px">
                                            @if($value->Status == \App\Calibration_instrument::UNDER_CALIBRATION_STATUS)
                                                <span class="badge badge-primary" style="font-size:10px">Under Calibration</span>
                                            @else
                                                @if($value->Duedate==NULL)
                                                    <span class="badge badge-secondary" style="font-size:10px">Not Calibrated</span>
                                                @elseif($value->Status=="Expired")
                                                    <span class="badge badge-danger" style="font-size:10px">Expired</span>
                                                    <?php
                                    //                                                 echo App\Http\Controllers\CalibrationController::statusExpired($value->Duedate);?>
                                                @elseif($value->Status=="Near to expiry")
                                                    <?php
                                        //                                                 echo App\Http\Controllers\CalibrationController::statusNeartoexpiry($value->Duedate);?>
                                                    <span class="badge badge-warning" style="font-size:10px">Near to expiry</span>
                                                @elseif($value->Status=="Calibrated")
                                                    <?php
                                                        //                                                 echo App\Http\Controllers\CalibrationController::statusCalibrated($value->Duedate);?>
                                                    <span class="badge badge-success" style="font-size:10px">Calibrated</span>
                                                @endif
                                            @endif
                                        </td>
                                        <div class="dropdown font-size-sm">
                                            <?php $id=$value->id;
                                            $insno=$value->Instrument_no?>

                                            <td class="text-center" style="font-size:10px">
                                                <div class="btn-group m-auto d-flex justify-content-center">
                                                    <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->id}}">
                                                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                                    </a>
                                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->id }}">
                                                        @if(count($mr)>0)
                                                            @if($isEditAble)
                                                            <a href="{{url('/user/calibration/editins?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                              <i class="fa fa-1x fa-edit"></i>&nbsp;&nbsp; Edit Instrument details
                                                            </a>
                                                            @endif
                                                            @if(($value->Type=='Internal') || ($value->Type=='INTERNAL') || ($value->Type=='internal'))
                                                                @if(($value->Status=='Near to expiry') || ($value->Status==NULL) || ($value->Status=='Expired') || ($value->Duedate==$today_dt))
                                                                <a href="{{url('/user/calibration/internal?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                                  <i class="fa fa-1x fa-plus"></i>&nbsp;&nbsp;Add Report details
                                                                </a>
                                                                @endif
                                                                <a href="{{url('/user/calibration/cal_log?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                                  <i class="fa fa-record-vinyl" style="font-size:10px;"></i>&nbsp;&nbsp; Calibration log
                                                                </a>
                                                                <a href="{{url('/user/calibration/intrep?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                                  <i class="fa fa-tasks" style="font-size:10px;"></i>&nbsp;&nbsp; Internal calibration
                                                                </a>
                                                            @elseif(($value->Type=='External') || ($value->Type=='EXTERNAL') || ($value->Type=='external'))
                                                                <a href="{{url('/user/calibration/cal_logext?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                                  <i class="fa fa-record-vinyl" style="font-size:10px;"></i>&nbsp;&nbsp; Calibration log
                                                                </a>
                                                                @if(($value->Status=='Near to expiry') || ($value->Status==NULL) || ($value->Status=="Expired") || ($value->Duedate==$today_dt))
                                                                <a href="{{url('/user/calibration/viewstatus?val='.$id . '&tab=int')}}" class="dropdown-item" style="font-size:10px">
                                                                   <i class="far fa-2x fa-check-square" style="font-size:12px;"></i>&nbsp;&nbsp;Update calibration status
                                                                </a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <a href="#" data-route="{{$value->id}}" class="btn btn-sm btn-alt-primary btn-view-detail" data-type='internal' data-id="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View">
                                                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                                    </a>

                                                </div>
                                            </td>
                                        </div>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>
                    </div>
                    <!-- Ends Internal tab  -->
                    <!-- External tab starts -->
                    <div class="tab-pane {{ app('request')->get('tab') && app('request')->get('tab') == 'ext' ? 'active' : '' }}"
                         id="btabs-alt-static-profile" role="tabpanel">
                        <div class="table-responsive scrollable" style="height:550px;" id="content">
                            <table class="table table-bordered table-striped table-vcenter" id="tabledata_ext">
                                <thead style="position: sticky; top: 0;z-index:5;background-color:#ffffff;">
                                    <tr>
                                        <th style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1px;vertical-align: middle;"></th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1px;vertical-align: middle;">S.No</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Instrument_no']['field_display_label']}}</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 25%;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Instrument_desc']['field_display_label']}}</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 100px;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Instru_Manufacturer']['field_display_label']}}</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 15%;position:sticky;top:-1px;vertical-align: middle;">Department</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Holder']['field_display_label']}}</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Capacity']['field_display_label']}}</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;vertical-align: middle;">Least count</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 10%;position:sticky;top:-1px;vertical-align: middle;">{{$calibrationFieldRules['Agency']['field_display_label']}}</th>
                                        <th class="text-center"
                                            id="frequency_ext_selectData"
                                            data-orderable="false"
                                            style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">Frequency</th>
                                        <th class="text-center"
                                            id="dateofcal_ext_selectData"
                                            data-orderable="false"
                                            style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">Date of Calibration</th>
                                        <th class="text-center"
                                            id="duedate_ext_selectData"
                                            data-orderable="false"
                                            style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">&nbsp;&nbsp;&nbsp;DueDate</th>
                                        <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">Certificate Number</th>
                                        <th class="text-center"
                                            id="status_ext_selectData"
                                            data-orderable="false"
                                            style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">Status</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;width:10%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $srno = 0; ?>
                                    @if(count($external_datas)>0)
                                    @foreach($external_datas as $key => $value)
                                    @php
                                        $duedate= $value->Duedate;
                                        $ddate = date('Y-m-d',strtotime($duedate));
                                        $dudat = explode('-',$ddate);
                                        $dudateval = $duedate == NULL ? NULL : $dudat[2]."-".$dudat[1]."-".$dudat[0];
                                        $neartoexpiry=date('Y-m-d',strtotime($duedate. ' - 30 days'));
                                        $today_dt=date("Y-m-d");
                                        $rowCalibrationFieldRules = json_decode($value->calibration_field_rules, true);
                                    @endphp
                                    <tr id={{"row_ext_".$value->id}}>
                                        <td><input class="checkbox external-item" type="checkbox"
                                                   data-calibration-id="{{ $value->id }}"
                                                   id="{{ $value->id}}" name="id[]" {{$value->Status=='Obsolete' ? 'disabled' : ''}}></td>
                                        <td class="text-center " style="font-size:10px;height:70px;"><span>{{++$srno}}</td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->Instrument_no }}</span>
                                            @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instrument_no', 'Instrument number');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->Instrument_desc }}</span>
                                             @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instrument_desc', 'Instrument description');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->Instru_Manufacturer=='no value'?'--': $value->Instru_Manufacturer}}</span>
                                            @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Instru_Manufacturer', 'Instrument manufacturer');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">
                                                @if(is_numeric($value->Department))
                                                    {{$value->departmentdetails? $value->departmentdetails->departmentname : ''}}
                                                @else
                                                    {{$value->Department}}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-center" style="font-size:10px">
                                            <span>{{$value->Holder}}</span>
                                            @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Holder', 'Instrument holder/location');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->Capacity }}</span>
                                            @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Capacity', 'Instrument capacity/range');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->least_count?$value->least_count:'--' }}</span>
                                        </td>
                                        @if(($value->Type=='Internal') || ($value->Type=='INTERNAL') || ($value->Type=='internal'))
                                        <td class="text-center " style="font-size:10px">
                                            <span class="text-muted" style="font-size:10px">--</span>
                                        </td>
                                        @elseif(($value->Type=='External') || ($value->Type=='EXTERNAL') || ($value->Type=='external'))
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->Agency }}</span>
                                            @php
                                                $tag = App\Helpers\Calibration\CalibrationFormConfigurationHelper::getTag($rowCalibrationFieldRules, $calibrationFieldRules,  'Agency', 'Calibration agency');
                                            @endphp
                                            @if ($tag)
                                                <span class="tag-style">{{$tag}}</span>
                                            @endif
                                        </td>
                                        @endif
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">{{ $value->Frequency }}</span>
                                            <div class="d-none">{{ "fre_ext" . str_replace(' ', '', $value->Frequency) }}</div>
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">
                                             {{$value->dateofcal==""?'--':date('d-m-Y',strtotime($value->dateofcal))}}
                                            </span>
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">
                                             {{$value->Duedate==""?'--':date('d-m-Y',strtotime($value->Duedate))}}
                                            </span>
                                        </td>
                                        <td class="text-center " style="font-size:10px">
                                            <span style="font-size:10px">
                                                @php
                                                  $reportno = $value->calibrationExternalReportNo ? $value->calibrationExternalReportNo->Report_no : '';
                                                @endphp
                                                {{$reportno?$reportno:'-'}}
                                            </span>
                                        </td>
                                        <td class="text-center" style="font-size:10px">
                                            @if($value->Status == \App\Calibration_instrument::UNDER_CALIBRATION_STATUS)
                                            <span class="badge badge-primary" style="font-size:10px">Under Calibration</span>
                                            @elseif($value->Status == 'Obsolete')
                                                <span class="badge" style="font-size:10px;background-color:darkgray;color:#fff">Obsolete</span>
                                            @else
                                                @if($value->Duedate==NULL)
                                                <span class="badge badge-secondary" style="font-size:10px">Not Calibrated</span>
                                                @elseif($value->Duedate<$today_dt || $value->Status=="Expired")
                                                <?php
                                                // echo App\Http\Controllers\CalibrationController::statusExpired($value->Duedate);?>
                                                <span class="badge badge-danger" style="font-size:10px">Expired</span>
                                                @elseif($neartoexpiry<=$today_dt || $value->Status=="Near to expiry")
                                                <?php
                                                // echo App\Http\Controllers\CalibrationController::statusNeartoexpiry($value->Duedate);?>
                                                <span class="badge badge-warning" style="font-size:10px">Near to expiry</span>
                                                @elseif($value->Duedate>$today_dt || $value->Status=="Calibrated")
                                                <?php
                                                // echo App\Http\Controllers\CalibrationController::statusCalibrated($value->Duedate);?>
                                                <span class="badge badge-success" style="font-size:10px">Calibrated</span>
                                                @endif
                                            @endif
                                        </td>
                                        <div class="dropdown font-size-sm">
                                            <td>
                                            <?php $id=$value->id;
                                            $insno=$value->Instrument_no?>

                                            <div class="btn-group m-auto d-flex justify-content-center">
                                                <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->id}}">
                                                    <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer;"></i>
                                                </a>
                                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->id }}">
                                                    @if(count($mr)>0)
                                                        @if($isEditAble && $value->Status != 'Obsolete')
                                                        <a href="{{url('/user/calibration/editins?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                            <i class="fa fa-1x fa-edit"></i>&nbsp;&nbsp;Edit Instrument Details
                                                        </a>
                                                        @endif
                                                        @if(($value->Type=='Internal') || ($value->Type=='INTERNAL') || ($value->Type=='internal'))
                                                            @if(($value->Status=='Near to expiry') || ($value->Status==NULL) || ($value->Status=='Expired') || ($value->Duedate==$today_dt))
                                                            <a href="{{url('/user/calibration/internal?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                                <i class="fa fa-1x fa-plus"></i>&nbsp;&nbsp;Add Report details
                                                            </a>
                                                            @endif
                                                            <a href="{{url('/user/calibration/cal_log?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                                <i class="fa fa-record-vinyl" style="font-size:10px;"></i>&nbsp;&nbsp; Calibration log
                                                            </a>
                                                            <a href="{{url('/user/calibration/intrep?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                                <i class="fa fa-tasks" style="font-size:10px;"></i>&nbsp;&nbsp; Internal calibration
                                                            </a>
                                                        @elseif(($value->Type=='External') || ($value->Type=='EXTERNAL') || ($value->Type=='external'))
                                                            <a href="{{url('/user/calibration/cal_logext?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                                <i class="fa fa-record-vinyl" style="font-size:10px;"></i>&nbsp;&nbsp; Calibration log
                                                            </a>
                                                            @if($value->Status != 'Obsolete')
                                                                @if(($value->Status=='Near to expiry') || ($value->Status==NULL) || ($value->Status=="Expired") || ($value->Duedate==$today_dt))
                                                                <a href="{{url('/user/calibration/viewstatus?val='.$id . '&tab=ext')}}" class="dropdown-item" style="font-size:10px">
                                                                    <i class="far fa-2x fa-check-square" style="font-size:12px;"></i>&nbsp;&nbsp; Update calibration status
                                                                </a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                                <a href="#" data-route="{{$value->id}}" class="btn btn-sm btn-alt-primary btn-view-detail" data-type='external' data-id="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View">
                                                    <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                                </a>

                                                <a href="#" class="btn btn-sm btn-alt-primary btn-add-attachment {{$value->Status=='Obsolete' ? 'disabled' : ''}}" data-id="{{ $value->id }}"
                                                    data-toggle="modal" data-placement="top"
                                                    data-target="#addattachmentsModal">
                                                    <span data-toggle="tooltip" title="Click here to add attachments">
                                                        <i class="fa fa-cloud-upload-alt text-center " style="font-size:10px;cursor:pointer" ></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </td>
                                    </div>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>
                    </div>
                     <!-- Ends External tab  -->
                </div>
            </div>
        </div>
    </div>
<div class="calibration-area"></div>
<div class="addattachments-div"></div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
@php
    $addCalibration = Gate::allows('add-calibration');
@endphp
<script>
    $('#addCalibration').click(function() {
        var addC = "{{$addCalibration}}";
        if (addC) {
            window.location.href = "{{url('/user/calibration/addinstruments')}}";
        } else {
            Swal.fire({
                title: 'Warning!',
                text: 'You are not authorized to perform this action. Please contact the admins.',
                icon: 'warning'
            });
        }
    });
</script>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('.flash-msg .alert').fadeOut('slow', function () {
                $(this).remove();
            });
        }, 5000);
    });
</script>
<script>
    $(function () {
        const navEntries = performance.getEntriesByType("navigation");

        const createHeadingSelect = (
            tableId = 'tabledata_int',
            headingName = '', headingId = '',
            values = '', dropdownId = '', columnIndex = 0, prefix = ''
        ) => {
            let nameArr = values.split(',');
            let optionText = "";
            $("#" + tableId).one("preInit.dt", function() {
                let table = $("#" + tableId).DataTable();

                // fetching saved state data
                let state = table.state.loaded();

                /* assume page is not reloaded */
                let reloadFlag = 0;

                /* check whethe page is reloaded */
                if (navEntries.length > 0 && navEntries[0].type === "reload") {
                    reloadFlag = 1;
                }

                /* checking if column index has filter data and page is not reloaded */
                if (state && state.columns[columnIndex].search.search && !reloadFlag) {

                    /* fetch saved state data of filter column  */
                    let savedSearch = state.columns[columnIndex].search.search;

                    /* remove prefix */
                    savedSearch = savedSearch.replace(prefix, '');

                    /* special case */
                    if (
                        headingId == 'frequency_int_selectData' ||
                        headingId == 'frequency_ext_selectData'
                    ) {
                        /* formatting value */
                        savedSearch = ssavedSearch = savedSearch
                            .replace(prefix, '')
                            .replace(/(\d+)/g, '$1 ')
                            .replace(/month/g, 'month')
                            .replace(/halfyearly/g, 'half yearly')
                            .trim();
                    }

                    /* Resetting the header of column starts here (this whole block is done when page is refreshed the header value doesnt change based on the filter state in the browser local storage) */
                    for (let i = 0; i < nameArr.length; i++) {
                        if(nameArr[i] != '') {
                            if (savedSearch == nameArr[i]) {
                                optionText += `<option value="${nameArr[i]}">${nameArr[i]}&nbsp;&nbsp;&#9207;</option>`;
                            } else {
                                optionText += `<option value="${nameArr[i]}">${nameArr[i]}</option>`;
                            }
                        }
                    }
                    const heading = $(`<select class="headingselect"
                                style="margin-left:10px;font-weight: 600;width: 100px" id="${dropdownId}">
                                    <option value="${headingName}">${headingName}</option>${optionText}
                                </select>`);

                    $('#' + headingId).html(heading);

                    $("#" + dropdownId).val(savedSearch);
                    if (savedSearch !== '') {
                        $("#" + dropdownId).addClass('text-primary');
                    }
                } else {
                    for (let i = 0; i < nameArr.length; i++) {
                        if(nameArr[i] != '') {
                            optionText += `<option value="${nameArr[i]}">${nameArr[i]}</option>`;
                        }
                    }
                    const heading = $(`<select class="headingselect"
                                style="margin-left:10px;font-weight: 600;width: 100px" id="${dropdownId}">
                                    <option value="${headingName}">${headingName}&nbsp;&nbsp;&#9207;</option>${optionText}
                                </select>`);

                    $('#' + headingId).html(heading);
                }
                // ---------------- CHANGE EVENT ----------------
                $("#" + dropdownId).on('change', function() {
                    if ($(this).val() != headingName) {
                        const value = $(this).val();
                        let searchValue = prefix + value;
                        if (headingId == 'frequency_int_selectData' || headingId == 'frequency_ext_selectData') {
                            searchValue = prefix + value.replace(/ /g, '');
                        }

                        table.columns(columnIndex).search(searchValue).draw();
                        $(this).addClass('text-primary');
                        $("#" + dropdownId +  "option").css('color', 'black')
                    } else {
                        table.columns(columnIndex).search('').draw();

                        // Optional: remove active color
                        $(this).removeClass('text-primary');
                    }
                });
            });
        }
        createHeadingSelect('tabledata_int', 'Frequency', 'frequency_int_selectData', "{{ $allFrequency }}", 'frequency_int_dropdown', 11, 'fre_int');
        createHeadingSelect('tabledata_int', 'Date of calibration', 'dateofcal_int_selectData', "{{ $allDateOfCalibrations }}", 'dateofcal_int_dropdown', 12);
        createHeadingSelect('tabledata_int', 'Due date', 'duedate_int_selectData', "{{ $allDueDates }}", 'duedate_int_dropdown', 13);
        createHeadingSelect('tabledata_int', 'Status', 'status_int_selectData', "{{ $allStatuses }}", 'status_int_dropdown', 14);
        createHeadingSelect('tabledata_ext', 'Frequency', 'frequency_ext_selectData', "{{ $allFrequency }}", 'frequency_ext_dropdown', 10, 'fre_ext');
        createHeadingSelect('tabledata_ext', 'Date of calibration', 'dateofcal_ext_selectData', "{{ $allDateOfCalibrations }}", 'dateofcal_ext_dropdown', 11);
        createHeadingSelect('tabledata_ext', 'Due date', 'duedate_ext_selectData', "{{ $allDueDates }}", 'duedate_ext_dropdown', 12);
        createHeadingSelect('tabledata_ext', 'Status', 'status_ext_selectData', "{{ $allStatuses }}", 'status_ext_dropdown', 14);
    });

    $('body').on('click', '.btn-view-detail', function () {
        const id = $(this).attr('data-route');
        const type = $(this).attr('data-type');
        $.ajax({
            url : window.location.protocol + "//" + window.location.host +`/user/calibration/${id}/${type}/show`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.success) {
                    $('.calibration-area').html(data.view)
                    const modalEle = $(`#viewModal`);
                    modalEle.modal('show');
                } else {
                    alert('Data is not found');
                }
            },
            error: function () {
                alert('Handle error');
            }
        });
    }).on('click','.btn-add-attachment', function (e) {
        const id = $(this).attr('data-id');
        const pagename = 'register';
        e.preventDefault();
        e.stopPropagation();
        $.ajax({
            url : window.location.protocol + "//" + window.location.host +`/user/calibration/${id}/${pagename}/add-attachment`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.success) {
                    $('.addattachments-div').html(data.view)
                    const modalEle = $(`#addattachmentsModal`);
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
</script>
    <script>
        $("#del").click(function() {
            const firstConfirm = confirm("Are you sure?");
            if (!firstConfirm) return;

            const secondConfirm = confirm("Instrument details will be deleted permanently. Press OK to continue.");
            if (!secondConfirm) return;

            var dataArr = [];
            $('input:checkbox:checked').each(function() {
                dataArr.push($(this).attr('id'));
            });

            if (dataArr.length === 0) {
                alert('No Record Selected');
                return;
            }
            $.ajax({
                    type: 'POST',
                    url: '{{ route("deletecal") }}',
                    data: { ids: dataArr }
                    , success: function(response) {
                        if(response.status===200){
                            $("#delmessage").css('display', 'block');
                        }
                            window.location.href = response.redirect;
                    }
                    , error: function(err) {
                        alert(err.responseJSON?.message || "Error deleting records.");
                    }
                });
            });

            $(document).ready(function () {
                setTimeout(function () {
                    $('#delmessage').fadeOut('slow');
                }, 2000);
            });

        function autoResizeDiv(height) {
                document.getElementById('content').style.height = (height) + 'px';
            }
            window.onresize = autoResizeDiv;
            autoResizeDiv(window.innerHeight - 100);

    //-------------------external add attachment starts----------------//
    let CSRF_TOKEN = "";
            window.onload = function() {
                CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            }

            $(document).on('submit', '#add_attachments_ajax', function(e) {
                e.preventDefault();
                let uploadformdata = new FormData(this);
                $.ajax({
                    data: uploadformdata
                    , url: "{{ route('calibration.addexternalattachments')}}"
                    , type: "POST"
                    , contentType: false
                    , processData: false
                    , _token: CSRF_TOKEN
                    , success: function(response) {
                        if (response.status == 200) {
                            $('#addattachmentsuccess').css('display', 'block');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    },
                    error: function(err) {
                    if (err.status === 500) {
                                var error_text='Internal server error';
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });
                                $('#attachmentnotupdated').html(error_text);
                                $('#dangeraddattachment').hide();
                                $('#attachmentnotupdated').css('display', 'block');
                        }
                    }
                });

            });


    //-------------------Ends external add attachment------------------//
        $(function () {

            // Function to update the URL parameter
            function updateUrlParameter(param, value) {
                let currentUrl = window.location.href;
                let url = new URL(currentUrl);
                url.searchParams.set(param, value);
                window.history.pushState({ path: url.href }, '', url.href);
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('body').on('click', '.js-internal', function () {
                $('.under-calibration-wrap').addClass('d-none')
                updateUrlParameter('tab', 'int');
            }).on('click', '.js-external', function () {
                $('.under-calibration-wrap').removeClass('d-none')
                updateUrlParameter('tab', 'ext');
            }).on('click', '.js-change-to-under', function () {
                const url = $(this).attr('data-route');
                const status = $(this).attr('data-status');
                const targetIds = [];
                $('.external-item').each(function (index, item) {
                    if($(item).is(":checked")) {
                        targetIds.push($(item).attr('data-calibration-id'))
                    }
                })

                if(targetIds.length) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Are you sure you want to mark selected instruments as Under Calibration?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url,
                                data: {
                                    'ids': targetIds,
                                    'status': status
                                },
                                type: 'POST',
                                beforeSend: function () {
                                    $('.flash-msg').html('<div class="flash_message alert-primary text-center py-2 my-2">' +
                                        '<span class="fas fa-info"></span> Processing.....</div>')
                                },
                                success: function (data) {
                                    if (data.success) {
                                        const queryString = "?tab=ext";
                                        const currentUrl = window.location.origin + window.location.pathname;
                                        let countdown = 5;
                                        const countdownInterval = setInterval(function() {
                                            $('.flash-msg').html('<div class="flash_message alert-success text-center py-2 my-2">' +
                                                '<span class="fas fa-check-circle"></span> Selected instruments marked as Under Calibration successfully. This page will be reloaded after ' + countdown + ' seconds</div>')
                                            countdown--;
                                            if (countdown < 0) {
                                                clearInterval(countdownInterval);
                                                window.location.href = currentUrl + queryString;
                                            }
                                        }, 1000);
                                    } else {
                                        $('.flash-msg').html('<div class="flash_message alert-danger text-center py-2 my-2">' +
                                            '<span class="fas fa-exclamation-triangle"></span> Change the status failure</div>')
                                    }
                                }
                            });
                        }
                    });

                } else {
                    $('.flash-msg').html('<div class="flash_message alert-danger text-center py-2 my-2">' +
                        '<span class="fas fa-exclamation-triangle"></span> ' +
                        'Please select the item to change the status!</div>');
                    setTimeout(function() {
                        $('.flash-msg .flash_message').fadeOut('slow', function() { $(this).remove(); });
                    }, 2500);
                }
            }).on('click', '.js-change-to-obsolete', function () {
                const url = $(this).attr('data-route');
                const targetIds = [];
                $('.external-item').each(function (index, item) {
                    if($(item).is(":checked")) {
                        targetIds.push($(item).attr('data-calibration-id'))
                    }
                })
                if(targetIds.length) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Are you sure you want to mark selected instruments as Obsolete?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url,
                                data: {
                                    'ids': targetIds,
                                },
                                type: 'POST',
                                beforeSend: function () {
                                    $('.flash-msg').html('<div class="flash_message alert-primary text-center py-2 my-2">' +
                                        '<span class="fas fa-info"></span> Processing.....</div>')
                                },
                                success: function (data) {
                                    if (data.success) {
                                        const queryString = "?tab=ext";
                                        const currentUrl = window.location.origin + window.location.pathname;
                                        let countdown = 5;
                                        const countdownInterval = setInterval(function() {
                                            $('.flash-msg').html('<div class="flash_message alert-success text-center py-2 my-2">' +
                                                '<span class="fas fa-check-circle"></span> Selected instruments marked as Obsolete successfully. This page will be reloaded after ' + countdown + ' seconds</div>')
                                            countdown--;
                                            if (countdown < 0) {
                                                clearInterval(countdownInterval);
                                                window.location.href = currentUrl + queryString;
                                            }
                                        }, 1000);
                                    } else {
                                        $('.flash-msg').html('<div class="flash_message alert-danger text-center py-2 my-2">' +
                                            '<span class="fas fa-exclamation-triangle"></span> Change the status failure</div>')
                                    }
                                }
                            })
                        }
                    });
                } else {
                    $('.flash-msg').html('<div class="flash_message alert-danger text-center py-2 my-2">' +
                        '<span class="fas fa-exclamation-triangle"></span> ' +
                        'Please select the item to change the status!</div>');
                    setTimeout(function() {
                        $('.flash-msg .flash_message').fadeOut('slow', function() { $(this).remove(); });
                    }, 2500);
                }
            });
        });
    </script>

    <!-- END Main Container -->
    <!-- END Footer -->
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/calibrationmgmt">
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

<script>
    function moveToRow() {
        var params = new URLSearchParams(window.location.search);
        var rowId = params.get('rowId');

        if (rowId) {
            var table_int = $('#tabledata_int').DataTable();
            var table_ext = $('#tabledata_ext').DataTable();

            let table = params.get('tab') === 'ext' ? table_ext : table_int;

            rowId = params.get('tab') == 'ext' ? 'row_ext_'+rowId : 'row_int_'+rowId;
            setTimeout(function () {
                /* find row index */
                var targetIndex = -1;

                var filteredIndexes =
                    table.rows({ search: 'applied' }).indexes().toArray();

                    var targetIndex = filteredIndexes.findIndex(function (idx) {

                    var node = table.row(idx).node();

                    return $(node).attr('id') == rowId;

                });
                /* row found */
                if (targetIndex >= 0) {
                    var pageInfo = table.page.info();
                    var targetPage = Math.floor(
                        targetIndex / pageInfo.length
                    );

                    /* wait for page draw */
                    table.one('draw.dt', function () {
                        setTimeout(function () {
                            var targetRow = $('#' + rowId);
                            if (targetRow.length) {
                                targetRow.addClass('table-warning');
                                var scrollBody = targetRow.closest('.scrollable');
                                // Scroll outer page/container
                                $('html, body').animate({
                                    scrollTop: scrollBody.offset().top - 100
                                }, 500);

                                // Scroll inner container
                                scrollBody.animate({
                                    scrollTop:
                                        scrollBody.scrollTop()
                                        + targetRow.position().top
                                        - 100
                                }, 1000);

                                setTimeout(function () {
                                    targetRow.removeClass('table-warning');
                                }, 3000);
                            }
                        }, 1000);

                    });
                    table.page(targetPage).draw(false);
                }
            }, 500);
        }
    }
</script>
<script>
    $(document).ready(function () {
        var table_int = $('#tabledata_int').DataTable({
            stateSave: true,
            lengthMenu: [
                [10, 25, 50, 100, 200, 500, 1000],
                [10, 25, 50, 100, 200, 500, 1000]
            ],
            language: {
                lengthMenu: "_MENU_"
            }
        });

        var table_ext = $('#tabledata_ext').DataTable({
            stateSave: true,
            lengthMenu: [
                [10, 25, 50, 100, 200, 500, 1000],
                [10, 25, 50, 100, 200, 500, 1000]
            ],
            language: {
                lengthMenu: "_MENU_"
            }
        });

        var params = new URLSearchParams(window.location.search);
        const navEntries = performance.getEntriesByType("navigation");

        /* if page is reloaded set the state value or filter value to empty else scroll to row  */
        if (navEntries.length > 0 && navEntries[0].type === "reload") {

            // resetting column filters of internal table
            table_int.search('').columns().search('').draw();
            table_int.state.clear();

            // reseting column filters of external table
            table_ext.search('').columns().search('').draw();
            table_ext.state.clear();

        } else {
            moveToRow();
        }
});

</script>
    @include('exporting.forcalibration')
@endsection


@section('sideitem')
{{-- @if(count($mr)>0) --}}
@if(Gate::allows('delete-calibration') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
{{-- @endif --}}

@endsection
