@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">

<body>

    <!-- Hero -->

    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Health And Safety Risk</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Secondary Review</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/ohsrisk/folder/{{$type}}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="padding-top:30px;width:95%;margin-left:45px;">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{url('user/ohsrisk/secondaryreview')}}" method="post" style="margin-top:40px">
                            {{csrf_field()}}
                            @if(count($data)> 0 )
                            @foreach($data as $dat)

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="actid" style="font-size: 13px;">Activity ID</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="actid" name="actid" readonly value="{{ $dat->actid }}" style="font-size: 13px;">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department">Department</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="department" name="department" readonly value="{{ $dat->dept ? $dat->dept->departmentname : '-' }}" style="font-size: 13px;">
                                </div>
                            </div>
                            @if($type == 'divisional' || $type == 'subdivisional')
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;">Division</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" readonly value="{{ $dat->division ? $dat->division->name : '-' }}" style="font-size: 13px;">
                                </div>
                            </div>
                            @endif
                            @if($type == 'subdivisional')
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;">SubDivision</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" readonly value="{{ $dat->area ? $dat->area->name : '-' }}" style="font-size: 13px;">
                                </div>
                            </div>
                            @endif
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Type of Risk</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="type" name="type" readonly value="{{ $dat->type }}" style="font-size: 13px;">
                                </div>
                            </div>
                            <?php date_default_timezone_set("Asia/Kuwait");  ?>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="team" style="font-size: 13px;">Time</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="date_created" name="date_created" readonly value="<?php echo Carbon\Carbon::now()->toDateTimeString()?>" style="font-size: 13px;">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="activity" style="font-size: 13px;">Activity</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="activity" name="activity" readonly value="{{ $dat->activity }}" placeholder="Enter activity" style="font-size: 13px;">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="legal" style="font-size: 13px;">Related Legal Requirement</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="legal" name="legal" readonly value="{{ $dat->legal }}" placeholder="Enter Related Legal Requirement" style="font-size: 13px;">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="location" style="font-size: 13px;">Location</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="location" name="location" readonly value="{{ $dat->loc ? $dat->loc->name : '-' }}" placeholder="Enter Location" style="font-size: 13px;">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="team" style="font-size: 13px;">Risk Assessment Team</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="team" name="team" readonly value="{{ $dat->team }}" placeholder="Enter Risk Assessment Team" style="font-size: 13px;">
                                </div>
                            </div><br>
                            @endforeach
                            @else
                            <div class="row justify-content-center">
                                <h2>No Data</h2>
                            </div>
                            @endif
                            @if(count($data1)>0)
                            <div class="col-lg-20">
                                <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable">
                                    <thead style="background-color:#FFFFFF">
                                        <tr>
                                            <th rowspan='2' style="text-transform:capitalize;font-size:12px;"><label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspR/NR/EM&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label></th>
                                            <th rowspan='2' style="text-transform:capitalize;font-size:12px;"><label>&nbsp&nbspWhat&nbsp&nbspare&nbsp&nbsphazards?&nbsp&nbsp&nbsp&nbsp</label></th>
                                            <th rowspan='2' style="text-transform:capitalize;font-size:12px;"><label>How&nbsp&nbspwill&nbsp&nbspit&nbsp&nbspaffect?&nbsp&nbspWhom?</label></th>
                                            <th rowspan='2' style="text-transform:capitalize;font-size:12px;"><label>Risk&nbsp&nbspcontrol&nbsp&nbspmeasure&nbsp&nbspalready&nbsp&nbspin&nbsp&nbspuse&nbsp&nbsp</label></th>
                                            <th colspan='2' style="text-transform:capitalize;font-size:12px;"><label>Original Risk with present controls</label></th>
                                            <th rowspan='2' style="text-transform:capitalize;font-size:12px;"><label>Additional&nbsp&nbspControl&nbsp&nbspMeasures&nbsp&nbspNeeded&nbsp&nbsp</label></th>
                                            <th colspan='2' style="text-transform:capitalize;font-size:12px;"><label>Residual Risk with Additional Control</label></th>
                                            <th rowspan='2' style="text-transform:capitalize;font-size:12px;"><label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspMonitoring&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label></th>
                                        </tr>
                                        <tr>
                                            <th style="font-size:12px;">S</th>
                                            <th style="font-size:12px;">L</th>
                                            <th style="font-size:12px;">S</th>
                                            <th style="font-size:12px;">L</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data1 as $dat1)
                                        <?php $rr=$dat1->ohs_id; ?>
                                        <tr>
                                            <td><select name="R_NR_EM[]" class="form-control" style="font-size:13px">
                                                    <option value="{{ $dat1->R_NR_EM }}">{{$dat1->R_NR_EM}}</option>
                                                    <option value="Routine">R-Routine</option>
                                                    <option value="Non Routine">NR-Non Routine</option>
                                                    <option value="Emergency">EM-Emergency</option>
                                                </select>
                                            </td>
                                            <td><textarea type="text" rows="3" name="hazards[]" class="form-control" style="font-size:13px">{{$dat1->hazards}}</textarea></td>
                                            <td><textarea type="text" rows="3" name="affect[]" class="form-control" style="font-size:13px">{{$dat1->affect}}</textarea></td>
                                            <td><textarea type="text" rows="3" name="control[]" class="form-control" style="font-size:13px">{{$dat1->control}}</textarea></td>
                                            <td><select name="OrS[]" class="form-control" style="width:56px;font-size:13px">
                                                    <option selected value="{{ $dat1->OrS }}">{{ $dat1->OrS }}</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </td>

                                            <td><select name="OrL[]" class="form-control" style="width:56px;font-size:13px">
                                                    <option selected value="{{ $dat1->OrL }}">{{ $dat1->OrL }}</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </td>
                                            <td><textarea type="text" rows="3" name="additional[]" class="form-control" style="font-size:13px">{{$dat1->additional}}</textarea></td>
                                            <td><select name="RS[]" class="form-control" style="width:56px;font-size:13px">
                                                    <option selected value="{{ $dat1->RS }}">{{ $dat1->RS }}</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </td>
                                            <td><select name="RL[]" class="form-control" style="width:56px;font-size:13px">
                                                    <option selected value="{{ $dat1->RL }}">{{ $dat1->RL }}</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="monitor[]" value="{{$dat1->monitor}}" class="form-control" style="font-size:13px" /></td>
                                            <input type="hidden" name="approvedby" value="{{Auth::user()->employeeid}}">
                                            <input type="hidden" name="posted" value="{{ $dat1->ohs_id}}">
                                            <input type="hidden" name="idd[]" value="{{ $dat1->id}}">
                                            <input type="hidden" name="slno[]" value="{{ $dat1->id}}">

                                            <td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Edit Client">
                                                    <i class="fa fa-fw fa-plus"></i>
                                                </button>
                                            </td>

                                        </tr>

                                    </tbody>
                                    @endforeach
                                </table>
                            </div>
                            @endif
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label for="question" class="col-sm-12 col-form-label" style="font-size: 13px;">Review outcome</label>
                                <div class="col-sm-12">
                                    <select name="question" id="close_option" required class="form-control col-xl-12" style="font-size: 13px;width:398px;">
                                        <option value="">None</option>
                                        <option value="2">Reviewed and found OK</option>
                                        <option value="1">Reviewed, need improvement</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label id="b" for="proposal" class="col-sm-12 col-form-label" style="font-size: 13px;">Reviewer Proposal</label>
                                <div class="col-sm-12">
                                    <input type="text" name="proposal" id="proposal" value="{{$dat->proposechange}}" class="form-control" style="font-size: 13px;">
                                </div>
                            </div>

                            <div class="form-group" style="text-align:center;">
                                <button for="submit" type="submit" class="btn btn-primary" style="font-size: 13px;margin-left:90%;" onclick="return confirm('Are you sure to submit the request?')">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->

        <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Options</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="row gutters-tiny">


                                <div class="col-6">

                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/ohsrisk/folder">
                                        <div class="block-content text-center">
                                            <i class="fa fa-2x fa-clipboard-list text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Register
                                            </p>
                                        </div>
                                    </a>

                                </div>
                                <!-- <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/ohsrisk/masterlist">
                                            <div class="block-content text-center">
                                                <i class="fa fa-2x fa-id-card-alt text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Master List
                                                </p>
                                            </div>
                                        </a>

                                    </div> -->
                                <div class="col-6">
                                    <!-- Payments -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/ohsrisk/analysis">
                                        <div class="block-content text-center">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Analysis
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Payments -->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Apps Modal -->
    </div>
    <!-- END Page Container -->


    <!-- Apps Modal -->

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $("#close_option").change(function() {
        console.log($("#close_option option:selected").val());
        if ($("#close_option option:selected").val() == 2) {
            $('#shipping_carrier').prop('disabled', 'disabled');
            $('#shipping_carrier').val(1);
            $('#proposal').hide();
            $('#b').hide();
            $('#remarks').show();
            $('#a').show();

        } else {
            $('#shipping_carrier').prop('disabled', false);
            $('#proposal').show();
            $('#b').show();
            $('#remarks').hide();
            $('#a').hide();


        }
    });

</script>

</html>
@endsection
