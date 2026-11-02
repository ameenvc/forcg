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
                                    <li class="breadcrumb-item">Audit Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Edit Responsibility</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/auditmgmt">Back</a>
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
                                    <form class="mb-5" enctype="multipart/form-data" action="{{url('/user/actionsystem/submitresp')}}" method="post" files="true" >
                                        {{csrf_field()}}

                                            @if(count($data)>0)
                                                @foreach($data as $dat)
                                                    <?php $rr=$dat->id;
                                                    $dept = DB::table('departments')->where('id',$dat->department)->value('departmentname');
                                                     ?>
                                                    <input type="hidden" name="id" value="{{$dat->id}}">
                                                    @if(Session::has('msg'))
                                                 <div class="alert alert-primary">
                                                    <a class="close" data-dismiss="alert">×</a>
                                                    {!!Session::get('msg')!!}
                                                </div>
                                                  @endif

                                                  <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label for="department" class="col-sm-12 col-form-label" style="font-size:13px;">Department</label>
                                                    <div class="col-sm-12">
                                                        <select class="form-control" name = "department" id="department" style="font-size:13px;" required="true" placeholder="Choose a Department">
                                                            <option value="1" style="font-size:13px;"> {{$dept }}</option></br>
                                                            @if(count($departments)>0)
                                                                @foreach($departments as $dep)
                                                                @if($dat->department != $dep->departmentname)
                                                                    <option value=" {{$dep->id}}" style="font-size:13px;"> {{$dep->departmentname}}</option></br>
                                                                    @endif
                                                                     @endforeach
                                                            @endif
                                                        </select> </div>
                                                </div>

                                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                        <label for="type" class="col-sm-12 col-form-label" style="font-size:13px;">Type</label>
                                                        @php
                                                            $type = App\Type::where('order', $dat->type)->first();
                                                            $type_name  = $type ? $type->name : '';
                                                        @endphp
                                                        <div class="col-sm-12">
                                                            <input type="text" class="form-control" id="type" name="type" style="font-size:13px;" value="{{$type_name}}" readonly="" >
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                        <label for="title" class="col-sm-12 col-form-label" style="font-size:13px;">Title</label>
                                                        <div class="col-sm-12">
                                                            <input type="text" class="form-control" id="title" style="font-size:13px;" name="title" value="{{$dat->title}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                        <label for="date" class="col-sm-12 col-form-label" style="font-size:13px;">Due&nbsp;Date</label>
                                                        <div class="col-sm-12">
                                                            <input type="date" class="js-datepicker form-control"  style="font-size:13px;" id="date" name="duedate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{$dat->duedate}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                        <div class="col-sm-12">
                                                            <label for="impl_status" style="font-size:13px;">Implementation Status</label>
                                                            <input  type="text" class="form-control" id="impl_status" style="font-size:13px;" name="impl_status" value="{{$dat->impl_status}}" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                    <div class="col-sm-12">
                                                        <label for="impl_status" style="font-size:13px;">Responsibility</label>
                                                        <select class="js-select2 form-select w-100" name="resp" required="true" style="font-size:13px;">
                                                            <option value="0" style="font-size:13px;" selected disabled>Choose responsibility</option></br>
                                                            @if(count($user)>0)
                                                                @foreach($user as $u)
                                                                    {{-- @if($dat->responsibility != $u->email) --}}
                                                                    <option value="{{$u->email}}" style="font-size:13px;" {{ $dat->responsibility === $u->email ? 'selected' : '' }}>{{$u->name}}</option></br>
                                                                    {{-- @endif --}}
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                <div class="col-sm-8 ml-auto">
                                                    <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:63%;"  onclick="return confirm('Do you want to proceed ? ')">Submit</button>
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
                        <h3 class="block-title">Options</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row gutters-tiny">
                        <!-- <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/actionsystem/addissue">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Add Issue
                                                </p>
                                            </div>
                                        </a>

                                    </div> -->
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/auditmgmt">
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
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/folders">
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
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/openissue">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    My Open NCRs
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/actionsystem/analysis">
                                            <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>


                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Audit Schedule Analysis
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



    </div>            <!-- END Apps Modal -->

    </body>




</html>
@endsection
