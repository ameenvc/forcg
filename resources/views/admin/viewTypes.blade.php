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
    .scrollable{
        height:98% !important;
        overflow-x: scroll;
        overflow-y: scroll;
    }
    .editable-label-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .editable-label {
        outline: none;
        transition: border-color 0.2s;
        min-width: 20px;
    }

    .editable-label[contenteditable="true"] {
        border-bottom: 2px solid #4f46e5;
        cursor: text;
        padding: 0 2px;
    }

    .edit-icon {
        font-size: 12px;
        color: #6b7280;
        opacity: 0;
        transition: opacity 0.2s;
        cursor: pointer;
        flex-shrink: 0;
    }

    .editable-label-wrapper:hover .edit-icon {
        opacity: 1;
    }
</style>
@endsection
@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Full -->

        @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">MS.05.01 Categories Table</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            NCR & Corrective Actions
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:80%;">
                <button type="button" name="update" class="btn btn-primary text-right ml-auto d-flex" style="font-size:10px;" data-toggle="modal" data-target="#AddCategoryModal">Add</button>
                <div id="AddCategoryModal" class="modal fadebd-example-modal-lg scrollable" aria-labelledby="AddCategoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="AddCategoryModalLabel">Add Category</h5>
                                <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="add_category_form" method="POST" enctype="multipart/form-data">

                                <div class="modal-body m-body p-4">

                                    <div class="alert alert-success d-none w-100" id="success_msg" style="font-size:12px;">
                                        Data added successfully
                                    </div>
                                    <div class="alert alert-danger d-none w-100" id="error_pnc" style="font-size:12px;">
                                        
                                    </div>
                                    <ul class="alert alert-danger d-none w-100" id="error_msg" style="font-size:12px;">
                                        Internal server error, we are trying to fix them soon
                                    </ul>

                                    <ul class="alert alert-warning d-none w-100" id="validation_error_msg" style="font-size:12px;"></ul>

                                    <div class="form-group d-inline-block" style="width:100%;">
                                        <label class="col-sm-12 col-form-label px-0" style="font-size:13px;">Category</label>
                                        <div class="col-sm-12 px-0">
                                            <select class="js-select2 form-control form-select w-100" name="category" id="category" style="font-size:13px;width:100%;" data-placeholder="Choose Category" onchange="changeCategory($(this).val())">
                                                <option value=""></option>
                                                @foreach($categories_array as $category)
                                                <option value="{{$category}}" style="font-size:13px;">{{ $category}}</option><br>
                                                @endforeach
                                                <option value="other" style="font-size:13px;">Other</option><br>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group d-none" style="width:100%;" id="if_other_selected">
                                        <label class="col-sm-12 col-form-label px-0" for="name" style="font-size:13px;">Name</label>
                                        <div class="col-sm-12 px-0">
                                            <input type="text" class="form-control" name="name" id="name" style="font-size:12px;">
                                        </div>
                                    </div>
                                    {{-------------------------- Start Category Based Field option for Product, Process and Vendor --------------}}
                                    <div class="form-group d-none" style="width:100%;" id="pnc-question-category">
                                        <label class="col-sm-12 col-form-label px-0" for="name" style="font-size:13px;">Do you want this category to capture category-specific details?</label>
                                        <div class="col-sm-12 px-0">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="pnc_yes" name="pnc_question" value="yes">
                                                <label class="form-check-label" for="pnc_yes" style="font-size:13px;">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="pnc_no" name="pnc_question" value="no" >
                                                <label class="form-check-label" for="pnc_no" style="font-size:13px;">No</label>
                                            </div>
                                                
                                        </div>
                                    </div>
                                    <div class="form-group d-none" style="width:100%;" id="product_non_conformity_section">
                                        <div class="col-sm-12 mb-2 px-0" style="font-size:13px;">
                                            <span class="text-danger">Note: Click the checkbox to enable the required fields.</span>
                                        </div>
                                        <div class="table-responsive">
                                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                                            <table class="table table-bordered table-striped table-vcenter">
                                                <thead>
                                                    <tr>
                                                        <th style="width:1%;"></th>
                                                        <th class="text-center" style="width:30%;font-size:12px;text-transform:capitalize">Field Name</th>
                                                        <th class="text-center" style="width:15%;font-size:12px;text-transform:capitalize">Field Type</th>
                                                        <th class="text-center" style="width:45%;font-size:12px;text-transform:capitalize">Display Label</th>
                                                        <th class="text-center" style="width:9%;font-size:12px;text-transform:capitalize">Mandatory?</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- Product Non- Conformity Row Start --}}
                                                    @foreach($productNcrConfigurationFields as $field)
                                                    <tr class="productRow">
                                                        <td style="font-size:12px;" class="text-center">
                                                            <div class="custom-control custom-checkbox d-inline-block">
                                                                <input type="checkbox" class="custom-control-input select-field" id="selectField{{ $field->id }}" name="select_field[]" value="{{ $field->id }}">
                                                                <label class="custom-control-label" for="selectField{{ $field->id }}"></label>
                                                            </div>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center">
                                                            <span>{{ $field->display_label }}</span>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <span>{{ ucfirst($field->field_type) }}</span>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <div class="col-sm-12 p-1">
                                                                <input type="text" data-old-value="{{ $field->display_label }}" name="display_label[]" placeholder="Enter Display Label" class="form-control display-label" style="font-size: 12px;width:100%;" value="{{ $field->display_label }}" disabled>
                                                            </div>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                                <input type="checkbox" name="mandatory[]" id="mandatory{{ $field->id }}" class="custom-control-input mandatory-field" value="{{$field->id}}" disabled>
                                                                <label class="custom-control-label" for="mandatory{{ $field->id }}"></label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    {{-- Product Non- Conformity Row End --}}
                                                    {{-- Process Non- Conformity Row Start --}}
                                                    @foreach($processNcrConfigurationFields as $field)
                                                    <tr class="processRow">
                                                        <td style="font-size:12px;" class="text-center">
                                                            <div class="custom-control custom-checkbox d-inline-block">
                                                                <input type="checkbox" class="custom-control-input select-field" id="selectFieldProcess{{ $field->id }}" name="select_field[]" value="{{ $field->id }}">
                                                                <label class="custom-control-label" for="selectFieldProcess{{ $field->id }}"></label>
                                                            </div>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center">
                                                            <span>{{ $field->display_label }}</span>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <span>{{ ucfirst($field->field_type) }}</span>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <div class="col-sm-12 p-1">
                                                                <input type="text" data-old-value="{{ $field->display_label }}" name="display_label[]" placeholder="Enter Display Label" class="form-control display-label" style="font-size: 12px;width:100%;" value="{{ $field->display_label }}" disabled>
                                                            </div>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                                <input type="checkbox" name="mandatory[]" id="mandatoryProcess{{ $field->id }}" class="custom-control-input mandatory-field" value="{{$field->id}}" disabled>
                                                                <label class="custom-control-label" for="mandatoryProcess{{ $field->id }}"></label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    {{-- Process Non- Conformity Row End --}}
                                                    {{-- Vendor Non- Conformity Row Start --}}
                                                    @foreach($vendorNcrConfigurationFields as $field)
                                                    <tr class="vendorRow">
                                                        <td style="font-size:12px;" class="text-center">
                                                            <div class="custom-control custom-checkbox d-inline-block">
                                                                <input type="checkbox" class="custom-control-input select-field" id="selectFieldVendor{{ $field->id }}" name="select_field[]" value="{{ $field->id }}">
                                                                <label class="custom-control-label" for="selectFieldVendor{{ $field->id }}"></label>
                                                            </div>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center">
                                                            <span>{{ $field->display_label }}</span>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <span>{{ ucfirst($field->field_type) }}</span>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <div class="col-sm-12 p-1">
                                                                <input type="text" data-old-value="{{ $field->display_label }}" name="display_label[]" placeholder="Enter Display Label" class="form-control display-label" style="font-size: 12px;width:100%;" value="{{ $field->display_label }}" disabled>
                                                            </div>
                                                        </td>
                                                        <td style="font-size:12px;" class="text-center p-0">
                                                            <div class="custom-control custom-switch custom-control-success mb-1">
                                                                <input type="checkbox" name="mandatory[]" id="mandatoryVendor{{ $field->id }}" class="custom-control-input mandatory-field" value="{{$field->id}}" disabled>
                                                                <label class="custom-control-label" for="mandatoryVendor{{ $field->id }}"></label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    {{-- Vendor Non- Conformity Row End --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{------------------ END Category Based Field option for Product, Process and Vendor ----------------------}}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal" style="font-size:12px">Close</button>
                                    <button type="submit" id="submitBtn" class="btn btn-primary" style="font-size:12px">Submit</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full mt-2">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:9%;font-size:10px;text-transform:capitalize;">SL No.</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:{{count($detectionStages)>0 ?'45%;' : '40%;'}}">Category Name</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:{{count($detectionStages)>0 ?'26%;' : '20%;'}}">Sub-Category(source) Field Required in the form?</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:{{count($detectionStages)>0 ?'15%;' : '26%;'}}">Detection Stage</th>
                                <th class="text-center" style="width:5%;font-size:10px;text-transform:capitalize">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 1; ?>
                            @foreach($categories as $c)

                            {{csrf_field()}}

                            <?php $id = $c->id ?>
                            <tr class="text-center">
                                <td style="font-size:10px;">
                                    <span style="width:5px">{{ $number }}</span>
                                </td>
                                <td style="font-size:10px;width:10px">
                                    <span>{{$c->categoryname}}</span>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch custom-control-success mb-1">
                                        <input type="checkbox" name="source{{ $c->id }}" id="source{{ $c->id }}" class="custom-control-input" value="{{ $c->id }}" onclick="enableSource('{{ $c->id }}')" {{ $c->enable_source ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="source{{ $c->id }}"></label>
                                    </div>
                                </td>
                                {{-- <td>
                                    <div class="custom-control custom-switch custom-control-success mb-1">
                                        <input type="checkbox" name="detectionStage{{ $c->id }}" id="detectionStage{{ $c->id }}" class="custom-control-input" value="{{ $c->id }}" onclick="enableDetectionStage('{{ $c->id }}')" {{ $c->enable_detection_stage && count($detectionStages)!=0 ? 'checked' : '' }} {{count($detectionStages)<=0 ? 'disabled' : ''}}>
                                        <label class="custom-control-label" for="detectionStage{{ $c->id }}" data-toggle="tooltip" title="Enable this to show Detection Stage field for this category in Add NCR page." data-placement="top"></label>
                                    </div>
                                    @if(count($detectionStages)<=0)
                                        <div>
                                            <span style="font-size:10px;" class="text-danger">No stages added yet. <a href="#" data-toggle="modal" data-target="#AddDetectionStageModal" class="text-primary">[Add Stages]</a></span>
                                        </div>
                                    @endif
                                </td> --}}
                                <td>
                                    <div class="custom-control custom-switch custom-control-success mb-1">
                                        <input type="checkbox" 
                                            name="detectionStage{{ $c->id }}" 
                                            id="detectionStage{{ $c->id }}" 
                                            class="custom-control-input detection-stage-checkbox" 
                                            value="{{ $c->id }}" 
                                            onclick="enableDetectionStage('{{ $c->id }}')" 
                                            {{ $c->enable_detection_stage && count($detectionStages) != 0 ? 'checked' : '' }} 
                                            {{ count($detectionStages) <= 0 ? 'disabled' : '' }}>
                                        <label class="custom-control-label" 
                                            for="detectionStage{{ $c->id }}" 
                                            data-toggle="tooltip" 
                                            title="Enable this to show Detection Stage field for this category in Add NCR page." 
                                            data-placement="right"
                                            @if(count($detectionStages) <= 0) onclick="showNoStageMsg({{ $c->id }})" style="cursor:pointer;" @endif
                                        ></label>
                                    </div>
                                    @if(count($detectionStages) <= 0)
                                        <div id="noStageMsg{{ $c->id }}" style="display:none;">
                                            <span style="font-size:10px;" class="text-danger">
                                                No stages added yet. 
                                                <a href="#detectionStageTable" class="text-primary scorllStageTable">[Add Stages]</a>
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td style="font-size:10px;width:10px">
                                    <div class="btn-group">
                                        <a href="{{url('/admin/editCategory?val='.$id)}}" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                        <a href="{{url('/admin/removeCategory?val='.$id)}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                        @if($c->is_pnc_cat=='yes' || $c->is_process_cat=='yes' || $c->is_vendor_cat=='yes')
                                            <a class="btn btn-sm btn-alt-primary view-field" data-id="{{$id}}" style="font-size:10px" data-toggle="tooltip" title="Click to view fields"><i class="fa fa-fw fa-info"></i></a>
                                        @endif
                                    </div>
                                </td>
                                
                                <?php $number++; ?>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="block block-rounded" id="detectionStageTable">
            <div class="block-header">
                <h3 class="block-title">
                    <span class="editable-label-wrapper" data-toggle="tooltip" title="Click to edit">
                        MS.05.02 
                        <span 
                            class="editable-label" 
                            contenteditable="false"
                            data-original="{{ $detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage' }}"
                            data-id="{{ $detectionStageLabel ? $detectionStageLabel->id : '' }}"
                        >{{ $detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage' }}</span>
                        Table
                        <i class="edit-icon fas fa-pencil-alt"></i>
                    </span>
                </h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            NCR & Corrective Actions
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:80%;">
                <button type="button" name="update" class="btn btn-primary text-right ml-auto d-flex" style="font-size:10px;" data-toggle="modal" data-target="#AddDetectionStageModal" {{count($detectionStages)>=5 ? 'disabled' : ''}}>Add</button>
                <div id="AddDetectionStageModal" class="modal fadebd-example-modal-lg scrollable" aria-labelledby="AddDetectionStageModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title text-white">Add {{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}}</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option closeBtnDetectionStage" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-fw fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <form id="add_detection_stage_form" method="POST" enctype="multipart/form-data">

                                <div class="modal-body m-body p-4">

                                    <div class="alert alert-success d-none w-100" id="success_msg_detection_stage" style="font-size:12px;">
                                        Data added successfully
                                    </div>
                                    <div class="alert alert-danger d-none w-100" id="error_detection_stage" style="font-size:12px;">
                                        
                                    </div>
                                    <ul class="alert alert-danger d-none w-100" id="error_msg_detection_stage" style="font-size:12px;">
                                        Internal server error, we are trying to fix them soon
                                    </ul>

                                    <ul class="alert alert-warning d-none w-100" id="validation_error_msg_detection_stage" style="font-size:12px;"></ul>

                                    <div class="form-group d-inline-block" style="width:100%;">
                                        <label class="col-sm-12 col-form-label" style="font-size:13px;">{{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}} <span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="detectionStageName" id="detectionStageName" placeholder="Enter {{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}} Name" style="font-size:12px;" required>
                                        </div>
                                        <div class="col-sm-12">
                                            <span style="font-size:12px;" class="text-danger">Note: Define detection stages for NCR (e.g., Received, Processing, Testing, Final Inspection).</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary closeBtnDetectionStage" data-dismiss="modal" style="font-size:12px">Close</button>
                                    <button type="submit" id="submitBtnDetectionStage" class="btn btn-primary" style="font-size:12px">Submit</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full mt-2">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:9%;font-size:10px;text-transform:capitalize;">SL No.</th>
                                <th class="text-center" style="width:54%;font-size:10px;text-transform:capitalize">{{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}} Name</th>
                                <th class="text-center" style="width:7%;font-size:10px;text-transform:capitalize">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($detectionStages as $stage)
                                {{csrf_field()}}
                                <tr class="text-center">
                                    <td style="font-size:10px;">
                                        <span style="width:5px">{{ $loop->iteration }}</span>
                                    </td>
                                    <td style="font-size:10px;width:10px">
                                        <span>{{$stage->name}}</span>
                                    </td>
                                    <td style="font-size:10px;width:10px">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-alt-primary editDetectionStageBtn" style="font-size:10px" data-toggle="tooltip" title="Edit" data-id="{{$stage->id}}" data-name="{{$stage->name}}">
                                                <i class="fa fa-fw fa-pencil-alt"></i>
                                            </a>
                                            <a href="{{url('/admin/remove-detection-stage/'.$stage->id)}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete">
                                                <i class="fa fa-fw fa-times"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-danger text-center" style="font-size:10px;" colspan="3">No {{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}} Found</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">MS.05.03 Types Table</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            NCR & Corrective Actions
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:80%;">
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

                         </div>
                     </div>
                 </div>
                 <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full mt-2">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Type</th>
                            <th class="d-sm-table-cell text-left" style="width: 10%;font-size:10px;text-transform:capitalize">Description</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($types as $c)

                        {{csrf_field()}}

                        <?php $id = $c->id ; $name=$c->name ?>
                        <tr>

                            <td style="font-size:10px" class="text-left">
                                <span>{{ $number }}</span>
                            </td>


                            <?php $number++; ?>
                            <td style="font-size:10px" class="text-left">
                               <span style="font-size:10px;background-color:{{ $c->background_color }};color:{{ $c->text_color}}" class="badge-class">{{ $c->name }}</span>
                            </td>

                            <td style="font-size:10px" class="text-left">
                                <span>{{$c->details}}</span>
                            </td>


                            <td class="text-left">
                                <div class="btn-group">
                                    <a href="{{url('/admin/editTypeCorAction?val='.$id.'&name='.$name)}}" class="btn btn-sm btn-alt-primary" style="font-size:13px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                    <a href="{{url('/admin/removeTypeCorAction?val='.$id)}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:13px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
                 </div>
            </div>
            <br><br>
            {{-- <div class="row" style="margin-left:80px">
                <div class="col-lg-10">
                    <form method="post" action="{{url('/admin/addType')}}" onsubmit="return validation()">
                {{csrf_field()}}



                <div class="col-lg-8">
                    <div class="form-group row" style="margin-top:-10px">

                        <div class="col-sm-8">
                            <input type="text" class="form-control" style="font-size: 13px;;margin-left:395px" placeholder="Enter type" name="type" id="type" required>
                        </div>
                    </div>


                    <div class="form-group row" style="margin-top:-78px">
                        <div class="col-md-8">
                            <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;;margin-left:760px;margin-top:21px" value="Add">
                        </div>
                    </div>
                    </form>

                </div>


            </div> --}}
        </div>
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">MS.05.04 Root Cause and Corrective Action Table</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            NCR & Corrective Actions
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:80%;">
                <div class="table-responsive">
                <button class="btn btn-primary text-right ml-auto d-flex" style="font-size:10px" onclick="addRootCorrective()">Add</button>
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full mt-2">
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
                                <span>{{ $rootcorrective->title }}</span>
                            </td>


                            <td style="font-size:10px" class="text-left">
                                <span>{{ $rootcorrective->root_cause }}</span>
                            </td>

                            <td style="font-size:10px" class="text-left">
                                @php
                                $points = $rootcorrective->corrective_action;
                                $point_arrays = explode("\n",$points);
                                @endphp
                                <ul>
                                    @foreach($point_arrays as $point_array)
                                    <li style="margin-bottom:2px;position:relative;left: -12px;">{{$point_array}}</li>
                                    @endforeach
                                </ul>
                            </td>

                            @php $number++; @endphp

                            <td class="text-left">
                                <div class="btn-group">
                                    <a href="{{ route('edit_corrective_root_corrective', ['id' => $rootcorrective->id ])}}" style="font-size:10px;" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                    <a href="{{ route('delete_corrective_root_corrective', ['id' => $rootcorrective->id ])}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:10px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <!--root cause and corrective action table ends  -->
    {{-- Edit Detection Stage Model Start --}}
    <div id="EditDetectionStageModal" class="modal fadebd-example-modal-lg scrollable" aria-labelledby="EditDetectionStageModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title text-white">Edit {{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option closeBtnEditDetectionStage" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <form id="edit_detection_stage_form" method="POST" enctype="multipart/form-data">

                    <div class="modal-body m-body p-4">

                        <div class="alert alert-success d-none w-100" id="success_msg_edit_detection_stage" style="font-size:12px;">
                            Data updated successfully
                        </div>
                        <div class="alert alert-danger d-none w-100" id="error_edit_detection_stage" style="font-size:12px;">
                            
                        </div>
                        <ul class="alert alert-danger d-none w-100" id="error_msg_edit_detection_stage" style="font-size:12px;">
                            Internal server error, we are trying to fix them soon
                        </ul>

                        <ul class="alert alert-warning d-none w-100" id="validation_error_msg_edit_detection_stage" style="font-size:12px;"></ul>

                        <div class="form-group d-inline-block" style="width:100%;">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">{{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}} <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="editDetectionStageName" id="editDetectionStageName" placeholder="Enter {{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}} Name" style="font-size:12px;" required>
                                <input type="hidden" id="stage_id" name="stage_id" value="">
                            </div>
                            <div class="col-sm-12">
                                <span style="font-size:12px;" class="text-danger">Note: Define detection stages for NCR (e.g., Received, Processing, Testing, Final Inspection).</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary closeBtnEditDetectionStage" data-dismiss="modal" style="font-size:12px">Close</button>
                        <button type="submit" id="submitBtnEditDetectionStage" class="btn btn-primary" style="font-size:12px">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    {{-- Edit Detection Stage Model END --}}
    <div id="field-model"></div>
@endsection

@section('js_after')
<script src="{{ asset('js/admin/correctiveModule.js') }}"></script>
@endsection