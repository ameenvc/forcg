@extends('layouts.backend')
@section('content')

    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Waste Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Add Waste</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/wastemgmt">Back</a>
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
                        <form name="form" class="mb-5" action="{{url('/user/waste/insertwastemaster')}}" method="POST" onsubmit="return validation()" enctype="multipart/form-data">
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

                           <!-- <div class="form-group">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Date Of Request</label>
                                <div class="col-sm-12">
                                <input type="date" style="font-size:13px;width:350px;" class="js-datepicker form-control" id="date" name="date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="<?php echo date("Y-m-d");?>" readonly>
                                </div>
                            </div> -->

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Department <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                    @if(count($departments)>0)
                                    <select class="form-control" name="dept" id="dept" style="font-size: 13px;" required="true">
                                    <option value="">Choose a Department</option>
                                        @foreach($departments as $dept)
                                        <option value="{{$dept->id}}">{{$dept->departmentname}}</option>
                                        @endforeach
                                    </select>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="responsibility">Responsibility <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                    @if(count($users)>0)
                                    <select class="form-control" name="responsibility" id="responsibility" style="font-size: 13px;" required="true">
                                    <option value="">Choose Responsibility</option>
                                        @foreach($users as $user)
                                        <option value="{{$user->name}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="wname" style="font-size:13px;">Waste Name <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="wastename" name="wastename" required>
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="wcode" style="font-size:13px;">Waste Code <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="wastecode" name="wastecode" required>
                                </div>
                            </div>
                            {{-- <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type Of Scrap <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="type" id="type" style="font-size: 13px;" required="true">
                                        <option value="" selected="" disabled>Choose Waste Type </option>
                                        @foreach($wastetypes as $w)
                                        <option value="{{$w->name}}">{{$w->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="mqty" style="font-size:13px;">Monthly Expected Quantity</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="mqty" name="mqty">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="yqty" style="font-size:13px;">Yearly Expected Quantity</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="yqty" name="yqty">
                                </div>
                            </div>


                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="unit" style="font-size:13px;">Unit <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="unit" id="unit" style="font-size: 13px;width:250px;" required="true">
                                    <option value="" selected="" disabled>Choose Unit</option>
                                        @foreach($wasteunits as $unit)
                                           <option value="{{$unit->name}}">{{ $unit->name}}</option>
                                        @endforeach
                                        <option value="Other">Other</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type Of Scrap <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="type" id="type" style="font-size: 13px;" required="true">
                                        <option value="" selected="" disabled>Choose Waste Type </option>
                                        @foreach($wastetypes as $w)
                                        <option value="{{$w->name}}">{{$w->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 col-form-label" for="other" style="font-size:13px;"></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;width:250px;" class="form-control" id="other" name="other">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="agency" style="font-size:13px;">Agency(to handle the scrap)</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="agency" name="agency">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="agencylicense" style="font-size:13px;">Attach Agency License(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" style="font-size: 13px;" class="form-control" id="agencylicense" name="agencylicense" multiple>
                                </div>
                            </div>
                       @if(count($wastedesc) > 0)
                        <div class="form-group">
                          <label class="col-sm-12 col-form-label" style="font-size:13px;">
                            @foreach($wastedesc as $w)
                            <label style="font-size:12px;">{{$w->waste_type}}&nbsp;{{$w->wastedesc}}</label><br>
                            @endforeach
                          </label>
                        </div>
                        @endif
                            <div class="form-group row">
                                <div class="col-sm-8 ml-auto">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:490px;margin-top:15px;">Submit</button>
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/waste/register">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Waste Management
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/waste/register">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Waste / Scrap List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/waste/transferrequest">
                                    <div class="block-content text-center">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Transfer Request
                                        </p>
                                    </div>
                                </a>

                            </div>

                                    <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/waste/analysis">
                                            <div class="block-content text-center">
                                                <i class="si si-wallet fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
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

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<!--<script src="{{ asset('js/jquery.min.js')}}"></script>-->
<script type="text/javascript">
window.onload = function(){
  // Your code here
  $(document).ready(function() {
        $('#other').hide();
    });
     $("#unit").change(function() {
        if ($("#unit option:selected").val() == "Other") {

            $('#other').show();
        } else {

            $('#other').hide();
        }
    });
}
</script>
@endsection
