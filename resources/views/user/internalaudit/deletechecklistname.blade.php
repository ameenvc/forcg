@extends('layouts.multiselect')
@section('content')   
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                          <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Audit Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href=""> Checklist Library</a>
                                    </li>
                                    

                                   <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="{{url('user/checklistlibrary')}}">Delete Checklist </a>
                                    </li> 
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="{{url('/user/internalaudit/checklistdetails/'.$checklist->id)}}">Back</a>
                                    </li> 
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
               
                <div class="content">
                    <div class="block block-rounded">                      
                        <div class="block-content block-content-full">
                            <div class="row">                              
                                <div class="col-lg-10">                                
                                    <form class="mb-5" action="{{route('confirmdeletechecklistname')}}" enctype="multipart/form-data"  method="POST" files="true">
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
                                        @if($checklist->department_id!=null)                                
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="department">Department</label>
                                            <div class="col-sm-12">
                                               <input type="text" class="form-control"style="font-size: 13px;" id="department" name="department" value="{{$checklist->departmentdetails?ucfirst($checklist->departmentdetails->departmentname):''}}" readonly>
                                            </div>
                                        </div>
                                        @elseif($checklist->division_id!=null)                                
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="division">Division</label>
                                            <div class="col-sm-12">
                                               <input type="text" class="form-control"style="font-size: 13px;" id="division" name="division" value="{{$checklist->divisiondetails?ucfirst($checklist->divisiondetails->name):''}}" readonly>
                                            </div>
                                        </div>
                                        @elseif($checklist->area_id!=null)                                
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="area">Subdivision</label>
                                            <div class="col-sm-12">
                                               <input type="text" class="form-control"style="font-size: 13px;" id="area" name="area" value="{{$checklist->areadetails?ucfirst($checklist->areadetails->name):''}}" readonly>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="checklist">Checklist</label>
                                            <div class="col-sm-12">
                                               <input type="text" class="form-control"style="font-size: 13px;" id="checklist_name" name="checklist_name" value="{{$checklist->checklist_name}}" readonly>
                                            </div>
                                        </div>                                      
                                       
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label for="reason for deleting" class="col-sm-12 col-form-label" style="font-size:13px;">Reason for Deleting Checklist</label>
                                            <div class="col-sm-12">                       
                                                <select class="form-control" name="delete_reason" id="delete_reason" style="font-size: 13px;" required="true" onclick="return addreason()">
                                                    <option value="">Choose a Reason</option>              
                                                    <option value="testing">Created for Testing</option>
                                                    <option value="other">Other</option>                        
                                                </select> 
                                            </div>                             
                                        </div>
         
                                        <div class="form-group" style="display:none;width:49%;" id="otherdeletereason">
                                            <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="otherdelete">Please specify Reason</label>
                                            <div class="col-sm-12">
                                               <textarea type="text" class="form-control" id="other_reason" name="other_reason"  style="font-size:13px;"></textarea>
                                            </div>
                                        </div>     
                                    </div>
                                </div>                               
                                <input type="hidden" name="checklistid" value="{{$checklist->id}}"/>

                                <!-- <div style="text-align:center;">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:54%;">Delete</button>                        
                                </div> -->
                                <div style="text-align:center;">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:54%;">Submit</button>                        
                                </div>
                                       
                                    </form>
                                   </div>
                            </div>
                        </div>
                        

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
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                        <div class="block-content text-center">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Audit Schedule
                                            </p>
                                        </div>
                                    </a>
                                 </div>

                                 <div class="col-6">
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                        <div class="block-content text-center">
                                            <i class="fas fa-address-card fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                <br> Auditors List
                                            </p>
                                        </div>
                                     </a>
                                  </div>
                                                                      
                                  <div class="col-6">
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                        <div class="block-content text-center">
                                            <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                <br>Audit Log
                                            </p>
                                        </div>
                                    </a>
                                 </div>
                                 <div class="col-6">
                                    <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                        <div class="block-content text-center">
                                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Closed Audits
                                            </p>
                                        </div>
                                    </a>
                                  </div>
                                    <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                            <div class="block-content text-center">
                                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
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
          
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

            <script>
              function addreason(){
                    if(document.getElementById('delete_reason').value =='other'){
                        document.getElementById('otherdeletereason').style.display="";
                        document.getElementById('other_reason').required= true;
                    }else{
                        document.getElementById('otherdeletereason').style.display="none";
                        document.getElementById('other_reason').required= false;
                    }
                }

           </script>  
            
@endsection
