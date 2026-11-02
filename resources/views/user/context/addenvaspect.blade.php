@extends('layouts.multiselect')

@section('content')
<!doctype html>
<html lang="en">
   
    <body>
        


            <!-- Main Container -->
            <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Environmental Aspect</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Add Aspect</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/enviaspects/folder">Back</a>
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
                               
                                    <!-- Form Horizontal - Default Style -->
                                    <form class="mb-5" action="{{url('/user/context/changelog/envaspect/insert')}}" onsubmit="return validation()" method="POST">
                                        {{csrf_field()}}
                                        <div class="col-lg-10">
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




                            <input type="hidden" name="context_id" value="{{$context_id}}">
                            <input type="hidden" name="context_crid" value="{{$context_crid}}">

                            <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="riskname" style="font-size: 13px;">Date</label>
                                            <div class="col-sm-12">
                                            <input type="date" style="font-size:13px;"class="js-datepicker form-control" id="date" name="date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="<?php echo date("Y-m-d");?>" readonly>
                                            </div>
                                        </div>


                                                <div class="form-group" style="display:inline-block;width:49%;"> 
                                                <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Team</label>
                                                <div class="col-sm-12"> 
                                                    <select class="js-select2 form-control"  id="example-select2-multiple" name = "team[]" style="font-size:13px;" placeholder="Choose Team" multiple>
                                                        <option></option>
                                                        @if(count($users)> 0 )
                                                        @foreach($users as $use)
                                                        <option value=" {{$use->name}}">{{ $use->name }}</option><br>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                           
                                          
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label  class="col-sm-12 col-form-label" style="font-size: 13px;" for="riskgroup">Department</label>
                                                <div class="col-sm-12">     
                                                        <input type="hidden" class="form-control" id="department" name="department"  value="{{$cont_dept_id}}">
                                                        <input type="text" class="form-control" readonly="true" value="{{$contdept}}" style="font-size: 13px;">
                                                    </div>
                                                </div>
                                                <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="riskname" style="font-size: 13px;">Location<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                            <input class="form-control" type="text" name="location" style="font-size: 13px;" required  placeholder="Enter location" >
                                            </div>
                                        </div>
                                        
                                        <div class="form-group" style="display:inline-block;width:98.5%;">
                                            <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Environmental activity<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <textarea type="text" class="form-control" id="activity" style="font-size: 13px;" name="activity"  required placeholder="Enter description"></textarea>
                                            </div>
                                        </div>
                              
                                        <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable" style="width:1050px;">
                            <thead style="background-color:#FFFFFF;">
                                <tr>
                                    <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize">Environmental&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Aspects&nbsp&nbsp</th>
                                    <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize">Emergency/ &nbsp&nbsp&nbsp&nbsp&nbsp&nbspNon&nbsp&nbspEmergency&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                    <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize">Potential &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspEnvironmental&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                    <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize">Occurrence Probability (A)</th>
                                    <!-- <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize">Frequency of Detection (B)</th> -->
                                    <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize">Nature of Consequence (B)</th>
                                    <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize">Legal/ Regulatory Requirement (C)</th>
                                    <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize">&nbsp&nbspQuantity&nbsp&nbsp (D)</th>
                                    <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize">Control&nbsp Measure&nbsp&nbsp&nbsp (E)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr> 
                                    <td><input type="text" name="doc[0][enviaspects]" style="font-size:12px;" required class="form-control"/></td> 
                                    <td><select name="doc[0][ER]" id="doc[0][ER]" class="form-control" style="font-size:12px;"><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="Emergency" >Emergency</option><option value="Non Emergency">Non Emergency</option></select></td>
                                    <td><select name="doc[0][potential]" id="doc[0][potential]" class="form-control" style="font-size:12px;" ><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="Resource Depletion" >Resource Depletion</option><option value="Air Pollution">Air Pollution</option><option value="Water Pollution">Water Pollution</option><option value="Noise and Vibrational Effect" >Noise and Vibrational Effect</option><option value="Land Contamination" >Land Contamination</option><option value="Ozone Depletion" >Ozone Depletion</option></select></td>
                                    <td><select name="doc[0][A]" id="doc[0][A]" class="form-control" style="font-size:12px;" > <option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="0.1" >0.1</option><option value="1" >1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select></td>
                                    <td><select name="doc[0][B]" id="doc[0][B]" class="form-control" style="font-size:12px;"><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="0.1" >0.1</option><option value="1" >1</option><option value="3">3</option><option value="5">5</option><option value="10">10</option></select></td>
                                    <td><select name="doc[0][C]" id="doc[0][C]" class="form-control"style="font-size:12px;" ><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option></select></td>
                                    <td><select name="doc[0][D]" id="doc[0][D]" class="form-control" style="font-size:12px;"><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option></select></td>
                                    <td><select name="doc[0][E]" id="doc[0][E]" class="form-control" style="font-size:12px;"><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option><option value="3" >3</option></select></td>
                                    <!-- <td><select name="doc[0][F]" id="doc[0][F]" class="form-control" style="font-size:12px;"><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option><option value="3" >3</option></select></td> -->
                                    <td><button type="button" style="font-size:12px;"name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add Aspect">
                                            <i class="fa fa-fw fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <div style="margin-top:-30px;">
                            {{-- <img src="{{url('images/bracket1.PNG')}}" alt="no logo" > --}}
                            <button type="button" style="color:black;border-radius:0%;font-size:11px; text-align: center;text-decoration: none;display: inline-block; cursor: pointer;" data-toggle="modal"  data-target="#viewModal1"><strong> A </strong> </button>
                            {{-- <img src="{{url('images/one.PNG')}}" alt="no log"  > --}} x
                            <button type="button" style="color:black;border-radius:0%;font-size:11px; text-align: center;text-decoration: none;display: inline-block;cursor: pointer;" data-toggle="modal"  data-target="#viewModal2"><strong>B </strong> </button>
                            {{-- <img src="{{url('images/bracket2.PNG')}}" alt="no log" >  --}}
                            {{-- <img src="{{url('images/mul3.PNG')}}" alt="no logo">  --}}x
                            <button type="button" style="color:black;border-radius:0%;font-size:11px;text-align: center;text-decoration: none;display: inline-block; cursor: pointer;" data-toggle="modal"  data-target="#viewModal3"><strong>C</strong> </button>
                            {{-- <img src="{{url('images/mul3.PNG')}}" alt="no logo">  --}}x
                            <button type="button" style="color:black;border-radius:0%;font-size:11px; text-align: center;text-decoration: none;display: inline-block;" data-toggle="modal"  data-target="#viewModal4"><strong> D </strong> </button>
                            {{-- <img src="{{url('images/mul3.PNG')}}" alt="no logo">  --}}x
                            <button type="button" style="color:black;border-radius:0%;font-size:11px;text-align: center;text-decoration: none;display: inline-block;cursor: pointer;" data-toggle="modal"  data-target="#viewModal5"><strong> E </strong> </button>
                            {{-- <img src="{{url('images/mul3.PNG')}}" alt="no logo">  --}}
                            <!-- <button type="button" style="color:black;border-radius:0%;font-size:11px; text-align: center;text-decoration: none;display: inline-block; margin: 4px 2px; cursor: pointer;" data-toggle="modal"  data-target="#viewModal6"><strong> F </strong> </button> -->
                            {{-- <img src="{{url('images/equal.PNG')}}" alt="no logo"> --}}=
                            {{-- <button type="button" style="color:black;border-radius:0%;font-size:11px; padding: 5px;text-align: center;text-decoration: none;display: inline-block; margin: 4px 2px; cursor: pointer;" data-toggle="modal"  data-target="#viewModal7"><strong> Significance </strong> </button> --}}
                            <button type="button" style="color:black;border-radius:0%;font-size:11px; text-align: center;text-decoration: none;display: inline-block; margin: 4px 2px; cursor: pointer;" data-toggle="modal"  data-target="#viewModal0"><strong> Significance </strong> </button>

                        </div>


                        <div id="viewModal1" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Environmental Aspect Information</h2>
                                    </div><br>
                                    <table class="table table-bordered table-striped " style="color:black;border: 2px solid black">
                                    @if(count($envinfo)>0)
                                                    @foreach($envinfo as $c)
                                                    @if($c->infoname=='A.PNG')
                                                  <center>  <img src="{{url('storage/envinfo/A.PNG')}}" style="height:490px;width:600px;align:center" alt="No Info"></center>
                                                   
                                               @break
                                                

                                                      
                                                         @endif
                                                         @endforeach
                                                         @if($c->infoname!='A.PNG')
                                                   <h3><center> No info</center></h3>
                                                    @endif
                                                    @else

