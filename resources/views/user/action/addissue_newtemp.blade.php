@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
  
    <body>
      
                <!-- Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                          
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Audit Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/actionsystem/addissue">Add Issue</a>
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
                <div class="content">
                  

                   <!-- Horizontal -->
                    <div class="block block-rounded">                       
                        <div class="block-content block-content-full">
                            <div class="row">                                
                                <div class="col-lg-8">
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
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="raised">Raised by</label>
                                                <div class="col-sm-8">
                                                    <input type="riskname" class="form-control" id="name" name="name" value="{{Auth::user()->name}}" readonly="" >
                                                </div>
                                            </div>
                                            <?php  $f=0; 
                                                $c=$count;
                                            ?>
                                            <div class="form-group row">
                                                <label  class="col-sm-4 col-form-label" for="category">Category</label>
                                                    <div class="col-sm-8">                                                    
                                                    <select class="form-control" name = "category" required="true" >     
                                                        @if(count($categories)>0)
                                                            @foreach($categories as $cat)
                                                                @if($cat->categoryname != "Corrective Action")
                                                                    <option value=" {{$cat->categoryname}}"> {{$cat->categoryname}}</option></br>
                                                                @endif 
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="department">Department</label>
                                                    <div class="col-sm-8">                                                           
                                                    <select class="form-control" name = "department" required="true">    
                                                        @if(count($departments)>0)
                                                            @foreach($departments as $dep)
                                                                <option value=" {{$dep->departmentname}}"> {{$dep->departmentname}}</option></br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="type">Type</label>
                                                    <div class="col-sm-8">                                                           
                                                    <select class="form-control" name = "type" required="true">    
                                                        @if(count($type)>0)
                                                            @foreach($type as $typ)
                                                                <option value=" {{$typ->name}}"> {{$typ->name}}</option></br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                               
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="title">Title</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title.." value="{{ old('title') }}" >
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="observation">Observation</label>
                                                <div class="col-sm-8">
                                                    <textarea type="text" class="form-control" id="observation" name="observation" placeholder="Enter observation" value="{{ old('observation') }}"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="agreedaction">Recommended action</label>
                                                <div class="col-sm-8">
                                                    <textarea type="text" class="form-control" id="agreedaction" name="agreedaction" placeholder="Enter Recommended Action" value="{{ old('agreedaction') }}"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="issue_file">Attach issue related files</label>
                                                <div class="col-sm-8">
                                                    <input type="file" class="form-control" id="issue_file"  name="issue_file[]"  multiple>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="responsibility">Responsibility</label>
                                                    <div class="col-sm-8">                                                           
                                                    <select class="form-control" name = "responsibility" required="true">    
                                                        @if(count($user)> 0 )
                                                            @foreach($user as $use)
                                                                <option    value=" {{$use->email}}"> {{ $use->name }}</option><br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row"> 
                                                <label class="col-sm-4 col-form-label" for="frequency">Email reminder for action:</label>
                                                <div class="col-sm-8"> 
                                                    <select class="form-control"  id="frequency" name = "frequency[]" >
                                                        <option value=" Monthly"> Monthly</option><br>
                                                        <option value=" Weekly"> Weekly</option><br>
                                                        <option value=" 2 Days Before"> 2 Days Before</option><br>
                                                    </select>
                                                </div>
                                            </div>
                
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="notification">Send notifications to:</label>
                                                <div class="col-sm-8">   
                                                    <select class="js-select2 form-control" id="example-select2-multiple" name="notification[]" style="width: 100%;" data-placeholder="Choose many.." multiple>
                                                        @if(count($user)> 0 )
                                                            @foreach($user as $use)
                                                                <option value=" {{$use->email}}"  > {{ $use->name }}</option><br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                          
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="date">Due date</label>
                                                <div class="col-sm-8">
                                                    <input type="date" class="js-datepicker form-control" id="date" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-8 ml-auto">
                                                    <button type="submit" class="btn btn-primary"  onclick="return confirm('Do you want to proceed ? ')">ADD</button>
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
                                                <i class="si si-speedometer fa-2x text-primary"></i>
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
                                                <i class="si si-rocket fa-2x text-primary"></i>
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
                                                <i class="si si-plane fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    All Closed Issues
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/openissue">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="si si-plane fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    My Open Issues
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
       
    </body>
  
<script type="text/javascript">
    $(document).ready(function() {
    $('#sel').select2({
    });
    });
    // $(document).ready(function() {    
    // $('#sel_not').select2({
    // });
    // });
    $(document).ready(function() {    
    $('#sel_freq').select2({
    });
    });
</script>
    

</html>
@endsection
