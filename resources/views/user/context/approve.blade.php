@extends('layouts.multiselect')
@section('content')
<style>
    .breadcrumb>li+li:before {
        display: none !important;
    }

    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>       
<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="/user/context/changelogreg" class="text-black">
                        Context Management
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Approve
                </li>
            </ol>
        </nav>                       
        <div class="block-content block-content-full">
            <div class="row">                                
                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <?php $f=0; ?>  
                    <form class="mb-5" action="{{url('/user/context/approve')}}" enctype="multipart/form-data"  method="post" files="true" >
                        {{csrf_field()}}
                        <input type="hidden" name="posted" value="{{Auth::user()->email}}" > 
                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}" > 
                        <input type="hidden" name="id" value="{{$id}}" >
            
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
                        @if($dat)
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="crid">Change Request ID</label>
                                        <div class="col-sm-12">
                                        <input type="text" class="form-control" id="crid" name="crid" value="{{$dat->crid}}" readonly style="font-size: 13px;">
                                        </div>
                                    </div>   

                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="title">Title</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="title" name="title" value="{{ $dat->title }}" readonly style="font-size: 13px;">
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" for="category" style="font-size: 13px;">Change Category</label>
                                        <div class="col-sm-12">
                                        <input type="text" class="form-control" id="category" name="category" value="{{ $changeCategories}}" readonly style="font-size: 13px;">
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="initiator">Initiator</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="initiator" name="initiator" value="{{  $dat->initiatorBy ? $dat->initiatorBy->name : $dat->initiator }}"  readonly=""  style="font-size: 13px;">
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-12 col-form-label" for="scope" style="font-size: 13px;">Scope<span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <textarea type="text"  style="font-size: 13px;" class="form-control" id="scope" name="scope" required>{{ $dat->scope }}</textarea>
                                        </div>
                                    </div>
                                
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-12 col-form-label" for="reason" style="font-size: 13px;">Reason</label>
                                        <div class="col-sm-12">
                                            <textarea type="text"  style="font-size: 13px;" class="form-control" id="reason" name="reason"   >{{  $dat->reason }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-12 col-form-label" for="benefits" style="font-size: 13px;">Benefits</label>
                                        <div class="col-sm-12">
                                            <textarea type="text"  style="font-size: 13px;" class="form-control" id="benefits" name="benefits"  >{{  $dat->benefits }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-12 col-form-label" for="impacts" style="font-size: 13px;">Impacts</label>
                                        <div class="col-sm-12">
                                            <textarea type="text"  style="font-size: 13px;" class="form-control" id="impacts" name="impacts"  >{{ $dat->impacts }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-12 col-form-label" for="risk_identified" style="font-size: 13px;">Risk Identified</label>
                                        <div class="col-sm-12">
                                            <textarea type="text"  style="font-size: 13px;" class="form-control" id="risk_identified" name="risk_identified"  >{{  $dat->risk_identified }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Priority<span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <select class="form-control" id="sel_priority" required name="priority" style="font-size: 13px;width:100%;" data-placeholder="Choose Priority">
                                                <option value=""></option>
                                                @if(count($priority)> 0 )
                                                @foreach($priority as $prio)
                                                <option style="font-size: 13px;" value="{{$prio->id}}" {{$prio->id==$dat->priority ?'selected' :''}}> {{ $prio->name }}</option><br>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Department<span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <select class="form-control" id="sel_department" required name="department" style="font-size: 13px;width:100%;" data-placeholder="Choose a Department">
                                                <option value=""></option>
                                                @if(count($department)> 0 )
                                                @foreach($department as $d)
                                                <option value=" {{$d->id}}" style="font-size: 13px;" {{$d->id==$dat->department ?'selected' :''}}> {{ $d->departmentname }}</option><br>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                

                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="changing_product">Changing Product/Service</label>
                                        <div class="col-sm-12">
                                            <textarea type="text" class="form-control" id="changing_product" name="changing_product" style="font-size: 13px;">{{ $dat->changing_product }}</textarea>
                                        </div>
                                    </div>
                                
                                    

                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" for="rev_authority" style="font-size: 13px;">Review Authority</label>
                                        <div class="col-sm-12">
                                        <input type="text" class="form-control" id="rev_authority" name="rev_authority" value="{{ $dat->reviewer ? $dat->reviewer->name : $dat->rev_authority }}" readonly style="font-size: 13px;">                       
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" for="appr_authority" style="font-size: 13px;">Approve Authority</label>
                                        <div class="col-sm-12">
                                        <input type="text" class="form-control" id="appr_authority" name="appr_authority" value="{{ $dat->approver ? $dat->approver->name : $dat->appr_authority }}" readonly style="font-size: 13px;">                       
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" for="remarks" style="font-size: 13px;">Remarks</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="remarks" name="remarks" value="{{$dat->remarks}}" readonly style="font-size: 13px;">                       
                                        </div>
                                    </div> 

                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="question">Approval</label>
                                        <div class="col-sm-12">
                                            <select  name="question" id="close_option" class="form-control " style="font-size: 13px;">
                                                <option value="none" selected disabled hidden> 
                                                    Select an Option 
                                                </option>
                                                <option value="2" style="font-size: 13px;" >Approved</option>
                                                <option value="1" style="font-size: 13px;">Not Approved, Need Improvement</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6" id="a"> 
                                        <label  class="col-sm-12 col-form-label" style="font-size: 13px;"  for="higherremarks">Approver Remarks</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="higherremarks" id="higherremarks" class="form-control" style="font-size: 13px;"> 
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6" id="b"> 
                                        <label  class="col-sm-12 col-form-label" style="font-size: 13px;"  for="higherproposal">Approver Proposal</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="higherproposal" id="higherproposal" class="form-control" style="font-size: 13px;"> 
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        @endif
                
                        <div class="row">
                            <div class="form-group col-sm-10 text-right">
                                <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;">Reset</button>
                                <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">Submit</button>
                            </div>
                        </div>
                    </form>              
                </div>
            </div>
        </div>
    </div>
</div>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#sel_category').select2({});
    });

    $(document).ready(function() {
    $('#sel_priority').select2({
    });
    });

    $(document).ready(function() {
    $('#sel_department').select2({
    });
    });

    $(document).ready(function() {
    $('#sel_rev').select2({
    });
    });

    $(document).ready(function() {
    $('#sel_appr').select2({
    });
    });
</script>    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $("#close_option").change(function() {
        console.log($("#close_option option:selected").val());
        if ($("#close_option option:selected").val() == 2) {
            $('#shipping_carrier').prop('disabled', 'disabled');
            $('#shipping_carrier').val(1);
           
            $('#b').hide();
           
            $('#a').show();

        } else {
            $('#shipping_carrier').prop('disabled', false);
            
            $('#b').show();
           
            $('#a').hide();


        }
        });                      
</script>
@endsection