<h3><center> No info</center></h3>
                                                         @endif
                                            
                                    </table>
                                    <br><br>
                                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                         
                        <div id="viewModal2" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Environmental Aspect Information</h2>
                                    </div><br>
                                    <table class="table table-bordered table-striped " style="color:black;border: 2px solid black">
                                    @if(count($envinfo)>0)
                                                    @foreach($envinfo as $c)
                                                    @if($c->infoname=='B.PNG')
                                                  <center>  <img src="{{url('storage/envinfo/B.PNG')}}" style="height:490px;width:570px;align:center" alt="No Info"></center>
                                                  @break
                                               
                                                   
                                                   @endif
                                                   
                                                    @endforeach
                                                   
                                                    @if($c->infoname!='B.PNG')
                                                   <h3><center> No info</center></h3>
                                                    @endif

                                                    @else

                                                  <h3> <center>No info</center></h3>

                                                         @endif
                                    </table>
                                    <br><br>
                                        
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                                           
                        <div id="viewModal3" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Environmental Aspect Information</h2>
                                    </div><br>
                                    <table class="table table-bordered table-striped " style="color:black;border: 2px solid black">
                                    @if(count($envinfo)>0)
                                                    @foreach($envinfo as $c)
                                                    @if($c->infoname=='C.PNG')
                                                   <center> <img src="{{url('storage/envinfo/C.PNG')}}"    style="height:490px;width:570px;align:center" alt="No Info"></center>
                                                @break
                                                    @endif
                                                    @endforeach

                                                    @if($c->infoname!='C.PNG')
                                                   <h3><center> No info</center></h3>
                                                    @endif

                                                    @else

