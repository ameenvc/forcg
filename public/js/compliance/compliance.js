
//Tab color change
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

/**-----------------------------------------------------------Start Regularoty Authority Javascript-------------------------------------------------------------------- */
//Clear form on Add New Regulatory Authority
$('#addRac').on('click',function(){
    $('#authname').val('');
    $('#duplicate-error').addClass('d-none');
    $('#RAC_id').val('');
    $('.dropdown_option').val('');
    $('.dropdown-option-row').not(':first').remove();
    $('#dropdown-duplicate-error').remove();
    $('#successBtn').addClass('d-none').removeClass('d-block');
    $('#dangerBtn').addClass('d-none').removeClass('d-block');
    const $inputs = $('.dropdown_option');
    $inputs.removeClass('is-invalid').css({
        'border-color': '',
        'background-color': ''
    });
});
//Form Submission with AJAX for Adding Regulatory Authority
$('#form_id').on('submit', function(e) {
    e.preventDefault();
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
    //Prevent to add duplicate
    if(!$('#duplicate-error').hasClass('d-none')){
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Regulatory Authority Name',
            text: 'Please ensure Regulatory Authority name is unique.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    // Check Duplicate Legal Document Names
    const dropdownOptions = $('.dropdown_option').map(function() {
        return $(this).val().trim().toLowerCase();
    }).get();
    const filteredOptions = dropdownOptions.filter(option => option !== '');
    const duplicates = filteredOptions.filter((item, idx) => filteredOptions.indexOf(item) !== idx);
    if (duplicates.length > 0) {
            Swal.fire({
            icon: 'error',
            title: 'Duplicate Legal Document Names',
            text: 'Please ensure all Legal Document Names are unique.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    //End of Duplicate Check
    // Proceed with AJAX submission
    var formData = new FormData(this);
    Swal.fire({
        title: 'Are you sure?',
        text: 'Are you sure to submit the request?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: window.location.origin + '/admin/compliance/addregulatory',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    'accept': 'application/json'
                },
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    var $btn = $('#form_id').find('button[type="submit"]');
                    if (!$btn.data('orig-html')) $btn.data('orig-html', $btn.html());
                    $btn.prop('disabled', true).addClass('disabled');
                    $btn.html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Submitting...');
                },
                complete: function() {
                    var $btn = $('#form_id').find('button[type="submit"]');
                    $btn.prop('disabled', false).removeClass('disabled');
                    $btn.html($btn.data('orig-html') || 'Submit');
                },
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
                        }, 500);

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
    });
    return false;
});

