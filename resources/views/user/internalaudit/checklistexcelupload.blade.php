@extends('layouts.multiselect')
@section('content')

<style>
body {font-family: Arial, Helvetica, sans-serif;}

#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal1 {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal1-content {
  margin: auto;
  display: block;
  width: 1200px;
  max-width: 1900px;
  height:auto;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal1-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: relative;
  top: 2px;
  right: 120px;
  /*margin-right:100px;*/
  color: white;
  font-size: 20px;
  font-weight: normal;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: white;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal1-content {
    width: 100%;
  }
}
</style>
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
                                        <a class="link-fx" href="{{url('user/checklistlibrary')}}">Upload Checklist Title and Points</a>
                                    </li> 
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="{{url('/user/internalaudit/checklistdetails/'.$data->id)}}">Back</a>
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
                       <form class="mb-5" action="{{route('insertchecklistexceldatas')}}" enctype="multipart/form-data"  method="POST" files="true">
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
                                @if(Session::has('errmsg'))
                                    <div class="alert alert-danger">
                                        <a class="close" data-dismiss="alert">×</a>
                                        {!!Session::get('errmsg')!!}
                                    </div>
                                @endif
                                <input type="hidden" name="checklistid" value="{{$data->id}}"/>
                                @if($data->department_id!=null)                                
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="department">Department</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control"style="font-size: 13px;" id="department" name="department" value="{{$data->departmentdetails?ucfirst($data->departmentdetails->departmentname):''}}" readonly>
                                    </div>
                                </div>
                                @elseif($data->division_id!=null)                                
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="division">Division</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control"style="font-size: 13px;" id="division" name="division" value="{{$data->divisiondetails?ucfirst($data->divisiondetails->name):''}}" readonly>
                                    </div>
                                </div>
                                @elseif($data->area_id!=null)                                
                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="area">Subdivision</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control"style="font-size: 13px;" id="area" name="area" value="{{$data->areadetails?ucfirst($data->areadetails->name):''}}" readonly>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="checklist">Checklist</label>
                                    <div class="col-sm-12">
                                    <input type="text" class="form-control"style="font-size: 13px;" id="checklist" name="checklist" value="{{ucfirst($data->checklist_name)}}" readonly>
                                    </div>
                                </div>

                                <!-- <div class="form-group" style="display:inline-block;width:49%;">
                                    <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="Uploadfile">Upload</label>
                                    <div class="col-sm-12">
                                       <input type="file" class="form-control"style="font-size: 13px;" id="checklist_data" name="checklist_data" value="" required>
                                        <input type="submit" value="Upload Details" class="btn btn-primary" style="font-size:10px;margin-left:350px;margin-top:-60px"/>  
                                    </div>   
                                </div>
                                -->
                                 
                                <div class="form-group" style="display:inline-block;width:47%;padding-left:15px;">
                                <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="Uploadfile">Upload</label>
                                   <div class="input-group">
                                     <input type="file" class="form-control" id="checklist_data" name="checklist_data" value="" required>
                                        <div class="input-group-append">
                                            <input type="submit" value="Upload Details" class="btn btn-primary" style="font-size:10px;"/>  
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="form-group" style="display:inline-block;width:49%;">
                                  <div class="col-sm-12">
                                        <a href="#" class="button btn btn-warning font-weight-normal mb-2" style="font-size:10px;font-family:Arial, Helvetica, sans-serif;" data-toggle="modal"  data-target="#note">Note (*important)</a>
                                  </div>     
                                </div>

                                <!-- Note starts -->
                                <div class="modal fade" id="note" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="block block-rounded block-themed block-transparent mb-0">
                                                        <div class="block-header bg-primary-dark">
                                                            <h3 class="block-title">*Important</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                    <i class="fa fa-fw fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="block-content font-size-sm">
                                                        <div class="table-responsive">
                                                        <span style="" style="font-size:10px;">1) The Checklist Details Excel Sheet Should have the Headings 'title, control_area, purpose, items_verified ' respectively and should be in the same sequence.</span>
                                                        <br/><br/><span style="" style="font-size:10px;">2) Checklist Details Should Be In Single Excel Sheet.</span>
                                                        <br/><br/><span style="" style="font-size:10px;">3) Title and Control Area are mandatory fields.</span>
                                                        <br><br><span class="text-primary">Below is the Sample Excel Format, Click on the Image to view</span><br><br>
                                                        
                                                             <!-- sample excel file image starts here -->
                                                             <img id="myImg" src="{{ asset('images/audit/check1.png') }}" alt="Sample Excel File Format" title="Sample Excel File Format" style="width:100%;max-width:300px">
                                                                <!-- The Modal -->
                                                                    <div id="myModal" class="modal1">
                                                                    <span class="close"><i class="fa fa-2x fa-window-close" style="color:white;"></i></span>
                                                                        <img class="modal1-content" id="img01">
                                                                        <div id="caption"></div>
                                                                    </div>
                                                              <!--sample excel file image starts here --> 
                                                              <br><br>
                                                              <span class="text-danger">Warning : Please do not merge cells</span>
                                                              <br><br>
                                                            </div>
                                                        </div>
                                                        <div class="block-content block-content-full text-right border-top">
                                                            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" style="font-size:10px;">Close</button>
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal" style="font-size:10px;">Ok</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       <!-- Note ends -->

                       
                   </div>
                </div>
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




<script>
        // Get the modal
        var modal = document.getElementById("myModal");
        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById("myImg");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        img.onclick = function(){
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
        }
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() { 
        modal.style.display = "none";
        }
    </script>           
@endsection