<h3><center> No info</center></h3>
                                                        @endif
                                    </table>
                                    <br><br>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                
                                         
                        <div id="viewModal4" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Environmental Aspect Information</h2>
                                    </div><br>
                                    <table class="table table-bordered table-striped " style="color:black;border: 2px solid black">
                                    @if(count($envinfo)>0)
                                                    @foreach($envinfo as $c)
                                                    @if($c->infoname=='D.PNG')
                                                   <center> <img src="{{url('storage/envinfo/D.PNG')}}"   style="height:490px;width:570px;align:center" alt="No Info"></center>
                                                       @break
                                                
                                                  @endif
                                                    @endforeach
                                                    @if($c->infoname!='D.PNG')
                                                   <h3><center> No info</center></h3>
                                                    @endif
                                                    @else

<h3><center> No info </center></h3>
                                                   
                                                        @endif
                                    </table>
                                    <br><br>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div id="viewModal5" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Environmental Aspect Information</h2>
                                    </div><br>
                                    <table class="table table-bordered table-striped " style="color:black;border: 2px solid black">
                                    @if(count($envinfo)>0)
                                                    @foreach($envinfo as $c)
                                                    @if($c->infoname=='E.PNG')
                                                   <center> <img src="{{url('storage/envinfo/E.PNG')}}"   style="height:490px;width:600px;align:center" alt="No Info"></center>
                                                    @break


                                                    @endif
                                                    @endforeach

                                                    @if($c->infoname!='E.PNG')
                                                   <h3><center> No info</center></h3>
                                                    @endif
                                                    @else

<h3><center> No info</center></h3>
                                                    
                                                        @endif
                                    </table>
                                    <br><br>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <div id="viewModal6" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Environmental Aspect Information</h2>
                                    </div><br>
                                    <table class="table table-bordered table-striped " style="color:black;border: 2px solid black">
                                    @if(count($envinfo)>0)
                                                    @foreach($envinfo as $c)
                                                    @if($c->infoname=='F.PNG')
                                                   <center> <img src="{{url('storage/envinfo/F.PNG')}}"  style="height:490px;width:600px;align:center" alt="No Info"></center>
                                                   @break
                                                    @endif
                                                    @endforeach
                                                    @if($c->infoname!='F.PNG')
                                                   <h3> <center>No info </center></h3>
                                                    @endif

                                                    @else

