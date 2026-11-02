@extends('admin.datatable')

@section('content')
    <!-- Page Content -->
    @php 
        $dnoneClass = $category->is_pnc_cat == 'yes' || $category->categoryname=='Product Non- Conformity' || $category->is_process_cat == 'yes' || $category->is_vendor_cat == 'yes' ? '': 'd-none';
    @endphp
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">

            <div class="block-header">
                <h3 class="block-title">Edit category</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/viewTypes">Back</a>
                        </li>
                    </ol>
                </nav>


            </div>
            <div class="block-content block-content-full" style="width:90%;">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" method="POST" action="{{url('/admin/updateCategory')}} " onsubmit="return validation()" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            <div class="alert alert-danger d-none w-100" id="error_pnc" style="font-size:12px;"></div>
                            <div class="form-group row">
                                <label class="col-sm-1 col-xs-12 col-form-label" style="font-size: 13px;" for="type">Name</label>
                                <div class="col-sm-8 col-xs-12">
                                    <input type="text" class="form-control" id="input01" style="font-size: 13px;" name="name" value="{{$category->categoryname}}">
                                </div>
                            </div>

                            <input type="hidden" name="id" class="xlarge" value="{{$category->id}}">
                            <input type="hidden" name="oldcatname" class="xlarge" value="{{$category->categoryname}}">
                            {{-- Start Categories Based Extra Fields--}}
                            <div class="form-group {{ $dnoneClass }}" style="width:100%;" id="pnc-question-category">
                                <label class="col-sm-12 col-form-label px-0" for="name" style="font-size:13px;">Do you want this category to capture category-specific details?</label>
                                <div class="col-sm-12 px-0">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="pnc_yes" name="pnc_question" value="yes" {{ $category->product_specific_details_required == 'yes' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pnc_yes" style="font-size:13px;">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="pnc_no" name="pnc_question" value="no" {{ $category->product_specific_details_required == 'no' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pnc_no" style="font-size:13px;">No</label>
                                    </div>
                                        
                                </div>
                            </div>
                            <div class="form-group {{ $category->product_specific_details_required == 'yes' ? '' : 'd-none' }}" style="width:100%;" id="product_non_conformity_section">
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
                                                <th class="text-center" style="width:40%;font-size:12px;text-transform:capitalize">Display Label</th>
                                                <th class="text-center" style="width:9%;font-size:12px;text-transform:capitalize">Mandatory?</th>
                                                <th class="text-center" style="width:5%;font-size:12px;text-transform:capitalize">View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Start Custom Fields Fields --}}
                                            @foreach($ncrConfigurationFields as $field)
                                            @php 
                                                $ncrConAddedFieldsArray = $ncrConAddedFields->pluck('field_id')->toArray();
                                                $displayLabel = $ncrConAddedFields->where('field_id', $field->id)->first();
                                                if ($displayLabel) {
                                                    $mandatory = $displayLabel->is_mandatory;
                                                    $displayLabel = $displayLabel->display_label;
                                                }else{
                                                    $displayLabel = $field->display_label;
                                                    $mandatory = 0;
                                                }
                                            @endphp
                                            <tr>
                                                <td style="font-size:12px;" class="text-center">
                                                    <div class="custom-control custom-checkbox d-inline-block">
                                                        <input type="checkbox" class="custom-control-input select-field" id="selectField{{ $field->id }}" name="select_field[]" value="{{ $field->id }}" {{ in_array($field->id, $ncrConAddedFieldsArray) ? 'checked' : '' }}>
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
                                                        <input type="text" data-old-value="{{ $displayLabel }}" name="display_label[]" placeholder="Enter Display Label" class="form-control display-label" style="font-size: 12px;width:100%;" value="{{ $displayLabel }}" {{ in_array($field->id, $ncrConAddedFieldsArray) ? '' : 'disabled' }}>
                                                    </div>
                                                    <div class="warning-message d-none" style="font-size:12px;">
                                                        <span class="text-danger" style="font-size:12px;">This change updates only the display name. Field purpose and existing data remain unchanged.</span>
                                                    </div>
                                                </td>
                                                <td style="font-size:12px;" class="text-center p-0">
                                                    <div class="custom-control custom-switch custom-control-success mb-1">
                                                        <input type="checkbox" name="mandatory[]" id="mandatory{{ $field->id }}" class="custom-control-input mandatory-field" value="{{$field->id}}" {{ in_array($field->id, $ncrConAddedFieldsArray) ? '' : 'disabled' }} {{ $mandatory ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="mandatory{{ $field->id }}"></label>
                                                    </div>
                                                </td>
                                                <td style="font-size:12px;" class="text-center p-0">
                                                    <a href="#" class="btn btn-sm btn-alt-primary view-history" data-category="{{$category->id}}" data-id='{{$field->field_name}}' data-toggle="tooltip" data-placement="top" title="View Field History">
                                                        <i class="fa fa-eye" style="font-size:11px;cursor:pointer"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            {{-- End Product Non Conformity Fields --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- END Fields for Categoris --}}
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                    <input type="submit" id="updateBtn" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->
        <div id="history-modal"></div>
    </div>
@endsection
@section('js_after')

<script>
    let pncPreviousState = {};
    let fieldLabelChanged = false;
    $('#pnc_yes, #pnc_no').on('change', function () {

        if ($('#pnc_no').is(':checked')) {

            // Save current YES state before hiding
            pncPreviousState = {};

            $('.select-field').each(function () {
                const row = $(this).closest('tr');
                const fieldId = $(this).val();

                pncPreviousState[fieldId] = {
                    selected: $(this).is(':checked'),
                    displayLabel: row.find('.display-label').val(),
                    mandatory: row.find('.mandatory-field').is(':checked')
                };
            });

            // Hide & disable UI
            $('#product_non_conformity_section').addClass('d-none');
            $('.select-field').prop('checked', false);
            $('.display-label').prop('disabled', true).prop('required', false);
            $('.mandatory-field').prop('disabled', true).prop('checked', false);
        }

        if ($('#pnc_yes').is(':checked')) {
            restorePNCState();
        }
    });
    function restorePNCState() {

        $('#product_non_conformity_section').removeClass('d-none');

        $('.select-field').each(function () {
            const row = $(this).closest('tr');
            const fieldId = $(this).val();

            if (pncPreviousState[fieldId]) {
                const state = pncPreviousState[fieldId];

                $(this).prop('checked', state.selected);

                row.find('.display-label')
                    .val(state.displayLabel)
                    .prop('disabled', !state.selected)
                    .prop('required', state.selected);

                row.find('.mandatory-field')
                    .prop('checked', state.mandatory)
                    .prop('disabled', !state.selected);
            }
        });
    }
    $(document).on('change', '.select-field', function () {
        const row = $(this).closest('tr');
        const isChecked = $(this).is(':checked');

        row.find('.display-label')
            .prop('disabled', !isChecked)
            .prop('required', isChecked);

        row.find('.mandatory-field')
            .prop('disabled', !isChecked)
            .prop('checked', isChecked ? row.find('.mandatory-field').is(':checked') : false);
    });
    $(document).ready(function () {
        if ($('#pnc_yes').is(':checked')) {
            restorePNCState();
        }
    });
    $('#input01').on('input', function() {
        let category_value = $(this).val();
        $('#error_pnc').html('').addClass('d-none');
        validation(category_value);
    });
    $(document).on('input', '.display-label', function () {
        const $input = $(this);
        const oldValue = (($input.data('old-value') || '').trim()).toLowerCase();
        const currentValue = (($input.val() || '').trim()).toLowerCase();


        const $warning = $input
            .closest('td')
            .find('.warning-message');

        if (currentValue !== oldValue) {
            $warning.removeClass('d-none');
            fieldLabelChanged = true;
        } else {
            $warning.addClass('d-none');
            fieldLabelChanged = false;
        }
    });

    function validation(category_value){  
        $.ajax({
            url: window.location.origin + "/admin/check-category",
            type: 'GET',
            data: { category_name: category_value, 'type': 'edit', 'id': '{{ $category->id }}','is_pnc_cat': '{{ $category->is_pnc_cat }}','is_process_cat': '{{ $category->is_process_cat }}','is_vendor_cat': '{{ $category->is_vendor_cat }}' },
            dataType: 'json',
            success: function(response) {
                if (response.exists) {
                    $('#error_pnc').html(response.msg).removeClass('d-none');
                    
                    $('#updateBtn').prop('disabled', true);
                } else {
                    $('#error_pnc').html('').addClass('d-none');
                    $('#updateBtn').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error checking PNC Category:", error);
            }
        });
    }

    // {{-- View Details of Fields --}}
    $('body').on('click', '.view-history', function () {
        const id = $(this).data('id');
        const category = $(this).data('category');
        $.ajax({
            url : window.location.origin +`/admin/corrective/viewhistory/${id}/${category}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.success) {
                    $('#history-modal').html(data.view)
                    const modalEle = $(`#viewHistory`);
                    modalEle.modal('show');
                } else {
                    alert('Data is not found');
                }
            },
            error: function () {
                alert('Handle error');
            }
        });
    });
    $('#updateBtn').on('click', function (e) {
        if (fieldLabelChanged) {
            e.preventDefault();

            Swal.fire({
                title: '<span style="font-size:20px;color:red;">Are you sure you want to update these fields?</span>',
                html: `
                    <ul class="mb-0 pl-4" style="font-size:13px; line-height:1.6; text-align:left;">
                        <li><strong>New names</strong> apply only to newly created records</li>
                        <li><strong>Existing records</strong> continue to display the old name in details and printed outputs</li>
                        <li><strong>Register page and exports</strong> will show the updated field name</li>
                        <li><strong>Functionality</strong> remains unchanged</li>
                    </ul>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('form').submit();
                }
            });
        }
    });

</script>
@endsection
