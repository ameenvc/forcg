@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
<head>
<script>
function ddlin_out()
{
    var d=document.getElementById("in_out");
    var displaytext=d.options[d.selectedIndex].text;
	document.getElementsByName('in_outt')[0].value=displaytext;
	if(displaytext=="Inhouse")
	{
		document.getElementsByName('agency')[0].style.visibility='hidden';
		document.getElementById("lblag").style.visibility='hidden';
	}
	else{
		document.getElementsByName('agency')[0].style.visibility='visible';
		document.getElementById("lblag").style.visibility='visible';
	}
    // $('#in_outt').value=displaytext;

}

</script> 
</head>

    <body>
        
                <!-- Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">HR Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Edit Training</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/hrtmgmt">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->

                <!-- Page Content -->
                <div class="content">
                  

                   <!-- Horizontal -->
                    <div class="block block-rounded">
                       
                        <div class="block-content block-content-full">
                            <div class="row">
                                
                                <div class="col-lg-10">
                                    <!-- Form Horizontal - Default Style -->
                                    <form name="form" class="mb-5" action="{{url('/user/hr/updatetraining')}}" method="POST">
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
                        @if(count($data)>0)
				              @foreach($data as $dat)
                              <input type="hidden" id="id" name="id" value=" {{ $dat->id }} ">
                                    
                                         <div class="form-group" style="display:inline-block;width:49%;">
                                             <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Training Title</label>
                                              <div class="col-sm-12">
                                                   {{ Form::text('title',$dat->training_title ,array('class'=>'form-control','style'=>'font-size: 13px')) }}
                                              </div>
                                         </div>
                                         <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="in_out"  style="font-size:13px;">Selected Inhouse / Outside</label>
                                            <div class="col-sm-12">
                                                 <input type="text"style="font-size: 13px;" class="form-control" id="in_outt" name="in_outt"  readonly value="{{$dat->inhouse_outside}}">
                                            </div>
                                         </div>
                                         <div class="form-group" style="display:inline-block;width:98.5%;">
                                            <label class="col-sm-12 col-form-label" for="type"style="font-size:13px;" id="lblt">Training Type</label>
                                            <div class="col-sm-12">
                                            <div class="form-check">
                                                <label class="form-check-label" id="lblappr1" style="font-size:13px;">
                                                     <input class="form-check-input" type="checkbox" name="type[]" id="typec1" value="Induction "><span></span>Induction&nbsp;&nbsp;&nbsp;&nbsp;
                                                </label><br>
                                                <label class="form-check-label" id="lblappr2" style="font-size:13px;">
                                                     <input class="form-check-input" type="checkbox" name="type[]" id="typec2" value="On Job "><span></span>On Job&nbsp;&nbsp;&nbsp;&nbsp;
                                                </label><br>
                                                <label class="form-check-label" id="lblappr3" style="font-size:13px;">
                                                     <input class="form-check-input" type="checkbox" name="type[]" id="typec3" value="Awareness "><span></span>Awareness&nbsp;&nbsp;&nbsp;&nbsp;
                                                </label><br>
                                                <label class="form-check-label" id="lblappr4" style="font-size:13px;">
                                                     <input class="form-check-input" type="checkbox" name="type[]" id="typec4" value="Certificate "><span></span>Certificate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </label><br>
                                                <label class="form-check-label" id="lblappr5" style="font-size:13px;">
                                                     <input class="form-check-input" type="checkbox" name="type[]" id="typec5" value="License "><span></span>License&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </label>
                                               <br> <label class="form-check-label" id="lblappr6" style="font-size:13px;">
                                                     <input class="form-check-input" type="checkbox" name="type[]" id="typec6" value="Competitive "><span></span>Competitive
                                                </label>
                                            </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="inhouse"  style="font-size:13px;">Select</label>
                                            <div class="col-sm-12">
                                                 <select class="form-control" style="font-size: 13px;"  name="in_out" id="in_out" onchange="ddlin_out()" >
                                                           <option value="0" selected="" disabled>Choose</option>
                                                           <option value="Inhouse">Inhouse</option>
                                                           <option value="Outside">Outside</option>
                                                 </select>
                                           </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="agency" id="lblag" style="font-size:13px;">Training Agency</label>
                                            <div class="col-sm-12">
                                                 {{ Form::text('agency',$dat->training_agency,array('class'=>'form-control','style'=>'font-size: 13px')) }}
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="provider"  style="font-size:13px;">Name Of Trainer</label>
                                            <div class="col-sm-12">
                                                 {{ Form::text('provider',$dat->training_provider,array('class'=>'form-control','style'=>'font-size: 13px')) }}
                                             </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="dur"  style="font-size:13px;">Training Duration</label>
                                            <div class="col-sm-12">
                                                 {{ Form::text('dur',$dat->training_duration,array('class'=>'form-control','style'=>'font-size: 13px')) }}
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="periods"  style="font-size:13px;">Date Of Training</label>
                                            <div class="col-sm-12">
                                               {{ Form::date('periods',$dat->periods,array('class'=>'form-control','style'=>'font-size: 13px')) }}
                                            </div>
                                        </div>
                                       
                                        <div class="form-group" style="display:inline-block;width:49%;"> 
                                         <label class="col-sm-12 col-form-label" for="empname"  style="font-size:13px;">Employee Name </label>
                                            <div class="col-sm-12"> 
                                                  {{ Form::text('empname',$dat->emp_name ,array('class'=>'form-control','style'=>'font-size: 13px')) }}
                                            </div>
                                        </div> 
                                        
                                        <div class="form-group" style="display:inline-block;width:98.5%;"> 
                                         <label class="col-sm-12 col-form-label" for="benefits"  style="font-size:13px;">Benefits Of Training</label>
                                            <div class="col-sm-12"> 
                                            <textarea rows="7" type="text"style="font-size: 13px;" class="form-control" id="benefits" name="benefits"  required>{{$dat->benefits}}</textarea>
                                                 <!-- {{ Form::text('benefits',$dat->benefits ,array('class'=>'form-control','style'=>'font-size: 13px')) }} -->
                                            </div>
                                       </div> 
                 
                                        <div class="form-group" style="display:inline-block;width:49%;"> 
                                         <label class="col-sm-12 col-form-label" for="empname"  style="font-size:13px;">Position</label>
                                            <div class="col-sm-12"> 
                                                 {{ Form::text('pos',$dat->position ,array('class'=>'form-control','style'=>'font-size: 13px')) }}
                                            </div>
                                       </div> 
                                       {{ Form::text('typee',$dat->training_type ,array('class'=>'form-control','style'=>'font-size: 13px;visibility:hidden')) }}
                              @endforeach
                        @endif
                                       
                                       
                                        <div class="form-group row">
                                            <div class="col-sm-8 ml-auto">
                                                <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:446px;"onclick="return confirm('Are you sure to submit the request?')"  >Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- END Form Horizontal - Default Style -->

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Horizontal -->

               

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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/hr/register">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  Job Description
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/hr/trainingregister">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Training Schedule
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                   
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Apps Modal -->
       
    </body>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript">
