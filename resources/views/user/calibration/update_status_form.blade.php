@extends('layouts.multiselect')
@section('content')
    <style>
        .label-invalid {
            font-size: 12px;
            padding: 0;
            color: red
        }
    </style>
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"  style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Calibration Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Change status</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/calibration/under-list">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <h3 style="font-size:18px"><b>Change status</b></h3>
                    <div class="container-fluid">
                        <div class="flash-msg-on-modal">
                        </div>
                        <div class="flash-msg-of-reviewer"></div>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        @php $count=1; @endphp
                        @foreach($calibrations as $index => $calibration)
                            @php
                                $temp = $FieldIndex;
                                $calibrationFieldRules = json_decode($calibration->calibration_field_rules, true);
                            @endphp
                            <div class="row sortable-field">
                                <input type="hidden" id="target_ids_{{ $calibration->id }}"
                                    data-item-id="{{ $calibration->id }}"
                                    name="target_ids[]" value="{{ $calibration->id }}">
                                <div class="label-invalid col error-target_ids_{{ $calibration->id }}"></div>
                                <!-- Instrument number -->
                                @if ($calibrationFieldRules['Instrument_no']['enable_field'])
                                    <div class="form-group col-sm-3" data-index="{{array_shift($temp)}}">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;"
                                            for="instrument_numbers_{{ $calibration->id }}">{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_no']['field_display_label'] : 'Instrument number'}} <span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="instrument_numbers_{{ $calibration->id }}" style="font-size: 13px;" disabled
                                                data-item-id="{{ $calibration->id }}"
                                                value="{{ $calibration->Instrument_no }}"
                                                name="instrument_numbers[]">
                                            <div class="label-invalid col error-instrument_numbers_{{ $calibration->id }}"></div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Certificate Number -->
                                <div class="form-group col-sm-3" data-index="{{array_shift($temp)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="certificate_numbers_{{ $calibration->id }}">Certificate Number <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="certificate_numbers_{{ $calibration->id }}" style="font-size: 13px;"
                                            data-item-id="{{ $calibration->id }}"
                                            value="{{ $certificateNumbers[$calibration->id] }}"
                                            name="certificate_numbers[]">
                                        <div class="label-invalid col error-certificate_numbers_{{ $calibration->id }}"></div>
                                    </div>
                                </div>
                                <!-- Date of Calibration -->
                                <div class="form-group col-sm-3" data-index="{{array_shift($temp)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;"
                                        for="date_of_calibrations_{{ $calibration->id }}">Date of Calibration <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control date-of-calibration"
                                            id="date_of_calibrations_{{ $calibration->id }}" style="font-size: 13px;"
                                            data-route="{{ route('calibration.user.calculate.duedate', $calibration->id) }}"
                                            data-item-id="{{ $calibration->id }}"
                                            name="date_of_calibrations[]">
                                        <div class="label-invalid col error-date_of_calibrations_{{ $calibration->id }}"></div>
                                    </div>
                                </div>
                                <!-- Next Due Date -->
                                <div class="form-group col-sm-3" data-index="{{array_shift($temp)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="next_due_dates_{{ $calibration->id }}">Next Due Date <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control"
                                            data-item-id="{{ $calibration->id }}"
                                            id="next_due_dates_{{ $calibration->id }}" style="font-size: 13px;"
                                            name="next_due_dates[]">
                                        <div class="label-invalid col error-next_due_dates_{{ $calibration->id }}"></div>
                                    </div>
                                </div>
                                @php
                                    $data = App\CalibrationExternal::where('instrument_id', $calibration->id)->first();
                                    Log::info("Data: ".json_encode($data,JSON_PRETTY_PRINT));
                                @endphp
                                @if(isset(($data)) && $data->count() > 0)
                                    @foreach($customFields as $customField)
                                        @php
                                            $fieldName = Str::slug($customField->field_name, '_');
                                            $value = $data->{$fieldName} ?? null;
                                            if(in_array($customField->field_type, ['checkbox', 'multiselect'])) {
                                                $value = is_array($value) ? $value : json_decode($value, true);
                                            }
                                            $options = App\DropdownOption::where('is_deleted',false)->where('custom_field_id',$customField->id)->pluck('option','id')->toArray();
                                        @endphp
                                        @switch($customField->field_type)
                                            @case('text')
                                            @case('number')
                                            @case('email')
                                            @case('date')
                                            @case('datetime-local')
                                            @case('time')
                                            @case('month')
                                            @case('week')
                                            @case('password')
                                            @case('file')
                                            @case('file_multiple')
                                                <div class="form-group col-sm-3" data-index="{{array_shift($temp)}}">
                                                    <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                                                    <div class="col-sm-12">
                                                        <input type="{{$customField->field_type=='file_multiple' ? 'file':$customField->field_type}}"
                                                            name="{{$customField->field_type=='file_multiple' ? $fieldName.$count.'[]':$fieldName.$count}}" id="{{$fieldName.$count}}"
                                                            placeholder="{{$customField->field_placeholder}}" class="form-control" {{$customField->mandatory==0?'':'required'}}
                                                            style="font-size: 13px;width:100%;" value="{{$value}}" {{$customField->field_type=='file_multiple' ? 'multiple':''}}>
                                                        <div class="label-invalid col error-{{$customField->field_name}}_{{ $calibration->id }}"></div>
                                                    </div>
                                                </div>
                                                @break
                                            @case('textarea')
                                                <div class="form-group col-sm-3" data-index="{{array_shift($temp)}}">
                                                    <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                                                    <div class="col-sm-12">
                                                        <textarea name="{{$fieldName.$count}}" id="{{$fieldName.$count}}" placeholder="{{$customField->field_placeholder}}" class="form-control" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;">{{$value}}</textarea>
                                                        <div class="label-invalid col error-{{$customField->field_name}}_{{ $calibration->id }}"></div>
                                                    </div>
                                                </div>
                                                @break
                                            @case('select')
                                                <div class="form-group col-sm-3" data-index="{{array_shift($temp)}}">
                                                    <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                                                    <div class="col-sm-12">
                                                        <select class="form-control custom_field_select" name="{{$fieldName.$count}}" id="{{$fieldName.$count}}" {{$customField->mandatory==0?'':'required'}} style="font-size: 13px;width:100%;" data-placeholder="{{$customField->field_placeholder?? 'Choose an option'}}">
                                                            @if($customField->field_placeholder==null)
                                                            <option value="" selected disabled>Choose a option</option>
                                                            @endif
                                                            @if($customField->field_placeholder)
                                                            <option value="" selected disabled>{{$customField->field_placeholder}}</option>
                                                            @endif
                                                            @if(count($options)>0)
                                                            @foreach($options as $key => $option)
                                                                <option value="{{$key}}" {{$key==$value ? 'selected' : ''}}>{{$option}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                @break
                                            @case('multiselect')
                                                <div class="form-group col-sm-3" data-index="{{array_shift($temp)}}">
                                                    <label class="col-sm-12 col-form-label" for="{{$customField->field_name}}" style="font-size: 13px;">{{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span></label>
                                                    <div class="col-sm-12">
                                                        <select class="form-control custom_field_select" name="{{$fieldName.$count.'[]'}}" id="{{$fieldName.$count}}" {{$customField->mandatory==0?'':'required'}} multiple style="font-size: 13px;width:100%;" data-placeholder="{{$customField->field_placeholder?? 'Choose an option'}}">
                                                            @if(count($options)>0)
                                                            @foreach($options as $key => $option)
                                                                <option value="{{$key}}" {{in_array($key, $value??[]) ? 'selected' : ''}}>{{$option}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                        <div class="label-invalid col error-{{$customField->field_name}}_{{ $calibration->id }}"></div>
                                                    </div>
                                                </div>
                                                @break
                                            @case('checkbox')
                                                <div class="form-group col-sm-3" data-index="{{array_shift($temp)}}">
                                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">
                                                        {{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span>
                                                    </label>
                                                    <div class="col-sm-12" style="font-size: 13px;">
                                                        @if(count($options) > 0)
                                                            @foreach($options as $key => $option)
                                                                @php
                                                                    $isChecked = is_array($value) ? in_array($key, $value) : ($value == $key);
                                                                @endphp
                                                                <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                                                    <input
                                                                        class="form-check-input"
                                                                        type="checkbox"
                                                                        name="{{$fieldName.$count}}[]"
                                                                        id="{{$fieldName}}_{{Str::slug($option, '_')}}{{$count}}"
                                                                        value="{{$key}}"
                                                                        {{$isChecked ? 'checked' : ''}}
                                                                        {{$customField->mandatory==0?'':'required'}}
                                                                    >
                                                                    <label class="form-check-label">
                                                                        {{$option}}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                @break
                                            @case('radio')
                                                <div class="form-group col-sm-3" data-index="{{array_shift($temp)}}">
                                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">
                                                        {{$customField->field_name}} <span class="text-danger">{{$customField->mandatory==0?'':'*'}}</span>
                                                    </label>
                                                    <div class="col-sm-12" style="font-size: 13px;">
                                                        @if(count($options) > 0)
                                                            @foreach($options as $key => $option)
                                                                @php
                                                                    $isChecked = $value == $key;
                                                                @endphp
                                                                <div class="form-check" style="display: inline-block; margin-right: 10px;">
                                                                    <input
                                                                        class="form-check-input"
                                                                        type="radio"
                                                                        name="{{$fieldName.$count}}"
                                                                        id="{{$fieldName}}_{{Str::slug($option, '_')}}{{$count}}"
                                                                        value="{{$key}}"
                                                                        {{$isChecked ? 'checked' : ''}}
                                                                        {{$customField->mandatory==0?'':'required'}}
                                                                    >
                                                                    <label class="form-check-label" for="{{$fieldName}}_{{Str::slug($option, '_')}}">
                                                                        {{$option}}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                @break
                                        @endswitch
                                    @endforeach
                                @endif
                            </div>
                            <hr style="border-style: dashed; border-color: grey">
                            @php $count++; @endphp
                        @endforeach
                        <!-- Save button -->
                        <div style="text-align:center;">
                            <button type="submit" class="btn btn-primary js-update-status-by-due-date"
                                    data-route="{{ route('calibration.user.update.update.by.duedate') }}"
                                    style="font-size:13px;margin-left:79.5%;">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(function () {
            $('.custom_field_select').select2();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            const getInputValue = (input) => {
                let arr = [];
                $(input).each(function() {
                    arr.push($(this).val());
                });
                return arr;
            }
            const displayMessageWhenSendToReviewer = (completedEmailList = {}, isFinishProcessing = false) => {
            let msg = '';
            $.each( completedEmailList, function( email, status ) {
                msg += '<li>' + email + ': ' + status + '</li>';
            });
            if(isFinishProcessing) {
                $('.flash-msg-of-reviewer').html('<div class="flash_message alert-primary px-2 py-2">' +
                '<span class="fas fa-info"></span> Sending email to calibration reviewer</div>' +
                '<ul class="px-5 alert-primary">' + msg + '</ul>');
                let countdown = 5;
                const countdownInterval = setInterval(function() {
                $('.flash-msg-on-modal').html('<div class="flash_message alert-success text-center py-2 my-2">' +
                    '<span class="fas fa-check-circle"></span> Change the status successfully. You will be moved to the calibration management screen after ' + countdown + ' seconds</div>')
                countdown--;
                if (countdown < 0) {
                    clearInterval(countdownInterval);
                    window.location = '/user/calibrationmgmt?tab=ext'
                }
                }, 1000);
            } else {
                msg += '<li>Processing...</li>';
                $('.flash-msg-of-reviewer').html('<div class="flash_message alert-primary px-2 py-2">' +
                '<span class="fas fa-info"></span> Sending email to calibration reviewer</div>' +
                '<ul class="px-5 alert-primary">' + msg + '</ul>');
            }
            }
            $('body').on('change', '.date-of-calibration', function () {
            const url = $(this).attr('data-route');
            const dateOfCalibration = $(this).val();
            const calibrationId = $(this).attr('data-item-id');

            $.ajax({
                url,
                data: {
                'date_of_calibration': dateOfCalibration,
                },
                type: 'POST',
                success: function (data) {
                if (data.success) {
                    $('#next_due_dates_' + calibrationId).val(data.due_date);
                }
                },
                error: function (response) {

                }
            })
            });
            $('form').on('submit', function (e) {
                e.preventDefault();
                const form = this; // Get the raw form DOM element
                const formData = new FormData(form); // Collect all inputs including files
                const url = $('.js-update-status-by-due-date').attr('data-route');
                const targetIds = getInputValue("input[name='target_ids[]']");
                const certificateNumbers = getInputValue("input[name='certificate_numbers[]']")
                const dateOfCalibrations = getInputValue("input[name='date_of_calibrations[]']");
                const nextDueDates = getInputValue("input[name='next_due_dates[]']");
                targetIds.forEach(id => {
                    formData.append('target_ids[]', id);
                });

                // Append certificate_numbers[]
                certificateNumbers.forEach(cert => {
                    formData.append('certificate_numbers[]', cert);
                });

                // Append date_of_calibrations[]
                dateOfCalibrations.forEach(date => {
                    formData.append('date_of_calibrations[]', date);
                });

                // Append next_due_dates[]
                nextDueDates.forEach(due => {
                    formData.append('next_due_dates[]', due);
                });

                $('.label-invalid').html("");
                $('input[type=text]').removeClass('is-invalid');
                $('input[type=date]').removeClass('is-invalid');
                $.ajax({
                    url,
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                    $('.flash-msg-on-modal').html('<div class="flash_message alert-primary text-center py-2 my-2">' +
                        '<span class="fas fa-info"></span> Processing.....</div>')
                    },
                    success: function (data) {
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    if (data.success) {
                        const targetIds = data.target_ids;
                        const calibrationReviewers = data.calibration_reviewer_emails;
                        $('.flash-msg-on-modal').html('<div class="flash_message alert-primary text-center py-2 my-2">' +
                        '<span class="fas fa-info"></span> Change the status successfully.</div>')
                        $('.flash-msg-of-reviewer').html('<div class="flash_message alert-primary px-2 py-2">' +
                        '<span class="fas fa-info"></span> Sending email to calibration reviewer </div><ul class="px-5 alert-primary"><li>Processing...</li></ul>');
                        const calibrationReviewerEmailList = calibrationReviewers.split(',');
                        let emailDone = {};
                        for (let i = 0; i < calibrationReviewerEmailList.length; ++i) {
                        const email = calibrationReviewerEmailList[i];
                        $.ajax({
                            url: '{{ route('calibration.user.send.mail.to.reviewer') }}',
                            data: {
                            'email': email,
                            'target_ids': targetIds,
                            },
                            type: 'POST',
                            success: function (data) {
                            if (data.success) {
                                emailDone[email] = 'Done';
                                displayMessageWhenSendToReviewer(emailDone, i == calibrationReviewerEmailList.length - 1);
                            } else {
                                emailDone[email] = 'Failed';
                                displayMessageWhenSendToReviewer(emailDone, i == calibrationReviewerEmailList.length - 1);
                            }
                            },
                            error: function (response) {
                            emailDone[email] = 'Failed';
                            displayMessageWhenSendToReviewer(emailDone, i == calibrationReviewerEmailList.length - 1);
                            }
                        })
                        }
                    } else {
                        $('.flash-msg-on-modal').html('<div class="flash_message alert-danger text-center py-2 my-2">' +
                        '<span class="fas fa-exclamation-triangle"></span> Can not display the form to change the status</div>')
                    }
                    },
                    error: function (response) {
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    $('.flash-msg-on-modal').html('<div class="flash_message alert-danger text-center py-2 my-2">' +
                        '<span class="fas fa-exclamation-triangle"></span> Some errors in the data input</div>')
                    const status = response.status;
                    const data = response.responseJSON;
                    if (status === 422) {
                        $.each(data.errors, function (field, object) {
                        const fieldCustom = field.replace(".", "_");
                        const errorString = object.join(',')
                        $('.error-' + fieldCustom).html(errorString)
                        $('#' + fieldCustom).addClass('is-invalid')
                        })
                    } else {
                        $('.flash-msg-on-modal').html('<div class="flash_message alert-danger text-center py-2 my-2">' +
                        '<span class="fas fa-exclamation-triangle"></span> Something is wrong</div>')
                    }
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var savedOrder = @json($defaultFieldIndex);

            $('.sortable-field').each(function () {
                var $container = $(this);
                var $allFields = $container.children('.form-group');

                var fieldMap = {};
                $allFields.each(function () {
                    var index = $(this).data('index');
                    if (index !== undefined) {
                        fieldMap[index] = $(this);
                    }
                });

                // Remove current fields
                $allFields.detach();

                // Append in saved order
                if (Array.isArray(savedOrder)) {
                    savedOrder.forEach(function (index) {
                        if (fieldMap[index]) {
                            $container.append(fieldMap[index]);
                            delete fieldMap[index];
                        }
                    });
                }

                // Append any leftover fields not in savedOrder
                $.each(fieldMap, function (index, $el) {
                    $container.append($el);
                });
            });
        });

    </script>
</div>
    <!-- Page Content -->
@endsection
