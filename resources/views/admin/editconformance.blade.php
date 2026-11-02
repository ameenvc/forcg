@extends('admin.datatable')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit Conformance</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/viewCategories">Back</a>
                        </li>
                    </ol>
                </nav>


            </div>
            <div class="block-content block-content-full" style="width:90%;">
                <div class="row">
                    <div class="col-lg-10 col-sm-10">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" method="POST" action="{{route('updateauditconformance')}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                           
                            <div class="row mb-4">
                                <div class="col-sm-3 ">
                                    <label class="col-form-label" style="font-size: 13px;" for="stage">Stage<span style="color:red">*</span></label>
                                    
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="stage" value="{{$data->stage}}" style="font-size: 13px;" required class="form-control">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="classification" style="font-size: 13px;">Classification<span style="color:red">*</span></label>
                                    
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control"style="font-size:13px;" id="classification" name="classification" required>{{$data->classification}}</textarea>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="issue" style="font-size: 13px;">Description<span style="color:red">*</span></label>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control"style="font-size:13px;" id="descp" name="descp" required>{{$data->description}}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="hidden" name="conformanceid" value="{{$data->id}}"/>
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
