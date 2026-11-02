@extends('layouts.multiselect')
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
                        <li class="breadcrumb-item">Compliance Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Revert</a>
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
                        <form class="mb-5" action="{{ route('reverting')}}" enctype="multipart/form-data" method="post" files="true">
                            {{csrf_field()}}

                            @if(count($data)>0)
                            @foreach($data as $dat)

                            <input class="form-control" type="hidden" name="approvedby" value="{{$approvedby}}">

                            <div class="form-group" style="display:inline-block;width:49%">
                                <label class="col-sm-12 col-form-label" for="Cid" style="font-size:13px;">Compliance id</label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="Cid" style="font-size:13px;" value="{{$dat->Cid}}" readonly="">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="authority" style="font-size:13px;">Regulatory authority</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="authority" style="font-size:13px;" value="{{$dat->authority}}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="issue" style="font-size:13px;">Legislative issue</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="issue" style="font-size:13px;" value="{{$dat->issue}}" readonly="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="version" style="font-size:13px;">Version / year / dated</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="version" style="font-size:13px;" value="{{$dat->version}}" readonly="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="regulation" style="font-size:13px;">Act or regulation</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="regulation" style="font-size:13px;" value="{{$dat->regulation}}" readonly="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="requirement" style="font-size:13px;">Requirement</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="requirement" style="font-size:13px;" value="{{$dat->requirement}}" readonly="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="aspect" style="font-size:13px;">Related aspect/risk number</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="aspect" style="font-size:13px;" value="{{$dat->aspect}}" readonly="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="owner" style="font-size:13px;">Process owner</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="owner" style="font-size:13px;" value="{{$dat->dep ? $dat->dep->departmentname : '-'}}" readonly="">
                                    </div>
                                </div>
                            </div>



                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="person_control" style="font-size:13px;">Person responsible for requirement/control</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="sel_control" style="font-size:13px;" name="person_control[]" multiple>
                                        @if(count($users)> 0 )
                                        @foreach($users as $value)
                                        <option value=" {{$value->name}}" style="font-size:13px;"> {{ $value->name}}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="person_monitor" style="font-size:13px;">Person responsible for monitoring</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="sel_monitor" style="font-size:13px;" name="person_monitor[]" multiple>
                                        @if(count($users)> 0 )
                                        @foreach($users as $value)
                                        <option value=" {{$value->name}}" style="font-size:13px;"> {{ $value->name}}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="comp_status" style="font-size:13px;">Process stage</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="comp_status" style="font-size:13px;" value="{{$dat->pro_status}}" readonly="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:98.5%;">
                                <label class="col-sm-12 col-form-label" for="comp_status" style="font-size:13px;">Reason For Reverting</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <textarea rows="8" class="form-control" name="reason" required></textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-sm-8 ml-auto">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:86%">Revert</button>
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
        <!--</div>-->
        <!-- END Horizontal -->
        <!-- END Page Content -->

        <!-- END Main Container -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        {{-- <script src="{{asset('js/plugins/select2/js/select2.full.min.js')}}"></script> --}}
        <script type="text/javascript">
            $('select').select2();

        </script>

        <!-- END Footer -->
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
        <!-- END Apps Modal -->
    </div>
    <!-- END Page Container -->


</body>

</html>
@endsection
{{-- @section('js_after') --}}

{{-- @endsection --}}

