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
                        <a class="link-fx" href="">Reopen</a>
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
            <div class="row">

                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" action="{{url('/user/compliance/reopen')}}" method="post">
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
                        @if(count($data)>0)
                        @foreach($data as $dat)
                        <input type="hidden" name="id" value="{{$dat->Cid}}">
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="Complianceid" style="font-size:13px;">Compliance id</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="Complianceid" style="font-size:13px;" name="Complianceid" value="{{$dat->auth_id}}">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="authority" style="font-size:13px;">Regulatory authority</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="authority" style="font-size:13px;" name="authority" value="{{$dat->authority}}" placeholder="Regulatory authority">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="issue" style="font-size:13px;">Legislative issue</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="issue" style="font-size:13px;" name="issue" value="{{$dat->authority}}" placeholder="Enter legislative issue">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="version" style="font-size:13px;">Version / year / dated</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="version" style="font-size:13px;" name="version" value="{{$dat->version}}" placeholder="Enter version">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="regulation" style="font-size:13px;">Act or regulation</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="regulation" style="font-size:13px;" name="regulation" value="{{$dat->regulation}}" placeholder="Enter act or regulation">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="owner" style="font-size:13px;">Process owner</label>
                            <div class="col-sm-12">
                                <select class="form-control" id="select_owner" style="font-size:13px;" name="owner" value="{{$dat->owner}}" style="width: 100%;" data-placeholder="Choose..">
                                    @if(count($mn)> 0 )
                                    @foreach($mn as $value)
                                    <option value="{{$value->id}}" style="font-size:13px;">{{ $value->departmentname }}</option><br>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98%;">
                            <label class="col-sm-12 col-form-label" for="requirement" style="font-size:13px;">Requirement</label>
                            <div class="col-sm-12">
                                <textarea type="text" class="form-control" id="requirement" style="font-size:13px;" name="requirement" placeholder="Enter requirement">{{$dat->requirement}}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98%;">
                            <label class="col-sm-12 col-form-label" for="aspect" style="font-size:13px;">Related aspect/ risk number</label>
                            <div class="col-sm-12">
                                <textarea type="text" class="form-control" id="aspect" style="font-size:13px;" name="aspect" placeholder="Enter related aspect/ risk number">{{$dat->aspect}}</textarea>
                            </div>
                        </div>



                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="person_control" style="font-size:13px;">Person responsible for requirement/ control</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="person_control" style="font-size:13px;" name="person_control" value="{{$dat->person_control}}" readonly>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="person_monitor" style="font-size:13px;">Person responsible for monitoring</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="person_monitor" style="font-size:13px;" name="person_monitor" value="{{$dat->person_monitor}}" readonly>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Target date</label>
                            <div class="col-sm-12">
                                <input type="Date" class="form-control" id="title" style="font-size:13px;" name="duedate" value="{{ $dat->duedate }}">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Review frequency </label>
                            <div class="col-sm-12">
                                <select class="form-control" name="frequency" required="true" style="font-size:13px;">
                                    <option value="Quarterly" style="font-size:13px;">Quarterly</option><br>
                                    <option value="Half yearly" style="font-size:13px;">Half yearly</option><br>
                                    <option value="Yearly" style="font-size:13px;">Yearly</option><br>
                                </select>

                            </div>
                        </div>

                        <input type="hidden" class="form-control" id="addeddate" name="addeddate" value="{{ date('Y-m-d') }}">

                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:89%;">ADD</button>
                            </div>
                        </div>
                        @endforeach
                        @endif
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
            $('#sel_control').select2({});
        });
        $(document).ready(function() {
            $('#sel_monitor').select2({});
        });

    </script>

</html>
@endsection
