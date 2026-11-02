@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">


 <body>
    <main id="main-container" style="padding-top:0px;">
       
    <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Context Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Edit Request Change Log</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/context/changelogreg">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
               
               
                <div class="content"style="width:100%">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                        <div class="block-content block-content-full" >
                    <div class="scrollable" style="height:450px;">
            <form class="mb-5" action="{{url('/user/context/editrequest_cr')}}" method="post">
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
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbsp&nbspCR&nbsp#&nbsp&nbsp&nbsp</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Scope</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Reason</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Benefits</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Impacts</th>                       
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Changing Product/Service</th>
                                <!-- <th class="text-center" style="font-s3ze:13px;width: 50px;text-transform:capitalize">Department</th> -->
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Priority</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Review Authority</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Approve Authority</th>
                            </tr>
                        </thead>
                        
                                
                        <tbody>
                            @foreach($data as $dat1)
                            <tr> 
                                <td class="text-center" style="font-size: 13px;" >  
                                    <input  type="text" style="font-size: 13px;"  style="width: 100px;" class="form-control" id="crid[]" name="crid[]"  readonly="" value="{{$dat1->crid}}"> 
                                </td>
                                <td class="text-center " style="font-size: 13px;" >  
                                    <input  type="text" style="font-size: 13px;"  style="width: 100px;" class="form-control" id="scope[]" name="scope[]" value="{{$dat1->scope}}"> 
                                </td>
                                @php 
                                    $reason = $dat1->correctiveDetails ? 'MOC raised as part of the corrective action for NCR #'.$dat1->correctiveDetails->issueid.' to ensure required process changes are evaluated and approved.' 
                                        : $dat1->reason
                                @endphp
                                <td class="text-center" style="font-size: 13px;" >  
                                    <input  type="text" style="font-size: 13px;"  style="width: 100px;" class="form-control" id="reason[]" name="reason[]"  value="{{$reason}}"> 
                                </td>
                                <td class="text-center " style="font-size: 13px;" >  
                                    <input  type="text" style="font-size: 13px;"  style="width: 100px;" class="form-control" id="benefits[]" name="benefits[]"   value="{{$dat1->benefits}}"> 
                                </td>
                                <td class="text-center " style="font-size: 13px;" >  
                                    <input  type="text"  style="font-size: 13px;"  style="width: 100px;" class="form-control" id="impacts[]" name="impacts[]"   value="{{$dat1->impacts}}"> 
                                </td>
                                <td class="text-center " style="font-size: 13px;" >  
                                    <input  type="text" style="font-size: 13px;"  style="width: 100px;" class="form-control" id="changing_product[]" name="changing_product[]"   value="{{$dat1->changing_product}}"> 
                                </td>
                                <td class="text-center "style="font-size:13px;width: 200px;">  
                                    <select class="form-control" id="sel_priority" required name="priority[]" style="font-size: 13px;width:100%;">
                                        <option value="" disabled selected>Select Priority</option>
                                        @if(count($priority)> 0 )
                                        @foreach($priority as $prio)
                                        <option style="font-size: 13px;" value="{{$prio->id}}" {{$prio->id==$dat1->priority ?'selected' :''}}> {{ $prio->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>  
                                <td class="text-center "style="font-size:13px;width: 200px;">  
                                    <select name="rev_authority[]" id="sel_rev" class="form-control js-select2" required style="font-size: 13px;width:100%;" data-placeholder="Select Approve Authority">
                                        <option value="" selected disabled>Select Review Authority</option>
                                        @if(count($reviewer)> 0 )
                                        @foreach($reviewer as $rev)
                                        <option value="{{$rev->id}}" style="font-size: 13px;" {{$rev->id==$dat1->rev_authority ?'selected' :''}}> {{ $rev->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>  
                                <td class="text-center "style="font-size:13px;width: 200px;">  
                                    <select name="appr_authority[]" id="sel_appr" class="form-control js-select2" required style="font-size: 13px;width:100%;" data-placeholder="Select Approve Authority">
                                        <option value="" disabled selected>Select Approve Authority</option>
                                        @if(count($approver)> 0 )
                                        @foreach($approver as $appr)
                                        <option value="{{$appr->id}}" style="font-size: 13px;" {{$appr->id==$dat1->appr_authority ?'selected' :''}}> {{ $appr->name }}</option><br>
                                        @endforeach
                                        @endif
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
                    <div class="col-lg-10"> 
                    <br>
                </div>
				<div style="text-align:center;">
                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px; float:right;margin-right:0%;">Submit</button>
                <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;float:right;margin-right:1%; ">Reset</button>
                </div>
                 
                   
                </form>
                        
            </div>
        </div>
    </div>
    </div>
   
    </main>
              <!-- Apps Modal -->
              <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">More</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="si si-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="row gutters-tiny">
                                 
                                    <div class="col-6">
                                        <!-- CRM -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="{{url('/user/contextmgmt')}}">
                                            <div class="block-content text-center">
                                                <i class="fa fa-bug fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Issue Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END CRM -->
                                    </div>
                                   <div class="col-6">
                                       
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/context/interestparty">
                                            <div class="block-content text-center">
                                                <i class="fa fa-2x fa-id-card-alt text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    InterestedParty Register
                                                </p>
                                            </div>
                                        </a>
                                   
                                    </div>
                                   <div class="col-6">
                                       
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/context/changelogreg">
                                            <div class="block-content text-center">
                                                <i class="fa fa-2x fa-clipboard-list text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Change Log Register
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
            
            <!-- END Apps Modal -->
    </body>
    
<script type="text/javascript">
    $(document).ready(function() {
    $('#sel_priority').select2({
    });
    });

    $(document).ready(function() {
    $('#sel_department').select2({
    });
    });

    $(document).ready(function() {
    $('#sel_rev').select2({
    });
    });

    $(document).ready(function() {
    $('#sel_appr').select2({
    });
    });
</script>
    </html>
@endsection