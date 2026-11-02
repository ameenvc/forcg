@extends('layouts.multiselect')

@section('content')           
      <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Context Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Add Issue</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/contextmgmt">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

        
                <!-- END Hero -->

                 <!-- Page Content -->
                <div class="content" style="width:95%;">
                   <!-- Horizontal -->
                    <div class="block block-rounded">                       
                        <div class="block-content block-content-full">
                            <div class="row">                                
                                <div class="col-lg-10">
                                    <!-- Form Horizontal - Default Style -->
                                    <?php $f=0; ?>               
                       
                       
                            <form class="mb-5" action="{{url('/user/context/insertissue')}}" enctype="multipart/form-data"  method="post" files="true" >
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
                                    <div class="col-lg-10">

                                        {{--<!-- <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="type"  >Type</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name = "type" required="true" >
                                                    @if(count($parties)>0)
                                                        @foreach($parties as $cat)
                                                           <option value=" {{$cat->type}}"> {{$cat->type}}</option></br>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>     -->--}}

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Type</label>
                                            <div class="col-sm-12">
                                                <select class="js-select2 form-control" style="font-size: 13px;"  id="example-select2-multiple" name = "type[]"  placeholder="Choose Type" multiple >
                                                    @if(count($parties)> 0 )
                                                        @foreach($parties as $p)
                                                            <option style="font-size: 13px;" value=" {{$p->type}}"  > {{ $p->type }}</option><br>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                       
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label style="font-size: 13px;" class="col-sm-4 col-form-label" for="title" >Title</label>
                                            <div class="col-sm-12">
                                                <input type="text" style="font-size: 13px;"   class="form-control" id="title" name="title" placeholder="Enter title Here. " value="{{ old('title') }}" >
                                            </div>
                                        </div>

                                        

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="purpose" >Purpose <span style="color:#f00;">*</span></label>
                                            <div class="col-sm-12">
                 
                                                <select class="js-select2 form-control" style="font-size: 13px;" id="sel_purpose" name = "purpose[]" placeholder="Choose Purpose" multiple >
                                                    @if(count($purpose)> 0 )
                                                        @foreach($purpose as $p)
                                                            <option style="font-size: 13px;" value=" {{$p->name}}"  > {{ $p->name }}</option><br>
                                                        @endforeach
                                                    @endif
                                                </select>
                                              
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label style="font-size: 13px;" class="col-sm-4 col-form-label" for="accountability">Accountability</label>
                                            <div class="col-sm-12">
                                                <input style="font-size: 13px;" type="text" class="form-control" id="accountability" name="accountability" value="{{ old('accountability') }}"  >
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:98%;">
                                            <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Issue</label>
                                            <div class="col-sm-12">
                                                <textarea type="text" style="font-size: 13px;"  class="form-control" id="issue" name="issue" placeholder="Enter Issue Here. " value="{{ old('issue') }}" ></textarea>
                                            </div>
                                        </div>
                                      
                                        <div class="form-group" style="display:inline-block;width:99%;">
                                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="monitoring" >Monitoring Methodology</label>
                                            <div class="col-sm-12">
                                                <textarea type="text" style="font-size: 13px;"  class="form-control" id="monitoring" name="monitoring" placeholder="Enter Monitoring Methodology Here. " value="{{ old('monitoring') }}" ></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:inline-block;width:99%;">
                                            <label style="font-size: 13px;" class="col-sm-4 col-form-label" for="bias" >Bias <span style="color:#f00;">*</span></label>
                                            <div class="col-sm-12">
                                                <select style="font-size: 13px;" class="form-control"  name = "bias"  placeholder="Choose Bias">
                                                    <option style="font-size: 13px;" value=" Negative"> Negative</option><br>
                                                    <option style="font-size: 13px;" value=" Positive"> Positive</option><br>
                                                </select>
                                            </div>
                                        </div>
                                       
                                        

                                    </div>
                                    <br>
                                    <div style="text-align:center;">
                                        <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px; float:right;margin-right:18%;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">Add </button>
                                        <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;float:right;margin-right:1%; ">Reset</button>
                                    </div>
                                    
                                </form> 
                            
                                                 
                    </div>
                </div>
            </div>
        </div>
  
    {{-- <footer id="page-footer" class="bg-body-light">
                <div class="content py-3">
                    <div class="row font-size-sm">
                        <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
                           </div>
                        <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
                            <a style="margin-left:35%;" class="font-w600" href="" target="_blank">SETILAS QuickQM 2.1.1</a> &copy; <span data-toggle="year-copy"></span>
                        </div>
                    </div>
                </div>
            </footer> --}}
              <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
            <!-- Opens from the modal toggle button in the header -->
            <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">More</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="si si-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="row gutters-tiny">
                                 
                                    <div class="col-6">
                                        <!-- CRM -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="{{url('/user/contextmgmt')}}">
                                            <div class="block-content text-center">
                                                <i class="fa fa-bug fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Issue Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END CRM -->
                                    </div>
                                   <div class="col-6">
                                       
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/context/interestparty">
                                            <div class="block-content text-center">
                                                <i class="fa fa-2x fa-id-card-alt text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    InterestedParty Register
                                                </p>
                                            </div>
                                        </a>
                                   
                                    </div>
                                   <div class="col-6">
                                       
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/context/changelogreg">
                                            <div class="block-content text-center">
                                                <i class="fa fa-2x fa-clipboard-list text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Change Log Register
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
             </div>
        <!-- END Page Container -->
        
        


@endsection
