@extends('admin.datatable')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit Employee Details</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">

                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/employeelist">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:90%;">
                <div class="row">
                    <div class="col-md-10">
                        <!-- Form Horizontal - Default Style -->
                        <form method="post" action="{{url('/admin/multipleempupdate')}}" enctype="multipart/form-data">
		                  {{ csrf_field() }}
                           
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            <div class="table-responsive">
                                @foreach($empdetails as $emp)
                                <table class="table table-bordered table-striped" style="margin-bottom:70px;">
                                
                                    <tr>
                                        <td class="col-sm-4">
                                            <label class="col-form-label" style="font-size: 13px;" for="type">Name</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <input type="text" name="name[]" value="{{$emp->emp_name}}" style="font-size: 13px;" required class="form-control"> 
                                        </td>
                                    </tr>
                                        
                                    <tr>
                                        <td class="col-sm-4">
                                            <label class=" col-form-label" style="font-size: 13px;" for="type">Position</label>
                                        </td>
                                        <td class="col-sm-8">
                                        <input type="text" class="form-control" style="font-size: 13px;"   name="position[]" value="{{$emp->emp_position}}" required>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="col-sm-4">
                                            <label class=" col-form-label" style="font-size: 13px;" for="type">Department</label>
                                        </td>
                                        <td class="col-sm-8">
                                        <!--<input type="text" class="form-control" style="font-size: 13px;"   name="dept[]" value="{{$emp->emp_department}}">-->
                                        <select class="form-control" name ="dept[]" style="font-size: 13px;" required="true">    
                                                <option value=""style="font-size: 13px;">choose Department</option>
                                                    @if(count($dept)> 0 )
                                                        @foreach($dept as $value)
                                                            <option value=" {{$value->id}}"<?php if($emp->emp_department == $value->id){ ?> selected="selected"<?php } ?>> {{ $value->departmentname}}</option></br>
                                                        @endforeach
                                                    @endif
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="col-sm-4">
                                            <label class="col-form-label" style="font-size: 13px;" for="type">Joining Date</label>
                                        </td>
                                        <td class="col-sm-8">
                                        <input type="text" class="form-control" style="font-size: 13px;" required  name="joindt[]" value="{{$emp->joining_date == '-' ? '-' : date('d-m-Y',strtotime($emp->joining_date))}}">
                                        <span style="font-size:11px;color:red;">Date should be in d-m-Y Format. For example : 14-02-2021</span>
                                        </td>
                                    </tr> 

                                    <tr>
                                        <td class="col-sm-4">
                                            <label class=" col-form-label" style="font-size: 13px;" for="type">DOB</label>
                                        </td>
                                        <td class="col-sm-8">
                                            <input type="text" class="form-control" style="font-size: 13px;" required  name="dob[]" value="{{$emp->emp_dob == '-' ? '-' : date('d-m-Y',strtotime($emp->emp_dob))}}">
                                            <span style="font-size:11px;color:red;">Date should be in d-m-Y Format. For example : 14-02-2021</span>
                                        </td>
                                    </tr>
                                    
                                    <input type="hidden" name='id[]' value="<?php echo $emp->emp_number; ?>">

                                </table>
                                @endforeach
                                <table style="margin-bottom:20px;">
                                    <tr class="text-center">
                                        <td><input type="submit" name="submit" class="btn btn-primary text-center" style="font-size:12px;" value="Update"></td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->
    </div>
<script>
    function ddlselect() {
        var d = document.getElementById("dept");
        var displaytext = d.options[d.selectedIndex].text;
        document.getElementById("dept").value = displaytext;
    }
</script>
<script>
    function validation() {
        if (document.getElementById('dept').value == 0) {
            alert('Please select a department');
            document.getElementById('dept').focus();
            return false;
        }
    }
</script>
@endsection
