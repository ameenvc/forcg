@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">


    <body>
        
                <!-- Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">HR Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Edit Training</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/hrtmgmt">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->

                <!-- Page Content -->
                <div class="content">
                  

                   <!-- Horizontal -->
                    <div class="block block-rounded">
                       
                        <div class="block-content block-content-full">
                            <div class="row">
                                
                                <div class="col-lg-10">
                                    <!-- Form Horizontal - Default Style -->
                                    <form name="form" class="mb-5" action="{{url('/user/hr/updatetraining')}}" method="POST">
                                    {{csrf_field()}}
                        
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
                                    <input type="hidden" name="editid" value="{{$data->id}}">
                                         <div class="form-group" style="display:inline-block;width:49%;">
                                             <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Training Title</label>
                                              <div class="col-sm-12">
                                                <input type="text"style="font-size: 13px;" class="form-control" id="title" name="title" value="{{$data->training_title}}" placeholder="Enter Training Title" required>
                                              </div>
                                         </div>
                                         <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="inhouse"  style="font-size:13px;">Inhouse / Outside</label>
                                            <div class="col-sm-12">
                                                <select class="form-control" name="in_out" id="in_out"  style="font-size: 13px;">
                                                    <option value="0" selected="" disabled>Choose</option>
                                                    <option value="Inhouse" <?php if($data->inhouse_outside=="Inhouse") {?> selected<?php } ?>>Inhouse</option>
                                                    <option value="Outside" <?php if($data->inhouse_outside=="Outside") {?> selected<?php } ?>>Outside</option>
                                                </select>
                                                   
                                            </div>
                                        </div>
                                         <div class="form-group" style="display:inline-block;width:98.5%;">
                                            <label class="col-sm-12 col-form-label" for="type"style="font-size:13px;">Training Type</label>
                                            <div class="col-sm-12">
                                                <?php
                                                    $traintype = explode(',',$data->training_type);
                                                    $trainingtype = ['Induction','On Job','Awareness','Certificate','License','Competitive'];                                                      
                                                 ?>
                                                 <div class="form-check">
                                                   @foreach($trainingtype as $item)
                                                    <label class="form-check-label" id="lblappr1" style="font-size:13px;">       
                                                        <input class="form-check-input" type="checkbox" name="type[]" id="type" value="{{$item}}" 
                                                          <?php foreach($traintype as $val) if($val == $item){?> checked <?php } ?> >{{$item}}</label><br> 
                                                   @endforeach       
                                                 </div>            
                                            </div>                
                                        </div> 
                                       @if($data->inhouse_outside=="Outside")                                                             
                                       <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="agency"  style="font-size:13px;">Training Agency</label>
                                            <div class="col-sm-12">
                                              <input type="text" style="font-size: 13px;" class="form-control" id="dataagency" name="agency" value="{{$data->training_agency}}" placeholder="Enter Training Agency">
                                            </div>
                                        </div>
                                      @endif

                                       <div class="form-group" id="agency" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="agency"  style="font-size:13px;">Training Agency</label>
                                            <div class="col-sm-12">
                                              <input type="text" style="font-size: 13px;" class="form-control"  name="agency" placeholder="Enter Training Agency">
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="provider"  style="font-size:13px;">Name Of Trainer</label>
                                            <div class="col-sm-12">
                                            <input type="text"style="font-size: 13px;" class="form-control" id="provider" name="provider" value="{{$data->training_provider}}" placeholder="Enter Name of Trainer" required>
                                             </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="dur"  style="font-size:13px;">Training Duration(In Hours)</label>
                                            <div class="col-sm-12">
                                              <input type="text"style="font-size: 13px;" class="form-control" id="dur" name="dur" value="{{$data->training_duration}}" placeholder="Enter Training Duration" required>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="periods"  style="font-size:13px;">Date Of Training</label>
                                            <div class="col-sm-12">
                                                <?php
                                                  $trainingdate = DateTime::createFromFormat('Y-m-d', $data->periods);
                                                  $trainingdate=$trainingdate->format('Y-m-d');
                                                ?>
                                                <input type="date" class="js-datepicker form-control" id="periods" name="periods" value="{{$trainingdate}}" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy"style="font-size: 13px;">
                                            </div>
                                        </div>
                                       
                                        <div class="form-group" style="display:inline-block;width:98.5%;"> 
                                         <label class="col-sm-12 col-form-label" for="benefits"  style="font-size:13px;">Benefits Of Training</label>
                                            <div class="col-sm-12"> 
                                                <textarea rows="9" type="text"style="font-size: 13px;" class="form-control" id="benefits" name="benefits" placeholder="Enter Training Benefits" required>{{$data->benefits}}</textarea>
                                            
                                            </div>
                                       </div> 
                 
                                    <div class="form-group" style="display:inline-block;width:98.5%;"> 
                                         <label class="col-sm-12 col-form-label" for="empname"  style="font-size:13px;">Employee Details</label>
                                        <div class="col-sm-12"> 
                                            <table width="90%" cellpadding="5" class="table table-bordered"> 
                                                                            
                                                    <tr style="font-size:13px;text-transform:capitalize;" class="text-center">
                                                       <th>#</th>
                                                       <th>Employee Number</th>
                                                       <th>Employee Name</th>
                                                       <th>Position</th>
                                                       <th>Department</th> 
                                                    </tr>
        
                                                <?php
                                                  $empid = explode(',',$data->employee_id); 
                                                  $sl_no = 1;            
                                                ?>
                                                 @foreach($empid as $val)
                                                  <tr class="text-center " style="font-size:12px;">
                                                    <td width="5%">{{$sl_no++}}</td>
                                                    <td width="16%"> 
                                                        <?php
                                                          $num = DB::table('employee_details')->where('id',$val)->value('emp_number');
                                                         ?>
                                                         {{$num}}
                                                   </td>  
                                                    <td> 
                                                        <?php
                                                          $name = DB::table('employee_details')->where('id',$val)->value('emp_name');
                                                         ?>
                                                         {{$name}}
                                                   </td>  
                                                   <td>
                                                        <?php
                                                          $post = DB::table('employee_details')->where('id',$val)->value('emp_position');
                                                         ?>
                                                         {{$post ? $post : '-' }}
                                                   </td> 
                                                   <td>
                                                        <?php
                                                          $dept = DB::table('employee_details')->where('id',$val)->value('emp_department');
                                                          $depname = DB::table('departments')->where('id',$dept)->value('departmentname');
                                                         ?>
                                                         {{$depname ? $depname : '-'}}
                                                   </td> 
                                                  </tr>
                                                 @endforeach
                                                
                                                
                                            </table>  
                                        </div>
                                    </div> 

                                    <div class="form-group" style="display:inline-block;width:49%;"> 
                                         <label class="col-sm-12 col-form-label" for="empname"  style="font-size:13px;">Add more Employees</label>
                                        <div class="col-sm-12"> 
                                            <table style="border:none;" id="dynamicTable" cellpadding="5">                                
                                               <tr>
                                                     <td>
                                                          <select class="form-control" onchange="sel(this.value,'{{$emp}}','{{$dep}}')"  id="empnumber[]" name = "number[]"  style="font-size: 13px;width:230px;">     
                                                            <option value="">Choose Employee Number</option>
                                                                @if(count($emp)>0)
                                                                @foreach($emp as $empd)  
                                                                  <option  value="{{$empd->emp_number}}" >{{$empd->emp_number}} - {{$empd->emp_name}}</option>
                                                                @endforeach    
                                                                @endif  
                                                           </select>                      
                                                     </td>
                                                     <td>
                                                        <input type="text"style="font-size: 13px;width:230px;" class="form-control" id="name[0]" name="empname[]" placeholder="Employee Name" readonly>
                                                     </td>
                                                     <td>
                                                         <input type="text"style="font-size:13px;width:230px;" class="form-control" id="position[0]" name="pos[]"  placeholder="Employee Position" readonly>
                                                     </td>
                                                     <td>
                                                         <input type="text"style="font-size:13px;width:230px;" class="form-control" id="department[0]" name="department[]"  placeholder="Employee Department" readonly>
                                                     </td>

                                                     <td><button type="button" style="font-size:12px;"name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add">
                                                         <i class="fa fa-fw fa-plus"></i>
                                                         </button>
                                                    </td>
                                             
                                                </tr>
                                                      
                                            </table>  
                                        </div>
                                    </div> 

                                       
                                       
                                        <div class="form-group row">
                                            <div class="col-sm-8 ml-auto">
                                                <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:449px;"onclick="return confirm('Are you sure to submit the request?')"  >Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- END Form Horizontal - Default Style -->

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Horizontal -->

               

            <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
            <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Options</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="si si-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="row gutters-tiny">
                                  
                                <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/hr/register">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  Job Description
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/hr/trainingregister">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Training Schedule
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                   
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Apps Modal -->
       
    </body>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript">
