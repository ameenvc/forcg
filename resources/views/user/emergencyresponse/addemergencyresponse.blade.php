@extends('layouts.multiselect')
@section('content')

<style>
   .agenda_style{
       margin:10px; 
   }
</style>
    <!-- Header -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Emergency Response</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Add Emergency Response</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('emergency.register') }}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Header -->

    <!-- Page Content -->
    <div class="content">

        <!-- Horizontal -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div class="row">
                   <div class="col-md-12">
                        <label class="col-sm-12 col-form-label text-right" style="font-size:13px;">All fields marked with <span class="text-danger">*</span> are mandatory</label>
                     </div>

                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form name="form" class="mb-5" action="{{route('emergency.insertemergencydetails')}}" method="POST">
                            {{csrf_field()}}

                            @if(count($errors) >0 )
                            @foreach($errors->all() as $error)
                            <div class='alert alert-warning'>
                             <a class="close" data-dismiss="alert">×</a>
                                {{ $error }}
                            </div>
                            @endforeach
                            @endif

                            @if(Session::has('msg'))
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                                                

                        <div class="col-lg-10">     

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Department <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                    @if(count($departments)>0)
                                    <select class="form-control" name="department" id="department" style="font-size: 13px;" required>
                                    <option value="">Choose a Department</option>
                                        @foreach($departments as $dept)
                                        <option value="{{$dept->id}}">{{$dept->departmentname}}</option>
                                        @endforeach
                                    </select>
                                    @endif

                                </div>
                            </div>
                            
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="locations" style="font-size:13px;">Choose Location</label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="location" id="location"  style="font-size: 13px;"  data-placeholder="Choose Location">
                                       <option value="">Choose Location</option>
                                        @foreach($locations as $location)
                                           <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach                                     
                                    </select>
                                </div>
                            </div>               

                            <div class="form-group" style="display:inline-block;width:95%;">
                                <label class="col-sm-12 col-form-label" for="situation" style="font-size:13px;">Emergency Situation<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                  <?php 
                                    $m = 0; 
                                    $items_per_row = 4; 
                                  ?>
                                    @foreach($emergencies as $emergency)                       
                                    <button type="button" class="btn btn-sm btn-outline-primary"  style="margin-right:4px;margin-left:10px;margin-bottom:10px;" onclick="return getdetails('{{$emergency->id}}');">{{$emergency->name}}</button>
                                    <?php 
                                        $m++; 
                                        if($m % $items_per_row == 0){ ?><br><?php } ?>                                    
                                    @endforeach
                                </div>
                                <ul class="text-danger" id="datanotfetched" style="padding-top:5px;font-size:13px;display:none;"></ul>
                                <div class="col-sm-6" style="padding-top:15px;">
                                    <input type="text" class="form-control" id="situation_name" name="situation_name"   style="font-size:13px;" required placeholder="Click on any Emergency situation above"/>
                                </div>
                                <div class="col-sm-12" style="padding-top:15px;">
                                    <textarea  rows="3" class="form-control" id="description" name="description"   style="font-size:13px;" required></textarea>
                                </div>
                                <!-- <input type="hidden" name="emergency_id" id="emergency_id" value=""/> -->
                            </div>                           

                            <div class="form-group"  style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Frequency<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name = "frequency" id="frequency" required  style="font-size: 13px;" data-placeholder="Choose Frequency">
                                      <option value="">Choose Frequency</option>
                                        @foreach($frequencies as $frequency)
                                           <option value="{{$frequency}}">{{$frequency}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                    

                             <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="drillteam" style="font-size:13px;">Drill Team<span class="text-danger">*</span></label>
                                <!-- <div class="col-sm-12"> -->
                                <div class="col-xs-12 col-sm-11">
                                <select class="js-select2 form-control" name="drill_team[]" id="drill_team" required style="font-size:13px;" multiple data-placeholder="Choose Drill Team">
                                        @foreach($empdetails as $empdetail)
                                           <option value="{{$empdetail->id}}">{{$empdetail->emp_name}}</option>
                                        @endforeach
                                        <!-- <option value="other">Others</option> -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="last_drill" style="font-size:13px;">Date Of Last Drill</label>
                                <div class="col-sm-12">
                                    <input type="date" style="font-size:13px;" class="js-datepicker form-control" id="last_drill" name="last_drill" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="">
                                </div>
                            </div>
                            
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Next Drill date<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" style="font-size:13px;" class="js-datepicker form-control" id="next_drill" name="next_drill" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="" required>
                                </div>
                            </div>      
                       
                          <div style="margin-top:50px;margin-bottom:50px;">
                            <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;margin-left:550px;" onclick="return validateform();">Submit</button>
                          </div>
                        </div> 
                        </form>
                        <!-- END Form Horizontal - Default Style -->

                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->
@include('user.emergencyresponse.header_options')      
<script>
        let CSRF_TOKEN = "";
                window.onload = function() {
                    CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                }

        function getdetails(id)
        { 
            $.ajax({
                    data: {
                            "data_id": id,
                            _token: CSRF_TOKEN
                        }          
                    , url: "{{ route('emergency.displayemergencysituationsajax')}}"
                    , type: "POST"
                    , success:function(response) {
                        if(response.status == 200)
                         {
                            document.getElementById('situation_name').value=response.situation_name;
                            document.getElementById('description').value=response.description;
                            document.getElementById('emergency_id').value=response.emergency_id;                                
                         }
                        
                        }
                    ,error: function(err) {
                             if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                              });
                            });                               
                                $('#datanotfetched').html(error_text);
                                $('#datanotfetched').css('display', 'block');
                            }            
                        }

                    });
        }       
    </script>
  



@endsection