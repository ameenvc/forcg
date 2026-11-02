@extends('admin.datatable')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit Root Cause and Corrective Action</h3>
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
                        <form class="mb-5" method="POST" action="{{route('updateauditrootcorrective')}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                           
                            <div class="row mb-4">
                                <div class="col-sm-3 ">
                                    <label class="col-form-label" style="font-size: 13px;" for="stage">Title<span style="color:red">*</span></label>
                                    
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="title" value="{{$data->title}}" style="font-size: 13px;" required class="form-control">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="rootcause" style="font-size: 13px;">Root Cause<span style="color:red">*</span></label>
                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control"style="font-size:13px;" id="root_cause" name="root_cause" required>{{$data->root_cause}}</textarea>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                <label class="col-form-label" for="corrective_action" style="font-size: 13px;">Corrective Action<span style="color:red">*</span></label>

                                </div>
                                <div class="col-sm-9">
                                    <textarea class="form-control"style="font-size:13px;" id="corrective_action" name="corrective_action" required>{{$data->corrective_action}}</textarea>

                                </div>
                                    
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="hidden" name="rootcorrectiveid" value="{{$data->id}}"/>
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
