@extends('layouts.multiselect')
@section('content')

<style>
    .breadcrumb>li+li:before {
        display: none !important;
    }

    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>

    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
            <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item font-w700">
                        <a href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{$type}}" class="text-black">
                            Objectives
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Change Responsible Person
                    </li>
                </ol>
            </nav>
            <div class="block-content block-content-full">
                @if(Session::has('msg'))
                    <div class="alert alert-success">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                @endif
                @if(Session::has('errmsg'))
                    <div class="alert alert-danger">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('errmsg')!!}
                    </div>
                @endif
                <div class="row">
                 <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{url('/user/objectives/updateresponsibleperson/'. $type)}}" method="POST" onsubmit="return validation()">
                            {{csrf_field()}}

                            @if(count($errors) >0 )
                            @foreach($errors->all() as $error)
                            <div class='alert alert-warning'>
                                {{ $error }}
                            </div>
                            @endforeach
                            @endif
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="objid">Objectives ID</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="objid" name="objid" readonly value="{{$datas->objective_id}}">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department">Department</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="department" name="department" readonly value="{{$datas->dep ? $datas->dep->departmentname : '-'}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if($type== 'divisional' || $type == 'subdivisional')
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Division</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" readonly value="{{$datas->division ? $datas->division->name : ''}}">
                                    </div>
                                </div>
                                @endif
                                @if($type == 'subdivisional')
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Sub-Division</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" readonly value="{{$datas->area ? $datas->area->name : ''}}">
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="objectives">Objective name</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="objectives" style="font-size: 13px;"readonly name="objectives" value="{{$datas->objectives}}">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="responsibility">
                                        Responsible Person <span style="color:red;">*</span>
                                    </label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" name="responsibility[]" required  style="font-size:13px;width:100%;" data-placeholder="Choose Responsible Persons" multiple>
                                        @foreach($users as $value)
                                            <option value="{{$value->email}}" style="font-size:13px;" {{ in_array($value->name, explode(',', $datas->responsibility)) || in_array($value->email, explode(',', $datas->responsibility)) ? 'selected' : ''}}> {{$value->name}}</option>
                                        @endforeach
                                        {{-- @php
                                            $check = \App\User::where('email',$datas->responsibility)->first();
                                        @endphp
                                        @if($check)
                                            @foreach($users as $user)
                                            <option value="{{$user->email}}" style="font-size:13px;" {{ $user->email == $datas->responsibility ? 'selected' : ''}}> {{$user->name}}</option>
                                            @endforeach
                                        @else
                                            @foreach($users as $user)
                                            <option value="{{$user->email}}" style="font-size:13px;" {{ $user->name == $datas->responsibility ? 'selected' : ''}}> {{$user->name}}</option>
                                            @endforeach
                                        @endif                                                                --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="objective_id" value="{{ $datas->Oid}}">

                            <div class="row">
                                <div class="col-sm-12 text-right form-group">
                                    <button type="submit" class="btn btn-primary" style="font-size: 13px;" onclick="return confirm('Do you want to proceed?')">Update</button>
                                </div>
                            </div>
                        </form>
                        <!-- END Form Horizontal - Default Style -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->
    </div>
    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">OPTIONS</h3>
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{Session::get('year')}}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/achieved">
                                    <div class="block-content text-center" style="height:130px">

                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Achieved<br>
                                        </p>
                                    </div>
                                </a>

                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/notachieved">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-times-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Not Achieved
                                        </p>
                                    </div>
                                </a>

                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <br>
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/objreport" style="margin-top:-18px">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Objective report
                                        </p>
                                    </div>
                                </a>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->

@endsection
