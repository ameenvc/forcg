@extends('layouts.multiselect')
@section('content')
<style>
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    } 
</style>
<div class="content" style="width:100%;">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="{{route('management.meetings')}}" class="text-black">
                        Management Review
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Add Action Plans
                </li>
            </ol>
        </nav>                       
        <div class="block-content block-content-full">
            <div class="row">                                
                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->                                   
                    <form class="mb-5" action="{{route('management.insertcorrective')}}" enctype="multipart/form-data"  method="post" files="true" >
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
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;" >Raised by</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="raisedby" name="raisedby" style="font-size:13px;"  value="{{Auth::user()->name}}" readonly="" >
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="category">Category</label>
                                    <div class="col-sm-12">                                                    
                                        <input type="text" class="form-control" name="category" id="category" style="font-size:13px;" value="Management Review" readonly>
                                </div>
                            </div> 
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department <span class="text-danger">*</span> </label>
                                    <div class="col-sm-12">                                                           
                                    <select class="form-control js-select2" name = "department" id="department" style="font-size:13px;" required onchange="chooseDivision($(this).val())"> 
                                        <option value="" selected disabled>Choose a Department</option>   
                                        @if(count($departments)>0)
                                            @foreach($departments as $dep)
                                                <option value="{{$dep->id}}" style="font-size:13px;"> {{$dep->departmentname}}</option></br>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @php 
                                $divisions = App\Division::where('department_id',old('department'))->get();
                                $areas = App\Area::where('division_id',old('division'))->get();
                            @endphp
                            <div class="form-group {{count($divisions)>0?'': 'd-none'}} col-sm-4" id="division">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                                        <option value="" selected>Choose Division</option></br>
                                        @if(count($divisions)>0)
                                        @foreach($divisions as $division)
                                        <option value="{{$division->id}}" {{$division->id==old('division')?'selected':''}}>{{$division->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group {{count($areas)>0?'': 'd-none'}} col-sm-4" id="area">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    <select class="form-control js-select2" id="area_select" name="area" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                        <option value="" selected>Choose Sub Division</option></br>
                                        @if(count($areas)>0)
                                        @foreach($areas as $area)
                                        <option value="{{$area->id}}" {{$area->id==old('area')?'selected':''}}>{{$area->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type <span class="text-danger">*</span> </label>
                                    <div class="col-sm-12">                                                           
                                    <select class="form-control js-select2" name = "type" id="type"  style="font-size:13px;" required> 
                                        <option value="" selected disabled>Choose Type</option>    
                                        @if(count($types)>0)
                                            @foreach($types as $type)
                                                <option value="{{$type->order}}" style="font-size:13px;"> {{$type->name}}</option></br>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title <span class="text-danger">*</span> </label>
                                <div class="col-sm-12">		
                                    <input type="text"style="font-size: 13px;" class="form-control" id="title" name="title" value="" placeholder="Enter Title" required>	
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="responsibility" style="font-size:13px;">Responsibility <span class="text-danger">*</span> </label>
                                    <div class="col-sm-12">                                                           
                                    <select class="form-control js-select2" name = "responsibility" id = "responsibility" style="font-size:13px;" required>    
                                            <option value="" selected disabled>Choose Responsibility</option>
                                        @if(count($users)> 0 )
                                            @foreach($users as $user)
                                                <option value="{{$user->email}}" style="font-size:13px;"> {{ $user->name }}</option><br>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation <span class="text-danger">*</span> </label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" style="font-size:13px;" id="observation" name="observation" placeholder="Enter observation" value="{{ old('observation') }}" required></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended action <span class="text-danger">*</span> </label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="agreedaction" style="font-size:13px;" name="agreedaction" placeholder="Enter recommended action" value="{{ old('agreedaction') }}" required></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="issue_file" style="font-size:13px;">Attach issue related files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="issue_file" style="font-size:13px;"  name="issue_file[]"  multiple>
                                </div>
                            </div>
                            <div class="form-group col-sm-4"> 
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Email reminder for action:</label>
                                <div class="col-sm-12">   
                                    <select class="js-select2 form-control" style="font-size:13px;"  id="frequency" name = "frequency[]" style="width: 100%;" data-placeholder="Choose frequencies" multiple>
                                        <option value=" Monthly" style="font-size:13px;"> Monthly</option><br>
                                        <option value=" Weekly" style="font-size:13px;"> Weekly</option><br>
                                        <option value=" 2 Days Before" style="font-size:13px;"> 2 days before</option><br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
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
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Due date <span class="text-danger">*</span> </label>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="duedate" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Does this issue require further root cause analysis? <span class="text-danger">*</span> 
                                    <input type="radio" style="font-size:11px;margin-left:10px;" name="is_rc_analysis" value="yes" required><span style="font-size:13px;margin-left:4px;">Yes</span>
                                    <input type="radio" style="font-size:11px;margin-left:10px;" name="is_rc_analysis" value="no" required><span style="font-size:13px;margin-left:4px;">No</span>
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="posted" value="{{Auth::user()->email}}"  > 
                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}"> 
                        <input type="hidden" id="review_schedule_id" name="review_schedule_id" value="{{$datas->id}}">

                        <div class="row">
                            <div class="col-sm-12 form-group text-right pr-4">
                                <button type="submit" class="btn btn-primary"  onclick="return confirmsubmit()" style="font-size:13px;">Add</button>
                            </div>
                        
                        </div>
                        <!-- END Form Horizontal - Default Style -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->
</div>
@include('user.managementreview.header_options')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    function confirmsubmit(){
        if($("#issue_file").val()=='')
        {   
            y = confirm('Are you sure to submit without attachment?');
            if(y==true){ }
            else{return false;}
        }
        else{
            x= confirm("Are you sure?");
            if(x==true){ }
            else{return false;}
        }
    }
    function chooseDivision(id) {
        $('#division').addClass('d-none');
        $('#area').addClass('d-none');
        let the_url = '{{ route("admin.departmentDivisions.getDivisions", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , success: function(resp) {
                if (resp.data.length) {
                    $('#division').removeClass('d-none');
                    $('#division').css('display', 'inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
                    }
                    $('#division_select').empty().append('<option value="">Choose Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });

    }

    function chooseArea(id) {
        $('#area').addClass('d-none');
        // $('#area_select').removeAttr('required');
        let the_url = '{{ route("admin.departmentDivisions.getAreasOfADivision", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , success: function(resp) {
                if (resp.data.length) {
                    $('#area').removeClass('d-none');
                    $('#area').css('display', 'inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
                    }
                    // $('#area_select').attr('required',true);
                    $('#area_select').empty().append('<option value="">Choose Sub Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });
    }
</script>                      
@endsection
