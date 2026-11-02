@extends('admin.datatable')

@section('content')

<main id="main-container">
         <!-- Services -->
          <!-- Hero -->
         
                <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:88%;margin-left:70px;margin-top:-75px">
                  <!-- Horizontal -->
                   <div class="block block-rounded">   
                   <div class="block-header">
                       <h3 class="block-title">Add Details</h3>
                       <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="margin-left:70px">
                                <ol class="breadcrumb breadcrumb-alt">
                                   
                                    <li class="breadcrumb-item" aria-current="page">
                                     
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/admin/viewUsers">Back</a>
                                    </li>
                                </ol>
                            </nav>
                            
                       
                        </div>      
                       
                                               
                       <div class="block-content block-content-full"  style="width:90%;margin-left:100px">
                           <div class="row">                                
                               <div class="col-lg-10">
                                   <!-- Form Horizontal - Default Style -->
                           <form class="mb-5" method="POST" action="{{url('/admin/updateDetails')}}" onsubmit="return validation()" enctype="multipart/form-data" >
                               {{csrf_field()}}
                                   @if(Session::has('msg'))
                                       <div class="alert alert-primary">
                                           <a class="close" data-dismiss="alert">×</a>
                                           {!!Session::get('msg')!!}
                                       </div>
                                   @endif
                                   <div class="col-lg-8">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Name</label>
                                           <div class="col-sm-8">
                                            <input type="text" name="name" value="{{ request()->get('name') }}" style="font-size: 13px;" class="form-control" >
                                           </div>
                                       </div>
                                      
                                       <div class="form-group row">
                                           <label style="font-size: 13px;" class="col-sm-4 col-form-label" for="title" >Employee Id</label>
                                           <div class="col-sm-8">
                                           <input type="text" class="form-control" style="font-size: 13px;" name="empid" value="{{ request()->get('value') }}"  >
                                           </div>
                                       </div>

                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Department set to</label>
                                           <div class="col-sm-8">
                                           <input type="text" class="form-control"  style="font-size: 13px;" readonly  id="dept_id" name="dept_id"  value="{{ request()->get('dept') }}">
                                           </div>
                                       </div>

                                       <input type="hidden"style="font-size:13px" id="userid" name="userid" value="{{ request()->get('val') }}"></tr>
                                        <input type="hidden"style="font-size:13px" id="usermail" name="usermail" value="{{ request()->get('email') }}"></tr>
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Department Name</label>
                                           <div class="col-sm-8">
                                           <select class="form-control" id="dept" name="dept" required="Please" style="font-size: 13px;" onchange="ddlselect()" >
                                                <option value="0">Please select</option>
                                                @foreach($departments as $dept)
                                                <option value="{{$dept->departmentname}}">{{$dept->departmentname}}</option>
                                               @endforeach
                                            </select>
                                        </div>
                                       </div>
                            
                                   <br>
                                   <div class="form-group row">
                                        <div class="col-md-6  col-xl-2" style="margin-top:-5px">
                                        <input type="submit"  name="update" value="Add" style=";margin-left:1px;margin-top:-60px;font-size: 10px;" class="btn btn-primary" onclick="formSubmit()">
                                        </div>
                                    </div>
                               </form> 
                           
                                                
                   </div>
               </div>
           </div>
       </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<script>
function ddlselect()
{
    var d=document.getElementById("dept");
    var displaytext=d.options[d.selectedIndex].text;
    document.getElementById("dept_id").value=displaytext;

}

</script>
        <script>
function validation() {


    if(document.getElementById('dept').value == 0){
            alert('Please select a department'); 
            document.getElementById('dept').focus();      
            return false;
         }
        
         

  // always return false to prevent form from submission.
}
</script>

     
   

@endsection