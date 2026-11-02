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
                            <a class="link-fx" href="{{ route('doc.register')}}">DAR Register</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">DAR MR Review</a>
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

                    <div class="col-lg-10">
                        <form class="mb-5" action="{{route('doc.hodreview')}}" method="post">
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
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="name">Document Id</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="documentid" name="documentid" value="{{$dat->doc_id}}" Readonly="">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="name">Department</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="department" name="department" value="{{$departmentname}}" Readonly="">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="id">Requester id</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="id" name="id" value="{{$dat->rid}}" Readonly="">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="name">Requester name</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="name" name="name" value="{{$dat->name}}" Readonly="">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="doctype">Document type</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="doctype" name="doctype" value="{{$dat->doctype}}" Readonly="">
                                </div>

                                <div style="margin-left:845px;margin-top:-23px;FONT-SIZE:8px">MS.03.01</div>
                            </div>


                            <div class="form-group" style="display:inline-block;width:98.5%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="reason">Remarks</label>
                                <div class="col-sm-12">

                                    <textarea type="text" class="form-control" style="font-size: 13px;" id="reason" name="reason" Readonly="">{{$dat->reason}}</textarea>
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="download">Attached file</label>
                                <div class="col-sm-8" style="font-size: 13px;">

                                    <button type="button" class="btn btn-secondary" style="font-size: 13px;" data-toggle="modal" data-id="{{ $dat->id }}" data-target="#viewDocs{{$dat->id}}"><strong>Attachments</strong> </button>
                                    <div id="viewDocs{{$dat->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Attachments</h3>
                                                </div>
                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                    <div class="row justify-content-center">
                                                        <thead>
                                                            <tr class="table-secondary">
                                                                <th>S.No</th>
                                                                <th>Attachments</th>
                                                                <th></th>

                                                            </tr>
                                                        </thead>
                                                        <?php $no=0;?>
                                                        @if( count($docs) > 0)
                                                        @foreach($docs as $doc)
                                                        @if( $doc->id==$dat->id)
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: ;">{{ $no=$no+1}}</td>
                                                                <td style="width: 100%;">{{ $doc->documents }}</td>
                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/documents/".$doc->documents)}}" download="{{$doc->documents}}">Download</a></td>

                                                            </tr>
                                                        </tbody>
                                                        @endif
                                                        @endforeach

                                                        @else
                                                        <div class="row justify-content-center">
                                                            <h2>No Files</h2>
                                                        </div>
                                                        @endif
                                                </table>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" style="" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="level">Level</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" style="font-size: 13px;" id="level" name="level" placeholder="Enter Level">
                                </div>
                            </div>
                            {{-- <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="docno">Do you need to change the document name & number?</label>
                                <div class="col-sm-12">
                                    <select class="form-control" style="font-size: 13px;" name="docno" id="no_method">
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>

                                </div>
                            </div> --}}

                    </div>
                </div>
                @if(count($data1)>0)
                <div class="col">
                    <table class="table /*table-responsive*/ table-bordered table-striped table-vcenter m-auto" id="dynamicTable">
                        <thead style="background-color:#FFFFFF">
                            <tr>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize">New Document Number</th>
                                <th class="text-center" style="font-size: 13px;text-transform:capitalize">New Doucment Name</th>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize">Revision Number</th>
                                <th class="text-center" style="font-size:13px;text-transform:capitalize">Revision Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data1 as $dat1)
                            <tr>
                                <td class="text-center">
                                    <input type="text" class="form-control" style="font-size:13px;" id="ndocnumber[]" name="ndocnumber[]" value="{{$dat1->ndocnumber}}">
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control" style="font-size:13px;" id="ndocname[]" name="ndocname[]" value="{{$dat1->ndocname}}">
                                </td>
                                <td class="text-center">
                                    <input type="number" class="form-control" style="font-size:13px;" id="erevnumber" name="erevnumber[]" value="{{ $dat1->erevnumber }}" />
                                </td>
                                <td class="text-center">
                                    <input type="date" class="form-control" style="font-size: 13px;" id="erevdate" name="erevdate[]" value="{{ $dat1->erevdate }}" />
                                </td>

                                <div class="form-group">
                                    <!-- <input type="hidden" name="sno[]" value="{{ $dat1->sno}}"> -->
                                    <input type="hidden" name="documents_id[]" value="{{$dat1->id}}">
                                    <input type="hidden" name="revno[]" value="{{$dat1->erevnumber}}">
                                    <input type="hidden" name="revdate[]" value="{{$dat1->erevdate}}">
                                </div>
                                <td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add More">
                                        <i class="fa fa-fw fa-plus"></i>
                                    </button>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                @endif
                <div class="form-group">
                    <input type="hidden" name="posted" value="{{ $dat->id}}">
                </div>
                <div>
                    <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px;margin-left:74%;">Submit</button>
                </div>
                @endforeach
                @endif
                </form>
                <!-- END Form Horizontal - Default Style -->
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{ asset('js/document/hodreview_script.js')}}"></script>
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href='{{route('doc.analysis')}}'>
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
