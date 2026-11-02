/******************************======================START Approve and Reject Single Document==================****************************** */
    function approveDocument($docId,$action){
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
                    url: window.location.origin + "/admin/compliance/review-doc",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        'docId' : $docId,
                        'action' : $action
                    },
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Processing...',
                            html: 'Please wait while the request is processed.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: function () {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire('Updated!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire('Error', 'An error occurred. Please try again.', 'error');
                    }
                });
            }
        });
        return false;
    }
/******************************======================End Approve and Reject Single Document==================****************************** */
/******************************======================START Approve and Reject Single Regulatory==================****************************** */
    function approveRegulatory($racId,$action){
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure to submit the request?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.location.origin + "/admin/compliance/review-auth",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        'racId' : $racId,
                        'action' : $action
                    },
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Processing...',
                            html: 'Please wait while the request is processed.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: function () {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire('Updated!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire('Error', 'An error occurred. Please try again.', 'error');
                    }
                });
            }
        });
        return false;
    }
/******************************======================END Approve and Reject Single Regulatory==================****************************** */
/******************************======================START Approve and Reject Multiple Regulatories==================****************************** */
    function approveRegulatoryMultiple($action){
        let selectedIds=[];
        $('.parent-checkbox:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure to submit the request?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.location.origin + "/admin/compliance/review-auth-multiple",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        'racIds' : selectedIds,
                        'action' : $action
                    },
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Processing...',
                            html: 'Please wait while the request is processed.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: function () {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire('Updated!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire('Error', 'An error occurred. Please try again.', 'error');
                    }
                });
            }
        });
        return false;
    }
/******************************======================End Approve and Reject Multiple Regulatories==================****************************** */
/******************************======================START Approve and Reject Multiple Documents==================****************************** */
    function approveLegalDocumentMultiple($racId,$action){
        let selectedIds=[];
        const selector = `.child-checkbox:checked[data-parent="${$racId}"]`;
        $(selector).each(function() {
            selectedIds.push($(this).data('id'));
        });
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
                    url: window.location.origin + "/admin/compliance/review-doc-multiple",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        'docIds' : selectedIds,
                        'action' : $action,
                        'racId' : $racId
                    },
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Processing...',
                            html: 'Please wait while the request is processed.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: function () {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire('Updated!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire('Error', 'An error occurred. Please try again.', 'error');
                    }
                });
            }
        });
        return false;
    }
/******************************======================End Approve and Reject Multiple Documents==================****************************** */

/**-------------------------------------------------------------START CHECKBOX----------------------------------------------------------- */

let selectedParents = {};        // parentId → true/false
let selectedChildren = {};       // parentId → { childId: true/false }

function ensureChildMap(id){
    if (!selectedChildren[id]) selectedChildren[id] = {};
}

// === Parent checkbox clicked ===
$(document).on("change", ".parent-checkbox", function() {
    let parentId = $(this).data("id").toString();
    let checked = $(this).is(":checked");

    selectedParents[parentId] = checked;
    ensureChildMap(parentId);
    $('input.parent-checkbox[data-id="'+parentId+'"]').prop("checked", checked);
});

// === Child checkbox clicked ===
$(document).on("change", ".child-checkbox", function () {
    let parentId = $(this).data("parent").toString();
    let childId = $(this).data("id").toString();

    ensureChildMap(parentId);

    selectedChildren[parentId][childId] = $(this).is(":checked");
    
    // ===== Child Select-All Sync Logic =====
    let $children = $('input.child-checkbox[data-parent="' + parentId + '"]');
    if ($children.length === 0) {
        return;
    }
    let total   = $children.length;
    let checked = $children.filter(':checked').length;
    $('#selectAllDoc' + parentId).prop('checked', total === checked);
    updateChildActionButtons(parentId);
});

