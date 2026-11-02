@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
   <body>
        
                <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"  style="height:0px;">
                
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Maintenance Management</li>
                        
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="">Maintenance Request</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="/user/maintenancemgmt">Back</a>
                            </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
                <!-- END Hero -->

                <!-- Page Content -->
    <div class="content"style="width:100%">
         <!-- Horizontal -->
        <div class="block block-rounded">
                       
            <div class="block-content block-content-full">
                <div class="row">
            <form class="mb-5" action="{{url('/user/maintenance/mrequest')}}" method="post">
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
         
            @foreach($data as $dat)
            @endforeach
            @endif
            
                   
                    @if(count($data)>0)
                    <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable">
                 
                        <thead style="background-color:#FFFFFF">
                            <tr>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Department</th>
                                <th class="text-center" style="font-size:13px;width: 40px;text-transform:capitalize">Equipment ID</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Equipment Type</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Serial number</th>
                                <th class="text-center" style="font-size:13px;width: 250px;text-transform:capitalize">Nature of fault</th>
                                <th class="text-center" style="font-size:13px;width: 30px;text-transform:capitalize">Severity</th>
                                <th class="text-center" style="font-size:13px;width: 30px;text-transform:capitalize">Priority</th>
                                
                            </tr>
                        </thead>
                        
                                
                        <tbody>
                            @foreach($data as $dat1)
                            <tr> 
                               <td class="text-center font-size-sm">  
                                    <input  type="text" style="font-size:13px;"  class="form-control" id="department[]" name="department[]" Readonly=""  value="{{$dat1->department}}" > 
                                </td> 
                                <td class="text-center ">  
                                    <input  type="text" style="font-size: 13px;" class="form-control" id="equid[]" name="equid[]"   value="{{$dat1->equid}}" Readonly=''> 
                                </td>  
                                <td class="text-center ">  
                                    <input  type="text" style="font-size: 13px;" class="form-control" id="type[]" name="type[]"   value="{{$dat1->type}}" Readonly=''> 
                                </td>  
                                <td class="text-center ">  
                                    <input  type="text" style="font-size: 13px;" class="form-control" id="serial[]" name="serial[]"   value="{{$dat1->serial}}" Readonly=''> 
                                </td> 
                                <td class="text-center ">  
                                    <textarea  type="text" style="font-size:13px;"  class="form-control" id="fault[]" name="fault[]"   > {{$dat1->fault}}</textarea>
                                </td>
                               
                               <td class="text-center "style="font-size:13px;width:125px;">  
                                    <select class="form-control" id="severity" style="font-size: 13px;"  name = "severity[]"  >
                                        <option style="font-size: 13px;" selected="" >{{$dat1->severity}}</option>
                                        <option style="font-size: 13px;"  value=" {{'High'}}"> High</option><br>                                       
                                        <option style="font-size: 13px;"  value=" {{'Moderate'}}"  >Moderate</option><br>
                                        <option style="font-size: 13px;"  value=" {{'Low'}}"> Low</option><br>
                                    </select>
                                </td>
                                <td class="text-center "style="font-size:13px;width:125px;">  
                                    <select class="form-control" id="priority" style="font-size: 13px;"  name = "priority[]"  >
                                        <option style="font-size: 13px;" selected="" >{{$dat1->priority}}</option>
                                        <option style="font-size: 13px;"  value=" {{'High'}}"> High</option><br>                                       
                                        <option style="font-size: 13px;"  value=" {{'Moderate'}}"  >Moderate</option><br>
                                        <option style="font-size: 13px;"  value=" {{'Low'}}"> Low</option><br>
                                    </select>
                                </td>
                            
                                 <div class="form-group">
                                  
                                    <input type="hidden" name="sno[]" value="{{ $dat1->id}}"> 
                                     
                                  </div>
                            </tr> 
                           
                            @endforeach                           
                        </tbody>
                        
                    </table>
                    @endif
                   
				
            <div >
				
                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary"style="font-size:13px;margin-left:90%;">Submit</button>
                     
                    </div>
                </form>
            </div>
        </div>
        </div>
                
                  
        </body>
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
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/maintenancemgmt">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Total Equipment List
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/pmfolder">
                                        <div class="block-content text-center"style="height:130px">
                                            <i class="fas fa-address-card fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                              PM Plan and Report
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Payments -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/testfolder">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                               Test Plan
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Payments -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/maintenance/breakfolder">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                               Breakdown Maintenance
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Apps Modal -->
  

</body>
</html>
@endsection