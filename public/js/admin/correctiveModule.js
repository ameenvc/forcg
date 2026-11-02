let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

function enableSource(id) {
    $.ajax({
        type: 'POST'
        , url: window.location.origin + "/admin/ajaxEnableSource"
        , data: {
            'id': id
            , 'is_checked': $('#source' + id).is(':checked')
            , _token: CSRF_TOKEN
        }
        , success: function(response) {
            window.location.reload();
        }
        , error: function(err) {
            console.log(err);
        }
    });

}
function enableDetectionStage(id) {
    $.ajax({
        type: 'POST'
        , url: window.location.origin + "/admin/enable-detection-stage"
        , data: {
            'id': id
            , 'is_checked': $('#detectionStage' + id).is(':checked')
            , _token: CSRF_TOKEN
        }
        , success: function(response) {
            window.location.reload();
        }
        , error: function(err) {
            console.log(err);
        }
    });

}
function changeCategory(value) {
    let other_selector = $('#if_other_selected');
    if(value=='Product Non- Conformity'){
        $('#pnc_yes').attr('required', true).prop('checked', false);
        $('#pnc_no').attr('required', true).prop('checked', false);
        $('#pnc-question-category').removeClass('d-none');
    }else if(value=='Process Non- Conformity'){
        $('#pnc_yes').attr('required', true).prop('checked', false);
        $('#pnc_no').attr('required', true).prop('checked', false);
        $('#pnc-question-category').removeClass('d-none');
    }else if(value=='Vendor Non- Conformity'){
        $('#pnc_yes').attr('required', true).prop('checked', false);
        $('#pnc_no').attr('required', true).prop('checked', false);
        $('#pnc-question-category').removeClass('d-none');
    }else{
        $('#pnc-question-category').addClass('d-none');
        $('#pnc_yes').attr('required', false).prop('checked', false);
        $('#pnc_no').attr('required', false).prop('checked', false);
    }
    $('#product_non_conformity_section').addClass('d-none');
    if (value === 'other') {
        other_selector.removeClass('d-none');
        other_selector.addClass('d-inline-block');
        $('#error_pnc').addClass('d-none');
        $('#submitBtn').prop('disabled', false);
    } else {
        other_selector.addClass('d-none');
        other_selector.removeClass('d-inline-block');
        validation(value);
    }
}
$('.closeBtn').on('click', function () {
    $('#name').val('');
    // Reset select2 / custom selects if any
    $('#category').val(null).trigger('change');
    // Hide sections
    $('#product_non_conformity_section').addClass('d-none');
    $('#pnc-question-category').addClass('d-none');
    $('#error_pnc').addClass('d-none');
    // Reset PNC radios
    $('#pnc_yes, #pnc_no')
        .prop('checked', false)
        .prop('required', false);

    // Reset PNC table fields
    $('.select-field').prop('checked', false);
    $('.display-label')
        .val(function () {
            return $(this).data('old'); // restore initial DB value
        })
        .prop('disabled', true)
        .prop('required', false);

    $('.mandatory-field')
        .prop('checked', false)
        .prop('disabled', true);
});

