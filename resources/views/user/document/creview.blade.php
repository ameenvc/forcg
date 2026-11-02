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
                        <li class="breadcrumb-item">Document Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{route('doc.home') }}">Master List</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">DCR HoD Review</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('doc.home')}}">Back</a>
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
                    <form class="mb-5" action="" method="post">
                    {{ csrf_field() }}

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
                    <?php
                      $departmentname = DB::table('departments')->where('id',$dat->department)->value('departmentname');
                    ?>
                    <div class="col-lg-10">
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department">Department</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" id="department" name="department" value="{{$departmentname}}" Readonly="">
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="docnumber">Document number</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" id="docnumber" name="docnumber" value="{{$dat->docnumber}}" Readonly="">
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="name">Document</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" id="document" name="document" value="{{$dat->document}}" Readonly="">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Type</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" id="type" name="type" value="{{$dat->type}}" Readonly="">
                            </div>

                            <div style="margin-left:845px;margin-top:-23px;FONT-SIZE:8px">MS.03.01</div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="level">Level</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" id="level" name="level" value="{{$dat->level}}" Readonly="">
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="revnumber">Revision number</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" id="revnumber" name="revnumber" value="{{$dat->ch_approved!='YES' ? $dat->revnumber : $dat->nrevnumber}}" Readonly="">
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="erevdate">Revision date</label>
                            <div class="col-sm-12">

                                <input type="text" class="form-control" style="font-size: 13px;" id="erevdate" name="erevdate" value="{{$dat->ch_approved!='YES' ? $dat->erevdate : $dat->nrevdate }}" Readonly="">
                            </div>
                        </div>
                        <div class="form-group" class="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="affected">Other affected documents</label>
                            <div class="col-sm-12">

                                <textarea type="text" class="form-control" style="font-size: 13px;" id="affected" name="affected" Readonly="">{{$dat->affected}}</textarea>
                            </div>
                        </div>
                        <div class="form-group" class="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="reason">Reason for change request</label>
                            <div class="col-sm-12">

                                <textarea type="text" class="form-control" style="font-size: 13px;" id="reason" name="reason" Readonly="">{{$dat->reason}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="posted" value="{{ $dat->id}}">

                        </div>
                        <br>
                    </div>

                    @php $rr=$dat->id;@endphp
                    <div>
                        <a href="{{ route('doc.changeok', ['id' => $id])}}" class="btn btn-primary" style="font-size:13px;margin-left:59%;">Review OK</a>
                        <a href="{{route('doc.changenotok', ['id' => $id])}}" class="btn btn-primary" style="font-size:13px;">Review Not OK</a>
                    </div>
                    @endforeach
                    @endif
                    </form>
                </div>
            </div>
        </div>
</body>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="{{route('doc.home')}}">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm  mt-2 mb-3">
                                        Master List
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->

                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('doc.register')}}">
                                <div class="block-content text-center">
                                    <i class="fa fa-file-upload fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        DAR&nbspRegister
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            {{-- <br> --}}
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-1" href="{{ route('doc.history')}}">
                                <div class="block-content text-center">
                                    <i class="fa fa-history fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm  mt-2 mb-3">
                                        History
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                        <div class="col-6">

                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-2" href="{{ route('doc.explorer')}}">
                                <div class="block-content text-center">
                                    <i class="fa fa-folder-open fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Explorer
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                        <div class="col-6">
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href='{{ route('doc.analysis')}}'>
                                <div class="block-content text-center">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-0">
                                        Analysis
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
</html>
@endsection
