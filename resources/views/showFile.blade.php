@extends('layouts.simple')
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Upload files in laravel</li>
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

                    <form class="mb-5" action="{{ route('fileUpload') }}" enctype="multipart/form-data" method="POST" files="true">
                        @csrf
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;" for="file"></label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" style="font-size:13px;" id="file" name="file">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary" style="font-size:13px; margin-left:84%; ">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- END Apps Modal -->
@endsection