// === When nested row is opened, re-sync checkboxes ===
function syncChildCheckboxes(parentId) {
    ensureChildMap(parentId);

    // get all child inputs for this parent (those just inserted into DOM)
    var $children = $('input.child-checkbox[data-parent="' + parentId + '"]');

    // if there are no child inputs in DOM, nothing to sync now
    if ($children.length === 0) {
        // still ensure parent checkbox reflects saved state
        if (selectedParents[parentId] !== undefined) {
            $('input.parent-checkbox[data-id="'+parentId+'"]').prop("checked", selectedParents[parentId]);
        }
        return;
    }

    // flag to check if we had any saved child state
    var hasSavedChildState = false;

    // 1) First pass: apply saved child state if available; collect missing ones
    $children.each(function () {
        var $c = $(this);
        var cid = $c.data('id');
        if (cid === undefined || cid === null) return;
        cid = cid.toString();

        if (selectedChildren[parentId] && selectedChildren[parentId].hasOwnProperty(cid)) {
            // apply saved value
            $c.prop('checked', !!selectedChildren[parentId][cid]);
            hasSavedChildState = true;
        }
    });

    // 2) If no child state existed but parent was previously checked, propagate parent -> children
    if (!hasSavedChildState && selectedParents[parentId] === true) {
        // mark all children checked and store in map
        $children.each(function () {
            var $c = $(this);
            var cid = $c.data('id');
            if (cid === undefined || cid === null) return;
            cid = cid.toString();
            $c.prop('checked', true);
            selectedChildren[parentId][cid] = true;
        });
        hasSavedChildState = true; // we now have child-state based on parent
    }

    // 3) For any child not present in the map, store its current DOM state into map
    $children.each(function () {
        var $c = $(this);
        var cid = $c.data('id');
        if (cid === undefined || cid === null) return;
        cid = cid.toString();
        if (!selectedChildren[parentId].hasOwnProperty(cid)) {
            selectedChildren[parentId][cid] = $c.is(':checked');
        }
    });

    // 4) Decide parent state:
    // If we have any entries in selectedChildren[parentId], compute parentChecked from them.
    // Otherwise, fall back to previously stored selectedParents[parentId] (if any).
    var childKeys = Object.keys(selectedChildren[parentId] || {});
    var parentChecked;
    if (childKeys.length > 0) {
        var allChecked = childKeys.every(function(k) { return !!selectedChildren[parentId][k]; });
        parentChecked = allChecked;
    } else {
        parentChecked = (selectedParents[parentId] === true);
    }

    // update memory and DOM parent checkbox
    selectedParents[parentId] = parentChecked;
    $('input.parent-checkbox[data-id="'+parentId+'"]').prop("checked", parentChecked);
}

/**-------------------------------------------------------------END CHECKBOX------------------------------------------------------------- */
/**--------------------------------------------------------------SELECT ALL CHECKBOX-------------------------------------------------------- */
// === Select All checkbox clicked ===
$(document).on("change", "#selectAll", function () {
    let checked = $(this).is(":checked");

    // Select ALL parent checkboxes that are not disabled
    $('.parent-checkbox').each(function () {
        // Update DOM
        let $parent = $(this);
        let parentId = $parent.data("id").toString();
        let status = ($parent.data("approved") || '').toString();

        if (status === '1') {
            return;
        }
        $(this).prop("checked", checked);

        // Update memory
        selectedParents[parentId] = checked;
        ensureChildMap(parentId);
    });
    updateActionButtons();
});

