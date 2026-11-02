@extends('layouts.multiselect')
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
            <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue3" name="tvalue3" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>

    </ul>
</div>
@endsection
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
<div class="main-container">

    <!-- Page Content -->
    <div class="content" style="width:95%;">
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
        <div class="alert alert-warning">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('errmsg')!!}
        </div>
        @endif
        <!-- Topics -->
         <!-- heading part starts -->  
        <div class="block-header block-header-default">
            <div class="block-options" style="margin-left:-25px; font-size: 1rem;">
                       <span style="text-transform: uppercase;font-weight:bold" class="font-w700">
                          Upload Content</span> /
                        <span style="text-transform: capitalize;font-weight:normal;font-size:15px;"> 
                        Document Management </span>
            </div>
            <div>
                <table width="100%" style="padding-top:5px;padding-right:15px;">
                    <tr>
                        <td align="right">
                            <a class="btn btn-sm btn-info" style="font-size:13px;" href="{{ route('doc.governancehub')}}">
                                 <i class="fa fa-x fa-angle-left mr-1"></i>&nbsp;Back to Procedures
                            </a>                          
                        </td>                    
                    </tr>
                </table>
            </div>
        </div>
         <!-- Ends heading part  -->
        <div class="block block-rounded">     
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- upload form starts here -->
                            <form action="{{route('doc.insertdocumentexcelcontent')}}" method="POST" enctype="multipart/form-data" files="true">
                                @csrf
                                <div class="col-lg-10">
                                    <input type="hidden" name="document_id" value="{{$data->id}}">
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label class="col-form-label" for="docnumber" style="font-size: 13px;">Document Number</label>
                                            <input type="text" class="form-control" name="docnumber" id="docnumber" 
                                             value="{{$data->document_number}}" style="font-size: 13px;" readonly>
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label class="col-form-label" for="docname" style="font-size: 13px;">Document Name</label>
                                            <input type="text" class="form-control" name="docname" id="docname" 
                                            value="{{$data->document_name}}" style="font-size: 13px;" readonly>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label class="col-form-label" for="contentupload" style="font-size: 13px;">Upload Content</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control" id="content_data" name="content_data" value="" required>
                                                <div class="input-group-append">
                                                    <input type="submit" value="Upload Details" class="btn btn-primary" style="font-size:10px;"/>  
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-6" style="margin-top:40px;">
                                        <label class="col-form-label"  style="font-size: 10px;"></label>
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#note" style="font-size:10px;">
                                              Note (*important)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- upload form ends here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Topics -->
    </div>
    <!-- END Page Content -->

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
                            <span style="" style="font-size:10px;">1) The Content Details Excel Sheet Should have the Headings 'title,responsibility,rich_text' respectively and should be in the same sequence.</span>
                            <br/><br/><span style="" style="font-size:10px;">2) Content Details Should Be In Single Excel Sheet.</span>
                            <br/><br/><span style="" style="font-size:10px;">3) All are mandatory fields.</span>
                            <br><br><span class="text-primary">Below is the Sample Excel Format, Click on the Image to view</span><br><br>
                            
                                    <!-- sample excel file image starts here -->
                                    <img id="myImg" src="{{ asset('images/document_module/document_content.png') }}" alt="Sample Excel File Format" title="Sample Excel File Format" style="width:100%;max-width:300px">
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

<!-- Opens from the modal toggle button in the header -->
@include('user.document.governance_hub_headers')
<!-- END Apps Modal -->

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