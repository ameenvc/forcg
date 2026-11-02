@extends('layouts.multiselect')

@section('content')

     
<style> 
 .stepsactive{
    font-size:13px;
    color:#25499c;
    /* background-color: #d9e2f6; */
    background-color: #17a2b8;   
    border-color: #d9e2f6;
    padding:5px 5px 5px 5px;
    border-radius:5px;
} 
.stepsnonactive{
    font-size:13px;
    color:#343a40;
    /* background-color: #edf0f2; */
    /* background-color: #FAEBD7; */
    /* border-color: #edf0f2; */
    background-color: #708090;
    border-color: #FFFFE0;
    padding:5px 5px 5px 5px;
    border-radius:5px;
} 
 .next-arrow {
        font-size: 30px;
        padding-top: 55px; 
        padding-left:20px;
        padding-right:20px;
        /* color: #007bff; */
        color: #17a2b8;
        transform: rotate(0deg);
  }
  .next-arrow2 {
        font-size: 30px;
        padding-top: 55px; 
        padding-left:20px; 
        padding-right:20px;
        color:darkgrey;
        transform: rotate(0deg);
  }
  input,select,textarea,span.select2.select2-container.select2-container--default{
        /* outline: 1px solid #AFEEEE; */
        outline: 1px solid #B0E0E6;
    }
    hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #B0E0E6;
    border-style: dashed;
    margin: 1em 0;
    padding: 0;
}
</style>

    <!-- START Hero -->
    <div class="bg-body-light">
         <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Emergency Response</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Create Drill Report</a>
                        </li>
                        <!-- <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('emergency.register') }}">Back</a>
                        </li> -->
                    </ol>
                </nav>
            </div>
        </div> 
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
     <div class="block block-rounded "style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <div class="block-content block-content-full">

        <table width="100%">
             <!-- Step blocks starts -->
            <tr><td style="padding-top:20px;">
               <table align="center">
                    <tr>
                        <td width="25%" class="text-center stepsactive text-white">
                            <b>Step 1<br>Create Report</b>
                        </td>
                        <td><span class="next-arrow">&rarr;</span></td>
                        <td width="25%"class="text-center text-muted stepsnonactive text-white">
                            <b>Step 2<br>Review</b>
                        </td>
                        <td ><span class="next-arrow2">&rarr;</span></td>
                        <td width="25%" class="text-center text-muted stepsnonactive text-white">
                            <b>Step 3<br>Approval</b>
                        </td>
                                                            
                    </tr>
                </table>
           </td></tr>
           </table>
            <!-- End Step blocks -->
    
        <div class="row" style="margin-top:60px;margin-left:90px;">
            <div class="col-lg-11">
                <div class="form-group" style="display:inline-block;width:90%;">
                    <label class="col-sm-12 col-form-label" for="last_drill" style="font-size:13px;">Upload Images</label>
                    <div class="col-sm-12">
                        <form method="post" action="{{route('emergency.image.store')}}" enctype="multipart/form-data" class="dropzone" id="dropzone">
                            @csrf
                        </form>                                                                           
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                        <span class="col-form-label text-danger" style="font-size:13px;">
                            Note : 1) Maximum number of images uploaded at a time is 6 <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                   2) Images should be of size less than 160KB <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;        
                                   3) Images should be in .jpeg,.jpg,.png,.gif format. 
                        </span>                                                                        
                    </div>
                </div>                        
                <div style="margin-top:50px;margin-bottom:50px;">
                    <!-- <button type="button" for="submit" class="btn btn-primary" style="font-size:13px;margin-left:550px;">Submit</button> -->
                    <a href="{{route('emergency.register',['msg' => '1'])}}" class="btn btn-primary" style="font-size:13px;margin-left:550px;">Submit</a> 
                </div>                  
            </div>
        </div> 

    
        </div>
      </div>
    </div>
   <!-- End Page Content -->             
    @include('user.emergencyresponse.header_options') 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.js"></script>

    <script type="text/javascript">
        let CSRF_TOKEN = "";
    window.onload = function() {
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    }

        Dropzone.options.dropzone ={
            maxFiles: 6, 
            // maxFilesize:12,
            maxFilesize:0.16,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
               return time+file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            timeout: 50000,
            removedfile: function(file) 
            {
                var name = file.upload.filename;
                console.log(name);
                $.ajax({
                    type: 'POST',
                    url: '{{ url("user/emergencyresponse/image/delete") }}',
                    data: {filename: name, _token: CSRF_TOKEN},
                    success: function (data){
                        console.log("File has been successfully removed!!");
                    },
                    error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }});
                    var fileRef;
                    return (fileRef = file.previewElement) != null ? 
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },
};
</script>
     
    
@endsection