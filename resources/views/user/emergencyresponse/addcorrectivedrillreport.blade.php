@extends('layouts.multiselect')

@section('content')

      <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Emergency Response</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Add Corrective Actions </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="{{route('emergency.viewdrillreport',['id'=>$datas->emergency_details_id])}}">Back</a>
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
                                    <form class="mb-5" action="{{route('emergency.insertcorrectivedrillreport')}}" enctype="multipart/form-data"  method="post" files="true" >
                                        {{csrf_field()}}
                                       
                                        @if(count($errors) >0 )
                                            @foreach($errors->all() as $error)
                                                <div class='alert alert-warning'>
                                                        {{ $error }}
                                                </div>
                                            @endforeach
                                        @endif	
                                        @if(Session::has('messages'))
                                            <div class="alert alert-primary">
                                                <a class="close" data-dismiss="alert">×</a>
                                                {!!Session::get('messages')!!}
                                            </div>
                                        @endif
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;" >Raised by</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="raisedby" name="raisedby" style="font-size:13px;"  value="{{Auth::user()->name}}" readonly="" >
                                                </div>
                                            </div>
                                            
                                           
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="category">Category</label>
                                                    <div class="col-sm-12">                                                    
                                                     <input type="text" class="form-control" name="category" id="category" style="font-size:13px;" value="Emergency Drill" readonly>
                                                </div>
                                            </div> 
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="category">Drill Number</label>
                                                    <div class="col-sm-12">                                                    
                                                     <input type="text" class="form-control" name="drill_number" id="drill_number" style="font-size:13px;" value="ED#{{$datas->report_number}}" readonly>
                                                </div>
                                            </div> 
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="category">Drill Type</label>
                                                    <div class="col-sm-12">                                                    
                                                     <input type="text" class="form-control" name="drill_type" id="drill_type" style="font-size:13px;" value="{{$datas->drill_type}}" readonly>
                                                </div>
                                            </div> 
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department <span class="text-danger">*</span> </label>
                                                    <div class="col-sm-12">                                                           
                                                    <select class="form-control" name = "department" id="department" style="font-size:13px;" required> 
                                                        <option value="">Choose a Department</option>   
                                                        @if(count($departments)>0)
                                                            @foreach($departments as $dep)
                                                                <option value="{{$dep->id}}" style="font-size:13px;"> {{$dep->departmentname}}</option></br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type <span class="text-danger">*</span> </label>
                                                    <div class="col-sm-12">                                                           
                                                    <select class="form-control" name = "type" id="type"  style="font-size:13px;" required> 
                                                        <option value="">Choose Type</option>    
                                                        @if(count($types)>0)
                                                            @foreach($types as $type)
                                                                <option value="{{$type->name}}" style="font-size:13px;"> {{$type->name}}</option></br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                               
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title <span class="text-danger">*</span> </label>
                                                <div class="col-sm-12">		
                                                  <input type="text"style="font-size: 13px;" class="form-control" id="title" name="title" value="" required>	
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="responsibility" style="font-size:13px;">Responsibility <span class="text-danger">*</span> </label>
                                                    <div class="col-sm-12">                                                           
                                                    <select class="form-control" name = "responsibility" id = "responsibility" style="font-size:13px;" required>    
                                                         <option value="">Choose Responsibility</option>
                                                        @if(count($users)> 0 )
                                                            @foreach($users as $user)
                                                                <option value="{{$user->email}}" style="font-size:13px;"> {{ $user->name }}</option><br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group" style="display:inline-block;width:98.5%;">
                                                <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation <span class="text-danger">*</span> </label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" style="font-size:13px;" id="observation" name="observation" placeholder="Enter observation" value="{{ old('observation') }}" required></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:98.5%;">
                                                <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended action <span class="text-danger">*</span> </label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="agreedaction" style="font-size:13px;" name="agreedaction" placeholder="Enter recommended action" value="{{ old('agreedaction') }}" required></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:98.5%;">
                                                <label class="col-sm-12 col-form-label" for="issue_file" style="font-size:13px;">Attach issue related files(avoid '#' and '&' in file names)</label>
                                                <div class="col-sm-12">
                                                    <input type="file" class="form-control" id="issue_file" style="font-size:13px;"  name="issue_file[]"  multiple>
                                                </div>
                                            </div>
                                            
 
                                            <div class="form-group" style="display:inline-block;width:49%;"> 
                                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Email reminder for action:</label>
                                                <div class="col-sm-12">   
                                                    <select class="js-select2 form-control" style="font-size:13px;"  id="frequency" name = "frequency[]" style="width: 100%;" data-placeholder="Choose frequencies" multiple>
                                                        <option value=" Monthly" style="font-size:13px;"> Monthly</option><br>
                                                        <option value=" Weekly" style="font-size:13px;"> Weekly</option><br>
                                                        <option value=" 2 Days Before" style="font-size:13px;"> 2 days before</option><br>
                                                    </select>
                                                </div>
                                            </div>
                
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="notification" style="font-size:13px;">Send notifications to:</label>
                                                <div class="col-sm-12">   
                                                    <select class="js-select2 form-control" style="font-size:13px;" id="example-select2-multiple" name="notification[]" style="width: 100%;" data-placeholder="Choose Persons" multiple>
                                                        @if(count($users)> 0 )
                                                            @foreach($users as $user)
                                                                <option value="{{$user->email}}" style="font-size:13px;"> {{ $user->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                          
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Due date <span class="text-danger">*</span> </label>
                                                <div class="col-sm-12">
                                                    <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="duedate" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" required>
                                                </div>
                                            </div>

                                              <input type="hidden" name="posted" value="{{Auth::user()->email}}"  > 
                                              <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}"> 
                                              <input type="hidden" id="drill_report_id" name="drill_report_id" value="{{$datas->id}}">
                                              <input type="hidden" id="main_emergency_details_id" name="main_emergency_details_id" value="{{$datas->emergency_details_id}}">

                                            <div class="form-group">
                                                <div class="col-sm-8 ml-auto">
                                                    <button type="submit" class="btn btn-primary"  onclick="return confirmsubmit()" style="font-size:13px; margin-left:86%; ">Add</button>
                                                </div>
                                           
										   </div>
										   <!-- END Form Horizontal - Default Style -->
                                    </form>
                                   
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Horizontal -->
                    </main>
            <!-- END Main Container -->

            @include('user.managementreview.header_options')  


<script>
    function confirmsubmit(){
        if($("#issue_file").val()=='')
        {   mess2='Are you sure to submit without attachment?';
            y=confirm(mess2);
            if(y==true)
            { }
            else
            {return false;}
        }
        else{
            mess1= "Are you sure?";
            x= confirm(mess1);
            if(x==true)
            { }
            else
            {return false;}
        }
    }
    </script>
                      
   @endsection