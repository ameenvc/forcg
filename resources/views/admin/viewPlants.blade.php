@extends('admin.datatable')
@section('content')


    <div class="content" style="width:92%;">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Plants Table</h3>
            </div>
            <div class="block-content block-content-full" style="width:80%;">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <button class="btn btn-primary text-right ml-auto d-flex mb-1" style="font-size:10px" data-toggle="modal" data-target="#AddPlantModal">Add</button>
                <div id="AddPlantModal" class="modal fadebd-example-modal-lg" aria-labelledby="AddPlantModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="AddPlantModalLabel">Add Plant</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="alert alert-success" id="successBtn" style="display:none;font-size:12px;">Plant Name added successfully!</div>
                            <ul class="alert alert-danger" id="dangerBtn" style="display:none;font-size:12px;"></ul>
                            <form id="form_id_add" method="post">
                                @csrf
                                <div class="modal-body m-body p-4">
                                    <div class="form-group">
                                        <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Plant Name <span style="color:red">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" style="font-size: 13px;" id="plantname" placeholder="Enter Plant Name" required name="plantname">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                    <button type="button" class="btn btn-primary" style="font-size:12px" onclick="savePlant()">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-left" style="width: 1%;font-size:12px;text-transform:capitalize">SL No.</th>
                                <th class="d-sm-table-cell text-left" style="width: 5%;font-size:12px;text-transform:capitalize">Plant Name</th>
                                <th class="text-left" style="width: 5%;font-size:12px;text-transform:capitalize">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 1; ?>
                            
                            @foreach($plants as $p)

                            {{csrf_field()}}

                            <?php $id = $p->id ; $name=$p->plant_name ?>
                            <tr>
                                <td style="font-size:12px" class="text-left">
                                    <span>{{ $number }}</span>
                                </td>
                                <?php $number++; ?>
                                <td style="font-size:12px" class="text-left">
                                    {{ $name }}
                                </td>
                                <td class="text-left">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-alt-primary" style="font-size:13px" data-toggle="tooltip" onclick="editPlant('{{$id}}','{{$name}}')" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                        <a class="btn btn-sm btn-alt-primary" onclick="deletePlant('{{$id}}')" style="font-size:13px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                           

                        </tbody>

                    </table>
            </div>
        </div>
    </div>
<!-- EDIT MODAL START -->
<div id="EditPlantModal" class="modal fadebd-example-modal-lg" aria-labelledby="EditPlantModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditPlantModalLabel">Edit Plant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success" id="successBtn1" style="display:none;font-size:12px;">Plant Name updated successfully!</div>
            <ul class="alert alert-danger" id="dangerBtn1" style="display:none;font-size:12px;"></ul>
            <form id="form_id_edit" method="post">
                @csrf
                <input type="hidden" class="form-control" style="font-size: 13px;" id="id" required name="id" value="">
                <div class="modal-body m-body p-4">
                    <div class="form-group">
                        <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Plant Name <span style="color:red">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" style="font-size: 13px;" id="plant_name" placeholder="Enter Plant Name" required name="plant_name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary" style="font-size:12px" onclick="updatePlant()">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- EDIT MODAL END -->


@endsection
<script>

    function editPlant(id,plantname){
        $('#EditPlantModal').modal('show');
        $('#id').val(id);
        $('#plant_name').val(plantname);
    }
    function savePlant() {
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
        var formData = new FormData();
        formData.append('plant_name', $('#plantname').val());
        $.ajax({
            url: "{{ url('/admin/addPlant') }}",
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
                        $('input').val("");
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

    function updatePlant() {
        var error_selector = $('#dangerBtn1');
        var success_selector = $('#successBtn1');
    
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
        formData.append('id',$('#id').val());
        formData.append('plant_name', $('#plant_name').val());
        $.ajax({
            url: "{{ url('/admin/updatePlant') }}",
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
    function deletePlant(id){
        if(confirm('Are you sure?')){
            $.ajax({
                type: 'POST',
                url: "{{ url('/admin/deletePlant') }}",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                }, 
                success: function(response) {
                    if (response.error != null) {
                        alert('Plant has not been deleted.')
                        console.error('Error deleting record: ' + error.statusText);
                    } else {
                        alert('Plant has been deleted successfully.')
                        window.location.reload();
                    
                    }
                },
                error: function(err) {
                    showError();

                    if (err.status === 500) {
                        alert('Some server errors happened, we are trying to fix them soon.')
                        console.error('Error deleting record: ' + error.statusText);
                    } else if (err.status === 422) {
                        alert('Plant has not been deleted.')
                        console.error('Error deleting record: ' + error.statusText);
                    }
                }
            });
        }
        
    }
</script>