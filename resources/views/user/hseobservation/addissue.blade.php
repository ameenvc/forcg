@extends('layouts.multiselect')
@section('content')
<style>
    .border-3 {
        border-width: 3px !important;
    }

    .font-15{
        font-size:12px !important;
    }
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
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
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="{{ route('hseobservation.index') }}" class="text-black">
                        HSE Observations
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Add Issue
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{route('hseobservation.insertissue')}}" enctype="multipart/form-data" method="POST" files="true">
                        {{csrf_field()}}
                        <input type="hidden" name="posted" value="{{Auth::user()->email}}">
                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">
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
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Raised by</label>
                                <div class="col-sm-12">
                                    <input type="riskname" class="form-control" id="name" style="font-size:13px;" name="name" value="{{Auth::user()->name}}" readonly="">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="category">Category <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="category" required="true" style="font-size:13px;" data-placeholder="Choose Category">
                                        <option value="">Choose Category</option>
                                        @if(count($categories)>0)
                                        @foreach($categories as $cat)
                                        @if($cat->categoryname != "Corrective Action")
                                        <option value="{{$cat->id}}" {{ old('category') == $cat->id ? 'selected' : '' }}>
                                            {{$cat->categoryname}}
                                        </option>
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="responsibility">Responsibility <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" name="responsibility" required="true" style="font-size:13px;width:100%;" data-placeholder="Choose Responsibility">
                                        <option value=""></option>
                                        @if(count($user)> 0 )
                                        @foreach($user as $use)
                                        <option value=" {{$use->email}}"> {{ $use->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="area" style="font-size:13px;">Area</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:13px;" id="area" name="area" placeholder="Enter Area" value="{{ old('area') }}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:13px;" id="title" name="title" placeholder="Enter title.." value="{{ old('title') }}" required>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="type" required="true" style="font-size:13px;" data-placeholder="Choose Type">
                                        <option value=""></option>
                                        @if(count($type)>0)
                                        @foreach($type as $typ)
                                        <option value="{{$typ->id}}" {{$typ->id==old('type')?'selected':''}}> {{$typ->hsetype}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="observation">Observation <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" required style="font-size:13px;" id="observation" name="observation" placeholder="Enter observation" value="{{ old('observation') }}"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="agreedaction">Recommended action</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" style="font-size:13px;" id="agreedaction" name="agreedaction" placeholder="Enter recommended action" value="{{ old('agreedaction') }}"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="issue_file">Attach issue related files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" style="font-size:13px;" id="issue_file" name="issue_file[]" multiple value="{{old('issue_file')}}">
                                </div>
                            </div>


                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Due date <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" style="font-size:13px;" id="date" name="duedate" required placeholder="dd/mm/yyyy">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Email reminder for action:</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="frequency" name="frequency[]" style="font-size:13px;width:100%;" data-placeholder="Choose Frequencies" multiple>
                                        <option value=" Monthly"> Monthly</option><br>
                                        <option value=" Weekly"> Weekly</option><br>
                                        <option value=" 2 Days Before"> 2 Days Before</option><br>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="notification" style="font-size:13px;">Send notifications to:</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="example-select2-multiple" style="font-size:13px;width:100%;" name="notification[]" multiple data-placeholder="Choose Users">
                                        @if(count($user)> 0 )
                                        @foreach($user as $use)
                                        <option value=" {{$use->email}}" style="font-size:13px;"> {{ $use->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Responsible Department <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="department" required="true" style="font-size:13px;" data-placeholder="Choose a Department" onchange="chooseDivision($(this).val())">
                                        <option value=""></option>
                                        @if(count($departments)>0)
                                        @foreach($departments as $dep)
                                        <option value="{{$dep->id}}" {{$dep->id==old('department')?'selected':''}}> {{$dep->departmentname}}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group d-none col-sm-6" id="division">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                                        <option value="0">Choose Division</option></br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group d-none col-sm-6" id="sub_div">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="sub_div">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    <select class="form-control js-select2" id="area_select" name="sub_div" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                        <option value="0">Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary" onclick="return ddldd()" style="font-size:13px;">Add</button>
                            </div>
                        </div>
                    </form>
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
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/hseobservation/issues">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/closedissue">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/openissue">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/analysis">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
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

<script src="{{ asset( 'js/oneui.core.min.js')}}"></script>
<script src="{{ asset( 'js/oneui.app.min.js')}}"></script>
<script src="{{ asset( 'js/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    function chooseDivision(id) {
        $('#division').addClass('d-none');
        $('#sub_div').addClass('d-none');
        $('#division_select').val("");
        $('#area_select').val("");
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
                    $('#division_select').empty().append('<option value="">Choose Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });
    }

    function chooseArea(id) {
        $('#sub_div').addClass('d-none');
        $('#area_select').val("");
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
                    $('#area_select').empty().append('<option value="">Choose Sub Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });
    }
</script>
@endsection
