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
                            <a class="link-fx" href="{{route('doc.home')}}">Master List</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Removal Request</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{route('doc.home')}}">Back</a>
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
                    <form class="mb-5" action="{{ route('doc.removalrequest')}}" method="post">
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
                        @endforeach
                        @endif
                        @if(count($data)>0)
                        <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable">

                            <thead style="background-color:#FFFFFF">
                                <tr>
                                    <th class="text-center" style="font-size: 13px;width: 50px;text-transform:capitalize">&nbspExisting&nbspDocument&nbspNumber&nbsp</th>
                                    <th class="text-center" style="font-size: 13px;width: 50px;text-transform:capitalize">&nbspExisting&nbspDocument&nbspName&nbsp</th>
                                    <th class="text-center" style="font-size: 13px;width: 50px;text-transform:capitalize">&nbspExisting&nbspRevision&nbspNumber&nbsp</th>
                                    <th class="text-center" style="font-size: 13px;width: 50px;text-transform:capitalize">&nbspExisting&nbspRevision&nbspDate&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $dat1)
                                <tr>
                                    <td class="text-center font-size-sm">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="docnumber[]" name="docnumber[]" value="{{$dat1->docnumber}}" Readonly=''>
                                    </td>
                                    <td class="text-center font-size-sm">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="document[]" name="document[]" value="{{$dat1->document}}" Readonly=''>
                                    </td>
                                    <td class="text-center font-size-sm">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="erevnumber[]" name="erevnumber[]" value="{{$dat1->revnumber}}" Readonly=''>
                                    </td>
                                    <td class="text-center font-size-sm">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="erevdate[]" name="erevdate[]" value="{{$dat1->erevdate}}" Readonly=''>
                                    </td>

                                    <div class="form-group">
                                        <input type="hidden" name="sno[]" value="{{ $dat1->id}}">
                                        <input type="hidden" name="revno[]" value="{{ $dat1->revnumber}}">
                                    </div>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                        @endif
                        <div class="col-lg-10">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="reason">Reason For Remove</label>
                                <div class="col-sm-8">
                                    <textarea type="text" class="form-control" style="font-size: 13px;" required="" id="reason" name="reason" placeholder="Enter Reason for removal"></textarea>
                                </div>
                            </div>
                            <div>
                                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px;margin-left:90%;">Submit</button>
                            </div>
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
                            <a class="block block-rounded block-link-shadow bg-body mb-2" href="{{route('doc.explorer')}}">
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
