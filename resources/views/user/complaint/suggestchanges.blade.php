@extends('layouts.multiselect')
@section('content')
<style>
    .td-class{
        font-size:11px;
        padding:4px !important;
    }
    .border-3 {
        border-width: 3px !important;
    }

    .font-15{
        font-size:12px !important;
    }
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
<div id="page-loader" class="show"></div>
<!-- Page Content -->
<div class="content" style="width:100%;">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="/user/complaint/closure?val={{ $id }}" class="text-black">
                        Customer Complaint
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Suggest Changes
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <form class="mb-5" action="{{route('complaint.suggestchanges')}}" method="post" enctype="multipart/form-data">

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
                        @if(Session::has('err'))
                        <div class='alert alert-warning'>
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('err')!!}
                        </div>
                        @endif

                        <div class="row">
                            <input type="hidden" name="id" value="{{$id}}">
                            <input type="hidden" name="percent_status" value="{{$status}}">
                            <input type="hidden" name="rid" value="{{$rid}}">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 24px;"><Strong>Suggest Changes</Strong> </label>
                                <div class="col-sm-12">
                                    <textarea name="remarks" id="remarks" cols="20" rows="7" class="form-control" style="font-size: 13px;"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="col-sm-6 col-form-label" style="font-size: 13px;">Actions required to be modified again? <span class="text-danger">*</span>
                                    <input style="margin-left:10px;" type="radio" value="yes" id="yes2"  name="action_modified" required><label style="font-size: 13px;">&nbsp;Yes</label>
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no2"  name="action_modified"><label style="font-size: 13px;">No</label>
                                </label>

                            </div>

                        </div>
                        <div class="col-sm-12 text-right">
                            <button type="submit" for="submit"  class="btn btn-primary" data-toggle="click-ripple"  onclick="return confirm('Are you sure to submit?')" style="font-size: 13px;" >Submit</button>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/complaint/addcustomer">
                                <div class="block-content text-center">
                                    <i class="fas fa-plus-square fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Add Customers
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaintmgmt">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                    <br>Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/list">
                                <div class="block-content text-center">
                                    <i class="fas fa-address-card fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Customer List
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                        <div class="col-6">
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/my">
                                <div class="block-content text-center">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        My Complaints
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                        <div class="col-6">
                            <br>
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/closedc"style="margin-top:-16px">
                                <div class="block-content text-center"style="height:130px">
                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br>Closed
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#remarks').blur(function(){
        $(this).css("background-color","#e8f0fe");
    }).focus(function(){
        $(this).css("background-color","white");
    });
    One.loader('show');
</script>
<!-- END Apps Modal -->
@endsection
