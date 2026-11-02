@extends('layouts.backend')
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Context Management</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Closure Review</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="{{ route('user.context.changelogreg') }}">Back</a>
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
                    <form class="mb-5" action="{{--route('closreviewcontext_post')--}}" method="post">
                        {{ csrf_field() }}
                        @if($dat)
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="Contextid">context Id</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="Contextid" name="Contextid" value="{{$dat->crid}}" Readonly="">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="Title">title</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="Title" name="Title" value="{{$dat->title}}" Readonly="">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="Category">Category</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="Category" style="font-size: 13px;" name="Category" value="{{$changeCategories}}" Readonly="">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="Scope">scope</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="Scope" style="font-size: 13px;" name="Scope" value="{{$dat->scope}}" Readonly="">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="reason">reason</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="reason" style="font-size: 13px;" name="reason" value="{{$dat->reason}}" Readonly="">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="benefits">benefits</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="benefits" style="font-size: 13px;" name="benefits" value="{{$dat->benefits}}" Readonly="">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="impacts">Impacts</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="impacts" style="font-size: 13px;" name="impacts" value="{{$dat->impacts}}" Readonly="">

                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="priority">Priority</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="priority" style="font-size: 13px;" name="priority" value="{{$dat->priorityDetails ? $dat->priorityDetails->name : $dat->priority}}" Readonly="">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department">Department</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="department" style="font-size: 13px;" name="department" value="{{$dat->departmentDetails ? $dat->departmentDetails->departmentname : $dat->department }}" Readonly="">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="initiator">initiator</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="initiator" name="initiator" value="{{$dat->initiatorBy ? $dat->initiatorBy->name : $dat->initiator}}" Readonly="">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="id" value="{{ $dat->id}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 text-center">
                                <a href="{{ route('user.context.closed', ['id' => $dat->id])}}" style="font-size: 13px;" class="btn btn-primary">Close Change Request</a>
                                <a href="{{ route('user.context.needMoreAction', ['id' => $dat->id])}}" style="font-size: 13px;" class="btn btn-primary">Need More Action</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- END Horizontal -->
@endsection