<h3> <center> No info </center></h3>
                                                        @endif
                                    </table>
                                    <br><br>
                                        
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                                    <div id="viewModal0" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Environmental Aspect Information</h2>
                                                </div><br>
                                                <table class="table table-bordered table-striped " style="color:black;border: 2px solid black">
                                                @if(count($envinfo)>0)
                                                    @foreach($envinfo as $c)
                                                    @if($c->infoname=='Significance.PNG')
                                                  <center>  <img src="{{url('storage/envinfo/Significance.PNG')}}"  style="height:270px;width:500px;align:center" alt="No Info"></center>
                                                  @break
                                                    @endif
                                                    @endforeach

                                                    @if($c->infoname!='Significance.PNG')
                                                   <h3> <center> No info <center></h3>
                                                    @endif

                                                    @else

<h3> <center> No info  <center></h3>
                                                        @endif
                                                </table>
                                                <br><br>
                                                    
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                </div>
                        
                       
                                </div>
                            </div>
                        </div>
                                           

                                  
                                        
                                            <div >
                                                <button type="submit" class="btn btn-primary"  style="font-size:13px;margin-left:75%;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed?')">Submit</button>
                                                <button for="reset" type="reset" class="btn btn-primary"style="font-size:13px;">Reset</button>
                                          
                                           
                                        </div>
                                    </div>
                                    </form>
                              
                                    <!-- END Form Horizontal - Default Style -->

                                </div>
                            </div>
                        </div>
                    </div>
                    

               

            <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
            <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">OPTIONS</h3>
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/enviaspects/folder">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                 <br> Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                   
                                

                                       
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/enviaspects/analysis">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                              <br> Analysis
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
       
            <script src="{{ asset( 'js/oneui.core.min.js')}}"></script>
        <script src="{{ asset( 'js/oneui.app.min.js')}}"></script>
        <script src="{{ asset( 'js/plugins/select2/js/select2.full.min.js')}}"></script>
        <script>jQuery(function () { One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);  });</script>
    
          </body>
  
<script type="text/javascript">
    $(document).ready(function() {
    $('#sel').select2({
    });
    });
    
    $(document).ready(function() {    
    $('#sel_freq').select2({
    });
    });
</script>
<script type="text/javascript">   
    var i = 0;
    $("#addc").click(function(){
        ++i;
        
        // $("#dynamicTable").append('<tr><td>{{ Form::text("doc['+i+'][enviaspects]",'',array('class'=>'form-control','placeholder'=>'','required'=>'','rows' => 3 )) }}</td><td>{{ Form::select("doc['+i+'][ER]",array('Emergency'=>'Emergency','Non Emergency'=>'Non Emergency'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][potential]",array('Resource Depletion' => 'Resource Depletion','Air Pollution'=>'Air Pollution','Water Pollution'=>'Water Pollution','Noise and Vibrational Effect'=>'Noise and Vibrational Effect','Land Contamination'=>'Land Contamination','Ozone Depletion'=>'Ozone Depletion'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][A]",array('0.1' => '0.1','1'=>'1','2'=>'2','3'=>'3','4'=>'4'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][B]",array('0.1' => '0.1','1'=>'1','2'=>'2','3'=>'3','4'=>'4'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][C]",array('0.1' => '0.1','1'=>'1','3'=>'3','5'=>'5','10'=>'10'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][D]",array('1' => '1','2'=>'2'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][E]",array('1' => '1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][F]",array('1' => '1','2'=>'2','3'=>'3'),null,array('class'=>'js-select2 form-control'))}}</td><td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Aspect"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');
        $("#dynamicTable").append('<tr><td><input type="text" name="doc['+i+'][enviaspects]"  class="form-control" /></td><td><select name="doc['+i+'][ER]" class="form-control" ><option value="Emergency" >Emergency</option><option value="Non Emergency">Non Emergency</option></select></td><td><select name="doc['+i+'][potential]" class="form-control" ><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="Resource Depletion" >Resource Depletion</option><option value="Air Pollution">Air Pollution</option><option value="Water Pollution">Water Pollution</option><option value="Noise and Vibrational Effect" >Noise and Vibrational Effect</option><option value="Land Contamination" >Land Contamination</option><option value="Ozone Depletion" >Ozone Depletion</option></select></td><td><select name="doc['+i+'][A]" class="form-control" ><option value="1" >1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select></td><td><select name="doc['+i+'][B]" class="form-control" ><option value="0.1" >0.1</option><option value="1" >1</option><option value="3">3</option><option value="5">5</option><option value="10">10</option></select></td><td><select name="doc['+i+'][C]" class="form-control" ><option value="0.1" >0.1</option><option value="1" >1</option><option value="3">3</option><option value="5">5</option><option value="10">10</option></select></td><td><select name="doc['+i+'][D]" class="form-control" ><option value="1" >1</option><option value="2">2</option></select></td><td><select name="doc['+i+'][E]" class="form-control" ><option value="1" >1</option><option value="2">2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option></select></td><td><select name="doc['+i+'][F]" class="form-control" ><option value="1" >1</option><option value="2">2</option><option value="3" >3</option></select></td><td><input type="text" name="doc['+i+'][control]"  class="form-control" /></td><td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Aspect"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');

        // <td><select name="doc['+i+'][R_NR_EM]" class="form-control" ><option value="Routine" >R-Routine</option><option value="Non Routine">NR-Non Routine</option><option value="Emergency">EM-Emergency</option></select></td>
    });
    $(document).on('click', '.remove-tr', function(){  
 $(this).parents('tr').remove();
 });
