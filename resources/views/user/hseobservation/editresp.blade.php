@extends('layouts.backend')
@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">HSE Observations</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Edit Responsibility</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/hseobservation/issues">Back</a>
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
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" enctype="multipart/form-data" action="{{url('/user/hseobservation/submitresp')}}" method="post" files="true">
                        {{csrf_field()}}
                        <?php $rr=$data->id ?>
                        <input type="hidden" name="id" value="{{$data->id}}">
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        <div class="form-group" style="display:inline-block;width:49%">
                            <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Category</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="category" name="category" style="font-size:13px;" value="{{$data->categoryName}}" readonly="">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="department" id="department" style="font-size:13px;" required="true" placeholder="Choose a Department" onchange="chooseDivision($(this).val(), $('#division_val').val())">
                                    @if(!(App\Departments::where('id', $data->department)->exists()))
                                    <option value="{{$data->department}}" style="font-size:13px;" selected>{{$data->department}}</option>
                                    @endif
                                    @if(count($departments)>0)
                                    @foreach($departments as $dep)
                                    <option value="{{$dep->id}}" style="font-size:13px;" {{ $dep->id == $data->department ? 'selected' : ''}}>{{$dep->departmentname}}</option></br>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="division_val" value="{{ $data->division_id }}" />
                        <input type="hidden" id="area_val" value="{{ $data->area_id }}" />

                        <div class="form-group d-inline-block" style="width:49%;" id="division">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                            <div class="col-sm-12">
                                <select class="form-control" id="division_select" name="division" style="font-size: 13px;" placeholder="Division" onchange="chooseArea($(this).val(), $('#area_val').val())">
                                    <option value="0" disabled>Choose Division</option></br>
                                </select>
                            </div>
                        </div>

                        <div class="form-group d-inline-block" style="width:49%;" id="area">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                            <div class="col-sm-12" id="action_number" name="action">
                                <select class="form-control" id="area_select" name="area" style="font-size: 13px;" placeholder="Sub Division">
                                    <option value="0" disabled>Choose Sub Division</option></br>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%">
                            <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="type" name="type" style="font-size:13px;" value="{{$type}}" readonly="">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%">
                            <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="title" style="font-size:13px;" name="title" value="{{$data->title}}" readonly>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%">
                            <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Due Date</label>
                            <div class="col-sm-12">
                                <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="date" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{$data->duedate}}" readonly>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%">
                            <label class="col-sm-12 col-form-label" for="impl_status" style="font-size:13px;">Implementation Status</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="impl_status" style="font-size:13px;" name="impl_status" value="{{$data->impl_status}}" readonly="">
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%">
                            <label class="col-sm-12 col-form-label" for="impl_status" style="font-size:13px;">Responsibility<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="resp" required="true" style="font-size:13px;">
                                    <option value=" {{$data->responsibility}}" style="font-size:13px;">{{$resname}}</option></br>
                                    @if(count($user)>0)
                                    @foreach($user as $u)
                                    @if($data->responsibility != $u->email)
                                    <option value=" {{$u->email}}" style="font-size:13px;"> {{$u->name}}</option></br>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:82%;" onclick="return confirm('Do you want to proceed ? ')">Submit</button>
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
@endsection
@section('js_after')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let department = $('#department').val();
    let area_id = $('#area_id').val();
    let show_area = 0;
    let area_data = [];
    let area_selector = $('#area');
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