// Real-time duplicate option validation for legal document names in Regulatory Authority form
$(document).on('input', '.dropdown_option', function () {
    validateDropdownOptions();
});
function validateDropdownOptions() {
    const $inputs = $('.dropdown_option');
    const dropdownOptions = $inputs.map(function() {
        return $(this).val().trim().toLowerCase();
    }).get();
    const filteredOptions = dropdownOptions.filter(option => option !== '');
    const duplicates = filteredOptions.filter((item, idx) => filteredOptions.indexOf(item) !== idx);
    // Remove previous error message
    $inputs.removeClass('is-invalid').css({
        'border-color': '',
        'background-color': ''
    });
    $('#dropdown-duplicate-error').remove();
    if (duplicates.length > 0) {
        // Show error message below the options list
        $('#dropdown-options-list').after(`
            <div id="dropdown-duplicate-error" class="text-danger" style="font-size:13px;padding-left:15px;">
                Please ensure all Legal Document Names are unique.
            </div>
        `);
        $inputs.each(function() {
            const val = $(this).val().trim().toLowerCase();
            if (duplicates.includes(val) && val !== '') {
                $(this).addClass('is-invalid').css({
                    'border-color': '#dc3545',
                    'background-color': '#f8d7da'
                });
            }
        });
    }
}
//Delete Regulatory Authority
$(document).on('click', '.delete-regulatory', function (e) {
    e.preventDefault();
    let id = $(this).data('id');
    Swal.fire({
        title: '<span style="font-size:20px;color:red;">Are you sure you want to delete this authority?</span>',
        text: 'This action will delete the authority and all its legal documents from users.',
        icon: 'question',
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
            url: window.location.origin + `/admin/compliance/deleteregulatory/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire('Deleted!', response.message, 'success').then(() => {
                        location.reload();
                    });
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
//Edit Regulatory Authority
$('.edit-regulatory').on('click',function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: window.location.origin + `/admin/compliance/edit-regulatory-modal/${id}`,
        success: function (data) {
            if (data.success) {
                $('.edit-modal').html(data.view)
                const modalEle = $(`#EditRegulatoryModal`);
                modalEle.modal('show');
            } else {
                alert('Data is not found');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching edit form:', error);
            alert('An error occurred while fetching the edit form.');
        }
    });
});
//View Regulatory
$('body').on('click', '.view-regulatory', function () {
    const id = $(this).data('id');
    $.ajax({
        url : window.location.origin +`/admin/compliance/viewregulatory/${id}`,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            if (data.success) {
                $('.view-regulatory-modal').html(data.view)
                const modalEle = $(`#viewModal${data.id}`);
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
// Add new legal document option row for regulatory authority
$(document).off('click', '.add-option').on('click', '.add-option', function () {
    const optionRow = `
        <div class="input-group mb-2 dropdown-option-row">
            <input type="text" name="dropdown_options[]" class="form-control dropdown_option" placeholder="Enter Legal Document Name" style="font-size:13px;">
            <div class="input-group-append">
                <button class="btn btn-alt-danger btn-sm remove-option" type="button"><i class="fa fa-trash-alt"></i></button>
            </div>
        </div>
    `;
    $('#dropdown-options-list').append(optionRow);
});

// Remove new legal document option row for regulatory authority
$(document).off('click', '.remove-option').on('click', '.remove-option', function () {
    $(this).closest('.dropdown-option-row').remove();
    validateDropdownOptions();
});
//Check uniqueness of Authority Name
$(document).on('input', '#authname, #authname_edit', function() {
    var input = $(this).val();
    input = input ? input.trim() : '';

    // hide duplicate messages if input is empty
    if (!input) {
        if ($('#duplicate-error').length) $('#duplicate-error').addClass('d-none');
        if ($('#duplicate-error-edit').length) $('#duplicate-error-edit').addClass('d-none');
        return;
    }

    // try to find RAC_id in the closest form (edit modal) or fallback to global
    var $closestForm = $(this).closest('form');
    var RAC_id = null;
    if ($closestForm.length && $closestForm.find('#RAC_id').length) {
        RAC_id = $closestForm.find('#RAC_id').val() || null;
    } else if ($('#RAC_id').length) {
        RAC_id = $('#RAC_id').val() || null;
    }

    $.ajax({
        url: window.location.origin+'/admin/compliance/checkuniquerequlatory',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            'accept': 'application/json'
        },
        type: 'POST',
        data: {
            auth_name: input,
            RAC_id: RAC_id 
        },
        success: function(response) {
            // Editing mode (RAC_id present) -> show edit-specific message if exists
            if (RAC_id) {
                var $dupEdit = $('#duplicate-error-edit');
                if ($dupEdit.length) {
                    if (response.exists) {
                        $dupEdit.removeClass('d-none');
                    } else {
                        $dupEdit.addClass('d-none');
                    }
                }
            } else {
                // Adding mode -> show add-specific message
                var $dup = $('#duplicate-error');
                if ($dup.length) {
                    if (response.exists) {
                        $dup.removeClass('d-none');
                    } else {
                        $dup.addClass('d-none');
                    }
                }
            }
        },
        error: function(err) {
            console.error('Error checking authority name uniqueness:', err);
        }
    });
});
/**-----------------------------------------------------------End Regularoty Authority Javascript-------------------------------------------------------------------- */


/**-----------------------------------------------------------Start Legal Documents Javascript-------------------------------------------------------------------- */
//Clear form on Add Legal Document
function addDocModal($ragid){
    $('#ragid').val($ragid);
    $('.legal_documents').each(function(){
        if(this !== $('.legal_documents').first()[0]){
            $(this).closest('.legal-document-row').remove();
        } 
    });
    $('#firstDoc').val('').removeClass('is-invalid').css({
        'border-color': '',
        'background-color': ''
    });
    $('#legal-document-duplicate-error').addClass('d-none');
    $('#duplicate-error-doc').addClass('d-none');
    $('#AddDocumentModal').modal('show');
}
// Add new legal document option row for Add Legal Document form
$(document).off('click', '.add-legal-document-plus').on('click', '.add-legal-document-plus', function () {
    const optionRow = `
        <div class="input-group mb-2 legal-document-row">
            <input type="text" name="legal_documents[]" class="form-control legal_documents" placeholder="Enter Legal Document Name" style="font-size:13px;">
            <div class="input-group-append">
                <button class="btn btn-alt-danger btn-sm remove-legal-document-minus" type="button"><i class="fa fa-trash-alt"></i></button>
            </div>
        </div>
    `;
    $('#legal-document-list').append(optionRow);
});

// Remove new legal document option row for Add Legal Document form
$(document).off('click', '.remove-legal-document-minus').on('click', '.remove-legal-document-minus', function () {
    $(this).closest('.legal-document-row').remove();
    triggerLegalDocumentUniquenessCheck();
});
//Delete Legal Document
$(document).on('click', '.delete-document', function (e) {
    e.preventDefault();
    let id = $(this).data('id');
    Swal.fire({
        title: '<span style="font-size:20px;color:red;">Are you sure you want to delete this document?</span>',
        // text: 'This action will delete the authority and all its legal documents from users.',
        icon: 'question',
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
            url: window.location.origin + `/admin/compliance/deletelegaldocument/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire('Deleted!', response.message, 'success').then(() => {
                        location.reload();
                    });
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
//Form Submission with AJAX for Adding Legal Documents
$('#doc_form').on('submit', function(e) {
    e.preventDefault();
    var error_selector = $('#dangerBtnDoc');
    var success_selector = $('#successBtnDoc');

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
    // Check Duplicate Legal Document Names
    const dropdownOptions = $('.legal_documents').map(function() {
        return $(this).val().trim().toLowerCase();
    }).get();
    const filteredOptions = dropdownOptions.filter(option => option !== '');
    const duplicates = filteredOptions.filter((item, idx) => filteredOptions.indexOf(item) !== idx);
    if (duplicates.length > 0) {
            Swal.fire({
            icon: 'error',
            title: 'Duplicate Legal Document Names',
            text: 'Please ensure all Legal Document Names are unique.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    //Prevent to add duplicate
    if(!$('#duplicate-error-doc').hasClass('d-none')){
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Legal Document Name',
            text: 'Some document names already exist in the system.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if($('#legal-document-duplicate-error').length!=0){
        const msg = $('#legal-document-duplicate-error').text();
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Legal Document Names',
            text: msg,
            confirmButtonText: 'OK'
        });
        return false;
    }
    //End of Duplicate Check
    // Proceed with AJAX submission
    var formData = new FormData(this);
    Swal.fire({
        title: 'Are you sure?',
        text: 'Are you sure to submit the request?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: window.location.origin + "/admin/compliance/addlegaldocument",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    'accept': 'application/json'
                },
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    var $btn = $('#doc_form').find('button[type="submit"]');
                    if (!$btn.data('orig-html')) $btn.data('orig-html', $btn.html());
                    $btn.prop('disabled', true).addClass('disabled');
                    $btn.html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Submitting...');
                },
                complete: function() {
                    var $btn = $('#doc_form').find('button[type="submit"]');
                    $btn.prop('disabled', false).removeClass('disabled');
                    $btn.html($btn.data('orig-html') || 'Submit');
                },
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
                        }, 500);

                    }
                },
                error: function(err) {
                    console.log(err);
                    
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
    });
    return false;
});
//Edit Legal Document Modal
function editDocModal(id){
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: window.location.origin + `/admin/compliance/edit-legal-document-modal/${id}`,
        success: function (data) {
            if (data.success) {
                $('.edit-modal').html(data.view)
                const modalEle = $(`#EditLegalDocumentModal`);
                modalEle.modal('show');
            } else {
                alert('Data is not found');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching edit form:', error);
            alert('An error occurred while fetching the edit form.');
        }
    });
}
//Check uniqueness of Legal document for Add document modal
$(document).on('input', '.legal_documents', function() {
    triggerLegalDocumentUniquenessCheck();
});
function triggerLegalDocumentUniquenessCheck(){
    const $inputs = $('.legal_documents');
    const ragid = $('#ragid').val();
    const $dup = $('#duplicate-error-doc');
    $('#legal-document-duplicate-error').remove();

    const docNames = $inputs.map(function() {
        return $(this).val().trim().toLowerCase();
    }).get();
    const filteredNames = docNames.filter(doc => doc !== '');
    const duplicates = filteredNames.filter((item, idx) => filteredNames.indexOf(item) !== idx);
    // Remove previous error message
    $inputs.removeClass('is-invalid').css({
        'border-color': '',
        'background-color': ''
    });
    if (duplicates.length > 0) {
        // Show error message below the options list
        if($('#duplicate-error-doc').hasClass('d-none')){
            $('#legal-document-list').after(`
                <div id="legal-document-duplicate-error" class="text-danger" style="font-size:13px;padding-left:15px;">
                    Please ensure all legal document names are unique.
                </div>
            `);
        }else if(!$('#duplicate-error-doc').hasClass('d-none')){
            $('#legal-document-duplicate-error').remove();
            $dup.addClass('d-none');
            $('#legal-document-list').after(`
                <div id="legal-document-duplicate-error" class="text-danger" style="font-size:13px;padding-left:15px;">
                    Please ensure all legal document names are unique; some of them already exist in the system.
                </div>
            `);
        }

        $inputs.each(function() {
            const val = $(this).val().trim().toLowerCase();
            if (duplicates.includes(val) && val !== '') {
                $(this).addClass('is-invalid').css({
                    'border-color': '#dc3545',
                    'background-color': '#f8d7da'
                });
            }
        });
    }
    if(filteredNames.length>0){
        $.ajax({
            url: window.location.origin+'/admin/compliance/checkuniquelegaldocument',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            type: 'POST',
            data: {
                doc_name: filteredNames,
                rac_id: ragid 
            },
            success: function(response) {
                // Adding mode -> show add-specific message
                if ($dup.length) {
                    if (response.exists) {
                        if($('#legal-document-duplicate-error').length==0){
                            $dup.text(response.msg).removeClass('d-none');
                        }
                        // Highlight existing docs
                        $inputs.each(function() {
                            const val = $(this).val().trim().toLowerCase();
                            if (response.duplicates.map(d => d.toLowerCase()).includes(val)) {
                                $(this).addClass('is-invalid').css({
                                    'border-color': '#dc3545',
                                    'background-color': '#f8d7da'
                                });
                            }
                        });
                    } else {
                        $dup.addClass('d-none');
                    }
                }
            },
            error: function(err) {
                console.error('Error checking legal document name uniqueness:', err);
            }
        });
    }
}
//Check uniqueness of Legal document for Edit document modal
$(document).on('input', '#doc_name', function() {
    var input = $(this).val();
    var doc_id = $('#doc_id').val();
    $.ajax({
        url: window.location.origin+'/admin/compliance/checkuniquelegaldocument',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            'accept': 'application/json'
        },
        type: 'POST',
        data: {
            doc_name: input,
            doc_id: doc_id 
        },
        success: function(response) {
            var $dupEdit = $('#duplicate-error-doc-edit');
            if ($dupEdit.length) {
                if (response.exists) {
                    $dupEdit.removeClass('d-none');
                } else {
                    $dupEdit.addClass('d-none');
                }
            }
        },
        error: function(err) {
            console.error('Error checking legal document name uniqueness:', err);
        }
    });
});
/**-----------------------------------------------------------End Legal Documents Javascript-------------------------------------------------------------------- */

/**-----------------------------------------------------------Start Datatable and Page Javascript-------------------------------------------------------------------- */
// DataTable Initialization with Nested Rows and Status Filter
$(document).ready(function () {
    // 1) Build a map of nested row HTML then remove nested rows from DOM
    var nestedHtmlMap = {}; // key: authority id, value: html string

    $('#regulatoryTable tbody tr.nested-row').each(function() {
        // Expect id like "nested-123"
        var nid = $(this).attr('id'); 
        if (!nid) return;
        var id = nid.replace('nested-', '');
        // store inner content (we want the whole <td colspan=...> contents)
        nestedHtmlMap[id] = $(this).find('td').html(); 
        // remove this nested row so DataTables won't process it
        $(this).remove();
    });

    // 2) Initialize DataTable on the cleaned table
    var table = $('#regulatoryTable').DataTable({
        pageLength: 25,
        lengthMenu: [ [25, 50, 100], [25, 50, 100] ],
        order: [],
        autoWidth: false,
        columnDefs: [
            { orderable: false, targets: [0, 4] } // adjust targets if needed
        ],
        // optionally keep responsive true if desired
        responsive: true,
        language: {
            info: "Page <strong>_PAGE_</strong> of <strong>_PAGES_</strong>",
            infoEmpty: "No entries to show",
            infoFiltered: "",
            paginate: {
                previous: '<i class="fa fa-angle-left"></i>',
                next: '<i class="fa fa-angle-right"></i>',
            },
        },
    });
    $('#regulatoryTable_filter input').attr("placeholder","Search..");
    $('.dataTables_length label').each(function(){
        var $label = $(this);
        $label.contents().filter(function() {
            return this.nodeType === Node.TEXT_NODE;
        }).remove();
        $label.find('select').prevAll().remove();
        $label.find('select').nextAll().remove(); 
    });
    $('#regulatoryTable_filter label').each(function(){
        var $label = $(this);
        $label.contents().filter(function() {
            return this.nodeType === Node.TEXT_NODE;
        }).remove();
        $label.find('input').prevAll().remove();
    });
    // 3) Expand/Collapse using DataTables child rows
    // Use event delegation because buttons are inside table body controlled by DataTables
    $('#regulatoryTable tbody').on('click', '.toggle-row', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var authorityId = $btn.data('id')?.toString();
        if (!authorityId) return;

        // find the DataTable row (the parent tr)
        var $parentTr = $btn.closest('tr');
        var dtRow = table.row($parentTr);

        // Build the nested HTML wrapper - re-create the original styling wrapper
        var nestedHtml = (nestedHtmlMap[authorityId] || '<div>No documents</div>');


        if (dtRow.child.isShown()) {
            // hide it
            dtRow.child.hide();
            $btn.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            // optionally remove a class on parent tr
            $parentTr.removeClass('expanded');
        } else {
            // show child row with the stored HTML
            dtRow.child( nestedHtml, 'child-row' ).show();
            $btn.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            $parentTr.addClass('expanded');
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // 4) Status filter integration
    function initializeDropdowns(dropdownConfigs) {
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
                    table.column(columnIndex).search('^' + selectedValue + '$', true, false).draw();
                    $(this).addClass('text-primary');
                    $("#" + dropdownId + " option").css('color', 'black')
                } else {
                    table.column(columnIndex).search('').draw();
                    $(this).removeClass('text-primary').val('');
                }
                firstOption.html(`${placeholder}&nbsp;&nbsp;&#9207;`);
            });
        });
    }
    // Prepare dropdown data from Blade
    let status = "{{$statusfilter}}".split(',').map(status => status.trim()).filter(status => status !== '');
    initializeDropdowns([
        {
            dropdownId: 'statusheader_dropdown',
            columnHeaderId: '#statusheader',
            optionsArray: ['Approved', 'Pending Review', 'Rejected'],
            placeholder: 'Status',
        },
    ]);
    // 5) If you have server side or other redraws, ensure child rows are closed on table draw
    table.on('draw', function() {
        // close any open child rows when table is redrawn to avoid misalignment
        table.rows().every(function() {
            if (this.child.isShown()) this.child.hide();
        });
        // reset icons in DOM (safe approach)
        $('#regulatoryTable .toggle-row i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    });
});
// Auto-dismiss session success alerts after 2 seconds
$(function(){
    var $sess = $('.alert.alert-success').filter(function(){
        return $(this).is(':visible') && $.trim($(this).text()) !== '';
    });
    if ($sess.length) {
        var dismiss = function() {
            if ($sess.data('dismissed')) return;
            $sess.data('dismissed', true);
            $sess.fadeTo(400, 0).slideUp(400, function(){ $(this).remove(); });
            $(document).off('.autoDismiss');
        };
        // auto timeout
        var t = setTimeout(dismiss, 2000);

        // dismiss on user interaction (ignore interactions inside the alert)
        $(document).on('click.autoDismiss keydown.autoDismiss change.autoDismiss submit.autoDismiss', function(e){
            if ($(e.target).closest('.alert.alert-success').length) return;
            clearTimeout(t);
            dismiss();
        });

        // also dismiss when a Bootstrap modal is shown
        $(document).on('show.bs.modal.autoDismiss', function(){
            clearTimeout(t);
            dismiss();
        });
    }
});
/**-----------------------------------------------------------End Datatable and Page Javascript-------------------------------------------------------------------- */
