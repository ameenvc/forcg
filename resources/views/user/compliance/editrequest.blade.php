@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">

<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Compliance Management</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Edit</a>
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
<div class="content " style="width:100%">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" action="{{url('/user/compliance/editrequest')}}" method="post">
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
                            <div class="table-responsive">
                                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                    <thead style="background-color:#FFFFFF">
                                        <tr>
                                            <th class="text-center" style="text-transform:capitalize;font-size:13px;">Compliance ID</th>
                                            <th class="text-center" style="font-size:13px;text-transform:capitalize;">Regulatory Authority</th>
                                            <th class="text-center" style="text-transform:capitalize;font-size:13px;">Legislative Issue</th>
                                            <th class="text-center" style="text-transform:capitalize;font-size:13px;">Version</th>
                                            <th class="text-center" style="text-transform:capitalize;font-size:13px;">Act or Regulation</th>
                                            <th class="text-center" style="text-transform:capitalize;font-size:13px;">Requirement</th>
                                            <th class="text-center" style="text-transform:capitalize;font-size:13px;">Related Aspect/Risk Number</th>
                                            <th class="text-center" style="text-transform:capitalize;font-size:13px;">Process&nbspOwner</th>
                                            <th class="text-center" style="text-transform:capitalize;font-size:13px;">Responsibility</th>
                                            <th class="text-center" style="font-size:13px;text-transform:capitalize;">Target Date</th>
                                            <th class="text-center" style="font-size:13px;text-transform:capitalize;">Review&nbspfrequency</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $dat1)
                                        <tr>
                                            <input type="hidden" style="font-size:13px;" class="form-control" id="Cid[]" name="Cid[]" readonly="" value="{{$dat1->Cid}}">
                                            <td class="text-center">
                                                <input type="text" style="font-size:13px;" class="form-control" id="authId[]" name="AuthId[]" readonly="" value="{{$dat1->auth_id}}">
                                            </td>
                                            <td class="text-center ">
                                                <input type="text" style="font-size:13px;" class="form-control" id="authority[]" name="authority[]" value="{{$dat1->authority}}">
                                            </td>
                                            <td class="text-center ">
                                                <input type="text" style="font-size:13px;" class="form-control" id="issue[]" name="issue[]" value="{{$dat1->issue}}">
                                            </td>
                                            <td class="text-center ">
                                                <input type="text" style="font-size:13px;" class="form-control" id="version[]" name="version[]" value="{{$dat1->version}}">
                                            </td>
                                            <td class="text-center ">
                                                <input type="text" style="font-size:13px;" class="form-control" id="regulation[]" name="regulation[]" value="{{$dat1->regulation}}">
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <input type="text" style="font-size:13px;" class="form-control" id="requirement[]" name="requirement[]" value="{{$dat1->requirement}}">
                                            </td>
                                            <td class="text-center ">
                                                <input type="text" style="font-size:13px;" class="form-control" id="aspect[]" name="aspect[]" value="{{$dat1->aspect}}">
                                            </td>
                                            <td class="text-center ">
                                                <select class="form-control" name="owner[]" required="true" style="font-size:13px;">
                                                    <option value="{{$dat1->owner}}"> {{$dat1->dep ? $dat1->dep->departmentname : '-' }}</option></br>
                                                    @if(count($mn)> 0 )
                                                    @foreach($mn as $value)
                                                    @if($value->id != $dat1->owner)
                                                    <option value="{{$value->id}}"> {{ $value->departmentname }}</option><br>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td class="text-center ">
                                                <select class="form-control" name="responsibility[]" required="true" style="font-size:13px;" id="responsibility">
                                                    <option value=" {{$dat1->responsibility}}"> {{$dat1->responsibility }}</option></br>
                                                    @if(count($users)> 0 )
                                                    @foreach($users as $value)
                                                    @if($value->email != $dat1->responsibility)
                                                    <option value=" {{$value->email}}"> {{ $value->email }}</option><br>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td class="text-center font-size-sm">
                                                <input type="date" style="font-size:13px;width:150px;" class="form-control" id="duedate[]" name="duedate[]" value="{{$dat1->duedate}}">
                                            </td>
                                            <td class="text-center " style="font-size:13px;">
                                                <select class="form-control" id="frequency" style="font-size: 13px;" name="frequency[]">
                                                    <option style="font-size: 13px;" selected="">{{$dat1->frequency}}</option>
                                                    <option style="font-size: 13px;" value=" {{'Quarterly'}}"> Quarterly</option><br>
                                                    <option style="font-size: 13px;" value=" {{'Half yearly'}}">Half yearly</option><br>
                                                    <option style="font-size: 13px;" value=" {{'Yearly'}}"> Yearly</option><br>
                                                </select>
                                            </td>
                                            <div class="form-group">
                                                <input type="hidden" name="sno[]" value="{{ $dat1->Cid}}">
                                            </div>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <br>
                        <div class="text-right mr-4">
                            <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary" style="font-size:13px;">Submit</button>
                        </div>

                    </form>
                    <!-- END Form Horizontal - Default Style -->


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
                            <h3 class="block-title">Apps</h3>
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
                                                <br />
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

        </body>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#select_owner').select2({});
            });

        </script>


</html>
@endsection