$('#name').on('input', function() {
    let category_value = $(this).val();
    validation(category_value);
});
function validation(category_value){  
    $.ajax({
        url: window.location.origin + "/admin/check-category",
        type: 'GET',
        data: { category_name: category_value,'type' : 'add' },
        dataType: 'json',
        success: function(response) {
            if (response.exists) {
                $('#error_pnc').html(response.msg).removeClass('d-none');
                $('#submitBtn').prop('disabled', true);
            } else {
                $('#error_pnc').html('').addClass('d-none');
                $('#submitBtn').prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error checking Category:", error);
        }
    });
}
$('#pnc_yes, #pnc_no').on('change', function(){
    if($('#pnc_yes').is(':checked')){
        $('#product_non_conformity_section').removeClass('d-none');
        let $category = $('#category').val();
        if($category === 'Product Non- Conformity'){
            $('.productRow').removeClass('d-none');
            $('.processRow, .vendorRow').addClass('d-none');
        }else if($category === 'Process Non- Conformity'){
            $('.processRow').removeClass('d-none');
            $('.productRow, .vendorRow').addClass('d-none');
        }else if($category === 'Vendor Non- Conformity'){
            $('.vendorRow').removeClass('d-none');
            $('.productRow, .processRow').addClass('d-none');
        }
    }else{
        $('#product_non_conformity_section').addClass('d-none');
        $('.select-field').prop('checked', false);
        $('.display-label').each(function () {
            const oldValue = $(this).data('old-value');
            $(this).val(oldValue).prop('disabled', true).attr('required', false);
        });

        $('.mandatory-field').prop('disabled', true).prop('checked', false);
    }
});
$(`#add_category_form`).on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    let error_selector = $('#error_msg');
    let validation_error_selector = $('#validation_error_msg');
    let success_selector = $('#success_msg');

    $.ajax({
        type: 'POST', 
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, 
        url: window.location.origin + "/admin/add-category",
        data: formData, 
        processData: false, 
        contentType: false,  
        cache: false,
        success: function(response) {
            if (response.error !== '' && response.error !== undefined) {
                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.removeClass('d-none')
                validation_error_selector.addClass('d-block');

                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');
                validation_error_selector.html(response.error);

            } else {
                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.removeClass('d-block')
                validation_error_selector.addClass('d-none');

                success_selector.removeClass('d-none');
                success_selector.addClass('d-block');

                setTimeout(function() {
                    window.location.reload();
                }, 2000);

            }

        }, 
        error: function(err) {
            if (err.status === 500) {
                error_selector.removeClass('d-none');
                error_selector.addClass('d-block');

                validation_error_selector.removeClass('d-block')
                validation_error_selector.addClass('d-none');

                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');
            } else if (err.status === 422) {
                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');

                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.addClass('d-block')
                validation_error_selector.removeClass('d-none');

                var error_text = '';
                $.each(err.responseJSON.errors, function(i) {
                    $.each(err.responseJSON.errors[i], function(key, val) {
                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                    });
                });
                validation_error_selector.html(error_text);


            }
        }
    });
});


let add_type_url = window.location.origin + "/admin/addType";

$('#submit_type').on('click', function(e) {
    e.preventDefault();
    let success_selector = $('#success_type_msg');
    let error_selector = $('#error_type_msg');
    let validation_error_selector = $('#validation_type_error_msg');

    $.ajax({
        data: {
            type: $('#type').val()
            , observation: $("input[name=observation]:checked").attr('id')
            , _token: CSRF_TOKEN
        , }
        , url: add_type_url
        , type: "POST"
        , success: function(response) {
            if (response.error != null) {
                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');

                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.addClass('d-block')
                validation_error_selector.removeClass('d-none');
                validation_error_selector.html('<li style="font-size:12px;">' + response.error + '</li>');
            } else {
                success_selector.removeClass('d-none');
                success_selector.addClass('d-block')

                error_selector.removeClass('d-block');
                error_selector.addClass('d-none')

                validation_error_selector.removeClass('d-block');
                validation_error_selector.addClass('d-none');

                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            }

        }
        , error: function(err) {
            validation_error_selector.text('');
            if (err.status === 500) {
                error_selector.removeClass('d-none');
                error_selector.addClass('d-block');

                validation_error_selector.removeClass('d-block')
                validation_error_selector.addClass('d-none');

                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');
            } else if (err.status === 422) {
                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');

                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.addClass('d-block')
                validation_error_selector.removeClass('d-none');

                var error_text = '';
                $.each(err.responseJSON.errors, function(i) {
                    $.each(err.responseJSON.errors[i], function(key, val) {
                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                    });
                });
                validation_error_selector.html(error_text);
            }

        }
    , });

});

function addRootCorrective()
{
    let url = window.location.origin + "/admin/addcorrectiverootcorrective";
    window.location.assign(url);
}
$(document).on('change', '.select-field', function () {
    let row = $(this).closest('tr');
    let isChecked = $(this).is(':checked');

    row.find('.display-label').prop('disabled', !isChecked).attr('required', isChecked);
    row.find('.mandatory-field').prop('disabled', !isChecked);
});

