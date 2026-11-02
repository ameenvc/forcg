@extends('admin.datatable')
@section('content')
<style>
    .container {
        padding-left: 20px;
        padding-top: 10px;
        padding-right: 20px;
        padding-bottom: 0;
        background-color: #f2f2f2;
        border-radius: 5px;
    }

    h4,h5{
        font-size: 12px;
        margin-bottom: 5px;
    }

    ul {
        margin-top: 0;
        padding-left: 20px;
    }

    li {
        list-style-type: disc;
        margin-bottom: 5px;
        font-size: 12px;
    }
    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {opacity: 0.7;}

    /* The Modal (background) */
    .modal1 {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
    }

    /* Modal Content (image) */
    .modal1-content {
        margin: auto;
        display: block;
        width: 1200px;
        max-width: 1900px;
        height:auto;
    }

    /* Caption of Modal Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation */
    .modal1-content, #caption {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)}
    to {-webkit-transform:scale(1)}
    }

    @keyframes zoom {
    from {transform:scale(0)}
    to {transform:scale(1)}
    }

    /* The Close Button */
    #myModal > .close {
        position: relative;
        top: 2px;
        right: 125px;
        /*margin-right:100px;*/
        color: white;
        font-size: 20px;
        font-weight: normal;
        transition: 0.3s;
    }

    #myModal > .close:hover,
    #myModal > .close:focus {
        color: white;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
        .modal1-content {
            width: 100%;
        }
    }
    .toggle-group {
        display: inline-flex;
        border: 1px solid #e8ecee;
        border-radius: 8px;
        overflow: hidden;
            white-space: nowrap; /* 🔥 prevents line break */

    }

    .toggle-group input[type="radio"] {
        display: none;
    }

    .toggle-group label {
        padding: 8px 16px;
        cursor: pointer;
        font-size: 13px;
        background: #f8f9fa;
        color: #495057;
        transition: 0.2s;
        border-right: 1px solid #e8ecee;
        margin-bottom: 0%;
    }

    .toggle-group label:last-child {
        border-right: none;
    }

    .toggle-group input[type="radio"]:checked + label {
        background: #5179d6;
        color: #fff;
        font-weight: 500;
    }
</style>

<div class="content" style="width:92%;">
    @if(count($errors) >0 )
    @foreach($errors->all() as $error)
    <div class='alert alert-warning'>
        {{ $error }}
    </div>
    @endforeach
    @endif
    @if(Session::has('msg'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('msg')!!}
    </div>
    @endif
    @if(Session::has('err'))
    <div class='alert alert-warning'>
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('err')!!}
    </div>
    @endif
    <div class="block block-rounded">
        <div class="block-content block-content-full" style="width:85%;">
            <h3 class="block-title">{{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}} Table</h3>
            <div class="table-responsive">
                <table class="table table-vcenter table-bordered" style="border:1px solid #e8ecee;">
                    <thead>
                        <tr>
                            <th class="text-left" style="width: 5%;font-size:13px;text-transform:capitalize;z-index:10;top:0;position:sticky;background:#ededed;">#</th>
                            <th class="d-sm-table-cell text-left" style="width: 80%;font-size:13px;text-transform:capitalize;z-index:10;top:0;position:sticky;background:#ededed;">Title</th>
                            <th class="text-left" style="width: 15%;font-size:13px;text-transform:capitalize;z-index:10;top:0;position:sticky;background:#ededed;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(count($sourceOfIssues)>0)
                        <?php $num = 1; ?>
                        @foreach($sourceOfIssues as $sourceOfIssue)
                            <tr>
                                <td style="font-size:13px;" class="text-left">
                                    <span>{{ $num++ }}</span>
                                </td>
                                <td style="font-size:13px;" class="text-left">
                                    {{ $sourceOfIssue->title }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-alt-primary" style="font-size:13px" data-toggle="tooltip" onclick="editSourceOfIssue('{{$sourceOfIssue->id}}','{{$sourceOfIssue->title}}')" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                        <a class="btn btn-sm btn-alt-primary" onclick="deleteSourceOfIssue('{{$sourceOfIssue->id}}')" style="font-size:13px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @else
                        <tr>
                            <td class="text-danger text-center" style="font-size:13px;" colspan="3">No Records Found</td>
                        </tr>
                        @endif
                    </tbody>

                </table>
            </div>
            <form method="post" action="{{route('admin.complaint.source-of-issue.store')}}" onsubmit="return validation()">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-sm-7"></div>
                    <div class="form-group col-sm-5 text-right">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" style="font-size: 13px;" id="sourceOfIssue" placeholder="Enter {{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}} Title" required name="title">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary btn-sm">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- EDIT UNIT MODAL START -->
<div id="EditSourceOfIssueModal" class="modal fadebd-example-modal-lg" aria-labelledby="EditSourceOfIssueModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title text-white">Edit {{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}}</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="alert alert-success" id="successBtn2" style="display:none;font-size:12px;">{{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}} Title updated successfully!</div>
            <ul class="alert alert-danger" id="dangerBtn2" style="display:none;font-size:12px;"></ul>
            <form onsubmit="updateSourceOfIssue(); return false;">
                <input type="hidden" class="form-control" style="font-size: 13px;" id="edit_soi_id" required name="id" value="">
                <div class="modal-body m-body p-0">
                    <div class="row">
                        <div class="form-group col-sm-12 mb-0">
                            <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">{{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}} Title <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" id="edit_soi_title" placeholder="Enter {{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}} Title" required name="edit_soi_title">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" style="font-size:12px" >Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- EDIT UNIT MODAL END -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    function deleteSourceOfIssue(id){
        Swal.fire({
            text: "Do you want to delete the {{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}}?",
            icon: 'question',
            showDenyButton: true,
            showCancelButton: true,
            denyButtonText: `Delete`,
            showConfirmButton: false
        }).then((result) => {
            if (result.isDenied) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('admin.complaint.source-of-issue.destroy', ':id') }}".replace(':id', id),
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.error != null) {
                            Swal.fire({
                                icon : 'error',
                                text: "{{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}} has not been deleted.",
                            });
                            console.error('Error deleting record: ' + error.statusText);
                        } else {
                            Swal.fire({
                                icon : 'success',
                                text: "{{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}} has been deleted successfully.",
                            });
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        }
                    },
                    error: function(err) {
                        log(err)
                        showError();
                        if (err.status === 500) {
                            Swal.fire({
                                icon : 'error',
                                text: "Some server errors happened, we are trying to fix them soon.",
                            });
                            console.error('Error deleting record: ' + error.statusText);
                        } else if (err.status === 422) {
                            Swal.fire({
                                icon : 'error',
                                text: "{{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}} has not been deleted.",
                            });
                            console.error('Error deleting record: ' + error.statusText);
                        }
                    }
                });
            } else{
                Swal.close();
            }
        });
    }
    function editSourceOfIssue(id, title){
        $('#EditSourceOfIssueModal').modal('show');
        $('#edit_soi_id').val(id);
        $('#edit_soi_title').val(title);
    }
    function updateSourceOfIssue() {
        var error_selector = $('#dangerBtn2');
        var success_selector = $('#successBtn2');

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
        var formData = new FormData();
        var id = $('#edit_soi_id').val();
        console.log($('#edit_soi_title').val());

        formData.append('title', $('#edit_soi_title').val());
        formData.append('_method', 'PUT');

        $.ajax({
            url: "{{ route('admin.complaint.source-of-issue.update', ':id') }}".replace(':id', id),
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
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
                        window.location.reload();
                    }, 2000);
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
</script>
@endsection
