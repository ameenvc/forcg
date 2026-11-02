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
                        <a class="link-fx" href="">Resubmit Objectives</a>
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
    <!-- Horizontal -->
    <div class="block block-rounded">

        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" id="resubmitbjective" enctype="multipart/form-data" action="{{url('/user/objectives/resubmit/'. $type)}}" method="POST">
                        {{csrf_field()}}
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
                        @if($data)
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="objid" style="font-size: 13px;">Objectives ID</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="Oid" name="Oid" style="font-size: 13px;" readonly value="{{$data->Oid}}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Department</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="department" style="font-size: 13px;" name="department" readonly value="{{$data->dep ? $data->dep->departmentname : ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($type== 'divisional' || $type == 'subdivisional')
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;">Division</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" readonly value="{{$data->division ? $data->division->name : ''}}">
                                </div>
                            </div>
                            @endif
                            @if($type == 'subdivisional')
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;">Sub-Division</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" readonly value="{{$data->area ? $data->area->name : ''}}">
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="objective_type">Objective type</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="objective_type" name="objective_type[]" style="font-size: 13px;width:100%;" data-placeholder="Choose Objective Type" multiple>
                                        @if(count($objective_types)> 0 )
                                            @foreach($objective_types as $item)
                                                <option value="{{$item->id}}" style="font-size: 13px;" {{ in_array($item->id, $data->objective_type_id) ? 'selected' : '' }}> {{ $item->name }}</option></br>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="objname" style="font-size: 13px;">Objective name</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="objname" style="font-size: 13px;" name="objname" value="{{$data->objectives}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="description" style="font-size: 13px;">Objectives description</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" type="text" name="description" id="description" style="font-size: 13px;">{{$data->description}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="fromdate" class="col-sm-12 col-form-label" style="font-size: 13px;">From</label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="Date" name="fromdate" style="font-size: 13px;" value="{{$data->fromdate}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="fromdate" class="col-sm-12 col-form-label" style="font-size: 13px;">To</label>

                                <div class="col-sm-12">
                                    <input class="form-control" type="Date" name="todate" style="font-size: 13px;" value="{{$data->todate}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="baseline" style="font-size: 13px;">Baseline</label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text"
                                        step="any" name="baseline" id="baseline"
                                        style="font-size: 13px;" value="{{$data->baseline}}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="target" style="font-size: 13px;">Targets</label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="number" step="any" name="target" id="otarget" style="font-size: 13px;" value="{{$data->otarget}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="hod">Unit</label>
                                <div class="col-sm-12">
                                    <select class="form-control" style="font-size: 13px;width:100%;" name="unit" id="hod_method">

                                        @if($data->unit=='%')
                                        <option value="{{$data->unit}}">{{$data->unit}}</option>
                                        <option value="No sign">No sign</option>
                                        <option value="4">Other</option>

                                        @elseif($data->unit=="No sign")
                                        <option value="{{$data->unit}}">{{$data->unit}}</option>
                                        <option value="%">%</option>
                                        <option value="4">Other</option>

                                        @else
                                        <option value="{{$data->unit}}">{{$data->unit}}</option>
                                        <option value="%">%</option>
                                        <option value="No sign">No sign</option>
                                        <option value="4">Other</option>
                                        @endif
                                    </select>

                                </div>
                                <div class="col-sm-12 form-group" id="otherunit" name="otherunit">
                                    @if($data->unit=="%" || $data->unit=="No sign")
                                    <br><input type="text" class="form-control" name="otherunit" style="font-size: 13px;" value="">
                                    @elseif($data->unit!="%" || $data->unit!="No sign")
                                    <br><input type="text" class="form-control" name="otherunit" style="font-size: 13px;" value="{{$data->unit}}">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="hod">Program span</label>
                                <div class="col-sm-12">
                                    <select class="form-control" style="font-size: 13px;width:100%;" name="span" id="span">
                                        <option value="{{$data->span}}">{{$data->span}}</option>

                                        <option value="Short">Short</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Long">Long</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;">Target date</label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="Date" style="font-size: 13px;" name="tardate" value="{{$data->tardate}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="methodology">Methodology</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" style="font-size: 13px;" id="methodology" name="methodology">{{$data->methodology}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="resources">Resources</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="resources" style="font-size: 13px;" name="resources">{{$data->resources}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="responsibility">Responsibility</label>
                                <div class="col-sm-12">

                                    <select class="js-select2 form-control" name="responsibility[]" data-placeholder="Choose Responsible Persons" required="true" style="font-size:13px;width:100%;" multiple>
                                        @if(count($users)>0)
                                        @foreach($users as $u)
                                        <option value="{{$u->email}}" style="font-size:13px;" {{ in_array($u->name, explode(',', $data->responsibility)) || in_array($u->email, explode(',', $data->responsibility)) ? 'selected' : ''}}> {{$u->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="frequency">Frequency of monitoring</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" name="frequency" id="frequency" value="{{$data->frequency}}" readonly>
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
                                            <option value="{{$value->id}}" {{ in_array($value->id, explode(',',$data->reviewers)) ? 'selected' : '' }}> {{$value->name}}</option>
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
                                        <option value="{{$value->id}}" {{$data->approver==$value->id ? 'selected':''}}> {{ $value->name }}</option>
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
                                    <a data-toggle="modal"  data-target="#viewModal"  data-id="{{ $data->Oid }}" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;color:#fff;"><strong>View Attachments</strong></a>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="{{$data->Oid}}" name="Oid">
                        <div class="row">
                            <div class="text-right col-sm-12 form-group">
                                <button for="reset" type="reset" class="btn btn-primary" style="font-size:13px;">Reset</button>
                                <button type="submit" class="btn btn-primary" style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed?')">Submit</button>
                            </div>
                        </div>
                        @endif
                    </form>
                    <!-- END Form Horizontal - Default Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->
</div>
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
                    @if($data->files!='')
                        <thead>
                            <tr class="table-secondary">
                                <th>S.No</th>
                                <th>Attachment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $files = explode(',',$data->files) @endphp
                            @foreach($files as $f)
                            <tr>
                                <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                <td style="font-size:15px;width:70%;">{{ preg_replace('/_\d+_[^.]+(\.[^.]+)$/', '$1', $f)}}</td>
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
        $('#resubmitbjective').on('submit', function () {
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
        console.log($("#hod_method option:selected").val());
        if ($("#hod_method option:selected").val() == "4") {
            //$('#risk_response').show();
            $('#otherunit').show();
        } else {
            //$('#risk_response').hide();
            $('#otherunit').hide();
        }
    });

</script>

@endsection
