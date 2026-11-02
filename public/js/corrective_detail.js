let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var removedIssueFilenames = [];
var removedActionFilenames = [];
$(document).on('click', '.remove-action-attachment', function () {
    var filename = $(this).data('filename');
    $(this).closest('tr').remove();
    removedActionFilenames.push(filename);
    $('#actionAttachmentTable tbody tr').each(function(index) {
        $(this).find('td:first').text(index + 1);
        $(this).find('td:first').css('font-size', '10px');
    });
});
$(document).on('click', '.remove-issue-attachment', function () {
    var filename = $(this).data('filename');
    $(this).closest('tr').remove();
    removedIssueFilenames.push(filename);
    $('#issueAttachmentTable tbody tr').each(function(index) {
        $(this).find('td:first').text(index + 1);
        $(this).find('td:first').css('font-size', '10px');
    });
});
function deleteIssueFile(filename){
    $.ajax({
        url : window.location.protocol + "//" + window.location.host +`/user/corrective/${recordId}/delete-issue`,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            filename : filename,
        },
        success: function (data) {
            $('#error_issue').addClass('d-none');
            $("#success_issue")
                .stop(true, true)
                .text(`Issue file name: ${filename} has been removed successfully!`)
                .removeClass('d-none')
                .fadeIn('slow')
                .delay(2000)
                .fadeOut('slow', function() {
                    $(this).addClass('d-none').text(''); 
                });
                setTimeout(() => {
                    location.reload();
                }, 2000);
        },
        error: function (response) {
            $('#error_issue').stop(true, true).removeClass('d-none');
        }
    });
}
function deleteActionFile(filename) {
    $.ajax({
        url : window.location.protocol + "//" + window.location.host +`/user/corrective/${recordId}/delete-action`,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            filename : filename,
        },
        success: function (data) {
            $('#error_action').addClass('d-none');
            $("#success_action")
                .stop(true, true)
                .text(`Issue file name: ${filename} has been removed successfully!`)
                .removeClass('d-none')
                .fadeIn('slow')
                .delay(2000)
                .fadeOut('slow', function() {
                    $(this).addClass('d-none').text(''); 
                });
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
        },
        error: function (error) {
            $('#error_action').stop(true, true).removeClass('d-none');
        }
    });
}
function showInvestigationModal(id) {
    $.ajax({
        url: window.location.origin+`/user/corrective/corrissues/${id}/investigation`,
        type: 'GET',
        success: function (data) {
            if (data.success) {
                $('.corrective-area').html(data.view)
                const modalEle = $(`#investigationModal${data.id}`);
                modalEle.modal('show');
                var factors_array = $('#factors' + id).val();
                var influence_array = $("#influence" + id).val();
                var factors_details_selector = $('#factors_details' + id);
                var influence_details_selector = $('#influence_details' + id);

                if (in_array('others', factors_array)) {
                    factors_details_selector.removeClass('d-none').addClass('d-inline-block');
                } else {
                    factors_details_selector.removeClass('d-inline-block').addClass('d-none');
                }

                if (in_array('others', influence_array)) {
                    influence_details_selector.removeClass('d-none').addClass('d-inline-block');
                } else {
                    influence_details_selector.removeClass('d-inline-block').addClass('d-none');
                }

                if ($('#foreseeable_yes_' + id).is(":checked")) {
                    $('#prevent_block_' + id).removeClass('d-none');
                    $('#prevent_block_' + id).addClass('d-inline-block');
                } else if ($('#foreseeable_no_' + id).is(":checked")) {
                    $('#prevent_block_' + id).addClass('d-none');
                    $('#prevent_block_' + id).removeClass('d-inline-block');
                }
                modalEle.find('.js-select2').each(function() {
                    const limitSelection = $(this).attr('data-limit-selection')
                    $(this).select2({
                        width: '100%',
                        tags: true,
                        multiple: true,
                        maximumSelectionLength: limitSelection ? limitSelection : 0
                    });
                });
            } else {
                alert('Data is not found')
            }
        },
        error: function () {
            alert('Handle error');
        }
    });
}
function showInvestigationReportModal(id) {
    $.ajax({
        url: window.location.origin+`/user/corrective/corrissues/${id}/investigation-report`,
        type: 'GET',
        success: function (data) {
            if (data.success) {
                $('.corrective-area').html(data.view)
                const modalEle = $(`#viewReportModal`);
                modalEle.modal('show');
            } else {
                alert('Data is not found')
            }
        },
        error: function () {
            alert('Handle error');
        }
    });
}
$(document).ready(function() {
    $(`#factors` + recordId).select2({
        dropdownParent: $(`#factors_append` + recordId)
    });
    $(`#influence` + recordId).select2({
        dropdownParent: $(`#influence_append` + recordId)
    });
    $(`#team` + recordId).select2({
        dropdownParent: $(`#team_append` + recordId)
    });
    $(`#lead` + recordId).select2({
        dropdownParent: $(`#lead_append` + recordId)
    });
        
    function getValuesByAttribute(id, attribute) {
        return Array.from(document.querySelectorAll(`[data-foo=${attribute}${id}]`)).map(inp => inp.value);
    }

    $('body').on('click', '.btn-submit-investigation', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        const submitButton = $(this);
        const modal = $(this).closest('.investigation-modal');
        const errorSelector = modal.find(`#error_investigation${id}`);
        const validationErrorSelector = modal.find(`#validation_error_investigation${id}`);
        const successSelector = modal.find(`#success_investigation${id}`);
        const formData = new FormData();

        const appendArrayToFormData = (key, array) => {
            array.forEach(item => formData.append(key, item));
        };

        formData.append('corrective_id', $(`#corrective_id${id}`).val());
        formData.append('occurrence', $("input[name='occurrence']:checked").val() || 0);
        formData.append('system_related_problems', $(`#system_related_problems${id}`).val());
        formData.append('recent_changes', $("input[name='recent_changes']:checked").val() || 0);
        formData.append('follow_procedures', $("input[name='follow_procedures']:checked").val() || 0);
        formData.append('issue_foreseeable', $("input[name='issue_foreseeable']:checked").val() || 0);
        formData.append('prevent_reason', $(`#prevent_reason${id}`).val());
        formData.append('factors_details', $(`#factors_details_val${id}`).val());
        formData.append('influence_details', $(`#influence_details_val${id}`).val());
        formData.append('removedIssueFilenames', JSON.stringify(removedIssueFilenames));
        formData.append('removedActionFilenames', JSON.stringify(removedActionFilenames));

        appendArrayToFormData('rootcause[]', getValuesByAttribute(id, 'r'));
        appendArrayToFormData('correctiveaction[]', getValuesByAttribute(id, 'c'));
        appendArrayToFormData('department[]', getValuesByAttribute(id, 'd'));
        appendArrayToFormData('responsibility[]', getValuesByAttribute(id, 'resp'));
        appendArrayToFormData('target_date[]', getValuesByAttribute(id, 'tar'));

        const appendFilesToFormData = (key, files) => {
            Array.from(files).forEach(file => formData.append(key, file));
        };

        appendFilesToFormData('issue_file[]', $('#issue_file')[0].files);
        appendFilesToFormData('action_file[]', $('#action_file')[0].files);

        const factors = $(`#factors${id}`).val() || [];
        const influence = $(`#influence${id}`).val() || [];
        appendArrayToFormData('contributing_factors[]', factors.length ? factors : [""]);
        appendArrayToFormData('external_factors[]', influence.length ? influence : [""]);
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: window.location.origin + `/user/corrective/submit-investigation`,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
            },
            success: function (response) {
                errorSelector.addClass('d-none').removeClass('d-block');
                validationErrorSelector.addClass('d-none').removeClass('d-block');
                successSelector.addClass('d-block').removeClass('d-none');
                $('#rootcauseTable').empty().html(response.table);
                $('#attachmentSection').empty().html(response.attachments);
                $('#processStage').empty().html(response.processStage);
                removedIssueFilenames = [];
                removedActionFilenames = [];
                setTimeout(() => {
                    submitButton.html('Submit');
                    modal.modal('hide');
                }, 2000);
            },
            error: function (err) {
                submitButton.html('Submit');
                if (err.status === 500) {
                    errorSelector.addClass('d-block').removeClass('d-none');
                    validationErrorSelector.addClass('d-none').removeClass('d-block');
                    successSelector.addClass('d-none').removeClass('d-block');
                } else if (err.status === 422) {
                    errorSelector.addClass('d-none').removeClass('d-block');
                    successSelector.addClass('d-none').removeClass('d-block');
                    validationErrorSelector.addClass('d-block').removeClass('d-none');

                    const errorText = Object.values(err.responseJSON.errors)
                        .flat()
                        .map(val => `<li style="font-size:12px;">${val}</li>`)
                        .join('');
                    validationErrorSelector.html(errorText);
                }
            }
        });
    });
});