$(document).ready(function() {
    var t=document.getElementsByName('typee')[0].value;
    var one = t.includes("Induction");
    if(one==true)
    {
        document.getElementById("typec1").checked=true;
        
    }
    var two = t.includes("On Job");
    if(two==true)
    {
        document.getElementById("typec2").checked=true;
        
    }
    var three = t.includes("Awareness");
    if(three==true)
    {
        document.getElementById("typec3").checked=true;
        
    }
    var four = t.includes("Certificate");
    if(four==true)
    {
        document.getElementById("typec4").checked=true;
        
    }
    var five = t.includes("License");
    if(five==true)
    {
        document.getElementById("typec5").checked=true;
        
    }
    var six = t.includes("Competitive");
    if(six==true)
    {
        document.getElementById("typec6").checked=true;
        
    }
});
</script> 


<!-- script for plus button of emp name and position -->
<script type="text/javascript">   
    var i = 0;
    $("#add").click(function(){
        ++i;
         $("#dynamicTable").append('<tr> <td><input type="text"style="font-size: 13px;width:230px;" class="form-control" name="empname[]" placeholder="Enter Employee Name"></td><td><input type="text"style="font-size: 13px;width:230px;" class="form-control" name="pos[]" placeholder="Enter Position"></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Employee Name"> <i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');

    });
    $(document).on('click', '.remove-tr', function(){  
 $(this).parents('tr').remove();
 });
</script>
</html>
@endsection
