@extends('admin.datatable')

@section('content')
<!-- Main Container -->
<main id="main-container">
    <!-- Hero -->

    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:92%;margin-left:50px;margin-top:-75px">
        <!-- Dynamic Table Full -->

        @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif
        <div class="block block-rounded">
            <div class="block-header justify-content-center">
                <div class="form-group mr-5">
                    <select class="form-control" id="department" name="department" style="width: 100%;font-size: 13px;" data-placeholder="Choose Department" onchange="filterDepartment($(this).val(), $('#division_select').val())">
                        <option value="0" selected>Choose Department</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}"> {{ $department->departmentname }}</option><br>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                     <select class="form-control" id="division_select" name="division" style="width: 100%;font-size: 13px;" data-placeholder="Choose Division" onchange="filterDepartment($('#department').val(), $(this).val())">
                        <option value="0" selected>Choose Division</option>
                    </select>
                </div>

            </div>
            <div class="block-content block-content-full" style="width:80%;margin-left:90px">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-left" style="width:12%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Sub Division</th>
                            <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Sub Division Owner</th>
                            <th class="text-left" style="width:10px;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>


</main>
<!-- END Main Container -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let users = "";
    function ajaxAllUsers() {
       let url_ajax_all_users = '{{ route("ajaxAllUsers") }}';
       $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'post'
            , url: url_ajax_all_users
            , async: false
            , success: function(resp) {
                users = resp.data;
            },
            error: function(result) {

            }
        });
    }
    ajaxAllUsers();
    function loadDataOfTable(url) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'post'
            , url: url
            , success: function(resp) {
                areas = resp.data;
                let number = 1;
                let id = 0;
                if (areas.length) {
                    for (var i = 0; i < areas.length; i++) {
                        id = areas[i].id;
                        let url_area_edit = '{{ route("admin.areas.edit", ":id") }}';
                        url_area_edit = url_area_edit.replace(':id', id);
                        let url_area_delete = '{{ route("admin.areas.delete", ":id") }}';
                        url_area_delete = url_area_delete.replace(':id', id);
                        let users_text = "";
                        for( var j = 0; j < users.length; j++) {
                            users_text +=`<option value="${users[j].id}" ${areas[i].owner_id==users[j].id ? 'selected' : '' }>${users[j].name}</option>`;
                        }
                        tbody_content += `<tr>
                            <td style="font-size:10px;" class="text-left">
                                <span style="width:5px"> ${number} </span>
                            </td>
                            <td style="font-size:10px;width:10px" class="text-left">
                                <span>${areas[i].name}</span>
                            </td>
                            <td style="font-size:10px;width:10px" class="text-left">
                                <span>${areas[i].owner ? areas[i].owner.name : '-'}</span>
                            </td>
                            <td style="font-size:10px;width:10px" class="text-left">
                                <div class="btn-group">

                                    <button type="button" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Edit" onClick="editBtn('${id}')"><strong><i class="fa fa-fw fa-pencil-alt"></i></strong></button>
                                    <div id="viewModal${id}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Edit Area</h2>
                                                </div>
                                                <form method="post" action="${url_area_edit}" id="form_${id}">
                                                    @csrf
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <input type="text" class="form-control" style="font-size: 13px;" value="${areas[i].name}" name="name">
                                                            </div>
                                                        </div>

                                                       <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <select class="form-control" id="owner_id" name="owner_id" style="font-size:13px" required>
                                                                    <option value="0">Please select area owner</option>` +
                                                                      users_text
                                                                + `</select>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" onClick="$('#form_${id}').submit()">Save</button>
                                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a type="button" href="${url_area_delete}" class="btn btn-sm btn-alt-primary" style="font-size:10px" data-toggle="tooltip" title="Delete"><strong><i class="fa fa-fw fa-times"></i></strong></a>
                            </td>
                        </tr>`;

                        number++;
                    }

                } else {
                    // tbody_content += `<tr class="odd">
                    //     <td valign="top" colspan="4" class="dataTables_empty">No records found</td>
                    // </tr>`;
                    tbody_content = "";
                }


                $('tbody').empty().append(tbody_content);
            }
            , error: function(err) {
                console.log(err);
            }
        });

    }

</script>
<script>
    let the_url = '{{ route("admin.ajaxDepartmentAreas", ":id") }}';
    the_url = the_url.replace(':id', $('#department').val() + ',' + $('#division_select').val());
    let areas = null;
    let tbody_content = "";
    loadDataOfTable(the_url);
    let department_divisions = "";


    function filterDepartment(id, division_id) {
         let get_department_divisions_url = '{{ route("admin.departmentDivisions.getDivisions", ":id") }}';
         get_department_divisions_url = get_department_divisions_url.replace(':id', id);
         $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: get_department_divisions_url
            , success: function(resp) {
                department_divisions = resp.data;
                $('#division_select').empty().append('<option value="0">Choose Division</option>');
                $.each(department_divisions, function (i, item) {
                    $('#division_select').append(`<option value="${item.id}" ${item.id == division_id ? 'selected' : ''}>${item.name}</option>`);
                });
            },
            error: function(result) {

            }
        });
        areas = null;
        tbody_content = "";
        let filter_url = '{{ route("admin.ajaxDepartmentAreas", ":id") }}';
        filter_url = filter_url.replace(':id', id + ',' + $('#division_select').val());
        loadDataOfTable(filter_url)
    }
</script>
</body>
</html>
@endsection
@section('js_after')
<script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script>
    $('table').dataTable({
        searching: false
        , paging: false
        , info: false
    });

</script>
<script>
    function editBtn(id) {
        $('#viewModal' + id).modal('show');
    }
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    $('[data-toggle="tooltip"]').tooltip()
</script>
@endsection