function updateSelectAllState() {
    let $enabledParents = $('.parent-checkbox:not(:disabled)');
    let total = $enabledParents.length;
    let checked = $enabledParents.filter(':checked').length;

    $('#selectAll').prop('checked', total > 0 && total === checked);
}
function updateActionButtons() {
    let hasSelection = false;
    // Check parents
    for (let pid in selectedParents) {
        if (selectedParents[pid] === true) {
            hasSelection = true;
            break;
        }
    }
    // Check children if no parent selected
    if (!hasSelection) {
        for (let pid in selectedChildren) {
            for (let cid in selectedChildren[pid]) {
                if (selectedChildren[pid][cid] === true) {
                    hasSelection = true;
                    break;
                }
            }
            if (hasSelection) break;
        }
    }

    if (hasSelection) {
        $('#approveRegulatories, #rejectRegulatories')
            .removeClass('disabled')
            .css('cursor', 'pointer');
        $('#regApproveSpan')
            .attr('data-original-title', 'Approve Regulatory Authority')
            .tooltip('update');

        $('#regRejectSpan')
            .attr('data-original-title', 'Reject Regulatory Authority')
            .tooltip('update');
    } else {
        $('#approveRegulatories, #rejectRegulatories')
            .addClass('disabled')
            .css('cursor', 'not-allowed');
        $('#regApproveSpan')
            .attr('data-original-title', 'Select the regulatory authority to enable the button.')
            .tooltip('update');

        $('#regRejectSpan')
            .attr('data-original-title', 'Select the regulatory authority to enable the button.')
            .tooltip('update');
    }
}
// Hook into existing checkbox changes
$(document).on("change", ".parent-checkbox", function () {
    updateSelectAllState();
    updateActionButtons();
});
$('#approveRegulatories, #rejectRegulatories').on('click', function (e) {
    if ($(this).hasClass('disabled')) {
        e.preventDefault();
        return false;
    }
});
// Doc Checkboxes inside nested rows select all
// === Select All checkbox clicked ===
function childSelectAll(parentId) {
    let checked = $('#selectAllDoc' + parentId).is(':checked');
    ensureChildMap(parentId);
    // Loop ONLY children of this parent
    $('input.child-checkbox[data-parent="' + parentId + '"]').each(function () {
        let $child = $(this);
        let childId = $child.data('id').toString();
        $child.prop('checked', checked);
        selectedChildren[parentId][childId] = checked;
    });
    updateChildActionButtons(parentId);
}
function updateChildActionButtons(authorityId) {

    let hasSelection = false;
    // Check only children of this authority
    if (selectedChildren[authorityId]) {
        for (let cid in selectedChildren[authorityId]) {
            if (selectedChildren[authorityId][cid] === true) {
                hasSelection = true;
                break;
            }
        }
    }
    let $approveBtn = $('#approvelegalDocumentsBtn'+authorityId);
    let $rejectBtn  = $('#rejectlegalDocumentsBtn'+authorityId);
    let $approveSpan = $('#docApproveSpan'+authorityId);
    let $rejectSpan  = $('#docRejectSpan'+authorityId);
    if (hasSelection) {
        $approveBtn.removeClass('disabled').css('cursor', 'pointer');
        $rejectBtn.removeClass('disabled').css('cursor', 'pointer');
        $approveSpan.attr('data-original-title', 'Approve Legal Documents').tooltip('update');
        $rejectSpan.attr('data-original-title', 'Reject Legal Documents').tooltip('update');
    } else {
        $approveBtn.addClass('disabled').css('cursor', 'not-allowed');
        $rejectBtn.addClass('disabled').css('cursor', 'not-allowed');
        $approveSpan.attr('data-original-title', 'Select the legal document to enable the button.').tooltip('update');
        $rejectSpan.attr('data-original-title', 'Select the legal document to enable the button.').tooltip('update');
    }
}

/**------------------------------------------------------SELECT ALL END---------------------------------------------------------------------- */
/**-----------------------------------------------------------Start Datatable and Page Javascript-------------------------------------------------------------------- */
// DataTable Initialization with Nested Rows and Status Filter
$(document).ready(function () {
    // 1) Build a map of nested row HTML then remove nested rows from DOM
    var nestedHtmlMap = {}; // key: authority id, value: html string

    $('#pendingRegulatoryTable tbody tr.nested-row').each(function() {
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
    var table = $('#pendingRegulatoryTable').DataTable({
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
    $('#pendingRegulatoryTable_filter input').attr("placeholder","Search..");
    $('.dataTables_length label').each(function(){
        var $label = $(this);
        $label.contents().filter(function() {
            return this.nodeType === Node.TEXT_NODE;
        }).remove();
        $label.find('select').prevAll().remove();
        $label.find('select').nextAll().remove(); 
    });
    $('#pendingRegulatoryTable_filter label').each(function(){
        var $label = $(this);
        $label.contents().filter(function() {
            return this.nodeType === Node.TEXT_NODE;
        }).remove();
        $label.find('input').prevAll().remove();
    });
    // 3) Expand/Collapse using DataTables child rows
    // Use event delegation because buttons are inside table body controlled by DataTables
    $('#pendingRegulatoryTable tbody').on('click', '.toggle-row', function(e) {
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
            setTimeout(() => syncChildCheckboxes(authorityId), 10);
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
        $('#pendingRegulatoryTable .toggle-row i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    });
});
/**-----------------------------------------------------------End Datatable and Page Javascript-------------------------------------------------------------------- */
