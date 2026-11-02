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
<!-- END Hero -->
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
                    Change Log
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <?php $f=0; ?>
                    <form class="mb-5" action="{{url('/user/context/insertrequest')}}" enctype="multipart/form-data" method="post" files="true">
                        {{csrf_field()}}
                        <input type="hidden" name="posted" value="{{Auth::user()->email}}">
                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">

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
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="title">Title</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" style="font-size: 13px;" placeholder="Enter title">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="category" style="font-size: 13px;width:100%;">Change Category <span style="color:#f00;">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="sel_category" name="category[]" multiple style="font-size: 13px;width:100%;" data-placeholder="Choose Categories" required>
                                            @if(count($changecategory)> 0 )
                                            @foreach($changecategory as $c)
                                            <option value="{{$c->id}}"> {{ $c->category }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="scope" style="font-size: 13px;">Scope <span style="color:#f00;">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea type="text" style="font-size: 13px;" class="form-control" id="scope" name="scope" placeholder="Enter Scope Here. " value="{{ old('scope') }}" required></textarea>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="reason" style="font-size: 13px;">Reason</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" style="font-size: 13px;" class="form-control" id="reason" name="reason" placeholder="Enter Reason Here. " value="{{ old('reason') }}"></textarea>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="benefits" style="font-size: 13px;">Benefits</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" style="font-size: 13px;" class="form-control" id="benefits" name="benefits" placeholder="Enter Benefits Here. " value="{{ old('benefits') }}"></textarea>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="impacts" style="font-size: 13px;">Impacts</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" style="font-size: 13px;" class="form-control" id="impacts" name="impacts" placeholder="Enter Impacts Here. " value="{{ old('impacts') }}"></textarea>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="risk_identified" style="font-size: 13px;">Risk Identified</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" style="font-size: 13px;" class="form-control" id="risk_identified" name="risk_identified" placeholder="Enter Risk Identified Here. " value="{{ old('risk_identified') }}"></textarea>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="initiator">Initiator</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="initiator" name="initiator" value="{{Auth::user()->name}}" readonly="" style="font-size: 13px;">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Department <span style="color:#f00;">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="sel_department" name="department" style="font-size: 13px;width:100%;" data-placeholder="Choose a Departmemt" required>
                                            <option value=""></option>
                                            @if(count($department)> 0 )
                                            @foreach($department as $d)
                                            <option value=" {{$d->id}}" style="font-size: 13px;"> {{ $d->departmentname }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="changing_product">Changing Product/Service</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" style="font-size: 13px;" class="form-control" id="changing_product" name="changing_product" value="{{ old('changing_product') }}" style="font-size: 13px;"></textarea>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Priority <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="sel_priority" name="priority" style="font-size: 13px;width:100%;" data-placeholder="Choose Priority" required>
                                            <option value=""></option>
                                            @if(count($priority)> 0 )
                                            @foreach($priority as $prio)
                                            <option style="font-size: 13px;" value="{{$prio->id}}"> {{ $prio->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="severity" style="font-size: 13px;">Severity <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control js-select2" id="severity" name="severity" style="font-size: 13px;width:100%;" required data-placeholder="Select Severity">
                                            <option></option>
                                            @if(count($severities)> 0 )
                                            @foreach($severities as $s)
                                            <option style="font-size: 13px;" value="{{$s->id}}"> {{ $s->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="rev_authority" style="font-size: 13px;">Review Authority <span style="color:#f00;">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" style="font-size: 13px;width:100%;" id="sel_rev" name="rev_authority" required data-placeholder="Select Review Authority">
                                            <option value=""></option>
                                            @if(count($reviewer)> 0 )
                                            @foreach($reviewer as $rev)
                                            <option value="{{$rev->id}}" style="font-size: 13px;"> {{ $rev->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                        <!-- <div style="margin-left:550px;margin-top:-23px;FONT-SIZE:8px">MS.07.04</div> -->
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="appr_authority" style="font-size: 13px;">Approve Authority <span style="color:#f00;">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" style="font-size: 13px;width:100%;" id="sel_appr" name="appr_authority" required data-placeholder="Select Approve Authority">                                            
                                            <option value=""></option>
                                            @if(count($approver)> 0 )
                                            @foreach($approver as $appr)
                                            <option value="{{$appr->id}}" style="font-size: 13px;"> {{ $appr->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                        <!-- <div style="margin-left:550px;margin-top:-23px;FONT-SIZE:8px">MS.07.03</div> -->
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="file" style="font-size:13px;">File</label>
                                    <div class="col-sm-12">
                                        <input type="file" class="form-control" id="file" style="font-size:13px;" name="file" value="{{ old('file') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-right form-group">
                                    <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;">Reset</button>
                                    <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>        
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Container -->
<!-- Apps Modal -->
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


<script type="text/javascript">
    $(document).ready(function() {
        $('#sel_category').select2({});
    });

    $(document).ready(function() {
        $('#sel_priority').select2({});
    });

    $(document).ready(function() {
        $('#sel_department').select2({});
    });

    $(document).ready(function() {
        $('#sel_rev').select2({});
    });

    $(document).ready(function() {
        $('#sel_appr').select2({});
    });

</script>
@endsection