// toggle part done
function ifChecked(id) {
    const preventBlockSelector = $('#prevent_block_' + id);
    const isCheckedYes = $('#foreseeable_yes_' + id).is(":checked");
    preventBlockSelector.toggleClass('d-none', !isCheckedYes).toggleClass('d-inline-block', isCheckedYes);
}
function in_array(needle, haystack) {
    return haystack.includes(needle);
}

function toggleVisibility(selector, condition) {
    selector.toggleClass('d-none', !condition).toggleClass('d-inline-block', condition);
}

function displayDetails(id) {
    const factors_details_selector = $('#factors_details' + id);
    const hasOthers = in_array('others', $('#factors' + id).val());
    toggleVisibility(factors_details_selector, hasOthers);
}

function displayInfluenceDetails(id) {
    const influence_details_selector = $('#influence_details' + id);
    const hasOthers = in_array('others', $('#influence' + id).val());
    toggleVisibility(influence_details_selector, hasOthers);
}

/**--------MOC DETAILS VIEW ------- */
$('body').on('click', '.view-moc-detail', function () {
    const id = $(this).attr('data-route');
    $.ajax({
        url : window.location.origin+`/user/context/changelog/${id}/show`,
        type: 'GET',
        success: function (data) {
            if (data.success) {
                $('#view-detail').html(data.view)
                const modalEle = $(`#viewModal${data.id}`);
                modalEle.modal('show');
            } else {
                alert('Data is not found')
            }
        },
        error: function () {
            alert('Handle error');
        }
    })
});