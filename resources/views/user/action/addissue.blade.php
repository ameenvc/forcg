@extends('layouts.multiselect')

@section('content')

{{-- <main id="main-container"> --}}
      <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Audit Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Add</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/auditmgmt">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

        
                <!-- END Hero -->
               
                 <!-- Page Content -->
                 <div class="content" style="">
                   <!-- Horizontal -->
                    <div class="block block-rounded">                       
                        <div class="block-content block-content-full">
                        <div class="row">                                
                                <div class="col-lg-10">
                                    <!-- Form Horizontal - Default Style -->
                                    <?php $f=0; ?>
                                    @foreach($auditors as $auditor)  <?php //dd($auditors); dd(Auth::user()->email); ?>
                                        @if(Auth::user()->email == $auditor) 
                                        <?php $f=1; ?>
                                        @break;
                                        @else
                                            <?php $f=0; ?>
                                        @endif
                                    <?php //break; ?> 
                                    @endforeach
                                    @if($f==1)
                                        <form class="mb-5" action="{{url('/user/actionsystem/insert')}}" enctype="multipart/form-data"  method="POST" files="true">
                                        {{csrf_field()}}
                                            <input type="hidden" name="posted" value="{{Auth::user()->email}}"  > 
                                            <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}"  > 
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
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-4 col-form-label" for="raised" style="font-size:13px;">Raised by</label>
                                                <div class="col-sm-12">
                                                    <input type="riskname" class="form-control" id="name" style="font-size:13px;" name="name" value="{{Auth::user()->name}}" readonly="" >
                                                </div>
                                            </div>
                                            <?php  $f=0; 
                                                $c=$count;
                                            ?>
                                            {{-- <div class="form-group row">
                                                <label  class="col-sm-4 col-form-label" style="font-size:13px;" for="category">Category</label>
                                                    <div class="col-sm-8">                                                    
                                                    <select class="form-control" name = "category" required="true" style="font-size:13px;">     
                                                        @if(count($categories)>0)
                                                            @foreach($categories as $cat)
                                                                @if($cat->categoryname != "Corrective Action")
                                                                    <option value=" {{$cat->categoryname}}"> {{$cat->categoryname}}</option></br>
                                                                @endif 
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div> --}}
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-4 col-form-label" for="category" style="font-size:13px;">Category</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="category" style="font-size:13px;" name="category" value="Internal Audit" readonly >
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-4 col-form-label" for="department" style="font-size:13px;">Department</label>
                                                    <div class="col-sm-12">                                                           
                                                    <select class="form-control" name = "department" required="true" style="font-size:13px;" placeholder="Choose a Department">    
                                                        <option selected="" disabled>Select department</option>
                                                        @if(count($departments)>0)
                                                            @foreach($departments as $dep)
                                                                <option value=" {{$dep->id}}"> {{$dep->departmentname}}</option></br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-4 col-form-label" for="type" style="font-size:13px;">Type</label>
                                                    <div class="col-sm-12">                                                           
                                                    <select class="form-control" name = "type" required="true" style="font-size:13px;" placeholder="Choose a Type">    
                                                        <option selected="" disabled>Select type</option>
                                                        @if(count($type)>0)
                                                            @foreach($type as $typ)
                                                                <option value=" {{$typ->name}}"> {{$typ->name}}</option></br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                               
                                            <div class="form-group" style="width:49%;display:inline-block;">
                                                <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Title</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" style="font-size:13px;" id="title" name="title" placeholder="Enter .." value="{{ old('title') }}" >
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-4 col-form-label" style="font-size:13px;" for="responsibility">Responsibility</label>
                                                    <div class="col-sm-12">                                                           
                                                    <select class="form-control" name = "responsibility" required="true" style="font-size:13px;">    
                                                        <option selected="" disabled>Choose Responsibility</option>
                                                        @if(count($user)> 0 )
                                                            @foreach($user as $use)
                                                                <option    value=" {{$use->email}}"> {{ $use->name }}</option><br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group" style="display:inline-block;width:99%;">
                                                <label class="col-sm-4 col-form-label" style="font-size:13px;" for="observation">Observation</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" style="font-size:13px;" id="observation" name="observation" placeholder="Enter .." value="{{ old('observation') }}"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:99%;">
                                                <label class="col-sm-4 col-form-label"  style="font-size:13px;" for="agreedaction">Recommended action</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" style="font-size:13px;" id="agreedaction" name="agreedaction" placeholder="Enter ..." value="{{ old('agreedaction') }}"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="issue_file">Attach issue related files</label>
                                                <div class="col-sm-12">
                                                    <input type="file" class="form-control" style="font-size:13px;" id="issue_file"  name="issue_file[]"  multiple>
                                                </div>
                                            </div>
                                            <!--<div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-4 col-form-label" style="font-size:13px;" for="responsibility">Responsibility</label>
                                                    <div class="col-sm-12">                                                           
                                                    <select class="form-control" name = "responsibility" required="true" style="font-size:13px;">    
                                                        @if(count($user)> 0 )
                                                            @foreach($user as $use)
                                                                <option    value=" {{$use->email}}"> {{ $use->name }}</option><br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>-->
                                            
                                            <div class="form-group" style="display:inline-block;width:49%;"> 
                                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Email reminder for action</label>
                                                <div class="col-sm-12"> 
                                                    <select class="js-select2 form-control"  id="frequency" name = "frequency[]" style="font-size:13px;" multiple>
                                                        <option></option>
                                                        <option value=" Monthly"> Monthly</option><br>
                                                        <option value=" Weekly"> Weekly</option><br>
                                                        <option value=" 2 Days Before"> 2 Days Before</option><br>
                                                    </select>
                                                </div>
                                            </div>
                                           
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="notification" style="font-size:13px;" >Send notifications to:</label>
                                                <div class="col-sm-12">   
                                                    <select class="js-select2 form-control" id="example-select2-multiple" style="font-size:13px;" name="notification[]"  multiple>
                                                        <option></option>
                                                        @if(count($user)> 0 )
                                                            @foreach($user as $use)
                                                                <option value=" {{$use->email}}" style="font-size:13px;" > {{ $use->name }}</option><br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            
                                         
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-4 col-form-label" for="date" style="font-size:13px;">Due date</label>
                                                <div class="col-sm-12">
                                                    <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="date" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group" style="display:inline-block;">
                                                <div class="col-sm-8 ml-auto">
                                                    <button type="submit" class="btn btn-primary"  onclick="return confirm('Do you want to proceed ?')" style="font-size:13px;margin-left:438px;">Add</button>
                                                </div>
                                            </div>
                                    </form>
                                    @elseif($f==0)
                                    <div class="row justify-content-center">
                                        <h2>User Denied </h2>
                                    </div>
                                @endif
                                    <!-- END Form Horizontal - Default Style -->

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Horizontal -->
                    <!-- END Page Content -->
                    </main>
            <!-- END Main Container -->

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
                                <!-- <div class="col-6">
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/actionsystem/addissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Add Issue
                                            </p>
                                        </div>
                                    </a>
                                    </div> -->
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/auditmgmt">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/folders">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-check-square fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Closed Issues
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/openissue">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    My Open NCRs
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>                                      
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/actionsystem/analysis">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>                                      
                                   
                                   
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Audit Schedule Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
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
   @endsection
