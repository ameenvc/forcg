@extends('admin.datatable')
@section('css_before')
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
.avatar-thumb {
        border: 1px solid #80808059;
        background: #eeeeee36;     
        width: 50%;
        height: 8rem;
    }
    .avatar-thumb img {
        padding: 1rem;
        object-fit: contain;
        width: 100%;
        height: 100%;
    }

    /* Disable text selection */
    #referencetable {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
       
    @media (min-width: 481px ) and (max-width: 600px) {
    .bgcontent_position{
        font-size:10px;
        /* color:blue;   */
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5;  
    }
 } 

 /* @media (min-width: 601px) and (max-width: 768px) {
    .bgcontent_position{
        font-size:10px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5;  
    }
 }  */
    @media (min-width: 601px) and (max-width: 768px) {
    .bgcontent_position{
        font-size:10px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5;  
    }
 }

 @media (min-width: 769px) and (max-width: 1024px) {
    .bgcontent_position{
        font-size: 13px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5; 
    }
 }

 @media (min-width: 1025px) and (max-width: 1280px) {
    .bgcontent_position{
        font-size: 11px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5; 
    }
 }
 @media (min-width: 1281px) and (max-width: 1440px) {
    .bgcontent_position{
        font-size: 12px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5; 
    }
 }
 @media (min-width: 1400px) { 
    .bgcontent_position{
        font-size:13px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5; 
    }
   }
 /* @media (min-width: 768px) and (max-width: 991.98px) {
    .bgcontent_position{
        font-size: 13px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5; 
    }
 } */

 /* @media (min-width: 992px) and (max-width: 1020px) { 
    .bgcontent_position{
        font-size:12px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5; 
    }
  }

  @media (min-width: 1020px) and (max-width: 1200px) { 
    .bgcontent_position{
        font-size:11px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5; 
    }
  }
  @media (min-width: 1200px) and (max-width: 1400px) { 
    .bgcontent_position{
        font-size:12px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5; 
    }
   }
   @media (min-width: 1400px) { 
    .bgcontent_position{
        font-size:14px;
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        line-height: 1.5; 
    }
   } */

