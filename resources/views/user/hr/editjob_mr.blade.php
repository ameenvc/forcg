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
                                    <li class="breadcrumb-item">Hr Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Edit Job</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/hrjmgmt">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
               
               
                 <!-- Page Content -->
                 <div class="content">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded">
                        {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full">
            <form class="mb-5" action="{{url('/user/hr/editmrjob')}}" method="post">
                {{ csrf_field() }}
			
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
          
            
                   
                    @if(count($data)>0)
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                            

                    <table class="table table-bordered table-striped table-vcenter " id="dynamicTable">
                        <thead style="background-color:#FFFFFF">
                            <tr>
                                <!-- <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Employee Name</th> -->
                                
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Position Title <span class="text-danger">*</span></th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Job Code <span class="text-danger">*</span></th>
                                <!-- <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Training Type</th> -->
                                <th class="text-center" style="font-size:10px;width: 70px;text-transform:capitalize">Reports To <span class="text-danger">*</span></th>                       
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Prepared/Revised On</th>
                                {{-- <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Date Of Training</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Benefits Of Training</th>
                                <!-- <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize">Iso Standard</th> --> --}}
                            </tr>
                        </thead>
                        
                                
                        <tbody>
                            @foreach($data as $dat1) 
                            <tr> 
                                {{-- <td class="text-center" style="font-size: 10px;" >  
                                    <input  type="text" style="font-size: 10px;"  style="width: 100px;" class="form-control" id="empname[]" name="empname[]"   value="{{$dat1->emp_name}}"> 
                                </td> --}}
                               
                                <td class="text-center" style="font-size: 10px;" >  
                                    <input  type="text" style="font-size: 10px;"  style="width: 100px;" class="form-control" id="pos[]" name="pos[]"   value="{{$dat1->position_title}}" required> 
                                </td>
                                <td class="text-center" style="font-size: 10px;" >  
                                    <input style="font-size: 10px;"  type="text" style="width: 100px;" class="form-control" id="title[]" name="title[]"   value="{{$dat1->job_code}}" required> 
                                </td>
                                <td class="text-center" style="font-size: 10px;" >  
                                    <input  style="font-size: 10px;"  type="text" style="width: 100px;" class="form-control" id="name[]" name="name[]"   value="{{$dat1->reports_to}}" required> 
                                </td>
                                <td class="text-center" style="font-size: 10px;" >  
                                    <input  style="font-size: 10px;"  type="date" style="width: 100px;" class="form-control" id="dur[]" name="dur[]"   value="{{$dat1->prepared_on}}"> 
                                </td>
                                {{-- <td class="text-center" style="font-size: 10px;" >  
                                    <input  style="font-size: 10px;"  type="date" style="width: 100px;" class="form-control" id="date[]" name="date[]"   value="{{$dat1->periods}}"> 
                                </td>
                                <td class="text-center" style="font-size: 10px;" >  
                                    <input  style="font-size: 10px;"  type="text" style="width: 100px;" class="form-control" id="ben[]" name="ben[]"   value="{{$dat1->benefits}}"> 
                                </td> --}}
                                
                               
                                 <div class="form-group">                                  
                                    <input style="font-size: 10px;"  type="hidden" name="sno[]" value="{{ $dat1->id}}"> 
                                    {{-- <input type="hidden" name="revno[]" value="{{ $dat1->revnumber}}">  --}}
                                </div>
                            </tr> 
                           
                            @endforeach                           
                        </tbody>
                        
                    </table>
                    @endif
                    <div class="col-lg-10"> 
                    <br>
                </div>
				
            <div >
				
                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary"style="font-size:13px;margin-left:92%;">Submit</button>
                     
                    </div>
                    </div>
        </div>
    </div>
    </div>

                </form>
                        
            
    </main>
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
    </body>
    
<script type="text/javascript">
    // $(document).ready(function() {
    // $('#sel_purpose').select2({
    // });
    // });
    $(document).ready(function() {
    $('#sel_type').select2({
    });
    });
    //sel_bias
</script>
    </html>
@endsection