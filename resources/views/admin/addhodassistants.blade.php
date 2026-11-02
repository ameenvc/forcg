@extends('admin.nav')
@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Add HOD Assistants</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/viewDepartments">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:80%;">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" method="POST" action="{{url('/admin/inserthodassts')}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                           
                            
                            <div class="col-lg-12">
       
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Department</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="dept" value="{{$dept}}" style="font-size:13px;" class="form-control" readonly>
                                    </div>
                                </div>
                                    
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">HOD</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="hod" value="{{$hod}}" style="font-size:13px;" class="form-control" readonly>
                                    </div>
                                </div>
                                
                                @if($deptb->hod_assistants!=NULL)
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Assistants</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="" value="{{$deptb->hod_assistants}}" style="font-size:13px;" class="form-control" readonly>
                                    </div>
                                </div>
                                @else
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;">Choose Assistants</label>
                                    <div class="col-sm-8">
                                        <select class="js-select2 form-control" name="asst[]" id="asst" style="font-size: 13px;width:100%;" multiple required data-placeholder="Choose Assistants">      
                                            @foreach($user as $u)                
                                            <option value="{{$u->name}}">{{$u->name}}</option>  
                                            @endforeach  
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <input type="hidden" name="id" value="{{$id}}">
                                <div class="form-group row">
                                   @if($deptb->hod_assistants!=NULL)
                                    <div style="color:red;font-size:12px;">
                                       Note: HOD Assistants Already Added. To Change Assistants, click Edit in Departments table
                                    </div>
                                   @else
                                    <div class="col-sm-12 text-center">
                                        <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="ADD">
                                    </div>
                                    @endif
                                </div>
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



        // always return false to prevent form from submission.
    }

</script>
@endsection
