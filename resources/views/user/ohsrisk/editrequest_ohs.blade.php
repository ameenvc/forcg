@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">


<body>
    <main id="main-container" style="padding-top:0px;">

        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">Health And Safety Risk</li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="">Edit Request</a>
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
            <!-- Dynamic Table Full -->
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="scrollable" style="height:450px;">
                        <form class="mb-5" action="{{url('/user/ohsrisk/editrequest_ohs')}}" method="post">
                            {{ csrf_field() }}

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
                            <div class="block">
                                <div class="block-content block-content-full">

                                    @foreach($data1 as $dat1)

                                    <div class="block">
                                        <div class="block-content block-content-full">
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="actid" style="font-size: 13px;">Activity Id</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="actid[]" style="font-size: 13px;" name="actid[]" readonly="" value="{{$dat1->actid}}">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Department</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="department[]" style="font-size: 13px;" name="department[]" readonly="" value="{{$dat1->department}}">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="type" style="font-size: 13px;">Type of Risk</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="type[]" style="font-size: 13px;" name="type[]" readonly="" value="{{$dat1->type}}">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="date_created" style="font-size: 13px;">Time</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="date_created[]" style="font-size: 13px;" name="date_created[]" readonly="" value="{{$dat1->created_at}}">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="activity" style="font-size: 13px;">Activity</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="activity[]" style="font-size: 13px;" name="activity[]" readonly="" value="{{$dat1->activity}}">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="legal" style="font-size: 13px;">Related Legal Requirement</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="legal[]" style="font-size: 13px;" name="legal[]" readonly="" value="{{$dat1->legal}}">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="location" style="font-size: 13px;">Location</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="location[]" style="font-size: 13px;" name="location[]" readonly="" value="{{$dat1->loc ? $dat1->loc->name : '-'}}">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="team" style="font-size: 13px;">Risk Assessment Team</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="team[]" style="font-size: 13px;" name="team[]" readonly="" value="{{$dat1->team}}">
                                                </div>
                                            </div>

                                            <input type="hidden" name="Rid" value="{{$dat1->id}}">
                                            <input type="hidden" name="postedby" value="{{$dat1->postedby}}">
                                        </div>
                                        @endforeach

                                    </div>
                                </div>
                                @if(count($data2) > 0)
                                <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable">
                                    <thead style="background-color:#FFFFFF">
                                        <tr>
                                            <th rowspan='2' style="text-transform:capitalize"><label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspR/NR/EM&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label></th>
                                            <th rowspan='2' style="text-transform:capitalize"><label>&nbsp&nbspWhat&nbsp&nbspare&nbsp&nbsphazards?&nbsp&nbsp&nbsp&nbsp</label></th>
                                            <th rowspan='2' style="text-transform:capitalize"><label>How&nbsp&nbspwill&nbsp&nbspit&nbsp&nbspaffect?&nbsp&nbspWhom?</label></th>
                                            <th rowspan='2' style="text-transform:capitalize"><label>Risk&nbsp&nbspcontrol&nbsp&nbspmeasure&nbsp&nbspalready&nbsp&nbspin&nbsp&nbspuse&nbsp&nbsp</label></th>
                                            <th colspan='2' style="text-transform:capitalize"><label>Original&nbsp&nbspRisk&nbsp&nbspwith present&nbsp&nbspcontrols&nbsp&nbspNeeded</label></th>
                                            <th rowspan='2' style="text-transform:capitalize"><label>Additional&nbsp&nbspControl&nbsp&nbspMeasures&nbsp&nbspNeeded&nbsp&nbsp</label></th>
                                            <th colspan='2' style="text-transform:capitalize"><label>Residual&nbspRisk&nbspwith&nbspAdditional Control&nbspNeeded&nbsp</label></th>
                                            <th rowspan='2' style="text-transform:capitalize"><label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspMonitoring&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label></th>

                                        </tr>
                                        <tr>
                                            <th>S</th>
                                            <th>L</th>
                                            <th>S</th>
                                            <th>L</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data2 as $dat1)
                                        <?php $rr=$dat1->id;?>
                                        <tr>
                                            <td class="text-center font-size-sm">
                                                <select class="form-control" name="R_NR_EM[]" required="true" style="width: 100px;font-size:13px">
                                                    <option value=" Routine "> R-Routine</option></br>
                                                    <option value=" Non Routine "> NR-Non Routine</option></br>
                                                    <option value=" Emergency "> EM-Emergency</option></br>
                                                </select>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <input type="text" style="width: 260px;font-size: 13px;" class="form-control" id="hazards[]" name="hazards[]" value="{{$dat1->hazards}}">
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <input type="text" style="width: 260px;font-size: 13px;" class="form-control" id="affect[]" name="affect[]" value="{{$dat1->affect}}">
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <input type="text" style="width: 280px;font-size: 13px;" class="form-control" id="control[]" name="control[]" value="{{$dat1->control}}">
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <select class="form-control" name="OrS[]" required="true" style="width: 100px;font-size:13px">
                                                    <option value=" 1 "> 1</option></br>
                                                    <option value=" 2 "> 2</option></br>
                                                    <option value=" 3 "> 3</option></br>
                                                    <option value=" 4 "> 4</option></br>
                                                </select>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <select class="form-control" name="OrL[]" required="true" style="width: 100px;font-size:13px">
                                                    <option value=" 1 "> 1</option></br>
                                                    <option value=" 2 "> 2</option></br>
                                                    <option value=" 3 "> 3</option></br>
                                                    <option value=" 4 "> 4</option></br>
                                                </select>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <input type="text" style="width: 280px;font-size: 13px;" class="form-control" id="additional[]" name="additional[]" value="{{$dat1->additional}}">
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <select class="form-control" name="RS[]" required="true" style="width: 100px;font-size:13px">
                                                    <option value=" 1 "> 1</option></br>
                                                    <option value=" 2 "> 2</option></br>
                                                    <option value=" 3 "> 3</option></br>
                                                    <option value=" 4 "> 4</option></br>
                                                </select>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <select class="form-control" name="RL[]" required="true" style="width: 100px;font-size:13px">
                                                    <option value=" 1 "> 1</option></br>
                                                    <option value=" 2 "> 2</option></br>
                                                    <option value=" 3 "> 3</option></br>
                                                    <option value=" 4 "> 4</option></br>
                                                </select>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <input type="text" style="width: 280px;font-size: 13px;" class="form-control" id="monitor[]" name="monitor[]" value="{{$dat1->monitor}}">
                                            </td>
                                            <div class="form-group">
                                                <input type="hidden" name="sno[]" value="{{ $dat1->id}}">
                                            </div>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                                <div>
                                    <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-secondary" style="font-size:13px;margin-left:91%;">Submit</button>
                                </div>

                                <!-- <div class="col-lg-10">
                <br>
                </div>				 -->

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- END Apps Modal -->
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

</body>

<script type="text/javascript">
    // $(document).ready(function() {
    // $('#sel_purpose').select2({
    // });
    // });
    $(document).ready(function() {
        $('#sel_type').select2({});
    });
    //sel_bias

</script>
</html>
@endsection
