<div id="EditRegulatoryModal" class="modal fadebd-example-modal-md" aria-labelledby="EditRegulatoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title text-white">Edit Authority</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="alert alert-success" id="successBtnEdit" style="display:none;font-size:12px;">Authority Name Updated successfully!</div>
            <ul class="alert alert-danger" id="dangerBtnEdit" style="display:none;font-size:12px;"></ul>
            <form id="edit_form" method="post">
                @csrf
                <div class="modal-body m-body p-2">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Authority Name <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input type="hidden" name="RAC_id" id="RAC_id" value="{{ $authority->id }}">
                                <input type="text" class="form-control" style="font-size: 13px;" id="authname_edit" placeholder="Enter Authority Name" required name="authname_edit" value="{{ $authority->name }}">
                            </div>
                            <div id="duplicate-error-edit" class="text-danger mt-2 d-none" style="font-size:13px;padding-left:15px;">
                                Authority Name already exists. Please choose a different name.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="submit" class="btn btn-primary" style="font-size:12px">Update</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
<script>
     $('#edit_form').on('submit', function(e) {
        e.preventDefault();
        var error_selector = $('#dangerBtnEdit');
        var success_selector = $('#successBtnEdit');
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
        if(!$('#duplicate-error-edit').hasClass('d-none')){
           Swal.fire({
                icon: 'error',
                title: 'Duplicate Regulatory Authority Name',
                text: 'Please ensure Regulatory Authority name is unique.',
                confirmButtonText: 'OK'
            });
            return false;
        }
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
                    url: "{{ url('/admin/compliance/editregulatory') }}",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        var $btn = $('#edit_form').find('button[type="submit"]');
                        if (!$btn.data('orig-html')) $btn.data('orig-html', $btn.html());
                        $btn.prop('disabled', true).addClass('disabled');
                        $btn.html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Updating...');
                    },
                    complete: function() {
                        var $btn = $('#edit_form').find('button[type="submit"]');
                        $btn.prop('disabled', false).removeClass('disabled');
                        $btn.html($btn.data('orig-html') || 'Update');
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
</script>