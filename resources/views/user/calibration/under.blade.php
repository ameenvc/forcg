@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print', 1)">

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

                <button title="Pdf" onclick="return fnCovertTable('pdf', 1)" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
                <input type="hidden" name="" id="current_user_name" value="{{ Auth::user()->name }}">
                <input type="hidden" name="orientation" id="orientation" value="landscape">

            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel', 1)" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel </button></li>
        <li><button title="Word" onclick="fnCovertTable('word', 1)" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>
<div class="input-group input-group-sm">
    <a type="button" id="addCalibration" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
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
</style>
    <input type="hidden" value="a1" id="tabselected">

    <div class="content" style="width:100%">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">Under Calibration</li>
                <li class="breadcrumb-item">
                    Calibration Management
                </li>
            </ol>
        </nav>
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="container-fluid d-flex justify-content-between w-100 py-2" style="border-bottom: 1px solid #e1e6e9; padding: 1.25rem 1.25rem 1px;">
                    <div></div>
                    <div>
                        <button class="btn btn-outline-secondary btn-sm js-btn-change-status-bulk"
                                disabled type="button"
                                data-route-store-target-id="{{ route('calibration.user.store.target.id') }}"
                                data-route-show-form="{{ route('calibration.user.show.update.status') }}">
                            Change status
                        </button>
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
                    </div>
                </div>
                <div class="block-content tab-content">
                    <div class="table-responsive scrollable" style="height:550px;" id="content">
                        <div class="is-existing-old-data"
                             data-value="{{ isset($targetIdList) && count($targetIdList) ? 'true' : 'false' }}"></div>
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead style="position: sticky; top: 0;z-index:5;background-color:#ffffff;">
                            <tr>
                                <th style="width: 5%;position:sticky;top:-1px">
                                    <input type="checkbox" class="select-all">
                                </th>
                                <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize;position:sticky;top:-1px;">S.No</th>
                                <th class="text-center" style="width:5%;font-size:10px;text-transform:capitalize;position:sticky;top:-1px">{{$calibrationFieldRules['Instrument_no']['field_display_label']}}</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;width:25%;">{{$calibrationFieldRules['Instrument_desc']['field_display_label']}}</th>
                                <th class="text-center" style="font-size:10px;width:100px;text-transform:capitalize;position:sticky;top:-1px">{{$calibrationFieldRules['Instru_Manufacturer']['field_display_label']}}</th>
                                <th class="text-center" style="width: 15%;font-size:10px;text-transform:capitalize;position:sticky;top:-1px">Department</th>
                                <th style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">{{$calibrationFieldRules['Holder']['field_display_label']}}</th>
                                <th style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">{{$calibrationFieldRules['Capacity']['field_display_label']}}</th>
                                <th style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">Least count</th>
                                <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">{{$calibrationFieldRules['Agency']['field_display_label']}}</th>
                                <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">Frequency</th>
                                <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">Date of Calibration</th>
                                <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">&nbsp;&nbsp;&nbsp;DueDate</th>
                                <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">Certificate Number</th>
                                <th class="text-center" style="font-size:10px;width: 10%;text-transform:capitalize;position:sticky;top:-1px">Status</th>
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
                                    <tr>
                                        <td><input class="checkbox select-item" type="checkbox"
                                                   {{ isset($targetIdList) && count($targetIdList) && in_array($value->id, $targetIdList) ? "checked" : "" }}
                                                   data-calibration-id="{{ $value->id }}"
                                                   id="{{ $value->Instrument_no}}" name="id[]"></td>
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
                                                <span style="font-size:10px">--</span>
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
                                                    $reportno = App\CalibrationExternal::where('Instrument_no',$value->Instrument_no)
                                                                                         ->where('Status','Calibrated')
                                                                                         ->value('Report_no');
                                                @endphp
                                                {{$reportno?$reportno:'-'}}
                                            </span>
                                        </td>
                                        <td class="text-center" style="font-size:10px">
                                            <span class="badge badge-primary" style="font-size:10px">Under Calibration</span>
                                        </td>
                                        <div class="dropdown font-size-sm">
                                            @php
                                                $id= $value->id;
                                                $insno= $value->Instrument_no
                                            @endphp

                                            <td class="text-center" style="font-size:10px">
                                                <div class="btn-group m-auto d-flex justify-content-center">
                                                    <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->id}}">
                                                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                                    </a>
                                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->id }}">
                                                        @if(count($mr)>0)
                                                            @if(Gate::allows('edit-calibration'))
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
                                                                @if(($value->Status=='Near to expiry') || ($value->Status==NULL) || ($value->Status=="Expired") || ($value->Duedate==$today_dt))
                                                                    <a href="{{url('/user/calibration/viewstatus?val='.$id)}}" class="dropdown-item" style="font-size:10px">
                                                                        <i class="far fa-2x fa-check-square" style="font-size:12px;"></i>&nbsp;&nbsp; Update calibration status
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <a href="#" data-route="{{$value->id}}" class="btn btn-sm btn-alt-primary btn-view-detail" data-id="{{ $value->id }}" data-toggle="tooltip" data-placement="top" title="View">
                                                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                                    </a>

                                                    <a href="#" class="btn btn-sm btn-alt-primary btn-add-attachment" data-id="{{ $value->id }}"
                                                       data-toggle="modal" data-placement="top"
                                                       data-target="#addattachmentsModal">
                                                        <span data-toggle="tooltip" title="Click here to add attachments">
                                                            <i class="fa  fa-cloud-upload-alt text-center " style="font-size:10px;cursor:pointer" ></i>
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
            </div>
        </div>
    </div>
    <div class="calibration-area"></div>
    <div class="addattachments-div"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    @php
        $addCalibration = Auth::user()->addCalibrationGate;
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
                    $.ajax({
                        type: 'get'
                        , url: "{{route('deletecal')}}" + "?val=" + $(this).attr('id')
                        , success: function(response) {
                            window.location.href = "{{url('/user/calibrationmgmt')}}";
                        }
                        , error: function(err) {
                            alert(err.responseJSON.message)
                        }
                    });
                });

            } else {
                alert('No Record Selected');
            }
        });
        $('body').on('click', '.btn-view-detail', function () {
            const id = $(this).attr('data-route');
            const type = 'undercalibration';
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
            e.preventDefault();
            e.stopPropagation();
            const id = $(this).attr('data-id');
            const pagename = 'undercalibration';
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

         function getinstrumentdetails(id){
              document.getElementById('instrument_main_id').value=id;
            }
    //-------------------Ends external add attachment------------------//
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const toggleTheChangeStatusButton = (isEnable = true) => {
                const btnTarget = $('.js-btn-change-status-bulk');
                if (isEnable) {
                    btnTarget.prop('disabled', false)
                    btnTarget.removeClass('btn-outline-secondary')
                    btnTarget.addClass('btn-secondary')
                } else {
                    btnTarget.prop('disabled', true)
                    btnTarget.addClass('btn-outline-secondary')
                    btnTarget.removeClass('btn-secondary')
                }
            }
            if ($('.is-existing-old-data').attr('data-value') == 'true') {
                const numberOfSelectedItem = $('.select-item:checked').length;
                toggleTheChangeStatusButton(numberOfSelectedItem >= 1)
                $('.select-all').prop('checked', numberOfSelectedItem === $('.select-item').length)
            }
            $('body').on('click', '.select-all', function () {
                if ($(this).is(":checked")) {
                    $('.select-item').prop('checked', true)
                    toggleTheChangeStatusButton()
                } else {
                    $('.select-item').prop('checked', false)
                    toggleTheChangeStatusButton(false)
                }
            }).on('click', '.select-item', function () {
                const numberOfSelectedItem = $('.select-item:checked').length;
                toggleTheChangeStatusButton(numberOfSelectedItem >= 1)
                $('.select-all').prop('checked', numberOfSelectedItem === $('.select-item').length)
            }).on('click', '.js-btn-change-status-bulk', function () {
                const storeTargetIdUrl = $(this).attr('data-route-store-target-id');
                const displayFormUrl = $(this).attr('data-route-show-form');
                const targetIds = [];
                $('input[name="id[]"]').each(function (index, item) {
                    if($(item).is(":checked")) {
                        targetIds.push($(item).attr('data-calibration-id'))
                    }
                })

                if(targetIds.length) {
                    $.ajax({
                        url: storeTargetIdUrl,
                        data: {
                            'ids': targetIds,
                        },
                        type: 'POST',
                        success: function (data) {
                            if (data.success) {
                                window.location = displayFormUrl;
                            } else {
                                $('.flash-msg').html('<div class="flash_message alert-danger text-center py-2 my-2">' +
                                    '<span class="fas fa-exclamation-triangle"></span> Can not display the form to change the status</div>')
                            }
                        }
                    })
                } else {
                    $('.flash-msg').html('<div class="flash_message alert-danger text-center py-2 my-2">' +
                        '<span class="fas fa-exclamation-triangle"></span> ' +
                        'Please select the item to change the status!</div>')
                }
            })
        })
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
    @include('exporting.forcalibration')
@endsection
<script>
    function ViewBtn(id) {
        $('#viewModal' + id).modal('show');
    }
</script>



