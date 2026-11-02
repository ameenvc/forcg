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
                                        <a class="link-fx" href="">Edit Request Issues</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/contextmgmt">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
               
               
                 <!-- Page Content -->
                 <div class="content"style="width:100%">
              
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                        <div class="block-content block-content-full" >
                        <div class="scrollable" style="height:450px;">
            <form class="mb-5" action="{{url('/user/context/editrequest_issues')}}" method="post">
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
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                            

                    <table class="table table-bordered table-striped table-vcenter " id="dynamicTable">
                        <thead style="background-color:#FFFFFF">
                            <tr>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Title</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Type</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Issue</th>
                                <!-- <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Purpose</th> -->
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Monitoring Methodology</th>                       
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Bias</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Accountability</th>
                            </tr>
                        </thead>
                        
                                
                        <tbody>
                            @foreach($data as $dat1) 
                            <tr> 
                                <td class="text-center" style="font-size: 13px;" >  
                                    <input  type="text" style="font-size: 13px;"  style="width: 100px;" class="form-control" id="title[]" name="title[]"  readonly="" value="{{$dat1->title}}"> 
                                </td>
                                <td class="text-center "style="font-size:13px;width: 200px;">  
                                    <select class="form-control" id="select_type" style="font-size: 13px;"  name = "type[]"  >
                                        <option value=" {{$dat1->type}}"> {{ $dat1->type }}</option><br>
                                        @if(count($parties)> 0 )
                                            @foreach($parties as $p)                                  
                                                @if($p->type != $dat1->type)
                                                    <option style="font-size: 13px;"  value=" {{$p->type}}"  > {{ $p->type }}</option><br>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </td>  
                                <td class="text-center" style="font-size: 13px;" >  
                                    <input style="font-size: 13px;"  type="text" style="width: 100px;" class="form-control" id="issue[]" name="issue[]"   value="{{$dat1->issue}}"> 
                                </td>
                                <td class="text-center" style="font-size: 13px;" >  
                                    <input  style="font-size: 13px;"  type="text" style="width: 100px;" class="form-control" id="monitoring[]" name="monitoring[]"   value="{{$dat1->monitoring}}"> 
                                </td>
                                <td class="text-center "style="font-size:13px;width: 200px;">  
                                    <select class="form-control" id="select_type" style="font-size: 13px;"  name = "bias[]"  >
                                        <option style="font-size: 13px;"  value=" {{'Negative'}}"> Negative</option><br>                                       
                                        <option style="font-size: 13px;"  value=" {{'Positive'}}"  >Positive</option><br>
                                    </select>
                                </td> 
                                <td class="text-center" style="font-size: 13px;" >  
                                    <input style="font-size: 13px;"  type="text" style="width: 100px;" class="form-control" id="accountability[]" name="accountability[]"   value="{{$dat1->accountability}}"> 
                                </td>
                                 <div class="form-group">                                  
                                    <input style="font-size: 13px;"  type="hidden" name="sno[]" value="{{ $dat1->id}}"> 
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
                    </div>
        </div>
    </div>
    </div>

                </form>
                        
            
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