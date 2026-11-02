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
                                        <a class="link-fx" href="">Edit Request Party</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/context/interestparty">Back</a>
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
                <form class="mb-5" action="{{url('/user/context/editrequest_party')}}" method="post">
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
                    <div class="block">
                        <div class="block-content block-content-full">  
                        
                        @foreach($data1 as $dat1)   
                            
                                <div class="block">
                                    <div class="block-content block-content-full">
                                        <div class="form-group" style="display:inline-block;width:49%;"> 
                                            <label class="col-sm-12 col-form-label" for="intr_party" style="font-size: 13px;">Interested Party</label>
                                            <div class="col-sm-12"> 
                                                <input  type="text"  class="form-control" id="intr_party[]" style="font-size: 13px;" name="intr_party[]"  readonly="" value="{{$dat1->intr_party}}"> 
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;"> 
                                            <label class="col-sm-12 col-form-label" for="type" style="font-size: 13px;">Type</label>
                                            <div class="col-sm-12"> 
                                                <input  type="text"  class="form-control" id="type[]" style="font-size: 13px;" name="type[]"  readonly="" value="{{$dat1->type}}"> 
                                            </div>
                                        </div>  
                                        <div class="form-group" style="display:inline-block;width:49%;"> 
                                            <label class="col-sm-12 col-form-label" for="purpose" style="font-size: 13px;">Purpose of IP</label>
                                            <div class="col-sm-12"> 
                                                <input  type="text"  class="form-control" id="purpose[]" style="font-size: 13px;" name="purpose[]"  readonly="" value="{{$dat1->purpose}}"> 
                                            </div>
                                        </div> 
                                       
            
                                    <input type="hidden" name="Rid" value="{{$dat1->Cid}}" style="font-size: 13px;"> 
                                </div>
                    @endforeach    
                       
                        </div>
                    </div>
                    @if(count($data2) > 0)    
                        <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable">
                            <thead style="background-color:#FFFFFF">
                                <tr>
                                   <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Requirement of IP</th>
                                    <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Monitoring Methodology</th>   
                                    <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Accountability</th>
                                </tr>
                            </thead>                      
                            <tbody>
                                @foreach($data2 as $dat1) 
                                    <tr>                                        
                                        <td class="text-center font-size-sm">  
                                            <input  type="text" style="width: 260px;font-size: 13px;" class="form-control" id="reqip[]" name="reqip[]"   value="{{$dat1->reqip}}"> 
                                        </td> 
                                        <td class="text-center font-size-sm">  
                                            <input  type="text" style="width: 260px;font-size: 13px;" class="form-control" id="monitoring[]" name="monitoring[]"   value="{{$dat1->monitoring}}"> 
                                        </td>
                                        <td class="text-center font-size-sm">  
                                            <input  type="text" style="width: 280px;font-size: 13px;" class="form-control" id="accountability[]" name="accountability[]"   value="{{$dat1->accountability}}"> 
                                        </td>
                                        <div class="form-group">                            
                                            <input type="hidden" name="sno[]" value="{{ $dat1->id}}">                                             
                                        </div>
                                    </tr>                             
                                @endforeach                         
                            </tbody>                    
                        </table>
                        @endif
                      
                    <div style="text-align:center;">
                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px; float:right;margin-right:0%;">Submit</button>
                <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;float:right;margin-right:1%; ">Reset</button>
                </div> 
                    
                <!-- <div class="col-lg-10"> 
                <br>
                </div>				 -->
                                    
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