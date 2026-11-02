@extends('admin.datatable')
@section('css_before')
<link rel="stylesheet" href="{{ asset('css/admin/departmentLocations.css') }}">
@endsection
@section('content')
<!-- Main Container -->
<main id="main-container">
    <!-- Hero -->

    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:92%;margin-left:50px;margin-top:-75px">
        <!-- Dynamic Table Full -->

        @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif
        <div class="block block-rounded">
            <div class="block-header justify-content-center">
                <h3 class="block-title">Locations Table</h3>
            </div>
            <div class="block-content block-content-full" style="width:80%;margin-left:90px">

                <div class="row">
                    @foreach($location_id_array as $key => $item)
                    <div class="col">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">{{ $key }}</h3>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div class="form-group mr-5">
                            <select class="form-control" id="department" name="department" style="width: 100%;font-size: 13px;" data-placeholder="Choose Department" onchange="filterDepartment($(this).val())">
                                <option value="0" selected>Choose Department</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}"> {{ $department->departmentname }}</option><br>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col">
                        <div class="form-group mr-5">
                            <select class="form-control" id="division" name="division" style="width: 100%;font-size: 13px;" data-placeholder="Choose Division" onchange="filterLocation($(this).val())">
                                <option value="0" selected>Choose Division</option>
                                @foreach($divisions as $division)
                                <option value="{{ $division->id }}"> {{ $division->name }}</option><br>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col">
                        <div class="form-group mr-5">
                            <select class="form-control" id="area" name="area" style="width: 100%;font-size: 13px;" data-placeholder="Choose Sub-Division" onchange="filterArea($(this).val())">
                                <option value="0" selected>Choose Sub-Division</option>
                                @foreach($areas as $area)
                                <option value="{{ $area->id }}"> {{ $area->name }}</option><br>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>


                <div class="row">
                    @foreach($location_id_array as $key => $item)
                    <div class="col">

                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full mt-0" id="{{ $item }}">
                            <thead>
                                <tr>
                                    <th class="text-left" style="width:12%;font-size:10px;text-transform:capitalize">SL
                                        No.</th>
                                    <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">
                                        Name</th>
                                    <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    @endforeach
                </div>


                <div class="row mt-2 ml-1">
                    <button type="button" class="btn btn-primary btn-sm" font-size="10px" onclick="addLocation()">Add New Location</button>
                    <div id="addLocation" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title">Add location for</h2>
                                </div>
                                <div class="alert alert-success d-none ml-3 mr-3" id="success_location">Location created successfully</div>
                                <ul class="alert alert-danger d-none ml-3 mr-3" id="danger_location"></ul>
                                {{-- <form method="post" action=""> --}}
                                {{-- @csrf --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="example-radios-default1" name="radio_select" value="department" onclick="displayDepartmentSelect()">
                                            <label class="form-check-label" for="example-radios-default1">Department</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="example-radios-default2" name="radio_select" value="division" onclick="displayDivisionSelect()">
                                            <label class="form-check-label" for="example-radios-default2">Division</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="example-radios-default3" name="radio_select" value="area" onclick="displayAreaSelect()">
                                            <label class="form-check-label" for="example-radios-default3">Sub Division</label>
                                        </div>
                                    </div>
                                    <div class="form-group d-none" id="department_radio_select">
                                        <select class="form-control" style="width: 100%;font-size: 13px;" data-placeholder="Choose Department" id="department_location_select">
                                            <option value="" selected>Choose Department</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}"> {{ $department->departmentname }}</option><br>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group d-none" id="division_radio_select">
                                        <select class="form-control" style="width: 100%;font-size: 13px;" data-placeholder="Choose Division" id="division_location_select">
                                            <option value="" selected>Choose Division</option>
                                            @foreach($divisions as $division)
                                            <option value="{{ $division->id }}"> {{ $division->name }}</option><br>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group d-none" id="area_radio_select">
                                        <select class="form-control" style="width: 100%;font-size: 13px;" data-placeholder="Choose Sub-Division" id="area_location_select">
                                            <option value="" selected>Choose Sub-Division</option>
                                            @foreach($areas as $area)
                                            <option value="{{ $area->id }}"> {{ $area->name }}</option><br>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input type="text" style="font-size: 13px;" class="form-control" id="name" name="location_name" placeholder="Location Name">
                                        </div>
                                    </div>


                                </div>


                                {{-- </form> --}}
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="saveLocation()">Save</button>
                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</main>
<!-- END Main Container -->
<script src="{{ asset('js/jquery.min.js')}}"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let location_department_url = '{{ route("admin.locations.ajaxLocationDepartments", ":id") }}';
    let location_division_url = '{{ route("admin.locations.ajaxLocationDivisions", ":id") }}';
    let location_area_url = '{{ route("admin.locations.ajaxLocationAreas", ":id") }}';
    let location_add_url = "{{ route('admin.locations.add')}}";
    let location_edit_url = "{{ route('admin.locations.edit', ':id')}}";

</script>
<script>


</script>
<script src="{{ asset('js/admin/departmentLocations.js')}}"></script>
@endsection
@section('js_after')
<script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTablewithoutSearching.js')}}"></script>
@endsection
