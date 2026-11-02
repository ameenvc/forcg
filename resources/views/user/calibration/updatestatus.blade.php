@extends('layouts.multiselect')
@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"  style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Calibration Managemnt</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Update details</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/calibrationmgmt?tab={{ request()->get('tab') }}&rowId={{request()->get('val')}}">Back</a>
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
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" action="{{url('/user/calibration/updatestatus')}}" onsubmit="return validation()" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        @if(count($errors) >0 )
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
                        <legend style="font-size:18px"><b>Instruments Information</b></legend><br>
                        <input type="hidden" name="tab" value="{{ request()->get('tab') }}">
                        <input type="hidden" name="instid" value="{{ request()->get('val') }}">
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
                        @php
                            $calibrationFieldRules = json_decode($calibration->calibration_field_rules, true);
                        @endphp
                        <div class="row sortable-field">
                            @if ($calibrationFieldRules['Instrument_no']['enable_field'])
                                <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" for="riskname" style="font-size: 13px;">{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_no']['field_display_label'] : 'Instrument number'}}</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" readonly  style="font-size: 13px;" value= "{{ $calibration->Instrument_no }}"  id="instno" name="instno">
                                    </div>
                                </div>
                            @endif
                            <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Date of calibration <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control date-of-calibration"
                                           data-route="{{ route('calibration.user.calculate.duedate', $calibration->id) }}"
                                           id="dateofcal" style="font-size: 13px;" name="dateofcal"
                                           required placeholder="Enter instrument description"></textarea>
                                </div>
                            </div>
                            <?php
                                $c=date('my');
                                $d = DB::select('select * from calibration_external');
                                if(!$d)
                                {
                                    $idd=1;
                                }
                                elseif($d)
                                {

                                    $id=DB::table('calibration_external')->select('id')->orderby('id', 'DESC')->first()->id;
                                    $idd=$id+1;
                                }
                            ?>
                            <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Certificate number</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="certino" style="font-size: 13px;" name="certino"  value="<?php echo $idd.$c; ?>">
                                </div>
                            </div>
                            <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label"for="doctype"style="font-size: 13px;">Next due date</label>
                                <div class="col-sm-12">
                                    <input type="date" style="font-size: 13px;"class="form-control" id="duedate" name="duedate" >
                                </div>
                            </div>
                            {!!$externalCustomFields!!}
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-right mr-2">
                                <button type="submit" class="btn btn-primary"  style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed?')">Submit</button>
                                <button for="reset" type="reset" class="btn btn-primary"style="font-size:13px;">Reset</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Form Horizontal - Default Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->
</div>
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        var savedOrder = @json($defaultFieldIndex);
        var $container = $('.sortable-field');
        var $allFields = $container.children('.form-group');
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
    });
</script>
<script>
    $(document).ready(function(){
        $("#externalfiles").hide();
        $("#agen").hide();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('body').on('change', '.date-of-calibration', function () {
            const url = $(this).attr('data-route');
            const dateOfCalibration = $(this).val();

            $.ajax({
                url,
                data: {
                    'date_of_calibration': dateOfCalibration,
                },
                type: 'POST',
                success: function (data) {
                    if (data.success) {
                        $('#duedate').val(data.due_date);
                    }
                },
                error: function (response) {

                }
            })
        })
    });
</script>
<script>
    $("#insttype").change(function() {
        if ($("#insttype option:selected").val() == "Internal") {
        //   $('#shipping_carrier').prop('disabled', 'disabled');
        //   $('#shipping_carrier').val(1);
        $('#risk_response').hide();
            $('#externalfiles').hide();
            $("#agen").hide();
        }else if ($("#insttype option:selected").val() == "External") {
        //   $('#shipping_carrier').prop('disabled', 'disabled');
        //   $('#shipping_carrier').val(1);
        $('#risk_response').show();
            $('#externalfiles').show();
            $("#agen").show();
        }else {
        //   $('#shipping_carrier').prop('disabled', false);
        $('#risk_response').show();
            $('#otherunit').show();
        }
    });
</script>
<script>
    $("#hod_method").change(function() {
        if ($("#hod_method option:selected").val() == 2) {
        //   $('#shipping_carrier').prop('disabled', 'disabled');
        //   $('#shipping_carrier').val(1);

            $('#otherunit').hide();
        }else if ($("#hod_method option:selected").val() == 3) {
        //   $('#shipping_carrier').prop('disabled', 'disabled');
        //   $('#shipping_carrier').val(1);

            $('#otherunit').hide();
        }else {
        //   $('#shipping_carrier').prop('disabled', false);
        $('#risk_response').show();
            $('#otherunit').show();
        }
    });

</script>
<script>
    function validation() {
        if(document.getElementById('department').value == 'none'){
            alert('Choose a Department');
            document.getElementById('department').focus();
            return false;
        }else if(document.getElementById('span').value == 'none'){
            alert('Choose Program Span');
            document.getElementById('span').focus();
            return false;
        }else if(document.getElementById('responsibility').value == 'none'){
            alert('Choose Responsibility');
            document.getElementById('responsibility').focus();
            return false;
        }else if(document.getElementById('frequency').value == 'none'){
            alert('Choose Frequency');
            document.getElementById('frequency').focus();
            return false;
        }
    // always return false to prevent form from submission.
    }
</script>
@endsection
