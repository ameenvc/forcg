@extends('layouts.multiselect')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">HSE Observations</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Reopen</a>
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
                    <form class="mb-5" action="{{url('/user/hseobservation/reopen')}}" method="post">
                        {{csrf_field()}}
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

                        <?php $rr=$data->id ?>
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <input type="hidden" name="posted" value="{{$data->id}}">
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;" for="raised">Issue id</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" style="font-size:13px;" name="name" value="{{$data->issueid}}" readonly="">
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Raised by</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" style="font-size:13px;" value="{{$data->postedby}}" readonly="">
                            </div>
                        </div>


                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="title" name="title" style="font-size:13px;" value="{{$data->title}}">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Category</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="category" name="category" style="font-size:13px;" value="{{$data->category}}" readonly="">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="department" id="department" required="true" style="font-size:13px;" onchange="chooseDivision($(this).val(), $('#division_val').val())">
                                    @if(count($departments)>0)
                                    @foreach($departments as $dep)
                                    <option value="{{$dep->id}}" style="font-size:13px;" {{ $dep->departmentname == $data->department ? 'selected' : '' }}> {{$dep->departmentname}}</option></br>
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


                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="type" required="true" style="font-size:13px;">
                                    @if(count($type)>0)
                                    @foreach($type as $typ)
                                    <option value="{{$typ->id}}" {{$typ->id==$data->type?'selected':''}} style="font-size:13px;">{{$typ->hsetype}}</option></br>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>


                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation</label>
                            <div class="col-sm-12">
                                <textarea type="text" class="form-control" id="observation" style="font-size:13px;" name="observation" placeholder="Enter observation">{{$data->observation}}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended action</label>
                            <div class="col-sm-12">
                                <textarea type="text" class="form-control" id="agreedaction" style="font-size:13px;" name="agreedaction">{{$data->agreedaction}}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="responsibility" style="font-size:13px;">Responsibility<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="responsibility" required="true" style="font-size:13px;">
                                    @if(count($user)> 0 )
                                    @foreach($user as $use)
                                    <option value=" {{$use->email}}" style="font-size:13px;"> {{ $use->name }}</option><br>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="notification" style="font-size:13px;">Send notifications to:</label>
                            <div class="col-sm-12">
                                <select class="js-select2 form-control" style="font-size:13px;" id="example-select2-multiple" name="notification[]" style="width: 100%;" data-placeholder="Choose many.." multiple>
                                    @if(count($user)> 0 )
                                    @foreach($user as $use)
                                    <option value=" {{$use->email}}" style="font-size:13px;"> {{ $use->name }}</option><br>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Due date</label>
                            <div class="col-sm-12">
                                <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="date" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{$data->duedate}}">
                            </div>
                        </div>


                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="correction" style="font-size:13px;">Correction</label>
                            <div class="col-sm-12">
                                <textarea rows="3" class="form-control" id="correction" style="font-size:13px;" name="correction" readonly="">{{$data->correction}}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="rootcause" style="font-size:13px;">Root cause</label>
                            <div class="col-sm-12">
                                <textarea rows="3" class="form-control" id="rootcause" style="font-size:13px;" name="rootcause" readonly="">{{$data->rootcause}}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98.5%;">
                            <label class="col-sm-12 col-form-label" for="correctiveaction" style="font-size:13px;">Corrective action</label>
                            <div class="col-sm-12">
                                <textarea rows="3" class="form-control" id="correctiveaction" style="font-size:13px;" name="correctiveaction" readonly="">{{$data->correctiveaction}}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 col-form-label" for="download" style="font-size:12px;">Attached file</label>
                            <div class="col-sm-8">
                                <button type="button" class="btn btn-primary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $data->id }}" data-target="#viewModal{{$data->id}}"><strong>Attachments&nbsp(Issues)</strong> </button>
                                <div id="viewModal{{$data->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title">Attachments</h3>
                                            </div>

                                            <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                <div class="row justify-content-center">
                                                    <thead>
                                                        <tr class="table-secondary">
                                                            <th>S.No</th>
                                                            <th>Attachments (issues)</th>
                                                            <th></th>

                                                        </tr>
                                                    </thead>
                                                    <?php $o=0;?>
                                                    @if( count($rec1) > 0)
                                                    @foreach($rec1 as $value1)

                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size:15px;width: ;">{{ $o=$o+1}}</td>
                                                            <td style="font-size:15px;width: 100%;">{{ $value1->issue_file }}</td>
                                                            <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_hse/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a></td>
                                                        </tr>
                                                    </tbody>
                                                    @endforeach
                                                    <div class="row justify-content-center">
                                                        {{$rec1->links()}}
                                                    </div>
                                                    @else
                                                    <div class="row justify-content-center">
                                                        <h2>No Files</h2>
                                                    </div>
                                                    @endif

                                            </table>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 col-form-label" for="download" style="font-size: 12px;"></label>
                            <div class="col-sm-8">

                                <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 155px;" data-toggle="modal" data-id="" data-target="#viewModal"><strong>Attachments&nbsp(Actions)</strong> </button>
                                <div id="viewModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title">Attachments</h3>
                                            </div>

                                            <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                <div class="row justify-content-center">
                                                    <thead>
                                                        <tr class="table-secondary">
                                                            <th>S.No</th>
                                                            <th>Attachments (actions)</th>
                                                            <th></th>

                                                        </tr>
                                                    </thead>
                                                    <?php $no=0;?>

                                                    @if( count($rec2) > 0)
                                                    @foreach($rec2 as $value2)
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size:15px;width: ;">{{ $no=$no+1}}</td>
                                                            <td style="font-size:15px;width: 100%;">{{ $value2->action_file }}</td>
                                                            <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_hse/".$value2->action_file)}}" download="{{$value2->action_file}}">Download</a></td>
                                                        </tr>
                                                    </tbody>

                                                    @endforeach
                                                    <div class="row justify-content-center">
                                                        {{$rec2->links()}}
                                                    </div>
                                                    @else
                                                    <div class="row justify-content-center">
                                                        <h2>No Files</h2>
                                                    </div>
                                                    @endif
                                            </table>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" tyle="display:inline-block;width:90%;">
                            <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach action files(avoid '#' and '&' in file names)</label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" id="action_file" style="font-size: 13px;" name="action_file[]" multiple>
                            </div>
                        </div>

                        <div style=";" class="form-group">
                            <button type="submit" for="submit" class="btn btn-primary" data-toggle="click-ripple" style="font-size:13px;margin-left:87.5%;" onclick="return confirm('Are you sure to propose the actions?')">Reopen</button>
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
        $('#sel_freq').select2({});
    });
</script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let department = $('#department').val();
    let area_id = $('#area_val').val();
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
