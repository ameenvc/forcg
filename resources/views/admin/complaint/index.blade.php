@extends('admin.datatable')
@section('content')
<style>
    .container {
        padding-left: 20px;
        padding-top: 10px;
        padding-right: 20px;
        padding-bottom: 0;
        background-color: #f2f2f2;
        border-radius: 5px;
    }

    h4,h5{
        font-size: 12px;
        margin-bottom: 5px;
    }

    ul {
        margin-top: 0;
        padding-left: 20px;
    }

    li {
        list-style-type: disc;
        margin-bottom: 5px;
        font-size: 12px;
    }
    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {opacity: 0.7;}

    /* The Modal (background) */
    .modal1 {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
    }

    /* Modal Content (image) */
    .modal1-content {
        margin: auto;
        display: block;
        width: 1200px;
        max-width: 1900px;
        height:auto;
    }

    /* Caption of Modal Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation */
    .modal1-content, #caption {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)}
    to {-webkit-transform:scale(1)}
    }

    @keyframes zoom {
    from {transform:scale(0)}
    to {transform:scale(1)}
    }

    /* The Close Button */
    #myModal > .close {
        position: relative;
        top: 2px;
        right: 125px;
        /*margin-right:100px;*/
        color: white;
        font-size: 20px;
        font-weight: normal;
        transition: 0.3s;
    }

    #myModal > .close:hover,
    #myModal > .close:focus {
        color: white;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
        .modal1-content {
            width: 100%;
        }
    }
    /* option for category plant dependency section start here */
    .toggle-group {
        display: inline-flex;
        border: 1px solid #e8ecee;
        border-radius: 8px;
        overflow: hidden;
        white-space: nowrap; /* 🔥 prevents line break */

    }

    .toggle-group input[type="radio"] {
        display: none;
    }

    .toggle-group label {
        padding: 8px 16px;
        cursor: pointer;
        font-size: 13px;
        background: #f8f9fa;
        color: #495057;
        transition: 0.2s;
        border-right: 1px solid #e8ecee;
        margin-bottom: 0%;
    }
    /* 🔥 Hover Effect */
    .toggle-group label:hover {
        background-color: #e9f3ff;
        border-color: #007bff;
        color: #007bff;
    }

    .toggle-group label:last-child {
        border-right: none;
    }

    .toggle-group input[type="radio"]:checked + label {
        background: #5179d6;
        color: #fff;
        font-weight: 500;
    }

    .toggle-group input[type="radio"]:checked + label:hover {
        background: #3f63b8;   /* darker, richer blue */
    }