// View Added Field Model
$('body').on('click', '.view-field', function () {
    const id = $(this).data('id');
    $.ajax({
        url : window.location.origin +`/admin/corrective/view-ncr-added-field/${id}`,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            if (data.success) {
                $('#field-model').html(data.view)
                const modalEle = $(`#viewFieldModal`);
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

//------------------Detection Stage Script Start---------------------
$('.closeBtnEditDetectionStage').on('click', function () {
    $('#editDetectionStageName').val('');
    $('error_edit_detection_stage').addClass('d-none');
    $('#EditDetectionStageModal').hide();
});
$('.closeBtnDetectionStage').on('click', function () {
    $('#detectionStageName').val('');
    $('#error_detection_stage').addClass('d-none');
});
$(`#edit_detection_stage_form`).on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    let error_selector = $('#error_msg_edit_detection_stage');
    let validation_error_selector = $('#validation_error_msg_edit_detection_stage');
    let success_selector = $('#success_msg_edit_detection_stage');
    const id = $('#stage_id').val();
    $.ajax({
        type: 'POST', 
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, 
        url: window.location.origin + `/admin/update-detection-stage/${id}`,
        data: formData, 
        processData: false, 
        contentType: false,  
        cache: false,
        success: function(response) {
            if (response.error !== '' && response.error !== undefined) {
                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.removeClass('d-none')
                validation_error_selector.addClass('d-block');

                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');
                validation_error_selector.html(response.error);

            } else {
                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.removeClass('d-block')
                validation_error_selector.addClass('d-none');

                success_selector.removeClass('d-none');
                success_selector.addClass('d-block');

                setTimeout(function() {
                    window.location.reload();
                }, 2000);

            }

        }, 
        error: function(err) {
            if (err.status === 500) {
                error_selector.removeClass('d-none');
                error_selector.addClass('d-block');

                validation_error_selector.removeClass('d-block')
                validation_error_selector.addClass('d-none');

                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');
            } else if (err.status === 422) {
                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');

                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.addClass('d-block')
                validation_error_selector.removeClass('d-none');

                var error_text = '';
                $.each(err.responseJSON.errors, function(i) {
                    $.each(err.responseJSON.errors[i], function(key, val) {
                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                    });
                });
                validation_error_selector.html(error_text);


            }
        }
    });
});
$(`#add_detection_stage_form`).on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    let error_selector = $('#error_msg_detection_stage');
    let validation_error_selector = $('#validation_error_msg_detection_stage');
    let success_selector = $('#success_msg_detection_stage');

    $.ajax({
        type: 'POST', 
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, 
        url: window.location.origin + "/admin/add-detection-stage",
        data: formData, 
        processData: false, 
        contentType: false,  
        cache: false,
        success: function(response) {
            if (response.error !== '' && response.error !== undefined) {
                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.removeClass('d-none')
                validation_error_selector.addClass('d-block');

                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');
                validation_error_selector.html(response.error);

            } else {
                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.removeClass('d-block')
                validation_error_selector.addClass('d-none');

                success_selector.removeClass('d-none');
                success_selector.addClass('d-block');

                setTimeout(function() {
                    window.location.reload();
                }, 2000);

            }

        }, 
        error: function(err) {
            if (err.status === 500) {
                error_selector.removeClass('d-none');
                error_selector.addClass('d-block');

                validation_error_selector.removeClass('d-block')
                validation_error_selector.addClass('d-none');

                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');
            } else if (err.status === 422) {
                success_selector.removeClass('d-block');
                success_selector.addClass('d-none');

                error_selector.removeClass('d-block');
                error_selector.addClass('d-none');

                validation_error_selector.addClass('d-block')
                validation_error_selector.removeClass('d-none');

                var error_text = '';
                $.each(err.responseJSON.errors, function(i) {
                    $.each(err.responseJSON.errors[i], function(key, val) {
                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                    });
                });
                validation_error_selector.html(error_text);
            }
        }
    });
});
$('#detectionStageName').on('input',function(){
    $.ajax({
        url: window.location.origin + "/admin/check-add-detection-stage",
        type: 'GET',
        data: { stage_name: $(this).val() },
        dataType: 'json',
        success: function(response) {
            if (response.exists) {
                $('#error_detection_stage').html(response.msg).removeClass('d-none');
                $('#submitBtnDetectionStage').prop('disabled', true);
            } else {
                $('#error_detection_stage').html('').addClass('d-none');
                $('#submitBtnDetectionStage').prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error checking Detection Stage:", error);
        }
    });
}); 
$('#editDetectionStageName').on('input',function(){
    $.ajax({
        url: window.location.origin + "/admin/check-edit-detection-stage",
        type: 'GET',
        data: { stage_name: $(this).val(),stage_id: $('#stage_id').val()},
        dataType: 'json',
        success: function(response) {
            if (response.exists) {
                $('#error_edit_detection_stage').html(response.msg).removeClass('d-none');
                $('#submitBtnEditDetectionStage').prop('disabled', true);
            } else {
                $('#error_edit_detection_stage').html('').addClass('d-none');
                $('#submitBtnEditDetectionStage').prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error checking Detection Stage:", error);
        }
    });
});
$('.editDetectionStageBtn').click(function(){
    const $detectionStageName = $(this).data('name');
    const $detectionStageId = $(this).data('id');
    $('#editDetectionStageName').val($detectionStageName);
    $('#stage_id').val($detectionStageId);
    $('#EditDetectionStageModal').show();
});

//Editable Detection Stage
$(document).ready(function () {
    const $label = $('.editable-label');
    const $editIcon = $('.edit-icon');

    let originalValue = $label.data('original');
    let hasChanged    = false;

    function enableEdit() {
        $label.attr('contenteditable', 'true').focus();

        const range = document.createRange();
        const sel   = window.getSelection();
        range.selectNodeContents($label[0]);
        range.collapse(false);
        sel.removeAllRanges();
        sel.addRange(range);
    }

    $editIcon.on('click', enableEdit);

    $label.on('click', function () {
        if ($label.attr('contenteditable') !== 'true') enableEdit();
    });

    $label.on('input', function () {
        hasChanged = $label.text().trim() !== originalValue.trim();
    });

    $label.on('blur', function () {
        if (!hasChanged) {
            $label.attr('contenteditable', 'false');
            return;
        }

        const newValue = $label.text().trim();

        setTimeout(function () {
            Swal.fire({
                title            : 'Save Changes?',
                text             : 'Do you want to save the changes you made?',
                icon             : 'question',
                showCancelButton : true,
                confirmButtonText: 'Save',
                cancelButtonText : 'Discard',
                confirmButtonColor: '#4f46e5',
                cancelButtonColor : '#6b7280',
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url        : window.location.origin+'/admin/detection-stage/update-label',
                        method     : 'POST',
                        contentType: 'application/json',
                        headers    : { 'X-CSRF-TOKEN': CSRF_TOKEN },
                        data       : JSON.stringify({ id: $label.data('id'), name: newValue }),
                        success    : function (data) {
                            if (data.success) {
                                originalValue = newValue;
                                $label.data('original', newValue);
                                hasChanged = false;
                                Swal.fire('Saved!', 'Label updated successfully.', 'success');
                                setTimeout(function(){
                                    window.location.reload()
                                },1000)
                            } else {
                                Swal.fire('Error!', 'Failed to update label.', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                } else {
                    $label.text(originalValue);
                    hasChanged = false;
                }

                $label.attr('contenteditable', 'false');
            });
        }, 1000);
    });
});
function showNoStageMsg(id) {
    $('.no-stage-msg').hide();

    var $msg = $('#noStageMsg' + id);
    $msg.show();

    setTimeout(function () {
        $msg.fadeOut();
    }, 3000);
}