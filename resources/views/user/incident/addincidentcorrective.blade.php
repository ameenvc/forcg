@extends('layouts.multiselect')
@section('content')

<script>
    function validation() {
        if (document.getElementById('department').value == 0) {
            alert('Please select Department');
            document.getElementById('department').focus();
            return false;
        }
        if (document.getElementById('type').value == 0) {
            alert('Please select Type');
            document.getElementById('type').focus();
            return false;
        }
        if (document.getElementById('responsibility').value == 0) {
            alert('Please select Responsibility');
            document.getElementById('responsibility').focus();
            return false;
        }

        //validation for due date calendar required field

        if (document.getElementById('date').value == 0) {
            alert('Please select Due Date');
            document.getElementById('date').focus();
            return false;

        }
        // always return false to prevent form from submission.
    }
</script>
<style>
    .breadcrumb>li+li:before {
        display: none !important;
    }

    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>

<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <!-- Header starts -->
            <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item font-w700">
                        <a href="{{url('/user/incidents/register')}}" class="text-black">
                          Incident Report
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                       Add Corrective Actions
                    </li>
                </ol>
            </nav>
            <!-- Ends Header --> 
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12  pr-5 pl-5">
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
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" action="{{route('incidentCorrected')}}" enctype="multipart/form-data" onsubmit="return validation()" method="post" files="true">
                        {{csrf_field()}}
                        <input type="hidden" name="posted" value="{{Auth::user()->email}}">
                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Raised by</label>
                                <div class="col-sm-12">
                                    <input type="riskname" class="form-control" id="name" style="font-size:13px;width:100%;" name="name" value="{{Auth::user()->name}}" readonly="">
                                </div>
                            </div>
                            <?php $f = 0;
                            ?>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="category">Category</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="category" id="category" style="width:100%;font-size:13px;" value="Incident Investigation" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="department" id="department" style="font-size:13px;width:100%;" required="true" placeholder="Choose a Department" onchange="chooseDivision($(this).val(), $('#division_val').val())">
                                        @if(count($departments)>0)
                                        @foreach($departments as $dep)
                                        <option value="{{$dep->id}}" style="font-size:13px;" {{ $dep->id == $incident->department ? 'selected' : '' }}> {{$dep->departmentname}}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="type" id="type" required="true" style="font-size:13px;width:100%;" placeholder="Choose Type">
                                        <option value="0" selected="" disabled style="font-size:13px;">Choose Type</option>
                                        @if(count($type)>0)
                                        @foreach($type as $typ)
                                        <option value="{{$typ->order}}" style="font-size:13px;"> {{$typ->name}}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="division_val" value="{{ $incident->division_id }}" />
                        <input type="hidden" id="area_val" value="{{ $incident->area_id }}" />
                        <div class="row">
                            <div class="form-group d-inline-block col-sm-6" id="division">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val(), $('#area_val').val())">
                                        <option value="0" disabled>Choose Division</option></br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group d-inline-block col-sm-6" id="subdiv">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="subdiv">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    <select class="form-control" id="area_select" name="subdiv" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                        <option value="0" disabled>Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" value="Incident # {{$incident->report_no}}" readonly>
                                    <input type="hidden" id="title" name="title" value="{{$incident->report_no}}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="responsibility" style="font-size:13px;">Responsibility<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="responsibility" id="responsibility" style="font-size:13px;width:100%;" required="true" placeholder="Choose Responsibility">
                                        <option value="0" selected="" disabled style="font-size:13px;">Choose Responsibility</option>
                                        @if(count($user)> 0 )
                                        @foreach($user as $use)
                                        <option value="{{$use->email}}" style="font-size:13px;"> {{ $use->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" style="font-size:13px;" id="observation" name="observation" placeholder="Enter observation" value="{{ old('observation') }}" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended action<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="agreedaction" style="font-size:13px;" name="agreedaction" placeholder="Enter recommended action" value="{{ old('agreedaction') }}" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="issue_file" style="font-size:13px;">Attach issue related files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="issue_file" style="font-size:13px;" name="issue_file[]" multiple>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Email reminder for action:</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" style="font-size:13px;width:100%;" id="frequency" name="frequency[]" style="width: 100%;" data-placeholder="Choose frequencies" multiple>
                                        <!-- <option value="0" selected="" disabled style="font-size:13px;">Choose</option> -->
                                        <option value=" Monthly" style="font-size:13px;"> Monthly</option><br>
                                        <option value=" Weekly" style="font-size:13px;"> Weekly</option><br>
                                        <option value=" 2 Days Before" style="font-size:13px;"> 2 days before</option><br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="notification" style="font-size:13px;">Send notifications to:</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" style="font-size:13px;width:100%;" id="example-select2-multiple" name="notification[]" style="width: 100%;" data-placeholder="Choose Persons" multiple>
                                        @if(count($user)> 0 )
                                        @foreach($user as $use)
                                        <option value="{{$use->email}}" style="font-size:13px;"> {{ $use->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Due date<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="date" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary" onclick="return ddldd()" style="font-size:13px; margin-left:86%; ">Add</button>
                                </div>
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
                    <h3 class="block-title">Options</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row gutters-tiny">
                        <!-- <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body" href="/user/corrective/addcorrective">
                                    <div class="block-content text-center" style="height:130px" >
                                    <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Add Issue
                                        </p>
                                    </div>
                                </a>

                            </div> -->
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/corrective/corrissues">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/closedissue">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-check-square fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Closed Issues
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/openissue">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        My Open Issues
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">

                            <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/corrective/analysis' style="margin-top:6px">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
        function ddldd() {
            if ($("#issue_file").val() == '') {
                mess2 = 'Are you sure to submit without attachment?';
                y = confirm(mess2);
                if (y == true) {} else {
                    return false;
                }
            } else {
                mess1 = "Are you sure?";
                x = confirm(mess1);
                if (x == true) {} else {
                    return false;
                }
            }
        }

</script>
<!-- END Apps Modal -->
<script src="{{ asset( 'js/oneui.core.min.js')}}"></script>
<script src="{{ asset( 'js/oneui.app.min.js')}}"></script>
<script src="{{ asset( 'js/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    jQuery(function() {
        One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);
    });

</script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#sel').select2({});
    });
    $(document).ready(function() {

        $('#sel_not').select2({});
    });

