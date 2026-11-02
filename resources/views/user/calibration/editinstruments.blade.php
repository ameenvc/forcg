@extends('layouts.multiselect')
@section('content')
<!-- Page Content -->
<div class="content" style="width:88%;">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">Edit Instrument Details</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="margin-left:70px">
                <ol class="breadcrumb breadcrumb-alt">

                    <li class="breadcrumb-item" aria-current="page">

                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/calibrationmgmt?tab={{ $cal->Type == 'internal' ? 'int' : 'ext' }}&rowId={{request()->get('val')}}">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full" style="width:90%;">
            <div class="row">
                <div class="col-md-10">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" method="post" action="{{url('/user/calibration/instupdate')}}" enctype="multipart/form-data" files="true">
                        {{ csrf_field() }}
                    @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                        <div class='alert alert-warning'>
                            {{ $error }}
                        </div>
                        @endforeach
                    @endif
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif

                        @php
                            $status = $cal->Status;
                            $status_val = $status == null ? "Not Calibrated" : $cal->Status;
                            $calibrationFieldRules = json_decode($cal->calibration_field_rules, true);
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="margin-bottom:70px;" id="edit_instrument_table">
                                <thead></thead>
                                <tbody class="sortable-field">
                                    @if ($calibrationFieldRules['Instrument_no']['enable_field'])
                                        <tr data-index="{{array_shift($defaultCountIndex)}}">
                                            <td class="col-sm-4">
                                                <label class="col-form-label" style="font-size: 13px;" for="type">{{$calibrationFieldRules ? $calibrationFieldRules['Instrument_no']['field_display_label'] : 'Instrument number'}}
                                                    @if ($calibrationFieldRules)
                                                        @if ($calibrationFieldRules['Instrument_no']['field_is_mandatory'])
                                                            <span class="text-danger">*</span>
                                                        @endif
                                                    @else
                                                        <span class="text-danger">*</span>
                                                    @endif</label>
                                            </td>
                                            <td class="col-sm-8">
                                                <input type="text" class="form-control @error('Instrument_no') is-invalid @enderror"
                                                    style="font-size: 13px;" name="Instrument_no" id="instrument_no"  placeholder="Enter {{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_no']['field_display_label'] : 'Instrument number'}}"
                                                    value="{{ old('Instrument_no', $cal->Instrument_no) }}">
                                                <span class="text-danger" id="Instrument_no_invalid" style="font-size: 12px; margin-top: 5px">@error('Instrument_no'){{ $message }}@enderror</span>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($calibrationFieldRules['Instru_Manufacturer']['enable_field'])
                                        <tr data-index="{{array_shift($defaultCountIndex)}}">
                                            <td class="col-sm-4">
                                                <label class="col-form-label" style="font-size: 13px;" for="type">{{$calibrationFieldRules ? $calibrationFieldRules['Instru_Manufacturer']['field_display_label'] : 'Instrument manufacturer'}}
                                                    @if ($calibrationFieldRules && $calibrationFieldRules['Instru_Manufacturer']['field_is_mandatory'])<span class="text-danger">*</span>@endif
                                                </label>
                                            </td>
                                            <td class="col-sm-8">
                                                <span style="font-size:13px;">
                                                    @if($cal->Instru_Manufacturer=='no value')
                                                    <input type="text" class="form-control" style="font-size: 13px;" name="inst_manf" value="" placeholder="Enter {{ $calibrationFieldRules ? $calibrationFieldRules['Instru_Manufacturer']['field_display_label'] : 'Instrument manufacturer'}}">
                                                    @else
                                                    <input type="text" class="form-control" style="font-size: 13px;" name="inst_manf" value="{{$cal->Instru_Manufacturer}}" placeholder="Enter {{ $calibrationFieldRules ? $calibrationFieldRules['Instru_Manufacturer']['field_display_label'] : 'Instrument manufacturer'}}">
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                    @endif

                                    <tr data-index="{{array_shift($defaultCountIndex)}}">
                                        <td class="col-sm-4">
                                            <label class="col-form-label" style="font-size: 13px;" for="type">Type</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <select class="form-control" name="type" id="type" style="font-size: 13px;">
                                                <option value="internal" {{ $cal->Type == 'internal' ? 'selected' : ''}}>Internal</option>
                                                <option value="external" {{ $cal->Type == 'external' ? 'selected' : ''}}>External</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @if ($calibrationFieldRules['Instrument_desc']['enable_field'])
                                        <tr data-index="{{array_shift($defaultCountIndex)}}">
                                            <td class="col-sm-4">
                                                <label class="col-form-label" style="font-size: 13px;" for="type">{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_desc']['field_display_label'] : 'Instrument description'}}
                                                    @if ($calibrationFieldRules && $calibrationFieldRules['Instrument_desc']['field_is_mandatory'])<span class="text-danger">*</span>@endif
                                                </label>
                                            </td>

                                            <td class="col-sm-8">
                                                <textarea name="desc" class="form-control" style="font-size: 13px;" placeholder="Enter {{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_desc']['field_display_label'] : 'Instrument description'}}">{{$cal->Instrument_desc}}</textarea>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr data-index="{{array_shift($defaultCountIndex)}}">
                                        <td class="col-sm-4">
                                            <label class=" col-form-label" style="font-size: 13px;" for="type">Department</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <select class="form-control" name="dept" style="font-size: 13px;" required="true">
                                                <option value="" style="font-size: 13px;">choose Department</option>
                                                @if(count($dept)> 0 )
                                                @foreach($dept as $value)
                                                <option value=" {{$value->id}}" <?php if($cal->Department == $value->id){ ?> selected="selected" <?php } ?>> {{ $value->departmentname}}</option></br>
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>
                                    </tr>
                                    @if ($calibrationFieldRules['Holder']['enable_field'])
                                        <tr data-index="{{array_shift($defaultCountIndex)}}">
                                            <td class="col-sm-4">
                                                <label class=" col-form-label" style="font-size: 13px;" for="type">{{ $calibrationFieldRules ? $calibrationFieldRules['Holder']['field_display_label'] : 'Instrument holder/location'}}
                                                    @if ($calibrationFieldRules && $calibrationFieldRules['Holder']['field_is_mandatory']) <span style="color:red">*</span> @endif
                                                </label>
                                            </td>
                                            <td class="col-sm-8">
                                                <input type="text" class="form-control" style="font-size: 13px;" name="holder" value="{{$cal->Holder}}" placeholder="Enter {{ $calibrationFieldRules ? $calibrationFieldRules['Holder']['field_display_label'] : 'Instrument holder/location'}}">
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($calibrationFieldRules['Capacity']['enable_field'])
                                        <tr data-index="{{array_shift($defaultCountIndex)}}">
                                            <td class="col-sm-4">
                                                <label class=" col-form-label" style="font-size: 13px;" for="type">{{$calibrationFieldRules ? $calibrationFieldRules['Capacity']['field_display_label'] : 'Instrument capacity/range'}}
                                                    @if ($calibrationFieldRules && $calibrationFieldRules['Capacity']['field_is_mandatory']) <span style="color:red">*</span> @endif
                                                </label>
                                            </td>
                                            <td class="col-sm-8">
                                                <input type="text" class="form-control" style="font-size: 13px;" name="capacity" value="{{$cal->Capacity}}" placeholder="Enter {{$calibrationFieldRules ? $calibrationFieldRules['Capacity']['field_display_label'] : 'Instrument capacity/range'}}">
                                            </td>
                                        </tr>
                                    @endif

                                    <tr data-index="{{array_shift($defaultCountIndex)}}">
                                        <td class="col-sm-4">
                                            <label class=" col-form-label" style="font-size: 13px;" for="least">Least count</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <input type="text" class="form-control" style="font-size: 13px;" name="least_count" value="{{$cal->least_count}}">
                                        </td>
                                    </tr>
                                    @if ($calibrationFieldRules['Calib_procedure']['enable_field'])
                                        <tr data-index="{{array_shift($defaultCountIndex)}}">
                                            <td class="col-sm-4">
                                                <label class=" col-form-label" style="font-size: 13px;" for="type">{{$calibrationFieldRules ? $calibrationFieldRules['Calib_procedure']['field_display_label'] : 'Calibration procedure'}}
                                                    @if ($calibrationFieldRules && $calibrationFieldRules['Calib_procedure']['field_is_mandatory']) <span style="color:red">*</span> @endif
                                                </label>
                                            </td>
                                            <td class="col-sm-8">
                                                <!-- <input type="text" class="form-control" style="font-size: 13px;"   name="calib_procedure" value="{{$cal->Calib_procedure}}"> -->
                                                <textarea type="text" class="form-control" style="font-size: 13px;" name="calib_procedure" placeholder="Enter {{$calibrationFieldRules ? $calibrationFieldRules['Calib_procedure']['field_display_label'] : 'Calibration procedure'}}">{{$cal->Calib_procedure}}</textarea>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr data-index="{{array_shift($defaultCountIndex)}}">
                                        <td class="col-sm-4">
                                            <label class=" col-form-label" style="font-size: 13px;" for="type">Standard Edition</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <input type="text" class="form-control" style="font-size: 13px;" name="standard_edition" value="{{$cal->standard_edition}}">
                                        </td>
                                    </tr>

                                    <tr data-index="{{array_shift($defaultCountIndex)}}">
                                        <td class="col-sm-4">
                                            <label class=" col-form-label" style="font-size: 13px;" for="type">Acceptance Criteria</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <input type="text" class="form-control" style="font-size: 13px;" name="acceptance_criteria" value="{{$cal->acceptance_criteria}}">
                                        </td>
                                    </tr>
                                    @if(($cal->Type == "External") || ($cal->Type == "EXTERNAL") || ($cal->Type == "external"))
                                    <tr data-index="{{array_shift($defaultCountIndex)}}">
                                        <td class="col-sm-4">
                                            <label class=" col-form-label" style="font-size: 13px;" for="number">Certficate Number</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <input type="text" class="form-control" style="font-size: 13px;" name="report_num_ext" value="{{$report_no_ext}}">
                                        </td>
                                    </tr>
                                    @if ($calibrationFieldRules['Agency']['enable_field'])
                                        <tr data-index="{{array_shift($defaultCountIndex)}}">
                                            <td class="col-sm-4">
                                                <label class=" col-form-label" style="font-size: 13px;" for="type">{{$calibrationFieldRules ? $calibrationFieldRules['Agency']['field_display_label'] : 'Calibration agency'}}
                                                    @if ($calibrationFieldRules && $calibrationFieldRules['Agency']['field_is_mandatory']) <span style="color:red">*</span> @endif
                                                </label>
                                            </td>
                                            <td class="col-sm-8">
                                                <input type="text" class="form-control" style="font-size: 13px;" name="agency" value="{{$cal->Agency}}" placeholder="Enter {{$calibrationFieldRules ? $calibrationFieldRules['Agency']['field_display_label'] : 'Calibration agency'}}">
                                            </td>
                                        </tr>
                                    @endif


                                    <tr data-index="{{array_shift($defaultCountIndex)}}">
                                        <td class="col-sm-4">
                                            <label class=" col-form-label" style="font-size: 13px;" for="type">Attachments</label>
                                        </td>
                                        <td class="col-sm-8">
                                            @if($cal->extfile==NULL)
                                            <span class="text-danger" style="font-size: 13px;">No Attachments</span>
                                            <a href="#" class="button btn btn-warning" style="font-size:11px;width:150px;" data-toggle="modal" data-target="#attachmentModal">Add Attachments</a>
                                            @else
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td align="right" colspan="3"><a href="#" class="button btn btn-sm btn-warning" style="font-size:11px;width:150px;" data-toggle="modal" data-target="#attachmentModal">Add More Attachments</a> </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:11px;">#</td>
                                                    <td>
                                                        <span style="font-size:11px;">Filename</span>
                                                    </td>
                                                </tr>
                                                <?php
                                                $file_values = DB::table('calibration_externalfiles')->where('instrument_id',$cal->id)->get();
                                                $sl =1;
                                            ?>
                                                @foreach($file_values as $file_value)
                                                <tr>
                                                    <td style="font-size:11px;">{{$sl++}}</td>
                                                    <td>
                                                        <span style="font-size:11px;">{{$file_value->Filename}} </span>
                                                        <a style="font-size:11px;width: 80px;" class="text-primary" href="{{asset("storage/calibration/".$file_value->Filename)}}" download="{{$file_value->Filename}}">(Download) </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{url('/user/calibration/deleteexternalfiles/'.$file_value->id)}}" class="btn btn-sm btn-alt-danger" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>



                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr data-index="{{array_shift($defaultCountIndex)}}">
                                        <td class="col-sm-4">
                                            <label class=" col-form-label" style="font-size: 13px;" for="type">Frequency</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <select class="form-control" name="freq" style="font-size: 13px;" required="true" id="frequency">
                                                <option value="" style="font-size: 13px;">choose frequency</option>
                                                @if(count($freq)> 0 )
                                                @foreach($freq as $value)
                                                <option value="{{$value->frequency}}" <?php if(trim(strtolower($cal->Frequency)) == trim(strtolower($value->frequency))){ ?> selected="selected" <?php } ?>> {{ $value->frequency}}</option></br>
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>
                                    </tr>
                                    <tr data-index="{{array_shift($defaultCountIndex)}}">
                                        <td class="col-sm-4">
                                            <label class="col-form-label" style="font-size: 13px;" for="type">Date of First Use</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <input type="date" class="form-control" style="font-size: 13px;" name="date_of_first_use" placeholder="dd-mm-yyyy" value="{{$cal->date_of_first_use}}">
                                        </td>
                                    </tr>
                                    <tr data-index="{{array_shift($defaultCountIndex)}}">
                                        <td class="col-sm-4">
                                            <label class="col-form-label" style="font-size: 13px;" for="type">Date of Calibration</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <input type="date" id="date-of-calibration" class="form-control" style="font-size: 13px;" required name="caldt" placeholder="dd-mm-yyyy" value="{{$cal->dateofcal}}">
                                        </td>
                                    </tr>
                                    <tr data-index="{{array_shift($defaultCountIndex)}}" id='duedaterow'>
                                        <td class="col-sm-4">
                                            <label class="col-form-label" style="font-size: 13px;" for="type">Due Date</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <input type="date" id="due-date" class="form-control" style="font-size: 13px;" required name="duedat" placeholder="dd-mm-yyyy" value="{{$cal->Duedate}}">
                                        </td>
                                    </tr>

                                    <input type="hidden" name='id' value="<?php echo $cal->id; ?>">
                                    <input type="hidden" name='status' value="<?php echo $status; ?>">
                                    <input type="hidden" name='instrtid' value="<?php echo $cal->id; ?>">
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 text-center">
                                <td><input type="submit" name="submit" class="btn btn-primary" style="font-size:12px;" value="Update"></td>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- add attachment starts-->
    <div class="modal fade" id="attachmentModal" tabindex="-1" role="dialog" aria-labelledby="attachmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attachmentModalLabel">Add attachments</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="alert alert-success" id="successBtntitle" style="display:none">
                        Attachments Successfully saved
                    </div>

                    <ul class="alert alert-danger" id="dangerValidationtitle" style="display:none"></ul>
                    <form id="save_attachment" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name='calid' id="calid" value="<?php echo $cal->id; ?>">

                        <div class="form-group">
                            <label class="col-form-label" style="font-size:12px;"> Attach files(avoid '#' and '&' in file names)</label>

                            <input type="file" style="font-size: 13px;" class="form-control" name="externalfiles[]" id="externalfiles" multiple>
                        </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <!-- <button type="button" class="btn btn-primary" style="font-size:12px" onclick="saveAttachments('{{$cal->id}}')">Submit</button> -->
                    <button type="submit" class="btn btn-primary" style="font-size:12px">Upload</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- add attachment  ends-->
    <!-- END Page Content -->
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    let CSRF_TOKEN = "";
    var ext_custom_fields = {!!$exCustomFields!!};
    var int_custom_fields = {!!$inCustomFields!!};
    var $CustomFields = `{!! $CustomFields !!}`;
    var $defaultCustomFields = `{!! $defaultCustomFields !!}`;
    var initialType = $('#type').val();
    var isChangedType = false;
    window.onload = function () {
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let $defaultRows = $($defaultCustomFields).addClass('dynamic-field-row');
        $('#edit_instrument_table tr:last').after($defaultRows); // insert after last row
        setFieldRequirements(initialType);
        $('.custom_field_select').select2();
        var savedOrder = @json($savedOrder);

        var $container = $('.sortable-field');
        var $allFields = $container.children('tr');
        // Map of index to element
        var fieldMap = {};
        $allFields.each(function () {
            var index = $(this).data('index');
            if (index !== undefined) {
                fieldMap[index] = $(this);
            }
        });
        // Remove all fields from container
        $allFields.detach();
        // Append elements in saved order
        if (Array.isArray(savedOrder)) {
            savedOrder.forEach(function (index) {
                if (fieldMap[index]) {
                    $container.append(fieldMap[index]);
                    delete fieldMap[index];
                }
            });
        }
        // Append remaining fields (not in savedOrder)
        $.each(fieldMap, function (index, $el) {
            $container.append($el);
        });
    };
    $(document).on('change', '#type', function () {
        var selectedType = $(this).val();
        if (selectedType !== initialType) {
            // Hide existing dynamic rows and tag them as default
            $('tr.dynamic-field-row').each(function () {
                $(this).addClass('d-none default-rows');
            });
            // Load new custom fields
            var $newRows = $($CustomFields).addClass('dynamic-field-row');
            $('#edit_instrument_table tr:last').after($newRows);
        }else {
            // Show back the default rows
            $('tr.dynamic-field-row.default-rows').removeClass('d-none');
            // Remove only dynamically added custom rows (not default ones)
            $('tr.dynamic-field-row').not('.default-rows').remove();
        }
        $('.custom_field_select').select2();
        setFieldRequirements(selectedType);
    });
    function setFieldRequirements(type) {
        // First remove all required fields
        for (const field in ext_custom_fields) {
            document.querySelectorAll(`[name^="${field}"]`).forEach(el => {
                el.removeAttribute('required');
            });
        }
        for (const field in int_custom_fields) {
            document.querySelectorAll(`[name^="${field}"]`).forEach(el => {
                el.removeAttribute('required');
            });
        }
        // Then set required fields based on current type
        let currentFields = type === 'external' ? ext_custom_fields : int_custom_fields;
        for (const field in currentFields) {
            document.querySelectorAll(`[name^="${field}"]`).forEach(el => {
                if (currentFields[field] === 1) {
                    el.setAttribute('required', 'required');
                }
            });
        }
    }

    $(function () {
        $('body').on('input change', '#instrument_no', function() {
            const ele = $(this);
            const invalidEle = $('#Instrument_no_invalid');
            $.ajax({
                url: "{{ route('calibration.instruments.checkinstrumentno') }}"
                , type: "POST"
                , data: {
                    _token: CSRF_TOKEN
                    , instrumentno: $(this).val()
                    , id: '{{ $cal->id }}'
                }
                , success: function(response) {
                    if (response.success == false) {
                        invalidEle.css('display', 'block');
                        invalidEle.html(response.message);
                        ele.addClass('is-invalid');
                    } else {
                        invalidEle.css('display', 'none');
                        ele.removeClass('is-invalid');
                    }
                } , error: function(response) {
                    invalidEle.css('display', 'none');
                    ele.removeClass('is-invalid');
                }
            });
        })
    });
    $(document).on('submit', '#save_attachment', function(e) {
        e.preventDefault();

        var id = $('#calid').val();
        //console.log(id);
        //let uploadformdata = new FormData($('#test')[0]);// used for uploading single file
        let uploadformdata = new FormData(this);
        let TotalImages = $('#externalfiles')[0].files.length; //Total Images
        let images = $('#externalfiles')[0];
        for (let i = 0; i < TotalImages; i++) {
            uploadformdata.append('images' + i, images.files[i]);
        }

        uploadformdata.append('TotalImages', TotalImages);
        //console.log(uploadformdata);

        $.ajax({
            data: uploadformdata
            , url: "{{ route('addcalibrationattachments')}}"
            , type: "POST"
            , contentType: false
            , processData: false
            , _token: CSRF_TOKEN
            , success: function(response) {
                //console.log(response);
                //console.log(response.data);
                if (response.status == 200) {
                    $('#successBtntitle').css('display', 'block');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            }
        });

    });

</script>
<script>
    $(document).ready(function () {
        function calculateDueDate() {
            const frequency = $('#frequency').val().trim().toLowerCase();
            const calDateValue = $('#date-of-calibration').val();

            if (!frequency || !calDateValue) {
                $('#due-date').val('');
                return;
            }

            const calDate = new Date(calDateValue);
            let daysToAdd = 0;

            // Determine the time interval based on the frequency
            switch (true) {
                // Weekly intervals
                case frequency === 'weekly':
                    daysToAdd = 7;
                    break;
                case frequency === 'bi-weekly' || frequency === 'biweekly':
                    daysToAdd = 14;
                    break;
                case frequency === 'fortnightly':
                    daysToAdd = 14;
                    break;

                // Monthly intervals
                case frequency === 'monthly':
                    daysToAdd = 30;
                    break;
                case frequency === 'bi-monthly' || frequency === 'bimonthly':
                    daysToAdd = 60;
                    break;

                // Quarterly intervals
                case frequency === 'quarterly':
                    daysToAdd = 90;
                    break;

                // Half-yearly intervals
                case frequency === 'half yearly' || frequency === 'half-yearly':
                    daysToAdd = 182;
                    break;

                // Yearly intervals
                case frequency === 'yearly' || frequency === '1 year':
                    daysToAdd = 365;
                    break;

                // Custom intervals like "2 years", "5 months", etc.
                case /\b(\d+)\s*(year|years)\b/.test(frequency): {
                    const years = parseInt(frequency.match(/\d+/)[0]);
                    daysToAdd = years * 365;
                    break;
                }
                case /\b(\d+)\s*(month|months)\b/.test(frequency): {
                    const months = parseInt(frequency.match(/\d+/)[0]);
                    daysToAdd = months * 30;
                    break;
                }
                case /\b(\d+)\s*(week|weeks)\b/.test(frequency): {
                    const weeks = parseInt(frequency.match(/\d+/)[0]);
                    daysToAdd = weeks * 7;
                    break;
                }
                case /\b(\d+)\s*(day|days)\b/.test(frequency): {
                    const days = parseInt(frequency.match(/\d+/)[0]);
                    daysToAdd = days;
                    break;
                }

                default:
                    alert('Invalid frequency format. Please check your input.');
                    $('#due-date').val('');
                    return;
            }

            // Add the calculated days to the calibration date
            calDate.setDate(calDate.getDate() + daysToAdd);

            // Format the calculated due date as dd-mm-yyyy
            const dd = String(calDate.getDate()).padStart(2, '0');
            const mm = String(calDate.getMonth() + 1).padStart(2, '0');
            const yyyy = calDate.getFullYear();
            $('#due-date').val(`${yyyy}-${mm}-${dd}`);
        }
        $('#frequency').on('change keyup', calculateDueDate);
        $('#date-of-calibration').on('change', calculateDueDate);
    });
</script>
@endsection
