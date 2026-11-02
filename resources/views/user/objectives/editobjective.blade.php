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
                        <a class="link-fx" href="">Edit Objectives</a>
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
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('msg')!!}
    </div>
    @endif
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">

        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-9">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" id="updateobjective" enctype="multipart/form-data" action="{{url('/user/objectives/updateobjective/'. $type)}}" onsubmit="return validation()" method="POST">
                        {{csrf_field()}}
                        @foreach($data as $dat1)
                        <input type="hidden" id="id" name="id" value=" {{ $dat1->Oid }} ">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department">Department <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="department" style="font-size: 13px;" name="department" required value="{{$dat1->dep ? $dat1->dep->departmentname : '-'}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="objective_type">Objective type</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="objective_type" name="objective_type[]" style="font-size: 13px;width:100%;" data-placeholder="Choose an Objective Type" multiple>
                                        @if(count($objective_types)> 0 )
                                            @foreach($objective_types as $item)
                                                <option value="{{$item->id}}" style="font-size: 13px;" {{ in_array($item->id, $dat1->objective_type_id) ? 'selected' : '' }}> {{ $item->name }}</option></br>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="objectives" style="font-size: 13px;">Objective name <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="objectives" style="font-size: 13px;" name="objectives" required value="{{$dat1->objectives}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="description" style="font-size: 13px;">Objective description <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="description" style="font-size: 13px;" name="description" required>{{$dat1->description}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="fromdate" style="font-size: 13px;">From <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="Date" name="fromdate" style="font-size: 13px;" required value="{{$dat1->fromdate}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="todate">To <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="Date" style="font-size: 13px;" name="todate" required value="{{$dat1->todate}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="baseline">Baseline</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control"
                                        id="baseline" style="font-size: 13px;"
                                        value="{{ old('baseline', $dat1->baseline) }}"
                                        name="baseline" placeholder="Enter Baseline">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="otarget">Target <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" step="any"  id="otarget" style="font-size: 13px;" required name="otarget" value="{{$dat1->otarget}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="hod">Unit</label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="unit" style="font-size: 13px;width:100%;" id="hod_method">
                                        <option value="{{$dat1->unit}}"> {{ $dat1->unit}}</option><br>
                                        @if($dat1->unit != "%")
                                        <option value="2">%</option>
                                        @endif
                                        @if($dat1->unit != "No sign")
                                        <option value="3">No sign</option>
                                        @endif
                                        @if($dat1->unit != "Other")
                                        <option value="4">Other</option>
                                        @endif

                                    </select>
                                </div>
                                <div class="form-group col-sm-12" id="otherunit" name="otherunit">
                                    <br><input type="text" class="form-control" name="otherunit" style="font-size: 13px;">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="hod">Program span</label>
                                <div class="col-sm-12">
                                    <select class="form-control" style="font-size: 13px;width:100%;" name="span" id="span">
                                        <option value="{{$dat1->span}}"> {{ $dat1->span}}</option><br>
                                        @if($dat1->span != "Short")
                                        <option value="Short">Short</option>
                                        @endif
                                        @if($dat1->span != "Medium")
                                        <option value="Medium">Medium</option>
                                        @endif
                                        @if($dat1->span != "Long")
                                        <option value="Long">Long</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="tardate" style="font-size: 13px;">Target date <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="Date" name="tardate" required style="font-size: 13px;" value="{{$dat1->tardate}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="methodology" style="font-size: 13px;">Methodology <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="methodology" required style="font-size: 13px;" name="methodology">{{$dat1->methodology}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="resources" style="font-size: 13px;">Resources <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="resources" required style="font-size: 13px;" name="resources">{{$dat1->resources}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="responsibility" style="font-size: 13px;">Responsibility</label>
                                <div class="col-sm-12">

                                    <select class="js-select2 form-control" id="responsibility" style="font-size: 13px;width:100%;" name="responsibility[]" data-placeholder="Choose Responsible Persons" multiple>
                                        @if(count($users)> 0 )
                                        @foreach($users as $value)
                                            <option value="{{$value->email}}" style="font-size:13px;" {{ in_array($value->name, explode(',', $dat1->responsibility)) || in_array($value->email, explode(',', $dat1->responsibility)) ? 'selected' : ''}}> {{$value->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size: 13px;">Frequency
                                    of monitoring <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="frequency" id="frequency" style="font-size: 13px;width:100%;" required>
                                        <option value="">Choose Frequency</option>
                                        <option value="Monthly" {{ $dat1->frequency == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="Quarterly" {{ $dat1->frequency == 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="Half yearly" {{ $dat1->frequency == 'Half yearly' ? 'selected' : '' }}>Half yearly</option>
                                        <option value="Yearly" {{ $dat1->frequency == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="reviewer" style="font-size: 13px;">Reviewer <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control selectm select_reviewer" name="reviewers[]" id="reviewers" style="font-size: 13px;width:100%;" multiple required data-placeholder="Choose Reviewers">
                                        @if(count($users)>0)
                                            @foreach($users as $value)
                                                @if(in_array($value->id,$objective_reviewers))
                                                <h1>{{in_array($value->id,$objective_reviewers)}}</h1>
                                                    <option value="{{$value->id}}" {{ in_array($value->id, explode(',',$dat1->reviewers)) ? 'selected' : '' }}> {{$value->name}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="approver" style="font-size: 13px;">Approver <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="approver" style="font-size: 13px;width:100%;" name="approver" required>
                                        <option value="" disabled selected>Choose Approver</option>
                                        @if(count($users)> 0 )
                                        @foreach($users as $value)
                                        @if(in_array($value->id,$objective_approvers))
                                        <option value="{{$value->id}}" {{$dat1->approver==$value->id ? 'selected':''}}> {{ $value->name }}</option>
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="file" class="col-sm-12 col-form-label" style="font-size:13px;">File</label>
                                <div class="col-sm-12">
                                    <input class="form-control" style="font-size:13px;" type="file" name="document[]" id="document" multiple>
                                </div>
                            </div>
                            <div class="form-group col-sm-6" style="margin:auto 0;">
                                <div class="col-sm-12">
                                    <a data-toggle="modal"  data-target="#viewModal"  data-id="{{ $dat1->Oid }}" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;color:#fff;"><strong>View Attachments</strong></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="row">
                            <div class="form-group col-sm-12 text-right">
                                <button for="reset" type="reset" class="btn btn-primary" style="font-size:13px;">Reset</button>
                                <button type="submit" class="btn btn-primary" style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed?')">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Form Horizontal - Default Style -->

                </div>
            </div>
        </div>
    </div>

    <!-- END Horizontal -->

    <div id="viewModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Attachments</h3>
                    <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>
                </div>
                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                    <div class="row justify-content-center">
                        <?php $o=0; ?>
                        @if($dat1->files!='')
                            <thead>
                                <tr class="table-secondary">
                                    <th>S.No</th>
                                    <th>Attachment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $files = explode(',',$dat1->files) @endphp
                                @foreach($files as $f)
                                <tr>
                                    <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                    <td style="font-size:15px;width:70%;">{{ preg_replace('/_\d+_[^.]+(\.[^.]+)$/', '$1', $f) }}</td>
                                    <td style="width:100%;">
                                        <a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset('storage/objectives_files/'.trim($f))}}" download="{{trim($f)}}">Download</a>
                                        <button class="btn btn-secondary remove-attachment" data-filename="{{ $f }}" style="font-size:11px;" title="Delete">Remove</button>
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
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("#otherunit").hide();


    });

</script>
<script>
    $(document).ready(function () {
        var removedFilenames = [];
        $('.remove-attachment').on('click', function () {
            var filename = $(this).data('filename');
            $(this).closest('tr').remove();
            removedFilenames.push(filename);
        });
        $('#updateobjective').on('submit', function () {
            $('<input />').attr('type', 'hidden')
                .attr('name', 'removedFilenames')
                .attr('value', JSON.stringify(removedFilenames))
                .appendTo($(this));
            return true;
        });
    });
</script>
<script>
    $("#hod_method").change(function() {
        if ($("#hod_method option:selected").val() == 2) {
            $('#otherunit').hide();
        } else if ($("#hod_method option:selected").val() == 3) {
            $('#otherunit').hide();
        } else {
            //$('#risk_response').show();
            $('#otherunit').show();
        }
    });

</script>

<script>
    function validation() {


        if (document.getElementById('department').value == 'none') {
            alert('Please select a department');
            document.getElementById('department').focus();
            return false;
        } else if (document.getElementById('span').value == 'none') {
            alert('Please select a program span');
            document.getElementById('span').focus();
            return false;
        } else if (document.getElementById('responsibility').value == 'none') {
            alert('Please choose responsibility');
            document.getElementById('responsibility').focus();
            return false;
        } else if (document.getElementById('frequency').value == 'none') {
            alert('Please select a frequency of monitoring');
            document.getElementById('frequency').focus();
            return false;
        }

        // always return false to prevent form from submission.
    }

</script>

@endsection
