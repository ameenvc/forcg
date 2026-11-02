@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
                            <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
                           
                                <i class="fa fa-print  fa-1x" style="color:grey"></i> 
                         
                            </a>
                            </div>
<div class="input-group input-group-sm">
<button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
    <i class="fa fa-file-export fa-1x" style="color:grey;"></i> 
</button>
<ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">    
<li>
    <!--change route as below-->
        <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
            @csrf

            <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
            <!--change hidden input type id as below-->
            <input type="hidden"  id="tvalue1" name="tvalue1"/>
        </form>
    </li>
    <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel </button></li>
    <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
</ul>
</div>

<div class="input-group input-group-sm">
<a type="button"  href="{{url('/user/hr/addtraining')}}" class="btn btn-sm btn-dual" style="margin-left:8px;"  style="">
<i class="fa fa-1x fa-plus" style="color:grey;"></i>
</a>
</div>
@endsection  
@section('content')
<!doctype html>
<html lang="en">
<head>
<style>
    body {
  overflow-y: hidden; 
    }
  </style>
{{--<style>
.header{
    margin-left:6%;
    margin-bottom:-2.9%;
}
</style>--}}
        </head>
    <body>
                
    <input type="hidden" value="a1" id="tabselected"> 
                <!-- Page Content -->
                
                 <div class="content" style="width:100%;">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" style="margin-left:-1%;">
                    {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full">
                            <div class="scrollable" style="height:450px;" >
                                <table class="move" width="15%" style="margin-left:670px">
                                    <tr>
                                   
                                    <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                            <ol class="breadcrumb breadcrumb-alt"style="margin-right:-200px;margin-bottom:-100px;">
                                                <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">HR Management</li>
                                                <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                    <a class="link-fx" href="/user/hr/trainingregister" >Training Schedule</a>
                                                    

                                                </li>
                                                
                                            </ol>
                                        </nav>
                                    </th>
                                    </tr>
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                   
                                     <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;position:sticky;top:-1"></th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                                        <!-- <th class="text-center"style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >Employee Name</th>
                                        <th class="text-center"style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >Position</th> -->
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Training Title</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Training Type</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >Inhouse /  Outside</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Name Of Trainer</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Training Duration</th>
                                         <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Date Of Training</th>
                                        <!-- <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Benefits of Training</th> -->
                                    <!-- <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Action Comment</th>
                                        <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Reevaluate</th> -->
                                        <th class="text-center"style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >View</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Actions</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Participants</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $no=0;?>
                                    @foreach($data1 as $value)
                                    <?php $rr=$value->id; ?>
                                    <tr>
                                        <td><input class="text-center font-size-sm" type="checkbox" id="{{ $value->id}}" name="id[]" ></td>
                                        <td class="text-center " style="font-size:10px;height:30px;">{{$no=$no+1}}</td>
                                        <!-- <td class="text-center " style="font-size:10px;height:30px;">{{ $value->id}}</td> -->
                                        <!-- <td class="text-center " style="font-size:10px;">{{ $value->emp_name}}</td>
                                        <td class="text-center " style="font-size:10px;text-transform:capitalize">{{ $value->position}}</td> -->
                                        <td class="text-center " style="font-size:10px;">{{ $value->training_title }}</td>
                                        <td class="text-center" style="font-size:10px;"><?php echo nl2br( $value->training_type)?></td>
                                        <td class="text-center " style="font-size:10px;">{{ $value->inhouse_outside}}</td>
                                        <td class="text-center " style="font-size:10px;">{{ $value->training_provider}}</td>
                                        <td class="text-center " style="font-size:10px;">{{ $value->training_duration }}</td>
                                        <td class="text-center " style="font-size:10px;">{{ $value->periods}}</td>
                                        <!-- <td class="text-center " style="font-size:10px;">{{ $value->benefits}}</td> -->
                                    
                                        <td>
                                    
                                   
                                                            <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{  $value->id}}" data-target="#viewModal1{{ $value->id}}"><strong>View</strong> </button>
                                                                <div id="viewModal1{{ $value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h2 class="modal-title">Detailed View</h2>
                                                                            </div>
                                                                            <table class="table table-striped">
                                                                                <!-- <tr>
                                                                                    <th>Vendor ID</th>
                                                                                    <td>{{ $value->id }}</td>
                                                                                </tr> -->
                                                                                <!-- <tr>
                                                                                    <th style="width:70px">Employee Name</th>
                                                                                    <td>{{ $value->emp_name }}</td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <th>Position</th>
                                                                                    <td>{{ $value->position}}</td>
                                                                                </tr> -->
                                                                                <tr>
                                                                                    <th style="width:70px">Training Title</th>
                                                                                    <td>{{ $value->training_title }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Training Type</th>
                                                                                    <td><?php echo nl2br( $value->training_type)?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Inhouse / Outside</th>
                                                                                    <td>{{ $value->inhouse_outside}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Name Of Trainer</th>
                                                                                    <td>{{ $value->training_provider }}</td>
                                                                                </tr>
                                                                                @if($value->inhouse_outside=="Outside")
                                                                                 <tr>
                                                                                    <th>Training Agency</th>
                                                                                    <td>{{ $value->training_agency}}</td>
                                                                                </tr>
                                                                                @endif
                                                                                <tr>
                                                                                    <th>Training Duration</th>
                                                                                    <td>{{ $value->training_duration}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Date Of Training</th>
                                                                                    <td>{{ $value->periods}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Benefits Of Training</th>
                                                                                    <td><?php echo nl2br( $value->benefits)?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Status</th>
                                                                                    @if($value->status!=NULL)
                                                                                        <td>{{ $value->status}}%</td>
                                                                                    @else
                                                                                        <td> </td>
                                                                                    @endif
                                                                                </tr>
                                                                                 
                                                                               <tr>
                                                                                    <th>Added by</th>
                                                                                    <td>{{$value->posted_id }} &nbsp; {{$value->postedByHrtraining ? $value->postedByHrtraining->name : ''}} </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Added Date</th>
                                                                                    <td>{{$value->created_at }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Last Evaluated by</th>
                                                                                    <td>{{$value->evaluatedby }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Last Evaluated Date</th>
                                                                                    <td>{{$value->evaluated_at }}</td>
                                                                                </tr>
                                                                               
                                                                                
                                                                                
                                                                            </table>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td> 
                                       
                                        <div class="dropdown font-size-sm">
                                       <td class="font-w600 font-size-sm">
                                       
                                            <button type="button"class="btn btn-primary dropdown-toggle" id="dropdown-default-primary"  style="font-size:10px;width:80px;height:35px;" data-toggle="dropdown">Actions</button>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                                     <a href="{{ url('/user/hr/edittraining?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Edit</a>
                                                      <a href="{{ url('/user/hr/evaluate?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Evaluate</a>
                                              </div>
                                       
                                       </td>
                                       </div>
                                       <td class="text-center "style="font-size:10px;width: 50px;">
                                       <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{  $value->id}}" data-target="#viewModal2{{ $value->id}}"><strong>View</strong> </button>
                                       <div id="viewModal2{{ $value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                    <div class="modal-dialog ">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Participants List</h4>
                                                                            </div>
                                                                            <!--<table class="table table-striped">
                                                                            <tr>
                                                                                 <th>Employee Name</th>
                                                                                 <th>Position</th>
                                                                            </tr>
                                                                            <tr>
                                                                                 <td><?php echo nl2br( $value->emp_name)?></td>
                                                                                 <td><?php echo nl2br( $value->position)?></td>
                                                                            </tr>
                                                                           
                                                                            </table>-->
                                                                            <table width="60%" cellpadding="5" class="table table-bordered"> 
                                                                            
                                                    <tr style="font-size:13px;text-transform:capitalize;" class="text-center">
                                                       <th>#</th>
                                                       <th>Employee Number</th>
                                                       <th>Employee Name</th>
                                                       <th>Position</th>
                                                       <th>Department</th> 
                                                    </tr>
        
                                                <?php
                                                  $empid = explode(',',$value->employee_id); 
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
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-dark" data-dismiss="modal" style="font-size:8px;" >Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
                    {{-----------delete  button for mr ------------}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
                 $("#del").click(function () {
                     var dataArr = new Array();
                     if($('input:checkbox:checked').length > 0){
                         $('input:checkbox:checked').each(function(){ 
                             dataArr.push($(this).attr('id'));
                             // console.log(dataArr);
                             $.ajax({
                                 type    :'get',
                                 url     :"{{route('deletetraining')}}"+"?val="+$(this).attr('id'),
                                 success : function(response){
                                         // alert(response);
                                         window.location.href ="{{url('/user/hrtmgmt')}}";
                                         //  redirect('/user/doc/masterlist')->withData($data);
                                 },
                                 error   :function(response){
                                         alert(errResponse);
                                 }
                     });
                         });
             
                     }
                     else{
                             alert('No Record Selected');
                          }
                 });
             </script>
                {{-----------edit  button for mr ------------}}
               
                            <script>
                                $("#edit").click(function () {
                                    var dataArr = new Array();
                                    var group = new Array();
                                    var d;
                                    var all;
                                    if($('input:checkbox:checked').length > 0){
                                        $('input:checkbox:checked').each(function(){ 
                                            dataArr.push($(this).attr('id'));
                                        //  console.log(dataArr);
                                            
                                            all = ""+dataArr;
                                            group.push(all); // push all checkbox values into an array
                                        //  console.log(group);
                                            
                                            
                                    });
                                    $.ajax({
                                                type    :'get',
                                                url     :"{{route('editt')}}"+"?val="+(all),
                                                success : function(response){
                                                        // alert(response);
                                                    
                                                        window.location.href ="{{route('editt')}}"+"?val="+(all);
                                                                            // console.log(response);
                            
                                                },
                                                error   :function(response){
                                                        alert(errResponse);
                                                }
                                        });
                            
                                    }
                                    else{
                                            alert('No Record Selected');
                                        }
                                });
                                </script>
                   
                </div>
                <!-- END Page Content -->
            {{-- </main> --}}
            <!-- END Main Container -->
          

          
            <!-- END Footer -->

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
                                    <!-- <div class="col-6">
                                      
                                        <a class="block block-rounded block-link-shadow bg-body" href="{{url('/user/vendor/addvendor')}}" >
                                            <div class="block-content text-center"style="height:130px" >
                                             <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-4">
                                                Add Vendor
                                                </p>
                                            </div>
                                        </a>
                                       
                                    </div> -->
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/hrjmgmt">
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/hrtmgmt">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Training Schedule
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                     <div class="col-6">        
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/employeelist">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Employee List
                                                </p>
                                            </div>
                                        </a>              
                                    </div> 
                                    
                                    <div class="col-6">                    
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/employeelist">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Employee List
                                                </p>
                                            </div>
                                        </a>       
                                    </div>                   

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

              <!-- END Apps Modal -->
  
@section('sideitem')



@foreach($mr as $mrs)
@if(Auth::user()->email==$mrs)
<div class="input-group input-group-sm ml-2">
<button type="button" style="margin-left:460px;" class="btn btn-sm btn-dual"  id="edit" value=id[] >
<i class="fa fa-1x fa-edit" style="color:grey;"></i>
</button>
</div>

<div class="input-group input-group-sm ml-2">
 <button type="button" class="btn btn-sm btn-dual"  id="del"value=id[] >
 <i class="fas fa-1x fa-trash" style="color:red;"></i>
 </button>      
</div>
@endif
@endforeach
@foreach($tc as $t)
 @if($t->email==Auth::user()->email) 
<div class="input-group input-group-sm">
<a type="button" title="Add Questions"  href="{{url('/user/hr/addqstn')}}"class="btn btn-sm btn-dual" style="margin-left:8px;"  style="">
<i class="fa fa-1x fa-plus" style="color:grey;"></i>
</a>
</div>
@endif
@endforeach
@endsection  
    </body>

</html>
@endsection
