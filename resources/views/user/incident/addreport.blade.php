@extends('layouts.multiselect')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Incident Report</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Add</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/incidents/register">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    @if(Session::has('msg'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('msg')!!}
    </div>
    @endif
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
            <div class="row">
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <?php $f=0; ?>
                    <form class="mb-5" action="{{route('incidentConfirm')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="rid" style="font-size: 13px;">Report ID</label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="rid" name="rid" value="{{$irid}}" readonly>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="addedby" style="font-size: 13px;">Reported By</label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="addedby" name="addedby" value="{{Auth::user()->name}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $today=Carbon\Carbon::now()->toDateTimeString();
                                $today=Carbon\Carbon::parse($today);
                            ?>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="addeddate" style="font-size: 13px;">Reporting Date</label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="addeddate" name="addeddate" value="{{$today->format('d/m/Y')}}" readonly>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Department <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="department" required style="font-size: 13px;width:100%;" placeholder="Choose a Department" onchange="chooseDivision($(this).val())">
                                            <option value="">Choose a Department</option>
                                            @if(count($departments)>0)
                                            @foreach($departments as $dept)
                                            <option value="{{$dept->id}}">{{$dept->departmentname}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group d-none col-sm-6" id="division">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="division_select" name="division" required="true" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                                            <option value="0">Choose Division</option></br>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group d-none col-sm-6" id="sub_div">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="sub_div">Sub Division</label>
                                    <div class="col-sm-12" id="action_number" name="action">
                                        <select class="form-control" id="area_select" name="sub_div" required="true" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                            <option value="0">Choose Sub Division</option></br>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="area" style="font-size: 13px;">Area <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" required class="form-control" id="area" name="area" placeholder="Enter Incident Area">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Incident Type <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" id="type" name="type[]" multiple style="font-size: 13px;width:100%;" data-placeholder="Choose Type" required>
                                            @if(count($incidents))
                                            @foreach($incidents as $incident)
                                            <option value="{{$incident->incident_type}}">{{$incident->incident_type}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Any Person Injured / Suffered <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input style="margin-right:4px;margin-left:10px;" type="radio" value="yes" id="person_injured" name="person_injured" required onclick="display3()"><label style="font-size: 13px;">Yes</label>
                                        <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="person_injured1" name="person_injured" onclick="display3()"><label style="font-size: 13px;">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6" id="checkid">
                                    <label class="col-sm-12 col-form-label" for="person" style="font-size: 13px;">Name of the injured / suffered person<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" onchange="sel(this.value,'{{$empdetails}}','{{$depnam}}')" onclick="display2()" id="injper" name="injper" style="font-size: 13px;width:100%;">
                                            <option value="">Select Injured Employee</option>
                                            @if(count($empdetails)>0)
                                            @foreach($empdetails as $emp)
                                            <option value="{{$emp->emp_number}}">{{$emp->emp_name}}</option>
                                            @endforeach
                                            @endif
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;margin-top:35px;" class="form-control" id="injposition" name="injposition" placeholder="Injured Employee Position" readonly>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="injempid" name="injempid" placeholder="Injured Employee ID" readonly>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="injdept" name="injdept" placeholder=" Injured Employee Department" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="injempname_oth" name="injempname_oth" placeholder="Injured Employee Name">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="injposition_oth" name="injposition_oth" placeholder="Injured Employee Position">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="injempid_oth" name="injempid_oth" placeholder="Injured Employee ID">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="col-sm-12">
                                        <select class="form-control" name="injdept_oth" id="injdept_oth" style="font-size: 13px;width:100%;">
                                            <option value="">Select Injured Employee Department</option>
                                            @if(count($departments)>0)
                                            @foreach($departments as $dept)
                                            <option value="{{$dept->id}}">{{$dept->departmentname}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Date and Time of Incident <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="date" class="js-datepicker form-control" required id="date" name="incidentdate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="col-sm-12">
                                        <input type="time" class="form-control" name="time" required style="font-size: 13px;" placeholder="Time">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="hospital" style="font-size: 13px;">Name of Hospital Injured / Suffered Person attended/ Admitted <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" required class="form-control" id="hospital" name="hospital" placeholder="Enter Hospital Name">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="employer" style="font-size: 13px;">Employer of the injured / suffered person (state if labour supply) <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" required style="font-size: 13px;" class="form-control" id="employer" name="employer" placeholder="Enter"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="place" style="font-size: 13px;">Place of the Incident <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="place" name="place" required placeholder="Enter Place of Incident">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="trade" style="font-size: 13px;"> Trade or occupation <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" placeholder="Enter"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="desc" style="font-size: 13px;"> Description <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea type="text" required style="font-size: 13px;" class="form-control form-control-file" id="desc" name="desc" placeholder="Describe step-by-step the events leading up to the incident (who, what, when, where, why) include names of any machines, parts, objects, tools, materials, Contacts, spread or other important details."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;"> Action Taken <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea type="text" required style="font-size: 13px;" class="form-control" id="action" name="action" placeholder="What Immediate actions has been taken including medical, investigation, Prevent recurrence / spread and by whom?"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;"> Attach Incident Related Files(avoid '#' and '&' in file names)</label>
                                    <div class="col-sm-12">
                                        <div class="input-group realprocode control-group lst increment" style="width:210%;">
                                            <input type="file" name="filenames[]" class="myfrm form-control">
                                            <div class="input-group-btn">
                                                <button class="btn btn-success" type="button"> <i class="fa fa-plus fa-1x"></i></button>
                                            </div>
                                        </div>

                                        <div class="clone hide">
                                            <div class="realprocode control-group lst input-group" style="margin-top:10px;width:210%;">
                                                <input type="file" name="filenames[]" class="myfrm form-control">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-danger" type="button"><i class="fa fa-minus fa-1x"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $(".btn-success").click(function() {
                                        var lsthmtl = $(".clone").html();
                                        $(".increment").after(lsthmtl);
                                    });
                                    $("body").on("click", ".btn-danger", function() {
                                        $(this).parents(".realprocode").remove();
                                    });
                                });

                            </script>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="incidentreviewer" style="font-size: 13px;">Incident Reviewer <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="reviewer" name="reviewer" style="font-size: 13px;width:100%;" required placeholder="Choose Reviewer">
                                            <option value="">Choose Reviewer</option>
                                            @if(count($reviewers))
                                            @foreach($reviewers as $reviewer)
                                            <option value="{{$reviewer->email}}">{{$reviewer->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="incidentapprover" style="font-size: 13px;">Incident Approver <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="approver" name="approver" style="font-size: 13px;width:100%;" required data-placeholder="Choose Approver">
                                            <option value="">Choose Approver</option>
                                            @if(count($approvers))
                                            @foreach($approvers as $approver)
                                            <option value="{{$approver->email}}">{{$approver->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Reportable under Local Regulations? <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input style="margin-right:4px;margin-left:10px;" type="radio" value="yes" id="yes" onclick="display()" name="local" required><label style="font-size: 13px;">Yes</label>
                                        <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no" onclick="display()" name="local"><label style="font-size: 13px;">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-8" style="display:none;" id="regu">
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="regulation" name="regulation" placeholder="Under which regulation?" style="display:none;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="row form-group">
                            <div class="col-sm-10 text-right">
                                <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;">Reset</button>
                                <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">Add </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function sel(value, details, deprts) {
        datas = JSON.parse(details);
        depmts = JSON.parse(deprts);
        for (var key in datas) {
            if (datas[key].emp_number === value) {
                document.getElementById('injempid').value = datas[key].emp_number;
                //document.getElementById('injdept').value=datas[key].emp_department;
                var a = datas[key].emp_department;
                deprtname = '-';
                for (let i = 0; i < depmts.length; i++) {
                    if (depmts[i].id == a) {
                        deprtname = depmts[i].departmentname;
                        break;
                    }
                }
                document.getElementById('injdept').value = deprtname;
                if (datas[key].emp_position == '') {
                    document.getElementById('injposition').value = '-';
                } else {
                    document.getElementById('injposition').value = datas[key].emp_position;
                }
            }
        }
    }

</script>
<script type="text/javascript">
    window.onload = function() {
        $(document).ready(function() {
            $('#checkid').hide();
            $('#injempid').hide();
            $('#injposition').hide();
            $('#injdept').hide();
            $('#injempname_oth').hide();
            $('#injempid_oth').hide();
            $('#injposition_oth').hide();
            $('#injdept_oth').hide();

        });

        $("#person_injured").change(function() {
            if ($("#person_injured:checked").val() == 'yes') {
                $('#checkid').show();
                $('#injempid').show();
                $('#injposition').show();
                $('#injdept').show();
            }
        });
        $("#person_injured1").change(function() {
            if ($("#person_injured1:checked").val() == 'no') {
                $('#checkid').hide();
                $('#injempid').hide();
                $('#injposition').hide();
                $('#injdept').hide();
            }
        });


        $("#injper").change(function() {
            if ($("#injper option:selected").val() == "other") {
                $('#injempname_oth').show();
                $('#injempid_oth').show();
                $('#injposition_oth').show();
                $('#injdept_oth').show();
                $('#injempid').hide();
                $('#injposition').hide();
                $('#injdept').hide();
            } else if ($("#injper option:selected").val() != "other") {
                $('#injempid').show();
                $('#injposition').show();
                $('#injdept').show();
                $('#injempname_oth').hide();
                $('#injempid_oth').hide();
                $('#injposition_oth').hide();
                $('#injdept_oth').hide();
            }
        });


    }

</script>

<script>
    function display2() {
        if (document.getElementById('injper').value == "other") {
            document.getElementById('injempname_oth').required = true;
            document.getElementById('injempid_oth').required = true;
            document.getElementById('injposition_oth').required = true;
            document.getElementById('injdept_oth').required = true;
        } else {
            document.getElementById('injempname_oth').required = false;
            document.getElementById('injempid_oth').required = false;
            document.getElementById('injposition_oth').required = false;
            document.getElementById('injdept_oth').required = false;
        }
    }

    function display3() {
        if (document.getElementById('person_injured').checked) {
            document.getElementById('injper').required = true;
        } else if (document.getElementById('person_injured1').checked) {
            document.getElementById('injper').required = false;
        }
    }

</script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    function chooseDivision(id) {
        $('#division').addClass('d-none');
        $('#sub_div').addClass('d-none');
        let the_url = '{{ route("admin.departmentDivisions.getDivisions", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , success: function(resp) {
                if (resp.data.length) {
                    $('#division').removeClass('d-none');
                    $('#division').css('display', 'inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
                    }
                    $('#division_select').empty().append('<option value="0">Choose Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });

    }

    function chooseArea(id) {
        $('#sub_div').addClass('d-none');
        let the_url = '{{ route("admin.departmentDivisions.getAreasOfADivision", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , success: function(resp) {
                if (resp.data.length) {
                    $('#sub_div').removeClass('d-none');
                    $('#sub_div').css('display', 'inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
                    }
                    $('#area_select').empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });
    }

</script>


<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">More</h3>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/incidents/register">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Incident Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/incidents/closed/incidents">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Closed Incident
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">

                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/incident/correactions">
                                <div class="block-content text-center">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        My Open Corrective Actions
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

<script>
    function display() {
        if (document.getElementById('yes').checked) {
            document.getElementById('regu').style.display = "";
            document.getElementById('regulation').required = true;
        } else if (document.getElementById('no').checked) {
            document.getElementById('regu').style.display = "none";
            document.getElementById('regulation').required = false;
        }
    }

</script>
@endsection
