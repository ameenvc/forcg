@extends('layouts.multiselect')
@section('content')

    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Waste Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Transfer Request Edit</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/waste/transferschedule/{{{$waste->year}}}">Back</a>
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
                        <form name="form" class="mb-5" action="{{url('/user/waste/hodupdaterequest')}}" method="POST">
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

                            @if(Session::has('qtymsg'))
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('qtymsg')!!}
                            </div>
                            @endif

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Date Of Request</label>
                                <div class="col-sm-12">
                                    <input type="date" style="font-size:13px;width:350px;" class="js-datepicker form-control" id="date" name="date"  value="{{$waste->date}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="request number" style="font-size:13px;">Transfer Request Number</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size:13px;width:350px;" class="form-control" id="request_num" name="request_num"  value="{{$waste->request_no}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="requestedby" style="font-size:13px;">Requested by</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size:13px;width:350px;" class="form-control" id="requestedby" name="requestedby"  value="{{$waste->posted_by}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="dept" style="font-size:13px;">Department</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size:13px;width:350px;" class="form-control" id="dept" name="dept"  value="{{$deptname}}" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-12 col-form-label" for="resp" style="font-size:13px;">Responsibility</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size:13px;width:350px;" class="form-control" id="resp" name="resp"  value="{{$waste->responsibility}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="itemname" style="font-size:13px;">Item Name</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="itemname" name="itemname" value="{{$waste->itemname}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type of Scrap</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size:13px;width:350px;" class="form-control" id="type" name="type"  value="{{$waste->type}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="qty" style="font-size:13px;">Quantity</label>
                                <div class="col-sm-12">
                                  @if(Session::has('qtymsg'))
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="qty" name="qty" value="{{ old('qty') }}" >
                                  @else
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="qty" name="qty" value="{{$waste->quantity}}" >
                                  @endif
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="unit" style="font-size:13px;">Unit</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="unit" name="unit" value="{{$waste->unit}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="use" style="font-size:13px;">Are there Useable parts in the euipment(if yes to be removed and reused)? <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                  @if(Session::has('qtymsg'))
                                    <textarea type="text" class="form-control" id="use" name="use" required style="font-size:13px;" >{{ old('use') }}</textarea>
                                  @else
                                  <textarea type="text" class="form-control" id="use" name="use" required style="font-size:13px;" >{{$waste->useableparts}}</textarea>
                                  @endif

                                </div>
                            </div>

                          <input type="hidden" name="year" value="{{$waste->year}}">
                          <input type="hidden" name="requestnum" value="{{$waste->request_no}}">
                          <input type="hidden" name="approver" value="{{$waste->approver}}">
                          <input type="hidden" name="reviewers" value="{{$waste->reviewer}}">

                            <div class="col-sm-12 form-group">
                                <table width="100%">
                                    <tr>
                                        <td align="right" width="80%"><input class="btn btn-primary btn-sm"  type="submit"  name="submit" value="Update"/></td>

                                    </tr>
                                </table>
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

@endsection
