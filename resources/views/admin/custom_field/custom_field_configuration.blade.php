@extends('admin.datatable')
@section('content')
<style>
    #firstColumn::after{
        content: '';
    }
     .hover-container {
        cursor: pointer;
    }

    .hover-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: -80%;
        z-index: 999;
        background-color: #f9f9f9;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        white-space: nowrap;
        font-size: 10px;
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
    }

    .hover-container:hover .hover-dropdown {
        display: block;
    }

    .dropdown-item {
        padding: 2px 0;
    }
    @media (max-width: 1800px) {
        #tab-2 {
            height: calc(100vh - 220px) !important;
            max-height: 900px !important;
        }
    }
    @media (max-width: 1200px) {
        #tab-2 {
            max-height: 600px !important;
        }
    }
    #tab-2{
        height:calc(100vh - 220px); max-height:900px;
    }
    .drag-handle {
        cursor: move;
        font-size: 14px;
        color: #666;
        display: inline-block;
    }
</style>
<script src="{{ asset('js/oneui.core.min.js') }}"></script>
<script src="{{ asset('js/oneui.app.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>jQuery(function () { One.helpers(['table-tools-checkable']); });</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Page Content -->
<div class="content px-0" style="-webkit-print-color-adjust: exact">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">
        <div class="d-flex justify-content-between align-items-center d-block-643" style="background: #F5F5F5;">
            <div class="d-flex d-flex-left p-2 align-items-center">
                <a href="{{ url('/user/home') }}" class="btn btn-secondary btn-sm bg-black mr-4 rounded-0" style="border: 1px solid">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <span class="mr-2" style="font-size:14px;">Custom Field Configuration</span>
                <span style="font-size: 12px; color: grey; padding-top: 0.2rem;">Create Custom Field</span>
            </div>
            <div class="d-flex-right p-2 d-flex d-block">
                <div class="d-flex" data-toggle="tooltip" data-placement="top" title="{{ $currentUser ? $currentUser->name : 'Guest' }}">
                    @if($currentUser && File::exists(public_path('/storage/profile/' . $currentUser->profile_picture)))
                        <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/' . $currentUser->profile_picture) }}">
                    @else
                        <img class="img-avatar img-avatar48 mr-3" src=" {{ asset('/images/avatar10.jpg') }}" />
                    @endif
                </div>
            </div>
        </div>
        <div class="block-content block-content-full">
        @if(Session::has('errmsg'))
            <div class="alert alert-danger">
                <a class="close" data-dismiss="alert">×</a>
                {!!Session::get('errmsg')!!}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <a class="close" data-dismiss="alert">×</a>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(Session::has('msg'))
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                {!!Session::get('msg')!!}
            </div>
        @endif
        <ul class="nav nav-tabs nav-tabs-alt" role="tablist" id="customFieldTabs">
            <li class="nav-item">
                <a class="nav-link active" href="#tab-1" data-toggle="tab" id="tab1" style="color: #2e2eb8;font-size:13px;">Create Custom Field</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tab-2" data-toggle="tab" style="color: grey;font-size:13px;" id="tab2">View Created Custom Fields</a>
            </li>
        </ul>

        <div class="tab-content" id="customFieldTabsContent">
            <div class="tab-pane fade show active mt-4" id="tab-1" role="tabpanel" aria-labelledby="custom-field-tab">
                {!!$form!!}
            </div>
            <div class="tab-pane fade mt-4 table-responsive" id="tab-2" role="tabpanel" aria-labelledby="view-custom-field-tab">
                 {!!$table!!}        
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin/customFields.js') }}"></script>
<script>
    $(document).ready(function () {
        function initializeDropdowns(tableId, dropdownConfigs) {
            $(tableId).one("preInit.dt", function () {
                let table = $(tableId).DataTable();
                dropdownConfigs.forEach(config => {
                    let { dropdownId, columnHeaderId, optionsArray, placeholder} = config;
                    let columnIndex = table.column(columnHeaderId).index();
                    let optionsHTML = optionsArray.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                    let dropdownHTML = `
                        <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:80px;background-color:#fff;font-weight:600 !important;max-height:20% !important;" id="${dropdownId}">
                            <option value="">${placeholder}&nbsp;&nbsp;&#9207;</option>
                            ${optionsHTML}
                        </select>
                    `;
                    $(columnHeaderId).html(dropdownHTML);
                    $(`#${dropdownId}`).on('change', function () {
                        let selectedValue = $(this).val();
                        let firstOption = $(`#${dropdownId} option:first`);

                        if (selectedValue) {
                            table.column(columnIndex).search('e_' + selectedValue + '_f').draw();
                            $(this).addClass('text-primary');
                            $("#" + dropdownId + " option").css('color', 'black')
                        } else {
                            table.column(columnIndex).search('').draw();
                            $(this).removeClass('text-primary').val('');
                        }
                        firstOption.html(`${placeholder}&nbsp;&nbsp;&#9207;`);
                    });
                });
            });
        }
        // Prepare dropdown data from Blade
        let modules = "{{$modulesfilter}}".split(',').map(module => module.trim()).filter(module => module !== '');
        let types= "{{$fieldtypesfilter}}".split(',').map(type => type.trim()).filter(type => type !== '');
        initializeDropdowns('#custom-field-table', [
            {
                dropdownId: 'modulenameheader_dropdown',
                columnHeaderId: '#modulenameheader',
                optionsArray: modules,
                placeholder: 'Module Name',
            },
            {
                dropdownId: 'types_dropdown',
                columnHeaderId: '#typeheader',
                optionsArray: types,
                placeholder: 'Type',
            }
        ]);
    });
    $(document).on('click', '.delete-custom-field', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let hasData = $(this).data('hasdata');
        let confirmText, confirmTitle;
        if (hasData) {
            confirmTitle = "Warning!";
            confirmText = "This field contains existing data. Deleting it will also remove all related data from user records. Do you want to proceed?";
        } else {
            confirmTitle = "Delete Field";
            confirmText = "Are you sure you want to delete this field permanently?";
        }

        Swal.fire({
            title: confirmTitle,
            text: confirmText,
            icon: hasData ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }
            // Continue with AJAX delete
            $.ajax({
                url: window.location.origin + `/admin/custom_field/custom-fields/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Deleted!', 'Field deleted successfully.', 'success').then(() => {
                            location.reload();
                        });
                    }else if(response.redirect){
                        window.location.href = response.redirect;
                    }else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'An error occurred. Please try again.', 'error');
                }
            });
        });
    });
</script>
@endsection

