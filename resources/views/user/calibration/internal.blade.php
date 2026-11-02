@extends('layouts.multiselect')
@section('content')
<style>
    body{
        overflow-x: hidden;
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
                        <a class="link-fx" href="">Add Report Details</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="{{ url('/user/calibrationmgmt?rowId='.request()->get('val')) }}">Back</a>
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
            <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <form  action="{{url('user/calibration/addInternalReport')}}" onsubmit="return validation()" method="POST" enctype="multipart/form-data">
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
                        <div class="row sortable-field">
                            @if(count($data)>0)
                            <div class="table-responsive form-group col-sm-12" data-index="{{array_shift($internalFieldIndex)}}">
                                <table class="table table-bordered table-striped table-vcenter">
                                    <thead style="background-color:#FFFFFF">
                                        @php
                                            $calibrationFieldRules = json_decode($data[0]->calibration_field_rules, true);
                                        @endphp
                                        <tr>
                                            <th class="text-center" style="font-size:13px;width: 20px;text-transform:capitalize">{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_no']['field_display_label'] : 'Instrument number'}}</th>
                                            <th class="text-center" style="font-size:13px;width: 30px;text-transform:capitalize">{{ $calibrationFieldRules ? $calibrationFieldRules['Instrument_desc']['field_display_label'] : 'Description'}}</th>
                                            <th class="text-center" style="font-size:13px;width: 30px;text-transform:capitalize">{{ $calibrationFieldRules ? $calibrationFieldRules['Calib_procedure']['field_display_label'] : 'Calibration Procedure'}}</th>
                                            <th class="text-center" style="font-size:13px;width: 100px;text-transform:capitalize">Due Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $dat1)
                                        <tr>
                                            <input  type="hidden" id="id" name="id" value="{{$dat1->id}}">
                                            <td class="text-center "style="font-size:13px">
                                                <input  type="text" style="font-size: 13px;"   class="form-control" id="Instrument_no"
                                                name="Instrument_no" readonly  value="{{$dat1->Instrument_no}}">
                                            </td>
                                            <td class="text-center "style="font-size:13px;">
                                                <!-- <input  type="text" style="font-size: 13px;"   class="form-control" id="Instrument_desc" name="Instrument_desc" readonly  value="{{$dat1->Instrument_desc}}">  -->
                                                <textarea style="font-size: 13px;" rows="2" cols="2"  class="form-control" id="Instrument_desc"
                                                name="Instrument_desc" readonly >{{$dat1->Instrument_desc}}</textarea>
                                            </td>
                                            <td class="text-center "style="font-size:13px;">
                                                <!-- <input  type="text" style="font-size: 13px;"   class="form-control" id="Calib_procedure" name="Calib_procedure" readonly  value="{{$dat1->Calib_procedure==''?'--':$dat1->Calib_procedure}}">  -->
                                                <textarea style="font-size: 13px;"   class="form-control" rows="2" cols="2" id="Calib_procedure"
                                                name="Calib_procedure" readonly>{{$dat1->Calib_procedure==''?'--':$dat1->Calib_procedure}}</textarea>
                                            </td>
                                            <td class="text-center "style="font-size:13px;">
                                                <input  type="text" style="font-size: 13px;"   class="form-control" id="duedate"
                                                name="duedate"  readonly value="{{$dat1->Duedate==''?'--':date('d-m-Y',strtotime($dat1->Duedate))}}">
                                            </td>
                                            <input type="hidden" name="sno[]" value="{{ $dat1->id}}">
                                            <input type="hidden" name="instrid" value="{{ $dat1->id}}">
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="riskname" style="font-size: 13px;">Acceptable error <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="ins_accerror" style="font-size: 13px;" name="ins_accerror" required placeholder="Enter acceptable error">
                                </div>
                            </div>
                            <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="riskdri" style="font-size: 13px;">Master instrument certificate number <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="ins_certificateno" style="font-size: 13px;"  placeholder="Enter master instrument certificate number" required >
                                </div>
                            </div>
                            <div class="form-group col-sm-12" data-index="{{array_shift($internalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Master instrument  <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="ins_master" style="font-size: 13px;" name="ins_master"  required placeholder="Enter master instrument"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-12" data-index="{{array_shift($internalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label"style="font-size: 13px;" for="riskname">Master reading, Instrument reading & Error <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <table style="border:none;" id="dynamic">
                                        <tr>
                                            <td>
                                                <select name="decimal" id="decimal" style="font-size: 13px;margin-bottom:15px;font-weight:bold;" onchange="getdecimal(this)">
                                                    <option><span class="form-label">Select Decimal Points</span></option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <input type="text"style="font-size: 13px;width:165px;" class="form-control"  id="doc[0][ins_mreading]"  name="doc[0][ins_mreading]" onchange="getmasterval(this,0)" placeholder="Enter master reading" required>
                                            </td>
                                            <td>
                                                <input type="text"style="font-size: 13px;width:165px;" class="form-control" id="doc[0][ins_reading]" name="doc[0][ins_reading]" onchange="getmasterval1(this,0)" placeholder="Enter instrument reading" required>
                                            </td>
                                            <td>
                                                <input type="text"style="font-size: 13px;width:165px;" class="form-control" id="doc[0][ins_error]" name="doc[0][ins_error]" placeholder="Enter error" required>
                                            </td>
                                            <td><button type="button" style="font-size:12px;"name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add">
                                                <i class="fa fa-fw fa-plus"></i>
                                                </button>

                                            </td>
                                        </tr>


                                    </table>
                                </div>
                            </div>
                            <?php $c=date('my'); ?>

                            <?php
                                if(!$id){
                                    $idd=1;
                                }elseif($id){
                                    $id=DB::table('calibration_internal')->select('id')->orderby('id', 'DESC')->first()->id;
                                    $idd=$id+1;
                                }
                            ?>
                            <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Report Number</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="ins_report" value=<?php echo $idd.$c?> required style="font-size: 13px;" name="ins_report" placeholder="Enter report number" readonly >
                                </div>
                            </div>
                            <?php $n=Auth::user()->name;  ?>
                            <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Calibrated by</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="ins_calby" value=<?php echo $n?> required style="font-size: 13px;" name="ins_calby" readonly>
                                </div>
                            </div>

                            <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="ins_verby" style="font-size:13px;" >Verified by <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="example-select2-multiple" required style="font-size:13px;width:100%;" name="ins_verby[]"  multiple>
                                        <option></option>
                                        @if(count($verifiers)> 0 )
                                            @foreach($verifiers as $v)
                                                <option value=" {{$v->email}}" style="font-size:13px;" > {{ $v->name }}</option><br>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Date of calibration <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" id="ins_date" required style="font-size: 13px;" name="ins_date" >
                                </div>
                            </div>

                            <div class="form-group col-sm-12" data-index="{{array_shift($internalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Adjustment made <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="ins_adj" required style="font-size: 13px;" name="ins_adj" placeholder="Enter adjustment"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-12" data-index="{{array_shift($internalFieldIndex)}}">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Remarks</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="ins_remarks"  style="font-size: 13px;" name="ins_remarks" placeholder="Enter remarks"></textarea>
                                </div>
                            </div>
                            {!!$internalCustomFields!!}
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group text-right mr-2">
                                <input type="hidden" name="freq" value="{{ $dat1->Frequency}}">
                                <button type="submit" class="btn btn-primary"  style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed?')">Submit</button>
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
    $(document).ready(function(){
        $("#otherunit").hide();
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
<!-- script for plus button of emp name and position -->
<script type="text/javascript">
    var i = 0;
    $("#add").click(function(){
        ++i;
         $("#dynamic").append('<tr> <td><input type="text"style="font-size: 13px;width:165px;" class="form-control" name="doc['+i+'][ins_mreading]" id="doc['+i+'][ins_mreading]" onchange=getmasterval(this,'+i+') placeholder="Enter master reading"></td><td><input type="text"style="font-size: 13px;width:165px;" class="form-control" name="doc['+i+'][ins_reading]" id="doc['+i+'][ins_reading]"  onchange=getmasterval1(this,'+i+') placeholder="Enter instrument reading"></td>  <td><input type="text"style="font-size: 13px;width:165px;" class="form-control" name="doc['+i+'][ins_error]" id="doc['+i+'][ins_error]" placeholder="Enter error"></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Employee Name"> <i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');

    });
    $(document).on('click', '.remove-tr', function(){
        $(this).parents('tr').remove();
    });
</script>

<script>
    var master_reading = "ins_mreading";
    var instr_reading = "ins_reading";
    var error_reading = "ins_error";

    function getdecimal(){
        var dec = document.getElementById('decimal').value;
        if(dec=="Select Decimal Points"){
            var dec_val = 3;
        }else{
        var dec_val = dec;
        }
        return dec_val;
    }
    function getmasterval(obj,iter){
        var result1 = obj.value;
        var name = "doc["+iter+"]["+instr_reading+"]";
        document.getElementById(name).value = result1;
        getmasterval1(obj,iter);

    }
    function getmasterval1(obj,iter){
        var result2 = obj.value;
        var decimal = getdecimal();
        var name = "doc["+iter+"]["+error_reading+"]";
        var master = "doc["+iter+"]["+master_reading+"]";
        //var result3 = result2 - document.getElementById(master).value;
        var result3 = document.getElementById(master).value - result2;
        //document.getElementById(name).value = Math.abs(result3.toFixed(decimal));
        document.getElementById(name).value = result3.toFixed(decimal);

    }
</script>
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
@endsection
