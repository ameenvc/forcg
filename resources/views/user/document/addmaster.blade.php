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
                            <a class="link-fx" href="">Add</a>
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
                    <form class="mb-5" action="{{ route('doc.addmasterlist') }}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                    <div class="col-lg-10">

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
                            <div class="form-group" style="display:inline-block;width:70%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department">Department<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <div class="form-group" style="color:#666666;top:5px;">
                                        <select class="js-select2 form-control" style="font-size: 13px;" id="department" name="department" required data-placeholder="Choose a Department">
                                            <option value="">Choose a Department</option>
                                            @if(count($departments)> 0 )
                                            @foreach($departments as $value)
                                            <!-- <option value=" {{$value->departmentname}}"> {{ $value->departmentname }}</option></br> -->
                                            <option value="{{$value->id}}"> {{ $value->departmentname }}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:70%;margin-top:-30px;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="type">Type<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <div class="form-group" style="color:#666666;">
                                        <select class="js-select2 form-control" style="font-size: 13px;" id="type" name="type" required data-placeholder="Choose Document Type">
                                            <option value="">Choose Document Type</option>
                                            @if(count($doctypes)> 0 )
                                            @foreach($doctypes as $value)
                                            <option value=" {{$value->id}}"> {{ $value->typename }}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                        <!-- <div style="margin-left:560px;margin-top:-23px;FONT-SIZE:8px">MS.03.01</div> -->
                                    </div>
                                </div>
                            </div>
                    </div>

                    <table class="table table-responsive table-bordered table-striped table-vcenter " id="newdynamicTable">
                        <thead style="background-color:#FFFFFF">
                            <tr>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspDocument&nbspName&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspDocument&nbspNumber&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspLevel&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspRevision&nbspnumber&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspRevision&nbspdate&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>

                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" style="font-size: 13px;" name="docu[0][docname]" placeholder="Enter document name" required="" class="form-control" /></td>
                                <td><input type="text" style="font-size: 13px;" name="docu[0][docnumber]" placeholder="Enter document number" required="" class="form-control" /></td>
                                <td><input type="number" style="font-size: 13px;" name="docu[0][level]" placeholder="Enter level" required="" class="form-control" /></td>
                                <td><input type="number" style="font-size: 13px;" name="docu[0][revnumber]" placeholder="Enter revision number" required="" class="form-control" /></td>
                                <td><input type="date" style="font-size: 13px;" name="docu[0][revdate]" required="" class="form-control" /></td>

                                <td><button type="button" name="add" id="newadd" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add more">
                                        <i class="fa fa-fw fa-plus"></i>
                                    </button>
                                </td>
                            </tr>

                        </tbody>

                    </table>
                    <input type="hidden" value="{{auth::user()->employeeid}}" name="post">
                </div>
                <div>
                    <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px;margin-left:90%;">Submit</button>
                </div>
                </form>
                <!-- END Form Horizontal - Default Style -->
            </div>
        </div>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/document/addmaster_script.js') }}"></script>
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
