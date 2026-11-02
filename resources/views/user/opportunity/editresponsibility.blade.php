@extends('layouts.multiselect')
@section('content')
<style>
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
                    <a href="{{ route('opportunity.oppindex') }}" class="text-black">
                        Opportunity
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Edit Responsibility
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" enctype="multipart/form-data" action="{{route('opportunity.submitoppresponsibility')}}" method="post" files="true">
                        {{csrf_field()}}

                        <input type="hidden" name="id" value="{{$data->id}}">

                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="reportedby">Reported By</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="reportedby" name="reportedby" value="{{App\User::where('employeeid',$data->postedby)->value('name')}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="opportunityid" style="font-size:13px;">Opportunity ID</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="oppid" name="oppid" style="font-size:13px;" value="{{$data->dep_id}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Opportunity Name</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" style="font-size:13px;" name="name" value="{{$data->oppname}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="type" name="type" style="font-size:13px;" value="{{$type}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Responsible Department<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="department" id="department" style="font-size:13px;width:100%;" required="true" placeholder="Choose a Department" onchange="chooseDivision($(this).val(), $('#division_val').val())">
                                        @if(count($departments)>0)
                                        @foreach($departments as $dep)
                                        <option value="{{$dep->id}}" style="font-size:13px;" <?php if($data->department==$dep->id) {?> selected <?php } ?>> {{$dep->departmentname}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="division_val" value="{{ $data->division_id }}" />
                            <input type="hidden" id="area_val" value="{{ $data->area_id }}" />

                            <div class="form-group col-sm-4" id="division">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val(), $('#area_val').val())">
                                        <option value="0" disabled>Choose Division</option></br>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-4" id="area">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    <select class="form-control js-select2" id="area_select" name="area" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                        <option value="0" disabled>Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="person" style="font-size:13px;">Responsible Person<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="responsible_person" required="true" style="font-size:13px;width:100%;" data-placeholder="Choose responsibility">
                                        <option value="" selected disabled>Choose responsibility</option>
                                        @if(count($users)>0)
                                            @foreach($users as $user)
                                            <option value="{{$user->email}}" style="font-size:13px;" {{$user->email==$data->responsibility?'selected':''}}> {{$user->name}}</option></br>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Target Date</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="targetdate" style="font-size:13px;" name="targetdate" value="{{date('d-m-Y',strtotime($data->tardate))}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 mb-0">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="opportunityapprover_id">Opportunity Approver<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <div class="form-group" style="color:#666666">
                                        <select {{ $data->status == 'Review Pending' || $data->status == 'Disapproved' ? '' : 'disabled'  }} class="js-select2 form-select w-100" style="font-size: 13px;width:100%;" id="opportunityapprover_id" name="opportunityapprover_id" required="true">
                                            <option value="" selected disabled>Select Approver</option>
                                            @if(count($opportunityapprovers)> 0 )
                                                @foreach($opportunityapprovers as $opportunityapprover)
                                                    <option value="{{$opportunityapprover->user->id}}" {{ $data->opportunityapprover_id == $opportunityapprover->user->id ? 'selected' : '' }}> {{ $opportunityapprover->user->name }}</option></br>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 mb-0">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="closurereviewer_id">Closure Reviewer<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <div class="form-group" style="color:#666666">
                                        <select class="js-select2 form-select w-100" style="font-size: 13px;width:100%;" id="closurereviewer_id" name="closurereviewer_id" required="true">
                                            <option value="" selected disabled>Select Reviewer</option>
                                            @if(count($closurereviewers)> 0 )
                                                @foreach($closurereviewers as $closurereviewer)
                                                    <option value="{{$closurereviewer->user->id}}" {{ $data->closurereviewer_id == $closurereviewer->user->id ? 'selected' : '' }}> {{ $closurereviewer->user->name }}</option></br>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="impl_status" style="font-size:13px;">Action Status</label>
                                @php
                                    if($data->actionstatus=='Not Started-0%'){
                                        $bgcolor_0='#D32F2F';
                                        $bgcolor_25=$bgcolor_50=$bgcolor_100='';
                                        $color_0='#000';
                                        $color_25=$color_50=$color_100='#5179d6';
                                    }elseif($data->actionstatus=='Progress-25%'){
                                        $bgcolor_0 = $bgcolor_25='#F57C00';
                                        $bgcolor_50=$bgcolor_100='';
                                        $color_25=$color_0='#000';
                                        $color_50=$color_100='#5179d6';
                                    }elseif($data->actionstatus=='Partially Completed-50%'){
                                        $bgcolor_0 = $bgcolor_25=$bgcolor_50='#FFC107';
                                        $bgcolor_100='';
                                        $color_0 = $color_25=$color_50='#000';
                                        $color_100='#5179d6';
                                    }elseif($data->actionstatus=='Completed-100%'){
                                        $bgcolor_0 = $bgcolor_25=$bgcolor_50=$bgcolor_100='#4CAF50';
                                        $color_0 = $color_25=$color_50=$color_100='#000';
                                    }else{
                                        $bgcolor_0 = $bgcolor_25=$bgcolor_50=$bgcolor_100='';
                                        $color_0 = $color_25=$color_50=$color_100='';
                                    }
                                @endphp
                                <div class="btn-group btn-group-toggle col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-sm btn-outline-primary disabled" id="btn-not-started" style="font-size: 10px;background-color:{{$bgcolor_0}};color:{{$color_0}};" aria-disabled="true">
                                        Not started-0%
                                    </label>
                                    <label class="btn btn-sm btn-outline-primary disabled" id="btn-progress" style="font-size: 10px;background-color:{{$bgcolor_25}};color:{{$color_25}};" aria-disabled="true">
                                        Progress-25%
                                    </label>
                                    <label class="btn btn-sm btn-outline-primary disabled" id="btn-partially-completed" style="font-size: 10px;background-color:{{$bgcolor_50}};color:{{$color_50}};" aria-disabled="true">
                                        Partially completed-50%
                                    </label>
                                    <label class="btn btn-sm btn-outline-primary disabled" id="btn-completed" style="font-size: 10px;background-color:{{$bgcolor_100}};color:{{$color_100}};" aria-disabled="true">
                                    Completed-100%
                                    </label>
                                </div>

                            </div>
                            <div class="col-sm-12 text-right pr-4">
                                <button type="submit" for="submit"  class="btn btn-primary" data-toggle="click-ripple"  onclick="return confirm('Do you want to proceed ? ')" style="font-size: 13px;" >Submit</button>
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

                        <div class="col-6">
                            <a class="block block-rounded block-link-shadow bg-body" href="{{ route('opportunity.oppindex') }}">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Register
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="col-6">
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('opportunity.oppaccomplished') }}">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-check-square fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Accomplished
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="col-6">
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('opportunity.oppanalysis') }}">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let department = '{{ $data->department }}';
    let area_id = '{{$data->area_id}}';
    let show_area = 0;
    let area_data = [];
    let area_selector = $('#area');
    let area_select_selector = $('#area_select');
    let division_selector = $('#division');
    let division_select_selector = $('#division_select');
    let division_select = '';
    area_select_selector.val(area_id);
    area_selector.removeClass('d-none').addClass('d-inline-block');
    chooseDivision($('#department').val(), $('#division_val').val());

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
                        //if (id != department) if_selected = '';
                        option_text += `<option value="${resp.data[i].id}" ${if_selected}>${resp.data[i].name}</option>`
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
