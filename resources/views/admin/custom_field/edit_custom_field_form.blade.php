<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <!-- Form Horizontal - Default Style -->
        <form id="customFieldEditForm" class="mb-5" action="/admin/custom_field/custom-fields/update" method="POST">
            {{csrf_field()}}
            <input type="hidden" name="id" id='id' value="{{$field->id}}">
            <input type="hidden" id="is_edit_page" value="true">
            <input type="hidden" name="all_field_orders" id="all_field_orders_input">
            <div class="row">
                <div class="form-group col-sm-6">
                    <label class="col-sm-12 col-form-label" for="Module Selection" style="font-size: 13px;">Module Selection <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <select class="form-control js-select2" name="modulenameedit" id="modulenameedit" required="true" style="font-size: 13px;width:100%;" data-placeholder="Select a Module to begin">
                            <option value="" selected disabled>Select a Module to begin</option>
                            @foreach($modulenames as $modulename)
                            <option value="{{$modulename}}" {{$modulename==$field->module_name?'selected':''}}>{{$modules[$modulename]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6" style="visibility:{{$field->sub_module?'visible':'hidden'}};" id="modulecalibrationdivedit">
                    <label class="col-sm-12 col-form-label" for="Module Calibration" style="font-size: 13px;">Module Calibration <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <select class="form-control js-select2" name="sub_moduleedit" id="modulecalibrationedit" style="font-size: 13px;width:100%;" data-placeholder="Select a Module Calibration">
                            <option value="" selected disabled>Select a Module Calibration</option>
                            <option value="internal" {{'internal'==$field->sub_module?'selected':''}}>Internal</option>
                            <option value="external" {{'external'==$field->sub_module?'selected':''}}>External</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-12 col-form-label" for="Field Name" style="font-size: 13px;">Field Name <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <input type="text" name="fieldnameedit" id="fieldnameedit" placeholder="Enter field label" class="form-control" required="true" style="font-size: 13px;width:100%;" value="{{$field->field_name}}">
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-12 col-form-label" for="Field Type" style="font-size: 13px;">Field Type <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <select class="form-control js-select2" name="fieldtypeedit" id="fieldtypeedit" required="true" style="font-size: 13px;width:100%;" data-placeholder="Select a Field Type">
                            <option value="">Select a Field Type</option>
                            @foreach($fieldtypes as $key => $value)
                                <option value="{{$key}}" {{$field->field_type==$key?'selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6" id="fieldplaceholderdiv">
                    <label class="col-sm-12 col-form-label" for="Field Placeholder" style="font-size: 13px;">Field Placeholder</label>
                    <div class="col-sm-12">
                        <input type="text" name="fieldplaceholderedit" id="fieldplaceholderedit" placeholder="Enter Placeholder" class="form-control" style="font-size: 13px;width:100%;" value="{{$field->field_placeholder}}">
                        
                    </div>
                </div>
                
                <div class="form-group col-sm-6" id="dynamicFieldContainerEdit">
                    <label class="col-sm-12 col-form-label" for="Is this field mandatory?" style="font-size: 13px;">Is this field mandatory? <span class="text-danger">*</span></label>
                    <div class="custom-control custom-switch col-sm-12" style="padding-left: 14px !important;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="example-radios-inline1" name="mandatoryedit" value="1" {{$field->mandatory!=0?'checked':''}} required>
                            <label class="form-check-label" style="font-size: 13px;" for="example-radios-inline1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="example-radios-inline2" name="mandatoryedit" value="0" {{$field->mandatory==0?'checked':''}} required>
                            <label class="form-check-label" style="font-size: 13px;" for="example-radios-inline2">No</label>
                        </div>
                    </div>
                </div>
                @if(count($options))
                <div id="dropdown-options-container-edit" class="col-sm-12 form-group">
                    <label style="font-size:13px;" class="col-sm-12">Options for {{$fieldtypes[$field->field_type]}} <span class="text-danger">*</span></label>
                    <div id="dropdown-options-list-edit" class="col-sm-12 edit-list">
                        @foreach($options as $key => $option)
                        @if($loop->first)
                        <div class="input-group mb-2 dropdown-option-row-edit">
                            <input type="text" name="dropdown_options_edit[]" class="form-control dropdown_option_edit" placeholder="Enter Option value" required style="font-size:13px;" value="{{$option}}">
                            <input type="hidden" name="dropdown_ids[]" value="{{$key}}">
                            <div class="input-group-append">
                                <button class="btn btn-success btn-sm add-option-edit" type="button"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        @else
                        <div class="input-group mb-2 dropdown-option-row-edit">
                            <input type="text" name="dropdown_options_edit[]" class="form-control dropdown_option_edit" placeholder="Enter Option value" required style="font-size:13px;" value="{{$option}}">
                            <input type="hidden" name="dropdown_ids[]" value="{{$key}}">
                            <div class="input-group-append">
                                <button class="btn btn-danger btn-sm remove-option-edit" type="button"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="row" id="pageContainerEdit">   
                {!!$view!!}  
            </div>
            <div class="row">
                <div class="form-group col-sm-4">
                    <div class="col-sm-12">
                        <button for="reset" type="reset" class="btn btn-outline-secondary" style="font-size:13px;width:100%;">Reset Fields</button>
                    </div>
                </div>
                <div class="form-group col-sm-8">
                    <div class="col-sm-12">
                        <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;width:100%;">Update Custom Field</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- END Form Horizontal - Default Style -->
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12" id="previewContainer" style="border: 1px solid #e0e0e0; padding: 20px; border-radius: 5px;">
        <div class="d-flex justify-content-center align-items-center h-100" style="min-height: 150px;">
            <p class="text-center m-0" style="font-size: 14px; font-weight: lighter;">
                Click the '<i class="far fa-eye-slash text-center" style="font-size:10px;"></i>' icon next to pages, to view a quick preview of your created Custom Fields.
            </p>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Add new option row
        $('#fieldtypeedit').select2();
        $('#modulenameedit').select2();
        $('#modulecalibrationedit').select2();
        $(document).off('click','.add-option-edit').on('click', '.add-option-edit', function () {
            const newOptionEdit = `
                <div class="input-group mb-2 dropdown-option-row-edit">
                    <input type="text" name="dropdown_options_edit[]" class="form-control dropdown_option_edit" placeholder="Enter Option value" required style="font-size:13px;">
                    <div class="input-group-append">
                        <button class="btn btn-danger btn-sm remove-option-edit" type="button"><i class="fa fa-minus"></i></button>
                    </div>
                </div>`;
            $('#dropdown-options-list-edit').append(newOptionEdit);
        });
        // Remove option row
        $(document).off('click','.remove-option-edit').on('click', '.remove-option-edit', function () {
            $(this).closest('.dropdown-option-row-edit').remove();
            validateDropdownOptions();
        });
        $('#fieldtypeedit').change(function () {
            const selectedTypeEdit = $(this).val();
            const inputTypesWithOptionsEdit = ['select', 'multiselect', 'radio', 'checkbox'];
            const inputForDisableFieldPlaceholder = ['date','file','file_multiple','checkbox','radio','datetime-local','time','month','week'];
            let optionLabelEdit = '';
            switch(selectedTypeEdit){
                case 'select': optionLabelEdit = 'Dropdown'; break;
                case 'multiselect': optionLabelEdit = 'Dropdown (Multiple Selection)'; break;
                case 'radio': optionLabelEdit = 'Radio Buttons'; break;
                case 'checkbox': optionLabelEdit = "Checkbox"; break;
            }
            // Remove old options section if it exists
            $('#dropdown-options-container-edit').remove();
            $('#dropdown-options-container-edit1').remove();

            if (inputTypesWithOptionsEdit.includes(selectedTypeEdit)) {
                const optionsHtmlEdit = `
                    <div id="dropdown-options-container-edit1" class="col-sm-12 form-group">
                        <label style="font-size:13px;" class="col-sm-12">Options for ${optionLabelEdit} <span class="text-danger">*</span></label>
                        <div id="dropdown-options-list-edit1" class="col-sm-12 edit-list">
                            <div class="input-group mb-2 dropdown-option-row-edit1">
                                <input type="text" name="dropdown_options_edit1[]" class="form-control dropdown_option_edit" placeholder="Enter Option value" required style="font-size:13px;">
                                <div class="input-group-append">
                                    <button class="btn btn-success btn-sm add-option-edit1" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#dynamicFieldContainerEdit').after(optionsHtmlEdit);
            }
            if(inputForDisableFieldPlaceholder.includes(selectedTypeEdit)){
                $('#fieldplaceholderdiv').addClass('d-none');
            }else{
                $('#fieldplaceholderdiv').removeClass('d-none');
            }
            // Add new option row
            $(document).off('click', '.add-option-edit1').on('click', '.add-option-edit1', function () {
                const optionRowEdit = `
                    <div class="input-group mb-2 dropdown-option-row-edit1">
                        <input type="text" name="dropdown_options_edit1[]" class="form-control dropdown_option_edit" placeholder="Enter Option value" required style="font-size:13px;">
                        <div class="input-group-append">
                            <button class="btn btn-danger btn-sm remove-option-edit1" type="button"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                `;
                $('#dropdown-options-list-edit1').append(optionRowEdit);
            });

            // Remove option row
            $(document).off('click', '.remove-option-edit1').on('click', '.remove-option-edit1', function () {
                $(this).closest('.dropdown-option-row-edit1').remove();
                validateDropdownOptions();
            });
        });
        $('#modulenameedit').change(function (event) {
            const selectedModuleEdit = $(this).val();

            if (selectedModuleEdit === 'calibration') {
                $('#modulecalibrationdivedit').css('visibility', 'visible');
                $('#modulecalibrationedit').prop('required', true);
            } else {
                $('#modulecalibrationdivedit').css('visibility', 'hidden');
                $('#modulecalibrationedit').prop('required', false).val('').trigger('change');
                fetchPageView(selectedModuleEdit, null);
            }
        });

        $('#modulecalibrationedit').change(function () {
            const subModuleEdit = $(this).val();
            const selectedModuleEdit = $('#modulenameedit').val();

            if (selectedModuleEdit === 'calibration' && subModuleEdit) {
                fetchPageViewEdit(selectedModuleEdit, subModuleEdit);
            }
        });

        function fetchPageViewEdit(modulename, sub_module = null) {
            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: window.location.origin + `/admin/custom_field/get-target-display-page`,
                data: {
                    modulename: modulename,
                    sub_module: sub_module
                },
                success: function (response) {
                    $('#pageContainerEdit').empty().html(response.view);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching target and display pages:', error);
                }
            });
        }
        $('#customFieldEditForm').on('submit', function (e) {
            const targetCheckedEdit = $('input[name="target_pages[]"]:checked').length;
            const displayCheckedEdit = $('input[name="display_pages[]"]:checked').length;
            const dropdownOptions1 = $('.dropdown_option_edit').map(function() {
                return $(this).val().trim().toLowerCase();
            }).get();
            const uniqueOptions1 = new Set(dropdownOptions1.filter(option => option !== ''));
            if (dropdownOptions1.length !== uniqueOptions1.size) {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Options',
                    text: 'Please ensure all dropdown options are unique and not empty.',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            if (targetCheckedEdit === 0 && displayCheckedEdit === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No Pages Selected',
                    text: 'Please select at least one page from either Target Pages or Display Pages.',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure to submit the request?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
            return false;
        });
        function disbleField(){
            const type = $('#fieldtypeedit').val();
            const inputForDisable = ['date','file','file_multiple','checkbox','radio','datetime-local','time','month','week'];
            if(inputForDisable.includes(type)){
                $('#fieldplaceholderdiv').addClass('d-none');
            }else{
                $('#fieldplaceholderdiv').removeClass('d-none');
            }
        }
        disbleField();
        // Real-time duplicate option validation
        $(document).on('input', '.dropdown_option_edit', function () {
            validateDropdownOptions();
        });
        function validateDropdownOptions() {
            const dropdownOptions = $('.dropdown_option_edit').map(function() {
                return $(this).val().trim().toLowerCase();
            }).get();
            const filteredOptions = dropdownOptions.filter(option => option !== '');
            const duplicates = filteredOptions.filter((item, idx) => filteredOptions.indexOf(item) !== idx);
            // Remove previous error message
            $('#dropdown-duplicate-error').remove();
            if (duplicates.length > 0) {
                // Show error message below the options list
                $('.edit-list').after(`
                    <div id="dropdown-duplicate-error" class="text-danger" style="font-size:13px;padding-left:15px;">
                        Please ensure all dropdown options are unique.
                    </div>
                `);
            }
        }
    });
</script>
