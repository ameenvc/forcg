@extends('admin.datatable')
@section('content')

    <!-- Page Content -->
    <div class="content">

        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">MS.15.02 Waste Description</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            Waste Management
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:84%;">
                <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Waste Type</th>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Description</th>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($waste as $w)

                        {{csrf_field()}}

                        <?php $id = $w->id ?>
                        <tr>
                            <td style="font-size:10px" class="text-center">
                                <span>{{ $number }}</span>
                            </td>
                            <td style="font-size:10px" class="text-center">
                                <span>{{$w->waste_type}}</span>
                            </td>
                            <td style="font-size:10px" class="text-center">
                                <span>{{$w->wastedesc}}</span>
                            </td>
                            <td style="font-size:10px" class="text-center">
                                <?php $number++; ?>
                                <div class="btn-group">
                                    <a href="{{url('/admin/editwaste/'.$id)}}" class="btn btn-sm btn-alt-primary" style="font-size:10px;" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                <!-- END Dynamic Table Full -->
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="content" style="width:106%;margin-left:-30px;margin-top:-10px">

        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">MS.14.03 Waste approver criteria</h3>
                <ol class="breadcrumb breadcrumb-alt" style="margin-left:53%;">
                    <li class="breadcrumb-item">Waste Management</li>
            </div>
            <div class="block-content block-content-full" style="width:80%;margin-left:90px">
                <div class="col-md-6 col-lg-12">
                    <table width="100%" style="margin-bottom:20px;">
                        <tr>
                            <td align="right">
                                <button type="button" class="btn btn-primary test" data-toggle="modal" style="font-size:12px" data-target="#exampleModal">
                                    Add
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">items</th>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">qty</th>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">approver</th>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($wastetargets as $w)

                        {{csrf_field()}}

                        <?php $id = $w->id ?>
                        <tr>
                            <td style="font-size:10px" class="text-center">
                                <span>{{ $number }}</span>
                            </td>
                            <td style="font-size:10px" class="text-center">
                                <span>{{$w->items}}</span>
                            </td>
                            <td style="font-size:10px" class="text-center">
                                <span>{{$w->qty}}</span>
                            </td>
                            <td style="font-size:10px" class="text-center">
                                <span>{{$w->approver ? $w->approver->name : '-'}}</span>
                            </td>
                            <td style="font-size:10px" class="text-center">
                                <?php $number++; ?>
                                <div class="btn-group">
                                    <a href="{{route('admin.waste.editwastetarget', ['id' => $id])}}" class="btn btn-sm btn-alt-primary" style="font-size:10px;" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                    <a href="{{ route('admin.waste.removewasteTarget', ['id' => $id])}}" class="btn btn-sm btn-alt-primary" onclick="return confirm('Are you sure?')" style="font-size:13px" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                <!-- END Dynamic Table Full -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add waste approval Criteria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" id="successBtn" style="display:none">
                        successfully saved
                    </div>
                    <ul class="alert alert-danger" id="dangerValidation" style="display:none"></ul>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="items" name="items" id="items">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="qty" name="qty" id="qty">
                    </div>
                    <div class="form-group">
                        <select id="approvers_id" name="approvers_id" class="form-control" style="font-size: 13px;" required>
                            <option value="0" selected="selected"> Select a user...</option>
                            @foreach($users as $u)
                            <option value="{{$u->id}}">{{$u->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary" style="font-size:12px" onclick="saveChanges()">Save changes</button>
                </div>

            </div>
        </div>
    </div> --}}
<!-- END Main Container -->
{{-- <script>
    let url = "{{ route('admin.waste.ajaxwastetarget')}}";
    let CSRF_TOKEN = "";
    window.onload = function() {
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    }
    let sw = 0;

    function ddlwaste() {

        mess1 = "Are you sure?";
        x = confirm(mess1);
        if (x == true) {
            mess2 = 'The data will be deleted permanently,Press OK to continue.';
            y = confirm(mess2);
            if (y == true) {

            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function showErrors(items_errors) {
        $.each(items_errors, (index, item) => {
            $('#dangerValidation').css('display', 'block');
            $('#dangerValidation').append('<li>' + item + '</li>')
        });
    }

    function saveChanges() {
        $.ajax({
            data: {
                "items": $('#items').val()
                , "qty": $('#qty').val()
                , "approvers_id": $('#approvers_id').val()
                , _token: CSRF_TOKEN
            }
            , url: url
            , type: "POST"
            , success: function(result) {
                $('#dangerValidation').css('display', 'none');
                $('#successBtn').css('display', 'block');
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }
            , error: function(result) {
                $('#dangerValidation').empty();
                $('#successBtn').css('display', 'none');
                let items_errors = JSON.parse(result.responseText).errors.items;
                showErrors(items_errors);
                let qty_errors = JSON.parse(result.responseText).errors.qty;
                showErrors(qty_errors);
                let approvers_id_errors = JSON.parse(result.responseText).errors.approvers_id;
                showErrors(approvers_id_errors);
            }
        });
    }

</script> --}}



@endsection