</script>




<script type="text/javascript">   
    var i = 0;
    $("#add").click(function(){
        ++i;
        
        // $("#dynamicTable").append('<tr><td>{{ Form::text("doc['+i+'][enviaspects]",'',array('class'=>'form-control','placeholder'=>'','required'=>'','rows' => 3 )) }}</td><td>{{ Form::select("doc['+i+'][ER]",array('Emergency'=>'Emergency','Non Emergency'=>'Non Emergency'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][potential]",array('Resource Depletion' => 'Resource Depletion','Air Pollution'=>'Air Pollution','Water Pollution'=>'Water Pollution','Noise and Vibrational Effect'=>'Noise and Vibrational Effect','Land Contamination'=>'Land Contamination','Ozone Depletion'=>'Ozone Depletion'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][A]",array('0.1' => '0.1','1'=>'1','2'=>'2','3'=>'3','4'=>'4'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][B]",array('0.1' => '0.1','1'=>'1','2'=>'2','3'=>'3','4'=>'4'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][C]",array('0.1' => '0.1','1'=>'1','3'=>'3','5'=>'5','10'=>'10'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][D]",array('1' => '1','2'=>'2'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][E]",array('1' => '1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'),null,array('class'=>'js-select2 form-control'))}}</td><td>{{ Form::select("doc['+i+'][F]",array('1' => '1','2'=>'2','3'=>'3'),null,array('class'=>'js-select2 form-control'))}}</td><td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Aspect"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');
        $("#dynamicTable").append('<tr><td><input type="text" name="doc['+i+'][enviaspects]"  style="font-size:13px" class="form-control" /></td><td><select name="doc['+i+'][ER]" class="form-control" style="font-size:13px"><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="Emergency" >Emergency</option><option value="Non Emergency">Non Emergency</option></select></td><td><select name="doc['+i+'][potential]" class="form-control" style="font-size:13px" ><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="Resource Depletion" >Resource Depletion</option><option value="Air Pollution">Air Pollution</option><option value="Water Pollution">Water Pollution</option><option value="Noise and Vibrational Effect" >Noise and Vibrational Effect</option><option value="Land Contamination" >Land Contamination</option><option value="Ozone Depletion" >Ozone Depletion</option></select></td><td><select name="doc['+i+'][A]" class="form-control" style="font-size:13px"><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="0.1" >0.1</option><option value="1" >1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select></td><td><select name="doc['+i+'][B]" class="form-control" style="font-size:13px"><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="0.1" >0.1</option><option value="1" >1</option><option value="3">3</option><option value="5">5</option><option value="10">10</option></select></td><td><select name="doc['+i+'][C]" class="form-control" style="font-size:13px" ><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option></select></td><td><select name="doc['+i+'][D]" class="form-control" style="font-size:13px"><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option></select></td><td><select name="doc['+i+'][E]" class="form-control" style="font-size:13px"><option style="font-size:13px" value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option><option value="3" >3</option></select></td><td><button type="button" name="add" id="add" style="font-size:13px" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Aspect"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');

        // <td><select name="doc['+i+'][R_NR_EM]" class="form-control" ><option value="Routine" >R-Routine</option><option value="Non Routine">NR-Non Routine</option><option value="Emergency">EM-Emergency</option></select></td>
    });
    $(document).on('click', '.remove-tr', function(){  
 $(this).parents('tr').remove();
 });
</script>




<script type="text/javascript">
$(document).ready(function() {

$('#team').select2({
placeholder:'Choose Team ',
});

});

</script>  

</html>
@endsection







