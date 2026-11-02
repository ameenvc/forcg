    $(document).ready(function () {
        $('#tab1, #tab2').click(function(){
            var $tab1 = $('#tab1');
            var $tab2 = $('#tab2');
            if (this.id === 'tab1') {
                $tab1.css('color', '#2e2eb8');
                $tab2.css('color', 'grey');
            } else if (this.id === 'tab2') {
                $tab1.css('color', 'grey');
                $tab2.css('color', '#2e2eb8');
            }
        });
        $('#modulename').change(function (event) {
            const selectedModule = $(this).val();

            if (selectedModule === 'calibration') {
                $('#modulecalibrationdiv').css('visibility', 'visible');
                $('#modulecalibration').prop('required', true);
            } else {
                $('#modulecalibrationdiv').css('visibility', 'hidden');
                $('#modulecalibration').prop('required', false).val('').trigger('change');
                fetchPageView(selectedModule, null);
            }
        });

        $('#modulecalibration').change(function () {
            const subModule = $(this).val();
            const selectedModule = $('#modulename').val();

            if (selectedModule === 'calibration' && subModule) {
                // Trigger AJAX only when submodule is selected
                fetchPageView(selectedModule, subModule);
            }
        });

        function fetchPageView(modulename, sub_module = null) {
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
                    $('#pageContainer').empty().html(response.view);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching target and display pages:', error);
                }
            });
        }

        $('#fieldtype').change(function () {
            const selectedType = $(this).val();
            const inputTypesWithOptions = ['select', 'multiselect', 'radio', 'checkbox'];
            const inputForDisableFieldPlaceholder = ['date','file','file_multiple','checkbox','radio','datetime-local','time','month','week'];
            let optionLabel = '';
            switch(selectedType){
                case 'select': optionLabel = 'Dropdown'; break;
                case 'multiselect': optionLabel = 'Dropdown (Multiple Selection)'; break;
                case 'radio': optionLabel = 'Radio Buttons'; break;
                case 'checkbox': optionLabel = "Checkbox"; break;
            }
            // Remove old options section if it exists
            $('#dropdown-options-container').remove();

            if (inputTypesWithOptions.includes(selectedType)) {
                const optionsHtml = `
                    <div id="dropdown-options-container" class="col-sm-12 form-group">
                        <label style="font-size:13px;" class="col-sm-12">Options for ${optionLabel} <span class="text-danger">*</span></label>
                        <div id="dropdown-options-list" class="col-sm-12">
                            <div class="input-group mb-2 dropdown-option-row">
                                <input type="text" name="dropdown_options[]" class="form-control dropdown_option" placeholder="Enter Option value" required style="font-size:13px;">
                                <div class="input-group-append">
                                    <button class="btn btn-success btn-sm add-option" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#dynamicFieldContainer').after(optionsHtml);
            }
            if(inputForDisableFieldPlaceholder.includes(selectedType)){
                $('#fieldplaceholderdiv').addClass('d-none');
            }else{
                $('#fieldplaceholderdiv').removeClass('d-none');
            }
            // Add new option row
            $(document).off('click', '.add-option').on('click', '.add-option', function () {
                const optionRow = `
                    <div class="input-group mb-2 dropdown-option-row">
                        <input type="text" name="dropdown_options[]" class="form-control dropdown_option" placeholder="Enter Option value" required style="font-size:13px;">
                        <div class="input-group-append">
                            <button class="btn btn-danger btn-sm remove-option" type="button"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                `;
                $('#dropdown-options-list').append(optionRow);
            });

            // Remove option row
            $(document).off('click', '.remove-option').on('click', '.remove-option', function () {
                $(this).closest('.dropdown-option-row').remove();
                validateDropdownOptions();
            });
        });
        $('.toggle-group-btn').on('click', function () {
            const icon = $(this).find('i');
            const targetId = $(this).data('target');
            const target = $(targetId);

            target.slideToggle('fast');

            if (icon.hasClass('fa-plus')) {
                icon.removeClass('fa-plus').addClass('fa-minus');
            } else {
                icon.removeClass('fa-minus').addClass('fa-plus');
            }
        });
        $('#customFieldForm').on('submit', function (e) {
            const targetChecked = $('input[name="target_pages[]"]:checked').length;
            const displayChecked = $('input[name="display_pages[]"]:checked').length;
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
            if (targetChecked === 0 && displayChecked === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No Pages Selected',
                    text: 'Please select at least one page from either Target Pages or Display Pages.',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            const dropdownOptions1 = $('.dropdown_option').map(function() {
                return $(this).val().trim().toLowerCase();
            }).get();
            
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
        $('.edit-field').on('click',function(e){
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: window.location.origin + `/admin/custom_field/custom-fields-edit/${id}`,
                data: {
                    id: id
                },
                success: function (response) {
                    $('#tab-1').click();
                    $('#tab1').click();
                    $('#tab-1').empty().html(response.editform);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching edit form:', error);
                }
            });
        });
        // Real-time duplicate option validation
        $(document).on('input', '.dropdown_option', function () {
            validateDropdownOptions();
        });
        function validateDropdownOptions() {
            const dropdownOptions = $('.dropdown_option').map(function() {
                return $(this).val().trim().toLowerCase();
            }).get();
            const filteredOptions = dropdownOptions.filter(option => option !== '');
            const duplicates = filteredOptions.filter((item, idx) => filteredOptions.indexOf(item) !== idx);
            // Remove previous error message
            $('#dropdown-duplicate-error').remove();
            if (duplicates.length > 0) {
                // Show error message below the options list
                $('#dropdown-options-list').after(`
                    <div id="dropdown-duplicate-error" class="text-danger" style="font-size:13px;padding-left:15px;">
                        Please ensure all dropdown options are unique.
                    </div>
                `);
            }
        }
    });
    