</style>
<div class="content" style="width:92%;">
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
    @if(Session::has('err'))
    <div class='alert alert-warning'>
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('err')!!}
    </div>
    @endif
    <div id="flashMessageBox"></div>

    <div class="block block-rounded">
        <div class="block-content block-content-full" style="width:85%;">
            <h3 class="block-title">Category–Plant Dependency</h3>
            <div style="border:1px solid #e8ecee; border-radius:8px; padding:15px; background:#fff;">
                <div style="display:flex; justify-content:space-between; align-items:center; gap:20px;">

                    <div style="flex:1;">
                        <span style="font-size: 13px;">
                            Control how complaint categories are associated with plants. Linked mode filters categories based on the selected plant.
                        </span>
                    </div>

                    <div class="toggle-group" style="flex-shrink:0;">
                        @foreach($category_plant_dependency_options as $index => $option)
                            <input
                                type="radio"
                                class="category_plant_dependency"
                                name="category_plant_dependency"
                                id="option_{{ $index }}"
                                value="{{ $option }}"
                                {{ $option == $category_plant_dependency->meta_value ? "checked" : '' }}
                            >
                            <label for="option_{{ $index }}">
                                {{ $option }}
                            </label>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-content block-content-full" style="width:85%;">
            <h3 class="block-title">MS.10.01 Complaint Category Table</h3>
            <div class="alert alert-primary d-none mt-2" style="margin-bottom:-15px;" id="uploadSuccess">

            </div>
            <div class='alert alert-warning d-none mt-2' style="margin-bottom:-15px;" id="uploadFailed">
            </div>
            <label for="Upload Categories" class="mt-4" style="font-size: 14px;">Upload Categories</label>
            <div class="input-group mb-1" style="display: flex;width:50%;">
                <input type="file" class="form-control form-control-alt" id="category_details" name="category_details" style="font-size:11px;" required>
                <button type="button" class="btn btn-primary input-group-append" id="upload" style="font-size:11px;" onclick="uploadExcel()">Upload</button>
                <a href="#" class="button btn btn-warning font-weight-normal ml-2 mr-3" style="font-size:11px;font-family:Arial, Helvetica, sans-serif;" data-toggle="modal" data-target="#note">Note (*important)</a>
            </div>
            <button class="btn btn-primary text-right ml-auto d-flex mb-1" style="font-size:10px" data-toggle="modal" data-target="#AddCategoryModal">Add</button>

            <div id="AddCategoryModal" class="modal fadebd-example-modal-lg" aria-labelledby="AddCategoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title text-white">Add Category</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>


                        <div class="alert alert-success" id="successBtn" style="display:none;font-size:12px;">Category Name added successfully!</div>
                        <ul class="alert alert-danger" id="dangerBtn" style="display:none;font-size:12px;"></ul>
                        <form id="form_id" method="post">
                            @csrf
                            <div class="modal-body m-body p-2">
                                <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Category Name <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="catname" placeholder="Enter Category Name" required name="catname">
                                    </div>
                                </div>
                                @if ($category_plant_dependency->meta_value == "Linked to Plant")
                                    <div class="form-group col-sm-6">
                                        <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Plant <span style="color:red">*</span></label>
                                        <div class="col-sm-12">
                                            <select class="js-select2 form-control" style="font-size: 13px;width: 100%;" id="plant" name="plant" required>
                                                <option value="" selected disabled>Select Plant</option>
                                                @if(count($plants)> 0 )
                                                @foreach($plants as $plant)
                                                <option value="{{$plant->id}}">{{ $plant->plant_name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                </div>
                            </div>
                            @if ($category_plant_dependency->meta_value == "Linked to Plant")
                                <div class="container">
                                    <h4>Example: Categories Listed Under Plants</h4>
                                    <div class="row">
                                    <div class="col-sm-6">
                                        <h5>Plant A - CABLE</h5>
                                        <p style="font-size:12px;margin:5px;"><strong>Complaint Categories:</strong></p>
                                        <ul>
                                            <li>Cable shorted</li>
                                            <li>Cable snaky</li>
                                            <li>Color variation</li>
                                            <li>Wrong diameter</li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6">
                                        <h5>Plant B - CONDUIT</h5>
                                        <p style="font-size:12px;margin:5px;"><strong>Complaint Categories:</strong></p>
                                        <ul>
                                            <li>Wrong placing</li>
                                            <li>Conduit breaking</li>
                                            <li>Shortage</li>
                                            <li>Wrong item</li>
                                        </ul>
                                    </div>
                                    </div>
                                </div>
                            @endif
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                <button type="button" class="btn btn-primary" style="font-size:12px" onclick="saveCategory()">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter table-bordered" style="border:1px solid #e8ecee;">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 5%;font-size:13px;text-transform:capitalize;z-index:10;top:0;position:sticky;background:#ededed;">#</th>
                            <th class="d-sm-table-cell text-left" style="width: 80%;font-size:13px;text-transform:capitalize;z-index:10;top:0;position:sticky;background:#ededed;">Category Name</th>
                            <th class="text-left" style="width: 15%;font-size:13px;text-transform:capitalize;z-index:10;top:0;position:sticky;background:#ededed;">Actions</th>
                        </tr>
                    </thead>
                    @if ($category_plant_dependency->meta_value == "Linked to Plant")
                        <tbody>
                            <?php $number = 1; ?>
                            @foreach($plants as $p)

                                {{csrf_field()}}

                                <?php
                                    $plant = $p->id;
                                    $plantname = DB::table('complaint_plants')->where('id',$plant)->value('plant_name');
                                    $comp_category = App\ComplaintCategory::where('plant_id',$plant)->where('is_deleted',false)->get();
                                ?>
                                @if(count($comp_category)>0)
                                    <tr>
                                        <td colspan="3" style="font-size:15px;">Plant: {{$plantname}}</td>
                                    </tr>
                                    <?php $secondary = 1; ?>
                                    @foreach($comp_category as $c)
                                    <?php $id = $c->id; $name = $c->category_name; ?>
                                    <tr>
                                        <td style="font-size:13px;" class="text-left">
                                            <span>{{ $number }}.{{$secondary}}</span>
                                        </td>
                                        <td style="font-size:13px;" class="text-left">
                                            {{ $name }}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-sm btn-alt-primary" style="font-size:13px" data-toggle="tooltip" onclick="editCategory('{{$id}}','{{$name}}','{{$plant}}')" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                                <a class="btn btn-sm btn-alt-primary" onclick="deleteCategory('{{$id}}')" style="font-size:13px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $secondary++; ?>
                                    @endforeach
                                    <?php $number++; ?>
                                @endif
                            @endforeach
                        </tbody>
                    @elseif($category_plant_dependency->meta_value == "Independent")
                        <tbody>
                            @foreach ($comp_category as $index => $c)
                                <tr>
                                    <td style="font-size:13px;" class="text-left">{{ $index+1 }}</td>
                                    <td style="font-size:13px;" class="text-left">{{ $c->category_name }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-alt-primary" style="font-size:13px" data-toggle="tooltip" onclick="editCategory('{{$c->id}}','{{$c->category_name}}')" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                            <a class="btn btn-sm btn-alt-primary" onclick="deleteCategory({{$c->id}})" style="font-size:13px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif

                </table>
            </div>

        </div>
    </div>

</div>
<!-- EDIT MODAL START -->
<div id="EditCategoryModal" class="modal fadebd-example-modal-lg" aria-labelledby="EditCategoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title text-white">Edit Category</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="alert alert-success" id="successBtn1" style="display:none;font-size:12px;">Category Name updated successfully!</div>
            <ul class="alert alert-danger" id="dangerBtn1" style="display:none;font-size:12px;"></ul>
            <form id="form_id" method="post">
                @csrf
                <input type="hidden" class="form-control" style="font-size: 13px;" id="id" required name="id" value="">
                <div class="modal-body m-body p-0">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Category Name <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" id="cat_name" placeholder="Enter Category Name" required name="cat_name">
                            </div>
                        </div>
                        @if ($category_plant_dependency->meta_value == "Linked to Plant")
                            <div class="form-group col-sm-6">
                                <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Plant <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" style="font-size: 13px;width: 100%;" id="plant_id" name="plant" required>
                                        <option value="" selected disabled>Select Plant</option>
                                        @if(count($plants)> 0 )
                                        @foreach($plants as $plant)
                                        <option value="{{$plant->id}}">{{ $plant->plant_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($category_plant_dependency->meta_value == "Linked to Plant")
                    <div class="container">
                        <h4>Example: Categories Listed Under Plants</h4>
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>Plant A - CABLE</h5>
                                <p style="font-size:12px;margin:5px;"><strong>Complaint Categories:</strong></p>
                                <ul>
                                    <li>Cable shorted</li>
                                    <li>Cable snaky</li>
                                    <li>Color variation</li>
                                    <li>Wrong diameter</li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <h5>Plant B - CONDUIT</h5>
                                <p style="font-size:12px;margin:5px;"><strong>Complaint Categories:</strong></p>
                                <ul>
                                    <li>Wrong placing</li>
                                    <li>Conduit breaking</li>
                                    <li>Shortage</li>
                                    <li>Wrong item</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                @endif
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary" style="font-size:12px" onclick="updateCategory()">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- EDIT MODAL END -->
{{-- Update Category Plant Mapping Modal starts here --}}
<div id="CategoryPlantMappingModal" class="modal fadebd-example-modal-lg" aria-labelledby="CategoryPlantMappingModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title text-white">Category Plant Mapping</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="alert alert-success" id="successBtn2" style="display:none;font-size:12px;">Category Plant Mapping done successfully!</div>
            <ul class="alert alert-danger" id="dangerBtn2" style="display:none;font-size:12px;"></ul>

            <form id="CategoryPlantMappingForm">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-vcenter table-bordered" style="border:1px solid #e8ecee;">
                        <thead>
                            <tr>
                                <th class="d-sm-table-cell text-left" style="width: 70%;font-size:13px;text-transform:capitalize;z-index:10;top:0;position:sticky;background:#ededed;">Category Name</th>
                                <th class="text-left" style="width: 30%;font-size:13px;text-transform:capitalize;z-index:10;top:0;position:sticky;background:#ededed;">Assigned Plants</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comp_category_with_no_plant as $c)
                                <tr style="height: auto; display: table-row;">
                                    <td style="vertical-align: top;">{{ $c->category_name }}</td>
                                    <td style="vertical-align: top;">
                                        <select class="js-select2 form-control" style="font-size: 13px;width: 100%; padding-right: 10px" id="plant_id_{{$c->id}}" name="plant_map[{{$c->id}}]" required>
                                            <option value="" selected disabled>Select Plant</option>
                                            @if(count($plants)> 0 )
                                            @foreach($plants as $plant)
                                            <option value="{{$plant->id}}">{{ $plant->plant_name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary" style="font-size:12px" onclick="updateCategoryPlantMapping()">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
{{-- Update Category Plant Mapping Modal ends here --}}
<!-- Note starts -->
<div class="modal fade" id="note" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">*Important</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content font-size-sm">
                    <div class="table-responsive">
                        @if ($category_plant_dependency->meta_value == "Linked to Plant")
                            <span style="" style="font-size:10px;">1) The Category Details Excel Sheet Should have the Headings 'category_name, plant_name' respectively and should be in the same sequence.</span>
                            <br/><br/><span style="" style="font-size:10px;">2) Category Details Should Be In Single Excel Sheet.</span>
                            <br/><br/><span style="" style="font-size:10px;">3) Category Name and Plant Name are mandatory fields.</span>
                            <br/><br/><span style="" style="font-size:10px;">4) Plant Name must be similar to the Plants table and also present in the Plants Table.</span>
                            <br><br><span class="text-primary">Below is the Sample Excel Format, Click on the Image to view</span><br><br>

                            <!-- sample excel file image starts here -->
                            <img id="myImg" src="{{ asset('images/complaint/CategoryLinkedToPlant.png') }}" alt="Sample Excel File Format" title="Sample Excel File Format" style="width:100%;max-width:300px">
                                <!-- The Modal -->
                                    <div id="myModal" class="modal1">
                                        <span class="close"><i class="fa fa-2x fa-window-close" style="color:white;"></i></span>
                                        <img class="modal1-content" id="img01" style="height:100%;">
                                        <div id="caption"></div>
                                    </div>
                            <!--sample excel file image starts here -->
                        @elseif ($category_plant_dependency->meta_value == "Independent")
                            <span style="" style="font-size:10px;">1) The Category Details Excel Sheet Should have the Headings 'category_name'.</span>
                            <br/><br/><span style="" style="font-size:10px;">2) Category Details Should Be In Single Excel Sheet.</span>
                            <br/><br/><span style="" style="font-size:10px;">3) Category Name is Mandatory.</span>
                            <br><br><span class="text-primary">Below is the Sample Excel Format, Click on the Image to view</span><br><br>

                            <!-- sample excel file image starts here -->
                            <img id="myImg2" src="{{ asset('images/complaint/CategoryIndependent.png') }}" alt="Sample Excel File Format" title="Sample Excel File Format" style="width:100%;max-width:300px">
                            <!-- The Modal -->
                                <div id="myModal2" class="modal1">
                                    <span class="close"><i class="fa fa-2x fa-window-close" style="color:white;"></i></span>
                                    <img class="modal1-content" id="img02" style="height:100%;">
                                    <div id="caption"></div>
                                </div>
                            <!--sample excel file image ends here -->

                        @endif
                        <br><br>
                        <span class="text-danger">Warning : Please do not merge cells</span>
                        <br><br>
                    </div>
                </div>
                <div class="block-content block-content-full text-right border-top">
                    <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" style="font-size:10px;">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="font-size:10px;">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Note ends -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    function editCategory(id,catname,plant){
        $('#EditCategoryModal').modal('show');
        $('#id').val(id);
        $('#cat_name').val(catname);
        if (plant) {
            $('#plant_id').val(plant).trigger('change');
        }
    }
    function saveCategory() {
        var error_selector = $('#dangerBtn');
        var success_selector = $('#successBtn');

        function showError() {
            success_selector.addClass('d-none');
            success_selector.removeClass('d-block');

            error_selector.addClass('d-block');
            error_selector.removeClass('d-none');
        }

        function showSuccess() {
            success_selector.addClass('d-block');
            success_selector.removeClass('d-none');

            error_selector.addClass('d-none');
            error_selector.removeClass('d-block');
        }
        var formData = new FormData();
        formData.append('cat_name', $('#catname').val());
        formData.append('plant', $('#plant').val() ? $('#plant').val() : '');
        formData.append('category_plant_dependency',"{{$category_plant_dependency->meta_value}}" );
        $.ajax({
            url: "{{ url('/admin/complaint/addcategory') }}",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.error != null) {
                    showError();
                    error_selector.empty();
                    $.each(response.error, function(index, errorMessage) {
                        error_selector.append(`<li>${errorMessage}</li>`);
                    });
                } else {
                    showSuccess();
                    setTimeout(function() {
                        $('input').val("");
                        window.location.reload();
                    }, 2000);
                }
            },
            error: function(err) {
                showError();

                if (err.status === 500) {
                    error_selector.empty().append(
                        `<li>Some server errors happened, we are trying to fix them soon.</li>`
                    )
                } else if (err.status === 422) {
                    for (let i in err.responseJSON.errors) {
                        error_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }
            }
        });
    }

    function updateCategory() {
        var error_selector = $('#dangerBtn1');
        var success_selector = $('#successBtn1');

        function showError() {
            success_selector.addClass('d-none');
            success_selector.removeClass('d-block');

            error_selector.addClass('d-block');
            error_selector.removeClass('d-none');
        }

        function showSuccess() {
            success_selector.addClass('d-block');
            success_selector.removeClass('d-none');

            error_selector.addClass('d-none');
            error_selector.removeClass('d-block');
        }
        var formData = new FormData();
        formData.append('id',$('#id').val());
        formData.append('cat_name', $('#cat_name').val());
        formData.append('plant_id', $('#plant_id').val() ?? '');
        formData.append('category_plant_dependency', '{{ $category_plant_dependency->meta_value }}')
        $.ajax({
            url: "{{ url('/admin/complaint/editcategory') }}",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.error != null) {
                    showError();
                    error_selector.empty();
                    $.each(response.error, function(index, errorMessage) {
                        error_selector.append(`<li>${errorMessage}</li>`);
                    });
                } else {
                    showSuccess();
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            },
            error: function(err) {
                showError();

                if (err.status === 500) {
                    error_selector.empty().append(
                        `<li>Some server errors happened, we are trying to fix them soon.</li>`
                    )
                } else if (err.status === 422) {
                    for (let i in err.responseJSON.errors) {
                        error_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }
            }
        });
    }
    function deleteCategory(id){
        if(confirm('Are you sure?')){
            $.ajax({
                type: 'POST',
                url: "{{ url('/admin/complaint/deletecategory') }}",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.error != null) {
                        alert('Category has not been deleted.')
                        console.error('Error deleting record: ' + error.statusText);
                    } else {
                        alert('Category has been deleted successfully.')
                        window.location.reload();

                    }
                },
                error: function(err) {
                    showError();

                    if (err.status === 500) {
                        alert('Some server errors happened, we are trying to fix them soon.')
                        console.error('Error deleting record: ' + error.statusText);
                    } else if (err.status === 422) {
                        alert('Category has not been deleted.')
                        console.error('Error deleting record: ' + error.statusText);
                    }
                }
            });
        }

    }
    function uploadExcel(){
        var formData = new FormData();
        var fileInput = $('#category_details')[0].files[0];
        var uploadFailed = $('#uploadFailed');
        var uploadSuccess = $('#uploadSuccess');
        if (fileInput) {
            formData.append('category_details', fileInput);
            formData.append('category_plant_dependency', '{{$category_plant_dependency->meta_value}}')

            $.ajax({
                url: "{{ url('/admin/complaint/addcategoryexcel') }}", // Replace with your server endpoint
                type: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    'accept': 'application/json'
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        console.log('File uploaded successfully:', response);
                        uploadFailed.addClass('d-none');
                        uploadSuccess.addClass('d-none');
                        uploadSuccess.empty().prepend(response.message+'<a class="close" data-dismiss="alert">×</a>').removeClass('d-none');
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        uploadSuccess.addClass('d-none');
                        uploadFailed.addClass('d-none');
                        if(response.errors['category_details']){
                            uploadFailed.empty().prepend(response.errors['category_details']+'<a class="close" data-dismiss="alert">×</a>').removeClass('d-none');
                        }else{
                            uploadFailed.empty().prepend(response.errors+'<a class="close" data-dismiss="alert">×</a>').removeClass('d-none');
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('File upload failed:', textStatus, errorThrown);
                    uploadSuccess.addClass('d-none');
                    uploadFailed.addClass('d-none');
                    uploadFailed.empty().prepend('Some server errors happened, we are trying to fix them soon.'+'<a class="close" data-dismiss="alert">×</a>').removeClass('d-none');
                }
            });
        } else {
            alert('Please select a file to upload.');
        }
    }

    function updateCategoryPlantDependency(selectedValue) {
        $('input[name="category_plant_dependency"][value="' + selectedValue + '"]').prop('checked', true);
        var formData = new FormData();
        formData.append('value', selectedValue);
        $.ajax({
            url: "{{ route('admin.complaint.updateComplaintCategoryPlantDependency') }}",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response.message);
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
                let message = selectedValue == "Independent" ? "Category is now set as Indpendent." : "Category is now linked to the plant."
                localStorage.setItem('flash_success', message);

            },
            error: function(err) {

            }
        });
    }

    function updateCategoryPlantMapping() {
        let isValid = true;

        // Loop through all select boxes inside the modal
        $('#CategoryPlantMappingModal select[name^="plant_map"]').each(function () {
            if (!$(this).val()) {
                isValid = false;

                // Highlight the invalid field
                $(this).next('.select2-container').find('.select2-selection').css('border', '1px solid red');

                // scroll to first error
                // $(this)[0].scrollIntoView({ behavior: 'smooth', block: 'center' });

                return false; // break loop
            } else {
                $(this).css('border', '');
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Selection',
                text: 'Please select plant for all categories'
            });
            return;
        }

        var error_selector = $('#dangerBtn2');
        var success_selector = $('#successBtn2');

        function showError() {
            success_selector.addClass('d-none');
            success_selector.removeClass('d-block');

            error_selector.addClass('d-block');
            error_selector.removeClass('d-none');
        }

        function showSuccess() {
            success_selector.addClass('d-block');
            success_selector.removeClass('d-none');

            error_selector.addClass('d-none');
            error_selector.removeClass('d-block');
        }

        let form = $('#CategoryPlantMappingForm')[0]; // ✅ DOM element
        let formData = new FormData(form);

        $.ajax({
            url: "{{ route('admin.complaint.updateComplaintCategoryPlantMapping') }}",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.error) {

                } else {
                    showSuccess();
                    console.log(response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            },
            error: function(err) {

            }
        });

        //         let obj = {};
        // formData.forEach((value, key) => {
        //     obj[key] = value;
        // });
        // console.log(obj);
        //         console.log('save mapping');
    }

    function checkWhetherCategoryExistWithoutPlant(selectedValue) {
        let categoryWithoutPlant = @json($comp_category_with_no_plant);
        if (categoryWithoutPlant.length > 0) {
            $('#CategoryPlantMappingModal').modal('show');

        } else {
            updateCategoryPlantDependency(selectedValue);
        }

    }
