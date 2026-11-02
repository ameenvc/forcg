@extends('layouts.multiselect')
@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Objectives</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Approve Objectives</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{$type}}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        @if(count($errors) >0 )
        @foreach($errors->all() as $error)
        <div class='alert alert-warning'>
            {{ $error }}
        </div>
        @endforeach
        @endif
        @if(Session::has('msg'))
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
        @endif

        <!-- Horizontal -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div class="row">

                    <div class="col-lg-9">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{url('/user/objectives/approve/'. $type)}}" enctype="multipart/form-data"  method="POST" onsubmit="return validation()">
                            {{csrf_field()}}
                            @if(count($data)>0)
                            @foreach($data as $dat)

                            <div class="row">
                                <div class="form-group col-sm-6" >
                                    <label class="col-sm-12 col-form-label" for="objid" style="font-size: 13px;">Objectives ID</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="objid" style="font-size: 13px;" name="objid" readonly value="{{$dat->objective_id}}">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Department</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="department" name="department" style="font-size: 13px;" readonly value="{{$dat->dep ? $dat->dep->departmentname : '-'}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if($type== 'divisional' || $type == 'subdivisional')
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Division</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" readonly value="{{$dat->division ? $dat->division->name : ''}}">
                                    </div>
                                </div>
                                @endif
                                @if($type == 'subdivisional')
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Sub-Division</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" readonly value="{{$dat->area ? $dat->area->name : ''}}">
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="objective_type">Objective type</label>
                                    <div class="col-sm-12">
                                        <select class="form-control js-select2" id="objective_type" name="objective_type[]" style="font-size: 13px;width:100%;" data-placeholder="Choose an Objective Type" multiple>
                                            @if(count($objective_types)> 0 )
                                            @foreach($objective_types as $item)
                                            <option value="{{$item->id}}" style="font-size: 13px;" {{ in_array($item->id, $dat->objective_type_id) ? 'selected' : '' }}> {{ $item->name }}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Objective name</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="objectives" style="font-size: 13px;" name="objectives" value="{{$dat->objectives}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskdri" style="font-size: 13px;">Targets</label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="number" name="target" id="otarget" step="any"  style="font-size: 13px;" value="{{$dat->otarget}}" required>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="riskdri" style="font-size: 13px;">Objectives description</label>
                                    <div class="col-sm-12">
                                        <textarea class="form-control" type="text" name="description" id="description" style="font-size: 13px;">{{$dat->description}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="hod" style="font-size: 13px;">Unit</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="unit" id="hod_method" style="font-size: 13px;width:100%;">

                                            @if($dat->unit=='%')
                                            <!-- <option value=" "></option> -->
                                            <option value="{{$dat->unit}}">{{$dat->unit}}</option>
                                            <option value="No sign">No sign</option>
                                            <option value="4">Other</option>

                                            @elseif($dat->unit=="No sign")
                                            <!-- <option value=" "></option> -->
                                            <option value="{{$dat->unit}}">{{$dat->unit}}</option>
                                            <option value="%">%</option>
                                            <option value="4">Other</option>

                                            @else
                                            <!-- <option value=" "></option> -->
                                            <option value="{{$dat->unit}}">{{$dat->unit}}</option>
                                            <option value="%">%</option>
                                            <option value="No sign">No sign</option>
                                            <option value="4">Other</option>

                                            @endif
                                        </select>

                                    </div>
                                    <div class="col-sm-12" id="otherunit" name="otherunit">
                                    <br>
                                        @if($dat->unit=="%" || $dat->unit=="No sign")
                                        <input type="text" class="form-control" name="otherunit" style="font-size: 13px;" value="">
                                        @elseif($dat->unit!="%" || $dat->unit!="No sign")
                                        <input type="text" class="form-control" name="otherunit" style="font-size: 13px;" value="{{$dat->unit}}">
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="nature">Objective Nature</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="nature" style="font-size: 13px;width:100%;" id="nature">
                                            <option value="0" {{ $dat->target_nature == null ? 'selected' : '' }}>Choose Target nature</option>
                                            <option value="increase" {{ $dat->target_nature == 'increase' ? 'selected' : '' }}>Increase</option>
                                            <option value="decrease" {{ $dat->target_nature == 'decrease' ? 'selected' : '' }}>Decrease</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="hod" style="font-size: 13px;">Program span</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="span" id="span" style="font-size: 13px;width:100%;">
                                            <option value="{{$dat->span}}">{{$dat->span}}</option>

                                            <option value="Short">Short</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Long">Long</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="baseline">Baseline</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="text" step="any" style="font-size: 13px;"
                                            name="baseline" id="baseline" value="{{$dat->baseline}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Methodology</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" id="methodology" name="methodology" style="font-size: 13px;">{{$dat->methodology}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Resources</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" id="resources" name="resources" style="font-size: 13px;">{{$dat->resources}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;width:100%;">Responsibility</label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" name="responsibility[]" required="true" style="font-size:13px;" data-placeholder="Choose Responsible Persons" multiple>
                                         @foreach($user as $value)
                                            <option value="{{$value->email}}" style="font-size:13px;" {{ in_array($value->name, explode(',', $dat->responsibility)) || in_array($value->email, explode(',', $dat->responsibility)) ? 'selected' : ''}}> {{$value->name}}</option>
                                        @endforeach
                                            {{-- <option value=" {{$dat->responsibility}}"> {{$resname}}</option><br> --}}
                                            {{-- @if(count($user)>0)
                                            @foreach($user as $u) --}}
                                            {{-- @if($dat->responsibility != $u->email) --}}
                                            {{-- <option value=" {{$u->email}}" style="font-size:13px;" {{ $u->email == $dat->responsibility ? 'selected' : ''}}> {{$u->name}}</option> --}}
                                            {{-- @endif --}}
                                            {{-- @endforeach
                                            @endif --}}
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label" for="frequency" style="font-size: 13px;">Frequency of monitoring</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="frequency" id="frequency" style="font-size: 13px;">
                                            <option value="{{$dat->frequency}}"> {{ $dat->frequency}}</option><br>
                                            @if($dat->frequency != "Quarterly")
                                            <option value="Quarterly">Quarterly</option>
                                            @endif
                                            @if($dat->frequency != "Half yearly")
                                            <option value="Half yearly">Half yearly</option>
                                            @endif
                                            @if($dat->frequency != "Yearly")
                                            <option value="Yearly">Yearly</option>
                                            @endif
                                        </select>
                                    </div>
                                </div> -->

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="frequency" style="font-size: 13px;">Frequency
                                        of monitoring <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="frequency" id="frequency" style="font-size: 13px;width:100%;" required>
                                            <option value="">Choose Frequency</option>
                                            <option value="Monthly" {{ $dat->frequency == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="Quarterly" {{ $dat->frequency == 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                                            <option value="Half yearly" {{ $dat->frequency == 'Half yearly' ? 'selected' : '' }}>Half yearly</option>
                                            <option value="Yearly" {{ $dat->frequency == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Target Date</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="Date" style="font-size: 13px;" name="tardate" value="{{$dat->tardate}}">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" id="a" for="Attachments">Attachments</label>
                                    <div class="col-sm-6">
                                        <a data-toggle="modal"  data-target="#viewModal5{{$dat->Oid}}"  data-id="{{ $dat->Oid }}" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;"><strong>View Attachment</strong></a>
                                        <div id="viewModal5{{$dat->Oid}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Attachment</h3>
                                                        <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>
                                                    </div>
                                                    <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                        <div class="row justify-content-center">

                                                            <?php $o=0; ?>
                                                            @if($dat->files!='')
                                                                <thead>
                                                                    <tr class="table-secondary">
                                                                        <th>S.No</th>
                                                                        <th>Attachment</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php $files = explode(',',$dat->files); @endphp
                                                                    @foreach($files as $f)
                                                                    <tr>
                                                                        <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                                        <td style="font-size:15px;width: 100%;">{{ preg_replace('/_\d+_[^.]+(\.[^.]+)$/', '$1', $f)}}</td>
                                                                        <td>
                                                                            <a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset('storage/objectives_files/'.trim($f))}}" download="{{trim($f)}}">Download</a>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            @else
                                                            <div class="row justify-content-center">
                                                                <h2>No Files</h2>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <input class="col-form-label" style="font-size:12px;" type="file" name="document[]" id="document" multiple placeholder="Add New Attachments">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="question">Approval</label>
                                    <div class="col-sm-12">
                                        <select name="question" id="question" style="font-size: 13px;width:100%;" class="form-control " required>
                                            <option value="none" selected disabled hidden>
                                                Select an Option
                                            </option>
                                            <option value="2">Approved</option>
                                            <option value="1">Not Approved, Need Improvement</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" id="a" style="font-size: 13px;" for="higherremarks">Approver remarks</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="higherremarks" style="font-size: 13px;" id="higherremarks" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" id="b" for="higherproposal">Approver proposal</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="higherproposal" style="font-size: 13px;" id="higherproposal" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="posted" value="{{ $dat->Oid}}">
                            @endforeach
                            @endif
                            <div class="row">
                                <div class="col-sm-12 form-group text-right mr-auto">
                                    <button type="submit" class="btn btn-primary" style="font-size: 13px;" onclick="return confirm('Do you want to proceed?')">Submit</button>
                                </div>
                            </div>
                        </form>

                        <!-- END Form Horizontal - Default Style -->

                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->



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
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{Session::get('year')}}">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                <br> Register
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/achieved">
                                        <div class="block-content text-center" style="height:130px">

                                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                <br> Achieved<br>
                                            </p>
                                        </div>
                                    </a>

                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/notachieved">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-times-circle fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Not Achieved
                                            </p>
                                        </div>
                                    </a>

                                </div>


                                <div class="col-6">
                                    <!-- Sales -->
                                    <br>
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/objreport" style="margin-top:-18px">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Objective report
                                            </p>
                                        </div>
                                    </a>

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
    $(document).ready(function() {
        $("#otherunit").hide();

    });

</script>
<script>
    $("#hod_method").change(function() {
        console.log($("#hod_method option:selected").val());
        if ($("#hod_method option:selected").val() == "4") {
            //   $('#shipping_carrier').prop('disabled', 'disabled');
            //   $('#shipping_carrier').val(1);
            $('#risk_response').show();
            $('#otherunit').show();
        }

        //    else if ($("#hod_method option:selected").val() == 3) {
        //    //   $('#shipping_carrier').prop('disabled', 'disabled');
        //    //   $('#shipping_carrier').val(1);
        //    $('#risk_response').hide();
        //      $('#otherunit').hide();
        //    }
        else {
            //   $('#shipping_carrier').prop('disabled', false);
            $('#risk_response').hide();
            $('#otherunit').hide();
        }
    });

</script>
<script>
    function validation() {


        if (document.getElementById('question').value == 'none') {
            alert('Please select an approval option');
            document.getElementById('question').focus();
            return false;
        }



        // always return false to prevent form from submission.
    }

</script>

@endsection