</style>
@endsection
@section('content')
    <div class="content">
        @if(Session::has('message'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('message')!!}
        </div>
        @endif
        @if(Session::has('delmessage'))
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('delmessage')!!}
        </div>
        @endif
        <!--audit report table starts -->
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">Customized Reports Table</h3>
                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="/admin/home">Audit Management</a>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="block-content block-content-full" style="width:90%;">
                    @if(Session::has('msg'))
                        <div class="alert alert-success">
                            <a href="javascript:void(0)" class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                    @endif
                    @if(count($errors) >0 )
                        @foreach($errors->all() as $error)
                            <div class='alert alert-warning'>
                            <a href="javascript:void(0)" class="close" data-dismiss="alert">×</a>
                                    {{ $error }}
                            </div>
                        @endforeach
                    @endif	
                    <div class="row">
                       <div class="col-lg-12">
                            <div class="row">
                                <div class="col-sm-8 mb-2">
                                    <form method="POST" action="{{url('/admin/audit/uploadreport')}}"  enctype="multipart/form-data" >
                                        {{csrf_field()}}
                                       <div class="form-group row">
                                           <label  class="col-lg-4 bgcontent_position" for="logo">
                                               Upload Report Template<span style="color:red;">*</span></label>
                                           <div class="col-lg-8">
                                               <div class="input-group">
                                                   <input type="file" class="form-control" style="font-size: 13px;"
                                                           id="audit_report" name="audit_report" required>
                                                   <div class="input-group-append">
                                                       <input type="submit" value="Upload" class="btn btn-primary" style="font-size:12px;"/>  
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </form>
                                </div>
                                <div class="col-sm-2 mb-2">
                                    <a href="#" class="btn btn-sm btn-warning" 
                                            style="font-size:10px;vertical-align:baseline;" 
                                            data-toggle="modal"  data-target="#note">Note (*important)
                                    </a> 
                                </div>
                                <div class="col-sm-2 mb-2">
                                    @if($audit_reports)
                                        <a style="font-size:11px;vertical-align:baseline;"
                                            class="btn btn-sm btn-alt-primary"
                                            href="{{asset("storage/ModuleReportTemplates/audit/" . $audit_reports->file_name)}}"
                                            download="{{$audit_reports->file_name}}" data-toggle="tooltip" 
                                            title="Click here to download template">
                                            Download&nbsp;&nbsp;<i class="fa fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>     
                       </div>                                  
                    </div>                  
                </div>
            </div>
       </div>
        <!--End audit report  table  -->
        <!-- Note starts -->
            <div class="modal fade" id="note" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
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
                            <!-- <span style="font-size:14px;">1) Report template should be created in a Word document</span>
                             <br/><br/><span style="font-size:14px;">2) Position of Fields can be in any order</span>
                           <br/><br/><span style="font-size:14px;">3) Each Field name should have a corresponding Field value
                           <br/><br/><span style="font-size:14px;">4) Field value should be exactly same as given in the reference table</span> -->
                           <span style="font-size:14px;">1) Your customized report template should be in a Word document format</span>
                             <br/><br/><span style="font-size:14px;">2) You have the flexibility to arrange the fields in your customized report in any order you prefer</span>
                             <br/><br/><span style="font-size:14px;">3) You can choose the specific fields that are needed for your report
                           <br/><br/><span style="font-size:14px;">4) Field names in the customized report must have a corresponding Field value
                           <br/><br/><span style="font-size:14px;">5) Field values should match exactly as shown in the reference table</span>
                           <br><br>  
                           <span class="text-danger">Warning : Do not copy-paste field values. Ensure there are no extra spaces between field values</span> 
                           <br><br>                    
                            <table class="table table-bordered table-secondary" id="referencetable" style="width:450px;font-size:13px;">
                                 <tr>
                                    <td colspan="2" class="text-center text-dark"><b>Reference Table</b></td>
                                 </tr>
                                 <tr>
                                    <td width="30%"><b>Field Name</b></td>
                                    <td><b>Field value</b></td>
                                 </tr>
                                 <tr>
                                    <td width="30%">Ref Id #</td>
                                    <td>@{{id}}</td>
                                 </tr>
                                 <tr>
                                    <td>Added Date</td>
                                    <td>@{{postdate}}</td>
                                 </tr>
                                 <tr>
                                    <td>Department</td>
                                    <td>@{{dept}}</td>
                                 </tr>
                                 <!-- <tr>
                                    <td>Category</td>
                                    <td>@{{cat}}</td>
                                 </tr> -->
                                 <!-- <tr>
                                    <td>Department</td>
                                    <td>@{{dept}}</td>
                                 </tr> -->
                                 <tr>
                                    <td>Division</td>
                                    <td>@{{div}}</td>
                                 </tr>
                                 <tr>
                                    <td>Subdivision</td>
                                    <td>@{{subdiv}}</td>
                                 </tr>
                                 <tr>
                                    <td>Type</td>
                                    <td>@{{type}}</td>
                                 </tr>
                                 <tr>
                                    <td>Observation</td>
                                    <td>@{{observe}}</td>
                                 </tr>
                                 <tr>
                                    <td>Process stage</td>
                                    <td>@{{pstage}}</td>
                                 </tr>
                                 <tr>
                                    <td>Responsibility</td>
                                    <td>@{{responsible}}</td>
                                 </tr>
                                 <tr>
                                    <td>RootCause</td>
                                    <td>@{{root}}</td>
                                 </tr>
                                 <tr>
                                    <td>Correction</td>
                                    <td>@{{correct}}</td>
                                 </tr>
                                 <tr>
                                    <td>Corrective Action</td>
                                    <td>@{{correctact}}</td>
                                 </tr>
                                 <tr>
                                    <td>Reported by</td>
                                    <td>@{{reported}}</td>
                                 </tr>
                                 <tr>
                                    <td>Agreed Action</td>
                                    <td>@{{agreedact}}</td>
                                 </tr>
                                 <tr>
                                    <td>Related Risk</td>
                                    <td>@{{relatedrisk}}</td>
                                 </tr>
                                 <tr>
                                    <td>Attachments Issues</td>
                                    <td>@{{attachmentissues}}</td>
                                 </tr>
                                 <tr>
                                    <td>Attachment action</td>
                                    <td>@{{attachmentaction}}</td>
                                 </tr>
                                 <tr>
                                    <td>Closure Remarks</td>
                                    <td>@{{remarks}}</td>
                                 </tr>
                                 <tr>
                                    <td>Review Action Remarks</td>
                                    <td>@{{actionremarks}}</td>
                                 </tr>
                                 <tr>
                                    <td>Action Reviewed By</td>
                                    <td>@{{actionreviewed}}</td>
                                 </tr>
                                
                                 <tr>
                                    <td>Action Reviewed Date</td>
                                    <td>@{{actionrevieweddate}}</td>
                                 </tr>
                                 <tr>
                                    <td>Hod Reviewed</td>
                                    <td>@{{hodreviewed}}</td>
                                 </tr>
                                 <tr>
                                    <td>Hod Reviewed Date</td>
                                    <td>@{{hodreviewdate}}</td>
                                 </tr>
                                 <tr>
                                    <td>Closed by</td>
                                    <td>@{{closedby}}</td>
                                 </tr>
                                 <tr>
                                    <td>Closed Date</td>
                                    <td>@{{closeddate}}</td>
                                 </tr>
                                 <tr>
                                    <td>DueDate</td>
                                    <td>@{{duedate}}</td>
                                 </tr>
                            </table>
                           
                            

                            <table class="table table-bordered table-warning" id="patterntable" style="width:350px;font-size:13px;">
                                <tr>
                                    <td><b>Field value</b></td>
                                    <td><b>Pattern Validation</b></td>
                                    <!-- <td><b>Correct Pattern</b></td> -->
                                </tr>
                                <tr>
                                    <td>@{{id}}</td>
                                    <td><i class="fa fa-check text-success" style="font-size:14px;"></i></td>
                                </tr>
                                <tr>
                                    <td>{ {id} }</td>                               
                                    <td><i class="fa fa-fw fa-times text-danger" style="font-size:15px;"></i></td>
                                   
                                </tr>
                                <tr>
                                    <td>@{{ id }}</td>
                                    <td><i class="fa fa-fw fa-times text-danger" style="font-size:14px;"></i></td>
                                   
                                </tr>
                                <tr>
                                    <td>@{{i d}}</td>
                                    <td><i class="fa fa-fw fa-times text-danger" style="font-size:15px;"></i></td>
                                   
                                </tr>
                                <tr>
                                    <td>{ {i d} }</td>
                                    <td><i class="fa fa-fw fa-times text-danger" style="font-size:15px;"></i></td>
                                   
                                </tr>
                                <tr>
                                    <td>@{{i d}}</td>
                                    <td><i class="fa fa-fw fa-times text-danger" style="font-size:15px;"></i></td>
                                    
                                </tr>
                            </table>

                            <!-- <span class="text-danger">Warning : Please do not copy paste field values. Do not leave spaces between field values</span> -->
                                  
                            <br><br><span class="text-primary">Below is a Sample Format, Click on the Image to view</span><br><br>
                            
                                    <!-- sample word file image starts here -->
                                    <img id="myImg" src="{{ asset('images/audit/audit_report_latest_template.png') }}" alt="Sample Audit Word Template Format" title="Sample Audit Word Template Format" style="width:100%;max-width:300px">
                                    <!-- The Modal -->
                                        <div id="myModal" class="modal1">
                                        <span class="close"><i class="fa fa-2x fa-window-close" style="color:white;"></i></span>
                                            <img class="modal1-content" id="img01">
                                            <div id="caption"></div>
                                        </div>
                                    <!--sample word file image starts here --> 
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
    <script>
        // Disable context menu
        document.getElementById('referencetable').addEventListener('contextmenu', function (e) {
            e.preventDefault();
        });

        // Disable Ctrl+C, Ctrl+X, Ctrl+V
        document.getElementById('referencetable').addEventListener('keydown', function (e) {
            if (e.ctrlKey && (e.key === 'c' || e.key === 'x' || e.key === 'v')) {
                e.preventDefault();
            }
        });
    </script>        
@endsection