</script>

<script>
    $(document).ready(function() {
        // checking whether flash message stored
        let message = localStorage.getItem('flash_success');
        if (message) {
            $('#flashMessageBox').html(
                `<div class="alert alert-success">${message}</div>`
            );
            localStorage.removeItem('flash_success'); // 🧹 cleanup
            // ⏳ Auto remove after 3 seconds
            setTimeout(function() {
                $('#flashMessageBox .alert').fadeOut(500, function() {
                    $(this).remove();
                });
            }, 3000);
        }
        //

        let currentChecked = $('input[name="category_plant_dependency"]:checked').val();
        $('input[name="category_plant_dependency"]').on('click', function (e) {
            e.preventDefault(); // ❗ stops radio from changing

            let selectedValue = $(this).val();

            let complaint_exists = {{ $complaint_exists}}; // tells whether complaints added to database
            let complaint_categories_exists = {{ $complaint_categories_exists}}; // tell whether complaint categories are ther in table

            // This is the PREVIOUS value
            console.log('Before change:', currentChecked);
            console.log('Trying to change to:', selectedValue);

            // only go to next step if toggle happened
            if (currentChecked != selectedValue) {
                // Checks whether complaint exist and complaint category exists
                if(complaint_exists && complaint_categories_exists) {
                    let message = "";
                    if (selectedValue === "Independent") {
                        message = "After this change, Complaint Categories will no longer depend on Plant selection. All categories will be available for all Plants. Do you want to continue?"
                    }else if(selectedValue === "Linked to Plant") {
                        message = `Changing this setting may affect how Complaint Categories are selected when creating new complaints. Do you want to continue?`
                    }
                    Swal.fire({
                        text: message,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: `Confirm`,
                        showConfirmButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if(selectedValue == "Linked to Plant") {
                                checkWhetherCategoryExistWithoutPlant(selectedValue);
                            } else if (selectedValue == "Independent") {
                                updateCategoryPlantDependency(selectedValue);
                            }
                        }
                    });
                } else {
                    updateCategoryPlantDependency(selectedValue);
                }
            }
        });

        //Hiding old form data
        $('#CategoryPlantMappingModal').on('hidden.bs.modal', function () {
            // Reset Select2 dropdowns
            $('#CategoryPlantMappingForm .js-select2').val(null).trigger('change');
            // Hide alerts if needed
            $('#successBtn2').hide();
            $('#dangerBtn2').hide().html('');
        });
    });
</script>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("myImg");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    img.onclick = function(){
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    }
    // Get the <span> element that closes the modal
    // var span = document.getElementsByClassName("close")[0];
    var span = document.querySelector('#myModal > .close');
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
</script>
<script>
    // Get the modal
    var modal = document.getElementById("myModal2");
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("myImg2");
    var modalImg = document.getElementById("img02");
    var captionText = document.getElementById("caption");
    img.onclick = function(){
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    }
    // Get the <span> element that closes the modal
    // var span = document.getElementsByClassName("close")[0];
    var span = document.querySelector('#myModal2 > .close');
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
</script>

@endsection
