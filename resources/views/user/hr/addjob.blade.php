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
                                    <li class="breadcrumb-item">HR Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Add Job Description</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/hrjmgmt">Back</a>
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
                                    <form name="form" class="mb-5" action="{{url('/user/hr/jobsubmit')}}" method="POST">
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
                                    <p class="font-size-sm text-muted" style="font-weight:bold;font-size: 13px;">Basic Details</p>
                                         <div class="form-group" style="display:inline-block;width:49%;">
                                             <label class="col-sm-12 col-form-label" for="pos_title" style="font-size:13px;">Position Title <span class="text-danger">*</span></label>
                                              <div class="col-sm-12">
                                                <input type="text" style="font-size:13px;" class="form-control" id="pos_title" name="pos_title" placeholder="Enter Position Title" required>
                                              </div>
                                         </div>
                                         <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="report" style="font-size:13px;">Reports To <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                            <input type="text"style="font-size: 13px;" class="form-control" id="report" name="report" placeholder="Enter Reports To" required>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="dept"  style="font-size:13px;">Departments <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                            @if(count($departments)>0)
                                                                    <select class="form-control" name="dept" id="dept" required  style="font-size: 13px;" >
                                                                        <option value="" selected="" disabled>Choose a Department</option>
                                                                        @foreach($departments as $type)
                                                                            <option value="{{$type->id}}">{{$type->departmentname}}</option>
                                                                        @endforeach
                                                                    </select>
                                                    @endif
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="code"  style="font-size:13px;">Job Code <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                            @if($count==0)
                                              <input type="number"style="font-size: 13px;" class="form-control" id="code" name="code" required placeholder="Enter Job Code">
                                           @else
                                              <input type="text"style="font-size: 13px;" class="form-control" id="code" name="code" value="{{$count}}" readonly required>
                                           @endif
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:98.5%;">
                                            <label class="col-sm-12 col-form-label" for="respo"  style="font-size:13px;">Responsibilities <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                            <textarea rows="20" type="text"style="font-size: 13px;" class="form-control" id="respo" name="respo" placeholder="Enter Responsibilities" required></textarea>
                                             </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:98.5%;">
                                            <label class="col-sm-12 col-form-label" for="auth"  style="font-size:13px;">Job Authorities <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                            <textarea rows="10" type="text"style="font-size: 13px;" class="form-control" id="auth" name="auth" placeholder="Enter Authorities" required></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:98.5%;">
                                            <label class="col-sm-12 col-form-label" for="account"  style="font-size:13px;">Accountabilities <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                            <textarea rows="10" type="text"style="font-size: 13px;" class="form-control" id="account" name="account" placeholder="Enter Accountabilities" required></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:98.5%;">
                                         <label class="col-sm-12 col-form-label" for="hse"  style="font-size:13px;">HSE General Responsibilities <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                            <textarea rows="20" type="text"style="font-size: 13px;" class="form-control" id="hse" name="hse" placeholder="Enter HSE General Responsibilities" required></textarea>
                                            </div>
                                       </div>
                                       <p class="font-size-sm text-muted" style="font-weight:bold;font-size: 15px;">Communications & Working Relationships</p>
                                       <div class="form-group" style="display:inline-block;width:49%;">
                                         <label class="col-sm-12 col-form-label" for="internal"  style="font-size:13px;">Internal  <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                              <input type="text"style="font-size: 13px;" class="form-control" id="internal" name="internal"  required>
                                            </div>
                                       </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="external"  style="font-size:13px;">External <span class="text-danger">*</span></label>
                                            <div class="col-sm-12" >
                                                  <input type="text"style="font-size: 13px;" class="form-control" id="external" name="external"  required>
                                            </div>
                                        </div>
                                        <p class="font-size-sm text-muted" style="font-weight:bold;font-size: 15px;">Qualifications, Experience, & Skills</p>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="qual"  style="font-size:13px;">Minimum Qualifications <span class="text-danger">*</span></label>
                                                <div class="col-sm-12">
                                                   <input type="text"style="font-size: 13px;" class="form-control" id="qual" name="qual"  required>
                                          </div>
                                    </div>


                                    <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="skills"  style="font-size:13px;">Training / Skills <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                  <input type="text"style="font-size: 13px;" class="form-control" id="skills" name="skills"  required>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="exp"style="font-size:13px;">Minimum Experience <span class="text-danger">*</span></label>
                                              <div class="col-sm-12">
                                                 <input type="text"style="font-size: 13px;" class="form-control" id="exp" name="exp"  required>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="prpared_on"  style="font-size:13px;">Prepared / Revised on</label>
                                            <div class="col-sm-12">
                                                 <input type="date" style="font-size:13px;"class="js-datepicker form-control" id="prepared_on" name="prepared_on" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="<?php echo date("Y-m-d");?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-8 ml-auto">
                                                <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:440px;"  >Submit</button>
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
                                   
                                    <div class="col-6">                    
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/employeelist">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Employee List
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

    </body>


</html>
@endsection