</script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let department = '{{ $incident->department }}';
    let area_id = '{{$incident->area_id}}';
    let show_area = 0;
    let area_data = [];
    let area_selector = $('#subdiv');
    let area_select_selector = $('#area_select');
    let division_selector = $('#division');
    let division_select_selector = $('#division_select');
    let division_select = '';
    area_select_selector.val(area_id);
    area_selector.removeClass('d-none').addClass('d-inline-block');
    chooseDivision(department, $('#division_val').val());

    function chooseDivision(id, division_id) {
        division_select_selector.empty();
        let get_areas_url = '{{ route("admin.departmentAreas.getAreas", ":id") }}';
        get_areas_url = get_areas_url.replace(':id', id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: get_areas_url
            , async: false
            , success: function(resp) {
                show_area = resp.data.length;
                area_data = resp.data;
            }
            , error: function(err) {
                console.log(err);
            }
        });

        let the_url = '{{ route("admin.departmentDivisions.getDivisions", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        let option_text1 = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , async: false
            , success: function(resp) {
                if (!resp.data.length) {
                    division_selector.removeClass('d-inline-block').addClass('d-none');
                    area_selector.removeClass('d-inline-block').addClass('d-none');
                    area_select_selector.val(0);
                    division_select_selector.val(0);
                } else {
                    if (show_area) {
                        area_selector.removeClass('d-none');
                        area_selector.addClass('d-inline-block');
                        for (var i = 0; i < area_data.length; i++) {
                            let if_selected = area_data[i].id === Number(area_id) ? 'selected' : '';
                            option_text1 += `<option value="${area_data[i].id}" ${if_selected}>${area_data[i].name}</option>`
                        }
                        area_select_selector.empty().append('<option value="0">Choose Sub Division</option></br>' + option_text1);

                    } else {
                        area_selector.removeClass('d-inline-block');
                        area_selector.addClass('d-none');
                        area_select_selector.val(0);
                    }

                    division_selector.removeClass('d-none').addClass('d-inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        let if_selected = resp.data[i].id == Number(division_id) ? 'selected' : '';
                        option_text += `<option value="${resp.data[i].id}" ${if_selected}>${resp.data[i].name}</option>`;
                    }
                    division_select_selector.empty().append(`<option value="0">Choose Division</option></br>` + option_text);
                    division_select = division_select_selector.val();
                }

            }
            , error: function(err) {
                console.log(err);
            }
        });

    }


    chooseArea(division_select, $('#area_val').val());

    function chooseArea(id, area_id) {
        console.log(id, area_id);
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
                if (!resp.data.length) {
                    area_selector.removeClass('d-inline-block');
                    area_selector.addClass('d-none');
                    area_select_selector.val(0);
                } else {
                    area_selector.removeClass('d-none');
                    area_selector.addClass('d-inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        let if_selected = resp.data[i].id === Number(area_id) ? 'selected' : '';
                        option_text += `<option value="${resp.data[i].id}" ${if_selected}>${resp.data[i].name}</option>`
                    }
                    area_select_selector.empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                    let area_select = area_select_selector.val();
                }
            }
            , error: function(err) {
                console.log(err);
            }
        });
    }

</script>
@endsection
