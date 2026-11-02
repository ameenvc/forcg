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
                        <li class="breadcrumb-item">Near Miss</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Edit</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('nearmiss.index')}}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:100%">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <form class="mb-5" action="{{ route('nearmiss.editrequest_post') }}" method="post">
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
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable">

                        <thead style="background-color:#FFFFFF">
                            <tr>
                                <th class="text-center" style="width: 100px;font-size:13px;text-transform:capitalize">&nbsp&nbspDepartment&nbsp&nbsp</th>
                                <th class="text-center" style="width: 100px;text-transform:capitalize;font-size:13px;">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspTitle&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                <th class="text-center" style="width: 100px;font-size:13px;text-transform:capitalize">&nbsp&nbsp&nbsp&nbspArea&nbsp&nbsp&nbsp&nbsp</th>
                                <th class="text-center" style="width: 200px;text-transform:capitalize;font-size:13px;">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspType&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                <th class="text-center" style="width: 100px;text-transform:capitalize;font-size:13px;">&nbsp&nbspObservation&nbsp&nbsp</th>
                                <th class="text-center" style="width: 100px;text-transform:capitalize;font-size:13px;">&nbsp&nbspRecommended Action&nbsp&nbsp</th>
                                <th class="text-center" style="width: 100px;text-transform:capitalize;font-size:13px;">&nbsp&nbspCorrection&nbsp&nbsp</th>
                                <th class="text-center" style="width: 100px;text-transform:capitalize;font-size:13px;">&nbsp&nbspRoot&nbspCause&nbsp&nbsp</th>
                                <th class="text-center" style="width: 100px;text-transform:capitalize;font-size:13px;">&nbsp&nbspCorrective&nbspAction&nbsp&nbsp</th>

                            </tr>
                        </thead>


                        <tbody>
                            @if(count($data) > 0)
                            @foreach($data as $dat1)
                            <tr>
                                <td class="text-center font-size-sm">
                                    <input type="text" style="font-size:13px;" class="form-control" id="department[]" name="department[]" Readonly="" value="{{$dat1->department}}">
                                </td>
                                <td class="text-center ">
                                    <input type="text" style="font-size:13px" class="form-control" id="docnumber[]" name="docnumber[]" readonly="" value="{{$dat1->title}}">
                                </td>
                                <td class="text-center font-size-sm">
                                    <input type="text" style="font-size:13px;" class="form-control" id="area[]" name="area[]" value="{{$dat1->area}}">
                                </td>
                                <td class="text-center font-size-sm">
                                    <select class="form-control" name="type[]" required="true" style="font-size:13px">
                                        <option value=" {{$dat1->type }}"> {{$dat1->type }}</option></br>
                                        @if(count($type)>0)
                                        @foreach($type as $typ)
                                        @if($dat1->type != $typ->name)
                                        <option value=" {{$typ->name}}"> {{$typ->name}}</option></br>
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td class="text-center font-size-sm">
                                    <textarea type="text" style="font-size:13px" class="form-control" id="observation[]" name="observation[]"> {{$dat1->observation}} </textarea>
                                </td>
                                <td class="text-center font-size-sm">
                                    <textarea type="text" style="font-size:13px" class="form-control" id="agreedaction[]" name="agreedaction[]"> {{$dat1->agreedaction}}</textarea>
                                </td>
                                <td class="text-center font-size-sm">
                                    <textarea type="text" style="font-size:13px" class="form-control" id="correction[]" name="correction[]"> {{$dat1->correction}}</textarea>
                                </td>
                                <td class="text-center font-size-sm">
                                    <textarea type="text" style="font-size:13px" class="form-control" id="rootcause[]" name="rootcause[]"> {{$dat1->rootcause}}</textarea>
                                </td>
                                <td class="text-center font-size-sm">
                                    <textarea type="text" style="font-size:13px" class="form-control" id="correctiveaction[]" name="correctiveaction[]"> {{$dat1->correctiveaction}}</textarea>
                                </td>

                                <div class="form-group">
                                    <input type="hidden" name="sno[]" value="{{ $dat1->id}}">
                                </div>
                            </tr>

                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    @endif
                    <div>
                        <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px;margin-left:92%;">Submit</button>
                    </div>

                </form>
            </div>
        </div>
        <!-- END Dynamic Table Full -->

    </div>
    <!-- END Page Content -->
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
                                <a class="block block-rounded block-link-shadow bg-body" href="{{ route('nearmiss.index')}}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.closedissue')}}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-check-square fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Issues
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.openissue')}}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            My Open Issues
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.analysis')}}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div>-->
    </div> <!-- END Apps Modal -->
</body>
</html>
@endsection
