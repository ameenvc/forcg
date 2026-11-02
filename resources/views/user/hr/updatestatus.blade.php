@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
<head>

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
                                        <a class="link-fx" href="/user/hr/trainingregister">Back</a>
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
                                    <form name="form" class="mb-5" action="{{url('/user/hr/sendstatus')}}" method="POST">
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
                                             <label class="col-sm-12 col-form-label" for="empname" style="font-size:13px;">Employee Name</label>
                                              <div class="col-sm-12">
                                              {{ Form::text('empname',$dat->emp_name ,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }}
                                              </div>
                                         </div>
                                         <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="pos"style="font-size:13px;">Position</label>
                                            <div class="col-sm-12">
                                                 {{ Form::text('pos',$dat->position ,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }}
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="title"  style="font-size:13px;">Training Title</label>
                                            <div class="col-sm-12">
                                                 {{ Form::text('title',$dat->training_title ,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }}
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="type"  style="font-size:13px;">Training Type</label>
                                            <div class="col-sm-12">
                                                 {{ Form::text('type',$dat->training_type ,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }}
                                           </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="in_out"  style="font-size:13px;">Inhouse / Outside</label>
                                            <div class="col-sm-12">
                                                 {{ Form::text('in_out',$dat->inhouse_outside ,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }}
                                            </div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="provider"  style="font-size:13px;">Training Provider</label>
                                            <div class="col-sm-12">
                                               {{ Form::text('provider',$dat->training_provider,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }}
                                             </div>
                                        </div>
                                        <!-- <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="agency"  style="font-size:13px;">Training Agency</label>
                                            <div class="col-sm-8">
                                                   {{ Form::text('agency',$dat->training_agency,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }}
                                            </div>
                                        </div> -->
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="dur"  style="font-size:13px;">Training Duration</label>
                                            <div class="col-sm-12">
                                               {{ Form::text('dur',$dat->training_duration,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }}
                                            </div>
                                        </div>
                                       
                                        <div class="form-group" style="display:inline-block;width:49%;"> 
                                         <label class="col-sm-12 col-form-label" for="periods" style="font-size:13px;">Periods</label>
                                            <div class="col-sm-12"> 
                                               {{ Form::text('periods',$dat->periods,array('class'=>'form-control','style'=>'font-size: 13px','Readonly')) }}
                                            </div>
                                       </div> 
                 
                                       <div class="form-group" style="display:inline-block;width:49%;"> 
                                         <label class="col-sm-12 col-form-label" for="status"style="font-size:13px;">Status</label>
                                            <div class="col-sm-12"> 
                                              {{ Form::text('status',$dat->status ,array('class'=>'form-control','style'=>'font-size: 13px')) }}
                                            </div>
                                       </div> 
                                       
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
