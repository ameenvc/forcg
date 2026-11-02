@extends('admin.datatable')
@section('css_before')
<style>
    .badge-class {
        display: inline-block;
        padding: 7px 5px 5px 7px;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .close {
        line-height: 0.75;
    }
</style>
@endsection
@section('content')
<div class="content">
    @if(Session::has('message'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('message')!!}
    </div>
    @endif
    @if(Session::has('delmessage'))
    <div class="alert alert-success">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('delmessage')!!}
    </div>
    @endif
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.01.01 Types Table</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page" >
                        Audit Management
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <button class="btn btn-primary text-right ml-auto d-flex" style="font-size:10px" data-toggle="modal" data-target="#AddTypeModal" {{ count($types) < 3 ? '' : 'disabled'}}>Add</button>
            <div id="AddTypeModal" class="modal fadebd-example-modal-lg" aria-labelledby="AddTypeModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="AddTypeModalLabel">Add Type</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        {{-- <form id="add_type_form" enctype="multipart/form-data"> --}}

                        <div class="modal-body m-body p-4">

                            <div class="alert alert-success d-none w-100" id="success_type_msg" style="font-size:12px;">
                                Data added successfully
                            </div>

                            <ul class="alert alert-danger d-none w-100" id="error_type_msg" style="font-size:12px;">
                                Internal server error, we are trying to fix them soon
                            </ul>

                            <ul class="alert alert-warning d-none w-100" id="validation_type_error_msg" style="font-size:12px;"></ul>

                            <div class="form-group">
                                <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;"> Type <span style="color:red">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="type" placeholder="Enter type" required name="type">
                                </div>
                            </div>

                            <div class="form-group" style="font-size:12px;">
                                <label class="col-sm-12 col-form-label text-black">Observation <span style="color:red">*</span></label>
                                <div class="custom-control custom-radio mb-1 ml-3">
                                    <input type="radio" class="custom-control-input" id="major" name="observation" checked>
                                    <label class="custom-control-label" for="major">Major Observation</label>
                                </div>
                                <div class="custom-control custom-radio mb-1 ml-3">
                                    <input type="radio" class="custom-control-input" id="moderate" name="observation">
                                    <label class="custom-control-label" for="moderate">Moderate Observation</label>
                                </div>
                                <div class="custom-control custom-radio mb-1 ml-3">
                                    <input type="radio" class="custom-control-input" id="minor" name="observation">
                                    <label class="custom-control-label" for="minor">Minor Observation</label>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                            <button type="button" class="btn btn-primary" style="font-size:12px" id="submit_type">Submit</button>
                        </div>

                        {{-- </form> --}}
                    </div>
                </div>
            </div>

            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <div class="alert d-none w-100 my-2" id="change_workflow_msg" style="font-size:12px;">
            </div>
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter mt-2 /*js-dataTable-full*/">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Type</th>
                        <th class="d-sm-table-cell text-left" style="width: 10%;font-size:10px;text-transform:capitalize">Description</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Manage workflow</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($types as $c)

                    {{csrf_field()}}

                    <?php $id = $c->id ?>
                    <tr>

                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>


                        <td style="font-size:10px" class="text-left">
                            {{-- <span>{{$c->name}}</span> --}}
                            <span style="font-size:10px;background-color:{{ $c->background_color }};color:{{ $c->text_color}}" class="badge-class">{{ $c->name }}</span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <span>{{$c->details}}</span>
                        </td>


                        <td style="font-size:10px" class="text-left">

                            <?php $number++; ?>


                            <div class="btn-group">
                                <a href="{{url('/admin/editType?val='.$id)}}" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                <a href="{{url('/admin/removeType?val='.$id)}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                            </div>
                        </td>
                        <td>
                            <div class="custom-control custom-switch custom-control-primary mb-1">
                                <input type="checkbox" name="is_manage_workflow_{{$id}}" id="is_manage_workflow_{{$id}}"
                                        value="{{ $id }}"
                                        data-route="{{ route('admin.update.manage.workflow', $id) }}"
                                        class="custom-control-input js_manage_workflow"
                                        {{ $c->is_manage_workflow ? 'checked' : '' }}
                                        style="position: fixed;left: 600px">
                                <label class="custom-control-label" for="is_manage_workflow_{{$id}}"
                                        data-toggle="tooltip" title="Do you need a verification stage after adding an Audit observation"></label>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            </div>
            <div class="text-danger font-weight-bold" style="font-size:12px">
                Notes:<br>
                1- Kindly add three classifications to organize your Non-Conformance Reports(NCRs) on the Audit module.(E.g: High,Medium,Low)<br>
                2- Add any types, the first type will be considered the major type, the second as the moderate and the third as the minor category.<br>
                Each category carries a distict weight within the overall scoring alghorithm.
            </div>

        </div>

        {{-- <br><br>
        <div class="row" style="margin-left:80px">
            <div class="col-lg-10"> --}}
                {{-- <form method="post" action="{{url('/admin/addType')}}" onsubmit="return validation()"> --}}
                    {{csrf_field()}}

                    {{-- @if(Session::has('msg'))
                    <div class="alert alert-primary">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                    @endif --}}

                    {{-- <div class="col-lg-8" style="margin-left:10px;margin-top:-10px"> --}}
                        {{-- <div class="form-group row">
                            <div class="col-sm-8">
                                <input type="text" class="form-control" style="font-size: 13px;margin-left:370px" id="example-text-input" placeholder="Enter type" required name="type">
                            </div>
                        </div> --}}

                        {{-- <div class="form-group row" style="margin-top:-78px">
                            <div class="col-md-3  col-xl-2">
                                <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;margin-left:750px;margin-top:21px" value="Add">
                            </div>
                        </div> --}}
                    {{-- </div> --}}
                {{-- </form> --}}
            {{-- </div> --}}
            <!-- END Dynamic Table Full -->


        {{-- </div> --}}

    </div>
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.01.02 Standards Table</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page" >
                        Audit Management
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Standards</th>

                            {{-- <th class="text-left"style="width: 5%;font-size:10px;text-transform:capitalize" >Actions</th> --}}


                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($standards as $sc)

                        {{csrf_field()}}

                        <?php $id = $sc->id ?>
                        <tr>

                            <td style="font-size:10px" class="text-left">
                                <span>{{ $number }}</span>
                            </td>


                            <td style="font-size:10px" class="text-left">
                                <span>{{$sc->name}}</span>
                            </td>

                            {{-- <td style="font-size:10px" class="text-left"> --}}

                            <?php $number++; ?>


                            {{-- <div class="btn-group">


                                            <a href="{{url('/admin/editstandard?val='.$id)}}" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <a href="{{url('/admin/removestandard?val='.$id)}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>


                            </td> --}}
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="{{url('/admin/addstandard')}}" onsubmit="return validation()">
                        {{csrf_field()}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" style="font-size: 13px" id="example-text-input" placeholder="Enter standard" required name="standard">
                                <div class="input-group-append">
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:12px" value="Add">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.01.03 ISO Clauses Table</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page" >
                        Audit Management
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Department Name</th>
                        <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">ISO Clauses</th>
                        <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>


                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($departments as $c)

                    {{csrf_field()}}

                    @php
                    $id = $c->id ;
                    $dept=$c->departmentname;
                    $isoclauses=$c->isoclauses;
                    @endphp
                    <tr>

                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>


                        <td style="font-size:10px" class="text-left">
                            <span>{{$c->departmentname}}</span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <span><?php echo nl2br($c->isoclauses)?></span>
                        </td>

                        <?php $number++; ?>

                        <td class="text-left">
                            <div class="btn-group">
                                <a href="{{url('/admin/editisoclauses?val='.$id.'&dept='.$dept.'&isoclauses='.$isoclauses)}}" style="font-size:10px;" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            </div>
        </div>
    </div>
    <!--conformance table starts -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.01.04 Conformance Table</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page" >
                        Audit Management
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
            <table class="table table-borderless">
                <tr>
                    <td align="right">
                        <a href="{{route('addauditchecklistconformance')}}" class="btn btn-primary btn-sm">Add</a>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-left" style="width:2%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Stage</th>
                        <th class="d-sm-table-cell text-left" style="width: 15%;font-size:10px;text-transform:capitalize">Classification</th>
                        <th class="d-sm-table-cell text-left" style="width: 15%;font-size:10px;text-transform:capitalize">Description</th>
                        <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">Actions</th>


                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($conformances as $conformance)

                    {{csrf_field()}}
                    <tr>

                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>


                        <td style="font-size:10px" class="text-left">
                            <span>{{$conformance->stage}}</span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <span><?php echo nl2br($conformance->classification)?></span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <span><?php echo nl2br($conformance->description)?></span>
                        </td>

                        <?php $number++; ?>

                        <td class="text-left">
                            <div class="btn-group">

                                <a href="{{route('editauditconformance',['id'=>$conformance->id])}}" style="font-size:10px;" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            </div>
        </div>
    </div>
    <!--conformance table ends  -->

    <!--root cause and corrective action table starts -->
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">MS.01.05 Root Cause and Corrective Action Table</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page" >
                        Audit Management
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full" style="width:84%;">
            <div class="table-responsive">
            <table class="table table-borderless">
                <tr>
                    <td align="right">
                        <a href="{{route('addauditaddrootcorrective')}}" class="btn btn-primary btn-sm">Add</a>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-left" style="width:2%;font-size:10px;text-transform:capitalize">SL No.</th>
                        <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Title</th>
                        <th class="d-sm-table-cell text-left" style="width: 15%;font-size:10px;text-transform:capitalize">Root Cause</th>
                        <th class="d-sm-table-cell text-left" style="width: 15%;font-size:10px;text-transform:capitalize">Corrective Action</th>
                        <th class="text-left" style="width: 2%;font-size:10px;text-transform:capitalize">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($rootcorrectives as $rootcorrective)

                    {{csrf_field()}}
                    <tr>

                        <td style="font-size:10px" class="text-left">
                            <span>{{ $number }}</span>
                        </td>


                        <td style="font-size:10px" class="text-left">
                            <span>{{$rootcorrective->title}}</span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <span>{{$rootcorrective->root_cause}}</span>
                        </td>

                        <td style="font-size:10px" class="text-left">
                            <!-- <span><?php //echo nl2br($rootcorrective->corrective_action)?></span> -->
                            <?php
                        $points = $rootcorrective->corrective_action;
                        $point_arrays = explode("\n",$points);
                    ?>
                            <ul>
                                @foreach($point_arrays as $point_array)
                                <li style="margin-bottom:2px;position:relative;left: -12px;">{{$point_array}}</li>
                                @endforeach
                            </ul>
                        </td>

                        <?php $number++; ?>

                        <td class="text-left">
                            <div class="btn-group">
                                <a href="{{route('editauditrootcorrective',['id'=>$rootcorrective->id])}}" style="font-size:10px;" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                                </a>
                                <a href="{{route('deleteauditrootcorrective',['id'=>$rootcorrective->id])}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            </div>
        </div>
    </div>
    <!--root cause and corrective action table ends  -->
</div>
@endsection
@section("js_after")
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let add_type_url = "{{ route('admin.addType') }}";

    $('#submit_type').on('click', function(e) {
        e.preventDefault();
        let success_selector = $('#success_type_msg');
        let error_selector = $('#error_type_msg');
        let validation_error_selector = $('#validation_type_error_msg');

        $.ajax({
            data: {
                type: $('#type').val()
                , observation: $("input[name=observation]:checked").attr('id')
                , _token: CSRF_TOKEN
            , }
            , url: add_type_url
            , type: "POST"
            , success: function(response) {
                if (response.error != null) {
                    success_selector.removeClass('d-block');
                    success_selector.addClass('d-none');

                    error_selector.removeClass('d-block');
                    error_selector.addClass('d-none');

                    validation_error_selector.addClass('d-block')
                    validation_error_selector.removeClass('d-none');
                    validation_error_selector.html('<li style="font-size:12px;">' + response.error + '</li>');
                } else {
                    success_selector.removeClass('d-none');
                    success_selector.addClass('d-block')

                    error_selector.removeClass('d-block');
                    error_selector.addClass('d-none')

                    validation_error_selector.removeClass('d-block');
                    validation_error_selector.addClass('d-none');

                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }

            }
            , error: function(err) {
                validation_error_selector.text('');
                if (err.status === 500) {
                    error_selector.removeClass('d-none');
                    error_selector.addClass('d-block');

                    validation_error_selector.removeClass('d-block')
                    validation_error_selector.addClass('d-none');

                    success_selector.removeClass('d-block');
                    success_selector.addClass('d-none');
                } else if (err.status === 422) {
                    success_selector.removeClass('d-block');
                    success_selector.addClass('d-none');

                    error_selector.removeClass('d-block');
                    error_selector.addClass('d-none');

                    validation_error_selector.addClass('d-block')
                    validation_error_selector.removeClass('d-none');

                    var error_text = '';
                    $.each(err.responseJSON.errors, function(i) {
                        $.each(err.responseJSON.errors[i], function(key, val) {
                            error_text += '<li style="font-size:12px;">' + val + '</li>';
                        });
                    });
                    validation_error_selector.html(error_text);
                }

            }
        , });

    });
    
    $('.js_manage_workflow').on('change', function (e) {
        e.preventDefault();
        let change_workflow_msg_element = $('#change_workflow_msg');
        const url = $(this).attr('data-route')
        const closeBtn = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
        $.ajax({
            data: {
                _token: CSRF_TOKEN
            }
            , url: url
            , type: "POST"
            , success: function(response) {
                if (response.success) {
                    change_workflow_msg_element.removeClass('d-none');
                    change_workflow_msg_element.addClass('d-block');
                    change_workflow_msg_element.addClass('alert-success');
                    change_workflow_msg_element.html(closeBtn + response.message)
                } else {
                    change_workflow_msg_element.removeClass('d-none');
                    change_workflow_msg_element.addClass('d-block');
                    change_workflow_msg_element.addClass('alert-danger');
                    change_workflow_msg_element.html(closeBtn + response.message)
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            }, error: function(err) {
                let error_text = '';
                $.each(err.responseJSON.errors, function(i) {
                    $.each(err.responseJSON.errors[i], function(key, val) {
                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                    });
                });
                change_workflow_msg_element.removeClass('d-none');
                change_workflow_msg_element.addClass('d-block');
                change_workflow_msg_element.addClass('alert-danger');
                change_workflow_msg_element.html(closeBtn + error_text)
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            }
        })
    })

</script>
@endsection
