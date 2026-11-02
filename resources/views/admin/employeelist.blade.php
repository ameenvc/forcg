@extends('admin.datatable')
@section('content')
    <!-- Page Content -->
    <div class="content">

        @if(Session::has('message'))
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Employees Table</h3>
            </div>
            <div class="block-content block-content-full" style="width:90%;">
                <form method="post" action="{{url('/admin/multipleempaction')}}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6 col-lg-12">
                            <table width="100%" style="margin-bottom:20px;">
                                <tr>
                                    <td align="right">
                                        <input class="btn btn-primary btn-sm" type="submit" onclick="return chkedit()" name="submit" value="Edit"/>
                                        {{--<input class="btn btn-warning btn-sm" type="submit" onclick="return ddlall()" name="submit" value="Delete"/>--}}
                                        <input class="btn btn-danger btn-sm" type="submit" onclick="return forcedelete()" name="submit" value="Force Delete" data-toggle="tooltip" data-placement="top" title="Delete from database"/>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="table-responsive">
                                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                    <thead>
                                        <tr>
                                            <th class="text-left" style="width:1%;font-size:10px;text-transform:capitalize">
                                                <input type="checkbox" id="checkAll">
                                            </th>
                                            <th class="text-left" style="width:2%;font-size:10px;text-transform:capitalize">
                                                SL No.</th>
                                            <th class="d-sm-table-cell text-left" style="width:2%;font-size:10px;text-transform:capitalize">Employee ID</th>
                                            <th class="d-sm-table-cell text-center" style="width:10%;font-size:10px;text-transform:capitalize">Name</th>
                                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Position</th>
                                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Department</th>
                                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">Joining date</th>
                                            <th class="text-left" style="width: 5%;font-size:10px;text-transform:capitalize">DOB</th>
                                            <!--<th class="text-left"style="width: 5%;font-size:10px;text-transform:capitalize" >Actions</th>-->


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $number = 1; ?>
                                        @foreach($empdetails as $emp)

                                        <?php $id=$emp->emp_number;?>
                                        <tr>
                                            <td><input name='id[]' type="checkbox" id="checkItem" value="<?php echo $emp->emp_number; ?>"></td>
                                            <td style="font-size:10px" class="text-left">
                                                <span>{{ $number }}</span>
                                            </td>

                                            <?php $number++; ?>
                                            <td style="font-size:10px" class="text-left">
                                                <span>{{$emp->emp_number}}</span>
                                            </td>
                                            <td style="font-size:10px" class="text-center">
                                                <span>{{$emp->emp_name}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-center">
                                                <span>{{$emp->emp_position}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-center">
                                                <span>
                                                    @if(is_numeric($emp->emp_department))
                                                    @foreach($departments as $dep)
                                                    @if($emp->emp_department==$dep->id)
                                                    {{$dep->departmentname}}
                                                    @break
                                                    @endif
                                                    @endforeach
                                                    @else
                                                    {{$emp->emp_department}}
                                                    @endif
                                                </span>
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                                <span>{{$emp->joining_date}}</span>
                                            </td>

                                            <td style="font-size:10px" class="text-left">
                                                <span>{{$emp->emp_dob}}</span>
                                            </td>

                                                <!--<td class="text-left">
                                                    <div class="btn-group">

                                                            <a href="{{url('/admin/displayeditEmp/'.$id.'/edit')}}" class="btn btn-sm btn-alt-primary" style="font-size:10px;"  data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i>
                                                                            </a>
                                                            <a href="{{url('/admin/deleteemployee?val='.$id)}}"  onclick="return ddluser()" class="btn btn-sm btn-alt-primary" data-tr="tr_{{$emp->emp_number}}" style="font-size:10px;" data-toggle="tooltip" title="Delete" ><i class="fa fa-fw fa-times"></i></a>

                                                </td>-->
                                            </td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Dynamic Table Full -->


    </div>
    <!-- END Page Content -->
<!-- END Main Container -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function ddluser() {

        mess1 = "Are you sure?";
        x = confirm(mess1);
        if (x == true) {
            mess2 = 'Employee will be deleted permanently,Press OK to continue.';
            y = confirm(mess2);
            if (y == true) {

            } else {
                return false;
            }
        } else {
            return false;
        }
    }

</script>

<script language="javascript">
    $("#checkAll").click(function() {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

</script>


<script>
    function chkedit() {
        if ($('input:checkbox:checked').length > 0) {} else {
            alert('Please select Employee to edit');
            return false;
        }
    }

    function forcedelete() {
        if ($('input:checkbox:checked').length > 0) {
            mess1 = "Are you sure?";
            x = confirm(mess1);
            if (x == true) {
                mess2 = 'Employees will be deleted permanently,Press OK to continue.';
                y = confirm(mess2);
                if (y == true) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            alert('Please select Employee to delete');
            return false;
        }
    }

</script>



</body>

</html>
@endsection
