<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <!-- Form Horizontal - Default Style -->
        <form id="customFieldForm" class="mb-5" action="/admin/custom_field/custom-fields" method="POST">
            {{csrf_field()}}
            <input type="hidden" id="is_edit_page" value="false">
            <input type="hidden" name="all_field_orders" id="all_field_orders_input">
            <div class="row">
                <div class="form-group col-sm-6">
                    <label class="col-sm-12 col-form-label" for="Module Selection" style="font-size: 13px;">Module Selection <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <select class="form-control js-select2" name="modulename" id="modulename" required="true" style="font-size: 13px;width:100%;" data-placeholder="Select a Module to begin">
                            <option value="" selected disabled>Select a Module to begin</option>
                            @foreach($modulenames as $modulename)
                            <option value="{{$modulename}}">{{$modules[$modulename]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6" style="visibility:hidden;" id="modulecalibrationdiv">
                    <label class="col-sm-12 col-form-label" for="Module Calibration" style="font-size: 13px;">Module Calibration <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <select class="form-control js-select2" name="sub_module" id="modulecalibration" style="font-size: 13px;width:100%;" data-placeholder="Select a Module Calibration">
                            <option value="" selected disabled>Select a Module Calibration</option>
                            <option value="internal">Internal</option>
                            <option value="external">External</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-12 col-form-label" for="Field Name" style="font-size: 13px;">Field Name <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <input type="text" name="fieldname" id="fieldname" placeholder="Enter field label" class="form-control" required="true" style="font-size: 13px;width:100%;" value="{{old('fieldname')}}">
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-12 col-form-label" for="Field Type" style="font-size: 13px;">Field Type <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <select class="form-control js-select2" name="fieldtype" id="fieldtype" required="true" style="font-size: 13px;width:100%;" data-placeholder="Select a Field Type">
                            <option value="">Select a Field Type</option>
                            @foreach($fieldtypes as $key => $value)
                                <option value="{{$key}}" {{old('fieldtype')==$key?'selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6" id="fieldplaceholderdiv">
                    <label class="col-sm-12 col-form-label" for="Field Placeholder" style="font-size: 13px;">Field Placeholder</label>
                    <div class="col-sm-12">
                        <input type="text" name="fieldplaceholder" id="fieldplaceholder" placeholder="Enter Placeholder" class="form-control" style="font-size: 13px;width:100%;" value="{{old('fieldplaceholder')}}">
                    </div>
                </div>
                
                <div class="form-group col-sm-6" id="dynamicFieldContainer">
                    <label class="col-sm-12 col-form-label" for="Is this field mandatory?" style="font-size: 13px;">Is this field mandatory? <span class="text-danger">*</span></label>
                    <div class="custom-control custom-switch col-sm-12" style="padding-left: 14px !important;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="example-radios-inline1" name="mandatory" value="1" required>
                            <label class="form-check-label" style="font-size: 13px;" for="example-radios-inline1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="example-radios-inline2" name="mandatory" value="0" required>
                            <label class="form-check-label" style="font-size: 13px;" for="example-radios-inline2">No</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="pageContainer">   
                <div class="form-group col-sm-12">
                    <label class="col-sm-12 col-form-label" for="Target Pages" style="font-size: 13px;">Target Pages <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                    </div>
                </div>
                <div class="form-group col-sm-12">
                    <label class="col-sm-12 col-form-label" for="Display Pages" style="font-size: 13px;">Display Pages</label>
                    <div class="col-sm-12">
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4">
                    <div class="col-sm-12">
                        <button for="reset" type="reset" class="btn btn-outline-secondary" style="font-size:13px;width:100%;">Reset Fields</button>
                    </div>
                </div>
                <div class="form-group col-sm-8">
                    <div class="col-sm-12">
                        <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;width:100%;">Create Custom Field</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- END Form Horizontal - Default Style -->
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12" id="previewContainer" style="border: 1px solid #e0e0e0; padding: 20px; border-radius: 5px;">
        <div class="d-flex justify-content-center align-items-center h-100" style="min-height: 150px;">
            <p class="text-center m-0" style="font-size: 14px;">
                Click the '<i class="far fa-eye-slash text-center" style="font-size:10px;"></i>' icon next to pages, to view a quick preview of your created Custom Fields.
            </p>
        </div>
    </div>
</div>