$(document).ready(function() {
    $('#lblagency').hide();
   $('#agency').hide();
});
$("#in_out").change(function() {
                           console.log($("#in_out option:selected").val());
                           if ($("#in_out option:selected").val() == "Outside") {
                            $('#lblagency').show();
                             $('#agency').show();
                           }
                           else
                           {
                            $('#lblagency').hide();
                             $('#agency').hide();
                           }
});



    </script>


<!-- script for plus button of emp name and position -->
<!--<script type="text/javascript">   
    var i = 0;
    $("#add").click(function(){
        ++i;
         $("#dynamicTable").append('<tr><td><select class="form-control" name="empname[]" id="empname"  style="font-size: 13px;width:200px;" ><option value="" selected="" disabled>Select </option>   @foreach($emp as $e)<option value="{{$e->emp_name}}">{{$e->emp_name}}</option> @endforeach</select></td><td><input type="text"style="font-size: 13px;width:230px;" class="form-control" name="pos[]" placeholder="Enter Position"></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Employee Name"> <i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');

    });
    $(document).on('click', '.remove-tr', function(){  
 $(this).parents('tr').remove();
 });
</script>-->

<script type="text/javascript">   
    var i = 0;
    $("#add").click(function(){
        ++i;
         $("#dynamicTable").append('<tr><td><select class="form-control" onchange="sel(this.value,\'{{$emp}}\',\'{{$dep}}\','+i+')" id="empnumber[]" name = "number[]" required="true" style="font-size: 13px;width:230px;"><option value="">Choose Employee Number</option>)@foreach($emp as $empd)<option  value="{{$empd->emp_number}}">{{$empd->emp_number}} - {{$empd->emp_name}}</option>@endforeach</select></td><td><input type="text"style="font-size: 13px;width:230px;" class="form-control" id="name['+i+']" name="empname[]" placeholder="Employee Name" readonly></td><td><input type="text"style="font-size: 13px;width:230px;" class="form-control" name="pos[]" id="position['+i+']" placeholder="Enter Position" readonly></td><td><input type="text"style="font-size:13px;width:230px;" class="form-control" id="department['+i+']" name="department[]" required placeholder="Employee Department" readonly></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Employee Name"> <i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');

    });
    $(document).on('click', '.remove-tr', function(){  
 $(this).parents('tr').remove();
 });
</script>

<script>
function sel(value, details,deprts, i=0){
    datas=JSON.parse(details);
    depmts = JSON.parse(deprts);
    for(var key in datas){
        if(datas[key].emp_number===value)
        {
            document.getElementById('name['+i+']').value=datas[key].emp_name;
            var a = datas[key].emp_department;
            deprtname = '-';
            for (let m = 0; m < depmts.length; m++) 
            {
                if (depmts[m].id==a) 
                {
                    deprtname = depmts[m].departmentname;
                    break;
                }    
            }    
            //document.getElementById('department['+i+']').value=datas[key].emp_department;
            document.getElementById('department['+i+']').value=deprtname;
            if(datas[key].emp_position=='')
            {
                document.getElementById('position['+i+']').value='-';
            }
            else
            {
                document.getElementById('position['+i+']').value=datas[key].emp_position;
            }
        }
    }
}
</script>


</html>
@endsection
