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
<div class="content">


    <!-- Horizontal -->
    <div class="block block-rounded">

        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-10">
                    @if(count($errors)>0)
                    @foreach($errors->all() as $error)
                    <div class="alert alert-warning">
                        {{$error}}
                    </div>
                    @endforeach
                    @endif
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" action="{{url('/user/compliance/editcompliance')}}" method="post" files="true">
                        {{csrf_field()}}
                        @if(count($data)>0)
                        @foreach($data as $dat)

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="Cid" style="font-size:13px;">Compliance id</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="Cid" style="font-size:13px;" value="{{$dat->Cid}}" readonly="">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="authority" style="font-size:13px;">Regulatory authority</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="authority" style="font-size:13px;" value="{{$dat->authority}}" readonly>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="issue" style="font-size:13px;">Legislative issue</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="issue" style="font-size:13px;" value="{{$dat->issue}}" readonly="">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="version" style="font-size:13px;">Version / year / dated</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="version" style="font-size:13px;" value="{{$dat->version}}" readonly="">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="regulation" style="font-size:13px;">Act or regulation</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="regulation" style="font-size:13px;" value="{{$dat->regulation}}" readonly="">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="requirement" style="font-size:13px;">Requirement</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="requirement" style="font-size:13px;" value="{{$dat->requirement}}" readonly="">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="aspect" style="font-size:13px;">Related aspect/risk number</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="aspect" style="font-size:13px;" value="{{$dat->aspect}}" readonly="">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="owner" style="font-size:13px;">Process owner</label>
                            <div class="col-sm-12">
                                <select class="form-control" id="select_owner" name="owner" style="font-size:13px;">
                                    @if(count($mn)> 0 )
                                    @foreach($mn as $value)
                                    <option value="{{$value->id}}" style="font-size:13px;">{{ $value->departmentname }}</option><br>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="responsibility" style="font-size:13px;">Responsibility</label>
                            <div class="col-sm-12">
                                <select class="form-control" name = "responsibility" required="true" style="font-size:13px;" id="responsibility">
                                    <option value=" {{$dat->responsibility}}"> {{$dat->responsibility }}</option></br>
                                    @if(count($users)> 0 )
                                        @foreach($users as $value)
                                            @if($value->email != $dat->responsibility)
                                                <option value=" {{$value->email}}"> {{ $value->email }}</option><br>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:87%">Agree</button>
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
