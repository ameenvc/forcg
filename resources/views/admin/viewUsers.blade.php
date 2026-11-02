@extends('admin.datatable')
@section('css_after')
<style>
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        display: inline-block;
    }
</style>
@endsection
@section('content')
    <!-- Page Content -->
    <div class="content">
        @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert" style="cursor: pointer;">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Users Table</h3>
            </div>
            <div class="block-content block-content-full" style="width:90%;">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="example">
                    <thead>
                        <tr>
                            <th style="position:sticky;top:-1px;width:2%;"></th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Profile Picture</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">ID</th>
                            <th class="d-sm-table-cell text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Name</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Email</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Dept</th>
                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($users as $u)

                        {{csrf_field()}}

                        <?php $dept=$u->deptid; $email=$u->email;$empid=$u->employeeid;$name=$u->name?>
                        <tr>
                            <td><input class="text-center font-size-sm chckbox" type="checkbox" id="{{ $u->id }}" data-id="{{ $u->id }}" name="id[]"></td>
                            <td style="font-size:10px" class="text-left">
                                <span>{{ $number }}</span>
                            </td>
                            <td style="font-size:10px" class="text-left">
                                <span><img src="{{ ($u->profile_picture && File::exists(public_path('/storage/profile/'.$u->profile_picture))) ? asset("storage/profile/".$u->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar"></span>
                            </td>
                            @if($u->employeeid==0)
                            <td style="font-size:10px" class="text-left">
                                <span>Not Set</span>
                            </td>
                            @else
                            <td style="font-size:10px" class="text-left">
                                <span>{{$u->employeeid}}</span>
                            </td>
                            @endif
                            <?php $number++; ?>
                            <td style="font-size:10px" class="text-left">
                                <span>{{$u->name}}</span>
                            </td>
                            <td style="font-size:10px" class="text-left">
                                <span>{{$u->email}}</span>
                            </td>
                            @if($u->deptid==NULL)
                            <td style="font-size:10px" class="text-left">
                                <span>Not Set</span>
                            </td>
                            @else
                            <td style="font-size:10px" class="text-left">
                                <span>{{$u->deptid}}</span>
                            </td>
                            @endif
                            <td class="text-left">
                                <div class="btn-group">


                                    {{-- <a href="{{url('/admin/AddDetails?val='.$id.'&value='.$empid.'&name='.$name.'&dept='.$dept.'&email='.$email)}} " style="font-size:10px;" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Add">
                                    <i class="fa fa-fw fa-plus"></i>
                                    </a> --}}
                                    <a href="{{url('/admin/displayeditUsers?val='.$u->id.'&value='.$email)}}" class="btn btn-sm btn-alt-primary" style="font-size:10px;" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>
                                    {{-- <a href="{{url('/admin/CarryUserID?val='.$id)}}" onclick="return ddluser()" class="btn btn-sm btn-alt-primary" style="font-size:10px;" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a> --}}
                                     {{-- <!-- <a href="#" onclick="return ddluser({{$u->id}})" class="btn btn-sm btn-alt-primary" style="font-size:10px;" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>  --> --}}

                                    <a href="{{ route('admin.showResponsibilities',['id'=>$u->id]) }}"  class="btn btn-sm btn-alt-primary" style="font-size:10px;" data-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>

                                    {{-- <!-- <a href="{{url('/admin/manageRoles?val='.$u->id)}}" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" style="font-size:10px;" title="Manage"> <i class="fa fa-fw fa-user"></i></a> --> --}}
                                    <a href="{{url('/admin/manageRoles?val='.$u->id.'&value='.$u->email)}}" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" style="font-size:10px;" title="Manage"> <i class="fa fa-fw fa-user"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->
    </div>
    <!-- END Page Content -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>

    function ddluser(id) {
        let url = '{{ route("admin.showResponsibilities", ":id") }}';
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Are you sure?'
            , text: "You won't be able to revert this!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#3085d6'
            , cancelButtonColor: '#d33'
            , confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.replace(url);
            }
        })
    }
</script>   
@endsection
@section('js_new')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    let selected_ids = [];
    let enable_disable_module_url = '{{ route("admin.enable_disable_module", ":ids") }}';
    let ids = [];

    $('.chckbox').on('change', function() {
        let counter = 0;

        selected_ids = $('input[type="checkbox"]').map(function() {
            let if_checked = $(this).is(':checked');
            selected_ids[counter] = if_checked ? $(this).data('id') : null;
            counter++;

            return selected_ids[counter - 1];
        }).get();

        if (selected_ids.length) {
            $('#module').prop('disabled', false);
        } else {
            $('#module').prop('disabled', 'disabled');
        }

        ids = selected_ids.join(',');
    });


    $("#example").one("preInit.dt", function() {
        let table = $('#example').DataTable();
        $button = $(`<button type="button" onclick="changeHref()" class="btn btn-primary text-white" style="font-size: 13px;" id="module" disabled>Enable/Disable Modules</button>`);

        $("#example_filter label").prepend($button);

        $button.button();
    });

    function changeHref() {
       console.log(ids);
       enable_disable_module_url = enable_disable_module_url.replace(':ids', ids);
       window.location.replace(enable_disable_module_url);
    }

</script>

@endsection
