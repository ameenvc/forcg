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
                                    <li class="breadcrumb-item">Maintenance Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Mechanical</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Add</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/maintenancemgmt">Back</a>
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
                                    <!-- Form Horizontal - Default Style -->
                                    <form class="mb-5" action="{{url('/user/maintenance/mechsubmit')}}" method="POST">
                                        {{csrf_field()}}
                                            
                                            
                                                
                                       
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" style="font-size: 13px;"for="desc">Equipment description</label>
                                            <div class="col-sm-8">
                                                <textarea type="text" style="font-size: 13px;"class="form-control" id="desc" name="desc" placeholder="Enter description"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row"> 
                                            <label class="col-sm-4 col-form-label"style="font-size: 13px;" for="location">Location/area</label>
                                                <div class="col-sm-8"> 
                                                    <input type="text"style="font-size: 13px;" class="form-control" id="location" name="location" placeholder="Enter..">
                                            
                                          </div>
                                    </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label"style="font-size: 13px;" for="serial">Serial number</label>
                                            <div class="col-sm-8">
                                                <input type="text"style="font-size: 13px;" class="form-control" id="serial" name="serial" placeholder="Enter..">
                                            </div>
                                        </div>
                                            
                                    <div class="form-group  row" >
                                        <label class="col-sm-4 col-form-label"class="form-control"style="font-size: 13px;"  for="make">Make/model</label>
                                        <div class="col-sm-8" id="make"style="font-size: 13px;" name="make">
                                            <input type="text"style="font-size: 13px;" class="form-control" id="make" name="make" placeholder="Enter">
                                        </div>
                                    </div>
                                    <div class="form-group  row" >
                                        <label class="col-sm-4 col-form-label"class="form-control"style="font-size: 13px;"  for="freq">Preventive maintenance frequency</label>
                                        <div class="col-sm-8" id="freq"style="font-size: 13px;" name="freq">
                                            <textarea type="text"style="font-size: 13px;" class="form-control" id="freq" name="freq" placeholder="Enter"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="pmby"style="font-size: 13px;">PM by</label>
                                            <div class="col-sm-8">
                                                <div class="form-group"style="color:#666666">
                                                    <select class="js-select2 form-control"style="font-size: 13px;"required="true" id="pmby" name="pmby" style="width: 100%;" data-placeholder="Choose one..">
                                                        <option selected="" disabled>Choose Name</option>
                                                        @if(count($users)> 0 )
                                                            @foreach($users as $value)
                                                                <option value=" {{$value->name}}"> {{ $value->name }}</option></br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="lastdate"style="font-size: 13px;">Last PM date</label>
                                                <div class="col-sm-8">
                                                    <input type="date"style="font-size: 13px;" class="js-datepicker form-control"required="true" id="lastdate" name="lastdate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="schedule"style="font-size: 13px;">Next PM schedule</label>
                                                <div class="col-sm-8">
                                                    <input type="date"style="font-size: 13px;" class="js-datepicker form-control"required="true" id="schedule" name="schedule" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                                </div>
                                        </div>

                                   <div class="form-group row">
                                            <label class="col-sm-4 col-form-label"style="font-size: 13px;" for="condition">Equipment condition</label>
                                            <div class="col-sm-8"> 
                                               <select class="form-control" style="font-size: 13px;" id="condition" name = "condition" placeholder="Choose Equipment Condition">
                                                    <option value="Excellent">Excellent</option><br>
                                                    <option value="Good">Good</option><br>
                                                    <option value="Satisfactory">Satisfactory</option><br>
                                                    <option value="Poor performance">Poor performance</option><br>
                                                    <option value="Damaged">Damaged</option><br>
                                                
                                                </select>
                                            </div>
                                        </div>
                        
                                       
                                        
                                        <div style="text-align:center;">
                                            <button type="submit" for="submit" class="btn btn-primary"style="font-size:13px;margin-left:78%;" data-toggle="click-ripple" onclick="return confirm('Are you sure to submit the request?')">Submit</button>
                                            <button for="reset" type="reset" class="btn btn-primary"style="font-size:13px;">Reset</button>
                                        </div>
                                    </form>
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/maintenance/total">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Total Equipment List
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/pmplan">
                                            <div class="block-content text-center"style="height:130px">
                                                <i class="fas fa-address-card fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  PM Plan and Report
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Payments -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/testplan">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Test Plan
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Payments -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/maintenance/breakfolder">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Breakdown Maintenance
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
    
</html>
@endsection
