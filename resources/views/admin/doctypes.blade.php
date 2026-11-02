@extends('admin.datatable')
@section('content')

<style>
/* .parent-table.hovered.table-bordered > tbody > tr:hover > td {
    background-color: #dddddd;
    border-color: #dddddd;
} */
.draggable-row {
    cursor: grab;
}
.draggable-row:active {
    cursor: grabbing;
}
.dragging {
    border: 2px solid #28a745 !important;
    background-color: #f8f9fa !important;
}
.draggable-row.drag-over {
    border-bottom: 2px dashed #28a745 !important;
}
a.btn-disabled {
    opacity: 0.6;
}
.content-item.hovered:hover {
    background-color: #dddddd;
    border-color: #dddddd;
}
.label-invalid {
    padding-left: 0;
    padding-right: 0;
    font-size: 13px;
    color: red;
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Page Content -->
<div class="content">
    <!-- Dynamic Table Full -->
    @if(Session::has('msg'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
    @endif
    <div class="block block-rounded" >
        <div class="block-header">
            <h3 class="block-title">MS.03.01 Types Table</h3>
            <div class="block-options">
                <button type="button" class="btn btn-sm btn-alt-primary mr-1 btn-drag-drop"
                        title="Drag and drop mode"
                        data-toggle="tooltip">
                    <i class="fa fa-hand-pointer"></i>
                </button>
                <button type="button" id="saveOrder" class="btn btn-sm btn-success" style="display: none;" data-toggle="tooltip" title="Save the new order">
                    <i class="fa fa-save"></i>
                </button>
                <button type="button" id="resetOrder" class="btn btn-sm btn-warning" style="display: none;" data-toggle="tooltip" title="Reset to last saved state">
                    <i class="fa fa-undo"></i>
                </button>
            </div>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        Document Management
                    </li>
                </ol>
            </nav>
        </div>
        <div class="block-content block-content-full"  style="width:84%;">
            <div class="d-flex w-100 justify-content-end mb-2">
                <button class="btn btn-primary btn-sm px-3" id="btn-add-type">Add</button>
            </div>
            <div class="table-responsive">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full js-table-drag-drop" width="100%">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 1%;font-size:10px;text-transform:capitalize">Level</th>
                        <th class="d-sm-table-cell text-left" style="width: 2%;font-size:10px;text-transform:capitalize">Code</th>
                        <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Type</th>
                        <th class="text-left"style="width: 5%;font-size:10px;text-transform:capitalize" >Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach($doctypes as $c)
                    {{csrf_field()}}  
                    <?php $id = $c->id ?>
                    <tr @if(!$c->is_default)class="content-item tr-draggable"@endif data-content-id="{{ $c->id }}" data-level="{{ $c->level }}" draggable="false">
                        <td style="font-size:10px" class="text-left">
                                <span style="color: #3366CC;font-size:13px;" class="font-w600 row-number">{{$c->level}}</span>
                        </td>
                        <?php $number++; ?>
                        <td style="font-size:10px" class="text-left">
                            <span>{{$c->type_code}}</span>
                        </td>
                        <td style="font-size:10px" class="text-left">
                                <span>{{$c->typename}}</span>
                        </td>
                        <td class="text-left">
                            <div class="btn-group">
                                @if($c->is_default)
                                    <a href="javascript:void(0)" class="btn btn-sm btn-alt-primary btn-edit-type"
                                       data-route="{{ route('admin.editdoctype', ['id' => $c->id]) }}"
                                       style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-sm btn-alt-primary btn-edit-type"
                                       data-route="{{ route('admin.editdoctype', ['id' => $c->id]) }}"
                                       style="font-size:10px" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                    <a href="{{url('/admin/removedoctype?val='.$id)}}" class="btn btn-sm btn-alt-primary"onclick="return confirm('Are you sure?')"  style="font-size:10px"  data-toggle="tooltip" title="Delete" ><i class="fa fa-fw fa-times"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                
                </tbody>
                @if(Session::has('message'))
                    <div class="alert alert-primary">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('message')!!}
                    </div>
                @endif
            </table>  
            <!-- END Dynamic Table Full -->
            </div>
        </div>
    </div>
</div>
<div class="doctype_area">
    @include('admin._doctypes_form')
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Get predefined types from Laravel config
    const predefinedTypes = @json(config('constants.document_management.default_type'));
    let debounceTimer;

    // Function to reset form to add mode
    function resetFormToAddMode() {
        const $typeName = $('#type_name');
        const $typeCode = $('#type_code');
        $typeName.val('');
        $typeCode.val('');
        $typeName.removeAttr('readonly');

        $('.error-type_name').text('');
        $('.error-type_code').text('');

        $('#similarity-warning').hide();
        $('#exact-match-error').hide();
        $('.modal-title').text('Add Type');

        $('.register-doc-type').attr('data-url', '{{ route('admin.adddoctype') }}');
        $typeName.removeClass('is-invalid');
        $typeName.next('.invalid-feedback').remove();
        $typeCode.removeClass('is-invalid');
        $typeCode.next('.invalid-feedback').remove();
    }

    $('.btn-reset-add').on('click', function() {
        resetFormToAddMode();
    });
    $('body').on('click', '.btn-reset-edit', function() {
        const $typeName = $('#type_name');
        const $typeCode = $('#type_code');
        $typeName.val($(this).data('name'));
        $typeCode.val($(this).data('code'));
        $typeName.removeClass('is-invalid');
        $typeName.next('.invalid-feedback').remove();
        $typeCode.removeClass('is-invalid');
        $typeCode.next('.invalid-feedback').remove();
        $('#similarity-warning').hide();
        $('#exact-match-error').hide();
    });

    $('body').on('hidden.bs.modal', '#form_type', function() {
        resetFormToAddMode();
        $('.btn-reset-form').removeClass('btn-reset-edit').addClass('btn-reset-add');
    });

    $('#btn-add-type').on('click', function() {
        resetFormToAddMode();
        $('#form_type').modal('show');
    });

    // Function to calculate Levenshtein distance (string similarity)
    function levenshteinDistance(str1, str2) {
        const matrix = [];
        const len1 = str1.length;
        const len2 = str2.length;

        if (len1 === 0) return len2;
        if (len2 === 0) return len1;

        // Initialize matrix
        for (let i = 0; i <= len2; i++) {
            matrix[i] = [i];
        }
        for (let j = 0; j <= len1; j++) {
            matrix[0][j] = j;
        }

        // Fill matrix
        for (let i = 1; i <= len2; i++) {
            for (let j = 1; j <= len1; j++) {
                if (str2.charAt(i - 1) === str1.charAt(j - 1)) {
                    matrix[i][j] = matrix[i - 1][j - 1];
                } else {
                    matrix[i][j] = Math.min(
                        matrix[i - 1][j - 1] + 1, // substitution
                        matrix[i][j - 1] + 1,     // insertion
                        matrix[i - 1][j] + 1      // deletion
                    );
                }
            }
        }

        return matrix[len2][len1];
    }

    // Function to calculate similarity percentage
    function calculateSimilarity(str1, str2) {
        const maxLength = Math.max(str1.length, str2.length);
        if (maxLength === 0) return 100;

        const distance = levenshteinDistance(str1.toLowerCase(), str2.toLowerCase());
        return ((maxLength - distance) / maxLength) * 100;
    }

    // Function to check similarity and show warning/error
    function checkSimilarity(inputValue) {
        const addButton = $('input[name="update"]');

        if (!inputValue || inputValue.trim().length < 1) {
            $('#similarity-warning').hide();
            $('#exact-match-error').hide();
            addButton.prop('disabled', false).removeClass('btn-disabled');
            return;
        }

        const trimmedInput = inputValue.trim();
        let mostSimilarType = null;
        let highestSimilarity = 0;
        let exactMatch = false;

        // Check against each predefined type
        predefinedTypes.forEach(function(type) {
            const similarity = calculateSimilarity(trimmedInput, type);

            // Check for exact match (case insensitive)
            if (trimmedInput.toLowerCase() === type.toLowerCase()) {
                exactMatch = true;
                mostSimilarType = type;
            }
            // Consider it similar if similarity is >= 60% and input is not exactly the same
            else if (similarity >= 60 && similarity < 100 && highestSimilarity < similarity) {
                highestSimilarity = similarity;
                mostSimilarType = type;
            }
        });

        // Handle exact match - show error and disable button
        if (exactMatch) {
            $('#similarity-warning').hide();
            const errorMessage = `This type '<strong>${mostSimilarType}</strong>' already exists as a system-defined type. Please choose a different name.`;
            $('#error-text').html(errorMessage);
            $('#exact-match-error').slideDown(200);
            addButton.prop('disabled', true).addClass('btn-disabled');
        }
        // Handle similar but not exact match - show warning but keep button enabled
        else if (mostSimilarType && highestSimilarity >= 60) {
            $('#exact-match-error').hide();
            const warningMessage = `This name is very similar to an existing system-defined type: '<strong>${mostSimilarType}</strong>'. Consider using a different name to avoid confusion.`;
            $('#warning-text').html(warningMessage);
            $('#similarity-warning').slideDown(200);
            addButton.prop('disabled', false).removeClass('btn-disabled');
        }
        // No match found - clear all messages and enable button
        else {
            $('#similarity-warning').slideUp(200);
            $('#exact-match-error').slideUp(200);
            addButton.prop('disabled', false).removeClass('btn-disabled');
        }
    }

    // Function to check if string contains emoji characters
    function containsEmoji(str) {
        const emojiRegex = /[\u{1F600}-\u{1F64F}]|[\u{1F300}-\u{1F5FF}]|[\u{1F680}-\u{1F6FF}]|[\u{1F1E0}-\u{1F1FF}]|[\u{2600}-\u{26FF}]|[\u{2700}-\u{27BF}]|[\u{1F900}-\u{1F9FF}]|[\u{1F018}-\u{1F270}]|[\u{238C}-\u{2454}]|[\u{20D0}-\u{20FF}]|[\u{FE0F}]|[\u{200D}]/gu;
        return emojiRegex.test(str);
    }

    // Event listeners for real-time checking
    $('body').on('input keyup paste', '#type_name', function() {
        const inputValue = $(this).val();
        
        // Clear any existing invalid feedback first
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
        
        // Check for emoji characters
        if (containsEmoji(inputValue)) {
            $(this).addClass('is-invalid');
            $(this).after('<div class="invalid-feedback">The type name contains invalid characters</div>');
            // Disable the save button when emoji is present
            $('.register-doc-type').prop('disabled', true).addClass('btn-disabled');
            return; // Don't proceed with similarity check if emoji is found
        } else {
            // Re-enable the save button if no emoji is present
            $('.register-doc-type').prop('disabled', false).removeClass('btn-disabled');
        }

        // Debounce the check to avoid too many calls
        clearTimeout(window.similarityCheckTimeout);
        window.similarityCheckTimeout = setTimeout(function() {
            checkSimilarity(inputValue);
        }, 300);
    });

    // Clear warning when input is focused (optional UX improvement)
    $('body').on('focus', '#type_name', function() {
        if ($(this).val().trim() === '') {
            $('#similarity-warning').hide();
            $('#exact-match-error').hide();
        }
    });

    const checkDocTypeCode = () => {
        const $doctypeCode = $('#type_code');
        const doctypeId = $doctypeCode.attr('data-doctype-id');
        $.ajax({
            url: "{{ route('admin.doctype.validation.code') }}",
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            data: {
                code: $doctypeCode.val(),
                id: doctypeId || '',
            },
            success: function(response) {
                if (response.exists) {
                    $doctypeCode.addClass('is-invalid');
                    $doctypeCode.next('.invalid-feedback').remove();
                    $doctypeCode.after('<div class="invalid-feedback">The type code has already been taken.</div>');
                } else {
                    $doctypeCode.removeClass('is-invalid');
                    $doctypeCode.next('.invalid-feedback').remove();
                }
            },
            error: function(err) {
                console.error('Error checking document number:', err);
            }
        });
    }
    const generateCode = (name) => {
        $('.label-invalid').html("");
        $('.invalid-feedback').html("");
        $('input[type=text]').removeClass('is-invalid');
        $.ajax({
            url: "{{ route('admin.doctype.code') }}",
            method: 'POST',
            data: {
                name: name,
                _token: $('meta[name="csrf-token"]').attr('content') ||
                           (window.Laravel && window.Laravel.csrfToken) ||
                           $('input[name="_token"]').val()
            },
            success: function(response) {
                if(response.code) {
                    $('#type_code').val(response.code);
                } else {
                    $('#type_code').val('');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error generating code:', error);
                $('#type_code').val('');
                $('#type_name').addClass('is-invalid');
                $('#type_name').next('.invalid-feedback').remove();
                $('#type_name').after('<div class="invalid-feedback">The type name contains invalid characters</div>');
            }
        });
    }
    $('body').on('input keyup paste', '#type_name', function() {
        let typeName = $(this).val();
        const $this = $(this);

        // Clear the previous timer
        clearTimeout(debounceTimer);

        if (typeName) {
            // Check for emoji characters before proceeding
            if (containsEmoji(typeName)) {
                // Don't generate code or check validation if emoji is present
                $('#type_code').val('');
                return;
            }
            
            // Set a new timer with 500ms delay
            debounceTimer = setTimeout(function() {
                typeName = $('#type_name').val();
                generateCode(typeName);
                checkDocTypeCode();
            }, 500);
        } else {
            $('#type_code').val('');
            $('.label-invalid').html("");
            $('.invalid-feedback').html("");
            $('input[type=text]').removeClass('is-invalid');
        }
    });
    $('body').on('input keyup paste', '#type_code', function() {
        const $code = $(this).val();
        const $this = $(this);

        // Clear any existing timer
        clearTimeout(debounceTimer);

        // Remove validation styles immediately for better UX
        $this.removeClass('is-invalid');
        $this.next('.invalid-feedback').remove();

        if ($code) {
            // Set a new timer with 500ms delay
            debounceTimer = setTimeout(function() {
                checkDocTypeCode();
            }, 500); // 500ms debounce delay
        }
    });

    $('body').on('click', '.register-doc-type', function () {
        const url = $(this).attr('data-url');
        const typeName = $('#type_name').val().trim();
        const typeCode = $('#type_code').val().trim();
        const $this = $(this);
        
        // Prevent form submission if emoji is detected
        if (containsEmoji(typeName)) {
            $('#type_name').addClass('is-invalid');
            $('#type_name').next('.invalid-feedback').remove();
            $('#type_name').after('<div class="invalid-feedback">The type name contains invalid characters</div>');
            return false; // Prevent form submission
        }
        
        $('.label-invalid').html("");
        $('.invalid-feedback').html("");
        $('input[type=text]').removeClass('is-invalid');
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                type_name: typeName,
                type_code: typeCode,
                _token: $('meta[name="csrf-token"]').attr('content') ||
                           (window.Laravel && window.Laravel.csrfToken) ||
                           $('input[name="_token"]').val()
            },
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message || 'An error occurred while adding the document type.',
                        icon: 'error',
                        timer: 3000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.reload();
                    });
                }
            },
            error: function (response) {
                const status = response.status;
                const data = response.responseJSON;
                if (status === 422) {
                    $.each(data.errors, function (field, object) {
                        const errorString = object.join('、')
                        $('.error-' + field).html(errorString)
                        $('#' + field).addClass('is-invalid')
                    })
                } else {
                    alert(data.message)
                }
            }
        })
    })
    $('.btn-edit-type').on('click', function () {
        const url = $(this).attr('data-route');
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    $('.doctype_area').html(response.view);
                    $('#form_type').modal('show');
                } else {
                    alert(response.message || 'An error occurred while fetching the document type details.');
                }
            },
            error: function (response) {
                const status = response.status;
                const data = response.responseJSON;
                alert(data.message || 'An error occurred while processing your request.')
            }
        })
    })
});

