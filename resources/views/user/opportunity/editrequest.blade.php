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
                        <li class="breadcrumb-item">Opportunity</li>
                        
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="">Edit</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="{{ route('opportunity.oppindex') }}">Back</a>
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
            <form class="mb-5" action="{{url('/user/opportunity/editrequest')}}" method="post">
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
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Opportunity ID</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Department</th>
                                {{-- <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Risk Group</th> --}}
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Opportunity Name</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Opportunity Description</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Target Date</th>
                               
                               
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Likelihood</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Benefit</th>
                                {{-- <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Likelihood</th> --}}
                                
                                
                            </tr>
                        </thead>
                        
                                
                        <tbody>
                            @foreach($data as $dat1)
                            <?php
                            $dept = DB::table('departments')->where('id',$dat1->department)->value('departmentname');
                            ?>
                            <tr>
                                <td class="text-center font-size-sm">  
                                    <input  type="text" style="font-size:13px;"  class="form-control" id="oppid[]" name="oppid[]" Readonly=""  value="{{$dat1->dep_id}}" > 
                                </td> 

                                <td class="text-center font-size-sm">  
                                    <input  type="text" style="font-size:13px;width:150px;"  class="form-control" id="department[]" name="department[]" Readonly=""  value="{{$dept}}" > 
                                </td> 
                               
                                <td class="text-center"style="font-size:13px;width:300px;">  
                                    <input  type="text" style="font-size:13px;"  class="form-control" id="oppname[]" name="oppname[]"   value="{{$dat1->oppname}}" > 
                                </td>  
                              
                                <td class="text-center"style="font-size:13px;width:250px;">  
                                    <textarea  type="text" style="font-size:13px;"  class="form-control" id="oppdes[]" name="oppdes[]"   >{{$dat1->oppdes}}</textarea > 
                                </td>  
                                {{-- <td 
                                class="text-center "style="font-size:13px;width: 200px;">{{ Form::date('tardate[]',$dat1->tardate,array('class'=>'form-control','style'=>'font-size: 13px')) }}
                                   
                                </td> --}}
                                <td class="text-center font-size-sm">  
                                    <input  type="date" style="font-size:13px;"  class="form-control" id="tardate[]" name="tardate[]"   value="{{$dat1->tardate}}" > 
                                </td> 
                                <td class="text-center "style="font-size:13px;width:125px;">  
                                    <select class="form-control" id="likelihood" style="font-size: 13px;"  name = "likelihood[]"  >
                                        <option style="font-size: 13px;" selected="" >{{$dat1->likelihood}}</option>
                                        <option style="font-size: 13px;"  value=" {{'High'}}"> High</option><br>                                       
                                        <option style="font-size: 13px;"  value=" {{'Moderate'}}"  >Moderate</option><br>
                                        <option style="font-size: 13px;"  value=" {{'Low'}}"> Low</option><br>
                                    </select>
                                </td>
                                <td class="text-center "style="font-size:13px;width:125px;">  
                                    <select class="form-control" id="benefit" style="font-size: 13px;"  name = "benefit[]"  >
                                        <option style="font-size: 13px;" selected="" >{{$dat->benefit}}</option>
                                        <option style="font-size: 13px;"  value=" {{'High'}}"> High</option><br>                                       
                                        <option style="font-size: 13px;"  value=" {{'Moderate'}}"  >Moderate</option><br>
                                        <option style="font-size: 13px;"  value=" {{'Low'}}"> Low</option><br>
                                    </select>
                                </td>
                              
                                 <div class="form-group">
                                  
                                    <input type="hidden" name="sno[]" value="{{ $dat1->id}}"> 
                                    {{-- <input type="hidden" name="revno[]" value="{{ $dat1->revnumber}}">  --}}
                                    
                                      
                                  </div>
                            </tr> 
                           
                            @endforeach                           
                        </tbody>
                        
                    </table>
                    @endif
                  
				
            <div >
				
                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary"style="font-size:13px;margin-left:93%;">Submit</button>
                     
                    </div>
                   
                </form>
                        
            </div>
        </div>
    </div>
    </div>
    </main>
    </body>
   
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="{{ route('opportunity.oppindex') }}">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/{{ route('opportunity.oppaccomplished') }}">
                                            <div class="block-content text-center">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Accomplished
                                                </p>
                                            </div>
                                        </a>
                                         </div>
                                                                      
                                    <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='{{ route('opportunity.oppanalysis') }}'>
                                            <div class="block-content text-center">
                                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Payments -->
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
    </body>
    
    </html>
    @endsection