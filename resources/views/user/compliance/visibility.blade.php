@extends('layouts.multiselect')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div>
@endsection
@section('content')            
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
            
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Compliance Management</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href=""> Choose Visibility</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/compliancemgmt">Back</a>
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
            <!-- <div class="row">                                
                <div class="col-lg-8"> -->
                    <!-- Form Horizontal - Default Style -->
                    <form action="{{route('visible')}}" method="post" >
                        {{csrf_field()}}  
                        <div class="jumbotron jumbotron-fluid">
                            <div class="container">
                                <h5 class="display-6">Make visible to:</h5>                                       
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="example-select2-multiple" name = "visible[]" style="width: 100%;font-size:13px" data-placeholder="Choose.." required="" multiple>
                                        @if(count($visible)> 0 )
                                            @foreach($visible as $value)
                                                <option value=" {{$value->email}}" style="font-size:13px;"> {{ $value->name }}</option><br>
                                            @endforeach
                                        @endif
                                    </select>
                                    <br><br>
                                    <input type="hidden" name="id" value="{{$id}}">
                                    <input type="submit" value="Make Visible"  class="btn btn-primary" onclick="return confirm('Do you want to proceed?')" style="font-size:13px;margin-left:87% " >
                                </div>
                            </div>
                        </div>
                    </form>                                   
                    <!-- END Form Horizontal - Default Style -->

                <!-- </div>
            </div> -->
        </div>
    </div>
    <!-- END Horizontal -->
</div>
<!-- End Page Content -->
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
                    {{-- <div class="col-6">
                            <!-- CRM -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/compliance/add">
                                <div class="block-content text-center">
                                    <i class="fas fa-plus-circle fa-2x text-primary"></i>       
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                    Add Compliance
                                    </p>
                                </div>
                            </a>
                            <!-- END CRM -->
                        </div> --}}
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/compliancemgmt">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                    <br/>    
                                    Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/compliance/myactions">
                                <div class="block-content text-center">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br>My Actions
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        {{-- <div class="col-6">
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="javascript:void(0)">
                                <div class="block-content text-center">
                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Apps Modal -->
@endsection