// Form validation function to prevent submission of exact matches
function validateForm() {
    const addButton = $('input[name="update"]');
    const inputValue = $('#example-text-input').val().trim();

    // Check if button is disabled (exact match exists)
    if (addButton.prop('disabled') || addButton.hasClass('btn-disabled')) {
        alert('Cannot add this type as it already exists as a system-defined type. Please choose a different name.');
        return false;
    }

    // Additional validation - check if input is empty
    if (!inputValue) {
        alert('Please enter a type name.');
        return false;
    }

    return true;
}
</script>


<!-- Hidden CSRF token for AJAX requests -->
<input type="hidden" name="_token" value="{{ csrf_token() }}">

<script>
$(function () {
    // Ensure CSRF token is available
    if (typeof window.Laravel === 'undefined') {
        window.Laravel = {};
    }
    if (!window.Laravel.csrfToken) {
        window.Laravel.csrfToken = $('meta[name="csrf-token"]').attr('content');
    }

    var originalOrder = [];
    var originalLevels = [];
    var hasChanges = false;
    var newOrder = [];

    // Store original order and levels
    function storeOriginalOrder() {
        originalOrder = [];
        originalLevels = [];
        $('.tr-draggable').each(function() {
            const id = $(this).data('content-id');
            const level = $(this).data('level');
            originalOrder.push({
                id: id,
                level: level
            });
            originalLevels.push(level);
        });
    }

    // Check if order has changed
    function checkForChanges() {
        let hasChanges = newOrder.length > 0;

        if (hasChanges) {
            $('#saveOrder, #resetOrder').show();
        } else {
            $('#saveOrder, #resetOrder').hide();
        }
    }

    $('body').on('click', '.btn-drag-drop-disabled', function () {
        $(this).html('<i class="fa fa-hand-pointer"></i>');
        $(this).removeClass('btn-drag-drop-disabled');
        $(this).addClass('btn-drag-drop');
        $('.js-table-drag-drop').find('.tr-draggable').each(function () {
            $(this).attr('draggable', false);
            $(this).removeClass('draggable-row');
        });
        $(this).attr('title', 'Drag and drop mode');
        $('.parent-table').removeClass('hovered');
        $('.content-item').removeClass('hovered');
        $('#saveOrder, #resetOrder').hide();
        hasChanges = false;
    }).on('click', '.btn-drag-drop', function () {
        $(this).html('<i class="fa fa-mouse-pointer"></i>');
        $(this).removeClass('btn-drag-drop');
        $(this).addClass('btn-drag-drop-disabled');
        $('.js-table-drag-drop').find('.tr-draggable').each(function () {
            $(this).attr('draggable', true);
            $(this).addClass('draggable-row');
        });
        $('.parent-table').addClass('hovered');
        $('.content-item').addClass('hovered');
        storeOriginalOrder();
        dragdrop(); // call dragdrop function
    });

    // Save order button
    $('#saveOrder').click(function() {
        let reorderedData = [];
        // Prepare new order data
        // Loop through the rows to get the new order
        for (let contentId in newOrder) {
            reorderedData.push({
                id: contentId,
                level: newOrder[contentId].level,
                position: newOrder[contentId].position
            });
        }
        console.log(newOrder, reorderedData)

        // Show confirmation dialog with SweetAlert2
        Swal.fire({
            title: 'Save Changes?',
            text: 'Are you sure you want to save the new order? This will permanently update the document type hierarchy.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, save!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Get CSRF token
                var csrfToken = $('meta[name="csrf-token"]').attr('content') ||
                               (window.Laravel && window.Laravel.csrfToken) ||
                               $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('admin.reorderdoctypes') }}",
                    method: 'POST',
                    data: {
                        reordered_data: reorderedData,
                        _token: csrfToken
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Document types reordered successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = 'An error occurred while saving the order.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // Reset order button
    $('#resetOrder').click(function() {
        Swal.fire({
            title: 'Reset Order?',
            text: 'Are you sure you want to reset to the last saved state? All unsaved changes will be lost.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, reset!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        });
    });

    function dragdrop() {
        let startIndex;
        let draggedTh;
        let dataContentId;
        const $table = $('.js-table-drag-drop');
        if ($table.length === 0) {
            console.error('Table not found: .js-table-drag-drop');
            return;
        }

        // Initialize drag events for the headers
        const draggableElements = $('body').find('.js-table-drag-drop tbody tr[draggable="true"].draggable-row');
        draggableElements.on('dragstart', function(e) {
            draggedTh = this;
            startIndex = $(this).index();
            dataContentId = $(this).attr('data-content-id');
            $(this).addClass('dragging');
        }).on('dragover', function(e) {
            e.preventDefault();
            e.originalEvent.dataTransfer.dropEffect = 'move';
            const mouseY = e.originalEvent.clientY;
            const screenHeight = window.innerHeight;
            const scrollStep = 5;  // speed of scroll
            const edgeThreshold = 100;

            // If mouse is near top, scroll up
            if (mouseY < edgeThreshold) {
                window.scrollBy(0, -scrollStep);
            }
            // If mouse is near bottom, scroll down
            else if (screenHeight - mouseY < edgeThreshold) {
                window.scrollBy(0, scrollStep);
            }
        }).on('dragend', function(e) {
            $(this).removeClass('dragging');
        }).on("dragover", function (event) {
            event.preventDefault(); // Allow drop
            $(this).addClass("drag-over");
        }).on("dragleave", function () {
            $(this).removeClass("drag-over");
        }).on('drop', function(e) {
            e.preventDefault();
            const targetContentId = $(this).attr('data-content-id');
            $(this).removeClass("drag-over");
            if (draggedTh !== this) {
                // Move the row
                var targetRow = $(this);
                var draggedRow = $(draggedTh);
                const tempLevel = draggedRow.data('level');
                draggedRow.data('level', targetRow.data('level'));
                draggedRow.find('.row-number').text(draggedRow.data('level'));
                targetRow.data('level', tempLevel);
                targetRow.find('.row-number').text(targetRow.data('level'));

                if (draggedRow.index() < targetRow.index()) {
                    draggedRow.insertAfter(targetRow);
                } else {
                    targetRow.insertAfter(draggedRow);
                }

                // Get the list of ID changes and assign levels based on position
                const currentIds = [];
                $('.tr-draggable').each(function() {
                    currentIds.push($(this).data('content-id'));
                });
                
                // Get original IDs in order
                const originalIds = originalOrder.map(item => item.id);
                
                // Loop through current elements and assign levels based on position
                $('.tr-draggable').each(function(index) {
                    const contentId = $(this).data('content-id');
                    
                    // Check if this ID has been swapped (position changed)
                    const originalIndex = originalIds.indexOf(contentId);
                    const hasPositionChanged = originalIndex !== index;
                    
                    if (hasPositionChanged) {
                        const newLevel = originalLevels[index]; // Get level from original sequence
                        const newPosition = index + 1;
                        
                        // Update the data and display
                        $(this).data('level', newLevel);
                        $(this).find('.row-number').text(newLevel);
                        
                        // Store the new order
                        newOrder[contentId] = {
                            position: newPosition,
                            level: newLevel
                        };
                    }
                });



                // Check for changes
                checkForChanges();
            }
            $(draggedTh).removeClass('dragging');
        }).on('dragend', function() {
            $(this).removeClass('dragging');
        });
    };

    // Initialize
    storeOriginalOrder();
});
</script>
@endsection